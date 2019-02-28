<?php
class Village_moneyAction extends BaseAction{
    //店铺充值列表
    public function money_list(){
        //物业余额-查看 权限
        if (!in_array(60, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if (empty($this->house_session['is_open_estate'])) {
            redirect($this->config['site_url'].'/shequ.php?g=House&c=Index&a=index');
            exit;
        }
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'order_id') {
                $condition_where['order_id'] = htmlspecialchars($_GET['keyword']);
            }
        }
        $condition_where['village_id']= $this->house_session['village_id'];
        !empty($_GET['type']) && $_GET['type']!='all' && $condition_where['type'] =$_GET['type'];
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where['_string']= "use_time BETWEEN ".$period[0].' AND '.$period[1];

        }

        $count = M('Village_money_list')->where($condition_where)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count, 15);
        $order_list= M('Village_money_list')->where($condition_where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
        $this->assign('pagebar',$p->show());
        unset($condition_where['_string']);
        $this->assign('village',M('House_village')->where($condition_where)->find());
        $this->assign('order_list',$order_list);
        $this->assign('alias_name',$this->get_alias_c_name());
        $this->display();

    }

    protected  function get_alias_c_name(){
        $c_name = array(
            'all'=>'所有分类',
            'sqrecharge'=>'充值',
            'withdraw'=>'提现',
            'village_pay'=>'社区缴费',
            'village_pay_cashier'=>'社区收银台缴费',
            'express'=>'快递',
        );

        return $c_name ;
    }
    public function recharge_list(){

        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'order_id') {
                $condition_where['order_id'] = htmlspecialchars($_GET['keyword']);
            }
        }
        !empty($_GET['pay_type']) && $condition_where['pay_type'] =$_GET['pay_type'];
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where['_string']= "pay_time BETWEEN ".$period[0].' AND '.$period[1];

        }
        $condition_where['village_id']=  $this->Village['village_id'];

        $count = M('Village_recharge_order')->where($condition_where)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count, 15);
        $order_list= M('Village_recharge_order')->where($condition_where)->order('order_id DESC')->limit($p->firstRow,$p->listRows)->select();
        $this->assign('pagebar',$p->show());
        $this->assign('order_list',$order_list);
        $this->assign('pay_type',$this->getPayName());
        $this->display();
    }

    protected function getPayName(){
        $payName = array(
            'weixin' => '微信支付',
            'tenpay' => '财付通支付',
            'yeepay' => '银行卡支付(易宝支付)',
            'allinpay' => '银行卡支付(通联支付)',
            'chinabank' => '银行卡支付(网银在线)',
        );
        return $payName;
    }
    public function recharge(){
        //物业余额-充值 权限
        if (!in_array(61, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if($_GET['money']>0){
            $money = floatval($_GET['money']);

            if (empty($money)||$money <0 ||!is_numeric($money)) {
                $this->error('请输入正确的充值金额');
            }
            $data_store_recharge_order['village_id'] = $this->house_session['village_id'];
            $data_store_recharge_order['money'] = $money;
            $data_store_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
            $data_store_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];

            if ($order_id = M('Village_recharge_order')->data($data_store_recharge_order)->add()) {
                redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'sqrecharge')));
            } else {
                $this->error('订单创建失败，请重试。');
            }
        }else{
            $this->display();
        }
    }

    public function withdraw(){
        //物业余额-提现 权限
        if (!in_array(62, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if($this->config['company_pay_open']=='0') {
            $this->error('平台没有开启提现功能！');
        }
        $village_id= intval($this->house_session['village_id']);
        $now_village = M('House_village')->where(array('village_id'=>$village_id))->find();
        $this->assign('now_village',$now_village);
        if(M('Village_withdraw')->where(array('village_id'=>$village_id,'status'=>array('in','0,4')))->find()){
            $this->error('您有一笔提现在审核，请审核通过了再申请！');
        }
        if(isset($_POST['money']) && !$_POST['money']){
            $this->error('金额输入有误');
        }
        if($_POST['money']>0){
            if($_POST['money']>$now_village['money']){
                $this->error('提现金额超过了您的余额');
            }
            $money = floatval(($_POST['money']))*100;
            if($_POST['money']<$this->config['min_withdraw_money']){
                $this->error('不能低于最低提款额 '.$this->config['min_withdraw_money'].' 元!');
            }

            if(empty($_POST['name'])){
                $this->error('真实姓名不能为空');
            }
            if($_POST['withdraw_type']!=2){
                $money = $_POST['money'];

                if($money<$this->config['company_least_money']){
                    $this->error('不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
                }

                if(!is_numeric($_POST['withdraw_type'])){
                    $this->error('提现方式没有选择');
                }
                $data_companypay['type'] = 'village';
                $data_companypay['pay_type'] = $_POST['withdraw_type'];//0 银行卡，1 支付宝 2微信
                $data_companypay['pay_id'] = $now_village['village_id'];
                $remark = '';
                if($_POST['withdraw_type']==0){
                    $data_companypay['account'] = $_POST['card_number'];
                    if(empty($_POST['card_number']) || empty($_POST['card_username']) ||empty($_POST['bank']) ){
                        $this->error('银行账号不全');
                    }
                    $remark = '开户名：'.$_POST['card_username'].',开户行：'.$_POST['bank'];
                }else if ($_POST['withdraw_type']==1){
                    if(empty($_POST['alipay_account'])  ){
                        $this->error('支付宝账号不全');
                    }
                    $data_companypay['account'] = $_POST['alipay_account'];
                }

                
                $data_companypay['truename'] = $_POST['name'];
                $data_companypay['name'] = $now_village['village_name'];
                $data_companypay['remark'] = $remark ;
                $data_companypay['phone'] = $now_village['phone']?$now_village['phone']:"";
                $data_companypay['money'] = bcmul($money*((100-$this->config['company_pay_village_percent'])/100),100);
                $data_companypay['old_money'] = $money*100;
                $data_companypay['desc'] = "社区提现对账订单|社区ID ".$now_village['village_id']." |转账 ".$money." 元" ;
                if($this->config['company_pay_village_percent']>0){
                    $data_companypay['desc'] .= '|手续费 '.(($data_companypay['old_money'] -  $data_companypay['money'])/100).' 比例 '.$this->config['company_pay_village_percent'].'%';
                }
                $data_companypay['status'] = 0;
                $data_companypay['add_time'] = time();


                $date_village['village_id']=$village_id;
                $date_village['name']=$_POST['name'];
                $date_village['money']=   $data_companypay['money'] ;
                $date_village['old_money'] =  $data_companypay['old_money'];
                $date_village['remark']=  $data_companypay['desc'].'|'.$_POST['info'];
                $date_village['withdraw_time'] = time();
                $date_village['status'] = 4;
                $res =M('Village_withdraw')->add($date_village);
                if(!$res){
                    $this->error('申请失败');die;
                }
                $data_companypay['withdraw_id'] = $res;
                $result = D('Village_money_list')->use_money($village_id,$money,'withdraw',  $data_companypay['desc'].'|'.$_POST['info'],$res ,$this->config['company_pay_village_percent'],(($data_companypay['old_money'] -  $data_companypay['money'])/100));
                M('Withdraw_list')->add($data_companypay);


                $this->success("申请成功，请等待审核！",U('Village_money/money_list'));die;
            }else{
                $res = D('Village_money_list')->withdraw($village_id,$_POST['name'],$money,$_POST['info']);

                if($res['error_code']){
                    $this->error($res['msg']);
                }else{
                    D('Scroll_msg')->add_msg('village_withdraw',$now_village['village_id'],'社区'.$now_village['name'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '提现成功！');
                    $this->success("申请成功，请等待审核！",U('Village_money/money_list'));
                }
            }
        }else{
            $this->display();
        }
    }


    public  function  get_bank_name(){
        $card_number = $_POST['card_number'];
        require_once APP_PATH . 'Lib/ORG/BankList.class.php';
        if($res = $this->bankInfo($card_number,$bankList)){
            $this->success($res);
        }else{
            $this->error('没有查询到相关银行');
        }
    }

    function bankInfo($card,$bankList)
    {
        $card_8 = substr($card, 0, 8);
        if (isset($bankList[$card_8])) {
            return $bankList[$card_8];
        }
        $card_6 = substr($card, 0, 6);
        if (isset($bankList[$card_6])) {
            return $bankList[$card_6];

        }
        $card_5 = substr($card, 0, 5);
        if (isset($bankList[$card_5])) {
            return $bankList[$card_5];

        }
        $card_4 = substr($card, 0, 4);
        if (isset($bankList[$card_4])) {
            return $bankList[$card_4];

        }
        return null;
    }


    public function village_money_export()
    {	
        //物业余额-导出 权限
        if (!in_array(63, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        //$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';

        $type = 'income';
        $title = '';

        switch ($type) {

            case 'income':
                $title = '收入明细';
                break;


        }
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);
        // 设置当前的sheet
        $objExcel->setActiveSheetIndex(0);


        $objExcel->getActiveSheet()->setTitle($type);
        $objActSheet = $objExcel->getActiveSheet();
        $cell_income   = array('type'=>'类型','order_id'=>'订单编号', 'num'=>'数量', 'money'=>'金额','use_time'=>'对账时间','system_take'=>'平台佣金','percent'=>'佣金百分比','now_village_money'=>'当前社区余额','desc'=>'描述');
        // 开始填充头部
        $cell_name = 'cell_'.$type;
        $cell_count = count($$cell_name);
        $cell_start = 1;
        for($f='A';$f<='Z';$f++,$cell_start++){
            if($cell_start>$cell_count){
                break;
            }
            $col_char[]=$f;
        }
        $col_k=0;
        foreach($$cell_name as $key=>$v){
            $objActSheet->getColumnDimension($col_char[$col_k])->setWidth(20);
            $objActSheet->setCellValue($col_char[$col_k].'1', $v);
            $col_k++;
        }
        $i = 2;
        //if($type=='income'){
        $where['village_id']=$village_id;
        if($_GET['type']&&$_GET['type']!='all'){
            $where['type']=$_GET['type'];
        }
        if($_GET['order_id']){
            $where['order_id']=$_GET['order_id'];
        }
        if($_GET['store_id']){
            $where['store_id']=$_GET['store_id'];
        }


        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));

            $time_condition=" (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $where['_string']=$time_condition;
        }
        $result = M('Village_money_list')->field('type,order_id,num,pow(-1,income+1)*money as money,use_time,desc,system_take,percent,now_village_money')->where($where)->order('use_time DESC')->select();

        //}
        $alias_name = $this->get_alias_c_name();
        foreach ($result as $row) {
            $col_k=0;
            foreach($$cell_name as $k=>$vv){
                switch($k){
                    case 'type':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $alias_name[$row[$k]].' ');
                        break;
                    case 'order_id':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'real_orderid':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'orderid':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'pay_time':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
                        break;
                    case 'use_time':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
                        break;
                    case 'desc':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    default:
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
                        break;
                }
                $col_k++;
            }
            if($type!='income'){
                $objActSheet->setCellValue($col_char[$cell_count-1] . $i, $row['balance_pay']+$row['coupon_price']+$row['score_deducte']+$row['payment_money']);
            }
            $i++;
        }
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();

    }

    //我的短信
    public function sms_note(){
        // 我的短信-查看 权限
        if (!in_array(248, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        $now_store = D('House_village')->get_one($this->house_session['village_id']);
        // dump($now_store);
        $this->assign('now_store',$now_store);
        if($_GET['type'] == 'buy'){
            $condition_where['type_id']=$village_id;
            $condition_where['type']=1;
            if(!empty($_GET['start_time']) && !empty($_GET['end_time'])){
                $condition_where['add_time'] = array(array('gt',strtotime($_GET['start_time']." 00:00:00")),array('lt',strtotime($_GET['end_time']." 23:59:59")));
            }elseif($_GET['start_time']){
                $condition_where['add_time'] = array('gt',strtotime($_GET['start_time']." 00:00:00"));
            }elseif($_GET['end_time']){
                $condition_where['add_time'] = array('lt',strtotime($_GET['end_time']." 23:59:59"));
            }
            if($_GET['paid'] != ''){
                $condition_where['paid'] = array('eq',$_GET['paid']);
            }else{
                $condition_where['paid'] = array('neq',0);
            }
            $count = D("Sms_buy_order")->where($condition_where)->count();
            import('@.ORG.merchant_page');
            $p = new Page($count, 10);
            $orderList= M('Sms_buy_order')->where($condition_where)->order('order_id DESC')->limit($p->firstRow,$p->listRows)->select();
            $this->assign('pagebar',$p->show());
            $this->assign('orderList',$orderList);
        }else{
            $condition_where['village_id']=$village_id;
            // 
            if(!empty($_GET['start_time']) && !empty($_GET['end_time'])){
                $condition_where['time'] = array(array('gt',strtotime($_GET['start_time']." 00:00:00")),array('lt',strtotime($_GET['end_time']." 23:59:59")));
            }elseif($_GET['start_time']){
                $condition_where['time'] = array('gt',strtotime($_GET['start_time']." 00:00:00"));
            }elseif($_GET['end_time']){
                $condition_where['time'] = array('lt',strtotime($_GET['end_time']." 23:59:59"));
            }
            if($_GET['status'] != ''){
                $condition_where['status'] = array('eq',$_GET['status']);
            }

            if($_GET['type'] == 'village_express'){
                $condition_where['type']= 'village_express';
            }elseif ($_GET['type'] == 'village_vistor') {
                $condition_where['type']= 'village_vistor';
            }

            $count = D("Sms_record")->where($condition_where)->count();
            import('@.ORG.merchant_page');
            $p = new Page($count, 10);
            $record_list= M('Sms_record')->where($condition_where)->order('pigcms_id DESC')->limit($p->firstRow,$p->listRows)->select();
            $this->assign('pagebar',$p->show());
            $this->assign('record_list',$record_list);
            $status = array('0' =>'发送成功', '-1' => '验证失败未购买', '-2' => '短信不足', '-3' => '操作失败', '-4' => '非法字符', '-5' => '内容过多', '-6' => '号码过多', '-7' => '频率过快', '-8' => '号码内容空', '-9' => '账号冻结', '-10' => '禁止频繁单条发送', '-11' => '系统暂定发送', '-12' => '有错误号码', '-13' => '定时时间不对', '-14' => '账号被锁，10分钟后登录', '-15' => '连接失败', '-16' => '禁止接口发送', '-17' => '绑定IP不正确', '-18' => '系统升级', '-19' => '域名不对', '-20' => 'key不匹配', '-21' => '用户不存在', '-22' => '余额不足', '-100' => '发送的token不合法','-999'=>'频繁发送');
            $this->assign('status', $status);

        }

        $this->display();
    }

    public function sms_buy(){
        // 我的短信-购买 权限
        if (!in_array(249, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if($_GET['sms_number']){
            $data_sms_buy_order['type_id'] = $this->house_session['village_id'];
            $data_sms_buy_order['type'] = 1;
            $data_sms_buy_order['sms_number'] = $_GET['sms_number'];
            $data_sms_buy_order['payment_money'] = $this->config['sms_price']*$_GET['sms_number']/100;
            $data_sms_buy_order['add_time'] = $_SERVER['REQUEST_TIME'];
            $data_sms_buy_order['orderid'] = 'sq'.date("ymdHis").rand(10,99).sprintf("%06d",$this->house_session['village_id']);

            if ($order_id = M('Sms_buy_order')->data($data_sms_buy_order)->add()) {
                redirect(U('Village_money/sms_buy_order_check', array('order_id' => $order_id, 'type' => 'sqsmsbuy')));
            } else {
                $this->error('订单创建失败，请重试。');
            }
        }
        $this->display();
    }

    public function sms_buy_order_check(){
        if(empty($this->house_session)){
            $this->error('请先进行登录！',U('Login/index'));
        }
        if(!in_array($_GET['type'],array('sqsmsbuy'))){
            $this->error('订单来源无法识别，请重试。');
        }

        $order_info = D('Sms_buy_order')->where(array('order_id'=>$_GET['order_id'],'type_id'=>$this->house_session['village_id'],'type'=>1))->find();
        $now_store = D('House_village')->get_one($this->house_session['village_id']);
        if(empty($now_store)){
            $this->error_tips('未获取到您的帐号信息，请重试！');
        }

        $pay_money = $order_info['payment_money'];
        $pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
        $tmp_pay_method['weixin'] = $pay_method['weixin'];
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        $order_info['order_type'] = 'sqsmsbuy';
        $this->assign('pay_method',$tmp_pay_method);
        $this->assign('now_store',$now_store);
        $this->assign('order_info',$order_info);

        $this->display();
    }


    public function go_pay_yue(){
        $order_info = D('Sms_buy_order')->where(array('order_id'=>$_POST['order_id'],'type_id'=>$this->house_session['village_id'],'type'=>1))->find();

        if($order_info['paid'] == 1){
            exit(json_encode(array('error'=>4,'msg'=>'订单已支付')));
        }

        $now_village = M('House_village')->where(array('village_id'=>$order_info['type_id']))->find();
        if($now_village['money']<$order_info['payment_money']){
            exit(json_encode(array('error'=>2,'msg'=>'当前社区余额不足请更换支付方式。')));
        }

        if(M('House_village')->where(array('village_id'=>$order_info['type_id']))->setDec('money',$order_info['payment_money'])){
            
            D('Sms_buy_order')->where(array('order_id'=>$_POST['order_id']))->data(array('paid'=>1,'pay_time'=>time(),'pay_type'=>'yue'))->save();
            M('House_village')->where(array('village_id'=>$order_info['type_id']))->setInc('now_sms_number',$order_info['sms_number']);
            exit(json_encode(array('error'=>1,'msg'=>'支付成功')));
        }else{
            exit(json_encode(array('error'=>3,'msg'=>'支付失败请重试')));
        } 
    }




    // 社区-短信购买记录 导出
    public function village_sms_buy_export(){
        // 导出短信购买记录 权限
        if (!in_array(251, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $village_id = $this->house_session['village_id'];
        $condition_where['type_id']=$village_id;
        $condition_where['type']=1;
        if(!empty($_GET['start_time']) && !empty($_GET['end_time'])){
            $condition_where['add_time'] = array(array('gt',strtotime($_GET['start_time']." 00:00:00")),array('lt',strtotime($_GET['end_time']." 23:59:59")));
        }elseif($_GET['start_time']){
            $condition_where['add_time'] = array('gt',strtotime($_GET['start_time']." 00:00:00"));
        }elseif($_GET['end_time']){
            $condition_where['add_time'] = array('lt',strtotime($_GET['end_time']." 23:59:59"));
        }

        if($_GET['status'] != ''){
            $condition_where['status'] = array('eq',$_GET['status']);
        }
        $orderList= M('Sms_buy_order')->where($condition_where)->order('order_id DESC')->select();
        if(count($orderList) <= 0 ){
            $this->error('无数据导出！');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

        $title = $this->village['village_name'] . '社区-短信购买记录';

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $length = ceil(count($orderList)/1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('共' . count($orderList) . '条记录');
            $objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '编号');
            $objActSheet->setCellValue('B1', '订单号');
            $objActSheet->setCellValue('C1', '支付金额');
            $objActSheet->setCellValue('D1', '购买条数');
            $objActSheet->setCellValue('E1', '添加时间');
            $objActSheet->setCellValue('F1', '支付时间');
            $objActSheet->setCellValue('G1', '支付方式');
            $objActSheet->setCellValue('H1', '状态');

            if (!empty($orderList)) {
                $index = 2;

                $cell_list = range('A','H');
                foreach ($cell_list as $cell) {
                    $objActSheet->getColumnDimension($cell)->setWidth(30);
                }

                foreach ($orderList as $value) {
                    $objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['orderid']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['payment_money']);
                    $objActSheet->setCellValueExplicit('D' . $index, $value['sms_number']);
                    $objActSheet->setCellValueExplicit('E' . $index, date('Y-m-d H:i:s', $value['add_time']));

                    if($value['pay_time']){
                        $objActSheet->setCellValueExplicit('F' . $index, $value['pay_time']);
                    }else{
                        $objActSheet->setCellValueExplicit('F' . $index, ' ');
                    }

                    if($value['pay_type'] == 'yue'){
                        $objActSheet->setCellValueExplicit('G' . $index, '余额');
                    }else if($value['pay_type'] == 'weixin'){
                        $objActSheet->setCellValueExplicit('G' . $index, '微信');
                    }else if($value['pay_type'] == 'system'){
                        $objActSheet->setCellValueExplicit('G' . $index, '管理员操作');
                    }else{
                        $objActSheet->setCellValueExplicit('G' . $index, $value['pay_type']);
                    }

                    if($value['paid'] == '1'){
                        $objActSheet->setCellValueExplicit('H' . $index, '支付成功');
                    }else if($value['paid'] == '0'){
                        $objActSheet->setCellValueExplicit('H' . $index, '未支付');
                    }else{
                        $objActSheet->setCellValueExplicit('H' . $index, '管理员操作');
                    }
                    $index++;
                }
            }
            sleep(2);
            //输出
            $objWriter = new PHPExcel_Writer_Excel5($objExcel);
            ob_end_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        }
    }


    // 短信发送记录 导出
    public function village_sms_send_export(){
        // 导出短信发送记录 权限
        if (!in_array(250, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(!empty($_GET['start_time']) && !empty($_GET['end_time'])){
            $condition_where['time'] = array(array('gt',strtotime($_GET['start_time']." 00:00:00")),array('lt',strtotime($_GET['end_time']." 23:59:59")));
        }elseif($_GET['start_time']){
            $condition_where['time'] = array('gt',strtotime($_GET['start_time']." 00:00:00"));
        }elseif($_GET['end_time']){
            $condition_where['time'] = array('lt',strtotime($_GET['end_time']." 23:59:59"));
        }
        if($_GET['status'] != ''){
            $condition_where['status'] = array('eq',$_GET['status']);
        }

        $condition_where['village_id'] = $this->house_session['village_id'];

        $record_list= M('Sms_record')->where($condition_where)->order('pigcms_id DESC')->select();
        $status = array('0' =>'发送成功', '-1' => '验证失败未购买', '-2' => '短信不足', '-3' => '操作失败', '-4' => '非法字符', '-5' => '内容过多', '-6' => '号码过多', '-7' => '频率过快', '-8' => '号码内容空', '-9' => '账号冻结', '-10' => '禁止频繁单条发送', '-11' => '系统暂定发送', '-12' => '有错误号码', '-13' => '定时时间不对', '-14' => '账号被锁，10分钟后登录', '-15' => '连接失败', '-16' => '禁止接口发送', '-17' => '绑定IP不正确', '-18' => '系统升级', '-19' => '域名不对', '-20' => 'key不匹配', '-21' => '用户不存在', '-22' => '余额不足', '-100' => '发送的token不合法','-999'=>'频繁发送');


        if(count($record_list) <= 0 ){
            $this->error('无数据导出！');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

        $title = $this->village['village_name'] . '社区-短信发送记录';

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $length = ceil(count($record_list)/1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('共' . count($record_list) . '条记录');
            $objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '编号');
            $objActSheet->setCellValue('B1', '发送到手机');
            $objActSheet->setCellValue('C1', '发送类型');
            $objActSheet->setCellValue('D1', '发送时间');
            $objActSheet->setCellValue('E1', '发送内容');
            $objActSheet->setCellValue('F1', '类型');
            $objActSheet->setCellValue('G1', '状态');

            if (!empty($record_list)) {
                $index = 2;

                $cell_list = range('A','H');
                foreach ($cell_list as $cell) {
                    $objActSheet->getColumnDimension($cell)->setWidth(30);
                }

                foreach ($record_list as $value) {

                    $objActSheet->setCellValueExplicit('A' . $index, $value['pigcms_id']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['phone']);
                    if($value['sendto'] == 'user'){
                        $objActSheet->setCellValueExplicit('C' . $index, '顾客');
                    }else{
                        $objActSheet->setCellValueExplicit('C' . $index, '商家');
                    }
                    $objActSheet->setCellValueExplicit('D' . $index, date('Y-m-d H:i:s', $value['time']));
                    $objActSheet->setCellValueExplicit('E' . $index, $value['text']);

                    if($value['type'] == 'food'){
                        $objActSheet->setCellValueExplicit('F' . $index, '订餐');
                    }else if($value['type'] == 'takeout'){
                        $objActSheet->setCellValueExplicit('F' . $index, '外卖');
                    }else if($value['type'] == 'group'){
                        $objActSheet->setCellValueExplicit('F' . $index, '团购');
                    }else if($value['type'] == 'shop'){
                        $objActSheet->setCellValueExplicit('F' . $index, '快店');
                    }else if($value['type'] == 'village_express'){
                        $objActSheet->setCellValueExplicit('F' . $index, '社区');
                    }else if($value['type'] == 'meal'){
                        $objActSheet->setCellValueExplicit('F' . $index, '顺风车');
                    }


                    $objActSheet->setCellValueExplicit('G' . $index, $status[$value['status']]);

                    $index++;
                }
            }
            sleep(2);
            //输出
            $objWriter = new PHPExcel_Writer_Excel5($objExcel);
            ob_end_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        }
    }

}
?>