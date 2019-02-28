<?php

/*
 * 后台管理基础类
 *
 */

class IndexAction extends BaseAction {

    public function index() {
		$mysqlVersion = M()->query('select VERSION()');
		$server_info = array(
            'PHP运行环境' => PHP_OS,
            'PHP运行方式' => php_sapi_name(),
            'PHP版本' => PHP_VERSION,
            'MYSQL版本' => $mysqlVersion[0]['VERSION()'],
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '磁盘剩余空间 ' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        );
        if($this->system_session['area_id']!=0){
            $now_area = D('Area')->get_area_by_areaId($this->system_session['area_id']);
            $this->assign('now_area',$now_area);
        }
        $this->assign('server_info', $server_info);

        $this->display();
    }

    public function main() {
        if (!$this->system_session['area_id']&&$this->system_session['level']!=2 && !in_array(9999,$this->system_session['menus'])) {
            $this->redirect(U('Index/profile'));
        }

        $area_id = $this->system_session['area_id'];//区域管理员区域

		$mysqlVersion = M()->query('select VERSION()');
		$server_info = array(
            'PHP运行环境' => PHP_OS,
            'PHP运行方式' => php_sapi_name(),
            'PHP版本' => PHP_VERSION,
            'MYSQL版本' => $mysqlVersion[0]['VERSION()'],
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '磁盘剩余空间 ' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        );
        $this->assign('server_info', $server_info);

        if ($area_id) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if ($now_area['area_type'] == 3) {
                $area_index = 'area_id';
            } elseif ($now_area['area_type'] == 2) {
                $area_index = 'city_id';
            } elseif ($now_area['area_type'] == 1) {
                $area_index = 'province_id';
            }
        }

        $this->assign('now_area',$now_area);
        //网站统计
        if($area_id){
            $pigcms_assign['website_collect_count'] = floatval(M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX').'merchant m ON l.mer_id = m.mer_id')->where('m.'.$area_index.' = '.$area_id)->sum('total_money'));
        }else{
            $pigcms_assign['website_collect_count'] = floatval(M('Merchant_money_list')->sum('total_money'));
        }
        $pigcms_assign['website_user_count'] = M('User')->count();
        $where['status'] = 1;
        if($area_id){
            $where['_string'] = $area_index.' = '.$area_id;
            $pigcms_assign['website_merchant_count'] = M('Merchant')->where($where)->count();
        }else{
            $pigcms_assign['website_merchant_count'] = M('Merchant')->where($where)->count();
        }

        if($area_id){
            $where['_string'] = $area_index.' = '.$area_id;
            $pigcms_assign['website_merchant_store_count'] = M('Merchant_store')->where($where)->count();
            $area_info = M('Area')->where(array('area_id'=>$area_id))->find();
            $this->assign('area_info', $area_info);
        }else{
            $pigcms_assign['website_merchant_store_count'] = M('Merchant_store')->where($where)->count();
        }
        //团购统计

        if($area_id){
            $where_group['l.status'] = 1;
            $where_group['_string'] = 'm.'. $area_index.' = '.$area_id;
            $pigcms_assign['group_group_count'] = M('Group')->join('as l left join '.C('DB_PREFIX').'merchant m ON l.mer_id = m.mer_id')->where($where_group)->count();
        }else{
            $pigcms_assign['group_group_count'] = M('Group')->where(array('status'=>1))->count();
        }
        //订餐统计
        $condition['s.status'] = 1;
        $condition['s.have_meal']=1;
        if($area_id){
            $condition['_string'] = 's.'. $area_index.' = '.$area_id;
        }

        //$sql = "SELECT count(s.store_id) as count FROM ". C('DB_PREFIX') . "merchant_store AS s INNER JOIN ". C('DB_PREFIX') . "merchant_store_foodshop AS m ON s.store_id=m.store_id WHERE s.status=1";
        $foodshop_count = M('Merchant_store')->join('as s LEFT JOIN '. C('DB_PREFIX').'merchant_store_foodshop AS m ON s.store_id = m.store_id ')->where($condition)->count('s.store_id');
        //$result = D('')->query($sql);
        $pigcms_assign['meal_store_count'] = $foodshop_count;
        unset( $condition['s.have_meal']);
        $condition['s.have_shop']=1;
        if($area_id){
            $condition['_string'] = 's.'. $area_index.' = '.$area_id;
        }
        $foodshop_count = M('Merchant_store')->join('as s LEFT JOIN '. C('DB_PREFIX').'merchant_store_shop AS m ON s.store_id = m.store_id ')->where($condition)->count('s.store_id');

        $pigcms_assign['shop_store_count'] = $foodshop_count;
        //预约统计

        $now_time = $_SERVER['REQUEST_TIME'];
        $appoint_where['appoint_status'] = 0;
        $appoint_where['check_status'] = 1;
        $appoint_where['start_time'] = array('lt' , $now_time);
        $appoint_where['end_time'] = array('gt' , $now_time);
        if($area_id){
            $appoint_where['_string'] = 'm.'. $area_index.' = '.$area_id;
            $pigcms_assign['appoint_group_count'] = M('Appoint')->join('as l left join '.C('DB_PREFIX').'merchant m ON l.mer_id = m.mer_id')->where($appoint_where)->count();
        }else{
            $pigcms_assign['appoint_group_count'] = M('Appoint')->where($appoint_where)->count();
        }

        //商家待审核
        // $pigcms_assign['merchant_verify_list'] = D('Merchant')->where(array('status'=>'2','reg_time'=>array('gt',$this->system_session['last_time'])))->select();
        if ($this->system_session['area_id']) {
        	$area_index = $area_index;
            $pigcms_assign['merchant_verify_count'] = D('Merchant')->where(array('status' => '2', $area_index => $this->system_session['area_id']))->count();
            //店铺待审核
            // $pigcms_assign['merchant_verify_store_list'] = D('Merchant_store')->where(array('status'=>'2','last_time'=>array('gt',$this->system_session['last_time'])))->select();
            $pigcms_assign['merchant_verify_store_count'] = D('Merchant_store')->where(array('status' => 2, $area_index => $this->system_session['area_id']))->count();
            //团购待审核
            // $pigcms_assign['group_verify_list'] = D('Group')->where(array('status'=>'2','last_time'=>array('gt',$this->system_session['last_time'])))->select();
            $merchants = D('Merchant')->field('mer_id')->where(array('status' => '1', $area_index => $this->system_session['area_id']))->select();
            $mer_ids = array();
            foreach ($merchants as $m) {
                if (!in_array($m['mer_id'], $mer_ids))
                    $mer_ids[] = $m['mer_id'];
            }

            $pigcms_assign['group_verify_count'] = 0;
            if ($mer_ids) {
                $pigcms_assign['group_verify_count'] = D('Group')->where(array('status' => '2', 'mer_id' => array('in', $mer_ids)))->count();
            }
        } else {
            $pigcms_assign['merchant_verify_count'] = D('Merchant')->where(array('status' => '2'))->count();
            //店铺待审核
            // $pigcms_assign['merchant_verify_store_list'] = D('Merchant_store')->where(array('status'=>'2','last_time'=>array('gt',$this->system_session['last_time'])))->select();
            $pigcms_assign['merchant_verify_store_count'] = D('Merchant_store')->where(array('status' => 2))->count();
            //团购待审核
            // $pigcms_assign['group_verify_list'] = D('Group')->where(array('status'=>'2','last_time'=>array('gt',$this->system_session['last_time'])))->select();
            $pigcms_assign['group_verify_count'] = D('Group')->where(array('status' => '2'))->count();
        }

        $this->assign('user',$this->ajax_user());
        $this->assign('mer_money',$this->ajax_merchant_money());
        $this->assign($pigcms_assign);
        $this->display();
    }

    public  function get_alias_name(){
        $alias_name = array('group','meal','shop','appoint','store','weidian','wxapp','waimai');
        if(!isset($this->config['appoint_alias_name'])){
            $key = array_search('appoint', $alias_name);
            unset($alias_name[$key]);
        }
        if(!isset($this->config['waimai_alias_name'])){
            $key = array_search('waimai', $alias_name);
            unset($alias_name[$key]);
        }
        return  $alias_name ;
    }

    public  function get_alias_c_name(){
        $alias_name = array(
            'all'=>'全部',
            'group'=>$this->config['group_alias_name'],
            'shop'=>$this->config['shop_alias_name'],
            'meal'=>$this->config['meal_alias_name'],
            'appoint'=>$this->config['appoint_alias_name'],
            'waimai'=>$this->config['waimai_alias_name'],
            'store'=>'到店',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现'
        );
        if(!isset($this->config['appoint_alias_name'])){
            unset($alias_name['appoint']);
        }
        if(!isset($this->config['waimai_alias_name'])){
            unset($alias_name['waimai']);
        }
        return $alias_name;
    }

    public function ajax_all_date(){
        $_POST['day']=I('day');
        $_POST['period']=I('period');
        $type=I('type');
        if(empty($_POST['day'])&&empty($_POST['period'])){
            $_POST['day'] =1;
        }
        $area_id = $this->system_session['area_id'];//区域管理员区域
        if ($area_id) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if ($now_area['area_type'] == 3) {
                $area_index = 'area_id';
            } elseif ($now_area['area_type'] == 2) {
                $area_index = 'city_id';
            } elseif ($now_area['area_type'] == 1) {
                $area_index = 'province_id';
            }
        }

        if($_POST['province_idss'] && $this->config['many_city']){
            $area_index = 'province_id';
            $area_id =$_POST['province_idss'];
        }
        if($_POST['city_idss']){
            $area_index = 'city_id';
            $area_id= $_POST['city_idss'];
        }
        if($_POST['area_id']){
            $area_index = 'area_id';
            $area_id= $_POST['area_id'];
        }
        $alias_name = $this->get_alias_name();
        $today_zero_time = mktime(0,0,0,date('m',$_SERVER['REQUEST_TIME']),date('d',$_SERVER['REQUEST_TIME']), date('Y',$_SERVER['REQUEST_TIME']));
        $period = false;
        $where_supply['status'] = array('gt', 0);
        if(isset($_POST['period'])&&!empty($_POST['period'])){
            $period = explode(' - ',$_POST['period']);
            $_POST['begin_time'] = $period[0];
            $_POST['end_time'] = $period[1];
            if (strtotime($_POST['begin_time'])>strtotime($_POST['end_time'])) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $time_condition_mer = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_merchant_request['_string']=$time_condition;
            $where_supply['_string']  = " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_mer_request['_string']=$time_condition_mer;
            $period = true;
        }
        if($period && $_POST['begin_time']==$_POST['end_time']){
            $_POST['day']=1;
        }


        if($_POST['day']==1&&!$period){
            $condition_merchant_request['pay_time'] = array(array('egt',$today_zero_time),array('elt',time()));
            $condition_mer_request['use_time'] = array(array('egt',$today_zero_time),array('elt',time()));
            $where_supply['create_time']  =   $condition_merchant_request['pay_time'];
        }else{
            if(!$period) {
                if ($_POST['day'] == 2) {
                    //本月
                    $today_zero_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
                    $condition_merchant_request['pay_time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                    $condition_mer_request['use_time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                } else {
                    $condition_merchant_request['pay_time'] = array(array('egt', $today_zero_time - (($_POST['day']-1) * 86400)), array('elt', time()));
                    $condition_mer_request['use_time'] = array(array('egt', $today_zero_time - (($_POST['day']-1) * 86400)), array('elt', time()));
                }
                $where_supply['create_time'] = array(array('egt', strtotime("-{$_POST['day']} day")), array('elt', time()));
            }
        }
        if($area_id){
            if($condition_mer_request['_string']){
                $condition_mer_request['_string'] .= " AND  (m.{$area_index}= {$area_id})";
            }else{
                $condition_mer_request['_string'] = "(m.{$area_index} = {$area_id})";
            }

            if( $condition_merchant_request['_string']){
                $condition_merchant_request['_string'] .= " AND  (m.{$area_index}= {$area_id})";
            }else{
                $condition_merchant_request['_string'] =  "(m.{$area_index} = {$area_id})";
            }
        }

        $tmp_array=array();
        $condition_merchant_request['paid'] = 1;
        $condition_merchant_request['o.status']=array('lt',3);
        $res_group   = M('Group_order')->field('total_money as money,payment_money,pay_type ,pay_time')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->select();

        $where_foodshop['o.paid'] = 1;
        $where_foodshop['o.business_type'] = 'foodshop';
        $condition_merchant_request['pay_time'] && $where_foodshop['o.pay_time'] = $condition_merchant_request['pay_time'];
        $condition_merchant_request['_string'] && $where_foodshop['_string'] = $condition_merchant_request['_string'];
//        $res_meal = M('Plat_order')->field('o.total_money as money,pay_money as payment_money,o.pay_type ,o.pay_time')
//            ->join(' as o left join '.C('DB_PREFIX').'foodshop_order as fs ON o.business_id = fs.order_id left join '.C('DB_PREFIX').'merchant m ON m.mer_id = fs.mer_id')
//            ->where($where_foodshop)->select();

//        $res_meal = M('Foodshop_order')->field('o.total_money as money,pay_money as payment_money,o.pay_type ,o.pay_time')
//            ->join(' as fs left join '.C('DB_PREFIX').'plat_order as o  ON o.business_id = fs.order_id left join '.C('DB_PREFIX').'merchant m ON m.mer_id = fs.mer_id')
//            ->where($where_foodshop)->select();

        $res_meal = M('Foodshop_order')->field('fs.price as money,pay_money as payment_money,o.pay_type ,fs.pay_time')
            ->join(' as fs left join '.C('DB_PREFIX').'merchant m ON m.mer_id = fs.mer_id left join '.C('DB_PREFIX')."plat_order as o  ON o.business_id = fs.order_id AND o.business_type='foodshop' ")
            ->where($where_foodshop)->select();


        $condition_merchant_request['o.status']=array('lt',4);
        $condition_merchant_request['o.platform']=0;
        $res_shop    = M('Shop_order')->field('price as money,payment_money ,pay_type,pay_time')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->select();

        unset($condition_merchant_request['o.status'], $condition_merchant_request['o.platform']);
        $res_appoint = M('Appoint_order')->field('payment_money as money ,pay_money as payment_money,pay_type,pay_time,product_id,product_payment_price')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->select();
        $res_wxapp   = M('Wxapp_order')->field('o.money,payment_money,pay_type ,pay_time')->where($condition_merchant_request)->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->select();
        $res_weidian = M('Weidian_order')->field('o.money as money,payment_money,pay_type ,pay_time')->where($condition_merchant_request)->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->select();
        $res_store   = M('Store_order')->field('total_price as money,payment_money,pay_type ,pay_time')->where($condition_merchant_request)->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->select();

        $count['group']   = M('Group_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();

        $condition_merchant_request['o.status']=array('lt',4);
        $condition_merchant_request['o.platform']=0;
        $count['shop']    = M('Shop_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        unset($condition_merchant_request['o.platform']);
        $count['appoint'] = M('Appoint_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['wxapp']   = M('Wxapp_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['weidian'] = M('Weidian_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['store']   = M('Store_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        unset($condition_merchant_request['paid']);
        $count['meal']    = M('Foodshop_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['all'] = $count['group']+$count['meal']+$count['shop']+$count['appoint']+$count['wxapp']+$count['weidian']+$count['store'];

        $condition_mer_request['type']=array('neq','withdraw');
        $mer_money = M('Merchant_money_list')->field('`o`.*')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_mer_request)->select();
        $mer_count= M('Merchant_money_list')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_mer_request)->group('o.type')->getField('type,count(o.id) as count');

        $deliver['count'] =  M('Deliver_supply')->where($where_supply)->count();
        $deliver['money'] = M('Deliver_supply')->where($where_supply)->sum('freight_charge');
        $deliver['money'] = empty($deliver['money'] )?0:$deliver['money'] ;
        $shop_deliver = M('Deliver_supply')->where($where_supply)->select();
        $mer_count['all']=0;
        foreach($alias_name as $mc){
            if(!isset($mer_count[$mc])){
                $mer_count[$mc]=0;
            }
            $mer_count['all'] +=$mer_count[$mc];
        }
        $weixin_pay = 0;
        $alipay_pay = 0;
        $system_take=$weixin=$alipay = array();
        $weixin['all']  =$alipay['all']=0;
        foreach($alias_name as $rv){
            $tmp = 'res_'.$rv;
            $weixin[$rv] = $alipay[$rv] = 0;
            foreach($$tmp as $value){

                if($rv=='appoint'&&$value['product_id']>0){
                    $value['money'] = $value['product_payment_price'];
                }

                if($rv=='appoint'&&$value['product_id']>0){
                    $value['money'] = $value['product_payment_price'];
                }
                if($value['pay_type']=='weixin'){
                    $weixin_pay+=$value['payment_money'];
                    $weixin[$rv]+=$value['payment_money'];
                    $weixin['all']+=$value['payment_money'];
                }elseif($value['pay_type']=='alipay'  || $value['pay_type']=='alipayh5'){
                    $alipay_pay +=$value['payment_money'];
                    $alipay[$rv]+=$value['payment_money'];
                    $alipay['all']+=$value['payment_money'];
                }

               // $system_take+=$value['system_take'];
                if($_POST['day']==2){
                    $tmp_time = date('d',$value['pay_time']);
                }else if($_POST['day']==1||($period&&($_POST['end_time']==$_POST['begin_time']))){
                    $tmp_time = date('G',$value['pay_time']);
                }else{
                    $tmp_time = date('ymd',$value['pay_time']);
                }
                if(!isset($tmp_array['all_count'][$tmp_time])){
                    $tmp_array['all_count'][$tmp_time]=0;
                    $tmp_array[$rv][$tmp_time]['count']=0;
                }
                $tmp_array['all_income'][$tmp_time] += $value['money'];
                $tmp_array['all_count'][$tmp_time] += 1;
                $tmp_array[$rv][$tmp_time]['income'] += $value['money'];
                $tmp_array[$rv][$tmp_time]['count'] += 1;
            }
        }


        $tmp_mer=array();
        foreach($mer_money as $value){
            if($value['type']=='cash'){
                $value['type'] = 'store';
            }
            if($_POST['day']==2){
                $tmp_time = date('d',$value['use_time']);
            }else if($_POST['day']==1){
                $tmp_time = date('G',$value['use_time']);
            }else{
                $tmp_time = date('ymd',$value['use_time']);
            }

            if(!isset( $tmp_mer['mer_count'][$tmp_time])){
                $tmp_mer['mer_count'][$tmp_time]=0;
                $tmp_mer['mer_count_by_type'][$value['type']][$tmp_time]=0;
            }
            $tmp_mer['mer_income'][$tmp_time] += $value['money'];
            $tmp_mer['mer_count'][$tmp_time] += 1;
            $tmp_mer['mer_income_by_type'][$value['type']][$tmp_time] += pow(-1,$value['income']+1)*$value['money'];
            $tmp_mer['mer_system_take_by_type'][$value['type']][$tmp_time] += $value['system_take'];
            $tmp_mer['mer_count_by_type'][$value['type']][$tmp_time] +=1;
            $tmp_mer['mer_system_take'][$tmp_time] +=$value['system_take'];

            $system_take[$value['type']]+=$value['system_take'];
            $system_take['all']+=$value['system_take'];
        }
        $tmp_shop_deliver=array();

        foreach($shop_deliver as $vv){
            if($_POST['day']==2){
                $tmp_time = date('d',$vv['create_time']);
            }else if($_POST['day']==1){
                $tmp_time = date('G',$vv['create_time']);
            }else{
                $tmp_time = date('ymd',$vv['create_time']);
            }

            if(!isset( $tmp_mer['mer_count'][$tmp_time])){
                $tmp_shop_deliver['deliver_money'][$tmp_time]=0;
                $tmp_shop_deliver['deliver_count'][$tmp_time]=0;

            }
            $tmp_shop_deliver['shop_deliver_money'][$tmp_time] += round($vv['freight_charge'],2);
            $tmp_shop_deliver['shop_deliver_count'][$tmp_time] += 1;
        }



        if(($_POST['day']==1&&!$period)||($period&&($_POST['end_time']==$_POST['begin_time']))){
            $day = date('H',time());
            for($i=0;$i<=date('H',time())+1;$i++){
                $pigcms_list['xAxis_arr'][]  = $i.'时';
                $pigcms_list['xAxis_arr_e'][]  = $i.'时';
                $time_arr[]=$i;
            }
        }else{
            if($_POST['day']==2){
                $day = date('d',time());
                for($i=1;$i<=$day;$i++){
                    $pigcms_list['xAxis_arr'][]  = $i.'日';
                    $pigcms_list['xAxis_arr_e'][]  =  date('Y/m/d',time()-$i*86400);
                    $time_arr[]=$i;
                }
            }else{
                //$now_day =date('d',$today_zero_time);
                $day = $_POST['day'];
                for($i=$day-1;$i>=0;$i--){
                    $pigcms_list['xAxis_arr'][]  = date('m/d',$today_zero_time-$i*86400);
                    $pigcms_list['xAxis_arr_e'][]  = date('Y/m/d',$today_zero_time-$i*86400);
                    $time_arr[]=date('ymd',$today_zero_time-$i*86400);
                }
            }
        }


        if($period){
            unset($pigcms_list['xAxis_arr']);
            unset($time_arr);
            $start_day =strtotime($_POST['end_time']);
            $day = (strtotime($_POST['end_time'])-strtotime($_POST['begin_time']))/86400;
            if($day==0){
                for($i=0;$i<24;$i++){
                    $pigcms_list['xAxis_arr'][]  = $i.'时';
                    $pigcms_list['xAxis_arr_e'][]  =  $i.'时';
                    $time_arr[]=$i;
                }
            }else{
                for($i=$day;$i>=0;$i--){
                    $pigcms_list['xAxis_arr'][]  = date('m/d',$start_day-$i*86400).'';
                    $pigcms_list['xAxis_arr_e'][]  = date('Y/m/d',$start_day-$i*86400).'';
                    $time_arr[]=date('ymd',$start_day-$i*86400);
                }
            }
        }


        foreach($time_arr as $v){
            $pigcms_list['all_income'][] = round($tmp_array['all_income'][$v],2);
            $pigcms_list['all_count'][] = floatval($tmp_array['all_count'][$v]);
            $pigcms_list['all_income_all'] += round(floatval($tmp_array['all_income'][$v]),2);
            $pigcms_list['all_mer_income'][] = round($tmp_mer['mer_income'][$v],2);
            $pigcms_list['all_mer_income_all'] += round($tmp_mer['mer_income'][$v],2);
            $pigcms_list['all_mer_count'] []= floatval($tmp_mer['mer_count'][$v]);
            $pigcms_list['all_mer_system_take'] []= round($tmp_mer['mer_system_take'][$v],2);

            foreach($alias_name as $a){
                $pigcms_list[$a.'_income'][] = round($tmp_array[$a][$v]['income'],2);
                $pigcms_list[$a.'_count'][] = floatval($tmp_array[$a][$v]['count']);
                $pigcms_list[$a.'_mer_income'][] = round($tmp_mer['mer_income_by_type'][$a][$v],2);
                $pigcms_list[$a.'_mer_count'][] = round($tmp_mer['mer_count_by_type'][$a][$v],2);
                $pigcms_list[$a.'_mer_system_take'][] = round($tmp_mer['mer_system_take_by_type'][$a][$v],2);
                if($a=='shop'){
                    $pigcms_list['shop_deliver_money'][] = round($tmp_shop_deliver['shop_deliver_money'][$v],2);
                    $pigcms_list['shop_deliver_count'][] = floatval($tmp_shop_deliver['shop_deliver_count'][$v]);

                }
            }
        }



        //数据组装
        $pigcms_list['xAxis_txt'] = $pigcms_list['xAxis_arr'];

        foreach($alias_name as $n){
            $pigcms_list[$n]['income_txt'] = $pigcms_list[$n.'_income'];
            $pigcms_list[$n]['count_txt'] = $pigcms_list[$n.'_count'];
            $pigcms_list[$n]['mer_income_txt'] = $pigcms_list[$n.'_mer_income'];
            $pigcms_list[$n]['mer_count_txt'] = $pigcms_list[$n.'_mer_count'];
            $pigcms_list[$n]['mer_system_take_txt'] = $pigcms_list[$n.'_mer_system_take'];
            if($n=='shop'){
                $pigcms_list[$n]['deliver_money_txt'] = $pigcms_list['shop_deliver_money'];
                $pigcms_list[$n]['deliver_count_txt'] = $pigcms_list['shop_deliver_count'];
                unset($pigcms_list['shop_deliver_count']);
                unset($pigcms_list['shop_deliver_money']);
            }
            unset($pigcms_list[$n.'_mer_income']);
            unset($pigcms_list[$n.'_income']);
            unset($pigcms_list[$n.'_count']);
            unset($pigcms_list[$n.'_mer_count']);
            unset($pigcms_list[$n.'_mer_system_take']);
        }
        $pigcms_list['all']['income_txt'] = $pigcms_list['all_income'];
        $pigcms_list['all']['mer_income_txt'] = $pigcms_list['all_mer_income'];
        $pigcms_list['all']['count_txt'] = $pigcms_list['all_count'];
        $pigcms_list['all']['mer_count_txt'] = $pigcms_list['all_mer_count'];
        $pigcms_list['all']['mer_system_take_txt'] = $pigcms_list['all_mer_system_take'];
        $pigcms_list['count'] = array('sales_count'=>$count,'consume'=>$mer_count);
        $pigcms_list['alias_name'] = $this->get_alias_c_name();
        $pigcms_list['system_take'] = $system_take;
        $pigcms_list['weixin'] = $weixin;
        $pigcms_list['alipay'] = $alipay;
        $pigcms_list['deliver'] = $deliver;


        if(IS_GET){
            $this->export($pigcms_list,$type,$_POST['day'],$_POST['period']);
        }
        $pigcms_list['pay_type'] =array('weixin'=>$weixin_pay,'alipay'=>$alipay_pay);
        unset($pigcms_list['all_income']);
        unset($pigcms_list['all_income']);
        unset($pigcms_list['all_mer_income']);
        unset($pigcms_list['xAxis_arr']);
        $this->ajaxReturn($pigcms_list);
    }


    public function export($result,$type_name,$day,$peroid){
        $type = 'analysis';
        $title = '';
        $alias_name = $this->get_alias_name();
        array_unshift($alias_name,'all');
        foreach($alias_name as $kn =>$na){
            if($na==$type_name){
                unset($alias_name[$kn]);
                array_unshift($alias_name,$type_name);
                break;
            }
        }

        $alias_c_name = $this->get_alias_c_name();
        if($day){
            $p_title = $day.'天内';
        }elseif($peroid){
            $p_title = $peroid;
        }
        $title  = '数据分析('.$p_title.' '.date("Y-m-d H'i's").')';
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $cell_analysis  = array('date'=>'日期','income_txt'=>'订单总金额', 'consume'=>'消费量总数','sales_count'=>'订单量总数', 'income_mer_txt'=>'消费总金额','mer_system_take_txt'=>'抽成总金额','deliver_money_txt'=>'配送费','deliver_count_txt'=>'配送单数量');

        //打印条件

        // 设置当前的sheet
        $sheet = 0;

        foreach ($alias_name as $ca) {
            if ($sheet > 0) {
                $objExcel->createSheet();
            }
            $objExcel->setActiveSheetIndex($sheet);
            $objExcel->getActiveSheet()->setTitle($result['alias_name'][$ca]);
            $objActSheet = $objExcel->getActiveSheet();
            $objActSheet->getDefaultRowDimension()->setRowHeight(30);
            $sheet++;
            // 开始填充头部
            $cell_name = 'cell_' . $type;
            $cell_count = count($$cell_name);
            $cell_start = 1;
            $col_char = array();
            for ($f = 'A'; $f <= 'Z'; $f++, $cell_start++) {
                if ($cell_start > $cell_count) {
                    break;
                }
                $col_char[] = $f;
            }
            $col_k = 0;
            foreach ($$cell_name as $key => $v) {
                if($ca!='shop'&& $ca!='all' &&($key=='deliver_money_txt'|| $key=='deliver_count_txt') ){
                    continue;
                }
                $objActSheet->getColumnDimension($col_char[$col_k])->setWidth(20);
                $objActSheet->setCellValue($col_char[$col_k] . '1', $v);
                $col_k++;

            }
            $i = 2;

            foreach ($result['xAxis_arr_e'] as $t => $row) {
                $col_k = 0;
                foreach ($$cell_name as $k => $vv) {
                    if($ca!='shop'&& $ca!='all' &&($k=='deliver_money_txt'|| $k=='deliver_count_txt') ){
                        continue;
                    }
                    switch ($k) {
                        case 'date':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $row . ' ');
                            break;
                        case 'income_txt':
                            $objActSheet->setCellValue($col_char[$col_k] . $i,round( $result[$ca]['income_txt'][$t] ,2) );
                            break;
                        case 'income_mer_txt':
                            $objActSheet->setCellValue($col_char[$col_k] . $i,round( floatval($result[$ca]['mer_income_txt'][$t]) ,2));
                            break;
                        case 'sales_count':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $result[$ca]['count_txt'][$t] . ' ');
                            break;
                        case 'consume':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $result[$ca]['mer_count_txt'][$t] . ' ');
                            break;
                        case 'mer_system_take_txt':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, round(floatval($result[$ca]['mer_system_take_txt'][$t]) ,2));
                            break;
                        case 'deliver_money_txt':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, round(floatval($result['shop']['deliver_money_txt'][$t]),2) );
                            break;
                        case 'deliver_count_txt':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $result['shop']['deliver_count_txt'][$t] . ' ');
                            break;

                    }
                    $col_k++;
                }
                $i++;
            }
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
        header('Content-Disposition:attachment;filename="'.$title. '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();

    }


    public function get_sales_for_rader_chart(){
        $alias_name = $this->get_alias_name();
        $alias_c_name = $this->get_alias_c_name();
        $money_list = D('Merchant_money_list');
        $res = $money_list->field('type,count(id) as sales,SUM(money) as money')->where(array('income'=>1))->group('type')->select();
        $money=array();
        $sales = array();
        foreach($res as $r){
//            $tmp[$r['type']]['money']=$r['money'];
            $tmp[$r['type']]['sales']=$r['sales'];
        }
        foreach($alias_name as $v){
//            $money[$v] = $tmp[$v]['money']?$tmp[$v]['money']:0;
            $sales[$v] = $tmp[$v]['sales']?$tmp[$v]['sales']:0;
        }
        //$max = trim(max($money),'"');
        $max = trim(max($sales),'"');
        //$max = $max_money>$max_sales?$max_money:$max_sales ;
        $this->ajaxReturn(array('c_key'=>$alias_c_name,'sales'=>$sales,'max'=>$max));
    }

    public function ajax_user(){
        $user_count = M('User')->count();
        $weixin_user = M('User')->where(array('openid'=>array('neq','')))->count();
        $app_user = M('User')->where(array('app_openid'=>array('neq','')))->count();
        $phone_user = M('User')->where(array('phone'=>array('neq','')))->count();
        $men_user = M('User')->where(array('sex'=>1))->count();
        $women_user = M('User')->where(array('sex'=>2))->count();
        $unkonw_user = M('User')->where(array('sex'=>0))->count();
        return array(
            'weixin'=>$weixin_user,
            'app'=>$app_user,
            'phone'=>$phone_user,
            'men'=>$men_user,
            'women'=>$women_user,
            'unknow_user'=>$unkonw_user,
            'user_count'=>$user_count
        );
    }


    public function ajax_merchant_money($area_id=''){
        if($area_id){
            if ($area_id) {
                $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
                if ($now_area['area_type'] == 3) {
                    $area_index = 'area_id';
                } elseif ($now_area['area_type'] == 2) {
                    $area_index = 'city_id';
                } elseif ($now_area['area_type'] == 1) {
                    $area_index = 'province_id';
                }
            }
            $condition_merchant_request['_string'] = "m.{$area_index} = ".$area_id;
            $all_money = M('')->query('SELECT SUM(power(-1,1+income)*money) AS all_money FROM '.C('DB_PREFIX').'merchant_money_list l inner join '.C('DB_PREFIX').'merchant m ON m.mer_id= l.mer_id where '.$condition_merchant_request['_string']);
            $all_money = floatval($all_money[0]['all_money']);
            $condition_merchant_request['type'] = array('neq','withdraw');
            $all_count= M('Merchant_money_list')->join('as l inner join'.C('DB_PREFIX').'merchant m ON m.mer_id = l.mer_id')->where($condition_merchant_request)->count();
            $all_mer_money =M('Merchant')->where("{$area_index} = ".$area_id)->sum('money');

            unset($condition_merchant_request['type']);
            $condition_merchant_request['status']=0;
            $all_need_pay = M('Merchant_withdraw')->join('as l inner join'.C('DB_PREFIX').'merchant m ON m.mer_id = l.mer_id')->where($condition_merchant_request)->sum('money');


            $all_need_pay += M('Withdraw_list')->join('as l inner join '.C('DB_PREFIX').'merchant m ON m.mer_id = l.pay_id')->where($condition_merchant_request)->sum('l.money');

        }else{
            $all_money = M('')->query('SELECT SUM(power(-1,1+income)*money) AS all_money FROM '.C('DB_PREFIX').'merchant_money_list ');
            $all_money = floatval($all_money[0]['all_money']);
            $all_count= M('Merchant_money_list')->where(array('type'=>array('neq','withdraw')))->count();
            $all_mer_money =M('Merchant')->sum('money');
            $all_need_pay = M('Merchant_withdraw')->where(array('status'=>0))->sum('money');
            $all_need_pay_tmp= M('Withdraw_list')->where(array('status'=>0,'type'=>'mer'))->sum('money');
            $all_need_pay += $all_need_pay_tmp;
        }

        $all_mer_money = $all_mer_money+$all_need_pay/100;
        return array(
            'all_money'=>$all_mer_money>0?$all_mer_money:0,
            'all_mer_money'=>$all_mer_money>0?$all_mer_money:0,
            'all_need_pay'=>$all_need_pay>0?$all_need_pay:0,
            'all_count'=>$all_count>0?$all_count:0,
        );
    }


    public function pass() {
        $this->display();
    }

    public function amend_pass() {
        $old_pass = $this->_post('old_pass');
        $new_pass = $this->_post('new_pass');
        $re_pass = $this->_post('re_pass');
        if ($old_pass == '') {
            $this->error('请填写旧密码！');
        } else if ($new_pass != $re_pass) {
            $this->error('两次新密码填写不一致！');
        } else if ($old_pass == $new_pass) {
            $this->error('新旧密码不能一样！');
        }

        $database_admin = D('Admin');
        $condition_admin['id'] = $this->system_session['id'];
        $admin = $database_admin->field('`id`,`pwd`')->where($condition_admin)->find();
        if ($admin['pwd'] != md5($old_pass)) {
            $this->error('旧密码错误！');
        } else {
            $data_admin['id'] = $admin['id'];
            $data_admin['pwd'] = md5($new_pass);
            if ($database_admin->data($data_admin)->save()) {
                $this->success('密码修改成功！');
            } else {
                $this->error('密码修改失败！请重试。');
            }
        }
    }

    public function profile() {
        $database_admin = D('Admin');
        $condition_admin['id'] = $this->system_session['id'];
        $admin = $database_admin->where($condition_admin)->find();
        $sort_menus	=	explode(';',$admin['sort_menus']);
        $sort_menus_son	=	array();
        foreach($sort_menus as &$v){
        	$exp	=	explode(',',$v);
			$sort_menus_son[$exp[0]]	=	$exp[1];
        }
        $this->assign('sort_menus_son', $sort_menus_son);
        $this->assign('admin', $admin);
        $this->display();
    }

    public function amend_profile() {
        $database_admin = D('Admin');
        $data_admin['id'] = $this->system_session['id'];
        $data_admin['realname'] = $this->_post('realname');
        $data_admin['email'] = $this->_post('email');
        $data_admin['qq'] = $this->_post('qq');
        $data_admin['phone'] = $this->_post('phone');
        $data_admin['sort_menus'] = $this->_post('system_menu');
        $data_admin['phone_country_type'] = $this->_post('phone_country_type');
        if ($database_admin->data($data_admin)->save()) {
            $this->success('资料修改成功！');
        } else {
            $this->error('资料修改失败！请检查是否有修改内容后再重试。');
        }
    }

    public function cache() {
        import('ORG.Util.Dir');
        Dir::delDirnotself('./runtime');
		
		$domainArr = explode('.',$_SERVER['HTTP_HOST']);
		$count = count($domainArr);
		if(str_replace(array('.gov.cn','.com.cn','.weihubao.com','.dazhongbanben.com'),'',$_SERVER['HTTP_HOST']) == $_SERVER['HTTP_HOST']){
			$top_domain = $domainArr[$count-2].'.'.$domainArr[$count-1];
		}else{
			$top_domain = $domainArr[$count-3].'.'.$domainArr[$count-2].'.'.$domainArr[$count-1];
		}
		$top_domain = strtolower($top_domain);
		unlink('./source/plan/'.$top_domain.'md5.php');
		unlink('./source/plan/time/'.$top_domain.'process.time');

        $this->frame_main_ok_tips('清除缓存成功！');
    }

    public function menu() {
        $this->assign('bg_color', '#F3F3F3');

        $database = D('Admin');
        $condition['id'] = intval($_GET['admin_id']);
        $admin = $database->field(true)->where($condition)->find();
        if (empty($admin)) {
         //   $this->frame_error_tips('数据库中没有查询到该管理员的信息！');
        }
        $admin['menus'] = explode(',', $admin['menus']);
        $this->assign('admin', $admin);

        $menus = D('System_menu')->where(array( 'status' => 1,'action'=>array('neq','authority_price')))->select();
        $list = array();
        foreach ($menus as $menu) {
            if (empty($menu['fid'])) {
                if (isset($list[$menu['id']])) {
                    $list[$menu['id']] = array_merge($list[$menu['id']], $menu);
                } else {
                    $list[$menu['id']] = $menu;
                }
            } else {
                if (isset($list[$menu['fid']])) {
                    $list[$menu['fid']]['lists'][] = $menu;
                } else {
                    $list[$menu['fid']]['lists'] = array($menu);
                }
            }
        }
        $this->assign('menus', $list);

        $this->display();
    }

    public function savemenu() {
        if (IS_POST) {
            $admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
            $menus = isset($_POST['menus']) ? $_POST['menus'] : '';
            $menus = implode(',', $menus);
            $_SESSION['menus'] = $menus;
            $database = D('Admin');
            $database->where(array('id' => $admin_id))->save(array('menus' => $menus));
            $this->success('权限设置成功！');
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function get_area_arr($fid,$level=2,$tmp=array()){
        if($level>3){
            return true ;
        }
        $area = M('Area')->where(array('area_pid'=>$fid))->select();
        foreach ($area as $v) {

            $tmp[]= $v['area_id'];
            if($level<3){
                $tmp = $this->get_area_arr($v['area_id'],$v['area_type']+1,$tmp);
            }
        }
        return $tmp;
    }

    public function account() {
        if($_GET['area_id']>0){
            $where['a.area_id'] = $_GET['area_id'];
        }else if($_GET['city_idss']>0){
            $area_arr = $this->get_area_arr($_GET['city_idss'],3);
            $where['a.area_id'] = array('in',$area_arr);
        }else if($_GET['province_idss']>0){
            $area_arr = $this->get_area_arr( $_GET['province_idss'],2);
            $where['a.area_id'] = array('in',$area_arr);
        }
        if(!empty($_GET['keyword'])){
            if($_GET['searchtype'] == 'admin_id'){
                $where['id'] = $_GET['keyword'];
            }else if($_GET['searchtype'] == 'account'){
                $where['account'] = array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'realname'){
                $where['realname'] = array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'phone'){
                $where['phone'] = array('like','%'.$_GET['keyword'].'%');
            }
        }

        $system_area_id = $this->system_session['area_id'];

        if($system_area_id){
            $system_area = M('Area')->where(array('area_id'=>$system_area_id))->find();
            $area_arr = $this->get_area_arr( $system_area_id,$system_area['area_type']+1);
            $where['a.area_id'] = array('in',$area_arr);
        }

// 		import('ORG.Net.IpLocation');
// 		$IpLocation = new IpLocation();
        $admins = D('Admin')->field('a.*,ar.area_name,ar.area_type,ar.area_ip_desc')->join('AS a left join '.C('DB_PREFIX').'area ar ON a.area_id = ar.area_id ')->where($where)->select();
        $direct_city = array('1','21','42','62');
        $sprecial_city = array('3235','3239','3242','2792','1989','2103','2873','566');
 		foreach($admins as &$value){
 			//$last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
 			//$value['last_ip_txt'] = iconv('GBK','UTF-8',$last_location['country']);
            switch($value['level']){
                case 0:
                    $value['level_name'] = '普通管理员';
                    break;
                case 1:
                    $value['level_name'] = '区域管理员';
                    break;
                case 2:
                    $value['level_name'] = '超级管理员';
                    break;
            }
 		}
        $this->assign('system_session', $this->system_session);
        $this->assign('admins', $admins);
        $this->assign('direct_city', $direct_city);
        $this->assign('sprecial_city', $sprecial_city);
        $this->display();
    }
	public function account_del(){
		$where['id']	=	$_POST['id'];
		$delete	=	D('Admin')->where($where)->delete();
		if($delete){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！请重试~');
        }
    }
    public function admin() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $admin = D('Admin')->field(true)->where(array('id' => $id))->find();
        $admin['area_id'] && $admin['area'] = D('Area')->get_parents_area_by_areaid($admin['area_id']);
        $admin['area'] = D('Area')->get_parents_area_by_areaid($admin['area_id']);
        if($_SESSION['system']['area_id']){
            $system_area = M('Area')->where(array('area_id'=>$_SESSION['system']['area_id']))->find();
            $_SESSION['system']['area_type']  = $system_area['area_type'];
        }
        $list=  M('Authority_group')->where(array('gid'=>0))->select();
        $this->assign('authority_group', $list);
        $this->assign('admin', $admin);
        $this->assign('system', $_SESSION['system']);
        $this->assign('bg_color', '#F3F3F3');
        $this->display();
    }

    public function saveAdmin() {
        if (IS_POST) {
            $database_area = D('Admin');
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $account = htmlspecialchars($_POST['account']);
            if ($database_area->where("`id`<>'{$id}' AND `account`='{$account}'")->find()) {
                $this->error('数据库中已存在相同的账号，请更换。');
            }

            if($_POST['area_id']>0){

            }else if($_POST['city_idss']>0){
                $_POST['area_id'] = $_POST['city_idss'];
            }else{
                $_POST['area_id'] = $_POST['province_idss'];
            }
            !$_POST['open_admin_area'] && $_POST['area_id'] = 0;
            if($_POST['area_id']){
                $_POST['level']  =1;
            }else{
                $_POST['level']  =0;
            }
            if($_SESSION['menus']){
                $_POST['menus'] = $_SESSION['menus'];
                unset($_SESSION['menus']);
            }
            if($_POST['authority_group_id']>0){
                $group = M('Authority_group')->where(array('id'=>$_POST['authority_group_id']))->find();
                $_POST['menus'] =$group['menus'];
            }

            unset($_POST['id']);
            if ($id) {
                if ($_POST['pwd']) {
                    $_POST['pwd'] = md5($_POST['pwd']);
                } else {
                    unset($_POST['pwd']);
                }
                $database_area->where(array('id' => $id))->data($_POST)->save();
                $this->success('修改成功！');
            } else {
                if (empty($_POST['pwd'])) {
                    $this->error('密码不能为空~');
                }
                $_POST['pwd'] = md5($_POST['pwd']);
                if ($database_area->data($_POST)->add()) {
                    $this->success('添加成功！');
                } else {
                    $this->error('添加失败！请重试~');
                }
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    /*     * **网站地图***** */

    public function sitemap() {
		$xmlfilepath = './'.str_replace('.','_',$_SERVER['HTTP_HOST']).'sitemap.xml';
		$this->assign('xmlfilepath', $xmlfilepath);
        $this->display();
    }

    /*     * **执行网站地图*****
     * *<loc>www.example1.com</loc>该页的网址。该值必须少于256个字节(必填项)。格式为<loc>您的url地址</loc>
     * *<lastmod>2010-01-01</lastmod>该文件上次修改的日期(选填项)。格式为<lastmod>年-月-日</lastmod>
     * *<changefreq> always </changefreq>页面可能发生更改的频率(选填项)
     * *有效值为：always、hourly、daily、weekly、monthly、yearly、never
     * *<priority>1.0</priority >此网页的优先级。有效值范围从 0.0 到 1.0 (选填项) 。0.0优先级最低、1.0最高。
     * *
     * */

    public function exeGenerate() {
        set_time_limit('100');
        /*         * **寻找网址*** */
        $UrlSetArr = array();
        $siteurl = $this->config['site_url'];
        $siteurl = rtrim($siteurl, '/') . '/';
        $UrlSetArr[] = array('loc' => $siteurl, 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '1.0');
        /*         * **团购***** */
        $UrlSetArr[] = array('loc' => $siteurl . 'category/all', 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.9');
        $urldatatmp = M('Group_category')->field('cat_id,cat_fid,cat_name,cat_url')->where(array('cat_status' => '1'))->order('cat_id ASC')->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'category/' . $vv['cat_url'], 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.7');
            }
        }

        $jointable = C('DB_PREFIX') . 'merchant';
        $GroupDb = M('Group');
        $GroupDb->join('as grp LEFT JOIN ' . $jointable . ' as mer on grp.mer_id=mer.mer_id');
        $urldatatmp = $GroupDb->field('grp.group_id,grp.mer_id,grp.last_time')->where('grp.status="1" AND mer.status="1"')->order('grp.group_id  DESC')->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'group/' . $vv['group_id'] . '.html', 'lastmod' => !empty($vv['last_time']) ? date('Y-m-d', $vv['last_time']) : date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.9');
            }
        }

        /*         * **订餐***** */
        $UrlSetArr[] = array('loc' => $siteurl . 'meal/all', 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.9');

        $urldatatmp = M('Meal_store_category')->field('cat_id,cat_fid,cat_name,cat_url')->where(array('cat_status' => '1'))->order('cat_id ASC')->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'meal/' . $vv['cat_url'] . '/all', 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.7');
            }
        }
        $urldatatmp = M('Merchant_store')->field('store_id,mer_id')->where(array('have_meal' => '1', 'status' => '1'))->order('store_id ASC')->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'meal/' . $vv['store_id'] . '.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.9');
            }
        }
        /*         * **分类信息***** */
        $UrlSetArr[] = array('loc' => $siteurl . 'classify/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.9');
        $UrlSetArr[] = array('loc' => $siteurl . 'classify/selectsub.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.5');
        $urldatatmp = M('Classify_category')->field('cid,fcid,subdir,updatetime')->where(array('cat_status' => '1'))->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                if (($vv['subdir'] == 1) && ($vv['fcid'] == 0)) {
                    $UrlSetArr[] = array('loc' => $siteurl . 'classify/subdirectory-' . $vv['cid'] . '.html', 'lastmod' => !empty($vv['updatetime']) ? date('Y-m-d', $vv['updatetime']) : date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.5');
                } elseif (($vv['subdir'] == 2) && ($vv['fcid'] > 0)) {
                    $UrlSetArr[] = array('loc' => $siteurl . 'classify/list-' . $vv['cid'] . '.html', 'lastmod' => !empty($vv['updatetime']) ? date('Y-m-d', $vv['updatetime']) : date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.8');
                } elseif (($vv['subdir'] == 3) && ($vv['fcid'] > 0)) {
                    $UrlSetArr[] = array('loc' => $siteurl . 'classify/list-' . $vv['fcid'] . '-' . $vv['cid'] . '.html', 'lastmod' => !empty($vv['updatetime']) ? date('Y-m-d', $vv['updatetime']) : date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.8');
                }
            }
        }

        $urldatatmp = M('Classify_userinput')->field('id,cid,addtime')->where(array('status' => '1'))->order('id DESC')->limit(5000)->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'classify/' . $vv['id'] . '.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.9');
            }
        }

        /*         * *****商家中心********* */
        $urldatatmp = M('Merchant')->field('mer_id')->where(array('ismain' => 1, 'status' => 1))->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'merindex/' . $vv['mer_id'] . '.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.3');
            }
        }
        /*         * ******活动********** */
        $UrlSetArr[] = array('loc' => $siteurl . 'activity/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.6');
        $urldatatmp = M('Extension_activity_list')->field('pigcms_id')->where(array('status' => '1'))->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'activity/' . $vv['pigcms_id'] . '.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.5');
            }
        }
        $this->exeGenerateFile($UrlSetArr);
    }

    private function exeGenerateFile($UrlSetArr) {
        if (!empty($UrlSetArr)) {
            $xmlfilepath = './'.str_replace('.','_',$_SERVER['HTTP_HOST']).'sitemap.xml';
            $fp = fopen($xmlfilepath, "wb");
            if ($fp) {
                fwrite($fp, '<?xml version="1.0" encoding="utf-8"?>' . chr(10) . '<urlset>');
                foreach ($UrlSetArr as $uv) {
                    $linestr = chr(10) . '<url>' . chr(10) . '<loc>' . $uv ['loc'] . '</loc>' . chr(10) . '<lastmod>' . $uv['lastmod'] . '</lastmod>' . chr(10) . '<changefreq>' . $uv ['changefreq'] . '</changefreq>' . chr(10) . '<priority>' . $uv['priority'] . '</priority>' . chr(10) . '</url>';
                    fwrite($fp, $linestr);
                }
                fwrite($fp, chr(10) . '</urlset>');
                fclose($fp);
                $this->dexit(array('error' => 0, 'msg' => '生成完成！'));
            } else {
                $this->dexit(array
                    ('error' => 1, 'msg' => '网站根目录下'.$xmlfilepath.'文件不可写！'));
            }
        }
        $this->dexit(array('error' => 1, 'msg' => '没有可生成的数据'));
    }

    /*     * json 格式封装函数* */

    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }
	public function ajax_help($group, $module, $action) {
		$url = strtolower($group . '_' . $module . '_' . $action);
		$url = 'http://o2o-service.pigcms.com/workorder/serviceAnswerApi.php?url=' . $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$content = curl_exec($ch);
		curl_close($ch);

		echo $content;
	}
	public function help(){
		$this->assign('answer_id', $_GET['answer_id']);
		$this->display();
	}

	public function check_account()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$admin = D('Admin')->field(true)->where(array('id' => $id))->find();
		if (empty($admin)) {
			exit(json_encode(array('error_code' => 1, 'msg' => 'no')));
		}
		if ($admin['openid']) {
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok', 'nickname' => $admin['nickname'], 'avatar' => $admin['avatar'])));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => 'no')));
		}
	}

	public function cancel_account()
	{
		if ($this->system_session['level'] != 2) exit(json_encode(array('error_code' => 1, 'msg' => '没有权限取消')));
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if (D('Admin')->where(array('id' => $id))->save(array('openid' => '', 'avatar' => '', 'nickname' => ''))) {
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok')));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => '取消失败')));
		}
	}

    public function authority_group(){
        $list=  M('Authority_group')->where(array('gid'=>0))->select();
        $this->assign('list',$list);
        $this->display();
    }

    public function authority_add(){
        if(IS_POST){
            if(empty($_POST['name'])){
                $this->error('名称不能为空');
            }
            $menus = isset($_POST['menus']) ? $_POST['menus'] : '';
            $date['menus'] = implode(',', $menus);
            $date['name'] = $_POST['name'];
            $date['add_time'] = $_SERVER['REQUEST_TIME'];
            if($_POST['id']){
                $where_authority_id['authority_group_id'] = $_POST['id'];
                $result = M('Authority_group')->where(array('id'=>$_POST['id']))->save($date);
            }else{
                $result = M('Authority_group')->add($date);
                $where_authority_id['authority_group_id'] = $result;
            }
            if($result){
                M("Admin")->where($where_authority_id)->setField('menus',$date['menus']);
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else {
            $menus = D('System_menu')->where(array('status' => 1, 'action' => array('neq', 'authority_price')))->select();
            $list  = array();
            foreach ($menus as $menu) {
                if (empty($menu['fid'])) {
                    if (isset($list[$menu['id']])) {
                        $list[$menu['id']] = array_merge($list[$menu['id']], $menu);
                    } else {
                        $list[$menu['id']] = $menu;
                    }
                } else {
                    if (isset($list[$menu['fid']])) {
                        $list[$menu['fid']]['lists'][] = $menu;
                    } else {
                        $list[$menu['fid']]['lists'] = array($menu);
                    }
                }
            }
            $group = M('Authority_group')->where(array('id'=>$_GET['id']))->find();
            $menu = explode(',', $group['menus']);
            $this->assign('group', $group);
            $this->assign('menu', $menu);
            $this->assign('menus', $list);
            $this->display();
        }
    }
	
	public function loginKeep(){
		
	}
}