<?php

class Domain_News {

    protected $num = 10;
    public function  getCategory(){
        $model = new Model_News();
        return $model -> getCategory();
    }
    
    public function  getLists($classid,$min_time,$pagesize){
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $min_time = intval($min_time)?intval($min_time):time();
        $classid = intval($classid)?intval($classid):0;
        if($classid<=0){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }
        $model = new Model_News();
        return $model -> getLists($classid,$min_time,$pagesize);
    }

    public function  getSearch($keywords,$min_time,$pagesize){
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $min_time = intval($min_time)?intval($min_time):time();
        $keywords = trim($keywords);
        if(!$keywords){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }
        $model = new Model_News();
        return $model -> getSearch($keywords,$min_time,$pagesize);
    }

    public function getDetail($id,$token){
        $id = intval($id)?intval($id):0;
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }
        $model = new Model_News();
        return $model -> getDetail($id,$token);
    }

    public function getVideo($token,$id){
        $id = intval($id)?intval($id):0;
        $model = new Model_News();
        return $model -> getVideo($token,$id);
    }

    public function getSetLike($token,$id,$likeid){
        $id = intval($id)?intval($id):0;
        $likeid = intval($likeid)?intval($likeid):1;
        if($likeid<0 || $likeid>2){
            $likeid=1;
        }
        if($id<=0 || $token==''){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $model = new Model_News();
        $info = $model->CheckInfoById($id);
        if(!$info){
            return array('code'=>1,'msg'=>'点赞信息不存在');
        }
        
        $z = $model->getZan($id,$auth['uid']);
        if($z){
            return array('code'=>1,'msg'=>'您已赞过了','list'=>array());
        }

        $param = array('service'=>"Asyn.InfoZan",'uid'=>$auth['uid'],'info_id'=>$id,'zan'=>$likeid);
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);
        
        return array('code'=>0,'msg'=>'操作成功','list'=>array());
    }

    public function getInfoComments($token,$info_id,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $info_id = intval($info_id);
        if($info_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $uid=0;
        if($token){
            $user = new Model_User();
            $auth = $user->checkToken($token);
            $uid=$auth['uid']?$auth['uid']:0;
        }

        $model = new Model_News();
        $list = $model->getInfoComments($uid,$info_id,$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$list);

    }

    public function getVoteComment($token,$cid){
        $cid = intval($cid)?intval($cid):0;
        if($cid<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $model = new Model_News();
        if($model->getCommentById($cid,$auth['uid'])){
            return array('code'=>0,'msg'=>'您已赞过了','list'=>array());
        }

        $param = array('service'=>"Asyn.VoteCommentInfo",'id'=>$cid,'uid'=>$auth['uid']);
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

        $model = new Model_News();
        $list = $model->getCommentDetail($uid,$cid,$id,$pagesize);
        return array('code'=>0,'list'=>$list);
    }

    public function getTjInfoComments($token,$info_id,$pid,$mentions,$msg){
        $info_id = intval($info_id);
        $pid = intval($pid);
        $msg = trim($msg);
        if($info_id<=0 || $msg==""){
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



        $model = new Model_News();
        $binfo = $model -> CheckInfoById($info_id);
        if(!$binfo){
            return array('code'=>1,'msg'=>"未找到相关信息");
        }

        //评论上级用户
        if($pid){
            $pl = $model->CheckCommentExist($pid);
            if(!$pl){
                return array('code'=>1,'msg'=>"被评论的信息不存在");
            }
        }

        $pl = $model->CheckCommentExistByMsg($auth['uid'],$info_id,$msg);
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
            'info_id'=>$info_id,
            'nickname'=>$uinfo['nickname'],
            'avatar'=>$uinfo['avatar'],
            'pid'=>$pid,
            'mentions'=>$mentions?$mentions:"",
            'replay'=>0,
            'zan'=>0,
            'msg'=>$msg,
            'addtime'=>date("Y-m-d H:i:s")
        );
        $param = array('service'=>"Asyn.InfoComments",'param'=>urlencode(json_encode($data)));
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);
        $comment_count = $binfo['comment_count']+1;
        return array('code'=>0,'msg'=>"评论成功",'list'=>array());

    }

    public function getInfoShare($token,$info_id){
        $info_id = intval($info_id);
        if($info_id<=0){
            return array('code'=>1,'msg'=>'缺少必要的参数值');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $model = new Model_News();
        $binfo = $model->CheckInfoById($info_id);
        if(!$binfo){
            return array('code'=>1,'msg'=>'相关信息不存在');
        }

        $picarr = explode("?",$binfo['pic']);

        $info = array(
            'title'=>$binfo['title'],
            'content'=>strip_tags($binfo['introduction']),
            'pic'=>$picarr[0].'?imageView2/2/w/150/h/100/q/70',
            'url'=>DI()->config->get('sys.xsapp.homeurl')."news/?uid=".$auth['uid']."&newsid=".$binfo['id']
        );

        $rs[0]['weixin'] = array_merge(array('enable'=>"1"),$info);
        $rs[1]['timeline'] = array_merge(array('enable'=>"1"),$info);
        $rs[2]['qq'] = array_merge(array('enable'=>"1"),$info);
        $rs[3]['qzone'] = array_merge(array('enable'=>"1"),$info);
        $rs[4]['copy'] = array_merge(array('enable'=>"1"),$info);

        return array('code'=>0,'list'=>$rs);

    }

}
