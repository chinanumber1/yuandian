<?php
/*
 * 顾客交流
 *
 * @  Writers    Jaty
 * @  BuildTime  2015/1/10 14:09
 * 
 */
class MessageAction extends BaseAction{
	public function index(){
		redirect(U('Message/group_reply'));
	}
	/* 团购评论 */
    public function group_reply(){
		
		
		$mer_id = $this->merchant_session['mer_id'];
		$group_id = intval($_GET['group_id']);
		
		//团购列表
		$group_list = D('Group')->get_grouplist_by_MerchantId($mer_id);
		$this->assign('group_list',$group_list);
		
		//评论列表
		$table = array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'group_order'=>'o');
		if(!empty($group_id)){
			$condition = "`r`.`order_type`='0' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id' AND `o`.`group_id`='$group_id'";
		}else{
			$condition = "`r`.`order_type`='0' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id'";
		}
		
		$reply_count = D('')->table($table)->where($condition)->count();
		import('@.ORG.merchant_page');
		$p = new Page($reply_count,20);
		
		$reply_list = D('')->table($table)->where($condition)->limit($p->firstRow.','.$p->listRows)->order('`r`.`pigcms_id` DESC')->select();
		$this->assign('reply_list',$reply_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		
		$this->display();
    }
    
    
    
    
    
    
    
	/* 团购评论详情 */
	public function group_reply_detail(){
		$mer_id = $this->merchant_session['mer_id'];
		$pigcms_id = $_GET['id'];
		
		$table = array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'group_order'=>'o');
		$condition = "`r`.`order_type`='0' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id' AND `r`.`pigcms_id`='$pigcms_id'";
		$reply_detail = D('')->table($table)->where($condition)->find();
		
		if(empty($reply_detail)){
			$this->error('该评论不存在！');
		}
		
		if(IS_POST){
			if($reply_detail['merchant_reply_time']){
				$this->error('该评论已经回复过，禁止重复回复。');
			}
			$database_reply = D('Reply');
			$condition_reply['pigcms_id'] = $reply_detail['pigcms_id'];
			$data_reply['merchant_reply_content'] = $_POST['reply_content'];
			$data_reply['merchant_reply_time'] = $_SERVER['REQUEST_TIME'];
			if($database_reply->where($condition_reply)->data($data_reply)->save()){
				$this->success('回复成功！');
			}else{
				$this->error('回复失败！请重试。');
			}
		}else{
			
			//查找出店铺名称
			if(!empty($reply_detail['store_id'])){
				$now_store = D('Merchant_store')->get_store_by_storeId($reply_detail['store_id']);
				$this->assign('now_store',$now_store);
			}
			
			$this->assign('reply_detail',$reply_detail);
			$this->display();
		}
	}
	
	
	
	
	/* 订餐评论 */
    public function meal_reply(){
		
		
		$mer_id = $this->merchant_session['mer_id'];
		$store_id = intval($_GET['store_id']);
		
		//团购列表
		$store_list = D('Merchant_store')->field('store_id, name')->where("`have_meal`=1 AND `status`=1 AND `mer_id`='{$mer_id}'")->select();
		
		//评论列表
		$table = array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'foodshop_order'=>'o');
		if(!empty($store_id)){
			$condition = "`r`.`order_type`='4' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id' AND `o`.`store_id`='$store_id'";
		}else{
			$condition = "`r`.`order_type`='4' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id'";
		}
		
		$count = D('')->table($table)->where($condition)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count,20);
		
		$reply_list = D('')->table($table)->where($condition)->limit($p->firstRow.','.$p->listRows)->order('`r`.`pigcms_id` DESC')->select();

		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		
		$this->assign('store_list',$store_list);
		$this->assign('reply_list',$reply_list);
		$this->display();
    }
	/* 订餐评论详情 */
	public function meal_reply_detail(){
		$mer_id = $this->merchant_session['mer_id'];
		$pigcms_id = $_GET['id'];

		$table = array(C('DB_PREFIX') . 'reply' => 'r', C('DB_PREFIX') . 'foodshop_order' => 'o');
		$condition = "`r`.`order_type`='4' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id' AND `r`.`pigcms_id`='$pigcms_id'";
		$reply_detail = D('')->table($table)->where($condition)->find();
		

		if (empty($reply_detail)) {
			$this->error('该评论不存在！');
		}
		if (IS_POST) {
			if($reply_detail['merchant_reply_time']){
				$this->error('该评论已经回复过，禁止重复回复。');
			}
			$database_reply = D('Reply');
			$condition_reply['pigcms_id'] = $reply_detail['pigcms_id'];
			$data_reply['merchant_reply_content'] = $_POST['reply_content'];
			$data_reply['merchant_reply_time'] = $_SERVER['REQUEST_TIME'];
			if ($database_reply->where($condition_reply)->data($data_reply)->save()) {
				$this->success('回复成功！');
			} else {
				$this->error('回复失败！请重试。');
			}
		} else {
			$orderinfo = D('Foodshop_order')->get_order_detail(array('order_id' => $reply_detail['order_id']), 3);
			$this->assign('goods_list', isset($orderinfo['info']) ? $orderinfo['info'] : '');
			if (!empty($reply_detail['store_id'])) {
				$now_store = D('Merchant_store')->get_store_by_storeId($reply_detail['store_id']);
				$this->assign('now_store',$now_store);
			}
			$this->assign('reply_detail',$reply_detail);
			$this->display('shop_reply_detail');
		}
	
		
		
// 		$mer_id = $this->merchant_session['mer_id'];
// 		$pigcms_id = $_GET['id'];
		
// 		$table = array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'meal_order'=>'o');
// 		$condition = "`r`.`order_type`='1' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id' AND `r`.`pigcms_id`='$pigcms_id'";
// 		$reply_detail = D('')->table($table)->where($condition)->find();
		
// 		if(empty($reply_detail)){
// 			$this->error('该评论不存在！');
// 		}
		
// 		if(IS_POST){
// 			if($reply_detail['merchant_reply_time']){
// 				$this->error('该评论已经回复过，禁止重复回复。');
// 			}
// 			$database_reply = D('Reply');
// 			$condition_reply['pigcms_id'] = $reply_detail['pigcms_id'];
// 			$data_reply['merchant_reply_content'] = $_POST['reply_content'];
// 			$data_reply['merchant_reply_time'] = $_SERVER['REQUEST_TIME'];
// 			if($database_reply->where($condition_reply)->data($data_reply)->save()){
// 				$this->success('回复成功！');
// 			}else{
// 				$this->error('回复失败！请重试。');
// 			}
// 		}else{
			
// 			//查找出店铺名称
// 			if(!empty($reply_detail['store_id'])){
// 				$now_store = D('Merchant_store')->get_store_by_storeId($reply_detail['store_id']);
// 				$this->assign('now_store',$now_store);
// 			}
			
// 			$this->assign('reply_detail',$reply_detail);
// 			$this->display();
// 		}
	}

	
    public function shop_reply()
    {
		$mer_id = $this->merchant_session['mer_id'];
		$store_id = intval($_GET['store_id']);
		
		$store_list = D('Merchant_store')->field('store_id, name')->where("`have_shop`=1 AND `status`=1 AND `mer_id`='{$mer_id}'")->select();
		
		$table = array(C('DB_PREFIX') . 'reply' => 'r', C('DB_PREFIX') . 'shop_order' => 'o');
		if (!empty($store_id)) {
			$condition = "`r`.`order_type`='3' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id' AND `o`.`store_id`='$store_id'";
		} else {
			$condition = "`r`.`order_type`='3' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id'";
		}
		
		$count = D('')->table($table)->where($condition)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count,20);
		
		$reply_list = D('')->table($table)->where($condition)->limit($p->firstRow.','.$p->listRows)->order('`r`.`pigcms_id` DESC')->select();

		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		
		$this->assign('store_list',$store_list);
		$this->assign('reply_list',$reply_list);
		$this->display();
    }

	public function shop_reply_detail()
	{
		$mer_id = $this->merchant_session['mer_id'];
		$pigcms_id = $_GET['id'];

		$table = array(C('DB_PREFIX') . 'reply' => 'r', C('DB_PREFIX') . 'shop_order' => 'o');
		$condition = "`r`.`order_type`='3' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`mer_id`='$mer_id' AND `r`.`pigcms_id`='$pigcms_id'";
		$reply_detail = D('')->table($table)->where($condition)->find();
		

		if (empty($reply_detail)) {
			$this->error('该评论不存在！');
		}
		if (IS_POST) {
			if($reply_detail['merchant_reply_time']){
				$this->error('该评论已经回复过，禁止重复回复。');
			}
			$database_reply = D('Reply');
			$condition_reply['pigcms_id'] = $reply_detail['pigcms_id'];
			$data_reply['merchant_reply_content'] = $_POST['reply_content'];
			$data_reply['merchant_reply_time'] = $_SERVER['REQUEST_TIME'];
			if ($database_reply->where($condition_reply)->data($data_reply)->save()) {
				$this->success('回复成功！');
			} else {
				$this->error('回复失败！请重试。');
			}
		} else {
			$orderinfo = D('Shop_order')->get_order_detail(array('order_id' => $reply_detail['order_id']));
			$this->assign('goods_list', isset($orderinfo['info']) ? $orderinfo['info'] : '');
			if (!empty($reply_detail['store_id'])) {
				$now_store = D('Merchant_store')->get_store_by_storeId($reply_detail['store_id']);
				$this->assign('now_store',$now_store);
			}
			$this->assign('reply_detail',$reply_detail);
			$this->display();
		}
	}
}