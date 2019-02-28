<?php
class House_village_propertyModel extends Model{
    public function house_village_property_add($data){
        if(!$data){
            return false;
        }

        if(!$data['property_month_num']){
            $data['property_month_num'] = $data['diy_property_month_num'];
            unset($data['diy_property_month_num']);
        }


        if($data['property_month_num'] <= 0){
            return array('status'=>0,'msg'=>'物业缴费周期不合法！');
        }

        if(!$data['presented_property_month_num']){
            $data['presented_property_month_num'] = $data['diy_presented_property_month_num'];
            unset($data['diy_presented_property_month_num']);
        }

        if($data['diy_type'] == 0){
            if(($data['property_month_num'] > 36) || ($data['property_month_num'] < 1)){
                return array('status'=>0,'msg'=>'请填写1-36之间的值！');
            }
            unset($data['diy_content']);
        }else{
            unset($data['presented_property_month_num'] , $data['diy_presented_property_month_num']);
        }
        $info = $this->where(array('property_month_num'=>$data['property_month_num'],'village_id'=>$_SESSION['house']['village_id']))->find();
        if(!empty($info)){
            return array('status'=>0,'msg'=>'该月份已存在！');
        }
        $data['add_time'] = time();

        $inser_id = $this->data($data)->add();
        if($inser_id){
            return array('status'=>1,'msg'=>'添加成功！');
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }

    public function house_village_proerty_page_list($where , $fields = true , $order = 'id desc',$pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count , $pageSize , 'page');

        $house_village_proerty_list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

        $list['list'] = $house_village_proerty_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }

    public function house_village_property_detail($where,$fields =true){
        if(!$where){
            return false;
        }

        $detail = $this->where($where)->field($fields)->find();
        if(!$detail){
            return array('status'=>0,'detail'=>$detail);
        }else{
            return array('status'=>1,'detail'=>$detail);
        }
    }

    public function house_village_property_del($where){
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

    public function house_village_property_edit($where , $data){
        if(!$where || !$data){
            return false;
        }

        if(!$data['property_month_num']){
            $data['property_month_num'] = $data['diy_property_month_num'];
            unset($data['diy_property_month_num']);
        }

        if($data['property_month_num'] <= 0){
            return array('status' => 0,'msg' => '物业缴费周期不合法！');
        }

        if($data['diy_type'] == 0){
            if(!$data['presented_property_month_num']){
                $data['presented_property_month_num'] = $data['diy_presented_property_month_num'];
                unset($data['diy_presented_property_month_num']);
            }

            if(($data['property_month_num'] > 36) || ($data['property_month_num'] < 1)){
                return array('status' => 0,'msg'=>'请填写1-36之间的值！');
            }
            unset($data['diy_content']);
        }else{
            $data['presented_property_month_num'] = 0;
        }

        $insert_id = $this->where($where)->data($data)->save();

        if($insert_id){
            return array('status'=>1,'msg'=>'修改成功！');
        }else{
            return array('status'=>0,'msg'=>'修改失败！');
        }
    }
}
?>