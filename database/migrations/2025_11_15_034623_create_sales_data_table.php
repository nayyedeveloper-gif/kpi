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
        Schema::create('sales_data', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->date('invoiced_date');
            $table->string('voucher_number');
            $table->string('branch');
            $table->string('customer_name');
            $table->string('customer_status')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('township')->nullable();
            $table->string('division')->nullable();
            $table->string('customer_nrc_number')->nullable();
            $table->string('item_categories')->nullable();
            $table->string('item_group')->nullable();
            $table->string('item_name')->nullable();
            $table->decimal('density', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('unit')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('g_price', 10, 2)->nullable();
            $table->decimal('g_gross_amount', 12, 2)->nullable();
            $table->decimal('m_price', 10, 2)->nullable();
            $table->decimal('m_gross_amount', 12, 2)->nullable();
            $table->decimal('dis', 10, 2)->nullable();
            $table->decimal('promotion_dis', 10, 2)->nullable();
            $table->decimal('special_dis', 10, 2)->nullable();
            $table->decimal('dis_net_amount', 12, 2)->nullable();
            $table->decimal('promotion_net_amount', 12, 2)->nullable();
            $table->decimal('total_net_amount', 12, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->string('sale_person')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            
            $table->index(['year', 'month']);
            $table->index('invoiced_date');
            $table->index('branch');
            $table->index('customer_name');
            $table->index('item_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_data');
    }
};