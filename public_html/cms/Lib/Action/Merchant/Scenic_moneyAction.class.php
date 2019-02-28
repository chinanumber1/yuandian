<?php

/*
 * 景区管理
 *   Writers    hanlu
 *   BuildTime  2016/07/04 16:20
 */

class Scenic_moneyAction extends BaseAction
{
    //首页数据分析
    public function index()
    {
        $money_list = D('Scenic_money_list');
        $scenic_id = intval($this->merchant_session['scenic_id']);
        $period = false;
        if (!empty($_GET['day'])) {
            $this->assign('day', $_GET['day']);
        }
        $condition_merchant_request['order_type'] = 'ticket';
        if (isset($_GET['begin_time']) && isset($_GET['end_time']) && !empty($_GET['begin_time']) && !empty($_GET['end_time'])) {
            if ($_GET['begin_time'] > $_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time'] . " 00:00:00"), strtotime($_GET['end_time'] . " 23:59:59"));

            $time_condition = " (time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";

            $condition_merchant_request['_string'] = $time_condition;
            $this->assign('begin_time', $_GET['begin_time']);
            $this->assign('end_time', $_GET['end_time']);
            $period = true;
        }

        $today_zero_time = mktime(0, 0, 0, date('m', $_SERVER['REQUEST_TIME']), date('d', $_SERVER['REQUEST_TIME']), date('Y', $_SERVER['REQUEST_TIME']));

        if (empty($_GET['day'])) {
            $_GET['day'] = 2;
        }
        if ($_GET['day'] < 1) {
            $this->error('日期非法！');
        }

        if ($_GET['day'] == 1 && !$period) {
            $condition_merchant_request['time'] = array(array('egt', $today_zero_time), array('elt', time()));
            $condition_merchant_request['scenic_id'] = $scenic_id;
            $request_list = M('Scenic_money_list')->field(true)->where($condition_merchant_request)->select();

        } else {
            if (!$period) {
                if ($_GET['day'] == 2) {
                    //本月
                    $today_zero_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
                    $condition_merchant_request['time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                } else {
                    $condition_merchant_request['time'] = array(array('egt', $today_zero_time - (($_GET['day']) * 86400)), array('elt', time()));
                }
            }

            $condition_merchant_request['scenic_id'] = $scenic_id;
            $request_list = M('Scenic_money_list')->field(true)->where($condition_merchant_request)->select();

        }

        $tmp_array = array();
        if (($_GET['day'] == 1 && !$period) || ($period && ($_GET['end_time'] == $_GET['begin_time']))) {
            foreach ($request_list as $value) {
                $tmp_time = date('G', $value['time']);
                if (empty($tmp_array[$tmp_time]['count'])) {
                    $tmp_array[$tmp_time]['count'] = 1;
                } else {
                    $tmp_array[$tmp_time]['count']++;
                }
                if ($value['type'] == 1) {
                    $tmp_array[$tmp_time]['income'] += $value['money'];
                } else {
                    $tmp_array[$tmp_time]['expend'] += $value['money'];
                }
            }
        } else {
            foreach ($request_list as $value) {
                if ($_GET['day'] == 2 && !$period) {
                    $tmp_time = date('j', $value['time']);
                } else {
                    $tmp_time = date('ymd', $value['time']);
                }
                if (empty($tmp_array[$tmp_time]['count'])) {
                    $tmp_array[$tmp_time]['count'] = 1;
                } else {
                    $tmp_array[$tmp_time]['count']++;
                }
                if ($value['type'] == 1) {
                    $tmp_array[$tmp_time]['income'] += $value['money'];
                } else {
                    $tmp_array[$tmp_time]['income'] += $value['money'];
                }
            }
        }

        ksort($tmp_array);
        if (($_GET['day'] == 1 && !$period) || ($period && ($_GET['end_time'] == $_GET['begin_time']))) {
            $day = date('G', time());
            for ($i = 0; $i <= date('H', time()); $i++) {
                $pigcms_list['xAxis_arr'][] = '"' . $i . '时"';
                $time_arr[] = $i;
            }
        } else {
            if ($_GET['day'] == 2) {
                $day = date('d', time());
                for ($i = 1; $i <= $day; $i++) {
                    $pigcms_list['xAxis_arr'][] = '"' . $i . '日"';
                    $time_arr[] = $i;
                }
            } else {
                $day = $_GET['day'];
                for ($i = $day - 1; $i >= 0; $i--) {
                    $pigcms_list['xAxis_arr'][] = '"' . date('d', $today_zero_time - $i * 86400) . '日"';
                    $time_arr[] = date('ymd', $today_zero_time - $i * 86400);
                }
            }
        }

        if ($period) {
            unset($pigcms_list['xAxis_arr']);
            unset($time_arr);
            $start_day = strtotime($_GET['end_time']);

            $day = (strtotime($_GET['end_time']) - strtotime($_GET['begin_time'])) / 86400;
            if ($day == 0) {
                for ($i = 0; $i < 24; $i++) {
                    $pigcms_list['xAxis_arr'][] = '"' . $i . '时"';
                    $time_arr[] = $i;
                }
            } else {
                for ($i = $day; $i >= 0; $i--) {
                    $pigcms_list['xAxis_arr'][] = '"' . date('d', $start_day - $i * 86400) . '日"';
                    $time_arr[] = date('ymd', $start_day - $i * 86400);
                }
            }
        }

        $no_data_time = array();
        foreach ($time_arr as $v) {
            //基础统计
            if ($tmp_array[$v]) {
                $pigcms_list['income'][] = '"' . floatval($tmp_array[$v]['income']) . '"';
                $pigcms_list['income_all'] += floatval($tmp_array[$v]['income']);
                $pigcms_list['order_count'][] = '"' . intval($tmp_array[$v]['count']) . '"';
                $pigcms_list['order_count_all'] += intval($tmp_array[$v]['count']);
            } else {
                if (!in_array($v, $no_data_time)) {
                    $pigcms_list['income'][] = '"0"';
                    $pigcms_list['order_count'][] = '"0"';
                }
            }
        }

        //基础统计
        $pigcms_list['xAxis_txt'] = implode(',', $pigcms_list['xAxis_arr']);
        $pigcms_list['income_txt'] = implode(',', $pigcms_list['income']);
        $pigcms_list['order_count_txt'] = implode(',', $pigcms_list['order_count']);

        if (!$period && !$_GET['day'] != '') {
            $this->assign('day', $_GET['day']);
        }
        $scenic_money = M('Scenic_list')->field('now_money')->where(array('scenic_id' => $scenic_id))->find();
        $this->assign('all_money', $scenic_money['now_money']);
        $this->assign('pigcms_list', $pigcms_list);

        $this->assign('scenic_id', $scenic_id);

        krsort($tmp_array);
        $this->assign('request_list', $tmp_array);

        $area=array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
        $res = D('Scenic_order')->order_ticket_num();
        foreach ($res as &$item) {
            $id_area = substr($item['certificates'],0,2);
            $id_area && $item['id_area'] = $area[$id_area];
           // $item['sexDesc'] = $item['sex']==2?'女':($item['sex']==1?'男':'未知');
            $id_area>0 && $area_data[$id_area]+=1;
            if( $item['sex']==2){
                $item['sexDesc'] = '女';
                $sex_data['female']+=1;
            }else if($item['sex']==1){
                $item['sexDesc'] = '男';
                $sex_data['male']+=1;
            }else{
                $item['sexDesc'] = '未知';
                $sex_data['unknown']+=1;
            }
        }

        ksort($area_data);
        foreach ($area_data as $key=>$v) {
            $area_name[] = $area[$key];
            $txt[] = "{value:{$area_data[$key]},name:'{$area[$key]}'}";
        }

        foreach ($sex_data as $key=>$v) {
            if($key=='female'){
                $value = '女性';
            }else if($key=='male'){
                $value = '男性';
            }elseif($v>0){
                $value = '未知';
            }
            $sex_txt[] = "{value:{$v},name:'{$value}'}";
        }

        $this->assign('area_name', implode(',',$area_name));
        $this->assign('txt', implode(',',$txt));
        $this->assign('sex_txt', implode(',',$sex_txt));
        $this->assign('sex_data', $sex_data);
        $this->assign('area', $area);

        $this->assign('area_data', $area_data);

        $this->display();
    }

    public function withdraw()
    {
        if ($this->config['company_pay_open'] == '0') {
            $this->error('平台没有开启提现功能！');
        }
        $scenic_id = intval($this->merchant_session['scenic_id']);
        $now_scenic = M('Scenic_list')->where(array('scenic_id' => $scenic_id))->find();
        $this->assign('now_scenic', $now_scenic);
        if (M('Scenic_withdraw')->where(array('scenic_id' => $scenic_id, 'status' => 0))->find()) {
            $this->error('您有一笔提现在审核，请审核通过了再申请！');
        }
        if ($_POST['money']) {
            if ($_POST['money'] > $now_scenic['now_money']) {
                $this->error('提现金额超过了您的余额');
            }
            $money = floatval(($_POST['money'])) * 100;
            if ($_POST['money'] < $this->config['min_withdraw_money']) {
                $this->error('不能低于最低提款额 ' . $this->config['min_withdraw_money'] . ' 元!');
            }
            $res = D('Scenic_money_list')->withdraw($scenic_id, $_POST['name'], $money, $_POST['remark']);
            if ($res['error_code']) {
                $this->error($res['msg']);
            } else {
                $this->success("申请成功，请等待审核！", U('Scenic_money/index'));
            }
        } else {
            $this->display();
        }
    }

    public function withdraw_list()
    {
        $scenic_id = intval($this->merchant_session['scenic_id']);
        $withdraw_list = D('Scenic_money_list')->get_withdraw_list($scenic_id);
        $this->assign($withdraw_list);
        $this->display();
    }

    public function income_list()
    {
        if(!empty($_POST['order_id'])){
            if(empty($_POST['order_type'])){
                $this->error_tips("没有选分类");
            }
            if($_POST['order_type']=='all'){
                $this->error("该分类下不能填写订单id");
            }else{
                $condition['order_id'] = $_POST['order_id'];
            }
        }
        $this->assign('order_type',$_POST['order_type']);
        if($_POST['order_type']!='all'&&!empty($_POST['order_type'])){
            $condition['order_type'] = $_POST['order_type'];
        }

        $scenic_id = intval($this->merchant_session['scenic_id']);
        $scenic = M('Scenic_list')->field(true)->where(array('scenic_id' => $scenic_id))->find();
//        if ($scenic['percent']) {
//            $percent = $scenic['percent'];
//        } elseif (C('config.scenic_proportion_full')) {
//            $percent = C('config.scenic_proportion_full');
//        }
//        $this->assign('percent', $percent);


        $this->assign('order_id', $_POST['order_id']);
        if (isset($_POST['begin_time']) && isset($_POST['end_time']) && !empty($_POST['begin_time']) && !empty($_POST['end_time'])) {
            if ($_POST['begin_time'] > $_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period =  array(strtotime($_POST['begin_time'] . " 00:00:00"), strtotime($_POST['end_time'] . " 23:59:59"));
            $time_condition = " (time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
            $condition['_string'] = $time_condition;
            $this->assign('begin_time', $_POST['begin_time']);
            $this->assign('end_time', $_POST['end_time']);
        }
        $res = D('Scenic_money_list')->get_income_list($scenic_id, 0, $condition);
        $alias_name  = $this->get_alias_name();
        $this->assign('alias_name',$alias_name);
        $this->assign('scenic_id', $scenic_id);
        $this->assign('income_list', $res['income_list']);
        $this->assign('pagebar', $res['pagebar']);
        $this->display();
    }

    public function withdraw_order_info()
    {
        $withdraw = M('Scenic_withdraw')->where(array('id' => $_GET['id']))->find();
        $now_scenic = M('Scenic_list')->where(array('mer_id' => $withdraw['mer_id']))->find();
        $this->assign('withdraw', $withdraw);
        $this->assign('now_scenict', $now_scenic);
        $this->display();
    }

    public function buy_system()
    {
        $this->error('该功能正在开发中');
    }

    public  function get_alias_name(){
        return array(
            'all'=>'选择分类',
            'ticket'=>'景区订单',
            'withdraw'=>'提现'
        );
    }

    //导出excel
    public function export()
    {
        $mer_id = isset($_GET['scenic_id']) ? intval($_GET['scenic_id']) : 0;
        $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'income';
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
        $cell_income  = array('type'=>'类型','order_id'=>'订单编号', 'money'=>'金额','time'=>'记账时间','desc'=>'描述');

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

            $objActSheet->setCellValue($col_char[$col_k].'1', $v);
            $col_k++;
        }
        $i = 2;
        if($type=='income'){
            $where['scenic_id']=$mer_id;
            if($_GET['order_type']&&$_GET['order_type']!='all'){
                $where['order_type']=$_GET['order_type'];
            }
            if($_GET['order_id']){
                $where['order_id']=$_GET['order_id'];
            }
            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = $_GET['begin_time']==$_GET['end_time']?array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['begin_time']." 23:59:59")):array(strtotime($_GET['begin_time']),strtotime($_GET['end_time']));
                $time_condition = " (time BETWEEN ".$period[0].' AND '.$period[1].")";
                $where['_string']=$time_condition;

            }

            $result = M('Scenic_money_list')->field('type,order_id,pow(-1,type+1)*money as money,time,desc')->where($where)->order('time DESC')->select();
        }
        //dump(D());die;
        foreach ($result as $row) {
            $col_k=0;
            foreach($$cell_name as $k=>$vv){

                switch($k){
                    case 'order_id':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'time':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
                        break;
                    case 'desc':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    default:
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
                }
                $col_k++;
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
}