<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class Helper{
    public static function addOnhand($pid, $qty){
        $i = DB::table('products')
        ->where('id',$pid)
        ->increment('onhand',$qty);
        return $i;
    }
    public static function subOnhand($pid,$qty){
        $i = DB::table('products')
        ->where('id',$pid)
        ->decrement('onhand',$qty);
        return $i;
    }
    public static function subWarehouse($wid, $pid, $qty){
        $i = DB::table('product_warehouses')
        ->where('warehouse_id',$wid)
        ->where('product_id',$pid)
        ->decrement('total',$qty);
        return $i;
    }
    public static function addWarehouse($wid, $pid, $qty){
        $p = DB::table('product_warehouses')
        ->where('warehouse_id',$wid)
        ->where('product_id',$pid)
        ->first();
        if($p==null){
            $i = DB::table('product_warehouses')
            ->insert([
                'warehouse_id' => $wid,
                'product_id' => $pid,
                'total' => $qty
            ]);
        }else{
            $i = DB::table('product_warehouses')
            ->where('warehouse_id',$wid)
            ->where('product_id',$pid)
            ->increment('total',$qty);
        }
        return $i;
    }
}
