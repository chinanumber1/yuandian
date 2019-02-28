<?php
/*
 * BBS管理
 *
 */
class BbsAction extends BaseAction{
	protected $village_id;
	protected $village;
	protected $bbs;

	public function _initialize(){
		parent::_initialize();

		$this->village_id = $this->house_session['village_id'];
		$group_name	=	strtolower(GROUP_NAME);
		if($group_name == 'house'){
			$this->village = D('House_village')->where(array('village_id'=>$this->village_id))->find();
		}
		if(empty($this->village)){
			$this->error('该小区不存在！');
		}else{
			$aBbs	=	M('Bbs')->where(array('third_type'=>$group_name,'third_id'=>$this->village['village_id']))->find();
		}
		//	如果第一次进入论坛，会自动创建一个论坛
		if(empty($aBbs)){
			$arr	=	array(
				'third_type'	=>	$group_name,
				'third_id'	=>	$this->village['village_id'],
				'auto_verify_post'	=>	1,
				'auto_verify_reply'	=>	1,
				'index_icon'	=>	'/tpl/Wap/default/static/bbs/img/1.png',
				'index_name'	=>	'全部',
			);
			$addBbs	=	M('Bbs')->add($arr);
			$arr['bbs_id']	=	$addBbs;
			$this->bbs	=	$arr;
		}else{
			$this->bbs	=	$aBbs;
		}
	}

	//	分类列表
    public function index(){
        //分类列表-查看 权限
        if (!in_array(127, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		// $aBbsList	=	D('Bbs')->get_category_list($this->bbs['bbs_id']);
		// foreach($aBbsList as $k=>$v){
		// 	$aBbsList[$k]['cat_logo']	=	$this->config['site_url'].$v['cat_logo'];
		// }
		// $this->assign('aBbsList',$aBbsList);
		// if($aBbsList){
            $where['bbs_id'] = $this->bbs['bbs_id'];
            $list = D('Bbs')->bbs_category_page_list($where);
            if(!$list){
                $this->error('处理数据有误！');
            }else{
                $this->assign('pagebar',$list['list']['pagebar']);
                $this->assign('list',$list['list']['list']);
            }
     	// }
		$this->display();
    }

    //	修改主图片的页面
    public function	modify_index_img(){
    	$aBbs	=	M('Bbs')->where(array('bbs_id'=>$this->bbs['bbs_id']))->find();
    	$this->assign('aBbs',$aBbs);
		$this->display();
    }

    //	修改主图片的操作
    public function	modify_img(){
		if(IS_POST){
		
			$arr	=	array(
				'index_name'	=>	$_POST['cat_name'],
				'index_icon'	=>	$_POST['pic'],
				'auto_verify_post'	=>	$_POST['auto_verify_post'],
				'auto_verify_reply'	=>	$_POST['auto_verify_reply'],
			);
			$sAdd	=	D('Bbs')->where(array('bbs_id'=>$this->bbs['bbs_id']))->data($arr)->save();
			if($sAdd){
				$this->success('修改成功',U('Bbs/index'));exit;
			}else{
				$this->error('修改失败',U('Bbs/index'));exit;
			}
		}else{
			$this->display();
		}
    }

	//	新增分类的页面
    public function category_add_show(){
        //分类列表-添加 权限
        if (!in_array(129, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$this->display();
    }

    //	新增分类的操作
    public function category_add(){
        //分类列表-添加 权限
        if (!in_array(129, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

    	if(IS_POST){
			$arr	=	array(
				'bbs_id'	=>	$this->bbs['bbs_id'],
				'cat_name'	=>	$_POST['cat_name'],
				'cat_order'	=>	$_POST['cat_order'],
				'cat_status'	=>	$_POST['cat_status'],
				'cat_logo'	=>	$_POST['pic'],
				'add_time'	=>	time(),
				'last_time'	=>	time(),
			);
			$sAdd	=	D('Bbs_category')->add($arr);
			if($sAdd){
				$this->success('新增分类成功',U('Bbs/index'));exit;
			}else{
				$this->error('新增分类失败',U('Bbs/index'));exit;
			}
		}else{
			$this->display();
		}
    	//if(IS_POST){
//			if ($_FILES['file']['error'] != 4) {
//				set_time_limit(0);
//				$upload_dir = './upload/bbs/'.date('Ymd').'/';
//				if (!is_dir($upload_dir)) {
//					mkdir($upload_dir, 0777, true);
//				}
//				import('ORG.Net.UploadFile');
//				$upload = new UploadFile();
//				$upload->maxSize = 10 * 1024 * 1024;
//				$upload->allowExts = array('png','gif','bmp','jpg','jpep');
//				$upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
//				$upload->savePath = $upload_dir;
//				$upload->thumb = false;
//				$upload->thumbType = 0;
//				$upload->imageClassPath = '';
//				$upload->thumbPrefix = '';
//				$upload->saveRule = 'uniqid';
//				if ($upload->upload()) {
//					$uploadList = $upload->getUploadFileInfo();
//					$arr	=	array(
//						'bbs_id'	=>	$this->bbs['bbs_id'],
//						'cat_name'	=>	$_POST['cat_name'],
//						'cat_order'	=>	$_POST['cat_order'],
//						'cat_status'	=>	$_POST['cat_status'],
//						'cat_logo'	=>	$uploadList[0]['savepath'].$uploadList[0]['savename'],
//						'add_time'	=>	time(),
//						'last_time'	=>	time(),
//					);
//					$l	=	substr($arr['cat_logo'],0,1);
//					if($l == "."){
//						$arr['cat_logo']	=	ltrim($arr['cat_logo'],".");
//					}
//					$sAdd	=	D('Bbs_category')->add($arr);
//					$this->success('新增分类成功',U('Bbs/index'));exit;
//				} else {
//					$this->error($upload->getErrorMsg());exit;
//				}
//			}
//			$this->error('文件上传失败');exit;
//		}else{
//			$this->display();
//		}
    }

    //	更改状态页面 修改分类
    public function cat_status($cat_id=0){
    	$aBbsCategory	=	D('Bbs_category')->where(array('cat_id'=>$cat_id))->find();
    	$this->assign('aBbsCategory',$aBbsCategory);
    	$this->display();
    }

    //	更改状态是否成功
    public function is_cat_status(){
        //分类列表-编辑 权限
        if (!in_array(130, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

    	$arr	=	array(
			'cat_name'	=>	$_POST['cat_name'],
			'cat_order'	=>	$_POST['cat_order'],
			'cat_status'	=>	$_POST['cat_status'],
    	);
    	if($_POST['pic']){
			$arr['cat_logo']	=	$_POST['pic'];
    	}
    	$aBbsCategory	=	D('Bbs_category')->where(array('cat_id'=>$_POST['cat_id']))->save($arr);
    	if($aBbsCategory){
    		$this->success('修改分类成功',U('Bbs/index'));exit;
    	}else{
			$this->error('修改分类失败',U('Bbs/index'));exit;
    	}
    }

    //	文章列表
    public function aricle_list($cat_id=0){
        //文章列表-查看 权限
        if (!in_array(131, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

    	if($cat_id){
			$where['cat_id'] = $cat_id;
    	}else{
			$where['cat_id']	=	I('cat_id');
    	}
        $list = D('Bbs')->bbs_aricle_page_list($where);

        if(!$list){
            $this->error('处理数据有误！');
        }else{
        	foreach($list['list']['list'] as $k=>$v){
				$aricle_img_arr = explode(';',$v['aricle_img']);
				$list['list']['list'][$k]['aricle_img'] = $aricle_img_arr[0];
				if($v['exp_time'] == 0){
					$list['list']['list'][$k]['exp_time']	=	'不过期';
				}else if($v['exp_time'] < time()){
					$list['list']['list'][$k]['exp_time']	=	'已过期';
				}
                // 查询对应的所需业主信息
                $where = 'uid='.$v['uid'].' AND status=1 And (type=0 OR type=3)';
                $list['list']['list'][$k]['village_user_info'] = D('House_village_user_bind')->where($where)->field('name,phone')->find();
        	}
        	$this->assign('cat_id',$cat_id);
            $this->assign('pagebar',$list['list']['pagebar']);
            $this->assign('aBbsAricle',$list['list']['list']);
        }
		$this->display();
    }

	public function activity_apply_list(){
        //文章列表-查看报名 权限
        if (!in_array(137, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		$aricle_id = $_GET['aricle_id'] + 0;

		if(!$aricle_id){
			$this->error('访问页面有误！');
		}

		$database_bbs_activity_apply = D('Bbs_activity_apply');
		$apply_where['aricle_id'] = $aricle_id;
		$apply_where['is_del'] = 0;
		$result = $database_bbs_activity_apply->bbs_activity_apply_page_list($apply_where);
		if(!$result){
			$this->error('数据处理有误！');
		}

		$this->assign('result' , $result['result']);
		$this->assign('user_list' , $result['user_list']);

		$this->display();
	}

	public function activity_apply_delete(){
        //文章列表-查看删除报名 权限
        if (!in_array(138, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		$id = $_GET['id'] + 0;
		if(!$id){
			$this->error('传递参数有误！');
		}

		$database_bbs_activity_apply = D('Bbs_activity_apply');

		$del_data['del_time'] = time();
		$del_data['is_del'] = 1;
		$del_where['id'] = $id;

		$insert_id = $database_bbs_activity_apply->where($del_where)->data($del_data)->save();

		if($insert_id){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}


    //	文章详情
    public function aricle_list_details($aricle_id=0){
        //文章列表-查看 权限
        if (!in_array(131, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		$aBbsAricleDetails	=	D('Bbs_aricle')->where(array('aricle_id'=>$aricle_id))->find();
		$aBbsAricleImg	=	D('Bbs_aricle_img')->where(array('aricle_id'=>$aricle_id))->select();
		$this->assign('aBbsAricleDetails',$aBbsAricleDetails);
		$this->assign('aBbsAricleImg',$aBbsAricleImg);
		$this->display();
    }

    //	文章状态更改页面
    public function aricle_status_show($aricle_id=0){
        //文章列表-查看 权限
        if (!in_array(131, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

    	$aBbsAricel	=	D('Bbs_aricle')->where(array('aricle_id'=>$aricle_id))->find();
    	$this->assign('aBbsAricel',$aBbsAricel);
		$this->display();
    }

    //	文章状态是否成功
    public function aricle_status(){
        //文章列表-更改状态 权限
        if (!in_array(132, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

    	$aFind	=	D('Bbs_aricle')->where(array('aricle_id'=>$_POST['aricle_id']))->find();
    	if($aFind){
    		$aBbsAricleStatus	=	D('Bbs_aricle')->where(array('aricle_id'=>$_POST['aricle_id']))->save(array('aricle_status'=>$_POST['aricle_status'],'aricle_sort'=>$_POST['aricle_sort']));
    		if($aBbsAricleStatus){
    			if($_POST['aricle_status'] == 1){
					M('Bbs_category')->where(array('cat_id'=>$aFind['cat_id']))->setInc('cat_aricle_num');
				}else{
					if($aFind['aricle_status'] == 1){
						M('Bbs_category')->where(array('cat_id'=>$aFind['cat_id']))->setDec('cat_aricle_num');
					}
				}
    			$this->success('文章状态修改成功',U('Bbs/aricle_list',array('cat_id'=>$_POST['cat_id'])));exit;
    		}else{
				$this->error('文章状态修改失败',U('Bbs/aricle_list',array('cat_id'=>$_POST['cat_id'])));exit;
    		}
    	}else{
			$this->error('文章状态修改失败',U('Bbs/aricle_list',array('cat_id'=>$_POST['cat_id'])));exit;
    	}
    }

    //	评论列表
    public function comment_list($aricle_id=0){
        //评论列表 权限
        if (!in_array(134, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		$where['aricle_id'] = $_GET['aricle_id'];
        $list = D('Bbs')->bbs_comment_page_list($where);
        if(!$list){
            $this->error('处理数据有误！');
        }else{
        	$cat_id	=	D('Bbs_aricle')->field(array('cat_id'))->where($where)->find();
            $this->assign('pagebar',$list['list']['pagebar']);
            $this->assign('aBbsComment',$list['list']['list']);
            $this->assign('cat_id',$cat_id['cat_id']);
        }
		$this->display();
    }


	public function comment_delete(){
        //文章-删除 权限
        if (!in_array(133, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		$aricle_id = $_GET['aricle_id'] + 0;

		if(empty($aricle_id)){
			$this->error('传递参数有误！~~~');
		}

		$database_bbs_comment = D('Bbs_comment');
		$database_bbs_aricle = D('Bbs_aricle');
		$bbs_condition['aricle_id'] = $aricle_id;
		$database_bbs_comment->where($bbs_condition)->delete();

		$insert_id = $database_bbs_aricle->where($bbs_condition)->delete();

		if($insert_id){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}

    //评论状态更改页面
    public function comment_status_show($comment_id=0){
        //评论列表 权限
        if (!in_array(134, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

    	$aBbsComment	=	D('Bbs_comment')->where(array('comment_id'=>$comment_id))->find();
    	if($aBbsComment){
			$aricle_id	=	D('Bbs_aricle')->field(array('aricle_id','cat_id'))->where(array('aricle_id'=>$aBbsComment['aricle_id']))->find();
    	}
    	$this->assign('aBbsComment',$aBbsComment);
    	$this->assign('aricle_id',$aricle_id);
		$this->display();
    }

    //	评论状态是否成功
    public function comment_status(){
        //评论列表-更改状态 权限
        if (!in_array(135, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

    	$aFind	=	D('Bbs_comment')->where(array('comment_id'=>$_POST['comment_id']))->find();
    	if($aFind){
    		$aBbsCommentStatus	=	D('Bbs_comment')->where(array('comment_id'=>$_POST['comment_id']))->save(array('comment_status'=>$_POST['comment_status']));
    		if($aBbsCommentStatus){
    			if($_POST['comment_status'] == 1){
					M('Bbs_aricle')->where(array('aricle_id'=>$aFind['aricle_id']))->setInc('aricle_comment_num');
				}else{
					if($aFind['comment_status'] == 1){
						M('Bbs_aricle')->where(array('aricle_id'=>$aFind['aricle_id']))->setDec('aricle_comment_num');
					}
				}
    			$this->success('评论状态修改成功',U('Bbs/comment_list',array('aricle_id'=>$_POST['aricle_id'])));exit;
    		}else{
				$this->error('评论状态修改失败',U('Bbs/comment_list',array('aricle_id'=>$_POST['aricle_id'])));exit;
    		}
		}else{
			$this->error('评论状态修改失败',U('Bbs/comment_list',array('aricle_id'=>$_POST['aricle_id'])));exit;
		}
    }


	public function delete_category(){
		
		$cat_id = $_GET['cat_id'] + 0;
		if(empty($cat_id)){
			$this->error('传递参数有误！~~~');
		}

		$database_bbs_aricle = D('Bbs_aricle');
		$bbs_aricle_condition['cat_id'] = $cat_id;
		$bbs_aricle_condition['aricle_status'] = array('lt' , 4);
		$bbs_article_count = $database_bbs_aricle->where($bbs_aricle_condition)->count();

		if($bbs_article_count > 0){
			$this->error('请先删除分类下的文章！');
		}

		$database_bbs_category = D('Bbs_category');
		$bbs_category_condition['cat_id'] = $cat_id;
		$insert_id = $database_bbs_category->where($bbs_category_condition)->delete();

		if($insert_id){
			$this->success('删除分类成功！');
		}else{
			$this->error('删除分类失败！');
		}
	}

    //	图片上传
    public function ajax_upload_pic(){
	    //分类-添加 编辑 权限
	    if (!in_array(129, $this->house_session['menus']) && !in_array(130, $this->house_session['menus'])) {
	        $this->error('对不起，您没有权限执行此操作');
	    }

		if ($_FILES['imgFile']['error'] != 4) {
			$img_mer_id = $this->bbs['bbs_id'];
			$upload_dir = './upload/bbs/category/' . $img_mer_id . '/';
			if (!is_dir($upload_dir)) {
			    mkdir($upload_dir, 0777, true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = $this->config['group_pic_size'] * 1024 * 1024;
			$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
			$upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
			$upload->savePath = $upload_dir;
			$upload->thumb = true;
			$upload->imageClassPath = 'ORG.Util.Image';
			$upload->thumbPrefix = 'm_,s_';
			$upload->thumbMaxWidth = $this->config['group_pic_width'];
			$upload->thumbMaxHeight = $this->config['group_pic_height'];
			$upload->thumbRemoveOrigin = false;
			$upload->saveRule = 'uniqid';
			if ($upload->upload()) {
			    $uploadList = $upload->getUploadFileInfo();

			    $title = $img_mer_id . ',' . $uploadList[0]['savename'];

			    $slide_image_class = new slide_image();
			    $url = $slide_image_class->get_image_by_path($title,2);
			    $title = $img_mer_id . '/' . $uploadList[0]['savename'];
			    exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
			} else {
			    exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
			}
	    } else {
			exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
	    }
    }

    //	图片删除
    public function ajax_del_pic(){
		$group_image_class = new slide_image();
        $group_image_class->del_image_by_path($_POST['path']);
    }
}