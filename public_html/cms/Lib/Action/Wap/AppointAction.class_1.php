<?php
/**
 * 
 * 预约服务
 */
class AppointAction extends BaseAction{
    public function _initialize() {
        parent::_initialize();
        $this->database_reply = D('Reply');
        $this->database_appoint_comment = D('Appoint_comment');
        $this->database_merchant_workers = D('Merchant_workers');
        $this->user_collect = D('User_collect');
    }
	
	/*public function index(){
		//判断分类信息
		$cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
		$this->assign('now_cat_url', $cat_url);

		//判断地区信息
		$area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
		$this->assign('now_area_url',$area_url);

		$circle_id = 0;
		if(!empty($area_url)){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error('当前区域不存在！');
			}
			$this->assign('now_area', $tmp_area);

			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$this->assign('now_circle', $now_circle);
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->error('当前区域不存在！');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$this->assign('top_area', $now_area);
			$area_id = $now_area['area_id'];
		}else{
			$area_id = 0;
		}

		//判断排序信息   默认排序就是按照手动设置项排序
		$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';

		$long_lat = array('lat' => 0, 'long' => 0);
		$_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if (empty($long_lat['long']) || empty($long_lat['lat'])) {
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'默认排序'),
					array('sort_id'=>'appointNum','sort_value'=>'按预约数'),
					array('sort_id'=>'start','sort_value'=>'最新发布'),
					array('sort_id'=>'price','sort_value'=>'价格最低'),
					array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
			);
		} else {
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
			$long_lat['lat'] = $location2['lat'];
			$long_lat['long'] = $location2['lng'];
			$sort_array = array(
					array('sort_id'=>'juli','sort_value'=>'离我最近'),
					array('sort_id'=>'appointNum','sort_value'=>'按预约数'),
					array('sort_id'=>'start','sort_value'=>'最新发布'),
					array('sort_id'=>'price','sort_value'=>'价格最低'),
					array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
			);
		}
		foreach($sort_array as $key=>$value){
			if($sort_id == $value['sort_id']){
				$now_sort_array = $value;
				break;
			}
		}
		$this->assign('sort_array',$sort_array);
		$this->assign('now_sort_array',$now_sort_array);


		//所有分类 包含2级分类
		$all_category_list = D('Appoint_category')->get_all_category();
		$this->assign('all_category_list',$all_category_list);

		//根据分类信息获取分类
		if(!empty($cat_url)){
			$now_category = D('Appoint_category')->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);

			if(!empty($now_category['cat_fid'])){
				$f_category = D('Appoint_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
				$category_cat_field = $f_category['cat_field'];

				$top_category = $f_category;
				$this->assign('top_category',$f_category);

				$get_grouplist_catfid = 0;
				$get_grouplist_catid = $now_category['cat_id'];
			}else{
				$all_category_url = $now_category['cat_url'];
				$category_cat_field = $now_category['cat_field'];
				$top_category = $now_category;
				$this->assign('top_category',$now_category);

				$get_grouplist_catfid = $now_category['cat_id'];
				$get_grouplist_catid = 0;
			}
		}else{
			//所有区域
		}
		$all_area_list = D('Area')->get_all_area_list();
		$this->assign('all_area_list',$all_area_list);
		$this->assign(D('Appoint')->wap_get_appoint_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id));
		$this->display();
	}*/
    
	public function index(){
	    $database_adver = D('Adver');
	    $database_category = D('Appoint_category');
	    //判断分类信息
	    $cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
	    $this->assign('now_cat_url', $cat_url);

	    //判断地区信息
	    $area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
	    $this->assign('now_area_url',$area_url);
	    
	    $circle_id = 0;
	    if(!empty($area_url)){
		    $tmp_area = D('Area')->get_area_by_areaUrl($area_url);
		    if(empty($tmp_area)){
			    $this->error_tips('当前区域不存在！');
		    }
		    $this->assign('now_area', $tmp_area);

		    if ($tmp_area['area_type'] == 3) {
			    $now_area = $tmp_area;
		    } else {
			    $now_circle = $tmp_area;
			    $this->assign('now_circle', $now_circle);
			    $now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
			    if (empty($tmp_area)) {
				    $this->error_tips('当前区域不存在！');
			    }
			    $circle_url = $now_circle['area_url'];
			    $circle_id = $now_circle['area_id'];
			    $area_url = $now_area['area_url'];
		    }
		    $this->assign('top_area', $now_area);
		    $area_id = $now_area['area_id'];
	    }else{
		    $area_id = 0;
	    }
	    
	    $header_adver = $database_adver->get_adver_by_key('appoint_index_top');
	    $condition_group_category['cat_status'] = 1;
	    $condition_group_category['cat_fid'] = 0;
	    $f_category_list = $database_category->field(true)->where($condition_group_category)->order('is_hot desc,cat_id asc')->select();
	    $condition_group_category['cat_fid'] = array('neq',0);
	    $s_category_list = $database_category->field(true)->where($condition_group_category)->select();
	    foreach($f_category_list as $fk=>$fv){
		foreach($s_category_list as $sv){
		    if($fv['cat_id']==$sv['cat_fid']){
			$f_category_list[$fk]['son_list'][]=$sv;
		    }
		}
	    }
	    $this->assign('header_adver',$header_adver);
	    $this->assign('category_list',$f_category_list);
	    $this->display();
	}
	
	public function categoryList(){
	    $database_category = D('Appoint_category');
	    $cat_id = $this->_get('cat_id');
	    if(!$cat_id){
		$this->error_tips('传递参数有误！');
	    }
	    
	    $where['cat_fid']=$cat_id;
	    $cate_list = $database_category->where($where)->select();
	    
	    if(!$cate_list){
		$this->error_tips('请先添加子分类！');
	    }
	    $cate_list['cate_list'] = $cate_list;
	    $cate_list['cate_num'] = count($cate_list['cate_list']);
	    $this->assign('cate_list',$cate_list);
	    $this->display();
	}
	
	public function productList(){
	    //判断分类信息
		$cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
		$this->assign('now_cat_url', $cat_url);
		
		//判断地区信息
		$area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
		$this->assign('now_area_url',$area_url);
		
		$circle_id = 0;
		if(!empty($area_url)){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error_tips('当前区域不存在！');
			}
			$this->assign('now_area', $tmp_area);
			
			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$this->assign('now_circle', $now_circle);
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->error_tips('当前区域不存在！');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$this->assign('top_area', $now_area);
			$area_id = $now_area['area_id'];
		}else{
			$area_id = 0;
		}
		
		//判断排序信息   默认排序就是按照手动设置项排序
		$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'defaults';

		$long_lat = array('lat' => 0, 'long' => 0);
		$_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if (empty($long_lat['long']) || empty($long_lat['lat'])) {
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'综合排序'),
					array('sort_id'=>'price','sort_value'=>'价格排序'),
					array('sort_id'=>'appointNum','sort_value'=>'预约数排序'),
			);
		} else {
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
			$long_lat['lat'] = $location2['lat'];
			$long_lat['long'] = $location2['lng'];
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'综合排序'),
					array('sort_id'=>'price','sort_value'=>'价格排序'),
					array('sort_id'=>'appointNum','sort_value'=>'预约数排序'),
			);
		}
		foreach($sort_array as $key=>$value){
			if($sort_id == $value['sort_id']){
				$now_sort_array = $value;
				break;
			}
		}
		$this->assign('sort_array',$sort_array);
		$this->assign('now_sort_array',$now_sort_array);
		
		
		//所有分类 包含2级分类
		$all_category_list = D('Appoint_category')->get_all_category();
		$this->assign('all_category_list',$all_category_list);
		
		//根据分类信息获取分类
		if(!empty($cat_url)){
			$now_category = D('Appoint_category')->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);
			
			if(!empty($now_category['cat_fid'])){
				$f_category = D('Appoint_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
				$category_cat_field = $f_category['cat_field'];
			
				$top_category = $f_category;
				$this->assign('top_category',$f_category);
			
				$get_grouplist_catfid = 0;
				$get_grouplist_catid = $now_category['cat_id'];
			}else{
				$all_category_url = $now_category['cat_url'];
				$category_cat_field = $now_category['cat_field'];
				$top_category = $now_category;
				$this->assign('top_category',$now_category);
			
				$get_grouplist_catfid = $now_category['cat_id'];
				$get_grouplist_catid = 0;
			}
		}else{
			//所有区域
		}
		$all_area_list = D('Area')->get_all_area_list();
		$this->assign('all_area_list',$all_area_list);
		$product_list = D('Appoint')->wap_get_appoint_list_by_catid($this->_get('cat_id'),$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
		foreach($product_list['group_list'] as $k=>$v){
		    $product_list['group_list'][$k]['pic']=  str_replace(',', '/', $v['pic']);
		}
		
		$this->assign('product_list',$product_list);
		$this->display();
	}
	
	//手艺人列表
	public function workerList(){
		$database_merchant_workers=D('Merchant_workers');
		
		//判断排序信息   默认排序就是按照手动设置项排序
		$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'defaults';

		$long_lat = array('lat' => 0, 'long' => 0);
		$_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if (empty($long_lat['long']) || empty($long_lat['lat'])) {
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'综合排序'),
					array('sort_id'=>'price','sort_value'=>'均价排序'),
					array('sort_id'=>'appointNum','sort_value'=>'单数排序'),
					array('sort_id'=>'all_avg_score','sort_value'=>'好评排序'),
			);
		} else {
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
			$long_lat['lat'] = $location2['lat'];
			$long_lat['long'] = $location2['lng'];
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'综合排序'),
					array('sort_id'=>'price','sort_value'=>'均价排序'),
					array('sort_id'=>'appointNum','sort_value'=>'单数排序'),
					array('sort_id'=>'all_avg_score','sort_value'=>'好评排序'),
			);
		}
		foreach($sort_array as $key=>$value){
			if($sort_id == $value['sort_id']){
				$now_sort_array = $value;
				break;
			}
		}
		$this->assign('sort_array',$sort_array);
		$this->assign('now_sort_array',$now_sort_array);
		$merchant_workers_list = $database_merchant_workers->wap_merchant_worker_list($sort_id);
		$this->assign('merchant_workers_list',$merchant_workers_list);
		$this->display();
	}
	/*public function appointList(){
		$this->header_json();
		$store_type = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';;
		$area_url = isset($_GET['area_url']) && $_GET['area_url']? htmlspecialchars($_GET['area_url']) : 'all';
		$cat_url = isset($_GET['cat_url']) && $_GET['cat_url']? htmlspecialchars($_GET['cat_url']) : 'all';

		$circle_id = 0;
		if($area_url != 'all'){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error('当前区域不存在！');
			}
			$this->assign('now_area', $tmp_area);

			if($tmp_area['area_type'] == 3){
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$this->assign('now_circle', $now_circle);
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->error('当前区域不存在！');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$area_id = $now_area['area_id'];
		}else{
			$area_id = 0;
		}
		$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if(empty($long_lat)){
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
		}
		$cat_id = 0;
		if($cat_url != 'all'){
			$now_category = D('Appoint_category')->get_category_by_catUrl($cat_url);
			if (empty($now_category)) {
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);

			if (!empty($now_category['cat_fid'])) {
				$f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];

				$this->assign('top_category',$f_category);

				$cat_fid = $now_category['cat_fid'];
				$cat_id = $now_category['cat_id'];
			} else {
				$this->assign('top_category',$now_category);

				$cat_id = 0;
				$cat_fid = $now_category['cat_id'];
			}
		}
// 		$store_type = $store_type == 2 ? 2 : array(0, 1);

		$appointList = D('Appoint')->wap_get_appoint_list_by_catid($cat_id,$cat_fid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
		foreach ($appointList['group_list'] as $k=>$appoint){
			if(isset($appoint['juli'])){
				$appointList['group_list'][$k]['juli'] = getRange($appoint['juli']);
			}
		}
		print_r($appointList);
	}*/

	public function ajaxList(){
		$this->header_json();
		$store_type = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';;
		$area_url = isset($_GET['area_url']) && $_GET['area_url']? htmlspecialchars($_GET['area_url']) : 'all';
		$cat_url = isset($_GET['cat_url']) && $_GET['cat_url']? htmlspecialchars($_GET['cat_url']) : 'all';
	
		$circle_id = 0;
		if($area_url != 'all'){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error_tips('当前区域不存在！');
			}
			$this->assign('now_area', $tmp_area);
	
			if($tmp_area['area_type'] == 3){
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$this->assign('now_circle', $now_circle);
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->error_tips('当前区域不存在！');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$area_id = $now_area['area_id'];
		}else{
			$area_id = 0;
		}
		$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if(empty($long_lat)){
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
		}
		$cat_id = 0;
		if($cat_url != 'all'){
			$now_category = D('Appoint_category')->get_category_by_catUrl($cat_url);
			if (empty($now_category)) {
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);
				
			if (!empty($now_category['cat_fid'])) {
				$f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
	
				$this->assign('top_category',$f_category);
					
				$cat_fid = $now_category['cat_fid'];
				$cat_id = $now_category['cat_id'];
			} else {
				$this->assign('top_category',$now_category);
					
				$cat_id = 0;
				$cat_fid = $now_category['cat_id'];
			}
		}

		$appointList = D('Appoint')->wap_get_appoint_list_by_catid($cat_id,$cat_fid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
		foreach ($appointList['group_list'] as $k=>$appoint){
			if(isset($appoint['juli'])){
				$appointList['group_list'][$k]['juli'] = getRange($appoint['juli']);
			}
		}
		echo json_encode($appointList);
	}
	
	public function detail(){
	    $data_appoint_custom_field = D('Appoint_custom_field');
	    $data_user_collect = D('User_collect');
	    $database_appoint_order = D('Appoint_order');
	    if(empty($_GET['appoint_id'])){
		    $this->error_tips('当前预约项不存在！');
	    }   
	    
	    $appoint_id = $this->_get('appoint_id');
	    $now_group = D('Appoint')->get_appoint_by_appointId($appoint_id,'hits-setInc');
	    if(empty($now_group)){
		    $this->error_tips('当前预约项不存在！');
	    }
	    //计算本月销量
	     //统计月销量
	    $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
	    $EndDate = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
	    $where['order_time'] = array(array('egt',strtotime($BeginDate)),array('lt',strtotime($EndDate)));
	    $where['appoint_id'] = $appoint_id;
	    $now_month_sales = $database_appoint_order->where($where)->count();
	    $now_group['now_month_sales'] = $now_month_sales;
	    $merchant_workers_info['now_month_sales'] = $now_month_sales;
	    
	    if(count($now_group['store_list'])==1){
		$now_group['tel'] = $now_group['store_list'][0]['phone'];
	    }else{
		$database_merchant = D('Merchant');
		$merchant_where['mer_id'] = $now_group['mer_id'];
		$now_group['tel'] = $database_merchant->where($merchant_where)->getField('phone');
	    }
            
            $appoint_reply_list = $this->database_reply->get_appointReply_list($now_group['appoint_id']);
            $now_group['reply_num'] = count($appoint_reply_list);
	    $this->assign('now_group',$now_group);
	    /*if($now_group['reply_count']){
		    $reply_list = D('Reply')->get_appointReply_list($now_group['appoint_id'], 2, 0, 3);
		    $this->assign('reply_list',$reply_list);
	    }*/
            

	    if(!empty($_GET['store_id'])){
		    $this->assign('store_id',$_GET['store_id']);
	    }
	    $merchant_group_list = D('Appoint')->get_appointlist_by_MerchantId($now_group['mer_id'],3,true,$now_group['appoint_id']);
	    $this->assign('merchant_group_list',$merchant_group_list);
	    $product_condition['appoint_id'] = $_GET['appoint_id'];
	    $appoint_product_list = D('Appoint_product')->field(true)->where($product_condition)->select();

	    // 粉丝行为分析 
	    D('Merchant_request')->add_request($now_group['mer_id'],array('appoint_hits'=>1));

	    $this->assign('appoint_product_list', $appoint_product_list);

	    if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_group['mer_id']))->select()) {
		    $key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
		    $kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.meihua.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_group['mer_id'];
		    $this->assign('kf_url', $kf_url);
	    }

	    //自定义表单
	    $appoint_custom_field_list = $data_appoint_custom_field->where(array('appoint_id'=>$_GET['appoint_id']))->order('appoint_custom_field_sort asc')->select();
	    $this->assign('appoint_custom_field_list',$appoint_custom_field_list);
	    
	    $collect_where['type'] = 'appoint_detail';
	    $collect_where['id'] = $_GET['appoint_id'];
	    $collect_where['uid'] = $_SESSION['user']['uid'];
	    $collect_num = $data_user_collect->where($collect_where)->count();
	    $this->assign('collect_num',$collect_num);
	    $this->display();
	}
	
	public function search(){
	    $this->display();
	}
	
	public function search_result(){
	    $keyword = $this->_get('keyword');
	    $database_merchant_workers = D('Merchant_workers');
	    
	    //判断排序信息   默认排序就是按照手动设置项排序
	    $sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'defaults';
	    
	    $long_lat = array('lat' => 0, 'long' => 0);
	    $_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
	    if (empty($long_lat['long']) || empty($long_lat['lat'])) {
		    $sort_id = $sort_id == '' ? 'defaults' : $sort_id;
		    $sort_array = array(
				    array('sort_id'=>'defaults','sort_value'=>'综合排序'),
					array('sort_id'=>'price','sort_value'=>'均价排序'),
					array('sort_id'=>'appointNum','sort_value'=>'单数排序'),
					array('sort_id'=>'all_avg_score','sort_value'=>'好评排序'),
		    );
	    } else {
		    import('@.ORG.longlat');
		    $longlat_class = new longlat();
		    $location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
		    $long_lat['lat'] = $location2['lat'];
		    $long_lat['long'] = $location2['lng'];
		    $sort_array = array(
				    array('sort_id'=>'defaults','sort_value'=>'综合排序'),
					array('sort_id'=>'price','sort_value'=>'均价排序'),
					array('sort_id'=>'appointNum','sort_value'=>'单数排序'),
					array('sort_id'=>'all_avg_score','sort_value'=>'好评排序'),
		    );
	    }
	    foreach($sort_array as $key=>$value){
		    if($sort_id == $value['sort_id']){
			    $now_sort_array = $value;
			    break;
		    }
	    }
	    $this->assign('sort_array',$sort_array);
	    
	    $where['name|mobile'] = array('like','%'.$keyword.'%');
	    $merchant_workers_list = $database_merchant_workers->wap_merchant_worker_list($sort_id,$where);
	    $this->assign( 'merchant_workers_list',$merchant_workers_list);
	
	    $this->assign('now_sort_array',$now_sort_array);
	    $this->display();
	}
	
	
	//工作人员评论列表
	public function worker_comment_list(){
	    $database_appoint_comment = D('Appoint_comment');
	    $merchant_worker_id =$_GET['merchant_worker_id'] + 0;
	    if( !$merchant_worker_id){
		$this->error_tips('传递参数有误！');
	    }
	    $where['merchant_worker_id'] = $merchant_worker_id;
	    $where['status'] = 1;
	    
	    $appoint_comment_list = $this->database_appoint_comment->appoint_comment_list($where);
	    $where['profession_score'] =array('egt',4.8);
	    $where['communicate_score'] =array('egt',4.8);
	    $where['speed_score'] =array('egt',4.8);
	    $perfect_appoint_comment_list = $this->database_appoint_comment->appoint_comment_list($where);
	    $where['profession_score'] = array(array('egt',4.5),array('lt',4.8));
	    $where['communicate_score'] =array(array('egt',4.5),array('lt',4.8));
	    $where['speed_score'] =array(array('egt',4.5),array('elt',4.8));
	    $great_appoint_comment_list = $this->database_appoint_comment->appoint_comment_list($where);
	    $where['profession_score'] =array(array('egt',4),array('lt',4.5));
	    $where['communicate_score'] =array(array('egt',4),array('lt',4.5));
	    $where['speed_score'] =array(array('egt',4),array('lt',4.5));
	    $general_appoint_comment_list = $this->database_appoint_comment->appoint_comment_list($where);
	    $where['profession_score'] =array('lt',4);
	    $where['communicate_score'] =array('lt',4);
	    $where['speed_score'] =array('lt',4);
	    $bad_appoint_comment_list = $this->database_appoint_comment->appoint_comment_list($where);
	    if(!$appoint_comment_list){
		$this->error_tips('暂无评论！');
	    }
	   $this->assign('appoint_comment_list',$appoint_comment_list);
	   $this->assign('perfect_appoint_comment_list',$perfect_appoint_comment_list);
	   $this->assign('great_appoint_comment_list',$great_appoint_comment_list);
	   $this->assign('general_appoint_comment_list',$general_appoint_comment_list);
	   $this->assign('bad_appoint_comment_list',$bad_appoint_comment_list);
	   $this->display();
	}
	
	
	//项目评论列表
	public function appoint_comment(){
	    $database_reply = D('Reply');
	    $appoint_id = $_GET['appoint_id'] + 0;
	    if(!$appoint_id && !$merchant_worker_id){
		$this->error_tips('传递参数有误！');
	    }
	    
	    $appoint_comment_list = $database_reply->get_appointReply_list($appoint_id);
	    
	    $where['score'] =array('egt',4.8);
	    $perfect_appoint_comment_list = $database_reply->get_appointReply_list($appoint_id,$where);
	    $where['score'] = array(array('egt',4.5),array('lt',4.8));
	    $great_appoint_comment_list  = $database_reply->get_appointReply_list($appoint_id,$where);
	    $where['score'] =array(array('egt',4),array('lt',4.5));
	    $general_appoint_comment_list  = $database_reply->get_appointReply_list($appoint_id,$where);
	    $where['score'] =array('lt',4);
	    $bad_appoint_comment_list  = $database_reply->get_appointReply_list($appoint_id,$where);
	    if(!$appoint_comment_list){
		$this->error_tips('暂无评论！');
	    }
	   $this->assign('appoint_comment_list',$appoint_comment_list);
	   $this->assign('perfect_appoint_comment_list',$perfect_appoint_comment_list);
	   $this->assign('great_appoint_comment_list',$great_appoint_comment_list);
	   $this->assign('general_appoint_comment_list',$general_appoint_comment_list);
	   $this->assign('bad_appoint_comment_list',$bad_appoint_comment_list);
	   $this->display();
	}
	
	//工作人员详情
	public function workerDetail(){
	    $database_merchant_store = D('Merchant_store');
	    
	    $merchant_workers_id = $_GET['merchant_workers_id'] + 0;
	    if(!$merchant_workers_id){
		$this->error_tips('传递参数有误！');
	    }
	    $where['merchant_worker_id']=$merchant_workers_id;
	    $merchant_workers_info = $this->database_merchant_workers->appoint_worker_info($where);
            $comment_where['merchant_worker_id'] = $merchant_workers_id;
            $comment_where['status'] = 1;
            $comment_num = $this->database_appoint_comment->where($comment_where)->count();
            $merchant_workers_info['comment_num'] = $comment_num;
	    //统计月销量
	    $database_appoint_visit_order_info = D('Appoint_visit_order_info');
	    $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
	    $EndDate = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
	    $where['add_time'] = array(array('egt',strtotime($BeginDate)),array('lt',strtotime($EndDate)));
	    $where['merchant_worker_id'] = $merchant_workers_id;
	    $now_month_sales = $database_appoint_visit_order_info->where($where)->count();
	    $merchant_workers_info['now_month_sales'] = $now_month_sales;
	    
	    //联系电话
	    $store_where['merchant_store_id'] = $merchant_workers_info['merchant_store_id'];
	   $merchant_workers_info['tel'] = $database_merchant_store->where($store_where)->getField('phone');
		    
	    $this->database_merchant_workers->where($where)->setInc('click_num');

	    $collect_where['type'] = 'worker_detail';
	    $collect_where['id'] = $_GET['merchant_workers_id'];
	    $collect_where['uid'] = $_SESSION['user']['uid'];
	    $collect_num =  $this->user_collect->where($collect_where)->count();
	    
	    $appoint_list = $this->database_merchant_workers->appoint_list($merchant_workers_id);
	    
	    
	    if ($services = D('Customer_service')->where(array('mer_id' => $now_group['mer_id']))->select()) {
		    $key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
		    $kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.meihua.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_group['mer_id'];
		    $this->assign('kf_url', $kf_url);
	    }
	    
	    $this->assign('merchant_workers_info',$merchant_workers_info);
	    $this->assign('collect_num',$collect_num);
	    $this->assign('appoint_list',$appoint_list);
	    $this->display();
	}
	// 店铺详情
	public function shop(){
	    if(empty($_GET['store_id'])){
			$this->error_tips('当前店铺不存在！');
		}
		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		
		if(!empty($this->user_session)){
			$condition_user_collect['type'] = 'group_shop';
			$condition_user_collect['id'] = $now_store['store_id'];
			$condition_user_collect['uid'] = $this->user_session['uid'];
			if( $this->user_collect->where($condition_user_collect)->find()){
				$now_store['is_collect'] = true;
			}
		}
		$this->assign('now_store',$now_store);
		$store_group_list = D('Appoint')->get_store_appoint_list($now_store['store_id'],5,true);
		$this->assign('store_group_list',$store_group_list);
		$this->display();
	}

	// 预约
	/*public function order(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！',U('Login/index'));
		}
		$now_user = D('User')->get_user($this->user_session['uid']);
		if(empty($this->user_session['phone']) && !empty($now_user['phone'])){
			$_SESSION['user']['phone'] = $this->user_session['phone'] = $now_user['phone'];
		}
		$this->assign('now_user',$now_user);
		if(empty($_GET['appoint_id'])){
			$this->error_tips('当前服务不存在！');
		}

		$appoint_id = $_GET['appoint_id'];
		$now_group = D('Appoint')->get_appoint_by_appointId($appoint_id,'hits-setInc');
		if(empty($now_group)){
			$this->error_tips('当前预约项不存在！');
		}

		if($now_group['start_time'] > $_SERVER['REQUEST_TIME']){
			$this->error_tips('此单还未开始！');
		}
		// 产品列表
		$appointProduct = D('Appoint_product')->get_productlist_by_appointId($appoint_id);
		if($appointProduct){
			$this->assign('appoint_product',$appointProduct);
			if(empty($_GET['menuId'])){
				$defaultAppointProduct = $appointProduct[0];
			}else{
				foreach($appointProduct as $value){
					if($value['id'] == $_GET['menuId']){
						$defaultAppointProduct = $value;
						break;
					}
				}
				if(empty($defaultAppointProduct)){
					$defaultAppointProduct = $appointProduct[0];
				}
			}
			$this->assign('defaultAppointProduct',$defaultAppointProduct);
		}

		$now_group['store_list'] = D('Appoint_store')->get_storelist_by_appointId($now_group['appoint_id']);

		$_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if($long_lat){
			foreach($now_group['store_list'] as &$value){
				$value['range'] = getDistance($value['lat'],$value['long'],$long_lat['lat'],$long_lat['long']);
				$value['range_txt'] = getRange($value['range']);
				$rangeSort[] = $value['range'];
				array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
			}
			$this->assign('long_lat',$long_lat);
		}
		$now_city = D('Area')->get_area_by_areaId($this->config['now_city']);
		$this->assign('city_name',$now_city['area_name']);
		// 预约开始时间 结束时间
		$office_time = unserialize($now_group['office_time']);
		dump($now_group);exit;

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
		$appoint_num = D('Appoint_order')->get_appoint_num($now_group['appoint_id'],$now_group['appoint_people']);
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
			$result = D('Appoint_order')->save_post_form($now_group,$this->user_session['uid'],0);
			if($result['error'] == 1){
				$this->error($result['msg']);
			}

			// 如果需要定金
			if(intval($now_group['payment_status']) == 1){
				$href = U('Pay/check',array('order_id'=>$result['order_id'],'type'=>'appoint'));
			}else{
				$resultOrder = D('Appoint_order')->no_pay_after($result['order_id'],$now_group);
				if($resultOrder['error'] == 1){
					$this->error($resultOrder['msg']);
				}
				$href = U('My/appoint_order',array('order_id'=>$result['order_id']));
			}
			$this->success($href);
		}else{
			if($this->user_session['phone']){
				$this->assign('pigcms_phone',substr($this->user_session['phone'],0,3).'****'.substr($this->user_session['phone'],7));
			}else{
				$this->assign('pigcms_phone','您需要绑定手机号码');
			}
			$this->assign('now_group',$now_group);
			$this->assign('timeOrder',$timeOrder);
			$this->display();
		}
	}*/

        public function ajaxAppointTime(){
            $appoint_id = $this->_post('appoint_id');
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
		$appoint_num = D('Appoint_order')->get_appoint_num($now_group['appoint_id'],$now_group['appoint_people']);
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
	
	//预约
	public function order(){
		$database_merchant_workers = D('Merchant_workers');
		$database_merchant_workers_appoint=D('Merchant_workers_appoint');
		$merchant_workers_id = $this->_get('merchant_workers_id');
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！',U('Login/index'));
		}
		$now_user = D('User')->get_user($this->user_session['uid']);
		if(empty($this->user_session['phone']) && !empty($now_user['phone'])){
			$_SESSION['user']['phone'] = $this->user_session['phone'] = $now_user['phone'];
		}
		$this->assign('now_user',$now_user);
		if(empty($_GET['appoint_id'])){
			$this->error_tips('当前服务不存在！');
		}
		
		$appoint_id = $_GET['appoint_id'];
		$now_group = D('Appoint')->get_appoint_by_appointId($appoint_id,'hits-setInc');
		if(empty($now_group)){
			$this->error_tips('当前预约项不存在！');
		}
		
		if($now_group['start_time'] > $_SERVER['REQUEST_TIME']){
			$this->error_tips('此单还未开始！');
		}
		// 产品列表
		$appointProduct = D('Appoint_product')->get_productlist_by_appointId($appoint_id);
		if($appointProduct){
			$this->assign('appoint_product',$appointProduct);
			if(empty($_GET['menuId'])){
				$defaultAppointProduct = $appointProduct[0];
			}else{
				foreach($appointProduct as $value){
					if($value['id'] == $_GET['menuId']){
						$defaultAppointProduct = $value;
						break;
					}
				}
				if(empty($defaultAppointProduct)){
					$defaultAppointProduct = $appointProduct[0];
				}
			}
			$this->assign('defaultAppointProduct',$defaultAppointProduct);
		}
		
		$now_group['store_list'] = D('Appoint_store')->get_storelist_by_appointId($now_group['appoint_id']);
		
		$long_lat = D('User_long_lat')->getLocation('onfo6tySRgO5tYJtkJ4tAueQI51g');
		if($long_lat){
			foreach($now_group['store_list'] as &$value){
				$value['range'] = getDistance($value['lat'],$value['long'],$long_lat['lat'],$long_lat['long']);
				$value['range_txt'] = getRange($value['range']);
				$rangeSort[] = $value['range'];
				array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
			}
			$this->assign('long_lat',$long_lat);
		}
		$now_city = D('Area')->get_area_by_areaId($this->config['now_city']);
		$this->assign('city_name',$now_city['area_name']);
		
		if($merchant_workers_id){
		    // 预约开始时间 结束时间
		$merchant_workers_info = $database_merchant_workers->where(array('merchant_worker_id'=>$merchant_workers_id))->find();
		
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
		$appoint_num = D('Appoint_order')->get_worker_appoint_num($now_group['appoint_id'],$now_group['appoint_people']);
		if(count($appoint_num)>0){
			foreach ($appoint_num as $val){
				$key = date('Y-m-d',strtotime($val['appoint_date']));
				if($timeOrder[$key][$val['appoint_time']]['order'] != 'no'){
					if(isset($timeOrder[$key]) && ($merchant_workers_info['appoint_people'] == $val['appointNum'])){
						$timeOrder[$key][$val['appoint_time']]['order'] = 'all';
					}
				}
			}
		}
		$this->assign('timeOrder',$timeOrder);
                }
		

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
		
		//工作人员处理
		if($merchant_workers_id){
		    $_Map['merchant_worker_id'] = $merchant_workers_id;
		    $_Map['appoint_id'] = $appoint_id;
		    $chk_worker_info = $database_merchant_workers_appoint->where($_Map)->find();
		    $_where['status'] = 1;
		    $_where['merchant_store_id'] = $chk_worker_info['merchant_store_id'];
		    $_where['appoint_type'] = $now_group['appoint_type'];
		    $merchant_workers_list = $database_merchant_workers->where($_where)->getField('merchant_worker_id,name');
		    $chk_worker_info['merchant_workers_list'] = $merchant_workers_list;
		    $this->assign('chk_worker_info',$chk_worker_info);
		}
		
		
		if(IS_POST){
			$now_group['product_id'] = $_POST['service_type'];
			$now_group['cue_field'] = serialize($_POST['custom_field']);
			$now_group['appoint_date'] = $_POST['service_date'];
			$now_group['appoint_time'] = $_POST['service_time'];
			$now_group['store_id'] = $_POST['store_id']?$_POST['store_id']:0;
			$merchant_workers_id = $_POST['merchant_workers_id'] + 0;
			
			$result = D('Appoint_order')->save_post_form($now_group,$this->user_session['uid'],0,$merchant_workers_id);		
			if($result['error'] == 1){
				$this->error_tips($result['msg']);
			}
			
			// 如果需要定金
			if(intval($now_group['payment_status']) == 1){
				$href = U('Pay/check',array('order_id'=>$result['order_id'],'type'=>'appoint'));
			}else{
				$resultOrder = D('Appoint_order')->no_pay_after($result['order_id'],$now_group);
				if($resultOrder['error'] == 1){
					$this->error_tips($resultOrder['msg']);
				}
				$href = U('My/appoint_order',array('order_id'=>$result['order_id']));
			}
			$this->success($href);
		}else{
			if($this->user_session['phone']){
				$this->assign('pigcms_phone',substr($this->user_session['phone'],0,3).'****'.substr($this->user_session['phone'],7));
			}else{
				$this->assign('pigcms_phone','您需要绑定手机号码');
			}
			$this->assign('now_group',$now_group);
			
			$this->display();
		}
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
	
	
	public function ajaxWorkerTime(){
	    $database_merchant_workers = D('Merchant_workers');
	    $database_merchant_workers_appoint=D('Merchant_workers_appoint');
	    
	    $worker_id = $_POST['worker_id'] + 0;
	    
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
		
		$appoint_id = $this->_post('appoint_id');
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
		$appoint_num = D('Appoint_order')->get_worker_appoint_num($now_group['appoint_id'],$now_group['appoint_people']);
		if(count($appoint_num)>0){
			foreach ($appoint_num as $val){
				$key = date('Y-m-d',strtotime($val['appoint_date']));
				if($timeOrder[$key][$val['appoint_time']]['order'] != 'no'){
					if(isset($timeOrder[$key]) && ($merchant_workers_info['appoint_people'] == $val['appointNum'])){
						$timeOrder[$key][$val['appoint_time']]['order'] = 'all';
					}
				}
			}
		}
		exit(json_encode(array('timeOrder'=>$timeOrder,'status'=>1)));
	}
        
        
        
	
	// 分店
	public function branch(){
		$now_group = D('Appoint')->get_appoint_by_appointId($_GET['appoint_id'],'hits-setInc');
		if(empty($now_group)){
			$this->error_tips('当前预约项不存在！');
		}
		$this->assign('now_group',$now_group);
		
		$this->display();
	}
	
	public function feedback(){
		$now_appoint = D('Appoint')->get_appoint_by_appointId($_GET['appoint_id']);
		if(empty($now_appoint)){
			$this->error_tips('当前预约不存在！');
		}
		$this->assign('now_appoint', $now_appoint);
		
		$_POST['page'] = $_GET['page'];
		$reply_return = D('Reply')->get_page_reply_list($now_appoint['appoint_id'], 2, '','time', 0);
		$reply_return['pagebar'] = '';
		if($$reply_return['total'] > 1){
			if($reply_return['now'] == 1){
				$reply_return['pagebar'] .= '<a class="btn btn-weak btn-disabled">上一页</a>';
			}else{
				$reply_return['pagebar'] .= '<a class="btn btn-weak" href="'.(U('Appoint/feedback', array('appoint_id' => $now_appoint['appoint_id'], 'page' => $reply_return['now'] - 1))).'">上一页</a>';
			}
			$reply_return['pagebar'] .= '<span class="pager-current">'.($reply_return['now']).'</span>';
			if($reply_return['now'] == $reply_return['total']){
				$reply_return['pagebar'] .= '<a class="btn btn-weak btn-disabled">下一页</a>';
			}else{
				$reply_return['pagebar'] .= '<a class="btn btn-weak" href="'.(U('Appoint/feedback', array('appoint_id' => $now_appoint['appoint_id'], 'page' => $reply_return['now'] + 1))).'">下一页</a>';
			}
		}
		$this->assign($reply_return);
		
		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_appoint['mer_id'],array('group_hits'=>1));
		
		/* 粉丝行为分析 */
		$this->behavior(array('mer_id' => $now_appoint['mer_id'],'biz_id' => $now_appoint['appoint_id']));
		
		$this->display();
	}
	public function ajaxFeedback(){
		$this->header_json();
		$now_appoint = D('Appoint')->get_appoint_by_appointId($_GET['appoint_id']);
		if(empty($now_appoint)){
			exit(json_encode(array('status' => 0,'info' => '当前预约不存在！')));
		}
		$reply_return = D('Reply')->get_page_reply_list($now_appoint['appoint_id'], 2, '', 'time', 0);
		$reply_return['status'] = 1;
		exit(json_encode($reply_return));
	}
	
	
	public function storeProductList(){
	    $mer_id = $this->_get('mer_id');
	    if(!$mer_id){
		$this->error_tips('传递参数有误！');
	    }
	    
	    $database_appoint = D('Appoint');
	    $list = $database_appoint->get_appointlist_by_MerchantId($mer_id,0,true);
	    $this->assign('list' , $list);
	    $this->display();
	}
	
}
?>