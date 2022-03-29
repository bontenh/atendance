<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/top.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-header">
            <p>出欠管理システム</p>
            @if($status==1)
            <ul>
                <li><a href="/top/manege_menu">管理者メニュー</a></li>
                <li><a href="login">ログイン</a></li>
            </ul>
            @elseif($status==2)
            <ul>
                <li><a href="login">ログイン</a></li>
                <li><a href="register">管理者登録</a></li>
            </ul>
            @elseif($status==3)
            <ul>
                <li><a href="login">ログイン</a></li>
                <li><a href="register">管理者登録</a></li>
            </ul>
            @elseif($status==4)
            @endif
        </div>
        <div class="container">
            @yield('top')
        </div>
    </body>
</html>
