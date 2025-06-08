@extends('layouts.default')

<!-- タイトル -->
@section('title', '勤怠登録')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.user_header')

<h1 class="page__title">勤怠詳細</h1>

<table class="detail__table">
    <tr class="table__name">
        <th class="table__header">名前</th>
        <td class="table__content">{{ $user->name }}</td>
    </tr>
    <tr class="table__date">
        <th class="table__header">日付</th>
        <td class="table__content">{{ $record->year }}</td>
        <td class="table__content">{{ $record->month }}</td>
    </tr>
</table>

@endsection