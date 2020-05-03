<?php
/**
 * 用户接口服务类
 */

class Api_User extends PhalApi_Api {

	public function getRules() {
        return array(
           'QqLogin' => array(
                'unionid' => array('name' => 'unionid', 'type' => 'string', 'require' => true,'desc'=>'区分不同应用的用户唯一性'),
			    'openid' => array('name' => 'openid', 'type' => 'string', 'require' => true,'desc'=>'openid'),
				'param' => array('name' => 'param', 'type' => 'string', 'require' => true,'desc'=>'URLEncode编码的json参数'),
			    'devicecode'=>array('name' => 'devicecode', 'type' => 'string', 'require' => false,'desc'=>'设备token'),
			    'qudao'=>array('name' => 'qudao', 'type' => 'string', 'require' => false,'desc'=>'渠道')
            ),

			'WxLogin' => array(
				'unionid' => array('name' => 'unionid', 'type' => 'string', 'require' => true,'desc'=>'区分不同应用的用户唯一性'),
				'param' => array('name' => 'param', 'type' => 'string', 'require' => true,'desc'=>'json进行URLEncode编码的参数'),
				'devicecode'=>array('name' => 'devicecode', 'type' => 'string', 'require' => false,'desc'=>'设备token'),
				'qudao'=>array('name' => 'qudao', 'type' => 'string', 'require' => false,'desc'=>'渠道')
			),

			'PhoneLogin' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => false,'desc'=>'邀请人uid'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'require' => true,'desc'=>'手机号码'),
				'smscode' => array('name' => 'smscode', 'type' => 'string', 'require' => true,'desc'=>'短信验证码'),
				'devicecode'=>array('name' => 'devicecode', 'type' => 'string', 'require' => false,'desc'=>'设备token'),
				'qudao'=>array('name' => 'qudao', 'type' => 'string', 'require' => false,'desc'=>'渠道')
			),

			'LoginOut' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token')
			),

			'UserInfo' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token')
			),

			'EditUserInfo' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'nickname' => array('name' => 'nickname', 'type' => 'string', 'require' => false,'desc'=>'用户昵称'),
				'sex' => array('name' => 'sex', 'type' => 'string', 'require' => false,'desc'=>'性别 1男2女'),
				'birth' => array('name' => 'birth', 'type' => 'string', 'require' => false,'desc'=>'出生年月 格式：年-月-日'),
				'area' => array('name' => 'area', 'type' => 'string', 'require' => false,'desc'=>'所在城市 格式：省-市-区')

			),

			'EditPhoto' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
                'file' => array('name' => 'file', 'type' => 'file','min' => 0,'max' => 1024 * 1024 * 2,'range' => array('image/jpg', 'image/jpeg', 'image/png'), 'ext' => array('jpg', 'jpeg', 'png'),'desc'=>'上传文件限2M')
                ),

			'SmsCode' => array(
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'require' => true,'desc'=>'手机号码')
			),

			'BindPhone' => array(
				'uid' => array('name' => 'uid', 'type' => 'string', 'require' => false,'desc'=>'邀请人uid'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'require' => true,'desc'=>'手机号码'),
				'smscode' => array('name' => 'smscode', 'type' => 'string', 'require' => true,'desc'=>'短信验证码')
			),
			'UnBindPhone' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'require' => true,'desc'=>'手机号码'),
				'smscode' => array('name' => 'smscode', 'type' => 'string', 'require' => true,'desc'=>'短信验证码')
			),

			'AlipayRequest' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token')
			),

			'BindAlipay' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'auth_code' => array('name' => 'auth_code', 'type' => 'string', 'require' => true,'desc'=>'授权返回auth_code'),
				'user_id' => array('name' => 'user_id', 'type' => 'string', 'require' => true,'desc'=>'授权返回user_id')
			),

			'UnBindAlipay' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token')
			),

			'OrderLog' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'BalanceLog' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'ChapteBuyLog' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'GiftLog' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'FeedBack' => array(
				'content' => array('name' => 'content', 'type' => 'string', 'require' => true,'desc'=>'问题描述，最多200字'),
				'category' => array('name' => 'category', 'type' => 'string', 'require' => true,'desc'=>'问题类型'),
				'contact' => array('name' => 'contact', 'type' => 'string', 'require' => true,'desc'=>'联系方式手机或QQ')
			),

			
			
            'AdrList' => array(
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
                'isdefault' => array('name' => 'isdefault', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'1获取默认地址'),
                'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位adrid值'),
                'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
            ),

			'SetDefault' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => true,'desc'=>'地址id'),
			),

			'AdrDel' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => true,'desc'=>'地址id'),
			),

            'AdrDetail' => array(
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
                'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => true,'desc'=>'地址id'),
            ),

            'AddAdr' => array(
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
                'name' => array('name' => 'name', 'type' => 'string', 'require' => true,'desc'=>'收件人姓名'),
                'tel' => array('name' => 'tel', 'type' => 'string', 'require' => true,'desc'=>'收件人电话'),
                'area' => array('name' => 'area', 'type' => 'string', 'require' => true,'desc'=>'所在城市 格式：省-市-区'),
                'address' => array('name' => 'address', 'type' => 'string', 'require' => true,'desc'=>'详细地址'),
                'isdefault' => array('name' => 'isdefault', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'1设置为默认地址'),
            ),

            'EditAdr' => array(
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
                'adrid' => array('name' => 'adrid', 'type' => 'string', 'require' => true,'desc'=>'地址id'),
                'name' => array('name' => 'name', 'type' => 'string', 'require' => true,'desc'=>'收件人姓名'),
                'tel' => array('name' => 'tel', 'type' => 'string', 'require' => true,'desc'=>'收件人电话'),
                'area' => array('name' => 'area', 'type' => 'string', 'require' => true,'desc'=>'所在城市 格式：省-市-区'),
                'address' => array('name' => 'address', 'type' => 'string', 'require' => true,'desc'=>'详细地址'),
                'isdefault' => array('name' => 'isdefault', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'1设置为默认地址'),
            ),

			'OrderList' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'订单状态1全部订单、2待付款、3未成团待分享、4已中奖待发货、5待收货'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位infoid值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'OrderDetail' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'infoid' => array('name' => 'infoid', 'type' => 'string', 'require' => true,'desc'=>'订单信息id'),
			),

			'OrderReceive' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'infoid' => array('name' => 'infoid', 'type' => 'string', 'require' => true,'desc'=>'订单信息id'),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'1取消订单、2确认收货'),
			),

			'Exchange' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
			),

			'Tixian' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'exid' => array('name' => 'exid', 'type' => 'string', 'require' => true,'desc'=>'提现金额ID'),
			),

			'ExchangeYdb' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
			),

			'TixianYdb' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'gold' => array('name' => 'gold', 'type' => 'string', 'require' => true,'desc'=>'可兑换阅读币'),
			),

			'TixianRecord' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'提现状态1全部订单、2待付款、3待审核'),
			),


			'Share' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
			),

			'Friend' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'uid' => array('name' => 'uid', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位uid值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'Wallet'=>array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'type' => array('name' => 'type', 'type' => 'string','default'=>1, 'require' => true,'desc'=>'1零钱 2金币'),
			),

        );

	}


    /**
	 * 用户模块
     * @desc QQ登录
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string list
	 * @return string list[].token 验证用户唯一性，请妥善保管
	 * @return string list[].exp 过期时间，请妥善保管
	 * @return string list[].is_new 1新用户
	 */
	 public  function QqLogin(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => '');
        $domain = new Domain_User();
        $data = $domain -> getqqLogin($this->unionid,$this->openid,$this->param,$this->devicecode,$this->qudao);
		 if($data['code']==0){
			 $rs['list'] = $data['list'];
		 }else{
			 $rs['code']=$data['code'];
			 $rs['msg']=$data['msg'];
		 }
		return $rs;
	 }

	/**
	 * 用户模块
	 * @desc 微信登录
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string list
	 * @return string list[].token 验证用户唯一性，请妥善保管
	 * @return string list[].exp 过期时间，请妥善保管
	 * @return string list[].is_new 1新用户
	 */
	public  function WxLogin(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => '');
		$domain = new Domain_User();
		$data = $domain -> getwxLogin($this->unionid,$this->param,$this->devicecode,$this->qudao);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 退出登录
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function LoginOut(){

		$rs = array('code' => 0, 'msg' => '退出成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getLoginOut($this->token);
		if($data['code']==0){
			$rs['msg'] = $data['msg'];
			$rs['list'] = $data['list'];
		}else{
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 获取用户基本信息
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return object list[].uid 用户ID
	 * @return object list[].nickname 昵称
	 * @return object list[].avatar 头像
	 * @return object list[].birth 出生年月
	 * @return object list[].sex 1男2女
	 * @return object list[].mobile 手机号码
	 * @return object list[].province 省
	 * @return object list[].city 市
	 * @return object list[].area 区
	 * @return object list[].login_type 登录方式 qq/wx/phone
	 * @return object list[].money 账户余额
	 * @return object list[].balance 阅读币
	 * @return object list[].gold 金币
	 * @return object list[].integral 积分
	 * @return object list[].is_vip 1VIP
	 * @return object list[].year_surplus VIP到期日期
	 * @return object list[].alipay_name 支付宝昵称
	 */
	public  function UserInfo(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getUserInfo($this->token);
		if($data['code']==0){
			unset($data['list']['onlogin'],$data['list']['status']);
			$rs['list'] = $data['list'];
		}else{
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 编辑用户基本信息  昵称、性别、生日、所在城市至少修改一项
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function EditUserInfo(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getEditUserInfo($this->token,$this->nickname,$this->sex,$this->birth,$this->area);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code'] = $data['code'];
			$rs['msg'] = $data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 修改头像
	 * @return int code 操作码 0表示成功，1必要参数不能为空,2没有登录，3修改失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function EditPhoto(){
		$rs = array('code' => 0, 'msg' => '成功修改头像', 'list' => array());
		if($this->token==''){
			$rs['code'] = 1;
			$rs['msg'] = T('必要参数不能为空');
			return $rs;
		}
		$model = new Model_User();
		$auth = $model->checkToken($this->token);
		if($auth['code']>0){
			$rs['code']=2;
			$rs['msg']="您还没有登录";
			return $rs;
		}
		//设置项目目录
		DI()->ucloud->set('default_path',DI()->config->get('sys.xsapp.appnameen'));

		//设置上传路径 设置方法参考3.2
		DI()->ucloud->set('save_path',date('Ymd'));

		//新增修改文件名设置上传的文件名称
		DI()->ucloud->set('file_name', time().rand(1000,9999));

		//上传表单名
		$res = DI()->ucloud->upfile($this->file);

		if($res['Err'])
		{
			$rs['code']=1;
			$rs['msg']=$res['Err'];
			return $rs;
		}


		if($res['file']) {
			$filepath="../../upload/".$res['file'];
			$fileinfo = ifileinfo($filepath);
			DI()->image->open($filepath);
			DI()->image->thumb(150, 150, IMAGE_THUMB_SCALING);
			$newfile=$fileinfo['ext'][0].'_thumb.'.$fileinfo['ext'][1];
			DI()->image->save($fileinfo['path'].$newfile);

			if($fileinfo['path'].$newfile)
			{
				@unlink($filepath);
				$photopath = DI()->config->get('sys.xsapp.siteurl').str_replace('../../','',$fileinfo['path']).$newfile;
			}

			$info = $model -> getEditUserInfo($auth['uid'],array('avatar'=>$photopath));
			if($info===false){
				$rs['code'] = 1;
				$rs['msg'] = T('必要参数不能为空');
			}

			if($rs['code']>0){
				@unlink($fileinfo['path'].$newfile);
				$photopath='';
			}
			$rs['list'] = array('avatar'=>$photopath);

		}else{
			$rs['code']=1;
			$rs['msg']="文件是空的";
		}

		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 获取短信验证码(1分钟内1次，每天有数量限制)
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string code 短信验证码 有效期15分钟
	 */
	public  function SmsCode(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getsmsCode($this->mobile);
		$rs['code'] = $data['code'];
		$rs['msg'] = $data['msg'];
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 手机号码登录
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string list
	 * @return string list[].token 验证用户唯一性，请妥善保管
	 * @return string list[].exp 过期时间，请妥善保管
	 * @return string list[].is_new 1新用户
	 */
	public  function PhoneLogin(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getPhoneLogin($this->mobile,$this->smscode,$this->devicecode,$this->uid,$this->qudao);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc QQ/微信登录绑定手机号
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 */
	public  function BindPhone(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getBindPhone($this->token,$this->mobile,$this->smscode,$this->uid);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc QQ/微信登录解除绑定手机号
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 */
	public  function UnBindPhone(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getUnBindPhone($this->token,$this->mobile,$this->smscode);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 支付宝登录授权请求参数
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 */
	public  function AlipayRequest(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getAlipayRequest($this->token);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}
	
	/**
	 * 用户模块
	 * @desc 绑定提现支付宝账号
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 */
	public  function BindAlipay(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getBindAlipay($this->token,$this->auth_code,$this->user_id);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 解绑提现支付宝账号
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 */
	public  function UnBindAlipay(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getUnBindAlipay($this->token);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 我的-阅读币-充值记录
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id
	 * @return string list[].title 充值标题
	 * @return string list[].balance 充值金额
	 * @return string list[].paytime 充值时间
	 */
	public function OrderLog(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getOrderLog($this->token,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 我的-阅读币-兑换记录
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id
	 * @return string list[].gold 扣除金币
	 * @return string list[].balance 兑换得阅读币
	 * @return string list[].addtime 兑换时间
	 */
	public function BalanceLog(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getBalanceLog($this->token,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 我的-阅读币-章节购买记录
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id
	 * @return string list[].book_name 书名
	 * @return string list[].chapte_name 章节
	 * @return string list[].price 扣除阅读币
	 * @return string list[].paytime 购买时间
	 */
	public function ChapteBuyLog(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getChapteBuyLog($this->token,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 我的-阅读币-打赏记录
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id
	 * @return string list[].book_name 书名
	 * @return string list[].gift_name 打赏内容
	 * @return string list[].gift_price 打赏扣除阅读币
	 * @return string list[].gift_time 打赏时间
	 */
	public function GiftLog(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getGiftLog($this->token,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 用户模块
	 * @desc 建议反馈
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 */

	public function FeedBack(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain -> getFeedBack($this->content,$this->category,$this->contact);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		return $rs;
	}


    /**
     * 用户模块
     * @desc 收货地址列表
     * @return int code 操作码 0表示成功，1表示失败,2没有登录
     * @return string msg 提示信息
     * @return object list 数据列表
     * @return string list[].adrid 地址id
     * @return string list[].name 收件人姓名
     * @return string list[].tel 收件人电话
     * @return string list[].address 详细地址
     * @return string list[].province 省市区
     * @return string list[].city
     * @return string list[].area
     * @return string list[].isdefault 1默认
     */
    public  function AdrList(){
        $rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_User();
        $data = $domain->GetAdrList($this->token,$this->isdefault,$this->id,$this->pagesize);
        if($data['code']==0){
            $rs['list'] = $data['list'];
        }else{
            $rs['code']=$data['code'];
            $rs['msg']=$data['msg'];
        }
        return $rs;
    }

	/**
	 * 用户模块
	 * @desc 设置默认收获地址
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function SetDefault(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->SetDefault($this->token,$this->adrid);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 用户模块
	 * @desc 删除收货地址
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function AdrDel(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->SetAdrDel($this->token,$this->adrid);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

    /**
     * 用户模块
     * @desc 收货地址详情
     * @return int code 操作码 0表示成功，1表示失败,2没有登录
     * @return string msg 提示信息
     * @return string list 数据列表
     * @return string list[].adrid 地址id
     * @return string list[].name 收件人姓名
     * @return string list[].tel 收件人电话
     * @return string list[].address 详细地址
     * @return string list[].province 省市区
     * @return string list[].city
     * @return string list[].area
     * @return string list[].isdefault 1默认
     */
    public  function AdrDetail(){
        $rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_User();
        $data = $domain->GetAdrDetail($this->token,$this->adrid);
        if($data['code']==0){
            $rs['list'] = $data['list'];
        }else{
            $rs['code']=$data['code'];
            $rs['msg']=$data['msg'];
        }
        return $rs;
    }

    /**
     * 用户模块
     * @desc 增加收货地址
     * @return int code 操作码 0表示成功，1表示失败,2没有登录
     * @return string msg 提示信息
     * @return object list 数据列表
     */
	public  function AddAdr(){
        $rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_User();
        $data = $domain->GetAddAdr($this->token,$this->name,$this->tel,$this->area,$this->address,$this->isdefault);
        $rs['code']=$data['code'];
        $rs['msg']=$data['msg'];
        return $rs;
    }

    /**
     * 用户模块
     * @desc 修改收货地址
     * @return int code 操作码 0表示成功，1表示失败,2没有登录
     * @return string msg 提示信息
     * @return object list 数据列表
     */
    public  function EditAdr(){
        $rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_User();
        $data = $domain->GetEditAdr($this->token,$this->adrid,$this->name,$this->tel,$this->area,$this->address,$this->isdefault);
        $rs['code']=$data['code'];
        $rs['msg']=$data['msg'];
        return $rs;
    }

	/**
	 * 用户模块
	 * @desc 我的订单-列表
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].infoid 信息id
	 * @return string list[].tzinfoid 团长拼团ID
	 * @return string list[].orderid 订单号
	 * @return string list[].id 商品id
	 * @return string list[].title 商品标题
	 * @return string list[].price  价格
	 * @return string list[].num 数量
	 * @return string list[].sku sku
	 * @return string list[].zt 拼团状态 0拼团中 1拼团成功 2拼团失败
	 * @return string list[].zj 中奖状态 0未中奖 1中奖
	 * @return string list[].lx 0直接购买 1拼团
	 * @return string list[].status 订单状态 -1订单取消 0未付款 1已付款|拼团中去分享|已成团等待抽奖|拼团失败|已中奖待发货|未中奖 2待收货 3已收货 4已退款 5退款失败
	 * @return string list[].desc 订单状态描述
	 * @return string list[].time 拼团时间

	 */
	public function OrderList(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getOrderList($this->token,$this->type,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 我的订单-详情
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].infoid 信息id
	 * @return string list[].orderid 订单号
	 * @return string list[].id 商品id
	 * @return string list[].title 商品标题
	 * @return string list[].price  价格
	 * @return string list[].num 数量
	 * @return string list[].sku sku
	 * @return string list[].zt 拼团状态 0拼团中 1拼团成功 2拼团失败
	 * @return string list[].zj 中奖状态 0未中奖 1中奖
	 * @return string list[].lx 0直接购买 1拼团
	 * @return string list[].status 订单状态 -1取消 0未付款 1已付款|拼团中去分享|已成团等待抽奖|拼团失败|已中奖待发货|未中奖 2待收货 3已收货 4已退款 5退款失败
	 * @return string list[].desc 订单状态描述
	 * @return string list[].name 收货人
	 * @return string list[].tel 收货人电话
	 * @return string list[].address 收货人地址
	 * @return string list[].time 拼团时间
	 * @return string list[].paytype 支付类型
	 * @return string list[].paytime 付款时间
	 * @return object list[like] 猜你喜欢（淘客）
	 */
	public function OrderDetail(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getOrderDetail($this->token,$this->infoid);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 我的订单-取消订单/确认收货
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function OrderReceive(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getOrderReceive($this->token,$this->infoid,$this->type);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 提现兑换-我要提现
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].money 账户余额
	 * @return string list[].gold 金币余额
	 * @return string list[].exmoney 可兑换金额
	 * @return string list[].total 可提现总金额
	 * @return string list[exchange]  选择提现金额
	 * @return string list[exchange].money 提现金额
	 * @return string list[exchange].saleprice 售价
	 * @return string list[exchange].isdefault 1默认选择
	 */
	public function Exchange(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getExchange($this->token);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 提现兑换-立即提现
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function Tixian(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getTixian($this->token,$this->exid);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 提现兑换-兑换阅读币
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].gold 金币余额
	 * @return string list[].dhbl 兑换比例(百分比)
	 * @return string list[].description 兑换说明
	 */
	public function ExchangeYdb(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getExchangeYdb($this->token);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 提现兑换-立即兑换
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function TixianYdb(){
		$rs = array('code' => 0, 'msg' => '兑换成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getTixianYdb($this->token,$this->gold);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}



	/**
	 * 用户模块
	 * @desc 提现兑换-提现记录
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 记录id
	 * @return string list[].nick_name 提现账户
	 * @return string list[].money 提现金额
	 * @return string list[].status 状态 0未审核，1待付款，2已到帐
	 * @return string list[].money 提现金额
	 */
	public function TixianRecord(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getTixianRecord($this->token,$this->type);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


    /**
	 * 用户模块
	 * @desc 邀请好友
	 */

	public function Share(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$model = new Model_User();
		$auth = $model->checkToken($this->token);
		if($auth['code']>0){
			$rs['code']=$auth['code'];
			$rs['msg']=$auth['msg'];
			return $rs;
		}
		$appname = DI()->config->get('sys.xsapp.appname');
		$url = DI()->config->get('sys.xsapp.homeurl')."book/?uid=".$auth['uid'];
		$pic = DI()->config->get('sys.xsapp.homeurl')."book/images/br.png?v=".rand();
		$rs['list']['guize']="1、好友激活 奖1元".PHP_EOL."2、好友充值奖励 奖充值金额的5%金币".PHP_EOL."3、一个手机用户只能被邀请一次".PHP_EOL."4、被邀请人登录绑定手机号才算邀请成功";
		$rs['list']['share'][0]['weixin'] = array(
			'enable'=>"1",
			'pic'=>$pic,
			'title'=>"超赞APP推荐！全正版小说.抢先看！这里有不一样的小说！！",
			'content'=>$appname."，一款“触动我内心“的阅读软件，这里有不一样的小说等你来看！！",
			'url'=>$url
		);
		$rs['list']['share'][1]['timeline'] = array(
			'enable'=>"1",
			'pic'=>$pic,
			'title'=>$appname."小说，一款“触动我内心“的阅读软件，这里有不一样的小说等你来看！！",
			'content'=>"超赞，这里有不一样的小说！！",
			'url'=>$url
		);

		$rs['list']['share'][2]['qq'] = array(
			'enable'=>"1",
			'pic'=>$pic,
			'title'=>$appname."小说，一款“触动我内心“的阅读软件，这里有不一样的小说等你来看！！",
			'content'=>"超赞，这里有不一样的小说！！",
			'url'=>$url
		);

		$rs['list']['share'][3]['qzone'] = array(
			'enable'=>"1",
			'pic'=>$pic,
			'title'=>$appname."小说，一款“触动我内心“的阅读软件，这里有不一样的小说等你来看！！",
			'content'=>"超赞，这里有不一样的小说！！",
			'url'=>$url
		);
		$rs['list']['share'][4]['copy'] = array(
			'enable'=>"1",
			'pic'=>$pic,
			'title'=>"超赞APP推荐！{$appname}小说，海量正版小说.抢先看！这里有不一样的小说！！",
			'content'=>"超赞APP推荐！{$appname}小说，海量正版小说.抢先看！这里有不一样的小说！！",
			'url'=>$url
		);

		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 邀请好友-我的好友
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].uid 用户id
	 * @return string list[].nick_name 昵称
	 * @return string list[].avatar 头像
	 * @return string list[].addtime 注册时间
	 */
	public function Friend(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getFriend($this->token,$this->uid,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 用户模块
	 * @desc 我的钱包-只保留最近三天的收支
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].money 账户余额
	 * @return string list[].total 累积赚取零钱
	 * @return string list[].gold 账户金币
	 * @return object list[record] 收益指出记录
	 * @return string list[record].id 记录id
	 * @return string list[record].title 标题
	 * @return string list[record].description 描述
	 * @return string list[record].money 金币/零钱
	 * @return string list[record].cz 操作
	 * @return string list[record].addtime 记录时间
	 */
	public function Wallet(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_User();
		$data = $domain->getWallet($this->token,$this->type);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 用户模块
	 * @desc 客服帮助-网页版
	 */

	public function Help(){
		$param = DI()->config->get("sys.xsapp");
		$param['device'] = igetdevice();
		DI()->view->show("help",$param);
	}

	 /**
	  * 用户模块
	  * @desc 版本更新、系统设置-关于我们
	  * @return int code 操作码 0表示成功，1表示失败
	  * @return string msg 提示信息
	  * @return object list 数据列表
	  * @return string list['app_upgrade'] 版本信息
	  * @return string list['about'].appname app名称
	  * @return string list['about'].weixin 客服微信
	  * @return string list['about'].qq 客服qq
	  * @return string list['about'].qq_qun qq群
	  * @return string list['about'].qq_qun_key qq群key值
	 */
	public function Config(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$rs['list']['app_upgrade'] = array(
			'version'=>'2.1.2',
			'version_num'=>'212',
			'description'=>'2.1.2新版本更新：<br/>1. 为了更好的体验，此版本为强制更新，感谢大家配合；<br/>2. 修复一些小bug，增强app稳定性',
			'upgrade_url'=>DI()->config->get('sys.xsapp.siteurl').'version/leshu-v2.1.2.apk'
		);
		$rs['list']['about']=array(
			'appname'=>DI()->config->get('sys.xsapp.appname'),
			'weixin'=>DI()->config->get('sys.xsapp.weixin'),
			'qq'=>DI()->config->get('sys.xsapp.qq'),
			'qq_qun'=>DI()->config->get('sys.xsapp.qq_qun'),
			'qq_qun_key_iphone'=>DI()->config->get('sys.xsapp.qq_qun_key_iphone'),
			'qq_qun_key_android'=>DI()->config->get('sys.xsapp.qq_qun_key_android')
		);
		return $rs;
	}

}
