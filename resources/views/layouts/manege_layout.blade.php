<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{asset('css/manege_user.css')}}">
        <link rel="stylesheet" href="{{asset('css/manege.css')}}">
        <title>Laravel</title>
    </head>
    <body>
        <div class="top-header">
            
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
                    <li><a href="logout">ログアウト</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
            @yield('manege')
        </div>
    </body>
</html>
