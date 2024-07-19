<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use DB;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        {
            Blade::if('canview',function($expression){
                $role_id=Auth::user()->role_id;
                $q=DB::table('role_permissions')
                ->join('permissions','permissions.id','=','role_permissions.permission_id')
                ->select('role_permissions.*')
                ->where(['role_permissions'.'.role_id'=>$role_id,'permissions.name'=>$expression])
                ->where('role_permissions.list',1);

                $i=$q->count()>0;
                if($i){
                    return true;
                }else{
                    return false;
                }
            });
            Blade::if('cancreate',function($expression){
                $role_id=Auth::user()->role_id;
                $q=DB::table('role_permissions')
                ->join('permissions','permissions.id','=','role_permissions.permission_id')
                ->select('role_permissions.*')
                ->where(['role_permissions'.'.role_id'=>$role_id,'permissions.name'=>$expression])
                ->where('role_permissions.create',1);

                $i=$q->count()>0;
                if($i){
                    return true;
                }else{
                    return false;
                }
            });
            Blade::if('canedit',function($expression){
                $role_id=Auth::user()->role_id;
                $q=DB::table('role_permissions')
                ->join('permissions','permissions.id','=','role_permissions.permission_id')
                ->select('role_permissions.*')
                ->where(['role_permissions'.'.role_id'=>$role_id,'permissions.name'=>$expression])
                ->where('role_permissions.edit',1);

                $i=$q->count()>0;
                if($i){
                    return true;
                }else{
                    return false;
                }
            });
            Blade::if('candelete',function($expression){
                $role_id=Auth::user()->role_id;
                $q=DB::table('role_permissions')
                ->join('permissions','permissions.id','=','role_permissions.permission_id')
                ->select('role_permissions.*')
                ->where(['role_permissions'.'.role_id'=>$role_id,'permissions.name'=>$expression])
                ->where('role_permissions.delete',1);

                $i=$q->count()>0;
                if($i){
                    return true;
                }else{
                    return false;
                }
            });
        }
    }
}
