<?php

namespace Database\Seeders;

use App\Models\Record;
use Illuminate\Database\Seeder;


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
            'month' => 6,
            'day' => 1,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 6,
            'day' => 18,
            'clock_in' => '08:00',
            'clock_out' => '17:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 1,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 5,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 6,
            'clock_in' => '13:00',
            'clock_out' => '21:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 8,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 11,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 12,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 13,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        Record::create($param);

        $param = [
            'user_id' => 2,
            'year' => 2025,
            'month' => 7,
            'day' => 15,
            'clock_in' => '09:30',
            'clock_out' => '18:00',
        ];
        Record::create($param);
    }
}
