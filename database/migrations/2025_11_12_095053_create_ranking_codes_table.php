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
        Schema::create('ranking_codes', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->string('position_name');
            $table->string('name')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_code', 1);
            $table->integer('branch_code');
            $table->string('group_code', 2);
            $table->string('position_code', 3);
            $table->integer('id_code');
            $table->string('ranking_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_codes');
    }
};
