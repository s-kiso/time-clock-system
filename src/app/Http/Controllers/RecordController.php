<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserPassword;
use App\Models\Record;
use App\Models\Rest;
use Carbon\Carbon;
use Illuminate\Auth\Recaller;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function attendance()
    {
        $now = Carbon::now();
        $date = $now->isoFormat('YYYY年MM月DD日(ddd)');
        $time = $now->isoFormat('HH:mm');
        $compare_date = [$now->year, $now->month, $now->day];
        $user_id = auth()->id();
        
        // ログイン中ユーザーの最新出退勤データ取り出し
        $record_data = Record::where('user_id', $user_id)->orderBy('id', 'desc')->first();

        if(!isset($record_data)){ //記録なし＝初めての打刻（出勤）
            $status = "勤務外";
        }elseif(isset($record_data->clock_out)){ //退勤あり=全て埋まっている（お疲れさまでしたor出勤）
            $record_date = [$record_data->year, $record_data->month, $record_data->day];
            if($compare_date == $record_date){
                $status = "退勤済";
            }else{
                $status = "勤務外";
            }
        }else{ //退勤なし出勤あり（休憩入or休憩戻or退勤）
            $record_id = $record_data->id;
            $rest_data = Rest::where('record_id', $record_id)->orderBy('id', 'desc')->first();
            if(!isset($rest_data)){
                $status = "出勤中";
            }elseif(isset($rest_data->end)){
                $status = "出勤中";
            }else{
                $status = "休憩中";
            }
        }

        return view('record/attendance', compact('date', 'time', 'status'));
    }

    public function attended(Request $request)
    {
        $status = $request->input('status');
        $date = Carbon::now();
        $user_id = auth()->id();

        switch($status){
            case "勤務外":
                $attendance = new Record();
                $attendance->user_id = $user_id;
                $attendance->year = $date->year;
                $attendance->month = $date->month;
                $attendance->day = $date->day;
                $attendance->clock_in = $date->isoFormat('HH:mm');
                $attendance->save();
                break;

            case "出勤中":
                Record::orderBy('id', 'desc')->where('user_id', $user_id)->first()->update([
                    'clock_out' => $date->isoFormat('HH:mm')
                ]);
                break;

            case "休憩中":
                $record_id = Record::where('user_id', $user_id)->orderBy('id', 'desc')->first()->id;
                Rest::orderBy('id', 'desc')->where('record_id', $record_id)->first()->update(['end' => $date->isoFormat('HH:mm')]);
                break;

            case "退勤済":
                break;
        }

        // getにリダイレクト(再読み込み対策にもなる)
        return redirect()->route('attendance_home');
    }

    public function rest()
    {
        $date = Carbon::now();
        $user_id = auth()->id();
        $record_id = Record::where('user_id', $user_id)->orderBy('id', 'desc')->first()->id;

        $rest = new Rest();
        $rest->record_id = $record_id;
        $rest->start = $date->isoFormat('HH:mm');
        $rest->save();

        return redirect()->route('attendance_home');
    }

    public function list()
    {
        if(isset($request)){
            $year = explode('-', $request)[0];
            $month = explode('-', $request)[1];
        }else{
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
        }
        // dd($month);
        $year_month = $year . "-" . $month;
        $user_id = auth()->id();
        $records = Record::where([
            ['user_id', $user_id],
            ['year', $year],
            ['month', $month],
        ])->get();

        foreach($records as $record){
            $rests = $record->rest;
            $rest_sum = 0;
            $work_sum = 0;
            //文字列で保存されている休憩開始、終了時刻を再度Carbonにする際に日付を利用
            $date = $record->year . "-" . $record->month . "-" . $record->day;
            //日付をCarbon形式で表示するために$recordにdate列を追加
            $date_display = new Carbon($date);
            $record->date = $date_display->isoformat('MM/DD(ddd)');
            //以下の処理、テーブルに保存する段階でやっておいたほうが良い？（restsテーブルに休憩時間合計列を追加する）
            foreach($rests as $rest){
                //休憩中に一覧を見た際、endがないためif文で分ける必要がある？→今の時間までの休憩時間を表示？それともその前の休憩までの時間を表示？
                if(!isset($rest->end)){
                    $rest_sum = 0;
                }else{
                    //文字列で保存されている休憩開始、終了時刻を再度Carbonに
                    $start_hour = new Carbon($date . "" .$rest->start);
                    $end_hour = new Carbon($date . "" . $rest->end);
                    //分で休憩合計時間を算出
                    $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                }
            }
            //分で求まった合計を時間と分に分ける
            $rest_hour_sum = floor($rest_sum / 60);
            $rest_minute_sum = $rest_sum % 60;
            $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
            //strtotime関数で時間表示に
            $rest_time = date('G:i',strtotime($rest_time));
            //$recordに休憩時間を追加
            $record->rest_time=$rest_time;

            //勤務合計時間も休憩合計時間と同様に計算、$recordに追加
            if (!isset($record->clock_out)) {
                $work_sum = 0;
            } else {
                //文字列で保存されている休憩開始、終了時刻を再度Carbonに
                $start_hour = new Carbon($date . "" . $record->clock_in);
                $end_hour = new Carbon($date . "" . $record->clock_out);
                //分で勤務合計時間を算出
                $work_sum = $start_hour->diffInMinutes($end_hour);
                $work_sum = $work_sum - $rest_sum;
            }
            //分で求まった合計を時間と分に分ける
            $work_hour_sum = floor($work_sum / 60);
            $work_minute_sum = $work_sum % 60;
            $work_time = $work_hour_sum . ':' . $work_minute_sum;
            //strtotime関数で時間表示に
            $work_time = date('G:i', strtotime($work_time));
            //$recordに勤務時間を追加
            $record->work_time = $work_time;
        }
        // dd($records);
        
        return view('record/list', compact('records', 'year_month'));
    }

    public function listed(Request $request)
    {
        $request = $request->input('month');

        return redirect()->route('list_home')->with(compact('request'));
        //redirectして処理しようとしたがうまくいかない。$requestの渡し方・受け取り方は？
    }

    public function detail($id)
    {
        $record = Record::find($id);
        $rests = $record->rest;
        $user = $record->user;
        return view('record/detail', compact('record', 'rests', 'user'));
    }

}
