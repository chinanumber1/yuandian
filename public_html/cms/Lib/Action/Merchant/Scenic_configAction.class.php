<?php
/*
 * 系统配置
 *   Writers    hanlu
 *   BuildTime  2016/07/04 09:20
 */

class Scenic_configAction extends BaseAction{
	/* 景区设置 */
    public function index(){
		$database_merchant = D('Scenic_list');
		if(IS_POST){
			$condition_merchant['scenic_id'] = $_POST['scenic_id'];
			if(empty($_POST['scenic_name'])){
				$this->error('请填写管理员');
			}
			if(empty($_POST['province_id'])){
				$this->error('请选择省');
			}
			if(empty($_POST['city_id'])){
				$this->error('请选择市');
			}
			if(empty($_POST['area_id'])){
				$this->error('请选择区');
			}
			if(empty($_POST['long_lat'])){
				$this->error('请选择店铺经纬度');
			}
			if(empty($_POST['sugest_time'])){
				$this->error('请输入建议游玩时间');
			}
			if(empty($_POST['scenic_phone'])){
				$this->error('请输入联系人电话');
			}
			if(empty($_POST['scenic_brief'])){
				$this->error('请输入景区介绍');
			}
			if(empty($_POST['scenic_explain'])){
				$this->error('请输入门票预定说明');
			}
			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
			if(empty($_POST['scenic_address'])){
				$this->error('请填写详细地址');
			}
			if(empty($_POST['scenic_intr'])){
				$this->error('请输入商家描述信息');
			}
			//if(!empty($_POST['new_pass'])){
//				$now_merchant = $database_merchant->field('`scenic_pwd`')->where($condition_merchant)->find();
//				if(md5($_POST['old_pass']) != $now_merchant['scenic_pwd']){
//					$this->error('原密码输入错误');
//				}else if(strlen($_POST['new_pass']) < 6){
//					$this->error('新密码最少6个字符');
//				}else if($_POST['new_pass'] != $_POST['re_pass']){
//					$this->error('两次新密码输入不一致，请重新输入');
//				}else{
//					$data_merchant['scenic_pwd'] = md5($_POST['new_pass']);
//				}
//			}
			$data_merchant['scenic_phone'] = $_POST['scenic_phone'];
			$data_merchant['scenic_title'] = $_POST['scenic_title'];
			$data_merchant['scenic_pic'] = implode(';',$_POST['pic']);
			$data_merchant['scenic_brief'] = $_POST['scenic_brief'];
			$data_merchant['scenic_name'] = $_POST['scenic_name'];
			$data_merchant['scenic_intr'] = $_POST['scenic_intr'];
			$data_merchant['scenic_explain'] = $_POST['scenic_explain'];
			$data_merchant['money'] = $_POST['money'];
			$data_merchant['sugest_time'] = $_POST['sugest_time'];
			$data_merchant['level'] = $_POST['level'];
			$data_merchant['start_time'] = $_POST['start_time']==0?'00:00:00':$_POST['start_time'].':00';	//开始时间
			$data_merchant['end_time'] = $_POST['end_time']==0?'00:00:00':$_POST['end_time'].':00';	//结束时间

			$data_merchant['notice_time'] = $_POST['notice_time']==0?'00:00:00':$_POST['notice_time'].':00';	//短信发送时间
			$data_merchant['open_notice'] = $_POST['open_notice'];

			$data_merchant['is_parking'] = $_POST['is_parking'];
			$data_merchant['parking_price'] = $_POST['parking_price'];
//			$data_merchant['is_broadcast'] = $_POST['is_broadcast'];
			$data_merchant['is_guide'] = $_POST['is_guide'];
			$data_merchant['guide_price'] = $_POST['guide_price'];
			$data_merchant['scenic_status'] = $_POST['scenic_status'];
			$data_merchant['scenic_address'] = $_POST['scenic_address'];
			$data_merchant['province_id'] = $_POST['province_id'];
			$data_merchant['city_id'] = $_POST['city_id'];
			$data_merchant['area_id'] = $_POST['area_id'];
			$data_merchant['update_time'] = $_SERVER['REQUEST_TIME'];
			$long_lat = explode(',',$_POST['long_lat']);
			$data_merchant['long'] = $long_lat[0];
			$data_merchant['lat'] = $long_lat[1];
			if($database_merchant->where($condition_merchant)->data($data_merchant)->save()){
				$this->success('保存成功！');
			}else{
				$this->error('保存失败！请检查是否有修改过内容后重试');
			}
		}else{
			$condition_merchant['company_id'] = $this->merchant_session['mer_id'];
			$now_merchant = $database_merchant->field(true,'scenic_pwd')->where($condition_merchant)->find();
			if(!empty($now_merchant['scenic_pic'])){
				$merchant_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$now_merchant['scenic_pic']);
				foreach($tmp_pic_arr as $key=>$value){
					$now_merchant['pic'][$key]['title'] = $value;
					$now_merchant['pic'][$key]['url'] = $merchant_image_class->get_image_by_path($value,$this->config['site_url'],'config','1');
				}
			}
			if($now_merchant['long'] == 0){
				unset($now_merchant['long']);
			}
			if($now_merchant['lat'] == 0){
				unset($now_merchant['lat']);
			}
			$this->assign('now_merchant',$now_merchant);
			$this->display();
		}
    }
	# 上传图片
	public function ajax_upload_pic(){
		if($_FILES['imgFile']['error'] != 4){
			$param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $this->config['group_pic_width'];
            $param['thumbMaxHeight'] = $this->config['group_pic_height'];
            $param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'scenic/config',1,$param);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$merchant_image_class = new scenic_image();
				$url = $merchant_image_class->get_image_by_path($title,$this->config['site_url'],'config','-1');
				exit(json_encode(array('error' => 0, 'url' => $url['image'], 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	# 删除图片
	public function ajax_del_pic(){
		$merchant_image_class = new scenic_image();
		$merchant_image_class->del_image_by_path($_POST['path']);
	}
}