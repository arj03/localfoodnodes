<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->required(); // For loading current data
            $table->text('user')->required();
            $table->integer('node_id')->required(); // For loading current data
            $table->text('node')->required();
            $table->integer('producer_id')->required(); // For loading current data
            $table->text('producer')->required();
            $table->integer('product_id')->required(); // For loading current data
            $table->text('product')->required();
            $table->integer('variant_id')->nullable(); // For loading current data
            $table->text('variant')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_items');
    }
}
