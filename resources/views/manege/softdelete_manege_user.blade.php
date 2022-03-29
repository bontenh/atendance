<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/softdelete_manege_user.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            <div class="select-school">
            所属
            <select onchange="location.href=value;">
                <option value="/manegement/softdelete_manege_user?school_id=0" <?php if($school_id==0){echo 'selected';}?>>全ての利用者</option>
                <option value="/manegement/softdelete_manege_user?school_id=1" <?php if($school_id==1){echo 'selected';}?>>本校</option>
                <option value="/manegement/softdelete_manege_user?school_id=2" <?php if($school_id==2){echo 'selected';}?>>本町２校</option>
            </select>
            </div>
            
            <div class="menu-box">
                <ul>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>><a href="/manegement/manege_menu">管理者メニュー</a>><a href="/manegement/manege_user?school_id=0">利用者管理</a>>削除した利用者
        </div>
        <div class="main">
            <form method="post">
                <input type="hidden" name="school_id" value="{{$school_id}}">
                @csrf
                <div class="two-button">
                    <button formaction="/manegement/manege_re_register_user">再登録</button>
                    <button formaction="/manegement/manege_delete_user">完全に削除</button>
                </div>

                <table class="table">
                    <tr>
                        <th class="select">選択</th>
                        <th class="id">ID</th>
                        <th class="name">氏名</th>
                        <th class="kana_name">カナ名</th>
                        <th class="belong">所属</th>
                        <th>登録日時</th>
                        <th>削除日時</th>
                    </tr>
                    @for($i=0;$i< count($users);$i++)
                    <tr>
                        <td><input type="checkbox" name="user_id_list[]" value="{{$users[$i]->id}}"></td>
                        <td>{{$users[$i]->id}}</td>
                        <td>{{$users[$i]->last_name}} {{$users[$i]->first_name}}</td>
                        <td>{{$users[$i]->last_name_kana}} {{$users[$i]->first_name_kana}}</td>
                        <td>{{$users_school_name[$i]}}</td>
                        <td>{{$users[$i]->created_at}}</td>
                        <td>{{$users[$i]->updated_at}}</td>
                    </tr>
                    @endfor
                </table>
            </form>
        </div>
    </body>
</html>
