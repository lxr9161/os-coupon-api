# 优惠券小程序-服务端
## 1.介绍
一个优惠券领取、分享小程序。优惠券类型包括饿了么外卖红包、美团外卖红包、滴滴打车红包等。还有抽奖、提现功能。服务端用PHP开发，使用laravel框架。

## 2.服务器环境
- nginx
- php7.4(保证PHP版本在7.2.5以上就可以)
- mysql5.7
- redis6.2.4

## 3.项目部署
### 3.1 数据库
> 数据库 Character Set: utf8mb4, Collation: utf8mb4_unicode_ci

数据库文件时根目录下`coupon.sql`，直接导入即可。然后补全`.env`文件里的数据库配置

### 3.2 生成.env 配置文件
- 执行命令`cp .env.example .env` 生成.env文件
- 执行命令`php artisan key:generate` 生成APP_KEY
- 执行命令`php artisan jwt:secret` 生成JWT_SECRET

#### 3.2.1 配置项说明
> 注意：laravel自带的配置不做说明
- `JWT_SECRET` jwt密钥
- `JWT_TTL` jwt有效分钟数
- `WEIXIN_APPID` 小程序appid
- `WEIXIN_APPSECRET` 小程序密钥
- `WEIXIN_COUPON_TEMPLATE_MSG_ID` 优惠券提醒模版消息id 对应类目 信息查询, 对应模版编号: 8570
- `WEIXIN_INCOME_TEMPLATE_MSG_ID` 收益到帐通知模版消息id 对应类目 报价/比价, 对应模版编号: 653
- `QINIU_ACCESS_KEY` 七牛access_key
- `QINIU_SECRET_KEY` 七牛secret_key
- `QINIU_BUCKET` 七牛空间bucket，上传图片会存储到这个空间

### 3.3 初始化管理员账号
执行命令`php artisan init:admin {login_name} {password}`
> 例如: php artisan init:admin dalong 12345， login_name是管理员登录名，password是登录密码，可自行替换
### 3.4 初始化系统配置
执行命令`php artisan init:setting`

### 3.5相关定时脚本
> 注意: 以下脚本目录需替换为真实服务器上对应目录，定时脚本时间可自行调整
#### 3.5.1 点餐提醒脚本
```sh
# 每分钟执行一次
* * * * * /usr/local/php7/bin/php /data/wwwroot/open_coupon_api/artisan dinner:notice clock1
* * * * * /usr/local/php7/bin/php /data/wwwroot/open_coupon_api/artisan dinner:notice clock2
```
## 4.关联项目
- [小程序](https://github.com/lxr9161/os-coupon-miniprogram)
- [管理后台](https://github.com/lxr9161/os-coupon-admin)

-----
开源版可能存在一些问题，欢迎吐槽。也欢迎大家做提出一些建议或意见。

技术交流可以扫码添加我个人的微信，也可以扫码进微信群
- 我个人的微信
<div>
  <img src="https://user-images.githubusercontent.com/13703050/155838535-741b3ac8-1e6e-48d2-936c-036eec90bb3b.JPG" width="250"/>
  <img src="https://user-images.githubusercontent.com/13703050/155838542-d63fefb9-7f1a-4e46-a47c-745cbff62c36.JPG" width="250"/>
</div>

- 微信群
<div>
   <img src="https://user-images.githubusercontent.com/13703050/159154826-834f55e3-c886-4b37-812f-5ae0ce249f57.JPG" width="250"/>
</div>

