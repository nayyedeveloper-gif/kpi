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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('staff_name')->nullable();
            $table->boolean('is_diamond')->default(false);
            $table->boolean('is_solid_gold')->default(false);
            $table->string('item_category')->nullable();
            $table->string('item_name')->nullable();
            $table->string('gold_quality')->nullable();
            $table->string('original_code')->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->string('goldsmith_name')->nullable();
            $table->date('goldsmith_date')->nullable();
            $table->string('color')->nullable();
            $table->string('supplier')->nullable();
            $table->string('voucher_no')->nullable();
            $table->decimal('item_k', 8, 2)->nullable();
            $table->decimal('item_p', 8, 2)->nullable();
            $table->decimal('item_y', 8, 2)->nullable();
            $table->decimal('item_tg', 8, 3)->nullable();
            $table->decimal('waste_k', 8, 2)->nullable();
            $table->decimal('waste_p', 8, 2)->nullable();
            $table->decimal('waste_y', 8, 2)->nullable();
            $table->decimal('waste_t', 8, 3)->nullable();
            $table->decimal('pwaste_k', 8, 2)->nullable();
            $table->decimal('pwaste_p', 8, 2)->nullable();
            $table->decimal('pwaste_y', 8, 2)->nullable();
            $table->decimal('pwaste_tg', 8, 3)->nullable();
            $table->decimal('sale_fixed_price', 10, 2)->default(0);
            $table->decimal('original_fixed_price', 10, 2)->default(0);
            $table->decimal('original_price_tk', 10, 2)->default(0);
            $table->decimal('original_price_gram', 10, 2)->default(0);
            $table->decimal('design_charges', 10, 2)->default(0);
            $table->decimal('plating_charges', 10, 2)->default(0);
            $table->decimal('mounting_charges', 10, 2)->default(0);
            $table->decimal('white_charges', 10, 2)->default(0);
            $table->decimal('other_charges', 10, 2)->default(0);
            $table->text('remark')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('code');
            $table->index('item_category');
            $table->index('gold_quality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};