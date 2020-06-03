<?php

namespace App\Http\Controllers\Home;

use App\Model\HomeUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Mail;
class RegisterController extends Controller
{
    public function register()
    {
        return view('home.emailregister');
    }
//邮箱处理
    public function doRegister(Request $request)
    {
        $input = $request->except('_token');
//        dd($input);
        $input['user_pass'] = Crypt::encrypt($input['user_pass']);
        $input['email'] = $input['user_name'];
        $input['token'] = md5($input['email'].$input['user_pass'].'123');
        $input['expire'] = time()+600;

        $user = HomeUser::create($input);

        if($user){

            Mail::send('email.active',['user'=>$user],function ($m) use ($user) {
//              $m->from('hello@app.com', 'Your Application');

                $m->to($user->email, $user->name)->subject('激活邮箱');
            });


            return redirect('login')->with('active','请先登录邮箱激活账号');
        }else{
            return redirect('emailregister')->with('注册失败!请重新注册');
        }
    }
    //注册账号邮箱激活
    public function active(Request $request){
        //找到要激活的用户，将用户的active字段改成1

        $user = HomeUser::findOrFail($request->userid);

        //验证token的有效性，保证链接是通过邮箱中的激活链接发送的
        if($request->token  != $user->token){
            return '当前链接非有效链接，请确保您是通过邮箱的激活链接来激活的';
        }
        //激活时间是否已经超时
        if(time() > $user->expire){
            return '激活链接已经超时，请重新注册';
        }

        $res = $user->update(['active'=>1]);
        //激活成功，跳转到登录页
        if($res){
            return redirect('login')->with('msg','账号激活成功');
        }else{
            return '邮箱激活失败，请检查激活链接，或者重新注册账号';
        }
    }

//    忘记密码
    public function forget()
    {
        return view('home.forget');
    }
    //发送密码找回邮件
    public function doforget(Request $request)
    {
        //要发送邮件的账号
        $email = $request->email;
        // 根据账号名查询用户信息
        $user = HomeUser::where('user_name',$email)->first();
        if($user){
            //想此用户发送密码找回邮件
            Mail::send('email.forget',['user'=>$user],function ($m) use ($user) {
//              $m->from('hello@app.com', 'Your Application');

                $m->to($user->email, $user->name)->subject('找回密码');


            });

            return '请登录您的邮箱查看重置密码邮件，重新设置密码';
        }else{
            return '用户不存在，请重新输入要找回密码的账号';
        }

    }
    //重新设置密码页面
    public function reset(Request $request)
    {
        $input = $request->all();
        //验证token，判断是否是通过重置密码邮件跳转过来的

        $user = HomeUser::find($input['uid']);
        return view('home.reset',compact('user'));
    }

    //重置密码逻辑
    public function doreset(Request $request)
    {
//        1. 接收要重置密码的账号、新密码
        $input = $request->all();

        $pass = Crypt::encrypt($input['user_pass']);

//        2.将此账号的密码重置为新密码
        $res = HomeUser::where('user_name',$input['user_name'])->update(['user_pass'=>$pass]);

//        3. 判断更新是否成功
        if($res){
            return redirect('login');
        }else{
            return redirect('reset');
        }
    }

}
