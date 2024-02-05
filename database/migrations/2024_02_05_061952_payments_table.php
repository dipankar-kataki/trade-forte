<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_accounts_id');
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('invoice_details_id');
            $table->string('invoice_currency');
            $table->string('terms_of_payment');
            $table->foreign('bank_accounts_id')->references('id')->on('bank_accounts');
            $table->foreign('users_id')->references('id')->on('users');
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
        Schema::dropIfExists('payments');
    }
}
