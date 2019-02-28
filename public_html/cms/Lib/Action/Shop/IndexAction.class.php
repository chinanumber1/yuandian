<?php

class IndexAction extends BaseAction 
{
	
    public function index() 
    {
		$database_area = D('Area');
		
		//通过IP得到当前IP的地理位置
		import('ORG.Net.IpLocation');
		$Ip = new IpLocation('UTFWry.dat');
		$area = $Ip->getlocation();
		$city = iconv('gbk','utf-8',$area['country']);
		//判断数据库里存不存在当前的城市
		$now_city = S('area_ip_desc_'.$city);
		if(empty($now_city) && !empty($city)){
			$condition_now_city['area_type'] = '2';
			$condition_now_city['area_ip_desc'] = $city;
			$condition_now_city['is_open'] = '1';
			$now_city = $database_area->field('`area_id`,`area_name`,`area_url`')->where($condition_now_city)->find();
			if(is_array($now_city)){								
				S('area_ip_desc_'.$city,$now_city);
			}
		}
		
		if(empty($now_city)){
			$now_city = $database_area->field('`area_id`,`area_name`,`area_url`')->where(array('area_id'=>$this->config['now_city']))->find();
		}
		$this->assign('referer', (isset($_GET['referer']) && $_GET['referer']) ? $_GET['referer'] : '/shop.html');
		$this->assign('now_city',$now_city);
		
		//得到所有的省份
// 		$all_province = S('all_province');
// 		if(empty($all_province)){
// 			$condition_all_area['area_type'] = 1;
// 			$condition_all_area['is_open'] = 1;
// 			$database_field = '`area_id`,`area_name`';
// 			$all_province = $database_area->field($database_field)->where($condition_all_area)->order('`area_sort` ASC,`area_id` ASC')->select();
// 			S('all_province',$all_province);
// 		}
// 		$this->assign('all_province', $all_province);
		
		//得到推荐的城市
// 		$hot_city = S('hot_city');
// 		if(empty($hot_city)){
// 			$database_field = '`area_name`,`area_url`';
// 			$condition_tuijian_city['area_type'] = 2;
// 			$condition_tuijian_city['is_open'] = 1;
// 			$condition_tuijian_city['is_hot'] = 1;
// 			$hot_city = $database_area->field($database_field)->where($condition_tuijian_city)->order('`area_id` ASC')->select();
// 			S('hot_city',$hot_city);
// 		}
// 		$this->assign('hot_city',$hot_city);
		
		//得到所有城市并以城市首拼排序
		$all_city = S('all_city');
		if(empty($all_city)){
			$database_field = '`area_id`,`area_name`,`area_url`,`first_pinyin`,`is_hot`';
			$condition_all_city['area_type'] = 2;
			$condition_all_city['is_open'] = 1;
			$all_city_old = $database_area->field($database_field)->where($condition_all_city)->order('`first_pinyin` ASC,`area_id` ASC')->select();
			foreach($all_city_old as $key=>$value){
				//首拼转成大写
				if(!empty($value['first_pinyin'])){
					$first_pinyin = strtoupper($value['first_pinyin']);
					$all_city[$first_pinyin][] = $value;
				}
			}
			S('all_city',$all_city);
		}
// 		echo '<pre/>';
// 		print_r($all_city);die;
		$city_list = D('Area')->field('`area_id`,`area_name`,`first_pinyin`')->where(array('is_open'=>'1','area_type'=>'2'))->order('`area_sort` DESC,`first_pinyin` ASC')->select();
		$allCityName = array();
		$citiesByName = array();
		foreach($city_list as $value){
			$allCityName[] = $value['area_name'];
			$citiesByName[$value['area_name']] = $value;
		}
// 		$this->assign('defaultCity', $city_list[0]);
		$this->assign('allCityName', $allCityName);
		
		
		$this->assign('city_list', $city_list);
		$this->assign('all_city', $all_city);
		
		$this->display();
    }

	public function suggestion()
	{
		$query = I('query', false);
		$region = I('region', false);
		$url = 'http://api.map.baidu.com/place/v2/suggestion?query='.urlencode($query).'&region='.urlencode($region).'&ak=4c1bb2055e24296bbaef36574877b4e2&output=json&city_limit=false';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if($result['status'] == 0 && $result['result']){
				$data = array();
				foreach($result['result'] as $value){
				    if (!empty($value['location']['lat']) && !empty($value['location']['lng'])) {
    					$data[] = array(
    						'name'=>$value['name'],
    						'light-name'=>str_replace($query, "<b>$query</b>", $value['name']),
    						'lat'=>$value['location']['lat'],
    						'long'=>$value['location']['lng'],
    						'address'=>$value['city'].$value['district'].$value['name']
    					);
				    }
				}
				$return = array();
				if ($data) {
    				$return['error_code'] = 0;
    				$return['data'] = $data;
				} else {
            		$return['error_code'] = 1;
            		$return['error_msg'] = '没有搜到相应的地址';
				}
				echo json_encode($return);
				exit;
			}
		}
		$return = array();
		$return['error_code'] = 1;
		$return['error_msg'] = '百度地图接口错误';

		echo json_encode($return);
	}
}