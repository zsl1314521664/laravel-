<?php

namespace App\Http\Controllers\Admin;

//use Dotenv\Validator;
//use App\User;
use App\Model\user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Org\code\Code;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Validator;
class LoginController extends Controller
{
    //后台登录页
    public function login()
    {
        return view('admin.login');
    }
//    验证码
    public function code()
    {
        $code=new Code();
//        $codes=$code->get();
//        \Session::flash('code', $codes);
        return $code->make();
    }
//    处理用户登录到方法
    public function doLogin(Request $request)
    {
        $input = $request->except('_token');
        $rule=[
            'username'=>'required|between:4,18',
            'password'=>'required|between:4,18|alpha_dash'
        ];
        $msg=[
            'username.required'=>'用户名必须输入',
            'username.between'=>'用户名长度必须在4-18位之间',
            'password.required'=>'密码必须输入',
            'password.between'=>'密码长度必须在4-18位之间',
            'password.alpha_dash'=>'密码必须是数组字母下滑线',
        ];
        $validator = Validator::make($input,$rule,$msg);

        if ($validator->fails()) {
            return redirect('admin/login')
                ->withErrors($validator)
                ->withInput();
        }

//        if(strtolower($input['code']) != strtolower(session()->get('code')) ){
//            return redirect('admin/login')->with('errors','验证码错误');
//        }
       $user= User::where('user_name',$input['username'])->first();
        if(!$user){
            return redirect('admin/login')->with('errors','用户不存在');
        }

        if($input['password'] != Crypt::decrypt($user->user_pass)){
            return redirect('admin/login')->with('errors','密码错误');
        }

        session()->put('user',$user);

//        5. 跳转到后台首页
        return redirect('admin/index');
    }

    public function getcode()
    {
        $code=new Code;
        return $code->get();
    }
    public function index()
    {
        return view('admin.index');
    }
    public function welcome()
    {
        return view('admin.welcome');
    }
//    退出登录
    public function logout()
    {
        session()->flush();
        return redirect('admin/login');
    }

    public function changetheme()
    {

    }
//    加密
    public function jiami()
    {
        $str='123456';
        $jiami=Crypt::encrypt($str);
        return $jiami;
    }
//    没有权限回调
    public function noaccess()
    {
        return view('errors.noaccess');
    }
}
