<?php
    /*对账管理*/
    class BillAction extends BaseAction{
        public function index(){
            if(!empty($_GET['keyword'])){
                if($_GET['searchtype'] == 'mer_id'){
                    $condition_merchant['mer_id'] = $_GET['keyword'];
                }else if($_GET['searchtype'] == 'account'){
                    $condition_merchant['account'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'name'){
                    $condition_merchant['name'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'phone'){
                    $condition_merchant['phone'] = array('like','%'.$_GET['keyword'].'%');
                }
            }
            $searchstatus = intval($_GET['searchstatus']);
            switch($searchstatus){
                case 0:
                    $condition_merchant['status'] = 1;
                    break;
                case 1:
                    $condition_merchant['status'] = 2;
                    break;
                case 2:
                    $condition_merchant['status'] = 0;
                    break;
            }

            if ($this->system_session['area_id']) {
                $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
                $condition_merchant[$area_index] = $this->system_session['area_id'];
            }
            $result = D('Order')->get_mer_bill($condition_merchant,15);

            $this->assign('merchant_list',$result['merchant_list']);
            $this->assign('pagebar',$result['pagebar']);
            $this->display();
        }

        public function billed(){
            if(!empty($_GET['keyword'])){
                if($_GET['searchtype'] == 'mer_id'){
                    $condition_merchant['mer_id'] = $_GET['keyword'];
                }else if($_GET['searchtype'] == 'account'){
                    $condition_merchant['account'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'name'){
                    $condition_merchant['name'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'phone'){
                    $condition_merchant['phone'] = array('like','%'.$_GET['keyword'].'%');
                }
            }
            $searchstatus = intval($_GET['searchstatus']);
            switch($searchstatus){
                case 0:
                    $condition_merchant['status'] = 1;
                    break;
                case 1:
                    $condition_merchant['status'] = 2;
                    break;
                case 2:
                    $condition_merchant['status'] = 0;
                    break;
            }

            if ($this->system_session['area_id']) {
                $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
                $condition_merchant[$area_index] = $this->system_session['area_id'];
            }

            $result = D('Order')->get_mer_billed($condition_merchant);

            $this->assign('merchant_list',$result['merchant_list']);
            $this->assign('pagebar',$result['pagebar']);
            $this->display();
        }

        public function order(){
            $percent = 0;
            $time = '';
            if (!$_POST['begin_time']) {
                $mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
                $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
            }else{
                $mer_id = isset($_POST['mer_id']) ? intval($_POST['mer_id']) : 0;
                $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
            }

            if(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
                if ($_POST['begin_time']>$_POST['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
                $time = array('period'=>$period);
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
            $result = D("Order")->bill_order($mer_id, $type, 1,$time);
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

        public function  check_unbill(){
            $mer_id = $_POST['mer_id'];
            $table = array('group','meal','shop','appoint','waimai','store','wxapp','weidian');
            $where['meal'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2) AND ( balance_pay<>'0.00 ' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money<>'0.00')";
            $where['group'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2,6) AND (balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money<>'0.00')";
            $where['weidian'] = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
            $where['wxapp'] = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
            $where['appoint'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1";
            $where['store']  = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND refund=0";
            $where['waimai'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 ";
            $where['shop'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status IN (2,3) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')";
            foreach($table as $v){
                $res = M(ucfirst($v).'_order')->where($where[$v].' AND is_pay_bill=0 ')->find();
                if(!empty($res)){
                    echo json_encode(array('unbill'=>1));exit;
                }
            }
            echo json_encode(array('unbill'=>0));exit;
        }

        public function billed_list(){
            $mer_id = $_GET['mer_id'];
            if (!$_POST['begin_time']) {
                $mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
                $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
            }else{
                $mer_id = isset($_POST['mer_id']) ? intval($_POST['mer_id']) : 0;
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
            $condition_bill['name'] = $_GET['type']?$_GET['type']:$name;
            $count_merchant = $bill_info->where($condition_bill)->count();
            import('@.ORG.system_page');
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

        public function update_bill_period(){
            $mer_id = $_GET['mer_id'];
            $where['mer_id']=$mer_id;
            $merchant = M('Merchant');
            $res  =$merchant->where($where)->find();
            if($res['bill_period']){
                $date['bill_time']=time()+$res['bill_period']*3600*24;
            }else{
                $date['bill_time']=time()+$this->config['bill_period']*3600*24;
            }
            //$date['bill_time']= strtotime(date('Y-m-d', $date['bill_time']));
            if(!M('Merchant')->where(array('mer_id'=>$mer_id))->save($date)){
                $this->error_tips('更新失败！');
            }else{
                $this->success('更新成功！');
            }

        }

        public function merchant_withdraw(){
            if(!empty($_GET['keyword'])){
                if($_GET['searchtype'] == 'mer_id'){
                    $condition_merchant['m.mer_id'] = $where['mer_id'] = $_GET['keyword'];
                }else if($_GET['searchtype'] == 'account'){
                    $condition_merchant['m.account']  = $where['account'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'name'){
                    $condition_merchant['m.name'] = $where['name'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'phone'){
                    $condition_merchant['m.phone'] = $where['phone'] = array('like','%'.$_GET['keyword'].'%');
                }
            }
            if(isset($_GET['withdraw_status'])){
                $searchstatus = intval($_GET['withdraw_status']);
               // $condition_merchant['w.withdraw_status'] = 0; //待体现
            }

            if ($this->system_session['area_id']) {
                $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
                if($now_area['area_type']==3){
                    $area_index = 'area_id';
                }elseif($now_area['area_type']==2){
                    $area_index = 'city_id';
                }elseif($now_area['area_type']==1){
                    $area_index = 'province_id';
                }

                $this->assign('admin_area',$now_area['area_type']);
                $condition_merchant['m.'.$area_index] =$where[$area_index]= $this->system_session['area_id'];
            }
            $this->assign('now_area',$now_area);

            if($_GET['province_idss'] && $this->config['many_city']){
                $condition_merchant['province_id'] =$_GET['province_idss'];
            }
            if($_GET['city_idss'] && $this->config['many_city']){
                $condition_merchant['m.city_id'] =$where['city_id']= $_GET['city_idss'];
            }
            if($_GET['area_id']){
                $condition_merchant['m.area_id'] =$where['area_id']= $_GET['area_id'];
            }

            if($_GET['export']){
                $this->merchent_withdraw_export($condition_merchant);exit;
            }

            $mer_withdraw_list = D('Merchant_money_list');

            $all_money = $mer_withdraw_list->get_all_mer_money($where);
            $this->assign('all_money',$all_money);
            if($_GET['withdraw_status']){
                $mer_withdraw_list = $mer_withdraw_list->get_mer_withdraw_list($condition_merchant);
            }else{
                $mer_withdraw_list = $mer_withdraw_list->get_money_list($condition_merchant);
            }
            $this->assign($mer_withdraw_list);
            $this->display();
        }

        public function merchent_withdraw_export($condition_merchant){
//            $param = $_POST;
//            $param['type'] = 'income';
//            $param['rand_number'] = time();
//            $param['system_session']['area_id'] = $this->system_session['area_id'];
//            if($res = D('Order')->order_export($param)){
//                echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
//            }else{
//                echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
//            }
//            die;
            set_time_limit(0);
            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
            $title = '商家余额记录';
            $objExcel = new PHPExcel();
            $objProps = $objExcel->getProperties();
            // 设置文档基本属性
            $objProps->setCreator($title);
            $objProps->setTitle($title);
            $objProps->setSubject($title);
            $objProps->setDescription($title);



            if($condition_merchant['province_id']){
                $province_id = $condition_merchant['province_id'];
                $where['a.area_pid'] = $condition_merchant['province_id'];
                unset($condition_merchant['province_id']);
                $where = array_merge($where,$condition_merchant);
                $count_merchant =M("Merchant" )->join("as m left join ".C('DB_PREFIX').'area as a ON m.city_id = a.area_id')->where($where)->count();
            }else{
                $count_merchant =M("Merchant as m" )->where($condition_merchant)->count();
            }



            $length = ceil($count_merchant[0]['tp_count'] / 1000);

            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);
                $objExcel->getActiveSheet()->setTitle('第' . ($i + 1) . '个一千个订单信息');
                $objActSheet = $objExcel->getActiveSheet();
                $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

                $objActSheet->setCellValue('A1', '编号');
                $objActSheet->setCellValue('B1', '商户名称');
                $objActSheet->setCellValue('C1', '联系电话');
                $objActSheet->setCellValue('D1', '最近待兑现');
                $objActSheet->setCellValue('E1', '商家余额');
                $objActSheet->setCellValue('F1', '最近提现时间');


                if($province_id){
                    $mer_withdraw_list =  M('Merchant')->join('as m left join '.'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM pigcms_merchant_withdraw WHERE status in(0,4)  GROUP BY mer_id) w ON m.mer_id = w.mer_id left join '.C('DB_PREFIX').'area as a on a.area_id = m.city_id')
                        ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money ')
                        ->where($where)
                        ->order('m.money DESC')
                        ->group('m.mer_id')
                        ->limit($i * 1000,1000)
                        ->select();
                }else{
                    $mer_withdraw_list =  M('Merchant')->join('as m left join '.'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM pigcms_merchant_withdraw WHERE  status in(0,4) GROUP BY mer_id) w ON m.mer_id = w.mer_id ')
                        ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money ')
                        ->where($condition_merchant)
                        ->order('m.money DESC')
                        ->group('m.mer_id')
                        ->limit($i * 1000,1000)
                        ->select();
                }

                //$mer_withdraw_list = M('Merchant')->join('as m left join ' .'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM '.C(DB_PREFIX).'merchant_withdraw WHERE status =0  GROUP BY mer_id) w ON m.mer_id = w.mer_id ')
                //    ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money ')
                //    ->where($condition_merchant)
                //    ->order('m.money DESC')
                //    ->group('m.mer_id')
                //    ->limit($i * 1000,1000)
                //    ->select();


                if (!empty($mer_withdraw_list)) {
                    $index = 1;
                    foreach ($mer_withdraw_list as $value) {

                            $index++;
                            $objActSheet->setCellValueExplicit('A' . $index, $value['mer_id']);
                            $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
                            $objActSheet->setCellValueExplicit('C' . $index, $value['phone']);
                            $objActSheet->setCellValueExplicit('D' . $index, $value['withdraw_money']?$value['withdraw_money']/100:'');
                            $objActSheet->setCellValueExplicit('E' . $index, $value['money']);
                            $objActSheet->setCellValueExplicit('F' . $index, $value['withdraw_time'] ? date('Y-m-d H:i:s', $value['withdraw_time']) : '');
                    }
                }
                sleep(2);

            }


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

        //抽成列表
        public function merchant_percentage(){

            if(!empty($_GET['keyword'])){
                if($_GET['searchtype'] == 'mer_id'){
                    $condition_merchant['mer_id'] = $_GET['keyword'];
                }else if($_GET['searchtype'] == 'account'){
                    $condition_merchant['account'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'name'){
                    $condition_merchant['name'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'phone'){
                    $condition_merchant['phone'] = array('like','%'.$_GET['keyword'].'%');
                }
            }
            if($_GET['area_id']){
                $condition_merchant['area_id'] = $_GET['area_id'];
            }
            if($_GET['city_id']){
                $condition_merchant['city_id'] = $_GET['city_id'];
            }

            if($_GET['province_id']){
                $condition_merchant['province_id'] = $_GET['province_id'];
            }
            if($this->config['open_admin_code']==1){
                if($this->system_session['invit_code']!=''){
                    $condition_merchant['invit_code'] = $this->system_session['invit_code'];
                }
            }

            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error("结束时间应大于开始时间");
                }
                $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
                $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $condition_merchant['_string']=$time_condition;
                $this->assign('begin_time',$_GET['begin_time']);
                $this->assign('end_time',$_GET['end_time']);
            }
            $mer_withdraw_list = D('Merchant_money_list');
            $all_money = $mer_withdraw_list->get_all_percent_money($condition_merchant);
            $all_score = $mer_withdraw_list->get_all_score($condition_merchant);
            $this->assign('all_money',$all_money);
            $this->assign('all_score',$all_score);
            $merchant_percentage_list = $mer_withdraw_list->get_mer_percentage_list($condition_merchant);

            $this->assign('result',$merchant_percentage_list);
            $this->display();
        }

        //导出抽成列表

        public function export_merchant_percentage(){
            $param = $_POST;
            $param['title'] = '抽成列表';
            $param['type'] = 'percentage';
            $param['rand_number'] = time();
            //$param['merchant_session']['mer_id'] = $this->merchant_session['mer_id'];
            if($res = D('Order')->order_export($param)){
                echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
            }else{
                echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
            }
            die;
        }

        public function merchant_withdraw_list(){
            if(!empty($_GET['keyword'])){
                if($_GET['searchtype'] == 'mer_id'){
                    $condition_merchant['w.mer_id'] = $_GET['keyword'];
                }else if($_GET['searchtype'] == 'account'){
                    $condition_merchant['m.account'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'name'){
                    $condition_merchant['m.name'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'phone'){
                    $condition_merchant['m.phone'] = array('like','%'.$_GET['keyword'].'%');
                }
            }

            if ($this->system_session['area_id']) {
                $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
                if($now_area['area_type']==3){
                    $area_index = 'area_id';
                }elseif($now_area['area_type']==2){
                    $area_index = 'city_id';
                }elseif($now_area['area_type']==1){
                    $area_index = 'province_id';
                }

                $this->assign('admin_area',$now_area['area_type']);
                $condition_merchant['m.'.$area_index] = $this->system_session['area_id'];
            }
            $this->assign('now_area',$now_area);


            if($_GET['area_id']){
                $condition_merchant['m.area_id'] = $_GET['area_id'];
            }
            if($_GET['city_idss']){
                $condition_merchant['m.city_id'] = $_GET['city_idss'];
            }

            if($_GET['province_idss']){
                $condition_merchant['m.province_id'] = $_GET['province_idss'];
            }



            $condition_merchant['w.status'] = 1;
            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
                $time_condition = " (w.withdraw_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $condition_merchant['_string']=$time_condition;
                $this->assign('begin_time',$_GET['begin_time']);
                $this->assign('end_time',$_GET['end_time']);
            }

            import('@.ORG.system_page');
            $count = M('Merchant_withdraw')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id = w.mer_id ')->where($condition_merchant)->count();

            $p = new Page($count, 20);
            $withdraw_list = M('Merchant_withdraw')->field('w.id,w.mer_id,w.withdraw_time,w.money,m.name,m.account,m.phone')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id = w.mer_id')->where($condition_merchant)->order('w.withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select();

            $pagebar=$p->show();

            $all_money =  M('Merchant_withdraw')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id = w.mer_id ')->where($condition_merchant)->sum('w.money');
            $this->assign('all_money',floatval($all_money/100));
            $this->assign('withdraw_list',$withdraw_list);
            $this->assign('pagebar',$pagebar);
            $this->display();
        }

        public function withdraw_info(){

            if(!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
                if ($_POST['begin_time']>$_POST['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
                $time_condition = " (withdraw_time BETWEEN ".$period[0].' AND '.$period[1].")";
               // $condition['_string']=$time_condition;
                $this->assign('begin_time',$_POST['begin_time']);
                $this->assign('end_time',$_POST['end_time']);
            }

            $mer_id = I('mer_id');
            $status = I('status');
            $merchant = D('Merchant')->field(true)->where(array('mer_id'=>$mer_id))->find();
            $withdraw_type = array(0=>'银行卡','1'=>'支付宝',2=>'微信');
            if(isset($_POST['withdraw_type']) && $_POST['withdraw_type']>-1){
                import('@.ORG.system_page');
                $where['w.status'] =1;
                if($time_condition){
                    $where['_string'] = $time_condition;
                }
                if($_POST['withdraw_type']==2){
                    $where['wl.withdraw_id'] = array('EXP','IS NULL');
                    $count = M('Merchant_withdraw')->join('as w LEFT JOIN '.C('DB_PREFIX').'withdraw_list wl ON w.id = wl.withdraw_id')->where($where)->count();
                    $p = new Page($count, 20);
                    $withdraw_list['withdraw_list']=  M('Merchant_withdraw')->field('w.*')->join('as w LEFT JOIN '.C('DB_PREFIX').'withdraw_list wl ON w.id = wl.withdraw_id')->where($where)->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select();
                    $withdraw_list['pagebar'] = $p->show();
                }else{
                    $where['wl.pay_type'] = $_POST['withdraw_type'];
                    $count = M('Merchant_withdraw')->join('as w LEFT JOIN '.C('DB_PREFIX').'withdraw_list wl ON w.id = wl.withdraw_id')->where($where)->count();
                    $p = new Page($count, 20);
                    $withdraw_list['withdraw_list'] =M('Merchant_withdraw')->field('w.*,wl.account,wl.remark as account_detail')->join('as w LEFT JOIN '.C('DB_PREFIX').'withdraw_list wl ON w.id = wl.withdraw_id')->where($where)->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select();
                    $withdraw_list['pagebar'] = $p->show();
                }

            }else{

                $withdraw_list = D('Merchant_money_list')->get_withdraw_list($mer_id,1,$status,$time_condition);

                foreach ($withdraw_list['withdraw_list'] as &$v) {
                    // if($v['status']==4){
                    $tmp = M('Withdraw_list')->where(array('type'=>'mer','withdraw_id'=>$v['id']))->find();

                    $v['pay_type'] = $tmp['pay_type']!=''?$withdraw_type[$tmp['pay_type']]:$withdraw_type[2];
                    $v['account'] = $tmp['account'];
                    $v['account_detail'] = $tmp['remark'];
                    //  }
                }
            }

            $this->assign('now_merchant', $merchant);
            $this->assign('mer_id', $mer_id);
            $this->assign('status', $status);
            $this->assign('withdraw_type', I('withdraw_type'));
            $this->assign('un_withdraw_list',$withdraw_list['withdraw_list']);
            $this->assign('pagebar',$withdraw_list['pagebar']);
            $this->display();
        }

        public function withdraw_order_info(){
            $withdraw = M('Merchant_withdraw')->field('w.*,m.system_take')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant_money_list m ON m.order_id = w.id')->where(array('w.id'=>$_GET['id'],'m.type'=>'withdraw'))->find();
            $now_merchant = M('Merchant')->where(array('mer_id'=>$withdraw['mer_id']))->find();
            $this->assign('withdraw',$withdraw);
            $this->assign('now_merchant',$now_merchant);
            $this->display();
        }

        public function merchant_money_list(){
            if(!empty($_POST['order_id'])){
                if(empty($_POST['order_type'])){
                    $this->error_tips("没有选分类");
                }
                if($_POST['order_type']=='all'){
                    $this->error("该分类下不能填写订单id");
                }else if($_POST['order_type']=='withdraw'){
                    $condition['id'] = $_POST['order_id'];
                }else{
                    $condition['order_id'] = $_POST['order_id'];
                }
            }

            $this->assign('order_id',$_POST['order_id']);
            $this->assign('order_type',$_POST['order_type']);
			if($_POST['order_type']=='activity'){
                $condition['type'] = 'coupon or yydb';
			}elseif($_POST['order_type']!='all'&&!empty($_POST['order_type'])){
				$condition['type'] = $_POST['order_type'];
			}
            if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
                if ($_POST['begin_time']>$_POST['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
                $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $condition['_string']=$time_condition;
                $this->assign('begin_time',$_POST['begin_time']);
                $this->assign('end_time',$_POST['end_time']);
            }
            //dump($condition);die;
            $mer_id = I('mer_id');
            if(!$_GET['page']){
                $_SESSION['condition'] = $condition;
                $_SESSION['condition_merid'] = $mer_id;
            }else{
                $mer_id =  $_SESSION['condition_merid'];
            }
            $res = D('Merchant_money_list')->get_income_list($mer_id,1,$condition);
   
            $merchant = D('Merchant')->field(true)->where(array('mer_id'=>$mer_id))->find();
            $this->assign('now_merchant', $merchant);
            $this->assign('mer_id', $mer_id);
            $this->assign('income_list',$res['income_list']);
            $this->assign('total_money',$res['total_money']);
            $this->assign('income_total_',$res['income_total_']);
            $this->assign('alias_name',$this->get_alias_name());
            $this->assign('pagebar',$res['pagebar']);
            $this->display();
        }

        public function agree_withdraw(){
            if(D('Merchant_money_list')->agree($_GET['mer_id'],$_GET['id'])){
                $this->success('保存成功！');
            }else{
                $this->error_tips('保存失败！');
            }
        }

        public function reject_withdraw(){
            $res = D('Merchant_money_list')->reject($_GET['mer_id'],$_GET['id']);
            if(!$res['error_code']){
                $this->success('保存成功！');
            }else{
                $this->error_tips($res['msg']);
            }
        }

        public function edit_reason(){
            if(IS_POST){
                if(empty($_POST['reason'])){
                    $this->error('理由不能为空！');
                }
                $res = D('Merchant_money_list')->reject($_POST['mer_id'],$_POST['id'],$_POST['reason']);
                if(!$res['error_code']){
                    $this->success('保存成功！');
                }else{
                    $this->error($res['msg']);
                }
            }else{
                $this->assign('id',$_GET['id']);
                $this->assign('mer_id',$_GET['mer_id']);
                $this->display();
            }

        }

        public function edit_withdraw(){
            $this->assign('id',I('id'));
            $this->assign('mer_id',I('mer_id'));
            $now_withdraw = M('Merchant_withdraw')->where(array('id'=>I('id'),'mer_id'=>I('mer_id')))->find();

            $this->assign('now_withdraw',$now_withdraw);
            if(IS_POST){
                if(empty($_POST['remark'])){
                    $this->error('理由不能为空！');
                }
                if($_POST['money']>$now_withdraw['money']){
                    $this->error('修改的金额不能大于用户提现的金额！');
                }
                $res = D('Merchant_money_list')->agree($_POST['mer_id'],$_POST['money'],$_POST['id'],$_POST['remark'],$_POST['is_online']);
                if(!$this->checkToken($_POST['token'])){
                    $this->error('请不要重复提交');exit;
                }


                if($_POST['is_online'] ){
                    $res=D('Merchant')->where(array('mer_id'=>$_POST['mer_id']))->find();
                    $data['pay_type'] = 'merchant';
                    $data['pay_id'] = $_POST['mer_id'];
                    $data['phone'] = $res['phone'];
                    $data['money'] = $_POST['money'];
                    $data['desc'] = '商家('.$res['name'].')申请提现|转账 '.(float)($_POST['money']/100).' 元';
                    $data['status'] = 0;
                    $data['add_time'] = time();
                    M('Companypay')->add($data);
                }
                if(!$res['error_code']){
                    $this->success('保存成功！');
                }else{
                    $this->error($res['msg']);
                }
            }else{
                $this->creatToken();
                $this->assign('token',$_SESSION['WITHDRAW_TOKEN']);
                $this->display();
            }

        }

        function creatToken() {
            $code = chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE));

            session('WITHDRAW_TOKEN', $this->authcode($code));
        }

        //判断TOKEN
        function checkToken($token) {
            if ($token == session('WITHDRAW_TOKEN')) {
                session('WITHDRAW_TOKEN', NULL);
                return TRUE;
            } else {
                return FALSE;
            }
        }

        /* 加密TOKEN */
        function authcode($str) {
            $key = "ANDIAMON";
            $str = substr(md5($str), 8, 10);
            return md5($key . $str);
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
                'sub_card'=>'免单套餐',
            );
            if(!$this->config['store_open_waimai']) unset($c_name['waimai']);
            if(!$this->config['wxapp_url']) unset($c_name['wxapp']);
            if(!$this->config['appoint_page_row']) unset($c_name['appoint']);
            if(!$this->config['is_open_weidian']) unset($c_name['weidian']);
            if(!$this->config['is_cashier']) unset($c_name['store']);
            if(!$this->config['pay_in_store'] || !$this->config['is_cashier'] ) unset($c_name['cash'],$c_name['shop_offline']);
            if(!$this->config['open_sub_card']  ) unset($c_name['sub_card']);
            return $c_name ;
        }

        //提现导出excel
        public function export_withdraw(){

            if(!empty($_GET['keyword'])){
                if($_GET['searchtype'] == 'mer_id'){
                    $condition_merchant['w.mer_id'] = $_GET['keyword'];
                }else if($_GET['searchtype'] == 'account'){
                    $condition_merchant['m.account'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'name'){
                    $condition_merchant['w.name'] = array('like','%'.$_GET['keyword'].'%');
                }else if($_GET['searchtype'] == 'phone'){
                    $condition_merchant['m.phone'] = array('like','%'.$_GET['keyword'].'%');
                }
            }



            if($_GET['province_idss']){
                $condition_merchant['province_id'] =$_GET['province_idss'];
            }
            if($_GET['city_idss']){
                $condition_merchant['m.city_id'] = $_GET['city_idss'];
            }
            if($_GET['area_id']){
                $condition_merchant['m.area_id']= $_GET['area_id'];
            }


            $condition_merchant['w.status'] = 1;
            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
                $time_condition = " (w.withdraw_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $condition_merchant['_string']=$time_condition;
                $this->assign('begin_time',$_GET['begin_time']);
                $this->assign('end_time',$_GET['end_time']);
            }

            $count = M('Merchant_withdraw')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id = w.mer_id ')->where($condition_merchant)->order('w.withdraw_time DESC')->count();
            $withdraw_list = M('Merchant_withdraw')->field('w.id,w.mer_id,w.withdraw_time,w.money,m.name,m.account,m.phone')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id = w.mer_id ')->where($condition_merchant)->order('w.withdraw_time DESC')->select();

            set_time_limit(0);
            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
            $title = '导出提现数据';
            if($_GET['begin_time']&&$_GET['end_time']){
                $title.='('.$_GET['begin_time'].'至'.$_GET['end_time'].')';
            }
            $objExcel = new PHPExcel();
            $objProps = $objExcel->getProperties();
            // 设置文档基本属性
            $objProps->setCreator($title);
            $objProps->setTitle($title);
            $objProps->setSubject($title);
            $objProps->setDescription($title);

            // 设置当前的sheet
            $length = ceil($count / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个提现信息');
                $objActSheet = $objExcel->getActiveSheet();

                $objActSheet->setCellValue('A1', '商家编号');
                $objActSheet->setCellValue('B1', '商家名称');
                $objActSheet->setCellValue('C1', '联系电话');
                $objActSheet->setCellValue('D1', '提现金额');
                $objActSheet->setCellValue('E1', '提现时间');

                if (!empty($withdraw_list)) {
                    $index = 2;
                    foreach ($withdraw_list as $value) {
                        $objActSheet->setCellValueExplicit('A' . $index, $value['mer_id']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
                        $objActSheet->setCellValueExplicit('C' . $index, $value['phone'] . ' ');
                        $objActSheet->setCellValueExplicit('D' . $index, floatval($value['money']/100) . ' ');
                        $objActSheet->setCellValueExplicit('E' . $index, $value['withdraw_time'] ? date('Y-m-d H:i:s', $value['withdraw_time']) : '');
                        $index++;
                    }
                }
                sleep(2);
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
            header('Content-Disposition:attachment;filename="'.$title.'_' . date("Ymd h:i:s", time()) . '.xls"');
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        }

        //配送费统计
        public function express_statistics(){
            $countWhere = '1';
            if(!empty($_GET['keyword'])){
                if($_GET['searchtype'] == 'mer_id'){
                    $condition_merchant['m.mer_id'] = $_GET['keyword'];
                    $countWhere .= ' AND m.mer_id=' . $_GET['keyword'];
                }else if($_GET['searchtype'] == 'account'){
                    $condition_merchant['account'] = array('like','%'.$_GET['keyword'].'%');
                    $countWhere .= " AND m.account LIKE '%" . $_GET['keyword'] . "%'";
                }else if($_GET['searchtype'] == 'name'){
                    $condition_merchant['name'] = array('like','%'.$_GET['keyword'].'%');
                    $countWhere .= " AND m.name LIKE '%" . $_GET['keyword'] . "%'";
                }else if($_GET['searchtype'] == 'phone'){
                    $condition_merchant['phone'] = array('like','%'.$_GET['keyword'].'%');
                    $countWhere .= " AND m.phone LIKE '%" . $_GET['keyword'] . "%'";
                }
            }
            if($_GET['area_id']){
                $condition_merchant['area_id'] = $_GET['area_id'];
                $countWhere .= " AND m.area_id=" . $_GET['area_id'];
            }
            if($_GET['city_id']){
                $condition_merchant['city_id'] = $_GET['city_id'];
                $countWhere .= " AND m.city_id=" . $_GET['city_id'];
            }
            if($_GET['province_id']){
                $condition_merchant['province_id'] = $_GET['province_id'];
                $countWhere .= " AND m.province_id=" . $_GET['province_id'];
            }

            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error("结束时间应大于开始时间");
                }
                $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
                $time_condition = " (create_time BETWEEN ".$period[0].' AND '.$period[1].") AND";
                $countWhere .= " AND (create_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
               // $condition_merchant['_string']=" (w.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $this->assign('begin_time',$_GET['begin_time']);
                $this->assign('end_time',$_GET['end_time']);
            }

           // $shop_deliver = M('Deliver_supply')->where($condition_merchant)->select();
            $database_merchant = M('Merchant');
            $where['area_id'] = $condition_merchant['area_id'];
            $count_merchant = $database_merchant->where($where)->count();
            import('@.ORG.system_page');
            $p = new Page($count_merchant,20);
            $mer_percentage_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(freight_charge) AS money,count(supply_id) AS count FROM '.C('DB_PREFIX').'deliver_supply  where '.$time_condition.' type = 0 AND item = 2 GROUP BY mer_id) w ON m.mer_id = w.mer_id  ')
                ->field('m.mer_id,m.name,m.phone,w.money,w.count')
                ->where($condition_merchant)
                ->order('w.money DESC')
                ->limit($p->firstRow.','.$p->listRows)
                ->select();
            
            $sql = 'SELECT SUM(freight_charge) AS money, count(supply_id) AS count FROM ' . C('DB_PREFIX') . 'merchant AS m INNER JOIN ' . C('DB_PREFIX') . 'deliver_supply AS d ON d.mer_id=m.mer_id WHERE ' . $countWhere;
            $mer_percentage_count = D()->query($sql);

            $deliver['all_money'] = isset($mer_percentage_count[0]['money']) ? floatval($mer_percentage_count[0]['money']) : 0;
            $deliver['all_count'] = isset($mer_percentage_count[0]['count']) ? floatval($mer_percentage_count[0]['count']) : 0;
//             $deliver['all_nomer_money'] = M('Deliver_supply')->where(array('mer_id'=>array('eq',0)))->sum('freight_charge');
//             $deliver['all_nomer_count'] = M('Deliver_supply')->where(array('mer_id'=>array('eq',0)))->count();
            $this->assign('mer_percentage_list',$mer_percentage_list);
            $this->assign('deliver',$deliver);
            $this->display();
        }

        public function express_statistics_mer(){


            $where['mer_id']=$_GET['mer_id'];
            $where['type']=0;
            $where['item']=2;

            $this->assign('order_id',$_GET['order_id']);

            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
                $time_condition = " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $where['_string']=$time_condition;
                $this->assign('begin_time',$_GET['begin_time']);
                $this->assign('end_time',$_GET['end_time']);
            }

            $mer_id = $_GET['mer_id'];
            import('@.ORG.system_page');

            $count = M('Deliver_supply')->where( $where)->count();


            $p = new Page($count, 20);
            $deliver_num = M('Deliver_supply')->where($where)->count();
            $deliver_money = M('Deliver_supply')->where($where)->sum('money');

            unset($where['mer_id']);
            unset($where['order_id']);
            $where['l.mer_id']=$mer_id;
            $_GET['order_id'] && $where['l.real_orderid']=$_GET['order_id'];
            $_GET['begin_time'] && $where['_string']= " (l.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $deliver_list = M('Deliver_supply')->join('as l left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id LEFT JOIN '.C('DB_PREFIX').'shop_order o ON o.order_id  = l.order_id ')->field('ms.name as store_name,l.supply_id,l.order_id,l.money,o.create_time,l.uid,l.freight_charge,o.real_orderid,o.pay_time,o.num')->where($where)->order('order_id DESC')->limit($p->firstRow, $p->listRows)->select();

            $merchant = D('Merchant')->field(true)->where(array('mer_id'=>$mer_id))->find();
            $this->assign('now_merchant', $merchant);
            $this->assign('mer_id', $mer_id);
            $this->assign('deliver_list', $deliver_list);
            $this->assign('deliver_money', $deliver_money);
            $this->assign('deliver_num', $deliver_num);


            $this->assign('pagebar', $p->show());
            $this->display();
        }


        public function express_statistics_export(){
            $param = $_POST;
            $param['type'] = 'express';
            $param['rand_number'] = $_SERVER['REQUEST_TIME'];
            $param['system_session']['area_id'] = $this->system_session['area_id'] ;

            if($res = D('Order')->order_export($param)){
                echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
            }else{
                echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
            }
            die;
            set_time_limit(0);
            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
            $title = '商家余额记录';
            $objExcel = new PHPExcel();
            $objProps = $objExcel->getProperties();

            $title = '配送信息';

            // 设置文档基本属性
            $objProps->setCreator($title);
            $objProps->setTitle($title);
            $objProps->setSubject($title);
            $objProps->setDescription($title);
            // 设置当前的sheet
            $objExcel->setActiveSheetIndex(0);

            if($_GET['order_id']){
                $where['order_id'] = $_GET['order_id'];
            }

            $where['mer_id']=$_GET['mer_id'];
            $where['type']=0;
            $where['item']=2;


            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
                $time_condition = " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $where['_string']=$time_condition;

            }

            $mer_id = $_GET['mer_id'];

            $count = M('Deliver_supply')->where( $where)->count();





            unset($where['mer_id']);
            unset($where['order_id']);
            $where['l.mer_id']=$mer_id;
            $_GET['order_id'] && $where['l.order_id']=$_GET['order_id'];
            $_GET['begin_time'] && $where['_string']= " (l.create_time BETWEEN ".$period[0].' AND '.$period[1].")";


            $length = ceil($count[0]['count'] / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个配送信息');

                $objActSheet = $objExcel->getActiveSheet();
                $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);


                $objActSheet->setCellValue('A1', '店铺名称');
                $objActSheet->setCellValue('B1', '订单号');
                $objActSheet->setCellValue('C1', '数量');
                $objActSheet->setCellValue('D1', '订单总价');
                $objActSheet->setCellValue('E1', '配送费');
                $objActSheet->setCellValue('F1', '支付时间');
                $result_list = M('Deliver_supply')->join('as l left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id LEFT JOIN '.C('DB_PREFIX').'shop_order o ON o.order_id  = l.order_id ')->field('ms.name as store_name,l.supply_id,l.order_id,l.money,o.create_time,l.uid,l.freight_charge,o.real_orderid,o.pay_time,o.num')->where($where)->order('order_id DESC')->limit( $i * 1000,1000)->select();


                if (!empty($result_list)) {
                    $index = 2;
                    foreach ($result_list as $value) {
                        $objActSheet->setCellValueExplicit('A' . $index, $value['store_name']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['real_orderid']);
                        $objActSheet->setCellValueExplicit('C' . $index, $value['num'] );
                        $objActSheet->setCellValueExplicit('D' . $index, $value['money']);
                        $objActSheet->setCellValueExplicit('E' . $index, floatval($value['freight_charge']));
                        $objActSheet->setCellValueExplicit('F' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
                        $index++;
                    }
                }
                sleep(2);
            }

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
?>