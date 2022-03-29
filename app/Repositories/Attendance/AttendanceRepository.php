<?php

namespace App\Repositories\Attendance;

use App\Repositories\Attendance\AttendanceRepositoryInterface AS AttendanceRepositoryInterface;
use App\Models\Usertables;
use App\Models\Performance;
use App\Models\Usertable;

class AttendanceRepository implements AttendanceRepositoryInterface{
    
    //学校ごとの利用者を取得
    public function get_school_users($school_id){
        $users=Usertable::get_school_users($school_id);
        return $users;
    }

    //ア行からワ行の学校ごとの利用者を取得
    public function get_select_names($school_id, $select)
    {
        $kana_table=[['ア','イ','ウ','エ','オ']
                    ,['カ','キ','ク','ケ','コ','ガ','ギ','グ','ゲ','ゴ']
                    ,['サ','シ','ス','セ','ソ','ザ','ジ','ズ','ゼ','ゾ']
                    ,['タ','チ','ツ','テ','ト','ダ','ヂ','ヅ','デ','ド']
                    ,['ナ','ニ','ヌ','ネ','ノ']
                    ,['ハ','ヒ','フ','ヘ','ホ','バ','ビ','ブ','ベ','ボ','パ','ピ','プ','ぺ','ポ']
                    ,['マ','ミ','ム','メ','モ']
                    ,['ヤ','ユ','ヨ']
                    ,['ラ','リ','ル','レ','ロ']
                    ,['ワ','ヲ','ン']];
        
        $users=Usertable::get_school_users($school_id);
        
        $select=$select-1;
        $select_kana_table=$kana_table[$select];
        
        $select_user=[];
        foreach($users as $user){
            foreach($select_kana_table as $kana){
                if(mb_substr($user->last_name_kana,0,1)==$kana){
                    $select_user[]=$user;
                }
            }
        }
        return $select_user;
    }

    //今日出席しているか、配列として取得
    public function get_attendance_today($users)
    {
        $is_attendance_array=[];
        foreach($users as $user){
            $user_id=$user->id;
            $years=date('Y-m-d');
            $perform_user=Performance::get_user_perform($user_id,$years);
            if(isset($perform_user)){
                if(isset($perform_user->end)){
                    $is_attendance_array[]=0;
                }else{
                    $is_attendance_array[]=1;
                }
            }else{
                $is_attendance_array[]=0;
            }
        }
        return $is_attendance_array;
    }

    //学校名を取得
    public function get_school_name($school_id)
    {
        $school_name=Usertable::get_school_name($school_id);
        return $school_name;
    }

    //利用者を取得
    public function get_user($user_id)
    {
        $user=Usertable::get_user($user_id);
        return $user;
    }

    //当日出席者を取得
    public function get_perform_user($user_id, $select_date)
    {
        $perform_user=Performance::get_user_perform($user_id,$select_date);
        return $perform_user;
    }

    //当日の利用者の入室
    public function in_attendance($request,$attendance_time)
    {
        $perform_user=(new Performance())->create_in_attendance($request,$attendance_time);
    }

    //当日の利用者の退室
    public function out_attendance($request, $attendance_time)
    {
        $perform_user=Performance::get_user_perform($request->user_id,date('Y-m-d'));
        if(strtotime($perform_user->start)<=strtotime($attendance_time)){
            $perform_user->out_attendance($attendance_time);
        }
    }
}
?>