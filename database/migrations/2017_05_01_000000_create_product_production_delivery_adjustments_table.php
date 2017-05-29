<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductProductionDeliveryAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_production_delivery_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->required();
            $table->integer('node_id')->required();
            $table->date('date')->required();
            $table->integer('quantity')->required();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_production_delivery_adjustments');
    }
}
