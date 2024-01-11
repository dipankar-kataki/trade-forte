<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exporters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_created_by');
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->string('pincode');
            $table->string('phone')->unique();
            $table->string('gst_no')->nullable();
            $table->string('iec_no')->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_height')->nullable();
            $table->string('logo_width')->nullable();
            $table->string('lut_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('account_created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exporters');
    }
}
