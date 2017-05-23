<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_productions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->required();
            $table->date('date')->nullable()->default(null);
            $table->integer('quantity')->required();
            $table->string('type')->required();
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
        Schema::drop('product_productions');
    }
}
