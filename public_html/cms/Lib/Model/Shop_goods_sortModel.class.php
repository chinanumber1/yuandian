<?php
class Shop_goods_sortModel extends Model
{
	public function get_sorts($store_id)
	{
		$store_shop = D('Merchant_store_shop')->where(array('store_id' => $store_id))->find();
		$store_discount = isset($store_shop['store_discount']) ? intval($store_shop['store_discount']) : 0;
		$sorts = $this->where(array('store_id' => $store_id))->select();
		$sort_discount = null;
		foreach ($sorts as $sort) {
			$sort_discount[$sort['sort_id']] = $sort['sort_discount'] ? $sort['sort_discount'] : $store_discount;
		}
		if ($sort_discount) {
			$sort_discount['discount_type'] = isset($store_shop['discount_type']) ? intval($store_shop['discount_type']) : 0;//0:折中折，1：以最优的优惠形式
		}
		return $sort_discount;
	}
	
	public function get_sorts_discount($store_id)
	{
		$store_shop = D('Merchant_store_shop')->where(array('store_id' => $store_id))->find();
		$store_discount = isset($store_shop['store_discount']) ? intval($store_shop['store_discount']) : 0;
		$sorts = $this->where(array('store_id' => $store_id))->select();
		$sort_discount = null;
		foreach ($sorts as $sort) {
			$sort_discount[$sort['sort_id']] = array('discount' => $sort['sort_discount'] ? $sort['sort_discount'] : $store_discount, 'discount_type' => $sort['sort_discount'] ? 1 : 0);
		}
		if ($sort_discount) {
			$sort_discount['discount_type'] = isset($store_shop['discount_type']) ? intval($store_shop['discount_type']) : 0;//0:折中折，1：以最优的优惠形式
		}
		return $sort_discount;
	}
	
	/**
	 * 获取一个店铺下的所有商品分类的树
	 * @param int $store_id
	 * @param string $is_hide
	 * @return multitype:Ambigous <Ambigous <unknown, string>>
	 */
    public function lists($store_id, $is_hide = true)
    {
        $items = $this->field(true)->where(array('store_id' => $store_id))->order('`sort` DESC,`sort_id` ASC')->select();
        $tmpMap = array();
        foreach ($items as $item) {
            $item['cat_id'] = $item['sort_id'];
            $item['cat_name'] = $item['sort_name'];
            if (!empty($item['week'])) {
                $week_arr = explode(',', $item['week']);
                $week_str = '';
                foreach ($week_arr as $k => $v) {
                    $week_str .= $this->getWeek($v) . ' ';
                }
                $item['week_str'] = $week_str;
            }
            $tmpMap[$item['sort_id']] = $item;
        }
        $list = array();
        foreach ($items as $item) {
            if (isset($tmpMap[$item['fid']])) {
                $tmpMap[$item['fid']]['son_list'][$item['sort_id']] = &$tmpMap[$item['sort_id']];
            } else {
                $list[$item['sort_id']] = &$tmpMap[$item['sort_id']];
            }
        }
        unset($tmpMap);
        $today = date('w');
        if ($is_hide) {
            foreach ($list as $key => $row) {
                if (!empty($row['is_weekshow'])) {
                    $week_arr = explode(',', $row['week']);
                    if (!in_array($today, $week_arr)) {
                        unset($list[$key]);
                    }
                }
            }
        }
        return $list;
    }
    
    public function getAllChilds($store_id)
    {
        $items = $this->field(true)->where(array('store_id' => $store_id))->order('`sort` DESC,`sort_id` ASC')->select();
        $s_list = array();
        $today = date('w');
        foreach ($items as $value) {
            if (!empty($value['is_weekshow'])) {
                $week_arr = explode(',', $value['week']);
                if (!in_array($today, $week_arr)) {
                    continue;
                }
            }
            $value['sort_discount'] = $value['sort_discount'] ? ($value['sort_discount'] / 10) : 0;
            $s_list[$value['sort_id']] = $value;
        }
        
        foreach ($s_list as $row) {
            unset($s_list[$row['fid']]);
        }
        return $s_list;
    }
    public function getFirst($sortId, $store_id)
    {
        if ($item = $this->field(true)->where(array('store_id' => $store_id, 'fid' => $sortId))->order('sort DESC')->find()) {
            return $this->getFirst($item['sort_id'], $store_id);
        } else {
            return array($sortId);
        }
    }
    /**
     * 根据指定的ID获取相应的父分类id
     */
    public function getIds($sortId, $store_id)
    {
        static $items;
        if (empty($items)) {
            $items = $this->field(true)->where(array('store_id' => $store_id))->order('sort DESC')->select();
        }
        $idsArray = array();
        foreach ($items as $item) {
            $idsArray[$item['sort_id']] = $item['fid'];
        }
        return array_reverse($this->ids($idsArray, $sortId));//[父ID，子ID, 孙ID, ..., $sortId（指定的ID）]
    }
    private function ids($idsArray, $sortId, $ids = array())
    {
        $ids[] = $sortId;
        if (isset($idsArray[$sortId]) && $idsArray[$sortId]) {
            return $this->ids($idsArray, $idsArray[$sortId], $ids);
        } else {
            return $ids;
        }
    }
    
    /**
     * 根据指定的分类ID获取这个分类下的所有子分类ID（这个子分类下面直接包含商品）
     * @param int $sortId
     * @param int $store_id
     * @return array
     */
    public function getAllSonIds($sortId, $store_id, $isShow = false)
    {
        $items = $this->field(true)->where(array('store_id' => $store_id))->order('sort DESC')->select();
        $tmpMap = array();
        $idsArray = array();
        $today = date('w');
        foreach ($items as $item) {
            if (!empty($item['is_weekshow']) && !$isShow) {
                $week_arr = explode(',', $item['week']);
                if (!in_array($today, $week_arr)) {
                    continue;
                }
            }
            $idsArray[$item['sort_id']] = $item['fid'];
            $item['cat_id'] = $item['sort_id'];
            $item['cat_name'] = $item['sort_name'];
            $tmpMap[$item['sort_id']] = $item;
        }
        
        $list = array();
        foreach ($items as $item) {
            if (!empty($item['is_weekshow']) && !$isShow) {
                $week_arr = explode(',', $item['week']);
                if (!in_array($today, $week_arr)) {
                    continue;
                }
            }
            if (isset($tmpMap[$item['fid']])) {
                $tmpMap[$item['fid']]['son_list'][$item['sort_id']] = &$tmpMap[$item['sort_id']];
            } else {
                $list[$item['sort_id']] = &$tmpMap[$item['sort_id']];
            }
        }
        
        $allIds = array_reverse($this->ids($idsArray, $sortId));
        
        array_pop($allIds);
        foreach ($allIds as $id) {
            if (isset($list[$id])) {
                if (isset($list[$id]['son_list'])) {
                    $list = $list[$id]['son_list'];
                }
            }
        }
        
        if (isset($list[$sortId]['son_list'])) {
            $this->sonIds($list[$sortId]['son_list'], $ids);
        } else {
            $ids[] = $sortId;
        }
        return $ids;
    }
    
    private function sonIds($list, &$ids)
    {
        foreach ($list as $row) {
            if (isset($row['son_list']) && $row['son_list']) {
                $this->sonIds($row['son_list'], $ids);
            } else {
                $ids[] = $row['sort_id'];
            }
        }
    }
    protected function getWeek($num)
    {
        switch($num){
            case 1:
                return '星期一';
            case 2:
                return '星期二';
            case 3:
                return '星期三';
            case 4:
                return '星期四';
            case 5:
                return '星期五';
            case 6:
                return '星期六';
            case 0:
                return '星期日';
            default:
                return '';
        }
    }
    
    public function checkShopDiscount($store_id)
    {
        $sorts = $this->where(array('store_id' => $store_id, 'sort_discount' => array('gt', 0)))->limit(1)->select();
        if ($sorts) {
            return true;
        } else {
            return false;
        }
    }
}
?>