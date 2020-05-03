<?php
/**
 * 福利接口服务类
 */

class Api_Welfare extends PhalApi_Api {

	public function getRules() {
        return array(
          'Index' => array(
			  'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
            ),

			'Sign' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
			),

			'ReceiveAward' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'基本福利type值'),
			),

			'Share' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
			),

			'ReadNews' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'娱乐信息id'),
				'min_token' => array('name' => 'min_token', 'type' => 'string', 'require' => true,'desc'=>'阅读资讯min_token'),
			),

			'Lottery' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
			),

			'ReadBook' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'chapte_id' => array('name' => 'chapte_id', 'type' => 'string', 'require' => true,'desc'=>'试读章节ID'),
				'min_token' => array('name' => 'min_token', 'type' => 'string', 'require' => true,'desc'=>'试读章节min_token'),
			),

        );

	}


    /**
	 * 福利模块
     * @desc 基本福利
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[sign].day 连续签到天数
     * @return string list[sign].gold 明日签到领取金币
     * @return string list[sign].status 今日是否签到 1已签到
	 * @return string list[sign].desc 说明
	 * @return string list[welfare].type 福利类型
     * @return string list[welfare].type_name 标题
	 * @return string list[welfare].type_desc 说明
	 * @return string list[welfare].gold 奖励金币/现金
     * @return string list[welfare].status 0未完成 1已完成,待领取 2已领取
	 * @return string list[welfare].status_desc 说明
	 * @return object list[welfare][book] 试读书籍
	 * @return string list[welfare][book].book_id 书本id
	 * @return string list[welfare][book].next_chapte 试读章节
	 * @return string list[welfare][book].sort_id 章节序号
	 * @return string list[welfare][book].sdtime 每个章节至少阅读时间，单位秒
	 * @return string list[welfare][book].sdtoken 试读token
	 */
	 public  function Index(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Welfare();
        $data = $domain -> getIndex($this->token);
		 if($data['code']==0){
			 $rs['list'] = $data['list'];
		 }else{
			 $rs['code']=$data['code'];
			 $rs['msg']=$data['msg'];
		 }
		 return $rs;
	 }

	/**
	 * 福利模块
	 * @desc 今日签到
	 * @return int code 操作码 0表示成功，1表示已签到，2未登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].day 连续签到天数
	 * @return string list[].gold 明日签到领取金币
	 * @return string list[].desc 说明
	 */
	public  function Sign(){
		$rs = array('code' => 0, 'msg' => '签到成功', 'list' => array());
		$domain = new Domain_Welfare();
		$data = $domain -> getSign($this->token);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 福利模块
	 * @desc 领取福利奖励
	 * @return int code 操作码 0表示领取成功，1表示失败，2未登录,3未完成
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].type 福利类型
	 * @return string list[].gold 领取金币/现金
	 * @return string list[].status 领取后状态
	 * @return string list[].status_desc 说明
	 */
	public  function ReceiveAward(){
		$rs = array('code' => 0, 'msg' => '领取成功', 'list' => array());
		$domain = new Domain_Welfare();
		$data = $domain -> getReceiveAward($this->token,$this->type);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 福利模块
	 * @desc 每日分享-回调（返回金币需提醒用户，其他情况静默处理）
	 * @return int code 操作码 0表示成功，1表示已分享，2未登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].gold 获得金币
	 * @return string list[].desc 描述
	 */
	public  function Share(){
		$rs = array('code' => 0, 'msg' => '操作成功', 'list' => array());
		$domain = new Domain_Welfare();
		$data = $domain -> getShare($this->token);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 福利模块
	 * @desc 阅读资讯-回调（返回金币需提醒用户，其他情况静默处理）
	 * @return int code 操作码 0表示成功，1表示已阅读，2未登录，3资讯验证失败或未到时间
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].gold 获得金币
	 * @return string list[].desc 描述
	 */
	public  function ReadNews(){
		$rs = array('code' => 0, 'msg' => '操作成功', 'list' => array());
		$domain = new Domain_Welfare();
		$data = $domain -> getReadNews($this->token,$this->min_token,$this->info_id);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 福利模块
	 * @desc 小说试读-回调（返回金币需提醒用户，其他情况静默处理）
	 * @return int code 操作码 0表示成功，1表示已阅读，2未登录，3验证失败或未到时间
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].gold 获得金币
	 * @return string list[].desc 描述
	 */
	public  function ReadBook(){
		$rs = array('code' => 0, 'msg' => '操作成功', 'list' => array());
		$domain = new Domain_Welfare();
		$data = $domain -> getReadBook($this->token,$this->min_token,$this->chapte_id);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 福利模块
	 * @desc 每日首次打开娱乐，可参与抽奖
	 * @return int code 操作码 0表示成功，1表示已抽奖，2未登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].type 金币/现金
	 * @return string list[].gold 获得金币/现金
	 * @return string list[].desc 描述
	 */
	public  function Lottery(){
		$rs = array('code' => 0, 'msg' => '操作成功', 'list' => array());
		$domain = new Domain_Welfare();
		$data = $domain -> getLottery($this->token);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


}
