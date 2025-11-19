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
        Schema::table('kpi_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('ranking_code_id')->nullable()->after('user_id');
            $table->foreign('ranking_code_id')->references('id')->on('ranking_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpi_logs', function (Blueprint $table) {
            $table->dropForeign(['ranking_code_id']);
            $table->dropColumn('ranking_code_id');
        });
    }
};
