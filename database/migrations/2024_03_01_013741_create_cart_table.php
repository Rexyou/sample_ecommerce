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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('order_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_option_detail_id');
            $table->decimal('per_unit_price', 8, 2)->default('0.00');
            $table->decimal('total_price', 8, 2)->default('0.00');
            $table->integer('quantity');
            $table->string('remark')->nullable();
            $table->integer('payment_status')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_option_detail_id')->references('id')->on('product_option_details');

            $table->index('user_id');
            $table->index('order_id');
            $table->index('product_id');
            $table->index('product_option_detail_id');
            $table->index('status');
            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('cart');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
