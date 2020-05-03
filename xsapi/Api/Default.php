<?php
/**
 * 默认接口服务类
 */

class Api_Default extends PhalApi_Api {

	public function getRules() {
        return array(
          'Index' => array(
			    'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'登录后的token'),
			  	'channel' => array('name' => 'channel', 'type' => 'string', 'require' => false,'desc'=>'1男2女(未登录，以此为准)'),
                'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
            ),

			'Banner' => array(
				'typeid' => array('name' => 'typeid', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'1推荐,2男频,3女频')
			),

        );

	}


    /**
	 * 推荐模块
     * @desc 推荐阅读
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 信息ID
     * @return string list[].typeid 1订单 2推荐书籍
     * @return string list[].title 标题
     * @return string list[].orderid 订单号
	 * @return string list[].paytype 支付 1支付宝 2微信
     * @return string list[].titlepic 标题图片
     * @return string list[].book_id 书ID
	 * @return string list[].chapte_id 章节ID,章节为空的时候 直接跳书本详细
     * @return string list[].book_name 书名
	 * @return string list[].book_url 书封面
     * @return string list[].description 简介
	 */
	 public  function Index(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Default();
        $rs['list'] = $domain -> getIndexTuijian($this->token,$this->channel,$this->id,$this->pagesize);
		return $rs;
	 }

	/**
	 * 推荐模块
	 * @desc 获取banner图片：1推荐,2男频,3女频
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].lid ID
	 * @return string list[].lname 标题
	 * @return string list[].lpic 标题图片
	 * @return string list[].book_id 书ID
	 * @return string list[].lurl 外部链接，存在跳外部链接
	 * @return string 特殊说明 <font color="red">banner图片最多返回5条</font>
	 */
	public  function Banner(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Default();
		$rs['list'] = $domain -> getBanner($this->typeid);
		return $rs;
	}

	/**
	 * 推荐模块
	 * @desc 获取Tags标签
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].keyid ID
	 * @return string list[].keyname Tags名
	 */
	public  function Tags(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Default();
		$rs['list'] = $domain -> getTags();
		return $rs;
	}

}
