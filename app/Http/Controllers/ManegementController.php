<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUserRequest;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;
use App\Repositories\Manege\ManegeRepositoryInterface AS Manege;
use Illuminate\Support\Facades\DB;
use App\Models\Usertable;

class ManegementController extends Controller
{

    public function __construct(Manege $manege)
    {
        $this->manege=$manege;
        $this->middleware('checklogin');
    }

    //管理者メニューの移動
    public function move_manege_menu(Request $request){
        return view('manege_menu');
    }

    //利用者管理画面の移動
    public function move_manege_user(Request $request){
        
        $school_id=$request->school_id;
        
        $users=$this->manege->get_school_user_paginate($school_id);
        $users_school_name=$this->manege->get_users_school_name($users);

        return view('manege.manege_user',compact('users','users_school_name','school_id'));
    }

    //新規利用者登録の画面の移動
    public function move_manege_new_user(){
        return view('manege.manege_new_user');
    }

    //新規利用者登録の実行
    public function manege_new_user(CreateUserRequest $request){

        $user=$this->manege->create_new_user($request);
        
        $title="新規利用者登録完了";
        $school_name=$this->manege->get_school_name($user->school_id);

        return view('manege.change_manege_user_complete',compact('title','user','school_name'));
    }

    //利用者情報の変更の画面の移動
    public function move_change_manege_user(Request $request){
        $user=$this->manege->get_user($request->user_id);
        return view('manege.change_manege_user',compact('user'));
    }

    //利用者情報の変更
    public function change_manege_user(ChangeUserRequest $request){

        $user=$this->manege->change_manege_user($request);

        $title='利用者情報変更完了';
        $school_name=$this->manege->get_school_name($user->school_id);
        
        return view('manege.change_manege_user_complete',compact('title','user','school_name'));
    }

    //利用者のソフトデリート実行
    public function softdelete_manege_user(Request $request){
        $user=$this->manege->softdelete_manege_user($request->user_id);

        $title='利用者情報削除完了';
        $school_name=$this->manege->get_school_name($user->school_id);
        return view('manege.change_manege_user_complete',compact('title','user','school_name'));
    }
    
    //ソフトデリートユーザ管理画面の移動
    public function move_softdelete_manege_user(Request $request){
        
        $school_id=$request->school_id;
        
        $users=$this->manege->get_softdelete_users($school_id);
        $users_school_name=$this->manege->get_users_school_name($users);

        return view('manege.softdelete_manege_user',compact('users','users_school_name','school_id'));
    }

    //利用者の再登録
    public function manege_re_register_user(Request $request){
        if(!isset($request->user_id_list)){
            return redirect()->action('ManegementController@move_softdelete_manege_user',['school_id'=>$request->school_id]);
        }

        $user_id_list=$request->user_id_list;
        $this->manege->manege_re_register_user($user_id_list);
            
        return redirect()->action('ManegementController@move_softdelete_manege_user');
    }

    //利用者の完全に削除
    public function manege_delete_user(Request $request){
        if(!isset($request->user_id_list)){
            return redirect()->action('ManegementController@move_softdelete_manege_user',['school_id'=>$request->school_id]);
        }

        $user_id_list=$request->user_id_list;
        $this->manege->manege_delete_user($user_id_list);
            
        return redirect()->action('ManegementController@move_softdelete_manege_user');
    }
}
