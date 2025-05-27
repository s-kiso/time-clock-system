<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function attendance()
    {
        $date = Carbon::now()->isoFormat('YYYY年MM月DD日(ddd)');
        $time = Carbon::now()->isoFormat('HH:mm');
        
        // if文書いて$statusに入れる値を変える
        $data = Record::orderBy('id', 'desc')->first();

        // 1.前データがない(id=1), 2.退勤が打たれている, 3.出勤が打たれている
        if(!isset($data)){
            $status = '出勤';
        } elseif(isset($data->clock_out)){
            $status = '出勤';
        } elseif(isset($data->clock_in)){
            $status = "退勤";
            $status2 = "休憩入";
        } 
        

        return view('record/attendance', compact('date', 'time', 'status'));
    }
}
