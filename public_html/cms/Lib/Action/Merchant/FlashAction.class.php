<?php
/**
 *首页幻灯片回复
**/
class FlashAction extends BaseAction
{
	public $tip;
	
	public function _initialize()
	{
		parent::_initialize();
		
		$this->tip = isset($_REQUEST['tip']) ? intval($_REQUEST['tip']) : 1;
		
		$this->assign('tip', $this->tip);
	}
	
	public function index()
	{
		$this->tip = 1;
		$this->assign('tip', $this->tip);
		
		$db = D('Flash');
		$where['token'] = $this->token;
		$where['tip'] = $this->tip;
		$count = $db->where($where)->count();
		import('@.ORG.merchant_page');
		$page = new Page($count, 25);
		$info = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id DESC')->select();
		$this->assign('page', $page->show());
		$this->assign('info', $info);
		$this->display();
	}
	
	public function back()
	{
		$this->tip = 2;
		$this->assign('tip', $this->tip);
		
		$db = D('Flash');
		$where['token'] = $this->token;
		$where['tip'] = $this->tip;
		$count = $db->where($where)->count();
		import('@.ORG.merchant_page');
		$page = new Page($count, 25);
		$info = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id DESC')->select();
		$this->assign('page', $page->show());
		$this->assign('info', $info);
		$this->display('index');
	}
	
	public function add()
	{
		$this->tip = 1;
		$this->assign('tip', $this->tip);
		$this->display('add');
	}
	
	public function addbg()
	{
		$this->tip = 2;
		$this->assign('tip', $this->tip);
		$this->display('add');
	}
	
	public function edit()
	{
		$this->tip = 1;
		$this->assign('tip', $this->tip);
		
		$where['id'] = $this->_get('id','intval');
		$where['token'] = $this->token;
		$res = D('Flash')->where($where)->find();
		$this->assign('info', $res);
		
		$this->assign('id',$this->_get('id','intval'));
		$this->display('add');
	}
	
	public function editbg()
	{
		$this->tip = 2;
		$this->assign('tip', $this->tip);
		
		$where['id'] = $this->_get('id','intval');
		$where['token'] = $this->token;
		$res = D('Flash')->where($where)->find();
		$this->assign('info', $res);
		$this->assign('id',$this->_get('id','intval'));
		$this->display('add');
	}
	
	public function del()
	{
		$where['id'] = $this->_get('id','intval');
		$where['token'] = $this->token;
		if (D('Flash')->where($where)->delete()) {
			if ($this->tip == 2) {
				$this->success('操作成功', U('Flash/back'));
			} else {
				$this->success('操作成功', U('Flash/index'));
			}
			//$this->success('操作成功', U('Flash/index', array('tip' => $this->tip)));
		} else {
			if ($this->tip == 2) {
				$this->error('操作失败', U('Flash/back'));
			} else {
				$this->error('操作失败', U('Flash/index'));
			}
			//$this->error('操作失败', U('Flash/index', array('tip' => $this->tip)));
		}
	}
	
	
	public function insert()
	{
		$flash = D('Flash');
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$arr = array();
		$arr['token'] = $this->token;
		$arr['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
		$arr['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
		$arr['tip'] = $this->tip;
		$image = D('Image')->handle($this->merchant_session['mer_id'], 'flash', 1);
		if (!$image['error']) {
			$arr = array_merge($arr, $image['url']);
		}
		if(!empty($_POST['image_select'])){
			$arr['img']=$_POST['image_select'];
		}
		if ($id) {
			$flash->where(array('id' => $id, 'token' => $this->token))->save($arr);
		} else {
			if (empty($arr['img'])) $this->error('没有上传图片');
			$id = $flash->add($arr);
		}
		
		$id && D('Image')->update_table_id($arr['img'], $id, 'flash');

		if ($this->tip == 2) {
			$this->success('操作成功', U('Flash/back'));
		} else {
			$this->success('操作成功', U('Flash/index'));
		}
	}
	
	
	public function upsave()
	{
		$flash = D('Flash');
		$id = $this->_get('id','intval');
		$tip = $this->tip;
		$arr = array();
		$arr['img'] = $this->_post('img');
		$arr['url'] = $this->_post('url');
		$arr['info'] = $this->_post('info');
		$flash->where(array('id' => $id))->save($arr);

		if ($this->tip == 2) {
			$this->success('操作成功', U('Flash/back'));
		} else {
			$this->success('操作成功', U('Flash/index'));
		}
		//$this->success('操作成功',U(MODULE_NAME.'/index',array('tip'=>$this->tip)));
	}

}
?>