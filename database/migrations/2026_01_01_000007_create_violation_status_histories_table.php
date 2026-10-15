<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violation_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_report_id')->constrained('violation_reports')->onDelete('cascade');
            $table->string('previous_status')->nullable();
            $table->string('current_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_status_histories');
    }
};
