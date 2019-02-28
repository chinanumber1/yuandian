<?php
    class Sub_card_orderModel extends Model{
        public function get_pay_order($order_id){
            $now_order = $this->get_order_by_id($order_id);
            if(empty($now_order)){
                return array('error'=>1,'msg'=>'当前订单不存在！');
            }


            $order_info = array(
                'order_id'			=>	$now_order['order_id'],
                'order_type'		=> 'sub_card',
                'money'	=>	floatval($now_order['money']),
                'order_name'		=>	$now_order['order_name'],
                'order_num'			=>	1,
                'num'   		=> 1,
                'price' 		=> floatval($now_order['money']),
                'money' 	=> floatval($now_order['money']),
                'order_total_money' 	=> floatval($now_order['money']),
                'pay_merchant_balance' 	=>false ,		//商家余额
                'pay_merchant_coupon' 	=> false,		//商家优惠券
                'pay_merchant_ownpay' 	=> false,		//商家自有支付
                'pay_system_balance' 	=> true,		//平台余额
                'pay_system_coupon' 	=> false,		//平台优惠券
                'pay_system_score' 		=> false,		//平台积分抵现

            );

            return array('error'=>0,'order_info'=>$order_info);
        }
        public function get_order_by_id($order_id){
            $where['order_id'] = $order_id;
            return $this->field(true)->where($where)->find();
        }

        public function after_pay($order_id,$plat_order_info){
            $where['order_id'] = $order_id;
            $now_order = $this->field(true)->where($where)->find();

            if(empty($now_order)){
                return array('error'=>1,'msg'=>'当前订单不存在');
            }else if($now_order['paid'] == 1){
                return array('error'=>1,'msg'=>'该订单已付款！','url'=>$this->get_order_url($now_order['order_id'],$now_order['is_mobile_pay']));
            }else{
                //得到当前社区信息，不将session作为调用值，因为可能会失效或错误。
                $now_user = D('User')->get_user($now_order['uid']);

                if(empty($now_user)){
                    return array('error'=>1,'msg'=>'没有查找用户，请联系管理员！');
                }

                $data_sub_card_order = array();
                $data_sub_card_order['pay_time'] = $_SERVER['REQUEST_TIME'];
                $data_sub_card_order['last_time'] = $_SERVER['REQUEST_TIME'];
                $data_sub_card_order['pay_type'] = $plat_order_info['pay_type'];
                $data_sub_card_order['third_id'] = $plat_order_info['third_id'];
                $data_sub_card_order['paid'] = 1;
                $data['status'] = 1;

                if($this->where($where)->save($data_sub_card_order)){
                    $now_order['store_id']  = explode(',',$now_order['store_id']);
                    //消费码
                    foreach ($now_order['store_id'] as $v) {
                        $tmp['fid'] = $now_order['order_id'] ;
                        $tmp['uid'] = $now_order['uid'] ;
                        $tmp['add_time'] = $_SERVER['REQUEST_TIME'] ;
                        $tmp['status'] = 0 ;
                        $tmp['store_id'] = $v ;
                        $now_store = M('Merchant_store')->where(array('store_id'=>$v))->find();
                        $tmp['mer_id'] = $now_store['mer_id'] ;
                        //减库存
                        M('Sub_card_mer_apply')->where(array('store_id'=>$v,'sub_card_id'=>$now_order['sub_card_id']))->setDec('sku',1);
                        //增加销量
                        M('Sub_card_mer_apply')->where(array('store_id'=>$v,'sub_card_id'=>$now_order['sub_card_id']))->setInc('sale_count',1);
                        $tmp['sub_card_id'] = $now_order['sub_card_id'] ;
                        $pass = array(
                            date('s',$_SERVER['REQUEST_TIME']),
                            date('m',$_SERVER['REQUEST_TIME']),
                            mt_rand(10,99),
                            date('d',$_SERVER['REQUEST_TIME']),
                            date('i',$_SERVER['REQUEST_TIME']),
                            mt_rand(10,99),
                            date('H',$_SERVER['REQUEST_TIME']),
                        );
                        shuffle($pass);
                        $tmp['pass'] = implode('',$pass) ;
                        $date_pass_array[] = $tmp;
                    }
                    M('Sub_card_user_pass')->addAll($date_pass_array);

                    //三级分佣
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $spread_total_money = $balance_pay + $payment_money;
                    if(!empty($now_user['openid'])&&C('config.open_user_spread') && (C('config.spread_money_limit')==0 || C('config.spread_money_limit')<=$spread_total_money)){
                        //上级分享佣金
                        $balance_pay  = $plat_order_info['system_balance'];
                        $payment_money = $plat_order_info['pay_money'];
                        $spread_rate = D('Percent_rate')->get_user_spread_rate($now_order['mer_id'],'sub_card');
                        $spread_type = 'sub_card';

                        $spread_users[]=$now_user['uid'];
                        if($now_user['wxapp_openid']!=''){
                            $spread_where['_string'] = "openid = '{$now_user['openid']}' OR openid = '{$now_user['wxapp_openid']}' ";
                        }else{
                            $spread_where['_string'] = "openid = '{$now_user['openid']}'";
                        }
                        $now_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where)->find();

                        if($data_sub_card_order['is_own']  && C('config.own_pay_spread')==0){
                            $payment_money=0;
                        }
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=My&a=spread_list&status=-1';
                        if(!empty($now_user_spread)){
                            if($now_user_spread['is_wxapp']){
                                $spread_user = D('User')->get_user($now_user_spread['spread_openid'],'wxapp_openid');
                            }else{
                                $spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
                            }
                            //$user_spread_rate = $update_group['spread_rate'] > 0 ? $update_group['spread_rate'] : C('config.user_spread_rate');
                            $user_spread_rate = $spread_rate['first_rate'];
                            if($spread_user && $user_spread_rate&&!in_array($spread_user['uid'],$spread_users)){
                                $spread_money = round(($balance_pay + $payment_money) * $user_spread_rate / 100, 2);
                                $spread_data = array(
                                    'uid'=>$spread_user['uid'],
                                    'spread_uid'=>0,
                                    'get_uid'=>$now_user['uid'],
                                    'money'=>$spread_money,
                                    'order_type'=>$spread_type,
                                    'order_id'=>$now_order['order_id'],
                                    'third_id'=>$now_order['store_id'],
                                    'add_time'=>$_SERVER['REQUEST_TIME']
                                );
                                if($spread_user['spread_change_uid']!=0){
                                    $spread_data['change_uid'] = 	$spread_user['spread_change_uid'];
                                }
                                D('User_spread_list')->data($spread_data)->add();
                                $buy_user = D('User')->get_user($now_user_spread['openid'],'openid');
                                if($spread_money>0){

                                    $money_name = '佣金';

                                    $model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $spread_user['openid'], 'first' => $buy_user['nickname'] . '通过您的分享购买了免单套餐，您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
                                }
                                $spread_users[]=$spread_user['uid'];
                                // D('User')->add_money($spread_user['uid'],$spread_money,'推广用户 '.$now_user['nickname'].' 购买 ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
                            }

                            //第二级分享佣金
                            $spread_where['_string'] = "openid = {$spread_user['openid']} OR openid = {$spread_user['wxapp_openid']} ";
                            $second_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where )->find();

                            if(!empty($second_user_spread)) {
                               if($second_user_spread['is_wxapp']){
                                $second_user = D('User')->get_user($second_user_spread['spread_openid'], 'wxapp_openid');
                            }else{
                                $second_user = D('User')->get_user($second_user_spread['spread_openid'], 'openid');
                            }
//							//$sub_user_spread_rate = $update_group['sub_spread_rate'] > 0 ? $update_group['sub_spread_rate'] : C('config.user_first_spread_rate');
                                $sub_user_spread_rate = $spread_rate['second_rate'];
                                if ($second_user && $sub_user_spread_rate&&!in_array($second_user['uid'],$spread_users)) {
                                    $spread_money = round(($balance_pay + $payment_money) * $sub_user_spread_rate / 100, 2);
                                    $spread_sec_data =array('uid' => $second_user['uid'], 'spread_uid' => $spread_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => $spread_type, 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
                                    if($second_user['spread_change_uid']!=0){
                                        $spread_sec_data['change_uid'] = 	$second_user['spread_change_uid'];
                                    }
                                    D('User_spread_list')->data($spread_sec_data)->add();
                                    $sec_user = D('User')->get_user($second_user_spread['openid'], 'openid');
                                    if($spread_money>0) {
                                        $model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $second_user['openid'], 'first' => $sec_user['nickname'] .'的子用户'.$buy_user['nickname'] . '通过您的分享购买了免单套餐，您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'),  $now_order['mer_id']);
                                    }
                                    $spread_users[]=$second_user['uid'];
                                    // D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
                                }

                                //顶级分享佣金
                                $spread_where['_string'] = "openid = {$second_user['openid']} OR openid = {$second_user['wxapp_openid']} ";
                                $first_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where(	$spread_where)->find();

                                if (!empty($first_user_spread) && C('config.user_third_level_spread')) {
                                    if($first_user_spread['is_wxapp']){
                                        $first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'wxapp_openid');
                                    }else{
                                        $first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'openid');
                                    }
//								//$sub_user_spread_rate = $update_group['third_spread_rate'] > 0 ? $update_group['third_spread_rate'] : C('config.user_second_spread_rate');
                                    $sub_user_spread_rate = $spread_rate['third_rate'];
                                    if ($first_spread_user && $sub_user_spread_rate&&!in_array($first_spread_user['uid'],$spread_users)) {
                                        $spread_money = round(($balance_pay + $payment_money) * $sub_user_spread_rate / 100, 2);
                                        $spread_thd_data=array('uid' => $first_spread_user['uid'], 'spread_uid' => $second_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => $spread_type, 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
                                        if($first_spread_user['spread_change_uid']!=0){
                                            $spread_thd_data['change_uid'] = 	$first_spread_user['spread_change_uid'];
                                        }
                                        D('User_spread_list')->data($spread_thd_data)->add();

                                        $fir_user = D('User')->get_user($first_user_spread['openid'], 'openid');
                                        if($spread_money>0) {
                                            $model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $first_spread_user['openid'], 'first' =>$fir_user['nickname'].'的子用户的子用户'.$buy_user['nickname'] . '通过您的分享购买了免单套餐，您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
                                        }
                                        // D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
                                    }
                                }

                            }
                        }
                    }


                    $href=C('config.site_url').'/wap.php?c=Sub_card&a=sub_card_list';
                    $sub_card = M('Sub_card')->where(array('id'=>$now_order['sub_card_id']))->find();
                    M('Sub_card')->where(array('id'=>$now_order['sub_card_id']))->setInc('sale_count',1);
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>'免单购买提醒', 'keyword1' => $sub_card['name'], 'keyword2' => $now_order['order_id'], 'keyword3' => $now_order['money'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'));
                    if (C('config.sms_store_success_order') == 2 ) {
                        $sms_data['uid'] = 0;
                        $sms_data['mobile'] = $now_user['phone'];
                        $sms_data['sendto'] = 'user';
                        $sms_data['content'] = '您购买免单套餐【'.$sub_card['name'].'】的订单(订单号：' . $now_order['order_id'] . ')已经完成支付';
                        Sms::sendSms($sms_data);
                    }

                    D('Scroll_msg')->add_msg('yuedan',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.'['.$sub_card['name'].']套餐成功');

                    return array('error'=>0,'msg'=>'购买成功','url'=>$this->get_order_url($now_order['order_id'],$now_order['is_mobile_pay']));
                }else{

                    return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
                }
            }
        }


        public function get_order_url($order_id, $is_mobile)
        {
            $now_order = $this->get_order_by_id($order_id);
            if ($now_order) {
                return C('config.site_url').'/wap.php?c=Sub_card&a=order_detail&order_id='.$order_id;
            }else{
                return array('error'=>1,'msg'=>'未知订单！');
            }

        }

        //获取订单消费码
        public function get_user_pass($fid,$ids=array()){
            $where['fid'] = $fid;
            $ids && $where['id'] = array('in',$ids);
            return M('Sub_card_user_pass')->where($where)->select();
        }

        public function get_store_pass($order_id,$store_id){
            return M('Sub_card_user_pass')->where(array('fid'=>$order_id,'store_id'=>$store_id))->select();
        }



        public function get_orderid_by_pass($pass,$store_id){
            $where['pass'] = $pass;
            $where['store_id'] = $store_id;
            $res =  M('Sub_card_user_pass')->where($where)->find();

            if(empty($res)){
                return array();
            }
            $sub_card = D('Sub_card')->get_sub_card($res['sub_card_id']);
            $sub_card_store = D('Sub_card')->sub_card_store_info($res['sub_card_id'],$store_id);
            if($res['share_uid']>0){
                $res['uid'] = $res['share_uid'];
            }
            $now_user = D("User")->get_user($res['uid']);
            $res['nickname'] = $now_user['nickname'];
            $res['phone'] = $now_user['phone'];
//            $res['effective_days'] = $sub_card['effective_days']-floor((time()-$res['add_time'])/86400);
            if($sub_card['use_time_type']==1){
                $res['effective_days'] = $sub_card['effective_days']-floor((time()-$res['add_time'])/86400);
            }else{
                $res['effective_days'] = floor(($sub_card_store['end_time']- strtotime(date('Ymd',time())))/86400);
            }
            $res['add_time'] = date('Y-m-d H:i:s',$res['add_time']);


            return array('sub_card'=>$sub_card,'pass_info'=>$res);
        }

        public function sub_card_list_by_uid($uid){
            $where['_string'] = "(uid = {$uid} AND share_uid = 0) or share_uid = {$uid}";
//            $card_list = $this->join('AS o LEFT JOIN '.C('DB_PREFIX').'sub_card AS s ON s.id = o.sub_card_id LEFT JOIN (SELECT * FROM '.C('DB_PREFIX').'sub_card_user_pass )')
//                ->field('s.*,o.order_id,o.store_id,o.status as consume_status,o.pay_time as buy_time,p.share,p.share_uid,p.uid')->where($where)->group('p.fid')->order('o.order_id DESC')->select();
//            dump($this);die;
            $card_list = M('Sub_card_user_pass')->where($where)->group('fid,share_uid')->order('fid DESC')->select();
            $now_time = time();

            $today_zero = strtotime(date('Ymd',time()));
            foreach ($card_list as &$v) {
                $condition['order_id'] = $v['fid'];
                $order_info = $this->where($condition)->find();

                $sub_card = D('Sub_card')->get_sub_card($v['sub_card_id']);
                $sub_card_store = M('Sub_card_mer_apply')->where(array('sub_card_id'=>$v['sub_card_id'],'store_id'=>$v['store_id']))->find();

                $v['store_id'] = explode(',',$order_info['store_id']);
                $v['name'] = $sub_card['name'];
                $v['desc'] = $sub_card['desc'];
                $v['free_total_num'] = $sub_card['free_total_num'];
                $v['order_id'] = $order_info['order_id'];
                $v['use_time_type'] = $sub_card['use_time_type'];
                $v['price'] = $sub_card['price'];
                if($v['share_uid']>0){
                    $v['store_num'] = M('Sub_card_user_pass')->where(array('share_uid'=>$uid,'sub_card_id'=>$v['sub_card_id'],'fid'=>$v['fid']))->group('store_id')->select();
                    $v['store_num'] = count($v['store_num']);
                    $v['pay_time'] = $v['add_time'];
//                    $v['free_total_num'] =
                }else{
                    $tmp = array_unique($v['store_id']);
                    $v['store_num'] = count($tmp);
                    $v['pay_time'] = $order_info['pay_time'];
                }
                if($sub_card['effective_days']<=0 && $sub_card['use_time_type']==1){
                    $v['effective_days'] = $sub_card['effective_days'];
                }else if($v['end_time']>0){
                    $v['effective_days'] =floor(($sub_card_store['end_time']-$today_zero)/86400);
                }else{
                    $v['effective_days'] = $sub_card['effective_days'];
                }
//                $v['effective_days'] = $v['effective_days']-floor(($now_time-$v['pay_time'])/86400);
                $v['use_count']  = M('Sub_card_user_pass')->where(array('fid'=>$v['fid'],'status'=>1))->count();
            }

            return $card_list;
        }

    }
?>