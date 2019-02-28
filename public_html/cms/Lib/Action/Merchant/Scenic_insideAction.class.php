<?php
/*
 * 景区内活动
 *
 *   Writers    hanlu
 *   BuildTime  2016/09/14 09:55
 *
 */
class Scenic_insideAction extends BaseAction{
	# 活动列表
    public function index(){
		$database_scenic = D('Scenic_activity');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_guide = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('start_time DESC')->select();
		$scenic_image_class = new scenic_image();
		foreach($now_guide as &$v){
			$tmp_pic_arr = explode(';',$v['activity_img']);
			$image	=	$scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'activity');
			$v['activity_img']	=	$image['image'];
			$v['start_time']	=	date('Y-m-d H:i',$v['start_time']);
			unset($image);
		}
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_guide',$now_guide);
		$this->display();
	}
    # 新增活动
    public function add(){
    	$data	=	array();
    	$data['scenic_id']	=	$this->merchant_session['scenic_id'];
    	if(IS_POST){
			if(empty($_POST['activity_title'])) {
				$this->error('活动标题必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("活动图片必须上传！");
            }
            if(empty($_POST['activity_stitle'])){
				$this->error("活动短标题必填！");
            }
            if(empty($_POST['start_time'])){
				$this->error("活动时间不能为空");
            }
            if(empty($_POST['activity_content'])){
				$this->error("活动内容不能为空");
            }
            $data['start_time']	=	strtotime(str_replace("T"," ",$_POST['start_time']));	//开始时间
			$data['activity_title'] = $_POST['activity_title'];		//标题
			$data['activity_stitle'] = $_POST['activity_stitle'];	//短标题
			$data['activity_content'] = $_POST['activity_content'];//活动内容
			$data['activity_img'] = implode(';',$_POST['pic']);	//活动图片
			$data['add_time'] = $_SERVER['REQUEST_TIME'];
			$data['status'] = 1;
            $add	=	M('Scenic_activity')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 修改活动
    public function edit(){
		$data	=	array();
    	if(IS_POST){
			if(empty($_POST['activity_title'])) {
				$this->error('活动标题必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("活动图片必须上传！");
            }
            if(empty($_POST['activity_stitle'])){
				$this->error("活动短标题必填！");
            }
            if(empty($_POST['start_time'])){
				$this->error("活动时间不能为空");
            }
            if(empty($_POST['activity_content'])){
				$this->error("活动内容不能为空");
            }
            $data['start_time']	=	strtotime(str_replace("T"," ",$_POST['start_time']));	//开始时间
			$data['activity_title'] = $_POST['activity_title'];		//标题
			$data['activity_stitle'] = $_POST['activity_stitle'];	//短标题
			$data['activity_content'] = $_POST['activity_content'];//活动内容
			$data['activity_img'] = implode(';',$_POST['pic']);	//活动图片
			$data['modify_time'] = $_SERVER['REQUEST_TIME'];
			$where['activity_id']	=	$_POST['activity_id'];
            $add	=	M('Scenic_activity')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['activity_id']	=	$_GET['activity_id'];
			$now_order	=	M('Scenic_activity')->where($where)->find();
			if(!empty($now_order['activity_img'])){
				$now_order['start_time']	=	str_replace(" ","T",date('Y-m-d H:i',$now_order['start_time']));
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$now_order['activity_img']);
				foreach($tmp_pic_arr as $key=>$value){
					$now_order['pic'][$key]['title'] = $value;
					$now_order['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'activity','1');
				}
			}
			$this->assign('now_order',$now_order);
			$this->display();
		}
    }
    # 关闭活动
    public function del(){
    	$where['activity_id']	=	$_GET['activity_id'];
    	if(empty($where)){
			$this->error('活动ID不能为空！');
    	}
    	$ticket	=	M('Scenic_activity')->where($where)->data(array('status'=>0))->save();
    	if($ticket){
			$this->success("关闭成功！");
    	}else{
			$this->error("关闭失败！");
    	}
    }
    # 新增图片
    public function ajax_upload_pic(){
		if ($_FILES['imgFile']['error'] != 4) {
			$param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $this->config['group_pic_width'];
            $param['thumbMaxHeight'] = $this->config['group_pic_height'];
            $param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->merchant_session['scenic_id'], 'scenic/activity', 1,$param);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$store_image_class = new scenic_image();
				$url = $store_image_class->get_image_by_path($title,$this->config['site_url'],'activity','-1');
				exit(json_encode(array('error' => 0, 'url' => $url['image'], 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	# 删除图片
	public function ajax_del_pic(){
		$store_image_class = new store_image();
		$store_image_class->del_image_by_path($_POST['path']);
	}
}