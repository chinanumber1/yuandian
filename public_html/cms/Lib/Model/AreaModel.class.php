<?php
class AreaModel extends Model{
    public $nameCache = array();
	/*得到所有区域*/
	public function get_area_list($limit='',$cat_url='', $type = 'category'){
		$condition_area['area_pid'] = C('config.now_city');
		$condition_area['is_open'] = '1';
		$area_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->limit($limit)->select();
		// dump($this);
		if(is_array($area_list)){
			foreach($area_list as $key=>$value){
				$area_list[$key]['url'] = $this->get_area_url($value,$cat_url, $type);
			}
		}
		return $area_list;
	}

	public function get_area_list_hot($limit='',$cat_url='', $type = 'category'){
		//dump( C('config.now_select_city'));die;
		$now_area_select = C('config.now_select_city');
		$area_pids = $this->where(array('area_pid'=>$now_area_select['area_id']))->select();
		foreach ($area_pids as $item) {
			$area_arr[] = $item['area_id'];
		}
		$condition_area['area_pid'] = array('in',$area_arr);
		$condition_area['is_open'] = '1';
		$condition_area['is_hot'] = '1';
		$condition_area['circle_category_id'] = array('gt',0);

		$area_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->limit($limit)->select();
		return $area_list;
	}

	public function get_circle_by_keyword($keyword){
		//dump( C('config.now_select_city'));die;
		$now_area_select = C('config.now_select_city');
		$area_pids = $this->where(array('area_pid'=>$now_area_select['area_id']))->select();
		foreach ($area_pids as $item) {
			$area_arr[] = $item['area_id'];
		}
		$condition_area['area_pid'] = array('in',$area_arr);
		$condition_area['is_open'] = '1';
		$condition_area['is_hot'] = '1';
		$condition_area['circle_category_id'] = array('gt',0);
		$condition_area['area_name'] = array('like','%'.$keyword.'%');
		$area_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();

		return $area_list;
	}

	/*通过父级ID得到子区域*/
	public function get_arealist_by_areaPid($area_pid=0,$is_open=false,$cat_url='', $type = 'category'){
		$cacha_name = 'area_pid_'.$area_pid.'_'.strval($is_open).'_'.$cat_url;
		$area_list = S($cacha_name);
		if(empty($area_list)){
			$condition_area['area_pid'] = $area_pid;
			if($is_open){
				$condition_area['is_open'] = '1';
			}
			$area_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->limit($limit)->select();
			if(is_array($area_list)){
				foreach($area_list as $key=>$value){
					$area_list[$key]['url'] = $this->get_area_url($value,$cat_url, $type);
				}
				S($cacha_name,$area_list,86400);
			}
		}
		return $area_list;
	}
	

	public function get_area_by_areaUrl($area_url,$cat_url='', $type = 'category'){
		$condition_area['area_url'] = $area_url;
		$condition_area['is_open'] = '1';
		//$condition_area['area_pid'] = C('config.now_city');
		$now_area = $this->field(true)->where($condition_area)->find();
		if(!empty($now_area)){
			$now_area['url'] = $this->get_area_url($now_area,$cat_url, $type);
		}
		return $now_area;
	}
	public function get_area_by_areaId($area_id,$is_open=true,$cat_url='', $type = 'category'){
		$condition_area['area_id'] = $area_id;
		if($is_open){
			$condition_area['is_open'] = '1';
		}
		$now_area = $this->field(true)->where($condition_area)->find();
		if(!empty($now_area)){
			$now_area['url'] = $this->get_area_url($now_area,$cat_url, $type);
		}
		return $now_area;
	}
	public function get_circle_list($limit='12',$cat_url='', $type = 'category'){
		$area_list = $this->get_area_list($limit, $cat_url, $type);
		if(empty($area_list)){
			return array();
		}
		$area_pid_arr = array();
		foreach($area_list as $key=>$value){
			array_push($area_pid_arr,$value['area_id']);
		}
		$condition_area['area_pid'] = array('in',implode(',',$area_pid_arr));
		$condition_area['is_open'] = 1;
		$circle_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->limit($limit)->select();
		if(is_array($circle_list)){
			foreach($circle_list as $key=>$value){
				$circle_list[$key]['url'] = $this->get_area_url($value,$cat_url, $type);
			}
		}
		return $circle_list;
	}

	public function get_hot_circle_list($limit='12',$cat_url='', $type = 'category'){
		$area_list = $this->get_area_list($limit, $cat_url, $type);
		if(empty($area_list)){
			return array();
		}
		$area_pid_arr = array();
		foreach($area_list as $key=>$value){
			array_push($area_pid_arr,$value['area_id']);
		}
		$condition_area['area_pid'] = array('in',implode(',',$area_pid_arr));
		$condition_area['is_open'] = 1;
		$condition_area['is_hot'] = 1;
		$circle_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->limit($limit)->select();
		if(is_array($circle_list)){
			foreach($circle_list as $key=>$value){
				$circle_list[$key]['url'] = $this->get_area_url($value,$cat_url, $type);
			}
		}
		return $circle_list;
	}

	/*通过area_id 向上找父集地区*/
	public function get_parents_area_by_areaid($area_id,$return=array()){
		$where['area_id'] = $area_id;
		$area = $this->where($where)->find();
		$return[$area['area_type']] = $area_id;

		if($area['area_pid']==0){
			return $return;
		}else{
			return	$this->get_parents_area_by_areaid($area['area_pid'],$return);
		}
	}


	/* 得到区域的URL */
	protected function get_area_url($area,$cat_url='', $type = 'category'){
		if(in_array($type,array('category','meal'))){
			if(empty($cat_url)){
				return C('config.site_url').'/'.$type.'/all/'.$area['area_url'];
			}else{
				return C('config.site_url').'/'.$type.'/'.$cat_url.'/'.$area['area_url'];
			}
		}else if($type == 'activity'){
			if(empty($cat_url)){
				return C('config.site_url').'/'.$type.'/all/'.$area['area_url'];
			}else{
				return C('config.site_url').'/'.$type.'/'.$cat_url.'/'.$area['area_url'];
			}
		}else if($type == 'appoint'){
			if(empty($cat_url)){
				return C('config.site_url').'/'.$type.'/category/all/'.$area['area_url'];
			}else{
				return C('config.site_url').'/'.$type.'/category/'.$cat_url.'/'.$area['area_url'];
			}
		}
	}

	public function get_all_area_list($limit='',$cat_url='', $type = 'category')
	{
		$condition_area['area_pid'] = C('config.now_city');
		$condition_area['is_open'] = '1';
		$area_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		$result = $area_pid = array();

		foreach($area_list as $value){
			$value['url'] = $this->get_area_url($value,$cat_url, $type);
			$area_pid[] = $value['area_id'];
			$value['area_list'] = array();
			$value['area_count'] = 0;
			$result[$value['area_id']] = $value;
		}

		if ($area_pid) {
			unset($condition_area['area_pid']);
			$condition_area['area_pid'] = array('in', $area_pid);
			$area_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
			foreach ($area_list as $row) {
				$row['url'] = $this->get_area_url($row,$cat_url, $type);
				if (isset($result[$row['area_pid']])) {
					$result[$row['area_pid']]['area_list'][] = $row;
					$result[$row['area_pid']]['area_count']++;
				}
			}
		}
		return $result;
	}

	/**
	 * 获取 area_name
	 * @param int $id
	 * @return string $area_name
	 */
	public function name( $id )
	{
	    if (isset($this->nameCache[$id])) {
	        return $this->nameCache[$id];
	    }

	    $where = array('area_id' => intval($id));

	    return $this->nameCache[$id] = $this->where( $where )->getField('area_name');
	}

	# 景区得到所有区域
	public function scenic_get_area_list($where='',$limit=''){
		$area_list = $this->field(true)->where($where)->order('`area_sort` DESC,`area_id` ASC')->limit($limit)->select();
		foreach($area_list as &$v){
			$v['son']	=	$this->scenic_get_arealist_by_areaPid($v['area_id'],1);
		}
		return $area_list;
	}
	# 景区得到所有区域
	public function scenic_get_area_list_pc($where='',$limit=''){
		$area_list = $this->field(true)->where($where)->order('`area_sort` DESC,`area_id` ASC')->limit($limit)->select();
		foreach($area_list as $k=>&$v){
			$v['son']	=	$this->scenic_get_arealist_by_areaPid($v['area_id'],1);
			if(empty($v['son'])){
				unset($area_list[$k]);
			}
		}
		return $area_list;
	}
	# 景区得到所有省市
	public function scenic_get_area_list_pcs($where=''){
		$area_list['province'] = $this->field(true)->where($where)->order('`area_sort` DESC,`area_id` ASC')->select();
		foreach($area_list['province'] as $k=>$v){
			$area_list['city'][$k]	=	$this->scenic_get_arealist_by_areaPid($v['area_id'],1);
		}
		return $area_list;
	}
	# 景区通过父级ID得到子区域
	public function scenic_get_arealist_by_areaPid($area_pid=0,$is_open=false){
		$where['area_pid']	=	$area_pid;
		$cacha_name = 'scenic_area_pid_'.$area_pid.'_'.strval($is_open);
		$area_list = S($cacha_name);
		if(empty($area_list)){
			$condition_area['area_pid'] = $area_pid;
			if($is_open){
				$condition_area['is_open'] = '1';
			}
			$area_list = $this->field(true)->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->limit($limit)->select();
			S($cacha_name,$area_list,86400);
		}
		return $area_list;
	}
	# 景区通过当前城市获取当前省市
	public function scenic_get_area_by_areaId($area_id,$is_open=true,$return=0){
		$condition_area['area_id'] = $area_id;
		if($is_open){
			$condition_area['is_open'] = '1';
		}
		$area	=	$this->field(true)->where($condition_area)->find();
		$now_area	=	$this->field(true)->where(array('area_id'=>$area['area_pid'],'is_open'=>1))->find();
		if($now_area){
			$now_area['city']	=	$this->field(true)->where(array('area_pid'=>$area['area_pid'],'is_open'=>1))->select();
		}else{
			$now_area	=	array();
		}
		if($return == 1){
			return $now_area['city'];
		}
		return $now_area;
	}
	# 景区用城市ID换取对应的名称
	public function scenic_get_one_city($area_id,$is_open=true){
		$condition_area['area_id'] = $area_id;
		if($is_open){
			$condition_area['is_open'] = '1';
		}
		$area	=	$this->field(true)->where($condition_area)->find();
		if($area){
			return $area;
		}else{
			return array();
		}
	}
	# 景区PC得到一级城市
	public function scenic_get_area_all($where=''){
		$area_list = $this->field(true)->where($where)->select();
		if($area_list){
			$arr	=	array(
				'ABCDE'	=>	array(),
				'FGHJ'	=>	array(),
				'KLMNP'	=>	array(),
				'QRSTW'	=>	array(),
				'XYZ'	=>	array(),
			);
			foreach($area_list as &$v){
				$v['first_pinyin']	=	strtoupper($v['first_pinyin']);
				if(strstr("ABCDE",$v['first_pinyin'])){
					$arr['ABCDE'][$v['first_pinyin']][]	=	$v;
				}else if(strstr("FGHJ",$v['first_pinyin'])){
					$arr['FGHJ'][$v['first_pinyin']][]	=	$v;
				}else if(strstr("KLMNP",$v['first_pinyin'])){
					$arr['KLMNP'][$v['first_pinyin']][]	=	$v;
				}else if(strstr("QRSTW",$v['first_pinyin'])){
					$arr['QRSTW'][$v['first_pinyin']][]	=	$v;
				}else if(strstr("XYZ",$v['first_pinyin'])){
					$arr['XYZ'][$v['first_pinyin']][]	=	$v;
				}
			}
		}
		return $arr;
	}
	# 景区PC得到热门城市
	public function scenic_get_hot(){
		$where	=	array(
			'is_hot'	=>	1,
			'is_open'	=>	1,
			'area_type'	=>	2,
		);
		$area_list = $this->field(true)->where($where)->order('`area_sort` DESC,`area_id` ASC')->select();
		return $area_list;
	}

    public function getIndexByAreaID($areaId)
    {
        $now_area = D('Area')->field(true)->where(array('area_id' => $areaId))->find();
        if ($now_area['area_type'] == 3) {
            $area_index = 'area_id';
        } elseif ($now_area['area_type'] == 2) {
            $area_index = 'city_id';
        } elseif ($now_area['area_type'] == 1) {
            $area_index = 'province_id';
        }
        return $area_index;
    }

	public function cityMatching($lat,$lng,$returnPois=false)
	{
		$url = 'http://api.map.baidu.com/geocoder/v2/?output=json&pois=1&ak=4c1bb2055e24296bbaef36574877b4e2&location=' .$lat . ',' . $lng;
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		$result = json_decode($result, true);
		$area['area_name'] = $result['result']['addressComponent']['district'];
		$area['city_name'] = $result['result']['addressComponent']['city'];
		$area['province_name'] = $result['result']['addressComponent']['province'];
		foreach ($area as &$v) {
			$long = strlen($v);
			if ($long >= 7) {
				$v = str_replace('省', '', $v);
				$v = str_replace('市', '', $v);
				$v = str_replace('地区', '', $v);
				$v = str_replace('特别行政区', '', $v);
				$v = str_replace('特別行政區', '', $v);
				$v = str_replace('蒙古自治州', '', $v);
				$v = str_replace('回族自治州', '', $v);
				$v = str_replace('柯尔克孜自治州', '', $v);
				$v = str_replace('哈萨克自治州', '', $v);
				$v = str_replace('土家族苗族自治州', '', $v);
				$v = str_replace('藏族羌族自治州', '', $v);
				$v = str_replace('傣族自治州', '', $v);
				$v = str_replace('布依族苗族自治州', '', $v);
				$v = str_replace('苗族侗族自治州', '', $v);
				$v = str_replace('壮族苗族自治州', '', $v);
				$v = str_replace('澳门', '澳門', $v);
				$v = str_replace('朝鲜族自治州', '', $v);
				$v = str_replace('哈尼族彝族自治州','',$v);
				$v = str_replace('傣族景颇族自治州','',$v);
				$v = str_replace('藏族自治州','',$v);
				$v = str_replace('彝族自治州','',$v);
				$v = str_replace('白族自治州','',$v);
				$v = str_replace('傈僳族自治州','',$v);
			}
		}

		$now_city = M('Area')->where(array('area_name'=>$area['city_name']))->find();
		$now_city['area_info']['city_name'] = $now_city['area_name'];
		$now_city['area_info']['city_id'] = $now_city['area_id'];
		
		$area_info = M('Area')->where(array('area_name'=>array('like','%'.$area['area_name'].'%')))->find();
		$now_city['area_info']['area_id'] = $area_info['area_id'];
		$now_city['area_info']['area_name'] = $area_info['area_name'];
		$now_city['area_info']['area_is_open'] = $area_info['is_open'];
		
		$now_city['area_info']['province_id'] = $now_city['area_pid'];
		$now_city['area_info']['province_name'] = $area['province_name'];
		
		$now_city['pois'] = array();
		if($result['result']['pois']){
			$now_city['address_name'] = $result['result']['pois'][0]['name'];
			$now_city['address_addr'] = $result['result']['pois'][0]['addr'];
			if($returnPois){
				foreach($result['result']['pois'] as $key=>$value){
					if($key != 0){
						$now_city['pois'][] = array(
							'name' 	   		=> $value['name'],
							'address_name'	=> $value['name'],
							'addr' 	   		=> $value['addr'],
							'distance' 		=> $value['distance'],
							'lat' 			=> $value['point']['y'],
							'lng' 			=> $value['point']['x'],
						);
					}
				}
			}
		}else{
			$now_city['address_name'] = $result['result']['addressComponent']['street'];
			$now_city['address_addr'] = $result['result']['addressComponent']['street'];
		}
		
		return $now_city;
	}
}
?>