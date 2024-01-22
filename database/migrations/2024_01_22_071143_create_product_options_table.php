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
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('color_option', 255);
            $table->string('variant_option', 255);
            $table->decimal('original_price', 8, 2);
            $table->decimal('member_price', 8, 2);
            $table->unsignedBigInteger('created_by');
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');

            $table->unique(['product_id', 'color_option', 'variant_option']);
            $table->index('product_id');
            $table->index('original_price');
            $table->index('member_price');
            $table->index('created_by');
            $table->index('status');
            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
