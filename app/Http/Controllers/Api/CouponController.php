<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Coupon;

class CouponController extends Controller
{

    /**
     * 优惠券列表
     *
     * @param Request $request
     * @param Coupon $couponModel
     */
    public function getCouponList(Coupon $couponModel)
    {        

        $couponList = $couponModel->getUseCoupon();
        
        $returnData = [
            'data' => [],
        ];
        // 根据优惠券类型生成获取红包链接
        foreach ($couponList as $item) {
            if ($item->jump_get_page == 1) {
                $link = '';
            } else {
                $link = [
                    'link' => $item['link'],
                    'appid' => $item['appid'],
                ];
            }
            $tmp = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'cps_link' => $link,
                'icon' => $item->icon,
                'cover' => $item->cover,
                'share_btn' => $item->share_btn,
                'jump_get_page' => $item->jump_get_page,
                'sub_title' => $item->sub_title
            ];
            $returnData['data'][] = $tmp;
        }
        

        return $this->responseSuccess($returnData);
    }

    /**
     * 获取优惠券信息
     *
     * @param Request $request
     */
    public function getCouponInfoForShare(Request $request, Coupon $couponModel, User $userModel)
    {
        $data = $request->query();
        if (empty($data['coupon'])) {
            $config = nl_get_setting(1);
            $couponId = $config['default_coupon'];
        } else {
            $couponId = (int) $data['coupon'];
        }

        if (empty($couponId)) {
            return $this->responseFail('红包不存在');
        }

        $couponInfo = $couponModel->getCouponInfoById($couponId);
        if (empty($couponInfo)) {
            return $this->responseFail('红包不存在');
        }
        
        $link = [
            'link' => $couponInfo['link'],
            'appid' => $couponInfo['appid'],
        ];
        
        $returnData = [
            'id' => $couponInfo->id,
            'cover' => $couponInfo->cover,
            'name' => $couponInfo->name,
            'price' => $couponInfo->price,
            'cps_link' => $link,
            'icon' => $couponInfo->icon,
            'sub_title' => $couponInfo->sub_title
        ];

        return $this->responseSuccess($returnData);
    }

    /**
     * 获取首页展示的红包
     *
     * @param Request $request
     * @param Coupon $couponModel
     * @param User $userModel
     */
    public function getIndexCoupons(Request $request, Coupon $couponModel)
    {
        
        $couponList = $couponModel->getIndexShowCoupon();
        
        $returnData = [
            'data' => [],
        ];
        // 根据优惠券类型生成获取红包链接
        foreach ($couponList as $item) {
            if ($item->jump_get_page == 1) {
                $link = '';
            } else {
                $link = [
                    'link' => $item['link'],
                    'appid' => $item['appid'],
                ];
            }            
            $tmp = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'cps_link' => $link,
                'icon' => $item->icon,
                'cover' => $item->cover,
                'share_btn' => $item->share_btn,
                'jump_get_page' => $item->jump_get_page,
                'sub_title' => $item->sub_title
            ];
            $returnData['data'][] = $tmp;
        }

        return $this->responseSuccess($returnData);
    }

    /**
     * 获取单个优惠券信息
     *
     * @param Request $request
     * @param Coupon $couponModel
     * @param User $userModel
     */
    public function getCouponDetail(Request $request, Coupon $couponModel)
    {
        $data = $request->query();
        if (empty($data['coupon'])) {
            $config = nl_get_setting(1);
            $couponId = $config['default_coupon'];
        } else {
            $couponId = (int) $data['coupon'];
        }
        if (empty($couponId)) {
            return $this->responseFail('红包不存在');
        }
        
        $couponInfo = $couponModel->getCouponInfoById($couponId);
        if (empty($couponInfo)) {
            return $this->responseFail('红包不存在');
        }
    
        $link = [
            'link' => $couponInfo['link'],
            'appid' => $couponInfo['appid'],
        ];
    
        $returnData = [
            'id' => $couponInfo->id,
            'cover' => $couponInfo->cover,
            'name' => $couponInfo->name,
            'price' => $couponInfo->price,
            'cps_link' => $link,
            'icon' => $couponInfo->icon,
            'sub_title' => $couponInfo->sub_title
        ];
        $returnData['rule'] = explode(PHP_EOL, $couponInfo->share_text);

        return $this->responseSuccess($returnData);
    }
}
