<?php

namespace App\Repositories\Attendance;

//出席退席時間を扱うリポジトリークラス
interface AttendanceRepositoryInterface{
    //学校ごとの利用者を取得する
    public function get_school_users($school_id);
    //カナ文字で範囲していした利用者を取得する
    public function get_select_names($school_id, $select);
    //今日出席している生徒を取得する
    public function get_attendance_today($users);
    //学校の名前を取得する
    public function get_school_name($school_id);
    //利用者情報の取得
    public function get_user($user_id);
    //利用者の出席情報を取得する
    public function get_perform_user($user_id, $select_date);
    //出席時間を記録する
    public function in_attendance($request, $attendance_time);
    //退席時間を記録する
    public function out_attendance($request, $attendance_time);
}

?>