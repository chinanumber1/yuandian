<?php
/*
 * 商户中心管理首页
 *
 */

class IndexAction extends BaseAction{
    public function index(){
		
		//商家公告
		$database_merchant_news = D('Merchant_news');
		$news_list = $database_merchant_news->field(true)->order('`is_top` DESC,`add_time` DESC')->limit(10)->select();
		$this->assign('news_list',$news_list);
		
		/**  商家数据统计 **/
		$mer_id = $this->merchant_session['mer_id'];
		$mer_money = M('Merchant')->field(true)->where(array('mer_id'=>$mer_id))->find();
		$this->assign('all_money',$mer_money['money']);
		//粉丝数量
		$where_fans =array('mer_id'=>$mer_id);
		$where_fans['_string'] = "u.openid<>'' AND m.openid<> ''";
		$pigcms_data['fans_count'] = M('Merchant_user_relation')->join('as m LEFT JOIN '.C('DB_PREFIX').'user u ON u.openid = m.openid')->where($where_fans)->count();
		//会员卡数量
		$pigcms_data['card_count'] = M('Card_userlist')->where(array('mer_id'=>$mer_id,'status'=>1))->count();
		//微活动数量
		$pigcms_data['lottery_count'] = M('Lottery')->where(array('mer_id'=>$mer_id))->count();
		//店铺数量
		$pigcms_data['store_count'] = M('Merchant_store')->where(array('mer_id'=>$mer_id,'status'=>array('neq',4)))->count();

		$this->assign($pigcms_data);
		
		$this->display();
    }
	public function news($id){
		$database_merchant_news = D('Merchant_news');
		$condition_merchant_news['id'] = $id;
		$now_news = $database_merchant_news->field(true)->where($condition_merchant_news)->find();
		if(empty($now_news)){
			$this->error('当前内容不存在！');
		}
		$this->assign('now_news',$now_news);
		
		$this->display();
	}

    /***收银台返回处理****/
	public function cashierBack(){
	   $lgcode=isset($_GET['lgcode']) ? trim($_GET['lgcode']) :'';
	   if($lgcode){
	      $merInfo=D("Merchant")->field('mer_id,account,name,phone,email')->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
		  if(!empty($merInfo)){
		    $tmplgcode=$merInfo['mer_id'].'_'.md5($merInfo['account']);
		    //echo $tmplgcode;die;
			$tmplgcode=md5($tmplgcode);
			if($lgcode==$tmplgcode){
			  Header('Location:/merchant.php');
			  exit();
			}
		  }
	   }
	   session('merchant',null);
	   $this->error('非法访问登录！');
	}
	


	public function ajax_help($group, $module, $action) 
	{
		$url = strtolower($group . '_' . $module . '_' . $action);
		$url = 'http://o2o-service.pigcms.com/workorder/serviceAnswerApi.php?url=' . $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$content = curl_exec($ch);
		curl_close($ch);

		$content = json_decode($content,true);
		foreach ($content as $value) {
			$class = $value['is_video'] == 1 ? 'class="video"' : 'class="writing"';
			$html .= '<p class="lianjie zuoce_clear "><a ' . $class . ' href="javascript:openwin(' . "'" . U('Index/help', array('answer_id' => $value['answer_id'])) . "'" .',768,960)">'.$value['title'].'</a></p>';
		}
		if (empty($html)) {
			$html = '<p class="lianjie zuoce_clear">没有帮助教程！</a>';
		}
		echo $html;
	}
	
	public function help()
	{
		$this->assign('answer_id', $_GET['answer_id']);
		$this->display();
	}
}