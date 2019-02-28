<?php
/*
 * wap端前台页面----景区内部游玩
 *   Writers    hanlu
 *   BuildTime  2016/08/11 15:05
 */
class Scenic_insideAction extends BaseAction{
	# 语音播报
	public function index(){
		$this->user_sessions();
		$where['scenic_id']	=	$_GET['scenic_id'];
		$where['project_status']	=	1;
		$scenic_project	=	D('Scenic_project')->get_all_project($where);

		if($scenic_project){
			$scenic_image_class = new scenic_image();
			foreach($scenic_project as &$v){
				$v['project_pic'] = array_shift(explode(';',$v['project_pic']));
				$v['pic'] = $scenic_image_class->get_image_by_path($v['project_pic'],$this->config['site_url'],'project','s');
				$v['url'] = $this->config['site'].U('details',array('project_id'=>$v['project_id']));
				$v['project_conter_audio'] = mb_substr($v['project_conter_audio'],0,240).'    更多文字请查看文字描述';
				$v['mp3'] = 'http://tsn.baidu.com/text2audio?tex='.$v['project_conter_audio'].'&lan=zh&tok='.$this->voic_baidu['access_token'].'&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
				$v['project_conter'] = htmlspecialchars_decode($v['project_conter']);
				$details	=	D('Scenic_project')->get_one_project_details(array('project_id'=>$v['project_id']));
				if($details){
					$v['type']	=	1;
				}
			}
		}
		$this->assign('scenic_project',$scenic_project);
		$this->display();
	}
	# 语音播报详情
	public function details(){
		$where['project_id']	=	$_GET['project_id'];
		if(empty($where)){
			$this->error_tips('未找到项目');
		}
		$scenic_project_details	=	D('Scenic_project')->get_all_project_details($where);
		if($scenic_project_details){
			$scenic_image_class = new scenic_image();
			foreach($scenic_project_details as &$v){
				$v['pic'] = $scenic_image_class->get_image_by_path($v['project_pic'],$this->config['site_url'],'project','s');
				$v['project_conter'] = htmlspecialchars_decode($v['project_conter']);
				$v['mp3'] = 'http://tsn.baidu.com/text2audio?tex='.$v['project_conter_audio'].'&lan=zh&tok='.$this->voic_baidu['access_token'].'&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
			}
		}
		$this->assign('project_details',$scenic_project_details);
		$this->display();
	}
	# 景区内行程
	public function inside_list(){
		$this->user_sessions();
		$store_image_class = new scenic_image();
		# 景内地图
		$map_category	=	M('Scenic_map_category')->field(true)->where(array('scenic_id'=>$_GET['scenic_id'],'map_fid'=>0))->select();
		if(!empty($map_category)){
			foreach($map_category as &$v){
				$v['map_img'] = $store_image_class->get_image_by_path($v['map_img'],$this->config['site_url'],'map','1');
				$v['url']	=	U('inside_map',array('map_id'=>$v['map_id'],'scenic_id'=>$v['scenic_id']));
			}
		}
		# 景内推荐
		$com_category	=	M('Scenic_com_category')->field(true)->where(array('scenic_id'=>$_GET['scenic_id']))->select();
		if(!empty($com_category)){
			foreach($com_category as $k=>&$vv){
				$count	=	M('Scenic_com')->where(array('cat_id'=>$vv['cat_id'],'status'=>1))->count();
				if($count == 0){
					unset($com_category[$k],$count);
					continue;
				}
				$vv['cat_img'] = $store_image_class->get_image_by_path($vv['cat_img'],$this->config['site_url'],'com','1');
				$vv['url']	=	U('inside_com',array('cat_id'=>$vv['cat_id'],'scenic_id'=>$vv['scenic_id']));
			}
		}
		# 景内活动
		$action	=	M('Scenic_activity')->where(array('scenic_id'=>$_GET['scenic_id']))->count();
		# 语音播报
		$project	=	M('Scenic_project')->where(array('scenic_id'=>$_GET['scenic_id']))->count();
		$this->assign('map_category',$map_category);
		$this->assign('com_category',$com_category);
		$this->assign('action',$action);
		$this->assign('project',$project);
		$this->display();
	}
	# 景内地图
	public function inside_map(){
		if($_GET['map_id']){
			$all_map_cat = M('Scenic_map_category')->where(array('scenic_id'=>$_GET['scenic_id'],'map_id'=>$_GET['map_id'],'status'=>'1'))->select();
		}else{
			$all_map_cat = M('Scenic_map_category')->where(array('scenic_id'=>$_GET['scenic_id'],'map_fid'=>0,'status'=>'1'))->order('`is_hot_map` DESC,`map_sort` DESC')->limit(3)->select();
		}

		$scenic_image_class = new scenic_image();
		foreach($all_map_cat as &$map_cat){
			$map_cat['cat_list'] = M('Scenic_map_category')->where(array('map_fid'=>$map_cat['map_id'],'status'=>'1'))->order('`map_sort` DESC')->select();
			foreach($map_cat['cat_list'] as &$v){
				$image	=	$scenic_image_class->get_image_by_path($v['map_img'],$this->config['site_url'],'map');
				$v['map_img']	=	$image['image'];
				unset($image);
			}
		}
		$this->assign('all_map_cat',$all_map_cat);

		$this->display();
	}
	public function inside_map_lt(){
		$map_list = M('Scenic_map')->where(array('map_id'=>$_GET['map_id'],'status'=>'1'))->select();
		echo json_encode($map_list);
	}
	# 景内住宿
	public function inside_com(){
		$find	=	M('Scenic_com_category')->field(true)->where(array('cat_id'=>$_GET['cat_id']))->find();
		$this->assign('find',$find);
		$this->display();
	}
	public function inside_com_json(){
		$where['cat_id'] = $_POST['cat_id'];
		$where['status'] = 1;
		$scenic_activity	=	M('Scenic_com')->field(true)->where($where)->select();
		if($scenic_activity){
			$scenic_image_class = new scenic_image();
			foreach($scenic_activity as &$v){
				$v['com_img']	=	$scenic_image_class->get_image_by_path($v['com_img'],$this->config['site_url'],'com','s');
			}
		}else{
			$this->returnCode('40000032');
		}
		$this->returnCode(0,$scenic_activity);
	}
	# 景内活动
	public function inside_activity(){
		$where	=	array(
//			'activity_id'	=>	$_GET['scenic_id'],
			'start_time'	=>	array('elt',$_SERVER['REQUEST_TIME']),
		);
		M('Scenic_activity')->where($where)->setField('status','2');
		$this->display();
	}
	public function inside_activity_json(){
		$where['scenic_id'] = $_POST['scenic_id'];
		$where['status'] = $_POST['status'];
		$scenic_activity	=	D('Scenic_activity')->get_all_list($where,"{$_POST['page']},10");
		if($scenic_activity){
			$scenic_image_class = new scenic_image();
			foreach($scenic_activity as &$v){
				$tmp_pic_arr = explode(';',$v['activity_img']);
				$v['pic'] = $scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'activity','s');
				$v['start_time']	=	date('Y-m-d H:i',$v['start_time']);
				$is_enroll	=	D('Scenic_activity')->get_user_activity(array('activity_id'=>$v['activity_id'],'user_id'=>$this->user_session['uid'],'status'=>1));
				$v['is_enroll']	=	$is_enroll;
			}
		}else{
			$this->returnCode('40000024');
		}
		$this->returnCode(0,$scenic_activity);
	}
	# 景内过期活动
	public function inside_overdue_activity(){
		$this->display();
	}
	public function inside_overdue_activity_json(){
		$where['scenic_id'] = $_POST['scenic_id'];
		$where['status'] = $_POST['status'];
		$scenic_activity	=	D('Scenic_activity')->get_all_list($where,"{$_POST['page']},10");
		if($scenic_activity){
			$scenic_image_class = new scenic_image();
			foreach($scenic_activity as &$v){
				$tmp_pic_arr = explode(';',$v['activity_img']);
				$v['pic'] = $scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'activity','s');
				$v['start_time']	=	date('Y-m-d H:i',$v['start_time']);
			}
		}else{
			$this->returnCode('40000024');
		}
		$this->returnCode(0,$scenic_activity);
	}
	# 景内活动详情
	public function inside_activity_details(){
		$this->user_sessions();
		$where['activity_id']	=	$_GET['activity_id'];
		if(empty($where)){
			$this->error_tips('未找到此活动！');
		}
		$scenic_activity	=	D('Scenic_activity')->get_one_list($where);
		if($scenic_activity){
			$scenic_image_class = new scenic_image();
			$tmp_pic_arr = explode(';',$scenic_activity['activity_img']);
			foreach($tmp_pic_arr as $v){
				$pic[] = $scenic_image_class->get_image_by_path($v,$this->config['site_url'],'activity','s');
			}
			$scenic_activity['start_time']	=	date('Y-m-d H:i',$scenic_activity['start_time']);
			$is_enroll	=	D('Scenic_activity')->get_user_activity(array('activity_id'=>$where['activity_id'],'user_id'=>$this->user_session['uid'],'status'=>1));
			$scenic_activity['activity_content'] = htmlspecialchars_decode($scenic_activity['activity_content']);
		}
		$this->assign('activity',$scenic_activity);
		$this->assign('pic',$pic);
		$this->assign('is_enroll',$is_enroll);
		$this->display();
	}
	# 报名
	public function enroll_json(){
		$where['activity_id']	=	$_POST['activity_id'];
		$activity	=	D('Scenic_activity')->get_one_list($where);
		if($activity['start_time'] <= $_SERVER['REQUEST_TIME']){
			$this->returnCode('40000027');
			M('Scenic_activity')->where(array('activity_id'=>$activity['activity_id']))->setField('status','2');
		}
		$scenic_where['scenic_id']	=	$activity['scenic_id'];
		$scenic_where['ticket_time']	=	date('Y-n-j',$activity['start_time']);
		$scenic_order	=	D('Scenic_order')->one_order($scenic_where);
		if($scenic_order){
			$data = array(
				'activity_id'	=>	$_POST['activity_id'],
				'user_id'	=>	$this->user_session['uid'],
				'status'	=>	1,
				'add_time'	=>	$_SERVER['REQUEST_TIME'],
			);
			$activity_add	=	D('Scenic_activity')->add_activit($data);
			if($activity_add == 0){
				$this->returnCode('40000025');
			}
		}else{
			$this->returnCode('40000026');
		}
		$this->returnCode(0);
	}
	# 公共接口
	public function user_sessions(){
		if(empty($this->user_session)){
			$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
			redirect(U('Login/index',$location_param));
		}
	}
}
?>