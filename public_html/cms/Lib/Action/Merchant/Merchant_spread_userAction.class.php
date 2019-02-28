<?php
// 商家推广用户
class Merchant_spread_userAction extends BaseAction{
    public function index(){
        import('@.ORG.merchant_page');
        $where['mer_id'] = $this->merchant_session['mer_id'];
        $reg_type = 0;
        if($_GET['keyword']){
            $wheres['u.nickname'] = array('like','%'.$_GET['keyword'].'%');
            $wheres['u.phone'] = array('like','%'.$_GET['keyword'].'%');
            $wheres['_logic'] = 'or';
            $where['_complex'] =$wheres;
            unset($_GET['reg']);
            $reg_type = 1;
        }else if(isset($_GET['reg']) && !isset($_GET['keyword'])){
            switch($_GET['reg']){
                case 0:
                    $where['u.uid'] =array('EXP','IS NULL');
                    break;
                case 1:
                    $where['u.uid'] =array('gt',0);
                    $reg_type = 1;
                    break;
                case -1:
                    $reg_type = 0;
                    break;
            }

        }
        if($_GET['reg']==1&&$_GET['keyword']!=''){
            $where['u.uid'] =array('gt',0);
            $reg_type = 1;
        }
        if($this->config['merchant_replace_money']>0){
            $count = M('Merchant_spread_list')->where(array('mer_id'=>$where['mer_id'] ))->count();
            $p = new Page($count, 20);
            $spread_user = M('Merchant_spread_list')->where(array('mer_id'=>$where['mer_id'] ))->limit($p->firstRow . ',' . $p->listRows)->select();
            $pagebar = $p->show();

       
        }else{
            $count = M('Merchant_spread')->join('as s left join '.C('DB_PREFIX').'user u ON s.openid = u.openid ')->where($where)->count();
            $p = new Page($count, 20);
            $spread_user = M('Merchant_spread')->join('as s left join '.C('DB_PREFIX').'user u ON s.openid = u.openid ')
                ->join('(SELECT openid,SUM(money) AS spread_money FROM '.C('DB_PREFIX').'merchant_spread_list where mer_id ='.$where['mer_id'].' GROUP BY openid) AS m ON m.openid = s.openid ')
                ->where($where)->group('s.openid')->limit($p->firstRow . ',' . $p->listRows)->select();
            $pagebar = $p->show();
        }


        $this->assign('pagebar', $pagebar);
        $all_spread_money = M('Merchant_spread_list')->where($where)->sum('money');
        $this->assign('all_spread_money',$all_spread_money?$all_spread_money:0);
        $this->assign('reg_type',$reg_type);
        $this->assign('spread_user',$spread_user);
        $this->display();
    }

    //推广佣金记录
    public function spread_list(){
        $where['mer_id'] = $this->merchant_session['mer_id'];
        import('@.ORG.merchant_page');
        $count = M('Merchant_spread_list')->where($where)->count();
        $p = new Page($count, 20);
        $spread_list = M('Merchant_spread_list')->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('spread_list',$spread_list);
        $this->display();
    }

    //店铺推广记录
    public function store_spread(){
        import('@.ORG.merchant_page');
        $where['mer_id'] = $this->merchant_session['mer_id'];
        $store_list = D('Merchant_store')->where($where)->select();
        $this->assign('store_list',$store_list);
        //if($_GET['keyword']){
        //    $where['u.nickname'] = array('like','%'.$_GET['keyword'].'%');
        //}
        if($_GET['store_id']){
            $where['store_id'] = $_GET['store_id'];
        }

        if($_GET['keyword']){
            $wheres['u.nickname'] = array('like','%'.$_GET['keyword'].'%');
            $wheres['u.phone'] = array('like','%'.$_GET['keyword'].'%');
            $wheres['_logic'] = 'or';
            $where['_complex'] =$wheres;
            unset($_GET['reg']);
            $reg_type = 1;
        }else if(isset($_GET['reg']) && !isset($_GET['keyword'])){
            switch($_GET['reg']){
                case 0:
                    $where['u.uid'] =array('EXP','IS NULL');
                    break;
                case 1:
                    $where['u.uid'] =array('gt',0);
                    $reg_type = 1;
                    break;
                case -1:
                    $reg_type = 0;
                    break;
            }

        }
        if($_GET['reg']==1&&$_GET['keyword']!=''){
            $where['u.uid'] =array('gt',0);
            $reg_type = 1;
        }
        $count = M('Merchant_spread')->join('as s left join '.C('DB_PREFIX').'user u ON s.openid = u.openid ')->where($where)->count();
        $p = new Page($count, 20);
        $spread_user = M('Merchant_spread')->join('as s left join '.C('DB_PREFIX').'user u ON s.openid = u.openid ')
            ->join('(SELECT openid,SUM(money) AS spread_money FROM '.C('DB_PREFIX').'merchant_spread_list where mer_id ='.$where['mer_id'].' GROUP BY openid) AS m ON m.openid = s.openid ')
            ->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();

        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $all_spread_money = M('Merchant_spread_list')->where($where)->sum('money');
        $this->assign('all_spread_money',$all_spread_money?$all_spread_money:0);
        $this->assign('spread_user',$spread_user);
        $this->display();
    }

    public function store_spread_list(){
        $where['l.mer_id'] = $this->merchant_session['mer_id'];
        $store_list = D('Merchant_store l')->where($where)->select();
        $this->assign('store_list',$store_list);
        if($_GET['store_id']){
            $where['s.store_id'] = $_GET['store_id'];
        }else{
            $where['s.store_id'] = array('neq','');
        }
        import('@.ORG.merchant_page');
        $count = M('Merchant_spread_list l')->join('as l left join '.C('DB_PREFIX').'merchant_spread s ON s.openid = l.openid')->where($where)->count();
        $p = new Page($count, 20);
        $spread_list = M('Merchant_spread_list l')->join(C('DB_PREFIX').'merchant_spread s ON s.openid = l.openid')->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('spread_list',$spread_list);
        $this->display();
    }
}