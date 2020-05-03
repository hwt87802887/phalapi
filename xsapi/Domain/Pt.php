<?php

class Domain_Pt {

    protected $num = 5;
    public function  getIndexTuiJian(){
        $model = new Model_Pt();
        return $model -> getIndexTuiJian();
    }

    public function getLists($id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $model = new Model_Pt();
        return $model -> getLists($id,$pagesize);
    }

    public function getDetail($id){
        $id = intval($id)?intval($id):0;
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }
        $model = new Model_Pt();
        return $model->getDetail($id);
    }

    public function geUnfinished($id,$pid,$pagesize){
        $id = intval($id)?intval($id):0;
        $pid = intval($pid)?intval($pid):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }
        $model = new Model_Pt();
        return $model->geUnfinished($id,$pid,$pagesize);
    }
    
    public function getPtDetail($infoid){
        $infoid = intval($infoid)?intval($infoid):0;
        if($infoid<=0){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }
        $model = new Model_Pt();
        return $model->getPtDetail($infoid);
    }

    //订单查询
    public function getCheckOrder($token,$orderid){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }

        $model = new Model_Pt();
        $rs = $model->getCheckOrder($auth['uid'],addslashes($orderid));

        if(!$rs){
            return array('code'=>1,'msg'=>'订单信息不存在','list'=>array());
        }else{
            return array('code'=>0,'msg'=>'成功','list'=>$rs);
        }
    }

    //中奖名单
    public function getWinList($id){
        $id = intval($id);
        if($id<=0){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }
        $model = new Model_Pt();
        $rs = $model->getWinList($id);
        return array('code'=>$rs['code'],'msg'=>$rs['msg'],'list'=>$rs['list']);
    }

}
