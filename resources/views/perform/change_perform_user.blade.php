<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/change_perform_user.css')}}">
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
            <h1>実績記録の変更</h1>
        <form method="POST">
            <table>
                <tr>
                    <td>日付</td>
                    <td class="insert-date"><?php echo (new DateTime($perform_user->insert_date))->format('n/d');?></td>
                </tr>
                <tr>
                    <td>利用者</td>
                    <td>
                        <select name="user_id">
                            @for($i=0;$i< count($users);$i++)
                                <option value="{{$users[$i]->id}}" <?php if($users[$i]->id==$perform_user->user_id){echo 'selected';}?>>{{$users[$i]->last_name}} {{$users[$i]->first_name}}</option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>開始時間</td>
                    <td>
                        <select name="start">
                            @for($i=0;$i< count($attendance_date);$i++)
                                <option <?php if(strtotime($perform_user->start)==strtotime($attendance_date[$i])){echo 'selected';}?>>{{$attendance_date[$i]}}</option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>終了時間</td>
                    <td>
                        <select name="end">
                            @for($i=0;$i< count($attendance_date);$i++)
                                <option <?php if(strtotime($perform_user->end)==strtotime($attendance_date[$i])){echo 'selected';}?>>{{$attendance_date[$i]}}</option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>食事提供加算</td>
                    <td><input type="checkbox" name="food_fg" value="1" <?php if($perform_user->food_fg==1){echo 'checked';}?>></td>
                </tr>
                <tr>
                    <td>施設外支援</td>
                    <td><input type="checkbox" name="outside_fg" value="1" <?php if($perform_user->outside_fg==1){echo 'checked';}?>></td>
                </tr>
                <tr>
                    <td>医療連携体制加算</td>
                    <td><input type="checkbox" name="medical_fg" value="1" <?php if($perform_user->medical_fg==1){echo 'checked';}?>></td>
                </tr>
                <tr>
                    <td>備考</td>
                    <td>
                        <select name="note_id">
                            <option value="1" <?php if($perform_user->note_id==1){echo 'selected';}?>>通所</option>
                            <option value="2" <?php if($perform_user->note_id==2){echo 'selected';}?>>Skype</option>
                            <option value="3" <?php if($perform_user->note_id==3){echo 'selected';}?>>メール</option>
                            <option value="4" <?php if($perform_user->note_id==4){echo 'selected';}?>>訪問</option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="form-button">
                <button formaction="/manegement/change_perform_user">変更</button>
                <input type="button" onclick="history.back()" value="戻る">
                <button formaction="/manegement/delete_perform_user">実績記録を削除</button>
            </div>
            <input type="hidden" name="select_date" value="{{$perform_user->insert_date}}">
            <input type="hidden" name="status" value="{{$status}}">
            @csrf
            </form>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </div>
    </body>
</html>
