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
        Schema::create('performance_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranking_code_id')->constrained()->onDelete('cascade');
            $table->date('evaluation_date');
            $table->decimal('personality_score', 5, 2)->default(0);
            $table->decimal('team_management_score', 5, 2)->default(0);
            $table->decimal('customer_follow_up_score', 5, 2)->default(0);
            $table->decimal('supervised_level_score', 5, 2)->default(0);
            $table->decimal('total_score', 5, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->decimal('bonus_amount', 12, 2)->default(0);
            $table->boolean('is_eligible_for_bonus')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index(['ranking_code_id', 'evaluation_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_kpis');
    }
};
