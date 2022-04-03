<?php

namespace App\Repositories\Manege;

use App\Repositories\Manege\ManegeRepositoryInterface AS ManegeRepositoryInterface;
use App\Models\Usertable;

class ManegeRepository implements ManegeRepositoryInterface{
    
    //学校ごとの利用者を取得、ページネーション
    //@var int $school_id 学校のid
    public function get_school_user_paginate($school_id)
    {
        //idが0なら、全生徒
        if($school_id == 0){
            //@var Usertable 全生徒
            $users = Usertable::get_all_school_user_paginate();
        }else{
            //@var Usertable 学校ごとの利用者
            $users = Usertable::get_school_user_paginate($school_id);
        }
        return $users;
    }

    //利用者ごとの学校名を取得
    //@param array $users
    //@param array 利用者の学校名
    public function get_users_school_name($users)
    {
        //@var array 利用者の学校名の配列
        $users_school_name = [];
        foreach($users as $user){
            $users_school_name[] = Usertable::get_school_name($user->school_id);
        }
        return $users_school_name;
    }

    //新規利用者を作成
    //@param Request $request
    //@return Usertable 新規登録した利用者
    public function create_new_user($request)
    {
        $user = (new Usertable())->create_new_user($request);
        return $user;
    }

    //学校の名前を取得
    //@param int 学校のid
    //@return string 学校名
    public function get_school_name($school_id)
    {
        //@var string 学校名
        $school_name = Usertable::get_school_name($school_id);
        return $school_name;
    }

    //利用者情報を取得
    //@param int $user_id
    //@return Usertable 利用者情報
    public function get_user($user_id){
        //@var Usertable 利用者情報
        $user = Usertable::get_user($user_id);
        return $user;
    }

    //利用者情報を変更
    //@param Request $request
    //@return Usertable 変更した利用者
    public function change_manege_user($request)
    {
        //@var Usertable 変更する利用者
        $user = Usertable::get_user($request->user_id);
        $user->change_manege_user($request);
        return $user;
    }

    //利用者のソフトデリート
    //@param int $user_id 利用者のid
    //return Usertable ソフトデリートした利用者
    public function softdelete_manege_user($user_id)
    {
        //@var Usertable ソフトデリートする利用者
        $user = Usertable::get_user($user_id);
        $user->softdelete_manege_user();
        return $user;
    }

    //ソフトデリートした利用者を取得
    //@param int $school_id
    //@return array ソフトデリートした利用者
    public function get_softdelete_users($school_id)
    {
        //0なら全生徒
        if($school_id == 0){
            //@var array ソフトデリートした利用者
            $users = Usertable::get_all_softdelete_users();
        }else{
            //@var array ソフトデリートした学校ごとの利用者
            $users = Usertable::get_softdelete_users($school_id);
        }
        return $users;
    }

    //ソフトデリートした利用者を再登録
    //@var array $user_id_list 再登録する利用者
    public function manege_re_register_user($user_id_list)
    {
        for($i = 0; $i< count($user_id_list); $i++){
            //@var Usertable 再登録する利用者
            $user = Usertable::find($user_id_list[$i]);
            $user->manege_re_register_user();
        }
    }

    //利用者を完全に削除
    //@var array $user_id_list 完全に削除する利用者
    public function manege_delete_user($user_id_list)
    {
        for($i=0;$i< count($user_id_list);$i++){
            //@var Usertable 完全削除した利用者
            $user = Usertable::destroy($user_id_list[$i]);
        }
    }
}
?>