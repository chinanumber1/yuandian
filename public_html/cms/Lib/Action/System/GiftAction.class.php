<?php
class GiftAction extends BaseAction{
    public function category_list(){
        $database_gift_category = D('Gift_category');
        $cat_fid = $_GET['cat_fid'] + 0;
        
        $where['is_del'] = 0;
        if(!$cat_fid){
            $where['cat_fid'] = 0;
        }else{
            $where['cat_fid'] = $Map['cat_id'] = $cat_fid;
            
            $now_category = $database_gift_category->gift_category_detail($Map);
            if(empty($now_category['detail'])){
                    $this->error_tips('没有找到该分类信息！', 3, U('category_list'));
            }elseif(empty($now_category['cat_status'])){

            }
            $this->assign('now_category', $now_category['detail']);
        }
        
        $category_list = $database_gift_category->gift_category_page_list($where);
        $this->assign('category_list',$category_list['list']);
        $this->display();
    }
    
    public function category_add(){
        if(IS_POST){
            $database_gift_category = D('Gift_category');
            $database_image = D('Image');
            
            $image = $database_image->handle($this->system_session['id'], 'system', 0, array('size' => 10));
            if (!$image['error']) {
                    $_POST = array_merge($_POST, str_replace('/upload/system/', '', $image['url']));
            }

            $result = $database_gift_category->gift_category_add($_POST);
            
            if(!$result){
                $this->frame_submit_tips(0, '数据处理有误！');
            }else{
                if($result['status']){
                    $this->frame_submit_tips(1,$result['msg']);
                }else{
                    $this->frame_submit_tips(0,$result['msg']);
                }
            }
        }else{
            $this->assign('bg_color','#F3F3F3');
            $this->display(); 
        }
    }
    
    public function category_edit(){
        $cat_id = $_GET['cat_id'] + 0;
        if(!$cat_id){
            $this->error('传递参数有误！');
        }
        $where['cat_id'] = $cat_id;
        $database_gift_category = D('Gift_category');
        
        if(IS_POST){
            $database_image = D('Image');
            $image = $database_image->handle($this->system_session['id'], 'system', 0, array('size' => 10));
            if (!$image['error']) {
                $_POST = array_merge($_POST, str_replace('/upload/system/', '', $image['url']));
            }
            $result = $database_gift_category->gift_category_edit($where,$_POST);
            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->frame_submit_tips(1,$result['msg']);
                }else{
                    $this->frame_submit_tips(0,$result['msg']);
                }
            }
        }else{
            $now_category = $database_gift_category->gift_category_detail($where);
            if(!$now_category){
                $this->error('数据处理有误！');
            }

            $this->assign('now_category',$now_category['detail']);
            $this->display();
        }
        
    }
    
    public function category_del(){
        if(IS_POST){
            $cat_id = $this->_post('cat_id');
            if(!$cat_id){
                $this->error(0, '传递参数有误！');
            }

            $database_gift_category = D('Gift_category');
            $where['cat_id'] = $cat_id;
            $result = $database_gift_category->gift_category_del($where);
            if(!$result){
                $this->error(0, '数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $this->error('非法提交,请重新提交~');
	}
        
    }
    
    
    public function index(){
        if(!empty($_GET['keyword'])){
            switch($_GET['searchtype']){
                case 'gift_id':
                    $where['gift_id'] = $_GET['keyword'] + 0;
                    break;
                case 'gift_name':
                    $where['gift_name'] = array('like','%'.$_GET['keyword'].'%');
                    break;
            }
        }

        if(!empty($_GET['searchstatus'])){
            switch($_GET['searchstatus']){
                case '1':
                    $where['status'] = 1;
                    break;
                case '2':
                    $where['status'] = 0;
                    break;
            }
        }

		$database_gift = D('Gift');
        $database_gift_category = D('Gift_category');
		$where['is_del'] = 0;
        $list = $database_gift->gift_page_list($where,true,'sort desc');
        $cat_list = $database_gift_category->where($where)->getField('cat_id,cat_name');

        $this->assign('cat_list',$cat_list);
		$this->assign('list',$list['list']);
        $this->display();
    }

    public function order(){
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $status = isset($_GET['status']) ? intval($_GET['status']) : 0;
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'order_time') $type = '';
        $order_sort = '';
        if ($type && $sort) {
            $order_sort .= $type . ' ' . $sort . ',';
            $order_sort .= 'order_id DESC';
        } else {
            $order_sort .= 'order_time DESC';
        }

        $condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`gift_id`=`g`.`gift_id`";

        if ($status != 0) {
            $condition_where .= 'AND `o`.`status`='.($status - 1);
        }

        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'order_id') {
                $condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($_GET['keyword'])."'";
            } elseif ($_GET['searchtype'] == 'order_name') {
                $condition_where .= " AND `o`.`order_name` like '%" . htmlspecialchars($_GET['keyword'])."%'";
            }
        }
        $condition_table = array(C('DB_PREFIX').'gift'=>'g', C('DB_PREFIX').'gift_order'=>'o', C('DB_PREFIX').'user'=>'u');

        $order_count = D('')->where($condition_where)->table($condition_table)->count();
        import('@.ORG.system_page');
        $p = new Page($order_count,20);

        $order_list = D('')->field('`o`.`phone` AS `gift_phone`,`o`.*,`g`.`gift_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`,`g`.`gift_id`')->where($condition_where)->table($condition_table)->order($order_sort)->limit($p->firstRow.','.$p->listRows)->select();

        if(empty($order_list)){
            //$this->error_tips('当前'.$this->config['gift_alias_name'].'并未产生订单！');
        }
        $this->assign('order_list',$order_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);

        $this->assign(array('type' => $type, 'sort' => $sort,'status'=>$status));
        $this->display();
    }
    
    public function gift_add(){
        if(IS_POST){
            $database_gift = D('Gift');
            $result = $database_gift->gift_add($_POST);
            
            if(!$result){
                $this->error('数据处理有有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $f_category_list = $this->_get_giftCategory(0);
            $this->assign('f_category_list',$f_category_list);
            $this->display();
        }
    }
	
	public function gift_del(){
		
		if(IS_POST){
			$gift_id = $this->_post('gift_id');
			if(!$gift_id){
				$this->error('传递参数有误！');
			}

			$database_gift = D('Gift');
			$where['gift_id'] = $gift_id;
			$result = $database_gift->gift_del($where);

			if(!$result){
				$this->error('数据处理有误！');
			}else{
				if($result['status']){
					$this->success($result['msg']);
				}else{
					$this->error($result['msg']);
				}
			}
		}else{
			$this->error('访问页面有误！');
		}
	}
	
	
	public function gift_edit(){
		$gift_id = $_GET['gift_id'] + 0;
		$database_gift = D('Gift');
		$where['gift_id'] = $gift_id;
		if(IS_POST){
			$result = $database_gift->gift_edit($where,$_POST);
			
			if(!$result){
			}else{
				if($result['status']){
					$this->success($result['msg']);
				}else{
					$this->error($result['msg']);
				}
			}
		}else{
			$detail = $database_gift->gift_detail($where);
            if(!$detail){
				$this->error('数据处理有误！');
			}else{
				$this->assign('detail',$detail['detail']);
				$f_category_list = $this->_get_giftCategory(0);
				$this->assign('f_category_list',$f_category_list);
				$s_category_list = $this->_get_giftCategory($detail['detail']['cat_fid']);
				$this->assign('s_category_list',$s_category_list);
				$this->display();
			}
		}
		
	}
    
    public function ajax_category(){
        if(IS_POST){
            $cat_fid = $_POST['cat_fid'] + 0;
            $cat_list = $this->_get_giftCategory($cat_fid);
            if($cat_list){
                exit(json_encode(array('status'=>1,'cat_list'=>$cat_list)));
            }else{
                exit(json_encode(array('status'=>0,'cat_list'=>$cat_list)));
            }
        }else{
            $this->error('访问页面有误！~~~');
        }
    }
	
	public function ajax_upload_pic(){
		if ($_FILES['imgFile']['error'] != 4) {
			$img_mer_id = sprintf("%09d", $this->system_session['id']);
			$rand_num = mt_rand(10, 99) . '/' . substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

			$upload_dir = './upload/system/gift/' . $rand_num . '/';
			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = $this->config['gift_pic_size'] * 1024 * 1024;
			$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
			$upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
            $upload->thumb = true;
            $upload->imageClassPath = 'ORG.Util.Image';
            $upload->thumbPrefix = 'm_,s_';
            $upload->thumbMaxWidth = 90;
            $upload->thumbMaxHeight = 90;
            $upload->thumbRemoveOrigin = false;
			$upload->savePath = $upload_dir;
			$upload->saveRule = 'uniqid';
			if ($upload->upload()) {
				$uploadList = $upload->getUploadFileInfo();
				$gift_image_class = new gift_image();
				$title = $rand_num . ',' . $uploadList[0]['savename'];
				$url = $gift_image_class->get_image_by_path($title);
				exit(json_encode(array('error' => 0, 'url' => $url['image'], 'title' => $title)));
			} else {
				exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
			}
			} else {
			exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
		}
    }

    public function order_detail(){
        $this->assign('bg_color','#F3F3F3');

        $order_id =  $_GET['order_id'] + 0;
        $database_gift_order = D('Gift_order');
        $condition_gift_order['order_id'] = $order_id;
        $now_order = $database_gift_order->get_order_detail_by_id_and_merId($order_id , $order_id ,false);

        $express_list = D('Express')->get_express_list();
        $this->assign('express_list',$express_list);
        $this->assign('now_order',$now_order);
        $this->display();
    }

    public function gift_express(){
        if(IS_AJAX){
            $database_gift_order = D('Gift_order');
            $now_order = $database_gift_order->get_order_detail_by_id_and_merId(intval($_GET['order_id']));

            if(empty($now_order)){
                $this->error('此订单不存在！');
            }
            if(empty($now_order['paid'])){
                $this->error('此订单尚未支付！');
            }

            $condition_gift_order['order_id'] = $now_order['order_id'];
            $data_gift_order['express_type'] = $_POST['express_type'];
            $data_gift_order['express_id'] = $_POST['express_id'];
            $data_gift_order['status'] = 1;

            $insert_id = $database_gift_order->where($condition_gift_order)->data($data_gift_order)->save();

            if($insert_id){
                $database_user = D('User');
                $userInfo = $database_user->get_user($now_order['uid']);
                //发送微信模板消息start
                if($userInfo['openid']){
                    $href = $this->config['site_url'] . '/wap.php?g=Wap&c=My&a=integral';
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));

                    $express_info = D('Express')->get_express($_POST['express_type']);
                    $express_info ='\n快递号：'.$_POST['express_id'].'的'.$express_info['name'].'已寄出，请及时查收。';
                    $model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' =>  $this->config['gift_alias_name'].'兑换信息', 'work' => $express_info, 'remark' => '\n请点击查看详细信息！'));
                }

                $this->success('修改成功！');
            }else{
                $this->error('修改失败！请重试。');
            }
        }else{
            $this->error_tips('访问页面有误！~~~');
        }
    }


    public function ajax_del_pic(){
        $group_image_class =  new gift_image();
        $group_image_class->del_image_by_path($_POST['path']);
    }


    
    private function _get_giftCategory($fid){
        $database_gift_category = D('Gift_category');
        $where['is_del'] = 0;
        //$where['cat_status'] = 1;
        $where['cat_fid'] = $fid;
        
        $category_list = $database_gift_category->where($where)->getField('cat_id,cat_name');
        return $category_list;
    }
}

