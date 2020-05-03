<?php

class Domain_Asyn {

    public function  SetBookHistory($uid,$book_id,$chapte_id,$chapte_name){
        $model = new Model_Asyn();
        return $model -> SetBookHistory($uid,$book_id,$chapte_id,$chapte_name);
    }

    public function  SetBookSelf($uid,$book_id){
        $model = new Model_Asyn();
        return $model -> SetBookSelf($uid,$book_id);
    }
    
    public function SetBookLike($uid,$book_id){
        $model = new Model_Asyn();
        return $model -> SetBookLike($uid,$book_id);
    }

    public function SetBookComments($param){
        $param = urldecode($param);
        $model = new Model_Asyn();
        $param= json_decode($param,true);
        if(!$param || empty($param)  || !is_array($param)){
            return array('code'=>1,'msg'=>'参数错误');
        }
        return $model -> SetBookComments($param);
    }
    
    public function SetVoteComment($uid,$id,$act){
        $model = new Model_Asyn();
        return $model -> SetVoteComment($uid,$id,$act);
    }
    
    public  function SetBookGift($param){
        $param = urldecode($param);
        $model = new Model_Asyn();
        $param= json_decode($param,true);
        if(!$param || empty($param)  || !is_array($param)){
            return array('code'=>1,'msg'=>'参数错误');
        }
        return $model -> SetBookGift($param);
    }

    public function SetInfoRead($info_id){
        $model = new Model_Asyn();
        if(!$info_id){
            return array('code'=>1,'msg'=>'参数错误');
        }
        return $model -> SetInfoRead($info_id);
    }


    public function SetInfoZan($uid,$info_id,$zan){
        $model = new Model_Asyn();
        return $model -> SetInfoZan($uid,$info_id,$zan);
    }

    public function SetVoteCommentInfo($uid,$id){
        $model = new Model_Asyn();
        return $model -> SetVoteCommentInfo($uid,$id);
    }

    public function SetInfoComments($param){
        $param = urldecode($param);
        $model = new Model_Asyn();
        $param= json_decode($param,true);
        if(!$param || empty($param)  || !is_array($param)){
            return array('code'=>1,'msg'=>'参数错误');
        }
        return $model -> SetInfoComments($param);
    }

    public function SetAddAddress($adrid,$param){
        $param = urldecode($param);
        $adrid = intval($adrid);
        $model = new Model_Asyn();
        $param= json_decode($param,true);
        if(!$param || empty($param)  || !is_array($param)){
            return array('code'=>1,'msg'=>'参数错误');
        }
        return $model -> SetAddAddress($adrid,$param);
    }

    public function SetDefault($adrid,$uid){
        $model = new Model_Asyn();
        return $model -> SetDefault($adrid,$uid);
    }

    public function SetAdrDel($adrid,$uid){
        /*
        $data = urldecode($adrid);
        $data = json_decode($data,true);
        if(!$data || empty($data)  || !is_array($data)){
            return array('code'=>1,'msg'=>'缺少必要的参数');
        }
        $ids = array();
        foreach ($data as $item){
            if(is_numeric($item)){
                $ids[] = $item;
            }
        }*/
        $model = new Model_Asyn();
        return $model -> SetAdrDel($adrid,$uid);
    }

    public function SetWelfare($uid,$param,$fuid){
        $param = urldecode($param);
        $uid = intval($uid);
        $model = new Model_Asyn();
        $param= json_decode($param,true);
        if(!$param || empty($param)  || !is_array($param)){
            return array('code'=>1,'msg'=>'参数错误');
        }
        return $model -> SetWelfare($uid,$param,$fuid);
    }

    public function SetSign($uid,$signcount,$day,$gold){
        $model = new Model_Asyn();
        return $model -> SetSign($uid,$signcount,$day,$gold);
    }

    public function SetShare($uid,$type,$gold){
        $model = new Model_Asyn();
        return $model -> SetShare($uid,$type,$gold);
    }
    
    public function SetReadNews($uid,$info_id,$gold){
        $model = new Model_Asyn();
        return $model -> SetReadNews($uid,$info_id,$gold);
    }

    public function SetLottery($uid,$type,$fuli,$gold){
        $model = new Model_Asyn();
        return $model -> SetLottery($uid,$type,$fuli,$gold);
    }
    
    public function SetTixian($uid,$gold,$money,$total){
        $model = new Model_Asyn();
        return $model -> SetTixian($uid,$gold,$money,$total);
    }

    public function SetYaoQing($fuid,$uid,$type){
        $model = new Model_Asyn();
        return $model -> SetYaoQing($fuid,$uid,$type);
    }
    
    public function SetYuedubi($uid,$gold,$yuedubi){
        $model = new Model_Asyn();
        return $model -> SetYuedubi($uid,$gold,$yuedubi);
    }
    
    public function SetWszl($uid){
        $model = new Model_Asyn();
        return $model -> SetWszl($uid);
    }

    public function SetXinren($uid,$login_type){
        $model = new Model_Asyn();
        return $model -> SetXinren($uid,$login_type);
    }
}
