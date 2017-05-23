<?php

use Illuminate\Database\Migrations\Migration;

class AddPointToProducerTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE producers ADD COLUMN location POINT NOT NULL AFTER city;');
        DB::statement('ALTER TABLE producers ADD SPATIAL INDEX(location);');
    }

    public function down()
    {
        DB::statement('ALTER TABLE producers DROP COLUMN location;');
    }
}