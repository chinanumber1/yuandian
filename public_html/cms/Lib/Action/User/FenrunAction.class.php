<?php
	/**
	 * Created by Goi.
	 * User: Goi
	 * Date: 2017年3月22日
	 * Time: 11:28:07
	 * Desc: 分润钱包
	 */
	class FenrunAction extends BaseAction{
		public function __construct()
		{
			parent::__construct();
			if(!$this->config['open_score_fenrun']){
				$this->error_tips('非法访问！');
			}
			if(empty($this->user_session)){
				$this->error_tips('请先进行登录！');
			}
			//if(empty($this->user_session['openid'])) {
			//	$this->error_tips('您没有绑定微信');
			//}
			$this->user_session  = $now_user= D('User')->get_user($this->user_session['uid']);
			$this->assign('now_user',$now_user);
		}

		//分润钱包
		public function fenrun_money_list(){
			$list	=	D('Fenrun')->fenrun_list($this->user_session['uid']);
			$this->assign($list);
			$this->display();
		}

		//用户奖励明细
		public function award_list(){
			$type = empty($_GET['type'])?1:$_GET['type'];
			$where['uid'] = $this->user_session['uid'];
			$where['type'] = $type;
			$where['income'] = 1;

			$return['award_list']	=	M('Fenrun_recommend_award_list')->where($where)->order('id DESC')->select();
			$return['free_list']	=   M('Fenrun_free_award_money_list')->where($where)->order('id DESC')->select();
			$this->assign($return);
			$this->display();
		}

		/*分润转余额*/
		public function fenrun_to_balance(){
			if(!$this->config['open_fenrun_to_balance']) {
				$this->error_tips('平台未开启分润转余额！');
			}
			$now_user = $this->user_session;
			if($now_user['fenrun_money']<$this->config['min_fenrun_to_balance_money']) {
				$this->error_tips('分润钱包满'.$this->config['min_fenrun_to_balance_money'].'元才能转余额！');
			}
			$percent = $this->config['fenrun_to_balance_percent'];
			if($_GET['money']>0){
				$money = $_GET['money'];
				if($money<$this->config['min_fenrun_to_balance_money']){
					$this->error('不能低于最低转余额金额 '.$this->config['company_least_money'].' 元!');
				}
				if($money>$now_user['fenrun_money']){
					$this->error('转余额超出限额，请求失败！');
				}
				$data_fenrun['uid'] = $now_user['uid'];
				$data_fenrun['money'] = $money*((100-$percent)/100);
				$data_fenrun['fenrun_money'] = $money;
				$data_fenrun['des'] = "用户分润钱包转余额对账订单|用户ID ".$now_user['uid']." |转余额 ".$money." 元" ;
				if($percent>0){
					$data_fenrun['des'] .= '|手续费 '.$money*($percent)/100 .' 比例 '.$percent.'%';
				}

				$use_result = D('Fenrun')->fenrun_to_balance($data_fenrun);
				$this->success($use_result['msg'].'分润转余额成功');die;
			}else{
				$this->display();
			}
		}

		public function award_to_balance(){
			if(!$this->config['open_award_to_balance']) {
				$this->error_tips('平台未开启分佣转余额！');
			}
			$now_user = $this->user_session;


			if( $_GET['money']){
				$money = $_GET['money'];
				if($money<0){
					$this->error_tips('转余额金额出错！');
				}
				if($money>$now_user['free_award_money']){
					$this->error_tips('转余额超出限额，请求失败！');
				}
				$data_award['des'] = "用户可用佣金转余额对账订单|用户ID ".$now_user['uid']." |转账 ".$money." 元" ;
				$data_award['uid'] = $now_user['uid'];
				$data_award['money'] = $money;
				$use_result = D('Fenrun')->award_to_balance($data_award);
				$this->success($use_result['msg'].'佣金转余额成功');die;
			}else{
				$this->display();
			}
		}


		public function user_free_award_list(){
			$list	=	D('Fenrun')->free_recommend_awards_list($this->user_session['uid']);
			$this->assign($list);
			$this->display();
		}

		public function ajax_fenrun_list(){
			$list	=	D('Fenrun')->fenrun_list($this->user_session['uid']);
			foreach($list['list'] as &$v){
				$v['add_time']	=	date('Y/m/d H:i',$v['add_time']);
			}
			echo json_encode($list);die;
		}

		public function ajax_user_free_award_list(){
			$list	=	D('Fenrun')->free_recommend_awards_list($this->user_session['uid']);
			foreach($list['list'] as &$v){
				$v['add_time']	=	date('Y/m/d H:i',$v['add_time']);
			}
			echo json_encode($list);die;
		}

		public function ajax_user_award_list(){
			$type = empty($_POST['type'])?1:$_POST['type'];
			$list	=	D('Fenrun')->recommend_awards_list($this->user_session['uid'],$type);
			foreach($list['list'] as &$v){
				$v['add_time']	=	date('Y/m/d H:i',$v['add_time']);
			}
			echo json_encode($list);die;
		}

		/*冻结首页，用户，商家统计*/
		public  function frozen_award_index(){
			$return['user_count'] = D('User_spread')->get_spread_num($this->user_session['openid'],$this->user_session['uid']);
			$return['mer_count'] = D('Merchant_spread')->get_spread_num($this->user_session['openid'],$this->user_session['uid']);
			$return['user_free_total'] = D('Fenrun')->get_free_total($this->user_session['uid'],1);
			$return['mer_free_total'] = D('Fenrun')->get_free_total($this->user_session['uid'],2);
			$return['user_award_total'] = D('Fenrun')->get_award_total($this->user_session['uid'],1);
			$return['mer_award_total'] = D('Fenrun')->get_award_total($this->user_session['uid'],2);

			$type = empty($_GET['type'])?1:$_GET['type'];
			$where['uid'] = $this->user_session['uid'];
			$where['type'] = $type;
			$where['income'] = 1;

			import('@.ORG.user_page');

			if($_GET['detail']==1 ||!isset($_GET['detail'])){
				$where['l.uid'] = $where['uid'];
				unset($where['uid']);

				if($type==1){
					$count =  M('Fenrun_recommend_award_list')->join('as l left join '.C('DB_PREFIX').'user u ON u.uid =l.type_id')->where($where)->count();
					$p = new Page($count,10);
					$return['list']	=	M('Fenrun_recommend_award_list')->field('l.*,u.phone,u.nickname as spreadname')->join('as l left join '.C('DB_PREFIX').'user u ON u.uid =l.type_id')->where($where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
				}else{
					$count =  M('Fenrun_recommend_award_list')->join('as l left join '.C('DB_PREFIX').'merchant u ON u.mer_id =l.type_id')->where($where)->count();
					$p = new Page($count,10);
					$return['list']	=	M('Fenrun_recommend_award_list')->field('l.*,u.phone,u.name as spreadname')->join('as l left join '.C('DB_PREFIX').'merchant u ON u.mer_id =l.type_id')->where($where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();

				}

				//$return['award_list']	=	M('Fenrun_recommend_award_list')->field('l.*,u.phone,u.nickname as spreadname')->join('as l left join '.C('DB_PREFIX').'user u ON u.uid =l.type_id')->where($where)->order('id DESC')->select();

			}else{
				$where['l.uid'] = $where['uid'];
				unset($where['uid']);

				if($type==1){
					$count =  M('Fenrun_free_award_money_list')->join('as l left join '.C('DB_PREFIX').'user u ON u.uid =l.type_id')->where($where)->count();
					$p = new Page($count,10);
					$return['list']	=   M('Fenrun_free_award_money_list')->field('l.*,u.phone,u.nickname as spreadname')->join('as l left join '.C('DB_PREFIX').'user u ON u.uid =l.type_id')->where($where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
				}else{
					$count =  M('Fenrun_free_award_money_list')->join('as l left join '.C('DB_PREFIX').'merchant u ON u.mer_id =l.type_id')->where($where)->count();
					$p = new Page($count,10);
					$return['list']	=   M('Fenrun_free_award_money_list')->field('l.*,u.phone,u.name as spreadname')->join('as l left join '.C('DB_PREFIX').'merchant u ON u.mer_id =l.type_id')->where($where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
				}

			}
			$return['pagebar'] = $p->show();
			$this->assign($return);

			$this->display();
		}

		public function withdraw(){
			if($this->config['company_pay_open']=='0') {
				$this->error_tips('平台没有开启提现功能！');
			}

			$user_info = M('User')->where(array('uid'=>$this->user_session['uid']))->find();
			$can_withdraw_money = $user_info['now_money']>=$user_info['score_recharge_money']?floatval((int)(($user_info['now_money']-$user_info['score_recharge_money'])*100)/100):$user_info['now_money'];
			if ($user_info['frozen_time'] < $_SERVER['REQUEST_TIME'] && $_SERVER['REQUEST_TIME'] < $user_info['free_time']) {
				$user_info['can_withdraw_money'] = $can_withdraw_money-$user_info['frozen_money']>0? $can_withdraw_money-$user_info['frozen_money']:0;
			}else{
				$user_info['can_withdraw_money'] = $can_withdraw_money;
			}
			$this->assign('user_info',$user_info);

			if(IS_POST){
				$money = $_POST['money'];
				if(empty($_POST['truename'])){
					$this->error('真实姓名不能为空');
				}
				if($money<$this->config['company_least_money']){
					$this->error('不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
				}
				if($money>$can_withdraw_money){
					$this->error('提款超出限额，请求失败！');
				}
				if(!is_numeric($_POST['pay_type'])){
					$this->error('提现方式没有选择');
				}
				$data_companypay['type'] = 'user';
				$data_companypay['pay_type'] = $_POST['pay_type'];//0 银行卡，1 支付宝 2微信
				$data_companypay['pay_id'] = $user_info['uid'];
				$remark = '';
				if($_POST['pay_type']==0){
					$data_companypay['account'] = $_POST['card_number'];
					if(empty($_POST['card_number']) || empty($_POST['card_username']) ||empty($_POST['bank']) ){
						$this->error('银行账号不全');
					}
					$remark = '开户名：'.$_POST['card_username'].',开户行：'.$_POST['bank'];
				}else if ($_POST['pay_type']==1){
					if(empty($_POST['alipay_account'])  ){
						$this->error('支付宝账号不全');
					}
					$data_companypay['account'] = $_POST['alipay_account'];

				}else{
					if(empty($user_info['openid'])  ){
						$this->error('没有绑定微信');
					}
					$data_companypay['account'] =  $user_info['openid'];
				}

				$data_companypay['truename'] = $_POST['truename'];
				$data_companypay['remark'] = $remark ;
				$data_companypay['phone'] = $user_info['phone'];
				$data_companypay['money'] = bcmul($money*((100-$this->config['company_pay_user_percent'])/100),100);
				$data_companypay['old_money'] = $money*100;
				$data_companypay['desc'] = "用户提现对账订单|用户ID ".$user_info['uid']." |转账 ".$money." 元" ;
				if($this->config['company_pay_user_percent']>0){
					$data_companypay['desc'] .= '|手续费 '.$money*($this->config['company_pay_user_percent'])/100 .' 比例 '.$this->config['company_pay_user_percent'].'%';
				}
				$data_companypay['status'] = 0;
				$data_companypay['add_time'] = time();

				$use_result = D('User')->user_money($user_info['uid'],$money,'提款 '.$money.' 扣除余额',0,0,1);

				if($use_result['error_code']){

					$this->error_tips($use_result['msg']);
				}else{
					M('Withdraw_list')->add($data_companypay);
					$this->success("申请成功，请等待审核！");
				}

			}
			$this->display();
		}

		public  function  get_bank_name(){
			$card_number = $_POST['card_number'];
			require_once APP_PATH . 'Lib/ORG/BankList.class.php';
			if($res = $this->bankInfo($card_number,$bankList)){
				$this->success($res);
			}else{
				$this->error('没有查询到相关银行');
			}
		}

		function bankInfo($card,$bankList)
		{
			$card_8 = substr($card, 0, 8);
			if (isset($bankList[$card_8])) {
				return $bankList[$card_8];
			}
			$card_6 = substr($card, 0, 6);
			if (isset($bankList[$card_6])) {
				return $bankList[$card_6];

			}
			$card_5 = substr($card, 0, 5);
			if (isset($bankList[$card_5])) {
				return $bankList[$card_5];

			}
			$card_4 = substr($card, 0, 4);
			if (isset($bankList[$card_4])) {
				return $bankList[$card_4];

			}
			return null;
		}

	}