<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * FINAL STATE: domains.tenant_id is a string referencing organizations.slug.
 * This is the definitive migration for the domains table foreign key.
 * stancl/tenancy creates domains with tenant_id = the tenant key (slug for us).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Drop any existing FK on tenant_id (regardless of what it references)
        try {
            Schema::table('domains', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
            });
        } catch (\Exception $e) {
            // FK doesn't exist — fine
        }

        // Step 2: Ensure column is VARCHAR (for slug storage)
        $columns = DB::select("SHOW COLUMNS FROM `domains` LIKE 'tenant_id'");
        if (!empty($columns)) {
            $type = strtolower($columns[0]->Type);
            if (strpos($type, 'varchar') === false) {
                // Convert from int/bigint to string
                DB::statement('ALTER TABLE `domains` MODIFY `tenant_id` VARCHAR(255) NOT NULL');
            }
        }

        // Step 3: Add the correct FK — tenant_id -> organizations.slug
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

        // Restore to original string column with no FK
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
        Schema::table('domains', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id');
        });
    }
};
