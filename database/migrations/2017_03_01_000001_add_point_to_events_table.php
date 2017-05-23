<?php

use Illuminate\Database\Migrations\Migration;

class AddPointToEventsTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE events ADD COLUMN location POINT NOT NULL AFTER city;');
        DB::statement('ALTER TABLE events ADD SPATIAL INDEX(location);');
    }

    public function down()
    {
        DB::statement('ALTER TABLE events DROP COLUMN location;');
    }
}
