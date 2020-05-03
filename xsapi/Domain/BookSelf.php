<?php

class Domain_BookSelf {

    protected $num = 8;
    public function getTuijian($token){
        $model = new Model_BookSelf();
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $list = $model->getTuijian($auth['uid']);
        return array('code'=>0,'list'=>$list);
    }
    
    public function  getBookSelf($token,$id,$pagesize){
        $id = intval($id)?intval($id):1;
		$pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        
        $model = new Model_BookSelf();
        $list = $model -> getBookSelf($auth['uid'],$id,$pagesize);
        return array('code'=>0,'list'=>$list);
    }

    public function getDelBookSelf($token,$param){
        $data = json_decode(stripslashes($param),true);
        if(!$data || empty($data)  || !is_array($data)){
            return array('code'=>1,'msg'=>'缺少必要的参数');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        
        $model = new Model_BookSelf();
        $rs = $model->getDelBookSelf($auth['uid'],$data);
        return array('code'=>0,'msg'=>"成功删除书架");

        /*
        if($rs>0){
            return array('code'=>0,'msg'=>"删除成功");
        }else{
            return array('code'=>1,'msg'=>"删除失败");
        }*/
    }

    public function getDelHistory($token){

        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $model = new Model_BookSelf();
        $rs = $model->getDelHistory($auth['uid']);
        return array('code'=>0,'msg'=>"成功清除历史记录");

    }

    public function  getBookHistory($token,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $model = new Model_BookSelf();
        $list = $model -> getBookHistory($auth['uid'],$id,$pagesize);
        return array('code'=>0,'list'=>$list);
    }

}
