<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units=DB::table('units')
        ->where('active',1)
        ->orderBy('id','desc')
        ->paginate(config('app.row'));
        return view('units.index',compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $i=DB::table('units')
        ->insert($request->except('_token'));
        if($i){
            Session::flash('success','Data has been saved!');
            return redirect('unit/create');
        }else{
            Session::flash('error','Fail to save data!');
            return redirect('unit/create')->withInput();
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
        $data['unit']=DB::table('units')->find($id);
        return view('units.edit',$data);
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
        $i=DB::table('units')
        ->where('id',$id)
        ->update(['name'=>$request->name]);
        if($i){
            Session::flash('success','Data has been saved');
            return redirect()->route('unit.edit',$id);
        }
        else{
            return redirect()->route('unit.edit',$id)
            ->with('error','Fail to save data!');
        }
    }
    public function delete($id){
        DB::table('units')
        ->where('id',$id)
        ->update(['active'=>0]);
        return redirect('unit')->with('success','Data has been removed!');
    }
}
