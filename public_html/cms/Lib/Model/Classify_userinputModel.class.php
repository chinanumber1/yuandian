<?php
class Classify_userinputModel extends Model{
    public function wap_get_order_list($uid , $status = 0){
        $condition_where = "`o`.`uid`='$uid' AND `o`.`classify_userinput_id`=`g`.`id`";

        if($status == '0'){
            $condition_where .= " AND `o`.`status`<=3";
        }else if($status == '-1'){
            $condition_where .= " AND `o`.`paid`='0' AND `o`.`status`='0'";
        }else if($status == '1'){
            $condition_where .= " AND `o`.`paid`='1'";
            $condition_where .= " AND `o`.`status`='0'";
        }else if($status == '2'){
            $condition_where .= " AND `o`.`paid`='1'";
            $condition_where .= " AND `o`.`status`='1'";
        }else if($status == '3'){
            $condition_where .= " AND `o`.`paid`='1'";
            $condition_where .= " AND `o`.`status`='2'";
        }

        $condition_table = array(C('DB_PREFIX').'classify_userinput'=>'g',C('DB_PREFIX').'classify_order'=>'o');

        $order_list = $this->field('`o`.*,`g`.`title`')->where($condition_where)->table($condition_table)->order('`o`.`order_time` DESC')->select();

        return $order_list;
    }
}
?>