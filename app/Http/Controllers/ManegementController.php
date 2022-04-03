<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUserRequest;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;
use App\Repositories\Manege\ManegeRepositoryInterface AS Manege;


//利用者情報を管理するコントローラークラス
class ManegementController extends Controller
{

    public function __construct(Manege $manege)
    {
        //ManegeRepositoryを設定する
        $this->manege = $manege;
        //ログインをチェックする
        $this->middleware('checklogin');
    }

    //管理者メニューの移動
    //@param Request $request
    //@return blade manege_menu.blade.php
    public function move_manege_menu(Request $request){
        return view('manege_menu');
    }

    //利用者管理画面の移動
    //@param Request $request
    //@return blade manege/mange_user.blade.php
    public function move_manege_user(Request $request){
        
        //@var int 学校のid
        $school_id = $request->school_id;
        
        //@var array 学校ごとの利用者
        $users = $this->manege->get_school_user_paginate($school_id);
        //@var array 学校名
        $users_school_name = $this->manege->get_users_school_name($users);

        return view('manege.manege_user',compact('users','users_school_name','school_id'));
    }

    //新規利用者登録の画面の移動
    //@return blade manege/manege_new_user.blade.php
    public function move_manege_new_user(){
        return view('manege.manege_new_user');
    }

    //新規利用者登録の実行
    //@param CreateUserRequst $request
    //@return blade mange/change_manege_user_complete.blade.php
    public function manege_new_user(CreateUserRequest $request){

        //@var Usertable 新規登録した利用者
        $user = $this->manege->create_new_user($request);
        //タイトル
        $title = "新規利用者登録完了";
        //@var string 学校名
        $school_name = $this->manege->get_school_name($user->school_id);

        return view('manege.change_manege_user_complete',compact('title','user','school_name'));
    }

    //利用者情報の変更の画面の移動
    //@param Request $requst
    //@return blade manege/change_manege_user
    public function move_change_manege_user(Request $request){
        //@var Usertable 変更する利用者情報
        $user = $this->manege->get_user($request->user_id);
        return view('manege.change_manege_user',compact('user'));
    }

    //利用者情報の変更
    //@param ChangeUserRequest
    //@return blade manege/change_manege_user_comlete
    public function change_manege_user(ChangeUserRequest $request){

        //@var Usertable 変更した利用者情報
        $user = $this->manege->change_manege_user($request);

        //@var string タイトル
        $title = '利用者情報変更完了';
        //@var string 学校名
        $school_name = $this->manege->get_school_name($user->school_id);
        
        return view('manege.change_manege_user_complete',compact('title','user','school_name'));
    }

    //利用者のソフトデリート実行
    //@param Request $request
    //@return blade manege/change_manege_user_complete
    public function softdelete_manege_user(Request $request){
        //@var Usertable ソフトデリートした利用者
        $user = $this->manege->softdelete_manege_user($request->user_id);

        //@var string タイトル
        $title = '利用者情報削除完了';
        //@var string 学校名
        $school_name = $this->manege->get_school_name($user->school_id);
        
        return view('manege.change_manege_user_complete',compact('title','user','school_name'));
    }
    
    //ソフトデリートユーザ管理画面の移動
    //@param Request $request
    //@return blade manege/softdelete_manege_user
    public function move_softdelete_manege_user(Request $request){
        //@var int 学校のid
        $school_id = $request->school_id;
        //@var array ソフトデリートした学校の利用者
        $users = $this->manege->get_softdelete_users($school_id);
        //@var array 学校名
        $users_school_name = $this->manege->get_users_school_name($users);

        return view('manege.softdelete_manege_user',compact('users','users_school_name','school_id'));
    }

    //利用者の再登録
    //@param Request $request
    //@return redirect
    public function manege_re_register_user(Request $request){
        if(!isset($request->user_id_list)){
            return redirect()->action('ManegementController@move_softdelete_manege_user',['school_id' => $request->school_id]);
        }

        //@var array 再登録する利用者のidの配列
        $user_id_list = $request->user_id_list;
        //利用者の再登録
        $this->manege->manege_re_register_user($user_id_list);
            
        return redirect()->action('ManegementController@move_softdelete_manege_user');
    }

    //利用者の完全に削除
    //@param Request $request
    //@return redirect
    public function manege_delete_user(Request $request){
        if(!isset($request->user_id_list)){
            return redirect()->action('ManegementController@move_softdelete_manege_user',['school_id'=>$request->school_id]);
        }

        //完全削除する利用者のidの配列
        $user_id_list = $request->user_id_list;
        //利用者の完全削除
        $this->manege->manege_delete_user($user_id_list);
            
        return redirect()->action('ManegementController@move_softdelete_manege_user');
    }
}
