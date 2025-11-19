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
        Schema::create('bonus_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // revenue, quantity, rank
            $table->string('period'); // monthly, quarterly, yearly
            $table->json('criteria')->nullable();
            $table->decimal('bonus_amount', 10, 2)->default(0);
            $table->decimal('bonus_percentage', 5, 2)->default(0);
            $table->integer('rank_limit')->nullable();
            $table->decimal('minimum_revenue', 12, 2)->nullable();
            $table->integer('minimum_quantity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_configurations');
    }
};