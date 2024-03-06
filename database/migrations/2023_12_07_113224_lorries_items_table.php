<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LorriesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("lorry_items", function (Blueprint $table) {
            $table->id();
            $table->string('trip');
            $table->string('vehicle_no');
            $table->string('quantity');
            $table->integer('total_quantity_to_deliver');
            $table->unsignedBigInteger('users_id')->nullable()->default(null);
            $table->foreign('users_id')->references('id')->on('users');
            $table->unsignedBigInteger("lorry_id");
            $table->foreign('lorry_id')->references('id')->on('lorry');
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
