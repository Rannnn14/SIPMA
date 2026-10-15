<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violation_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number', 30)->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('violation_type_id')->constrained('violation_types')->onDelete('cascade');
            $table->foreignId('violation_subtype_id')->nullable()->constrained('violation_subtypes')->onDelete('set null');
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('points')->default(0);
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->string('incident_location', 255);
            $table->text('chronology');
            $table->enum('status', [
                'dilaporkan',
                'diverifikasi_kaprodi',
                'diteruskan_kemahasiswaan',
                'ditolak',
                'sedang_diproses',
                'sanksi_ditetapkan',
                'selesai'
            ])->default('dilaporkan');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_reports');
    }
};
