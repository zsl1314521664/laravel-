<?php

namespace App\Http\Controllers\Admin;

//use App\User;
use App\Model\user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $user=User::paginate(3);
//        $input=$request->all();
//        dd($input);
        $user=User::orderBy('user_id','asc')
            ->where(function ($query) use($request){
            $username=$request->input('username');
            if(!empty($username)){
                $query->where('user_name','like','%'.$username.'%');
            }
        })
            ->paginate(3);
        //用户列表
        return view('admin.user.list',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.user.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
//        return 11111;
        $input=$request->all();
        $username=$input['email'];
        $pass=Crypt::encrypt($input['pass']);
        $res=User::create(['user_name'=>$username,'user_pass'=>$pass,'email'=>$input['email']]);
        if($res){
            $data = [
                'status'=>0,
                'message'=>'添加成功'
            ];

        }else{
            $data = [
                'status'=>1,
                'message'=>'添加失败'
            ];

        }
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
//        2. 获取要修改成的用户名
        $username = $request->input('user_name');

        $user->user_name = $username;

        $res = $user->save();

        if($res){
            $data = [
                'status'=>0,
                'message'=>'修改成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'修改失败'
            ];
        }
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $res = $user->delete();
        if($res){
            $data = [
                'status'=>0,
                'message'=>'修改成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'修改失败'
            ];
        }
        return $data;
    }
    //删除所有用户
    public function delAll()
    {
        
    }
}
