@extends('layouts.default')

<!-- タイトル -->
@section('title', '勤怠登録')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/list.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.user_header')

<h1 class="page__title">勤怠一覧</h1>

<form action="/attendance/list" method="post" class="list__form" id="list__form">
@csrf
    <ul class="list__header">
        <li>前月</li>
        {{-- valueの入れ方が分からない --}}
        <li><input type="month" value= "{{ $year_month }}" name="month"></li>
        <button type="submit">表示</button>
        <li>翌月</li>
    </ul>
</form>


<table class="list__table">
    <tr class="table__header">
        <th>日付</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>休憩</th>
        <th>合計</th>
        <th>詳細</th>
    </tr>

    @foreach($records as $record)
    <div class="list__table-record">
        <tr class="table__content">
            <td>{{ $record->date }}
            <td>{{ substr($record->clock_in, 0, 5) }}</td>
            <td>{{ substr($record->clock_out, 0, 5) }}</td>
            <td>{{ $record->rest_time }}</td>
            <td>{{ $record->work_time }}</td>
            <td>
                {{-- <form action="{{ route('record.detail', ['id'=>$record->id]) }}" method="post" class="detail__form" id="detail__form">
                    <input type="hidden" value={{ $record->id }} name="record_id">
                    <button type="submit">詳細</button>
                </form> --}}
                <a href="{{ route('record.detail', ['id'=>$record->id]) }}">詳細</a>
            </td>
        </tr>
    </div>
    @endforeach
</table>

@endsection