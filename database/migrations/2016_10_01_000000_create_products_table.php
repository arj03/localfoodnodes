<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('producer_id');
            $table->string('name');
            $table->text('info')->nullable();
            $table->string('package_unit')->nullable();
            $table->string('package_amount')->nullable();
            $table->string('price_unit')->required();
            $table->integer('price')->required();
            $table->text('payment_info')->nullable();
            $table->boolean('is_hidden')->nullable();
            $table->integer('deadline')->nullable();
            $table->softDeletes();
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
        Schema::drop('products');
    }
}
