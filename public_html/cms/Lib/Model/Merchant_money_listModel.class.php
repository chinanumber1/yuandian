<?php
// 商家余额
class Merchant_money_listModel extends Model{
    //增加余额
    public function add_money($order_info){
        $order_info['total_money'] = round($order_info['total_money'],2);
        $order_info['total_price'] = round($order_info['total_price'],2);
        //商家绑定用户
        $mer_id = $order_info['mer_id'];

        $desc = $order_info['desc'];
        $mer_user = D('Merchant')->get_merchant_user($mer_id);
        if(isset($order_info['store_id'])){
            $now_store = M('Merchant_store')->where(array('store_id'=>$order_info['store_id']))->find();
            $date['store_id'] = $order_info['store_id'];
        }

        $now_merchant = D('Merchant')->get_info($mer_id);
        //自有支付
        if(isset($order_info['is_own'])&&$order_info['is_own']>0){
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $alias_name = $this->get_alias_c_name($order_info['order_type']);
            $store_name = '无';
            if (!empty($now_store)) {
                $store_name = $now_store['name'];
            }
            if($order_info['is_own']==2){
                $remark = '子商户支付商家余额不增加余额，请到子商户平台中查看';
            }else{
                $remark = '请到商家平台中查看';
            }
            $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $mer_user['openid'], 'first' => '收款成功,' , 'keyword1' => $alias_name[$order_info['order_type']], 'keyword2' =>$order_info['payment_money'],'keyword3' =>$store_name, 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' =>$remark), $mer_id);
            if(C('config.open_mer_own_percent')==1){
                $own_percent_money = $order_info['payment_money'];
            }
            $order_info['payment_money']=0;
        }

        switch($order_info['order_type']){
            case 'group':
                if(!empty($order_info['refund'])){
                    $num =1;
                    $money=$order_info['refund_money'];
                    $order_info['total_money'] =  $money;
                }elseif(!$order_info['verify_all']){
                    $num =1;
                    if($order_info['pay_type']=='offline'){
                        $count = D('Group_pass_relation')->get_pass_num($order_info['order_id'],1);
                        if($order_info['score_deducte']>$order_info['price']){
                            if($order_info['score_deducte']-$count*$order_info['price']>0){
                                $money =$order_info['price'];
                            }else{
                                $money = $order_info['score_deducte']-($count-1)*$order_info['price'];
                            }
                        }else{
                            if($count==1){
                                $money = $order_info['score_deducte'];
                            }else{
                                $money=0;
                                return array('error_code'=>false,'msg'=>'无收入记录');
                            }
                        }
                    }else{
                        $money = ($order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['merchant_balance'])/$order_info['num']*100/100;
                    }
                    $order_info['total_money'] = $money;
                }else{
                    $num =$order_info['num'];
                    $money = ($order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['merchant_balance'])*100/100;
                    $order_info['total_money'] = $order_info['total_money'];
                }
                $order_info['express_fee'] = $order_info['express_fee']/$order_info['total_money']*$money*100/100;
                $money -= $order_info['express_fee'];


                break;
            case 'meal':
                $num =$order_info['total'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['merchant_balance'];
                $order_info['total_money'] = $order_info['total_price'];
                break;
            case 'shop':
                $num =$order_info['num'];


                if($order_info['is_pick_in_store']==0&&($order_info['card_give_money']>0||$order_info['card_price']>0)&&$order_info['order_from']==0){//平台配送 （使用了商家赠送余额或商家优惠券）
                    $pay_for_system = $order_info['freight_charge'];
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']-$order_info['no_bill_money']+$order_info['merchant_balance'];
                    $money_ = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']+$order_info['merchant_balance'];
                    if($money<=0){
                        $pay_for_system-=$money_;
                    }
                }else if($order_info['is_pick_in_store']==0&&$order_info['order_from']==1){//平台配送 （使用了商家赠送余额或商家优惠券）
                    $pay_for_system = $order_info['no_bill_money'];
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']+$order_info['merchant_balance'];

                }else{
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']-$order_info['no_bill_money']+$order_info['merchant_balance'];
                }
                if($order_info['is_pick_in_store']==0){
                    $money = $money-$order_info['other_money'];
                }
                if($now_merchant['package_fee_percent']==0){
                    $money = $money-$order_info['packing_charge'];
                }
                $order_info['total_money'] = $order_info['total_price'];
                break;
            case 'shop_offline':
                $num = 1;
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']-$order_info['no_bill_money']+$order_info['merchant_balance'];
                $order_info['total_money'] = $order_info['total_price'];
                break;
            case 'appoint':
                $num = 1;
                $money = $order_info['money'];
                $order_info['total_money']  = $order_info['total_money'];
                break;
            case 'store':
                $num =1;
                $order_info['total_money'] = $order_info['total_price'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance']+$order_info['coupon_price']+$order_info['score_deducte'];
                break;
            case 'cash':
                $num =1;
                $order_info['total_money'] = $order_info['total_price'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance']+$order_info['coupon_price']+$order_info['score_deducte'];
                break;
            case 'wxapp':
                $num =1;
                $order_info['total_money'] = $order_info['money'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
                break;
            case 'weidian':
                $num =$order_info['order_num'];
                $order_info['total_money'] = $order_info['money'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
                break;
            case 'withdraw':
                $num =1;
                $money = $order_info['money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            case 'yydb':
                $num =1;
                $money = $order_info['money'];
                $order_info['total_money']= $money;
                break;
            case 'coupon':
                $num =1;
                $money = $order_info['money'];
                $order_info['total_money']= $money;
                break;
            case 'spread':
                $num =1;
                $money = $order_info['money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            case 'market':
            case 'marketcancel':
                $num =1;
                $money = $order_info['money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            case 'sub_card':
                $num =1;
                $money = $order_info['money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            case 'merrecharge':
                $num =1;
                $money = $order_info['pay_money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            default:
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
                break;
        }
        if(C('config.vip_discount_pay_for')==1 && $order_info['vip_discount_money']>0){
            $money+=$order_info['vip_discount_money'];
        }

        if($order_info['order_type']=='group'){
            $percent = D('Percent_rate')->get_percent($mer_id,$order_info['order_type'],$money,$order_info['group_id']);
        } elseif($order_info['order_type']=='meal'&&$order_info['order_from']==1){
            if(C('config.is_open_merchant_foodshop_discount')==1){
                $where = array('real_orderid'=>$order_info['order_id']);
                $percent = D('Foodshop_order')->where($where)->getField('mer_table_scale');
            }else{
                $percent = 0;
            }
            if($percent==0 || $percent==100){
                $percent = D('Percent_rate')->get_percent($mer_id,'meal_scan',$money);
            }
        } elseif($order_info['order_type']=='market'){
            $percent = D('Percent_rate')->get_market_percent($mer_id);
        } elseif($order_info['order_type']=='marketcancel'){
            $percent = 0;
        } elseif($order_info['order_type']=='sub_card') {
            $percent = $order_info['percent'];
        } else {
            if(C('config.is_open_merchant_foodshop_discount')==1){
                $where = array('real_orderid'=>$order_info['order_id']);
                $percent = D('Foodshop_order')->where($where)->getField('mer_table_scale');
            }else{
                $percent = 0;
            }
            if($percent == 0 || $percent == 100){
                $percent = D('Percent_rate')->get_percent($mer_id,$order_info['order_type'],$money);
            }

        }
        $percent = floatval($percent);
        fdump($percent,'percent');
        fdump($money,'money');
        if(C('config.open_mer_own_percent') && $own_percent_money>0){
            $desc_pay_for_system='对商家自有支付抽成，在线自有支付支付金额：'.$own_percent_money.',扣除商家余额：'.sprintf("%.2f",$own_percent_money*$percent/100);
            $result_pay = $this->use_money($mer_id,sprintf("%.2f",$own_percent_money*$percent/100),$order_info['order_type'],$desc_pay_for_system,$order_info['real_orderid'],$percent,sprintf("%.2f",$own_percent_money*$percent/100));
        }

        if($money<=0){

            if($pay_for_system!=0){
                if($order_info['order_from']==1){
                    $desc_pay_for_system='商城订单快递配送转为' . C('config.deliver_name') . '，系统扣除' . C('config.deliver_name') . '费';
                }else{
                    $desc_pay_for_system= '快店' . C('config.deliver_name') . '，平台从商家余额中扣除订单的配送费';
                }
                $result_pay = $this->use_money($mer_id,$pay_for_system,$order_info['order_type'],$desc_pay_for_system,$order_info['real_orderid']);
            }
            $this->merchant_card($mer_id,$order_info);
            return array('error_code'=>false,'msg'=>'无收入记录');
        }

        $date['num'] = $num>0?$num:1;
        $date['mer_id']=$mer_id>0?$mer_id:1;
        if($order_info['order_type']!='withdraw' && $order_info['order_type']!='merrecharge'){
            if(C('config.open_extra_price')==1){
                $date['score']=D('Percent_rate')->get_extra_money($order_info);
                $date['score_count']=$order_info['score_used_count'];
            }else {
                //$date['score'] = $money*C('config.user_score_get');
                if(C('config.add_score_by_percent')==0 && (C('config.open_score_discount')==0 || $order_info['score_discount_type']!=2)) {
                    if (C('config.open_score_get_percent') == 1) {
                        $score_get = C('config.score_get_percent') / 100;
                    } else {
                        $score_get = C('config.user_score_get');
                    }
                    if ($order_info['mer_id'] > 0) {
                        $now_merchant = D('Merchant')->get_info($order_info['mer_id']);
                        if ($now_merchant['score_get_percent'] >= 0) {
                            $score_get = $now_merchant['score_get_percent'] / 100;
                        }
                    }
                    $date['score'] = round($money*$score_get);
                }
                $date['score_count']=$order_info['score_used_count']>0?$order_info['score_used_count']:0;
            }
        }

        $date['type']=$order_info['order_type'];
        if($date['type']=='group'||$date['type']=='shop') {
            $date['order_id'] = $order_info['real_orderid'];
        }else{
            $date['order_id'] = $order_info['order_id'];
        }

        //除了提现，分佣，其他的收入要抽成
        if($order_info['order_type']!='withdraw' && $order_info['order_type']!='spread' && $order_info['order_type']!='merrecharge'){
            $date['total_money']= $order_info['total_money'];
            $date['system_take']=  round($money*$percent/100,2);
            if($order_info['uid']>0 && C('config.add_score_by_percent')==0 && round($date['system_take'])>0) {
                $date['score'] = round($date['system_take']);
            }
            if($now_merchant['package_fee_percent']==0){
                $money = $money+$order_info['packing_charge'];
            }else if($order_info['express_fee']>0){
                $money = $money+$order_info['express_fee'];
            }
//            $date['money']= sprintf("%.2f",$money*(100-$percent)/100);
            $date['money']= $money- $date['system_take'];
//            if($order_info['order_type']=='shop'&&$order_info['order_from']==1){
//                $date['money']+=$order_info['freight_charge'];
//            }


            $date['percent'] = $percent;
        }else{
            $date['percent'] = 0;
        }
        $date['income'] = 1;
        $date['use_time']= time();
        $date['desc']=  empty($desc)?'':$desc;

        if( $percent != 100 && $date['money']!=0 && !M('Merchant')->where(array('mer_id'=>$mer_id))->setInc('money', $date['money'])  ){

            return array('error_code'=>true,'msg'=>'增加商家余额失败');
        }elseif($order_info['order_type']=='group'||$order_info['order_type']=='meal'||$order_info['order_type']=='shop'||$order_info['order_type']=='appoint'||$order_info['order_type']=='store'||$order_info['order_type']=='cash'||$order_info['order_type']=='wxapp'||$order_info['order_type']=='weidian'){
            if($order_info['order_type']=='cash'){
                $date['type'] = 'store';
            }
            M(ucfirst($date['type']) . '_order')->where(array('order_id' => $order_info['order_id']))->setField('is_pay_bill', 1);
            $date['type'] = $order_info['order_type'];
        }
        $now_mer_money = M('Merchant')->field('money')->where(array('mer_id'=> $mer_id))->find();
        $date['now_mer_money'] = $now_mer_money['money'];

        if(!$this->add($date)){

            return array('error_code'=>true,'msg'=>$desc.' ，保存商家收入失败！');
        }else{
            if($order_info['order_type'] != 'withdraw'&& !empty($mer_user) && $mer_user['open_money_tempnews']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $alias_name = $this->get_alias_c_name($order_info['order_type']);
                $store_name = '无';
                if (!empty($now_store)) {
                    $store_name = $now_store['name'];
                }
                $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $mer_user['openid'], 'first' => '收款成功，当前商家余额:'.$now_mer_money['money'] , 'keyword1' => $alias_name[$order_info['order_type']], 'keyword2' =>$money,'keyword3' =>$store_name, 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到商家中心商家余额中查看'), $mer_id);
            }
            if($order_info['order_type']!='merrecharge'){
                $this->merchant_card($mer_id,$order_info);
            }

            //解冻用户奖励金额 定制
            if(C('config.free_recommend_awards_percent')>0 && $order_info['uid'] ){
                // 在线支付金额 平台余额 商家余额 商家会员卡赠送余额
                // 推广注册的用户 推广注册的商家
                $award_money  = $order_info['payment_money'] + $order_info['balance_pay']+$order_info['merchant_balance']+$order_info['card_give_money'];
                if($award_money > 0) {
                    $spread_users[]  = $order_info['uid'];
                    $User_model      = D('User');
                    $Fenrun_model      = D('Fenrun');
                    $now_user        = $User_model->get_user($order_info['uid']);
                    if($now_user['openid']){
                        $where['_string'] = " openid = '{$now_user['openid']}' OR uid = {$now_user['uid']}";
                    }else{
                        $where['_string'] = " uid = {$now_user['uid']}";
                    }

                    $now_user_spread = D('User_spread')->field('`spread_openid`,`spread_uid`, `openid`,`uid`')->where($where)->find();

                    if (!empty($now_user_spread)) {
                        if(!empty($now_user_spread['spread_uid'])){
                            $spread_user = $User_model->get_user($now_user_spread['spread_uid'], 'uid');
                        }else if (!empty($now_user_spread['spread_openid'])){
                            $spread_user = $User_model->get_user($now_user_spread['spread_openid'], 'openid');
                        }

                        if ($spread_user && !in_array($spread_user['uid'], $spread_users)) {
                            $free_money = round(($award_money) * C('config.free_recommend_awards_percent') / 100, 2);

                            if ($free_money > 0 && $spread_user['frozen_award_money'] > 0) {
                                $Fenrun_model->free_user_recommend_awards($spread_user['uid'], $free_money,1,$order_info['uid']);
                            }
                        }
                    }
                    //如果该商家是被推荐注册的

                    if ($now_merchant['uid']) {
                        $now_merchant_user = $User_model->get_user($now_merchant['uid']);
                        $where['_string'] = " uid = {$now_merchant['uid']}";
                        if($now_merchant_user['openid']){
                            $where['_string'] = " (openid = '{$now_merchant_user['openid']}' AND openid<>'') OR uid ={$now_merchant['uid']}";
                        }else{
                            $where['_string'] = " uid = {$now_merchant['uid']}";
                        }
                        $now_user_spread   = D('User_spread')->field('`spread_openid`,`spread_uid`, `openid`,`uid`')->where($where)->find();
                        if (!empty($now_user_spread)) {
                            if(!empty($now_user_spread['spread_uid'])){
                                $spread_user = $User_model->get_user($now_user_spread['spread_uid'], 'uid');
                            }else if (!empty($now_user_spread['spread_openid'])){
                                $spread_user = $User_model->get_user($now_user_spread['spread_openid'], 'openid');
                            }
                            if ($spread_user && !in_array($spread_user['uid'], $spread_users)) {
                                $free_money = round(($award_money) * C('config.free_mer_award_percent') / 100, 2);
                                if ($free_money > 0 && $spread_user['frozen_award_money'] > 0) {
                                    $Fenrun_model->free_user_recommend_awards($spread_user['uid'], $free_money, 2,$mer_id);
                                }
                            }
                        }
                    }
                }
            }

            if(C('config.open_distributor')>0 && $order_info['mer_id'] &&C('config.agent_percent')>0){
                D('Distributor_agent')->agent_add_money($order_info['mer_id'],$money,$order_info['order_id']);
            }

            if(C('config.share_coupon')==1 && $order_info['share_status']==1){
                $param['type'] = $order_info['order_type'];
                $param['order_id'] = $order_info['order_id'];
                $param['uid'] = $order_info['uid'];
                D('System_coupon')->share_coupon_rand_get_coupon($param);
            }
            if($pay_for_system!=0){
                if($order_info['order_from']==1){
                    $desc_pay_for_system='商城订单快递配送转为' . C('config.deliver_name') . '，系统扣除' . C('config.deliver_name') . '费';
                }else{
                    $desc_pay_for_system= '快店' . C('config.deliver_name') . '，平台从商家余额中扣除订单的配送费';
                }
                $this->use_money($mer_id,$pay_for_system,$order_info['order_type'],$desc_pay_for_system,$order_info['real_orderid'],$mer_user);
            }


            //增加积分按平台抽成算
            if( $order_info['uid']>0 && C('config.add_score_by_percent')==1 &&  round($date['system_take'])>0 && (C('config.open_score_discount')==0 || $order_info['score_discount_type']!=2)){
                $score_name = C('config.score_name');
                switch($order_info['order_type']){
                    case 'store':
                    case 'cash':
                        $score_desc =  '在' . $now_store['name'] . ' 中使用'.C('config.cash_alias_name').'支付了' . floatval($order_info['total_money']) . '元 获得'.$score_name;
                        break;
                    case 'group':
                        $score_desc =  '购买 ' . $order_info['order_name'] . ' 消费' . floatval($order_info['total_money']) . '元 获得'.$score_name;
                        break;
                    case 'shop':
                        $score_desc = '在 ' . $now_store['name'] . ' 中消费' . floatval($order_info['total_money']) . '元 获得'.$score_name;
                        break;
                    case 'meal':
                        $score_desc ='购买'.C('config.meal_alias_name').'商品获得'.$score_name;
                        break;
                    case 'shop_offline':
                        $score_desc = '在 ' . $now_store['name'] . ' 中消费' . floatval($order_info['total_money']) . '元 获得'.$score_name;
                        break;
                    case 'appoint':
                        $score_desc = '购买'.C('config.appoint_alias_name').'商品获得'.$score_name;
                        break;
                    case 'wxapp':
                        $score_desc = '购买 '.$order_info['order_id'].' 消费'.floatval($order_info['money']).'元 获得'.$score_name;
                        break;
                    case 'weidian':
                        $score_desc = '购买 '.$order_info['order_id'].' 消费'.floatval($order_info['money']).'元 获得'.$score_name;
                        break;
                }
                D('User')->add_score($order_info['uid'], round( $date['system_take']),$score_desc);
                $now_user  = D('User')->get_user($order_info['uid']);
                D('Scroll_msg')->add_msg($order_info['type'],$order_info['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买 ' . C('config.'.$order_info['order_type'].'_alias_name'). '产品成功并消费获得'.$score_name);
            }

            if($order_info['uid']){
                $spread_condition['order_type'] =$order_info['order_type'] ;
                $spread_condition['order_id'] =$order_info['order_id'] ;
                $now_spread = D('User_spread_list')->where($spread_condition)->select();
          
                foreach($now_spread as $v){
                    if(C('config.open_distributor')>0){
                        $res= D('Distributor_agent')->get_effective($v['uid'],1);
                        if($res['error_code']){
                            break;
                        }
                    }
                    $this->spread_check($v['uid'],$v['pigcms_id']);
                }
            }
            return array('error_code'=>false,'msg'=>$desc.' ，保存商家收入成功！');
        }
    }

    public function merchant_card($mer_id,$order_info){
        if($card = D('Card_new')->get_card_by_mer_id($mer_id)){
            $uid = $order_info['uid'];
            $card_info = D('Card_new')->get_card_by_uid_and_mer_id($uid,$mer_id);
            if(empty($card_info)&&$card['auto_get_buy']){

                $res = D('Card_new')->auto_get($uid,$mer_id);
            }
            if($card_info['weixin_send'] && $order_info['pay_type']=='weixin' && $order_info['payment_money'] >= $card_info['weixin_send_money'] ){
                //购买自动派券功能（派发的都是用户可以领取的功能）
                $coupon_list = explode(',',$card_info['weixin_send_couponlist']);

                $model = D('Card_new_coupon');
                foreach ($coupon_list as $item) {
                    $tmp = $model->had_pull($item,$uid);
                    switch($tmp['error_code']) {
                        case '0':
                            $error_msg = '领取成功';
                            break;
                        case '1':
                            $error_msg = '领取发生错误';
                            break;
                        case '2':
                            $error_msg = '优惠券已过期';
                            break;
                        case '3':
                            $error_msg = '优惠券已经领完了';
                            break;
                        case '4':
                            $error_msg = '只允许新用户领取';
                            break;
                        case '5':
                            $error_msg = '不能再领取了';
                            break;
                    }
                    $tmp['msg'] ='微信购买派发，'.$error_msg;
                    $data['uid'] = $uid;
                    $data['mer_id']  = $mer_id;
                    $data['coupon_id'] = $item;
                    $data['error_code']  =$tmp['error_code'];
                    $data['msg']  =$tmp['msg'];
                    $data['add_time']  =time();
                    M('Card_coupon_send_history')->add($data);
                }
            }

            if(!empty($card_info)){
                $data_score['card_id'] = $card_info['id'] ;
                $data_score['type'] = 1;
                if($order_info['order_type']=='group' && !$order_info['verify_all'] ){
                    $data_score['score_add'] = round($card_info['support_score']*($order_info['total_money']+$order_info['balance_pay']+$order_info['merchant_balance']+$order_info['card_give_money']));
                }else{
                    $data_score['score_add'] = round($card_info['support_score']*($order_info['payment_money']+$order_info['balance_pay']+$order_info['merchant_balance']+$order_info['card_give_money']));
                }
                if( $data_score['score_add']>0){
                    $data_score['desc'] = '消费获得会员卡积分';
                    $res = D('Card_new')->add_user_money($mer_id,$uid,0,0,$data_score['score_add'],'',$data_score['desc']);
                }
            }
        }

    }

    protected  function get_alias_c_name(){
        return array(
            'all'=>'选择分类',
            'group'=>C('config.group_alias_name'),
            'shop'=>C('config.shop_alias_name'),
            'meal'=>C('config.meal_alias_name'),
            'appoint'=>C('config.appoint_alias_name'),
            'waimai'=>'外卖',
            'store'=>C('config.cash_alias_name'),
            'cash'=>'到店支付',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现',
            'coupon'=>'优惠券',
            'withdraw'=>'提现',
            'activity'=>'平台活动',
            'spread'=>'商家推广',
            'sub_card'=>'免单套餐'
        );
    }

    //减少余额
    public function use_money($mer_id,$money,$type,$desc,$order_id,$percent=0,$system_take = 0,$mer_user=array()){
        $date['mer_id']=$mer_id;
        $date['income'] = 2;
        $date['order_id'] = $order_id;
        if($percent){
            $date['percent'] = $percent;
            $date['system_take'] = $system_take;
        }
        $date['use_time']= time();
        $date['type']= $type;
        $date['desc']=  $desc;
        $date['money']=  $money;
        if(!M('Merchant')->where(array('mer_id'=>$mer_id))->setDec('money', $date['money'])){
            return array('error_code'=>true,'msg'=>$desc.'，使用失败！');
        }
        $now_mer_money = M('Merchant')->field('money,mch_owe_money')->where(array('mer_id'=> $mer_id))->find();
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        $mer_status = 1;
        if(C('config.open_mer_owe_money')==0){
            $now_mer_money['mch_owe_money'] = 0;
        }

        if($now_mer_money['money']<$now_mer_money['mch_owe_money']*(-1)){
            M('Merchant')->where(array('mer_id'=>$mer_id))->setField('status',3);
            $mer_status = 3;
            $this->merchant_owe_money_notice($mer_id);
        }else  if($now_mer_money['money']<0){
            $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_mer_money['openid'], 'first' => $desc.'，当前商家余额:'.$now_mer_money['money'].',您的商家状态为欠费，您的商家业务状态为禁止状态，请及时充值' , 'keyword1' => '商家余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到商家中心商家余额中查看'), $mer_id);

        }
        if(!empty($mer_user['openid'])){
            if($mer_status==3){
                $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_mer_money['openid'], 'first' => $desc.'，当前商家余额:'.$now_mer_money['money'].',您的商家状态为欠费，您的商家业务状态为禁止状态，请及时充值' , 'keyword1' => '商家余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到商家中心商家余额中查看'), $mer_id);
            }else{
                $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_mer_money['openid'], 'first' => $desc.'，当前商家余额:'.$now_mer_money['money'] , 'keyword1' => '商家余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到商家中心商家余额中查看'), $mer_id);

            }
        }

        $date['now_mer_money'] = $now_mer_money['money'];
        if(!$this->add($date)){
            return array('error_code'=>true,'msg'=>$desc.'，保存失败！');
        }else{
            return array('error_code'=>false,'msg'=>$desc.'，保存成功！');
        }
    }

    //提现
    public function withdraw($mer_id,$name,$money,$remark){
        $date['mer_id']=$mer_id;
        $withdraw_money = $money;

        if (C('config.company_pay_mer_percent') > 0 && C('config.merchant_withdraw_fee_type')==0) {
            $tmp_money  = $money;
            $withdraw_money = floor ($tmp_money * (100-C('config.company_pay_mer_percent'))/100);
            //$date['percent'] = C('config.company_pay_mer_percent');
            $system_take = floor($tmp_money * (C('config.company_pay_mer_percent'))/100)/100;
        }else if(C('config.merchant_withdraw_fee_type')==1&& C('config.company_pay_mer_money')>0){
           // $data_companypay['desc'] .= '|手续费 '.$this->config['company_pay_mer_money'].'元';

            $tmp_money  = $money;
            $withdraw_money = $tmp_money-C('config.company_pay_mer_money')*100;
            //$date['percent'] = C('config.company_pay_mer_percent');
            $system_take = C('config.company_pay_mer_money');

        }
        if($withdraw_money<=0){
            return array('error_code'=>true,'msg'=>'您的提现金额不足以抵扣提现手续费，不能提现');
        }
        $date['name']=$name;
        $date['money']=  $withdraw_money;
        $date['old_money']=  $money;
        $date['remark']=  empty($remark)?"":$remark;
        $date['withdraw_time'] = time();
        $res =M('Merchant_withdraw')->add($date);
        $this->withdraw_notice($res);
        if(!$res){
            return array('error_code'=>true,'msg'=>'保存失败！');
        }else{
            //考虑兑现后减值
            $this->use_money($mer_id,$money/100,'withdraw','商户提现减少金额',$res,C('config.company_pay_mer_percent'),$system_take);
            return array('error_code'=>false,'msg'=>'保存成功！');
        }
    }

    //拒绝提现 增加余额
    public function reject($mer_id,$withdraw_id,$reason){
        $res = M('Merchant_withdraw')->where(array('id'=>$withdraw_id,'mer_id'=>$mer_id))->find();
        $date['status'] = 2;
        $date['remark'] = $reason;
        M('Merchant_withdraw')->where(array('id'=>$withdraw_id,'mer_id'=>$mer_id))->save($date);
        $desc = '驳回商户提现增加金额';
        $order_info['money'] = $res['old_money']/100;
        $order_info['order_type'] = 'withdraw';
        $order_info['mer_id'] = $mer_id;
        $order_info['order_id'] = $withdraw_id;
        $order_info['desc'] = $desc;
        $this->withdraw_status_notice($withdraw_id);
        if($res['status']==4){
            M('Withdraw_list')->where(array('withdraw_id'=>$withdraw_id,'type'=>'mer','pay_id'=>$mer_id))->setField('status',4);
        }
        return $this->add_money($order_info);
    }

    //同意提现改变状态
    public function agree($mer_id,$money,$withdraw_id,$remark,$is_online=false){
        $date['status'] = 1;
        $date['remark'] = $remark;
        $date['online'] = $is_online;
        $date['money'] = $money;
        $res = M('Merchant_withdraw')->where(array('id'=>$withdraw_id,'mer_id'=>$mer_id))->find();
        if($res['status']==4){
            M('Withdraw_list')->where(array('withdraw_id'=>$withdraw_id,'type'=>'mer','pay_id'=>$mer_id))->setField('status',1);
        }
        $res = M('Merchant_withdraw')->where(array('id'=>$withdraw_id,'mer_id'=>$mer_id))->save($date);
        $this->withdraw_status_notice($withdraw_id);
        return $res;
    }

    //统计所有商家余额
    public function  get_all_mer_money($where){
        if(!empty($where)){
            return M('Merchant')->where($where)->sum('money');
        }
        return M('Merchant')->sum('money');
    }

    //获取提现列表
    public function get_withdraw_list($mer_id,$is_system = 0,$status=3,$time=''){
        if($is_system){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }
        $where['mer_id']= $mer_id;
        if($status!=3){
            $where['status']= $status;
        }
        if($status==0){
            $where['status'] = array('in','0,4');
        }
        if(!empty($time)){
            $where['_string'] = $time;
        }

        $count = M('Merchant_withdraw')->where($where)->count();
        $p = new Page($count, 20);
        $pagebar=$p->show();
        if($_GET['page']>$p->totalPage){
            return array('withdraw_list'=>array(),'page_num'=>$p->totalPage);
        }else{
            return array('withdraw_list'=>M('Merchant_withdraw')->where($where)->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select(),'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
        }

    }

    //获取商家收入列表
    public function get_income_list($mer_id,$is_system = 0,$where){
        if($_GET['page']){
            $where = $_SESSION['condition'];
        }

        if($is_system){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }
        unset($where['mer_id']);
        $where['l.mer_id']=$mer_id;
        if($where['store_id']){
            $where['l.store_id'] = $where['store_id'];
            unset($where['store_id']);
        }
        $count = $income_count= M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id ')->where($where)->count();
        $p = new Page($count, 20);
        $pagebar=$p->show();
        if($_GET['page']>$p->totalPage){
            return array('income_list'=>array(),'total'=>0,'page_num'=>$p->totalPage);
        }else {
            $income_list = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id ')->field('ms.name as store_name,l.order_id,l.desc,l.use_time,l.num,l.money,l.type,l.id,l.income,l.now_mer_money,l.system_take,l.percent,l.store_id,l.score,l.score_count')->where($where)->order('use_time DESC')->limit($p->firstRow, $p->listRows)->select();
            $income_sum= M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id ')->where($where)->sum('l.money');

            $total_where = array_merge($where,array('l.mer_id'=>$mer_id,'l.income'=>1));
            $total_income = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id ')->where($total_where)->sum('l.money');
            $total_money_where = array_merge($where,array('l.mer_id'=>$mer_id));
            $total_money = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id ')->where($total_money_where)->sum('pow(-1,l.income+1)*l.money');
            $total_money  = floatval(key($total_money));

            $income_total_where = array_merge($where,array('l.mer_id'=>$mer_id,'l.income'=>1,'l.type'=>array('neq','merrecharge')));
            $income_total = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id ')->where($income_total_where)->sum('l.money');
            $income_total_where_ = array_merge($where,array('l.mer_id'=>$mer_id,'l.type'=>array('neq','withdraw')));
            $income_total_ = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id ')->where($income_total_where_)->sum('l.money');
            $recharge_total = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id ')->where(array('l.mer_id'=>$mer_id,'l.income'=>1,'l.type'=>'merrecharge'))->sum('l.money');
            $total_score = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id ')->where($where)->order('use_time DESC')->sum('l.score');
            return array('income_list' => $income_list,
                'total'=>empty($total_income)?0:$total_income,
                'income_total'=>empty($income_total)?0:$income_total,
                'income_total_'=>empty($income_total_)?0:$income_total_,
                'total_money'=>empty($total_money)?0:$total_money,
                'total_score'=>empty($total_score)?0:$total_score,
                'recharge_total'=>empty($recharge_total)?0:$recharge_total,
                'income_count' => $count,
                'income_sum' => $income_sum,
                'pagebar' => $pagebar,
                'page_num' => $p->totalPage);
        }
    }

    //获取所有商家提现列表 有提现的向前排列
    public function get_mer_withdraw_list($condition_merchant,$page_count=15){
        $database_merchant = M('Merchant');
        import('@.ORG.system_page');

        // $count_merchant =M('Merchant_withdraw')->where(array('status'=>array('in','0,4')))->count();
        $condition_merchant['w.status'] = array('in','0,4');
        $count_merchant =$database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status  FROM pigcms_merchant_withdraw WHERE status in (0,4)  GROUP BY mer_id) w ON m.mer_id = w.mer_id ')
            ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money ')
            ->where($condition_merchant)->count();
        $p = new Page($count_merchant,$page_count);
        $mer_withdraw_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status  FROM pigcms_merchant_withdraw WHERE status in (0,4)  GROUP BY mer_id) w ON m.mer_id = w.mer_id ')
            ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money ')
            ->where($condition_merchant)
            ->order('m.money DESC')
            ->group('m.mer_id')
            ->limit($p->firstRow.','.$p->listRows)
            ->select();

        $pagebar = $p->show();
        return array('mer_withdraw_list'=>$mer_withdraw_list,'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
    }

    public function get_money_list($condition_merchant,$page_count=15){
        $database_merchant = M('Merchant');
        import('@.ORG.system_page');
       // if(isset($condition_merchant)){

            if($condition_merchant['province_id']){
                $province_id = $condition_merchant['province_id'];
                $where['a.area_pid'] = $condition_merchant['province_id'];
                unset($condition_merchant['province_id']);
                $where = array_merge($where,$condition_merchant);
                $count_merchant =M("Merchant" )->join("as m left join ".C('DB_PREFIX').'area as a ON m.city_id = a.area_id')->where($where)->count();
            }else{
                $count_merchant =M("Merchant as m" )->where($condition_merchant)->count();
            }

            $p = new Page($count_merchant,$page_count);
            if($province_id){
                $mer_withdraw_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM pigcms_merchant_withdraw WHERE status in (0,4)  GROUP BY mer_id) w ON m.mer_id = w.mer_id left join '.C('DB_PREFIX').'area as a on a.area_id = m.city_id')
                    ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money ')
                    ->where($where)
                    ->order('m.money DESC')
                    ->group('m.mer_id')
                    ->limit($p->firstRow.','.$p->listRows)
                    ->select();
            }else{
                $mer_withdraw_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM pigcms_merchant_withdraw WHERE status in (0,4)  GROUP BY mer_id) w ON m.mer_id = w.mer_id ')
                    ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money ')
                    ->where($condition_merchant)
                    ->order('m.money DESC')
                    ->group('m.mer_id')
                    ->limit($p->firstRow.','.$p->listRows)
                    ->select();
            }

        //}else{
        //
        //    $count_merchant = $database_merchant->where($condition_merchant)->count();
        //    $p = new Page($count_merchant,$page_count);
        //    $mer_withdraw_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM pigcms_merchant_withdraw WHERE status =0  GROUP BY mer_id) w ON m.mer_id = w.mer_id ')
        //        ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money')
        //        ->where($condition_merchant)
        //        ->order('m.money DESC')
        //        ->limit($p->firstRow.','.$p->listRows)
        //        ->select();
        //}
        $pagebar = $p->show();
        return array('mer_withdraw_list'=>$mer_withdraw_list,'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
    }


    //抽成列表
    public function get_mer_percentage_list($condition_merchant,$page_count=15){
        $database_merchant = M('Merchant');
        $condition_merchant['province_id'] && $where['province_id'] = $condition_merchant['province_id'];
        $condition_merchant['city_id'] && $where['city_id'] = $condition_merchant['city_id'];
        $condition_merchant['area_id'] && $where['area_id'] = $condition_merchant['area_id'];

        $count_merchant = $database_merchant->where($where)->count();
        $time_condition = '';
        foreach($condition_merchant as $k=>$v){
            if($k!='_string'){
                $condition_merchant['m.'.$k] = $v;
            }else{
                $time_condition = 'WHERE '.$condition_merchant[$k];
            }
            unset($condition_merchant[$k]);
        }
        $extra_str ='';
        $extra_field ='';
        if(C('config.open_extra_price')==1){
            $tmp_time_condition  =  str_replace('use_time','add_time', $time_condition);
            $extra_str= 'LEFT JOIN (SELECT mer_id,SUM(score_count) as send_count FROM  '.C('DB_PREFIX').'merchant_score_send_log '.$tmp_time_condition.'  group by mer_id ) sl ON sl.mer_id = m.mer_id ';
            $extra_field = ' ,sl.send_count';
        }

        import('@.ORG.system_page');
        $p = new Page($count_merchant,$page_count);
        $mer_percentage_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(system_take) AS  money ,SUM(score) as all_score FROM '.C('DB_PREFIX').'merchant_money_list '.$time_condition.' GROUP BY mer_id) w ON m.mer_id = w.mer_id  '.$extra_str)
            ->field('m.mer_id,m.money as now_money,m.name,m.phone,w.money,w.all_score'.$extra_field)
            ->where($condition_merchant)
            ->order('w.money DESC,w.all_score DESC')
            ->limit($p->firstRow.','.$p->listRows)
            ->select();

        $pagebar = $p->show();
        return array('mer_percentage_list'=>$mer_percentage_list,'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
    }

    //统计所有商家余额
    public function  get_all_percent_money($condition_merchant){
        foreach($condition_merchant as $k=>$v){
            if($k!='_string'){
                $condition_merchant['m.'.$k] = $v;
                unset($condition_merchant[$k]);
            }
        }
        return M('Merchant_money_list')->join('AS l left join '.C('DB_PREFIX') .'merchant as m ON m.mer_id = l.mer_id')->where($condition_merchant)->sum('l.system_take');
    }

    //统计所有送出的积分
    public function  get_all_score($condition_merchant){
        $all_socre=  0;
        if(C('config.open_extra_price')==1){
            $tmp_condition = $condition_merchant;
            foreach($tmp_condition as $k=>$v){
                if($k!='_string'){
                    $tmp_condition['m.'.$k] = $v;
                    unset($tmp_condition[$k]);
                }else{
                    $tmp_condition[$k] = str_replace('use_time','add_time', $tmp_condition[$k]);
                }
            }
            $all_socre = M('Merchant')->join('as m LEFT JOIN '.C('DB_PREFIX').'merchant_score_send_log sl ON sl.mer_id = m.mer_id')->where($tmp_condition)->sum('score_count');
        }
        foreach($condition_merchant as $k=>$v){
            if($k!='_string'){
                $condition_merchant['m.'.$k] = $v;
                unset($condition_merchant[$k]);
            }
        }
        $all_socre += M('Merchant_money_list')->join('AS l left join '.C('DB_PREFIX') .'merchant as m ON m.mer_id = l.mer_id')->where($condition_merchant)->sum('l.score');
        return $all_socre;
    }

    public  function get_system_income($condition){
        $condition['income']=1;
        $condition['type']=array('neq','merrecharge');

        $income_total = M('Merchant_money_list')
            ->where($condition)
            ->sum('system_take');

        return $income_total;
    }


    public function spread_check($uid,$id){
        if(C('config.open_extra_price')==1){
            $money_name = C('config.extra_price_alias_name');
        }else{
            $money_name = '佣金';
        }
        $where = array(
            'pigcms_id'=>$id,
            '_string'=>'uid='.$uid.' OR change_uid='.$uid
        );

        $now_spread = D('User_spread_list')->where($where)->find();
        //dump($now_spread);
        if($now_spread && $now_spread['status'] == 0){
            if($now_spread['order_type'] == 'group'){
                $order_info = D('Group_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();
                if($order_info['status'] == '1' || $order_info['status'] == '2'){
                    if(D('User_spread_list')->where(array('pigcms_id'=>$id))->data(array('status'=>1))->save()){
                        //D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.C('config.group_alias_name').'商品获得佣金');
                        if($now_spread['change_uid']!=0){
                            D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.C('config.group_alias_name').'商品获得'.$money_name.'('.$money_name.'过户)');

                        }else{
                            if(C('config.open_extra_price')==1){
                                D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.C('config.group_alias_name').'商品获得'.$money_name);
                            }else{
                                D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.C('config.group_alias_name').'商品获得'.$money_name);

                                D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
                            }
                        }
                        return true;
                    }else{
                        return false;
                    }
                }else if($order_info['status'] == '3' || $order_info['status'] == '6'){
                    if(D('User_spread_list')->where(array('pigcms_id'=>$id))->data(array('status'=>2))->save()){
                        return true;
                    }else{
                        return false;
                    }
                }
            }else if ($now_spread['order_type']=='shop'){
                $order_info = D('Shop_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();
                if($order_info['status'] == '1' || $order_info['status'] == '2' || $order_info['status'] == '3'){
                    if(D('User_spread_list')->where(array('pigcms_id'=>$id))->data(array('status'=>1))->save()){
                        //D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.C('config.shop_alias_name').'商品获得佣金');

                        if($now_spread['change_uid']!=0){
                            D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.C('config.shop_alias_name').'商品获得'.$money_name.'('.$money_name.'过户)');
                        }else{
                            if(C('config.open_extra_price')==1){
                                D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.C('config.shop_alias_name').'商品获得'.$money_name);
                            }else{
                                D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.C('config.shop_alias_name').'商品获得'.$money_name);
                                D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
                            }
                        }
                        return true;
                    }else{
                        return false;
                    }
                }else if($order_info['status'] == '4'||$order_info['status'] == '5'){
                    if(D('User_spread_list')->where(array('pigcms_id'=>$id))->data(array('status'=>2))->save()){
                        return true;
                    }else{
                        return false;
                    }
                }
            }else if ($now_spread['order_type']=='store'||$now_spread['order_type']=='cash'){
                $order_info = D('Store_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

                $alia_name = array('store'=>'优惠买单','cash'=>'到店付');
                if(D('User_spread_list')->where(array('pigcms_id'=>$id))->data(array('status'=>1))->save()){
                    if($now_spread['change_uid']!=0){
                        D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name.'('.$money_name.'过户)');
                    }else{
                        if(C('config.open_extra_price')==1){
                            D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name);
                        }else{

                            D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name);
                            D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
                        }
                    }
                    return true;
                }else{
                    return false;
                }

            }else if ($now_spread['order_type']=='meal'){
                $order_info = D('Foodshop_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

                $alia_name = array('store'=>C('config.meal_alias_name'));
                if(D('User_spread_list')->where(array('pigcms_id'=>$id))->data(array('status'=>1))->save()){
                    //D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买餐饮商品获得佣金');
                    if($now_spread['change_uid']!=0){
                        D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买餐饮商品获得'.$money_name.'('.$money_name.'过户)');
                    }else{
                        if(C('config.open_extra_price')==1){
                            D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.C('config.meal_alias_name').'商品获得'.$money_name);
                        }else{
                            D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.C('config.meal_alias_name').'商品获得'.$money_name);
                            D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
                        }
                    }
                    return true;
                }else{
                    return false;
                }

            }else if ($now_spread['order_type']=='sub_card'){
                $order_info = D('Sub_card_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

                $alia_name = array('sub_card'=>'次卡套餐');
                if(D('User_spread_list')->where(array('pigcms_id'=>$id))->data(array('status'=>1))->save()){
                    //D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买餐饮商品获得佣金');
                    if($now_spread['change_uid']!=0){
                        D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买免单套餐获得'.$money_name.'('.$money_name.'过户)');
                    }else{

                        D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买免单套餐商品获得'.$money_name);
                        D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');

                    }
                    return true;
                }else{
                    return false;
                }

            }else if ($now_spread['order_type']=='yuedan'){
                $order_info = D('Yuedan_service_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();
                $alia_name = array('yuedan'=>'约单');
                if(D('User_spread_list')->where(array('pigcms_id'=>$id))->data(array('status'=>1))->save()){
                    //D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买餐饮商品获得佣金');
                    if($now_spread['change_uid']!=0){
                        D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买约单获得'.$money_name.'('.$money_name.'过户)');
                    }else{

                        D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买约单获得'.$money_name);
                        D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');

                    }
                    return true;
                }else{
                    return false;
                }

            }

        }
    }

    public function merchant_owe_money_notice($mer_id){
        $where['mer_id'] = $mer_id;
        $merchant = M('Merchant')->field('area_id,city_id ,province_id')->where($where)->find();

        $where_admin['openid']=array('neq','');
        $where_admin['withdraw_notice']=1;
        $admin_list = M('Admin')->where($where_admin)->select();
        $tmp_area = array(
            $merchant['area_id'],$merchant['city_id'],$merchant['province_id']
        );
        foreach ($admin_list as $v) {
            if($v['level']==2 || $v['level']==0){
                $send_to[] = $v;
            }else if(in_array($v['area_id'],$tmp_area)){
                $send_to[] = $v;
            }
        }
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        foreach ($send_to as $s) {
            $href ='';
            $model->sendTempMsg('OPENTM401300510', array('href' => $href,
                'wecha_id' => $s['openid'],
                'first' => '欠款通知',
                'keyword1' =>"商户【{$merchant['name']}】",
                'keyword2' => '欠款'.$merchant['money'],
                'remark' =>'时间'.date('Y-m-d H:i',time()).',您的帐户余额已欠费，请尽快充值'),
            0);
        }
    }

    public function withdraw_notice($withdraw_id = 0){
        $where['w.id'] = $withdraw_id;
        $withdraw = M('Merchant_withdraw')->field('w.*,m.area_id,m.city_id ,m.province_id,m.name as withdraw_name')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id = w.mer_id ')->where($where)->find();

        $where_admin['openid']=array('neq','');
        $where_admin['withdraw_notice']=1;
        $admin_list = M('Admin')->where($where_admin)->select();
        $tmp_area = array(
            $withdraw['area_id'],$withdraw['city_id'],$withdraw['province_id']
        );
        foreach ($admin_list as $v) {
            if($v['level']==2 || $v['level']==0){
                $send_to[] = $v;
            }else if(in_array($v['area_id'],$tmp_area)){
                $send_to[] = $v;
            }
        }

        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        foreach ($send_to as $s) {
            $href ='';
            $model->sendTempMsg('OPENTM412319702', array('href' => $href,
                'wecha_id' => $s['openid'],
                'first' => '有一笔商户提现待审批，请尽快审核！',
                'keyword1' =>$withdraw['withdraw_name'],
                'keyword2' => date('Y-m-d H:i',$withdraw['withdraw_time']),
                'keyword3' => '￥'.floatval($withdraw['money']/100),
                'remark' =>'请尽快登录后台审核'),
                0);
        }
    }

    public function withdraw_status_notice($withdraw_id = 0,$is_user=0,$is_wexin=false){

        $withdraw_type  = array(
            '0' => '银行卡',
            '1' => '支付宝',
            '2' => '微信',
        );
        $withdraw_status =array(
            0=>'未审核',
            1=>'审核通过',
            2=>'驳回',
            3=>'',
            4=>'审核通过',
        );
        $where['w.id'] = $withdraw_id;
        if($is_user){
            if($is_wexin){
                $withdraw =  M('Companypay')->join('as w LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = w.pay_id')->field('w.*,u.nickname as withdraw_name')->where(array('pigcms_id'=>$withdraw_id))->find();
                if($withdraw['status']==0){
                    $withdraw['status_txt'] = '审核不通过';
                }elseif($withdraw['status']==2){
                    $withdraw['status_txt'] = '恢复审核';
                }
                $withdraw['pay_type']=2;
            }else{

                $withdraw = M('Withdraw_list')->field('w.*,u.openid,u.nickname as withdraw_name')->join('as w LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = w.pay_id')->where($where)->find();
                if($withdraw['status']==2){
                    $withdraw['status_txt'] = '审核不通过';
                }elseif($withdraw['status']==0){
                    $withdraw['status_txt'] = '恢复审核';
                }else{
                    $withdraw['status_txt'] = '审核通过';
                }
            }

        }else{
            $withdraw = M('Merchant_withdraw')->field('w.*,u.openid,m.name as withdraw_name')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id = w.mer_id LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = m.uid')->where($where)->find();

            $where_withdraw['withdraw_id'] = $withdraw['id'];
            $where_withdraw['mer_id'] = $withdraw['mer_id'];
            $withdraw_by_hand = M('Withdraw_list')->where($where_withdraw)->find();
            if(empty($withdraw_by_hand)){
                $withdraw['pay_type'] = 2;
            }else{
                $withdraw['pay_type'] = $withdraw_by_hand['pay_type'];
            }
            if($withdraw['status']==2 ){
                $withdraw['status_txt'] = $withdraw_status[$withdraw['status']].'(原因:'.$withdraw['remark'].')';
            }else{
                $withdraw['status_txt'] = $withdraw_status[$withdraw['status']];
            }

        }


        if($withdraw['openid']){
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href =$is_user?C('config.site_url').'/wap.php?c=My&a=transaction':C('config.site_url').'/packapp/merchant/apply_record.html';
            $model->sendTempMsg('OPENTM202425107', array('href' => $href,
                'wecha_id' => $withdraw['openid'],
                'first' => $withdraw['withdraw_name'].'，您好。您的提现申请已处理。',
                'keyword1' =>'￥'.floatval($withdraw['money']/100),
                'keyword2' =>'提现至'.$withdraw_type[$withdraw['pay_type']],
                'keyword3' =>date('Y-m-d H:i',$is_user?$withdraw['add_time']:$withdraw['withdraw_time']),
                'keyword4' => $withdraw['status_txt'],
                'keyword5' =>date('Y-m-d H:i'),

                'remark' =>'点击查看详情'),
                0);
        }


    }

    public function get_system_take_by_condition($condition){
        if($condition){
            $where = $condition;
        }

        return $this->where($where)->sum('system_take');
    }
}