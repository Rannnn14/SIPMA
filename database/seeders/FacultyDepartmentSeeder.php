<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FacultyDepartmentSeeder extends Seeder
{
    /**
     * Seed data fakultas dan prodi dari Bridge API,
     * lalu buat akun placeholder untuk kajur dan wadek3.
     */
    public function run(): void
    {
        $baseUrl = 'https://bridge.uinssc.ac.id/api';
        $token   = 'ptipd-access-token';
        $headers = ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'];

        // ── 1. Ambil data dari API ──────────────────────────────────────────
        $this->command->info('Mengambil data fakultas dari API...');
        $fakultasRes = Http::withoutVerifying()->withHeaders($headers)->get("{$baseUrl}/fakultas");

        $this->command->info('Mengambil data program studi dari API...');
        $prodiRes = Http::withoutVerifying()->withHeaders($headers)->get("{$baseUrl}/program_studi");

        if (! $fakultasRes->successful() || ! $prodiRes->successful()) {
            $this->command->error('Gagal mengambil data dari API. Periksa koneksi internet.');
            return;
        }

        $apiFakultas = $fakultasRes->json('data');
        $apiProdi    = $prodiRes->json('data');

        if (empty($apiFakultas) || empty($apiProdi)) {
            $this->command->error('Data dari API kosong.');
            return;
        }

        // ── 2. Bersihkan data lama ──────────────────────────────────────────
        $this->command->info('Membersihkan akun kajur dan wadek3 lama...');

        // Hapus akun kajur, wadek3, kaprodi, dekan lama (bukan mahasiswa, kemahasiswaan, warek, admin)
        DB::table('users')
            ->whereIn('role', ['kajur', 'wadek3', 'kaprodi', 'dekan'])
            ->delete();

        // Hapus semua departemen dan fakultas lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('departments')->truncate();
        DB::table('faculties')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ── 3. Insert fakultas dari API ─────────────────────────────────────
        $this->command->info('Menyimpan data fakultas...');

        $facultyMap = []; // kode_fakultas => [id, name, short_name]

        foreach ($apiFakultas as $f) {
            $rawName   = trim($f['nama_fakultas']);
            $shortName = trim($f['nama_fakultas_singkat']);

            // Awali dengan FAKULTAS (contoh: FAKULTAS ILMU TARBIYAH DAN KEGURUAN)
            $fullName = (!str_starts_with($rawName, 'FAKULTAS') && $rawName !== 'PASCASARJANA')
                ? "FAKULTAS {$rawName}"
                : $rawName;

            $id = DB::table('faculties')->insertGetId([
                'name'       => $fullName,
                'short_name' => $shortName,
                'api_code'   => $f['kode_fakultas'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $facultyMap[$f['kode_fakultas']] = [
                'id'         => $id,
                'name'       => $fullName,
                'short_name' => $shortName,
            ];
            $this->command->line("  ✓ [{$id}] {$fullName} ({$shortName})");
        }

        // ── 4. Insert program studi dari API ────────────────────────────────
        $this->command->info('Menyimpan data program studi...');

        $insertedCount = 0;
        foreach ($apiProdi as $p) {
            $facultyKode = $p['fakultas_kode'];

            if (! isset($facultyMap[$facultyKode])) {
                $this->command->warn("  ⚠ Prodi {$p['nama_prodi']} — kode fakultas {$facultyKode} tidak ditemukan, dilewati.");
                continue;
            }

            $jenjang = trim($p['jenjang'] ?? 'S1');
            $rawName = trim($p['nama_prodi']);

            // Jika jenjang bukan S1 (misal S2, S3, Profesi), sertakan jenjang agar nama prodi jelas dan unik
            $deptName = ($jenjang !== 'S1' && !str_contains($rawName, $jenjang))
                ? "{$rawName} ({$jenjang})"
                : $rawName;

            $deptId = DB::table('departments')->insertGetId([
                'faculty_id'  => $facultyMap[$facultyKode]['id'],
                'name'        => $deptName,
                'jenjang'     => $jenjang,
                'kode_prodi'  => $p['kode_prodi'] ?? null,
                'api_id'      => $p['id_prodi'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $insertedCount++;
            $this->command->line("  ✓ [{$deptId}] [{$jenjang}] {$deptName} — {$facultyMap[$facultyKode]['short_name']}");
        }

        // ── 5. Buat akun placeholder kajur (per departemen) ────────────────
        $this->command->info('Membuat akun kajur untuk setiap prodi dengan username & email singkat...');

        $shortCodeMap = [
            // FEBI
            'AKUNTANSI SYARIAH'                           => 'aks',
            'BIOTEKNOLOGI'                                => 'biotek',
            'EKONOMI SYARIAH'                             => 'es',
            'PARIWISATA SYARIAH'                          => 'par',
            'PERBANKAN SYARI`AH'                          => 'pbs',
            'PERBANKAN SYARIAH'                           => 'pbs',

            // FS
            'HUKUM EKONOMI SYARIAH (MUAMALAH)'            => 'hes',
            'HUKUM KELUARGA (AKHWAL SYAKSIYAH)'           => 'hki',
            'HUKUM TATANEGARA ISLAM'                      => 'htn',
            'ILMU FALAK'                                  => 'falak',
            'PJJ HUKUM KELUARGA ISLAM (AHWAL SYAKHSHIYYAH)' => 'pjj.hki',

            // FUA
            'AQIDAH DAN FILSAFAT ISLAM'                   => 'afi',
            'BAHASA DAN SASTRA ARAB'                      => 'bsa',
            'ILMU AL-QUR`AN DAN TAFSIR'                   => 'iat',
            'ILMU AL-QURAN DAN TAFSIR'                    => 'iat',
            'ILMU HADIS'                                  => 'ilha',
            'PJJ SEJARAH PERADABAN ISLAM'                 => 'pjj.spi',
            'SEJARAH PERADABAN ISLAM'                     => 'spi',
            'TASAWUF DAN PSIKOTERAPI'                     => 'tp',

            // FDKI
            'BIMBINGAN DAN KONSELING ISLAM'               => 'bki',
            'KOMUNIKASI DAN PENYIARAN ISLAM'              => 'kpi',
            'PENGEMBANGAN MASYARAKAT ISLAM'               => 'pmi',
            'SOSIOLOGI AGAMA'                             => 'sa',

            // FITK
            'INFORMATIKA'                                 => 'if',
            'MANAJEMEN PENDIDIKAN ISLAM'                  => 'mpi',
            'MATEMATIKA'                                  => 'mat',
            'PENDIDIKAN AGAMA ISLAM'                      => 'pai',
            'PENDIDIKAN BAHASA ARAB'                      => 'pba',
            'PENDIDIKAN GURU MADRASAH IBTIDAIYAH'         => 'pgmi',
            'PENDIDIKAN ISLAM ANAK USIA DINI'             => 'piaud',
            'PENDIDIKAN PROFESI GURU KEAGAMAAN (PROFESI)' => 'ppg',
            'PJJ PENDIDIKAN AGAMA ISLAM'                  => 'pjj.pai',
            'PJJ PENDIDIKAN BAHASA ARAB'                  => 'pjj.pba',
            'PJJ PENDIDIKAN GURU MADRASAH IBTIDAIYAH'     => 'pjj.pgmi',
            'TADRIS BAHASA INDONESIA'                     => 'tbindo',
            'TADRIS BAHASA INGGRIS'                       => 'tbing',
            'TADRIS BIOLOGI'                              => 'tbio',
            'TADRIS ILMU PENGETAHUAN SOSIAL (IPS)'        => 'tips',
            'TADRIS KIMIA'                                => 'tkim',
            'TADRIS MATEMATIKA'                           => 'tmat',

            // PASCASARJANA
            'EKONOMI SYARI`AH (S2)'                       => 's2.es',
            'EKONOMI SYARIAH (S2)'                        => 's2.es',
            'EKONOMI SYARIAH (S3)'                        => 's3.es',
            'HUKUM KELUARGA (AKHWAL SYAKSIYAH) (S2)'      => 's2.hki',
            'HUKUM KELUARGA ISLAM (AHWAL SYAKHSHIYYAH) (S3)' => 's3.hki',
            'MANAJEMEN PENDIDIKAN ISLAM (S2)'             => 's2.mpi',
            'PENDIDIKAN AGAMA ISLAM (S2)'                 => 's2.pai',
            'PENDIDIKAN AGAMA ISLAM (S3)'                 => 's3.pai',
            'PENGEMBANGAN MASYARAKAT ISLAM (S2)'          => 's2.pmi',
            'PJJ PENDIDIKAN AGAMA ISLAM (S2)'             => 's2.pjj.pai',
            'SEJARAH PERADABAN ISLAM (S2)'                => 's2.spi',
        ];

        $departments = DB::table('departments')
            ->join('faculties', 'faculties.id', '=', 'departments.faculty_id')
            ->select('departments.id', 'departments.name', 'departments.jenjang', 'faculties.name as faculty_name', 'faculties.short_name')
            ->get();

        $usedEmails = [];
        $kajurCount = 0;

        foreach ($departments as $dept) {
            $cleanName = strtoupper(trim($dept->name));
            $shortCode = $shortCodeMap[$cleanName] ?? Str::slug(substr($dept->name, 0, 10), '.');

            $username = "kajur.{$shortCode}";
            $email = "kajur.{$shortCode}@uinssc.ac.id";

            if (isset($usedEmails[$email])) {
                $username = "kajur.{$shortCode}.{$dept->id}";
                $email = "kajur.{$shortCode}.{$dept->id}@uinssc.ac.id";
            }
            $usedEmails[$email] = true;

            DB::table('users')->insert([
                'name'       => '', // Kosong sesuai instruksi, nanti diisi manual oleh admin
                'email'      => $email,
                'username'   => $username,
                'password'   => Hash::make('kajur12345'),
                'role'       => 'kajur',
                'faculty'    => $dept->faculty_name,
                'department' => $dept->name,
                'nim'        => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $kajurCount++;
        }
        $this->command->line("  ✓ Berhasil membuat {$kajurCount} akun kajur placeholder dengan username singkat.");

        // ── 6. Buat akun placeholder wadek3 (1 per fakultas) ───────────────
        $this->command->info('Membuat akun wadek3 untuk setiap fakultas...');

        $faculties = DB::table('faculties')->get();
        $wadekCount = 0;

        foreach ($faculties as $fak) {
            $slug  = strtolower($fak->short_name);
            $email = "wadek3.{$slug}@uinssc.ac.id";
            $username = "wadek3.{$slug}";

            DB::table('users')->insert([
                'name'       => '', // Kosong sesuai instruksi
                'email'      => $email,
                'username'   => $username,
                'password'   => Hash::make('wadek12345'),
                'role'       => 'wadek3',
                'faculty'    => $fak->name,
                'department' => null,
                'nim'        => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $wadekCount++;
            $this->command->line("  ✓ Wadek3: {$fak->name} ({$fak->short_name}) → {$email}");
        }

        // ── 7. Ringkasan ────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info("🎉 SINKRONISASI DATA API BERHASIL!");
        $this->command->table(
            ['Data', 'Jumlah'],
            [
                ['Fakultas', count($apiFakultas)],
                ['Program Studi', $insertedCount],
                ['Akun Kajur Dibuat', $kajurCount],
                ['Akun Wadek3 Dibuat', $wadekCount],
            ]
        );

        $this->command->newLine();
        $this->command->comment('Password default akun kajur  : kajur12345');
        $this->command->comment('Password default akun wadek3 : wadek12345');
        $this->command->comment('Nama akun kajur & wadek3 dikosongkan (bisa dilengkapi di menu Manajemen Akun).');
    }
}
