<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $facultyDepartments = [
        'FITK' => [
            'Pendidikan Agama Islam', 'Pendidikan Bahasa Arab', 'Tadris Bahasa Inggris', 'Tadris IPS',
            'Tadris Matematika', 'Tadris Biologi', 'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)',
            'Pendidikan Islam Anak Usia Dini (PIAUD)', 'Manajemen Pendidikan Islam', 'Tadris Bahasa Indonesia',
            'Tadris Kimia', 'Informatika', 'Matematika', 'PJJ Pendidikan Agama Islam',
            'PJJ Pendidikan Bahasa Arab', 'PJJ Pendidikan Guru Madrasah Ibtidaiyah',
        ],
        'FEBI' => ['Perbankan Syariah', 'Ekonomi Syariah', 'Akuntansi Syariah', 'Pariwisata Syariah', 'Bioteknologi'],
        'Fakultas Syariah' => ['Hukum Keluarga Islam', 'Hukum Ekonomi Syariah', 'Hukum Tata Negara', 'Ilmu Falak', 'PJJ Hukum Keluarga Islam'],
        'FUA' => ['Sejarah Peradaban Islam', 'Aqidah dan Filsafat Islam', 'Ilmu Al-Qur\'an dan Tafsir', 'Ilmu Hadis', 'Bahasa dan Sastra Arab', 'Tasawuf dan Psikoterapi', 'PJJ Sejarah Peradaban Islam'],
        'FDKI' => ['Komunikasi dan Penyiaran Islam', 'Pengembangan Masyarakat Islam', 'Bimbingan dan Konseling Islam', 'Sosiologi Agama'],
    ];

    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150)->unique();
            $table->timestamps();
        });

        foreach ($this->facultyDepartments as $facultyName => $departments) {
            DB::table('faculties')->insertOrIgnore([
                'name' => $facultyName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $facultyId = DB::table('faculties')->where('name', $facultyName)->value('id');

            foreach ($departments as $departmentName) {
                DB::table('departments')->insertOrIgnore([
                    'faculty_id' => $facultyId,
                    'name' => $departmentName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
        Schema::dropIfExists('faculties');
    }
};
