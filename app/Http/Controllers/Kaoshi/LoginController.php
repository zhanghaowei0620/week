<?php

namespace App\Http\Controllers\Kaoshi;

use App\Jobs\ProcessPodcast;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function log()
    {
        echo __METHOD__;
        ProcessPodcast::dispatch()->onQueue('aaa');

//        return view('kaoshi/login');
    }

    public function login(Request $request)
    {
        $nickname =  $request->input('nickname');
        $password =  $request->input('password');
//        $password= (password_hash($passwd,PASSWORD_BCRYPT));
        $arr = DB::table('user1')->where(['nickname'=>$nickname])->first();
        if ($arr){
//            if (password_verify($password,$arr->password)){
            if ($password==$arr->password){

                $id=$arr->id;
//            var_dump($id);exit;
                Cookie::queue('id',$id);
//                header('Refresh:1;url=index');
                $arr = ['errno'=>50001,'msg'=>'登录成功'];

                return $arr;

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

//    public function aui()
//    {
//        echo __METHOD__;
//        \App\Jobs\Aui::dispatch()->delay(10);
//    }
}
