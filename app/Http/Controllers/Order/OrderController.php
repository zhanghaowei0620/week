<?php

namespace App\Http\Controllers\Order;

use App\Model\CartModel;
use App\Model\OrderDetailModel;
use App\Model\OrderModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /*
     * 订单生成
     * */
    public function create()
    {
        $goods = CartModel::where(['user_id'=>Auth::id(),'session_id'=>Session::getId()])->get()->toArray();
        //计算订单金额
        $order_amount = 0;
        foreach ($goods as $k=>$v)
        {
            $order_amount += $v['goods_selfprice'];
        }

        $order_info = [
            'user_id'        => Auth::id(),
            'order_number'   => OrderModel::generateOrderSN(Auth::id()),     //订单编号
            'order_amount'   => $order_amount,
            'create_time'    => time(),
        ];
        //订单添加入库
        $order_id = OrderModel::insertGetId($order_info);
//        print_r($order_id);die;

        //订单详情表
        foreach ($goods as $k=>$v)
        {
            $detail = [
                'order_id'          => $order_id,
                'goods_id'          => $v['goods_id'],
                'goods_name'        => $v['goods_name'],
                'goods_selfprice'   => $v['goods_selfprice'],
                'user_id'           => Auth::id()
            ];
            //订单详情表添加入库
            OrderDetailModel::insertGetId($detail);
        }
        header('Refresh:3;url=/lists');
        echo '生成订单成功';
    }

    /*
     * 订单展示
     * */
    public function lists()
    {
        $arr = OrderModel::where('is_del',1)->get();
        return view('order.lists',['arr'=>$arr]);
    }

    /*
     * 查询订单支付状态
     * */
    public function paystatus()
    {
        $order_id = intval($_GET['order_id']);
        $info = OrderModel::where(['order_id'=>$order_id])->first();
        $response = [];
        if ($info)
        {
            if ($info->create_time > 0)
            {
                $response = [
                    'pay_status'    => 0,       // 0 已支付
                    'msg'       => 'ok'
                ];
            }

        }else{
            die("订单不存在");
        }
        die(json_encode($response));
    }

}
