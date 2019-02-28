<?php
class PayAction extends BaseAction{
    public function check(){
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！',U('Login/index'));
        }
        if(empty($this->user_session['phone'])){
            $this->error_tips('尊敬的用户，购买之前请您先绑定手机号码！',U('My/bind_user',array('referer'=>urlencode(U('Pay/check',$_GET)))));
        }
        if(!in_array($_GET['type'],array('group','meal','weidian','takeout', 'food', 'foodPad','recharge','appoint','wxapp', 'store'))){
            $this->error_tips('订单来源无法识别，请重试。');
        }

        if($_GET['type'] == 'group'){
            $now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
        }else if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad'){
            $now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']), false, $_GET['type']);
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
            $this->assign('notCard',true);
        }else{
            $this->error_tips('非法的订单');
        }


        if($now_order['error'] == 1){
            if($now_order['url']){
                $this->error_tips($now_order['msg'],$now_order['url']);
            }else{
                $this->error_tips($now_order['msg']);
            }
        }
        $order_info = $now_order['order_info'];
        $this->assign('order_info',$order_info);


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
                'redirct_url' => $this->config['site_url'].'/wap.php?c=Pay&a=butt_pay',
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
        if($_GET['type'] == 'group'){
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
            }
        }
        $cheap_info['wx_cheap']=sprintf("%.2f",substr(sprintf("%.3f", (float)$cheap_info['wx_cheap']), 0, -2));
        $this->assign('cheap_info',$cheap_info);
        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);
        if(empty($now_user)){
            $this->error_tips('未获取到您的帐号信息，请重试！');
        }
        $now_user['now_money'] = floatval($now_user['now_money']);
        $this->assign('now_user',$now_user);
        if($_GET['type'] != 'recharge' && $_GET['type'] != 'weidian'){
            //优惠券
            if(!empty($_GET['card_id'])){
                $now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($_GET['card_id'],$this->user_session['uid']);
                $now_coupon['type']='mer';
                $this->assign('now_coupon',$now_coupon);
            }
            //平台优惠券
            if(!empty($_GET['coupon_id'])){
                $now_coupon = D('System_coupon')->get_coupon_by_id($_GET['coupon_id']);
                $now_coupon['type']='system';
                $this->assign('now_coupon',$now_coupon);

            }
            //商家会员卡余额
            $merchant_balance = D('Member_card')->get_balance($this->user_session['uid'],$order_info['mer_id']);
            $this->assign('merchant_balance',$merchant_balance);

            $pay_money = (round($order_info['order_total_money'] * 100) - round($now_coupon['price'] * 100) - round($merchant_balance * 100) - round($now_user['now_money'] * 100))/100;
        }else{
            $pay_money = $order_info['order_total_money'];
        }

        //使用积分
        $score_can_use_count=0;
        $score_deducte=0;
        if ($_GET['type'] == 'group'||$_GET['type'] == 'meal'||$_GET['type'] == 'takeout'||$_GET['type'] == 'food'||$_GET['type'] == 'foodPad') {
            $score_config = D('Config')->field('name,value')->where('tab_id="user_score"')->getField('name,value');
            $user_score_use_condition=$score_config['user_score_use_condition'];
            $user_score_max_use=$score_config['user_score_max_use'];
            if($_GET['type']=='group'){
                $group_info = D('Group')->where(array('group_id'=>$order_info['group_id']))->find();
                if($group_info['score_use']){
                    if($group_info['group_max_score_use']!=0){
                        $user_score_max_use = $group_info['group_max_score_use'];
                    }
                }else{
                    $user_score_max_use = 0;
                }
            }
            $user_score_use_percent=(float)$score_config['user_score_use_percent'];
            $score_max_deducte=round((float)$user_score_max_use/$user_score_use_percent,1);
            if($user_score_use_percent>0&&$score_max_deducte>0&&$user_score_use_condition>0&&$now_user['score_count']>0){   //如果设置没有错误
                $total_ = isset($now_coupon['price'])?$order_info['order_total_money']-$now_coupon['price']:$order_info['order_total_money'];
                if ($cheap_info['can_cheap']) {
                    $total_-=$cheap_info['wx_cheap'];
                }
                if ($total_>=$user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
                    if($total_>$score_max_deducte){                    //判断积分最大抵扣金额是否比这个订单的总额大
                        $score_can_use_count = (int)($now_user['score_count']>$user_score_max_use?$user_score_max_use:$now_user['score_count']);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
                        $score_deducte = sprintf("%.2f",substr(sprintf("%.3f", (float)$score_can_use_count/$user_score_use_percent), 0, -2));
                        $score_deducte = $score_deducte>$total_?$total_:$score_deducte;
                    }else{
                        $score_can_use_count = ceil($total_*$user_score_use_percent)>(int)$now_user['score_count']?(int)$now_user['score_count']:ceil($total_*$user_score_use_percent);
                        $score_deducte = sprintf("%.2f",substr(sprintf("%.3f", (float)$score_can_use_count*$user_score_use_percent), 0, -2));
                        $score_deducte = $score_deducte>$total_?$total_:$score_deducte;
                    }
                }
            }
            $this->assign('score_can_use_count',$score_can_use_count);
            $this->assign('score_deducte',$score_deducte);
            $this->assign('score_count',$now_user['score_count']);
        }

        //需要支付的钱
        $this->assign('pay_money',number_format($pay_money,2));

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

        $now_merchant = D('Merchant')->get_info($order_info['mer_id']);
        if(empty($notOffline)){
            if ($now_merchant) {
                $notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
            }
        }

        if (isset($_GET['online']) && $_GET['type'] == 'foodPad') {
            $online = isset($_GET['online']) ? intval($_GET['online']) : 1;
            $notOnline = $online ? 0 : 1;
            $notOffline = $online ? 1 : $notOffline;
        }

        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        if(in_array($_GET['type'],array('group','meal','takeout','food','appoint','waimai','weidian')) && $order_info['mer_id']){

        }else{
            $this->config['merchant_ownpay'] = 0;
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
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        
        if(empty($_SESSION['openid']) || $_GET['type'] == 'foodPad'){
            unset($pay_method['weixin']);
        }
        if($pay_method['weixin']['config']['is_own']){
            $merchant_bind = D('Weixin_bind')->field('authorizer_appid')->where(array('mer_id' => $now_merchant['mer_id']))->find();
            if(empty($merchant_bind)){
                unset($pay_method['weixin']);
            }else{
                if(empty($_SESSION['open_authorize_openid'])){
                    $this->open_authorize_openid(array('appid'=>$merchant_bind['authorizer_appid']));
                }
                if($_SESSION['open_authorize_openid'] == 'error'){
                    unset($pay_method['weixin']);
                }
            }
        }

        if ($_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad') {
            $order_table = 'Meal_order';
        }else if($_GET['type']=='recharge'){
            $order_table = 'User_recharge_order';
        }else{
            $order_table = ucfirst($_GET['type']).'_order';
        }

        $nowtime = date("ymdHis");
        $orderid = $nowtime.rand(10,99).sprintf("%08d",$order_info['order_id']);
        $save_pay_id = D($order_table)->where(array('order_id'=>$order_info['order_id']))->setField('orderid',$orderid);
        if(!$save_pay_id){
            $this->error_tips('更新失败，请联系管理员');
        }else{
            $order_info['order_id']=$orderid;
        }

        if(!empty($pay_method['weixin']['config'])){
            $this->assign('orderid_info',$order_info);
        }

        if(empty($pay_method)&&$this->is_app_browser==false){
            $this->error_tips('暂时没有可使用的支付方式');
        }
        $this->assign('pay_method',$pay_method);

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
        );
        return $payName[$label];
    }
    public function go_pay(){
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！',U('Login/index'));
        }
        if(!in_array($_POST['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store'))){
            $this->error_tips('订单来源无法识别，请重试。');
        }
        if (strtolower($_POST['pay_type']) == 'alipay') {
            $url = U('Pay/alipay', $_POST);
            $this->assign('url', $url);
            $this->display('alipay_pay');
            die;
        }

        switch($_POST['order_type']){
            case 'group':
                $now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            case 'meal':
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order = D('Meal_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']), false,  $_POST['order_type']);
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            case 'recharge':
                $now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            case 'appoint':
                $now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            case 'waimai':
                $now_order = D('Waimai_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),false,$_POST['order_type']);
                if ($now_order['order_info']['pay_type'] !== $_POST['pay_type']) {
                    $this->error_tips('非法的订单');
                }
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->get_pay_order(0,intval($_POST['order_id']));
                break;
            case 'store':
                $now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            default:
                $this->error_tips('非法的订单1');
        }
        if($now_order['error'] == 1){
            $this->error_tips($now_order['msg'],$now_order['url']);
        }
        $order_info = $now_order['order_info'];

        if($_POST['order_type'] != 'recharge'  && $_POST['order_type'] != 'weidian'){
            //优惠券
            if(!empty($_POST['card_id'])){
                $now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($_POST['card_id'],$this->user_session['uid']);
                $now_coupon['type']='mer';
            }
            if(!empty($_POST['coupon_id'])){
                $now_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                $now_coupon['type']='system';
            }

            //商家会员卡余额
            $merchant_balance = D('Member_card')->get_balance($this->user_session['uid'],$order_info['mer_id']);
            $this->assign('merchant_balance',$merchant_balance);
        }

        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);
        if(empty($now_user)){
            $this->error_tips('未获取到您的帐号信息，请重试！');
        }
        //判断积分是否够用 防止支付同时积分被改动

        if ($_POST['use_score']) {
            if($now_user['score_count']<$_POST['score_used_count']){
                $this->error_tips('账户积分不够，请重试！');
            }
            //$order_info['order_total_money']-=$_POST['score_deducte'];
            $order_info['score_used_count']=$_POST['score_used_count'];
            $order_info['score_deducte']=$_POST['score_deducte'];
        }else{
            $order_info['score_used_count']=0;
            $order_info['score_deducte']=0;
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
        }

        if($save_result['error_code']){
            $this->error_tips($save_result['msg']);
        }else if($save_result['url']){
            $this->success_tips($save_result['msg'],$save_result['url']);
        }

        //需要支付的钱
        $pay_money = round($save_result['pay_money']*100)/100;
        if(in_array($order_info['order_type'],array('group','meal','takeout','food','appoint','waimai','store','weidian')) && $order_info['mer_id']){
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
        if(empty($pay_method[$_POST['pay_type']])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($_POST['pay_type']);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        $order_id = $order_info['order_id'];
        if ($_POST['order_type'] == 'takeout' || $_POST['order_type'] == 'food' || $_POST['order_type'] == 'foodPad') {
            $order_table = 'Meal_order';
        }else if($_POST['order_type']=='recharge'){
            $order_table = 'User_recharge_order';
        }else{
            $order_table = ucfirst($_POST['order_type']).'_order';
        }
        //更新长id
        //if($_POST['pay_type']!='offline'){
            $nowtime = date("ymdHis");
            $orderid = $nowtime.rand(10,99).sprintf("%08d",$order_id);
            $data_tmp['pay_type'] = $_POST['pay_type'];
            $data_tmp['order_type'] = $_POST['order_type'];
            $data_tmp['order_id'] = $order_id;
            $data_tmp['orderid'] = $orderid;
            $data_tmp['addtime'] = $nowtime;
            D('Tmp_orderid')->add($data_tmp);
            $save_pay_id = D($order_table)->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
            if(!$save_pay_id){
                $this->error_tips('更新失败，请联系管理员');
            }else{
                $order_info['order_id']=$orderid;
            }
        //}

        $pay_class = new $pay_class_name($order_info,$pay_money,$_POST['pay_type'],$pay_method[$_POST['pay_type']]['config'],$this->user_session,1);
        $go_pay_param = $pay_class->pay();//dump($go_pay_param);
        if(empty($go_pay_param['error'])){
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
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$order_info['order_type'].'&order_id='.$order_info['order_id'].'&own_mer_id='.$order_info['mer_id'];
                }else{
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$order_info['order_type'].'&order_id='.$order_info['order_id'];
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

    public function get_weixin_param(){
        if(IS_POST){
            $pay_money = $_POST['pay_money'];
            $order_info = $_POST['orderid_info'];
            $nowtime = date("ymdHis");
            $order_type = $order_info['order_type'];

            if ($order_type == 'takeout' || $order_type == 'food' || $order_type == 'foodPad') {
                $order_table = 'Meal_order';
            }else if($order_type=='recharge'){
                $order_table = 'User_recharge_order';
            }else{
                $order_table = ucfirst($order_type).'_order';
            }
            $orderid = $nowtime.rand(10,99).sprintf("%08d",$_POST['short_orderid']);
            $data_tmp['pay_type'] = 'weixin';
            $data_tmp['order_type'] = $order_type;
            $data_tmp['order_id'] = $_POST['short_orderid'];
            $data_tmp['orderid'] = $orderid;
            $data_tmp['addtime'] = $nowtime;
            D('Tmp_orderid')->add($data_tmp);
            $save_pay_id = D($order_table)->where(array('order_id'=>$_POST['short_orderid']))->setField('orderid',$orderid);
            if(!$save_pay_id){
                $this->error_tips('更新失败，请联系管理员');
            }else{
                $order_info['order_id']=$orderid;
            }
            $pay_method = $_POST['pay_method'];
            $mer_id = $order_info['mer_id'];
            $import_result = import('@.ORG.pay.Weixin');
            $pay_class = new Weixin($order_info,$pay_money,$_POST['pay_type'],$pay_method['weixin']['config'],$this->user_session,1);
            $go_pay_param = $pay_class->pay();
            if(empty($go_pay_param['error'])) {
                if (!empty($go_pay_param['weixin_param'])) {
                    if ($pay_method['weixin']['config']['is_own']) {
                        C('open_authorize_wxpay', true);
                        $share = new WechatShare($this->config, $_SESSION['openid']);
                        $this->hideScript = $share->gethideOptionMenu($mer_id);
                        $arr['hidScript'] = $this->hideScript;
                        $redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type=' . $order_info['order_type'] . '&order_id=' . $order_info['order_id'] . '&own_mer_id=' . $order_info['mer_id'];
                    } else {
                        $redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type=' . $order_info['order_type'] . '&order_id=' . $order_info['order_id'];
                    }
                    $arr['redirctUrl'] = $redirctUrl;
                    $arr['pay_money'] = $pay_money;
                    $arr['weixin_param'] = json_decode($go_pay_param['weixin_param']);
                    $arr['error'] = 0;
                    echo json_encode($arr);
                } else {
                    echo json_encode(array('error'=>1,'msg'=>$go_pay_param['msg']));
                }
            }
        }
    }

    public function alipay()
    {
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！',U('Login/index'));
        }
        if(!in_array($_GET['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store'))){
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
            default:
                $this->error_tips('非法的订单1');
        }
        if($now_order['error'] == 1){
            $this->error_tips($now_order['msg'],$now_order['url']);
        }
        $order_info = $now_order['order_info'];

        if($_GET['order_type'] != 'recharge'){
            //优惠券
            if(!empty($_GET['card_id'])){
                $now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($_GET['card_id'],$this->user_session['uid']);
            }

            //商家会员卡余额
            $merchant_balance = D('Member_card')->get_balance($this->user_session['uid'],$order_info['mer_id']);
            $this->assign('merchant_balance',$merchant_balance);
        }

        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);
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
        }

        if($save_result['error_code']){
            $this->error_tips($save_result['msg']);
        }else if($save_result['url']){
            $this->success_tips($save_result['msg'],$save_result['url']);
        }

        //需要支付的钱
        $pay_money = round($save_result['pay_money']*100)/100;

        if(in_array($order_info['order_type'],array('group','meal','takeout','food','appoint','waimai','store')) && $order_info['mer_id']){
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
            $order_table = 'User_recharge_order';
        }else{
            $order_table = ucfirst($_GET['order_type']).'_order';
        }
        if($_GET['pay_type']!='offline'){
            $nowtime = date("YmdHis");
            $orderid = $nowtime.rand(10,99).sprintf("%08d",$order_id);
            $data_tmp['pay_type'] = $_POST['pay_type'];
            $data_tmp['order_type'] = $_POST['order_type'];
            $data_tmp['order_id'] = $order_id;
            $data_tmp['orderid'] = $orderid;
            $data_tmp['addtime'] = $nowtime;
            D('Tmp_orderid')->add($data_tmp);
            $save_pay_id = D($order_table)->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
            if(!$save_pay_id){
                $this->error_tips('更新失败，请联系管理员');
            }else{
                $order_info['order_id']=$orderid;
            }
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
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$order_info['order_type'].'&order_id='.$order_info['order_id'].'&own_mer_id='.$order_info['mer_id'];
                }else{
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$order_info['order_type'].'&order_id='.$order_info['order_id'];
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
            }
        }
        if(empty($now_order)){
            $this->error_tips('该订单不存在');
        }else{
            $tmp_orderid->where(array('order_id'=>$now_order['order_id']))->delete();
        }

        return $now_order;
    }

    //微信同步回调页面
    public function weixin_back(){
        switch($_GET['order_type']){
            case 'group':
                //$now_order = D('Group_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Group_order',$_GET['order_id']);
                break;
            case 'meal':
                // $now_order = D('Meal_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Meal_order',$_GET['order_id']);
                break;
            case 'takeout':
            case 'food':
            case 'foodPad':
                // $now_order = D('Meal_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Meal_order',$_GET['order_id']);
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Weidian_order',$_GET['order_id']);
                break;
            case 'recharge':
                //$now_order = D('User_recharge_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('User_recharge_order',$_GET['order_id']);
                break;
            case 'appoint':
//                $now_order = D('Appoint_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Appoint_order',$_GET['order_id']);
                break;
            case 'waimai':
//                $now_order = D('Waimai_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Waimai_order',$_GET['order_id']);
                break;
            case 'wxapp':
//                $now_order = D('Wxapp_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Wxapp_order',$_GET['order_id']);
                break;
            case 'store':
//                $now_order = D('Store_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Store_order',$_GET['order_id']);
                break;
            default:
                $this->error_tips('非法的订单');
        }


        if(empty($now_order)){
            $this->error_tips('该订单不存在');
        }
        $now_order['order_type'] = $_GET['order_type'];
        if($now_order['paid']){
            switch($_GET['order_type']){
                case 'group':
                    $redirctUrl = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$now_order['order_id'];
                    break;
                case 'meal':
                    $redirctUrl = C('config.site_url').'/wap.php?c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'takeout':
                    $redirctUrl = C('config.site_url').'/wap.php?c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'food':
                case 'foodPad':
                    $redirctUrl = C('config.site_url').'/wap.php?c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'weidian':
                    $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']));
                    $this->NoticeWDAsyn($now_order['orderid']);
                    break;
                case 'appoint':
                    $redirctUrl = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
                case 'waimai':
                    $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                    break;
                case 'recharge':
                    $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                    // $redirctUrl = C('config.site_url').'/wap.php?c=My&a=index';
                    break;
                case 'wxapp':
                    $redirctUrl = C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                    break;
                case 'store':
                    $redirctUrl = C('config.site_url').'/wap.php?c=My&a=store_order_list';
                    break;
            }
            redirect($redirctUrl);exit;
        }

        if(in_array($_GET['order_type'],array('group','meal','takeout','food','appoint','waimai')) && $now_order['mer_id']){
            $mer_id = $now_order['mer_id'];
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
        $_GET['order_id'] = $now_order['order_id'];
        $now_order['order_id']  =   $now_order['orderid'];
        $import_result = import('@.ORG.pay.Weixin');
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        $pay_class = new Weixin($now_order,0,'weixin',$pay_method['weixin']['config'],$this->user_session,1);
        $go_query_param = $pay_class->query_order();
        if($go_query_param['error'] === 0){
            $go_query_param['order_param']['return']=1;
            switch($_GET['order_type']){
                case 'group':
                    D('Group_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'meal':
                case 'takeout':
                case 'food':
                case 'foodPad':
                    $go_query_param['order_param']['orderid'] = $go_query_param['order_param']['order_id'];
                    unset($go_query_param['order_param']['order_id']);
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
                    D('appoint_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'wxapp':
                    D('Wxapp_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'store':
                    D('Store_order')->after_pay($go_query_param['order_param']);
                    break;
            }
        }
        switch($_GET['order_type']){
            case 'group':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=My&a=group_order&order_id='.$_GET['order_id'];
                break;
            case 'meal':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Meal&a=detail&orderid='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'takeout':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Takeout&a=order_detail&order_id='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'food':
            case 'foodPad':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Food&a=order_detail&order_id='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'weidian':
                $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                $this->NoticeWDAsyn($now_order['orderid']);
                break;
            case 'appoint':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=My&a=appoint_order&order_id='.$_GET['order_id'];
                break;
            case 'waimai':
                $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$_GET['order_id'];
                break;
            case 'recharge':
                $redirctUrl = C('config.site_url').'/wap.php?c=My&a=index';
                break;
            case 'wxapp':
                $redirctUrl = C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$_GET['order_id'];
                break;
            case 'store':
                $redirctUrl = C('config.site_url').'/wap.php?c=My&a=store_order_list';
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
        if(!empty($now_order) && isset($now_order['pay_type']) && ($now_order['pay_type']=='weixin')){
            $wdAsynarr=array('order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>'weixin');
            $wdAsynarr['salt'] = 'pigcms';
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
        $get_pay_param['order_param']['return']=1;
        $offline = $pay_type!='offline'?false:true;

        if(empty($get_pay_param['error'])){
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
            }else{
                $this->error_tips('订单类型非法！请重新下单。');
            }
            if(empty($pay_info['error'])){
                if($get_pay_param['order_param']['pay_type'] == 'weixin'){
                    exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
                } elseif ($get_pay_param['order_param']['pay_type'] == 'baidu') {//百度的异步通知返回
                    exit("<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>");
                }
                $pay_info['msg'] = '订单付款成功！现在跳转.';
            }
            if(empty($pay_info['url'])){
                $this->error_tips($pay_info['msg']);
            }else{
                $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                $this->assign('pay_info',$pay_info);
                $this->display('after_pay');
            }
        }else{
            $this->error_tips($get_pay_param['msg']);
        }
    }

    //支付宝支付同步回调
    public function alipay_return(){
        $order_id_arr = explode('_',$_GET['out_trade_no']);
        $order_type = $order_id_arr[0];
        $order_id = $order_id_arr[1];
        $total_fee = $order_id_arr[2];
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
            case 'waimai':
                $now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'store':
                $now_order = D('Store_order')->where(array('orderid'=>$order_id))->find();
                break;
            default:
                $this->error_tips('非法的订单');
        }
        if($now_order['paid']){
            switch($order_type){
                case 'group':
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
                    break;
                case 'meal':
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'takeout':
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'food':
                case 'foodPad':
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'weidian':
                    $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                    break;
                case 'appoint':
                    $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
                case 'waimai':
                    $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                    break;
                case 'recharge':
                    $redirctUrl = C('config.site_url').'/wap.php?c=My&a=index';
                    break;
                case 'wxapp':
                    $redirctUrl = C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                    break;
                case 'store':
                    $redirctUrl = C('config.site_url').'/wap.php?c=My&a=store_order_list';
                    break;
            }
            redirect($redirctUrl);exit;
        }

        if(in_array($_GET['order_type'],array('group','meal','takeout','food','appoint','waimai')) && $now_order['mer_id']){
            $mer_id = $now_order['mer_id'];
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
        $import_result = import('@.ORG.pay.Alipay');
        $pay_class = new Alipay('','','alipay',$pay_method['alipay']['config'],$this->user_session,1);
        $go_query_param = $pay_class->query_order();
        if($go_query_param['error'] === 0){
            $go_query_param['order_param']['pay_money'] = $total_fee;
            switch($order_type){
                case 'group':
                    $pay_info = D('Group_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'meal':
                case 'takeout':
                case 'food':
                case 'foodPad':
                    $go_query_param['order_param']['orderid'] = $go_query_param['order_param']['order_id'];
                    unset($go_query_param['order_param']['order_id']);
                    $pay_info = D('Meal_order')->after_pay($go_query_param['order_param'], $order_type);
                    break;
                case 'weidian':
                    $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                    if(($pay_info['error']==0) && isset($pay_info['url'])){  /***异步通知***/
                        $this->NoticeWDAsyn($go_query_param['order_param']['order_id']);
                    }
                    break;
                case 'recharge':
                    $pay_info = D('User_recharge_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'waimai':
                    $pay_info = D('Waimai_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'appoint':
                    $pay_info = D('appoint_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'wxapp':
                    $pay_info = D('Wxapp_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'store':
                    $pay_info = D('Store_order')->after_pay($go_query_param['order_param']);
                    break;
            }
        }
        switch($order_type){
            case 'group':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
                break;
            case 'meal':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'takeout':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'food':
            case 'foodPad':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'weidian':
                $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                break;
            case 'appoint':
                $redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
                break;
            case 'waimai':
                $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                break;
            case 'recharge':
                $redirctUrl = C('config.site_url').'/wap.php?c=My&a=index';
                break;
            case 'wxapp':
                $redirctUrl = C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                break;
            case 'store':
                $redirctUrl = C('config.site_url').'/wap.php?c=My&a=store_order_list';
                break;
        }
        if ($pay_info['error']==0) echo 'success';
        redirect($redirctUrl);
    }
    //支付宝异步通知
    public function alipay_notice()
    {
        $pay_method = D('Config')->get_pay_method(0, 0, true);
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        $import_result = import('@.ORG.pay.Alipay');
        $pay_class = new Alipay('','','alipay',$pay_method['alipay']['config'],$this->user_session,1);
        $go_query_param = $pay_class->notice_url();
        if($go_query_param['error'] == 0){
            switch($go_query_param['order_param']['order_type']){
                case 'group':
                    D('Group_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'meal':
                case 'takeout':
                case 'food':
                case 'foodPad':
                    $go_query_param['order_param']['orderid'] = $go_query_param['order_param']['order_id'];
                    unset($go_query_param['order_param']['order_id']);
                    D('Meal_order')->after_pay($go_query_param['order_param'], $go_query_param['order_param']['order_type']);
                    break;
                case 'weidian':
                    $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                    if(($pay_info['error']==0) && isset($pay_info['url'])){  /***异步通知***/
                        $this->NoticeWDAsyn($go_query_param['order_param']['order_id']);
                    }
                    break;
                case 'recharge':
                    D('User_recharge_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'waimai':
                    D('Waimai_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'appoint':
                    D('appoint_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'wxapp':
                    D('Wxapp_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'store':
                    D('Store_order')->after_pay($go_query_param['order_param']);
                    break;
            }

            $order_id = $go_query_param['order_param']['order_id'];
            switch($go_query_param['order_param']['order_type']){
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
                case 'waimai':
                    $now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'wxapp':
                    $now_order = D('Wxapp_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'store':
                    $now_order = D('Store_order')->where(array('orderid'=>$order_id))->find();
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
                        $redirctUrl = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$_GET['order_id'];
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
                            $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
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
                default:
                    $this->error_tips('非法的订单');
            }


            if(empty($now_order)){
                $this->error_tips('该订单不存在');
            }
            $now_order['order_type'] = $get_pay_param['order_param']['order_type'];
            if($now_order['paid']){
                switch($get_pay_param['order_param']['order_type']){
                    case 'group':
                        $redirctUrl = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$now_order['order_id'];
                        break;
                    case 'meal':
                        $redirctUrl = C('config.site_url').'/wap.php?c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'takeout':
                        $redirctUrl = C('config.site_url').'/wap.php?c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'food':
                    case 'foodPad':
                        $redirctUrl = C('config.site_url').'/wap.php?c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'weidian':
                        $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']));
                        $this->NoticeWDAsyn($now_order['orderid']);
                        break;
                    case 'appoint':
                        $redirctUrl = C('config.site_url').'/wap.php?c=My&a=appoint_order&order_id='.$now_order['order_id'];
                        break;
                    case 'waimai':
                        $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                        break;
                    case 'recharge':
                        $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                        // $redirctUrl = C('config.site_url').'/wap.php?c=My&a=index';
                        break;
                    case 'wxapp':
                        $redirctUrl = C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                        break;
                    case 'store':
                        $redirctUrl = C('config.site_url').'/wap.php?c=My&a=store_order_list';
                        break;
                }
                redirect($redirctUrl);exit;
            }

            if($get_pay_param['order_param']['order_type'] == 'group'){
                $pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food' || $get_pay_param['order_param']['order_type'] == 'foodPad'){
                $get_pay_param['order_param']['orderid'] = $get_pay_param['order_param']['order_id'];
                unset($get_pay_param['order_param']['order_id']);
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
            }else if($get_pay_param['order_param']['order_type'] == 'waimai'){
                $pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'wxapp'){
                $pay_info = D('Wxapp_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'store'){
                $pay_info = D('Store_order')->after_pay($get_pay_param['order_param']);
            }else{
                $this->error_tips('订单类型非法！请重新下单。');
            }
            if(empty($pay_info['error'])){
                $pay_info['msg'] = '订单付款成功！现在跳转.';
            }
            if(empty($pay_info['url'])){
                $this->error_tips($pay_info['msg']);
            }else{
                $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                $this->assign('pay_info',$pay_info);
                $this->display('after_pay');
            }
        }else{
            $this->error_tips($get_pay_param['msg']);
        }
    }
}
?>
