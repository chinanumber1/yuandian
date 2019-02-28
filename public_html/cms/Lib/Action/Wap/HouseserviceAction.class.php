<?php

//社区O2O便民中心

class HouseserviceAction extends BaseAction{
	public function index(){
		$now_village = $this->get_village($_GET['village_id']);
		$this->assign('now_village',$now_village);
		$hot_cat_list = D('House_service_category')->getHotCatList($now_village['village_id'],6);
		$cat_list = D('House_service_category')->getAllCatList($now_village['village_id']);
		if(!$cat_list){
			$this->house_service_test();
			$cat_list = D('House_service_category')->getAllCatList($now_village['village_id']);
		}

		//幻灯片
		$has_service_slide = $this->getHasConfig($now_village['village_id'],'has_service_slide');      
		if($has_service_slide){
			$where['village_id'] = $now_village['village_id'];
			$where['status'] = '1';
			$where['type'] = '1';
			$slider_list = M('House_village_slider')->where($where)->order('sort DESC,id ASC')->select();
			if(defined('IS_INDEP_HOUSE')){
				foreach($slider_list as $k=>$v){
					$slider_list[$k]['url'] = str_replace('wap.php',C('INDEP_HOUSE_URL'), $v['url']);
				}
			}
			$this->assign('slider_list',$slider_list);
		}

		$this->assign('hot_cat_list',$hot_cat_list);
		$this->assign('cat_list',$cat_list);
		
		$this->display();
	}
	public function cat_list(){
		$now_village = $this->get_village($_GET['village_id']);
		$this->assign('now_village',$now_village);
		
		$now_category = M('House_service_category')->where(array('id'=>$_GET['id'],'village_id'=>$now_village['village_id']))->find();
		if(empty($now_category)){
			$this->error_tips('当前便民信息分类不存在');
		}
		$this->assign('now_category',$now_category);
		
		$this->assign('long_lat',D('User_long_lat')->getLocation($_SESSION['openid']));
		
		$this->display();
	}
        
        public function lbs_search(){
                $keyword =$_GET['keyword'];
                $village_id = $_GET['village_id'] + 0;
                if(empty($keyword) || empty($village_id)){
                    $this->error_tips('传递参数有误！');
                }
                
		$city_id = $this->config['now_city'];
		$now_city = D('Area')->field(true)->where(array('area_id' => $city_id))->find();
                
                $database_house_village = D('House_village');
                $now_village = $database_house_village->get_one($village_id);
                $this->assign('now_village',$now_village);
                $url = 'http://api.map.baidu.com/place/v2/search?query='.urlencode($keyword).'&page_size=9999&page_num=0&scope=1&location=&location='.$now_village['lat'].','.$now_village['long'].'&radius=2000&output=json&ak=4c1bb2055e24296bbaef36574877b4e2';
                import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
                if($result){
			$result = json_decode($result,true);
			if($result['status'] == 0 && $result['results']){
				$return = array();
				foreach($result['results'] as $value){
					$return[] = array(
						'name'=>$value['name'],
						'lat'=>$value['location']['lat'],
						'long'=>$value['location']['lng'],
						'address'=>$value['city'].$value['district']
					);
				}
				$this->assign('result',$result);
			}else{
				$this->error_tips('查询为空！');
			}
		}else{
			$this->error_tips('查询为空！');
		}
                $this->display();
        }
        
        public function ajax_lbs_list(){
			$this->header_json();
			$keyword = $_GET['keyword'];
			$village_id = $_GET['village_id'] + 0;
			if(empty($keyword) || empty($village_id)){
				$this->error_tips('传递参数有误！');
			}
					
			$city_id = $this->config['now_city'];
			$now_city = D('Area')->field(true)->where(array('area_id' => $city_id))->find();
					
			$database_house_village = D('House_village');
			$now_village = $database_house_village->get_one($village_id);
			$url = 'http://api.map.baidu.com/place/v2/search?query='.urlencode($keyword).'&page_size=9999&page_num=0&scope=1&location=&location='.$now_village['lat'].','.$now_village['long'].'&radius=2000&output=json&ak=4c1bb2055e24296bbaef36574877b4e2';
			import('ORG.Net.Http');
			$http = new Http();
			$results = $http->curlGet($url);
			file_put_contents('111.txt',json_encode($results));
			if($results){
				$results = json_decode($results,true);
				if($results['status'] == 0 && $results['results']){
					$return = array();
					foreach($results['results'] as $value){
						if(!isset($value['location']['lat']) && !isset($value['location']['lng'])){
							continue;
						}
						
						$url = wapLbsTranform("LBS://".$value['location']['lng'].",".$value['location']['lat'],array('title'=>$value['name'],'village_id'=>$village_id),true);

						$return['list'][] = array(
							'name'=>$value['name'],
							'lat'=>$value['location']['lat'],
							'long'=>$value['location']['lng'],
													'address'=>$value['address'],
													'url'=>$url['url']
						);
					}
								}
							   $return['count'] = count($return['list']);
					}
					exit(json_encode($return));
        
           }
        
	public function ajax_service(){
		$this->header_json();
		
		$page = $_GET['page'] ? intval($_GET['page']) : 1;
		
		$database_house_service_info = M('House_service_info');
		$return['count'] = $database_house_service_info->where(array('cat_id'=>$_GET['id'],'status'=>'1'))->count();
		if($return['count'] > ($page-1)*10){
			$return['list'] = $database_house_service_info->field('`id`,`title`,`phone`,`img_path`,`url`')->where(array('cat_id'=>$_GET['id'],'status'=>'1'))->order('sort DESC,id ASC')->limit((($page-1)*10).',10')->select();
			foreach($return['list'] as &$serviceValue){
				if(!empty($serviceValue['img_path'])){
					$serviceValue['img_path'] = $this->config['site_url'].'/upload/service/'.$serviceValue['img_path'];
				}
				$tmpLbs = wapLbsTranform($serviceValue['url'],array('title'=>$serviceValue['title'],'pic'=>$serviceValue['img_path'],'phone'=>$serviceValue['phone']),true);
				if(is_array($tmpLbs)){
					$serviceValue['url'] = $tmpLbs['url'];
					$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
					if($long_lat){
						import('@.ORG.longlat');
						$longlat_class = new longlat();
						$serviceValue['range'] = $longlat_class->GetDistanceMToKm($long_lat['lat'],$long_lat['long'],$tmpLbs['lat'],$tmpLbs['long'],true);
					}
				}
			}
		}else{
			$return['list'] = array();
		}
		$return['totalPage'] = ceil($return['count']/10);
		
		echo json_encode($return);
	}
	protected function get_village($village_id){
		$now_village = D('House_village')->get_one($village_id);
		if(empty($now_village)){
			$this->error_tips('当前访问的小区不存在或未开放');
		}
		$this->assign('now_village',$now_village);
		return $now_village;
	}
	private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
   }


	private function house_service_test(){
		$database_house_service_category = D('House_service_category');

		$service_category['village_id'] = $service_where['village_id'] = $_GET['village_id'] + 0;
		$service_category['cat_name'] = $service_where['cat_name'] = '品质生活';
		$service_category['status'] = $service_category['is_test'] = $service_where['is_test'] = 1;

		$service_info = $database_house_service_category->where($service_where)->order('id desc')->find();
		if(!$service_info){
			$insert_id = $database_house_service_category->data($service_category)->add();
		}else{
			$insert_id = $service_info['id'];
		}

		$service_son_category['add_time'] = time();
		$service_son_category['parent_id'] = $insert_id;
		$service_son_category['village_id'] = $_GET['village_id'] + 0;
		$service_son_category['status'] = $service_son_category['is_test'] = 1;
		if($insert_id){
			$service_son_category_condition['parent_id'] = $insert_id;
			$count = $database_house_service_category->where($service_son_category_condition)->count();
			if($count >= 3){
				return false;
			}

			$service_son_category['cat_name'] = '社区账单';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/zhangdan.png';
			$database_house_service_category->data($service_son_category)->add();

			$service_son_category['cat_name'] = '访客通行';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/fangke.png';
			$database_house_service_category->data($service_son_category)->add();

			$service_son_category['cat_name'] = '我的房屋';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/my_house.png';
			$database_house_service_category->data($service_son_category)->add();

			/* $service_son_category['cat_name'] = '疏通';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/shutong.png';
			$database_house_service_category->data($service_son_category)->add(); */

			/* $service_son_category['cat_name'] = '生活缴费';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/fee2.png';
			$database_house_service_category->data($service_son_category)->add(); */



			/* $service_son_category['cat_name'] = '社区用车';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/car.png';
			$database_house_service_category->data($service_son_category)->add(); */

			/* $service_son_category['cat_name'] = '门禁钥匙';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/key.png';
			$database_house_service_category->data($service_son_category)->add(); */

			/*$service_son_category['cat_name'] = '快递收送';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/kauidi.png';
			$database_house_service_category->data($service_son_category)->add(); */

			/* $service_son_category['cat_name'] = '挂画服务';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/guahua.png';
			$database_house_service_category->data($service_son_category)->add(); */

			/* $service_son_category['cat_name'] = '更换高压软管';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/ruanguan.png';
			$database_house_service_category->data($service_son_category)->add(); */

			/* $service_son_category['cat_name'] = '更换灯泡';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/dengpao.png';
			$database_house_service_category->data($service_son_category)->add(); */

			/* $service_son_category['cat_name'] = '报事报修';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/baoxiu.png';
			$database_house_service_category->data($service_son_category)->add(); */
		}

		$service_category['cat_name'] = $service_where['cat_name'] = '便民服务';
		$service_info = $database_house_service_category->where($service_where)->order('id desc')->find();

		if(!$service_info){
			$insert_id = $database_house_service_category->data($service_category)->add();
		}else{
			$insert_id = $service_info['id'];
		}

		if($insert_id){
			$service_son_category_condition['parent_id'] = $service_son_category['parent_id'] = $insert_id;
			$count = $database_house_service_category->where($service_son_category_condition)->count();
			if($count >= 3){
				return false;
			}

			/* 			$service_son_category['cat_name'] = '更换高压软管';
                        $service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/ruanguan.png';
                        $database_house_service_category->data($service_son_category)->add();

                        $service_son_category['cat_name'] = '手机维修';
                        $service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/mobile.png';
                        $database_house_service_category->data($service_son_category)->add();

                        $service_son_category['cat_name'] = '房屋维修';
                        $service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/repair.png';
                        $database_house_service_category->data($service_son_category)->add();

                        $service_son_category['cat_name'] = '家庭保洁';
                        $service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/clear.png';
                        $database_house_service_category->data($service_son_category)->add(); */


			$service_son_category['cat_name'] = '快递收送';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/kauidi.png';
			$database_house_service_category->data($service_son_category)->add();

			$service_son_category['cat_name'] = '社区用车';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/car.png';
			$database_house_service_category->data($service_son_category)->add();

			$service_son_category['cat_name'] = '生活缴费';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/fee2.png';
			$database_house_service_category->data($service_son_category)->add();
		}

		$service_category['cat_name'] = $service_where['cat_name'] = '物业服务';
		$service_info = $database_house_service_category->where($service_where)->order('id desc')->find();

		if(!$service_info){
			$insert_id = $database_house_service_category->data($service_category)->add();
		}else{
			$insert_id = $service_info['id'];
		}


		if($insert_id){
			$service_son_category_condition['parent_id'] = $service_son_category['parent_id'] = $insert_id;
			$count = $database_house_service_category->where($service_son_category_condition)->count();
			if($count >= 3){
				return false;
			}

			$service_son_category['cat_name'] = '周边预约';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/appoint.png';
			$service_son_category['cat_url'] = U('House/village_appointlist',array('village_id'=>$_GET['village_id'] + 0));
			$database_house_service_category->data($service_son_category)->add();

			$service_son_category['cat_name'] = '周边团购';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/group.png';
			$service_son_category['cat_url'] = U('House/village_grouplist',array('village_id'=>$_GET['village_id'] + 0));
			$database_house_service_category->data($service_son_category)->add();

			$service_son_category['cat_name'] = '周边餐饮';
			$service_son_category['cat_img'] = '/tpl/Wap/pure/static/images/kd.png';
			$service_son_category['cat_url'] = U('House/village_meallist',array('village_id'=>($_GET['village_id'] + 0) ."#cat-all"));
			$database_house_service_category->data($service_son_category)->add();
		}
	}
}

?>