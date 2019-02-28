<?php
/**
* 黄页板块
*/
class YellowAction extends BaseAction {
	public function _initialize(){
		parent::_initialize();
		$this->assign('portal_name','生活黄页');
	}
	// 首页
	public function index(){
		// 黄页板块分类
		$all_category_list = D('Group_category')->get_category();

		$where['status'] = 1;
		$pid = (int)$_GET['pid'];
		$cid = (int)$_GET['cid'];
		if($pid){
			$where['pid'] = $pid;
		}
		if($cid){
			$where['cid'] = $cid;
		}
		$title = trim($_GET['wd']);
		if($title){
			$where['title'] = array('like','%'.$title.'%');
		}

		$where['city'] = C('config.now_city');
		
		$area_id = (int)$_GET['area_id'];
		if($area_id){
			$where['area'] = $area_id;
			$this->assign('area_id',$area_id);
		}

		$count = D('Portal_yellow')->where($where)->count();
        import('@.ORG.page');
        $p = new Page($count,20);
        $pagebar = $p->show();

		$yellow_list = D('Portal_yellow')->where($where)->order('top_time desc,status asc,dateline desc')->limit($p->firstRow.','.$p->listRows)->select();
		// 分类
		foreach($yellow_list as $key => $yellow){

			foreach($all_category_list as $category){
				if($yellow['pid'] == $category['cat_id']){
					// 一级分类
					$yellow_list[$key]['parent_cat_name'] = $category['cat_name'];
					// 二级分类
					foreach($category['category_list'] as $child){
						if($yellow['cid'] == $child['cat_id']){
							$yellow_list[$key]['child_cat_name'] = $child['cat_name'];
							break;
						}
					}
					break;
				}
			}
		}

		if($pid==0){
			$pid = (int)$_GET['parent_id'];
		}

		$area_list = D('Area')->get_area_list();
		$this->assign('yellow_list',$yellow_list);
		$this->assign('all_category_list',$all_category_list);
		$this->assign('area_list',$area_list);
		$this->assign('pagebar',$pagebar);
		$this->assign('pid',$pid);
		$this->assign('cid',$cid);
		$this->assign('title',$title);
		$this->display();
	}

	// 详情
	public function detail(){
		$yid = (int)$_GET['yid'];
		$detail = D('Portal_yellow')->where(array('id'=>$yid))->find();
		$custome_info = D('Portal_yellow_detail')->where(array('yellow_id'=>$yid))->find();

		// 统计PV
		D('Portal_yellow')->where(array('id'=>$yid))->setInc('PV');

		// 分类信息
		$p_info = D('Group_category')->get_category_by_id($detail['pid']);
		$c_info = D('Group_category')->get_category_by_id($detail['cid']);
		$detail['parent_cat_name'] = $p_info['cat_name'];
		$detail['child_cat_name'] = $c_info['cat_name'];

		// 评论信息
		$recomment_list = D('Portal_yellow_recomment')->where(array('yellow_id'=>$yid))->order('id asc')->select();

		$this->assign('recomment_list',$recomment_list);
		$this->assign('detail',$detail);
		$this->assign('custome_info',$custome_info);
		$this->display();
	}

	// 评论
	public function save_recomment(){
		if(!$_SESSION['user']['uid']){
			exit(json_encode(array('code'=>1,'msg'=>'您还未登录，不能评论')));
		}

		$yellow_id = (int)$_POST['yellow_detail_id'];
		$msg = trim($_POST['msg']);
		if($yellow_id <= 0){
			exit(json_encode(array('code'=>1,'msg'=>'公司ID错误')));
		}
		if($msg == ''){
			exit(json_encode(array('code'=>1,'msg'=>'没有评论内容')));
		}
		$data['yellow_id'] = $yellow_id;
		$data['msg'] = $msg;
		$data['uid'] = $_SESSION['user']['uid'];
		$data['nickname'] = $_SESSION['user']['nickname'];
		$data['avatar'] = $_SESSION['user']['avatar'];
		$data['dateline'] = time();
		$res = D('Portal_yellow_recomment')->data($data)->add();
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'评论失败')));
		}
		exit(json_encode(array('code'=>0,'msg'=>'评论成功')));
	}

}