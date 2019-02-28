<?php
/*
 * 待评价
 *
 */
class RatesAction extends BaseAction {
    public function index(){
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
		
		//我的未评价列表
		$order_list = D('Group')->get_rate_order_list($this->now_user['uid'],false,false);
		$this->assign('order_list',$order_list);
		
		$this->display();
    }
	public function rated(){
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
		
		//我的已评价列表
		$order_list = D('Group')->get_rate_order_list($this->now_user['uid'],true,false);
		$this->assign('order_list',$order_list);

		$this->display();
    }
	public function ajax_upload_pic(){
		$dom_id = $_POST['id'];
		$order_id = $_POST['order_id'];
		$order_type = $_POST['order_type'];
		if($order_type == 0){
			$pic_filepath = 'group';
		}elseif($order_type == 1){
			$pic_filepath = 'meal';
		} else {
			$pic_filepath = 'appoint';
		}
		if($_FILES['file']['error'] != 4){
			
			$param = array('size' => $this->config['reply_pic_size'], 'thumbMaxWidth' => $this->config['reply_pic_width'], 'thumbMaxHeight' => $this->config['reply_pic_height'], 'thumb' => true, 'imageClassPath' => 'ORG.Util.Image', 'thumbPrefix' => 'm_,s_', 'thumbRemoveOrigin' => false);
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'reply/' . $pic_filepath, 2, $param);
			if ($image['error']) {
				die('{"jsonrpc":"2.0","result":{"error_code":1001,"message":"'.$image['msg'].'"},"id":"'.$dom_id.'"}');
				exit(json_encode($image));
			} else {
				$title = $image['title']['file'];
				$database_reply_pic = D('Reply_pic');
				$data_reply_pic['name'] = $_POST['name'];
				$data_reply_pic['pic'] = $title;
				$data_reply_pic['uid'] = $this->user_session['uid'];
				$data_reply_pic['order_type'] = $order_type;
				$data_reply_pic['order_id'] = $order_id;
				$data_reply_pic['add_time'] = $_SERVER['REQUEST_TIME'];
				if($pigcms_id = $database_reply_pic->data($data_reply_pic)->add()){
					$reply_image_class = new reply_image();
					$url = $reply_image_class->get_image_by_path($data_reply_pic['pic'],$pic_filepath,'s');
						
					die('{"jsonrpc":"2.0","result":{"error_code":0,"pigcms_id":'.$pigcms_id.',"order_id":'.$order_id.',"url":"'.$url.'"},"id":"'.$dom_id.'"}');
				}else{
					die('{"jsonrpc":"2.0","result":{"error_code":1002,"message":"图片添加失败！请重试。"},"id":"'.$dom_id.'"}');
				}
			}
			
// 			$img_order_id = sprintf("%09d",$this->user_session['uid']);
// 			$rand_num = mt_rand(10,99).'/'.substr($img_order_id,0,3).'/'.substr($img_order_id,3,3).'/'.substr($img_order_id,6,3);
// 			$upload_dir = './upload/reply/'.$pic_filepath.'/'.$rand_num.'/'; 
// 			if(!is_dir($upload_dir)){
// 				mkdir($upload_dir,0777,true);
// 			}
// 			import('ORG.Net.Upload File');
// 			$upload = new Upload File();
// 			$upload->maxSize = $this->config['reply_pic_size']*1024*1024;
// 			$upload->allowExts = array('jpg','jpeg','png','gif');
// 			$upload->allowTypes = array('image/png','image/jpg','image/jpeg','image/gif');
// 			$upload->savePath = $upload_dir; 
// 			$upload->thumb = true;
// 			$upload->thumbType = 0;
// 			$upload->imageClassPath = 'ORG.Util.Image';
// 			$upload->thumbPrefix = 'm_,s_';
// 			$upload->thumbMaxWidth  = $this->config['reply_pic_width'];
// 			$upload->thumbMaxHeight = $this->config['reply_pic_height'];
// 			$upload->thumbRemoveOrigin = false;
// 			$upload->saveRule = 'uniqid';
// 			if($upload->upload()){
// 				$uploadList = $upload->getUpload FileInfo();
				
// 				$database_reply_pic = D('Reply_pic');
// 				$data_reply_pic['name'] = $_POST['name'];
// 				$data_reply_pic['pic'] = $rand_num.','.$uploadList[0]['savename'];
// 				$data_reply_pic['uid'] = $this->user_session['uid'];
// 				$data_reply_pic['order_type'] = $order_type;
// 				$data_reply_pic['order_id'] = $order_id;
// 				$data_reply_pic['add_time'] = $_SERVER['REQUEST_TIME'];
// 				if($pigcms_id = $database_reply_pic->data($data_reply_pic)->add()){
// 					$reply_image_class = new reply_image();
// 					$url = $reply_image_class->get_image_by_path($data_reply_pic['pic'],$pic_filepath,'s');
					
// 					die('{"jsonrpc":"2.0","result":{"error_code":0,"pigcms_id":'.$pigcms_id.',"order_id":'.$order_id.',"url":"'.$url.'"},"id":"'.$dom_id.'"}');
// 				}else{
// 					die('{"jsonrpc":"2.0","result":{"error_code":1002,"message":"图片添加失败！请重试。"},"id":"'.$dom_id.'"}');
// 				}
// 			}else{
// 				die('{"jsonrpc":"2.0","result":{"error_code":1001,"message":"'.$upload->getErrorMsg().'"},"id":"'.$dom_id.'"}');
// 			}
		}else{
			die('{"jsonrpc":"2.0","result":{"error_code":1000,"message":"没有选择图片！"},"id":"'.$dom_id.'"}');
		}
	}
	public function ajax_del_pic(){
		$database_reply_pic = D('Reply_pic');
		$condition_reply_pic['uid'] = $this->user_session['uid'];
		$condition_reply_pic['pigcms_id'] = $_POST['pic_id'];
		$condition_reply_pic['order_id'] = $_POST['order_id'];
		$now_order = $database_reply_pic->field('`pigcms_id`,`pic`,`order_type`')->where($condition_reply_pic)->find();
		if(!empty($now_order)){
			if($now_order['order_type'] == 0){
				$pic_filepath = 'group';
			}elseif($now_order['order_type'] == 1){
				$pic_filepath = 'meal';
			} else {
				$pic_filepath = 'appoint';
			}
			
			$reply_image_class = new reply_image();
			$reply_image_class->del_image_by_path($now_order['pic'],$pic_filepath);
			
			$condition_reply_pic_del['pigcms_id'] = $now_order['pigcms_id'];
			$database_reply_pic->where($condition_reply_pic)->delete();
		}
	}
	public function ajax_get_pic(){
		$reply_image_class = new reply_image();
		$pic_list = $reply_image_class->get_image_by_ids($_POST['pic_ids'],$_POST['order_type']);
		if($pic_list){
			echo json_encode($pic_list);
		}else{
			echo '0';
		}
	}
	public function reply_to(){
		$order_type = intval($_POST['order_type']);
        $score = intval($_POST['score']);
        $goods = '';
        $pre = '';
		if($order_type == 0){
			$now_order = D('Group_order')->get_order_detail_by_id($this->now_user['uid'],$_GET['order_id']);
			$data_reply['parent_id'] = $now_order['group_id'];
		}elseif($order_type == 1){
			$now_order = D('Meal_order')->where(array('uid' => $this->now_user['uid'], 'order_id' => $_GET['order_id']))->find();
			$data_reply['parent_id'] = $now_order['store_id'];
		} elseif($order_type == 3){
			$goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : 0;
			$goods_ids = explode(',', $goods_ids);
			
			$now_order = D('Shop_order')->get_order_detail(array('uid' => $this->now_user['uid'], 'order_id' => $_GET['order_id']));
			$data_reply['parent_id'] = $now_order['store_id'];

			$goodsids = array();
			if (isset($now_order['info'])) {
				foreach ($now_order['info'] as $row) {
					if (!in_array($row['goods_id'], $goodsids)) {
						$goodsids[] = $row['goods_id'];
						if (in_array($row['goods_id'], $goods_ids)) {
							$goods .= $pre . $row['name'];
							$pre = '#@#';
						}
					}
				}
			}
		} else {
			$now_order = D('Appoint_order')->get_order_detail_by_id($this->now_user['uid'], $_GET['order_id']);
			$now_order['status'] = $now_order['service_status'];
			$data_reply['parent_id'] = $now_order['appoint_id'];
                        
                        
                        //工作人员评分start
				$database_merchant_workers = D('Merchant_workers');
				$database_appoint_visit_order_info = D('Appoint_visit_order_info');
				$Map['appoint_order_id'] = $_GET['order_id'];
				$appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();
				if($appoint_visit_order_info){
				    $_Map['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'];
				    $merchant_workers_info = $database_merchant_workers->appoint_worker_info($_Map);
				    $profession_total_score = $merchant_workers_info['profession_total_score'];
				    $communicate_total_score = $merchant_workers_info['communicate_total_score'];
				    $speed_total_score = $merchant_workers_info['speed_total_score'];
				    $profession_num = $merchant_workers_info['profession_num'];
				    $communicate_num = $merchant_workers_info['communicate_num'];
				    $speed_num = $merchant_workers_info['speed_num'];
				    
				    if($merchant_workers_info){
					$profession_total_score += $score + 0;
					$communicate_total_score += $score + 0;
					$speed_total_score += $score + 0;
					$profession_num++;
					$communicate_num++;
					$speed_num++;
					
					$merchant_workers_data['profession_total_score'] = $profession_total_score;
					$merchant_workers_data['communicate_total_score'] = $communicate_total_score;
					$merchant_workers_data['speed_total_score'] = $speed_total_score;
					$merchant_workers_data['profession_num'] = $profession_num;
					$merchant_workers_data['communicate_num'] = $communicate_num;
					$merchant_workers_data['speed_num'] = $speed_num;
					$merchant_workers_data['profession_avg_score'] = $profession_total_score/$profession_num;
					$merchant_workers_data['communicate_avg_score'] = $communicate_total_score/$communicate_num;
					$merchant_workers_data['speed_avg_score'] = $speed_total_score/$speed_num;
					$merchant_workers_data['all_avg_score'] = ($merchant_workers_data['profession_avg_score'] + $merchant_workers_data['communicate_avg_score'] + $merchant_workers_data['speed_avg_score']) / 3;
					$merchant_workers_data['mer_id'] =  $now_order['mer_id'];
					$result = $database_merchant_workers->where($_Map)->data($merchant_workers_data)->save();
					if(!$result){
					    $this->error_tips('工作人员评分失败！');
					}
					
					$database_appoint_comment = D('Appoint_comment');
                   $database_appoint = D('Appoint');
					$_data['uid'] = $this->user_session['uid'];
					$_data['merchant_worker_id'] =  $appoint_visit_order_info['merchant_worker_id'];
					$_data['appoint_id'] = $now_order['appoint_id'];
					$_data['profession_score'] = $score;
					$_data['communicate_score'] = $score;
					$_data['speed_score'] = $score;
					if($inputimg){
					    $_data['comment_img'] = serialize($inputimg);
					}
					$_data['content'] = $_POST['comment'];
					$_data['add_time'] = time();
					$_data['order_id'] = $now_order['order_id'];
					$_data['mer_id'] = $now_order['mer_id'];

					if($database_appoint_comment->data($_data)->add()){
					    $database_merchant_workers->where(array('merchant_worker_id'=>$appoint_visit_order_info['merchant_worker_id']))->setInc('comment_num');
					    $database_appoint->where(array('appoint_id'=>$now_order['appoint_id']))->setInc('comment_num');
					}
				    }
				}
				//工作人员评分end
		}
                
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error('当前订单未付款！无法评论。');
		}
		if ($order_type == 3) {
			if($now_order['status'] < 2){
				$this->error('当前订单未消费！无法评论。');
			}
		} else {
			if(empty($now_order['status'])){
				$this->error('当前订单未消费！无法评论。');
			}
		}
		
		if($score > 5 || $score < 1){
			$this->error('评分只能1到5分！');
		}
		$database_reply = D('Reply');
		$data_reply['store_id'] = $now_order['store_id'];
		$data_reply['mer_id'] = $now_order['mer_id'];
		$data_reply['score'] = $score;
		$data_reply['order_type'] = $order_type;
		$data_reply['order_id'] = intval($_GET['order_id']);
		$data_reply['anonymous'] = intval($_POST['anonymous']);
		$data_reply['comment'] = $_POST['comment'];
		$data_reply['uid'] = $this->now_user['uid'];
		$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
		$data_reply['add_ip'] = get_client_ip(1);
		$data_reply['pic'] = isset($_POST['pic_ids']) ? $_POST['pic_ids'] : '';
		$data_reply['goods'] = $goods;
		if($database_reply->data($data_reply)->add()){
			if($order_type == 0){
				D('Group')->setInc_group_reply($now_order,$score);
				D('Group_order')->change_status($now_order['order_id'],2);
			}elseif($order_type == 1){
				D('Merchant_store')->setInc_meal_reply($now_order['store_id'], $score);
				D('Meal_order')->change_status($now_order['order_id'],2);
			} elseif($order_type == 3){
				D('Merchant_store')->setInc_shop_reply($now_order['store_id'], $score);
				D('Shop_order')->change_status($now_order['order_id'], 3);
				D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 8));
				$reply_goods = array();
				foreach ($goods_ids as $goods_id) {
					if (in_array($goods_id, $goodsids) && !in_array($goods_id, $reply_goods)) {
						$reply_goods[] = $goods_id;
						D('Shop_goods')->where(array('goods_id' => $goods_id))->setInc('reply_count', 1);
						D('Shop_order_detail')->where(array('goods_id' => $goods_id, 'order_id' => $now_order['order_id']))->save(array('is_goods' => 1));
					}
				}
			} else {
				D('Appoint')->setInc_appoint_reply($now_order, $score);
				D('Appoint_order')->change_status($now_order['order_id'], 2);
			}

			$database_merchant_score = D('Merchant_score');
			$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
			if(empty($now_merchant_score)){
				$data_merchant_score['parent_id'] = $now_order['mer_id'];
				$data_merchant_score['type'] = '1';
				$data_merchant_score['score_all'] = $score;
				$data_merchant_score['reply_count'] = 1;
				$database_merchant_score->data($data_merchant_score)->add();
			}else{
				$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
				$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
				$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
			}
			$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
			if(empty($now_store_score)){
				$data_store_score['parent_id'] = $now_order['store_id'];
				$data_store_score['type'] = '2';
				$data_store_score['score_all'] = $score;
				$data_store_score['reply_count'] = 1;
				$database_merchant_score->data($data_store_score)->add();
			}else{
				$data_store_score['score_all'] = $now_store_score['score_all']+$score;
				$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
				$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
			}
			if($this->config['feedback_score_add']>0){
			  	D('User')->add_extra_score($this->now_user['uid'],$this->config['feedback_score_add'],$this->config[$order_type.'_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
			  	D('Scroll_msg')->add_msg('feedback',$this->now_user['uid'],'用户'.$this->now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'对'.$this->config[$order_type.'_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
			}
			$this->success('添加评论成功！');
		}else{
			$this->error('添加评论失败！');
		}
	}
	public function del_invalid_pic(){
		if($_POST['order_type'] == 0){
			$pic_filepath = 'group';
		}elseif($_POST['order_type'] == 1){
			$pic_filepath = 'meal';
		} else {
			$pic_filepath = 'appoint';
		}
		$database_reply_pic = D('Reply_pic');
		$condition_reply_pic['uid'] = $this->user_session['uid'];
		$condition_reply_pic['order_type'] = $_POST['order_type'];
		$condition_reply_pic['order_id'] = $_POST['order_id'];
		$reply_pic_list = $database_reply_pic->field('`pic`')->where($condition_reply_pic)->select();
		if($reply_pic_list){
			$reply_image_class = new reply_image();
			foreach($reply_pic_list as $value){
				$reply_image_class->del_image_by_path($value['pic'],$pic_filepath);
			}
			$database_reply_pic->where($condition_reply_pic)->delete();
		}
	}
	
	public function meal()
	{
		//导航条
		$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
		$this->assign('web_index_slider',$web_index_slider);
		 
		//热门搜索词
		$search_hot_list = D('Search_hot')->get_list(12);
		$this->assign('search_hot_list',$search_hot_list);
	
		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
	
		//我的未评价列表
		$order_list = D('Meal_order')->get_rate_order_list($this->now_user['uid'],false,false);
		$this->assign('order_list',$order_list);
	
		$this->display();
	}
	
	public function meal_rated(){
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
		
		//我的已评价列表
		$order_list = D('Meal_order')->get_rate_order_list($this->now_user['uid'],true,false);
		$this->assign('order_list',$order_list);

		$this->display();
    }
	
	public function appoint()
	{
		//导航条
		$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
		$this->assign('web_index_slider',$web_index_slider);
		 
		//热门搜索词
		$search_hot_list = D('Search_hot')->get_list(12);
		$this->assign('search_hot_list',$search_hot_list);
	
		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
	
		//我的未评价列表
		$order_list = D('Appoint_order')->get_rate_order_list($this->now_user['uid'], false, false);
		$this->assign('order_list',$order_list);
	
		$this->display();
	}
	
	public function appoint_rated()
	{
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
		
		//我的已评价列表
		$order_list = D('Appoint_order')->get_rate_order_list($this->now_user['uid'], true, false);
		$this->assign('order_list',$order_list);

		$this->display();
    }
	
	public function shop()
	{
		//导航条
		$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
		$this->assign('web_index_slider',$web_index_slider);
		 
		//热门搜索词
		$search_hot_list = D('Search_hot')->get_list(12);
		$this->assign('search_hot_list',$search_hot_list);
	
		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
	
		//我的未评价列表
		$order_list = D('Shop_order')->get_rate_order_list($this->now_user['uid'],false,false);
		// $this->assign('order_list',$order_list);
		$this->assign('order_list',$order_list['order_list']);
		$this->assign('pagebar', $order_list['pagebar']);
	
		$this->display();
	}
	
	public function shop_rated()
	{
		//导航条
		$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
		$this->assign('web_index_slider',$web_index_slider);
		 
		//热门搜索词
		$search_hot_list = D('Search_hot')->get_list(12);
		$this->assign('search_hot_list',$search_hot_list);
	
		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
	
		//我的未评价列表
		import('@.ORG.user_page');
		$p = new Page($count, 10);

		$order_list = D('Shop_order')->get_rate_order_list($this->now_user['uid'], true, false);

		$this->assign('order_list',$order_list['order_list']);
		$this->assign('pagebar', $order_list['pagebar']);
		// dump($order_list['pagebar']);
	
		$this->display();
	}
	
}