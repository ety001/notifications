<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/wechat', function () {
    $config = array(
        'token'=>env('WX_TOKEN'),
        'encodingaeskey'=>'encodingaeskey',
        'appid'=>env('WX_APP_ID'),
        'appsecret'=>env('WX_APP_SECRET')
    );
    $wechat_obj = new \App\Weixin($config);
    $status = $wechat_obj->valid();
    \Log::info('STATUS:'.$status);
    $type = $wechat_obj->getRev()->getRevType();
    $open_id = $wechat_obj->getRevFrom();
    $bind_msg = '功能还在开发中，请稍后访问，你的id为：'.$open_id;
    switch($type)
    {
        case Weixin::MSGTYPE_TEXT:
            if($user_info)
            {
                $wechat_obj->text("功能还在开发中，请稍后访问")->reply();
                exit;
            }
            else
            {
                $wechat_obj->text($bind_msg)->reply();
                exit;
            }
            exit;
            break;
        case Weixin::MSGTYPE_EVENT:
            $event_type = $wechat_obj->getRevEvent();
            if($event_type==Weixin::EVENT_SUBSCRIBE)
            {
                $wechat_obj->text($bind_msg)->reply();
                exit;
            }
            break;
        case Weixin::MSGTYPE_IMAGE:
            $wechat_obj->text("暂不支持图片消息")->reply();
            exit;
            break;
        default:
            //$wechat_obj->text($bind_msg)->reply();
            exit;
            break;
    }
    view('wechat');
});

Route::any('/wechat/send', function () {
    $config = array(
        'token'=>env('WX_TOKEN'),
        'encodingaeskey'=>'encodingaeskey',
        'appid'=>env('WX_APP_ID'),
        'appsecret'=>env('WX_APP_SECRET')
    );
    $wechat_obj = new \App\Weixin($config);
    $status = $wechat_obj->checkAuth($config['appid'], $config['appsecret']);

    $info = array(
        'touser' => env('WX_TOUSER'),
        'template_id' => env('WX_TMPL_ID'),
        'url' => '',
        'topcolor' => '#ff0000',
        'data' => array(
            'first' => array(
                'value' => $_GET['msg'],
                'color' => '#173177'
            )
        )
    );

    $a = $wechat_obj->sendTemplateMessage($info);
    var_dump($a);
    die();
});