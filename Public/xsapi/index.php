<?php
/**
 *  xsapi 统一入口
 */

require_once dirname(__FILE__) . '/../init.php';
require_once dirname(__FILE__) . '/../../xsapi/Common/functions.php';
require_once dirname(__FILE__) . '/../../xsapi/Common/gd.php';
//装载你的接口
DI()->loader->addDirs('xsapi','Library');
//DI()->cache=new PhalApi_Cache_Memcache(DI()->config->get('sys.mc'));
//DI()->cache = new Cache_Lite(3600,"../../cache/xsapi/");
DI()->view = new View_Lite("xsapi","Default");
//DI()->cart = new Cart_Lite();
DI()->sms = new Sms_Lite();
DI()->ucloud = new UCloud_Lite();
//DI()->image = new Image_Lite();
DI()->alipay = new Alipay_Lite();
//DI()->jpush = new JPush_Lite(DI()->config->get('app.jpush.appkey'),DI()->config->get('app.jpush.appsecret'));
/** ---------------- 响应接口请求 ---------------- **/

$api = new PhalApi();
$rs = $api->response();
$rs->output();


