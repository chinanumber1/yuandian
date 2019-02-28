<?php
class House_village_floor_typeModel extends Model{
    public function house_village_floor_type_add($data){
        if(empty($data['name'])){
            return array('status' => 0, 'msg'=>'类型名称不能为空！');
        }

        $data['add_time'] = time();
        $data['village_id'] = $_SESSION['house']['village_id'];

        $insert_id = $this->data($data)->add();
        if($insert_id){
            return array('status' => 1 , 'msg' => '添加成功！');
        }else{
            return array('status'=> 0 , 'msg' => '添加失败！');
        }
    }

    public function house_village_floor_type_page_list($where , $fields = true , $order = 'id desc' , $pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $house_village_floor_type_list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        $list['list'] = $house_village_floor_type_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }

    public function house_village_floor_type_detail($where , $fields = true){
        if(!$where){
            return false;
        }

        $detail = $this->where($where)->field($fields)->find();

        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }

    public function house_village_floor_type_edit($where , $data){
        if(!$where || !$data){
            return false;
        }

        if(empty($data['name'])){
            return array('status'=>0,'msg'=>'类型名称不能为空！');
        }

        $insert_id = $this->where($where)->data($data)->save();
        if($insert_id){
            return array('status' => 1 , 'msg' => '修改成功！');
        }else{
            return array('status' => 0 , 'msg' => '修改失败！');
        }
    }

    public function village_floor_type_delete($where){
        if(!$where){
            return false;
        }

        $detail = $this->house_village_floor_type_detail($where);
        $detail = $detail['detail'];

        $database_house_village_floor = D('House_village_floor');
        $floor_condition['floor_type'] = $detail['id'];
        $floor_sum = $database_house_village_floor->where($floor_condition)->count();
        if($floor_sum > 0){
            return array('status'=>0,'msg'=>'请先删除或修改相应类型单元。');
        }

        $insert_id = $this->where($where)->delete();
        if($insert_id){
            return array('status'=>1,'msg'=>'删除成功！');
        }else{
            return array('status'=>0,'msg'=>'删除失败！');
        }
    }
}

?>