<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    
    protected $table = 'ad';

    protected $dateFormat = 'U';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'img_url', 'link', 'status', 'position', 'sort', 'type', 'appid', 'target', 'coupon_id'
    ];

    public function coupon()
    {
        return $this->belongsTo('App\Model\Coupon', 'coupon_id');
    }


    /**
     * 获取广告位置广告
     *
     * @param string $position
     * @param array $field
     */
    public function getAdByPosition($position, $field = ['appid', 'img_url', 'link', 'appid', 'target', 'type', 'coupon_id'])
    {
        $model = $this->where('position', $position);
        if (in_array('coupon_id', $field)) {
            $model = $model->with('coupon');
        }

        return $model->where('status', 1)->select(...$field)->orderBy('sort', 'DESC')->get();
    }

    /**
     * 获取广告列表
     *
     * @param [type] $where
     * @param [type] $field
     */
    public function getAllAd($where, $field = ['*']) {
        
        return $this->where($where)->select($field)->orderBy('sort', 'DESC')->get();
    }
}
