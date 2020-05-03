<?php

class Model_Book extends PhalApi_Model_NotORM {
    //分类
    public function getCategoyry($channel){
        $data =array();
        $rs = DI()->notorm->enewskey->select('keyid,keyname')->where("cid",$channel)->order('keyid desc')->fetchAll();
        /*
        foreach($rs as $key=>$item){
            $data[$key+1] = $item['keyname'];
        }*/
        return $rs;
    }

    //排行
    public function getRankType($channel){
        $goodarr = array('1'=>'热门榜','2'=>'推荐榜','3'=>'新书榜','4'=>'畅销榜','5'=>'人气榜');
        $data = array();
        foreach ($goodarr as $key=>$arr){
            $data[$key-1]['typeid']=(string)$key;
            $data[$key-1]['typename']=$arr;
            $rs = $this->getORM()->select("id as book_id,title as book_name,author,titlepic as book_url,book_type,book_status,description")->where("channel = ? and isgood = ?",$channel,$key)->order(' id desc ')->limit(3)->fetchAll();
            $data[$key-1]['list'] = $rs;
        }
        return $data;
    }
    //自动检索
    public function getAssociate($keywords){
        return $this->getORM()->select("id as book_id,title as book_name")->where("match(title) against ('*".addslashes($keywords)."*' IN BOOLEAN MODE)")->order(" id desc")->limit(12)->fetchAll();
    }

    //搜索
    public  function getSearch($keywords,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("match(title) against ('*".addslashes($keywords)."*' IN BOOLEAN MODE)".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //分类信息列表
    public  function getCateList($channel,$tagname,$progressid,$wordsid,$updatedid,$id,$pagesize){
        $add = "";
        if($tagname){$add.= " and book_type='".$tagname."'";}
        if($progressid>0 && $progressid<=2){$add.=" and book_status = ".($progressid-1);}
        if($wordsid==1){
            $add.=" and words < 300000";
        }elseif($wordsid==2){
            $add.=" and (words >= 300000 and words <= 1000000)";
        }elseif($wordsid==3){
            $add.=" and words > 1000000";
        }
        if($updatedid==1){
            $time = date("Y-m-d H:i:s");
            $add.=" and lastdotime > ".strtotime($time);
        }elseif($updatedid==2){
            $time = strtotime('-2 day');
            $time = date('Y-m-d 00:00:00', $time);
            $add.=" and lastdotime > ".strtotime($time);
        }elseif($updatedid==3){
            $time = strtotime('-10 day');
            $time = date('Y-m-d 00:00:00', $time);
            $add.=" and lastdotime > ".strtotime($time);
        }

        if($id>1){
            $add.= " and id < $id";
        }

        return $this->getORM()->select("id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description")->where(" channel = ".$channel.$add)->order(' id desc ')->limit($pagesize)->fetchAll();

    }




    //更多排行
    public  function getRankList($channel,$typeid,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and isgood = $typeid ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //本期主打
    public  function getMainfight($channel,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and isgood = 6 ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //精选小说
    public  function getHandpick($channel,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and isgood = 7 ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //完本畅读
    public  function getBookend($channel,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and book_status = 1 ".$add)->order('lastdotime desc')->limit($pagesize)->fetchAll();
    }

    //短篇小说
    public  function getShortBook($channel,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and islong = 0 ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //新书推荐
    public  function getNewBook($channel,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and isgood = 3 ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //男女主页
    public function getBookHome($channel){
        $data = array();
        //本期主打
        $data[0]['type']="Mainfight";
        $data[0]['typename']="本期主打";
        $data[0]['data'] = $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and isgood = 6 ")->order('id desc')->limit(6)->fetchAll();
        //精选
        $data[1]['type']="Handpick";
        $data[1]['typename']="精选小说";
        $data[1]["data"] =  $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and isgood = 7 ")->order('id desc')->limit(3)->fetchAll();
        //完本
        $data[2]['type']="Bookend";
        $data[2]['typename']="完本畅读";
        $data[2]["data"] =  $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and book_status = 1 ")->order('lastdotime desc')->limit(3)->fetchAll();
        //短篇小说
        $data[3]['type']="ShortBook";
        $data[3]['typename']="短篇小说";
        $data[3]["data"] =$this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and islong = 0 ")->order('id desc')->limit(3)->fetchAll();
        //新书推荐
        $data[4]['type']="NewBook";
        $data[4]['typename']="新书推荐";
        $data[4]["data"] = $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and isgood = 3 ")->order('id desc')->limit(5)->fetchAll();
        //畅销
        $data[5]['type']="RankList";
        $data[5]['typename']="畅销排行";
        $data[5]["data"] = $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where("channel = ".$channel." and isgood = 4")->order('id desc')->limit(5)->fetchAll();

        return $data;
    }

    //VIP新书推荐
    public  function getVIPNewBook($id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return $this->getORM()->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where(" isgood = 3 ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }


    //书本详情
    public function getBookInfo($id,$uid){
        $data = array();
        $r = $this->getORM()->select("id as book_id,title as book_name,channel,author,words,titlepic as book_url,book_status,is_shelves,is_vip,like_num,reward,plnum,lastdotime,book_type,description")->where("id",$id)->limit(1)->fetch();
        if($r){
            $data = $r;
            $data['words'] = format_num($data['words']);
            $data['lastdotime'] = date("Y-m-d H:i:s",$data['lastdotime']);
            if($data['is_shelves']==1) {
                $c = DI()->notorm->ecms_book_history->select("chapte_id,chapte_name")->where("book_id", $id)->fetch();
                if ($c) {
                    $data['read_chapte'] = $c['chapte_id'];
                    $data['is_read'] = "1";
                } else {
                    $c = DI()->notorm->ecms_book_chapte->select("chapte_id,chapte_name")->where("book_id", $id)->order("sort_id asc")->limit(1)->fetch();
                    $data['read_chapte'] = $c['chapte_id'] ? $c['chapte_id'] : "0";
                    $data['is_read'] = "0";
                }

                $c = DI()->notorm->ecms_book_chapte->select("chapte_id,chapte_name")->where("book_id", $id)->order("sort_id desc")->limit(1)->fetch();
                $data['new_chapte'] = $c['chapte_name']?$c['chapte_name']:"";
            }else{
                $data['is_read'] = "0";
                $data['read_chapte'] = "0";
                $data['new_chapte']="";
            }
            $data['isbookshelf']="0";
            if($uid) {
                $s = DI()->notorm->ecms_book_self->select("book_id")->where("book_id=? and uid = ?", $id,$uid)->fetch();
                $data['isbookshelf'] = $s['book_id'] ? "1" : "0";
            }
            $data['description'] = strip_tags($data['description']);
        }
        return $data;
    }

    
    public function getBookComments($uid,$book_id,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        $rs =  DI()->notorm->book_comment->select('id,book_id,uid,nickname,avatar,star,replay,zan,mentions,msg,addtime')->where("book_id = ".$book_id." and chapte_id = 0 ".$add)->order('id desc')->limit($pagesize)->fetchAll();
            foreach($rs as $key=>$item){
                $rs[$key]['is_vote']="0";
                if($uid) {
                    $z = DI()->notorm->book_comment_zan->select("id")->where("uid = ? and cid = ?", $uid, $item['id'])->limit(1)->fetch();
                    if ($z) {
                        $rs[$key]['is_vote'] = "1";
                    }
                }
        }
        return $rs;
    }

    public function getChapteComments($uid,$book_id,$chapte_id,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        $rs =  DI()->notorm->book_comment->select('id,book_id,chapte_id,uid,nickname,avatar,star,replay,zan,mentions,msg,addtime')->where("book_id = ".$book_id." and uid = ".$uid." and chapte_id = ".$chapte_id.$add)->order('id desc')->limit($pagesize)->fetchAll();
        /*
        foreach($rs as $key=>$item){
            $rs[$key]['is_vote']="0";
            if($uid) {
                $z = DI()->notorm->book_comment_zan->select("id")->where("uid = ? and cid = ?", $uid, $item['id'])->limit(1)->fetch();
                if ($z) {
                    $rs[$key]['is_vote'] = "1";
                }
            }
        }*/
        return $rs;
    }

    public  function getCommentDetail($uid,$cid,$id,$pagesize){
        $data = array();
        $rs =  DI()->notorm->book_comment->select('id,book_id,uid,nickname,avatar,star,replay,zan,mentions,msg,addtime')->where("id = ?",$cid)->limit(1)->fetch();
        if($rs){
            $rs['is_vote']="0";
            if($uid) {
                $z = DI()->notorm->book_comment_zan->select("id")->where("uid = ? and id = ?", $uid, $cid)->limit(1)->fetch();
                if ($z) {
                    $rs['is_vote'] = "1";
                }
            }
            $data['comment'] = $rs;
            //获取下级评论
            if($id>1){
                $add = " and id > $id";
            }
            $plnxt =  DI()->notorm->book_comment->select('id,book_id,uid,nickname,avatar,star,replay,zan,mentions,msg,addtime')->where("pid = ".$cid.$add)->order("id asc")->limit($pagesize)->fetchAll();
            $data['replays'] = $plnxt;

        }
        return $data;
    }
    
    public function getBookById($id){
       return $this->getORM()->select("id as book_id,title as book_name,channel,author,words,titlepic as book_url,book_status,book_type,like_num,description")->where("id",$id)->limit(1)->fetch();
    }

    //书本详情--同类推荐
    public function getBookSimilar($book_type,$pagesize){
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');
        return $this->getORM()->queryAll("SELECT b1.id as book_id,b1.title as book_name,b1.titlepic as book_url,author FROM ".$prefix."ecms_book AS b1 JOIN (SELECT	ROUND(RAND() * ((SELECT MAX(id) FROM ".$prefix."ecms_book where book_type='".$book_type."')-(SELECT MIN(id) FROM ".$prefix."ecms_book where book_type='".$book_type."')) + (SELECT MIN(id) FROM ".$prefix."ecms_book where book_type='".$book_type."')) AS id) AS b2 WHERE b1.book_type='".$book_type."' and b1.id>=b2.id limit ".$pagesize);
    }

    //书本详情--看过这本书的人都在看、猜你喜欢
    public function getPersonSimilar($pagesize){
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');
        return $this->getORM()->queryAll("SELECT b1.id as book_id,b1.title as book_name,b1.titlepic as book_url,author FROM ".$prefix."ecms_book AS b1 JOIN (SELECT	ROUND(RAND() * ((SELECT MAX(id) FROM ".$prefix."ecms_book)-(SELECT MIN(id) FROM ".$prefix."ecms_book)) + (SELECT MIN(id) FROM ".$prefix."ecms_book)) AS id) AS b2 WHERE b1.id>=b2.id limit ".$pagesize);
    }

    //获取章节列表
    public function getChapteList($book_id){
        return DI()->notorm->ecms_book_chapte->select("book_id,chapte_id,chapte_name,is_vip,sort_id")->where("book_id",$book_id)->order("sort_id asc")->fetchAll();
    }
    
    //添加书架
    public function AddBookSelf($uid,$book_id){
        $rs = DI()->notorm->ecms_book_self->select("id")->where("uid =? and book_id = ?",$uid,$book_id)->limit(1)->fetch();
        if(!$rs){
            $param = array('service'=>"Asyn.BookSelf",'uid'=>$uid,'book_id'=>$book_id);
            $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
            doSock($url,$param);
        }
        return array('code'=>0,'msg'=>'成功');
    }

    //第一章节
    public function getChapteFirst($book_id){
        $rs = DI()->notorm->ecms_book_chapte->select("chapte_id,chapte_name")->where("book_id=?",$book_id)->order("sort_id asc")->limit(1)->fetch();
        return $rs;
    }

    //章节信息
    public function getChapteInfo($token,$book_id,$chapte_id,$sdtoken){
        $data = array();
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');
        $r = $this->getORM()->queryAll("select c.chapte_id,c.chapte_name,c.is_vip,c.contentpath,c.words,c.saleprice,c.book_id,b.title as book_name,c.plnum from ".$prefix."ecms_book_chapte as c INNER JOIN ".$prefix."ecms_book as b on c.book_id=b.id where c.book_id = ".$book_id." and c.chapte_id=".$chapte_id." limit 1");
        if($r){
            $data = $r[0];

            if(!$data['contentpath']){
                return array('code'=>1,'msg'=>"未找到章节内容",'data'=>array());
            }

            //是否收费
            if($data['is_vip']==1 && $data['saleprice']<=0){
                $data['is_vip']=="0";
            }

            if(!$data['contentpath']){
                return array();
            }

            if($data['is_vip']==1 && !$token){
                return array('code'=>2,'msg'=>"您未登录",'data'=>array());
            }

            if($token){
                $user = new Model_User();
                $auth = $user->checkToken($token);
                if($auth['code']>0){
                    return array('code'=>2,'msg'=>"token失效",'data'=>array());
                }
                $uid = $auth['uid'];
                $uinfo = $user->getuserInfo($uid);
                $data['balance'] = $uinfo['balance'];
                $isvip = $uinfo['is_vip'];
            }

            $data['saleprice']  = (string)ceil($data['saleprice']*1.5);
            //$t1 = microtime(true);
            $txtpath = getContentPath($book_id,$data['contentpath']);
            $fp = new FileReader_Lite($txtpath);
            $lines =  $fp->lines();//总行数
            $arr = $fp->slice(1,($lines+1));
            $content = implode("",$arr);
            $data['content'] = str_replace("<br>",PHP_EOL,str_replace("</p>",PHP_EOL,str_replace("<p>","",$content)));
            ///$data['content'] = $content;
            if($content==""){
                return array('code'=>1,'msg'=>"未找到章节内容",'data'=>array());
            }

            $pr = DI()->notorm->ecms_book_chapte->select("chapte_id")->where("book_id=?  and chapte_id < ?",$book_id,$chapte_id)->order("sort_id desc")->limit(1)->fetch();
            $data['previous_chapte'] = $pr['chapte_id']?$pr['chapte_id']:"0";

            $nx = DI()->notorm->ecms_book_chapte->select("chapte_id")->where("book_id=?  and chapte_id > ?",$book_id,$chapte_id)->order("sort_id asc")->limit(1)->fetch();
            $data['next_chapte'] = $nx['chapte_id']?$nx['chapte_id']:"0";

            unset($data['contentpath']);
            

            //添加历史记录
            if($uid){
                $param = array('service'=>"Asyn.BookHistory",'uid'=>$uid,'book_id'=>$book_id,'chapte_id'=>$chapte_id,'chapte_name'=>$data['chapte_name']);
                $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
                doSock($url,$param);
            }

            //收费，非VIP用户,检查之前是否购买过
            if($data['is_vip']==1 && !$isvip){
                //判断此章节是否购买
                $buy = DI()->notorm->ecms_book_chapte_buy->select("id")->where("uid = ? and book_id = ? and chapte_id = ? ",$uid,$book_id,$chapte_id)->limit(1)->fetch();
                if(!$buy) {
                    $data['content'] = sub_string($data['content'], 180);
                    return array('code' => 3, 'data' => $data);
                }else{
                    $data['is_vip'] = "0";
                }
            }elseif($data['is_vip']==1 && $isvip){
                $data['is_vip'] = "0";
            }

            $data['min_token']="";
            if($sdtoken){
                list($id,$qudao) = authcode(base64_decode_s($sdtoken),'DECODE');
                if($id!="" && $qudao!="" && $uid){
                    $bt =  DI()->notorm->book_trial->select('*')->where('id=?',$id)->limit(1)->fetch();
                    if($bt) {
                        $book_chapte = json_decode($bt['book_chapte'],true);
                        //是否属于试读chapte_id
                        if(in_array($chapte_id,$book_chapte)) {
                            //之前是否阅读过
                            $sdc = DI()->notorm->book_trial_chapte->select('chapte_id')->where('uid = ? and chapte_id=?', $uid, $chapte_id)->limit(1)->fetch();
                            if (!$sdc) {
                                $data['min_token'] = base64_encode_s(authcode($id . '[\t]' . $qudao . '[\t]' . $book_id . '[\t]' . $chapte_id . '[\t]' . $bt['sdmiao'] . '[\t]'. $bt['sdmoney'] . '[\t]'. time(), 'ENCODE'));
                            }
                        }
                    }
                }
            }

                        
            //$t2 = microtime(true);
            //echo '耗时'.round($t2-$t1,3).'秒<br>';
        }
            return $data;
    }

    //获取小说订单
    public function getCheckOrder($uid,$orderid){
        $rs= DI()->notorm->bookorder->select("orderid,title,balance as price,is_vip,zt,paytype,paytime")->where('uid = ? and orderid=?',$uid,$orderid)->limit(1)->fetch();
        if($rs && $rs['paytime']){
            $rs['paytime'] = date("Y-m-d H:i:s",$rs['paytime']);
        }
        return $rs;
    }


    public function getBookZanInfo($uid,$book_id){
        return DI()->notorm->book_zan->select("*")->where("uid = ? and book_id= ?",$uid,$book_id)->limit(1)->fetch();
    }

    public  function getBookGift(){
        return DI()->notorm->book_reward_set->select("*")->fetchAll();
    }

    public function getBookGiftHistory($book_id,$id,$pagesize){
        if($id>1){
            $add = " and r.id < $id";
        }
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');
        return DI()->notorm->book_reward->queryAll('SELECT r.id,u.nickname,u.avatar,r.gift_name,r.gift_time FROM '.$prefix.'book_reward as r INNER JOIN '.$prefix.'user as u on r.uid=u.uid where r.book_id='.$book_id.$add.' order by id desc limit '.$pagesize);
    }

    public function  getBookGiftById($id){
        return DI()->notorm->book_reward_set->select("*")->where("id",$id)->limit(1)->fetch();
    }
    
    public function AddChapteBuy($data){
        return DI()->notorm->ecms_book_chapte_buy->insert($data);
    }

    public function  CheckBookExist($book_id){
        return $this->getORM()->select("id")->where("id",$book_id)->limit(1)->fetch();
    }

    public function  CheckChapteExist($book_id,$chapte_id){
        return DI()->notorm->ecms_book_chapte->select("*")->where("book_id = ? and chapte_id= ?",$book_id,$chapte_id)->limit(1)->fetch();
    }

    public function  CheckCommentExist($id){
        return DI()->notorm->book_comment->select("id")->where(" id = ?",$id)->limit(1)->fetch();
    }

    public function  CheckCommentExistByMsg($uid,$book_id,$msg){
        return DI()->notorm->book_comment->select("id")->where(" uid = ? and book_id = ? and msg = ?",$uid,$book_id,addslashes($msg))->limit(1)->fetch();
    }

    protected function getTableName($id) {
        return 'ecms_book';
    }
}
