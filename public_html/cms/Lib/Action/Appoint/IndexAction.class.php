<?php
/*
 * 预约首页
 *
 */
class IndexAction extends BaseAction{
    public function index(){
		if($this->config['appoint_site_url'] && stripos($this->config['appoint_site_url'],$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']) === false){
			redirect($this->config['appoint_site_url']);
		}
                $database_appoint_news = D('Appoint_news');
                $database_appoint_category = D('Appoint_category');
                $database_system_coupon = D('System_coupon');
                $database_reply = D('Reply');
		//所有分类 包含2级分类
		$all_category_list = $database_appoint_category->get_category();
		$this->assign('all_category_list',$all_category_list);
		// dump($all_category_list);
		
                //热门品类start
                $where['cat_status'] = 1;
                $where['cat_fid'] = array('neq',0);
                $where['is_hot'] = 1;
                $s_appoint_category_list = $database_appoint_category->where($where)->field('cat_id,cat_name,cat_pic,cat_url')->select();
                $this->assign('s_appoint_category_list',$s_appoint_category_list);
                //热门品类end
                
                //新闻大事记start
                $where['status'] = 1;
                $news_list = $database_appoint_news->appoint_news_page_list($where,true,'sort desc,id desc',5);
                $this->assign('news_list',$news_list['list']);
                //新闻大事记end
                
                //评论start
                $reply_list = $database_reply->get_appoint_reply_list('high','add_time');
		$this->assign('reply_list', $reply_list);
                //评论end
                
		$this->display();
    }
    public function article(){
                $id = $_GET['id'] + 0;
                if(!$id){
                    $this->error('传递参数有误！');
                }
                
                $database_appoint_news = D('Appoint_news');
                $where['id'] = $id;
                $detail = $database_appoint_news->appoint_news_detail($where);
                if(!$detail){
                    $this->error('数组处理有误！');
                }else{
                    if($detail['status']){
                        $this->assign('detail',$detail['detail']);
                    }else{
                        $this->error('文章不存在！');
                    }
                }
		$this->display();
	}
    public function category(){
		//所有分类 包含2级分类
		$all_category_list = D('Appoint_category')->get_category();
		$this->assign('all_category_list',$all_category_list);

		$this->display();
	}
        
        
    public function category_list(){
            $cat_url = $_GET['cat_url'];
            $area_url = $_GET['area_url'];
            $cat_url = !empty($cat_url) ? $cat_url : 'all';
            $area_url = !empty($area_url) ? $area_url : 'all';
            $database_appoint_category = D('Appoint_category');
            $database_area = D('Area');
            $database_appoint = D('Appoint');

            $cat_info = $database_appoint_category->get_category_by_catUrl($cat_url);
            
            if(!$cat_info['is_autotrophic']){
                if($area_url != 'all'){
                    $tmp_area = $database_area->get_area_by_areaUrl($area_url,$cat_url,'appoint');
			if(empty($tmp_area)){
				$this->error('当前区域不存在！');
			}
			if($tmp_area['area_type'] == 3){
				$now_area = $tmp_area;
			}else{
				$now_circle = $tmp_area;
				$this->assign('now_circle',$now_circle);
				$now_area = $database_area->get_area_by_areaId($tmp_area['area_pid'],true,$cat_url,'appoint');
				if(empty($tmp_area)){
					$this->error('当前区域不存在！');
				}
				$circle_url = $now_circle['area_url'];
				$area_url = $now_area['area_url'];
			}
			$area_id = $now_area['area_id'];
			$circle_list = $database_area->get_arealist_by_areaPid($now_area['area_id'],true,$cat_url,'appoint');
			if($now_circle && $circle_list){
				foreach($circle_list as &$value){
					if($value['area_id'] == $now_circle['area_id']){
						$vlaue['is_hover'] = true;
					}
				}
			}
                        
			$this->assign('now_area',$now_area);
			$this->assign('circle_list',$circle_list);
                }else{
                    $area_id = 0;
                }
                
                
                if($cat_url != 'all'){
                    $now_category = $database_appoint_category->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->error('此分类不存在！');
			}
			$this->assign('now_category',$now_category);
			if(!empty($now_category['cat_fid'])){
				$f_category = $database_appoint_category->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
				$category_cat_field = $f_category['cat_field'];
				$top_category = $f_category;
				$this->assign('top_category',$f_category);
				$get_grouplist_catfid = 0;
				$get_grouplist_catid = $now_category['cat_id'];
			}else{
				$all_category_url = $now_category['cat_url'];
				$category_cat_field = $now_category['cat_field'];
				$top_category = $now_category;
				$this->assign('top_category',$now_category);
				
				$get_grouplist_catfid = $now_category['cat_id'];
				$get_grouplist_catid = 0;
			}
                }
                
                $area_list = $database_area->get_area_list('',$now_category['cat_url'],'appoint');
                $this->assign('area_list',$area_list);
                
                $group_category_all = C('config.site_url').'/appoint/category/'.$now_category['cat_url'].'/all/all';
		$this->assign('group_category_all',$group_category_all);
        $result = $database_appoint->new_get_appoint_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$cat_url,$area_id,$now_circle['area_id'],$order,$attrs,$category_cat_field);

		//得到分类下的团购列表
		$this->assign($result);
		//$cat_option_html = $this->get_cat_option_html($cat_option_list,$cat_url,$area_url,$circle_url,$order,$attrs);
		//$this->assign('cat_option_html',$cat_option_html);
		//$cat_sort_url = $this->get_cat_sort_url($cat_url,$area_url,$attrs);
		//$this->assign($cat_sort_url);
                $this->display('category_list_merchant');//商家自营页面
            }else{
                $f_cat_info = $database_appoint_category->get_category_by_id($cat_info['cat_fid']);
                $cat_info['pc_title'] = unserialize($cat_info['pc_title']);
                $pc_content_arr = unserialize($cat_info['pc_content']);
                $cat_info['pc_content'] = $pc_content_arr;
                $this->assign('cat_info',$cat_info);
                $this->assign('f_cat_info',$f_cat_info);

                if(!$cat_info['is_pc_order']){
                    $this->display();	//PC不能下单的页面
                }else{
                    if(IS_POST){
                        $database_appoint_order = D('Appoint_order');
                        $data['cue_field'] = serialize($_POST['custom_field']);
                        $data['type'] = 1;
                        $data['cat_id'] = $cat_info['cat_id'];
                        $data['cat_fid'] = $cat_info['cat_fid'];
                        if(!$this->user_session){
                            exit(json_encode(array('status'=>0,'msg'=>'请先进行登录！','url'=> $this->config['config_site_url'].U('Index/Login/index',array('referer'=>urlencode('http://'.$_SERVER['HTTP_HOST'].(!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'])))))));
                        }
                        
                        $data['uid'] = $this->user_session['uid'];
                        $result = $database_appoint_order->platform_save_post_form($data);
                        exit(json_encode($result));
                    }else{
                         // 自定义表单项
                        $category = $database_appoint_category->get_category_by_id($cat_info['cat_id']);
                        if(empty($category['cue_field'])){
                            $category = $database_appoint_category->get_category_by_id($category['cat_fid']);
                        }
                        if($category){
                                $cuefield = unserialize($category['cue_field']);
                                foreach ($cuefield as $val){
                                        $sort[] = $val['sort'];
                                }
                                array_multisort($sort, SORT_DESC, $cuefield);
                        }

                        $this->assign('formData',$cuefield);
                    }
                    $this->display('category_list_canpay');//PC可以下单的页面，第三方入驻也用此模板，右侧仅显示电话咨询
                }
            }
		// $this->display();	//PC不能下单的页面
		// $this->display('category_list_canpay');	//PC可以下单的页面		 第三方入驻也用此模板，右侧仅显示电话咨询
		//$this->display('category_list_merchant');	//商家自营页面
	}
        
        
    public function group_order(){
        $group_id = $_GET['group_id'] + 0;
        if(!$group_id){
            $this->error('传递参数有误！');
        }

       $database_group = D('Group');
       $database_appoint_order = D('Appoint_order');
       $now_group = $database_group->get_group_by_groupId($group_id);
       if(!$now_group){
           $this->error('该团购不存在！');
       }
       $result = $database_appoint_order->save_post_form($now_group,$this->user_session['uid'],0);
       
       if($result['error'] == 1){
            $this->error_tips($result['msg']);
       }
       if(intval($now_group['payment_status']) == 1){
                $href = U('Pay/check',array('order_id'=>$result['order_id'],'type'=>'appoint'));
        }else{
                $resultOrder = D('Appoint_order')->no_pay_after($result['order_id'],$now_group);
                if($resultOrder['error'] == 1){
                        $this->error_tips($resultOrder['msg']);
                }
                $href = U('My/appoint_order',array('order_id'=>$result['order_id']));
        }
        $this->success($href);
    }

}