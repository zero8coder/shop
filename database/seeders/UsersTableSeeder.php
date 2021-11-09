<?php

namespace Database\Seeders;

use DB;
use Hash;
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
            'name' => 'zero',
            'phone' => '13160612345',
            'email' => '812419396@qq.com',
            'password' => Hash::make('password')
        ]);

    }
}
