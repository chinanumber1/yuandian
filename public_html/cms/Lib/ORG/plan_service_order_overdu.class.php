<?php

class plan_service_order_overdu extends plan_base{
	public function runTask(){
		$list = D('Service_user_publish')->where(array('status'=>2,'catgory_type'=>array('neq',1)))->limit(5)->select();
        foreach ($list as $key => $value) {
			$this->keepThread();
            $catInfo = D('Service_category')->where(array('cid'=>$value['cid']))->find();
            $time = $value['add_time']+(intval($catInfo['accept_time'])*60*60);
            if(time() > $time){
                $refund  = D('Plat_order')->order_refund(array('business_type'=>'service','business_id'=>$value['publish_id']));
                if(!$refund['error']) {
//                    if ($value['catgory_type'] == 2) {
//                        $cat_field_info = D('Service_user_publish_buy')->where(array('publish_id' => $value['publish_id']))->find();
//                    } else if ($value['catgory_type'] == 3) {
//                        $cat_field_info = D('Service_user_publish_give')->where(array('publish_id' => $value['publish_id']))->find();
//                    }
                    D('Service_user_publish')->where(array('publish_id' => $value['publish_id']))->save(array('status' => 11));
                    D('Deliver_supply')->updateStatusToZero($value['publish_id']);
//                    if ($res) {
//                        D('User')->where(array('uid' => $value['uid']))->setInc('now_money', $cat_field_info['total_price']);
//                        D('User_money_list')->add_row($value['uid'], 1, $cat_field_info['total_price'], $catInfo['cat_name'] . "超时退款 " . $cat_field_info['total_price'] . " 元");
//                    }
                } else {
                    M('Service_user_publish')->where(array('publish_id' => $value['publish_id']))->save(array('status' => 13));
                    M('Deliver_supply')->where(array('item' => 3, 'order_id' => $value['publish_id']))->save(array('status' => 7));
                }
            }
        }
        return true;
    }
}
?>