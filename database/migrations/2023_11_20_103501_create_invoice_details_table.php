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
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('exporter_id');
            $table->unsignedBigInteger('consignee_id');
            $table->string('invoice_id')->unique()->index();
            $table->string('category');
            $table->string('type')->nullable();
            $table->string('country_of_destination');
            $table->string('country_of_export');
            $table->string('country_of_origin');
            $table->string('import_export_code')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_destination')->nullable();
            // $table->string('freight')->nullable();
            // $table->string('valid_upto')->nullable();
            // $table->string('vehicle_no')->nullable();
            // $table->string('insurance')->nullable();
            $table->string("incoterm")->nullable();
            $table->timestamp('invoice_date')->default(Carbon::now());
            // $table->string('eway_bill_id')->nullable();

            $table->string("po_contract_number")->nullable();
            $table->string('po_contract_date')->nullable();
            $table->string("remarks")->nullable();

            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('exporter_id')->references('id')->on('exporters');
            $table->foreign('consignee_id')->references('id')->on('consignees');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
}
