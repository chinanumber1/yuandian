<?php
class User_adressModel extends Model{
	/*得到所有地址*/
	public function get_adress_list($uid , $flag = true){
		$condition_user_adress['uid'] = $uid;
		$user_adress_list = $this->field(true)->where($condition_user_adress)->order('`default` DESC,`adress_id` ASC')->select();
		foreach($user_adress_list as $key=>$value){
			if ((!($value['latitude'] > 0 &&  $value['longitude'] > 0))&& $flag) {
				unset($user_adress_list[$key]);
				continue;
			}
			$province = D('Area')->get_area_by_areaId($value['province'],false);
			$user_adress_list[$key]['province_txt'] = $province['area_name'];
			
			$city = D('Area')->get_area_by_areaId($value['city'],false);
			$user_adress_list[$key]['city_txt'] = $city['area_name'];
			
			$area = D('Area')->get_area_by_areaId($value['area'],false);
			$user_adress_list[$key]['area_txt'] = $area['area_name'];
		}
		return $user_adress_list;
	}
	/*设置默认地址*/
	public function set_default($uid,$adress_id){
		$condition_default_user_adress['uid'] = $uid;
		$this->where($condition_default_user_adress)->setField('default','0');
		
		$condition_user_adress['adress_id'] = $adress_id;
		$condition_user_adress['uid'] = $uid;
		
		return $this->where($condition_user_adress)->setField('default','1');
	}
	/*保存地址*/
	public function post_form_save($uid){

		if($_POST['adress_id']){
			$condition_user_adress['adress_id'] = $_POST['adress_id'];
			$condition_user_adress['uid'] = $uid;
			unset($_POST['adress_id']);	
			if(!empty($_POST['default'])){
				$condition_default_user_adress['uid'] = $uid;
				$this->where($condition_default_user_adress)->setField('default','0');
			}else{
				$_POST['default'] = 0;
			}
			
			return $this->where($condition_user_adress)->data($_POST)->save();
		}else{
			$_POST['uid'] = $uid;
			if(!empty($_POST['default'])){
				$condition_default_user_adress['uid'] = $uid;
				$this->where($condition_default_user_adress)->setField('default','0');
			}else{
				$_POST['default'] = 0;
			}
			return $this->data($_POST)->add();
		}
	}
	
	public function delete_adress($uid,$adress_id){
		$condition_user_adress['uid'] = $uid;
		$condition_user_adress['adress_id'] = $adress_id;
		return $this->where($condition_user_adress)->delete();
	}
	
	public function get_adress($uid,$adress_id){
		$condition_user_adress['uid'] = $uid;
		$condition_user_adress['adress_id'] = $adress_id;
		return $this->field(true)->where($condition_user_adress)->find();
	}
	public function get_one_adress($uid,$adress_id=0){
		$condition_user_adress['uid'] = $uid;
		if($adress_id){
			$condition_user_adress['adress_id'] = $adress_id;
		}
		$user_adress = $this->field(true)->where($condition_user_adress)->order('`default` DESC,`adress_id` ASC')->find();
		if($user_adress){
			if (!($user_adress['latitude'] > 0 &&  $user_adress['longitude'] > 0)) return false;
			$province = D('Area')->get_area_by_areaId($user_adress['province'],false);
			$user_adress['province_txt'] = $province['area_name'];
				
			$city = D('Area')->get_area_by_areaId($user_adress['city'],false);
			$user_adress['city_txt'] = $city['area_name'];
				
			$area = D('Area')->get_area_by_areaId($user_adress['area'],false);
			$user_adress['area_txt'] = $area['area_name'];
		}
		
		return $user_adress;
	}
	//得到距离在1千米之内的一个地址
	public function getNearAdress($uid, $lat, $lng){
        $where = "`uid`='$uid' AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`latitude`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`latitude`*PI()/180)*POW(SIN(({$lng}*PI()/180-`longitude`*PI()/180)/2),2)))*1000) <= 100000";
        $oneAdress = $this->field("*,ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`latitude`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`latitude`*PI()/180)*POW(SIN(({$lng}*PI()/180-`longitude`*PI()/180)/2),2)))*1000) AS juli")->where($where)->order('`juli` ASC')->find();

        return $oneAdress;
    }
}
?>