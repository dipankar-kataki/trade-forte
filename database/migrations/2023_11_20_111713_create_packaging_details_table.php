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
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('users_id');
            $table->text('net_weight')->nullable();
            $table->text('gross_weight')->nullable();
            $table->decimal('each_box_weight')->default(0);
            $table->string('packaging_type');
            $table->integer('quantity')->default(1);
            $table->decimal('total_gross_weight')->default(0);
            $table->timestamps();
            $table->foreign('users_id')->references('id')->on('users');
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
