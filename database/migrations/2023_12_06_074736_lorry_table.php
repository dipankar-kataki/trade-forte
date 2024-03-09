<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LorryTable extends Migration
{

    public function up()
    {
        Schema::create("lorry", function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->integer('total_quantity');
            $table->string('uqc');
            $table->integer('total_trips');

            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');

            $table->unsignedBigInteger('exporter_id');
            $table->foreign('exporter_id')->references('id')->on('exporters');

            $table->unsignedBigInteger('exporter_address_id')->nullable();
            $table->foreign('exporter_address_id')->references('id')->on('exporter_address');

            $table->unsignedBigInteger('consignee_id');
            $table->foreign('consignee_id')->references('id')->on('consignees');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_log');
    }
}
