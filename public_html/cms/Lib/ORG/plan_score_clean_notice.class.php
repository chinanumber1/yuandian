<?php
//超时自动取消订单
class plan_score_clean_notice extends plan_base
{

	public function runTask()
	{
		//清零时间的前一天发通知
		$clean_type = C('config.clean_score_type')==1?'score_count':'score_extra_count';
		$clean_time = strtotime(date(Y).'-'.C('config.score_clean_time')); //系统清零时间
		$level_list = M('User_level')->where(array('score_clean_time'=>array('neq','')))->field('level,score_clean_time')->select();
		$now_time = time();
		if(!empty($level_list)){
			foreach ($level_list as $time) {
				$tmp_clean_time= strtotime(date(Y).'-'.$time['score_clean_time']);
				//将不符合时间的过滤
				if($tmp_clean_time<$now_time||$now_time+86400<$tmp_clean_time){
					continue;
				}
				$level_clean_time[$time['level']] = $tmp_clean_time;
				$level[] = $time['level'];
			}
		}
		if(empty($level_clean_time) && ($clean_time<$now_time||$now_time+86400<$clean_time)){
			$this->keepThread();
			return true;
		}
		if(!empty($level)){
			$condition['_string'] = "openid<>''  AND {$clean_type}>0 OR level in(".implode(',',$level)
					.") AND (score_clean_notic_time=0 OR (score_clean_notic_time>0 AND score_clean_notic_time<".strtotime(date('Y',$now_time)."-01-01")." ))";
			$user_list = M('User')->field('openid,level')->where($condition)->select();
		}else{
			$condition = array($clean_type=>array('gt',0),'openid'=>array('neq',''));
			$condition['_string'] = "(score_clean_notic_time=0 OR (score_clean_notic_time>0 AND score_clean_notic_time<".strtotime(date('Y',$now_time)."-01-01")." ))";
			$user_list = M('User')->field('openid,level')->where()->select();
		}

		foreach ($user_list as $v) {
			$this->keepThread();
			if($v['level']>0 || ($clean_time > $now_time && ($now_time+86400)>$clean_time)){
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$href = C('config.site_url').'/wap?c=My&a=index';
				if($v['level']>0 && $level_clean_time[$v['level']]>0){
					$user_clean_time =  date("Y年m月d日 H:i",$level_clean_time[$time['level']]);
				}else{
					$user_clean_time = date("Y年m月d日 H:i", $clean_time);
				}
				$model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $v['openid'], 'first' => C('config.score_name')
						.'清零提醒', 'keyword1' => '您的'.C('config.score_name').'将于'.$user_clean_time.'清零', 'keyword2' => date("Y年m月d日 H:i"), 'remark' => ''),'');
				M('User')->where(array('uid'=>$v['uid']))->setField('score_clean_notic_time',$now_time);
			}
		}

		return true;
	}
}
?>