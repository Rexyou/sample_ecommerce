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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('image_url')->nullable();
            $table->date('dob')->nullable();
            $table->string('country', 3)->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->json('addresses')->nullable();
            $table->json('preferences')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');

            $table->fulltext('addresses');
            $table->unique('user_id');
            $table->index('user_id');
            $table->index('gender');
            $table->index('country');
            $table->index('state');
            $table->index('city');
            $table->index('status');
            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
