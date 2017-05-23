<?php

use Illuminate\Database\Migrations\Migration;

class AddPointToNodeTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE nodes ADD COLUMN location POINT NOT NULL AFTER city;');
        DB::statement('ALTER TABLE nodes ADD SPATIAL INDEX(location);');
    }

    public function down()
    {
        DB::statement('ALTER TABLE nodes DROP COLUMN location;');
    }
}