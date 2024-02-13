<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConsigneesBankAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consignees_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consignee_id');
            $table->unsignedBigInteger('users_id');
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('forex_account_name');
            $table->string('forex_account_no')->unique();
            $table->string('swift_code')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('consignee_id')->references('id')->on('consignees');
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
        Schema::dropIfExists('consignees_bank_accounts');
    }
}
