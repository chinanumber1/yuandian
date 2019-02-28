<?php
/*
 * 公交管理
 *
 * @  BuildTime  2016年7月29日 17:14:13
 */

class BusAction extends BaseAction{

    public function bus_line(){
		$count = D('Fc_bus_line')->count();
		import('@.ORG.system_page');
		$p = new Page($count,15);
		$lineList = D('Fc_bus_line')->limit($p->firstRow.','.$p->listRows)->select();
		// echo D('Fc_bus_line')->getlastsql();
		$this->assign('lineList',$lineList);
		
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
    	$this->display();
    }

    public function bus_line_add(){
    	$this->display();
    }

    public function bus_line_add_data(){
    	// dump($_POST);die;
    	$data['line_name'] = $_POST['line_name'];
    	$data['status'] = $_POST['status'];
    	$data['city_id'] = $_POST['city_id'];
    	$data['province_id'] = $_POST['province_id'];
    	
    	$ret = D('Fc_bus_line')->data($data)->add();

    	if($ret){
    		foreach ($_POST['station'] as $k => $v) {
    			D('Fc_bus_line_station')->data(array('line_id'=>$ret,'station_id'=>$v['stationId'],'sort'=>$v['sort']))->add();
    		}

			$this->frame_submit_tips(1,'添加成功！');
		}else{
			$this->frame_submit_tips(0,'添加失败！请重试~');
		}

    }

    public function bus_line_save(){
    	// echo $_GET['line_id'];
    	$lineInfo = D('Fc_bus_line')->where(array('line_id'=>intval($_GET['line_id'])))->find();
    	// $line_stationList = D('Fc_bus_line_station')->where(array('line_id'=>intval($_GET['line_id'])))->select();

    	

    	$line_stationList = D('')->table(array(C('DB_PREFIX').'fc_bus_line_station'=>'ls', C('DB_PREFIX').'fc_bus_station'=>'s'))->where("ls.line_id= '".intval($_GET['line_id'])."' AND ls.station_id = s.station_id")->select();
    	$this->assign('lineInfo',$lineInfo);
    	// dump($line_stationList);
    	$this->assign('line_stationList',$line_stationList);
    	$this->display();
    }

    public function bus_line_save_data(){
    	// dump($_POST);die;
    	$data['line_name'] = $_POST['line_name'];
    	$data['status'] = $_POST['status'];
    	$data['city_id'] = $_POST['city_id'];
    	$data['province_id'] = $_POST['province_id'];
        $data['last_time'] = time();
    	
    	$ret = D('Fc_bus_line')->where(array('line_id'=>$_POST['line_id']))->data($data)->save();

			D('Fc_bus_line_station')->where(array('line_id'=>$_POST['line_id']))->delete();

			foreach ($_POST['station'] as $k => $v) {
				if($v['stationId']){
					D('Fc_bus_line_station')->data(array('line_id'=>$_POST['line_id'],'station_id'=>$v['stationId'],'sort'=>$v['sort']))->add();
				}
    			
    		}

    	if($ret){
			$this->frame_submit_tips(1,'修改成功');
		}else{
			$this->frame_submit_tips(0,'修改失败！请重试~');
		}
    }

    public function bus_station_data(){
    	$stationList = D('Fc_bus_station')->where(array('station_name'=>array('like','%'.$_POST['name'].'%')))->select();
    	
    	$html.="<option value='xz'>== 请选择站点 ==</option>";

    	if($stationList){
    		foreach ($stationList as $k => $v) {
    			$html.="<option value='".$v['station_id']."'>".D('Area')->name($v['city_id']) .' ' .$v['station_name']."</option>";
    		}
    	}
    		
    	$html.="<option value='tj'>添加站点</option>";

    	exit(json_encode(array('data'=>$html)));
    }

    public function bus_station(){
    	$stationList = D('Fc_bus_station')->select();
    	$this->assign('stationList',$stationList);
    	$this->display();
    }



    public function bus_station_add(){
    	$this->display();
    }

    public function bus_station_add_data(){
    	$long_lat = explode(',',$_POST['long_lat']);
		$data['long'] = $long_lat[0];
		$data['lat'] = $long_lat[1];
		$data['station_name'] = $_POST['station_name'];
		$data['city_id'] = $_POST['city_id'];
		$data['province_id'] = $_POST['province_id'];
		$data['status'] = $_POST['status'];
		$data['first_letter'] = $_POST['first_letter'];

		$ret = D('Fc_bus_station')->data($data)->add();
    	if($ret){
			$this->success(array('id'=>$ret,'station_name'=>$data['station_name']));
		}else{
			$this->error('添加失败！请重试~');
		}
		
    }


    public function bus_station_save(){
    	// echo $_GET['station_id'];
    	$stationInfo = D('Fc_bus_station')->where(array('station_id'=>$_GET['station_id']))->find();
    	// echo D('Fc_bus_station')->getlastsql();
    	$this->assign('stationInfo',$stationInfo);
    	// dump($stationInfo);
    	$this->display();
    }

    public function bus_station_save_data(){
    	$long_lat = explode(',',$_POST['long_lat']);
		$data['long'] = $long_lat[0];
		$data['lat'] = $long_lat[1];
		$data['station_name'] = $_POST['station_name'];
		$data['city_id'] = $_POST['city_id'];
		$data['province_id'] = $_POST['province_id'];
		$data['status'] = $_POST['status'];
		$data['first_letter'] = $_POST['first_letter'];
		$data['station_id'] = $_POST['station_id'];

		$ret = D('Fc_bus_station')->where(array('station_id'=>$_POST['station_id']))->data($data)->save();

    	if($ret){
			$this->frame_submit_tips(1,'修改成功');
		}else{
			$this->frame_submit_tips(0,'修改失败！请重试~');
		}
    }


    public function line_del(){
    	if(D('Fc_bus_line')->where(array('line_id'=>$_POST['line_id']))->delete()){
    		D('Fc_bus_line_station')->where(array('line_id'=>$_POST['line_id']))->delete();
    		$this->success('删除成功！');
    	}else{
    		$this->error('删除失败');
    	}
    }

    public function station_del(){
    	$_POST['station_id'];
    	if(D('Fc_bus_station')->where(array('station_id'=>$_POST['station_id']))->delete()){
    		D('Fc_bus_line_station')->where(array('station_id'=>$_POST['station_id']))->delete();
    		$this->success('删除成功！');
    	}else{
    		$this->error('删除失败');
    	}
    }




    
}