<?php

class Domain_Tk {

    protected $num = 10;
    protected $backarr = array(1,2,10,20,50,100,120,200,500,1000);
    protected $backarr2 = array(1,2,10,20,50,100);
    protected $typearr = array(1,2,3,4,5,8,9,10);
    public function  getIndexTuiJian($min_id,$back){
        $min_id = intval($min_id)?intval($min_id):1;
        $back = intval($back)?intval($back):$this->num;
        if(!in_array($back,$this->backarr)){
            return array('code'=>1,'msg'=>'back参数请在1,2,10,20,50,100,120,200,500,1000选择其中之一。','min_id'=>"1",'list'=>array());
        }
        $model = new Model_Tk();
        return $model -> getIndexTuiJian($min_id,$back);
    }

    public function  getLists($cid,$sort,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$min_id,$back){
        $min_id = intval($min_id)?intval($min_id):1;
        $back = intval($back)?intval($back):$this->num;
        $cid = intval($cid)?intval($cid):0;
        $sort = intval($sort)?intval($sort):0;
        if($cid<0){
            return array('code'=>1,'msg'=>'缺少必要参数','min_id'=>"1",'list'=>array());
        }
        if(!in_array($back,$this->backarr)){
            return array('code'=>1,'msg'=>'back参数请在1,2,10,20,50,100,120,200,500,1000选择其中之一。','min_id'=>"1",'list'=>array());
        }

        if($cid==0){
            return $this->getIndexTuiJian($min_id,$back);
        }

        $model = new Model_Tk();
        return $model -> getLists($cid,$sort,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$min_id,$back);
    }

    public function  getTypeLists($type,$cid,$sort,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$min_id,$back){
        $min_id = intval($min_id)?intval($min_id):1;
        $back = intval($back)?intval($back):$this->num;
        $sort = intval($sort)?intval($sort):0;
        $cid = intval($cid)?intval($cid):0;
        if(!in_array($back,$this->backarr)){
            return array('code'=>1,'msg'=>'back参数请在1,2,10,20,50,100,120,200,500,1000选择其中之一。','min_id'=>"1",'list'=>array());
        }

        if(!in_array($type,$this->typearr)){
            return array('code'=>1,'msg'=>'type参数请在2,3,4,5,8,9,10选择其中之一。','min_id'=>"1",'list'=>array());
        }

        $model = new Model_Tk();
        return $model -> getTypeLists($type,$cid,$sort,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$min_id,$back);
    }

    public function getSearchLists($keywords,$tb_p,$sort,$is_tmall,$is_coupon,$min_id,$back){
        $min_id = intval($min_id)?intval($min_id):1;
        $back = intval($back)?intval($back):$this->num;
        $tb_p = intval($tb_p)?intval($tb_p):1;
        $sort = intval($sort)?intval($sort):0;
        $is_tmall = intval($is_tmall)?intval($is_tmall):0;
        $is_coupon = intval($is_coupon)?intval($is_coupon):0;
        if(!in_array($back,$this->backarr2)){
            return array('code'=>1,'msg'=>'back参数请在1,2,10,20,50,100选择其中之一。','min_id'=>"1",'list'=>array());
        }

        if(trim($keywords)==''){
            return array('code'=>1,'msg'=>'搜索关键词不能为空','tb_p'=>"1",'min_id'=>"1",'list'=>array());
        }

        $model = new Model_Tk();
        return $model -> getSearchLists(trim($keywords),$tb_p,$sort,$is_tmall,$is_coupon,$min_id,$back);

    }
    
    public function getHotKey(){
        $model = new Model_Tk();
        return $model -> getHotKey();
    }

    public function getDetail($itemid,$activityid){
        if(!$itemid){
            return array('code'=>2,'msg'=>'缺少必要的参数','list'=>array());
        }
        $model = new Model_Tk();
        $rs =  $model->getDetail($itemid,$activityid);
		if(!$rs['list']['itemid']){
			return array('code'=>1,'msg'=>'失败','list'=>$rs['list']);
		}else{
			return array('code'=>0,'msg'=>'成功','list'=>$rs['list']);
		}
    }
    
    public function getTkl($token){
        if(!$token){
            return array('code'=>1,'msg'=>'缺少必要的参数','list'=>array());
        }
        $auth = authcode(base64_decode_s($token),'DECODE');
        list($url,$itemid,$itempic,$itemtitle,$md5) = explode("\t",$auth);

        if($md5!=md5($url.$itemid)){
            return array('code'=>1,'msg'=>'Token验证失败','list'=>array('tkl'=>'','content'=>''));
        }
        if($url==''){
            return array('code'=>1,'msg'=>'获取淘口令失败','list'=>array('tkl'=>'','content'=>''));
        }

        $tkllink = "http://www.haopianyi.com/e/admin/taobao/api_tkl.php?logo=".urlencode($itempic)."&url=".urlencode($url)."&text=".urlencode($itemtitle);
        $tkl = curl_post($tkllink);
        if($tkl){
            return array('code'=>0,'msg'=>'成功','list'=>array('tkl'=>$tkl,'content'=>'复制框内整段文字，打开【手机淘宝】即可【领取优惠券】并购买'.$tkl));
        }else{
            return array('code'=>1,'msg'=>'无法获取口令','list'=>array('tkl'=>'','content'=>''));
        }

    }


}
