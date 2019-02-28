<?php
class CustomerAction extends BaseAction
{
	protected $openid;
	
	protected $village;
	
	protected $village_id;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->openid = $_SESSION['openid'];// = 'oG77RjiV06w5wBzs_sO4vSHvFqrM';
		if (empty($this->openid)) {
			$this->error_tips('在微信中操作');
		}
		$this->village = D('House_village')->field(true)->where(array('openid' => $this->openid))->find();
		if (empty($this->village)) {
			$this->error_tips('您的账号不正常，请联系社区物业');
		}
		$this->village_id = $this->village['village_id'];
	}
	
	public function index()
	{
		$this->display();
	}
	
	public function ajax_list()
	{
		$status = $_GET['status'];
		if (!$status) {
			$status = 0;
		}else{
			$status = array('neq',0);
		}
		$repair_list = D('House_village_repair_list')->field(true)->where(array('status' => $status, 'village_id' => $this->village_id))->select();
		
		if ($repair_list) {
			foreach ($repair_list as &$r) {
				$r['url'] = U('Customer/detail', array('pigcms_id' => $r['pigcms_id']));
				$r['content'] = msubstr($r['content'], 0, 20);
				$r['time'] = date('Y-m-d H:i', $r['time']);
			}
			exit(json_encode(array('status' => 1, 'order_list' => $repair_list)));
		}
		exit(json_encode(array('status' => 0)));
	}
	
	public function detail()
	{
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		$repair_detail = D('House_village_repair_list')->field(true)->where(array('pigcms_id' => $pigcms_id, 'village_id' => $this->village_id))->find();
		if(empty($repair_detail)){
			$this->error_tips('当前内容不存在');
		}
		
		if($repair_detail['pic']){
			$repair_detail['picArr'] = explode('|',$repair_detail['pic']);
		}
		if($repair_detail['reply_pic']){
			$repair_detail['reply_picArr'] = explode('|',$repair_detail['reply_pic']);
		}
		if($repair_detail['comment_pic']){
			$repair_detail['comment_picArr'] = explode('|',$repair_detail['comment_pic']);
		}
		$logs = D('House_village_repair_log')->field(true)->where(array('repair_id' => $repair_detail['pigcms_id']))->order('lid desc')->select();
		foreach ($logs as $log) {
			$repair_detail['status_time_' . $log['status']] = $log['dateline'];
		}
		
		$now_user_info = D('House_village_user_bind')->field(true)->where(array('uid' => $repair_detail['uid'], 'pigcms_id' => $repair_detail['bind_id']))->find();
		
	
		if ($repair_detail['status']) {
			$worker = D('House_worker')->field(true)->where(array('wid' => $repair_detail['wid'], 'village_id' => $this->village_id))->find();
			$this->assign('worker', $worker);
		} else {
    		$type = $repair_detail['type'] == 1 ? 1 : 0;
	    	$workers = D('House_worker')->field(true)->where(array('type' => $type, 'status' => 1, 'village_id' => $this->village_id))->select();
	    	$this->assign('workers', $workers);
		}
    	$title = '';
		if ($repair_detail['type'] == 1) {
			$title = '报修';
		} elseif ($repair_detail['type'] == 2) {
			$title = '水电煤上报';
		} elseif ($repair_detail['type'] == 3) {
			$title = '投诉建议';
		}
		
		$this->assign('logs', $logs);
		$this->assign('title', $title);
		$this->assign('repair_detail', $repair_detail);
		$this->assign('now_user_info', $now_user_info);
		$this->display('status');
	}
	
	public function do_work()
	{
		$pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
		$worker_id = isset($_POST['worker_id']) ? intval($_POST['worker_id']) : 0;
		//             $reply_content = $this->_post('reply_content');
		$database_house_village_repair_list = D('House_village_repair_list');
		$repair = $database_house_village_repair_list->field(true)->where(array('pigcms_id' => $pigcms_id, 'village_id' => $this->village_id))->find();
		if (empty($repair)) {
			exit(json_encode(array('status' => 0,'msg' => '传递参数有误！')));
		}
		$worker = D('House_worker')->field(true)->where(array('wid' => $worker_id, 'village_id' => $this->village_id))->find();
		if (empty($worker)) {
			exit(json_encode(array('status' => 0, 'msg' => '工作人员不能为空！')));
		}
		
		$data['wid'] = $worker_id;
		$data['status'] = 1;
		$where['village_id'] = $this->village_id;
		//             $data['reply_time'] = time();
		//             $data['is_read'] = 1;
		$where['pigcms_id'] = $pigcms_id;
		if ($database_house_village_repair_list->where($where)->save($data)) {
			D('House_village_repair_log')->add_log(array('status' => 1, 'repair_id' => $pigcms_id, 'phone' => $worker['phone'], 'name' => $worker['name']));
			exit(json_encode(array('status'=>1,'msg'=>'提交成功！')));
		} else {
			exit(json_encode(array('status'=>0,'msg'=>'提交失败！')));
		}
	}
}
?>