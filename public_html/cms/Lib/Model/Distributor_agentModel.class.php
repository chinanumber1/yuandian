<?php
class Distributor_agentModel extends Model{
	public function agent_add_money($mer_id,$money,$order_id){
		$user_model = D('User');
		$now_merchant = D('Merchant')->get_info($mer_id);
		$spread_code = $now_merchant['spread_code'];
		$spread_user = $user_model->get_user($spread_code,'spread_code');
		$res = $this->get_effective($spread_user['uid'],2);

		if($res['error_code']==0){
			$spread_money = round($money*C('config.agent_percent')/100,2);

			if($spread_money>0){
				$user_model->add_money($spread_user['uid'],$spread_money,"用户在商家【{$now_merchant['name']}】消费，代理商获得佣金{$spread_money}元");
				$this->add_row($spread_user['uid'],$now_merchant['mer_id'],1,$spread_money,$order_id,"用户在商家【{$now_merchant['name']}】消费，代理商获得佣金{$spread_money}元");
				fdump(M(),'mmm');
			}
		}
	}

	//获取代理商/分销员的有效性
	public function get_effective($uid,$type=1){
		$where['uid'] = $uid;
		$where['type'] = $type;
		$da = $this->where($where)->find();
		$type_name = $type==1?'分销员':'代理商';
		if($da){
			if( $da['start_time']<$_SERVER['REQUEST_TIME'] && $da['end_time']>$_SERVER['REQUEST_TIME']){
				return array('error_code'=>0,'msg'=>$type_name.'有效');
			}else{
				return array('error_code'=>1,'msg'=>$type_name.'不在有效期内，请续费','url'=>U('Wap/Distributor_agent/buy',array('type'=>$type)));
			}
		}else{
			return array('error_code'=>1,'msg'=>$type_name.'不存在');
		}
	}

	public function agent_spread_log($mer_id){

		$date['mer_id'] = $mer_id;
		$date['add_time'] = $_SERVER['REQUEST_TIME'];
		$now_merchant = D('Merchant')->get_info($mer_id);
		$spread_code = $now_merchant['spread_code'];
		$spread_user = D('User')->get_user($spread_code,'spread_code');
		$spread_count = M('Merchant')->where(array('spread_code'=>$spread_code))->count();
		$where['mer_id'] = $mer_id;
//		$where['uid']  = $spread_user['uid'];
		if(M('Agent_spread_log')->where($where)->find()){
			return array('error_code'=>1,'msg'=>'该商户已经被推广了');
		}
		if($spread_count>C('config.agent_spread_num')-1 && C('config.agent_spread_num')>0){
			return array('error_code'=>1,'msg'=>'推广数量超过限制');
		}
		$date['uid'] = $spread_user['uid'];
		$date['des'] = "【{$now_merchant['name']}】成功入驻平台";
		M('Agent_spread_log')->add($date);
	}

	public function add_row($uid,$mer_id,$income=1,$money,$order_id,$msg ){
		$time = $_SERVER['REQUEST_TIME'];
		$data_user_money_list['uid'] = $uid;
		$data_user_money_list['mer_id'] = $mer_id;
		$data_user_money_list['order_id'] = $order_id;
		$data_user_money_list['income'] = $income;
		$data_user_money_list['money'] = $money;
		$data_user_money_list['des'] = $msg;
		$data_user_money_list['add_time'] = $time;
		if(M('Agent_spread_money_list')->data($data_user_money_list)->add()){
			return true;
		}else{
			return false;
		}
	}

	public function get_agent_spread_list($uid,$page=0,$page_count=0){
		$condition_user_money_list['uid'] = $uid;

		$model = M('Agent_spread_money_list');
		import('@.ORG.user_page');
		$count = $model->where($condition_user_money_list)->count();
		$p = new Page($count,10);
		if($page){
			$return['money_list'] = $model->field(true)->where($condition_user_money_list)->order('`id` DESC')->page($page.','.$page_count)->select();
		}else{
			$return['money_list'] = $model->field(true)->where($condition_user_money_list)->order('`id` DESC')->limit($p->firstRow.',10')->select();
		}


		$return['pagebar'] = $p->show();
		return $return;
	}

	public function get_list($uid,$page=0,$page_count=0){
		$condition_user_money_list['uid'] = $uid;
		$model = M('Agent_spread_log');
		import('@.ORG.user_page');
		$count =$model->where($condition_user_money_list)->count();
		$p = new Page($count,10);
		if($page){
			$return['money_list'] = $model->field(true)->where($condition_user_money_list)->order('`id` DESC')->page($page.','.$page_count)->select();
		}else{
			$return['money_list'] = $model->field(true)->where($condition_user_money_list)->order('`id` DESC')->limit($p->firstRow.',10')->select();
		}
		$return['pagebar'] = $p->show();
		return $return;
	}

	//代理商分佣
	public function spread_money($now_order,$type=1){
		if($type==1){
			$arr['first_percent'] = C('config.first_distributor_percent');
			$arr['second_percent'] = C('config.second_distributor_percent');
			$arr['third_percent'] = C('config.third_distributor_percent');
		}else{
			$arr['first_percent'] = C('config.first_agent_percent');
			$arr['second_percent'] = C('config.second_agent_percent');
			$arr['third_percent'] = C('config.third_agent_percent');
		}
		$type_name = $type==1?'distributor':'agent';
		$uid = $now_order['uid'];
		$now_user =$order_user= D('User')->get_user($uid);
		$spread_users[]=$uid;
		if($now_user['wxapp_openid']!=''){
			$spread_where['_string'] = "openid = '{$now_user['openid']}' OR openid = '{$now_user['wxapp_openid']}' ";
		}else{
			$spread_where['_string'] = "openid = '{$now_user['openid']}'";
		}
		$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where)->find();

		$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
		$href = C('config.site_url') . '/wap.php?g=Wap&c=My&a=index';
		foreach ($arr as $key=>$percent) {
			if($now_user_spread['is_wxapp']){
				$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'wxapp_openid');
			}else{
				$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
			}
			$res = $this->get_effective($spread_user['uid'],$type);
			if($res['error_code']){
				break;
			}
			$spread_money  = round(($now_order['money']) * $percent / 100, 2);

			$spread_data =array('uid' => $spread_user['uid'],
					'spread_uid' => $now_user['uid'],
					'get_uid' => $now_order['uid'],
					'money' => $spread_money,
					'order_type' => $type_name,
					'order_id' => $now_order['order_id'],
					'third_id' => $type,
					'add_time' => $_SERVER['REQUEST_TIME']);

			if($spread_user['spread_change_uid']!=0){
				$spread_data['change_uid'] = 	$spread_user['spread_change_uid'];
			}

			D('User_spread_list')->data($spread_data)->add();
			if($key=='first_percent') {
				$spread_relation_txt = $order_user['nickname'];
			}else if($key=='second_percent'){
				$spread_relation_txt =$spread_user['nickname'].'的子用户'.$order_user['nickname'];
			}else{
				$spread_relation_txt = $spread_user['nickname'].'的子用户的子用户'.$order_user['nickname'] ;
			}

			if($spread_money>0) {
				$model->sendTempMsg('OPENTM201812627',
					array('href' => $href,
							'wecha_id' => $spread_user['openid'],
							'first' =>$spread_relation_txt. '通过您的分享购买了【' . $now_order['order_name'] . '】，您获得佣金。',
							'keyword1' => $spread_money,
							'keyword2' => date("Y年m月d日 H:i"),
							'remark' => '点击查看详情！')
					);
			}

			if($key=='third_percent'){
				break;
			}

			$now_user = $spread_user;

			if($spread_user['wxapp_openid']!=''){
				$spread_where['_string'] = "openid = '{$spread_user['openid']}' OR openid = '{$spread_user['wxapp_openid']}' ";
			}else{
				$spread_where['_string'] = "openid = '{$spread_user['openid']}'";
			}
			$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where)->find();
		}
	}


}
?>