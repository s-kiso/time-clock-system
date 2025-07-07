@extends('layouts.default')

<!-- タイトル -->
@section('title', '勤怠登録')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.admin_header')

<div class="main">
    <div class="contents">
        <h1 class="page__title">勤怠詳細</h1>

        {{-- action未入力 --}}
        <form action="{{ route('modify.approved', ['attendance_correct_request'=>$original_id]) }}" method="post" >
        @csrf
            <table class="detail__table">
                <tr class="table__name">
                    <th class="table__header">名前</th>
                    <td class="table__content">{{ $user->name }}</td>
                </tr>
                <tr class="table__date">
                    <th class="table__header">日付</th>
                    <td class="table__content">{{ $record->year }}年</td>
                    <td></td>
                    <td class="table__content">{{ $record->month }}月{{ $record->day }}日</td>
                </tr>
                <tr class="table__time">
                    <th class="table__header">出勤・退勤</th>
                    <td class="table__content">{{ substr($record->clock_in, 0, 5) }}</td>
                    <td class="table__content-wave">～</td>
                    <td class="table__content">{{ substr($record->clock_out, 0, 5) }}</td>
                </tr>

                @if(!($rests->isEmpty()))
                    @foreach($rests as $rest)
                            <tr class="table__time">
                                <th class="table__header">
                                    @if($loop->first)
                                        休憩
                                    @else
                                        休憩{{ $loop->iteration }}
                                    @endif
                                </th>
                                <td class="table__content">{{ substr($rest->start, 0, 5) }}</td>
                                <td class="table__content-wave">～</td>
                                <td class="table__content">{{ substr($rest->end, 0, 5) }}</td>
                            </tr>
                        @endforeach
                @endif
                        
                <tr class="table__remarks">
                    <th class="table__header">備考</th>
                    <td class="table__content">{{ $record->notes }}</td>
                </tr>

            </table>

            <div class=form__submit-button>
                @if($status == 2)
                    <p class="approved">承認済み</p>
                @else
                    <button>承認</button>
                @endif
            </div>
        </form>

    </div>
    
</div>


@endsection