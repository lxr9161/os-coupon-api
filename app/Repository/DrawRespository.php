<?php
namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;


class DrawRespository
{
    
    /**
     * 从数据库获取所有奖励
     */
    public function getDrawConfigFromDb()
    {
        return DB::table('draw_config')->get();
    }


    /**
     * 获取抽奖信息
     */
    public function getDrawConfigSingle()
    {
        $data = $this->getDrawConfigFromCache();
        $returnData = [];
        foreach ($data as $item) {
            $returnData[] = [
                'id' => $item['id'], 
                'title' => $item['title'],
                'img_url' => $item['img_url']
            ];
        }

        return $returnData;
    }

    /**
     * 从缓存获取所有奖励
     */
    public function getDrawConfigFromCache($column = false)
    {
        $redis = Redis::connection();
        $configJson = $redis->get('draw_config');
        $config = json_decode($configJson, true);
        if ($column) {
            $config = array_column($config, null, 'id');
        }

        return $config;
    }

    /**
     * 保存设置
     */
    public function saveDrawConfigInCache($config)
    {
        $redis = Redis::connection();
        $configJson = json_encode($config, JSON_UNESCAPED_UNICODE);
        $key = 'draw_config';
        $redis->set($key, $configJson);
    }

    /**
     * 获取用户的抽奖历史记录
     *
     * @param int $userId
     */
    public function getDrawHistoryByUserId($userId)
    {
        $res = DB::table('draw_result')->select(['created_at', 'reward_title', 'reward_price', 'reward_img_url', 'status'])->where('user_id', $userId)->where('reward_type', '<>', 4)->orderBy('created_at', 'desc')->simplePaginate(20);

        foreach ($res as $item) {
            $item->created_at = date('Y-m-d H:i:s', $item->created_at);
        }

        return $res;
    }

    /**
     * 增加抽奖次数
     *
     * @param int $userId
     * @param int $count
     */
    public function incrDrawCount($userId, $count)
    {
        $isExist = DB::table('user_extend')->where('user_id', $userId)->first();
        if (empty($isExist)) {
            DB::table('user_extend')->insert([
                'user_id' => $userId,
                'draw_count' => $count
            ]);
        } else {
            DB::table('user_extend')->where('user_id', $userId)->update([
                'draw_count' => $isExist->draw_count + $count
            ]);
        }
    }

    /**
     * 扣除抽奖次数
     *
     * @param int $userId
     */
    public function decrDrawCount($userId, $num = 1)
    {
        DB::table('user_extend')->where('user_id', $userId)->decrement('draw_count', $num);
    }

    /**
     * 获取用户抽奖次数
     *
     * @param int $userId
     */
    public function getUserDrawCount($userId)
    {
        $drawCount = DB::table('user_extend')->where('user_id', $userId)->value('draw_count');
        if (empty($drawCount)) {
            return 0;
        }

        return $drawCount;
    }

    /**
     * 获取抽奖结果
     *
     * @param [type] $params
     * @param int $page
     */
    public function getDrawResultWithPage($params, $page = 1)
    {
        $where = '1=1';
        if (!empty($params['user_id'])) {
            $where .= ' and user_id=' . $params['user_id'];
        }
        if (!empty($query['start_time'])) {
            $where .= ' and created_at >= ' .strtotime($query['start_time']);
        }
        if (!empty($query['end_time'])) {
            $where .= ' and created_at <= ' .strtotime($query['end_time']);
        }
        $res = DB::table('draw_result')->whereRaw($where)->orderBy('created_at', 'desc')->paginate(10);

        foreach ($res as $item) {
            $item->created_at = date('Y-m-d H:i:s', $item->created_at);
        }

        return $res;
    }
}
