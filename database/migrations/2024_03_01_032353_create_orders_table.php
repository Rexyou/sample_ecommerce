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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('currency', 3);
            $table->decimal('total_amount', 8, 2)->default('0.00');
            $table->integer('payment_type')->default(1);
            $table->string('reference_id')->nullable();
            $table->unsignedBigInteger('callback_id')->nullable();
            $table->unsignedBigInteger('handle_by')->default(0);
            $table->integer('payment_status')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('handle_by')->references('id')->on('users');

            $table->index('order_number');
            $table->index('user_id');
            $table->index('payment_type');
            $table->index('reference_id');
            $table->index('callback_id');
            $table->index('handle_by');
            $table->index('payment_status');
            $table->index('status');
            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
