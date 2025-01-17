<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;

class StockInController extends Controller
{
    public function index()
    {
        $data['ins'] = DB::table('stock_ins')
            ->join('warehouses','stock_ins.warehouse_id','warehouses.id')
            ->where('stock_ins.active',1)
            ->select('stock_ins.*','warehouses.name')
            ->paginate(config('app.row'));
        $data['q']='';
        return view('ins.index',$data);
    }
    public function create()
    {
       $data['ws'] = DB::table('warehouses')
        ->where('active',1)
        ->get();
       $data['products']=DB::table('products')
        ->join('units','products.unit_id','units.id')
        ->where('products.active',1)
        ->select('products.*','units.name as uname')
        ->get();
        return view('ins.create',$data);
    }
    public function store(Request $request)
    {
        $m = json_encode($request->master);
        $m = json_decode($m);
        $data = array(
            'in_date' => $m->in_date,
            'reference' => $m->reference,
            'po_no' => $m->po_no,
            'warehouse_id' => $m->warehouse_id,
            'description' => $m->description,
            'in_by' => Auth::user()->id
        );
        $i = DB::table('stock_ins')->insertGetId($data);
        if($i){
            $items = json_encode($request->items);
            $items = json_decode($items);
            foreach($items as $item){
                $in = array(
                    'stock_in_id' => $i,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'warehouse_id' => $m->warehouse_id //get by master
                );
                $x = DB::table('stock_in_details')->insert($in);
                Helper::addOnhand($item->product_id,$item->quantity);
                Helper::addWarehouse($m->warehouse_id,$item->product_id,$item->quantity);
            }
        }
        return $i;
    }
    public function detail($id){
        $data['in'] = DB::table('stock_ins')
            ->join('warehouses','stock_ins.warehouse_id','warehouses.id')
            ->where('stock_ins.active',1)
            ->where('stock_ins.id',$id)
            ->select('stock_ins.*','warehouses.name')
            ->first();
        $data['items'] = DB::table('stock_in_details')
            ->join('products','stock_in_details.product_id', 'products.id')
            ->join('units','products.unit_id','units.id')
            ->where('stock_in_details.stock_in_id',$id)
            ->select('stock_in_details.*','products.code','products.name','units.name as uname')
            ->get();
        $data['warehouse'] = DB::table('warehouses')
            ->where('active',1)
            ->get();
        $data['products'] = DB::table('products')
            ->where('active',1)
            ->get();
        return view('ins.detail',$data);
    }
    public function delete_item($id){
        $item = DB::table('stock_in_details')->find($id);
        $i = DB::table('stock_in_details')
            ->where('id',$id)
            ->delete();
        if($i){
            //subtract onhand
            Helper::subOnhand($item->product_id,$item->quantity);
            Helper::subWarehouse($item->warehouse_id,$item->product_id,$item->quantity);
        }
        return $i;
    }
    public function save_item(Request $r){
        $data = array(
            'stock_in_id' => $r->id,
            'warehouse_id' => $r->warehouse_id,
            'product_id' => $r->item,
            'quantity' => $r->quantity
        );
        $i = DB::table('stock_in_details')->insert($data);
        if($i){
            Helper::addOnhand($r->item, $r->quantity);
            Helper::addWarehouse($r->warehouse_id, $r->item, $r->quantity);
        }
        return redirect('stock-in/detail/'.$r->id);
    }
    public function save_master(Request $r){
        $data = array(
            'in_date' => $r->in_date,
            'reference' => $r->reference,
            'po_no' => $r->po_no,
            'warehouse_id' => $r->warehouse_id,
            'description' => $r->description,
        );
        $i = DB::table('stock_ins')
        ->where('id',$r->id)
        ->update($data);
        if($i){
            return $r->id;
        }else{
            return 0;
        }
    }
    //method to delete stock its id
    public function delete($id){
        $i = DB::table('stock_ins')
            ->where('id', $id)
            ->delete();
        if($i){
            $items = DB::table('stock_in_details')
                ->where('stock_in_id',$id)
                ->get();
            $n = DB::table('stock_in_details')
                ->where('stock_in_id',$id)
                ->delete();
            if($n){
                foreach($items as $item){
                    Helper::subOnhand($item->product_id, $item->quantity);
                    Helper::subWarehouse($item->warehouse_id, $item->product_id, $item->quantity);
                }
            }
        }
        return redirect('stock-in')->with('success','Data has been removed!');
    }
   public function print($id){
        $data['in'] = DB::table('stock_ins')
        ->join('warehouses','stock_ins.warehouse_id','warehouses.id')
        ->where('stock_ins.active',1)
        ->where('stock_ins.id',$id)
        ->select('stock_ins.*','warehouses.name')
        ->first();
    $data['items'] = DB::table('stock_in_details')
        ->join('products','stock_in_details.product_id', 'products.id')
        ->join('units','products.unit_id','units.id')
        ->where('stock_in_details.stock_in_id',$id)
        ->select('stock_in_details.*','products.code','products.name','units.name as uname')
        ->get();
        return view('ins.print',$data);
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
        //
    }
}
