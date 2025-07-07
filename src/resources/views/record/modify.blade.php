@extends('layouts.default')

<!-- タイトル -->
@section('title', '申請一覧')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/modify.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@if($now_user->admin_check == null)
    @include('components.user_header')
@else
    @include('components.admin_header')
@endif

<div class="main">
    <div class="contents">
        <h1 class="page__title">申請一覧</h1>

        <div class="list__header">
        @if($done_check == true)
            <h2 class="list__header-regular"><a href="/stamp_correction_request/list">承認待ち</a></h2>
            <h2 class="list__header-bold"><a href="/stamp_correction_request/list?done=true">承認済み</a></h2>    
        @else
            <h2 class="list__header-bold"><a href="/stamp_correction_request/list">承認待ち</a></h2>
            <h2 class="list__header-regular"><a href="/stamp_correction_request/list?done=true">承認済み</a></h2>    
        @endif
        </div>
        
        <table class="list__table">
            <tr class="table__header">
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        
            <div class="list__table-record">
                @foreach($modify_requests as $modify_request)
                    <tr class="table__content">
                        @if($modify_request->status == 1)
                            <td>承認待ち</td>
                        @else
                            <td>承認済み</td>
                        @endif

                        <td>{{ $modify_request->record->user->name }}</td>
                        <td>{{ $modify_request->date }}</td>
                        <td>{{ $modify_request->notes }}</td>
                        <td>{{ $modify_request->created_at->isoFormat('YYYY/MM/DD') }}</td>
                        <td class="record__detail">
                            @if($now_user->admin_check == null)
                                <a href="{{ route('record.detail', ['id'=>$modify_request->record_id]) }}">詳細</a>
                            @else
                                <a href="{{ route('modify.approve', ['attendance_correct_request'=>$modify_request->record_id]) }}">詳細</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </div>
            
        </table>

    </div>
    
</div>


@endsection