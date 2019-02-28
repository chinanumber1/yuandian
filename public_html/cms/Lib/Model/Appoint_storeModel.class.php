<?php
class Appoint_storeModel extends Model{
	
	/**
	 * 根据appoint_id获取所有的店铺
	 * @param int $appoint_id
	 */
	public function get_storelist_by_appointId($appoint_id){
		// $long_lat = array('lat' => 0, 'long' => 0);
		//$_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		//if($long_lat){
		//	$lat = $long_lat['lat'];
		//	$long = $long_lat['long'];
		//	$store_list = D('')->field("*, ROUND( 12756.276 * ASIN( SQRT( POW( SIN(( {$lat} * 0.017453294 - `s`.`lat` * 0.017453294 ) / 2 ), 2 ) + COS( {$lat} * 0.017453294 ) * COS(`s`.`lat` * 0.017453294) * POW( SIN(( {$long} * 0.017453294 - `s`.`long` * 0.017453294 ) / 2 ), 2 ))) * 1000 ) AS juli")->table(array(C('DB_PREFIX').'appoint_store'=>'gc',C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'area'=>'a'))->where("`gc`.`appoint_id`='$appoint_id' AND`gc`.`store_id`=`s`.`store_id` AND `gc`.`area_id`=`a`.`area_id` AND `gc`.`city_id`='".C('config.now_city')."'")->order('juli DESC,`s`.`store_id` ASC')->select();

		//}else{
		//	$store_list = D('')->table(array(C('DB_PREFIX').'appoint_store'=>'gc',C('DB_PREFIX').'merchant_store'=>'mc',C('DB_PREFIX').'area'=>'a'))->where("`gc`.`appoint_id`='$appoint_id' AND`gc`.`store_id`=`mc`.`store_id` AND `gc`.`area_id`=`a`.`area_id`")->order('`mc`.`store_id` ASC')->select();

		//}
		$store_list = D('')->table(array(C('DB_PREFIX').'appoint_store'=>'gc',C('DB_PREFIX').'merchant_store'=>'mc',C('DB_PREFIX').'area'=>'a'))->where("`gc`.`appoint_id`='$appoint_id' AND`gc`.`store_id`=`mc`.`store_id` AND `gc`.`area_id`=`a`.`area_id`")->order('`mc`.`store_id` ASC')->select();
		return $store_list;
	}
}

?>