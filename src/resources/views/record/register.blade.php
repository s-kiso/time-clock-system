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

@endsection