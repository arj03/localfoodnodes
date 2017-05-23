<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('owner_id')->required();
            $table->string('owner_type')->required();
            $table->string('name')->required();
            $table->text('info')->required();
            $table->dateTime('start_datetime')->required();
            $table->dateTime('end_datetime')->required();
            $table->string('address')->required();
            $table->string('zip')->required();
            $table->string('city')->required();
            $table->integer('fee')->nullable();
            $table->boolean('is_hidden')->nullable();
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
        Schema::drop('events');
    }
}
