<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Drop any existing foreign key on tenant_id (safe — ignore errors)
        try {
            Schema::table('domains', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
            });
        } catch (\Exception $e) {
            // Already dropped or doesn't exist — fine
        }

        // Step 2: Ensure tenant_id column is a string (for slug references)
        // Only change if it's not already a varchar
        $columnType = DB::select("SHOW COLUMNS FROM `domains` LIKE 'tenant_id'");
        if (!empty($columnType) && stripos($columnType[0]->Type, 'varchar') === false) {
            Schema::table('domains', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
            Schema::table('domains', function (Blueprint $table) {
                $table->string('tenant_id');
            });
        }

        // Step 3: Re-add the FK pointing to organizations.slug
        Schema::table('domains', function (Blueprint $table) {
            $table->foreign('tenant_id')
                ->references('slug')
                ->on('organizations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        try {
            Schema::table('domains', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
            });
        } catch (\Exception $e) {}
    }
};
