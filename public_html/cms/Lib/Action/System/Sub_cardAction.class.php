<?php 
/*
 *免单功能
 */
class Sub_cardAction extends BaseAction {
		
		public function index(){
			if (!empty($_GET['keyword'])) {
				if ($_GET['searchtype'] == 'id') {
					$condition_sub_card['id'] = $_GET['keyword'];
				} else if ($_GET['searchtype'] == 'name') {
					$condition_sub_card['name'] = array('like', '%' . $_GET['keyword'] . '%');
				}
			}
			$area_id = $this->system_session['area_id'];//区域管理员区域
			if ($area_id) {
				$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
				if ($now_area['area_type'] == 3) {
					$area_index = 'area_id';
					$wherestr['city_id'] = $now_area['area_pid'];
				} elseif ($now_area['area_type'] == 2) {
					$area_index = 'city_id';
					$wherestr['city_id'] = $this->system_session['area_id'];
				} elseif ($now_area['area_type'] == 1) {
					$area_index = 'province_id';

				}
			}

			if($_GET['province_idss'] && $this->config['many_city']){
				$area_list = M('Area')->where(array('area_pid'=>$_GET['province_idss']))->select();
				foreach ($area_list as $v) {
					$city_arr[] = $v['area_id'];
				}
				$sub_card_arr = M('Sub_card_area')->field('sub_cardid')->where(array('aid'=>array('in',$city_arr)))->group('sub_cardid')->select();

			}

			if($_GET['city_idss']){
				$area_id= $_GET['city_idss'];
				$sub_card_arr = M('Sub_card_area')->field('sub_cardid')->where(array('aid'=>$area_id))->group('sub_cardid')->select();
			}
			if(!empty($sub_card_arr)){
				foreach ($sub_card_arr as $v) {
					$tmp[] = $v['sub_cardid'];
				}
				$condition_sub_card['id']=  array('in',$tmp);
				$condition_sub_card['use_area']=  1;
			}
			$condition_sub_card['status'] = array('neq',4);

			//$condition_sub_card['delete'] = 0;
			isset($_GET['status']) && $_GET['status']>=0 && $condition_sub_card['status'] = $_GET['status'];
			//排序 /*/
			$order_string = '`id` DESC';

			$sub_card = M('Sub_card');
			$count= $sub_card->where($condition_sub_card)->count();

			import('@.ORG.system_page');
			$p = new Page($count, 15);
			$sub_card_list = $sub_card->field(true)->where($condition_sub_card)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
			foreach ($sub_card_list as &$item) {
				if($sub_card_list['buy_time_type']==1 && intval($item['end_time'])<time() ){
					M('Sub_card')->where(array(id=>$item['id']))->setField('status',3);

				}
//				$item['pic_list'] = empty($item['pic_list'])?'':explode(';',$item['pic_list']);
			}
			$this->assign('sub_card_list',$sub_card_list);
			$pagebar = $p->show();
			$this->assign('pagebar', $pagebar);
			$this->display();
		}

		public function add(){
			if(IS_POST){
				if($_POST['buy_time_type']==1&&(strtotime($_POST['end_time'])<strtotime($_POST['start_time'])||strtotime($_POST['end_time'])+86399<time())){
					$this->error('起始时间设置有误！');
				}
				$data = $_POST;
				$data['start_time']=strtotime($data['start_time']);
				$data['end_time']=$data['end_time']>0?strtotime($data['end_time'])+86399:0;//到 23:59:59
				$data['add_time']=$data['last_time']=time();
				if($_POST['use_time_type']==1 && $_POST['effective_days']==0){
					$this->error('有效期不能为0 ，填写大于0的整数');
				}

				if($_POST['id']){
					$result = D('Sub_card')->where(array('id'=>$_POST['id']))->save($data);
				}else{
					$result = D('Sub_card')->add($data);
				}
				if($result){
					$this->success('操作成功！');
				}else{
					$this->error('操作失败！');
				}
			}else {
				if($_GET['id']){
					$sub_card =  D('Sub_card')->get_sub_card($_GET['id']);
					$this->assign("sub_card",$sub_card);
				}
				$this->display();
			}
		}

		public function check_list(){
			$where['ms.sub_card_id'] = $_GET['id'];
			isset($_GET['status']) && $where['ms.status']  =isset($_GET['status']);
			import('@.ORG.system_page');
			$count = M('Sub_card_mer_apply')->join('as ms LEFT JOIN '.C('DB_PREFIX').'merchant_store s on ms.store_id=s.store_id LEFT JOIN '.C('DB_PREFIX').'merchant m ON s.mer_id = m.mer_id')->where($where)->count();
			$p = new Page($count,20);
			$apply_list = M('Sub_card_mer_apply')->field('ms.* ,s.name as store_name,m.name as mer_name')->join('as ms LEFT JOIN '.C('DB_PREFIX').'merchant_store s on ms.store_id=s.store_id LEFT JOIN '.C('DB_PREFIX').'merchant m ON s.mer_id = m.mer_id')->where($where)->limit($p->firstRow,$p->listRows)->order('ms.status DESC,ms.id DESC')->select();
			foreach ($apply_list as &$vo) {
				$vo['pic_list'] = empty($vo['pic_list'])?'':explode(';',$vo['pic_list']);
			}

			$sub_card = D('Sub_card')->get_sub_card($_GET['id']);

			$this->assign('sub_card',$sub_card);
			$this->assign('apply_list',$apply_list);
			$this->assign('pagebar',$p->show());
			$this->display();
		}

		public function order_list(){
			import('@.ORG.system_page');
			if(!empty($_GET['keyword'])){
				if ($_GET['searchtype'] == 'order_id') {
					$where['order_id'] = htmlspecialchars($_GET['keyword']);
				}else if ($_GET['searchtype'] == 'nickname') {
					$where['u.nickname'] = array('like','%'.htmlspecialchars($_GET['keyword']).'%');
				}else if ($_GET['searchtype'] == 'phone') {
					$where['u.phone'] = htmlspecialchars($_GET['keyword']);
				}
			}

			if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
				if ($_GET['begin_time']>$_GET['end_time']) {
					$this->error_tips("结束时间应大于开始时间");
				}

				$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
				$where['_string']= "pay_time BETWEEN ".$period[0].' AND '.$period[1];

			}
			$where['s.paid']=1;
			$count = M('Sub_card_order')->join('AS s LEFT JOIN '.C('DB_PREFIX').'sub_card sc ON sc.id = s.sub_card_id LEFT JOIN '.C('DB_PREFIX').'user u ON s.uid  =u.uid  ')->where($where)->count();

			$p=new Page($count,15);
//			$where['s.status']=1;
//			unset($where['status']);
			$list = M('Sub_card_order')->field('s.* ,sc.name,sc.desc,sc.price,sc.free_total_num,u.nickname,u.phone')->join('AS s LEFT JOIN '.C('DB_PREFIX').'sub_card sc ON sc.id = s.sub_card_id LEFT JOIN '.C('DB_PREFIX').'user u ON s.uid  =u.uid  ')->where($where)->limit($p->firstRow,$p->listRows)->order('order_id DESC')->select();

			$this->assign('order_list',$list);
			$this->assign('pagebar',$p->show());
			$this->display();
		}

	public function consume_list(){

		$where['fid']=$_GET['order_id'];
		$count = M('Sub_card_user_pass')->where($where)->count();

//			$where['s.status']=1;
//			unset($where['status']);
		$list = M('Sub_card_user_pass')->field('s.* ,sc.name,sc.desc,sc.price,sc.free_total_num,st.name as store_name')->join('AS s LEFT JOIN '.C('DB_PREFIX').'sub_card sc ON sc.id = s.sub_card_id LEFT JOIN '.C('DB_PREFIX').'merchant_store st ON st.store_id  =s.store_id   ')->where($where)->order('s.use_time DESC')->order('id DESC')->select();


		$this->assign('order_list',$list);

		$this->display();
	}


		public function change_status(){
			$where['id'] = $_POST['id'];
			$date['status'] = $_POST['status'];
			if(M('Sub_card_user_pass')->where(array('store_id'=>$_POST['store_id'],'sub_card_id'=>$_POST['sub_card_id']))->find()){
				$this->error('已经有人购买了，取消资格将影响用户订单');
			}
			if(M('Sub_card_mer_apply')->where($where)->save($date)){

				//增减参与的记录，通用验证才能更新
				D('Sub_card')->sub_card_change_num($_POST['sub_card_id'],'join_num',$_POST['status']);
				$mer_count = M('Sub_card_mer_apply')->where(array('sub_card_id'=>$_POST['sub_card_id'],'status'=>1))->group('mer_id')->select();
				M('Sub_card')->where(array('id'=>$_POST['sub_card_id']))->setField('mer_pass_num',count($mer_count));
				$this->success('审核成功');
			}else{
				$this->error('审核失败');
			}
		}


	public function edit_area(){
		if(IS_POST){
			$discountAreaDB = D('Sub_card_area');
			//删除已经设置的区域
			$discountAreaDB->where(array('sub_cardid' => $_POST['sub_cardid']))->delete();
			$provinceIds = array();
			$cityIds = array();
			$areaIds = array();
			foreach ($_POST['areaIds'] as $aid ) {

				$discountAreaDB->add(array('sub_cardid' =>  $_POST['sub_cardid'], 'aid' => $aid));
			}
			$this->success('设置成功');
		}else{
			$where = array('area_type' => array('lt', 3), 'is_open' => 1);
			if ($this->system_session['area_id']) {
				$where['area_pid'] = $this->system_session['area_id'];
			}
			$areaList = D('Area')->field('area_id, area_pid, area_name')->where($where)->select();
			$list = D('Sub_card_area')->field('aid')->where(array('sub_cardid' => $_GET['sub_cardid']))->select();

			$areaIds = array();
			foreach ($list as $row) {
				$areaIds[] = $row['aid'];
			}

			$tmpMap = array();
			foreach ($areaList as $item) {
				$item['select'] = 0;
				if (in_array($item['area_id'], $areaIds)) {
					$item['select'] = 1;
				}
				$tmpMap[$item['area_id']] = $item;
			}


			$list = array();
			foreach ($areaList as $item) {
				if (isset($tmpMap[$item['area_pid']])) {
					$tmpMap[$item['area_id']]['select']  && $tmpMap[$item['area_pid']]['select'] = 1;
					$tmpMap[$item['area_pid']]['son_list'][$item['area_id']] = &$tmpMap[$item['area_id']];
				} else {
					$list[$item['area_id']] = &$tmpMap[$item['area_id']];
				}
			}

			$this->assign('area_list', $list);
			$this->display();
		}



	}

	public function del(){
		if(IS_POST){
			if(!empty($_POST['id'])){
				if(M('Sub_card')->where(array('id'=>$_POST['id']))->setField('status',4)){
					$this->success('删除成功');
				}else{
					$this->error('删除失败！');
				}
			}

		}
	}


}
