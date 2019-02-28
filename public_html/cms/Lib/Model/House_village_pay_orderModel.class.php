<?php
class House_village_pay_orderModel extends Model{
	/*得到小区的新闻列表*/
	public function get_limit_list_page($column,$pageSize=20,$isSystem=false){
		if(!$column['village_id']){
			return null;
		}

    	$condition_table  = array(C('DB_PREFIX').'house_village_pay_order'=>'o',C('DB_PREFIX').'house_village_user_bind'=>'b');
    	$condition_where = " `o`.`village_id` = `b`.`village_id`  AND `o`.`bind_id` = `b`.`pigcms_id`  AND `o`.`village_id` =".$column['village_id'];
    	
    	
    	if(isset($column['paid'])){
    		$condition_where .= " AND `o`.`paid`= ".intval($column['paid']);
    	}

        if($column['bind_id']){
    		$condition_where .= " AND `o`.`bind_id`=".intval($column['bind_id']);
    	}
    	if($column['phone']){
			$condition_where .= " AND `b`.`phone` like '%".$column['phone']."%'";
		}
		if($column['order_name']){
			$condition_where .= " AND `o`.`order_name` like '%".$column['order_name']."%'";
		}
		if($column['order_type']){
			$condition_where .= " AND `o`.`order_type` in (".$column['order_type'].")";
		}
		if(isset($column['cashier_id'])){
			$condition_where .= " AND `o`.`cashier_id` in (".$column['cashier_id'].")";
		}


		if($column['is_pay_bill']==1){
			$condition_where .= " AND (`o`.`is_pay_bill`= 1 OR `o`.`is_pay_bill`= 2)" ;
		}else if($column['is_pay_bill']==2){
			$condition_where .= " AND `o`.`is_pay_bill`= 0" ;
		}

		if($column['pay_time_str']){
			$condition_where .= ' AND '.$column['pay_time_str'];
		}
    	$condition_field = '`b`.`name` AS `username` ,b.*,o.*';
    	$order = ' `o`.`order_id` DESC, `o`.`paid` ASC';
    	if($isSystem){
    		import('@.ORG.system_page');
    	}else{
    		import('@.ORG.merchant_page');
    	}
    	$count_order = D('')->table($condition_table)->where($condition_where)->count();
    	$p = new Page($count_order,$pageSize,'page');
    	$order_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		$database_house_village_property_paylist = D('House_village_property_paylist');
		$pay_list = $database_house_village_property_paylist->where(array('village_id'=>$column['village_id']))->select();

		if(!empty($pay_list)){
			foreach($order_list as $Key=>$order){
				foreach($pay_list as $pay_info){
					if($order['order_id'] ==  $pay_info['order_id']){
						$order_list[$Key]['property_time_str'] = date('Y-m-d',$pay_info['start_time']) . '至' . date('Y-m-d',$pay_info['end_time']);
					}
				}
			}
		}


    	$total = D('')->field(' SUM(`o`.`money` ) AS totalMoney ')->table($condition_table)->where($condition_where)->find();
    	$already = D('')->field(' SUM(`o`.`money` ) AS readyMoney ')->table($condition_table)->where($condition_where." AND `o`.`is_pay_bill`=1 ")->find();
    	
    	$return['pagebar'] = $p->show();
    	$return['order_list'] = $order_list;
    	$return['totalMoney'] = $total;
    	$return['readyMoney'] = $already;
    	
    	return $return;
	}


	public function get_one($order_id){
		if(!$order_id){
			return false;
		}

		return $this->where(array('order_id'=>$order_id))->find();
	}

	public function get_pay_order($order_id){
		$now_order = $this->get_one($order_id);
		$order_info = array(
				'pay_offline' 			=> false,		  //线下支付
				'pay_merchant_balance' 	=> false,		  //商家余额
				'pay_merchant_coupon' 	=> false,		  //商家优惠券
				'pay_merchant_ownpay' 	=> false,		  //商家自有支付
				'pay_system_balance' 	=> true,		  //平台余额
				'pay_system_coupon' 	=> false,		  //平台优惠券
				'pay_system_score' 		=> true,		  //平台积分抵现
				'order_info' 			=> $now_order,	  //平台积分抵现
		);
		if($now_order['order_type']=='property'){
			$order_info['pay_system_score']=true;
		}
		$now_village = D('House_village')->get_one($now_order['village_id']);
		if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 ){
			$order_info['pay_merchant_ownpay'] = true;
		}

		if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 && $now_village['sub_mch_discount']==0 ){
			$order_info['pay_system_score'] = false;
		}

		if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 && $now_village['sub_mch_sys_pay']==0 ){
			$order_info['pay_system_balance'] = false;
		}
		return $order_info;
	}

	public function after_pay($order_id,$plat_order_info){
		$now_order = $this->get_one($order_id);
		$date_order['pay_time'] = $plat_order_info['pay_time'];
		$date_order['paid'] = 1;
		$date_order['score_used_count'] = $plat_order_info['system_score'];
		$date_order['score_deducte'] = $plat_order_info['system_score_money'];
		if($now_order['score_can_get']>0){
			D('User')->add_score($plat_order_info['uid'],$now_order['score_can_get'],'交物业费获得积分');
		}
		$this->where(array('order_id'=>$order_id))->save($date_order);
		$tmp_order = $now_order;
		$tmp_order['is_own'] = $plat_order_info['is_own'];
		$tmp_order['payment_money'] = $plat_order_info['pay_money'];
		$tmp_order['score_deducte'] = $plat_order_info['system_score_money'];
		$tmp_order['balance_pay'] = $plat_order_info['system_balance'];
		switch($now_order['order_type']){
			case 'property':
				$now_village = D('House_village')->get_one($now_order['village_id']);
				//增加积分
				if($now_order['score_can_get']>0){
					D('User')->add_score($plat_order_info['uid'],$now_order['score_can_get'],'交物业费获得积分');
				}

				$bind_field = 'property_price';
				$now_user_info = D('House_village_user_bind')->get_one_by_bindId($now_order['bind_id']);
				$House_village_property_paylist = D('House_village_property_paylist');
				$paylist_data['bind_id'] = $now_order['bind_id'];
				$paylist_data['uid'] = $now_order['uid'];
				$paylist_data['village_id'] = $now_order['village_id'];
				$paylist_data['property_month_num'] = $now_order['property_month_num'];
				$paylist_data['presented_property_month_num'] = $now_order['presented_property_month_num'];
				$paylist_data['house_size'] = $now_order['house_size'];
				$paylist_data['property_fee'] = $now_order['property_fee'];
				$paylist_data['floor_type_name'] = $now_order['floor_type_name'];

				$now_pay_info = $House_village_property_paylist->where(array('bind_id'=>$now_order['bind_id']))->order('add_time desc')->find();
				// if(!empty($now_pay_info)){
				// 	$paylist_data['start_time'] = $now_pay_info['end_time'] ;
				// 	$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_pay_info['end_time']);
				// }else{
				// 	if($now_user_info['add_time'] > 0){
				// 		$paylist_data['start_time'] = $now_user_info['add_time'] ;
				// 		$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
				// 	}else{
				// 		$paylist_data['start_time'] = time();
				// 		$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
				// 	}
				// }

				if($now_user_info['property_endtime']){
	                $paylist_data['start_time'] = $now_user_info['property_endtime'];
	                $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['property_endtime']);
	            }else{
	                if($now_user_info['add_time'] > 0){
	                    $paylist_data['start_time'] = $now_user_info['add_time'] ;
	                    $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
	                }else{
	                    $paylist_data['start_time'] = time();
	                    $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
	                }

	            }
	            
				$paylist_data['add_time'] = time();
				$paylist_data['order_id'] = $order_id;

				$paylist_data['end_time'] = strtotime(date('Y-m-d 23:59:59',$paylist_data['end_time']));
				
				$where['uid'] = $now_order['uid'];
				$where['village_id'] = $now_order['village_id'];
				$where['pigcms_id'] = $now_order['bind_id']; 
				M('House_village_user_bind')->where($where)->save(array('property_endtime'=>$paylist_data['end_time']));
				$House_village_property_paylist->data($paylist_data)->add();
				$tmp_order['desc'] = '用户交物业费';
				break;
			case 'water':
				$bind_field = 'water_price';
				$tmp_order['desc'] = '用户交水费';
				break;
			case 'electric':
				$bind_field = 'electric_price';
				$tmp_order['desc'] = '用户交电费';
				break;
			case 'gas':
				$bind_field = 'gas_price';
				$tmp_order['desc'] = '用户交燃气费';
				break;
			case 'park':
				$bind_field = 'park_price';
				$tmp_order['desc'] = '用户交车位费';
				break;
			default:
				$bind_field = '';
				$tmp_order['desc']= $plat_order_info['order_name'];
		}

		if(!empty($bind_field)){
			$now_user_info = D('House_village_user_bind')->get_one($now_order['village_id'],$now_order['bind_id'],'pigcms_id');
			$data_bind['pigcms_id'] = $now_user_info['pigcms_id'];
			if($now_user_info[$bind_field] - $now_order['money'] >= 0){
				$data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'];
			}else{
				$data_bind[$bind_field] = 0;
			}
			$data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'] >= 0 ? $now_user_info[$bind_field] - $now_order['money'] : 0;
			D('House_village_user_bind')->data($data_bind)->save();

		}


		$tmp_order['order_type'] = 'village_pay';
		if($plat_order_info['is_own']==0)
			$plat_order_info['is_own']+=4;
		//社区对账
		$res = D('SystemBill')->bill_method($plat_order_info['is_own'],$tmp_order);

		if(!$res['error_code']){
			$this->where(array('order_id'=>$order_id))->setField('is_pay_bill',2);
		}
		$now_user = D('User')->get_user($now_order['uid']);
		if(!empty($now_user['openid'])){
			$href = C('config.site_url').'/wap.php?g=Wap&c=House&a=village_my_paylists&village_id='.$now_order['village_id'];
			$model = new templateNews($this->config['wechat_appid'],$this->config['wechat_appsecret']);
			$model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>  ' 缴费成功提醒', 'keynote1' =>$now_order['order_name'], 'keynote2' =>'物业号 '. $now_user_info['usernum'], 'remark' => '缴费时间：'.date('Y年n月j日 H:i',$now_order['time']).'\n'.'缴费金额：￥'.$now_order['money']));
		}

		$village_info = D('House_village')->where(array('village_id'=>$now_order['village_id']))->find();
		$village_bind = D('House_village_user_bind')->where(array('pigcms_id'=>$now_order['bind_id']))->find();
		if($village_info['openid']){
			$href = "";
			$model = new templateNews($this->config['wechat_appid'],$this->config['wechat_appsecret']);
			$model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>  ' 业主 '.$village_bind['name'].'( '.$village_bind['address'].' )缴费成功提醒', 'keynote1' =>$village_info['property_name'], 'keynote2' =>$village_bind['name']."( ".$village_bind['phone']." )", 'remark' => '缴费周期： '.$now_order['property_month_num'].' 个月\n缴费时间：'.date('Y年n月j日 H:i',$now_order['time']).'\n'.'缴费金额：￥'.$now_order['money']));
		}
	}

	public function get_order_url($order_id,$is_mobile){
		$now_order = $this->get_one($order_id);
		M('House_village')->where(array('village_id'=>$now_order['village_id']))->setInc('payment_reminder');
		M('House_village_payment_standard_bind')->where(array('bind_id'=>$now_order['payment_bind_id']))->setInc('paid_cycle',$now_order['payment_paid_cycle']);
		
		// 小票打印start
        $printHaddle = new PrintVillage();
        $printHaddle->printit($order_id);
        // 小票打印end

		return '/wap.php?g=Wap&c=House&a=village_my_paylists&village_id='.$now_order['village_id'];
	}

	public function get_user_village_info($bind_id){
		$database_house_village_user_bind = D('House_village_user_bind');
		$now_user_info = $database_house_village_user_bind->get_one_by_bindId($bind_id);
		if(empty($now_user_info)){
			$this->check_ajax_error_tips('您不是该小区业主',U('bind_village',array('village_id'=>$_GET['village_id'])));
		}

		$where['parent_id|pigcms_id'] = $bind_id;
		$where['uid'] = $this->user_session['uid'];
		$where['village_id'] = $_GET['village_id'] + 0;
		$house_village_user_bind_count = $database_house_village_user_bind->where($where)->count();
		if(!$house_village_user_bind_count){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}

		$this->assign('now_user_info',$now_user_info);
		return $now_user_info;
	}

	/**
	 * [get_pay_order_sum 获取总和]
	 * @return [type] [description]
	 */
	public function get_pay_order_sum($field,$where=array()){
        $pay_count = D('House_village_pay_order')->where($where)->sum($field);
        $pay_count = $pay_count ? $pay_count : 0;
        return $pay_count;
	}
	
}