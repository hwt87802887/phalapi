<?php 
/**
 * 以下配置为系统级的配置，通常放置不同环境下的不同配置
 */

return array(
	/**
	 * 默认环境配置
	 */
	'debug' => false,
	'authkey'=>'Dujejt9FaeZllTOl3AuurlryWPBGub948YnWdtWxTx8qymgQL9EL3tcj8e0hZX48',
	'filepath'=>'http://api.qqyou.com/xsapi/Public',
	/**
	 * MC缓存服务器参考配置
	 */
	 'mc' => array(
        'host' => '127.0.0.1',
        'port' => 11211,
	 ),

    /**
     * 加密
     */
    'crypt' => array(
        'mcrypt_iv' => 'XSUNZBWSY',      //8位
    ),

	/*淘宝客*/
	'tk'=>array(
		'apiurl'=>'http://v2.api.haodanku.com',
		'apikey'=>'JjdTEolew',
		'session'=>'70000100845678567e01c9396788c671f707adc1e5a3b8b70f2fbed241806b71d2188032154977053',
		'pid'=>'mm_124255194_86600493_16230250069',
		'small_w_h' => '_250x250.jpg',
		'big_w_h' => '_400x400.jpg',
	),
	
	'xsapp'=> array(
        'appname' => '趣看看',
		'appnameen' => 'qkk',
		'weixin' => '趣看看',
		'qq' => '251763280',
		'qq_qun' => '894645208',
		'qq_qun_key_iphone'=>'2d43df3f664464cfd17314d12b8ca1df3728e2fb087abe2174c8d39c1c0cd23e',
		'qq_qun_key_android'=>'QxqPGCApGAbjCz4ZGO-Qi_YC7YjderIg',
		'homeurl' => 'http://app.qqyou.com/',
        'siteurl' => 'http://api.qqyou.com/',
        'filepath' => 'http://api.qqyou.com/Public',
		'txtpath'=>"/data/book/d/txt/book/",
    )

);
