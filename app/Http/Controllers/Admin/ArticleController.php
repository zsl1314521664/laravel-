<?php

namespace App\Http\Controllers\Admin;

use App\Model\Cate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;
use Image;
use Storage;
use Redis;
use App\Model\Article;

class ArticleController extends Controller
{
    //文章上传
    public function upload(Request $request)
    {
        //获取上传文件
        $file = $request->file('photo');
        //判断上传文件是否成功
        if (!$file->isValid()) {
            return response()->json(['ServerNo' => '400', 'ResultData' => '无效的上传文件']);
        }
        //获取原文件的扩展名
        $ext = $file->getClientOriginalExtension();    //文件拓展名
        //新文件名
        $newfile = md5(time() . rand(1000, 9999)) . '.' . $ext;

        //文件上传的指定路径
        $path = public_path('uploads');


        //将文件从临时目录移动到本地指定目录
        if (!$file->move($path, $newfile)) {
            return response()->json(['ServerNo' => '400', 'ResultData' => '保存文件失败']);
        }
        return response()->json(['ServerNo' => '200', 'ResultData' => $newfile]);
        $res=Image::make($file)->resize(100,100)->save($path.'/'.$newfile);
        if($res){
//            // 如果上传成功
            return response()->json(['ServerNo'=>'200','ResultData'=>$newfile]);
        }else{
            return response()->json(['ServerNo'=>'400','ResultData'=>'上传文件失败']);
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $arts=Article::get();
        return view('admin.article.list',compact('arts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cates=(new Cate())->tree();
        return view('admin.article.add',compact('cates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token','photo');
//        $input['art_time']=time();
        $input['art_time'] = time();
        $input['art_view'] = 0;
        $input['art_status'] = 0;
        $res=Article::create($input);
//        $vali
//        dd($re);
//        dd($input);
        if($res){
            return redirect('admin/article');
        }else{
            return back();
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
