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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->integer('phone')->unique();
            $table->string('password');
            $table->integer('type')->default(1);
            $table->integer('verified')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->fulltext('username');
            $table->fulltext('email');
            $table->unique([ 'username', 'email', 'phone' ]);
            $table->index('username');
            $table->index('email');
            $table->index('phone');
            $table->index('type');
            $table->index('verified');
            $table->index('status');
            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
