<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violation_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_category_id')->constrained('violation_categories')->onDelete('cascade');
            $table->string('name', 150);
            $table->enum('severity', ['ringan', 'sedang', 'berat'])->default('ringan');
            $table->unsignedInteger('default_points')->default(10);
            $table->string('default_sanction', 255)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_types');
    }
};
