<?php
/**
 * 提供给收银台的数据
 */
class IndexAction extends CommonAction
{
	private $_data = null;
	
	public function __construct()
	{
		parent::__construct();
		$postStr = file_get_contents("php://input");
		$postStr = trim($postStr);
		$postStr = base64_decode($postStr);
		$postStr = $this->Encryptioncode($postStr,'DECODE');
		$this->_data = json_decode($postStr, true);
		
		//$config_file = './t.php';
		//$fp = fopen($config_file, 'a+');
		//fwrite($fp, "<?php \nreturn " . stripslashes(var_export($this->_data, true)) . ";");
		//fclose($fp);
	}


    public function store() 
    {
    	$listdata = array();
    	$stores = D('Merchant_store')->where(array('mer_id' => $this->_data['mid']))->select();
    	$area_ids = array();
    	foreach ($stores as $st) {

    		$photo_list = '';
    		$tmp_pic_arr = explode(';', $st['pic_info']);
    		foreach($tmp_pic_arr as $key=>$value){
    			$image_tmp = explode(',',$value);
    			$photo_list[] = array('local_img' => C('config.site_url') . '/upload/store/'.$image_tmp[0].'/'.$image_tmp['1']);
    		}
    		
    		
    		$t['store_id'] = $st['store_id'];
    		$t['storename'] = $st['name'];
    		$t['branchname'] = '';
    		$t['telephone'] = $st['phone'];
    		$t['longitude'] = $st['long'];
    		$t['latitude'] = $st['lat'];
    		
    		
    		if ($st['office_time']) {
    			$st['office_time'] = unserialize($st['office_time']);
    			$pre = $str = '';
    			foreach ($st['office_time'] as $time) {
    				$str .= $pre . $time['open'] . '-' . $time['close'];
    				$pre = ',';
    			}
    		} else {
    			if ($st['open_1'] == '00:00:00' && $st['close_1'] == '00:00:00') {
    				$str = '24小时营业';
    			} else {
    				$str = $st['open_1'] . '-' . $st['close_1'];
    				if ($st['open_2'] != '00:00:00' && $st['close_2'] != '00:00:00') {
    					$str .= ',' . $st['open_2'] . '-' . $st['close_2'];
    				}
    				if ($st['open_3'] != '00:00:00' && $store['close_3'] != '00:00:00') {
    					$str .= ',' . $st['open_3'] . '-' . $st['close_3'];
    				}
    			}
    		}
    		$t['open_time'] = $str;
    		
    		$t['categories'] = '';
    		$t['province'] = $st['province_id'];
    		$t['city'] = $st['city_id'];
    		$t['district'] = $st['area_id'];
    		
    		if (!in_array($st['province_id'], $area_ids)) $area_ids[] = $st['province_id'];
    		if (!in_array($st['city_id'], $area_ids)) $area_ids[] = $st['city_id'];
    		if (!in_array($st['area_id'], $area_ids)) $area_ids[] = $st['area_id'];
    		
    		$t['avg_price'] = $st['permoney'];
    		$t['address'] = $st['address'];
    		$t['photo_list'] = $photo_list;
    		$t['introduction'] = $st['txt_info'];
    		$t['recommend'] = '';
    		$t['special'] = $st['feature'];
    		if ($st['status'] == 1) {
    			$t['status'] = 3;
    		} elseif ($st['status'] == 2) {
    			$t['status'] = 2;
    		} elseif ($st['status'] == 4 || $st['status'] == 0) {
    			$t['status'] = 4;
    		}
    		
    		$t['thirdmid'] = $st['mer_id'];
    		$listdata[] = $t;
    	}
    	if ($area_ids) {
    		$areas = D('Area')->where(array('area' => array('in', $area_ids)))->select();
    		$list = array();
    		foreach ($areas as $area) {
    			$list[$area['area_id']] = $area;
    		}
    		
    		foreach ($listdata as &$row) {
				$row['province'] = isset($list[$row['province']]['area_name']) ? $list[$row['province']]['area_name'] : '';
				$row['city'] = isset($list[$row['city']]['area_name']) ? $list[$row['city']]['area_name'] : '';
				$row['district'] = isset($list[$row['district']]['area_name']) ? $list[$row['district']]['area_name'] : '';
    		}
    	}
    	
    	$outputStr = json_encode(array('totalcount' => count($stores), 'listdata' => $listdata));
    	$outputStr = $this->Encryptioncode($outputStr, 'ENCODE');
    	$outputStr = base64_encode($outputStr);
    	exit($outputStr);
    }
    
    public function staff() 
    {
    	$store_staffs = D('Merchant_store_staff')->field(true)->where("token='{$this->_data['mid']}'")->select();
    	$list = array();
    	foreach ($store_staffs as $row) {
    		$t['account'] = $row['username'];
    		$t['staffname'] = $row['name'];
    		$t['store_id'] = $row['store_id'];
    		$t['password'] = $row['password'];
    		$t['salt'] = '';
    		$t['status'] = 1;
    		$t['phone'] = $row['tel'];
    		$t['email'] = '';
    		$list[] = $t;
    	}
    	
    	$outputStr = json_encode(array('totalcount' => count($list), 'listdata' => $list));
    	$outputStr = $this->Encryptioncode($outputStr, 'ENCODE');
    	$outputStr = base64_encode($outputStr);
    	exit($outputStr);
    }

    /**
     * *获取订单对接数据接口
     * * POST数据格式：$PostData = array('mid' => $this->merchant['thirdmid'], 'orderidd' => $datas['tname']);
     * ** mid O2O商家id，orderidd O2O的支付订单号
      获取到JOSN数据格式：{"error":0,"msg":"ok","data":{"orderidd":"订单号","store_id":"1"
      "ispay":"0未支付1已支付","goodsid":"订单表自增id","goodsname":"商品名","euid":"员工id","goods_type":"商品类型",'mprice':56.25}}
     * * 必填字段为 orderidd  store_id ispay goodsname mprice
	 * * 其他非必填字段 如果没有值 请传空值或者0
     * *ispay订单支付状态 goodsname订单商品名或标题，比如 老乡鸡21桌订餐、老乡鸡100元代金券等
     * *goods_type 比如 老乡鸡餐饮、老乡鸡团购、电商下单
     * *mprice 订单应支付价格
     * * */
    public function order() 
    {
    	$mer_id = $this->_data['mid'];
    	$orderid = $this->_data['orderidd'];
    	$order_id = isset($this->_data['order_id']) ? $this->_data['order_id'] : '';
    	$type = $this->_data['type'];
    	$data = array();
    	switch ($type) {
			case 'meal':
				$order = D('Meal_order')->field(true)->where("order_id='{$orderid}' AND mer_id='{$mer_id}'")->find();
				if (empty($order)) {
					$outputStr = json_encode(array('error' => 1, 'msg' => '不存在的订单'));
					$outputStr = $this->Encryptioncode($outputStr, 'ENCODE');
					$outputStr = base64_encode($outputStr);
					exit($outputStr);
				}
				$data['goodsname'] = $order['orderid'];
    			$data['mprice'] = $order['price'];
				break;
			case 'group':
				$order = D('Group_order')->field(true)->where("order_id='{$orderid}' AND mer_id='{$mer_id}'")->find();
				
				if (empty($order)) {
					exit($this->data_format(array('error' => 1, 'msg' => '不存在的订单')));
				}
				$data['goodsname'] = $order['order_name'];
    			$data['mprice'] = $order['price'];
				break;
			case 'weidian':
				$order = D('Weidian_order')->field(true)->where("order_id='{$orderid}' AND mer_id='{$mer_id}'")->find();
				
				if (empty($order)) {
					exit($this->data_format(array('error' => 1, 'msg' => '不存在的订单')));
				}
				$data['goodsname'] = $order['order_name'];
    			$data['mprice'] = $order['money'];
				break;
			case 'wxapp':
				$order = D('Wxapp_order')->field(true)->where("order_id='{$orderid}' AND mer_id='{$mer_id}'")->find();
				
				if (empty($order)) {
					exit($this->data_format(array('error' => 1, 'msg' => '不存在的订单')));
				}
				$data['goodsname'] = $order['order_name'];
    			$data['mprice'] = $order['money'];
				break;
			case 'appoint':
				$order = D('Appoint_order')->field(true)->where("order_id='{$orderid}' AND mer_id='{$mer_id}'")->find();
				
				if (empty($order)) {
					exit($this->data_format(array('error' => 1, 'msg' => '不存在的订单')));
				}
				$data['goodsname'] = $order['appoint_id'];
    			$data['mprice'] = $order['payment_money'];
				break;
			default:
				$order = D('Store_order')->field(true)->where("orderid='{$order_id}' AND mer_id='{$mer_id}'")->find();
				if (empty($order)) {
					exit($this->data_format(array('error' => 1, 'msg' => '不存在的订单')));
				}
				$data['goodsname'] = $order['name'];
    			$data['mprice'] = $order['price'];
				break;
		}
		
		$data['goods_type'] = $type;
    	//$data['orderidd'] = $type . '_' . $order['order_id'];
		$data['orderidd'] = $order['order_id'];
    	$data['store_id'] = $order['store_id'];
    	$data['ispay'] = $order['paid'];
    	$data['goodsid'] = $order['order_id'];
    	$data['euid'] = $order['store_uid'];
    	$data['is_own'] = $order['is_own'];
    	$data['is_pay_bill'] = $order['is_pay_bill'];
    	
    	exit($this->data_format(array('error' => 0, 'msg' => 'ok', 'data' => $data)));
    }
    
    
    
	/**
	 * 
	 * mer_id o2o的商家id即收银台的thirdmid
	 * store_id o2o的店铺id即收银台中店铺的 pop_id
	 * total_price 订单总价
	 * discount_price 订单优惠的金额
	 * price 订单实际支付的金额
	 * staff_account 收银台中店员的账号
	 * orderid 订单id
	 * third_id 第三方支付id
	 * paid 支付状态
	 * pay_type 支付类型 weixin
	 * name 订单名称
	 * 
	 */
    public function storder()
    {
//     			$config_file = 't.php';
//     			$fp = fopen($config_file, 'a+');
//     			fwrite($fp, "<?php \nreturn " . stripslashes(var_export($this->_data, true)) . ";");
//     			fclose($fp);
    	
    	$data = $this->_data;
    	if ($data['paid'] == 1) {
    		$data['payment_money'] = $data['price'];
    	}
    	$staff = D('Merchant_store_staff')->where(array('store_id' => $data['store_id'], 'account' => $data['staff_account']))->find();
    	if (empty($staff))exit($this->data_format(array('error' => 1, 'msg' => '不存在的店员')));
    	$data['staff_name'] = $staff['name'];
    	$data['staff_id'] = $staff['id'];
    	$data['dateline'] = time();
    	$data['pay_time'] = time();
    	unset($data['staff_account']);
    	if ($order_id = D('Store_order')->add($data)) {
    		exit($this->data_format(array('error' => 0, 'msg' => 'ok', 'order_id' => $order_id)));
    	} else {
    		exit($this->data_format(array('error' => 1, 'msg' => '创建订单失败')));
    	}
    }

    /**
     * *订单支付成功通知对接数据接口
     * * POST数据格式：$PostData =array('orderidd'=>$o2oorder,'store_id'=>$data['storeid'],'euid'=>$data['eid'],
						'realprice'=>$data['goods_price'],'ispay'=>1,'pay_way'=>'weixin','wxtransaction_id'=>$transaction_id,'openid'=>$tmpopenid,'mprice'=>$data['mprice']);
     * ** orderidd订单号，store_id 门店Id ，euid员工id，realprice实际支付金额 ，mprice原来金额
	 * ** ispay 支付状态 1已支付，pay_way支付方式：weixin（微信支付），wxtransaction_id 微信订单号
	 * ** openid 支付用户openid

      获取到JOSN数据格式：{"error":0,"msg":"ok"}
	 * ** error 成功0 失败 1，msg 失败时 传过来的描述
     * **如果第一次失败 这边会在尝试再一次请求
     * * */
    public function notify() 
    {
    	$type = $this->_data['type'];
    	$data = array();
    	$data['pay_type'] = $this->_data['pay_way'];
    	$data['is_mobile'] = '0';
    	$data['order_type'] = $this->_data['type'];
    	$order_id = $this->_data['orderidd'];
		if(!is_numeric($order_id) && strpos($order_id, '_')){
		  $orderidArr=explode('_',$order_id);
		  $order_id=$orderidArr['1'];
		}
		$data['order_id'] =$order_id;
    	$data['is_own'] = $this->_data['is_own'];
    	$data['third_id'] = $this->_data['wxtransaction_id'];
    	$data['pay_money'] = $this->_data['realprice'];
    	
    	switch ($type) {
    		case 'group':
    			$return = D('Group_order')->after_pay($data);
    			break;
    		case 'meal':
    		case 'takeout':
    		case 'food':
    		case 'foodPad':
    			$return = D('Meal_order')->after_pay($data, $this->_data['type']);
    			break;
    		case 'weidian':
    			$return = D('Weidian_order')->after_pay($data);
    			break;
    		case 'recharge':
    			$return = D('User_recharge_order')->after_pay($data);
    			break;
    		case 'waimai':
    			$return = D('Waimai_order')->after_pay($data);
    			break;
    		case 'appoint':
    			$return = D('appoint_order')->after_pay($data);
    			break;
    		case 'wxapp':
    			$return = D('Wxapp_order')->after_pay($data);
    			break;
    	}
    	if (empty($return['error'])) {
    		$return = array('error' => 0, 'msg' => 'ok');
    	}
    	$outputStr = json_encode($return);
    	$outputStr = $this->Encryptioncode($outputStr, 'ENCODE');
    	$outputStr = base64_encode($outputStr);
    	exit($outputStr);
    }

    
    private function data_format($data)
    {
    	$outputStr = json_encode($data);
    	$outputStr = $this->Encryptioncode($outputStr, 'ENCODE');
    	$outputStr = base64_encode($outputStr);
    	return $outputStr;
    }
    
    public function refund()
    {
    	$type = $this->_data['type'];
    	$order_id = $this->_data['order_id'];
    	$orderid = $this->_data['orderid'];
//     			$config_file = 't.php';
//     			$fp = fopen($config_file, 'a+');
//     			fwrite($fp, "<?php \nreturn " . stripslashes(var_export($this->_data, true)) . ";");
//     			fclose($fp);
    	switch ($type) {
    		case 'group':
    			if ($now_order = D('Group_order')->field(true)->where("order_id='{$order_id}'")->find()) {
    				$data_group_order['order_id'] = $now_order['order_id'];
    				$data_group_order['refund_detail'] = serialize($this->_data['response']);
    				$data_group_order['status'] = $this->_data['status'];
    				if (D('Group_order')->data($data_group_order)->save()) {

		    			//退款时销量回滚
		    			D('Group')->where(array('group_id' => $now_order['group_id']))->setDec('sale_count', $now_order['num']);
		    			
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
    					exit($this->data_format(array('error' => 0, 'msg' => 'ok')));
    				} else {
    					exit($this->data_format(array('error' => 1, 'msg' => '取消订单失败！请重试。')));
    				}
    			}
    			break;
    		case 'meal':
    			if ($now_order = D('Meal_order')->field(true)->where("order_id='{$order_id}'")->find()) {
    				$data_group_order['order_id'] = $now_order['order_id'];
    				$data_group_order['refund_detail'] = serialize($this->_data['response']);
    				$data_group_order['status'] = $this->_data['status'];
    				if (D('Meal_order')->data($data_group_order)->save()) {					
    					if ($now_order['paid'] == 1 && date('m', $now_order['dateline']) == date('m')) {
							foreach (unserialize($now_order['info']) as $menu) {
								D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
							}
						}
						D("Merchant_store_meal")->where(array('store_id' => $now_order['store_id']))->setDec('sale_count', 1);
						
						//退款打印
						$msg = ArrayToStr::array_to_str($now_order['order_id']);
						$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
						$op->printit($now_order['mer_id'], $store_id, $msg, 1);
						
						$mer_store = D('Merchant_store')->where(array('mer_id' => $now_order['mer_id'], 'store_id' => $store_id))->find();
						$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
						if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
							$sms_data['uid'] = $now_order['uid'];
							$sms_data['mobile'] = $now_order['phone'];
							$sms_data['sendto'] = 'user';
							$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
							Sms::sendSms($sms_data);
						}
						if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
							$sms_data['uid'] = 0;
							$sms_data['mobile'] = $mer_store['phone'];
							$sms_data['sendto'] = 'merchant';
							$sms_data['content'] = '顾客' . $now_order['name'] . '的预定订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
							Sms::sendSms($sms_data);
						}
    					exit($this->data_format(array('error' => 0, 'msg' => 'ok')));
    				} else {
    					exit($this->data_format(array('error' => 1, 'msg' => '取消订单失败！请重试。')));
    				}		//退款时销量回滚

    			}
    			
    			break;
    		default:
    			$staff = D('Merchant_store_staff')->where(array('store_id' => $this->_data['store_id'], 'account' => $this->_data['staff_account']))->find();
    			if (empty($staff))exit($this->data_format(array('error' => 1, 'msg' => '不存在的店员')));
    			$data_group_order['staff_name'] = $staff['name'];
    			$data_group_order['staff_id'] = $staff['id'];
    			
    			if ($now_order = D('Store_order')->field(true)->where("orderid='{$orderid}'")->find()) {
    				$data_group_order['refund_txt'] = serialize($this->_data['response']);
    				$data_group_order['refund'] = $this->_data['status'] == 3 ? 1 : 2;
    				$data_group_order['refund_time'] = time();
    				$data_group_order['order_id'] = $now_order['order_id'];
    				if (D('Store_order')->data($data_group_order)->save()) {					
    					exit($this->data_format(array('error' => 0, 'msg' => 'ok')));
    				} else {
    					exit($this->data_format(array('error' => 1, 'msg' => '取消订单失败！请重试。')));
    				}		//退款时销量回滚

    			}
    			
    			break;
    	}
    }
    
    
    
    
    
    


	/**
	 * 加密和解密函数
	 * @access public
	 * @param  string  $string    需要加密或解密的字符串
	 * @param  string  $operation 默认是DECODE即解密 ENCODE是加密
	 * @param  string  $key       加密或解密的密钥 参数为空的情况下取全局配置encryption_key
	 * @param  integer $expiry    加密的有效期(秒)0是永久有效 注意这个参数不需要传时间戳
	 * @return string
	 * 请保持$key的值为 lhs_simple_encryption_code_87063 不要改变
	 * 不用动此函数 任何 代码！ 请直接套用即可
	 */
 
	 private function Encryptioncode($string, $operation = 'DECODE', $key = '', $expiry = 0) 
	 {
	    $ckey_length = 4;
	    $key = md5($key != '' ? $key : 'lhs_simple_encryption_code_87063');
	    $keya = md5(substr($key, 0, 16));
	    $keyb = md5(substr($key, 16, 16));
	    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
	
	    $cryptkey = $keya . md5($keya . $keyc);
	    $key_length = strlen($cryptkey);
	
	    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	    $string_length = strlen($string);
	
	    $result = '';
	    $box = range(0, 255);
	
	    $rndkey = array();
	    for ($i = 0; $i <= 255; $i++) {
	        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
	    }
	
	    for ($j = $i = 0; $i < 256; $i++) {
	        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
	        $tmp = $box[$i];
	        $box[$i] = $box[$j];
	        $box[$j] = $tmp;
	    }
	
	    for ($a = $j = $i = 0; $i < $string_length; $i++) {
	        $a = ($a + 1) % 256;
	        $j = ($j + $box[$a]) % 256;
	        $tmp = $box[$a];
	        $box[$a] = $box[$j];
	        $box[$j] = $tmp;
	        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	    }
	
	    if ($operation == 'DECODE') {
	        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
	            return substr($result, 26);
	        } else {
	            return '';
	        }
	    } else {
	        return $keyc . str_replace('=', '', base64_encode($result));
	    }
	}
}