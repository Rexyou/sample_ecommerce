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
        Schema::create('product_option_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->json('options')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('original_price', 8, 2)->default('0.00');
            $table->decimal('member_price', 8, 2)->default('0.00');
            $table->decimal('sale_price', 8, 2)->default('0.00');
            $table->decimal('sale_member_price', 8, 2)->default('0.00');
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');

            $table->index('product_id');
            $table->index('options');
            $table->index('quantity');
            $table->index('original_price');
            $table->index('member_price');
            $table->index('sale_price');
            $table->index('sale_member_price');
            $table->index('status');
            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option_details');
    }
};
