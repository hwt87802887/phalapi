<?php

class Model_Default extends PhalApi_Model_NotORM {

    public  function getIndexTuiJian($token,$channel,$id,$pagesize){
        $where = " (classid = 3 ";
        $uid = 0;
        if($token){
            $user = new Model_User();
            $auth = $user->checkToken($token);
            if($auth['code']==0) {
                $u = $user->getuserInfo($auth['uid']);
                $channel = $u['sex']?$u['sex']:$channel;
                $uid = $auth['uid'];
            }
        }
        if($channel>0){
            $where.= " and (channel=0 or channel = $channel ) ";
        }
        if($uid) {
            $where.= " or userid=$uid ";
        }
        $where.=" ) ";
		$where.= ($id==1)?"":" and id < $id ";
        $rs = $this->getORM()->select('id,classid as typeid,orderid,title,titlepic,book_id,chapte_id,description,tuji')->where($where)->order('id desc')->limit($pagesize)->fetchAll();
        foreach($rs as $rk=>$item){
			
            $tuji = array();
            if($item['tuji']){
                $tuji2 = explode("||||||",$item['tuji']);
                $j=0;
                foreach ($tuji2 as $k=>$dlist){
                    if($dlist!="::::::") {
                        $dlist = explode("::::::", $dlist);
                        if ($dlist[0] && $dlist[0] != "::::::") {
                            $tuji[$j] = array('titlepic' => $dlist[0], 'description' => $dlist[1],'book_id' => $dlist[2]?$dlist[2]:"0", 'chapte_id' => $dlist[3]?$dlist[3]:"0");
                        }
                        $j++;
                    }
                }
            }
            $rs[$rk]['tuji'] = $tuji;
            $rs[$rk]['paytype'] = "";
			if($item['orderid']){
				$rs[$rk]['typeid']="1";
                $pay = new Model_Pay();
                $order = $pay->getOrderByOrderId($item['orderid']);
                if($order){
                    $rs[$rk]['paytype'] = $order['paytype'];
                }
			}else{
				$rs[$rk]['typeid']="2";
			}
        }
        return $rs;
    }

    public function getBanner($classid){
        return DI()->notorm->enewslink->select("lid,lname,lpic,book_id,lurl")->where("classid = ".$classid." and checked = 1 ")->order("myorder desc,lid desc")->limit(5)->fetchAll();
    }

    public function getTags(){
        return DI()->notorm->enewskey->select("keyid as id,keyname as tags")->limit(20)->fetchAll();
    }

    protected function getTableName($id) {
        return 'ecms_news';
    }
}
