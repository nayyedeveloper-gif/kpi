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
        Schema::create('kpi_measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('measurement_date');
            $table->boolean('ready_to_sale')->default(false);
            $table->boolean('counter_check')->default(false);
            $table->boolean('cleanliness')->default(false);
            $table->boolean('stock_check')->default(false);
            $table->boolean('order_handling')->default(false);
            $table->boolean('customer_followup')->default(false);
            $table->integer('total_score')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'measurement_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_measurements');
    }
};