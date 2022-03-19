/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50727
 Source Host           : localhost:3306
 Source Schema         : open_coupon

 Target Server Type    : MySQL
 Target Server Version : 50727
 File Encoding         : 65001

 Date: 28/02/2022 01:58:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for nl_ad
-- ----------------------------
DROP TABLE IF EXISTS `nl_ad`;
CREATE TABLE `nl_ad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `img_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片地址',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题描述',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转地址',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序(倒叙)',
  `position` char(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '广告位置',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态 0开启 1关闭',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '跳转类型 1小程序 2h5',
  `appid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '小程序uuid',
  `target` tinyint(1) NOT NULL DEFAULT '0' COMMENT '小程序跳转 0内部跳转 1跳转到其他小程序',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for nl_admin
-- ----------------------------
DROP TABLE IF EXISTS `nl_admin`;
CREATE TABLE `nl_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `login_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员登录名',
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录密码',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1启用 0禁用',
  PRIMARY KEY (`id`),
  KEY `idx_login` (`login_name`,`password`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for nl_answer
-- ----------------------------
DROP TABLE IF EXISTS `nl_answer`;
CREATE TABLE `nl_answer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL DEFAULT '0' COMMENT '问题id',
  `content` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '答案内容',
  `option_key` char(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '答案选项',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `select_count` int(11) NOT NULL DEFAULT '0' COMMENT '选择次数',
  `topic_uuid` char(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '所属题目',
  `topic_id` int(11) NOT NULL DEFAULT '0' COMMENT '题目id',
  `jump_question` int(11) NOT NULL DEFAULT '0' COMMENT '跳转到哪个问题',
  `sort` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `idx_question` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='问题答案';

-- ----------------------------
-- Table structure for nl_coupon
-- ----------------------------
DROP TABLE IF EXISTS `nl_coupon`;
CREATE TABLE `nl_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `code` char(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '优惠券唯一code',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1启用 0禁用',
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'icon',
  `cover` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面地址',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '红包价值',
  `extra` text COLLATE utf8mb4_unicode_ci COMMENT '额外信息',
  `sort` int(10) NOT NULL COMMENT '排序(倒叙)',
  `platform` tinyint(2) NOT NULL DEFAULT '0' COMMENT '优惠券平台',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '优惠券类型',
  `share_btn` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否显示分享按钮 0不显示 1显示',
  `index_show` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否首页展示 1是0否',
  `jump_get_page` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否跳转到领取页面 0否 1是',
  `appid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转小程序appid',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '小程序地址',
  `share_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分享文案',
  `sub_title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '子标题',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_code` (`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='优惠券表';

-- ----------------------------
-- Table structure for nl_draw_config
-- ----------------------------
DROP TABLE IF EXISTS `nl_draw_config`;
CREATE TABLE `nl_draw_config` (
  `id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `img_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '奖品图片',
  `level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '奖品等级',
  `probability` smallint(5) NOT NULL DEFAULT '0' COMMENT '抽奖概率',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `reward_config` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '奖品配置',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '奖品类型 1贝壳  2线下道具 3现金红包',
  `reward_price` int(11) NOT NULL DEFAULT '0' COMMENT '奖品价值(贝壳)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='抽奖配置表';

-- ----------------------------
-- Table structure for nl_draw_result
-- ----------------------------
DROP TABLE IF EXISTS `nl_draw_result`;
CREATE TABLE `nl_draw_result` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `reward_id` int(11) NOT NULL DEFAULT '0' COMMENT '奖品id',
  `reward_title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '奖品名称',
  `reward_type` int(11) NOT NULL DEFAULT '0' COMMENT '奖品类型 1金币 2线下道具',
  `reward_price` int(11) NOT NULL DEFAULT '0' COMMENT '奖品价值(贝壳)',
  `reward_img_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '奖品图片',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发放状态 1已发放 0未发放',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '奖品发放时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_create` (`user_id`,`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='抽奖结果表';

-- ----------------------------
-- Table structure for nl_food
-- ----------------------------
DROP TABLE IF EXISTS `nl_food`;
CREATE TABLE `nl_food` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜品名称',
  `type` tinyint(11) NOT NULL DEFAULT '0' COMMENT '菜品类型 1早餐 2午餐 3晚餐 4宵夜',
  `province` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜品流行省份',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推荐菜品表';

-- ----------------------------
-- Table structure for nl_order_cps
-- ----------------------------
DROP TABLE IF EXISTS `nl_order_cps`;
CREATE TABLE `nl_order_cps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单id',
  `platform` tinyint(1) NOT NULL DEFAULT '0' COMMENT '平台',
  `pay_price` int(11) NOT NULL DEFAULT '0' COMMENT '订单价格',
  `profit` int(11) NOT NULL DEFAULT '0' COMMENT '佣金',
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺名',
  `order_time` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `goods_title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `order_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单类型',
  `coupon_type` int(10) NOT NULL DEFAULT '0' COMMENT '优惠券类型',
  PRIMARY KEY (`id`),
  KEY `idx_order_time` (`order_time`) USING BTREE,
  KEY `idx_updated_at` (`updated_at`) USING BTREE,
  KEY `idx_order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单表';

-- ----------------------------
-- Table structure for nl_order_jutuike
-- ----------------------------
DROP TABLE IF EXISTS `nl_order_jutuike`;
CREATE TABLE `nl_order_jutuike` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `order_time` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` int(11) NOT NULL COMMENT '状态',
  `order_detail` text COLLATE utf8mb4_unicode_ci COMMENT '订单详细内容',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='聚推客cps订单';

-- ----------------------------
-- Table structure for nl_order_meituan
-- ----------------------------
DROP TABLE IF EXISTS `nl_order_meituan`;
CREATE TABLE `nl_order_meituan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `order_time` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` int(11) NOT NULL COMMENT '状态',
  `order_detail` text COLLATE utf8mb4_unicode_ci COMMENT '订单详细内容',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`) USING BTREE,
  KEY `idx_user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='美团cps订单';

-- ----------------------------
-- Table structure for nl_order_tbk
-- ----------------------------
DROP TABLE IF EXISTS `nl_order_tbk`;
CREATE TABLE `nl_order_tbk` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `order_time` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` int(11) NOT NULL COMMENT '状态',
  `order_detail` text COLLATE utf8mb4_unicode_ci COMMENT '订单详细内容',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='淘宝客cps订单';

-- ----------------------------
-- Table structure for nl_setting
-- ----------------------------
DROP TABLE IF EXISTS `nl_setting`;
CREATE TABLE `nl_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `config` text COLLATE utf8mb4_unicode_ci COMMENT '配置内容',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统设置表';

-- ----------------------------
-- Table structure for nl_user
-- ----------------------------
DROP TABLE IF EXISTS `nl_user`;
CREATE TABLE `nl_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `nickname` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0位置 1男 2女',
  `city` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '城市',
  `province` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '省份',
  `country` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '国家',
  `openid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `mobile` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `session_key` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '绘画密钥',
  `wx_appid` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信小程序appid',
  `is_admin` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否有管理员权限',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_openid` (`openid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

-- ----------------------------
-- Table structure for nl_user_coin
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_coin`;
CREATE TABLE `nl_user_coin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `coin_total` int(11) NOT NULL DEFAULT '0' COMMENT '当前金币(贝壳)总数',
  `history_coin_total` int(11) NOT NULL DEFAULT '0' COMMENT '历史获得总数',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户金币(贝壳)表';

-- ----------------------------
-- Table structure for nl_user_coin_income
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_coin_income`;
CREATE TABLE `nl_user_coin_income` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `coin_count` int(11) NOT NULL DEFAULT '0' COMMENT '金币数量',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型 1收入 2支出',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `remark` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '备注',
  `source` tinyint(3) NOT NULL DEFAULT '0' COMMENT '获取/消费途径',
  PRIMARY KEY (`id`),
  KEY `idx_user_create` (`user_id`,`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户金币(贝壳)出入明细';

-- ----------------------------
-- Table structure for nl_user_extend
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_extend`;
CREATE TABLE `nl_user_extend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `draw_count` int(11) NOT NULL DEFAULT '0' COMMENT '抽奖次数',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户业务参数';

-- ----------------------------
-- Table structure for nl_user_income_detail
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_income_detail`;
CREATE TABLE `nl_user_income_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '收益金额',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型',
  `order_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `split_per` int(11) NOT NULL DEFAULT '0' COMMENT '分成比例',
  `order_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收益所属订单类型',
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`) USING BTREE,
  KEY `idx_created_at` (`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户收益明细表';

-- ----------------------------
-- Table structure for nl_user_income_total
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_income_total`;
CREATE TABLE `nl_user_income_total` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `total_income` int(11) NOT NULL DEFAULT '0' COMMENT '总收益',
  `withdrawals_total` int(11) NOT NULL DEFAULT '0' COMMENT '提现总额',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户总收益表';

-- ----------------------------
-- Table structure for nl_user_income_withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_income_withdrawals`;
CREATE TABLE `nl_user_income_withdrawals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '提现金额',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `success_time` int(11) NOT NULL DEFAULT '0' COMMENT '提现成功时间',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '提现状态 0提现中 1提现成功 2拒绝提现请求',
  `way` tinyint(3) NOT NULL DEFAULT '0' COMMENT '提现方式 1支付宝 2微信',
  `refuse_time` int(11) NOT NULL DEFAULT '0' COMMENT '拒绝时间',
  `refuse_reason` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '拒绝原因',
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`) USING BTREE,
  KEY `idx_created_at` (`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户收益提现表';

-- ----------------------------
-- Table structure for nl_user_notice
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_notice`;
CREATE TABLE `nl_user_notice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `clock1` int(11) NOT NULL DEFAULT '0' COMMENT '提醒时刻1 格式 分钟',
  `clock2` int(11) NOT NULL DEFAULT '0' COMMENT '提醒时刻2  格式 分钟',
  `clock1_str` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '提醒时刻1  格式HH:ss',
  `clock2_str` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '提醒时刻2  格式HH:ss',
  `openid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户openid',
  `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间戳',
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`) USING BTREE,
  KEY `idx_clock1` (`clock1`) USING BTREE,
  KEY `idx_clock2` (`clock2`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户提醒设置';

-- ----------------------------
-- Table structure for nl_user_third_account
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_third_account`;
CREATE TABLE `nl_user_third_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户名称',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `withdrawal_way` tinyint(3) NOT NULL DEFAULT '0' COMMENT '提现方式 1微信 2支付宝',
  `wx_pay_qrcode` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信收款码',
  `alipay_qrcode` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付宝收款码',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户第三方账号表';

-- ----------------------------
-- Table structure for nl_user_wechat
-- ----------------------------
DROP TABLE IF EXISTS `nl_user_wechat`;
CREATE TABLE `nl_user_wechat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户uuid',
  `avatar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `nickname` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0位置 1男 2女',
  `city` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '城市',
  `province` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '省份',
  `country` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '国家',
  `openid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `mobile` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `session_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '会话密钥',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_uuid` (`uuid`),
  UNIQUE KEY `udx_openid` (`openid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

SET FOREIGN_KEY_CHECKS = 1;
