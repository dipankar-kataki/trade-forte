<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packaging_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_item_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('details_added_by');
            $table->text('net_weight')->nullable();
            $table->text('gross_weight')->nullable();
            $table->decimal('each_box_weight')->default(0);
            $table->string('packaging_type');
            $table->integer('quantity')->default(1);
            $table->decimal('total_gross_weight')->default(0);
            $table->string('vehicle_no')->nullable();
            $table->string('custom_column_name_1');
            $table->string('custom_column_value_1');
            $table->string('custom_column_name_2');
            $table->string('custom_column_value_2');
            $table->string('custom_column_name_3');
            $table->string('custom_column_value_3');
            $table->string('custom_column_name_4');
            $table->string('custom_column_value_4');
            $table->string('custom_column_name_5');
            $table->string('custom_column_value_5');
            $table->timestamps();
            $table->foreign('details_added_by')->references('id')->on('users');
            $table->foreign('invoice_item_id')->references('id')->on('invoice_items');
            $table->foreign('invoice_id')->references('id')->on('invoice_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packaging_details');
    }
}
