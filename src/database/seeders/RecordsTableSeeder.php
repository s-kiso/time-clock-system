<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 1,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        DB::table('records')->insert($param);
    }
}
