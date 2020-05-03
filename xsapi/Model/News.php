<?php

class Model_News extends PhalApi_Model_NotORM {

    public  function getCategory(){
        $data = array(0=>array('classid'=>"255",'classname'=>"推荐"));
        $rs =  DI()->notorm->enewsclass->select('classid,classname')->where("bclassid",5)->fetchAll();
        return array_merge($data,$rs);
    }
    
    public function getLists($classid,$min_time,$pagesize){
        if($classid ==255){
            $add = " i.lastdotime < $min_time ";
        }elseif($classid==6){
            $add = " i.video_type=1 and i.lastdotime < $min_time";
        }else{
            $add = " i.classid = $classid and i.lastdotime < $min_time";
        }
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');
        $rs = $this->getORM()->queryAll("select i.id,title,introduction,source_name,keyboard as tag,cover,cover_show_type,d.atlas_list,content_type,tips,tips_color,play_time,video_value,read_count,comment_count,newstime,lastdotime as min_time from ".$prefix."ecms_info as i inner join ".$prefix."ecms_info_data_1 as d on  i.id=d.id where $add order by i.lastdotime desc limit $pagesize");
        if($rs){
            foreach ($rs as $key=>$item){
                if($item['cover']){
                    $rs[$key]['cover'] = explode("||||||",$item['cover']);
                }

                if($item['tag']){
                    $rs[$key]['tag']  = explode(",",$item['tag']);
                }


                if($item['atlas_list']){
                    $atlas_list = explode("||||||",$item['atlas_list']);
                    foreach ($atlas_list as $k=>$dlist){
                        $dlist = explode("::::::",$dlist);
                        $atlas_list[$k] = array('pic'=>$dlist[0],'desc'=>$dlist[1]);
                    }
                    $rs[$key]['atlas_list']= $atlas_list;
                }

                //$rs[$key]['newstime'] = format_date($item['newstime']);

            }


            $count = count($rs);
            $min_time = $rs[$count-1]['min_time'];
            $adlist = $this->getAd($classid);
            return  array("code"=>0,'min_time'=>$min_time,'list'=>$rs,'adlist'=>$adlist);
        }else{
            return array("code"=>0,'min_time'=>0,'list'=>array());
        }

    }

    public function getSearch($keywords,$min_time,$pagesize){
        $add= " and i.lastdotime < $min_time ";
        //$add= " LOCATE('".addslashes($keywords)."' , title)".$add;
        $add = " match(title) against ('*".addslashes($keywords)."*' IN BOOLEAN MODE)".$add;
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');
        $rs = $this->getORM()->queryAll("select i.id,title,introduction,source_name,keyboard as tag,cover,cover_show_type,d.atlas_list,content_type,tips,tips_color,play_time,video_value,read_count,comment_count,newstime,lastdotime as min_time from ".$prefix."ecms_info as i inner join ".$prefix."ecms_info_data_1 as d on  i.id=d.id where $add order by i.lastdotime desc limit $pagesize");

        if($rs){
            foreach ($rs as $key=>$item){
                if($item['cover']){
                    $rs[$key]['cover'] = explode("||||||",$item['cover']);
                }

                if($item['tag']){
                    $rs[$key]['tag']  = explode(",",$item['tag']);
                }


                if($item['atlas_list']){
                    $atlas_list = explode("||||||",$item['atlas_list']);
                    foreach ($atlas_list as $k=>$dlist){
                        $dlist = explode("::::::",$dlist);
                        $atlas_list[$k] = array('pic'=>$dlist[0],'desc'=>$dlist[1]);
                    }
                    $rs[$key]['atlas_list']= $atlas_list;
                }

                //$rs[$key]['newstime'] = format_date($item['newstime']);

            }


            $count = count($rs);
            $min_time = $rs[$count-1]['min_time'];
            $adlist = $this->getAd();
            return  array("code"=>0,'min_time'=>$min_time,'list'=>$rs,'adlist'=>$adlist);
        }else{
            return array("code"=>0,'min_time'=>0,'list'=>array());
        }
    }

    public function getDetail($id,$token){
        $rs = $this->getORM()->select("id,title,introduction,source_name,keyboard as tag,cover,cover_show_type,content_type,tips,tips_color,play_time,video_value,read_count,comment_count,can_comment,show_comment,like_num,unlike_num,newstime")->where("id",$id)->limit(1)->fetchOne();
        if($rs){
            if($rs['content_type']==12){
                $d = DI()->notorm->ecms_info_data_1->select("atlas_list")->where("id",$id)->limit(1)->fetch();
                if($d['atlas_list']){
                    $j=0;
                    $atlas_list = array();
                    $atlas_list2 = explode("||||||",$d['atlas_list']);
                    foreach ($atlas_list2 as $k=>$dlist){
                        if($dlist!="::::::") {
                            $dlist = explode("::::::",$dlist);
                            $atlas_list[$k] = array('pic'=>$dlist[0],'desc'=>$dlist[1]);
                            $j++;
                        }
                    }
                }
            }elseif($rs['content_type']==3 && $rs['video_value']){
                $d = DI()->notorm->ecms_info_data_1->select("video_url")->where("id",$id)->limit(1)->fetch();
                if($d['video_url']){
                    $video_url2 = explode("||||||",$d['video_url']);
                    $j=0;
                    $video_url = array();
                    foreach ($video_url2 as $k=>$dlist){
                        if($dlist!="::::::") {
                            $dlist = explode("::::::", $dlist);
                            if ($dlist[0] && $dlist[0] != "::::::") {
                                $video_url[$j] = array('clarity' => $dlist[0], 'url' => $dlist[1],'size' => $dlist[2], 'fps' => $dlist[3], 'bitrate' => $dlist[4]);
                            }
                            $j++;
                        }
                    }
                }
            }else{
                //$d = DI()->notorm->ecms_info_data_1->select("content")->where("id",$id)->limit(1)->fetch();
                //$content= $d['content'];
                $contenturl = $rs['content'] = DI()->config->get("sys.xsapp.homeurl")."news/content.php?id=".$id;
            }
            if($rs['cover']){
                $rs['cover'] = explode("||||||",$rs['cover']);
            }

            $rs['atlas_list'] = $atlas_list?$atlas_list:array();
            $rs['video_url'] = $video_url?$video_url:array();
            $rs['contenturl'] = $contenturl?$contenturl:"";


            $rs['user_islike']="0";
            $rs['adlist'] = $this->getAd($rs['classid']);

            $rs['recommendlist'] = $this->getRecommendByTag($rs['tag']);

            $rs['is_read'] = "0";
            $rs['min_time'] = (string)time();
            $string=$id.'[\t]'.$rs['min_time'] ;
            $rs['min_token'] = base64_encode_s(authcode($string,"ENCODE"));
            $rs['min_second'] = (string)DI()->config->get('fuli.fuli.zxmiao');
            if($token){
                $user = new Model_User();
                $auth = $user->checkToken($token);
                if($auth['code']==0){
                    $z = DI()->notorm->info_zan->select("id,zan")->where("uid = ? and info_id = ?",$auth['uid'],$id)->limit(1)->fetch();
                    if($z){
                        $rs['user_islike']=$z['zan'];
                    }

                    $h = DI()->notorm->info_history->select("*")->where("uid = ? and info_id = ?",$auth['uid'],$id)->limit(1)->fetch();
                    if($h){
                        $rs['is_read'] = "1";
                    }
                }

            }


            $param = array('service'=>"Asyn.InfoRead",'info_id'=>$id);
            $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
            doSock($url,$param);

            return array("code"=>0,'list'=>$rs);

        }else{
            return array("code"=>0,'list'=>array());
        }
    }

    public function getVideo($token,$id){
        $d = DI()->notorm->ecms_info_data_1->select("video_url")->where("id",$id)->limit(1)->fetch();
        $data = array();
        if($d['video_url']){
            $video_url2 = explode("||||||",$d['video_url']);
            $j=0;
            $video_url = array();
            foreach ($video_url2 as $k=>$dlist){
                if($dlist!="::::::") {
                    $dlist = explode("::::::", $dlist);
                    if ($dlist[0] && $dlist[0] != "::::::") {
                        $video_url[$j] = array('clarity' => $dlist[0], 'url' => $dlist[1],'size' => $dlist[2], 'fps' => $dlist[3], 'bitrate' => $dlist[4]);
                    }
                    $j++;
                }
            }

            $rs = $this->getORM()->select("play_time,video_value,cover")->where("id",$id)->limit(1)->fetch();
            $cover = "";
            if($rs['cover']){
                $cover = explode("||||||",$rs['cover']);
                //$cover = $cover[0];
            }

            $data['play_time'] = $rs['play_time'];
            $data['video_value'] = $rs['video_value'];
            $data['cover'] = $cover;
            $data['video_url'] = $video_url;

            $data['is_read'] = "0";
            $data['min_time'] = (string)time();
            $string=$id.'[\t]'.$rs['min_time'] ;
            $data['min_token'] = base64_encode_s(authcode($string,"ENCODE"));
            $data['min_second'] = (string)DI()->config->get('fuli.fuli.zxmiao');
            if($token){
                $user = new Model_User();
                $auth = $user->checkToken($token);
                if($auth['code']==0){
                    $h = DI()->notorm->info_history->select("*")->where("uid = ? and info_id = ?",$auth['uid'],$id)->limit(1)->fetch();
                    if($h){
                        $data['is_read'] = "1";
                    }
                }
            }

        }
        return $data;
    }

    public function getInfoComments($uid,$info_id,$id,$pagesize){
        $add=" and pid = 0";
        if($id>1){
            $add.= " and id < $id";
        }
        $rs =  DI()->notorm->info_comment->select('id,info_id,uid,nickname,avatar,replay,zan,mentions,msg,addtime')->where("info_id = ".$info_id.$add)->order('id desc')->limit($pagesize)->fetchAll();
        foreach($rs as $key=>$item){
            $rs[$key]['is_vote']="0";
            if($uid) {
                $z = DI()->notorm->info_comment_zan->select("id")->where("uid = ? and cid = ?", $uid, $item['id'])->limit(1)->fetch();
                if ($z) {
                    $rs[$key]['is_vote'] = "1";
                }
            }
        }
        return $rs;
    }

    public  function getCommentDetail($uid,$cid,$id,$pagesize){
        $data = array();
        $rs =  DI()->notorm->info_comment->select('id,info_id,uid,nickname,avatar,replay,zan,mentions,msg,addtime')->where("id = ?",$cid)->limit(1)->fetch();
        if($rs){
            $rs['is_vote']="0";
            if($uid) {
                $z = DI()->notorm->info_comment_zan->select("id")->where("uid = ? and id = ?", $uid, $cid)->limit(1)->fetch();
                if ($z) {
                    $rs['is_vote'] = "1";
                }
            }
            $data['comment'] = $rs;
            //获取下级评论
            if($id>1){
                $add = " and id > $id";
            }
            $plnxt =  DI()->notorm->info_comment->select('id,info_id,uid,nickname,avatar,replay,zan,mentions,msg,addtime')->where("pid = ".$cid.$add)->order("id asc")->limit($pagesize)->fetchAll();
            $data['replays'] = $plnxt;

        }
        return $data;
    }

    public function  getRecommendByTag($tags){
        if($tags){
            $tags = explode(",",$tags);
            $keys="";
            foreach ($tags as $t){
                $keys.="*".$t."*,";
            }
            $rs =  $this->getORM()->select("id,title,source_name,SUBSTRING_INDEX(cover,'||||||',1) as cover,comment_count,tips,video_value,newstime")->where("match(title) against ('".addslashes($keys)."' IN BOOLEAN MODE)")->order('lastdotime desc')->limit(8)->fetchAll();
            if(!$rs){
                $prefix = DI()->config->get('dbs.tables.__default__.prefix');
                return $this->getORM()->queryAll("SELECT b1.id,title,source_name,SUBSTRING_INDEX(cover,'||||||',1) as cover,comment_count,tips,video_value,newstime FROM ".$prefix."ecms_info AS b1 JOIN (SELECT	ROUND(RAND() * ((SELECT MAX(id) FROM ".$prefix."ecms_info)-(SELECT MIN(id) FROM ".$prefix."ecms_info )) + (SELECT MIN(id) FROM ".$prefix."ecms_info)) AS id) AS b2 WHERE  b1.id>=b2.id limit 8");
            }else{
                return $rs;
            }

        }else{
            $prefix = DI()->config->get('dbs.tables.__default__.prefix');
            return $this->getORM()->queryAll("SELECT b1.id,title,source_name,SUBSTRING_INDEX(cover,'||||||',1) as cover,comment_count,tips,video_value,newstime FROM ".$prefix."ecms_info AS b1 JOIN (SELECT	ROUND(RAND() * ((SELECT MAX(id) FROM ".$prefix."ecms_info)-(SELECT MIN(id) FROM ".$prefix."ecms_info )) + (SELECT MIN(id) FROM ".$prefix."ecms_info)) AS id) AS b2 WHERE  b1.id>=b2.id limit 8");
        }



    }
    public  function getZan($id,$uid){
        return DI()->notorm->info_zan->select("id,zan")->where("uid = ? and info_id = ?",$uid,$id)->limit(1)->fetch();
    }

    public  function getCommentById($cid,$uid){
        return DI()->notorm->info_comment_zan->select("id")->where("uid = ? and cid = ?",$uid,$cid)->limit(1)->fetch();
    }

    public function  CheckCommentExist($id){
        return DI()->notorm->info_comment->select("id")->where(" id = ?",$id)->limit(1)->fetch();
    }

    public function  CheckCommentExistByMsg($uid,$info_id,$msg){
        return DI()->notorm->info_comment->select("id")->where(" uid = ? and info_id = ? and msg = ?",$uid,$info_id,addslashes($msg))->limit(1)->fetch();
    }

    public function  CheckInfoById($id){
        return $this->getORM()->select('id,title,introduction,SUBSTRING_INDEX(cover,"||||||",1) as pic')->where("id",$id)->limit(1)->fetch();
    }


    public function  getInfoById($id){
        return $this->getORM()->select('id,title')->where("id",$id)->limit(1)->fetch();
    }
    public function getAd($classid=0){
        if($classid ==255 || $classid ==0){
            $add = " starttime<=".time()."  and endtime>".time();
        }elseif($classid==6){
            $add = " starttime<=".time()."  and endtime>".time()." and changet=3";
        }else{
            $add = " (classid=$classid or classid=0)and starttime<=".time()."  and endtime>".time();
        }
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');
        return $this->getORM()->queryAll("SELECT b1.adid,adtitle,changet,picurl,pic_width,pic_height,adurl,description FROM ".$prefix."ad AS b1 JOIN (SELECT	ROUND(RAND() * ((SELECT MAX(adid) FROM ".$prefix."ad where $add)-(SELECT MIN(adid) FROM ".$prefix."ad where $add)) + (SELECT MIN(adid) FROM ".$prefix."ad where $add)) AS adid) AS b2 WHERE $add and b1.adid>=b2.adid limit 3");
    }

    protected function getTableName($id) {
        return 'ecms_info';
    }
}
