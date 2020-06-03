<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//前台路由
Route::get('index','Home\IndexController@index');
Route::get('lists/{id}','Home\IndexController@lists');
Route::get('detain/{id}','Home\IndexController@detail');
Route::get('shouye','Home\IndexController@shouye');
//后台登录路由
Route::get('admin/login', 'Admin\LoginController@login');
//验证码
Route::get('admin/code', 'Admin\LoginController@code');
//处理后台登陆路由
Route::get('admin/dologin', 'Admin\LoginController@doLogin');
Route::get('admin/getcode', 'Admin\LoginController@getcode');
Route::get('admin/jiami', 'Admin\LoginController@jiami');

Route::get('noaccess', 'Admin\LoginController@noaccess');
//    发送邮箱
Route::get('emailregister','Home\RegisterController@register');
Route::post('doregister','Home\RegisterController@doRegister');
//激活邮箱
Route::get('active','Home\RegisterController@active');
Route::get('forget','Home\RegisterController@forget');
//发送密码找回邮件
Route::post('doforget','Home\RegisterController@doforget');
//重新设置密码页面
Route::get('reset','Home\RegisterController@reset');
//重置密码逻辑
Route::post('doreset','Home\RegisterController@doreset');
//登录
Route::get('login','Home\LoginController@login');
Route::post('dologin','Home\LoginController@doLogin');
Route::get('loginout','Home\LoginController@loginOut');

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['isLogin']],function() {
//后台首页路由
    Route::get('index', 'LoginController@index');
//后台欢迎页
    Route::get('welcome', 'LoginController@welcome');
//后台退出登录路由
    Route::get('logout', 'LoginController@logout');
//主题切换
    Route::get('changetheme','LoginController@changetheme');
//    删除
    Route::post('user/del','UserController@deAll');
//    后台用户模块
    Route::resource('user','UserController');
//    角色模块
    Route::resource('role','RoleController');
//    角色授权
    Route::get('role/auth/{id}','RoleController@auth');
    Route::post('role/doauth/','RoleController@doAuth');
//    列表
    Route::resource('cate','CateController');
//    修改排序
    Route::post('cate/changeorder','CateController@changeOrder');
//    文章模块
    Route::post('article/upload','ArticleController@upload');
    Route::resource('article','ArticleController');
//    权限管理
    Route::resource('permission','PermissionController');
//    网站配置模块
    Route::post('config/changecontent','ConfigController@changeContent');
    Route::get('config/putcontent','ConfigController@putContent');
    Route::resource('config','ConfigController');

});

////后台首页路由
////Route::get('admin/index', 'Admin\LoginController@index');
////Route::get('admin/welcome', 'Admin\LoginController@welcome');
//////退出登录
////Route::get('admin/logout', 'Admin\LoginController@logout');