<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/manege_menu.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            <div class="head-title">出欠管理システム</div>
            <div class="head-link">
                <ul>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>>管理者メニュー
        </div>
        <div class="main">
            <table>
                <tr onclick="location.href='/manegement/manege_user?school_id=0';"><td>利用者管理</td></tr>
                <tr onclick="location.href='/manegement/manege_performance?school_id=1';"><td>実績記録管理</td></tr>
            </table>
        </div>
    </body>
</html>
