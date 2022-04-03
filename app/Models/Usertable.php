<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\School;

//利用者情報モデル
class Usertable extends Model
{
    protected $guarded=['id'];

    //利用者の新規登録
    //@param Request $request
    //@return Usertable 作成した利用者情報
    public function create_new_user(Request $request){
        //@var string 性
        $last_name = $request->last_name;
        //@var string 名
        $first_name = $request->first_name;
        //@var string 性カナ
        $last_name_kana = $request->last_name_kana;
        //@var string 名カナ
        $first_name_kana = $request->first_name_kana;
        //@var int 学校のid
        $school_id = $request->school_id;
        
        $user = $this->create([
            'last_name'=>$last_name,
            'first_name'=>$first_name,
            'last_name_kana'=>$last_name_kana,
            'first_name_kana'=>$first_name_kana,
            'school_id'=>$school_id,
        ]);
        return $user;
    }

    //利用者の変更
    //$param Request $request
    public function change_manege_user(Request $request){
        //氏名、氏名カナ、学校のidを変更する
        $this->last_name = $request->last_name;
        $this->first_name = $request->first_name;
        $this->last_name_kana = $request->last_name_kana;
        $this->first_name_kana = $request->first_name_kana;
        $this->school_id = $request->school_id;
            
        $this->save();
    }

    //利用者のソフトデリート
    public function softdelete_manege_user(){
        $this->deleted_at = date('Y/m/d h:m:s');
        $this->save();
    }

    //ソフトデリートした利用者の再登録
    public function manege_re_register_user(){
        //ソフトデリートした利用者の情報をnullに設定する
        $this->deleted_at = null;
        $this->save();
    }

    //学校ごとの利用者を取得、ページネーション
    //@param int $school 学校のid
    //@return Usertable 学校ごとの利用者のページネーション
    static function get_school_user_paginate($school_id){
        return Usertable::where('deleted_at',null)->where('school_id',$school_id)->paginate(10);
    }

    //学校ごとの利用者を取得
    //@var int $school_id 学校のid
    //@var array 学校ごとの全生徒
    static function get_school_users($school_id){
        return Usertable::where('deleted_at', null)->where('school_id', $school_id)->get();
    }

    //学校全体の利用者を取得、ページネーション
    //@return 学校 学校の利用者のページネーション
    static function get_all_school_user_paginate(){
        return Usertable::where('deleted_at', null)->paginate(10);
    }

    //学校ごとのソフトデリートした利用者を取得
    //@param int $school_id 学校のid
    //@return array 学校ごとのソフトデリートしたid
    static function get_softdelete_users($school_id){
        return Usertable::where('school_id', $school_id)->whereNotNull('deleted_at')->get();
    }

    //学校全体のソフトデリートした利用者を取得
    //@return array ソフトデリートした利用者の情報
    static function get_all_softdelete_users(){
        return Usertable::whereNotNull('deleted_at')->get();
    }

    //利用者のschool_idを取得
    //@param int $user_id 利用者のid
    //@return int 利用者の所属する学校のid
    static function get_school_id($user_id){
        return Usertable::where('id', $user_id)->first()->school_id;
    }

    //学校の名前を取得
    //@param int $school_id 学校のid
    //@return string 学校名
    static function get_school_name($school_id){
        return School::where('id', $school_id)->first()->school_name;
    }

    //利用者情報を取得
    //@param int $user_id 利用者のid
    //@return Usertable 利用者の情報
    static function get_user($user_id){
        return Usertable::find($user_id);
    }
}
