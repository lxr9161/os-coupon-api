<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tool\WechatTool;

class WechatController extends Controller
{
    public function getWxAccessToken(Request $request)
    {
        $wechatTool = new WechatTool;

        return $wechatTool->getAccountTokenRes();
    }
}
