<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\User;
use App\Http\Controllers\ReportController;

class StudentLookupController extends Controller
{
    /**
     * Cari data mahasiswa berdasarkan NIM.
     * Alur:
     * 1. Cek di tabel students (database lokal).
     * 2. Cek di tabel users (mahasiswa yang pernah terdaftar).
     * 3. Jika belum ada, panggil Bridge API (https://bridge.uinssc.ac.id).
     * 4. Simpan data dari API ke tabel students lokal.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookup(Request $request)
    {
        $nim = trim((string) $request->query('nim'));

        if (!$nim) {
            return response()->json([
                'success' => false,
                'message' => 'NIM wajib diisi.',
            ], 422);
        }

        $departmentFacultyMap = ReportController::departmentFacultyMap();

        // 1. Cek di tabel students lokal
        $localStudent = Student::where('nim', $nim)->first();
        if ($localStudent) {
            [$faculty, $matchedDept] = $this->resolveFacultyAndDepartment($localStudent->study_program, $departmentFacultyMap);
            $entryYear = $localStudent->entry_year ?: $this->guessEntryYear($nim);
            $semester = $this->calculateCurrentSemester($entryYear);

            return response()->json([
                'success' => true,
                'source'  => 'database_students',
                'data'    => [
                    'nim'        => $localStudent->nim,
                    'name'       => $localStudent->name,
                    'department' => $matchedDept ?: $localStudent->study_program,
                    'faculty'    => $faculty,
                    'semester'   => $semester,
                    'phone'      => $localStudent->phone,
                    'entry_year' => $entryYear,
                ],
            ]);
        }

        // 2. Cek di tabel users lokal (jika mahasiswa sudah ada akunnya)
        $localUser = User::where('nim', $nim)->first();
        if ($localUser) {
            [$faculty, $matchedDept] = $this->resolveFacultyAndDepartment($localUser->department, $departmentFacultyMap);
            $faculty = $localUser->faculty ?: $faculty;
            $entryYear = $this->guessEntryYear($nim);
            $semester = $this->calculateCurrentSemester($entryYear);

            // Simpan ke tabel students lokal agar konsisten
            Student::updateOrCreate(
                ['nim' => $localUser->nim],
                [
                    'name'          => $localUser->name,
                    'study_program' => $matchedDept ?: ($localUser->department ?? '-'),
                    'entry_year'    => $entryYear,
                    'phone'         => $localUser->phone ?? null,
                ]
            );

            return response()->json([
                'success' => true,
                'source'  => 'database_users',
                'data'    => [
                    'nim'        => $localUser->nim,
                    'name'       => $localUser->name,
                    'department' => $matchedDept ?: $localUser->department,
                    'faculty'    => $faculty,
                    'semester'   => $semester,
                    'phone'      => $localUser->phone,
                    'entry_year' => $entryYear,
                ],
            ]);
        }

        // 3. Panggil Bridge API (https://bridge.uinssc.ac.id)
        $apiResult = $this->fetchFromBridgeApi($nim);

        if ($apiResult && !empty($apiResult['name'])) {
            $entryYear = $apiResult['entry_year'] ?? $this->guessEntryYear($nim);
            $semester = $apiResult['semester'] ?? $this->calculateCurrentSemester($entryYear);
            [$faculty, $matchedDept] = $this->resolveFacultyAndDepartment($apiResult['department'] ?? null, $departmentFacultyMap);
            $faculty = $apiResult['faculty'] ?: $faculty;
            $department = $matchedDept ?: ($apiResult['department'] ?? '-');

            // 4. Simpan data dari API ke tabel students lokal
            Student::updateOrCreate(
                ['nim' => $apiResult['nim']],
                [
                    'name'          => $apiResult['name'],
                    'study_program' => $department,
                    'entry_year'    => $entryYear,
                    'phone'         => $apiResult['phone'] ?? null,
                ]
            );

            return response()->json([
                'success' => true,
                'source'  => 'bridge_api',
                'data'    => [
                    'nim'        => $apiResult['nim'],
                    'name'       => $apiResult['name'],
                    'department' => $department,
                    'faculty'    => $faculty,
                    'semester'   => $semester,
                    'phone'      => $apiResult['phone'] ?? null,
                    'entry_year' => $entryYear,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Data mahasiswa dengan NIM {$nim} tidak ditemukan.",
        ], 404);
    }

    /**
     * Memanggil Bridge API kampus untuk mencari mahasiswa berdasarkan NIM.
     *
     * @param string $nim
     * @return array|null
     */
    protected function fetchFromBridgeApi(string $nim): ?array
    {
        $baseUrl = rtrim(config('services.bridge_api.url', env('BRIDGE_API_URL', 'https://bridge.uinssc.ac.id')), '/');
        $timeout = (int) config('services.bridge_api.timeout', 5);

        // Ambil token dari konfigurasi atau session user yang sedang login
        $token = config('services.bridge_api.token') ?? session('bridge_token');

        // Daftar variasi endpoint Bridge API v2 yang dicoba
        $endpoints = [
            "{$baseUrl}/api/v2/admin/mahasiswa/{$nim}",
            "{$baseUrl}/api/v2/admin/mahasiswa/search?q=" . urlencode($nim),
            "{$baseUrl}/api/v2/admin/mahasiswa?nim=" . urlencode($nim),
            "{$baseUrl}/api/v2/mahasiswa/{$nim}",
        ];

        foreach ($endpoints as $url) {
            try {
                $client = Http::withoutVerifying()->timeout($timeout)->withHeaders(['Accept' => 'application/json']);
                if ($token) {
                    $client = $client->withToken($token);
                }

                $response = $client->get($url);

                if ($response->successful()) {
                    $json = $response->json();
                    $data = $json['data'] ?? $json['mahasiswa'] ?? $json['user'] ?? $json;

                    // Jika dibungkus array (seperti hasil search), ambil item pertama
                    if (is_array($data) && isset($data[0]) && is_array($data[0])) {
                        $data = $data[0];
                    }

                    if (is_array($data)) {
                        $name = $data['nama'] ?? $data['name'] ?? null;
                        if ($name) {
                            $prodi   = $data['prodi'] ?? $data['program_studi'] ?? $data['jurusan'] ?? null;
                            $faculty = $data['fakultas'] ?? $data['faculty'] ?? null;
                            $phone   = $data['phone'] ?? $data['no_hp'] ?? $data['telepon'] ?? null;
                            $entry   = $data['angkatan'] ?? $data['entry_year'] ?? null;

                            return [
                                'nim'        => $data['nim'] ?? $nim,
                                'name'       => $name,
                                'department' => $prodi,
                                'faculty'    => $faculty,
                                'phone'      => $phone,
                                'entry_year' => $entry ? (int) $entry : null,
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::debug("[Bridge API Lookup] Error saat request {$url}: " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Cocokkan jurusan dan fakultas berdasarkan master mapping aplikasi.
     *
     * @param string|null $department
     * @param array $map
     * @return array{0: string|null, 1: string|null} [faculty, normalizedDepartment]
     */
    protected function resolveFacultyAndDepartment(?string $department, array $map): array
    {
        if (!$department || $department === '-') {
            return [null, null];
        }

        // 1. Cek kecocokan persis (exact match)
        if (isset($map[$department])) {
            return [$map[$department], $department];
        }

        // 2. Cek case-insensitive
        foreach ($map as $deptName => $facName) {
            if (strcasecmp($deptName, $department) === 0) {
                return [$facName, $deptName];
            }
        }

        // 3. Cek partial match (misal: "Informatika" cocok dengan "Teknik Informatika" atau sebaliknya)
        foreach ($map as $deptName => $facName) {
            if (stripos($deptName, $department) !== false || stripos($department, $deptName) !== false) {
                return [$facName, $deptName];
            }
        }

        return [null, $department];
    }

    /**
     * Prediksi tahun angkatan dari 2 digit pertama NIM (contoh: 2388010016 -> 2023).
     *
     * @param string $nim
     * @return int
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

    /**
     * Hitung perkiraan semester aktif mahasiswa berdasarkan tahun angkatan.
     *
     * @param int|null $entryYear
     * @return int|null
     */
    protected function calculateCurrentSemester(?int $entryYear): ?int
    {
        if (!$entryYear || $entryYear < 2000 || $entryYear > 2050) {
            return null;
        }

        $now = now();
        $currentYear = (int) $now->format('Y');
        $currentMonth = (int) $now->format('n');

        // Kalender Akademik:
        // Semester Ganjil: Agustus - Januari (bulan 8 - 12, atau bulan 1)
        // Semester Genap: Februari - Juli (bulan 2 - 7)
        if ($currentMonth >= 8) {
            $yearsDiff = $currentYear - $entryYear;
            $semester = ($yearsDiff * 2) + 1;
        } elseif ($currentMonth === 1) {
            $yearsDiff = ($currentYear - 1) - $entryYear;
            $semester = ($yearsDiff * 2) + 1;
        } else {
            $yearsDiff = ($currentYear - 1) - $entryYear;
            $semester = ($yearsDiff * 2) + 2;
        }

        return max(1, min(14, $semester));
    }
}
