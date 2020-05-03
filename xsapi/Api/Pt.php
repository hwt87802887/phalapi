<?php
/**
 * 拼团接口服务类
 */

class Api_Pt extends PhalApi_Api {

	public function getRules() {
        return array(
          'Lists' => array(
                'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>5, 'require' => false,'desc'=>'每页返回条数')
            ),

			'Detail' => array(
				'id' => array('name' => 'id', 'type' => 'string', 'require' => true,'desc'=>'商品id')
			),

			'Unfinished' => array(
				'id' => array('name' => 'id', 'type' => 'string', 'require' => true,'desc'=>'商品id'),
				'pid' => array('name' => 'pid', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位pid值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>5, 'require' => false,'desc'=>'每页返回条数')
			),

			'CheckOrder' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'orderid' => array('name' => 'orderid', 'type' => 'string', 'require' => true,'desc'=>'订单号')
			),
			'PtDetail' => array(
				'infoid' => array('name' => 'infoid', 'type' => 'string', 'require' => true,'desc'=>'团长拼团ID')
			),

			'WinList' => array(
				'id' => array('name' => 'id', 'type' => 'string', 'require' => true,'desc'=>'拼团商品ID')
			),

        );

	}


    /**
	 * 拼团模块
     * @desc 最新拼团数据2条
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 信息ID
     * @return string list[].title 标题
     * @return string list[].ftitle 副标题
     * @return string list[].titlepic 封面图
	 * @return string list[].yuanjia 单买价
	 * @return string list[].price 拼团价
	 * @return string list[].num 限额人数
     * @return string list[].tnum 成团人数
	 * @return string list[].cstarttime 开抢/抽奖时间
	 * @return string list[].cstarttime 结束/抽奖时间
	 * @return string list[].status 状态 0未开始（去看看） 1已开始（去拼团） 2已结束（等待开奖） 3已开奖（中奖名单）
     * @return string list[].description 简介
	 * @return string list[].pdinfo 用户拼单
	 */
	 public  function Index(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Pt();
        $rs['list'] = $domain -> getIndexTuijian();
		return $rs;
	 }

	/**
	 * 拼团模块
	 * @desc 拼团列表数据
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 信息ID
	 * @return string list[].title 标题
	 * @return string list[].ftitle 副标题
	 * @return string list[].titlepic 封面图
	 * @return string list[].yuanjia 单买价
	 * @return string list[].price 拼团价
	 * @return string list[].num 限额人数
	 * @return string list[].tnum 成团人数
	 * @return string list[].time 开抢/抽奖时间
	 * @return string list[].status 状态 0未开始（去看看） 1已开始（去拼团） 2已结束（等待开奖） 3已开奖（中奖名单）
	 * @return string list[].description 简介
	 */
	public  function Lists(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pt();
		$rs['list'] = $domain -> getLists($this->id,$this->pagesize);
		return $rs;
	}

	/**
	 * 拼团模块
	 * @desc 商品详情-限购1件
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 信息ID
	 * @return string list[].title 标题
	 * @return string list[].ftitle 副标题
	 * @return string list[].titlepic 封面图
	 * @return string list[].tuji 图集
	 * @return string list[].yuanjia 单买价
	 * @return string list[].price 拼团价
	 * @return string list[].num 限额人数
	 * @return string list[].tnum 成团人数
	 * @return string list[].time 开抢/抽奖时间
	 * @return string list[].status 状态 0未开始（去看看） 1已开始（去拼团） 2已结束（等待开奖） 3已开奖（中奖名单）
	 * @return string list[].description 简介
	 * @return string list[].sku 属性
	 * @return string list[].tag 标签
	 * @return string list[].tag1 说明
	 * @return string list[].game 玩法说明
	 * @return string list[].contenturl
	 */
	public  function Detail(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pt();
		$data = $domain -> getDetail($this->id);
		if($data['code']==0){
			$rs['msg']=$data['msg'];
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 拼团模块
	 * @desc 商品详情-未完成拼单
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].pid 信息ID
	 * @return string list[].uid 用户id
	 * @return string list[].nickname 用户昵称
	 * @return string list[].avatar 头像
	 * @return string list[].tnum 团人数
	 * @return string list[].num 已参团人数
	 * @return string list[].endtime 结束时间
	 * @return string list[].tzinfoid 团长拼团ID
	 */
	public function Unfinished(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pt();
		$data = $domain -> geUnfinished($this->id,$this->pid,$this->pagesize);
		if($data['code']==0){
			$rs['msg']=$data['msg'];
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 拼团模块
	 * @desc 拼团详情
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 商品id
	 * @return string list[].title 商品标题
	 * @return string list[].sku 属性
	 * @return string list[].titlepic 封面图
	 * @return string list[].price 拼团价
	 * @return string list[].num 还差人数
	 * @return string list[].tnum 成团人数
	 * @return string list[].endtime 结束时间
	 * @return string list[].status 状态 0拼团中 1拼团成功 2拼团失败
	 * @return string list[][tuan] 参团名单
	 * @return string list[][tuan].uid 用户id
	 * @return string list[][tuan].nickname 昵称
	 * @return string list[][tuan].avatar 头像
	 * @return string list[][tuan].tag 标签
	 * @return string list[][like] 猜你喜欢(淘客)
	 * @return string list[][share] 分享列表
	 */
	public function PtDetail(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pt();
		$data = $domain -> getPtDetail($this->infoid);
		if($data['code']==0){
			$rs['msg']=$data['msg'];
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 拼团模块
	 * @desc 订单查询
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].orderid 订单号
	 * @return string list[].title 说明
	 * @return string list[].price 付款金额
	 * @return string list[].sku 属性
	 * @return string list[].paytype 支付类型
	 * @return string list[].zt  -1取消订单 0未付款 1已付款
	 * @return string list[].paytime 付款时间
	 * @return string list[].tzinfoid 团长ID
	 */

	public function CheckOrder(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pt();
		$data = $domain -> getCheckOrder($this->token,$this->orderid);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 拼团模块
	 * @desc 中奖名单
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 商品id
	 * @return string list[].title 商品标题
	 * @return string list[].sku 属性
	 * @return string list[].titlepic 封面图
	 * @return string list[].price 拼团价
	 * @return string list[].time 开奖时间
	 * @return string list[].status 状态 0未开始（去看看） 1已开始（去开团） 2已结束（等待开奖） 3已开奖（中奖名单）
	 * @return string list[][roster] 中奖名单
	 * @return string list[][roster].nickname 昵称
	 * @return string list[][roster].avatar 头像
	 * @return string list[][like] 猜你喜欢(淘客)
	 */

	public function WinList(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pt();
		$data = $domain -> getWinList($this->id);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

}
