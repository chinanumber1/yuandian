<?php
/*
 * 景内推荐商品
 *   Writers    hanlu
 *   BuildTime  2016/09/21 11:39
 */
class Scenic_mapAction extends BaseAction{
	# 分类列表
    public function index(){
		$database_scenic = D('Scenic_map_category');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
    	$condition_scenic['map_fid'] = 0;
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_guide = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('map_sort DESC,map_id DESC')->select();
		if($now_guide){
			$scenic_image_class = new scenic_image();
			foreach($now_guide as &$v){
				$image	=	$scenic_image_class->get_image_by_path($v['map_img'],$this->config['site_url'],'map');
				$v['map_img']	=	$image['image'];
				unset($image);
			}
		}
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_guide',$now_guide);
		$this->display();
    }
    # 分类列表
    public function one_index(){
		$database_scenic = D('Scenic_map_category');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
    	$condition_scenic['map_fid'] = $_GET['map_id'];
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_guide = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('map_sort DESC,map_id DESC')->select();
		if($now_guide){
			$scenic_image_class = new scenic_image();
			foreach($now_guide as &$v){
				$image	=	$scenic_image_class->get_image_by_path($v['map_img'],$this->config['site_url'],'map');
				$v['map_img']	=	$image['image'];
				unset($image);
			}
		}
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_guide',$now_guide);
		$this->display();
    }
    # 新增分类
    public function add(){
		$data	=	array();
    	$data['scenic_id']	=	$this->merchant_session['scenic_id'];
    	if(IS_POST){
			if(empty($_POST['map_name'])){
				$this->error('分类名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("图片必须上传！");
            }
			$data['map_img']  = implode(';',$_POST['pic']);	//图片
			$data['map_name'] = $_POST['map_name'];	//分类名
			$data['map_sort'] = $_POST['map_sort'];	//排序
			$data['status']   = $_POST['status'];	//状态
			$data['add_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_map_category')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 新增分类
    public function one_add(){
		$data	=	array();
    	$data['scenic_id']	=	$this->merchant_session['scenic_id'];
    	if(IS_POST){
    		if(empty($_POST['map_id'])){
				$this->error('分类父ID不能为空！');
			}
			if(empty($_POST['map_name'])){
				$this->error('分类名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("图片必须上传！");
            }
			$data['map_img']  = implode(';',$_POST['pic']);	//图片
			$data['map_name'] = $_POST['map_name'];	//分类名
			$data['map_sort'] = $_POST['map_sort'];	//排序
			$data['status']   = $_POST['status'];	//状态
			$data['map_fid']   = $_POST['map_id'];	//分类父ID
			$data['add_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_map_category')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 修改分类
    public function edit(){
		$data	=	array();
    	if(IS_POST){
			if(empty($_POST['map_name'])){
				$this->error('分类名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("图片必须上传！");
            }
			$data['map_img']  = implode(';',$_POST['pic']);	//图片
			$data['map_name'] = $_POST['map_name'];	//分类名
			$data['map_sort'] = $_POST['map_sort'];	//排序
			$data['status']   = $_POST['status'];	//状态
			$where['map_id']	=	$_POST['map_id'];
            $add	=	M('Scenic_map_category')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['map_id']	=	$_GET['map_id'];
			$now_order	=	M('Scenic_map_category')->where($where)->find();
			if(!empty($now_order)){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$now_order['map_img']);
				foreach($tmp_pic_arr as $key=>$value){
					$now_order['pic'][$key]['title'] = $value;
					$now_order['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'map','1');
				}
			}
			$this->assign('now_order',$now_order);
			$this->display();
		}
    }
    # 修改分类
    public function one_edit(){
		$data	=	array();
    	if(IS_POST){
			if(empty($_POST['map_name'])){
				$this->error('分类名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("图片必须上传！");
            }
			$data['map_img']  = implode(';',$_POST['pic']);	//图片
			$data['map_name'] = $_POST['map_name'];	//分类名
			$data['map_sort'] = $_POST['map_sort'];	//排序
			$data['status']   = $_POST['status'];	//状态
			$data['map_fid']   = $_POST['map_fid'];	//父ID
			$where['map_id']	=	$_POST['map_id'];
            $add	=	M('Scenic_map_category')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['map_id']	=	$_GET['map_id'];
			$now_order	=	M('Scenic_map_category')->where($where)->find();
			if(!empty($now_order)){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$now_order['map_img']);
				foreach($tmp_pic_arr as $key=>$value){
					$now_order['pic'][$key]['title'] = $value;
					$now_order['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'map','1');
				}
			}
			$this->assign('now_order',$now_order);
			$this->display();
		}
    }
    # 删除分类
    public function del(){
    	$where['map_id']	=	$_GET['map_id'];
    	if(empty($where)){
			$this->error('分类ID不能为空');
    	}
    	$ticket	=	M('Scenic_map_category')->where($where)->delete();
    	if($ticket){
    		M('Scenic_map_category')->where(array('map_fid'=>$_GET['map_id']))->delete();
    		M('Scenic_map_category')->where(array('map_fid'=>$_GET['map_id']))->delete();
			$this->success("删除成功！");
    	}else{
			$this->error("删除失败！");
    	}
    }
    # 删除分类
    public function one_del(){
    	$where['map_id']	=	$_GET['map_id'];
    	if(empty($where)){
			$this->error('分类ID不能为空');
    	}
    	$ticket	=	M('Scenic_map_category')->where($where)->delete();
    	if($ticket){
    		M('Scenic_map_category')->where($where)->delete();
			$this->success("删除成功！");
    	}else{
			$this->error("删除失败！");
    	}
    }
    # 地图列表
    public function com_list(){
		$database_scenic = D('Scenic_map');
    	$condition_scenic['map_id'] = $_GET['map_id'];
    	$condition_scenic['map_fid'] = $_GET['map_fid'];
    	if(empty($condition_scenic)){
			$this->error("列表出错！");
    	}
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_guide = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('com_id DESC')->select();
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_guide',$now_guide);
		$url	=	U('com_add',array('map_id'=>$_GET['map_id'],'map_fid'=>$_GET['map_fid']));
		$this->assign('url',$url);
		$this->display();
    }
    # 新增地图
    public function com_add(){
		$data	=	array();
    	$data['map_id']	=	$_POST['map_id'];
    	$data['map_fid']	=	$_POST['map_fid'];
    	if(IS_POST){
			if(empty($_POST['com_name'])){
				$this->error('分类名必填！');
			}
			if(empty($_POST['long_lat'])){
				$this->error('请选择店铺经纬度');
			}
			$long_lat = explode(',',$_POST['long_lat']);
			$data['long'] = $long_lat[0];
			$data['lat'] = $long_lat[1];
			$data['com_name'] = $_POST['com_name'];	//地图名
			$data['status']	  = $_POST['status'];	//状态
			$data['add_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_map')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 修改地图
    public function com_edit(){
		$data	=	array();
    	if(IS_POST){
			if(empty($_POST['com_name'])){
				$this->error('分类名必填！');
			}
			if(empty($_POST['long_lat'])){
				$this->error('请选择店铺经纬度');
			}
			$long_lat = explode(',',$_POST['long_lat']);
			$data['long'] = $long_lat[0];
			$data['lat'] = $long_lat[1];
			$data['com_name'] = $_POST['com_name'];	//地图名
			$data['status']	  = $_POST['status'];	//状态
			$where['com_id']	=	$_POST['com_id'];
            $add	=	M('Scenic_map')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['com_id']	=	$_GET['com_id'];
			$now_order	=	M('Scenic_map')->where($where)->find();
			if($now_order['long'] == 0){
				unset($now_order['long']);
			}
			if($now_order['lat'] == 0){
				unset($now_order['lat']);
			}
			$this->assign('now_order',$now_order);
			$this->display();
		}
    }
    # 删除地图
    public function com_del(){
		$where['com_id']	=	$_GET['com_id'];
    	if(empty($where)){
			$this->error('商品ID不能为空');
    	}
    	$ticket	=	M('Scenic_map')->where($where)->delete();
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
			$image = D('Image')->handle($this->merchant_session['scenic_id'], 'scenic/map', 1,$param);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$store_image_class = new scenic_image();
				$url = $store_image_class->get_image_by_path($title,$this->config['site_url'],'map','-1');
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
	# 地图上传说明
	public function map_explain(){
		$this->display();
	}
	# 地图demo
	public function map_demo(){
		$this->display();
	}
}