<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Model\Cate;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $cate=Cate::get();
        $cateone=[];
        $catetwo=[];
        foreach ($cate as $k=>$v){
            if($v->cate_pid==0){
                $cateone[$k]=$v;
                foreach ($cate as $m=>$n){
                    if($v->cate_id==$n->cate_pid){
                        $catetwo[$k][$m]=$n;
                    }
                }
            }
        }
//        dd($cateone);
//        dd($catetwo);
        view()->share('cateone',$cateone);
        view()->share('catetwo',$catetwo);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
