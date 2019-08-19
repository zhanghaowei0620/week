<?php

namespace App\Http\Controllers\Oss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use OSS\OssClient;

class OssController extends Controller
{
    protected $accessKeyId = "LTAI2sznOiLK1drL";   //
    protected $accessKeySecret = "BJxwVzYHrV9MbALIxnuRtzOweIRrhQ";
    protected $Bucket = '1809-video';

    //上传文件
    public function Oss()
    {
        $ossClient = new OssClient($this->accessKeyId,$this->accessKeySecret,env('ALI_OSS_ENDPOINT'));
        $Object = 'aaa.txt';
        $Content = 'holle world';

        $oss = $ossClient->putObject($this->Bucket,$Object,$Content);

        var_dump($oss);
    }

    //上传图片
    public function Oss2()
    {
        $client = new OssClient($this->accessKeyId, $this->accessKeySecret, env('ALI_OSS_ENDPOINT'));
        $obj = md5(time().mt_rand(1,9999999)).'.jpg';
        $local_file = 'a2.jpg';
        $rs = $client->uploadFile($this->Bucket,$obj,$local_file);
        var_dump($rs);
    }
}
