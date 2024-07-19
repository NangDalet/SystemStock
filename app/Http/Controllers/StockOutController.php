<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;

class StockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['outs'] = DB::table('stock_outs')
            ->join('warehouses','stock_outs.warehouse_id','warehouses.id')
            ->where('stock_outs.active',1)
            ->select('stock_outs.*','warehouses.name')
            ->paginate(config('app.row'));
        $data['q']='';
        return view('outs.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        return view('outs.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $m = json_encode($request->master);
        $m = json_decode($m);
        $data = array(
            'out_date' => $m->out_date,
            'reference' => $m->reference,
            'warehouse_id' => $m->warehouse_id,
            'description' => $m->description,
            'out_by' => Auth::user()->id
        );
        $i = DB::table('stock_outs')->insertGetId($data);
        if($i){
            $items = json_encode($request->items);
            $items = json_decode($items);
            foreach($items as $item){
                $in = array(
                    'stock_out_id' => $i,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'warehouse_id' => $m->warehouse_id //get by master
                );
                $x = DB::table('stock_out_details')->insert($in);
                Helper::subOnhand($item->product_id,$item->quantity);
                Helper::subWarehouse($m->warehouse_id,$item->product_id,$item->quantity);
            }
        }
        return $i;
    }
    public function detail($id){
        $data['out'] = DB::table('stock_outs')
            ->join('warehouses','stock_outs.warehouse_id','warehouses.id')
            ->where('stock_outs.active',1)
            ->where('stock_outs.id',$id)
            ->select('stock_outs.*','warehouses.name')
            ->first();
        $data['items'] = DB::table('stock_out_details')
            ->join('products','stock_out_details.product_id', 'products.id')
            ->join('units','products.unit_id','units.id')
            ->where('stock_out_details.stock_out_id',$id)
            ->select('stock_out_details.*','products.code','products.name','units.name as uname')
            ->get();
        $data['warehouse'] = DB::table('warehouses')
            ->where('active',1)
            ->get();
        $data['products'] = DB::table('products')
            ->where('active',1)
            ->get();
        return view('outs.detail',$data);
    }

    public function save_master(Request $r){
        $data = array(
            'out_date' => $r->out_date,
            'reference' => $r->reference,
            'warehouse_id' => $r->warehouse_id,
            'description' => $r->description,
        );
        $i = DB::table('stock_outs')
        ->where('id',$r->id)
        ->update($data);
        if($i){
            return $r->id;
        }else{
            return 0;
        }
    }
    public function delete_item($id){
        $item = DB::table('stock_out_details')->find($id);
        $i = DB::table('stock_out_details')
            ->where('id',$id)
            ->delete();
        if($i){
            //subtract onhand
            Helper::addOnhand($item->product_id,$item->quantity);
            Helper::addWarehouse($item->warehouse_id,$item->product_id,$item->quantity);
        }
        return $i;
    }
    public function save_item(Request $r){
        $data = array(
            'stock_out_id' => $r->id,
            'warehouse_id' => $r->warehouse_id,
            'product_id' => $r->item,
            'quantity' => $r->quantity
        );
        $i = DB::table('stock_out_details')->insert($data);
        if($i){
            Helper::subOnhand($r->item, $r->quantity);
            Helper::subWarehouse($r->warehouse_id, $r->item, $r->quantity);
        }
        return redirect('stock-out/detail/'.$r->id);
    }
     //method to delete stock its id
     public function delete($id){
        $i = DB::table('stock_outs')
            ->where('id', $id)
            ->delete();
        if($i){
            $items = DB::table('stock_out_details')
                ->where('stock_out_id',$id)
                ->get();
            $n = DB::table('stock_out_details')
                ->where('stock_out_id',$id)
                ->delete();
            if($n){
                foreach($items as $item){
                    Helper::addOnhand($item->product_id, $item->quantity);
                    Helper::addWarehouse($item->warehouse_id, $item->product_id, $item->quantity);
                }
            }
        }
        return redirect('stock-out')->with('success','Data has been removed!');
    }
    public function print($id){
        $data['out'] = DB::table('stock_outs')
        ->join('warehouses','stock_outs.warehouse_id','warehouses.id')
        ->where('stock_outs.active',1)
        ->where('stock_outs.id',$id)
        ->select('stock_outs.*','warehouses.name')
        ->first();
    $data['items'] = DB::table('stock_out_details')
        ->join('products','stock_out_details.product_id', 'products.id')
        ->join('units','products.unit_id','units.id')
        ->where('stock_out_details.stock_out_id',$id)
        ->select('stock_out_details.*','products.code','products.name','units.name as uname')
        ->get();
        return view('outs.print',$data);
   }
}
