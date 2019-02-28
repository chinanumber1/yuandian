<?php
class Classify_orderModel extends Model{
    public function save_post_form($now_classify_userinput , $uid){
        $classify_order_where['classify_userinput_id'] = $now_classify_userinput['id'];
        $classify_order_where['paid'] = 1;

        if($this->where($classify_order_where)->find()){
            return array('error'=>1,'msg'=>'该信息已被人购买！');
        }

        $classify_order['classify_userinput_id'] = $now_classify_userinput['id'];
        $classify_order['order_name'] = $now_classify_userinput['title'];
        $classify_order['price'] = $now_classify_userinput['assure_money'];
        $classify_order['num'] = 1;
        $classify_order['total_price'] = $classify_order['price'] * $classify_order['num'];
        $classify_order['order_time'] = time();
        $classify_order['is_source'] = $_POST['is_source'] + 0;

        $now_adress = D('User_adress')->get_one_adress($uid,$_POST['address_id']);
        if(empty($now_adress)){
            return array('error'=>1,'msg'=>'请先添加收货地址！');
        }
        $classify_order['contact_name'] = $now_adress['name'];
        $classify_order['phone'] = $now_adress['phone'];
        $classify_order['zipcode'] = $now_adress['zipcode'];
        $classify_order['adress'] = $now_adress['province_txt'].' '.$now_adress['city_txt'].' '.$now_adress['area_txt'].' '.$now_adress['adress'].' '.$now_adress['detail'];
        $classify_order['seller_user_id'] = $now_classify_userinput['uid'];

        $database_user = D('User');
        $now_user = $database_user->get_user($uid);
        if(empty($now_user)){
            return array('error'=>1,'msg'=>'未获取到您的帐号信息，请重试！');
        }
        $classify_order['uid'] = $uid;

        if($now_user['now_money'] < $classify_order['total_price']){
            if(!empty($classify_order['is_source'])){
                $order_id = $this->data($classify_order)->add();
            }
            return array('error'=>1,'msg'=>'您的帐户余额为 '. $now_user['now_money'].' ，不足以购买此商品','order_id'=>$order_id,'flag'=>1);
        }

        $classify_order['paid'] = 1;
        $order_id = $this->data($classify_order)->add();
        if($order_id){
            $save_result = D('User')->user_money($uid,$classify_order['total_price'],'购买'.C('config.classify_name').'  '.$classify_order['order_name'].' * '.$classify_order['num'] , 3 ,$classify_order['order_id']);
            if(!$save_result['error_code']){
                //算取抽成。
                // if(C('config.classify_proportion_full') > 0){
                //     $total	= $classify_order['total_price'] - $classify_order['total_price'] * (C('config.classify_proportion_full') / 100);
                //     D('User')->add_money($classify_order['seller_user_id'] , $total , '收钱 分类信息：'. $classify_order['order_name'] .' 收取金额 ' , 3 ,$classify_order['order_id']);
                // }

                return array('error'=>0,'msg'=>$save_result['error_code']);
            }
        }else{
            return array('error'=>1,'msg'=>'订单产生失败！请重试');
        }

    }

    //支付之后
    public function after_pay($order_param){
        unset($_SESSION['classify_order']);

        if($order_param['pay_type']!=''){
            $condition_classify_order['orderid'] = $order_param['order_id'];
        }else{
            $condition_classify_order['order_id'] = $order_param['order_id'];
        }

        $now_order = $this->field(true)->where($condition_classify_order)->find();

        if(empty($now_order)){
            return array('error'=>1,'msg'=>'当前订单不存在！');
        }else if($now_order['paid'] == 1){
            return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('Index/classify/classify_order_view',array('order_id'=>$now_order['order_id'])));
        }else{
            $database_user = D('User');

            //得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
            $now_user = $database_user->get_user($now_order['uid']);
            if(empty($now_user)){
                return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
            }
            $classify_where['status'] = 1;
            $classify_where['id'] = $now_order['classify_userinput_id'];

            $database_classify_userinput = D('Classify_userinput');
            $now_classify_userinput = $database_classify_userinput->where($classify_where)->find();

            if(empty($now_classify_userinput)){
                return array('error'=>1,'msg'=>'该信息不存在！');
            }

            $data_classify_order['pay_time'] = $_SERVER['REQUEST_TIME'];
            $data_classify_order['pay_type'] = $order_param['pay_type'] ? $order_param['pay_type'] : '';
            $data_classify_order['paid'] = 1;
            $data_classify_order['status'] = 0;
            $data_classify_order['num'] = $order_param['num'];
            $data_classify_order['is_source'] = $order_param['is_source'] ? $order_param['is_source'] : 0;

            if($this->where($condition_classify_order)->data($data_classify_order)->save()){

                //算取抽成。
                if(C('config.classify_proportion_full') > 0){
                    $total	= $now_order['total_price'] - $now_order['total_price'] * (C('config.classify_proportion_full') / 100);
                    D('User')->add_money($now_order['seller_user_id'] , $total , '收钱 分类信息：'. $now_order['order_name'] .' 收取金额 ' , 3 ,$now_order['order_id']);
                }

                if($order_param['is_mobile']){
                    return array('error'=>0,'url'=>str_replace('/source/','/',U('classify/success_order',array('order_id'=>$now_order['order_id']))));
                }else{
                    return array('error'=>0,'url'=>U('Index/classify/classify_order_view',array('order_id'=>$now_order['order_id'])));
                }
            }else{
                return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
            }
        }
    }
}
?>