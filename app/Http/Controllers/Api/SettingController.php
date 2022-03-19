<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    /**
     * 获取小程序相关配置
     */
    public function getMiniProgramSetting()
    {
        $settingContent = nl_get_setting(1);

        return $this->responseSuccess($settingContent);
    }
}