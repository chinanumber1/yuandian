<?php
class Community_replyModel extends Model
{
    public function reply_select($Distinct=false,$field,$join,$where,$order='',$limit){
    	$result = D('Community_reply')
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
}
?>