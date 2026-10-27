<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    private const MANAGED_ROLES = ['kajur', 'wadek3', 'warek'];

    public function updateOwnName(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $request->user()->update(['name' => trim($data['name'])]);

        return back()->with('success', 'Nama profil berhasil diperbarui.');
    }

    public function index(Request $request)
    {
        $query = User::query()->whereIn('role', self::MANAGED_ROLES);

        if ($request->filled('role') && in_array($request->role, self::MANAGED_ROLES, true)) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('faculty', 'like', "%{$search}%");
            });
        }

        $accounts = $query->orderByRaw("CASE role WHEN 'warek' THEN 0 WHEN 'wadek3' THEN 1 WHEN 'kajur' THEN 2 ELSE 3 END")
            ->orderBy('faculty')->orderBy('department')->orderBy('name')
            ->paginate(20)->withQueryString();

        $counts = User::query()->whereIn('role', self::MANAGED_ROLES)
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')->pluck('total', 'role');

        $faculties = Faculty::with('departments')->orderBy('name')->get();
        $departments = Department::with('faculty')->orderBy('name')->get();

        return view('accounts.index', compact('accounts', 'counts', 'faculties', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedAccount($request);

        $user = DB::transaction(function () use ($data) {
            [$faculty, $department] = $this->resolveOrganization($data);

            // Restore a soft-deleted managed account when its unique identifiers are reused.
            $deletedAccount = User::onlyTrashed()
                ->whereIn('role', self::MANAGED_ROLES)
                ->where(function ($query) use ($data) {
                    $query->where('email', $data['email'])
                        ->orWhere('username', $data['username']);
                })
                ->first();

            if ($deletedAccount) {
                $deletedAccount->fill([
                    'name' => $data['name'] ?? '',
                    'email' => $data['email'],
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'role' => $data['role'],
                    'faculty' => $faculty?->name,
                    'department' => $department?->name,
                    'nim' => null,
                    'student_id' => null,
                ]);
                $deletedAccount->restore();
                $deletedAccount->save();

                return $deletedAccount;
            }

            return User::create([
                'name' => $data['name'] ?? '',
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => $data['password'],
                'role' => $data['role'],
                'faculty' => $faculty?->name,
                'department' => $department?->name,
                'nim' => null,
                'student_id' => null,
            ]);
        });

        return redirect()->route('kemahasiswaan.accounts.index')
            ->with('success', "Akun {$user->username} berhasil dibuat.");
    }

    public function update(Request $request, User $user)
    {
        $this->ensureManagedAccount($user);
        $data = $this->validatedAccount($request, $user);
        DB::transaction(function () use ($data, $user) {
            [$faculty, $department] = $this->resolveOrganization($data);
            $user->fill([
                'name' => $data['name'] ?? '',
                'email' => $data['email'],
                'username' => $data['username'],
                'role' => $data['role'],
                'faculty' => $faculty?->name,
                'department' => $department?->name,
            ]);
            if (!empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            if (!empty($data['password']) && config('session.driver') === 'database' && Schema::hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
        });

        return redirect()->route('kemahasiswaan.accounts.index')
            ->with('success', "Data akun {$user->username} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $this->ensureManagedAccount($user);

        DB::transaction(function () use ($user) {
            if (config('session.driver') === 'database' && Schema::hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
            $user->delete();
        });

        return redirect()->route('kemahasiswaan.accounts.index')
            ->with('success', "Akun {$user->username} berhasil dihapus.");
    }

    private function validatedAccount(Request $request, ?User $user = null): array
    {
        $roles = self::MANAGED_ROLES;
        $faculties = Faculty::query()->pluck('name')->all();
        $departments = Department::query()->pluck('name')->all();

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user?->id)->whereNull('deleted_at')],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user?->id)->whereNull('deleted_at')],
            'role' => ['required', Rule::in($roles)],
            'faculty' => ['nullable', 'string', Rule::in(array_merge($faculties, ['__new__']))],
            'department' => ['nullable', 'string', Rule::in(array_merge($departments, ['__new__']))],
            'new_faculty' => ['nullable', 'string', 'max:150'],
            'new_department' => ['nullable', 'string', 'max:150'],
            'password' => $user
                ? ['nullable', 'string', Password::min(12)->letters()->numbers(), 'confirmed']
                : ['required', 'string', Password::min(12)->letters()->numbers(), 'confirmed'],
            'password_confirmation' => ['required_with:password', 'string'],
        ]);

        $needsFaculty = in_array($data['role'], ['kajur', 'wadek3'], true);
        if ($needsFaculty && empty($data['faculty'])) {
            throw \Illuminate\Validation\ValidationException::withMessages(['faculty' => 'Fakultas wajib dipilih atau ditambahkan.']);
        }
        if (($data['faculty'] ?? null) === '__new__' && empty(trim($data['new_faculty'] ?? ''))) {
            throw \Illuminate\Validation\ValidationException::withMessages(['new_faculty' => 'Nama fakultas baru wajib diisi.']);
        }
        if ($data['role'] === 'kajur' && empty($data['department'])) {
            throw \Illuminate\Validation\ValidationException::withMessages(['department' => 'Jurusan wajib dipilih atau ditambahkan.']);
        }
        if ($data['role'] === 'kajur' && ($data['department'] ?? null) === '__new__' && empty(trim($data['new_department'] ?? ''))) {
            throw \Illuminate\Validation\ValidationException::withMessages(['new_department' => 'Nama jurusan baru wajib diisi.']);
        }

        return $data;
    }

    private function resolveOrganization(array $data): array
    {
        $faculty = null;
        if (in_array($data['role'], ['kajur', 'wadek3'], true)) {
            $faculty = ($data['faculty'] ?? null) === '__new__'
                ? Faculty::firstOrCreate(['name' => trim($data['new_faculty'])])
                : Faculty::where('name', $data['faculty'])->firstOrFail();
        }

        $department = null;
        if ($data['role'] === 'kajur') {
            if ($data['department'] === '__new__') {
                $departmentName = trim($data['new_department']);
                if (Department::where('name', $departmentName)->exists()) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['new_department' => 'Nama jurusan tersebut sudah digunakan.']);
                }
                $department = Department::create(['faculty_id' => $faculty->id, 'name' => $departmentName]);
            } else {
                $department = Department::where('faculty_id', $faculty->id)->where('name', $data['department'])->firstOrFail();
            }
        }

        return [$faculty, $department];
    }

    private function ensureManagedAccount(User $user): void
    {
        abort_unless(in_array($user->role, self::MANAGED_ROLES, true), 404);
    }

}
