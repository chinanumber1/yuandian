<?php
/*
 * 景区门票
 *
 *   Writers    hanlu
 *   BuildTime  2016/07/04 20:00
 *
 */

class Scenic_parkAction extends BaseAction{
    # 门票列表
    public function index(){
    	$database_scenic = D('Scenic_park');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_ticket = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('parking_id DESC')->select();
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_ticket',$now_ticket);
		$this->display();
    }
    # 新增门票
    public function add(){
    	$data	=	array();
    	$data['scenic_id']	=	$this->merchant_session['scenic_id'];
    	if(IS_POST){
			if(empty($_POST['parking_name'])) {
				$this->error('停车场名称必填！');
			}
            if(empty($_POST['parking_address'])) {
                $this->error("停车场地址必填！");
            }
            if(empty($_POST['parking_count'])){
				$this->error("停车位数量必填！");
            }
            if(empty($_POST['long_lat'])){
				$this->error("停车场位置必选！");
            }
            $long_lat = explode(',',$_POST['long_lat']);
			$data['parking_long'] = $long_lat[0];
			$data['parking_lat'] = $long_lat[1];
			$data['parking_name'] = $_POST['parking_name'];			//停车场名称
			$data['parking_address'] = $_POST['parking_address'];	//停车场地址
			$data['parking_count'] = $_POST['parking_count'];		//停车位数量
			$data['parking_status'] = 1;
			$data['create_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_park')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 修改门票
    public function edit(){
		$data	=	array();
    	$where['parking_id']	=	$_POST['parking_id'];
    	if(IS_POST){
			if(empty($_POST['parking_name'])) {
				$this->error('停车场名称必填！');
			}
            if(empty($_POST['parking_address'])) {
                $this->error("停车场地址必填！");
            }
            if(empty($_POST['parking_count'])){
				$this->error("停车位数量必填！");
            }
            if(empty($_POST['long_lat'])){
				$this->error("停车场位置必选！");
            }
            $long_lat = explode(',',$_POST['long_lat']);
			$data['parking_long'] = $long_lat[0];
			$data['parking_lat'] = $long_lat[1];
			$data['parking_name'] = $_POST['parking_name'];			//停车场名称
			$data['parking_address'] = $_POST['parking_address'];	//停车场地址
			$data['parking_count'] = $_POST['parking_count'];		//停车位数量
			$data['parking_status'] = $_POST['parking_status'];		//停车位状态
            $add	=	M('Scenic_park')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['parking_id']	=	$_GET['parking_id'];
			$now_order	=	M('Scenic_park')->where($where)->find();
			$this->assign('now_order',$now_order);
			$this->display();
		}
    }
    # 删除门票
    public function del(){
    	$where['parking_id']	=	$_GET['parking_id'];
    	if(empty($where)){
			$this->error('停车场ID不能为空！');
    	}
    	$ticket	=	M('Scenic_park')->where($where)->delete();
    	if($ticket){
			$this->success("删除成功！");
    	}else{
			$this->error("删除失败！");
    	}
    }
}