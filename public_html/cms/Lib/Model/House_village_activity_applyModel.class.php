<?php
class House_village_activity_applyModel extends Model{
    public function house_village_activityapply_add($data){
        if(!$data){
            return false;
        }
        
        if(empty($data['name'])){
            return array('status'=>0,'msg'=>'姓名不能为空！');
        }
        
        if(empty($data['phone'])){
            return array('status'=>0,'msg'=>'手机号码不能为空！');
        }

        if(!C('config.international_phone') && !preg_match('/^[0-9]{11}$/',$data['phone'])){
            return array('status'=>0,'msg'=>'手机号码格式不正确！');
        }
        
        if(empty($data['apply_num'])){
            return array('status'=>0,'msg'=>'报名人数不能为空！');
        }
        if(!is_numeric($data['apply_num'])){
            return array('status'=>0,'msg'=>'报名人数必须为数字！');
        }
        
        
        
        $database_house_village_user_bind = D('House_village_user_bind');
        $database_house_village_activity = D('House_village_activity');
        
        $where['phone'] = $data['phone'];
        $where['parent_id'] = 0;
        $where['village_id'] = $_SESSION['now_village_bind']['village_id'];
        $bind_info = $database_house_village_user_bind->where($where)->find();
        
        if(!$bind_info){
            return array('status'=>0,'msg'=>'手机号不是该小区业主！');
        }
        
        $data['user_bind_id'] = $bind_info['uid'];
        
        $Map['id'] = $data['activity_id'];
        $now_activity = $database_house_village_activity->house_village_activity_detail($Map);
        $now_activity = $now_activity['detail'];
        
        $apply_where['activity_id'] = $data['activity_id'];
        $apply_where['is_del'] = 0;
        $apply_where['apply_status'] = 1;
        $now_apply_sum = $this->where($apply_where)->sum('apply_num');
        if($now_activity['apply_now_num']){
            if($data['apply_num'] > ($now_activity['apply_now_num'] - $now_apply_sum)){
                return array('status'=>0,'msg'=>'报名人数超出限额！');
            }
        }else if($now_activity['apply_limit_num']){
            if($data['apply_num'] > ($now_activity['apply_limit_num'] - $now_apply_sum)){
                return array('status'=>0,'msg'=>'报名人数超出限额！');
            }
        }
        
        $data['apply_time'] = time();
        $data['village_id'] = $_SESSION['now_village_bind']['village_id'];
        $data['apply_status'] = 1;
        $insert_id = $this->data($data)->add();

        if($insert_id){
            return array('status'=>1,'msg'=>'添加成功！');
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }
    
    
    public function house_village_activity_apply_page_list($where , $field = true , $order = 'id desc',$pageSize = 20){
        if(!$where){
            return false;
        }
        
        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $house_village_visitor_list = $this->where($where)->field($field)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        
        $activityIdArr = array();
        foreach($house_village_visitor_list as $k=>$v){
            array_push($activityIdArr,$v['activity_id']);
            $house_village_visitor_list[$k]['url'] = str_replace('shequ.php','wap.php',U('Wap/House/village_activity',array('village_id'=>$v['village_id'],'id'=>$v['activity_id'])));
        }
        
        $database_house_village_activity = D('house_village_activity');
        $Map['id'] = array('in',$activityIdArr);
        $activity_info = $database_house_village_activity->where($Map)->getField('id,title');
        foreach($house_village_visitor_list as $k=>$v){
            $house_village_visitor_list[$k]['activity_name'] = $activity_info[$v['activity_id']];
        }
        
        $list['list'] = $house_village_visitor_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }
    
    
    public function house_village_activity_apply_del($where){
        if(!$where){
            return false;
        }
        
        $insert_id = $this->where($where)->setField('is_del',1);
        if($insert_id){
            return array('status'=>1,'msg'=>'删除成功！');
        }else{
            return array('status'=>1,'msg'=>'删除失败！');
        }
    }
    
    
    public function house_village_activity_apply_detail($where,$field = true){
        if(!$where){
            return false;
        }
        $detail = $this->where($where)->field($field)->find();
        if($detail){
            $database_house_village_activity = D('house_village_activity');
            $Map['id'] = $detail['activity_id'];
            $detail['activity_name'] = $database_house_village_activity->where($Map)->getField('title');
            $detail['url'] = str_replace('shequ.php', 'wap.php', U('Wap/House/village_activity',array('village_id'=>$detail['village_id'],'id'=>$detail['activity_id'])));
        }
        
        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }
    
    
    public function house_village_activity_apply_edit($where,$data){
        if(!$where || !$data){
            return false;
        }
        
        $data['last_time'] = time();
        $insert_id = $this->data($data)->where($where)->save();
        if($insert_id){
            return array('status'=>1,'msg'=>'修改成功！');
        }else{
            return array('status'=>0,'msg'=>'修改失败！');
        }
    }
}
?>