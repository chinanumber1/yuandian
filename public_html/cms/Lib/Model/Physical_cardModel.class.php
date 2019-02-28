<?php
//实体卡
class Physical_cardModel extends Model{
    public $status = array(0=>'禁止',1=>'正常',2=>'未审核');
    //增加记录
    public function add_log($param){
        $param['add_time'] = time();
        return M('Physical_card_log')->add($param);
    }

    /*
     * 绑定用户
     * */
    public function bind_user($card,$user,$des){
        $last_time = time();
        $this->where(array('cardid'=>$card['cardid']))->save(array('uid'=>$user['uid'],'regtime'=>$last_time,'last_time'=>$last_time,'status'=>1,'is_bind'=>1));
        D('User')->where(array('uid'=>$user['uid']))->setField('cardid',$card['cardid']);
        D('User')->add_money($user['uid'],$card['balance_money'],$des);
        return true;
    }

    /*
     * 根据mer_id 查询实体卡
    */
    public  function get_cardid_by_mer_id($mer_id,$is_system=false){
        if($is_system){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }

        $count = $this->where(array('merid'=>$mer_id))->count();
        $p = new Page($count, 20);
        $card_list = $this->field('p.*,u.nickname,u.phone,s.name as staff_name')
            ->join('as p left join '.C('DB_PREFIX').'user u ON p.uid = u.uid left join '.C('DB_PREFIX').'merchant_store_staff s ON p.staff_id =s.id ')
            ->where(array('merid'=>$mer_id))
            ->limit($p->firstRow,$p->listRows)
            ->order('p.regtime DESC,p.last_time DESC,p.id DESC')
            ->select();
        $pagebar=$p->show();
        return array('card_list'=>$card_list,'pagebar'=>$pagebar);
    }

    /*
     * 检测实体卡 是否绑定商家，绑定用户,
     * @return 返回是否能绑定新用户
     * */
    public function check_card($cardid,$phone,$merid){
        $where['cardid'] = $cardid;
        $card_info = $this->where($where)->find();
        $now_user = D('User')->get_user($phone,'phone');
        if(empty($now_user)){
            return array('error_code'=>1,'msg'=>'没有该用户');
        }
        if($now_user['cardid']){
            return array('error_code'=>1,'msg'=>'该用户已经绑定了实体卡，不能再绑定了');
        }
        if($card_info){
            if($card_info['merid']!=$merid&&$card_info['merid']!=0){
                return array('error_code'=>1,'msg'=>'该卡已经绑定了给了其他商家');
            }
            if($card_info['uid']!=0){
                return array('error_code'=>1,'msg'=>'该卡已经绑定了给了其他用户');
            }
            return array('error_code'=>0,'msg'=>'可以绑定','card_info'=>$card_info,'user'=>$now_user);
        }else{
            return array('error_code'=>1,'msg'=>'没有查询到该卡');
        }

    }

    public function card_log($mer_id=0,$staff_id = 0,$is_system=false){
        if($is_system){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }
        if($mer_id){
            $where['mer_id'] =$mer_id;
        }else if($staff_id){
            $where['staff_id'] = $staff_id;
        }else{
            $where = '22=22';
        }

        $count = M('Physical_card_log')->where($where)->count();
        $p = new Page($count, 20);
        if($is_system) {
            $log_list = M('Physical_card_log')->field('p.*,a.realname as admin_name,m.name as mer_name,s.name as staff_name')
                ->join('as p left join ' . C('DB_PREFIX') . 'admin a ON p.system_id = a.id left join ' . C('DB_PREFIX') . 'merchant_store_staff s ON p.staff_id =s.id left join ' . C('DB_PREFIX') . 'merchant m ON p.mer_id =m.mer_id')
                ->limit($p->firstRow, $p->listRows)
                ->order('p.add_time DESC')
                ->select();
        }else{
            if($mer_id){
                $where['p.mer_id'] = $mer_id;
                unset($where['mer_id']);
            }else if($staff_id){
                $where['p.staff_id'] = $staff_id;
                unset($where['staff_id']);
            }
            $log_list = M('Physical_card_log')->field('p.*,s.name as staff_name')
                ->join('as p left join ' . C('DB_PREFIX') . 'merchant_store_staff s ON p.staff_id =s.id ')
                ->where($where)
                ->limit($p->firstRow, $p->listRows)
                ->order('p.add_time DESC')
                ->select();
        }

        $pagebar=$p->show();
        return array('log_list'=>$log_list,'pagebar'=>$pagebar);
    }
}

