<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/change_complete_layout.css')}}">
        <link rel="stylesheet" href="{{asset('css/change_perform_complete.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-header">
            <div class="menu-box">
                <a href="/logout">ログアウト</a>
            </div>
        </div>
        <div class="container">
            @yield('manege')
        </div>
    </body>
</html>
