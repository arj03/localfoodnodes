<?php

use Illuminate\Database\Migrations\Migration;

class AddNodeFulltextIndex extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE nodes ADD FULLTEXT search_index(name, address, zip, city)');
    }

    public function down()
    {
        DB::statement('DROP INDEX search_index ON nodes');
    }
}
