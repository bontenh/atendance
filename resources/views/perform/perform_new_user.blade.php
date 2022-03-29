<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/perform_new_user.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            
            <div class="select-school">
                所属
                <span>{{$school_name}}</span>
            </div>
            
            <div class="menu-box">
                <ul>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>><a href="/manegement/manege_menu">管理者メニュー</a>><a href="/manegement/manege_performance?school_id=1">実績記録管理</a>>実績記録の変更<br />
        </div>
        <div class="main">
            <h1>実績記録作成</h1>
        <form method="POST">
            <table>
                <tr>
                    <td>日付</td>
                    <td><input class="insert-date" type="date" value="{{$select_date}}" name="insert_date"></td>
                </tr>
                <tr>
                    <td>利用者</td>
                    <td>
                        <select name="user_id">
                            @for($i=0;$i< count($users);$i++)
                                <option value="{{$users[$i]->id}}" <?php if($users[$i]->id==$user_id){echo 'selected';}?>>{{$users[$i]->last_name}} {{$users[$i]->first_name}}</option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>開始時間</td>
                    <td>
                        <select name="start">
                            @for($i=0;$i< count($attendance_date);$i++)
                                <option>{{$attendance_date[$i]}}</option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>終了時間</td>
                    <td>
                        <select name="end">
                            @for($i=0;$i< count($attendance_date);$i++)
                                <option <?php if($i==count($attendance_date)-1){echo 'selected';}?>>{{$attendance_date[$i]}}</option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>食事提供加算</td>
                    <td><input type="checkbox" name="food_fg" value="1"></td>
                </tr>
                <tr>
                    <td>施設外支援</td>
                    <td><input type="checkbox" name="outside_fg" value="1"></td>
                </tr>
                <tr>
                    <td>医療連携体制加算</td>
                    <td><input type="checkbox" name="medical_fg" value="1"></td>
                </tr>
                <tr>
                    <td>備考</td>
                    <td>
                        <select name="note_id">
                            <option value="1">通所</option>
                            <option value="2">Skype</option>
                            <option value="3">メール</option>
                            <option value="4">訪問</option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="form-button">
                <button formaction="/manegement/perform_new_user">作成</button>
                <input type="button" onclick="history.back()" value="戻る">
            </div>
            <input type="hidden" name="status" value="{{$status}}">
            @csrf
            </form>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </div>
    </body>
</html>
