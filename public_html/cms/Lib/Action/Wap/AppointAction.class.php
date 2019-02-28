<?php

/**
 *
 * 预约服务
 */
class AppointAction extends BaseAction{
    public function getFooterMenu(){
        $database_home_menu = D('Home_menu');
        $footer_menu_list = $database_home_menu->getMenuList('appoint_footer');
        $this->assign('footer_menu_list', $footer_menu_list);
        return array();
    }

    public function index(){
        $this->getFooterMenu();

        $database_adver = D('Adver');
        $database_slider = D('Slider');
        $database_system_coupon = D("System_coupon");
        $database_appoint_category = D('Appoint_category');

        //顶部广告
        $wap_index_top_adver = $database_adver->get_adver_by_key('wap_yue_index_top', 5);
        $this->assign('wap_index_top_adver', $wap_index_top_adver);

        //中间广告
        $wap_index_center_adver = $database_adver->get_adver_by_key('wap_yue_index_center', 4);
        $this->assign('wap_index_center_adver', $wap_index_center_adver);

        //导航条
        $tmp_wap_index_slider = $database_slider->get_slider_by_key('wap_yue_slider', 0);
        $wap_index_slider = array();
        foreach ($tmp_wap_index_slider as $key => $value) {
            $tmp_i = floor($key / 8);
            $wap_index_slider[$tmp_i][] = $value;
        }
        $this->assign('wap_index_slider', $wap_index_slider);

        //随机派发优惠券
        if( $this->user_session['uid'] && $this->config['open_rand_send']){
            $coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$this->user_session['uid']));
            $coupon_html && $this->assign('coupon_html',$coupon_html);
        }

        //最新3条优惠券
        $coupon_list = $database_system_coupon->get_coupon_list_by_type('appoint', '0', '3', '-1');
        $this->assign('coupon_list', $coupon_list);

        //所有分类 包含2级分类
        $all_category_list = $database_appoint_category->get_category();
        $this->assign('all_category_list', $all_category_list);
        // dump($all_category_list);
        $this->display();
    }

    public function category(){
        $this->getFooterMenu();

        $cat_id = $_GET['cat_id'] + 0;
        $database_appoint_category = D('Appoint_category');
        //所有分类 包含2级分类
        $all_category_list = $database_appoint_category->get_category(1,0,0,0);

        $this->assign('all_category_list', $all_category_list);

        $now_cat_id = !empty($cat_id) ? $cat_id : $all_category_list[0]['cat_id'];
        $this->assign('now_cat_id', $now_cat_id);

        $this->display();
    }

    public function two_category(){
        $cat_id = $_GET['cat_id'] + 0;
        $database_appoint_category = D('Appoint_category');
        $cat_info = $database_appoint_category->get_category_by_id($cat_id);
        if (!$cat_info['is_autotrophic']) {
            $database_area = D('Area');
            $database_appoint = D('Appoint');
            //判断分类信息
            $cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
            $this->assign('now_cat_url', $cat_url);

            //判断地区信息
            $area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
            $this->assign('now_area_url', $area_url);

            $circle_id = 0;
            if (!empty($area_url)) {
                $tmp_area = $database_area->get_area_by_areaUrl($area_url);
                if (empty($tmp_area)) {
                    $this->error_tips('当前区域不存在！');
                }
                $this->assign('now_area', $tmp_area);

                if ($tmp_area['area_type'] == 3) {
                    $now_area = $tmp_area;
                } else {
                    $now_circle = $tmp_area;
                    $this->assign('now_circle', $now_circle);
                    $now_area = $database_area->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
                    if (empty($tmp_area)) {
                        $this->error_tips('当前区域不存在！');
                    }
                    $circle_id = $now_circle['area_id'];
                }
                $this->assign('top_area', $now_area);
                $area_id = $now_area['area_id'];
            } else {
                $area_id = 0;
            }

            //判断排序信息   默认排序就是按照手动设置项排序
            $sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'defaults';

            $long_lat = array('lat' => 0, 'long' => 0);
            $_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
            if (empty($long_lat['long']) || empty($long_lat['lat'])) {
                $sort_id = $sort_id == 'juli' ?  $sort_id:'defaults' ;
                $sort_array = array(
                    array('sort_id' => 'defaults', 'sort_value' => '综合排序'),
                    array('sort_id' => 'price', 'sort_value' => '价格排序'),
                    array('sort_id' => 'appointNum', 'sort_value' => '预约数排序'),
                    array('sort_id' => 'juli', 'sort_value' => '离我最近'),
                );
            } else {
//                import('@.ORG.longlat');
//                $longlat_class = new longlat();
//                $location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
//                $long_lat['lat'] = $location2['lat'];
//                $long_lat['long'] = $location2['lng'];
                $sort_array = array(
                    array('sort_id' => 'defaults', 'sort_value' => '综合排序'),
                    array('sort_id' => 'price', 'sort_value' => '价格排序'),
                    array('sort_id' => 'appointNum', 'sort_value' => '预约数排序'),
                    array('sort_id' => 'juli', 'sort_value' => '离我最近'),
                );
            }
            foreach ($sort_array as $key => $value) {
                if ($sort_id == $value['sort_id']) {
                    $now_sort_array = $value;
                    break;
                }
            }
            $this->assign('sort_array', $sort_array);
            $this->assign('now_sort_array', $now_sort_array);


            //所有分类 包含2级分类
            $all_category_list = $database_appoint_category->get_all_category();
            $this->assign('all_category_list', $all_category_list);

            //根据分类信息获取分类
            if (!empty($cat_url)) {
                $now_category = $database_appoint_category->get_category_by_catUrl($cat_url);
                if (empty($now_category)) {
                    $this->error_tips('此分类不存在！');
                }
                $this->assign('now_category', $now_category);

                if (!empty($now_category['cat_fid'])) {
                    $f_category = $database_appoint_category->get_category_by_id($now_category['cat_fid']);
                    $this->assign('top_category', $f_category);

                    $get_grouplist_catfid = 0;
                } else {
                    $this->assign('top_category', $now_category);

                    $get_grouplist_catfid = $now_category['cat_id'];
                }
            }
            $all_area_list = $database_area->get_all_area_list();
            $this->assign('all_area_list', $all_area_list);
            $product_list = $database_appoint->wap_get_appoint_list_by_catid($_GET['cat_id'] + 0, $get_grouplist_catfid, $area_id, $sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
            foreach ($product_list['group_list'] as $k => $v) {
                $product_list['group_list'][$k]['pic'] = str_replace(',', '/', $v['pic']);
            }
            $this->assign('product_list', $product_list);
            $this->assign('cat_info', $cat_info);
            $this->assign('long_lat', $long_lat);
            $this->display('two_category_merchant');
        } else {
            if ($cat_info) {
                $cuefield = unserialize($cat_info['cue_field']);
                foreach ($cuefield as $val) {
                    $sort[] = $val['sort'];
                }
                array_multisort($sort, SORT_DESC, $cuefield);
            }
            $cat_info['wap_title'] = unserialize($cat_info['wap_title']);
            $cat_info['wap_content'] = unserialize($cat_info['wap_content']);
            $this->assign('cat_info', $cat_info);
            $this->assign('formData', $cuefield);
            $this->display();    //自营、第三方模板
        }
    }

    public function categoryList(){
        $database_category = D('Appoint_category');
        $cat_id = $_GET['cat_id'] + 0;
        if (empty($cat_id)) {
            $this->error_tips('传递参数有误！');
        }

        $where['cat_fid'] = $cat_id;
        $cate_list = $database_category->where($where)->select();
        if (!$cate_list) {
            $this->error_tips('请先添加子分类！');
        }
        $cate_list['cate_list'] = $cate_list;
        $cate_list['cate_num'] = count($cate_list['cate_list']);
        $this->assign('cate_list', $cate_list);
        $this->display();
    }

    public function productList(){
        //判断分类信息
        $cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
        $this->assign('now_cat_url', $cat_url);

        //判断地区信息
        $area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
        $this->assign('now_area_url', $area_url);

        $circle_id = 0;
        if (!empty($area_url)) {
            $tmp_area = D('Area')->get_area_by_areaUrl($area_url);
            if (empty($tmp_area)) {
                $this->error_tips('当前区域不存在！');
            }
            $this->assign('now_area', $tmp_area);

            if ($tmp_area['area_type'] == 3) {
                $now_area = $tmp_area;
            } else {
                $now_circle = $tmp_area;
                $this->assign('now_circle', $now_circle);
                $now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
                if (empty($tmp_area)) {
                    $this->error_tips('当前区域不存在！');
                }
                $circle_id = $now_circle['area_id'];
            }
            $this->assign('top_area', $now_area);
            $area_id = $now_area['area_id'];
        } else {
            $area_id = 0;
        }

        //判断排序信息   默认排序就是按照手动设置项排序
        $sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'defaults';

        $long_lat = array('lat' => 0, 'long' => 0);
        $_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
        if (empty($long_lat['long']) || empty($long_lat['lat'])) {
            //$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
            $sort_array = array(
                array('sort_id' => 'defaults', 'sort_value' => '综合排序'),
                array('sort_id' => 'price', 'sort_value' => '价格排序'),
                array('sort_id' => 'appointNum', 'sort_value' => '预约数排序'),
                array('sort_id' => 'juli', 'sort_value' => '离我最近'),
            );
        } else {
            import('@.ORG.longlat');
            $longlat_class = new longlat();
            $location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
            $long_lat['lat'] = $location2['lat'];
            $long_lat['long'] = $location2['lng'];
            $sort_array = array(
                array('sort_id' => 'defaults', 'sort_value' => '综合排序'),
                array('sort_id' => 'price', 'sort_value' => '价格排序'),
                array('sort_id' => 'appointNum', 'sort_value' => '预约数排序'),
                array('sort_id' => 'juli', 'sort_value' => '离我最近'),
            );
        }
        foreach ($sort_array as $key => $value) {
            if ($sort_id == $value['sort_id']) {
                $now_sort_array = $value;
                break;
            }
        }
        $this->assign('sort_array', $sort_array);
        $this->assign('now_sort_array', $now_sort_array);


        //所有分类 包含2级分类
        $all_category_list = D('Appoint_category')->get_all_category();
        $this->assign('all_category_list', $all_category_list);

        //根据分类信息获取分类
        if (!empty($cat_url)) {
            $now_category = D('Appoint_category')->get_category_by_catUrl($cat_url);
            if (empty($now_category)) {
                $this->error_tips('此分类不存在！');
            }
            $this->assign('now_category', $now_category);

            if (!empty($now_category['cat_fid'])) {
                $f_category = D('Appoint_category')->get_category_by_id($now_category['cat_fid']);
                $this->assign('top_category', $f_category);

                $get_grouplist_catfid = 0;
            } else {
                $this->assign('top_category', $now_category);
                $get_grouplist_catfid = $now_category['cat_id'];
            }
        } else {
            //所有区域
        }
        $all_area_list = D('Area')->get_all_area_list();
        $this->assign('all_area_list', $all_area_list);
        $product_list = D('Appoint')->wap_get_appoint_list_by_catid($_GET['cat_id'] + 0, $get_grouplist_catfid, $area_id, $sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
        foreach ($product_list['group_list'] as $k => $v) {
            $product_list['group_list'][$k]['pic'] = str_replace(',', '/', $v['pic']);
        }

        $this->assign('product_list', $product_list);
        $this->display();
    }

    //手艺人列表
    public function workerList(){
        $database_merchant_workers = D('Merchant_workers');

        //判断排序信息   默认排序就是按照手动设置项排序
        $sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'defaults';

        $long_lat = array('lat' => 0, 'long' => 0);
        $_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
        if (empty($long_lat['long']) || empty($long_lat['lat'])) {
            $sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
            $sort_array = array(
                array('sort_id' => 'defaults', 'sort_value' => '综合排序'),
                array('sort_id' => 'price', 'sort_value' => '均价排序'),
                array('sort_id' => 'appointNum', 'sort_value' => '单数排序'),
                array('sort_id' => 'all_avg_score', 'sort_value' => '好评排序'),
            );
        } else {
            import('@.ORG.longlat');
            $longlat_class = new longlat();
            $location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
            $long_lat['lat'] = $location2['lat'];
            $long_lat['long'] = $location2['lng'];
            $sort_array = array(
                array('sort_id' => 'defaults', 'sort_value' => '综合排序'),
                array('sort_id' => 'price', 'sort_value' => '均价排序'),
                array('sort_id' => 'appointNum', 'sort_value' => '单数排序'),
                array('sort_id' => 'all_avg_score', 'sort_value' => '好评排序'),
            );
        }
        foreach ($sort_array as $key => $value) {
            if ($sort_id == $value['sort_id']) {
                $now_sort_array = $value;
                break;
            }
        }
        $this->assign('sort_array', $sort_array);
        $this->assign('now_sort_array', $now_sort_array);
        $merchant_workers_list = $database_merchant_workers->wap_merchant_worker_list($sort_id);
        $this->assign('merchant_workers_list', $merchant_workers_list);
        $this->display();
    }

    public function ajaxList(){
        $this->header_json();
        $cat_id = $_GET['cat_id'] + 0;
        $area_url = isset($_GET['area_url']) && $_GET['area_url'] ? htmlspecialchars($_GET['area_url']) : 'all';
        $cat_url = isset($_GET['cat_url']) && $_GET['cat_url'] ? htmlspecialchars($_GET['cat_url']) : 'all';
        $circle_id = 0;

        $database_area = D('Area');
        $database_appoint = D('Appoint');
        $database_user_long_lat = D('User_long_lat');
        $database_appoint_category = D('Appoint_category');
        $database_meal_store_category = D('Meal_store_category');
        if ($area_url != 'all') {
            $tmp_area = $database_area->get_area_by_areaUrl($area_url);
            if (empty($tmp_area)) {
                $this->error_tips('当前区域不存在！');
            }
            $this->assign('now_area', $tmp_area);

            if ($tmp_area['area_type'] == 3) {
                $now_area = $tmp_area;
            } else {
                $now_circle = $tmp_area;
                $this->assign('now_circle', $now_circle);
                $now_area = $database_area->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
                if (empty($tmp_area)) {
                    $this->error_tips('当前区域不存在！');
                }
                $circle_id = $now_circle['area_id'];
            }
            $area_id = $now_area['area_id'];
        } else {
            $area_id = 0;
        }
        $sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';
        $long_lat = $database_user_long_lat->getLocation($_SESSION['openid']);
        if (empty($long_lat)) {
            $sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
        }
        if ($cat_url != 'all') {
            $now_category = $database_appoint_category->get_category_by_catUrl($cat_url);
            if (empty($now_category)) {
                $this->error_tips('此分类不存在！');
            }
            $this->assign('now_category', $now_category);

            if (!empty($now_category['cat_fid'])) {
                $f_category = $database_meal_store_category->get_category_by_id($now_category['cat_fid']);

                $this->assign('top_category', $f_category);

                $cat_fid = $now_category['cat_fid'];
                $cat_id = $now_category['cat_id'];
            } else {
                $this->assign('top_category', $now_category);

                $cat_id = 0;
                $cat_fid = $now_category['cat_id'];
            }
        }

        $appointList = $database_appoint->wap_get_appoint_list_by_catid($cat_id, $cat_fid, $area_id, $sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
        //foreach ($appointList['group_list'] as $k => $appoint) {
        //    if (isset($appoint['juli'])) {
        //        $appointList['group_list'][$k]['juli'] = getRange($appoint['juli']);
        //    }
        //}
		
		if(!$appointList['group_list']){
			$appointList['group_list'] = array();
		}
		
        echo json_encode($appointList);
    }

    public function detail(){
        $data_appoint_custom_field = D('Appoint_custom_field');
        $data_user_collect = D('User_collect');
        $database_appoint_order = D('Appoint_order');
        $database_appoint = D('Appoint');
        $database_appoint_product = D('Appoint_product');
        $database_reply = D('Reply');
        $database_appoint_category = D('Appoint_category');
        $database_appoint_comment = D('Appoint_comment');


        $appoint_id = $_GET['appoint_id'] + 0;

        if (empty($appoint_id)) {
            $this->error_tips('当前预约项不存在！');
        }

        $now_group = $database_appoint->get_appoint_by_appointId($appoint_id, 'hits-setInc');
        if (empty($now_group)) {
            $this->error_tips('当前预约项不存在！');
        }else{
            $now_appoint_category = $database_appoint_category->get_category_by_id($now_group['cat_id']);
//             if(!empty($now_appoint_category['is_autotrophic'])){
//                 $this->error_tips('访问页面不存在！');
//             }
        }

        $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);

        if($long_lat){
            $rangeSort = array();
            foreach($now_group['store_list'] as &$storeValue){
                $storeValue['Srange'] = getDistance($long_lat['lat'],$long_lat['long'],$storeValue['lat'],$storeValue['long']);
                $storeValue['range'] = getRange($storeValue['Srange'],false);
                $rangeSort[] = $storeValue['Srange'];
            }
            array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
            $this->assign('long_lat',$long_lat);
        }

        $where_comment['appoint_id'] = $appoint_id;
        $where_comment['status'] = 1;
        $comment_list = $database_appoint_comment->where($where_comment)->limit(3)->select();
        $all_score=0;
        foreach ($comment_list as $key => $value) {
            $sum = $value['profession_score']+$value['communicate_score']+$value['speed_score'];
            $comment_list[$key]['sum'] = 5-intval($sum/3);
            $comment_list[$key]['comment_img'] = unserialize(htmlspecialchars_decode($value['comment_img']));
//            $comment_list[$key]['score'] = intval($sum/3);
            if($value['score']=='0.00'){
                $comment_list[$key]['score'] = intval($sum/3);
                $value['score'] =  $comment_list[$key]['score'];
            }
            $all_score +=$value['score'];

            $where_reply['order_id'] = $value['order_id'];
            $where_reply['order_type'] = 2;
            $reply = M('Reply')->where($where_reply)->find();
            $user = D('User')->get_user($value['uid']);
            $comment_list[$key]['anonymous'] = $reply['anonymous'] ;
            $comment_list[$key]['nickname'] = $user['nickname'] ;


        }
        $now_group['score_mean'] = $all_score/count($comment_list);
        $comment_count = $database_appoint_comment->where($where_comment)->count();
        $this->assign('comment_count',$comment_count);
        $this->assign('comment_list',$comment_list);

        //计算本月销量
        //统计月销量
        $BeginDate = date('Y-m-01', strtotime(date("Y-m-d")));
        $EndDate = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
        $where['order_time'] = array(array('egt', strtotime($BeginDate)), array('lt', strtotime($EndDate)));
        $where['appoint_id'] = $appoint_id;
        $now_month_sales = $database_appoint_order->where($where)->count();
        $now_group['now_month_sales'] = $now_month_sales;
        $merchant_workers_info['now_month_sales'] = $now_month_sales;

        if (count($now_group['store_list']) == 1) {
            $now_group['tel'] = $now_group['store_list'][0]['phone'];
        } else {
            $database_merchant = D('Merchant');
            $merchant_where['mer_id'] = $now_group['mer_id'];
            $now_group['tel'] = $database_merchant->where($merchant_where)->getField('phone');
        }

        $appoint_reply_list = $database_reply->get_appointReply_list($now_group['appoint_id']);
        $now_group['reply_num'] = count($appoint_reply_list);


        $store_id = $_GET['store_id'] + 0;
        if (!empty($store_id)) {
            $this->assign('store_id', $store_id);
        }
        $merchant_group_list = $database_appoint->get_appointlist_by_MerchantId($now_group['mer_id'], 3, true, $now_group['appoint_id']);
        $this->assign('merchant_group_list', $merchant_group_list);
        $product_condition['appoint_id'] = $_GET['appoint_id'] + 0;
        $appoint_product_list = $database_appoint_product->where($product_condition)->select();

		if(count($appoint_product_list) == 1){
			$appoint_product_list[0]['is_active'] = 1;
			$now_group['appoint_price'] = $appoint_product_list[0]['price'];
            $now_group['payment_money'] = $appoint_product_list[0]['payment_price'];
		}else if($appoint_product_list){
            $tmp_appoint_product_list = array();
            foreach($appoint_product_list AS $uniqid => $row){
                foreach($row AS $key=>$value){
                    $tmp_appoint_product_list[$key][$uniqid] = $value;
                }
            }

            $sort = array(
                'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => 'payment_price',       //排序字段
            );
            array_multisort($tmp_appoint_product_list[$sort['field']], constant($sort['direction']) ,$appoint_product_list);
            $appoint_product_list[0]['is_active'] = 1;
            $now_group['appoint_price'] = $appoint_product_list[0]['price'];
            $now_group['payment_money'] = $appoint_product_list[0]['payment_price'];
        }

        $this->assign('now_group', $now_group);

        // 粉丝行为分析
        D('Merchant_request')->add_request($now_group['mer_id'], array('appoint_hits' => 1));

        $this->assign('appoint_product_list', $appoint_product_list);

        if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_group['mer_id']))->select()) {
            $key = $this->get_encrypt_key(array('app_id' => $this->config['im_appid'], 'openid' => $_SESSION['openid']), $this->config['im_appkey']);
            $kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com') . '/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_group['mer_id'];
            $this->assign('kf_url', $kf_url);
        }

        //自定义表单
        $appoint_custom_field_list = $data_appoint_custom_field->where(array('appoint_id' => $appoint_id))->order('appoint_custom_field_sort asc')->select();
        $this->assign('appoint_custom_field_list', $appoint_custom_field_list);

        $collect_where['type'] = 'appoint_detail';
        $collect_where['id'] = $appoint_id;
        $collect_where['uid'] = $_SESSION['user']['uid'];
        $collect_num = $data_user_collect->where($collect_where)->count();
        $this->assign('collect_num', $collect_num);

		//$comment	=	M('Appoint_comment')->field(true)->where(array('appoint_id'=>$appoint_id))->select();
//		if($comment){
//
//		}
        //可选技师s
        $database_merchant_workers = D('Merchant_workers');
        $worker_list = $database_merchant_workers->get_appoint_worker_list($appoint_id);
        if ($worker_list) {
            $this->assign('worker_list', $worker_list);
        }

        $collection_info = D('Appoint_collection')->where(array('appoint_id'=>intval($_GET['appoint_id']),'uid'=>$this->user_session['uid']))->find();
        if($collection_info){
            $this->assign('collection_info',$collection_info);
        }
        
        $this->display();
    }

    public function collection(){
        $info = D('Appoint_collection')->where(array('appoint_id'=>intval($_POST['appoint_id']),'uid'=>$this->user_session['uid']))->find();
        if($info){
            if(D('Appoint_collection')->where(array('appoint_id'=>intval($_POST['appoint_id']),'uid'=>$this->user_session['uid']))->delete()){
                exit(json_encode(array('error'=>3,'msg'=>'取消收藏成功')));
            }
            
        }
        $res = D('Appoint_collection')->data(array('appoint_id'=>$_POST['appoint_id'],'uid'=>$this->user_session['uid']))->add();
        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'收藏成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'收藏失败请重试')));
        }
    }

    public function comment_list(){
        $database_appoint_comment = D('Appoint_comment');
        $where_comment['appoint_id'] = $_GET['appoint_id'];
        $where_comment['status'] = 1;
        $list = $database_appoint_comment->where($where_comment)->select();
        $all_score = 0;
        foreach ($list as $key => $value) {
            $sum = $value['profession_score']+$value['communicate_score']+$value['speed_score'];
            $list[$key]['sum'] = 5-intval($sum/3);
            $list[$key]['comment_img'] = unserialize(htmlspecialchars_decode($value['comment_img']));
            if($value['score']=='0.00'){
                 $list[$key]['score'] = intval($sum/3);
                $value['score'] =  $list[$key]['score'];
            }
            $all_score +=$value['score'];

            $where_reply['order_id'] = $value['order_id'];
            $where_reply['order_type'] = 2;
            $reply = M('Reply')->where($where_reply)->find();
            $user = D('User')->get_user($value['uid']);
            $list[$key]['anonymous'] = $reply['anonymous'] ;
            $list[$key]['nickname'] = $user['nickname'] ;
        }

        $this->assign('list',$list);
        if(count($list)>0){
            $this->assign('all_score',$all_score/count($list));
        }

        $this->assign('score_mean',$all_score/count($list));

        $this->display();
    }

    public function search(){
        $this->display();
    }

    public function search_result(){
        $keyword = $_GET['keyword'];
        $database_merchant_workers = D('Merchant_workers');

        //判断排序信息   默认排序就是按照手动设置项排序
        $sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'defaults';

        $long_lat = array('lat' => 0, 'long' => 0);
        $_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
        if (empty($long_lat['long']) || empty($long_lat['lat'])) {
            $sort_id = $sort_id == '' ? 'defaults' : $sort_id;
            $sort_array = array(
                array('sort_id' => 'defaults', 'sort_value' => '综合排序'),
                array('sort_id' => 'price', 'sort_value' => '均价排序'),
                array('sort_id' => 'appointNum', 'sort_value' => '单数排序'),
                array('sort_id' => 'all_avg_score', 'sort_value' => '好评排序'),
            );
        } else {
            import('@.ORG.longlat');
            $longlat_class = new longlat();
            $location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
            $long_lat['lat'] = $location2['lat'];
            $long_lat['long'] = $location2['lng'];
            $sort_array = array(
                array('sort_id' => 'defaults', 'sort_value' => '综合排序'),
                array('sort_id' => 'price', 'sort_value' => '均价排序'),
                array('sort_id' => 'appointNum', 'sort_value' => '单数排序'),
                array('sort_id' => 'all_avg_score', 'sort_value' => '好评排序'),
            );
        }
        foreach ($sort_array as $key => $value) {
            if ($sort_id == $value['sort_id']) {
                $now_sort_array = $value;
                break;
            }
        }
        $this->assign('sort_array', $sort_array);

        $where['name|mobile'] = array('like', '%' . $keyword . '%');
        $merchant_workers_list = $database_merchant_workers->wap_merchant_worker_list($sort_id, $where);
        $this->assign('merchant_workers_list', $merchant_workers_list);

        $this->assign('now_sort_array', $now_sort_array);
        $this->display();
    }

    //工作人员评论列表
    public function worker_comment_list(){
        $database_appoint_comment = D('Appoint_comment');
        $merchant_worker_id = $_GET['merchant_worker_id'] + 0;
        if (!$merchant_worker_id) {
            $this->error_tips('传递参数有误！');
        }
        $where['merchant_worker_id'] = $merchant_worker_id;
        $where['status'] = 1;

        $appoint_comment_list = $database_appoint_comment->appoint_comment_list($where);
        $where['profession_score'] = array('egt', 4.8);
        $where['communicate_score'] = array('egt', 4.8);
        $where['speed_score'] = array('egt', 4.8);
        $perfect_appoint_comment_list = $database_appoint_comment->appoint_comment_list($where);
        $where['profession_score'] = array(array('egt', 4.5), array('lt', 4.8));
        $where['communicate_score'] = array(array('egt', 4.5), array('lt', 4.8));
        $where['speed_score'] = array(array('egt', 4.5), array('elt', 4.8));
        $great_appoint_comment_list = $database_appoint_comment->appoint_comment_list($where);
        $where['profession_score'] = array(array('egt', 4), array('lt', 4.5));
        $where['communicate_score'] = array(array('egt', 4), array('lt', 4.5));
        $where['speed_score'] = array(array('egt', 4), array('lt', 4.5));
        $general_appoint_comment_list = $database_appoint_comment->appoint_comment_list($where);
        $where['profession_score'] = array('lt', 4);
        $where['communicate_score'] = array('lt', 4);
        $where['speed_score'] = array('lt', 4);
        $bad_appoint_comment_list = $database_appoint_comment->appoint_comment_list($where);
        if (!$appoint_comment_list) {
            $this->error_tips('暂无评论！');
        }
        $this->assign('appoint_comment_list', $appoint_comment_list);
        $this->assign('perfect_appoint_comment_list', $perfect_appoint_comment_list);
        $this->assign('great_appoint_comment_list', $great_appoint_comment_list);
        $this->assign('general_appoint_comment_list', $general_appoint_comment_list);
        $this->assign('bad_appoint_comment_list', $bad_appoint_comment_list);
        $this->display();
    }

    //项目评论列表
    public function appoint_comment(){
        $database_reply = D('Reply');
        $appoint_id = $_GET['appoint_id'] + 0;
        if (empty($appoint_id)) {
            $this->error_tips('传递参数有误！');
        }

        $appoint_comment_list = $database_reply->get_appointReply_list($appoint_id);

        $where['score'] = array('egt', 4.8);
        $perfect_appoint_comment_list = $database_reply->get_appointReply_list($appoint_id, $where);
        $where['score'] = array(array('egt', 4.5), array('lt', 4.8));
        $great_appoint_comment_list = $database_reply->get_appointReply_list($appoint_id, $where);
        $where['score'] = array(array('egt', 4), array('lt', 4.5));
        $general_appoint_comment_list = $database_reply->get_appointReply_list($appoint_id, $where);
        $where['score'] = array('lt', 4);
        $bad_appoint_comment_list = $database_reply->get_appointReply_list($appoint_id, $where);
        if (!$appoint_comment_list) {
            $this->error_tips('暂无评论！');
        }
        $this->assign('appoint_comment_list', $appoint_comment_list);
        $this->assign('perfect_appoint_comment_list', $perfect_appoint_comment_list);
        $this->assign('great_appoint_comment_list', $great_appoint_comment_list);
        $this->assign('general_appoint_comment_list', $general_appoint_comment_list);
        $this->assign('bad_appoint_comment_list', $bad_appoint_comment_list);
        $this->display();
    }

    //工作人员详情
    public function workerDetail(){
        $database_merchant_store = D('Merchant_store');
        $database_appoint_comment = D('Appoint_comment');
        $database_merchant_workers = D('Merchant_workers');
        $database_user_collect = D('User_collect');

        $merchant_workers_id = $_GET['merchant_workers_id'] + 0;
        if (!$merchant_workers_id) {
            $this->error_tips('传递参数有误！');
        }
        $where['merchant_worker_id'] = $merchant_workers_id;
        $merchant_workers_info = $database_merchant_workers->appoint_worker_info($where);
        $comment_where['merchant_worker_id'] = $merchant_workers_id;
        $comment_where['status'] = 1;
        $comment_num = $database_appoint_comment->where($comment_where)->count();
        $merchant_workers_info['comment_num'] = $comment_num;
        //统计月销量
        $database_appoint_visit_order_info = D('Appoint_visit_order_info');
        $BeginDate = date('Y-m-01', strtotime(date("Y-m-d")));
        $EndDate = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
        $where['add_time'] = array(array('egt', strtotime($BeginDate)), array('lt', strtotime($EndDate)));
        $where['merchant_worker_id'] = $merchant_workers_id;
        $now_month_sales = $database_appoint_visit_order_info->where($where)->count();
        $merchant_workers_info['now_month_sales'] = $now_month_sales;

        //联系电话
        $store_where['merchant_store_id'] = $merchant_workers_info['merchant_store_id'];
        $merchant_workers_info['tel'] = $database_merchant_store->where($store_where)->getField('phone');

        $database_merchant_workers->where($where)->setInc('click_num');

        $collect_where['type'] = 'worker_detail';
        $collect_where['id'] = $_GET['merchant_workers_id'];
        $collect_where['uid'] = $_SESSION['user']['uid'];
        $collect_num = $database_user_collect->where($collect_where)->count();

        $appoint_list = $database_merchant_workers->appoint_list($merchant_workers_id);


        if ($services = D('Customer_service')->where(array('mer_id' => $merchant_workers_info['mer_id']))->select()) {

            $key = $this->get_encrypt_key(array('app_id' => $this->config['im_appid'], 'openid' => $_SESSION['openid']), $this->config['im_appkey']);
            $kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com') . '/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $merchant_workers_info['mer_id'];
            $this->assign('kf_url', $kf_url);
        }

        $this->assign('merchant_workers_info', $merchant_workers_info);
        $this->assign('collect_num', $collect_num);
        $this->assign('appoint_list', $appoint_list);
        $this->display();
    }

    // 店铺详情
    public function shop(){
        if (empty($_GET['store_id'])) {
            $this->error_tips('当前店铺不存在！');
        }
        $now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
        if (empty($now_store)) {
            $this->error_tips('该店铺不存在！');
        }

        if (!empty($this->user_session)) {
            $condition_user_collect['type'] = 'group_shop';
            $condition_user_collect['id'] = $now_store['store_id'];
            $condition_user_collect['uid'] = $this->user_session['uid'];

            $database_user_collect = D('User_collect');
            if ($database_user_collect->where($condition_user_collect)->find()) {
                $now_store['is_collect'] = true;
            }
        }
        $this->assign('now_store', $now_store);
        $store_group_list = D('Appoint')->get_store_appoint_list($now_store['store_id'], 5, true,'`store_sort` desc');
        $this->assign('store_group_list', $store_group_list);
        $this->display();
    }

    public function ajaxAppointTime(){
        if(IS_POST){
            $appoint_id = $_POST['appoint_id'] + 0;
            $now_group = D('Appoint')->get_appoint_by_appointId($appoint_id, 'hits-setInc');
            $office_time = unserialize($now_group['office_time']);
            if(isset($office_time['open']) && isset($office_time['close'])){
                $office_time = array($office_time);
            }


            $tempTime = array();
            foreach ($office_time as $office_time_val) {
                // 如果设置的营业时间为0点到0点则默认是24小时营业
                if ((count($office_time_val) < 1)|| (($office_time_val['open'] == '00:00') && ($office_time_val['close']=='00:00'))) {
                    $office_time_val['open'] = '00:00';
                    $office_time_val['close'] = '24:00';
                } else {
                    foreach ($office_time_val as $i => $time) {
                        if ($time['open'] == '00:00' && $time['close'] == '00:00') {
                            unset($office_time_val[$i]);
                        }
                    }
                }
                // 发起预约时候的起始时间 还有提前多长时间可预约
                $beforeTime = $now_group['before_time'] > 0 ? ($now_group['before_time']) * 3600 : 0;
                $gap = $now_group['time_gap'] * 60 > 0 ? $now_group['time_gap'] * 60 : 1800;
                $startTime = strtotime(date('Y-m-d') . ' ' . $office_time_val['open']);
                $endTime = strtotime(date('Y-m-d') . ' ' . $office_time_val['close']);
                for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                    $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                    $tempTime[$tempKey]['time'] = $tempKey;
                    $tempTime[$tempKey]['start'] = date('H:i', $time);
                    $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                    $tempTime[$tempKey]['order'] = 'no';
                    if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                        $tempTime[$tempKey]['order'] = 'yes';
                    }
                }
            }

            $tmp_gap = $now_group['time_gap'];
            $startTimeAppoint = $now_group['start_time'] > strtotime('now') ? $now_group['start_time'] : strtotime('now');
            if($tmp_gap > 0){
                $endTimeAppoint = $now_group['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_group['end_time'];
            }else{
                $endTimeAppoint = $now_group['end_time'] > strtotime('+30 day') ? strtotime('+30 day') : $now_group['end_time'];
            }

            $dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
            $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
            for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
                $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
            }
            ksort($dateArray);
            foreach ($dateArray as $i => $date) {
                $timeOrder[$date] = $tempTime;
            }
            ksort($timeOrder);
            foreach ($timeOrder as $i => $tem) {
                foreach ($tem as $key => $temval) {
                    if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                        $timeOrder[$i][$key]['order'] = 'no';
                    } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                        $timeOrder[$i][$key]['order'] = 'yes';
                    }
                }
            }

            // 查询可预约时间点
            //$appoint_num = D('Appoint_order')->get_appoint_num($now_group['appoint_id'], $now_group['appoint_people']);
            $appoint_num = D('Appoint_order')->get_appoint_num($now_group['appoint_id'], 1);

            if (count($appoint_num) > 0) {
                foreach ($appoint_num as $val) {
                    $key = date('Y-m-d', strtotime($val['appoint_date']));
                    if(isset($timeOrder[$key])) {
                        foreach ($timeOrder[$key] as $tk => &$t) {
                            if ($t['order'] != 'no' && $t['start'] == $val['appoint_time']) {

                                $t['order'] = 'all';
                            }
                        }
                    }
//                    if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
//                        if (isset($timeOrder[$key]) && $timeOrder[$key]['time'] == $val['appoint_time']) {
//                            $timeOrder[$key][$val['appoint_time']]['order'] = 'all';
//                        }
//                    }
                }
            }

            exit(json_encode(array('status' => 1, 'timeOrder' => $timeOrder)));
        }else{
            $this->error_tips("访问页面有误！~~`");
        }

    }

    public function order(){

        $database_merchant_workers = D('Merchant_workers');
        $database_appoint_store = D('Appoint_store');
        $database_user_long_lat = D('User_long_lat');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_appoint_product =  D('Appoint_product');
        $database_area = D('Area');
        $database_appoint_order = D('Appoint_order');
        $database_appoint_category = D('Appoint_category');

        $merchant_workers_id = $_GET['merchantWorkerId'] + 0;
        $appoint_id = $_GET['appoint_id'] + 0;
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $now_user = $database_user->get_user($this->user_session['uid']);
        if (empty($this->user_session['phone']) && !empty($now_user['phone'])) {
            $_SESSION['user']['phone'] = $this->user_session['phone'] = $now_user['phone'];
        }
        $this->assign('now_user', $now_user);

        if (empty($appoint_id)) {
            $this->error_tips('当前服务不存在！');
        }

        $now_group = $database_appoint->get_appoint_by_appointId($appoint_id, 'hits-setInc');
        if (empty($now_group)) {
            $this->error_tips('当前预约项不存在！');
        }

        if ($now_group['start_time'] > $_SERVER['REQUEST_TIME']) {
            $this->error_tips('此单还未开始！');
        }

        // 产品列表
        $appointProduct = $database_appoint_product->get_productlist_by_appointId($appoint_id);

        if ($appointProduct) {
            $this->assign('appoint_product', $appointProduct);
            if (empty($_GET['menuId'])) {
                $defaultAppointProduct = $appointProduct[0];
            } else {
                foreach ($appointProduct as $value) {
                    if ($value['id'] == $_GET['menuId']) {
                        $defaultAppointProduct = $value;
                        break;
                    }
                }
                if (empty($defaultAppointProduct)) {
                    $defaultAppointProduct = $appointProduct[0];
                }
            }

            $this->assign('defaultAppointProduct', $defaultAppointProduct);

            if (empty($_GET['merchantWorkerId'])) {
            } else {
                foreach ($appointProduct as $value) {
                    if ($value['id'] == $_GET['merchantWorkerId']) {
                        break;
                    }
                }
            }
        }

        $now_group['store_list'] = $database_appoint_store->get_storelist_by_appointId($now_group['appoint_id']);

        $long_lat = $database_user_long_lat->getLocation($_SESSION['openid']);
        if (!empty($long_lat)) {
            foreach ($now_group['store_list'] as &$value) {
                $value['range'] = getDistance($value['lat'], $value['long'], $long_lat['lat'], $long_lat['long']);
                $value['range_txt'] = getRange($value['range']);
                $rangeSort[] = $value['range'];
                array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
            }
            $this->assign('long_lat', $long_lat);
        }

        $now_city = $database_area->get_area_by_areaId($this->config['now_city']);
        $this->assign('city_name', $now_city['area_name']);

        $tmp_gap = $now_group['time_gap'];
        $beforeTime = $now_group['before_time'] > 0 ? ($now_group['before_time']) * 3600 : 0;
        $gap = $tmp_gap > 0 ? $tmp_gap * 60 : 1800;

        if (!empty($merchant_workers_id)) {
            // 预约开始时间 结束时间
            $merchant_workers_info = $database_merchant_workers->where(array('merchant_worker_id' => $merchant_workers_id))->find();
            $office_time = unserialize($merchant_workers_info['office_time']);
            if(isset($office_time['open']) && isset($office_time['close'])){
                $office_time = array($office_time);
            }

            $tmp_gap = $merchant_workers_info['time_gap'];
            // 发起预约时候的起始时间 还有提前多长时间可预约
            $beforeTime = $merchant_workers_info['before_time'] > 0 ? ($merchant_workers_info['before_time']) * 3600 : 0;
            $gap = $tmp_gap > 0 ? $tmp_gap * 60 : 1800;
        } else {
            $office_time = unserialize($now_group['office_time']);
            if(isset($office_time['open']) && isset($office_time['close'])){
                $office_time = array($office_time);
            }
//            if(!empty($office_time[0]['open']) && !empty($office_time[0]['close'])){
//                $office_time['open'] = $office_time[0]['open'];
//                $office_time['close'] = $office_time[0]['close'];
//            }
        }


        $tempTime = array();
        foreach ($office_time as $office_time_val) {
            // 如果设置的营业时间为0点到0点则默认是24小时营业
            if ((count($office_time_val) < 1)|| (($office_time_val['open'] == '00:00') && ($office_time_val['close']=='00:00'))) {
                $office_time_val['open'] = '00:00';
                $office_time_val['close'] = '24:00';
            } else {
                foreach ($office_time_val as $i => $time) {
                    if ($time['open'] == '00:00' && $time['close'] == '00:00') {
                        unset($office_time_val[$i]);
                    }
                }
            }
            $startTime = strtotime(date('Y-m-d') . ' ' . $office_time_val['open']);
            $endTime = strtotime(date('Y-m-d') . ' ' . $office_time_val['close']);
            for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                $tempTime[$tempKey]['time'] = $tempKey;
                $tempTime[$tempKey]['start'] = date('H:i', $time);
                $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                $tempTime[$tempKey]['order'] = 'no';
                if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                    $tempTime[$tempKey]['order'] = 'yes';
                }
            }
        }

        $startTimeAppoint = $now_group['start_time'] > strtotime('now') ? $now_group['start_time'] : strtotime('now');

        if($tmp_gap > 0){
            $endTimeAppoint = $now_group['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_group['end_time'];
        }else{
            $endTimeAppoint = $now_group['end_time'] > strtotime('+30 day') ? strtotime('+30 day') : $now_group['end_time'];
        }


        $dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
        $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
        for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
            $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
        }
        ksort($dateArray);

        if($tmp_gap > 0){
            foreach ($dateArray as $i => $date) {
                $timeOrder[$date] = $tempTime;
            }
            ksort($timeOrder);


            foreach ($timeOrder as $i => $tem) {
                foreach ($tem as $key => $temval)
                    if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                        $timeOrder[$i][$key]['order'] = 'no';
                    } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                        $timeOrder[$i][$key]['order'] = 'yes';
                    }
            }

            // 查询可预约时间点
            if ($now_group['is_store']) {
                $appoint_num = $database_appoint_order->get_worker_appoint_num($now_group['appoint_id'], $merchant_workers_id);
            } else {
                $appoint_num = $database_appoint_order->get_appoint_num($now_group['appoint_id']);
            }

            if (count($appoint_num) > 0) {
                foreach ($appoint_num as $val) {
                    $key = date('Y-m-d', strtotime($val['appoint_date']));
//                    if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
//                        if (isset($timeOrder[$key]) && (1 == $val['appointNum'])) {
//                            $timeOrder[$key][$val['appoint_time']]['order'] = 'all';
//                        }
//
//                    }
                    if ($timeOrder[$key][$val['appoint_time'].'-'.date('H:i',strtotime($val['appoint_time'])+$gap)]['order'] != 'no') {
                        $timeOrder[$key][$val['appoint_time'].'-'.date('H:i',strtotime($val['appoint_time'])+$gap)]['order'] ='all';
                    }
                }
            }
            $this->assign('timeOrder', $timeOrder);
        }else{
            $this->assign('timeOrder', $dateArray);
        }



        // 自定义表单项
        $category = $database_appoint_category->get_category_by_id($now_group['cat_id']);
        if (empty($category['cue_field'])) {
            $category = $database_appoint_category->get_category_by_id($category['cat_fid']);
        }
        if ($category) {
            $cuefield = unserialize($category['cue_field']);
            foreach ($cuefield as $val) {
                $sort[] = $val['sort'];
            }
            array_multisort($sort, SORT_DESC, $cuefield);
        }

        $this->assign('formData', $cuefield);

        if (isset($merchant_workers_id) && !empty($merchant_workers_id)) {
            $_where['merchant_worker_id'] = $merchant_workers_id;
            $default_workers_info = $database_merchant_workers->where($_where)->find();
            $default_workers_info['avatar_path'] = str_replace(',','/',$default_workers_info['avatar_path']);
            $default_workers_info['desc'] = htmlspecialchars_decode($default_workers_info['desc']);
            $reward_order = M('Reward_order');
            $uid = $this->user_session['uid'];
            $order = $reward_order->field('order_id')->where(array('status' => 1, 'reward_id' => $merchant_workers_id, 'type' => 3, 'uid' => $uid))->find();
            if ($order) $default_workers_info['is_reward'] = 1;

            $database_appoint_supply = D('Appoint_supply');
            $supply_where['status'] = 3;
            $supply_where['store_id'] = $default_workers_info['merchant_store_id'];
            // 获取服务次数
            $supply_where['worker_id'] = $merchant_workers_id;
            $default_workers_info['finish_count'] = $database_appoint_supply->where($supply_where)->count();


            $this->assign('default_workers_info', $default_workers_info);
            $this->assign('default_store_id', $default_workers_info['merchant_store_id']);
        }


        if (IS_POST) {
            $now_group['cue_field'] = serialize($_POST['custom_field']);
            $now_group['appoint_date'] = $_POST['service_date'];
            $now_group['appoint_time'] = $_POST['service_time'];
            $now_group['store_id'] = !empty($_POST['store_id']) ? $_POST['store_id'] + 0 : 0;
            $now_group['product_id'] = $_POST['product_id'] ? $_POST['product_id'] + 0 : 0;
            if (empty($this->user_session['phone'])) {
                $this->error_tips('您需要绑定手机号码',U('My/bind_user',array('referer'=>urlencode(U('Appoint/order',$_GET)))));
            }
            if(!empty($now_group['is_store']) && empty($now_group['store_id'])){
                $this->error_tips('店铺不能为空！');
            }
            fdump($now_group,'ww');

            $merchant_workers_id = $_POST['merchant_workers_id'] + 0;

            $result = $database_appoint_order->save_post_form($now_group, $this->user_session['uid'], 0, $merchant_workers_id);
            if ($result['error'] == 1) {
                $this->error_tips($result['msg']);
            }

            // 如果需要定金
            if (intval($now_group['payment_status']) == 1) {
                $href = U('Pay/check', array('order_id' => $result['order_id'], 'type' => 'appoint'));
            } else {
                $now_order = $database_appoint_order->where(array('order_id' => $result['order_id']))->find();
                $database_appoint_supply = D('Appoint_supply');
                $supply_data['appoint_id'] = $now_order['appoint_id'];
                $supply_data['mer_id'] = $now_order['mer_id'];
                $supply_data['store_id'] = $now_order['store_id'];
                $supply_data['create_time'] = time();
                $supply_data['worker_id'] = $now_order['merchant_worker_id'];
                $supply_data['start_time'] = $_SERVER['REQUEST_TIME'];
                $supply_data['paid'] = $now_order['paid'];
                if($now_order['merchant_worker_id']){
                    $supply_data['status'] =  2;
                }else{
                    $supply_data['status'] =  1;
                }
                $supply_data['uid'] =  $now_order['uid'];
                $supply_data['pay_type'] = $now_order['pay_type'];
                $supply_data['order_time'] = $now_order['order_time'];
                $supply_data['deliver_cash'] = floatval($now_order['product_price'] - $now_order['product_card_price'] - $now_order['product_merchant_balance'] - $now_order['product_balance_pay'] - $now_order['product_payment_money'] - $now_order['product_score_deducte'] - $now_order['product_coupon_price']);
                $supply_data['order_id'] = $now_order['order_id'];
                $database_appoint_supply->data($supply_data)->add();
                $href = U('My/appoint_order', array('order_id' => $result['order_id']));
            }
            $this->success($href);
        } else {
            if ($this->user_session['phone']) {
                $this->assign('pigcms_phone', substr($this->user_session['phone'], 0, 3) . '****' . substr($this->user_session['phone'], 7));
            } else {
                $this->assign('pigcms_phone', '您需要绑定手机号码');
            }
            $address_id = isset($_GET['adress_id']) ? intval($_GET['adress_id']) : cookie('userLocationId');
            $user_adress = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
            $this->assign('user_adress', $user_adress);
            //dump($user_adress);die;

            $this->assign('now_group', $now_group);
            $this->display();
        }
    }

    //平台下订单
    public function platform_order(){
        $cat_id = $_GET['cat_id'] + 0;
        $cat_fid = $_GET['cat_fid'] + 0;
        if (!$cat_id || !$cat_fid) {
            $this->error_tips('传递参数有误！');
        }
        if (empty($this->user_session)) {
            if ($this->is_app_browser) {
                $location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
                $this->error_tips('请先进行登录！', U('Login/index', $location_param));
            } else {
                $location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
                redirect(U('Login/index', $location_param));
            }
        }

        if (IS_POST) {
            $database_appoint_order = D('Appoint_order');
            $data['type'] = 1;
            $data['cat_id'] = $cat_id;
            $data['cat_fid'] = $cat_fid;
            $data['uid'] = $this->user_session['uid'];
            $data['cue_field'] = serialize($_POST['custom_field']);
            $result = $database_appoint_order->platform_save_post_form($data);

            $this->success(U('two_category',array('cat_id'=>$_GET['cat_id'])));
        } else {
            $database_appoint_category = D('Appoint_category');
            // 自定义表单项
            $category = $database_appoint_category->get_category_by_id($cat_id);
            if (empty($category['cue_field'])) {
                $category = $database_appoint_category->get_category_by_id($cat_fid);
            }
            if ($category) {
                $cuefield = unserialize($category['cue_field']);
                foreach ($cuefield as $val) {
                    $sort[] = $val['sort'];
                }
                array_multisort($sort, SORT_DESC, $cuefield);
            }
            $now_city = D('Area')->get_area_by_areaId($this->config['now_city']);
            $this->assign('city_name', $now_city['area_name']);
            $this->assign('formData', $cuefield);
            $this->display();
        }
    }

    public function ajaxWorker(){
        $database_merchant_workers = D('Merchant_workers');
        $database_merchant_workers_appoint = D('Merchant_workers_appoint');
        $database_appoint_supply = D('Appoint_supply');

        $merchant_store_id = $_POST['merchant_store_id'] + 0;
        $appoint_id = $_POST['appoint_id'] + 0;

        $where['merchant_store_id'] = $merchant_store_id;
        $where['appoint_id'] = $appoint_id;
        $merchant_workers_appoint_list = $database_merchant_workers_appoint->where($where)->getField('id,merchant_worker_id');

        if ($merchant_workers_appoint_list) {
            $Map['merchant_worker_id'] = array('in', $merchant_workers_appoint_list);
            $Map['status'] = 1;
            $worker_list = $database_merchant_workers->where($Map)->select();
            $reward_order = M('Reward_order');
            $uid = $this->user_session['uid'];
            $supply_where['status'] = 3;
            $supply_where['store_id'] = $merchant_store_id;
            foreach($worker_list as &$worker){
                $worker['avatar_path'] = $this->config['site_url'] . '/upload/appoint/' .str_replace(',','/',$worker['avatar_path']);
                //$worker['desc'] = htmlspecialchars_decode(strip_tags(html_entity_decode($worker['desc'])));
				$worker['desc'] = strip_tags(htmlspecialchars_decode(html_entity_decode($worker['desc'])));
                $order = $reward_order->field('order_id')->where(array('status' => 1, 'reward_id' => $worker['merchant_worker_id'], 'type' => 3, 'uid' => $uid))->find();
                if ($order) $worker['is_reward'] = 1;
                // 获取服务次数
                $supply_where['worker_id'] = $worker['merchant_worker_id'];
                $worker['finish_count'] = $database_appoint_supply->where($supply_where)->count();
            }

            exit(json_encode(array('status' => 1, 'worker_list' => $worker_list)));
        } else {
            exit(json_encode(array('status' => 0)));
        }

    }

    public function ajaxWorkerTime(){
        if (IS_POST) {
            $database_merchant_workers = D('Merchant_workers');
            $worker_id = $_POST['worker_id'] + 0;

            // 预约开始时间 结束时间
            $merchant_workers_info = $database_merchant_workers->where(array('merchant_worker_id' => $worker_id))->find();
            $office_time = unserialize($merchant_workers_info['office_time']);
            if(isset($office_time['open']) && isset($office_time['close'])){
                $office_time = array($office_time);
            }


            $tempTime = array();
            foreach ($office_time as $office_time_val) {
                // 如果设置的营业时间为0点到0点则默认是24小时营业
                if ((count($office_time_val) < 1)|| (($office_time_val['open'] == '00:00') && ($office_time_val['close']=='00:00'))) {
                    $office_time_val['open'] = '00:00';
                    $office_time_val['close'] = '24:00';
                } else {
                    foreach ($office_time_val as $i => $time) {
                        if ($time['open'] == '00:00' && $time['close'] == '00:00') {
                            unset($office_time_val[$i]);
                        }
                    }
                }
                // 发起预约时候的起始时间 还有提前多长时间可预约
                $tmp_gap = $merchant_workers_info['time_gap'];
                $beforeTime = $merchant_workers_info['before_time'] > 0 ? ($merchant_workers_info['before_time']) * 3600 : 0;
                $gap = $tmp_gap > 0 ? $tmp_gap * 60 : 1800;

                $startTime = strtotime(date('Y-m-d') . ' ' . $office_time_val['open']);
                $endTime = strtotime(date('Y-m-d') . ' ' . $office_time_val['close']);
                for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                    $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                    $tempTime[$tempKey]['time'] = $tempKey;
                    $tempTime[$tempKey]['start'] = date('H:i', $time);
                    $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                    $tempTime[$tempKey]['order'] = 'no';
                    if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                        $tempTime[$tempKey]['order'] = 'yes';
                    }
                }
            }

            $appoint_id = $_POST['appoint_id'] + 0;
            $now_group = D('Appoint')->get_appoint_by_appointId($appoint_id, 'hits-setInc');
            $startTimeAppoint = $now_group['start_time'] > strtotime('now') ? $now_group['start_time'] : strtotime('now');
            if($tmp_gap >= 0){
                $endTimeAppoint = $now_group['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_group['end_time'];
            }else{
                $endTimeAppoint = $now_group['end_time'] > strtotime('+30 day') ? strtotime('+30 day') : $now_group['end_time'];
            }

            $dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
            $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
            for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
                $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
            }
            ksort($dateArray);

            // if($tmp_gap >= 0){
                foreach ($dateArray as $i => $date) {
                    $timeOrder[$date] = $tempTime;
                }
                ksort($timeOrder);
                foreach ($timeOrder as $i => $tem) {
                    foreach ($tem as $key => $temval)
                        if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                            $timeOrder[$i][$key]['order'] = 'no';
                        } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                            $timeOrder[$i][$key]['order'] = 'yes';
                        }
                }

                // 查询可预约时间点
                $appoint_num = D('Appoint_order')->get_worker_appoint_num($now_group['appoint_id'], $worker_id);

                if (count($appoint_num) > 0) {
                    foreach ($appoint_num as $val) {
                        $key = date('Y-m-d', strtotime($val['appoint_date']));
                        if(isset($timeOrder[$key])) {
                            foreach ($timeOrder[$key] as $tk => &$t) {
                                if ($t['order'] != 'no' && $t['start'] == $val['appoint_time']) {

                                    $t['order'] = 'all';
                                }
                            }
                        }
                    }
                }
                exit(json_encode(array('timeOrder' => $timeOrder, 'status' => 1)));
            // }else{
            //     exit(json_encode(array('timeOrder' => $dateArray, 'status' => 2)));
            // }

        } else {
            $this->error_tips('访问页面有误！~~~');
        }

    }

    // 分店
    public function branch(){
        $now_group = D('Appoint')->get_appoint_by_appointId($_GET['appoint_id'], 'hits-setInc');
        $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);

        if($long_lat){
            $rangeSort = array();
            foreach($now_group['store_list'] as &$storeValue){
                $storeValue['Srange'] = getDistance($long_lat['lat'],$long_lat['long'],$storeValue['lat'],$storeValue['long']);
                $storeValue['range'] = getRange($storeValue['Srange'],false);
                $rangeSort[] = $storeValue['Srange'];
            }
            array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
            $this->assign('long_lat',$long_lat);
        }
        if (empty($now_group)) {
            $this->error_tips('当前预约项不存在！');
        }
        $this->assign('now_group', $now_group);

        $this->display();
    }

    public function feedback(){
        $now_appoint = D('Appoint')->get_appoint_by_appointId($_GET['appoint_id']);
        if (empty($now_appoint)) {
            $this->error_tips('当前预约不存在！');
        }
        $this->assign('now_appoint', $now_appoint);

        $_POST['page'] = $_GET['page'];
        $reply_return = D('Reply')->get_page_reply_list($now_appoint['appoint_id'], 2, '', 'time', 0);
        $reply_return['pagebar'] = '';
        if ($$reply_return['total'] > 1) {
            if ($reply_return['now'] == 1) {
                $reply_return['pagebar'] .= '<a class="btn btn-weak btn-disabled">上一页</a>';
            } else {
                $reply_return['pagebar'] .= '<a class="btn btn-weak" href="' . (U('Appoint/feedback', array('appoint_id' => $now_appoint['appoint_id'], 'page' => $reply_return['now'] - 1))) . '">上一页</a>';
            }
            $reply_return['pagebar'] .= '<span class="pager-current">' . ($reply_return['now']) . '</span>';
            if ($reply_return['now'] == $reply_return['total']) {
                $reply_return['pagebar'] .= '<a class="btn btn-weak btn-disabled">下一页</a>';
            } else {
                $reply_return['pagebar'] .= '<a class="btn btn-weak" href="' . (U('Appoint/feedback', array('appoint_id' => $now_appoint['appoint_id'], 'page' => $reply_return['now'] + 1))) . '">下一页</a>';
            }
        }
        $this->assign($reply_return);

        /* 粉丝行为分析 */
        D('Merchant_request')->add_request($now_appoint['mer_id'], array('group_hits' => 1));

        /* 粉丝行为分析 */
        $this->behavior(array('mer_id' => $now_appoint['mer_id'], 'biz_id' => $now_appoint['appoint_id']));

        $this->display();
    }

    public function ajaxFeedback(){
        $this->header_json();
        $now_appoint = D('Appoint')->get_appoint_by_appointId($_GET['appoint_id']);
        if (empty($now_appoint)) {
            exit(json_encode(array('status' => 0, 'info' => '当前预约不存在！')));
        }
        $reply_return = D('Reply')->get_page_reply_list($now_appoint['appoint_id'], 2, '', 'time', 0);
        $reply_return['status'] = 1;
        exit(json_encode($reply_return));
    }

    public function storeProductList(){
        $mer_id = $_GET['mer_id'] + 0;
        if (!$mer_id) {
            $this->error_tips('传递参数有误！');
        }

        $database_appoint = D('Appoint');
        $list = $database_appoint->get_appointlist_by_MerchantId($mer_id, 0, true);
        $this->assign('list', $list);
        $this->display();
    }

    public function worker_list(){
        $database_merchant_workers = D('Merchant_workers');
        $database_appoint_supply = D('Appoint_supply');

        $appoint_id = $_GET['appoint_id'] + 0;
        $uid = $this->user_session['uid'];
        $merchant_worker_list = $database_merchant_workers->get_appoint_worker_list($appoint_id, $uid);
        if ($merchant_worker_list) {
            $supply_where['status'] = 3;
            foreach ($merchant_worker_list as &$worker) {
                $supply_where['store_id'] = $worker['merchant_store_id'];
                // 获取服务次数
                $supply_where['worker_id'] = $worker['merchant_worker_id'];
                $worker['finish_count'] = $database_appoint_supply->where($supply_where)->count();
            }
        }
        $this->assign('merchant_worker_list', $merchant_worker_list);
        $this->display();
    }

    public function worker_detail(){
        $database_merchant_workers = D('Merchant_workers');
        $database_appoint_comment = D('Appoint_comment');
        $merchant_worker_id = $_GET['merchant_worker_id'] + 0;

        if (empty($merchant_worker_id)) {
            $this->error_tips('访问页面有误！');
        }

        $where['merchant_worker_id'] = $merchant_worker_id;
        $worker_detail = $database_merchant_workers->merchant_worker_detail($where);
        $reward_order = M('Reward_order');
        $uid = $this->user_session['uid'];
        $order = $reward_order->field('order_id')->where(array('status' => 1, 'reward_id' => $merchant_worker_id, 'type' => 3, 'uid' => $uid))->find();
        if ($order) $worker_detail['detail']['is_reward'] = 1;

        if (empty($worker_detail['status'])) {
            $this->error_tips('技师不存在！');
        }


        $Map['merchant_worker_id'] = $merchant_worker_id;
        $Map['status'] = 1;
        $comment_list = $database_appoint_comment->appoint_comment_list($Map);

        // 预约开始时间 结束时间
        $merchant_workers_info = $database_merchant_workers->where(array('merchant_worker_id' => $merchant_worker_id))->find();
        $office_time = unserialize($merchant_workers_info['office_time']);
        if(isset($office_time['open']) && isset($office_time['close'])){
            $office_time = array($office_time);
        }
        $tempTime = array();
        foreach ($office_time as $office_time_val) {
            // 如果设置的营业时间为0点到0点则默认是24小时营业
            if ((count($office_time_val) < 1) || (($office_time_val['open'] == '00:00') && ($office_time_val['close']=='00:00'))) {
                $office_time_val['open'] = '00:00';
                $office_time_val['close'] = '24:00';
            } else {
                foreach ($office_time_val as $i => $time) {
                    if ($time['open'] == '00:00' && $time['close'] == '00:00') {
                        unset($office_time_val[$i]);
                    }
                }
            }
            // 发起预约时候的起始时间 还有提前多长时间可预约
            $beforeTime = $merchant_workers_info['before_time'] > 0 ? ($merchant_workers_info['before_time']) * 3600 : 0;
            $gap = $merchant_workers_info['time_gap'] * 60 > 0 ? $merchant_workers_info['time_gap'] * 60 : 1800;
            $startTime = strtotime(date('Y-m-d') . ' ' . $office_time_val['open']);
            $endTime = strtotime(date('Y-m-d') . ' ' . $office_time_val['close']);
            for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                $tempTime[$tempKey]['time'] = $tempKey;
                $tempTime[$tempKey]['start'] = date('H:i', $time);
                $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                $tempTime[$tempKey]['order'] = 'no';
                $tempTime[$tempKey]['now_date'] = date('Y-m-d',$time);
                if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                    $tempTime[$tempKey]['order'] = 'yes';
                }
            }
        }


        $startTimeAppoint = strtotime('now');
        $endTimeAppoint = strtotime('+3 day');
        $dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
        $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
        for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
            $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
        }
        ksort($dateArray);
        foreach ($dateArray as $i => $date) {
            $timeOrder[$date] = $tempTime;
        }
        ksort($timeOrder);
        foreach ($timeOrder as $i => $tem) {
            foreach ($tem as $key => $temval)
                if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                    $timeOrder[$i][$key]['order'] = 'no';
                } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                    $timeOrder[$i][$key]['order'] = 'yes';
                }
        }
        $database_appoint_supply = D('Appoint_supply');
        $supply_where['store_id'] = $worker_detail['detail']['merchant_store_id'];
        // 获取服务次数
        $supply_where['status'] = 3;
        $supply_where['worker_id'] = $merchant_worker_id;
        $worker_detail['detail']['finish_count'] = $database_appoint_supply->where($supply_where)->count();
        $this->assign('timeOrder',$timeOrder);
        $this->assign('worker_detail', $worker_detail['detail']);
        $this->assign('comment_list', $comment_list['result']['list']);
        $this->display();
    }

    public function check_update_money(){
        $product_price = floatval($_POST['money'] );
        $order_id  = $_POST['order_id'];
        $now_order = D('Appoint_order')->get_order_detail_by_id($this->user_session['uid'],$order_id,true);

        if(floatval($now_order['product_price'])!=$product_price){
            $this->success('价格改变了');
        }else{
            $this->error('价格还未变');
        }
    }



    // 生成技师打赏金钱信息
    public function worker_reward_pay_order() {
        $merchant_worker_id = (int)$_POST['merchant_worker_id'];
        $uid = (int)$_SESSION['user']['uid'];
        if($uid <= 0){
            exit(json_encode(array('code'=>2,'msg'=>'您还未登录，请先登录')));
        }
        // 查询一下用户是否支付过
        $info_pay = M('Reward_order')->where(array('uid'=>$uid,'reward_id'=>$merchant_worker_id, 'status' => 1, 'type' => 3))->find();
        // 如果已经打赏过了，提示打赏过了
        if (!empty($info_pay)) {
            exit(json_encode(array('error'=>1,'msg'=>'已打赏')));
        }
        // 获取技师信息
        $worker_info = M('Merchant_workers')->where(array('merchant_worker_id'=>$merchant_worker_id))->field('name, is_reward, reward_money')->find();
        // 该技师不存在
        if (empty($worker_info)) {
            exit(json_encode(array('error'=>2,'msg'=>'该技师不存在')));
        }
        $userInfo = D('User')->where(array('uid'=>$uid))->field('uid, now_money')->find();
        // 如果余额不足提示去充值
        $reward_money = floatval($worker_info['reward_money']);
        $now_money = floatval($userInfo['now_money']);
        $data = array(
            'now_money' => $userInfo['now_money'],  // 当前余额
            'reward_money' => $worker_info['reward_money'],  // 打赏金额
        );
        if($reward_money > $now_money){
            $data['difference'] = $reward_money - $now_money; // 差额
            exit(json_encode(array('error'=>3,'msg'=>'当前余额不足请充值', 'info' => $data)));
        }
        $data['difference'] = $now_money - $reward_money; // 差额

        exit(json_encode(array('error'=>5,'info'=>$data)));
    }

    // 技师打赏支付
    public function worker_reward_pay() {
        $merchant_worker_id = (int)$_POST['merchant_worker_id'];
        $uid = (int)$_SESSION['user']['uid'];
        if($uid <= 0){
            exit(json_encode(array('code'=>2,'msg'=>'您还未登录，请先登录')));
        }
        // 查询一下用户是否支付过
        $info_pay = M('Reward_order')->where(array('uid'=>$uid,'reward_id'=>$merchant_worker_id, 'status' => 1, 'type' => 3))->find();
        // 如果已经打赏过了，提示打赏过了
        if (!empty($info_pay)) {
            exit(json_encode(array('error'=>1,'msg'=>'已打赏')));
        }
        // 获取文章信息
        $worker_info = M('Merchant_workers')->where(array('merchant_worker_id'=>$merchant_worker_id))->field('name, is_reward, reward_money')->find();
        // 该资讯已被删除
        if (empty($worker_info)) {
            exit(json_encode(array('error'=>2,'msg'=>'该资讯已被删除')));
        }
        $userInfo = M('User')->where(array('uid'=>$uid))->field('uid, nickname, now_money')->find();
        // 如果余额不足提示去充值
        $reward_money = floatval($worker_info['reward_money']);
        $now_money = floatval($userInfo['now_money']);
        if($reward_money > $now_money){
            $data = array(
                'now_money' => $userInfo['now_money'],  // 当前余额
                'reward_money' => $worker_info['reward_money'],  // 打赏金额
                'difference' => $reward_money - $now_money,  // 差额
            );
            exit(json_encode(array('error'=>3,'msg'=>'当前余额不足请充值', 'info' => $data)));
        }
        D()->startTrans();
        $dec_money = D('User')->user_money($this->user_session['uid'], $reward_money, "打赏技工【".$worker_info['name']."】扣除余额 ". $reward_money ." 元");
        if(!$dec_money['error_code']){
            $order_info = array(
                'reward_id' => $merchant_worker_id,
                'uid' => $uid,
                'money' => $reward_money,
                'pay_type' => 3,
                'status' => 1,
                'type' => 3,
                'add_time' => time()
            );
            $reward_order = M('Reward_order')->data($order_info)->add();
            if (!$reward_order) {
                D()->rollback();
                exit(json_encode(array('error'=>2,'msg'=>'打赏失败请重试')));
            }
            // 对应添加技师记录
            // 处理一下操作用户的明后才能
            $user_name = $userInfo['nickname'];
            $msg = $this->add_row($merchant_worker_id, 1, $reward_money, $user_name . '打赏您' . $reward_money . '元');
            if (!$msg) {
                D()->rollback();
                exit(json_encode(array('error'=>2,'msg'=>'打赏失败请重试')));
            }
            $platform_reward_technician_percentage = $this->config['platform_reward_technician_percentage'];
            if ($platform_reward_technician_percentage) {
                // 扣除平台抽成
                $platform_reward_money = ($reward_money * floatval($platform_reward_technician_percentage)) / 100;
                $platform_reward_money = round($platform_reward_money, 2);
                $msg = $this->add_row($merchant_worker_id, 2, $platform_reward_money, '平台抽成，扣除余额' . $platform_reward_money . '元');
                if (!$msg) {
                    D()->rollback();
                    exit(json_encode(array('error'=>2,'msg'=>'打赏失败请重试')));
                }
            }
            D()->commit();
            exit(json_encode(array('error'=>1,'msg'=>'打赏成功')));
        }else{
            D()->rollback();
            exit(json_encode(array('error'=>2,'msg'=> $dec_money['msg'])));
        }
    }

    // 添加技师余额记录
    public function add_row($merchant_worker_id,$type,$money,$msg,$record_ip = true,$ask=0,$ask_id=0,$admin=false,$time =0 ){
        if(!$time){
            $time = $_SERVER['REQUEST_TIME'];
        }
        $data_merchant_workers_money['merchant_worker_id'] = $merchant_worker_id;
        $data_merchant_workers_money['type'] = $type;
        $data_merchant_workers_money['money'] = $money;
        $data_merchant_workers_money['desc'] = $msg;
        $data_merchant_workers_money['time'] = $time;
        if($_SESSION['system']['id'] && $admin){
            $data_merchant_workers_money['admin_id'] = $_SESSION['system']['id'];
        }
        if($record_ip){
            $data_merchant_workers_money['ip'] = get_client_ip(1);
        }

        if(M('Merchant_workers_money')->data($data_merchant_workers_money)->add()){
            return true;
        }else{
            return false;
        }
    }

}

?>