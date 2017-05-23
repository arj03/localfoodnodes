<?php

use Illuminate\Database\Migrations\Migration;

class AddPointToUserTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE users ENGINE = MyISAM');
        DB::statement('ALTER TABLE users ADD COLUMN location POINT AFTER city;');
        DB::statement('UPDATE users SET location = GeomFromText("POINT(56.002490 13.293257)");');
        DB::statement('ALTER TABLE users MODIFY location POINT NOT NULL;');
        DB::statement('ALTER TABLE users ADD SPATIAL INDEX(location);');
    }

    public function down()
    {
        DB::statement('ALTER TABLE users DROP COLUMN location;');
        DB::statement('ALTER TABLE users ENGINE = InnoDB');
    }
}
