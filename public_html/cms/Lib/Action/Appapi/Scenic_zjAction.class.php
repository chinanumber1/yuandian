<?php
/*
 *闸机接口
 */
class Scenic_zjAction extends BaseAction{
	private $rc4_key = 'gm228689ty';
	public function heart(){
		echo json_encode(array('success'=>1));die;
	}

	public function ticket_num_id(){

		$ticket_id = $_POST['ticket_id'];
		$time = $_POST['time'];
		$now_ticket = D('Scenic_ticket')->get_scenic_one_ticket(array('ticket_id'=>$ticket_id));
		$now_scenic = D('Scenic_list')->get_one_list(array('scenic_id'=>$now_ticket['scenic_id']));
		if(empty($now_ticket)){
			echo json_encode(array('Status'=>0,'StatusDesc'=>"门票不存在"));exit;
		}

		if(empty($time)){
			echo json_encode(array('Status'=>0,'StatusDesc'=>"时间错误"));exit;
		}
		$where['ticket_id'] = $now_ticket['ticket_id'];
		$time_zero = strtotime($time);
		$where['_string']  = 'last_time >'.$time_zero .' AND last_time<'.($time_zero+86399);
		//$arr['into_num'] = M('Scenic_into_log')->where($where)->sum('num');

		$subQuery = M('Scenic_into_log')->field('num')->where($where)->group("order_id")->buildSql();
		$arr['into_num'] =  M()->table($subQuery.' a')->count();

		$arr['into_num'] = $arr['into_num']?$arr['into_num']:0;
		$arr['name'] = $now_ticket['ticket_title'];
		$sale_where['ticket_id'] = $ticket_id;
		$sale_where['paid'] = 2;
		$sale_where['_string'] = 'pay_time >'.$time_zero .' AND pay_time<'.($time_zero+86399);
		$arr['sale_num'] = M('Scenic_order')->where($sale_where)->sum('ticket_num');
		$arr['sale_num'] = $arr['sale_num']?$arr['sale_num']:0;
		$arr['id'] = $now_ticket['ticket_id'];
		$arr['desc'] = $now_ticket['ticket_title'] .' '.date("Y年m月d日",$time_zero)." 售票:{$arr['sale_num']}张 入园：".$arr['into_num'].'人';
		echo json_encode(array('Status'=>0,'StatusDesc'=>"获取成功",'result' => $arr));exit;
	}

	public function ticket_num_all(){
		$time = $_POST['time'];
		if(empty($time)){
			echo json_encode(array('Status'=>0,'StatusDesc'=>"时间错误"));exit;
		}
		$scenic_id = $_POST['scenic_id'];
//		$ticket_list = D('Scenic_ticket')->get_scenic_ticket_all(array('scenic_id'=>$scenic_id));
		$ticket_list = M('Scenic_into_log')->join('as s LEFT JOIN '.C('DB_PREFIX').'scenic_ticket t ON t.ticket_id = s.ticket_id')->field('s.ticket_id,t.*')->where(array('s.scenic_id'=>$scenic_id))->group("s.ticket_id")->select();

		$now_scenic = D('Scenic_list')->get_one_list(array('scenic_id'=>$scenic_id));
		if(empty($now_scenic)){
			echo json_encode(array('Status'=>0,'StatusDesc'=>"景区id错误"));exit;
		}
		$time_zero = strtotime($time);
		$where['_string']  = 'last_time >'.$time_zero .' AND last_time<'.($time_zero+86399);
		$into_num = 0;
		foreach ($ticket_list as $v) {
			$where['ticket_id'] = $v['ticket_id'];
			$where['scenic_id'] = $scenic_id;
			//$tmp['into_num'] =  M('Scenic_into_num')->where($where)->sum('num');
			$subQuery = M('Scenic_into_log')->field('num')->where($where)->group("order_id")->buildSql();
			$tmp['into_num'] =  M()->table($subQuery.' a')->count();

			$tmp['into_num'] = $tmp['into_num']?$tmp['into_num']:0;
			$into_num += $tmp['into_num'];
			if($v['scenic_ticket']!=$scenic_id){
				$tmp['name'] =  $v['ticket_title']."(联票)";
			}else{
				$tmp['name'] =  $v['ticket_title'];
			}
			$tmp['id'] =  $v['ticket_id'];

			$sale_where_tmp['ticket_id'] = $v['ticket_id'];
			$sale_where_tmp['paid'] = 2;
			$sale_where_tmp['_string'] = 'pay_time >'.$time_zero .' AND pay_time<'.($time_zero+86399);
			$tmp['sale_num'] = M('Scenic_order')->where($sale_where_tmp)->sum('ticket_num');
			$tmp['sale_num'] = $tmp['sale_num']?$tmp['sale_num']:0;

			$arr['all_ticket_date'][] = $tmp;
		}
		$sale_where['scenic_id'] = $scenic_id;
		$sale_where['paid'] = 2;
		$sale_where['_string'] = 'pay_time >'.$time_zero .' AND pay_time<'.($time_zero+86399);
		$arr['sale_num'] = M('Scenic_order')->where($sale_where)->sum('ticket_num');
		$arr['sale_num'] = $arr['sale_num']?$arr['sale_num']:0;
		$arr['scenic_id'] = $scenic_id;
		$arr['name'] = $now_scenic['scenic_title'];
		$arr['into_num'] = $into_num;
		$arr['desc'] = $now_scenic['scenic_title'] .' '.date("Y年m月d日",$time_zero)." 售票:{$arr['sale_num']}张 入园：".$into_num.'人';

		echo json_encode(array('Status'=>0,'StatusDesc'=>"获取成功",'result' => $arr));exit;
	}


	public function check_ticket(){
		if(IS_POST){
			$_POST = json_decode(html_entity_decode($_POST['paramaters']),true);
			$type = $_POST['CodeType'];
			$codezj = $_POST['CodeVal'];
			if($type=='Q'){
				$codezj = $this->getRc4Encode($this->rc4_key,pack("H*",$codezj));
			}
			$scenic_id = floatval($_POST['ViewId']);
			$today = date('Y-m-d',time());

			if($type=='Q'){
				$code = substr($codezj,-14);
//				$scenic_id = floatval(substr($codezj,7,13));
				//$scenic_id = floatval(substr($codezj,6,7));
				$code_info = M('Scenic_order_com')->where(array('code'=>$code))->find();

				if( empty($code_info)){
					echo json_encode(array('Status'=>0,'StatusDesc'=>'门票不存在！'));exit;
				}
				//$all_count = M('Scenic_order_com')->where(array('order_id'=>$code_info['order_id']))->count();

				if($code_info['status']==1){
					$where['order_id'] = $code_info['order_id'];
					$res = D('Scenic_order')->get_one_order($where);
					$now_ticket = $res['ticket'];
					$endtime = $res['endtime'];

					if($endtime<time()){
						echo json_encode(array('Status'=>0,'StatusDesc'=>"门票已过期"));exit;
					}

					if(strtotime($res['ticket_time'])!=strtotime($today) && $endtime<strtotime($res['ticket_time'])){
						echo json_encode(array('Status'=>0,'StatusDesc'=>"门票预订时间\r\n不是今天"));exit;
					}

					//多次票
					//判断使用次数

					//倒数第二次之前不验证消费，允许进入

					if($now_ticket['ticket_group']==2){
						if($code_info['use_num']==$now_ticket['ticket_num']){
							echo json_encode(array('Status'=>0,'StatusDesc'=>'可用次数为0'));die;
						}
						if($code_info['use_num']<$res['ticket_num']-1){
							M('Scenic_order_com')->where(array('code'=>$code))->setInc('use_num',1);
							$add = D('Scenic_ticket')->ticket_num_add($code_info['order_id'],$type,$codezj,$scenic_id);
							echo json_encode(array('Status'=>1,'StatusDesc'=>"验票成功\r\n还可以使用".($res['ticket_num']-$code_info['use_num']-1)."次"));die;
						}
					}else{
						if($code_info['use_num']==$now_ticket['use_limit']){
							echo json_encode(array('Status'=>0,'StatusDesc'=>'可用次数为0'));die;
						}
						if($now_ticket['use_limit']>1 && $code_info['use_num']<$now_ticket['use_limit']-1 ||$now_ticket['use_limit']==-1){
							M('Scenic_order_com')->where(array('code'=>$code))->setInc('use_num',1);
							$add = D('Scenic_ticket')->ticket_num_add($code_info['order_id'],$type,$codezj,$scenic_id);
							if($now_ticket['use_limit']==-1){
								echo json_encode(array('Status'=>1,'StatusDesc'=>'验票成功'));die;
							}else{
								echo json_encode(array('Status'=>1,'StatusDesc'=>"验票成功\r\n还可以使用".($now_ticket['use_limit']-$code_info['use_num']-1)."次"));die;
							}
						}
					}

					$verify_count = M('Scenic_order_com')->where(array('order_id'=>$code_info['order_id'],'status'=>2))->count();

					$user_list = explode(',',$res['family_id']);
					$scenic_user_id = $user_list[$verify_count];
					$scenic_user = M('Scenic_family')->where(array('family_id'=>$scenic_user_id))->find();

					if($res){
						D('Scenic_order')->where($where)->setField('order_status',2);
						//扫码成功增加景区余额
						if(D('Scenic_money_list')->add_row($res['scenic_id'],1,$code_info['price'],$scenic_user['family_name'].'，'.$scenic_user['certificates'].'，闸机入园，确认码:'.$code,$code_info['order_id'])){
							$where['type'] = 1;
							$save_data['status']  =2;
							$save_data['last_time'] = time();
							M('Scenic_order_com')->where(array('code'=>$code))->save($save_data);
							$ticket_list = M('Scenic_order_com')->where(array('order_id'=>$code_info['order_id']))->select();
							$guide_service=false;
							foreach ($ticket_list as $v) {
								if($v['status']==2){
									$guide_service=true;
									break;
								}
							}
							$guide = M('Scenic_order_com')->field('type_id')->where(array('type'=>3,'order_id'=>$code_info['order_id']))->find();
							$guide_service==false && M('Scenic_guide')->where(array('guide_id'=>$guide['type_id']))->setInc('guide_service_number',1);
							M('Scenic_ticket')->where(array('ticket_id'=>$res['ticket_id']))->setInc('sale_count');
							$add = D('Scenic_ticket')->ticket_num_add($code_info['order_id'],$type,$codezj,$scenic_id);
							M('Scenic_order_com')->where(array('code'=>$code))->setInc('use_num',1);
							echo json_encode(array('Status'=>1,'StatusDesc'=>'验票成功'));die;
						}else{
							echo json_encode(array('Status'=>0,'StatusDesc'=>'门票验证失败'));exit;
						}
					}else{
						echo json_encode(array('Status'=>0,'StatusDesc'=>'门票不存在！'));exit;
					}
				}else{
					$msg = '';
					if($code_info['status']==2){
						$msg = "已消费\r\n不能再次验票";
					}elseif($code_info['status']==2){
						$msg = '该票已退款';
					}
					echo json_encode(array('Status'=>0,'StatusDesc'=>$msg));die;
				}
			}else if($type=='I'){
				$personID = $codezj;
				$scenic_user = M('Scenic_family')->where(array('certificates'=>$personID))->find();
				$condition['family_id'] = array('EXP',"REGEXP '^".$scenic_user['family_id']."$'");
				$condition['scenic_id'] = $scenic_id;
				$condition['order_status'] = 1;
				//$condition['ticket_time'] = date('Y-m-d',time());
				$order_list = M('Scenic_order')->where($condition)->select();
				$verify_ticket = $order_list[0];
				$ticket = D('Scenic_ticket')->get_scenic_one_ticket(array('ticket_id'=>$verify_ticket['ticket_id']));

				if(empty($verify_ticket)){
					echo json_encode(array('Status'=>0,'StatusDesc'=>'没有可用门票！'));exit;
				}else{

					$code_condition['order_id'] = $verify_ticket['order_id'];
					$code_condition['status'] = 1;
					$code_condition['family_id'] = $scenic_user['family_id'];
					$code_info = M('Scenic_order_com')->where($code_condition)->find();

					if($code_info['use_num']==$ticket['use_limit']){
						echo json_encode(array('Status'=>0,'StatusDesc'=>'可用次数为0'));die;
					}
					//倒数第二次之前不验证消费，允许进入
					if($verify_ticket['endtime']>time() && ($ticket['use_limit']>1 && $code_info['use_num']<$ticket['use_limit']-1 ||$ticket['use_limit']==-1)){
						$where_com['ticket'] = $verify_ticket['ticket_id'];
						$where_com['family_id'] = $scenic_user['family_id'];
						$where_com['order_id'] = $verify_ticket['order_id'];
						M('Scenic_order_com')->where($where_com)->setInc('use_num',1);
						$add = D('Scenic_ticket')->ticket_num_add($code_info['order_id'],$type,$personID,$scenic_id);
						echo json_encode(array('Status'=>1,'StatusDesc'=>"验票成功\r\n还可以使用".($ticket['use_limit']-$code_info['use_num']-1)."次"));die;
					}

					$where['order_id'] = $verify_ticket['order_id'];
					D('Scenic_order')->where($where)->setField('order_status',2);
					//扫码成功增加景区余额
					if(D('Scenic_money_list')->add_row($verify_ticket['scenic_id'],1,$code_info['price'],$scenic_user['family_name'].','.$personID.',证件入园，确认码：'.$code_info['code'],$code_info['order_id'])){
						$where['type'] = 1;
						$save_data['status']  =2;
						$save_data['last_time'] = time();
						M('Scenic_order_com')->where(array('com_id'=>$code_info['com_id']))->save($save_data);
//						M('Scenic_order_com')->where(array('com_id'=>$code_info['com_id']))->setField('status',2);
						$ticket_list = M('Scenic_order_com')->where(array('order_id'=>$code_info['order_id']))->select();
						$guide_service=false;
						foreach ($ticket_list as $v) {
							if($v['status']==2){
								$guide_service=true;
								break;
							}
						}
						$guide = M('Scenic_order_com')->field('type_id')->where(array('type'=>3,'order_id'=>$code_info['order_id']))->find();
						$guide_service==false && M('Scenic_guide')->where(array('guide_id'=>$guide['type_id']))->setInc('guide_service_number',1);
						M('Scenic_ticket')->where(array('ticket_id'=>$verify_ticket['ticket_id']))->setInc('sale_count');

						M('Scenic_order_com')->where(array('com_id'=>$code_info['com_id']))->setInc('use_num',1);

						$add = D('Scenic_ticket')->ticket_num_add($code_info['order_id'],$type,$personID,$scenic_id);
						echo json_encode(array('Status'=>1,'StatusDesc'=>'验票成功'));die;
					}else{
						echo json_encode(array('Status'=>0,'StatusDesc'=>'门票验证失败'));exit;
					}
				}
			}
		}else{
			echo json_encode(array('Status'=>0,'StatusDesc'=>'门票不存在！'));exit;
		}
	}

	public function getRc4Encode($pwd, $data) {

		$cipher      = '';
		$key[]       = "";
		$box[]       = "";
		$pwd_length  = strlen($pwd);
		$data_length = strlen($data);
		for ($i = 0; $i < 256; $i++) {
			$key[$i] = ord($pwd[$i % $pwd_length]);
			$box[$i] = $i;
		}

		for ($j = $i = 0; $i < 256; $i++) {
			$j       = ($j + $box[$i] + $key[$i]) % 256;
			$tmp     = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		for ($a = $j = $i = 0; $i < $data_length; $i++) {
			$a       = ($a + 1) % 256;
			$j       = ($j + $box[$a]) % 256;
			$tmp     = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$k       = $box[(($box[$a] + $box[$j]) % 256)];
			//dump(chr(ord($data[$i]) ^ $k));
			$cipher .= chr(ord($data[$i])^$k);
			// var_dump($cipher);
		}

		//dump($cipher);die;
		return $cipher;
	}
}
?>