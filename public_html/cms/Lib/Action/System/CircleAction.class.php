<?php
/*
 * 圈子管理
 */

class CircleAction extends BaseAction
{
	public function index(){

		echo 123;die();
	}
//分类
	public function cateorty(){
	    $class = M('circle_cateory')->where(array('fid'=>0))->order('id ASC')->select(); 
	    $mycircle =  M('circle')->select();
	    //var_dump($mycircle);exit;
        /*
		foreach ($class as $key => $vo) {
			$c =M('circle_cateory')->where(array('fid' => $vo['id'],'level'=>2))->order('id ASC')->select();
			$class[$key]['class'] = $c;
		}
        */
		$this->assign('class', $class);
		$this->assign('mycircle',$mycircle);
		$this->display();
	   
	}
//添加分类
	public function cateAdd(){
		if(IS_POST){
    		$data=array(
    			'name'=>trim($_POST['name']),
    			'fid'=>I('post.fid'),
    			'addTime'=>time(),
    			'status'=>I('post.status')
    			);
    		if(intval($data['fid'])==0){
    			$data['level']=1;
    		}else{
    			$data['level']=2;
    		}
    		$where['name']= $data['name'];
    		if(!M('circle_cateory')->where($where)->find()){
			  $add=M('circle_cateory')->add($data);
			}else{
				$this->success('已经存在!');
			}
			if($add){
				$this->success('添加成功！');
			}else{
				$this->success('添加失败！请重试~');
			}
		}else{
            
			$this->display();
		}
	}
	//添加圈子
	function circleAdd(){
		if(IS_POST){
          $data=array(
    			'name'=>trim($_POST['name']),
    			'cate_id'=>I('post.cate_id'),
    			'create_uid'=>0,
    			//'addTime'=>time(),
    			'title'=>trim($_POST['title']),
    			'status'=>I('post.status')
    			);
          $where['name']= $data['name'];
    		if(!M('circle')->where($where)->find()){
			  $add=M('circle')->add($data);
			}else{
				$this->error('已经存在!');
			}
			if($add){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			 $where=array('fid'=>0);
			$cateList=M('circle_cateory')->where($where)->select();
			$this->assign('cateList',$cateList);
			$this->display();
		}
	}
	//编辑圈子
	function editCircle(){
		if(IS_POST){file_put_contents('logo.txt',I('post.logo'));
          if(!I('post.logo')){
				$this->error('请选择图标');
				exit;
			}
	  $where['id']=I('post.id');
      $data=array('name'=>I('post.name'),'cate_id'=>I('post.cate_id'),'status'=>I('post.status'),'logo'=>I('post.logo'),'title'=>I('post.title'));
      if(M('circle')->where($where)->save($data)){
         $this->success('编辑成功！');
      }else{
         $this->error('编辑分类失败！');
      }
		
		}else{
			if(I('get.id','false')){
			$res=M('circle')->where(array('id'=>I('get.id')))->find();
			if($res){
				$this->fu();
				$this->assign('now_category',$res);
				$this->display();
			}else{
				$this->frame_error_tips('没有找到对应信息');
			}
           
		}else{
			$this->frame_error_tips('没有找到对应信息');
		}
		}
      
	}
	//删除圈子
	function delCircle(){
    if(I('post.id')){
		$data['id']=I('post.id');
		if(M('circle')->where($data)->delete()){
             $this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
	}
//编辑分类
function editcateory(){
		if(I('id','false')){
			$res=M('circle_cateory')->where(array('id'=>I('id')))->find();
			if($res){
				$this->fu();
				$this->assign('now_category',$res);
				$this->display();
			}else{
				$this->frame_error_tips('没有找到对应信息');
			}
           
		}else{
			$this->frame_error_tips('没有找到对应信息');
		}
	}
//处理编辑分类
function docateoryedit(){
	if(I('post.id')){
		if(!I('post.icon_value')){
				$this->error('请选择图标');
				exit;
			}
	  $where['id']=I('post.id');
      $data=array('name'=>I('post.name'),'fid'=>I('post.fid'),'status'=>I('post.status'),'icon_value'=>I('post.icon_value'));
      if(M('circle_cateory')->where($where)->save($data)){
         $this->success('编辑成功！');
      }else{
         $this->error('编辑分类失败！');
      }
		
	}
}
//删除分类
function delcateory(){
	if(I('post.id')){
		$data['id']=I('post.id');
		if(M('circle_cateory')->where($data)->delete()){
             $this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
}

//动态
 function dynamic(){
	if(I('param.biaoshi')){
		if(I('param.circle_id') == 'all'){//非系统发布查找
		      $count = D()->table('pigcms_circle ca, pigcms_circle_dynamic dy,pigcms_circle_user us')->where('dy.circle_id = ca.id and dy.uid=us.id')->count();
				import('@.ORG.system_page');
				$p = new Page($count,30);
			    $dynamic=M()->table('pigcms_circle ca, pigcms_circle_dynamic dy,pigcms_circle_user us')->where('dy.circle_id = ca.id and dy.uid=us.id')->field('dy.id as id, dy.content as content, dy.title as title,dy.ding as ding,dy.status as status,dy.add_time as add_time,dy.update_time as update_time,dy.image as image,ca.name as name,us.nickName as nickname')->order('dy.id asc' )->limit($p->firstRow.','.$p->listRows)->select();
			    $pagebar = $p->show();
			    $this->quanzi();
				$this->assign('pagebar',$pagebar);
			    $this->assign('dynamicList',$dynamic);
				$this->display();
		}else if(I('param.circle_id') == 'xitong'){//系统发布查找
			
			$count = D()->table('pigcms_circle ca,pigcms_circle_dynamic dy,pigcms_circle_user us')->where('dy.circle_id = ca.id and  dy.uid=us.id and dy.uid=0')->count();
				import('@.ORG.system_page');
				$p = new Page($count,30);
			    $dynamic=M()->table('pigcms_circle ca,pigcms_circle_dynamic dy,pigcms_circle_user us')->where('dy.circle_id = ca.id and  dy.uid=us.id and dy.uid=0')->field('dy.id as id, dy.content as content, dy.title as title,dy.ding as ding,dy.status as status,dy.add_time as add_time,dy.update_time as update_time,dy.image as image,ca.name as name,us.nickName as nickname')->order('dy.id asc' )->limit($p->firstRow.','.$p->listRows)->select();
			    $pagebar = $p->show();
			    $this->quanzi();
				$this->assign('pagebar',$pagebar);
			    $this->assign('dynamicList',$dynamic);
				$this->display();
				
			
		} else{
				
				$count = D()->table('pigcms_circle  ca, pigcms_circle_dynamic dy,pigcms_circle_user us')->where('dy.circle_id = ca.id and dy.uid=us.id and dy.circle_id='.I('param.circle_id'))->count();
				import('@.ORG.system_page');
				$p = new Page($count,30);
			    $dynamic=M()->table('pigcms_circle ca, pigcms_circle_dynamic dy,pigcms_circle_user us')->where('dy.circle_id = ca.id and dy.uid=us.id and dy.circle_id='.I('param.circle_id'))->field('dy.id as id, dy.content as content, dy.title as title,dy.ding as ding,dy.status as status,dy.add_time as add_time,dy.update_time as update_time,dy.image as image,ca.name as name,us.nickName as nickname')->order('dy.id asc' )->limit($p->firstRow.','.$p->listRows)->select();
			    $pagebar = $p->show();
			    $this->quanzi();
				$this->assign('pagebar',$pagebar);
			    $this->assign('dynamicList',$dynamic);
				$this->display();
				
			
			}
     
	}else{//所有发布
	   $count = D()->table('pigcms_circle ca, pigcms_circle_dynamic dy,pigcms_circle_user us')->where('dy.circle_id = ca.id and dy.uid=us.id')->count();
		import('@.ORG.system_page');
		$p = new Page($count,30);
	    $dynamic=M()->table('pigcms_circle ca, pigcms_circle_dynamic dy,pigcms_circle_user us')->where('dy.circle_id = ca.id and dy.uid=us.id')->field('dy.id as id, dy.content as content, dy.title as title,dy.ding as ding,dy.status as status,dy.add_time as add_time,dy.update_time as update_time,dy.image as image,ca.name as name,us.nickName as nickname')->order('dy.id asc' )->limit($p->firstRow.','.$p->listRows)->select();
	    $pagebar = $p->show();
	    $this->quanzi();
		$this->assign('pagebar',$pagebar);
	    $this->assign('dynamicList',$dynamic);
		$this->display();
	 }
    }

//添加动态,编辑动态
	public function dynamicAdd(){
		if(I('get.id')){//开始编辑
			$res=M('circle_dynamic')->where(array('id'=>I('id')))->find();
			if($res['img_url']){
				$imgurl=trim($res['img_url'],'[""]');
	            $imgurl=explode(',',$imgurl);
	            foreach($imgurl as $key=>$value){
	            	$imgurl[$key]=trim($value,'""');
	            }
            }
			$this->quanzi();
			$this->assign('imgurl',$imgurl);
			$this->assign('now_category',$res);
			$this->display();
			
		}else{//开始添加
			    $this->quanzi();
				$this->display();
		}
	}
//处理编辑,处理添加
function editdynamic(){
	if($_POST['id']){//处理编辑
		    if(!$_POST['image']){
				$this->frame_error_tips('必须提供封面');
				exit;
			}
			foreach($_POST['img_url'] as $key=>$val){
				if($val){
					$img_url[$key]=$val;
				}
			}
			$img_url = implode(',',$img_url);
			unset($_POST['dosubmit']);
			$_POST['update_time'] =  time();
			 $res=D('Circle_dynamic')->where(array('id'=>intval($_POST['id'])))->save($_POST);
			if($res){
				$data2['update_time']=time();
				D('Circle_dynamic')->where($where)->save($data2);
				$this->frame_submit_tips(1,'编辑成功');
		    }else{
				$this->frame_submit_tips(0,'编辑失败！请重试~');
			}
	}else{//处理添加
           if(!$_POST['image']){
				$this->frame_error_tips('必须提供封面');
				exit;
			}
			foreach($_POST['img_url'] as $key=>$val){
				if($val){
					$img_url[$key]=$val;
				}
			}
            $img_url = implode(',',$img_url);
            $data['add_time']=time();
			$data['title'] = !empty($_POST['title']) ? $_POST['title'] : '';
			$data['circle_id'] = !empty($_POST['fid']) ? $_POST['fid'] : '';
			$data['image'] = !empty($_POST['image']) ? $_POST['image'] : '';
			$data['img_url'] = !empty($_POST['img_url']) ? $_POST['img_url'] : '';
			$data['ding'] = $_POST['ding'] ;
			$data['status'] =  $_POST['status'];
			$data['uid']=$_POST['uid'];
            $data['content']=!empty($_POST['content']) ? htmlspecialchars_decode($_POST['content']) : '';
            $res=M('circle_dynamic')->data($data)->add();
			if($res){
				$this->frame_submit_tips(1,'添加成功');
		     }else{
				$this->frame_submit_tips(0,'添加失败');
			}

		}
}

//删除动态
function deldynamic(){
	if(I('post.id')){
		$data['id']=I('post.id');
		if(M('circle_dynamic')->where($data)->delete()){
             $this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
}	
function relationList($cid=1){
        $model=M('circle_relation');
        $list=$model->where('circle_id='.$cid.' and status=0')->select();
        $this->diaplay();
	}
	
//用户列表
	function userlist(){
		//$where['status']=0;
		$where['id']=array('neq',0);
		$count=M('circle_user')->where($where)->order('id asc')->count();
		import('@.ORG.system_page');
		$p = new Page($count,30);
		$res=M('circle_user')->where($where)->order('id asc')->limit($p->firstRow.','.$p->listRows)->select();
		$pagebar = $p->show();
	    $this->assign('pagebar',$pagebar);
		$this->assign('res',$res);
		$this->display();
		
	}
//编辑用户
	function useredit(){
		if(I('id','false')){
			$res=M('circle_user')->where(array('id'=>I('id')))->find();
			if($res){
				$this->assign('now_category',$res);
				$this->display();
			}else{
				$this->frame_error_tips('没有找到对应信息');
			}
           
		}else{
			$this->frame_error_tips('没有找到对应信息');
		}
	}
	function douseredit(){
		if(IS_POST){
		$data=array('nickName'=>I('nickName'),'gender'=>I('gender'),'province'=>I('province'),'city'=>I('city'),'phone'=>I('phone'),'content'=>I('content'),'date'=>I('date'));
         if(M('circle_user')->where(array('id'=>I('id')))->save($data)){
         	$this->success('修改成功');
         }else{
         	$this->error('修改失败');
         }
		}
	}
//删除用户
   function deluser(){
	if(I('post.id')){
		$data['id']=I('post.id');
		if(M('circle_user')->where($data)->delete()){
             $this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
}
//我的收藏
function usercollection(){
	if(I('param.uid')){
		$where=array('uid'=>I('param.uid'));
        $count=M()->table('pigcms_circle_collection co, pigcms_circle_dynamic dy')->where('co.dynamic_id = dy.id and co.uid='.I('param.uid'))->field('co.id as id,co.status as status,co.add_time as add_time,dy.title as title,dy.id as did')->count();
        import('@.ORG.system_page');
		$p = new Page($count,30);
        $res=M()->table('pigcms_circle_collection co, pigcms_circle_dynamic dy')->where('co.dynamic_id = dy.id and co.uid='.I('param.uid'))->field('co.id as id,co.status as status,co.add_time as add_time,dy.title as title,dy.id as did')->order('co.id asc' )->limit($p->firstRow.','.$p->listRows)->select();
        $pagebar = $p->show();
	    $this->assign('pagebar',$pagebar);
	    $this->assign('res',$res);
	    $this->display();
	}else{
		$this->frame_error_tips('参数错误');
	}
}
//修改收藏
function modifyCollection(){
	if(IS_POST){
        $where['id']=I('post.id');
        $data['status']=I('post.status');
        $res=M('circle_collection')->where($where)->save($data);
	        if($res){
	        	$this->success('修改成功');
	        }else{
	        	$this->error('修改失败');
	        }

	}else{
		if(I('id')){
			$where['id']=I('id');
			$res=M('circle_collection')->where($where)->find();
			$this->assign('res',$res);
		    $this->display();
	    }else{
		$this->frame_error_tips('参数错误');
	    }
	}
	
}
//删除收藏
function delcollection(){
	if(I('param.did')){
		$where=array('id'=>I('param.did'));
        $res=M('circle_collection')->where($where)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}else{
       $this->error('没有找到对应信息');
	}

}
//查看收藏文章
function seearticle(){
	if(I('param.did')){
		$res=M('circle_dynamic')->where(array('id'=>I('did')))->find();
		if($res){
            if($res['img_url']){
				$imgurl=trim($res['img_url'],'[""]');
	            $imgurl=explode(',',$imgurl);
	            foreach($imgurl as $key=>$value){
	            	$imgurl[$key]=trim($value,'""');
	            }
            }
			$this->fu();
			$this->assign('imgurl',$imgurl);
			$this->assign('now_category',$res);
			$this->display();
		}else{
			$this->frame_error_tips('查看失败');
		}
	}else{
		$this->frame_error_tips('参数错误');
	}
}
//查看评论
function showcomment(){
	if(I('dyid')){
        $where=array('dynamic'=>I('dyid'));
        $count=M()->table('pigcms_circle_cateory ca, pigcms_circle_dynamic dy,pigcms_circle_comment co,pigcms_circle_user us')->where('co.uid = us.id and co.circle_id=ca.id and co.dynamic_id=dy.id and co.dynamic_id='.I('dyid'))->field('co.status as status,co.id as id,co.content as content,us.nickName as name,ca.name as quanzi,dy.title as title,co.add_time as add_time')->count();
        import('@.ORG.system_page');
		$p = new Page($count,30);
        $res=M()->table('pigcms_circle_cateory ca, pigcms_circle_dynamic dy,pigcms_circle_comment co,pigcms_circle_user us')->where('co.uid = us.id and co.circle_id=ca.id and co.dynamic_id=dy.id and co.dynamic_id='.I('dyid'))->field('co.status as status,co.id as id,co.content as content,us.nickName as name,ca.name as quanzi,dy.title as title,co.add_time as add_time')->order('co.id asc' )->limit($p->firstRow.','.$p->listRows)->select();
        $pagebar = $p->show();
	    $this->assign('pagebar',$pagebar);
	    $this->assign('res',$res);
	    $this->display();
	}else{
		$this->frame_error_tips('参数错误');
	}
}
	
//编辑评论
function editcomment(){
	if($_GET['id']){
		$where=array('id'=>$_GET['id']);
		$res=M('circle_comment')->where($where)->find();
		$this->assign('res',$res);
        $this->display();
	}else{
		$this->frame_error_tips('参数错误');
	}
}
function doeditcomment(){
	if($_POST['id']){
		$where=array('id'=>$_POST['id']);
		$data['content']=htmlspecialchars_decode($_POST['content']);
		$data['status'] = $_POST['status'];
		$res=M('circle_comment')->where($where)->save($data);
		if($res){
			$this->frame_submit_tips(1,'编辑成功');
		}else{
			$this->frame_submit_tips(0,'编辑失败');
		}
	}
}
//删除评论
function delcomment(){
	if(I('param.id')){
		$where=array('id'=>I('param.id'));
        $res=M('circle_comment')->where($where)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}else{
       $this->error('没有找到对应信息');
	}
}
//添加评论
function addcomment(){

}
//我的圈子
	function mycircle($uid){
	    if(IS_GET){
	    	$count = D()->table('pigcms_circle ca, pigcms_circle_relation re')->where('ca.id=re.circle_id  and re.uid='.I('uid'))->count();
		import('@.ORG.system_page');
		$p = new Page($count,20);
	    $res=M()->table('pigcms_circle ca, pigcms_circle_relation re')->where('ca.id=re.circle_id and re.uid='.I('uid'))->field('re.id as id,re.status as status,ca.name as name,ca.cate_id as cate_id')->order('re.id asc' )->limit($p->firstRow.','.$p->listRows)->select();
	    $this->fu();
	    $pagebar = $p->show();
	    $this->assign('pagebar',$pagebar);
	    $this->assign('res',$res);
		$this->display();
	    }
	}
	//修改圈子状态
	function modifyRelation(){
		if(IS_POST){
        $where['id']=I('post.id');
        $data['status']=I('post.status');
        $res=M('circle_relation')->where($where)->save($data);
	        if($res){
	        	$this->success('修改成功');
	        }else{
	        	$this->error('修改失败');
	        }
		}else{
			$where['id']=I('id');
			$res=M('circle_relation')->where($where)->find();
			$this->assign('res',$res);
			$this->display();
		}
	}
	//获取圈子列表
function quanzi(){
	
	$quanzi=M('circle')->order('id asc')->select();
	$this->assign('quanzi',$quanzi);
}
	//获取分类列表
function fu(){
	$where['fid']=0;
	$fu=M('circle_cateory')->where($where)->order('id asc')->select();
	$this->assign('fu',$fu);
}
// 上传图片
function ajax_upload_pic(){
        if ($_FILES['imgFile']['error'] != 4) {
            $upload_dir = './upload/Circle/'.date('Ymd').'/'.date('H').'/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = $this->config['group_pic_size'] * 1024 * 1024;
            $upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
            $upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif','application/octet-stream');
            $upload->savePath = $upload_dir;
            $upload->saveRule = 'uniqid';
            if ($upload->upload()) {
                $uploadList = $upload->getUploadFileInfo();
                $title = $uploadList[0]['savename'];
                $url = $upload_dir.$title;
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            } else {
                exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
            }
        } else {
            exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
        }
    }

}