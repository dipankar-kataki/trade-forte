<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LorryInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("lorry_invoices", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lorry_id');
            $table->foreign('lorry_id')->references('id')->on('lorry');
            $table->unsignedBigInteger('invoice_details_id');
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
        Schema::dropIfExists('user_log');
    }
}
