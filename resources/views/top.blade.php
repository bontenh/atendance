<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/top.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            <div class="head-title">出欠管理システム</div>
            <div class="head-link">
                <ul>
                    <li><a href="/manegement/manege_menu">管理者メニュー</a></li>
                    <li><a href="login">ログイン</a></li>
                </ul>
            </div>
        </div>
        <div class="main">
            <h1>打刻開始する校を選択</h1>
            <table>
                <tr onclick="location.href='/attendance?school_id=1&select=0';"><td>本校</td></tr>
                <tr onclick="location.href='/attendance?school_id=2&select=0';"><td>本町２校</td></tr>
            </table>
        </div>
    </body>
</html>
