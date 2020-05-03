<?php
/**
 * 支付接口服务类
 */

class Api_Pay extends PhalApi_Api {

	public function getRules() {
        return array(

			'Recharge' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token')
			),

			'Alipay' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'recharge_id' => array('name' => 'recharge_id', 'type' => 'string', 'require' => true,'desc'=>'充值ID'),
				'sdtoken' => array('name' => 'sdtoken', 'type' => 'string', 'require' => false,'desc'=>'试读token')
			),

			'Alipay_Pt' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'id' => array('name' => 'id', 'type' => 'string', 'require' => true,'desc'=>'拼团商品ID'),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'1原价购买，2开团/参团'),
				'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => true,'desc'=>'收货地址ID'),
				'infoid' => array('name' => 'infoid', 'type' => 'string', 'require' => false,'desc'=>'团长拼团ID,参团必须必传'),
				'sku' => array('name' => 'sku', 'type' => 'string', 'require' => false,'desc'=>'属性值格式 颜色:白色;身高:175CM'),
			),
			
			'Weixinpay' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'recharge_id' => array('name' => 'recharge_id', 'type' => 'string', 'require' => true,'desc'=>'充值ID'),
				'sdtoken' => array('name' => 'sdtoken', 'type' => 'string', 'require' => false,'desc'=>'试读token')
			),

			'Weixinpay_Pt' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'id' => array('name' => 'id', 'type' => 'string', 'require' => true,'desc'=>'拼团商品ID'),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'1原价购买，2开团/参团'),
				'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => true,'desc'=>'收货地址ID'),
				'infoid' => array('name' => 'infoid', 'type' => 'string', 'require' => false,'desc'=>'团长拼团ID,参团必须必传'),
				'sku' => array('name' => 'sku', 'type' => 'string', 'require' => false,'desc'=>'属性值格式 颜色:白色;身高:175CM'),
			),

			'UnPay' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'orderid' => array('name' => 'orderid', 'type' => 'string', 'require' => true,'desc'=>'订单号'),
			),

			'RePay' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'orderid' => array('name' => 'orderid', 'type' => 'string', 'require' => true,'desc'=>'订单号'),
				'paytype' => array('name' => 'paytype', 'type' => 'string', 'require' => false,'desc'=>'支付类型 alipay(支付宝) weixin(微信)')
			),

			'RePay_Pt' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'orderid' => array('name' => 'orderid', 'type' => 'string', 'require' => true,'desc'=>'订单号'),
				'paytype' => array('name' => 'paytype', 'type' => 'string', 'require' => false,'desc'=>'支付类型 alipay(支付宝) weixin(微信)')
			),
        );

	}


    /**
	 * 支付模块
     * @desc 小说充值类型
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 信息ID 注意ID=1为首次充值
     * @return string list[].price 充值金额
     * @return string list[].giving 送阅读币
     * @return string list[].is_hot 1选中充值框
	 * @return string list[].is_vip 1选中年费VIP会员
     * @return string list[].yuedubi 阅读币
	 */
	 public  function Recharge(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Pay();
        $data = $domain -> getRecharge($this->token);
		if($data['code']){
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}else{
			$rs['list']=$data;
		}
		return $rs;
	 }



	/**
 * 支付模块
 * @desc 支付宝充值-小说
 * @return int code 操作码 0表示成功，1表示失败,2没有登录
 * @return string msg 提示信息
 * @return object list 数据列表
 * @return string list[].orderid 订单号
 * @return string list[].price 充值金额
 * @return string list[].subject 主题
 * @return string list[].notify_url 异步URL
 * @return string list[].return_url 同步URL
 */
	public  function Alipay(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pay();
		$data = $domain -> getAlipay($this->token,$this->recharge_id,$this->sdtoken);
		if($data['code']){
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['list']=$data['list'];
		}
		return $rs;
	}

	/**
	 * 支付模块
	 * @desc 支付宝付款-拼团
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录 ，3其他提示信息，4团满
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].orderid 订单号
	 * @return string list[].price 商品金额
	 * @return string list[].subject 主题
	 * @return string list[].notify_url 异步URL
	 * @return string list[].return_url 同步URL
	 */
	public  function Alipay_Pt(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pay();
		$data = $domain -> getAlipay_Pt($this->token,$this->id,$this->type,$this->infoid,$this->sku,$this->adrid);
		if($data['code']){
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['list']=$data['list'];
		}
		return $rs;
	}

	/**
	 * 支付模块
	 * @desc 微信充值-小说
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 微信支付相关参数
	 */
	public  function Weixinpay(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pay();
		$data = $domain -> getWeixinpay($this->token,$this->recharge_id,$this->sdtoken);
		if($data['code']){
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['list']=$data['list'];
		}
		return $rs;
	}

	/**
	 * 支付模块
	 * @desc 微信支付-拼团
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 微信支付相关参数
	 */
	public  function Weixinpay_Pt(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pay();
		$data = $domain -> getWeixinpay_Pt($this->token,$this->id,$this->type,$this->infoid,$this->sku,$this->adrid);
		if($data['code']){
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['list']=$data['list'];
		}
		return $rs;
	}

	/**
	 * 支付模块
	 * @desc 支付宝/微信充值-订单未支付回调-小说
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function UnPay(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pay();
		$data = $domain -> getUnPay($this->token,$this->orderid);
		if($data['code']){
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['list']=$data['list'];
		}
		return $rs;
	}

	/**
	 * 支付模块
	 * @desc 支付宝/微信充值-订单重新支付-小说
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录，3已支付，重复支付
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list.orderid 订单号
	 * @return string list.paytype 支付类型（返回不同订单信息）
	 */
	public  function RePay(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pay();
		$data = $domain -> getRePay($this->token,$this->orderid,$this->paytype);
		if($data['code']){
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['list']=$data['list'];
		}
		return $rs;
	}

	/**
	 * 支付模块
	 * @desc 支付宝/微信-订单重新支付-拼团
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录，3已支付，重复支付
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list.orderid 订单号
	 * @return string list.paytype 支付类型（返回不同订单信息）
	 */
	public  function RePay_Pt(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Pay();
		$data = $domain -> getRePay_Pt($this->token,$this->orderid,$this->paytype);
		if($data['code']){
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['list']=$data['list'];
		}
		return $rs;
	}

	/**
	 * 支付模块
	 * @desc 支付宝回调-小说
	 */
	public function AlipayNotify(){
		/*
			//获取回调信息
			$notify = $GLOBALS['PAY_NOTIFY'];

			if (!$notify) {
				DI()->logger->log('payError', 'Not data commit', array('Type' => "alipay"));
				exit; //直接结束程序，不抛出错误
			}
		*/
		//DI()->logger->log('POST', "接收", $_POST);

		$verify_result = DI()->alipay->alipayNotify();

		DI()->logger->log('verify_result', $verify_result, array('Type' => "alipay"));

		if($verify_result) {

			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			$price = $_POST['price'];

			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			$buyer_email = $_POST['buyer_email'];
			$buyer_id = $_POST['buyer_id'];
			//交易状态
			$trade_status = $_POST['trade_status'];

			DI()->logger->log('trade_status',  $_POST['trade_status'], array('out_trade_no'=>$out_trade_no,'trade_no'=>$_POST['trade_no'],'price'=>$_POST['price']));

			/*
			$cishu = 0;
			if($_POST['trade_status'] == 'TRADE_FINISHED') {
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序

				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
				//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

				//echo "success";		//请不要修改或删除
				$zhuangtai="交易完成";
				$cishu=1;

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			}
			else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序

				//注意：
				//付款完成后，支付宝系统发送该交易状态通知
				//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

				//echo "success";		//请不要修改或删除
				$zhuangtai="交易完成";
				$cishu=1;

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			}
			*/
				if($_POST['trade_status']=="TRADE_FINISHED" || $_POST['trade_status'] == 'TRADE_SUCCESS') {

					$pay = new Model_Pay();
					$time = time();
					$order = $pay->getOrderByOrderId($out_trade_no);
					//if ($order['balance'] != $price) {
					//	echo "fail";
					//	exit();
					//} else {
						echo "success";
					//}
					if ($order['zt'] < 1) {
						$pay->getUpdatePay($out_trade_no, array('zt' => 1, 'paytime' => $time));
						$yuedubi = $order['yuedubi'];
						$is_vip = $order['is_vip'];
						$uid = $order['uid'];
						//更新用户账号
						$user = new Model_User();
						if ($is_vip) {
							$year_surplus = strtotime(date("Y-m-d 00:00:00")) + (365 * 86400);
							$year_surplus = new NotORM_Literal("year_surplus + ".$year_surplus);
							$user->getEditUserInfo($uid, array('is_vip' => 1, 'year_surplus' => $year_surplus));
						} else {
							$balance = new NotORM_Literal('balance + ' . $yuedubi);
							$user->getEditUserInfo($uid, array('balance' => $balance));
						}

						//充值奖励上级
						$u = $user->getuserInfo($uid);
						if($u['fuid']){
							$dhjb = DI()->config->get("fuli.fuli.dhjb");
							$czjl = DI()->config->get("fuli.fuli.czjl")/100;
							$yuedubi = $is_vip?(365*$dhjb):$yuedubi;
							$yuedubi = intval($yuedubi *$czjl);
							$user->getEditUserInfo($u['fuid'], array('balance' => new NotORM_Literal('balance + ' . $yuedubi)));
						}

						//试读充值小说记录
						if($order['sdid']>0){
							$sd = $user->getBookTrial($order['sdid']);
							if($sd){
								if(time()-86400 > $sd['readtime']){
									$daymoney = $order['balance'];
								}else{
									$daymoney = $sd['daymoney'] + $order['balance'];//今日充值
								}
								$totalmoney = $sd['totalmoney'] + $order['balance'];//累积充值

								$user->getEditUserInfo($uid, array('daymoney' => $daymoney,'totalmoney' => $totalmoney));
							}
						}

					}

				}else{
					echo "fail";
				}

		}else{
			//验证失败
			echo "fail";
		}

		exit();

		//移除超全局变量
		//unset($GLOBALS['PAY_NOTIFY']);

	}

	/**
	 * 支付模块
	 * @desc 支付宝回调-拼团
	 */
	public function AlipayNotify_Pt(){

		$verify_result = DI()->alipay->alipayNotify();

		DI()->logger->log('verify_result', $verify_result, array('Type' => "alipay_Pt"));
		//$verify_result = 1;
		//$_POST['out_trade_no']="pt1537003628908254761186";
		//$_POST['trade_status'] = "TRADE_SUCCESS";
		if($verify_result) {

			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			$price = $_POST['price'];

			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			$buyer_email = $_POST['buyer_email'];
			$buyer_id = $_POST['buyer_id'];
			//交易状态
			$trade_status = $_POST['trade_status'];

			DI()->logger->log('trade_status',  $_POST['trade_status'], array('out_trade_no'=>$out_trade_no,'trade_no'=>$_POST['trade_no'],'price'=>$_POST['price']));

			if($_POST['trade_status']=="TRADE_FINISHED" || $_POST['trade_status'] == 'TRADE_SUCCESS') {

				$pay = new Model_Pay();
				$time = time();
				$order = $pay->getOrderByOrderId_Pt($out_trade_no);
				echo "success";
				if ($order['fk'] ==0) {
					$pay->getUpdatePay_Pt($out_trade_no, array('fk' => 1, 'paytime' => $time));

					$modelpt = new Model_Pt();
					//更新拼团信息
					$pay->UpdatePinTuan($order['pid'],array('num'=>new NotORM_Literal('num + 1')));
					$pt = $modelpt->getPt($order['pid']);

					if($pt['lx']==0){
						//直接购买
						$pay->UpdatePinTuan($order['pid'], array('zt' => 1));
						$pay->UpdatePinTuanInfo($order['pid'], array('zt' => 1));
					}elseif($pt['lx']==1){
						//开团或参团
						if($pt['tnum']<=$pt['num']){
							$pay->UpdatePinTuan($order['pid'], array('zt' => 1));
							$pay->UpdatePinTuanInfo($order['pid'], array('zt' => 1));
						}
						//取消同一用户参团或开团未支付的订单
						$pay->CancelOrder($order['uid'],$order['id']);
					}
				}

			}else{
				echo "fail";
			}

		}else{
			//验证失败
			echo "fail";
		}

		exit();

		//移除超全局变量
		//unset($GLOBALS['PAY_NOTIFY']);

	}

}
