<?php
class Pick_addressModel extends Model{
	public function get_pick_addr_by_merid($mer_id,$is_system=false, $store_id = 0){
	    
	    $lng_lat['long'] = cookie('userLocationLong');
	    $lng_lat['lat'] = cookie('userLocationLat');
	    if (empty($lng_lat['long']) || empty($lng_lat['lat'])) {
	        $lng_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
	    }
		
		if(!$is_system){
			$store_list = D('Merchant_store')->get_storelist_by_merId($mer_id);
			foreach($store_list as $key=>$vs) {
				$area[]  =$vs['province_id'];
				$area[]  =$vs['city_id'];
				$area[]  =$vs['area_id'];
				$distance  = getDistance($lng_lat['lat'],$lng_lat['long'],$vs['lat'],$vs['long']);
				$pick_addr[]=array('name'=>$vs['adress'].' '.$vs['name'],'area_info'=>array('province'=>$vs['province_id'],'city'=>$vs['city_id'],'area'=>$vs['area_id']),'pick_addr_id'=>'s'.$vs['store_id'],'phone'=>$vs['phone'],'long'=>$vs['long'],'lat'=>$vs['lat'],'addr_type'=>1,'distance'=>$distance);
			}
		} elseif ($store_id) {
		    $store = D('Merchant_store')->field(true)->where(array('mer_id' => $mer_id, 'store_id' => $store_id))->find();
		    $area[] = $store['province_id'];
		    $area[] = $store['city_id'];
		    $area[] = $store['area_id'];
		    $distance  = getDistance($lng_lat['lat'], $lng_lat['long'], $store['lat'], $store['long']);
		    $pick_addr[] = array('name'=> $store['adress'] . ' ' . $store['name'], 'area_info' => array('province' => $store['province_id'], 'city' => $store['city_id'], 'area' => $store['area_id']),'pick_addr_id' => 's' . $store['store_id'], 'phone' => $store['phone'], 'long' => $store['long'], 'lat' => $store['lat'], 'addr_type' => 1,'distance' => $distance);
		}
		$pick_addr_list = M('Pick_address')->where(array('mer_id'=>$mer_id))->select();
        foreach($pick_addr_list as $k=>$v){
            $area[]  =$v['province_id'];
            $area[]  =$v['city_id'];
            $area[]  =$v['area_id'];
			$distance  = getDistance($lng_lat['lat'],$lng_lat['long'],$v['lat'],$v['long']);
			$pick_addr[]=array('name'=>$v['pick_addr'],'area_info'=>array('province'=>$v['province_id'],'city'=>$v['city_id'],'area'=>$v['area_id']),'pick_addr_id'=>'p'.$v['id'],'phone'=>$v['phone'],'long'=>$v['long'],'lat'=>$v['lat'],'addr_type'=>2,'distance'=>$distance);
		}
		$where['area_id']=array('in',implode(',',$area));
		$area_name = M('Area')->where($where)->getField('area_id,area_name');
		foreach($pick_addr as &$v){
			$v['area_info']['province'] = $area_name[$v['area_info']['province']];
			$v['area_info']['city'] = $area_name[$v['area_info']['city']];
			$v['area_info']['area'] = $area_name[$v['area_info']['area']];
		}
		$pick_addr = sortArrayAsc($pick_addr,'distance');
		return $pick_addr;
	}


//	public function get_one_address($pick_addr_id,$address_type){
//		if($address_type==1){
//
//		}elseif($address_type==2){
//
//		}
//	}
}