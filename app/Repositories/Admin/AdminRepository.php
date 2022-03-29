<?php

namespace App\Repositories\Admin;

use App\Repositories\Admin\AdminRepositoryInterface AS AdminRepositoryInterface;
use App\Models\Admin;

class AdminRepository implements AdminRepositoryInterface{
    
    //管理者の登録
    public function admin_register($request)
    {
        $admin=(new Admin())->admin_register($request);
    }
}
?>