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
            $table->string('addresses');
            $table->string('pincode');
            $table->string('phone')->unique();
            $table->string('gst_no');
            $table->string('iec_no');
            $table->string('logo');
            $table->string('customer_category');
            $table->string("organization_type");
            $table->string('lut_no');
            $table->string('state')->nullable();
            $table->string('organization_reg_no')->nullable();
            $table->string('authorised_signatory_name')->nullable();
            $table->string('authorised_signatory_designation')->nullable();
            $table->string('authorised_signatory_sex')->nullable();
            $table->string('authorised_signatory_dob')->nullable();
            $table->string('authorised_signatory_pan')->nullable();
            $table->string('authorised_signatory_aadhar')->nullable();
            $table->string('organization_email')->nullable();
            $table->string('organization_phone')->nullable();
            $table->string('firm_pan_no')->nullable();
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
