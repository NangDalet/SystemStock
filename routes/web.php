<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>'auth'],function(){
    Route::get('/', "DashboardController@index");
    Route::get('user/logout','UserController@logout');
    //user route
    Route::get('user','UserController@index');
    Route::get('user/create','UserController@create');
    Route::post('user/save','UserController@save');
    Route::get('user/delete/{id}','UserController@delete');
    Route::get('user/edit/{id}','UserController@edit');
    Route::post('user/update','UserController@update');
     //roles
     Route::get('role','RoleController@index');
     Route::get('role/create','RoleController@create');
     Route::get('role/edit/{id}','RoleController@edit');
     Route::get('role/delete/{id}','RoleController@delete');
     Route::get('role/detail/{id}','RoleController@detail');
     Route::post('role/save','RoleController@save');
     Route::post('role/update','RoleController@update');
     Route::post('role/permission/save','RoleController@save_permission');
      //Category Route
    Route::resource('category','CategoryController')
    ->except(['show','destroy']);
    //->only(['show','destroy']);
    Route::get('category/delete/{id}','CategoryController@delete');
    //warehouse
    Route::resource('warehouse','WarehouseController')
    ->except(['show','destroy']);
    Route::get('warehouse/delete/{id}','WarehouseController@delete');
    //Units
    Route::resource('unit','UnitController')
    ->except(['show','destroy']);
    Route::get('unit/delete/{id}','UnitController@delete');
    //Products
    Route::resource('product','ProductController')
    ->except(['show','destroy']);
    Route::get('product/delete/{id}','ProductController@delete')
    ->name('product.delete');
    Route::get('product/detail/{id}','ProductController@detail')
    ->name('product.detail');
    Route::get('product/search','ProductController@search');
    Route::post('product/import','ProductController@import');
    //Stock in rount
    Route::resource('stock-in','StockInController')
    ->except(['show','destroy']);
    Route::get('stock-in/detail/{id}','StockInController@detail');
    Route::get('stock-in/item/delete/{id}','StockInController@delete_item');
    Route::post('stock-in/item/save','StockInController@save_item');
    Route::post('stock-in/master/save','StockInController@save_master');
    Route::get('stock-in/delete/{id}','StockInController@delete');
    Route::get('stock-in/print/{id}','StockInController@print');
   //rout stock out
   Route::resource('stock-out','StockOutController')
   ->except(['show','destroy']);
   Route::get('stock-out/detail/{id}','StockOutController@detail');
   Route::get('stock-out/item/delete/{id}','StockOutController@delete_item');
   Route::post('stock-out/item/save','StockOutController@save_item');
   Route::post('stock-out/master/save','StockOutController@save_master');
   Route::get('stock-out/delete/{id}','StockOutController@delete');
   Route::get('stock-out/print/{id}','StockOutController@print');
});

Auth::routes();

