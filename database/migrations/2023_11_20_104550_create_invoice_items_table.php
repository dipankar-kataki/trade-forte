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
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('items_added_by');
            $table->string('hsn_code')->nullable();
            $table->text('description')->nullable();
            $table->string('unit_type')->nullable();
            $table->integer('unit_value')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('weight')->default(0);
            $table->integer('net_weight')->default(0);
            $table->integer('total_value')->default(0);
            $table->timestamps();
            $table->foreign('items_added_by')->references('id')->on('users');
            $table->foreign('invoice_id')->references('id')->on('invoice_details');
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
