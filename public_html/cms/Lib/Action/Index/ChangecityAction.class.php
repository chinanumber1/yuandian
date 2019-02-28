<?php
/*
 * 选择城市
 *
 */
class ChangecityAction extends BaseAction{
    public function index(){
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
		$this->assign('now_city',$now_city);
		
		
		//得到所有的省份
		$all_province = S('all_province');
		if(empty($all_province)){
			$condition_all_area['area_type'] = 1;
			$condition_all_area['is_open'] = 1;
			$database_field = '`area_id`,`area_name`';
			$all_province = $database_area->field($database_field)->where($condition_all_area)->order('`area_sort` ASC,`area_id` ASC')->select();
			S('all_province',$all_province);
		}
		$this->assign('all_province',$all_province);
		
		//得到推荐的城市
		$hot_city = S('hot_city');
		if(empty($hot_city)){
			$database_field = '`area_name`,`area_url`';
			$condition_tuijian_city['area_type'] = 2;
			$condition_tuijian_city['is_open'] = 1;
			$condition_tuijian_city['is_hot'] = 1;
			$hot_city = $database_area->field($database_field)->where($condition_tuijian_city)->order('`area_sort` DESC,`area_id` ASC')->select();
			S('hot_city',$hot_city);
		}
		$this->assign('hot_city',$hot_city);
		
		//得到所有城市并以城市首拼排序
		$all_city = S('all_city');
		if(empty($all_city)){
			$database_field = '`area_id`,`area_name`,`area_url`,`first_pinyin`,`is_hot`';
			$condition_all_city['area_type'] = 2;
			$condition_all_city['is_open'] = 1;
			$all_city_old = $database_area->field($database_field)->where($condition_all_city)->order('`first_pinyin` ASC,`area_sort` DESC,`area_id` ASC')->select();
			foreach($all_city_old as $key=>$value){
				//首拼转成大写
				if(!empty($value['first_pinyin'])){
					$first_pinyin = strtoupper($value['first_pinyin']);
					$all_city[$first_pinyin][] = $value;
				}
			}
			S('all_city',$all_city);
		}
		$this->assign('all_city',$all_city);
		
		$this->display();
    }
	//根据省份的ID得到符合的所有城市
	public function ajaxGetCitysByPro(){
		$area_id = $this->_get('pid','intval');
		$area_city = S('change_area_city_'.$area_id);
		if(empty($area_city) && !empty($area_id)){
			$database_area = D('Area');
			$condition_area_city['area_pid'] = $area_id;
			$condition_area_city['is_open'] = 1;
			$database_field = '`area_name` `cityname`,`area_url` `encity`';
			$area_city = $database_area->field($database_field)->where($condition_area_city)->order('`area_sort` ASC,`area_id` ASC')->select();
			if(is_array($area_city)){
				S('change_area_city_'.$area_id,$area_city);
			}
		}
		echo json_encode($area_city);
	}
}