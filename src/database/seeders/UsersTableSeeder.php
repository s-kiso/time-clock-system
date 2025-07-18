<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '管理者ユーザー1',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password'),
            'admin_check' => '1',
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => '一般ユーザー1',
            'email' => 'general1@example.com',
            'password' => Hash::make('password'),
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => '一般ユーザー2',
            'email' => 'general2@example.com',
            'password' => Hash::make('password'),
        ];
        DB::table('users')->insert($param);

    }
}
