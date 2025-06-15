@extends('layouts.default')

<!-- タイトル -->
@section('title', '勤怠登録')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/attendance.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@if($status==="退勤済")
    @include('components.user_header_after')
@else
    @include('components.user_header')
@endif

<div class="contents">
    <div class="status">
        {{ $status }}
    </div>
    <div class="date">
        {{ $date }}
    </div>
    <div class="time">
        {{ $time }}
    </div>
    <div class="button">
        <form action="/attendance" method="post" class="attendance__form" id="attendance__form">
            @csrf
            <input type="hidden" value="{{ $status }}" name="status">
            <div class="attendance__form-button">
                @if($status==="勤務外")
                <button class="attendance-button">出勤</button>
                @elseif($status==="出勤中")
                <button class="attendance-button">退勤</button>
                @elseif($status==="休憩中")
                <button class="rest-button">休憩戻</button>
                @else
                <p class="clock-out-message">お疲れ様でした。</p>
                @endif
            </div>
        </form>
        @if($status==="出勤中")
        <form action="/rest" method="post" class="rest__form">
            @csrf
            <div class="rest__form-button">
                <button class="rest-button">休憩入</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection