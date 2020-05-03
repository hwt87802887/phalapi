<?php
/**
 * 异步接口服务类
 */

class Api_Asyn extends PhalApi_Api {

	public function getRules() {
        return array(
          'BookHistory' => array(
                'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'用户ID'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'书ID'),
			    'chapte_id' => array('name' => 'chapte_id', 'type' => 'string', 'require' => true,'desc'=>'章节ID'),
			    'chapte_name' => array('name' => 'chapte_name', 'type' => 'string', 'require' => true,'desc'=>'章节名'),
            ),

			'BookSelf' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'用户ID'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'书ID')
			),

			'BookSetLike' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'用户ID'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'书ID')
			),
			
			'BookComments' => array(
				'param' => array('name' => 'param', 'type' => 'string', 'require' => true,'desc'=>'书ID')
			),
			'VoteComment' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'uid'),
				'id' => array('name' => 'id', 'type' => 'string', 'require' => true,'desc'=>'评论id'),
				'act' => array('name' => 'act', 'type' => 'string', 'require' => true,'desc'=>'操作1点赞 2取消')
			),
			
			'BookGift' => array(
				'canshu' => array('name' => 'canshu', 'type' => 'string', 'require' => true,'desc'=>'uid')
			),

			'InfoRead' => array(
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'信息ID')
			),
		
			'InfoZan' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'uid'),
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'评论id'),
				'zan' => array('name' => 'zan', 'type' => 'string', 'require' => true,'desc'=>'操作1喜欢 2不喜欢')
			),

			'VoteCommentInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'uid'),
				'id' => array('name' => 'id', 'type' => 'string', 'require' => true,'desc'=>'评论id')
			),

			'InfoComments' => array(
				'param' => array('name' => 'param', 'type' => 'string', 'require' => true,'desc'=>'')
			),

            'AddAddress' => array(
                'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => false,'desc'=>''),
                'param' => array('name' => 'param', 'type' => 'string', 'require' => true,'desc'=>'')
            ),

			'SetDefault' => array(
				'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => true,'desc'=>''),
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'')
			),

			'SetAdrDel' => array(
				'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => true,'desc'=>''),
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'')
			),

			'Welfare' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>''),
				'fuid' => array('name' => 'fuid', 'type' => 'string', 'require' => true,'desc'=>''),
				'param' => array('name' => 'param', 'type' => 'string', 'require' => true,'desc'=>'')
			),

			'Sign' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>''),
				'signcount' => array('name' => 'signcount', 'type' => 'string', 'require' => true,'desc'=>'签到次数'),
				'day' => array('name' => 'day', 'type' => 'string', 'require' => true,'desc'=>'连续签到'),
				'gold' => array('name' => 'gold', 'type' => 'string', 'require' => true,'desc'=>'是否存在')
			),
			'Share' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>''),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'操作'),
				'gold' => array('name' => 'gold', 'type' => 'string', 'require' => true,'desc'=>'')
			),

			'ReadNews' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>''),
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'资讯id'),
				'gold' => array('name' => 'gold', 'type' => 'string', 'require' => true,'desc'=>'')
			),

			'Lottery' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>''),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'操作'),
				'fuli' => array('name' => 'fuli', 'type' => 'string', 'require' => true,'desc'=>'操作'),
				'gold' => array('name' => 'gold', 'type' => 'string', 'require' => true,'desc'=>'')
			),

			'Tixian' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>''),
				'gold' => array('name' => 'gold', 'type' => 'string', 'require' => false,'desc'=>'需扣除金币'),
				'money' => array('name' => 'money', 'type' => 'string', 'require' => true,'desc'=>'需扣除账户余额'),
				'total' => array('name' => 'total', 'type' => 'string', 'require' => true,'desc'=>'提现金额')
			),

			'YaoQing' => array(
				'fuid' => array('name' => 'fuid', 'type' => 'string', 'require' => true,'desc'=>'邀请人'),
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'被邀请人'),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'登录方式'),
			),

			'Xinren' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>''),
				'login_type' => array('name' => 'login_type', 'type' => 'string', 'require' => false,'desc'=>'')
			),

			'Wszl' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => true,'desc'=>'')
			),

		);

	}



	/**
	 * 异步处理模块
	 * @desc 阅读章节历史记录
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function BookHistory(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetBookHistory($this->uid,$this->book_id,$this->chapte_id,$this->chapte_name);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 添加书架
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function BookSelf(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetBookSelf($this->uid,$this->book_id);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 书本点赞
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function BookSetLike(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetBookLike($this->uid,$this->book_id);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 书本、章节、用户评论
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function BookComments(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetBookComments($this->param);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 书本、章节、用户评论
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function VoteComment(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetVoteComment($this->uid,$this->id,$this->act);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 打赏
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function BookGift(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetBookGift($this->canshu);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 娱乐阅读量
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function InfoRead(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetInfoRead($this->param);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 娱乐信息点赞
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function InfoZan(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetInfoZan($this->uid,$this->info_id,$this->zan);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 娱乐用户评论
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function VoteCommentInfo(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetVoteCommentInfo($this->uid,$this->id);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 娱乐用户评论
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function InfoComments(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetInfoComments($this->param);
		return $rs;
	}

    /**
     * 异步处理模块
     * @desc 添加收货地址
     * @return int code 操作码 0表示成功，1表示失败
     * @return string msg 提示信息
     * @return object list 数据列表
     */
    public  function AddAddress(){
        $rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Asyn();
        $rs['list'] = $domain -> SetAddAddress($this->adrid,$this->param);
        return $rs;
    }

	/**
	 * 异步处理模块
	 * @desc 设置默认地址
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function SetDefault(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetDefault($this->adrid,$this->uid);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 删除收货地址
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function SetAdrDel(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetAdrDel($this->adrid,$this->uid);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 更新福利状态
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function Welfare(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetWelfare($this->uid,$this->param,$this->fuid);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 签到
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function Sign(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetSign($this->uid,$this->signcount,$this->day,$this->gold);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 分享回调处理
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function Share(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetShare($this->uid,$this->type,$this->gold);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 阅读资讯
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function ReadNews(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetReadNews($this->uid,$this->info_id,$this->gold);
		return $rs;
	}


	/**
	 * 异步处理模块
	 * @desc 抽奖回调处理
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function Lottery(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain -> SetLottery($this->uid,$this->type,$this->fuli,$this->gold);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 提现兑换-立即提现
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	private function Tixian(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain->SetTixian($this->uid,$this->gold,$this->money,$this->total);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 邀请好友
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function YaoQing(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain->SetYaoQing($this->fuid,$this->uid,$this->type);
		return $rs;
	}

	/**
	 * 异步处理模块
	 * @desc 新人
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function Xinren(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain->SetXinren($this->uid,$this->login_type);
		return $rs;
	}


	/**
	 * 异步处理模块
	 * @desc 完善资料
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function Wszl(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Asyn();
		$rs['list'] = $domain->SetWszl($this->uid);
		return $rs;
	}


}
