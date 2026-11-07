<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Master data required by report forms. User and incident data are managed in-app.
        $categories = [
            'Akademik' => 'Pelanggaran berkaitan dengan kejujuran dan etika kegiatan perkuliahan atau ujian.',
            'Tata Tertib Kampus' => 'Pelanggaran fasilitas kampus, ketertiban umum, dan norma lingkungan.',
            'Etika & Moral' => 'Pelanggaran etika, kesopanan, perundungan, atau norma kesantunan kampus.',
        ];

        $categoryIds = [];
        foreach ($categories as $name => $description) {
            $categoryIds[$name] = DB::table('violation_categories')->insertGetId([
                'name' => $name,
                'description' => $description,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $types = [
            [$categoryIds['Akademik'], 'Plagiarisme & Kecurangan Ujian', 'berat', 'Skorsing / Pembatalan Nilai'],
            [$categoryIds['Tata Tertib Kampus'], 'Merokok / Vape di Kawasan Terlarang Kampus', 'ringan', 'Teguran Lisan / Tertulis'],
            [$categoryIds['Etika & Moral'], 'Perkelahian & Keributan', 'sedang', 'Surat Peringatan & Pemanggilan Wali Mahasiswa'],
        ];

        foreach ($types as [$categoryId, $name, $severity, $sanction]) {
            DB::table('violation_types')->insert([
                'violation_category_id' => $categoryId,
                'name' => $name,
                'severity' => $severity,
                'default_sanction' => $sanction,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
