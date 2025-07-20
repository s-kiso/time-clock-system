@extends('layouts.default')

<!-- タイトル -->
@section('title', '勤怠一覧')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/list.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.admin_header')

<div class="main">
    <div class="contents">
        <h1 class="page__title">{{ $date_display }}の勤怠一覧</h1>

        <div class="list__header">
            <form action="/admin/attendance/list" method="post" class="list__form" id="list__form">
                @csrf
                <div class="list__header-previous">
                    <input type="hidden" value="{{ $date }}" name="now">
                    <input type="hidden" value="previous" name="type">
                    <button><img src="{{ asset('img/arrow.png') }}" alt="左矢印">前日</button>
                </div>
            </form>
            <div class="list__header-calendar">
                <img src="{{ asset('img/calendar.png') }}" alt="カレンダー画像">
                <span class="list__header-date">{{ $date }}</span>
            </div>
            <form action="/admin/attendance/list" method="post" class="list__form" id="list__form">
                @csrf
                <div class="list__header-next">
                    <input type="hidden" value="{{ $date }}" name="now">
                    <input type="hidden" value="next" name="type">
                    <button>翌日</button>
                    <img src="{{ asset('img/arrow.png') }}" alt="右矢印">
                </div>
            </form>
        </div>
        <table class="list__table">
            <tr class="table__header">
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            <div class="list__table-record">
                @foreach($records as $record)
                    <tr class="table__content">
                        <td>{{ $record->user_name }}</td>
                        <td>{{ substr($record->clock_in, 0, 5) }}</td>
                        <td>{{ substr($record->clock_out, 0, 5) }}</td>
                        <td>{{ $record->rest_time }}</td>
                        <td>{{ $record->work_time }}</td>
                        <td class="record__detail"><a href="{{ route('record.detail', ['id'=>$record->id]) }}">詳細</a></td>
                    </tr>
                @endforeach
            </div>
        </table>
    </div>
</div>

@endsection