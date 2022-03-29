<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/attendance.css')}}">
        <title>Laravel</title>
    </head>
    <body>
            <div class="left">
                <div class="link-menu">
                    <a href="/top">Top</a>>打刻画面
                </div>
                <div class="left-main">
                    <div class="school-name">
                        {{$school_name}}
                    </div>
                    <div class="years">
                        {{$years[0]}}年{{$years[1]}}月{{$years[2]}}日（{{$week}}）
                    </div>
                    <div class="times">
                        {{$times[0]}}:{{$times[1]}}:{{$times[2]}}
                    </div>
                    <div class="name">
                        @if(isset($user))
                        {{$user->last_name}} {{$user->first_name}} さん
                        @endif
                    </div>
                    <div class="end">
                        @if($flag==='end')
                            本日はお疲れ様でした！ 
                        @endif
                    </div>
                    @if(($flag==='list') or($flag=='end'))
                    <div class="select-list">
                        <p>右リストから</p>
                        <p>利用者名を選択して下さい</p>
                    </div>
                    @elseif(($flag=='in') or ($flag=='out'))
                    <div class="in-out">
                        <form method="post">
                            <button formaction="/attendance/in_attendance" <?php if($flag=='out'){echo 'disabled';}?>>IN</button>
                            <button formaction="/attendance/out_attendance" <?php if($flag=='in'){echo 'disabled';}?>>OUT</button>
                            <input type="hidden" value="{{$user->id}}" name="user_id">
                            @csrf
                        </form>
                    @endif
                    </div>
                </div>
            </div>
            <div class="center">
                <div class="scroll">
                    <ul>
                        @for($i=0;$i < count($users);$i++)
                            <li>
                                <a href="/attendance/move_doattendance?user_id={{$users[$i]->id}}"><?php echo sprintf('%03d',$users[$i]->id);?>:{{$users[$i]->last_name}} {{$users[$i]->first_name}}
                                </a>
                                @if($attendance_today[$i]==1)
                                    <div class="attand">出席中</div>
                                @endif
                            </li>
                        @endfor
                    </ul>
                </div>
            </div>
            <div class="right">
                <ul>

                    <li><a href="/attendance?select=0&school_id={{$school_id}}">ALL</a></li>
                    
                    <li><a href="/attendance?select=1&school_id={{$school_id}}">ア</a></li>
                    <li><a href="/attendance?select=2&school_id={{$school_id}}">カ</a></li>
                    <li><a href="/attendance?select=3&school_id={{$school_id}}">サ</a></li>
                    <li><a href="/attendance?select=4&school_id={{$school_id}}">タ</a></li>
                    <li><a href="/attendance?select=5&school_id={{$school_id}}">ナ</a></li>
                    
                    <li><a href="/attendance?select=6&school_id={{$school_id}}">ハ</a></li>
                    <li><a href="/attendance?select=7&school_id={{$school_id}}">マ</a></li>
                    <li><a href="/attendance?select=8&school_id={{$school_id}}">ヤ</a></li>
                    <li><a href="/attendance?select=9&school_id={{$school_id}}">ラ</a></li>
                    <li><a href="/attendance?select=10&school_id={{$school_id}}">ワ</a></li>
                </ul>
            </div>
    </body>
</html>
