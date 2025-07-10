<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserPassword;
use App\Models\Modify_request;
use App\Models\Modify_request_rest;
use App\Models\Record;
use App\Models\Rest;
use Carbon\Carbon;
use Illuminate\Auth\Recaller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;

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

        if (!isset($record_data)) { //記録なし＝初めての打刻（出勤）
            $status = "勤務外";
        } elseif (isset($record_data->clock_out)) { //退勤あり=全て埋まっている（お疲れさまでしたor出勤）
            $record_date = [$record_data->year, $record_data->month, $record_data->day];
            if ($compare_date == $record_date) {
                $status = "退勤済";
            } else {
                $status = "勤務外";
            }
        } else { //退勤なし出勤あり（休憩入or休憩戻or退勤）
            $record_id = $record_data->id;
            $rest_data = Rest::where('record_id', $record_id)->orderBy('id', 'desc')->first();
            if (!isset($rest_data)) {
                $status = "出勤中";
            } elseif (isset($rest_data->end)) {
                $status = "出勤中";
            } else {
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

        switch ($status) {
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
        $user_id = auth()->id();
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

            // 休憩が0でも表示するなら、勤務が終了しているかif分で判定し終了→135行目のnullを0にする

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

        return view('record/list', compact('records', 'year_month', 'days', 'month', 'date_month'));
    }

    public function listed(Request $request)
    {
        $now_month = new Carbon(str_replace('/', '-', $request->input('now')));
        $type = $request->input('type');

        if ($type == "previous") {
            $year_month = $now_month->subMonthNoOverflow();
        } else {
            $year_month = $now_month->addMonthNoOverflow();
        }

        return redirect()->route('list_home')->with(compact('year_month'));
    }

    public function detail($id)
    {
        $record = Record::find($id);
        $user = $record->user;
        $original_id = $record->id;
        $now_user = Auth::user();

        $modify_request = $record->modify_request;
        if ($modify_request != null) {
            $status = $modify_request->status;
            $rests = $modify_request->modify_request_rest;
            $record = $modify_request;
        } else {
            $status = null;
            $rests = $record->rest;
        }

        return view('record/detail', compact('record', 'rests', 'user', 'now_user', 'status', 'original_id'));
    }

    public function detailed($id, AttendanceRequest $request)
    {
        // dd($request->all());
        $record = Record::find($id);
        $now_user = Auth::user();

        $modify_record = new Modify_request();
        $modify_record->record_id = $record->id;
        $modify_record->user_id = $record->user_id;
        $modify_record->year = $record->year;
        $modify_record->month = $record->month;
        $modify_record->day = $record->day;
        $modify_record->clock_in = $request->input("clock_in");
        $modify_record->clock_out = $request->input('clock_out');
        $modify_record->notes = $request->input('notes');
        // 1:承認待ち, 2:承認済みにする
        if ($now_user->admin_check == null) {
            $modify_record->status = 1;
        } else {
            $modify_record->status = 2;
        }

        $modify_record->save();

        $rest_number = $request->input('rest_number');
        for ($i = 1; $i <= $rest_number; $i++) {
            $start_i = "start" . $i;
            $end_i = "end" . $i;
            $start = $request->input($start_i);
            $end = $request->input($end_i);

            if ($start != null) {
                $modify_rest = new Modify_request_rest();
                $modify_rest->modify_request_id = $modify_record->id;
                $modify_rest->start = $start;
                $modify_rest->end = $end;
                $modify_rest->save();
            }
        }

        $user = $record->user;
        $original_id = $record->id;
        $record = $modify_record;
        // dd($record);
        $rests = $record->modify_request_rest;
        // dd($rests);
        $status = $record->status;

        return view('record/detail', compact('record', 'rests', 'user', 'now_user', 'status', 'original_id'));
    }

    public function apply(Request $request)
    {
        $done_check = $request->query('done');
        $now_user = Auth::user();

        if ($done_check == "true") {
            if ($now_user->admin_check == null) {
                $modify_requests = Modify_request::where([
                    ['user_id', $now_user->id],
                    ['status', 2],
                ])->get();
            } else {
                $modify_requests = Modify_request::where([
                    ['status', 2],
                ])->get();
            }
        } else {
            if ($now_user->admin_check == null) {
                $modify_requests = Modify_request::where([
                    ['user_id', $now_user->id],
                    ['status', 1],
                ])->get();
            } else {
                $modify_requests = Modify_request::where([
                    ['status', 1],
                ])->get();
            }
        }

        foreach ($modify_requests as $modify_request) {
            $date = $modify_request->year . "-" . $modify_request->month . "-" . $modify_request->day;
            $date = new Carbon($date);
            $modify_request->date = $date->isoFormat('YYYY/MM/DD');
        }

        return view('record/modify', compact('now_user', 'modify_requests', 'done_check'));
    }
}
