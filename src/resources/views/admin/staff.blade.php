@extends('layouts.default')

<!-- タイトル -->
@section('title', 'スタッフ一覧')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/list.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.admin_header')

<div class="main">
    <div class="contents">
        <h1 class="page__title">スタッフ一覧</h1>

        <table class="list__table">
            <tr class="table__header">
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤務</th>
            </tr>
            <div class="list__table-record">
                @foreach($user_information as $user_info)
                    <tr class="table__content">
                        <td>{{ $user_info->name }}</td>
                        <td>{{ $user_info->email }}</td>
                        <td class="record__detail"><a href="{{ route('staff.detail', ['id'=>$user_info->id]) }}">詳細</a></td>
                    </tr>
                @endforeach
            </div>
        </table>
    </div>
</div>


@endsection