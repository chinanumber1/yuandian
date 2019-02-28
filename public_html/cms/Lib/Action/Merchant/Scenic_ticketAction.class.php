<?php
/*
 * 景区门票
 *   Writers    hanlu
 *   BuildTime  2016/07/04 20:00
 */
class Scenic_ticketAction extends BaseAction{
    # 门票列表
    public function index(){
    	$database_scenic = D('Scenic_ticket');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_ticket = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('ticket_id DESC')->select();
		$ticket_time_array=D('Scenic_ticket')->ticket_time_array;
		foreach ($now_ticket as &$v) {
			$v['auth_count'] = M('Scenic_ticket_token_list')->where(array('ticket_id'=>$v['ticket_id']))->count();
			if($v['is_children']==1){
				$prefix_child = '儿童票-';
			}else{
				$prefix_child = '成人票-';
			}
			if($v['ticket_time_type']){
				$prefix_time =$ticket_time_array[$v['ticket_time_type']].'-';
			}

			if($v['ticket_group']==1){
				$prefix_group ='单人票-';
			}elseif($v['ticket_group']==2){
				$prefix_group ='团队票-';
			}

			if($v['ticket_union']==1){
				$prefix_union ='联票';
			}else{
				$prefix_union='非联票';
			}

			$v['ticket_prefix_title'] = $prefix_child.$prefix_time.$prefix_group.$prefix_union;
		}
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_ticket',$now_ticket);
		$this->display();
    }
    # 新增门票
    public function add(){
    	$data	=	array();
    	$data['scenic_id']	=	$this->merchant_session['scenic_id'];
    	$scenic_project	=	M('Scenic_project')->where(array('scenic_id'=>$data['scenic_id']))->find();
    	if(empty($scenic_project)){
			$this->error('请先添加景区项目！',U('Scenic/project'));
    	}
    	if(IS_POST){
			if(empty($_POST['ticket_title'])){
				$this->error('门票名称必填！');
			}
            if(empty($_POST['ticket_explain'])){
                $this->error('预定须知必填！');
            }
            if(empty($_POST['park_intr'])){
				$this->error('入园提示必填！');
            }
            if(empty($_POST['ticket_cue'])){
				$this->error('入园方式必填！');
            }
            if(empty($_POST['ticket_price'])){
				$this->error('门票现价必填！');
            }
            if(empty($_POST['count_num'])){
				$this->error('门票数量必填！');
            }
            $priceArr = json_decode(htmlspecialchars_decode($_POST['jsonPrice']),true);
			$data['project_id'] = implode(',',$_POST['project']);//项目名
			$data['ticket_title'] = $_POST['ticket_title'];		//门票名称
			$data['ticket_explain'] = $_POST['ticket_explain'];	//预定须知
			$data['park_intr'] = $_POST['park_intr'];			//入园提示
			$data['ticket_cue'] = $_POST['ticket_cue'];			//门票介绍
			if(empty($_POST['old_price'])){
				$data['old_price'] = $_POST['ticket_price'];	//老价格
			}else{
				$data['old_price'] = $_POST['old_price'];		//老价格
			}
			$data['ticket_price'] = $_POST['ticket_price'];		//现价
			$data['serialize'] = serialize($priceArr);			//门票日期序列化
			$data['count_num'] = $_POST['count_num'];			//门票数量
			//$data['start_time'] = isset($_POST['start_time'])?strtotime($_POST['start_time']):'00:00';	//开始时间
//			$data['end_time'] = isset($_POST['end_time'])?strtotime($_POST['end_time']):'00:00';	//结束时间
			$data['start_time'] = $_POST['start_time']==0?'00:00:00':$_POST['start_time'].':00';	//开始时间
			$data['end_time'] = $_POST['end_time']==0?'00:00:00':$_POST['end_time'].':00';	//结束时间
			$data['last_time'] = $_SERVER['REQUEST_TIME'];		//更新时间
			$data['create_time'] = $_SERVER['REQUEST_TIME'];	//创建时间
			$data['ticket_sort'] = $_POST['ticket_sort'];		//排序
//			$data['is_refund'] = $_POST['is_refund'];			//随时退
			$data['is_children'] = $_POST['is_children'];		//门票类型
			$data['is_general'] = $_POST['is_general'];			//节假日
			$data['ticket_status'] = 1;

			$data['ticket_time_type'] = $_POST['ticket_time_type'];	//时间类型
			$data['ticket_custom_time'] = $_POST['ticket_custom_time'];	//自定义时间
			$data['ticket_group'] = $_POST['ticket_group'];	//团队票
			if($data['ticket_time_type']!=1){
				$data['ticket_group'] = 1;
			}
			if( $_POST['ticket_group']==1){
				$data['ticket_union'] = $_POST['ticket_union'];	//联票
				if($_POST['ticket_union']==0){
					$data['use_limit'] = $_POST['use_limit'];	//联票
				}
			}else{
				$data['ticket_union'] = 0;
			}
			$data['ticket_limit'] = $_POST['ticket_limit'];	//最多购买
			$data['ticket_limit_min'] = $_POST['ticket_limit_min'];	//最少购买

			if($data['ticket_limit']<$data['ticket_limit_min'] && $data['ticket_limit']>0){
				$this->error('最多购买数量不能比最少购买数量小');
			}

			if($_POST['ticket_group']==1 && $_POST['auth_token'] && $_POST['ticket_union']==1){
				foreach ($_POST['auth_token'] as $key=>$v) {
					$where['token'] = $v;
					if($res = D('Scenic_ticket')->get_scenic_one_ticket($where)){
						if($res['scenic_id'] == $this->merchant_session['scenic_id']){
							$this->error('授权码：'.$v.' 是同一个景区，无法授权');
						}
						$tmp_ticket_id[] = $res['ticket_id'];
						$res['union_sell'] = $_POST['union_sell'][$key];
						$tmp_scenic_list[$res['ticket_id']] = $res;

					}
				}
				$data['ticket_union_id'] = implode(',',$tmp_ticket_id);
			}

            $add	=	M('Scenic_ticket')->data($data)->add();
            if($add){
				if($tmp_ticket_id){
					foreach ($tmp_ticket_id as $t) {
						$date_token['ticket_id'] = $t;
						$date_token['token'] = $tmp_scenic_list[$t]['token'];
						$date_token['auth_ticket'] = $add;
						$date_token['status'] = 1;
						$date_token['union_sell'] = $tmp_scenic_list[$t]['union_sell'];
						$date_token['add_time'] = time();
						M('Scenic_ticket_token_list')->add($date_token);
					}
				}


                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$project	=	M('Scenic_project')->where($data)->select();
			$this->assign('project',$project);
			$this->display();
		}
    }
    # 修改门票
    public function edit(){
		$data	=	array();
    	$data['scenic_id']	=	$this->merchant_session['scenic_id'];
    	if(IS_POST){
			if(empty($_POST['ticket_id'])) {
				$this->error('门票ID不能为空！');
			}else{
				$where['ticket_id']	=	$_POST['ticket_id'];
			}
			if(empty($_POST['ticket_title'])) {
				$this->error('门票名称必填！');
			}
            if(empty($_POST['ticket_explain'])) {
                $this->error('预定须知必填！');
            }
            if(empty($_POST['park_intr'])){
				$this->error('入园提示必填！');
            }
            if(empty($_POST['ticket_cue'])){
				$this->error('入园方式必填！');
            }
            if(empty($_POST['ticket_price'])){
				$this->error('门票现价必填！');
            }
            if(empty($_POST['count_num'])){
				$this->error('门票数量必填！');
            }
            $priceArr = json_decode(htmlspecialchars_decode($_POST['jsonPrice']),true);
			$data['project_id'] = implode(',',$_POST['project']);//项目名
			$data['ticket_title'] = $_POST['ticket_title'];		//门票名称
			$data['ticket_explain'] = $_POST['ticket_explain'];	//预定须知
			$data['park_intr'] = $_POST['park_intr'];			//入园提示
			$data['ticket_cue'] = $_POST['ticket_cue'];			//门票介绍
			if(empty($_POST['old_price'])){
				$data['old_price'] = $_POST['ticket_price'];	//老价格
			}else{
				$data['old_price'] = $_POST['old_price'];		//老价格
			}
			$data['ticket_price'] = $_POST['ticket_price'];		//现价
			$data['serialize'] = serialize($priceArr);			//门票日期序列化
			$data['count_num'] = $_POST['count_num'];			//门票数量
			$data['start_time'] = $_POST['start_time']==0?'00:00:00':$_POST['start_time'].':00';	//开始时间
			$data['end_time'] = $_POST['end_time']==0?'00:00:00':$_POST['end_time'].':00';	//结束时间
			$data['last_time'] = $_SERVER['REQUEST_TIME'];		//更新时间
			$data['ticket_sort'] = $_POST['ticket_sort'];		//排序
//			$data['is_refund'] = $_POST['is_refund'];			//随时退
			$data['is_children'] = $_POST['is_children'];		//门票类型
			$data['is_general'] = $_POST['is_general'];			//节假日
			$data['ticket_status'] = $_POST['ticket_status'];	//状态
			$data['ticket_time_type'] = $_POST['ticket_time_type'];	//时间类型
			$data['ticket_custom_time'] = $_POST['ticket_custom_time'];	//自定义时间
			$data['ticket_group'] = $_POST['ticket_group'];	//团队票
			if($data['ticket_time_type']!=1){
				$data['ticket_group'] = 1;
			}
			if( $_POST['ticket_group']==1){
				$data['ticket_union'] = $_POST['ticket_union'];	//联票
				if($_POST['ticket_union']==0){
					$data['use_limit'] = $_POST['use_limit'];	//联票
				}
			}else{
				$data['ticket_union'] = 0;
			}
			$data['ticket_limit'] = $_POST['ticket_limit'];	//最多购买
			$data['ticket_limit_min'] = $_POST['ticket_limit_min'];	//最少购买

			if($data['ticket_limit']<$data['ticket_limit_min'] && $data['ticket_limit']>0){
				$this->error('最多购买数量不能比最少购买数量小');
			}

			if($_POST['ticket_group']==1 && $_POST['auth_token'] && $_POST['ticket_union']==1 ){
				foreach ($_POST['auth_token'] as $key=>$v) {
					$condition['token'] = $v;

					if($res = D('Scenic_ticket')->get_scenic_one_ticket($condition)){
						if($res['scenic_id'] == $this->merchant_session['scenic_id']){
							$this->error('授权码：'.$v.' 是同一个景区，无法授权');
						}
						$tmp_ticket_id[] = $res['ticket_id'];
						$res['union_sell'] = $_POST['union_sell'][$key];
						$tmp_scenic_list[$res['ticket_id']] = $res;
					}else{
						M('Scenic_ticket_token_list')->where(array('token'=>$v))->delete();
					}


				}

				$data['ticket_union_id'] = implode(',',$tmp_ticket_id);
			}else if( $_POST['ticket_union']==0){
				$data['ticket_union_id'] = '';
			}

            $save	=	M('Scenic_ticket')->where($where)->data($data)->save();
            if($save){
				//授权码更新
				if($_POST['ticket_group']==1 &&  $_POST['auth_token'] && $_POST['ticket_union']==1) {
					//M('Scenic_ticket_token_list')->where(array('auth_ticket' => $_POST['ticket_id']))->delete();
					foreach ($tmp_ticket_id as $t) {
						$date_token['ticket_id'] = $t;
						$date_token['token'] = $tmp_scenic_list[$t]['token'];
						$date_token['auth_ticket'] = $_POST['ticket_id'];
						$date_token['status'] = 1;
						$date_token['union_sell'] = $tmp_scenic_list[$t]['union_sell'];
						$date_token['add_time'] = time();
						$condition_token['auth_ticket'] = $_POST['ticket_id'];
						$condition_token['ticket_id'] = $t;
						if($res = M('Scenic_ticket_token_list')->where($condition_token)->find()){
							M('Scenic_ticket_token_list')->where($condition_token)->save($date_token);
						}else{
							M('Scenic_ticket_token_list')->add($date_token);
						}
					}
				}else{
					$condition_token['auth_ticket'] = $_POST['ticket_id'];
					M('Scenic_ticket_token_list')->where($condition_token)->delete();
				}

                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['ticket_id']	=	$_GET['ticket_id'];
			$ticket	=	M('Scenic_ticket')->field(true)->where($where)->find();
			if(!empty($ticket['ticket_pic'])){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$ticket['ticket_pic']);
				foreach($tmp_pic_arr as $key=>$value){
					$ticket['pic'][$key]['title'] = $value;
					$ticket['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'ticket','1');
				}
			}
			if($ticket['serialize']){
				$serialize	=	unserialize($ticket['serialize']);
				$ticket['serialize']	=	json_encode($serialize);
			}else{
				$ticket['serialize']	=	1;
			}
			$this->assign('ticket',$ticket);
			$project	=	M('Scenic_project')->field(true)->where($data)->select();
			$project_id	=	explode(',',$ticket['project_id']);
			foreach($project as $k=>&$v){
				$search	=	in_array($v['project_id'],$project_id);
				if($search){
					$v['is_open']	=	1;
				}else{
					$v['is_open']	=	0;
				}
			}
			$auth_list = D('Scenic_ticket')->get_auth_list_by_ticket_id($_GET['ticket_id']);

			$this->assign('project',$project);
			$this->assign('auth_list',$auth_list);
			$this->display();
		}
    }
    # 删除门票
    public function del(){
    	$where['ticket_id']	=	$_GET['ticket_id'];
    	if(empty($where)){
			$this->error('门票ID不能为空！');
    	}
    	$ticket	=	M('Scenic_ticket')->where($where)->delete();
		M('Scenic_ticket_token_list')->where(array('auth_ticket'=>$_GET['ticket_id']))->delete();
    	if($ticket){
			$this->success("删除成功！");
    	}else{
			$this->error("删除失败！");
    	}
    }
    # 门票订单
    public function order_list(){
		$database_scenic = D('Scenic_order');
    	$where['scenic_id']	=	$condition_scenic['o.scenic_id'] = $this->merchant_session['scenic_id'];
    	$ticket_id = intval($_GET['ticket_id']);
    	if($ticket_id){
			$condition_scenic['t.ticket_id']	=	$ticket_id;
    	}

		if($_GET['is_children']>-1){
			$condition_scenic['t.is_children']	=	$_GET['is_children'];
		}

		if($_GET['is_children']>-1){
			$condition_scenic['t.is_children']	=	$_GET['is_children'];
		}

		if($_GET['ticket_time_type']>-1){
			$condition_scenic['t.ticket_time_type']	=	$_GET['ticket_time_type'];
		}

		if($_GET['ticket_group']>-1){
			$condition_scenic['t.ticket_group']	=	$_GET['ticket_group'];
		}

		if($_GET['ticket_union']>-1){
			$condition_scenic['t.ticket_union']	=	$_GET['ticket_union'];
		}



    	$count_store = $database_scenic->join('as o LEFT JOIN '.C('DB_PREFIX').'scenic_ticket t ON t.ticket_id = o.ticket_id')->where($condition_scenic)->count();
		$p = new Page($count_store,15);
		$now_order = $database_scenic->field('o.*')->join('as o LEFT JOIN '.C('DB_PREFIX').'scenic_ticket t ON t.ticket_id = o.ticket_id')->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('order_id DESC')->select();

		foreach($now_order as &$v){
			$ticket_title	=	M('Scenic_ticket')->field('ticket_title')->where(array('ticket_id'=>$v['ticket_id']))->find();
			$v['ticket_title']	=	$ticket_title['ticket_title'];
			$v['ticket_count']	=	M('Scenic_order_com')->where(array('order_id'=>$v['order_id'],'type'=>1))->count();
			$v['park_count']	=	M('Scenic_order_com')->where(array('order_id'=>$v['order_id'],'type'=>2))->count();
			$v['guide']			=	M('Scenic_order_com')->where(array('order_id'=>$v['order_id'],'type'=>3))->count();
			$v['ticket_num_add']	=	M('Scenic_group_user')->where(array('order_id'=>$v['order_id']))->count();
			$v['sms_count']	=	M('Scenic_group_user')->where(array('order_id'=>$v['order_id'],'send_sms'=>1))->count();
		}
		$pagebar = $p->show();
		# 项目列表
        $ticket_list	=	M('Scenic_ticket')->field(true)->where($where)->select();

        $this->assign('ticket_list', $ticket_list);
		$this->assign('pagebar',$pagebar);
		$this->assign('now_order',$now_order);
		$this->display();
    }
    # 门票订单详情
    public function order_detail(){
    	$condition_scenic['order_id'] = $_GET['order_id'];
    	$database_scenic = D('Scenic_order');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
        $now_order = $database_scenic->field(true)->where($condition_scenic)->find();
        if (empty($now_order)) {
            exit('此订单不存在！');
        }
        $scenic_ticket	=	D('Scenic_ticket')->get_scenic_one_ticket(array('ticket_id'=>$now_order['ticket_id']));
        $now_order['pay_type'] = D('Pay')->get_pay_name($now_order['pay_type'],$now_order['is_mobile_pay']);
		$com	=	M('Scenic_order_com')->field(true)->where(array('order_id'=>$now_order['order_id']))->select();
		foreach($com as $v){
			if($v['type'] == 1){
				$order_com['ticket'][]	=	$v;
			}else if($v['type'] == 2){
				$order_com['park'][]	=	$v;
			}else if($v['type'] == 3){
				$order_com['guide'][]	=	$v;
			}
		}
		if($order_com['guide']){
			$guide_name	=	M('Scenic_guide')->field('guide_name')->where(array('guide_id'=>$order_com['guide'][0]['type_id']))->find();
			$order_com['guide'][0]['guide_name']	=	$guide_name['guide_name'];
		}
		if($now_order['family_id']){
			$family_id = explode(",",$now_order['family_id']);
			foreach($family_id as $v){
				$scenic_family[]	=	M('Scenic_family')->field(true)->where(array('family_id'=>$v))->find();
			}
		}
		//$a_img = strstr($now_order['group_traveler_img'], ',',true);

		if($now_order['is_group']==2){
			//$store_image_class = new scenic_image();
			$now_order['group_traveler_img'] && $now_order['group_traveler_img'] = explode(';',$now_order['group_traveler_img']);
			$user_list = M('Scenic_group_user')->where(array('order_id'=>$now_order['order_id']))->select();
			$now_order['group_traveler'] = $user_list;
		}

        $this->assign('scenic_family', $scenic_family);
        $this->assign('now_order', $now_order);
        $this->assign('order_com', $order_com);
        $this->assign('scenic_ticket', $scenic_ticket);
        $this->display();
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
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'scenic/ticket',1,$param);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$merchant_image_class = new scenic_image();
				$url = $merchant_image_class->get_image_by_path($title,$this->config['site_url'],'ticket','-1');
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

	public function  verify(){
		$find_value = $_POST['find_value'];
		$find_type = $_POST['find_type'];

		if($find_type == 1 && strlen($find_value) == 14){
			$res = D('Scenic_order_com')->where(array('code'=>array('like','%'.$find_value.'%')))->find();
			$condition_scenic['order_id']=$res['order_id'];
		}else{
			if($find_type == 1){
				$res = D('Scenic_order_com')->where(array('code'=>array('like','%'.$find_value.'%')))->find();
				$condition_scenic['order_id']=$res['order_id'];
			}else if($find_type == 2){
				$condition_scenic['order_id'] = $find_value;
			}else if($find_type == 3){
				$condition_scenic['user_id']=$find_value;
			}else if($find_type == 4){
				$user = M('User')->where(array('nickname'=>array('like','%'.$find_value.'%')))->find();
				if($user){
					$condition_scenic['user_id'] = $user['uid'];
				}
			}else if($find_type == 5){
				$condition_scenic['phone'] = array('like','%'.$find_value.'%');
			}
		}
		$this->assign('find_type',$find_type);
		$this->assign('find_value',$find_value);
		$database_scenic = D('Scenic_order');
		$where['scenic_id']	=	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
		$condition_scenic['order_status']=1;

		$condition_scenic['ticket_time']=array('like','%'.date('Y-n-j').'%');
		$ticket_id = intval($_GET['ticket_id']);
		if($ticket_id){
			$condition_scenic['ticket_id']	=	$ticket_id;
		}
		$count_store = $database_scenic->where($condition_scenic)->count();
		$p = new Page($count_store,15);
		$now_order = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('order_id DESC')->select();

		foreach($now_order as &$v){
			$user	=	D('User')->get_user($v['user_id']);
			$v['nickname']	=	$user['nickname'];
			$v['phone']	=	$user['phone'];
			$ticket_title	=	M('Scenic_ticket')->field('ticket_title')->where(array('ticket_id'=>$v['ticket_id']))->find();
			$v['ticket_title']	=	$ticket_title['ticket_title'];
			$v['ticket_count']	=	M('Scenic_order_com')->where(array('order_id'=>$v['order_id'],'type'=>1))->count();
			$v['park_count']	=	M('Scenic_order_com')->where(array('order_id'=>$v['order_id'],'type'=>2))->count();
			$v['guide']			=	M('Scenic_order_com')->where(array('order_id'=>$v['order_id'],'type'=>3))->count();
		}
		$pagebar = $p->show();
		# 项目列表
		$ticket_list	=	M('Scenic_ticket')->field(true)->where($where)->select();
		$this->assign('ticket_list', $ticket_list);
		$this->assign('pagebar',$pagebar);
		$this->assign('now_order',$now_order);
		$this->display();
	}

	public function ajax_verify_ticket(){
		$where['order_id'] = $_POST['order_id'];
		$res = D('Scenic_order')->get_one_order($where);
		if($res){
			D('Scenic_order')->where($where)->setField('order_status',2);
			$where['type'] = 1;
			$save_data['status']  =2;
			$save_data['last_time'] = time();
			M('Scenic_order_com')->where(array('order_id'=>$_POST['order_id']))->save($save_data);
			// M('Scenic_order_com')->where(array('order_id'=>$_POST['order_id']))->setField('status',2);
			$ticket = M('Scenic_order_com')->field('count(*) as count ,type_id')->where($where)->select();
			$guide = M('Scenic_order_com')->field('type_id')->where(array('type'=>3,'order_id'=>$_POST['order_id']))->find();
			M('Scenic_guide')->where(array('guide_id'=>$guide['type_id']))->setInc('guide_service_number',1);
			$ticket = $ticket[0];
			M('Scenic_ticket')->where(array('ticket_id'=>$ticket['type_id']))->setInc('sale_count',$ticket['count']);
			//支付成功增加景区余额
			if(D('Scenic_money_list')->add_row($res['scenic_id'],1,$res['order_total'],'用户购买门票计入收入',$_POST['order_id'])){
				echo json_encode(array('errorCode'=>0,'errorMsg'=>'订单验证成功！'));exit;
			}else{
				echo json_encode(array('errorCode'=>1,'errorMsg'=>'订单验证失败！'));exit;
			}
		}else{
			echo json_encode(array('errorCode'=>1,'errorMsg'=>'订单不存在！'));exit;
		}
	}

	public function create_token(){
		$ticket_id = $_GET['ticket_id'];
		if($_GET['ac'] =='create'){
			$token = md5(md5($ticket_id.time()));
			M('Scenic_ticket')->where(array('ticket_id'=>$ticket_id))->setField('token',$token);
		} else{
			$token = $_GET['token'];
		}


		$this->assign('token',$token);
		$this->display();
	}

	public function token_list(){
		$where['ticket_id'] = $_GET['ticket_id'];
		$where['status'] = 1;

		$auth_list = D('Scenic_ticket')->get_authed_list_by_ticket_id($_GET['ticket_id']);

		$auth_status_txt = array(
			1=>'正常',
			2=>'已失效',
			3=>'门票关闭',
			4=>'过期',
		);
		$this->assign('status_txt',$auth_status_txt);
		$this->assign('list',$auth_list);
		$this->display();
	}

	public function token_search(){
		$where['token'] = $_POST['token'];
		if($res = D('Scenic_ticket')->get_scenic_one_ticket($where)){
			if($res['scenic_id'] == $this->merchant_session['scenic_id']){

				echo json_encode(array('errorCode'=>1,'errorMsg'=>'授权码：'.$_POST['token'].' 是同一个景区，无法授权'));exit;
			}
			echo json_encode(array('errorCode'=>0,'ticket'=>$res));exit;
		}else{
			echo json_encode(array('errorCode'=>1,'errorMsg'=>'门票不存在！'));exit;
		}
	}

	public function union_sell_status(){
		$ticket_id = $_POST['ticket_id'];
		$union_sell = $_POST['union_sell']?0:1;
		$token = $_POST['token'];
		$where['auth_ticket'] = $ticket_id;
		$where['token'] = $token;
		if(M('Scenic_ticket_token_list')->where($where)->setField('union_sell',$union_sell)){
			echo json_encode(array('errorCode'=>0,'msg'=>'更新成功','status'=>$union_sell));exit;
		}else{
			echo json_encode(array('errorCode'=>1,'ms'=>'更新失败'));exit;
		}

	}

	public function unlink(){
		$ticket_id = $_POST['ticket_id'];
		$token = $_POST['token'];
		$where['auth_ticket'] = $ticket_id;
		$where['token'] = $token;
		if(M('Scenic_ticket_token_list')->where($where)->delete()){
			echo json_encode(array('errorCode'=>0,'msg'=>'删除成功'));exit;
		}else{
			echo json_encode(array('errorCode'=>1,'ms'=>'删除失败'));exit;
		}
	}


	public function save_traveler(){
		$order_id = $_POST['order_id'];
		$person_id = $_POST['person_id'];

		$person_name = $_POST['person_name'];
		$phone = $_POST['phone'];
		if(empty($person_name)){
			echo json_encode(array('errorCode'=>1,'msg'=>'姓名必填'));exit;
		}
		if(empty($person_id)){
			echo json_encode(array('errorCode'=>1,'msg'=>'身份证必填'));exit;
		}
		if(empty($phone)){
			echo json_encode(array('errorCode'=>1,'msg'=>'手机号码必填'));exit;
		}
		$where['order_id'] = $order_id;
		$where['person_id'] = $person_id;
		$where['person_name'] = $person_name;

		$data['order_id']  =$order_id;
		$data['person_name']  =$person_name;
		$data['phone']  =$phone;
		$data['person_id']  =$person_id;
		$data['status']  =0;
		$data['use_num']  =0;
		$data['order_id']  =$order_id;
		$edit =0;
		if(M('Scenic_group_user')->where($where)->find()){
			$result = M('Scenic_group_user')->where($where)->save($data);
			$edit = 1;
		}else{
			$result = M('Scenic_group_user')->add($data);
		}

		if($result){
			echo json_encode(array('errorCode'=>0,'msg'=>'编辑成功','edit'=>$edit));exit;
		}else{
			echo json_encode(array('errorCode'=>1,'msg'=>'编辑失败','edit'=>$edit));exit;
		}
	}

	public  function send_sms(){
		$order_id = $_POST['order_id'];
		$phone = $_POST['phone'];
		$where['order_id'] = $order_id;
		$now_order = D('Scenic_order')->get_one_order($where);
		$condition['phone'] = $phone;
		$condition['order_id'] = $order_id;
		$now_traveler = D('Scenic_ticket')->get_group_traveler($condition);
		if($now_traveler['send_sms']){
			echo json_encode(array('errorCode'=>1,'msg'=>'已发送过了'));exit;
		}else{
			$now_scenic = D('Scenic_ticket')->get_scenic_one_ticket(array('ticket_id'=>$now_order['ticket_id']));
			$href = C('config.site_url').'/wap.php?c=Scenic_user&a=ticket_order_details&order_id='.$now_order['order_id'];
			$modes = D('Access_token_expires');
			$token = $modes->get_access_token();

			$short_url_now_user = long2short_url($token['access_token'],$href.'&order_token='.$now_order['token']);

			$sms_data = array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => 0, 'type' => 'ticket');
			$sms_data['uid'] = $phone;
			$sms_data['mobile'] = $phone;
			$sms_data['sendto'] = 'ticket_group_user';
			$desc_txt = $now_order['ticket_time'].' '.$now_scenic['scenic_title'];

			$sms_data['content'] = "您购买{$desc_txt}景区门票成功，消费码{$now_order['code']}。入园请戳以下链接:{$short_url_now_user} 获取二维码，凭二维码过闸机入园。建议手机截图本二维码保存，毋转发他人使用";
			$res = Sms::sendSms($sms_data);
			D('Scenic_group_user')->where($condition)->setField('send_sms',1);
			echo json_encode(array('errorCode'=>0,'msg'=>'发送成功'));exit;
		}

	}

	public function change_order_price(){
		$order_id = $_POST['order_id'];
		$price = $_POST['price'];
		$where['order_id'] = $order_id;
		$res = D('Scenic_order')->get_one_order($where);
		if($res['paid']==2){
			echo json_encode(array('errorCode'=>1,'msg'=>'订单已支付，不能修改'));exit;
		}
		if($price<0){
			echo json_encode(array('errorCode'=>1,'msg'=>'金额错误'));exit;
		}
		if(M('Scenic_order')->where($where)->setField('order_total',$price)){
			echo json_encode(array('errorCode'=>1,'msg'=>'修改成功'));exit;
		}else{
			echo json_encode(array('errorCode'=>1,'msg'=>'修改失败'));exit;
		}
	}
}