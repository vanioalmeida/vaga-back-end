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
        // it'll be 3 users in total, 2 of them will be random
        factory(App\User::class, 2)->create();

        // the other will be "admin"
        App\User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('adminpwd'),
            'remember_token' => str_random(10),
        ]);
    }
}
