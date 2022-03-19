<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('nl_get_setting')) {
    /**
     * 获取系统设置
     *
     * @param int $id  1基础配置 2提现设置 3小程序设置
     */
    function nl_get_setting($id)
    {
        $redis = app('redis.connection');
        $config = $redis->get('setting:' . $id);
        if (empty($config)) {
            $config = DB::table('setting')->where('id', $id)->value('config');
        }
        return json_decode($config, true);
    }
}

if (!function_exists('nl_meituan_sign')) {
    /**
     * 生成美团签名
     *
     * @param array $params 请求参数
     * @param string $secret 美团联盟密钥
     */
    function nl_meituan_sign($params, $secret)
    {
        unset($params["sign"]);
        ksort($params);
        $str = $secret;
        foreach($params as $key => $value) {
            $str .= $key . $value;
        }
        $str .= $secret;
        $sign = md5($str);
        return $sign;
    }
}

if (!function_exists('nl_clock_to_minute')) {
    /**
     * 时刻转成分钟数
     *
     * @param string $clock
     */
    function nl_clock_to_minute($clock)
    {
        $data = explode(':', $clock);
        
        return $data[0] * 60 + $data[1];
    }
}

?>