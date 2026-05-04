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
            // tutor_id references central DB users — no FK constraint possible across databases in MySQL
            $table->unsignedBigInteger('tutor_id')->nullable()->index();
            $table->string('subject_name');
            $table->text('description')->nullable();
            $table->string('cover_photo')->nullable();
            $table->decimal('fee', 8, 2)->default(0.00);
            $table->enum('status', ['open', 'full', 'done', 'archived'])->default('open');
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
