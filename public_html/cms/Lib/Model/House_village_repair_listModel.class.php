<?php
class House_village_repair_listModel extends Model{
	
	public function getlist($column, $sizePage=20,$order = '')
	{
		if(!$column['village_id']){
			return '';
		}
		$condition_table = array(C('DB_PREFIX') . 'house_village_repair_list' => 'r', C('DB_PREFIX') . 'house_village_user_bind' => 'b');
		$condition_where = " r.village_id=" . $column['village_id'];
		if($column['type']){
			$condition_where .= " AND r.type = ".$column['type'];
		}
		if($column['wid']){
			$condition_where .= " AND r.wid = ".intval($column['wid']);
		}
		if($column['bind_id']){
			$condition_where .= " AND r.bind_id = ".intval($column['bind_id']);
		}
		
		$condition_field = 'r.pigcms_id as pid,r.type as r_type,r.status as r_status,r.*,b.*';
		if($column['pigcms_id']){
			$condition_where .= " AND r.pigcms_id = ".intval($column['pigcms_id']);
		}

		if($column['usernum']){
			$condition_where .= " AND b.usernum like '%".$column['usernum']."%'";
		}elseif($column['phone']){
			$condition_where .= " AND b.phone like '%".$column['phone']."%'";
		}elseif($column['address']){
			$condition_where .= " AND b.address like '%".$column['address']."%'";
		}

		if($column['status'] > -1){
			$condition_where .= " AND r.status =".$column['status'];
		}


		if (($column['begin_time'] > 0) && ($column['end_time'] > 0)) {
			$condition_where .= " AND r.time>'{$column['begin_time']}' AND r.time<'{$column['end_time']}'";
		}elseif($column['begin_time'] > 0){
			$condition_where .= " AND r.time>'{$column['begin_time']}'";
		}elseif($column['end_time'] > 0){
			$condition_where .= " AND r.time<'{$column['end_time']}'";
		}

		if($order['time']){
			$order = " r.time ".$order['time'];
		}

		if(empty($order)){
			//$order = ' r.status ASC, r.pigcms_id DESC ';
			$order = ' `r`.`time` DESC ';
		}

		import('@.ORG.merchant_page');
		$sql = 'SELECT count(1) AS cnt FROM ' . C('DB_PREFIX') . 'house_village_repair_list AS r LEFT JOIN ' . C('DB_PREFIX') . 'house_village_user_bind AS b ON r.village_id = b.village_id AND r.bind_id = b.pigcms_id';
		$sql .= ' WHERE' . $condition_where;
		$count_repair = $this->query($sql);
		$count_repair = isset($count_repair[0]['cnt']) ? intval($count_repair[0]['cnt']) : 0;
		
		if ($sizePage === -100) return $count_repair;
// 		$count_repair = D('')->table($condition_table)->where($condition_where)->count();

		$p = new Page($count_repair,$sizePage,'page');
		
		$sql = 'SELECT ' . $condition_field . ' FROM ' . C('DB_PREFIX') . 'house_village_repair_list AS r LEFT JOIN ' . C('DB_PREFIX') . 'house_village_user_bind AS b ON r.village_id = b.village_id AND r.bind_id = b.pigcms_id';
		$sql .= ' WHERE' . $condition_where . ' ORDER BY ' . $order . ' LIMIT ' . $p->firstRow.','.$p->listRows;
		$repair_list = $this->query($sql);
// 		$repair_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

		$return = array();
		foreach ($repair_list as &$v) {
			if ($v['pic']) {
				$pic = explode('|', $v['pic']);
				$picArray = array();
				foreach ($pic as $picinfo) {
					$picArray[] = C('config.site_url') . "/upload/house/" . $picinfo;
				}
				$v['pic'] = $picArray;
			}
			if ($v['reply_pic']) {
				$pic = explode('|', $v['reply_pic']);
				$picArray = array();
				foreach ($pic as $picinfo) {
					$picArray[] = C('config.site_url') . "/upload/worker/" . $picinfo;
				}
				$v['reply_pic'] = $picArray;
			}
			if ($v['comment_pic']) {
				$pic = explode('|', $v['comment_pic']);
				$picArray = array();
				foreach ($pic as $picinfo) {
					$picArray[] = C('config.site_url') . "/upload/house/" . $picinfo;
				}
				$v['comment_pic'] = $picArray;
			}
		}
		$return['pagebar'] = $p->show();
		$return['repair_list'] = $repair_list;
		return $return;
	}
	
	public function notice_work()
	{
		$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
		
		$sql = "SELECT openid, l.pigcms_id, l.type FROM " . C('DB_PREFIX') . "house_village AS v INNER JOIN " . C('DB_PREFIX') . "house_village_repair_list AS l ON l.village_id=v.village_id WHERE v.handle_type=1 AND l.time+v.hour*3600<" . time();
		
		$res = $this->query($sql);
		foreach ($res as $r) {
			if ($r['type'] == 1) {
				$first = '报修';
			} elseif ($r['type'] == 2) {
				$first = '水电煤';
			} else {
				$first = '投诉建议';
			}
			$href = C('config.site_url').'/wap.php?c=Customer&a=detail&pigcms_id=' . $r['pigcms_id'];
			$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $r['openid'], 'first' => $first, 'work' => '您有一个' . $first . '需处理', 'remark' => date('Y-m-d H:i:s')));
		}

	}
	
	//小程序获取小区物业报修列表 - wangdong
	public function wxapp_getList($uid , $village_id , $bind_id , $type){
		
		$where['uid'] = $uid;
		$where['village_id'] = $village_id;
		$where['bind_id'] = $bind_id;
		$where['type'] = $type;
		$list = $this->field(true)->where($where)->select();
		return $list;
			
	}
	
}