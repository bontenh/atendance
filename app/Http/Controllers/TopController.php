<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAdminRequest;
use App\Http\Requests\RegisterAdminRequest;
use Illuminate\Http\Request;
use App\Repositories\Admin\AdminRepositoryInterface AS Admin;

//TopControllerは　管理者登録　管理者ログインの認証　管理者画面への移動

//$status=1 top画面 , $status=2 管理者ログイン画面 , $status=3 管理者登録画面 

class TopController extends Controller
{
    //レポジトリークラスをコントローラークラスで使えるようにする
    //@param AdminRepository $admin 管理者のデータベースを扱うクラス
    public function __construct(Admin $admin){
        $this->admin = $admin;
    }

    //出欠管理システムTOP
    //@return blade top.blade.php
    public function top(){
        //@var int 管理画面を管理するステータス文字
        $status = 1;
        return view('top',compact('status'));
    }

    //管理者ログイン画面の移動
    //@return blade admin_register.blade.php
    public function move_login(){
        //@var int 管理画面を管理するステータス文字
        $status = 2;
        return view('admin_login',compact('status'));
    }

    //管理者ログインの実行
    //@param LoginAdminRequest $request ログイン機能を実行するクラス
    //@return blade manege_menu.blade.php
    public function do_admin_login(LoginAdminRequest $request){
        //@var string 管理者名を取り出す
        $admin_name = $request->admin_name;
        //セッションに、管理者名を保存する
        session()->put('admin_name',$admin_name);

        return view('manege_menu');
    }
    //管理者登録画面の移動
    //@return blade admin_register.blade.php
    public function move_register(){
        //@var int 管理画面を管理するステータス文字
        $status = 3;
        return view('admin_register',compact('status'));
    }

    //管理者登録の実行
    //@param RegisterAdminRequest $request 管理者を登録するための情報をバリデーション
    //@return blade admin_blade.blade.php
    public function do_admin_register(RegisterAdminRequest $request){
        
        //管理者を登録する
        $this->admin->admin_register($request);
        //@var int 管理画面を管理するステータス文字
        $status = 2;
        return view('admin_login',compact('status'));
    }

    //ログアウト
    //@return blade top.blade.php
    public function logout(){
        //管理者の名前を消し、ログアウトを実行する
        session()->forget('admin_name');
        //@var int 管理画面を管理するステータス文字
        $status = 1;
        return view('top',compact('status'));
    }
}
