<?php
/*
 * 景区管理
 *   Writers    hanlu
 *   BuildTime  2016/07/04 16:20
 */

class ScenicAction extends BaseAction{
    # 景区项目
    public function project(){
    	$database_scenic = D('Scenic_project');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_scenic = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('project_sort DESC,project_id DESC')->select();
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_scenic',$now_scenic);
		$this->display();
    }
    # 添加景区项目
    public function project_add(){
		if(IS_POST){
			if (empty($_POST['long_lat'])) {
				$this->error('项目经纬度必填！');
			}
            if (empty($_POST['project_title'])) {
                $this->error("项目名不能为空！");
            }
            if(empty($_POST['project_conter'])){
				$this->error("项目描述不能为空！");
            }
            if(empty($_POST['project_conter_audio'])){
				$this->error("项目语音文本不能为空！");
            }
			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
            $data	=	array();
			$long_lat = explode(',',$_POST['long_lat']);
			$data['long'] = $long_lat[0];
			$data['lat'] = $long_lat[1];
			$data['project_title'] = $_POST['project_title'];
			$data['project_conter'] = $_POST['project_conter'];
			$data['project_conter_audio'] = $_POST['project_conter_audio'];
			$data['project_sort'] = $_POST['project_sort'];
			$data['project_price'] = $_POST['project_price'];
			$data['project_pic'] = implode(';',$_POST['pic']);
			$data['project_status'] = 1;
            $data['scenic_id'] = $this->merchant_session['scenic_id'];
            $data['create_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_project')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 修改景区项目
    public function project_edit(){
		if(IS_POST){
			if(empty($_POST['project_id'])){
				$this->error('项目ID不能为空');
			}else{
				$where['project_id']	=	$_POST['project_id'];
			}
			if(empty($_POST['long_lat'])){
				$this->error('项目经纬度必填！');
			}
            if(empty($_POST['project_title'])){
                $this->error("项目名不能为空！");
            }
            if(empty($_POST['project_conter'])){
				$this->error("项目描述不能为空！");
            }
            if(empty($_POST['project_conter_audio'])){
				$this->error("项目语音文本不能为空！");
            }
			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
            $data	=	array();
			$long_lat = explode(',',$_POST['long_lat']);
			$data['long'] = $long_lat[0];
			$data['lat'] = $long_lat[1];
			$data['project_title'] = $_POST['project_title'];
			$data['project_conter'] = $_POST['project_conter'];
			$data['project_conter_audio'] = $_POST['project_conter_audio'];
			$data['project_sort'] = $_POST['project_sort'];
			$data['project_price'] = $_POST['project_price'];
			$data['project_pic'] = implode(';',$_POST['pic']);
			$data['project_status'] = $_POST['project_status'];
            $data['scenic_id'] = $this->merchant_session['scenic_id'];
            $data['last_time'] = $_SERVER['REQUEST_TIME'];		//更新时间
            $add	=	M('Scenic_project')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			if(empty($_GET['project_id'])){
				$this->error("项目ID不能为空");
			}
			$find	=	M('Scenic_project')->field(true)->where(array('project_id'=>$_GET['project_id']))->find();
			if(!empty($find['project_pic'])){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$find['project_pic']);
				foreach($tmp_pic_arr as $key=>$value){
					$find['pic'][$key]['title'] = $value;
					$find['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'project','1');
				}
			}
			$this->assign('pro',$find);
			$this->display();
		}
    }
    # 删除项目
    public function project_del(){
		if(empty($_GET['project_id'])){
			$this->error("项目ID不能为空");
		}
		$delete	=	M('Scenic_project')->where(array('project_id'=>$_GET['project_id']))->delete();
		if($delete){
			$this->success("删除成功！");
        }else{
            $this->error("删除失败！");
        }
    }
    # 项目详情
    public function project_details(){
    	if(empty($_GET['project_id'])){
			$this->error("项目ID不能为空");
		}
    	$where['project_id']	=	$_GET['project_id'];
    	$database_scenic = D('Scenic_project_details');
    	$count_store = $database_scenic->where($where)->count();
    	$p = new Page($count_store,15);
		$now_scenic = $database_scenic->field(true)->where($where)->limit($p->firstRow.','.$p->listRows)->order('project_sort DESC')->select();
		if(!empty($now_scenic)){
			$store_image_class = new scenic_image();
			foreach($now_scenic as $key=>$value){
				$tmp_pic_arr = explode(';',$value['project_pic']);
				$now_scenic[$key]['title'] = $tmp_pic_arr[0];
				$now_scenic[$key]['url'] = $store_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'project','1');
			}
		}
		$project_title	=	M('Scenic_project')->field('project_title')->where($where)->find();
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_scenic',$now_scenic);
		$this->assign('project_title',$project_title['project_title']);
		$this->display();
    }
    # 添加景区项目
    public function project_details_add(){
    	$project_id	=	I('project_id');
    	if(empty($project_id)){
			$this->error("项目ID不能为空");
		}
		if(IS_POST){
			if(empty($_POST['project_title'])){
				$this->error("项目标题不能为空！");
            }
            if(empty($_POST['project_conter'])){
				$this->error("项目描述不能为空！");
            }
			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
            $data	=	array();
			$data['project_title'] = $_POST['project_title'];
			$data['project_conter'] = $_POST['project_conter'];
			$data['project_conter_audio'] = $_POST['project_conter_audio'];
			$data['project_sort'] = $_POST['project_sort'];
			$data['project_pic'] = implode(';',$_POST['pic']);
			$data['status'] = 1;
            $data['project_id'] = $_POST['project_id'];
            $data['create_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_project_details')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 修改景区项目
    public function project_details_edit(){
    	$project_id	=	I('project_id');
    	if(empty($project_id)){
			$this->error("项目ID不能为空");
		}
		if(IS_POST){
			if(empty($_POST['project_title'])){
				$this->error("项目标题不能为空！");
            }
			if(empty($_POST['pigcms_id'])){
				$this->error("项目详情ID不能为空");
			}
            if(empty($_POST['project_conter'])){
				$this->error("项目描述不能为空！");
            }
			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
            $data	=	array();
			$data['project_conter'] = $_POST['project_conter'];
			$data['project_conter_audio'] = $_POST['project_conter_audio'];
			$data['project_title'] = $_POST['project_title'];
			$data['project_sort'] = $_POST['project_sort'];
			$data['project_pic'] = implode(';',$_POST['pic']);
			$data['status'] = 1;
            $data['project_id'] = $_POST['project_id'];
            $data['last_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_project_details')->where(array('pigcms_id'=>$_POST['pigcms_id']))->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			if(empty($_GET['pigcms_id'])){
				$this->error("项目详情ID不能为空");
			}
			$scenic_project_details	=	M('Scenic_project_details')->field(true)->where(array('pigcms_id'=>$_GET['pigcms_id']))->find();
			if(!empty($scenic_project_details)){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$scenic_project_details['project_pic']);
				foreach($tmp_pic_arr as $k=>$v){
					$scenic_project_details['pic'][$k]['title'] = $v;
					$scenic_project_details['pic'][$k]['url'] = $store_image_class->get_image_by_path($v,$this->config['site_url'],'project','1');
				}
			}
			$this->assign('details',$scenic_project_details);
			$this->display();
		}
    }
    # 删除项目
    public function project_details_del(){
		if(empty($_GET['pigcms_id'])){
			$this->error("项目详情ID不能为空");
		}
		$delete	=	M('Scenic_project_details')->where(array('pigcms_id'=>$_GET['pigcms_id']))->delete();
		if($delete){
			$this->success("删除成功！");
        }else{
            $this->error("删除失败！");
        }
    }
	# 上传图片
	public function ajax_upload_pic(){
		if($_FILES['imgFile']['error'] != 4){
			$param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $this->config['group_pic_width'];
            $param['thumbMaxHeight'] = $this->config['group_pic_height'];
            $param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'scenic/project',1,$param);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$merchant_image_class = new scenic_image();
				$url = $merchant_image_class->get_image_by_path($title,$this->config['site_url'],'project','-1');
				exit(json_encode(array('error' => 0, 'url' => $url['image'], 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	# 删除图片
	public function ajax_del_pic(){
		$merchant_image_class = new scenic_image();
		$merchant_image_class->del_image_by_path($_POST['path']);
	}


}