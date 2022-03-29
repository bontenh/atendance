<?php

namespace App\Repositories\perform;
use App\Repositories\perform\PerformRepositoryInterface AS PerformRepositoryInterface;
use App\Models\Usertable;
use App\Models\Performance;

class PerformRepository implements PerformRepositoryInterface{
    //利用者のページネーション
    public function get_user_paginate($school_id)
    {
        return Usertable::where('deleted_at',null)->where('school_id',$school_id)->paginate(10);
    }

    //一日の利用者ごとの実績記録を取得
    public function get_users_performance($users,$select_date)
    {
        $performances=[];
        foreach ($users as $user) {
            $user_performance=Performance::get_user_perform($user->id,$select_date);
            
            if(empty($user_performance)){
                $empty_performance=new Performance();
                $performances[]=$empty_performance;
            }else{
                $performances[]=$user_performance;
            }
        }
        return $performances;
    }

    //一日の利用者の備考を取得
    public function get_users_note($performances){
        $notes=[];
        foreach($performances as $perform){
            if(isset($perform->note_id)){
                $notes[]=Performance::get_note($perform->note_id);
            }else{
                $notes[]='';
            }
        }
        return $notes;
    }

    //一人の一日の実績記録を取得
    public function get_user_performance($user_id, $select_date)
    {
        return Performance::get_user_perform($user_id,$select_date);
    }

    public function get_school_name($user_id)
    {
        $school_id=Usertable::get_school_id($user_id);
        $school_name=Usertable::get_school_name($school_id);
        return $school_name;
    }


    //学校ごとの利用者を取得
    public function get_school_users($user_id)
    {
        $school_id=Usertable::get_school_id($user_id);
        $users=Usertable::where('deleted_at',null)->where('school_id',$school_id)->get();
        return $users;
    }

    public function get_users($school_id)
    {
        $users=Usertable::where('deleted_at',null)->where('school_id',$school_id)->get();
        return $users;
    }

    //実績記録を変更
    public function change_perform_user($request)
    {
        $perform_user=Performance::get_user_perform($request->user_id,$request->select_date);
        $perform_user->change_perform_user($request);
        return $perform_user;
    }

    //備考の名前を取得
    public function get_note($note_id){
        return Performance::get_note($note_id);
    }

    public function get_school_id($user_id)
    {
        return Usertable::where('id',$user_id)->first()->school_id;
    }

    //実績記録を削除
    public function delete_perform_user($user_id,$select_date)
    {
        $perform_user=Performance::get_user_perform($user_id,$select_date);
        $copy_perform_user=$perform_user->replicate();
            
        $perform_user->delete();

        return $copy_perform_user;
    }

    //一人の利用者の一月すべての実績記録を取得
    public function get_month_performance($user_id, $select_date, $count_month_day)
    {
        $performances=[];
        for($i=1;$i<=$count_month_day;$i++){
            $performance_day=Performance::where('insert_date',$select_date.'-'.sprintf('%02d',$i))->where('user_id',$user_id)->first();
            if(isset($performance_day)){
                $performances[]=$performance_day;
            }else{
                $performances[]=null;
            }
        }
        return $performances;
    }

    //実績記録の作成
    public function create_perform_user($request)
    {
        $perform_user=(new Performance())->create_perform_user($request);
        return $perform_user;
    }

    //利用者情報の取得
    public function get_user($user_id){
        $user=Usertable::get_user($user_id);
        return $user;
    }
}
?>