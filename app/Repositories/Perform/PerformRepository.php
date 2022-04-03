<?php

namespace App\Repositories\perform;
use App\Repositories\perform\PerformRepositoryInterface AS PerformRepositoryInterface;
use App\Models\Usertable;
use App\Models\Performance;

//出席情報を管理するリポジトリークラス
class PerformRepository implements PerformRepositoryInterface{
    //利用者のページネーション
    //@var int $school_id 学校のid
    //@return array 利用者のページネーション
    public function get_user_paginate($school_id)
    {
        return Usertable::where('deleted_at',null)->where('school_id',$school_id)->paginate(10);
    }

    //一日の利用者ごとの実績記録を取得
    //@param array $users 利用者
    //@param date $select_date 選択日付
    //@return array 出席情報
    public function get_users_performance($users, $select_date)
    {
        //@var array 出席情報
        $performances = [];
        foreach ($users as $user) {
            //@var Performance 選択した日付の出席情報
            $user_performance = Performance::get_user_perform($user->id, $select_date);
            
            if(empty($user_performance)){
                //@var Performance 空の出席情報
                $empty_performance = new Performance();
                $performances[] = $empty_performance;
            }else{
                $performances[] = $user_performance;
            }
        }
        return $performances;
    }

    //一日の利用者の備考を取得
    //@param array $performances　出席情報の配列
    //@return array 備考の配列
    public function get_users_note($performances){
        //@var array 備考の配列
        $notes = [];
        foreach($performances as $perform){
            if(isset($perform->note_id)){
                $notes[] = Performance::get_note($perform->note_id);
            }else{
                $notes[] = '';
            }
        }
        return $notes;
    }

    //一人の一日の実績記録を取得
    //@var int $user_id 利用者のid
    //@var date $select_date 選択した日付
    //@return Performance 利用者の出席情報
    public function get_user_performance($user_id, $select_date)
    {
        return Performance::get_user_perform($user_id, $select_date);
    }

    //利用者の学校名を取得する
    //@var int $user_id 利用者のid
    //@return string 学校名
    public function get_school_name($user_id)
    {
        //@var int 学校のid
        $school_id = Usertable::get_school_id($user_id);
        //@var string 学校名
        $school_name = Usertable::get_school_name($school_id);
        return $school_name;
    }


    //学校ごとの利用者を取得
    //@var int $user_id 利用者のid
    //@return array 利用者の配列
    public function get_school_users($user_id)
    {
        //@var int 学校のid
        $school_id = Usertable::get_school_id($user_id);
        //@var array 利用者の配列
        $users = Usertable::where('deleted_at',null)->where('school_id',$school_id)->get();
        return $users;
    }

    //学校ごとの利用者を取得する
    //@param int $school_id
    //@return array 利用者の配列
    public function get_users($school_id)
    {
        //@var array 利用者の配列
        $users = Usertable::where('deleted_at',null)->where('school_id',$school_id)->get();
        return $users;
    }

    //実績記録を変更
    //@param Request $request
    //@return Performance 変更した実績情報
    public function change_perform_user($request)
    {
        //@var Performance 利用者の出席情報
        $perform_user = Performance::get_user_perform($request->user_id, $request->select_date);
        //出席情報の変更
        $perform_user->change_perform_user($request);
        return $perform_user;
    }

    //備考の名前を取得
    //@param int note_id 備考のid
    //@return string 備考名
    public function get_note($note_id){
        return Performance::get_note($note_id);
    }

    //学校のidを取得する
    //@param int $user_id 利用者のid
    //@return int 学校のid
    public function get_school_id($user_id)
    {
        return Usertable::where('id',$user_id)->first()->school_id;
    }

    //実績記録を削除
    //@param int $user_id 利用者のid
    //@param date $select_date 削除する日付
    //@return Performance 削除した出席情報
    public function delete_perform_user($user_id, $select_date)
    {
        //@var Performance 削除する出席情報
        $perform_user = Performance::get_user_perform($user_id, $select_date);
        //@var Performance 削除する前に、表示用の情報を保存する
        $copy_perform_user = $perform_user->replicate();
            
        $perform_user->delete();

        return $copy_perform_user;
    }

    //一人の利用者の一月すべての実績記録を取得
    //@param int $user_id 利用者のid
    //@param date $select_date 選択した日付
    //@param int 選択した月
    public function get_month_performance($user_id, $select_date, $count_month_day)
    {
        //@var array 出席情報の配列
        $performances = [];
        for($i = 1; $i <= $count_month_day; $i++){
            //@var Performance 利用者の1日の出席情報
            $performance_day = Performance::where('insert_date',$select_date.'-'.sprintf('%02d',$i))->where('user_id',$user_id)->first();
            if(isset($performance_day)){
                $performances[] = $performance_day;
            }else{
                $performances[] = null;
            }
        }
        return $performances;
    }

    //実績記録の作成
    //@param Request $request
    //@return Performance 作成した出席情報
    public function create_perform_user($request)
    {
        //@var Performance 出席情報
        $perform_user = (new Performance())->create_perform_user($request);
        return $perform_user;
    }

    //利用者情報の取得
    //@param int $user_id 利用者のid
    //@return Usertable 利用者の情報
    public function get_user($user_id){
        //@var Usertable 利用者の情報
        $user = Usertable::get_user($user_id);
        return $user;
    }
}
?>