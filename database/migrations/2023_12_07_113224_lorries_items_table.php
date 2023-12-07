<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LorriesItemsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("lorry_items", function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->string('trip');
            $table->string('vehiclle_no');
            $table->string('quantity');
            $table->unsignedBigInteger("lorry_id");
            $table->foreign('lorry_id')->references('id')->on('lorries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_log');
    }
}
