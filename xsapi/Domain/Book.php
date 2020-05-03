<?php

class Domain_Book {
    protected $num = 10;
    public function getCategoyry($channel){
        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $model = new Model_Book();
        $list = $model -> getCategoyry($channel);

        return array('code'=>0,'list'=>$list);
    }
    
    public function getRankType($channel){
        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $model = new Model_Book();
        $list = $model -> getRankType($channel);

        return array('code'=>0,'list'=>$list);
    }

    public function getAssociate($keywords){
        $keywords = trim($keywords);
        if(!$keywords){
            return array('code'=>1,'msg'=>'搜索关键词不能为空');
        }

        $model = new Model_Book();
        $list = $model -> getAssociate($keywords);
        return array('code'=>0,'list'=>$list);
    }

    public function getSearch($keywords,$id,$pagesize){

        $keywords = trim($keywords);
        if(!$keywords){
            return array('code'=>1,'msg'=>'搜索关键词不能为空');
        }

        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;

        $model = new Model_Book();
        $list = $model -> getSearch($keywords,$id,$pagesize);

        return array('code'=>0,'list'=>$list);
    }
    
    public function getRankList($channel,$typeid,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }

        $model = new Model_Book();
        $list = $model -> getRankList($channel,$typeid,$id,$pagesize);

        return array('code'=>0,'list'=>$list);
    }

    public function  getCateList($channel,$tagname,$progressid,$wordsid,$updatedid,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        if($tagname){$tagname = addslashes($tagname);}
        if($progressid){$progressid = intval($progressid)?intval($progressid):0;}
        if($wordsid){$wordsid = intval($wordsid)?intval($wordsid):0;}
        if($updatedid){$updatedid = intval($updatedid)?intval($updatedid):0;}

        $model = new Model_Book();
        $list = $model -> getCateList($channel,$tagname,$progressid,$wordsid,$updatedid,$id,$pagesize);

        return array('code'=>0,'list'=>$list);
    }
    
    public function  getMainfight($channel,$id,$pagesize){
        $id = intval($id)?intval($id):1;
		$pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }

        $model = new Model_Book();
        $list = $model -> getMainfight($channel,$id,$pagesize);
   
        return array('code'=>0,'list'=>$list);
    }

    public function  getHandpick($channel,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;

        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }

        $model = new Model_Book();
        $list = $model -> getHandpick($channel,$id,$pagesize);
        return array('code'=>0,'list'=>$list);
    }

    public function  getBookend($channel,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;

        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }

        $model = new Model_Book();
        $list = $model -> getBookend($channel,$id,$pagesize);

        return array('code'=>0,'list'=>$list);
    }

    public function  getShortBook($channel,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;

        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }

        $model = new Model_Book();
        $list = $model -> getShortBook($channel,$id,$pagesize);

        return array('code'=>0,'list'=>$list);
    }

    public function  getNewBook($channel,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;

        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }

        $model = new Model_Book();
        $list = $model -> getNewBook($channel,$id,$pagesize);

        return array('code'=>0,'list'=>$list);
    }

    public function  getBookList($channel,$type,$id,$pagesize){
        if($type=="Mainfight"){
            return $this->getMainfight($channel,$id,$pagesize);
        }elseif($type=="Handpick"){
            return $this->getHandpick($channel,$id,$pagesize);
        }elseif($type=="Bookend"){
            return $this->getBookend($channel,$id,$pagesize);
        }elseif($type=="ShortBook"){
            return $this->getShortBook($channel,$id,$pagesize);
        }elseif($type=="NewBook"){
            return $this->getNewBook($channel,$id,$pagesize);
        }elseif($type=="RankList"){
            return $this->getRankList($channel,4,$id,$pagesize);
        }else{
            return array('code'=>0,'list'=>array());
        }
    }


    public function getBookHome($channel){
        $channel = intval($channel);
        if($channel<1 || $channel>2){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $model = new Model_Book();
        $list = $model -> getBookHome($channel);

        return array('code'=>0,'list'=>$list);
    }

    public function  getVIPNewBook($id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;

        $model = new Model_Book();
        $list = $model -> getVIPNewBook($id,$pagesize);

        return array('code'=>0,'list'=>$list);
    }

    public function getBookInfo($id,$token){
        $id = intval($id);
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $uid=0;
        if($token){
            $user = new Model_User();
            $auth = $user->checkToken($token);
            if ($auth['code'] > 0) {
                return $auth;
            }
            $uid = $auth['uid'];
        }

        $model = new Model_Book();
        $list = $model -> getBookInfo($id,$uid);
        if(!$list){
            return array('code'=>1,'msg'=>"未找到书本信息");
        }else{
            return array('code'=>0,'list'=>$list);
        }

    }
    
    public function getBookSetLike($token,$id){
        $id = intval($id);
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $model = new Model_Book();
        $binfo = $model -> getBookById($id);
        if(!$binfo){
            return array('code'=>1,'msg'=>"未找到书本信息");
        }

        $zinfo = $model -> getBookZanInfo($auth['uid'],$id);
        if($zinfo){
            return array('code'=>0,'msg'=>"已赞",'list'=>array('like_num'=>$binfo['like_num']));
        }

        $param = array('service'=>"Asyn.BookSetLike",'book_id'=>$id);
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);
        $like_num = $binfo['like_num']+1;
        return array('code'=>0,'msg'=>"已赞",'list'=>array('like_num'=>$like_num));
    }

    public function getBookGift(){
        $model = new Model_Book();
        return $model->getBookGift();
    }

    public function getBookGiftHistory($book_id,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $book_id = intval($book_id);
        if($book_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        
        $model = new Model_Book();
        $rs= $model->getBookGiftHistory($book_id,$id,$pagesize);
        return array('code'=>0,'list'=>$rs);
    }

    public function getTjBookGift($token,$book_id,$gift_id){
        $gift_id = intval($gift_id);
        $book_id = intval($book_id);
        if($gift_id<=0 || $book_id<=0 || !$token){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }

        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $model = new Model_Book();
        $binfo = $model->getBookById($book_id);
        if(!$binfo){
            return array('code'=>1,'msg'=>'书本信息不存在');
        }
        $ginfo = $model->getBookGiftById($gift_id);
        if(!$ginfo){
            return array('code'=>1,'msg'=>'打赏类型不存在');
        }
        $uinfo = $user->getuserInfo($auth['uid']);
        if($uinfo['balance']<$ginfo['discount_price']){
            return array('code'=>3,'msg'=>'余额不足');
        }
        //打赏成功，添加评论
        $canshu = array(
            'uid'=>$uinfo['uid'],
            'nickname'=>$uinfo['nickname'],
            'avatar'=>$uinfo['avatar'],
            'book_id'=>$book_id,
            'book_name'=>$binfo['book_name'],
            'gift_id'=>$gift_id,
            'gift_name'=>$ginfo['gift_name'],
            'gift_price'=>$ginfo['discount_price'],
            'gift_time'=>date("Y-m-d H:i:s")
        );
        $param = array('service'=>"Asyn.BookGift",'canshu'=>urlencode(json_encode($canshu)));
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        return array('code'=>0,'msg'=>'打赏成功');
    }
    
    public function getBookShare($token,$book_id){
        $book_id = intval($book_id);
        if($book_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        
        $model = new Model_Book();
        $binfo = $model->getBookById($book_id);
        if(!$binfo){
            return array('code'=>1,'msg'=>'书本信息不存在');
        }
        /*
        $cinfo = $model->getChapteFirst($book_id);
        if(!$cinfo){
            return array('code'=>1,'msg'=>'章节信息不存在');
        }*/

        $info = array(
            'title'=>"Hi~有朋友邀你一起读好书啦~ —— 《".$binfo['book_name']."》",
            'content'=>strip_tags($binfo['description']),
            'pic'=>$binfo['book_url'],
            'url'=>DI()->config->get('sys.xsapp.homeurl')."Text/shareBook/?uid=".$auth['uid']."&book_id=".$book_id
        );

        $rs[0] = array_merge(array('type'=>'weixin','enable'=>"1"),$info);
        $rs[1] = array_merge(array('type'=>'timeline','enable'=>"1"),$info);
        $rs[2] = array_merge(array('type'=>'qq','enable'=>"1"),$info);
        $rs[3] = array_merge(array('type'=>'qzone','enable'=>"1"),$info);
        $rs[4] = array_merge(array('type'=>'copy','enable'=>"1"),$info);

        return array('code'=>0,'list'=>$rs);

    }

    public function getBookComments($token,$book_id,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):5;
        $book_id = intval($book_id);
        if($book_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $uid=0;
        if($token){
            $user = new Model_User();
            $auth = $user->checkToken($token);
            $uid=$auth['uid']?$auth['uid']:0;
        }

        $model = new Model_Book();
        $list = $model->getBookComments($uid,$book_id,$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$list);
        
    }

    public function getVoteComment($token,$id,$act){
        $id = intval($id)?intval($id):0;
        $act = intval($id)?intval($act):1;
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $param = array('service'=>"Asyn.VoteComment",'id'=>$id,'uid'=>$auth['uid'],'act'=>$act);
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        return array('code'=>0,'msg'=>'成功','list'=>array());
    }

    public function getCommentDetail($token,$cid,$id,$pagesize){
        $cid = intval($id)?intval($cid):0;
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):5;
        if($cid<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $uid=0;
        if($token) {
            $user = new Model_User();
            $auth = $user->checkToken($token);
            if ($auth['code'] > 0) {
                return $auth;
            }
            $uid= $auth['uid'];
        }

        $model = new Model_Book();
        $list = $model->getCommentDetail($uid,$cid,$id,$pagesize);
        return array('code'=>0,'list'=>$list);
    }

    public function getChapteComments($token,$book_id,$chapte_id,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):5;
        $book_id = intval($book_id);
        $chapte_id = intval($chapte_id);
        if($book_id<=0 || $chapte_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $uid=0;
        if($token){
            $user = new Model_User();
            $auth = $user->checkToken($token);
            $uid=$auth['uid']?$auth['uid']:0;
        }

        $model = new Model_Book();
        $list = $model->getChapteComments($uid,$book_id,$chapte_id,$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$list);

    }

    public function getTjBookComments($token,$book_id,$chapte_id,$star,$pid,$mentions,$msg){
        $book_id = intval($book_id);
        $chapte_id = intval($chapte_id);
        $star = intval($star)?intval($star):5;
        $pid = intval($pid);
        $msg = trim($msg);
        if($book_id<=0 || $msg==""){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        if(strlen($msg)>200){
            return array('code'=>1,'msg'=>'评论内容太长');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }



        $model = new Model_Book();
        $binfo = $model -> getBookById($book_id);
        if(!$binfo){
            return array('code'=>1,'msg'=>"未找到书本信息");
        }

        //评论上级用户
        if($pid){
            $pl = $model->CheckCommentExist($pid);
            if(!$pl){
                return array('code'=>1,'msg'=>"被评论的信息不存在");
            }
        }

        //评论章节
        if($chapte_id){
            $cinfo = $model->CheckChapteExist($book_id,$chapte_id);
            if(!$cinfo){
                return array('code'=>1,'msg'=>"未找到章节信息");
            }
        }
        
        $pl = $model->CheckCommentExistByMsg($auth['uid'],$book_id,$msg);
        if($pl){
            return array('code'=>1,'msg'=>"抱歉，评论内容与其它重复");
        }

        //@用户信息
        if($mentions){
            $dms= json_decode($mentions,true);
            if(!$dms || empty($dms)  || !is_array($dms)){
                return array('code'=>1,'msg'=>'被@用户信息参数错误');
            }
            if(!$dms['uid'] || !$dms['nickname']){
                return array('code'=>1,'msg'=>'被@用户信息参数错误');
            }

        }

        $uinfo = $user->getuserInfo($auth['uid']);
        $data = array(
            'uid'=>$auth['uid'],
            'book_id'=>$book_id,
            'chapte_id'=>$chapte_id,
            'nickname'=>$uinfo['nickname'],
            'avatar'=>$uinfo['avatar'],
            'pid'=>$pid,
            'mentions'=>$mentions?$mentions:"",
            'star'=>$star,
            'replay'=>0,
            'zan'=>0,
            'msg'=>$msg,
            'addtime'=>date("Y-m-d H:i:s")
        );
        $param = array('service'=>"Asyn.BookComments",'param'=>urlencode(json_encode($data)));
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);
        $like_num = $binfo['like_num']+1;
        return array('code'=>0,'msg'=>"评论成功",'list'=>array());

    }
    
    public function getBookSimilar($id){
        $id = intval($id);
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $model = new Model_Book();
        $binfo = $model -> getBookById($id);
        if(!$binfo){
            return array('code'=>1,'msg'=>"未找到书本信息");
        }
        
        $list =  $model->getBookSimilar($binfo['book_type'],6);
        return array('code'=>0,'list'=>$list);
    }

    public function getPersonSimilar($id){
        $id = intval($id);
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $model = new Model_Book();
        $binfo = $model -> getBookById($id);
        if(!$binfo){
            return array('code'=>1,'msg'=>"未找到书本信息");
        }

        $list = $model->getPersonSimilar(6);
        return array('code'=>0,'list'=>$list);
    }

    public function getChapteList($book_id){
        $book_id = intval($book_id);
        if($book_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $model = new Model_Book();
        $binfo = $model->CheckBookExist($book_id);
        if(!$binfo){
            return array('code'=>1,'msg'=>'书本不存在');
        }
        $cinfo = $model->getChapteList($book_id);
        return array('code'=>0,'list'=>$cinfo);
    }
    
    public function AddBookSelf($token,$book_id){
        $book_id = intval($book_id);
        if($book_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $model = new Model_Book();
        return $model -> AddBookSelf($auth['uid'],$book_id);
    }

    public  function getChapteInfo($token,$book_id,$chapte_id,$sdtoken){
        $book_id = intval($book_id)?intval($book_id):0;
        $chapte_id = intval($chapte_id)?intval($chapte_id):0;
        if($book_id<=0 || $chapte_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $model = new Model_Book();
        $list = $model -> getChapteInfo($token,$book_id,$chapte_id,$sdtoken);

        if(!$list){
            return array('code'=>1,'msg'=>"未找到相关章节",'list'=>array());
        }else{
            if($list['code']==1){
                return array('code'=>1,'msg'=>$list['msg'],'list'=>array());
            }elseif($list['code']==2){
                return array('code'=>2,'msg'=>$list['msg'],'list'=>array());
            }elseif($list['code']==3){
                return array('code'=>3,'msg'=>"VIP章节，需购买方可阅读",'list'=>$list['data']);
            }else {
                return array('code' => 0,'msg'=>"成功", 'list' => $list);
            }
        }
    }
    
    public function getBuyChapte($token,$book_id,$chapte_id){
        $book_id = intval($book_id)?intval($book_id):0;
        $chapte_id = intval($chapte_id)?intval($chapte_id):0;
        if($book_id<=0 || $chapte_id<=0 || $token==''){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']){
            return $auth;
        }
        $model = new Model_Book();
        $cinfo = $model->CheckChapteExist($book_id,$chapte_id);
        if(!$cinfo){
            return array('code'=>1,'msg'=>'书本章节不存在'); 
        }

        $uinfo = $user->getuserInfo($auth['uid']);
        if(!$uinfo){
            return $auth;
        }
        //vip用户无需购买
        if($uinfo['is_vip']){
            return array('code'=>0);
        }

        //免费章节无需购买
        if(($cinfo['is_vip']==1 && $cinfo['saleprice']<=0) || $cinfo['is_vip']==0){
            return array('code'=>0);
        }
        
        //余额不足
        if($uinfo['balance'] < $cinfo['saleprice']){
            return array('code'=>3,'msg'=>'余额不足');
        }

        //购买，更新账户，添加购买章节记录
        $u = $user->getEditUserInfo($auth['uid'],array('balance'=>new NotORM_Literal('balance - '.$cinfo['saleprice'])));
        if($u!==false){
            $data = array('uid'=>$auth['uid'],'book_id'=>$book_id,'chapte_id'=>$chapte_id,'saleprice'=>$cinfo['saleprice'],'buytime'=>time());
            $id = $model->AddChapteBuy($data);
        }

        return array('code'=>0);
    }

    //小说订单查询
    public function getCheckOrder($token,$orderid){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $model = new Model_Book();
        $rs = $model->getCheckOrder($auth['uid'],addslashes($orderid));
        if(!$rs){
            return array('code'=>1,'msg'=>'订单信息不存在','list'=>array());
        }else{
            return array('code'=>0,'msg'=>'成功','list'=>$rs);
        }
    }

}
