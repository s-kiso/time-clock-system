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

</div>
<div class="date">
    {{ $date }}
</div>
<div class="time">
    {{ $time }}
</div>
<form action="attendance" method="post" class="register__form" id="register__form">
    {{ $status }}
</form>

@endsection