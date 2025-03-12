<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
            [
                'name' => 'admin',
                'description' => 'manager of the application'
            ]
        );

        DB::table('roles')->insert(
            [
                'name' => 'user',
                'description' => 'user of the application'
            ]
        );

        DB::table('users')->insert(
            [
                'firstname' => 'admin',
                'lastname' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('adminpass'),
                'status' => 'active',
                'role_id' => 1,
            ]
        );
    }
}
