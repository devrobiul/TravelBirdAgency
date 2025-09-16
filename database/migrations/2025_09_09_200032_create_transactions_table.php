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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('from_account_id')->nullable();
            $table->unsignedBigInteger('to_account_id')->nullable();
            $table->float('amount')->default(0);
            $table->string('account_name')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_date')->nullable();
            $table->longText('note')->nullable();
            $table->enum('status', ['pending', 'approve', 'cancel',])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
