<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Laravel</title>
    </head>
    <body>
        <div class="bg-info text-white">
            <nav class="navbar">
                    <dev class="navbar-brand">出欠管理システム</dev>
                    <a href="login">ログイン</a>
                    <a href="register">管理者登録</a>
            </nav>
            <div class="container">
                @yield('admin')
            </div>
        </div>
    </body>
</html>
