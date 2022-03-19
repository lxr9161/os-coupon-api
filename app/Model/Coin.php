<?php

namespace App\Model;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coin extends Model
{
    
    protected $table = 'user_coin';

    protected $dateFormat = 'U';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'user_id',
        'coin_total',
        'history_coin_total',
    ];

    /**
     * 获取用户金币信息
     *
     * @param int $userId
     */
    public function getUserCoin($userId)
    {
        return $this->where('user_id', $userId)->first();
    }

    /**
     * 获取用户当前可用金币数
     *
     * @param int $userId
     */
    public function getUserCurrentCoin($userId)
    {
        $currentTotal = $this->where('user_id', $userId)->value('coin_total');

        return $currentTotal ?: 0;
    }
}
