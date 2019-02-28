<?php
class House_village_express_orderModel extends Model{
    public function house_village_express_order_add($data){
        if(!$data){
            return false;
        }

        $send_time = strtotime($data['send_time']);

        if($send_time <= 0){
            return array('status'=>0,'msg'=>'送达时间不能为空！');
        }

        if($send_time < time()){
            return array('status'=>0,'msg'=>'送达时间不能小于当前时间！');
        }


        $express_id = $data['express_id'] + 0;
        $info = $this->where(array('express_id'=>$express_id,'paid'=>1))->find();

        if($info){
            return array('status'=>0,'msg'=>'已成功上报，请勿重复操作！');
        }

        $data['express_id'] = $express_id;

        $data['send_time'] = strtotime($data['send_time']);
        $data['uid'] = $_SESSION['user']['uid'];
        $data['phone'] = $_SESSION['user']['phone'];
        $data['express_collection_price'] = $data['express_collection_price'] + 0;
        $data['village_id'] =$_POST['village_id']?$_POST['village_id']: $_SESSION['now_village_bind']['village_id'];
        $data['add_time'] = time();

        $insert_id = $this->data($data)->add();
        if($insert_id){
            return array('status'=>1,'msg'=>'添加成功！','order_id'=>$insert_id);
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }
    public function get_pay_order($order_id){
        $now_order = $this->get_one($order_id);
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
        if($now_order['order_type']=='property'){
            $order_info['pay_system_score']=true;
        }
        $now_village = D('House_village')->get_one($now_order['village_id']);
        if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 ){
            $order_info['pay_merchant_ownpay'] = true;
        }

        if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 && $now_village['sub_mch_discount']==0 ){
            $order_info['pay_system_score'] = false;
        }

        if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 && $now_village['sub_mch_sys_pay']==0 ){
            $order_info['pay_system_balance'] = false;
        }
        return $order_info;
    }

    public function get_order_url($order_id,$is_mobile){
        $now_order = $this->get_one($order_id);

        return '.wap.php?g=Wap&c=Library&a=express_service_list&village_id='.$now_order['village_id'];
    }

    public function get_one($order_id){
        if(!$order_id){
            return false;
        }

        return $this->where(array('order_id'=>$order_id))->find();
    }

    public function house_village_express_order_detail($where,$fields = true){
        if(!$where){
            return false;
        }

        $detail = $this->where($where)->field($fields)->find();
        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }

    public function house_village_express_order_edit($where , $data){
        if(!$where || !$data){
            return false;
        }

        $insert_id = $this->where($where)->data($data)->save();
        if($insert_id){
            return array('status'=>1,'msg'=>'修改成功！');
        }else{
            return array('status'=>0,'msg'=>'修改失败！');
        }
    }


    public function house_village_express_order_page_list($where ,$fields = true , $order = 'order_id desc',$pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $house_village_express_order_list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

        $list['list'] = $house_village_express_order_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'result'=>$list);
        }else{
            return array('status'=>0,'result'=>$list);
        }
    }

    public function after_pay($order_id,$plat_order_info){
        $now_order = $this->get_one($order_id);

        $date_order['pay_time'] = $plat_order_info['pay_time'];
        $date_order['paid'] = 1;


        $this->where(array('order_id'=>$order_id))->save($date_order);

        $tmp_order = $now_order;
        $tmp_order['is_own'] = $plat_order_info['is_own'];
        $tmp_order['payment_money'] = $plat_order_info['pay_money'];
        $tmp_order['balance_pay'] = $plat_order_info['system_balance'];
        $tmp_order['order_type'] = 'express';
        $tmp_order['desc'] = '快递代送费用';
        if($plat_order_info['is_own']==0)
            $plat_order_info['is_own']+=4;
        //社区对账

       $res =  D('SystemBill')->bill_method($plat_order_info['is_own'],$tmp_order);
        $village_info = D('House_village')->where(array('village_id'=>$now_order['village_id']))->find();

        //通知社区管理员
        $express_config =M('House_village_express_config')->where(array('village_id'=>$village_info))->find();
        $order_info = M('House_village_express')->field('e.id,o.send_time,u.name,ex.name as express_name , e.express_no,v.village_name')->join('as e LEFT JOIN '.C('DB_PREFIX').'house_village_express_order  o ON e.id = o.express_id left join '.C('DB_PREFIX').'express ex ON e.express_type = ex.id left join '.C('DB_PREFIX').'house_village v ON e.village_id = v.village_id left join  '.C('DB_PREFIX').'house_village_user_bind u ON e.uid = u.uid')->where(array('e.village_id'=>$_POST['village_id'],'e.id'=>$order_id))->find();
        if (C('config.village_sms')&&$order_info['send_time']>0) {
            $sms_data = array('mer_id' => $express_config['village_id'],'store_id'=>0,'type' => 'village_express');
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $express_config['notice_phone'];
            $sms_data['sendto'] = 'village';
            $sms_data['content'] = '【提醒】您好，'.$order_info['village_name'].$order_info['name'].'业主，已预约快递代送服务，代送时间:'.date('Y-m-d H:i',$order_info['send_time']).'，'.$order_info['express_name'].'，单号：'.$order_info['express_no'].'。['.C('config.site_name').']';
            Sms::sendSms($sms_data);
        }

        //通知业主
        if (C('config.village_sms')&&$order_info['send_time']>0) {
            $sms_data = array('mer_id' => $express_config['village_id'],'store_id'=>0,'type' => 'village_express');
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $express_config['notice_phone'];
            $sms_data['sendto'] = 'village';
            $sms_data['content'] = '【提醒】您好，'.$order_info['village_name'].$order_info['name'].'业主，已确认收货，收货时间:'.date('Y-m-d H:i',$order_info['send_time']).'，'.$order_info['express_name'].'，单号：'.$order_info['express_no'].'。['.C('config.site_name').']';
            Sms::sendSms($sms_data);
        }
    }


}
?>