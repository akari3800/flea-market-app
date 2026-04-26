<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flea_market_app</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/header.css')}}">
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
        </header>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>

</html>