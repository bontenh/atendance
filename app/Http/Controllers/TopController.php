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
    public function __construct(Admin $admin){
        $this->admin=$admin;
    }

    //出欠管理システムTOP
    public function top(){
        $status=1;
        return view('top',compact('status'));
    }

    //管理者ログイン画面の移動
    public function move_login(){
        $status=2;
        return view('admin_login',compact('status'));
    }

    //管理者ログインの実行（ログイン状態を保持する機能はまだ）
    public function do_admin_login(LoginAdminRequest $request){
        
        $admin_name=$request->admin_name;
        
        session()->put('admin_name',$admin_name);

        return view('manege_menu');
    }
    //管理者登録画面の移動
    public function move_register(){
        $status=3;
        return view('admin_register',compact('status'));
    }

    //管理者登録の実行
    public function do_admin_register(RegisterAdminRequest $request){
        
        $this->admin->admin_register($request);
        $status=2;
        return view('admin_login',compact('status'));
    }

    //ログアウト
    public function logout(){
        session()->forget('admin_name');
        $status=1;
        return view('top',compact('status'));
    }
}
