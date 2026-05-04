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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ngalan sa Organization [cite: 22]
            $table->string('slug')->unique(); // Ang branding sa URL (Requirement #4)
            $table->enum('status', ['active', 'expired'])->default('active'); // Ang Kill Switch indicator [cite: 12]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
