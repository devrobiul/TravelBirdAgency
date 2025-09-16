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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('purchase_vendor_id')->nullable();
            $table->unsignedBigInteger('purchase_account_id')->nullable();
            $table->double('purchase_price')->default(0);
            $table->string('purchase_date')->nullable();
            $table->text('purchase_note')->nullable();
            $table->string('purchase_tnxid')->nullable();
            $table->json('pax_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
