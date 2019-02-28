<?php
class LinkPCAction extends BaseAction
{

    public $modules;

    public function _initialize()
    {
        parent::_initialize();
        
        $this->modules = array(
            'Home' => '首页',
            'Portal' => '门户首页',
            'Activity' => '限时活动',
            'KuaiDian' => '快店',
            'Appoint' => '预约',
            'Meal' => '餐饮',
            'Group' => '团购',
            'Mall' => '商城',
            'Group_Xiuxianyule' => '休息娱乐',
            'Group_Around' => '附近优惠',
            'Classify' => '分类信息',
            'Life' => '生活缴费',
            'News' => '平台快报',
            'Gift' => $this->config['gift_alias_name'],
            'Scenic_index' => '景区首页'
        );
    }
	public function insert()
	{
		$modules = $this->modules();
		$this->assign('modules', $modules);
		$this->display();
	}
	public function modules()
	{
		
		$t = array();
		$t[] = array('module' => 'Home', 'linkcode' => $this->config['site_url'], 'name' => '首页', 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Home'], 'askeyword' => 1);
		
		if(isset($this->config['portal_switch'])){
			$t[] = array('module' => 'Portal', 'linkcode' => $this->config['site_url'] . '/index.php?g=Portal&c=Index&a=index', 'name' => '门户首页', 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Portal'], 'askeyword' => 1);
		}

		$t[] = array('module' => 'Activity', 'linkcode' => $this->config['site_url'] . '/activity/', 'name' => '限时活动', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Activity'], 'askeyword' => 1);
		$t[] = array('module' => 'KuaiDian', 'linkcode' => $this->config['site_url'] . '/shop', 'name' => '快店', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['KuaiDian'], 'askeyword' => 1);
		if($this->config['appoint_site_url']){
			$t[] = array('module' => 'Appoint', 'linkcode' => $this->config['appoint_site_url'], 'name' => '预约', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Appoint'], 'askeyword' => 1);
		}else{
			$t[] = array('module' => 'Appoint', 'linkcode' => $this->config['site_url'] . '/appoint/', 'name' => '预约', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Appoint'], 'askeyword' => 1);
		}
		$t[] = array('module' => 'Meal', 'linkcode' => $this->config['site_url'] . '/meal/all', 'name' => '餐饮', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Meal'], 'askeyword' => 1);
		$t[] = array('module' => 'Mall', 'linkcode' => $this->config['site_url'] . '/mall', 'name' => '商城', 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Mall'], 'askeyword' => 1);
		$t[] = array('module' => 'Group', 'linkcode' => $this->config['site_url'] . '/category/all/all', 'name' => '团购', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Group'], 'askeyword' => 1);
		$t[] = array('module' => 'Group_Around', 'linkcode' => $this->config['site_url'] . '/group/around/', 'name' => '附近优惠', 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Group_Around'], 'askeyword' => 1);
		if(isset($this->config['wap_home_show_classify'])){
			$t[] = array('module' => 'Classify', 'linkcode' => $this->config['site_url'] . '/classify/', 'name' => '分类信息', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Classify'], 'askeyword' => 1);
		}
		if($this->config['live_service_have']){
			$t[] = array('module' => 'Life', 'linkcode' => $this->config['site_url'] . '/topic/life.html', 'name' => '生活缴费', 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Life'], 'askeyword' => 1);
		}
		$t[] = array('module' => 'News', 'linkcode' => $this->config['site_url'] . '/news/', 'name' => '平台快报', 'sub' => 1, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['News'], 'askeyword' => 1);
		
		if($this->config['gift_alias_name']){
			$t[] = array('module' => 'Gift', 'linkcode' => $this->config['site_url'] . '/index.php?g=Index&c=Gift&a=index', 'name' =>  $this->config['gift_alias_name'], 'sub' => 0, 'canselected' => 1,'linkurl' => '','keyword' => $this->modules['Life'], 'askeyword' => 1);
		}
		if(isset($this->config['scenic_now_city'])){
			$t[] = array('module' => 'Scenic_index', 'linkcode' => $this->config['site_url'] . '/scenic.php','name'=>$this->modules['Scenic_index'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		return $t;
	}

	public function News()
	{
		$this->assign('moduleName', $this->modules['News']);
		$where['status'] =  1;
		$db = D('System_news_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('id' => $item['id']))->find()) {
				array_push($items, array('id' => $item['id'], 'sub' => 1, 'name' => $item['name'], 'linkcode'=> str_replace('admin.php', 'wap.php', $this->config['site_url'].'/news/cat-'.$item['id'].'.html'),'sublink' => U('Link/news', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Group/index', array('cat_url' => $item['cat_url']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Activity()
	{
		$this->assign('moduleName', $this->modules['Activity']);
		$where = array('status' => 1, 'is_finish' => 0);
		$db = D('Extension_activity_list');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['pigcms_id'], 'sub' => 0, 'name' => $item['title'], 'linkcode'=> $this->config['site_url'] . '/activity/' . $item['pigcms_id'] . '.html','sublink' => '','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Group()
	{
		$this->assign('moduleName', $this->modules['Group']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid);
		$db = D('Group_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/category/' . $item['cat_url'] . '/all','sublink' => U('LinkPC/Group', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/category/' . $item['cat_url'] . '/all','sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function KuaiDian()
	{
		$this->assign('moduleName', $this->modules['KuaiDian']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid, 'status' => 1);
		$db = D('Shop_category');
		$count = $db->where($where)->count();
		$Page = new Page($count, 5);
		$show = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/shop/' . $item['cat_url'], 'sublink' => U('LinkPC/KuaiDian', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/shop/' . $item['cat_url'], 'sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Meal()
	{
		$this->assign('moduleName', $this->modules['Meal']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid, 'status' => 1);
		$db = D('Meal_store_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/kd/' . $item['cat_url'] . '/all','sublink' => U('LinkPC/KuaiDian', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/kd/' . $item['cat_url'] . '/all','sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function Classify()
	{
		$this->assign('moduleName', $this->modules['Classify']);
		$where = array('subdir' => 1, 'cat_status' => 1);
		$db = D('Classify_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['cid'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/classify/subdirectory-' . $item['cid'] . '.html','sublink' => '','keyword' => $item['cat_name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Appoint()
	{
		$this->assign('moduleName', $this->modules['Appoint']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid, 'cat_status' => 1);
		$db = D('Appoint_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/appoint/category/' . $item['cat_url'] . '/all','sublink' => U('LinkPC/Appoint', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> $this->config['site_url'] . '/appoint/category/' . $item['cat_url'] . '/all','sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
}
?>