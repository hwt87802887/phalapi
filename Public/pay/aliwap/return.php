<?php
/*
 * +----------------------------------------------------------------------
 * | 支付宝同步回调
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 summer All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: summer <aer_c@qq.com> <qq7579476>
 * +----------------------------------------------------------------------
 * | This is not a free software, unauthorized no use and dissemination.
 * +----------------------------------------------------------------------
 * | Date
 * +----------------------------------------------------------------------
 */

//写入超全局变量
$GLOBALS['PAY_NOTIFY'] = $_POST ? $_POST :$_GET;

$_REQUEST['service'] = 'Pay.AlipayNotify';
$_REQUEST['type']	= 'aliwap';
$_REQUEST['method'] = 'return';
require_once(dirname(dirname(dirname(__FILE__))) . '/xsapi/index.php');