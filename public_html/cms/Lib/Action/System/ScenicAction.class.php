<?php
class ScenicAction extends BaseAction{
	# 景点列表
	public function scenic_list(){
		$where		=	array();
		$searchstatus	=	$_GET['searchstatus'];
		$searchtype		=	$_GET['searchtype'];
		$keyword		=	$_GET['keyword'];
		if($keyword){
			$where[$searchtype]	=	array('like','%'.$keyword.'%');
		}
		if($searchstatus){
			$where['scenic_status']	=	$searchstatus;
		}
		import('@.ORG.system_page');
		$count_user = M('Scenic_list')->where($where)->count();
		$p = new Page($count_user, 20);
		$list	=	M('Scenic_list')->field(true)->limit($p->firstRow . ',' . $p->listRows)->where($where)->order('scenic_id desc')->select();
		foreach($list as &$v){
			$company_name	=	M('Merchant')->field('name')->where(array('mer_id'=>$v['company_id']))->find();
			$v['company_name']	=	$company_name['name'];
		}
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->assign('list', $list);
		$this->display();
	}
	# 景点新增
	public function scenic_add(){
		if(IS_POST){
			$condition_merchant	=	array(
				'company_id'	=>	$_POST['company_id'],		//公司ID
				'company_name'	=>	$_POST['company_name'],		//公司名
				'scenic_title'	=>	$_POST['scenic_title'],		//景区名
				'scenic_name'	=>	$_POST['scenic_name'],		//管理员
				'scenic_account'=>	$_POST['scenic_account'],	//账号
				'scenic_phone'	=>	$_POST['scenic_phone'],		//管理员手机
				'scenic_wchant'	=>	$_POST['scenic_wchant'],	//绑定微信号
				'level'			=>	$_POST['level'],			//景点星级
				'scenic_status'	=>	$_POST['scenic_status'],	//景点状态
				'is_parking'	=>	$_POST['is_parking'],		//车位状态
				'is_hot'		=>	$_POST['is_hot'],			//景区热门
				'panorama_map'	=>	$_POST['panorama_map'],		//开启地图
				'is_guide'		=>	$_POST['is_guide'],			//景内导游
				'province_id'	=>	$_POST['province_idss'],		//省
				'city_id'		=>	$_POST['city_idss'],			//市
				'area_id'		=>	$_POST['area_idss'],			//区
				'scenic_address'=>	$_POST['scenic_address'],	//地址
				'add_time'		=>	$_SERVER['REQUEST_TIME'],	//注册时间
				'add_ip'		=>	get_client_ip(1),			//注册IP
				'scenic_pwd'	=>	md5($_POST['scenic_pwd']),	//密码
			);
			$add	=	M('Scenic_list')->data($condition_merchant)->add();
			if($add){
				$this->success('新增成功！');
			}else{
				$this->error('新增失败！请重试~');
			}
		}else{
			$company_id	=	$_GET['company_id'];
			$company	=	M('Scenic_company')->field(true)->where(array('company_id'=>$company_id))->find();
			$this->assign('company', $company);
			$this->display();
		}
	}
	# 景点修改
	public function scenic_edit(){
		if(IS_POST){
			$scenic_id	=	$_POST['scenic_id'];
			$find	=	M('Scenic_list')->field(true)->where(array('scenic_id'=>$scenic_id))->find();
			if(empty($find)){
				$this->error('未找到景点');
			}
			$condition_merchant	=	array(
				'scenic_title'	=>	$_POST['scenic_title'],		//景区名
				'scenic_name'	=>	$_POST['scenic_name'],		//管理员
				'scenic_phone'	=>	$_POST['scenic_phone'],		//管理员手机
				'level'			=>	$_POST['level'],			//景点星级
				'scenic_status'	=>	$_POST['scenic_status'],	//景点状态
				'is_parking'	=>	$_POST['is_parking'],		//车位状态
				'panorama_map'	=>	$_POST['panorama_map'],		//开启地图
				'is_hot'		=>	$_POST['is_hot'],			//开启热门
				'is_guide'		=>	$_POST['is_guide'],			//景内导游
				'province_id'	=>	$_POST['province_idss'],	//省
				'city_id'		=>	$_POST['city_idss'],		//市
				'area_id'		=>	$_POST['area_idss'],		//区
				'scenic_address'=>	$_POST['scenic_address'],	//地址
				'spread_rate'	=>	$_POST['spread_rate'],		//佣金比例
			);
			$save	=	M('Scenic_list')->where(array('scenic_id'=>$scenic_id))->data($condition_merchant)->save();
			if($save){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！请重试~');
			}
		}else{
			$scenic_id	=	$_GET['scenic_id'];
			$find	=	M('Scenic_list')->field(true)->where(array('scenic_id'=>$scenic_id))->find();
			if($find){
				$company	=	M('Merchant')->field('name')->where(array('mer_id'=>$find['company_id']))->find();
			}else{
				$this->error('未找到景区~');
			}
			$this->assign('find', $find);
			$this->assign('company', $company);
			$this->display();
		}
	}
	# 查看金额明细
	//public function money_list() {
//        $this->assign('bg_color', '#F3F3F3');
//        $database_user_money_list = D('Scenic_money_list');
//        $condition_user_money_list['scenic_id'] = intval($_GET['scenic_id']);
//        $count = $database_user_money_list->where($condition_user_money_list)->count();
//        import('@.ORG.system_page');
//        $p = new Page($count, 15);
//        $money_list = $database_user_money_list->field(true)->where($condition_user_money_list)->order('`time` DESC')->select();
//        $this->assign('pagebar', $p->show());
//        $this->assign('money_list', $money_list);
//        $this->display();
//    }
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
	# 获取区
	public function ajax_area(){
		$database_area = D('Area');
		$condition_area['area_pid'] = intval($_POST['id']);
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
	# 向导列表
	public function aguide(){
		$where			=	array();
		$searchstatus	=	$_GET['searchstatus'];
		$searchtype		=	$_GET['searchtype'];
		$keyword		=	$_GET['keyword'];
		if($keyword){
			$where[$searchtype]	=	array('like','%'.$keyword.'%');
		}
		if($searchstatus){
			$where['guide_status']	=	$searchstatus;
		}
		$count_user = M('Scenic_aguide')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 20);
		$list	=	M('Scenic_aguide')->field(true)->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('guide_id desc')->select();
		if($list){
			foreach($list as &$v){
				$v['guide_price']	=	intval($v['guide_price']);
			}
		}
		$this->assign('list', $list);
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->display();
	}
	# 修改向导
	public function aguide_edit(){
		if(IS_POST){
			$where['guide_id']	=	$_POST['guide_id'];
			if(empty($where['guide_id'])){
				$this->error('未找到向导');
			}
			$condition_merchant	=	array(
				'guide_status'	=>	$_POST['guide_status'],		//状态
			);
			$save	=	M('Scenic_aguide')->where($where)->data($condition_merchant)->save();
			if($save){
				$this->frame_submit_tips(1,'修改成功！');
			}else{
				$this->frame_submit_tips(0,'修改失败！请重试~');
			}
		}else{
			$where['guide_id']	=	$_GET['guide_id'];
			if(empty($where['guide_id'])){
				$this->error('未找到向导');
			}
			$list	=	M('Scenic_aguide')->field(true)->where($where)->find();
			if(!empty($list)){
				$merchant_image_class = new scenic_image();
				$list['guide_card_img'] = $merchant_image_class->get_image_by_path($list['guide_card_img'],$this->config['site_url'],'aguide','1');
				$list['guide_card_back_img'] = $merchant_image_class->get_image_by_path($list['guide_card_back_img'],$this->config['site_url'],'aguide','1');
			}
			$province	=	M('Area')->field(true)->where(array('area_id'=>$list['province_id']))->find();
			$city	=	M('Area')->field(true)->where(array('area_id'=>$list['city_id']))->find();
			$list['province_id']	=	$province['area_name'];
			$list['city_id']	=	$city['area_name'];
			$this->assign('list', $list);
			$this->display();
		}
	}
	# 审核向导
	public function aguide_verify(){
		if(IS_POST){
			$where['guide_id']	=	$_POST['guide_id'];
			if(empty($where['guide_id'])){
				$this->frame_submit_tips(0,'未找到向导');
			}
			$one_guide	=	M('Scenic_aguide')->field(true)->where($where)->find();
			if(empty($one_guide)){
				$this->frame_submit_tips(0,'未找到向导');
			}
			$authentication	=	M('User_authentication')->field(true)->where(array('uid'=>$_POST['user_id']))->find();
			if(empty($authentication)){
				$this->frame_submit_tips(0,'这个用户未申请实名认证');
			}
			if($authentication['authentication_status'] == 0){
				$this->frame_submit_tips(0,'这个用户的实名认证未审核，请先审核实名认证');
			}else if($authentication['authentication_status'] == 2){
				$this->frame_submit_tips(0,'这个用户的实名认证未通过~');
			}
			$condition_merchant	=	array(
				'update_time'	=>	$_SERVER['REQUEST_TIME'],	//更新时间
				'guide_status'	=>	$_POST['guide_status'],		//状态
				'guide_remarks'	=>	$_POST['guide_remarks'],	//审核备注
			);
			$save	=	M('Scenic_aguide')->where($where)->data($condition_merchant)->save();
			if($save){
				$this->frame_submit_tips(1,'审核成功！');
			}else{
				$this->frame_submit_tips(0,'审核失败');
			}
		}else{
			$where['guide_id']	=	$_GET['guide_id'];
			if(empty($where['guide_id'])){
				$this->error('未找到向导');
			}
			$list	=	M('Scenic_aguide')->field(true)->where($where)->find();
			if(!empty($list)){
				$merchant_image_class = new scenic_image();
				$list['guide_card_img'] = $merchant_image_class->get_image_by_path($list['guide_card_img'],$this->config['site_url'],'aguide','1');
				$list['guide_card_back_img'] = $merchant_image_class->get_image_by_path($list['guide_card_back_img'],$this->config['site_url'],'aguide','1');
			}
			$province	=	M('Area')->field(true)->where(array('area_id'=>$list['province_id']))->find();
			$city	=	M('Area')->field(true)->where(array('area_id'=>$list['city_id']))->find();
			$list['province_id']	=	$province['area_name'];
			$list['city_id']	=	$city['area_name'];
			$this->assign('list', $list);
			$this->display();
		}
	}
	# 结伴列表
	public function mate(){
		$count_user = M('Scenic_mate')->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 20);
		$list	=	M('Scenic_mate')->field(true)->limit($p->firstRow . ',' . $p->listRows)->order('mate_id desc')->select();
		foreach($list as &$v){
			$Scenic_area	=	D('Area');
			$province_id	=	$Scenic_area->scenic_get_one_city($v['province_id']);
			$v['province_id']	=	$province_id['area_name'];
			$city_id		=	$Scenic_area->scenic_get_one_city($v['city_id']);
			$v['city_id']	=	$city_id['area_name'];
			$scenic_id		=	D('Scenic_list')->get_one_list(array('scenic_id'=>$v['scenic_id']));
			$v['scenic_id']	=	$scenic_id['scenic_title'];
		}
		$this->assign('list', $list);
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->display();
	}
	# 结伴订单
	public function mate_order(){
		$mate_id['mate_id']	=	$_GET['mate_id'];
		$count_user = M('Scenic_mate_order')->where($mate_id)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 10);
		$list	=	M('Scenic_mate_order')->field(true)->where($mate_id)->limit($p->firstRow . ',' . $p->listRows)->order('order_id desc')->select();
		if($list){
			foreach($list as &$v){
				$user	=	D('User')->get_user($v['user_id']);
				$v['truename']	=	$user['truename'];
				$v['phone']	=	$user['phone'];
			}
		}
		$this->assign('list', $list);
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->display();
	}
	# 关闭结伴
	public function mate_close(){
		$mate_id['mate_id']	=	$_GET['mate_id'];
		$list	=	M('Scenic_mate')->field(true)->where($mate_id)->find();
		if(empty($list)){
			$this->error('未找到结伴');
		}else{
			$save	=	M('Scenic_mate')->where($mate_id)->data(array('mate_status'=>4))->save();
		}
		if($save){
			M('Scenic_mate_order')->where($mate_id)->data(array('rela_status'=>4))->save();
			$this->success('修改成功！');
		}else{
			$this->error('关闭失败');
		}
	}

	public function scenic_money(){
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'scenic_id'){
				$condition_merchant['scenic_id'] = $_GET['keyword'];
			}else if($_GET['searchtype'] == 'account'){
				$condition_merchant['scenic_account'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'name'){
				$condition_merchant['scenic_title'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'phone'){
				$condition_merchant['scenic_phone'] = array('like','%'.$_GET['keyword'].'%');
			}
		}
		if(isset($_GET['withdraw_status'])){
			$searchstatus = intval($_GET['withdraw_status']);
			switch($searchstatus){
				case 1:
					$condition_merchant['w.withdraw_status'] = 0; //待体现
					break;
			}
		}
		if ($this->system_session['area_id']) {
			$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			if($now_area['area_type']==3){
				$area_index = 'area_id';
			}elseif($now_area['area_type']==2){
				$area_index = 'city_id';
			}elseif($now_area['area_type']==1){
				$area_index = 'province_id';
			}
			$condition_merchant[$area_index] = $this->system_session['area_id'];
		}

		$scenic_money_list = D('Scenic_money_list');
		$scenic_list = $scenic_money_list->get_scenic_withdraw_list($condition_merchant);
		$all_money = M('Scenic_list')->sum('now_money');
		$this->assign('scenic_list',$scenic_list['scenic_list']);
		$this->assign('pagebar',$scenic_list['pagebar']);
		$this->assign('all_money',$all_money);
		$this->display();
	}

	public function scenic_income_list(){
		if(!empty($_POST['order_id'])){
			if(empty($_POST['order_type'])){
				$this->error_tips("没有选分类");
			}
			if($_POST['order_type']=='all'){
				$this->error("该分类下不能填写订单id");
			}else{
				$condition['order_id'] = $_POST['order_id'];
			}
		}
		$this->assign('order_id',$_POST['order_id']);
		$this->assign('order_type',$_POST['order_type']);
		if($_POST['order_type']!='all'&&!empty($_POST['order_type'])){
			$condition['order_type'] = $_POST['order_type'];
		}


		if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
			$time_condition = " (time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition['_string']=$time_condition;
			$this->assign('begin_time',$_POST['begin_time']);
			$this->assign('end_time',$_POST['end_time']);
		}
		$alias_name  = $this->get_alias_name();
		$this->assign('alias_name',$alias_name);
		$scenic_id = I('scenic_id');
		$res = D('Scenic_money_list')->get_income_list($scenic_id,1,$condition);

		$scenic = D('Scenic_list')->field(true)->where(array('scenic_id'=>$scenic_id))->find();
		$this->assign('scenic', $scenic);
		$this->assign('scenic_id', $scenic_id);
		$this->assign('income_list',$res['income_list']);
		$this->assign('pagebar',$res['pagebar']);
		$this->display();
	}

	public function withdraw_info(){

		if(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
			$time_condition = " (withdraw_time BETWEEN ".$period[0].' AND '.$period[1].")";
			// $condition['_string']=$time_condition;
			$this->assign('begin_time',$_POST['begin_time']);
			$this->assign('end_time',$_POST['end_time']);
		}
		$scenic_id = I('scenic_id');
		$status = I('status');
		$scenic = D('Scenic_list')->field(true)->where(array('scenic_id'=>$scenic_id))->find();
		$withdraw_list = D('Scenic_money_list')->get_withdraw_list($scenic_id,1,$status,$time_condition);
		$this->assign('scenic', $scenic);
		$this->assign('scenic_id', $scenic_id);
		$this->assign('status', $status);
		$this->assign('un_withdraw_list',$withdraw_list['withdraw_list']);
		$this->assign('pagebar',$withdraw_list['pagebar']);
		$this->display();
	}

	public function scenic_withdraw_list(){
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'scenic_id'){
				$condition_merchant['scenic_id'] = $_GET['keyword'];
			}else if($_GET['searchtype'] == 'account'){
				$condition_merchant['scenic_account'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'name'){
				$condition_merchant['scenic_name'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'phone'){
				$condition_merchant['scenic_phone'] = array('like','%'.$_GET['keyword'].'%');
			}
		}
		import('@.ORG.system_page');
		$count = M('Scenic_withdraw')->where(array('status'=>1))->count();
		$p = new Page($count, 20);
		$scenic_list = M('Scenic_withdraw')->field('w.id,w.scenic_id,w.withdraw_time,w.now_money as money,m.scenic_name as name ,m.scenic_account as count,m.scenic_phone as phone')->join('as w left join '.C('DB_PREFIX').'merchant m ON m.scenic_id = w.scenic_id ')->where(array('w.status'=>1))->limit($p->firstRow,$p->listRows)->select();
		$pagebar=$p->show();
		$all_money = M('Scenic_list')->sum('now_money');
		$this->assign('all_money',$all_money);
		$this->assign('scenic_list',$scenic_list);
		$this->assign('pagebar',$pagebar);
		$this->display();
	}

	public function agree_withdraw(){
		if(D('Scenic_money_list')->agree($_GET['scenic_id'],$_GET['id'])){
			$this->success('保存成功！');
		}else{
			$this->error_tips('保存失败！');
		}
	}

	public function reject_withdraw(){
		$res = D('Scenic_money_list')->reject($_GET['scenic_id'],$_GET['id']);
		if(!$res['error_code']){
			$this->success('保存成功！');
		}else{
			$this->error_tips($res['msg']);
		}
	}

	public function edit_reason(){
		if(IS_POST){
			if(empty($_POST['reason'])){
				$this->error('理由不能为空！');
			}
			$res = D('Scenic_money_list')->reject($_POST['scenic_id'],$_POST['id'],$_POST['reason']);
			if(!$res['error_code']){
				$this->success('保存成功！');
			}else{
				$this->error($res['msg']);
			}
		}else{
			$this->assign('id',$_GET['id']);
			$this->assign('scenic_id',$_GET['scenic_id']);
			$this->display();
		}

	}

	public function edit_withdraw(){
		if(IS_POST){
			if(empty($_POST['remark'])){
				$this->error('理由不能为空！');
			}
			$res = D('Scenic_money_list')->agree($_POST['scenic_id'],$_POST['id'],$_POST['remark']);
			if(!$res['error_code']){
				$this->success('保存成功！');
			}else{
				$this->error($res['msg']);
			}
		}else{
			$this->assign('id',$_GET['id']);
			$this->assign('scenic_id',$_GET['scenic_id']);
			$now_withdraw = M('Scenic_withdraw')->where(array('id'=>$_GET['id'],'scenic_id'=>$_GET['scenic_id']))->find();
			$this->assign('now_withdraw',$now_withdraw);
			$this->display();
		}
	}

	public function get_alias_name(){
		return array(
				'all'=>'选择分类',
				'ticket'=>'景区订单',
				'withdraw'=>'提现'
		);
	}
	# 城市广告分类
	public function advert_cat_index(){
		$database_adver_category  = D('Scenic_advert_category');
		$category_list = $database_adver_category->field(true)->order('`cat_id` ASC')->select();
		$this->assign('category_list',$category_list);
		$this->display();
	}
	# 城市广告分类添加
	public function advert_cat_add(){
		if(IS_POST){
			$database_adver_category  = D('Scenic_advert_category');
			if($database_adver_category->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->display();
		}
	}
	# 城市广告分类修改
	public function advert_cat_edit(){
		if(IS_POST){
			$database_adver_category  = D('Scenic_advert_category');
			if($database_adver_category->data($_POST)->save()){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败！请重试~');
			}
		}else{
			$now_category = $this->frame_check_get_category($_GET['cat_id']);
			$this->assign('now_category',$now_category);
			$this->display();
		}
	}
	# 检测分类是否存在
	protected function frame_check_get_category($cat_id){
		$now_category = $this->get_category($cat_id);
		if(empty($now_category)){
			$this->frame_error_tips('分类不存在！');
		}else{
			return $now_category;
		}
	}
	protected function get_category($cat_id){
		$database_adver_category  = D('Scenic_advert_category');
		$condition_adver_category['cat_id'] = $cat_id;
		$now_category = $database_adver_category->field(true)->where($condition_adver_category)->find();
		return $now_category;
	}
	# 城市首页的广告
	public function advert(){
		$where['cat_id']	=	$_GET['cat_id'];
		$count_user = M('Scenic_advert')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 20);
		$list	=	M('Scenic_advert')->field(true)->where($where)->limit($p->firstRow . ',' . $p->listRows)->order(array('sort'=>'DESC','advert_id'=>'DESC'))->select();
		if($list){
			foreach($list as &$v){
				$v['add_time']	=	date('Y-m-d H:i',$v['add_time']);
				$v['update_time']	=	date('Y-m-d H:i',$v['update_time']);
				$city	=	M('Area')->field('area_name')->where(array('area_id'=>$v['city_id']))->find();
				if(empty($city)){
					$v['city_id']	=	'通用';
				}else{
					$v['city_id']	=	$city['area_name'];
				}
			}
		}
		$this->assign('list', $list);
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->display();
	}
	# 添加广告
	public function add_advert(){
		if(IS_POST){
			if(empty($_POST['advert_title'])){
				$this->error('标题不能为空！');
			}
			if(empty($_POST['advert_url'])){
				$this->error('跳转地址不能为空！');
			}
			if(empty($_POST['advert_img'])){
				$this->error('图片不能为空！');
			}
			$arr	=	array(
				'cat_id'	=>	$_POST['cat_id'],
				'advert_title'	=>	$_POST['advert_title'],
				'advert_img'	=>	$_POST['advert_img'],
				'advert_url'	=>	$_POST['advert_url'],
				'advert_status'	=>	$_POST['advert_status'],
				'sort'			=>	$_POST['sort'],
				'add_time'		=>	$_SERVER['REQUEST_TIME'],
				'update_time'	=>	$_SERVER['REQUEST_TIME'],
			);
			if($_POST['currency'] == 2){
				$arr['province_id']	=	$_POST['province_idss'];
				$arr['city_id']	=	$_POST['city_idss'];
			}else{
				$arr['province_id']	=	0;
				$arr['city_id']	=	0;
			}
			$res = M('Scenic_advert')->data($arr)->add();
			if($res){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
		}else{
			$this->display();
		}
	}
	# 修改广告
	public function edit_advert(){
		if(IS_POST){
			if(empty($_POST['advert_title'])){
				$this->error('标题不能为空！');
			}
			if(empty($_POST['advert_url'])){
				$this->error('跳转地址不能为空！');
			}
			if(empty($_POST['advert_img'])){
				$this->error('图片不能为空！');
			}
			$arr	=	array(
				'advert_title'	=>	$_POST['advert_title'],
				'advert_img'	=>	$_POST['advert_img'],
				'advert_url'	=>	$_POST['advert_url'],
				'advert_status'	=>	$_POST['advert_status'],
				'sort'			=>	$_POST['sort'],
				'update_time'	=>	$_SERVER['REQUEST_TIME'],
			);
			if($_POST['currency'] == 2){
				$arr['province_id']	=	$_POST['province_idss'];
				$arr['city_id']	=	$_POST['city_idss'];
			}else{
				$arr['province_id']	=	0;
				$arr['city_id']	=	0;
			}
			$res = M('Scenic_advert')->where(array('advert_id'=>$_POST['advert_id']))->data($arr)->save();
			if($res){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
		}else{
			$advert_id	=	$_GET['advert_id'];
			$scenic_advert	=	M('Scenic_advert')->field(true)->where(array('advert_id'=>$advert_id))->find();
			if($scenic_advert){
				$scenic_advert['url_advert_img']	=	$this->config['site_url'].$scenic_advert['advert_img'];
			}
			$this->assign('scenic_advert',$scenic_advert);
			$this->display();
		}
	}

	//导出excel
	public function export()
	{
		$mer_id = isset($_GET['scenic_id']) ? intval($_GET['scenic_id']) : 0;
		$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'income';
		$title = '';
		switch ($type) {
			case 'income':
				$title = '收入明细';
				break;
		}
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		// 设置当前的sheet
		$objExcel->setActiveSheetIndex(0);


		$objExcel->getActiveSheet()->setTitle($type);
		$objActSheet = $objExcel->getActiveSheet();
		$cell_income  = array('type'=>'类型','order_id'=>'订单编号', 'money'=>'金额','time'=>'记账时间','desc'=>'描述');

		// 开始填充头部
		$cell_name = 'cell_'.$type;
		$cell_count = count($$cell_name);
		$cell_start = 1;
		for($f='A';$f<='Z';$f++,$cell_start++){
			if($cell_start>$cell_count){
				break;
			}
			$col_char[]=$f;
		}
		$col_k=0;
		foreach($$cell_name as $key=>$v){

			$objActSheet->setCellValue($col_char[$col_k].'1', $v);
			$col_k++;
		}
		$i = 2;
		if($type=='income'){
			$where['scenic_id']=$mer_id;
			if($_GET['order_type']&&$_GET['order_type']!='all'){
				$where['order_type']=$_GET['order_type'];
			}
			if($_GET['order_id']){
				$where['order_id']=$_GET['order_id'];
			}
			if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
				if ($_GET['begin_time']>$_GET['end_time']) {
					$this->error_tips("结束时间应大于开始时间");
				}
				$period = $_GET['begin_time']==$_GET['end_time']?array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['begin_time']." 23:59:59")):array(strtotime($_GET['begin_time']),strtotime($_GET['end_time']));
				$time_condition = " (time BETWEEN ".$period[0].' AND '.$period[1].")";
				$where['_string']=$time_condition;

			}

			$result = M('Scenic_money_list')->field('type,order_id,pow(-1,type+1)*money as money,time,desc')->where($where)->order('time DESC')->select();
		}
		//dump(D());die;
		foreach ($result as $row) {
			$col_k=0;
			foreach($$cell_name as $k=>$vv){

				switch($k){
					case 'order_id':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
						break;
					case 'time':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
						break;
					case 'desc':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
						break;
					default:
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
				}
				$col_k++;
			}

			$i++;
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	# 门票订单详情
	public function ticket_detail(){
		$condition_scenic['order_id'] = $_GET['order_id'];
		$condition_scenic['scenic_id'] = $_GET['scenic_id'];
		$database_scenic = D('Scenic_order');
		$now_order = $database_scenic->field(true)->where($condition_scenic)->find();
		if (empty($now_order)) {
			exit('此订单不存在！');
		}
		$scenic_ticket	=	M('Scenic_ticket')->field(true)->where(array('ticket_id'=>$now_order['ticket_id']))->find();
		$now_order['pay_type'] = D('Pay')->get_pay_name($now_order['pay_type'],$now_order['is_mobile_pay']);
		$user	=	D('User')->get_user($now_order['user_id']);
		$com			=	M('Scenic_order_com')->field(true)->where(array('order_id'=>$now_order['order_id']))->select();
		foreach($com as $v){
			if($v['type'] == 1){
				$order_com['ticket'][]	=	$v;
			}else if($v['type'] == 2){
				$order_com['park'][]	=	$v;
			}else if($v['type'] == 3){
				$order_com['guide'][]	=	$v;
			}
		}
		$now_order['nickname']	=	$user['nickname'];
		$now_order['phone']		=	$user['phone'];
		$this->assign('now_order', $now_order);
		$this->assign('order_com', $order_com);
		$this->assign('scenic_ticket', $scenic_ticket);
		$this->display();
	}
	# 景区推荐
	public function groom(){
		import('@.ORG.system_page');
		$many_city	=	$this->config['many_city'];
		$this->assign('many_city',$many_city);
		$count_user = M('Scenic_groom_category')->count();
		$p = new Page($count_user, 20);
		$list	=	M('Scenic_groom_category')->field(true)->limit($p->firstRow . ',' . $p->listRows)->order('cat_sort desc')->select();
		if($many_city == 1 && $list){
			foreach($list as &$v){
				$city	=	M('Area')->field('area_name')->where(array('area_id'=>$v['city_id']))->find();
				if(empty($city)){
					$v['city_id']	=	'通用';
				}else{
					$v['city_id']	=	$city['area_name'];
				}
			}
		}
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->assign('list', $list);
		$this->display();
	}
	# 添加推荐
	public function groom_add(){
		$many_city	=	$this->config['many_city'];
		$this->assign('many_city',$many_city);
		if(IS_POST){
			if(empty($_POST['cat_name'])){
				$this->error('分类名不能为空！');
			}
			if(empty($_POST['cat_img'])){
				$this->error('图片不能为空！');
			}
			$condition_merchant	=	array(
				'cat_name'	=>	$_POST['cat_name'],		//分类名
				'cat_sort'	=>	$_POST['cat_sort'],		//排序
				'cat_img'	=>	$_POST['cat_img'],		//小图标
				'status'	=>	$_POST['status'],		//状态
				'complete'	=>	$_POST['complete'],		//补齐
				'add_time'	=>	$_SERVER['REQUEST_TIME'],//注册时间
			);
			if($_POST['currency'] == 1){
				$condition_merchant['province_id']	=	0;
				$condition_merchant['city_id']	=	0;
			}else{
				$condition_merchant['province_id']	=	$_POST['province_idss'];
				$condition_merchant['city_id']	=	$_POST['city_idss'];
				unset($_POST['province_idss'],$_POST['city_idss']);
			}
			$add	=	M('Scenic_groom_category')->data($condition_merchant)->add();
			if($add){
				$this->success('新增成功！');
			}else{
				$this->error('新增失败！请重试~');
			}
		}else{
			$this->display();
		}
	}
	# 修改推荐
	public function groom_edit(){
		$many_city	=	$this->config['many_city'];
		$this->assign('many_city',$many_city);
		if(IS_POST){
			if(empty($_POST['cat_name'])){
				$this->error('分类名不能为空！');
			}
			if(empty($_POST['cat_img'])){
				$this->error('图片不能为空！');
			}
			$condition_merchant	=	array(
				'cat_name'	=>	$_POST['cat_name'],		//分类名
				'cat_sort'	=>	$_POST['cat_sort'],		//排序
				'cat_img'	=>	$_POST['cat_img'],		//小图标
				'status'	=>	$_POST['status'],		//状态
				'complete'	=>	$_POST['complete'],		//补齐
			);
			if($_POST['currency'] == 1){
				$condition_merchant['province_id']	=	0;
				$condition_merchant['city_id']	=	0;
			}else{
				$condition_merchant['province_id']	=	$_POST['province_idss'];
				$condition_merchant['city_id']	=	$_POST['city_idss'];
				unset($_POST['province_idss'],$_POST['city_idss']);
			}
			$add	=	M('Scenic_groom_category')->where(array('cat_id'=>$_POST['cat_id']))->data($condition_merchant)->save();
			if($add){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！请重试~');
			}
		}else{
			$cat_id	=	$_GET['cat_id'];
			$list	=	M('Scenic_groom_category')->field(true)->where(array('cat_id'=>$cat_id))->find();
			$this->assign('list', $list);
			$this->display();
		}
	}
	# 删除推荐
	public function groom_del(){
		$cat_id	=	$_GET['cat_id'];
		$list	=	M('Scenic_groom_category')->where(array('cat_id'=>$cat_id))->delete();
		if($list){
			M('Scenic_groom')->where(array('cat_id'=>$cat_id))->delete();
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！请重试~');
		}
	}
	# 商品列表
	public function com_list(){
		$where['cat_id']	=	$_GET['cat_id'];
		import('@.ORG.system_page');
		$count_user = M('Scenic_groom')->count();
		$p = new Page($count_user, 20);
		$list	=	M('Scenic_groom')->field(true)->limit($p->firstRow . ',' . $p->listRows)->where($where)->order('sort DESC,com_id DESC')->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->assign('list', $list);
		$this->display();
	}
	# 添加商品
	public function com_add(){
		if(IS_POST){
			if(empty($_POST['com_name'])){
				$this->error('商品名不能为空！');
			}
			if(empty($_POST['com_img'])){
				$this->error('图片不能为空！');
			}
			if(empty($_POST['com_title'])){
				$this->error('简单描述不能为空！');
			}
			if(empty($_POST['price'])){
				$this->error('价格不能为空！');
			}
			if(empty($_POST['url'])){
				$this->error('跳转链接不能为空！');
			}
			$condition_merchant	=	array(
				'com_name'	=>	$_POST['com_name'],		//分类名
				'com_title'	=>	$_POST['com_title'],	//简单描述
				'sort'		=>	$_POST['sort'],			//排序
				'com_img'	=>	$_POST['com_img'],		//小图标
				'price'		=>	$_POST['price'],		//价格
				'url'		=>	$_POST['url'],			//跳转链接
				'status'	=>	$_POST['status'],		//状态
				'cat_id'	=>	$_POST['cat_id'],		//状态
				'add_time'	=>	$_SERVER['REQUEST_TIME'],//注册时间
			);
			$add	=	M('Scenic_groom')->data($condition_merchant)->add();
			if($add){
				$this->success('新增成功！');
			}else{
				$this->error('新增失败！请重试~');
			}
		}else{
			$this->display();
		}
	}
	# 修改商品
	public function com_edit(){
		if(IS_POST){
			if(empty($_POST['com_name'])){
				$this->error('商品名不能为空！');
			}
			if(empty($_POST['com_img'])){
				$this->error('图片不能为空！');
			}
			if(empty($_POST['com_title'])){
				$this->error('简单描述不能为空！');
			}
			if(empty($_POST['price'])){
				$this->error('价格不能为空！');
			}
			if(empty($_POST['url'])){
				$this->error('跳转链接不能为空！');
			}
			$condition_merchant	=	array(
				'com_name'	=>	$_POST['com_name'],		//分类名
				'com_title'	=>	$_POST['com_title'],	//简单描述
				'sort'		=>	$_POST['sort'],			//排序
				'com_img'	=>	$_POST['com_img'],		//小图标
				'price'		=>	$_POST['price'],		//价格
				'url'		=>	$_POST['url'],			//跳转链接
				'status'	=>	$_POST['status'],		//状态
			);
			$add	=	M('Scenic_groom')->where(array('com_id'=>$_POST['com_id']))->data($condition_merchant)->save();
			if($add){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！请重试~');
			}
		}else{
			$cat_id	=	$_GET['com_id'];
			$list	=	M('Scenic_groom')->field(true)->where(array('com_id'=>$cat_id))->find();
			$this->assign('list', $list);
			$this->display();
		}
	}
	# 删除商品
	public function com_del(){
		$cat_id	=	$_GET['com_id'];
		$list	=	M('Scenic_groom')->where(array('com_id'=>$cat_id))->delete();
		if($list){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！请重试~');
		}
	}
	# 关于我们分类
	public function about_category(){
		$select	=	M('Scenic_about_category')->field(true)->order('com_sort DESC')->select();
		$this->assign('select', $select);
		$this->display();
	}
	# 新增关于我们分类
	public function about_category_add(){
		if(IS_POST){
			$arr	=	array(
				'com_title'	=>	$_POST['com_title'],
				'com_hot'	=>	$_POST['com_hot'],
				'com_status'=>	$_POST['com_status'],
				'com_sort'	=>	$_POST['com_sort'],
			);
			$arr	=	M('Scenic_about_category')->data($arr)->add();
			if($arr){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
		}else{
			$this->display();
		}
	}
	# 修改关于我们分类
	public function about_category_edit(){
		if(IS_POST){
			$where['com_id']	=	$_POST['com_id'];
			$arr	=	array(
				'com_title'	=>	$_POST['com_title'],
				'com_hot'	=>	$_POST['com_hot'],
				'com_status'=>	$_POST['com_status'],
				'com_sort'	=>	$_POST['com_sort'],
			);
			$arr	=	M('Scenic_about_category')->where($where)->data($arr)->save();
			if($arr){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
		}else{
			$where['com_id']	=	$_GET['com_id'];
			$find	=	M('Scenic_about_category')->field(true)->where($where)->find();
			$this->assign('find',$find);
			$this->display();
		}
	}
	# 删除关于我们分类
	public function about_category_delete(){
		$where['com_id']	=	$_GET['com_id'];
		$delete	=	M('Scenic_about_category')->where($where)->delete();
		if($delete){
			M('Scenic_about')->where($where)->delete();
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
	# 关于我们
	public function about(){
		$where['com_id']	=	$_GET['com_id'];
		$select	=	M('Scenic_about')->field(true)->where($where)->order('about_sort DESC')->select();
		$this->assign('select', $select);
		$this->display();
	}
	# 新增关于我们
	public function about_add(){
		if(IS_POST){
			$arr	=	array(
				'com_id'	=>	$_POST['com_id'],
				'about_title'	=>	$_POST['about_title'],
				'about_text'	=>	$_POST['about_text'],
				'about_status'=>	$_POST['about_status'],
				'about_hot'	=>	$_POST['about_hot'],
				'about_sort'	=>	$_POST['about_sort'],
			);
			$arr	=	M('Scenic_about')->data($arr)->add();
			if($arr){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
		}else{
			$this->display();
		}
	}
	# 修改关于我们
	public function about_edit(){
		if(IS_POST){
			$where['about_id']	=	$_POST['about_id'];
			$arr	=	array(
				'about_title'	=>	$_POST['about_title'],
				'about_text'	=>	$_POST['about_text'],
				'about_status'	=>	$_POST['about_status'],
				'about_hot'		=>	$_POST['about_hot'],
				'about_sort'	=>	$_POST['about_sort'],
			);
			$arr	=	M('Scenic_about')->where($where)->data($arr)->save();
			if($arr){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
		}else{
			$where['about_id']	=	$_GET['about_id'];
			$find	=	M('Scenic_about')->field(true)->where($where)->find();
			$this->assign('find',$find);
			$this->display();
		}
	}
	# 删除关于我们
	public function about_delete(){
		$where['about_id']	=	$_GET['about_id'];
		$delete	=	M('Scenic_about')->where($where)->delete();
		if($delete){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
}