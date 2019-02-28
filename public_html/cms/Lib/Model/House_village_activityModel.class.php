<?php
class House_village_activityModel extends Model{
    public function house_village_activity_add($data){
        if(!$data){
            return false;
        }
        if(empty($data['title'])){
            return array('status'=>0,'msg'=>'活动标题不能为空！');
        }

        if(empty($data['pic'])){
            return array('status'=>0,'msg'=>'图片不能为空');
        }

        $data['pic'] = implode(';',$data['pic']);

        if(empty($data['content'])){
            return array('status'=>0,'msg'=>'活动内容不能为空！');
        }
        
        $data['content'] = htmlspecialchars_decode($data['content']);

        if($data['activity_start_time'] == $data['activity_end_time']){
            $data['activity_end_time'] = strtotime($data['activity_end_time'].' 23:59:59');
        }else{
            $data['activity_end_time'] = strtotime($data['activity_end_time']);
        }
        $data['activity_start_time'] = strtotime($data['activity_start_time']);
        if($data['activity_start_time'] > $data['activity_end_time']){
            return array('status'=>0,'msg'=>'活动开始时间必须小于活动结束时间！');
        }
        /*if($data['apply_start_time'] == $data['apply_end_time']){
            $data['apply_end_time'] = strtotime($data['apply_end_time'].' 23:59:59');
        }else{
            $data['apply_end_time'] = strtotime($data['apply_end_time']);
        }*/
      //  $data['apply_start_time'] = strtotime($data['apply_start_time']);


        /*if($data['apply_start_time'] > $data['apply_end_time']){
            return array('status'=>0,'msg'=>'报名开始时间必须小于报名结束时间！');
        }*/

        if($data['stop_apply_time']){
            $data['apply_end_time'] = strtotime($data['stop_apply_time']);
			$data['stop_apply_time'] = strtotime($data['stop_apply_time']); 
        }

        if($data['stop_apply_time'] > $data['activity_start_time']){
            return array('status'=>0,'msg'=>'报名截止时间必须小于活动开始时间！');
        }



        $data['add_time'] = time();
        $data['village_id'] = $_SESSION['house']['village_id'];
        $insert_id = $this->data($data)->add();
        if($insert_id){
            return array('status'=>1,'msg'=>'添加成功！');
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }

    public function house_village_activity_page_list($where , $field = true , $order = 'id desc',$pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $database_house_village_activity_apply = D('House_village_activity_apply');
        $house_village_activity_list = $this->where($where)->field($field)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

        foreach($house_village_activity_list as $Key=>$activity){
            $house_village_activity_list[$Key]['url'] = str_replace('shequ.php','wap.php',U('Wap/House/village_activity',array('village_id'=>$activity['village_id'],'id'=>$activity['id'])));
            $Map['activity_id'] = $activity['id'];
            $Map['is_del'] = 0;
            $Map['apply_status'] = 1;
            $now_apply_sum = $database_house_village_activity_apply->where($Map)->sum('apply_num');
            $now_apply_sum = $now_apply_sum ? $now_apply_sum : 0 ;
            $house_village_activity_list[$Key]['now_apply_sum'] = $now_apply_sum ;
            if(!empty($activity['apply_now_num'])){
                $remain_num = ($activity['apply_now_num'] - $now_apply_sum > 0) ? ($activity['apply_now_num'] - $now_apply_sum) : 0;
            }else if(!empty($activity['apply_limit_num'])){
                $remain_num = ($activity['apply_limit_num'] - $now_apply_sum > 0 ) ? $activity['apply_limit_num'] - $now_apply_sum : 0;
            }

            if(!empty($remain_num)){
                $house_village_activity_list[$Key]['remain_num'] = $remain_num ;
            }else{
                $house_village_activity_list[$Key]['remain_num'] = 0;
            }
        }
        $list['list'] = $house_village_activity_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }
	
	#社区首页社区活动列表3个
	public function house_village_activity_appapi_list($where , $field = true , $order = 'id desc',$pageSize = 20){
        if(!$where){
            return false;
        }
		
        $database_house_village_activity_apply = D('House_village_activity_apply');
        $house_village_activity_list = $this->where($where)->field($field)->order($order)->limit($pageSize)->select();

        foreach($house_village_activity_list as $Key=>$activity){
            $house_village_activity_list[$Key]['url'] = str_replace('shequ.php','wap.php',U('Wap/House/village_activity',array('village_id'=>$activity['village_id'],'id'=>$activity['id'])));
            $Map['activity_id'] = $activity['id'];
            $Map['is_del'] = 0;
            $Map['apply_status'] = 1;
            $now_apply_sum = $database_house_village_activity_apply->where($Map)->sum('apply_num');
            $now_apply_sum = $now_apply_sum ? $now_apply_sum : 0 ;
            $house_village_activity_list[$Key]['now_apply_sum'] = $now_apply_sum ;
            if(!empty($activity['apply_now_num'])){
                $remain_num = ($activity['apply_now_num'] - $now_apply_sum > 0) ? ($activity['apply_now_num'] - $now_apply_sum) : 0;
            }else if(!empty($activity['apply_limit_num'])){
                $remain_num = ($activity['apply_limit_num'] - $now_apply_sum > 0 ) ? $activity['apply_limit_num'] - $now_apply_sum : 0;
            }

            if(!empty($remain_num)){
                $house_village_activity_list[$Key]['remain_num'] = $remain_num ;
            }else{
                $house_village_activity_list[$Key]['remain_num'] = 0;
            }
        }
        $list['list'] = $house_village_activity_list;
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }
	
    
    public function house_village_activity_del($where){
        if(!$where){
            return false;
        }
        
        $insert_id = $this->where($where)->delete();
        if($insert_id){
            return array('status'=>1,'msg'=>'删除成功！');
        }else{
            return array('status'=>0,'msg'=>'删除失败！');
        }
    }
    
    public function house_village_activity_detail($where,$field=true){
        if(!$where){
            return false;
        }

        $detail = $this->where($where)->field($field)->find();
        if($detail){
            $database_house_village_activity_apply = D('House_village_activity_apply');
            $Map['activity_id'] = $detail['id'];
            $Map['is_del'] = 0;
            $Map['apply_status'] = 1;
            $now_activity_apply_sum = $database_house_village_activity_apply->where($Map)->sum('apply_num');
            $detail['now_activity_apply_sum'] = $now_activity_apply_sum;
            if($detail['apply_now_num']){
                if(($remain_num = $detail['apply_now_num'] - $now_activity_apply_sum) > 0){
                    $detail['remain_num'] = $remain_num;
                }
                if($now_activity_apply_sum >= $detail['apply_now_num']){
                    $detail['is_full'] = true;
                }
            }else if($detail['apply_limit_num']){
                if(($remain_num = $detail['apply_limit_num'] - $now_activity_apply_sum) > 0){
                    $detail['remain_num'] = $remain_num;
                }
                if($now_activity_apply_sum >= $detail['apply_limit_num']){
                    $detail['is_full'] = true;
                }
            }

            if($detail['pic']){
                $detail['pic'] = explode(';',$detail['pic']);
            }
        }
        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }
    
    public function house_village_activity_edit($where,$data){
        if(!$where || !$data){
            return false;
        }
        if(empty($data['title'])){
            return array('status'=>0,'msg'=>'活动标题不能为空！');
        }

        if(empty($data['pic'])){
            return array('status'=>0,'msg'=>'图片不能为空');
        }
        $data['pic'] = implode(';',$data['pic']);


        if(empty($data['content'])){
            return array('status'=>0,'msg'=>'活动内容不能为空！');
        }
        $data['content'] = htmlspecialchars_decode($data['content']);

        if($data['activity_start_time'] == $data['activity_end_time']){
            $data['activity_end_time'] = strtotime($data['activity_end_time'].' 23:59:59');
        }else{
            $data['activity_end_time'] = strtotime($data['activity_end_time']);
        }
        $data['activity_start_time'] = strtotime($data['activity_start_time']);
        
        /*if($data['apply_start_time'] == $data['apply_end_time']){
            $data['apply_end_time'] = strtotime($data['apply_end_time'].' 23:59:59');
        }else{
            $data['apply_end_time'] = strtotime($data['apply_end_time']);
        }
        $data['apply_start_time'] = strtotime($data['apply_start_time']);*/

        
        if($data['activity_start_time'] > $data['activity_end_time']){
            return array('status'=>0,'msg'=>'活动开始时间必须小于活动结束时间！');
        }
        /*if($data['apply_start_time'] > $data['apply_end_time']){
            return array('status'=>0,'msg'=>'报名开始时间必须小于报名结束时间！');
        }
        if($data['apply_end_time'] > $data['activity_start_time']){
            return array('status'=>0,'msg'=>'报名时间必须小于在活动时间！');
        }*/

        if($data['stop_apply_time']){
            $data['apply_end_time'] = strtotime($data['stop_apply_time']);
        }

        if($data['stop_apply_time'] > $data['activity_start_time']){
            return array('status'=>0,'msg'=>'报名截止时间必须小于活动开始时间！');
        }

        unset($data['apply_limit_num']);
        $data['last_time'] = time();
		
        $insert_id = $this->where($where)->data($data)->save();
		
        if($insert_id){
            return array('status'=>1,'msg'=>'修改成功！');
        }else{
            return array('status'=>0,'msg'=>'修改失败！');
        }
    }
}
?>