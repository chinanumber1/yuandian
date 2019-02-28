<?php
class Merchant_workersModel extends Model{
    //添加工作人员
    public function merchant_workers_add($data){
		if(!$data){
			return false;
		}
		$data['add_time']=time();
		$insert_id = $this->data($data)->add();
		if($insert_id){
			return true;
		}else{
			return false;
		}
    }


    //修改工作人员
    public function merchant_workers_edit($data,$where){
		if(!$data){
			return false;
		}
		$data['add_time']=time();
		$insert_id=$this->where($where)->save($data);
		if($insert_id){
			return true;
		}else{
			return false;
		}
    }


    public function wap_merchant_worker_list($order,$where=array()){
		//排序相关
		switch($order){
			case 'price':
				$order = 'appoint_price asc';
				break;
			case 'appointNum':
				$order = 'appoint_num DESC';
				break;
			case 'all_avg_score':
				$order = 'all_avg_score desc';
				break;
			default:
				$order = 'merchant_worker_id DESC';

		}
			import('@.ORG.wap_group_page');
			$where['status'] = 1;
			$count_worker = $this->where($where)->order($order)->count();
			C('config.appoint_page_row','10');
			$p = new Page($count_worker,C('config.appoint_page_row'),C('config.appoint_page_val'));
			$worker_list = $this->where($where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
			foreach($worker_list as $k=>$v){
				$worker_list[$k]['avatar_path']=  str_replace(',', '/', $v['avatar_path']);
			}
			$return['pagebar'] = $count_worker>0?$p->show():'';
			$return['worker_list'] = $worker_list;
			$return['totalPage'] = $count_worker > 0?$p->totalPage:'';
			$return['meal_count'] = $count_worker;
			return $return;
    }


    public function appoint_list($merchant_worker_id){
		$database_merchant_workers_appoint = D('Merchant_workers_appoint');
		$database_appoint = D('Appoint');
		if(!$merchant_worker_id){
			return false;
		}

		$where['merchant_worker_id']=$merchant_worker_id;
		$merchant_workers_appoint_list=$database_merchant_workers_appoint->where($where)->getField('id,appoint_id');
		if(!$merchant_workers_appoint_list){
			return false;
		}
		$Map['appoint_id']=array('in',$merchant_workers_appoint_list);
		$appoint_list = $database_appoint->where($Map)->select();
		if(!$appoint_list){
			return false;
		}

		foreach($appoint_list as $k=>$v){
			$appoint_list[$k]['pic']=  str_replace(',', '/', $v['pic']);
		}

		return $appoint_list;
		}


		public function appoint_worker_info($where , $field = true){
		if(!$where){
			return false;
		}
		$info = $this->where($where)->field($field)->find();
		if(!$info){
			return false;
		}

		$info['avatar_path'] = str_replace(',','/',$info['avatar_path']);
		return $info;
    }


    public function wap_get_worker_collect_list($uid){
		$condition_where = "`c`.`type`='worker_detail' AND `c`.`uid`='$uid' AND `s`.`status` = 1 AND `s`.`merchant_worker_id` = `c`.`id` ";
		$condition_table = array(C('DB_PREFIX').'merchant_workers'=>'s',C('DB_PREFIX').'user_collect'=>'c');
		$order = '`c`.`collect_id` DESC';

		import('@.ORG.wap_collect_page');
		$count_store = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_store,10,'page');
		$worker_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

		if($worker_list){
			$store_image_class = new store_image();
			foreach($worker_list as &$v){
				$v['avatar_path'] = $this->get_image_by_path($v['avatar_path']);
			}
		}

		$return['worker_list'] = $worker_list;
		$return['pagebar'] = $p->show();
		return $return;
    }


    private function get_image_by_path($path,$image_type='-1'){
	    if(!empty($path)){
			$image_tmp = explode(',',$path);
			if($image_type == '-1'){
				$return['image'] = C('config.site_url').'/upload/appoint/'.$image_tmp[0].'/'.$image_tmp['1'];
				$return['m_image'] = C('config.site_url').'/upload/appoint/'.$image_tmp[0].'/m_'.$image_tmp['1'];
				$return['s_image'] = C('config.site_url').'/upload/appoint/'.$image_tmp[0].'/s_'.$image_tmp['1'];
			}else{
				$return = C('config.site_url').'/upload/appoint/'.$image_tmp[0].'/'.$image_type.'_'.$image_tmp['1'];
			}
			return $return;
		}else{
			return false;
		}
	}


	// wap用
	public function get_list_by_search($w, $order = 'store_id',$is_wap = false){
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_table = array(C('DB_PREFIX').'merchant_workers'=>'g');

		//排序相关
		switch($order){
			case 'price':
				$order = '`g`.`appoint_price` ASC,`g`.`merchant_worker_id` DESC';
				break;
			case 'priceDesc':
				$order = '`g`.`appoint_price` DESC,`g`.`appoint_id` DESC';
				break;
			case 'appointNum':
				$order = '`g`.`appoint_sum` DESC,`g`.`appoint_id` DESC';
				break;
			case 'hot':
			    $order = '`g`.`appoint_num` DESC';
			    break;
			default:
				$order = '`g`.`merchant_worker_id` DESC';
		}
		if($w){
			$condition_where .= " `g`.`name` like '%$w%' ";
		}
		if(empty($is_wap)){
			import('@.ORG.group_page');
		}else{
			import('@.ORG.wap_group_search_page');
		}

		$count_group = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_group,C('config.appoint_page_row'),C('config.appoint_page_val'));
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

		foreach($group_list as &$v){
		    $v['avatar_path'] = str_replace(',', '/', $v['avatar_path']);
		}

		$return['pagebar'] = $count_group>0?$p->show():'';

		$return['group_list'] = $count_group>0?$group_list:'';
		$return['totalPage'] = $count_group>0?$p->totalPage:'';
		$return['meal_count'] = $count_group;
		$return['group_count'] = $count_group;
		$return['store_count'] = $count_group;
		return $return;
	}


	public function get_worker_list_by_keywords($keyword, $order, $is_wap=false){
	    $now_time = $_SERVER['REQUEST_TIME'];
		$condition_table = array(C('DB_PREFIX').'merchant_workers'=>'g');
		//排序相关
		switch($order){
			case 'price':
				$order = '`g`.`appoint_price` ASC,`g`.`merchant_worker_id` DESC';
				break;
			case 'priceDesc':
				$order = '`g`.`appoint_price` DESC,`g`.`appoint_id` DESC';
				break;
			case 'appointNum':
				$order = '`g`.`appoint_sum` DESC,`g`.`appoint_id` DESC';
				break;
			case 'hot':
			    $order = '`g`.`appoint_num` DESC';
			    break;
			default:
				$order = '`g`.`merchant_worker_id` DESC';
		}
		if($keyword){
			$condition_where .= " `g`.`name` like '%$keyword%' ";
		}
		if(empty($is_wap)){
			import('@.ORG.group_page');
		}else{
			import('@.ORG.wap_group_search_page');
		}

		$count_group = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_group,C('config.appoint_page_row'),C('config.appoint_page_val'));
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		foreach($group_list as &$v){
		    $v['avatar_path'] = str_replace(',', '/', $v['avatar_path']);
		    $v['url'] = U('Wap/Appoint/workerDetail',array('merchant_workers_id'=>$v['merchant_worker_id']));
		}


		$return['pagebar'] = $count_group>0?$p->show():'';

		$return['group_list'] = $count_group>0?$group_list:'';
		$return['totalPage'] = $count_group>0?$p->totalPage:'';
		$return['meal_count'] = $count_group;
		$return['group_count'] = $count_group;
		$return['store_count'] = $count_group;
		return $return;
	}


        public function merchant_worker_list($where,$field = true, $order='merchant_worker_id desc',$pageSize = 20){
            if(!$where){
                return false;
            }

            import('@.ORG.merchant_page');
            $count = $this->where($where)->count();
            $p = new Page($count,$pageSize,'page');

            $worker_list = $this->where($where)->field($field)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

			foreach($worker_list as &$worker){
				$worker['avatar_path'] = str_replace(',', '/', $worker['avatar_path']);
			}

            $list['list'] = $worker_list;
            $list['pagebar'] = $p->show();
            if($list){
                return array('status'=>1,'list'=>$list);
            }else{
                return array('status'=>0,'list'=>$list);
            }
        }


        public function get_appoint_worker_list($appoint_id, $uid = ''){
            if(!$appoint_id){
                return false;
            }

            $database_merchant_workers_appoint = D('Merchant_workers_appoint');
            $where['appoint_id'] = $appoint_id;

            $list = $database_merchant_workers_appoint->where($where)->getField('merchant_worker_id',true);
            if(!$list){
                return false;
            }

            $Map['merchant_worker_id'] = array('in',$list);
            $Map['status'] = 1;
            $worker_list = $this->where($Map)->select();
            if(!$worker_list){
                return false;
            }else{
                $reward_order = M('Reward_order');
				foreach($worker_list as &$worker){
					$worker['avatar_path'] = str_replace(',', '/', $worker['avatar_path']);
					$worker['desc'] = htmlspecialchars_decode($worker['desc']);
                    if ($uid) {
                        $order = $reward_order->field('order_id')->where(array('status' => 1, 'reward_id' => $worker['merchant_worker_id'], 'type' => 3, 'uid' => $uid))->find();
                        if ($order) $worker['is_reward'] = 1;
                    }
				}
                return $worker_list;
            }
        }

        public function merchant_worker_detail($where,$field = true){
            if(!$where){
                return false;
            }
            $detail = $this->where($where)->field($field)->find();
            if(!$detail){
                return array('status'=>0,'detail'=>$detail);
            }else{
				$detail['avatar_path'] = str_replace(',', '/', $detail['avatar_path']);
				$detail['desc'] = htmlspecialchars_decode($detail['desc']);
                return array('status'=>1,'detail'=>$detail);
            }
        }

}
?>
