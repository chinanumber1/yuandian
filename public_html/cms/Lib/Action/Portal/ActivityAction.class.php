<?php
/**
* 活动板块
*/
class ActivityAction extends BaseAction {

	public function _initialize(){
		parent::_initialize();
		$this->assign('portal_name','同城活动');
	}

	// 首页
	public function index(){
		$activityCatgoryList = D('Portal_activity_cat')->where(array('fcid'=>0))->order('cid desc')->select();
		$this->assign('activityCatgoryList',$activityCatgoryList);
		if($_GET['cid']){
			$activity_where['cid'] = $_GET['cid'];
		}
		$activity_where['status'] = 1;
		$count = D('Portal_activity')->where($activity_where)->count();
        import('@.ORG.page');
        $p = new Page($count,10);
		$activityList = D('Portal_activity')->where($activity_where)->order('a_id desc')->limit($p->firstRow.','.$p->listRows)->select();
		foreach ($activityList as $k => $v) {
			if($v['enroll_time']<time()){
				$v['state'] = 5;//结束了
			}else if($v['number']<=$v['already_sign_up'] && $v['number']>0){
				$v['state'] = 3;//即将组团
			}else{
				$v['state'] = 2;//招募中
			}
			$activityList[$k] = $v;
		}
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
		$this->assign('activityList',$activityList);
		$this->display();
	}

	// 活动分类列表
	public function lists(){
		
		$this->display();
	}

	// 活动详情
	public function detail(){
		$a_id = intval($_GET['a_id']);
		$activityInfo = D('Portal_activity')->where(array('a_id'=>$a_id))->find();
		if(!is_array($activityInfo)){
			$this->error('查询的目标不存在');
		}

		if($activityInfo['enroll_time']<time()){
			$activityInfo['state'] = 5;//活动结束
		}else if($activityInfo['number']<=$activityInfo['already_sign_up'] && $activityInfo['number']>0){
			$activityInfo['state'] = 3;//即将组团
		}else{
			$activityInfo['state'] = 2;//正在召集
		}

		
		$activityInfo['over_time'] = intval(($activityInfo['enroll_time'] - time())/60/60/24);
		$this->assign('activityInfo',$activityInfo);

		$recommentCount = D('Portal_recomment')->where(array('type'=>1,'target_id'=>$a_id))->count();
		$recommentList = D('Portal_recomment')->where(array('type'=>1,'target_id'=>$a_id))->select();
		$this->assign('recommentCount',$recommentCount);
		$this->assign('recommentList',$recommentList);
		

		$activitySignList = D('Portal_activity_sign')->where(array('a_id'=>$a_id))->order('create_time desc')->select();


		// 资讯评论
//		$activitySignList = M()->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'portal_activity_sign'=>'pas'))->where('pas.a_id='.$a_id.' and u.uid=pas.uid')->field('pas.*,u.avatar,u.nickname')->select();
		$this->assign('activitySignList',$activitySignList);

		// $activitySignList = D('Portal_activity_sign')->where(array('a_id'=>$a_id))->select();
		// $this->assign('activitySignList',$activitySignList);
		// dump($activitySignList);
		$this->display();
	}

	// 在线报名
	public function baoming(){
		$this->display();
	}

	public function activity_baoming(){
	    $allowed = D('Portal_activity')->where(array('a_id'=>$_POST['a_id']))->find();
	    if($allowed['already_sign_up']>=$allowed['number']) {
            exit(json_encode(array('error'=>2,'msg'=>'名额已满，报名失败！')));
        }
		if(is_array($_SESSION['user'])){
			$_POST['uid'] = $_SESSION['user']['uid'];
			$_POST['avatar'] = $_SESSION['user']['avatar'];
			$_POST['nickname'] = $_SESSION['user']['nickname'];
            $_POST['create_time'] = time();
		}else{
			$_POST['uid'] = 0;
			$_POST['avatar'] =  '';
			$_POST['nickname'] = '游客';
            $_POST['create_time'] = time();
		}
		$res = D('Portal_activity_sign')->data($_POST)->add();
		if($res){
			D('Portal_activity')->where(array('a_id'=>intval($_POST['a_id'])))->setInc('already_sign_up');
			exit(json_encode(array('error'=>1,'msg'=>'报名成功！')));
		}else{
			exit(json_encode(array('error'=>2,'msg'=>'报名失败，请重试！')));
		}

	}

	public function recomment(){
		if(IS_POST){
			$data['uid']=$this->user_session['uid'];
			$data['nickname']=$this->user_session['nickname'];
			$data['target_id']=$_POST['target_id'];
			$data['avatar']=$this->user_session['avatar'];
			$data['type']=1;
			$data['msg']=$_POST['msg'];
			$data['dateline']=time();
			$res = D('Portal_recomment')->data($data)->add();
			if($res){
				exit(json_encode(array('error'=>1,'msg'=>'评论成功！','data'=>$data)));
			}else{
				exit(json_encode(array('error'=>2,'msg'=>'评论失败，请重试！')));
			}
		}
		
	}
}