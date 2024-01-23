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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('icon_image_url', 255)->nullable();
            $table->string('banner_image_url', 255)->nullable();
            $table->string('description', 355)->nullable();
            $table->year('founded_year');
            $table->json('social_platform');
            $table->integer('main_brand')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->fulltext('name');
            $table->index('name');
            $table->index('founded_year');
            $table->index('main_brand');
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
        Schema::dropIfExists('brands');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
