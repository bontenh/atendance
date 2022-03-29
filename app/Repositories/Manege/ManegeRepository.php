<?php

namespace App\Repositories\Manege;

use App\Repositories\Manege\ManegeRepositoryInterface AS ManegeRepositoryInterface;
use App\Models\Usertable;

class ManegeRepository implements ManegeRepositoryInterface{
    
    //学校ごとの利用者を取得、ページネーション
    public function get_school_user_paginate($school_id)
    {
        if($school_id==0){
            $users=Usertable::get_all_school_user_paginate();
        }else{
            $users=Usertable::get_school_user_paginate($school_id);
        }
        return $users;
    }

    //利用者ごとの学校名を取得
    public function get_users_school_name($users)
    {
        $users_school_name=[];
        foreach($users as $user){
            $users_school_name[]=Usertable::get_school_name($user->school_id);
        }
        return $users_school_name;
    }

    //新規利用者を作成
    public function create_new_user($request)
    {
        $user=(new Usertable())->create_new_user($request);
        return $user;
    }

    //学校の名前を取得
    public function get_school_name($school_id)
    {
        $school_name=Usertable::get_school_name($school_id);
        return $school_name;
    }

    //利用者情報を取得
    public function get_user($user_id){
        $user=Usertable::get_user($user_id);
        return $user;
    }

    //利用者情報を変更
    public function change_manege_user($request)
    {
        $user=Usertable::get_user($request->user_id);
        $user->change_manege_user($request);
        return $user;
    }

    //利用者のソフトデリート
    public function softdelete_manege_user($user_id)
    {
        $user=Usertable::get_user($user_id);
        $user->softdelete_manege_user();
        return $user;
    }

    //ソフトデリートした利用者を取得
    public function get_softdelete_users($school_id)
    {
        if($school_id==0){
            $users=Usertable::get_all_softdelete_users();
        }else{
            $users=Usertable::get_softdelete_users($school_id);
        }
        return $users;
    }

    //ソフトデリートした利用者を再登録
    public function manege_re_register_user($user_id_list)
    {
        for($i=0;$i< count($user_id_list);$i++){
            $user=Usertable::find($user_id_list[$i]);
            $user->manege_re_register_user();
        }
    }

    //利用者を完全に削除
    public function manege_delete_user($user_id_list)
    {
        for($i=0;$i< count($user_id_list);$i++){
            $user=Usertable::destroy($user_id_list[$i]);
        }
    }
}
?>