<?php

class Model_Tk extends PhalApi_Model_NotORM {

    //首页推荐
    public  function getIndexTuiJian($min_id,$back){
        $apiurl = DI()->config->get('sys.tk.apiurl');
        $apikey = DI()->config->get('sys.tk.apikey');
        $url = "{$apiurl}/itemlist/apikey/{$apikey}/nav/2/cid/0/back/{$back}/min_id/{$min_id}";

        $data = json_decode(curl_post($url),true);
        return array('min_id'=>$data['min_id'],'list'=>$this->getRs($data['data']));
    }

    //分类商品列表
    public function getLists($cid,$sort,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$min_id,$back){

        $apiurl = DI()->config->get('sys.tk.apiurl');
        $apikey = DI()->config->get('sys.tk.apikey');
        $add="";
        if($price_min){$add.="/price_min/$price_min";}
        if($price_max){$add.="/price_max/$price_max";}
        if($sale_min){$add.="/sale_min/$sale_min";}
        if($sale_max){$add.="/sale_max/$sale_max";}
        if($coupon_min){$add.="/coupon_min/$coupon_min";}
        if($coupon_max){$add.="/coupon_max/$coupon_max";}

        $url = "{$apiurl}/itemlist/apikey/{$apikey}/nav/3/cid/{$cid}".$add."/sort/{$sort}/back/{$back}/min_id/{$min_id}";

        $data = json_decode(curl_post($url),true);
        return array('min_id'=>$data['min_id'],'list'=>$this->getRs($data['data']));
    }

    //类型搜索
    public function getTypeLists($type,$cid,$sort,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$min_id,$back){

        $apiurl = DI()->config->get('sys.tk.apiurl');
        $apikey = DI()->config->get('sys.tk.apikey');
        $add="";
        if($cid){$add.="/cid/$cid";}
        if($price_min){$add.="/price_min/$price_min";}
        if($price_max){$add.="/price_max/$price_max";}
        if($sale_min){$add.="/sale_min/$sale_min";}
        if($sale_max){$add.="/sale_max/$sale_max";}
        if($coupon_min){$add.="/coupon_min/$coupon_min";}
        if($coupon_max){$add.="/coupon_max/$coupon_max";}

        $url = "{$apiurl}/column/apikey/{$apikey}/type/".$type.$add."/sort/{$sort}/back/{$back}/min_id/{$min_id}";
        $data = json_decode(curl_post($url),true);
        return array('min_id'=>$data['min_id'],'list'=>$this->getRs($data['data']));
    }

    //超级搜索
    public function getSearchLists($keywords,$tb_p,$sort,$is_tmall,$is_coupon,$min_id,$back){
        $apiurl = DI()->config->get('sys.tk.apiurl');
        $apikey = DI()->config->get('sys.tk.apikey');
        $add="";
        if($tb_p){$add.="/tbid/$tb_p";}
        if($is_tmall){$add.="/is_tmall/$is_tmall";}
        if($is_coupon){$add.="/is_coupon/$is_coupon";}
        $keywords = urlencode(urlencode($keywords));
        $url = "{$apiurl}/supersearch/apikey/{$apikey}/keyword/".$keywords.$add."/sort/{$sort}/back/{$back}/min_id/{$min_id}";
        $data = json_decode(curl_post($url),true);
        return array('tb_p'=>$data['tb_p'],'min_id'=>$data['min_id'],'list'=>$this->getRs($data['data']));
    }
    //热门关键词
    public function getHotKey(){
        $apiurl = DI()->config->get('sys.tk.apiurl');
        $apikey = DI()->config->get('sys.tk.apikey');
        $url = "{$apiurl}/hot_key/apikey/{$apikey}/";
        $data = json_decode(curl_post($url),true);
        $data =  $data['data'];
        $c = count($data);
        if($c<=20){
            return $data;
        }else{
            $array = array();
            for($i=0;$i<20;$i++){
                $array[$i]['keyword'] = $data[$i]['keyword'];
            }
            return $array;
        }
    }

    //详细
    public function getDetail($itemid,$activityid){
        $rs = array();
        $pid = explode("_",DI()->config->get('sys.tk.pid'));
        $session = DI()->config->get('sys.tk.session');
        $url = "http://gy.yishoudan.com/ysd_api.php?item_id=".$itemid."&adzone_id=".$pid[3]."&site_id=".$pid[2]."&session=".$session."&get_click_url=1";
        $item = json_decode(curl_post($url),true);
        $sufix = DI()->config->get('sys.tk.big_w_h');
        if($item['tbk_sc_material_optional_response']['total_results']){
            $item = $item['tbk_sc_material_optional_response']['result_list']['map_data'][0];
            $couponurl = 'https://uland.taobao.com/coupon/edetail?itemId='.$item['itemid'].'&activityId='.$item['activityid'].'&pid='.DI()->config->get('sys.tk.pid');
            $rs['itemid'] = $item['num_iid'];
            $rs['itempic'] = $item['pict_url'].$sufix;
            $rs['itemtitle'] = $item['title'];
            $rs['itemshorttitle'] = $item['short_title'];
            $rs['itemdesc'] = "";
            $rs['itemsale'] = (string)$item['volume'];
            $rs['itemprice'] = (string)$item['zk_final_price'];

            $couponmoney=$itemendprice="";
            if($item['coupon_info']){
                $coupon_info = str_replace("元","",$item['coupon_info']);
                $coupon_info = explode("减",$coupon_info);
                $couponmoney = $coupon_info[1];
                $itemendprice =  $rs['itemprice'] - $couponmoney;
            }

            $rs['itemendprice'] = (string)$itemendprice;
            $rs['couponmoney'] = (string)$couponmoney;
            $rs['shoptype'] = $item['shoptype']?"B":"C";
            $rs['videourl'] = "";
            $rs['couponexplain'] = $item['coupon_info']?$item['coupon_info']:"";
            $rs['couponstarttime'] = $item['coupon_start_time']?$item['coupon_start_time']:"";
            $rs['couponendtime'] = $item['coupon_end_time']?$item['coupon_end_time']:"";
            $rs['couponurl'] = $item['coupon_share_url']?"https:".$item['coupon_share_url']."&activityid=".$activityid:"https:".$item['url'];
            $rs['shareurl'] = DI()->config->get('sys.xsapp.homeurl')."share/index.php?tbid=".$itemid."&activityid=".$activityid;
            $rs['contenturl'] = DI()->config->get('sys.xsapp.homeurl')."share/content.php?tbid=".$itemid;
            $rs['small_images'] = $item['small_images']['string']?$item['small_images']['string']:array();

            $string = $rs['couponurl']."\t".$rs['itemid']."\t".$rs['itempic']."\t".$rs['itemshorttitle']."\t".(md5($rs['couponurl'].$rs['itemid']));
            $rs['tklToken'] = base64_encode_s(authcode($string,"ENCODE"));
        }else{
            $rs['shareurl'] = DI()->config->get('sys.xsapp.homeurl')."share/?tbid=".$itemid."&activityid=".$activityid;
            $rs['contenturl'] = DI()->config->get('sys.xsapp.homeurl')."share/content.php?tbid=".$itemid;
        }
        return array('list'=>$rs);
    }


    public function getRs($data){
        $rs = array();
        $sufix = DI()->config->get('sys.tk.big_w_h');
        $i=0;
        foreach((array)$data as $item){
            $couponurl = 'https://uland.taobao.com/coupon/edetail?itemId='.$item['itemid'].'&activityId='.$item['activityid'].'&pid='.DI()->config->get('sys.tk.pid');
            $rs[$i]['product_id'] = $item['product_id']?$item['product_id']:"0";
            $rs[$i]['itemid'] = $item['itemid'];
            $rs[$i]['itempic'] = $item['itempic'].$sufix;
            $rs[$i]['itemtitle'] = $item['itemtitle'];
            $rs[$i]['itemshorttitle'] = $item['itemshorttitle'];
            $rs[$i]['itemdesc'] = $item['itemdesc'];
            $rs[$i]['itemsale'] = $item['itemsale'];
            $rs[$i]['itemprice'] = $item['itemprice'];
            $rs[$i]['itemendprice'] = $item['itemendprice'];
            $rs[$i]['couponmoney'] = $item['couponmoney'];
            $rs[$i]['shoptype'] = $item['shoptype'];
            $rs[$i]['videourl'] = $item['videourl']?"http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/".$item['videourl'].".mp4":"";
            $rs[$i]['couponexplain'] = $item['couponexplain']?$item['couponexplain']:"";
            $rs[$i]['couponstarttime'] = $item['couponstarttime']?date("Y-m-d",$item['couponstarttime']):"";
            $rs[$i]['couponendtime'] = $item['couponendtime']?date("Y-m-d",$item['couponendtime']):"";
            $rs[$i]['is_brand'] = $item['is_brand']?$item['is_brand']:"0";
            $rs[$i]['couponurl'] = $couponurl;
            $rs[$i]['activityid'] = $item['activityid'];
            $string = $rs[$i]['couponurl']."\t".$rs[$i]['itemid']."\t".$rs[$i]['itempic']."\t".$rs[$i]['itemshorttitle']."\t".(md5($rs[$i]['couponurl'].$rs[$i]['itemid']));
            $rs[$i]['tklToken'] =base64_encode_s(authcode($string,"ENCODE"));

            $i++;
        }
        return $rs;
    }


    protected function getTableName($id) {
        return '';
    }
}
