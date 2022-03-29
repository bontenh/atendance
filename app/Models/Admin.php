<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Admin extends Model
{
    protected $guarded=['id'];

    public function admin_register(Request $request){
        $this->name=$request->admin_name;
        $this->password=password_hash($request->admin_password,PASSWORD_DEFAULT);
        $this->save();
    }
}
