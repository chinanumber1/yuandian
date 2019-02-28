<?php
class DepositAction extends BaseAction{
	public function allinyun_back(){

		// preg_match('/rps\=.*.?/is', urldecode($_SERVER['QUERY_STRING']), $str);
		// $rps = json_decode(str_replace('rps=','',$str[0]),true);
		$rps = $_SERVER['original_get'];

		$rps = json_decode($rps['rps'],true);

		// import('@.ORG.AccountDeposit.AccountDeposit');
		// $deposit = new AccountDeposit('Allinyun');
		// $allyun = $deposit->getDeposit();


		//$verify_res = $allyun->sign($rps['sysid'],json_encode($rps_['returnValue']),$rps['timestamp']);


		// $verify_res = $allyun->verify($rps['sign'],$rps['sysid'].json_encode($rps_['returnValue']).$rps['timestamp']);

		if($rps['status']=='OK' && $rps['signedValue']['ContractNo']!=''){
			if(M('User_allinyun_info')->where(array('bizUserId'=>$rps['signedValue']['bizUserId']))->setField('signStatus',1)){
				if($_GET['source']==1){
					redirect(C('config.site_url').'/wap.php?c=setAccountDeposit&a=sign_success');
				}else{
					redirect(C('config.site_url').'/Index.php?c=setAccountDeposit&a=sign_success');
				}
			}
		}
		if($_GET['order_id']){
			$ordreid = $_GET['order_id'];

			$ordreid = explode('_',$ordreid);
			$order_type = $ordreid[0];
			$order_id = $ordreid[1];

			$order = $this->get_orderid(ucfirst($order_type).'_order',$order_id);
			// fdump($order,'order');
			import('@.ORG.AccountDeposit.AccountDeposit');
			$deposit = new AccountDeposit('Allinyun');
			$allyun = $deposit->getDeposit();
			$allinyun = M('User_allinyun_info')->where(array('uid'=>$order['uid']))->find();
			$allyun->setUser($allinyun);
			$res = $allyun->OrderDetail($_GET);
			// fdump($res,'order',1);
			$ordreid = $res['signedResult']['bizOrderNo'];
			$orderNo = $res['signedResult']['orderNo'];
			$payment_money = floatval($_GET['amount'])/100;
			$status = $res['status'];


			//主动去查
			if($status=='OK' && $res['signedResult']['orderStatus']==4){
				$this->allinyun_pay_back($order_type,$order_id,$orderNo,$payment_money,$_GET['source']);
			}else{
				if($_GET['source']==2){
					header('Content-type: application/json');
					$pay_info = array(
							'errorCode'=>'20130047',
							'errorMsg'=>'订单未支付',
					);
					echo json_encode($pay_info);die;
				}else{
					$this->back_url($order_type,$order_id,$_GET['source']);
				}
			}
		}
		if(empty($rps['method'])){

			if($_GET['source']==1){
				redirect($this->config['site_url'].'/wap.php?c=SetAccountDeposit&a=index');
			}else{
				redirect($this->config['site_url'].'/index.php?g=User&c=Index&a=index');
			}
		}

		switch($rps['method']){
			case 'pay':

				// die;
				$orderid = $rps['returnValue']['bizOrderNo'];
				$orderid = explode('_',$orderid);
				$order_type = $orderid[0];
				$order_id = $orderid[1];
				$source = $orderid[2]?$orderid[2]:1;
				$orderNo = $rps['returnValue']['orderNo'];
				$payment_money = bcdiv($rps['returnValue']['amount'],100);
				$status = $rps['status'];

				if($status=='OK'){
					$this->allinyun_pay_back($order_type,$order_id,$orderNo,$payment_money,$source);
				}else{

				}
				break;
			case 'balance_pay':

				$orderid = $rps['bizOrderNo'];

				$orderid = explode('_',$orderid);

				$order_type = $orderid[0];
				$order_id = $orderid[1];


				// $payment_money = $rps['amount']/100;
				// $status = $rps['status'];
				import('@.ORG.AccountDeposit.AccountDeposit');
				$deposit = new AccountDeposit('Allinyun');
				$allyun = $deposit->getDeposit();
				$now_order =$this->get_orderid(ucfirst($order_type).'_order',$order_id);
				$allinyun = M('User_allinyun_info')->where(array('uid'=>$now_order['uid']))->find();
				$allyun->setUser($allinyun);
				$arr['order_id'] = $rps['bizOrderNo'];
				$res = $allyun->OrderDetail($arr);
				$orderNo = $res['signedResult']['orderNo'];
				$payment_money = $rps['amount']/100;
				$status = $rps['status'];
				$source = 1;
				if($status=='OK'){
					$this->allinyun_pay_back($order_type,$order_id,$orderNo,$payment_money,$source);
				}else{

				}
				break;
		}


	}


	public function allinyun_pay_back($order_type,$order_id,$orderNo,$payment_money,$source=1,$extendInfo=''){

		switch($order_type){
			case 'group':
				$now_order =$this->get_orderid('Group_order',$order_id);
				break;
			case 'meal':
				$now_order =$this->get_orderid('Meal_order',$order_id);
				break;
			case 'takeout':
			case 'food':
			case 'foodPad':
				$now_order =$this->get_orderid('Meal_order',$order_id);
				break;
			case 'weidian':
				$now_order = D('Weidian_order')->where(array('orderid'=>$order_id))->find();
				$now_order =$this->get_orderid('Weidian_order',$order_id);
				break;
			case 'recharge':
				$now_order =$this->get_orderid('User_recharge_order',$order_id);
				break;
			case 'appoint':
				$now_order =$this->get_orderid('Appoint_order',$order_id);
				break;
			case 'waimai':
				$now_order =$this->get_orderid('Waimai_order',$order_id);
				break;
			case 'wxapp':
				$now_order =$this->get_orderid('Wxapp_order',$order_id);
				break;
			case 'store':
				$now_order =$this->get_orderid('Store_order',$order_id);
				break;
			case 'shop':
			case 'mall':
				$now_order = $this->get_orderid('Shop_order', $order_id);
				break;
			case 'plat':
				$now_order = $this->get_orderid('Plat_order', $order_id);
				break;
			case 'balance-appoint':
				$now_order = $this->get_orderid('Appoint_order', $order_id);
				break;
			default:
				$this->error_tips('非法的订单');
		}


		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}
		$now_order['order_type'] = $order_type;
		if($now_order['paid']==1 &&$now_order['order_type']!='balance-appoint'){
			switch($order_type){
				case 'group':
					$redirctUrl = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$now_order['order_id'];
					break;
				case 'meal':
					$redirctUrl = C('config.site_url').'/wap.php?c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
					break;
				case 'takeout':
					$redirctUrl = C('config.site_url').'/wap.php?c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
					break;
				case 'food':
				case 'foodPad':
					$redirctUrl = C('config.site_url').'/wap.php?c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
					break;
				case 'weidian':
					$redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']));
					$this->NoticeWDAsyn($now_order['orderid']);
					break;
				case 'appoint':
					$redirctUrl = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					break;
				case 'waimai':
					$redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
					break;
				case 'recharge':
					$redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
					break;
				case 'wxapp':
					$redirctUrl = C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
					break;
				case 'store':
					$redirctUrl = C('config.site_url').'/wap.php?c=My&a=store_order_detail&order_id='.$now_order['order_id'];
					break;
				case 'shop':
					$redirctUrl = C('config.site_url').'/wap.php?c=Shop&a=status&order_id=' . $now_order['order_id'];
					break;
				case 'mall':
					$redirctUrl = C('config.site_url').'/wap.php?c=Mall&a=status&order_id=' . $now_order['order_id'];
					break;
				case 'plat':
					$redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
					break;
				case 'balance-appoint':
					$redirctUrl = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					break;
			}
			if($source==2){
				header('Content-type: application/json');
				$pay_info =  array(
						'errorCode'=>0,
						'errorMsg'=>'success',
						'result'=>array(
								'url'=>$redirctUrl
						),
				);
				echo json_encode($pay_info);die;
			}else if($source==0){
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=index';
				redirect($redirctUrl);
			}else{
				redirect($redirctUrl);
			}
		}

		if($extendInfo[1]=='pc'){
			$source = 0;
		}else if($extendInfo[1]=='wap'){
			$source = 1;
		}

		$go_query_param['order_param'] = array(
				'order_id' => $order_id,
				'third_id' => $orderNo,
				'pay_money' => $payment_money,
				'pay_type' => $extendInfo[0]?$extendInfo[0]:'allinyun',
				'is_mobile' =>$source ,
				'is_own'=>10,
		);

		$now_order['is_mobile_pay'] = $go_query_param['order_param'] ['is_mobile'];
		switch($order_type){
			case 'group':
				D('Group_order')->after_pay($go_query_param['order_param']);
				break;
			case 'meal':
			case 'takeout':
			case 'food':
			case 'foodPad':
				D('Meal_order')->after_pay($go_query_param['order_param'], $order_type);
				break;
			case 'weidian':
				$pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
				if(($pay_info['error']==0) && isset($pay_info['url'])){  /***异步通知***/
					$this->NoticeWDAsyn($now_order['orderid']);
				}
				break;
			case 'recharge':
				$res = D('User_recharge_order')->after_pay($go_query_param['order_param']);
				break;
			case 'waimai':
				D('Waimai_order')->after_pay($go_query_param['order_param']);
				break;
			case 'appoint':

				D('Appoint_order')->after_pay($go_query_param['order_param']);

				break;
			case 'balance-appoint':

				D('Appoint_order')->balance_after_pay($go_query_param['order_param']);

				break;
			case 'wxapp':
				D('Wxapp_order')->after_pay($go_query_param['order_param']);
				break;
			case 'store':
				D('Store_order')->after_pay($go_query_param['order_param']);
				break;
			case 'shop':
			case 'mall':
				D('Shop_order')->after_pay($go_query_param['order_param']);
				break;
			case 'plat':
				D('Plat_order')->after_pay($go_query_param['order_param']);
				break;
		}


		switch($order_type){
			case 'group':
				$redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
				break;
			case 'meal':
				$redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
				break;
			case 'takeout':
				$redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
				break;
			case 'food':
			case 'foodPad':
				$redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
				break;
			case 'weidian':
				$redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
				$this->NoticeWDAsyn($now_order['orderid']);
				break;
			case 'appoint':
			case 'balance-appoint':
				$redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
				break;
			case 'waimai':
				$redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
				break;
			case 'recharge':
				$redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
				break;
			case 'wxapp':
				$redirctUrl = C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
				break;
			case 'store':
				$redirctUrl = C('config.site_url').'/wap.php?c=My&a=store_order_detail&order_id='.$now_order['order_id'];
				break;
			case 'shop':
				$redirctUrl = C('config.site_url').'/wap.php?c=Shop&a=status&order_id=' . $now_order['order_id'];
				break;
			case 'mall':
				$redirctUrl = C('config.site_url').'/wap.php?c=Mall&a=status&order_id=' . $now_order['order_id'];
				break;
			case 'plat':
				$redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
				break;
		}
		if($go_query_param['error'] == 1){
			$this->error_tips('校验时发生错误！'.$go_query_param['msg'],$redirctUrl);
		}else if($source==2){
			header('Content-type: application/json');
			$pay_info =  array(
					'errorCode'=>0,
					'errorMsg'=>'success',
					'result'=>array(
							'url'=>$redirctUrl
					),
			);
			echo json_encode($pay_info);die;
		}else{
			redirect($redirctUrl);
		}
	}

	public function back_url($order_type,$order_id,$source=1){
		switch($order_type){
			case 'group':
				$now_order =$this->get_orderid('Group_order',$order_id);
				break;
			case 'meal':
				$now_order =$this->get_orderid('Meal_order',$order_id);
				break;
			case 'takeout':
			case 'food':
			case 'foodPad':
				$now_order =$this->get_orderid('Meal_order',$order_id);
				break;
			case 'weidian':
				$now_order = D('Weidian_order')->where(array('orderid'=>$order_id))->find();
				$now_order =$this->get_orderid('Weidian_order',$order_id);
				break;
			case 'recharge':
				$now_order =$this->get_orderid('User_recharge_order',$order_id);
				break;
			case 'appoint':
				$now_order =$this->get_orderid('Appoint_order',$order_id);
				break;
			case 'waimai':
				$now_order =$this->get_orderid('Waimai_order',$order_id);
				break;
			case 'wxapp':
				$now_order =$this->get_orderid('Wxapp_order',$order_id);
				break;
			case 'store':
				$now_order =$this->get_orderid('Store_order',$order_id);
				break;
			case 'shop':
			case 'mall':
				$now_order = $this->get_orderid('Shop_order', $order_id);
				break;
			case 'plat':
				$now_order = $this->get_orderid('Plat_order', $order_id);
				break;
			case 'balance-appoint':
				$now_order = $this->get_orderid('Appoint_order', $order_id);
				break;
			default:
				$this->error('非法的订单');
		}


		if(empty($now_order)){
			$this->error('该订单不存在');
		}
		$now_order['order_type'] = $order_type;

		if($source){

			switch($order_type){
				case 'group':
					$redirctUrl = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$now_order['order_id'];
					break;
				case 'meal':
					$redirctUrl = C('config.site_url').'/wap.php?c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
					break;
				case 'takeout':
					$redirctUrl = C('config.site_url').'/wap.php?c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
					break;
				case 'food':
				case 'foodPad':
					$redirctUrl = C('config.site_url').'/wap.php?c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
					break;
				case 'weidian':
					$redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']));
					$this->NoticeWDAsyn($now_order['orderid']);
					break;
				case 'appoint':
					$redirctUrl = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					break;
				case 'waimai':
					$redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
					break;
				case 'recharge':
					$redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
					break;
				case 'wxapp':
					$redirctUrl = C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
					break;
				case 'store':
					$redirctUrl = C('config.site_url').'/wap.php?c=My&a=store_order_detail&order_id='.$now_order['order_id'];
					break;
				case 'shop':
					$redirctUrl = C('config.site_url').'/wap.php?c=Shop&a=status&order_id=' . $now_order['order_id'];
					break;
				case 'mall':
					$redirctUrl = C('config.site_url').'/wap.php?c=Mall&a=status&order_id=' . $now_order['order_id'];
					break;
				case 'plat':
					$redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
					break;
				case 'balance-appoint':
					$redirctUrl = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					break;
			}
			redirect($redirctUrl);exit;

		}else{


			switch($order_type){
				case 'group':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
					break;
				case 'recharge':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
					break;
				case 'appoint':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
					break;
				case 'waimai':
					$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
					break;
				case 'shop':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
					break;
			}
			redirect($redirctUrl);exit;
		}

	}

	public function get_orderid($table,$orderid,$offline=0){
		$order =  D($table);
		$tmp_orderid = D('Tmp_orderid');
		if($offline){
			$now_order = $order->where(array('orderid'=>$orderid))->find();
		}else{
			$now_order = $order->where(array('orderid'=>$orderid))->find();
			if(empty($now_order)){
				$res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
				$now_order = $order->where(array('order_id'=>$res['order_id']))->find();
				fdump(M(),'order1',1);
				$order->where(array('order_id'=>$res['order_id']))->setField('orderid',$orderid);
				$now_order['orderid']=$orderid;
			}
		}
		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}else{
			//            $tmp_orderid->where(array('order_id'=>$now_order['order_id']))->delete();
		}

		return $now_order;
	}

	public function allinyun_notice(){

		fdump($_POST,'NOTCT',1);



		import('@.ORG.AccountDeposit.AccountDeposit');
		$deposit = new AccountDeposit('Allinyun');
		$allyun = $deposit->getDeposit();



		// $verify_res = $allyun->sign('100009000325','{"status":"OK","returnValue":{"buyerBizUserId":"user_0000039782","amount":1,"bizOrderNo":"recharge_1805300953474000039782_1","payDatetime":"2018-05-30 09:53:56","orderNo":"1001642799315046400","extendInfo":""},"service":"OrderService","method":"pay"}','2018-05-30 09:53:56');



		$verify_res = $allyun->verify($_POST['sign'],$_POST['sysid'].html_entity_decode($_POST['rps']).$_POST['timestamp']);


		if($verify_res){
			$rps = json_decode(html_entity_decode($_POST['rps']),true);

			if($rps['status']!='OK'){
				return ;
			}

			switch($rps['method']){
				case 'signContract':

					if(strpos($rps['returnValue']['bizUserId'],$this->config['allinyun_mer_prefix'])!==false){
						M('Merchant_allinyun_info')->where(array('bizUserId'=>$rps['returnValue']['bizUserId']))->setField('sign_status',1);
					}else{
						M('User_allinyun_info')->where(array('bizUserId'=>$rps['returnValue']['bizUserId']))->setField('signStatus',1);
						fdump(M(),'verify_reee',1);
					}
					break;

				case 'pay':
					$orderid = $rps['returnValue']['bizOrderNo'];
					$orderid = explode('_',$orderid);
					$order_type = $orderid[0];
					$order_id = $orderid[1];
					$source = $orderid[2]?$orderid[2]:1;
					$orderNo = $rps['returnValue']['orderNo'];
					$payment_money = $rps['returnValue']['amount']/100;
					$status = $rps['status'];
					$extendInfo = explode(',',$rps['returnValue']['extendInfo']);
					$this->allinyun_pay_back($order_type,$order_id,$orderNo,$payment_money,$source,$extendInfo);

					break;
				case 'setPayPwd':
					if(strpos($rps['returnValue']['bizUserId'],C('config.allinyun_mer_prefix'))!==false){
						// M('Merchant_allinyun_info')->where(array('bizUserId'=>$rps['returnValue']['bizUserId']))->setField('setPayPwd',1);
					}else{
						M('User_allinyun_info')->where(array('bizUserId'=>$rps['returnValue']['bizUserId']))->setField('setPwd',1);
					}
					break;
				// case 'recharge':

				// $ordreid = $rps['returnValue']['bizOrderNo'];
				// $ordreid = explode('_',$ordreid);
				// $order_type = $ordreid[0];
				// $order_id = $ordreid[1];
				// $source = $ordreid[2];
				// $orderNo = $rps['returnValue']['orderNo'];
				// $payment_money = bcdiv($rps['returnValue']['amount'],100);
				// $status = $rps['status'];
				// $this->allinyun_pay_back($order_type,$order_id,$orderNo,$payment_money);
				// break;
			}
		}

	}


}
	
	