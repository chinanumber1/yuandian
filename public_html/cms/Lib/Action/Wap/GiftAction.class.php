<?php
class GiftAction extends BaseAction{
    public function index(){
        $database_adver = D('Adver');
        $database_gift_category = D('Gift_category');

        //导航start
        $gift_adver_list = $database_adver->get_adver_by_key('wap_gift_slider', 3);
        //导航end

        if( $this->user_session['uid'] && $this->config['open_rand_send']){
             $coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$this->user_session['uid']));
            $coupon_html && $this->assign('coupon_html',$coupon_html);
        }

        //用户信息start
        if($this->user_session){
            $database_user = D('User');
            $now_user = $database_user->get_user($this->user_session['uid']);
            $this->assign('now_user',$now_user);
        }
        //用户信息end

        //分类列表start
        $gift_cat_condition['is_del'] = 0;
        //$gift_cat_condition['status'] = 1;
        $gift_cat_condition['cat_fid'] = 0;
        $gift_cat_condition['cat_status'] = 1 ;
        $gift_category_list = $database_gift_category->gift_category_page_list($gift_cat_condition , true , 'cat_sort desc' , 999);
        //分类列表end


        $this->assign('gift_adver_list' , $gift_adver_list);
        $this->assign('gift_category_list' , $gift_category_list['list']);
        $this->display();
    }

    public function gift_list()
    {
        $cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
        $category = M('Gift_category')->field(true)->where(array('is_del' => 0, 'status' => 1, 'cat_id' => $cat_id))->find();
        $this->assign('gift_category_detail', $category);
        $this->display();
    }

    public function ajax_gift()
    {
        $cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
        $order = isset($_POST['order']) ? trim($_POST['order']) : '';
        
        switch ($order) {
            case 'integral_desc':
                $order = 'exchange_type asc, payment_pure_integral desc';
                break;
            case 'integral_asc':
                $order = 'exchange_type asc, payment_pure_integral asc';
                break;
            default:
                $order = 'sort desc';
                break;
        }
        
        $where = array('is_del' => 0, 'status' => 1);
        if (!empty($cat_id)) {
            $where['cat_fid'] = $cat_id;
        }
        $return = D('Gift')->gift_page_list($where, true, $order, 10);
        exit(json_encode($return));
    }
    
    public function gift_detail(){
        $gift_id = $_GET['gift_id'] + 0;
        if(empty($gift_id)){
            $this->error_tips('传递参数有误！');
        }

        $database_gift = D('Gift');
        $gift_condition['is_del'] = 0;
        $gift_condition['status'] = 1;
        $gift_condition['gift_id'] = $gift_id;
        $gift_detail = $database_gift->gift_detail($gift_condition);

        $gift_detail = $gift_detail['detail'];
        $gift_detail['specification'] = explode("\r\n" , $gift_detail['specification']);
        $this->assign('gift_detail' , $gift_detail);
        $this->display();
    }

    public function fast_gift(){
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！', U('Login/index'));
        }

        $order = $_GET['order'];
        $database_user = D('User');
        $database_gift = D('Gift');
        $now_user = $database_user->get_user($this->user_session['uid']);
        $this->assign('now_user' , $now_user);

        $gift_condition['is_del'] = 0;
        $gift_condition['status'] = 1;
        //$gift_condition['exchange_type'] = 0;
        $gift_condition['payment_pure_integral'] = array('lt',$now_user['score_count']);
        if(!empty($order)){
            switch($order){
                case 'integral_desc':
                    $order = 'exchange_type asc,payment_pure_integral desc';
                    break;
                case 'integral_asc':
                    $order = 'exchange_type asc,payment_pure_integral asc';
                    break;
                default:
                    $order = 'sort desc';
                    break;
            }
        }



        $fast_gift_list = $database_gift->gift_page_list($gift_condition , true , $order , 9999);
        $this->assign('fast_gift_list' , $fast_gift_list['list']);
        $this->display();
    }

    public function order(){
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！', U('Login/index'));
        }

        $gift_id = $_GET['gift_id'] + 0;
        $adress_id = $_GET['adress_id'] + 0;

        $database_gift = D('Gift');
        $database_user_adress = D('User_adress');
        $now_user_adress = $database_user_adress->get_one_adress($this->user_session['uid'] , $adress_id);

        $gift_condition['is_del'] = 0;
        $gift_condition['status'] = 1;
        $gift_condition['gift_id'] = $gift_id;

        $gift_detail = $database_gift->gift_detail($gift_condition);

        $database_user = D('User');
        $now_user = $database_user->get_user($this->user_session['uid']);

        $gift_detail = $gift_detail['detail'];
        if($gift_detail['exchange_type'] == 0){
            if($now_user['score_count'] < $gift_detail['payment_pure_integral']){
                $this->error_tips(''.$this->config['score_name'].'不足，暂时无法兑换！');
            }
        }elseif($gift_detail['exchange_type'] == 1){
            if($now_user['score_count'] < $gift_detail['payment_integral']){
                $this->error_tips(''.$this->config['score_name'].''.$this->config['score_name'].'不足，暂时无法兑换！');
            }
        }

        if(!$gift_detail['status']){
            $this->error_tips('该礼品不存在！');
        }

        $this->assign('gift_detail',$gift_detail);
        $this->assign('now_user_adress' , $now_user_adress);
        $this->display();
    }

    public function buy(){
        if(IS_POST){
            if(empty($this->user_session)){
                $this->assign('jumpUrl',U('Index/Login/index'));
                $this->error('请先登录！');
            }

            $database_gift = D('Gift');
            $database_gift_order = D('Gift_order');
            $gift_condition['is_del'] = 0;
            $gift_condition['status'] = 1;

            $gift_id = $_POST['gift_id'] + 0;
            $gift_condition['gift_id'] = $gift_id;
            $gift_detail = $database_gift->gift_detail($gift_condition);
            $gift_detail = $gift_detail['detail'];

            if(empty($gift_detail['status'])){
                $this->error_tips('该礼品不存在！');
            }

            $database_user = D('User');
            $now_user = $database_user->get_user($this->user_session['uid']);

            if($gift_detail['exchange_type'] == 0){
                if($now_user['score_count'] < $gift_detail['payment_pure_integral']){
                    $this->error_tips(''.$this->config['score_name'].'不足，暂时无法兑换！');
                }
            }elseif($gift_detail['exchange_type'] == 1){
                if($now_user['score_count'] < $gift_detail['payment_integral']){
                    $this->error_tips(''.$this->config['score_name'].'不足，暂时无法兑换！');
                }
            }

            /* if($now_user['score_count'] < $gift_detail['payment_integral']){
                $this->error_tips('积分不足，暂时无法兑换！');
            } */

            $order_sum = $database_gift_order->where(array('gift_id'=>$gift_id,'uid'=>$this->user_session['uid']))->sum('num');

            if(($gift_detail['exchange_limit_num'] > 0) && ($gift_detail['exchange_limit_num'] < $order_sum + $_POST['num'])){
                $this->error_tips('该礼品您每人限定购买'.$gift_detail['exchange_limit_num'].'件,您现在只可购买'.($gift_detail['exchange_limit_num'] - $order_sum.'件'));
            }

            $_POST['is_source'] = 1;
            $result = $database_gift_order->save_post_form($gift_detail,$this->user_session['uid'] , 0);

            if(!empty($result['flag'])){
                $this->redirect('chk_buy',array('order_id'=>$result['order_id'],'num'=>$_POST['num']));
                exit;
            }

            if($result['error']==1){
                $this->error_tips($result['msg']);
            }

            $this->redirect(U('success_order'),array('order_id'=>$result['order_id']));
        }else{
            $this->error_tips('访问页面有误！');
        }
    }


    public function pay_order(){
        if(empty($this->user_session)){
            $this->assign('jumpUrl',U('Index/Login/index'));
            $this->error('请先登录！');
        }

        $database_gift = D('Gift');
        $database_gift_order = D('Gift_order');

        $order_id = $_GET['order_id'] + 0;
        if(!$order_id){
            $this->error_tips('传递参数有误！');
        }

        $now_order = $database_gift_order->get_pay_order($this->user_session['uid'],$order_id);
        $now_order = $now_order['order_info'];
        $gift_condition['gift_id'] = $now_order['gift_id'];
        $gift_detail = $database_gift->gift_detail($gift_condition);
        if(empty($gift_detail['status'])){
            $this->error_tips('该礼品不存在！');
        }
        $result = $database_gift_order->save_post_form($gift_detail['detail'],$this->user_session['uid'] , $order_id);

        if(!empty($result['flag'])){
            $this->redirect('chk_buy',array('order_id'=>$result['order_id'],'num'=>$result['num']));
            exit;
        }

        if($result['error']==1){
            $this->error_tips($result['msg']);
        }
        if(!$result['error']){
            $this->redirect($result['url']);
        }else{
            $this->error('数据处理有误！');
        }
    }

    public function chk_buy(){
        $order_id = $_GET['order_id'] + 0;

        if(empty($order_id)){
            $this->error_tips('传递参数有误！');
        }

        $gift_order_condition['order_id'] = $order_id;
        $database_gift_order = D('Gift_order');
        $now_order = $database_gift_order->get_order_detail_by_id($this->user_session['uid'] , $order_id);

        if(isset($now_order['remain_num']) && ($now_order['remain_num'] <= 0)){
            $this->error_tips('该礼品您每人限定购买'.$now_order['exchange_limit_num'].'件');
        }

        if(empty($now_order)){
            $this->error_tips('该订单不存在！');
        }

        $this->assign('now_order' , $now_order);
        $this->display();
    }


    public function submit(){
        header('Content-Type: application/json; charset=utf-8');
        $quantity = intval(I('q'));
        $order_id = $_GET['order_id'] + 0;
        $databse_gift_order = D('Gift_order');

        if($quantity < 1){
            exit(json_encode(array('status'=>0,'info'=>'最少需要参与1次')));
        }else if($quantity > 200){
            exit(json_encode(array('status'=>0,'info'=>'一次性最多购买200次，请分批次购买')));
        }
        if(empty($this->user_session)){
            exit(json_encode(array('status'=>-3,'info'=>'请先进行登录')));
        }

        $now_order = $databse_gift_order->get_order_detail_by_id($this->user_session['uid'],$order_id);


        $database_gift = D('Gift');
        $gift_id = $now_order['gift_id'];
        $gift_condition['gift_id'] = $gift_id;
        $gift_condition['is_del'] = 0;
        $gift_condition['status'] = 1;

        $now_gift = $database_gift->gift_detail($gift_condition);
        if(empty($now_gift['status'])){
            exit(json_encode(array('status'=>0,'info'=>'该礼品不存在')));
        }

        $now_gift = $now_gift['detail'];
        $surplus_count = $now_gift['sku'] - $now_gift['sale_count'];
        if($surplus_count == 0){
            exit(json_encode(array('status'=>-2,'info'=>'该礼品已经结束，点击确定按钮 刷新页面！')));
        }
        if($surplus_count < $quantity){
            if($exchange_limit_num = $now_gift['exchange_limit_num']){
                exit(json_encode(array('status'=>-1,'info'=>'您最多只能参加 '.($exchange_limit_num).' 次','count'=>$surplus_count)));
            }else{
                exit(json_encode(array('status'=>-1,'info'=>'您最多只能参加 '.($surplus_count).' 次','count'=>$surplus_count)));
            }
        }

        $now_gift['money'] = floatval($now_gift['payment_money']);

        $now_user = D('User')->get_user($this->user_session['uid']);
        if(empty($now_user)){
            exit(json_encode(array('status'=>0,'info'=>'未获取到您的帐号信息，请重试')));
        }
        if(!(M('User_adress')->where(array('uid'=>$this->user_session['uid']))->find())){
            exit(json_encode(array('status'=>-5,'info'=>'您必须添加一个收货地址才能进行兑换')));
        }

        if($now_order['exchange_type'] == 0){
            $use_score = $now_order['payment_pure_integral'] * $quantity;
            if($now_user['score_count'] < $use_score){
                exit(json_encode(array('status'=>0,'info'=>'您的帐户'.$this->config['score_name'].'为 '. $now_user['score_count'].' ，不足以兑换此奖品')));
            }

        }elseif($now_order['exchange_type'] == 1){
            $use_score = $now_order['payment_integral'] * $now_order['num'];
            if($now_user['score_count'] < $use_score){
                exit(json_encode(array('status'=>0,'info'=>'您的帐户'.$this->config['score_name'].'为 '. $now_user['score_count'].' ，不足以兑换此奖品')));
            }

            $use_money = $now_order['payment_money'] * $quantity;
            if($now_user['now_money'] < $use_money){
                exit(json_encode(array('status'=>-4,'info'=>'您的帐户余额为 <span>'.$now_user['now_money'].'</span> 元，请先充值帐户余额','recharge'=>$use_money-$now_user['now_money'])));
            }

        }

        $order_param['order_id'] = $order_id;
        $order_param['num'] = $quantity;
        $databse_gift_order->after_pay($order_param);

        if($_GET['source'] == 'wap'){
            exit(json_encode(array('status'=>-6,'info'=>'兑换成功')));
        }else{
            $this->redirect('success_order',array('order_id'=>$order_id));
        }
    }

    public function success_order(){
        $order_id = $_GET['order_id'] + 0;
        if(empty($order_id)){
            $this->error_tips('传递参数有误！~~~');
        }

        $database_gift_order = D('Gift_order');
        $now_gift_order = $database_gift_order->get_order_detail_by_id($this->user_session['uid'],$order_id,true);
        $this->assign('now_gift_order' , $now_gift_order);
        $this->display();
    }


    public function ajax_gift_list(){
        if(IS_POST){
            $cat_fid  = $_POST['cat_fid'] + 0;
            if(!isset($cat_fid) && empty($cat_fid)){
                $this->error_tips('传递参数有误！');
            }

            $database_gift = D('Gift');
            $gift_condition['is_del'] = 0;
            $gift_condition['status'] = 1;
            $gift_condition['cat_fid'] = $cat_fid;
            $gift_list = $database_gift->gift_page_list($gift_condition , true ,'sort desc', 8);

            if($gift_list['status']){
                exit(json_encode(array('status'=>1,'gift_list'=>$gift_list['list'])));
            }else{
                exit(json_encode(array('status'=>0,'gift_list'=>$gift_list['list'])));
            }

        }else{
            $this->error_tips('访问页面有误！~~~');
        }
    }


    public function chk_order(){
        $order_id = $_GET['order_id'] + 0;

        if(!$order_id){
            $this->error_tips('传递参数有误！');
        }

        $database_gift_order = D('Gift_order');
        $where['order_id'] = $order_id;
        $insert_id = $database_gift_order->where($where)->setField('status',2);

        if($insert_id){
            $this->assign('lastPageNeedReload' , 1);
            $this->success_tips('确认收货成功！');
        }else{
            $this->assign('lastPageNeedReload' , 1);
            $this->error_tips('确认收货失败！');
        }
    }
}
?>