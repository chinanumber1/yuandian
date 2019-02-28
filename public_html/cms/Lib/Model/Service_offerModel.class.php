<?php
// 修改订单状态
class Service_offerModel extends Model{
	// 配送员接收订单
	public function add_offer($publish_id,$puid){

    	$publishInfo = D("Service_user_publish")->where(array('publish_id'=>$publish_id))->field('publish_id,uid,cid,order_sn')->find();

        $offer_info = $this->where(array('publish_id'=>$publish_id))->find();
        if($offer_info){
            return false;
        }
        
        $offer_data['publish_id'] = $publishInfo['publish_id'];
        $offer_data['price'] = 0;
        $offer_data['uid'] = $publishInfo['uid'];
        $offer_data['cid'] = $publishInfo['cid'];
        $offer_data['add_time'] = time();
        $offer_data['order_sn'] = date("ymdHis").rand(10,99).sprintf("%08d",$this->user_session['uid']);
        $offer_data['p_uid'] = $puid;
        $offer_data['status'] = 8;
        $offer_data['deliver_type'] = 1;

        $offer_id = $this->data($offer_data)->add();
        if($offer_id){
            $now_user = D('User')->get_user($publishInfo['uid']);
            if ($now_user['openid']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $publish_id;
//                $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'keyword1' => '您发布的需求配送员已接单，请查看。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时查看！'));
                // 帮买帮送修改  待办事项通知 模板 为 订单状态更新 模板
                $model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'OrderSn' => $publishInfo['order_sn'], 'OrderStatus' =>'您发布的需求配送员已接单，请查看。', 'remark' => date('Y.m.d H:i:s')));
//                $aaaa = '您发布的需求配送员已接单，请查看。';
            }
            // 进行极光App推送
            if ($now_user) {
                if (!$publishInfo['order_sn']) $publishInfo['order_sn'] = $publish_id;
                import('@.ORG.Apppush');
                $order['status'] = 8;
                $order['order_id'] = $publish_id;
                $order['order_sn'] = $publishInfo['order_sn'];
                $order['uid'] = $publishInfo['uid'];
                $order['catgory_type'] = $publishInfo['catgory_type'];
                $apppush = new Apppush();
                $apppush->send($order, 'publish');
            }

            D('Service_offer_record')->data(array('offer_id'=>$offer_id,'publish_id'=>$publish_id,'add_time'=>time(),'remarks'=>'配送员已接单'))->add();
            D("Service_user_publish")->where(array('publish_id'=>$publish_id))->save(array('status' => 8));
			return $offer_id;
		}else{
			return false;
		}
    }

    // 修改订单状态
    public function offer_save_status($publish_id,$puid,$offer_id,$status){
    	$user_publish_where['publish_id']  = $publish_id;
    	D("Service_user_publish")->where($user_publish_where)->save(array('status' => $status));

        $publishInfo = D("Service_user_publish")->where($user_publish_where)->field('publish_id,uid,cid,order_sn')->find();


    	$offer_where['p_uid'] = $puid;
    	$offer_where['offer_id'] = $offer_id;
    	$offer_where['deliver_type'] = 1;
    	
        if($status == 4){
            $remarks = '订单已完成';
        }else if($status == 9){
            $remarks = '配送员送货中';
        }else if($status == 8){
            $remarks = '配送员已接单';
        }
        
    	if($this->where($offer_where)->save(array('status' => $status))){

            D('Service_offer_record')->data(array('offer_id'=>$offer_id,'publish_id'=>$publish_id,'add_time'=>time(),'remarks'=>$remarks))->add();
            $now_user = D('User')->get_user($publishInfo['uid']);
            if ($now_user['openid']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $publish_id;
//                $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'keyword1' => '您发布的需求'.$remarks.'，请查看。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时查看！'));
                // 帮买帮送修改  待办事项通知 模板 为 订单状态更新 模板
                $model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'OrderSn' => $publishInfo['order_sn'], 'OrderStatus' =>'您发布的需求'.$remarks.'，请查看。', 'remark' => date('Y.m.d H:i:s')));
//                $aaaa = '您发布的需求'.$remarks.'，请查看。';
            }
            // 进行极光App推送
            if ($status != 0 && $status != 1) {
                if (!$publishInfo['order_sn']) $publishInfo['order_sn'] = $publish_id;
                import('@.ORG.Apppush');
                $order['status'] = $status;
                $order['order_id'] = $publish_id;
                $order['order_sn'] = $publishInfo['order_sn'];
                $order['uid'] = $publishInfo['uid'];
                $order['catgory_type'] = $publishInfo['catgory_type'];
                $apppush = new Apppush();
                $apppush->send($order, 'publish');
            }

			return true;
		}else{
			return false;
		}
    }


    public function cancel_order($publish_id){
        if($this->where(array('publish_id'=>$publish_id))->delete()){
            D("Service_user_publish")->where(array('publish_id'=>$publish_id))->data(array('status'=>2))->save();
            return true;
        }else{
            return false;
        }
    }

}