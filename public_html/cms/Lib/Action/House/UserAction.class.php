<?php

/*
 * 小区业主
 *
 */

class UserAction extends BaseAction
{
    protected $village_id;
    protected $village;

    public function _initialize()
    {
        parent::_initialize();

        $this->village_id = $this->house_session['village_id'];
        $this->village = D('House_village')->where(array('village_id' => $this->village_id))->find();
        if (empty($this->village)) {
            $this->error('该小区不存在！');
        }
        if ($this->village['status'] == 0) {
            $this->assign('jumpUrl', U('Index/config'));
            $this->error('您需要先完善信息才能继续操作');
        }
    }

    // 所有业主列表
    public function index()
    {
        //业主-添加 权限
        if (!in_array(91, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $find_type = $_GET['find_type'];
        $find_value = $_GET['find_value'];
        $is_platform = $_GET['is_platform'] + 0;
        if ($find_value) {
            if ($find_type == 1) {
                $where['usernum'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 2) {
                $where['name'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 3) {
                $where['phone'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 4) {
                $where['address'] = array('like', '%' . $find_value . '%');
            }
        }

        $property_endtime_start = $_GET['property_endtime_start'];
        $property_endtime_end = $_GET['property_endtime_end'];
        $this->assign('property_endtime_start', $property_endtime_start);
        $this->assign('property_endtime_end', $property_endtime_end);
        
        if($property_endtime_start && $property_endtime_end){
            $start_time = strtotime($property_endtime_start);
            $end_time = strtotime($property_endtime_end.'23:59:59');
            $where['property_endtime'] = array('between',array($start_time,$end_time));
        }else if($property_endtime_start){
            $property_endtime_start = strtotime($property_endtime_start);
            $where['property_endtime'] = array('egt',$property_endtime_start);
        }else if($property_endtime_end){
            $property_endtime_end = strtotime($property_endtime_end.'23:59:59');
            $where['property_endtime'] = array('lt',$property_endtime_end);
        }

        if($is_platform == 1){
            $where['uid'] = array('neq',0);
        }elseif ($is_platform == 2) {
            $where['uid'] = array('eq',0);
        }

        $village_id = $this->village_id;
        if (empty($where)) {
            $user_list = D('House_village_user_bind')->get_limit_list_page($village_id, 20);
        } else {
            $user_list = D('House_village_user_bind')->get_limit_list_page($village_id, 20, $where);
        }

        foreach ($user_list['user_list'] as $k=>$v) {
            if(!$v['property_endtime']){
                $now_pay_info = D('House_village_property_paylist')->where(array('bind_id'=>$v['pigcms_id']))->order('add_time desc')->field('end_time')->find();
                $user_list['user_list'][$k]['property_endtime'] = $now_pay_info['end_time'];
            }

            if(!$v['property_starttime']){
                $now_pay_info = D('House_village_property_paylist')->where(array('bind_id'=>$v['pigcms_id']))->order('add_time asc')->field('start_time')->find();
                $user_list['user_list'][$k]['property_starttime'] = $now_pay_info['start_time'];
            }

            $door_control = D('House_village_floor')->where(array('floor_id'=>$v['floor_id']))->getField('door_control');
            if (empty($door_control)) {
                $door_control_str = $v['door_control'];
            }elseif ($v['door_control'] == null) {
                $door_control_str = $door_control;
            }else{
                $door_control_str = $v['door_control'].','.$door_control;
            }

            if($this->house_session['owe_property_open_door'] == 1){
                $time = $v['property_endtime']+$this->house_session['owe_property_open_door_day']*86400;
            }else{
                $time = $v['property_endtime'];
            }

            $user_list['user_list'][$k]['door_str'] = $this->convert($time,$door_control_str);

            $v['bind_unverify_num'] = D('House_village_user_bind')->where(array('parent_id'=>$v['pigcms_id'],'status'=>2))->count();

            //获取微信 openid
            if ($v['uid']) {
                $now_user = D('User')->get_user($v['uid']);
                $user_list['user_list'][$k]['openid'] = $now_user['openid'];
            }

            // 查看有无车位 
            $user_list['user_list'][$k]['bind_position'] = D('House_village_bind_position')->where(array('user_id'=>$v['pigcms_id'],'village_id'=>$this->village_id))->count();

        }
        $village_info = D('House_village')->field(true)->where(array('village_id'=>$village_id))->find();
        $village_info['long'] = floatval($village_info['long']);
        $village_info['lat'] = floatval($village_info['lat']);

        $this->assign('door_pwd',$this->pwd_convert($this->village['door_pwd']));
        $this->assign('door_sector',$this->village['door_sector']);
        $this->assign('village_info',$village_info);
        $this->assign('find_value', $find_value);
        $this->assign('find_type', $find_type);
        $this->assign('user_list', $user_list);
        $this->display();
    }



    //时间转换
    public function convert($time,$door_control_str){

        $twoy = decbin(date('y',$time));
        $twom = decbin(date('m',$time));
        $twod = decbin(date('d',$time));

        $len_nian = strlen($twoy);
        if($len_nian == 7){
            $str_nian = $twoy;
        }else{
            $str_nian = $this->str_prefix($twoy,intval( 7-$len_nian),'0');
        }

        $len_yue = strlen($twom);
        if($len_yue == 4){
            $str_yue = $twom;
        }else{
            $str_yue = $this->str_prefix($twom,intval( 4-$len_yue),'0');
        }

        $len_ri = strlen($twod);
        if($len_ri == 5){
            $str_ri = $twod;
        }else{
            $str_ri = $this->str_prefix($twod,intval( 5-$len_ri),'0');
        }

        // '二进制组合，日的第一部分放在最前+年+日的第二部分+月 -- 结果：';
        $total_str = substr($str_ri, 0,1).$str_nian.substr($str_ri, 1,4).$str_yue;
        // '组合之后转换成十六进制：';
        $ymdshiliu = dechex(bindec($total_str));
        //数据组合

        $data = '5100'.$ymdshiliu.'000000'.'000000000000000000'.$this->door_control_convert($door_control_str);
        // $data = '5000'.$ymdshiliu.'000000'.'000000000000000000'.'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF';
        return $data;

    }


    //门禁机转换
    public function door_control_convert($door_control_str){

        if(strlen($door_control_str) > 0){
            $door_array = explode(',', $door_control_str);
        }
        $door_two_str = '';
        for ($i=0; $i <= 127; $i++) { 
            if(in_array($i, $door_array)){
                $door_two_str .= '1';
            }else{
                $door_two_str .= '0';
            }
        }

        $start_position = 0;
        $total_door_str = '';
        for ($c=0; $c < 16; $c++) { 
            $byte = substr($door_two_str, $start_position,8);
            $byte_sixteen = dechex(bindec($byte));
            if(strlen($byte_sixteen) < 2){
                $byte_sixteen = $this->str_prefix($byte_sixteen,intval( 2-strlen($byte_sixteen)),'0');
            }
            $total_door_str .= $byte_sixteen;
            $start_position += 8;
        }

        return $total_door_str;
    }



    //密码转换
    public function pwd_convert($door_pwd){
        $door_pwd = explode(',', $door_pwd);

        $pwd1 = $door_pwd[0];
        $pwd2 = $door_pwd[1];
        $pwd3 = $door_pwd[2];
        $pwd4 = $door_pwd[3];
        $pwd5 = $door_pwd[4];
        $pwd6 = $door_pwd[5];

        $pwd_16_1 = dechex($pwd1);
        if(strlen($pwd_16_1) == 2){
            $pwd_16_1 = $pwd_16_1;
        }else{
            $pwd_16_1 = $this->str_prefix($pwd_16_1,intval( 2-strlen($pwd_16_1)),'0');
        }

        $pwd_16_2 = dechex($pwd2);
        if(strlen($pwd_16_2) == 2){
            $pwd_16_2 = $pwd_16_2;
        }else{
            $pwd_16_2 = $this->str_prefix($pwd_16_2,intval( 2-strlen($pwd_16_2)),'0');
        }

        $pwd_16_3 = dechex($pwd3);
        if(strlen($pwd_16_3) == 2){
            $pwd_16_3 = $pwd_16_3;
        }else{
            $pwd_16_3 = $this->str_prefix($pwd_16_3,intval( 2-strlen($pwd_16_3)),'0');
        }

        $pwd_16_4 = dechex($pwd4);
        if(strlen($pwd_16_4) == 2){
            $pwd_16_4 = $pwd_16_4;
        }else{
            $pwd_16_4 = $this->str_prefix($pwd_16_4,intval( 2-strlen($pwd_16_4)),'0');
        }

        $pwd_16_5 = dechex($pwd5);
        if(strlen($pwd_16_5) == 2){
            $pwd_16_5 = $pwd_16_5;
        }else{
            $pwd_16_5 = $this->str_prefix($pwd_16_5,intval( 2-strlen($pwd_16_5)),'0');
        }

        $pwd_16_6 = dechex($pwd6);
        if(strlen($pwd_16_6) == 2){
            $pwd_16_6 = $pwd_16_6;
        }else{
            $pwd_16_6 = $this->str_prefix($pwd_16_6,intval( 2-strlen($pwd_16_6)),'0');
        }

        $pwd_total = $pwd_16_1.$pwd_16_2.$pwd_16_3.$pwd_16_4.$pwd_16_5.$pwd_16_6;

        return $pwd_total;

    }

    public function str_prefix($str, $n=1, $char="0"){
        for ($x=0;$x<$n;$x++){$str = $char.$str;}
        return $str;
    }

    public function str_suffix($str, $n=1, $char="0"){
        for ($x=0;$x<$n;$x++){$str = $str.$char;}
        return $str;
    }

    public function card_no_add(){
        $res =  D('House_village_user_bind')->where(array('pigcms_id'=>intval($_POST['pigcms_id'])))->data(array('card_no'=>$_POST['card_no']))->save();
        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'更新门禁卡号成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'更新门禁卡号失败','sql'=>D('House_village_user_bind')->getlastsql())));
        }
    }

    // 家属绑定列表
    public function bind_list(){
        //家属租客-查看 权限
        if (!in_array(115, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $pigcms_id = $_GET['pigcms_id'] + 0;
        $where['parent_id'] = $pigcms_id;
        $user_list = D('House_village_user_bind')->field(true)->where($where)->order('`pigcms_id` DESC')->select();
        $parent_info = D('House_village_user_bind')->where(array('pigcms_id'=>$pigcms_id))->find();
        $parent_info['openid'] = D('User')->where(array('uid'=>$parent_info['uid']))->getField('openid');


        $door_control = D('House_village_floor')->where(array('floor_id'=>$parent_info['floor_id']))->getField('door_control');
        if (empty($door_control)) {
            $door_control_str = $parent_info['door_control'];
        }elseif ($parent_info['door_control'] == null) {
            $door_control_str = $door_control;
        }else{
            $door_control_str = $parent_info['door_control'].','.$door_control;
        }
        $parent_info['door_str'] = $this->convert($parent_info['property_endtime'],$door_control_str);

        $this->assign('door_pwd',$this->pwd_convert($this->village['door_pwd']));
        $this->assign('door_sector',$this->village['door_sector']);
        $this->assign('user_list' , $user_list);
        $this->assign('parent_info' , $parent_info);
        // dump($parent_info);
        $this->display();
    }

    // 添加编辑家属信息
    public function bind_other(){
        if(IS_POST){
            if ($_POST['edit'] == 1) { // 编辑家属信息
                //家属租客-编辑 权限
                if (!in_array(116, $this->house_session['menus']) && !in_array(104, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                $child_info =  D('House_village_user_bind')->get_one_by_bindId($_POST['pigcms_id']);
                if(!$child_info){
                    $this->error('家属信息不存在');
                }
                $bind_info =  D('House_village_user_bind')->get_one_by_bindId($child_info['parent_id']);
            } else {
                //家属租客-添加 权限
                if (!in_array(110, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                $bind_info =  D('House_village_user_bind')->get_one_by_bindId($_POST['pigcms_id']);
            }
            if(!$bind_info){
                $this->error('业主信息不存在');
            }
            if($_POST['phone']==$bind_info['phone']){
                $this->error('手机号码不能跟业主的手机号码相同');
            }

            if(empty($_POST['phone'])){
                $this->error('手机号码不能为空');
            }

            $bind_data = $bind_info;
            unset($bind_data['pigcms_id']);
            $user_info = D('User')->where(array('phone'=>$_POST['phone']))->find();
            if(empty($user_info)){
                $this->error('提交失败，用户不是平台用户，请先注册平台会员。');
            }
            if($_POST['edit'] == 1 && $child_info){
                $edit_data = array();
                $where = array();
                $where['pigcms_id'] = $_POST['pigcms_id'];
                $edit_data['name'] = $_POST['name'];
                $edit_data['phone'] =  $_POST['phone'];
                $edit_data['type'] = $_POST['type'];
                $edit_data['memo'] = $_POST['memo'];
                $insert_id = D('House_village_user_bind')->where($where)->save($edit_data);
            }else{
                $bind_data['uid'] = $user_info['uid'];
                $bind_data['name'] = $_POST['name'];
                $bind_data['phone'] =  $_POST['phone'];
                $bind_data['type'] = $_POST['type'];
                $bind_data['status'] = 2;
                $bind_data['usernum'] = rand(0,99999) . '-' . time();
                $bind_data['add_time'] = time();
                $bind_data['parent_id'] = $_POST['pigcms_id'];
                $edit_data['memo'] = $_POST['memo'];
                $insert_id = D('House_village_user_bind')->data($bind_data)->add();
            }
            if($insert_id !== false){
                $this->success('提交审核成功');
            }else{
                $this->error('提交审核失败');
            }
        }else{
            //家属租客-查看 权限
            if (!in_array(115, $this->house_session['menus']) && !in_array(103, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $bind_info =  D('House_village_user_bind')->get_one_by_bindId($_GET['pigcms_id']);
            if($bind_info['parent_id']>0){
                $this->assign('bind_info',$bind_info);
            }
            $this->display();
        }
    }

    // 删除家属信息
    public function bind_delete(){
        //家属租客-删除 权限
        if (!in_array(113, $this->house_session['menus']) && !in_array(106, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(D('House_village_user_bind')->where(array('pigcms_id'=>$_GET['pigcms_id']))->delete()){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    // 删除家属信息 批量删除
    public function ajax_del_bind(){
        //审核业主列表-删除 权限
        if (!in_array(106, $this->house_session['menus'])) {
           exit(json_encode(array('status'=>0,'msg'=>'对不起，您没有权限执行此操作')));
        }
        
        $arr_pigcms_id = $_POST['arr_pigcms_id'];

        if (!$arr_pigcms_id) {
           exit(json_encode(array('status'=>0,'msg'=>'参数传递错误')));
        }
        $where['pigcms_id'] = array('in',$arr_pigcms_id);
        $where['type'] = array('in',array(1,2));
        $where['village_id'] = $this->village_id;
        $res = D('House_village_user_bind')->where($where)->delete();
            
        if ($res) {
           exit(json_encode(array('status'=>1,'msg'=>'删除成功')));
        }else{
           exit(json_encode(array('status'=>1,'msg'=>'删除失败')));
        }        
    }

    //家属审核列表
    public function bind_audit_list(){
        //家属审核列表-查看 权限
        if (!in_array(103, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->village_id;
        $where['village_id'] = $village_id;

        $find_type = $_GET['find_type'];
        $find_value = $_GET['find_value'];
        $find_status = $_GET['status'];

        $begin_time = $_GET['begin_time'];
        $end_time = $_GET['end_time'];

        if ($find_value) {
            switch ($find_type) {
                case 1: // 家属姓名
                    $where['name'] = array('like', '%' . $find_value . '%');
                    // $where['parent_id'] = array('gt',0);
                    $where['type'] = array('in',array(1,2,3));
                    break;
                case 2: // 家属手机号
                    $where['phone'] = array('like', '%' . $find_value . '%');
                    // $where['parent_id'] = array('gt',0);
                    $where['type'] = array('in',array(1,2,3));
                    break;
                case 3: // 业主姓名
                    // 查询业主信息
                    $parent_where = $where;
                    $parent_where['name'] = array('like', '%' . $find_value . '%');
                    $parent_where['parent_id'] = 0;
                    $parent_where['type'] = 0;
                    $parent_list = D('House_village_user_bind')->where($parent_where)->field('pigcms_id')->select();
                    if ($parent_list) {
                        $parent_ids = array(); // 业主id
                        foreach ($parent_list as $key => $value) {
                           $parent_ids[] = $value['pigcms_id'];
                        }
                        $where['parent_id'] = array('in' , $parent_ids);
                    } else {
                        $where['parent_id'] = -1;
                    }
                    break;
                case 4: // 业主手机号 
                    // 查询业主信息
                    $parent_where = $where;
                    $parent_where['phone'] = array('like', '%' . $find_value . '%');
                    $parent_where['parent_id'] = 0;
                    $parent_where['type'] = 0;
                    $parent_list = D('House_village_user_bind')->where($parent_where)->field('pigcms_id')->select();
                    if ($parent_list) {
                        $parent_ids = array(); // 业主id
                        foreach ($parent_list as $key => $value) {
                           $parent_ids[] = $value['pigcms_id'];
                        }
                        $where['parent_id'] = array('in' , $parent_ids);
                    } else {
                        $where['parent_id'] = -1;
                    }
                    break;
            }
        } else {
            // $where['parent_id'] = array('gt',0);
            $where['type'] = array('in',array(1,2,3));
        }

        // 状态
        if($find_status){
            $where['status'] = $find_status;   
        }

        if ($begin_time && !$end_time) {
            $where['add_time'] = array('gt' , strtotime($begin_time));
        }
        if (!$begin_time && $end_time) {
            $where['add_time'] = array('lt' , strtotime(date('Y-m-d 23:59:59',strtotime($end_time))));
        }
        if ($begin_time && $end_time) {
            $where['add_time'] = array('between' , array(strtotime($begin_time),strtotime(date('Y-m-d 23:59:59',strtotime($end_time)))));
        }

        $result = D('House_village_user_bind')->get_all_child_list($where,true,'status desc,pigcms_id desc');

      
        if($find_type){
            $this->assign('find_type',$find_type);  
        }
        if($find_value){
            $this->assign('find_value',$find_value);    
        }
        if($find_status){
            $this->assign('find_status',$find_status);  
        }
        $this->assign('user_list',$result['result']);
        // dump($parent_info);
        $this->display();
    }


    public function test(){
        require_once APP_PATH . 'Lib/ORG/plan_house_notice.class.php';
        $objExcel = new plan_house_notice();
        $objExcel->runTask();exit();
    }

    public function payment_list(){
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '模板-业主每月账单明细';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->setActiveSheetIndex(0);
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);


        $objActSheet->setCellValue('A1', '业主编号');
        $objActSheet->setCellValue('B1', '业主名');
        $objActSheet->setCellValue('C1', '手机号');
        $objActSheet->setCellValue('D1', '用水(立方米)');
        $objActSheet->setCellValue('E1', '用电(千瓦时[度])');
        $objActSheet->setCellValue('F1', '燃气(立方米)');
        // $objActSheet->setCellValue('G1', '是否需要缴物业费（1需要，0不需要）');
        $objActSheet->setCellValue('G1', '是否需要缴停车费（1需要，0不需要）');

        //筛选数据
        $where['status'] = 1;
        $where['village_id'] = $this->village_id;
        $where['parent_id'] = 0;
        $where['type'] =0 ;
        $bind_user = M('House_village_user_bind')->field('usernum,name,phone')->where($where)->select();
        $index = 2;
        foreach ($bind_user as $value) {
            $objActSheet->setCellValueExplicit('A' . $index, $value['usernum']);
            $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
            $objActSheet->setCellValueExplicit('C' . $index, $value['phone']);
            $objActSheet->setCellValueExplicit('D' . $index, 0);
            $objActSheet->setCellValueExplicit('E' . $index,0);
            $objActSheet->setCellValueExplicit('F' . $index, 0);
            $objActSheet->setCellValueExplicit('G' . $index, 0);
            // $objActSheet->setCellValueExplicit('H' . $index,0);
            $index++;
        }

        if (!$bind_user) {
            $objActSheet->setCellValueExplicit('A' . $index, '');
            $objActSheet->setCellValueExplicit('B' . $index, '');
            $objActSheet->setCellValueExplicit('C' . $index, '');
            $objActSheet->setCellValueExplicit('D' . $index, '');
            $objActSheet->setCellValueExplicit('E' . $index,'');
            $objActSheet->setCellValueExplicit('F' . $index, '');
            $objActSheet->setCellValueExplicit('G' . $index, '');
            // $objActSheet->setCellValueExplicit('H' . $index,'');
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

    // 审核业主列表
    public function audit_index(){
        //审核业主列表-查看 权限
        if (!in_array(101, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
		
		$find_type = $_GET['find_type'];
        $find_value = $_GET['find_value'];
		$find_status = $_GET['status'];
        if ($find_value) {
            if ($find_type == 1) {
                $where['usernum'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 2) {
                $where['name'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 3) {
                $where['phone'] = array('like', '%' . $find_value . '%');
            }
        }
		if($find_status !== '' && $find_status !== null ){
			 $where['status'] = $find_status;	
		}
		
        $village_id = $this->village_id;
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['village_id'] = $village_id;
        $where['is_del'] = 0;
		if(!$where['phone']){
			$where['phone'] = array('neq' , "");
		}
		if(!$where['name']){
			$where['name'] = array('neq' , "");
		}
		// $where['uid'] = array('neq' , 0);

        $begin_time = $_GET['begin_time'];
        $end_time = $_GET['end_time'];
        if ($begin_time && !$end_time) {
            $where['application_time'] = array('gt' , strtotime($begin_time));
        }
        if (!$begin_time && $end_time) {
            $where['application_time'] = array('lt' , strtotime(date('Y-m-d 23:59:59',strtotime($end_time))));
        }
        if ($begin_time && $end_time) {
            $where['application_time'] = array('between' , array(strtotime($begin_time),strtotime(date('Y-m-d 23:59:59',strtotime($end_time)))));
        }

        $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($where);
		
        if(!$result){
            $this->error('数据处理有误！');
        }
		if($find_type){
			$this->assign('find_type',$find_type);	
		}
		if($find_value){
			$this->assign('find_value',$find_value);	
		}
		if($find_status){
			$this->assign('find_status',$find_status);	
		}
        $this->assign('user_list',$result['result']);
        $this->display();
    }

    // 审核业主列表 删除
    public function ajax_del_audit(){
        //审核业主列表-查看 权限
        if (!in_array(268, $this->house_session['menus'])) {
           exit(json_encode(array('status'=>0,'msg'=>'对不起，您没有权限执行此操作')));
        }
        
        $arr_pigcms_id = $_POST['arr_pigcms_id'];

        if (!$arr_pigcms_id) {
           exit(json_encode(array('status'=>0,'msg'=>'参数传递错误')));
        }
        $bind_condition['pigcms_id'] = array('in',$arr_pigcms_id);
        $bind_condition['village_id'] = $this->village_id;
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $database_house_village_user_bind = D('House_village_user_bind');
        $del_list = $database_house_village_user_vacancy->where($bind_condition)->select();

        if (!$del_list) {
           exit(json_encode(array('status'=>0,'msg'=>'审核信息不存在')));
        }
       
        $data['name'] = '';
        $data['phone'] = '';
        $data['uid'] = 0;
        $data['status'] = 1;
        foreach ($del_list as $key => $value) {
            if ($value['status'] != 0) { // 审核不通过的才可以删除
               continue;
            }

            //更新房屋信息
            $modify = $database_house_village_user_vacancy->where($bind_condition)->data($data)->save();

            //删除绑定业主信息
            if ($modify) {
                $where = array(
                    'village_id'=>$this->village_id,
                    'vacancy_id'=>$value['pigcms_id'],
                    'type'=>0,
                );
                $user_bind = $database_house_village_user_bind->where($where)->select();
                if ($user_bind) { //存在业主信息 
                    // 查询有无家属信息
                    $bind_info = $database_house_village_user_bind->where(array('type'=>array('in',array(1,2)),'vacancy_id'=>$value['pigcms_id']))->count();
                    if ($bind_info) { // 更新为虚拟房主
                        $edit_data = array();
                        $edit_data['uid'] = 0;
                        $edit_data['name'] = '';
                        $edit_data['phone'] = '';
                        $edit_data['status'] = 1;
                        $save_bind = $database_house_village_user_bind->where($where)->data($edit_data)->save();
                    }else{
                        // 删除房主信息
                        $save_bind = $database_house_village_user_bind->where($where)->delete();
                    }
                }
            }
        }
        if ($modify) {
           exit(json_encode(array('status'=>1,'msg'=>'删除成功')));
        }else{
           exit(json_encode(array('status'=>1,'msg'=>'删除失败')));
        }        
    }

     // 审核业主列表 删除
    public function audit_delete(){
        //审核业主列表-删除 权限
        if (!in_array(268, $this->house_session['menus']) && !in_array(94, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $pigcms_id = $_GET['pigcms_id'] + 0;

        if (!$pigcms_id) {
            $this->error('参数传递错误');
        }

        $bind_condition['pigcms_id'] = $pigcms_id;
        $bind_condition['village_id'] = $this->village_id;
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $info = $database_house_village_user_vacancy->house_village_user_vacancy_detail($bind_condition);

        if (!$info['detail']) {
            $this->error('审核信息不存在');
        }

        if ($info['detail']['status'] != 0) {
            $this->error('该审核信息不能删除');
        }
        $data['name'] = '';
        $data['phone'] = '';
        $data['uid'] = 0;
        $data['status'] = 1;
        $del = $database_house_village_user_vacancy->where($bind_condition)->data($data)->save();
        if ($del) {
            $where = array(
                'village_id'=>$this->village_id,
                'vacancy_id'=>$pigcms_id,
                'type'=>0,
            );
            $user_bind = D('House_village_user_bind')->where($where)->select();
            if ($user_bind) { //删除绑定业主信息
                $user_bind = D('House_village_user_bind')->where($where)->delete();
            }
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }        
    }

    // 审核业主列表 编辑
    public function audit_edit(){
        //审核业主列表-查看 权限
        if (!in_array(101, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $pigcms_id = $_GET['pigcms_id'] + 0;
        $usernum = $_GET['usernum'];
        // 房屋面积处理
        $housesize = $_POST['housesize'];


        $bind_condition['pigcms_id'] = $pigcms_id;
        $bind_condition['usernum'] = $usernum;
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $info = $database_house_village_user_vacancy->house_village_user_vacancy_detail($bind_condition);
        if(!$info){
            $this->error('数据处理有误！');
        }
        $info = $info['detail'];

        if(IS_POST) {
            //审核业主列表-编辑 权限
            if (!in_array(102, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            
            if(empty($_POST['usernum'])){
                $this->error('业主编号不能为空！');
            }

            if(empty($_POST['user_name'])){
                $this->error('业主名不能为空！');
            }

            if(empty($_POST['phone'])){
                $this->error('手机号不能为空！');
            }
            $status = $_POST['status'] + 0;

            $data['usernum'] = $_POST['usernum'];
            $database_house_village_floor = D('House_village_floor');
            $database_house_village_user_bind = D('House_village_user_bind');
            $database_user = D('User');

            $where['floor_id'] = $_POST['floor_id'] + 0;
            $where['status'] = $status;
            $where['village_id'] = $this->village_id;
            $house_village_floor_info = $database_house_village_floor->where($where)->find();

            //检测用户是否已存在
            $Map['usernum'] =  $data['usernum'];
            $find_info = $database_house_village_user_bind->where($Map)->count();

            if($status == 1){
               // if ($database_house_village_user_bind->field('`usernum`')->where($Map)->find()) {
                   // $this->error('业主名为已存在！物业编号重复。');
               // }

                if (!isset($house_village_floor_info)) {
                    $this->error('单元不存在，请查看社区中心，单元管理-单元列表！');
                }

                $now_user = $database_user->get_user($_POST['phone'], 'phone');
                if ($now_user) {
                    $data['uid'] = $now_user['uid'];
                }
                $layer_num = $_POST['layer_num'];
                $room_num = $_POST['room_num'];

                $data['name'] = $_POST['user_name'];
                $data['phone'] = $_POST['phone'];
                $data['floor_id'] = $_POST['floor_id'] + 0;
                $data['room_addrss'] =  $room_num;

                // 验证手机号是否和家属一样 先有家属   
                // 手机号不能与家属手机号一样 
                $bind_condition2['vacancy_id'] = $pigcms_id;
                $bind_condition2['phone'] = $_POST['phone'];
                $bind_condition2['type'] = array('in' , '1,2');;
                $bind_condition2['status'] = array('in' , '1,2');
            
                $family_bind_info = $database_house_village_user_bind->where($bind_condition2)->find();
                if($family_bind_info){
                    $this->error('该手机号已绑定或已申请绑定此房间');
                }

                if($memo = htmlspecialchars(trim($_POST['memo']))){
                    $data['memo'] = $memo;
                }

                $data['layer_num'] = $layer_num;
                $data['address'] = $_POST['address'];
                $data['village_id'] = $this->village_id;
                // 传过来的面积不同就改值
                if (intval($housesize) != intval($info['housesize'])) {
                    $data['housesize'] = $housesize;
                } else {
                    $data['housesize'] = $info['housesize'];
                }
                $data['park_flag'] = $info['park_flag'];
                $data['add_time'] = time();
                $data['vacancy_id'] = $info['pigcms_id'];
                $data['type'] = $info['type'];
				
				if($find_info>0){
                    $data['status'] = 1;
					$insert_id = $database_house_village_user_bind->where($Map)->data($data)->save();	
				}else{
					$insert_id = $database_house_village_user_bind->data($data)->add();
				}
                if($insert_id){
					$data_room['status'] = 3;
					$data_room['name'] = $_POST['user_name'];
					$data_room['phone'] = $_POST['phone'];
					$data_room['memo'] = $memo ? $memo : "";
                    // 传过来的面积不同就改值
                    if (intval($housesize) != intval($info['housesize'])) {
                        $data_room['housesize'] = $housesize;
                    }
                    //$database_house_village_user_vacancy->where($bind_condition)->setField('status',3);
					$database_house_village_user_vacancy->where($bind_condition)->data($data_room)->save();
                    $this->success('添加成功！',U('index'));
                }else{
                    $this->error('添加失败！');
                }
            }else{ // 审核不通过
                // 更新房屋信息
                $edit_data['status'] = 0;
                $insert_id = $database_house_village_user_vacancy->where($bind_condition)->data($edit_data)->save();
               
                if($find_info>0){ //存在业主信息 
                    // 查询有无家属信息
                    $bind_info = $database_house_village_user_bind->where(array('type'=>array('in',array(1,2)),'vacancy_id'=>$pigcms_id))->count();
                    if ($bind_info) { // 更新为虚拟房主
                        $edit_data = array();
                        $edit_data['uid'] = 0;
                        $edit_data['name'] = '';
                        $edit_data['phone'] = '';
                        $edit_data['status'] = 1;
                        $save_bind = $database_house_village_user_bind->where(array('vacancy_id'=>$_POST['pigcms_id'],'usernum'=>$_POST['usernum']))->data($edit_data)->save();
                    }else{
                        // 删除房主信息
                        $save_bind = $database_house_village_user_bind->where(array('vacancy_id'=>$_POST['pigcms_id'],'usernum'=>$_POST['usernum']))->delete();
                    }
                }
				
                if($insert_id ){
                    $this->success('修改成功！');
                }else{
                    $this->error('修改失败！');
                }
            }
            exit;
        }else if ($pigcms_id && $usernum) {
            $this->assign('info', $info);
        }
        $this->display();
    }


    public function audit_del(){
        $pigcms_id = $_GET['pigcms_id'] + 0;
        if(!$pigcms_id){
            $this->error('传递参数有误！');
        }

        $where['pigcms_id'] = $pigcms_id;

        $database_house_village_user_vacancy = D('House_village_user_vacancy');

        $data['is_del'] = 1;
        $data['del_time'] = time();
        $insert_id = $database_house_village_user_vacancy->where($where)->data($data)->save();

        if($insert_id){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }

	# update 2017-03-21 - wangdong
    // 家属租客-绑定解绑
    public function bind_edit(){
        //家属租客-绑定解绑 权限
        if (!in_array(111, $this->house_session['menus']) && !in_array(105, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $pigcms_id = $_GET['pigcms_id'] + 0;
        if(!$pigcms_id){
            $this->error('传递参数有误！~~~');
        }

        $database_house_village_user_bind = D('House_village_user_bind');
        $now_user = $database_house_village_user_bind->get_one_by_bindId($pigcms_id);
        if(!$now_user){
            $this->error('信息暂时不存在！');
        }

        if(!$_GET['no_bind']) {
            if ($now_user['status'] == 1) {
                $this->error('已通过审核！');
            }
        }	
		
        if($now_user['type'] == 3){
            // 更新原房主信息
            $user_data['uid'] = $now_user['uid'];
            $user_data['name'] = $now_user['name'];
            $user_data['phone'] = $now_user['phone'];
            $user_data['pass_time'] = time();
			$user_data['parent_id'] = 0;
            $insert_id = $database_house_village_user_bind->where(array('pigcms_id'=>$now_user['parent_id']))->data($user_data)->save();
			
			#要修改 房间house_village_user_vacancy 表信息
			$vacancy_data['uid'] = $now_user['uid'];
			$vacancy_data['name'] = $now_user['name'];
			$vacancy_data['phone'] = $now_user['phone'];
			$vacancy_data['status'] = 3;
			
			$vacancy_where['pigcms_id'] = $now_user['vacancy_id'];
			$vacancy_where['floor_id']  = $now_user['floor_id'];
			$vacancy_where['village_id'] = $now_user['village_id'];
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			$database_house_village_user_vacancy->where($vacancy_where)->data($vacancy_data)->save();

            // 删除申请记录
            $database_house_village_user_bind->where(array('pigcms_id'=>$pigcms_id))->delete();

			#禁止之前房主ID
			// $database_house_village_user_bind->where(array('pigcms_id'=>$now_user['parent_id']))->data(array('status'=>0))->save();
			#修改之前房主下面的绑定亲属/租客 替换到现在房主下面
			// $database_house_village_user_bind->where(array('parent_id'=>$now_user['parent_id']))->data(array('parent_id'=>$now_user['pigcms_id']))->save();
			
			
        }

        if($_GET['no_bind']){
            $insert_id = $database_house_village_user_bind->where(array('pigcms_id'=>$pigcms_id))->data(array('pass_time'=>time(),'status'=>2))->save();
        }else{
            // 房屋信息
            $database_house_village_user_vacancy = D('House_village_user_vacancy');
            $vacancy_condition['pigcms_id'] = $now_user['vacancy_id'];
            $bind_info = $database_house_village_user_vacancy->where($vacancy_condition)->find();            
            if($now_user['phone'] == $bind_info['phone']){
                if ($bind_info['status']==2) {
                    $this->error('该手机号已提交申请');
                }else{
                    $this->error('手机号不能与业主一致');
                }
            }

            $insert_id = $database_house_village_user_bind->where(array('pigcms_id'=>$pigcms_id))->data(array('pass_time'=>time(),'status'=>1))->save();
        }

        if($insert_id){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败！');
        }
    }

    // 编辑业主
    public function edit(){
        if (IS_POST) {
            //业主-编辑 权限
            if (!in_array(93, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $page = $_POST['page'];
            unset($_POST['page']);
            if($_POST['property_starttime'] > $_POST['property_endtime']){
                $this->error('请填写正确的物业时间！');
            }
            $_POST['property_starttime'] = strtotime($_POST['property_starttime'].'00:00:00');
            $_POST['property_endtime'] = strtotime($_POST['property_endtime'].'23:59:59');
            $condition['usernum'] = $_POST['usernum'];
            $condition['pigcms_id'] = $_POST['pigcms_id'];
            $condition['village_id'] = $this->village_id;

            $_POST['add_time'] = $_SERVER['REQUEST_TIME'];

            $database_house_village_user_bind = D('House_village_user_bind');
            $database_house_village_floor = D('House_village_floor');
            $database_house_village_user_vacancy = D('house_village_user_vacancy');


            if($_POST['phone'] && !preg_match('/^[0-9]{11}$/',$_POST['phone'])){
                $this->error('请输入正确的手机号！');
            }

            // 业主信息
            $user_info = $database_house_village_user_bind->where($condition)->find();
            // 验证是否更新房屋信息
            $update_vacancy = true;
            if ($user_info['uid']==0 &&  $user_info['phone']=='') { // 虚拟业主
                if (!$_POST['phone']) { // 没有修改手机号，不更新房屋状态；修改则更新
                    $update_vacancy = false;
                }
            }

            if ($_POST['phone']) {
                $uid = D('User')->get_user_by_phone($_POST['phone']);
            }
            $_POST['uid'] = $uid ? $uid : 0;

            $_POST['floor_id'] = $_POST['floor_id'] + 0;

            $where['floor_id'] = $_POST['floor_id'];
            $where['status'] = 1;
            $house_village_floor_info = $database_house_village_floor->where($where)->find();

            if (!isset($house_village_floor_info)) {
                $this->error('单元不存在，请查看社区中心，单元管理-单元列表！');
            }
            $vacancy_where['status'] = array('in' , '1,2,3');
            $vacancy_where['pigcms_id'] = $user_info['vacancy_id'];
            $vacancy_info = $database_house_village_user_vacancy->where($vacancy_where)->find();
            if (!isset($vacancy_info)) {
                $this->error('该房间信息不存在！');
            }
			
           // $Map['usernum'] =  $vacancy_info['usernum'];
			
           // if ($database_house_village_user_bind->field('`usernum`')->where($Map)->find()) {
				//$this->error('业主名为' . $_POST['user_name'] . ' 已存在！物业编号重复');
            //}
            //$_POST['usernum'] =  $vacancy_info['usernum'];
			
			//没有修改房间表 house_village_user_vacancy
				
            if ($database_house_village_user_bind->where($condition)->data($_POST)->save()) {
				if ($update_vacancy) {
                    //修改房间表 信息
                    $condition_vacancy['usernum']    = $vacancy_info['usernum'];
                    $condition_vacancy['village_id'] = $this->village_id;
                    
                    $data_vacancy['status'] = 3;
                    $data_vacancy['uid'] = $_POST['uid'];
                    $data_vacancy['name'] = $_POST['name'];
                    $data_vacancy['phone'] = $_POST['phone'];
                    $data_vacancy['memo'] = $_POST['memo'];
                    // $data_vacancy['housesize'] = $_POST['housesize'];
                    $data_vacancy['park_flag'] = $_POST['park_flag'];
                    $data_vacancy['type'] = 0;
                    $database_house_village_user_vacancy->where($condition_vacancy)->data($data_vacancy)->save();
                }
				
                $this->success('修改成功', U('User/index',array('page'=>$page)));
                exit;
            }

            $this->error('保存失败');
            exit;
        } else {
            //业主-查看 权限
            if (!in_array(91, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $pigcms_id = $_GET['pigcms_id'];
            $usernum = $_GET['usernum'];
            if ($pigcms_id && $usernum) {
                $bind_condition['pigcms_id'] = $pigcms_id;
                $bind_condition['usernum'] = $usernum;
                $info = D('House_village_user_bind')->where($bind_condition)->find();

                // $database_house_village_property_paylist = D('House_village_property_paylist');
                // $pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$info['pigcms_id']))->order('add_time asc')->select();
                // if(!empty($pay_list)){
                //     $first_pay_info = reset($pay_list);
                //     $end_pay_info = end($pay_list);
                //     if($first_pay_info && $end_pay_info){
                //         $info['property_month'] =  date('Y-m-d',$first_pay_info['start_time']) .' 至 '. date('Y-m-d', $end_pay_info['end_time']);
                //     }else{
                //         $info['property_month'] =  date('Y-m-d',$pay_list['start_time']) .' 至 '. date('Y-m-d', $pay_list['end_time']);
                //     }
                // }
                if(!$info['property_endtime']){
                    $now_pay_info = D('House_village_property_paylist')->where(array('bind_id'=>$info['pigcms_id']))->order('add_time desc')->field('end_time')->find();
                    $info['property_endtime'] = $now_pay_info['end_time'];
                }

                if(!$info['property_starttime']){
                    $now_pay_info = D('House_village_property_paylist')->where(array('bind_id'=>$info['pigcms_id']))->order('add_time asc')->field('start_time')->find();
                    $info['property_starttime'] = $now_pay_info['start_time'];
                }

                $database_house_village_floor = D('House_village_floor');
                if($info['floor_id']){
                    $floor_type = $database_house_village_floor->where(array('floor_id'=>$info['floor_id']))->getField('floor_type');
                    $info['floor_type_name'] = D('House_village_floor_type')->where(array('id'=>$floor_type))->getField('name');
                }
                $this->assign('info', $info);

                $condition['village_id'] = $this->village_id;
                $condition['status'] = 1;
                $floor_list = $database_house_village_floor->house_village_floor_page_list($condition , true ,'floor_id desc' , 99999);

                if(!$floor_list){
                    $this->error('数据处理有误！');
                }


                $database_house_village_user_vacancy = D('House_village_user_vacancy');
                $vacancy_where['status'] = array('in' , '1,2,3');
                $vacancy_where['village_id'] = $this->village_id;
                $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($vacancy_where ,true ,'pigcms_id desc' , 999999999);
				
                $this->assign('vacancy_list',$result['result']['list']);

                if(!$floor_list['status']){
                    $this->error($floor_list['msg']);
                }else{
                    $this->assign('floor_list' ,$floor_list['list']);
                }

                //车位信息
                $position_list = D('House_village_bind_position')->get_user_position_bind_list(array('pigcms_id'=>$pigcms_id));
                //车辆信息
                $car_list = D('House_village_bind_car')->get_user_car_bind_list(array('pigcms_id'=>$pigcms_id));
                
                // 自定义缴费项
                $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb', C('DB_PREFIX').'house_village_payment_standard'=>'ps', C('DB_PREFIX').'house_village_payment'=>'p'))->where("psb.pigcms_id= '".$pigcms_id."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id AND psb.village_id=".$this->village_id)->select();
                $payment_list = $payment_list ? $payment_list : array();

                // 自定义缴费项（车位）
                $position_payment_list = D('House_village_bind_position')->get_user_position_payment_list(array('pigcms_id'=>$pigcms_id));
                $payment_list = array_merge($payment_list, $position_payment_list);

                // dump($payment_list);
                $cycle_type = array(
                            'Y'=>'年',
                            'M'=>'月',
                            'D'=>'日',
                        );
                $this->assign('cycle_type',$cycle_type);
                $this->assign('position_list',$position_list);
                $this->assign('car_list',$car_list);
                $this->assign('payment_list',$payment_list);
                // dump($payment_list);


            }
            $this->display();
        }
    }

    // 删除缴费项目
    public function payment_del(){
        if (intval($_POST['pigcms_id'])) {
            //业主-删除缴费项 权限
            if (!in_array(114, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $res = D('House_village_payment_standard_bind')->where(array('bind_id'=>intval($_POST['bind_id']),'village_id'=>$this->village_id,'pigcms_id'=>intval($_POST['pigcms_id'])))->delete();
        }elseif (intval($_POST['position_id'])) {
            // 车位-删除缴费项 权限
            if (!in_array(262, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $res = D('House_village_payment_standard_bind')->where(array('bind_id'=>intval($_POST['bind_id']),'village_id'=>$this->village_id,'position_id'=>intval($_POST['position_id'])))->delete();
        }

        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'删除成功！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'删除失败！')));
        }
        // dump($_POST);
    }

    //添加缴费项
    public function payment_add(){
        // D('')
        // dump($_GET);

        //业主-添加缴费项 权限
        if (!in_array(97, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        $payment_list = D('House_village_payment')->where(array('village_id'=>$this->village_id))->select();
        $this->assign('payment_list',$payment_list);
        $this->display();
    }

    // 选择缴费标准
    public function payment_standard_choice(){
        // header('Content-Type: application/json; charset=utf-8');
        if (!$_POST['payment_id']) {
            exit(json_encode(array('error'=>1,'msg'=>'参数传递错误','data'=>array())));
        }
        $where = 'payment_id='.$_POST['payment_id'];
        if ($_POST['bind_type']==2) { // 绑定车位
            $where .= ' AND (pay_type=1 OR  metering_mode_type=3)';
        }else{ //绑定业主
            $where .= ' AND  metering_mode_type<>3';
        }
        $list = D('House_village_payment_standard')->where($where)->select();
        $html = '<option value="">——选择收费标准——</option>';

        $cycle_type = array(
                            'Y'=>'年',
                            'M'=>'月',
                            'D'=>'日',
                        );

        foreach ($list as $key => $value) {
            $val = '';
            if($value['pay_type'] == 1){
                $val .= '缴费模式: 固定费用;';
            }else{
                $val .= '缴费模式: 按金额*数量;';
                $val .= '计量方式: '.$value['metering_mode'].';';
            }
            $val .= '缴费金额: '.$value['pay_money'].'; ';
            $val .= '周期类型: '.$cycle_type[$value['cycle_type']].'; ';
            $val .= '缴费周期: '.$value['pay_cycle'].'('.$cycle_type[$value['cycle_type']].') / 周期; ';
            $val .= '周期上限: '.$value['max_cycle'].' (周期) ; ';
            $html.= '<option data-max_cycle="'.$value['max_cycle'].'" data-pay_type="'.$value['pay_type'].'" data-metering_mode="'.$value['metering_mode'].'" data-metering_mode_type="'.$value['metering_mode_type'].'" value="'.$value['standard_id'].'">'.$val.'</option>';
        }
        exit(json_encode(array('error'=>0,'data'=>$html)));
    }

    // 用户签订缴费项合同
    public function user_payment_add(){
        //业主-添加缴费项 权限
        if (!in_array(97, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $payment_info = D('House_village_payment')->where(array('village_id'=>$this->village_id,'payment_id'=>$_POST['payment_id']))->find();
        $standard_info = D('House_village_payment_standard')->where(array('payment_id'=>$_POST['payment_id'],'standard_id'=>$_POST['standard_id']))->find();
        
        $cycle_type = array('Y'=>'year', 'M'=>'month', 'D'=>'day', );
        $data['payment_id'] = $_POST['payment_id'];
        $data['standard_id'] = $_POST['standard_id'];
        
        $data['village_id'] = $this->village_id;
        if($_POST['start_time'] == ''){
            $_POST['start_time'] = date('Y-m-d',time());
        }

        if($_POST['cycle_sum'] == ''){
            $_POST['cycle_sum'] = $standard_info['max_cycle'];
        }
        $data['start_time'] = strtotime($_POST['start_time']);
        $data['end_time'] = strtotime($_POST['start_time'].'+'.$_POST['cycle_sum']*$standard_info['pay_cycle'].$cycle_type[$standard_info['cycle_type']]);
        $data['cycle_sum'] = $_POST['cycle_sum'];
        $data['pay_cycle'] = 0;
        $data['remarks'] = $_POST['remarks'];

        $database_house_village_user_bind = D('House_village_user_bind');
        
        if(empty($_POST['uid'])){
            $data['pigcms_id'] = $_POST['pigcms_id'];
            if($_POST['metering_mode_type'] == 1){ //房屋面积
                $housesize = D('House_village_user_bind')->where(array('pigcms_id'=>$_POST['pigcms_id']))->getField('housesize');
                $data['metering_mode_val'] = $housesize;
            } elseif($_POST['metering_mode_type'] == 3){ //车位面积
                $condition_table  = array(C('DB_PREFIX').'house_village_bind_position'=>'b',C('DB_PREFIX').'house_village_parking_position'=>'p');
                $condition_where = " `p`.`position_id` = `b`.`position_id` AND `b`.`user_id` =".$_POST['pigcms_id'];
                $condition_field = '`p`.`position_area`';
                $position_list = D()->field($condition_field)->table($condition_table)->where($condition_where)->select();
                if ($position_list) {
                    $position_area = 0;
                    foreach ($position_list as $key => $value) {
                        $position_area += $value['position_area'];
                    }
                    $data['metering_mode_val'] = $position_area;
                }else{
                    $data['metering_mode_val'] = 0;
                }
            }else{
                $data['metering_mode_val'] = $_POST['metering_mode_val'];
            }
            if($standard_info['pay_type'] == 1){
                $data['metering_mode_val'] = '';
            }
            $res = D('House_village_payment_standard_bind')->data($data)->add();

            // 发送微信通知
            if ($data['start_time']<time()) {
                $bind_condition['pigcms_id'] = $data['pigcms_id'];
                $bind_condition['village_id'] = $this->village_id;
                $user_bind_list = $database_house_village_user_bind->get_cashier_unpaid_list($bind_condition,0,0);
                if($user_bind_list['list']){
                    $database_house_village_user_bind->send_weixin_pay($this->village_id,$user_bind_list['list']);   
                }
            }
        }else{
            $pigcms_id=explode(',',$_POST['uid']); 
            foreach ($pigcms_id as $key => $value) {
                if($value > 0){
                    $data['pigcms_id'] = $value;
                    if($_POST['metering_mode_type'] == 1){
                        
                        $housesize = D('House_village_user_bind')->where(array('pigcms_id'=>$value))->getField('housesize');
                        $data['metering_mode_val'] = $housesize;
                    } elseif($_POST['metering_mode_type'] == 3){ //车位面积
                        $condition_table  = array(C('DB_PREFIX').'house_village_bind_position'=>'b',C('DB_PREFIX').'house_village_parking_position'=>'p');
                        $condition_where = " `p`.`position_id` = `b`.`position_id` AND `b`.`user_id` =".$_POST['pigcms_id'];
                        $condition_field = '`p`.`position_area`';
                        $position_list = D()->field($condition_field)->table($condition_table)->where($condition_where)->select();
                        if ($position_list) {
                            $position_area = 0;
                            foreach ($position_list as $key => $value) {
                                $position_area += $value['position_area'];
                            }
                            $data['metering_mode_val'] = $position_area;
                        }else{
                            $data['metering_mode_val'] = 0;
                        }
                    }else{
                        $data['metering_mode_val'] = $_POST['metering_mode_val'];
                    }
                    if($standard_info['pay_type'] == 1){
                        $data['metering_mode_val'] = '';
                    }
                    $res = D('House_village_payment_standard_bind')->data($data)->add();
                }
            } 
             // 发送微信通知
            if ($_POST['start_time']<time()) {
                $bind_condition['pigcms_id'] = trim($_POST['uid'],',');
                $bind_condition['village_id'] = $this->village_id;
                $user_bind_list = $database_house_village_user_bind->get_cashier_unpaid_list($bind_condition,0,0);
                if($user_bind_list['list']){
                    $database_house_village_user_bind->send_weixin_pay($this->village_id,$user_bind_list['list']);   
                }
            }
        }

        if($res){
            $this->success('保存成功！');
        }else{
            $this->error('保存失败！');
        }
    }

    // 添加业主
    public function user_add(){
        //业主-添加 权限
        if (!in_array(92, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $database_house_village_floor = D('House_village_floor');
		$vacancy_where['status'] = 1;
        $vacancy_where['village_id'] = $this->village_id;
		
		//先判断单元
		$floor_num = $database_house_village_floor->where(array('village_id'=>$this->village_id,'status'=>1))->count();
		if($floor_num <= 0){
            $this->error('请先添加单元',U('Unit/index'));
        }
		
        $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($vacancy_where ,true ,'pigcms_id desc' , 999999999);

        if(!$result['result']['list']){
            $this->error('请先导入房间',U('Unit/import_village'));
        }
        $this->assign('vacancy_list' , $result['result']['list']);

        if (IS_POST) {
            /*if(empty($_POST['usernum'])){
                $this->error('业主编号不能为空！');
            }*/

            if(empty($_POST['user_name'])){
                $this->error('业主名不能为空！');
            }

            if(empty($_POST['phone'])){
                $this->error('手机号不能为空！');
            }

            if(!preg_match('/^[0-9]{11}$/',$_POST['phone'])){
                $this->error('请输入正确的手机号！');
            }

            if(empty($_POST['floor_id'])){
                $this->error('单元名称不能为空！');
            }

            /*if(empty($_POST['floor_name'])){
                $this->error('单元名称不能为空！');
            }

            if(empty($_POST['floor_layer'])){
                $this->error('楼号不能为空！');
            }*/

            if(empty($_POST['layer_num'])){
                $this->error('层号不能为空！');
            }

            if(empty($_POST['room_num'])){
                $this->error('门牌号不能为空！');
            }

            if(empty($_POST['housesize'])){
                $this->error('房子平方不能为空！');
            }

            if($_POST['property_starttime'] > $_POST['property_endtime']){
                $this->error('请填写正确的物业时间！');
            }
            
            $_POST['property_starttime'] = strtotime($_POST['property_starttime'].'00:00:00');
            $_POST['property_endtime'] = strtotime($_POST['property_endtime'].'23:59:59');


            $floor_name = $_POST['floor_name'];
            $floor_layer = $_POST['floor_layer'];
            $layer_num = $_POST['layer_num'];
            $room_num = $_POST['room_num'];
            //$data['usernum'] = $this->village_id . '-' . $_POST['usernum'];

            $database_house_village_floor = D('House_village_floor');
            $database_house_village_user_bind = D('House_village_user_bind');
            $database_house_village_user_vacancy = D('House_village_user_vacancy');
            $database_user = D('User');
           // $where['floor_name'] = $floor_name;
          //  $where['floor_layer'] = $_POST['floor_layer'];
            $where['floor_id'] = $_POST['floor_id'] + 0;
            $where['status'] = 1;
            $where['village_id'] = $this->village_id;

            $house_village_floor_info = $database_house_village_floor->where($where)->find();
            if (!isset($house_village_floor_info)) {
                $this->error('单元不存在，请查看社区中心，单元管理-单元列表！');
            }


            $vacancy_where['status'] = 1;
            $vacancy_where['pigcms_id'] = $_POST['layer_room'] + 0;
            $vacancy_info = $database_house_village_user_vacancy->where($vacancy_where)->find();

            if (!isset($vacancy_info)) {
                $this->error('该房间信息不存在！');
            }
			
			$now_user = $database_user->get_user($_POST['phone'], 'phone');
            if ($now_user) {
                $data['uid'] = $now_user['uid'];
            }else{
				  $data['uid'] = 0;
			}
			
            //检测用户是否已存在
            $Map['usernum'] =  $vacancy_info['usernum'];
            if ($database_house_village_user_bind->field('`usernum`')->where($Map)->find()) {
                $this->error('业主名为' . $_POST['user_name'] . ' 已存在！物业编号重复');
            }
            

            $data['name'] = $_POST['user_name'];
            $data['phone'] = $_POST['phone'];
            $data['floor_id'] = $_POST['floor_id'];
            $data['water_price'] = $_POST['water_price'];
            $data['electric_price'] = $_POST['electric_price'];
            $data['gas_price'] = $_POST['gas_price'];
            $data['park_flag'] = $_POST['park_flag'] + 0;
            $data['room_addrss'] = $room_num;
            $data['usernum'] = $vacancy_info['usernum'];
            $data['vacancy_id'] = $_POST['vacancy_id'] + 0;

            if($memo = htmlspecialchars(trim($_POST['memo']))){
                $data['memo'] = $memo;
            }

            if(isset($_POST['park_flag'])){
                $data['park_price'] = $_POST['park_price'];
            }

            $data['housesize'] = $_POST['housesize'];
            $data['layer_num'] = $layer_num;
            $data['address'] = $house_village_floor_info['floor_name'] . $house_village_floor_info['floor_layer'] . $layer_num . $room_num;
            $data['village_id'] = $this->village_id;
            $data['add_time'] = time();

            $data['property_starttime'] = $_POST['property_starttime'];
            $data['property_endtime'] = $_POST['property_endtime'];

            $insert_id = $database_house_village_user_bind->data($data)->add();
            if($insert_id){
				
				//更改房间房主信息  uid,name,phone,type=0,status=3,housesize,park_flag,add_time,				
				$data_info['uid'] = $data['uid'];
				$data_info['name'] = $_POST['user_name'];
				$data_info['phone'] = $_POST['phone'];
				$data_info['type'] = 0;
				$data_info['status'] = 3;
				$data_info['housesize'] = $_POST['housesize'];
				$data_info['park_flag'] = $_POST['park_flag'];
				$data_info['add_time'] = time();
				
				$where_info['pigcms_id'] = $_POST['layer_room'];
				$where_info['village_id'] = $this->village_id;
				
				
				$database_house_village_user_vacancy->data($data_info)->where($where_info)->save();
				
                $this->success('添加成功！',U('index'));
            }else{
                $this->error('添加失败！');
            }
        } else {
            $database_house_village_floor = D('House_village_floor');
            $condition['village_id'] = $this->village_id;
            $condition['status'] = 1;
            $floor_list = $database_house_village_floor->house_village_floor_page_list($condition , true ,'floor_id desc' , 99999);

            if(!$floor_list){
                $this->error('数据处理有误！');
            }

            if(!$floor_list['status']){
                $this->error($floor_list['msg']);
            }else{

                if(!count($floor_list['list']['list'])){
                    $this->error('单元不存在，请先添加！');
                }

                $this->assign('floor_list' ,$floor_list['list']);
                $this->display();
            }

        }
    }


    public function ajax_get_layer(){
        if(IS_AJAX){
            $floor_id = $_POST['floor_id'] + 0;
            $database_house_village_user_vacancy = D('House_village_user_vacancy');

            $where['floor_id'] = $floor_id;
            $where['status'] = 1;
            $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($where ,true ,'pigcms_id desc' , 999999999);

            if(!$result){
                exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
            }else{
                if(!$result['status']){
                    exit(json_encode(array('status'=>0,'msg'=>$result['msg'])));
                }else{
                    exit(json_encode(array('status'=>1,'list'=>$result['result']['list'])));
                }
            }
        }else{
            $this->error('访问页面有误！~~~~');
        }
    }


    public function ajax_user_bind(){
        //检测用户是否已存在
        $Map['usernum'] =  $this->village_id . '-' . $_POST['usernum'];
        $database_house_village_user_bind = D('House_village_user_bind');
        if ($database_house_village_user_bind->field('`usernum`')->where($Map)->find()) {
            exit(json_encode(array('status'=>1)));
        }else{
            exit(json_encode(array('status'=>0)));
        }
    }

    // 导入业主
    public function user_import()
    {
        //业主-添加 权限
        if (!in_array(92, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if (IS_POST) {
            if ($_FILES['file']['error'] != 4) {
                set_time_limit(0);
				
				import('ORG.Util.Dir');
				Dir::delDirnotself('./upload/excel/villageuser');
				
                $upload_dir = './upload/excel/villageuser/' . date('Ymd') . '/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 10 * 1024 * 1024;
                $upload->allowExts = array('xls', 'xlsx');
                $upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
                $upload->savePath = $upload_dir;
                $upload->thumb = false;
                $upload->thumbType = 0;
                $upload->imageClassPath = '';
                $upload->thumbPrefix = '';
                $upload->saveRule = 'uniqid';
                if ($upload->upload()) {
                    $uploadList = $upload->getUploadFileInfo();
                    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
                    $path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                    $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                    $objReader = PHPExcel_IOFactory::createReader($fileType);
                    $excelObj = $objReader->load($path);
                    $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
					unlink($path);	//删除物业上传的excel表格

                    $database_user = D('User');
                    $error_arr = array();
                    if (!empty($result) && is_array($result)) {
                        unset($result[1]);
                        $last_user_id = 0;
                        $err_msg = '';
                        foreach ($result as $kk => $vv) {
                            if (array_sum($vv) == 0) {
                                continue;
                            }
                            if ($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null && $vv['F'] === null && $vv['G'] === null && $vv['H'] === null && $vv['I'] === null && $vv['J'] === null && $vv['K'] === null && $vv['L'] === null && $vv['M'] === null && $vv['N'] === null) continue;

                            // $vv['N'] = floatval($vv['N']);
                            if (empty($vv['A'])) {
                                $vv['Q'] = '请填写业主编号！';
                                $error_arr[] = $vv;
                                // $err_msg = '请填写业主编号！';
                                continue;
                            }
                            if (empty($vv['B'])) {
                                $vv['Q'] = '请填写业主名！';
                                $error_arr[] = $vv;
                                // $err_msg = '请填写业主名！';
                                continue;
                            }
                            if (empty($vv['C'])) {
                                $vv['Q'] = '请填写手机号！';
                                $error_arr[] = $vv;
                                // $err_msg = '请填写手机号！';
                                continue;
                            }

                            if(!preg_match('/^[0-9]{11}$/',$vv['C'])){
                                $vv['Q'] = '请填写正确的手机号！';
                                $error_arr[] = $vv;
                            }

                            if (empty($vv['I'])) {
                                $vv['Q'] = '请填写单元名称！';
                                $error_arr[] = $vv;
                                // $err_msg = '请填写单元名称！';
                                continue;
                            }

                            if (empty($vv['J'])) {
                                $vv['Q'] = '请填写楼号！';
                                $error_arr[] = $vv;
                                // $err_msg = '请填写楼号！';
                                continue;
                            }


                            if (empty($vv['K'])) {
                                $vv['Q'] = '请填写层号！';
                                $error_arr[] = $vv;
                                // $err_msg = '请填写层号！';
                                continue;
                            }


                            if (empty($vv['L'])) {
                                $vv['Q'] = '请填写门牌号！';
                                $error_arr[] = $vv;
                                // $err_msg = '请填写门牌号！';
                                continue;
                            }

                            // if (empty($vv['M'])) {
                            //     $err_msg = '请填写房子平方！';
                            //     continue;
                            // }

                            $floor_name = htmlspecialchars(trim($vv['I']), ENT_QUOTES);
                            $floor_layer = htmlspecialchars(trim($vv['J']), ENT_QUOTES);
                            $where['floor_name'] = $floor_name;
                            $where['floor_layer'] = $floor_layer;
                            $where['status'] = 1;
                            $where['village_id'] = $this->village_id;
                            $database_house_village_floor = D('House_village_floor');
                            $house_village_floor_info = $database_house_village_floor->where($where)->find();
                            if (!$house_village_floor_info) {
                                $vv['Q'] = '单元不存在，请查看社区中心，单元管理-单元列表!';
                                $error_arr[] = $vv;
                                // $err_msg = '单元不存在，请查看社区中心，单元管理-单元列表！';
                                continue;
                            }
							
							//检查房间是否存在或已绑定
							$vwhere['village_id'] = $this->village_id;
							$vwhere['floor_id'] =  $house_village_floor_info['floor_id'];
							$vwhere['layer'] = htmlspecialchars(trim($vv['K']), ENT_QUOTES);
							$vwhere['room'] = htmlspecialchars(trim($vv['L']), ENT_QUOTES);
							$vwhere['type'] = 0;
							$room_info = D('House_village_user_vacancy')->where($vwhere)->find();

							if(!$room_info){
								$err_msg = htmlspecialchars(trim($vv['I']), ENT_QUOTES).htmlspecialchars(trim($vv['J']), ENT_QUOTES). htmlspecialchars(trim($vv['K']), ENT_QUOTES).htmlspecialchars(trim($vv['L']), ENT_QUOTES)."房间不存在";
                                $vv['Q'] = $err_msg;
                                $error_arr[] = $vv;
                                continue;
							}elseif($room_info['status']!=1 || $room_info['is_del']==1 || ($room_info['name']!="" && $room_info['phone']!="")){
								$err_msg = htmlspecialchars(trim($vv['J']), ENT_QUOTES).htmlspecialchars(trim($vv['I']), ENT_QUOTES). htmlspecialchars(trim($vv['K']), ENT_QUOTES).'层'.htmlspecialchars(trim($vv['L']), ENT_QUOTES)."可能被绑定/禁用/删除";
                                $vv['Q'] = $err_msg;
                                $error_arr[] = $vv;
                                continue;
							}
							

                            $tmpdata = array();
                            $tmpdata['usernum'] =$this->village_id . '-' . htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                            //检测用户是否已存在
                            if (D('House_village_user_bind')->field('`usernum`')->where(array('usernum' => $tmpdata['usernum']))->find()) {
                                $err_msg = '业主名为' . $vv['B'] . ' 已存在！物业编号重复';
                                $vv['Q'] = $err_msg;
                                $error_arr[] = $vv;
                                continue;
                            }
                            $tmpdata['name'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                            $tmpdata['phone'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $tmpdata['water_price'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
                            $tmpdata['electric_price'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                            $tmpdata['gas_price'] = htmlspecialchars(trim($vv['F']), ENT_QUOTES);
                            $tmpdata['park_flag'] = htmlspecialchars(trim($vv['G']), ENT_QUOTES);
                            $tmpdata['park_price'] = htmlspecialchars(trim($vv['H']), ENT_QUOTES);
                            $tmpdata['layer_num'] = $vv['K'];
                            $room_addrss = htmlspecialchars(trim($vv['L']), ENT_QUOTES);
                            $tmpdata['room_addrss'] = $room_addrss;
                            $tmpdata['address'] = $floor_name . $floor_layer . htmlspecialchars(trim($vv['K']), ENT_QUOTES) .$room_addrss;
                            $tmpdata['floor_id'] = $house_village_floor_info['floor_id'];
                            


                            //$van = M('House_village_user_vacancy')->where(array('floor_id'=>$tmpdata['floor_id'],'room'=>$room_addrss,'village_id'=>$this->village_id))->find();
                            //$tmpdata['vacancy_id'] = $van['pigcms_id']?$van['pigcms_id']:0;
                            $tmpdata['village_id'] = $this->village_id;
                            if($memo = htmlspecialchars(trim($vv['M']), ENT_QUOTES)){
                                $tmpdata['memo'] = $memo;
                            }


                            $user = $database_user->get_user($tmpdata['phone'], 'phone');
                            if ($user) {
                                $tmpdata['uid'] = $user['uid'];
                            }

                            $tmpdata['add_time'] = time();

                            if($_POST['property_starttime'] > $_POST['property_endtime']){
                                $error_arr[] = $vv;
                                $vv['Q'] = '请填写正确的物业时间！';
                                // $this->error('请填写正确的物业时间！');
                            }
                            $tmpdata['property_starttime'] = strtotime(trim($vv['N']));
                            $tmpdata['property_endtime'] = strtotime(trim($vv['O']));
                            $tmpdata['vacancy_id'] = $room_info['pigcms_id'];
                            if($room_info['housesize'] > 0.00){
                                $tmpdata['housesize'] = $room_info['housesize'];
                            }else{
                                $tmpdata['housesize'] = htmlspecialchars(trim($vv['P']), ENT_QUOTES);
                            }


                            $last_user_id = D('House_village_user_bind')->data($tmpdata)->add();
                            if (!$last_user_id) {
                                $err_msg = '业主名为' . $vv['B'] . ' 导入失败！';
                                $error_arr[] = $vv;
                                $vv['Q'] = $err_msg ;
                            }else{
								//导入成功 绑定此房间
								$data_vacancy = array();
								$data_vacancy['uid'] = $tmpdata['uid'] ? $tmpdata['uid'] : 0;
								$data_vacancy['status'] = 3;
								$data_vacancy['name'] = $tmpdata['name'];
								$data_vacancy['phone'] = $tmpdata['phone'];
								$data_vacancy['housesize'] = $tmpdata['housesize'];
								$data_vacancy['park_flag'] = $tmpdata['park_flag'];
								D('House_village_user_vacancy')->where($vwhere)->data($data_vacancy)->save();	
							}
                        }
                        if (empty($error_arr)) {
                            $this->success('导入成功');
                            exit;
                        } else {
                            $num = count($error_arr);
                            // echo "<script language='JavaScript' type='text/javascript'>alert('失败{$num}条，点击下载！')</script>";
                            //导出失败信息
                            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
                            error_reporting(E_ALL);  
                            date_default_timezone_set('Europe/London');  
                            $objExcel = new PHPExcel();  
                      
                            $title = $this->village['village_name'] . '社区-业主导入失败列表';
                            /*以下是一些设置 ，什么作者  标题之类的*/  
                             $objExcel->getProperties()->setCreator($title)  
                               ->setLastModifiedBy($title)  
                               ->setTitle($title)  
                               ->setSubject("失败信息导出")  
                               ->setDescription("备份数据")  
                               ->setKeywords("excel")  
                               ->setCategory("result file");  
                            
                            $i = 0;
                            $objExcel->createSheet();
                            $objExcel->setActiveSheetIndex($i);
                            $objExcel->getActiveSheet()->setTitle($title);
                            $objActSheet = $objExcel->getActiveSheet();
                            $objActSheet->setCellValue('A1', '业主编号');
                            $objActSheet->setCellValue('B1', '业主名');
                            $objActSheet->setCellValue('C1', '手机号');
                            $objActSheet->setCellValue('D1', '水费总欠费');
                            $objActSheet->setCellValue('E1', '电费总欠费');
                            $objActSheet->setCellValue('F1', '燃气费总欠费');
                            $objActSheet->setCellValue('G1', '是否有停车位（1有0无）');
                            $objActSheet->setCellValue('H1', '停车费总欠费');
                            $objActSheet->setCellValue('I1', '单元名称');
                            $objActSheet->setCellValue('J1', '楼号');
                            $objActSheet->setCellValue('K1', '层号');
                            $objActSheet->setCellValue('L1', '门牌号');
                            $objActSheet->setCellValue('M1', '备注');
                            $objActSheet->setCellValue('N1', '物业开始时间');
                            $objActSheet->setCellValue('O1', '物业结束时间');
                            $objActSheet->setCellValue('P1', '房子平方(计算物业费使用)');
                            $objActSheet->setCellValue('Q1', '失败信息');
                            $index = 2;
                            foreach ($error_arr as  $value) {  
                                $objActSheet->setCellValueExplicit('A' . $index, $value['A']);
                                $objActSheet->setCellValueExplicit('B' . $index, $value['B']);
                                $objActSheet->setCellValueExplicit('C' . $index, $value['C']);
                                $objActSheet->setCellValueExplicit('D' . $index, $value['D']);
                                $objActSheet->setCellValueExplicit('E' . $index, $value['E']);
                                $objActSheet->setCellValueExplicit('F' . $index, $value['F']);
                                $objActSheet->setCellValueExplicit('G' . $index, $value['G']);
                                $objActSheet->setCellValueExplicit('H' . $index, $value['H']);
                                $objActSheet->setCellValueExplicit('I' . $index, $value['I']);
                                $objActSheet->setCellValueExplicit('J' . $index, $value['J']);
                                $objActSheet->setCellValueExplicit('K' . $index, $value['K']);
                                $objActSheet->setCellValueExplicit('L' . $index, $value['L']);
                                $objActSheet->setCellValueExplicit('M' . $index, $value['M']);
                                $objActSheet->setCellValueExplicit('N' . $index, $value['N']);
                                $objActSheet->setCellValueExplicit('O' . $index, $value['O']);
                                $objActSheet->setCellValueExplicit('P' . $index, $value['P']);
                                $objActSheet->setCellValueExplicit('Q' . $index, $value['Q']);
                                $index++;
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
                            header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
                            header("Content-Transfer-Encoding:binary");
                            $objWriter->save('php://output');
                            exit();
                        }
                    
                        // if (!empty($last_user_id)) {
                        //     $this->success('导入成功');
                        //     exit;
                        // } else {
                        //     $this->error('导入失败！原因：' . $err_msg);
                        //     exit;
                        // }
                    }
                } else {
                    $this->error($upload->getErrorMsg());
                    exit;
                }
            }
            $this->error('文件上传失败');
            exit;
        } else {
            $this->display();
        }
    }

    // 欠费明细
    public function pay_detail()
    {
        //业主-查看欠费明细 权限
        if (!in_array(118, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->village_id;
        $usernum = $_GET['usernum'];

        if ($village_id && $usernum) {
            $list = D('House_village_user_paylist')->get_limit_list_page($usernum, $village_id);

            $this->assign('user_list', $list);
        }

        $this->display();
    }

    public function pay_one_del(){
        //业主-删除欠费明细 权限
        if (!in_array(119, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->village_id;
        $pigcms_id = $_GET['pigcms_id'];

        $pay_condition['village_id'] = $village_id;
        $pay_condition['pigcms_id'] = $pigcms_id;
        $database_house_village_user_paylist = D('House_village_user_paylist');
        $insert_id = $database_house_village_user_paylist->where($pay_condition)->delete();

        if($insert_id){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
        echo $pigcms_id;
    }

    // 业主管理-导入账单明细
    public function detail_import()
    {
        //业主管理-导入账单明细 权限
        if (!in_array(117, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if (IS_POST) {
            if (!$_POST['paytime']) {
                $this->error('请选择时间');
                exit;
            }
            $yearArray = explode('年', $_POST['paytime']);
            $year = $yearArray[0];
            $m = str_replace('月', '', $yearArray[1]);

            unset($_POST['paytime']);
            $_POST['ydate'] = $year;
            $_POST['mdate'] = intval($m);

            if ($_FILES['file']['error'] != 4) {
                $upload_dir = './upload/house/excel/paydetail/' . date('Ymd') . '/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 10 * 1024 * 1024;
                $upload->allowExts = array('xls', 'xlsx');
                $upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
                $upload->savePath = $upload_dir;
                $upload->thumb = false;
                $upload->thumbType = 0;
                $upload->imageClassPath = '';
                $upload->thumbPrefix = '';
                $upload->saveRule = 'uniqid';
                if ($upload->upload()) {
                    $uploadList = $upload->getUploadFileInfo();
                    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
                    $path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                    $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                    $objReader = PHPExcel_IOFactory::createReader($fileType);
                    $excelObj = $objReader->load($path);
                    $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);

                    $old_end_user_id = D('House_village_user_paylist')->field('pigcms_id')->order('pigcms_id DESC')->find();

                    if (!empty($result) && is_array($result)) {
                        unset($result[1]);
                        $last_user_id = 0;
                        $err_msg = '';
                        foreach ($result as $kk => $vv) {
                            if ($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null && $vv['F'] === null && $vv['G'] === null && $vv['H'] === null) continue;

                            if (empty($vv['A'])) {
                                $err_msg = '请填写业主编号！';
                                continue;
                            }
                            // if (empty($vv['B'])) {
                            //     $err_msg = '请填写业主名！';
                            //     continue;
                            // }
                            // if (empty($vv['C'])) {
                            //     $err_msg = '请填写手机号！';
                            //     continue;
                            // }
                            //if (empty($vv['I'])) {
                            //    $err_msg = '请填写住址！';
                            //    continue;
                            //}

                            $tmpdata = array();
                            $tmpdata['mdate'] = $_POST['mdate'];
                            $tmpdata['ydate'] = $_POST['ydate'];
                            $tmpdata['village_id'] = $this->village_id;
                            $tmpdata['usernum'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                            //检测业主是否已经导入
                            $condition = array('usernum' => $tmpdata['usernum'], 'ydate' => $tmpdata['ydate'], 'mdate' => $tmpdata['mdate']);
                            $pay_list = D('House_village_user_paylist')->field('`usernum`')->where($condition)->find();
                            if ($pay_list) {
                                $err_msg = '业主 ' . $vv['B'] . ' 当月帐单已导入';
                                continue;
                            }

                            $conditionBind = array('village_id' => $this->village_id, 'usernum' => $tmpdata['usernum']);
                            //$bindInfo = D('House_village_user_bind')->field('`pigcms_id`,`usernum`,`uid`,`housesize`')->where($conditionBind)->find();
                            $bindInfo = D('House_village_user_bind')->where($conditionBind)->find();
                            if (!$bindInfo) {
                                $err_msg = '通过业主编号没找到 ' . $vv['B'];
                                continue;
                            }

                            //if($bindInfo['address'] != htmlspecialchars(trim($vv['I']), ENT_QUOTES)){
                            //    $err_msg = '地址填写不一致！ ';
                            //    continue;
                            //}

                            $tmpdata['name'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                            $tmpdata['phone'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $tmpdata['use_water'] = floatval(htmlspecialchars(trim($vv['D']), ENT_QUOTES));
                            $tmpdata['use_electric'] = floatval(htmlspecialchars(trim($vv['E']), ENT_QUOTES));
                            $tmpdata['use_gas'] = floatval(htmlspecialchars(trim($vv['F']), ENT_QUOTES));
                            $tmpdata['use_property'] = intval(htmlspecialchars(trim($vv['G']), ENT_QUOTES));
                            $tmpdata['use_park'] = intval(htmlspecialchars(trim($vv['H']), ENT_QUOTES));
                           // $tmpdata['address'] = htmlspecialchars(trim($vv['I']), ENT_QUOTES);
                            $tmpdata['bind_id'] = $bindInfo['pigcms_id'];
                            $tmpdata['uid'] = $bindInfo['uid'];
                            $tmpdata['add_time'] = $_SERVER['REQUEST_TIME'];
							
							//print_r($tmpdata);
							//exit;
							$floor_where['floor_id'] = $bindInfo['floor_id'];
							$floor_where['status'] = 1;
							$database_house_village_floor = D('House_village_floor');
							$floor_info = $database_house_village_floor->house_village_floor_detail($floor_where);
							$floor_info = $floor_info['detail'];
							
                            if ($tmpdata['use_water']) {
								if(($floor_info['water_fee'] == '0.00') || (!isset($floor_info['water_fee']))){
                                    $water_price = $this->village['water_price'];
                                }else{
                                    $water_price = $floor_info['water_fee'];
                                }
                                $tmpdata['water_price'] = $tmpdata['use_water'] * $water_price;
                                D('House_village_user_bind')->where($conditionBind)->setInc('water_price', $tmpdata['water_price']);
                            }
                            if ($tmpdata['use_electric']) {
								if(($floor_info['electric_fee'] == '0.00') || (!isset($floor_info['electric_fee']))){
                                    $electric_price = $this->village['electric_price'];
                                }else{
                                    $electric_price = $floor_info['electric_fee'];
                                }
                                $tmpdata['electric_price'] = $tmpdata['use_electric'] * $electric_price;
                                D('House_village_user_bind')->where($conditionBind)->setInc('electric_price', $tmpdata['electric_price']);
                            }
                            if ($tmpdata['use_gas']) {
								if(($floor_info['gas_fee'] == '0.00') || (!isset($floor_info['gas_fee']))){
                                    $gas_price = $this->village['gas_price'];
                                }else{
                                    $gas_price = $floor_info['gas_fee'];
                                }
                                $tmpdata['gas_price'] = $tmpdata['use_gas'] * $gas_price;
                                D('House_village_user_bind')->where($conditionBind)->setInc('gas_price', $tmpdata['gas_price']);
                            }
                            if ($tmpdata['use_property']) {
                                if(($floor_info['property_fee'] == '0.00') || (!isset($floor_info['property_fee']))){
                                    $property_fee = $this->village['property_price'];
                                }else{
                                    $property_fee = $floor_info['property_fee'];
                                }
                                $tmpdata['property_price'] = $property_fee * $bindInfo['housesize'];
                                D('House_village_user_bind')->where($conditionBind)->setInc('property_price', $tmpdata['property_price']);
                            }
                            if ($tmpdata['use_park']) {
                                $tmpdata['park_price'] = $this->village['park_price'];
                                D('House_village_user_bind')->where($conditionBind)->setInc('park_price', $this->village['park_price']);
                            }
							
                            $last_user_id = D('House_village_user_paylist')->data($tmpdata)->add();
                            if (!$last_user_id) {
                                $err_msg = $vv['B'] . ' 帐单导入失败';
                            }
                        }
                        if (!empty($last_user_id)) {
                            // 模板消息
                            $this->send($old_end_user_id['pigcms_id'], $last_user_id);

                        } else {
                            $this->error('导入失败！原因：' . $err_msg);
                            exit;
                        }
                    }
                } else {
                    $this->error($upload->getErrorMsg());
                    exit;
                }
            }
            $this->error('文件上传失败');
            exit;
        } else {
            $this->display();
        }
    }

    // 缴费明细
    public function orders()
    {
        //业主-查看缴费明细 权限
        if (!in_array(120, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        //$uid = $_GET['uid'];
        $bind_id = $_GET['bind_id'] + 0;
        if ($bind_id) {
            //$condtion['uid'] = $uid;
            $condition['bind_id'] = $bind_id;
            $condition['village_id'] = $this->village_id;
            $condition['paid'] = 1;

            import('@.ORG.merchant_page');
            $count_order = D('House_village_pay_order')->where($condition)->count();
            $p = new Page($count_order, 20, 'page');
            $order_list = D('House_village_pay_order')->where($condition)->order('order_id desc')->limit($p->firstRow . ',' . $p->listRows)->select();

            $database_house_village_property_paylist = D('House_village_property_paylist');
            $pay_list = $database_house_village_property_paylist->where(array('village_id'=>$this->village_id))->select();
            $totalmoney = 0;
            if(!empty($pay_list)){
                foreach($order_list as $Key=>$order){
                    $totalmoney += $order['money'];
                    foreach($pay_list as $pay_info){
                        if($order['order_id'] ==  $pay_info['order_id']){
                            $order_list[$Key]['property_time_str'] = date('Y-m-d',$pay_info['start_time']) . '至' . date('Y-m-d',$pay_info['end_time']);
                        }
                    }
                }
            }

            $result['order_list'] = $order_list;
            $result['pagebar'] = $p->show();
            $this->assign('totalmoney', $totalmoney);
            $this->assign('order_list', $result);

        }
        $this->display();
    }

    public function village_order()
    {
        $village_id = $this->village_id;
        if ($village_id) {
            $condition['village_id'] = $this->village_id;
            $condition['paid'] = 1;

            $result = D('House_village_pay_order')->get_limit_list_page($condition);

            $this->assign('order_list', $result);
        }

        $this->display();
    }

    public function change()
    {
        $village_id = $this->village_id;
        $strids = isset($_POST['strids']) ? htmlspecialchars($_POST['strids']) : '';
        if ($strids) {
            $array = explode(',', $strids);
            $usernums = $orderids = array();
            foreach ($array as $val) {
                $t = explode('_', $val);
                if ($t[1]) {
                    $orderids[] = $t[1];
                }
            }

            $orderids && D('House_village_pay_order')->where(array('village_id' => $village_id, 'order_id' => array('in', $orderids)))->save(array('is_pay_bill' => 1));
        }
        exit(json_encode(array('error_code' => 0)));
    }

    public function send($old_end_user_id, $last_user_id)
    {
        $users = D('House_village_user_bind')->get_pay_list_open($this->village_id, 20, $last_user_id, $old_end_user_id);

        if ($users) {
            $page = $_GET['page'] ? intval($_GET['page']) : 1;

            if ($page > $users['totalPage']) {
                $this->success('导入成功！');
                exit;
            } else {
                // 模板消息
                foreach ($users['user_list'] as $userInfo) {
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_my_pay&village_id=' . $this->village_id;

                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，社区发布了一条账单信息', 'keynote2' => $userInfo['address'], 'keynote1' => $this->village['property_name'], 'remark' => '请点击查看详细信息！'));
                }

                $this->success('发送微信消息完毕，正在跳转下一页', U('User/detail_import', array('page' => $page + 1)));
                exit;
            }
        } else {
            $this->success('导入成功。',U('index'));
            exit;
        }
    }

    // 发送微信通知
    public function send_property(){
        if(IS_AJAX){
            $database_house_village_user_bind = D('House_village_user_bind');
            $database_house_village_pay_order = D('House_village_pay_order');
            $database_user =D('User');
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            if($_POST['is_collective']){ 
                //业主-群发微信消息 权限
                if (!in_array(100, $this->house_session['menus'])) {
                    exit(json_encode(array('msg'=>'对不起，您没有权限执行此操作','status'=>0)));
                }
                
                $bind_condition['village_id'] = $this->village_id;
                $bind_condition['parent_id'] = 0;
                $user_bind_list = $database_house_village_user_bind->where($bind_condition)->select();

                $href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_my_pay&village_id='.$this->village_id;
                foreach($user_bind_list as $Key => $User){
                    $now_user = $database_user->get_user($User['uid']);
                    //添加一个统计物业总时间
//                  $wy_num = 0;
//                  $wy_num_buy = $database_house_village_pay_order->where(array('bind_id'=>$house_village_user_bind_info['pigcms_id'],'uid'=>$house_village_user_bind_info['uid'],'order_type'=>'property','paid'=>1))->Sum('property_month_num');
//                  $wy_num_sbuy = $database_house_village_pay_order->where(array('bind_id'=>$house_village_user_bind_info['pigcms_id'],'uid'=>$house_village_user_bind_info['uid'],'order_type'=>'property','paid'=>1))->Sum('presented_property_month_num');
//                  $wy_num = $wy_num_buy + $wy_num_sbuy + 0;

                    if(!empty($now_user['openid'])){
                        if($User['add_time'] > 0){
                            $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => ' 尊敬的业主，您有新的账单！', 'keynote2' => $User['address'], 'keynote1' => $this->village['property_name'], 'remark' => '请点击查看详细信息！'));
                        }else{
                            $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => ' 尊敬的业主，您有新的账单！', 'keynote2' => $User['address'], 'keynote1' => $this->village['property_name'], 'remark' => '请点击查看详细信息！'));
                        }
                    }
                }
            }else{
                //业主-发送微信通知 权限
                if (!in_array(96, $this->house_session['menus'])) {
                    exit(json_encode(array('msg'=>'对不起，您没有权限执行此操作','status'=>0)));
                }
                
                $pigcms_id = $_POST['pigcms_id'] + 0;
                $usernum = $_POST['usernum'];

                if(empty($pigcms_id) || empty($usernum)){
                    exit(json_encode(array('msg'=>'传递参数有误！','status'=>0)));
                }

                $bind_condition['pigcms_id'] = $pigcms_id;
                $bind_condition['usernum'] = $usernum;

                $house_village_user_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
                if(!$house_village_user_bind_info){
                    exit(json_encode(array('msg'=>'该用户不存在！','status'=>0)));
                }

                $now_user = $database_user->get_user($house_village_user_bind_info['uid']);
                
                //添加一个统计物业总时间
                $wy_num_buy = $database_house_village_pay_order->where(array('bind_id'=>$house_village_user_bind_info['pigcms_id'],'uid'=>$house_village_user_bind_info['uid'],'order_type'=>'property','paid'=>1))->Sum('property_month_num');
                $wy_num_sbuy = $database_house_village_pay_order->where(array('bind_id'=>$house_village_user_bind_info['pigcms_id'],'uid'=>$house_village_user_bind_info['uid'],'order_type'=>'property','paid'=>1))->Sum('presented_property_month_num');
                $wy_num = $wy_num_buy + $wy_num_sbuy + 0;
                
                if(!empty($now_user['openid'])){
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_my_pay&village_id='.$this->village_id;
                    $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>   '尊敬的业主，您有新的账单！', 'keynote1' =>$this->village['property_name'], 'keynote2' =>$house_village_user_bind_info['address'], 'remark' => '请点击查看详细信息！'));
                }
            }
            exit(json_encode(array('msg'=>'发送微信消息完毕！','status'=>1)));
        }else{
            $this->error('访问页面有误！~~~');
        }
    }

    // 发送微信通知 新 未缴账单
    public function send_weixin_notice(){
        // 全部发送 群发 单个 
        // 业主 家属
        if(IS_AJAX){
            $database_house_village_user_bind = D('House_village_user_bind');
            $database_user =D('User');
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));

            $bind_condition['village_id'] = $this->village_id;
            if ($_POST['is_all']) {
                //未缴-全部发微信消息 权限
                if (!in_array(264, $this->house_session['menus'])) {
                    exit(json_encode(array('msg'=>'对不起，您没有权限执行此操作','status'=>0)));
                }

                $bind_condition['parent_id'] = 0;
                $user_bind_list = $database_house_village_user_bind->get_cashier_unpaid_list($bind_condition,0,0);
            } elseif($_POST['ids']) {
                //未缴-群发微信消息 权限
                if (!in_array(265, $this->house_session['menus'])) {
                    exit(json_encode(array('msg'=>'对不起，您没有权限执行此操作','status'=>0)));
                }
                $bind_condition['pigcms_id'] = trim($_POST['ids'],',');
                $user_bind_list = $database_house_village_user_bind->get_cashier_unpaid_list($bind_condition,0,0);

            }else{
                 //未缴-发送微信通知 权限
                if (!in_array(266, $this->house_session['menus'])) {
                    exit(json_encode(array('msg'=>'对不起，您没有权限执行此操作','status'=>0)));
                }
                
                $pigcms_id = $_POST['pigcms_id'] + 0;

                if(empty($pigcms_id)){
                    exit(json_encode(array('msg'=>'传递参数有误！','status'=>0)));
                }

                $bind_condition['pigcms_id'] = $pigcms_id;
                $user_bind_list = $database_house_village_user_bind->get_cashier_unpaid_list($bind_condition,0,0);
                if(!$user_bind_list['list']){
                    exit(json_encode(array('msg'=>'该用户不存在！','status'=>0)));
                }
            }

            $href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_my_pay&village_id='.$this->village_id;
            
            if(!$user_bind_list['list']){
                exit(json_encode(array('msg'=>'发送失败！','status'=>0)));
            }

            //判断发送业主 或 家属
            $send_user_type = $_POST['send_user_type'] ? $_POST['send_user_type'] : 1;

            // 发送
            $database_house_village_user_bind->send_weixin_pay($this->village_id,$user_bind_list['list'],$send_user_type);
            
            exit(json_encode(array('msg'=>'发送微信消息完毕！','status'=>1)));
        }else{
            $this->error('访问页面有误！~~~');
        }
    }

    // 删除业主
    public function user_delete(){
        //业主-删除 权限
        if (!in_array(94, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $pigcms_id = $_GET['pigcms_id'] + 0;
        $usernum = $_GET['usernum'];
        $database_house_village_user_bind = D('House_village_user_bind');
        $bind_condition['pigcms_id'] = $pigcms_id;
        $bind_condition['usernum'] = $usernum;
        $bind_condition['parent_id'] = 0;
        $now_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
        if(!$now_bind_info){
            $this->error('该信息不存在！');
        }

        $insert_id = $database_house_village_user_bind->where($bind_condition)->delete();
        // 查询一下是否存在其他状态的业主信息，全部删除
        $where = array('village_id' => $now_bind_info['village_id'], 'room_addrss' => $now_bind_info['room_addrss'],'layer_num' => $now_bind_info['layer_num'],'floor_id' => $now_bind_info['floor_id'], 'type' => array('in', '0, 3'));
        $other = $database_house_village_user_bind->where($where)->field('pigcms_id')->select();
        if (!empty($other)) {
            foreach ($other as $val) {
                $database_house_village_user_bind->where(array('pigcms_id' => $val['pigcms_id']))->delete();
            }
        }
        if($insert_id){
			$family_condition['village_id'] = $now_bind_info['village_id'];
			$family_condition['parent_id'] = $now_bind_info['pigcms_id'];
			$database_house_village_user_bind->where($family_condition)->delete();
			
			//清空房间
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			$data['uid'] = 0;
			$data['status'] = 1;
			$data['name'] = "";
			$data['phone'] = "";
			$data['type'] = 0;
			$data['park_flag'] = 0;
            $database_house_village_user_vacancy->where(array('phone'=>$now_bind_info['phone'],'pigcms_id'=>$now_bind_info['vacancy_id'],'village_id'=>$now_bind_info['village_id']))->data($data)->save();
			
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }

    // 业主 数据统计
    public function user_data(){
        //业主-数据统计 权限
        if (!in_array(99, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $database_house_village_user_bind = D('House_village_user_bind');
        $where['village_id'] = $this->village_id;
        $where['parent_id'] = 0;
        //业主分析表start
        $user_list = $database_house_village_user_bind->where($where)->group('uid')->field('*,count(*) as house_sum')->select();

        $uidArr = array();
        foreach($user_list as $user){
            $uidArr[] = $user['uid'];
        }

        $database_user = D('User');
        $user_condition['uid'] = array('in',$uidArr);
        $user_condition['open_id'] != '';
        $wx_user_list = $database_user->where($user_condition)->select();

        $wx_sum = 0;
        foreach($user_list as $Key => $user){
            foreach($wx_user_list as $wx_key => $wx_user){
                if(($user['uid'] == $wx_user['uid']) && !empty($wx_user['openid'])){
                    $wx_sum += $user['house_sum'];
                }
            }
        }

        $count_user = 0;
        foreach($user_list as $user){
            $count_user += $user['house_sum'];
        }
        $this->assign('count_user' , $count_user);
        $this->assign('wx_user_count' , $wx_sum);
        //业主分析表end

        //停车位start
        $part_count = $database_house_village_user_bind->where(array('park_flag'=>1,'village_id'=>$this->village_id))->count();
        $this->assign('part_count' , $part_count);
        //停车位end


        $this->display();
    }


    // 导出业主
    public function user_export(){
        //业主-导出 权限
        if (!in_array(98, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $find_type = $_GET['find_type'];
        $find_value = $_GET['find_value'];
        $is_platform = $_GET['is_platform'] + 0;
        if ($find_value) {
            if ($find_type == 1) {
                $where['usernum'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 2) {
                $where['name'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 3) {
                $where['phone'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 4) {
                $where['address'] = array('like', '%' . $find_value . '%');
            }
        }

        if($is_platform == 1){
            $where['uid'] = array('neq',0);
        }elseif ($is_platform == 2) {
            $where['uid'] = array('eq',0);
        }

        $property_endtime_start = $_GET['property_endtime_start'];
        $property_endtime_end = $_GET['property_endtime_end'];
        
        if($property_endtime_start && $property_endtime_end){
            $start_time = strtotime($property_endtime_start);
            $end_time = strtotime($property_endtime_end.'23:59:59');
            $where['property_endtime'] = array('between',array($start_time,$end_time));
        }else if($property_endtime_start){
            $property_endtime_start = strtotime($property_endtime_start);
            $where['property_endtime'] = array('egt',$property_endtime_start);
        }else if($property_endtime_end){
            $property_endtime_end = strtotime($property_endtime_end.'23:59:59');
            $where['property_endtime'] = array('lt',$property_endtime_end);
        }


        $village_id = $this->village_id;
        $user_list = D('House_village_user_bind')->get_limit_list_page($village_id, 99999999, $where);
        $user_list = $user_list['user_list'];

        if(count($user_list) <= 0 ){
            $this->error('无数据导出！');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

        $title = $this->village['village_name'] . '社区-业主列表';

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $length = ceil(count($user_list)/1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('共' . count($user_list) . '个用户');
            $objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '业主编号');
            $objActSheet->setCellValue('B1', '姓名');
            $objActSheet->setCellValue('C1', '手机号');
            $objActSheet->setCellValue('D1', '住宅类型');
            $objActSheet->setCellValue('E1', '住址');
            $objActSheet->setCellValue('F1', '待缴费用');
            $objActSheet->setCellValue('G1', '停车位');
            $objActSheet->setCellValue('H1', '房子大小');

            if (!empty($user_list)) {
                $index = 2;

                $cell_list = range('A','H');
                foreach ($cell_list as $cell) {
                    $objActSheet->getColumnDimension($cell)->setWidth(40);
                }

                foreach ($user_list as $value) {
                    $objActSheet->setCellValueExplicit('A' . $index, $value['usernum']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['phone']);

                    if($value['floor_type_name']){
                        $objActSheet->setCellValueExplicit('D' . $index, $value['floor_type_name']);
                    }else{
                        $objActSheet->setCellValueExplicit('D' . $index, '暂无');
                    }

                    $objActSheet->setCellValueExplicit('E' . $index, $value['address']);

                    $village_price = "";
                    if($water_price = floatval($value['water_price'])){
                        $village_price .= "水费：" . $water_price . chr(10);
                    }

                    if($electric_price = floatval($value['electric_price'])){
                        $village_price .= "电费：" . $electric_price . chr(10);
                    }

                    if($gas_price = floatval($value['gas_price'])){
                        $village_price .= "燃气费：" . $gas_price . chr(10);
                    }

                    if($park_price = floatval($value['park_price'])){
                        $village_price .= "停车费：" . $park_price . chr(10);
                    }

                    $objActSheet->setCellValueExplicit('F' . $index, $village_price);

                    if($value['park_flag'] > 0){
                        $objActSheet->setCellValueExplicit('G' . $index, '有');
                    }else{
                        $objActSheet->setCellValueExplicit('G' . $index, '无');
                    }

                    $objActSheet->setCellValueExplicit('H' . $index, $value['housesize']);
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
	
	#申请解绑审核 - wangdong
	public function audit_unbind(){
        //申请解绑列表-查看 权限
        if (!in_array(107, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$database_house_village_user_unbind = D('House_village_user_unbind');	

		$database_house_village_floor = D('House_village_floor');

		$database_house_village_user_vacancy = D('House_village_user_vacancy');

		$where['village_id'] = $this->village_id;
        $find_type = $_GET['find_type'];
        $find_value = $_GET['find_value'];
        $find_status = $_GET['status'];
        if ($find_value) {
            if ($find_type == 2) {
                $where['name'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 3) {
                $where['phone'] = array('like', '%' . $find_value . '%');
            }
        }
        if($find_status){
            $where['status'] = $find_status;   
        }

        $begin_time = $_GET['begin_time'];
        $end_time = $_GET['end_time'];
        if ($begin_time && !$end_time) {
            $where['addtime'] = array('gt' , strtotime($begin_time));
        }
        if (!$begin_time && $end_time) {
            $where['addtime'] = array('lt' , strtotime(date('Y-m-d 23:59:59',strtotime($end_time))));
        }
        if ($begin_time && $end_time) {
            $where['addtime'] = array('between' , array(strtotime($begin_time),strtotime(date('Y-m-d 23:59:59',strtotime($end_time)))));
        }

        $lists = $database_house_village_user_unbind->get_limit_list_page($this->village_id, 20, $where);

        // $lists = $database_house_village_user_unbind->where($condition)->order('itemid DESC')->select();


		// foreach($lists as $k=>&$v){

		// 	$floor_info = $database_house_village_floor->where(array('floor_id'=>$v['floor_id']))->find();

		// 	$room_info = $database_house_village_user_vacancy->where(array('pigcms_id'=>$v['room_id']))->find();

		// 	$v['address'] = $floor_info['floor_layer']." - ".$floor_info['floor_name']." - ".$room_info['layer']."#".$room_info['room'];

		// }

		$this->assign("lists" , $lists);

		$this->display();
		
	}
	
	#修改/审核用户申请解绑信息 - wangdong
	
	public function audit_unbind_edit(){
		
		$database_house_village_user_unbind = D('House_village_user_unbind');	
			
		$database_house_village_floor = D('House_village_floor');
		
		$database_house_village_user_bind = D('House_village_user_bind');

		$database_house_village_user_vacancy = D('House_village_user_vacancy');
		
		if(IS_POST){
            //申请解绑列表-编辑 权限
            if (!in_array(108, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
			
			$itemid = $_POST['itemid'] + 0;
			
			$status = $_POST['status'] + 0;
			
			if(!$itemid)  $this->error('传递参数错误！~~~');
			
			$where['itemid'] = $itemid;
			
			$data['status']   = $status;	
			$data['edittime'] = time();
			$save_id = $database_house_village_user_unbind -> where($where) -> data($data)->save();
			
			#审核不通过/审核中 不删除绑定信息
			if($status==1 || $status==2){
				
				if($save_id) $this->success('操作成功！',U('User/audit_unbind')); else $this->error('操作失败！');
			
			# 审核通过 删除绑定（要判断是否是管理员 如果是 那么删除房间绑定信息和管理员绑定信息 以及 该房间绑定的亲属/租客）	
			}elseif($status==3){
		
				if($save_id){
				
					$info_where['itemid']     = $itemid;
					$info_where['village_id'] = $this->village_id;
					$info = $database_house_village_user_unbind->where($info_where)->find();
					#如果是管理员/替换管理员 先清空房间绑定信息 vacancy 在删除绑定信息/亲属/租客 bind 
					if($info['type']==0 || $info['type']==3){
						
						$room['status'] = 1;
						$room['uid'] = $room['type'] = $room['park_flag']= 0;
						$room['name'] = $room['phone'] = $room['memo'] = "";
						$room['housesize'] = 0.00;
						
						$room_where['pigcms_id'] = $info['room_id'];
						$room_where['village_id'] = $info['village_id'];
						$reset_id = $database_house_village_user_vacancy->where($room_where)->data($room)->save();
						
						#房间清除完成 删除绑定信息
						if($reset_id){
							
							#先删除房主信息
							
							$bind_info['uid']        = $info['uid'];
							$bind_info['name']       = $info['name'];
							$bind_info['phone']      = $info['phone'];
							$bind_info['floor_id']   = $info['floor_id'];
							$bind_info['vacancy_id'] = $info['room_id'];
							$bind_info['village_id'] = $info['village_id'];
							$bind_info['village_id'] = $info['village_id'];
							$bind_info['type']       = $info['type'];
							$bind_info['pigcms_id']  = $info['bind_id'];
							$del_0_id = $database_house_village_user_bind->where($bind_info)->delete();
						
							#再删除亲属/租客信息
							if($del_0_id){
								$bind_info_1['parent_id'] = $info['bind_id'];
								$bind_info_1['type']      = array('in' , '1,2');	
								$del_1_id = $database_house_village_user_bind->where($bind_info_1)->delete(); 
							}
							$this->success('操作成功！',U('User/audit_unbind'));
						}else{
							
							$this->error('操作失败！');
						}
						
					#如果是亲属/租客	删除绑定信息 bind
					}elseif($info['type']==1 || $info['type']==2){
						
						$bind_info['uid']        = $info['uid'];
						$bind_info['name']       = $info['name'];
						$bind_info['phone']      = $info['phone'];
						$bind_info['floor_id']   = $info['floor_id'];
						$bind_info['vacancy_id'] = $info['room_id'];
						$bind_info['village_id'] = $info['village_id'];
						$bind_info['village_id'] = $info['village_id'];
						$bind_info['type']       = $info['type'];
						$bind_info['pigcms_id']  = $info['bind_id'];
						$del_0_id = $database_house_village_user_bind->where($bind_info)->delete();
						if($del_0_id) $this->success('操作成功！',U('User/audit_unbind')); else $this->error('操作失败！');
					}
				
				}else{
					$this->error('操作失败！');
				}
				
			}
			
		}else{
            //申请解绑列表-查看 权限
            if (!in_array(107, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

			$itemid = $_GET['itemid']+0;
			
			if(!$itemid)  $this->error('传递参数错误！~~~');

			$condition['itemid'] = $itemid;
			
			$edit = $database_house_village_user_unbind->where($condition)->find();
			
			$floor_info = $database_house_village_floor->where(array('floor_id'=>$edit['floor_id']))->find();
	
			$room_info = $database_house_village_user_vacancy->where(array('pigcms_id'=>$edit['room_id']))->find();
	
			$edit['address'] = $floor_info['floor_layer']." - ".$floor_info['floor_name']." - ".$room_info['layer']."#".$room_info['room'];
			
			$this->assign('edit' , $edit);
		
			$this->display();
		}
		
	}
	
	#删除 解绑信息 - wangdong
	public function audit_unbind_del(){
        //申请解绑列表-删除 权限
        if (!in_array(109, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
		
		$itemid = $_GET['itemid'] + 0;
		
		$village_id = $this->village_id;
		
		if(!$itemid || !$village_id)  $this->error('传递参数错误！~~~');
		
		$condition['itemid']     = $itemid;
		$condition['village_id'] = $village_id;
		 
		$database_house_village_user_unbind = D('House_village_user_unbind');
		$delete_id = $database_house_village_user_unbind->where($condition)->delete();
		
		if($delete_id) $this->success('删除成功！',U('User/audit_unbind')); else $this->error('删除失败！');
		
			
	}
	
	#检测是否存在用户
	public function ajax_empty_user_info(){
		
		if(IS_AJAX){
			
			$phone = trim($_POST['phone']);
			if(!$phone) $this->error('参数传递错误！~~~~');
			
			$batabase_user = D('User');
			$info = $batabase_user->where(array('phone'=>$phone,'status'=>1))->count();
			if($info>0){
				exit(json_encode(array('status'=>0,'msg'=>'')));
			}else{
				exit(json_encode(array('status'=>1,'msg'=>'此手机号暂时不是平台用户')));
			}
		}
			
	}
	
	#批量删除业主
	public function ajaxDelete(){
		if(IS_AJAX){
            //业主-删除 权限
            if (!in_array(269, $this->house_session['menus'])) {
                exit(json_encode(array('status'=>0,'msg'=>"对不起，您没有权限执行此操作")));  
            }

			$database_house_village_user_bind = D('House_village_user_bind');
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			
			$village_id = $_POST['village_id']+0;
			$arr_pigcms_id = $_POST['arr_pigcms_id'];
			if(($this->village_id != $village_id) || empty($arr_pigcms_id)){
				exit(json_encode(array('status'=>1,'msg'=>'参数错误')));	
			}
			$arr_pigcms_ids = explode(",",$arr_pigcms_id);
			for($i=0;$i<count($arr_pigcms_ids);$i++){
				//先删除亲属/租客 然后解绑房间  最后删除绑定数据
				$bind_info = $database_house_village_user_bind->where(array('pigcms_id'=>$arr_pigcms_ids[$i],'village_id'=>$village_id))->find();
				if($bind_info){
					//删除亲属/ 租客
					$where = array();
					$where['parent_id'] = $bind_info['pigcms_id'];
					$where['type']      = array('in','1,2');
					$where['village_id']=$village_id;
					
					$database_house_village_user_bind->where($where)->delete();
					
					//清空房间
					$data = array();
					$data['status'] = 1;
					$data['uid'] = 0;
					$data['name'] = "";
					$data['phone'] = "";
					$data['type'] = 0;
					$data['mome'] = "";
					$data['is_del'] = 0;
					$data['del_time'] = 0;
					$data['park_flag'] = 0;
					$database_house_village_user_vacancy->where(array('village_id'=>$village_id,'phone'=>$bind_info['phone']))->data($data)->save();
					//做到清空房间了
					$database_house_village_user_bind->where(array('village_id'=>$village_id,'pigcms_id'=>$arr_pigcms_ids[$i]))->delete();	
				}
			}
			exit(json_encode(array('status'=>1,'msg'=>"业主删除完成")));	
			
			
		}
	}
	
}