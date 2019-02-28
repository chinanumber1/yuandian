<?php
class DiypageAction extends BaseAction {
	public $now_store = array();
	public function _initialize(){
		parent::_initialize();
		
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['store_id'] = $_GET['store_id'] ? $_GET['store_id'] : $_POST['store_id'];
		$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
		if ($this->merchant_session['store_id'] && $this->merchant_session['store_id'] != $condition_merchant_store['store_id']) {
		    $this->error('您没有这个权限');
		}
		$this->now_store = $database_merchant_store->where($condition_merchant_store)->find();
		if(IS_GET){
			if(empty($this->now_store)){
				$this->error('店铺不存在！');
			}
			if(!empty($this->now_store['pic_info'])){
				$store_image_class = new store_image();
				$tmp_pic_arr = explode(';',$this->now_store['pic_info']);
				foreach($tmp_pic_arr as $key=>$value){
					$this->now_store['pic'][$key]['title'] = $value;
					$this->now_store['pic'][$key]['url'] = $store_image_class->get_image_by_path($value);
				}
			}
			$this->assign('now_store',$this->now_store);
		}
	}
	public function index(){
		$database_diypage = D('Merchant_store_diypage');
		$condition_diypage = array('store_id'=>$this->now_store['store_id'],'is_remove'=>'0');
		$count_diypage = $database_diypage->where($count_diypage)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count_diypage,20);
		
		$page_list = $database_diypage->where($condition_diypage)->order('`page_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('page_list',$page_list);
		$this->assign('pagebar',$p->show());
		
		$this->display();
	}
	public function create(){
		if($_GET['page_id']){
			$condition_page['page_id'] = $_GET['page_id'];
			$condition_page['store_id'] = $this->now_store['store_id'];
			$now_page = D('Merchant_store_diypage')->where($condition_page)->find();
			$now_page_custom = D('Merchant_store_diypage_field')->where($condition_page)->order('`field_id` ASC')->select();
			// dump($now_page_custom);
			foreach($now_page_custom as $key=>$value){
				$now_page_custom[$key]['content'] = unserialize($value['content']);
				if($value['field_type'] == 'rich_text'){
					$now_page_custom[$key]['content'] = str_replace("font-family:'Microsoft YaHei';",'',$now_page_custom[$key]['content']);
					$now_page_custom[$key]['content'] = str_replace("'allowfullscreen'",'"allowfullscreen"',$now_page_custom[$key]['content']);
				}
			}
			// dump($now_page_custom);
			$this->assign('now_page',$now_page);
			$this->assign('now_page_custom',$now_page_custom);
		}
		
		$this->display();
	}
	public function set_home(){
		$condition_page['store_id'] = $this->now_store['store_id'];
		D('Merchant_store_diypage')->where($condition_page)->data(array('is_home'=>'0'))->save();
		if(empty($_GET['close'])){
			$condition_page['page_id'] = $_GET['page_id'];
			D('Merchant_store_diypage')->where($condition_page)->data(array('is_home'=>'1'))->save();
		}
		$this->success('设置成功');
	}
	public function delete(){
		$condition_page['store_id'] = $this->now_store['store_id'];
		$condition_page['page_id'] = $_GET['page_id'];
		if(D('Merchant_store_diypage')->where($condition_page)->data(array('is_remove'=>'1'))->save()){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	public function upload(){
		if ($_FILES['file']['error'] != 4) {
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'diypage', 1);

			if ($image['error']) {
				exit(json_encode($image));
			} else {
				if($image['url']['imgFile'])
					$url = $image['url']['imgFile'];
				else
					$url = $image['url']['file'];
				$image = D('Image')->field(true)->where(array('pic_md5' => md5($url)))->find();

				exit(json_encode(array('error' => 0,'pigcms_id'=>$image['pigcms_id'], 'url' => $url, 'id' => $_POST['id'])));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	public function page_add(){
		$page_id=$_POST['page_id'];
		$database_diypage = D('Merchant_store_diypage');
		$database_diypage_field = D('Merchant_store_diypage_field');
		$data_diypage_field['mer_id'] = $this->now_store['mer_id'];
		$data_diypage_field['store_id'] = $this->now_store['store_id'];
		$data_diypage_field['page_id'] = $page_id;
		
		$data_page['page_name'] = $_POST['page_name'];
		$data_page['page_desc'] = $_POST['page_desc'];
		$data_page['bgcolor'] = $_POST['bgcolor'];
		$data_page['add_time'] = time();
		if(empty($_POST['page_id'])){
			$data_page['mer_id'] = $this->now_store['mer_id'];
			$data_page['store_id'] = $this->now_store['store_id'];
			$data_diypage_field['page_id'] = $page_id = $database_diypage->data($data_page)->add();
		}else{
			$data_diypage_field['page_id'] = $page_id = $_POST['page_id'];
			$database_diypage->where($data_diypage_field)->data($data_page)->save();
			
			$database_diypage_field->where($data_diypage_field)->delete();
		}
		if(empty($page_id)){
			$this->error('保存失败');
		}
		$custom = $_POST['custom'];
		foreach($_POST['stock'] as $v){
			$data_diypage_field['field_type'] = $custom[$v]['type'];
			unset($custom[$v]['type']);
			array_walk_recursive($custom[$v],'arr_htmlspecialchars_decode');
			$data_diypage_field['content'] = serialize($custom[$v]);
			$database_diypage_field->data($data_diypage_field)->add();
		}
		
		//foreach($_POST['custom'] as $value){
		//	$data_diypage_field['field_type'] = $value['type'];
		//	unset($value['type']);
		//	array_walk_recursive($value,'arr_htmlspecialchars_decode');
		//	$data_diypage_field['content'] = serialize($value);
		//	$database_diypage_field->data($data_diypage_field)->add();
		//}
		$this->success('保存成功');
	}
	public function group(){
		
		$group_return = D('Group')->diypage_store_group_list($this->now_store['store_id']);
		// dump($group_return);
		$this->assign($group_return);
		$this->display();
	}
	public function activity_module(){
		
		$database_wxapp_list = D('Wxapp_list');
		$condition_wxapp_list = array('mer_id'=>$this->now_store['mer_id']);
		$condition_wxapp_list = array('status'=>1);
		if($_POST['keyword']){
			$condition_wxapp_list['title'] = array('like',$_POST['keyword']);
		}
		$count_wxapp = $database_wxapp_list->where($condition_wxapp_list)->count();
		import('@.ORG.diypage');
		$p = new Page($count_wxapp,8);
		
		$wxapp_list = $database_wxapp_list->where($condition_wxapp_list)->order('`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();

		$this->assign('page_bar',$p->show());
		$this->assign('wxapp_list',$wxapp_list);
		
		
		$selecteditemsArr = explode(',',$_GET['selecteditems']);
		$this->assign('selecteditemsArr',$selecteditemsArr);
		
		$this->display();
	}
	//选择页面
	public function page(){
		$database_diypage = D('Merchant_store_diypage');
		$condition_diypage = array('store_id'=>$this->now_store['store_id']);
		if($_POST['keyword']){
			$condition_diypage['page_name'] = array('like',$_POST['keyword']);
		}
		$count_diypage = $database_diypage->where($condition_diypage)->count();
		import('@.ORG.diypage');
		$p = new Page($count_diypage,8);
		
		$page_list = $database_diypage->where($condition_diypage)->order('`page_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();

		$this->assign('page_bar',$p->show());
		$this->assign('page_list',$page_list);
		
		$this->display();
	}
	//选择商品
	public function good(){
		$selecteditemsArr = explode(',',$_GET['selecteditems']);
		$this->assign('selecteditemsArr',$selecteditemsArr);
		
		$this->assign(D('Shop_goods')->get_list_by_storeid_diypage($this->now_store['store_id']));
		$this->display();
	}
	//选择商品
	public function goods(){
		$this->assign(D('Shop_goods')->get_list_by_storeid_diypage($this->now_store['store_id']));
		$this->display();
	}
	//选择图片
	public function imageList(){
		$search = isset($_POST['keyword']) ? $_POST['keyword'] : '';
		$condition = "`otype`=1 AND `oid`='{$this->merchant_session['mer_id']}' AND `status`=1";
		if ($search) $condition .= " AND `img_remark` LIKE '%{$search}%'";
		$count = D('Image')->where($condition)->count();
		import('@.ORG.diypage');
		$Page = new Page($count,27);
		$image_list = D('Image')->where($condition)->order('`pigcms_id` DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$return['count'] = $count;
		$return['image_list'] = $image_list;
		$return['page_bar'] = $Page->show();
		$this->success($return);
	}
	//选择优惠券
	public function coupon(){
		$couponList = D('Card_new_coupon')->get_coupon_list_by_merid_diypage($this->merchant_session['mer_id']);
		// dump($couponList);
		
		$selecteditemsArr = explode(',',$_GET['selecteditems']);
		
		$this->assign($couponList);
		$this->assign('selecteditemsArr',$selecteditemsArr);
		$this->display();
	}
	
}
?>