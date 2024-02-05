<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransportationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('invoice_details_id');
            $table->string('mode_of_transport');
            $table->string('bl_awb_lr_no');
            $table->date('bl_awb_lr_date');
            $table->string('transporter_name');

            $table->string('vehicle_vessel_flight_no');
            $table->string('challan_number');
            $table->date('challan_date');
            $table->string('eway_bill_no');
            $table->date('eway_bill_date');
            $table->string('pre_carriage_by');
            $table->string('place_of_pre_carriage');

            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('invoice_details_id')->references('id')->on('invoice_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
