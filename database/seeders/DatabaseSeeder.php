<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Controllers\ReportController;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Truncate / Clean existing demo data
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        DB::table('sanctions')->truncate();
        DB::table('case_actions')->truncate();
        DB::table('report_evidences')->truncate();
        DB::table('violation_reports')->truncate();
        DB::table('violation_subtypes')->truncate();
        DB::table('violation_types')->truncate();
        DB::table('violation_categories')->truncate();
        DB::table('users')->truncate();
        DB::table('students')->truncate();

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }

        // 2. Seed Master Students
        $studentsData = [
            ['nim' => '2024001', 'name' => 'Ahmad Fauzi', 'study_program' => 'Informatika', 'entry_year' => 2024, 'phone' => '081234567890'],
            ['nim' => '2024002', 'name' => 'Budi Santoso', 'study_program' => 'Pendidikan Agama Islam', 'entry_year' => 2024, 'phone' => '081234567891'],
            ['nim' => '2024003', 'name' => 'Citra Lestari', 'study_program' => 'Perbankan Syariah', 'entry_year' => 2023, 'phone' => '081234567892'],
            ['nim' => '2024004', 'name' => 'Doni Kusuma', 'study_program' => 'Hukum Keluarga Islam', 'entry_year' => 2024, 'phone' => '081234567893'],
            ['nim' => '2024005', 'name' => 'Eka Prasetya', 'study_program' => 'Komunikasi dan Penyiaran Islam', 'entry_year' => 2023, 'phone' => '081234567894'],
            ['nim' => '2024006', 'name' => 'Fatimah Zahra', 'study_program' => 'Ilmu Al-Qur\'an dan Tafsir', 'entry_year' => 2024, 'phone' => '081234567895'],
        ];

        $studentIds = [];
        foreach ($studentsData as $st) {
            $id = DB::table('students')->insertGetId(array_merge($st, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
            $studentIds[$st['nim']] = $id;
        }

        // 3. Seed Users
        // A. Mahasiswa (Pelapor)
        $mahasiswaUser = DB::table('users')->insertGetId([
            'name' => 'Ahmad Fauzi',
            'email' => 'mahasiswa@sipma.test',
            'nim' => '2024001',
            'username' => '2024001',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'department' => 'Informatika',
            'faculty' => 'FITK',
            'student_id' => $studentIds['2024001'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // B. Admin Kemahasiswaan
        $adminKemahasiswaanId = DB::table('users')->insertGetId([
            'name' => 'Admin Kemahasiswaan',
            'email' => 'admin@sipma.test',
            'nim' => null,
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'kemahasiswaan',
            'department' => null,
            'faculty' => null,
            'student_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'name' => 'Bagian Kemahasiswaan',
            'email' => 'kemahasiswaan@sipma.test',
            'nim' => null,
            'username' => 'kemahasiswaan',
            'password' => Hash::make('password'),
            'role' => 'kemahasiswaan',
            'department' => null,
            'faculty' => null,
            'student_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // C. Warek 3 (Bidang Kemahasiswaan & Kerjasama - Universitas)
        $warekUser = DB::table('users')->insertGetId([
            'name' => 'Prof. Dr. H. Syarif Hidayat, M.Ag.',
            'email' => 'warek3@sipma.test',
            'nim' => null,
            'username' => 'warek3',
            'password' => Hash::make('password'),
            'role' => 'warek',
            'department' => null,
            'faculty' => null,
            'student_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // D. 5 Wadek 3 (Satu Akun per Fakultas)
        $wadekList = [
            [
                'name' => 'Dr. H. Saifuddin, M.Pd.',
                'email' => 'wadek3.fitk@sipma.test',
                'username' => 'wadek3_fitk',
                'faculty' => 'FITK',
            ],
            [
                'name' => 'Dr. Hj. Nur Aini, M.E.Sy.',
                'email' => 'wadek3.febi@sipma.test',
                'username' => 'wadek3_febi',
                'faculty' => 'FEBI',
            ],
            [
                'name' => 'Dr. Ahmad Mubarok, S.H., M.H.',
                'email' => 'wadek3.syariah@sipma.test',
                'username' => 'wadek3_syariah',
                'faculty' => 'Fakultas Syariah',
            ],
            [
                'name' => 'Dr. H. Abdul Ghofur, M.Ag.',
                'email' => 'wadek3.fua@sipma.test',
                'username' => 'wadek3_fua',
                'faculty' => 'FUA',
            ],
            [
                'name' => 'Dr. Hj. Siti Rohmah, M.Si.',
                'email' => 'wadek3.fdki@sipma.test',
                'username' => 'wadek3_fdki',
                'faculty' => 'FDKI',
            ],
        ];

        foreach ($wadekList as $w) {
            DB::table('users')->insert([
                'name' => $w['name'],
                'email' => $w['email'],
                'nim' => null,
                'username' => $w['username'],
                'password' => Hash::make('password'),
                'role' => 'wadek3',
                'department' => null,
                'faculty' => $w['faculty'],
                'student_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // E. Kajur untuk Setiap Jurusan di 5 Fakultas
        $departmentsMapping = ReportController::$departments;

        // Custom slug map for clean emails
        $slugOverrides = [
            'Pendidikan Agama Islam' => 'pai',
            'Pendidikan Bahasa Arab' => 'pba',
            'Tadris Bahasa Inggris' => 'tbi',
            'Tadris IPS' => 'tips',
            'Tadris Matematika' => 'tmat',
            'Tadris Biologi' => 'tbio',
            'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)' => 'pgmi',
            'Pendidikan Islam Anak Usia Dini (PIAUD)' => 'piaud',
            'Manajemen Pendidikan Islam' => 'mpi',
            'Tadris Bahasa Indonesia' => 'tbindo',
            'Tadris Kimia' => 'tkimia',
            'Informatika' => 'if',
            'Matematika' => 'mat',
            'PJJ Pendidikan Agama Islam' => 'pjjpai',
            'PJJ Pendidikan Bahasa Arab' => 'pjjpba',
            'PJJ Pendidikan Guru Madrasah Ibtidaiyah' => 'pjjpgmi',
            'Perbankan Syariah' => 'ps',
            'Ekonomi Syariah' => 'es',
            'Akuntansi Syariah' => 'as',
            'Pariwisata Syariah' => 'parsya',
            'Bioteknologi' => 'bio',
            'Hukum Keluarga Islam' => 'hki',
            'Hukum Ekonomi Syariah' => 'hes',
            'Hukum Tata Negara' => 'htn',
            'Ilmu Falak' => 'falak',
            'PJJ Hukum Keluarga Islam' => 'pjjhki',
            'Sejarah Peradaban Islam' => 'spi',
            'Aqidah dan Filsafat Islam' => 'afi',
            'Ilmu Al-Qur\'an dan Tafsir' => 'iat',
            'Ilmu Hadis' => 'ih',
            'Bahasa dan Sastra Arab' => 'bsa',
            'Tasawuf dan Psikoterapi' => 'tp',
            'PJJ Sejarah Peradaban Islam' => 'pjjspi',
            'Komunikasi dan Penyiaran Islam' => 'kpi',
            'Pengembangan Masyarakat Islam' => 'pmi',
            'Bimbingan dan Konseling Islam' => 'bki',
            'Sosiologi Agama' => 'sa',
        ];

        $kajurUsersByDept = [];

        foreach ($departmentsMapping as $deptName => $facName) {
            $slug = $slugOverrides[$deptName] ?? strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $deptName));
            $email = "kajur.{$slug}@sipma.test";
            $username = "kajur_{$slug}";
            $name = "Dr. Ketua Jurusan " . $deptName;

            $kajurId = DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'nim' => null,
                'username' => $username,
                'password' => Hash::make('password'),
                'role' => 'kajur',
                'department' => $deptName,
                'faculty' => $facName,
                'student_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $kajurUsersByDept[$deptName] = $kajurId;
        }

        // 4. Seed Violation Categories & Types
        $catAkademik = DB::table('violation_categories')->insertGetId([
            'name' => 'Akademik',
            'description' => 'Pelanggaran berkaitan dengan kejujuran & etika kegiatan perkuliahan/ujian',
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $catTataTertib = DB::table('violation_categories')->insertGetId([
            'name' => 'Tata Tertib Kampus',
            'description' => 'Pelanggaran fasilitas kampus, ketertiban umum, dan norma lingkungan',
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $catEtika = DB::table('violation_categories')->insertGetId([
            'name' => 'Etika & Moral',
            'description' => 'Pelanggaran etika, kesopanan, perundungan, atau norma kesantunan kampus',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $typePlagiarisme = DB::table('violation_types')->insertGetId([
            'violation_category_id' => $catAkademik,
            'name' => 'Plagiarisme & Kecurangan Ujian',
            'severity' => 'berat',
            'default_points' => 0,
            'default_sanction' => 'Skorsing / Pembatalan Nilai',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $typeMerokok = DB::table('violation_types')->insertGetId([
            'violation_category_id' => $catTataTertib,
            'name' => 'Merokok / Vape di Kawasan Terlarang Kampus',
            'severity' => 'ringan',
            'default_points' => 0,
            'default_sanction' => 'Teguran Lisan / Tertulis',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $typePerkelahian = DB::table('violation_types')->insertGetId([
            'violation_category_id' => $catEtika,
            'name' => 'Perkelahian & Keributan',
            'severity' => 'sedang',
            'default_points' => 0,
            'default_sanction' => 'Surat Peringatan & Pemanggilan Wali Mahasiswa',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // 5. Seed Sample Violation Reports (Menggunakan Fakultas & Jurusan Baru, Semester Angka)
        $sampleReports = [
            [
                'report_number' => 'REP-202610-001',
                'student_id' => $studentIds['2024002'],
                'reported_name' => 'Budi Santoso',
                'reported_nim' => '2024002',
                'reported_faculty' => 'FITK',
                'reported_department' => 'Pendidikan Agama Islam',
                'target_department' => 'Pendidikan Agama Islam',
                'violation_type_id' => $typePlagiarisme,
                'reporter_id' => $mahasiswaUser,
                'points' => 0,
                'severity' => 'Berat',
                'incident_date' => Carbon::now()->subDays(2)->toDateString(),
                'incident_time' => '10:30',
                'incident_location' => 'Ruang Ujian Gedung FITK Lt. 3',
                'chronology' => 'Terlapor terbukti membawa contekan dan menggunakan gawai tanpa izin saat ujian tengah semester berlangsung.',
                'status' => 'pending',
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'reported_semester' => '3',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'report_number' => 'REP-202610-002',
                'student_id' => $studentIds['2024003'],
                'reported_name' => 'Citra Lestari',
                'reported_nim' => '2024003',
                'reported_faculty' => 'FEBI',
                'reported_department' => 'Perbankan Syariah',
                'target_department' => 'Perbankan Syariah',
                'violation_type_id' => $typeMerokok,
                'reporter_id' => $mahasiswaUser,
                'points' => 0,
                'severity' => 'Ringan',
                'incident_date' => Carbon::now()->subDays(4)->toDateString(),
                'incident_time' => '14:15',
                'incident_location' => 'Selasar Gedung FEBI',
                'chronology' => 'Terlapor menggunakan rokok elektrik di area koridor yang telah terpasang stiker larangan merokok.',
                'status' => 'dikonfirmasi',
                'verified_by' => $adminKemahasiswaanId,
                'verified_at' => Carbon::now()->subDays(3),
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'reported_semester' => '5',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'report_number' => 'REP-202610-003',
                'student_id' => $studentIds['2024004'],
                'reported_name' => 'Doni Kusuma',
                'reported_nim' => '2024004',
                'reported_faculty' => 'Fakultas Syariah',
                'reported_department' => 'Hukum Keluarga Islam',
                'target_department' => 'Hukum Keluarga Islam',
                'violation_type_id' => $typePerkelahian,
                'reporter_id' => $mahasiswaUser,
                'points' => 0,
                'severity' => 'Sedang',
                'incident_date' => Carbon::now()->subDays(6)->toDateString(),
                'incident_time' => '16:00',
                'incident_location' => 'Area Parkir Fakultas Syariah',
                'chronology' => 'Terjadi adu mulut yang berujung keributan fisik antar mahasiswa di area parkir kendaraan.',
                'status' => 'diproses',
                'verified_by' => $adminKemahasiswaanId,
                'verified_at' => Carbon::now()->subDays(5),
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'reported_semester' => '3',
                'sanction_type' => 'Pemanggilan Mahasiswa & Klarifikasi',
                'kajur_notes' => 'Telah dilakukan pemanggilan awal ke ruang jurusan untuk klarifikasi mediasi.',
                'handled_by' => $kajurUsersByDept['Hukum Keluarga Islam'] ?? null,
                'handled_at' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'report_number' => 'REP-202610-004',
                'student_id' => $studentIds['2024001'],
                'reported_name' => 'Ahmad Fauzi',
                'reported_nim' => '2024001',
                'reported_faculty' => 'FITK',
                'reported_department' => 'Informatika',
                'target_department' => 'Informatika',
                'violation_type_id' => $typeMerokok,
                'reporter_id' => $adminKemahasiswaanId,
                'points' => 0,
                'severity' => 'Ringan',
                'incident_date' => Carbon::now()->subDays(10)->toDateString(),
                'incident_time' => '11:00',
                'incident_location' => 'Laboratorium Komputer FITK Lt. 2',
                'chronology' => 'Membawa minuman dan makanan yang dilarang di dalam area laboratorium komputer.',
                'status' => 'selesai',
                'verified_by' => $adminKemahasiswaanId,
                'verified_at' => Carbon::now()->subDays(9),
                'sanction_type' => 'Teguran Lisan & Surat Pernyataan',
                'kajur_notes' => 'Mahasiswa telah menandatangani surat kesediaan mematuhi tata tertib laboratorium.',
                'handled_by' => $kajurUsersByDept['Informatika'] ?? null,
                'handled_at' => Carbon::now()->subDays(7),
                'completed_at' => Carbon::now()->subDays(7),
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'reported_semester' => '2',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'report_number' => 'REP-202610-005',
                'student_id' => $studentIds['2024005'],
                'reported_name' => 'Eka Prasetya',
                'reported_nim' => '2024005',
                'reported_faculty' => 'FDKI',
                'reported_department' => 'Komunikasi dan Penyiaran Islam',
                'target_department' => 'Komunikasi dan Penyiaran Islam',
                'violation_type_id' => $typePerkelahian,
                'reporter_id' => $mahasiswaUser,
                'points' => 0,
                'severity' => 'Sedang',
                'incident_date' => Carbon::now()->subDays(5)->toDateString(),
                'incident_time' => '13:00',
                'incident_location' => 'Depan Studio Radio FDKI',
                'chronology' => 'Mengeluarkan kata-kata tidak pantas dan memicu kericuhan di depan ruang studio.',
                'status' => 'selesai',
                'verified_by' => $adminKemahasiswaanId,
                'sanction_type' => 'Pembinaan Karakter & Kerja Sosial',
                'kajur_notes' => 'Telah dibina oleh Kajur dan dosen wali.',
                'handled_by' => $kajurUsersByDept['Komunikasi dan Penyiaran Islam'] ?? null,
                'handled_at' => Carbon::now()->subDays(3),
                'completed_at' => Carbon::now()->subDays(3),
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'reported_semester' => '4',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'report_number' => 'REP-202610-006',
                'student_id' => $studentIds['2024006'],
                'reported_name' => 'Fatimah Zahra',
                'reported_nim' => '2024006',
                'reported_faculty' => 'FUA',
                'reported_department' => 'Ilmu Al-Qur\'an dan Tafsir',
                'target_department' => 'Ilmu Al-Qur\'an dan Tafsir',
                'violation_type_id' => $typeMerokok,
                'reporter_id' => $mahasiswaUser,
                'points' => 0,
                'severity' => 'Ringan',
                'incident_date' => Carbon::now()->subDays(1)->toDateString(),
                'incident_time' => '09:00',
                'incident_location' => 'Gazebo Belakang FUA',
                'chronology' => 'Meninggalkan sampah makanan dan merusak inventaris taman gazebo.',
                'status' => 'pending',
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'reported_semester' => '1',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($sampleReports as $rep) {
            DB::table('violation_reports')->insert(array_merge($rep, [
                'created_at' => $rep['created_at'] ?? $now,
                'updated_at' => $rep['updated_at'] ?? $now,
            ]));
        }
    }
}
