<?php
/*
*2016-1-19
*系统实体卡编辑
*
*/

class CardeditAction extends BaseAction{

	//显示实体卡列表
	public function index(){
		//dump($_GET);die();
		//搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_card['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'cardid') {
                $condition_card['cardid'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'merid'){
				$condition_card['merid'] = $_GET['keyword'];
			}
        }else if ($_GET['searchtype'] == 'regtime'){
			$condition_card="regtime!=''";
		}
		
		//排序 /*/
		$order_string = '`id` DESC';
		if($_GET['sort']){
			switch($_GET['sort']){
				case 'uid':
					$order_string = '`uid` DESC';
					break;
				case 'lastTime':
					$order_string = '`last_time` DESC';
					break;
				case 'money':
					$order_string = '`now_money` DESC';
					break;
				case 'score':
					$order_string = '`score_count` DESC';
					break;
			}
		}
		
		$card = M('Physical_card');
        $count_card = $card->where($condition_card)->count();
        import('@.ORG.system_page');
        $p = new Page($count_card, 15);
		$card_list = $card->field('id,cardid,uid,merid,balance_money,regtime,last_time,status')->where($condition_card)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->getField('id,cardid,uid,merid,balance_money,regtime,last_time,status');
		$this->assign('card_list',$card_list);
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

		$this->display();
	}

	/*
	 * 实体卡操作记录
	 */
	public function log(){

		$log = D('Physical_card')->card_log(0,0,1);
		$this->assign($log);
		$this->display();
	}

	//批量生成卡号 也可以单个增加
	public function add_card(){
		if(IS_POST){
			$head_number = $_POST['head_number'];
			$start_number = $_POST['start_number'];
			$end_number = $_POST['end_number'];
			$merid = empty($_POST['merid'])?NULL:(int)$_POST['merid'];
			$default_money = empty($_POST['default_money'])?0:(int)$_POST['default_money'];
			$head_number = $head_number<999999?$head_number*10:$head_number;
			$card = M('Physical_card'); 
			$head_number *=100000;
			$last_time =$add_time= time();
			if($merid){
				$tmp_data['merid']=$merid;
			}
			$tmp_data['balance_money']=$default_money;
			//$tmp_data['add_time']=$add_time;
			$tmp_data['last_time']=$last_time;
			for($i=$start_number;$i<=$end_number;$i++){
				$data = $tmp_data;
				$data['cardid']= $head_number+$i;
				if($card->where(array('cardid'=>$data['cardid']))->find()){
					$duplicate = true;
					continue;
				}
				if(!$card->add($data)){
					$return = array('error'=>false,'msg'=>"添加实体卡".$data['cardid']."失败");
					break;
				}else{
					$return = array('error'=>false,'msg'=>"添加成功! ");
				}
			}
			if(!$return['error']){
				if($duplicate){
					$return['msg'].=" 有重复添加！";
				}
				
				$this->success($return['msg']);
			}else{
				$this->error($return['msg']);
			}
		}else{
			$this->display();
		}
	}

	public function del_card(){
		$card = M('Physical_card');
		$uid = $card->where(array('cardid'=>$_GET['cardid']))->getField('uid');
		if(!empty($uid)){
			D('User')->where(array('uid'=>$uid))->save(array('cardid'=>''));
		}
		if($card->where('cardid='.$_GET['cardid'])->delete()){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
		//$this->display();
	}

	public function edit_card(){
		$card = D('Physical_card');
		$status = $card->status;
		if(IS_POST){
			$condition_card['cardid']=$_POST['cardid'];
			if($_POST['uid']>0) {
				$card_data['uid'] =$_POST['uid'];
			}
			if($_POST['merid']>0) {
				$card_data['merid'] =$_POST['merid'];
			}
			if($_POST['balance_money']>0){
				$card_data['balance_money']=$_POST['balance_money'];
			}
			$card_data['status']=$_POST['status'];
			$card_data['last_time']=time();
			
			if(!empty($_POST['uid'])){
				$condition_user['uid'] = $_POST['uid'];
				$user_uid = D('User')->where($condition_user)->find();

				if(empty($user_uid)){
					$this->error('用户不存在');
				}
				if($card_data['status']==1) {
					$card_data['regtime'] = time();
				}
				$card_data['last_time']=time();
				$card_data['is_bind'] = 1;
				//if($card)
				if($card_data['balance_money']){
					D('User')->add_money($_POST['uid'],$card_data['balance_money'],'管理员为用户绑定实体卡，将实体卡余额充值进用户余额，共'.$card_data['balance_money'].'元');
				}

				D('User')->where($condition_user)->save(array('cardid'=>$_POST['cardid']));
			}else{
				$card_data['regtime']='';
				D('User')->where($condition_card)->save(array('cardid'=>''));
			}
				
			$res = $card->where($condition_card)->setField($card_data);
			$log['system_id'] = $this->system_session['id'];
			$log['card_id'] = $_POST['cardid'];
			if($_POST['balance_money']>0){
				$des = '当前金额'.$_POST['balance_money'].'元,';
			}
			$des.='状态'.$status[$_POST['status']];
			$log['des'] = '系统管理员'.$this->system_session['id'].'操作,'.$des;
			$card->add_log($log);
			if(!$res){
				$this->error('保存失败');
			}else{
				$this->success('保存成功！');
			}
		}else{
			$condition_card['cardid']=$_GET['cardid'];
			$res_card = $card->where($condition_card)->getField('cardid,uid,merid,balance_money,status');
			if(empty($res_card)){
				$this->error('没有查询到数据');
			}
		}
		$this->assign('physical_card',$res_card[$_GET['cardid']]);
		$this->display();
	}
	
	
	public function mutil_bind_merid(){
		if(IS_POST){
			$cardids = preg_replace('/'.PHP_EOL.'/',",",$_POST['cardids']);
			$merid = empty($_POST['merid'])?NULL:(int)$_POST['merid'];
			$where['cardid']=array('in',$cardids);
			if(!D('Physical_card')->where($where)->setField('merid',$merid)){
				$this->error('批量更新失败，请检查数据是否正确');
			}else{
				$this->success('实体卡绑定商家ID成功！');
			}
		}
		$this->display();
	}
}

?>