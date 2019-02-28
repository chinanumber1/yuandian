<?php
class Service_orderModel extends Model{
    public function get_order($order_id){
        return M('Service_user_publish')->where(array('publish_id'=>$order_id))->find();
    }
    public function get_pay_order($order_id){
        $now_order = $this->get_order($order_id);
        if(empty($now_order)){
            return array('error'=>1,'msg'=>'当前订单已失效或不存在！');
        }
        if($now_order['status'] == 2){
            return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>'/wap.php?c=Service&a=need_list');
        }

        if($now_order['catgory_type'] == 2){
            $now_order['order_name'] = '帮我买';
            $info = M('Service_user_publish_buy')->where(array('publish_id'=>$now_order['publish_id']))->find();
        }elseif ($now_order['catgory_type'] == 3) {
            $now_order['order_name'] = '帮我送';
            $info = M('Service_user_publish_give')->where(array('publish_id'=>$now_order['publish_id']))->find();
        }
        $now_order['order_num'] = 1;

        $order_info = array(
            'pay_offline' 			=> false,		  //线下支付
            'pay_merchant_balance' 	=> false,		  //商家余额
            'pay_merchant_coupon' 	=> false,		  //商家优惠券
            'pay_merchant_ownpay' 	=> false,		  //商家自有支付
            'pay_system_balance' 	=> true,		  //平台余额
            'pay_system_coupon' 	=> false,		  //平台优惠券
            'pay_system_score' 		=> false,		  //平台积分抵现
            'order_info' 			=> $now_order,	  //平台积分抵现
        );
        return $order_info;
    }

    public function after_pay($order_id,$plat_order_info){
        $data_order['status'] = 2;
        $where['publish_id'] = $order_id;
        M('Service_user_publish')->where($where)->data($data_order)->save();
        $this->template_message($order_id);

    }
    public function get_order_url($order_id){
        $now_order = $this->get_order($order_id);
        return C('config.site_url').'/wap.php?c=Service&a=price_list&publish_id='.$order_id;
    }



    public function template_message($publish_id){
        $info = $this->get_order($publish_id);
        if($info['deliver_type'] == 1){
            //推送配送订单，
            D('Deliver_supply')->saveOrder($publish_id, null,3,'service_user_publish');
        }else{
            //模板消息
            $where = "`cid`={$info['cid']} AND `area_id`={$info['address_area_id']} AND (ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$info['address_lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$info['address_lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$info['address_lng']}*PI()/180-`lng`*PI()/180)/2),2)))*1000) <= `radius`*1000 OR `radius` = 0) AND `uid` != {$this->user_session['uid']} ";
            $plist = D("Service_provider_category")->where($where)->field('uid,cat_name')->select();
            
            // 多维数组去重复
            foreach ($plist as $v){
                $temp[] = serialize($v);
            }
            $temp=array_unique($temp); //去掉重复的字符串,也就是重复的数组
            foreach ($temp as $k => $v){
                $temp[$k]=unserialize($v); //再将拆开的数组重新组装
            }
            $plist = $temp;
        
            if($plist){
                foreach ($plist as $key => $value) {
                    $user_info = D("User")->where(array('uid'=>$value['uid']))->field('uid,openid,nickname')->find();
                    if ($user_info['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=detail_special&publish_id='.$res;
                        $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '用户发布了一个符合您的“'.$value['cat_name'].'”需求，配送费为'.$buyData['total_price'].'，请及时接单', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                    }
                }
            }
        }

        // D('User')->where(array('uid'=>$this->user_session['uid']))->setDec('now_money',$_POST['total_price']);
        // D('User_money_list')->add_row($this->user_session['uid'],2,$_POST['total_price'],"支付帮我买服务费 ".$_POST['total_price']." 元",true,4,$res);

        D('Service_offer_record')->data(array('offer_id'=>0,'publish_id'=>$publish_id,'add_time'=>time(),'remarks'=>'订单支付成功'))->add();

        $now_user = D('User')->get_user($info['uid']);
        if ($now_user['openid']) {
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $publish_id;
//            $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'keyword1' => '您发布的需求已经完成支付，请等待接单。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时查看！'));
            // 帮买帮送修改  待办事项通知 模板 为 订单状态更新 模板
            $model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'OrderSn' => $info['order_sn'], 'OrderStatus' =>'您发布的需求已经完成支付，请查看。', 'remark' => date('Y.m.d H:i:s')));
        }
        // 进行极光App推送
        if ($now_user) {
            if (!$info['order_sn']) $info['order_sn'] = $publish_id;
            import('@.ORG.Apppush');
            $order['status'] = 2;
            $order['order_id'] = $publish_id;
            $order['order_sn'] = $info['order_sn'];
            $order['uid'] = $info['uid'];
            $order['catgory_type'] = $info['catgory_type'];
            $apppush = new Apppush();
            $apppush->send($order, 'publish');
        }

    }
}

?>