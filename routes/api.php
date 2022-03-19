<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth.api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth.api'], 'namespace' => 'Api'], function(){
    //获取上传token
    Route::get('getUploadToken', 'UserController@getUploadToken');
    Route::get('order_cps', 'OrderCpsController@getOrderList');
    Route::get('income', 'IncomeController@getUserIncome');
    Route::get('income_details', 'IncomeController@getUserIncomeDetails');
    Route::get('withdrawals', 'IncomeController@getUserWithdrawals');
    // 提现申请
    Route::post('apply_withdrawal', 'IncomeController@applyWithdrawal');
    // 设置第三方账号信息
    Route::post('bindThirdAccount', 'IncomeController@bindThirdAccount');
    // 获取第三方账号信息
    Route::get('getThirdAccount', 'IncomeController@getThirdAccount');
    // 获取提现配置
    Route::get('getWithdrawalSetting', 'IncomeController@getWithdrawalSetting');
    //设置微信信息
    Route::post('setWechatUserInfo', 'UserController@setWechatUserInfo');
    //执行抽奖
    Route::post('startDraw', 'DrawController@startDraw');
    //获取抽奖历史
    Route::get('getDrawHisotry', 'DrawController@getDrawHisotry');
    //获取金币总额
    Route::get('getUserCoin', 'CoinController@getUserCoin');
    //获取金币明细
    Route::get('getUserCoinDetail', 'CoinController@getUserCoinDetail');
    //获取用户抽奖次数
    Route::get('getUserDrawCount', 'DrawController@getUserDrawCount');
    //设置点餐提醒时间
    Route::post('setOrderNotice', 'UserNoticeController@setOrderNotice');
    //获取点餐提醒设置
    Route::get('getOrderNoticeSetting', 'UserNoticeController@getOrderNoticeSetting');
    //贝壳兑换物品
    Route::post('redeemHandle', 'CoinController@redeemHandle');
    //抽奖连抽
    Route::post('drawCombo', 'DrawController@drawCombo');
});


Route::namespace('Api')->group(function(){
    Route::get('coupons', 'CouponController@getCouponList');
    //获取首页展示的优惠券
    Route::get('getIndexCoupons', 'CouponController@getIndexCoupons');
    //获取单个优惠券信息
    Route::get('getCouponDetail', 'CouponController@getCouponDetail');
    Route::get('test', 'CouponController@topTest');
    Route::get('ad/{position}', 'AdController@getPostionAd');
    Route::get('getMeituanOrder', 'CouponController@getMeituanOrder');
    Route::get('getMeituanOrderDetail', 'CouponController@getMeituanOrderDetail');
    Route::get('tbkOrder', 'CouponController@tbkOrder');
    Route::get('getCouponInfoForShare', 'CouponController@getCouponInfoForShare');
    Route::get('getDrawInfo', 'DrawController@getDrawInfo');
    Route::get('getFoods', 'FoodController@getFoods');
    //获取小程序相关设置
    Route::get('getMiniProgramSetting', 'SettingController@getMiniProgramSetting');
    //获取兑换物品
    Route::get('getRedeemConfig', 'CoinController@getRedeemConfig');
    //获取微信access_token
    Route::post('getWxAccessToken', 'WechatController@getWxAccessToken');
});

Route::post('login', 'Api\LoginController@doLogin');
