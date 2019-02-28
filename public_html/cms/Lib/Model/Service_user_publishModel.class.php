<?php
class Service_user_publishModel extends Model{
    /*根据 支付平台的英文 和 是否移动端支付 得到中文名称*/
    public function get_pay_name($pay_type,$is_mobile_pay, $paid = 1){
        switch($pay_type){
            case 'alipay':
                $pay_type_txt = '支付宝';
                break;
            case 'tenpay':
                $pay_type_txt = '财付通';
                break;
            case 'yeepay':
                $pay_type_txt = '易宝支付';
                break;
            case 'allinpay':
                $pay_type_txt = '通联支付';
                break;
            case 'chinabank':
                $pay_type_txt = '网银在线';
                break;
            case 'weixin':
                $pay_type_txt = '微信支付';
                break;
            case 'baidu':
                $pay_type_txt = '百度钱包';
                break;
            case 'unionpay':
                $pay_type_txt = '银联支付';
                break;
            case 'offline':
                $pay_type_txt = '货到付款';
                break;
            default:
                if ($paid) {
                    $pay_type_txt = '余额支付';
                } else {
                    $pay_type_txt = '未支付';
                    return '未支付';
                }
                
        }
        if($is_mobile_pay){
            $pay_type_txt .= '(移动端)';
        }
        return $pay_type_txt;
    }
    
    //获取订单信息
    public function get_pay_publish($uid,$order_id,$is_web=false){

        $now_order = $this->where(array('publish_id'=>$order_id))->find();
        if(empty($now_order)){
            return array('error'=>1,'msg'=>'当前订单已失效或不存在！');
        }
        if($now_order['status'] == 2){
            return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>'/wap.php?c=Service&a=need_list');
        }
        
        if($now_order['catgory_type'] == 2){
            $now_order['order_name'] = '帮我买';
            $info = M('service_user_publish_buy')->where(array('publish_id'=>$now_order['publish_id']))->find();
        }elseif ($now_order['catgory_type'] == 3) {
            $now_order['order_name'] = '帮我送';
            $info = M('service_user_publish_give')->where(array('publish_id'=>$now_order['publish_id']))->find();
        }

        $order_info = array(
            'order_id' => $now_order['publish_id'],
            'order_type' => 'service',
            'order_name' => $now_order['order_name'],
            'order_price' => $info['total_price'],
            'order_total_money' => $info['total_price'],
        );
        
        return array('error'=>0,'order_info'=>$order_info);
    }

    public  function get_order_by_business_id($param){
        $now_order = $this->where($param)->find();
        if ($now_order['pay_type']) {
            $now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], 0);
        }
        return $now_order;
    }

    public function wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user){
        //return array('error_code' => false, 'pay_money' => $order_info['order_total_money']);
        //去除微信优惠的金额
        
        $pay_money = $order_info['order_total_money'];

        if($pay_money<=0){
            return $this->wap_after_pay_before($order_info);
        }

        //去掉折扣
        if($merchant_balance['card_discount']>0){
            $data_plat_order['card_discount'] = $merchant_balance['card_discount'];
            $tmp_pay_money = sprintf("%.2f",$pay_money*$merchant_balance['card_discount']/10);
            //$tmp_pay_money = floor($pay_money*$merchant_balance['card_discount']*10)/100;
            $data_plat_order['merchant_discount_money'] = sprintf("%.2f",$pay_money - $tmp_pay_money );
            $pay_money = $tmp_pay_money;
        }
        if(C('config.open_extra_price')==1){
            $user_score_use_percent=(float)C('config.user_score_use_percent');
            $order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
            $pay_money +=$order_info['order_extra_price'];
        }

        //判断优惠券
        if($now_coupon['card_price']>0) {
            $data_plat_order['card_id'] = $now_coupon['merc_id'];
            $data_plat_order['card_price'] = $now_coupon['card_price'];
            if ($now_coupon['card_price'] >= $pay_money) {
                $order_result = $this->wap_pay_save_order($order_info, $data_plat_order);
                if ($order_result['error_code']) {
                    return $order_result;
                }
                return $this->wap_after_pay_before($order_info);
            }
            $pay_money -= $now_coupon['card_price'];
            $data_plat_order['pay_money'] = $pay_money;
        }

        //系统优惠券
        if($now_coupon['coupon_price']>0){
            $data_plat_order['coupon_id'] = $now_coupon['sysc_id'];
            $data_plat_order['coupon_price'] = $now_coupon['coupon_price'];
            if ($now_coupon['coupon_price'] >= $pay_money) {
                $order_result = $this->wap_pay_save_order($order_info, $data_plat_order);
                if ($order_result['error_code']) {
                    return $order_result;
                }
                return $this->wap_after_pay_before($order_info);
            }
            $pay_money -= $now_coupon['coupon_price'];
            $data_plat_order['pay_money'] = $pay_money;
        }

        // 使用积分
        if(!empty($order_info['score_deducte'])&&$order_info['use_score']){
            $data_plat_order['score_used_count']  = $order_info['score_used_count'];
            $data_plat_order['score_deducte']     = (float)$order_info['score_deducte'];
            if($order_info['score_deducte'] >= $pay_money){
                //扣除积分
                $order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
                if($order_result['error_code']){
                    return $order_result;
                }
                $order_info['pay_money'] = $pay_money;
                return $this->wap_after_pay_before($order_info);
            }


            $pay_money -= $order_info['score_deducte'];
            //$data_plat_order['system_pay'] += $data_plat_order['score_deducte'];
        }

        //判断商家余额
        if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
            if($merchant_balance['card_money'] >= $pay_money){
                $data_plat_order['merchant_balance'] = $pay_money;
                $order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
                if($order_result['error_code']){
                    return $order_result;
                }
                return $this->wap_after_pay_before($order_info);
            }else{
                $data_plat_order['merchant_balance'] = $merchant_balance['card_money'];
            }
            $pay_money -= $merchant_balance['card_money'];
        }

        if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
            if($merchant_balance['card_give_money'] >= $pay_money){
                $data_plat_order['card_give_money'] = $pay_money;
                $order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
                if($order_result['error_code']){
                    return $order_result;
                }
                return $this->wap_after_pay_before($order_info);
            }else{
                $data_plat_order['card_give_money'] = $merchant_balance['card_give_money'];
            }
            $pay_money -= $merchant_balance['card_give_money'];
        }


        //判断帐户余额
        if(!empty($now_user['now_money'])&&$order_info['use_balance']){
            if($now_user['now_money'] >= $pay_money){
                $data_plat_order['balance_pay'] = $pay_money;
                $order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
                if($order_result['error_code']){
                    return $order_result;
                }
                return $this->wap_after_pay_before($order_info);
            }else{
                $data_plat_order['balance_pay'] = $now_user['now_money'];
            }
            $pay_money -= $now_user['now_money'];
        }
        //在线支付

        $order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
        if($order_result['error_code']){
            return $order_result;
        }

        return array('error_code'=>false,'pay_money'=>$pay_money);
    }


    //修改订单
    public function wap_pay_save_order($order_info,$data_plat_order){

        $data_plat_order['system_coupon_id']            = !empty($data_plat_order['coupon_id']) ? $data_plat_order['coupon_id'] : 0;
        $data_plat_order['system_coupon_price']         = !empty($data_plat_order['coupon_price']) ? $data_plat_order['coupon_price'] : 0;
        $data_plat_order['merchant_coupon_id']          = !empty($data_plat_order['card_id']) ? $data_plat_order['card_id'] : 0;
        $data_plat_order['merchant_coupon_price']           = !empty($data_plat_order['card_price']) ? $data_plat_order['card_price'] : 0;
        $data_plat_order['merchant_balance_pay']    = !empty($data_plat_order['merchant_balance']) ? $data_plat_order['merchant_balance'] : 0;
        $data_plat_order['merchant_balance_give']   = !empty($data_plat_order['card_give_money']) ? $data_plat_order['card_give_money'] : 0;
        $data_plat_order['merchant_discount']   = !empty($data_plat_order['card_discount']) ? $data_plat_order['card_discount'] : 0;
        $data_plat_order['merchant_discount_money']     = !empty($data_plat_order['merchant_discount_money']) ? $data_plat_order['merchant_discount_money'] : 0;
        $data_plat_order['system_balance']      = !empty($data_plat_order['balance_pay']) ? $data_plat_order['balance_pay'] : 0;
        $data_plat_order['system_score']    = !empty($data_plat_order['score_used_count'])?$data_plat_order['score_used_count']:0;
        $data_plat_order['system_score_money']      = !empty($data_plat_order['score_deducte'])?(float)$data_plat_order['score_deducte']:0;
        $data_plat_order['score_discount_type']      = !empty($order_info['score_discount_type']) ? $order_info['score_discount_type'] : 0;
        $data_plat_order['last_time']           = $_SERVER['REQUEST_TIME'];
        $data_plat_order['submit_order_time']   = $_SERVER['REQUEST_TIME'];

        if ($this->where(array('publish_id'=>$order_info['order_id']))->data($data_plat_order)->save()) {
            return array('error_code' => false, 'msg' => '保存订单成功！');
        } else {
            return array('error_code' => true, 'msg' => '保存订单失败！请重试或联系管理员。');
        }
    }

    public function wap_after_pay_before($order_info){
        $order_param = array(
                'order_id' => $order_info['order_id'],
                'mer_id' => $order_info['mer_id'],
                'pay_type' => '',
                'third_id' => '',
                'is_mobile' => 0,
        );
        $result_after_pay = $this->after_pay($order_param);

        if($result_after_pay['error']){
            return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
        }
        return array('error_code'=>false,'msg'=>'支付成功！','url'=>str_replace('/source/','/',$result_after_pay['url']));
    }



    public function after_pay($order_param){
        
        if ($order_param['pay_type'] != '') {
            $where['orderid'] = $order_param['order_id'];
        } else {
            $where['publish_id'] = $order_param['order_id'];
        }
        $now_order = $this->field(true)->where($where)->find();
        $business_order_table = D(ucfirst($now_order['business_type']).'_order');
        if (empty($now_order)) {
            return array('error' => 1, 'msg' => '当前订单不存在！');
        } elseif ($now_order['status'] == 2){
            return array('error' => 1, 'msg' => '该订单已付款！', 'url' => $url);
        } else {
            // 得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
         
            $now_user = D('User')->get_user($now_order['uid']);
            if(empty($now_user)){
                return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
            }

            //判断平台优惠券
            if($now_order['system_coupon_id']){
                $now_coupon = D('System_coupon')->get_coupon_by_id($now_order['system_coupon_id']);
                if(empty($now_coupon)){
                    return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
                }
            }

            //判断会员卡余额
            $merchant_balance = floatval($now_order['merchant_balance_pay']);
            if($merchant_balance){
                $user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$order_param['mer_id']);
                if($user_merchant_balance['card_money'] < $merchant_balance){
                    return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
                }
            }

            $card_give_money = floatval($now_order['merchant_balance_give']);
            
            if($card_give_money){
                $user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$order_param['mer_id']);
                if($user_merchant_balance['card_money_give'] < $card_give_money){
                    return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
                }
            }
            //判断帐户余额
            $balance_pay = floatval($now_order['system_balance']);
            if($balance_pay){
                if($now_user['now_money'] < $balance_pay){
                    return $this->wap_after_pay_error($now_order,$order_param,'您的帐户余额不够此次支付！');
                }
            }

            //如果使用了商家优惠券
//          if($now_order['card_id']){
//              $use_result = D('Member_card_coupon')->user_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);
//              if($use_result['error_code']){
//                  return array('error'=>1,'msg'=>$use_result['msg']);
//              }
//          }

            if($now_order['merchant_coupon_id']){
                $use_result = D('Card_new_coupon')->user_coupon($now_order['merchant_coupon_id'],$now_order['business_id'],$now_order['business_type'],$order_param['mer_id'],$now_order['uid']);
                if($use_result['error_code']){
                    return array('error'=>1,'msg'=>$use_result['msg']);
                }
            }
            //如果使用了平台优惠券
            if($now_order['system_coupon_id']){

                $use_result = D('System_coupon')->user_coupon($now_order['system_coupon_id'],$now_order['business_id'],$now_order['business_type'],$order_param['mer_id'],$now_order['uid']);
                if($use_result['error_code']){
                    return array('error'=>1,'msg'=>$use_result['msg']);
                }
            }


            //如果用户使用了积分抵扣，则扣除相应的积分
            //判断积分数量是否正确
            $score_used_count=$now_order['system_score'];
            if($now_user['score_count']<$score_used_count){
                return array('error'=>1,'msg'=>'保存订单失败！请重试或联系管理员。');
            }
            if($score_used_count>0){
                $use_result = D('User')->user_score($now_order['uid'],$score_used_count,'购买 '.$now_order['order_name'].' 扣除'.C('config.score_name'));
                if($use_result['error_code']){
                    return array('error'=>1,'msg'=>$use_result['msg']);
                }
            }

            //如果使用会员卡余额
            if($merchant_balance){
                $use_result = D('Card_new')->use_money($now_order['uid'],$order_param['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
                
                if($use_result['error_code']){
                    return array('error'=>1,'msg'=>$use_result['msg']);
                }
            }

            if($card_give_money){
                $use_result = D('Card_new')->use_give_money($now_order['uid'],$order_param['mer_id'],$card_give_money,'购买 '.$now_order['order_name'].' 扣除会员卡赠送余额');
                if($use_result['error_code']){
                    return array('error'=>1,'msg'=>$use_result['msg']);
                }
            }
            //如果用户使用了余额支付，则扣除相应的金额。
            if(!empty($balance_pay)){
                $use_result = D('User')->user_money($now_order['uid'],$balance_pay,'购买 '.$now_order['order_name'].' 扣除余额');
                if($use_result['error_code']){
                    return array('error'=>1,'msg'=>$use_result['msg']);
                }
            }

            $data_order = array();
            // $data_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
            // $data_order['pay_money'] = floatval($order_param['pay_money']);//在线支付的钱
            // $data_order['pay_type'] = $order_param['pay_type'];
            // $data_order['third_id'] = $order_param['third_id'];
            // $data_order['is_mobile_pay'] = $order_param['is_mobile'];
            // $data_order['is_own'] = $order_param['is_own'];
            $data_order['status'] = 2;
            if($this->where($where)->data($data_order)->save()){

                $this->template_message($order_param['order_id']);
                // $now_order = $this->field(true)->where($where)->find();
                // if(isset($order_param['sub_mch_id']) && $order_param['sub_mch_id'] >0){
                //     $now_order['sub_mch_id'] = $order_param['sub_mch_id'];
                // }
                // $business_order_table->after_pay($now_order['business_id'],$now_order);
                // $url = $business_order_table->get_order_url($now_order['business_id'],$order_param['is_mobile']);
                return array('error' => 0, 'url' =>U('Service/need_list'));
            }else{
                return array('error' => 1, 'msg' => '修改订单状态失败，请联系系统管理员！');
            }
        }
    }
    public function get_order_url($order_id,$is_mobile){
        $now_order = $this->where(array('order_id'=>$order_id))->find();

        $business_order_table = D(ucfirst($now_order['business_type']).'_order');

        return $business_order_table->get_order_url($now_order['business_id'],$is_mobile);
    }

    //支付时，金额不够，记录到帐号
    public function wap_after_pay_error($now_order,$order_param,$error_tips){

        //记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
        $user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
        $this->where(array('order_id'=>$now_order['order_id']))->setField('status',3);
        if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
            exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
        }
        if($user_result['error_code']){
            return array('error'=>1,'msg'=>$user_result['msg']);
        }else{
            if($order_param['is_mobile']){
                $return_url = str_replace('/source/','/',U('My/group_order',array('order_id'=>$now_order['order_id'])));
            }else{
                $return_url = U('User/Index/group_order_view',array('order_id'=>$now_order['order_id']));
            }
            return array('error'=>1,'msg'=>$error_tips.'，已将您充值的金额添加到您的余额内。','url'=>$return_url);
        }
    }

    //param array（business_id,business_type）
    public function order_refund($param){
        $now_order = $this->get_order_by_business_id($param);
        //if(!$now_order['paid']){
        //  return array('error'=>1,'msg'=>'该订单未支付不能退款');
        //}
        $data_plat_order['is_refund'] = 1;
        $order_id = $now_order['order_id'];
        if($now_order['system_balance']>0){
            $result = D('User')->add_money($now_order['uid'],$now_order['system_balance'],'退款 ' . $now_order['order_name'] . '(' . $order_id . ') 增加余额');

            $param = array('refund_time' => time());
            if($result['error_code']){
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }

            $data_plat_order['order_id'] = $now_order['order_id'];
            $data_plat_order['refund_detail'] = serialize($param);
            $result['error_code'] && $data_plat_order['is_refund'] = 4;
            $this->data($data_plat_order)->save();
            if ($result['error_code']) {
                return array('error'=>1,'msg'=>$result['msg']);
            }
            $refund_msg = ' 平台余额退款成功 |';
        }
        if($now_order['system_score']>0 && $now_order['system_score_money']>0){
            $result = D('User')->add_score($now_order['uid'],$now_order['system_score'],'退款 ' . $now_order['order_name'] . '(' . $order_id . ') '.$this->config['score_name'].'回滚');
            $param = array('refund_time' => time());
            if ($result['error_code']) {
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }
            $data_plat_order['order_id'] = $now_order['order_id'];
            $data_plat_order['refund_detail'] = serialize($param);
            $result['error_code'] && $data_plat_order['is_refund'] = 4;
            $this->data($data_plat_order)->save();
            if ($result['error_code']) {
                return array('error'=>1,'msg'=>$result['msg']);

            }
            $refund_msg .= ' '.$result['msg'].'|';
        }
        if ($now_order['merchant_balance_pay'] != '0.00'||$now_order['merchant_balance_give']!='0.00') {
            $result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance_pay'],$now_order['merchant_balance_give'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');

            $param = array('refund_time' => time());
            if ($result['error_code']) {
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }

            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize($param);
            $result['error_code'] && $data_plat_order['is_refund'] = 4;
            $this->data($data_shop_order)->save();
            if ($result['error_code']) {
                return array('error'=>1,'msg'=>$result['msg']);
            }
            $refund_msg .= $result['msg'] .'|';
        }

        if($now_order['pay_money']>0){
            $now_order['business_param'] = unserialize($now_order['business_param']);
            if ($now_order['is_own'] && $now_order['business_param']['mer_id'] ) {
                $pay_method = array();
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['business_param']['mer_id']))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }
                $now_merchant = D('Merchant')->get_info($now_order['mer_id']);
                if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
                    $pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
                    $pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
                    $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                    $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                    $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                    $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
                    $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
                    $pay_method['weixin']['config']['is_own'] = 2 ;
                }
            } else {
                $pay_method = D('Config')->get_pay_method(0,0,1);
            }

            if (empty($pay_method)) {
                return array('error'=>1,'msg'=>'系统管理员没开启任一一种支付方式');

            }
            if (empty($pay_method[$now_order['pay_type']])) {
                return array('error'=>1,'msg'=>'您选择的支付方式不存在，请更新支付方式！');

            }

            $pay_class_name = ucfirst($now_order['pay_type']);
            $import_result = import('@.ORG.pay.'.$pay_class_name);
            if(empty($import_result)){

                return array('error'=>1,'msg'=>'系统管理员暂未开启该支付方式，请更换其他的支付方式');
            }

            $now_order['order_type'] = 'plat';
            $now_order['order_id'] = $now_order['orderid'];
            if($now_order['is_mobile_pay']==3){
                $pay_method[$now_order['pay_type']]['config'] =array(
                        'pay_weixin_appid'=>$this->config['pay_wxapp_appid'],
                        'pay_weixin_key'=>$this->config['pay_wxapp_key'],
                        'pay_weixin_mchid'=>$this->config['pay_wxapp_mchid'],
                        'pay_weixin_appsecret'=>$this->config['pay_wxapp_appsecret'],
                );
                C('config.pay_weixin_client_cert',$this->config['pay_wxapp_cert']);
                C('config.pay_weixin_client_key',$this->config['pay_wxapp_cert_key']);
            }
            $now_order['payment_money'] = $now_order['pay_money'];
            $pay_class = new $pay_class_name($now_order, $now_order['pay_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, 1);
            $go_refund_param = $pay_class->refund();

            $now_order['order_id'] = $order_id;
            $data_plat_order['order_id'] = $order_id;
            $data_plat_order['refund_detail'] = serialize($go_refund_param['refund_param']);
            if (empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok') {
                $data_plat_order['is_refund'] = 1;
            }
            $data_plat_order['last_time'] = time();
            $this->data($data_plat_order)->save();

            if($data_plat_order['is_refund'] != 1){
                return array('error'=>1,'msg'=>$go_refund_param['msg']);
            }else{
                $refund_msg .="在线支付退款成功| ";
            }

        }

        if($data_plat_order['is_refund'] != 4){
            return array('error'=>0,'msg'=>$refund_msg);
        }

    }


    public function template_message($publish_id){
        $info = $this->where(array('publish_id'=>$publish_id))->find();
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
            $publishInfo = D("Service_user_publish")->where(array('publish_id' => $publish_id))->field('publish_id,uid,cid,order_sn')->find();
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $publish_id;
//            $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'keyword1' => '您发布的需求已经完成支付，请等待接单。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时查看！'));
            // 帮买帮送修改  待办事项通知 模板 为 订单状态更新 模板
            $model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'OrderSn' => $publishInfo['order_sn'], 'OrderStatus' =>'您发布的需求已经完成支付，请查看。', 'remark' => date('Y.m.d H:i:s')));
        }
    }
}

?>