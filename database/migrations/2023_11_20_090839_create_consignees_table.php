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
            $table->unsignedBigInteger('users_id');
            $table->string('name');
            $table->string('address_line_one');
            $table->string('address_line_two')->nullable();
            $table->string('state');
            $table->string('pin_code');
            $table->string('city');
            $table->string('district');
            $table->string('foreign_business_country');
            $table->string('foreign_category');
            $table->string('license_no')->nullable();
            $table->string('authorised_signatory_name')->nullable();
            $table->string('authorised_signatory_designation')->nullable();
            $table->string('organization_email')->nullable();
            $table->string('organization_phone')->nullable();            
            $table->boolean('status')->default(1);
            $table->foreign('users_id')->references('id')->on('users');            
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
        Schema::dropIfExists('consignees');
    }
}
