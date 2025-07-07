@extends('layouts.default')

<!-- タイトル -->
@section('title', '勤怠登録')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css') }}">
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
        <h1 class="page__title">勤怠詳細</h1>

        {{-- action未入力 --}}
        <form action="{{ route('record.modify', ['id'=>$original_id]) }}" method="post" >
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
                    @if($status == 1 || $status == 2)
                        <td class="table__content">{{ substr($record->clock_in, 0, 5) }}</td>
                        <td class="table__content-wave">～</td>
                        <td class="table__content">{{ substr($record->clock_out, 0, 5) }}</td>
                    @else
                        <td class="table__content"><input type="text" value="{{ substr($record->clock_in, 0, 5) }}" name="clock_in"></td>
                        <td class="table__content-wave">～</td>
                        <td class="table__content"><input type="text" value="{{ substr($record->clock_out, 0, 5) }}" name="clock_out"></td>
                    @endif
                </tr>

                {{-- 休憩は+1回分出す --}}
                @if($status == 1 || $status == 2)
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
                        
                @else
                    @if($rests->isEmpty())
                        <tr class="table__time">
                            <th class="table__header">休憩</th>
                            <td class="table__content"><input type="text" value="" name="start1"></td>
                            <td class="table__content-wave">～</td>
                            <td class="table__content"><input type="text" value="" name="end1"></td>
                            <input type="hidden" name="rest_number" value=1>
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
                            <td class="table__content"><input type="text" value="{{ substr($rest->start, 0, 5) }}" name="start{{ $loop->iteration }}"></td>
                            <td class="table__content-wave">～</td>
                            <td class="table__content"><input type="text" value="{{ substr($rest->end, 0, 5) }}" name="end{{ $loop->iteration }}"></td>
                        </tr>
                            @if($loop->last)
                                <tr class="table__time">
                                    <th class="table__header">休憩{{ ($loop->iteration)+1 }}</th>
                                    <td class="table__content"><input type="text" value="" name="start{{ ($loop->iteration)+1 }}"></td>
                                    <td class="table__content-wave">～</td>
                                    <td class="table__content"><input type="text" value="" name="end{{ ($loop->iteration)+1 }}"></td>
                                </tr>
                                <input type="hidden" name="rest_number" value={{$loop->iteration + 1}}>
                            @endif
                        @endforeach
                    @endif
                @endif

                <tr class="table__remarks">
                    <th class="table__header">備考</th>
                    @if($status == 1 || $status == 2)
                        <td class="table__content">{{ $record->notes }}</td>
                    @else
                        <td class="table__content"><input type="text"  name="notes"></td>
                    @endif
                </tr>

            </table>

            <div class=form__submit-button>
                @if($status == 1)
                    <p class="approving">*承認待ちのため修正はできません</p>
                @elseif($status == 2)
                    <p class="approved">修正済み</p>
                @else
                    <button>修正</button>
                @endif
            </div>
        </form>

    </div>
    
</div>


@endsection