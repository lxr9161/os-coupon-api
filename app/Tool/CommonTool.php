<?php
namespace App\Tool;

use Qiniu\Auth;

class CommonTool
{
    public function genQiunuUploadToken()
    {
        $accessKey = env('QINIU_ACCESS_KEY');
        $secretKey = env('QINIU_SECRET_KEY');
        $bucket = env('QINIU_BUCKET');
        $qiniuAuth = new Auth($accessKey, $secretKey);
        $token = $qiniuAuth->uploadToken($bucket, null, 1800, [
            'mimeLimit' => 'image/*',
            'fsizeLimit' => 10485760
        ]);
        
        return $token;
    }
}
