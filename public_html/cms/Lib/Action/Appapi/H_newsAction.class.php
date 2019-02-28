<?php
/*
 * 社区新闻管理 2.0
 *
 */
class H_newsAction extends BaseAction{
	//	获取社区评论列表
    public function reply(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(122, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id['village_id']	=	I('village_id');
		$village_id['status']		=	1;
		$village_id['reply_status']		=	1;
		$page	=	I('page',1);
		$page_coun	=	I('page_coun',10);
		$arr = $this->getReplyList($village_id,$page,$page_coun);
		if(empty($arr['reply_list'])){
			$this->returnCode('20090015');
		}
		$this->returnCode(0,$arr);
	}
	//	获取社区评论列表
	public function getReplyList($column,$page=1,$page_coun=10){
		$condition_table  = array(C('DB_PREFIX').'house_village_news'=>'n',C('DB_PREFIX').'house_village_news_category'=>'c',C('DB_PREFIX').'house_village_news_reply'=>'r',C('DB_PREFIX').'user'=>'u');
		$condition_where = " n.village_id = r.village_id  AND n.village_id = c.village_id  AND n.news_id = r.news_id  AND r.uid = u.uid AND  n.cat_id = c.cat_id AND c.cat_status=1  AND n.village_id=".$column['village_id'];
		if($column['status']){
			$condition_where .= " AND n.status = ".intval($column['status']);
		}
		if($column['reply_status']){
			$condition_where .= " AND r.status = ".intval($column['reply_status']);
		}
		$condition_field = 'n.title,c.cat_name,r.*,u.nickname';
		$order = ' r.is_read ASC,r.pigcms_id DESC ';
		$count_news = M('')->table($condition_table)->where($condition_where)->count();
		$reply_list = M('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->page($page,$page_coun)->select();
		foreach($reply_list as $k=>$v){
			$reply_list[$k]['add_time_s']	=	date('Y-m-d H:i',$v['add_time']);
		}
		$return['totalPage']	= ceil($count_news/$page_coun);
		$return['page']			= intval($page);
		$return['reply_count']	= count($reply_list);
		$return['reply_list']	= $reply_list;
		return $return;
	}
	//	删除评论
	public	function	delete_reply(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(125, $this->power)){    		
			$this->returnCode('20090103');
    	}
    	
		$pigcms_id	=	I('pigcms_id');
		if(empty($pigcms_id)){
			$this->returnCode('20090016');
		}else{
			$data['status']	=	2;
		}
		$sSave	=	M('House_village_news_reply')->where(array('pigcms_id'=>$pigcms_id))->data($data)->save();
		if($sSave){
			$this->returnCode(0);
		}else if($sSave === 0){
			$this->returnCode('20090018');
		}else{
			$this->returnCode('20090017');
		}
	}
	//	投诉阅读
	public	function	read_reply(){
		$this->is_existence();
		$pigcms_id	=	I('pigcms_id');
		if(empty($pigcms_id)){
			$this->returnCode('20090079');
		}else{
			$data['is_read']	=	1;
		}
		$sSave	=	M('House_village_news_reply')->where(array('pigcms_id'=>$pigcms_id))->data($data)->save();
		if($sSave){
			$this->returnCode(0);
		}else if($sSave === 0){
			$this->returnCode('20090080');
		}else{
			$this->returnCode('20090081');
		}
	}
	//	获取投诉建议列表
	public	function	suggess(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(224, $this->power)){    		
			$this->returnCode('20090103');
    	}
    	
		$column['village_id'] = I('village_id');
		$column['type'] = 3;
		$column['status'] = 1;
		$page	=	I('page',1);
		$page_coun	=	I('page_coun',10);
    	$arr = $this->getSuggessList($column,$page,$page_coun);
    	if(empty($arr['repair_list'])){
			$this->returnCode('20090019');
    	}
    	$this->returnCode(0,$arr);
	}
	//	获取投诉建议列表
	public function getSuggessList($column,$page=1,$page_coun=10){
		$condition_table  = array(C('DB_PREFIX').'house_village_repair_list'=>'r',C('DB_PREFIX').'house_village_user_bind'=>'b');
		$condition_where = " r.village_id = b.village_id  AND r.bind_id = b.pigcms_id  AND r.village_id=".$column['village_id'];
		if($column['type']){
			$condition_where .= " AND r.type = ".$column['type'];
		}
		if($column['bind_id']){
			$condition_where .= " AND r.bind_id = ".intval($column['bind_id']);
		}
		if($column['pigcms_id']){
			$condition_where .= " AND r.pigcms_id = ".intval($column['pigcms_id']);
		}
		if($column['status']){
			$condition_where .= " AND r.status = ".intval($column['status']);
		}
		$condition_field = 'r.pigcms_id as pid,r.*,b.*';
		$order = ' r.pigcms_id DESC,r.is_read ASC ';
		$count_repair = D('')->table($condition_table)->where($condition_where)->count();
		$repair_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->page($page,$page_coun)->select();
		$return = array();
		if($repair_list){
			foreach ($repair_list as $k=>$v){
				if($v['pic']){
					$pic = explode('|', $v['pic']);
					$picArray = array();
					foreach ($pic as $picinfo){
						$picArray[] = C('config.site_url')."/upload/house/".$picinfo;
					}
					$repair_list[$k]['pic'] = $picArray;
				}
				$repair_list[$k]['time_s']	=	date('Y-m-d H:i',$v['time']);
			}
			$return['totalPage']	= ceil($count_repair/$page_coun);
			$return['page']			= intval($page);
			$return['repair_count']	= count($repair_list);
			$return['repair_list'] 	= $repair_list;
		}
		return $return;
	}
	//	删除投诉
	public	function	delete_suggess(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(257, $this->power)){    		
			$this->returnCode('20090103');
    	}
    	
		$pigcms_id	=	I('pid');
		if(empty($pigcms_id)){
			$this->returnCode('20090020');
		}else{
			$data['status']	=	2;
		}
		$sSave	=	M('House_village_repair_list')->where(array('pigcms_id'=>$pigcms_id))->data($data)->save();
		if($sSave){
			$this->returnCode(0);
		}else if($sSave === 0){
			$this->returnCode('20090022');
		}else{
			$this->returnCode('20090021');
		}
	}
	//	投诉阅读
	public	function	suggess_read(){
		$this->is_existence();
		$pigcms_id	=	I('pid');
		if(empty($pigcms_id)){
			$this->returnCode('20090020');
		}else{
			$data['is_read']	=	1;
		}
		$sSave	=	M('House_village_repair_list')->where(array('pigcms_id'=>$pigcms_id))->data($data)->save();
		if($sSave){
			$this->returnCode(0);
		}else if($sSave === 0){
			$this->returnCode('20090024');
		}else{
			$this->returnCode('20090023');
		}
	}
}