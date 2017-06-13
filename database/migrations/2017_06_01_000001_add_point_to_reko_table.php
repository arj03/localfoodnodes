<?php

use Illuminate\Database\Migrations\Migration;

class AddPointToRekoTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE reko ADD COLUMN location POINT NOT NULL;');
        DB::statement('ALTER TABLE reko ADD SPATIAL INDEX(location);');
    }

    public function down()
    {
        DB::statement('ALTER TABLE reko DROP COLUMN location;');
    }
}
