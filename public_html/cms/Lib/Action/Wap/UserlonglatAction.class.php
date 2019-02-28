<?php
//保存用户的地理位置
class UserlonglatAction extends BaseAction{
	public function report(){
		if(IS_POST){
			if($_SESSION['openid'] && $_POST['userLong'] && $_POST['userLat']){
				D('User_long_lat')->saveLocation($_SESSION['openid'],$_POST['userLong'],$_POST['userLat']);
				empty($_COOKIE['userLocationHasRecord']) && setcookie('userLocationHasRecord','1',$_SERVER['REQUEST_TIME']+120,'/');
			}
		}
	}
	/*百度经纬度转火星经纬度*/
	public function baiduToGcj02(){
		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$location2 = $longlat_class->baiduToGcj02($_GET['baidu_lat'], $_GET['baidu_lng']);
		if($location2){
			$this->success($location2);
		}else{
			$this->error('失败');
		}
	}
}
?>