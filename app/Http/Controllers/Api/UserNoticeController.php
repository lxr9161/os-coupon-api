<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserNoticeController extends Controller
{
    
    /**
     * 设置点餐提醒
     */
    public function setOrderNotice(Request $request)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $postData = $request->input();
        
        $expire_time = time() + 86400 * 3;
        DB::table('user_notice')->updateOrInsert(
            [
                'user_id' => $userId
            ],
            [
                'clock1' => nl_clock_to_minute($postData['clock1_str']),
                'clock2' => nl_clock_to_minute($postData['clock2_str']),
                'clock1_str' => $postData['clock1_str'],
                'clock2_str' => $postData['clock2_str'],
                'expire_time' => $expire_time,
                'openid' => $user->openid
            ]
        );

        return $this->responseSuccess('设置成功');
    }

    /**
     * 获取点餐提现设置
     */
    public function getOrderNoticeSetting()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        
        $data = DB::table('user_notice')->where('user_id', $userId)->first();

        $returnData = [
            'clock1_str' => $data->clock1_str ?: '10:30',
            'clock2_str' => $data->clock2_str ?: '15:00',
        ];

        return $this->responseSuccess($returnData);
    }

}
