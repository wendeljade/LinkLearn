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
        Schema::table('organizations', function (Blueprint $table) {
            $table->integer('total_payments_made')->default(0)->after('proof_of_payment');
        });

        // Retroactively assume currently active organizations have paid at least once
        \Illuminate\Support\Facades\DB::table('organizations')
            ->where('status', 'active')
            ->update(['total_payments_made' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('total_payments_made');
        });
    }
};
