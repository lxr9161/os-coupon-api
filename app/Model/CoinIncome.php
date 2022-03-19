<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CoinIncome extends Model
{
    
    protected $table = 'user_coin_income';

    public $timestamps = false;

    protected $dateFormat = 'U';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $fillable = [
        ''
    ];

    /**
     * 金币来源
     *
     * @var array
     */
    protected $sourceMap = [
        1 => '抽奖',
        2 => '签到'
    ];

    /**
     * 获取用户金币明细
     *
     * @param int $userId
     */
    public function getUserCoinDetail($userId)
    {
        $data = $this->select(['coin_count', 'type', 'source', 'created_at'])->where('user_id', $userId)->orderBy('created_at', 'DESC')->simplePaginate(20);
        foreach ($data as $item) {
            $item->source_desc = $this->sourceMap[$item->source];
        }
        return $data;
    }
}
