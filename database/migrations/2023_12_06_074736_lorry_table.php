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
            $table->string('total_quantity');
            $table->string('uqc');
            $table->string('total_trips');

            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->unsignedBigInteger("invoice_details_id");
            $table->foreign('invoice_details_id')->references('id')->on('invoice_details');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_log');
    }
}
