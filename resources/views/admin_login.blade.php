<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/admin_login.css')}}">
        <title>Laravel</title>
    </head>
    <body>
    <div class="top-head">
            <div class="head-title">出欠管理システム</div>
            <div class="head-link">
                <ul>
                    <li><a href="/login">ログイン</a></li>
                    <li><a href="/register">管理者登録</a></li>
                </ul>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>>管理者ログイン
        </div>
        <div class="main">
            <h1>管理者ログイン</h1>
            <form action="/login/do_admin_login" method="post">
                <table>
                    <tr>
                        <th>管理者名</th>
                        <td><input class="type_name" type="text" name="admin_name"></td>
                    </tr>
                    <tr>
                        <th>パスワード</th>
                        <td><input class="type_password" type="password" name="admin_password"></td>
                    </tr>
                    <tr class="tr_check">
                        <th></th>
                        <td><input type="checkbox" value="1" name="login_status">ログイン状態を保持</td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input class="login" type="submit" value="認証"></td>
                    </tr>
                </table>
                @csrf
            </form>
        @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
        </div>
    </body>
</html>
