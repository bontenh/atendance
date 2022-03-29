<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\School;

class Usertable extends Model
{
    protected $guarded=['id'];

    //利用者の新規登録
    public function create_new_user(Request $request){
        $last_name=$request->last_name;
        $first_name=$request->first_name;
        $last_name_kana=$request->last_name_kana;
        $first_name_kana=$request->first_name_kana;
        $school_id=$request->school_id;
        
        $user=$this->create([
            'last_name'=>$last_name,
            'first_name'=>$first_name,
            'last_name_kana'=>$last_name_kana,
            'first_name_kana'=>$first_name_kana,
            'school_id'=>$school_id,
        ]);
        return $user;
    }

    //利用者の変更
    public function change_manege_user(Request $request){
        $this->last_name=$request->last_name;
        $this->first_name=$request->first_name;
        $this->last_name_kana=$request->last_name_kana;
        $this->first_name_kana=$request->first_name_kana;
        $this->school_id=$request->school_id;
            
        $this->save();
    }

    //利用者のソフトデリート
    public function softdelete_manege_user(){
        $this->deleted_at=date('Y/m/d h:m:s');
        $this->save();
    }

    //利用者の再登録
    public function manege_re_register_user(){
        $this->deleted_at=null;
        $this->save();
    }

    //学校ごとの利用者を取得、ページネーション
    static function get_school_user_paginate($school_id){
        return Usertable::where('deleted_at',null)->where('school_id',$school_id)->paginate(10);
    }

    //学校ごとの利用者を取得
    static function get_school_users($school_id){
        return Usertable::where('deleted_at',null)->where('school_id',$school_id)->get();
    }

    //学校全体の利用者を取得、ページネーション
    static function get_all_school_user_paginate(){
        return Usertable::where('deleted_at',null)->paginate(10);
    }

    //学校ごとのソフトデリートした利用者を取得
    static function get_softdelete_users($school_id){
        return Usertable::where('school_id',$school_id)->whereNotNull('deleted_at')->get();
    }

    //学校全体のソフトデリートした利用者を取得
    static function get_all_softdelete_users(){
        return Usertable::whereNotNull('deleted_at')->get();
    }

    //利用者のschool_idを取得
    static function get_school_id($user_id){
        return Usertable::where('id',$user_id)->first()->school_id;
    }

    //学校の名前を取得
    static function get_school_name($school_id){
        return School::where('id',$school_id)->first()->school_name;
    }

    //利用者情報を取得
    static function get_user($user_id){
        return Usertable::find($user_id);
    }
}
