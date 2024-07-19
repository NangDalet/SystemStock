<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['categories']=DB::table('categories')
        ->where('active',1)
        ->orderBy('id','desc')
        ->paginate(config('app.row'));
        return view('categories.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $i = DB::table('categories')
       ->insert($request->except('_token'));
       if($i){
           Session::flash('success','Data has been save!');
           return redirect('category/create');
       }else{
           Session::flash('error','Fail to save data!');
           return redirect('category/create')->withInput();
       }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cat=DB::table('categories')->find($id);
        return view('categories.edit',compact('cat'));
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
        $i=DB::table('categories')
        ->where('id',$id)
        ->update(['name'=>$request->name]);
        if($i){
            //Session::flash('success','Data has been updated successfully.');
            return redirect()->route('category.edit',$id)
            ->with('success','Data has been updated successfully.');
        }else{
            Session::flash('error','Fail to save data!');
            return redirect("category/{$id}/edit");
        }
    }

    public function delete($id){
        $i=DB::table('categories')
        ->where('id',$id)
        ->update(['active'=>0]);
        return redirect()->route('category.index')
        ->with('success','Data has been removed!');
    }
}
