<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Storage;
use App\Tool\CommonTool;

class UploadController extends Controller
{
    public function uploadToLocal(Request $request)
    {
        $file = $request->file('file');

        if ($file->isValid()) {
            $fileTypes = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            $isInFileType = in_array($extension,$fileTypes);
            $maxSize = 3145728;
            if (!$isInFileType) {
                return $this->responseFail('图片类型不允许');
            } 
            $size = $file->getSize();
            if ($size > $maxSize) {
                return $this->responseFail('图片过大，限制3M');
            }
            $path = $file->store('images', 'public');
            if ($path) {
                return ['status' => 'success', 'info' => Storage::url($path)];
            }
        }

        return ['status' => 'fail', 'info' => '上传失败'];
    }

    /**
     * 获取上传阿里云oss token
     *
     * @param Request $request
     */
    public function uploadToOssToken(Request $request)
    {

	    $now = time();
	    $expire = 120; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
	    $end = $now + $expire;
	    $expiration = $this->gmtIso8601($end);
	    $d =  $request->input('d');
	    if (!empty($d)) {
	    	$dir = $d.'/'.date('Ymd').'/';
	    } else {
	    	$dir = 'video/'.date('Ymd').'/';
	    }

	    //最大文件大小.用户可以自己设置
	    $condition = array(0=>'content-length-range', 1=>0, 2=>10485760000);
	    $conditions[] = $condition; 

	    //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
	    $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
	    $conditions[] = $start; 


	    $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
	    //echo json_encode($arr);
	    //return;
	    $ossId = env('ALIYUN_ACCESS_ID');
	    $osskey = env('ALIYUN_ACCESS_SECRET');
	    $host = env('ALIYUN_OSS_BUCKET_HOST');
	    $policy = json_encode($arr);
	    $base64_policy = base64_encode($policy);
	    $string_to_sign = $base64_policy;
	    $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $osskey, true));

	    $response = array();
	    $response['accessid'] = $ossId;
	    $response['host'] = $host;
	    $response['policy'] = $base64_policy;
	    $response['signature'] = $signature;
	    $response['expire'] = $end;
	    //这个参数是设置用户上传指定的前缀
	    $response['dir'] = $dir;
        $response['yunxc_host'] = env('ALIYUN_OSS_ENDPOINT');
        
	    return $response;
    }

    private function gmtIso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }


	/**
     * 获取上传token
     */
    public function getQiniuUploadToken()
    {
        $tool = new CommonTool;
        $token = $tool->genQiunuUploadToken();

        return $this->responseSuccess([
            'token' => $token
        ]);
    }
}

?>
