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
            $table->string('category', 255);
            $table->string('name', 255);
            $table->unsignedBigInteger('created_by');
            $table->integer('display_order')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('created_by')->references('id')->on('users');

            $table->unique(['product_id', 'category', 'name']);
            $table->index('product_id');
            $table->index('category');
            $table->index('name');
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
