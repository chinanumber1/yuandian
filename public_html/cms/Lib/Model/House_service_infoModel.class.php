<?php
class House_service_infoModel extends Model{
    protected $_validate = array(
        array('title','require','标题不能为空！'),
        array('cat_id','require','分类不能为空！'),
        array('cat_fid','require','分类不能为空！'),
        array('url','require','链接不能为空！'),
    );
    
    protected $_auto = array(
        array('add_time','time',1,'function'),
        array('village_id','get_village_id',1,'callback'),
        array('url' , 'get_htmlspecialchars_decode' , 1 , 'callback')
    );
    
    protected function get_village_id(){
        return $_SESSION['house']['village_id'];
    }
    
    protected function get_htmlspecialchars_decode(){
        if($_POST['url']){
            return htmlspecialchars_decode($_POST['url']);
        }else{
            return '';
        }
    }

    public function house_service_info_add(){
        if(!$this->create()){
            return array('status'=>0,'msg'=>$this->getError());
        }else{
            if($this->add()){
                return array('status'=>1,'msg'=>'添加成功！');
            }else{
                return array('status'=>0,'msg'=>'添加失败！');
            }
        }
    }
    
    public function house_service_info_page_list($where , $field = true , $order = 'id desc',$pageSize = 20){
        if(!$where){
            return false;
        }
        
        // 查询分类
        $database_house_service_category = D('House_service_category');
        $cate_where = array('village_id'=>$where['village_id']);
        $house_service_category_info = $database_house_service_category->where($cate_where)->getField('id,cat_name');

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');
        $house_service_info_list = $this->where($where)->field($field)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        foreach($house_service_info_list as $k=>$v){
            $house_service_info_list[$k]['url'] = wapLbsTranform($v['url'],array('title'=>$v['title']));
            if($house_service_category_info[$v['cat_fid']] && $house_service_category_info[$v['cat_id']]){
                $house_service_info_list[$k]['cat_name'] = $house_service_category_info[$v['cat_fid']].'&nbsp;&nbsp;-&nbsp;&nbsp;'.$house_service_category_info[$v['cat_id']];
            }else if($house_service_category_info[$v['cat_fid']]){
                $house_service_info_list[$k]['cat_name'] = $house_service_category_info[$v['cat_fid']];
            }else{
                $house_service_info_list[$k]['cat_name'] = '<span class="red">分类不存在</span>';
            }
        }
        
        $list['list'] = $house_service_info_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }
    
    
    public function house_service_info_del($where){
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
    
    public function house_service_info_detail($where,$field=true){
        if(!$where){
            return false;
        }
        $database_house_service_category = D('House_service_category');
        $detail = $this->where($where)->field($field)->find();
        
        $where['status'] = 1;
        $where['id'] = $detail['cat_fid'];
        $cat_fname = $database_house_service_category->where($where)->getField('cat_name');
        $where['id'] = $detail['cat_id'];
        $cat_sname = $database_house_service_category->where($where)->getField('cat_name');
        
        if($cat_fname && $cat_sname){
            $detail['cat_name'] = $cat_fname . '&nbsp;&nbsp;-&nbsp;&nbsp;' . $cat_sname;
        }else if($cat_fname){
             $detail['cat_name'] = $cat_fname;
        }else{
            $detail['cat_name'] = '<span class="red">分类不存在</span>';
        }
        
        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }
    
    
    public function house_service_info_edit($where){
        if(!$this->create()){
            return array('status'=>0,'msg'=>$this->getError());
        }else{
            if($this->where($where)->save()){
                return array('status'=>1,'msg'=>'修改成功！');
            }else{
                return array('status'=>0,'msg'=>'修改失败！');
            }
        }
    }

    
    public function getHotList($village_id,$limit = 6){
        $where['village_id'] = $village_id;
        $where['status'] = 1;
        $hot_list = $this->house_service_info_page_list($where , true, 'sort desc' , $limit);
        return $hot_list['list'];
    }
}