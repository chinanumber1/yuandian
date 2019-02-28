<?php
/*
 * 专题管理
 */
class SpecialAction extends BaseAction{
	public function shop(){
		$database_special = D('Special');
		$condition_special['type'] = '1';
        $count_special = $database_special->where($condition_special)->count();
        import('@.ORG.system_page');
        $p = new Page($count_special, 15);
        $special_list = $database_special->field(true)->where($condition_special)->order('`pigcms_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        if($special_list){
			foreach($special_list as &$v){
				$v['url']	=	$this->config['site_url'].'/wap.php?c=Special&id='.$v['pigcms_id'];
			}
        }
		$this->assign('special_list', $special_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

		$this->display();
	}
	# 专题二维码 2016-12-13 hanlu
	public function shop_qr_code(){
		$pigcms_id	=	$_GET['pigcms_id'];
		$special	=	M('Special')->field(true)->where(array('pigcms'=>$pigcms_id))->find();
		if($special){
			$special['url']	=	$this->config['site_url'].'/wap.php?c=Special&id='.$special['pigcms_id'];
		}
		$this->assign('special', $special);
		$this->display();
	}
	public function shop_add(){
		$this->display();
	}
	public function motify(){
		$data_special['name'] = $_POST['name'];
		$data_special['desc'] = $_POST['desc'];
		$data_special['bgcolor'] = $_POST['bgcolor'];
		$data_special['image'] = $_POST['image'];
		$data_special['coupon'] = $_POST['coupon'] ? serialize($_POST['coupon']) : '';
		$data_special['product_list'] = serialize($_POST['product_list']);
		$data_special['type'] = $_POST['type'];
		$data_special['status'] = '1';
		$data_special['last_time'] = $_SERVER['REQUEST_TIME'];
		if(M('Special')->data($data_special)->add()){
			$this->success('添加成功');
		}else{
			$this->error('添加失败，请重试');
		}
	}
	public function amend(){
		$condition_special['pigcms_id'] = $_POST['pigcms_id'];
		$data_special['name'] = $_POST['name'];
		$data_special['desc'] = $_POST['desc'];
		$data_special['bgcolor'] = $_POST['bgcolor'];
		$data_special['image'] = $_POST['image'];
		$data_special['coupon'] = $_POST['coupon'] ? serialize($_POST['coupon']) : '';
		$data_special['product_list'] = serialize($_POST['product_list']);
		$data_special['last_time'] = $_SERVER['REQUEST_TIME'];
		if(M('Special')->where($condition_special)->data($data_special)->save()){
			$this->success('编辑成功');
		}else{
			$this->error('编辑失败，请重试');
		}
	}
	public function del(){
		$condition_special['pigcms_id'] = $_POST['id'];
		if(M('Special')->where($condition_special)->delete()){
			$this->success('删除成功');
		}else{
			$this->error('删除失败，请重试');
		}
	}
	public function shop_edit(){
		$database_special = D('Special');
		$condition_special['pigcms_id'] = $_GET['id'];
        $now_special = $database_special->where($condition_special)->find();
		if($now_special['coupon']){
			$now_special['coupon'] = unserialize($now_special['coupon']);
		}
		$now_special['product_list'] = unserialize($now_special['product_list']);
		
		foreach($now_special['product_list'] as $key=>$value){
			$value['name'] = trim($value['name']);
			$value['name'] = rtrim($value['name'],'	');
			$now_special['product_list'][$key]['name'] = trim($value['name']);
		}
		$product_list_json = json_encode($now_special['product_list']);
		$product_list_json = str_replace('\t','',$product_list_json);
		// dump($now_special['product_list']);
		$this->assign('now_special', $now_special);
		$this->assign('product_list_json', $product_list_json);
		$this->assign('now_special', $now_special);
		$this->display();
	}
	public function choose_shop(){
		$this->assign('bg_color','#F3F3F3');

		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['have_shop'] = '1';
		$condition_merchant_store['status'] = '1';
		$where="`s`.`store_id`=`ss`.`store_id` AND `s`.`status`='1' AND `s`.`have_shop`='1'";
		if($_GET['search']){
			$where.=" AND s.name like '%{$_GET['search']}%'";
		}
		$count_store = M()->table(array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'merchant_store_shop'=>'ss'))->where($where)->count();

		import('@.ORG.system_page');
		$p = new Page($count_store, 30);

		$store_list = M()->field("`s`.`store_id`, `s`.`pic_info`, `s`.`name`, `s`.`adress`, `s`.`phone`, `s`.`sort`,`ss`.`sale_count`,`ss`.`deliver_type`")->table(array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'merchant_store_shop'=>'ss'))->where($where)->order("`s`.`sort` DESC,`s`.`store_id` ASC")->limit($p->firstRow . ',' . $p->listRows)->select();

		$store_image_class = new store_image();
		foreach($store_list as &$value){
			$tmp_pic_arr = explode(';',$value['pic_info']);
			$value['image'] = $store_image_class->get_image_by_path($tmp_pic_arr[0]);
		}

		$this->assign('store_list', $store_list);
		// dump($store_list);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);

		$this->display();
	}
	public function choose_shop_coupon(){
		$this->assign('bg_color','#F3F3F3');

		$coupon  = D('System_coupon');
		$coupon_list = $coupon->get_coupon_list();
		// dump($coupon_list);
		$this->assign('coupon_list', $coupon_list);

		$this->display();
	}
	public function ajax_upload_pic(){
		$dom_id = $_POST['id'];
		if($_FILES['file']['error'] != 4){
			$image = D('Image')->handle($this->system_session['id'], 'special');
			if ($image['error']) {
				die('{"jsonrpc":"2.0","result":{"error_code":1001,"message":"'.$image['msg'].'"},"id":"'.$dom_id.'"}');
			} else {
				echo json_encode(array('error_code'=>0,'url'=>$image['url']['file']));
				// die('{"jsonrpc":"2.0","result":{"error_code":0,"pigcms_id":'.$pigcms_id.',"url":"'.$image['url']['file'].'"},"id":"'.$dom_id.'"}');
			}
		}else{
			die('{"jsonrpc":"2.0","result":{"error_code":1000,"message":"没有选择图片！"},"id":"'.$dom_id.'"}');
		}
	}
}