<?php
/*
 * 城市区域管理
 *
 */

class AreaAction extends BaseAction{
    
    private function allArea($areaId)
    {
        static $allAreas;
        if ($allAreas) {
            
        } else {
            $condition_area['is_open'] = array('neq', 2);
            $areaList = D('Area')->field(true)->where($condition_area)->order($order)->select();
            $tmpMap = array();
            foreach ($areaList as $area) {
                $tmpMap[$area['area_id']] = $area['area_pid'];
            }
            return array_reverse($this->ids($tmpMap, $areaId));//[父ID，子ID, 孙ID, ..., $sortId（指定的ID）]
        }
    }
    
    private function ids($idsArray, $areaId, $ids = array())
    {
        $ids[] = $areaId;
        if (isset($idsArray[$areaId]) && $idsArray[$areaId]) {
            return $this->ids($idsArray, $idsArray[$areaId], $ids);
        } else {
            return $ids;
        }
    }
    
	public function index(){
		$database_area = D('Area');
		if(!isset($_GET['type'])){
			if(!$this->config['many_city']){
				if($_GET['type'] != 4) $_GET['type'] = 3;
				$_GET['pid'] = !empty($_GET['pid']) ? $_GET['pid'] : $this->config['now_city'];
			}else{
				if($this->config['now_province']){
					if(empty($_GET['type'])) $_GET['type'] = 2;
					$_GET['pid'] = !empty($_GET['pid']) ? $_GET['pid'] : $this->config['now_province'];
				}else{
					if(empty($_GET['type'])) $_GET['type'] = 1;
					$_GET['pid'] = !empty($_GET['pid']) ? $_GET['pid'] : 0;
				}
			}
		}

		if ($this->system_session['area_id']) {
		    $allAreaIds = $this->allArea($_GET['pid']);

		    if (!in_array($this->system_session['area_id'], $allAreaIds)) {

				$tempArea = $database_area->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
//				$_GET['pid'] = $this->system_session['area_id'];
				$_GET['pid'] = $tempArea['area_pid'];
				$_GET['type'] = $tempArea['area_type'] ;
		    }
		}

		$condition_area['area_pid'] = $_GET['pid'];
		$tempArea['area_id'] && $condition_area['area_id'] = $tempArea['area_id'];
		$condition_area['area_type'] = $_GET['type'];
		$condition_area['is_open'] = array('neq',2);

		if($this->system_session['area_id']==$_GET['pid']||$this->system_session['area_id']=='0'){
			$this->assign('is_system',true);
		}

		$now_area = $database_area->field(true)->where(array('area_id'=>$_GET['pid']))->find();
		$this->assign('now_area',$now_area);

		if($_GET['type'] == 4){
			$order = '`area_sort` DESC,`is_open` DESC,`first_pinyin` ASC';
		}else{
			$order = '`area_sort` DESC,`is_open` DESC,`area_id` ASC';
		}
		$area_list = $database_area->field(true)->where($condition_area)->order($order)->select();
		$this->assign('area_list',$area_list);
		switch($_GET['type']){
			case 1:
				$now_type_str = '省份';
				break;
			case 2:
				$now_type_str = '城市';
				break;
			case 3:
				$now_type_str = '区域';
				break;
			default:
				$now_type_str = '商圈';
		}
		$this->assign('now_type_str',$now_type_str);
		if($_GET['type']==4){
			$circle_category = D('Circle_category')->getField('id,name');
			$this->assign('circle_category',$circle_category);
		}
        if (0 == $this->config['is_open_merchant_foodshop_discount']) {
            $this->assign('merchant_close','1');
        }
		$this->display();
    }
    //	商场列表
    public function area_market(){
		$database_area = D('Area_market');
		$area_id = !empty($_GET['pid']) ? $_GET['pid'] : 0;
		$now_area = M('Area')->field(true)->where(array('area_id'=>$area_id))->find();
		
		$area_market	=	$database_area->order('market_sort desc')->where(array('area_id'=>$area_id))->select();
		if($area_market){
			foreach($area_market as $k=>$v){
				$area_market[$k]['img']	=	$this->config['site_url'].$v['img'];
			}
		}
		$this->assign('now_area',$now_area);
		$this->assign('area_list',$area_market);
		$this->assign('area_id',$area_id);
		$this->display();
    }
    //	添加商场
    public function add_market(){
    	if(IS_POST){
    		$image = D('Image')->handle($_POST['area_id'], 'market', 0, array('size' => 10), false);
    		if (!$image['error']) {
				$_POST = array_merge($_POST,$image['url']);
			} else {
				$this->frame_submit_tips(0,$image['msg']);
			}
			$farea	=	M('Area')->field(array('area_pid'))->where(array('area_id'=>$_POST['area_id']))->find();
			$area	=	M('Area')->field(array('area_pid'))->where(array('area_id'=>$farea['area_pid']))->find();
			$_POST['city_id']	=	$area['area_pid'];
			$long	=	explode(",",$_POST['long_lat']);
			$_POST['long']	=	$long[0];
			$_POST['lat']	=	$long[1];
			unset($_POST['long_lat']);
			$add	=	M('Area_market')->add($_POST);
			if($add){
				$this->frame_submit_tips(1,'添加成功！');
			}else{
				$this->frame_submit_tips(0,'添加失败！请重试~');
			}
		}else{
			$this->assign('bg_color','#F3F3F3');
			$this->display();
		}
    }
    //	修改商场
    public function edit_market(){
    	if(IS_POST){
    		$image = D('Image')->handle($_POST['area_id'], 'market', 0, array('size' => 10), false);
    		if(!$image['error']) {
				$_POST = array_merge($_POST,$image['url']);
			}
			$long	=	explode(",",$_POST['long_lat']);
			$_POST['long']	=	$long[0];
			$_POST['lat']	=	$long[1];
			unset($_POST['long_lat']);
			$save	=	M('Area_market')->where(array('market_id'=>$_GET['market_id']))->data($_POST)->save();
			if($save){
				$this->frame_submit_tips(1,'添加成功！');
			}else{
				$this->frame_submit_tips(0,'添加失败！请重试~');
			}
		}else{
			$market		=	M('Area_market')->where(array('market_id'=>$_GET['market_id']))->find();
			$this->assign('bg_color','#F3F3F3');
			$this->assign('market',$market);
			$this->display();
		}
    }
    //	删除商场
    public function del_market(){
    	$del	=	M('Area_market')->where(array('market_id'=>$_POST['market_id']))->delete();
    	if($del){
			$this->success('删除成功！');
    	}else{
			$this->error('删除失败！请重试~');
    	}
    }
    //	介绍类型列表
    //public function add_market_type(){
//    	if(IS_POST){
//			$image = D('Image')->handle($_POST['area_id'], 'market', 0, array('size' => 10), false);
//    		if(!$image['error']) {
//				$_POST = array_merge($_POST,$image['url']);
//			}
//			$add	=	M('Area_market_type')->add($_POST);
//			if($add){
//				$this->success('添加成功！');
//			}else{
//				$this->error('添加失败！请重试~');
//			}
//    	}else{
//			$typeimg	=	M('Area_market_type')->field(array('pigcms_id','type_name','type_img'))->select();
//			if($typeimg){
//				foreach($typeimg as $k=>$v){
//					$typeimg[$k]['type_img']	=	$this->config['site_url'].$v['type_img'];
//				}
//			}
//			$this->assign('typeimg',$typeimg);
//			$this->display();
//    	}
//    }
	public function add(){
		$database_area =D('Area');
		$now_city = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
		import('ORG.Net.Http');
		$http = new Http();
		$url = "http://api.map.baidu.com/place/v2/search?query={$now_city['area_name']}&region={$now_city['area_name']}&output=json&ak=4c1bb2055e24296bbaef36574877b4e2";
		$result = $http->curlGet($url);
		$result = json_decode($result, true);
		$now_area['lng'] = $result['results'][0]['location']['lng'];
		$now_area['lat'] = $result['results'][0]['location']['lat'];
		$this->assign('now_area',$now_area);
		$this->assign('cat_list',$this->circle_category());

		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}

	public function circle_category_list(){
		$circle_category_list= M('Circle_category')->select();
		$this->assign('circle_category_list',$circle_category_list);
		$this->display();
	}

	public function circle_category_add(){
		if(IS_POST){
			$category_id = $_POST['category_id'];
			if($category_id){
				$result = M('Circle_category')->where(array('id'=>$category_id))->save($_POST);
			}else{
				$result =M('Circle_category')->add($_POST);
			}
			if($result ){
				$this->success('操作成功！');
			}else{
				$this->error('操作失败！');
			}
		}else{
			$category_id = $_GET['category_id'];
			$res = M('Circle_category')->where(array('id'=>$category_id))->find();
			$this->assign('res',$res);
			$this->display();
		}
	}

		public function circle_category_del(){
			if(IS_POST){
				$return = M('Circle_category')->where(array('id'=>$_POST['id']))->delete();
				import('ORG.Util.Dir');
				Dir::delDirnotself('./runtime');
				$this->success('删除成功！');
			}else{
				$this->error('非法提交,请重新提交~');
			}
		}

	public function modify(){
		if(IS_POST){
			$database_area = D('Area');
			$condition_area['area_url'] = $_POST['area_url'];
			if($database_area->where($condition_area)->find()){
				$this->error('数据库中已存在相同的网址标识，请更换。');
			}
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['lng'] = $long_lat[0];
			$_POST['lat'] = $long_lat[1];
			if($database_area->data($_POST)->add()){
				import('ORG.Util.Dir');
				Dir::delDirnotself('./runtime');
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function edit(){
		$database_area = D('Area');
		$this->assign('bg_color','#F3F3F3');
		$condition_area['area_id'] = $_GET['area_id'];
		$now_area = $database_area->field(true)->where($condition_area)->find();
		$tmpArea = floatval($now_area['area_lng']);
		if(empty($tmpArea) && $_GET['type'] == 4){
			$now_area_ = $database_area->where(array('area_id'=>$now_area['area_pid']))->find();
			$now_city = $database_area->where(array('area_id'=>$now_area_['area_pid']))->find();
			import('ORG.Net.Http');
			$http = new Http();
			$url = "http://api.map.baidu.com/place/v2/search?query={$now_city['area_name']}&region={$now_city['area_name']}&output=json&ak=4c1bb2055e24296bbaef36574877b4e2";
			$result = $http->curlGet($url);
			$result = json_decode($result, true);
			$now_area['area_lng'] = $result['results'][0]['location']['lng'];
			$now_area['area_lat'] = $result['results'][0]['location']['lat'];
			$now_area['notice'] = '您还没有定位，当前商圈定位为该城市中心，请重新定位';
		}

		if(empty($now_area)){
			$this->frame_error_tips('数据库中没有查询到该信息！');
		}
		$this->assign('now_area',$now_area);
		$this->assign('cat_list',$this->circle_category());
		$this->display();
	}
	public function circle_category(){
		$circle_category_list= M('Circle_category')->select();

		return $circle_category_list;
	}
	public function amend(){
		if(IS_POST){
			$database_area = D('Area');
			$condition_area['area_url'] = $_POST['area_url'];
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['area_lng'] = $long_lat[0];
			$_POST['area_lat'] = $long_lat[1];
			$area_type = $database_area->where(array('area_id'=>$_POST['area_id']))->field('area_type')->select();
			if($database_area->data($_POST)->save()){
				import('ORG.Util.Dir');
				Dir::delDirnotself('./runtime');
				if ($area_type[0]['area_type']>3) {
                    $circles = D('group_store')->where(array('circle_id'=>$_POST['area_id']))->field('group_id')->select();
					if(!empty($circles)){
						$tmp = '';
						foreach ($circles as $v) {
							$tmp.= $v['group_id'].',';
						}
						$tmp = substr($tmp, 0,-1);
						$where['group_id'] = array('in',$tmp);
						D('group')->where($where)->save(array('prefix_title'=>$_POST['area_name']));

                    }
                }
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function del(){
		if(IS_POST){
			$return = $this->recursive_del($_POST['area_id']);
			import('ORG.Util.Dir');
			Dir::delDirnotself('./runtime');
			$this->success('删除成功！');
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	/* 递归删除分类下的子分类且删除自己 */
	protected function recursive_del($area_id){
		$database_area = D('Area');
		$condition_area['area_pid'] = $area_id;
		$now_area = $database_area->field('`area_id`')->where($condition_area)->select();
		if(is_array($now_area)){
			foreach($now_area as $key=>$value){
				$this->recursive_del($value['area_id']);
			}
		}

		$condition_del_area['area_id'] = $area_id;
		$database_area->where($condition_del_area)->setField('is_open',2);
	}
	public function ajax_province(){
		$database_area = D('Area');
		$condition_area['area_type'] = 1;
		$condition_area['is_open'] = 1;
		//$this->system
		
		
		$province_id = 0;
		if ($this->system_session['area_id']) {
		    $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
		    if($now_area['area_type']==3){
		        $now_area = D('Area')->field(true)->where(array('area_id' => $now_area['area_pid']))->find();
// 		        $now_area = D('Area')->field(true)->where(array('area_id' => $now_area['area_pid']))->find();
		        $province_id = $now_area['area_pid'];
		    } elseif($now_area['area_type']==2){
// 		        $now_area = D('Area')->field(true)->where(array('area_id' => $now_area['area_pid']))->find();
		        $province_id = $now_area['area_pid'];
		    }elseif($now_area['area_type']==1){
		        $province_id = $this->system_session['area_id'];
		    }
		}
		if ($province_id) {
		    $condition_area['area_id'] = $province_id;
		    $province_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		} else {
		    $province_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		}

		if(count($province_list) == 1 ||  $this->system_session['area_type']>=1){
			$return['error'] = 2;
			$return['id'] = $province_list[0]['id'];
			$return['name'] = $province_list[0]['name'];
		}else if(!empty($province_list)){
			$return['error'] = 0;
			$return['list'] = $province_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '没有开启了的省份！请先开启。';
		}

		exit(json_encode($return));
	}
	public function ajax_city(){
		$database_area = D('Area');
		if($this->system_session['area_type']==1){
			$_POST['id'] = $this->system_session['area_id'];
		}

		$condition_area['area_pid'] = intval($_POST['id']);
		if($_POST['id']==0){
			$return['error'] = 0;
			$return['list'] = array();
			exit(json_encode($return));
		}
		$condition_area['is_open'] = 1;
		
		$city_id = 0;
		if ($this->system_session['area_id']) {
		    $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
		    if ($now_area['area_type'] == 3) {
		        $city_id = $now_area['area_pid'];
		    } elseif($now_area['area_type']==2){
		        $city_id = $this->system_session['area_id'];
		    }
		}
		
		if ($city_id && $this->system_session['area_id']!=$_POST['id']) {
		    $condition_area['area_id'] = $city_id;
		    $city_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		} else {
		    $city_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		}
		if(count($city_list) == 1 && !$_POST['type'] || $this->system_session['area_type']>1){
			$return['error'] = 2;
			$return['id'] = $city_list[0]['id'];
			$return['name'] = $city_list[0]['name'];
		}else if(!empty($city_list)){
			$return['error'] = 0;
			$return['list'] = $city_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '［ <b>'.$_POST['name'] .'</b> ］ 省份下没有已开启的城市！请先开启城市或删除此省份';
		}
		exit(json_encode($return));
	}
	public function ajax_area(){
		$database_area = D('Area');
		if($this->system_session['area_type']==2){
			$_POST['id'] = $this->system_session['area_id'];
		} elseif($this->system_session['area_type']==3){
		    $area = $database_area->field(true)->where(array('area_id' => $this->system_session['area_id']))->order('`area_sort` DESC,`area_id` ASC')->find();
		    $return['error'] = 2;
		    $return['id'] = $area['area_id'];
		    $return['name'] = $area['area_name'];
		    exit(json_encode($return));
		}
		$condition_area['area_pid'] = intval($_POST['id']);


		if($_POST['id']==0){
			$return['error'] = 0;
			$return['list'] = array();
			exit(json_encode($return));
		}
		$condition_area['is_open'] = 1;
		$area_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		if(!empty($area_list)){
			$return['error'] = 0;
			$return['list'] = $area_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '［ <b>'.$_POST['name'] .'</b> ］ 城市下没有已开启的区域！请先开启区域或删除此城市';
		}
		exit(json_encode($return));
	}
	public function ajax_circle(){
		$database_area = D('Area');
		$condition_area['area_pid'] = intval($_POST['id']);
		$condition_area['is_open'] = 1;
		$circle_list = $database_area->field('`area_id` `id`,`area_name` `name`,`first_pinyin`')->where($condition_area)->order('`area_sort` DESC,`first_pinyin` ASC')->select();
		if(!empty($circle_list)){
			$tmp_list = array();
			foreach($circle_list as $key=>$value){
				if(empty($tmp_list[$value['first_pinyin']])){
					$circle_list[$key]['name'] = $value['first_pinyin'].'. '.$value['name'];
					$tmp_list[$value['first_pinyin']] = true;
				}else{
					$circle_list[$key]['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$value['name'];
				}
			}
			$return['error'] = 0;
			$return['list'] = $circle_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '［ <b>'.$_POST['name'] .'</b> ］ 区域下没有已开启的商圈！请先开启商圈或删除此区域';
		}
		exit(json_encode($return));
	}
	public function ajax_market(){
		$database_area = D('Area_market');
		$condition_area['area_id'] = intval($_POST['id']);
		$condition_area['is_open'] = 1;
		$circle_list = $database_area->field('`market_id` `id`,`market_name` `name`')->where($condition_area)->order('`market_sort` DESC')->select();
		if(!empty($circle_list)){
			//$tmp_list = array();
//			foreach($circle_list as $key=>$value){
//				if(empty($tmp_list[$value['first_pinyin']])){
//					$circle_list[$key]['name'] = $value['first_pinyin'].'. '.$value['name'];
//					$tmp_list[$value['first_pinyin']] = true;
//				}else{
//					$circle_list[$key]['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$value['name'];
//				}
//			}
			$return['error'] = 0;
			$return['list'] = $circle_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '［ <b>'.$_POST['name'] .'</b> ］ 商圈下没有已开启的商场！请先开启商场或删除此区域';
		}
		exit(json_encode($return));
	}

	public function admin()
	{
		$area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
		$area = D('Area')->field(true)->where(array('area_id' => $area_id, 'is_open' => 1))->find();
		if (empty($area)) {
			$this->error('不存在的区域或该区域没有被开通,请查证后重新操作~');
		}
		if ($area['area_type'] == 2) {
			$this->assign('title', '城市');
		} elseif ($area['area_type'] == 3) {
			$this->assign('title', '区域');
		}
		$admin = D('Admin')->field(true)->where(array('area_id' => $area_id))->select();

		$this->assign('admin', $admin);
		$this->display();
	}

	public function addadmin()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$admin = D('Admin')->field(true)->where(array('id' => $id))->find();
		$this->assign('admin', $admin);
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}

	public function saveAdmin()
	{
		if(IS_POST){
			$database_area = D('Admin');
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			$account = htmlspecialchars($_POST['account']);
			if($database_area->where("`id`<>'{$id}' AND `account`='{$account}'")->find()){
				$this->error('此账号已存在，请更换。');
			}
			unset($_POST['id']);

			$area_id = isset($_POST['area_id']) ? intval($_POST['area_id']) : 0;
			$area = D('Area')->field(true)->where(array('area_id' => $area_id, 'is_open' => 1))->find();
			if (empty($area)) {
				$this->error('不存在的区域或该区域没有被开通,请查证后重新操作~');
			}
			if ($area['area_type'] == 2) {
				$_POST['level'] = 3;
			} elseif ($area['area_type'] == 3) {
				$_POST['level'] = 1;
			}
			if ($id) {
				if ($_POST['pwd']) {
					$_POST['pwd'] = md5($_POST['pwd']);
				} else {
					unset($_POST['pwd']);
				}
				$database_area->where(array('id' => $id))->data($_POST)->save();
				$this->success('修改成功！');
			} else {
				if (empty($_POST['pwd'])) {
					$this->error('密码不能为空~');
				}
				$_POST['pwd'] = md5($_POST['pwd']);
				$_POST['menus'] = 9999;//区域管理员默认能使用概况
				if($database_area->data($_POST)->add()){
					$this->success('添加成功！');
				}else{
					$this->error('添加失败！请重试~');
				}
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}

	}
	
    public function deliver()
    {
        $area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
        $now_area = D('Area')->field(true)->where(array('area_id' => $area_id))->find();
        if(empty($now_area)){
            $this->frame_error_tips('数据库中没有查询到该信息！');
        }
        
        $deliverSet = D('Deliver_set')->field(true)->where(array('area_id' => $area_id))->find();
        if (empty($deliverSet)) {
            $deliverSet = array('delivertime_start' => '08:00', 'delivertime_stop' => '20:00', 'delivertime_start2' => '20:00', 'delivertime_stop2' => '23:30');
        } else {
            $deliverSet['delivertime_start'] = substr($deliverSet['delivertime_start'], 0, -3);
            $deliverSet['delivertime_stop'] = substr($deliverSet['delivertime_stop'], 0, -3);
            $deliverSet['delivertime_start2'] = substr($deliverSet['delivertime_start2'], 0, -3);
            $deliverSet['delivertime_stop2'] = substr($deliverSet['delivertime_stop2'], 0, -3);
        }
        $this->assign('deliverSet', $deliverSet);
        $this->assign('area_id', $area_id);
        $this->assign('bg_color','#F3F3F3');
        $this->display();
    }
    
    public function deliverAmend()
    {
        if(IS_POST){
            $area_id = isset($_POST['area_id']) ? intval($_POST['area_id']) : 0;
            $where = array('area_id' => $area_id);
            $now_area = D('Area')->field(true)->where($where)->find();
            if(empty($now_area)){
                $this->error('提交数据错误！');
            }
            $deliverSetDB = D('Deliver_set');
            
            if ($deliver = $deliverSetDB->field(true)->where($where)->find()) {
                unset($_POST['area_id']);
                if($deliverSetDB->where($where)->save($_POST)){
                    D('Area')->where($where)->save(array('is_deliver' => intval($_POST['status'])));
                    $this->success('修改成功！');
                } else {
                    $this->error('修改失败！请重试~');
                }
            } else {
                if ($deliverSetDB->add($_POST)) {
                    D('Area')->where($where)->save(array('is_deliver' => intval($_POST['status'])));
                    $this->success('设置成功！');
                } else {
                    $this->error('设置失败！请重试~');
                }
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
        
    }
	public  function ajax_area_center(){
		$area_id = $_POST['area_id'];


		$now_city = M('Area')->where(array('area_id'=>$area_id))->find();
		$url = 'http://api.map.baidu.com/place/v2/search?query='.$now_city['area_name'].'&region='.$now_city['area_name'].'&city_limit=true&output=json&ak=4c1bb2055e24296bbaef36574877b4e2';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		$result = json_decode($result, true);

//		return $result['results'][0]['location'];

		echo json_encode(array('errcode'=>0,'lng'=>$result['results'][0]['location']['lng'],'lat'=>$result['results'][0]['location']['lat']));

	}

}