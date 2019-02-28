<?php
/*
 * wap端前台页面----首页
 *   Writers    hanlu
 *   BuildTime  2016/07/08 15:00
 */
class Scenic_indexAction extends BaseAction{
	# 首页列表
	public function index(){
		# 首页广告
		$advert	=	$this->config['scenic_index_advert'];
		if(empty($advert)){
			$advert	=	$this->config['site_url'].'/tpl/Wap/pure/static/scenic/images/ckjqban_02.jpg';
		}
		$this->assign('advert',$advert);
		# 首页热门推荐
		$where['scenic_status']	=	1;
		$where['is_hot']	=	1;
		$list	=	D('Scenic_list')->where($where)->order('sort desc')->limit($this->config['scenic_index_number'])->select();
		foreach($list as &$v){
			if(!empty($v)){
				$merchant_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$v['scenic_pic']);
				$v['pic'] = $merchant_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'config','1');
				$v['url']	=	U('Scenic_list/details',array('scenic_id'=>$v['scenic_id']));
				if(empty($v['money'])){
					$v['money']	=	M('Scenic_ticket')->where(array('scenic_id'=>$v['scenic_id']))->order('ticket_price')->getField('ticket_price');
				}
				$v['money'] = floatval($v['money']);
			}
			unset($tmp_pic_arr);
		}
		$this->assign('list',$list);
		$this->display();
	}
	# 全部城市列表
	public function all_city(){
		$database_area = D('Area');
		//通过IP得到当前IP的地理位置
//		$now_city = $this->config['scenic_select_city'];
		//判断数据库里存不存在当前的城市
		//if(empty($now_city)){
//			$condition_now_city['area_type'] = '2';
//			$condition_now_city['area_id'] = $now_city['area_id'];
//			$condition_now_city['is_open'] = '1';
//			$now_city = $database_area->field('`area_id`,`area_name`,`area_type`,`area_url`,`area_pid`')->where($condition_now_city)->find();
//		}
//		$this->assign('now_city',$now_city);
		//得到推荐的城市
//		$hot_city = S('scenic_hot_city');
		if(empty($hot_city)){
			$database_field = '`area_name`,`area_url`,`area_id`,`area_type`,`area_pid`';
			$condition_tuijian_city['area_type'] = 2;
			$condition_tuijian_city['is_open'] = 1;
			$condition_tuijian_city['is_hot'] = 1;
			$condition_tuijian_city['is_abroad'] = 2;
			$hot_city = $database_area->field($database_field)->where($condition_tuijian_city)->order('`area_id` ASC')->select();
			if($hot_city){
				foreach($hot_city as &$vv){
					$vv['url']	=	U('city_index',array('scenic_city'=>$vv['area_id']));
				}
				//			S('scenic_hot_city',$hot_city);
			}
		}
		$this->assign('hot_city',$hot_city);
		//得到所有城市并以城市首拼排序
//		$all_city = S('scenic_all_city');
		if(empty($all_city)){
			$all_city['domestic']	=	$this->city_format();
//			$all_city['domestic']	=	$this->city_format(2);
			if($all_city['domestic']){
				foreach($all_city['domestic'] as &$v){
					foreach($v as &$vvv){
						$vvv['url']	=	U('city_index',array('scenic_city'=>$vvv['area_id']));
					}
				}
			}
			//if($all_city['abroad_city']){
//				foreach($all_city['domestic'] as &$v){
//					foreach($v as &$vv){
//						$vv['url']	=	U('city_index',array('scenic_city'=>$vv['area_id']));
//					}
//				}
//			}
//			S('scenic_all_city',$all_city);
		}
		$this->assign('all_city',$all_city);
		$this->display();
	}
	# 城市格式化
	public function city_format(){
		$database_area = M('Area');
		$database_field = '`area_id`,`area_name`,`area_url`,`first_pinyin`,`is_hot`';
		$condition_all_city['area_type'] = 2;
		$condition_all_city['is_open'] = 1;
//		$condition_all_city['is_abroad'] = $is_abroad;
		$all_city_old = $database_area->field($database_field)->where($condition_all_city)->order('`first_pinyin` ASC,`area_id` ASC')->select();
		foreach($all_city_old as $key=>$value){
			//首拼转成大写
			if(!empty($value['first_pinyin'])){
				$first_pinyin = strtoupper($value['first_pinyin']);
				$all_city[$first_pinyin][] = $value;
			}
		}
		return $all_city;
	}
	# 获取附近目的地
	public function nearby_city(){
		$database_area = D('Area');
		//通过IP得到当前IP的地理位置
		$city = $this->config['scenic_select_city'];
		//判断数据库里存不存在当前的城市
		if(!empty($city)){
			$condition_now_city['area_type'] = '2';
			$condition_now_city['area_id'] = $city['area_id'];
			$condition_now_city['is_open'] = '1';
			$now_city = $database_area->field('`area_id`,`area_name`,`area_url`')->where($condition_now_city)->find();
//			if($now_city){
//				$now_city['url']	=	U('city_index',array('scenic_city'=>$now_city['area_id']));
//			}
		}
//		$this->assign('now_city',$now_city);
		$scenic_area = S('scenic_area_nearby_'.$now_city['area_id']);
		if(empty($scenic_area)){
			$scenic_area	=	D('Area')->scenic_get_area_by_areaId($now_city['area_id'],1);
			if($scenic_area){
				foreach($scenic_area['city'] as &$vv){
					$vv['url']	=	U('city_index',array('scenic_city'=>$vv['area_id']));
				}
			}
			S('scenic_area_nearby_'.$now_city['area_id'],$scenic_area);
		}
		$this->assign('scenic_area',$scenic_area);
		$this->display();
	}
	# 城市首页
	public function city_index(){
		# 广告轮播图
		$advert_number	=	$this->config['scenic_advert_number'];
		$city_id	=	$this->config['scenic_city'];
		if(empty($city_id)){
			$this->error_tips('请先选择城市！',U('Scenic_index/all_city'));
		}
		$scenic_area	=	D('Area')->scenic_get_one_city($city_id);
		if(empty($scenic_area)){
			$this->error_tips('该城市已关闭，请选择其它城市！',U('Scenic_index/all_city'));
		}
		if($scenic_area['area_type'] != 2){
			$this->error_tips('请先选择城市！',U('Scenic_index/all_city'));
		}
		$city_advert	=	M('Scenic_advert')->field(true)->where(array('cat_id'=>1,'advert_status'=>1,'city_id'=>$city_id))->order('sort DESC')->limit($advert_number)->select();
		$advert_count	=	count($city_advert);
		if($advert_number > $advert_count){
			$wanting	=	$advert_number - $advert_count;
			$index_advert	=	M('Scenic_advert')->field(true)->where(array('cat_id'=>1,'advert_status'=>1,'city_id'=>0))->order('sort DESC')->limit($wanting)->select();
			if($index_advert){
				if($advert_count == 0){
					$city_advert	=	$index_advert;
				}else{
					$city_advert	=	array_merge($city_advert,$index_advert);
				}
			}
		}
		if($city_advert){
			foreach($city_advert as &$v){
				$v['advert_img']	=	$this->config['site_url'].$v['advert_img'];
			}
		}
		$this->assign('city_advert',$city_advert);
		
		# 天气预报
		$city	=	$this->config['scenic_select_city']['area_name'];
		$weather = S('scenic_weather_'.$city.date('Ymd'));
		if(empty($weather)){
			$weather	=	im::weather($city);
			$weather['img']	=	$weather['img'].'.png';
			
			S('scenic_weather_'.$city.date('Ymd'),$weather);
			$this->assign('weather',$weather);
		}
		
		# 城市下有多少个景点
		$scenic_number	=	D('Scenic_list')->scenic_number($city_id);
		$this->assign('scenic_number',$scenic_number);
		# 景点推荐
		$hot_list	=	D('Scenic_list')->hot_list($city_id,$this->config['scenic_index_number']);
		if($hot_list){
			$merchant_image_class = new scenic_image();
			foreach($hot_list as &$v){
				unset($v['scenic_pwd']);
				$tmp_pic_arr = explode(';',$v['scenic_pic']);
				$v['pic'] = $merchant_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'config','1');
				$v['url'] = U('Scenic_list/details',array('scenic_id'=>$v['scenic_id']));
				if(empty($v['money'])){
					$v['money']	=	M('Scenic_ticket')->where(array('scenic_id'=>$v['scenic_id']))->order('ticket_price')->getField('ticket_price');
				}
				$v['money'] = floatval($v['money']);
			}
		}
		$this->assign('hot_list',$hot_list);
		$this->assign('scenic_select_city',$scenic_area);
		$this->display();
	}
	# 景区
	public function recommended(){
		//$city_id	=	$this->config['scenic_city'];
//		$long	=	$_POST['long'];
//		$lat	=	$_POST['lat'];
//		$group_category = M('Group_category')->field(array('cat_id','scenic_number','cat_name','cat_url'))->where(array('is_scenic'=>1))->order('cat_sort DESC')->select();
//		$arr	=	array();
//		if($group_category){
//			foreach($group_category as $v){
//				$arr[]	=	array(
//					'list'	=>	D('Group')->scenic_get_group_list_by_cate($v['cat_id'],$city_id,null,$v['scenic_number'],$lat,$long,$v['cat_name'],$v['cat_url']),
//					'color'	=>	$this->rand_color(6,'COLOR'),
//				);
//			}
//		}
		$groom_category	=	M('Scenic_groom_category')->where(array('status'=>1))->order('cat_sort DESC,cat_id DESC')->select();
		if($groom_category){
			foreach($groom_category as $v){
				$arr[]	=	array(
					'cat_name'	=>	$v['cat_name'],
					'list'	=>	M('Scenic_groom')->where(array('status'=>1,'cat_id'=>$v['cat_id']))->select(),
					'color'	=>	$this->rand_color(6,'COLOR'),
				);
			}
		}
		$this->returnCode(0,$arr);
	}
	# 搜索页面
	public function search(){
		$this->display();
	}
	# 搜索列表json
	public function search_json(){
		$city_id['scenic_title']	=	array('like','%'.$_POST['scenic'].'%');
		$city_id['scenic_status']	=	1;
		$scenic_list	=	D('Scenic_list')->get_all_list($city_id,20);
		if($scenic_list){
			$scenic_image_class = new scenic_image();
			foreach($scenic_list as $k=>&$v){
				$city	=	D('Area')->scenic_get_one_city($v['city_id']);
				if(empty($city)){
					unset($scenic_list[$k]);
					continue;
				}
				$tmp_pic_arr = explode(';',$v['scenic_pic']);
				$v['pic'] = $scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'config','s');
				unset($v['scenic_account'],$v['scenic_pwd']);
				$v['range_txt'] = $this->wapFriendRange($v['juli']);
				$v['url']	=	U('Scenic_list/details',array('scenic_id'=>$v['scenic_id']));
				if(empty($v['money'])){
					$v['money']	=	M('Scenic_ticket')->where(array('scenic_id'=>$v['scenic_id']))->order('ticket_price')->getField('ticket_price');
				}
				$v['money'] = floatval($v['money']);
			}
		}else{
			$scenic_list	=	$this->config['site_url'].'/tpl/Wap/pure/static/scenic/images/search_fail.png';
		}
		$this->returnCode(0,$scenic_list);
	}
}
?>