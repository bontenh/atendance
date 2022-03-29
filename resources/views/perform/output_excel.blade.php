<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/output_excel.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-head">
            
            <div class="select-school">
            所属
            <select onchange="location.href='/manegement/output_excel?select_date={{$select_date}}&school_id='+value">
                <option value="1" <?php if($school_id==1){echo 'selected';}?>>本校</option>
                <option value="2" <?php if($school_id==2){echo 'selected';}?>>本町２校</option>
            </select>
            日付
            <input type="month" value="{{$select_date}}" onchange="location.href='/manegement/output_excel?school_id={{$school_id}}&user_id={{$user_id}}&select_date='+value">
            利用者名
            <select onchange="location.href='/manegement/output_excel?select_date={{$select_date}}&school_id={{$school_id}}&user_id='+value">
            @for($i=0;$i< count($users);$i++)
                <option value="{{$users[$i]->id}}" <?php if($users[$i]->id==$user_id){$id=$i;echo 'selected';}?>>{{$users[$i]->last_name}} {{$users[$i]->first_name}}</option>
            @endfor
            </select>
            </div>
            
            <div class="menu-box">
                <ul>
                    <li><a href="/manegement/output_select_excel?select_date={{$select_date}}&school_id={{$school_id}}">利用者選択出力画面</a></li>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="link-menu">
            <a href="/top">Top</a>><a href="/manegement/manege_menu">管理者メニュー</a>><a href="/manegement/manege_performance?school_id=1">実績記録管理</a>>Excel出力プレビュー
        </div>
        <div class="output-preview">
            <div class="year-select"><?php echo date('Y年m月',strtotime($select_date))?></div>
            <div class="preview"><a href="/manegement/output_perform_excel?user_id={{$user_id}}&select_date={{$select_date}}">プレビューを出力</a></div>
        </div>
        <div class="main">
        <table class="table" border="1">
            <tr>
                <th colspan="3">支給障害者氏名</th><th colspan="6" rowspan="2">実務記録表</th><th colspan="2">事業者及びその事業所</th>
            </tr>
            <tr>
                <th colspan="3">{{$users[$id]->last_name}} {{$users[$id]->first_name}}</th><th colspan="2"><?php if($school_id==2){echo '本町２校';}else{echo '本校';}?></th>
            </tr>
            <tr>
                <th rowspan="2">日付</th><th rowspan="2">曜日</th><th colspan="7">サービス提供者</th><th rowspan="2">備考</th><th rowspan="2">利用者<br>確認印</th>
            </tr>
            <tr>
                <th>サービス提供の状況</th><th>開始<br>時間</th><th>終了<br>時間</th><th>訪問支援特別加算<br>時間数</th><th>食事提供<br>加算</th><th>施設外<br>支援</th><th>医療連携<br>体制加算</th>
            </tr>
            @for($i=0;$i< count($performances);$i++)
                @if(isset($performances[$i]))
                <tr onclick="location.href='/manegement/change_perform_user?status=2&user_id={{$user_id}}&select_date={{$select_date}}-'+{{$i+1}}">
                    <td>{{$i+1}}日</td>
                    <td>{{$weeks[$i+1-1]}}</td>
                    <td></td>
                    <td><?php echo date('H:i',strtotime($performances[$i]->start));?></td>
                    <td><?php echo date('H:i',strtotime($performances[$i]->end));?></td>
                    <td></td>
                    <td><?php if($performances[$i]->food_fg==0){echo '';}else{echo $performances[$i]->food_fg;}?></td>
                    <td><?php if($performances[$i]->outside_fg==0){echo '';}else{echo $performances[$i]->outside_fg;}?></td>
                    <td><?php if($performances[$i]->medical_fg==0){echo '';}else{echo $performances[$i]->medical_fg;}?></td>
                    <td>
                        @if(isset($performances[$i]->note_id))
                            <?php echo $notes[intval($performances[$i]->note_id)-1]->note;?>
                        @endif
                    </td>
                    <td></td>
                </tr>
                @elseif($weeks[$i+1-1]=='日')
                    <tr>
                    <td>{{$i+1}}日</td>
                    <td>{{$weeks[$i+1-1]}}</td>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    </tr>
                @else
                <tr onclick="location.href='/manegement/perform_new_user?status=2&user_id={{$user_id}}&select_date={{$select_date}}-'+{{$i+1}}">
                    <td>{{$i+1}}日</td>
                    <td>{{$weeks[$i+1-1]}}</td>
                    <td>欠</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                @endif
            @endfor
        </table>
        </div>
    </body>
</html>
