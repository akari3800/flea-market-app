<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flea_market_app</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <h1 class="header__heading">
                <a href="/">
                    <img src="{{ asset('images/headerlogo.png') }}">
                </a>
            </h1>

            <div class="header__center">
                <form method="GET" action="{{ url()->current() }}" class="header-search">
                <input type="search" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                <input type="hidden" name="tab" value="{{ request('tab') }}">
                </form>
            </div>

            <div class="header__right">

                @if(!Auth::check() || !Auth::user()->hasVerifiedEmail())
                    <a class="header__link" href="/login">ログイン</a>
                @endif

                @if(Auth::check() && Auth::user()->hasVerifiedEmail())
                    <form method="POST" action="/logout" class="header__logout-form">
                    @csrf
                        <button type="submit" class="header__logout">ログアウト</button>
                    </form>
                @endif

                <a class="header__link-mypage" href="/mypage">マイページ</a>

                <button class="header__button" type="button" onclick="location.href='/sell'">
                    <span class="header__button-text">出品</span>
                </button>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>

