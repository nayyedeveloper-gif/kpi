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
        Schema::create('performance_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('period_date');
            $table->string('period_type'); // monthly, quarterly, yearly
            $table->decimal('overall_score', 5, 2)->default(0);
            $table->decimal('kpi_score', 5, 2)->default(0);
            $table->decimal('task_score', 5, 2)->default(0);
            $table->decimal('quality_score', 5, 2)->default(0);
            $table->decimal('attendance_score', 5, 2)->default(0);
            $table->decimal('collaboration_score', 5, 2)->default(0);
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_total')->default(0);
            $table->integer('kpis_completed')->default(0);
            $table->integer('kpis_total')->default(0);
            $table->integer('days_present')->default(0);
            $table->integer('days_total')->default(0);
            $table->integer('department_rank')->nullable();
            $table->integer('company_rank')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'period_date', 'period_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_scores');
    }
};