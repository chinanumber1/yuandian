<?php
/*对账管理*/
class BillAction extends BaseAction{

    public function order(){
        $percent = 0;
        $period = 0;
        $time = '';
        $mer_id = $this->merchant_session['mer_id'];
        if (!$_POST['begin_time']) {

            $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
        }else{

            $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
        }

        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
            $time = array('period'=>$period);
            //$time = serialize($time);
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }
        //$time = unserialize($time);
        if ($time['period']){
            if (is_array($time['period'])) {
                $time_condition = " AND (pay_time BETWEEN ".$time['period'][0].' AND '.$time['period'][1].")";
            }else{
                $time_condition = " AND pay_time=".$time['period'];
            }
        }

        switch($type){
            case 'meal':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2) AND ( balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00')".$time_condition;
                break;
            case 'group':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2,6) AND (balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00')".$time_condition;
                break;
            case 'weidian':
                $where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'".$time_condition;
                break;
            case 'wxapp':
                $where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'".$time_condition;
                break;
            case 'appoint':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1".$time_condition;
                break;
            case 'store':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00') ".$time_condition;
                break;
            case 'waimai':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00') ".$time_condition;
                break;
            case 'shop':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status IN (2,3)".$time_condition;
                break;
        }
        if($type=='waimai'){
            $un_bill_count = D(ucfirst($type).'_order')->where($where." AND is_pay_bill=0 ")->count();
        }else if($type=='appoint'){
            $un_bill_count = D(ucfirst($type).'_order o')->join(C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id")->where("o.mer_id=".$mer_id." AND o.paid=1 AND o.is_own=0 AND o.pay_type<>'offline' AND o.service_status=1 AND o.is_pay_bill=0 AND a.payment_status=1")->count();
        }else if($type=='store'){
            $un_bill_count = D(ucfirst($type).'_order')->where($where." AND is_pay_bill=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00') ")->count();
        }else{
            $un_bill_count = D(ucfirst($type).'_order')->where($where." AND is_pay_bill=0 ")->count();
        }

        $merchant = D('Merchant')->field(true)->where(array('mer_id'=> $mer_id))->find();
        if ($merchant['percent']) {
            $percent = $merchant['percent'];
        } elseif ($this->config['platform_get_merchant_percent']) {
            $percent = $this->config['platform_get_merchant_percent'];
        }
        $res = M('Bill_time')->where(array('mer_id'=>$mer_id))->find();
        if($res){
            foreach($res as $key=>$v){
                if(stristr($key,'_time')){
                    $arr[]=$v;
                }
            }
            rsort($arr);
            $bill_time=$arr[0];
            $this->assign('bill_time',$bill_time);
        }
        $this->assign('percent', $percent);
        $result = D("Order")->bill_order($mer_id, $type, 0,$time);
        $this->assign($result);
        $this->assign('un_bill_count',$un_bill_count);
        $this->assign('now_merchant', $merchant);
        $this->assign('mer_id', $mer_id);
        $this->assign('type', $type);
        $this->display();
    }

    public function get_un_bill_count(){
        $mer_id= $_POST['mer_id'];
        $where['meal'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2) AND ( balance_pay<>'0.00 ' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money<>'0.00')";
        $un_bill_count['meal'] = D('Meal_order')->where($where['meal']." AND is_pay_bill=0 ")->count();
        $all_bill_money = D('Meal_order')->where($where['meal']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money+score_deducte+coupon_price');

        $where['group'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2,6) AND (balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money<>'0.00')";
        $un_bill_count['group'] = D('Group_order')->where($where['group']." AND is_pay_bill=0 ")->count();
        $all_bill_money += D('Group_order')->where($where['group']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money+score_deducte+coupon_price-refund_money+refund_fee');

        $where['weidian'] = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
        $un_bill_count['weidian'] = D('Weidian_order')->where($where['weidian']." AND is_pay_bill=0 ")->count();
        $all_bill_money += D('Weidian_order')->where($where['weidian']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money');

        $where['wxapp'] = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
        $un_bill_count['wxapp'] = D('Wxapp_order')->where($where['wxapp']." AND is_pay_bill=0 ")->count();
        $all_bill_money += D('Wxapp_order')->where($where['wxapp']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money');

        $where['appoint'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1";
        $un_bill_count['appoint'] = D('Appoint_order o')->join(C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id")->where("o.mer_id=".$mer_id." AND o.paid=1 AND o.is_own=0 AND o.pay_type<>'offline' AND o.service_status=1 AND o.is_pay_bill=0 AND a.payment_status=1")->count();
        $all_bill_money += D('Appoint_order o')->join(C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id")->where("o.mer_id=".$mer_id." AND o.paid=1 AND o.is_own=0 AND o.pay_type<>'offline' AND o.service_status=1 AND o.is_pay_bill=0 AND a.payment_status=1")->sum('o.balance_pay+o.payment_money+o.score_deducte+o.coupon_price');

        $where['store']  = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND refund=0";
        $un_bill_count['store']  = D('Store_order')->where($where['store']." AND is_pay_bill=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00') ")->count();
        $all_bill_money += D('Store_order')->where($where['store']." AND is_pay_bill=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00') ")->sum('balance_pay+payment_money');

        $where['waimai'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 ";
        $un_bill_count['waimai'] = D('Waimai_order')->where($where['waimai']." AND is_pay_bill=0  AND (online_pay<>'0.00' OR balance_pay<>'0.00')")->count();
        $all_bill_money += D('Waimai_order')->where($where['waimai']." AND is_pay_bill=0  AND (online_pay<>'0.00' OR balance_pay<>'0.00')")->sum('online_pay+balance_pay');

        $where['shop'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status IN (2,3) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')";
        $un_bill_count['shop'] = D('Shop_order')->where($where['shop']." AND is_pay_bill=0 ")->count();
        $all_bill_money += D('Shop_order')->where($where['shop']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money+balance_reduce+coupon_price+score-deducte-no_bill_money');

        $return['all_bill_money'] = $all_bill_money;
        $return['un_bill_count'] = $un_bill_count;
        $this->AjaxReturn($return);
        exit;
    }

    public function billed_list(){
        $mer_id = $this->merchant_session['mer_id'];
        if (!$_POST['begin_time']) {
            $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
        }else{
            $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
        }
        $bill_info = M('Bill_info');
        $condition_bill['mer_id']=$mer_id;
        $bill_time = M('Bill_time')->where(array('merid'=>$mer_id))->find();
        $this->assign('bill_time',$bill_time);
        $name = 'meal';
        foreach($bill_time as $key=>$val){
            if(stristr($key,'_time')){
                if(!empty($val)){
                    $tmp=explode('_',$key);
                    $name = $tmp[0];
                    break;
                }
            }
        }
        $merchant = D('Merchant')->field(true)->where($condition_bill)->find();
        $condition_bill['name'] = empty($_GET['type'])?$name:$_GET['type'];
        $count_merchant = $bill_info->where($condition_bill)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count_merchant,15);

        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
            $time_condition = " (bill_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_bill['_string']=$time_condition;
            $condition_bill['name']=$type;
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }

        $bill_list = $bill_info->where($condition_bill)->order('bill_time DESC')->limit($p->firstRow,$p->listRows)->select();
        foreach ($bill_list as $k=>&$v) {
            $v['id_list'] = explode(',',$v['id_list']);
        }
        $pagebar = $p->show();
        $this->assign('now_merchant', $merchant);
        $this->assign('mer_id', $merchant['mer_id']);
        $this->assign('type',$condition_bill['name']);
        $this->assign('pagebar',$pagebar);
        $this->assign('bill_list',$bill_list);
        $this->display();
    }

    public function billed_info(){
        $condition ['id'] = $_GET['id'];
        $res = M('Bill_info')->where($condition)->find();
        $res['count']=count(explode(',',$res['id_list']));

        $this->assign('bill_info',$res);
        $merchant = D('Merchant')->field(true)->where(array('mer_id'=>$res['mer_id']))->find();
        if ($merchant['percent']) {
            $percent = $merchant['percent'];
        } elseif ($this->config['platform_get_merchant_percent']) {
            $percent = $this->config['platform_get_merchant_percent'];
        }
        $this->assign('percent', $percent);
        $this->assign('now_merchant',$merchant );
        $this->assign('mer_id', $merchant['mer_id']);
        $order_list =D('Order')->bill_order($res['mer_id'], $res['name'], 1,'',$res['id_list']);
        $this->assign('order_list',$order_list);
        $this->assign('type',$res['name']);
        $this->assign('mer_id',$res['mer_id']);
        $this->display();
    }

    public function export()
    {
        if(!isset($_GET['withdraw_type'])) {
            $param = $_POST;
            $param['type'] = 'income';
            $param['rand_number'] = time();
            $param['merchant_session']['mer_id'] = $this->merchant_session['mer_id'];
            if ($res = D('Order')->order_export($param)) {
                echo json_encode(array('error_code' => 0, 'msg' => '添加导出计划成功', 'file_name' => $res['file_name'], 'export_id' => $res['export_id'], 'rand_number' => $param['rand_number']));
            } else {
                echo json_encode(array('error_code' => 1, 'msg' => '导出失败'));
            }
            die;
        }
        $mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
        $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
        $title = '';

        switch ($type) {
            case 'meal':
                $title = '餐饮账单';
                break;
            case 'group':
                $title = '团购账单';
                break;
            case 'weidian':
                $title = '微店账单';
                break;
            case 'wxapp':
                $title = '预约账单';
                break;
            case 'appoint':
                $title = '营销账单';
                break;
            case 'store':
                $title = '收银账单';
                break;
            case 'waimai':
                $title = '外卖账单';
                break;
            case 'shop':
                $title = '快店账单';
                break;
            case 'income':
                $title = '收入明细';
                break;
            case 'withdraw':
                $title = '提现明细';
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
        $cell_meal    = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> ''.$this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_group   = array('store_name'=>'门店名称','real_orderid'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> ''.$this->config['score_name'].'抵扣','refund_money'=>'退款金额','refund_fee'=>'退款手续费','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_appoint = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> ''.$this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_shop    = array('store_name'=>'门店名称','real_orderid'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> ''.$this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_waimai  = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_store   = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_weidian = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_wxapp   = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_income   = array('store_name'=>'店铺名称','type'=>'类型','order_id'=>'订单编号', 'num'=>'数量','total_money'=>'订单总价','goods_money'=>'商品价格','score_deducte'=>'积分抵扣','coupon_price'=>'系统优惠券抵扣','card_price'=>'商家优惠券抵扣','no_bill_money'=>'不参与对账金额', 'money'=>'金额','system_take'=>'系统抽成','score'=>'送出'.$this->config['score_name'],'score_count'=>$this->config['score_name'].'使用数量','use_time'=>'记账时间','desc'=>'描述');
        $cell_withdraw   = array('name'=>'提现人','withdraw_time'=>'提现时间','money'=>'提现金额','status'=>'提现状态', 'remark'=>'备注');
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
        $title_index=1;
        $i = 2;
        if($type=='income'){
            $title_index=3;
            $i = 4;
        }
        foreach($$cell_name as $key=>$v){

            $objActSheet->getColumnDimension($col_char[$col_k])->setWidth(20);

            $objActSheet->setCellValue($col_char[$col_k].$title_index, $v);
            $col_k++;
        }



        if ($_GET['bill_id']) {
            $res = M('Bill_info')->where(array('id' => $_GET['bill_id']))->find();
            $result = D('Order')->export_order_by_mid($mer_id, $type,1,$res['id_list']);
        }else if($type=='withdraw'){
            $where['mer_id']=$mer_id;
            if($_GET['withdraw_type']==2){
                $result = M('Withdraw_list')->field('id,name,status,pay_type,remark,desc,old_money as money,add_time as withdraw_time')->where(array('pay_id' => $mer_id))->select();

            }else{
                $result = M('Merchant_withdraw')->where($where)->order('withdraw_time DESC')->select();
            }

        }else if($type=='income'){
            $where['m.mer_id']=$mer_id;
            if($_GET['order_type']&&$_GET['order_type']!='all'){
                $where['type']=$_GET['order_type'];
            }
            if($_GET['order_id']){
                $where['order_id']=$_GET['order_id'];
            }
            if($_GET['store_id']){
                $where['m.store_id']=$_GET['store_id'];
            }


            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error("结束时间应大于开始时间");
                }
                $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));

                $time_condition=" (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $where['_string']=$time_condition;
            }
            $result = M('Merchant_money_list')->join('as m left join '.C('DB_PREFIX').'merchant_store s ON m.store_id = s.store_id')->field('m.type,m.total_money,(m.system_take+pow(-1,m.income+1)*m.money) as goods_money,(m.total_money-m.system_take-pow(-1,m.income+1)*m.money) as no_bill_money,m.system_take,m.order_id,m.num,pow(-1,m.income+1)*m.money as money,m.use_time,m.desc,m.score,m.score_count,s.name as store_name')->where($where)->order('use_time DESC')->select();

            $total_where = array_merge($where,array('m.mer_id'=>$mer_id,'m.income'=>1));
            $total = M('Merchant_money_list')->join('as m left join '.C('DB_PREFIX') . 'merchant mm ON m.mer_id = mm.mer_id left join '.C('DB_PREFIX') . 'merchant_store s ON m.store_id = s.store_id ')->where($total_where)->order('use_time DESC')->sum('m.money');

            $income_total_where = array_merge($where,array('m.mer_id'=>$mer_id,'m.income'=>1,'m.type'=>array('neq','merrecharge')));
            $income_total = M('Merchant_money_list')->join('as m left join '.C('DB_PREFIX') . 'merchant mm ON mm.mer_id = m.mer_id left join '.C('DB_PREFIX') . 'merchant_store s ON m.store_id = s.store_id ')->where($income_total_where)->order('use_time DESC')->sum('m.money');

            if($type=='income'){
                $objActSheet->setCellValue('A1', '总计 ');
                $objActSheet->setCellValue('B1', $total);
                $objActSheet->setCellValue('C1', '消费收入');
                $objActSheet->setCellValue('D1', $income_total);
            }
        }else {
            $result = D("Order")->export_order_by_mid($mer_id, $type);
        }
        //dump($result);die;
        $alias_name = $this->get_alias_name();
        foreach ($result as $row) {
            $col_k=0;
            switch($row['type']){
                case 'group':
                    $now_order = D('Group_order')->field('score_deducte,coupon_price,card_price')->where(array('real_orderid'=>$row['order_id']))->find();
                    break;
                case 'shop':
                    $now_order = D('Shop_order')->field('score_deducte,coupon_price,card_price')->where(array('real_orderid'=>$row['order_id']))->find();
                    break;
                case 'meal':
                    $now_order = D('Plat_order')->join('as p LEFT JOIN '.C('DB_PREFIX').'foodshop_order as f ON f.order_id = p.order_id')->field('system_coupon_price as coupon_price,merchant_coupon_price as card_price,system_score_money as score_deducte')
                        ->where(array(
                            'business_type'=>'foodshop',
                            'f.real_orderid'=>$row['order_id'],
                            '_string'=>'system_coupon_price<>0 OR merchant_coupon_price<>0 OR system_score_money <>0'
                            ))
                        ->find();
                    if(empty($now_order)){
                        $now_order['score_deducte']=  0;
                        $now_order['coupon_price']=  0;
                        $now_order['card_price']=  0;
                    }
                    break;
                case 'store':
                    $now_order = D('Store_order')->field('score_deducte,coupon_price,card_price')->where(array('order_id'=>$row['order_id']))->find();
                    break;
                case 'appoint':
                    $now_order = D('Appoint_order')->field('score_deducte,coupon_price,card_price')->where(array('order_id'=>$row['order_id']))->find();
                    break;

            }
           // dump($$cell_name);
            foreach($$cell_name as $k=>$vv){
                //dump($k);
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
                    case 'money':
                        if($type=='withdraw'){
                            $objActSheet->setCellValue($col_char[$col_k] . $i, floatval($row[$k]/100));
                        }else{
                            $objActSheet->setCellValue($col_char[$col_k] . $i, floatval($row[$k]));
                        }
                        break;
                    case 'status':
                        if($type=='withdraw'){
                            $row[$k]==0 && $row[$k] = '审核中';
                            $row[$k]==1 && $row[$k] = '已通过';
                            $row[$k]==2 && $row[$k] = '被驳回';
                            $row[$k]==3 && $row[$k] = '已提现';
                            $row[$k]==4 && $row[$k] = '审核中';
                        }
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'pay_time':
                    case 'use_time':
                    case 'withdraw_time':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
                        break;
                    case 'desc':
                    case 'remark':
                    case 'name':
                    //dump( $row[$k]);die;
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'score_deducte':
                        $objActSheet->setCellValue($col_char[$col_k] . $i,$now_order['score_deducte'].' ');
                        break;
                    case 'coupon_price':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $now_order['coupon_price'].' ');
                        break;
                    case 'card_price':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $now_order['card_price'].' ');
                        break;
                    default:
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
                        break;
                }
                $col_k++;
            }
            if($type!='income' && $type!='withdraw'){
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

    protected  function get_alias_name(){
        $c_name = array(
            'all'=>'选择分类',
            'group'=>$this->config['group_alias_name'],
            'shop'=>$this->config['shop_alias_name'],
            'shop_offline'=>$this->config['shop_alias_name'].'线下零售',
            'meal'=>$this->config['meal_alias_name'],
            'appoint'=>$this->config['appoint_alias_name'],
            'waimai'=>'外卖',
            'store'=>'优惠买单',
            'cash'=>'到店支付',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现',
            'coupon'=>'优惠券',
            'withdraw'=>'提现',
            'activity'=>'平台活动',
            'spread'=>'商家推广',
            'market_order'=>'批发',
        );
        if(!$this->config['store_open_waimai']) unset($c_name['waimai']);
        if(!$this->config['wxapp_url']) unset($c_name['wxapp']);
        if(!$this->config['appoint_page_row']) unset($c_name['appoint']);
        if(!$this->config['is_open_weidian']) unset($c_name['weidian']);
        if(!$this->config['is_cashier']) unset($c_name['store']);
        if(!$this->config['pay_in_store'] || !$this->config['is_cashier'] ) unset($c_name['cash'],$c_name['shop_offline']);
        return $c_name ;
    }
}
?>