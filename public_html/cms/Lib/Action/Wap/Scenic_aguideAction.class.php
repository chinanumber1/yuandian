<?php
/*
 * wap端前台页面----向导
 *   Writers    hanlu
 *   BuildTime  2016/07/08 15:00
 */
class Scenic_aguideAction extends BaseAction{
	# 向导列表
	public function index(){
		$this->display();
	}
	# 向导列表json
	public function index_json(){
		$page	=	$_POST['page'];
		$city_id	=	$this->config['scenic_city'];
		$list	=	D('Scenic_aguide')->city_get_all_aguide($page,$city_id);
		if($list){
			foreach($list as $k=>&$v){
				if(empty($v['avatar'])){
					$v['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
				}
				$v['guide_price']	=	intval($v['guide_price']);
				if($v['date'] != '0000-00-00'){
					$v['date']	=	D('User')->age($v['date']);
				}else{
					$v['date']	=	'保密';
				}
			}
		}else{
			$this->returnCode('40000001');
		}
		$this->returnCode(0,$list);
	}
	# 向导详情
	public function details(){
		$where['guide_id']	=	$_GET['guide_id'];
		if(empty($where)){
			$this->error_tips('请选择一个向导！',U('Scenic_aguide/index'));
		}
		$list	=	D('Scenic_aguide')->city_get_one_aguide($where);
		if($list){
			$user	=	D('User')->get_user($list['user_id']);
			if(empty($user['avatar'])){
				$list['avatar']	=	$this->config['site_url'].'/tpl/Wap/pure/static/scenic/images/user_avatar.jpg';
			}else{
				$list['avatar']	=	$user['avatar'];
			}
			$list['real_name']	=	$user['real_name'];
			$list['guide_price']	=	floatval($list['guide_price']);
			if($list['date'] != '0000-00-00'){
				$list['date']	=	D('User')->age($list['date']);
			}else{
				$list['date']	=	'保密';
			}
			$city_id	=	D('Area')->scenic_get_one_city($list['city_id'],false);
			$list['city_id']	=	$city_id['area_name'];
			$scenic_image_class = new scenic_image();
			$tmp_pic_arr = explode(';',$list['guide_pic']);
			foreach($tmp_pic_arr as $k=>$v){
				if(empty($v)){
					continue;
				}
				$list['pic'][$k] = $scenic_image_class->get_image_by_path($v,$this->config['site_url'],'aguide','s');
			}
			if($list['score_all'] == '0.0'){
				$score_mean	=	array('0','0','5');
				$list['score_mean']	=	'暂无评';
			}else{
				$score_mean = explode('.',$list['score_mean']);
				if($score_mean[1] == 0){
					$score_mean[2]	=	5-$score_mean[0];
				}else{
					$score_mean[2]	=	5-$score_mean[0]-1;
				}
			}
			$list['guide_intr']	=	str_replace("\n","<br>",$list['guide_intr']);
			if($list['user_id'] == $this->user_session['uid']){
				$this->assign('oneself',1);
			}
		}else{
			$this->error_tips('未找到向导！',U('Scenic_aguide/index'));
		}
		$this->assign('list',$list);
		$this->assign('score_mean',$score_mean);
		$this->display();
	}
	# 向导下单
	public function order(){
		$where['guide_id']	=	$_GET['guide_id'];
		$this->user_sessions();
		if(empty($where)){
			$this->error_tips('请选择一个向导！',U('Scenic_aguide/index'));
		}
		$list	=	D('Scenic_aguide')->city_get_one_aguide($where);
		if($list['user_id'] == $this->user_session['uid']){
			$this->error_tips('不能预定自己！');
		}
		if($list['guide_status'] != 1){
			$this->error_tips('向导休息了！');
		}
		if($list){
			$user	=	D('User')->get_user($list['user_id']);
			if(empty($user['avatar'])){
	            $list['avatar']   =   $this->config['site_url'] . '/static/images/user_avatar.jpg';
	        }else{
				$list['avatar']		=	$user['avatar'];
	        }
			$list['real_name']	=	$user['real_name'];
			$list['guide_price']	=	floatval($list['guide_price']);
			if($list['date'] == '0000-00-00'){
				$list['date']	=	'保密';
			}else{
				$list['date']	=	D('User')->age($list['date']);
			}
			$list['guide_pic']	=	$this->config['site_url'].$list['guide_pic'];
			if($list['score_all'] != '0.0'){
				$score_mean = explode('.',$list['score_mean']);
				if($score_mean[1] == 0){
					$score_mean[2]	=	5-$score_mean[0];
				}else{
					$score_mean[2]	=	5-$score_mean[0]-1;
				}
			}
		}else{
			$this->error_tips('未找到向导！',U('Scenic_aguide/index'));
		}
		$time	=	$_SERVER['REQUEST_TIME']+86400;
		$time	=	date('Y-m-d',$time);
		$this->assign('list',$list);
		$this->assign('time',$time);
		$this->assign('score_mean',$score_mean);
		$this->assign('user',$this->user_session);
		$scenic_family	=	D('Scenic_family')->get_all_list(array('user_id'=>$this->user_session['uid']));
		$this->assign('scenic_family',$scenic_family);
		$this->display();
	}
	# 生成订单
	public function go_pay(){
		$where['guide_id']	=	$_POST['guide_id'];
		$this->user_sessions_json();
		if(empty($where)){
			$this->returnCode('40000001');
		}
		$scenic_aguide	=	D('Scenic_aguide')->city_get_one_aguide($where);
		if(empty($scenic_aguide)){
			$this->returnCode('40000001');
		}
		$data	=	array(
			'start_time'	=>	$_POST['start_time'],	//开始时间
			'end_time'		=>	$_POST['end_time'],		//结束时间
			'gender'		=>	$_POST['gender'],		//性别
			'number'		=>	$_POST['number_day'],	//天数
			'phone'			=>	$_POST['phone'],	//电话
			'name'			=>	$_POST['name'],		//联系人
			'family_id'		=>	$_POST['family_id'],	//出行人ID
			'explain'		=>	$_POST['explain'],		//说明
			'user_id'		=>	$this->user_session['uid'],		//用户ID
			'guide_id'		=>	$scenic_aguide['guide_id'],		//向导ID
			'guide_price'	=>	$scenic_aguide['guide_price'],	//单价
			'total_price'	=>	$scenic_aguide['guide_price']*$_POST['number_day'],	//总价
			'create_time'	=>	$_SERVER['REQUEST_TIME'],
			'pay_status'	=>	1,
			'rela_status'	=>	0,
		);
		$add_aguide	=	D('Scenic_aguide')->add_aguide_order($data);
		$order_id	=	D('Scenic_aguide')->getLastInsID();
		if($add_aguide){
			$url	=	$this->config['site_url'].U('Scenic_pay/index',array('order_id'=>$order_id,'type'=>1));
			$this->returnCode(0,$url);
		}else{
			$this->returnCode('40000005');
		}
	}
	# 公共接口
	public function user_sessions(){
		if(empty($this->user_session)){
			$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
			redirect(U('Login/index',$location_param));
		}
	}
	public function user_sessions_json(){
		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}
	}
	/* 图片上传 */
    public function ajaxImgUpload(){
		$mulu=isset($_GET['ml']) ? trim($_GET['ml']):'aguide';
		$mulu=!empty($mulu) ? $mulu : 'aguide';
        $filename = trim($_POST['filename']);
        $img = $_POST[$filename];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $imgdata = base64_decode($img);
		$img_order_id = sprintf("%09d",$this->user_session['uid']);
		$rand_num = mt_rand(10,99).'/'.substr($img_order_id,0,3).'/'.substr($img_order_id,3,3).'/'.substr($img_order_id,6,3);
        $getupload_dir = "/upload/scenic/".$mulu."/" .$rand_num;
        $upload_dir = "." . $getupload_dir;
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $newfilename = $mulu.'_' . date('YmdHis') . '.jpg';
        $save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
		$save = file_put_contents($upload_dir . '/m_' . $newfilename, $imgdata);
		$save = file_put_contents($upload_dir . '/s_' . $newfilename, $imgdata);
        if ($save) {
            $this->dexit(array('error' => 0, 'data' => array('code' => 1, 'siteurl'=>$this->config['site_url'],'imgurl' =>$getupload_dir . '/' . $newfilename, 'msg' => '')));
        } else {
            $this->dexit(array('error' => 1, 'data' => array('code' => 0, 'url' => '', 'msg' => '保存失败！')));
        }
    }
	/* 图片上传 */
    public function ajaxWebUpload(){
		if ($_FILES['file']['error'] != 4) {
        	$width = '900,450';
        	$height = '500,250';
			$param = array('size' => 2);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $width;
            $param['thumbMaxHeight'] = $height;
            $param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->user_session['uid'], 'scenic/aguide', 1, $param);
			if ($image['error']) {
				exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
			} else {
				exit(json_encode(array('error' => 0, 'url' => $image['url']['file'], 'title' => $image['title']['file'])));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
    }
    /* json 格式封装函数 */
    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }
}
?>