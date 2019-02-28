<?php
/*
 * 社区首页
 *
 */
class Wxapp_houseAction extends BaseAction{
	
	//小区管家页面 - wangdong
	public function village_manager_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);	
			
	}
	
	//物业报修列表 - wangdong
	public function village_my_repairlists(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		$village_bind_id = I('bind_id'); 
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358605; // 多余的 回头要删除
			
			$database_house_village_user_bind = D('House_village_user_bind');
			$database_hosue_village_repair_list = D('House_village_repair_list');
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				if($village_bind_id){
					//说明已经定位业主绑定信息 首先判断绑定信息是否存在
					$bind_find = $database_house_village_user_bind->field(true)->where(array('uid'=>$info['uid'],'village_id'=>$village_id,'pigcms_id'=>$village_bind_id))->find();
					if($bind_find){
						//存在绑定信息  读取报修列表
						$lists = $database_hosue_village_repair_list->wxapp_getList($info['uid'] , $village_id , $village_bind_id , 1);
							
						foreach($lists as $k=>$v){
							$lists[$k]['add_date'] = date("Y-m-d H:i:s",$v['time']);
							$lists[$k]['pic'] = explode("|",$v['pic']);
							$lists[$k]['reply_pic'] = explode("|",$v['reply_pic']);
							$lists[$k]['comment_pic'] = explode("|",$v['comment_pic']);
							foreach($lists[$k]['pic'] as $m=>$n){
								if($n) $lists[$k]['pic'][$m] = $this->config['site_url'].'upload/house/'.$n;
							}
							foreach($lists[$k]['reply_pic'] as $m=>$n){
								if($n) $lists[$k]['reply_pic'][$m] = $this->config['site_url'].'upload/house/'.$n;
							}
							foreach($lists[$k]['comment_pic'] as $m=>$n){
								if($n) $lists[$k]['comment_pic'][$m] = $this->config['site_url'].'upload/house/'.$n;
							}
							
						}
						$arr['lists'] = $lists;
						$this->returnCode(0,$arr);
					}else{
						//不存绑定信息	
						$this->returnCode('20120002'); //你不属于当前小区  
					}
						
				}else{
					//如果没有定位  首先判断是否属于当前小区
					$where['uid'] = $info['uid'];
					$where['village_id'] = $now_village['village_id'];
					$where['status'] = 1;
					$bindinfo_list = $database_house_village_user_bind->wxapp_getSelect($where);
					if(count($bindinfo_list) > 0){
						//跳转选择房间列表	
						$this->returnCode(0,1); //1 表示本小区多个绑定信息 需要选择一个绑定信息
						//$this->returnCode(0,$arr);	
					}else{
						$this->returnCode('20120002');	//你不属于当前小区
					}
				}
				
					
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);	
	}
	
	//选择户号列表 - wangdong
	public function village_select(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358605; // 多余的 回头要删除
			
			$database_house_village_user_bind = D('House_village_user_bind');
			$database_house_village = D('House_village');
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				//查询当前用户在当前小区的绑定的信息
				$where['uid'] = $info['uid'];
				$where['village_id'] = $village_id;
				$where['status'] = 1;
				$lists = $database_house_village_user_bind->wxapp_getSelect($where);
				$arr['lists'] = $lists;
				$this->returnCode(0,$arr);
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);	
			
	}
	
	//投诉建议列表 - wangdong
	public function village_my_suggestlist(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		$village_bind_id = I('bind_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358605; // 多余的 回头要删除
			
			$database_house_village_user_bind = D('House_village_user_bind');
			$database_hosue_village_repair_list = D('House_village_repair_list');
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				if($village_bind_id){
					//说明已经定位业主绑定信息 首先判断绑定信息是否存在
					$bind_find = $database_house_village_user_bind->field(true)->where(array('uid'=>$info['uid'],'village_id'=>$village_id,'pigcms_id'=>$village_bind_id))->find();
					if($bind_find){
						//存在绑定信息  读取投诉建议
						$lists = $database_hosue_village_repair_list->wxapp_getList($info['uid'] , $village_id , $village_bind_id , 3);
						foreach($lists as $k=>$v){
							$lists[$k]['add_date'] = date("Y-m-d H:i:s",$v['time']);
							$lists[$k]['pic'] = explode("|",$v['pic']);
							$lists[$k]['reply_pic'] = explode("|",$v['reply_pic']);
							$lists[$k]['comment_pic'] = explode("|",$v['comment_pic']);
							foreach($lists[$k]['pic'] as $m=>$n){
								if($n) $lists[$k]['pic'][$m] = $this->config['site_url'].'upload/house/'.$n;
							}
							foreach($lists[$k]['reply_pic'] as $m=>$n){
								if($n) $lists[$k]['reply_pic'][$m] = $this->config['site_url'].'upload/house/'.$n;
							}
							foreach($lists[$k]['comment_pic'] as $m=>$n){
								if($n) $lists[$k]['comment_pic'][$m] = $this->config['site_url'].'upload/house/'.$n;
							}
						}
						$arr['lists'] = $lists;
						$this->returnCode(0,$arr);
					}else{
						//不存绑定信息	
						$this->returnCode('20120002'); //你不属于当前小区  
					}
						
				}else{
					//如果没有定位  首先判断是否属于当前小区
					$where['uid'] = $info['uid'];
					$where['village_id'] = $now_village['village_id'];
					$where['status'] = 1;
					$bindinfo_list = $database_house_village_user_bind->wxapp_getSelect($where);
					if(count($bindinfo_list) > 0){
						//跳转选择房间列表	
						$this->returnCode(0,1); //1 表示本小区多个绑定信息 需要选择一个绑定信息
						//$this->returnCode(0,$arr);	
					}else{
						$this->returnCode('20120002');	//你不属于当前小区
					}
				}
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);	
			
	}
	
	//小区缴费列表 - wangdong
	public function village_my_pay(){
	
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$arr = array();
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);	
		
	}
	
	//缴费类型 - wangdong
	public $pay_type = array(
			'property'=>'物业费',
			'water'=>'水费',
			'electric'=>'电费',
			'gas'=>'燃气费',
			'park'=>'停车费',
			'custom'=>'其他缴费',
	);
	
	//小区缴物业费 - wangdong
	public function village_pay(){
	
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		$bind_id	=	I('bind_id');
		$type = I('type');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village                  = D('House_village');
			$database_house_village_user_bind        = D('House_village_user_bind');
			$database_house_village_user_paylist     = D('House_village_user_paylist');
			$database_house_village_floor            = D('House_village_floor');
			$database_house_village_floor_type       = D('House_village_floor_type');
			$database_house_village_property_paylist = D('House_village_property_paylist');
			$database_house_village_property         = D('House_village_property');
			$database_house_village_config           = D('House_village_config');
			
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$pay_name = $this->pay_type[$type];
				if(empty($pay_name)){
					$this->returnCode('20120011'); //当前访问的缴费类型不存在
				}
				//判断用户是否属于当前小区
				$where['uid'] = $info['uid'];
				$where['village_id'] = $village_id;
				$where['pigcms_id'] = $bind_id;
				$where['status'] = 1;
				$bind_find = $database_house_village_user_bind->field(true)->where($where)->find();
				
				if(!empty($bind_find)){
					$pay_money = 0;
					switch($type){
						case 'property':
							if(empty($now_village['property_price'])) $this->returnCode('20120012');//当前小区不支持缴纳物业费
							
							$pay_money = $bind_find['property_price'];
							$order_list = $database_house_village_user_paylist->field('`ydate`,`mdate`,`property_price` AS `price`')->where(array('usernum'=>$bind_find['usernum']))->order('`pigcms_id` DESC')->select();
							foreach($order_list as $key=>$value){
								$order_list[$key]['desc'] = '物业费 '.floatval($value['price']).' 元';
							}
							if($bind_find['floor_id']){
								$floor_where['status'] =  1;
								$floor_where['floor_id'] = $bind_find['floor_id'];
								$floor_info = $database_house_village_floor->where($floor_where)->find();
								if($floor_info['property_fee'] != '0.00'){
									$bind_find['property_fee'] = $floor_info['property_fee'];
								}else{
									$village_info = $database_house_village->where(array('village_id'=>$now_village['village_id']))->find();
									$bind_find['property_fee'] = $village_info['property_price'];
								}
			
								$type_where['status'] = 1;
								$type_where['id'] = $floor_info['floor_type'];
								if($floor_type_name = $database_house_village_floor_type->where($type_where)->getField('name')){
									$bind_find['floor_type_name'] = $floor_type_name;
								}else{
									$bind_find['floor_type_name'] = '暂无';
								}
							}else{
								$village_info = $database_house_village->where(array('village_id'=>$now_village['village_id']))->find();
								$bind_find['property_fee'] = $village_info['property_price'];
							}
			
							$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$bind_find['pigcms_id']))->order('add_time asc')->select();
			
							if(!empty($pay_list)){
								$start_pay_info = reset($pay_list);
								$end_pay_info = end($pay_list);
								if($start_pay_info && $end_pay_info){
									$bind_find['property_time_str'] = date('Y-m-d',$start_pay_info['start_time']).'&nbsp;至&nbsp;'.date('Y-m-d',$end_pay_info['end_time']);
								}else{
									$bind_find['property_time_str'] = date('Y-m-d',$pay_list['start_time']).'&nbsp;至&nbsp;'.date('Y-m-d',$pay_list['end_time']);
								}
							}
							break;
							
						case 'water':
							if(empty($now_village['water_price'])) $this->returnCode('20120014');//当前小区不支持缴纳水费
							$pay_money = $bind_find['water_price'];
							$order_list =$database_house_village_user_paylist->field('`ydate`,`mdate`,`use_water` AS `use`,`water_price` AS `price`')->where(array('usernum'=>$bind_find['usernum']))->order('`pigcms_id` DESC')->select();
							foreach($order_list as $key=>$value){
								$order_list[$key]['desc'] = '用水 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
							}
							break;
						case 'electric':
							if(empty($now_village['electric_price'])) $this->returnCode('20120015');//当前小区不支持缴纳电费
							$pay_money = $bind_find['electric_price'];
							$order_list = $database_house_village_user_paylist->field('`ydate`,`mdate`,`use_electric` AS `use`,`electric_price` AS `price`')->where(array('usernum'=>$bind_find['usernum']))->order('`pigcms_id` DESC')->select();
							foreach($order_list as $key=>$value){
								$order_list[$key]['desc'] = '用电 '.floatval($value['use']).' 千瓦时(度)，总费用 '.floatval($value['price']).' 元';
							}
							break;
						case 'gas':
							if(empty($now_village['gas_price'])) $this->returnCode('20120016');//当前小区不支持缴纳燃气费
							$pay_money = $bind_find['gas_price'];
							$order_list = $database_house_village_user_paylist->field('`ydate`,`mdate`,`use_gas` AS `use`,`gas_price` AS `price`')->where(array('usernum'=>$bind_find['usernum']))->order('`pigcms_id` DESC')->select();
							foreach($order_list as $key=>$value){
								$order_list[$key]['desc'] = '使用燃气 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
							}
							break;
						case 'park':
							if(empty($now_village['park_price'])) $this->returnCode('20120017');//当前小区不支持缴纳停车费
							$pay_money = $bind_find['park_price'];
							$order_list = $database_house_village_user_paylist->field('`ydate`,`mdate`,`park_price` AS `price`')->where(array('usernum'=>$bind_find['usernum']))->order('`pigcms_id` DESC')->select();
							foreach($order_list as $key=>$value){
								$order_list[$key]['desc'] = '停车费 '.floatval($value['price']).' 元';
							}
							break;
						case 'custom':
							if(empty($now_village['has_custom_pay'])) $this->returnCode('20120018');//当前小区不支持缴纳其他费用
							break;
					}
					
					$arr['type'] = $type;
					$arr['pay_name'] = $pay_name;
					$arr['pay_money'] = $pay_money;
					$arr['order_list'] = $order_list;
					$arr['bind_find'] = $bind_find;
					$arr['now_village'] = $now_village;
		
					if($type == 'property'){
						$property_condition['village_id'] = $now_village['village_id'];
						$property_condition['status'] = 1;
						$property_list = $database_house_village_property->where($property_condition)->field(true)->order('property_month_num desc')->select();
						if(!$property_list){
							$this->returnCode('20120013'); //社区管理员暂未添加
						}
						$arr['property_list'] = $property_list;
					}
					$this->returnCode(0,$arr);
				}else{
					$this->returnCode('20120002');//您不属于当前小区
				}
				
				
				
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
		
	}
	
	//快递代收 - wangdong
	public function express_service_list(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358605; // 多余的 回头要删除
			$database_house_village = D('House_village');
			$database_house_village_express = D('House_village_express');
			$database_house_village_express_config = D('House_village_express_config');

			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$house_village_info = $database_house_village->get_one($village_id,'has_express_service');
				$has_express_service = $house_village_info['has_express_service'];
				if($has_express_service){
					$where['village_id'] = $village_id;
					$where['uid'] = $info['uid'];
					$list = $database_house_village_express->express_service_list($where);
					$express_config = $database_house_village_express_config->where(array('village_id'=>$village_id))->find();
					$express_config['status'] = $express_config['status'];
					foreach($list['list'] as $k=>$v){
						$list['list'][$k]['add_datetime'] = date('Y-m-d H:i',$v['add_time']);	
					}
					
					$arr['list'] = $list['list'];
					
					$arr['express_config'] = $express_config;
					$this->returnCode(0,$arr);
			  }else{
				  $this->returnCode('20120019'); //小区未开通相关服务
			  }	
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	}
	
	//预约上门送件 - wangdong
	public function express_appoint(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		$express_id = I('express_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
			$database_house_village_express = D('House_village_express');
			$database_house_village_express_config = D('House_village_express_config');
			$datatabase_house_village_express_order = D('House_village_express_order');

			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$where['express_id'] = $express_id;
				$now_order = $datatabase_house_village_express_order->house_village_express_order_detail($where);
	
				$now_order  = $now_order['detail'];
	
				$now_express = $database_house_village_express->where(array('id'=>$express_id))->find();
				if($now_order){
					if($now_order['paid'] > 0){
						$this->returnCode('20120020'); // 订单已支付,请您耐心等待
					}
				}
				$express_config = $database_house_village_express_config->where(array('village_id'=>$village_id))->find();
				$arr['express_config'] = $express_config;
				$arr['now_express']    = $now_express;
				$this->returnCode(0,$arr);
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
			
	}
	
	
	//社区团购列表 - wangdong
	public function village_grouplist(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
			$database_user_long_lat = D('User_long_lat');
			$database_config = D('Config');
			$database_house_village_group = D('House_village_group');

			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$system_config = $database_config->get_config();
				$arr['title'] = $system_config['group_alias_name'];
				
				$user_long_lat = $database_user_long_lat->getLocation($_SESSION['openid'],0);
				//推荐团购
				$group_list = $database_house_village_group->get_limit_list_page($now_village['village_id'],10,$user_long_lat,true);
				$arr['list'] =$group_list;
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
			
	}
	
	//社区团购详情 - wangdong
	public function group_detail(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}	
		$village_id	=	I('village_id');
		$pin_num = I('pin_num') + 0;
		$group_id = I('group_id') + 0;
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');
				//print_r($now_group);
				if(empty($now_group)){
					$this->returnCode('20120021'); //当前信息不存在
					//$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
				}
				if($now_group['end_time']<$_SERVER['REQUEST_TIME']){
					//$this->error_tips('当前团购已结束！');
					$this->returnCode('20120022'); //当前团购已结束
				}
				if($now_group['cue']){
					$now_group['cue_arr'] = unserialize($now_group['cue']);
				}
				if(!empty($now_group['pic_info'])){
					$merchant_image_class = new merchant_image();
					$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
				}
				
				//判断是否微信浏览器，
				$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		
				if($long_lat){
					$rangeSort = array();
					foreach($now_group['store_list'] as &$storeValue){
						$storeValue['Srange'] = getDistance($long_lat['lat'],$long_lat['long'],$storeValue['lat'],$storeValue['long']);
						$storeValue['range'] = getRange($storeValue['Srange'],false);
						$rangeSort[] = $storeValue['Srange'];
					}
					array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
					$arr['long_lat'] = $long_lat;
				}
				
				
				if($now_group['packageid']>0 && $now_group['pin_num']==0){
					$packages = M('Group_packages')->where(array('id' => $now_group['packageid'], 'mer_id' => $now_group['mer_id']))->find();
					if(!empty($packages['groupidtext'])){
						$mpackages = unserialize($packages['groupidtext']);
						$packagesgroupid = $this->check_group_status(array_keys($mpackages));
						if(is_array($packagesgroupid)){
							foreach($packagesgroupid as $gvv){
								$tmp_mpackages[$gvv['group_id']]=$mpackages[$gvv['group_id']];
							}
							$mpackages=$tmp_mpackages;
							unset($tmp_mpackages);
						}
					}else{
						$mpackages = false;
					}
					//$this->assign('mpackages',$mpackages);
					$arr['mpackages'] = $mpackages;
				}
				
				if($info['uid']){
					$database_user_collect = D('User_collect');
					$condition_user_collect['type'] = 'group_detail';
					$condition_user_collect['id'] = $now_group['group_id'];
					$condition_user_collect['uid'] = $info['uid'];
					if($database_user_collect->where($condition_user_collect)->find()){
						$now_group['is_collect'] = true;
					}
		
					//判断积分抵现
					$user_coupon_use = D('User')->check_score_can_use($info['uid'],$now_group['price'],'group',$now_group['group_id'],$now_group['mer_id']);
					//$this->assign('user_coupon_use',$user_coupon_use);
					$arr['user_coupon_use'] = $user_coupon_use;
				}
				
				$now_group['wx_cheap']  =   floatval($now_group['wx_cheap']);
				
				//拼团团购详情页面 参团价格分割
				if($now_group['pin_num']>0) {
					$now_group['old_price_e'] = explode('.', sprintf('%.2f', $now_group['old_price']));
					$now_group['price_e'] = explode('.', sprintf('%.2f', $now_group['price']));
					$group_start = D('Group_start');
					$start_num = $group_start->where(array('group_id' => $now_group['group_id'], 'status' => 0))->count();
					//$start_head = M('Group_start')->where(array('group_id' => $now_group['group_id'], 'status' => 0,'uid'=>$_SESSION['user']['uid']))->find();
		
					//$this->assign('start_head', $start_head);  //判断当前团购是否自己是团长，使团长详情页不能参团
					//$this->assign('start_num', $start_num);  //当前可参加团购数量
					//根据gid 参加团长发起的团购小组并检查有效性
					$now_start = $group_start->where(array('id'=>$_GET['gid']))->find();
					if (!$now_start['status']&&isset($_GET['gid']) && !empty($_GET['gid'])) {
						$in_group = false;
						$start_user_arr = $group_start->get_buyerer_by_order_id('',$_GET['gid']);
						foreach($start_user_arr as $st){
							if($st['uid']==$_SESSION['user']['uid']){
								$in_group = true;
							}
						}
						if(!$in_group){
							$group_share_info = $group_start->get_group_start_user_by_gid($_GET['gid']);
							$end_time = $group_share_info['start_time'] + $now_group['pin_effective_time'] * 3600;
							$effective_time = $end_time - $_SERVER['REQUEST_TIME'];
							$efftime['h'] = floor($effective_time / 3600);
							$efftime['m'] = floor(($effective_time - $efftime['h'] * 3600) / 60);
							$efftime['s'] = $effective_time - $efftime['h'] * 3600 - $efftime['m'] * 60;
							if ($effective_time > 0) {
								//$this->assign('effective_time', $efftime);
								$arr['effective_time'] = $efftime;
								//$this->assign('group_share_info', $group_share_info);
								$arr['group_share_info'] = $group_share_info;
							} else {
								$group_start->update_start_group($_GET['gid'], 2); //2 团购小组超时
							}
						}
					}else{
						$can_join = D('Group_start')->check_join_pin($now_group['group_id'],$info['uid'],$now_group['pin_effective_time']);
						if($can_join){
							$now_group['can_join'] = true;
							$now_group['pin_need_num'] = $can_join;
						}else{
							$now_group['can_join'] = fales;
							$now_group['pin_need_num'] = 0;
						}
					}
		
		
				}
				foreach( $now_group['all_pic'] as $v_img){
					$now_group['img_arr'][] = $v_img['m_image'];
				}
		
				//$this->assign('now_group',$now_group);
				$arr['now_group'] = $now_group;
				
				//组团购
				if(!empty($_GET['fid']) && $now_group['group_share_num']>0){
					$_SESSION['fid']=$_GET['fid'];
				}else{
					unset($_SESSION['fid']);
				}
				//新拼团
				unset($_SESSION['gid']);
				if(!empty($_GET['gid'])){
					$this_share_group = M('Group_start')->where(array('id'=>$_GET['gid']))->find();
					if(!$now_start['status']&&$now_group['group_id']==$this_share_group['group_id']){
						$_SESSION['gid']=$_GET['gid'];
					}
				}
		
				$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];
				$time_array['d'] = floor($time/86400);
				$time_array['h'] = floor($time%86400/3600);
				$time_array['m'] = floor($time%86400%3600/60);
				$time_array['s'] = floor($time%86400%60);
				//$this->assign('time_array',$time_array);
				$arr['time_array'] = $time_array;
		
				if($now_group['reply_count']){
					$reply_list = D('Reply')->get_reply_list($now_group['group_id'],0,count($now_group['store_list']),3);
					//$this->assign('reply_list',$reply_list);
					$arr['reply_list'] = $reply_list;
				}
		
		
				$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_group['mer_id'],3,true,$now_group['group_id']);
				//$this->assign('merchant_group_list',$merchant_group_list);
				$arr['merchant_group_list'] = $merchant_group_list;
		
				//分类下其他团购
				$category_group_list = D('Group')->get_grouplist_by_catId($now_group['cat_id'],$now_group['cat_fid'],3,true);
				foreach($category_group_list as $key=>$value){
					if($value['group_id'] == $now_group['group_id']){
						unset($category_group_list[$key]);
					}
				}
				//$this->assign('category_group_list',$category_group_list);
				$arr['category_group_list'] = $category_group_list;
		
				/* 粉丝行为分析 */
				D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));
		
				/* 粉丝行为分析 */
				$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id'],'keyword'=>strval($_GET['keywords'])));
		
				if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_group['mer_id']))->select()) {
					$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
					$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_group['mer_id'];
					//$this->assign('kf_url', $kf_url);
					$arr['kf_url'] = $kf_url;
				}
		
				//if($now_group['pin_num']>0){
					//$this->display('pin_detail');
				//}else{
					//$this->display();
				//}
				
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	}
	private function check_group_status($groupids=array()){
		if(!empty($groupids)){
			$tmpids=M('Group')->where('group_id in('.implode(',',$groupids).') and status="1"')->field('group_id')->select();
			return $tmpids;
		}
		return false;
	}
	
	/*粉丝行为分析、统计*/
	public function behavior($param=array(),$extra_param=false){
		$openid = $_SESSION['openid'];

		if(empty($param) || empty($openid)){
			return false;
		}

		if(empty($param['model'])){
			$param['model'] = MODULE_NAME.'_'.ACTION_NAME;
		}

		$database_behavior = M('Behavior');

		$data_behavior = $param;
		$data_behavior['openid'] = $openid;
		$data_behavior['date'] = $data_behavior['last_date'] = $_SERVER['REQUEST_TIME'];
		$database_behavior->data($data_behavior)->add();
	}
	
	public function get_encrypt_key($array,$app_key){
		$new_arr = array();
		ksort($array);
		foreach($array as $key=>$value){
			$new_arr[] = $key.'='.$value;
		}
		$new_arr[] = 'app_key='.$app_key;

		$string = implode('&',$new_arr);
		return md5($string);
	}
	
	//社区团购下订单 - wangdong
	public function group_buy(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$village_id	=	I('village_id');
		$group_id = I('group_id') + 0;
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$now_user = D('User')->get_user($info['uid']);
				$_SESSION['user']['phone'] = $now_user['phone'];
				$arr['now_user'] = $now_user;
				
				
				$now_group = D('Group')->get_group_by_groupId($group_id);
				if(empty($now_group)){
					$this->returnCode('20120021'); //当前信息不存在
				}
		
				if($now_group['begin_time'] > $_SERVER['REQUEST_TIME']){
					$this->returnCode('20120023'); //此单团购还未开始
				}
				if($now_group['type'] > 2){
					$this->returnCode('20120024'); //此单团购已结束
				}
				
				//用户等级 优惠
				$level_off = false;
				$finalprice = 0;
				$user_= M('User')->where(array('uid'=>$info['uid']))->find();
				$this->user_session['level'] = $user_['level'];
				
				if(!empty($this->user_level) && !empty($now_user) && isset($this->user_session['level'])){
					$leveloff=!empty($now_group['leveloff']) ? unserialize($now_group['leveloff']) :'';
					/****type:0无优惠 1百分比 2立减*******/
					if(!empty($leveloff) && isset($leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])){
						$level_off=$leveloff[$this->user_session['level']];
						if($level_off['type']==1){
						  $finalprice=$now_group['price']*($level_off['vv']/100);
						  $finalprice=$finalprice>0 ? $finalprice : 0;
						  $level_off['offstr']='单价按原价'.$level_off['vv'].'%来结算';
		
						}elseif($level_off['type']==2){
						  $finalprice=$now_group['price']-$level_off['vv'];
						  $finalprice=$finalprice>0 ? $finalprice : 0;
						  $level_off['offstr']='单价立减'.$level_off['vv'].'元';
		
						}
					}
				}
				is_array($level_off) && $level_off['price']=round($finalprice,2);
				unset($leveloff);
				
				if($now_group['tuan_type'] == 2){
					$now_group['user_adress'] = D('User_adress')->get_one_adress($info['uid'],intval($_GET['adress_id']));
				}
				$now_group['wx_cheap'] = floatval($now_group['wx_cheap']);
				$pick_list = D('Pick_address')->get_pick_addr_by_merid($now_group['mer_id']);
				if(!empty($_GET['pick_addr_id'])){
					foreach($pick_list as $k=>$v){
						if($v['pick_addr_id']==$_GET['pick_addr_id']){
							$pick_address = $v;
							break;
						}
					}
				}else{
					$pick_address =$pick_list[0];
				}
				$arr['pick_address'] = $pick_address;
				$arr['leveloff'] = $level_off;
				$arr['finalprice'] = $finalprice;
	
				if($_GET['type']==1){
					$now_group['price'] = $now_group['old_price'];
					$now_group['extra_pay_price']=$now_group['extra_pay_old_price'];
				}elseif($_GET['type']==3){
					$now_group['price'] = $now_group['price']*$now_group['start_discount']/100; //团长按团长折扣计算
				}else{
					$now_group['price'] = $now_group['price'];
				}
	
				$arr['now_group'] = $now_group;
				
				if($_SESSION['user']['phone']){
					$arr['pigcms_phone'] = substr($_SESSION['user']['phone'],0,3).'****'.substr($_SESSION['user']['phone'],7);
				}else{
					$arr['pigcms_phone'] = "您需要绑定手机号码";
				}
				/* 粉丝行为分析 */
				D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));
	
				/* 粉丝行为分析 */
				$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id']));
							
				if($now_group['trade_type'] == 'hotel'){
					$trade_hotel['time_dep_time'] = date('Y-m-d');
					$trade_hotel['show_dep_time'] = date('m-d');
					$trade_hotel['dep_time'] = date('Ymd');
					$trade_hotel['time_end_time'] = date('Y-m-d',time()+86400);
					$trade_hotel['show_end_time'] = date('m-d',time()+86400);
					$trade_hotel['end_time'] = date('Ymd',time()+86400);
					$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$trade_hotel['dep_time'],$trade_hotel['end_time']);
					$hotel_cat_id = explode(',',$now_group['trade_info']);
					foreach ($hotel_cat_id as $vc) {
						$hotel_list[$vc] = $hotel_list_tmp[$vc];
					}
					$arr['trade_hotel'] = $trade_hotel; 
					$arr['hotel_list'] = $hotel_list;
				}
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	}
	
	//用户收货地址 - 列表 - wangdong
	public function my_address(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}	
		$village_id	=	I('village_id');
		$group_id = I('group_id') + 0;
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$adress_list = D('User_adress')->get_adress_list($info['uid']);

				if(empty($adress_list)){
					$this->returnCode('20120025'); //您还没有添加收货地址
				}else{
					
					$arr['adress_list'] = $adress_list;
	
					$database_area = D('Area');
					$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
					$arr['now_city_area'] = $now_city_area;
		
					$province_list = $database_area->get_arealist_by_areaPid(0);
					$arr['province_list'] = $province_list;
		
					$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
					$arr['city_list'] = $city_list;
		
					$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
					$arr['area_list'] = $area_list;
		
					$id = $_GET['adress_id'];
					
					$now_adress = D('User_adress')->get_adress($info['uid'], $id);
					if ($now_adress) {
						$arr['now_adress'] = $now_adress;
	
						$province_list = $database_area->get_arealist_by_areaPid(0);
						$arr['province_list'] = $province_list;
	
						$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
						$arr['city_list'] = $city_list;
	
						$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
						$arr['area_list'] = $area_list;
					} else {
						$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
						$arr['now_city_area'] = $now_city_area;
	
						$province_list = $database_area->get_arealist_by_areaPid(0);
						$arr['province_list'] = $province_list;
	
						$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
						$arr['city_list'] = $city_list;
	
						$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
						$arr['area_list'] = $area_list;
					}
					
				}
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	}
	
	//到店自提 地址选择 - wangdong
	public function pick_address(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$village_id	=	I('village_id');
		$group_id = I('group_id') + 0;
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$flag = $_GET['buy_type'] == 'shop' || $_GET['buy_type'] == 'mall' ? true : false;
				$adress_list = D('Pick_address')->get_pick_addr_by_merid($_GET['mer_id'], $flag);
				if(empty($adress_list)){
					$this->returnCode('20120026'); // 地址信息错误请联系管理员
				}else{
					$arr['pick_list'] = $adress_list;
				}
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	}
	
	//周边快店列表 - wangdong
	public function village_shoplist(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}	
		$village_id	=	I('village_id');
		$group_id = I('group_id') + 0;
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$key = isset($_GET['key']) ? htmlspecialchars($_GET['key']) : '';
				$cat_url = isset($_GET['cat_url']) ? htmlspecialchars($_GET['cat_url']) : 'all';
				$order = isset($_GET['sort_url']) ? htmlspecialchars($_GET['sort_url']) : 'juli';
				$deliver_type = isset($_GET['type_url']) ? htmlspecialchars($_GET['type_url']) : 'all';
				$lat = isset($_GET['user_lat']) ? htmlspecialchars($_GET['user_lat']) : 0;
				$long = isset($_GET['user_long']) ? htmlspecialchars($_GET['user_long']) : 0;
				$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
				$is_wap = $_GET['is_wap'] + 0;
				$page = max(1, $page);
				$cat_id = 0;
				$cat_fid = 0;
				if ($cat_url != 'all') {
					$now_category = D('Shop_category')->get_category_by_catUrl($cat_url);
					if ($now_category) {
						if ($now_category['cat_fid']) {
							$cat_id = $now_category['cat_id'];
							$cat_fid = $now_category['cat_fid'];
						} else {
							$cat_id = 0;
							$cat_fid = $now_category['cat_id'];
						}
					}
				}
		
				$where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
				$key && $where['key'] = $key;
		
				if($is_wap > 0){
					$lists = D('Merchant_store_shop')->get_list_by_option($where,$is_wap);
				}else{
					$lists = D('Merchant_store_shop')->get_list_by_option($where);
				}
				$return = array();
				$now_time = date('H:i:s');
		
				foreach ($lists['shop_list'] as $row) {
					$temp = array();
					$temp['id'] = $row['store_id'];
					$temp['name'] = $row['name'];
					$temp['range'] = $row['range'];
					$temp['image'] = $row['image'];
					$temp['star'] = $row['score_mean'];
					$temp['month_sale_count'] = $row['sale_count'];
					$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
					$temp['delivery_time'] = $row['send_time'];//配送时长
					$temp['delivery_price'] = floatval($row['basic_price']);//起送价
					$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
					$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
					$temp['is_close'] = 1;
					$temp['isverify'] = $row['isverify'];
		
					if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
						$temp['time'] = '24小时营业';
						$temp['is_close'] = 0;
					} else {
						$temp['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
						if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
							$temp['is_close'] = 0;
						}
						if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
							$temp['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
							if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
								$temp['is_close'] = 0;
							}
						}
						if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
							$temp['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
							if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
								$temp['is_close'] = 0;
							}
						}
					}
		
					$temp['coupon_list'] = array();
					if ($row['is_invoice']) {
						$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
					}
					if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
						$temp['coupon_list']['discount'] = $row['store_discount']/10;
					}
					$system_delivery = array();
					foreach ($row['system_discount'] as $row_d) {
						if ($row_d['type'] == 0) {//新单
							$temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
						} elseif ($row_d['type'] == 1) {//满减
							$temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
						} elseif ($row_d['type'] == 2) {//配送
							if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
								$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
							}
						}
					}
					foreach ($row['merchant_discount'] as $row_m) {
						if ($row_m['type'] == 0) {
							$temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
						} elseif ($row_m['type'] == 1) {
							$temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
						}
					}
					if ($row['deliver']) {
						if ($temp['delivery_system']) {
							$system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
						} else {
							if ($row['is_have_two_time']) {
								if ($row['reach_delivery_fee_type2'] == 0) {
									if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
										$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
									}
								} elseif ($row['reach_delivery_fee_type2'] == 1) {
									//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
								} elseif ($row['reach_delivery_fee_type2'] == 2) {
									$row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
								}
							} else {
								if ($row['reach_delivery_fee_type'] == 0) {
									if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
										$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
									}
								} elseif ($row['reach_delivery_fee_type'] == 1) {
									//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
								} elseif ($row['reach_delivery_fee_type'] == 2) {
									$row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
								}
							}
						}
					}
					$temp['coupon_count'] = count($temp['coupon_list']);
					$return[] = $temp;
				}
				$arr['store_list'] = $return;
				$arr['has_more'] = $lists['has_more'] ? true : false;
				//echo json_encode(array('store_list' => $return, 'has_more' => $lists['has_more'] ? true : false));
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	}
	
	
	//周边店铺详情页面 - wandong
	public function village_shop_detial(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$village_id	=	I('village_id');
		$group_id = I('group_id') + 0;
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$store_id = intval($_GET['shopid']) ? intval($_GET['shopid']) :  2;
				$where = array('store_id' => $store_id);
				$now_store = D('Merchant_store')->field(true)->where($where)->find();
		
				//资质认证
				if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
					$this->returnCode('20120027'); //店铺正在认证中
				}
				$now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
				$now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
				if (empty($now_shop) || empty($now_store)) {
					//echo json_encode(array());
					//exit;
					$this->returnCode('20120028'); //店铺不存在
				}
				$auth_files = array();
				if (!empty($now_store['auth_files'])) {
					$auth_file_class = new auth_file();
					$tmp_pic_arr = explode(';', $now_store['auth_files']);
					foreach($tmp_pic_arr as $key => $value){
						$auth_files[] = $auth_file_class->get_image_by_path($value, 'm');//array('title' => $value, 'url' => $auth_file_class->get_image_by_path($value, 's'));
					}
				}
				$now_store['auth_files'] = $auth_files;
// 				$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
				$discounts = D('Shop_discount')->getDiscounts($now_store['mer_id'], $store_id);
				$row = array_merge($now_store, $now_shop);
		
				$store = array();
				$store_image_class = new store_image();
				$images = $store_image_class->get_allImage_by_path($row['pic_info']);
		
				$store['id'] = $row['store_id'];
		
				$store['phone'] = $row['phone'];
				$store['long'] = $row['long'];
				$store['lat'] = $row['lat'];
				$store['store_theme'] = $row['store_theme'];
				$store['is_mult_class'] = $row['is_mult_class'];
				$store['adress'] = $row['adress'];
				$store['is_close'] = 1;
				$store['isverify'] = $now_mer['isverify'];
				$now_time = date('H:i:s');
				if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
					$store['time'] = '24小时营业';
					$store['is_close'] = 0;
				} else {
					$store['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
					if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
						$store['is_close'] = 0;
					}
					if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
						$store['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
						if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
							$store['is_close'] = 0;
						}
					}
					if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
						$store['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
						if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
							$store['is_close'] = 0;
						}
					}
				}
		
				$store['home_url'] = U('Index/index', array('token' => $row['mer_id']));
				$store['name'] = $row['name'];
				$store['store_notice'] = $row['store_notice'];
				$store['txt_info'] = $row['txt_info'];
				$store['image'] = isset($images[0]) ? $images[0] : '';
				$store['auth_files_str'] = implode(',', $auth_files);
				$store['auth_files'] = $auth_files;
				$store['images'] = $images;
				$store['images_str'] = implode(',', $images);
				$store['star'] = $row['score_mean'];
				$store['month_sale_count'] = $row['sale_count'];
				$store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
				$store['delivery_time'] = $row['send_time'];//配送时长
				$store['delivery_price'] = floatval($row['basic_price']);//起送价
		
				$is_have_two_time = 0;//是否是第二时段的配送显示
		
				if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
					if ($this->config['delivery_time']) {
						$delivery_times = explode('-', $this->config['delivery_time']);
						$start_time = $delivery_times[0] . ':00';
						$stop_time = $delivery_times[1] . ':00';
						if (!($start_time == $stop_time && $start_time == '00:00:00')) {
							if ($this->config['delivery_time2']) {
								$delivery_times2 = explode('-', $this->config['delivery_time2']);
								$start_time2 = $delivery_times2[0] . ':00';
								$stop_time2 = $delivery_times2[1] . ':00';
								if (!($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
									$is_have_two_time = 1;
								}
							}
						}
					}
		
					if ($is_have_two_time) {
						if ($now_time <= $stop_time || $now_time > $stop_time2) {
							$is_have_two_time = 0;
						}
					}
		
					if ($row['s_is_open_own']) {
						if ($is_have_two_time) {
							$store['delivery_money'] = $row['s_free_type2'] == 0 ? 0 : $row['s_delivery_fee2'];
						} else {
							$store['delivery_money'] = $row['s_free_type'] == 0 ? 0 : $row['s_delivery_fee'];
						}
					} else {
						$store['delivery_money'] = $is_have_two_time ? C('config.delivery_fee2') : C('config.delivery_fee');
					}
				} else {
					if (!($row['delivertime_start'] == $row['delivertime_stop'] && $row['delivertime_start'] == '00:00:00')) {
						if (!($row['delivertime_start2'] == $row['delivertime_stop2'] && $row['delivertime_start2'] == '00:00:00')) {
							$is_have_two_time = 1;
						}
					}
					if ($is_have_two_time) {
						if ($now_time <= $row['delivertime_stop'] || $now_time > $row['delivertime_stop2']) {
							$is_have_two_time = 0;
						}
					}
					$store['delivery_money'] = $is_have_two_time ? $row['delivery_fee2'] : $row['delivery_fee'];
				}
		
		
		
				$store['delivery_money'] = floatval($store['delivery_money']);
		// 		$store['delivery_money'] = $row['deliver_type'] == 0 ? C('config.delivery_fee') : $row['delivery_fee'];//配送费
		// 		$store['delivery_money'] = floatval($store['delivery_money']);//配送费
				$store['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
				if (in_array($row['deliver_type'], array(2, 3, 4))) {
					$store['pick'] = 1;//是否支持自提
				} else {
					$store['pick'] = 0;//是否支持自提
				}
				$store['pack_alias'] = $row['pack_alias'];//打包费别名
				$store['freight_alias'] = $row['freight_alias'];//运费别名
				$store['coupon_list'] = array();
				if ($row['is_invoice']) {
					$store['coupon_list']['invoice'] = floatval($row['invoice_price']);
				}
				if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
					$store['coupon_list']['discount'] = $row['store_discount']/10;
				}
				$system_delivery = array();
				if (isset($discounts[0]) && $discounts[0]) {
					foreach ($discounts[0] as $row_d) {
						if ($row_d['type'] == 0) {//新单
							$store['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
						} elseif ($row_d['type'] == 1) {//满减
							$store['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
						} elseif ($row_d['type'] == 2) {//配送
							$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
						}
					}
				}
				if (isset($discounts[$store_id]) && $discounts[$store_id]) {
					foreach ($discounts[$store_id] as $row_m) {
						if ($row_m['type'] == 0) {
							$store['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
						} elseif ($row_m['type'] == 1) {
							$store['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
						}
					}
				}
				if ($store['delivery']) {
					if ($store['delivery_system']) {
						$system_delivery && $store['coupon_list']['delivery'] = $system_delivery;
					} else {
						if ($is_have_two_time) {
							if ($row['reach_delivery_fee_type2'] == 0) {
								if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
									$store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
								}
							} elseif ($row['reach_delivery_fee_type'] == 1) {
								//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
							} else {
								$row['delivery_fee2'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
							}
						} else {
							if ($row['reach_delivery_fee_type'] == 0) {
								if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
									$store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
								}
							} elseif ($row['reach_delivery_fee_type'] == 1) {
								//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
							} else {
								$row['delivery_fee'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
							}
						}
					}
				}
				$today = date('Ymd');
		
				$product_list = D('Shop_goods')->get_list_by_storeid($store_id);
				foreach ($product_list as $row) {
					$temp = array();
					$temp['cat_id'] = $row['sort_id'];
					$temp['cat_name'] = $row['sort_name'];
					$temp['sort_discount'] = $row['sort_discount']/10;
					foreach ($row['goods_list'] as $r) {
						$glist = array();
						$glist['product_id'] = $r['goods_id'];
						$glist['product_name'] = $r['name'];
						$glist['product_price'] = $r['price'];
						$glist['is_seckill_price'] = $r['is_seckill_price'];
						$glist['o_price'] = $r['o_price'];
						$glist['number'] = $r['number'];
						$glist['packing_charge'] = floatval($r['packing_charge']);
						$glist['unit'] = $r['unit'];
						if (isset($r['pic_arr'][0])) {
							$glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
						}
						$glist['product_sale'] = $r['sell_count'];
						$glist['product_reply'] = $r['reply_count'];
						$glist['has_format'] = false;
						if ($r['spec_value'] || $r['is_properties']) {
							$glist['has_format'] = true;
						}
						if($r['extra_pay_price']>0){
							$glist['extra_pay_price']=$r['extra_pay_price'];
							$glist['extra_pay_price_name']=$this->config['extra_price_alias_name'];
						}
		
						$r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
						if ($today == $r['sell_day']) {
							$glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
						} else {
							$glist['stock'] = $r['stock_num'];
						}
						$temp['product_list'][] = $glist;
					}
					$list[] = $temp;
				}
				$arr['store'] = $store;
				$arr['product_list'] = $list;
				//echo json_encode(array('store' => $store, 'product_list' => $list));
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
		
		
			
	}
	
	
	//周边店铺详情 - 评论 - wandong
	public function village_shop_reply(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}	
		$village_id	=	I('village_id');
		$group_id = I('group_id') + 0;
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				//order_type parent_id showCount
				$reply_return = D('Reply')->get_page_reply_list($_GET['parent_id'],$_GET['order_type'],$_GET['tab'],$_GET['order'],$_GET['store_count'],$_GET['showCount']);
				$arr['reply_return'] = $reply_return['count'] ? $reply_return : 0;	
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	
	}
	
	
	//周边店铺 商品详情 - wandong
	public function village_shop_goods(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}	
		$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 1;
				if(empty($goods_id)){
					$this->returnCode('20120029'); //商品不存在
				}
				$database_shop_goods = D('Shop_goods');
				$now_goods = $database_shop_goods->get_goods_by_id($goods_id);
				if(empty($now_goods)){
					//$this->error_tips('商品不存在！');
					$this->returnCode('20120029'); //商品不存在
				}
				//echo json_encode($now_goods);
				$arr['now_goods'] = $now_goods;
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	
	}
	
	
	//周边店铺 订单页面 - wandong
	public function confirm_order(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}	
		$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			//$info['uid'] = 1358644; // 多余的 回头要删除
			
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
				$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
				if ($order_id && ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $info['uid'])))) {
					$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $order['info'], 0);
					//$this->assign('order_id', $order_id);
					$arr['order_id'] = $order_id;
				} else {
					$cookieData = $this->getCookieData($store_id);
					if(empty($cookieData)) {
						$this->returnCode('20120030'); //请选择商品
						//redirect(U('Shop/index') . '#shop-' . $store_id);
						//exit;
					}
					$return = D('Shop_goods')->checkCart($store_id, $info['uid'], $cookieData);
				}
				
				if ($return['error_code']){
					$arr['error_msg'] = $return['msg'];
				}
				if(!$return['error_code']){
					//$village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
					//$this->assign('village_id', $village_id);
					$arr['village_id'] = $village_id;
					$is_own = 0;
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id' => $return['mer_id']))->find();
					foreach ($merchant_ownpay as $ownKey => $ownValue) {
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
			// 				$is_own = 1;
						}
					}
					if ($is_own) {
						if ($return['delivery_type'] == 0) {
							//$this->error_tips('商家配置的配送信息不正确');
							$this->returnCode('20120031'); //商家配置的配送信息不正确
						} elseif ($return['delivery_type'] == 3) {
							$return['delivery_type'] = 2;
						}
					}
			
					$basic_price = $return['price'];
					if($this->config['open_extra_price']>0){
						$basic_price += $return['extra_price'];
					}
					$return['price'] = round($return['vip_discount_money'] - round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'], 2), 2);//实际要支付的价格
			
					$advance_day = $return['store']['advance_day'];
					$advance_day = empty($advance_day) ? 1 : $advance_day;
			
					if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {
						$delivery_times = explode('-', $this->config['delivery_time']);
						$start_time = $delivery_times[0] . ':00';
						$stop_time = $delivery_times[1] . ':00';
			
						$delivery_times2 = explode('-', $this->config['delivery_time2']);
						$start_time2 = $delivery_times2[0] . ':00';
						$stop_time2 = $delivery_times2[1] . ':00';
			
					} else {
						$start_time = $return['store']['delivertime_start'];
						$stop_time = $return['store']['delivertime_stop'];
			
						$start_time2 = $return['store']['delivertime_start2'];
						$stop_time2 = $return['store']['delivertime_stop2'];
					}
			
					$have_two_time = 1;//是否两个时段 0：没有，1有
			
					$is_cross_day_1 = 0;//第一时间段是否跨天 0：不跨天，1：跨天
					$is_cross_day_2 = 0;//第二时间段是否跨天 0：不跨天，1：跨天
			
					$time = time() + $return['store']['send_time'] * 60;//默认的期望送达时间
			
					$format_second_time = 1;//是否要格式化时间段二
			
					$now_time_value = 1;//当前所处的时间段
					if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
						$start_time = strtotime(date('Y-m-d ') . '00:00');
						$stop_time = strtotime(date('Y-m-d ') . '23:59');
						$have_two_time = 0;
					} else {
						$start_time = strtotime(date('Y-m-d ') . $start_time);
						$stop_time = strtotime(date('Y-m-d ') . $stop_time);
						if ($stop_time < $start_time) {
							$stop_time = $stop_time + 86400;
							$is_cross_day_1 = 1;
						}
			
						if ($time < $start_time) {
							$time = $start_time;
						} elseif ($start_time <= $time && $time <= $stop_time) {
			
						} else {
							$format_second_time = 0;
							if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
								$have_two_time = 0;
								$time = $start_time + 86400;
								$start_time2 = strtotime(date('Y-m-d ') . '00:00');
								$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
							} else {
								$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
								$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
								if ($stop_time2 < $start_time2) {
									$stop_time2 = $stop_time2 + 86400;
									$is_cross_day_2 = 1;
								}
			
								if ($time < $start_time2) {
									$time = $start_time2;
									$now_time_value = 2;
								} elseif ($start_time2 <= $time && $time <= $stop_time2) {
									$now_time_value = 2;
								} else {
									$time = $start_time + 86400;
								}
							}
						}
					}
					if ($format_second_time) {//是否要格式化时间段二
						if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {
							$have_two_time = 0;
							$start_time2 = strtotime(date('Y-m-d ') . '00:00');
							$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
						} else {
							$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
							$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
							if ($stop_time2 < $start_time2) {
								$stop_time2 = $stop_time2 + 86400;
								$is_cross_day_2 = 1;
							}
						}
					}
			
					if ($have_two_time) {
						//$this->assign(array('time_select_1' => date('H:i', $start_time) . '-' . date('H:i', $stop_time), 'time_select_2' => date('H:i', $start_time2) . '-' . date('H:i', $stop_time2)));
						$arr['time_select_1'] = date('H:i', $start_time) . '-' . date('H:i', $stop_time);
						$arr['time_select_2'] = date('H:i', $start_time2) . '-' . date('H:i', $stop_time2);
					}
					$arr['have_two_time'] = $have_two_time;
					$arr['arrive_date'] = date('Y-m-d', $time);
					$arr['arrive_time'] = date('H:i', $time);
					$arr['now_time_value'] = $now_time_value;
					//$this->assign('have_two_time', $have_two_time);
					//$this->assign('arrive_date', date('Y-m-d', $time));
					//$this->assign('arrive_time', date('H:i', $time));
					//$this->assign('now_time_value', $now_time_value);
			
			
					$date['minYear'] = date('Y', $time);
					$date['minMouth'] = date('n', $time) - 1;
					$date['minDay'] = date('j', $time);
			
			
					$date['minHour_today'] = date('G', $time);
					$date['minMinute_today'] = date('i', $time);
			
					$date['minHour_tomorrow'] = date('G', $start_time);
					$date['minMinute_tomorrow'] = date('i', $start_time);
			
					if ($time < $start_time2) {
						$date['minHour_today2'] = date('G', $start_time2);
						$date['minMinute_today2'] = date('i', $start_time2);
					} else {
						$date['minHour_today2'] = date('G', $time);
						$date['minMinute_today2'] = date('i', $time);
					}
					$date['minHour_tomorrow2'] = date('G', $start_time2);
					$date['minMinute_tomorrow2'] = date('i', $start_time2);
			
					$date['maxYear_today'] = date('Y', $stop_time);
					$date['maxMouth_today'] = date('n', $stop_time) - 1;
					$date['maxDay_today'] = date('j', $stop_time);
			
					$date['maxYear_today2'] = date('Y', $stop_time2);
					$date['maxMouth_today2'] = date('n', $stop_time2) - 1;
					$date['maxDay_today2'] = date('j', $stop_time2);
			
			
					$time = strtotime("+{$advance_day} day") + $return['store']['send_time'] * 60;
					$date['maxYear'] = date('Y', $time);
					$date['maxMouth'] = date('n', $time) - 1;
					$date['maxDay'] = date('j', $time);
			
					$date['maxHour'] = date('G', $stop_time);
					$date['maxMinute'] = date('i', $stop_time);
			
					$date['maxHour2'] = date('G', $stop_time2);
					$date['maxMinute2'] = date('i', $stop_time2);
			
					$date['today'] = date('Y-m-d');
			
					$date['is_cross_day_1'] = $is_cross_day_1;
					$date['is_cross_day_2'] = $is_cross_day_2;
					$arr['date'] = $date;
					//$this->assign($date);
			
					if ($return['store']['basic_price'] <= $basic_price) {
						$address_id = isset($_GET['adress_id']) ? intval($_GET['adress_id']) : cookie('userLocationId');
						$user_adress = D('User_adress')->get_one_adress($info['uid'], intval($address_id));
						$arr['user_adress'] =  $user_adress;
						//$this->assign('user_adress', $user_adress);
					} else {
						if (in_array($return['delivery_type'], array(2, 3, 4))) {
							$return['delivery_type'] = 2;
						} else {
							//$this->error_tips('没有达到起送价，不予以配送');
							$this->returnCode('20120032'); //没有达到起送价，不予以配送
						}
					}
			
					//计算配送费
					if ($user_adress) {
						$distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
						$distance = $distance / 1000;
						$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
						$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
						$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
						$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
			
						$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
						$return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
						$return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
						$return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
					}
					$pick_addr_id = isset($_GET['pick_addr_id']) ? $_GET['pick_addr_id'] : '';
					$pick_list = D('Pick_address')->get_pick_addr_by_merid($return['mer_id'], true);
					if ($pick_addr_id) {
						foreach ($pick_list as $k => $v) {
							if ($v['pick_addr_id'] == $pick_addr_id) {
								$pick_address = $v;
								break;
							}
						}
					} else {
						$pick_address = $pick_list[0];
					}
					$pick_address['distance'] = $this->wapFriendRange($pick_address['distance']);
					$arr['return'] = $return;
					$arr['pick_addr_id'] = $pick_addr_id;
					$arr['pick_address'] = $pick_address;
					//$this->assign($return);
					//$this->assign('pick_addr_id', $pick_addr_id);
					//$this->assign('pick_address', $pick_address);
			
					$now_store_category_relation = M('Shop_category_relation')->where(array('store_id'=>$return['store_id']))->find();
					$now_store_category = M('Shop_category')->where(array('cat_id'=>$now_store_category_relation['cat_id']))->find();
					if($now_store_category['cue_field']){
						//$this->assign('cue_field',unserialize($now_store_category['cue_field']));
						$arr['cue_field'] = unserialize($now_store_category['cue_field']);
					}
				}
				//$this->display();
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	
	}
	
	
	//社区预约 - 列表 - wandong
	public function village_appointlist(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			$database_house_village = D('House_village');
			$database_house_village_appoint = D('House_village_appoint');
			
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		
				//推荐预约
				$appoint_list = $database_house_village_appoint->get_limit_list_page($village_id,10,$user_long_lat);
				foreach($appoint_list['appoint_list'] as $k=>$v){
					$appoint_list['appoint_list'][$k]['pic'] = explode(";",$v['pic']);
					foreach($appoint_list['appoint_list'][$k]['pic'] as $m=>$n){
						$arrPic = explode(",",$n); 
						$appoint_list['appoint_list'][$k]['pic'][$m] = 	$this->config['site_url'].'upload/appoint/'.$arrPic[0].$arrPic[1];
					}	
				}
				
				$arr['appoint_list'] = $appoint_list;
				$arr['appoint_alias_name'] = $this->config['appoint_alias_name'];
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	
	}
	
	
	//社区预约详情 - wangdang
	public function village_appoint_detail(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			
			$database_house_village = D('House_village');
			$database_house_village_appoint = D('House_village_appoint');
			
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$data_appoint_custom_field = D('Appoint_custom_field');
				$data_user_collect = D('User_collect');
				$database_appoint_order = D('Appoint_order');
				$database_appoint = D('Appoint');
				$database_appoint_product = D('Appoint_product');
				$database_reply = D('Reply');
				$database_appoint_category = D('Appoint_category');
				$database_appoint_comment = D('Appoint_comment');
		
				$appoint_id = $_GET['appoint_id'] + 0;
		
				if (empty($appoint_id)) {
					$this->returnCode('20120033'); //当前预约项不存在
				}
		
				$now_group = $database_appoint->get_appoint_by_appointId($appoint_id, 'hits-setInc');
				if (empty($now_group)) {
					$this->returnCode('20120033'); //当前预约项不存在
				}else{
					$now_appoint_category = $database_appoint_category->get_category_by_id($now_group['cat_id']);
				}
		
				$comment_list = $database_appoint_comment->where(array('appoint_id'=>$appoint_id))->limit(3)->select();
				foreach ($comment_list as $key => $value) {
					$sum = $value['profession_score']+$value['communicate_score']+$value['speed_score'];
					$comment_list[$key]['sum'] = 5-intval($sum/3);
					$comment_list[$key]['comment_img'] = unserialize(htmlspecialchars_decode($value['comment_img']));
					if($comment_list[$key]['comment_img']){
						
						foreach($comment_list[$key]['comment_img'] as $k=>$v){
							$comlist .= ",".$this->config['site_url'].$v;	
						}
						$comment_list[$key]['comment_img'] = explode(",",substr($comlist,1));	
					}
					
					
				}
				$comment_count = $database_appoint_comment->where(array('appoint_id'=>$appoint_id))->count();
				$arr['comment_count'] = $comment_count;
				$arr['comment_list']  = $comment_list;
		
				//统计月销量
				$BeginDate = date('Y-m-01', strtotime(date("Y-m-d")));
				$EndDate = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
				$where['order_time'] = array(array('egt', strtotime($BeginDate)), array('lt', strtotime($EndDate)));
				$where['appoint_id'] = $appoint_id;
				$now_month_sales = $database_appoint_order->where($where)->count();
				$now_group['now_month_sales'] = $now_month_sales;
				$merchant_workers_info['now_month_sales'] = $now_month_sales;
		
				if (count($now_group['store_list']) == 1) {
					$now_group['tel'] = $now_group['store_list'][0]['phone'];
				} else {
					$database_merchant = D('Merchant');
					$merchant_where['mer_id'] = $now_group['mer_id'];
					$now_group['tel'] = $database_merchant->where($merchant_where)->getField('phone');
				}
		
				$appoint_reply_list = $database_reply->get_appointReply_list($now_group['appoint_id']);
				$now_group['reply_num'] = count($appoint_reply_list);
		
		
				$store_id = $_GET['store_id'] + 0;
				if (!empty($store_id)) {
					$arr['store_id']  = $store_id;
				}
				$merchant_group_list = $database_appoint->get_appointlist_by_MerchantId($now_group['mer_id'], 3, true, $now_group['appoint_id']);
				$arr['merchant_group_list']  = $merchant_group_list;
				$product_condition['appoint_id'] = $_GET['appoint_id'] + 0;
				$appoint_product_list = $database_appoint_product->where($product_condition)->select();
		
				if(count($appoint_product_list) == 1){
					$appoint_product_list[0]['is_active'] = 1;
					$now_group['appoint_price'] = $appoint_product_list[0]['price'];
					$now_group['payment_money'] = $appoint_product_list[0]['payment_price'];
				}else if($appoint_product_list){
					$tmp_appoint_product_list = array();
					foreach($appoint_product_list AS $uniqid => $row){
						foreach($row AS $key=>$value){
							$tmp_appoint_product_list[$key][$uniqid] = $value;
						}
					}
		
					$sort = array(
						'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
						'field'     => 'payment_price',       //排序字段
					);
					array_multisort($tmp_appoint_product_list[$sort['field']], constant($sort['direction']) ,$appoint_product_list);
					$appoint_product_list[0]['is_active'] = 1;
					$now_group['appoint_price'] = $appoint_product_list[0]['price'];
					$now_group['payment_money'] = $appoint_product_list[0]['payment_price'];
				}
		
		
				$arr['now_group']  = $now_group;
		
				// 粉丝行为分析
				D('Merchant_request')->add_request($now_group['mer_id'], array('appoint_hits' => 1));
		
				$arr['appoint_product_list']  = $appoint_product_list;
		
				if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_group['mer_id']))->select()) {
					$key = $this->get_encrypt_key(array('app_id' => $this->config['im_appid'], 'openid' => $_SESSION['openid']), $this->config['im_appkey']);
					$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com') . '/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_group['mer_id'];
					$arr['kf_url']  = $kf_url;
				}
		
				//自定义表单
				$appoint_custom_field_list = $data_appoint_custom_field->where(array('appoint_id' => $appoint_id))->order('appoint_custom_field_sort asc')->select();
				$arr['appoint_custom_field_list']  = $appoint_custom_field_list;
		
				$collect_where['type'] = 'appoint_detail';
				$collect_where['id'] = $appoint_id;
				$collect_where['uid'] = $_SESSION['user']['uid'];
				$collect_num = $data_user_collect->where($collect_where)->count();
				$arr['collect_num']  = $collect_num;
		
				//可选技师s
				$database_merchant_workers = D('Merchant_workers');
				$worker_list = $database_merchant_workers->get_appoint_worker_list($appoint_id);
				if ($worker_list) {
					$arr['worker_list']  = $worker_list;
				}
		
				$collection_info = D('Appoint_collection')->where(array('appoint_id'=>intval($_GET['appoint_id']),'uid'=>$info['uid']))->find();
				if($collection_info){
					$arr['collection_info']  = $collection_info;
				}
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
			
	}
	
	//周边预约详情 - 查看更多分店 - wandong
	public function village_appoint_branch(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}	
		$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			$database_house_village = D('House_village');
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$now_appoint = D('Appoint')->get_appoint_by_appointId($_GET['appoint_id'], 'hits-setInc');
				if (empty($now_appoint)) {
					$this->returnCode('20120033'); //当前预约项不存在
				}
				$arr['now_appoint'] = $now_appoint;
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	
	}
	
	//周边预约下订单页面 - wandong
	public function village_appoint_order(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}	
		$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			$database_house_village = D('House_village');
	
			$now_village = $database_house_village->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$database_merchant_workers = D('Merchant_workers');
				$database_appoint_store = D('Appoint_store');
				$database_user_long_lat = D('User_long_lat');
				$database_user = D('User');
				$database_appoint = D('Appoint');
				$database_appoint_product =  D('Appoint_product');
				$database_area = D('Area');
				$database_appoint_order = D('Appoint_order');
				$database_appoint_category = D('Appoint_category');
		
				$merchant_workers_id = $_GET['merchantWorkerId'] + 0;
				$appoint_id = $_GET['appoint_id'] + 0;
				
				$now_user = $database_user->get_user($info['uid']);
				if (!empty($now_user['phone'])) {
					$_SESSION['user']['phone'] = $now_user['phone'];
				}
				$arr['now_user'] = $now_user;
		
				if (empty($appoint_id)) {
					$this->returnCode('20120033'); //当前预约不存在
				}
		
				$now_appoint = $database_appoint->get_appoint_by_appointId($appoint_id, 'hits-setInc');
				if (empty($now_appoint)) {
					$this->returnCode('20120033'); //当前预约项不存在
				}
		
				if ($now_appoint['start_time'] > $_SERVER['REQUEST_TIME']) {
					$this->returnCode('20120034'); //当前预约项还未开始
				}
		
				// 产品列表
				$appointProduct = $database_appoint_product->get_productlist_by_appointId($appoint_id);
		
				if ($appointProduct) {
					$arr['appointProduct'] = $appointProduct;
					if (empty($_GET['menuId'])) {
						$defaultAppointProduct = $appointProduct[0];
					} else {
						foreach ($appointProduct as $value) {
							if ($value['id'] == $_GET['menuId']) {
								$defaultAppointProduct = $value;
								break;
							}
						}
						if (empty($defaultAppointProduct)) {
							$defaultAppointProduct = $appointProduct[0];
						}
					}
		
					$arr['defaultAppointProduct'] = $defaultAppointProduct;
		
					if (empty($_GET['merchantWorkerId'])) {
					} else {
						foreach ($appointProduct as $value) {
							if ($value['id'] == $_GET['merchantWorkerId']) {
								break;
							}
						}
					}
				}
		
				$now_appoint['store_list'] = $database_appoint_store->get_storelist_by_appointId($now_appoint['appoint_id']);
		
				$long_lat = $database_user_long_lat->getLocation('onfo6tySRgO5tYJtkJ4tAueQI51g');
				if (!empty($long_lat)) {
					foreach ($now_appoint['store_list'] as &$value) {
						$value['range'] = getDistance($value['lat'], $value['long'], $long_lat['lat'], $long_lat['long']);
						$value['range_txt'] = getRange($value['range']);
						$rangeSort[] = $value['range'];
						array_multisort($rangeSort, SORT_ASC, $now_appoint['store_list']);
					}
					$arr['long_lat'] = $long_lat;
				}
		
				$now_city = $database_area->get_area_by_areaId($this->config['now_city']);
				$arr['city_name'] = $now_city['area_name'];
		
				$tmp_gap = $now_appoint['time_gap'];
				$beforeTime = $now_appoint['before_time'] > 0 ? ($now_appoint['before_time']) * 3600 : 0;
				$gap = $tmp_gap > 0 ? $tmp_gap * 60 : 1800;
		
				if (!empty($merchant_workers_id)) {
					// 预约开始时间 结束时间
					$merchant_workers_info = $database_merchant_workers->where(array('merchant_worker_id' => $merchant_workers_id))->find();
					$office_time = unserialize($merchant_workers_info['office_time']);
		
					$tmp_gap = $merchant_workers_info['time_gap'];
					// 发起预约时候的起始时间 还有提前多长时间可预约
					$beforeTime = $merchant_workers_info['before_time'] > 0 ? ($merchant_workers_info['before_time']) * 3600 : 0;
					$gap = $tmp_gap > 0 ? $tmp_gap * 60 : 1800;
				} else {
					$office_time = unserialize($now_appoint['office_time']);
					if(!empty($office_time[0]['open']) && !empty($office_time[0]['close'])){
						$office_time['open'] = $office_time[0]['open'];
						$office_time['close'] = $office_time[0]['close'];
					}
				}
		
				// 如果设置的营业时间为0点到0点则默认是24小时营业
				if ((count($office_time) < 1)|| (($office_time['open'] == '00:00') && ($office_time['close']=='00:00'))) {
					$office_time['open'] = '00:00';
					$office_time['close'] = '24:00';
				} else {
					foreach ($office_time as $i => $time) {
						if ($time['open'] == '00:00' && $time['close'] == '00:00') {
							unset($office_time[$i]);
						}
					}
				}
				$startTime = strtotime(date('Y-m-d') . ' ' . $office_time['open']);
				$endTime = strtotime(date('Y-m-d') . ' ' . $office_time['close']);
				for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
					$tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
					$tempTime[$tempKey]['time'] = $tempKey;
					$tempTime[$tempKey]['start'] = date('H:i', $time);
					$tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
					$tempTime[$tempKey]['order'] = 'no';
					if ((date('H:i') < date('H:i', $time - $beforeTime))) {
						$tempTime[$tempKey]['order'] = 'yes';
					}
				}
		
				$startTimeAppoint = $now_appoint['start_time'] > strtotime('now') ? $now_appoint['start_time'] : strtotime('now');
		
				if($tmp_gap > 0){
					$endTimeAppoint = $now_appoint['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_appoint['end_time'];
				}else{
					$endTimeAppoint = $now_appoint['end_time'] > strtotime('+30 day') ? strtotime('+30 day') : $now_appoint['end_time'];
				}
		
		
				$dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
				$dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
				for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
					$dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
				}
				ksort($dateArray);
		
				if($tmp_gap > 0){
					foreach ($dateArray as $i => $date) {
						$timeOrder[$date] = $tempTime;
					}
					ksort($timeOrder);
		
		
					foreach ($timeOrder as $i => $tem) {
						foreach ($tem as $key => $temval)
							if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
								$timeOrder[$i][$key]['order'] = 'no';
							} elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
								$timeOrder[$i][$key]['order'] = 'yes';
							}
					}
		
					// 查询可预约时间点
					if ($now_appoint['is_store']) {
						$appoint_num = $database_appoint_order->get_worker_appoint_num($now_appoint['appoint_id'], $merchant_workers_id);
					} else {
						$appoint_num = $database_appoint_order->get_appoint_num($now_appoint['appoint_id']);
					}
		
					if (count($appoint_num) > 0) {
						foreach ($appoint_num as $val) {
							$key = date('Y-m-d', strtotime($val['appoint_date']));
							if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
								if (isset($timeOrder[$key]) && (1 == $val['appointNum'])) {
									$timeOrder[$key][$val['appoint_time']]['order'] = 'all';
								}
							}
						}
					}
					$arr['timeOrder'] = $timeOrder;
				}else{
					$arr['timeOrder'] = $dateArray;
				}
		
		
		
				// 自定义表单项
				$category = $database_appoint_category->get_category_by_id($now_appoint['cat_id']);
				if (empty($category['cue_field'])) {
					$category = $database_appoint_category->get_category_by_id($category['cat_fid']);
				}
				if ($category) {
					$cuefield = unserialize($category['cue_field']);
					foreach ($cuefield as $val) {
						$sort[] = $val['sort'];
					}
					array_multisort($sort, SORT_DESC, $cuefield);
				}
				$this->assign('formData', $cuefield);
		
				if (isset($merchant_workers_id) && !empty($merchant_workers_id)) {
					$_where['merchant_worker_id'] = $merchant_workers_id;
					$default_workers_info = $database_merchant_workers->where($_where)->find();
					$default_workers_info['avatar_path'] = str_replace(',','/',$default_workers_info['avatar_path']);
					$default_workers_info['desc'] = htmlspecialchars_decode($default_workers_info['desc']);
					$arr['default_workers_info'] = $default_workers_info;
					$arr['default_store_id'] = $default_workers_info['merchant_store_id'];
				}
		
		
				if (IS_POST) {
					$now_appoint['cue_field'] = serialize($_POST['custom_field']);
					$now_appoint['appoint_date'] = $_POST['service_date'];
					$now_appoint['appoint_time'] = $_POST['service_time'];
					$now_appoint['store_id'] = !empty($_POST['store_id']) ? $_POST['store_id'] + 0 : 0;
					$now_appoint['product_id'] = $_POST['product_id'] ? $_POST['product_id'] + 0 : 0;
					if (empty($this->user_session['phone'])) {
						$this->returnCode('20120035'); //您需要绑定手机号码
					}
					if(!empty($now_appoint['is_store']) && empty($now_appoint['store_id'])){
						$this->returnCode('20120036'); //店铺不能为空
					}
		
		
					$merchant_workers_id = $_POST['merchant_workers_id'] + 0;
		
					$result = $database_appoint_order->save_post_form($now_appoint, $info['uid'], 0, $merchant_workers_id);
					if ($result['error'] == 1) {
						$this->error_tips($result['msg']); 
					}
		
					// 如果需要定金
					if (intval($now_appoint['payment_status']) == 1) {
						$href = U('Pay/check', array('order_id' => $result['order_id'], 'type' => 'appoint'));
					} else {
						$now_order = $database_appoint_order->where(array('order_id' => $result['order_id']))->find();
						$database_appoint_supply = D('Appoint_supply');
						$supply_data['appoint_id'] = $now_order['appoint_id'];
						$supply_data['mer_id'] = $now_order['mer_id'];
						$supply_data['store_id'] = $now_order['store_id'];
						$supply_data['create_time'] = time();
						$supply_data['worker_id'] = $now_order['merchant_worker_id'];
						$supply_data['start_time'] = $_SERVER['REQUEST_TIME'];
						$supply_data['paid'] = $now_order['paid'];
						if($now_order['merchant_worker_id']){
							$supply_data['status'] =  2;
						}else{
							$supply_data['status'] =  1;
						}
						$supply_data['uid'] =  $now_order['uid'];
						$supply_data['pay_type'] = $now_order['pay_type'];
						$supply_data['order_time'] = $now_order['order_time'];
						$supply_data['deliver_cash'] = floatval($now_order['product_price'] - $now_order['product_card_price'] - $now_order['product_merchant_balance'] - $now_order['product_balance_pay'] - $now_order['product_payment_money'] - $now_order['product_score_deducte'] - $now_order['product_coupon_price']);
						$supply_data['order_id'] = $now_order['order_id'];
						$database_appoint_supply->data($supply_data)->add();
						$href = U('My/appoint_order', array('order_id' => $result['order_id']));
					}
					$this->success($href);
				} else {
					if ($this->user_session['phone']) {
						$arr['pigcms_phone'] = substr($this->user_session['phone'], 0, 3) . '****' . substr($this->user_session['phone'], 7);
					} else {
						$arr['pigcms_phone'] = "您需要绑定手机号码";
					}
		
					$arr['now_appoint'] = $now_appoint;
				}
			}
		}else{
			$this->returnCode('30000001'); //社区ID不能为空
		}
		$this->returnCode(0,$arr);
	
	}
	
	
	private function getCookieData($store_id)
    {
        $productCart = json_decode(cookie('shop_cart_' . $store_id), true);
        if (empty($productCart)) {
            $productCart = array();
            for ($i = 0; $i < 20; $i++) {
                $tmpCookie = cookie('shop_cart_' . $store_id . '_' . $i);
                if (!empty($tmpCookie)) {
                    $tmpArr = json_decode($tmpCookie, true);
                    if (empty($tmpArr)) {
                        $tmpArr = array();
                    }
                    $productCart = array_merge($productCart, $tmpArr);
                } else {
                    break;
                }
            }
        }
        return $productCart;
    }
	
	//新版社区首页接口
	public function index_new(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$village_info = M('House_village')->field(array('qrcode_id','wx_image','wx_desc'),true)->where(array('village_id'=>$village_id))->find();
				$village_info	=	isset($village_info)?$village_info:array();
				unset($village_info['pwd']);
				if($village_info['province_id']){
					$province_id	=	$this->getCityId($village_info['province_id']);
					$village_info['default']['province_name']	=	$province_id['area_name'];
					$village_info['default']['province_id']	=	$village_info['province_id'];
				}
				if($village_info['city_id']){
					$city_id	=	$this->getCityId($village_info['city_id']);
					$village_info['default']['city_name']	=	$city_id['area_name'];
					$village_info['default']['city_id']	=	$village_info['city_id'];
				}
				if($village_info['area_id']){
					$area_id	=	$this->getCityId($village_info['area_id']);
					$village_info['default']['area_name']	=	$area_id['area_name'];
					$village_info['default']['area_id']	=	$village_info['area_id'];
				}
				if($village_info['circle_id']){
					$circle_id	=	$this->getCityId($village_info['circle_id']);
					$village_info['default']['circle_name']	=	$circle_id['area_name'];
					$village_info['default']['circle_id']	=	$village_info['circle_id'];
				}
				if(empty($village_info['default'])){
					$village_info['default']	=	(object)array();
				}
				$village_info['longs']	=	$village_info['long'];
				$village_info['lats']	=	$village_info['lat'];
				$village_info['now_city']	=	$this->config['now_city'];
				$village_info['many_city']	=	$this->config['many_city'];
				unset($village_info['long'],$village_info['lat']);
				$arr	=	array(
					'village'	=>	isset($village_info)?$village_info:(object)array(),
				);
				//查询小区新闻
				$news_list = D('House_village_news')->get_limit_list($village_id,3);
				foreach($news_list as $k=>$v){
					$news_list[$k]['add_date'] = date("m-d",$v['add_time']);	
				}
				$arr['news_list'] = $news_list;
				
				//查询小区活动
				$database_house_village_activity = D('House_village_activity');
				$activity_where['status'] = 1;
				$activity_where['village_id'] = $village_id;
				$activity_where['pic'] = array('neq' , '');
				$activity_list = $database_house_village_activity->house_village_activity_page_list($activity_where,true,'sort desc',3);
				foreach($activity_list['list']['list'] as $k=>$v){
					$activity_list['list']['list'][$k]['arrpic'] = explode(";",$v['pic']);
					foreach($activity_list['list']['list'][$k]['arrpic'] as $m=>$n){
						$activity_list['list']['list'][$k]['arrpic'][$m] = $this->config['site_url'].'/upload/activity/'.$n;
					}
					$activity_list['list']['list'][$k]['activity_start_date'] = date("Y-m-d",$v['activity_start_time']);
					$activity_list['list']['list'][$k]['activity_end_date'] = date("Y-m-d",$v['activity_end_time']);
					$activity_list['list']['list'][$k]['apply_end_date'] = date("Y-m-d",$v['apply_end_time']);
					$activity_list['list']['list'][$k]['stop_apply_date'] = date("Y-m-d",$v['stop_apply_time']);	
					$activity_list['list']['list'][$k]['last_date'] = date("Y-m-d",$v['last_time']);	
					$activity_list['list']['list'][$k]['add_date'] = date("Y-m-d",$v['add_time']);	
					
				}
				unset($activity_list['list']['pagebar']);
				$arr['activity_list'] = $activity_list['list'];
				
				//小区周边快店
				$database_merchant_store_shop = D('Merchant_store_shop');
				$merchant_store_shop_where['lat'] = $now_village['lat'];
				$merchant_store_shop_where['long'] = $now_village['long'];
				$merchant_store_shop_lists = $database_merchant_store_shop->get_list_by_option($merchant_store_shop_where);
				$merchant_store_shop_result = array();
				$deliver_type = 'all';
				$now_time = date('H:i:s');
				foreach ($merchant_store_shop_lists['shop_list'] as $row) {
					$temp = array();
					$temp['id'] = $row['store_id'];
					$temp['name'] = $row['name'];
					$temp['range'] = $row['range'];
					$temp['image'] = $row['image'];
					$temp['star'] = $row['score_mean'];
					$temp['month_sale_count'] = $row['sale_count'];
					$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
					$temp['delivery_time'] = $row['send_time'];//配送时长
					$temp['delivery_price'] = floatval($row['basic_price']);//起送价
					$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
					$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
					$temp['is_close'] = 1;
					$temp['txt_info'] = $row['txt_info'];
		
					if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
						$temp['time'] = '24小时营业';
						$temp['is_close'] = 0;
					} else {
						$temp['time'] = $row['open_1'] . '~' . $row['close_1'];
						if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
							$temp['is_close'] = 0;
						}
						if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
							$temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
							if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
								$temp['is_close'] = 0;
							}
						}
						if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
							$temp['time'] .= ',' . $row['open_3'] . '~' . $row['close_3'];
							if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
								$temp['is_close'] = 0;
							}
						}
					}
		
					$temp['coupon_list'] = array();
					if ($row['is_invoice']) {
						$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
					}
					if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
						$temp['coupon_list']['discount'] = $row['store_discount']/10;
					}
					$system_delivery = array();
					foreach ($row['system_discount'] as $row_d) {
						if ($row_d['type'] == 0) {//新单
							$temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
						} elseif ($row_d['type'] == 1) {//满减
							$temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
						} elseif ($row_d['type'] == 2) {//配送
							if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
								$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
							}
						}
					}
					foreach ($row['merchant_discount'] as $row_m) {
						if ($row_m['type'] == 0) {
							$temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
						} elseif ($row_m['type'] == 1) {
							$temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
						}
					}
					if ($row['deliver']) {
						if ($temp['delivery_system']) {
							$system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
						} else {
							if ($row['is_have_two_time']) {
								if ($row['reach_delivery_fee_type2'] == 0) {
									if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
										$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
									}
								} elseif ($row['reach_delivery_fee_type2'] == 1) {
									//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
								} elseif ($row['reach_delivery_fee_type2'] == 2) {
									$row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
								}
							} else {
								if ($row['reach_delivery_fee_type'] == 0) {
									if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
										$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
									}
								} elseif ($row['reach_delivery_fee_type'] == 1) {
								} elseif ($row['reach_delivery_fee_type'] == 2) {
									$row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
								}
							}
						}
					}
					$temp['coupon_count'] = count($temp['coupon_list']);
					$merchant_store_shop_result[] = $temp;
				}	
				$arr['shop_list'] = $merchant_store_shop_result;
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);	
			
	}
	
	//社区会员中心 - wangdong
	public function village_my(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$pigcms_id	=	I('pigcms_id');
				if(empty($pigcms_id)){
					$this->returnCode('20120004');
				}
				$pigcms_type	=	I('pigcms_type',1);
				$now_user_info = $this->get_user_village_info($pigcms_id,$village_id,$info['uid']);
				$now_user = D('User')->get_user($info['uid']);
				$arr['user']	=	array(
						'name'		=>	$now_user_info['name'],
						'usernum'	=>	$now_user_info['usernum'],
						'address'	=>	$now_user_info['address'],
						'avatar'	=>	$now_user['avatar'],
						'money'     =>  $now_user['now_money'],//金额
						'score'     =>  $now_user['score_count'],//积分
						'level'     =>  $now_user['level'],//等级
				);
				if(empty($now_user['avatar'])){
					$arr['user']['avatar']	=	$this->config['site_url'].'/tpl/Wap/pure/static/images/pic-default.png';
				}
				if(empty($arr['user'])){
					$arr['user']	=	array();
				}
				$array	=	array('list'=>array($arr['village'],$arr['service']),'user'=>$arr['user']);
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);	
			
	}
	
	//社区会员中心 我的钱包 - wangdong
	public function village_my_money(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			
			$info['uid'] = "1358644";
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$now_user = D('User')->get_user($info['uid']);
				$now_user['now_money'] = floatval($now_user['now_money']);
				$now_user['now_money_two'] = number_format(floatval($now_user['now_money']),2);
				$arr['now_user'] = $now_user;
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);	
	}
	
	//社区 -> 用车列表 - wangdong
	public function ride_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			//$info['uid'] = "1358644";
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$ride_price	=	I('ride_price');
				$page		=	I('page',1);
				$page_coun	=	I('page_coun',10);
				if($ride_price){
					switch($ride_price){
						case 20:
							$where['ride_price']	=	array(array('gt',0),array('elt',20));
							break;
						case 40:
							$where['ride_price']	=	array(array('egt',20),array('elt',40));
							break;
						case 60:
							$where['ride_price']	=	array(array('egt',40),array('elt',60));
							break;
						case 1000:
							$where['ride_price']	=	array('egt',60);
							break;
					}
				}
				$remain_number	=	I('remain_number');
				if($remain_number){
					switch($remain_number){
						case 1:
							$where['remain_number']	=	array('egt',1);
							break;
						case 2:
							$where['remain_number']	=	array('egt',2);
							break;
						case 3:
							$where['remain_number']	=	array('egt',3);
							break;
						case 4:
							$where['remain_number']	=	array('egt',4);
							break;
					}
				}
				$destination	=	I('destination');
				if($destination	!= 'undefined' && $destination){
					$where['destination']	=	array('like','%'.$destination.'%');
				}
				$departure_place	=	I('departure_place');
				if($departure_place	==	2){
					$where['village_id']	=	$this->village_id;
				}
				//$where['start_time']	=	array('egt',time());
		
				$where['_string'] = '((start_time >='.time().') AND (ride_date_number=1)) or (ride_date_number=2)';
				$where['status']	=	array('eq',1);
				$where['city_id']	=	$this->config['now_city'];
				$RideModel	=	D('Ride');
				$aRideList	=	$RideModel->get_ride_list($where,$page,$page_coun);
				if($aRideList == 1){
					$this->returnCode('20100001');
				}else if($aRideList == 2){
					$this->returnCode('20100002');
				}else{
					foreach($aRideList['ride_list'] as $k=>$v){
						$aRideList['ride_list'][$k]['avatar_pic'] = $this->config['site_url'].$v['avatar'];	
					}
					$aRideList['defaultImg']	=	$this->defaultImg;
				}
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$aRideList);	
			
	}
	
	//社区 -> 拼车列表 -> 拼车详情 - wangdong
	public function ride_details(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			//$info['uid'] = "1358644";
			$now_village = D('House_village')->get_one($village_id);
			if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
			}else{
				$ride_id	=	I('ride_id');
				if(empty($ride_id)){
					$this->returnCode('20100003');
				}
				$where['ride_id']	=	$ride_id;
				$RideModel		=	D('Ride');
				$aRideDetails	=	$RideModel->get_ride_one($where,$info['uid']);
				if($aRideDetails == 2){
					$this->returnCode('20100004');
				}else{
					$aRideDetails['defaultImg']	=	$this->defaultImg;
				}
			}
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$aRideDetails);	
	}
	
	//社区会员中心 -> 我的小区 - wangdong
	public function my_village_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		//查询我的小区 应该从House_village_user_vacancy 这个表
		$database_house_village_user_vacancy = D('House_village_user_vacancy');
		$database_house_village_floor = D('House_village_floor');
		$database_village_list = D('House_village');
		$database_house_village_user_bind = D('House_village_user_bind');
		$database_house_village_user_unbind = D('House_village_user_unbind');
		
		$my_village_lists = $database_house_village_user_vacancy->get_my_village_lists($info['uid']);
		foreach($my_village_lists as $k=>&$v){
				
				$v['unbind_status'] = $database_house_village_user_unbind->where(array('type'=>array('in','0,3'),'village_id'=>$v['village_id'],'name'=>$v['name'] ,'phone'=>$v['phone'],'floor_id'=>$v['floor_id'],'room_id'=>$v['pigcms_id'],'uid'=>$v['uid'],'status'=>1))->count();
				
				//根据信息得到我所在房间的单元信息和小区信息
				$floor = $database_house_village_floor->get_unit_find($v['floor_id'] , 'village_id,floor_name,floor_layer');
				$village_info = $database_village_list->get_village_info($v['village_id'] , 'village_name,village_address');
				$v['village_name']        =  $village_info['village_name'];
				$v['village_address']     =  $village_info['village_address'];
				$v['floor_name']          =  $floor['floor_name'];
				$v['floor_layer']         =  $floor['floor_layer'];
				
				if($v['status']==3){
					$info_id = $database_house_village_user_bind->where(array('vacancy_id'=>$v['pigcms_id'] , 'village_id'=>$v['village_id'] , 'type'=>array("in","0,3") , 'status'=>1))->getField('pigcms_id');
					$v['bind_pigcms_id'] = $info_id;
				}
				//获取亲属/租客
				$v['child_list'] = $database_house_village_user_bind->get_my_room_user($v['pigcms_id']);
		}

		$my_village['my_village_lists'] = $my_village_lists;
		
		//得到我是业主的所有房间号
		$my_village_room_array_id = $database_house_village_user_vacancy->get_my_village_lists($info['uid'] , 'pigcms_id');
		
		$village_room_str = "";
		if(!empty($my_village_room_array_id)){
			foreach($my_village_room_array_id as $key=>$value) $village_room_str .= ",".$value['pigcms_id'];
		}
		//得到我是业主的房间集合完毕
		$my_room_not_master = $database_house_village_user_bind->get_my_room_not_master($info['uid'] , '1,2' , substr($village_room_str,1));
		
		if(!empty($my_room_not_master)){
			foreach($my_room_not_master as $k=>&$v){
				
				$v['unbind_status'] = $database_house_village_user_unbind->where(array('type'=>$v['type'],'village_id'=>$v['village_id'],'name'=>$v['name'] ,'phone'=>$v['phone'],'floor_id'=>$v['floor_id'],'room_id'=>$v['vacancy_id'],'uid'=>$v['uid'],'status'=>1))->getField('itemid');
				
				//根据信息查询小区信息 单元信息 房间信息
				$find_village_room = $database_house_village_user_vacancy->get_find_room_info($v['vacancy_id'] , "layer,room");
				$find_village_floor = $database_house_village_floor->get_unit_find($v['floor_id'] , 'floor_name,floor_layer');
				$find_village = $database_village_list->get_village_info($v['village_id'] , 'village_name,village_address');
				
				$v['vacancy_layer']       =  $find_village_room['layer'];
				$v['vacancy_room']        =  $find_village_room['room'];
				$v['village_name']        =  $find_village['village_name'];
				$v['village_address']     =  $find_village['village_address'];
				$v['floor_name']          =  $find_village_floor['floor_name'];
				$v['floor_layer']         =  $find_village_floor['floor_layer'];
				
			}	
		}
		$my_village['my_village_vacancy'] = $my_room_not_master;
		$arr['my_village'] = $my_village;
		$this->returnCode(0,$arr);	
			
	}
	
	
	
	//社区会员中心 -> 我的小区 -> 加入房屋 - wangdong
	public function empty_village_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$database_house_village = D('House_village');
		$village_where['status'] = 1;
		$village_list = $database_house_village->where($village_where)->limie(10)->select();
		
		$arr['village_list'] = $village_list;
	
		$this->returnCode(0,$arr);	
			
	}
	
	
	//社区会员中心 -> 我的小区 -> 加入房屋 -> 单元列表  - wangdong
	public function empty_village_unit_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		if($village_id){
			$database_House_village = D('House_village');
			$database_house_village_floor = D('House_village_floor');
			//查询小区信息
			$village_info = $database_House_village->get_village_info($village_id);
			$arr['village_info'] = $village_info;
			//查询小区单元信息
			$unit_list = $database_house_village_floor->get_unit_list($village_id);
			$arr['unit_list'] = $unit_list;
			
		}else{
			$this->returnCode('30000001');
		}
	
		$this->returnCode(0,$arr);	
			
	}
	
	
	//社区会员中心 -> 我的小区 -> 加入房屋 -> 单元列表 -> 房间列表  - wangdong
	public function empty_village_room_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		$floor_id	=	I('floor_id');
		if($village_id){
			if(!$floor_id){
				$this->returnCode('20020011'); //参数错误
			}
	
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			$database_house_village = D('House_village');
			$database_house_village_floor = D('House_village_floor');
			
			//查询单元信息 小区信息
			$find_village_floor = $database_house_village_floor->get_unit_find($floor_id , 'village_id,floor_name,floor_layer');
			$find_village = $database_house_village->get_village_info($find_village_floor['village_id'] , 'village_name,village_address');
			$find_info = array(
				'village_name'    =>  $find_village['village_name'],
				'village_address' =>  $find_village['village_address'],
				'floor_name'      =>  $find_village_floor['floor_name'],
				'floor_layer'     =>  $find_village_floor['floor_layer'],
				'village_id'     =>  $find_village_floor['village_id']
			);
			$arr['find_info'] = $find_info;
	
			$vacancy_where['status'] = array('in' , '1,3');
			$vacancy_where['is_del'] = 0;
			$vacancy_where['floor_id'] = $floor_id;
	
			$vacancy_list = $database_house_village_user_vacancy->house_village_user_vacancy_page_list_room($vacancy_where , true , 'pigcms_id desc' , 9999999);
			$vacancy_list = $vacancy_list['result']['list'];
			$arr['vacancy_list'] = $vacancy_list;
			
		}else{
			$this->returnCode('30000001');
		}
	
		$this->returnCode(0,$arr);	
			
	}
	
	//社区会员中心 -> 我的小区 -> 加入房屋 -> 单元列表 -> 房间列表 -> 绑定房间  - wangdong
	public function empty_village_room_info(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		if($village_id){
			$database_house_village_user_bind = D('House_village_user_bind');
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			
			$database_house_village = D('House_village');
			$database_house_village_floor = D('House_village_floor');
	
			$pigcms_id = $_GET['pigcms_id'] + 0;
			if(!$pigcms_id){
				$this->returnCode('20020011'); //参数错误
			}
			$bind_where['pigcms_id'] = $pigcms_id;

			//查询 房间信息 单元信息 小区信息
			$find_village_room = $database_house_village_user_vacancy->get_find_room_info($pigcms_id , "floor_id,village_id,layer,room");
			$find_village_floor = $database_house_village_floor->get_unit_find($find_village_room['floor_id'] , 'village_id,floor_name,floor_layer');
			$find_village = $database_house_village->get_village_info($find_village_room['village_id'] , 'village_name,village_address');
			$find_info = array(
				'vacancy_layer'         =>  $find_village_room['layer'],
				'vacancy_floor_id'      =>  $find_village_room['floor_id'],
				'vacancy_room'          =>  $find_village_room['room'],
				'village_name'          =>  $find_village['village_name'],
				'village_address'       =>  $find_village['village_address'],
				'floor_name'            =>  $find_village_floor['floor_name'],
				'floor_layer'           =>  $find_village_floor['floor_layer'],
				'village_id'            =>  $find_village_floor['village_id']
			);
			
			$arr['find_info'] = $find_info;
			
			//查询有无业主
			$room_true_find = $database_house_village_user_vacancy->get_find_room_count($pigcms_id);
			$arr['room_true_find'] = $room_true_find;
			
			$userArr = D('User')->get_user($info['uid']);
			unset($userArr['pwd']);
			$arr['userArr'] = $userArr;

			$bind_where['is_del'] = 0;
			$bind_info = $database_house_village_user_vacancy->where($bind_where)->find();
			
			if(!$bind_info){
				$this->returnCode('20120038'); //房间不存在
			}
	
			if(IS_POST){
				//待开发........
			}else{
				$arr['bind_info'] = $bind_info;
			}
			
		}else{
			$this->returnCode('30000001');
		}
	
		$this->returnCode(0,$arr);	
			
	}
	
	
	//社区会员中心 -> 我的小区 -> 绑定亲属  - wangdong
	public function empty_bind_relatives(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$database_house_village_user_vacancy = D("House_village_user_vacancy");
		$database_house_village_floor = D('House_village_floor');
		$database_village_list = D('House_village');
		$database_house_village_user_bind = D('House_village_user_bind');
		
		//查询用户所属业主的所有房屋列表
		$user_all_village_list = $database_house_village_user_vacancy -> get_my_village_lists($info['uid']);
		
		foreach($user_all_village_list as $k=>&$v){
			
			//获取小区信息和房间信息	
			$find_village_floor = $database_house_village_floor->get_unit_find($v['floor_id'] , 'floor_name,floor_layer');
			$find_village = $database_village_list->get_village_info($v['village_id'] , 'village_name,village_address');
			$v['village_name']        =  $find_village['village_name'];
			$v['village_address']     =  $find_village['village_address'];
			$v['floor_name']          =  $find_village_floor['floor_name'];
			$v['floor_layer']         =  $find_village_floor['floor_layer'];
				
		}
		$arr['user_all_village_list'] = $user_all_village_list;

		$this->returnCode(0,$arr);	
			
	}
	
	//社区会员中心 -> 生活缴费  - wangdong
	public function village_my_paylists(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		$order_type =   I('order_type');
		if($village_id){
			$pigcms_id	=	I('pigcms_id');
			if(empty($pigcms_id)){
				$this->returnCode('20020011'); //参数错误
			}
	
			$now_user_info = $this->get_user_village_info($pigcms_id,$village_id,$info['uid']);
			if(empty($now_user_info)){
				$this->returnCode(20120039); //绑定信息不存在	
			}
	
			$order_where['bind_id'] = $pigcms_id;
			$order_where['village_id'] = $village_id;
			$order_where['paid'] = 1;
			if($_GET['order_type']){
				$order_where['order_type'] = $_GET['order_type'];
			}
	
			$order_list = D('House_village_pay_order')->field(true)->where($order_where)->order('`order_id` DESC')->select();
			$pay_list   = D('House_village_property_paylist')->where(array('bind_id'=>$now_user_info['pigcms_id']))->order('id desc')->select();;
	
			foreach($order_list as $Key=>$order){
				foreach($pay_list as $pay_info){
					if($order['order_id'] == $pay_info['order_id']){
						$order_list[$Key]['start_time'] = $pay_info['start_time'];
						$order_list[$Key]['end_time'] = $pay_info['end_time'];
					}
				}
			}
			$arr['order_list'] = $order_list;
			$arr['pay_type'] = $this->pay_type;
		}else{
			$this->returnCode('30000001');
		}
		
		
		$this->returnCode(0,$arr);
			
	}
	
	//社区会员中心 -> 我的订单  - wangdong
	public function village_my_order(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		if(!$village_id){
			
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	//社区会员中心 -> 我的订单 -> 团购订单列表  - wangdong
	public function village_my_group_order(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		if($village_id){
			$order_list = D('Group')->wap_get_order_list($info['uid'],intval($_GET['status']));
			$arr['order_list'] = $order_list;
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	//社区会员中心 -> 我的订单 -> 快店订单列表  - wangdong
	public function village_my_shop_order(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		if($village_id){
			$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
			$where = "is_del=0 AND uid={$info['uid']}";
			
			if ($status == -1) {
				$where .= " AND paid=0";
			} elseif ($status == 1) {
				$where .= " AND paid=1 AND status<2";
			} elseif ($status == 2) {
				$where .= " AND paid=1 AND status=2";
			}
	
			$where .= " AND is_del = 0";
			$order_list = D("Shop_order")->get_order_list($where, 'order_id DESC', 11);
			$order_list = $order_list['order_list'];
	
			foreach ($order_list as $st) {
				$store_ids[] = $st['store_id'];
			}
			$m = array();
			if ($store_ids) {
				$store_image_class = new store_image();
				$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
				foreach ($merchant_list as $li) {
					$images = $store_image_class->get_allImage_by_path($li['pic_info']);
					$li['image'] = $images ? array_shift($images) : array();
					unset($li['status']);
					$m[$li['store_id']] = $li;
				}
			}
			$list = array();
			foreach ($order_list as $ol) {
				if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
					$list[] = array_merge($ol, $m[$ol['store_id']]);
				} else {
					$list[] = $ol;
				}
			}
			/*foreach($list as $key=>$val){
				$list[$key]['order_url'] = U('Shop/status', array('order_id' => $val['order_id']));
			}*/
			$arr['order_list'] = $list;
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	//社区会员中心 -> 我的订单 -> 预约订单列表  - wangdong
	public function village_my_appoint_order(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		if($village_id){
			$status = $_GET['status'] ? $_GET['status'] + 0 : 0;
	
			$database_appoint = D('Appoint');
	
			if($status == 1){
				$where['service_status'] = 0;
			}elseif($status == 2){
				$where['service_status'] = 1;
			}
			$order_list = $database_appoint->wap_get_order_list($info['uid'], $status);
			$arr['order_list'] = $order_list;
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	
	//社区会员中心 -> 访客登记  - wangdong
	public function visitor_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		if($village_id){
		  $now_village = D('House_village')->get_one($village_id);	
          if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
		  }else{
			  	$database_house_village_visitor = D('House_village_visitor');
				$has_visitor = $this->getHasConfig($village_id, 'has_visitor');
				if($has_visitor){
					$where['village_id'] = $village_id;
					$where['owner_uid'] = $info['uid'];
					$list = $database_house_village_visitor->house_village_visitor_list($where);
					if(!$list){
						$this->returnCode('20120040');//数据处理有误
					}else{
						$arr['list'] = $list['list'];
						$arr['visitor_type'] = $database_house_village_visitor->visitor_type;
					}
				}else{
					$this->returnCode('20120041');//小区未开通相关服务
				}	
		  }
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	 private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
     }
	 
	 
	//社区新闻公告列表  - wangdong
	public function village_article_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		if($village_id){
		  $now_village = D('House_village')->get_one($village_id);	
          if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
		  }else{
				$category_list = D('House_village_news_category')->get_limit_list($now_village['village_id']);
				if($category_list){
					$arr['category_list'] = $category_list;
					$news_list = D('House_village_news')->get_list_by_cid($category_list[0]['cat_id']);
					$arr['news_list'] = $news_list;
				}else{
					$this->returnCode('20120042'); //暂无小区动态
				}
		  }
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	//社区新闻公告详情  - wangdong
	public function village_article_view(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		$news_id	=	I('news_id');
		if($village_id){
		  $now_village = D('House_village')->get_one($village_id);	
          if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
		  }else{
				$now_news = D('House_village_news')->get_one($news_id);
				if(empty($now_news)){
					$this->returnCode('20120043'); // 文章不存在	
				}
				$arr['now_news'] = $now_news;
					
		  }
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	//社区活动列表  - wangdong
	public function village_activity_list(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		$news_id	=	I('news_id');
		if($village_id){
		  $now_village = D('House_village')->get_one($village_id);	
          if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
		  }else{
				if($now_village['has_activity']){
					$database_house_village_activity = D('House_village_activity');
					$where['village_id'] = $village_id;
					$where['status'] = 1;
					$activity_list = $database_house_village_activity->house_village_activity_page_list($where,true,'id desc',9999);
					$activity_list = $activity_list['list']['list'];
		
					foreach($activity_list as $Key=>$row){
						$row['pic'] = explode(';',$row['pic']);
						foreach($row['pic'] as $k=>$v){
							$row['pic'][$k] = $this->config['site_url'].'/upload/activity/'.$v;	
						}
						$row['activity_start_date'] = date('Y-m-d',$row['activity_start_time']);
						if($row['activity_end_time'] > time()){
							
							$activity_list['running_activity'][] = $row;
						}else{
							$activity_list['stop_activity'][] = $row;
						}
						unset($activity_list[$Key]);
					}
					$arr['activity_list'] = $activity_list;
					
				}else{
					$this->returnCode('20120044'); //该社区没有开启活动
				}
					
		  }
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	//社区活动详情  - wangdong
	public function village_activity_view(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		$activity_id	=	I('activity_id');
		if($village_id){
		  $now_village = D('House_village')->get_one($village_id);	
          if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
		  }else{
				if($now_village['has_activity']){
					$database_house_village_activity = D('House_village_activity');
					$where['id'] = $activity_id + 0;
					$where['status'] = 1;
					$now_activity = $database_house_village_activity->house_village_activity_detail($where);
					if($now_activity['status'] == 0){
						$this->returnCode('20120045'); //当前活动不存在
					}
					$now_activity['detail']['apply_end_date'] = date("Y-m-d",$now_activity['detail']['apply_end_time']);
					foreach($now_activity['detail']['pic'] as $k=>$v){
						$now_activity['detail']['pic'][$k] = $this->config['site_url'].'/upload/activity/'.$v;		
					}
					$arr['now_activity'] = $now_activity['detail'];
				}else{
					$this->returnCode('20120044'); //该社区没有开启活动
				}
					
		  }
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	//社区活动 -> 我要报名  - wangdong
	public function village_activity_apply(){
		
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
		$info = ticket::get($ticket, 'wxapp', true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
		
		//$info['uid'] = "1358644";
		
		$village_id	=	I('village_id');
		$activity_id	=	I('activity_id');
		if($village_id){
		  $now_village = D('House_village')->get_one($village_id);	
          if(empty($now_village)){
				$this->returnCode('20120001'); // 当前小区不存在或未开放
		  }else{
				if(!$activity_id){
					//$this->error_tips('传递参数有误！');
					$this->returnCode('20020011');//参数错误
				}
		
				if(IS_POST){
					$database_house_village_activity_apply = D('House_village_activity_apply');
					$result = $database_house_village_activity_apply->house_village_activityapply_add($_POST);
					exit(json_encode($result));
				}else{
					$database_house_village_activity = D('House_village_activity');
					$where['id'] = $activity_id;
					$now_activity = $database_house_village_activity->house_village_activity_detail($where);
					$now_activity = $now_activity['detail'];
					if(!$now_activity){
						$this->returnCode('20120045');//当前活动不存在
					}
					if(time() > $now_activity['apply_end_time']+86400){
						$this->returnCode('20120046');//活动已截止
					}
		
					if($now_activity['is_full']){
						$this->returnCode('20120047');//活动人数已满
					}
				}
		  }
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
			
	}
	
	//	查询是否绑定了当前小区
	protected function get_user_village_info($bind_id,$village_id,$uid){
		$now_user_info = D('House_village_user_bind')->get_one_by_bindId($bind_id);
		if(empty($now_user_info)){
			$this->returnCode('20120003');
		}
		$database_house_village_user_bind = D('House_village_user_bind');
		$where['parent_id|pigcms_id'] = $bind_id;
		$where['uid'] = $uid;
		$where['village_id'] = $village_id;
		$house_village_user_bind_count = $database_house_village_user_bind->where($where)->count();
		if(!$house_village_user_bind_count){
			$this->returnCode('20120002');
		}
		return $now_user_info;
	}

	
	
	//	获取社区基本信息管理
    public function index(){
    	$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, 'wxapp', true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			/*if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}*/
			$village_info = M('House_village')->field(array('qrcode_id','wx_image','wx_desc'),true)->where(array('village_id'=>$village_id))->find();
			$village_info	=	isset($village_info)?$village_info:array();
//			$village_info['long'] = floatval($village_info['long']);
//			$village_info['lat'] = floatval($village_info['lat']);
			unset($village_info['pwd']);
			if($village_info['province_id']){
				$province_id	=	$this->getCityId($village_info['province_id']);
				$village_info['default']['province_name']	=	$province_id['area_name'];
				$village_info['default']['province_id']	=	$village_info['province_id'];
			}
			if($village_info['city_id']){
				$city_id	=	$this->getCityId($village_info['city_id']);
				$village_info['default']['city_name']	=	$city_id['area_name'];
				$village_info['default']['city_id']	=	$village_info['city_id'];
			}
			if($village_info['area_id']){
				$area_id	=	$this->getCityId($village_info['area_id']);
				$village_info['default']['area_name']	=	$area_id['area_name'];
				$village_info['default']['area_id']	=	$village_info['area_id'];
			}
			if($village_info['circle_id']){
				$circle_id	=	$this->getCityId($village_info['circle_id']);
				$village_info['default']['circle_name']	=	$circle_id['area_name'];
				$village_info['default']['circle_id']	=	$village_info['circle_id'];
			}
			if(empty($village_info['default'])){
				$village_info['default']	=	(object)array();
			}
			$village_info['longs']	=	$village_info['long'];
			$village_info['lats']	=	$village_info['lat'];
			$village_info['now_city']	=	$this->config['now_city'];
			$village_info['many_city']	=	$this->config['many_city'];
			unset($village_info['long'],$village_info['lat']);
			$arr	=	array(
				'village'	=>	isset($village_info)?$village_info:(object)array(),
			);
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
    }
    //	获取省、市、区、商圈
    public	function	getProvince($stats=0){
    	$province_id	=	I('area_pid',0);
    	$aProvince_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$province_id,'is_open'=>1))->select();
    	if($stats){
			return $aProvince_id;
    	}else{
			$this->returnCode(0,$aProvince_id);
    	}
    }
    //	获取市
   // public	function	getCity(){
//		$province_id	=	I('area_pid');
//		if(empty($province_id)){
//			$this->returnCode('20090001');
//		}
//		$aCity_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$province_id,'is_open'=>1))->select();
//		$this->returnCode(0,$aCity_id);
//    }
//    //	获取区
//    public	function	getArea(){
//		$city_id	=	I('area_pid');
//		if(empty($city_id)){
//			$this->returnCode('20090002');
//		}
//		$aArea_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$city_id,'is_open'=>1))->select();
//		$this->returnCode(0,$aArea_id);
//    }
//    //	获取商圈
//    public	function	getCircle(){
//		$area_id	=	I('area_pid');
//		if(empty($area_id)){
//			$this->returnCode('20090003');
//		}
//		$aCircle_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$area_id,'is_open'=>1))->select();
//		$this->returnCode(0,$aCircle_id);
//    }
    //	用ID获取城市
    public	function	getCityId($area_id=0){
		if(empty($area_id)){
			return array();
		}
		$aArea_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_id'=>$area_id,'is_open'=>1))->find();
		if(empty($aArea_id)){
			$aArea_id	=	(object)array();
		}
		return $aArea_id;
    }
    //	修改社区基本信息管理
    public	function	villageEdit(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$info = ticket::get($ticket, 'wxapp', true);
    	if(empty($info)){
			$this->returnCode('20000009');
		}
		/*if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
		}*/
    	$village_id	=	I('village_id');
    	if(empty($village_id)){
			$this->returnCode('30000001');
    	}else{
    		$arr	=	array(
    			'property_phone'	=>	I('property_phone'),		//物业联系电话
    			'property_address'	=>	I('property_address'),		//物业联系地址
    			'long'				=>	I('longs'),					//经度
    			'lat'				=>	I('lats'),					//纬度
    			'province_id'		=>	I('province_id'),			//省
    			'city_id'			=>	I('city_id'),				//市
    			'area_id'			=>	I('area_id'),				//区
    			'circle_id'			=>	I('circle_id'),				//商圈
    			'village_address'	=>	I('village_address'),		//社区地址
    			'property_price'	=>	I('property_price'),		//一平方米的物业费单价
    			'water_price'		=>	I('water_price'),			//水费单价
    			'electric_price'	=>	I('electric_price'),		//电费单价
    			'gas_price'			=>	I('gas_price'),				//燃气费单价
    			'park_price'		=>	I('park_price'),			//停车位每月价格
    			'has_custom_pay'	=>	I('has_custom_pay'),		//是否支持自定义缴费
    			'has_express_service'=>	I('has_express_service'),	//是否开启快递代收
    			'has_visitor'		=>	I('has_visitor'),			//是否开启访客登记
    			'has_slide'			=>	I('has_slide'),				//是否开启社区幻灯片
    			'has_service_slide'	=>	I('has_service_slide'),		//是否开启便民页面幻灯片 0，关闭 1，开启
    		);
    		foreach($arr as $k=>$v){
				if($v==null){
					unset($arr[$k]);
				}
    		}
    		$aVillage_id	=	M('House_village')->field(array('village_id','status'))->where(array('village_id'=>$village_id))->find();
    		if($aVillage_id){
    			if($aVillage_id['status'] == 0){
					if($arr['long'] && $arr['lat']){
						$arr['status']	=	1;
					}
    			}
				$aSave	=	M('House_village')->where(array('village_id'=>$village_id))->data($arr)->save();
    		}else{
				$this->returnCode('20090005');
    		}
    	}
    	if($aSave){
			$this->returnCode(0);
    	}else if($aSave === 0){
			$this->returnCode('20090007');
    	}else{
			$this->returnCode('20090006');
    	}
    }
    public function see_qrcode(){
    	$this->is_existence();
    	$type		=	'house';
    	$village_id	=	I('village_id');
		//判断ID是否正确，如果正确且以前生成过二维码则得到ID
		$pigcms_return = D('House_village')->get_qrcode($village_id);
		if(empty($pigcms_return)){
			$this->returnCode('20090053');
		}
		if(empty($pigcms_return['qrcode_id'])){
			$qrcode_return = D('Recognition')->get_new_qrcode($type,$village_id);
		}else{
			$qrcode_return = D('Recognition')->get_qrcode($pigcms_return['qrcode_id']);
			if($qrcode_return['error_code']){
				$this->returnCode('20090055');
			}
		}
		if($qrcode_return['error_code']){
			$this->returnCode('20090056');
		}else if($qrcode_return['qrcode'] == 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='){
			$qrcode_return = D('Recognition')->get_new_qrcode($type,$village_id);
		}

		//echo $_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house';
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house/'.$village_id.'.png')){
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house')){
				echo $_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house';
				mkdir($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house/',0777,true);
			}
			import('ORG.Net.Http');
			$http = new Http();
			file_put_contents('./runtime/qrcode/house/'.$village_id.'.png',Http::curlGet($qrcode_return['qrcode']));
		}
		$arr	=	array(
			'img'	=>	$this->config['site_url'].'/runtime/qrcode/house/'.$village_id.'.png',
		);
		$this->returnCode(0,$arr);
    }
}