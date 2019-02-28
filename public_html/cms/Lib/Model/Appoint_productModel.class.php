<?php
/**
 * 添加预约，自定义字段
 * pigcms_appoint_product
 */
class Appoint_productModel extends Model{
	/*获取预约下的所有的产品项目*/
	public function get_productlist_by_appointId($appoint_id){
		$database_custom = D('Appoint_product');
		$where['appoint_id'] = $appoint_id;
		$custom_list = $database_custom->field(true)->where($where)->select();
		return $custom_list;
	}

	public function get_product_info($product_id,$fields = true){
		if(!$product_id){
			return false;
		}

		$where['id'] = $product_id;
		$detail = $this->where($where)->field($fields)->find();

		if($detail){
			return array('status'=>1,'detail'=>$detail);
		}else{
			return array('status'=>0,'detail'=>$detail);
		}

	}
	 
}