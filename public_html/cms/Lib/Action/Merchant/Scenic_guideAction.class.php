<?php
/*
 * 景区内导游
 *
 *   Writers    hanlu
 *   BuildTime  2016/07/05 20:31
 *
 */
class Scenic_guideAction extends BaseAction{
    # 向导列表
    public function index(){
    	$database_scenic = D('Scenic_guide');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_guide = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('guide_id DESC')->select();
		$scenic_image_class = new scenic_image();
		foreach($now_guide as &$v){
			$image	=	$scenic_image_class->get_image_by_path($v['guide_pig'],$this->config['site_url'],'guide');
			$v['guide_pig']	=	$image['image'];
			unset($image);
		}
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_guide',$now_guide);
		$this->display();
    }
    # 新增向导
    public function add(){
    	$data	=	array();
    	$data['scenic_id']	=	$this->merchant_session['scenic_id'];
    	if(IS_POST){
			if(empty($_POST['guide_name'])) {
				$this->error('导游名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("导游照片必须上传！");
            }
            if(empty($_POST['guide_phone'])){
				$this->error("导游手机号必填！");
            }
            if(empty($_POST['guide_intr'])){
				$this->error("向导介绍下吧！");
            }
			$data['guide_name'] = $_POST['guide_name'];		//导游名
			$data['guide_gender'] = $_POST['guide_gender'];	//导游性别
			$data['guide_price'] = $_POST['guide_price'];	//导游价格
			$data['guide_unit'] = $_POST['guide_unit'];	//导游价格
			$data['guide_age'] = $_POST['guide_age'];	//导游年龄
			$data['guide_pig'] = implode(';',$_POST['pic']);	//照片
			$data['guide_phone'] = $_POST['guide_phone'];//手机号
			$data['guide_intr'] = $_POST['guide_intr'];		//简介
			$data['guide_status'] = 1;
			$data['create_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_guide')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 修改导游
    public function edit(){
		$data	=	array();
    	if(IS_POST){
			if(empty($_POST['guide_name'])) {
				$this->error('导游名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("导游照片必须上传！");
            }
            if(empty($_POST['guide_phone'])){
				$this->error("导游手机号必填！");
            }
            if(empty($_POST['guide_intr'])){
				$this->error("向导介绍下吧！");
            }
			$data['guide_name'] = $_POST['guide_name'];		//导游名
			$data['guide_gender'] = $_POST['guide_gender'];	//导游性别
			$data['guide_price'] = $_POST['guide_price'];	//导游价格
			$data['guide_unit'] = $_POST['guide_unit'];	//导游价格
			$data['guide_age'] = $_POST['guide_age'];	//导游年龄
			$data['guide_pig'] = implode(';',$_POST['pic']);//照片
			$data['guide_phone'] = $_POST['guide_phone'];//手机号
			$data['guide_intr'] = $_POST['guide_intr'];		//简介
			$data['guide_status'] = 1;
			$data['last_time'] = $_SERVER['REQUEST_TIME'];
			$where['guide_id']	=	$_POST['guide_id'];
            $add	=	M('Scenic_guide')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['guide_id']	=	$_GET['guide_id'];
			$now_order	=	M('Scenic_guide')->where($where)->find();
			if(!empty($now_order['guide_pig'])){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$now_order['guide_pig']);
				foreach($tmp_pic_arr as $key=>$value){
					$now_order['pic'][$key]['title'] = $value;
					$now_order['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'guide','1');
				}
			}
			$this->assign('now_order',$now_order);
			$this->display();
		}
    }
    # 删除向导
    public function del(){
    	$where['guide_id']	=	$_GET['guide_id'];
    	if(empty($where)){
			$this->error('导游ID不能为空！');
    	}
    	$ticket	=	M('Scenic_guide')->where($where)->delete();
    	if($ticket){
			$this->success("删除成功！");
    	}else{
			$this->error("删除失败！");
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
			$image = D('Image')->handle($this->merchant_session['scenic_id'], 'scenic/guide', 1,$param);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$store_image_class = new scenic_image();
				$url = $store_image_class->get_image_by_path($title,$this->config['site_url'],'guide','-1');
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