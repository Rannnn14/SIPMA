<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom reported_semester (semester saat kejadian pelanggaran terlapor)
     * ke tabel violation_reports.
     */
    public function up(): void
    {
        Schema::table('violation_reports', function (Blueprint $table) {
            // Semester saat pelanggaran dilaporkan oleh mahasiswa
            // Contoh: "Ganjil 2026/2027", "Genap 2025/2026"
            $table->string('reported_semester', 30)->nullable()->after('semester');
        });
    }

    public function down(): void
    {
        Schema::table('violation_reports', function (Blueprint $table) {
            $table->dropColumn('reported_semester');
        });
    }
};
