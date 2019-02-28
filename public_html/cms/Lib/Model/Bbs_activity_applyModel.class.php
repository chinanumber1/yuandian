<?php
class Bbs_activity_applyModel extends Model{
    public function bbs_activity_apply_page_list($where , $fields = true , $order = 'id desc' , $pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        $uidArr = array();
        foreach($list as $row){
            $uidArr[] = $row['uid'];
        }

        if($uidArr){
            $user_where['uid'] = array('in',$uidArr);
            $database_user = D('User');
            $user_list = $database_user->where($user_where)->select();
            foreach($user_list as $key=>$user){
                $user_list[$user['uid']] = $user;
                unset($user_list[$key]);
            }
        }else{
            $user_list = array();
        }

        $result['list'] = $list;
        $result['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'result'=>$result,'user_list'=>$user_list);
        }else{
            return array('status'=>0,'result'=>$result);
        }
    }
}
?>