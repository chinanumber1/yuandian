<?php
class plan_wechat_mass extends plan_base{
	public function runTask($param){
		$send_id = $param['id'];
		$log = D('Send_log')->where(array('pigcms_id' => $send_id))->find();
		
		$where = array('mer_id' => $log['mer_id']);
//		switch ($log['type']) {
//			case 0:
//				break;
//			case 1:
//				$where['from_merchant'] = 1;
//				break;
//			case 2:
//				$where['from_merchant'] = 0;
//				break;
//			case 3:
//				$where['from_merchant'] = 2;
//				break;
//		}
		$log['type']>0 && $where['from_merchant']  = $log['type'];

		$mer_use_obj = D('Merchant_user_relation');
		$openids = $mer_use_obj->field('openid')->where($where)->order('dateline DESC')->select();
		$source = D('Source_material')->where(array('pigcms_id' => $log['c_id']))->find();
		
		if (empty($source)) return true;
		$ids = unserialize($source['it_ids']);
		$image_text = D('Image_text')->field(true)->where(array('pigcms_id' => array('in', $ids)))->select();
		$result = array();
		foreach ($image_text as $txt) {
			$result[$txt['pigcms_id']] = $txt;
		}
		$image_text = array();
		foreach ($ids as $id) {
			$image_text[] = isset($result[$id]) ? $result[$id] : array();
		}
		
		
		$access_token_array = D('Access_token_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			return true;
		}
		$access_token = $access_token_array['access_token'];
			
		$send_to_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
		$send_obj = M('Send_user');
		import('ORG.Net.Http');
		foreach ($openids as $user) {
			if ($send_obj->field(true)->where(array('log_id' => $send_id, 'openid' => $user['openid']))->find()) {
				continue;
			}
			$this->keepThread();
			$str = '{"touser":"'.$user['openid'].'","msgtype":"news","news":{"articles": [';
			$pre = '';
			foreach ($image_text as $txt) {
				$url = C('config.site_url') . '/wap.php?g=Wap&c=Article&a=index&imid=' . $txt['pigcms_id'].'&lid='.$send_id;
				$str .= $pre . '{"title":"'.$txt['title'].'", "description":"'.$txt['digest'].'", "url":"'. $url .'", "picurl":"'.C('config.site_url') . $txt['cover_pic'].'"}';
				$pre = ',';
			}
			$str .= ']}}';
			
			$rt = Http::curlPost($send_to_url, $str);
			$user = D('User')->get_user($user['openid'],'openid');
			if ($rt['errcode']) {
				$send_obj->add(array('log_id' => $send_id, 'openid' => $user['openid'], 'status' => 2, 'sendtime' => time()));
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$model->sendTempMsg('OPENTM405462911',
						array('href' => C('config.site_url') . '/wap.php?g=Wap&c=Article&a=article_list&id=' . $log['c_id'],
								'wecha_id' => $user['openid'],
								'first' => '您好，您有新消息查看\n',
								'keyword1' => '商家粉丝群发',
								'keyword2' => '成功',
								'keyword3' => date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),
								'keyword4' => $user['nickname'],
								'remark' => '点击查看群发消息详情！'));
			} else {
				$send_obj->add(array('log_id' => $send_id, 'openid' => $user['openid'], 'status' => 1, 'sendtime' => time()));
			}
		}
		
		D('Send_log')->where(array('pigcms_id' => $send_id))->save(array('status' => 1));
		return true;
	}
	
	/*
	 *  添加任务
	 *
	 *  param array 消息参数
	 *
	 *  param.type   1为短信通知，2为模板消息，3为图文消息，4为APP通知
	 *
	 *
	 *
	 *  param.content 不同的业务，不同的值，传数组
	 *
	 *  param.send_time  发送时间
	 *
	 *
	 *
	 *
	 */
	public function addTask($param){
		$data['type'] = $param['type'];
		$data['label'] = $param['label'] ? (is_array($param['label']) ? implode(',',$param['label']) : $param['label']) : '';
		if(!is_array($param['content'])){
			$param['content'] = array($param['content']);
		}else if(empty($param['content'][0])){
			$param['content'] = array($param['content']);
		}
		$data['content'] = serialize($param['content']);
		$data['send_time'] = $param['send_time'] ? $param['send_time'] : time();
		$data['add_time'] = time();
		$task_id = M('Process_plan_msg')->data($data)->add();
		
		import('@.ORG.plan');
		$plan_class = new plan();
		$param = array(
			'file'=>'msg',
			'plan_time'=>$data['send_time'],
			'param'=>array(
				'id'=>$task_id,
			),
		);
		$plan_class->addTask($param);
	}
}
?>