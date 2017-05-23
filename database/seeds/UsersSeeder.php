<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'David Ajnered',
            'email' => 'davidajnered@gmail.com',
            'password' => bcrypt('password'),
            'address' => 'Hantverksgatan 5',
            'zip' => '26868',
            'city' => 'Röstånga',
        ]);
    }
}
