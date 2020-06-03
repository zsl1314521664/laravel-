<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Article;
use App\Model\Cate;


class IndexController extends Controller
{
    public function index()
    {
        //获取相关二级类及二级类下的文章
        $cate_arts = Cate::where('cate_pid','<>',0)->with('article')->get();
//        dd($cate_arts);
        return view('home.index',compact('cate_arts'));
    }

    public function shouye()
    {
        return view('home.index');
}
    public function lists(Request $request,$id)
    {
//        获取分类
        $cate = Cate::find($id);

        $cateid = $cate->cate_id;

        $catename = $cate->cate_name;
//        dd($catename);
        $arr = [];
        if($cate->cate_pid == 0){
            $cate = Cate::where('cate_pid',$cate->cate_id)->get();
            //存放分类id的数组
            $arr = [];
            foreach ($cate as $v){
                $arr[] = $v->cate_id;
            }
        }else{
            $arr[] = $cate->cate_id;
        }
        //获取分类下的文章
        $arts = Article::whereIn('cate_id',$arr)->paginate(5);
//        dd($arts);
        return view('home.lists',compact('catename','cateid','arts'));
    }
}
