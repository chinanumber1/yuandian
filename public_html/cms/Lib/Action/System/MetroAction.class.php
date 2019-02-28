<?php

class MetroAction extends BaseAction
{
    public $line_table_name = 'FcMetroLine';
    
    public $station_table_name = 'FcMetroStation';
    
    public $line_station_table_name = 'FcMetroLineStation';
    
    
    public function line()
    {
        
        $lines = D($this->line_table_name)->all();
        
        foreach($lines as $key => $line) {
            $lines[$key]['city'] = D('Area')->name($line['city_id']);
        }
        
        $this->assign('lines', $lines);
        $this->display();
    }
    
    
    public function createLine()
    {
        $this->display();
    }
    
    public function saveLine()
    {
        
     	$data['name'] = $_POST['name'];
    	$data['is_hot'] = $_POST['is_hot'];
    	$data['status'] = $_POST['status'];
    	$data['sort'] = $_POST['sort'];
    	$data['city_id'] = $_POST['city_id'];
    	$data['province_id'] = $_POST['province_id'];
    	
    	$result = D($this->line_table_name)->add($data);

    	if($result) {
    	    foreach ($_POST['station'] as $k => $v) {
    	        D($this->line_station_table_name)->data(array('line_id'=>$result,'station_id'=>$v['stationId'],'sort'=>$v['sort']))->add();
    	    }
    	    
			$this->frame_submit_tips(1,'添加成功！');
		} else {
			$this->frame_submit_tips(0,'添加失败！请重试~');
		}
    }
    
    public function editLine()
    {
        $info = D($this->line_table_name)->info( $_GET['id'] );
        $stations = D('')->table(array(C('DB_PREFIX').'fc_metro_line_station'=>'ls', C('DB_PREFIX').'fc_metro_station'=>'s'))->where("ls.line_id= '".intval($_GET['id'])."' AND ls.station_id = s.id")->select();

        $this->assign('info', $info);
        $this->assign('stations', $stations);
        $this->display();
    }
    
    public function updateLine()
    {
        $id = intval($_POST['id']);

        
        $data['name'] = $_POST['name'];
        $data['is_hot'] = $_POST['is_hot'];
        $data['status'] = $_POST['status'];
        $data['sort'] = $_POST['sort'];
        $data['city_id'] = $_POST['city_id'];
        $data['province_id'] = $_POST['province_id'];
        
        $result = D($this->line_table_name)->where( array('id' => $id) )->save($data);
        
        D($this->line_station_table_name)->where(array('line_id'=>$id))->delete();
        
        foreach ($_POST['station'] as $k => $v) {
            if($v['stationId']){
                D($this->line_station_table_name)->data(array('line_id'=>$id,'station_id'=>$v['stationId'],'sort'=>$v['sort']))->add();
            }
        }
        

        return $this->frame_submit_tips(1,'更新成功！');
       
    }
    
    
    public function destroyLine()
    {
        if( D($this->line_table_name)->destroy($_POST['id']) ) {
            
            $this->success('删除成功！');
        } else {
            
            $this->error('删除失败！请重试~');
        }
        
    }
    
    
    public function station()
    {
        $stations = D($this->station_table_name)->all();
        
        foreach($stations as $key => $station) {
            $stations[$key]['city'] = D('Area')->name($station['city_id']);
        }
//         dump($stations);
        $this->assign('stations', $stations);
        $this->display();
    }
    
    public function createStation()
    {
        $this->display();
    }
    
    public function saveStation()
    {
        list($long, $lat) = explode(',', $_POST['long_lat']);
        
        $data['name'] = $_POST['name'];
        $data['first_word'] = strtoupper($_POST['first_word']);
        $data['long'] = $long;
        $data['lat'] = $lat;
        $data['status'] = $_POST['status'];
        $data['city_id'] = $_POST['city_id'];
        $data['province_id'] = $_POST['province_id'];
         
        $result = D($this->station_table_name)->add($data);
    
        if($result) {
            $this->frame_submit_tips(1,'添加成功！');
        } else {
            $this->frame_submit_tips(0,'添加失败！请重试~');
        }
    }
    
    public function editStation()
    {
        $info = D($this->station_table_name)->info($_GET['id']);
        $this->assign('info', $info);
        $this->display();
    }
    

    public function updateStation()
    {
        $id = intval($_POST['id']);
        list($long, $lat) = explode(',', $_POST['long_lat']);
        
        $data['name'] = $_POST['name'];
        $data['first_word'] = strtoupper($_POST['first_word']);
        $data['long'] = $long;
        $data['lat'] = $lat;
        $data['status'] = $_POST['status'];
        $data['city_id'] = $_POST['city_id'];
        $data['province_id'] = $_POST['province_id'];
    
        $result = D($this->station_table_name)->where( array('id' => $id) )->save($data);
    
        if($result) {
            return $this->frame_submit_tips(1,'更新成功！');
        } else {
            return $this->frame_submit_tips(0,'更新失败！请重试~');
        }
    }
    
    
    public function destroyStation()
    {
        if( D($this->station_table_name)->destroy($_POST['id']) ) {
    
            $this->success('删除成功！');
        } else {
    
            $this->error('删除失败！请重试~');
        }
    }
    
    public function apiStation()
    {
    	$stations = D($this->station_table_name)->search($_POST['name']);
    	
    	$html.="<option value='xz'>== 请选择站点 ==</option>";

    	if($stations){
    		foreach ($stations as $k => $v) {
    			$html.="<option value='".$v['id']."'>".D('Area')->name($v['city_id']) .' '. $v['name']."</option>";
    		}
    	}
    		
//     	$html.="<option value='tj'>添加站点</option>";

    	exit(json_encode(array('data'=>$html)));
    }
    
    public function apiLine()
    {
        $stations = D($this->line_table_name)->search($_POST['name']);
         
        $html.="<option value='xz'>== 请选择站点 ==</option>";
    
        if($stations){
            foreach ($stations as $k => $v) {
                $html.="<option value='".$v['id']."'>".D('Area')->name($v['city_id']) .' ' .$v['name']."</option>";
            }
        }
    
//         $html.="<option value='tj'>添加站点</option>";
    
        exit(json_encode(array('data'=>$html)));
    }
}