<?php
// 店铺余额
class Store_money_listModel extends Model{
    //增加余额
    public function add_money($order_info){
        //店铺绑定用户
        //$mer_user = D('Merchant')->get_merchant_user($store_id);
            $store_id = $order_info['store_id'];
            $desc = $order_info['desc'];

            $date['store_id'] = $order_info['store_id'];

        //自有支付
        if(isset($order_info['is_own'])&&$order_info['is_own']>0){
            //$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $alias_name = $this->get_alias_c_name($order_info['order_type']);
            $store_name = '无';
            if (!empty($now_store)) {
                $store_name = $now_store['name'];
            }
            if($order_info['is_own']==2){
                $remark = '请到子商户平台中查看';
            }else{
                $remark = '请到店铺平台中查看';
            }
            //$model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $mer_user['openid'], 'first' => '收款成功,' , 'keyword1' => $alias_name[$order_info['order_type']], 'keyword2' =>$order_info['payment_money'],'keyword3' =>$store_name, 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' =>$remark), $store_id);
            if(C('config.open_store_own_percent')==1){
                $own_percent_money = $order_info['payment_money'];
            }
            $order_info['payment_money']=0;
        }

        switch($order_info['order_type']){
            case 'group':
                if(!empty($order_info['refund'])){
                    $num =1;
                    $money=$order_info['refund_money'];
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
                }else{
                    $num =$order_info['num'];
                    $money = ($order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['merchant_balance'])*100/100;
                }

                $order_info['total_money'] = $order_info['total_money'];
                break;
            case 'meal':
                $num =$order_info['total'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['merchant_balance'];
                $order_info['total_money'] = $order_info['total_price'];
                break;
            case 'shop':
                $num =$order_info['num'];
                if($order_info['is_pick_in_store']==0&&($order_info['card_give_money']>0||$order_info['card_price']>0)&&$order_info['order_from']==0){//平台配送 （使用了店铺赠送余额或店铺优惠券）
                    $pay_for_system = $order_info['freight_charge'];
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']-$order_info['no_bill_money']+$order_info['merchant_balance'];

                }else if($order_info['is_pick_in_store']==0&&$order_info['order_from']==1){//平台配送 （使用了店铺赠送余额或店铺优惠券）
                    $pay_for_system = $order_info['no_bill_money'];
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']+$order_info['merchant_balance']-$order_info['freight_charge'];

                }else{
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']-$order_info['no_bill_money']+$order_info['merchant_balance'];
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
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
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
            case 'strecharge':
                $num =1;
                $money = $order_info['pay_money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            default:
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
                break;
        }
        if($order_info['order_type']=='group'){
            $percent = D('Percent_rate')->get_percent($store_id,$order_info['order_type'],$money,$order_info['group_id'],$store_id);
        }else{
            $percent = D('Percent_rate')->get_percent($store_id,$order_info['order_type'],$money,'',$store_id);
        }

        if(C('config.open_store_own_percent') && $own_percent_money>0){
            $desc_pay_for_system='对店铺自有支付抽成，在线自有支付支付金额：'.$own_percent_money.',扣除店铺余额：'.sprintf("%.2f",$own_percent_money*$percent/100);
            $result_pay = $this->use_money($store_id,sprintf("%.2f",$own_percent_money*$percent/100),$order_info['order_type'],$desc_pay_for_system,$order_info['real_orderid'],$percent,sprintf("%.2f",$own_percent_money*$percent/100));

        }


        $date['num'] = $num>0?$num:1;
        $date['money'] = $money;
        $date['type']=$order_info['order_type'];
        if($date['type']=='group'||$date['type']=='shop') {
            $date['order_id'] = $order_info['real_orderid'];
        }else{
            $date['order_id'] = $order_info['order_id'];
        }

        if($order_info['order_type']!='withdraw' && $order_info['order_type']!='spread' && $order_info['order_type']!='strecharge'){
            $date['total_money']= $order_info['total_money'];
            $date['system_take']= ($money*$percent/100);
            $date['money']= sprintf("%.2f",$money*(100-$percent)/100);

            $date['percent'] = $percent;
        }else{
            $date['percent'] = 0;
        }

        $date['income'] = 1;
        $date['use_time']= time();
        $date['desc']=  empty($desc)?'':$desc;

        if(  !M('Merchant_store')->where(array('store_id'=>$store_id))->setInc('money', $date['money'])  ){
            return array('error_code'=>true,'msg'=>'增加店铺余额失败');
        }elseif($order_info['order_type']=='group'||$order_info['order_type']=='meal'||$order_info['order_type']=='shop'||$order_info['order_type']=='appoint'||$order_info['order_type']=='store'||$order_info['order_type']=='cash'||$order_info['order_type']=='wxapp'||$order_info['order_type']=='weidian'){
            if($order_info['order_type']=='cash'){
                $date['type'] = 'store';
            }
            M(ucfirst($date['type']) . '_order')->where(array('order_id' => $order_info['order_id']))->setField('is_pay_bill', 2);
            $date['type'] = $order_info['order_type'];
        }
        $now_store_money = M('Merchant_store')->where(array('store_id'=> $store_id))->find();
        $date['now_store_money'] = $now_store_money['money'];

        if(!$this->add($date)){
            return array('error_code'=>true,'msg'=>$desc.' ，保存店铺收入失败！');
        }else{
            //if($order_info['order_type'] != 'withdraw'&& !empty($mer_user) && $mer_user['open_money_tempnews']) {
            //    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            //    $alias_name = $this->get_alias_c_name($order_info['order_type']);
            //    $store_name = '无';
            //    if (!empty($now_store)) {
            //        $store_name = $now_store['name'];
            //    }
            //    $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $mer_user['openid'], 'first' => '收款成功，当前店铺余额:'.$now_store_money['money'] , 'keyword1' => $alias_name[$order_info['order_type']], 'keyword2' =>$money,'keyword3' =>$store_name, 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到店铺中心店铺余额中查看'), $store_id);
            //}
            if($order_info['order_type']!='strecharge'){
                $this->merchant_card($now_store_money['mer_id'],$order_info);
            }

            if($pay_for_system!=0){
                if($order_info['order_from']==1){
                    $desc_pay_for_system='商城订单快递配送转为' . C('config.deliver_name') . '，系统扣除' . C('config.deliver_name') . '费';
                }else{
                    $desc_pay_for_system= '快店' . C('config.deliver_name') . '，平台从店铺余额中扣除订单的配送费';
                }
                $this->use_money($store_id,$pay_for_system,$order_info['order_type'],$desc_pay_for_system,$order_info['real_orderid']);
            }
            return array('error_code'=>false,'msg'=>$desc.' ，保存店铺收入成功！');
        }
    }

    public function merchant_card($mer_id,$order_info){
        if($card = D('Card_new')->get_card_by_mer_id($mer_id)){
            $uid = $order_info['uid'];
            $card_info = D('Card_new')->get_card_by_uid_and_mer_id($uid,$mer_id);
            if(empty($card_info)&&$card['auto_get_buy']){
                $res = D('Card_new')->auto_get($uid,$mer_id);
            }
            if($card_info['weixin_send'] && $order_info['pay_type']=='weixin' && $order_info['pay_money'] > $card_info['weixin_send_money'] ){
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
                $data_score['score_add'] = round($card_info['support_score']*($order_info['payment_money']+$order_info['balance_pay']+$order_info['merchant_balance']+$order_info['card_give_money']));
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
            'store'=>'优惠买单',
            'cash'=>'到店支付',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现',
            'coupon'=>'优惠券',
            'withdraw'=>'提现',
            'activity'=>'平台活动',
            'spread'=>'商家推广'
        );
    }


    //减少余额
    public function use_money($store_id,$money,$type,$desc,$order_id,$percent=0,$system_take = 0,$mer_user=array()){
        $date['store_id']=$store_id;
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
        if(!M('Merchant_store')->where(array('store_id'=>$store_id))->setDec('money', $date['money'])){
            return array('error_code'=>true,'msg'=>$desc.'，使用失败！');
        }
        $now_store_money = M('Merchant_store')->field('money,store_owe_money')->where(array('store_id'=> $store_id))->find();
        //$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        $mer_status = 1;
        if(C('config.open_store_owe_money')){
            $now_store_money['store_owe_money'] = 0;
        }
        if($now_store_money['money']<$now_store_money['store_owe_money']){
            M('Merchant_store')->where(array('store_id'=>$store_id))->setField('status',3);
            $mer_status = 3;
        }
        //if(!empty($mer_user['openid'])){
        //    if($mer_status==3){
        //        $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_store_money['openid'], 'first' => $desc.'，当前店铺余额:'.$now_store_money['money'].',您的店铺状态为欠费，您的店铺业务状态为禁止状态，请及时充值' , 'keyword1' => '店铺余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到店铺中心店铺余额中查看'), $store_id);
        //    }else{
        //        $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_store_money['openid'], 'first' => $desc.'，当前店铺余额:'.$now_store_money['money'] , 'keyword1' => '店铺余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到店铺中心店铺余额中查看'), $store_id);
        //
        //    }
        //}

        $date['now_store_money'] = $now_store_money['money'];
        if(!$this->add($date)){
            return array('error_code'=>true,'msg'=>$desc.'，保存失败！');
        }else{
            return array('error_code'=>false,'msg'=>$desc.'，保存成功！');
        }
    }

    //统计所有店铺余额
    public function  get_all_money($where){
        if(!empty($where)){
            return M('Merchant_store')->where($where)->sum('money');
        }
        return M('Merchant_store')->sum('money');
    }


}