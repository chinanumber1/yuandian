<?php
/*
 * BBS管理
 *
 */
class Bbs_manageAction extends CommonAction{
	public $now_bbs;
	public function _initialize(){
		parent::_initialize();
		$this->now_bbs = session('now_bbs');
		if(ACTION_NAME != 'index' && empty($this->now_bbs)){
			$this->error('访问超时，请重新进入。');
		}
		$this->static_path   = './tpl/Merchant/default/static/';
		$this->assign('static_path',$this->static_path);
		
		$this->assign('no_sidebar',true);
	}
    public function index(){
		switch($_GET['type']){
			case 'house':	//小区
				if(empty($_SESSION['house'])){
					$this->assign('jumpUrl',$this->config['site_url'].'/shequ.php');
					$this->error('请先进行登录！');
				}else{
					$third_type = 'house';
					$third_id = $_SESSION['house']['village_id'];
				}
				break;
			case 'merchant':	//商家
				if(empty($_SESSION['merchant'])){
					$this->assign('jumpUrl',$this->config['site_url'].'/merchant.php');
					$this->error('请先进行登录！');
				}else{
					$third_type = 'merchant';
					$third_id = $_SESSION['merchant']['mer_id'];
				}
				break;
			default:
				$this->error('非法访问！');
		}
		$condition_bbs = array(
			'third_type' => $third_type,
			'third_id' => $third_id,
		);
		$now_bbs = D('Bbs')->field(true)->where($condition_bbs)->find();
		if(empty($bbs)){
			$bbs_id = D('Bbs')->data($condition_bbs)->add();
			if($bbs_id){
				$now_bbs = $condition_bbs;
				$now_bbs['bbs_id'] = $bbs_id;
			}else{
				$this->error('社区BBS创建失败');
			}
		}
		session('now_bbs',$now_bbs);
		redirect(U('cat_list'));
    }
	public function cat_list(){
		$this->display();
	}
}