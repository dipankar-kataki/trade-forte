<?php

use App\Models\PackagingDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_details_id');
            $table->unsignedBigInteger('users_id');
            $table->integer('hsn_code');
            $table->text('product_name');
            $table->string('uqc');
            $table->integer('quantity');
            $table->integer('net_value');
            $table->integer('net_weight');
            $table->integer('unit_type')->nullable();
            $table->string('packaging_description')->nullable();
            $table->string('custom_column_name_1')->nullable();
            $table->string('custom_column_value_1')->nullable();
            $table->string('custom_column_name_2')->nullable();
            $table->string('custom_column_value_2')->nullable();
            $table->string('custom_column_name_3')->nullable();
            $table->string('custom_column_value_3')->nullable();
            $table->string('custom_column_name_4')->nullable();
            $table->string('custom_column_value_4')->nullable();
            $table->string('custom_column_name_5')->nullable();
            $table->string('custom_column_value_5')->nullable();
            $table->integer('net_weight_of_each_unit');
            $table->integer('gst_rate')->nullable();
            $table->integer('unit_value')->nullable();
            $table->integer('cess_rate')->nullable();
            $table->timestamps();
            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('invoice_details_id')->references('id')->on('invoice_details');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
