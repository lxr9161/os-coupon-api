<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{


    /** 
     * 小程序配置对应id
     */
    const SETTING_MIN_PROGRAM = 1;


    /**
     * 小程序配置
     *
     * @param Request $request
     */
    public function miniProgramSetting(Request $request)
    {
        $setting = DB::table('setting')->where('id', self::SETTING_MIN_PROGRAM)->first();

        $settingContent = json_decode($setting->config, true);

        return $this->responseSuccess([
            //抽奖开关
            'draw_status' => $settingContent['draw_status'] ?: 0,
            //个人中心优惠券入口显示控制
            'center_coupon_enter' => $settingContent['center_coupon_enter'] ?: 0,
            //首页优惠券入口显示控制
            'index_coupon_display' => $settingContent['index_coupon_display'] ?: 0,
            //首页更多优惠券入口显示控制
            'index_coupon_more' => $settingContent['index_coupon_more'] ?: 0
        ]);
    }

    /**
     * 保存
     *
     * @param Request $request
     */
    public function saveSetting(Request $request)
    {
        $id = $request->input('id');
        $config = $request->input('config');
        $res = DB::table('setting')->where('id', $id)->update(['config' => $config, 'updated_at' => time()]);
        if ($res) {
            $redis = app('redis.connection');
            $redis->set('setting:' . $id, $config);
            return $this->responseSuccess();
        }

        return $this->responseFail();
    }
}
