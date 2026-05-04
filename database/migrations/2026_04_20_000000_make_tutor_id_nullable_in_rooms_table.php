<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']);
        });

        DB::statement('ALTER TABLE rooms MODIFY tutor_id BIGINT UNSIGNED NULL');

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('tutor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']);
        });

        DB::statement('ALTER TABLE rooms MODIFY tutor_id BIGINT UNSIGNED NOT NULL');

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('tutor_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
