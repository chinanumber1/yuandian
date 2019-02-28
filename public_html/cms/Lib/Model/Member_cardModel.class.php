<?php

class Member_cardModel extends Model{
	/**
	 * 获取用户对应商家的总积分
	 * @param int $uid
	 * @param int $mer_id
	 * @return number
	 */
	public function get_score($uid, $mer_id){
		$userinfo = D("Userinfo")->field('total_score')->where(array('token' => $mer_id, 'wecha_id' => $uid))->find();
		return isset($userinfo['total_score']) ? intval($userinfo['total_score']) : 0;
	}
	
	/**
	 * 获取用户对应商家的余额
	 * @param unknown $uid
	 * @param unknown $mer_id
	 * @return Ambigous <number, mixed>
	 */
	public function get_balance($uid, $mer_id){
		$userinfo = D("Userinfo")->field('balance')->where(array('token' => $mer_id, 'wecha_id' => $uid))->find();
		return isset($userinfo['balance']) ? floatval($userinfo['balance']) : 0;
	}
	
	/**
	 * 检查用户是否领取了商家的会员卡
	 * 如果没有且商家开了会员卡，提现用户去领取
	 * @param int $uid
	 * @param int $mer_id
	 * @return multitype:number string
	 */
	public function check_card($uid, $mer_id){
		$userinfo = D("Userinfo")->field(true)->where(array('token' => $mer_id, 'wecha_id' => $uid))->find();
		if (empty($userinfo)) {
			$cards = D("Member_card_set")->field(true)->where(array('token' => $mer_id))->select();
			$cardids = array();
			foreach ($cards as $c) {
				$cardids[] = $c['id'];
			}
			$card_number = D("Member_card_create")->field(true)->where(array('wecha_id' => '', 'token' => $mer_id, 'cardid' => array('in', $cardids)))->select();
			if ($card_number) {
				$config = D('Config')->get_config();
				return array('error_code' => 1, 'msg' => '您现在还没有该商家的会员卡，您现在可以去领一张', 'url' => $config['site_url'] . '/wap.php?c=Card&m=index&token=' . $mer_id);
			}
		} else {
			$mycard = D("Member_card_create")->field(true)->where(array('token' => $mer_id, 'wecha_id' => $uid))->find();
			if (empty($mycard)) array('error_code' => 1, 'msg' => '未领取会员卡', 'url' => $config['site_url'] . "/wap.php?c=Card&m=index&token={$mer_id}");
			$card = D("Member_card_set")->field(true)->where(array('token' => $mer_id, 'id' => $mycard['cardid']))->find();
			if (empty($card)) return array('error_code' => 1, 'msg' => '已拥有的卡号对应的会员卡已被商家暂停使用，请重新领取别的卡', 'url' => $config['site_url'] . "/wap.php?c=Card&m=index&token={$mer_id}");
			return array('error_code' => 0, 'msg' => 'ok');
		}
	}
	
	/**
	 * 会员卡余额支付
	 * @param int $uid
	 * @param int $mer_id
	 * @param float $money
	 * @param string $ordername
	 * @return boolean|Ambigous <boolean, mixed, unknown>
	 */
	public function use_card($uid, $mer_id, $money, $ordername = '会员卡现金支付'){
		$userinfo = D("Userinfo")->field(true)->where(array('token' => $mer_id, 'wecha_id' => $uid))->find();
		if (empty($userinfo)) return array('error_code' => 1, 'msg' => '用户不存在！');
		if ($money <= 0 || $money > $userinfo['balance']) return array('error_code' => 1, 'msg' => '用户不存在');
		$mycard = D("Member_card_create")->field(true)->where(array('token' => $mer_id, 'wecha_id' => $uid))->find();
		if (empty($mycard)) return array('error_code' => 1, 'msg' => '您还没有领取会员卡！');
		$card = D("Member_card_set")->field(true)->where(array('token' => $mer_id, 'id' => $mycard['cardid']))->find();
		if (empty($card)) return array('error_code' => 1, 'msg' => '商家没有设置会员卡！');
		$exchange = D("Member_card_exchange")->field(true)->where(array('token' => $mer_id, 'cardid' => $mycard['cardid']))->find();
		$data = array();
		$result = false;
		if ($exchange) {
			$score = $money * $exchange['reward'];
			$data['expend_score'] = $userinfo['expend_score'] + $score;
			$data['total_score'] = $userinfo['total_score'] + $score;
			$data['balance'] = $userinfo['balance'] - $money;
			$data['expensetotal'] = $userinfo['expensetotal'] + $money;
			$result = D("Userinfo")->where(array('token' => $mer_id, 'wecha_id' => $uid))->save($data);
		} else {
			$data['balance'] = $userinfo['balance'] - $money;
			$data['expensetotal'] = $userinfo['expensetotal'] + $money;
			$result = D("Userinfo")->where(array('token' => $mer_id, 'wecha_id' => $uid))->save($data);
		}
		if ($result) {
			$single_orderid = date('YmdHis',time()).mt_rand(1000,9999);
			$pay_record = array('orderid' => $single_orderid, 'ordername' => $ordername, 'paytype' => 'CardPay', 'token' => $mer_id, 'wecha_id' => $uid, 'createtime' => time(), 'paytime' => time());
			$pay_record['paid'] = 1;
			$pay_record['type'] = 0;
			$pay_record['price'] = $money;
			$result = D("Member_card_pay_record")->add($pay_record);
		}
		return array('error_code' => 0, 'data' => $result);
	}
	
	/**
	 * 会员卡余额支付的退款
	 * @param int $uid
	 * @param int $mer_id
	 * @param float $money
	 * @param string $ordername
	 * @return boolean|Ambigous <boolean, mixed, unknown>
	 */
	public function add_card($uid, $mer_id, $money, $ordername = '退款'){
		$userinfo = D("Userinfo")->field(true)->where(array('token' => $mer_id, 'wecha_id' => $uid))->find();
		if (empty($userinfo)) return array('error_code' => 1, 'msg' => '用户不存在！');
		if ($money <= 0) return array('error_code' => 1, 'msg' => '退款的金额不能为负数');
		$mycard = D("Member_card_create")->field(true)->where(array('token' => $mer_id, 'wecha_id' => $uid))->find();
		if (empty($mycard)) return array('error_code' => 1, 'msg' => '您还没有领取会员卡！');
		$card = D("Member_card_set")->field(true)->where(array('token' => $mer_id, 'id' => $mycard['cardid']))->find();
		if (empty($card)) return array('error_code' => 1, 'msg' => '商家没有设置会员卡！');
		$exchange = D("Member_card_exchange")->field(true)->where(array('token' => $mer_id, 'cardid' => $mycard['cardid']))->find();
		$data = array();
		$result = false;
		if ($exchange) {
			$score = $money * $exchange['reward'];
			$data['expend_score'] = $userinfo['expend_score'] - $score;
			$data['total_score'] = $userinfo['total_score'] - $score;
			$data['balance'] = $userinfo['balance'] + $money;
			$result = D("Userinfo")->where(array('token' => $mer_id, 'wecha_id' => $uid))->save($data);
		} else {
			$data['balance'] = $userinfo['balance'] + $money;
			$result = D("Userinfo")->where(array('token' => $mer_id, 'wecha_id' => $uid))->save($data);
		}
		if ($result) {
			$single_orderid = date('YmdHis',time()).mt_rand(1000,9999);
			$pay_record = array('orderid' => $single_orderid, 'ordername' => $ordername, 'paytype' => 'CardPay', 'token' => $mer_id, 'wecha_id' => $uid, 'createtime' => time(), 'paytime' => time());
			$pay_record['paid'] = 1;
			$pay_record['type'] = 0;
			$pay_record['price'] = $money;
			$result = D("Member_card_pay_record")->add($pay_record);
		}
		return array('error_code' => 0, 'msg' => '退款成功，已经返还到您的会员卡余额中');
	}
}
?>