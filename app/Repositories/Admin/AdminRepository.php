<?php

namespace App\Repositories\Admin;

use App\Repositories\Admin\AdminRepositoryInterface AS AdminRepositoryInterface;
use App\Models\Admin;

//管理者のデータベースを扱う
class AdminRepository implements AdminRepositoryInterface{
    
    //管理者の登録
    //@param Request $request 入力された管理者名とパスワード
    public function admin_register($request)
    {
        //@var Admin 管理者の登録
        $admin=(new Admin())->admin_register($request);
    }
}
?>