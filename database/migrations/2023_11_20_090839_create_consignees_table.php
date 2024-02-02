<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsigneesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consignees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_created_by');
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('country');
            $table->string('customer_category');
            $table->string('license_no')->nullable();
            $table->string('pin_code')->nullable();
            $table->boolean('status')->default(1);
            $table->string('authorised_signatory_name')->nullable();
            $table->string('authorised_signatory_designation')->nullable();
            $table->string('organization_email')->nullable();
            $table->string('organization_phone')->nullable();
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
        Schema::dropIfExists('consignees');
    }
}
