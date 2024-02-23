<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExporterAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exporter_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exporter_id');
            $table->unsignedBigInteger('users_id');
            $table->string('address_line_one');
            $table->string('address_line_two')->nullable();
            $table->string('state');
            $table->string('pin_code');
            $table->string('city');
            $table->string('district');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('exporter_id')->references('id')->on('exporters');
            $table->foreign('users_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
