<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Tool\WechatTool;

class DinnerNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dinner:notice {field}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用餐提醒';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $field = $this->argument('field');
        if (!in_array($field, ['clock1', 'clock2'])) {
            return;
        }
        $wechatTool = new WechatTool;
        $redis = Redis::connection();
        $tmplId = env('WEIXIN_COUPON_TEMPLATE_MSG_ID');
        $hour = date('G');
        $minute = intval(date('i'));
        $minuteTotal = $hour * 60 + $minute;
        $data = DB::table('user_notice')->whereIn($field, [$minuteTotal - 2, $minuteTotal - 1, $minuteTotal])->where('expire_time', '>=', time())->pluck('openid');
        //模版数据
        $tmplData = [
            'thing1' => [
                'value' => '美团 + 饿了么外卖大红包'
            ],
            'thing2' => [
                'value' => '点餐时间到了，快来领红包下单，还有返利哦'
            ],
            'thing3' => [
                'value' => '白贝外卖券'
            ],
            'thing4' => [
                'value' => '领红包下单获返利，还可参与抽奖，赢贝壳'
            ]
        ];
        $redisKey = 'dinner:notice:' . date('Ymd') . ':' . $field;
        foreach ($data as $openid) {
            if (empty($openid)) {
                continue;
            }
            if ($redis->sIsMember($redisKey, $openid)) {
                continue;
            }
            $res = $wechatTool->sendMessage($tmplId, $openid, $tmplData);
            if ($res['errcode'] == 0) {
                $redis->sAdd($redisKey, $openid);
                if ($redis->ttl($redisKey) == -1) {
                    $redis->expire($redisKey, 86400);
                }
            } else {
                logger('点餐提醒错误信息: ' . json_encode($res, JSON_UNESCAPED_UNICODE));
            }
        }
        
        return 0;
    }
}
