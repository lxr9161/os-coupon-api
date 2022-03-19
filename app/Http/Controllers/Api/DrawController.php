<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\CoinRespository;
use App\Repository\DrawRespository;
use App\Repository\IncomeRespository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrawController extends Controller
{
    
    /**
     * 获取抽奖信息
     */
    public function getDrawInfo(DrawRespository $respository)
    {
        // 获取抽奖开关
        $baseConfig = nl_get_setting(3);
        if ($baseConfig['draw_status'] != 1) {
            return $this->responseSuccess([
                'reward' => [],
                'status' => 0
            ]);
        } 
        
        $config = $respository->getDrawConfigSingle();
        $returnData = [
            'reward' => $config,
            'status' => 1
        ];

        return $this->responseSuccess($returnData);
    }

    /**
     * 获取用户当前抽奖次数
     *
     * @param DrawRespository $respository
     */
    public function getUserDrawCount(DrawRespository $respository)
    {
        // 获取用户账户信息
        $user = auth('api')->user();
        $userId = $user->id;

        // 抽奖次数校验
        $drawCount = $respository->getUserDrawCount($userId);

        return $this->responseSuccess(['draw_count' => $drawCount]);
    }

    /**
     * 开始抽奖
     */
    public function startDraw(DrawRespository $respository)
    {
        // 获取用户账户信息
        $user = auth('api')->user();
        $userId = $user->id;
        
        // 抽奖次数校验
        $drawCount = $respository->getUserDrawCount($userId);
        if (empty($drawCount)) {
            return $this->responseSuccess('抽奖次数已用完');
        }

        //奖品配置
        $config = $respository->getDrawConfigFromCache(true);
        $totalProbability = 0;
        foreach ($config as $item) {
            $totalProbability += $item['probability'];
        }
        $rewardId = 0;
        foreach ($config as $item) {
            if (empty($item['probability'])) {
                continue;
            }
            if ($this->isWin($item['probability'], $totalProbability)) {
                $rewardId = $item['id'];
                break;
            }
        }
        if (empty($rewardId)) {
            $rewardId = 1;
        }
        $rewardInfo = $config[$rewardId];
        try {
            DB::transaction(function() use ($respository, $userId, $rewardInfo) {
                // 扣除抽奖次数
                $respository->decrDrawCount($userId);
                // 中奖记录
                $resultId = DB::table('draw_result')->insertGetId([
                    'user_id' => $userId,
                    'created_at' => time(),
                    'reward_id' => $rewardInfo['id'],
                    'reward_title' => $rewardInfo['title'],
                    'reward_type' => $rewardInfo['type'],
                    'reward_price' => $rewardInfo['reward_price'],
                    'reward_img_url' => $rewardInfo['img_url']
                ]);

                // 发放奖励
                $rewardPrice = $rewardInfo['reward_price'];
                if ($rewardPrice > 0) {
                    if ($rewardInfo['type'] == 1) {
                        $coinRespository = new CoinRespository;
                        $coinRespository->incrUserCoinWithoutTranscation($userId, $rewardPrice, 1, '抽奖');
                        // 更新记录
                        DB::table('draw_result')->where('id', $resultId)->update(['status' => 1, 'send_time' => time()]);
                    }
                }
            });
            return $this->responseSuccess([
                'reward' => $rewardId,
                'reward_info' => [
                    'title' => $rewardInfo['title'],
                    'img_url' => $rewardInfo['img_url'],
                    // 是否中奖了，type=4时为未中奖
                    'is_win' => $rewardInfo['type'] == 4 ? 0 : 1
                ],
            ]);
        } catch(\Throwable $e) {
            logger('抽奖失败: ' . $e->getMessage());
            return $this->responseFail('抽奖失败');
        }
    }

    /**
     * 是否中奖
     *
     * @param int $pr  概率, 支持一位小数
     * @param int $max 最大值
     * @return bool
     */
    private function isWin($pr, int $max = 100)
    {
        $max = $max * 10;
        $pr  = intval($pr * 10);
        if ($pr < 1) {
            return false;
        }

        $rand = mt_rand(1, $max);

        return $rand <= $pr;
    }

    /**
     * 获取抽奖历史
     */
    public function getDrawHisotry(DrawRespository $respository)
    {
        // 获取用户账户信息
        $user = auth('api')->user();
        $userId = $user->id;

        $history = $respository->getDrawHistoryByUserId($userId);

        return $this->responseSuccess($history);
    }

    /**
     * 连抽
     */
    public function drawCombo(DrawRespository $respository,Request $request)
    {
        $combo = $request->input('combo');
        if (!in_array($combo, [3, 5, 10])) {
            return $this->responseFail('非法操作');
        }
        // 获取用户账户信息
        $user = auth('api')->user();
        $userId = $user->id;
        // 抽奖次数校验
        $drawCount = $respository->getUserDrawCount($userId);
        if (empty($drawCount)) {
            return $this->responseSuccess('抽奖次数已用完，领红包点外卖可以获得抽奖次数哦');
        }
        if ($drawCount < $combo) {
            return $this->responseSuccess('抽奖次数不足，领红包点外卖可以获得抽奖次数哦');
        }
        //奖品配置
        $config = $respository->getDrawConfigFromCache(true);
        $totalProbability = 0;
        foreach ($config as $item) {
            $totalProbability += $item['probability'];
        }
        $rewardIdArr = [];
        for ($i = 0; $i < $combo; $i++) {
            $defaultRewardId = 1;
            foreach ($config as $item) {
                if (empty($item['probability'])) {
                    continue;
                }
                if ($this->isWin($item['probability'], $totalProbability)) {
                    $defaultRewardId = $item['id'];
                    break;
                }
            }
            $rewardIdArr[] = $defaultRewardId;
        }
        
        if (empty($rewardIdArr)) {
            return $this->responseSuccess([
                'message' => '未中奖',
                'reward' => null
            ]);
        }
        
        try {
            DB::transaction(function() use ($respository, $userId, $config, $rewardIdArr, $combo) {
                $insertData = [];
                $coinRecord = [];
                $coinTotal = 0;
                $incomeRecord = [];
                $incomeTatal = 0;
                $rewardList = [];
                foreach ($rewardIdArr as $rewardId) {
                    $rewardInfo = $config[$rewardId];
                    $rewardList[] = [
                        'id' => $rewardId,
                        'title' => $rewardInfo['title'],
                        'img_url' => $rewardInfo['img_url'],
                        // 是否中奖了，type=4时为未中奖
                        'is_win' => $rewardInfo['type'] == 4 ? 0 : 1
                    ];
                    $tmp = [
                        'user_id' => $userId,
                        'created_at' => time(),
                        'reward_id' => $rewardInfo['id'],
                        'reward_title' => $rewardInfo['title'],
                        'reward_type' => $rewardInfo['type'],
                        'reward_price' => $rewardInfo['reward_price'],
                        'reward_img_url' => $rewardInfo['img_url'],
                        'status' => 0,
                        'send_time' => 0
                    ];
                    $rewardPrice = $rewardInfo['reward_price'];
                    if ($rewardPrice > 0) {
                        if ($rewardInfo['type'] == 1) {
                            $tmp['status'] = 1;
                            $tmp['send_time'] = time();
                            $coinRecord[] = [
                                'coin' => $rewardPrice
                            ];
                            $coinTotal += $rewardPrice;
                        } elseif ($rewardInfo['type'] == 3) {
                            $tmp['status'] = 1;
                            $tmp['send_time'] = time();
                            $incomeRecord[] = [
                                'amount' => $rewardPrice
                            ];
                            $incomeTatal += $rewardPrice;
                        }
                    }
                    $insertData[] = $tmp;
                }
                // 扣除抽奖次数
                $respository->decrDrawCount($userId, $combo);
                DB::table('draw_result')->insert($insertData);
                if (!empty($coinRecord)) {
                    $coinRespository = new CoinRespository;
                    $coinRespository->batchIncrUserCoinWithoutTranscation($userId, $coinTotal, 1, '抽奖', $coinRecord);
                }
            });
            $rewardList = [];
            foreach ($rewardIdArr as $rewardId) {
                $rewardInfo = $config[$rewardId];
                $rewardList[] = [
                    'id' => $rewardId,
                    'title' => $rewardInfo['title'],
                    'is_win' => $rewardInfo['type'] == 4 ? 0 : 1
                ];
            } 
            return $this->responseSuccess([
                'reward_list' => $rewardList
            ]);
        } catch(\Throwable $e) {
            logger('抽奖失败: ' . $e->getMessage());
            return $this->responseFail('抽奖失败');
        }
    }

}
