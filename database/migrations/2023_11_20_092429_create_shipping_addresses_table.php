<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAddressesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consignee_id');
            $table->unsignedBigInteger('details_created_by');
            $table->string('name');
            $table->string('address');
            $table->string('country');
            $table->string('phone');
            $table->string('pin_code');
            $table->boolean('status')->default(1);

            $table->timestamps();

            $table->foreign('consignee_id')->references('id')->on('consignees');
            $table->foreign('details_created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shipping_addresses');
    }
}
