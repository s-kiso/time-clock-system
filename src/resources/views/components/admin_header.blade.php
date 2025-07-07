<header class="header">
    <div class="header__logo">
        <a href="/attendance"><img src={{ asset('img/logo.png') }} alt="ロゴ"></a>
    </div>

    @if(Auth::check())
    <nav class="header__nav">
        <ul>
            <li><a href="/admin/attendance/list">勤怠一覧</a></li>
            <li><a href="/admin/staff/list">スタッフ一覧</a></li>
            <li><a href="/stamp_correction_request/list">申請一覧</a></li>
            <li>
                <form action="/admin/logout" method="post">
                    @csrf
                    <button class="header__logout">ログアウト</button>
                </form>
            </li>
        </ul>
    </nav>
    @endif
</header>