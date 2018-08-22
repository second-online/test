<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Alex Lacayo',
            'email' => 'alex@alexlacayo.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
