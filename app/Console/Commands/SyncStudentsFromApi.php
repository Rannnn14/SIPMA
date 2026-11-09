<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Student;

class SyncStudentsFromApi extends Command
{
    /**
     * Nama dan signature perintah console.
     */
    protected $signature = 'students:sync {--limit= : Maksimal jumlah data yang disinkronkan} {--page=1 : Mulai dari halaman tertentu}';

    /**
     * Deskripsi perintah console.
     */
    protected $description = 'Sinkronisasi massal seluruh data mahasiswa dari Bridge API kampus ke database lokal';

    /**
     * Jalankan perintah.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi data mahasiswa di latar belakang...');

        $baseUrl = rtrim(config('services.bridge_api.url', env('BRIDGE_API_URL', 'https://bridge.uinssc.ac.id')), '/');
        $timeout = (int) config('services.bridge_api.timeout', 15);
        $token   = config('services.bridge_api.token') ?? env('BRIDGE_API_TOKEN');

        // Jika token belum disetel di .env, coba login otomatis jika ada kredensial admin
        if (!$token) {
            $adminUser = env('BRIDGE_API_ADMIN_USERNAME');
            $adminPass = env('BRIDGE_API_ADMIN_PASSWORD');

            if ($adminUser && $adminPass) {
                $this->line('Mencoba autentikasi otomatis via akun admin API...');
                $token = $this->authenticateAdmin($baseUrl, $adminUser, $adminPass, $timeout);
            }
        }

        $page = (int) $this->option('page');
        $limitTotal = $this->option('limit') ? (int) $this->option('limit') : null;
        $perPage = 50;

        $totalSaved = 0;
        $hasMore = true;

        while ($hasMore) {
            $this->line("Mengambil data halaman {$page}...");

            $endpoint = "{$baseUrl}/api/v2/admin/mahasiswa";
            $response = null;

            try {
                $client = Http::withoutVerifying()
                    ->timeout($timeout)
                    ->withHeaders(['Accept' => 'application/json']);

                if ($token) {
                    $client = $client->withToken($token);
                }

                $response = $client->get($endpoint, [
                    'page'     => $page,
                    'limit'    => $perPage,
                    'per_page' => $perPage,
                ]);
            } catch (\Exception $e) {
                $this->error("Koneksi ke Bridge API gagal: " . $e->getMessage());
                Log::error("[SyncStudentsFromApi] Koneksi gagal ke {$endpoint}: " . $e->getMessage());
                break;
            }

            if (!$response || !$response->successful()) {
                $status = $response ? $response->status() : 'No Response';
                $body = $response ? $response->body() : '';

                $this->warn("Gagal mengambil data halaman {$page} (HTTP Status {$status}).");

                if ($response && ($response->status() === 401 || str_contains($body, 'Token tidak valid'))) {
                    $this->error("Akses ditolak: Endpoint {$endpoint} memerlukan Token API atau akun admin di file .env (BRIDGE_API_TOKEN).");
                }

                Log::warning("[SyncStudentsFromApi] Gagal pada halaman {$page}: {$body}");
                break;
            }

            $json = $response->json();
            $items = $json['data'] ?? $json['mahasiswa'] ?? $json['users'] ?? null;

            // Jika response berupa array langsung di root
            if (is_array($json) && isset($json[0]) && is_array($json[0])) {
                $items = $json;
            }

            if (empty($items) || !is_array($items)) {
                $this->line('Tidak ada data tambahan ditemukan dari API.');
                break;
            }

            $countInPage = 0;
            foreach ($items as $item) {
                if (!is_array($item)) continue;

                $nim = $item['nim'] ?? $item['username'] ?? null;
                $name = $item['nama'] ?? $item['name'] ?? null;

                if (!$nim || !$name) continue;

                $prodi = $item['prodi'] ?? $item['program_studi'] ?? $item['jurusan'] ?? '-';
                $entry = $item['angkatan'] ?? $item['entry_year'] ?? $this->guessEntryYear((string) $nim);
                $phone = $item['phone'] ?? $item['no_hp'] ?? $item['telepon'] ?? null;

                Student::updateOrCreate(
                    ['nim' => trim((string) $nim)],
                    [
                        'name'          => trim((string) $name),
                        'study_program' => trim((string) $prodi),
                        'entry_year'    => (int) $entry,
                        'phone'         => $phone ? trim((string) $phone) : null,
                    ]
                );

                $totalSaved++;
                $countInPage++;

                if ($limitTotal && $totalSaved >= $limitTotal) {
                    $hasMore = false;
                    break;
                }
            }

            $this->info("Berhasil menyimpan/memperbarui {$countInPage} data dari halaman {$page}. (Total saat ini: {$totalSaved})");

            // Jika jumlah data di halaman ini kurang dari perPage, berarti sudah halaman terakhir
            if ($countInPage < $perPage) {
                $hasMore = false;
            } else {
                $page++;
            }
        }

        $this->info("Sinkronisasi selesai. Total {$totalSaved} data mahasiswa tersimpan di sistem.");
        Log::info("[SyncStudentsFromApi] Sinkronisasi selesai. Total {$totalSaved} data mahasiswa tersimpan.");

        return 0;
    }

    /**
     * Login admin ke Bridge API untuk mendapatkan token Bearer.
     */
    protected function authenticateAdmin(string $baseUrl, string $username, string $password, int $timeout): ?string
    {
        try {
            $res = Http::withoutVerifying()
                ->timeout($timeout)
                ->asForm()
                ->withHeaders(['Accept' => 'application/json'])
                ->post("{$baseUrl}/api/v2/portal/login", [
                    'username' => $username,
                    'password' => $password,
                ]);

            if ($res->successful()) {
                $data = $res->json();
                return $data['token']
                    ?? $data['data']['token']
                    ?? $data['access_token']
                    ?? $data['data']['access_token']
                    ?? null;
            }
        } catch (\Exception $e) {
            Log::debug('[SyncStudentsFromApi] Gagal login admin otomatis: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Prediksi tahun angkatan dari 2 digit pertama NIM.
     */
    protected function guessEntryYear(string $nim): int
    {
        if (preg_match('/^(\d{2})/', $nim, $matches)) {
            $yy = (int) $matches[1];
            if ($yy >= 50) {
                return 1900 + $yy;
            }
            return 2000 + $yy;
        }

        return (int) date('Y');
    }
}
