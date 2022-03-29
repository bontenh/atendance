<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/manege_new_user.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            <div class="menu-box">
                <a href="/logout">ログアウト</a>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>><a href="/manegement/manege_menu">管理者メニュー</a>><a href="/manegement/manege_user?school_id=0">利用者管理</a>>新規利用者登録<br />
        </div>
        <div class="main">
            <h1>新規利用者登録</h1>
            <form action="/manegement/manege_new_user" method="post">
            <table>
                <tr>
                    <th>氏名</th>
                    <td><input type="text" name="last_name"></td>
                    <td><input type="text" name="first_name"></td>
                </tr>
                <tr>
                    <th>カナ名</th>
                    <td><input type="text" name="last_name_kana"></td>
                    <td><input type="text" name="first_name_kana"></td>
                </tr>
                <tr>
                    <th>所属</th>
                        <td>
                            <select name="school_id">
                                <option value="1">本校</option>
                                <option value="2">本町２校</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="form-button">
                    <input type="submit" value="登録">
                    <input type="button" value="戻る" onclick="history.back();">
                </div>
            @csrf
            </form>
    @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
    @endforeach
        </div>
    </body>
</html>
