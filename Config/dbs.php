<?php
/**
 * 分库分表的自定义数据库路由配置
 */

return array(
    /**
     * DB数据库服务器集群
     */
    'servers' => array(
        'db_xsapi' => array(                         //服务器标记
            'host'      => 'rm-wz9xi99680byp644g.mysql.rds.aliyuncs.com',             //数据库域名
            'name'      => 'bookdata',               //数据库名字
            'user'      => 'qyzhangwengwu',                  //数据库用户名
            'password'  => 'tiou%^hCYLl0975RT09EtNB',	                    //数据库密码
            'port'      => '3306',                  //数据库端口
            'charset'   => 'UTF8',                  //数据库字符集
        ),
    ),

    /**
     * 自定义路由表
     */
    'tables' => array(
        //通用路由
        '__default__' => array(
            'prefix' => 'xs_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_xsapi'),
            ),
        ),

        'ecms_info_data' => array(
            'prefix' => 'xs_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_xsapi'),
                array('start' => 1, 'end' => 2, 'db' => 'db_xsapi'),
            ),
        ),

        'ecms_pintuan_data' => array(
            'prefix' => 'xs_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_xsapi'),
                array('start' => 1, 'end' => 2, 'db' => 'db_xsapi'),
                ),
        ),

        'pintuan' => array(
            'prefix' => 'xs_',
            'key' => 'pid',
            'map' => array(
                array('db' => 'db_xsapi')
            ),
        ),

        'pintuan_info' => array(
            'prefix' => 'xs_',
            'key' => 'infoid',
            'map' => array(
                array('db' => 'db_xsapi')
            ),
        ),


        /**
        'demo' => array(                                                //表名
            'prefix' => 'phome_',                                       //表名前缀
            'key' => 'id',                                              //表主键名
            'map' => array(                                             //表路由配置
                array('db' => 'db_xsapi'),                              //单表配置：array('db' => 服务器标记)
                array('start' => 1, 'end' => 2, 'db' => 'db_xsapi'),    //分表配置：array('start' => 开始下标, 'end' => 结束下标, 'db' => 服务器标记)
            ),
        ),
         */
    ),
);