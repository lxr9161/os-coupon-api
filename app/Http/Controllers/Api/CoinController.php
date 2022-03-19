<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Coin;
use App\Model\CoinIncome;
use Illuminate\Http\Request;
use App\Repository\CoinRespository;
use Illuminate\Support\Facades\DB;

class CoinController extends Controller
{

    /**
     * 获取用户金币数
     *
     * @param Coin $model
     */
    public function getUserCoin(CoinRespository $coinRespository, Coin $model)
    {
        $user = auth('api')->user();
        $userId = $user->id;

        
        $data = $model->getUserCoin($userId);
        $historyCoinTotal = $data->history_coin_total ?: 0;
        $coinTotal = $data->coin_total ?: 0;
        $returnData = [
            'history_coin_total' => $historyCoinTotal,
            'coin_total' => $coinTotal,
            'cost_coin_total' => $historyCoinTotal - $coinTotal,
            'today_coin' => $coinRespository->getTodayCoin($userId),

        ];

        return $this->responseSuccess($returnData);
    }

    /**
     * 获取用户金币明细
     *
     * @param CoinIncome $model
     */
    public function getUserCoinDetail(CoinIncome $model)
    {
        $user = auth('api')->user();
        $userId = $user->id;

        $data = $model->getUserCoinDetail($userId);

        return $this->responseSuccess($data);
    }

    /**
     * 获取可兑换物品
     */
    public function getRedeemConfig(CoinRespository $respository)
    {
        
        //TODO 获取兑换物品

        return $this->responseSuccess([]);
    }

    /**
     * 兑换
     */
    public function redeemHandle(Request $request, CoinRespository $coinRespository)
    {
        $user = auth('api')->user();

        $userId = $user->id;
        $itemSn = $request->input('item_sn');
        
        // 获取兑换物品信息
        $itemInfo = $coinRespository->getCoinRedeemItemInfo($itemSn);
        if (empty($itemInfo)) {
            return $this->responseFail('兑换物品不存在');
        }
        // 获取用户可用金币数
        $coinModel = new Coin;
        $coinCount = $coinModel->getUserCurrentCoin($userId);
        if ($coinCount < $itemInfo['condition']) {
            return $this->responseFail('贝壳不足');
        }
        
        try {
            DB::beginTransaction();
            //TODO 兑换物品
            //扣除金币
            $coinRespository->decrUserCoinWithoutTranscation($userId, $itemInfo['condition'], 4, '兑换现金:' . $itemInfo['title']);
            DB::commit();
            return $this->responseSuccess('兑换成功');
        } catch (\Throwable $e) {
            logger('物品兑换失败:' . $e->getMessage());
            DB::rollBack();
            return $this->responseFail('兑换失败');
        }
    }
    
}
