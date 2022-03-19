<?php

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['backend.login']], function(){
    Route::post('upload', 'UploadController@uploadToLocal');
    //获取七牛上传token
    Route::get('getQiniuUploadToken', 'UploadController@getQiniuUploadToken');
});

Route::group(['namespace' => 'Admin'], function(){
    // 登录
	Route::post('login', 'LoginController@index');
    Route::get('me', 'LoginController@me');
    // Route::get('main', 'MainController@index');
    Route::post('logout', 'LoginController@logout');
    // Route::post('upload', 'EditorUploadController@uploadImg');

    Route::group(['middleware' => ['backend.login']], function(){
        // 管理员
        Route::resource('admin', 'AdminController');
        Route::resource('coupon', 'CouponController');
        Route::resource('ad', 'AdController');
        Route::get('getCouponConfig', 'CouponController@getCouponConfig');
        Route::get('getOrderList', 'OrderController@getOrderList');
        Route::get('searchOrder', 'OrderController@searchOrder');
        Route::get('syncOrder', 'OrderController@syncOrder');
        //基础配置
        Route::get('baseConfig', 'SettingController@baseConfig');
        //提现设置
        Route::get('withdrawalSetting', 'SettingController@withdrawalSetting');
        //小程序配置
        Route::get('miniProgramSetting', 'SettingController@miniProgramSetting');
        //保存配置
        Route::post('saveSetting', 'SettingController@saveSetting');

        //查询用户收益明细
        Route::get('getUserIncomeDetails', 'UserIncomeController@getUserIncomeDetails');
        //提现申请列表
        Route::get('getWithdrawalApply', 'UserIncomeController@getWithdrawalApply');
        //处理申请
        Route::post('handleApply', 'UserIncomeController@handleApply');
        //用户列表
        Route::get('getUserList', 'UserController@getUserList');
        //拒绝提现申请
        Route::post('refuseApply', 'UserIncomeController@refuseApply');
        //获取抽奖配置
        Route::get('getDrawConfig', 'DrawConfigController@getConfig');
        //保存抽奖配置
        Route::post('saveDrawConfig', 'DrawConfigController@saveConfig');
        //获取抽奖记录
        Route::get('getDrawRecord', 'DrawConfigController@getDrawRecord');
        //设置小程序用户为管理身份
        Route::post('setAdmin', 'UserController@setAdmin');
    });

});