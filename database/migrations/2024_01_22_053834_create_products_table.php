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
            $table->string('name', 255);
            $table->string('code_name', 255);
            $table->string('icon_image_url', 255)->nullable();
            $table->string('banner_image_url', 255)->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->integer('type')->default(1);
            $table->json('sizing');
            $table->decimal('original_price', 8, 2);
            $table->decimal('member_price', 8, 2);
            $table->text('description', 355);
            $table->unsignedBigInteger('created_by');
            $table->integer('display_order')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('created_by')->references('id')->on('users');

            $table->fulltext('name');
            $table->fulltext('code_name');
            $table->unique(['code_name', 'brand_id', 'type']);
            $table->index('brand_id');
            $table->index('type');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('products');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
