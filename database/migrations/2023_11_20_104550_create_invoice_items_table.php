<?php

use App\Models\PackagingDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_details_id');
            $table->unsignedBigInteger('users_id');
            $table->string('hsn_code');
            $table->text('product_name');
            $table->string('unit_type');
            $table->integer('unit_value');
            $table->integer('uqc');
            $table->integer('quantity');
            $table->integer('net_weight_of_each_unit');
            $table->integer('gst_rate');
            $table->integer('cess_rate');
            $table->timestamps();
            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('invoice_details_id')->references('id')->on('invoice_details');
        });
    }

    public function packagingDetails()
    {
        return $this->hasMany(PackagingDetail::class, 'invoice_item_id', 'id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
