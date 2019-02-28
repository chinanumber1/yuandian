<?php
class Shop_orderModel extends Model
{
    public $status_list = array('-1' => '全部' ,'0' => '未接单', 1 => '已确认', 2 => '已消费', 3 => '已评价', 4 => '已退款', 5 => '已取消', 6 => '未支付', 7 => '分配到自提点', 8 => '发货到自提点', 9 => '自提点接货', 10 => '自提点发货');
	public $status_list_admin = array('-1' => '全部', '100'=>'未支付' ,'0' => '未接单', 1 => '已确认', 2 => '已消费', 3 => '已评价', 4 => '已退款', 5 => '已取消', 7 => '分配到自提点', 8 => '发货到自提点', 9 => '自提点接货', 10 => '自提点发货');
	/**获取订单分类**/
	public function get_order_cate($order_id){
		$store_id = $this->field('store_id')->where(array('order_id'=>$order_id))->find();
		$cat_id = M('Shop_category_relation')->field('cat_fid')->where(array('store_id'=>$store_id['store_id']))->find();
		$meal_cate = M('Shop_category')->field('cat_id,cat_name')->where(array('cat_id'=>$cat_id['cat_fid']))->find();
		return $meal_cate;
	}

	public function get_order_cate_more($order_id){
		$store_id = $this->field('store_id')->where(array('order_id'=>$order_id))->find();
		$cat_id = M('Shop_category_relation')->field('cat_fid')->where(array('store_id'=>$store_id['store_id']))->select();
		foreach($cat_id as $v){
			$catfid_arr[] = $v['cat_fid'];
		}
		$meal_cate = M('Shop_category')->field('cat_id,cat_name')->where(array('cat_id'=>array('in',$catfid_arr)))->select();
		return $meal_cate;
	}

	public function get_order_by_id($uid, $order_id, $orderid = 0){
		$where = array();
		$order_id && $where['order_id'] = $order_id;
		$orderid && $where['orderid'] = $orderid;
		$where['uid'] = $uid;
		return $this->field(true)->where($where)->find();
	}

	public function get_order_by_orderid($order_id){
		$where = array();
		$order_id && $where['order_id'] = $order_id;
		return $this->field(true)->where($where)->find();
	}

	public function get_pay_order($uid, $order_id, $is_web = false,$is_app = false)
	{
		$now_order = $this->get_order_by_id($uid, $order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}
		if($now_order['paid'] == 1){
			if (!$is_web) {
				if ($now_order['order_from'] == 1) {
					return array('error' => 1, 'msg' => '您已经支付过此订单！', 'url' => U('Wap/Mall/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				} else {
					return array('error' => 1, 'msg' => '您已经支付过此订单！', 'url' => U('Wap/Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				}
			} else {
				return array('error' => 1, 'msg' => '您已经支付过此订单！', 'url' => U('User/Index/shop_order_view', array('order_id' => $now_order['order_id'])));
			}
		}

		if($now_order['status'] == 4 || $now_order['status'] == 5){
			if (!$is_web) {
				if ($now_order['order_from'] == 1) {
					return array('error' => 1, 'msg' => '您的订单已取消，不能付款了！', 'url' => U('Wap/Mall/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				} else {
					return array('error' => 1, 'msg' => '您的订单已取消，不能付款了！', 'url' => U('Wap/Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				}
			} else {
				return array('error' => 1, 'msg' => '您的订单已取消，不能付款了！', 'url' => U('User/Index/shop_order_view', array('order_id' => $now_order['order_id'])));
			}
		}
		if ($now_order['is_pick_in_store'] < 2 && $now_order['expect_use_time'] && $now_order['expect_use_time'] < time()) {
		    $this->where(array('order_id' => $order_id))->save(array('status' => 5));
		    D('Shop_order_log')->add_log(array('status' => 10, 'order_id' => $order_id, 'name' => '自动取消', 'note' => '超时未付款，自动取消订单'));
			
			if (!$is_web) {
				$redirectUrl = U('Wap/Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id']));
			}else{
				$redirectUrl = U('User/Index/shop_list');
			}
		    return array('error'=>1,'msg'=>'期望送达时间比当前时间早，无法进行支付，此订单已自动取消，请重新下单！', 'url' => $redirectUrl);
		}

		$merchant_store = M("Merchant_store")->where(array('store_id' => $now_order['store_id'], 'mer_id' => $now_order['mer_id']))->find();
		$imgs =explode(';', $merchant_store['pic_info']);
		foreach($imgs as &$v){
			$v = preg_replace('/,/','/',$v);
		}

		$order_content = D('Shop_order_detail')->join('as o LEFT JOIN '.C('DB_PREFIX').'shop_goods g ON g.goods_id = o.goods_id')->field('o.*,g.score_max,score_percent')->where(array('order_id' => $order_id))->select();
		foreach ($order_content as &$trow) {
			$trow['name'] = $trow['spec'] ? $trow['name'] . ' (' . $trow['spec'] . ')' : $trow['name'];
			$trow['money'] = floatval($trow['price'] * $trow['num']);
		}

		//$str='';
		foreach ($order_content as $val) {
			if(count($order_content)>1) {
				if($is_app){
					$str = $val['name'] . ' ￥' . $val['price'] . ' *' . $val['num'] . ' 等';
				}else{
					$str = $val['name'] . ' ￥' . $val['price'] . ' *' . $val['num'] . '  <b style="color:#BEBFBF;">等</b>';
				}
			}else{
				$str = $val['name'] . ' ￥' . $val['price'] . ' *' . $val['num'] ;
			}
			break;
		}
		if ($is_web) {
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'orderid'			=>	$now_order['orderid'],
					'mer_id'			=>	$now_order['mer_id'],
					'store_id'			=>	$now_order['store_id'],
					'paid'				=>	$now_order['paid'],
					'uid'				=>	$now_order['uid'],
					'balance_pay'		=>	$now_order['balance_pay'],//平台余额支付的金额
					'merchant_balance'	=>	$now_order['merchant_balance'],//商家余额支付的金额
					'payment_money'		=>	$now_order['payment_money'],//在线支付的金额
					'pay_money'			=>	$now_order['pay_money'],//订单已经完成支付的金额
					'order_name'		=>	$merchant_store['name'],
					'order_num'			=>	$now_order['num'],
					'order_from'		=>	$now_order['order_from'],
					'freight_charge'	=>	$now_order['freight_charge'],
					'order_content'		=>  $order_content?$order_content:array(),
					'order_total_money'	=>	$now_order['price'],//当前需要支付的金额
					'order_type'		=>	'shop',
					'extra_price'	=>	$now_order['extra_price'],
					'can_discount_money'	=>	$now_order['can_discount_money'],
			);
		} else {
			$order_info = array(
				'order_id'			=>	$now_order['order_id'],
				'orderid'			=>	$now_order['orderid'],
				'mer_id'			=>	$now_order['mer_id'],
				'store_id'			=>	$now_order['store_id'],
				'paid'				=>	$now_order['paid'],
				'uid'				=>	$now_order['uid'],
				'balance_pay'		=>	$now_order['balance_pay'],//平台余额支付的金额
				'merchant_balance'	=>	$now_order['merchant_balance'],//商家余额支付的金额
				'payment_money'		=>	$now_order['payment_money'],//在线支付的金额
				'pay_money'			=>	$now_order['pay_money'],//订单已经完成支付的金额
				'order_name'		=>	$merchant_store['name'],
				'order_num'			=>	$now_order['num'],
				'order_from'		=>	$now_order['order_from'],
				'order_content'		=>  $order_content?$order_content:array(),
				'order_total_money'	=>	$now_order['price'],
				'freight_charge'	=>	$now_order['freight_charge'],
				'order_type'		=>	'shop',
				'img'				=> C('config.site_url').'/upload/store/'.$imgs[0],
				'order_txt_type'	=>	$str,
				'extra_price'	=>	$now_order['extra_price'],
				'can_discount_money'	=>	$now_order['can_discount_money'],
			);
		}
		return array('error' => 0, 'order_info' => $order_info);
	}


	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_user)
	{
		//判断是否需要在线支付
		if(!$order_info['use_balance']){
			$now_user['now_money']=0;
		}
		if(($now_user['now_money']+$order_info['score_deducte'] )< $order_info['order_total_money']){
			$online_pay = true;
		} else {
			$online_pay = false;
		}
		//不使用在线支付，直接使用会员卡和用户余额。
		if(empty($online_pay)){
			$order_pay['balance_pay'] =  $order_info['order_total_money']-$order_info['score_deducte'];
		}else{
			if(!empty($now_user['now_money'])){
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}

		//将已支付用户余额等信息记录到订单信息里
		if(!empty($order_pay['balance_pay'])){
			$data_shop_order['balance_pay'] = $order_pay['balance_pay'];
		}
		//扣除积分并保存订单
		if(!empty($order_info['score_deducte'])){
			$data_shop_order['score_used_count']	= $order_info['score_used_count'];
			$data_shop_order['score_deducte']      	= (float)$order_info['score_deducte'];
		}

		if(!empty($data_shop_order)){
			$data_shop_order['score_used_count'] = (int)$order_info['score_used_count'];	//扣除积分
			$data_shop_order['score_deducte'] = (float)$order_info['score_deducte'];
			$data_shop_order['last_time'] = $_SERVER['REQUEST_TIME'];
			$condition_shop_order['order_id'] = $order_info['order_id'];
			if(!$this->where($condition_shop_order)->data($data_shop_order)->save()){
				return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
		}

		if($online_pay){
			return array('error_code' => false, 'pay_money' => $order_info['order_total_money'] - $now_user['now_money']-(float)$order_info['score_deducte']);
		}else{
			$order_param = array(
					'order_id' => $order_info['order_id'],
					'orderid' => $order_info['orderid'],
					'pay_type' => '',
					'third_id' => '',
					'is_mobile' => 0,
					'pay_money' => 0,
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'msg'=>'支付成功！','url'=>U('User/Index/shop_order_view',array('order_id'=>$order_info['order_id'])));
		}
	}

	//手机端支付前订单处理
	public function wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user)
	{
		$pay_money = round($order_info['order_total_money'] * 100) / 100; //本次支付的总金额
		if($merchant_balance['card_discount']>0){
			$pay_money = sprintf("%.2f",($pay_money-$order_info['freight_charge'])*$merchant_balance['card_discount']/10)+$order_info['freight_charge'];
			//$pay_money = floor(($pay_money-$order_info['freight_charge'])*$merchant_balance['card_discount']*10)/100+$order_info['freight_charge'];
		}
		$merchant_balance['card_money'] = round($merchant_balance['card_money'] * 100) / 100;			//用户在商家所拥有的金额
		$merchant_balance['card_give_money'] = round($merchant_balance['card_give_money'] * 100) / 100;			//用户在商家所拥有的金额
		$data_shop_order['card_discount'] = $merchant_balance['card_discount'];
		//判断优惠券
		if(C('config.open_extra_price')==1){
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
			$pay_money +=$order_info['order_extra_price'];
		}
		if($pay_money==0){
			$order_result = $this->wap_pay_save_order($order_info, $data_shop_order);
			if ($order_result['error_code']) {
				return $order_result;
			}
			return $this->wap_after_pay_before($order_info);
		}
		$data_shop_order['card_discount'] = $merchant_balance['card_discount'];
			if ($now_coupon['card_price'] >0) {
				$data_shop_order['card_id'] = $now_coupon['merc_id'];
				$data_shop_order['card_price'] = round($now_coupon['card_price'] * 100)/100;
				if ($now_coupon['card_price'] >= $pay_money) {
					$order_result = $this->wap_pay_save_order($order_info, $data_shop_order);
					if ($order_result['error_code']) {
						return $order_result;
					}
					return $this->wap_after_pay_before($order_info);
				}
				$pay_money -= round($now_coupon['card_price'] * 100)/100;
				$pay_money = round($pay_money * 100)/100;
			}

			if ($now_coupon['coupon_price'] >0) {
				$data_shop_order['coupon_id'] = $now_coupon['sysc_id'];
				$data_shop_order['coupon_price'] = round($now_coupon['coupon_price'] * 100)/100;
				if ($now_coupon['coupon_price'] >= $pay_money) {
					$order_result = $this->wap_pay_save_order($order_info, $data_shop_order);
					if ($order_result['error_code']) {
						return $order_result;
					}
					return $this->wap_after_pay_before($order_info);
				}
				$pay_money -= round($now_coupon['coupon_price'] * 100)/100;
				$pay_money = round($pay_money * 100)/100;
			}

		// 使用积分
        if(!empty($order_info['score_deducte'])&&$order_info['use_score']){
			$data_shop_order['score_used_count']  = $order_info['score_used_count'];
			$data_shop_order['score_deducte']     = (float)$order_info['score_deducte'];
			if($order_info['score_deducte'] >= round($pay_money,2)){
				$order_result = $this->wap_pay_save_order($order_info,$data_shop_order);
				if($order_result['error_code']){
					return $order_result;
				}
				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $order_info['score_deducte'];
			$pay_money = round($pay_money * 100)/100;
		}

		//判断商家余额
		if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
			if ($merchant_balance['card_money'] >= $pay_money) {
				$data_shop_order['merchant_balance'] = $pay_money;
				$order_info['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info, $data_shop_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
// 				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_shop_order['merchant_balance'] = $merchant_balance['card_money'];
			}
			$pay_money -= $merchant_balance['card_money'];
			$pay_money = round($pay_money * 100)/100;
		}

		if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_give_money'] >= $pay_money){
				$data_shop_order['card_give_money'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_shop_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_shop_order['card_give_money'] = $merchant_balance['card_give_money'];
			}
			$pay_money -= $merchant_balance['card_give_money'];
		}

		//判断帐户余额
		if(!empty($now_user['now_money'])&&$order_info['use_balance']){
			$now_user['now_money'] = round($now_user['now_money'] * 100)/100;
			if($now_user['now_money'] >= $pay_money){
				$data_shop_order['balance_pay'] = $pay_money;
				$order_info['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_shop_order);
				if($order_result['error_code']){
					return $order_result;
				}
				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_shop_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
			$pay_money = round($pay_money * 100)/100;
		}
		//在线支付
		$order_result = $this->wap_pay_save_order($order_info, $data_shop_order);
		if($order_result['error_code']){
			return $order_result;
		}
		return array('error_code' => false, 'pay_money' => $pay_money);
	}

	/**
	 * 处理本次使用的余额付款，将余额保存到订单
	 */
	public function wap_pay_save_order($order_info, $data_shop_order)
	{
		$condition_shop_order['order_id'] 		= $order_info['order_id'];
		$data_shop_order['coupon_id'] 			= !empty($data_shop_order['coupon_id']) ? $data_shop_order['coupon_id'] : 0;
		$data_shop_order['card_id'] 			= !empty($data_shop_order['card_id']) ? $data_shop_order['card_id'] : 0;
		$data_shop_order['card_price'] 			= !empty($data_shop_order['card_price']) ? $data_shop_order['card_price'] : 0;
		$data_shop_order['coupon_price'] 		= !empty($data_shop_order['coupon_price']) ? $data_shop_order['coupon_price'] : 0;
		$data_shop_order['merchant_balance'] 	= !empty($data_shop_order['merchant_balance']) ? $data_shop_order['merchant_balance'] : 0;
		$data_shop_order['card_give_money'] 	= !empty($data_shop_order['card_give_money']) ? $data_shop_order['card_give_money'] : 0;
		$data_shop_order['card_discount'] 	= !empty($data_shop_order['card_discount']) ? $data_shop_order['card_discount'] : 0;
		$data_shop_order['balance_pay'] 		= !empty($data_shop_order['balance_pay']) ? $data_shop_order['balance_pay'] : 0;
		$data_shop_order['score_used_count']  	= !empty($data_shop_order['score_used_count'])?$data_shop_order['score_used_count']:0;
		$data_shop_order['score_deducte']     	= !empty($data_shop_order['score_deducte'])?(float)$data_shop_order['score_deducte']:0;
		$data_shop_order['score_discount_type']      = !empty($order_info['score_discount_type']) ? $order_info['score_discount_type'] : 0;
		$data_shop_order['last_time'] 			= $_SERVER['REQUEST_TIME'];
		$data_shop_order['submit_order_time'] 	= $_SERVER['REQUEST_TIME'];
		if ($this->where($condition_shop_order)->data($data_shop_order)->save()) {
			return array('error_code' => false, 'msg' => '保存订单成功！');
		} else {
			return array('error_code' => true, 'msg' => '保存订单失败！请重试或联系管理员。');
		}
	}


	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info)
	{
		$order_param = array(
				'order_id' => $order_info['order_id'],
				'orderid' => $order_info['orderid'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => $order_info['is_mobile'],
				'pay_money' => 0,
				'order_total_money' => $order_info['order_total_money'],
				'balance_pay' => $order_info['balance_pay'],
				'merchant_balance' => $order_info['merchant_balance'],
				'order_type' => 'shop',
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code' => true,'msg'=>$result_after_pay['msg']);
			}
			if ($order_info['order_from'] == 1) {
				return array('error_code'=>false,'msg'=>'支付成功！','url' => C('config.site_url') . "/wap.php?g=Wap&c=Mall&a=status&order_id=" . $order_info['order_id'] . '&mer_id=' . $order_info['mer_id'] . '&store_id=' . $order_info['store_id']);
			} else {
				return array('error_code'=>false,'msg'=>'支付成功！','url' => C('config.site_url') . "/wap.php?g=Wap&c=Shop&a=status&order_id=" . $order_info['order_id'] . '&mer_id=' . $order_info['mer_id'] . '&store_id=' . $order_info['store_id']);
			}
	}


	public function after_pay($order_param)
	{
		if ($order_param['pay_type'] != '') {
			$where['orderid'] = $order_param['order_id'];
		} else {
			$where['order_id'] = $order_param['order_id'];
		}

		$now_order = $this->field(true)->where($where)->find();
		if (empty($now_order)) {
			return array('error' => 1, 'msg' => '当前订单不存在！');
		} elseif ($now_order['paid'] == 1) {
			if ($order_param['is_mobile']) {
				if ($order_param['order_from'] == 1) {
					return array('error' => 1, 'msg' => '该订单已付款！', 'url' => U('Wap/Mall/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				} else {
					return array('error' => 1, 'msg' => '该订单已付款！', 'url' => U('Wap/Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				}
			} else {
				return array('error' => 1, 'msg' => '该订单已付款！', 'url' => U('User/Index/shop_order_view', array('order_id' => $now_order['order_id'])));
			}
		} else {
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}

			//判断优惠券是否正确
//			$card_price = 0;
//			if($now_order['card_id']){
//				$now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($now_order['card_id'],$now_order['uid']);
//				$card_price = $now_coupon['price'];
//				if(empty($now_coupon)){
//					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
//				}
//			}
			$card_price = 0;
			if($now_order['card_id']){
				$now_coupon = D('Card_new_coupon')->get_coupon_by_id($now_order['card_id']);
				$card_price = $now_coupon['price'];
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			//判断平台优惠券
			if($now_order['coupon_id']){
				$now_coupon = D('System_coupon')->get_coupon_by_id($now_order['coupon_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			//判断会员卡余额
			$merchant_balance = floatval($now_order['merchant_balance']);
			if($merchant_balance){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance['card_money'] < $merchant_balance){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}

			$card_give_money = floatval($now_order['card_give_money']);
			if($card_give_money){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance['card_money_give'] < $card_give_money){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}
			//判断帐户余额
			$balance_pay = floatval($now_order['balance_pay']);
			if($balance_pay){
				if($now_user['now_money'] < $balance_pay){
					return $this->wap_after_pay_error($now_order,$order_param,'您的帐户余额不够此次支付！');
				}
			}

			//如果使用了商家优惠券
//			if($now_order['card_id']){
//				$use_result = D('Member_card_coupon')->user_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);
//				if($use_result['error_code']){
//					return array('error'=>1,'msg'=>$use_result['msg']);
//				}
//			}

			if($now_order['card_id']){
				$use_result = D('Card_new_coupon')->user_coupon($now_order['card_id'],$now_order['order_id'],$order_param['order_type'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}


			//如果使用了平台优惠券
			if($now_order['coupon_id']){
				$use_result = D('System_coupon')->user_coupon($now_order['coupon_id'],$now_order['order_id'],$order_param['order_type'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//如果用户使用了积分抵扣，则扣除相应的积分
			//判断积分数量是否正确
			$score_used_count = $now_order['score_used_count'];
			if($score_used_count && $now_user['score_count'] < $score_used_count){
				return array('error' => 1, 'msg' => '保存订单失败！请重试或联系管理员。');
			}

			if ($score_used_count>0) {
			    $use_result = D('User')->user_score($now_order['uid'], $score_used_count, '购买' . C('config.shop_alias_name') . '商品 ,扣除' . C('config.score_name') . ' ,订单编号' . $now_order['real_orderid']);
				if ($use_result['error_code']) {
					return array('error' => 1, 'msg' => $use_result['msg']);
				}
			}

			//如果使用会员卡余额
//			if($merchant_balance){
//				$use_result = D('Card_new')->use_money($now_order['uid'],$now_order['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
//				if($use_result['error_code']){
//					return array('error'=>1,'msg'=>$use_result['msg']);
//				}
//			}

			if($merchant_balance){
			    $use_result = D('Card_new')->use_money($now_order['uid'],$now_order['mer_id'],$merchant_balance,'购买' . C('config.shop_alias_name') . '商品,扣除会员卡余额 ,订单编号' . $now_order['real_orderid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			if($card_give_money){
			    $use_result = D('Card_new')->use_give_money($now_order['uid'],$now_order['mer_id'],$card_give_money,'购买' . C('config.shop_alias_name') . '商品,扣除会员卡赠送余额 ,订单编号' . $now_order['real_orderid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}


			//如果用户使用了余额支付，则扣除相应的金额。
			if (!empty($balance_pay)) {
				$use_result = D('User')->user_money($now_order['uid'], $balance_pay, '购买' . C('config.shop_alias_name') . '商品,扣除余额,订单编号' . $now_order['real_orderid']);
				if ($use_result['error_code']) {
					return array('error' => 1, 'msg' => $use_result['msg']);
				}
			}

			$data_shop_order = array();
			$data_shop_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_shop_order['payment_money'] = floatval($order_param['pay_money']);//在线支付的钱
			$data_shop_order['pay_type'] = $order_param['pay_type'];
			$data_shop_order['third_id'] = $order_param['third_id'];
			if (($now_order['order_from'] == 2 || $now_order['order_from'] == 3) && empty($order_param['pay_type'])) {
			    $data_shop_order['is_mobile_pay'] = 2;
			} else {
			    $data_shop_order['is_mobile_pay'] = $order_param['is_mobile'];
			}
			$data_shop_order['is_own'] = isset($order_param['sub_mch_id'])?2:$order_param['is_own'];
			$data_shop_order['paid'] = 1;
			$data_shop_order['price'] = $now_order['price'];
			if ($now_order['card_discount']) {
				$data_shop_order['price'] = sprintf("%.2f", ($now_order['price']-$now_order['freight_charge']) * $now_order['card_discount']/10)+$now_order['freight_charge'];//floor($now_order['price'] * $now_order['card_discount'] * 10) / 100;
			}

		
            //会员卡折的金额
			$discountPrice = $now_order['coupon_price'] + $now_order['card_price'];
// 			$discountPrice = $now_order['price'] - $data_shop_order['price'] + $now_order['coupon_price'] + $now_order['card_price'];
			
			if($this->where($where)->save($data_shop_order)){
                if ($now_order['plat_discount'] > 0) {
                    D('Merchant_discount_log')->saveLog($now_order, 0);
                }
			    D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 1));
			    D("Merchant_store_shop")->where(array('store_id' => $now_order['store_id']))->setInc('sale_count', 1);
			    
			    
			    /*****************20170518增加取单号**********************/
			    if ($now_order['order_from'] != 6 && $now_order['is_pick_in_store'] < 2) {
			        $nowDay = date('Ymd');
			        if ($shop = D('Merchant_store_shop')->field('fetch_day, fetch_number')->where(array('store_id' => $now_order['store_id']))->find()) {
			            if ($shop['fetch_day'] == $nowDay) {
			                $fetch_number = $shop['fetch_number'] + 1;
			                D('Merchant_store_shop')->where(array('store_id' => $now_order['store_id']))->setInc('fetch_number');
			            } else {
			                $fetch_number = 1;
			                D('Merchant_store_shop')->where(array('store_id' => $now_order['store_id']))->save(array('fetch_number' => 1, 'fetch_day' => $nowDay));
			            }
			        } else {
			            $fetch_number = 0;
			        }
			        $this->where($where)->save(array('fetch_number' => $fetch_number));
			    }
			    /*****************20170518增加取单号**********************/
			    //-----------------------add auto order 2017-03-06--------------------------------------
			    if ($now_order['is_pick_in_store'] != 2 && $now_order['is_pick_in_store'] != 3) {
			        $storeShop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
			        if ($storeShop && $storeShop['is_auto_order']) {
			            $store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
			            $result = D('Deliver_supply')->saveOrder($now_order['order_id'], $store);
			            if ($result['error_code']) {
			                $this->where(array('order_id' => $now_order['order_id']))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
			                return array('error' => 1, 'msg' => $result['msg']);
			            }
			            $this->where(array('order_id' => $now_order['order_id']))->save(array('status' => 1, 'order_status' => 1, 'last_time' => time()));
			            D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 2, 'name' => '自动接单', 'phone' => '', 'note' => '店铺设置下单后自动接单'));
			        }
			    }
			    //-----------------------add auto order 2017-03-06--------------------------------------
			    
			    if ($now_order['cartid'] && ($orderTemp = D('Shop_order_temp')->field(true)->where(array('cartid' => $now_order['cartid']))->find())) {
			        $tempPrice = $data_shop_order['price'] - $now_order['card_price'] - $now_order['coupon_price'];
			        D('Shop_order_temp')->where(array('cartid' => $now_order['cartid']))->save(array('price' => floatval($tempPrice)));
			    }
				D('Scroll_msg')->add_msg('shop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.shop_alias_name').'成功');


				$spread_total_money = $balance_pay + $data_shop_order['payment_money']-$now_order['freight_charge'];
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				if(!empty($now_user['openid'])&&$now_order['pay_type']!='offline' && C('config.open_user_spread')&& (C('config.spread_money_limit')==0 || C('config.spread_money_limit')<=$spread_total_money)){
					$now_store_shop = M('Merchant_store_shop')->join('as store_shop left join '.C('DB_PREFIX').'merchant_store store ON store_shop.store_id = store.store_id')->where(array('store_shop.store_id'=>$now_order['store_id']))->find();
					if($data_shop_order['is_own'] && C('config.own_pay_spread')==0){
						$data_shop_order['payment_money']=0;
					}
					$open_extra_price = C('config.open_extra_price');
					//上级分享佣金
					$spread_users[]  = $now_user['uid'];

					if($now_user['wxapp_openid']!=''){
						$spread_where['_string'] = "openid = '{$now_user['openid']}' OR openid = '{$now_user['wxapp_openid']}' ";
					}else{
						$spread_where['_string'] = "openid = '{$now_user['openid']}'";
					}


					$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where)->find();

					$spread_rate     = D('Percent_rate')->get_user_spread_rate($now_order['mer_id'], 'shop');
					$href            = C('config.site_url') . '/wap.php?g=Wap&c=My&a=spread_list&status=-1';
					if(!empty($now_user_spread)){
						if($now_user_spread['is_wxapp']){
							$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'wxapp_openid');
						}else{
							$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
						}
//						$user_spread_rate = $now_store_shop['spread_rate'] > 0 ? $now_store_shop['spread_rate'] : C('config.user_spread_rate');
						$user_spread_rate = $spread_rate['first_rate'];
						if($now_order['is_pick_in_store']!=0){
							$now_order['freight_charge']=0;
						}
						if($spread_user && $user_spread_rate&&!in_array($spread_user['uid'],$spread_users)){
							$spread_money = round(($balance_pay + $data_shop_order['payment_money']-$now_order['freight_charge']) * $user_spread_rate / 100, 2);

							$spread_data = array('uid'=>$spread_user['uid'],'spread_uid'=>0,'get_uid'=>$now_user['uid'],'money'=>$spread_money,'order_type'=>'shop','order_id'=>$now_order['order_id'],'third_id'=>$now_order['store_id'],'add_time'=>$_SERVER['REQUEST_TIME']);
							if($spread_user['spread_change_uid']!=0){
								$spread_data['change_uid'] = $spread_user['spread_change_uid'];
							}

							$buy_user = D('User')->get_user($now_user_spread['openid'],'openid');
							if($spread_money>0){
								D('User_spread_list')->data($spread_data)->add();
								$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $spread_user['openid'], 'first' => $buy_user['nickname'] . '通过您的分享购买了【' . $now_store_shop['name'] . '】的'.C('config.shop_alias_name').'商品，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
							}
							$spread_users[]=$spread_user['uid'];
							// D('User')->add_money($spread_user['uid'],$spread_money,'推广用户 '.$now_user['nickname'].' 购买 ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
						}

						//第二级分享佣金
						$spread_where['_string'] = "openid = '{$spread_user['openid']}' OR openid = '{$spread_user['wxapp_openid']}' ";
						$second_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where )->find();

						if(!empty($second_user_spread)&&!$open_extra_price) {
							if($second_user_spread['is_wxapp']){
								$second_user = D('User')->get_user($second_user_spread['spread_openid'], 'wxapp_openid');
							}else{
								$second_user = D('User')->get_user($second_user_spread['spread_openid'], 'openid');
							}
//							$sub_user_spread_rate = $now_store_shop['sub_spread_rate'] > 0 ? $now_store_shop['sub_spread_rate'] : C('config.user_first_spread_rate');
							$sub_user_spread_rate = $spread_rate['second_rate'];
							if ($second_user && $sub_user_spread_rate&&!in_array($second_user['uid'],$spread_users)) {
								$spread_money = round(($balance_pay + $data_shop_order['payment_money']-$now_order['freight_charge']) * $sub_user_spread_rate / 100, 2);
								$spread_data = array('uid' => $second_user['uid'], 'spread_uid' => $spread_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => 'shop', 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
								if($spread_user['spread_change_uid']!=0){
									$spread_data['change_uid'] = 	$second_user['spread_change_uid'];
								}
								$sec_user = D('User')->get_user($second_user_spread['openid'], 'openid');
								if($spread_money>0) {
									D('User_spread_list')->data($spread_data)->add();
									if($open_extra_price){
										$money_name = C('config.extra_price_alias_name');
									}else{
										$money_name = '佣金';
									}
									$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $second_user['openid'], 'first' => $sec_user['nickname'] .'的子用户'.$buy_user['nickname']. '通过分享购买了【' . $now_store_shop['name']. '】的'.C('config.shop_alias_name').'商品，验证消费后您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
								}
								$spread_users[]=$second_user['uid'];
								// D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
							}

							//顶级分享佣金
							$spread_where['_string'] = "openid = '{$second_user['openid']}' OR openid = '{$second_user['wxapp_openid']}' ";
							$first_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where(	$spread_where)->find();

							if (!empty($first_user_spread) && C('config.user_third_level_spread')&&!$open_extra_price) {
								if($first_user_spread['is_wxapp']){
									$first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'wxapp_openid');
								}else{
									$first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'openid');
								}
//								$sub_user_spread_rate = $now_store_shop['third_spread_rate'] > 0 ? $now_store_shop['third_spread_rate'] : C('config.user_second_spread_rate');
								$sub_user_spread_rate = $spread_rate['third_rate'];
								if ($first_spread_user && $sub_user_spread_rate&&!in_array($first_spread_user['uid'],$spread_users)) {
									$spread_money = round(($balance_pay + $data_shop_order['payment_money']-$now_order['freight_charge']) * $sub_user_spread_rate / 100, 2);
									$spread_data = array('uid' => $first_spread_user['uid'], 'spread_uid' => $second_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => 'shop', 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
									if($spread_user['spread_change_uid']!=0){
										$spread_data['change_uid'] = 	$first_spread_user['spread_change_uid'];
									}

									$fir_user = D('User')->get_user($first_user_spread['openid'], 'openid');
									if($spread_money>0) {
										D('User_spread_list')->data($spread_data)->add();
										$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $first_spread_user['openid'], 'first' => $fir_user['nickname'].'的子用户的子用户'.$buy_user['nickname'] . '通过您的分享购买了【' . $now_store_shop['name'] . '】的'.C('config.shop_alias_name').'商品，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
									}
									// D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
								}
							}
						}
					}
				}

				//增加粉丝 5 group 6 shop 7 meal 8 appoint 9 store
				D('Merchant')->saverelation($now_user['openid'], $now_order['mer_id'], 6);


				$goodsDetailDB = D('Shop_order_detail');
				$goods = $goodsDetailDB->field(true)->where(array('order_id' => $now_order['order_id']))->select();
				$keyword2 = '';
				$pre = '';
				$goods_obj = D("Shop_goods");
				$i = 1;
				foreach ($goods as $gr) {
					if (empty($now_order['reduce_stock_type']) || $now_order['order_from'] == 6) {
						$goods_obj->update_stock($gr);//修改库存
					}
					if ($i < 5) {
    					$keyword2 .= $pre . $gr['name'] . ':' . $gr['price'] . '*' . $gr['num'];
    					$pre = '\n\t\t\t';
					} elseif ($i == 5) {
					    $keyword2 .= $pre . '...';
					}
					$i++;
					if ($discountPrice > 0 || ($now_order['card_discount'] != 10 && $now_order['card_discount'] != 0)) {
				        $goodsDetailDB->where(array('id' => $gr['id']))->save(array('pay_price' => floatval(round($gr['pay_price'] * ($now_order['card_discount']/10) * (1 - $discountPrice / $data_shop_order['price']), 2))));
					}
				}

				//微信模板消息提醒
				if ($now_user['openid'] && $order_param['is_mobile']) {
					$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $now_order['order_id'] . '&mer_id=' . $now_order['mer_id'] . '&store_id=' . $now_order['store_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					if ($order_param['pay_type'] == 'offline' && empty($order_param['third_id'])) {
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.shop_alias_name').'提醒', 'keyword2' => $now_order['real_orderid'], 'keyword1' => $keyword2, 'keyword3' => $now_order['price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '购买成功（线下未支付）！'), $now_order['mer_id']);
					} else {
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.shop_alias_name').'提醒', 'keyword2' => $now_order['real_orderid'], 'keyword1' => $keyword2, 'keyword3' => $now_order['price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '购买成功!'), $now_order['mer_id']);
					}
				}

				$offline = '';
				if ($order_param['pay_type'] == 'offline' && empty($order_param['third_id'])) {
					$offline = '(线下未支付)';
				}

				//微信派发优惠券 支付到平台 微信支付
				if($data_shop_order['is_own']==0 &&  $data_shop_order['pay_type']=='weixin' && $data_shop_order['payment_money']>=C('config.weixin_send_money')){
					D('System_coupon')->weixin_send( $data_shop_order['payment_money'],$now_order['uid']);
				}

				//小票打印


				
// 				$msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
// 				$op = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
// 				$op->printit($now_order['mer_id'], $now_order['store_id'], $msg, 1);

//                 $str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
// 				foreach ($str_format as $print_id => $print_msg) {
// 					$print_id && $op->printit($now_order['mer_id'], $now_order['store_id'], $print_msg, 1, $print_id);
// 				}
// 				$str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order', 1);
// 				foreach ($str_format as $print_id => $print_msg) {
// 					$print_id && $op->printit($now_order['mer_id'], $now_order['store_id'], $print_msg, 1, $print_id);
// 				}

// 				$msg = ArrayToStr::printOnlyOne($now_order['order_id'], 'shop_order');
// 				$op->printit($now_order['mer_id'], $now_order['store_id'], $msg, 1);
				
				//短信提醒
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['mer_id'], 'type' => 'shop');
				$store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
				if (C('config.sms_shop_success_order') == 1 || C('config.sms_shop_success_order') == 3) {
					if (empty($now_order['phone'])) {
						$user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
						$now_order['phone'] = $user['phone'];
					}
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['userphone'];
					$sms_data['sendto'] = 'user';
					if ($data_shop_order['shop_pass']) {
						$sms_data['content'] = '您在' . $store['name'] . '中的订单号：' . $now_order['real_orderid'] . ',已经完成了支付' . $offline . '！';
					} else {
						$sms_data['content'] = '您在' . $store['name'] . '中的订单号：' . $now_order['real_orderid'] . ',已经完成了支付' . $offline . '！';
					}
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_shop_success_order') == 2 || C('config.sms_shop_success_order') == 3) {
					$store_phpne = explode(' ',$store['phone']);
					if(count($store_phpne)>0){
						foreach($store_phpne as $phone){
							if(preg_match('/^[0-9]{11}$/',$phone)){
								$store['phone'] = $phone;
							}
						}
					}
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $store['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客' . $now_order['username'] . '在' . date("Y-m-d H:i:s") . '时，将订单号：' . $now_order['real_orderid'] . '支付成功' . $offline . '！';
					Sms::sendSms($sms_data);
				}

				if (($now_order['order_from'] == 6 && $now_order['is_pick_in_store'] == 2 && $now_order['staff_id'] > 0) || $now_order['order_from'] == 9) {
					$finish_data = array('order_status' => 5, 'status' => 2, 'use_time' => time());
					if ($this->where($where)->save($finish_data)) {
					    $now_order = array_merge($now_order, $data_shop_order);
						$this->shop_notice($now_order);
						D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 15, 'name' => '', 'phone' => ''));
						D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 6, 'name' => '', 'phone' => ''));
					}
				} else {
				    $printHaddle = new PrintHaddle();
				    $printHaddle->printit($now_order['order_id'], 'shop_order', 1);
				}
				
				//-----------------------add auto order 2017-03-06--------------------------------------
//                 if ($now_order['is_pick_in_store'] != 2 && $now_order['is_pick_in_store'] != 3) {
//                     $storeShop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
//                     if ($storeShop && $storeShop['is_auto_order']) {
//                         $store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
//                         $result = D('Deliver_supply')->saveOrder($now_order['order_id'], $store);
//                         if ($result['error_code']) {
//                             D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
//                             return array('error' => 1, 'msg' => $result['msg']);
//                         }
//                         $this->where(array('order_id' => $now_order['order_id']))->save(array('status' => 1, 'order_status' => 1, 'last_time' => time()));
//                         D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 2, 'name' => '自动接单', 'phone' => '', 'note' => '店铺设置下单后自动接单'));
//                     }
//                 }
                //-----------------------add auto order 2017-03-06--------------------------------------
				
				/* 粉丝行为分析 */
// 				D('Merchant_request')->add_request($now_order['mer_id'], array('meal_buy_count' => $now_order['num'], 'meal_buy_money' => $now_order['price']));
				if ($order_param['is_mobile']) {
					if ($now_order['order_from'] == 1) {
						return array('error' => 0, 'url' => U('Wap/Mall/status', array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'], 'order_id' => $now_order['order_id'])));
					} else {
						return array('error' => 0, 'url' => U('Wap/Shop/status', array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'], 'order_id' => $now_order['order_id'])));
					}
				} else {
					return array('error' => 0, 'url' => U('User/Index/shop_order_view', array('order_id'=>$now_order['order_id'])));
				}
			} else {
				return array('error' => 1, 'msg' => '修改订单状态失败，请联系系统管理员！');
			}
		}
	}

	//修改订单状态
	public function change_status($order_id, $status)
	{
		$where['order_id'] = $order_id;
		$data['status'] = $status;
		if ($this->where($where)->data($data)->save()) {
			return true;
		} else {
			return false;
		}
	}


	//支付时，金额不够，记录到帐号
	public function wap_after_pay_error($now_order,$order_param,$error_tips)
	{
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if ($user_result['error_code']) {
			return array('error'=>1,'msg'=>$user_result['msg']);
		} else {
			if ($order_param['is_mobile']) {
				$return_url = str_replace('/source/', '/', U('My/shop_order',array('order_id' => $now_order['order_id'])));
			} else {
				$return_url = U('User/Index/shop_order_view', array('order_id' => $now_order['order_id']));
			}
			return array('error' => 1, 'msg' => $error_tips . '已将您充值的金额添加到您的余额内。', 'url' => $return_url);
		}
	}

	public function get_order_list($where = array(), $order = 'pay_time DESC', $is_wap = false, $is_user = true)
	{
		if (isset($where['status']) && $where['status'] === 0) $where['paid'] = 1;
		if (is_array($where) && $is_user) $where['is_del'] = 0;
		if($is_wap != 10){
			$count = $this->where($where)->count();
		}
		if ($is_wap == 4) {
	        import('@.ORG.wap_group_page');
	        $p = new Page($count, 20, 'p');
		} elseif ($is_wap == 3) {
			import('@.ORG.system_page');
			$p = new Page($count, 20);
		} elseif ($is_wap == 2) {
			import('@.ORG.merchant_page');
			$p = new Page($count, 20);
		} elseif($is_wap == 10){
			$list = $this->where($where)->order($order)->page(1,10)->select();
		}  elseif($is_wap == 11){
	        import('@.ORG.wap_group_page');
	        $p = new Page($count, 20, 'p');
			$list = $this->where($where)->order($order)->select();
		} elseif ($is_wap) {
			import('@.ORG.wap_group_page');
			$p = new Page($count, 20, 'p');
		} else {
			import('@.ORG.user_page');
			$p = new Page($count, 10);
		}
		if($is_wap != 11 && $is_wap != 10){
			$list = $this->where($where)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();
		}
		$notOffline = 1;
		$pay_offline_open = C('config.pay_offline_open');
		if ($pay_offline_open == 1) {
			$now_merchant = D('Merchant')->get_info($mer_id);
			if ($now_merchant) {
				$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
			}
		}
		foreach ($list as &$order) {
			$order['offline_price'] = round($order['price'] +$order['extra_price'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
			$order['deliver_info'] = $order['deliver_info'] ? unserialize($order['deliver_info']) : '';
			switch ($order['status']) {
				case 0:
					$order['status_str'] = '<b style="color:red">未确认</b>';
					break;
				case 1:
					$order['status_str'] = '<b style="color:green">已确认</b>';
					break;
				case 2:
					$order['status_str'] = '<b style="color:green">已消费</b>';
					break;
				case 3:
					$order['status_str'] = '<b style="color:green">已评价</b>';
					break;
				case 4:
					$order['status_str'] = '<del style="color:gray">已退款</del>';
					break;
				case 5:
					$order['status_str'] = '<del style="color:gray">已取消</del>';
					break;
				case 7:
					$order['status_str'] = '<b style="color:green">分配到自提点</b>';
					break;
				case 8:
					$order['status_str'] = '<b style="color:green">发货到自提点</b>';
					break;
				case 9:
					$order['status_str'] = '<b style="color:green">自提点接货</b>';
					break;
				case 10:
					$order['status_str'] = '<del style="color:green">自提点发货</del>';
					break;
			}

			if ($order['paid'] == 0) {
				$order['pay_status'] = '未支付';
			} elseif ($order['paid'] == 1) {
				if ($order['pay_type'] == 'offline') {
					if ($order['third_id']) {
						$order['pay_status'] = '<b style="color:green">已支付</b>';
					} else {
						$order['pay_status'] = '<b style="color:red">未支付</b>';
					}
				} else {
					$order['pay_status'] = '<b style="color:green">已支付</b>';
				}
			}
			if ($order['is_pick_in_store'] == 0) {
			    $order['deliver_str'] = C('config.deliver_name');
			    $order['deliverinfo'] = C('config.deliver_name');
				// if ($order['deliver_info']) {
					// $order['deliverinfo'] .= '<br/>配送员姓名：' . $order['deliver_info']['name'] . '<br/>配送员电话：' . $order['deliver_info']['phone'];
				// }
			} elseif ($order['is_pick_in_store'] == 1) {
				$order['deliver_str'] = '商家配送';
				$order['deliverinfo'] = '商家配送';
				// if ($order['deliver_info']) {
					// $order['deliverinfo'] .= '<br/>配送员姓名：' . $order['deliver_info']['name'] . '<br/>配送员电话：' . $order['deliver_info']['phone'];
				// }
			} elseif ($order['is_pick_in_store'] == 2) {
				$order['deliver_str'] = '自提';
				$order['deliverinfo'] = '自提';
			} elseif ($order['is_pick_in_store'] == 3) {
				$order['deliver_str'] = '快递配送';
				$order['deliverinfo'] = '快递配送';
			}
// 			if ($order['is_pick_in_store'] == 2) {
// 				$order['deliver_str'] = '自提';
// 				$order['deliverinfo'] = '自提';
// 			} else {
// 				$order['deliver_str'] = '配送';
// 				$order['deliverinfo'] = '配送';
// 				if ($order['deliver_info']) {
// 					$order['deliverinfo'] .= '<br/>配送员：' . $order['deliver_info']['name'] . '<br/>配送员电话：' . $order['deliver_info']['phone'];
// 				}
// 			}

			//配送状态（0：订单生产，1:店员接单，2：配送员接单，3：配送员取货，4：送达，5：确认收货，6，配送结束）
			switch ($order['order_status']) {
				case 0:
					if ($order['is_pick_in_store'] == 2) {
						$order['deliver_status_str'] = '待提货';
					} elseif ($order['is_pick_in_store'] == 3) {
					    if ($order['status'] < 1) {
					        $order['deliver_status_str'] = '待发货';
					    } elseif ($order['status'] == 4) {
					        $order['deliver_status_str'] = '已退款';
					    } elseif ($order['status'] == 5) {
					        $order['deliver_status_str'] = '已取消';
					    }else {
					        $order['deliver_status_str'] = '已发货';
					    }
					} else {
						$order['deliver_status_str'] = '待发货';
					}
					break;
				case 1:
					$order['deliver_status_str'] = '店铺已接单';
					break;
				case 2:
					$order['deliver_status_str'] = '配送员接单';
					break;
				case 3:
					$order['deliver_status_str'] = '配送员取货';
					break;
				case 4:
					$order['deliver_status_str'] = '配送中';
					break;
				case 5:
					$order['deliver_status_str'] = '确认收货';
					break;
				case 6:
					if ($order['is_pick_in_store'] == 2) {
						$order['deliver_status_str'] = '已提货';
					} else {
						$order['deliver_status_str'] = '配送完成';
					}
					break;

			}
			$order['pay_type_str'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);

			if($order['from_plat']==3){
				$order['pay_type_str'].=' 扫用户码 ';
			}
		}
		if($is_wap != 10){
			return array('order_list' => $list, 'pagebar' => $p->show(), 'page' => ceil($count/10),'count' => $count);
		}else{
			return array('order_list' => $list, 'page' => ceil($count/10),'count' => $count);
		}
	}

	public function get_order_detail($where)
	{
		//$where['is_del'] = 0;
		$order = $this->field(true)->where($where)->find();
		if (empty($order)) return false;
		$order['info'] = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
		$order['cue_field'] = isset($order['cue_field']) && $order['cue_field'] ? unserialize($order['cue_field']) : '';
		if(empty($order['cue_field'])){
			$order['cue_field'] = array();
		}
		if(empty($order['cue_field'])){
			$order['cue_field'] = '';
		}
		if ($order['discount_detail'] && @unserialize($order['discount_detail'])) {
		    $order['discount_detail'] = unserialize($order['discount_detail']);
		} else {
		    $order['discount_detail'] = '';
		}
		$order['date'] = date('Y-m-d H:i:s', $order['create_time']);
		$order['offline_price'] = round($order['price'] +$order['extra_price'] - round($order['card_price'] + $order['card_give_money'] +$order['merchant_balance'] + $order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
		switch ($order['status']) {
			case 0:
				$order['css'] = 'inhand';
				$order['show_status'] = '处理中';
				$order['status_str'] = '未确认';
				break;
			case 1:
				$order['css'] = 'confirm';
				$order['show_status'] = '已确认';
				$order['status_str'] = '已确认';
				break;
			case 2:
				$order['css'] = 'confirm';
				$order['show_status'] = '已消费';
				$order['status_str'] = '已消费';
				break;
			case 3:
				$order['css'] = 'complete';
				$order['show_status'] = '已评价';
				$order['status_str'] = '已评价';
				break;
			case 4:
				$order['css'] = 'cancle';
				$order['show_status'] = '已退款';
				$order['status_str'] = '已退款';
				$order['refund_detail'] = unserialize($order['refund_detail']);
				$order['last_time'] = isset($order['refund_detail']['refund_time']) ? $order['refund_detail']['refund_time'] : $order['last_time'];
				break;
			case 5:
				$order['css'] = 'cancle';
				$order['show_status'] = '已取消';
				$order['status_str'] = '已取消';
				break;
			case 7:
				$order['css'] = 'confirm';
				$order['show_status'] = '分配到自提点';
				$order['status_str'] = '分配到自提点';
				break;
			case 8:
				$order['css'] = 'confirm';
				$order['show_status'] = '发货到自提点';
				$order['status_str'] = '发货到自提点';
				break;
			case 9:
				$order['css'] = 'confirm';
				$order['show_status'] = '自提点接货';
				$order['status_str'] = '自提点接货';
				break;
			case 10:
				$order['css'] = 'confirm';
				$order['show_status'] = '自提点发货';
				$order['status_str'] = '自提点发货';
				break;
		}

		if ($order['paid'] == 0) {
			$order['pay_status'] = '<b style="color:red">未支付</b>';
			$order['pay_status_print'] = '未支付';
		} elseif ($order['paid'] == 1) {
			if ($order['pay_type'] == 'offline') {
				if ($order['third_id']) {
					$order['pay_status'] = '<b style="color:green">已支付</b>';
					$order['pay_status_print'] = '已支付';
				} else {
					$order['pay_status'] = '<b style="color:red">未支付</b>';
					$order['pay_status_print'] = '未支付';
				}
			} else {
				$order['pay_status'] = '<b style="color:green">已支付</b>';
				$order['pay_status_print'] = '已支付';
			}
		}
		if ($order['is_pick_in_store'] == 0 || $order['is_pick_in_store'] == 5) {
		    $order['deliver_str'] = C('config.deliver_name');
		} elseif ($order['is_pick_in_store'] == 1) {
			$order['deliver_str'] = '商家配送';
		} elseif ($order['is_pick_in_store'] == 2) {
			$order['deliver_str'] = '自提';
		} elseif ($order['is_pick_in_store'] == 3) {
			$order['deliver_str'] = '快递配送';
		}

		
		$order['deliver_log_list'] = '';
		if ($order['is_pick_in_store'] < 2) {
		    $order['deliver_log_list'] = D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id'], 'status' => array(array('gt', 1), array('lt', 7))))->order('status ASC')->select();
		    foreach ($order['deliver_log_list'] as &$tv) {
		        $tv['dateline'] = date('Y-m-d H:i:s', $tv['dateline']);
		    }
		}
		$order['deliver_log_list'] = $order['deliver_log_list'] ?:'';
		//配送状态（0：订单生产，1:店员接单，2：配送员接单，3：配送员取货，4：送达，5：确认收货，6，配送结束）
		switch ($order['order_status']) {
			case 0:
				if ($order['is_pick_in_store'] == 2) {
					$order['deliver_status_str'] = '待提货';
				} elseif ($order['is_pick_in_store'] == 3) {
				    if ($order['status'] < 1) {
				        $order['deliver_status_str'] = '待发货';
				    } else {
				        $order['deliver_status_str'] = '已发货';
				    }
				} else {
					$order['deliver_status_str'] = '待发货';
				}
				break;
			case 1:
				$order['deliver_status_str'] = '店铺已接单';
				break;
			case 2:
				$order['deliver_status_str'] = '配送员接单';
				break;
			case 3:
				$order['deliver_status_str'] = '配送员取货';
				break;
			case 4:
				$order['deliver_status_str'] = '配送中';
				break;
			case 5:
				$order['deliver_status_str'] = '确认收货';
				break;
			case 6:
				if ($order['is_pick_in_store'] == 2) {
					$order['deliver_status_str'] = '已提货';
				} else {
					$order['deliver_status_str'] = '配送完成';
				}
				break;

		}
		
		if ($order['order_from'] == 6) {
		    $order['deliver_str'] = '';
		    $order['deliver_status_str'] = '';
		}
		
		

		if ($order['is_pick_in_store'] == 3) {
			$express = D('Express')->get_express($order['express_id']);
			$order['express_name'] = isset($express['name']) ? $express['name'] : '';
			$order['express_code'] = $express['code'];
		}
		$order['pay_type_str'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);
		if($order['pay_type_str']!='' && $order['is_own']>0){
			$now_merchant = D('Merchant')->get_info($order['mer_id']);
			if($order['is_own']==2){
				$order['pay_type_str'] .= '(平台子商家：'.$now_merchant['name'].')';
			}else{
				$order['pay_type_str'] .= '(商家：'.$now_merchant['name'].')';
			}
		}
		
		if ($order['offline_pay']) {
		    $storePay = M('Store_pay')->field(true)->where(array('id' => $order['offline_pay'], 'store_id' => $order['store_id']))->find();
		    $order['pay_type_str'] = isset($storePay['name']) ? $storePay['name'] : $order['pay_type_str'];
		}
		$sql = "SELECT u.name, u.phone FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "deliver_user AS u ON u.uid=s.uid WHERE s.order_id={$order['order_id']} AND s.item=2";
		$res = $this->query($sql);
		$res = isset($res[0]) && $res[0] ? $res[0] : '';
		$order['deliver_user_info'] = $res;
		$order['register_phone'] = '';
		if ($user = D('User')->field(true)->where(array('uid' => $order['uid']))->find()) {
			$order['register_phone'] = $user['phone'];
		}
		return $order;
	}

	public function get_rate_order_list($uid,$is_rate=false,$is_wap=false)
	{
		$condition_where = "`o`.`uid`='$uid' AND `o`.`store_id`=`s`.`store_id`";
		if($is_rate){
			$condition_where .= " AND `o`.`paid`='1'";
			$condition_where .= " AND `o`.`status`='3'";
			$condition_where .= " AND `r`.`order_type`='3' AND `r`.`order_id`=`o`.`order_id`";
			$condition_table = array(C('DB_PREFIX').'merchant_store' => 's', C('DB_PREFIX').'shop_order' => 'o', C('DB_PREFIX').'reply' => 'r');
			$condition_field = '`o`.*,`s`.`name`,`s`.`pic_info`,`r`.*';
			$condition_order = '`r`.`pigcms_id` DESC';
		}else{
			$condition_where .= " AND `o`.`paid`='1'";
			$condition_where .= " AND `o`.`status`<3";
			$condition_table = array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'shop_order'=>'o');
			$condition_field = '`o`.*,`s`.`name`,`s`.`pic_info`';
			$condition_order = '`o`.`create_time` DESC';
		}

		$count = $this->field($condition_field)->where($condition_where)->table($condition_table)->order($condition_order)->count();
		
		import('@.ORG.user_page');
		$p = new Page($count, 10);

		$order_list = $this->field($condition_field)->where($condition_where)->table($condition_table)->order($condition_order)->limit($p->firstRow . ',' . $p->listRows)->select();
		$store_image_class = new store_image();
		foreach ($order_list as &$v) {
			$v['info'] = D("Shop_order_detail")->field(true)->where(array('order_id' => $v['order_id']))->select();
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
			$v['comment'] = stripslashes($v['comment']);
			if($v['pic']){
				$tmp_array = explode(',',$v['pic']);
				$v['pic_count'] = count($tmp_array);
			}
		}
		// 'pagebar' => $p->show(), 'page' => ceil($count/10),'count' => $count
		return array('order_list' => $order_list, 'pagebar' => $p->show(), 'page' => ceil($count/10),'count' => $count);
		// return $order_list;
	}

	public function order_count($data = array())
	{
	    $where = '1';
	    if (isset($data['store_id']) && $data['store_id']) {
	        $where .= " AND o.store_id={$data['store_id']}";
	    }
	    if (isset($data['begin_time']) && $data['begin_time'] && isset($data['end_time']) && $data['end_time']) {
	        $where .= " AND o.pay_time>'{$data['begin_time']}' AND o.pay_time<'{$data['end_time']}'";
	    }
	    $sql = "SELECT d.*, sum(d.num) as total FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON d.order_id=o.order_id WHERE {$where} AND o.paid=1 AND (o.status<2 OR o.status>5) GROUP BY d.goods_id, d.spec_id";
	    $res = $this->query($sql);
	    $list = array();
	    foreach ($res as $r) {
	        if (isset($list[$r['goods_id']])) {
	            $list[$r['goods_id']]['count'] += $r['total'];
	            $list[$r['goods_id']]['row'] += 1;
	            $list[$r['goods_id']]['list'][] = $r;
	        } else {
	            $list[$r['goods_id']] = array('count' => $r['total'], 'name' => $r['name'], 'row' => 1, 'list' => array($r));
	        }
	    }
	    return $list;
	}

    public function goods_sale_count($data = array())
    {
        $where = ' o.paid=1 AND (o.status=2 OR o.status=3) ';
        if (isset($data['store_id']) && $data['store_id']) {
            $where .= " AND o.store_id={$data['store_id']}";
        }
        if (isset($data['begin_time']) && $data['begin_time'] && isset($data['end_time']) && $data['end_time']) {
            $where .= " AND o.pay_time>'{$data['begin_time']}' AND o.pay_time<'{$data['end_time']}'";
        }
        if (isset($data['sort_id']) && $data['sort_id']) {
            $where .= ' AND d.sort_id IN (' . implode(',', $data['sort_id']) . ')';
        }
        if (isset($data['order_from']) && $data['order_from']) {
            if ($data['order_from'] == 1) {
                $where .= ' AND o.order_from=1';
            } elseif ($data['order_from'] == 6) {
                $where .= ' AND o.order_from=6';
            } elseif ($data['order_from'] == 2){
                $where .= ' AND o.order_from<>1 AND o.order_from<>6';
            }
        }
        $sql = "SELECT d.*, sum(d.num) as total FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON d.order_id=o.order_id WHERE {$where} GROUP BY d.goods_id, d.spec_id";
        $res = $this->query($sql);
        $list = array();
        foreach ($res as $r) {
            if (isset($list[$r['goods_id']])) {
                $list[$r['goods_id']]['count'] += $r['total'];
                $list[$r['goods_id']]['row'] += 1;
                $list[$r['goods_id']]['list'][] = $r;
            } else {
                $list[$r['goods_id']] = array(
                    'count' => $r['total'],
                    'name' => $r['name'],
                    'row' => 1,
                    'list' => array(
                        $r
                    )
                );
            }
        }
        return $list;
    }

	public function cancel()
	{
		$cancel_time = 60 * C('config.shop_order_cancel_time');
		$time = time();
		$sql = "SELECT o.* FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS s ON s.store_id=o.store_id WHERE o.paid=1 AND o.status=0 AND ((o.cancel_time>0 AND {$time}-o.create_time>o.cancel_time*60) OR (o.cancel_time=0 AND {$time}-o.create_time>{$cancel_time}))";
		$res = $this->query($sql);
		foreach ($res as $row) {
			$this->where(array('order_id' => $row['order_id']))->save(array('status' => 5));
			D('Shop_order_log')->add_log(array('order_id' => $row['order_id'], 'status' => 10, 'name' => '店员接单超时系统自动取消', 'phone' => ''));
		}
	}
	
	public function shop_notice($order, $is_staff = false)
	{
	
		if ($is_staff) {
			if($order['card_id']){
				$use_result = D('Card_new_coupon')->user_coupon($order['card_id'], $order['order_id'], 'shop', $order['mer_id'], $order['uid']);
				if($use_result['error_code']){
					return array('error' => 1, 'msg' => $use_result['msg']);
				}
			}
	
			if($order['merchant_balance']){
				$use_result = D('Card_new')->use_money($order['uid'], $order['mer_id'], $order['merchant_balance'], '购买 '. $order['real_orderid'].' 扣除会员卡余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
	
			if($order['card_give_money']){
				$use_result = D('Card_new')->use_give_money($order['uid'], $order['mer_id'], $order['card_give_money'], '购买 ' . $order['real_orderid'] . ' 扣除会员卡赠送余额');
				if ($use_result['error_code']) {
					return array('error' => 1, 'msg' => $use_result['msg']);
				}
			}
		}
		
		$store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
		
		//验证增加商家余额
		$order['order_type']='shop';
		//D('Merchant_money_list')->add_money($store['mer_id'],'用户在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元记入收入',$order);
		$order['desc']='用户在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元记入收入';
		D('SystemBill')->bill_method($order['is_own'],$order);
		//商家推广分佣
		$now_user = M('User')->where(array('uid'=>$order['uid']))->find();
		D('Merchant_spread')->add_spread_list($order,$now_user,'shop',$now_user['nickname'].'用户购买快店商品获得佣金');
	
		//积分
		if(C('config.open_extra_price')==1){
			$score = D('Percent_rate')->get_extra_money($order);
			if($score>0){
				D('User')->add_score($order['uid'], floor($score),'在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.C('config.extra_price_alias_name'));
				D('Scroll_msg')->add_msg('shop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.shop_alias_name').'成功获得'.C('config.score_name'));
			}
	
		}else {
			if(C('config.add_score_by_percent')==0 && (C('config.open_score_discount')==0 || $order['score_discount_type']!=2)){
				if(C('config.open_score_get_percent')==1){
					$score_percent = C('config.score_get_percent')/100;
				}else{
					$score_percent = C('config.user_score_get');
				}
				if(C('config.shop_goods_score_edit') == 1){
					$score_get = D('Percent_rate')->shop_get_score($order);
				}else{
					$now_merchant = D('Merchant')->get_info($order['mer_id']);
					if($now_merchant['score_get_percent']>=0){
						$score_percent = $now_merchant['score_get_percent']/100;
					}
					$score_get  = round(($order['payment_money'] + $order['balance_pay']) *$score_percent);
				}

				D('User')->add_score($order['uid'],  $score_get, '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.C('config.score_name'));
			}
			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
		}
		//短信
		$sms_data = array('mer_id' => $store['mer_id'], 'store_id' => $store['store_id'], 'type' => 'shop');
		if (C('config.sms_shop_finish_order') == 1 || C('config.sms_shop_finish_order') == 3) {
			if (empty($order['phone'])) {
				$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
				$order['phone'] = $user['phone'];
			}
			$sms_data['uid'] = $order['uid'];
			$sms_data['mobile'] = $order['userphone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $store['name'] . '店中下的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if (C('config.sms_shop_finish_order') == 2 || C('config.sms_shop_finish_order') == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
			Sms::sendSms($sms_data);
		}

		//微信模板消息提醒
		if ($now_user['openid'] && $order['order_from'] != 6) {
			$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $order['order_id'] . '&mer_id=' . $order['mer_id'] . '&store_id=' . $order['store_id'];
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
			$keyword2 = '';
			$pre = '';
			$i = 1;
			foreach ($goods as $gr) {
			    if ($i < 5) {
			        $keyword2 .= $pre . $gr['name'] . ':' . $gr['price'] . '*' . $gr['num'];
			        $pre = '\n\t\t\t';
			    } elseif ($i == 5) {
			        $keyword2 .= $pre . '...';
			    }
			}
			$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.shop_alias_name').'提醒', 'keyword2' => $order['real_orderid'], 'keyword1' => $keyword2, 'keyword3' => $order['price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '消费成功!'), $order['mer_id']);
		}
	
		//打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($order['order_id'], 'shop_order', 2);
	}

	public function shop_status($is_pick_in_store=0){
		$pick_status = '店员验证消费';
		if($is_pick_in_store==3){
			$pick_status = '店员已发货';




		}
		return array(
				0=>array('txt'=>"订单生成成功",'img'=>3),
				1=>array('txt'=>"订单支付成功",'img'=>3),
				2=>array('txt'=>"店员接单",'img'=>1),
				3=>array('txt'=>"配送员接单",'img'=>4),
				4=>array('txt'=>"配送员取货",'img'=>5),
				5=>array('txt'=>"配送员配送中",'img'=>5),
				6=>array('txt'=>"配送结束",'img'=>5),
				7=>array('txt'=>$pick_status,'img'=>4),
				8=>array('txt'=>"完成评论",'img'=>3),
				9=>array('txt'=>"已完成退款",'img'=>3),
				10=>array('txt'=>"已取消订单",'img'=>3),
				11=>array('txt'=>"商家分配自提点",'img'=>5),
				12=>array('txt'=>"商家发货到自提点",'img'=>5),
				13=>array('txt'=>"自提点已接货",'img'=>5),
				14=>array('txt'=>"自提点已发货",'img'=>5),
				15=>array('txt'=>"您在自提点取货",'img'=>3),
				30=>array('txt'=>"店员为您修改了价格",'img'=>3),
		);
    }

        // 取消订单
    public function check_refund($now_order)
    {
        $nowTime = time() + 5;
        $order_id = intval($now_order['order_id']);
        if (empty($now_order['paid'])) {
            $data_shop_order['order_id'] = $order_id;
            $data_shop_order['status'] = 5;
            $data_shop_order['last_time'] = $nowTime;
            $this->data($data_shop_order)->save();
            return false;
        }
        
        $mer_store = D('Merchant_store')->where(array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id']))->find();
        
        $my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
        
        // 线下支付退款
        if ($now_order['pay_type'] == 'offline') {
            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
            $data_shop_order['status'] = 4;
            $data_shop_order['last_time'] = $nowTime + 1;
            if ($this->data($data_shop_order)->save()) {
                $return = $this->shop_refund_detail($now_order, $store_id);
                if ($return['error_code']) {
                    $data_shop_order['order_id'] = $order_id;
                    $data_shop_order['status'] = 5;
                    $data_shop_order['last_time'] = $nowTime + 1;
                    $this->data($data_shop_order)->save();
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            if ($now_order['payment_money'] != '0.00') {
                if ($now_order['is_own']) {
                    $pay_method = array();
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['mer_id']))->find();
                    foreach ($merchant_ownpay as $ownKey => $ownValue) {
                        $ownValueArr = unserialize($ownValue);
                        if ($ownValueArr['open']) {
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name' => '', 'config' => $ownValueArr);
                        }
                    }
                } else {
                    $is_wap = $now_order['pay_type'] == 'alipayh5' ? 1 : 0;
                    $pay_method = D('Config')->get_pay_method(0, 0, $is_wap, 0);
                }
                
                if (empty($pay_method)) {
                    $data_shop_order['order_id'] = $order_id;
                    $data_shop_order['status'] = 5;
                    $data_shop_order['last_time'] = $nowTime + 1;
                    $this->data($data_shop_order)->save();
                    return false;
                }
                if (empty($pay_method[$now_order['pay_type']])) {
                    $data_shop_order['order_id'] = $order_id;
                    $data_shop_order['status'] = 5;
                    $data_shop_order['last_time'] = $nowTime + 1;
                    $this->data($data_shop_order)->save();
                    return false;
                }
                
                $pay_class_name = ucfirst($now_order['pay_type']);
                $import_result = import('@.ORG.pay.' . $pay_class_name);
                if (empty($import_result)) {
                    $data_shop_order['order_id'] = $order_id;
                    $data_shop_order['status'] = 5;
                    $data_shop_order['last_time'] = $nowTime + 1;
                    $this->data($data_shop_order)->save();
                    return false;
                }
                
                $this->where(array('order_id' => $now_order['order_id']))->save(array('is_refund' => 1));
                $now_order['order_type'] = 'shop';
                $now_order['order_id'] = $now_order['orderid'];
                if ($now_order['order_from'] == 4) {
                    $pay_method['weixin']['config'] = array(
                        'pay_weixin_appid' => C('config.pay_wxapp_appid'),
                        'pay_weixin_key' => C('config.pay_wxapp_key'),
                        'pay_weixin_mchid' => C('config.pay_wxapp_mchid'),
                        'pay_weixin_appsecret' => C('config.pay_wxapp_appsecret')
                    );
                    if ($now_order['is_own'] == 1) {
                        $wxapp_own_pay = M('Weixin_app_bind')->where(array('other_id' => $_POST['own_pay_mer_id'], 'bind_type' => 0))->find();
                        $pay_method['weixin']['config'] = array(
                            'pay_weixin_appid' => $wxapp_own_pay['appid'],
                            'pay_weixin_key' => $wxapp_own_pay['wxpay_key'],
                            'pay_weixin_mchid' => $wxapp_own_pay['wxpay_merid'],
                            'pay_weixin_appsecret' => $wxapp_own_pay['appsecret'],
                            'is_own' => 1
                        );
                    }
                }


				if ($pay_class_name == 'Alipay' && $now_order['is_mobile_pay'] == 2 && C('config.new_pay_alipay_app_public_key') != '' && C('config.new_pay_alipay_app_appid')!= '' && C('config.new_pay_alipay_app_private_key')!= '') {
					$pay_class_name = 'AlipayApp';
					$pay_method['alipay']['config']['new_pay_alipay_app_appid'] = C('config.new_pay_alipay_app_appid');
					$pay_method['alipay']['config']['new_pay_alipay_app_private_key'] = C('config.new_pay_alipay_app_private_key');
					$pay_method['alipay']['config']['new_pay_alipay_app_public_key'] = C('config.new_pay_alipay_app_public_key') ;
				}
				$import_result = import('@.ORG.pay.' . $pay_class_name);
				$pay_class = new $pay_class_name($now_order, $now_order['payment_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $my_user, 1);

				$go_refund_param = $pay_class->refund();
                
                $now_order['order_id'] = $order_id;
                $data_shop_order['order_id'] = $order_id;
                $data_shop_order['refund_detail'] = serialize($go_refund_param['refund_param']);
                if (! (empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok')) {
                    $data_shop_order['status'] = 5;
                    $data_shop_order['is_refund'] = 0;
                    $data_shop_order['last_time'] = $nowTime;
                } else {
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = $nowTime;
                }
            }
            if (empty($now_order['pay_type'])) {
                $data_shop_order['order_id'] = $order_id;
                $data_shop_order['status'] = 4;
                $data_shop_order['last_time'] = $nowTime;
            }
            
            if ($this->data($data_shop_order)->save()) {
                if ($data_shop_order['status'] == 4) {
                    $return = $this->shop_refund_detail($now_order, $now_order['store_id']);
                    if (empty($return['error_code'])) {
                        D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
                    }
                }
            }
        }
    }


	private function shop_refund_detail($now_order, $store_id)
	{
		$order_id  = $now_order['order_id'];

		$mer_store = D('Merchant_store')->where(array('mer_id' => $now_order['mer_id'], 'store_id' => $store_id))->find();

		//如果使用了积分 2016-1-15
		if ($now_order['score_used_count'] != 0) {
		    $result = D('User')->add_score($now_order['uid'], $now_order['score_used_count'], C('config.shop_alias_name') . '商品退款，' . C('config.score_name') . '回滚，订单编号' . $now_order['real_orderid']);
			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			$this->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//平台余额退款
		if ($now_order['balance_pay'] != '0.00') {
		    $result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'], C('config.shop_alias_name') . '商品退款，增加余额，订单编号' . $now_order['real_orderid']);
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			$this->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
			}
			$go_refund_param['msg'] = '平台余额退款成功';
		}
		//商家会员卡余额退款
		if ($now_order['merchant_balance'] != '0.00' || $now_order['card_give_money'] != '0.00') {
		    $result = D('Card_new')->add_user_money($now_order['mer_id'], $now_order['uid'],  $now_order['merchant_balance'],$now_order['card_give_money'], 0, C('config.shop_alias_name') . '商品退款，增加余额，订单编号' . $now_order['real_orderid'], C('config.shop_alias_name') . '商品退款，增加余额，订单编号' . $now_order['real_orderid']);
			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			$this->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
			}
			$go_refund_param['msg'] = $result['msg'];
		}
		//退款打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($now_order['order_id'], 'shop_order', 3);

		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'shop');
		if (C('config.sms_shop_cancel_order') == 1 || C('config.sms_shop_cancel_order') == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['userphone'] ? $now_order['userphone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被取消并退款，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if (C('config.sms_shop_cancel_order') == 2 || C('config.sms_shop_cancel_order') == 3) {
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
			$this->where(array('order_id' => $now_order['order_id']))->save(array('is_rollback' => 1));
		}
		D("Merchant_store_shop")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);
		//退款时销量回滚
		$go_refund_param['error_code'] = false;
		return $go_refund_param;
	}
    
    public function saveOrder($order_data, $return, $user)
    {
        $detail_obj = D('Shop_order_detail');
        $goods_obj = D("Shop_goods");
        $oldOrder = null;
        $orderTemp = null;
        $order_data['platform_merchant'] = $return['platform_merchant'];//平台优惠中商家补贴的总和统计
        $order_data['platform_plat'] = $return['platform_plat'];//平台优惠中平台补贴的总和统计

        /** 20180507 平台给商家限定订单数的折扣，下单时就增加折扣订单数目，没有做此单没有支付或者其他原因回滚折扣订单数 */
        $order_data['plat_discount'] = 0;
        $order_data['plat_discount_price'] = 0;
        $plat_discount = D('Merchant')->getDiscount($order_data['mer_id']);
        $plat_discount = floatval($plat_discount);
        if ($plat_discount) {
            $dprice = round($order_data['price'] * $plat_discount * 0.01, 2);
            $order_data['can_discount_money'] = round($order_data['can_discount_money'] * $plat_discount * 0.01, 2);
            $order_data['balance_reduce'] += ($order_data['price'] - $dprice);
            $order_data['plat_discount_price'] = $order_data['price'] - $dprice;
            $order_data['price'] = $dprice;
            $order_data['plat_discount'] = $plat_discount;
        } else {
            $plat_discount = 100;
        }
        
        if ($order_data['cartid']) {
            $oldOrder = $this->field(true)->where(array('cartid' => $order_data['cartid']))->find();
            if ($oldOrder['status'] != 0 || $oldOrder['paid'] != 0) return 0;
            $orderTemp = D('Shop_order_temp')->field(true)->where(array('cartid' => $order_data['cartid']))->find();
        }
        
        $order_id = 0;
        if ($oldOrder && $orderTemp) {
            if ($this->where(array('order_id' => $oldOrder['order_id']))->save($order_data)) {
                $details = $detail_obj->where(array('order_id' => $oldOrder['order_id']))->select();
                foreach ($details as $detail) {
                    $order_data['reduce_stock_type'] && $goods_obj->update_stock($detail, 1);//修改库存
                }
                $detail_obj->where(array('order_id' => $oldOrder['order_id']))->delete();
                $order_id = $oldOrder['order_id'];
            }
        } else {
            if ($order_id = $this->add($order_data)) {
                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
                if ($order_data['is_pick_in_store'] == 2 && $order_data['status'] == 7) {
                    D('Pick_order')->add(array('store_id' => $order_data['store_id'], 'order_id' => $order_id, 'pick_id' => $order_data['pick_id'], 'status' => 0, 'dateline' => time()));
                    //D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
                }
            }
        }
        if ($order_id) {
            if (isset($orderTemp['cartid']) && $orderTemp['cartid']) {
                D('Shop_order_temp')->where(array('cartid' => $orderTemp['cartid']))->save(array('price' => $order_data['price'], 'packing_charge' => $order_data['packing_charge'], 'freight_charge' => $order_data['freight_charge']));
            }

            $discountPrice = $order_data['merchant_reduce'] + $order_data['balance_reduce'];
            $totalDiscountPrice = $order_data['discount_price'];
            
            foreach ($return['goods'] as $grow) {
                if (($order_data['order_from'] == 2 || $order_data['order_from'] == 3) && !empty($grow['str'])) {
                    $temp = explode(',', $grow['str']);
                    $temp = array_reverse($temp);
                    $grow['str'] = implode(',', $temp);
                }
                $detail_data = array(
                    'store_id' => $return['store_id'], 
                    'order_id' => $order_id, 
                    'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 
                    'cost_price' => $grow['cost_price'], 
                    'unit' => $grow['unit'], 
                    'goods_id' => $grow['goods_id'], 
                    'name' => $grow['name'], 
                    'price' => $grow['price'], 
                    'num' => $grow['num'], 
                    'spec' => $grow['str'], 
                    'spec_id' => $grow['spec_id'], 
                    'create_time' => time(),
                    'extra_price' => isset($grow['extra_price']) ? floatval($grow['extra_price']) : 0,
                    'packname' => isset($grow['packname']) ? trim($grow['packname']) : '',
                    'packing_charge' => isset($grow['packing_charge']) ? floatval($grow['packing_charge']) : 0
                );
                $detail_data['is_seckill'] = intval($grow['is_seckill_price']);
                $detail_data['is_use_coupon'] = intval($grow['is_use_coupon']);
                $detail_data['discount_type'] = intval($grow['discount_type']);
                $detail_data['discount_rate'] = $grow['discount_rate'];
                $detail_data['sort_id'] = $grow['sort_id'];
                $detail_data['old_price'] = floatval($grow['old_price']);
                $detail_data['discount_price'] = floatval($grow['discount_price']);
                $detail_data['pay_price'] = floatval(round($grow['discount_price'] * (1 - $discountPrice / $totalDiscountPrice), 2) * $plat_discount * 0.01);
                $detail_obj->add($detail_data);
                $order_data['reduce_stock_type'] && $goods_obj->update_stock($grow);//修改库存
            }

            if ($user['openid']) {
                $keyword2 = '';
                $pre = '';
                $i = 1;
                foreach ($return['goods'] as $menu) {
                    if ($i < 5) {
                        $keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
                        $pre = '\n\t\t\t';
                    } elseif ($i == 5) {
                        $keyword2 .= $pre . '...';
                    }
                    $i++;
                }
                $href = C('config.site_url') . '/wap.php?c=Shop&a=status&order_id='. $order_id;
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $order_data['real_orderid'], 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => C('config.shop_alias_name') . '订单已生成，等待您完成支付并使用'), $order_data['mer_id']);
            }
        
            $printHaddle = new PrintHaddle();
            $printHaddle->printit($order_id, 'shop_order', 0);
            
            $sms_data = array('mer_id' => $return['mer_id'], 'store_id' => $return['store_id'], 'type' => 'shop');
            if (C('config.sms_shop_place_order') == 1 || C('config.sms_shop_place_order') == 3) {
                $sms_data['uid'] = $this->user_session['uid'];
                $sms_data['mobile'] = $order_data['userphone'];
                $sms_data['sendto'] = 'user';
                $sms_data['content'] = '您' . date("H时i分") . '在【' . $return['store']['name'] . '】中下了一个订单，订单号：' . $order_data['real_orderid'];
                Sms::sendSms($sms_data);
            }
            if (C('config.sms_shop_place_order') == 2 || C('config.sms_shop_place_order') == 3) {
                $sms_data['uid'] = 0;
                $sms_data['mobile'] = $return['store']['phone'];
                $sms_data['sendto'] = 'merchant';
                $sms_data['content'] = '顾客【' . $order_data['username'] . '】在' . date("Y-m-d H:i:s") . '时下了一个订单，订单号：' . $order_data['real_orderid'] . '请您注意查看并处理!';
                Sms::sendSms($sms_data);
            }
            return $order_id;
        } else {
            return 0;
        }
    }
    
    private function checkTime($businessTime, $time)
    {
        if (empty($businessTime)) return true;
        $time = strtotime(date('Y-m-d') . ' ' . date('H:i:s', $time));
        foreach ($businessTime as $row) {
            $s = strtotime(date('Y-m-d ' . $row['s']));
            $e = strtotime(date('Y-m-d ' . $row['e']));
            if ($s <= $time && $time <= $e) {
                return true;
            }
        }
        return false;
    }
    
    public function formatTime($data, &$date_list, $businessTime)
    {
        $time_select = $data['time_select'];
        $after_time = $data['after_time'];
        $start_time = $data['start_time'];
        $stop_time = $data['stop_time'];
        $time = $data['time'];
        $day = $data['day'];
        $delivery_fee = $data['delivery_fee'];
    
        $stime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $start_time) + $after_time;
        $etime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $stop_time);

        $next_stime = 0;
        $next_etime = 0;
        if ($etime < $stime) {
            $etime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' 23:59:59');
            $next_stime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')));
            $next_etime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $stop_time);
        }
    
        if ($stime < $time) $stime = $time;
        if ($etime > $stime) {
            $bigen_time = $stime;
            $_m_15 = strtotime(date('Y-m-d H:15', $stime));
            $_m_30 = strtotime(date('Y-m-d H:30', $stime));
            $_m_45 = strtotime(date('Y-m-d H:45', $stime));
            if ($_m_15 >= $stime) {
                $stime = $_m_15;
            } elseif ($_m_30 >= $stime) {
                $stime = $_m_30;
            } elseif ($_m_45 >= $stime) {
                $stime = $_m_45;
            } else {
                $stime = strtotime(date('Y-m-d H:00', $stime + 3600));
            }
            if ($this->checkTime($businessTime, $bigen_time)) {
                $date_list[date('Y-m-d', $bigen_time)][date('H:i', $bigen_time)] = array('hour_minute' => date('H:i', $bigen_time), 'time_select' => $time_select, 'delivery_fee' => $delivery_fee);
            }
            for ($now_date = $stime; $now_date <= $etime;) {
                if ($this->checkTime($businessTime, $now_date)) {
                    $date_list[date('Y-m-d', $now_date)][date('H:i', $now_date)] = array('hour_minute' => date('H:i', $now_date), 'time_select' => $time_select, 'delivery_fee' => $delivery_fee);
                }
                $now_date += 900;
            }
        }
    
        if ($next_stime != $next_etime) {
            $stime = $next_stime;
            $etime = $next_etime;
            if ($stime < $time) $stime = $time;
            if ($etime > $stime) {
                $bigen_time = $stime;
                $_m_15 = strtotime(date('Y-m-d H:15', $stime));
                $_m_30 = strtotime(date('Y-m-d H:30', $stime));
                $_m_45 = strtotime(date('Y-m-d H:45', $stime));
                if ($_m_15 >= $stime) {
                    $stime = $_m_15;
                } elseif ($_m_30 >= $stime) {
                    $stime = $_m_30;
                } elseif ($_m_45 >= $stime) {
                    $stime = $_m_45;
                } else {
                    $stime = strtotime(date('Y-m-d H:00', $stime + 3600));
                }
                if ($this->checkTime($businessTime, $bigen_time)) {
                    $date_list[date('Y-m-d', $bigen_time)][date('H:i', $bigen_time)] = array('hour_minute' => date('H:i', $bigen_time), 'time_select' => $time_select, 'delivery_fee' => $delivery_fee);
                }
                for ($now_date = $stime; $now_date <= $etime;) {
                    if ($this->checkTime($businessTime, $now_date)) {
                        $date_list[date('Y-m-d', $now_date)][date('H:i', $now_date)] = array('hour_minute' => date('H:i', $now_date), 'time_select' => $time_select, 'delivery_fee' => $delivery_fee);
                    }
                    $now_date += 900;
                }
            }
            
            $stime = $next_stime + 86400;
            $etime = $next_etime + 86400;
            if ($stime < $time) $stime = $time;
            if ($etime > $stime) {
                $bigen_time = $stime;
                $_m_15 = strtotime(date('Y-m-d H:15', $stime));
                $_m_30 = strtotime(date('Y-m-d H:30', $stime));
                $_m_45 = strtotime(date('Y-m-d H:45', $stime));
                if ($_m_15 >= $stime) {
                    $stime = $_m_15;
                } elseif ($_m_30 >= $stime) {
                    $stime = $_m_30;
                } elseif ($_m_45 >= $stime) {
                    $stime = $_m_45;
                } else {
                    $stime = strtotime(date('Y-m-d H:00', $stime + 3600));
                }
                if ($this->checkTime($businessTime, $bigen_time)) {
                    $date_list[date('Y-m-d', $bigen_time)][date('H:i', $bigen_time)] = array('hour_minute' => date('H:i', $bigen_time), 'time_select' => $time_select, 'delivery_fee' => $delivery_fee);
                }
                for ($now_date = $stime; $now_date <= $etime;) {
                    if ($this->checkTime($businessTime, $now_date)) {
                        $date_list[date('Y-m-d', $now_date)][date('H:i', $now_date)] = array('hour_minute' => date('H:i', $now_date), 'time_select' => $time_select, 'delivery_fee' => $delivery_fee);
                    }
                    $now_date += 900;
                }
            }
        }
    }
}
?>
