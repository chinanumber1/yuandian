<?php
/**
 * Created by Goi.
 * User: Goi
 * Date: 2017年3月22日
 * Time: 11:28:07
 * Desc: 分润方法
 */
class FenrunModel extends Model
{

    /*分润钱包*/
    public function get_fenrun_score($uid=0,$today = -1)
    {
        //统计昨天的积分支出
        if($today==1){
            $yesteray   = strtotime(date("Y-m-d", $_SERVER['REQUEST_TIME']));
        }else{
            $yesteray   = strtotime(date("Y-m-d", strtotime("-1 day")));
        }
        $uid && $where['l.uid']  = $uid;
        $where['l.type']     = 1;
        $where['u.status']     = 1;
        $where['_string'] = 'l.time >=' . ($yesteray + 86400) . ' AND  l.time <' . ($yesteray + 172899); //今天获得的积分
        //$where['_string'] = ' time <' . ($yesteray + 86399);

        $score_count      = M('User_score_list')->join('AS l LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid=l.uid')->where($where)->sum('score');

        //$score_count      = array_keys($score_count);
        $uid && $condition['uid']  = $uid;
        $condition['status']=1;
        $score_all_count      = M('User')->where($condition)->sum('score_count');

        if(empty($score_count) || $score_count<0){
            $score_count  =0;
        }
        //if($uid){
        //    return sprintf("%.2f",$score_count[0]);
        //}else{
            return sprintf("%.2f",$score_all_count-$score_count);

        //}
    }

    public function get_system_take($today = -1)
    {
        //统计昨天的积分支出
        if($today){
            $yesteray   = strtotime(date("Y-m-d", $_SERVER['REQUEST_TIME']));
        }else{
            $yesteray   = strtotime(date("Y-m-d", strtotime("-1 day")));
        }
        $where['_string'] = 'use_time >=' . $yesteray . ' AND  use_time <' . ($yesteray + 86399);
        $money  = M('Merchant_money_list')->where($where)->sum('system_take');
        if(empty($money)){
            $money  =0;
        }
        return $money;
    }

    public function get_fenrun_user()
    {
        //筛选昨天有积分支出的用户
        $yesterday        = strtotime(date("Y-m-d", strtotime("-1 day")));
        $today            = strtotime(date("Y-m-d"));
        $where['status'] = 1;
        $where['score_count'] = array('gt',0);
        $where['_string'] = 'fenrun_time=0 OR fenrun_time<' . $today;
        //$where['_string'] = ' us.time <' . ($yesterday + 86399) . ' AND u.fenrun_time<' . $today;
        //$where['_string'] = 'us.time >=' . $yesterday . ' AND  us.time <' . ($yesterday + 86399) . ' AND u.fenrun_time<' . $today;
        //$user_list        = M('User_score_list')->join('AS us INNER JOIN ' . C('DB_PREFIX') . 'user u ON u.uid = us.uid')->field('us.uid')->where($where)->group('us.uid')->select();
        $user_list        = M('User')->field('uid')->where($where)->select();
        return $user_list;
    }

    public function fenrun($uid)
    {
        $User_model = D('User');
        $now_user   = $User_model->get_user($uid);

        //判断是否已经分润了
        if ($now_user['status'] && ($now_user['fenrun_time'] == 0 || (strtotime(date("Y-m-d", $now_user['fenrun_time'])) != strtotime(date("Y-m-d"))))) {
            $percent      = C('config.score_fenrun_percent');
            $fenrun_score = $this->get_fenrun_score($uid);

            if ($fenrun_score <= 0) {
                return;
            }
            $fenrun_money = sprintf("%.2f",$fenrun_score * $percent / 100);
            if ($fenrun_money <= 0) {
                return;
            }
            $des = "用户{$fenrun_money}积分转入分润钱包，转换金额{$fenrun_money}元剩余".sprintf("%.2f",($now_user['score_count']-$fenrun_money));
            $User_model->user_score($uid, $fenrun_money, $des,3); //定制需要减去分润金额
            $this->add_fenrun_money($uid, $fenrun_money, $fenrun_score, $percent, $des);
        }
    }

    public function add_fenrun_money($uid, $money, $score, $percent, $des)
    {
        $User_model                = D('User');
        $now_user                  = $User_model->get_user($uid);
        $date['uid']               = $uid;
        $date['score_count']       = $score;
        $date['fenrun_money']      = $money;
        $date['percent']           = $percent;
        $date['now_score_count']   = $now_user['score_count'] - $money > 0 ? $now_user['score_count'] - $money : 0;
        $date['now_fenrun_money']  = $money + $now_user['fenrun_money'];
        $date['des']               = $des;
        $date['add_time']          = time();
        $date['type']          = 1;
        $data_user['fenrun_time']  = time();
        $data_user['fenrun_money'] = $now_user['fenrun_money'] + $money;
        $User_model->where(array('uid' => $uid))->save($data_user);
        M('User_fenrun_list')->add($date);
    }

    public function fenrun_recharge($uid, $fenrun_moeny)
    {
        $User_model              = D('User');
        $fenrun_recharge_percent = C('config.fenrun_recharge_precent');
        $fenrun_recharge_fee     = C('config.fenrun_recharge_fee');
        $result                  = $User_model->where(array('uid' => $uid))->setDec('fenrun_money', $fenrun_moeny);
        $money                   = sprintf("%.2f", $fenrun_moeny * $fenrun_recharge_percent / 100);
        $fenrun_fee              = sprintf("%.2f", $money * $fenrun_recharge_fee / 100);
        $fenrun_fee_txt          = '';
        if ($fenrun_fee > 0) {
            $fenrun_fee_txt = ',手续费：' . $fenrun_fee;
        }
        if ($User_model->add_money($uid, $money - $fenrun_fee, '用户分润钱包金额转余额，转换比例:' . $fenrun_recharge_percent . $fenrun_fee_txt)) {
            return array('error_code' => false, 'msg' => '分润钱包金额转余额成功');
        } else {
            return array('error_code' => true, 'msg' => '分润钱包金额转余额失败，请联系管理员');
        }
    }

    /*解冻根据子用户消费奖励金额*/
    public function free_user_recommend_awards($uid, $free_money,$type,$type_id)
    {
        $User_model = D('User');
        $now_user   = $User_model->get_user($uid);
        $spread_recharge_limit = $now_user['frozen_award_money'];
        $recharge_money = $free_money > $spread_recharge_limit ? $spread_recharge_limit : $free_money;
        $limit_txt     = ',您的还有' . ($spread_recharge_limit - $recharge_money<0?0:$spread_recharge_limit - $recharge_money) . '元未解冻';
        //$this->add_free_award_money($uid, $rechage_money, '解冻奖励金额' . $rechage_money . '元' . $limit_txt,$type,$type_id);
        $User_model = D('User');
        $now_user   = $User_model->get_user($uid);
        $date_user['free_award_money'] = $now_user['free_award_money'] + $recharge_money;
        $date_user['frozen_award_money'] = $now_user['frozen_award_money'] - $recharge_money;
        $date['uid'] = $uid;
        //解冻金额
        $date['money'] = $recharge_money;
        //当前冻结金额
        $date['now_frozen_money'] = $recharge_money;
        $date['now_free_money']   = $now_user['free_award_money'] + $recharge_money;
        $date['income']   = 1;//收入增加
        $date['type']   = $type;
        $date['type_id']   = $type_id;
        $date['des'] = '解冻奖励佣金' . $recharge_money . '元' . $limit_txt;
        $date['add_time'] = $_SERVER['REQUEST_TIME'];
        //用户解冻金额增加，冻结进额减少
        $User_model->where(array('uid'=>$uid))->save($date_user);
        //增加记录
        M('Fenrun_free_award_money_list')->where(array('uid' => $uid))->add($date);
    }

    //解冻金额增加
    public function add_free_award_money($uid, $money, $des,$type,$type_id)
    {
        $User_model = D('User');
        $now_user   = $User_model->get_user($uid);
        $date_user['free_award_money'] = $now_user['free_award_money'] + $money;
        $date_user['frozen_award_money'] = $now_user['frozen_award_money'] - $money;
        $date['uid'] = $uid;
        //解冻金额
        $date['money'] = $money;
        //当前冻结金额
        $date['now_frozen_money'] = $money;
        $date['now_free_money']   = $now_user['free_award_money'] + $money;
        $date['income']   = 1;//收入增加
        $date['type']   = $type;
        $date['des'] = $des;
        $date['add_time'] = $_SERVER['REQUEST_TIME'];
        //用户解冻金额增加，冻结进额减少
        $User_model->where(array('uid'=>$uid))->save($date_user);
        //增加记录
        M('Fenrun_free_award_money_list')->where(array('uid' => $uid))->add($date);
    }

    //奖励金额增加
    public function add_recommend_award($uid,$type_id,$type ,$money,$des){
        $User_model = D('User');
        $User_model->where(array('uid'=>$uid))->setInc('frozen_award_money',$money);
        $date['uid'] = $uid;
        $date['type_id'] = $type_id;
        $date['type'] = $type;
        $date['income'] = 1;
        $date['money'] = $money;
        $date['des'] = $des;
        $date['add_time'] = $_SERVER['REQUEST_TIME'];
        M('Fenrun_recommend_award_list')->add($date);
    }


    //奖励明细
    public function recommend_awards_list($uid,$type= 1)
    {
        $where['uid'] = $uid;
        $where['type'] = $type;
//        $_GET['page'] = isset($_POST['page'])?$_POST['page']:$_GET['page'];
//        import('@.ORG.user_page');
//        $count =  M('Fenrun_recommend_award_list')->where($where)->count();
//        $p = new Page($count,10);
        $return= M('Fenrun_recommend_award_list')->where($where)->order('id DESC')->select();

//        if($_GET['page'] >  $p->totalPage &&  $p->totalPage>0){
//            $return['list'] = array();
//        }
        //$return['pagebar'] = $p->show();
        return $return;
    }

    //奖励解冻明细
    public function free_recommend_awards_list($uid,$mer_id)
    {
        $where['uid'] = $uid;
        if($mer_id){
            unset($where['uid']);
            $where['mer_id'] = $mer_id;
        }
        $_GET['page'] = isset($_POST['page'])?$_POST['page']:$_GET['page'];
        import('@.ORG.user_page');
        $count =  M('Fenrun_free_award_money_list')->where($where)->count();
        $p = new Page($count,10);
        $return['list']  = M('Fenrun_free_award_money_list')->where($where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();

        if($_GET['page'] >  $p->totalPage &&  $p->totalPage>0){
            $return['list'] = array();
        }
        $return['pagebar'] = $p->show();
        return $return;
    }

    //分润列表
    public  function fenrun_list($uid){
        $where['uid'] = $uid;
        $_GET['page'] = isset($_POST['page'])?$_POST['page']:$_GET['page'];
        import('@.ORG.user_page');
        $count =  M('User_fenrun_list')->where($where)->count();
        $p = new Page($count,10);
        $return['list']  = M('User_fenrun_list')->where($where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
        if($_GET['page'] >  $p->totalPage &&  $p->totalPage>0){
            $return['list'] = array();
        }
        $return['pagebar'] = $p->show();

        return $return;
    }

    public function get_free_total($uid,$type){
        $where['uid'] = $uid;
        $where['type'] = $type;
        $where['income'] = 1;
        $total_money = M('Fenrun_free_award_money_list')->where($where)->sum('money');
        return $total_money;
    }

    public function get_award_total($uid,$type){
        $where['uid'] = $uid;
        $where['type'] = $type;
        $where['income'] = 1;
        $total_money = M('Fenrun_recommend_award_list')->where($where)->sum('money');
        return $total_money;
    }

    /*分润转余额*/
    public function fenrun_to_balance($param){

        $User_model       = D('User');
        $where['uid']     = $param['uid'];
        $now_user         = $User_model->get_user($param['uid']);
        $date['uid']      = $param['uid'];
        $date['des']      = $param['des'];
        $date['fenrun_money']    = $param['fenrun_money'];
        $date['add_time'] = time();
        $date['type']     = 2;
        $date['now_free_money'] = $date_user['fenrun_money']=  $now_user['fenrun_money']-$param['fenrun_money'];
        $date['now_frozen_money']  =$now_user['frozen_award_money'];

        if( $now_user['fenrun_money']>=$param['money'] && M('User_fenrun_list')->add($date) && $User_model->where($where)->save($date_user)){
            $resutl = $User_model->add_money($param['uid'],$param['money'],$param['des']);
            return $resutl;
        }else{
            return array('error_code' => true, 'msg' => '用户分润转余额失败！请联系管理员协助解决。');
        }
    }

    /*奖励转余额*/
    public function award_to_balance($param){
        $User_model = D('User');
        $where['uid'] = $param['uid'];
        $now_user   = $User_model->get_user($param['uid']);
        $date['uid'] = $param['uid'];
        $date['des'] = $param['des'];
        $date['money'] = $param['money'];
        $date['income'] = 2;
        $date['type_id'] = 0;
        $date['add_time'] = time();
        $date['type'] = 0;
        $date['now_free_money'] = $date_user['free_award_money']=  $now_user['free_award_money']-$param['money'];
        $date['now_frozen_money'] =$now_user['frozen_award_money'];

        if( $now_user['free_award_money']>=$param['money'] && M('Fenrun_free_award_money_list')->add($date) && $User_model->where($where)->save($date_user)){
            $resutl = $User_model->add_money($param['uid'],$param['money'],$param['des']);
            return $resutl;
        }else{
            return array('error_code' => true, 'msg' => '用户分佣转余额失败！请联系管理员协助解决。');
        }
    }

    public function save_today_fenrun_income_date($type=1){
        $today             = strtotime(date("Y-m-d", $_SERVER['REQUEST_TIME']));
        $yesteray             = strtotime(date("Y-m-d", strtotime('-1 day')));
        if(M('System_fenrun_list')->where(array('record_time'=>$yesteray))->find()) {
            return;
        }
        $where['l.type']        = 3;
        $where['u.status']        = 1;
        $where['_string']     = 'l.time >=' . $today . ' AND  l.time <' . ($today + 86399);
        //$where['_string']     = ' time <' . ($yesteray + 86399);
        $score_tatal         = M('User_score_list')->join('AS l LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid=l.uid')->where($where)->sum('l.score');
        //$score_tatal = 0;
        //foreach ($score_list as $v) {
        //    $v['score']>0 && $score_tatal+=$v['score'];
        //}
        $score_all_count      = M('User')->where(array('status'=>1))->sum('score_count');

        $date['score_count']  =$score_all_count+ $score_tatal;
        $date['fenrun_money']  =sprintf("%.2f", $date['score_count'] *  C('config.score_fenrun_percent') / 100);
        $condition['_string'] = 'use_time >=' . $yesteray . ' AND  use_time <' . ($yesteray + 86399);
        $date['income']       = M('Merchant_money_list')->where($condition)->sum('system_take');
        $date['income']       = $date['income']>0?$date['income']:0;
        $date['record_time']  = $yesteray;
        $date['add_time']     = time();
        $date['type']     = $type;
        return M('System_fenrun_list')->add($date);
    }

}

?>