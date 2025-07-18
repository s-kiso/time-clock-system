<?php

namespace Database\Seeders;

use App\Models\Rest;
use Illuminate\Database\Seeder;

class RestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'record_id' => 1,
            'start' => '12:00',
            'end' => '13:00',
        ];
        Rest::create($param);

        $param = [
            'record_id' => 2,
            'start' => '12:00',
            'end' => '13:00',
        ];
        Rest::create($param);

        $param = [
            'record_id' => 3,
            'start' => '12:00',
            'end' => '13:00',
        ];
        Rest::create($param);

        $param = [
            'record_id' => 4,
            'start' => '12:00',
            'end' => '13:00',
        ];
        Rest::create($param);

        $param = [
            'record_id' => 5,
            'start' => '15:00',
            'end' => '15:30',
        ];
        Rest::create($param);

        $param = [
            'record_id' => 5,
            'start' => '19:00',
            'end' => '20:00',
        ];
        Rest::create($param);

        $param = [
            'record_id' => 6,
            'start' => '12:00',
            'end' => '13:00',
        ];
        Rest::create($param);

        $param = [
            'record_id' => 7,
            'start' => '12:00',
            'end' => '13:00',
        ];
        Rest::create($param);

        $param = [
            'record_id' => 8,
            'start' => '12:00',
            'end' => '13:00',
        ];
        Rest::create($param);
    }
}
