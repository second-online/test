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
            'name' => 'Pontius Pilate',
            'email' => 'alex@alexlacayo.com',
            'profile_picture' => 'https://cdn.dribbble.com/users/27547/avatars/normal/4fcd4e590875971421a29752885ed604.jpg?1493839816',
            'password' => bcrypt('secret'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jeff Hancock',
            'email' => 'jeff@jeffhancock.com',
            'profile_picture' => 'https://cdn.dribbble.com/users/1166392/avatars/normal/7765da9b241339c9885a24bb0c48a363.jpg?1499245430',
            'password' => bcrypt('secret'),
        ]);

        $role = App\Role::find(2);
        $userIds = [10000,10001];

        foreach ($userIds as $id) {
            $user = App\User::find($id);
            $user->roles()->attach($role);
        }
    }
}
