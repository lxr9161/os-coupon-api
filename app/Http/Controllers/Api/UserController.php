<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\IncomeRespository;
use App\Model\User;
use App\Tool\WechatTool;
use App\Tool\CommonTool;

class UserController extends Controller
{

    /**
     * 设置微信用户信息
     */
    public function setWechatUserInfo(Request $request, User $model)
    {
        // 获取用户账户信息
        $user = auth('api')->user();
        $sessionKey = $user->session_key;
        $userData = $request->input();
        $encryptedData = $userData['encryptedData'];
        $wxTool = new WechatTool;
        $data = $wxTool->decryptData($sessionKey, $encryptedData, $userData['iv']);
        if ($data['error_code'] != 0) {
            return $this->responseFail('操作失败，请重新进入小程序后重试');
        }
        // 判断是否已经入库
        $result = $model->updateOrCreate(
            [
                'openid' => $user->openid
            ],
            [
                'nickname' => $data['info']['nickName'],
                'gender' => $data['info']['gender'],
                'city' => $data['info']['city'],
                'province' => $data['info']['province'],
                'country' => $data['info']['country'],
                'avatar' => $data['info']['avatarUrl']
            ]
        );

        return $this->responseSuccess('操作成功');
    }



    /**
     * 获取个人信息
     */
    public function getUserInfo()
    {
        $user = auth('api')->user();

        $userId = $user->id;
        
        return $this->responseSuccess([
            'user' => $user,
        ]);
    }

    /**
     * 获取上传token
     */
    public function getUploadToken()
    {
        $tool = new CommonTool;
        $token = $tool->genQiunuUploadToken();

        return $this->responseSuccess([
            'token' => $token
        ]);
    }
}