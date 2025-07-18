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
    }
}
