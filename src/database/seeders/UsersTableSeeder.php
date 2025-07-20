<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
            'email_verified_at' => '2025-07-18 10:00:00',
            'password' => Hash::make('password'),
            'admin_check' => '1',
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー1',
            'email' => 'general1@example.com',
            'email_verified_at' => '2025-07-18 10:00:00',
            'password' => Hash::make('password'),
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー2',
            'email' => 'general2@example.com',
            'email_verified_at' => '2025-07-18 10:00:00',
            'password' => Hash::make('password'),
        ];
        User::create($param);

    }
}
