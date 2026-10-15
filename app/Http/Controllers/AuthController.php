<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the split-screen web login page.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Process authentication.
     * Supports: NIM, Email, or Username + Password.
     * Ready for future Student API integration.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Silakan masukkan NIM, Email, atau Username Anda.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');

        // 1. Check local database (supports NIM, Email, or Username)
        $user = User::where('email', $loginInput)
            ->orWhere('nim', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        // 2. Placeholder for Future Student API:
        // If not found in local DB or if external student verification is active:
        // $apiStudent = $this->verifyWithStudentApi($loginInput, $password);
        // if ($apiStudent) { $user = ... }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'login' => 'NIM / Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('login');
    }

    /**
     * Quick demo autofill / login helper for testing during development.
     */
    public function quickLogin($role)
    {
        $query = User::query();

        if ($role === 'kajur') {
            $user = $query->where('role', 'kajur')->where('department', 'Teknik Informatika')->first();
        } elseif ($role === 'kajur_si') {
            $user = $query->where('role', 'kajur')->where('department', 'Sistem Informasi')->first();
        } elseif ($role === 'kemahasiswaan') {
            $user = $query->whereIn('role', ['kemahasiswaan', 'admin'])->first();
        } else {
            $user = $query->where('role', $role)->first();
        }

        if ($user) {
            Auth::login($user);
            return $this->redirectBasedOnRole($user);
        }

        return redirect()->route('login')->with('error', "Akun demo untuk role {$role} tidak ditemukan.");
    }

    /**
     * Logout and invalidate session.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Helper to redirect authenticated user to their role-specific dashboard.
     */
    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'kemahasiswaan', 'admin' => redirect()->route('kemahasiswaan.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            'kajur', 'kaprodi' => redirect()->route('kajur.dashboard'),
            'dekan' => redirect()->route('dekan.dashboard'),
            'warek' => redirect()->route('warek.dashboard'),
            default => redirect('/'),
        };
    }

    /**
     * Stub for future Student API integration.
     * When ready, implement HTTP client request here.
     */
    protected function verifyWithStudentApi(string $nim, string $password): ?array
    {
        // Future implementation:
        // $response = Http::post('https://api.kampus.ac.id/auth/mahasiswa', ['nim' => $nim, 'password' => $password]);
        // if ($response->successful()) { return $response->json(); }
        return null;
    }
}
