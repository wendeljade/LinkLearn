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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->nullable()->constrained('users')->nullOnDelete(); // Kinsa ang mag-tutor
    $table->string('subject_name');                      // Unsa nga subject
    $table->text('description')->nullable();            // Detalye sa session
    $table->decimal('fee', 8, 2)->default(200.00);      // Ang 200 pesos
    $table->string('room_link')->nullable();            // Zoom, Google Meet, o Jitsi link
    $table->enum('status', ['open', 'full', 'done'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
