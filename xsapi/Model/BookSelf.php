<?php

class Model_BookSelf extends PhalApi_Model_NotORM {

    public function getTuijian($uid){
        $book = DI()->notorm->ecms_book->select('id as book_id,title as book_name,author,titlepic as book_url,book_type as tags,book_status,description')->where(" isgood = 3 ")->order('id desc')->limit(5)->fetchAll();
        $s = DI()->notorm->sign->select("*")->where("uid",$uid)->limit(1)->fetch();
        if(!$s){
            $status = "0";
        }else{
            $status = "1";
            $lasttime = date("Y-m-d 00:00:00",$s['lasttime']);
            $time = date("Y-m-d 00:00:00");
            if($time > $lasttime){
                $status = "0";
            }
        }
        $qiandao = DI()->config->get('fuli.fuli.qiandao');
        $qiandaodz = DI()->config->get('fuli.fuli.qiandaodz');
        if($status=="1"){
            $title = "今日已签到，获得#gold#金币";
            $description="每日签到领取奖励";
            $day = "1";
            $gold = $qiandao;
        }else{
            $title = "再签到#day#天，可领#gold#金币";
            $description="每日签到领取奖励";
            $day = "6";
            $gold = 0;
            for($i=1;$i<=6;$i++){
                $gold+=$qiandao + ($qiandaodz*$i);
            }
        }
        $sign = array('title'=>$title,'day'=>$day,'gold'=>$gold,'description'=>$description,'status'=>$status);
        return array('sign'=>$sign,'book'=>$book);
    }

    public  function getBookSelf($uid,$id,$pagesize){
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');

        if($id>1){
            $add=" and bs.id < $id";
        }
        $sql = 'select bs.id,bs.book_id,bs.book_name,bs.author,bs.book_url,bh.chapte_id,bh.chapte_name from '. $prefix.'ecms_book_self AS bs LEFT JOIN '. $prefix.'ecms_book_history AS bh ON bs.book_id= bh.book_id and bs.uid=bh.uid where bs.uid = '.$uid.$add.' ORDER BY bs.id desc LIMIT '.$pagesize;
        $rs= $this->getORM()->queryAll($sql);
        return $rs;
    }
    
    public function getDelBookSelf($uid,$data){
        return $this->getORM()->where('uid',$uid)->where('id',$data)->delete();
    }

    public  function getBookHistory($uid,$id,$pagesize){
        $prefix = DI()->config->get('dbs.tables.__default__.prefix');

        if($id>1){
            $add=" and bh.id < $id";
        }
        $sql = 'select bh.id,bh.book_id,b.title,b.author,b.titlepic,b.chapte_num,bh.chapte_id,bh.chapte_name from '. $prefix.'ecms_book_history AS bh inner JOIN '. $prefix.'ecms_book AS b ON bh.book_id= b.id  where bh.uid = '.$uid.$add.' ORDER BY bh.updatetime desc LIMIT '.$pagesize;
        $rs=DI()->notorm->ecms_book_history->queryAll($sql);
        return $rs;
    }


    public function getDelHistory($uid){
        return DI()->notorm->ecms_book_history->where('uid',$uid)->delete();
    }

    protected function getTableName($id) {
        return 'ecms_book_self';
    }
}
