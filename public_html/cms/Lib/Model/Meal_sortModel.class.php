<?php
class Meal_sortModel extends Model
{
	public function get_sorts($store_id)
	{
		$store_meal = D('Merchant_store_meal')->where(array('store_id' => $store_id))->find();
		$store_discount = isset($store_meal['store_discount']) ? intval($store_meal['store_discount']) : 0;
		$sorts = $this->where(array('store_id' => $store_id))->select();
		$sort_discount = null;
		foreach ($sorts as $sort) {
			$sort_discount[$sort['sort_id']] = $sort['sort_discount'] ? $sort['sort_discount'] : $store_discount;
		}
		if ($sort_discount) {
			$sort_discount['discount_type'] = isset($store_meal['discount_type']) ? intval($store_meal['discount_type']) : 0;//0:折中折，1：以最优的优惠形式
		}
		return $sort_discount;
	}
}
?>