<?php
/*
 * 数据分析
 *
 * @  Writers    jun
 * @  BuildTime  2015/12/30 8:00
 * 
 */
class AnalysisAction extends BaseAction {

    //交易汇总
    public function trade(){
       // $_POST['type'] = 'shop';
        if(empty($_GET['type'])){
            $_GET['type'] = 'shop';
        }
        if(empty($_GET['platform'])){
            $_GET['platform'] = 0;
        }
        $_GET['mer_id'] = $this->merchant_session['mer_id'];
        $pay_method = D('Config')->get_pay_method();
        $this->assign('type_name',$this->get_alias_c_name());
        $this->assign('pay_method',$pay_method);
        $res = D('Order')->sell_order_date(0,$_GET);
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
        $store_list = M('Merchant_store')->where(array('mer_id'=>$this->merchant_session['mer_id']))->select();
        $param = $_GET;
        $param['mer_id'] = $this->merchant_session['mer_id'];
        if($_GET['store_list']==-1 &&  $store_list){
            $param['store_list'] = array();
            foreach ($store_list as $v) {
                $tmp[] = $v['store_id'];
            }
            $param['store_list'] = implode(',',$tmp);
        }

        $res = D('Order')->goods_date(0,$param);

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

    public  function store_list(){
        $where['mer_id'] = $this->merchant_session['mer_id'];
        $_POST['name'] && $where['name'] = array('like', "%{$_POST['name']}%");

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

    public  function staff_list(){
        $where['token'] = $this->merchant_session['mer_id'];
        if($_POST['name']){
            $where['_string'] = "name like '%{$_POST['name']}%' OR username like '%{$_POST['name']}%'";
        }

         $_GET['store_list'] && $where['store_id'] = array('in',$_GET['store_list']);
        $count = M('Merchant_store_staff')->where($where)->count();
        $Page = new Page($count, 5);
        $staff_list = M('Merchant_store_staff')->where($where)->field('id,name,store_id,username')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $get_list = explode(',', $_GET['staff_list']);

        if ($get_list){
            foreach ($staff_list as &$item) {
                if(in_array($item['id'],$get_list)){
                    $item['select'] =1;
                }
            }
        }
        $this->assign('page', $Page->show());
        $this->assign('staff_list',$staff_list);
        $this->display();
    }

    public  function type_list(){
        if(empty($_GET['store_list'])) {
            $condition['mer_id'] = $this->merchant_session['mer_id'];
            $store_list = M('Merchant_store')->where($condition)->select();
            foreach ($store_list as $v) {
                $tmp[] = $v['store_id'];
            }

            if(empty($tmp)){
                $this->error('店铺不存在');
            }
        }else{
            $tmp[] = explode(',',$_GET['store_list']);
        }


        $count = M('Merchant_store_staff')->where(array('store_id'=>array('in',$tmp)))->count();
        $Page = new Page($count, 5);
        if($_GET['type']=='shop'){
            $sort_list = M('Shop_goods_sort')->where(array('store_id'=>array('in',$tmp)))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }else{
            $sort_list = M('Foodshop_goods_sort')->where(array('store_id'=>array('in',$tmp)))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }


        $this->assign('page', $Page->show());
        $this->assign('sort_list',$sort_list);
        $this->display();
    }

    public function export(){
        $param = $_POST;
        $param['order_type'] = $_POST['type'];
        $param['type'] = 'trade';
        $param['rand_number'] = $_SERVER['REQUEST_TIME'];
        //$param['appoint_id'] = 'appoint_id';
        $param['merchant_session']['mer_id'] = $this->merchant_session['mer_id'];
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
        //$param['appoint_id'] = 'appoint_id';
        $param['merchant_session']['mer_id'] = $this->merchant_session['mer_id'];
        
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
    }


}