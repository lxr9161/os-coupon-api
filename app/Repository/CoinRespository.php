<?php
namespace App\Repository;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * 金币数据
 */
class CoinRespository
{
    
    /**
     * 增加金币数量-事务
     *
     * @param int $userId
     * @param int $coin
     */
    public function incrUserCoinIntransaction($userId, $coin, $source, $remark = '')
    {
        DB::beginTransaction();
        try {
            $this->incrUserCoinWithoutTranscation($userId, $coin, $source, $remark);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * 增加金币数量-未加事务
     *
     * @param int $userId
     * @param int $coin
     * @param int $source
     * @param string $remark
     */
    public function incrUserCoinWithoutTranscation($userId, $coin, $source, $remark = '')
    {
        $isExist = DB::table('user_coin')->where('user_id', $userId)->first();
        $time = time();
        if (!empty($isExist)) {
            DB::table('user_coin')->where('user_id', $userId)->update([
                'coin_total' => $isExist->coin_total + $coin,
                'history_coin_total' => $isExist->history_coin_total + $coin,
                'updated_at' => $time
            ]);
        } else {
            DB::table('user_coin')->insert([
                'user_id' => $userId,
                'coin_total' => $coin,
                'history_coin_total' => $coin,
                'created_at' => $time,
                'updated_at' => $time
            ]);
        }
        // 明细
        DB::table('user_coin_income')->insert([
            'user_id' => $userId,
            'coin_count' => $coin,
            'type' => 1,
            'created_at' => $time,
            'remark' => $remark,
            'source' => $source
        ]);
    }

    /**
     * 批量增加金币和明细-未加事物
     *
     * @param int    $userId
     * @param int    $totalCoin
     * @param int    $soucre
     * @param string $remark
     * @param array  $records
     */
    public function batchIncrUserCoinWithoutTranscation($userId, $totalCoin, $source, $remark, $records)
    {
        $isExist = DB::table('user_coin')->where('user_id', $userId)->first();
        $time = time();
        if (!empty($isExist)) {
            DB::table('user_coin')->where('user_id', $userId)->update([
                'coin_total' => $isExist->coin_total + $totalCoin,
                'history_coin_total' => $isExist->history_coin_total + $totalCoin,
                'updated_at' => $time
            ]);
        } else {
            DB::table('user_coin')->insert([
                'user_id' => $userId,
                'coin_total' => $totalCoin,
                'history_coin_total' => $totalCoin,
                'created_at' => $time,
                'updated_at' => $time
            ]);
        }
        $income_arr = [];
        foreach ($records as $item) {
            $income_arr[] = [
                'user_id' => $userId,
                'coin_count' => $item['coin'],
                'type' => 1,
                'created_at' => $time,
                'remark' => $remark,
                'source' => $source
            ];
        }
        // 明细
        DB::table('user_coin_income')->insert($income_arr);
    }

    /**
     * 扣除用户金币
     *
     * @param int $userId
     * @param int $coin
     * @param int $source
     * @param string $remark
     */
    public function decrUserCoinWithoutTranscation($userId, $coin, $source, $remark = '')
    {
        $isExist = DB::table('user_coin')->where('user_id', $userId)->first();
        $time = time();
        if (!empty($isExist)) {
            $decrCoin = $isExist->coin_total - $coin;
            if ($decrCoin < 0) {
                throw new Exception('coin not enough');
            }
            DB::table('user_coin')->where('user_id', $userId)->update([
                'coin_total' => $decrCoin,
                'updated_at' => $time
            ]);
            // 明细
            DB::table('user_coin_income')->insert([
                'user_id' => $userId,
                'coin_count' => $coin,
                'type' => 2,
                'created_at' => $time,
                'remark' => $remark,
                'source' => $source
            ]);
        } else {
            throw new Exception('not found coin record');
        }
    }

    /**
     * 获取兑换物品配置
     */
    public function getCoinRedeemConfig()
    {
        $config = [
        ];

        return $config;
    }

    /**
     * 获取兑换物品信息
     *
     * @param int $itemId
     */
    public function getCoinRedeemItemInfo($itemId)
    {
        $config = $this->getCoinRedeemConfig();

        return $config[$itemId];
    }

    /**
     * 获取今日获得的贝壳数
     *
     * @param int $userId
     */
    public function getTodayCoin($userId)
    {

        $startTime = strtotime('Today');
        $endTime = strtotime('+1 day');
        $where = 'user_id=' . $userId . ' and created_at >' . $startTime . ' and created_at < ' . $endTime . ' and type=1';
        return DB::table('user_coin_income')->whereRaw($where)->sum('coin_count');
    }
}
