<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListController extends Controller
{
    public function list(Request $request)
    {
        if (!($this->isAdmin($request))) {
            return redirect('/admin/login');
        };

        $date = session('date');
        if (isset($date)) {
            $now = $date;
            $date = $date->isoFormat('YYYY/MM/DD');
        } else {
            $now = Carbon::now();
            $date = $now->isoFormat('YYYY/MM/DD');
        }
        $date_display = $now->isoFormat('YYYY年MM月DD日');

        $year = $now->year;
        $month = $now->month;
        $day = $now->day;
        $records = Record::where([
            ['year', $year],
            ['month', $month],
            ['day', $day],
        ])->get();

        foreach ($records as $loop => $record) {
            $user_info = $record->user;
            $record->user_name = $user_info->name;

            $rests = $record->rest;
            $rest_sum = 0;
            $work_sum = 0;

            foreach ($rests as $rest_loop => $rest) {
                if ($rest_loop == 0) {
                    if (!isset($rest->end)) {
                        $record->rest_time = null;
                    } else {
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                        $rest_hour_sum = floor($rest_sum / 60);
                        $rest_minute_sum = $rest_sum % 60;
                        $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                        $rest_time = date('G:i', strtotime($rest_time));
                        $record->rest_time = $rest_time;
                    }
                } else {
                    if (!isset($rest->end)) {
                        $rest_sum = $rest_sum;
                    } else {
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                    }
                    $rest_hour_sum = floor($rest_sum / 60);
                    $rest_minute_sum = $rest_sum % 60;
                    $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                    $rest_time = date('G:i', strtotime($rest_time));
                    $record->rest_time = $rest_time;
                }
            }

            if (!isset($record->clock_out)) {
                $record->work_time = null;
            } else {
                $start_hour = new Carbon($date . "" . $record->clock_in);
                $end_hour = new Carbon($date . "" . $record->clock_out);
                $work_sum = $start_hour->diffInMinutes($end_hour);
                $work_sum = $work_sum - $rest_sum;
                $work_hour_sum = floor($work_sum / 60);
                $work_minute_sum = $work_sum % 60;
                $work_time = $work_hour_sum . ':' . $work_minute_sum;
                $work_time = date('G:i', strtotime($work_time));
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

        if (!($this->isAdmin($request))) {
            return redirect('/admin/login');
        };
        $user_information = User::where('admin_check', null)->get();

        return view('admin/staff', compact('user_information'));
    }

    public function staff_detail($id, Request $request){

        if (!($this->isAdmin($request))) {
            return redirect('/admin/login');
        };

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
            $date = $record->year . "-" . $record->month . "-" . $record->day;

            foreach ($rests as $rest_loop => $rest) {

                if ($rest_loop == 0) {
                    if (!isset($rest->end)) {
                        $record->rest_time = null;
                    } else {
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                        $rest_hour_sum = floor($rest_sum / 60);
                        $rest_minute_sum = $rest_sum % 60;
                        $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                        $rest_time = date('G:i', strtotime($rest_time));
                        $record->rest_time = $rest_time;
                    }
                } else {
                    if (!isset($rest->end)) {
                        $rest_sum = $rest_sum;
                    } else {
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                    }

                    $rest_hour_sum = floor($rest_sum / 60);
                    $rest_minute_sum = $rest_sum % 60;
                    $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                    $rest_time = date('G:i', strtotime($rest_time));
                    $record->rest_time = $rest_time;
                }
            }

            if (!isset($record->clock_out)) {
                $record->work_time = null;
            } else {
                $start_hour = new Carbon($date . "" . $record->clock_in);
                $end_hour = new Carbon($date . "" . $record->clock_out);
                $work_sum = $start_hour->diffInMinutes($end_hour);
                $work_sum = $work_sum - $rest_sum;
                $work_hour_sum = floor($work_sum / 60);
                $work_minute_sum = $work_sum % 60;
                $work_time = $work_hour_sum . ':' . $work_minute_sum;
                $work_time = date('G:i', strtotime($work_time));
                $record->work_time = $work_time;
            }

            $records[$day] = $record;
            $records->pull($loop);
        }

        $get_days = new Carbon($year_month);
        $days = $get_days->daysInMonth;
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
        if (!($this->isAdmin($request))) {
            return redirect('/admin/login');
        };

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

    public function staff_detail_export(Request $request)
    {
        $now_month = new Carbon(str_replace('/', '-', $request->input('now')));
        $id = $request->input('user_id');
        $year = intval(explode('-', $now_month)[0]);
        $month = intval(explode('-', $now_month)[1]);
        $year_month = $year . "-" . $month;

        $user_id = $id;
        // $user_info = User::find($user_id);

        $records = Record::where([
            ['user_id', $user_id],
            ['year', $year],
            ['month', $month],
        ])->get();

        $get_days = new Carbon($year_month);
        $days = $get_days->daysInMonth;
        $export_records = [];

        for ($i = 1; $i <= $days; $i++) {
            $date_display = new Carbon($year_month . '-' . $i);
            $date_display = $date_display->isoformat('MM/DD(ddd)');
            $export_records[$i]['date'] = $date_display;
        }

        foreach ($records as $loop => $record) {
            $rests = $record->rest;
            $rest_sum = 0;
            $work_sum = 0;
            $day = $record->day;
            $date = $record->year . "-" . $record->month . "-" . $record->day;

            if (!isset($record->clock_out)) {
                $work_time = null;
                $export_records[$day]['work_time'] = null;
            } else {
                $start_hour = new Carbon($date . "" . $record->clock_in);
                $end_hour = new Carbon($date . "" . $record->clock_out);
                $work_sum = $start_hour->diffInMinutes($end_hour);
                $work_sum = $work_sum - $rest_sum;
                $work_hour_sum = floor($work_sum / 60);
                $work_minute_sum = $work_sum % 60;
                $work_time = $work_hour_sum . ':' . $work_minute_sum;
                $work_time = date('G:i', strtotime($work_time));
                $export_records[$day]['clock_in'] = substr($record->clock_in, 0, 5);
                $export_records[$day]['clock_out'] = substr($record->clock_out, 0, 5);
            }

            foreach ($rests as $rest_loop => $rest) {
                if ($rest_loop == 0) {
                    if (!isset($rest->end)) {
                        $export_records[$day]['rest_time'] = null;
                    } else {
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                        $rest_hour_sum = floor($rest_sum / 60);
                        $rest_minute_sum = $rest_sum % 60;
                        $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                        $rest_time = date('G:i', strtotime($rest_time));
                        $export_records[$day]['rest_time'] = $rest_time;
                    }
                } else {
                    if (!isset($rest->end)) {
                        $rest_sum = $rest_sum;
                    } else {
                        $start_hour = new Carbon($date . "" . $rest->start);
                        $end_hour = new Carbon($date . "" . $rest->end);
                        $rest_sum = $rest_sum + $start_hour->diffInMinutes($end_hour);
                    }

                    $rest_hour_sum = floor($rest_sum / 60);
                    $rest_minute_sum = $rest_sum % 60;
                    $rest_time = $rest_hour_sum . ':' . $rest_minute_sum;
                    $rest_time = date('G:i', strtotime($rest_time));
                    $export_records[$day]['rest_time'] = $rest_time;
                }
            }

            $records[$day] = $record;
            $records->pull($loop);
            $export_records[$day]['work_time'] = $work_time;
        }

        
        $csv_header = ['日付', '出勤', '退勤', '合計'];

        $csv_content = fopen('php://temp', 'r+');
        fputcsv($csv_content, $csv_header);
        foreach ($export_records as $export_record) {
            fputcsv($csv_content, $export_record);
        }
        rewind($csv_content);
        $csv_data = stream_get_contents($csv_content);
        $sjis_data = mb_convert_encoding($csv_data, 'SJIS-win', 'UTF-8');
        fclose($csv_content);
        
        return Response::make($sjis_data, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="record.csv',
        ]);



        // $get_days = new Carbon($year_month);
        // $days = $get_days->daysInMonth;
        // $date_month = [];
        // for ($i = 1; $i <= $days; $i++) {
        //     $date_display = new Carbon($year_month . '-' . $i);
        //     $date_display = $date_display->isoformat('MM/DD(ddd)');
        //     $date_month[$i] = $date_display;
        // }
        // $year_month = $get_days->format('Y/m');
        return $response;
    }

    public function approve($attendance_correct_request)
    {
        $record = Record::find($attendance_correct_request);
        $user = $record->user;
        $original_id = $record->id;

        $modify_request = $record->modify_request;
        $status = $modify_request->status;
        $rests = $modify_request->modify_request_rest;
        $record = $modify_request;

        return view('admin/approve', compact('record', 'rests', 'user', 'status', 'original_id'));
    }

    public function approved($attendance_correct_request)
    {
        $record = Record::find($attendance_correct_request);
        $user = $record->user;
        $original_id = $record->id;
        $modify_request = $record->modify_request;
        $modify_request->status = 2;
        $modify_request->update();
        $status = $modify_request->status;
        $rests = $modify_request->modify_request_rest;
        $record = $modify_request;

        return view('admin/approve', compact('record', 'rests', 'user', 'status', 'original_id'));
    }

    private function isAdmin(Request $request) {
        $user = $request->user();
        if (!(isset($user) && $user->admin_check)) {
            return false;
        }
        return true;
    }
}
