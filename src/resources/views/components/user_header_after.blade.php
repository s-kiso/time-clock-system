<header class="header">
    <div class="header__logo">
        <a href="/attendance"><img src={{ asset('img/logo.png') }} alt="ロゴ"></a>
    </div>

    @if(Auth::check())
    <nav class="header__nav">
        <ul>
            <!-- 退勤後分ける処理を書く必要あり -->
            <li><a href="/attendance/list">今月の出勤一覧</a></li>
            <li><a href="/stamp_correction_request/list">申請一覧</a></li>
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