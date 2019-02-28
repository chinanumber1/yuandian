<?php
class Appoint_commentModel extends Model{
    public function appoint_comment_list($where , $fields = true , $order = 'id desc' , $pageSize = 20){
		$database_user = D('User');
		if(!$where){
			return false;
		}

		import('@.ORG.merchant_page');
		$count = $this->where($where)->count();
		$p = new Page($count , $pageSize , 'page');

		$comment_list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();

		foreach($comment_list as $k=>$v){
			$comment_list[$k]['comment_img'] =  unserialize($v['comment_img']);
			$now_user = $database_user->where(array('uid'=>$v['uid']))->field('nickname,avatar')->find();
			$comment_list[$k]['avatar'] = $now_user['avatar'];
			$comment_list[$k]['nickname'] = $now_user['nickname'];
			$comment_list[$k]['avg_score'] = (int)(($v['profession_score'] + $v['communicate_score'] + $v['speed_score']) / 3) ;
		}

		$list['list'] = $comment_list;
		$list['pagebar'] = $p->show();

		if($comment_list){
			return array('status' => 1 , 'result' => $list);
		}else{
			return array('status' => 0 , 'result' => $list);
		}
    }
    
}
?>