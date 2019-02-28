<?php

class House_village_user_unbindModel extends Model
{


    /*得到小区下所有的解绑列表*/
    public function get_limit_list_page($village_id, $pageSize = 20, $condition_where = array())
    {
        if (!$village_id) {
            return null;
        }
        $return = array();
        $condition_where['village_id'] = $village_id;
    
       
        $count = $this->where($condition_where)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count, $pageSize, 'page');
        $list = $this->field(true)->where($condition_where)->order('status ASC,`itemid` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        // var_dump($this->_sql());
        foreach($list as &$v){
            $floor_info = D('House_village_floor')->where(array('floor_id'=>$v['floor_id']))->find();
            $room_info = D('House_village_user_vacancy')->where(array('pigcms_id'=>$v['room_id']))->find();
            $v['address'] = $floor_info['floor_layer']." - ".$floor_info['floor_name']." - ".$room_info['layer']."#".$room_info['room'];
        }


        if ($list) {
            $return['totalPage'] = ceil($count / $pageSize);
            $return['user_count'] = count($list);
            $return['pagebar'] = $p->show();
            $return['list'] = $list;
        }

        return $return;
    }

}
