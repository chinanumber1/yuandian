<?php
class AddMerchantProvinceAction extends BaseAction{
    public function index(){
    	$mer_list = M('Merchant')->field('mer_id,city_id,province_id')->where(array('city_id'=>array('gt',0)))->select();
		foreach ($mer_list as &$item) {
			if(empty($item['province_id'])){
				$now_area = M('Area')->field('area_id,area_pid')->where(array('area_id' => $item['city_id']))->find();
				if($now_area['area_pid']!=0){
					$now_area = M('Area')->field('area_id,area_pid')->where(array('area_id' => $now_area['area_pid']))->find();
				}
				$item['province_id'] = $now_area['area_id'];
				M('Merchant')->where(array('mer_id'=>$item['mer_id']))->setField('province_id',$item['province_id']);
			}
		}
    }
}