<?php
/*
 * 广告管理
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/06 16:47
 *
 */
class AdverAction extends BaseAction{
	public function index(){
		$database_adver_category  = D('Adver_category');
		$category_list = $database_adver_category->field(true)->order('`cat_id` ASC')->select();
		$this->assign('category_list',$category_list);
		$this->display();
	}
	public function cat_add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	public function cat_modify(){
		if(IS_POST){
			$database_adver_category  = D('Adver_category');
			if($database_adver_category->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cat_edit(){
		$this->assign('bg_color','#F3F3F3');
		$now_category = $this->frame_check_get_category($_GET['cat_id']);
		$this->assign('now_category',$now_category);

		$this->display();
	}
	public function cat_amend(){
		if(IS_POST){
			$database_adver_category  = D('Adver_category');
			if($database_adver_category->data($_POST)->save()){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cat_del(){
		if(IS_POST){
			$database_adver_category  = D('Adver_category');
			$condition_adver_category['cat_id'] = $_POST['cat_id'];
			if($database_adver_category->where($condition_adver_category)->delete()){
				//删除所有广告
				$database_adver = D('Adver');
				$condition_adver['cat_id'] = $now_category['cat_id'];
				$adver_list = $database_adver->field(true)->where($condition_adver)->order('`id` DESC')->select();
				foreach($adver_list as $key=>$value){
					unlink('./upload/adver/'.$value['pic']);
				}
				$database_adver->where($condition_adver)->delete();

				$this->clearCache();
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}

    public function adver_list()
    {
        $now_category = $this->check_get_category($_GET['cat_id']);
        $this->assign('now_category', $now_category);
        $many_city = $this->config['many_city'];
        $database_adver = D('Adver');
        $condition_adver['cat_id'] = $now_category['cat_id'];
        
        if ($this->system_session['area_id']) {
            $area_id = $this->system_session['area_id'];
            if ($this->system_session['level'] == 1) {
                $temp = M('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
                $area_id = $temp['area_pid'];
            }
            $condition_adver['city_id'] = $area_id;
        }
        $adver_list = $database_adver->field(true)->where($condition_adver)->order('`id` DESC')->select();
        if ($many_city == 1 && $adver_list) {
            foreach ($adver_list as &$v) {
                $city = M('Area')->field('area_name')->where(array('area_id' => $v['city_id']))->find();
                if (empty($city)) {
                    $v['city_id'] = '通用';
                } else {
                    $v['city_id'] = $city['area_name'];
                }
            }
        }
        $this->assign('adver_list', $adver_list);
        $this->assign('many_city', $many_city);
        $this->display();
    }
	public function adver_add(){
		$many_city	=	$this->config['many_city'];
		$this->assign('many_city',$many_city);
		$this->assign('bg_color','#F3F3F3');
		$now_category = $this->frame_check_get_category($_GET['cat_id']);
		$this->assign('now_category',$now_category);

		$this->display();
	}
	public function adver_modify(){
		$image = D('Image')->handle($this->system_session['id'], 'adver', 0, array('size' => 10), false);
		if (!$image['error']) {
			$_POST = array_merge($_POST, str_replace('/upload/adver/', '', $image['url']));
		} else {
			$this->frame_submit_tips(0, $image['message']);
		}
		if($_POST['currency'] == 1 || empty($_POST['province_idss'])){
			$_POST['province_id']	=	0;
			$_POST['city_id']	=	0;
		}else{
			$_POST['province_id']	=	$_POST['province_idss'];
			$_POST['city_id']	=	$_POST['city_idss'];
			unset($_POST['province_idss'],$_POST['city_idss']);
		}
		$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
		$_POST['url'] = htmlspecialchars_decode($_POST['url']);
		$database_adver = D('Adver');
		if($id = $database_adver->data($_POST)->add()){
			D('Image')->update_table_id('/upload/adver/' . $_POST['pic'], $id, 'adver');
			
			$this->clearCache();
			
			$this->frame_submit_tips(1,'添加成功！');
		}else{
			$this->frame_submit_tips(0,'添加失败！请重试~');
		}
	}
	public function adver_edit(){
		$many_city	=	$this->config['many_city'];
		$this->assign('many_city',$many_city);
		$this->assign('bg_color','#F3F3F3');

		$database_adver = D('Adver');
		$condition_adver['id'] = $_GET['id'];
		$now_adver = $database_adver->field(true)->where($condition_adver)->find();
		if(empty($now_adver)){
			$this->frame_error_tips('该广告不存在！');
		}
		$this->assign('now_adver',$now_adver);

		$now_category = $this->frame_check_get_category($now_adver['cat_id']);
		$this->assign('now_category',$now_category);

		$this->display();
	}

	public function adver_amend(){
		$database_adver = D('Adver');
		$condition_adver['id'] = $_POST['id'];
		$now_adver = $database_adver->field(true)->where($condition_adver)->find();

		if($_FILES['pic']['error'] != 4){
			$image = D('Image')->handle($this->system_session['id'], 'adver', 0, array('size' => 10), false);
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/adver/', '', $image['url']));
			} else {
				$this->frame_submit_tips(0, $image['message']);
			}
		}
		if($_POST['currency'] == 1){
			$_POST['province_id']	=	0;
			$_POST['city_id']	=	0;
		}else{
			$_POST['province_id']	=	$_POST['province_idss'];
			$_POST['city_id']	=	$_POST['city_idss'];
			unset($_POST['province_idss'],$_POST['city_idss']);
		}
		$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
		$_POST['url'] = htmlspecialchars_decode($_POST['url']);
		$database_adver = D('Adver');
		if($database_adver->data($_POST)->save()){
			D('Image')->update_table_id('/upload/adver/' . $_POST['pic'], $_POST['id'], 'adver');
			$this->clearCache();
			if($_POST['pic']){
				unlink('./upload/adver/'.$now_adver['pic']);
			}
			$this->frame_submit_tips(1,'编辑成功！');
		}else{
			$this->frame_submit_tips(0,'编辑失败！请重试~');
		}
	}

	public function adver_del(){
		$database_adver = D('Adver');
		$condition_adver['id'] = $_POST['id'];
		$now_adver = $database_adver->field(true)->where($condition_adver)->find();
		if($database_adver->where($condition_adver)->delete()){
			unlink('./upload/adver/'.$now_adver['pic']);
			$this->clearCache();
			$this->success('删除成功');
		}else{
			$this->error('删除失败！请重试~');
		}
	}
	# 获取省
	public function ajax_province(){
		$database_area = D('Area');
		$condition_area['area_type'] = 1;
		$condition_area['is_open'] = 1;
		$province_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		if(count($province_list) == 1){
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
	# 获取市
	public function ajax_city(){
		$database_area = D('Area');
		$condition_area['area_pid'] = intval($_POST['id']);
		$condition_area['is_open'] = 1;
		$city_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		if(count($city_list) == 1 && !$_POST['type']){
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

	/*
	 * app 全屏广告
	 * */

	public function app_fullscreen_adver(){
		import('@.ORG.system_page');
		$count = M('App_fullscreen_adver')->count();
		$p = new Page($count,20);
		$adver_list = M('App_fullscreen_adver')->limit($p->firstRow,$p->listRows)->select();
		$this->assign('adver_list',$adver_list);
		$this->assign('pagebar',$p->show());
		$this->display();
	}

	public function app_fullscreen_add(){
		if(IS_POST){

			if(empty($_POST['name']) || empty($_POST['ios_pic_s']) || empty($_POST['ios_pic_b']) || empty($_POST['android_pic']) || empty($_POST['url'])|| empty($_POST['begin_time'])|| empty($_POST['end_time'])|| empty($_POST['play_time'])){
				$this->frame_submit_tips(0,'数据不全');
			}
			if($_POST['play_time']<3 || $_POST['play_time']>9){
				$this->frame_submit_tips(0,"播放时间请设置3至9秒的范围");
			}
			$_POST['begin_time'] =strtotime($_POST['begin_time']." 00:00:00");
			$_POST['end_time'] = strtotime($_POST['end_time']." 23:59:59");
			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->frame_submit_tips(0,"结束时间应大于开始时间");
			}
			$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
			if(M('App_fullscreen_adver')->add($_POST)){
				$this->frame_submit_tips(1,'添加成功');
			}else{
				$this->frame_submit_tips(0,'添加失败');
			}
		}else{
			$this->display();
		}
	}

	public function app_fullscreen_edit(){
		if(IS_POST){
			if(empty($_POST['name']) || empty($_POST['ios_pic_s']) || empty($_POST['ios_pic_b']) || empty($_POST['android_pic']) || empty($_POST['url']) || empty($_POST['begin_time'])|| empty($_POST['end_time'])|| empty($_POST['play_time'])){
				$this->error('数据不全');
			}
			$_POST['begin_time'] =strtotime($_POST['begin_time']." 00:00:00");
			$_POST['end_time'] = strtotime($_POST['end_time']." 23:59:59");
			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->frame_submit_tips(0,"结束时间应大于开始时间");
			}
			$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
			if(M('App_fullscreen_adver')->where(array('id'=>$_POST['id']))->save($_POST)){
				$this->frame_submit_tips(1,'编辑成功');
			}else{

				$this->frame_submit_tips(0,'编辑失败');
			}
		}else{
			$now_adver  =M('App_fullscreen_adver')->where(array('id'=>$_GET['id']))->find();
			$this->assign('now_adver',$now_adver);
			$this->display();
		}
	}

	public function app_fullscreen_del(){
		if(IS_POST){
			$database_adver_category  = D('App_fullscreen_adver');
			$condition_adver_category['id'] = $_POST['id'];
			if($database_adver_category->where($condition_adver_category)->delete()){
				$this->success('删除成功');
			}else{
				$this->frame_submit_tips(0,'删除失败！请重试~');
			}
		}else{
			$this->frame_submit_tips(0,'非法提交,请重新提交~');
		}
	}

	protected function get_category($cat_id){
		$database_adver_category  = D('Adver_category');
		$condition_adver_category['cat_id'] = $cat_id;
		$now_category = $database_adver_category->field(true)->where($condition_adver_category)->find();
		return $now_category;
	}
	protected function frame_check_get_category($cat_id){
		$now_category = $this->get_category($cat_id);
		if(empty($now_category)){
			$this->frame_error_tips('分类不存在！');
		}else{
			return $now_category;
		}
	}
	protected function check_get_category($cat_id){
		$now_category = $this->get_category($cat_id);
		if(empty($now_category)){
			$this->error_tips('分类不存在！');
		}else{
			return $now_category;
		}
	}
	public function clearCache(){
		import('ORG.Util.Dir');
        Dir::delDirnotself('./runtime');
	}
}