<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Note;

class Performance extends Model
{

    protected $guarded=['id'];

    //一人の利用者の一つの実績記録を取得。時間指定
    static function get_user_perform($user_id,$select_date){
        return Performance::where('user_id',$user_id)->where('insert_date',$select_date)->first();
    }

    //実績記録の変更
    public function change_perform_user(Request $request){
        //dd('stop',$request);
        $this->start=$request->start;
        $this->end=$request->end;
        if(isset($request->food_fg)){
            $this->food_fg=1;
        }else{
            $this->food_fg=0;
        }
        if(isset($request->outside_fg)){
            $this->outside_fg=1;
        }else{
            $this->outside_fg=0;
        }
        if(isset($request->medical_fg)){
            $this->medical_fg=1;
        }else{
            $this->medical_fg=0;
        }
        $this->note_id=$request->note_id;
        
        $this->save();
    }

    //実績記録作成
    public function create_perform_user(Request $request){
        
        if(isset($request->food_fg)){
            $food_fg=1;
        }else{
            $food_fg=0;
        }
        if(isset($request->outside_fg)){
            $outside_fg=1;
        }else{
            $outside_fg=0;
        }
        if(isset($request->medical_fg)){
            $medical_fg=1;
        }else{
            $medical_fg=0;
        }
        $note_id=$request->note_id;

        $perform_user=$this->create([
            'insert_date'=>$request->insert_date,
            'user_id'=>$request->user_id,
            'start'=>$request->start,
            'end'=>$request->end,
            'food_fg'=>$food_fg,
            'outside_fg'=>$outside_fg,
            'medical_fg'=>$medical_fg,
            'note_id'=>$note_id,
            'created_at'=>now(),
        ]);
        return $perform_user;
    }

    //実績記録作成、入室
    public function create_in_attendance($request,$attendance_time){
        $perform_user=$this->create([
            'insert_date'=>date('Y-m-d'),
            'user_id'=>$request->user_id,
            'start'=>$attendance_time,
            'note_id'=>1,
            'created_at'=>now(),
        ]);
        return $perform_user;
    }

    //実績記録の変更、退室
    public function out_attendance($attendance_time){
        $this->end=$attendance_time;
        $this->save();
    }

    //実績記録変更画面の表示の為のデータの整形
    public function output_perform_user(){
        if(!isset($this->end)){
            $this->end=date('16:00');
        }
    }

    //note_idから備考の内容を返す。通所、Skypeなど
    static function get_note($note_id){
        $note=Note::where('id',$note_id)->first()->note;
        return $note;
    }
}
