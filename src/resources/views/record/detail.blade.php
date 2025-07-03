@extends('layouts.default')

<!-- タイトル -->
@section('title', '勤怠登録')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css') }}">
@endsection

<!-- 本体 -->
@section('content')

{{-- @if($admin_check == "admin")
    @include('components.admin_header')
@else
    @include('components.user_header')
@endif --}}

{{-- @if($user->admin_check == null)
    @include('components.user_header')
@else
    @include('components.admin_header')
@endif --}}

@include('components.user_header')

<div class="main">
    <div class="contents">
        <h1 class="page__title">勤怠詳細</h1>

        {{-- action未入力 --}}
        <form action="" >
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
                    <td class="table__content"><input type="text" value="{{ substr($record->clock_in, 0, 5) }}" name="clock_in"></td>
                    <td>～</td>
                    <td class="table__content"><input type="text" value="{{ substr($record->clock_out, 0, 5) }}" name="clock_out"></td>
                </tr>

                {{-- 休憩は+1回分出す --}}
                @if($rests->isEmpty())
                    <tr class="table__time">
                        <th class="table__header">休憩</th>
                        <td class="table__content"><input type="text" value="" name="start"></td>
                        <td>～</td>
                        <td class="table__content"><input type="text" value="" name="end"></td>
                    </tr>
                @else
                @foreach($rests as $rest)
                <tr class="table__time">
                    <th class="table__header">
                        @if($loop->first)
                            休憩
                        @else
                            休憩{{ $loop->iteration }}
                        @endif
                    </th>
                    <td class="table__content"><input type="text" value="{{ substr($rest->start, 0, 5) }}" name="start"></td>
                    <td>～</td>
                    <td class="table__content"><input type="text" value="{{ substr($rest->end, 0, 5) }}" name="end"></td>
                </tr>
                @if($loop->last)
                    <tr class="table__time">
                        <th class="table__header">休憩{{ ($loop->iteration)+1 }}</th>
                        <td class="table__content"><input type="text" value="" name="start"></td>
                        <td>～</td>
                        <td class="table__content"><input type="text" value="" name="end"></td>
                    </tr>
                @endif
                @endforeach
                @endif

                <tr class="table__remarks">
                    <th class="table__header">備考</th>
                    <td class="table__content"><input type="text"  name="remarks"></td>
                </tr>

            </table>
            <div class=form__submit-button>
                <button>修正</button>
            </div>
        </form>

    </div>
    
</div>


@endsection