<?php

class Store_orderAction  extends  BaseAction{
    public function index(){
        $store_order = M('Store_order');
        import('@.ORG.merchant_page');
        $where = array('paid' => 1, 'from_plat' => 1);
        $where['s.mer_id']  =$this->merchant_session['mer_id'];

        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'order_id') {
                $where['order_id'] = $_GET['keyword'];
            }else if ($_GET['searchtype'] == 'orderid') {
              //  $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
                $where['order_id'] = $tmp_result['order_id'];
            } elseif ($_GET['searchtype'] == 'name') {
                $where['u.nickname'] =array('like',"%{$_GET['keyword']}%");
            } elseif ($_GET['searchtype'] == 'phone') {
                $where['u.phone']=$_GET['keyword'];
            } elseif ($_GET['searchtype'] == 'third_id') {
                $where['s.third_id']=$_GET['keyword'];
            }
        }

		if ($_GET['begin_time'] && $_GET['end_time']) {
            $where['s.pay_time'] = array(array('gt', strtotime($_GET['begin_time'])), array('lt', strtotime($_GET['end_time'] . '23:59:59')));
        }
        $count =  M('Store_order')->join("AS s LEFT JOIN ".C('DB_PREFIX')."merchant AS m ON s.mer_id=m.mer_id LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS ms ON s.store_id=ms.store_id LEFT JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid")
            ->where($where)->count();
			fdump($count ,'sssssss');
        $p = new Page($count, 20);
        unset($where['mer_id']);
        
        $where['s.mer_id']  =$this->merchant_session['mer_id'];
        
        $order_list = M('Store_order')->join("AS s LEFT JOIN ".C('DB_PREFIX')."merchant AS m ON s.mer_id=m.mer_id LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS ms ON s.store_id=ms.store_id LEFT JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid")
            ->field(' s.*, u.nickname, u.phone, m.name AS merchant_name, ms.name AS store_name')->where($where)->order('s.pay_time desc')->limit($p->firstRow,$p->listRows)->select();
//        dump($order_list);
        foreach ($order_list as &$l) {
            $l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
        }
        $pagebar = $p->show();

        $pay_method = D('Config')->get_pay_method('','',1);
        $this->assign('pay_method',$pay_method);

        $this->assign(array('order_list' => $order_list, 'pagebar' => $pagebar,'pay_method'=>$pay_method));
        $this->display();
    }

    public function export(){
        $param = $_POST;
        $param['type'] = 'store';
        $param['rand_number'] = time();
        $param['merchant_session']['mer_id'] = $this->merchant_session['mer_id'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
        die;
    }
}