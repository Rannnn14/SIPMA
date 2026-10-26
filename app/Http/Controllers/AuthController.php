<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
     * Proses autentikasi dengan alur:
     *
     * MAHASISWA:
     *   1. Coba Bridge API v2 (POST /api/v2/portal/login) — sumber kebenaran utama
     *   2. Jika Bridge berhasil → buat/perbarui akun lokal otomatis, login
     *   3. Jika Bridge gagal/timeout → tolak login (mahasiswa HARUS via Bridge)
     *
     * STAF INTERNAL (kemahasiswaan, kajur, dekan, warek, admin):
     *   1. Cek DB lokal dengan password yang di-hash
     *   2. Jika cocok → login langsung
     *
     * Alur deteksi:
     *   - Jika input terdeteksi sebagai NIM (numerik panjang) → langsung coba Bridge
     *   - Jika tidak ada di DB lokal → coba Bridge (mungkin mahasiswa baru)
     *   - Jika ada di DB lokal sebagai mahasiswa → coba Bridge (tanpa fallback password lokal)
     *   - Jika ada di DB lokal sebagai staf → cek password lokal saja
     */
    public function login(Request $request)
    {
        // Validasi CAPTCHA terlebih dahulu
        $captchaError = $this->validateCaptcha($request);
        if ($captchaError) {
            return back()->withErrors(['captcha' => $captchaError])->onlyInput('login');
        }

        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required'    => 'Silakan masukkan NIM, Email, atau Username Anda.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->input('login'));
        $password   = $request->input('password');

        // 1. Cari user di database lokal
        $localUser = User::where('email', $loginInput)
            ->orWhere('nim', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        // --- JALUR STAF INTERNAL ---
        // Jika user ada di DB lokal dan bukan mahasiswa → autentikasi lokal saja
        if ($localUser && !$localUser->isMahasiswa()) {
            if (Hash::check($password, $localUser->password)) {
                Auth::login($localUser, $request->boolean('remember'));
                $request->session()->regenerate();
                return $this->redirectBasedOnRole($localUser);
            }
            // Password staf salah
            return back()->withErrors([
                'login' => 'Email / Username atau kata sandi yang Anda masukkan tidak sesuai.',
            ])->onlyInput('login');
        }

        // --- JALUR MAHASISWA (via Bridge API) ---
        // Untuk mahasiswa (ada di DB lokal sebagai mahasiswa) ATAU belum ada sama sekali,
        // selalu coba Bridge API terlebih dahulu sebagai sumber kebenaran utama.
        $bridgeResult = $this->loginViaBridgeApi($loginInput, $password);

        if ($bridgeResult) {
            $profile = $bridgeResult['profile'] ?? [];

            // Tentukan nilai kolom dari profil Bridge
            $bridgeName    = $profile['nama']     ?? ($profile['name'] ?? ($localUser?->name ?? $loginInput));
            $bridgeEmail   = $profile['email']    ?? ($profile['email_kampus'] ?? null);
            $bridgeNim     = $profile['nim']      ?? ($profile['username'] ?? $loginInput);
            $bridgeProdi   = $profile['prodi']    ?? ($profile['program_studi'] ?? ($profile['jurusan'] ?? null));
            $bridgeFaculty = $profile['fakultas'] ?? ($profile['faculty'] ?? null);

            // Buat atau perbarui akun lokal mahasiswa berdasarkan data Bridge
            if ($localUser && $localUser->isMahasiswa()) {
                // Update data jika berubah
                $updateData = [
                    'name'     => $bridgeName ?: $localUser->name,
                    'email'    => $bridgeEmail ?: $localUser->email,
                    'nim'      => $bridgeNim   ?: $localUser->nim,
                    'password' => Hash::make($password), // Sinkronisasi password lokal
                ];
                if ($bridgeProdi) {
                    $updateData['department'] = $bridgeProdi;
                }
                if ($bridgeFaculty) {
                    $updateData['faculty'] = $bridgeFaculty;
                }
                $localUser->update($updateData);
                $user = $localUser->fresh();
            } else {
                // Buat akun mahasiswa lokal baru berdasarkan data Bridge
                $user = User::updateOrCreate(
                    ['nim' => $bridgeNim ?: $loginInput],
                    [
                        'name'       => $bridgeName,
                        'email'      => $bridgeEmail ?: ($bridgeNim . '@mahasiswa.uinssc.ac.id'),
                        'username'   => $bridgeNim ?: $loginInput,
                        'nim'        => $bridgeNim ?: $loginInput,
                        'department' => $bridgeProdi,
                        'faculty'    => $bridgeFaculty,
                        'password'   => Hash::make($password),
                        'role'       => 'mahasiswa',
                    ]
                );
            }

            // Simpan token Bridge & profil ke session
            session([
                'bridge_token'   => $bridgeResult['token'],
                'bridge_profile' => $profile,
            ]);

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return $this->redirectBasedOnRole($user);
        }

        // Bridge API gagal atau kredensial tidak valid. Mahasiswa selalu harus
        // terautentikasi oleh Bridge; password lokal tidak boleh menjadi fallback.
        return back()->withErrors([
            'login' => 'Login mahasiswa gagal. Pastikan NIM dan kata sandi akun portal kampus Anda benar, lalu coba lagi.',
        ])->onlyInput('login');
    }

    /**
     * Logout dan invalidasi session.
     * Jika ada Bridge token aktif, revoke dari Bridge API juga.
     */
    public function logout(Request $request)
    {
        $bridgeToken = session('bridge_token');
        if ($bridgeToken) {
            $this->logoutFromBridgeApi($bridgeToken);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Redirect user berdasarkan role ke dashboard masing-masing.
     */
    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'kemahasiswaan', 'admin' => redirect()->route('kemahasiswaan.dashboard'),
            'mahasiswa'              => redirect()->route('mahasiswa.dashboard'),
            'kajur', 'kaprodi'      => redirect()->route('kajur.dashboard'),
            'dekan'                 => redirect()->route('dekan.dashboard'),
            'wadek3'                => redirect()->route('wadek3.dashboard'),
            'warek'                 => redirect()->route('warek.dashboard'),
            default                 => redirect('/'),
        };
    }

    // =========================================================================
    // BRIDGE API HELPERS
    // =========================================================================

    /**
     * Autentikasi via Bridge API v2 (bridge.uinssc.ac.id).
     * POST /api/v2/portal/login, lalu GET /api/v2/profil.
     *
     * @return array{token: string, profile: array|null}|null  — null jika gagal
     */
    protected function loginViaBridgeApi(string $username, string $password): ?array
    {
        $baseUrl = rtrim(config('services.bridge_api.url', env('BRIDGE_API_URL', 'https://bridge.uinssc.ac.id')), '/');
        $timeout = (int) config('services.bridge_api.timeout', 5);

        try {
            // Postman collection Bridge API v2 mengonfirmasi endpoint dan body urlencoded ini.
            $loginResponse = Http::withoutVerifying()
                ->timeout($timeout)
                ->asForm()
                ->withHeaders(['Accept' => 'application/json'])
                ->post("{$baseUrl}/api/v2/portal/login", [
                    'username' => $username,
                    'password' => $password,
                ]);

            if (!$loginResponse->successful()) {
                Log::debug('[Bridge API] Login gagal', [
                    'status' => $loginResponse->status(),
                ]);
                return null;
            }

            $loginData = $loginResponse->json() ?? [];

            // Ekstrak token dari berbagai variasi response
            $token = $loginData['token']
                ?? $loginData['data']['token']
                ?? $loginData['access_token']
                ?? $loginData['data']['access_token']
                ?? $loginData['authorisation']['token']
                ?? $loginData['authorization']['token']
                ?? null;

            // Ekstrak profil jika endpoint login ikut mengembalikannya.
            $profile = $loginData['user']
                ?? $loginData['data']['user']
                ?? $loginData['mahasiswa']
                ?? $loginData['data']['mahasiswa']
                ?? null;

            // Jika response login membungkus data langsung di root atau di 'data'
            if (!$profile && isset($loginData['data']) && is_array($loginData['data'])) {
                if (isset($loginData['data']['nim']) || isset($loginData['data']['name']) || isset($loginData['data']['username'])) {
                    $profile = $loginData['data'];
                }
            }

            // Login response collection menjamin token; profil diambil dari endpoint khusus.
            if (!$token) {
                Log::debug('[Bridge API] Login response tidak berisi token', [
                    'keys' => array_keys($loginData),
                ]);
                return null;
            }

            try {
                $profileResponse = Http::withoutVerifying()
                    ->timeout($timeout)
                    ->withToken($token)
                    ->withHeaders(['Accept' => 'application/json'])
                    ->get("{$baseUrl}/api/v2/profil");

                if ($profileResponse->successful()) {
                    $profileData = $profileResponse->json() ?? [];
                    $profile = $profileData['data']['user']
                        ?? $profileData['data']['mahasiswa']
                        ?? $profileData['user']
                        ?? $profileData['mahasiswa']
                        ?? $profileData['data']
                        ?? $profileData;
                } else {
                    Log::debug('[Bridge API] Gagal mengambil profil', [
                        'status' => $profileResponse->status(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::debug('[Bridge API] Error mengambil profil: ' . $e->getMessage());
            }

            if (!is_array($profile)) {
                $profile = [];
            }
            $profile['nim'] = $profile['nim'] ?? $profile['username'] ?? $username;
            $profile['username'] = $profile['username'] ?? $username;
            $profile['nama'] = $profile['nama'] ?? $profile['name'] ?? $username;

            return [
                'token'   => $token,
                'profile' => $profile,
            ];

        } catch (\Exception $e) {
            // Bridge API tidak tersedia / timeout / network error
            Log::debug('[Bridge API] Tidak tersedia: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Logout dari Bridge API (revoke Bearer token jika didukung).
     */
    protected function logoutFromBridgeApi(string $token): void
    {
        $baseUrl = rtrim(config('services.bridge_api.url', env('BRIDGE_API_URL', 'https://bridge.uinssc.ac.id')), '/');

        try {
            Http::withoutVerifying()
                ->timeout(3)
                ->withToken($token)
                ->withHeaders(['Accept' => 'application/json'])
                ->post("{$baseUrl}/api/v2/logout");
        } catch (\Exception $e) {
            // Abaikan error logout Bridge API
        }
    }

    // =========================================================================
    // CAPTCHA HELPERS ("Saya bukan robot" Checkbox Verification)
    // =========================================================================

    /**
     * Validasi captcha "Saya bukan robot" dari request.
     * Return pesan error string jika tidak dicentang, null jika valid.
     */
    protected function validateCaptcha(Request $request): ?string
    {
        $checked = $request->input('captcha_verified');

        if (!$checked || $checked != '1') {
            return 'Centang captcha di atas untuk melengkapi validasi.';
        }

        return null;
    }
}
