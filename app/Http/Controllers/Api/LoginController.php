<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Repository\DrawRespository;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
class LoginController extends Controller
{
    public function doLogin(Request $request, User $model)
    {

        $code = $request->input('code');
        if (empty($code)) {
            return $this->responseFail('未查到到code');
        }

        $res = $this->wechatLogin($code);
        if ($res['status'] == 'fail') {
            return $res;
        }
        // 判断是否已经入库
        $result = $model->updateOrCreate(
            ['openid' => $res['openid']],
            [
                'session_key' => $res['session_key'],
                'wx_appid' => env('WEIXIN_APPID', '')
            ]);

        $payload = JWTFactory::sub($result->id)->make();

        $token = JWTAuth::encode($payload)->get();

        // 抽奖次数+1
        $redis = app('redis');
        $key = 'login:daily:send_draw:' . date('Ymd');
        if (!$redis->sIsMember($key, $result->id)) {
            $redis->sAdd($key, $result->id);
            if ($redis->ttl($key) == -1) {
                $redis->expire($key, 86400 * 2);
            }
            $drawRespository = new DrawRespository;
            $drawRespository->incrDrawCount($result->id, 1);
        }

        return response()->json([
            'status' => 'success',
            'info' => [
                'token' => $token,
                'openid' => $res['openid']
            ]
        ]);

        return $token;
    }

    /**
     * 微信登录
     *
     * @param [type] $code
     */
    private function wechatLogin($code)
    {
        $appid = env('WEIXIN_APPID');
        $secret = env('WEIXIN_APPSECRET');
        $api = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $secret . '&js_code=' . $code . '&grant_type=authorization_code';
        $res = file_get_contents($api);
        $res = json_decode($res, true);
        if (isset($res['errcode']) && $res['errcode'] != 0) {
            logger('微信登录验证失败.', $res);
            return $this->responseFail('登录验证失败, code:' . $res['errcode']);
        }

        // 数据入库
        return $res;
    }
}