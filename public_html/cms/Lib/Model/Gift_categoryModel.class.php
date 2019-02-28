<?php
class Gift_categoryModel extends Model{
    public function gift_category_add($data){
        if(!$data){
            return false;
        }
 
        $data['add_time'] = time();
        $insert_id = $this->data($data)->add();
        if($insert_id){
            return array('status'=>1,'msg'=>'添加成功！');
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }
    
    
    public function gift_category_page_list($where , $field = true , $order = 'cat_id desc',$pageSize = 20){
        if(!$where){
            return false;
        }
        import('@.ORG.system_page');
        $count = $this->where($where)->count();
        $page = new Page($count,$pageSize,'page');
        $list['list'] = $this->field($field)->where($where)->order($order)->limit($page->firstRow. ',' .$page->listRows)->select();
        $list['pagebar'] = $page->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }
    
    public function gift_category_del($where){
        if(!$where){
            return false;
        }
        
        $cat_id = $this->where($where)->getField('cat_id');
        if(!empty($cat_id) && ($cat_id != 0)){
            $Map['cat_fid'] = $cat_id;
            $Map['is_del'] = 0;
            $cat_count = $this->where($Map)->count();
            if($cat_count){
                return array('status'=>0,'msg'=>'请先删除底下子分类！');
            }
        }
        
        $data['del_time'] = time();
        $data['is_del'] = 1;
        $insert_id = $this->where($where)->data($data)->save();
        if($insert_id){
            return array('status'=>1,'msg'=>'删除成功！');
        }else{
            return array('status'=>0,'msg'=>'删除失败！');
        }
    }
    
    public function gift_category_detail($where,$field = true){
        if(!$where){
            return false;
        }
        
        $detail = $this->where($where)->field($field)->find();
        if(!$detail){
            return array('status'=>0,'detail'=>$detail);
        }else{
            return array('status'=>1,'detail'=>$detail);
        }
    }
    
    public function gift_category_edit($where,$data){
        if(!$where || !$data){
            return false;
        }
        
        $insert_id = $this->where($where)->data($data)->save();
        if($insert_id){
            return array('status'=>1,'msg'=>'修改成功！');
        }else{
            return array('status'=>0,'msg'=>'修改失败！');
        }
    }


    public function get_son_category($cat_fid){
        if(empty($cat_fid)){
            return false;
        }

        $where['cat_fid'] = $cat_fid;
        $where['is_del'] = 0;
        $where['cat_status'] = 1;
        $son_category_list = $this->where($where)->select();

        $database_gift = D('Gift');

        $gift_category_list = array();
        $gift_count = 0;
        foreach($son_category_list as $Key=>$Son_category){
            $Son_category_condition['cat_id'] = $Son_category['cat_id'];
            $Son_category_condition['is_del'] = 0;
            $Son_category_condition['status'] = 1;

            $Son_category['gift_num'] = $database_gift->where($Son_category_condition)->count();
            array_push($gift_category_list , $Son_category);
            $gift_count += $Son_category['gift_num'];
        }

        return array('gift_category_list'=>$gift_category_list , 'gift_count'=>$gift_count);
    }
}
?>
