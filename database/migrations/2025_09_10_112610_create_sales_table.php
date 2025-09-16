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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('sale_customer_id')->nullable();
            $table->unsignedBigInteger('sale_account_id')->nullable();
            $table->string('sale_date')->nullable();
            $table->double('sale_price')->default(0);
            $table->float('sale_due')->default(0)->nullable();
            $table->double('sale_profit')->default(0);
            $table->double('sale_loss')->default(0);
            $table->text('sale_note')->nullable();
            $table->string('sale_tnxid')->nullable();
            $table->json('pax_data')->nullable();
            $table->string('pax_name')->nullable();
            $table->string('pax_mobile_no')->nullable();
            $table->string('pax_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
