<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::create([
            'id' => 1,
            'name' => 'mike',
            'first_name' => 'Michael',
            'last_name' => 'Stahle',
            'email' => 'MichaelStahle@nascencetechnologies.com',
            'password' => bcrypt('mike'),
            'role_id' => 2,
        ]);

        User::create([
            'id' => 2,
            'name' => 'tony',
            'first_name' => 'Tony',
            'last_name' => 'Bergeson',
            'email' => 'tonybergeson@nascencetechnologies.com',
            'password' => bcrypt('tony'),
            'role_id' => 2,
        ]);

        User::create([
            'id' => 3,
            'name' => 'chris',
            'first_name' => 'Chris',
            'last_name' => 'Cahoon',
            'email' => 'chris@meccapm.com',
            'password' => bcrypt('chris'),
            'role_id' => 2,
        ]);

        User::create([
            'id' => 4,
            'name' => 'Dima',
            'first_name' => 'Dmytro',
            'last_name' => 'Matviienko',
            'email' => 'dmytro@aabocrm.com',
            'password' => bcrypt('dima'),
            'role_id' => 2,
        ]);

        User::create([
            'id' => 5,
            'name' => 'Chanthai',
            'first_name' => 'Chanthai',
            'last_name' => 'Sali',
            'email' => 'chanthaisali@gmail.com',
            'password' => bcrypt('chanthai'),
            'role_id' => 2,
        ]);
    }
}