<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $departments = [
            'Teknik Informatika' => 'Fakultas Ilmu Komputer',
            'Sistem Informasi' => 'Fakultas Ilmu Komputer',
            'Teknik Elektro' => 'Fakultas Teknik',
            'Teknik Sipil' => 'Fakultas Teknik',
            'Manajemen' => 'Fakultas Ekonomi & Bisnis',
            'Akuntansi' => 'Fakultas Ekonomi & Bisnis',
        ];

        foreach ($departments as $dept => $faculty) {
            \Illuminate\Support\Facades\DB::table('violation_reports')
                ->whereNull('reported_faculty')
                ->orWhere('reported_faculty', '')
                ->where(function ($q) use ($dept) {
                    $q->where('reported_department', $dept)
                      ->orWhere('target_department', $dept);
                })
                ->update(['reported_faculty' => $faculty]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
