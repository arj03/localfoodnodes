<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartDateItemLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_date_item_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->required();
            $table->integer('cart_item_id')->required();
            $table->integer('cart_date_id')->required();
            $table->integer('quantity')->required();
            $table->string('ref')->required();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cart_date_item_links');
    }
}
