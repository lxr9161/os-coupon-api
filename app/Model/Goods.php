<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    
    protected $table = 'goods';

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
        'type',
        'need_coin',
    ];

    /**
     * 根据id获取信息
     *
     * @param int $id
     */
    public function getInfoById($id)
    {
        $data = $this->find($id);

        if (empty($data)) {
            return false;
        }

        return $data;
    }


    /**
     * 获取列表
     */
    public function getListWithPage($pageSize = 20, $whereRaw = '', $orderBy = 'id DESC')
    {
        $whereRaw = $whereRaw ? $whereRaw : '1=1';

        $data = $this->whereRaw($whereRaw)->orderByRaw($orderBy)->simplePaginate($pageSize);

        return $data;
    }
}
