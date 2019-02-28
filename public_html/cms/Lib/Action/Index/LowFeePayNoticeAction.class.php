<?php
class LowFeePayNoticeAction extends BaseAction{
	public function order_back_notice(){
		fdump($_GET,'get');
		$order_type = $_GET['order_type'];
		$order_id = $_GET['order_id'];
		$now_order = $this->get_now_order($order_id,$order_type);

		if($now_order['paid']==1){
			$redirctUrl = $this->back_url($order_type,$now_order,$_GET['source']);
			redirect($redirctUrl);
		}else {
			import('@.ORG.LowFeePay');
			$order['orderNo'] = $order_type . '_' . $now_order['orderid'].($_GET['is_own']?'_1':'');
			$lowfeepay = new LowFeePay('juhepay');
			if($now_order['mer_id']){
				 $mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$now_order['mer_id']))->find();
				if(!empty($mer_juhe)){ 
					$lowfeepay->userId =$mer_juhe['userid'];
				}
			}
			$go_pay_param = $lowfeepay->check($order);
			fdump($order,'get_go');
			fdump($go_pay_param,'get_go',1);
			$this->after_notice($go_pay_param);
		}


	}

	public function order_notice(){
		
		$notice_data = file_get_contents("php://input");
		// $notice_data= '{"md5Info":"F67ADD4427C2768B819BF23B164876A3","orderAmount":"0.01","orderNo":"group_1806141615137700000007","orderStatus":"1","orderTime":"20180614161541","payType":"21","serialNo":"20180614000004717432","thridOrderNo":"2018061421001004980588880312"}';

		import('@.ORG.LowFeePay');
		$lowfeepay = new LowFeePay($_GET['pay_type']);
		$verfiy_result =$lowfeepay->notice($notice_data);


		if($verfiy_result['sign_result']){
			$go_pay_param = $verfiy_result['order_param'];
			
			$this->after_notice($go_pay_param);
		}
		header("HTTP/1.0 200 OK");

	}

	public function after_notice($go_pay_param){
		$order_type = $go_pay_param['order_type'];
		$now_order = $this->get_now_order($go_pay_param['order_id'],$order_type);
	
		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}
		$now_order['order_type'] = $order_type;
		if($now_order['paid']==1 && $order_type!='balance-appoint'){
			$redirctUrl = $this->back_url($order_type,$now_order,1);
			if($$go_pay_param['is_mobile']==2){
				header('Content-type: application/json');
				$pay_info =  array(
						'errorCode'=>0,
						'errorMsg'=>'success',
						'result'=>array(
								'url'=>$redirctUrl
						),
				);
				echo json_encode($pay_info);die;
			}else if($$go_pay_param['is_mobile']==0){
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=index';
				redirect($redirctUrl);
			}else{
				redirect($redirctUrl);
			}
		}

		$now_order['is_mobile_pay'] = $go_pay_param ['is_mobile'];
		switch($order_type){
			case 'group':
				$res = D('Group_order')->after_pay($go_pay_param);
			
				break;
			case 'meal':
			case 'takeout':
			case 'food':
			case 'foodPad':
				D('Meal_order')->after_pay($go_pay_param, $order_type);
				break;
			case 'weidian':
				$pay_info = D('Weidian_order')->after_pay($go_pay_param);
				if(($pay_info['error']==0) && isset($pay_info['url'])){  /***异步通知***/
					$this->NoticeWDAsyn($now_order['orderid']);
				}
				break;
			case 'recharge':
				$res = D('User_recharge_order')->after_pay($go_pay_param);
				break;
			case 'waimai':
				D('Waimai_order')->after_pay($go_pay_param);
				break;
			case 'appoint':

				D('Appoint_order')->after_pay($go_pay_param);

				break;
			case 'balance-appoint':

				D('Appoint_order')->balance_after_pay($go_pay_param);

				break;
			case 'wxapp':
				D('Wxapp_order')->after_pay($go_pay_param);
				break;
			case 'store':
				D('Store_order')->after_pay($go_pay_param);
				break;
			case 'shop':
			case 'mall':
				D('Shop_order')->after_pay($go_pay_param);
				break;
			case 'plat':
				D('Plat_order')->after_pay($go_pay_param);
				break;
		}


		$redirctUrl = $this->back_url($order_type,$now_order,1);
		if($go_pay_param['error'] == 1){
			$this->error_tips('校验时发生错误！'.$go_pay_param['msg'],$redirctUrl);
		}else if($$go_pay_param['is_mobile']==2){
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

	public function  get_now_order($order_id,$order_type){
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
				fdump($now_order,'ss');
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
				$now_order =array();
		}
		return $now_order;

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



	public function back_url($order_type,$now_order,$is_wap=1){
		if($is_wap){
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

		}
		return  $redirctUrl;
	}


}
	
	