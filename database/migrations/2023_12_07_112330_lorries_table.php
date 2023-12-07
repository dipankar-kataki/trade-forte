<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LorriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("lorries", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("invoice_id");
            $table->unsignedBigInteger("exporter_id");
            $table->unsignedBigInteger("consignee_id");
            $table->unsignedBigInteger("bank_id");
            $table->foreign('invoice_id')->references('id')->on('invoice_details');
            $table->foreign('exporter_id')->references('id')->on('exporters');
            $table->foreign('consignee_id')->references('id')->on('users');
            $table->foreign('bank_id')->references('id')->on('bank_accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('lorries');
    }
}
