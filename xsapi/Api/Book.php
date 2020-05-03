<?php
/**
 * 书城接口服务类
 */

class Api_Book extends PhalApi_Api {

	public function getRules() {
        return array(
			'Category' => array(
				'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
			),
			'CateList' => array(
				'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
				'tagname' => array('name' => 'tagname', 'type' => 'string', 'require' => false,'desc'=>'分类名'),
				'progressid' => array('name' => 'progressid', 'type' => 'int', 'require' => false,'desc'=>'进度id'),
				'wordsid' => array('name' => 'wordsid', 'type' => 'int', 'require' => false,'desc'=>'字数id'),
				'updatedid' => array('name' => 'updatedid', 'type' => 'int', 'require' => false,'desc'=>'更新id'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'Associate' => array(
				'keywords' => array('name' => 'keywords', 'type' => 'string', 'require' => true,'desc'=>'关键词'),
			),

			'Search' => array(
				'keywords' => array('name' => 'keywords', 'type' => 'string', 'require' => true,'desc'=>'关键词'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'RankType' => array(
				'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
			),
			'RankList' => array(
				'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
				'typeid' => array('name' => 'typeid', 'type' => 'string', 'require' => true,'desc'=>'排行榜typeid'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),
          	'Mainfight' => array(
			    'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
                'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
            ),
			'Handpick' => array(
				'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),
			'Bookend' => array(
				'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'ShortBook' => array(
				'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'NewBook' => array(
				'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'BookHome'=>array(
                'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
            ),

            'BookList' => array(
                'channel' => array('name' => 'channel', 'type' => 'string', 'require' => true,'desc'=>'1男2女'),
                'type' => array('name' => 'type', 'type' => 'string', 'require' => true,'desc'=>'类型参数'),
                'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
                'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
            ),

			'VIPNewBook' => array(
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位book_id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'BookInfo' => array(
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'登陆后保存的token')
			),

			'BookSetLike' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token,收费章节必传'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID')
			),

			'TjBookGift' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID'),
				'gift_id' => array('name' => 'gift_id', 'type' => 'string', 'require' => true,'desc'=>'打赏类型ID')
			),

			'BookGiftHistory' => array(
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位打赏记录id'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>10, 'require' => false,'desc'=>'每页返回条数')
			),

			'BookShare' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID')
			),

			'BookComments' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'登陆后保存的token,验证当前用户是否点赞评论'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位评论id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>5, 'require' => false,'desc'=>'每页返回条数')
			),

			'VoteComment' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token,验证当前用户是否点赞评论'),
				'cid' => array('name' => 'cid', 'type' => 'string', 'require' => true,'desc'=>'当前评论ID'),
				'act' => array('name' => 'act', 'type' => 'string', 'require' => true,'default'=>1,'min' => 1,'max' => 2,'desc'=>'当前操作1点赞，2取消'),
			),

			'CommentDetail' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'登陆后保存的token,验证当前用户是否点赞评论'),
				'cid' => array('name' => 'cid', 'type' => 'string', 'require' => true,'desc'=>'当前评论id'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位评论id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>5, 'require' => false,'desc'=>'每页返回条数')
			),

			'TjBookComments' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token,收费章节必传'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID'),
				'chapte_id' => array('name' => 'chapte_id', 'type' => 'string', 'require' => false,'desc'=>'章节ID,评论章节时用'),
				'star' => array('name' => 'star', 'type' => 'int','min' => 1,'max' => 5, 'require' => true,'desc'=>'1-5星级'),
				'pid' => array('name' => 'pid', 'type' => 'string', 'require' => false,'desc'=>'评论详情id'),
				'mentions' => array('name' => 'mentions', 'type' => 'string', 'require' => false,'desc'=>'被@用户的json进行URLEncode编码的参数（uid,nickname参数）'),
				'msg' => array('name' => 'msg', 'type' => 'string','min' => 1,'max' => 200, 'require' => true,'desc'=>'评论内容,限制200字')
			),

			'BookSimilar' => array(
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID')
			),

			'PersonSimilar' => array(
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID')
			),

			'AddBookSelf' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token,收费章节必传'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID')
			),

			'ChapteList' => array(
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID')
			),

			'ChapteInfo' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'登陆后保存的token,收费章节必传'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID'),
				'chapte_id' => array('name' => 'chapte_id', 'type' => 'string', 'require' => true,'desc'=>'chapte_id章节'),
				'sdtoken' => array('name' => 'sdtoken', 'type' => 'string', 'require' => false,'desc'=>'试读token')
			),
			'ChapteComments' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false,'desc'=>'登陆后保存的token,验证当前用户是否点赞评论'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID'),
				'chapte_id' => array('name' => 'chapte_id', 'type' => 'string', 'require' => true,'desc'=>'chapte_id 章节id'),
				'id' => array('name' => 'id', 'type' => 'string','default'=>1, 'require' => false,'desc'=>'分页,当前页最后一位评论id值'),
				'pagesize' => array('name' => 'pagesize', 'type' => 'string','default'=>5, 'require' => false,'desc'=>'每页返回条数')
			),

			'BuyChapte' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token,收费章节必传'),
				'book_id' => array('name' => 'book_id', 'type' => 'string', 'require' => true,'desc'=>'book_id 书ID'),
				'chapte_id' => array('name' => 'chapte_id', 'type' => 'string', 'require' => true,'desc'=>'chapte_id章节')
			),

			'CheckOrder' => array(
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true,'desc'=>'登陆后保存的token'),
				'orderid' => array('name' => 'orderid', 'type' => 'string', 'require' => true,'desc'=>'订单号')
			),

        );

	}


	/**
	 * 书城模块
	 * @desc  分类
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].tags 分类
	 * @return string list[].progress 进度
	 * @return string list[].nums 字数
	 * @return string list[].updated 更新
	 */
    public  function Category(){
        $rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Book();
        $data = $domain -> getCategoyry($this->channel);
        if($data['code']==0){
            $rs['list']['tags'] = $data['list'];
            $rs['list']['progress'] = array(array("keyid"=>'1','keyname'=>'连载中'),array("keyid"=>'2','keyname'=>'已完结'));
            $rs['list']['words'] = array(array("keyid"=>'1','keyname'=>'30万字以下'),array("keyid"=>'2','keyname'=>'30-100万字'),array("keyid"=>'3','keyname'=>'100万字以上'));
            $rs['list']['updated'] = array(array("keyid"=>'1','keyname'=>'今日'),array("keyid"=>'2','keyname'=>'三天内'),array("keyid"=>'3','keyname'=>'一周内'));
        }else{
            $rs['code']=1;
            $rs['msg']=$data['msg'];
        }
        return $rs;
    }

	/**
	 * 书城模块
	 * @desc  分类信息列表
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].book_type 标签
	 * @return string list[].book_status 0连载中，1已完结
	 * @return string list[].words 字数
	 * @return string list[].description 描述
	 */
	public  function CateList(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getCateList($this->channel,$this->tagname,$this->progressid,$this->wordsid,$this->updatedid,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 书城模块
	 * @desc  热门搜索词
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].tags tags词
	 */
	public  function hotSearch(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$rs['list'] = array('异能','都市','灵异','豪门','穿越');
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  关键词自动检索-最多返回12条
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 */
	public  function Associate(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getAssociate($this->keywords);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  搜索
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return object list 数据列表
	 * @return string list[].typeid 类型ID
	 * @return string list[].typename 类型名称
	 * @return string list[][list].book_id 书本ID
	 * @return string list[][list].book_name 书名
	 * @return string list[][list].author 作者
	 * @return string list[][list].book_url 书封面图
	 * @return string list[][list].book_type 标签
	 * @return string list[][list].book_status 0连载中，1已完结
	 * @return string list[][list].words 字数
	 * @return string list[][list].description 描述
	 */
	public  function Search(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getSearch($this->keywords,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  排行榜
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return object list 数据列表
	 * @return string list[].typeid 类型ID
	 * @return string list[].typename 类型名称
	 * @return string list[][list].book_id 书本ID
	 * @return string list[][list].book_name 书名
	 * @return string list[][list].author 作者
	 * @return string list[][list].book_url 书封面图
	 * @return string list[][list].book_type 标签
	 * @return string list[][list].book_status 0连载中，1已完结
	 * @return string list[][list].words 字数
	 * @return string list[][list].description 描述
	 */
	public  function RankType(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getRankType($this->channel);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  排行榜列表
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].tags 标签
	 * @return string list[].book_status 0连载中，1已完结
	 * @return string list[].description 描述
	 */
	public  function RankList(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getRankList($this->channel,$this->typeid,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

    /**
	 * 书城模块
     * @desc  本期主打
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
     * @return string list[].book_id 书本ID
     * @return string list[].book_name 书名
     * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].tags 标签
	 * @return string list[].book_status 0连载中，1已完结
     * @return string list[].description 描述
	 */
	 private  function Mainfight(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Book();
        $data = $domain -> getMainfight($this->channel,$this->id,$this->pagesize);
		 if($data['code']==0){
			 $rs['list'] = $data['list'];
		 }else{
			 $rs['code']=$data['code'];
			 $rs['msg']=$data['msg'];
		 }
		return $rs;
	 }

	/**
	 * 书城模块
	 * @desc  精选小说
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].tags 标签
	 * @return string list[].book_status 0连载中，1已完结
	 * @return string list[].description 描述
	 */
    private  function Handpick(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getHandpick($this->channel,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 书城模块
	 * @desc  完本畅读
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].tags 标签
	 * @return string list[].book_status 0连载中，1已完结
	 * @return string list[].description 描述
	 */
    private  function Bookend(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getBookend($this->channel,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 书城模块
	 * @desc  短篇小说
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].tags 标签
	 * @return string list[].book_status 0连载中，1已完结
	 * @return string list[].description 描述
	 */
    private  function ShortBook(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getShortBook($this->channel,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 书城模块
	 * @desc  新书推荐
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].tags 标签
	 * @return string list[].book_status 0连载中，1已完结
	 * @return string list[].description 描述
	 */
    private  function NewBook(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getNewBook($this->channel,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

    /**
     * 书城模块
     * @desc  男女书主页
     * @return int code 操作码 0表示成功，1表示失败
     * @return string msg 提示信息
     * @return object list 数据列表
     * @return string list[].Mainfight 本期主打
     * @return string list[].Handpick 精选小说
     * @return string list[].Bookend 完本畅读
     * @return string list[].ShortBook 短篇小说
     * @return string list[].NewBook 新书推荐
     * @return string list[].RankList 畅销书单排行
     */
    public  function BookHome(){
        $rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Book();
        $data = $domain -> getBookHome($this->channel);
        if($data['code']==0){
            $rs['list'] = $data['list'];
        }else{
            $rs['code']=$data['code'];
            $rs['msg']=$data['msg'];
        }
        return $rs;
    }

    /**
     * 书城模块
     * @desc  男女书主页-分类列表
     * @return int code 操作码 0表示成功，1表示失败
     * @return string msg 提示信息
     * @return object list 数据列表
     * @return string list[].book_id 书本ID
     * @return string list[].book_name 书名
     * @return string list[].author 作者
     * @return string list[].book_url 书封面图
     * @return string list[].tags 标签
     * @return string list[].book_status 0连载中，1已完结
     * @return string list[].description 描述
     */
    public  function BookList(){
        $rs = array('code' => 0, 'msg' => '成功', 'list' => array());
        $domain = new Domain_Book();
        $data = $domain -> getBookList($this->channel,$this->type,$this->id,$this->pagesize);
        if($data['code']==0){
            $rs['list'] = $data['list'];
        }else{
            $rs['code']=$data['code'];
            $rs['msg']=$data['msg'];
        }
        return $rs;
    }


	/**
	 * 书城模块
	 * @desc  VIP新书推荐
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 书封面图
	 * @return string list[].tags 标签
	 * @return string list[].book_status 0连载中，1已完结
	 * @return string list[].description 描述
	 */
	public  function VIPNewBook(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getVIPNewBook($this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  书本详情介绍
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].channel 男女书
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].words  总字数
	 * @return string list[].book_url 封面
	 * @return string list[].book_status 是否完结  0未完结 1已完结
	 * @return string list[].is_shelves 是否上架 1上架
	 * @return string list[].is_vip  需要vip   0免费 1收费
	 * @return string list[].like_num 点赞数
	 * @return string list[].plnum 评论数
	 * @return string list[].lastdotime 最后更新时间
	 * @return string list[].reward  打赏数量
	 * @return string list[].read_chapte 阅读章节历史，默认第一章
	 * @return string list[].isbookshelf 是否加入书架 0未加入 1已加入
	 * @return string list[].is_read  我是否阅读过
	 * @return string list[].book_type 类型
	 * @return string list[].new_chapte 新章节标题
	 * @return string list[].description 介绍
	 */
	public  function BookInfo(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getBookInfo($this->book_id,$this->token);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  书本详情介绍-点赞
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].like_num 点赞数
	 */
	public  function BookSetLike(){
		$rs = array('code' => 0, 'msg' => '点赞成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getBookSetLike($this->token,$this->book_id);
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
	 * 书城模块
	 * @desc  书本详情介绍-打赏
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 类型ID
	 * @return string list[].gift_name 类型
	 * @return string list[].gift_url 类型图
	 * @return string list[].price 原价
	 * @return string list[].discount_price 现价
	 */
	public function BookGift(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$rs['list'] = $domain -> getBookGift();
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  书本详情介绍-确定打赏
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录,3余额不足
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function TjBookGift(){
		$rs = array('code' => 0, 'msg' => '打赏成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getTjBookGift($this->token,$this->book_id,$this->gift_id);
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
	 * 书城模块
	 * @desc  书本详情介绍-打赏记录
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 打赏记录id
	 * @return string list[].gift_name 类型
	 * @return string list[].nickname 昵称
	 * @return string list[].avatar 头像
	 * @return string list[].gift_time 打赏时间
	 */
	public function BookGiftHistory(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getBookGiftHistory($this->book_id,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}

		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  书本详情介绍-分享
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function BookShare(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getBookShare($this->token,$this->book_id);
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
	 * 书城模块
	 * @desc  书本评论（书本评论不能@用户，有点赞和下级评论数，能进入评论详情）
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 评论id
	 * @return string list[].book_id 书ID
	 * @return string list[].uid 用户id
	 * @return string list[].nickname 用户昵称
	 * @return string list[].avatar 头像
	 * @return string list[].star 星级
	 * @return string list[].replay 评论数
	 * @return string list[].zan 点赞数
	 * @return string list[].mentions 被@用户信息json
	 * @return string list[].is_vote 是否点赞评论 1是
	 * @return string list[].msg 内容
	 * @return string list[].addtime 评论时间
	 */
	public function BookComments(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getBookComments($this->token,$this->book_id,$this->id,$this->pagesize);
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
	 * 书城模块
	 * @desc  书本评论-给评论用户点赞
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function VoteComment(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getVoteComment($this->token,$this->cid,$this->act);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  书本评论详情（书本评论能@用户，没有点赞和下级评论数）
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
	 * @return string list[].star 星级
	 * @return string list[].replay 评论数
	 * @return string list[].zan 点赞数
	 * @return string list[].mentions 被@用户信息json
	 * @return string list[].is_vote 是否点赞评论 1是
	 * @return string list[].msg 内容
	 */
	public function CommentDetail(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
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
	 * 书城模块
	 * @desc  提交-书本评论、章节评论
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function TjBookComments(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getTjBookComments($this->token,$this->book_id,$this->chapte_id,$this->star,$this->pid,$this->mentions,$this->msg);
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
	 * 书城模块
	 * @desc  书本详情介绍--同类推荐
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 封面
	 */
	public function BookSimilar(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getBookSimilar($this->book_id);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  书本详情介绍--看过这本书的人都在看、猜你喜欢
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].author 作者
	 * @return string list[].book_url 封面
	 */
	public function PersonSimilar(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getPersonSimilar($this->book_id);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}


	/**
	 * 书城模块
	 * @desc  当前书所有章节列表
	 * @return int code 操作码 0表示成功，1表示失败
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].chapte_id 章节id
	 * @return string list[].chapte_name 章节名
	 * @return string list[].is_vip  1收费章节
	 * @return string list[].sort_id 章节排序号
	 */
	public function  ChapteList(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getChapteList($this->book_id);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  添加书架
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public  function AddBookSelf(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> AddBookSelf($this->token,$this->book_id);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  章节详情介绍：收费章节需要验证token
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录，3需要购买（返回部分章节内容）
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].book_id 书本ID
	 * @return string list[].book_name 书名
	 * @return string list[].chapte_id 章节id
	 * @return string list[].chapte_name 章节名
	 * @return string list[].words  总字数
	 * @return string list[].content 章节内容
	 * @return string list[].saleprice 阅读币
	 * @return string list[].is_vip  需要vip   0免费 1收费
	 * @return string list[].previous_chapte 上一章节
	 * @return string list[].next_chapte  下一章节
	 * @return string list[].balance 账户剩余阅读币
	 * @return string list[].plnum 章节评论数
	 * @return string list[].min_token 验证试读时间（sdtoken存在才返回，否则为空）
	 */
	public  function ChapteInfo(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getChapteInfo($this->token,$this->book_id,$this->chapte_id,$this->sdtoken);
		$rs['code']=$data['code'];
		$rs['msg']=$data['msg'];
		$rs['list'] = $data['list'];
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  章节评论（章节评论能@用户，不显示点赞和下级评论数）
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].id 评论id
	 * @return string list[].book_id 书ID
	 * @return string list[].chapte_id 章节ID
	 * @return string list[].uid 用户id
	 * @return string list[].nickname 用户昵称
	 * @return string list[].avatar 头像
	 * @return string list[].star 星级
	 * @return string list[].replay 评论数
	 * @return string list[].zan 点赞数
	 * @return string list[].mentions 被@用户信息json
	 * @return string list[].is_vote 是否点赞评论 1是
	 * @return string list[].msg 内容
	 * @return string list[].addtime 评论时间
	 */
	public function ChapteComments(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getChapteComments($this->token,$this->book_id,$this->chapte_id,$this->id,$this->pagesize);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc  购买章节
	 * @return int code 操作码 0表示成功，1表示失败，2没有登录，3余额不足
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 */
	public function BuyChapte(){
		$rs = array('code' => 0, 'msg' => '购买章节成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getBuyChapte($this->token,$this->book_id,$this->chapte_id);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

	/**
	 * 书城模块
	 * @desc 订单查询
	 * @return int code 操作码 0表示成功，1表示失败,2没有登录
	 * @return string msg 提示信息
	 * @return object list 数据列表
	 * @return string list[].orderid 订单号
	 * @return string list[].title 说明
	 * @return string list[].price 付款金额
	 * @return string list[].is_vip 是否vip
	 * @return string list[].paytype 支付类型
	 * @return string list[].zt  -1取消订单 0未付款 1已付款
	 * @return string list[].paytime 付款时间
	 */

	public function CheckOrder(){
		$rs = array('code' => 0, 'msg' => '成功', 'list' => array());
		$domain = new Domain_Book();
		$data = $domain -> getCheckOrder($this->token,$this->orderid);
		if($data['code']==0){
			$rs['list'] = $data['list'];
		}else{
			$rs['code']=$data['code'];
			$rs['msg']=$data['msg'];
		}
		return $rs;
	}

}
