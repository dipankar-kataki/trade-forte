<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */public function up()
    {
        Schema::create("user_log", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->text('activity');
            $table->string('resource_name');
            $table->unsignedBigInteger("resource_id");
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_log');
    }
}
