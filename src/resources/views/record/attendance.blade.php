@extends('layouts.default')

<!-- タイトル -->
@section('title', '勤怠登録')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/register.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.user_header')

<div class="status">
    {{ $status }}
</div>
<div class="date">
    {{ $date }}
</div>
<div class="time">
    {{ $time }}
</div>
<form action="/attendance" method="post" class="attendance__form" id="attendance__form">
    @csrf
    <input type="hidden" value="{{ $status }}" name="status">
    @if($status==="勤務外")
    <button>出勤</button>
    @elseif($status==="出勤中")
    <button>退勤</button>
    @elseif($status==="休憩中")
    <button>休憩戻</button>
    @else
    <p>お疲れ様でした。</p>
    @endif
</form>
@if($status==="出勤中")
<form action="/rest" method="post" class="rest__form">
    @csrf
    <button>休憩入</button>
</form>
@endif

@endsection