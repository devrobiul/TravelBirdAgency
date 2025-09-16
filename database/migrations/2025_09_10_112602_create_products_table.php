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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('airline_id')->nullable();
            $table->unsignedBigInteger('visa_id')->nullable();
            $table->string('invoice_no')->index();
            $table->string('product_type')->nullable();
            $table->string('ticket_type')->nullable();
            $table->integer('group_qty')->nullable();
            $table->float('group_single_price')->default(0);
            $table->string('ticket_pnr')->nullable()->index();
            $table->float('product_sale_profit')->nullable();
            $table->float('product_sale_loss')->nullable();
            $table->string('travel_status')->nullable();
            $table->string('departer_time')->nullable();
            $table->string('arrival_time')->nullable();
            $table->string('depart_date')->nullable();
            $table->string('return_date')->nullable();
            $table->string('journey_from')->nullable();
            $table->string('journey_to')->nullable();
            $table->string('multicity_from')->nullable();
            $table->string('multicity_to')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('re_issue_date')->nullable();
            $table->string('refund_date', 50)->nullable();
            $table->string('service_type')->nullable();
            $table->string('passport_type')->nullable();
            $table->string('dath_of_birth')->nullable();
            $table->string('tracking_id')->nullable();
            $table->string('application_date')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('visa_type')->nullable();
            $table->string('visit_country')->nullable();
            $table->string('visa_issue_date')->nullable();
            $table->string('visa_exp_date')->nullable();

            $table->string('hotel_name')->nullable();
            $table->string('hotel_location')->nullable();
            $table->string('hotel_purchase_email')->nullable();
            $table->string('hotel_number_of_day')->nullable();
            $table->string('hotel_refer')->nullable();
            $table->float('passport_price')->default(0);

            $table->string('sale_date')->nullable();
            $table->json('pax_data')->nullable();
            $table->json('meta_data')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
