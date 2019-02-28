<?php

//社区O2O的电话

class LbsAction extends BaseAction{
	public function show(){
		$title = $_GET['title'];
		$long = $_GET['long'];
		$lat = $_GET['lat'];
		if(empty($long) || empty($lat)){
			$this->error_tips('访问不正常！请重试。');
		}
		$this->display();
	}
	public function route(){
		$title = $_GET['title'];
		$long = $_GET['long'];
		$lat = $_GET['lat'];
		$this->assign('title',$title);
		$this->assign('long',$long);
		$this->assign('lat',$lat);
        if($_GET['village_id']){
            $database_house_village = D('House_village');
            $now_village = $database_house_village->get_one($_GET['village_id']);
            import('@.ORG.longlat');
            $longlat_class = new longlat();
            $location2 = $longlat_class->gpsToBaidu($now_village['lat'], $now_village['long']);
            $this->assign('long_lat',array('long'=>$location2['lng'],'lat'=>$location2['lat'],'dateline'=>$_SERVER['REQUEST_TIME']));
        }else{
            $this->assign('long_lat',D('User_long_lat')->getLocation($_SESSION['openid']));
        }
		$this->display();
	}
}

?>