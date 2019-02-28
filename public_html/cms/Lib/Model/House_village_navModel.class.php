<?php
class House_village_navModel extends Model{
    public function house_village_nav_add($data){
        if(!$data){
            return false;
        }

        if(empty($data['name'])){
            return array('status'=>0,'msg'=>'名称不能为空！');
        }

        if(empty($data['url'])){
            return array('status'=>0,'msg'=>'url不能为空！');
        }

        if(empty($data['img'])){
            return array('status'=>0,'msg'=>'图片不能为空！');
        }

        if($this->data($data)->add()){
            return array('status'=>1,'msg'=>'添加成功！');
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }


    public function house_village_nav_page_list($where , $fields = true ,$order = 'id desc' , $pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');
        $house_village_nav_list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

        foreach($house_village_nav_list as $k=>$v){
            $house_village_nav_list[$k]['url'] = wapLbsTranform($v['url'],array('title'=>$v['name']));
        }

        $result['list'] = $house_village_nav_list;
        $result['pagebar'] = $p->show();

        if($house_village_nav_list){
            return array('status'=>1,'result'=>$result);
        }else{
            return array('status'=>0,'result'=>$result);
        }
    }


    public function house_village_nav_detail($where ,$fields = true){
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


    public function house_village_nav_edit($where , $data){
        if(!$where || !$data){
            return false;
        }

        $info = $this->where($where)->find();
        if(!$info){
            return array('status'=>0,'msg'=>'信息不存在！');
        }

        $insert_id = $this->where($where)->data($data)->save();
        if($insert_id){
            return array('status'=>1,'msg'=>'修改成功！');
        }else{
            return array('status'=>0,'msg'=>'修改失败！');
        }
    }


    public function house_village_nav_del($where){
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
}
?>