<?php
/**
 * 书架接口服务类
 */

class Api_BookSelf extends PhalApi_Api {

	public function getRules() {
        return array(

			'Tuijian' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登录获取的token'),
			),

            'Index' => array(
			    'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登录获取的token'),
                'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>8, 'require' => false,'desc'=>'每页返回条数')
            ),

			'History' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登录获取的token'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>8, 'require' => false,'desc'=>'每页返回条数')
			),

			'DelBookSelf' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登录获取的token'),
				'id' => array('name' => 'id', 'type' => 'string', 'require' => true,'desc'=>'URLEncode编码的json参数'),
			),
			'DelHistory' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登录获取的token')
			),

        );

	}


	/**
	 * 书架模块
	 * @desc  书架推荐书本-（签到+推荐）
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return object list[sign] 签到数据
	 * @return string list[sign].title 标题
	 * @return string list[sign].day 签到天数
	 * @return string list[sign].gold 获得奖励
	 * @return string list[sign].description 描述
	 * @return string list[sign].status 0未签到，1签到（未签到才展示）
	 * @return object list[book] 数据列表
	 * @return string list[book].id 信息ID
	 * @return string list[book].book_id 书本ID
	 * @return string list[book].book_name 书名
	 * @return string list[book].author 作者
	 * @return string list[book].book_url 书封面图
	 * @return string list[book].chapte_num 总章节数
	 * @return string list[book].chapte_id 章节ID,章节为空的时候 直接跳书本详细
	 * @return string list[book].chapte_name 章节名
	 * @return string list[book].description 简介
	 */
	public  function Tuijian(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_BookSelf();
		$data = $domain -> getTuijian($this->token);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

    /**
	 * 书架模块
     * @desc  收藏书架
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 信息ID
     * @return string list[].book_id 书本ID
     * @return string list[].book_name 书名
     * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
     * @return string list[].chapte_num 总章节数
	 * @return string list[].chapte_id 章节ID,章节为空的时候 直接跳书本详细
     * @return string list[].chapte_name 章节名
	 */
	 public  function Index(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_BookSelf();
        $data = $domain -> getBookSelf($this->token,$this->id,$this->pagesize);
		 if($data['code']==0){
			 $rs['list'] = $data['list'];
		 }else{
			 $rs['code']=$data['code'];
			 $rs['msg']=$data['msg'];
		 }
		return $rs;
	 }

	/**
	 * 书架模块
	 * @desc  删除收藏书架
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function DelBookSelf(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_BookSelf();
		$data = $domain -> getDelBookSelf($this->token,$this->id);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		return $rs;
	}

	/**
	 * 书架模块
	 * @desc  阅读历史记录
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 信息ID
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].chapte_num 总章节数
	 * @return string list[].chapte_id 章节ID,章节为空的时候 直接跳书本详细
	 * @return string list[].chapte_name 章节名
	 */
	public  function History(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_BookSelf();
		$data = $domain -> getBookHistory($this->token,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书架模块
	 * @desc  清空阅读历史记录
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function DelHistory(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_BookSelf();
		$data = $domain -> getDelHistory($this->token);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		return $rs;
	}


}
