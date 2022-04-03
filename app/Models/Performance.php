<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Note;

//利用者の出席情報モデル
class Performance extends Model
{

    protected $guarded=['id'];

    //一人の利用者の一つの実績記録を取得。時間指定
    //@param int $user_id 利用者のid
    //@param date $select_date 時間
    //@return performance 利用者の出席退席情報
    static function get_user_perform($user_id,$select_date){
        return Performance::where('user_id',$user_id)->where('insert_date',$select_date)->first();
    }

    //実績記録の変更
    //@param Request $request
    public function change_perform_user(Request $request){
        //出席時間を設定する
        $this->start = $request->start;
        //退席時間を設定する
        $this->end = $request->end;
        //食事加算フラグ
        if(isset($request->food_fg)){
            $this->food_fg = 1;
        }else{
            $this->food_fg = 0;
        }
        //外部加算フラグ
        if(isset($request->outside_fg)){
            $this->outside_fg = 1;
        }else{
            $this->outside_fg = 0;
        }
        //医療加算フラグ
        if(isset($request->medical_fg)){
            $this->medical_fg = 1;
        }else{
            $this->medical_fg = 0;
        }
        //備考を設定する
        $this->note_id = $request->note_id;
        
        $this->save();
    }

    //実績記録作成
    //@param Request $request
    //@return Performance 記録した利用者情報
    public function create_perform_user(Request $request){
        //食事加算フラグ
        if(isset($request->food_fg)){
            //@var int 食事加算フラグ
            $food_fg = 1;
        }else{
            $food_fg = 0;
        }
        //外部加算フラグ
        if(isset($request->outside_fg)){
            //@var int 外部加算フラグ
            $outside_fg = 1;
        }else{
            $outside_fg = 0;
        }
        //医療加算フラグ
        if(isset($request->medical_fg)){
            //@var int 医療加算フラグ
            $medical_fg = 1;
        }else{
            $medical_fg = 0;
        }
        //@var int 備考のid
        $note_id = $request->note_id;

        //@var Performance 利用者の出席情報
        $perform_user = $this->create([
            'insert_date' => $request->insert_date,
            'user_id' => $request->user_id,
            'start' => $request->start,
            'end' => $request->end,
            'food_fg' => $food_fg,
            'outside_fg' => $outside_fg,
            'medical_fg' => $medical_fg,
            'note_id' => $note_id,
            'created_at' => now(),
        ]);
        return $perform_user;
    }

    //実績記録作成、入室
    //@param Request $request
    //@param date $attendance_time 出席時間
    //@return Performance 利用者の出席情報
    public function create_in_attendance($request, $attendance_time){
        //@var Performance 利用者の出席情報
        $perform_user = $this->create([
            'insert_date' => date('Y-m-d'),
            'user_id' => $request->user_id,
            'start' => $attendance_time,
            'note_id' => 1,
            'created_at' => now(),
        ]);
        return $perform_user;
    }

    //実績記録の変更、退室
    //@param date $attendance_time 退席時間
    public function out_attendance($attendance_time){
        //退席時間を設定する
        $this->end = $attendance_time;
        $this->save();
    }

    //実績記録変更画面の表示の為のデータの整形
    public function output_perform_user(){
        if(!isset($this->end)){
            $this->end=date('16:00');
        }
    }

    //note_idから備考の内容を返す。通所、Skypeなど
    //@param int $note_id 備考のid
    //@return string 備考名
    static function get_note($note_id){
        //@var string 備考名
        $note = Note::where('id', $note_id)->first()->note;
        return $note;
    }
}
