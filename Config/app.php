<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

    /**
     * 应用接口层的统一参数
     */
	 /*
    'apiCommonRules' => array(
       'sign' => array('name' => 'sign', 'require' => true),
    ),
	*/

    /**
     * 不需要统一签名服务

    'service'=> array(
         'Order.OrderConfirm',
         'Order.changeConfirm',
        ),
     */

    /**
     * 极光推送
     */
    'jpush' => array(
		'appkey'=>'49f77514663db6da9a70e91b',
		'appsecret'=>'045610222cc1a2904ef56435',
    ),

    /**
     * 云上传引擎,支持local,oss,upyun
     */
    'UCloudEngine' => 'local',

    /**
     * 本地存储相关配置（UCloudEngine为local时的配置）
     */
    'UCloud' => array(
        //对应的文件路径
        'host' => 'http://api.qqyou.com/upload',
    ),

);
