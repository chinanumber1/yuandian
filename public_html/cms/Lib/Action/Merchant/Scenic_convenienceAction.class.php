<?php
/*
 * 景区便民服务
 *   Writers    hanlu
 *   BuildTime  2016/07/07 10:00
 */

class Scenic_convenienceAction extends BaseAction{
    # 景区便民服务
    public function index(){
    	$database_scenic = M('Scenic_convenience');
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_scenic =	$database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('pigcms_id DESC')->select();

		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_scenic',$now_scenic);
		$this->display();
    }
    # 添加便民
    public function add(){
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
		if(IS_POST){
			if (empty($_POST['long_lat'])) {
				$this->error('经纬度必填！');
			}
            if (empty($_POST['name'])) {
                $this->error("便民名称必填！");
            }
            if(empty($_POST['address'])){
				$this->error("地址必填！");
            }
            if(empty($_POST['pic'])){
				$this->error("图片至少上传一张");
            }
            $data	=	array();
			$long_lat = explode(',',$_POST['long_lat']);
			$data['long'] = $long_lat[0];
			$data['lat'] = $long_lat[1];
			$data['name'] = $_POST['name'];
			$data['address'] = $_POST['address'];
			$data['type'] = $_POST['type'];
            $data['scenic_id'] = $this->merchant_session['scenic_id'];
            $data['img'] = implode(';',$_POST['pic']);
            $data['create_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_convenience')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$category	=	M('Scenic_convenience_category')->field(true)->where($condition_scenic)->select();
			$this->assign('category',$category);
			$this->display();
		}
    }
    # 修改便民
    public function edit(){
		if(IS_POST){
			if (empty($_POST['long_lat'])) {
				$this->error('经纬度必填！');
			}
            if (empty($_POST['name'])) {
                $this->error("便民名称必填！");
            }
            if(empty($_POST['address'])){
				$this->error("地址必填！");
            }
            if(empty($_POST['pic'])){
				$this->error("图片至少上传一张");
            }
            $where['pigcms_id']	=	$_POST['pigcms_id'];
            $data	=	array();
			$long_lat = explode(',',$_POST['long_lat']);
			$data['long'] = $long_lat[0];
			$data['lat'] = $long_lat[1];
			$data['name'] = $_POST['name'];
			$data['address'] = $_POST['address'];
			$data['type'] = $_POST['type'];
            $data['img'] = implode(';',$_POST['pic']);
            $add	=	M('Scenic_convenience')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$category	=	M('Scenic_convenience_category')->field(true)->where($condition_scenic)->select();
			$this->assign('category',$category);
			if(empty($_GET['pigcms_id'])){
				$this->error("便民ID不能为空");
			}
			$find	=	M('Scenic_convenience')->where(array('pigcms_id'=>$_GET['pigcms_id']))->find();
			if(!empty($find['img'])){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$find['img']);
				foreach($tmp_pic_arr as $key=>$value){
					$find['pic'][$key]['title'] = $value;
					$find['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'convenience','1');
				}
			}
			$this->assign('conven',$find);
			$this->display();
		}
    }
    # 删除便民
    public function del(){
		if(empty($_GET['pigcms_id'])){
			$this->error("便民ID不能为空");
		}
		$delete	=	M('Scenic_convenience')->where(array('pigcms_id'=>$_GET['pigcms_id']))->delete();
		if($delete){
			$this->success("删除成功！");
        }else{
            $this->error("删除失败！");
        }
    }
    # 新增分类
    public function category_add(){
		if(IS_POST){
			$data['scenic_id'] = $this->merchant_session['scenic_id'];
			if(empty($_POST['name'])){
				$this->error("分类名不能为空！");
			}
			$data['name']	=	$_POST['name'];
			$data['create_time']	=	$_SERVER['REQUEST_TIME'];
			$add	=	M('Scenic_convenience_category')->data($data)->add();
			if($add){
				$this->success("新增成功！");
	        }else{
	            $this->error("新增失败！");
	        }
		}else{
			$this->display();
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
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'scenic/convenience',1,$param);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$merchant_image_class = new scenic_image();
				$url = $merchant_image_class->get_image_by_path($title,$this->config['site_url'],'convenience','-1');
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