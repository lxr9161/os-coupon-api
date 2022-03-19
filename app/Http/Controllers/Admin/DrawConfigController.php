<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\DrawRespository;
use Exception;
use Illuminate\Support\Facades\DB;

class DrawConfigController extends Controller
{
    /**
     * 获取抽奖配置
     */
    public function getConfig(DrawRespository $respository)
    {
        return $this->responseSuccess($respository->getDrawConfigFromDb());
    }

    /**
     * 保存配置
     */
    public function saveConfig(Request $request)
    {
        $data = $request->input();
        $config = $data['config'];
        $totalProbability = 0;
        foreach ($config as $item) {
            if ($item['probability'] > 100) {
                return $this->responseFail('概率设置不能超过100%');
            }
            $totalProbability += $item['probability'];
            if ($totalProbability > 100) {
                return $this->responseFail('概率总和设置不能超过100%');
            }
        }
        try {
            DB::transaction(function () use ($config) {
                foreach ($config as $item) {
                    $id = $item['id'];
                    unset($item['id']);
                    DB::table('draw_config')->where('id', $id)->update($item);
                }
                $respository = new DrawRespository;
                $respository->saveDrawConfigInCache($config);
            });
            return $this->responseSuccess('保存成功');

        } catch (Exception $e) {
            return $this->responseFail('保存失败，' . $e->getMessage());
        }  
    }

    /**
     * 获取抽奖结果列表
     *
     * @param Request $request
     */
    public function getDrawResult(Request $request)
    {
        
    }
}
