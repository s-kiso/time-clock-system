<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\User;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    public function list(Request $request)
    {
        // 管理者権限の確認
        // なぜかこれだけ一般ユーザーログインに飛ばされる
        if (!($this->isAdmin($request))) {
            return redirect('/admin/login');
        };

        // 日付の取得・表示
        $date = session('date');
        if (isset($date)) {
            $now = $date;
            $date = $date->isoFormat('YYYY/MM/DD');
        } else {
            $now = Carbon::now();
            $date = $now->isoFormat('YYYY/MM/DD');
        }
        $date_display = $now->isoFormat('YYYY年MM月DD日');

        // 当日の勤怠情報の取得
        $year = $now->year;
        $month = $now->month;
        $day = $now->day;
        $records = Record::where([
            ['year', $year],
            ['month', $month],
            ['day', $day],
        ])->get();

        foreach ($records as $loop => $record) {
            // 社員情報の取得
            $user_info = $record->user;
            $record->user_name = $user_info->name;

            $rests = $record->rest;
            $rest_sum = 0;
            $work_sum = 0;
            
            foreach ($rests as $rest_loop => $rest) {
                //休憩中に一覧を見た際、endがないためif文で分ける必要がある？→今の時間までの休憩時間を表示？それともその前の休憩までの時間を表示？
                // dd($rest_loop);
                if ($rest_loop == 0) {
                    if (!isset($rest->end)) {
                        $record->rest_time = null;
                    } else {
                        //文字列で保存されている休憩開始、終了時刻を再度Carbonに
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        //分で休憩合計時間を算出
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                        //分で求まった合計を時間と分に分ける
                        $rest_hour_sum = floor($rest_sum / 60);
                        $rest_minute_sum = $rest_sum % 60;
                        $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                        //strtotime関数で時間表示に
                        $rest_time = date('G:i', strtotime($rest_time));
                        //$recordに休憩時間を追加
                        $record->rest_time = $rest_time;
                    }
                } else {
                    if (!isset($rest->end)) {
                        $rest_sum = $rest_sum;
                    } else {
                        //文字列で保存されている休憩開始、終了時刻を再度Carbonに
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        //分で休憩合計時間を算出
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                    }
                    //分で求まった合計を時間と分に分ける
                    $rest_hour_sum = floor($rest_sum / 60);
                    $rest_minute_sum = $rest_sum % 60;
                    $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                    //strtotime関数で時間表示に
                    $rest_time = date('G:i', strtotime($rest_time));
                    //$recordに休憩時間を追加
                    $record->rest_time = $rest_time;
                }
            }

            //勤務合計時間も休憩合計時間と同様に計算、$recordに追加
            if (!isset($record->clock_out)) {
                $record->work_time = null;
            } else {
                //文字列で保存されている休憩開始、終了時刻を再度Carbonに
                $start_hour = new Carbon($date . "" . $record->clock_in);
                $end_hour = new Carbon($date . "" . $record->clock_out);
                //分で勤務合計時間を算出
                $work_sum = $start_hour->diffInMinutes($end_hour);
                $work_sum = $work_sum - $rest_sum;
                //分で求まった合計を時間と分に分ける
                $work_hour_sum = floor($work_sum / 60);
                $work_minute_sum = $work_sum % 60;
                $work_time = $work_hour_sum . ':' . $work_minute_sum;
                //strtotime関数で時間表示に
                $work_time = date('G:i', strtotime($work_time));
                //$recordに勤務時間を追加
                $record->work_time = $work_time;
            }
        }

        return view('admin/list', compact('date', 'date_display', 'records'));
    }

    public function listed(Request $request)
    {
        if(!($this->isAdmin($request))) {
            return redirect('/login');
        };
        $now = $request->input('now');
        $now_date = new Carbon($request->input('now'));
        $type = $request->input('type');

        if ($type == "previous") {
            $date = $now_date->subDay();
        } else {
            $date = $now_date->addDay();
        }

        return redirect()->route('admin_list_home')->with(compact('date'));
    }

    public function staff_list(Request $request){
        // 管理者権限の確認
        if (!($this->isAdmin($request))) {
            return redirect('/admin/login');
        };

        $user_information = User::where('admin_check', null)->get();
        
        return view('admin/staff', compact('user_information'));
    }

    public function staff_detail($id, Request $request){

        // 管理者権限の確認
        if (!($this->isAdmin($request))) {
            return redirect('/admin/login');
        };

        //sessionで受け取ったデータを消す必要があるかも？session_start, delete
        $year_month = session('year_month');
        if (isset($year_month)) {
            $year = intval(explode('-', $year_month)[0]);
            $month = intval(explode('-', $year_month)[1]);
        } else {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
        }
        $year_month = $year . "-" . $month;

        $user_id = $id;
        $user_info = User::find($user_id);

        $records = Record::where([
            ['user_id', $user_id],
            ['year', $year],
            ['month', $month],
        ])->get();

        foreach ($records as $loop => $record) {
            $rests = $record->rest;
            $rest_sum = 0;
            $work_sum = 0;
            $day = $record->day;
            //文字列で保存されている休憩開始、終了時刻を再度Carbonにする際に日付を利用
            $date = $record->year . "-" . $record->month . "-" . $record->day;
            // 日付をCarbon形式で表示するために$recordにdate列を追加
            // $date_display = new Carbon($date);
            // $record->date = $date_display->isoformat('MM/DD(ddd)');
            //以下の処理、テーブルに保存する段階でやっておいたほうが良い？（restsテーブルに休憩時間合計列を追加する）
            foreach ($rests as $rest_loop => $rest) {
                //休憩中に一覧を見た際、endがないためif文で分ける必要がある？→今の時間までの休憩時間を表示？それともその前の休憩までの時間を表示？
                // dd($rest_loop);
                if ($rest_loop == 0) {
                    if (!isset($rest->end)) {
                        $record->rest_time = null;
                    } else {
                        //文字列で保存されている休憩開始、終了時刻を再度Carbonに
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        //分で休憩合計時間を算出
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                        //分で求まった合計を時間と分に分ける
                        $rest_hour_sum = floor($rest_sum / 60);
                        $rest_minute_sum = $rest_sum % 60;
                        $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                        //strtotime関数で時間表示に
                        $rest_time = date('G:i', strtotime($rest_time));
                        //$recordに休憩時間を追加
                        $record->rest_time = $rest_time;
                    }
                } else {
                    if (!isset($rest->end)) {
                        $rest_sum = $rest_sum;
                    } else {
                        //文字列で保存されている休憩開始、終了時刻を再度Carbonに
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        //分で休憩合計時間を算出
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                    }
                    //分で求まった合計を時間と分に分ける
                    $rest_hour_sum = floor($rest_sum / 60);
                    $rest_minute_sum = $rest_sum % 60;
                    $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                    //strtotime関数で時間表示に
                    $rest_time = date('G:i', strtotime($rest_time));
                    //$recordに休憩時間を追加
                    $record->rest_time = $rest_time;
                }
            }

            //勤務合計時間も休憩合計時間と同様に計算、$recordに追加
            if (!isset($record->clock_out)) {
                $record->work_time = null;
            } else {
                //文字列で保存されている休憩開始、終了時刻を再度Carbonに
                $start_hour = new Carbon($date . "" . $record->clock_in);
                $end_hour = new Carbon($date . "" . $record->clock_out);
                //分で勤務合計時間を算出
                $work_sum = $start_hour->diffInMinutes($end_hour);
                $work_sum = $work_sum - $rest_sum;
                //分で求まった合計を時間と分に分ける
                $work_hour_sum = floor($work_sum / 60);
                $work_minute_sum = $work_sum % 60;
                $work_time = $work_hour_sum . ':' . $work_minute_sum;
                //strtotime関数で時間表示に
                $work_time = date('G:i', strtotime($work_time));
                //$recordに勤務時間を追加
                $record->work_time = $work_time;
            }
            // $records[日付]という形にする
            $records[$day] = $record;
            // 元の$recordを削除
            $records->pull($loop);
        }

        $get_days = new Carbon($year_month);
        $days = $get_days->daysInMonth;
        //繰り返し使って06/01~30までのデータを作り、$date(日付)に入れてcompactで渡してやりたい
        $date_month = [];
        for ($i = 1; $i <= $days; $i++) {
            $date_display = new Carbon($year_month . '-' . $i);
            $date_display = $date_display->isoformat('MM/DD(ddd)');
            $date_month[$i] = $date_display;
        }
        $year_month = $get_days->format('Y/m');

        return view('admin/staff_list', compact('records', 'year_month', 'days', 'month', 'date_month', 'user_info'));
    }

    public function staff_detail_post(Request $request)
    {
        $now_month = new Carbon(str_replace('/', '-', $request->input('now')));
        $type = $request->input('type');
        $id = $request->input('user_id');

        if ($type == "previous") {
            $year_month = $now_month->subMonthNoOverflow();
        } else {
            $year_month = $now_month->addMonthNoOverflow();
        }

        return redirect()->route('staff.detail', ['id'=>$id])->with(compact('year_month'));
    }

    // public function detail($id)
    // {
    //     $record = Record::find($id);
    //     $rests = $record->rest;
    //     $user = $record->user;
    //     $admin_check = "admin";

    //     return view('record/detail', compact('record', 'rests', 'user', 'admin_check'));
    // }

    private function isAdmin(Request $request) {
        $user = $request->user();
        // dd(isset($user) && $user->admin_check);
        if (!(isset($user) && $user->admin_check)) {
            return false;
        }

        return true;
    }
}
