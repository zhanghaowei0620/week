<?php

namespace App\Http\Controllers\Index;

use App\Model\CartModel;
use http\Header;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    /*
     * 商品展示
     * */
    public function index()
    {
        $arr = GoodsModel::where('goods_up',1)->get();  //查询上架的商品

        return view("index.index",['arr'=>$arr]);
    }

    /*
     * 商品详情
     * */
    public function goodsdetail()
    {
        $goods_id = intval($_GET["goods_id"]);
        $arr = GoodsModel::where('goods_id',$goods_id)->get();
        return view('index.goodsdetail',['arr'=>$arr]);

    }

    /*
     * 添加购物车
     * */
    public function add($goods_id)
    {
        //是否购买商品
        if (empty($goods_id))
        {
            header('Refresh:3;url=/cart');
            die("请选择购买的商品");
        }

        //商品是否有效
        $goods = GoodsModel::where(['goods_id'=>$goods_id])->first();
        if ($goods)
        {
            //商品是否上架
            if ($goods->goods_up > 1 ){
                header("Refresh:3;url=indexx");
                echo "该商品已下架，请重新选择商品";
                die;
            }

            //商品库存是否充足
            if ($goods->goods_num == 0 ){
                header("Refresh:3;url=indexx");
                echo "该商品库存不足，请重新选择商品";
                die;
            }

            //进行添加购物车
            $cart_info = [
                'goods_id'        => $goods['goods_id'],
                'goods_name'      => $goods['goods_name'],
                'goods_selfprice' => $goods['goods_selfprice'],
                'user_id'         => Auth::id(),
                'create_time'     => time(),
                'session_id'      => Session::getId(),
                'buy_number'      => 1,
            ];

            //执行入库
            $cart_id = CartModel::insertGetId($cart_info);
            if ($cart_id){
                header('Refresh:3;url=/cart');
                die("添加购物车成功，自动跳转至购物车");
            }else{
                header('Refresh:3;url=/indexx');
                die("添加购物车失败");
            }
        }else{
            echo  '商品已下架或售完';
        }
    }
}
