<?php
/**
 * 淘客接口服务类
 */

class Api_Tk extends PhalApi_Api {

	public function getRules() {
        return array(
           'Index' => array(
			  	'min_id' => array('name' => 'min_id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'上次获取后的数据的min_id值'),
			    'back' => array('name' => 'back', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数（请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回）')
			),

			'Lists' => array(
				'cid' => array('name' => 'cid', 'type' => 'string', 'require' => true,'desc'=>'分类id'),
				'sort' => array('name' => 'sort', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'0.综合（最新），1.券后价(低到高)，2.券后价（高到低），3.券面额（高到低）<br/>，4.月销量（高到低），6.券面额（低到高），7.月销量（低到高）'),
				'price_min' => array('name' => 'price_min', 'type' => 'string', 'require' => false,'desc'=>'券后价筛选，筛选大于等于所设置的券后价的商品'),
				'price_max' => array('name' => 'price_max', 'type' => 'string', 'require' => false,'desc'=>'券后价筛选，筛选小于等于所设置的券后价的商品'),
				'sale_min' => array('name' => 'sale_min', 'type' => 'string', 'require' => false,'desc'=>'销量筛选，筛选大于等于所设置的销量的商品'),
				'sale_max' => array('name' => 'sale_max', 'type' => 'string', 'require' => false,'desc'=>'销量筛选，筛选小于等于所设置的销量的商品'),
				'coupon_min' => array('name' => 'coupon_min', 'type' => 'string', 'require' => false,'desc'=>'券金额筛选，筛选大于等于所设置的销量的商品'),
				'coupon_max' => array('name' => 'coupon_max', 'type' => 'string', 'require' => false,'desc'=>'券金额筛选，筛选小于等于所设置的销量的商品'),
				'min_id' => array('name' => 'min_id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'上次获取后的数据的min_id值'),
				'back' => array('name' => 'back', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数（请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回）')
			),

			'TypeLists' => array(

				'typeid' => array('name' => 'typeid', 'type' => 'string','default'=>1, 'require' => true,'desc'=>'type=1今日更新（必买清单），type=2是9.9包邮，type=3是30元封顶，type=4是聚划算<br/>，type=5是淘抢购，type=8是品牌单，type=9是天猫商品，type=10是视频单'),
				'cid' => array('name' => 'cid', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'分类id'),
				'sort' => array('name' => 'sort', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'0.综合（最新），1.券后价(低到高)，2.券后价（高到低），3.券面额（高到低）<br/>，4.月销量（高到低），6.券面额（低到高），7.月销量（低到高）'),
				'price_min' => array('name' => 'price_min', 'type' => 'string', 'require' => false,'desc'=>'券后价筛选，筛选大于等于所设置的券后价的商品'),
				'price_max' => array('name' => 'price_max', 'type' => 'string', 'require' => false,'desc'=>'券后价筛选，筛选小于等于所设置的券后价的商品'),
				'sale_min' => array('name' => 'sale_min', 'type' => 'string', 'require' => false,'desc'=>'销量筛选，筛选大于等于所设置的销量的商品'),
				'sale_max' => array('name' => 'sale_max', 'type' => 'string', 'require' => false,'desc'=>'销量筛选，筛选小于等于所设置的销量的商品'),
				'coupon_min' => array('name' => 'coupon_min', 'type' => 'string', 'require' => false,'desc'=>'券金额筛选，筛选大于等于所设置的销量的商品'),
				'coupon_max' => array('name' => 'coupon_max', 'type' => 'string', 'require' => false,'desc'=>'券金额筛选，筛选小于等于所设置的销量的商品'),
				'min_id' => array('name' => 'min_id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'上次获取后的数据的min_id值'),
				'back' => array('name' => 'back', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数（请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回）')
			),



			'Search' => array(
				'keyword' => array('name' => 'keyword', 'type' => 'string', 'require' => true,'desc'=>'搜索关键词'),
				'tb_p' => array('name' => 'sale_max', 'type' => 'string', 'require' => false,'desc'=>'淘宝分页，来源于上次获取后的数据的tb_p值，默认开始请求值为1'),
				'sort' => array('name' => 'sort', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'0.综合，1.最新，2.销量（高到低），3.销量（低到高），4.价格(低到高)，5.价格（高到低）'),
				'is_tmall' => array('name' => 'coupon_min', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'是否只取天猫商品：0否；1是，默认是0'),
				'is_coupon' => array('name' => 'is_coupon', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'是否只取有券商品：0否；1是，默认是0'),
				'min_id' => array('name' => 'min_id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'上次获取后的数据的min_id值'),
				'back' => array('name' => 'back', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数（请在1,2,10,20,50,100中选择一个数值返回）')
			),

			'Detail' => array(
				'itemid' => array('name' => 'itemid', 'type' => 'string', 'require' => true,'desc'=>'itemid商品ID'),
				'activityid' => array('name' => 'activityid', 'type' => 'string', 'require' => false,'desc'=>'activityid 有必传'),

			),

			'Tkl' => array(
				'tklToken' => array('name' => 'tklToken', 'type' => 'string', 'require' => true,'desc'=>'淘口令Token'),
			),

		);

	}


    /**
	 * 淘宝客模块
     * @desc 分类
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	 public  function Categroy(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		//$rs['list'] = array('1'=>'女装','2'=>'男装','3'=>'内衣','4'=>'美妆','5'=>'配饰','6'=>'鞋品','7'=>'箱包','8'=>'儿童','9'=>'母婴','10'=>'居家','11'=>'美食','12'=>'数码','13'=>'家电','15'=>'车品','16'=>'文体','14'=>'其他');

		 $rs['list'] = array(
			 '0'=>array(
				 'classid'=>"0",
				 'classname'=>"精选",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/0.png",
			 ),
			 '1'=>array(
				 'classid'=>"1",
				 'classname'=>"女装",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/1.png",
			 ),
			 '2'=>array(
				 'classid'=>"2",
				 'classname'=>"男装",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/2.png",
			 ),
			 '3'=>array(
				 'classid'=>"3",
				 'classname'=>"内衣",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/3.png",
			 ),
			 '4'=>array(
				 'classid'=>"4",
				 'classname'=>"美妆",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/341.png",
			 ),
			 '5'=>array(
				 'classid'=>"5",
				 'classname'=>"配饰",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/13.png",
			 ),
			 '6'=>array(
				 'classid'=>"6",
				 'classname'=>"鞋品",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/8.png",
			 ),
			 '7'=>array(
				 'classid'=>"7",
				 'classname'=>"箱包",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/5.png",
			 ),
			 '8'=>array(
				 'classid'=>"8",
				 'classname'=>"儿童",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/55.png",
			 ),

			 '9'=>array(
				 'classid'=>"9",
				 'classname'=>"母婴",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/4.png",
			 ),

			 '10'=>array(
				 'classid'=>"10",
				 'classname'=>"居家",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/7.png",
			 ),

			 '11'=>array(
				 'classid'=>"11",
				 'classname'=>"美食",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/9.png",
			 ),
			 '12'=>array(
				 'classid'=>"12",
				 'classname'=>"数码",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/sm.png",
			 ),

			 '13'=>array(
				 'classid'=>"13",
				 'classname'=>"家电",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/11.png",
			 ),
			 '14'=>array(
				 'classid'=>"15",
				 'classname'=>"车品",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/cp.png",
			 ),
			 '15'=>array(
				 'classid'=>"16",
				 'classname'=>"文体",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/10.png",
			 ),
			 '16'=>array(
				 'classid'=>"14",
				 'classname'=>"其它",
				 'icon'=>DI()->config->get('sys.filepath')."/icon/12.png",
			 )
		 );
		 return $rs;

	 }

	/**
	 * 淘宝客模块
	 * @desc 首页推荐
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_id 分页标示
	 * @return object list 数据列表
	 * @return string list[].product_id 自增ID
	 * @return string list[].itemid 商品id
	 * @return string list[].itempic 图片
	 * @return string list[].itemtitle 标题
	 * @return string list[].itemshorttitle 短标题
	 * @return string list[].itemdesc 商品描述
	 * @return string list[].itemsale 月销量
	 * @return string list[].itemprice 在售价
	 * @return string list[].itemendprice 券后价
	 * @return string list[].couponmoney 优惠券金额
	 * @return string list[].shoptype 店铺类型 天猫店（B）淘宝店（C）
	 * @return string list[].videourl 视频地址
	 * @return string list[].couponexplain 优惠券使用条件
	 * @return string list[].couponstarttime 优惠券开始时间
	 * @return string list[].couponendtime 优惠券结束时间
	 * @return string list[].is_brand 是否为品牌产品（1是）
	 * @return string list[].couponurl 优惠券链接
	 * @return string list[].activityid 优惠券ID
	 * @return string list[].tklToken 淘口令Token
	 */
	public  function Index(){

		$rs = array('code' => 0, 'msg' => '成功','min_id'=>'', 'list' => array());
		$domain = new Domain_Tk();
		$data = $domain -> getIndexTuiJian($this->min_id,$this->back);
		if($data['code']){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['min_id'] = $data['min_id'];
			$rs['list'] = $data['list'];
		}
		return $rs;
	}

	/**
	 * 淘宝客模块
	 * @desc 分类列表商品
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_id 分页标示
	 * @return object list 数据列表
	 * @return string list[].product_id 自增ID
	 * @return string list[].itemid 商品id
	 * @return string list[].itempic 图片
	 * @return string list[].itemtitle 标题
	 * @return string list[].itemshorttitle 短标题
	 * @return string list[].itemdesc 商品描述
	 * @return string list[].itemsale 月销量
	 * @return string list[].itemprice 在售价
	 * @return string list[].itemendprice 券后价
	 * @return string list[].couponmoney 优惠券金额
	 * @return string list[].shoptype 店铺类型 天猫店（B）淘宝店（C）
	 * @return string list[].videourl 视频地址
	 * @return string list[].couponexplain 优惠券使用条件
	 * @return string list[].couponstarttime 优惠券开始时间
	 * @return string list[].couponendtime 优惠券结束时间
	 * @return string list[].is_brand 是否为品牌产品（1是）
	 * @return string list[].couponurl 优惠券链接
	 * @return string list[].activityid 优惠券ID
	 * @return string list[].tklToken 淘口令Token
	 */
	public  function Lists(){

		$rs = array('code' => 0, 'msg' => '成功','min_id'=>'', 'list' => array());
		$domain = new Domain_Tk();
		$data = $domain -> getLists($this->cid,$this->sort,$this->price_min,$this->price_max,$this->sale_min,$this->sale_max,$this->coupon_min,$this->coupon_max,$this->min_id,$this->back);
		if($data['code']){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['min_id'] = $data['min_id'];
			$rs['list'] = $data['list'];
		}

		return $rs;
	}

	/**
	 * 淘宝客模块
	 * @desc 商品筛选
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_id 分页标示
	 * @return object list 数据列表
	 * @return string list[].product_id 自增ID
	 * @return string list[].itemid 商品id
	 * @return string list[].itempic 图片
	 * @return string list[].itemtitle 标题
	 * @return string list[].itemshorttitle 短标题
	 * @return string list[].itemdesc 商品描述
	 * @return string list[].itemsale 月销量
	 * @return string list[].itemprice 在售价
	 * @return string list[].itemendprice 券后价
	 * @return string list[].couponmoney 优惠券金额
	 * @return string list[].shoptype 店铺类型 天猫店（B）淘宝店（C）
	 * @return string list[].videourl 视频地址
	 * @return string list[].couponexplain 优惠券使用条件
	 * @return string list[].couponstarttime 优惠券开始时间
	 * @return string list[].couponendtime 优惠券结束时间
	 * @return string list[].is_brand 是否为品牌产品（1是）
	 * @return string list[].couponurl 优惠券链接
	 * @return string list[].activityid 优惠券ID
	 * @return string list[].tklToken 淘口令Token
	 */
	public  function TypeLists(){

		$rs = array('code' => 0, 'msg' => '成功','min_id'=>'', 'list' => array());
		$domain = new Domain_Tk();
		$data = $domain -> getTypeLists($this->typeid,$this->cid,$this->sort,$this->price_min,$this->price_max,$this->sale_min,$this->sale_max,$this->coupon_min,$this->coupon_max,$this->min_id,$this->back);
		if($data['code']){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['min_id'] = $data['min_id'];
			$rs['list'] = $data['list'];
		}

		return $rs;
	}

	/**
	 * 淘宝客模块
	 * @desc 搜索
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_id 分页标示
	 * @return object list 数据列表
	 * @return string list[].product_id 自增ID
	 * @return string list[].itemid 商品id
	 * @return string list[].itempic 图片
	 * @return string list[].itemtitle 标题
	 * @return string list[].itemshorttitle 短标题
	 * @return string list[].itemdesc 商品描述
	 * @return string list[].itemsale 月销量
	 * @return string list[].itemprice 在售价
	 * @return string list[].itemendprice 券后价
	 * @return string list[].couponmoney 优惠券金额
	 * @return string list[].shoptype 店铺类型 天猫店（B）淘宝店（C）
	 * @return string list[].videourl 视频地址
	 * @return string list[].couponexplain 优惠券使用条件
	 * @return string list[].couponstarttime 优惠券开始时间
	 * @return string list[].couponendtime 优惠券结束时间
	 * @return string list[].is_brand 是否为品牌产品（1是）
	 * @return string list[].couponurl 优惠券链接
	 * @return string list[].activityid 优惠券ID
	 * @return string list[].tklToken 淘口令Token
	 */
	public  function Search(){

		$rs = array('code' => 0, 'msg' => '成功','tb_p'=>'','min_id'=>'', 'list' => array());
		$domain = new Domain_Tk();
		$data = $domain -> getSearchLists($this->keyword,$this->tb_p,$this->sort,$this->is_tmall,$this->is_coupon,$this->min_id,$this->back);
		if($data['code']){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['min_id'] = $data['min_id'];
			$rs['tb_p'] = $data['tb_p'];
			$rs['list'] = $data['list'];
		}

		return $rs;
	}

	/**
	 * 淘宝客模块
	 * @desc 热门搜索词
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_id 分页标示
	 * @return object list 数据列表
	 */
	public function HotKey(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Tk();
		$rs['list'] = $domain -> getHotKey();
		return $rs;
	}

	/**
	 * 淘宝客模块
	 * @desc 详情（<font color=red>如详情没有返回数据，所有数据请请从列表中获取</font>）
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_id 分页标示
	 * @return object list 数据列表
	 * @return string list[].itemid 商品id
	 * @return string list[].itempic 图片
	 * @return string list[].itemtitle 标题
	 * @return string list[].itemshorttitle 短标题
	 * @return string list[].itemdesc 从列表获取
	 * @return string list[].itemsale 月销量
	 * @return string list[].itemprice 在售价
	 * @return string list[].itemendprice 券后价
	 * @return string list[].couponmoney 优惠券金额
	 * @return string list[].shoptype 店铺类型 天猫店（B）淘宝店（C）
	 * @return string list[].videourl 从列表获取
	 * @return string list[].couponexplain 优惠券使用条件
	 * @return string list[].couponstarttime 优惠券开始时间
	 * @return string list[].couponendtime 优惠券结束时间
	 * @return string list[].couponurl 优惠券链接
	 * @return string list[].small_images 图集
	 * @return string list[].shareurl 分享链接
	 * @return string list[].contenturl 详情内容
	 * @return string list[].tklToken 淘口令Token
	 */
	public  function Detail(){

		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Tk();
		$data = $domain -> getDetail($this->itemid,$this->activityid);
		if($data['code']){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
			$rs['list'] = $data['list'];
		}else{
			$rs['list'] = $data['list'];
		}

		return $rs;
	}

	/**
	 * 淘宝客模块
	 * @desc 淘口令
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_id 分页标示
	 * @return object list[].tkl 口令
	 * @return object list[].content 文案
	 */
	public  function Tkl(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Tk();
		$data = $domain -> getTkl($this->tklToken);
		if($data['code']){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['list'] = $data['list'];
		}

		return $rs;
	}

	

}
