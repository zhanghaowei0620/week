<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    public function log()  //登录展示
    {
        return view('login.login');
    }

    public function login(Request $request)  //单击登录
    {
        $nickname =  $request->input('nickname');
        $password =  $request->input('password');
//        $password= (password_hash($passwd,PASSWORD_BCRYPT));
        $arr = DB::table('user1')->where(['nickname'=>$nickname])->first();
        if ($arr){
            if (password_verify($password,$arr->password)){

                $key = 'server:login'.$arr->id;
                $data = Redis::get($key);

                if ($data != 1){
                    $data = Redis::set($key,1);

                    header('Refresh:3;url=lis');
                    die('登录okk');

                }else{
                    $response = [
                        'errno' =>  50001,
                        'msg'   =>  '已在其他客户端登录',
                    ];
                    die(json_encode($response,JSON_UNESCAPED_UNICODE));
                }

            }else{

                $response = [
                    'errno' =>  50002,
                    'msg'   =>  '账号或密码错误',
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }
            
        }else{
            $response = [
                'errno' =>  50003,
                'msg'   =>  '账号不正确',
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }

    public function lis()  //展示
    {
        return view('login.goods');
    }

    public function lists()  //
    {
        $num = mt_rand(1,100);
        $res = DB::table('goods')->where('goods_id',$num)->first();
//        print_r($res);die;

        $info = [
            'goods_id'  => $res->goods_id,
        ];
//
        $post_json = json_encode($info);

//        print_r($post_json);die;

        $k = openssl_pkey_get_private('file://'.storage_path('app/key/private.pem'));
        openssl_private_encrypt($post_json,$enc_data,$k);
        $arr = base64_encode($enc_data);
//         print_r($arr);die;
        $url='http://vm.lianxi.com/lists_do';  //重定向地址

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


    public function lists_do()
    {
        $str = file_get_contents("php://input");
//        echo '密文+base64:'.$str;

        $arr = base64_decode($str);  //base64解开
//        print_r($arr);
        $pk = openssl_get_publickey('file://'.storage_path('app/key/public.pem'));
        openssl_public_decrypt($arr,$dec_data,$pk);

        echo '<hr>';
//        echo '明文:'.$dec_data;

        $post_json = json_decode($dec_data);
//        print_r($post_json);die;
        $res = DB::table('goods')->where('goods_id',$post_json->goods_id)->first();
//        print_r($res);die;
        $key = 'goods:goods';
        Redis::set($key,$res);
        $data = Redis::get($key);
        if (!$data){
            $res = DB::table('goods')->where('goods_id',$post_json->goods_id)->first();

            $key = 'goods:goods';
            Redis::set($key,$res);
            $data = Redis::get($key);
//            return view('login.goods',['data'=>$data]);

        }
//        return view('login.goods',['data'=>$data]);

    }




}
