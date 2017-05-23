<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producers', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->string('zip');
            $table->string('city');
            $table->text('info')->nullable();
            $table->string('currency')->nullable();
            $table->string('payment_bank')->nullable();
            $table->string('payment_account')->nullable();
            $table->string('payment_swish')->nullable();
            $table->text('payment_info')->nullable();
            $table->string('link_homepage')->nullable();
            $table->string('link_facebook')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('link_twitter')->nullable();
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
        Schema::drop('producers');
    }
}
