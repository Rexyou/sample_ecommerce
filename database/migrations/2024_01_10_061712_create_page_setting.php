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
        Schema::create('page_setting', function (Blueprint $table) {
            $table->id();
            $table->string('page_name', 255);
            $table->string('component', 255);
            $table->string('image_url');
            $table->string('redirect_url')->nullable();
            $table->string('title', 155)->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('display_order');
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->fulltext('page_name');
            $table->fulltext('component');
            $table->fulltext('title');
            $table->index('page_name');
            $table->index('component');
            $table->index('display_order');
            $table->index('status');
            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_setting');
    }
};
