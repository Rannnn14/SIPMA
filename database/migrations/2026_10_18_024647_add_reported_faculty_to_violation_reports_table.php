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
        Schema::table('violation_reports', function (Blueprint $table) {
            $table->string('reported_faculty')->nullable()->after('reported_nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violation_reports', function (Blueprint $table) {
            $table->dropColumn('reported_faculty');
        });
    }
};
