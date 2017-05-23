<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->required();
            $table->integer('node_id')->required();
            $table->text('node')->required();
            $table->integer('producer_id')->required();
            $table->text('producer')->required();
            $table->integer('product_id')->required();
            $table->text('product')->required();
            $table->integer('variant_id')->nullable();
            $table->text('variant')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cart_items');
    }
}
