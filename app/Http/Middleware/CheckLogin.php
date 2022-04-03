<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;

//ログインをチェックする
class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //@var string 管理者名
        $admin_name = session()->get('admin_name');
        //@var Admin 管理者情報
        $admin = Admin::where('name',$admin_name)->first();
        //管理者名がないか、管理者名がないなら、ログイン画面に移動する
        if(!isset($admin_name) or !isset($admin)){
            return redirect()->action('TopController@move_login');
        }
        return $next($request);
    }
}
