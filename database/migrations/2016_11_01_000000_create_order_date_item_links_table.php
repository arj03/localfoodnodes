<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDateItemLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_date_item_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->required(); // Enables easy access for users
            $table->integer('producer_id')->required(); // Enables easy access for producers
            $table->integer('order_item_id')->required();
            $table->integer('order_date_id')->required();
            $table->integer('quantity')->required();
            $table->string('ref')->required();
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
        Schema::drop('order_date_item_links');
    }
}
