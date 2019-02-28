<?php
class Appoint_orderModel extends Model{
	/*获取订单详情*/
	public function get_order_detail_by_id($uid, $order_id, $is_wap=false, $check_user=true){
		$database_appoint = D('Appoint');
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_appoint_product = D('Appoint_product');
		$database_user = D('User');
		if($check_user){
			$condition_appoint['uid'] = $uid;
		} else {
			$condition_appoint['uid'] = '';
		}
		$condition_appoint['_string'] = "order_id='{$order_id}' OR orderid='{$order_id}'";
		$now_order = $this->field(true)->where($condition_appoint)->find();
		if(empty($now_order)){
			return null;
		}
		$where['appoint_id'] = $now_order['appoint_id'];
		$appoint_info = $database_appoint->field(true)->where($where)->find();
		$tmp_pic_arr = explode(';', $appoint_info['pic']);
		$appoint_image_class = new appoint_image();
		$now_order['list_pic'] = $appoint_image_class->get_image_by_path($tmp_pic_arr[0],'s');
		$now_order['appoint_name'] = $appoint_info['appoint_name'];
		$now_order['appoint_price'] = $appoint_info['appoint_price'];
		$now_order['appoint_type'] = $appoint_info['appoint_type'];
		$now_order['start_time'] = $appoint_info['start_time'];
		$now_order['end_time'] = $appoint_info['end_time'];
		$now_order['reply_count'] = $appoint_info['reply_count'];
		$now_order['score_all'] = $appoint_info['score_all'];
		$now_order['score_mean'] = $appoint_info['score_mean'];
		$now_order['payment_status'] = $appoint_info['payment_status'];
		$now_order['is_appoint_price'] = $appoint_info['is_appoint_price'];
		$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], $now_order['is_mobile_pay']);
		$now_order['product_pay_type_txt'] = D('Pay')->get_pay_name($now_order['product_pay_type'], $now_order['is_mobile_pay']);

		if($now_order['pay_type_str']!='' && $now_order['is_own']>0){
			$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
			if($now_order['is_own']==2){
				$now_order['pay_type_txt'] .= '(平台服务商子商家：'.$now_merchant['name'].')';
				$now_order['product_pay_type_txt'] .= '(平台服务商子商家：'.$now_merchant['name'].')';
			}else{
				$now_order['pay_type_txt'] .= '(商家：'.$now_merchant['name'].')';
				$now_order['product_pay_type_txt'] .= '(商家：'.$now_merchant['name'].')';
			}
		}


		$now_order['url'] = $database_appoint->get_appoint_url($now_order['appoint_id'], $is_wap);
		$condition_user['uid'] = $uid;
		$user_info = $database_user->field('`phone`')->where($condition_user)->find();
		$now_order['phone'] = $user_info['phone'];

		if(!empty($now_order['product_id'])){
			$product_detail = $database_appoint_product->get_product_info($now_order['product_id']);
			if($product_detail['status']){
				$now_order['product_detail'] = $product_detail['detail'];
			}
		}
		$Map['appoint_order_id'] = $now_order['order_id'];
		$appoint_visit_order_info = $database_appoint_visit_order_info->appoint_visit_order_detail($Map);
		if($appoint_visit_order_info){
			$now_order['worker_detail'] = $appoint_visit_order_info['detail'];
		}
		return $now_order;
	}

	/**获取订单分类**/
	public function get_order_cate($order_id){
		$appoint_id = $this->field('appoint_id')->where(array('order_id'=>$order_id))->find();
		$cat_id = D('Appoint')->field('cat_id')->where(array('appoint_id'=>$appoint_id))->find();
		$appoint_cate = D('Appoint_category')->field('cat_id,cat_name')->where(array('cat_id'=>$cat_id))->find();
		return $appoint_cate;
	}

	// 下单
	public function save_post_form($appoint,$uid,$order_id,$merchant_workers_id = 0){
		$data_appoint_order['uid'] = $uid;
		$data_appoint_order['appoint_id'] = $appoint['appoint_id'];
		$data_appoint_order['mer_id'] = $appoint['mer_id'];
		$data_appoint_order['order_time'] = $_SERVER['REQUEST_TIME'];
		$data_appoint_order['payment_money'] = $appoint['payment_money'];
		$data_appoint_order['appoint_price'] = $appoint['appoint_price'];
		$data_appoint_order['payment_status'] = $appoint['payment_status'];
		$data_appoint_order['content'] = $appoint['content'] ? $appoint['content'] : '';
		$data_appoint_order['appoint_type'] = $appoint['appoint_type'];
		$data_appoint_order['product_id'] = $appoint['product_id'];
		$data_appoint_order['cue_field'] = $appoint['cue_field'];
		if(!$appoint['product_type']){
			$data_appoint_order['appoint_time'] = $appoint['appoint_time'];
			$data_appoint_order['appoint_date'] = $appoint['appoint_date'];
		}
		if($appoint['payment_status']==0){

			$pass_arr = array(
					date('y',$_SERVER['REQUEST_TIME']),
					date('m',$_SERVER['REQUEST_TIME']),
					date('d',$_SERVER['REQUEST_TIME']),
					date('H',$_SERVER['REQUEST_TIME']),
					date('i',$_SERVER['REQUEST_TIME']),
					date('s',$_SERVER['REQUEST_TIME']),
					mt_rand(10,99),
			);
			shuffle($arr);
			$data_appoint_order['appoint_pass'] = implode('',$pass_arr);
		}


		$data_appoint_order['store_id'] = $appoint['store_id'];
		$data_appoint_order['merchant_worker_id'] = $merchant_workers_id ? $merchant_workers_id  : 0;
		$data_appoint_order['order_name'] = $appoint['appoint_name'];
		$data_appoint_order['merchant_assign_time'] =  time();
		if(!$data_appoint_order['payment_status']){
			$data_appoint_order['paid'] = 1;
		}

		if($_SESSION['now_village_bind']['village_id']>0&&M('House_village_appoint')->where(array('village_id'=>$_SESSION['now_village_bind']['village_id'],'appoint_id'=>$appoint['appoint_id']))->find()){
			$data_appoint_order['village_id'] = $_SESSION['now_village_bind']['village_id'];
		}
		if($appoint['extra_pay_price']>0){
			$data_appoint_order['extra_price'] = $appoint['extra_pay_price'];
		}

		if(!$appoint['is_store']){
			$data_appoint_order['type'] = 2;
		}

		if($appoint['product_id'] > 0){
			$database_appoint_product = D('Appoint_product');
			$product_where['id'] = $appoint['product_id'];
			$now_product = $database_appoint_product->where($product_where)->find();
			if($now_product){
				$data_appoint_order['product_name'] = $now_product['name'];
				$data_appoint_order['product_content'] = $now_product['content'];
				$data_appoint_order['product_payment_price'] = $now_product['payment_price'];
				$data_appoint_order['product_price'] = $now_product['price'];
				$data_appoint_order['product_use_time'] = $now_product['use_time'];
			}

		}

		$now_user = D('User')->get_user($uid);
		if(empty($now_user)){
			return array('error'=>1,'msg'=>'未获取到您的帐号信息，请重试！','url'=>C('config.site_url').'/index.php?c=Login&index');
		}
		if($order_id){
			$condition_group_order['order_id'] = $order_id;
			$condition_group_order['uid'] = $uid;

			$save_result = $this->where($condition_group_order)->data($data_appoint_order)->save();
			if($save_result){
			    //小票打印start
			    $printHaddle = new PrintHaddle();
			    $printHaddle->printit($order_id, 'appoint_order', 1);
				//小票打印end

				return array('error'=>0,'msg'=>'订单修改成功！','order_id'=>$order_id);
			}else{
				return array('error'=>1,'msg'=>'订单修改失败！请重试','order_id'=>$order_id);
			}
		}else{
			//上门预约服务工作人员处理start
			$databaes_appoint_visit_order_info = D('Appoint_visit_order_info');
			$database_merchant_workers = D('Merchant_workers');
			$order_id = $this->data($data_appoint_order)->add();
		
			$appoint_visit_order_info['appoint_order_id'] = $order_id;
			$appoint_visit_order_info['merchant_worker_id'] = $merchant_workers_id ? $merchant_workers_id : 0 ;
			$appoint_visit_order_info['uid'] = $uid;
			$appoint_visit_order_info['add_time'] = time();
			$appoint_visit_order_info['appoint_order_id'] = $order_id;
			$appoint_visit_order_info['service_address'] = $data_appoint_order['appoint_type'] == 1 ? ($appoint['cue_field'] ? $appoint['cue_field'] : '') : '';
			$appoint_visit_order_info_insert_id = $databaes_appoint_visit_order_info->data($appoint_visit_order_info)->add();
			if(!$appoint_visit_order_info_insert_id){
				return array('error'=>1,'msg'=>'预约订单产生失败！');
			}else{
				$worker_where['merchant_worker_id'] = $merchant_workers_id;
				$database_merchant_workers->where($worker_where)->setInc('appoint_num');
			}
			//上门预约服务工作人员处理end

			if($order_id){
				if ($_SESSION['openid']) {
					$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$order_id;
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $_SESSION['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $order_id, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $appoint['appoint_name'], 'remark' => '您的该次预约下单成功，感谢您的使用！'));
				}

				$sms_data = array('mer_id' => $appoint['mer_id'], 'store_id' => 0, 'type' => 'appoint');
				if (C('config.sms_place_order') == 1 || C('config.sms_place_order') == 3) {
					$sms_data['uid'] = $uid;
					$sms_data['mobile'] = $now_user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您在' . date("Y-m-d H:i:s") . '时，成功预约了' . $appoint['appoint_name'] . '，已成功生成订单，订单号：' . $order_id;
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_place_order') == 2 || C('config.sms_place_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $appoint['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '有份新的' . $appoint['appoint_name'] . '被预约，订单号：' . $order_id . '请您注意查看并处理!';
					Sms::sendSms($sms_data);
				}


				$sms_data = array('mer_id' => $appoint['mer_id'], 'store_id' => $appoint['store_id'], 'type' => 'appoint');
				if((C('config.worker_sms_place_order') == 1) && !empty($merchant_workers_id)){
					$worker_where['merchant_worker_id'] = $merchant_workers_id;
					$now_worker = $database_merchant_workers->where($worker_where)->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $now_worker['mobile'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '用户在' . date('Y-m-d H:i',time()) . '时，购买了预约服务，已成功生成订单，订单号：'. $order_id;
					Sms::sendSms($sms_data);
				}

				$staff_where['store_id'] = $appoint['store_id'];
				$staff_arr = D('Merchant_store_staff')->where($staff_where)->field('tel,openid')->select();

				if($staff_arr){
					foreach ($staff_arr as $v) {
						$sms_data = array('mer_id' => $appoint['mer_id'], 'store_id' => $appoint['store_id'], 'type' => 'appoint');
						$sms_data['uid'] = 0;
						$sms_data['mobile'] = $v['tel'];
						$sms_data['sendto'] = 'merchant';
						$sms_data['content'] = '有份新的预约订单，订单号：' . $order_id . ' 请您注意查看并处理!';
						Sms::sendSms($sms_data);

						if ($v['openid']) {
							$href = C('config.site_url') . '/wap.php?g=Wap&c=Storestaff&a=appoint_edit&order_id='.$order_id;
							$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
							$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $v['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $order_id, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $appoint['appoint_name'], 'remark' => '您有新的预约，您注意查看并处理'));
						}
					}
				}


				//小票打印start
				if(!$appoint['payment_money']){
				    $printHaddle = new PrintHaddle();
				    $printHaddle->printit($order_id, 'appoint_order', 1);
				}
				//小票打印end


				return array('error'=>0,'msg'=>'订单产生成功！','order_id'=>$order_id);
			}else{
				return array('error'=>1,'msg'=>'订单产生失败！请重试');
			}
		}
	}

	//平台自营下单
	public function platform_save_post_form($data){
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_appoint_category = D('Appoint_category');
		$data['order_time'] = $_SERVER['REQUEST_TIME'];
		$data['payment_money'] = 0;
		$insert_id = $this->data($data)->add();
		if($insert_id){
			$appoint_visit_order_info['appoint_order_id'] = $insert_id;
			$appoint_visit_order_info['merchant_worker_id'] =  0 ;
			$appoint_visit_order_info['uid'] = $data['uid'];
			$appoint_visit_order_info['add_time'] = time();
			$appoint_visit_order_info['type'] = 2;
			$appoint_visit_order_info['service_address'] = $data['cue_field'] ? $data['cue_field'] : '';
			$appoint_visit_order_info_insert_id = $database_appoint_visit_order_info->data($appoint_visit_order_info)->add();
			if(!$appoint_visit_order_info_insert_id){
				return array('error'=>1,'msg'=>'预约订单产生失败！');
			}

			$cat_info = $database_appoint_category->get_category_by_id($data['cat_id']);
			$sms_key = C('config.sms_key');
			if(isset($sms_key) && !empty($sms_key)){
				if ($_SESSION['user']['openid']) {
					$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$insert_id;
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $_SESSION['user']['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $insert_id, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $cat_info['cat_name'], 'remark' => '您的该次预约下单成功，感谢您的使用！'));
				}

				$database_user = D('User');
				$now_user = $database_user->get_user($data['uid']);
				$sms_data = array('mer_id' => 0, 'store_id' => 0, 'type' => 'appoint');
				$sms_data['uid'] = $now_user['uid'];
				$sms_data['mobile'] = $now_user['phone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '您在' . date("Y-m-d H:i:s") . '时，成功预约了' . $cat_info['cat_name'] . '，已成功生成订单，订单号：' . $insert_id;
				Sms::sendSms($sms_data);
			}

			return array('status'=>1,'msg'=>'订单产生成功！','order_id'=>$insert_id);
		}else{
			return array('status'=>0,'msg'=>'订单产生失败！请重试');
		}
	}

	public function get_order_by_id($uid,$order_id){
		$condition_group_order['order_id'] = $order_id;
		$condition_group_order['uid'] = $uid;
		$order = $this->field(true)->where($condition_group_order)->find();
		return $order;
	}
	public function get_pay_order($uid,$order_id,$is_web=false){
		$now_order = $this->get_order_by_id($uid,$order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}

		if(!empty($now_order['paid'])){
			if($is_web){
				return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
			}else{
				return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>U('My/appoint_order',array('order_id'=>$now_order['order_id'])));
			}
		}

		$now_group = D('Appoint')->get_appoint_by_appointId($now_order['appoint_id']);
		if(empty($now_group)){
			return array('error'=>1,'msg'=>'当前预约不存在或已过期！');
		}
		$imgs =explode(';', $now_group['pic']);
		foreach($imgs as &$v){
			$v = preg_replace('/,/','/',$v);
		}

		if($is_web){
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'store_id'			=>	$now_order['store_id'],
					'order_type'		=>	'appoint',
					'order_name'		=>	$now_group['appoint_name'],
					'order_num'			=>	$now_order['num'],
					'order_total_money'	=>	floatval($now_order['payment_money']),
					'order_content'    =>  array(
							0=>array(
									'name'  		=> $now_group['merchant_name'].'：'.$now_group['appoint_name'],
									'num'   		=> 1,
									'price' 		=> floatval($now_order['payment_money']),
									'money' 	=> floatval($now_order['payment_money']),
							)
					),
					'extra_price' =>$now_order['extra_price'],
					'extra_pay_price' =>$now_group['extra_pay_price'],
			);
		}else{
			if($now_order['product_id']) {
				$order_info = array(
						'order_id' => $now_order['order_id'],
						'appoint_id' => $now_order['appoint_id'],
						'mer_id' => $now_order['mer_id'],
						'store_id'			=>	$now_order['store_id'],
						'order_type' => 'appoint',
						'order_name' => $now_order['product_name'] ? $now_group['appoint_name'] . ' - ' . $now_order['product_name'] : $now_group['appoint_name'],
						'order_num' => $now_order['num'] ? $now_order['num'] : 0,
						'order_price' => $now_order['product_payment_price'] ? floatval($now_order['product_payment_price']) : floatval($now_order['payment_money']),
						'order_total_money' => $now_order['product_payment_price'] ? floatval($now_order['product_payment_price']) : floatval($now_order['payment_price']),
						'img' => C('config.site_url') . '/upload/appoint/' . $imgs[0],
						'discount_status' => false,
						'extra_price' =>$now_order['extra_price'],
						'extra_pay_price' =>$now_group['extra_pay_price'],
				);
			}else{
				$order_info = array(
						'order_id' => $now_order['order_id'],
						'appoint_id' => $now_order['appoint_id'],
						'mer_id' => $now_order['mer_id'],
						'store_id'			=>	$now_order['store_id'],
						'order_type' => 'appoint',
						'order_name' => $now_order['product_name'] ? $now_group['appoint_name'] . ' - ' . $now_order['product_name'] : $now_group['appoint_name'],
						'order_num' => $now_order['num'] ? $now_order['num'] : 0,
						'order_price' => $now_order['payment_money'],
						'order_total_money' =>  $now_order['payment_money'],
						'img' => C('config.site_url') . '/upload/appoint/' . $imgs[0],
						'discount_status' => false,
						'extra_price' =>$now_order['extra_price'],
						'extra_pay_price' =>$now_group['extra_pay_price'],
				);
			}
		}
		return array('error'=>0,'order_info'=>$order_info);

	}


	public function get_pay_balace_order($uid,$order_id,$is_web=false){
		$now_order = $this->get_order_by_id($uid,$order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}

		if(($now_order['paid'] != 1) && ($now_order['payment_status']==1)){
			return array('error'=>1,'msg'=>'当前订单未支付！','url'=>U('My/appoint_order',array('order_id'=>$now_order['order_id'])));
		}

		if(($now_order['paid'] == 1) && ($now_order['service_status'] == 1) && ($now_order['is_initiative'] == 1)){
			return array('error'=>1,'msg'=>'当前订单已支付完成！','url'=>U('My/appoint_order',array('order_id'=>$now_order['order_id'])));
		}


		$now_group = D('Appoint')->get_appoint_by_appointId($now_order['appoint_id']);
		if(empty($now_group)){
			return array('error'=>1,'msg'=>'当前预约不存在或已过期！');
		}
		$imgs =explode(';', $now_group['pic']);
		foreach($imgs as &$v){
			$v = preg_replace('/,/','/',$v);
		}

		if($is_web){
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'order_type'		=>	'balance-appoint',
					'order_name'		=>	$now_group['appoint_name'],
					'order_num'			=>	$now_order['num'],
					'order_total_money'	=>	$now_order['product_price'] - $now_order['product_payment_price'],
					'order_content'    =>  array(
							0=>array(
									'name'  		=> $now_group['merchant_name'].'：'.$now_group['appoint_name'],
									'num'   		=> 1,
									'price' 		=> floatval($now_order['payment_money']),
									'money' 	=> floatval($now_order['payment_money']),
							)
					),
					'extra_price' =>$now_order['extra_price'],
					'extra_pay_price' =>$now_group['extra_pay_price'],
			);
		}else{
			if($now_order['product_id']){
				if(($now_order['type'] == 2) || ($now_order['payment_status'] == 0)){
					$order_price = $now_order['product_price'];
				}else{
					$order_price = $now_order['product_price'] - $now_order['product_payment_price'];
				}
				$order_info = array(
						'order_id'			=>	$now_order['order_id'],
						'appoint_id'		=>	$now_order['appoint_id'],
						'mer_id'			=>	$now_order['mer_id'],
						'order_type'		=>	'balance-appoint',
						'order_name'		=>	$now_order['product_name'] ? $now_group['appoint_name'] . ' - ' . $now_order['product_name'] : $now_group['appoint_name'],
						'order_num'			=>	$now_order['num'] ? $now_order['num'] : 0,
						'order_price'		=>	$order_price,
						'order_total_money'	=>	$order_price,
						'img'				=> C('config.site_url').'/upload/appoint/'.$imgs[0],
						'extra_price' =>$now_order['extra_price'],
						'extra_pay_price' =>$now_group['extra_pay_price'],
				);
			}else{
				if(($now_order['type'] == 2) || ($now_order['payment_status'] == 0)){
					$order_price = $now_order['appoint_price'];
				}else{
					$order_price = $now_order['appoint_price'] - $now_order['payment_money'];
				}

				if($now_order['product_price']>0){
					$order_price = $now_order['product_price'] - $now_order['payment_money'];
				}

				$order_info = array(
						'order_id'			=>	$now_order['order_id'],
						'appoint_id'		=>	$now_order['appoint_id'],
						'mer_id'			=>	$now_order['mer_id'],
						'order_type'		=>	'balance-appoint',
						'order_name'		=>	$now_order['product_name'] ? $now_group['appoint_name'] . ' - ' . $now_order['product_name'] : $now_group['appoint_name'],
						'order_num'			=>	$now_order['num'] ? $now_order['num'] : 0,
						'order_price'		=>	$order_price,
						'order_total_money'	=>	$order_price,
						'img'				=> C('config.site_url').'/upload/appoint/'.$imgs[0],
						'extra_price' =>$now_order['extra_price'],
						'extra_pay_price' =>$now_group['extra_pay_price'],
				);
			}
		}
		return array('error'=>0,'order_info'=>$order_info);

	}

	// 已经约满的时间点
	public function get_appoint_num($appoint_id,$sum=1){
		$time = date('Y-m-d');
		$sql = "SELECT count(*) as appointNum,appoint_date,appoint_time from pigcms_appoint_order
				where appoint_date >='".$time."' and service_status=0 AND is_del!=5 AND appoint_id = ".$appoint_id."
				group by appoint_date,appoint_time
				having appointNum>=".$sum;
		$result = D('')->query($sql);
		return $result;
	}

	//计算预约人数
	public function get_appoint_remain_num($appoint_id){
		$time = date('Y-m-d');
		$sql = "SELECT count(*) as appointNum,appoint_date,appoint_time from pigcms_appoint_order
				where appoint_date >='".$time."' and service_status=0 AND merchant_worker_id = 0 AND appoint_id = ".$appoint_id."
				group by appoint_date,appoint_time";
		$result = D('')->query($sql);
		return $result;
	}

	//工作人员约满时间点
	public function get_worker_appoint_num($appoint_id , $merchant_worker_id){
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$where['merchant_worker_id'] = $merchant_worker_id;
		$appoint_order_id_arr = $database_appoint_visit_order_info->where($where)->getField('id,appoint_order_id');
		$result = array();
		if($appoint_order_id_arr){
			$time = date('Y-m-d');

			if($appoint_id){
				$sql = "SELECT count(*) as appointNum,appoint_date,appoint_time from pigcms_appoint_order
				where type = 0 AND appoint_date >='".$time."' and service_status=0 AND is_del=0 AND order_id in(".implode(',',$appoint_order_id_arr).")  AND appoint_id = ".$appoint_id."
				group by appoint_date,appoint_time";
			}else{
				$sql = "SELECT count(*) as appointNum,appoint_date,appoint_time from pigcms_appoint_order
				where type = 0 AND appoint_date >='".$time."' and service_status=0 AND is_del=0 AND order_id in(".implode(',',$appoint_order_id_arr).")
				group by appoint_date,appoint_time";
			}

			$result = D('')->query($sql);

		}

		return $result;
	}

	/*获得订单链接*/
	public function get_order_url($order_id,$is_wap=false){
		if($is_wap){
			return U('My/appoint_order',array('order_id'=>$order_id));
		}else{
			return U('User/Index/appoint_order_view',array('order_id'=>$order_id));
		}
	}
	public function wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user){
		//去除微信优惠的金额
		$pay_money = $order_info['order_total_money'];

		if(C('config.open_extra_price')==1){
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
			$pay_money +=$order_info['order_extra_price'];
		}
		if($pay_money == 0){
			return $this->wap_after_pay_before($order_info);
		}
		if($merchant_balance['card_discount']>0){
			$pay_money = sprintf("%.2f",$pay_money*$merchant_balance['card_discount']/10);
			//$pay_money = floor($pay_money*$merchant_balance['card_discount']*10)/100;
		}
		$merchant_balance['card_money'] = round($merchant_balance['card_money'] * 100) / 100;			//用户在商家所拥有的金额
		$merchant_balance['card_give_money'] = round($merchant_balance['card_give_money'] * 100) / 100;			//用户在商家所拥有的金额
		$data_group_order['pay_money'] = $pay_money;
		//判断优惠券

		//商家优惠券
		if($now_coupon['card_price']>0) {
			$data_group_order['card_id'] = $now_coupon['merc_id'];
			$data_group_order['card_price'] = $now_coupon['card_price'];
			if ($now_coupon['card_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_group_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['card_price'];
			$data_group_order['pay_money'] = $pay_money;
		}

		//系统优惠券
		if($now_coupon['coupon_price']>0){
			$data_group_order['coupon_id'] = $now_coupon['sysc_id'];
			$data_group_order['coupon_price'] = $now_coupon['coupon_price'];
			if ($now_coupon['coupon_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_group_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['coupon_price'];
			$data_group_order['pay_money'] = $pay_money;
		}


		$data_group_order['card_discount'] = $merchant_balance['card_discount'];


		if(!empty($order_info['score_deducte'])&&$order_info['use_score']){
			$data_group_order['score_used_count']  = $order_info['score_used_count'];
			$data_group_order['score_deducte']     = (float)$order_info['score_deducte'];
			if($order_info['score_deducte'] >= $pay_money){
				//扣除积分
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $order_info['score_deducte'];
			$data_group_order['pay_money'] = $pay_money;
			//$data_group_order['system_pay'] += $data_group_order['score_deducte'];
		}

		//判断商家余额
		if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_money'] >= $pay_money){
				$data_group_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['merchant_balance'] = $merchant_balance['card_money'];
			}
			$pay_money -= $merchant_balance['card_money'];
		}

		if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_give_money'] >= $pay_money){
				$data_group_order['card_give_money'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['card_give_money'] = $merchant_balance['card_give_money'];
			}
			$pay_money -= $merchant_balance['card_give_money'];
		}

		//判断帐户余额
		if(!empty($now_user['now_money'])&&$order_info['use_balance']){
			if($now_user['now_money'] >= $pay_money){
				$data_group_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		$data_group_order['pay_money'] = $pay_money;
		//在线支付

		$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
		if($order_result['error_code']){
			return $order_result;
		}

		return array('error_code'=>false,'pay_money'=>$pay_money);
	}


	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_user){
		//判断是否需要在线支付
		if(!$order_info['use_balance']){
			$now_user['now_money']=0;
		}
		if($now_user['now_money']+$order_info['score_deducte']< $order_info['order_total_money']){
			$online_pay = true;
		}else{
			$online_pay = false;
		}
		//不使用在线支付，直接使用用户余额。
		if(empty($online_pay)){
			$order_pay['balance_pay'] = $order_info['order_total_money']-$order_info['score_deducte'];
		}else{
			if(!empty($now_user['now_money'])){
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}

		if(!empty($order_info['score_deducte'])){
			$data_group_order['score_used_count']	= $order_info['score_used_count'];
			$data_group_order['score_deducte']      = (float)$order_info['score_deducte'];
		}

		//将已支付用户余额等信息记录到订单信息里
		if(!empty($order_pay['balance_pay'])){
			$data_group_order['balance_pay'] = $order_pay['balance_pay'];
		}
		if(!empty($data_group_order)){
//			$data_group_order['wx_cheap'] 			= 0;
			$data_group_order['card_id'] 			= 0;
			$data_group_order['merchant_balance'] 	= 0;
			$data_group_order['pay_money'] 	= 0;
			$condition_group_order['order_id'] = $order_info['order_id'];
			if(!$this->where($condition_group_order)->data($data_group_order)->save()){
				return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
		}

		if($online_pay){
			return array('error_code'=>false,'pay_money'=>$order_info['order_total_money'] - $now_user['now_money']-(float)$order_info['score_deducte']);
		}else{
			$order_param = array(
					'order_id' => $order_info['order_id'],
					'pay_type' => '',
					'third_id' => '',
					'is_mobile' => 0,
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'msg'=>'支付成功！','url'=>U('User/Index/appoint_order_view',array('order_id'=>$order_info['order_id'])));
		}
	}
	//手机端支付前订单处理



	//手机端余额支付
	public function wap_balace_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user){
		//去除微信优惠的金额
		$pay_money = $order_info['order_total_money'];
		$pay_money = sprintf("%.2f",$pay_money*$merchant_balance['card_discount']/10);
		$merchant_balance['card_money'] = round($merchant_balance['card_money'] * 100) / 100;			//用户在商家所拥有的金额
		$merchant_balance['card_give_money'] = round($merchant_balance['card_give_money'] * 100) / 100;			//用户在商家所拥有的金额
		$data_group_order['user_pay_money'] = $pay_money;
		$data_group_order['product_card_discount'] = $merchant_balance['card_discount'];
		if(C('config.open_extra_price')==1){
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
			$pay_money +=$order_info['order_extra_price'];
		}
		//判断优惠券

		//商家优惠券
		if($now_coupon['card_price']>0) {
			$data_group_order['product_card_id'] = $now_coupon['merc_id'];
			$data_group_order['product_card_price'] = $now_coupon['card_price'];
			if ($now_coupon['card_price'] >= $pay_money) {
				$order_result = $this->wap_pay_balace_save_order($order_info, $data_group_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info,true);
			}
			$pay_money -= $now_coupon['card_price'];
			$data_group_order['user_pay_money'] = $pay_money;
		}

		//系统优惠券
		if($now_coupon['coupon_price']>0){
			$data_group_order['product_coupon_id'] = $now_coupon['sysc_id'];
			$data_group_order['product_coupon_price'] = $now_coupon['coupon_price'];
			if ($now_coupon['coupon_price'] >= $pay_money) {
				$order_result = $this->wap_pay_balace_save_order($order_info, $data_group_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info,true);
			}
			$pay_money -= $now_coupon['coupon_price'];
			$data_group_order['user_pay_money'] = $pay_money;
		}




		if(!empty($order_info['score_deducte'])&&$order_info['use_score']){
			$data_group_order['product_score_used_count']  = $order_info['score_used_count'];
			$data_group_order['product_score_deducte']     = (float)$order_info['score_deducte'];
			if($order_info['score_deducte'] >= $pay_money){
				//扣除积分
				$order_result = $this->wap_pay_balace_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info,true);
			}
			$pay_money -= $order_info['score_deducte'];
			$data_group_order['user_pay_money'] = $pay_money;
		}

		//判断商家余额
		if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_money'] >= $pay_money){
				$data_group_order['product_merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_balace_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info,true);
			}else{
				$data_group_order['product_merchant_balance'] = $merchant_balance['card_money'];
			}
			$pay_money -= $merchant_balance['card_money'];
		}

		if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_give_money'] >= $pay_money){
				$data_group_order['product_card_give_money'] = $pay_money;
				$order_result = $this->wap_pay_balace_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info,true);
			}else{
				$data_group_order['product_card_give_money'] = $merchant_balance['card_give_money'];
			}
			$pay_money -= $merchant_balance['card_give_money'];
		}

		//判断帐户余额
		if(!empty($now_user['now_money'])&&$order_info['use_balance']){
			if($now_user['now_money'] >= $pay_money){
				$data_group_order['product_balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_balace_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info,true);
			}else{
				$data_group_order['product_balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		$data_group_order['user_pay_money'] = $pay_money;
		$order_result = $this->wap_pay_balace_save_order($order_info,$data_group_order);
		if($order_result['error_code']){
			return $order_result;
		}

		return array('error_code'=>false,'pay_money'=>$pay_money);
	}

	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info,$data_group_order){
		$data_group_order['coupon_id'] 			= !empty($data_group_order['coupon_id']) ? $data_group_order['coupon_id'] : 0;
		$data_group_order['coupon_price'] 			= !empty($data_group_order['coupon_price']) ? $data_group_order['coupon_price'] : 0;
		$data_group_order['card_id'] 			= !empty($data_group_order['card_id']) ? $data_group_order['card_id'] : 0;
		$data_group_order['card_price'] 			= !empty($data_group_order['card_price']) ? $data_group_order['card_price'] : 0;
		$data_group_order['merchant_balance'] 	= !empty($data_group_order['merchant_balance']) ? $data_group_order['merchant_balance'] : 0;
		$data_group_order['card_give_money'] 	= !empty($data_group_order['card_give_money']) ? $data_group_order['card_give_money'] : 0;
		$data_group_order['card_discount'] 	= !empty($data_group_order['card_discount']) ? $data_group_order['card_discount'] : 0;
		$data_group_order['balance_pay']	 	= !empty($data_group_order['balance_pay']) ? $data_group_order['balance_pay'] : 0;
		$data_group_order['score_used_count']  = !empty($data_group_order['score_used_count']) ? $data_group_order['score_used_count'] : 0;
		$data_group_order['score_deducte']     = !empty($data_group_order['score_deducte']) ? $data_group_order['score_deducte'] : 0;
		$data_group_order['pay_money']	 	    = !empty($data_group_order['pay_money']) ? (float)$data_group_order['pay_money'] : 0;
		$data_group_order['product_real_payment_price']	 	    = !empty($data_group_order['product_real_payment_price']) ? (float)$data_group_order['product_real_payment_price'] : 0;
		$data_group_order['score_discount_type']      = !empty($order_info['score_discount_type']) ? $order_info['score_discount_type'] : 0;
		$data_group_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
		$condition_group_order['order_id'] = $order_info['order_id'];

		if($this->where($condition_group_order)->data($data_group_order)->save()){
			return array('error_code'=>false,'msg'=>'保存订单成功！');
		}else{
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
	}


	public function wap_pay_balace_save_order($order_info,$data_group_order){

		$data_group_order['product_coupon_id'] 			= !empty($data_group_order['product_coupon_id']) ? $data_group_order['product_coupon_id'] : 0;
		$data_group_order['product_coupon_price'] 			= !empty($data_group_order['product_coupon_price']) ? $data_group_order['product_coupon_price'] : 0;
		$data_group_order['product_card_id'] 			= !empty($data_group_order['product_card_id']) ? $data_group_order['product_card_id'] : 0;
		$data_group_order['product_card_price'] 			= !empty($data_group_order['product_card_price']) ? $data_group_order['product_card_price'] : 0;
		$data_group_order['product_merchant_balance'] 	= !empty($data_group_order['product_merchant_balance']) ? $data_group_order['product_merchant_balance'] : 0;
		$data_group_order['product_card_give_money'] 	= !empty($data_group_order['product_card_give_money']) ? $data_group_order['product_card_give_money'] : 0;
		$data_group_order['product_card_discount'] 	= !empty($data_group_order['product_card_discount']) ? $data_group_order['product_card_discount'] : 0;
		$data_group_order['product_balance_pay']	 	= !empty($data_group_order['product_balance_pay']) ? $data_group_order['product_balance_pay'] : 0;
		$data_group_order['product_score_used_count']  = !empty($data_group_order['product_score_used_count']) ? $data_group_order['product_score_used_count'] : 0;
		$data_group_order['product_score_deducte']     = !empty($data_group_order['product_score_deducte']) ? $data_group_order['product_score_deducte'] : 0;
		$data_group_order['user_pay_money']	 	    = !empty($data_group_order['user_pay_money']) ? (float)$data_group_order['user_pay_money'] : 0;
		$data_group_order['product_real_balace_price']	 	    = !empty($data_group_order['product_real_balace_price']) ? (float)$data_group_order['product_real_balace_price'] : 0;
		$data_group_order['score_discount_type']      = !empty($order_info['score_discount_type']) ? $order_info['score_discount_type'] : 0;
		$data_group_order['user_pay_time'] = $_SERVER['REQUEST_TIME'];

		$condition_group_order['order_id'] = $order_info['order_id'];
		if($this->where($condition_group_order)->data($data_group_order)->save()){
			return array('error_code'=>false,'msg'=>'保存订单成功！');
		}else{
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
	}

	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info , $is_balace = false){
		$order_param = array(
				'order_id' => $order_info['order_id'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => $order_info['is_mobile'],
		);

		if($is_balace){
			$result_after_pay = $this->balance_after_pay($order_param);
		}else{
			$result_after_pay = $this->after_pay($order_param);
		}

		if($result_after_pay['error']){
			return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
		}
		return array('error_code'=>false,'msg'=>'支付成功！','url'=>str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$order_info['order_id']))));
	}
	//支付前订单处理
	public function befor_pay($order_info,$now_coupon,$now_user){
		//判断是否需要在线支付
		if($now_coupon['price']+$now_user['now_money'] < $order_info['order_total_money']){
			$online_pay = true;
		}else{
			$online_pay = false;
		}
		//不使用在线支付，直接使用会员卡和用户余额。
		if(empty($online_pay)){
			if(!empty($now_coupon)){
				$coupon_pay_result = D('Member_card_coupon')->user_card($now_coupon['record_id'],$order_info['mer_id'],$now_user['uid']);
				if($coupon_pay_result['error_code']){
					return $coupon_pay_result;
				}
				$order_pay['car_id'] = $now_coupon['record_id'];
			}
			if(!empty($now_user['now_money']) && $now_coupon['price'] < $order_info['order_total_money']){
				$money_pay_result = D('User')->user_money($now_user['uid'],$order_info['order_total_money']-$now_coupon['price']);
				if($money_pay_result['error_code']){
					return $money_pay_result;
				}
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}else{
			//校验会员卡
			if(!empty($now_coupon)){
				$coupon_pay_result = D('Member_card_coupon')->check_card($now_coupon['record_id'],$order_info['mer_id'],$now_user['uid']);
				if($coupon_pay_result['error_code']){
					return $coupon_pay_result;
				}
				$order_pay['car_id'] = $now_coupon['record_id'];
			}
			if(!empty($now_user['now_money'])){
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}

		//将会员卡ID，已支付用户余额等信息记录到订单信息里
		if(!empty($order_pay['car_id'])){
			$data_group_order['card_id'] = $order_pay['record_id'];
		}
		if(!empty($order_pay['balance_pay'])){
			$data_group_order['balance_pay'] = $order_pay['record_id'];
		}
		if(!empty($data_group_order)){
			$condition_group_order['order_id'] = $order_info['order_id'];
			if(!$this->where($condition_group_order)->data($data_group_order)->save()){
				return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
		}

		if($online_pay){
			return array('error_code'=>false,'pay_money'=>$order_info['order_total_money'] - $now_coupon['price'] - $now_user['now_money']);
		}else{
			$order_param = array(
					'order_id' => $order_info['order_id'],
					'pay_type' => '',
					'third_id' => '',
					'is_mobile' => 0,
					'pay_money' => 0,
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'url'=>U('My/appoint_order',array('order_id'=>$order_info['order_id'])));
		}
	}

	//支付之后
	/**
	 * @param $order_param
	 * @return array
	 */
	public function after_pay($order_param){
		if($order_param['pay_type'] != ''){
			$condition_appoint_order['orderid'] = $order_param['order_id'];
		}else{
			$condition_appoint_order['order_id'] = $order_param['order_id'];
		}
		$now_order = $this->field(true)->where($condition_appoint_order)->find();
		$appoint_info  =D('Appoint')->where(array('appoint_id'=>$now_order['appoint_id']))->find();

		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}else if($now_order['paid'] == 1){
			if(empty($order_param['is_initiative'])){
				if($order_param['is_mobile']){
					return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('My/appoint_order',array('order_id'=>$now_order['order_id'])));
				}else{
					return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
				$now_user = D('User')->get_user($now_order['uid']);
				if(empty($now_user)){
					return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
				}

				//D('Action_relation')->add_user_action($order_param['order_id'],'appoint');

				$group_pass_array = array(
					date('y',$_SERVER['REQUEST_TIME']),
					date('m',$_SERVER['REQUEST_TIME']),
					date('d',$_SERVER['REQUEST_TIME']),
					date('H',$_SERVER['REQUEST_TIME']),
					date('i',$_SERVER['REQUEST_TIME']),
					date('s',$_SERVER['REQUEST_TIME']),
					mt_rand(10,99),
				);
				shuffle($group_pass_array);
				$data_group_order['appoint_pass'] = implode('',$group_pass_array);
			
				$data_group_order['pay_money'] = floatval($order_param['pay_money']);
				$data_group_order['user_pay_money'] = $order_param['user_pay_money'];
				$data_group_order['user_pay_time'] = $order_param['user_pay_time'];
				$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
				$data_group_order['user_recharge_order_id'] = $order_param['user_recharge_order_id'];
				$data_group_order['paid'] = 1;

				if($this->where($condition_appoint_order)->data($data_group_order)->save()){
					/*分析粉丝行为*/

					D('Scroll_msg')->add_msg('appoint',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.appoint_alias_name').'成功');

					D('Merchant_request')->add_request($now_order['mer_id'],array('appoint_buy_count'=>1,'appoint_buy_money'=>$now_order['payment_money']));

					$condition_group['appoint_id'] = $now_order['appoint_id'];
					//D('Appoint')->where($condition_group)->setInc('appoint_sum',1);
					$now_order = $this->field(true)->where($condition_appoint_order)->find();
					if($now_order["is_initiative"] && $now_order["service_status"]){
						$payment_money = $now_order['pay_money'] + $now_order['user_pay_money'];
					}else if($now_order["is_initiative"] && !$now_order["service_status"]){
						$payment_money = $now_order['user_pay_money'];
					}else{
						$payment_money = $now_order['pay_money'];
					}
					$payment_money = $now_order['pay_money'] + $now_order['score_deducte']+ $now_order['merchant_balance']+$now_order['balance_pay'];
					if ($now_user['openid'] && $order_param['is_mobile']) {
						$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
						$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '预约提醒', 'keyword1' => $appoint_info['appoint_name'], 'keyword2' => $now_order['order_id'], 'keyword3' => $payment_money, 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '预约成功，您的消费码：'.$data_group_order['appoint_pass']));
					}
					$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'appoint');
					if (C('config.sms_success_order') == 1 || C('config.sms_success_order') == 3) {
						$sms_data['uid'] = $now_order['uid'];
						$sms_data['mobile'] = $now_user['phone'];
						$sms_data['sendto'] = 'user';
						$sms_data['content'] = '您预约 '.$now_order['order_name'].'的订单(订单号：' . $now_order['order_id'] . ')已经完成支付,您的消费码：' . $data_group_order['appoint_pass'];
						Sms::sendSms($sms_data);
					}
					if (C('config.sms_success_order') == 2 || C('config.sms_success_order') == 3) {
						$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
						$sms_data['uid'] = 0;
						$sms_data['mobile'] = $merchant['phone'];
						$sms_data['sendto'] = 'merchant';
						$sms_data['content'] = '顾客预约的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已经完成了支付！';
						Sms::sendSms($sms_data);
					}

					$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'], 'type' => 'appoint');

					$database_appoint_visit_order_info = D('Appoint_visit_order_info');
					$worker_where['appoint_order_id'] = $now_order['order_id'];
					$now_worker = $database_appoint_visit_order_info->appoint_visit_order_detail($worker_where);
					$now_worker = $now_worker['detail'];
					if((C('config.worker_sms_success_order') == 1) && !empty($now_worker)){
						$sms_data['uid'] = 0;
						$sms_data['mobile'] = $now_worker['mobile'];
						$sms_data['sendto'] = 'user';
						$sms_data['content'] = '用户在' . date('Y-m-d H:i',time()) . '时，购买了预约服务，已成功支付，订单号：' . $now_order['order_id'];
						Sms::sendSms($sms_data);
					}

					//小票打印start
					$printHaddle = new PrintHaddle();
					$printHaddle->printit($now_order['order_id'], 'appoint_order', 1);
					//小票打印end

					if($order_param['is_mobile']){
						return array('error'=>0,'url'=>str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$now_order['order_id']))));
					}else{
						return array('error'=>0,'url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
					}
				}else{
					return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
				}
			}
		}else{

			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}

			if($now_order['card_id']){
				$now_coupon = D('Card_new_coupon')->get_coupon_by_id($now_order['card_id']);
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

			//判断帐户余额
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

			$balance_pay = floatval($now_order['balance_pay']);
			if($balance_pay){
				if($now_user['now_money'] < $balance_pay){
					return $this->wap_after_pay_error($now_order,$order_param,'您的帐户余额不够此次支付！');
				}
			}

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
			$score_used_count=$now_order['score_used_count'];
			if($now_user['score_count']<$score_used_count){
				return array('error'=>1,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
			if($score_used_count>0){
				$use_result = D('User')->user_score($now_order['uid'],$score_used_count,'购买 '.$now_order['order_name'].' 扣除'.C('config.score_name'));
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}


			if($merchant_balance){
				$use_result = D('Card_new')->use_money($now_order['uid'],$now_order['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			if($card_give_money){
				$use_result = D('Card_new')->use_give_money($now_order['uid'],$now_order['mer_id'],$card_give_money,'购买 '.$now_order['order_name'].' 扣除会员卡赠送余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//如果用户使用了余额支付，则扣除相应的金额。
			if(!empty($balance_pay)){
				if($now_order['product_name']){
					$tmp_order_name = $appoint_info['appoint_name'] . ' - ' . $now_order['product_name'];
				}else{
					$tmp_order_name = $appoint_info['appoint_name'];
				}

				$use_result = D('User')->user_money($now_order['uid'],$balance_pay,'预约 '.$tmp_order_name.' 扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//D('Action_relation')->add_user_action($order_param['order_id'],'appoint');

			$group_pass_array = array(
					date('y',$_SERVER['REQUEST_TIME']),
					date('m',$_SERVER['REQUEST_TIME']),
					date('d',$_SERVER['REQUEST_TIME']),
					date('H',$_SERVER['REQUEST_TIME']),
					date('i',$_SERVER['REQUEST_TIME']),
					date('s',$_SERVER['REQUEST_TIME']),
					mt_rand(10,99),
			);
			shuffle($group_pass_array);
			$data_group_order['appoint_pass'] = implode('',$group_pass_array);

			$data_group_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_group_order['pay_money'] = floatval($order_param['pay_money']);
			$data_group_order['pay_type'] = $order_param['pay_type'];
			$data_group_order['third_id'] = $order_param['third_id'];
			$data_group_order['is_mobile_pay'] = $order_param['is_mobile'];
			$data_group_order['product_real_pay_payment_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['product_real_payment_price'] = floatval($order_param['pay_money']);
			$data_group_order['is_own'] = isset($order_param['sub_mch_id'])?2:$order_param['is_own'];
			$data_group_order['paid'] = 1;
			$data_group_order['last_staff'] = '用户余额支付';

			if($this->where($condition_appoint_order)->data($data_group_order)->save()){
				/*分析粉丝行为*/
				D('Merchant_request')->add_request($now_order['mer_id'],array('appoint_buy_count'=>1,'appoint_buy_money'=>$now_order['payment_money']));

				D('Scroll_msg')->add_msg('appoint',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.appoint_alias_name').'成功');

				$condition_group['appoint_id'] = $now_order['appoint_id'];
				//D('Appoint')->where($condition_group)->setInc('appoint_sum',1);

				$now_order = $this->field(true)->where($condition_appoint_order)->find();
				if($now_order['store_id']){
					D('Merchant_store_staff')->sendMsgAppointOrder($now_order);
				}

				//增加粉丝 5 group 6 shop 7 meal 8 appoint 9 store
				D('Merchant')->saverelation($now_user['openid'], $now_order['mer_id'], 8);


				if($now_order["is_initiative"] && $now_order["service_status"]){
					$payment_money = $now_order['pay_money'] + $now_order['user_pay_money'];
				}else if($now_order["is_initiative"] && !$now_order["service_status"]){
					$payment_money = $now_order['user_pay_money'];
				}else{
					$payment_money = $now_order['pay_money'];
				}
				$payment_money = $now_order['pay_money'] + $now_order['score_deducte']+ $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['product_score_deducte'];

				if ($now_user['openid'] && $order_param['is_mobile']) {
					$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '预约提醒', 'keyword1' => $appoint_info['appoint_name'], 'keyword2' => $now_order['order_id'], 'keyword3' => $payment_money, 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '预约成功，您的消费码：'.$data_group_order['appoint_pass']));
				}
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'appoint');
				if (C('config.sms_success_order') == 1 || C('config.sms_success_order') == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您预约 '.$now_order['order_name'].'的订单(订单号：' . $now_order['order_id'] . ')已经完成支付,您的消费码：' . $data_group_order['appoint_pass'];
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_success_order') == 2 || C('config.sms_success_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客预约的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已经完成了支付！';
					Sms::sendSms($sms_data);
				}

				$database_appoint_visit_order_info = D('Appoint_visit_order_info');
				$worker_where['appoint_order_id'] = $now_order['order_id'];
				$now_worker = $database_appoint_visit_order_info->appoint_visit_order_detail($worker_where);
				$now_worker = $now_worker['detail'];

				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'], 'type' => 'appoint');
				if((C('config.worker_sms_success_order') == 1) && !empty($now_worker)){
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $now_worker['mobile'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '用户在' .date('Y-m-d H:i',time()). '时，购买了预约服务，已成功支付，订单号：'. $now_order['order_id'];
					Sms::sendSms($sms_data);
				}


				$database_appoint_supply = D('Appoint_supply');
				$supply_data['appoint_id'] = $now_order['appoint_id'];
				$supply_data['mer_id'] = $now_order['mer_id'];
				$supply_data['store_id'] = $now_order['store_id'];
				$supply_data['create_time'] = time();
				$supply_data['worker_id'] = $now_order['merchant_worker_id'];
				$supply_data['start_time'] = $_SERVER['REQUEST_TIME'];
				$supply_data['paid'] = $now_order['paid'];
				$supply_data['status'] =  2;
				$supply_data['pay_type'] = $now_order['pay_type'];
				$supply_data['order_time'] = $now_order['order_time'];
				$supply_data['deliver_cash'] = floatval($now_order['product_price'] - $now_order['product_card_price'] - $now_order['product_merchant_balance'] - $now_order['product_balance_pay'] - $now_order['product_payment_money'] - $now_order['product_score_deducte'] - $now_order['product_coupon_price']);
				$supply_data['order_id'] = $now_order['order_id'];
				$database_appoint_supply->data($supply_data)->add();


				//小票打印start
				$printHaddle = new PrintHaddle();
				$printHaddle->printit($now_order['order_id'], 'appoint_order', 1);
				//小票打印end

				if($order_param['is_mobile']){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$now_order['order_id']))));
				}else{
					return array('error'=>0,'url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}


	//余额支付
	public function balance_after_pay($order_param){
		if($order_param['pay_type']!=''){
			$condition_appoint_order['orderid'] = $order_param['order_id'];
		}else{
			$condition_appoint_order['order_id'] = $order_param['order_id'];
		}
		$now_order = $this->field(true)->where($condition_appoint_order)->find();
		$appoint_info  =D('Appoint')->where(array('appoint_id'=>$now_order['appoint_id']))->find();

		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}else if((($now_order['paid'] == 1) && ($now_order['service_status'] == 0)) || ($now_order['payment_status'] == 0&& $now_order['service_status'] == 0)){
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}

			if($now_order['product_card_id']){
				$now_coupon = D('Card_new_coupon')->get_coupon_by_id($now_order['product_card_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			//判断平台优惠券
			if($now_order['product_coupon_id']){
				$now_coupon = D('System_coupon')->get_coupon_by_id($now_order['product_coupon_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			//如果用户使用了积分抵扣，则扣除相应的积分
			//判断积分数量是否正确
			$score_used_count=$now_order['product_score_used_count'];
			if($now_user['score_count']<$score_used_count){
				return array('error'=>1,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
			if($score_used_count>0){
				$use_result = D('User')->user_score($now_order['uid'],$score_used_count,'购买 '.$now_order['order_name'].' 扣除'.C('config.score_name'));
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//判断帐户余额
			$merchant_balance = floatval($now_order['product_merchant_balance']);
			if($merchant_balance){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance['card_money'] < $merchant_balance){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}

			$card_give_money = floatval($now_order['product_card_give_money']);
			if($card_give_money){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance['card_money_give'] < $card_give_money){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}

			$balance_pay = floatval($now_order['product_balance_pay']);
			if($balance_pay){
				if($now_user['now_money'] < $balance_pay){
					return $this->wap_after_pay_error($now_order,$order_param,'您的帐户余额不够此次支付！');
				}
			}

			if($now_order['product_card_id']){
				$use_result = D('Card_new_coupon')->user_coupon($now_order['product_card_id'],$now_order['order_id'],$order_param['order_type'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//如果使用了平台优惠券
			if($now_order['product_coupon_id']){
				$use_result = D('System_coupon')->user_coupon($now_order['product_coupon_id'],$now_order['order_id'],$order_param['order_type'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}


			if($merchant_balance){
				$use_result = D('Card_new')->use_money($now_order['uid'],$now_order['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			if($card_give_money){
				$use_result = D('Card_new')->use_give_money($now_order['uid'],$now_order['mer_id'],$card_give_money,'购买 '.$now_order['order_name'].' 扣除会员卡赠送余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//如果用户使用了余额支付，则扣除相应的金额。
			if(!empty($balance_pay)){
				if($now_order['product_name']){
					$tmp_order_name = $appoint_info['appoint_name'] . ' - ' . $now_order['product_name'];
				}else{
					$tmp_order_name = $appoint_info['appoint_name'];
				}

				$use_result = D('User')->user_money($now_order['uid'],$balance_pay,'预约 '.$tmp_order_name.' 扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//D('Action_relation')->add_user_action($order_param['order_id'],'appoint');

			if(empty($now_order['appoint_pass'])){
				$group_pass_array = array(
						date('y',$_SERVER['REQUEST_TIME']),
						date('m',$_SERVER['REQUEST_TIME']),
						date('d',$_SERVER['REQUEST_TIME']),
						date('H',$_SERVER['REQUEST_TIME']),
						date('i',$_SERVER['REQUEST_TIME']),
						date('s',$_SERVER['REQUEST_TIME']),
						mt_rand(10,99),
				);
				shuffle($group_pass_array);
				$data_group_order['appoint_pass'] = implode('',$group_pass_array);
			}

			$data_group_order['user_pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_group_order['user_pay_money'] = floatval($order_param['pay_money']);
			$data_group_order['product_pay_type'] = $order_param['pay_type'];
			$data_group_order['product_third_id'] = $order_param['third_id'];
			$data_group_order['product_is_mobile_pay'] = $order_param['is_mobile'];
			$data_group_order['product_real_balace_pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['product_real_balace_price'] = floatval($order_param['pay_money']);
			$data_group_order['product_is_own'] = $order_param['is_own'];
			$data_group_order['is_initiative'] = 1;
			$data_group_order['service_status'] = 1;
			$data_group_order['paid'] = 1;

			if($this->where($condition_appoint_order)->data($data_group_order)->save()){
				/*分析粉丝行为*/
				D('Merchant_request')->add_request($now_order['mer_id'],array('appoint_buy_count'=>1,'appoint_buy_money'=>$now_order['payment_money']));

				D('Scroll_msg')->add_msg('appoint',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.appoint_alias_name').'成功');

				$condition_group['appoint_id'] = $now_order['appoint_id'];
				D('Appoint')->where($condition_group)->setInc('appoint_sum',1);
				$now_order = $this->field(true)->where($condition_appoint_order)->find();
				if($now_order["is_initiative"] && $now_order["service_status"]){
					$payment_money = $now_order['user_pay_money'] + $now_order['pay_money'];
				}else if($now_order["is_initiative"] && !$now_order["service_status"]){
					$payment_money = $now_order['user_pay_money'];
				}else{
					$payment_money = $now_order['pay_money'];
				}

				$payment_money = $now_order['product_balance_pay']+$now_order['product_merchant_balance']+$now_order['product_card_give_money']+$now_order['user_pay_money']+$now_order['product_coupon_price']+$now_order['product_card_price']+$now_order['product_score_deducte'];

				if ($now_user['openid'] && $order_param['is_mobile']) {
					$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '预约余款支付提醒', 'keyword1' => $appoint_info['appoint_name'], 'keyword2' => $now_order['order_id'], 'keyword3' => $payment_money, 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '预约成功，您的消费码：'.$now_order['appoint_pass']));
				}
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'appoint');
				if (C('config.sms_success_order') == 1 || C('config.sms_success_order') == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您预约 '.$now_order['order_name'].'的订单(订单号：' . $now_order['order_id'] . ')已经完成支付,您的消费码：' . $now_order['appoint_pass'];
					Sms::sendSms($sms_data);
				}

				if (C('config.sms_success_order') == 2 || C('config.sms_success_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客预约的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已经完成了支付！';
					Sms::sendSms($sms_data);
				}

				$database_appoint_visit_order_info = D('Appoint_visit_order_info');
				$worker_where['appoint_order_id'] = $now_order['order_id'];
				$now_worker = $database_appoint_visit_order_info->appoint_visit_order_detail($worker_where);
				$now_worker = $now_worker['detail'];

				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'], 'type' => 'appoint');
				if((C('config.worker_sms_success_order') == 1) && !empty($now_worker)){
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $now_worker['mobile'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '用户在' .date('Y-m-d H:i',time()). '时，购买了预约服务，已成功支付，订单号：'. $now_order['order_id'];
					Sms::sendSms($sms_data);
				}


				//小票打印start
				$printHaddle = new PrintHaddle();
				$printHaddle->printit($now_order['order_id'], 'appoint_order', 1);
				//小票打印end

				$now_order = $this->field(true)->where($condition_appoint_order)->find();
				$order_info['order_type'] = 'appoint';
				$order_info['mer_id'] = $now_order['mer_id'];
				$order_info['order_id'] = $now_order['order_id'];
				$order_info['store_id'] = $now_order['store_id'];
				$order_info['balance_pay'] = $now_order['balance_pay'];
				$order_info['score_deducte'] = $now_order['score_deducte'];
				$order_info['payment_money'] = $now_order['pay_money'];
				$order_info['is_own'] = $now_order['product_is_own'];
				$order_info['merchant_balance'] = $now_order['merchant_balance'];
				$order_info['score_used_count'] = $now_order['score_used_count'];
				$order_info['user_pay_money'] = $now_order['user_pay_money'];


				$order_info['product_score_deducte'] = $now_order['product_score_deducte'];
				$order_info['product_balance_pay'] = $now_order['product_balance_pay'];
				$order_info['product_score_used_count'] = $now_order['product_score_used_count'];
				$order_info['product_coupon_price'] = $now_order['product_coupon_price'];
				$order_info['product_merchant_balance'] = $now_order['product_merchant_balance'];
				if($order_info['is_own']>0){
					$order_info['payment_money'] = 0;
					$order_info['user_pay_money'] = 0;
				}
				$order_info['total_money'] = $order_info['money'] = $order_info['balance_pay']
						+ $order_info['score_deducte']
						+ $order_info['payment_money']
						+ $order_info['merchant_balance']
						+ $order_info['product_score_deducte']
						+ $order_info['product_balance_pay']
						+ $order_info['product_coupon_price']
						+ $order_info['user_pay_money']
						+ $order_info['product_merchant_balance'];

				$order_info['payment_money'] = $now_order['pay_money'] + $now_order['user_pay_money'];
				$order_info['balance_pay'] = $now_order['balance_pay'] + $now_order['product_balance_pay'];
				$order_info['merchant_balance'] = $now_order['merchant_balance'] + $now_order['product_merchant_balance'];
				$order_info['card_give_money'] = $now_order['card_give_money'] + $now_order['product_card_give_money'];
				$order_info['uid'] = $now_order['uid'];
				$order_info['desc'] = '用户预约'.$appoint_info['appoint_name'].'记入收入';
				 $order_info['score_discount_type']=$now_order['score_discount_type'];
				//D('Merchant_money_list')->add_money($now_order['mer_id'],'用户预约'.$appoint_info['appoint_name'].'记入收入',$order_info);
				D('SystemBill')->bill_method($order_info['is_own'],$order_info);

				//增加粉丝 5 group 6 shop 7 meal 8 appoint 9 store
				D('Merchant')->saverelation($now_user['openid'], $now_order['mer_id'], 8);

				//微信派发优惠券 支付到平台 微信支付
				if($order_param['is_own']==0 && $order_param['pay_type']=='weixin' && $order_param['pay_money']>=C('config.weixin_send_money')){
					D('System_coupon')->weixin_send($order_param['pay_money'],$order_info['uid']);
				}

				if(C('config.open_extra_price')==1){
					$score = D('Percent_rate')->get_extra_money($now_order);
					if($score>0){
						D('User')->add_score($now_order['uid'], $score,'购买'.C('config.appoint_alias_name').'商品获得'.C('config.score_name'));
					}
				}else if(C('config.add_score_by_percent')==0 && (C('config.open_score_discount')==0 || $now_order['score_discount_type']!=2)){
					if(C('config.open_score_get_percent')==1){
						$score_get = C('config.score_get_percent')/100;
					}else{
						$score_get = C('config.user_score_get');
					}
					$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
		            if($now_merchant['score_get_percent']>=0){
		               	$score_get = $now_merchant['score_get_percent']/100;
		            }
					if($order_info['is_own'] && C('config.user_own_pay_get_score')!=1){
						$order_info['payment_money']= 0;
					}
					D('User')->add_score($now_order['uid'], round(($order_info['balance_pay']+$order_info['payment_money']) * $score_get), '购买预约商品获得'.C('config.score_name'));
				}

				D('Merchant_spread')->add_spread_list($order_info,$now_user,'appoint',$now_user['nickname']."用户购买预约获得佣金");

				if($order_param['is_mobile']){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$now_order['order_id']))));
				}else{
					return array('error'=>0,'url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}else{
			if($now_order['service_status'] == 1){
				return array('error'=>0,'url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
			}
			return array('error'=>1,'msg'=>'修改订单失败，请联系系统管理员！');
		}
	}
	//支付时，金额不够，记录到帐号
	public function wap_after_pay_error($now_order,$order_param,$error_tips){
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$now_order['order_id'])));
			}else{
				$return_url = U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id']));
			}
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}

	// 不需要付款
	public function no_pay_after($order_id,$appoint_info,$is_mobile=1){

		$condition_group_order['order_id'] = $order_id;
		$now_order = $this->field(true)->where($condition_group_order)->find();
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}else{
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}

			$condition_group_order['order_id'] = $order_id;

			$group_pass_array = array(
					date('y',$_SERVER['REQUEST_TIME']),
					date('m',$_SERVER['REQUEST_TIME']),
					date('d',$_SERVER['REQUEST_TIME']),
					date('H',$_SERVER['REQUEST_TIME']),
					date('i',$_SERVER['REQUEST_TIME']),
					date('s',$_SERVER['REQUEST_TIME']),
					mt_rand(10,99),
			);
			shuffle($group_pass_array);
			$data_group_order['appoint_pass'] = implode('',$group_pass_array);
			if($this->where($condition_group_order)->data($data_group_order)->save()){

				/*分析粉丝行为*/
				D('Merchant_request')->add_request($now_order['mer_id'],array('appoint_buy_count'=>1));

				$condition_group['appoint_id'] = $now_order['appoint_id'];
				//D('Appoint')->where($condition_group)->setInc('appoint_sum',1);

				if ($now_user['openid'] && $is_mobile) {
					$href = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '预约提醒', 'keyword1' => $appoint_info['appoint_name'], 'keyword2' => $now_order['order_id'], 'keyword3' => date('Y-m-d H:i:s'), 'remark' => '预约成功，您的消费码：'.$data_group_order['appoint_pass']));
				}
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'appoint');
				if (C('config.sms_success_order') == 1 || C('config.sms_success_order') == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您预约 '.$appoint_info['appoint_name'].'的订单(订单号：' . $now_order['order_id'] . ')已经完成支付,您的消费码：' . $data_group_order['appoint_pass'];
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_success_order') == 2 || C('config.sms_success_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客预约的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已经完成！';
					Sms::sendSms($sms_data);
				}

				if($is_mobile){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('My/appoint_order',array('order_id'=>$now_order['order_id']))));
				}else{
					return array('error'=>0,'url'=>U('User/Index/appoint_order_view',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}


	//修改订单状态
	public function change_status($order_id, $status)
	{
		$condition_appoint_order['order_id'] = $order_id;
		$data_appoint_order['service_status'] = $status;//2已评价
		if($this->where($condition_appoint_order)->data($data_appoint_order)->save()){
			return true;
		}else{
			return false;
		}
	}

	public function get_rate_order_list($uid, $is_rate = false, $is_wap = false)
	{
		$mod = new Model();
		if ($is_rate) {
			$sql = "SELECT `a`.`pic` as list_pic, `a`.*, `o`.*, `r`.* FROM  " . C('DB_PREFIX') . "appoint AS a INNER JOIN " . C('DB_PREFIX') . "appoint_order AS o ON `a`.`appoint_id`=`o`.`appoint_id` INNER JOIN " . C('DB_PREFIX') . "reply AS r ON r.order_id=o.order_id WHERE `o`.`uid`='{$uid}' AND `o`.`paid`<>4 AND `o`.`service_status`=2 ORDER BY `o`.`order_id` DESC";
		} else {
			$sql = "SELECT `a`.`pic` as list_pic, `a`.*, `o`.* FROM  " . C('DB_PREFIX') . "appoint AS a INNER JOIN " . C('DB_PREFIX') . "appoint_order AS o ON `a`.`appoint_id`=`o`.`appoint_id` WHERE `o`.`uid`='{$uid}' AND `o`.`paid`<>4 AND `o`.`service_status`=1 ORDER BY `o`.`order_id` DESC";
		}

		$order_list = $mod->query($sql);
		$appoint_image_class = new appoint_image();
		foreach ($order_list as &$row) {
			$tmp_pic_arr = explode(';', $row['list_pic']);
			$row['list_pic'] = $appoint_image_class->get_image_by_path($tmp_pic_arr[0], 's');
			$row['url'] = $this->get_appoint_url($row['appoint_id'], $is_wap);

			if($is_rate){
				$row['comment'] = stripslashes($row['comment']);
				if($row['pic']){
					$tmp_array = explode(',', $row['pic']);
					$row['pic_count'] = count($tmp_array);
				}
			}
		}
		return $order_list;
	}

	public function get_appoint_url($appoint_id, $is_wap)
	{
		if ($is_wap) {
			return U('Wap/Appoint/detail', array('appoint_id' => $appoint_id));
		} else {
			return C('config.site_url') . '/appoint/' . $appoint_id . '.html';
		}
	}
}