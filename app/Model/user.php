<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    //关联用户表
    public $table='user';
//    主键
    public $primaryKey='user_id';
//    允许批量操作
//    public $fillable=['user_name','user_pass','phone','email'];
    public $guarded=[];
//    是否维护cra和update字段
    public $timestamps=false;

    public function role()
    {
        return $this->belongsToMany('App\Model\Role','user_role','user_id','role_id');
    }
}
