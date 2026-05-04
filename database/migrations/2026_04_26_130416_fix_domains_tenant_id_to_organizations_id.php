<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * NOTE: This migration is now a no-op.
 * The previous migration (fix_domains_tenant_foreign_key) already handles
 * ensuring tenant_id is a string pointing to organizations.slug.
 * The next migration (fix_domains_tenant_id_to_slug) will set the final state.
 * Keeping this file here only so migrate:status does not show missing migrations.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Drop FK if it exists (safe cleanup before next migration adds the correct one)
        try {
            Schema::table('domains', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
            });
        } catch (\Exception $e) {
            // Already dropped or never existed — fine
        }

        // Ensure the column is a string (slug-compatible) — change from bigint if needed
        $columnType = DB::select("SHOW COLUMNS FROM `domains` LIKE 'tenant_id'");
        if (!empty($columnType) && stripos($columnType[0]->Type, 'int') !== false) {
            // It's an integer type — convert to string for slug storage
            DB::statement('ALTER TABLE `domains` MODIFY `tenant_id` VARCHAR(255) NOT NULL');
        }
    }

    public function down(): void
    {
        // Nothing to reverse — the column state is managed by surrounding migrations
    }
};
