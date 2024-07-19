<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class UserController extends Controller
{
    //language
    public function __construct(){
        $this->middleware(function($request,$next){
            app()->setLocale(Auth::user()->language);
            return $next($request);
        });
    }

    public function index(){
        if(!Permission::check('user','l')){
            return view('roles.no');
        }

        $data['users']=DB::table('users')
        ->join('roles','users.role_id','roles.id')
        ->orderBy('users.id','desc')
        ->select('users.*','roles.name as rname')
        ->orderBy('id','desc')
        ->paginate(config('app.row'));
        return view('users.index',$data);
    }
    public function create(){
        if(!Permission::check('user','i')){
            return view('roles.no');
        }
        $data['roles']=DB::table('roles')
        ->where('active',1)
        ->get();
        return view('users.create',$data);
    }
    public function save(Request $r){
        $validate = $r->validate([
            'name' => 'required|min:3|max:200',
            'email' => 'required',
            'username' => 'required|min:3|unique:users',
            'password' => 'required|min:3'
        ]);
        $data = array(
            'name'=> $r->name,
            'email'=> $r->email,
            'username'=> $r->username,
            'role_id'=>$r->role,
            'password'=> bcrypt($r->name)
        );
        if($r->photo){
            $data['photo'] = $r->file('photo')->store('uploads/users', 'custom');
        }
        $i = DB::table('users')->insert($data);
        if($i){
            $r->session()->flash('success','Data has been saved');
            return redirect('user/create');
        }else{
            $r->session()->flash('error','Fail to save data!');
            return redirect('user/create')->withInput();
        }
    }
    public function update(Request $r){
        $validate = $r->validate([
            'name' => 'required|min:3|max:200',
            'email' => 'required',
            'username' => 'required'
        ]);
        $data = array(
            'name'=> $r->name,
            'email'=> $r->email,
            'username'=> $r->username,
            'language'=>$r->language,
            'role_id'=>$r->role,
        );
        if($r->password){
            $data['password']=bcrypt($r->password);
        }
        if($r->photo){
            $data['photo'] = $r->file('photo')->store('uploads/users', 'custom');
        }
        $i = DB::table('users')
        ->where('id',$r->id)
        ->update($data);
        if($i){
            $r->session()->flash('success','Data has been saved');
            return redirect('user/edit/'.$r->id);
        }else{
            $r->session()->flash('error','Fail to save data!');
            return redirect('user/edit/'.$r->id);
        }
    }
    public function edit($id){
        $data['roles']=DB::table('roles')
        ->where('active',1)
        ->get();

        $data['user']=DB::table('users')
        ->where('id',$id)
        ->first();
        return view('users.edit',$data);
    }
    public function delete($id, Request $r){
        DB::table('users')
        ->where('id',$id)
        ->delete();
        $r->session()->flash('success','Data has been removed!');
        return redirect('user');
    }
    public function logout(){
        Auth::logout();
        return redirect('login');
    }
}
