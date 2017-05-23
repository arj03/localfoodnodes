<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducerNodeLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producer_node_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('producer_id')->unsigned();
            $table->integer('node_id')->unsigned();
            $table->unique(['producer_id', 'node_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('producer_node_links');
    }
}
