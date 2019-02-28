<?php
class GiftAction extends BaseAction{
    public function _initialize(){
        parent::_initialize();

        //分类列表start
        $gift_cat_condition['is_del'] = 0;
        $gift_cat_condition['status'] = 1;
        $gift_cat_condition['cat_fid'] = 0;
        $gift_category_list = D('Gift_category')->gift_category_page_list($gift_cat_condition , true , 'cat_sort desc' , 999);

        unset($gift_cat_condition['cat_fid']);
        $gift_cat_condition['cat_id'] = $_GET['cat_id'] + 0;
        $now_nav_gift_category = D('Gift_category')->gift_category_detail($gift_cat_condition);
        //分类列表end

        $database_user = D('User');
        $now_user = $database_user->get_user($this->user_session['uid']);
        $this->assign('now_user',$now_user);

        $this->assign('gift_category_list' , $gift_category_list['list']);
        $this->assign('now_nav_gift_category' , $now_nav_gift_category['detail']);
    }
    public function index(){
        $database_gift_category = D('Gift_category');
        $database_adver = D('Adver');
        $database_gift = D('Gift');

        //导航banner列表start
        $gift_slider_list = $database_adver->get_adver_by_key('web_gift_slider');
        //导航banner列表end

        //精选生活start
        $gift_cat_condition['cat_fid'] = array('neq' , 0);
        $gift_cat_condition['is_hot'] = 1;
        $gift_cat_condition['is_del'] = 0;
        $gift_cat_condition['cat_status'] = 1 ;
        $good_gift_category_list = $database_gift_category->gift_category_page_list($gift_cat_condition , true , 'cat_sort desc' , 8);
        //精选生活end

        //快捷兑换start
        $gift_condition['is_del'] = 0;
        $gift_condition['status'] = 1;
        $gift_list = $database_gift->gift_page_list($gift_condition , true ,'exchanged_num desc , sort desc', 9);
        //快捷兑换end


        //高端生活start
        $gift_condition['exchange_type'] = 0;
        $gift_condition['status'] = 1;
        $integral_gift_list = $database_gift->gift_page_list($gift_condition , true ,'sort desc', 10);
        //高端生活end


        //hot兑换排行榜start
        $this->_get_new_gift_list();
        //hot兑换排行榜end

        //unset($gift_condition['exchange_type']);
        //$gift_condition['cat_fid'] = 0;
        //$sport_gift_list = $database_gift->gift_page_list($gift_condition , true ,'exchanged_num desc', 5);
        $gift_index_list = $database_gift->getIndexGiftList(5);

        $this->assign('gift_slider_list' , $gift_slider_list);
        $this->assign('good_gift_category_list' , $good_gift_category_list['list']);
        $this->assign('gift_list' , $gift_list['list']);
        $this->assign('integral_gift_list' , $integral_gift_list['list']);
        //$this->assign('sport_gift_list' , $sport_gift_list['list']);
        $this->assign('gift_index_list' , $gift_index_list);
        $this->display();
    }


    public function gift_list(){
        $cat_id = $_GET['cat_id'] + 0;
        $integral_start = $_GET['integral_start'] + 0;
        $integral_end = $_GET['integral_end'] + 0;
        $exchange_type = $_GET['exchange_type'] + 0;
        $order = safeValue($_GET['order']);
        $type = safeValue($_GET['type']);

        if(empty($cat_id) && empty($type)){
            $this->error('传递参数有误！');
        }

        $database_gift_category = D('Gift_category');
        $database_gift = D('Gift');

        $gift_cat_condition['is_del'] = 0;
        $gift_cat_condition['cat_status'] = 1;

        if(!empty($cat_id)){
            $gift_cat_condition['cat_id'] = $cat_id;
        }


        $son_category_list = $database_gift_category->get_son_category($cat_id);
        $now_gift_category = $database_gift_category->gift_category_detail($gift_cat_condition);
        if($parent_gift_category_id = $now_gift_category['detail']['cat_fid']){
            $gift_cat_condition['cat_id'] = $parent_gift_category_id;
            $parent_gift_category = $database_gift_category->gift_category_detail($gift_cat_condition);
            if(!empty($parent_gift_category)){
                $this->assign('parent_gift_category' , $parent_gift_category['detail']);
            }

            $son_category_list = $database_gift_category->get_son_category($parent_gift_category_id);
        }

        if(empty($type)){
            if(($now_gift_category['detail']['cat_fid'] == 0)){
                $gift_condition['cat_fid'] = $cat_id;
            }else{
                $gift_condition['cat_id'] = $cat_id;
            }
        }

        if($exchange_type != 2){
            $gift_condition['exchange_type'] = $exchange_type;
        }

        $gift_condition['is_del'] = 0;

        if(empty($order)){
            $gift_order = 'sort desc';
        }else{
            if($exchange_type == 0){
                $gift_order = 'payment_pure_integral ' . $order;
            }elseif($exchange_type == 1){
                $gift_order = 'payment_integral ' . $order;
            }else{
                $gift_order = 'exchange_type asc,payment_pure_integral ' . $order.',payment_integral '. $order;
            }

        }

        if(!empty($type)){
            switch ($type) {
                case 'hot':
                    if(empty($this->user_session)){
                        redirect(U('Login/index'));
                    }

                    $now_user = D('User')->get_user($this->user_session['uid']);
                    if(in_array($exchange_type,array(0,2))){
                        $gift_condition['payment_pure_integral'] = array('lt',$now_user['score_count']);
                    }elseif($exchange_type == 1){
                        $gift_condition['payment_integral'] = array('lt',$now_user['score_count']);
                    }
                    break;
            }
        }

        if(!empty($integral_start) && !empty($integral_end)){
            if($exchange_type == 0){
                $gift_condition['payment_pure_integral'] = array('between',array($integral_start,$integral_end));
            }elseif($exchange_type == 1){
                $gift_condition['payment_integral'] = array('between',array($integral_start,$integral_end));
            }else{
                $gift_condition['_string'] = '(((payment_pure_integral >='.$integral_start.') AND (payment_pure_integral <'.$integral_end.')) AND (exchange_type = 0)) OR (((payment_integral >='.$integral_start.') AND (payment_integral <'.$integral_end.')) AND (exchange_type = 1))';
            }
        }else if(!empty($integral_start)){
            if($exchange_type == 0){
                $gift_condition['payment_pure_integral'] = array('gt',$integral_start);
            }elseif($exchange_type == 1){
                $gift_condition['payment_integral'] = array('gt',$integral_start);
            }else{
                $gift_condition['_string'] = '((payment_pure_integral >='.$integral_start.') AND exchange_type = 0) OR (payment_integral >'.$integral_start.' ) AND exchange_type = 1)';
            }
        }else if(!empty($integral_end)){
            if($exchange_type == 0){
                $gift_condition['payment_pure_integral'] = array('lt',$integral_end);
            }elseif($exchange_type == 1){
                $gift_condition['payment_integral'] = array('lt',$integral_end);
            }else{
                $gift_condition['_string'] = '((payment_pure_integral <'.$integral_end.') AND (exchange_type = 0)) OR ((payment_integral <'.$integral_end.' ) AND (exchange_type = 1))';
            }
        }


        $gift_condition['status'] = 1;
        $gift_list = $database_gift->gift_page_list($gift_condition , true ,$gift_order);

        $this->assign('now_gift_category' , $now_gift_category['detail']);
        $this->assign('son_category_list' , $son_category_list);

        $this->assign('gift_list' , $gift_list['list']);
        $this->display();
    }


    public function gift_detail(){
        $gift_id = $_GET['gift_id'] + 0;
        if(empty($gift_id)){
            $this->error('传递参数有误！');
        }

        $database_gift = D('Gift');
        $database_gift_category = D('Gift_category');

        $gift_condition['gift_id'] = $gift_id;
        $gift_condition['is_del'] = 0;
        $gift_condition['status'] = 1;
        $gift_detail = $database_gift->gift_detail($gift_condition);

        if(empty($gift_detail['status'])){
            $this->error('该礼品不存在！');
        }

        $gift_detail = $gift_detail['detail'];
        $gift_detail['specification'] = explode("\r\n" , $gift_detail['specification']);

        $this->_get_new_record($gift_detail['gift_id']);
        $gift_cat_condition['is_del'] = 0;

        $gift_cat_condition['cat_status'] = 1;
        $gift_cat_condition['cat_id'] = $gift_detail['cat_id'];
        $now_gift_category = $database_gift_category->where($gift_cat_condition)->find();
        $gift_cat_condition['cat_id'] = $gift_detail['cat_fid'];
        $top_gift_category = $database_gift_category->where($gift_cat_condition)->find();

        $this->_get_new_gift_list();

        $this->assign('gift_detail' , $gift_detail);
        $this->assign('now_gift_category',$now_gift_category);
        $this->assign('top_gift_category',$top_gift_category);
        $this->display();
    }


    public function gift_buy(){
        if(empty($this->user_session)){
            $this->assign('jumpUrl',U('Index/Login/index'));
            $this->error('请先登录！');
        }

        $gift_id = $_GET['gift_id'] + 0;
        if(empty($gift_id)){
            $this->error('传递参数有误！');
        }

        $database_user = D('User');
        $database_gift = D('Gift');
        $gift_where['gift_id'] = $gift_id;
        $gift_where['is_del'] = 0;
        $gift_where['status'] = 1;
        $gift_detail = $database_gift->gift_detail($gift_where);
        $now_user = $database_user->get_user($this->user_session['uid']);

        if($now_user['score_count']<$gift_detail['detail']['payment_pure_integral']){
            $this->error('您的'.$this->config['score_name'].'暂时不足，暂时无法兑换！');
        }

        if(empty($gift_detail['status'])){
            $this->error('该礼品暂时不存在！');
        }

        $this->assign('gift_detail' , $gift_detail['detail']);

        $this->_get_recommend_gift($gift_detail['detail']['cat_id']);

        $this->display();
    }

    public function check_gift_buy(){
        $gift_id = $_GET['gift_id'] + 0;
        if(empty($gift_id)){
            $this->error('传递参数有误！');
        }
        $database_gift = D('Gift');
        $gift_where['gift_id'] = $gift_id;
        $gift_where['is_del'] = 0;
        $gift_where['status'] = 1;
        $gift_detail = $database_gift->gift_detail($gift_where);
        if(empty($gift_detail['status'])){
            $this->error('该礼品不存在！');
        }

        $adress_list = D('User_adress')->get_adress_list($this->user_session['uid']);
        $this->assign('adress_list',$adress_list);
        $this->assign('gift_detail',$gift_detail['detail']);

        $this->_get_recommend_gift($gift_detail['detail']['cat_id']);

        $this->display();
    }

    public function order(){
        if(IS_POST){
            if(empty($this->user_session)){
                $this->assign('jumpUrl',U('Index/Login/index'));
                $this->error('请先登录！');
            }

            $gift_id = $_POST['gift_id'] + 0;

            $database_gift = D('Gift');
            $database_gift_order = D('Gift_order');
            $gift_condition['gift_id'] = $gift_id;
            $gift_condition['is_del'] = 0;
            $gift_condition['status'] = 1;
            $gift_detail = $database_gift->gift_detail($gift_condition);

            if(empty($gift_detail['status'])){
                $this->error('该礼品不存在！');
            }

            $result = $database_gift_order->save_post_form($gift_detail['detail'],$this->user_session['uid'] , 0);

            if($result['flag']){
                $this->error('您的帐户余额不足以兑换此奖品！请先进行充值！','/index.php?g=User&c=Credit&a=index');
            }else{
                if($result['error'] == 1){
                    $this->error($result['msg']);
                }
            }
            $this->redirect('gift_order_view',array('order_id'=>$result['order_id']));
            //redirect(U('Index/Pay/check',array('order_id'=>$result['order_id'],'type'=>'gift')));
        }else{
            $this->error('访问页面有误！~~~');
        }
    }


    public function gift_order_view(){
        $order_id = $_GET['order_id'] + 0;

        $database_gift = D('Gift');
        $database_gift_order = D('Gift_order');
        $uid = $this->user_session['uid'];
        $now_order = $database_gift_order->get_order_detail_by_id($uid,$order_id);
        $now_gift = $database_gift->where(array('gift_id'=>$now_order['gift_id']))->find();
        $this->assign('now_gift',$now_gift);
        $now_order['order_type'] = 'gift';
        $laste_order_info=D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
        if(!$now_order['paid'] && !empty($laste_order_info)) {
            if ($laste_order_info['pay_type']=='weixin') {
                $redirctUrl = C('config.site_url') . '/index.php?g=Index&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
                file_get_contents($redirctUrl);
                $now_order = $database_gift_order->get_order_detail_by_id($this->user_session['uid'], intval($_GET['order_id']), true);
            }
        }
        if(empty($now_order)){
            $this->error('当前订单不存在！',U('Index/index'));
        }

        $this->assign('now_order',$now_order);
        $this->display();
    }


    private function _get_new_gift_list(){
        $database_gift = D('Gift');

        $where['is_del'] = 0;
        $where['status'] = 1;
        $where['is_new'] = 1;
        $new_gift_list = $database_gift->gift_page_list($where , true ,'exchanged_num desc', 3);
        $this->assign('new_gift_list' , $new_gift_list['list']);
    }

    //最近浏览
    private function _get_new_record($gift_id){
        if(empty($_SESSION['gift_id_arr'])){
            $gift_id_arr = array();
        }else{
            $gift_id_arr = $_SESSION['gift_id_arr'];
        }

        array_unshift($gift_id_arr , $gift_id);
        $_SESSION['gift_id_arr'] = $gift_id_arr;

        $database_gift = D('Gift');

        $gift_condition['is_del'] = 0;
        $gift_condition['status'] = 1;
        $gift_condition['gift_id'] = array('in',array_filter($gift_id_arr));
        $gift_record_list = $database_gift->gift_page_list($gift_condition , true ,'exchanged_num desc', 5);
        $this->assign('gift_record_list' , $gift_record_list['list']);
    }

    //同款推荐
    private function _get_recommend_gift($cat_id){
        $database_gift = D('Gift');
        $recommend_condition['cat_id'] = $cat_id;
        $recommend_condition['is_del'] = 0;
        $recommend_condition['status'] = 1;
        $recommend_gift_list = $database_gift->gift_page_list($recommend_condition);
        $this->assign('recommend_gift_list' , $recommend_gift_list['list']);
    }
}
?>