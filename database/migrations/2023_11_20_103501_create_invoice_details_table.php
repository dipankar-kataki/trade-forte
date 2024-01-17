<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{

    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('details_added_by');
            $table->unsignedBigInteger('exporter_id');
            $table->unsignedBigInteger('consignee_id');
            $table->string('invoice_id')->unique()->index();
            $table->string('country_of_destination');
            $table->string('country_of_export');
            $table->string('country_of_origin');
            $table->string('import_export_code')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_destination')->nullable();
            $table->string('freight')->nullable();
            $table->string('valid_upto')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('insurance')->nullable();
            $table->string('buyer_no')->nullable();
            $table->string("incoterm_cpt")->nullable();
            $table->string("buyer_order_no")->nullable();
            $table->string("terms_of_payment")->nullable();
            $table->timestamp('invoice_date')->default(Carbon::now());
            $table->string('eway_bill_id')->nullable();
            $table->timestamps();
            $table->foreign('details_added_by')->references('id')->on('users');
            $table->foreign('exporter_id')->references('id')->on('exporters');
            $table->foreign('consignee_id')->references('id')->on('consignees');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
}
