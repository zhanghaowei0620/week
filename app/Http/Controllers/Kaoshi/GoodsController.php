<?php

namespace App\Http\Controllers\Kaoshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    public function index()  //首页展示
    {
        return view('kaoshi.index');
    }

    public function lis()  //商品列表
    {
        $arr = DB::table('kao_goods')->where('is_del',1)->get();
        return view('kaoshi.goods',['arr'=>$arr]);
    }

    public function add(Request $request)  //商品添加展示
    {
        $cookie_id = $request->cookie('id');
        if ($cookie_id){
            return view('kaoshi.add');
        }else{
            header('Refresh:3;url=log');
            die("请先登录,自动跳转至登录页面");
        }

    }

    public function add_goods(Request $request)  //商品添加执行
    {
        $goods_name = $request->input('goods_name');
        $goods_img = $this->upload($request,'goods_img');

        $info = [
            'goods_name'  =>  $goods_name,
            'goods_img'   =>  $goods_img,
        ];
//        print_r($info);die;

        $cart_json = json_encode($info);
//        print_r($cart_json);die;
        $k = openssl_pkey_get_private('file://'.storage_path('app/key/private.pem'));
        openssl_private_encrypt($cart_json,$enc_data,$k);
        $arr = base64_encode($enc_data);

        $url='http://vm.lianxi.com/add_goods_do';  //重定向地址

        //创建一个新curl资源 初始化
        $ch = curl_init();
        //设置URL和对应的选项
        curl_setopt($ch,CURLOPT_URL,$url);

        //为post请求
        curl_setopt($ch,CURLOPT_POST,1);

        //发送数据
        curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);

        //禁止浏览器输出，用变量接收
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //禁止 cURL 验证对等证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //是否检测服务器的域名与证书上的是否一致

        //抓取URL并传给浏览器
        $data = curl_exec($ch);
        echo $data;

        //查看错误码
        $err_code = curl_errno($ch);
        if ($err_code > 0){
            echo "CURL错误码:".$err_code;
            exit;
        }
        //关闭curl资源，并释放系统内存
        curl_close($ch);
    }

    public function add_goods_do()  //商品添加执行
    {
        $str = file_get_contents("php://input");
        $arr = base64_decode($str);  //base64解开
//        print_r($arr);
        $pk = openssl_get_publickey('file://'.storage_path('app/key/public.pem'));  //公钥解开
        openssl_public_decrypt($arr,$dec_data,$pk);

        echo '<hr>';
//        echo '明文:'.$dec_data;
        $arr = json_decode($dec_data);
//        print_r($arr);die;

        $goods_name = $arr->goods_name;
        $goods_img = $arr->goods_img;

        $info = [
            'goods_name'  => $goods_name,
            'goods_img'   => $goods_img,
        ];
//        print_r($info);die;
        $res = DB::table('kao_goods')->insert($info);
        if ($res){
            $response = [
                'errno' =>  50006,
                'msg'   =>  '成功',
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
//            header('Refresh:3;url=log');
        }else{
            $response = [
                'errno' =>  50005,
                'msg'   =>  '添加失败',
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }

    public function updata(Request $request)  //修改展示
    {
        $goods_id = $request->input('goods_id');
        $arr = DB::table('kao_goods')->where('goods_id','=',$goods_id)->first();
        return view('kaoshi.updata',['arr'=>$arr]);
    }

    public function updata_do(Request $request)  //修改
    {
        $goods_id = $request->input('goods_id');
//         print_r($goods_id);die;
        $data = $request->input();
        $cookie_id = $request->cookie('id');
        if ($cookie_id){
            $info = [
                'goods_name'  => $data['goods_name'],
                'goods_img'   => $data['goods_img'],
            ];
            // print_r($info);die;
            $res = DB::table('kao_goods')->where('goods_id',$goods_id)->update($info);

            if($res){
                $arr = ['status'=>50007,'msg'=>'修改成功'];
                return $arr;
            }else{
                $arr = ['status'=>50008,'msg'=>'修改失败'];
                return $arr;
            }

        }else{
            header('Refresh:3;url=log');
            die("请先登录,自动跳转至登录页面");
        }
    }


    public function del(Request $request)  //删除
    {
        $goods_id = $request->input('goods_id');

        $cookie_id = $request->cookie('id');
        if ($cookie_id){
            $info = [
                'is_del' => 2,
            ];
            $arr = DB::table('kao_goods')->where('goods_id',$goods_id)->update($info);
            if($arr){
                $arr = ['status'=>50009,'msg'=>'删除成功'];
                die(json_encode($arr,JSON_UNESCAPED_UNICODE));
                return $arr;
            }else{
                $arr = ['status'=>50010,'msg'=>'删除失败'];
                die(json_encode($arr,JSON_UNESCAPED_UNICODE));
                return $arr;
            }
        }else{
            header('Refresh:3;url=log');
            die("请先登录,自动跳转至登录页面");
        }

    }


    public function upload(Request $request,$filename)  //文件上传
    {
        if ($request->hasFile($filename) && $request->file($filename)->isValid()) {
            $photo = $request->file($filename);
            // $extension = $photo->extension();
            // $store_result = $photo->store('photo');
            $store_result = $photo->store('uploads/'.date('Ymd'));
            return $store_result;
        }
        exit('未获取到上传文件或上传过程出错');
    }
}
