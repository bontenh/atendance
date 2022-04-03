<?php

namespace App\Repositories\Attendance;

use App\Repositories\Attendance\AttendanceRepositoryInterface AS AttendanceRepositoryInterface;
use App\Models\Performance;
use App\Models\Usertable;

//出席退席時間を扱うリポジトリークラス
class AttendanceRepository implements AttendanceRepositoryInterface{
    
    //学校ごとの利用者を取得
    //@param int $school_id 学校のid
    //@return array 学校ごとの全生徒
    public function get_school_users($school_id){
        //@var array 学校ごとの全生徒
        $users = Usertable::get_school_users($school_id);
        return $users;
    }

    //ア行からワ行の学校ごとの利用者を取得
    //@param int $school_id 学校のid
    //@pram int ア行からワ行の情報
    //@return array 範囲指定した学校ごとの利用者
    public function get_select_names($school_id, $select)
    {
        //@var array カナ文字のテーブル
        $kana_table = [['ア','イ','ウ','エ','オ']
                    ,['カ','キ','ク','ケ','コ','ガ','ギ','グ','ゲ','ゴ']
                    ,['サ','シ','ス','セ','ソ','ザ','ジ','ズ','ゼ','ゾ']
                    ,['タ','チ','ツ','テ','ト','ダ','ヂ','ヅ','デ','ド']
                    ,['ナ','ニ','ヌ','ネ','ノ']
                    ,['ハ','ヒ','フ','ヘ','ホ','バ','ビ','ブ','ベ','ボ','パ','ピ','プ','ぺ','ポ']
                    ,['マ','ミ','ム','メ','モ']
                    ,['ヤ','ユ','ヨ']
                    ,['ラ','リ','ル','レ','ロ']
                    ,['ワ','ヲ','ン']];
        //@var array 学校ごとの全利用者
        $users=Usertable::get_school_users($school_id);
        //配列のインデックスと合わせるために、マイナス
        $select = $select-1;
        //@var array ア行からワ行のカナ文字の配列
        $select_kana_table=$kana_table[$select];
        
        //@var array 範囲指定した生徒を代入する
        $select_user=[];
        foreach($users as $user){
            foreach($select_kana_table as $kana){
                //頭文字が合えば、代入する
                if(mb_substr($user->last_name_kana,0,1)==$kana){
                    $select_user[]=$user;
                }
            }
        }
        return $select_user;
    }

    //今日出席しているか、配列として取得
    //@param array $users 学校ごとの利用者
    //@return array 利用者の出席情報
    public function get_attendance_today($users)
    {
        //@var array 出席情報、0は退席しているか、出席しているか。1は出席中。
        $is_attendance_array = [];
        foreach($users as $user){
            //@var int 利用者のidを取得する
            $user_id = $user->id;
            //@var date 今日の時刻
            $years = date('Y-m-d');
            //@var Performance 今日の利用者の出席情報を取得する
            $perform_user = Performance::get_user_perform($user_id,$years);
            //出席情報があるか
            if(isset($perform_user)){
                //退席しているか、判断
                if(isset($perform_user->end)){
                    $is_attendance_array[] = 0;
                }else{
                    $is_attendance_array[] = 1;
                }
            }else{
                $is_attendance_array[] = 0;
            }
        }
        return $is_attendance_array;
    }

    //学校名を取得
    //@param int $school_id 学校のid
    //@return string 学校名
    public function get_school_name($school_id)
    {
        //@var string 学校名
        $school_name=Usertable::get_school_name($school_id);
        return $school_name;
    }

    //利用者を取得
    //@param int $user_id ユーザーのid
    //@return Usertable ユーザー情報
    public function get_user($user_id)
    {
        //@var Usertable ユーザー情報
        $user = Usertable::get_user($user_id);
        return $user;
    }

    //当日出席者を取得
    //@param int 利用者のid
    //@param date 時刻
    //@return Performance 利用者の出席情報
    public function get_perform_user($user_id, $select_date)
    {
        //@var Perfromance 利用者の出席情報
        $perform_user = Performance::get_user_perform($user_id, $select_date);
        return $perform_user;
    }

    //当日の利用者の入室
    //@param Request $request リクエストクラス
    //@param date 出席時間
    public function in_attendance($request, $attendance_time)
    {
        //出席時間を記録する
        $perform_user = (new Performance())->create_in_attendance($request, $attendance_time);
    }

    //当日の利用者の退室
    //@param Request $request リクエストクラス
    //@param date 退席時間
    public function out_attendance($request, $attendance_time)
    {
        //@var Performance 今日の利用者の出席時間を取得する
        $perform_user = Performance::get_user_perform($request->user_id, date('Y-m-d'));
        //出席時間より退席時間が早いなら、記録しない
        if(strtotime($perform_user->start) <= strtotime($attendance_time)){
            $perform_user->out_attendance($attendance_time);
        }
    }
}
?>