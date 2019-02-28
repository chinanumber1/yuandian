<?php
class Community_praiseModel extends Model
{
    public function praise_select($Distinct=false,$field,$join,$where,$order='',$limit){
    	$result = D('Community_praise')
                        ->alias('a')
                        ->Distinct($Distinct)
                        ->field($field)
                        ->join($join)
                        ->where($where)
                        ->order($order)
                        ->limit($limit)
                        ->select();
        if($result){
        	return $result;
        }                
    }

    //获取点赞单条信息
    public function praise_find($Distinct=false,$field,$join,$where){
        $result = D('Community_praise')
                        ->alias('a')
                        ->Distinct($Distinct)
                        ->field($field)
                        ->join($join)
                        ->where($where)
                        ->find();
        if($result){
            return $result;
        }  
    }
}
?>