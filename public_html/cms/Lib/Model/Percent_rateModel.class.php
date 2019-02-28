<?php

class Percent_rateModel extends Model
{
    //获取抽成比例
    public function get_percent($mer_id,$type,$money,$group_id='',$store_id='')
    {
        if(C('config.open_meal_scan_percent')==0 && $type=='meal_scan'){
            $type='meal';
        }
        if($group_id>0 && C('config.open_group_percent')==1){
            $now_group = M('Group')->where(array('group_id'=>$group_id))->find();
            if($now_group['percent']>=0){
                return $now_group['percent'];
            }
        }
        if($store_id){
            $where['store_id'] = $store_id;
            $now_mer_pr = M('Store_percent_rate')->where($where)->find();
        }else{
            $where['mer_id'] = $mer_id;
            $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        }
        if ($now_mer_pr) {
            if ($now_mer_pr[$type . '_percent'] >= 0 && $now_mer_pr[$type .'_percent']!='') {
                $percent = $this->percent_detail($mer_id,$type,'mer_type',$money,$store_id);
                if(empty($percent)){
                    return $now_mer_pr[$type . '_percent'];
                }else{
                    return $percent;
                }
            } elseif ($now_mer_pr['merchant_percent'] >= 0 &&$now_mer_pr['merchant_percent']!='') {
                $percent = $this->percent_detail($mer_id,$type,'merchant',$money,$store_id);

                if(empty($percent)){
                    return $now_mer_pr['merchant_percent'];
                }else{
                    return $percent;
                }
            } elseif ($now_mer_pr['merchant_store_percent'] >= 0 &&$now_mer_pr['merchant_store_percent']!='') {
                $percent = $this->percent_detail($mer_id,$type,'merchant_store',$money,$store_id);

                if(empty($percent)){
                    return $now_mer_pr['merchant_store_percent'];
                }else{
                    return $percent;
                }
            }elseif ( C('config.open_meal_scan_percent')==1 && $type=='meal_scan' && C('config.meal_scan_percent') >= 0 ) {

                return C('config.' . $type . '_percent');

            }elseif ( C('config.' . $type . '_percent') >= 0 || ($type=='meal_scan' && C('config.meal_percent') >= 0 ) ) {
                if($type=='meal_scan'){
                    $type='meal';
                }
                $percent = $this->percent_detail($mer_id,$type,'sys_type',$money,$store_id);
                if(empty($percent)){
                    return C('config.' . $type . '_percent');

                }else{

                    return $percent;
                }
            } elseif ( C('config.platform_get_merchant_percent') >= 0) {
                $percent = $this->percent_detail($mer_id,$type,'system',$money,$store_id);
                if(empty($percent)){
                    return C('config.platform_get_merchant_percent');
                }else{
                    return $percent;
                }
            } else {
                return 0;
            }
        } else {
            if (C('config.' . $type . '_percent') >= 0) {
                return C('config.' . $type . '_percent');
            } elseif (C('config.platform_get_merchant_percent') >= 0) {
                return C('config.platform_get_merchant_percent');
            } else {
                return 0;
            }
        }
    }

    //批发抽成
    public function get_market_percent($mer_id){

        $now_merchant = D('Merchant')->get_info($mer_id);
        if($now_merchant['market_percent']>0){
            return  $now_merchant['market_percent'];
        }else{
            return   C('config.platform_get_merchant_percent');
        }
    }

    //抽成细则的筛选
    public function percent_detail($mer_id,$type,$level,$money,$store_id=''){
        if($type=='meal_scan') return;
        $percent_detail = M('Percent_detail')->select();
        if(!empty($percent_detail)){
            if($store_id){
                $model = M('Percent_detail_by_store_type');
                $system_percent = $model->where(array('fid'=>0))->find();
                $mer_percent = $model->where(array('fid'=>$store_id))->find();
            }else{
                $model = M('Percent_detail_by_type');
                $system_percent = $model->where(array('fid'=>0))->find();
                $mer_percent = $model->where(array('fid'=>$mer_id))->find();
            }
            $percent  = 0;
            $i = 0;
            $in_detail = false;
            switch($level){
                case 'mer_type':
                    $percent_arr = explode(',',$mer_percent[$type.'_percent_detail']);
                    break;
                case 'merchant';
                    $percent_arr = explode(',',$mer_percent['merchant_percent_detail']);
                    break;
                case 'merchant_store';
                    $percent_arr = explode(',',$mer_percent['merchant_store_percent_detail']);
                    break;
                case 'sys_type':
                    $percent_arr = explode(',',$system_percent[$type.'_percent_detail']);
                    break;
            }

            foreach ($percent_detail as $pv) {
                if($pv['money_start'] <= $money && $money <= $pv['money_end']){
                    if($percent_arr[$i] >= 0&&$percent_arr[$i]!=''){
                        $percent = $percent_arr[$i];
                    }else{
                        $percent = $pv['percent'];
                    }
                    $in_detail = true;
                }
                $i++;
            }
            if($in_detail){
                return $percent;
            }else{
                return;
            }
        }else{
            return ;
        }
    }
    //获取分佣比例
    public function get_rate($mer_id, $type)
    {
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        if ($now_mer_pr) {
            if ($now_mer_pr[$type . '_rate'] >= 0 &&$now_mer_pr[$type . '_rate']!='') {
                return $now_mer_pr[$type . '_rate'];
            } elseif ( $now_mer_pr['merchant_rate'] >= 0 &&$now_mer_pr['merchant_rate']!='') {
                return $now_mer_pr['merchant_rate'];
            } elseif (C('config.' . $type . '_rate') >= 0) {
                return C('config.' . $type . '_rate');
            } elseif ( C('config.platform_get_merchant_rate') >= 0) {
                return C('config.platform_get_merchant_rate');
            } else {
                return 0;
            }
        } else {
            if (C('config.' . $type . '_rate') >= 0 ) {
                return C('config.' . $type . '_rate');
            } elseif (C('config.platform_get_merchant_rate') >= 0) {
                return C('config.platform_get_merchant_rate');
            } else {
                return 0;
            }
        }
    }

    //判断是否可以使用线下支付
    public function pay_offline($mer_id,$type){
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        if ($now_mer_pr) {
            if($type=='group'||$type=='shop'||$type=='meal'){
                return $now_mer_pr[$type.'_offline']&&$now_mer_pr['merchant_offline']&&C('config.'.$type.'_offline')&&C('config.pay_offline_open');
            }else{
                return $now_mer_pr['merchant_offline']&&C('config.'.$type.'_offline')&&C('config.pay_offline_open');
            }
        } else {
            return C('config.'.$type.'_offline')&&C('config.pay_offline_open');
        }
    }

    //获取用户分佣比例
    public function get_user_spread_rate($mer_id,$type,$group_id=''){
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        $first_rate='';
        $second_rate='';
        $third_rate='';
        $rate_type='';
        switch($type){
            case 'group':
                $now_group = M('Group')->where(array('group_id'=>$group_id))->find();
                //if($now_group['spread_rate']&&$now_mer_pr['group_first_rate']&&C('config.group_first_rate')&&C('config.user_spread_rate')){
                if($now_group['spread_rate']>=0){
                    $first_rate=$now_group['spread_rate'];
                    $second_rate=$now_group['sub_spread_rate']>0?$now_group['sub_spread_rate']:0;
                    $third_rate=$now_group['third_spread_rate']>0?$now_group['third_spread_rate']:0;
                    $rate_type='group';
                }elseif($now_mer_pr['group_first_rate']>=0&&$now_mer_pr['group_first_rate']!=''){
                    $first_rate=$now_mer_pr['group_first_rate'];
                    $second_rate=$now_mer_pr['group_second_rate']>0?$now_mer_pr['group_second_rate']:0;
                    $third_rate=$now_mer_pr['group_third_rate']>0?$now_mer_pr['group_third_rate']:0;
                    $rate_type='merchant';
                }elseif(C('config.group_first_rate')>=0){
                    $first_rate=C('config.group_first_rate');
                    $second_rate=C('config.group_second_rate')>0?C('config.group_second_rate'):0;
                    $third_rate=C('config.group_third_rate')>0?C('config.group_third_rate'):0;
                    $rate_type='system_group';
                }elseif(C('config.user_spread_rate')>=0){
                    $first_rate=C('config.user_spread_rate');
                    $second_rate=C('config.user_first_spread_rate')>0?C('config.user_first_spread_rate'):0;
                    $third_rate=C('config.user_second_spread_rate')>0?C('config.user_second_spread_rate'):0;
                    $rate_type='system';
                }else{
                    $first_rate=0;
                    $second_rate=0;
                    $third_rate=0;
                    $rate_type='';
                }
                //  }
                break;
            default:
                if($now_mer_pr){
                    if($now_mer_pr[$type.'_first_rate']>=0){
                        $first_rate=$now_mer_pr[$type.'_first_rate'];
                        $second_rate=$now_mer_pr[$type.'_second_rate']>0?$now_mer_pr[$type.'_second_rate']:0;
                        $third_rate=$now_mer_pr[$type.'_third_rate']>0?$now_mer_pr[$type.'_third_rate']:0;
                        $rate_type='merchant';
                    }elseif(C('config.'.$type.'_first_rate')>=0){
                        $first_rate=C('config.'.$type.'_first_rate');
                        $second_rate=C('config.'.$type.'_second_rate')>0?C('config.'.$type.'_second_rate'):0;
                        $third_rate=C('config.'.$type.'_third_rate')>0?C('config.'.$type.'_third_rate'):0;
                        $rate_type='system_'.$type;
                    }elseif(C('config.user_spread_rate')>=0){
                        $first_rate=C('config.user_spread_rate');
                        $second_rate=C('config.user_first_spread_rate')>0?C('config.user_first_spread_rate'):0;
                        $third_rate=C('config.user_second_spread_rate')>0?C('config.user_second_spread_rate'):0;
                        $rate_type = 'system';
                    }else{
                        $first_rate=0;
                        $second_rate=0;
                        $third_rate=0;
                        $rate_type = '';
                    }
                }else{
                    $first_rate=C('config.user_spread_rate');
                    $second_rate=C('config.user_first_spread_rate');
                    $third_rate=C('config.user_second_spread_rate');
                    $rate_type = 'system';
                }
                break;

        }

        return array(
            'first_rate'=>$first_rate,
            'second_rate'=>$second_rate,
            'third_rate'=>$third_rate,
            'type'=>$rate_type,
        );
    }

    //积分最大使用量
    public function get_max_core_use($mer_id,$type,$money=0,$order_info=array()){
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        if(C('config.shop_goods_score_edit')==1 && $type=='shop' && $order_info){

            $use_condition =C('config.user_score_use_condition');

            $user_score_use_percent = C('config.user_score_use_percent');


            if($order_info['total_money']<$use_condition){
                return  0;
            }
            $goods_list = $order_info['order_content'];
            //$goods_list = M('Shop_order')->field('d.id,d.goods_id,g.score_max,g.price')->join('as o left join '.C('DB_PREFIX').'shop_order_detail as d ON o.order_id = d.order_id left join '.C('DB_PREFIX').'shop_goods as g ON d.goods_id = g.goods_id')->where($where)->select();

            $all_score_max = 0;
            foreach ($goods_list as $v) {
                if($v['score_max']>0){
                    $score_max_deducte = bcdiv($v['score_max'], $user_score_use_percent, 2);
                    if($score_max_deducte>$v['discount_price']){
                        $score_max_deducte = $v['discount_price'];
                        $v['score_max'] =  intval(round($score_max_deducte*$user_score_use_percent));
                    }
                    $order_info['total_money'] -= $score_max_deducte;
                    if($order_info['total_money']<$use_condition){
                        $tmp_score_deducte = $use_condition-$order_info['total_money'];
                        $tmp_score_max =  intval(round($tmp_score_deducte*$user_score_use_percent));
                        return $all_score_max + $tmp_score_max;
                    }
                    $all_score_max +=$v['score_max'];
                }
            }
            return $all_score_max;
        }else if ($now_mer_pr) {
            if(strpos($now_mer_pr['merchant_'.$type . '_score_max'],'%')){
                $now_mer_pr['merchant_'.$type . '_score_max'] = $money*floatval(str_replace('%','',$now_mer_pr['merchant_'.$type . '_score_max']))/100;
            }

            if(strpos($now_mer_pr['merchant_score_max'],'%')){
                $now_mer_pr['merchant_score_max'] = $money*floatval(str_replace('%','',$now_mer_pr['merchant_score_max']))/100;
            }

            if(strpos(C('config.' . $type . '_score_max'),'%')){
                C('config.' . $type . '_score_max',$money*floatval(str_replace('%','',C('config.' . $type . '_score_max')))/100);
            }

            if(strpos( C('config.user_score_max_use'),'%')){
                C('config.user_score_max_use',$money*floatval(str_replace('%','', C('config.user_score_max_use')))/100);
            }

            if ($now_mer_pr['merchant_'.$type . '_score_max'] >= 0 && $now_mer_pr['merchant_'.$type . '_score_max']!='') {
                return $now_mer_pr['merchant_'.$type . '_score_max'];
            } elseif ( $now_mer_pr['merchant_score_max'] >= 0 && $now_mer_pr['merchant_score_max']!='') {
                return $now_mer_pr['merchant_score_max'];
            } elseif (C('config.' . $type . '_score_max') >= 0 ) {
                return C('config.' . $type . '_score_max');
            } elseif ( C('config.user_score_max_use') >= 0) {
                return C('config.user_score_max_use');
            } else {
                return 0;
            }
        } else {
            if(strpos(C('config.' . $type . '_score_max'),'%')){
                C('config.' . $type . '_score_max',$money*floatval(str_replace('%','',C('config.' . $type . '_score_max')))/100);
            }

            if(strpos( C('config.user_score_max_use'),'%')){
                C('config.user_score_max_use',$money*floatval(str_replace('%','', C('config.user_score_max_use')))/100);
            }

            if (C('config.' . $type . '_score_max')!='' && C('config.' . $type . '_score_max') >= 0 ) {
                return C('config.' . $type . '_score_max');
            } elseif (C('config.user_score_max_use') >= 0) {
                return C('config.user_score_max_use');
            } else {
                return 0;
            }
        }
    }
    //元宝定制
    public function get_extra_money($order){
        switch($order['order_type']){
            case 'group':
                $total_money = $order['balance_pay']+$order['merchant_balance']+$order['payment_money']+$order['score_deducte'];
                break;
            case 'meal':
                $total_money = $order['balance_pay']+$order['merchant_balance']+$order['payment_money']+$order['score_deducte'];
                break;
            case 'shop':
                $total_money = $order['balance_pay']+$order['merchant_balance']+$order['payment_money']+$order['score_deducte'];
                break;
            case 'appoint':

                if($order['paid'] == 3){
                    $total_money = $order['product_price'];
                }else{
                    if($order['is_initiative']==1){
                        //剩余钱的逻辑
                        if($order['product_id']){
                            //剩余钱的逻辑
                            $total_money = $order['product_balance_pay']+$order['user_pay_money']+$order['product_score_deducte']+$order['product_coupon_price'] + $order['product_payment_price'];

                        }else{
                            $total_money =$order['balance_pay'] + $order['pay_money'] + $order['product_balance_pay']+$order['user_pay_money']+$order['product_score_deducte']+$order['product_coupon_price'] + $order['product_payment_price'];
                        }
                    }else{
                        if($order['product_id']){
                            $money = $order['product_payment_price'];
                        }else{
                            $money = $order['payment_money'];
                        }
                        $total_money  = $money;
                    }

                }



                break;
            case 'store':
                $total_money = $order['balance_pay']+$order['merchant_balance']+$order['payment_money']+$order['score_deducte'];
                break;
            case 'cash':
                $total_money = $order['total_price'];
                break;
        }

        $now_merchant = M('Merchant')->where(array('mer_id'=>$order['mer_id']))->find();
        if($now_merchant['score_get']>=0){
            $score_percent = $now_merchant['score_get'];
        }else{
            $score_percent = C('config.user_score_get');
        }

        if($order['extra_price']>0){
            if($order['score_used_count']>0){
                if($order['score_used_count']<$order['extra_price']){
                    $give_money =$order['extra_price']-$order['score_used_count'];
                }else{
                    $give_money =0 ;
                }
            }else{
                $give_money =bcmul($total_money,$score_percent,2) ;
            }
        }else{
            $give_money =bcmul($total_money,$score_percent,2) ;
        }

        return $give_money;
    }

    public function shop_get_score($order){
        $where['o.order_id'] = $order['order_id'];
        $goods_list = M('Shop_order')->field('d.id,d.goods_id,g.score_max,g.price,g.score_percent,d.num')->join('as o left join '.C('DB_PREFIX').'shop_order_detail as d ON o.order_id = d.order_id left join '.C('DB_PREFIX').'shop_goods as g ON d.goods_id = g.goods_id')->where($where)->select();
        $now_merchant = D('Merchant')->get_info($order['mer_id']);
        if($now_merchant['score_get_percent']>=0){
            $score_get_percent = $now_merchant['score_get_percent']/100;
        }else{
            if(C('config.open_score_get_percent')==1){
                $score_get_percent =C('config.open_score_get_percent')/100;
            }else{
                $score_get_percent =  C('config.user_score_get');
            }
        }
        $all_score_get = 0;
        foreach($goods_list as $v){
            if($v['score_percent']!=''){
                if(strpos($v['score_percent'],'%')){
                    $score_get = round($v['price']*floatval(str_replace('%','',$v['score_percent']))/100)*$v['num'];
                }else{
                    $score_get = round($v['price']*$v['score_percent'])*$v['num'];
                }
            }else{
                $score_get = round($v['discount_price']*$score_get_percent)*$v['num'];
            }
            $all_score_get+=$score_get;
        }
        return $all_score_get;
    }

    public function get_user_spead_money($uid,$mer_id,$order_type,$spread_money=0){
        $user = D('User')->get_user($uid);
        $spread_list = D('User_spread')->get_spread_list($user['openid']);
        $spread_rate = D('Percent_rate')->get_user_spread_rate($mer_id,$order_type);
        $spread_money_array = array();
        foreach($spread_list as $key=>$v){
            if($key==1){
                $spread_money_array[$key] = round($spread_money * $spread_rate['first_rate'] / 100, 2);
            }else if($key==2){
                $spread_money_array[$key] = round($spread_money * $spread_rate['second_rate'] / 100, 2);
            }else if($key==3){
                $spread_money_array[$key] = round($spread_money * $spread_rate['third_rate'] / 100, 2);
            }
        }
        return $spread_money_array;
    }

    public function get_percent_money($order_type,$pay_money,$order_info){
        if($order_type=='group'){
            $percent = D('Percent_rate')->get_percent($order_info['mer_id'],$order_info['order_type'],$pay_money,$order_info['group_id']);
        } elseif($order_type=='meal'&&$order_info['order_from']==1){
            $percent = D('Percent_rate')->get_percent($order_info['mer_id'],'meal_scan',$pay_money);
        } elseif($order_type=='market'){
            $percent = D('Percent_rate')->get_market_percent($order_info['mer_id']);
        } elseif($order_type=='marketcancel'){
            $percent = 0;
        } elseif($order_type=='sub_card') {
            $percent = $order_info['percent'];
        } else {
            $percent = D('Percent_rate')->get_percent($order_info['mer_id'],$order_info['order_type'],$pay_money);
        }
        $percent = floatval($percent);
        return ($pay_money*$percent/100);
    }

}