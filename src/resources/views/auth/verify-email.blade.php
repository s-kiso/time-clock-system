@extends('layouts.default')

<!-- タイトル -->
@section('title', 'メール認証')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/verify.css') }}">
@endsection

<!-- 本体 -->
@section('content')

@include('components.user_header')

<div class="mail_notice--div">
    <div class="mail_notice--header">
        <p class="notice_header--p">登録していただいたメールアドレスに認証メールを送付しました。</p>
        <p class="notice_header--p">メール認証を完了してください。</p>
    </div>

    <div class="mail_notice--content">
        @if (session('resent'))
        <p class="notice_resend--p" role="alert">
            新規認証メールを再送信しました！
        </p>
        @endif
        <form class="mail_verify--link" method="GET" action="http://localhost:8025">
            <input type="submit" class="mail_verify--button" value="認証はこちらから">
        </form>

        <form class="mail_resend--form" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <input type="submit" class="mail_resend--button" value="認証メールを再送する">
        </form>
    </div>
</div>
@endsection