<?php

namespace App\Repositories\perform;

interface PerformRepositoryInterface{
    //利用者のページネーション
    public function get_user_paginate($school_id);
    //選択した日付の配列
    public function get_users_performance($users, $select_date);
    //備考の配列
    public function get_users_note($performances);
    //選択した日付の出席情報
    public function get_user_performance($user_id, $select_date);
    //学校名を取得する
    public function get_school_name($user_id);
    //利用者が所属する学校の生徒情報
    public function get_school_users($user_id);
    //学校に所属する利用者を取得する
    public function get_users($school_id);
    //出席情報を変更する
    public function change_perform_user($request);
    //備考名を取得する
    public function get_note($note_id);
    //利用者が取得する学校のid
    public function get_school_id($user_id);
    //出席情報を削除する
    public function delete_perform_user($user_id, $select_date);
    //ひと月の出席情報を取得する
    public function get_month_performance($user_id, $select_date, $count_month_day);
    //出席情報の作成
    public function create_perform_user($request);
    //利用者情報の取得
    public function get_user($user_id);
}

?>