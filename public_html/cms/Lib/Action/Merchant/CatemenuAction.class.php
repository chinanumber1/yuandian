<?php
class CatemenuAction extends BaseAction 
{
	
    public $fid;
    
    public function _initialize() 
    {
        parent::_initialize();
        $this->fid = isset($_REQUEST['fid']) ? intval($_REQUEST['fid']) : 0;
        $this->assign('fid', $this->fid);
        if ($this->fid) {
            $thisCatemenu = M('Catemenu')->find($this->fid);
            $this->assign('thisCatemenu', $thisCatemenu);
        }
    }
    public function index()
    {
        $db = D('catemenu');
        $where['token'] = $this->token;
        $where['fid'] = intval($_GET['fid']);
        $count = $db->where($where)->count();
        //var_dump($count);
        //echo $db->getlastsql();
        $page = new Page($count,25);
        $info = $db->where($where)->order('orderss desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('countMenu', $count);
        $this->assign('page', $page->show());
        $this->assign('info', $info);
        $this->display();
    }
    
    public function add(){
        $this->display();
    }
    
    public function edit()
    {
        $id = $this->_get('id','intval');
        $info = M('Catemenu')->where(array('id' => $id, 'token' => $this->token))->find();
        $this->assign('info',$info);
        $this->display('add');
    }
    
    public function del()
    {
        $where['id'] = $this->_get('id','intval');
        $where['token'] = $this->token;
		
        if (D(MODULE_NAME)->where($where)->delete()){
            $fidwhere['fid']=intval($where['id']);
            D('Catemenu')->where($fidwhere)->delete();
            $this->success('操作成功',U('Catemenu/index',array('fid'=>$_GET['fid'])));
        } else {
            $this->error('操作失败',U('Catemenu/index',array('fid'=>$_GET['fid'])));
        }
    }
    
    public function insert()
    {
        $db = D('Catemenu');
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        if (empty($name)) {
        	$this->error('菜单名不能为空!');
        }
        $old = $db->where("`name`='{$name}' AND `token`='{$this->token}' AND `id`<>'{$id}'")->find();
        if ($old) {
        	$this->error('菜单名已经存在!');
        }
        $orderss = isset($_POST['orderss']) ? intval($_POST['orderss']) : 0;
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
        $url = isset($_POST['url']) ? $_POST['url'] : '';
        $picurl = isset($_POST['picurl']) ? $_POST['picurl'] : '';
        
        $fid = isset($_POST['fid']) ? intval($_POST['fid']) : 0;
        $data = array('name' => $name, 'status' => $status, 'orderss' => $orderss, 'url' => $url, 'fid' => $fid);
        
        $image = D('Image')->handle($this->merchant_session['mer_id'], 'catemenu', 1);
        if (!$image['error']) {
        	$data = array_merge($data, $image['url']);
        }
        
        $data['picurl'] = $picurl ? $picurl : ($data['img'] ? $data['img'] : '');
        if ($id) {
        	$res = $db->where(array('id' => $id, 'token' => $this->token))->save($data);
        } else {
        	$data['token'] = $this->token;
        	$id = $res = $db->add($data);
        }
        $id && D('Image')->update_table_id($data['img'], $id, 'catemenu');
        if ($res) {
        	$this->success('操作成功',U('Catemenu/index',array('fid' => $_POST['fid'])));
        } else {
        	$this->error('操作失败',U('Catemenu/index',array('fid' => $_POST['fid'])));
        }
    }
	
    public function upsave(){
		$token = session('token');
		S("bottomMenus_".$token,NULL);

        $this->all_save();
    }
    
    public function styleSet()
    {
        $db=D('Home');
        $RadioGroup1=$db->where(array('token'=>$this->token))->getfield("RadioGroup");
        //var_dump($RadioGroup1);

        $this->assign('RadioGroup1',$RadioGroup1);
        $this->assign('radiogroup',$RadioGroup1);
        $this->display();
    }
    
    public function styleChange()
    {
        $db = D('Home');
        $info = $db->where(array('token'=>$this->token))->find();
        $radiogroup = $this->_get('radiogroup');
		$token = $this->token;
        $data['radiogroup'] = $radiogroup;
        if ($info == false) {
        	$data['token'] = $this->token;
            $res = $db->add($data);
        } else {
            $data['id'] = $info['id'];
            $res = $db->save($data);
        }
        import('ORG.Util.Dir');
        Dir::delDirnotself('./runtime');
    }
    
    public function colorChange()
    {
        $db=M('styleset');
        $info=$db->where(array('token'=>$this->token))->find();
        $plugmenucolor=$this->_get('themestyle');
        $data['plugmenucolor']=$plugmenucolor;
        if($info==false){
            $res=$db->add($data);
        }else{
            $data['id']=$info['id'];
            $res=$db->save($data);
            //echo $data['plugmenucolor'];exit;
        }
    }
	
	public function chooseMenu()
	{
		$tpid = isset($_GET['tpid']) ? intval($_GET['tpid']) : 0;
		include('./PigCms/Lib/ORG/radiogroup.php');
		$this->assign("info", $bottomMenu[$tpid]);
		$this->assign('menu',$bottomMenu);
		$this->display();
	}
	
	public function plugmenu()
	{
		$where = array('token' => $this->token);
// 		$menuArr = array('tel','memberinfo','nav','message','share','home','album','email','shopping','membercard','activity','weibo','tencentweibo','qqzone','wechat','music','video','recommend','other');
		$home = D('Home')->where(array('token' => $this->token))->find();
// 		$plugmenu_db = M('site_plugmenu');
// 		if (!$home) {
// 			$this->error('请先配置3g网站信息',U('Home/set',array('token' => $this->token)));
// 		} else {
			if (IS_POST) {
				//保存版权信息和菜单颜色
				if ($home) {
					D('Home')->where($where)->save(array('plugmenucolor' => $this->_post('plugmenucolor'), 'copyright' => $this->_post('copyright')));
				} else {
					D('Home')->add(array('token' => $this->token, 'plugmenucolor' => $this->_post('plugmenucolor'), 'copyright' => $this->_post('copyright')));
				}
				
// 				echo D('Home')->_sql();
				//保存各个菜单
				//先删除原来的
// 				$plugmenu_db->where($where)->delete();
// 				//添加
// 				foreach ($menuArr as $m) {
// 					$row = array('token'=>$this->token);
// 					$row['name'] = $m;
// 					$row['url'] = $this->_post('url_'.$m);
// 					$row['taxis'] = intval($_POST['sort_'.$m]);
// 					$row['display'] = intval($_POST['display_'.$m]);
// 					$plugmenu_db->add($row);
// 				}
				$this->success('设置成功',U('Catemenu/plugmenu',array('token'=>$this->token)));
			} else {
// // 				$homeInfo = D('Home')->where($where)->find();
// 				echo "<Pre>";
// 				print_r($home);die;
				if (!$home['plugmenucolor']) {
					$home['plugmenucolor'] = '#ff0000';
				}
// 				$this->assign('userGroup',$this->userGroup);
				$this->assign('homeInfo', $home);
// 				$menus=$plugmenu_db->where($where)->select();
// 				$menusArr=array();
// 				foreach ($menus as $m){
// 					$menusArr[$m['name']]=$m;
// 				}
// 				$this->assign('menus',$menusArr);
				$this->display();
			}
// 		}
	}
	
	public function music()
	{
		$where = array('token' => $this->token);
		$home = D('Home')->where(array('token' => $this->token))->find();
		if (IS_POST) {
			if ($home) {
				D('Home')->where($where)->save(array('musicurl' => $this->_post('musicurl')));
			} else {
				D('Home')->add(array('token' => $this->token, 'musicurl' => $this->_post('musicurl')));
			}
			$this->success('设置成功', U('Catemenu/music', array('token' => $this->token)));
		} else {
			$this->assign('homeInfo', $home);
			$this->display();
		}
	}
}
?>