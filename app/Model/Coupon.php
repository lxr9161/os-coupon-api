<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    
    protected $table = 'coupon';

    protected $dateFormat = 'U';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'name',
        'updated_at',
        'created_at',
        'status',
        'icon',
        'cover',
        'price',
        'extra',
        'sort',
        'activity_id',
        'miniprogram_appid', 
        'share_btn',
        'index_show',
        'jump_get_page',
        'link',
        'appid',
        'share_text',
        'sub_title'
    ];

    /**
     * 根据id获取用户信息
     *
     * @param int $id
     */
    public function getCouponInfoById($id)
    {
        $data = $this->find($id);

        if (empty($data)) {
            return false;
        }

        return $data;
    }

    /**
     * 获取可用的优惠券
     */
    public function getUseCoupon()
    {
        $data = $this->getCouponListWithPage(20, 'status=1', 'sort desc');

        return $data;
    }

    /**
     * 获取优惠券列表
     */
    public function getCouponListWithPage($pageSize = 20, $whereRaw = '', $orderBy = 'id DESC')
    {
        $whereRaw = $whereRaw ? $whereRaw : '1=1';

        $data = $this->whereRaw($whereRaw)->orderByRaw($orderBy)->simplePaginate($pageSize);

        return $data;
    }

    /**
     * 获取首页展示的优惠券
     */
    public function getIndexShowCoupon()
    {
        $data = $this->select(['id', 'name', 'icon', 'cover', 'price', 'share_btn', 'jump_get_page', 'link', 'appid', 'sub_title'])->where('status', 1)->where('index_show', 1)->orderBy('sort', 'desc')->get();

        return $data;
    }
}
