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
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date');
            $table->unsignedBigInteger('sales_person_id')->nullable();
            $table->string('invoice_no');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('item_name');
            $table->float('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total_amount', 12, 2);
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_nrc')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->string('payment_method')->nullable();
            $table->string('branch');
            $table->float('commission_rate')->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->string('goldsmith_name')->nullable();
            $table->string('shop_number')->nullable();
            $table->string('cashier')->nullable();
            $table->string('color_manager')->nullable();
            $table->string('responsible_signature')->nullable();
            $table->string('item_category')->nullable();
            $table->string('gold_quality')->nullable();
            $table->string('color')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('item_k')->nullable();
            $table->string('item_p')->nullable();
            $table->string('item_y')->nullable();
            $table->string('item_tg')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('sales_person_id')->references('id')->on('sales_people')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->index('sale_date');
            $table->index('invoice_no');
            $table->index('customer_name');
            $table->index('branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_transactions');
    }
};