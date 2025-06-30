<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '管理者1',
            'email' => 'bb@gmail.com',
            'password' => Hash::make('22222222'),
            'admin_check' => '1'
        ];
        DB::table('users')->insert($param);

        
    }
}
