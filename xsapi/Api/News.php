<?php
/**
 * 娱乐接口服务类
 */

class Api_News extends PhalApi_Api {

	public function getRules() {
        return array(
			'Lists' => array(
				'classid' => array('name' => 'classid', 'type' => 'string', 'require' => true,'desc'=>'分类ID'),
				'min_time' => array('name' => 'min_time', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'分页标示'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数'),
			),

			'Search' => array(
				'keywords' => array('name' => 'keywords', 'type' => 'string', 'require' => true,'desc'=>'搜索关键词'),
				'min_time' => array('name' => 'min_time', 'type' => 'string','default'=>0, 'require' => false,'desc'=>'分页标示'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数'),
			),

			'Detail' => array(
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'信息id'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'用户登录后的token')
			),

			'Video' => array(
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'信息id'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'用户登录后的token')
			),

			'SetLike' => array(
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'信息id'),
				'linkid' => array('name' => 'linkid', 'type' => 'string', 'require' => true,'desc'=>'1喜欢 2不喜欢'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'用户登录后的token')
			),

			'InfoComments' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'登陆后保存的token,验证当前用户是否点赞评论'),
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'信息ID'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位评论id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>5, 'require' => false,'desc'=>'每页返回条数')
			),

			'VoteComment' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token,验证当前用户是否点赞评论'),
				'cid' => array('name' => 'cid', 'type' => 'string', 'require' => true,'desc'=>'当前评论ID'),
			),

			'CommentDetail' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'登陆后保存的token,验证当前用户是否点赞评论'),
				'cid' => array('name' => 'cid', 'type' => 'string', 'require' => true,'desc'=>'当前评论id'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位评论id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>5, 'require' => false,'desc'=>'每页返回条数')
			),

			'TjInfoComments' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token,收费章节必传'),
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'信息ID'),
				'pid' => array('name' => 'pid', 'type' => 'string', 'require' => false,'desc'=>'评论详情id'),
				'mentions' => array('name' => 'mentions', 'type' => 'string', 'require' => false,'desc'=>'被@用户的json进行URLEncode编码的参数（uid,nickname参数）'),
				'msg' => array('name' => 'msg', 'type' => 'string','min' => 1,'max' => 200, 'require' => true,'desc'=>'评论内容,限制200字')
			),

			'InfoShare' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'info_id' => array('name' => 'info_id', 'type' => 'string', 'require' => true,'desc'=>'信息ID')
			),

        );

	}


	/**
	 * 娱乐模块
	 * @desc 栏目分类
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function Category(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$rs['list'] = $domain -> getCategory();
		return $rs;
	}


	/**
	 * 娱乐模块
	 * @desc 栏目分类信息,下拉刷新
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_time 分页标示
	 * @return object list 数据列表
	 * @return string list[list].id 信息id
	 * @return string list[list].title 标题
	 * @return string list[list].introduction 简介
	 * @return string list[list].source_name 来源
	 * @return string list[list].tag 标签
	 * @return string list[list].cover 图片
	 * @return string list[list].cover_show_type 图片展示类型（ 4视频图 3多图 2单大图 1右一小图）
	 * @return string list[list].atlas_list 图集
	 * @return string list[list].content_type 内容展现形式 （12图集 3视频 1文章）
	 * @return string list[list].说明 content_type=1 包含 cover_show_type<=3
	 * @return string list[list].tips 提示语
	 * @return string list[list].tips_color 提示语颜色
	 * @return string list[list].play_time 视频时长
	 * @return string list[list].video_value 视频ID
	 * @return string list[list].read_count 阅读量
	 * @return string list[list].comment_count 评论量
	 * @return string list[list].newstime 发布时间
	 * @return object adlist 广告列表
	 * @return string list[adlist].adid 广告id
	 * @return string list[adlist].adtitle 广告标语
	 * @return string list[adlist].changet 广告展现类型（1长图图片 2小图广告 3视频广告 4文字广告）
	 * @return string list[adlist].picurl 图片地址
	 * @return string list[adlist].pic_width 宽
	 * @return string list[adlist].pc_height 高
	 * @return string list[adlist].adurl 广告链接
	 * @return string list[adlist].description 介绍
	 */
	public  function Lists(){
		$rs = array('code' => 0, 'msg' => '成功','min_time'=>0, 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getLists($this->classid,$this->min_time,$this->pagesize);
		if($data['code']==1){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['min_time'] = $data['min_time'];
			$rs['list']['list'] = $data['list'];
			$rs['list']['adlist'] = $data['adlist'];
		}

		return $rs;
	}


	/**
	 * 娱乐模块
	 * @desc 搜索
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return string min_time 分页标示
	 * @return object list 数据列表
	 * @return string list.id 信息id
	 * @return string list.title 标题
	 * @return string list.introduction 简介
	 * @return string list.source_name 来源
	 * @return string list.tag 标签
	 * @return object list[cover] 图片
	 * @return string list.cover_show_type 图片展示类型（ 4视频图 3多图 2单大图 1右一小图）
	 * @return object list[atlas_list] 图集
	 * @return string list.content_type 内容展现形式 （12图集 3视频 1文章）
	 * @return string list.说明 content_type=1 包含 cover_show_type<=3
	 * @return string list.tips 提示语
	 * @return string list.tips_color 提示语颜色
	 * @return string list.play_time 视频时长
	 * @return string list.video_value 视频ID
	 * @return string list.read_count 阅读量
	 * @return string list.comment_count 评论量
	 * @return string list.newstime 发布时间
	 * @return object list[video_url][] 视频
	 * @return string list[video_url].clarity 视频清晰度
	 * @return string list[video_url].url 视频地址
	 * @return string list[video_url].size 视频大小
	 * @return string list[video_url].fps 帧/秒
	 * @return string list[video_url].bitrate 比特率
	 * @return string list.content 内容
	 * @return object adlist 广告列表
	 * @return string list[adlist].adid 广告id
	 * @return string list[adlist].adtitle 广告标语
	 * @return string list[adlist].changet 广告展现类型（1长图图片 2小图广告 3视频广告 4文字广告）
	 * @return string list[adlist].picurl 图片地址
	 * @return string list[adlist].pic_width 宽
	 * @return string list[adlist].pc_height 高
	 * @return string list[adlist].adurl 广告链接
	 * @return string list[adlist].description 介绍
	 */
	public  function Search(){
		$rs = array('code' => 0, 'msg' => '成功','min_time'=>0, 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getSearch($this->keywords,$this->min_time,$this->pagesize);
		if($data['code']==1){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['min_time'] = $data['min_time'];
			$rs['list']['list'] = $data['list'];
			$rs['list']['adlist'] = $data['adlist'];
		}

		return $rs;
	}

	/**
	 * 娱乐模块
	 * @desc 详情
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 分页标示
	 * @return object list 数据列表
	 * @return string list.id 信息id
	 * @return string list.title 标题
	 * @return string list.introduction 简介
	 * @return string list.source_name 来源
	 * @return string list.tag 标签
	 * @return string list.cover 图片
	 * @return string list.cover_show_type 图片展示类型（ 4视频图 3多图 2单大图 1右一小图）
	 * @return string list.atlas_list 图集
	 * @return string list.content_type 内容展现形式 （12图集 3视频 1文章）
	 * @return string list.说明 content_type=1 包含 cover_show_type<=3
	 * @return string list.tips 提示语
	 * @return string list.tips_color 提示语颜色
	 * @return string list.play_time 视频时长
	 * @return string list.video_value 视频ID
	 * @return string list.read_count 阅读量
	 * @return string list.comment_count 评论量
	 * @return string list.can_comment 是否开启评论 1是
	 * @return string list.show_comment 是否显示评论 1是
	 * @return string list.like_num 喜欢
	 * @return string list.unlike_num 不喜欢
	 * @return string list.user_islike 0没操作 1喜欢 2不喜欢
	 * @return string list.newstime 发布时间
	 * @return string list.is_read 是否阅读过 1是
	 * @return string list.min_time 获取信息时间
	 * @return string list.min_token token验证阅读时间
	 * @return string list.min_second 阅读时长，单位秒（超过此时长方可获得奖励）
	 * @return object recommendlist 推荐信息
	 * @return object adlist 广告列表
	 * @return string list[adlist].adid 广告id
	 * @return string list[adlist].adtitle 广告标语
	 * @return string list[adlist].changet 广告展现类型（1长图图片 2小图广告 3视频广告 4文字广告）
	 * @return string list[adlist].picurl 图片地址
	 * @return string list[adlist].pic_width 宽
	 * @return string list[adlist].pc_height 高
	 * @return string list[adlist].adurl 广告链接
	 * @return string list[adlist].description 介绍
	 */
	public function Detail(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getDetail($this->info_id,$this->token);
		if($data['code']==1){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['list'] = $data['list'];
		}
		return $rs;
	}

	/**
	 * 娱乐模块
	 * @desc 获取视频地址
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 分页标示
	 * @return object list 数据列表
	 * @return string list.play_time 视频时长
	 * @return string list.video_value 视频ID
	 * @return string list.cover 封面
	 * @return object list[video_url] 数据列表
	 * @return string list[video_url].clarity 视频清晰度
	 * @return string list[video_url].url 视频地址
	 * @return string list[video_url].size 视频大小
	 * @return string list[video_url].fps 帧/秒
	 * @return string list[video_url].bitrate 比特率
	 * @return string list.is_read 是否阅读过 1是
	 * @return string list.min_time 获取信息时间
	 * @return string list.min_token token验证
	 * @return string list.min_second 阅读时长，单位秒（超过此时长方可获得奖励）
	 */
	public function Video(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getVideo($this->token,$this->info_id);
		$rs['list'] = $data;
		return $rs;
	}

	/**
	 * 娱乐模块
	 * @desc 点赞 1喜欢 2不喜欢
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 */
	public function SetLike(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getSetLike($this->token,$this->info_id,$this->linkid);
		if($data['code']==1){
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}else{
			$rs['msg']=$data['msg'];
			$rs['list'] = $data['list'];
		}
		return $rs;
	}


	/**
	 * 娱乐模块
	 * @desc  信息评论（信息评论不能@用户，有点赞和下级评论数，能进入评论详情）
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 评论id
	 * @return string list[].info_id 信息ID
	 * @return string list[].uid 用户id
	 * @return string list[].nickname 用户昵称
	 * @return string list[].avatar 头像
	 * @return string list[].replay 评论数
	 * @return string list[].zan 点赞数
	 * @return string list[].mentions 被@用户信息json
	 * @return string list[].is_vote 是否点赞评论 1是
	 * @return string list[].msg 内容
	 * @return string list[].addtime 评论时间
	 */
	public function InfoComments(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getInfoComments($this->token,$this->info_id,$this->id,$this->pagesize);
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
	 * 娱乐模块
	 * @desc  信息评论-给评论用户点赞
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function VoteComment(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getVoteComment($this->token,$this->cid);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 娱乐模块
	 * @desc  信息评论详情（信息评论能@用户，没有点赞和下级评论数）
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[comment] 当前评论详细
	 * @return string list[replay][] 评论列表
	 * @return string list[].id 评论id
	 * @return string list[].book_id 书ID
	 * @return string list[].uid 用户id
	 * @return string list[].nickname 用户昵称
	 * @return string list[].avatar 头像
	 * @return string list[].replay 评论数
	 * @return string list[].zan 点赞数
	 * @return string list[].mentions 被@用户信息json
	 * @return string list[].is_vote 是否点赞评论 1是
	 * @return string list[].msg 内容
	 */
	public function CommentDetail(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getCommentDetail($this->token,$this->cid,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 娱乐模块
	 * @desc  提交-信息评论
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function TjInfoComments(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getTjInfoComments($this->token,$this->info_id,$this->pid,$this->mentions,$this->msg);
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
	 * 娱乐模块
	 * @desc  信息详情介绍-分享
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function InfoShare(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_News();
		$data = $domain -> getInfoShare($this->token,$this->info_id);
		if($data['code']==0){
			$rs['msg']=$data['msg'];
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

}
