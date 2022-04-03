<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

//管理者情報モデル
class Admin extends Model
{
    protected $guarded=['id'];

    //管理者を登録する
    //@param Request $request 管理者名とパスワード
    public function admin_register(Request $request){
        //管理者名を代入する
        $this->name = $request->admin_name;
        //パスワードをハッシュ化して、代入する
        $this->password = password_hash($request->admin_password,PASSWORD_DEFAULT);
        //管理者情報をデータベースに保存する
        $this->save();
    }
}
