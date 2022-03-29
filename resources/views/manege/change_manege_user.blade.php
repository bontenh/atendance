<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/change_manege_user.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            <div class="menu-box">
                <a href="/logout">ログアウト</a>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>><a href="/manegement/manege_menu">管理者メニュー</a>><a href="/manegement/manege_user?school_id=0">利用者管理</a>>利用者情報の変更
        </div>
        <div class="main">
            <h1>利用者情報の変更</h1>
            <form method="POST">
                <table>
                    <tr>
                        <th>氏名</th>
                        <td><input type="text" value="{{$user->last_name}}" name="last_name"></td>
                        <td><input type="text" value="{{$user->first_name}}" name="first_name"></td>
                    </tr>
                    <tr>
                        <th>カナ名</th>
                        <td><input type="text" value="{{$user->last_name_kana}}" name="last_name_kana"></td>
                        <td><input type="text" value="{{$user->first_name_kana}}" name="first_name_kana"></td>
                    </tr>
                    <tr>
                        <th>所属</th>
                        <td>
                            <select name="school_id">
                                <option value="1" <?php if($user->school_id==1){echo 'selected';}?>>本校</option>
                                <option value="2" <?php if($user->school_id==2){echo 'selected';}?>>本町２校</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="form-button">
                    <button formaction="/manegement/change_manege_user">変更</button>
                    <input type="button" onclick="history.back();" value="戻る">
                    <button formaction="/manegement/softdelete_manege_user">登録を削除</button>
                </div>
                <input type="hidden" name="user_id" value="{{$user->id}}">
            @csrf
            </form>
        @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
        </div>
    </body>
</html>
