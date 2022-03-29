<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/manege_performance.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            
            <div class="select-school">
            所属
            <select onchange="location.href=value;">
                <option id="select_one" value="/manegement/manege_performance?school_id=1&select_date={{$select_date}}" <?php if($school_id==1){echo 'selected';}?>>本校</option>
                <option id="select_two" value="/manegement/manege_performance?school_id=2&select_date={{$select_date}}" <?php if($school_id==2){echo 'selected';}?>>本町２校</option>
            </select>
            日付<input type="date" id="select_date" value="{{$select_date}}" onchange="location.href='/manegement/manege_performance?school_id={{$school_id}}&select_date='+value">
            </div>
            
            <div class="menu-box">
                <ul>
                    <li><a href="/manegement/perform_new_user?status=1&select_date={{$select_date}}">実績記録作成</a></li>
                    <li><a href="/manegement/output_excel?school_id={{$school_id}}&select_date="{{$select_date}}">Excel出力プレビュー</a></li>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>><a href="/manegement/manege_menu">管理者メニュー</a>>実績記録管理
        </div>
        <div class="main">
            <table>
                <tr>
                    <th>日付</th>
                    <th>氏名</th>
                    <th>開始<br>時間</th>
                    <th>終了<br>時間</th>
                    <th>食事提供<br>加算</th>
                    <th>施設外<br>支援</th>
                    <th>医療連携<br>体制加算</th>
                    <th>備考</th>
                </tr>
                @for($i=0;$i < count($users);$i++)
                    <tr onclick="location.href='/manegement/change_perform_user?status=1&user_id={{$users[$i]->id}}&select_date={{$select_date}}'">
                        <td><?php echo date('Y/m/d',strtotime($select_date));?></td>
                        <td>{{$users[$i]->last_name}} {{$users[$i]->first_name}}</td>
                        <td><?php if(isset($performances[$i]->start)){ echo date('H:i',strtotime($performances[$i]->start));}?></td>
                        <td><?php if(isset($performances[$i]->end)){ echo date('H:i',strtotime($performances[$i]->end));}?></td>
                        <td><?php if($performances[$i]->food_fg==0){echo '';}else{echo $performances[$i]->food_fg;}?></td>
                        <td><?php if($performances[$i]->outside_fg==0){echo '';}else{echo $performances[$i]->outside_fg;}?></td>
                        <td><?php if($performances[$i]->medical_fg==0){echo '';}else{echo $performances[$i]->medical_fg;}?></td>
                        <td>{{$notes[$i]}}</td>
                    </tr>
                @endfor
            </table>
            <div class="paginate">
                {{$users->appends(['school_id'=>$school_id,'select_date'=>$select_date])->links()}}
            </div>
        </div>
    </body>
</html>
