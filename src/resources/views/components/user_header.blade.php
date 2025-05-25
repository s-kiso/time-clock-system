<header class="header">
    <div class="header__logo">
        <a href="/attendance"><img src={{ asset('img/logo.png') }} alt="ロゴ"></a>
    </div>

    @if(Auth::check())
    <nav class="header__nav">
        <ul>
            <!-- 退勤後分ける処理を書く必要あり -->
            <li>勤怠</li>
            <li>勤怠一覧</li>
            <li>申請</li>
            <li>
                <form action="/logout" method="post">
                    @csrf
                    <button class="header__logout">ログアウト</button>
                </form>
            </li>
        </ul>
    </nav>
    @endif
</header>