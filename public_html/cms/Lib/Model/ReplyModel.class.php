<?php
/**
 * order_type 0:代表团购，1：订餐，2：预约，3：快店，4：新的餐饮
 *
 */
class ReplyModel extends Model{
	/*得到带分页的评论列表*/
	public function get_page_reply_list($parent_id,$order_type,$tab='good',$order='',$store_count=0,$show_count=false, $from_type = 0){
		switch($tab){
			case 'high':
				$condition_reply = '`score`>3 AND ';
				break;
			case 'mid':
				$condition_reply = '`score`=3 AND ';
				break;
			case 'low':
				$condition_reply = '`score`<3 AND ';
				break;
			case 'withpic':
				$condition_reply = "`pic`<>'' AND ";
				break;
			case 'good':
				$condition_reply = '`score`>=3 AND ';
				break;
			case 'wrong':
				$condition_reply = '`score`<3 AND ';
				break;
		}

		if ($order == 'time') {
			$condition_order = '`r`.`add_time` DESC';
		} elseif($order == 'score') {
			$condition_order = '`r`.`score` DESC';
		}elseif($order_type == '3'){
			$condition_order = '`r`.`pigcms_id` DESC';
		}else{
			$condition_order = '`r`.`pigcms_id` ASC';
		}
		if ($from_type == 0) {
			import('@.ORG.new_reply_ajax_page');
		} elseif ($from_type == 1) {
		    import('@.ORG.shop_pc_comment_page');
		} elseif ($from_type == 2) {
		    import('@.ORG.mall_pc_comment_page');
		}

		if(empty($parent_id)){
			$condition_reply_page = $condition_reply."`order_type`='$order_type' AND `status`<=1";
		}else{

			if($order_type == '3' && $show_count){
				// if($order_type == '3'){
				$return['all_count'] = $this->where("`order_type`='$order_type' AND `parent_id`='$parent_id' AND `status`<2")->count();
				$return['good_count'] = $this->where("`score` >= 3 AND `order_type`='$order_type' AND `parent_id`='$parent_id' AND `status`<2")->count();
				$return['wrong_count'] = $this->where("`score` < 3 AND `order_type`='$order_type' AND `parent_id`='$parent_id' AND `status`<2")->count();
			}
			$condition_reply_page = $condition_reply."`order_type`='$order_type' AND `parent_id`='$parent_id' AND `status`<=1";
		}

		$reply_count = $this->where($condition_reply_page)->count();
		$page_size = 10;
		$p = new Page($reply_count, $page_size);

		if(($order_type == 0) && $store_count > 1){
			$reply_list = D('')->field('`s`.`name` `store_name`,`u`.`nickname`,`u`.`avatar`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'user'=>'u'))->where((!empty($condition_reply) ? '`r`.'.$condition_reply : '')."`r`.`parent_id`='$parent_id' AND `r`.`status`<=1 AND `r`.`order_type`='$order_type' AND `r`.`order_id`=`o`.`order_id` AND `o`.`store_id`=`s`.`store_id` AND `r`.`uid`=`u`.`uid`")->order($condition_order)->limit($p->firstRow . ',' . $page_size)->select();
		}else{
			if(empty($parent_id)){

				$reply_condition = (!empty($condition_reply) ? '`r`.'.$condition_reply : '')."  `r`.`order_type`='$order_type' AND `r`.`uid`=`u`.`uid`";
			}else{
				$reply_condition = (!empty($condition_reply) ? '`r`.'.$condition_reply : '')."`r`.`parent_id`='$parent_id' AND `r`.`order_type`='$order_type' AND `r`.`uid`=`u`.`uid`";

			}
			if($order_type == 2){
				$reply_condition .= ' AND `r`.`status`=1';
			} else {
				$reply_condition .= ' AND `r`.`status`<=1';
			}
			$reply_list = D('')->field('`u`.`nickname`,`u`.`avatar`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'user'=>'u'))->where($reply_condition)->order($condition_order)->limit($p->firstRow . ',' . $page_size)->select();


		}

		if($reply_list){
			$pic_arr = array();
			$new_pic_arr = array();
			foreach($reply_list as $key=>$value){
				if (isset($value['goods']) && $value['goods']) {
					$reply_list[$key]['goods'] = explode('#@#', $value['goods']);
				}
				$reply_list[$key]['add_time'] = date('Y-m-d',$value['add_time']);
				$reply_list[$key]['add_time_hi'] = date('Y-m-d H:i',$value['add_time']);
				if($value['merchant_reply_time']){
					$reply_list[$key]['merchant_reply_time_hi'] = date('Y-m-d H:i',$value['merchant_reply_time']);
				}
				if($value['anonymous']){
					if(msubstr($value['nickname'],0,2,false) == $value['nickname']){
						$reply_list[$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**';
					}else{
						$reply_list[$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**'.msubstr($value['nickname'],-1,1,false);
					}
				}

				if(C('config.open_filter_word')==1){
					$filter_words = S('Filter_words');
					if(empty($filter_words)){
						$filter_words = M('Filter_word')->field('word')->select();
						$filter_words = array_values($filter_words);
						S('Filter_words',$filter_words);
					}
					$reply_list[$key]['comment'] = str_replace($filter_words,'***',$reply_list[$key]['comment']);
				}

				if(!empty($value['pic'])){
					$tmp_arr = explode(',',$value['pic']);
					foreach($tmp_arr as $v){
						$new_pic_arr[$v] = $key;
					}
					$pic_arr = array_merge($pic_arr,$tmp_arr);
				}
			}
			if ($order_type == 0) {
				$pic_filepath = 'group';
			} elseif($order_type == 1) {
				$pic_filepath = 'meal';
			} else {
				$pic_filepath = 'appoint';
			}
			if($pic_arr){
				$condition_reply_pic['pigcms_id'] = array('in',implode(',',$pic_arr));
				$reply_pic_list = D('Reply_pic')->field('`pigcms_id`,`pic`')->where($condition_reply_pic)->select();
				$reply_image_class = new reply_image();
				foreach($reply_pic_list as $key=>$value){
					$tmp_value = $reply_image_class->get_image_by_path($value['pic'],$pic_filepath);
					$reply_list[$new_pic_arr[$value['pigcms_id']]]['pics'][] = $tmp_value;
				}
			}
		}
		$reply_page = $p->show();
		$return['count'] = $reply_count;
		$return['list']  = $reply_list ? $reply_list : array();
		$return['page']  = $reply_page;
		$return['now']  = $p->nowPage;
		$return['total']  = $p->totalPage;
		return $return;
	}
	/*得到规定条数的评论列表,不带评论*/
	public function get_reply_list($parent_id,$order_type,$store_count,$limit){

		$condition_order = '`r`.`add_time` DESC';
		if($order_type == 0 && $store_count > 1){
			$reply_list = D('')->field('`s`.`name` `store_name`,`u`.`nickname`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'user'=>'u'))->where("`r`.`parent_id`='$parent_id' AND `r`.`status`<=1 AND `r`.`order_type`='$order_type' AND `r`.`order_id`=`o`.`order_id` AND `o`.`store_id`=`s`.`store_id` AND `r`.`uid`=`u`.`uid`")->order($condition_order)->limit($limit)->select();
		}else{
			$reply_list = D('')->field('`u`.`nickname`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'user'=>'u'))->where("`r`.`parent_id`='$parent_id' AND `r`.`status`<=1 AND `r`.`order_type`='$order_type' AND `r`.`uid`=`u`.`uid`")->order($condition_order)->limit($limit)->select();
		}

		if($reply_list){
			$pic_arr = array();
			$new_pic_arr = array();
			foreach($reply_list as $key=>$value){
				if($value['anonymous']){
					if(msubstr($value['nickname'],0,2,false) == $value['nickname']){
						$reply_list[$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**';
					}else{
						$reply_list[$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**'.msubstr($value['nickname'],-1,1,false);
					}
				}
				$reply_list[$key]['add_time'] = date('Y-m-d',$value['add_time']);

				if(!empty($value['pic'])){
					$tmp_arr = explode(',',$value['pic']);
					foreach($tmp_arr as $v){
						$new_pic_arr[$v] = $key;
					}
					$pic_arr = array_merge($pic_arr,$tmp_arr);
				}
			}
			if($order_type == 0){
				$pic_filepath = 'group';
			}elseif($order_type == 1){
				$pic_filepath = 'meal';
			} else {
				$pic_filepath = 'appoint';
			}
			if($pic_arr){
				$condition_reply_pic['pigcms_id'] = array('in',implode(',',$pic_arr));
				$reply_pic_list = D('Reply_pic')->field('`pigcms_id`,`pic`')->where($condition_reply_pic)->select();
				$reply_image_class = new reply_image();
				foreach($reply_pic_list as $key=>$value){
					$tmp_value = $reply_image_class->get_image_by_path($value['pic'],$pic_filepath);
					$reply_list[$new_pic_arr[$value['pigcms_id']]]['pics'][] = $tmp_value;
				}
			}
		}
		return $reply_list;
	}


	/*得到规定条数的评论列表*/
	public function ajax_reply_list($parent_id, $order_type, $store_count, $limit, $start)
	{
		$condition_order = '`r`.`add_time` DESC';
		if($order_type == 0 && $store_count > 1){
			$reply_list = D('')->field('`s`.`name` `store_name`,`u`.`nickname`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'user'=>'u'))->where("`r`.`parent_id`='$parent_id' AND `r`.`status`<=1 AND `r`.`order_id`=`o`.`order_id` AND `o`.`store_id`=`s`.`store_id` AND `r`.`uid`=`u`.`uid`")->order($condition_order)->limit($start, $limit)->select();
		}else{
			$reply_list = D('')->field('`u`.`nickname`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'user'=>'u'))->where("`r`.`parent_id`='$parent_id' AND `r`.`uid`=`u`.`uid` AND `r`.`status`<=1")->order($condition_order)->limit($start, $limit)->select();
// 			echo D('')->_sql();die;
		}

		if($reply_list){
			$pic_arr = array();
			$new_pic_arr = array();
			foreach($reply_list as $key=>$value){
				$reply_list[$key]['add_time'] = date('Y-m-d',$value['add_time']);

				if(!empty($value['pic'])){
					$tmp_arr = explode(',',$value['pic']);
					foreach($tmp_arr as $v){
						$new_pic_arr[$v] = $key;
					}
					$pic_arr = array_merge($pic_arr,$tmp_arr);
				}
			}
			if($order_type == 0){
				$pic_filepath = 'group';
			}else{
				$pic_filepath = 'meal';
			}
			if($pic_arr){
				$condition_reply_pic['pigcms_id'] = array('in',implode(',',$pic_arr));
				$reply_pic_list = D('Reply_pic')->field('`pigcms_id`,`pic`')->where($condition_reply_pic)->select();
				$reply_image_class = new reply_image();
				foreach($reply_pic_list as $key=>$value){
					$tmp_value = $reply_image_class->get_image_by_path($value['pic'],$pic_filepath);
					$reply_list[$new_pic_arr[$value['pigcms_id']]]['pics'][] = $tmp_value;
				}
			}
		}
		return $reply_list;
	}


	/*得到带分页的评论列表*/
	public function get_reply_listByid($mer_id,$store_id=0,$tab='all',$order,$pagesize=0){
		$condition_reply_page=$condition_reply='';
		switch($tab){
			case 'high':
				$condition_reply = '`score`>3 AND ';
				break;
			case 'mid':
				$condition_reply = '`score`=3 AND ';
				break;
			case 'low':
				$condition_reply = '`score`<3 AND ';
				break;
			case 'withpic':
				$condition_reply = "`pic`<>'' AND ";
				break;
		}
		if ($order == 'time') {
			$condition_order = '`r`.`add_time` DESC';
		} elseif($order == 'score') {
			$condition_order = '`r`.`score` DESC';
		} else {
			$condition_order = '`r`.`pigcms_id` ASC';
		}
		import('@.ORG.common_page');

		$condition_reply_page = !empty($condition_reply) ? " `r`." . $condition_reply . " `r`.`mer_id`='$mer_id' AND `r`.`status`<2" : " `r`.`mer_id`='$mer_id' AND `r`.`status`<2";
		$condition_reply_count = $condition_reply . " `mer_id`='$mer_id' AND `status`<2";
		
		if($store_id>0){
			$condition_reply_page = $condition_reply_page." AND `r`.`store_id`='$store_id'";
			$condition_reply_count=$condition_reply_count." AND `store_id`='$store_id'";
		}
		$reply_count = $this->where($condition_reply_count)->count();

		$p = new Page($reply_count,$pagesize);
		$reply_list = D('')->field('`s`.`name` as 	store_name,`u`.`nickname`,`u`.`avatar`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'user'=>'u'))->where($condition_reply_page." AND `r`.`store_id`=`s`.`store_id` AND `r`.`uid`=`u`.`uid`")->order($condition_order)->limit($p->firstRow.','.$pagesize)->select();
		if($reply_list){
			$pic_arr = array();
			$new_pic_arr = array();
			foreach($reply_list as $key=>$value){
				$reply_list[$key]['add_time'] = date('Y-m-d',$value['add_time']);
				if($value['anonymous']){
					if(msubstr($value['nickname'],0,2,false) == $value['nickname']){
						$reply_list[$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**';
					}else{
						$reply_list[$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**'.msubstr($value['nickname'],-1,1,false);
					}
				}
				if(!empty($value['pic'])){
					$tmp_arr = explode(',',$value['pic']);
					foreach($tmp_arr as $v){
						$new_pic_arr[$v] = $key;
					}
					$pic_arr = array_merge($pic_arr,$tmp_arr);
				}
			}
			if($pic_arr){
				$condition_reply_pic['pigcms_id'] = array('in',implode(',',$pic_arr));
				$reply_pic_list = D('Reply_pic')->field('`pigcms_id`,`pic`,order_type')->where($condition_reply_pic)->select();
				$reply_image_class = new reply_image();
				foreach($reply_pic_list as $key=>$value){
					$order_type=intval($value['order_type']);
					if($order_type == 0){
						$pic_filepath = 'group';
					}else{
						$pic_filepath = 'meal';
					}
					$tmp_value = $reply_image_class->get_image_by_path($value['pic'],$pic_filepath);
					$reply_list[$new_pic_arr[$value['pigcms_id']]]['pics'][] = $tmp_value;
				}
			}
		}
		$reply_page = $p->show();
		$return['count'] = $reply_count;
		$return['list']  = $reply_list;
		$return['page']  = $reply_page;
		$return['now']  = $p->nowPage;
		$return['total']  = $p->totalPage;
		return $return;
	}


	public function get_appoint_reply_list($tab='high',$order = 'score'){
		switch($tab){
			case 'high':
				$condition_reply = '`score`>3 AND ';
				break;
			case 'mid':
				$condition_reply = '`score`=3 AND ';
				break;
			case 'low':
				$condition_reply = '`score`<3 AND ';
				break;
			case 'withpic':
				$condition_reply = "`pic`<>'' AND ";
				break;
		}
		if ($order == 'time') {
			$condition_order = '`r`.`add_time` DESC';
		} elseif($order == 'score') {
			$condition_order = '`r`.`score` DESC';
		} else {
			$condition_order = '`r`.`pigcms_id` ASC';
		}
		$reply_list = D('')->field('`s`.`name` as 	store_name,`u`.`nickname`,`u`.`avatar`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'user'=>'u'))->where($condition_reply ."`r`.`store_id`=`s`.`store_id` AND `r`.`uid`=`u`.`uid` AND `r`.`status` = 1 AND `r`.`order_type` = 2")->order($condition_order)->select();
		if($reply_list){
			$pic_arr = array();
			$new_pic_arr = array();
			$database_user = D('User');
			foreach($reply_list as $key=>$value){
				$reply_list[$key]['add_time'] = date('Y-m-d',$value['add_time']);
				if($value['anonymous']){
					if(msubstr($value['nickname'],0,2,false) == $value['nickname']){
						$reply_list[$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**';
					}else{
						$reply_list[$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**'.msubstr($value['nickname'],-1,1,false);
					}
				}
				if(!empty($value['pic'])){
					$tmp_arr = explode(',',$value['pic']);
					foreach($tmp_arr as $v){
						$new_pic_arr[$v] = $key;
					}
					$pic_arr = array_merge($pic_arr,$tmp_arr);
				}

				$where['uid'] = $value['uid'];
				$reply_list[$key]['sex'] = $database_user->where($where)->getField('sex');

			}
			if($pic_arr){
				$condition_reply_pic['pigcms_id'] = array('in',implode(',',$pic_arr));
				$reply_pic_list = D('Reply_pic')->field('`pigcms_id`,`pic`,order_type')->where($condition_reply_pic)->select();
				$reply_image_class = new reply_image();
				foreach($reply_pic_list as $key=>$value){
					$order_type=intval($value['order_type']);
					if($order_type == 0){
						$pic_filepath = 'group';
					}else{
						$pic_filepath = 'meal';
					}
					$tmp_value = $reply_image_class->get_image_by_path($value['pic'],$pic_filepath);
					$reply_list[$new_pic_arr[$value['pigcms_id']]]['pics'][] = $tmp_value;
				}
			}
		}
		return $reply_list;
	}



	//预约不带分页列表
	public function get_appointReply_list($appoint_id,$Map=array()){
		if(!$appoint_id){
			return false;
		}

		$database_appoint = D('Appoint');
		$where['_string']=C('DB_PREFIX').'appoint_order.appoint_id='.$appoint_id;
		$field=C('DB_PREFIX').'appoint_order.order_id,'.C('DB_PREFIX').'appoint_order.appoint_id';
		$order_id_arr = $database_appoint->join('__APPOINT_ORDER__ ON __APPOINT__.appoint_id=__APPOINT_ORDER__.appoint_id')->where($where)->getField($field);
		$reply_list=array();
		if($order_id_arr){
			$database_reply = D('Reply');
			$Map['order_id'] = array('in',array_keys($order_id_arr));
			$Map['status'] = 1;
			$reply_list = $database_reply->where($Map)->select();
			if($reply_list){
				$pic_arr = array();
				$new_pic_arr = array();
				foreach($reply_list as $key=>$value){
					$reply_list[$key]['add_time'] = date('Y-m-d',$value['add_time']);
					if(!empty($value['pic'])){
						$tmp_arr = explode(',',$value['pic']);
						foreach($tmp_arr as $v){
							$new_pic_arr[$v] = $key;
						}
						$pic_arr = array_merge($pic_arr,$tmp_arr);
					}

					$user_info=D('User')->where(array('uid'=>$value['uid']))->field('nickname,avatar')->find();
					$reply_list[$key]['nickname']= $user_info['nickname'];
					$reply_list[$key]['avatar']= $user_info['avatar'];
				}

				if($pic_arr){
					$condition_reply_pic['pigcms_id'] = array('in',implode(',',$pic_arr));
					$reply_pic_list = D('Reply_pic')->field('`pigcms_id`,`pic`')->where($condition_reply_pic)->select();
					$reply_image_class = new reply_image();
					foreach($reply_pic_list as $key=>$value){
						$tmp_value = $reply_image_class->get_image_by_path($value['pic'],'appoint');
						$reply_list[$new_pic_arr[$value['pigcms_id']]]['pics'][] = $tmp_value;
					}
				}


			}
		}
		return $reply_list;
	}
}

?>