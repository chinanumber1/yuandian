<?php
/*
 * IM聊天
 *
 */
class MyAction extends BaseAction{
    /* 我的 */
    public function my(){
        $activity_arr = array();
        $ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
            if(empty($now_user['avatar'])){
                $now_user['avatar']   =   $this->config['site_url'] . '/static/images/user_avatar.jpg';
            }

            if(!empty($now_user)){
                $activity_arr[][] = array(
                    'code_title'=>  urlencode('会员中心'),
                    'Ctitle'    =>  '会员中心',
                    'phone'     =>  $now_user['phone'],
                    'nickname'  =>  $now_user['nickname'],
                    'now_user'  =>  $now_user['now_user'],
                    'now_money' =>  floatval($now_user['now_money']),
                    'score_count'=> floatval($now_user['score_count']),
                    'url'       =>  $this->config['site_url'].U('My/myinfo'),
                    'status'    =>  $now_user['status'],
                    'level'     =>  $now_user['level'],
                    'avatar'    =>  $now_user['avatar'],
                    'bg'        =>  $this->config['site_url'].'/tpl/Wap/default/static/images/my-photo.png',
                );
            }else{
                $activity_arr[][]   =   array();
            }
        }else{
            $activity_arr[][]   =   array();
        }

        //多行
        $order_array = array(
            array(
                'title'=>$this->config['group_alias_name'].'订单',
                'intro'=>$this->config['group_alias_name'].'订单',
                'image'=> $this->config['site_url'] . '/static/images/im/tubaio1_03.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'] .  '/wap.php?g=Wap&c=My&a=group_order_list',
            ),
            array(
                'title'=>$this->config['meal_alias_name'].'订单',
                'intro'=>$this->config['meal_alias_name'].'订单',
                'image'=> $this->config['site_url'] . '/static/images/im/tubiao_13.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'] .  '/wap.php?g=Wap&c=My&a=meal_order_list',
            ),
            array(
                'title'=>'预约订单',
                'intro'=>'预约订单',
                'image'=> $this->config['site_url'] . '/static/images/im/tubiao_33.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'] .  '/wap.php?g=Wap&c=My&a=appoint_order_list',
            ),
        );
        if($this->config['live_service_appid']){
            $order_array[] = array(
                'title'=>'生活缴费订单',
                'intro'=>'生活缴费订单',
                'image'=> $this->config['site_url'] . '/static/images/im/tubiao_14.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'] .  '/wap.php?g=Wap&c=My&a=lifeservice',
            );
        }
        $activity_arr[] = isset($order_array) ? $order_array : array();

        //多行
        $activity_arr[] = array(
            array(
                    'title'=>'我的收藏',
                    'intro'=>'我的收藏',
                    'image'=> $this->config['site_url'] . '/static/images/im/tubaio1_06.png', /*图片暂定为 26*26的像素 */
                    'url' => $this->config['site_url'] . '/wap.php?g=Wap&c=My&a=group_collect',
            ),
            array(
                'title'=>'我关注的商家',
                'intro'=>'我关注的商家',
                'image'=> $this->config['site_url'] . '/static/images/im/tubiao_15.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'] .  '/wap.php?g=Wap&c=My&a=follow_merchant',
            ),
            array(
                'title'=>'我参与的活动',
                'intro'=>'我参与的活动',
                'image'=> $this->config['site_url'] . '/static/images/im/tubiao_16.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'] .  '/wap.php?g=Wap&c=My&a=join_lottery',
            ),
        );
        //多行
        $activity_arr[] = array(
            array(
                    'title'=>'我的优惠券',
                    'intro'=>'我的优惠券',
                    'image'=> $this->config['site_url'] . '/static/images/im/tubaio1_10.png', /*图片暂定为 26*26的像素 */
                    'url' => $this->config['site_url'] . '/wap.php?g=Wap&c=My&a=card_list',
            ),
            array(
                'title'=>'我的会员卡',
                'intro'=>'我的会员卡',
                'image'=> $this->config['site_url'] . '/static/images/im/tubiao_17.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'] .  '/wap.php?g=Wap&c=My&a=cards',
            ),
        );
        //检测是否有分类信息
        if(isset($this->config['wap_home_show_classify'])){
            $activity_arr[] = array(
                    'title'=>'我的发布',
                    'intro'=>'我的发布',
                    'image'=> $this->config['site_url'] . '/static/images/im/tubiao_19.png', /*图片暂定为 26*26的像素 */
                    'url'=> $this->config['site_url'] .  '/wap.php?c=Classify&a=myCenter',
            );
        }

        $this->returnCode(0,$activity_arr);
    }
    /*选择优惠券*/
    public function select_card(){
        //以下代码是为了得到商户的mer_id ，并且判断此订单是否存在！
        //$uid    =   I('uid',false);
//        if($uid){
//            $this->error_tips('用户uid不能为空');
//        }
//        $this->user_session['uid'] = $uid;
        $ticket = I('ticket', false);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }
        if(empty($this->user_session)){
            $this->returnCode('20044010');
        }
        $_GET['type'] = I('type');
        $_GET['order_id']   =   I('order_id');
        if($_GET['type'] == 'group'){
            $now_order = D('Group_order')->get_order_by_id($this->user_session['uid'],$_GET['order_id']);
        }else if($_GET['type'] == 'meal' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad' || $_GET['type'] == 'takeout'){
            $now_order = D('Meal_order')->get_order_by_id($this->user_session['uid'],$_GET['order_id']);
        }else if($_GET['type'] == 'weidian'){
            $now_order = D('Weidian_order')->get_order_by_id($this->user_session['uid'],$_GET['order_id']);
        }else if($_GET['type'] == 'appoint'){
            $now_order = D('Appoint_order')->get_order_by_id($this->user_session['uid'],$_GET['order_id']);
        }else if($_GET['type'] == 'shop'){
            $now_order = D('Shop_order')->get_order_by_id($this->user_session['uid'],$_GET['order_id']);
        }else{
            //$this->error_tips('来源非法，请检查后再访问。');
            $this->returnCode('20031005');
        }
        if(empty($now_order)){
            $this->returnCode('20031004');
        }
        //$arr['back_url']  =   $this->config['site_url'].U('Pay/check',$_GET);
        $card_list = D('Member_card_coupon')->get_coupon($now_order['mer_id'],$this->user_session['uid']);

        if(!empty($card_list)){
            foreach($card_list as &$value){
                $value['card_id'] =$value['record_id'];
                $value['pic']   =   $this->config['site_url'].$value['pic'];
            }
        }else{
            $card_list = array();
        }
        $arr = isset($card_list) ? $card_list:null;
        $this->returnCode(0,$arr);
    }

    //平台优惠券
    public function select_coupon(){
        $ticket = I('ticket', false);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }
        if(empty($this->user_session)){
            $this->returnCode('20044010');
        }else{
            $now_user = D('User')->get_user($this->user_session['uid']);
            if(empty($now_user)){
                $this->returnCode('20044010');
            }
        }

        $_GET['type'] = I('type');
        $_GET['order_id'] = I('order_id');
        if ($_GET['type'] == 'group') {
            $now_order = D('Group_order')->get_order_by_id($this->user_session['uid'], $_GET['order_id']);
        } else if ($_GET['type'] == 'meal' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad' || $_GET['type'] == 'takeout') {
            $now_order = D('Meal_order')->get_order_by_id($this->user_session['uid'], $_GET['order_id']);
        } else if ($_GET['type'] == 'weidian') {
            $now_order = D('Weidian_order')->get_order_by_id($this->user_session['uid'], $_GET['order_id']);
        } else if ($_GET['type'] == 'appoint') {
            $now_order = D('Appoint_order')->get_order_by_id($this->user_session['uid'], $_GET['order_id']);
        } else if($_GET['type'] == 'shop'){
            $now_order = D('Shop_order')->get_order_by_id($this->user_session['uid'],$_GET['order_id']);
        } else {
            $this->returnCode('20031005');
        }
        if (empty($now_order)) {
            $this->returnCode('20031004');
        }
        //判断平台
        if($this->is_app_browser){
            $platform = 'app';
        }else if($this->is_wexin_browser){
            $platform = 'weixin';
        }else{
            $platform = 'wap';
        }
        $coupone_list = D('System_coupon')->get_noworder_coupon_list($now_order,I('type'),$now_user['phone'],$now_user['uid'],$platform);
        foreach($coupone_list as $k=>&$v){
            if($v['cate_id']!='0'){
                $v['cate_name'].=$v['cate_id']['cat_name'];
            }
            if($v['cate_name']=='all'){
                $v['cate_name']='全通类优惠券';
            }
            if($v['is_discount']){
                $v['coupon_des'] = ($v['order_money']>0?'满'.$v['order_money'].'元':'').'打'.$v['discount_value'].'折';
            }else{
                $v['coupon_des'] = ($v['order_money']>0?'满'.$v['order_money'].'元':'').'减'.$v['discount'].'元';
            }
            unset($coupone_list[$k]['cate_id']);
        }

        $arr =  isset($coupone_list) ? $coupone_list:array();
        $this->returnCode(0,$arr);
    }

    //新的版本优惠券接口

    public function new_select_coupon(){
        $ticket = I('ticket', false);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }
        if(empty($this->user_session)){
            $this->returnCode('20044010');
        }else{
            $now_user = D('User')->get_user($this->user_session['uid']);
            if(empty($now_user)){
                $this->returnCode('20044010');
            }
        }

        $_GET['type'] = I('type');
        $_GET['order_id'] = I('order_id');
        $_GET['coupon_type'] = I('coupon_type');
        $_GET['merc_id'] = I('merchant_coupon_id');
        $_GET['sysc_id'] = I('system_coupon_id');
        $_GET['card_id'] = I('card_id');

        if($_GET['type'] == 'group'){
            $now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
            if($now_order['order_info']['group_share_num']>0||$now_order['order_info']['pin_num']>0)$group_pay_offline=false;
        }else if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad'){
            $now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']), false, $_GET['type']);
            if ($now_order['order_info']['paid'] == 2) $this->assign('notCard',true);
            $_GET['type']  = 'meal';
        }else if($_GET['type'] == 'weidian'){
            $now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
            $this->assign('notCard',true);
        }else if($_GET['type'] == 'recharge'){
            $now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
            $this->assign('notCard',true);
        }else if($_GET['type'] == 'appoint'){
            $now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
        }else if($_GET['type'] == 'wxapp'){
            $_GET['notOffline'] = true;
            $now_order = D('Wxapp_order')->get_pay_order($_GET['uid'],intval($_GET['order_id']));
            $this->assign('notCard',true);
        }else if($_GET['type'] == 'store'){
            $_GET['notOffline'] = true;
            $now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
            //$this->assign('notCard',true);
        }else if($_GET['type'] == 'shop'){
            $now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
        }else if($_GET['type'] == 'plat'){
            $now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
        }else if($_GET['type'] == 'balance-appoint'){
            $now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval($_GET['order_id']));
        }else{
			$this->returnCode('20044010','非法的订单');
        }

        $now_order = $now_order['order_info'];
        $tmp_order = $now_order;
        $tmp_order['uid'] = $this->user_session['uid'];
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->user_session['uid'], $now_order['mer_id']);

        if($card_info['discount']<=0||$card_info['discount']>10||empty($card_info['discount'])){
            $card_info['discount'] = 10;
        }
        $tmp_order['total_money'] = $tmp_order['order_total_money']*$card_info['discount']/10;
        if (empty($now_order)) {
            $this->returnCode('20031004');
        }
        //判断平台
        $platform = 'app';

        if($_GET['coupon_type']=='mer') {
            //$card_list = D('Member_card_coupon')->get_coupon($now_order['mer_id'], $this->user_session['uid']);
            if($_GET['sysc_id']){
                $sys_coupon = D('System_coupon')->get_coupon_by_id($_GET['sysc_id']);
                $tmp_order['total_money'] -= $sys_coupon['discount'];
            }

            if(!empty($now_order['business_type'])){
                $coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order,$_GET['type'],$platform,$now_order['business_type']);
            }else{
                $coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'],$platform);
            }
        }else if($_GET['coupon_type']=='system') {
            if($_GET['merc_id']){
                $mer_coupon = D('Card_new_coupon')->get_coupon_by_id($_GET['merc_id']);
                $tmp_order['total_money'] -= $mer_coupon['discount'];
            }

            if(!empty($now_order['business_type'])) {
                $coupon_list = D('System_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform,$now_order['business_type']);
            }else{
                $coupon_list = D('System_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform);
            }

        }

        foreach($coupon_list as $k=>&$v){
            if($v['cate_id']!='0'){
                $v['cate_name'].=$v['cate_id']['cat_name'];
            }
            if($v['cate_name']=='all'){
                $v['cate_name']='全通类优惠券';
            }
            if($v['is_discount']){
                $v['coupon_des'] = ($v['order_money']>0?'满'.floatval($v['order_money']).'元':'').'打'.floatval($v['discount_value']).'折';
            }else{
                $v['coupon_des'] = ($v['order_money']>0?'满'.floatval($v['order_money']).'元':'').'减'.floatval($v['discount']).'元';
            }
            $tmp_coupon_list['discount_value'] = floatval($v['discount_value']);
            $tmp_coupon_list['is_discount'] = $v['is_discount'];
            $tmp_coupon_list['coupon_des'] = $v['coupon_des'];
            $tmp_coupon_list['name'] = $v['name'];
            $tmp_coupon_list['discount'] = $v['discount'];
            $tmp_coupon_list['order_money'] = $v['order_money'];
            $tmp_coupon_list['had_id'] = $v['id'];
           // $tmp_coupon_list['coupon_id'] = $v['coupon_id'];
            if($_GET['coupon_type']=='mer'){
                $tmp_coupon_list['img'] = $this->config['site_url'].$v['img'];
            }
            $tmp_coupon_list['platform'] = $v['platform'];
            $tmp_coupon_list['des'] = $v['des'];
            $tmp_coupon_list['cate_name'] = $v['cate_name'];
            $tmp_coupon_list['time'] = '有效期至'.date('y.m.d',$v['end_time']);
            $coupon_list_[] = $tmp_coupon_list;
            unset($coupon_list[$k]['cate_id']);
        }

        $arr =  isset($coupon_list_) ? $coupon_list_:array();
        $this->returnCode(0,$arr);
    }



	//	我的新版页面
    public function my_new(){
        $activity_arr = array();
        if($this->_uid){
            $now_user = D('User')->get_user($this->_uid);
            if(empty($now_user['avatar'])){
                $now_user['avatar']   =   $this->config['site_url'] . '/static/images/user_avatar.jpg';
            }
            $level = M('User_level')->getField('level,lname');
            $now_user['lname'] = $now_user['level']?$level[$now_user['level']]:'VIP0';



			//	我的信息
            if(!empty($now_user)){
            	if($now_user['phone']){
					$left	=	substr($now_user['phone'],0,3);
					$right	=	substr($now_user['phone'],strlen($now_user['phone'])-4);
					$now_user['phone_s']	=	$left.'****'.$right;
            	}else{
					$now_user['phone_s']	=	'';
            	}

                $recently_sign = M('User_sign')->where(array('uid'=>$now_user['uid']))->order('id DESC')->find();
                $today_sing = 0;
                if($recently_sign && strtotime(date('Ymd',$_SERVER['REQUEST_TIME']))==strtotime(date('Ymd',$recently_sign['sign_time']))){
                    $today_sing = 1;
                }

                $activity_arr['user'] = array(
                    'phone_s'   =>  $now_user['phone_s'],
                    'phone'     =>  $now_user['phone'],
                    'nickname'  =>  $now_user['nickname'],
                    'now_money' =>  strval(floatval($now_user['now_money'])),
                    'score_count'=> strval(floatval($now_user['score_count'])),
                    'level'     =>  $now_user['lname'],
                    'avatar'    =>  $now_user['avatar'],
                    'bg'        =>  $this->config['site_url'].'/tpl/Wap/pure/static/images/my-photo.png',
                    'sign_today'        => $today_sing,
                    'frozen_money'        => 0,
                    'frozen_reason'        => '',
                    'frozen_time'        => '',
                );

                if($now_user['frozen_money'] > 0 && $this->config['open_frozen_money'] == 1 && $now_user['free_time']>$_SERVER['REQUEST_TIME']){
                    $activity_arr['user']['frozen_money'] = strval($now_user['frozen_money']);
                    $activity_arr['user']['frozen_reason'] = $now_user['frozen_reason'];
                    $activity_arr['user']['frozen_time'] = date('Y-m-d',$now_user['frozen_time']).'-'.date('Y-m-d',$now_user['free_time']);
                }
                if($this->config['open_score_fenrun']){
                    $activity_arr['user']['fenrun_money']  = $now_user['fenrun_money'];
                    $activity_arr['user']['fenrun_name']  = '分润钱包';
                    $activity_arr['user']['fenrun_url']  = $this->config['site_url'].'/wap.php?g=Wap&c=Fenrun&a=fenrun_money_list';

                    $activity_arr['user']['fenrun_now_money']  = (string)floatval($now_user['now_money']);
                    $activity_arr['user']['fenrun_now_money_name']  = '余额';
                    $activity_arr['user']['fenrun_now_money_url']  = $this->config['site_url'].'/wap.php?g=Wap&c=My&a=money_list';

                    $activity_arr['user']['fenrun_score']  =  floatval($now_user['score_count']);
                    $activity_arr['user']['fenrun_score_name']  = '积分';
                    $activity_arr['user']['fenrun_score_url']  = $this->config['site_url'].'/wap.php?g=Wap&c=My&a=score_list';

                    $activity_arr['user']['fenrun_level']  = $now_user['level'];
                    $activity_arr['user']['fenrun_level_name']  = '等级';
                    $activity_arr['user']['fenrun_level_url']  = $this->config['site_url'].'/wap.php?g=Wap&c=My&a=levelUpdate';
                }else{
                    $activity_arr['user_link']['now_money']  = (string)floatval($now_user['now_money']);
                    $activity_arr['user_link']['now_money_name']  = '余额';
                    $activity_arr['user_link']['now_money_url']  = $this->config['site_url'].'/wap.php?g=Wap&c=My&a=money_list';

                    $activity_arr['user_link']['score']  =  floatval($now_user['score_count']);
                    $activity_arr['user_link']['score_name']  = '积分';
                    $activity_arr['user_link']['score_url']  = $this->config['site_url'].'/wap.php?g=Wap&c=My&a=score_list';

                    $activity_arr['user_link']['level']  = $now_user['level'];
                    $activity_arr['user_link']['level_name']  = '等级';
                    $activity_arr['user_link']['level_url']  = $this->config['site_url'].'/wap.php?g=Wap&c=My&a=levelUpdate';
                }
                if(isset($config['specificfield'])){
					$activity_arr['user']['is_data']	=	1;
					$activity_arr['user']['data_url']	=	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=inputinfo';
                }else{
					$activity_arr['user']['is_data']	=	0;
                }
            }else{
                $this->returnCode('20044011');
            }
        }else{
            $this->returnCode('20046009');
        }
        //我的佣金
        if($this->config['open_score_fenrun']){
            $activity_arr['fenrun'] = array(
                array(
                   // 'title'=>'可用佣金',
                    'money'=>$now_user['free_award_money'],
                    //'image'=> $this->config['site_url'].'、tpl/Wap/pure/static/images/grzx_03.png', /*图片暂定为 26*26的像素 */
                    'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=Fenrun&a=user_free_award_list',
                ),
                array(
                   // 'title'=>'冻结佣金',
                    'money'=>$now_user['frozen_award_money'],
                    //'image'=> $this->config['site_url'].'、tpl/Wap/pure/static/images/grzx_05.png', /*图片暂定为 26*26的像素 */
                    'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=Fenrun&a=frozen_award_index',
                ),
            );
        }




	    //	我的订单
        $order_array = array(
            array(
                'title'=>$this->config['group_alias_name'].'订单',
                'image'=> $this->config['site_url'].'/static/images/my_new/tuangou.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=group_order_list',
            ),
			 array(
                'title'=>$this->config['shop_alias_name'].'订单',
                'image'=> $this->config['site_url'].'/static/images/my_new/kuaidian.png', /*图片暂定为 26*26的像素 */
                 'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=shop_order_list',
             ),
            array(
                'title'=>$this->config['meal_alias_name'].'订单',
                'image'=> $this->config['site_url'].'/static/images/my_new/canyin.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=foodshop_order_list',
            ),
        );



		if($this->config['appoint_page_row']){
			$order_array[] = array(
				'title'=>$this->config['appoint_alias_name'].'订单',
                'image'=> $this->config['site_url'].'/static/images/my_new/yuyue1.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=appoint_order_list',
            );
		}else if($this->config['live_service_appid']){
            $order_array[] = array(
                'title'=>'缴费订单',
                'image'=> $this->config['site_url'].'/tpl/Wap/default/static/images/new_my/pay.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=lifeservice',
            );
        }
		if(isset($this->config['wap_home_show_classify'])){
			$order_array[] = array(
				'title'=>'分类信息',
                'image'=> $this->config['site_url'].'/static/images/my_new/xinxi.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=classify_order_list',
            );
		}
		if($this->config['pay_in_store']){
			$order_array[] = array(
				'title'=>'到店付',
                'image'=> $this->config['site_url'].'/static/images/my_new/apply.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=store_order_list',
            );
		}

		$order_array[] = array(
			'title'=>$this->config['gift_alias_name'],
			'image'=> $this->config['site_url'].'/static/images/my_new/jifen.png', /*图片暂定为 26*26的像素 */
			'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=gift_order_list',
		);
        if($this->config['mobile_recharge_APIKey'] && $this->config['mobile_recharge_openid']){
            $order_array[] = array(
                'title'=>'话费订单',
                'image'=> $this->config['site_url'].'/static/images/my_new/huafei.png',
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=Third_recharge&a=mobile_recharge_list'
            );
        }

        $order_array[] = array(
            'title'=>'服务快派',
            'image'=> $this->config['site_url'].'/static/images/my_new/kuaipai.png',
            'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=Service&a=need_list_app'
        );

        $activity_arr['order'] = isset($order_array) ? $order_array : array();

        $collect_count = D('User_collect')->get_collect_num($this->_uid,$now_user['openid']);

		//	我的收藏
        $activity_arr['collection'] = array(
        	array(
                'title'=>'关注商家',
                'count'=>intval($collect_count['merchant_count']),
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=follow_merchant',
            ),
            array(
            	'title'=>$this->config['group_alias_name'].'收藏',
                'count'=>intval($collect_count['group_count']),
                'url' => $this->config['site_url'].'/wap.php?g=Wap&c=My&a=group_collect',
            ),
			array(
            	'title'=>$this->config['meal_alias_name'].'收藏',
                'count'=>intval($collect_count['meal_count']),
                'url' => $this->config['site_url'].'/wap.php?g=Wap&c=My&a=group_store_collect',
            ),
            array(
            	'title'=>$this->config['appoint_alias_name'].'收藏',
                'count'=>intval($collect_count['appoint_count']),
                'url' => $this->config['site_url'].'/wap.php?g=Wap&c=My&a=appoint_collect',
            ),
        );
        $site_phone = empty($this->config['site_phone'])?array():explode(' ',$this->config['site_phone']);
        $activity_arr['kefu']['phone'] =$site_phone;

        if ($this->DEVICE_ID == 'wxapp' && $this->config['open_kefu_url'] && $services = D('Customer_service')->where(array('mer_id' => $this->config['kefu_mer_id']))->select()) {
			$activity_arr['kefu']['url'] = $this->config['site_url'].'/wap.php?c=My&a=concact_kefu&mer_id='.$this->config['kefu_mer_id'];
		}

        if($this->config['show_scroll_msg']){
            $tmp_scroll =  D('Scroll_msg')->get_msg();
            foreach ($tmp_scroll as $v) {

                $activity_arr['scroll_msg'][] = $v['content'];
            }
            $activity_arr['scroll_image'] = $this->config['site_url'].'/static/images/my_new/scroll.png';
        }

        $uid	=	$this->_uid;
		//	商家优惠券
        $mer_list = D('Card_new_coupon')->get_user_all_coupon_list($uid,1);
		if($mer_list){
			$mer_number	=	count($mer_list);
		}else{
			$mer_number = 0;
		}

		//	平台优惠券
		$coupon_list = D('System_coupon')->get_user_coupon_list($uid,$this->user_session['phone'],1);
		$coupon_number	=	count($coupon_list);
        //	统计我参与的活动
       	$sql	=	"SELECT COUNT(*) AS tp_count FROM `pigcms_extension_activity_record` `ear`,`pigcms_extension_activity_list` `eal`,`pigcms_merchant` `m` WHERE(`ear`.`activity_list_id` = `eal`.`pigcms_id` AND `eal`.`mer_id` = `m`.`mer_id`AND `ear`.`uid` = '$uid') GROUP BY `eal`.`pigcms_id` ";
		$mod = new Model();
		$result = $mod->query($sql);
		$activity_number	=	count($result);
		//	统计我的会员卡
		$sql = 'SELECT c.card_id,c.bg,c.diybg,m.name,c.discount,cl.id as cardid,cl.card_money,cl.card_money_give,m.pic_info,m.mer_id FROM '
				.C('DB_PREFIX').'card_userlist `cl`  left join '
				.C('DB_PREFIX').'card_new `c` on cl.mer_id = c.mer_id left join '
				.C('DB_PREFIX').'merchant m on c.mer_id  = m.mer_id WHERE ( cl.uid = '.$uid.' AND c.status=1 AND cl.status=1 AND m.status=1 ) ';
		$res =  M('')->query($sql);
		foreach ($res as $v) {
			$tmp[$v['card_id']]['id'] = $v['cardid'];
		}
		$card_number = count($tmp);

        $activity_arr['discount'] = array(
            array(
                'title'=>'商家优惠券',
                'url' => $this->config['site_url'].'/wap.php?g=Wap&c=My&a=card_list&coupon_type=mer',
                'number'=>$mer_number,
                'pic_url'=> $this->config['site_url'].'/static/images/my_new/pingtai_quan.png',
            ),
            array(
                'title'=>'平台优惠券',
                'url' => $this->config['site_url'].'/wap.php?g=Wap&c=My&a=card_list&coupon_type=system',
                'number'=>$coupon_number,
                'pic_url'=> $this->config['site_url'].'/static/images/my_new/shangjia_quan.png',
            ),
            array(
                'title'=>'参与活动',
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=join_activity',
                'number'=>$activity_number,
                'pic_url'=> $this->config['site_url'].'/static/images/my_new/add_group.png',
            ),
            array(
                'title'=>'会员卡',
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=cards',
                'number'=>$card_number,
                'pic_url'=> $this->config['site_url'].'/static/images/my_new/card.png',
            ),
        );
        // 我的发布
        if($this->config['open_distributor']==1){

                $activity_arr['release'][] = array(
                    'title'=>'我是'.$this->config['agent_alias_name'],
                    'image'=> $this->config['site_url'].'/tpl/Wap/default/images/new_my/tubiao2_11.png', /*图片暂定为 26*26的像素 */
                    'url'=> $this->config['site_url'].'/wap.php?c=Distributor_agent&a=agent',
                );


                $activity_arr['release'][] = array(
                    'title'=>'我是'.$this->config['distributor_alias_name'],
                    'image'=> $this->config['site_url'].'/tpl/Wap/default/images/new_my/tubiao2_11.png', /*图片暂定为 26*26的像素 */
                    'url'=> $this->config['site_url'].'/wap.php?c=Distributor_agent&a=index',
                );


        }

        if($this->config['open_score_fenrun']){
            $activity_arr['release'][] = array(
                'title'=>'推广有奖',
                'image'=> $this->config['site_url'].'/static/images/my_new/share.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'].'/wap.php?c=My&a=my_spread_code',
            );
        }
        //app红包记录
        if($this->config['open_app_redpack']){
            $activity_arr['release'][] = array(
                'title'=>'红包记录',
                'image'=> $this->config['site_url'].'/static/images/my_new/hongbao.png', /*图片暂定为 26*26的像素 */
                'url'=> $this->config['site_url'].'/wap.php?c=My&a=redpack_list',
            );
        }
        if($this->config['open_user_spread']) {
            $activity_arr['release'][] = array(
                'title' => '我的推广',
                'image' => $this->config['site_url'] . '/static/images/my_new/tuiguang.png', /*图片暂定为 26*26的像素 */
                'url'   => $this->config['site_url'] . '/wap.php?c=My&a=my_spread',
            );
        }

        if($activity_arr['kefu']['phone'] || $activity_arr['kefu']['url'] && $device_id=='wxapp') {
            $activity_arr['release'][] = array(
                'title' => '在线客服',
                'image' => $this->config['site_url'] . '/static/images/my_new/my_kefu.png', /*图片暂定为 26*26的像素 */
                'url'   => '',
                'phone'   =>$this->config['site_phone'],
            );
        }
        if(isset($this->config['wap_home_show_classify'])){
//            if($now_user['openid']){
//				$activity_arr['release'][] = array(
//						'title'=>'我的推广二维码',
//						'image'=> $this->config['site_url'].'/tpl/Wap/default/static/images/new_my/qr_code.png', /*图片暂定为 26*26的像素 */
//						'url'=> $this->config['site_url'].'/wap.php?c=My&a=my_spread',
//				);
//			}
            $activity_arr['release'][] = array(
                    'title'=>'我的发布',
                    'image'=> $this->config['site_url'].'/static/images/my_new/fabu.png', /*图片暂定为 26*26的像素 */
                    'url'=> $this->config['site_url'].'/wap.php?c=Classify&a=myfabu&uid='.$this->uid,
            );
			$database_house_worker = D('House_worker');
			$house_worker_condition['status'] = 1;
			$house_worker_condition['openid'] = $now_user['openid'];
			$now_house_worker = $database_house_worker->where($house_worker_condition)->find();
			if(!empty($now_house_worker)){
				if($now_house_worker["type"] == 0){
					$house_name = '客服';
				}else{
					$house_name = '维修';
				}
				$activity_arr['release'][] = array(
					'title'=>'社区'.$house_name,
					'image'=> $this->config['site_url'].'/tpl/Wap/pure/static/images/new_my/order.png', /*图片暂定为 26*26的像素 */
					'url'=> $this->config['site_url'].'/wap.php?c=Worker&a=index',
				);
			}
        }

        if($this->config['find_msg'] == 1) {
            $activity_arr['release'][] = array(
                'title' => '我的发现',
                'image' => $this->config['site_url'] . '/tpl/Wap/default/static/images/new_my/find.png', /*图片暂定为 26*26的像素 */
                'url'   => $this->config['site_url'] . '/wap.php?c=Discover&a=my_discover',
            );
        }
        $activity_arr['sign_get_score'] =  $this->config['sign_get_score']?$this->config['sign_get_score']:0;
        $this->returnCode(0,$activity_arr);
    }
    //	优惠活动
    public function discount_action(){
    	$ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
        }else{
            $this->returnCode('20046009');
        }
        $uid	=	$info['uid'];
		//	商家优惠券
        $mer_list = D('Member_card_coupon')->get_all_coupon($uid);
        $mer_number	=	count($mer_list);
        //	平台优惠券
        $coupon_list = D('System_coupon')->get_user_coupon_list($uid,$now_user['phone']);
        $coupon_number	=	count($coupon_list);
        //	统计我参与的活动
       	$sql	=	"SELECT COUNT(*) AS tp_count FROM `pigcms_extension_activity_record` `ear`,`pigcms_extension_activity_list` `eal`,`pigcms_merchant` `m` WHERE(`ear`.`activity_list_id` = `eal`.`pigcms_id` AND `eal`.`mer_id` = `m`.`mer_id`AND `ear`.`uid` = '$uid') GROUP BY `eal`.`pigcms_id` ";
		$mod = new Model();
		$result = $mod->query($sql);
		$activity_number	=	count($result);
		//	统计我的会员卡
        $card_number = D('Member_card_set')->get_all_card_count($uid);
        $activity_arr = array(
            array(
                'title'=>'商家优惠券',
                'url' => $this->config['site_url'].'/wap.php?g=Wap&c=My&a=card_list&coupon_type=mer',
                'number'=>$mer_number,
            ),
            array(
                'title'=>'平台优惠券',
                'url' => $this->config['site_url'].'/wap.php?g=Wap&c=My&a=card_list&coupon_type=system',
                'number'=>$coupon_number,
            ),
            array(
                'title'=>'我参与的活动',
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=join_activity',
                'number'=>$activity_number,
            ),
            array(
                'title'=>'我的会员卡',
                'url'=> $this->config['site_url'].'/wap.php?g=Wap&c=My&a=cards',
                'number'=>$card_number,
            ),
        );
        $this->returnCode(0,$activity_arr);
    }
    //	账号管理页面
    public function account_management(){
		$ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
		}else{
			$this->returnCode('20046009');
		}
		$find	=	M('User_authentication')->field('authentication_id')->where(array('uid'=>$now_user['uid']))->order('authentication_time DESC')->find();
		if($now_user){
			$arr[]	=	array(
				'title'		=>	'昵称',
				'content'	=>	$now_user['nickname'],
				'url'		=> 	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=username',
			);
			if($now_user['phone']){
				$left	=	substr($now_user['phone'],0,3);
				$right	=	substr($now_user['phone'],strlen($now_user['phone'])-4);
				$now_user['phone_s']	=	$left.'****'.$right;
                if(!$now_user['pwd']){
                    $arr[]	=	array(
                        'title'	=>	'设置密码',
                        'content'	=>	'未设置',
                        'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=password',
                    );
                }else{
                    $arr[]	=	array(
                        'title'	=>	'修改密码',
                        'content'	=>	'',
                        'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=password',
                    );
                }
				$arr[]	=	array(
					'title'	=>	'修改手机号',
					'content'	=>	$now_user['phone_s'],
					'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=bind_user',
				);
			}else{
				$arr[]	=	array(
					'title'	=>	'绑定手机号',
					'content'	=>	$now_user['phone'],
					'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=bind_user',
				);
			}
			$arr[]	=	array(
				'title'	=>	'收货地址',
				'content'	=>	'',
				'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=adress',
			);
			if($find){
				$arr[]	=	array(
					'title'	=>	'我的实名认证',
					'content'	=>	'',
					'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=authentication_index',
				);
			}else{
				$arr[]	=	array(
					'title'	=>	'我的实名认证',
					'content'	=>	'',
					'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=authentication',
				);
			}
			$arr[]	=	array(
				'title'	=>	'我的平台实体卡',
				'content'	=>	'',
				'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=cardcode',
			);

            $arr[]	=	array(
                'title'	=>	'银行卡列表',
                'content'	=>	'',
                'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=Fenrun&a=bank_list',
            );
			//$arr[]	=	array(
//				'title'	=>	'关于我们',
//				'content'	=>	'',
//				'url'	=>	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=about',
//			);
		}else{
			$this->returnCode('20044011');
		}
		$this->returnCode(0,$arr);
    }

    //设置密码
    public  function set_passwd(){
        $ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
        }else{
            $this->returnCode('20046009');
        }
        if(!empty($now_user['pwd']) && md5($_POST['currentpassword']) != $now_user['pwd']){
            //$this->assign('error','当前密码输入错误！');
            $this->returnCode('10045009');
        }else if($_POST['currentpassword'] == $_POST['password']){
            $this->returnCode('10045010');
        }else if($_POST['password2'] != $_POST['password']){
            $this->returnCode('10045011');
        }else{
            $result = D('User')->save_user($now_user['uid'],'pwd',md5($_POST['password']));
            if($result['error']){
                $this->returnCode('10045012','',$result['msg']);
            }else{
                $this->returnCode(0);
            }
        }

    }
    //	我的钱包
    public function my_wallet(){
		$ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
		}else{
			$this->returnCode('20046009');
		}

        $level = M('User_level')->getField('level,lname');
        $now_user['lname'] = $now_user['level']?$level[$now_user['level']]:'VIP0';
		if($now_user){
			$arr['user']	=	array(
				'title'		=>	'我的余额',
				'now_money'	=>	strval($now_user['now_money']),
				'now_money2'	=>	strval($now_user['now_money']),
				'recharge'	=> 	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=recharge',
				'withdraw'	=> 	$this->config['site_url'].'/wap.php?g=Wap&c=Fenrun&a=withdraw',
				'frozen_money'	=> 	0,
				'frozen_reason'	=> 	'',
				'frozen_time'	=> 	'',
			);
            if($now_user['frozen_money'] > 0 && $this->config['open_frozen_money'] == 1 && $now_user['free_time']>$_SERVER['REQUEST_TIME']){
                $arr['user']['frozen_money'] = strval($now_user['frozen_money']);
                $arr['user']['frozen_reason'] = $now_user['frozen_reason'];
                $arr['user']['frozen_time'] = date('Y-m-d',$now_user['frozen_time']).'-'.date('Y-m-d',$now_user['free_time']);
            }
            if($this->config['company_pay_open']!=1||!isset($this->config['company_pay_encrypt'])) {
                $arr['user']['is_withdraw'] = 0;
            }else{
                $arr['user']['is_withdraw'] = 1;
            }

            if($this->config['open_user_recharge']!=1||!isset($this->config['open_user_recharge'])) {
                $arr['user']['is_recharge'] = 0;
            }else{
                $arr['user']['is_recharge'] = 1;
            }
			$arr['data']	=	array(
				array(
					'title'	=>	'余额记录',
					'content'	=>	'',
					'url'	=> 	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=transaction',
				),
				array(
					'title'	=>	$this->config['score_name'].'记录',
					'content'	=>	'',
					'url'	=> 	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=integral',
				),
				array(
					'title'	=>	'等级管理',
					'content'	=>	$now_user['lname'],
					'url'	=> 	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=levelUpdate',
				),
				array(
					'title'	=>	$this->config['score_name'].'换余额',
					'content'	=>	$now_user['score_count'],
					'url'	=> 	$this->config['site_url'].'/wap.php?g=Wap&c=My&a=score_recharge',
				),
			);
		}else{
			$this->returnCode('20044011');
		}
		$this->returnCode(0,$arr);
    }
    public function username(){
    	$ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
		}else{
			$this->returnCode('20046009');
		}
		if($now_user){
			if(empty($_POST['nickname'])){
				$this->returnCode('20090098');
			}else if($_POST['nickname'] == $this->now_user['nickname']){
				$this->returnCode('20090099');
			}else if($_POST['nickname'] == $this->config['site_name']){
				$this->returnCode('20090101');
			}else{
				$result = D('User')->save_user($info['uid'],'nickname',$_POST['nickname']);
				if($result['error']){
					$this->returnCode('20090100');
				}else{
					$this->returnCode(0);
				}
			}
		}else{
			$this->returnCode('20044011');
		}
    }
    //	到店自提
    public function pick_address(){
    	$ticket	=	I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20046009');
		}
		$mer_id = I('mer_id');
		$fromType = I('fromType');
		$store_id = I('store_id', 0);
		$long = I('long');
		$lat = I('lat');
		cookie('userLocationLong', $long);
		cookie('userLocationLat', $lat);
		
		$is_system = $fromType == 'shop' || $fromType == 'mall' ? true :false;
		$adress_list = D('Pick_address')->get_pick_addr_by_merid($mer_id, $is_system, $store_id);
		if(empty($adress_list)){
			$this->returnCode('20046015');
		}else{
			foreach($adress_list as &$v){
				$v['province'] =	$v['area_info']['province'];
				$v['city'] =	$v['area_info']['city'];
				$v['area'] =	$v['area_info']['area'];
				unset($v['area_info']);
			}
		}
		$this->returnCode(0,$adress_list);
	}
	//	用户地址列表
	public function adress(){
		$store_id = I('store_id', 0);
		if (!$this->_uid) {
			$this->returnCode('20046009');
		}
		$adress_list = D('User_adress')->get_adress_list($this->_uid);

		$lat = 0;
		$lng = 0;
		$delivery_radius = 0;
		if ($store_id && ($store = D('Merchant_store')->field('long, lat')->where(array('store_id' => $store_id))->find())) {
			$lat = $store['lat'];
			$lng = $store['long'];
			if ($store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
				$delivery_radius = $store_shop['delivery_radius'];
				$store_shop['delivery_range_polygon'] = substr($store_shop['delivery_range_polygon'], 9, strlen($store_shop['delivery_range_polygon']) - 11);
				$lngLatData = explode(',', $store_shop['delivery_range_polygon']);
				array_pop($lngLatData);
				$lngLats = array();
				foreach ($lngLatData as $lnglat) {
				    $lng_lat = explode(' ', $lnglat);
				    $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
				}
				$store_shop['delivery_range_polygon'] = $lngLats ? array($lngLats) : '';
			}
		}

		if($adress_list){
			//非百度下的经纬度
			if($_POST['longlatType']){
				$longlatClass = new longlat();
			}
			foreach($adress_list as $v){
				$is_deliver = false;
				$distance = PHP_INT_SIZE;
				if ($store_shop['delivery_range_type'] == 0) {
				    if ($lat != 0 && $lng != 0) {
				        $distance = getDistance($v['latitude'], $v['longitude'], $lat, $lng);
				        $distance = $distance / 1000;
				        if ($distance <= $delivery_radius) {
				            $is_deliver = true;
				        }
				    }
				} else {
				    if ($store_shop['delivery_range_polygon']) {
				        if (isPtInPoly($v['longitude'], $v['latitude'], $store_shop['delivery_range_polygon'])) {
				            $is_deliver = true;
				        }
				    }
				}
				$formartLng = 0;
				$formartLat = 0;
				if($_POST['longlatType']){
					$longlat = $longlatClass->baiduToGcj02($v['latitude'],$v['longitude']);
					$formartLng = $longlat['lng'];
					$formartLat = $longlat['lat'];
				}
				$arr[]	=	array(
					'adress_id'	=>	$v['adress_id'],
					'phone'		=>	$v['phone'],
					'province'	=>	$v['province'],
					'city'		=>	$v['city'],
					'area'		=>	$v['area'],
					'province_txt'	=>	$v['province_txt'],
					'city_txt'	=>	$v['city_txt'],
					'area_txt'	=>	$v['area_txt'],
					'adress'	=>	$v['adress'],
					'address_name'	=>	$v['adress'],
					'detail'	=>	$v['detail'],
					'defaults'	=>	$v['default'],
					'name'		=>	$v['name'],
					'zipcode'	=>	$v['zipcode'],
					'lng'		=>	$v['longitude'],
					'lat'		=>	$v['latitude'],
					'sex'		=>	$v['sex'],
					'is_deliver' => $is_deliver,
					'distance' => $distance,
					'formartLng' => $formartLng,
					'formartLat' => $formartLat,
				);
				$distances[] = $distance;
				array_multisort($distances, SORT_ASC, $arr);
			}
		}else{
			$arr	=	array();
		}
		$this->returnCode(0,$arr);
	}
	/*添加编辑地址*/
	public function edit_adress(){
		$ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
		}else{
			$this->returnCode('20046009');
		}
		if(empty($_POST['adress'])){
			$this->returnCode('20046017');
		}
		if(empty($_POST['name'])){
			$this->returnCode('20046018');
		}
		if(empty($_POST['phone'])){
			$this->returnCode('20046019');
		}
        $now_city = D('Area')->cityMatching($_POST['latitude'],$_POST['longitude']);
        $_POST['province'] = $now_city['area_info']['province_id'];
        $_POST['city'] = $now_city['area_info']['city_id'];
        $_POST['area'] = $now_city['area_info']['area_id'];

//		if(empty($_POST['province'])){
//			$this->returnCode('20046020');
//		}
//		if(empty($_POST['city'])){
//			$this->returnCode('20046021');
//		}
//		if(empty($_POST['area'])){
//			$this->returnCode('20046022');
//		}
		if(empty($_POST['detail'])){
			$this->returnCode('20046023');
		}
		$return	=	D('User_adress')->post_form_save($info['uid']);
		if($return){
			$this->returnCode(0);
		}else{
			$this->returnCode('20046016');
		}
	}
	//	添加编辑地址页面
	public function adress_show(){
		$ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
		}else{
			$this->returnCode('20046009');
		}
		$adress_id = $_POST['adress_id'];
		$database_area = D('Area');
		$now_adress = D('User_adress')->get_adress($info['uid'],$adress_id);
		if ($now_adress) {
			$province_list = $database_area->get_arealist_by_areaPid(0);
			$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
			$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
		} else {
			$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
			$province_list = $database_area->get_arealist_by_areaPid(0);
			foreach($province_list as $k=>$v){
				if($v['area_id'] == $now_city_area['area_pid']){
					$province_lists[]	=	$province_list[$k];
					unset($province_list[$k]);
					break;
				}
			}
			foreach($province_list as $kk=>$vv){
				$province_lists[]	=	$vv;
			}
			$province_list	=	array_values($province_lists);
			$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
            if($city_list[0]['area_id']!=$_POST['now_city']){
                foreach($city_list as $k=>$vc){
                    if($vc['area_id'] == $_POST['now_city']){
                        $tmp = $vc;
                        unset($city_list[$k]);
                        array_unshift($city_list,$tmp);
                        break;
                    }
                }
            }
			$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
		}
		if($now_adress){
			$now_adress['details']	=	$now_adress['detail'];
			$now_ad[] 	=	$now_adress;
		}else{
			$now_ad	=	array();
		}
		$arr	=	array(
			'now_adress'	=>	$now_ad,
			'province_list'	=>	isset($province_list)?$this->del_field($province_list,2):array(),
			'city_list'	=>	isset($city_list)?$this->del_field($city_list,2):array(),
			'area_list'	=>	isset($area_list)?$this->del_field($area_list,2):array(),
		);
		$this->returnCode(0,$arr);
	}

    /* 地图 */
    public function adres_map(){
        //得到所有城市并以城市首拼排序
        $database_area = D('Area');
        $all_city = S('all_city_address');
        if(empty($all_city)){
            $database_field = '`area_id`,`area_name`,`area_url`,`first_pinyin`,`is_hot`';
            $condition_all_city['area_type'] = 2;
            $all_city_old = $database_area->field($database_field)->where($condition_all_city)->order('`first_pinyin` ASC,`area_id` ASC')->select();
            foreach($all_city_old as $key=>$value){
                //首拼转成大写
                if(!empty($value['first_pinyin'])){
                    $first_pinyin = strtoupper($value['first_pinyin']);
                    $all_city[$first_pinyin][] = $value;
                }
            }
            S('all_city_address',$all_city);
        }
        $this->returnCode(0,$all_city);
    }

	//	删除多余字段
	private function del_field($arr,$type){
		if($type == 2){
			foreach($arr as &$v){
				unset($v['area_sort'],$v['first_pinyin'],$v['is_open'],$v['area_url'],$v['area_ip_desc'],$v['area_type'],$v['is_hot'],$v['url']);
			}
		}else if($type == 1){
			unset($arr['area_sort'],$arr['first_pinyin'],$arr['is_open'],$arr['area_url'],$arr['area_ip_desc'],$arr['area_type'],$arr['is_hot'],$arr['url']);
		}
		return $arr;
	}
	//	更改地址
	public function select_area(){
		$city	=	$this->select_area_array($_POST['pid']);
		$area_type	=	I('area_type',1);
		if(!empty($city)){
			if($area_type == 1){
				$arr['city_list'] = isset($city[0])?$this->del_field($city[0],2):array();
				$arr['area_list'] = isset($city[1])?$this->del_field($city[1],2):array();
			}else if($area_type == 2){
				$arr['area_list'] = isset($city[0])?$this->del_field($city[0],2):array();
				$city_list =	M('Area')->where(array('area_pid'=>$_POST['pid']))->find();
				if($city_list){
					$city_p =	M('Area')->where(array('area_id'=>$city_list['area_pid']))->find();
					if($city_p){
						$city_pp = D('Area')->get_arealist_by_areaPid($city_p['area_pid']);
						if($city_pp){
							$arr['city_list'] =	$this->del_field($city_pp,1);
						}else{
							$arr['city_list'] =	$this->del_field($city_p,1);
						}
					}else{
						$arr['city_list'][] =	$this->del_field($city_list,1);
					}
				}else{
					$arr['city_list'] =	array();
				}
			}
			$this->returnCode(0,$arr);
		}else{
			$this->returnCode('20046027');
		}
	}
	private function select_area_array($pid){
		$area_list[] = D('Area')->get_arealist_by_areaPid($pid);
		if($area_list){
			if($area_list[0][0]['area_type'] == 3){
				return $area_list;
			}else{
				$area_list[] = D('Area')->get_arealist_by_areaPid($area_list[0][0]['area_id']);
				return $area_list;
			}
		}else{
			return null;
		}
	}
	/*删除地址*/
	public function del_adress(){
		$ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
		}else{
			$this->returnCode('20046009');
		}
		$result = D('User_adress')->delete_adress($info['uid'],$_POST['adress_id']);
		if($result){
			$this->returnCode(0,$arr);
		}else{
			$this->returnCode('20046026');
		}
	}

    //提现
    public function withdraw(){

        $ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
            if(empty($now_user)){
                $this->returnCode('20046009');
            }
        }else{
            $this->returnCode('20046009');
        }
        if($this->config['company_pay_open']=='0') {
            $this->returnCode('20140059');
        }

        if(empty($_POST['truename'])){
            $this->returnCode('20046031');
        }

        $user_info = $now_user;
        $can_withdraw_money = $user_info['now_money']>=$user_info['score_recharge_money']?floatval((int)(($user_info['now_money']-$user_info['score_recharge_money'])*100)/100):$user_info['now_money'];
        $user_info['can_withdraw_money'] = $can_withdraw_money;
        $this->assign('user_info',$user_info);
        if(empty($user_info['openid'])){
            $this->returnCode('20046029');
        }

        $money = $_POST['money'];
        if($money<$this->config['company_least_money']){
            $this->returnCode(1,array(),'不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
        }
        if($money>$can_withdraw_money){
            $this->returnCode('20046030');
        }
        $data_companypay['pay_type'] = 'user';
        $data_companypay['pay_id'] = $user_info['uid'];
        $data_companypay['openid'] = $user_info['openid'];
        $data_companypay['nickname'] = $_POST['truename'];
        $data_companypay['phone'] = $user_info['phone'];
        $data_companypay['money'] = bcmul($money,100);
        $data_companypay['desc'] = "用户提现对{$money}元，用户ID: ".$user_info['uid']  ;
        $data_companypay['status'] = 0;
        $data_companypay['add_time'] = time();

        $use_result = D('User')->user_money($user_info['uid'],$money,'提款 '.$money.' 扣除余额');
        if($use_result['error_code']){
            $this->returnCode(1,array(),$use_result['msg']);
        }else{
            D('Companypay')->add($data_companypay);
            $this->returnCode(0);
        }
    }

    //提现列表
    public function withdraw_list(){
        $ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        $pigcms_id    =   I('pigcms_id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
            if(empty($now_user)){
                $this->returnCode('20046009');
            }
        }else{
            $this->returnCode('20046009');
        }
        $where['pay_type']='user';
        $where['pay_id']=$now_user['uid'];
        $withdraw = M('Companypay');
        $count = $withdraw->where($where)->count();
        if($pigcms_id){
            $where['pigcms_id'] = array('lt',$pigcms_id);
        }
        $withdraw_list = $withdraw->field('pigcms_id,money,status,add_time,pay_time')->where($where)->page('1,10')->order('pigcms_id DESC')->select();
        foreach($withdraw_list as &$v){
            $v['money'] = floatval(round($v['money']/100));
            $v['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
            if($v['pay_time']>0){
                $v['pay_time'] = date('Y-m-d H:i:s',$v['pay_time']);
            }
        }

        if(empty($withdraw_list)){
            $arr['withdraw_list']	=	array();
        }else{
            $arr['withdraw_list']	=	$withdraw_list;
        }
        $arr['page']	=	ceil($count/10);
        $arr['count'] = $count;
        $this->returnCode(0,$arr);
    }

    //快店详情接口
    public  function  shop_order_detail(){

        $order_id =   $_POST['order_id'];
        if(!$this->_uid){
            $this->returnCode('20046009');
        }

        if ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->_uid))) {
            $storeName = D("Merchant_store")->field('`name`, `phone`')->where(array('store_id' => $order['store_id']))->find();
            $arr['storeName']= $storeName;
            $status = D('Shop_order_log')->field(true)->where(array('order_id' => $order_id))->order('id DESC')->select();
            $statusCount = D("Shop_order_log")->where(array('order_id' => $order_id))->count();
            $arr['statusCount']= $statusCount;
            //配送员轨迹
            if (in_array($order['order_status'], array(1, 5))) {
                $supply = D("Deliver_supply")->where(array('order_id'=>$_GET['order_id']))->find();
                $start_time = $supply['start_time'];
                $end_time = $supply['end_time']? $supply['end_time']: time();
                $where = array();
                $where['uid'] = $supply['uid'];
                $where['create_time'] = array(array('gt', $start_time), array('lt', $end_time));
                $lines = D("Deliver_user_location_log")->where($where)->order("`create_time` ASC")->select();
                $points = array();
                $points['from_site'] = array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']);
                $points['aim_site'] = array('lng'=>$supply['aim_lnt'], 'lat'=>$supply['aim_lat']);
                $arr['supply']= $supply;
                $arr['lines']= $lines;
                if ($lines) {
                    $arr['center']=array_pop($lines);
                } else {
                    $arr['center']=array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']);
                }
                $arr['point']= $points;
            }
            $status_arr = D('Shop_order')->shop_status($order['is_pick_in_store']);
            foreach ($status as &$v) {
                $v['dateline']= date('Y-m-d H:i', $v['dateline']);
                $v['status_txt'] = $status_arr[$v['status']]['txt'];
                if($v['name']){
                    $name_str = '【'.$v['name'].'】';
                }else{
                    $name_str = '';
                }

                if($v['status']==0){
                    $v['des']='订单编号：'.$order['real_orderid'];
                }else if($v['status'] == 1){
                    $v['des']='订单编号：'.$order['real_orderid'];
                }else if($v['status'] == 2){
                    $v['des']='店员：'.$name_str.$v['phone'].'正在为您准备商品';
                } else if($v['status'] == 3){
                    $v['des']='配送员：'.$name_str.$v['phone'].'正在赶往店铺取货';
                }else if($v['status'] == 4){
                    $v['des']='配送员：'.$name_str.$v['phone'].'已取货，准备配送，请耐心等待';
                }else if($v['status'] == 5){
                    $v['des']='配送员：'.$name_str.$v['phone'].'正快速向您靠拢，请耐心等待！';
                }else if($v['status'] == 6){
                    $v['des']='配送员：'.$name_str.$v['phone'].'已完成配送，欢迎下次光临！';
                }else if($v['status'] == 7){
                    if($order['is_pick_in_store']==3){
                        $v['des'] = '店员：'.$name_str.$v['phone'].'已发货给快递公司,快递单号:'.$order['express_number'];
                    }else{
                        $v['des'] = '店员：'.$name_str.$v['phone'].'将订单改成已消费';
                    }
                }else if($v['status']==8){
                    $v['des']='您已完成评论，谢谢您提出宝贵意见！';
                }else if($v['status']==9){
                    $v['des']='您已完成退款';
                }else if($v['status']==10){
                    $v['des']='您已经取消订单';
                }else if($v['status'] == 11){
                    $v['des']='店员：'.$name_str.$v['phone'].'给您分配';
                } else if($v['status'] == 12){
                    $v['des']='店员：'.$name_str.$v['phone'].'已经给您发货到配送点';
                } else if($v['status']==13){
                    $v['des']='自提点'.$name_str.$v['phone'].'已经接到您的货物了';
                }else if($v['status']==14){
                    $v['des']='自提点'.$name_str.$v['phone'].'已经给您发货了';
                }else if($v['status']==15){
                    $v['des']='您在自提点'.$name_str.$v['phone'].'已经把您的货提走了！';
                }else if($v['status'] == 30){
                    $v['des']='店员：'.$name_str.$v['phone'].'已将订单的总价修改成'.$v['note'];
                }
                $v['img'] = $this->config['site_url'].'/tpl/Wap/pure/static/shop/images/'.$status_arr[$v['status']]['img'].'.png';
            }
            $arr['status']= $status;
        } else {
            $this->returnCode('20130006');
        }

        $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
        $shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
        $order['create_time']= $order['create_time']>0?date('Y-m-d H:i', $order['create_time']):0;
		$order['pay_time_txt'] = $order['pay_time']>0?date('Y-m-d H:i', $order['pay_time']):0;
        $order['date']= $order['date']>0?date('Y-m-d H:i', $order['date']):0;
        $order['expect_use_time']= $order['expect_use_time']>0?date('Y-m-d H:i', $order['expect_use_time']):0;
		$order['goods_price'] = getFormatNumber($order['goods_price']);
		$order['total_price'] = getFormatNumber($order['total_price']);
		$order['balance_reduce'] = getFormatNumber($order['balance_reduce']);
		$order['price'] = getFormatNumber($order['price']);
		foreach($order['info'] as &$value){
			$value['price'] = getFormatNumber($value['price']);
		}
		
        $arr['store']=array_merge($store, $shop);
        $arr['order']= $order;


        $this->returnCode(0,$arr);
    }

    //快店订单
    public function shop_feedback(){
        $ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        $order_id =   I('order_id',false);
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

        $now_order = D('Shop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => $order_id));


        if (empty($now_order)) {
            $this->returnCode('20130006');
        }
        if (empty($now_order['paid'])) {
            $this->returnCode('20046032');
        }
        if ($now_order['status'] < 2) {
            $this->returnCode('20046033');
        }
        if ($now_order['status'] == 3) {
            $this->returnCode('20046034');
        }

        if (isset($now_order['info'])) {
            $list = array();
            $goods_ids = array();
            foreach ($now_order['info'] as $row) {
                if (!in_array($row['goods_id'], $goods_ids)) {
                    $goods_ids[] = $row['goods_id'];
                    $list[] = $row;
                }
            }
            $now_order['info'] = $list;
        }
        if($list){
            $this->returnCode(0,$list);
        }else{
            $this->returnCode('20046035');
        }

    }

    //添加快店评论
    public function add_comment()
    {
        $ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        $order_id =   I('order_id',false);
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

        $goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : 0;
        $score = isset($_POST['whole']) ? $_POST['whole'] : 5;
        $comment = isset($_POST['textAre']) ? htmlspecialchars($_POST['textAre']) : 0;
        
        $dscore = isset($_POST['score']) ? $_POST['score'] : 5;
        $dcomment = isset($_POST['comment']) ? htmlspecialchars(trim($_POST['comment'])) : '';

        $now_order = D('Shop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => $order_id));

        if (empty($now_order)) {
            $this->returnCode('20130006');
        }
        if (empty($now_order['paid'])) {
            $this->returnCode('20046032');
        }
        if ($now_order['status'] < 2) {
            $this->returnCode('20046033');
        }
        if ($now_order['status'] == 3) {
            $this->returnCode('20046034');
        }


        $goodsids = array();

        $goods = '';
        $pre = '';
        if (isset($now_order['info'])) {
            foreach ($now_order['info'] as $row) {
                if (!in_array($row['goods_id'], $goodsids)) {
                    $goodsids[] = $row['goods_id'];
                    if (in_array($row['goods_id'], $goods_ids)) {
                        $goods .= $pre . $row['name'];
                        $pre = '#@#';
                    }
                }
            }
        }
        $database_reply = D('Reply');
        $data_reply['parent_id'] = $now_order['store_id'];
        $data_reply['store_id'] = $now_order['store_id'];
        $data_reply['mer_id'] = $now_order['mer_id'];
        $data_reply['score'] = $score;
        $data_reply['order_type'] = 3;
        $data_reply['order_id'] = intval($now_order['order_id']);
        $data_reply['anonymous'] = 1;
        $data_reply['comment'] = $comment;
        $data_reply['uid'] = $this->user_session['uid'];
        $data_reply['pic'] = '';
        $data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
        $data_reply['add_ip'] = get_client_ip(1);
        $data_reply['goods'] = $goods;
        $data_reply['deliver_score'] = $dscore;

        if ($database_reply->data($data_reply)->add()) {
            D('Merchant_store')->setInc_shop_reply($now_order['store_id'], $score);
            D('Shop_order')->change_status($now_order['order_id'], 3);
            D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 8));
            foreach ($goods_ids as $goods_id) {
                if (in_array($goods_id, $goodsids)) {
                    D('Shop_goods')->where(array('goods_id' => $goods_id))->setInc('reply_count', 1);
                    D('Shop_order_detail')->where(array('goods_id' => $goods_id, 'order_id' => $order_id))->save(array('is_goods' => 1));
                }
            }
            if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $now_order['order_id'], 'item' => 2))->find()) {
                D('Deliver_supply')->where(array('order_id' => $now_order['order_id'], 'item' => 2))->save(array('score' => $dscore, 'status' => 6, 'comment_time' => time(), 'comment' => $dcomment));
                if ($user = D('Deliver_user')->field(true)->where(array('uid' => $supply['uid']))->find()) {
                    $userData = array();
                    $userData['reply_count'] = $user['reply_count'] + 1;
                    $userData['total_score'] = $user['total_score'] + $dscore;
                    $userData['average_score'] = round($userData['total_score'] / $userData['reply_count'], 2);
                    D('Deliver_user')->where(array('uid' => $supply['uid']))->save($userData);
                }
            }
            
            $this->returnCode(0);
        }else{
            $this->returnCode('10100101');
        }
    }

    //优惠买单展示
    public function pay(){
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $mer_id = isset($_POST['mer_id']) ? intval($_POST['mer_id']) : 0;
		if($store_id){
			$now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
            if($this->config['store_ticket_have'] && $now_store['bind_store_trade'] == 'ticket'){
                $this->returnCode(0,array('url'=>$this->config['site_url'].'/wap.php?c=My&a=pay&go_to=web&store_id='.$store_id));
            }
			if(empty($now_store)){
				$this->returnCode('20046001');
			}
			$now_store['discount_txt'] = unserialize($now_store['discount_txt']);
			$arr['store_id']= $now_store['store_id'];
			$arr['store_name']= $now_store['name'];
			$arr['discount_type']= isset($now_store['discount_txt']['discount_type']) ? $now_store['discount_txt']['discount_type'] : 0;
			$arr['discount_percent']= isset($now_store['discount_txt']['discount_percent']) ? $now_store['discount_txt']['discount_percent'] : 0;
			$arr['condition_price']=isset($now_store['discount_txt']['condition_price']) ? $now_store['discount_txt']['condition_price'] : 0;
			$arr['minus_price']= isset($now_store['discount_txt']['minus_price']) ? $now_store['discount_txt']['minus_price'] : 0;
		}else{
			$store_list = D('Merchant_store')->get_store_list_by_merId($mer_id);
			if(empty($store_list)){
				$this->returnCode('1001',array(),'商家暂未创建/开启店铺');
			}
			if(count($store_list) == 1){
				$_POST['store_id'] = $store_list[0]['store_id'];
				$_POST['mer_id'] = 0;
				$this->pay();
				die;
			}
			if($_POST['lng'] && $_POST['lat']){
				$user_long_lat['lat'] = $_POST['lat'];
				$user_long_lat['long'] = $_POST['lng'];
			}
			foreach ($store_list as &$vo) {
				if($user_long_lat){
					$vo['Srange'] = getDistance($user_long_lat['lat'], $user_long_lat['long'], $vo['lat'], $vo['long']);
					$vo['range'] = getRange($vo['Srange'], false);
				}
				$vo['discount_txt'] = unserialize($vo['discount_txt']);
				$vo['discount_type']= isset($vo['discount_txt']['discount_type']) ? $vo['discount_txt']['discount_type'] : 0;
				$vo['discount_percent']= isset($vo['discount_txt']['discount_percent']) ? $vo['discount_txt']['discount_percent'] : 0;
				$vo['condition_price']=isset($vo['discount_txt']['condition_price']) ? $vo['discount_txt']['condition_price'] : 0;
				$vo['minus_price']= isset($vo['discount_txt']['minus_price']) ? $vo['discount_txt']['minus_price'] : 0;
				$rangeSort[] = array('store_id'=>$vo['store_id'],'juli'=>$vo['Srange']);
			}
			$arr['store_list'] = sortArrayAsc($store_list,'Srange');
		}
		
		if($this->config['open_score_get_percent']){
			$arr['score_precent'] = $this->config['score_get_percent'];
		}else{
			$arr['score_precent'] = $this->config['user_score_get']*100;
		}
		
		$arr['pay_notice'] = '1.'.$this->config['cash_alias_name'].'仅限到店消费后使用，请勿提前支付'.PHP_EOL;
		$arr['pay_notice'].= '2.请在付款前与商家确认门店信息和消费金额'.PHP_EOL;
		$arr['pay_notice'].= '3.遇节假日能否享受优惠，请详询店家'.PHP_EOL;
		$arr['pay_notice'].= '4.请与商家确认到店付能否与店内其他优惠同享'.PHP_EOL;
		
		$arr['score_notice'] = '1.平台积分仅在实付金额中产生，使用优惠券、商家余额等不获得积分';
			
        $this->returnCode(0,$arr);
    }


    //优惠买单订单
    public function store_order(){
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
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $total_money = isset($_POST['total_money']) ? (intval($_POST['total_money'] * 100) / 100) : 0;
        $no_discount_money = isset($_POST['no_discount_money']) ? (intval($_POST['no_discount_money'] * 100) / 100) : 0;

//        $total_price = isset($_POST['total_price']) ? (intval($_POST['total_price'] * 100) / 100) : 0;
//        $minus_price = isset($_POST['minus_price']) ? (intval($_POST['minus_price'] * 100) / 100) : 0;
//        $price = isset($_POST['price']) ? (intval($_POST['price'] * 100) / 100) : 0;

        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
        if(empty($now_store)){
            $this->returnCode('20046001');
        }
        if ($total_money <= 0)  $this->returnCode('10100302');;
        $minus_price_true = $price_true = 0;
        $now_store['discount_txt'] = unserialize($now_store['discount_txt']);

        if (isset($now_store['discount_txt']['discount_type'])) {
            if ($now_store['discount_txt']['discount_type'] == 1) {
                if (isset($now_store['discount_txt']['discount_percent']) && $now_store['discount_txt']['discount_percent'] > 0) {
                    $price_true = ($total_money - $no_discount_money) * $now_store['discount_txt']['discount_percent'] / 10 + $no_discount_money;
                    $minus_price_true = $total_money - $price_true;

                }
            } elseif ($now_store['discount_txt']['discount_type'] == 2) {
                if (isset($now_store['discount_txt']['condition_price']) && $now_store['discount_txt']['condition_price'] > 0 && isset($now_store['discount_txt']['minus_price']) && $now_store['discount_txt']['minus_price']) {
                    $minus_price_true = floor(($total_money - $no_discount_money) / $now_store['discount_txt']['condition_price']) * $now_store['discount_txt']['minus_price'];
                    $price_true = $total_money - $minus_price_true;

                }
            }
        }

        if ($minus_price_true == 0 && $price_true == 0) {
            $minus_price_true = 0;
            $price_true = $total_money;
        }

        $data = array('store_id' => $now_store['store_id']);
        $data['mer_id'] = $now_store['mer_id'];
        $data['uid'] = $this->user_session['uid'];
        $data['orderid'] = date("YmdHis") . mt_rand(10000000, 99999999);
        $data['name'] = '顾客现场自助支付-' . $now_store['name'];
        $data['total_price'] = $total_money;
        $data['discount_price'] = $minus_price_true;
        $data['price'] = $price_true;
        $data['dateline'] = time();
        $data['from_plat'] = 1;
        $order_id = D("Store_order")->add($data);
        if ($order_id) {
            $this->returnCode(0,array('order_type'=>'store','order_id'=>$order_id));
        } else {
            $this->returnCode('20046014');
        }
    }

    //优惠买单列表
    public function store_order_list(){
		$order_id =   $_POST['order_id'];
        $where['uid'] = $this->_uid;
        $where['paid'] = 1;
        if($order_id){
            $where['order_id'] = array('lt',$order_id);
        }
		if($_POST['mer_id']){
			$where['mer_id'] = $_POST['mer_id'];
		}
        $order_list = D("Store_order")->field('order_id,store_id,mer_id,name,total_price,pay_time,from_plat,discount_price,price')->where($where)->page('1,10')->order('order_id DESC')->select();

        $temp = $store_ids = array();
        foreach ($order_list as &$st) {
            $store_ids[] = $st['store_id'];
        }
        $m = array();
        if ($store_ids) {
            $store_image_class = new store_image();
            $merchant_list = D("Merchant_store")->field('store_id,name,mer_id,discount_txt,pic_info')->where(array('store_id' => array('in', $store_ids)))->select();
            foreach ($merchant_list as $li) {
                $images = $store_image_class->get_allImage_by_path($li['pic_info']);
                $li['image'] = $images ? array_shift($images) : array();

                $tmp = unserialize($li['discount_txt']);
                if ($tmp['discount_type'] == 1) {
                    $li['discount_str'] = $tmp['discount_percent'] . "折优惠";
                } else if ($tmp['discount_type'] == 2) {
                    $li['discount_str'] = '满'.$tmp['condition_price'] . "减".$tmp['minus_price'] ;
                }
                unset($li['status']);
                unset($li['discount_txt']);

                $m[$li['store_id']] = $li;
            }
        }
        $list = array();
        foreach ($order_list as $ol) {
            $ol['pay_time'] = date('Y-m-d H:i',$ol['pay_time']);
            if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
                $list[] = array_merge($ol, $m[$ol['store_id']]);
            } else {
                $list[] = $ol;
            }
        }
        $last_id  = 0;
        if(!empty($list)){
            $last_arr = array_slice($list,-1,1);
            $last_id = $last_arr[0]['order_id'];
        }
        $this->returnCode(0,array('list'=>$list,'order_id'=>$last_id));
    }


    public function group_order_list(){
        $status = $_POST['status'];
        $last_order_id = $_POST['last_order_id'];

        if(empty($this->_uid)){
            $this->returnCode('20046009');
        }
        $order_list = D('Group')->wap_get_order_list($this->user_session['uid'],intval($status),$last_order_id,1,intval($_POST['mer_id']));
		
		if($_POST['Device-Id'] == 'wxapp'){
			$order_list= array();
			$this->returnCode(2001,array(),'请至微信公众号 “'.$this->config['wechat_name'].'” 管理'.$this->config['group_alias_name'].'订单');
		}
		
        foreach ($order_list as &$v) {
            if($v['pay_time']>0){
                $v['pay_time'] = date('Y-m-d H:i',$v['pay_time']);
            }
        }
		$last_id = 0;
        if(!empty($order_list)){
            $last_arr = array_slice($order_list,-1,1);
            $last_id = $last_arr[0]['order_id'];
        }
        $this->returnCode(0,array('list'=>$order_list,'order_id'=>$last_id));
    }

    public function group_order_detail(){

        $order_id    =  $_POST['order_id'];
        if(empty($this->_uid)){
            $this->returnCode('20046009');
        }

        $now_order = D('Group_order')->get_order_detail_by_id($this->_uid,$order_id,true);
        $now_order['order_type'] = 'group';
        $laste_order_info=D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
        if(!$now_order['paid'] && !empty($laste_order_info)) {
            if ($laste_order_info['pay_type']=='weixin') {
                $redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
                file_get_contents($redirctUrl);
                $now_order = D('Group_order')->get_order_detail_by_id($this->_uid, intval($_GET['order_id']), true);
            }
        }
        $now_group = D('Group')->where(array('group_id'=>$now_order['group_id']))->find();

        $database_merchant = D('Merchant');
        $now_merchant = $database_merchant->get_info($now_group['mer_id']);
        $now_group['merchant_name'] = $now_merchant['name'];

        if(empty($now_order)){
            $this->returnCode('20130006');
        }
        if(empty($now_order['paid'])){
            $now_order['status_txt'] = '未付款';
        }else if(empty($now_order['third_id']) && $now_order['pay_type'] == 'offline'){
            $now_order['status_txt'] = '线下未付款';
        }else if(empty($now_order['status'])){
            if($now_order['tuan_type'] != 2){
                $now_order['status_txt'] = '未消费';
            }else{
                $now_order['status_txt'] = '未发货';
            }
        }else if($now_order['status'] == '1'){
            $now_order['status_txt'] = '待评价';
        }else if($now_order['status'] == '2'){
            $now_order['status_txt'] = '已完成';
        }else if($now_order['status'] == '3'){
            $now_order['status_txt'] = '已退款';
            $now_order['group_pass_txt'] = '退款订单无法查看';
        }

        $uid = $this->user_session['uid'];
        $group_share_num = D('Group_share_relation')->get_share_num($uid,$now_order['order_id']);
        $group_image_class = new group_image();
        $pic = explode(';',$now_group['pic']);
        $now_group['pic'] = $group_image_class->get_image_by_path($pic[0],'s');


        if($now_group['pin_num']>0 && $now_order['single_buy']==0 && $now_order['status']<3 && $now_order['paid']){
            $my_group_join = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
            if(empty($my_group_join)&&$now_order['paid']==1){
                $this->returnCode('20020014');
            }
            $buyer = D('Group_start')->get_buyerer_by_order_id($now_order['order_id']);
            $end_time = $my_group_join['start_time'] + $now_group['pin_effective_time'] * 3600;
            $effective_time = $end_time - $_SERVER['REQUEST_TIME'];
            $efftime['h'] = floor($effective_time / 3600);
            $efftime['m'] = floor(($effective_time - $efftime['h'] * 3600) / 60);
            $efftime['s'] = $effective_time - $efftime['h'] * 3600 - $efftime['m'] * 60;

            if ($effective_time > 0) {
                $now_order['effective_time']= $efftime;
            }
            $end_time = $my_group_join['start_time']+$now_group['pin_effective_time']*3600;
            $effective_time= $end_time-$_SERVER['REQUEST_TIME'];
            if($effective_time<=0&&$my_group_join['status']==0){
                D('Group_start')->timeout($now_order['order_id']);
                $my_group_join['status'] = 2;
            }
            $arr['effective_time']=$effective_time<0?array():$efftime;
            $arr['buyer']=$buyer;
            $my_group_join['start_time'] = date('Y-m-d H:i',$my_group_join['start_time']);
            //$my_group_join['last_time'] = date('Y-m-d H:i',$my_group_join['last_time']);
            $my_group_join['end_time'] = date('Y-m-d H:i',$end_time);

            $arr['my_group_join']=$my_group_join;
        }else {
            if ($now_group['group_share_num'] == 0 && $now_group['open_now_num'] == 0 && $now_group['open_num'] == 0) {
                M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
                $now_order['is_share_group'] = 2;
            } else if ($now_group['group_share_num'] != 0 && $now_group['group_share_num'] <= $group_share_num) {
                M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
                $now_order['is_share_group'] = 2;
            } else if ($now_group['open_now_num'] <= $now_group['sale_count'] && $now_group['open_now_num'] != 0 && $now_group['group_share_num'] == 0) {
                M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
                $now_order['is_share_group'] = 2;
            } else if ($now_group['open_num'] <= $now_group['sale_count'] && $now_group['open_num'] != 0 && $now_group['open_now_num'] == 0 && $now_group['group_share_num'] == 0) {
                M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
                $now_order['is_share_group'] = 2;
            }
            if ($now_group['group_share_num'] > 0) {
                $share_user = D('Group_share_relation')->get_share_user($this->user_session['uid'], $now_order['order_id']);
                $arr['share_user']= $share_user;
            }
        }

        if($now_order['pass_array']){
            $pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
            $consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
            $unconsume_pass_num = $now_order['num']-$consume_num;
            $now_order['pass_array']=$pass_array;
        }else{
            $now_order['pass_array']=array();
        }
        if($now_order['status']==6){
            if($now_order['num']!=$unconsume_pass_num){
                $refund_money = $now_order['refund_money'];
            }else{
                $refund_money = $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money'];
            }
            $now_order['refund_total'] = $refund_money;
        }else{
            $now_order['refund_total'] = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance'];
        }

        if($now_order['trade_info']){
            $trade_info_arr = unserialize($now_order['trade_info']);
            if($trade_info_arr['type'] == 'hotel'){
                $trade_hotel_info = D('Trade_hotel_category')->format_order_trade_info($now_order['trade_info']);
                $has_refund = $trade_hotel_info['has_refund'];
                $trade_refund = true;
                if($has_refund==1){
                    $trade_refund = false;
                }elseif($has_refund==2&&$now_order['add_time']+3600*$trade_hotel_info['refund_hour']>time()){
                    $trade_refund = false;
                }
                $trade_hotel_info['refund'] = $trade_refund;
                $arr['trade_hotel_info']=$trade_hotel_info;
            }
        }
        $now_order['pay_time'] = date('Y-m-d H:i',$now_order['pay_time']);
        $now_order['add_time'] = date('Y-m-d H:i',$now_order['add_time']);
        $arr['now_order']=$now_order;
        $arr['now_group']=array(
            'merchant_name'=>$now_group['merchant_name'],
            'group_refund_fee'=>$now_group['group_refund_fee'],
            'pin_num'=>$now_group['pin_num'],
            's_name'=>$now_group['s_name'],
            'pic'=>$now_group['pic'],
        );

        $this->returnCode(0,$arr);
    }
	public function personal(){
		if(empty($this->_uid)){
            $this->returnCode('20046009');
        }
		$data_user['truename']  = $_POST['truename'];
		$data_user['birthday']  = $_POST['birthday'];
		$data_user['last_time'] = time();
		$condition_user['uid'] = $this->_uid;
		if(M('User')->where($condition_user)->data($data_user)->save()){
			$this->returnCode(0,$data_user);
		}else{
			$this->returnCode(1001,array(),'修改失败，请重试');
		}
	}
	public function store_order_detail(){
		$order = D('Store_order')->get_order_by_id($this->_uid,$_POST['order_id']);
		if(empty($order)){
			$this->returnCode(1001,array(),'订单不存在');
		}
		M('Store_order')->where(array('order_id'=>$order['order_id']))->setInc('show_lottery_first',1);
		$order['pay_type_str']  = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);
		$store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($store['pic_info']);
		$store['image'] = isset($images[0]) ? $images[0] : '';
		
		$order['pay_time_txt'] = date('Y-m-d H:i:s',$order['pay_time']);
		$order['price'] = getFormatNumber($order['price']);
		$order['total_price'] = getFormatNumber($order['total_price']);
		$order['discount_price'] = getFormatNumber($order['discount_price']);
		$order['card_price'] = getFormatNumber($order['card_price']);
		$order['coupon_price'] = getFormatNumber($order['coupon_price']);
		$order['score_deducte'] = getFormatNumber($order['score_deducte']);
		$order['score_used_count'] = getFormatNumber($order['score_used_count']);
		$order['card_give_money'] = getFormatNumber($order['card_give_money']);
		$order['merchant_balance'] = getFormatNumber($order['merchant_balance']);
		$order['balance_pay'] = getFormatNumber($order['balance_pay']);
		$order['payment_money'] = getFormatNumber($order['payment_money']);
		if($order['card_discount'] == 0){
			$order['card_discount'] = 10;
		}else{
			$order['merchant_discount'] = getFormatNumber($order['price']-($order['price']-$order['no_discount_money'])*$order['card_discount']/10-$order['no_discount_money']);
		}
		
		$return['store'] = $store;
		$return['order'] = $order;
		$this->returnCode(0,$return);
	}


    public  function sign(){
        $ticket = I('ticket', false);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }
        if(empty($this->user_session)){
            $this->returnCode('20044010');
        }
        $return = D('User')->sign_in($this->user_session['uid']);
        if($return['error_code']){

            $this->returnCode('10052017','',$return['msg']);
        }else{
            $this->returnCode(0);
        }
    }
    
    public function share_type(){
        $ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
        }else{
            $this->returnCode('20046009');
        }
        $type = $_POST['type'];
        
        $order = M(ucfirst($type).'_order')->where(array('order_id'=>$_POST['order_id']))->find();
        if($this->config['share_coupon'] && $this->config['open_share_lottery']==0){
            $arr['type'] = 'share_coupon';
            $arr['url'] = $this->config['site_url'].'/wap.php?c=Share_lottery&a=share_coupon&type='.$type.'&order_id='.$_POST['order_id'];
        }else if($this->config['open_share_lottery']){
            $arr['type'] = 'share_lottery';
            if($type=='shop'){
                $arr['url'] = $this->config['site_url'].'/wap.php?c=Shop&a=index&shop-id='.$order['store_id'];
            }else{
                $arr['url'] = $this->config['site_url'].'/wap.php?c=My&a=pay&store_id='.$order['store_id'];
            }
            
        }
        $this->returnCode(0,$arr);
    }
    
    /**
     * 获取话费充值列表
     * 4:充值成功，3:等待充值，0:未扣款，12:充值失败
     */
    public function get_mobile_recharge_list()
    {
        $ticket = I('ticket', false);
        $device_id = I('Device-Id', false);
        if ($ticket && $device_id) {
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
        } else {
            $this->returnCode('20046009');
        }
        $status = I('status', -1, 'intval');
        if ($status == 12) {
            $where['status'] = array('in', array(5, 6, 12));
        } elseif ($status >= 0) {
            $where['status'] = $status;
        }
 
        $where['uid'] = $info['uid'];
        $where['paid'] = 1;
        $order_list = D("Mobile_recharge_order")->field(true)->where($where)->order('order_id DESC')->select();
        foreach($order_list as $key=>$val){
            $order_list[$key]['order_url'] = $this->config['site_url'] . '/wap.php?c=Third_recharge&a=mobile_recharge_detial&order_id=' . $val['order_id'];
        }
        $this->returnCode(0, $order_list);
    }
}