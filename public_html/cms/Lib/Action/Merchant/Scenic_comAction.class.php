<?php
/*
 * 景内推荐商品
 *   Writers    hanlu
 *   BuildTime  2016/09/21 11:39
 */
class Scenic_comAction extends BaseAction{
	# 分类列表
    public function index(){
		$database_scenic = D('Scenic_com_category');
    	$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_guide = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('cat_sort DESC,cat_id DESC')->select();
		if($now_guide){
			$scenic_image_class = new scenic_image();
			foreach($now_guide as &$v){
				if(empty($v)){
					continue;
				}
				$image	=	$scenic_image_class->get_image_by_path($v['cat_img'],$this->config['site_url'],'com');
				$v['cat_img']	=	$image['image'];
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
			if(empty($_POST['cat_name'])){
				$this->error('分类名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("图片必须上传！");
            }
			$data['cat_img']  = implode(';',$_POST['pic']);	//图片
			$data['cat_name'] = $_POST['cat_name'];	//分类名
			$data['cat_sort'] = $_POST['cat_sort'];	//排序
			$data['status']   = $_POST['status'];	//状态
			$data['is_recom'] = $_POST['is_recom'];	//首页推荐
			$data['add_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_com_category')->data($data)->add();
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
			if(empty($_POST['cat_name'])){
				$this->error('分类名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("图片必须上传！");
            }
			$data['cat_img']  = implode(';',$_POST['pic']);	//图片
			$data['cat_name'] = $_POST['cat_name'];	//分类名
			$data['cat_sort'] = $_POST['cat_sort'];	//排序
			$data['status']   = $_POST['status'];	//状态
			$data['is_recom'] = $_POST['is_recom'];	//首页推荐
			$data['add_time'] = $_SERVER['REQUEST_TIME'];
			$where['cat_id']	=	$_POST['cat_id'];
            $add	=	M('Scenic_com_category')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['cat_id']	=	$_GET['cat_id'];
			$now_order	=	M('Scenic_com_category')->where($where)->find();
			if(!empty($now_order)){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$now_order['cat_img']);
				foreach($tmp_pic_arr as $key=>$value){
					if(empty($value)){
						continue;
					}
					$now_order['pic'][$key]['title'] = $value;
					$now_order['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'com','1');
				}
			}
			$this->assign('now_order',$now_order);
			$this->display();
		}
    }
    # 删除分类
    public function del(){
    	$where['cat_id']	=	$_GET['cat_id'];
    	if(empty($where)){
			$this->error('分类ID不能为空');
    	}
    	$ticket	=	M('Scenic_com_category')->where($where)->delete();
    	if($ticket){
    		M('Scenic_com')->where($where)->delete();
			$this->success("删除成功！");
    	}else{
			$this->error("删除失败！");
    	}
    }
    # 推荐商品列表
    public function com_list(){
		$database_scenic = D('Scenic_com');
    	$condition_scenic['cat_id'] = $_GET['cat_id'];
    	$count_store = $database_scenic->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_guide = $database_scenic->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('sort DESC,com_id DESC')->select();
		if($now_guide){
			$scenic_image_class = new scenic_image();
			foreach($now_guide as &$v){
				if(empty($v)){
					continue;
				}
				$image	=	$scenic_image_class->get_image_by_path($v['com_img'],$this->config['site_url'],'com');
				$v['com_img']	=	$image['image'];
				unset($image);
			}
		}
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('now_guide',$now_guide);
		$url	=	U('com_add',array('cat_id'=>$_GET['cat_id']));
		$this->assign('url',$url);
		$this->display();
    }
    # 新增商品
    public function com_add(){
		$data	=	array();
    	$data['cat_id']	=	$_POST['cat_id'];
    	if(IS_POST){
			if(empty($_POST['com_name'])){
				$this->error('分类名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("图片必须上传！");
            }
			$data['com_img']  = implode(';',$_POST['pic']);	//图片
			$data['com_name'] = $_POST['com_name'];	//商品名
			$data['com_title'] = $_POST['com_title'];	//商品介绍
			$data['price'] 	  = $_POST['price'];	//价格
			$data['sort']	  = $_POST['sort'];		//排序
			$data['is_recom'] = $_POST['is_recom'];	//首页推荐
			$data['status']	  = $_POST['status'];	//状态
			$data['url']	  = $_POST['url'];		//链接
			$data['add_time'] = $_SERVER['REQUEST_TIME'];
            $add	=	M('Scenic_com')->data($data)->add();
            if($add){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
		}else{
			$this->display();
		}
    }
    # 修改商品
    public function com_edit(){
		$data	=	array();
    	if(IS_POST){
			if(empty($_POST['com_name'])){
				$this->error('分类名必填！');
			}
            if(empty($_POST['pic'])){
				$this->error("图片必须上传！");
            }
			$data['com_img']  = implode(';',$_POST['pic']);		//图片
			$data['com_name'] = $_POST['com_name'];	//商品名
			$data['com_title'] = $_POST['com_title'];	//商品介绍
			$data['price'] 	  = $_POST['price'];	//价格
			$data['sort']	  = $_POST['sort'];		//排序
			$data['is_recom'] = $_POST['is_recom'];	//首页推荐
			$data['status']	  = $_POST['status'];	//状态
			$data['url']	  = $_POST['url'];		//链接
			$where['com_id']	=	$_POST['com_id'];
            $add	=	M('Scenic_com')->where($where)->data($data)->save();
            if($add){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
		}else{
			$where['com_id']	=	$_GET['com_id'];
			$now_order	=	M('Scenic_com')->where($where)->find();
			if(!empty($now_order)){
				$store_image_class = new scenic_image();
				$tmp_pic_arr = explode(';',$now_order['com_img']);
				foreach($tmp_pic_arr as $key=>$value){
					if(empty($value)){
						continue;
					}
					$now_order['pic'][$key]['title'] = $value;
					$now_order['pic'][$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'com','1');
				}
			}
			$this->assign('now_order',$now_order);
			$this->display();
		}
    }
    # 删除商品
    public function com_del(){
		$where['com_id']	=	$_GET['com_id'];
    	if(empty($where)){
			$this->error('商品ID不能为空');
    	}
    	$ticket	=	M('Scenic_com')->where($where)->delete();
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
			$image = D('Image')->handle($this->merchant_session['scenic_id'], 'scenic/com', 1,$param);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$store_image_class = new scenic_image();
				$url = $store_image_class->get_image_by_path($title,$this->config['site_url'],'com','-1');
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