<?php

class SpreadAction extends BaseAction
{

    public function spread_change(){
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        $condition_user['spread_change_uid'] = array('gt',0);
        $count = M('User')->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 20);
        $spread_user_list = M('User')->where(array('spread_change_uid'=>array('gt',0)))->limit($p->firstRow,$p->listRows)->select();

        foreach ($spread_user_list as &$v) {
            $v['change_user'] = D('User')->get_user($v['spread_change_uid']);
            $v['spread_money'] = M('User_spread_list')->where(array('uid'=>$v['uid'],'change_uid'=>$v['spread_change_uid']))->sum('money');
        }
        $this->assign('spread_user_list', $spread_user_list);
        $this->assign('pagebar', $p->show());
        $this->display();
    }

    public function unbind_spread_change(){
        $uid=$_POST['uid'];
        if(M('User')->where(array('uid'=>$uid))->setField('spread_change_uid',0)){
            $this->success('解绑成功');
        }else{
            $this->error('解绑失败');
        }
        exit;
    }

    public function user_spread()
    {

        if(!empty($_GET['keyword'])){
           if ($_GET['searchtype'] == 'name') {
                $condition_where['u.nickname']= array( 'like','%'.htmlspecialchars($_GET['keyword']) .'%');
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where['u.phone']=  htmlspecialchars($_GET['keyword']);
            }
        }

        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['order_type']) && $_GET['order_type'] ? $_GET['order_type'] : '';

        $order_sort = 'l.pigcms_id DESC';

        if ($status != -1) {
            $condition_where ['l.status']= $status;
        }

        if ($type) {
            $condition_where['l.order_type']= $type;
        }


        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where['_string']= "l.add_time BETWEEN ".$period[0].' AND '.$period[1];
            //$condition_where['_string']=$time_condition;
        }

        $order_count = D('User_spread_list')->join('as l LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = l.uid')->where($condition_where)->count();
        import('@.ORG.system_page');
        $p = new Page($order_count,30);
        $un_spread_list = D('User_spread_list')->field('l.*,u.nickname,u.phone')->join('as l LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = l.uid')->where($condition_where)->order($order_sort)->limit($p->firstRow.','.$p->listRows)->select();

        $uids = $orderids = $groupids = array();
        foreach ($un_spread_list as $row) {
            if (!in_array($row['uid'], $uids)) $uids[] = $row['uid'];
            if (!in_array($row['spread_uid'], $uids)) $uids[] = $row['spread_uid'];
            if (!in_array($row['get_uid'], $uids)) $uids[] = $row['get_uid'];
            if ($row['order_type'] == 'group') {
                if (!in_array($row['order_id'], $orderids)) $orderids[] = $row['order_id'];
                if (!in_array($row['third_id'], $groupids)) $groupids[] = $row['third_id'];
            }
        }
        if ($uids) {
            $users = D('User')->where(array('uid' => array('in', $uids)))->select();
            $user_list = array();
            foreach ($users as $u) {
                $user_list[$u['uid']] = $u;
            }
        }
        //if ($orderids) {
        //    $orders = D('Group_order')->where(array('order_id' => array('in', $orderids)))->select();
        //    $order_list = array();
        //    foreach ($orders as $o) {
        //        $order_list[$o['order_id']] = $o;
        //    }
        //}
        //if ($groupids) {
        //    $groups = D('Group')->where(array('group_id' => array('in', $groupids)))->select();
        //    $group_list = array();
        //    foreach ($groups as $g) {
        //        $group_list[$g['group_id']] = $g;
        //    }
        //}
        $list = array();
        foreach ($un_spread_list as $un) {
            $str = '';
            if (isset($user_list[$un['uid']])) {
                $un['get_nickname'] = $user_list[$un['uid']]['nickname'];//佣金获得者
                $un['get_phone'] = $user_list[$un['uid']]['phone'];//佣金获得者
                $str = '由<font color="green"> 【' . ($user_list[$un['uid']]['nickname']?$user_list[$un['uid']]['nickname']:$user_list[$un['uid']]['phone']). '】 </font>分享出去后，';
            }
            if (isset($user_list[$un['spread_uid']])) {
                $str .= '再由<font color="green"> 【' .($user_list[$un['spread_uid']]['nickname']?$user_list[$un['spread_uid']]['nickname']:$user_list[$un['spread_uid']]['phone']) . '】 </font>分享出去后，';
            }
            if (isset($user_list[$un['get_uid']])) {
                $un['buy_nickname'] = $user_list[$un['get_uid']]['nickname'];//购买人
                $un['buy_phone'] = $user_list[$un['get_uid']]['phone'];//购买人
                $str .= '被 <font color="green"> 【' . ($user_list[$un['get_uid']]['nickname']?$user_list[$un['get_uid']]['nickname']:$user_list[$un['get_uid']]['phone']). '】 </font>通过分享购买';
            }
            if (isset($order_list[$un['order_id']])) {
                //$str .= '<font style="color:#F76120;">《' . $order_list[$un['order_id']]['order_name'] . '》。</font>';
            }
            $un['txt'] = $str;

            $list[] = $un;
        }
        $this->assign('list', $list);

        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->assign('type_name',$this->get_alias_name());
        $this->display();
    }

    public function merchant_spread(){
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'm_name') {
                $condition_where['u.name']= array( 'like','%'.htmlspecialchars($_GET['keyword']) .'%');
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where['u.phone']=  htmlspecialchars($_GET['keyword']);
            } elseif ($_GET['searchtype'] == 'mer_id') {
                $condition_where['u.mer_id']=  htmlspecialchars($_GET['keyword']);
            }
        }
        $type = isset($_GET['order_type']) && $_GET['order_type'] ? $_GET['order_type'] : '';

        $order_sort = 'l.id DESC';
        if ($type) {
            $condition_where['l.order_type']= $type;
        }


        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where['_string']= "l.add_time BETWEEN ".$period[0].' AND '.$period[1];
            //$condition_where['_string']=$time_condition;
        }

        $order_count = D('Merchant_spread_list')->join('as l LEFT JOIN '.C('DB_PREFIX').'merchant u ON u.mer_id = l.mer_id')->where($condition_where)->count();
        import('@.ORG.system_page');
        $p = new Page($order_count,30);
        $un_spread_list = D('Merchant_spread_list')->field('l.*,u.name,u.phone')->join('as l LEFT JOIN '.C('DB_PREFIX').'merchant u ON u.mer_id = l.mer_id')->where($condition_where)->order($order_sort)->limit($p->firstRow.','.$p->listRows)->select();

        $merids = $uids = array();
        foreach ($un_spread_list as $row) {
            if (!in_array($row['uid'], $uids)) $uids[] = $row['uid'];
            if (!in_array($row['mer_id'], $merids)) $merids[] = $row['mer_id'];
            if (!in_array($row['verify_mer_id'], $merids)) $merids[] = $row['verify_mer_id'];

        }

        if ($uids) {
            $users = D('User')->where(array('uid' => array('in', $uids)))->select();
            $user_list = array();
            foreach ($users as $u) {
                $user_list[$u['uid']] = $u;
            }
        }
        if ($merids) {
            $mers = M('Merchant')->where(array('mer_id' => array('in', $merids)))->select();
            $merchant_list = array();
            foreach ($mers as $o) {
                $merchant_list[$o['mer_id']] = $o;
            }
        }

        foreach ($un_spread_list as $un) {
            $str = '';
            if (isset($merchant_list[$un['mer_id']])) {
                $un['get_mer_name'] = $merchant_list[$un['mer_id']]['name'];//佣金获得者
                $str = '<font color="green"> 【' . $merchant_list[$un['mer_id']]['name'] . '】 </font>在推广用户';
            }

            if (isset($user_list[$un['uid']])) {
                $un['buy_nickname'] = $user_list[$un['uid']]['nickname'];//购买人
                $un['buy_phone'] = $user_list[$un['uid']]['phone'];//购买人
                 $str .= ' <font color="green"> 【' . ($user_list[$un['uid']]['nickname']?$user_list[$un['uid']]['nickname']:$user_list[$un['uid']]['phone']). '】 </font>，';
            }
            if (isset($merchant_list[$un['verify_mer_id']])) {
                $un['buy_mer_name'] = $merchant_list[$un['verify_mer_id']]['name'];//购买人
                $str .= '在商家<font color="green"> 【' . $merchant_list[$un['verify_mer_id']]['name'] . '】 </font>购买商品时获得佣金';
            }

             $un['txt'] = $str;

            $list[] = $un;
        }

        $this->assign('list', $list);
        $this->assign('type_name',$this->get_alias_name());
        $this->display();
    }

    public function get_alias_name(){
        $c_name = array(
            'group'=>$this->config['group_alias_name'],
            'shop'=>$this->config['shop_alias_name'],
            'meal'=>$this->config['meal_alias_name'],
            'store'=>'优惠买单',
            'cash'=>'到店支付',
        );
        return $c_name;
    }

    public function export(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        if($_GET['type']=='user') {
            $title = '用户三级分佣记录';
        }elseif($_GET['type']=='mer'){
            $title = '商家推广分佣记录';
        }
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);
        $spread_status = array(
            0=>'未结算',
            1=>'已结算',
            2=>'订单退款不予结算',
        );
        // 设置当前的sheet

        if($_GET['type']=='user') {
            if (!empty($_GET['keyword'])) {
                if ($_GET['searchtype'] == 'name') {
                    $condition_where['u.nickname'] = array('like', '%' . htmlspecialchars($_GET['keyword']) . '%');
                } elseif ($_GET['searchtype'] == 'phone') {
                    $condition_where['u.phone'] = htmlspecialchars($_GET['keyword']);
                }
            }

            $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
            $type   = isset($_GET['order_type']) && $_GET['order_type'] ? $_GET['order_type'] : '';

            $order_sort = 'l.pigcms_id DESC';

            if ($status != -1) {
                $condition_where ['l.status'] = $status;
            }

            if ($type) {
                $condition_where['l.order_type'] = $type;
            }


            if (!empty($_GET['begin_time']) && !empty($_GET['end_time'])) {
                if ($_GET['begin_time'] > $_GET['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }

                $period                     = array(strtotime($_GET['begin_time'] . " 00:00:00"), strtotime($_GET['end_time'] . " 23:59:59"));
                $condition_where['_string'] = "l.add_time BETWEEN " . $period[0] . ' AND ' . $period[1];
                //$condition_where['_string']=$time_condition;
            }

            $order_count = D('User_spread_list')->join('as l LEFT JOIN ' . C('DB_PREFIX') . 'user u ON u.uid = l.uid')->where($condition_where)->count();

            $un_spread_list = D('User_spread_list')->field('l.*,u.nickname,u.phone')->join('as l LEFT JOIN ' . C('DB_PREFIX') . 'user u ON u.uid = l.uid')->where($condition_where)->order($order_sort)->select();

            $alias_name     = $this->get_alias_name();
            $uids           = $orderids = $groupids = array();
            foreach ($un_spread_list as $row) {
                if (!in_array($row['uid'], $uids)) $uids[] = $row['uid'];
                if (!in_array($row['spread_uid'], $uids)) $uids[] = $row['spread_uid'];
                if (!in_array($row['get_uid'], $uids)) $uids[] = $row['get_uid'];

            }

            if ($uids) {
                $users     = D('User')->where(array('uid' => array('in', $uids)))->select();
                $user_list = array();
                foreach ($users as $u) {
                    $user_list[$u['uid']] = $u;
                }
            }

            $list = array();
            foreach ($un_spread_list as $un) {
                $str = '';
                if (isset($user_list[$un['uid']])) {
                    $un['get_nickname'] = $user_list[$un['uid']]['nickname'];//佣金获得者
                    $str                = '由【' . $user_list[$un['uid']]['nickname'] . '】 分享出去后，';
                }
                if (isset($user_list[$un['spread_uid']])) {
                    $str .= '再由【' . $user_list[$un['spread_uid']]['nickname'] . '】分享出去后，';
                }
                if (isset($user_list[$un['get_uid']])) {
                    $un['buy_nickname'] = $user_list[$un['get_uid']]['nickname'];//购买人
                    $str .= '被【' . $user_list[$un['get_uid']]['nickname'] . '】通过分享购买';
                }

                $un['txt'] = $str;

                $list[] = $un;
            }

        }elseif($_GET['type']=='mer'){
            if(!empty($_GET['keyword'])){
                if ($_GET['searchtype'] == 'm_name') {
                    $condition_where['u.name']= array( 'like','%'.htmlspecialchars($_GET['keyword']) .'%');
                } elseif ($_GET['searchtype'] == 'phone') {
                    $condition_where['u.phone']=  htmlspecialchars($_GET['keyword']);
                } elseif ($_GET['searchtype'] == 'mer_id') {
                    $condition_where['u.mer_id']=  htmlspecialchars($_GET['keyword']);
                }
            }
            $type = isset($_GET['order_type']) && $_GET['order_type'] ? $_GET['order_type'] : '';

            $order_sort = 'l.id DESC';
            if ($type) {
                $condition_where['l.order_type']= $type;
            }


            if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }

                $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
                $condition_where['_string']= "l.add_time BETWEEN ".$period[0].' AND '.$period[1];
                //$condition_where['_string']=$time_condition;
            }

            $order_count = D('Merchant_spread_list')->join('as l LEFT JOIN '.C('DB_PREFIX').'merchant u ON u.mer_id = l.mer_id')->where($condition_where)->count();
            import('@.ORG.system_page');
            $p = new Page($order_count,30);
            $un_spread_list = D('Merchant_spread_list')->field('l.*,u.name,u.phone')->join('as l LEFT JOIN '.C('DB_PREFIX').'merchant u ON u.mer_id = l.mer_id')->where($condition_where)->order($order_sort)->limit($p->firstRow.','.$p->listRows)->select();
            $alias_name     = $this->get_alias_name();
            $merids = $uids = array();
            foreach ($un_spread_list as $row) {
                if (!in_array($row['uid'], $uids)) $uids[] = $row['uid'];
                if (!in_array($row['mer_id'], $merids)) $merids[] = $row['mer_id'];
                if (!in_array($row['verify_mer_id'], $merids)) $merids[] = $row['verify_mer_id'];

            }

            if ($uids) {
                $users = D('User')->where(array('uid' => array('in', $uids)))->select();
                $user_list = array();
                foreach ($users as $u) {
                    $user_list[$u['uid']] = $u;
                }
            }
            if ($merids) {
                $mers = M('Merchant')->where(array('mer_id' => array('in', $merids)))->select();
                $merchant_list = array();
                foreach ($mers as $o) {
                    $merchant_list[$o['mer_id']] = $o;
                }
            }

            foreach ($un_spread_list as $un) {
                $str = '';
                if (isset($merchant_list[$un['mer_id']])) {
                    $un['get_mer_name'] = $merchant_list[$un['mer_id']]['name'];//佣金获得者
                    $str = '【' . $merchant_list[$un['mer_id']]['name'] . '】在推广用户';
                }

                if (isset($user_list[$un['uid']])) {
                    $un['buy_nickname'] = $user_list[$un['uid']]['nickname'];//购买人
                    $un['buy_phone'] = $user_list[$un['uid']]['phone'];//购买人
                    $str .= '  【' . ($user_list[$un['uid']]['nickname']?$user_list[$un['uid']]['nickname']:$user_list[$un['uid']]['phone']). '】 ，';
                }
                if (isset($merchant_list[$un['verify_mer_id']])) {
                    $un['buy_mer_name'] = $merchant_list[$un['verify_mer_id']]['name'];//购买人
                    $str .= '在商家【' . $merchant_list[$un['verify_mer_id']]['name'] . '】购买商品时获得佣金';
                }

                $un['txt'] = $str;

                $list[] = $un;
            }
        }

       // $sql = "SELECT count(order_id) as count FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC ";
        $count = $order_count;

        $length = ceil($count / 1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
            $objActSheet = $objExcel->getActiveSheet();


            if($_GET['type']=='user') {
                $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(80);
                $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $objActSheet->setCellValue('A1', '编号');
                $objActSheet->setCellValue('B1', '获得佣金的用户');
                $objActSheet->setCellValue('C1', '购买的用户');
                $objActSheet->setCellValue('D1', '订单类型');
                $objActSheet->setCellValue('E1', '订单号');
                $objActSheet->setCellValue('F1', '获得详情');
                $objActSheet->setCellValue('G1', '金额');
                $objActSheet->setCellValue('H1', '佣金状态');
                $objActSheet->setCellValue('I1', '时间');
            }elseif($_GET['type']=='mer'){
                $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(80);
                $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $objActSheet->setCellValue('A1', '编号');
                $objActSheet->setCellValue('B1', '获得佣金的商户');
                $objActSheet->setCellValue('C1', '购买的用户');
                $objActSheet->setCellValue('D1', '消费商家');
                $objActSheet->setCellValue('E1', '订单类型');
                $objActSheet->setCellValue('F1', '订单号');
                $objActSheet->setCellValue('G1', '获得详情');
                $objActSheet->setCellValue('H1', '金额');
                $objActSheet->setCellValue('I1', '时间');

            }



            if (!empty($list)) {
                $index = 2;
                for ($c = $i*1000; $c < 1000; $c++) {
                    if(empty($list[$c])){

                        break;
                    }
                    $value = $list[$c];
                    if($_GET['type']=='user') {
                        $objActSheet->setCellValueExplicit('A' . $index, $value['pigcms_id']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['get_nickname']);
                        $objActSheet->setCellValueExplicit('C' . $index, $value['buy_nickname'] . ' ');
                        $objActSheet->setCellValueExplicit('D' . $index, $alias_name[$value['order_type']]);
                        $objActSheet->setCellValueExplicit('E' . $index, $value['order_id']);
                        $objActSheet->setCellValueExplicit('F' . $index, $value['txt'].'');
                        $objActSheet->setCellValueExplicit('G' . $index, floatval($value['money']));
                        $objActSheet->setCellValueExplicit('H' . $index,$spread_status[$value['status']]);
                        $objActSheet->setCellValueExplicit('I' . $index, $value['add_time'] ? date('Y-m-d H:i:s', $value['add_time']) : '');
                    }elseif($_GET['type']=='mer'){
                        $objActSheet->setCellValueExplicit('A' . $index, $value['id']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
                        $objActSheet->setCellValueExplicit('C' . $index, ($value['buy_nickname']?$value['buy_nickname']:$value['buy_phone']). ' ');
                        $objActSheet->setCellValueExplicit('D' . $index, $value['buy_mer_name']);
                        $objActSheet->setCellValueExplicit('E' . $index,$alias_name[$value['order_type']]);
                        $objActSheet->setCellValueExplicit('F' . $index,  $value['order_id']);
                        $objActSheet->setCellValueExplicit('G' . $index,$value['txt'].'' );
                        $objActSheet->setCellValueExplicit('H' . $index,floatval($value['money']));
                        $objActSheet->setCellValueExplicit('I' . $index, $value['add_time'] ? date('Y-m-d H:i:s', $value['add_time']) : '');
                    }


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
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

}