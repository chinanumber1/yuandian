<?php
class PayAction extends BaseAction{
    protected function _initialize() {
        parent::_initialize();
        if(defined('IS_INDEP_HOUSE')){
            $this->indep_house = C('INDEP_HOUSE_URL');
        }else{
            $this->indep_house = 'wap.php';
        }
        //二次验证时间
        if($this->user_session['uid']){
            if($times = D('Verify_limit')->field('end_time')->where(array('uid'=>$this->user_session['uid'],'times'=>array('lt',2)))->find()){
                $_SESSION['user']['verify_end_time']=$times['end_time'];
            }
        }
    }
    public function check(){
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！',U('Login/index'));
        }
        $now_user = D('User')->get_user($this->user_session['uid']);
        if($this->config['open_extra_price']==0 && (($_GET['type'] != 'store' && $_GET['type'] != 'shop') || $this->config['cash_bind_phone']) && empty($now_user['phone'])){
            $this->error_tips('尊敬的用户，为了提供更好的服务，请您在消费之前先绑定手机号码！',U('My/bind_user',array('referer'=>urlencode(U('Pay/check',$_GET)))));
        }

        if(!in_array($_GET['type'],array('group','meal','weidian','takeout', 'food', 'foodPad','recharge','appoint','wxapp', 'store', 'shop', 'mall', 'plat','balance-appoint'))){
            $this->error_tips('订单来源无法识别，请重试。');
        }
        $group_pay_offline = true;
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
        }else if($_GET['type'] == 'shop' || $_GET['type'] == 'mall'){
            $now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
            if($this->config['open_extra_price']==0  && empty($now_user) && $now_order['order_info'] && $now_order['order_info']['order_from'] != 6){
                $this->error_tips('尊敬的用户，为了提供更好的服务，请您在消费之前先绑定手机号码！',U('My/bind_user',array('referer'=>urlencode(U('Pay/check',$_GET)))));
            }
        }else if($_GET['type'] == 'plat'){
            $now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
            $now_order['order_info']['extra_price'] =  $now_order['order_info']['order_info']['extra_price'];
            $now_village = M('House_village')->where(array('village_id'=>$now_order['order_info']['order_info']['village_id']))->find();
            $this->assign('now_village',$now_village);
            if($now_order['order_info']['status']==1){
                $this->error_tips('订单已支付');
            }
        }else if($_GET['type'] == 'balance-appoint'){
            $now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval($_GET['order_id']));
        }else{
            $this->error_tips('非法的订单');
        }

        if($now_order['error'] == 1){
            if($now_order['url']){
                $this->success_tips($now_order['msg'],$now_order['url']);
            }else{
                $this->error_tips($now_order['msg']);
            }
        }
        $order_info = $now_order['order_info'];
        if(!isset($order_info['discount_status'])){
            $order_info['discount_status']=  true;
        }

        if($this->is_wexin_browser && !empty($_SESSION['openid']) && ($merchant_bind = D('Weixin_bind')->field(true)->where(array('mer_id' => $order_info['mer_id'], 'type' => 0))->find()) && $merchant_bind['service_type_info'] == '2' && $merchant_bind['verify_type_info'] != '-1'){
            $this->open_authorize_openid(array('appid' => $merchant_bind['authorizer_appid'], 'mer_id' => $merchant_bind['mer_id']));
        }

        if($this->config['open_extra_price']==1&&($order_info['order_type']!='appoint'||$order_info['discount_status'])){
            $user_score_use_percent=(float)$this->config['user_score_use_percent'];
            $order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
        }else{
            $order_info['order_extra_price'] = 0;
            $order_info['extra_price'] = 0;
        }
        $this->assign('order_info',$order_info);

        if($this->is_app_browser){
            $this->display();die;
        }

        if($order_info['mer_id']){
            $now_merchant = D('Merchant')->get_info($order_info['mer_id']);
            if(empty($notOffline)){
                if ($now_merchant) {
                    $notOffline =($pay_offline && $now_merchant['is_offline'] == 1) ? 0 : 1;
                }
            }

            $use_discount = true;
            if($order_info['discount_status']===false){
                $use_discount = false;
            }
            if($this->config['open_score_discount'] && $use_discount){
                switch($now_merchant['user_discount_type']){
                    case 1 :   //优惠
                        break;
                    case 2 :   //积分
                        $use_discount =false;
                        break;
                    case 3 :   //2选1
                        $use_discount =  $_GET['discount_type'] == 'score'?false:true;
                        break;
                }
            }
            $this->assign('now_merchant',$now_merchant);
        }

        //判断线下支付
        if($order_info['order_type'] == 'plat'){
            $this->assign('pay_offline',$order_info['pay_offline']);
        }else{
            $pay_offline = D('Percent_rate')->pay_offline($order_info['mer_id'],$_GET['type']);
            $this->assign('pay_offline',$pay_offline&&$group_pay_offline);
        }

        //判断对接
        if(C('butt_open')){
            import('ORG.Net.Http');
            $http = new Http();
            $postArr = array(
                'butt_id' => $order_info['mer_id'],
                'order_id' => $order_info['order_id'],
                'order_type' => $order_info['order_type'],
                'order_name' => $order_info['order_name'],
                'order_num' => $order_info['order_num'],
                'order_price' => $order_info['order_price']*100,
                'order_total_money' => $order_info['order_total_money'],
                'redirct_url' => $this->config['site_url'].'/'.$this->indep_house.'?c=Pay&a=butt_pay',
            );
            $return = Http::curlPost(C('butt_pay_post_url'),get_butt_encrypt_key($postArr,C('butt_key')));
            if($return['err_code']){
                $this->error($return['err_msg']);
            }else if($return['result']){
                redirect($return['result']);
            }else{
                $this->error('调用支付时发生错误，请重试');
            }
        }

        //得到微信优惠金额,判断用户能否购买此团购
        $cheap_info = array('can_buy'=>true,'can_cheap'=>false,'wx_cheap'=>0);
        if($_GET['type'] == 'group' && $this->is_wexin_browser){
            $now_user = D('User')->get_user($this->user_session['uid']);
            if($this->config['weixin_buy_follow_wechat'] == 2 && !empty($_SESSION['openid']) && empty($now_user['is_follow'])){
                $cheap_info['can_buy'] = false;
            }
            $cheap_info['wx_cheap'] = D('Group')->get_group_cheap($order_info['group_id']);
            $cheap_info['wx_cheap'] = $cheap_info['wx_cheap']*$order_info['order_num'];
            if($cheap_info['wx_cheap']){
                $cheap_info['can_cheap'] = true;
                if($_SESSION['openid']){
                    if($this->config['weixin_buy_follow_wechat'] == 1 && empty($now_user['is_follow'])){
                        $cheap_info['can_cheap'] = false;
                    }
                }else{
                    $cheap_info['can_cheap'] = false;
                }
                $cheap_info['wx_cheap'] = round($cheap_info['wx_cheap'] * 100) /100;
                $_SESSION['wx_cheap'] = $cheap_info['wx_cheap'];
            }
        }
        $this->assign('cheap_info',$cheap_info);

        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);

        if(empty($now_user)){
            $this->error_tips('未获取到您的帐号信息，请重试！');
        }
        $now_user['now_money'] = floatval($now_user['now_money']);
        //子商户设置不允许使用平台余额支付
        if(($this->config['cash_nobind_phone_use_balance']==0 && empty($now_user['phone'])) || $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_sys_pay']!=1){
            $now_user['now_money'] = 0;
        }

        $this->assign('now_user',$now_user);

        if(($this->config['cash_nobind_phone_use_balance'] || !empty($now_user['phone'])) && $_GET['type'] != 'recharge' && $_GET['type'] != 'weidian' && ($order_info['business_type']==''||$order_info['business_type']!='card_new_recharge') ) {
            //商家优惠券
            if ($this->is_app_browser) {
                $platform = 'app';
            } else if ($this->is_wexin_browser) {
                $platform = 'weixin';
            } else {
                $platform = 'wap';
            }
            $order_info['total_money'] = $order_info['order_total_money'];
            $tmp_order = $order_info;

            if($order_info['order_type']=='store'){
                $tmp_order['total_money']-=$tmp_order['no_discount_money'];
            }else  if($order_info['business_type']=='foodshop'){
                //$tmp_order['total_money']=$order_info['order_info']['can_discount_money'];
                //$tmp_order['total_money']=$order_info['order_info']['can_discount_table_money'];
            }


            $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->user_session['uid'], $order_info['mer_id']);
            if($card_info['status']) {
                $merchant_balance = $card_info['card_money'] + $card_info['card_money_give'];
                if (!$use_discount || isset($order_info['discount_status']) && !$order_info['discount_status'] || empty($card_info)||empty($card_info['discount'])) {
                    $card_info['discount'] = 10;
                }
                $_SESSION['discount'] = $card_info['discount'];
                $tmp_order['uid'] = $this->user_session['uid'];

                if($tmp_order['freight_charge']>0){
                    $tmp_order['total_money'] = ($tmp_order['total_money']-$tmp_order['freight_charge']) * $card_info['discount'] / 10 - $cheap_info['wx_cheap']+$tmp_order['freight_charge'];
                }else{
                    $tmp_order['total_money'] = $tmp_order['total_money'] * $card_info['discount'] / 10 - $cheap_info['wx_cheap'];
                }

                //快店部分商品不参加商家优惠券
                if( $order_info['order_type']=='shop' && $tmp_order['can_discount_money']>0){
                    $tmp_order['can_discount_money'] = $tmp_order['can_discount_money'] * $card_info['discount'] / 10 ;
                }
                if ((!isset($order_info['discount_status']) || $order_info['discount_status'])&&$_GET['unmer_coupon']!=1 && $use_discount) {
                    if($order_info['order_type']=='shop'){
                        if($tmp_order['can_discount_money']>0){
                            if (!empty($_GET['card_id'])) {
                                $now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($_GET['card_id'], $this->user_session['uid']);
                                $now_coupon['type'] = 'mer';
                                $this->assign('now_coupon', $now_coupon);
                            } else {
                                if (empty($_GET['merc_id'])) {
                                    if (!empty($order_info['business_type'])) {
                                        $card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $platform, $order_info['business_type']);
                                    } else {
                                        $card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $platform);
                                    }

                                    $mer_coupon = reset($card_coupon_list);

                                } else {
                                    $mer_coupon = D('Card_new_coupon')->get_coupon_info($_GET['merc_id']);
                                }

                            }
                        }
                    }else{
                        if (!empty($_GET['card_id'])) {
                            $now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($_GET['card_id'], $this->user_session['uid']);
                            $now_coupon['type'] = 'mer';
                            $this->assign('now_coupon', $now_coupon);
                        } else {
                            if (empty($_GET['merc_id'])) {
                                if (!empty($order_info['business_type'])) {
                                    $card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $platform, $order_info['business_type']);
                                } else {
                                    $card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $platform);
                                }

                                $mer_coupon = reset($card_coupon_list);

                            } else {
                                $mer_coupon = D('Card_new_coupon')->get_coupon_info($_GET['merc_id']);
                            }

                        }
                    }

                }
            }else{
                if ($cheap_info['can_cheap']) {
                    $tmp_order['total_money']-=$cheap_info['wx_cheap'];
                }
            }

            //平台优惠券
            if ($use_discount && ($tmp_order['total_money'] > $mer_coupon['discount'] || empty($mer_coupon))&&$_GET['unsys_coupon']!=1&&($order_info['discount_status']||!isset($order_info['discount_status']))) {
                $tmp_order['total_money'] -= empty($mer_coupon['discount']) ? 0 : $mer_coupon['discount'];
                if (empty($_GET['sysc_id'])) {
                    if (!empty($order_info['business_type'])) {
                        $now_coupon = D('System_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform, $order_info['business_type']);
                    } else {
                        $now_coupon = D('System_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform);
                    }
                    $system_coupon = reset($now_coupon);

                } else {
                    $system_coupon = D('System_coupon')->get_coupon_info($_GET['sysc_id']);
                }
            }

            //子商户设置不允许使用平台优惠抵扣
            if( $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_discount']!=1){
                $system_coupon = array();
            }

            if (!empty($mer_coupon)) {
                $mer_coupon['coupon_url_param'] = array('merc_id' => $mer_coupon['id'], 'order_id' => $order_info['order_id'], 'type' => $_GET['type'],'discount_type'=>'coupon');
                if($now_merchant['user_discount_type'] == 2 ||$_GET['discount_type']=='coupon'){
                    $mer_coupon['coupon_url_param']['discount_type'] = 'coupon';
                }else{
                    unset($mer_coupon['coupon_url_param']['discount_type']);
                }
                if($system_coupon['discount']<$tmp_order['total_money']){
                    $this->assign('card_coupon', $mer_coupon);
                    $_SESSION['merc_id'] = $mer_coupon['id'];
                    $_SESSION['card_discount'] = $mer_coupon['discount'];
                }
                $this->assign('isUseMerCoupon',1);
            } else {
                $mer_coupon['coupon_url_param'] = array();
                if($_GET['unmer_coupon']!=1){
                    $this->assign('isUseMerCoupon',2);
                }
            }


            if (!empty($system_coupon)) {
                $system_coupon['coupon_url_param'] = array('sysc_id' => $system_coupon['id'], 'order_id' => $order_info['order_id'], 'type' => $_GET['type'],'discount_type'=>'coupon');

                if($now_merchant['user_discount_type'] == 2 ||$_GET['discount_type']=='coupon'){
                    $system_coupon['coupon_url_param']['discount_type'] = 'coupon';
                }else{
                    unset($system_coupon['coupon_url_param']['discount_type']);
                }

                if($system_coupon['is_discount']==0 && $system_coupon['discount']>=$tmp_order['total_money']){
                    $ban_mer_coupon=1;
                }else{
                    $ban_mer_coupon=0;
                }

                $this->assign('ban_mer_coupon',$ban_mer_coupon);
                //   if($system_coupon['discount']<$tmp_order['total_money']) {
                $_SESSION['sysc_id'] = $system_coupon['id'];

                $system_coupon['discount'] = $system_coupon['discount_money'];
                $this->assign('system_coupon', $system_coupon);

                $tmp_order['total_money'] -= empty($system_coupon['discount']) ? 0 : $system_coupon['discount'];

                // }
            } else {
                $system_coupon['coupon_url_param'] = array();
            }



            $coupon_url = array_merge($mer_coupon['coupon_url_param'], $system_coupon['coupon_url_param']);
            if($_GET['unsys_coupon']&&!empty($coupon_url)){
                unset($_SESSION['sysc_id']);
                $coupon_url['unsys_coupon']=1;
            }
            if($_GET['unmer_coupon']&&!empty($coupon_url)){
                unset($_SESSION['merc_id']);
                $coupon_url['unmer_coupon']=1;
            }
            $this->assign('coupon_url', $coupon_url);

            if (isset($order_info['discount_status']) && !$order_info['discount_status']) {
                $card_info['discount'] = 10;
            }
            $this->assign('card_info', $card_info);

            $this->assign('merchant_balance', $merchant_balance);

        }

        //使用积分
        $score_can_use_count=0;
        $score_deducte=0;
        $user_score_use_percent = (float)$this->config['user_score_use_percent'];
        //定制元宝判断条件
        if($this->config['open_extra_price']==1&&$order_info['extra_price']>0) {
            if ($_GET['type'] == 'group' || $_GET['type'] == 'store' || $_GET['type'] == 'meal' ||$_GET['type'] == 'mall' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad' || $_GET['type'] == 'appoint' || $_GET['type'] == 'mall' || $_GET['type'] == 'shop' || $_GET['type'] == 'balance-appoint' || ($order_info['order_type'] == 'plat' && $order_info['pay_system_score'])) {           //business_type
                $type_ = $_GET['type'];
                if ($order_info['business_type'] == 'foodshop') {
                    $type_ = 'meal';
                }

                if ($_GET['type'] == 'balance-appoint') {
                    $type_ = 'appoint';
                }
                if ($_GET['type'] == 'mall') {
                    $type_ = 'shop';
                }



                $user_score_use_condition = $this->config['user_score_use_condition'];
                $user_score_max_use = D('Percent_rate')->get_max_core_use($order_info['mer_id'], $type_);//不同业务不同积分
                // $user_score_max_use=$score_config['user_score_max_use'];

                if ($_GET['type'] == 'group') {
                    $group_info = D('Group')->where(array('group_id' => $order_info['group_id']))->find();
                    if ($group_info['score_use']) {
                        if ($group_info['group_max_score_use'] != 0) {
                            $user_score_max_use = $group_info['group_max_score_use'];
                        }
                    } else {
                        $user_score_max_use = 0;
                    }
                }


                $user_score_use_percent = (float)$this->config['user_score_use_percent'];
                $score_max_deducte = bcdiv($user_score_max_use, $user_score_use_percent, 2);

                if ($user_score_use_percent > 0 && $score_max_deducte > 0 && $user_score_use_condition > 0 && $now_user['score_count'] > 0) {   //如果设置没有错误
                    $total_ = $order_info['extra_price'];
                    if ($total_ >= $user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
                        if ($total_ > $score_max_deducte) {                    //判断积分最大抵扣金额是否比这个订单的总额大
                            $score_can_use_count = (int)($now_user['score_count'] > $user_score_max_use ? $user_score_max_use : $now_user['score_count']);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
                            $score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
                            $score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
                        } else {
                            $score_can_use_count = $total_ * $user_score_use_percent > (int)$now_user['score_count'] ? (int)$now_user['score_count'] : $total_ * $user_score_use_percent;
                            $score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
                            $score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
                        }
                    }
                }

            }
        }else{
            if ($_GET['type'] == 'group' || $_GET['type'] == 'store' || $_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad' || $_GET['type'] == 'appoint' || $_GET['type'] == 'mall' || $_GET['type'] == 'shop' || $_GET['type'] == 'balance-appoint' || ($order_info['order_type'] == 'plat' && $order_info['pay_system_score'])) {           //business_type
                $type_ = $_GET['type'];
                if ($order_info['business_type'] == 'foodshop') {
                    $type_ = 'meal';
                }

                if ($_GET['type'] == 'balance-appoint') {
                    $type_ = 'appoint';
                }
                if ($_GET['type'] == 'mall') {
                    $type_ = 'shop';
                }
                if ($_GET['type'] == 'store') {
//                   $tmp_order['total_money'] = $order_info['order_price'];
                }

                if($_GET['type']=='plat'&&empty($tmp_order)){
                    $tmp_order['total_money']  =$order_info['order_total_money'];
                }

                $user_score_use_condition = $this->config['user_score_use_condition'];
                $user_score_max_use = D('Percent_rate')->get_max_core_use($order_info['mer_id'], $type_,$tmp_order['total_money'],$tmp_order);//不同业务不同积分

//                 $user_score_max_use=$score_config['user_score_max_use'];

                if ($_GET['type'] == 'group') {
                    $group_info = D('Group')->where(array('group_id' => $order_info['group_id']))->find();
                    if ($group_info['score_use']) {
                        if ($group_info['group_max_score_use'] != 0) {
                            $user_score_max_use = $group_info['group_max_score_use'];
                        }
                    } else {
                        $user_score_max_use = 0;
                    }
                }
                $user_score_use_percent = (float)$this->config['user_score_use_percent'];
                $score_max_deducte = bcdiv($user_score_max_use, $user_score_use_percent, 2);

                if ($user_score_use_percent > 0 && $score_max_deducte > 0 && $user_score_use_condition > 0 && $now_user['score_count'] > 0) {   //如果设置没有错误
                    $total_ = $tmp_order['total_money'];

                    if ($total_ >= $user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
                        if ($total_ > $score_max_deducte) {                    //判断积分最大抵扣金额是否比这个订单的总额大
                            $score_can_use_count = (int)($now_user['score_count'] > $user_score_max_use ? $user_score_max_use : $now_user['score_count']);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
                            $score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
                            $score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
                        } else {
                            $score_can_use_count = ceil($total_ * $user_score_use_percent) > (int)$now_user['score_count'] ? (int)$now_user['score_count'] : ceil($total_ * $user_score_use_percent);
                            $score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
                            $score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
                        }
                    }
                }

            }
        }

        //物业费积分判断 现在改成所有的缴费都可使用获得积分
         if($_GET['type'] == 'plat'&& $order_info['order_info']['order_type']=='property'){
//        if($_GET['type'] == 'plat'&& in_array($order_info['order_info']['order_type'], array('property','custom','park','gas','electric','water','custom_payment'))){
            $score_can_use_count = $order_info['order_info']['score_can_use'];
            $score_deducte = $order_info['order_info']['score_can_pay'];
            $score_percent = $order_info['order_info']['score_percent'];
            if($now_user['score_count']<$score_can_use_count){
                $score_can_use_count = $now_user['score_count'];
                $score_deducte = floor($score_can_use_count/$score_percent);
            }
        }

        // 社区收银台缴费
//        if($_GET['type'] == 'plat'&& $order_info['business_type']=='house_village_pay_cashier'){
//            $score_info = D(ucfirst($order_info['business_type']).'_order')->get_score($order_info['order_info']['cashier_id'],$now_user);
//            $score_can_use_count = $score_info['score_can_use'];
//            $score_deducte = $score_info['score_can_pay'];
//            $score_percent = $score_info['score_percent'];
//            if($now_user['score_count']<$score_can_use_count){
//                $score_can_use_count = $now_user['score_count'];
//                $score_deducte = floor($score_can_use_count/$score_percent);
//            }
//        }

        //子商户设置不允许使用平台优惠抵扣  开关 1 子商户 ，2 绑定手机 3 积分冻结
        if(  $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_discount']!=1||($this->config['cash_nobind_phone_use_balance']==0 && empty($now_user['phone'])) || $now_user['forzen_score'] == 1){
            $score_can_use_count = 0;
            $score_deducte = 0;
        }

        //可用积分
        $this->assign('score_can_use_count', $score_can_use_count);
        $this->assign('score_deducte', sprintf("%.2f",substr(sprintf("%.3f", $score_deducte), 0, -1)));
        $this->assign('score_count', $now_user['score_count']);

        //调出支付方式
        $notOnline = intval($_GET['notOnline']);
        if($_GET['type'] != 'recharge' && $_GET['type'] != 'appoint'){
            $notOffline = intval($_GET['notOffline']);
        }else{
            $notOffline = 1;
        }

        //********************预定金不允许线下支付*************************//
        if (intval($_GET['isdeposit'])) $notOffline = 1;
        if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad'){
            $t_order = D('Meal_order')->get_order_by_id($this->user_session['uid'], intval($_GET['order_id']));
            $true_price = $t_order['total_price'] - $t_order['minus_price'];
            if ($t_order['price'] < $true_price) $notOffline = 1;
        }
        //********************预定金不允许线下支付*************************//


        if($this->config['open_extra_price']==1){
            $now_mer = M('Merchant')->where(array('mer_id'=>$order_info['mer_id']))->find();

            $score_percent = $now_mer['score_get']>=0?$now_mer['score_get']:$this->config['user_score_get'];

            $this->assign('score_get',$score_percent);
        }

        if (isset($_GET['online']) && $_GET['type'] == 'foodPad') {
            $online = isset($_GET['online']) ? intval($_GET['online']) : 1;
            $notOnline = $online ? 0 : 1;
            $notOffline = $online ? 1 : $notOffline;
        }

        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        if(in_array($_GET['type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','balance-appoint')) && $order_info['mer_id']){

        }else if($_GET['type'] == 'plat' && $order_info['pay_merchant_ownpay'] && $order_info['mer_id']){
            $this->config['merchant_ownpay'] = $this->config['merchant_ownpay'];
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $now_merchant = D('Merchant')->get_info($order_info['mer_id']);
        if ($now_merchant) {
            $notOffline = ($pay_offline && $now_merchant['is_offline'] == 1) ? 0 : 1;
        }
        switch($this->config['merchant_ownpay']){
            case 0:
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                break;
            case 1:
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_merchant['mer_id']))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }

                break;
            case 2:
                $pay_method = array();
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_merchant['mer_id']))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                }

                break;
        }

        if(isset($pay_method['alipayh5'])){
            $pay_method['alipayh5']['name'] = '支付宝';
        }

        if (  $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
            $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
            $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
            $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
            $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
            $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
            //$pay_method['weixin']['config']['is_own'] = 1 ;
        }

        if($this->config['open_allinyun']==1){
            $can_pay_allinyun = true;
            $allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();

            if($order_info['mer_id'] && $_GET['type']!='recharge'){
                $allinyun_mer =  D('Deposit')->get_merchant_info($order_info['mer_id'],1);
                if( $allinyun_mer['status']==1){
                    $can_pay_allinyun = true;
                }else{
                    $can_pay_allinyun = false;
                }
            }
            if($allinyun['bizUserId'] && $can_pay_allinyun){
                $pay_method['allinyun_gateway']['name'] ='云商通网关';
                $_GET['type']!='recharge' && $pay_method['allinyun_balance']['name'] ='云商通余额';
                $this->assign('balance_disable',1);
            }
        }



        if(empty($pay_method) && $this->is_app_browser==false){
            $this->error_tips('系统管理员没开启任意一种支付方式！');
        }

        if(empty($_SESSION['openid']) || $_GET['type'] == 'foodPad' || !$this->is_wexin_browser){
            unset($pay_method['weixin'],$pay_method['weifutong']);
        }
        unset($pay_method['weixinapp']);
        if($pay_method['weixin']['config']['is_own']){
            $merchant_bind = D('Weixin_bind')->field(true)->where(array('mer_id' => $now_merchant['mer_id'], 'type' => 0))->find();
            if(empty($merchant_bind) || $merchant_bind['service_type_info'] != 2 || $merchant_bind['verify_type_info'] == -1){
                unset($pay_method['weixin']);
            }else{
                if(empty($_SESSION['open_authorize_openid']) || empty($_SESSION['open_authorize_mer_id']) || ($_SESSION['open_authorize_mer_id'] != $order_info['mer_id'])){
                    $this->open_authorize_openid(array('appid'=>$merchant_bind['authorizer_appid'], 'mer_id'=>$merchant_bind['mer_id']));
                }
                if($_SESSION['open_authorize_openid'] == 'error'){
                    unset($pay_method['weixin']);
					unset($_SESSION['open_authorize_openid']);
                }
            }
        }
        $this->assign('pay_method',$pay_method);

        //定制桌台优惠
        if($_GET['type']=='plat'){
            $tablesDiscount = D('Merchant_store_foodshop')->getTableDiscount($order_info['mer_id'],$order_info['order_info']['order_id']);
            if($tablesDiscount!=false && $tablesDiscount['mer_discount']!=0){
                $this->assign('tablesDiscount',$tablesDiscount);
            }
        }
        if($_GET['type'] == 'group'){
            $this->behavior(array('model'=>'Pay_group','mer_id'=>$order_info['mer_id'],'biz_id'=>$order_info['order_id']),true);
        }else if($_GET['type'] == 'meal'){
            $this->behavior(array('model'=>'Play_meal','mer_id'=>$order_info['mer_id'],'biz_id'=>$order_info['order_id']),true);
        }

        $this->assign('type',$_GET['type']);
        $this->assign('order_id',$_GET['order_id']);
        $this->display();
    }

    protected function getPayName($label){
        $payName = array(
            'weixin' => '微信支付',
            'tenpay' => '财付通支付',
            'yeepay' => '银行卡支付(易宝支付)',
            'allinpay' => '银行卡支付(通联支付)',
            'chinabank' => '银行卡支付(网银在线)',
            'weifutong' => $this->config['pay_weifutong_alias_name'],
        );
        return $payName[$label];
    }

    public function go_pay()
    {
		//小程序非微信支付使用 GET 传参 跳转此页面
		if($_GET['pay_param']){
			$pay_param = base64_decode(urldecode($_GET['pay_param']));
			$_POST = json_decode($pay_param,true);
			fdump($_POST,'pay_param');
		}

        //换参数
        if ($_POST['ticket'] != '') {
            $_POST['use_merchant_balance'] = $_POST['use_merchant_money'];
            $_POST['use_balance'] = !isset($_POST['use_balance_money']) ? 1 : $_POST['use_balance_money'];
            $_POST['discount_type'] = !isset($_POST['discount_type']) ? '' : $_POST['discount_type'];
        }
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }

        if ($_POST['pay_type'] == 'offline' && $_POST['use_balance']) {
            if ($allinyun_user = D('Deposit')->allinyun_user($this->user_session['uid'])) {
                $this->error_tips('您是云商通账户,不能使用余额+线下的支付方式');
            }
        }
        if (!in_array($_POST['order_type'], array('group', 'meal', 'weidian', 'takeout', 'food', 'foodPad', 'recharge', 'appoint', 'waimai', 'wxapp', 'store', 'shop', 'mall', 'plat', 'balance-appoint', 'plat'))) {
            $this->error_tips('订单来源无法识别，请重试。');
        }
        if ($this->config['open_juhepay']==0 && (strtolower($_POST['pay_type']) == 'alipay' || strtolower($_POST['pay_type']) == 'alipayh5')) {
            $url = U('Pay/alipay', $_POST);
            $this->assign('url', $url);
            $this->display('alipay_pay');
            die;
        }

        switch ($_POST['order_type']) {
            case 'group':
                $now_order = D('Group_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'meal':
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order = D('Meal_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']), false, $_POST['order_type']);
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'recharge':
                $now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'appoint':
                $now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'waimai':
                $now_order = D('Waimai_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']), false, $_POST['order_type']);
                if ($now_order['order_info']['pay_type'] !== $_POST['pay_type']) {
                    $this->error_tips('非法的订单');
                }
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->get_pay_order(0, intval($_POST['order_id']));
                break;
            case 'store':
                $now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'shop':
            case 'mall':
                $now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'plat':
                $now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'balance-appoint':
                $now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            default:
                $this->error_tips('非法的订单!');
        }

        if ($now_order['error'] == 1) {
            $this->error_tips($now_order['msg'], $now_order['url']);
        }

        $order_info = $now_order['order_info'];
        if ($_POST['ticket'] != '') {
            $order_info['is_mobile'] = 2;
        } else {
            $order_info['is_mobile'] = 1;
        }
        $now_merchant = D('Merchant')->get_info($order_info['mer_id']);
        if ($now_merchant['status'] == 3) {
            $this->error_tips('该商家状态异常，无法支付');
        }
        $use_discount = true;

        if ($this->config['open_score_discount']) {
            switch ($now_merchant['user_discount_type']) {
                case 1 :   //优惠
                    $order_info['score_discount_type'] = 2;
                    break;
                case 2 :   //积分
                    $use_discount = false;
                    $order_info['score_discount_type'] = 1;
                    break;
                case 3 :   //2选1
                    $use_discount = $_POST['discount_type'] == 'score' ? false : true;
                    break;
            }
            if ($_POST['discount_type'] == "score") {
                $order_info['score_discount_type'] = 1;
            } else if ($_POST['discount_type'] == "coupon") {
                $order_info['score_discount_type'] = 2;
            }

        }
        //商家会员卡余额
        if ($_POST['use_merchant_balance']) {
            $order_info['use_merchant_balance'] = 1;
        } else {
            $order_info['use_merchant_balance'] = 0;
        }
        if ($_POST['order_type'] != 'recharge' && $_POST['order_type'] != 'weidian' && (empty($order_info['business_type']) || $order_info['business_type'] != 'card_new_recharge')) {
            //优惠券
            if ($use_discount && (!isset($order_info['discount_status']) || $order_info['discount_status'])) {
                if ((!empty($_POST['card_id']) && $_POST['card_id'] == $_SESSION['merc_id']) || ($_POST['ticket'] && $_POST['card_id'] && $_POST['use_mer_coupon'])) {
                    $card_coupon = D('Card_new_coupon')->get_coupon_by_id($_POST['card_id']);
                    $now_coupon['card_price'] = $card_coupon['price'];
                    $now_coupon['merc_id'] = $card_coupon['id'];
                    unset($_SESSION['merc_id']);
                }
                if ((!empty($_POST['coupon_id']) && $_POST['coupon_id'] == $_SESSION['sysc_id']) || ($_POST['ticket'] && $_POST['coupon_id'] && $_POST['use_sys_coupon'])) {
                    $system_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                    $now_coupon['coupon_price'] = $system_coupon['price'];
                    $now_coupon['sysc_id'] = $system_coupon['id'];
                    unset($_SESSION['sysc_id']);
                }
            }
            //子商户设置不允许使用平台优惠抵扣
            if ($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0 && $now_merchant['sub_mch_discount'] != 1) {
                $now_coupon['coupon_price'] = 0;
                $now_coupon['sysc_id'] = 0;
            }

            if ($order_info['mer_id']) {
                $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->user_session['uid'], $order_info['mer_id']);
                if (empty($card_info)) {
                    $merchant_balance['card_money'] = 0;
                    $merchant_balance['card_give_money'] = 0;
                    $merchant_balance['card_discount'] = 10;
                } else {
                    $merchant_balance['card_money'] = $card_info['card_money'];
                    $merchant_balance['card_give_money'] = $card_info['card_money_give'];
                    if (!$use_discount || empty($card_info['discount']) || (isset($order_info['discount_status']) && !$order_info['discount_status'])) {
                        $merchant_balance['card_discount'] = 10;
                    } else {
                        $merchant_balance['card_discount'] = $card_info['discount'];
                    }
                }
            }

            //判断优惠券折扣
            if ($system_coupon['is_discount'] && $system_coupon['discount_value'] > 0 && $system_coupon['discount_value'] < 10) 
            {
                $tmp_wx_cheap = 0;

                $tmp_total_money = $order_info['order_total_money'];
                if ($order_info['order_type'] == 'store') {
                    $tmp_total_money -= $order_info['no_discount_money'];
                } else if ($order_info['business_type'] == 'foodshop') {
                    $tmp_total_money = $order_info['order_info']['can_discount_money'];
                }
                if ($merchant_balance['card_discount'] > 0) {
                    $tmp_total_money = sprintf("%.2f", $tmp_total_money * $merchant_balance['card_discount'] / 10);
                }
                if ($order_info['order_type'] == 'group') {
                    if ($order_info['order_type'] == 'group') {
                        //判断有没有使用微信，如果是微信，则检测此团购有没有微信优惠！
                        if ($this->is_app_browser) {
                            $now_group_ = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id' => $order_info['group_id']))->find();
                            $tmp_total_money = $order_info['order_num'] * $now_group_['wx_cheap'];
                        } elseif ($this->config['weixin_buy_follow_wechat'] == 2 && empty($now_user['is_follow'])) {
                            $tmp_total_money = 0;
                        } elseif ($this->config['weixin_buy_follow_wechat'] == 1 && empty($now_user['is_follow'])) {
                            $tmp_total_money = 0;
                        } elseif ($_SESSION['openid']) {
                            $now_group_ = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id' => $order_info['group_id']))->find();
                            $tmp_total_money = $order_info['order_num'] * $now_group_['wx_cheap'];
                        }

                    }
                    $tmp_total_money = $tmp_total_money - $tmp_wx_cheap;
                    if ($now_coupon['card_price'] > 0) {
                        if ($now_coupon['card_price'] < $tmp_total_money) {
                            $tmp_total_money -= $now_coupon['card_price'];
                        } else {
                            $tmp_total_money = 0;
                        }

                    }

                    if ($tmp_total_money > 0 && !empty($system_coupon) && $tmp_total_money > $system_coupon['order_money']) {
                        $now_coupon['coupon_price'] = round($tmp_total_money * (100 - $system_coupon['discount_value'] * 10) / 100, 2);
                    }
                }
            }
        }
            //用户信息
            $now_user = D('User')->get_user($this->user_session['uid']);


            if (empty($now_user)) {
                $this->error_tips('未获取到您的帐号信息，请重试！');
            }
            //判断积分是否够用 防止支付同时积分被改动


            if ($_POST['use_score'] && (empty($order_info['business_type']) || $order_info['business_type'] != 'mobile_recharge')) {
                if ($now_user['score_count'] < $_POST['score_used_count']) {
                    $this->error_tips('账户' . $this->config['score_name'] . '不够，请重试！');
                }
                if ($this->config['open_extra_price']) {

                    if (isset($order_info['extra_price']) && $order_info['extra_price'] > 0 && $order_info['extra_price'] < $_POST['score_deducte']) {
                        $this->error_tips($this->config['score_name'] . '抵扣金额错误！');
                    }
                } else {
                    if ($order_info['order_total_money'] < $_POST['score_deducte']) {
                        $this->error_tips($this->config['score_name'] . '抵扣金额错误！');
                    }
                }

                $order_info['score_used_count'] = $_POST['score_used_count'];
                $order_info['score_deducte'] = $this->config['user_score_use_percent'] > 0 ? bcdiv($_POST['score_used_count'], (float)$this->config['user_score_use_percent'], 2) : 0;
            } else {
                $order_info['score_used_count'] = 0;
                $order_info['score_deducte'] = 0;
            }
            $order_info['use_score'] = $_POST['use_score'];
            //这里为了解决app支付
            if ($_POST['use_balance'] == 1) {
                $order_info['use_balance'] = 1;
            } else {
                $order_info['use_balance'] = 0;
            }

            //子商户设置不允许使用平台余额支付
            if ($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0 && $now_merchant['sub_mch_sys_pay'] != 1) {
                $order_info['use_balance'] = 0;
            }
            //子商户设置不允许使用平台优惠抵扣
            if ($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0 && $now_merchant['sub_mch_discount'] != 1 || ($this->config['cash_nobind_phone_use_balance'] == 0 && empty($now_user['phone'])) || $now_user['forzen_score'] == 1) {
                $order_info['score_used_count'] = 0;
                $order_info['score_deducte'] = 0;
                $order_info['use_score'] = $_POST['use_score'];
            }

            if ($_POST['order_type'] == 'plat' && $this->config['open_extra_price'] == 1 && $order_info['business_type'] == 'foodshop') {
                $now_business_order = M('Foodshop_order')->where(array('order_id' => $order_info['business_id']))->find();
                $order_info['order_total_money'] += $now_business_order['extra_price'];
            } else if ($_POST['order_type'] == 'balance-appoint' && $this->config['open_extra_price'] == 1) {
                $order_info['order_total_money'] += $order_info['extra_price'];
            }

            //如果用户存在余额或使用了优惠券，则保存至订单信息。如果金额满足订单总价，则实时扣除并返回支付成功！若不够则不实时扣除，防止用户在付款过程中取消支付
            $wx_cheap = 0;
            if ($order_info['order_type'] == 'group') {
                //判断有没有使用微信，如果是微信，则检测此团购有没有微信优惠！
                if ($this->is_app_browser) {
                    $now_group = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id' => $order_info['group_id']))->find();
                    $wx_cheap = $order_info['order_num'] * $now_group['wx_cheap'];
                } elseif ($this->config['weixin_buy_follow_wechat'] == 2 && empty($now_user['is_follow'])) {
                    $this->error_tips('您未关注公众号，不能购买！请先关注公众号。');
                } elseif ($this->config['weixin_buy_follow_wechat'] == 1 && empty($now_user['is_follow'])) {
                    $wx_cheap = 0;
                } elseif ($_SESSION['openid']) {
                    $now_group = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id' => $order_info['group_id']))->find();
                    $wx_cheap = $order_info['order_num'] * $now_group['wx_cheap'];
                }
                $save_result = D('Group_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user, $wx_cheap);
            } else if ($order_info['order_type'] == 'meal' || $order_info['order_type'] == 'takeout' || $order_info['order_type'] == 'food' || $order_info['order_type'] == 'foodPad') {
                $save_result = D('Meal_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user, $order_info['order_type']);
            } else if ($order_info['order_type'] == 'weidian') {
                $save_result = D('Weidian_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
            } else if ($order_info['order_type'] == 'recharge') {
                $save_result = D('User_recharge_order')->web_befor_pay($order_info, $now_user);
            } else if ($order_info['order_type'] == 'appoint') {
                $save_result = D('Appoint_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
            } else if ($order_info['order_type'] == 'waimai') {
                $save_result = D('Waimai_order')->wap_befor_pay($order_info, $merchant_balance, $now_user);
            } else if ($order_info['order_type'] == 'wxapp') {
                $save_result = D('Wxapp_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
            } else if ($order_info['order_type'] == 'store') {
                $save_result = D('Store_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
            } else if ($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall') {
                $save_result = D('Shop_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
            } else if ($order_info['order_type'] == 'plat') {
                //定制商家桌次折扣
                if($order_info['order_info']['mer_table_discount']>0 && $order_info['order_info']['mer_table_discount']<100 )
                {
                    $discount = $order_info['order_info']['mer_table_discount'] / 100 ;
                    $reduce_money = round($order_info['order_info']['can_discount_table_money'] - $order_info['order_info']['can_discount_table_money'] * $discount,2);
                    $pay_money = $order_info['order_total_money'];
                    $pay_money = round($pay_money - $reduce_money,2);
                    $order_info['order_total_money'] = $pay_money;
                }
                $save_result = D('Plat_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
            } else if ($order_info['order_type'] == 'balance-appoint') {
                $save_result = D('Appoint_order')->wap_balace_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
            }


            if (cookie('is_house')) {
                $save_result['url'] = str_replace('wap.php', C('INDEP_HOUSE_URL'), $save_result['url']);
            }
            if ($save_result['error_code']) {
                $this->error_tips($save_result['msg']);
            } else if ($save_result['url']) {
                $this->success_tips($save_result['msg'], $save_result['url']);
            }

            //需要支付的钱
            $pay_money = round($save_result['pay_money'] * 100) / 100;

            if (in_array($order_info['order_type'], array('group', 'meal', 'weidian', 'takeout', 'food', 'foodPad', 'recharge', 'appoint', 'waimai', 'wxapp', 'store', 'shop', 'plat', 'balance-appoint')) && $order_info['mer_id']) {
                $mer_id = $order_info['mer_id'];
            } else {
                $this->config['merchant_ownpay'] = 0;
            }
            if ($order_info['pay_merchant_ownpay'] === false) {
                $this->config['merchant_ownpay'] = 0;
            }
            $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
            switch ($this->config['merchant_ownpay']) {
                case '0':
                    $pay_method = D('Config')->get_pay_method($notOnline, $notOffline, true);
                    break;
                case '1':
                    $pay_method = D('Config')->get_pay_method($notOnline, $notOffline, true);
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $mer_id))->find();
                    foreach ($merchant_ownpay as $ownKey => $ownValue) {
                        $ownValueArr = unserialize($ownValue);
                        if ($ownValueArr['open']) {
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
                        }
                    }

                    break;
                case '2':
                    $pay_method = array();
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $mer_id))->find();
                    foreach ($merchant_ownpay as $ownKey => $ownValue) {
                        $ownValueArr = unserialize($ownValue);
                        if ($ownValueArr['open']) {
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
                        }
                    }

                    if (empty($pay_method)) {
                        $pay_method = D('Config')->get_pay_method($notOnline, $notOffline, true);
                    }
                    break;
            }

            //配置服务商子商户支付
            if ($this->config['open_sub_mchid']) {
                $now_store = D('Merchant_store')->get_store_by_storeId($order_info['store_id']);
                $is_sub_mchid = false;
                if ($order_info['store_id'] != 0 && $now_store['open_sub_mchid'] && $now_store['sub_mch_id'] > 0) {
                    $pay_method['weixin']['config']['sub_mch_id'] = $now_store['sub_mch_id'];
                    $pay_method['weixin']['config']['is_own'] = 3;
                    $is_sub_mchid = true;
                } else if ($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
                    $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                    $pay_method['weixin']['config']['is_own'] = 2;
                    $is_sub_mchid = true;
                } else if ($order_info['business_type'] == 'house_village_pay' || $order_info['business_type'] == 'house_village_express') {
                    $now_village = D('House_village')->get_one($order_info['order_info']['village_id']);
                    if ($now_village['sub_mch_id'] > 0) {
                        $pay_method['weixin']['config']['is_own'] = 5;
                        $pay_method['weixin']['config']['sub_mch_id'] = $now_village['sub_mch_id'];
                        $is_sub_mchid = true;
                    }
                }

                if ($is_sub_mchid) {
                    $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                    $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                    $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
                    $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
                }
            }


            $order_id = $order_info['order_id'];
            if ($_POST['order_type'] == 'takeout' || $_POST['order_type'] == 'food' || $_POST['order_type'] == 'foodPad') {
                $order_table = 'Meal_order';
            } else if ($_POST['order_type'] == 'recharge') {
                $order_table = 'User_recharge_order';
                if (floatval($pay_money) != floatval($order_info['order_price'])) {
                    $this->error_tips('充值订单有误');
                }
            } else if ($_POST['order_type'] == 'balance-appoint') {
                $order_table = 'Appoint_order';
                //$order_info['order_type']='appoint';
            } else {
                $order_table = ucfirst($_POST['order_type']) . '_order';
            }

            //更新长id
            $nowtime = date("ymdHis");
            if ($_POST['order_type'] == 'balance-appoint') {
                $nowtime = date("mdHis");
                $orderid = $nowtime . sprintf("%04d", $this->user_session['uid']);;
            } else {
                $orderid = $nowtime . rand(10, 99) . sprintf("%08d", $this->user_session['uid']);
            }
            $data_tmp['pay_type'] = $_POST['pay_type'];
            $data_tmp['order_type'] = $_POST['order_type'];
            $data_tmp['order_id'] = $order_id;
            $data_tmp['orderid'] = $orderid;
            $data_tmp['addtime'] = $nowtime;
            if (!D('Tmp_orderid')->add($data_tmp)) {
                $this->error_tips('更新订单信息失败，请联系管理员');
            }

            $save_pay_id = D($order_table)->where(array("order_id" => $order_id))->setField('orderid', $orderid);

            //支付前保存在线支付金额
            $payment_money_date['payment_money'] = $pay_money;
            $payment_money_date['order_type'] =  $_POST['order_type'];
            $payment_money_date['order_id'] =  $_POST['order_id'];
            $payment_money_date['orderid'] =  $orderid;
            $payment_money_date['addtime'] = time();
            M('Payment_money_order')->add($payment_money_date);

            if (!$save_pay_id) {
                $this->error_tips('更新订单信息失败，请联系管理员');
            } else {
                $order_info['order_id'] = $orderid;
            }
            if (strpos($_POST['pay_type'], 'allinyun') !== false || ($this->user_session['openid'] && $_POST['pay_type'] == 'weixin' && $this->config['open_allinyun'] == 1)) {

                import('@.ORG.AccountDeposit.AccountDeposit');
                $deposit = new AccountDeposit('Allinyun');
                $allyun = $deposit->getDeposit();
                //$allinyun_user = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
                $allinyun_user = D('Deposit')->allinyun_user($this->user_session['uid']);
                if ($order_info['mer_id']) {
                    $allinyun_mer = D('Deposit')->get_merchant_info($order_info['mer_id'], 1);
                    $order_info['recieverId'] = $allinyun_mer['bizUserId'];
                }


                if ($allinyun_user && ($allinyun_mer['status'] == 1 || $_POST['order_type'] == 'recharge')) {
                    if (empty($allinyun_user['phone'])) {
                        $this->error_tips('您的云商通账号还未绑定手机无法完成支付，请先绑定手机号码', U('SetAccountDeposit/bindphone'));
                    }
                    $allyun->setUser($allinyun_user);
                    $pay_type = explode('_', $_POST['pay_type']);
                    $order_info['payerId'] = $allinyun_user['bizUserId'];
                    $order_info['openid'] = $this->user_session['openid'];
                    if ($_POST['pay_type'] == 'weixin') {
                        $order_info['pay_type'] = 'weixin';
                    } else {

                        $order_info['pay_type'] = $pay_type[1];
                    }
                    $order_info['source'] = 1;
                    $order_info['platform'] = $_POST['platform'] ? $_POST['platform'] : 'wap';


                    $res = $allyun->Pay($_POST['order_type'], $orderid, $pay_money, $order_info);
                    if ($res['status'] == 'OK' && $res['signedResult']['payInfo'] != '') {
                        // dump($res['signedResult']['payInfo']);
                        $this->assign('url', $res['signedResult']['payInfo']);
                        $this->display();
                    } else {
                        $this->error_tips($res['message']);
                    }
                    die;
                }

            }


            if ($this->config['open_juhepay'] == 1) {
                $now_user = D('User')->get_user($this->user_session['uid']);
                $order_info['openid'] = $now_user['openid'];
                $order_info['pay_type'] = ($_POST['pay_type'] == 'alipay' || $_POST['pay_type']=='alipayh5' )? 'alipay_scan' : $_POST['pay_type'];
                $order_info['order_id'] = $_POST['order_type'] . '_' . $order_info['order_id'];

                $order_info['order_total_money'] = $pay_money;
                fdump($order_info,'ssss');
                $lowfeepay = new LowFeePay('juhepay');
                if($mer_id){
                    $mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$mer_id))->find();
                    if(!empty($mer_juhe)){
                        $lowfeepay->userId =$mer_juhe['userid'];
                        $order_info['is_own'] =1 ;
                        $order_info['order_id'] =  $order_info['order_id'].'_1';
                    }
                    $go_pay_param = $lowfeepay->pay($order_info);
                }else{
                    $go_pay_param = $lowfeepay->pay($order_info);
                }
                fdump($go_pay_param,'ssss',1);
                if (($_POST['pay_type'] == 'alipay' || $_POST['pay_type'] == 'alipayh5') && $go_pay_param['status'] == 1 && $go_pay_param['info'] != '') {
                    redirect($go_pay_param['info']);
                }
            } else {
                if (empty($pay_method)) {
                    $this->error_tips('系统管理员没开启任一一种支付方式！');
                }
                if (empty($pay_method[$_POST['pay_type']])) {
                    $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
                }

                $pay_class_name = ucfirst($_POST['pay_type']);
                $import_result = import('@.ORG.pay.' . $pay_class_name);
                if (empty($import_result)) {
                    $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
                }
                $pay_class = new $pay_class_name($order_info, $pay_money, $_POST['pay_type'], $pay_method[$_POST['pay_type']]['config'], $this->user_session, 1);
                $go_pay_param = $pay_class->pay();
            }


            //$pay_method['allinpay']['config']['is_own'] = 1;

            if (empty($go_pay_param['error'])) {
                if (!empty($go_pay_param['url'])) {
                    $this->assign('url', $go_pay_param['url']);
                    $this->display();
                } else if (!empty($go_pay_param['form'])) {
                    $this->assign('form', $go_pay_param['form']);
                    $this->display();
                } else if (!empty($go_pay_param['qrcode'])) {
                    $this->assign('qrcode', $go_pay_param['qrcode']);
                    $this->display('go_pay_qrcode');
                } else if (!empty($go_pay_param['weixin_param'])) {
                    if ($pay_method['weixin']['config']['is_own']) {
                        C('open_authorize_wxpay', true);
                        $share = new WechatShare($this->config, $_SESSION['openid']);
                        $this->hideScript = $share->gethideOptionMenu($mer_id);
                        $arr['hidScript'] = $this->hideScript;
                        $redirctUrl = C('config.site_url') . '/' . $this->indep_house . '?g=Wap&c=Pay&a=weixin_back&order_type=' . $order_info['order_type'] . '&order_id=' . $order_info['order_id'] . '&own_mer_id=' . $order_info['mer_id'];
                    } else {
                        $redirctUrl = C('config.site_url') . '/' . $this->indep_house . '?g=Wap&c=Pay&a=weixin_back&order_type=' . $order_info['order_type'] . '&order_id=' . $order_info['order_id'];
                    }
                    $arr['redirctUrl'] = $go_pay_param['redirctUrl'] ? $go_pay_param['redirctUrl'] : $redirctUrl;
                    $arr['pay_money'] = $pay_money;
                    $arr['weixin_param'] = json_decode($go_pay_param['weixin_param']);
                    $arr['error'] = 0;
                    echo json_encode($arr);
                    die;
                } else {
                    $this->error_tips('调用支付发生错误，请重试。');
                }
            } else {
                $this->error_tips($go_pay_param['msg']);
            }

    }
    public function alipay()
    {

        if($_GET['ticket']!=''){
            $_GET['use_merchant_balance'] = $_GET['use_merchant_money'];
            $_GET['use_balance'] = $_GET['use_balance_money'];
        }
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！',U('Login/index'));
        }
        if(!in_array($_GET['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint'))){
            $this->error_tips('订单来源无法识别，请重试。');
        }
        switch($_GET['order_type']){
            case 'group':
                $now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            case 'meal':
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order = D('Meal_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']), false,  $_GET['order_type']);
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            case 'recharge':
                $now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            case 'appoint':
                $now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            case 'waimai':
                $now_order = D('Waimai_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),false,$_GET['order_type']);
                if ($now_order['order_info']['pay_type'] !== $_GET['pay_type']) {
                    $this->error_tips('非法的订单');
                }
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->get_pay_order(0,intval($_GET['order_id']));
                break;
            case 'store':
                $now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
                break;
            case 'shop':
            case 'mall':
                $now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
                break;
            case 'plat':
                $now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
                break;
            case 'balance-appoint':
                $now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval($_GET['order_id']));
                break;

            default:
                $this->error_tips('非法的订单');
        }

        if($now_order['error'] == 1){
            $this->error_tips($now_order['msg'],$now_order['url']);
        }
        $order_info = $now_order['order_info'];

        $now_merchant = D('Merchant')->get_info($order_info['mer_id']);
        if($now_merchant['status']==3){
            $this->error_tips('该商家状态异常，无法支付');
        }
        $use_discount = true;
        if($this->config['open_score_discount'] && empty($_GET['ticket'])){
            switch($now_merchant['user_discount_type']){
                case 1 :   //优惠
                    $order_info['score_discount_type'] = 2;
                    break;
                case 2 :   //积分
                    $use_discount =false;
                    $order_info['score_discount_type'] = 1;
                    break;
                case 3 :   //2选1
                    $use_discount =  $_GET['discount_type'] == 'score'?false:true;
                    break;
            }
            if($_GET['discount_type']=="score"){
                $order_info['score_discount_type'] = 1;
            }else if($_GET['discount_type']=="coupon"){
                $order_info['score_discount_type'] = 2;
            }

        }

        if($_GET['order_type'] != 'recharge'  && $_GET['order_type'] != 'weidian'  && (empty($order_info['business_type'])||$order_info['business_type']!='card_new_recharge')) {
            //优惠券
            if ($use_discount && (!isset($order_info['discount_status']) || $order_info['discount_status'])) {
                if ((!empty($_GET['card_id'])&&$_GET['card_id']==$_SESSION['merc_id'])||($_GET['ticket']&&$_GET['card_id']&&$_GET['use_mer_coupon'])) {
                    $card_coupon = D('Card_new_coupon')->get_coupon_by_id($_GET['card_id']);
                    $now_coupon['card_price'] = $card_coupon['price'];
                    $now_coupon['merc_id'] = $card_coupon['id'];
                    unset($_SESSION['merc_id']);
                }
                if ((!empty($_GET['coupon_id'])&&$_GET['coupon_id']==$_SESSION['sysc_id'])||($_GET['ticket']&&$_GET['coupon_id']&&$_GET['use_sys_coupon'])) {
                    $system_coupon = D('System_coupon')->get_coupon_by_id($_GET['coupon_id']);
                    $now_coupon['coupon_price'] = $system_coupon['price'];
                    $now_coupon['sysc_id'] = $system_coupon['id'];
                    unset($_SESSION['sysc_id']);
                }
            }
            if($order_info['mer_id'] ){
                $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->user_session['uid'],$order_info['mer_id']);
                if(empty($card_info)){
                    $merchant_balance['card_money'] = 0;
                    $merchant_balance['card_give_money'] = 0;
                    $merchant_balance['card_discount'] = 10;
                }else{
                    $merchant_balance['card_money'] = $card_info['card_money'];
                    $merchant_balance['card_give_money'] = $card_info['card_money_give'];
                    if(!$use_discount || empty($card_info['discount'])||(isset($order_info['discount_status'])&&!$order_info['discount_status'])){
                        $merchant_balance['card_discount'] = 10;
                    }else{
                        $merchant_balance['card_discount'] = $card_info['discount'];
                    }
                }
            }
        }

//        if(!empty($_GET['ticket'])){
//            $merchant_balance = array();
//            $merchant_balance['card_give_money'] = 0;
//            $merchant_balance['card_discount'] = 10;
//        }

        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);


        if ($_GET['use_score']) {
            if($now_user['score_count']<$_GET['score_used_count']){
                $this->error_tips('账户'.$this->config['score_name'].'不够，请重试！');
            }
            $order_info['score_used_count']=$_GET['score_used_count'];
            $order_info['score_deducte']=$_GET['score_deducte'];
        }else{
            $order_info['score_used_count']=0;
            $order_info['score_deducte']=0;
        }
        $order_info['use_score'] = $_GET['use_score'];
        if($_GET['use_balance']==0){
            $order_info['use_balance'] = 0;
        }else{
            $order_info['use_balance'] = 1;
        }
        if(empty($now_user)){
            $this->error_tips('未获取到您的帐号信息，请重试！');
        }

        //如果用户存在余额或使用了优惠券，则保存至订单信息。如果金额满足订单总价，则实时扣除并返回支付成功！若不够则不实时扣除，防止用户在付款过程中取消支付
        $wx_cheap = 0;
        if($order_info['order_type'] == 'group'){
            //判断有没有使用微信，如果是微信，则检测此团购有没有微信优惠！
            if($this->config['weixin_buy_follow_wechat'] == 2 && empty($now_user['is_follow'])){
                $this->error_tips('您未关注公众号，不能购买！请先关注公众号。');
            }elseif($this->config['weixin_buy_follow_wechat'] == 1 && empty($now_user['is_follow'])){
                $wx_cheap = 0;
            }elseif($_SESSION['openid']){
                $now_group = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id'=>$order_info['group_id']))->find();
                $wx_cheap = $order_info['order_num'] * $now_group['wx_cheap'];
            }elseif($this->is_app_browser){
                $now_group = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id'=>$order_info['group_id']))->find();
                $wx_cheap = $order_info['order_num'] * $now_group['wx_cheap'];
            }
            $save_result = D('Group_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user,$wx_cheap);
        }else if($order_info['order_type'] == 'meal' || $order_info['order_type'] == 'takeout' || $order_info['order_type'] == 'food' || $order_info['order_type'] == 'foodPad'){
            $save_result = D('Meal_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user, $order_info['order_type']);
        }else if($order_info['order_type'] == 'weidian'){
            $save_result = D('Weidian_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'recharge'){
            $save_result = D('User_recharge_order')->web_befor_pay($order_info,$now_user);
        }else if($order_info['order_type'] == 'appoint'){
            $save_result = D('Appoint_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'waimai'){
            $save_result = D('Waimai_order')->wap_befor_pay($order_info,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'wxapp'){
            $save_result = D('Wxapp_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'store'){
            $save_result = D('Store_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){
            $save_result = D('Shop_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
        }else if($order_info['order_type'] == 'plat'){
            $save_result = D('Plat_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'balance-appoint'){
            $save_result = D('Appoint_order')->wap_balace_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }


        if($save_result['error_code']){
            $this->error_tips($save_result['msg']);
        }else if($save_result['url']){
            $this->success_tips($save_result['msg'],$save_result['url']);
        }

        //需要支付的钱
        $pay_money = round($save_result['pay_money']*100)/100;

        if(in_array($order_info['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $order_info['mer_id']){
            $mer_id = $order_info['mer_id'];
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);

        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }
                break;
            case '2':
                $pay_method = array();
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                }
                break;
        }

        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$_GET['pay_type']])){
            //$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($_GET['pay_type']);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        $order_id = $order_info['order_id'];
        if ($_GET['order_type'] == 'takeout' || $_GET['order_type'] == 'food' || $_GET['order_type'] == 'foodPad') {
            $order_table = 'Meal_order';
        }else if($_GET['order_type']=='recharge'){

            if(floatval($pay_money)!=floatval($order_info['order_price'])){
                $this->error_tips('充值订单有误');
            }
            $order_table = 'User_recharge_order';
        }else if($_GET['order_type']=='balance-appoint'){
            $order_table = 'Appoint_order';
        } else{
            $order_table = ucfirst($_GET['order_type']).'_order';
        }

        $nowtime = date("ymdHis");
        $orderid = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);

        $_SESSION['alipay_order'] =array(
            'order_type'=>$_GET['order_type'],
            'orderid'=>$orderid,
        );

        $data_tmp['pay_type'] = 'alipay';
        $data_tmp['order_type'] = $_GET['order_type'];
        $data_tmp['order_id'] = $order_id;
        $data_tmp['orderid'] = $orderid;
        $data_tmp['addtime'] = $nowtime;

        if(!D('Tmp_orderid')->add($data_tmp)){
            $this->error_tips('更新失败，请联系管理员');
        }

        $save_pay_id = D($order_table)->where(array("order_id"=>$order_id))->setField('orderid',$orderid);


        //支付前保存在线支付金额
        $payment_money_date['payment_money'] = $pay_money;
        $payment_money_date['order_type'] =  $_GET['order_type'];
        $payment_money_date['order_id'] =  $order_id;
        $payment_money_date['orderid'] =  $orderid;
        $payment_money_date['addtime'] = time();
        M('Payment_money_order')->add($payment_money_date);

        if(!$save_pay_id){
            $this->error_tips('更新失败，请联系管理员');
        }else{
            $order_info['order_id']=$orderid;
        }

        $pay_class = new $pay_class_name($order_info,$pay_money,$_GET['pay_type'],$pay_method[$_GET['pay_type']]['config'],$this->user_session,1);
        $go_pay_param = $pay_class->pay();
        if(empty($go_pay_param['error'])){
            header("Content-type: text/html; charset=utf-8");
            echo $go_pay_param['form'];
            die;
            if(!empty($go_pay_param['url'])){
                $this->assign('url',$go_pay_param['url']);
                $this->display();
            }else if(!empty($go_pay_param['form'])){
                $this->assign('form',$go_pay_param['form']);
                $this->display();
            }else if(!empty($go_pay_param['weixin_param'])){
                if($pay_method['weixin']['config']['is_own']){
                    C('open_authorize_wxpay',true);
                    $share = new WechatShare($this->config,$_SESSION['openid']);
                    $this->hideScript = $share->gethideOptionMenu($mer_id);
                    $this->assign('hideScript', $this->hideScript);
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Pay&a=weixin_back&order_type='.$order_info['order_type'].'&order_id='.$order_info['order_id'].'&own_mer_id='.$order_info['mer_id'];
                }else{
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Pay&a=weixin_back&order_type='.$order_info['order_type'].'&order_id='.$order_info['order_id'];
                }
                $this->assign('redirctUrl',$redirctUrl);
                $this->assign('pay_money',$pay_money);
                $this->assign('weixin_param',json_decode($go_pay_param['weixin_param']));
                $this->display('weixin_pay');
            }else{
                $this->error_tips('调用支付发生错误，请重试。');
            }
        }else{
            $this->error_tips($go_pay_param['msg']);
        }
    }

    public function get_orderid($table,$orderid,$offline=0){
        $order =  D($table);
        $tmp_orderid = D('Tmp_orderid');
        if($offline){
            $now_order = $order->where(array('orderid'=>$orderid))->find();
        }else{
            $now_order = $order->where(array('orderid'=>$orderid))->find();
            if(empty($now_order)){
                $res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
                $now_order = $order->where(array('order_id'=>$res['order_id']))->find();
                $order->where(array('order_id'=>$res['order_id']))->setField('orderid',$orderid);
                $now_order['orderid']=$orderid;
            }
        }
        if(empty($now_order)){
            $this->error_tips('该订单不存在');
        }else{
//            $tmp_orderid->where(array('order_id'=>$now_order['order_id']))->delete();
        }

        return $now_order;
    }

    //微信同步回调页面
    public function weixin_back(){
        switch($_GET['order_type']){
            case 'group':
                $now_order =$this->get_orderid('Group_order',$_GET['order_id']);
                break;
            case 'meal':
                $now_order =$this->get_orderid('Meal_order',$_GET['order_id']);
                break;
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order =$this->get_orderid('Meal_order',$_GET['order_id']);
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Weidian_order',$_GET['order_id']);
                break;
            case 'recharge':
                $now_order =$this->get_orderid('User_recharge_order',$_GET['order_id']);
                break;
            case 'appoint':
                $now_order =$this->get_orderid('Appoint_order',$_GET['order_id']);
                break;
            case 'waimai':
                $now_order =$this->get_orderid('Waimai_order',$_GET['order_id']);
                break;
            case 'wxapp':
                $now_order =$this->get_orderid('Wxapp_order',$_GET['order_id']);
                break;
            case 'store':
                $now_order =$this->get_orderid('Store_order',$_GET['order_id']);
                break;
            case 'shop':
            case 'mall':
                $now_order = $this->get_orderid('Shop_order', $_GET['order_id']);
                break;
            case 'plat':
                $now_order = $this->get_orderid('Plat_order', $_GET['order_id']);
                break;
            case 'balance-appoint':
                $now_order = $this->get_orderid('Appoint_order', $_GET['order_id']);
                break;
            default:
                $this->error_tips('非法的订单');
        }


        if(empty($now_order)){
            $this->error_tips('该订单不存在');
        }
        $now_order['order_type'] = $_GET['order_type'];
        if($now_order['paid']==1 &&$now_order['order_type']!='balance-appoint'){
            switch($_GET['order_type']){
                case 'group':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=group_order&order_id='.$now_order['order_id'];
                    break;
                case 'meal':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'takeout':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'food':
                case 'foodPad':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'weidian':
                    $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']));
                    $this->NoticeWDAsyn($now_order['orderid']);
                    break;
                case 'appoint':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
                case 'waimai':
                    $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                    break;
                case 'recharge':
                    $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                    break;
                case 'wxapp':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                    break;
                case 'store':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_detail&order_id='.$now_order['order_id'];
                    break;
                case 'shop':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'mall':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'plat':
                    $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                    break;
                case 'balance-appoint':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
            }
            redirect($redirctUrl);exit;
        }


        if(in_array($_GET['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $now_order['mer_id']){
            $mer_id = $now_order['mer_id'];
        }else if($_GET['order_type'] == 'plat'){
            $get_order_info = D('Plat_order')->get_pay_order(0,$now_order['order_id']);
            if($get_order_info['order_info']['pay_merchant_ownpay'] && $get_order_info['order_info']['mer_id']){
                $mer_id = $get_order_info['order_info']['mer_id'];
            }else{
                $this->config['merchant_ownpay'] = 0;
            }
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method();
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){

                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }


                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){

                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }

                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method();
                }

                break;
        }

//        if($now_order['order_type']=='plat' && ($now_order['business_type']=='house_village_express' || $now_order['business_type']=='house_village_pay')){
//            $house_villiage_order = M(ucfirst($now_order['business_type'].'_order'))->where(array('order_id'=>$now_order['business_id']))->find();
//            $now_house_village  = M('House_village')->where(array('village_id'=>$house_villiage_order['village_id']))->find();
//        }


        if($mer_id) {
            $now_merchant = D('Merchant')->get_info($mer_id);
            if ( $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
                $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
                $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
                $pay_method['weixin']['config']['is_own'] = 2 ;
            }
        }
        if($now_order['order_type']=='plat' && ($now_order['business_type']=='house_village_express' || $now_order['business_type']=='house_village_pay')){
            $house_order =  D('Plat_order')->get_pay_order($this->user_session['uid'], intval($now_order['order_id']));
            $now_village = D('House_village')->get_one($house_order['order_info']['order_info']['village_id']);

            if ($now_village['sub_mch_id'] > 0) {
                $pay_method['weixin']['config']['is_own'] = 5;
                $pay_method['weixin']['config']['sub_mch_id'] = $now_village['sub_mch_id'];
                $is_sub_mchid = true;
            }
            $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
            $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
            $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
            $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
            //$pay_method['weixin']['config']['is_own'] = 5;
        }

        $now_store = D('Merchant_store')->get_store_by_storeId($now_order['store_id']);
        $is_sub_mchid = false;
        if ($now_order['store_id'] != 0 && $now_store['open_sub_mchid'] && $now_store['sub_mch_id'] > 0){
            $pay_method['weixin']['config']['sub_mch_id']    = $now_store['sub_mch_id'];
            $pay_method['weixin']['config']['is_own']         = 3;
            $is_sub_mchid = true;
        }
        if($is_sub_mchid){
            $pay_method['weixin']['config']['pay_weixin_mchid']       = $this->config['pay_weixin_sp_mchid'];
            $pay_method['weixin']['config']['pay_weixin_key']         = $this->config['pay_weixin_sp_key'];
            $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
            $pay_method['weixin']['config']['pay_weixin_client_key']  = $this->config['pay_weixin_sp_client_key'];
        }
        $now_store = D('Merchant_store')->get_store_by_storeId($now_order['store_id']);

        if ($now_order['store_id'] != 0 && $now_store['open_sub_mchid'] && $now_store['sub_mch_id'] > 0){
            $pay_method['weixin']['config']['sub_mch_id']    = $now_store['sub_mch_id'];
            $pay_method['weixin']['config']['is_own']         = 3;

        }
        $_GET['order_id'] = $now_order['order_id'];
        $now_order['order_id']  =   $now_order['orderid'];
        $import_result = import('@.ORG.pay.Weixin');
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        $pay_class = new Weixin($now_order,0,'weixin',$pay_method['weixin']['config'],$this->user_session,1);
        $go_query_param = $pay_class->query_order();



        if($go_query_param['error'] === 0){
            //判断回调金额
            if($payment_money_order = M('Payment_money_order')->where(array('orderid'=>$now_order['order_id'],'order_type'=>$now_order['order_type']))->find()){
                M('Payment_money_order')->where(array('orderid'=>$now_order['order_id'],'order_type'=>$now_order['order_type']))->setField('paid_money',$go_query_param['order_param']['pay_money']);
                if($payment_money_order['payment_money'] > $go_query_param['order_param']['pay_money']){
                    $this->error_tips('支付异常');
                }
            }else{
                $this->error_tips('支付异常');
            }

            $go_query_param['order_param']['return']=1;
            switch($_GET['order_type']){
                case 'group':
                    D('Group_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'meal':
                case 'takeout':
                case 'food':
                case 'foodPad':
                    D('Meal_order')->after_pay($go_query_param['order_param'], $_GET['order_type']);
                    break;
                case 'weidian':
                    $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                    if(($pay_info['error']==0) && isset($pay_info['url'])){  /***异步通知***/
                        $this->NoticeWDAsyn($now_order['orderid']);
                    }
                    break;
                case 'recharge':
                    D('User_recharge_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'waimai':
                    D('Waimai_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'appoint':

                    D('Appoint_order')->after_pay($go_query_param['order_param']);

                    break;
                case 'balance-appoint':

                    D('Appoint_order')->balance_after_pay($go_query_param['order_param']);

                    break;
                case 'wxapp':
                    D('Wxapp_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'store':
                    D('Store_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'shop':
                case 'mall':
                    D('Shop_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'plat':
                    D('Plat_order')->after_pay($go_query_param['order_param']);
                    break;
            }
        }
        switch($_GET['order_type']){
            case 'group':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=group_order&order_id='.$_GET['order_id'];
                break;
            case 'meal':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Meal&a=detail&orderid='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'takeout':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Takeout&a=order_detail&order_id='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'food':
            case 'foodPad':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Food&a=order_detail&order_id='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'weidian':
                $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                $this->NoticeWDAsyn($now_order['orderid']);
                break;
            case 'appoint':
            case 'balance-appoint':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=appoint_order&order_id='.$_GET['order_id'];
                break;
            case 'waimai':
                $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$_GET['order_id'];
                break;
            case 'recharge':
                $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                break;
            case 'wxapp':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$_GET['order_id'];
                break;
            case 'store':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_detail&order_id='.$_GET['order_id'];
                break;
            case 'shop':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $_GET['order_id'];
                break;
            case 'mall':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $_GET['order_id'];
                break;
            case 'plat':
                $redirctUrl = D('Plat_order')->get_order_url($_GET['order_id'],true);
                break;
        }
        if($go_query_param['error'] == 1){
            $this->error_tips('校验时发生错误！'.$go_query_param['msg'],$redirctUrl);
        }else{
            redirect($redirctUrl);
        }
    }

    /***异步通知*微店**/
    public function NoticeWDAsyn($order_id){
        $now_order = M('Weidian_order')->field(true)->where(array('orderid'=>trim($order_id)))->find();
        if(!empty($now_order) && ($now_order['pay_type']=='weixin' || $now_order['pay_type']=='alipay')){
            $wdAsynarr=array('order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']);
            $wdAsynarr['salt'] = C('config.weidian_sign');
            ksort($wdAsynarr);
            $wdAsynarr['sign_key'] = sha1(http_build_query($wdAsynarr));
            $wdAsynarr['request_time'] = time();
            $returnarr=httpRequest($this->config['weidian_url'].'/api/pay_notify.php','POST',$wdAsynarr);
            if(empty($returnarr['1'])){
                $returnarr=httpRequest($this->config['weidian_url'].'/api/pay_notify.php','POST',$wdAsynarr);
            }
        }
    }

    //异步通知
    public function notify_url(){
        $pay_method = D('Config')->get_pay_method();
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$_GET['pay_type']])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_type = isset($_GET['paytype']) ? $_GET['paytype'] : $_GET['pay_type'];
        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        $pay_class = new $pay_class_name('', '', $pay_type, $pay_method[$pay_type]['config'], $this->user_session, 1);
        $notify_return = $pay_class->notice_url();

        if(empty($notify_return['error'])){

        }else{
            $this->error_tips($notify_return['msg']);
        }
    }

    //跳转通知
    public function return_url(){
        $pay_type = isset($_GET['paytype']) ? $_GET['paytype'] : $_GET['pay_type'];
        if($pay_type == 'weixin'){
            $array_data = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            if($array_data && $array_data['attach'] != 'weixin'){
                $_GET['own_mer_id'] = $array_data['attach'];
            }
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method();
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }

                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method();
                }

                break;
        }
        if($_GET['own_mer_id']) {
            $now_merchant = D('Merchant')->get_info($_GET['own_mer_id']);
            if ( $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
                $pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
                $pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
                $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
                $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
                $pay_method['weixin']['config']['is_own'] = 2 ;
            }
        }
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$pay_type])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }
        $fileContent = file_get_contents("php://input");
        $xml_data = json_decode(json_encode(simplexml_load_string($fileContent, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        $out_trade_no  = explode('_',$xml_data['out_trade_no']);
        $trade_type = $out_trade_no[0];
        $orderid = $out_trade_no[1];
        $is_own = $out_trade_no[2];

        if ( $this->config['open_sub_mchid'] && $xml_data['sub_mch_id'] ) {
            $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
            $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
            $pay_method['weixin']['config']['sub_mch_id'] = $xml_data['sub_mch_id'];
            $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
            $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
            $pay_method['weixin']['config']['is_own'] = 2 ;
        }

        if($xml_data['sub_mch_id']!='' && $is_own == 5){
            $now_order =$this->get_orderid(ucfirst($trade_type),$orderid);
            $house_order =  D('Plat_order')->get_pay_order($this->user_session['uid'], intval($now_order['order_id']));
            $now_village = D('House_village')->get_one($house_order['order_info']['order_info']['village_id']);

            if ($now_village['sub_mch_id'] > 0) {
                $pay_method['weixin']['config']['is_own'] = 5;
                $pay_method['weixin']['config']['sub_mch_id'] = $now_village['sub_mch_id'];
                $is_sub_mchid = true;
            }
            $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
            $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
            $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
            $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
        }

        $pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,1);
        $get_pay_param = $pay_class->return_url();



        $get_pay_param['order_param']['return']=1;
        $offline = $pay_type!='offline'?false:true;

        if(empty($get_pay_param['error'])){

            //在线支付回调判断是否与支付前金额相等
            if($pay_type!='offline'){

                if($payment_money_order = M('Payment_money_order')->where(array('orderid'=>$orderid,'order_type'=>$trade_type))->find()){
                    M('Payment_money_order')->where(array('orderid'=>$orderid,'order_type'=>$trade_type))->setField('paid_money',$get_pay_param['order_param']['pay_money']);
                    if($payment_money_order['payment_money'] > $get_pay_param['order_param']['pay_money']){
                        $this->error_tips('支付异常');
                    }
                }else{
                    $this->error_tips('支付异常');
                }
            }


            if($get_pay_param['order_param']['order_type'] == 'group'){
                $now_order = $this->get_orderid('Group_order',$get_pay_param['order_param']['order_id'],$offline);
//                $get_pay_param['order_param']['order_id']=$offline?$get_pay_param['order_param']['order_id']:$now_order['orderid'];
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);

            }else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food' || $get_pay_param['order_param']['order_type'] == 'foodPad'){
                $now_order = $this->get_orderid('Meal_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Meal_order')->after_pay($get_pay_param['order_param'], $get_pay_param['order_param']['order_type']);
            }else if($get_pay_param['order_param']['order_type'] == 'weidian'){
                $now_order = $this->get_orderid('Weidian_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Weidian_order')->after_pay($get_pay_param['order_param']);
                if(($pay_info['error']==0) && isset($pay_info['url']) && ($get_pay_param['order_param']['pay_type'] == 'weixin')){  /***异步通知***/
                    $this->NoticeWDAsyn($get_pay_param['order_param']['order_id']);
                }
            }else if($get_pay_param['order_param']['order_type'] == 'recharge'){
                $now_order = $this->get_orderid('User_recharge_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'appoint'){
                $now_order = $this->get_orderid('Appoint_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];

                $pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);

            }else if($get_pay_param['order_param']['order_type'] == 'waimai'){
                $now_order = $this->get_orderid('Waimai_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'wxapp'){
                $now_order = $this->get_orderid('Wxapp_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Wxapp_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'store'){
                $now_order = $this->get_orderid('Store_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Store_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'shop' || $get_pay_param['order_param']['order_type'] == 'mall'){
                $now_order = $this->get_orderid('Shop_order', $get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Shop_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'plat'){
                $now_order = $this->get_orderid('Plat_order', $get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Plat_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'balance-appoint'){
                $now_order = $this->get_orderid('Appoint_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Appoint_order')->balance_after_pay($get_pay_param['order_param']);
            }else{
                $this->error_tips('订单类型非法！请重新下单。');
            }

            $urltype = isset($_GET['urltype']) ? $_GET['urltype'] : '';
            if(empty($pay_info['error'])){
                if($get_pay_param['order_param']['pay_type'] == 'weixin'){
                    exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
                } elseif ($get_pay_param['order_param']['pay_type'] == 'baidu') {//百度的异步通知返回
                    exit("<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>");
                } elseif ('unionpay' == $get_pay_param['order_param']['pay_type'] && $urltype == 'back') {
                    exit("验签成功");
                } elseif ('weifutong' == $get_pay_param['order_param']['pay_type'] && $urltype == 'back') {
                    exit("success");
                }
                $pay_info['msg'] = '订单付款成功！现在跳转.';
            }
            if(empty($pay_info['url'])){
                $this->error_tips($pay_info['msg']);
            }else{
                if(cookie('is_house')){
                    $pay_info['url'] = str_replace('wap.php',C('INDEP_HOUSE_URL'),$pay_info['url']);
                }else{
                    $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                }
                if ($pay_info['error'] && $urltype == 'front') {
                    $this->redirect($pay_info['url']);
                    exit;
                }
                $this->assign('pay_info', $pay_info);
                $this->display('after_pay');
            }
        }else{
            $this->error_tips($get_pay_param['msg']);
        }
    }
    public function ccb_return(){
        $order_id_arr = explode('_',$_GET['ORDERID']);
        $order_type = $order_id_arr[0];
        $order_id = $order_id_arr[1];

        switch($order_type){
            case 'group':
                $now_order = D('Group_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'meal':
                $now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'recharge':
                $now_order = D('User_recharge_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'appoint':
                $now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'balance-appoint':
                $now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'waimai':
                $now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'store':
                $now_order = D('Store_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'shop':
            case 'mall':
                $now_order = D('Shop_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'plat':
                $now_order = D('Plat_order')->where(array('orderid'=>$order_id))->find();
                break;
            default:
                $this->error_tips('非法的订单');
        }
        if($now_order['paid'] && $order_type!='balance-appoint'){
            switch($order_type){
                case 'group':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
                    break;
                case 'meal':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'takeout':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'food':
                case 'foodPad':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'weidian':
                    $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                    break;
                case 'appoint':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
                case 'waimai':
                    $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                    break;
                case 'recharge':
                    $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                    break;
                case 'wxapp':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                    break;
                case 'store':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_detail&order_id='.$now_order['order_id'];
                    break;
                case 'shop':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'mall':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'plat':
                    $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                    break;
            }
            redirect($redirctUrl);exit;
        }

        if(in_array($order_type,array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $now_order['mer_id']){
            $mer_id = $now_order['mer_id'];
        }else if($order_type == 'plat'){
            $get_order_info = D('Plat_order')->get_pay_order(0,$now_order['order_id']);
            if($get_order_info['order_info']['pay_merchant_ownpay'] && $get_order_info['order_info']['mer_id']){
                $mer_id = $get_order_info['order_info']['mer_id'];
            }else{
                $this->config['merchant_ownpay'] = 0;
            }
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method(0, 0, true);
                }
                break;
        }
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }

        $pay_type = 'ccb';
        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        $pay_class = new $pay_class_name('', '', $pay_type, $pay_method[$pay_type]['config'],$this->user_session,$this->is_app_browser ? 2 : 1);
        $go_query_param = $pay_class->query_order();

        $offline = false;
        if($go_query_param['error'] === 0){
            $go_query_param['order_param']['pay_money'] = isset($go_query_param['order_param']['pay_money']) && $go_query_param['order_param']['pay_money'] ? $go_query_param['order_param']['pay_money'] : $total_fee;
            if($order_type == 'group'){
                $now_order = $this->get_orderid('Group_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Group_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'meal' || $order_type == 'takeout' || $order_type == 'food' || $order_type == 'foodPad'){
                $now_order = $this->get_orderid('Meal_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Meal_order')->after_pay($go_query_param['order_param'], $order_type);
            }else if($order_type == 'weidian'){
                $now_order = $this->get_orderid('Weidian_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                if(($pay_info['error']==0) && isset($pay_info['url']) && ($go_query_param['order_param']['pay_type'] == 'weixin')){  /***异步通知***/
                    $this->NoticeWDAsyn($go_query_param['order_param']['order_id']);
                }
            }else if($order_type == 'recharge'){
                $now_order = $this->get_orderid('User_recharge_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('User_recharge_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'appoint'){
                $now_order = $this->get_orderid('Appoint_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];


                D('Appoint_order')->after_pay($go_query_param['order_param']);

                // $pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'balance-appoint'){
                $now_order = $this->get_orderid('Appoint_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                D('Appoint_order')->balance_after_pay($go_query_param['order_param']);
            }else if($order_type == 'waimai'){
                $now_order = $this->get_orderid('Waimai_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Waimai_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'wxapp'){
                $now_order = $this->get_orderid('Wxapp_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Wxapp_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'store'){
                $now_order = $this->get_orderid('Store_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Store_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'shop' || $order_type == 'mall'){
                $now_order = $this->get_orderid('Shop_order', $go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Shop_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'plat'){
                $now_order = $this->get_orderid('Plat_order', $go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Plat_order')->after_pay($go_query_param['order_param']);
            }else{
                $this->error_tips('订单类型非法！请重新下单。');
            }
        }
        switch($order_type){
            case 'group':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
                break;
            case 'meal':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'takeout':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'food':
            case 'foodPad':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'weidian':
                $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                break;
            case 'appoint':
            case 'balance-appoint':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
                break;
            case 'waimai':
                $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                break;
            case 'recharge':
                $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                break;
            case 'wxapp':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                break;
            case 'store':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_detail&order_id='.$now_order['order_id'];
                break;
            case 'shop':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                break;
            case 'mall':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                break;
            case 'plat':
                $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                break;
        }
        if ($pay_info['error']==0) echo 'success';
        redirect($redirctUrl);
    }
    //支付宝支付同步回调
    public function alipay_return(){
        $order_id_arr = explode('_',$_GET['out_trade_no']);
        $order_type = $order_id_arr[0];
        $order_id = $order_id_arr[1];
        $total_fee = $order_id_arr[2];
        if(!empty($_SESSION['alipay_order'])){
            $order_id = $_SESSION['alipay_order']['orderid'];
            $order_type = $_SESSION['alipay_order']['order_type'];
            unset($_SESSION['alipay_order']);
        }
        switch($order_type){
            case 'group':
                $now_order = D('Group_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'meal':
                $now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'recharge':
                $now_order = D('User_recharge_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'appoint':
                $now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'balance-appoint':
                $now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'waimai':
                $now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'store':
                $now_order = D('Store_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'shop':
            case 'mall':
                $now_order = D('Shop_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'plat':
                $now_order = D('Plat_order')->where(array('orderid'=>$order_id))->find();
                break;
            default:
                $this->error_tips('非法的订单');
        }
        if($now_order['paid'] && $order_type!='balance-appoint'){
            switch($order_type){
                case 'group':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
                    break;
                case 'meal':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'takeout':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'food':
                case 'foodPad':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'weidian':
                    $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                    break;
                case 'appoint':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
                case 'waimai':
                    $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                    break;
                case 'recharge':
                    $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                    break;
                case 'wxapp':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                    break;
                case 'store':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_detail&order_id='.$now_order['order_id'];
                    break;
                case 'shop':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'mall':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'plat':
                    $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                    break;
            }
            redirect($redirctUrl);exit;
        }

        if(in_array($_GET['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $now_order['mer_id']){
            $mer_id = $now_order['mer_id'];
        }else if($_GET['order_type'] == 'plat'){
            $get_order_info = D('Plat_order')->get_pay_order(0,$now_order['order_id']);
            if($get_order_info['order_info']['pay_merchant_ownpay'] && $get_order_info['order_info']['mer_id']){
                $mer_id = $get_order_info['order_info']['mer_id'];
            }else{
                $this->config['merchant_ownpay'] = 0;
            }
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method(0, 0, true);
                }
                break;
        }
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }

        $pay_type = $_GET['pay_type'];
        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        $pay_class = new $pay_class_name('', '', $pay_type, $pay_method[$pay_type]['config'],$this->user_session,1);
        $go_query_param = $pay_class->query_order();

        $offline = false;
        if($go_query_param['error'] === 0){

            //判断回调金额
            if($payment_money_order = M('Payment_money_order')->where(array('order_id'=>$now_order['order_id'],'order_type'=>$order_type))->find()){
                M('Payment_money_order')->where(array('order_id'=>$now_order['order_id'],'order_type'=>$order_type))->setField('paid_money',$go_query_param['order_param']['pay_money']);
                if($payment_money_order['payment_money'] > $go_query_param['order_param']['pay_money']){
                    $this->error_tips('支付异常');
                }
            }else{
                $this->error_tips('支付异常');
            }

            $go_query_param['order_param']['pay_money'] = isset($go_query_param['order_param']['pay_money']) && $go_query_param['order_param']['pay_money'] ? $go_query_param['order_param']['pay_money'] : $total_fee;
            if($order_type == 'group'){
                $now_order = $this->get_orderid('Group_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Group_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'meal' || $order_type == 'takeout' || $order_type == 'food' || $order_type == 'foodPad'){
                $now_order = $this->get_orderid('Meal_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Meal_order')->after_pay($go_query_param['order_param'], $order_type);
            }else if($order_type == 'weidian'){
                $now_order = $this->get_orderid('Weidian_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                if(($pay_info['error']==0) && isset($pay_info['url']) && ($go_query_param['order_param']['pay_type'] == 'weixin')){  /***异步通知***/
                    $this->NoticeWDAsyn($go_query_param['order_param']['order_id']);
                }
            }else if($order_type == 'recharge'){
                $now_order = $this->get_orderid('User_recharge_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('User_recharge_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'appoint'){
                $now_order = $this->get_orderid('Appoint_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];


                D('Appoint_order')->after_pay($go_query_param['order_param']);

                // $pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'balance-appoint'){
                $now_order = $this->get_orderid('Appoint_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                D('Appoint_order')->balance_after_pay($go_query_param['order_param']);
            }else if($order_type == 'waimai'){
                $now_order = $this->get_orderid('Waimai_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Waimai_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'wxapp'){
                $now_order = $this->get_orderid('Wxapp_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Wxapp_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'store'){
                $now_order = $this->get_orderid('Store_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Store_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'shop' || $order_type == 'mall'){
                $now_order = $this->get_orderid('Shop_order', $go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Shop_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'plat'){
                $now_order = $this->get_orderid('Plat_order', $go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Plat_order')->after_pay($go_query_param['order_param']);
            }else{
                $this->error_tips('订单类型非法！请重新下单。');
            }
        }
        switch($order_type){
            case 'group':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
                break;
            case 'meal':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'takeout':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'food':
            case 'foodPad':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'weidian':
                $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                break;
            case 'appoint':
            case 'balance-appoint':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
                break;
            case 'waimai':
                $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                break;
            case 'recharge':
                $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                break;
            case 'wxapp':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                break;
            case 'store':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_detail&order_id='.$now_order['order_id'];
                break;
            case 'shop':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                break;
            case 'mall':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                break;
            case 'plat':
                $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                break;
        }
        if ($pay_info['error']==0) echo 'success';
        redirect($redirctUrl);
    }
    //支付宝异步通知
    public function alipay_notice()
    {
        fdump_sql($_POST,'wap_alipay_notice');
        $pay_method = D('Config')->get_pay_method(0, 0, true);
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }

        $pay_type = $_GET['pay_type'];
        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.' . $pay_class_name);
        $pay_class = new $pay_class_name('', '', $pay_type, $pay_method[$pay_type]['config'], $this->user_session, 1);
        $go_query_param = $pay_class->notice_url();



        $offline = false;
        if($go_query_param['error'] == 0){
            //判断回调金额
		
            if($payment_money_order = M('Payment_money_order')->where(array('orderid'=>$go_query_param['order_param']['order_id'],'order_type'=>$go_query_param['order_param']['order_type']))->find()){
                M('Payment_money_order')->where(array('orderid'=>$go_query_param['order_param']['order_id'],'order_type'=>$go_query_param['order_param']['order_type']))->setField('paid_money',$go_query_param['order_param']['pay_money']);
                if($payment_money_order['payment_money'] > $go_query_param['order_param']['pay_money']){
                    $this->error_tips('支付异常');
                }
            }else{
                $this->error_tips('支付异常');
            }
            if($go_query_param['order_param']['trade_status'] == 'TRADE_FINISHED') {
                $this->error_tips('订单已完成');
            }else if ($go_query_param['order_param']['trade_status'] == 'TRADE_SUCCESS') {
                if ($go_query_param['order_param']['order_type'] == 'group') {
                    $now_order = $this->get_orderid('Group_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Group_order')->after_pay($go_query_param['order_param']);
                } else if ($go_query_param['order_param']['order_type'] == 'meal' || $go_query_param['order_param']['order_type'] == 'takeout' || $go_query_param['order_param']['order_type'] == 'food' || $go_query_param['order_param']['order_type'] == 'foodPad') {
                    $now_order = $this->get_orderid('Meal_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Meal_order')->after_pay($go_query_param['order_param'], $go_query_param['order_param']['order_type']);
                } else if ($go_query_param['order_param']['order_type'] == 'weidian') {
                    $now_order = $this->get_orderid('Weidian_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                    if (($pay_info['error'] == 0) && isset($pay_info['url']) && ($go_query_param['order_param']['pay_type'] == 'weixin')) {
                        /***异步通知***/
                        $this->NoticeWDAsyn($go_query_param['order_param']['order_id']);
                    }
                } else if ($go_query_param['order_param']['order_type'] == 'recharge') {
                    $now_order = $this->get_orderid('User_recharge_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('User_recharge_order')->after_pay($go_query_param['order_param']);
                } else if ($go_query_param['order_param']['order_type'] == 'appoint') {
                    $now_order = $this->get_orderid('Appoint_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);

                    //$pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);
                } else if ($go_query_param['order_param']['order_type'] == 'balance-appoint') {
                    $now_order = $this->get_orderid('Appoint_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Appoint_order')->balance_after_pay($go_query_param['order_param']);
                    //$pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);
                } else if ($go_query_param['order_param']['order_type'] == 'waimai') {
                    $now_order = $this->get_orderid('Waimai_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Waimai_order')->after_pay($go_query_param['order_param']);
                } else if ($go_query_param['order_param']['order_type'] == 'wxapp') {
                    $now_order = $this->get_orderid('Wxapp_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Wxapp_order')->after_pay($go_query_param['order_param']);
                } else if ($go_query_param['order_param']['order_type'] == 'store') {
                    $now_order = $this->get_orderid('Store_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Store_order')->after_pay($go_query_param['order_param']);
                } else if ($go_query_param['order_param']['order_type'] == 'shop' || $go_query_param['order_param']['order_type'] == 'mall') {
                    $now_order = $this->get_orderid('Shop_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Shop_order')->after_pay($go_query_param['order_param']);
                } else if ($go_query_param['order_param']['order_type'] == 'plat') {
                    $now_order = $this->get_orderid('Plat_order', $go_query_param['order_param']['order_id'], $offline);
                    $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                    $pay_info = D('Plat_order')->after_pay($go_query_param['order_param']);
                } else {
                    $this->error_tips('订单类型非法！请重新下单。');
                }
            }

            $order_id = $go_query_param['order_param']['order_id'];
            switch($go_query_param['order_param']['order_type']){
                case 'group':
                    $now_order = D('Group_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'meal':
                case 'takeout':
                case 'food':
                case 'foodPad':
                    $now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'weidian':
                    $now_order = D('Weidian_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'recharge':
                    $now_order = D('User_recharge_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'appoint':
                    $now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'waimai':
                    $now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'wxapp':
                    $now_order = D('Wxapp_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'store':
                    $now_order = D('Store_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'shop':
                case 'mall':
                    $now_order = D('Shop_order')->where(array('orderid' => $order_id))->find();
                    break;
                case 'plat':
                    $now_order = D('Plat_order')->where(array('orderid' => $order_id))->find();
                    break;
                default:
                    echo "fail";
                    exit;
            }
            if ($now_order['paid']) {
                echo "success";
                exit;
            }
        }
        echo "fail";
        exit;
    }

    //对接支付跳转
    public function butt_pay(){
        if(!empty($_GET['order_id']) && !empty($_GET['order_type'])){
            if(empty($_GET['is_paid'])){
                switch($_GET['order_type']){
                    case 'appoint':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=appoint_order&order_id='.$_GET['order_id'];
                        break;
                }
                if(empty($redirctUrl)){
                    $this->error('该类订单不允许访问');
                }else{
                    redirect($redirctUrl);
                }
            }else{
                $butt_array = array(
                    'order_id' => $_GET['order_id'],
                    'order_type' => $_GET['order_type'],
                    'pay_type' => $_GET['pay_type'],
                    'pay_money' => $_GET['pay_money'],
                    'pay_third_id' => $_GET['pay_third_id'],
                    'encrypt_time' => $_GET['encrypt_time'],
                );
                $key = get_butt_encrypt_key($butt_array,C('butt_key'),true);
                if($key == $_GET['encrypt_key']){
                    $_GET['pay_money'] = $_GET['pay_money']/100;
                    switch($_GET['order_type']){
                        case 'appoint':
                            $order_param = array(
                                'pay_type' => 'weixin',
                                'is_mobile' => '1',
                                'order_type' => $_GET['order_type'],
                                'order_id' => $_GET['order_id'],
                                'is_own' => '0',
                                'third_id' => $_GET['pay_third_id'],
                                'pay_money' => $_GET['pay_money'],
                            );
                            $pay_info = D('Appoint_order')->after_pay($order_param);
                            break;
                    }
                    if(!empty($pay_info)){
                        if(empty($pay_info['url'])){
                            $this->error_tips($pay_info['msg']);
                        }else{
                            if(cookie('is_house')){
                                $pay_info['url'] = str_replace('wap.php',C('INDEP_HOUSE_URL'),$pay_info['url']);
                            }else{
                                $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                            }

                            redirect($pay_info['url']);
                        }
                    }else{
                        $this->error('该类订单不允许访问');
                    }
                }else{
                    $this->error('订单校验失败，请重试');
                }
            }
        }else{
            $this->error('访问出错，请重试');
        }
    }

    //百度的同步通知
    public function baidu_back()
    {
        $pay_type = 'baidu';
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method(0, 0, true);
                }
                break;
        }
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$pay_type])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }
        $pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,1);
        $get_pay_param = $pay_class->return_url();
        if($pay_type!='offline'){
            $get_pay_param['order_param']['return']=1;
        }
        if(empty($get_pay_param['error'])){

            switch($get_pay_param['order_param']['order_type']){
                case 'group':
                    $now_order = D('Group_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'meal':
                    $now_order = D('Meal_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'takeout':
                case 'food':
                case 'foodPad':
                    $now_order = D('Meal_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'weidian':
                    $now_order = D('Weidian_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'recharge':
                    $now_order = D('User_recharge_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'appoint':
                    $now_order = D('Appoint_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'waimai':
                    $now_order = D('Waimai_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'wxapp':
                    $now_order = D('Wxapp_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'store':
                    $now_order = D('Store_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'shop':
                case 'mall':
                    $now_order = D('Shop_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'plat':
                    $now_order = D('Plat_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                default:
                    $this->error_tips('非法的订单');
            }


            if(empty($now_order)){
                $this->error_tips('该订单不存在');
            }
            $now_order['order_type'] = $get_pay_param['order_param']['order_type'];
            if($now_order['paid']&&$now_order['order_type']!='balance-appoint'){
                switch($get_pay_param['order_param']['order_type']){
                    case 'group':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=group_order&order_id='.$now_order['order_id'];
                        break;
                    case 'meal':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'takeout':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'food':
                    case 'foodPad':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'weidian':
                        $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']));
                        $this->NoticeWDAsyn($now_order['orderid']);
                        break;
                    case 'appoint':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=appoint_order&order_id='.$now_order['order_id'];
                        break;
                    case 'waimai':
                        $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                        break;
                    case 'recharge':
                        $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                        // $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=index';
                        break;
                    case 'wxapp':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                        break;
                    case 'store':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_detail&order_id='.$_GET['order_id'];
                        break;
                    case 'shop':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                        break;
                    case 'mall':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                        break;
                    case 'plat':
                        $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                        break;
                }
                redirect($redirctUrl);exit;
            }

            if($get_pay_param['order_param']['order_type'] == 'group'){
                $pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food' || $get_pay_param['order_param']['order_type'] == 'foodPad'){
//                 $get_pay_param['order_param']['orderid'] = $get_pay_param['order_param']['order_id'];
//                 unset($get_pay_param['order_param']['order_id']);
                $pay_info = D('Meal_order')->after_pay($get_pay_param['order_param'], $get_pay_param['order_param']['order_type']);
            }else if($get_pay_param['order_param']['order_type'] == 'weidian'){
                $pay_info = D('Weidian_order')->after_pay($get_pay_param['order_param']);
                if(($pay_info['error']==0) && isset($pay_info['url']) && ($get_pay_param['order_param']['pay_type'] == 'weixin')){  /***异步通知***/
                    $this->NoticeWDAsyn($get_pay_param['order_param']['order_id']);
                }
            }else if($get_pay_param['order_param']['order_type'] == 'recharge'){
                $pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'appoint'){

                $pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);

                //$pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'balance-appoint'){

                $pay_info =D('Appoint_order')->balance_after_pay($get_pay_param['order_param']);

                //$pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'waimai'){
                $pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'wxapp'){
                $pay_info = D('Wxapp_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'store'){
                $pay_info = D('Store_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'shop' || $get_pay_param['order_param']['order_type'] == 'mall'){
                $pay_info = D('Shop_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'plat'){
                $pay_info = D('Plat_order')->after_pay($get_pay_param['order_param']);
            }else{
                $this->error_tips('订单类型非法！请重新下单。');
            }
            if(empty($pay_info['error'])){
                $pay_info['msg'] = '订单付款成功！现在跳转.';
            }
            if(empty($pay_info['url'])){
                $this->error_tips($pay_info['msg']);
            }else{
                if(cookie('is_house')){
                    $pay_info['url'] = str_replace('wap.php',C('INDEP_HOUSE_URL'),$pay_info['url']);
                }else{
                    $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                }
                $this->assign('pay_info',$pay_info);
                $this->display('after_pay');
            }
        }else{
            $this->error_tips($get_pay_param['msg']);
        }
    }
}
?>
