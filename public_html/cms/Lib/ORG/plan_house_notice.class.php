<?php
/*社区欠费自动发送微信通知提醒 一天一次

 * */
class plan_house_notice extends plan_base{
	public function runTask(){
		$i = 0;
		$limit = 1000;

		$list = $this->get_house_unpaid_list($i,$limit);
		while( $list ){
			foreach ($list as $key => $value) {
            	$href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_my_pay&village_id='.$value['village_id'];
	            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $database_house_village_user_bind = D('House_village_user_bind');
                $param = array(
                    'address' => $value['address'],
                    'total' => $value['total'],
                    'property_name' => $value['property_name'],
                );

            	switch ($value['send_user_type']) {
	                case '1': // 只发送业主
	                    if ($value['openid']) {
                            $database_house_village_user_bind->send_weixin($href,$value['openid'],$param);
		            	}
	                    break;
	                case '2': // 业主和家属
	                	if ($value['openid']) {
                            $database_house_village_user_bind->send_weixin($href,$value['openid'],$param);
		            	}
                        // 查询家属
                        $condition = '`u`.`uid` = `b`.`uid` AND `b`.`status`=1 AND `b`.`type` in(1,2) AND b.vacancy_id ='.$value['vacancy_id'];
                        $condition_table  = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'house_village_user_bind'=>'b');
                        $field = '`u`.`openid`';
                        $bind_list = D()->field($field)->table($condition_table)->where($condition)->select();
                        if ($bind_list) {
                            foreach ($bind_list as $bind) {
                                if(!empty($bind['openid'])){
                                    $database_house_village_user_bind->send_weixin($href,$bind['openid'],$param);
                                }
                            }
                        }
	                    break;
	                case '3':// 只发送家属
	                    $condition = '`u`.`uid` = `b`.`uid` AND `b`.`status`=1 AND `b`.`type` in(1,2) AND b.vacancy_id ='.$value['vacancy_id'];
                        $condition_table  = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'house_village_user_bind'=>'b');
                        $field = '`u`.`openid`';
                        $bind_list = D()->field($field)->table($condition_table)->where($condition)->select();
                        if ($bind_list) {
                            foreach ($bind_list as $bind) {
                                if(!empty($bind['openid'])){
                                    $database_house_village_user_bind->send_weixin($href,$bind['openid'],$param);
                                }
                            }
                        }
	                    break;
	            }
			}
			$i += $limit;
			$list = $this->get_house_unpaid_list($i,$limit);
		}
		return true;
	}

	/*得到未缴列表*/
    public function get_house_unpaid_list($i=0,$limit=1000){ 
        // 水电燃气停车费
        $condition = '`v`.`village_id` = `b`.`village_id`  AND (`b`.`water_price`>0 OR `b`.`electric_price`>0 OR `b`.`gas_price`>0 OR `b`.`park_price`>0) AND `v`.`send_date` ="'.intval(date('d')).'"';
        $condition_table  = array(C('DB_PREFIX').'house_village'=>'v',C('DB_PREFIX').'house_village_user_bind'=>'b');
        $field = '`b`.`pigcms_id`';

        $list = D('')->field($field)->table($condition_table)->where($condition)->limit($i,$limit)->select();
        // var_dump(D('')->getLastSql());
        // var_dump($list);
    	$ids = array();
        foreach ($list as $key => $value) {
            $ids[] = $value['pigcms_id'];
        }

        // 物业费
        $condition = '( ((property_endtime <> "" OR property_endtime <> null) AND property_endtime <="'.strtotime(date('Y-m-d')).'")) AND parent_id=0';
        $list = M('House_village_user_bind')->field('pigcms_id,property_endtime')->where($condition)->limit($i,$limit)->select();
        foreach ($list as $key => $value) {
        	if (date('d',$value['property_endtime']) == date('d')) {
           	 	$ids[] = $value['pigcms_id'];
        	}
        }

        // 自定义缴费
        $condition = '`psb`.`pigcms_id` = `b`.`pigcms_id`  AND `psb`.`standard_id` = `ps`.`standard_id` AND `psb`.`cycle_sum` > `psb`.`paid_cycle` AND `psb`.`start_time` <"'.time().'"';
        $condition_table  = array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',C('DB_PREFIX').'house_village_user_bind'=>'b',C('DB_PREFIX').'house_village_payment_standard'=>'ps');
        $field = '`psb`.*,`ps`.`cycle_type`,`ps`.`pay_cycle`,`b`.`pigcms_id`';

        $list1 = D('')->field($field)->table($condition_table)->where($condition)->limit($i,$limit)->select();
        $list1 = $list1 ? $list1 : array();

        // 车位自定义缴费
        $condition = '`bp`.`user_id` = `b`.`pigcms_id`  AND `bp`.`position_id` = `psb`.`position_id`  AND `psb`.`standard_id` = `ps`.`standard_id` AND `psb`.`cycle_sum` > `psb`.`paid_cycle` AND `psb`.`start_time` <"'.time().'"';
        $condition_table  = array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',C('DB_PREFIX').'house_village_user_bind'=>'b',C('DB_PREFIX').'house_village_payment_standard'=>'ps',C('DB_PREFIX').'house_village_bind_position'=>'bp');
        $field = '`psb`.*,`ps`.`cycle_type`,`ps`.`pay_cycle`,`b`.`pigcms_id`';

        $list2 = D('')->field($field)->table($condition_table)->where($condition)->limit($i,$limit)->select();
        $list2 = $list2 ? $list2 : array();

        $custom_list = array_merge($list1,$list2);

        $custom_ids = array();
        if ($custom_list) {
            foreach ($custom_list as $key => $value) {
                switch ($value['cycle_type']) {
                   case 'Y': // 年
                        // 计算到期时间 = 开始时间 + 已缴时间（收费周期*已缴周期）
                        $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400;
                        break;
                    case 'M': //月
                        $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*30;
                        break;
                    case 'D': // 日
                        $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*365;
                        break;
                }
                if ($end_time<time() && date("d",$end_time) == date("d")) {
                    $custom_ids[] = $value['pigcms_id'];;
                }
            }
        }

        //欠费业主id集
        $ids = array_merge($ids,$custom_ids);
        $condition = 'f.floor_id=b.floor_id AND b.pigcms_id in('.implode(',',$ids).') AND b.village_id=v.village_id AND b.uid=u.uid';
         
        $condition_table  = array(C('DB_PREFIX').'house_village_floor'=>'f',C('DB_PREFIX').'house_village_user_bind'=>'b',C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'house_village'=>'v');
        $field = 'b.*,f.floor_name,f.floor_layer,f.property_fee,v.property_name,v.send_user_type,v.send_date,v.property_price,u.openid';
        $order = ' `b`.`pigcms_id` DESC';

        $list = D('')->field($field)->table($condition_table)->where($condition)->order($order)->select();

        if($list){
            foreach ($list as &$v){
                //物业欠费
                $property_price = 0;
                if ($v['property_endtime'] && $v['property_endtime'] < strtotime(date("Y-m-d"))) {
                    $num = $this->getTimeNum($v['property_endtime'],strtotime(date("Y-m-d")),'M');
                    if (($v['property_fee'] != '0.00') && isset($v['property_fee'])) {
                        $property_price = $v['property_fee'] * $v['housesize'] * $num;
                    } else {
                        $property_price = $v['property_price'] * $v['housesize'] * $num;
                    }
                }

                //自定义项欠费
                $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',
                C('DB_PREFIX').'house_village_payment_standard'=>'ps',
                C('DB_PREFIX').'house_village_payment'=>'p'))
                ->where("psb.pigcms_id= '".$v['pigcms_id']."' AND p.payment_id = psb.payment_id AND `psb`.`cycle_sum` > `psb`.`paid_cycle` AND ps.standard_id = psb.standard_id AND `psb`.`start_time` <".time())->select();
                $payment_list = $payment_list ? $payment_list : array();

                 // 车位缴费
                $position_payment_list = D('House_village_bind_position')->get_user_position_payment_list(array('pigcms_id'=>$v['pigcms_id']));
                $payment_list = array_merge($payment_list, $position_payment_list);
                $cunstom_money = 0;
                if ($payment_list) {
                    foreach ($payment_list as $key => $value) {
                        switch ($value['cycle_type']) {
                            case 'Y':
                        		$end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400;
                                break;
                            case 'M': 
                        		$end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*30;
                                break;
                            case 'D':
                        		$end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*365;
                                break;
                        }
                        if ($end_time<time()) {
                            $num = ceil($num/$value['pay_cycle']);
                            $num = $this->getTimeNum($end_time,time(),$value['cycle_type']);
                            if ($value['pay_type']==1) {
                                $cunstom_money += $value['pay_money'] * $num;
                            }else{
                                $cunstom_money += $value['pay_money'] * $num * $value['metering_mode_val'];
                            }
                        }
                    }
                }
                $v['total'] =  $v['water_price']+$v['electric_price']+$v['gas_price']+$v['park_price'] + $property_price + $cunstom_money;
            }
        }
        return $list;
    }

     //获得相差时间 $date2>$date1
    function getTimeNum($date1,$date2,$type){
        switch ($type) {
            case 'Y': //相差年份
                $date_1['y'] = date('Y',$date1);
                $date_2['y'] = date('Y',$date2);
                $num = $date_2['y']-$date_1['y'];
                if (strtotime(date('m-d',$date2))-strtotime(date('m-d',$date1))>=0) {
                    $num += 1;
                }
                break;
            case 'M': //相差月份
                list($date_1['y'],$date_1['m'],$date_1['d']) = explode("-",date('Y-m-d',$date1));
                list($date_2['y'],$date_2['m'],$date_2['d']) = explode("-",date('Y-m-d',$date2));
                $num = ($date_2['y']-$date_1['y'])*12 +$date_2['m']-$date_1['m'];
                if ($date_2['d']- $date_1['d'] >= 0) { //多相差1天则加一个月
                    $num += 1;
                }
                break;
            case 'D': //相差天数
                $num = abs( ceil(($date2-$date1)/86400));
                break;
        }
        return $num;    
    }
    
}
?>