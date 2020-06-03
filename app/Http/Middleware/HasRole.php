<?php

namespace App\Http\Middleware;

use Closure;
//use http\Client\Curl\User;
use App\Model\user;
use App\Model\Role;
use App\Model\Permission;


class HasRole
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
        $route=\Route::current()->getActionName();
//        dd($request);
//        dd($route);
//        获取当前用户权限组
        $user=user::find(session()->get('user')->user_id);
        $roles=$user->role;
//        dd($roles);
        $arr=[];
        foreach ($roles as $v){
            $perms=$v->permission;
            foreach ($perms as $perm){
                $arr[]=$perm->per_url;
            }
        }
        $arr = array_unique($arr);
//        dd($arr);
        if(in_array($route,$arr)){
            return $next($request);
        }else{
            return redirect('noaccess');
        }
        return $next($request);
    }
}
