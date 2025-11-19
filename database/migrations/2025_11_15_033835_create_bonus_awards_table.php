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
        Schema::create('bonus_awards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_person_id');
            $table->unsignedBigInteger('bonus_configuration_id')->nullable();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->integer('total_quantity')->default(0);
            $table->integer('total_transactions')->default(0);
            $table->integer('rank')->nullable();
            $table->decimal('bonus_amount', 10, 2)->default(0);
            $table->string('bonus_type'); // configuration, tier, special
            $table->text('reason')->nullable();
            $table->string('status')->default('pending'); // pending, approved, paid
            $table->timestamp('awarded_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->foreign('sales_person_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bonus_configuration_id')->references('id')->on('bonus_configurations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_awards');
    }
};