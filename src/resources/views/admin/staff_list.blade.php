@extends('layouts.default')

<!-- タイトル -->
@section('title', '管理者勤怠一覧')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/list.css') }}">
@endsection

<!-- 本体 -->
@section('content')


@include('components.admin_header')

<div class="main">
    <div class="contents">
        <h1 class="page__title">{{ $user_info->name }}さんの勤怠</h1>

        <div class="list__header">
            <form action={{ route('staff.detail_post', ['id'=>$user_info->id]) }} method="post" class="list__form" id="list__form">
                @csrf
                <div class="list__header-previous">
                    
                    <input type="hidden" value="{{ $year_month }}" name="now">
                    <input type="hidden" value="previous" name="type">
                    <input type="hidden" value="{{ $user_info->id }}" name="user_id">
                    <button><img src="{{ asset('img/arrow.png') }}" alt="左矢印">前月</button>
                </div>
            </form>
            <div class="list__header-calendar">
                <img src="{{ asset('img/calendar.png') }}" alt="カレンダー画像">
                <span class="list__header-date">{{ $year_month }}</span>
            </div>
            
            <form action={{ route('staff.detail_post', ['id'=>$user_info->id]) }} method="post" class="list__form" id="list__form">
                @csrf
                <div class="list__header-next">
                    <input type="hidden" value="{{ $year_month }}" name="now">
                    <input type="hidden" value="next" name="type">
                    <input type="hidden" value="{{ $user_info->id }}" name="user_id">
                    <button>翌月</button>
                    <img src="{{ asset('img/arrow.png') }}" alt="右矢印">
                </div>
            </form>
        </div>
        
            
            
        <table class="list__table">
            <tr class="table__header">
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        
            {{-- foreachの条件は月の初日～最終日 --}}
            {{-- @forでやる --}}
            
            <div class="list__table-record">
                @for($i = 1; $i <= $days; $i++)
                    <tr class="table__content">
                        <td>{{ $date_month[$i] }}</td>
        
                        @if(isset($records[$i]))
                            <td>{{ substr($records[$i]->clock_in, 0, 5) }}</td>
                            <td>{{ substr($records[$i]->clock_out, 0, 5) }}</td>
                            <td>{{ $records[$i]->rest_time }}</td>
                            <td>{{ $records[$i]->work_time }}</td>
                            <td class="record__detail"><a href="{{ route('record.detail', ['id'=>$records[$i]->id]) }}">詳細</a></td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="record__detail">詳細</td>
                        @endif
                        
                    </tr>
                @endfor
            </div>
            
        </table>

    </div>
    
</div>


@endsection