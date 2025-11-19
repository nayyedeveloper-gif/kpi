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
            $table->string('personality_score')->nullable();
            $table->string('performance_score')->nullable();
            $table->string('hospitality_score')->nullable();
            $table->integer('customer_follow_up_score')->default(0);
            $table->integer('number_of_people')->default(0);
            $table->integer('supervised_level_score')->default(0);
            $table->json('personality_kpis')->nullable();
            $table->json('performance_kpis')->nullable();
            $table->json('hospitality_kpis')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpi_measurements', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'personality_score',
                'performance_score',
                'hospitality_score',
                'customer_follow_up_score',
                'number_of_people',
                'supervised_level_score',
                'personality_kpis',
                'performance_kpis',
                'hospitality_kpis',
                'created_by',
                'updated_by'
            ]);
        });
    }
};
