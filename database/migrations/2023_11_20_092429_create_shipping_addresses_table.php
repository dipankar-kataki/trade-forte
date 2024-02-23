<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // 'address_line_1' => 'required|string',
    // 'address_line_2' => 'required|string',
    // 'pin_code' => 'required|string',
    // "city"=>"required|string",
    // "district"=>"required|string",
    // "state"=>"required|string" ,           
    // 'country' => 'required|string',
    public function up()
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->string('address_line_one');
            $table->string('address_line_two')->nullable();
            $table->string('state');
            $table->string('pin_code');
            $table->string('city');
            $table->string('district');
            $table->boolean('status')->default(1);
            $table->timestamps();
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
        Schema::dropIfExists('shipping_addresses');
    }
}
