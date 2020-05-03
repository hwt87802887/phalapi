<?php

class Domain_Welfare {

    protected $num = 10;
    public function  getIndex($token){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $model = new Model_Welfare();
        $list = $model->getIndex($auth['uid']);
        return array('code'=>0,'list'=>$list);
    }

    public function  getSign($token){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $model = new Model_Welfare();
        return $model->getSign($auth['uid']);
    }

    public function getReceiveAward($token,$type){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $model = new Model_Welfare();
        return $model->getReceiveAward($auth['uid'],$type);
    }

    public function getShare($token){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $model = new Model_Welfare();
        return $model->getShare($auth['uid']);
    }

    public function getReadNews($token,$min_token,$info_id){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $min_token = authcode(base64_decode_s($min_token),'DECODE');

        if(!$min_token){
            return array('code'=>3,'msg'=>'资讯验证失败');
        }

        list($id,$min_time) = explode('[\t]',$min_token);
        if($id!=$info_id){
            return array('code'=>3,'msg'=>'信息验证失败');
        }
        $news = new Model_News();
        $info = $news->getInfoById($id);
        if(!$info){
            return array('code'=>3,'msg'=>'信息不存在');
        }

        $zxmiao = DI()->config->get('fuli.fuli.zxmiao');
        if(time()-$min_time < $zxmiao){
            return array('code'=>3,'msg'=>'未到奖励时间，请继续阅读');
        }

        $zxmin = DI()->config->get("fuli.fuli.zxmin");
        $zxmax = DI()->config->get("fuli.fuli.zxmax");
        $gold = mt_rand($zxmin,$zxmax);

        $model = new Model_Welfare();
        return $model->getReadNews($auth['uid'],$info['id'],$gold);
    }

    public function getReadBook($token,$min_token,$info_id){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $min_token = authcode(base64_decode_s($min_token),'DECODE');
        if(!$min_token){
            return array('code'=>3,'msg'=>'试读验证失败');
        }
        list($id,$qudao,$book_id,$chapte_id,$sdmiao,$sdmoney,$min_time) = explode('[\t]',$min_token);

        if($chapte_id!=$info_id){
            return array('code'=>3,'msg'=>'信息验证失败');
        }
        $model = new Model_Welfare();

        if(time()-$min_time < $sdmiao){
            return array('code'=>3,'msg'=>'未到奖励时间，请继续试读');
        }
        
        return $model->getReadBook($auth['uid'],$book_id,$chapte_id,$sdmoney);
    }

    public function getLottery($token){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }
        $model = new Model_Welfare();
        return $model->getLottery($auth['uid']);
    }

}
