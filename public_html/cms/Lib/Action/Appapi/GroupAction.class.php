<?php
class GroupAction extends BaseAction{
    public function index(){
        //判断分类信息
        $cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
        //判断地区信息
        $area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
        $circle_id = 0;
        if(!empty($area_url)){
            $tmp_area = D('Area')->get_area_by_areaUrl($area_url);
            if(empty($tmp_area)){
                $this->returnCode('20045008');
            }

            if ($tmp_area['area_type'] == 3) {
                $now_area = $tmp_area;
            } else {
                $now_circle = $tmp_area;
                $now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
                if (empty($tmp_area)) {
                    $this->returnCode('20045008');
                }
                $circle_url = $now_circle['area_url'];
                $circle_id = $now_circle['area_id'];
                $area_url = $now_area['area_url'];
            }
            $area_id = $now_area['area_id'];
        }else{
            $area_id = 0;
        }
		if($this->config['open_group_default_sort']){
			$sort_id = 'defaults';
		}
        //判断排序信息
        $sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';
        $long_lat   =   $this->user_long_lat;
        if(empty($long_lat)){
            $sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
            $sort_array = array(
				array('sort_id'=>'defaults','sort_value'=>'智能排序'),
                array('sort_id'=>'defaults','sort_value'=>'默认排序'),
                array('sort_id'=>'rating','sort_value'=>'评价最高'),
                array('sort_id'=>'start','sort_value'=>'最新发布'),
                array('sort_id'=>'solds','sort_value'=>'人气最高'),
                array('sort_id'=>'price','sort_value'=>'价格最低'),
                array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
            );
        } else {
			if(!$this->config['open_group_default_sort']){
				$sort_array = array(
						array('sort_id'=>'juli','sort_value'=>'离我最近'),
						array('sort_id'=>'defaults','sort_value'=>'智能排序'),
						array('sort_id'=>'rating','sort_value'=>'评价最高'),
						array('sort_id'=>'start','sort_value'=>'最新发布'),
						array('sort_id'=>'solds','sort_value'=>'人气最高'),
						array('sort_id'=>'price','sort_value'=>'价格最低'),
						array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
				);
			}else{
				$sort_array = array(
						array('sort_id'=>'defaults','sort_value'=>'智能排序'),
						array('sort_id'=>'juli','sort_value'=>'离我最近'),
						array('sort_id'=>'rating','sort_value'=>'评价最高'),
						array('sort_id'=>'start','sort_value'=>'最新发布'),
						array('sort_id'=>'solds','sort_value'=>'人气最高'),
						array('sort_id'=>'price','sort_value'=>'价格最低'),
						array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
				);
			}
        }
        foreach($sort_array as $key=>$value){
            if($sort_id == $value['sort_id']){
                $now_sort_array = $value;
                break;
            }
        }
        $arr['sort_array'] =   isset($sort_array)?$sort_array:null;

        //所有分类 包含2级分类
        $all_category = D('Group_category')->get_all_category();
        foreach($all_category as $k => $v){
            foreach($v['category_list'] as $kk => $vv){
                $v['category_list_tmp'][] =   $vv;
            }
            unset($v['category_list']);
            $all_category_list[]   =   $v;
        }
        unset($all_category);
        $arr['all_category_list'] =   isset($all_category_list)?$all_category_list:null;

        //根据分类信息获取分类
        if(!empty($cat_url)){
            $now_category = D('Group_category')->get_category_by_catUrl($cat_url);
            if(empty($now_category)){
                $this->returnCode('20045009');
            }

            if(!empty($now_category['cat_fid'])){
                $f_category = D('Group_category')->get_category_by_id($now_category['cat_fid']);
                $all_category_url = $f_category['cat_url'];
                $category_cat_field = $f_category['cat_field'];

                $top_category = $f_category;

                $get_grouplist_catfid = 0;
                $get_grouplist_catid = $now_category['cat_id'];
            }else{
                $all_category_url = $now_category['cat_url'];
                $category_cat_field = $now_category['cat_field'];
                $top_category = $now_category;

                $get_grouplist_catfid = $now_category['cat_id'];
                $get_grouplist_catid = 0;
            }
        }
        $all_area_list_tmp = D('Area')->get_all_area_list();
        foreach($all_area_list_tmp as $v){
            $all_area_list[]  =   $v;
        }
        $arr['all_area_list'] =   isset($all_area_list)?$all_area_list:array();
        $this->returnCode(0,$arr);
    }

    // 列表
    public function ajaxList(){
        //判断分类信息
        $cat_url = I('cat_url','');
        $_GET['page']   =   I('page');
        //判断地区信息
        $area_url = I('area_url','');

        $circle_id = 0;
        if(!empty($area_url)){
            $tmp_area = D('Area')->get_area_by_areaUrl($area_url);
            if(empty($tmp_area)){
                $this->returnCode('20045008');
            }

            if ($tmp_area['area_type'] == 3) {
                $now_area = $tmp_area;
            } else {
                $now_circle = $tmp_area;
                $now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
                if (empty($tmp_area)) {
                    $this->returnCode('20045008');
                }
                $circle_url = $now_circle['area_url'];
                $circle_id = $now_circle['area_id'];
                $area_url = $now_area['area_url'];
            }
            $area_id = $now_area['area_id'];
        }else{
            $area_id = 0;
        }

        //判断排序信息
        $sort_id = I('sort_id','juli');
        $long_lat   =   $this->user_long_lat;
        if(empty($long_lat)){
            $sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
        }

        //所有分类 包含2级分类
        $all_category_list = D('Group_category')->get_all_category();

        //根据分类信息获取分类
        if(!empty($cat_url)){
            $now_category = D('Group_category')->get_category_by_catUrl($cat_url);
            if(empty($now_category)){
                $this->returnCode('20045009');
            }

            if(!empty($now_category['cat_fid'])){
                $f_category = D('Group_category')->get_category_by_id($now_category['cat_fid']);
                $all_category_url = $f_category['cat_url'];
                $category_cat_field = $f_category['cat_field'];

                $top_category = $f_category;

                $get_grouplist_catfid = 0;
                $get_grouplist_catid = $now_category['cat_id'];
            }else{
                $all_category_url = $now_category['cat_url'];
                $category_cat_field = $now_category['cat_field'];
                $top_category = $now_category;

                $get_grouplist_catfid = $now_category['cat_id'];
                $get_grouplist_catid = 0;
            }
        }
        C('config.group_page_row',10);
        if($sort_id == 'juli'){
            $return = D('Group')->wap_get_storeList_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
            foreach($return['store_list'] as &$storeValue){
                $storeValue['url'] = $this->config['site_url'].str_replace('appapi.php','wap.php',$storeValue['url']);
                $storeValue['range_txt'] = $this->wapFriendRange($storeValue['juli']);
                $group_list = S('wap_store_group_'.($get_grouplist_catfid ? $get_grouplist_catfid : $get_grouplist_catid).'_'.$storeValue['store_id']);
                if(empty($group_list)){
                    $group_list = D('Group')->get_single_store_group_list($storeValue['store_id'],0,true);
					foreach($group_list as $key=>$value){
						$group_list[$key]['sale_count'] = $group_list[$key]['sale_count']+$group_list[$key]['virtual_num'];
						if(($get_grouplist_catid && $value['cat_id'] != $get_grouplist_catid) || ($get_grouplist_catfid && $value['cat_fid'] != $get_grouplist_catfid)){
							unset($group_list[$key]);
						}
					}
					$group_list = array_values($group_list);
                    S('wap_store_group_'.($get_grouplist_catfid ? $get_grouplist_catfid : $get_grouplist_catid).'_'.$storeValue['store_id'],$group_list,360);
                }else{
                    foreach($group_list as &$groupValue){
                        if($groupValue['end_time'] < $_SERVER['REQUEST_TIME']){
                            unset($groupValue);
                        }
                    }
                }
                $storeValue['group_list'] = $group_list;
                $storeValue['group_count'] = count($group_list);
                if(empty($storeValue['group_count'])){
                    unset($storeValue);
                }
                foreach($storeValue as $k => &$v){
                    foreach($v as $kk => &$vv){
                        $vv['url']  =$this->config['site_url'].$vv['url'];
                        $vv['price']    =   rtrim(rtrim(number_format($vv['price'],2,'.',''),'0'),'.');
                        $vv['wx_cheap'] =   rtrim(rtrim(number_format($vv['wx_cheap'],2,'.',''),'0'),'.');
                    }
                }
            }
            if(!$return['store_list']){
                $return['store_list']   =   array();
            }
            $return['style'] = 'store';
        }else{
            $return = D('Group')->wap_get_group_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
            $return['style'] = 'group';
            foreach($return['group_list'] as $k => $v){
                $return['group_list'][$k]['url']   =   $this->config['site_url'].$v['url'];
                $return['group_list'][$k]['price'] =   rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.');
                $return['group_list'][$k]['wx_cheap'] =   rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.');
            }
        }
        if(!$return['group_list']){
            $return['group_list']   =   array();
        }
        if(!$return['store_list']){
            $return['store_list']   =   array();
        }

        $this->returnCode(0,$return);
    }
    //	团购详情
    public function detail(){
    	$group_id	=	I('group_id');
		$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');

		if(empty($now_group)){
			$this->returnCode('20046010');
		}

		if($now_group['cue']){
			$now_group['cue_arr'] = unserialize($now_group['cue']);
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		//判断是否微信浏览器，
		$long	=	I('long');
		$lat	=	I('lat');
		if($long && $lat){
			$rangeSort = array();
			foreach($now_group['store_list'] as &$storeValue){
				$storeValue['Srange'] = getDistance($lat,$long,$storeValue['lat'],$storeValue['long']);
				$storeValue['range'] = getRange($storeValue['Srange'],false);
				$rangeSort[] = $storeValue['Srange'];
			}
			array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
		}
		if($now_group['packageid']>0){
			$packages=M('Group_packages')->where(array('id' => $now_group['packageid'], 'mer_id' => $now_group['mer_id']))->find();
			if(!empty($packages['groupidtext'])){
				$mpackages = unserialize($packages['groupidtext']);
				$packagesgroupid = $this->check_group_status(array_keys($mpackages));
				if(is_array($packagesgroupid)){
					foreach($packagesgroupid as $gvv){
						$tmp_mpackages[]	=	array(
							'key'	=>	$gvv['group_id'],
							'value'	=>	$mpackages[$gvv['group_id']],
						);
					}
					$mpackages=$tmp_mpackages;
					unset($tmp_mpackages);
				}
			}else{
				$mpackages = false;
			}
		}
		$arr['mpackages']	=	isset($mpackages)?$mpackages:array();
		//	积分是否存在
		if(!empty($this->_uid)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_detail';
			$condition_user_collect['id'] = $now_group['group_id'];
			$condition_user_collect['uid'] = $this->_uid;
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_group['is_collect'] = true;
			}
			//判断积分抵现
			$user_coupon_use = D('User')->check_score_can_use($this->_uid,$now_group['price'],'group',$now_group['group_id'],$now_group['group_id'],$now_group['mer_id']);
		}
//		if($now_group['pin_num']>0){
//			$now_group['price'] = $now_group['old_price'];
//		}
		foreach ($now_group['all_pic'] as $vv) {
			$images[] = $vv['m_image'];
		}
		if($now_group['begin_time']+864000>time()&&$now_group['sale_count']==0){
			$now_group['sale_txt'] = '新品上架';
		}elseif($now_group['begin_time']+864000<time()&&$now_group['sale_count']==0){
			$now_group['sale_txt'] = '';
		}else{
			$now_group['sale_txt'] = '已售'.floatval($now_group['sale_count']+$now_group['virtual_num']);
		}
		$arr['now_group']	=	array(
			'score'			=>	$user_coupon_use['score'] ? $user_coupon_use['score'] : 0,
			'score_money'	=>	$user_coupon_use['score_money'] ? $user_coupon_use['score_money'] : 0,
			'group_id'		=>	$now_group['group_id'],				//团购ID
			'image'			=>	$now_group['all_pic'][0]['m_image'],	//图片
			'images'			=>	$images,	//图片
			'group_name'	=>	$now_group['group_name'],	//团购名
			'price'			=>	rtrim(rtrim(number_format($now_group['price'],2,'.',''),'0'),'.'),		//现价
			'old_price'		=>	rtrim(rtrim(number_format($now_group['old_price'],2,'.',''),'0'),'.'),	//老价格
			'wx_cheap'		=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),	//APP优惠多少钱
			'group_share_num'=>	$now_group['group_share_num'],	//您需要购买或者邀请好友购买多少份才能成团
			'open_now_num'	=>	$now_group['open_now_num'],	//还差多少份成团
			'open_num'		=>	$now_group['open_num'],			//还差多少份成团
			'sale_count'	=>	$now_group['sale_count']+$now_group['virtual_num'],	//已售
			'score_mean'	=>	$now_group['score_mean'],			//多少分
			'reply_count'	=>	$now_group['reply_count'],			//多少人评论
			'tuan_type'		=>	$now_group['tuan_type'],			//团购类型
			'pin_num'		=>	$now_group['pin_num'],			//团购类型
			'trade_type' => $now_group['trade_type'],
			'appoint_id' => $now_group['appoint_id'],
			'is_collect' => 0,
			'sale_txt' => $now_group['sale_txt'],
			'pic_list'=>$now_group['merchant_pic'],
			'map_url'=>$this->config['site_url'].'/wap.php?g=Wap&c=Group&a=map&group_id='.$now_group['group_id'],
			'group_start_time' => $now_group['begin_time'],
			'now_server_time' => time(),
			'trade_type' => $now_group['trade_type'],
			'intro' => $now_group['intro'],
			'no_refund' => $now_group['no_refund'],
			'hits' => $now_group['hits'],
		);


		if(!empty($this->_uid)){
			$data_user_collect['type'] ='group_detail';
			$data_user_collect['id'] = $now_group['group_id'];
			$data_user_collect['uid'] = $this->_uid;
			if($database_user_collect->field('collect_id')->where($data_user_collect)->find()){
				$arr['now_group']['is_collect'] = 1;
			}
		}

		if($now_group['tuan_type'] != 2){
			$arr['now_group']['merchant_name']	=	$now_group['merchant_name'];
		}else{
			$arr['now_group']['merchant_name']	=	$now_group['s_name'];
		}
		if($now_group['cue_arr']){
			foreach($now_group['cue_arr'] as $v){
				if(!empty($v['value'])){
					$cue_arr[]	=	array(
						'key'	=>	$v['key'],
						'value'	=>	$v['value'],
					);
				}
			}
			$arr['cue_arr']	=	isset($cue_arr)?$cue_arr:array();
		}else{
			$arr['cue_arr']	=	array();
		}
		//	商家的其他店铺
		if($now_group['store_list']){
			foreach($now_group['store_list'] as $k=>$v){
				$arr['store_list'][]	=	array(
					'store_id'	=>	$v['store_id'],
					'name'	=>	$v['name'],
					'area_name'	=>	$v['area_name'],
					'adress'	=>	$v['adress'],
					'range'	=>	isset($v['range'])?$v['range']:'',
					'phone'	=>	$v['phone'],
					'lat'	=>	$v['lat'],
					'lng'	=>	$v['long'],
				);
			}
		}else{
			$arr['store_list']	=	array();
		}
		//	还有多久开始时间
		$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];

		if($time > 0){
			$arr['now_group']['is_time']	=	1;		//时间按钮
			$time_array = '还剩'.floor($time/86400).'天'.floor($time%86400/3600).'时'.floor($time%86400%3600/60).'分开团';
		}else if($now_group['end_time'] > $_SERVER['REQUEST_TIME'] && $now_group['begin_time'] < $_SERVER['REQUEST_TIME'] && $now_group['type'] == 1){
			if($now_group['is_appoint_bind']){
				$arr['now_group']['is_time']	=	2;	//立即预约
				$appUrl		=	$this->config['site_url'].U('Wap/Appoint/detail',array('appoint_id'=>$now_group['appoint_id']));
				$arr['now_group']['url']	=	str_replace('appapi.php','wap.php',$appUrl);
			}else{
				$arr['now_group']['is_time']	=	3;	//立即购买
			}
		}else{
			$arr['now_group']['is_time']	=	0;		//没有按钮
		}
		
		$arr['now_group']['time_array']	=	isset($time_array)?$time_array:'';
		$arr['now_group']['url']	=	isset($arr['now_group']['url'])?$arr['now_group']['url']:'';
		if($now_group['trade_type']!='' && $now_group['trade_type']!='0'){
			$arr['now_group']['is_time']=4;
			$arr['now_group']['url']	=	$this->config['site_url'].'/wap.php?c=Group&a=detail&group_id='.$now_group['group_id'];
		}
		//	评论
		if($now_group['reply_count']){
			$reply_list = D('Reply')->get_reply_list($now_group['group_id'],0,count($now_group['store_list']),3);
		}
		if($reply_list){
			foreach($reply_list as $k=>$v){
				if($v['pics']){
					foreach($v['pics'] as $kk=>$vv){
						if($kk == 8){
							break;
						}else{
							$pics[]	=	$vv['m_image'];
						}
					}
				}
				$arr['reply_list'][]	=	array(
					'nickname'	=>	$v['nickname'],
					'add_time'	=>	$v['add_time'],
					'score'		=>	$v['score'],
					'comment'	=>	$v['comment'],
					'pics'		=>	isset($pics)?$pics:array(),
					'merchant_reply_content'	=>	$v['merchant_reply_content'],
				);
				unset($pics);
			}
		}else{
			$arr['reply_list']	=	array();
		}
		
		
		//本地优选小程序不需要商家团购、其他团购，而是需要推荐同分类下的其他团购
		if(!$_POST['is_youxuan']){
			//	商家其他的团购
			$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_group['mer_id'],3,true,$now_group['group_id']);
			if($merchant_group_list){
				foreach($merchant_group_list as $k=>$v){
					$arr['merchant_group_list'][]	=	array(
						'list_pic'	=>	$v['list_pic'],
						'group_id'	=>	$v['group_id'],
						'name'	=>	$v['name'],
						'group_name'	=>	$v['group_name'],
						'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
						'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
						'sale_count'	=>	$v['sale_count']+$v['virtual_num'],
						'sale_txt'	=>	$v['sale_txt'],
						'pin_num'	=>	$v['pin_num'],
						'trade_type'	=>	$v['trade_type'],
					);
				}
			}else{
				$arr['merchant_group_list']	=	array();
			}
			//	分类下其他团购，看了本团购的用户还看了
			$category_group_list = D('Group')->get_grouplist_by_catId($now_group['cat_id'],$now_group['cat_fid'],3,true);
			if($category_group_list){
				foreach($category_group_list as $key=>$value){
					if($value['group_id'] == $now_group['group_id']){
						unset($category_group_list[$key]);
					}else{
						$arr['category_group_list'][]	=	array(
							'list_pic'	=>	$value['list_pic'],
							'group_id'	=>	$value['group_id'],
							'name'	=>	$value['name'],
							'tuan_type'	=>	$value['tuan_type'],
							'group_name'	=>	$value['group_name'],
							'price'	=>	rtrim(rtrim(number_format($value['price'],2,'.',''),'0'),'.'),
							'wx_cheap'	=>	rtrim(rtrim(number_format($value['wx_cheap'],2,'.',''),'0'),'.'),
							'sale_count'	=>	$value['sale_count']+$value['virtual_num'],
							'sale_txt'	=>	$value['sale_txt'],
							'trade_type'	=>	$value['trade_type'],
						);
					}
				}
			}else{
				$arr['category_group_list']	=	array();
			}
		}
		$arr['share']	=	array(
			'url'	=>	$this->config['site_url'].U('Wap/Group/detail',array('group_id'=>$now_group['group_id'])),
			'pic'	=>	$arr['now_group']['image'],
			'title'	=>	$now_group['price'].'元 【'.$now_group['s_name'].'】',
			'content'=>	$now_group['intro'],
		);
		$arr['share']['url']	=	str_replace('appapi.php','wap.php',$arr['share']['url']);
		if($this->DEVICE_ID=='wxapp'){
			$arr['now_group']['content_xml'] = S('wxapp_groupcontent_'.$now_group['group_id']);
			if(empty($arr['now_group']['content_xml'])){
				$src	=	'<img src="'.C('config.site_url').'/';
				$now_group['content']	=	str_replace('<img src="/',$src,$now_group['content']);
				// $now_group['content'] = '<p></p>';
				$dom = new simple_html_dom();
				$dom->load($now_group['content']);
				$arr['now_group']['content_xml'] = $this->htmlToArray($dom->root->children, 1);
				$dom->clear();
				S('wxapp_groupcontent_'.$now_group['group_id'],$arr['now_group']['content_xml'],600);
			}
			
		}else{
			$content = $this->detail_content($now_group['group_id']);
			$arr['now_group']['content']  = $content;
		}
		if($this->DEVICE_ID == 'wxapp_groupwxapp' || $this->DEVICE_ID == 'wxapp'){
			$src	=	'<img src="'.C('config.site_url').'/';
			$arr['now_group']['content_rich'] = str_replace('<img src="'.C('config.site_url').'/',$src,$now_group['content']);
			$arr['now_group']['content_rich'] = str_replace('<img src="/',$src,$arr['now_group']['content_rich']);
			$arr['now_group']['content_rich'] = '<div class="detail"><div class="content">'.$arr['now_group']['content_rich'].'</div></div>';
		}
		
		$this->returnCode(0,$arr);
	}

	public function group_collect(){
		$now_user = D('User')->get_user($this->_uid);
		if(empty($now_user)){
			$this->returnCode('20044013');
		}
		$_POST['type'] = 'group_detail';
		$id = $_POST['group_id'];
		$database_user_collect = D('User_collect');
		if($_POST['action'] == 'add'){
			$data_user_collect['type'] = $_POST['type'];
			$data_user_collect['id'] = $id;
			$data_user_collect['uid'] = $this->_uid;
			if($database_user_collect->field('collect_id')->where($data_user_collect)->find()){
				$this->returnCode('20046038');
			}
			if($database_user_collect->data($data_user_collect)->add()){
				$condition_group['group_id'] =$id;
				D('Group')->where($condition_group)->setInc('collect_count');
				$this->returnCode(0);
			}else{
				$this->returnCode('20046040');
			}
		}else if($_POST['action'] == 'del'){
			$condition_user_collect['type'] = $_POST['type'];
			$condition_user_collect['id'] =$id;
			$condition_user_collect['uid'] = $this->_uid;
			if($database_user_collect->where($condition_user_collect)->delete()){
				$condition_group['group_id'] = $id;
				D('Group')->where($condition_group)->setDec('collect_count');
				$this->returnCode(0);
			}else{
				$this->returnCode('20046042');
			}
		}else{
			$this->returnCode('20045014');
		}

	}
	
	private function htmlToArray($obj, $count)
	{
		$return = array();
		foreach ($obj as $p) {
			$data = array();
			if ($p->tag == 'table') continue;
			$data['tag'] = $p->tag;
			foreach ($p->attr as $k => $v) {
				$data['attr'][$k] = $v;
			}
			if ($p->tag == 'img') {
				$data['index'] = $count;
				$count ++;
			}
			if (!empty($p->nodes)) {
				$data['child'] =  $this->htmlToArray($p->nodes, $count);
			} else {
				$data['text'] = htmlspecialchars_decode(str_replace('&nbsp;',' ',$p->plaintext));
				$txt = trim($data['text']);
				if (empty($txt) && $p->tag == 'text') continue;
			}
			$return[] = $data;
		}
		return $return;
	}
	
	private function check_group_status($groupids=array()){
		if(!empty($groupids)){
			$tmpids=M('Group')->where('group_id in('.implode(',',$groupids).') and status="1" AND pin_num=0  AND end_time>'.$_SERVER['REQUEST_TIME'])->field('group_id')->select();
			return $tmpids;
		}
		return false;
	}
	//	图文详情
	public function detail_content($group_id_=''){
		$group_id	=	$group_id_!=''?$group_id_:I('group_id');
		$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');
		if(empty($now_group)){
			$this->returnCode('20046010');
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		//	组装详情字段
		$src	=	'<img src="'.C('config.site_url').'/';
		//$now_group['content']	=	str_replace('<img src="/',$src,$now_group['content']);
		$now_group['content']	=	preg_replace('/<img.*?.src\=\"\//',$src,$now_group['content']);
		$content	=	'<!DOCTYPE html>
						<html lang="zh-CN">
                            <head>
						        <meta charset="utf-8" />
								<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
								<meta name="apple-mobile-web-app-capable" content="yes"/>
								<meta name="apple-touch-fullscreen" content="yes"/>
								<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
								<meta name="format-detection" content="telephone=no"/>
								<meta name="format-detection" content="address=no"/>
						
						<style type="text/css">
							article, aside, audio, body, canvas, caption, details, div, p, figure, footer, header, hgroup, html, iframe, img, mark, menu, nav, object, section, span, summary, table, tbody, td, tfoot, thead, tr, video, dl, dd {margin: 0;padding: 0;border: 0;}body {font-size: 14px;line-height: 1.5;-webkit-user-select: none;-webkit-touch-callout: none;background-color:white;padding-bottom: 10px;padding-left:10px;padding-right:10px;}
							table {border-collapse: collapse;border-spacing: 0;}
							.deal-menu-summary {padding: 0 10px 10px;text-align: right;border-bottom: 1px #e8e8e8 solid;}
							.detail .content p {margin: 10px 0;color: #a1a1a1;}
							.deal-menu-summary .worth {display: inline-block;min-width: 10px;_width: 10px;padding-right: 20px;text-align: left;word-break: normal;word-wrap: normal;font-weight: bold;}
							.deal-menu-summary .price {color: #ea4f01;padding-right: 0;}
							.detail .content{line-height:1.6em;}.detail .content table { width:100%!important; margin-top:0px; border:none; font-size:14px; color:#222; }
							.detail .content table .name { width:auto; text-align:left; border-left:none; }
							.detail .content table .price { width:20%; text-align:center; }
							.detail .content table .amount { width:20%; text-align:center; }
							.detail .content table .subtotal { width:20%; text-align:right; border-right:none; font-family: arial, sans-serif; }
							.detail .content table caption, .detail .content table th, .detail .content table td {padding:8px 10px; background:#FFF; border:1px solid #E8E8E8; border-top:none; word-break:break-all; word-wrap:break-word;}
							.detail .content table caption { background:#F0F0F0; }
							.detail .content table caption .title, .detail .content table .subline .title { font-weight:bold; }
							.detail .content table th { color:#333; background:#F0F0F0; font-weight:bold; border-left-style:none; border-right-style:none;}
							.detail .content table td { color:#a1a1a1; border-bottom-style:dotted; }
							.detail .content table .subline { background:#fff; text-align:center; border-left:none; border-right:none; }
							.detail .content table .subline-left { width:22%; text-align:left;border-right: 1px #e8e8e8 dotted; }
							.detail .content img{max-width:100%;_width:100%;display:inline-block;}
							.detail .content ul{list-style-type: initial;padding-left:16px;font-size:14px!important;}
							.detail .content ul li {font-size:14px!important;margin:4px 0;line-height: 1.5;color:#a1a1a1!important;}
						</style></head><body><div class="detail"><div class="content">'.$now_group['content'].'</div></div></body></html>';
						
						
		
		if($group_id_){
			return $content;
		}
		$arr['now_group']	=	array(
			'group_id'		=>	$now_group['group_id'],				//团购ID
			'image'		=>	$now_group['all_pic'][0]['m_image'],	//图片
			'group_name'	=>	$now_group['group_name'],	//团购名
			'price'	=>	rtrim(rtrim(number_format($now_group['price'],2,'.',''),'0'),'.'),			//现价
			'old_price'	=>	rtrim(rtrim(number_format($now_group['old_price'],2,'.',''),'0'),'.'),	//老价格
			'wx_cheap'	=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),	//APP优惠多少钱
			'group_share_num'	=>	$now_group['group_share_num'],	//您需要购买或者邀请好友购买多少份才能成团
			'open_now_num'	=>	$now_group['open_now_num'],	//还差多少份成团
			'open_num'	=>	$now_group['open_num'],			//还差多少份成团
			'content'		=>	$content,						//本单详情
			'tuan_type'		=>	$now_group['tuan_type'],			//团购类型
		);
		
		if($now_group['tuan_type'] == 2){
			$arr['now_group']['merchant_name']	=	$now_group['merchant_name'];
		}else{
			$arr['now_group']['merchant_name']	=	$now_group['s_name'];
		}
		//	还有多久开始时间
		$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];
		if($time > 0){
			$arr['now_group']['is_time']	=	1;		//时间按钮
			$time_array = '还剩'.floor($time/86400).'天'.floor($time%86400/3600).'时'.floor($time%86400%3600/60).'分开团';
		}else if($now_group['end_time'] > $_SERVER['REQUEST_TIME'] && $now_group['begin_time'] < $_SERVER['REQUEST_TIME'] && $now_group['type'] == 1){
			if($now_group['is_appoint_bind']){
				$arr['now_group']['is_time']	=	2;	//立即预约
			}else{
				$arr['now_group']['is_time']	=	3;	//立即购买
			}
		}else{
			$arr['now_group']['is_time']	=	0;		//没有按钮
		}
		$arr['now_group']['url'] = C('config.site_url').'/wap.php?g=Wap&c=Appoint&a=index';
		$arr['now_group']['trade_type']  = $now_group['trade_type'];
		if($now_group['trade_type']=='hotel'){
			$arr['now_group']['url'] = C('config.site_url').'/wap.php?g=Wap&c=Group&a=detail&group_id='.$now_group['group_id'];
			$arr['now_group']['is_time']	=	4;
		}
		$arr['now_group']['time_array']	=	isset($time_array)?$time_array:'';
		
		if($_GET['show_html']){
			echo $arr['now_group']['content'];
		}else{
			$this->returnCode(0,$arr);
		}
	}
	//	提交订单页面
	public function buy(){
		$now_user = D('User')->get_user($this->_uid);
		if(empty($now_user)){
			$this->returnCode('20044013');
		}
		$group_id	=	I('group_id');
		$type	=	I('type',1);
		$now_group = D('Group')->get_group_by_groupId($group_id);
		if(empty($now_group)){
			$this->returnCode('20046011');
		}
		if($now_group['begin_time'] > $_SERVER['REQUEST_TIME']){
			$this->returnCode('20046012');
		}
		if($now_group['type'] > 2){
			$this->returnCode('20046013');
		}
		if($now_group['pin_num']>0){
			if($_POST['group_type']==1){
				$tmp_group['price'] = $now_group['old_price'];
			}elseif($_POST['group_type']==3){
				$tmp_group['price'] = $now_group['price']*$now_group['start_discount']/100; //团长按团长折扣计算
			}elseif($_POST['group_type']==2){
				$_POST['group_type'] = 2;
				$tmp_group['price'] = $now_group['price'];
			}
		}else{
			$tmp_group['price'] = $now_group['price'];
		}

		//用户等级 优惠
		$level_off=false;
		$finalprice=0;
		if(!empty($this->user_level) && !empty($now_user) && isset($now_user['level'])){
			$leveloff=!empty($now_group['leveloff']) ? unserialize($now_group['leveloff']) :'';
			/****type:0无优惠 1百分比 2立减*******/
			if(!empty($leveloff) && isset($leveloff[$now_user['level']]) && isset($this->user_level[$now_user['level']])){
				$level_off=$leveloff[$now_user['level']];
				if($level_off['type']==1){
					$finalprice=$tmp_group['price']*($level_off['vv']/100);
					$finalprice=$finalprice>0 ? $finalprice : 0;
					$level_off['offstr']='单价按原价'.$level_off['vv'].'%来结算';
				}elseif($level_off['type']==2){
					$finalprice=$tmp_group['price']-$level_off['vv'];
					$finalprice=$finalprice>0 ? $finalprice : 0;
					$level_off['offstr']='单价立减'.$level_off['vv'].'元';
				}
			}
		}
		is_array($level_off) && $level_off['price']=round($finalprice,2);
		unset($leveloff);
		if($type == 2){
			if($finalprice > 0 ){
				$now_group['price']=round($finalprice,2);
				$now_group['level_price']=round($finalprice,2);
			}
			//$_POST['group_type']=1;
			$_SESSION['gid'] = $_POST['gid'];
			$_POST['pick_in_store'] = 0;
			if($_POST['pick_addr_id']!=''){
				$_POST['pick_in_store']=1;
				$_POST['adress_id']=$_POST['pick_addr_id'];
			}
			if($now_group['pin_num']>0){
				if($_POST['group_type']==1){
					$now_group['price'] = $now_group['old_price'];
				}elseif($_POST['group_type']==3){
					$now_group['price'] = $now_group['price']*$now_group['start_discount']/100; //团长按团长折扣计算
				}elseif($_POST['group_type']==2){
					$_POST['group_type'] = 2;
					$now_group['price'] = $now_group['price'];
				}
			}
			$result = D('Group_order')->save_post_form($now_group,$now_user['uid'],0);

			if($result['error'] == 1){
				$this->returnCode('1001',array(),$result['msg']);
			}
			$arr['order']	=	array(
				'order_id'	=>	$result['order_id'],
				'type'	=>	'group',
			);
			$this->returnCode(0,$arr);
		}else{
			if($now_group['tuan_type'] == 2){
				$now_group['user_adress'] = D('User_adress')->get_one_adress($now_user['uid'],intval($_GET['adress_id']));
			}
			$pick_list = D('Pick_address')->get_pick_addr_by_merid($now_group['mer_id']);
			$pick_addr_id	=	I('pick_addr_id');
			if(!empty($pick_addr_id)){
				foreach($pick_list as $k=>$v){
					if($v['pick_addr_id']==$pick_addr_id){
						$pick_address = $v;
						break;
					}
				}
			}else{
				$pick_address =$pick_list[0];
			}
			if($pick_address){
				$arr['pick_address'][]	=	array(
					'pick_addr_id'	=>	$pick_address['pick_addr_id'],
					'name'	=>	$pick_address['name'],
					'phone'	=>	$pick_address['phone'],
					'province'	=>	$pick_address['area_info']['province'],
					'city'	=>	$pick_address['area_info']['city'],
					'area'	=>	$pick_address['area_info']['area'],
				);
			}else{
				$arr['pick_address']	=	array();
			}
			if($now_group){
				//每ID每天限购
				if($now_group['once_max_day']){
					$now_user_today_count = D('Group_order')->get_once_max_day($now_group['group_id'],$this->_uid);
					$today_can_buy = $now_group['once_max_day'] - $now_user_today_count;
					
					if($today_can_buy <= 0){
						$this->error_tips('该商品限制单人每天只能购买' . $now_group['once_max_day'] . '份，您当天购买的数量已达上限，不能再购买!');
					}
					
					if(!$now_group['once_max'] && $today_can_buy || $now_group['once_max'] < $today_can_buy){
						$now_group['once_max'] = $today_can_buy;
					}
				}
				$arr['now_group']	=	array(
					'group_id'	=>	$now_group['group_id'],
					'pin_num'	=>	$now_group['pin_num'],
					'mer_id'	=>	$now_group['mer_id'],
					's_name'	=>	$now_group['group_name'],
					'price'	=>		rtrim(rtrim(number_format($tmp_group['price'],2,'.',''),'0'),'.'),
					'wx_cheap'	=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),
					'once_min'	=>	$now_group['once_min'],
					'once_max'	=>	$now_group['once_max'],
					'tuan_type'	=>	$now_group['tuan_type'],			//2选择地址，时间说明	0，1直接下单
					'pick_in_store'	=>	$now_group['pick_in_store'],	//0送货	1自提
					'finalprice'	=>	isset($finalprice)?$finalprice:0,
					'level'	=>	isset($level_off['level'])?$level_off['level']:0,
					'pd_price'	=>	isset($level_off['price'])?rtrim(rtrim(number_format($level_off['price'],2,'.',''),'0'),'.'):0,
				);
			}else{
				$arr['now_group']	=	array();
			}
			if($now_group['user_adress']){
				$arr['user_adress'][]	=	array(
					'adress_id'	=>	$now_group['user_adress']['adress_id'],
					'name'	=>	$now_group['user_adress']['name'],
					'phone'	=>	$now_group['user_adress']['phone'],
					'province_txt'	=>	$now_group['user_adress']['province_txt'],
					'city_txt'	=>	$now_group['user_adress']['city_txt'],
					'area_txt'	=>	$now_group['user_adress']['area_txt'],
					'adress'	=>	$now_group['user_adress']['adress'],
					'detail'	=>	$now_group['user_adress']['detail'],
					'zipcode'	=>	$now_group['user_adress']['zipcode'],
					'url'	=>	$this->config['site_url'].U('Wap/My/adress',array('group_id'=>$now_group['group_id'],'current_id'=>$now_group['current_id'])),
				);
				$arr['user_adress'][0]['url']	=	str_replace('appapi.php','wap.php',$arr['user_adress'][0]['url']);
			}else{
				$arr['user_adress']	=	array();
			}
			$arr['delivery_time']	=	array(
				array(
					'key'	=>	1,
					'value'	=>	'工作日、双休日与假日均可送货',
				),
				array(
					'key'	=>	2,
					'value'	=>	'只工作日送货',
				),
				array(
					'key'	=>	3,
					'value'	=>	'只双休日、假日送货',
				),
				array(
					'key'	=>	4,
					'value'	=>	'白天没人，其它时间送货',
				),
			);
			if($now_user['phone']){
				$arr['user']	=	array(
					'phone'		=>	substr($now_user['phone'],0,3).'****'.substr($now_user['phone'],7),
				);
			}else{
				$arr['user']	=	array(
					'phone'	=>	'',
				);
			}
			$this->returnCode(0,$arr);
		}
	}

	/*
	 * 酒店购买
	*/
	public function hotel_before_buy(){
		//$_POST['cat-id'],$_POST['dep-time'],$_POST['end-time'];
		$now_user = D('User')->get_user($this->_uid);
		$arr['phone'] = $now_user['phone'];
		$now_group = D('Group')->get_group_by_groupId($_POST['group_id']);
		$hotel_list = D('Trade_hotel_category')->get_cat_price($now_group['mer_id'],$_POST['cat_id'],$_POST['dep_time'],$_POST['end_time']);
		if($hotel_list['err_code']){
			$this->returnCode('1','',$hotel_list['err_msg']);
		}
		$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$_POST['dep_time'],$_POST['end_time']);


		foreach ($hotel_list_tmp[$now_group['trade_info']]['son_list'] as $vv) {
			if($vv['cat_id']==$_POST['cat_id']){
				$now_hotel_info = $vv;
				break;
			}
		}
		$week = array(
				'0'=>'周日',
				'1'=>'周一',
				'2'=>'周二',
				'3'=>'周三',
				'4'=>'周四',
				'5'=>'周五',
				'6'=>'周六',

		);

		$arr['cat_pname'] = $hotel_list_tmp[$now_group['trade_info']]['cat_name'];
		$arr['cat_name'] = $now_hotel_info['cat_name'];
		$day = (strtotime($_POST['end_time'])-strtotime($_POST['dep_time']))/86400;
		$arr['day_text'] =  date('m月d日',strtotime($_POST['dep_time'])).'('.$week[date('w',strtotime($_POST['dep_time']))].')-'. date('m月d日',strtotime($_POST['end_time'])).'('.$week[date('w',strtotime($_POST['dep_time']))].') 共'.$day.'晚';
		foreach ($hotel_list['stock_list'] as &$v) {
			$v['day'] = date('m月d日',strtotime($v['day']));
		}
		$arr['hotel_list'] = $hotel_list;
		$this->returnCode(0,$arr);
	}

	public function hotel_buy(){
		//$_POST['cat-id'],$_POST['dep-time'],$_POST['end-time'];$_POST['quantity']
		$now_user = D('User')->get_user($this->_uid);
		if(empty($now_user)){
			$this->returnCode('20044013');
		}
		$group_id	=	I('group_id');
		$now_group = D('Group')->get_group_by_groupId($group_id);
		if(empty($now_group)){
			$this->returnCode('20046011');
		}
		if($now_group['begin_time'] > $_SERVER['REQUEST_TIME']){
			$this->returnCode('20046012');
		}
		if($now_group['trade_type'] !='hotel'){
			$this->returnCode('20070005');
		}
		$_POST["dep-time"] =$_POST['dep_time'];
		$_POST["end-time"]=$_POST['end_time'];
		$_POST["cat-id"]=$_POST['cat_id'];
		$_POST["quantity"] = $_POST['quantity'];

		$result = D('Group_order')->save_post_form($now_group,$now_user['uid'],0);

		if($result['error'] == 1){
			$this->returnCode('20046014');
		}
		$arr['order']	=	array(
				'order_id'	=>	$result['order_id'],
				'type'	=>	'group',
		);
		$this->returnCode(0,$arr);
	}

	
	//	店铺详情
	public function shop(){
		$store_id	=	I('store_id');
		$now_store = D('Merchant_store')->get_store_by_storeId($store_id);
		if(empty($now_store)){
			$this->returnCode('20046001');
		}
		//得到当前店铺的评分
		$store_score = D('Merchant_score')->field('`score_all`,`reply_count`')->where(array('parent_id'=>$now_store['store_id'],'type'=>'2'))->find();
		if(!$store_score){
			$store_score	=	array(
				'score_all'	=>	'',
				'reply_count'	=>	'',
			);
		}else{
			$store_score['reply_count']	=	round($store_score['score_all']/$store_score['reply_count'],1);
		}
		$arr['store_score']	=	$store_score;
		if(!empty($this->_uid)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_shop';
			$condition_user_collect['id'] = $now_store['store_id'];
			$condition_user_collect['uid'] = $this->_uid;
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_store['is_collect'] = true;
			}
		}
		if($now_store){
			$arr['now_store']	=	array(
				'store_id'	=>	$now_store['store_id'],
				'name'		=>	$now_store['name'],
				'adress'	=>	$now_store['adress'],
				'phone'		=>	$now_store['phone'],
				'lng'		=>	$now_store['long'],
				'lat'		=>	$now_store['lat'],
				'all_pic'	=>	$now_store['all_pic'][0],
				'store_url'	=>	$this->config['site_url'].U('Wap/Index/index',array('token'=>$now_store['mer_id'])),
				'pay_url'	=>	$this->config['site_url'].U('Wap/My/pay',array('store_id'=>$now_store['store_id'])),
				'map_url'	=>	$this->config['site_url'].U('Wap/Group/addressinfo',array('store_id'=>$now_store['store_id'])),
			);
			$arr['now_store']['store_url']	=	str_replace('appapi.php','wap.php',$arr['now_store']['store_url']);
			$arr['now_store']['pay_url']	=	str_replace('appapi.php','wap.php',$arr['now_store']['pay_url']);
			$arr['now_store']['map_url']	=	str_replace('appapi.php','wap.php',$arr['now_store']['map_url']);
		}else{
			$arr['now_store']	=	array();
		}
		$store_group_list = D('Group')->get_store_group_list($now_store['store_id'],0,true);
		if($store_group_list){
			foreach($store_group_list as $k=>$v){
				$arr['store_group_list'][]	=	array(
					'group_name'	=>	$v['group_name'],
					'group_id'	=>	$v['group_id'],
					'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
					'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
					'sale_count'	=>	$v['sale_count'],
					'list_pic'	=>	$v['list_pic'],
					'pin_num'	=>	$v['pin_num'],
				);
			}
		}else{
			$arr['store_group_list']	=	array();
		}
		//为粉丝推荐
		$index_sort_group_list = D('Group')->get_group_list('index_sort',10,true);
		//判断是否微信浏览器，
		if($index_sort_group_list){
			$long	=	I('long');
			$lat	=	I('lat');
			if($long && $lat){
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->gpsToBaidu($lat,$long);//转换腾讯坐标到百度坐标
				$group_store_database = D('Group_store');
				foreach($index_sort_group_list as &$storeGroupValue){
					$tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($location2['lat'],$location2['lng'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
							$rangeSort[] = $tmpStore['Srange'];
						}
						array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
						$storeGroupValue['store_list'] = $tmpStoreList;
						$storeGroupValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
			foreach($index_sort_group_list as $v){
				$arr['index_sort_group_list'][]	=	array(
					'group_name'	=>	$v['group_name'],
					'group_id'	=>	$v['group_id'],
					'list_pic'	=>	$v['list_pic'],
					'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
					'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
					'sale_count'	=>	$v['sale_count'],
					'sale_txt'	=>	$v['sale_txt'],
				);
			}
		}else{
			$arr['index_sort_group_list']	=	array();
		}
        // 分享用信息
        $arr['share_title'] = $now_store['name'];
        $arr['share_content'] = $now_store['txt_info'];
        $arr['share_image'] = $now_store['all_pic'][0];

        $now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
        $row = array_merge($now_store, $now_shop);
        if ($row['store_theme'] && empty($row['is_mult_class'])) {
            $arr['share_url'] = $this->config['site_url'] . 'wap.php?c=Shop&a=classic_shop&shop_id=' . $store_id;
        } else {
            $arr['share_url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=index#shop-' . $store_id;
        }
		$this->returnCode(0,$arr);
	}
	//	团购评论
	public function feedback(){
		$group_id	=	I('group_id');
		$tmp_now_group = D('Group')->get_group_by_groupId($group_id);
		if(empty($tmp_now_group)){
			$this->returnCode('20046011');
		}else{
			$now_group	=	array(
				'score_mean'	=>	$tmp_now_group['score_mean'],
				'reply_count'	=>	$tmp_now_group['reply_count'],
			);
		}
		$arr['now_group']	=	$now_group;
		$reply_return = D('Reply')->get_page_reply_list($tmp_now_group['group_id'],0,'','time',count($tmp_now_group['store_list']));
		foreach($reply_return['list'] as &$v){
			unset($v['store_name'],$v['avatar'],$v['pigcms_id'],$v['order_id'],$v['parent_id'],$v['store_id'],$v['mer_id'],$v['order_type'],$v['uid'],$v['anonymous'],$v['pic'],$v['status'],$v['add_ip']);
			foreach($v['pics'] as $vv){
				$pics[]	=	$vv['m_image'];
			}
			$v['pics']	=	isset($pics)?$pics:array();
			unset($pics);
		}
		unset($reply_return['page']);
		$arr['reply_return']	=	$reply_return;
		$this->returnCode(0,$arr);
	}

	//拼团详情
	public function group_pin_detail(){
		$group_id	=	I('group_id');
		$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');

		if(empty($now_group)){
			$this->returnCode('20046010');
		}
		if($now_group['cue']){
			$now_group['cue_arr'] = unserialize($now_group['cue']);
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		//判断是否微信浏览器，
		$long	=	I('long');
		$lat	=	I('lat');
		if($long && $lat){
			$rangeSort = array();
			foreach($now_group['store_list'] as &$storeValue){
				$storeValue['Srange'] = getDistance($lat,$long,$storeValue['lat'],$storeValue['long']);
				$storeValue['range'] = getRange($storeValue['Srange'],false);
				$rangeSort[] = $storeValue['Srange'];
			}
			array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
		}

		//	积分是否存在
		if(!empty($this->_uid)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_detail';
			$condition_user_collect['id'] = $now_group['group_id'];
			$condition_user_collect['uid'] = $this->_uid;
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_group['is_collect'] = true;
			}

			//判断积分抵现
			$user_coupon_use = D('User')->check_score_can_use($this->_uid,$now_group['price'],'group',$now_group['group_id'],$now_group['mer_id']);
		}

		$arr['now_group']	=	array(
			'score'			=>	$user_coupon_use['score']?$user_coupon_use['score']:0,
			'score_money'	=>	$user_coupon_use['score_money']?strval($user_coupon_use['score_money']):0,
			'group_id'		=>	$now_group['group_id'],				//团购ID
			'group_name'	=>	$now_group['group_name'],	//团购名
			'price'			=>	rtrim(rtrim(number_format($now_group['price'],2,'.',''),'0'),'.'),		//现价
			'old_price'		=>	rtrim(rtrim(number_format($now_group['old_price'],2,'.',''),'0'),'.'),	//老价格
			'wx_cheap'		=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),	//APP优惠多少钱
			'group_share_num'=>	$now_group['group_share_num'],	//您需要购买或者邀请好友购买多少份才能成团
			'open_now_num'	=>	$now_group['open_now_num'],	//还差多少份成团
			'open_num'		=>	$now_group['open_num'],			//还差多少份成团
			'sale_count'	=>	$now_group['sale_count']+$now_group['virtual_num'],	//已售
			'score_mean'	=>	$now_group['score_mean'],			//多少分
			'reply_count'	=>	$now_group['reply_count'],			//多少人评论
			'tuan_type'		=>	$now_group['tuan_type'],			//团购类型
			'pin_num'      =>$now_group['pin_num'],                 //拼团人数
			'invite_num'  =>$now_group['pin_num']-1,
			'time_desc'  =>'该团拼团时限为'.$now_group['pin_effective_time'].'小时',
		    'group_desc' =>$now_group['intro'],
			'group_start_time' => $now_group['begin_time'],
			'now_server_time' => time(),
		);
		foreach( $now_group['all_pic'] as $v_img){
			$arr['now_group']['img_arr'][] = $v_img['m_image'];
		}
		//判断当前团购是否有团购组
		$group_start = D('Group_start');
		$_POST['gid']>0 && $now_start = $group_start->where(array('id'=>$_POST['gid']))->find();
		$group_share_info = array();
		if (!$now_start['status']&&isset($_POST['gid']) && !empty($_POST['gid'])) {
			$in_group = false;
			$start_user_arr = $group_start->get_buyerer_by_order_id('',$_POST['gid']);

			foreach($start_user_arr as $st){
				if($st['uid']==$_SESSION['user']['uid']){
					$in_group = true;
				}
			}
			if(!$in_group){
				$group_share_info = $group_start->get_group_start_user_by_gid($_POST['gid']);
				$end_time = $group_share_info['start_time'] + $now_group['pin_effective_time'] * 3600;
				$effective_time = $end_time - $_SERVER['REQUEST_TIME'];
				if ($effective_time > 0) {
					$group_share_info['end_time'] = $end_time;
				} else {
					$group_start->update_start_group($_GET['gid'], 2); //2 团购小组超时
				}
			}
		}
		$arr['can_join'] = array();
		$arr['group_share_info'] = array();

		if($group_share_info){
			$group_share_info['price'] = $now_group['price'];
			$group_share_info['need_num'] = $group_share_info['complete_num']-$group_share_info['num'];
			$arr['group_share_info'] =$group_share_info;
		}else{
			$can_join = D('Group_start')->check_join_pin($now_group['group_id'],$this->_uid,$now_group['pin_effective_time']);
			if($can_join){
				foreach ($can_join as &$item) {
					$item['need_num'] = $item['complete_num']-$item['num'];
					$item['price'] = $now_group['price'];
					$item['server_time'] = $arr['now_group']['now_server_time'];
					!$item['avatar'] && $item['avator'] = $this->config['site_url'].'/static/img/images/nohead.png';
				}
				$arr['can_join'] = $can_join;
			}
		}



		if($now_group['group_refund_fee']!=100){

			$arr['pin_sign'] = array('1. 拼团成功前，开团人（团长）在拼团时限内不允许取消订单，参团人允许取消订单。','2. 拼团成功后，开团人（团长）不得取消订单，参团人取消订单则收取一定的手续费。');
		}else{
			$arr['pin_sign'] = array('1. 拼团成功前，开团人（团长）在拼团时限内不允许取消订单，参团人不允许取消订单。','2. 拼团成功后，开团人（团长）不得取消订单，参团人不允许取消订单。');

		}
		$arr['pin_rule'] = array('1.开团：选择可开团商品，点击“发起X人团”按钮，付款后即为开团成功;',
				'2.参团：进入朋友分享的页面，点击“立即参团”按钮，付款后即为参团成功，多人同时支付时，支付成功时间较早的人获得参团资格;',
				'3.成团：在开团或参团成功后，点击“分享团购”将页面分享给好友，凑齐人数即为成团，此时商家会开始接单;',
				'4.组团失败：在有效时间内未凑齐人数，即为组团失败，此时将自动退款；'
									);

		if($now_group['tuan_type'] != 2){
			$arr['now_group']['merchant_name']	=	$now_group['merchant_name'];
		}else{
			$arr['now_group']['merchant_name']	=	$now_group['s_name'];
		}
		if($now_group['cue_arr']){
			foreach($now_group['cue_arr'] as $v){
				if(!empty($v['value'])){
					$cue_arr[]	=	array(
							'key'	=>	$v['key'],
							'value'	=>	$v['value'],
					);
				}
			}
			$arr['cue_arr']	=	isset($cue_arr)?$cue_arr:array();
		}else{
			$arr['cue_arr']	=	array();
		}
		//	商家的其他店铺
		if($now_group['store_list']){
			foreach($now_group['store_list'] as $k=>$v){
				$arr['store_list'][]	=	array(
						'store_id'	=>	$v['store_id'],
						'name'	=>	$v['name'],
						'area_name'	=>	$v['area_name'],
						'adress'	=>	$v['adress'],
						'range'	=>	isset($v['range'])?$v['range']:'',
						'phone'	=>	$v['phone'],
				);
			}
		}else{
			$arr['store_list']	=	array();
		}
		//	还有多久开始时间
		$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];

		if($time > 0){
			$arr['now_group']['is_time']	=	1;		//时间按钮
			$time_array = ''.floor($time/86400).'天'.floor($time%86400/3600).'时'.floor($time%86400%3600/60).'分开团';
		}else if($now_group['end_time'] > $_SERVER['REQUEST_TIME'] && $now_group['begin_time'] < $_SERVER['REQUEST_TIME'] && $now_group['type'] == 1){
			if($now_group['is_appoint_bind']){
				$arr['now_group']['is_time']	=	2;	//立即预约
				$appUrl		=	$this->config['site_url'].U('Wap/Appoint/detail',array('appoint_id'=>$now_group['appoint_id']));
				$arr['now_group']['url']	=	str_replace('appapi.php','wap.php',$appUrl);
			}else{
				$arr['now_group']['is_time']	=	3;	//立即购买
			}
		}else{
			$arr['now_group']['is_time']	=	0;		//没有按钮
		}
		$arr['now_group']['time_array']	=	isset($time_array)?$time_array:'';
		$arr['now_group']['url']	=	isset($arr['now_group']['url'])?$arr['now_group']['url']:'';
		//	评论
		if($now_group['reply_count']){
			$reply_list = D('Reply')->get_reply_list($now_group['group_id'],0,count($now_group['store_list']),3);
		}
		if($reply_list){
			foreach($reply_list as $k=>$v){
				if($v['pics']){
					foreach($v['pics'] as $kk=>$vv){
						if($kk == 8){
							break;
						}else{
							$pics[]	=	$vv['m_image'];
						}
					}
				}
				$arr['reply_list'][]	=	array(
						'nickname'	=>	$v['nickname'],
						'add_time'	=>	$v['add_time'],
						'score'		=>	$v['score'],
						'comment'	=>	$v['comment'],
						'pics'		=>	isset($pics)?$pics:array(),
						'merchant_reply_content'	=>	$v['merchant_reply_content'],
				);
				unset($pics);
			}
		}else{
			$arr['reply_list']	=	array();
		}
		//	商家其他的团购
		$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_group['mer_id'],3,true,$now_group['group_id']);
		if($merchant_group_list){
			foreach($merchant_group_list as $k=>$v){
				$arr['merchant_group_list'][]	=	array(
						'list_pic'	=>	$v['list_pic'],
						'group_id'	=>	$v['group_id'],
						'name'	=>	$v['name'],
						'group_name'	=>	$v['group_name'],
						'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
						'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
						'sale_count'	=>	$v['sale_count']+$v['virtual_num'],
						'pin_num'	=>	$v['pin_num'],
				);
			}
		}else{
			$arr['merchant_group_list']	=	array();
		}
		//	分类下其他团购，看了本团购的用户还看了
		$category_group_list = D('Group')->get_grouplist_by_catId($now_group['cat_id'],$now_group['cat_fid'],3,true);
		if($category_group_list){
			foreach($category_group_list as $key=>$value){
				if($value['group_id'] == $now_group['group_id']){
					unset($category_group_list[$key]);
				}else{
					$arr['category_group_list'][]	=	array(
							'list_pic'	=>	$value['list_pic'],
							'group_id'	=>	$value['group_id'],
							'name'	=>	$value['name'],
							'tuan_type'	=>	$value['tuan_type'],
							'group_name'	=>	$value['group_name'],
							'price'	=>	rtrim(rtrim(number_format($value['price'],2,'.',''),'0'),'.'),
							'wx_cheap'	=>	rtrim(rtrim(number_format($value['wx_cheap'],2,'.',''),'0'),'.'),
							'sale_count'	=>	$value['sale_count']+$value['virtual_num'],
					);
				}
			}
		}else{
			$arr['category_group_list']	=	array();
		}
		$arr['share']	=	array(
			'url'	=>	$this->config['site_url'].U('Wap/Group/detail',array('group_id'=>$now_group['group_id'])),
			'pic'	=>	$arr['now_group']['img_arr'][0],
			'title'	=>	$now_group['price'].'元 【'.$now_group['s_name'].'】',
			'content'=>	$now_group['intro'],
		);
		$arr['share']['url']	=	str_replace('appapi.php','wap.php',$arr['share']['url']);
		$this->returnCode(0,$arr);
	}


	public function get_express_fee(){

		$ticket = I('ticket', false);
		$device_id    =   I('Device-Id',false);
		if($ticket && $device_id){
			$info = ticket::get($ticket, $device_id, true);
			$now_user = D('User')->get_user($info['uid']);
			if(empty($now_user)){
				$this->returnCode('20046009');
			}
			$this->user_session = $now_user;
		}else{
			$this->returnCode('20046009');
		}
		$group_id = $_POST['group_id'];
		$address_id = $_POST['address_id'];
		$total_money = $_POST['price'];

		$now_group['user_adress'] = D('User_adress')->get_one_adress($this->_uid,intval($address_id));

		/*运费计算*/
		if($now_group['user_adress']){
			$express_fee = D('Group')->get_express_fee($group_id,$total_money,$now_group['user_adress']);
			$now_group['express_fee'] =$express_fee['freight'];
			$now_group['express_template'] = $express_fee;
//			$now_group['price'] += $now_group['express_fee'];
			$this->returnCode(0,array('express_fee'=>$express_fee['freight'],'express_template'=>$express_fee));

		}else{
			$this->returnCode(0,array('express_fee'=>0));
		}


	}

	public function main_page(){
		$show_ad = $this->config['group_main_page_show_ad'];
		$center_type = $this->config['group_main_page_center_type'];
		//$show_type = $this->config['group_main_page_show_type'];

		$group_adver = D('Adver')->get_adver_by_key('group_index',5);
		$arr['show_ad'] = $show_ad;
		$arr['center_type'] = $center_type;
		$arr['group_adver'] = $group_adver;
		$arr['limit'] = intval($this->config['group_list_default_num']);
		if($show_ad){
			$where['type'] =$center_type;
			$slider_list = M('Group_main_page_pic_slider')->where($where)->order('sort DESC')->select();
			foreach ($slider_list as &$v) {
				$v['pic'] = $this->config['site_url'].'/upload/slider/'.$v['pic'];
				$v['url'] = html_entity_decode($v['url']);
			}
			$arr['center_slider'] = $slider_list;
		}

		$tmp_wap_index_slider =D('Slider')->get_slider_by_key('wap_group_slider', 0);

//		$wap_index_slider = array();
//		foreach ($tmp_wap_index_slider as $key => $value) {
//			$tmp_i = floor($key / 8);
//			$wap_index_slider[$tmp_i][] = $value;
//		}
		$arr['slider'] = $tmp_wap_index_slider;
		$arr['sort_id'] = $this->config['group_list_default_type'];

		$this->returnCode(0,$arr);
	}


	public function hotel(){
		$group_id	=	I('group_id');
		$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');

		if(empty($now_group)){
			$this->returnCode('20046010');
		}

		if($now_group['cue']){
			$now_group['cue_arr'] = unserialize($now_group['cue']);
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		//判断是否微信浏览器，
		$long	=	I('long');
		$lat	=	I('lat');
		if($long && $lat){
			$rangeSort = array();
			foreach($now_group['store_list'] as &$storeValue){
				$storeValue['Srange'] = getDistance($lat,$long,$storeValue['lat'],$storeValue['long']);
				$storeValue['range'] = getRange($storeValue['Srange'],false);
				$rangeSort[] = $storeValue['Srange'];
			}
			array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
		}
		if($now_group['packageid']>0){
			$packages=M('Group_packages')->where(array('id' => $now_group['packageid'], 'mer_id' => $now_group['mer_id']))->find();
			if(!empty($packages['groupidtext'])){
				$mpackages = unserialize($packages['groupidtext']);
				$packagesgroupid = $this->check_group_status(array_keys($mpackages));
				if(is_array($packagesgroupid)){
					foreach($packagesgroupid as $gvv){
						$tmp_mpackages[]	=	array(
								'key'	=>	$gvv['group_id'],
								'value'	=>	$mpackages[$gvv['group_id']],
						);
					}
					$mpackages=$tmp_mpackages;
					unset($tmp_mpackages);
				}
			}else{
				$mpackages = false;
			}
		}
		$arr['mpackages']	=	isset($mpackages)?$mpackages:array();
		//	积分是否存在
		if(!empty($this->_uid)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_detail';
			$condition_user_collect['id'] = $now_group['group_id'];
			$condition_user_collect['uid'] = $this->_uid;
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_group['is_collect'] = true;
			}
			//判断积分抵现
			$user_coupon_use = D('User')->check_score_can_use($this->_uid,$now_group['price'],'group',$now_group['group_id'],$now_group['group_id'],$now_group['mer_id']);
		}
//		if($now_group['pin_num']>0){
//			$now_group['price'] = $now_group['old_price'];
//		}
		foreach ($now_group['all_pic'] as $vv) {
			$images[] = $vv['m_image'];
		}
		if($now_group['begin_time']+864000>time()&&$now_group['sale_count']==0){
			$now_group['sale_txt'] = '新品上架';
		}elseif($now_group['begin_time']+864000<time()&&$now_group['sale_count']==0){
			$now_group['sale_txt'] = '';
		}else{
			$now_group['sale_txt'] = '已售'.floatval($now_group['sale_count']+$now_group['virtual_num']);
		}
		$arr['now_group']	=	array(
				'score'			=>	$user_coupon_use['score'] ? $user_coupon_use['score'] : 0,
				'score_money'	=>	$user_coupon_use['score_money'] ? $user_coupon_use['score_money'] : 0,
				'group_id'		=>	$now_group['group_id'],				//团购ID
				'image'			=>	$now_group['all_pic'][0]['m_image'],	//图片
				'images'			=>	$images,	//图片
				'group_name'	=>	$now_group['group_name'],	//团购名
				'price'			=>	rtrim(rtrim(number_format($now_group['price'],2,'.',''),'0'),'.'),		//现价
				'old_price'		=>	rtrim(rtrim(number_format($now_group['old_price'],2,'.',''),'0'),'.'),	//老价格
				'wx_cheap'		=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),	//APP优惠多少钱
				'group_share_num'=>	$now_group['group_share_num'],	//您需要购买或者邀请好友购买多少份才能成团
				'open_now_num'	=>	$now_group['open_now_num'],	//还差多少份成团
				'open_num'		=>	$now_group['open_num'],			//还差多少份成团
				'sale_count'	=>	$now_group['sale_count']+$now_group['virtual_num'],	//已售
				'score_mean'	=>	$now_group['score_mean'],			//多少分
				'reply_count'	=>	$now_group['reply_count'],			//多少人评论
				'tuan_type'		=>	$now_group['tuan_type'],			//团购类型
				'pin_num'		=>	$now_group['pin_num'],			//团购类型
				'trade_type' => $now_group['trade_type'],
				'appoint_id' => $now_group['appoint_id'],
				'is_collect' => 0,
				'sale_txt' => $now_group['sale_txt'],
				'pic_list'=>$now_group['merchant_pic'],
				'map_url'=>$this->config['site_url'].'/wap.php?g=Wap&c=Group&a=map&group_id='.$now_group['group_id'],
				'group_start_time' => $now_group['begin_time'],
				'now_server_time' => time(),
				'trade_type' => $now_group['trade_type'],
				'intro' => $now_group['intro'],
				'no_refund' => 1,
		);


		if(!empty($this->_uid)){
			$data_user_collect['type'] ='group_detail';
			$data_user_collect['id'] = $now_group['group_id'];
			$data_user_collect['uid'] = $this->_uid;
			if($database_user_collect->field('collect_id')->where($data_user_collect)->find()){
				$arr['now_group']['is_collect'] = 1;
			}
		}

		if($now_group['tuan_type'] != 2){
			$arr['now_group']['merchant_name']	=	$now_group['merchant_name'];
		}else{
			$arr['now_group']['merchant_name']	=	$now_group['s_name'];
		}
		if($now_group['cue_arr']){
			foreach($now_group['cue_arr'] as $v){
				if(!empty($v['value'])){
					$cue_arr[]	=	array(
							'key'	=>	$v['key'],
							'value'	=>	$v['value'],
					);
				}
			}
			$arr['cue_arr']	=	isset($cue_arr)?$cue_arr:array();
		}else{
			$arr['cue_arr']	=	array();
		}
		//	商家的其他店铺
		if($now_group['store_list']){
			foreach($now_group['store_list'] as $k=>$v){
				$arr['store_list'][]	=	array(
						'store_id'	=>	$v['store_id'],
						'name'	=>	$v['name'],
						'area_name'	=>	$v['area_name'],
						'adress'	=>	$v['adress'],
						'range'	=>	isset($v['range'])?$v['range']:'',
						'phone'	=>	$v['phone'],
						'lat'	=>	$v['lat'],
						'lng'	=>	$v['long'],
				);
			}
		}else{
			$arr['store_list']	=	array();
		}
		//	还有多久开始时间
		$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];

		if($time > 0){
			$arr['now_group']['is_time']	=	1;		//时间按钮
			$time_array = '还剩'.floor($time/86400).'天'.floor($time%86400/3600).'时'.floor($time%86400%3600/60).'分开团';
		}else if($now_group['end_time'] > $_SERVER['REQUEST_TIME'] && $now_group['begin_time'] < $_SERVER['REQUEST_TIME'] && $now_group['type'] == 1){
			if($now_group['is_appoint_bind']){
				$arr['now_group']['is_time']	=	2;	//立即预约
				$appUrl		=	$this->config['site_url'].U('Wap/Appoint/detail',array('appoint_id'=>$now_group['appoint_id']));
				$arr['now_group']['url']	=	str_replace('appapi.php','wap.php',$appUrl);
			}else{
				$arr['now_group']['is_time']	=	3;	//立即购买
			}
		}else{
			$arr['now_group']['is_time']	=	0;		//没有按钮
		}

		$arr['now_group']['time_array']	=	isset($time_array)?$time_array:'';
		$arr['now_group']['url']	=	isset($arr['now_group']['url'])?$arr['now_group']['url']:'';
		if($now_group['trade_type']!='' && $now_group['trade_type']!='0'){
			$arr['now_group']['is_time']=4;
			$arr['now_group']['url']	=	$this->config['site_url'].'/wap.php?c=Group&a=detail&group_id='.$now_group['group_id'];
		}
		//	评论
		if($now_group['reply_count']){
			$reply_list = D('Reply')->get_reply_list($now_group['group_id'],0,count($now_group['store_list']),3);
		}
		if($reply_list){
			foreach($reply_list as $k=>$v){
				if($v['pics']){
					foreach($v['pics'] as $kk=>$vv){
						if($kk == 8){
							break;
						}else{
							$pics[]	=	$vv['m_image'];
						}
					}
				}
				$arr['reply_list'][]	=	array(
						'nickname'	=>	$v['nickname'],
						'add_time'	=>	$v['add_time'],
						'score'		=>	$v['score'],
						'comment'	=>	$v['comment'],
						'pics'		=>	isset($pics)?$pics:array(),
						'merchant_reply_content'	=>	$v['merchant_reply_content'],
				);
				unset($pics);
			}
		}else{
			$arr['reply_list']	=	array();
		}
		//	商家其他的团购
		$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_group['mer_id'],3,true,$now_group['group_id']);
		if($merchant_group_list){
			foreach($merchant_group_list as $k=>$v){
				$arr['merchant_group_list'][]	=	array(
						'list_pic'	=>	$v['list_pic'],
						'group_id'	=>	$v['group_id'],
						'name'	=>	$v['name'],
						'group_name'	=>	$v['group_name'],
						'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
						'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
						'sale_count'	=>	$v['sale_count']+$v['virtual_num'],
						'sale_txt'	=>	$v['sale_txt'],
						'pin_num'	=>	$v['pin_num'],
						'trade_type'	=>	$v['trade_type'],
				);
			}
		}else{
			$arr['merchant_group_list']	=	array();
		}
		//	分类下其他团购，看了本团购的用户还看了
		$category_group_list = D('Group')->get_grouplist_by_catId($now_group['cat_id'],$now_group['cat_fid'],3,true);
		if($category_group_list){
			foreach($category_group_list as $key=>$value){
				if($value['group_id'] == $now_group['group_id']){
					unset($category_group_list[$key]);
				}else{
					$arr['category_group_list'][]	=	array(
							'list_pic'	=>	$value['list_pic'],
							'group_id'	=>	$value['group_id'],
							'name'	=>	$value['name'],
							'tuan_type'	=>	$value['tuan_type'],
							'group_name'	=>	$value['group_name'],
							'price'	=>	rtrim(rtrim(number_format($value['price'],2,'.',''),'0'),'.'),
							'wx_cheap'	=>	rtrim(rtrim(number_format($value['wx_cheap'],2,'.',''),'0'),'.'),
							'sale_count'	=>	$value['sale_count']+$value['virtual_num'],
							'sale_txt'	=>	$value['sale_txt'],
							'trade_type'	=>	$value['trade_type'],
					);
				}
			}
		}else{
			$arr['category_group_list']	=	array();
		}
		$arr['share']	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/Group/detail',array('group_id'=>$now_group['group_id'])),
				'pic'	=>	$arr['now_group']['image'],
				'title'	=>	$now_group['price'].'元 【'.$now_group['s_name'].'】',
				'content'=>	$now_group['intro'],
		);
		$arr['share']['url']	=	str_replace('appapi.php','wap.php',$arr['share']['url']);
		if($this->DEVICE_ID=='wxapp'){
			$arr['now_group']['content_xml'] = S('wxapp_groupcontent_'.$now_group['group_id']);
			if(empty($arr['now_group']['content_xml'])){
				$src	=	'<img src="'.C('config.site_url').'/';
				$now_group['content']	=	str_replace('<img src="/',$src,$now_group['content']);
				// $now_group['content'] = '<p></p>';
				$dom = new simple_html_dom();
				$dom->load($now_group['content']);
				$arr['now_group']['content_xml'] = $this->htmlToArray($dom->root->children, 1);
				$dom->clear();
				S('wxapp_groupcontent_'.$now_group['group_id'],$arr['now_group']['content_xml'],600);
			}

		}else{
			$content = $this->detail_content($now_group['group_id']);
			$arr['now_group']['content']  = $content;
		}

		
		$week = array(
		'0'=>'周日',
		'1'=>'周一',
		'2'=>'周二',
		'3'=>'周三',
		'4'=>'周四',
		'5'=>'周五',
		'6'=>'周六',
		
		);

		if($_POST['dep_date'] && strtotime(date('Y-m-d'))<strtotime($_POST['end_date'])){
			$trade_hotel['time_dep_time'] = $_POST['dep_date'];
			$trade_hotel['time_end_time'] = $_POST['end_date'];
			$trade_hotel['show_dep_time'] = substr($_POST['dep_date'],5);
			$trade_hotel['show_end_time'] =  substr($_POST['end_date'],5);
			$trade_hotel['dep_time'] =  $_POST['dep_time'];
			$trade_hotel['end_time'] =  $_POST['end_time'];
			$trade_hotel['dep_time_txt'] =  $week[date('w',strtotime($trade_hotel['dep_time']))];
			$trade_hotel['end_time_txt'] =  $week[date('w',strtotime($trade_hotel['end_time']))];
			$trade_hotel['days'] =  (strtotime($trade_hotel['end_time'])- strtotime($trade_hotel['dep_time']))/86400;
		}else{
			$trade_hotel['time_dep_time'] = date('Y-m-d');
			$trade_hotel['show_dep_time'] = date('m-d');
			$trade_hotel['dep_time'] = date('Ymd');
			$trade_hotel['time_end_time'] = date('Y-m-d',time()+86400);
			$trade_hotel['show_end_time'] = date('m-d',time()+86400);
			$trade_hotel['end_time'] = date('Ymd',time()+86400);
			$trade_hotel['dep_time_txt'] =  $week[date('w',strtotime($trade_hotel['dep_time']))];
			$trade_hotel['end_time_txt'] =  $week[date('w',strtotime($trade_hotel['end_time']))];
			$trade_hotel['days'] =  (strtotime($trade_hotel['end_time'])- strtotime($trade_hotel['dep_time']))/86400;
		}
		$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$trade_hotel['dep_time'],$trade_hotel['end_time']);

		$hotel_cat_id = explode(',',$now_group['trade_info']);

		foreach($hotel_list_tmp as $key=>$v){
			if(in_array($key,$hotel_cat_id)){
				foreach ($v['son_list'] as &$item) {
					$item['stock_num']  = floatval($item['stock_num']);
					$item['price_txt']  = strval($item['price_txt']);
				}
				foreach ($v['cat_pic_list'] as $k=>$vv) {
					unset($v['cat_pic_list'][$k]['image'],$v['cat_pic_list'][$k]['s_image']);
				}
				$hotel_list[] = $v;
				$hotel_list_tmp[$v['cat_id']] = $v;
			}
		}
		$arr['trade_hotel']=$trade_hotel;
		$arr['hotel_list']=$hotel_list;

		$finalprice = round($now_group['price'],2);

		$arr['finalprice']=$finalprice;
		$this->returnCode(0,$arr);

	}
	
	public function ajax_get_trade_hotel_stock(){
		$now_group = D('Group')->get_group_by_groupId($_POST['group_id']);

		$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$_POST['dep_time'],$_POST['end_time']);
		$hotel_cat_id = explode(',',$now_group['trade_info']);
		foreach($hotel_list_tmp as $key=>$v){
			if(in_array($key,$hotel_cat_id)){
				foreach ($v['son_list'] as &$item) {
					$item['stock_num']  = floatval($item['stock_num']);
					$item['price_txt']  = strval($item['price_txt']);
				}
				$hotel_list[] = $v;
			}
		}

		$this->returnCode(0,$hotel_list);
	}

}
?>