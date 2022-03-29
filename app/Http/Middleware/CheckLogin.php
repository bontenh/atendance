<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;

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
        $admin_name=session()->get('admin_name');
        $admin=Admin::where('name',$admin_name)->first();
        if(!isset($admin_name) or !isset($admin)){
            return redirect()->action('TopController@move_login');
        }
        return $next($request);
    }
}
