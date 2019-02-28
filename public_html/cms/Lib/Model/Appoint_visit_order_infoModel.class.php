<?php
class Appoint_visit_order_infoModel extends Model{
    public function order_appoint_price_sum($where){
	if(!$where){
	    return false;
	}
	
	$order_id_arr = $this->where($where)->getField('id,appoint_order_id');
	if(!$order_id_arr){
	    return false;
	}
	
	$database_appoint_order = D('Appoint_order');
	$database_appoint_product = D('Appoint_product');
	$_Map['order_id'] = array('in',$order_id_arr);
	$_Map['service_status'] = array('neq',0);
	$product_id_arr = $database_appoint_order->where($_Map)->getField('order_id,product_id');
	
	$_where['id'] = array('in',$product_id_arr);
	$appoint_product_list = $database_appoint_product->where($_where)->select();
	
	$field = array(C('DB_PREFIX').'appoint_order.order_id',C('DB_PREFIX').'appoint_product.price');
	$price_avg_num = $database_appoint_order->join('__APPOINT_PRODUCT__ on __APPOINT_ORDER__.product_id = __APPOINT_PRODUCT__.id')->where($_where)->field($field)->avg('price');
	return $price_avg_num;
    }
    
    public function appoint_visit_order_detail($where){
        if(!$where){
            return false;
        }
        
        $detail = $this->where($where)->find();
        if($detail){
            $database_merchant_workers = D('Merchant_workers');
            $Map['merchant_worker_id'] = $detail['merchant_worker_id'];
            $worker_detail = $database_merchant_workers->appoint_worker_info($Map);

            return array('status'=>1,'detail'=>$worker_detail);
        }else{
            return array('status'=>0,'detail'=>$worker_detail);
        }
    }
}
?>