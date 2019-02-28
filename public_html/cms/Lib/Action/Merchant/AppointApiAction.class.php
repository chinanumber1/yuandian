<?php
class AppointApiAction extends Action{
    protected function _initialize(){
		$server_name = $_SERVER['HTTP_HOST'];
		$this->now_database = S('database_'.$server_name);
		if(empty($now_database)){
			$condition_database['site_url'] = $server_name;
			$now_database = M('Database')->field(true)->where($condition_database)->find();
			
			S('database_'.$server_name,$now_database);
			$this->now_database = $now_database;
		}

	}
    

	
    public function index(){
	$mer_id = $this->_get('mer_id');
	$limit = $this->_get('limit');
	
	if(!$mer_id){
	    $this->error('传递参数有误！');
	}
	
	$where['mer_id'] = $mer_id;
	$count = D('')->table($this->now_database['db_name'] . '.'.C('DB_PREFIX').'appoint')->where($where)->count();
	$list['count'] = $count;
	
	$limit = $limit ? $limit : 10;
	
	import('@.ORG.group_page');
	$p = new Page($count , $limit , 'page');
	$field = 'appoint_id,appoint_name';
	$list['list'] = D('')->table($this->now_database['db_name'] . '.'.C('DB_PREFIX').'appoint')->field($field)->where($where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
	exit(json_encode($list));
    }
}

