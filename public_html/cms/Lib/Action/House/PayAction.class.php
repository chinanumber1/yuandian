<?php
class PayAction extends BaseAction{
    public function check(){
        if(empty($this->house_session)){
            $this->error('请先进行登录！',U('Login/index'));
        }
        if(!in_array($_GET['type'],array('sqrecharge'))){
            $this->error('订单来源无法识别，请重试。');
        }
        if($_GET['type'] == 'sqrecharge'){
            $now_village = D('Village_recharge_order')->get_pay_order($this->house_session['village_id'],intval($_GET['order_id']),true);
        }
        if($now_village['error'] == 1){
            if($now_village['url']){
                $this->error_tips($now_village['msg'],$now_village['url']);
            }else{
                $this->error_tips($now_village['msg']);
            }
        }
        $now_store = D('House_village')->get_one($this->house_session['village_id']);
        if(empty($now_store)){
            $this->error_tips('未获取到您的帐号信息，请重试！');
        }
        $order_info = $now_village['order_info'];

        $pay_money = $order_info['order_total_money'];
        $pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
        $tmp_pay_method['weixin'] = $pay_method['weixin'];
        $tmp_pay_method['allinpay'] = $pay_method['allinpay'];



        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        $this->assign('pay_method',$tmp_pay_method);

        $this->assign('order_info',$order_info);

        $this->display();
    }
    protected function getPayName($label){
        $payName = array(
            'weixin' => '微信支付',
            'tenpay' => '财付通支付',
            'yeepay' => '银行卡支付(易宝支付)',
            'allinpay' => '银行卡支付(通联支付)',
            'chinabank' => '银行卡支付(网银在线)',
        );
        return $payName[$label];
    }
    public function go_pay(){

        $pay_class_name = ucfirst($_POST['pay_type']);

        if(empty($this->house_session)){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>0,
                    'info'=>'请先进行登录！',
                );
                exit(json_encode($returnArr));
            }
            $this->error('请先进行登录！',U('Login/index'));
        }
        if(!in_array($_POST['order_type'],array('sqrecharge','sqsmsbuy'))){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>0,
                    'info'=>'订单来源无法识别，请重试。',
                );
                exit(json_encode($returnArr));
            }
            $this->error('订单来源无法识别，请重试。');
        }

        if($_POST['order_type'] == 'sqrecharge'){
            $now_village = D('Village_recharge_order')->get_pay_order($this->house_session['village_id'],intval($_POST['order_id']),true);
        }elseif($_POST['order_type'] == 'sqsmsbuy'){
            $order_info = D('Sms_buy_order')->where(array('order_id'=>$_POST['order_id'],'village_id'=>$this->house_session['village_id']))->find();
            $order_info['order_type'] = 'sqsmsbuy';
            $order_info['order_name'] = '充值短信';
        }

        if($now_village['error'] == 1){
            if($now_village['url']){
                if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                    $returnArr = array(
                        'status'=>0,
                        'info'=>$now_village['msg'],
                    );
                    header('Content-type: application/json');
                    exit(json_encode($returnArr));
                }
                $this->error($now_village['msg'],$now_village['url']);
            }else{
                if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                    $returnArr = array(
                        'status'=>0,
                        'info'=>$now_village['msg'],
                    );
                    exit(json_encode($returnArr));
                }
                $this->error($now_village['msg']);
            }
        }

        if($_POST['order_type'] == 'sqrecharge'){
            $order_info = $now_village['order_info'];
        }
        
        //用户信息
        $now_store = D('House_village')->get_one($this->house_session['village_id']);
        if(empty($now_store)){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>0,
                    'info'=>'未获取到您的帐号信息，请重试！',
                );
                exit(json_encode($returnArr));
            }
            $this->error('未获取到您的帐号信息，请重试！');
        }

        if($order_info['order_type'] == 'sqrecharge'){
            $save_result = D('Village_recharge_order')->web_befor_pay($order_info,$now_store);

            if($save_result['error_code']){
                $this->error($save_result['msg']);exit();
            }else if($save_result['url']){
                $this->assign('jumpUrl',$save_result['url']);
                $this->success($save_result['msg']);exit();
            }
        }

        
        //需要支付的钱
        if($order_info['order_type'] == 'sqrecharge'){
            $pay_money = $save_result['pay_money'];
        }elseif ($order_info['order_type'] == 'sqsmsbuy') {
            $pay_money = $order_info['payment_money'];
        }
        

        $pay_method = D('Config')->get_pay_method($notOnline,$notOffline);


        if(empty($pay_method)){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>0,
                    'info'=>'系统管理员没开启任一一种支付方式！',
                );
                exit(json_encode($returnArr));
            }
            $this->error('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$_POST['pay_type']])){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>0,
                    'info'=>'您选择的支付方式不存在，请更换支付方式！',
                );
                header('Content-type: application/json');
                exit(json_encode($returnArr));
            }
            $this->error('您选择的支付方式不存在，请更换支付方式！');
        }

        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>0,
                    'info'=>'系统管理员暂未开启该支付方式，请更换其他的支付方式',
                );
                exit(json_encode($returnArr));
            }
            $this->error('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        //用pay_id代替参数order_id传进在线支付
        $order_id = $order_info['order_id'];

        if($_POST['order_type']=='sqrecharge'){
            $order_table = 'Village_recharge_order';
        }else{
            $order_table = ucfirst($_POST['order_type']).'_order';
        }

        $nowtime = date("ymdHis");
        if($order_info['order_type'] == 'sqsmsbuy'){
            $orderid = $order_info['orderid'];
        }else{
            $orderid = $nowtime.rand(10,99).sprintf("%06d",$this->house_session['village_id']);
        }
        
        $data_tmp['pay_type'] = $_POST['pay_type'];
        $data_tmp['order_type'] = $_POST['order_type'];
        $data_tmp['order_id'] = $order_id;
        $data_tmp['orderid'] = $orderid;
        $data_tmp['addtime'] = $nowtime;
        if(!D('Tmp_orderid')->add($data_tmp)){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>0,
                    'info'=>'更新失败，请联系管理员',
                );
                exit(json_encode($returnArr));
            }
            $this->error('更新失败，请联系管理员');
        }

        if($order_info['order_type'] != 'sqsmsbuy'){
            $save_pay_id = D($order_table)->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
            if(!$save_pay_id){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                    $returnArr = array(
                        'status'=>0,
                        'info'=>'更新失败，请联系管理员',
                    );
                    exit(json_encode($returnArr));
                }
                $this->error('更新失败，请联系管理员');
            }
        }

        $order_info['order_id']=$orderid;

        $pay_class = new $pay_class_name($order_info,$pay_money,$_POST['pay_type'],$pay_method[$_POST['pay_type']]['config'],$this->house_session,0);
        $go_pay_param = $pay_class->pay();

        if(empty($go_pay_param['error'])){
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>1,
                    'info'=>$go_pay_param['qrcode'],
                    'orderid'=>$orderid,
                );
                header('Content-type: application/json');
                echo json_encode($returnArr);
                exit;
            }else if(!empty($go_pay_param['url'])){
                $this->assign('url',$go_pay_param['url']);
            }else if(!empty($go_pay_param['form'])){
                $this->assign('form',$go_pay_param['form']);
            }else{
                if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                    $returnArr = array(
                        'status'=>0,
                        'info'=>'调用支付发生错误，请重试。',
                    );
                    header('Content-type: application/json');
                    exit(json_encode($returnArr));
                }
                $this->error('调用支付发生错误，请重试。');
            }
        }else{
            if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
                $returnArr = array(
                    'status'=>0,
                    'info'=>$go_pay_param['msg'],
                );
                header('Content-type: application/json');
                exit(json_encode($returnArr));
            }
            $this->error($go_pay_param['msg']);
        }
        $this->display();
    }

    //异步通知
    public function notify_url(){
        $pay_method = D('Config')->get_pay_method();
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$_GET['pay_type']])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($_GET['pay_type']);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        $pay_class = new $pay_class_name('','',$_GET['pay_type'],$pay_method[$_GET['pay_type']]['config'],$this->house_session,0);
        $notify_return = $pay_class->notice_url();

        if(empty($notify_return['error'])){

        }else{
            $this->error_tips($notify_return['msg']);
        }
    }

    //跳转通知
    public function return_url(){
        $pay_type = isset($_GET['paytype']) ? $_GET['paytype'] : $_GET['pay_type'];

        $pay_method = D('Config')->get_pay_method();

        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$pay_type])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        $pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->house_session,0);
        $get_pay_param = $pay_class->return_url();

        if(empty($get_pay_param['error'])){
            if($get_pay_param['order_param']['order_type'] == 'sqrecharge'){
                $now_village = $this->get_orderid('Village_recharge_order',$get_pay_param['order_param']['order_id'],0);
                $get_pay_param['order_param']['order_id']=$now_village['orderid'];
                $pay_info = D('Village_recharge_order')->after_pay($get_pay_param['order_param']);
            }elseif ($get_pay_param['order_param']['order_type'] == 'sqsmsbuy') {
                $now_village = $this->get_orderid('Sms_buy_order',$get_pay_param['order_param']['order_id'],0);
                $get_pay_param['order_param']['order_id']=$now_village['orderid'];
                $pay_info = D('Sms_buy_order')->after_pay($get_pay_param['order_param']);
            }else{
                $this->error('订单类型非法！请重新下单。');
            }


            $urltype = isset($_GET['urltype']) ? $_GET['urltype'] : '';
            if(empty($pay_info['error'])){
                if($get_pay_param['order_param']['pay_type'] == 'weixin'){
                    exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
                } elseif ($get_pay_param['order_param']['pay_type'] == 'baidu') {//百度的异步通知返回
                    exit("<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>");
                } elseif ('unionpay' == $get_pay_param['order_param']['pay_type'] && $urltype == 'back') {
                    exit("验签成功");
                }
                if(!empty($pay_info['url'])){
                    $this->assign('jumpUrl',$pay_info['url']);
                    $this->success('订单付款成功！现在跳转.');
                    exit();
                }
            }
            if(empty($pay_info['url'])){
                $this->error($pay_info['msg']);
            }else{
                if ($pay_info['error'] && $urltype == 'front') {
                    $this->redirect($pay_info['url']);
                    exit;
                }
                $this->error($pay_info['msg'],$pay_info['url']);exit;
            }
        }else{
            $this->error($get_pay_param['msg']);
        }
    }

    public function get_orderid($table,$orderid,$offline=0){
        $order =  D($table);
        $tmp_orderid = D('Tmp_orderid');
        if($offline){
            $now_village = $order->where(array('orderid'=>$orderid))->find();
        }else{
            $now_village = $order->where(array('orderid'=>$orderid))->find();
            if(empty($now_village)){
                $res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
                $now_village = $order->where(array('order_id'=>$res['order_id']))->find();
                $order->where(array('order_id'=>$res['order_id']))->setField('orderid',$orderid);
                $now_village['orderid']=$orderid;
            }
        }
        if(empty($now_village)){
            $this->error('该订单不存在');
        }
        return $now_village;
    }


    //微信同步回调页面
    public function weixin_back(){
        switch($_GET['order_type']){
            case 'sqrecharge':
                $now_village =$this->get_orderid('Village_recharge_order',$_GET['order_id']);
                break;
            case 'sqsmsbuy':
                $now_village =$this->get_orderid('Sms_buy_order',$_GET['order_id']);
                break;
            default:
                $this->error('非法的订单');
        }

        if(empty($now_village)){
            $this->error('该订单不存在');
        }
        $now_village['order_type'] = $_GET['order_type'];
        if($now_village['paid']){
            switch($_GET['order_type']){

                case 'sqrecharge':
                    $redirctUrl = C('config.site_url').'/shequ.php?g=House&c=Village_money&a=money_list';
                    break;
                case 'sqsms':
                    $redirctUrl = C('config.site_url').'/shequ.php?g=House&c=Village_money&a=sms_note&type=buy';
                    break;
            }
            redirect($redirctUrl);exit;
        }else if($_GET['pay_type'] == 'weifutong'){
            $this->error('您还未支付');
        }
        $tmpid = $now_village['order_id'];
        $now_village['order_id']  =   $now_village['orderid'];
        $import_result = import('@.ORG.pay.Weixin');
        $pay_method = D('Config')->get_pay_method();
        if(empty($pay_method)){
            $this->error('系统管理员没开启任一一种支付方式！');
        }
        $pay_class = new Weixin($now_village,0,'weixin',$pay_method['weixin']['config'],$this->house_session,1);
        $go_query_param = $pay_class->query_order();
        if($go_query_param['error'] === 0){
            switch($_GET['order_type']){
                case 'sqrecharge':
                    D('Village_recharge_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'sqsmsbuy':
                    D('Sms_buy_order')->after_pay($go_query_param['order_param']);
                    break;

            }
        }
        $now_village['order_id']  =   $tmpid;
        switch($_GET['order_type']){

            case 'sqrecharge':
                $redirctUrl = C('config.site_url').'/shequ.php?g=House&c=Village_money&a=money_list';
                break;
            case 'sqsmsbuy':
                $redirctUrl = C('config.site_url').'/shequ.php?g=House&c=Village_money&a=sms_note&type=buy';
                break;


        }
        redirect($redirctUrl);
    }
    //支付宝支付同步回调
    public function alipay_return(){
        $order_id_arr = explode('_',$_GET['out_trade_no']);
        $order_type = $order_id_arr[0];
        $order_id = $order_id_arr[1];
        switch($order_type){
            case 'sqrecharge':
                $now_village = D('Village_recharge_order')->where(array('orderid'=>$order_id))->find();
                break;
            default:
                $this->error('非法的订单');
        }
        if($now_village['paid']){
            switch($order_type){

                case 'sqrecharge':
                    $redirctUrl = C('config.site_url').'/shequ.php?g=Store&c=Store_money&a=index';
                    break;

            }
            redirect($redirctUrl);exit;
        }
        $pay_method = D('Config')->get_pay_method();
        if(empty($pay_method)){
            $this->error('系统管理员没开启任一一种支付方式！');
        }
        $import_result = import('@.ORG.pay.Alipay');
        $pay_class = new Alipay('','',$order_type,$pay_method['alipay']['config'],$this->house_session,0);
        $go_query_param = $pay_class->query_order();
        if($go_query_param['error'] === 0){
            switch($order_type){
                case 'sqrecharge':
                    D('Village_recharge_order')->after_pay($go_query_param['order_param']);
                    break;
            }
        }
        switch($order_type){

            case 'sqrecharge':
                $redirctUrl = C('config.site_url').'/shequ.php?g=House&c=Village_money&a=recharge&order_id='.$now_village['order_id'];
                break;
        }
        redirect($redirctUrl);
    }

}
?>