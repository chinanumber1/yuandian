<?php
/*
 * 黄页申请
 *
 * @  Writers    Appler
 * @  BuildTime  2017/02/18 09:51
 * 
 */
class YellowAction extends BaseAction{
    public function index(){
		//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		
		$this->assign('all_category_list',$all_category_list);
		
		//省市区
		$province_list = D('Area')->get_arealist_by_areaPid(0);
		$this->assign('province_list',$province_list);
			
		$city_list = D('Area')->get_arealist_by_areaPid($province_list[0]['area_id']);
		$this->assign('city_list',$city_list);
			
		$area_list = D('Area')->get_arealist_by_areaPid($city_list[0]['area_id']);
		$this->assign('area_list',$area_list);

		// 申请信息
		$apply_info = D('Portal_yellow')->where(array('uid'=>$_SESSION['user']['uid']))->find();
		$this->assign('apply_info',$apply_info);
		
		$this->display();
    }
    
    // 获取分类
    public function ajax_get_categroy_list(){
		$all_category_list = D('Group_category')->get_category();

		$pid = (int)$_GET['pid'];
		$data_list = array();
		if($pid == 0){
			foreach($all_category_list as $item){
				if($item['cat_fid'] == 0){
					$data_list[] = array('cat_id'=>$item['cat_id'],'cat_name'=>$item['cat_name']);
				}
			}
		}else{
			foreach($all_category_list as $item){
				if($item['cat_id'] != $pid){
					continue;
				}
				foreach($item['category_list'] as $child){
					$data_list[] = array('cat_id'=>$child['cat_id'],'cat_name'=>$child['cat_name']);
				}
			}
		}
		
		exit(json_encode(array('code'=>0,'data'=>$data_list)));
    }

    // 加载百度地图
    public function baidu_map(){
    	$this->display();
    }

    // 图片上传
    public function uplad_img(){
    	if($_FILES['file_img']['error'] != 4){
			$image = D('Image')->handle($_SESSION['user']['uid'], 'portal', 0, array('size' => 3), false);
			if (!$image['error']){
				if((int)$_GET['flag'] == 1){
					exit('<script type="text/javascript">parent.upload_logo_success("'.$image['url']['file_img'].'")</script>');
				}else{
					exit('<script type="text/javascript">parent.upload_success("'.$image['url']['file_img'].'")</script>');
				}
			}
		}else{
			exit('<script type="text/javascript">parent.upload_error("没有选择图片");</script>');
		}
    }

    // 富文本图片上传
    public function ajax_upload_pic(){
        if($_FILES['imgFile']['error'] == 4){
            exit(json_encode(array('error'=>1,'message'=>'没有选择图片')));
        }

        $upload_file = D('Image')->handle($this->system_session['uid'], 'upload_image', 0, array('size' => 5), false);
        if ($upload_file['error']){
            exit(json_encode(array('error'=>1,'message'=>$upload_file['message'])));
        }

        exit(json_encode(array('error' => 0, 'url' => $upload_file['url']['imgFile'], 'title' => '图片')));
    }

    // 自己定义信息
    public function add_custom_info(){
        $yellow_id = (int)$_GET['yellow_id'];
        $info_id = (int)$_GET['info_id'];
        $custom_info = D('Portal_yellow_detail')->where(array('yellow_id'=>$yellow_id))->find();
        $this->assign('custom_info',$custom_info);
        $this->assign('yellow_id',$yellow_id);
        $this->display();
    }

    // 保存申请信息
    public function save_apply(){
    	$data['uid'] = (int)$_SESSION['user']['uid'];
    	$data['title'] = trim($_POST['title']);
    	$data['tel'] = trim($_POST['tel']);
    	$data['email'] = trim($_POST['email']);
    	$data['address'] = trim($_POST['address']);
    	$data['pid'] = (int)$_POST['parent_cate'];
    	$data['cid'] = (int)$_POST['child_cate'];
    	$data['province'] = (int)$_POST['address_province'];
    	$data['city'] = (int)$_POST['address_city'];
    	$data['area'] = (int)$_POST['address_area'];
    	$data['lng'] = $_POST['lng'];
    	$data['lat'] = $_POST['lat'];
    	$data['logo'] = $_POST['logo'];
    	$data['qrcode'] = $_POST['qrcode'];
    	$data['service'] = trim($_POST['service']);
        $data['dateline'] = time();

    	if($data['title'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'公司名称缺失')));
    	}
    	if($data['tel'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'联系电话缺失')));
    	}
    	if($data['address'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'联系地址缺失')));
    	}
    	if($data['pid']<=0 || $data['cid'] <= 0){
    		exit(json_encode(array('code'=>1,'msg'=>'业务类型缺失')));
    	}
    	if($data['province']<=0 || $data['city']<=0 || $data['area']<=0){
    		exit(json_encode(array('code'=>1,'msg'=>'所在区域缺失')));
    	}
    	if($data['lng']==''|| $data['lat'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'经纬度缺失')));
    	}
    	if($data['logo'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'请上传公司logo')));
    	}
    	/*
    	if($data['qrcode'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'没有上传二维码')));
    	}
    	*/
    	if($data['service'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'服务内容缺失')));
    	}

    	$id = (int)$_POST['id'];
    	if($id){
    		$res = D('Portal_yellow')->where(array('id'=>$id))->data($data)->save();
    	}else{
    		$res = D('Portal_yellow')->data($data)->add();
    	}
    	
    	if(!$res){
    		exit(json_encode(array('code'=>1,'msg'=>'保存失败')));
    	}
    	exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }

    // 保存申请详细信息
    public function save_custom_info(){
        $info_id = (int)$_POST['info_id'];
        $data['yellow_id'] = (int)$_POST['yellow_id'];
        $data['title1'] = trim($_POST['title1']);
        $data['msg1'] = trim($_POST['msg1']);
        $data['title2'] = trim($_POST['title2']);
        $data['msg2'] = trim($_POST['msg2']);
        $data['title3'] = trim($_POST['title3']);
        $data['msg3'] = trim($_POST['msg3']);
        $data['title4'] = trim($_POST['title4']);
        $data['msg4'] = trim($_POST['msg4']);
        $data['title5'] = trim($_POST['title5']);
        $data['msg5'] = trim($_POST['msg5']);
        if($data['title1'] == '' && $data['msg1'] == '' && $data['title2'] == '' && $data['msg2'] == '' && $data['title3'] == '' && $data['msg3'] == '' && $data['title4'] == '' && $data['msg4'] == '' && $data['title5'] == '' && $data['msg5'] == ''){
            exit(json_encode(array('code'=>1,'msg'=>'详细信息不能为空')));
        }

        if($info_id){
            $res = D('Portal_yellow_detail')->where(array('id'=>$info_id))->data($data)->save();
        }else{
            $res = D('Portal_yellow_detail')->data($data)->add();
        }

        if(!$res){
            exit(json_encode(array('code'=>1,'msg'=>'保存失败')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));

    }
 
}