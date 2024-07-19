<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Image;
use QrCode;
use Excel;
use Session;

class ProductController extends Controller
{
    public function index()
    {
        $data['q']='';
        $data['products']=DB::table('products')
        ->join('categories','products.category_id','categories.id')
        ->join('units','products.unit_id','units.id')
        ->where('products.active',1)
        ->select('products.*','categories.name as cname','units.name as uname')
        ->orderBy('products.id','desc')
        ->paginate(config('app.row'));
        return view('products.index',$data);
    }

    public function create()
    {
        $data['cats']=DB::table('categories')
        ->where('active',1)
        ->get();
        $data['units']=DB::table('units')
        ->where('active',1)
        ->get();
        return view('products.create',$data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required|unique:products',
            'name'=>'required'
        ]);
        $data = $request->except('_token','photo');
        if($request->photo){
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();
            $file_name = md5(date('Y-m-d-H-i-s')) . "." . $ext;

            $image = Image::make($file->getRealPath())
            ->resize(450, null, function($aspect){
                $aspect->aspectRatio();
            });
            $image->save('uploads/products/' .$file_name,80);
            $data['photo'] = 'uploads/products/' . $file_name;
        }
        $i = DB::table('products')->insertGetId($data);
        if($i){
            //Generate qrcode
            $qr = "uploads/products/qrcodes/" . $i . "-qrcode.png";
            QrCode::size(500)->format('png')
                ->generate(url('product/detail/'.$i), public_path($qr));
            DB::table('products')
                ->where('id',$i)
                ->update(['qrcode'=>$qr]);
            return redirect()->route('product.create')->with('success','Data has been saved!');
        }else{
            Session()->flash('error','Fail to save data. Please check again!');
            return redirect()->route('product.create')->withInput();
        }
    }

    public function edit($id)
    {
        $data['cats']=DB::table('categories')
        ->where('active',1)
        ->get();
        $data['units']=DB::table('units')
        ->where('active',1)
        ->get();

        $data['product']=DB::table('products')->find($id);
        return view('products.edit',$data);
    }
    public function update(Request $request, $id)
    {
        $data=$request->except('_token','_method','photo');
        $p=DB::table('products')->find($id);
        if($request->photo){
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();
            $file_name = md5(date('Y-m-d-H-i-s')) . "." . $ext;

            $image = Image::make($file->getRealPath())
            ->resize(450, null, function($aspect){
                $aspect->aspectRatio();
            });
            $image->save('uploads/products/' .$file_name,80);
            $data['photo'] = 'uploads/products/' . $file_name;
            //$data['photo']=$request->file('photo')->store('uploads/products','custom');
            unlink($p->photo);
        }
        $i=DB::table('products')
        ->where('id',$id)
        ->update($data);
        if($i){
            return redirect()->route('product.edit',$id)
            ->with('success','Data has been saved!');
        }else{
            return redirect()->route('product.edit',$id)
            ->with('error','Fail to save data, please check again!');
        }
    }
    public function delete($id){
        DB::table('products')
        ->where('id',$id)
        ->update(['active'=>0]);
        return redirect('product')->with('success','Data has been removed successfully!');
    }
    public function search(Request $r)
    {
        $data['q']=$r->q;
        $q=$r->q;
        $data['products']=DB::table('products')
        ->join('categories','products.category_id','categories.id')
        ->join('units','products.unit_id','units.id')
        ->where('products.active',1)
        ->where(function($query)use($q){
            $query=$query->orWhere('products.code','like',"%{$q}%")
            ->orWhere('products.name','like',"%{$q}%")
            ->orWhere('products.barcode','like',"%{$q}%")
            ->orWhere('products.brand','like',"%{$q}%")
            ->orWhere('products.description','like',"%{$q}%")
            ->orWhere('categories.name','like',"%{$q}%");
        })
        ->select('products.*','categories.name as cname','units.name as uname')
        ->orderBy('products.id','desc')
        ->paginate(config('app.row'));
        return view('products.index',$data);
    }
    public function detail($id){
        $p=DB::table('products')
        ->join('categories','products.category_id','categories.id')
        ->join('units','products.unit_id','units.id')
        ->where('products.id',$id)
        ->select('products.*','categories.name as cname','units.name as uname')
        ->first();
        return view('products.detail',compact('p'));
    }
    public function import(Request $r){
        $path=$r->file('file_import')->getRealPath();
        $data=Excel::load($path,function($reader){})->get();
        if($data->count() && !empty($data)){
            foreach($data as $key => $value){
                $product=array(
                    'code'=>$value->code,
                    'name'=>$value->name,
                    'barcode'=>$value->barcode,
                    'price'=>$value->price,
                    'cost'=>$value->cost,
                    'brand'=>$value->brand,
                    'category_id'=>$value->category_id,
                    'unit_id'=>$value->unit_id,
                    'description'=>$value->description
                );
                DB::table('products')->insert($product);
            }
            if(!empty($product)){
                return redirect('product')->with('success','Data has been imported successfully');
            }else{
                return redirect('product')->with('error','Fail to importe data, please check again!');
            }
        }
    }
}
