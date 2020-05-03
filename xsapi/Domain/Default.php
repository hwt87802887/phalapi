<?php

class Domain_Default {

    protected $num = 10;
    public function  getIndexTuiJian($token,$channel,$id,$pagesize){
        $id = intval($id)?intval($id):1;
        $channel = intval($channel);
        if($channel<0 || $channel>2){
            $channel = 0;
        }
		$pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $model = new Model_Default();
        return $model -> getIndexTuiJian($token,$channel,$id,$pagesize);
    }

    public function  getBanner($classid){
        $classid = intval($classid)?intval($classid):1;
        $model = new Model_Default();
        return $model -> getBanner($classid);
    }

    public function getTags(){
        $model = new Model_Default();
        return $model -> getTags();
    }

}
