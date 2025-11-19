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
        Schema::table('kpi_measurements', function (Blueprint $table) {
            $table->json('team_management_kpis')->nullable();
            $table->json('customer_follow_up_kpis')->nullable();
            $table->json('supervised_level_kpis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpi_measurements', function (Blueprint $table) {
            $table->dropColumn([
                'team_management_kpis',
                'customer_follow_up_kpis',
                'supervised_level_kpis'
            ]);
        });
    }
};
