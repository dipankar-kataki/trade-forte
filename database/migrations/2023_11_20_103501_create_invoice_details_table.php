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
            $table->unsignedBigInteger('shipping_id');
            $table->unsignedBigInteger('exporter_address_id');

            $table->string('invoice_number')->nullable()->unique()->index();
            $table->integer('invoice_value')->nullable();
            $table->integer('total_net_weight')->nullable();
            $table->string('lorry_number')->nullable();
            $table->string('category');
            $table->string('type')->nullable();
            $table->string('country_of_destination');
            $table->string('country_of_export');
            $table->string('country_of_origin');
            $table->string('import_export_code')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_destination')->nullable();
            $table->string("incoterm")->nullable();
            $table->timestamp('invoice_date')->default(Carbon::now());
            $table->string("po_contract_number")->nullable();
            $table->date('po_contract_date')->nullable();
            $table->string("remarks")->nullable();
            $table->boolean("with_letter_head")->nullable()->default(1);

            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('exporter_address_id')->references('id')->on('exporter_address');
            $table->foreign('shipping_id')->references('id')->on('shipping_addresses');
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
