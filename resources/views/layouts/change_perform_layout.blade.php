<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/manege_user.css')}}">
        <link rel="stylesheet" href="{{asset('css/manege.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-header">
            @if(isset($school_name))
                所属{{$school_name}}
            @endif
            <div class="menu-box">
                <ul>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
            @yield('manege')
        </div>
    </body>
</html>
