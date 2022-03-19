<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Ad;

class AdController extends Controller
{

    protected $model;

    public function __construct(Ad $model)
    {
        $this->model = $model;
    }
    
    /**
     * 获取广告列表
     *
     * @param [type] $postion
     */
    public function getPostionAd($postion)
    {

        $data = $this->model->getAdByPosition($postion);
        
        $returnData = [];
        foreach ($data as $item) {
            $tmp = [
                'appid' => $item->appid ?: '',
                'img_url' => $item->img_url,
                'link' => $item->link,
                'type' => $item->type,
                'target' => $item->target,
                'coupon_id' => $item->coupon_id,
            ];
            if (!empty($item->coupon)) {
                $tmp['coupon'] = [
                    'cps_link' => [
                        'link' => $item->coupon->link,
                        'appid' => $item->coupon->appid,
                    ]
                ];
            }
            $returnData[] = $tmp;
        }

        return $this->responseSuccess($returnData);
    }
}
