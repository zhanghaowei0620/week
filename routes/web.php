<?php

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

Route::get('/', function () {
    return view('welcome');
});


//考试
//Route::get('log', 'Kaoshi\LoginController@log');  // 登录展示
//Route::get('aui', 'Kaoshi\LoginController@aui');  // 登录展示
//Route::post('login', 'Kaoshi\LoginController@login');  //登录执行
//Route::get('index', 'Kaoshi\GoodsController@index');  //展示
//Route::get('lis', 'Kaoshi\GoodsController@lis');  //商品展示
//Route::get('add', 'Kaoshi\GoodsController@add');  //商品添加展示
//Route::post('add_goods', 'Kaoshi\GoodsController@add_goods');  //商品添加执行
//Route::post('add_goods_do', 'Kaoshi\GoodsController@add_goods_do');  //商品添加执行
//Route::get('updata', 'Kaoshi\GoodsController@updata');  //修改展示
//Route::post('updata_do', 'Kaoshi\GoodsController@updata_do');  //修改执行
//Route::get('del', 'Kaoshi\GoodsController@del');  //删除


Route::get('Oss', 'Oss\OssController@Oss');//
Route::get('Oss2', 'Oss\OssController@Oss2');//


//小电商
Auth::routes();
Route::get('index', 'Index\IndexController@index');  // 商品展示
Route::get('goodsdetail', 'Index\IndexController@goodsdetail');  // 商品详情
Route::any('add/{goods_id?}', 'Index\IndexController@add');  //购物车添加
Route::get('cart', 'Cart\CartController@cart');  //购物车展示
Route::get('create', 'Order\OrderController@create');  //订单提交
Route::get('lists', 'Order\OrderController@lists');  //订单详情
Route::get('pay', 'Pay\PayController@pay');  //订单支付
Route::get('test', 'Pay\PayController@test');  //钥
Route::get('aliReturn', 'Pay\PayController@aliReturn');



Route::get('/home', 'HomeController@index')->name('home');
