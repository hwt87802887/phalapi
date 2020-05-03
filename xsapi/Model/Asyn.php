<?php

class Model_Asyn extends PhalApi_Model_NotORM {

    public  function SetBookHistory($uid,$book_id,$chapte_id,$chapte_name){
        $rs = $this->getORM()->select("id")->where(" uid = ? and book_id = ?",$uid,$book_id)->limit(1)->fetch();
        if($rs){
           return $this->getORM()->where(" uid = ? and book_id = ?",$uid,$book_id)->update(array('chapte_id'=>$chapte_id,'chapte_name'=>$chapte_name,'updatetime'=>time()));
        }else{
            return  $this->getORM()->insert(array('uid'=>$uid,'book_id'=>$book_id,'chapte_id'=>$chapte_id,'chapte_name'=>$chapte_name,'updatetime'=>time()));
        }
    }

    public  function  SetBookSelf($uid,$book_id){

        $b = DI()->notorm->ecms_book->select("title,author,titlepic")->where("id",$book_id)->limit(1)->fetch();

        $gold = DI()->config->get('fuli.fuli.addshujia');
        $wf = DI()->notorm->welfare->select('tianjiashujia')->where('uid',$uid)->limit(1)->fetch();
        if(!$wf){
            $welfare = array(
                'uid'=>$uid,
                'xinren'=>'1,'.DI()->config->get('fuli.fuli.newuser'),
                'wanshanziliao'=>'0,0',
                'bangdingshouji'=>'0,0',
                'tianjiashujia'=>'1,'.$gold,
                'meirifenxiang'=>'0,0',
                'yueduzixun'=>'0,0',
                'xiaoshuoshidu'=>'0,0',
                'addtime'=>time(),
                'lasttime'=>time(),
            );
            DI()->notorm->welfare->insert($welfare);

        }else{
            $tianjiashujia = explode(",",$wf['tianjiashujia']);
            if($tianjiashujia[0]=="0") {

                //更新任务状态-首次添加书架
                $updata['tianjiashujia'] = "1," . $gold;
                $up = $this->SetWelfare($uid, $updata);
                $description = '首次添加书架收益';
                DI()->notorm->welfare_log->insert(array('uid' => $uid,'type'=>2,'cz'=>'+','money'=>$gold, 'title' => "添加书架", 'description' => $description, 'addtime' => time()));//记录福利

            }
        }
        return  DI()->notorm->ecms_book_self->insert(array('uid'=>$uid,'book_id'=>$book_id,'book_name'=>$b['title'],'author'=>$b['author'],'book_url'=>$b['titlepic']));
    }
    
    public function SetBookLike($uid,$book_id){
        $rs =  DI()->notorm->ecms_book->where("id",$book_id)->update(array('like_num'=>new NotORM_Literal("like_num + 1")));
        DI()->notorm->book_zan->insert(array('uid'=>$uid,'book_id'=>$book_id,'zan'=>1,'ztime'=>time()));
        return $rs;
    }

    public  function SetBookComments($param){
        $rs = DI()->notorm->book_comment->insert($param);
        if($rs){
            if($param['book_id'] && !$param['chapte_id']){
                //书本评论+1
                DI()->notorm->ecms_book->where("id",$param['book_id'])->update(array('plnum'=>new NotORM_Literal("plnum + 1")));
            }elseif($param['book_id'] && $param['chapte_id']){
                //章节评论+1
                DI()->notorm->ecms_book_chapte->where("book_id = ? and chapte_id = ?",$param['book_id'],$param['chapte_id'])->update(array('plnum'=>new NotORM_Literal("plnum + 1")));
            }
        }
        return $rs;
    }
    public function SetVoteComment($uid,$id,$act){
        if($act==1){
            $rs = DI()->notorm->book_comment_zan->select("cid")->where("cid = ? and uid = ?",$id,$uid)->limit(0)->fetch();
            if($rs){
                return "存在";
            }else{
                return DI()->notorm->book_comment_zan->insert(array('cid'=>$id,'uid'=>$uid));
            }
        }elseif($act==2){
            return  DI()->notorm->book_comment_zan->where("cid = ? and uid = ?",$id,$uid)->delete();
        }
    }

    public function SetInfoZan($uid,$info_id,$zan){
        if($zan==1){
            $rs =  DI()->notorm->ecms_info->where("id",$info_id)->update(array('like_num'=>new NotORM_Literal("like_num + 1")));
        }else{
            $rs =  DI()->notorm->ecms_info->where("id",$info_id)->update(array('unlike_num'=>new NotORM_Literal("unlike_num + 1")));
        }

        $data = array('uid'=>$uid,'info_id'=>$info_id,'zan'=>$zan,'ztime'=>time());
        return DI()->notorm->info_zan->insert($data);
    }

    public function SetVoteCommentInfo($uid,$id){
        $rs =  DI()->notorm->info_comment->where("id",$id)->update(array('zan'=>new NotORM_Literal("zan + 1")));
        return DI()->notorm->info_comment_zan->insert(array('cid'=>$id,'uid'=>$uid));
    }

    public function SetBookGift($param){
        //打赏记录
        $gift_param = array(
            'uid' => $param['uid'],
            'book_id' => $param['book_id'],
            'book_name' => $param['book_name'],
            'gift_id' => $param['gift_id'],
            'gift_name' => $param['gift_name'],
            'gift_price'=>$param['gift_price'],
            'gift_time'=>$param['gift_time']
        );
        $rs = DI()->notorm->book_reward->insert($gift_param);
        if($rs){
            //更新账户
            DI()->notorm->user->where('uid',$param['uid'])->update(array('balance'=>new NotORM_Literal('balance - '.$param['gift_price'])));

            //添加评论
            $comment_param = array(
                'uid'=>$param['uid'],
                'book_id'=>$param['book_id'],
                'chapte_id'=>0,
                'nickname'=>$param['nickname'],
                'avatar'=>$param['avatar'],
                'pid'=>0,
                'mentions'=>'',
                'star'=>5,
                'replay'=>0,
                'zan'=>0,
                'msg'=>$param['gift_name'],
                'addtime'=>$param['gift_time']
            );

            $this->SetBookComments($comment_param);
        }

        return $rs;

    }

    public  function SetInfoComments($param){
        $rs = DI()->notorm->info_comment->insert($param);
        if($rs){
            if($param['info_id']){
                DI()->notorm->ecms_info->where("id",$param['info_id'])->update(array('lastdotime'=>time(),'comment_count'=>new NotORM_Literal("comment_count + 1")));
            }

            if($param['pid']){
                DI()->notorm->info_comment->where("id",$param['pid'])->update(array('replay'=>new NotORM_Literal("replay + 1")));
            }
        }
        return $rs;
    }

    public  function SetInfoRead($info_id){
       return  DI()->notorm->ecms_info->where("id",$info_id)->update(array('read_count'=>new NotORM_Literal("read_count + 1")));
    }

    public function SetAddAddress($adrid,$param){
        $isdefault = $param['isdefault'];
        if($isdefault>0){
            DI()->notorm->address->where('uid',$param['uid'])->update(array('isdefault'=>0));
        }
        if($adrid){
            return DI()->notorm->address->where("adrid",$adrid)->update($param);
        }else{
            return DI()->notorm->address->insert($param);
        }

    }

    public function SetDefault($adrid,$uid){
         DI()->notorm->address->where('uid',$uid)->update(array('isdefault'=>0));
        return DI()->notorm->address->where("adrid",$adrid)->update(array('isdefault'=>1));
    }

    public function SetAdrDel($adrid,$uid){
        return DI()->notorm->address->where("uid",$uid)->where("adrid",$adrid)->delete();
    }

    public function SetWelfare($uid,$param,$fuid=0){
        $data = $this->SetYaoQing($fuid,$uid,'');
        return DI()->notorm->welfare->where("uid",$uid)->update($param);
    }
    
    public function SetSign($uid,$signcount,$day,$gold){
        $rs= DI()->notorm->sign->select('*')->where("uid",$uid)->limit(1)->fetch();
        if(!$rs){
            DI()->notorm->sign->insert(array('uid'=>$uid,'signcount'=>$signcount,'day'=>$day,'lasttime'=>time(),'addtime'=>time()));
        }else{
            DI()->notorm->sign->where('uid',$uid)->update(array('signcount'=>$signcount,'day'=>$day,'lasttime'=>time()));
        }

        DI()->notorm->sign_history->insert(array('uid'=>$uid,'title'=>date('Y-m-d'),'addtime'=>time()));//记录签到

        $description ='每日签到收益';
        DI()->notorm->welfare_log->insert(array('uid'=>$uid,'type'=>2,'cz'=>'+','money'=>$gold,'title'=>"每日签到",'description'=>$description,'addtime'=>time()));//记录福利
        DI()->notorm->user->where('uid',$uid)->update(array('gold'=>new NotORM_Literal('gold + '.$gold)));

    }

    public function SetShare($uid,$type,$gold){
        //分享历史
        if($type=="add"){
           $data= DI()->notorm->share->insert(array('uid'=>$uid,'sharecount'=>1,'lasttime'=>time(),'addtime'=>time()));
        }elseif($type=="update"){
            $data =  DI()->notorm->share->where('uid',$uid)->update(array('sharecount'=>new NotORM_Literal('sharecount + 1'),'lasttime'=>time()));
        }else{
            $data = false;
        }

        if($type=="add" || $type=="update"){
            $rs = DI()->notorm->welfare->select('meirifenxiang')->where('uid',$uid)->limit(1)->fetch();
            $gold2 = $gold;
            if(!$rs){
                $welfare = array(
                    'uid'=>$uid,
                    'xinren'=>'1,'.DI()->config->get('fuli.fuli.newuser'),
                    'wanshanziliao'=>'0,0',
                    'bangdingshouji'=>'0,0',
                    'tianjiashujia'=>'0,0',
                    'meirifenxiang'=>'1,'.$gold2,
                    'yueduzixun'=>'0,0',
                    'xiaoshuoshidu'=>'0,0',
                    'addtime'=>time(),
                    'lasttime'=>time(),
                );
               DI()->notorm->welfare->insert($welfare);
            }else{
                $mrfx = explode(",",$rs['meirifenxiang']);
                $status = 1;
                //未领取累加
                if($mrfx[0]==1){
                    $gold2 = $mrfx[1] + $gold2;
                }

                $updata['meirifenxiang'] = $status.",".$gold2;
                 $this->SetWelfare($uid,$updata);
            }


            $description ='每日分享收益';
            DI()->notorm->welfare_log->insert(array('uid'=>$uid,'type'=>2,'cz'=>'+','money'=>$gold,'title'=>"每日分享",'description'=>$description,'addtime'=>time()));//记录福利
            //DI()->notorm->user->where('uid',$uid)->update(array('gold'=>new NotORM_Literal('gold + '.intval($gold))));

        }
        return $data;
    }

    public function SetReadNews($uid,$info_id,$gold){
        //阅读历史
        $data= DI()->notorm->info_history->insert(array('uid'=>$uid,'info_id'=>$info_id,'addtime'=>time()));
        $rs = DI()->notorm->welfare->select('yueduzixun')->where('uid',$uid)->limit(1)->fetch();
        $gold2 = $gold;
        if(!$rs){
            $welfare = array(
                'uid'=>$uid,
                'xinren'=>'1,'.DI()->config->get('fuli.fuli.newuser'),
                'wanshanziliao'=>'0,0',
                'bangdingshouji'=>'0,0',
                'tianjiashujia'=>'0,0',
                'meirifenxiang'=>'0,0',
                'yueduzixun'=>'1,'.$gold,
                'xiaoshuoshidu'=>'0,0',
                'addtime'=>time(),
                'lasttime'=>time(),
            );
            DI()->notorm->welfare->insert($welfare);
        }else{
            $ydzx = explode(",",$rs['yueduzixun']);
            $status = 1;
            //未领取累加
            if($ydzx[0]==1){
                $gold2 = $ydzx[1] + $gold2;
            }

            $updata['yueduzixun'] = $status.",".$gold2;
            $this->SetWelfare($uid,$updata);
        }

        $description ='每日阅读收益';
        DI()->notorm->welfare_log->insert(array('uid'=>$uid,'type'=>2,'cz'=>'+','money'=>$gold,'title'=>"阅读资讯",'description'=>$description,'addtime'=>time()));//记录福利
        //DI()->notorm->user->where('uid',$uid)->update(array('gold'=>new NotORM_Literal('gold + '.intval($gold))));
        
        return $data;
    }

    public function SetLottery($uid,$type,$fuli,$gold){
        $rs= DI()->notorm->lottery->select('*')->where("uid",$uid)->limit(1)->fetch();
        if($rs && date("Ymd",$rs['lasttime'])==date("Ymd")){
            return "今日已抽奖";
        }
        //抽奖
        if($type=="add"){
            $data= DI()->notorm->lottery->insert(array('uid'=>$uid,'lotterycount'=>1,'lasttime'=>time(),'addtime'=>time()));
        }elseif($type=="update"){
            $data =  DI()->notorm->lottery->where('uid',$uid)->update(array('lotterycount'=>new NotORM_Literal('lotterycount + 1'),'lasttime'=>time()));
        }else{
            $data = false;
        }

        if($type=="add" || $type=="update"){

            if($fuli==1){
                $description ='每日抽奖收益';
                $gold = (float)$gold;
                DI()->notorm->welfare_log->insert(array('uid'=>$uid,'type'=>1,'cz'=>'+','money'=>$gold,'title'=>"每日抽奖",'description'=>$description,'addtime'=>time()));//记录福利
                DI()->notorm->user->where('uid',$uid)->update(array('money'=>new NotORM_Literal('money + '.$gold)));
            }elseif($fuli==2){
                $description ='每日抽奖收益';
                DI()->notorm->welfare_log->insert(array('uid'=>$uid,'type'=>2,'cz'=>'+','money'=>$gold,'title'=>"每日抽奖",'description'=>$description,'addtime'=>time()));//记录福利
                DI()->notorm->user->where('uid',$uid)->update(array('gold'=>new NotORM_Literal('gold + '.intval($gold))));
            }

        }
        return $data;
    }

    public function SetTixian($uid,$gold,$money,$total)
    {
        $rs = false;
        $user = new Model_User();
        $alipay = $user->getAlipayInfo($uid);
        if ($alipay) {
            $user_id = $alipay['user_id'];
            $nick_name = $alipay['nick_name'];
            $time = time();
            $data = array(
                'orderid' => 't' . str_replace(".", "", microtime(true)) . getRandNum(8),
                'uid' => $uid,
                'user_id' => $user_id,
                'nick_name' => $nick_name,
                'money' => $total,
                'addtime' => $time,
            );
            DI()->notorm->tixian->insert($data);
            if ($gold > 0) {
                $uarr1 = array('gold' => new NotORM_Literal('gold -' . $gold), 'txmoney' => new NotORM_Literal('txmoney +' . $total));
                $description = '系统金币换零钱提现';
                DI()->notorm->welfare_log->insert(array('uid' => $uid, 'type' => 2, 'cz' => '-', 'money' => $gold, 'title' => "金币换零钱", 'description' => $description, 'addtime' => $time));//记录福利
            }
            if ($money > 0) {
                $uarr2 = array('money' => new NotORM_Literal('money -' . $money), 'txmoney' => new NotORM_Literal('txmoney +' . $total));
                $description = '系统零钱提现';
                DI()->notorm->welfare_log->insert(array('uid' => $uid, 'type' => 1, 'cz' => '-', 'money' => $money, 'title' => "零钱提现", 'description' => $description, 'addtime' => $time));//记录福利
            }
            $uarr = array_merge($uarr1, $uarr2);

            if ($uarr) {
                $rs = DI()->notorm->user->where('uid', $uid)->update($uarr);
            }
            return $rs;
        }
    }

    //邀请好友记录
    public function SetYaoQing($fuid,$uid,$type=''){
        if(!$fuid || $uid){
            return false;
        }
        $user = new Model_User();
        $u = $user->getuserInfo($uid);
        if(!$u['fuid']){
            $fu = $user->getuserInfo($fuid);
            if(!$fu){
                return false;
            }
            //是否已绑定手机号
            if($u['mobile']){
              $money = DI()->config->get("fuli.fuli.yqmoney");
              $user->getEditUserInfo($uid,array('fuid'=>$fuid));
              $user->getEditUserInfo($fuid,array('money'=>new NotORM_Literal('money + '.$money)));
              $description = '邀请好友收益';
              DI()->notorm->welfare_log->insert(array('uid' => $uid, 'type' => 1, 'cz' => '+', 'money' => number_format($money,2), 'title' => "邀请好友", 'description' => $description, 'addtime' => time()));//记录福利
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    //完善资料记录
    public function SetWszl($uid){
        $rs = DI()->notorm->welfare->select('wanshanziliao')->where('uid',$uid)->limit(1)->fetch();
        $gold = DI()->config->get('fuli.fuli.perfectzl');
        if(!$rs){
            $welfare = array(
                'uid'=>$uid,
                'xinren'=>'1,'.DI()->config->get('fuli.fuli.newuser'),
                'wanshanziliao'=>'1,'.gold,
                'bangdingshouji'=>'0,0',
                'tianjiashujia'=>'0,0',
                'meirifenxiang'=>'0,0',
                'yueduzixun'=>'0,0',
                'xiaoshuoshidu'=>'0,0',
                'addtime'=>time(),
                'lasttime'=>time(),
            );
            DI()->notorm->welfare->insert($welfare);
        }else{
            $wszl = explode(",",$rs['wanshanziliao']);
            if($wszl[0]>0){
                $gold=0;
            }
        }

        if($gold) {
            $description = '完善基本资料收益';
            DI()->notorm->welfare_log->insert(array('uid' => $uid, 'type' => 2, 'cz' => '+', 'money' => $gold, 'title' => "完善资料", 'description' => $description, 'addtime' => time()));//记录福利
            //DI()->notorm->user->where('uid',$uid)->update(array('gold'=>new NotORM_Literal('gold + '.intval($gold))));
        }

        return true;
    }

    //新人记录
    public function SetXinren($uid,$login_type){
        $rs = DI()->notorm->welfare->select('xinren')->where('uid',$uid)->limit(1)->fetch();
        $gold = DI()->config->get('fuli.fuli.newuser');
        $bangdingshouji="0,0";
        if($login_type=="mobile"){
            $bangdingshouji="1,".DI()->config->get('fuli.fuli.bandphone');
        }
        if(!$rs){
            $welfare = array(
                'uid'=>$uid,
                'xinren'=>'1,'.$gold,
                'wanshanziliao'=>'0,0',
                'bangdingshouji'=>$bangdingshouji,
                'tianjiashujia'=>'0,0',
                'meirifenxiang'=>'0,0',
                'yueduzixun'=>'0,0',
                'xiaoshuoshidu'=>'0,0',
                'addtime'=>time(),
                'lasttime'=>time(),
            );
            DI()->notorm->welfare->insert($welfare);
        }else{
            $xr = explode(",",$rs['xinren']);
            if($xr[0]>0){
                $gold=0;
            }
        }

        if($gold) {
            $description = '新人红包收益';
            DI()->notorm->welfare_log->insert(array('uid' => $uid, 'type' => 2, 'cz' => '+', 'money' => $gold, 'title' => "新人红包", 'description' => $description, 'addtime' => time()));//记录福利
            //DI()->notorm->user->where('uid',$uid)->update(array('gold'=>new NotORM_Literal('gold + '.intval($gold))));
        }

        return true;
    }

    public function SetYuedubi($uid,$gold,$yuedubi){

    }

    protected function getTableName($id) {
        return 'ecms_book_history';
    }
}
