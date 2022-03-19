<?php

namespace App\Tool;

use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class WechatTool
{

    public $OK = 0;
	public $IllegalAesKey = -41001;
	public $IllegalIv = -41002;
	public $IllegalBuffer = -41003;
	public $DecodeBase64Error = -41004;

    private $appid;
	private $secret;

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->appid = env('WEIXIN_APPID');
        $this->secret = env('WEIXIN_APPSECRET');
	}


	/**
	 * 检验数据的真实性，并且获取解密后的明文.
	 * @param $encryptedData string 加密的用户数据
	 * @param $iv string 与用户数据一同返回的初始向量
	 * @param $data string 解密后的原文
     *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function decryptData($sessionKey, $encryptedData, $iv)
	{
		if (strlen($sessionKey) != 24) {
			return [
                'error_code' => $this->IllegalAesKey
            ];
		}
		$aesKey=base64_decode($sessionKey);
        
		if (strlen($iv) != 24) {
			return [
                'error_code' => $this->IllegalIv
            ];
		}
		$aesIV = base64_decode($iv);

		$aesCipher = base64_decode($encryptedData);

		$result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

		$dataObj = json_decode($result, true);
		if (empty($dataObj)) {
            return [
                'error_code' => $this->IllegalBuffer
            ];
		}
		if ($dataObj['watermark']['appid'] != $this->appid){
			return [
                'error_code' => $this->IllegalBuffer
            ];
		}
        $data = $result;
        
		return [
            'error_code' => $this->OK,
            'info' => $dataObj
        ];
	}

	/**
	 * 获取微信小程序全局调用凭证
	 */
	private function getAccessToken()
	{
		$redis = Redis::connection();
		$redisKey = 'wx:access_token';
		$token = $redis->get($redisKey);
		if ($token) {
			return $token;
		}
		$api = 'https://api.weixin.qq.com/cgi-bin/token';
		$query = [
			'grant_type' => 'client_credential',
			'appid' => $this->appid,
			'secret' => $this->secret
		];

		$api = $api . '?' . http_build_query($query);
		$res = file_get_contents($api);
		$res = json_decode($res, true);
		if ($res['errcode'] != 0) {
			return false;
		}
		
		$token = $res['access_token'];
		$redis->setex($redisKey, 6500, $token);
		
		return $token;
	}

	/**
	 * 获取token返回的真实结果(未经处理)
	 */
	public function getAccountTokenRes()
	{
		$api = 'https://api.weixin.qq.com/cgi-bin/token';
		$query = [
			'grant_type' => 'client_credential',
			'appid' => $this->appid,
			'secret' => $this->secret
		];

		$api = $api . '?' . http_build_query($query);
		$res = file_get_contents($api);

		return $res;
	}

	/**
	 * 发送订阅消息
	 */
	public function sendMessage($tmplId, $openId, $data, $page = 'pages/index/index', $state = 'formal')
	{
		$token = $this->getAccessToken();
		$api = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $token;
		
		$postData = [
			'touser' => $openId,
			'template_id' => $tmplId,
			'page' => $page,
			'miniprogram_state' => $state,
			'data' => $data
		];
		$options = [
			'json' => $postData
		];

		$client = new Client();
		$res = $client->post($api, $options);

		return json_decode($res->getBody(), true);
	}

	/**
	 * 发放收益到账通知
	 *
	 * @param string $openId
	 * @param string $amount
	 * @param string $dateTime
	 */
	public function sendIncomeNotice($openId, $amount, $dateTime)
	{
		$tmplId = env('WEIXIN_INCOME_TEMPLATE_MSG_ID');
		$tmplData = [
            'thing1' => [
                'value' => '外卖返利金到账'
            ],
            'amount2' => [
                'value' => $amount
            ],
            'thing3' => [
                'value' => '你的收益已到账，可申请提现，点击查看'
            ],
            'time4' => [
                'value' => $dateTime
            ]
        ];
		
		$res = $this->sendMessage($tmplId, $openId, $tmplData);

		logger('发送收益到账通知: ' . json_encode($res) . ', openid: ' . $openId . ', amount: ' . $amount . ', date: ' . $dateTime);
	}

}
