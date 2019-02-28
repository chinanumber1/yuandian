<?php
class Community_dynamicModel extends Model
{
    //获取动态-帖子多条信息
    public function dynamic_select($Distinct=false,$field,$join,$where,$order='',$limit){
    	$result = D('Community_dynamic')
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

    //获取动态-帖子单条信息
    public function dynamic_find($Distinct=false,$field,$join,$where){
        $result = D('Community_dynamic')
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