<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom short_name, api_code ke faculties
     * dan kolom jenjang, kode_prodi, api_id ke departments
     * agar bisa menyimpan data lengkap dari Bridge API.
     */
    public function up(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            $table->string('short_name', 50)->nullable()->after('name');
            $table->unsignedInteger('api_code')->nullable()->after('short_name');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->string('jenjang', 20)->nullable()->default('S1')->after('name');
            $table->string('kode_prodi', 20)->nullable()->after('jenjang');
            $table->unsignedInteger('api_id')->nullable()->after('kode_prodi');
            $table->unique(['faculty_id', 'name', 'jenjang']);
        });
    }

    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropColumn(['short_name', 'api_code']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['faculty_id', 'name', 'jenjang']);
            $table->dropColumn(['jenjang', 'kode_prodi', 'api_id']);
            $table->unique('name');
        });
    }
};
