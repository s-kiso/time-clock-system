<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function list()
    {
        $date = session('date');
        if (isset($date)) {
            $now = $date;
            $date = $date->isoFormat('YYYY/MM/DD');
        } else {
            $now = Carbon::now();
            $date = $now->isoFormat('YYYY/MM/DD');
        }

        $date_display = $now->isoFormat('YYYY年MM月DD日');

        return view('admin/list', compact('date', 'date_display'));
    }

    public function listed(Request $request)
    {
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
}
