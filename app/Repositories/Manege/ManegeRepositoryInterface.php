<?php

namespace App\Repositories\Manege;

interface ManegeRepositoryInterface{
    //学校ごとの利用者のページネーション
    public function get_school_user_paginate($school_id);
    //学校名の取得
    public function get_users_school_name($users);
    //利用者の新規登録
    public function create_new_user($request);
    //学校名の取得
    public function get_school_name($school_id);
    //利用者の取得
    public function get_user($user_id);
    //利用者情報の変更
    public function change_manege_user($request);
    //利用者のソフトデリート
    public function softdelete_manege_user($user_id);
    //ソフトデリートした利用者の取得
    public function get_softdelete_users($school_id);
    //ソフトデリートした利用者の再登録
    public function manege_re_register_user($user_id_list);
    //利用者の完全削除
    public function manege_delete_user($user_id_list);
}

?>