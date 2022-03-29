<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/output_select_excel.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            <div class="menu-box">
                <ul>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>><a href="/manegement/manege_menu">管理者メニュー</a>><a href="/manegement/manege_performance?school_id=1">実績記録管理</a>><a href="/manegement/output_excel?select_date={{$select_date}}&school_id={{$school_id}}">Excel出力プレビュー</a>>利用者選択出力
        </div>
        <div class="main">
            <form action="/manegement/output_select_excel" method="POST">
                @csrf
                <input type="submit" value="所属校と年月から一括出力">
                <div class="select_school_month">
                    <div class="select_school">
                        所属校
                        <select onchange="location.href='/manegement/output_select_excel?select_date={{$select_date}}&school_id='+value">
                                <option value="1" <?php if($school_id==1){echo 'selected';}?>>本校</option>
                                <option value="2" <?php if($school_id==2){echo 'selected';}?>>本町２校</option>
                        </select>
                    </div>
                    <div class="selec_month">
                        年月
                        <input type="month" value="{{$select_date}}" onchange="location.href='/manegement/output_select_excel?school_id={{$school_id}}&select_date='+value">
                    </div>
                </div>
                <table>
                    <tr>
                        <th class="check">選択</th>
                        <th class="user_name">名前</th>
                    </tr>
                    @for($i=0;$i < count($users);$i++)
                        <tr>
                            <td><input type="checkbox" name="user_id_list[]" value="{{$users[$i]->id}}"></td>
                            <td>{{$users[$i]->last_name}} {{$users[$i]->first_name}}</td>
                        </tr>
                    @endfor
                </table>
                <input type="hidden" name="select_date" value="{{$select_date}}">
                <input type="hidden" name="school_id" value="{{$school_id}}">
            </form>
        </div>
    </body>
</html>
