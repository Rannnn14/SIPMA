<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Truncate/Clean existing demo data
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
            ['nim' => '2024001', 'name' => 'Ahmad Fauzi', 'study_program' => 'Teknik Informatika', 'entry_year' => 2024, 'phone' => '081234567890'],
            ['nim' => '2024002', 'name' => 'Budi Santoso', 'study_program' => 'Teknik Informatika', 'entry_year' => 2024, 'phone' => '081234567891'],
            ['nim' => '2024003', 'name' => 'Citra Lestari', 'study_program' => 'Sistem Informasi', 'entry_year' => 2023, 'phone' => '081234567892'],
            ['nim' => '2024004', 'name' => 'Doni Kusuma', 'study_program' => 'Teknik Elektro', 'entry_year' => 2024, 'phone' => '081234567893'],
            ['nim' => '2024005', 'name' => 'Eka Prasetya', 'study_program' => 'Manajemen', 'entry_year' => 2023, 'phone' => '081234567894'],
        ];

        $studentIds = [];
        foreach ($studentsData as $st) {
            $id = DB::table('students')->insertGetId(array_merge($st, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
            $studentIds[$st['nim']] = $id;
        }

        // 3. Seed Users for 5 Roles (RBAC)
        // Mahasiswa (Pelapor)
        $mahasiswaUser = DB::table('users')->insertGetId([
            'name' => 'Ahmad Fauzi (Mahasiswa)',
            'email' => 'mahasiswa@sipma.test',
            'nim' => '2024001',
            'username' => '2024001',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'department' => 'Teknik Informatika',
            'faculty' => 'Fakultas Ilmu Komputer',
            'student_id' => $studentIds['2024001'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Kemahasiswaan (Verifikator & Global Analyst - Super Admin)
        $kemahasiswaanUser = DB::table('users')->insertGetId([
            'name' => 'Dra. Siti Rahma, M.Pd. (Kemahasiswaan)',
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

        // Also alias 'admin@sipma.test'
        DB::table('users')->insert([
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

        // Ketua Jurusan (Kajur TI)
        $kajurTiUser = DB::table('users')->insertGetId([
            'name' => 'Dr. Hendra Wijaya, S.Kom., M.T.',
            'email' => 'kajur@sipma.test',
            'nim' => null,
            'username' => 'kajur_ti',
            'password' => Hash::make('password'),
            'role' => 'kajur',
            'department' => 'Teknik Informatika',
            'faculty' => 'Fakultas Ilmu Komputer',
            'student_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Ketua Jurusan SI
        $kajurSiUser = DB::table('users')->insertGetId([
            'name' => 'Dr. Ir. Maya Sari, M.T.',
            'email' => 'kajur.si@sipma.test',
            'nim' => null,
            'username' => 'kajur_si',
            'password' => Hash::make('password'),
            'role' => 'kajur',
            'department' => 'Sistem Informasi',
            'faculty' => 'Fakultas Ilmu Komputer',
            'student_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Dekan (Fakultas Ilmu Komputer)
        $dekanUser = DB::table('users')->insertGetId([
            'name' => 'Prof. Dr. Ir. Budi Darmawan, M.Eng.',
            'email' => 'dekan@sipma.test',
            'nim' => null,
            'username' => 'dekan',
            'password' => Hash::make('password'),
            'role' => 'dekan',
            'department' => null,
            'faculty' => 'Fakultas Ilmu Komputer',
            'student_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Wakil Rektor (Warek) - Universitas
        $warekUser = DB::table('users')->insertGetId([
            'name' => 'Prof. Dr. Suharto, M.Sc.',
            'email' => 'warek@sipma.test',
            'nim' => null,
            'username' => 'warek',
            'password' => Hash::make('password'),
            'role' => 'warek',
            'department' => null,
            'faculty' => null,
            'student_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 4. Violation Categories & Types
        $catAkademik = DB::table('violation_categories')->insertGetId([
            'name' => 'Akademik',
            'description' => 'Pelanggaran yang berkaitan dengan kegiatan akademik & ujian',
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $catTataTertib = DB::table('violation_categories')->insertGetId([
            'name' => 'Tata Tertib Kampus',
            'description' => 'Pelanggaran tata tertib, fasilitas, dan ketertiban umum',
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $catEtika = DB::table('violation_categories')->insertGetId([
            'name' => 'Etika & Moral',
            'description' => 'Pelanggaran etika, kesopanan, perundungan, atau pelecehan',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $typePlagiarisme = DB::table('violation_types')->insertGetId([
            'violation_category_id' => $catAkademik,
            'name' => 'Plagiarisme & Kecurangan Ujian',
            'severity' => 'berat',
            'default_points' => 75,
            'default_sanction' => 'Skorsing 1 Semester / Nilai E',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $typeMerokok = DB::table('violation_types')->insertGetId([
            'violation_category_id' => $catTataTertib,
            'name' => 'Merokok / Vape di Kawasan Terlarang',
            'severity' => 'ringan',
            'default_points' => 20,
            'default_sanction' => 'Teguran Lisan / Tertulis',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $typePerkelahian = DB::table('violation_types')->insertGetId([
            'violation_category_id' => $catEtika,
            'name' => 'Perkelahian & Keributan',
            'severity' => 'sedang',
            'default_points' => 50,
            'default_sanction' => 'Surat Peringatan & Pemanggilan Ortu',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // 5. Seed Violation Reports across lifecycle stages
        $sampleReports = [
            [
                'report_number' => 'REP-202610-001',
                'student_id' => $studentIds['2024002'],
                'reported_name' => 'Budi Santoso',
                'reported_nim' => '2024002',
                'reported_department' => 'Teknik Informatika',
                'target_department' => 'Teknik Informatika',
                'violation_type_id' => $typePlagiarisme,
                'reporter_id' => $mahasiswaUser,
                'points' => 75,
                'severity' => 'Berat',
                'incident_date' => Carbon::now()->subDays(2)->toDateString(),
                'incident_time' => '10:30:00',
                'incident_location' => 'Laboratorium Komputer 3, Gedung B',
                'chronology' => 'Terlapor terbukti menyontek tugas akhir dan menggunakan source code rekan lain tanpa izin pada saat praktikum.',
                'status' => 'pending', // Baru dilaporkan, menunggu verifikasi Kemahasiswaan
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'report_number' => 'REP-202610-002',
                'student_id' => $studentIds['2024003'],
                'reported_name' => 'Citra Lestari',
                'reported_nim' => '2024003',
                'reported_department' => 'Sistem Informasi',
                'target_department' => 'Sistem Informasi',
                'violation_type_id' => $typeMerokok,
                'reporter_id' => $mahasiswaUser,
                'points' => 20,
                'severity' => 'Ringan',
                'incident_date' => Carbon::now()->subDays(4)->toDateString(),
                'incident_time' => '14:15:00',
                'incident_location' => 'Kantin Gedung C',
                'chronology' => 'Terlapor merokok di area tertutup kantin yang sudah terdapat tanda dilarang merokok.',
                'status' => 'dikonfirmasi', // Kemahasiswaan approve -> diteruskan ke Kajur SI
                'verified_by' => $kemahasiswaanUser,
                'verified_at' => Carbon::now()->subDays(3),
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'report_number' => 'REP-202610-003',
                'student_id' => $studentIds['2024002'],
                'reported_name' => 'Budi Santoso',
                'reported_nim' => '2024002',
                'reported_department' => 'Teknik Informatika',
                'target_department' => 'Teknik Informatika',
                'violation_type_id' => $typePerkelahian,
                'reporter_id' => $mahasiswaUser,
                'points' => 50,
                'severity' => 'Sedang',
                'incident_date' => Carbon::now()->subDays(6)->toDateString(),
                'incident_time' => '16:00:00',
                'incident_location' => 'Area Parkir Barat',
                'chronology' => 'Terjadi adu argumen yang berlanjut pada aksi dorong-mendorong dengan sesama mahasiswa di area parkir.',
                'status' => 'diproses', // Kajur TI sedang menindaklanjuti
                'verified_by' => $kemahasiswaanUser,
                'verified_at' => Carbon::now()->subDays(5),
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'sanction_type' => 'Pemanggilan Mahasiswa & Klarifikasi',
                'kajur_notes' => 'Telah dilakukan pemanggilan awal mahasiswa terlapor ke ruang jurusan pada hari Selasa.',
                'handled_by' => $kajurTiUser,
                'handled_at' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'report_number' => 'REP-202610-004',
                'student_id' => $studentIds['2024001'],
                'reported_name' => 'Ahmad Fauzi',
                'reported_nim' => '2024001',
                'reported_department' => 'Teknik Informatika',
                'target_department' => 'Teknik Informatika',
                'violation_type_id' => $typeMerokok,
                'reporter_id' => $kemahasiswaanUser,
                'points' => 20,
                'severity' => 'Ringan',
                'incident_date' => Carbon::now()->subDays(10)->toDateString(),
                'incident_time' => '11:00:00',
                'incident_location' => 'Lantai 2 Koridor Gedung A',
                'chronology' => 'Membawa rokok elektrik dan menghembuskan uap di dalam ruang koridor perkuliahan.',
                'status' => 'selesai', // Kajur TI selesai memberikan sanksi
                'verified_by' => $kemahasiswaanUser,
                'verified_at' => Carbon::now()->subDays(9),
                'sanction_type' => 'Teguran Lisan & Surat Pernyataan',
                'kajur_notes' => 'Mahasiswa telah hadir didampingi dosen wali, menandatangani surat pernyataan tidak mengulangi perbuatan.',
                'handled_by' => $kajurTiUser,
                'handled_at' => Carbon::now()->subDays(7),
                'completed_at' => Carbon::now()->subDays(7),
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'report_number' => 'REP-202610-005',
                'student_id' => null,
                'reported_name' => 'Rian Hidayat',
                'reported_nim' => '2024999',
                'reported_department' => 'Teknik Elektro',
                'target_department' => 'Teknik Elektro',
                'violation_type_id' => null,
                'reporter_id' => $mahasiswaUser,
                'points' => 0,
                'severity' => 'Suspek',
                'incident_date' => Carbon::now()->subDays(8)->toDateString(),
                'incident_time' => '09:00:00',
                'incident_location' => 'Gerbang Utama Kampus',
                'chronology' => 'Informasi dugaan coret-coret dinding pos satpam.',
                'status' => 'ditolak', // Ditolak Kemahasiswaan
                'rejection_reason' => 'Bukti foto tidak jelas dan NIM terlapor tidak terdaftar pada pangkalan data kampus.',
                'verified_by' => $kemahasiswaanUser,
                'verified_at' => Carbon::now()->subDays(7),
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'report_number' => 'REP-202610-006',
                'student_id' => $studentIds['2024004'],
                'reported_name' => 'Doni Kusuma',
                'reported_nim' => '2024004',
                'reported_department' => 'Teknik Elektro',
                'target_department' => 'Teknik Elektro',
                'violation_type_id' => $typePlagiarisme,
                'reporter_id' => $mahasiswaUser,
                'points' => 75,
                'severity' => 'Berat',
                'incident_date' => Carbon::now()->subMonths(1)->toDateString(),
                'incident_time' => '13:00:00',
                'incident_location' => 'Ruang Ujian Gedung Elektro',
                'chronology' => 'Membawa contekan lembar kertas saat ujian tengah semester.',
                'status' => 'selesai',
                'verified_by' => $kemahasiswaanUser,
                'sanction_type' => 'Surat Peringatan (SP 1) & Nilai Matkul Dibatalkan',
                'kajur_notes' => 'Sanksi telah diputuskan rapat jurusan Teknik Elektro.',
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'report_number' => 'REP-202610-007',
                'student_id' => $studentIds['2024005'],
                'reported_name' => 'Eka Prasetya',
                'reported_nim' => '2024005',
                'reported_department' => 'Manajemen',
                'target_department' => 'Manajemen',
                'violation_type_id' => $typePerkelahian,
                'reporter_id' => $mahasiswaUser,
                'points' => 50,
                'severity' => 'Sedang',
                'incident_date' => Carbon::now()->subMonths(2)->toDateString(),
                'incident_time' => '15:30:00',
                'incident_location' => 'Lapangan Olahraga',
                'chronology' => 'Keributan antar suporter pertandingan futsal kampus.',
                'status' => 'selesai',
                'verified_by' => $kemahasiswaanUser,
                'sanction_type' => 'Pembinaan Karakter & Kerja Sosial Kampus',
                'kajur_notes' => 'Mahasiswa menjalankan pembinaan selama 1 minggu.',
                'academic_year' => '2025/2026',
                'semester' => 'Genap',
                'created_at' => Carbon::now()->subMonths(2),
                'updated_at' => Carbon::now()->subMonths(2),
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
