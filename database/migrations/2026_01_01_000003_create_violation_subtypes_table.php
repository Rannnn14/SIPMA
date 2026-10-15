<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violation_subtypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_type_id')->constrained('violation_types')->onDelete('cascade');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_subtypes');
    }
};
