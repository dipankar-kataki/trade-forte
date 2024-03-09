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
            $table->unsignedBigInteger('invoice_details_id');
            $table->unsignedBigInteger('users_id');
            $table->string('reference_no')->nullable();
            $table->string('eway_bill_no')->nullable();
            $table->integer('total_packages')->default(0);
            $table->decimal('net_weight_in_kgs')->default(0);
            $table->decimal('total_gross_weight')->default(0);
            $table->boolean("with_letter_head")->nullable()->default(1);
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
        Schema::dropIfExists('packaging_details');
    }
}
