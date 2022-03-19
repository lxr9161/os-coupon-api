<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * 登录
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $requestData = $request->only(['login_name', 'password', 'remember']);

        $ttl = config('jwt.ttl');
        if (isset($requestData['remember'])) {
            if ($requestData['remember'] == 1) {
                $ttl = 1440 * 7;  // 记住密码 token有效期设为7天 ，默认1天
            }
            unset($requestData['remember']);
        }
        
        if (! $token = auth('backend')->setTTL($ttl)->attempt($requestData)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * 获取授权用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('backend')->user());
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        auth('backend')->logout();

        return ['status' => 'success', 'info' => '退出登录'];
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'expires_in' => auth('backend')->factory()->getTTL() / 1440
        ]);
    }
}
