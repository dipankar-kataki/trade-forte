<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HsnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("hsn_table", function (Blueprint $table) {
            $table->id();
            $table->text('hsn_code');
            $table->longText('hsn_description');
            $table->fullText(['hsn_code', 'hsn_description']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hsn_table');
    }
}
