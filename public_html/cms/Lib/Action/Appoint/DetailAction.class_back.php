<?php
/*
 * 团购内页
 *
 */
class DetailAction extends BaseAction{
    public function index(){
    	//右侧广告
//     	$index_right_adver = D('Adver')->get_adver_by_key('index_right',3);
//     	$this->assign('index_right_adver',$index_right_adver);
    	
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		//所有分类 包含2级分类
		$all_category_list = D('Appoint_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
		
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12,0);
    	$this->assign('search_hot_list',$search_hot_list);
		$now_group = D('Appoint')->get_appoint_by_appointId($_GET['appoint_id'],'hits-setInc');
		if(empty($now_group)){
			$this->group_noexit_tips('预约不存在。');
		}
		//商家的活动
		$lotterys = D("Lottery")->field(true)->where(array('token' => $now_group['mer_id'], 'statdate' => array('lt', time()), 'enddate' => array('gt', time())))->select();
		foreach ($lotterys as $lottery) {
			$index_right_adver[] = array('name' => $lottery['title'], 'pic' => $lottery['starpicurl'], 'url' => 'javascript:void(0);', 'id' => $lottery['id']);
		}
		$this->assign('index_right_adver',$index_right_adver);
		
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		
		if(!empty($this->user_session)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_detail';
			$condition_user_collect['id'] = $now_group['group_id'];
			$condition_user_collect['uid'] = $this->user_session['uid'];
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_group['is_collect'] = true;
			}
		}
		
		$this->assign('now_group',$now_group);
		if(!empty($now_category['cat_fid'])){
			$f_category = D('Appoint_category')->get_category_by_id($now_category['cat_fid']);
		}else{
			$f_category = D('Appoint_category')->get_category_by_id($now_group['cat_id']);
		}
		
		
		if(empty($f_category)){
			$this->group_noexit_tips('预约上级分类不存在。');
		}
		$this->assign('f_category',$f_category);
		
		$cat_id = intval($now_group['cat_id']);
		$now_group['cat_fid'] = intval($now_group['cat_fid']);
		$now_group['cat_id'] = intval($now_group['cat_id']);
		
		if( $now_group['cat_fid'] >0 && $now_group['cat_id']== 0){
			$cat_id = $now_group['cat_fid'];
		}
		
		$s_category = D('Appoint_category')->get_category_by_id($cat_id);
		if(empty($s_category)){
			$this->group_noexit_tips('预约分类不存在。');
		}
		$this->assign('s_category',$s_category);

//		if($now_group['packageid']>0){
//		   $packages=M('Group_packages')->where(array('id' => $now_group['packageid'], 'mer_id' => $now_group['mer_id']))->find();
//		   if(!empty($packages['groupidtext'])){
//		      $mpackages = unserialize($packages['groupidtext']);
//		   }else{
//		      $mpackages = false;
//		   }
//			$this->assign('mpackages',$mpackages);
//		}

		//商家所有预约列表
		$merchant_group_list = D('Appoint')->get_appointlist_by_MerchantId($now_group['mer_id'],3,true,$now_group['appoint_id']);
		$this->assign('merchant_group_list',$merchant_group_list);
		
		//猜您喜欢
		$like_group_list = D('Appoint')->get_appointlist_by_catId($now_group['cat_id'],$now_group['cat_fid'],5);
		foreach($like_group_list as $key=>$value){
			if($value['appoint_id'] == $now_group['appoint_id']){
				unset($like_group_list[$key]);
			}
		}
		$this->assign('like_group_list',$like_group_list);
		
		//分类下最热门团购，销售量
		$category_hot_group_list = D('Appoint')->get_appointlist_by_catId($now_group['cat_id'],$now_group['cat_fid'],4,false,'`appoint_sum` DESC');
		foreach($category_hot_group_list as $key=>$value){
			if($value['appoint_id'] == $now_group['appoint_id']){
				unset($category_hot_group_list[$key]);
			}
		}
		$this->assign('category_hot_group_list',$category_hot_group_list);
		
		//可选服务
		$product_condition['appoint_id'] = $_GET['appoint_id'];
		$appoint_product_list = D('Appoint_product')->field(true)->where($product_condition)->select();
		$this->assign('appoint_product_list', $appoint_product_list);
		
		$this->display();
    }
    
    public function order(){
    	//判断登录
    	// if(empty($this->user_session)){
    		// $this->assign('jumpUrl',U('Index/Login/index'));
    		// $this->error('请先登录！');
    	// }
	
    	$appoint_id = $_GET['appoint_id'];
    	//现在的团购
   		$now_group = D('Appoint')->get_appoint_by_appointId($appoint_id);
		if(empty($now_group)){
			$this->group_noexit_tips();
		}
		
		if($now_group['start_time'] > $_SERVER['REQUEST_TIME']){
			$this->error_tips('此预约活动还未开始！');
		}
		if($now_group['end_time'] <$_SERVER['REQUEST_TIME']){
			$this->error_tips('此预约活动已结束！');
		}
		// 产品列表
		$appointProduct = D('Appoint_product')->get_productlist_by_appointId($appoint_id);
		$this->assign('appoint_product',$appointProduct);
		// 预约开始时间 结束时间
		$office_time = unserialize($now_group['office_time']);
		
		// 如果设置的营业时间为0点到0点则默认是24小时营业
		if(count($office_time)<1){
			$office_time[0]['open'] = '00:00';
			$office_time[0]['close'] = '24:00';
		}else{
			foreach ($office_time as $i=>$time){
				if($time['open'] == '00:00' && $time['close'] == '00:00'){
					unset($office_time[$i]);
				}
			}
		}
		// 发起预约时候的起始时间 还有提前多长时间可预约
    $beforeTime = $now_group['before_time']>0?($now_group['before_time'])*3600:0;
		$gap = $now_group['time_gap']*60>0?$now_group['time_gap']*60:1800;
		
		foreach ($office_time as $i=>$time){
			$startTime = strtotime(date('Y-m-d').' '.$time['open']);
			$endTime   = strtotime(date('Y-m-d').' '.$time['close']);
			for($time = $startTime;$time<$endTime;$time=$time+$gap){
				$tempKey = date('H:i',$time).'-'.date('H:i',$time+$gap);
				$tempTime[$tempKey]['time'] = $tempKey;
				$tempTime[$tempKey]['start'] = date('H:i',$time);
				$tempTime[$tempKey]['end'] = date('H:i',$time+$gap);
				$tempTime[$tempKey]['order'] = 'no';
				if( ( date('H:i')> date('H:i',$time-$beforeTime) &&  date('H:i')<date('H:i',$time+$gap-$beforeTime) ) || ( date('H:i')<date('H:i',$time-$beforeTime) &&  date('H:i')<date('H:i',$time+$gap-$beforeTime) ) ){
					$tempTime[$tempKey]['order'] = 'yes';
				}
			}
		}
		
		$startTimeAppoint = $now_group['start_time']>strtotime('now')?$now_group['start_time']:strtotime('now');
		$endTimeAppoint   = $now_group['end_time']>strtotime('+3 day')?strtotime('+3 day'): $now_group['end_time'];
		
		$dateArray[date('Y-m-d',$startTimeAppoint)] = date('Y-m-d',$startTimeAppoint);
		$dateArray[date('Y-m-d',$endTimeAppoint)] = date('Y-m-d',$endTimeAppoint);
		for($date=$startTimeAppoint;$date<$endTimeAppoint;$date=$date+86400){
			$dateArray[date('Y-m-d',$date)] = date('Y-m-d',$date);
		}
		ksort($dateArray);ksort($dateArray);
		foreach ($dateArray as $i=>$date){
			$timeOrder[$date] = $tempTime;
		}
                
		ksort($timeOrder);
		foreach($timeOrder as $i=>$tem){
			foreach ($tem as $key=>$temval)
				if(strtotime($i.' '.$temval['end'])<strtotime('now')+$beforeTime && ($temval['order'] == 'yes')){
					$timeOrder[$i][$key]['order'] = 'no';
			    }elseif(strtotime($i.' '.$temval['end'])>strtotime('now')+$beforeTime && ($temval['order'] == 'no')){
					$timeOrder[$i][$key]['order'] = 'yes';
			    }
		}
		
		// 查询可预约时间点
		//$appoint_num = D('Appoint_order')->get_appoint_num($now_group['appoint_id'],$now_group['appoint_people']);
		$appoint_num = D('Appoint_order')->get_appoint_num($now_group['appoint_id'] , 1);
		if(count($appoint_num) > 0){
			foreach ($appoint_num as $val){
				$key = date('Y-m-d',strtotime($val['appoint_date']));
				if($timeOrder[$key][$val['appoint_time']]['order'] != 'no'){
					if(isset($timeOrder[$key]) && $timeOrder[$key]['time'] == $val['appoint_num']){
						$timeOrder[$key][$val['appoint_time']]['order'] = 'all';
					}
				}
			}
		}
                
		$this->assign('timeOrder',$timeOrder);
		// 自定义表单项
		$category = D('Appoint_category')->get_category_by_id($now_group['cat_id']);
		if(empty($category['cue_field'])){
			$category = D('Appoint_category')->get_category_by_id($category['cat_fid']);
		}
		if($category){
			$cuefield = unserialize($category['cue_field']);
			foreach ($cuefield as $val){
				$sort[] = $val['sort'];
			}
			array_multisort($sort, SORT_DESC, $cuefield);
		}
		$this->assign('formData',$cuefield);
		
    	if(IS_POST){
    		$now_group['product_id'] = $_POST['service_type'];
                $now_group['cue_field'] = serialize($_POST['custom_field']);
                $now_group['appoint_date'] = $_POST['service_date'];
                $now_group['appoint_time'] = $_POST['service_time'];
                $now_group['store_id'] = $_POST['store_id']?$_POST['store_id']:0;
                $merchant_workers_id = $_POST['merchant_workers_id'] + 0;
                $result = D('Appoint_order')->save_post_form($now_group,$this->user_session['uid'],0,$merchant_workers_id);
                if($result['error'] == 1){
                        $this->error($result['msg']);
                }

                // 如果需要定金
                if(intval($now_group['payment_status']) == 1){
                        $href = U('Index/Pay/check',array('order_id'=>$result['order_id'],'type'=>'appoint'));
                }else{
                        $resultOrder = D('Appoint_order')->no_pay_after($result['order_id'],$now_group);
                        if($resultOrder['error'] == 1){
                                $this->error($resultOrder['msg']);
                        }
                        $href = U('User/Index/appoint_order_view',array('order_id'=>$result['order_id']));
                }
                $this->success($href);
    	}else{
	    	$pigcms_get = $this->get_uri_param();

		$this->assign('now_group',$now_group);

		$database_merchant_workers = D('Merchant_workers');
		$database_merchant_workers_appoint = D('Merchant_workers_appoint');

		$where['appoint_id'] = $now_group['appoint_id'];
		$merchant_workers_appoint_list = $database_merchant_workers_appoint->where($where)->getField('id,merchant_worker_id');

		if($merchant_workers_appoint_list){
		    $Map['merchant_worker_id']=array('in',$merchant_workers_appoint_list);
		    $worker_list = $database_merchant_workers->where($Map)->select();
		    $this->assign('worker_list',$worker_list);
		}
		
		
	    	
	    	if($pigcms_get['q'] < $now_group['once_min']){
	    		$pigcms_assign['num'] = $now_group['once_min'];
	    	}else{
	    		$pigcms_assign['num'] = $pigcms_get['q'];
	    	}
	    	$pigcms_assign['total_price'] = $pigcms_assign['num']*$now_group['price'];

	    	$pigcms_assign['pigcms_phone'] = substr($this->user_session['phone'],0,3).'****'.substr($this->user_session['phone'],7);
	    	$pigcms_assign['total_off_price'] = $finalprice > 0 ? round(($pigcms_assign['num']*$finalprice),2) : $pigcms_assign['total_price'];
			is_array($level_off) && $level_off['price']=round($finalprice,2);
			$this->assign('leveloff',$level_off);
			$this->assign('finalprice',$finalprice);
	    	$this->assign($pigcms_assign);
	    	//导航条
	    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
	    	$this->assign('web_index_slider',$web_index_slider);
	    	 
			//热门搜索词
			$search_hot_list = D('Search_hot')->get_list(12,0);
			$this->assign('search_hot_list',$search_hot_list);
			
	    	//所有分类 包含2级分类
	    	$all_category_list = D('Group_category')->get_category();
	    	$this->assign('all_category_list',$all_category_list);
	    	$this->display();
    	}
    }
    
public function ajaxAppointTime(){
            $appoint_id = $_POST['appoint_id'] + 0;
            $now_group = D('Appoint')->get_appoint_by_appointId($appoint_id,'hits-setInc');
            $office_time = unserialize($now_group['office_time']);
		
		// 如果设置的营业时间为0点到0点则默认是24小时营业
		if(count($office_time)<1){
			$office_time[0]['open'] = '00:00';
			$office_time[0]['close'] = '24:00';
		}else{
			foreach ($office_time as $i=>$time){
				if($time['open'] == '00:00' && $time['close'] == '00:00'){
					unset($office_time[$i]);
				}
			}
		}
		// 发起预约时候的起始时间 还有提前多长时间可预约
		$beforeTime = $now_group['before_time']>0?($now_group['before_time'])*3600:0;
		$gap = $now_group['time_gap']*60>0?$now_group['time_gap']*60:1800;
		
		foreach ($office_time as $i=>$time){
			$startTime = strtotime(date('Y-m-d').' '.$time['open']);
			$endTime   = strtotime(date('Y-m-d').' '.$time['close']);
			for($time = $startTime;$time<$endTime;$time=$time+$gap){
				$tempKey = date('H:i',$time).'-'.date('H:i',$time+$gap);
				$tempTime[$tempKey]['time'] = $tempKey;
				$tempTime[$tempKey]['start'] = date('H:i',$time);
				$tempTime[$tempKey]['end'] = date('H:i',$time+$gap);
				$tempTime[$tempKey]['order'] = 'no';
				if( ( date('H:i')> date('H:i',$time-$beforeTime) &&  date('H:i')<date('H:i',$time+$gap-$beforeTime) ) || ( date('H:i')<date('H:i',$time-$beforeTime) &&  date('H:i')<date('H:i',$time+$gap-$beforeTime) ) ){
					$tempTime[$tempKey]['order'] = 'yes';
				}
			}
		}
		
		$startTimeAppoint = $now_group['start_time']>strtotime('now')?$now_group['start_time']:strtotime('now');
		$endTimeAppoint   = $now_group['end_time']>strtotime('+3 day')?strtotime('+3 day'): $now_group['end_time'];
		
		$dateArray[date('Y-m-d',$startTimeAppoint)] = date('Y-m-d',$startTimeAppoint);
		$dateArray[date('Y-m-d',$endTimeAppoint)] = date('Y-m-d',$endTimeAppoint);
		for($date=$startTimeAppoint;$date<$endTimeAppoint;$date=$date+86400){
			$dateArray[date('Y-m-d',$date)] = date('Y-m-d',$date);
		}
		ksort($dateArray);ksort($dateArray);
		foreach ($dateArray as $i=>$date){
			$timeOrder[$date] = $tempTime;
		}
		ksort($timeOrder);
		foreach($timeOrder as $i=>$tem){
			foreach ($tem as $key=>$temval)
				if(strtotime($i.' '.$temval['end'])<strtotime('now')+$beforeTime && ($temval['order'] == 'yes')){
					$timeOrder[$i][$key]['order'] = 'no';
			    }elseif(strtotime($i.' '.$temval['end'])>strtotime('now')+$beforeTime && ($temval['order'] == 'no')){
					$timeOrder[$i][$key]['order'] = 'yes';
			    }
		}
		
		// 查询可预约时间点
		//$appoint_num = D('Appoint_order')->get_appoint_num($now_group['appoint_id'],$now_group['appoint_people']);
		$appoint_num = D('Appoint_order')->get_appoint_num($now_group['appoint_id'] , 1);
		if(count($appoint_num)>0){
			foreach ($appoint_num as $val){
				$key = date('Y-m-d',strtotime($val['appoint_date']));
				if($timeOrder[$key][$val['appoint_time']]['order'] != 'no'){
					if(isset($timeOrder[$key]) && $timeOrder[$key]['time'] == $val['appoint_num']){
						$timeOrder[$key][$val['appoint_time']]['order'] = 'all';
					}
				}
			}
		}
            exit(json_encode(array('status'=>1,'timeOrder'=>$timeOrder)));
        }
    
    
    public function ajaxWorkerTime(){
	    $database_merchant_workers = D('Merchant_workers');
	    $database_merchant_workers_appoint=D('Merchant_workers_appoint');
	    
	    $worker_id = $_POST['worker_id'] + 0;
	    if(!$worker_id){
		exit(json_encode(array('status'=>0)));
	    }
	    
	    // 预约开始时间 结束时间
		$merchant_workers_info = $database_merchant_workers->where(array('merchant_worker_id'=>$worker_id))->find();
		$office_time = unserialize($merchant_workers_info['office_time']);
		
		// 如果设置的营业时间为0点到0点则默认是24小时营业
		if(count($office_time)<1){
			$office_time[0]['open'] = '00:00';
			$office_time[0]['close'] = '24:00';
		}else{
			foreach ($office_time as $i=>$time){
				if($time['open'] == '00:00' && $time['close'] == '00:00'){
					unset($office_time[$i]);
				}
			}
		}
		// 发起预约时候的起始时间 还有提前多长时间可预约
		$beforeTime = $merchant_workers_info['before_time']>0?($merchant_workers_info['before_time'])*3600:0;
		$gap = $merchant_workers_info['time_gap']*60>0?$merchant_workers_info['time_gap']*60:1800;
		
		foreach ($office_time as $i=>$time){
		    $startTime = strtotime(date('Y-m-d').' '.$time['open']);
		    $endTime   = strtotime(date('Y-m-d').' '.$time['close']);
		    for($time = $startTime;$time<$endTime;$time=$time+$gap){
				$tempKey = date('H:i',$time).'-'.date('H:i',$time+$gap);
				$tempTime[$tempKey]['time'] = $tempKey;
				$tempTime[$tempKey]['start'] = date('H:i',$time);
				$tempTime[$tempKey]['end'] = date('H:i',$time+$gap);
				$tempTime[$tempKey]['order'] = 'no';
				if( ( date('H:i')> date('H:i',$time-$beforeTime) &&  date('H:i')<date('H:i',$time+$gap-$beforeTime) ) || ( date('H:i')<date('H:i',$time-$beforeTime) &&  date('H:i')<date('H:i',$time+$gap-$beforeTime) ) ){
					$tempTime[$tempKey]['order'] = 'yes';
				}
			}
		}
		
		$appoint_id = $_POST['appoint_id'] + 0;
		$now_group = D('Appoint')->get_appoint_by_appointId($appoint_id,'hits-setInc');
		$startTimeAppoint = $now_group['start_time']>strtotime('now')?$now_group['start_time']:strtotime('now');
		$endTimeAppoint   = $now_group['end_time']>strtotime('+3 day')?strtotime('+3 day'): $now_group['end_time'];
		$dateArray[date('Y-m-d',$startTimeAppoint)] = date('Y-m-d',$startTimeAppoint);
		$dateArray[date('Y-m-d',$endTimeAppoint)] = date('Y-m-d',$endTimeAppoint);
		for($date=$startTimeAppoint;$date<$endTimeAppoint;$date=$date+86400){
			$dateArray[date('Y-m-d',$date)] = date('Y-m-d',$date);
		}
		ksort($dateArray);
		foreach ($dateArray as $i=>$date){
			$timeOrder[$date] = $tempTime;
		}
		ksort($timeOrder);
		foreach($timeOrder as $i=>$tem){
			foreach ($tem as $key=>$temval)
				if(strtotime($i.' '.$temval['end'])<strtotime('now')+$beforeTime && ($temval['order'] == 'yes')){
					$timeOrder[$i][$key]['order'] = 'no';
			    }elseif(strtotime($i.' '.$temval['end'])>strtotime('now')+$beforeTime && ($temval['order'] == 'no')){
					$timeOrder[$i][$key]['order'] = 'yes';
			    }
		}
		// 查询可预约时间点
		$appoint_num = D('Appoint_order')->get_worker_appoint_num($now_group['appoint_id'],$worker_id);
		if(count($appoint_num)>0){
			foreach ($appoint_num as $val){
				$key = date('Y-m-d',strtotime($val['appoint_date']));
				if($timeOrder[$key][$val['appoint_time']]['order'] != 'no'){
					//if(isset($timeOrder[$key]) && ($merchant_workers_info['appoint_people'] == $val['appointNum'])){
					if(isset($timeOrder[$key]) && (1 == $val['appointNum'])){
						$timeOrder[$key][$val['appoint_time']]['order'] = 'all';
					}
				}
			}
		}
		exit(json_encode(array('timeOrder'=>$timeOrder,'status'=>1)));
	}
        
        
        public function ajaxWorker(){
	    $database_merchant_workers = D('Merchant_workers');
	    $database_merchant_workers_appoint = D('Merchant_workers_appoint');

	    $merchant_store_id = $_POST['merchant_store_id'] + 0;
	    $appoint_id = $_POST['appoint_id'] + 0;
	    $merchant_workers_id=$_POST['merchant_workers_id'];

	    $where['merchant_store_id'] = $merchant_store_id;
	    $where['appoint_id'] = $appoint_id;
	    $merchant_workers_appoint_list = $database_merchant_workers_appoint->where($where)->getField('id,merchant_worker_id');
	    
	    if($merchant_workers_appoint_list){
		$Map['merchant_worker_id']=array('in',$merchant_workers_appoint_list);
		$worker_list = $database_merchant_workers->where($Map)->select();

		exit(json_encode(array('status'=>1,'worker_list'=>$worker_list)));
	    }else{
		exit(json_encode(array('status'=>0)));
	    }
	    
	}
	public function group_noexit_tips($fix){
		$this->assign('jumpUrl',$this->config['site_url']);
		$this->error('您不能查看该商品！'.$fix);
	}
}