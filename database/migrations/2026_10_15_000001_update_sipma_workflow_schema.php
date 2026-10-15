<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('mahasiswa')->change();
            $table->string('department', 150)->nullable()->after('role');
            $table->string('faculty', 150)->nullable()->after('department');
        });

        // 2. Update violation_reports table
        Schema::table('violation_reports', function (Blueprint $table) {
            $table->string('status', 50)->default('pending')->change();
            $table->unsignedBigInteger('student_id')->nullable()->change();
            $table->unsignedBigInteger('violation_type_id')->nullable()->change();

            $table->string('reported_name', 150)->nullable()->after('student_id');
            $table->string('reported_nim', 50)->nullable()->after('reported_name');
            $table->string('reported_department', 150)->nullable()->after('reported_nim');
            $table->string('target_department', 150)->nullable()->after('reported_department');
            $table->string('severity', 50)->nullable()->after('points'); // Ringan, Sedang, Berat, Suspek
            $table->string('academic_year', 20)->default('2026/2027')->after('chronology');
            $table->string('semester', 20)->default('Ganjil')->after('academic_year');
            $table->string('evidence_path', 255)->nullable()->after('semester');
            $table->string('evidence_name', 255)->nullable()->after('evidence_path');
            
            // Kajur handling columns
            $table->string('sanction_type', 150)->nullable()->after('rejection_reason');
            $table->text('kajur_notes')->nullable()->after('sanction_type');
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null')->after('kajur_notes');
            $table->timestamp('handled_at')->nullable()->after('handled_by');
        });
    }

    public function down(): void
    {
        Schema::table('violation_reports', function (Blueprint $table) {
            $table->dropForeign(['handled_by']);
            $table->dropColumn([
                'reported_name',
                'reported_nim',
                'reported_department',
                'target_department',
                'severity',
                'academic_year',
                'semester',
                'evidence_path',
                'evidence_name',
                'sanction_type',
                'kajur_notes',
                'handled_by',
                'handled_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['department', 'faculty']);
        });
    }
};
