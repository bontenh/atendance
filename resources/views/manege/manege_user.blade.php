<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/manege_user.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            
            <div class="select-school">
            所属
            <select onchange="location.href=value;">
                <option value="/manegement/manege_user?school_id=0" <?php if($school_id==0){echo 'selected';}?>>全ての利用者</option>
                <option value="/manegement/manege_user?school_id=1" <?php if($school_id==1){echo 'selected';}?>>本校</option>
                <option value="/manegement/manege_user?school_id=2" <?php if($school_id==2){echo 'selected';}?>>本町２校</option>
            </select>
            </div>
            
            <div class="menu-box">
                <ul>
                    <li><a href="/manegement/manege_new_user">新規利用者登録</a></li>
                    <li><a href="/manegement/softdelete_manege_user?school_id=0">削除した利用者</a></li>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>><a href="/manegement/manege_menu">管理者メニュー</a>>利用者管理<br />
        </div>
        <div class="main">
            <table>
                <tr>
                    <th class="id">ID</th>
                    <th class="name">氏名</th>
                    <th class="kana_name">カナ名</th>
                    <th class="belong">所属</th>
                    <th class="create">登録日時</th>
                    <th class="update">更新日時</th>
                </tr>
                @for($i=0;$i< count($users);$i++)
                <tr onclick="location.href='/manegement/change_manege_user?user_id={{$users[$i]->id}}'">
                    <td>{{$users[$i]->id}}</td>
                    <td>{{$users[$i]->last_name}} {{$users[$i]->first_name}}</td>
                    <td>{{$users[$i]->last_name_kana}} {{$users[$i]->first_name_kana}}</td>
                    <td>{{$users_school_name[$i]}}</td>
                    <td>{{$users[$i]->created_at}}</td>
                    <td>{{$users[$i]->updated_at}}</td>
                </tr>
                @endfor
            </table>
            <div class="paginate">
                {{$users->appends(['school_id'=>$school_id])->links()}}
            </div>
        </div>
    </body>
</html>
