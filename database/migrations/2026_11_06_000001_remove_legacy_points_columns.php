<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violation_reports', function (Blueprint $table) {
            $table->dropColumn('points');
        });

        Schema::table('violation_types', function (Blueprint $table) {
            $table->dropColumn('default_points');
        });
    }

    public function down(): void
    {
        Schema::table('violation_types', function (Blueprint $table) {
            $table->unsignedInteger('default_points')->default(0);
        });

        Schema::table('violation_reports', function (Blueprint $table) {
            $table->unsignedInteger('points')->default(0);
        });
    }
};
