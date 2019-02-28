<?php
/*
 * 首页
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/12/20 10:54
 * 
 */
class IndexAction extends BaseAction {

	protected function  _initialize(){
		parent::_initialize();
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);

		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
	}

    public function index(){
		//我的团购列表
		$this->assign(D('Group')->get_order_list($this->now_user['uid'],intval($_GET['status']),false));

		$this->display();
    }
       public function myinfo(){
		/*if($this->now_user['status']==2){
		 $this->display();
		}else{
		     redirect('http://'.$_SERVER['HTTP_HOST']);
		}*/
		$this->display();
    }

	public function savemyinfo(){
	    $_POST['truename']=trim($_POST['truename']);
		if(empty($_POST['truename'])) $this->dexit(array('error'=>1,'msg'=>'您的姓名必须要填写！'));
		if(empty($_POST['youaddress'])) $this->dexit(array('error'=>1,'msg'=>'通讯地址必须要填写！'));
	    if(M('User')->where(array('uid'=>$this->now_user['uid']))->data($_POST)->save()){
		      $this->dexit(array('error'=>0,'msg'=>'保存成功！'));
		}
		$this->dexit(array('error'=>1,'msg'=>'保存失败！'));
	}
	/*     * json 格式封装函数* */

    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }

    //我的预约列表
 	public function appoint_order(){
 		if(empty($this->user_session)){
			$this->error('请先进行登录！');
		}
		$order_list = D('Appoint')->get_order_list($this->now_user['uid'], intval($_GET['status']));
		$this->assign('order_list', $order_list['order_list']);
		$this->assign('pagebar', $order_list['pagebar']);
		$this->display();
    }

	public function group_order_view(){
		$now_order = D('Group_order')->get_order_detail_by_id($this->now_user['uid'],$_GET['order_id']);
		$now_group = D('Group')->where(array('group_id'=>$now_order['group_id']))->find();
		$this->assign('now_group',$now_group);
		$now_order['order_type'] = 'group';
		$laste_order_info=D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
		if(!$now_order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/index.php?g=Index&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl);
				$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'], intval($_GET['order_id']), true);
			}
		}
		if(empty($now_order)){
			$this->error('当前订单不存在！',U('Index/index'));
		}
		$uid = $this->now_user['uid'];
		$group_share_num = D('Group_share_relation')->get_share_num($uid,$now_order['order_id']);
		if($now_group['group_share_num']==0&&$now_group['open_now_num']==0&&$now_group['open_num']==0){
			M('Group_order')->where(array('order_id'=>$now_order['order_id']))->save(array('is_share_group'=>2));
			$now_order['is_share_group']=2;
		}else if($now_group['group_share_num']!=0&&$now_group['group_share_num']<=$group_share_num){
			M('Group_order')->where(array('order_id'=>$now_order['order_id']))->save(array('is_share_group'=>2));
			$now_order['is_share_group']=2;
		}else if($now_group['open_now_num']<=$now_group['sale_count']&&$now_group['open_now_num']!=0&&$now_group['group_share_num']==0){
			M('Group_order')->where(array('order_id'=>$now_order['order_id']))->save(array('is_share_group'=>2));
			$now_order['is_share_group']=2;
		}else if($now_group['open_num']<=$now_group['sale_count']&&$now_group['open_num']!=0&&$now_group['open_now_num']==0&&$now_group['group_share_num']==0){
			M('Group_order')->where(array('order_id'=>$now_order['order_id']))->save(array('is_share_group'=>2));
			$now_order['is_share_group']=2;
		}
		if(!empty($now_order['card_id'])){
			$now_card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
			$now_order['coupon_type'] = 'mer';
			$now_order['coupon_price'] = $now_card['price'];
		}
		if(!empty($now_order['coupon_id'])){
			$now_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
			$now_order['coupon_type'] = 'system';
			$now_order['coupon_price'] = $now_coupon['price'];
		}

		if($now_group['group_share_num']>0){
			$share_user = D('Group_share_relation')->get_share_user($this->user_session['uid'],$now_order['order_id']);
			$this->assign('share_user',$share_user);
		}

		$has_pay = $now_order['wx_cheap']+$now_order['merchant_balance']+$now_order['balance_pay']+$now_order['score_deducte']+$now_order['coupon_price']+$now_order['payment_money'];
		if($now_order['pass_array']){
			$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
			$consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
			$unconsume_pass_num = $now_order['num']-$consume_num;

			$this->assign('pass_array',$pass_array);
		}

		if($now_order['status']==6){
			//$total_pay = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance'];
			if($now_order['num']!=$unconsume_pass_num){
				$refund_money = round($has_pay-$now_order['price']*$consume_num-$now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100>0? $has_pay-$now_order['price']*$consume_num-$now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100:0,2);
			}else{
				$refund_money = $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money'];
			}
			$now_order['refund_total'] = $refund_money;
			$now_order['refund_fee'] = round($now_order['price']*$now_group['group_refund_fee']*$unconsume_pass_num/100,2);
		}else{
			$now_order['refund_total'] = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance'];
			$now_order['refund_fee'] = round($now_order['price']*$now_group['group_refund_fee']*$unconsume_pass_num/100,2);
		}

		$this->assign('now_order',$now_order);
		$this->assign('group_share_num',$group_share_num);
		$is_shared = D('Group_share_relation')->check_share($uid,$now_order['order_id']);
		if($is_shared['res']['fid']==$now_order['order_id']){
			$is_shared['res']['fid']=$now_order['order_id'];
		}
		$this->assign('is_shared',$is_shared);
		$this->display();
	}

	public function appoint_order_view(){
		$now_order = D('Appoint_order')->get_order_detail_by_id($this->now_user['uid'], $_GET['order_id']);

		$now_order['order_type'] = 'appoint';
		$laste_order_info=D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
		if(!$now_order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/index.php?g=Index&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl);
				$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'], intval($_GET['order_id']), true);
			}
		}
		if(empty($now_order['order_id'])){
			$this->error('当前订单不存在！', U('Index/appoint_order'));
		} else {
			$this->assign('now_order', $now_order);
		}

		$this->display();
	}

	public function group_order_del(){
		$now_order = D('Group_order')->get_order_detail_by_id($this->now_user['uid'],$_GET['order_id']);
		if(empty($now_order)){
			$this->error('当前订单不存在！',U('Index/index'));
		}else if($now_order['paid']){
			$this->error('当前订单已付款，不能删除。');
		}

		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['status'] = 4;
		if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
			$this->success('删除成功！',U('Index/index'));
		}else{
			$this->error('删除失败！请重试。');
		}
	}

	//取消订单
	public function group_order_check_refund(){
		if(empty($this->user_session)){
			$this->error('请先进行登录！');
		}
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
		$now_group = M('Group')->where(array('group_id'=>$now_order['group_id']))->find();
		if(empty($now_order)){
			$this->error('当前订单不存在');
		}
		if(empty($now_order['paid'])){
			$this->error('当前订单还未付款！');
		}
		if ($now_order['status'] > 0 && $now_order['status'] < 3) {
			$this->error('订单必须是未消费状态才能取消！',U('group_order_view',array('order_id'=>$now_order['order_id'])));
		} elseif ($now_order['status'] > 2) {
			$this->redirect(U('group_order_view',array('order_id'=>$now_order['order_id'])));
		}

		if($now_order['pass_array']){
			$consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
			$unconsume_pass_num = $now_order['num']-$consume_num;
		}elseif($now_order['tuan_type']==2){
			$unconsume_pass_num  = $now_order['num'];
		}else{
			$unconsume_pass_num=1;
		}


		//在线付款退款
		if($now_order['pay_type'] == 'offline'){
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize(array('refund_time'=>time()));
			$data_group_order['status'] = 3;
			if(D('Group_order')->data($data_group_order)->save()){
				//2015-12-24     线下退款时销量回滚
				$update_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();
				if ($update_group['type'] == 3) {
					$sale_count = $update_group['sale_count'] - $now_order['num'];
					$sale_count = $sale_count > 0 ? $sale_count : 0;
					$update_group_data = array('sale_count' => $sale_count);
					if ($update_group['count_num'] > 0 && $sale_count < $update_group['count_num']) {
						$update_group_data['type'] = 1;
					}
					D('Group')->where(array('group_id' => $now_order['group_id']))->save($update_group_data);
				} else {
					//退款时销量回滚
					D('Group')->where(array('group_id' => $now_order['group_id']))->setDec('sale_count', $now_order['num']);
				}
				//如果使用了优惠券
				if($now_order['card_id']&&$unconsume_pass_num==$now_order['num']){
					$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//用户积分退款是回滚

				if ($now_order['score_used_count']>0&&$unconsume_pass_num==$now_order['num']) {
					$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$now_order['order_name'].' '.$this->config['score_name'].'回滚');
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//平台余额退款
				if($now_order['balance_pay'] != '0.00'){
//					if($now_order['num']>$unconsume_pass_num){
//
//						$now_order['balance_pay'] = ($has_pay-$now_order['merchant_balance']-$consume_num*$now_order['price'])>0?($has_pay-$now_order['merchant_balance']-$consume_num*$now_order['price']):0;
//					}
					$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error($result['msg']);
					}
					$go_refund_param['msg'] = '平台余额退款成功';
				}
				//商家会员卡余额退款
				if($now_order['merchant_balance'] != '0.00'){
					$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],'退款 '.$now_order['order_name'].' 增加余额');
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}
				//D('Group_pass_relation')->change_refund_status($now_order['order_id']);
				$this->success('您使用的是线下支付！订单状态已修改为已退款。',U('group_order_view',array('order_id'=>$now_order['order_id'])));
				exit;
			}else{
				$this->error('取消订单失败！请重试。');
			}
		}
		$total_pay = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance'];
		$balance_percent = round($now_order['balance_pay']/$total_pay,4);
		$payment_percent = round($now_order['payment_money']/$total_pay,4);
		$merchant_percent = round($now_order['merchant_balance']/$total_pay,4);


		//线上支付
		if($now_order['payment_money'] != '0.00'){
			if($this->config['open_juhepay']==1 &&( $now_order['pay_type']=='weixin' || $now_order['pay_type']=='alipay')){
				import('@.ORG.LowFeePay');
				$lowfeepay = new LowFeePay('juhepay');
				$now_order['orderNo'] = 'group_'.$now_order['orderid'];
				if($now_order['mer_id']){
                    $mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$now_order['mer_id']))->find();
					if(!empty($mer_juhe)){ 
						$lowfeepay->userId =$mer_juhe['userid'];
						$now_order['is_own'] =1 ;
					    $now_order['orderNo'] =  $now_order['orderNo'].'_1';
					}
					
                }
				$go_refund_param= $lowfeepay->refund($now_order);

			}else {
				if ($now_order['num'] > $unconsume_pass_num) {
					$now_order['payment_money'] = round($now_order['price'] * $unconsume_pass_num * (1 - $now_group['group_refund_fee'] / 100 * $payment_percent), 2);
				} else {
					$now_order['payment_money'] -= round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $payment_percent, 2);
				}
				if ($now_order['is_own']) {
					$pay_method = array();
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['mer_id']))->find();
					foreach ($merchant_ownpay as $ownKey => $ownValue) {
						$ownValueArr = unserialize($ownValue);
						if ($ownValueArr['open']) {
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
						}
					}
				} else {
					$pay_method = D('Config')->get_pay_method();
				}

				if (empty($pay_method)) {
					$this->error('系统管理员没开启任一一种支付方式！');
				}
				if (empty($pay_method[$now_order['pay_type']])) {
					$this->error('您选择的支付方式不存在，请更新支付方式！');
				}

				$pay_class_name = ucfirst($now_order['pay_type']);
				$import_result = import('@.ORG.pay.' . $pay_class_name);
				if (empty($import_result)) {
					$this->error('系统管理员暂未开启该支付方式，请更换其他的支付方式');
				}
				$now_order['order_type'] = 'group';


				if (!empty($now_order['orderid'])) {
					$now_order['order_id'] = $now_order['orderid'];
				}
				$pay_class = new $pay_class_name($now_order, $now_order['payment_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, $now_order['is_mobile_pay']);
				$go_refund_param = $pay_class->refund();
			}
			//file_put_contents('log.txt',var_export($go_refund_param,true));
			$now_order['order_id'] = $_GET['order_id'];
			$data_group_order['order_id'] = $_GET['order_id'];
			$data_group_order['refund_detail'] = serialize($go_refund_param['refund_param']);
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$data_group_order['status'] = 3;
			}
			D('Group_order')->data($data_group_order)->save();
			if($data_group_order['status'] != 3){
				$this->error($go_refund_param['msg']);
			}
		}
		//如果使用了优惠券
		if($now_order['card_id']){
			$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//用户积分退款是回滚

		if ($now_order['score_used_count']>0&&$unconsume_pass_num==$now_order['num']) {
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$now_order['order_name'].' '.$this->config['score_name'].'回滚');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//平台余额退款
		if($now_order['balance_pay'] != '0.00'){
			if($now_order['num']>$unconsume_pass_num){
				$now_order['balance_pay'] = round($now_order['price']*$unconsume_pass_num*(1-$now_group['group_refund_fee']/100)*$balance_percent,2);
			}else{
				$now_order['balance_pay'] -= round($now_order['total_money']*$now_group['group_refund_fee']/100*$balance_percent,2);
			}

			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = '平台余额退款成功';
		}
		//商家会员卡余额退款
		if($now_order['merchant_balance'] != '0.00'){

			if($now_order['num']>$unconsume_pass_num){
				$now_order['merchant_balance'] = round($now_order['price']*$unconsume_pass_num*(1-$now_group['group_refund_fee']/100)*$merchant_percent,2);
			}else{
				$now_order['merchant_balance'] -= round($now_order['total_money']*$now_group['group_refund_fee']/100*$merchant_percent,2);
			}
			$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],'退款 '.$now_order['order_name'].' 增加余额');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//2015-12-9     退款时销量回滚
		if($unconsume_pass_num!=$now_order['num']){
			$now_order['num']=$unconsume_pass_num;
			$refund_fee = round($now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100,2);
			$refund_money = round($total_pay+$now_order['score_deducte']+$now_order['coupon_price']+$now_order['card_price']-$now_order['price']*$consume_num-$refund_fee,2);
			$data_group_order['refund_money'] = $refund_money;
			$data_group_order['order_id'] = $now_order['order_id'];
			if($refund_fee>0){
				$data_group_order['refund_fee'] = $refund_fee;
			}
			$data_group_order['status'] = 6;
			D('Group_order')->data($data_group_order)->save();
		}

		$update_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();
		if ($update_group['type'] == 3) {
			$sale_count = $update_group['sale_count'] - $now_order['num'];
			$sale_count = $sale_count > 0 ? $sale_count : 0;
			$update_group_data = array('sale_count' => $sale_count);
			if ($update_group['count_num'] > 0 && $sale_count < $update_group['count_num']) {
				$update_group_data['type'] = 1;
			}
			D('Group')->where(array('group_id' => $now_order['group_id']))->save($update_group_data);
		} else {
			//退款时销量回滚
			D('Group')->where(array('group_id' => $now_order['group_id'], 'sale_count' => array('egt', $now_order['num'])))->setDec('sale_count', $now_order['num']);
		}

		//短信提醒
		$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'group');
		if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您购买 '.$now_order['order_name'].'的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
			$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $merchant['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
			Sms::sendSms($sms_data);
		}
		D('Group_pass_relation')->change_refund_status($now_order['order_id']);
		$this->success($go_refund_param['msg'],U('group_order_view',array('order_id'=>$_GET['order_id'])));
	}

	public function appoint_order_del(){
		$databaes_appoint_order = D('Appoint_order');
		$order_id = $_GET['order_id'] + 0;
		$now_order = $databaes_appoint_order->get_order_detail_by_id($this->now_user['uid'] , $order_id);
		if(empty($now_order)){
			$this->error('当前订单不存在！',U('Index/appoint_order'));
		}else if($now_order['paid']){
			$this->error('当前订单已付款，不能删除。');
		}

		$condition_appoint_order['order_id'] = $now_order['order_id'];
		$data_appoint_order['is_del'] = 1;
		$data_appoint_order['del_time'] = time();

		if($databaes_appoint_order->where($condition_appoint_order)->data($data_appoint_order)->save()){
			$this->success('取消成功！',U('Index/appoint_order'));
		}else{
			$this->error('取消失败！请重试。');
		}
	}

	public function meal_list(){
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		//$where = array('uid' => $this->now_user['uid'], 'status' => array('lt', 4));
		$where = array('uid' => $this->now_user['uid']);
		if ($status == 0) {
			$where['paid'] = 0;
		} elseif ($status == 1) {
			$where['status'] = 0;
		} elseif ($status == 2) {
			$where['status'] = 1;
		}

		import('@.ORG.user_page');
		$count = M("Meal_order")->where($where)->count();
		$p = new Page($count,10);

		$orders = M("Meal_order")->where($where)->order('order_id DESC')->limit($p->firstRow.',10')->select();
		$tmp = array();
		foreach ($orders as $o) {
			$tmp[] = $o['store_id'];
		}
		if ($tmp) {
			$store_image_class = new store_image();
			$store = D('Merchant_store')->where(array('store_id' => array('in', $tmp)))->select();
			$list = array();
			foreach ($store as $v) {
				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
				$v['image'] = $images ? array_shift($images) : array();
				$list[$v['store_id']] = $v;
			}
		}

		foreach ($orders as &$or) {
			$or['image'] = isset($list[$or['store_id']]['image']) ? $list[$or['store_id']]['image'] : '';
			$or['s_name'] = isset($list[$or['store_id']]['name']) ? $list[$or['store_id']]['name'] : '';
			$or['url'] = C('config.site_url').'/meal/'.$or['store_id'].'.html';
		}
		$this->assign('order_list', $orders);
		$this->assign('status', $status);
		$this->assign('pagebar', $p->show());

		$this->display();
	}

	public function meal_order_view()
	{
		$now_order = D('Meal_order')->get_order_by_id($this->now_user['uid'],$_GET['order_id']);
		$now_order['info'] = unserialize($now_order['info']);
		$now_order['order_type'] = 'meal';
		$laste_order_info=D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
		if(!$now_order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/index.php?g=Index&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl);
				$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'], intval($_GET['order_id']), true);
			}
		}
		$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], $now_order['is_mobile_pay']);

		if ($now_order['meal_pass']) {
			$now_order['meal_pass_txt'] = preg_replace('#(\d{4})#','$1 ',$now_order['meal_pass']);
		}
		$now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
		if (empty($now_order)) {
			$this->error('当前订单不存在！');
		} else {
			$this->assign('now_order',$now_order);
		}

		$this->display();
	}

	public function meal_order_del()
	{
		$now_order = D('Meal_order')->get_order_by_id($this->now_user['uid'],$_GET['order_id']);
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}else{
			D('Meal_order')->where(array('uid' => $this->now_user['uid'], 'order_id' => $_GET['order_id']))->save(array('status' => 4));
			$this->success('订单删除成功', U('User/Index/meal_list'));
		}
	}


	public function meal_order_check_refund()
	{
		if(empty($this->user_session)){
			$this->error('请先进行登录！');
		}
		$orderid = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$now_order = M("Meal_order")->where(array('order_id' => $orderid))->find();

		if(empty($now_order)){
			$this->error('当前订单不存在');
		}

		if ($now_order['is_confirm']) {
			$this->error('当前订单店员正在处理中，不能退款或取消');
		}
		if(empty($now_order['paid'])){
			$this->error('当前订单还未付款！');
		}

		if ($now_order['meal_type']) {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error('订单必须是未消费状态才能取消！', U('Index/meal_order_view',array('order_id'=>$now_order['order_id'])));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Index/meal_order_view',array('order_id'=>$now_order['order_id'])));
			}
		} else {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error('订单必须是未消费状态才能取消！', U('Index/meal_order_view',array('order_id'=>$now_order['order_id'])));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Index/meal_order_view',array('order_id'=>$now_order['order_id'])));
			}
		}


		$my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();

		//在线付款退款
		if($now_order['pay_type'] == 'offline'){
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize(array('refund_time'=>time()));
			$data_meal_order['status'] = 3;
			if(D('Meal_order')->data($data_meal_order)->save()){
				//退款打印
			    $printHaddle = new PrintHaddle();
			    $printHaddle->printit($now_order['order_id'], 'meal_order', 3);

			    $mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $now_order['store_id']))->find();
				$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
				if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'] ? $now_order['phone'] : $my_user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
					Sms::sendSms($sms_data);
				}
				if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $mer_store['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客' . $now_order['name'] . '的预定订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
					Sms::sendSms($sms_data);
				}
				//如果使用了优惠券
				if($now_order['card_id']){
					$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//如果使用了积分 2016-1-15
				if ($now_order['score_used_count']>0) {
					$order_info=unserialize($now_order['info']);
					$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
					$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$order_name.' '.$this->config['score_name'].'回滚');
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//平台余额退款
				if($now_order['balance_pay'] != '0.00'){
					$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error($result['msg']);
					}
					$go_refund_param['msg'] = '平台余额退款成功';
				}
				//商家会员卡余额退款
				if($now_order['merchant_balance'] != '0.00'){
					$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				$this->success($go_refund_param['msg'],U('Index/meal_order_view',array('order_id'=>$_GET['order_id'])));
				exit;
			}else{
				$this->error('取消订单失败！请重试。');
			}
		}

		if($now_order['payment_money'] != '0.00'){
			if($now_order['is_own']){
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_order['mer_id']))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
			}else{
				$pay_method = D('Config')->get_pay_method();
			}

			if(empty($pay_method)){
				$this->error('系统管理员没开启任一一种支付方式！');
			}
			if(empty($pay_method[$now_order['pay_type']])){
				$this->error('您选择的支付方式不存在，请更新支付方式！');
			}

			$pay_class_name = ucfirst($now_order['pay_type']);
			$import_result = import('@.ORG.pay.'.$pay_class_name);
			if(empty($import_result)){
				$this->error('系统管理员暂未开启该支付方式，请更换其他的支付方式');
			}

			if ($now_order['meal_type'] == 1) {
				$now_order['order_type'] = 'takeout';
			} elseif ($now_order['meal_type'] == 2) {
				$now_order['order_type'] = 'foodPad';
			} else {
				$now_order['order_type'] = 'food';
			}
			$order_id = $now_order['order_id'];
			$now_order['order_id'] = $now_order['orderid'];

			$pay_class = new $pay_class_name($now_order,$now_order['payment_money'],$now_order['pay_type'],$pay_method[$now_order['pay_type']]['config'],$this->user_session,1);
			$go_refund_param = $pay_class->refund();

			$now_order['order_id'] = $orderid;
			$data_meal_order['order_id'] = $orderid;
			$data_meal_order['refund_detail'] = serialize($go_refund_param['refund_param']);
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$data_meal_order['status'] = 3;
			}

			D('Meal_order')->data($data_meal_order)->save();
			if($data_meal_order['status'] != 3){
				$this->error($go_refund_param['msg']);
			}
		}


		//如果使用了优惠券
		if($now_order['card_id']){
			$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}


		//如果使用了积分 2016-1-15
		if ($now_order['score_used_count']>0) {
			$order_info=unserialize($now_order['info']);
			$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$order_name.' '.$this->config['score_name'].'回滚');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] .= $result['msg'];
		}

		//平台余额退款
		if($now_order['balance_pay'] != '0.00'){
			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = '平台余额退款成功';
		}
		//商家会员卡余额退款
		if($now_order['merchant_balance'] != '0.00'){
			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}
		if(empty($now_order['pay_type'])){
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			$go_refund_param['msg'] = '取消订单成功';
		}

		//退款时销量回滚
		if ($now_order['paid'] == 1 && date('m', $now_order['dateline']) == date('m')) {
			foreach (unserialize($now_order['info']) as $menu) {
				D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
			}
		}
		D("Merchant_store_meal")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);

		//退款打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($now_order['order_id'], 'meal_order', 3);

		$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $now_order['store_id']))->find();
		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
		if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['phone'] ? $now_order['phone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $mer_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客' . $now_order['name'] . '的预定订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
			Sms::sendSms($sms_data);
		}

		$this->success($go_refund_param['msg'], U('Index/meal_order_view',array('order_id'=>$orderid)));
	}


	public function lifeservice(){
		import('@.ORG.user_page');
		$condition_where = array('uid'=>$this->user_session['uid'],'status'=>array('neq','0'));
		$count = D('Service_order')->where($condition_where)->count();
		$p = new Page($count,20);

		$order_list = D('Service_order')->field(true)->where($condition_where)->order('`order_id` DESC')->limit($p->firstRow.',10')->select();
		foreach($order_list as &$value){
			$value['type_txt'] = $this->lifeservice_type_txt($value['type']);
			$value['type_eng'] = $this->lifeservice_type_eng($value['type']);
			$value['infoArr'] = unserialize($value['info']);
			$value['order_url'] = U('My/lifeservice_detail',array('id'=>$value['order_id']));
		}
		$this->assign('pagebar', $p->show());
		$this->assign('order_list', $order_list);
		$this->display();
	}
	public function lifeservice_detail(){
		$now_order = D('Service_order')->field(true)->where(array('order_id'=>$_GET['order_id']))->find();
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}
		$now_order['infoArr'] = unserialize($now_order['info']);
		$now_order['type_txt'] = $this->lifeservice_type_txt($now_order['type']);
		$now_order['type_eng'] = $this->lifeservice_type_eng($now_order['type']);
		$now_order['pay_money'] = floatval($now_order['pay_money']);
		$this->assign('now_order', $now_order);
		// dump($now_order);
		$this->display();
	}
	protected function lifeservice_type_txt($type){
		switch($type){
			case '1':
				$type_txt = '水费';
				break;
			case '2':
				$type_txt = '电费';
				break;
			case '3':
				$type_txt = '煤气费';
				break;
			default:
				$type_txt = '生活服务';
		}
		return $type_txt;
	}
	protected function lifeservice_type_eng($type){
		switch($type){
			case '1':
				$type_txt = 'water';
				break;
			case '2':
				$type_txt = 'electric';
				break;
			case '3':
				$type_txt = 'gas';
				break;
			default:
				$type_txt = 'life';
		}
		return $type_txt;
	}

	public function  is_group_share(){
		if(!M('Group_order')->where(array('order_id'=>$_POST['order_id']))->save(array('is_share_group'=>1))){
			$this->error('更新组团购分享失败！');
		}else{
			$date['fid']=$_POST['order_id'];
			$date['uid']=$_POST['uid'];
			$date['order_id']=$_POST['order_id'];
			$result = M('Group_share_relation')->where($date)->find();
			if($result){
				$this->success('已经生成团购分组，不能再生成了！');
			}
			M('Group_share_relation')->add($date);
			$this->success('更新组团购分享成功！');
		}
	}

	public function ajax_group_share_num(){
		$uid = $_POST['uid'];
		$order_id = $_POST['order_id'];
		if(!$order_id||!uid){
			exit(json_encode(array('error_code'=>1,'msg'=>'传递参数有误！')));
		}
		$num = D('Group_share_relation')->get_share_num($uid,$order_id);

		exit(json_encode(array('error_code'=>0,'num'=>(int)$num)));

	}

	public  function  ajax_group_user(){
		$uid = $_POST['uid'];
		$order_id = $_POST['order_id'];
		$uids = explode(',',substr($_POST['uids'],0,-1));

		if(!$order_id||!uid){
			exit(json_encode(array('error_code'=>1,'msg'=>'传递参数有误！')));
		}

		$res['user_arr'] = D('Group_share_relation')->get_share_user($uid,$order_id);
		foreach($res['user_arr'] as $v){
			if(in_array($v['uid'],$uids)){
				$res['in'][] = $v['uid'];
			}
		}
		foreach($uids as $vv){
			if(!in_array($vv,$res['in'])){
				$res['not_in'][] = $vv;
			}
		}
		exit(json_encode(array('error_code'=>0,'res'=>$res)));
	}
	public function change_is_share(){
		if(!M('Group_order')->where(array('order_id'=>$_POST['order_id']))->save(array('is_share_group'=>2))){
			$this->error('更新组团购分享失败！');
		}
	}

	public function shop_list()
	{
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$where = array('uid' => $this->now_user['uid']);
		if ($status == 0) {
			$where['paid'] = 0;
// 			$where['status'] = 0;
		} elseif ($status == 1) {
			$where['paid'] = 1;
			$where['status'] = array('lt', 2);
		} elseif ($status == 2) {
			$where['status'] = 2;
		}

		$orders = D("Shop_order")->get_order_list($where, 'order_id DESC');
		$pagebar = $orders['pagebar'];
		$orders = $orders['order_list'];
		$tmp = array();
		foreach ($orders as $o) {
			$tmp[] = $o['store_id'];
		}
		if ($tmp) {
			$store_image_class = new store_image();
			$store = D('Merchant_store')->where(array('store_id' => array('in', $tmp)))->select();
			$list = array();
			foreach ($store as $v) {
				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
				$v['image'] = $images ? array_shift($images) : array();
				$list[$v['store_id']] = $v;
			}
		}

		foreach ($orders as &$or) {
			$or['image'] = isset($list[$or['store_id']]['image']) ? $list[$or['store_id']]['image'] : '';
			$or['s_name'] = isset($list[$or['store_id']]['name']) ? $list[$or['store_id']]['name'] : '';
			$or['url'] = C('config.site_url').'/shop/'.$or['store_id'].'.html';
		}
		$this->assign('order_list', $orders);
		$this->assign('status', $status);
		$this->assign('pagebar', $pagebar);

		$this->display();
	}


	public function gift_list(){
		$status = $_GET['status'] + 0;
		$database_gift_order = D('Gift_order');
		$orders = $database_gift_order->get_order_list($this->now_user['uid'],$status,true,99999);
		$this->assign('order_list', $orders['order_list']);
		$this->assign('status', $status);
		$this->assign('pagebar', $orders['pagebar']);
		$this->display();
	}

	public function shop_order_view()
	{
		$now_order = D('Shop_order')->get_order_detail(array('uid' => $this->now_user['uid'], 'order_id' => $_GET['order_id']));
		$now_order['order_type'] = 'shop';
		$laste_order_info = D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);

		if (empty($now_order)) {
			$this->error('当前订单不存在！');
		} else {
			$this->assign('now_order',$now_order);
		}

		$this->display();
	}

	public function shop_order_del()
	{
		$now_order = D('Shop_order')->get_order_detail(array('uid' => $this->now_user['uid'], 'order_id' => $_GET['order_id']));
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}else{
			D('Shop_order')->where(array('uid' => $this->now_user['uid'], 'order_id' => $_GET['order_id']))->save(array('status' => 5));
			$this->success('订单删除成功', U('User/Index/shop_list'));
		}
	}


	public function shop_order_check_refund(){
		if (empty($this->user_session)) {
			$this->error('请先进行登录！');
		}
		$order_id = intval($_GET['order_id']);
		$now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id));

		if(empty($now_order)){
			$this->error('当前订单不存在');
		}
		if ($now_order['status'] == 1) {
			$this->error('当前订单店员正在处理中，不能退款或取消');
		}
		if (empty($now_order['paid'])) {
			$this->error('当前订单还未付款！');
		}
		if ($now_order['status'] > 0 && $now_order['status'] < 4) {
			$this->error('订单必须是未消费状态才能取消！', U('shop_order_view',array('order_id'=>$now_order['order_id'])));
		} elseif ($now_order['status'] > 3) {
			$this->redirect(U('shop_order_view',array('order_id'=>$now_order['order_id'])));
		}


		//线下支付退款
		if ($now_order['pay_type'] == 'offline') {
			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
			$data_shop_order['status'] = 4;
			if (D('Shop_order')->data($data_shop_order)->save()) {
				$return = $this->shop_refund_detail($now_order, $now_order['store_id']);
				if ($return['error_code']) {
					$this->error($return['msg']);
				} else {
					$this->success('您使用的是线下支付！订单状态已修改为已退款。',U('shop_order_view',array('order_id' => $now_order['order_id'])));
				}
			} else {
				$this->error('取消订单失败！请重试。');
			}
		} else {
			if ($now_order['payment_money'] != '0.00') {
				if($this->config['open_juhepay']==1 &&( $now_order['pay_type']=='weixin' || $now_order['pay_type']=='alipay')){
					import('@.ORG.LowFeePay');
					$lowfeepay = new LowFeePay('juhepay');
					$now_order['orderNo'] = 'shop_'.$now_order['orderid'];
					if($now_order['mer_id']){
	                    $mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$now_order['mer_id']))->find();
						if(!empty($mer_juhe)){ 
							$lowfeepay->userId =$mer_juhe['userid'];
							$now_order['is_own'] =1 ;
						    $now_order['orderNo'] =  $now_order['orderNo'].'_1';
						}
						
	                }
					$go_refund_param= $lowfeepay->refund($now_order);

				}else {
					if ($now_order['is_own']) {
						$pay_method = array();
						$merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['mer_id']))->find();
						foreach ($merchant_ownpay as $ownKey => $ownValue) {
							$ownValueArr = unserialize($ownValue);
							if ($ownValueArr['open']) {
								$ownValueArr['is_own'] = true;
								$pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
							}
						}
					} else {
						$pay_method = D('Config')->get_pay_method();
					}

					if (empty($pay_method)) {
						$this->error('系统管理员没开启任一一种支付方式！');
					}
					if (empty($pay_method[$now_order['pay_type']])) {
						$this->error('您选择的支付方式不存在，请更新支付方式！');
					}

					$pay_class_name = ucfirst($now_order['pay_type']);
					$import_result = import('@.ORG.pay.' . $pay_class_name);
					if (empty($import_result)) {
						$this->error('系统管理员暂未开启该支付方式，请更换其他的支付方式');
					}

					$now_order['order_type'] = 'shop';
					$now_order['order_id'] = $now_order['orderid'];

					$pay_class = new $pay_class_name($now_order, $now_order['payment_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, 1);
					$go_refund_param = $pay_class->refund();
				}
				$now_order['order_id'] = $order_id;
				$data_shop_order['order_id'] = $order_id;
				$data_shop_order['refund_detail'] = serialize($go_refund_param['refund_param']);
				if (empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok') {
					$data_shop_order['status'] = 4;
				}
				D('Shop_order')->data($data_shop_order)->save();
				if($data_shop_order['status'] != 4){
					$this->error($go_refund_param['msg']);
				}
			}


			$return = $this->shop_refund_detail($now_order, $now_order['store_id']);
			if ($return['error_code']) {
				$this->error($return['msg']);
			} else {
				$go_refund_param['msg'] = $return['msg'];
			}

			if (empty($now_order['pay_type'])) {
				$data_shop_order['order_id'] = $now_order['order_id'];
				$data_shop_order['status'] = 4;
				D('Shop_order')->data($data_shop_order)->save();
				$go_refund_param['msg'] = '取消订单成功';
			}
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
			$this->success($go_refund_param['msg'], U('shop_order_view',array('order_id' => $order_id)));
		}
	}


	private function shop_refund_detail($now_order, $store_id){
		$order_id  = $now_order['order_id'];

		$mer_store = D('Merchant_store')->where(array('store_id' => $store_id))->find();

		//如果使用了优惠券
		if ($now_order['card_id']) {
			$result = D('Member_card_coupon')->add_card($now_order['card_id'], $now_order['mer_id'], $now_order['uid']);
			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//如果使用了积分 2016-1-15
		if ($now_order['score_used_count'] > 0) {
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 ' . $mer_store['name'] . '(' . $order_id . ') '.$this->config['score_name'].'回滚');
			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//平台余额退款
		if ($now_order['balance_pay'] != '0.00') {
			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 ' . $mer_store['name'] . '(' . $order_id . ') 增加余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = '平台余额退款成功';
		}
		//商家会员卡余额退款
		if ($now_order['merchant_balance'] != '0.00') {
			$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],'退款 ' . $mer_store['name'] . '(' . $order_id . ')  增加余额');
			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//退款打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($now_order['order_id'], 'shop_order', 3);
		
// 		$msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
// 		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
// 		$op->printit($mer_store['mer_id'], $store_id, $msg, 3);

// 		$str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
// 		foreach ($str_format as $print_id => $print_msg) {
// 			$print_id && $op->printit($mer_store['mer_id'], $store_id, $print_msg, 3, $print_id);
// 		}

		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'shop');
		if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['userphone'] ? $now_order['userphone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $mer_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客' . $now_order['username'] . '的预定订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
			Sms::sendSms($sms_data);
		}

		//退款时销量回滚
		if (($now_order['paid'] == 1 || $now_order['reduce_stock_type'] == 1) && $now_order['is_rollback'] == 0) {
			$goods_obj = D("Shop_goods");
			foreach ($now_order['info'] as $menu) {
				$goods_obj->update_stock($menu, 1);//修改库存
			}
			D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_rollback' => 1));
		}
		D("Merchant_store_shop")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);
		//退款时销量回滚

		$go_refund_param['error_code'] = false;
		return $go_refund_param;
	}

	public function sign(){
		$return = D('User')->sign_in($this->user_session['uid']);

		$this->ajaxReturn($return);

	}
	
    public function finishOrder()
    {
        if (empty($this->user_session)) {
            $this->error('请先进行登录！');
        }
        
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $where = array('order_id' => $order_id, 'uid' => $this->user_session['uid']);
        $order = D('Shop_order')->field(true)->where($where)->find();
        if (empty($order)) {
            $this->error('订单信息不正确');
        }
        $data = array('status' => 2);
        $data['use_time'] = $_SERVER['REQUEST_TIME'];
        $data['last_time'] = $_SERVER['REQUEST_TIME'];
        if (D('Shop_order')->where($where)->save($data)) {
            D('Shop_order')->shop_notice($order);
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 6, 'name' => '', 'phone' => ''));
            $this->success('收货成功', U('shop_order_view',array('order_id' => $order_id)));
        } else {
            $this->error('订单状态修改失败，稍后重试');
        }
    }
}