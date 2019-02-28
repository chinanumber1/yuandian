<?php
class Appoint_newsModel extends Model{
    public function appoint_news_add($data){
        if(!$data){
            return false;
        }
        
        if(!$data['desc']){
            return array('status'=>0,'msg'=>'描述不能为空！');
        }
        
        $data['content'] = htmlspecialchars_decode($data['content']);
        
        if(!$data['content']){
            return array('status'=>0,'msg'=>'内容不能为空！');
        }
        
        $data['publish_time'] =  strtotime($data['publish_time']);
        $data['last_time'] = time();
        $data['add_time'] = time();
        $insert_id = $this->data($data)->add();
        
        if($insert_id){
            return array('status'=>1,'msg'=>'添加成功！');
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }
    
    public function appoint_news_page_list($where,$field = true,$order = 'sort desc,id desc',$pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.system_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $appoint_news_list = $this->where($where)->field($field)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        
        foreach($appoint_news_list as $k=>$v){
            $appoint_news_list[$k]['content'] = htmlspecialchars_decode($v['content']);
        }
        
        $list['list'] = $appoint_news_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }
    
    public function appoint_news_del($where){
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
    
    
    public function appoint_news_detail($where,$field = true){
        if(!$where){
            return false;
        }
        
        $detail = $this->where($where)->field($field)->find();
        $detail['add_time'] = date('Y-m-d H:i:s',$detail['add_time']);
        $detail['content'] = htmlspecialchars_decode($detail['content']);
        if(!$detail){
            return array('status'=>0,'detail'=>$detail);
        }else{
            return array('status'=>1,'detail'=>$detail);
        }
    }
    
    public function appoint_news_edit($where,$data){
        if(!$where || !$data){
            return false;
        }
        if(!$data['desc']){
            return array('status'=>0,'msg'=>'描述不能为空！');
        }
        
        $data['content'] = htmlspecialchars_decode($data['content']);
        if(!$data['content']){
            return array('status'=>0,'msg'=>'内容不能为空！');
        }
        
        $data['publish_time'] =  strtotime($data['publish_time']);
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