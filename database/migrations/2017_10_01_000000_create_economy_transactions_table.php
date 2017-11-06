<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEconomyTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('economy_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash')->required();
            $table->date('date')->required();
            $table->string('ref')->required();
            $table->string('description')->required();
            $table->integer('amount')->required();
            $table->string('category')->nullable();
            $table->unique('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('economy_transactions');
    }
}
