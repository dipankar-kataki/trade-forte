<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exporter_id');
            $table->unsignedBigInteger('users_id');
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('account_name');
            $table->string('account_no')->unique();
            $table->string('ifsc_code');
            $table->string('auth_dealer_code')->nullable();
            $table->string('swift_code')->nullable();
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
        Schema::dropIfExists('bank_accounts');
    }
}
