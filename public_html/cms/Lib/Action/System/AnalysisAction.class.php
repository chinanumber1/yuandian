<?php
/*
 * 数据分析
 *
 * @  Writers    jun
 * @  BuildTime  2015/12/30 8:00
 * 
 */
class AnalysisAction extends BaseAction {
    //获取用户总数统计
    public function echart(){
        $this->assign('star_year',2015);

        $this->display();
    }
    public function userc(){
        $star_year='2015';
        $this->assign('star_year',$star_year);
        $express_list = D('House_village')->get_village_list();
         //array_unshift($express_list,array('village_id'=>0,'village_name'=>'所有社区'));

        $this->assign('village_list',$express_list);
        $this->display();
    }
    public function getuserc(){
        $result = D('Analysis')->get_count_byAreaId($_POST['area_id'],$_POST['type']);
        $result['note']="用户统计是按地区统计的，用户必须要有完整的配送地址信息";
        $this->ajaxReturn($result);
    }
    //粉丝排行统计
    public  function fanc(){
        $result = D('Analysis')->get_fan_count($_POST['area_id'],$_POST['type']);
        $result['note']="粉丝统计是统计关注每个商家的粉丝数量";
        $this->ajaxReturn(array('msg'=>$result['msg'],'area_pname'=>$result['area_pname'],'note'=>$result['note'],'error'=>''));
    }
    //获取商家总数统计
    public function merc(){
        $result = D('Analysis')->get_merchant_count($_POST['area_id'],$_POST['type']);
        $area_pname = empty($result['area_pname'])?"全国商家数量统计":$result['area_pname']."商家数量统计";
        $result['note']="按地区统计商家数量";
        $this->ajaxReturn(array('msg'=>$result['msg'],'area_pname'=>$area_pname,'note'=>$result['note'],'error'=>''));
    }
    //统计团购消费统计
    public function groupc(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 3,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.group_alias_name')."消费统计";
        $result['note']=C('config.group_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }
    //餐饮消费统计
    public function mealc(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 0,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.meal_alias_name')."消费统计";
        $result['note']=C('config.meal_alias_name')."消费统计按地区时间统计已消费的数据";

        $this->ajaxReturn($result);
    }

    //快店消费统计
    public function shopc(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 5,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.shop_alias_name')."消费统计";
        $result['note']=C('config.shop_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }

    //店铺消费统计
     public function storec(){
       $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 1,$_POST['year'],$_POST['month'],$_POST['period']);
       $result['area_pname'].=C('config.store_alias_name')."消费统计";
         $result['note']=C('config.store_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }
    //外卖消费统计
    public function waimaic(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 2,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.waimai_alias_name')."消费统计";
        $result['note']=C('config.waimai_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }
    //预约消费统计
    public function appointc(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 4,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.appoint_alias_name')."消费统计";
        $result['note']=C('config.appoint_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }
    //小区消费
    public function villagec(){
        $result = D('Analysis')->get_village_consumer($_POST['area_id'],$_POST['type'],$_POST['year'],$_POST['month'],$_POST['period']);
        $area_pname2 = empty($result['area_pname'])?"全国":$result['area_pname'];
        $area_pname = empty($result['area_pname'])?"全国小区缴费统计":$result['area_pname']."小区缴费统计";
        $result['note']="社区消费统计按地区时间统计社区缴费的数据";
        $this->ajaxReturn(array('msg'=>$result['msg'],'type_money'=>$result['type_money'],'area_pname'=>$area_pname,'area_pname2'=>$area_pname2,'error'=>''));
    }

    public function villagebasec(){
        $result = D('Analysis')->get_village_base($_POST['type'],$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname2'] = empty($result['area_pname'])?"所有小区":$result['area_pname'];
        $result['area_pname'] = empty($result['area_pname'])?"所有小区":$result['area_pname'];
        $result['note']="小区基础信息统计";
        $result['error']="";
        $this->ajaxReturn($result);
    }
	
//    
    //框架内菜单选项
    public function getmenu(){
        $this->ajaxReturn($this->menu());
    }
    public function menu(){
        $menu = array(
            'getuserc'=>'用户统计',
            'merc'=>'商家统计',
            'fanc'=>'商家粉丝排行',
            'groupc'=>C('config.group_alias_name').'消费统计',
            'mealc'=>C('config.meal_alias_name').'消费统计',
            'shopc'=>C('config.shop_alias_name').'消费统计',
            'storec'=>C('config.store_alias_name').'消费统计',
            'appointc'=>C('config.appoint_alias_name').'消费统计',
        );
        if (empty($this->config['is_cashier'])) {
            unset($menu['storec']);
        }
        if($this->config['house_open']){
            $menu['villagec'] = '小区缴费统计';
            $menu['villagebasec'] = '小区基础信息统计';
        }
        if($this->config['store_open_waimai']){
            $menu['waimaic'] = '外卖消费统计';
        }
        return $menu;
    }

    //交易汇总
    public function trade(){
       // $_POST['type'] = 'shop';
        if(empty($_GET['type'])){
            $_GET['type'] = 'shop';
        }
        $pay_method = D('Config')->get_pay_method();
        $this->assign('type_name',$this->get_alias_c_name());
        $this->assign('pay_method',$pay_method);
        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();

            $this->assign('now_area',$now_area);
        }
        if(!isset($_GET['selectTimeType']) && !isset($_GET['begin_time'])  && !isset($_GET['end_time'])){
            $_GET['selectTimeType'] = 1;
        }
        $res = D('Order')->sell_order_date(1,$_GET);
        $this->assign($res);
        $this->display();
    }

    //菜品分析
    public function goods(){
        if(empty($_GET['type'])){
            $_GET['type'] = 'shop';
        }
        $this->assign('type_name',array(
            'shop'=>$this->config['shop_alias_name'],
            'foodshop'=>$this->config['meal_alias_name'],
        ));

        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();

            $this->assign('now_area',$now_area);
        }
        $res = D('Order')->goods_date(1,$_GET);

        $this->assign($res);
        $this->display();
    }

    public  function get_alias_c_name(){
        $alias_name = array(
            'shop'=>$this->config['shop_alias_name'],
            'group'=>$this->config['group_alias_name'],
            'meal'=>$this->config['meal_alias_name'],
            'appoint'=>$this->config['appoint_alias_name'],
            'store'=>'到店',
        );
        if(!isset($this->config['appoint_alias_name'])){
            unset($alias_name['appoint']);
        }

        return $alias_name;
    }

    public  function mer_list()
    {
        if ($_GET['area'] && $_GET['area']!='undefined') {
            $where['area_id'] = $_GET['area'];
        } else if ($_GET['city'] && $_GET['city']!='undefined') {
            $where['city_id'] = $_GET['city'];
        } else if ($_GET['province'] && $_GET['province']!='undefined') {
            $where['province_id'] = $_GET['province'];
        }

        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if($now_area['area_type']==3){
                $area_index = 'area_id';
            }elseif($now_area['area_type']==2){
                $area_index = 'city_id';
            }elseif($now_area['area_type']==1) {
                $area_index = 'province_id';

            }
            $where[$area_index] = $this->system_session['area_id'];
        }
        $_POST['name'] && $where['name'] = array('like', "%{$_POST['name']}%");
        $count = M('Merchant')->where($where)->count();

        $Page = new Page($count, 5);
        $mer_list = M('Merchant')->where($where)->field('mer_id,name')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $get_list = explode(',', $_GET['mer_list']);

        if ($get_list){
            foreach ($mer_list as &$item) {
                if(in_array($item['mer_id'],$get_list)){
                    $item['select'] =1;
                }
            }
        }
        $this->assign('page', $Page->show());
        $this->assign('mer_list',$mer_list);
        $this->display();
    }

    public  function store_list(){
        if ($_GET['area'] && $_GET['area']!='undefined') {
            $where['area_id'] = $_GET['area'];
        } else if ($_GET['city'] && $_GET['city']!='undefined') {
            $where['city_id'] = $_GET['city'];
        } else if ($_GET['province'] && $_GET['province']!='undefined') {
            $where['province_id'] = $_GET['province'];
        }

        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if($now_area['area_type']==3){
                $area_index = 'area_id';
            }elseif($now_area['area_type']==2){
                $area_index = 'city_id';
            }elseif($now_area['area_type']==1) {
                $area_index = 'province_id';

            }
            $where[$area_index] = $this->system_session['area_id'];
        }
        $_POST['name'] && $where['name'] = array('like', "%{$_POST['name']}%");
        I('mer_list') && $where['mer_id'] = array('in',I('mer_list'));
       // $_GET['mer_id'] && $where['mer_id'] = array('in',$_GET['mer_id']);
        $count = M('Merchant_store')->where($where)->count();
        $Page = new Page($count, 5);
        $store_list = M('Merchant_store')->where($where)->field('store_id,name')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $get_list = explode(',', $_GET['store_list']);

        if ($get_list){
            foreach ($store_list as &$item) {
                if(in_array($item['store_id'],$get_list)){
                    $item['select'] =1;
                }
            }
        }
        $this->assign('page', $Page->show());
        $this->assign('store_list',$store_list);
        $this->display();
    }

    public  function  ajax_get_merchant(){
        $province_id = $_POST['province_id'];
        $city_id = $_POST['city_id'];
        $area_id = $_POST['area_id'];

        $province_id && $where['province_id'] = $province_id;
        $city_id && $where['city_id'] = $city_id;
        $area_id && $where['area_id'] = $area_id;

        $mer_list = M('Merchant')->field('mer_id,name')->where($where)->select();
        $this->ajaxReturn($mer_list);
    }

    public  function  ajax_get_store(){
        $mer_list = $_POST['mer_list'];
        $type = $_POST['type'];
        $where['mer_id']=array('in',$mer_list);
        if($type!='' && $type!='store'){
            $where['have_'.$type]=1;
        }
        $store_list = M('Merchant_store')->field('store_id,name')->where($where)->select();
        $this->ajaxReturn($store_list);
    }

    public function export(){
        $param = $_POST;
        $param['order_type'] = $_POST['type'];
        $param['type'] = 'trade';
        $param['rand_number'] = $_SERVER['REQUEST_TIME'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
    }

    public function goods_export(){
        $param = $_POST;
        $param['order_type'] = $_POST['type'];
        $param['type'] = 'goods';
        $param['rand_number'] = $_SERVER['REQUEST_TIME'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
    }
}