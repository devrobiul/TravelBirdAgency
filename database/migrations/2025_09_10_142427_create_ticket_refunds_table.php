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
        Schema::create('ticket_refunds', function (Blueprint $table) {
            $table->id(); 
            $table->integer('user_id')->nullable();
            $table->integer('product_id'); 
            $table->string('refund_pnr', 130);
            $table->integer('refund_vendor_id'); 
            $table->bigInteger('profit_account_id')->nullable(); 
            $table->float('refund_amount'); 
            $table->float('customer_refund'); 
            $table->float('refund_profit'); 
            $table->date('refund_date'); 
            $table->date('refund_expected_date'); 
            $table->enum('status', ['1', '0'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_refunds');
    }
};
