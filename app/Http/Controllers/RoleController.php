<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class RoleController extends Controller
{
    //language
    public function __construct(){
        $this->middleware(function($request,$next){
            app()->setLocale(Auth::user()->language);
            return $next($request);
        });
    }
    public function index(){
        $data['roles']=DB::table('roles')
        ->where('active',1)
        ->orderBy('id','desc')
        ->paginate(config('app.row'));
        return view('roles.index',$data);
    }
    public function create(){
        return view('roles.create');
    }
    public function save(Request $r){

        $validate = $r->validate([
            'name' => 'required|min:3|max:200',
        ]);

        $data=array(
            'name'=>$r->name,
        );

        $i=DB::table('roles')->insert($data);
        if($i){
            $r->session()->flash('success','Data has been saved!');
            return redirect('role/create');
        }else{
            $r->session()->flash('error','Fail to save data');
            return redirect('role/create')->withInput();
        }
    }
    public function edit($id){
        $data['role']=DB::table('roles')
        ->where('id',$id)
        ->first();
        return view('roles.edit',$data);
    }
    public function update(Request $r){

        $validate = $r->validate([
            'name' => 'required|min:3|max:200',

        ]);

        $data=array(
            'name'=>$r->name,
        );

        $i=DB::table('roles')
        ->where('id',$r->id)
        ->update($data);
        if($i){
            $r->session()->flash('success','Data has been saved!');
            return redirect('role/edit/'.$r->id);
        }else{
            $r->session()->flash('error','Fail to save data');
            return redirect('role/edit/'.$r->id);
        }
    }
    public function detail($id){
        $data['role']=DB::table('roles')
        ->where('id',$id)
        ->first();
        $sql="select permissions.id as pid, permissions.alias, tbl.*from permissions
              left join(select * from role_permissions where role_id=$id) as tbl
              on permissions.id=tbl.permission_id";
        $data['permissions']=DB::select($sql);
        return view('roles.detail', $data);
    }
    public function save_permission(Request $r){
        $i=0;

        if($r->rpid>0){
            //update role_permission
            $data=array(
                'role_id'=>$r->role_id,
                'permission_id'=>$r->pid,
                'list' => $r->list,
                'create' => $r->create,
                'edit' => $r->edit,
                'delete' => $r->del
            );
            DB::table('role_permissions')->where('id',$r->rpid)
            ->update($data);
            $i=$r->rpid;
        }else{
            //insert into role_permission
            $data=array(
                'role_id'=>$r->role_id,
                'permission_id'=>$r->pid,
                'list' => $r->list,
                'create' => $r->create,
                'edit' => $r->edit,
                'delete' => $r->del
            );
            $i=DB::table('role_permissions')->insertGetId($data);
        }
        return $i;
    }
}
