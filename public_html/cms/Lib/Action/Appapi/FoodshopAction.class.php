<?php

//快店列表
class FoodshopAction extends BaseAction{
	public function index(){
		$this->header_json();
		$page	=	$_POST['page']?$_POST['page']-1:0;
		$page_count	=	10;
		$user_long_lat['long'] =$_POST['long'];
		$user_long_lat['lat']  =$_POST['lat'];
		if(!$user_long_lat['long'] && !$user_long_lat['lat']){
			$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		}
		if($_POST['city_id']>0) C('config.now_city',$_POST['city_id']);
		$circle_id = 0;
		$area_url = I('area_url','');
		$sort_id =   I('sort_id','juli');
		$cat_url =   I('cat_url','all');
		if (!empty($area_url)) {
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->returnCode('20045008');
			}

			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->returnCode('20045008');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$area_id = $now_area['area_id'];
		}
		//判断排序信息

		$cat_id = 0;
		if($cat_url != 'all'){
			$now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);
			if (empty($now_category)) {
				$this->returnCode('20045009');
			}
			if (!empty($now_category['cat_fid'])) {
				$f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
				$cat_fid = $now_category['cat_fid'];
				$cat_id = $now_category['cat_id'];
			} else {
				$cat_id = 0;
				$cat_fid = $now_category['cat_id'];
			}
		}
		$params['area_id'] = $area_id;
		$params['circle_id'] = $circle_id;
		$params['sort'] = $sort_id;
		$params['lat'] = $user_long_lat['lat'];
		$params['long'] = $user_long_lat['long'];
		$params['cat_fid'] = $cat_fid;
		$params['cat_id'] = $cat_id;
		$params['queue'] = -1;
		$params['page'] = $page;
		$return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($params, 1);
		$page_all = $return['totalPage'];
		$n=1;
		if(!empty($return)) {
			foreach ($return['store_list'] as $v) {
				if ($n > $page_count)
					break;
				$n++;
				if($v['discount_txt']['discount_type']==1){
					$v['discount_txt']  = $v['discount_txt']['discount_percent'].'折';
				}else if($v['discount_txt']['discount_type']==2) {
					$v['discount_txt']  = '每满'.$v['discount_txt']['condition_price'].'减'.$v['discount_txt']['minus_price'].'元';
				}else{
					$v['discount_txt']  = '';
				}
				$tmp_store_list[] = $v;
			}
			$store_lsit = $tmp_store_list;
		}

		$new_group_list =$store_lsit;


		if(!empty($new_group_list)){
			$this->returnCode(0,array('content'=>$new_group_list,'page_all'=>$page_all));exit;
		}else{
			$this->returnCode(0,array('content'=>array(),'page_all'=>$page_all));exit;
		}
	}

	public function header_json(){
		header('Content-type: application/json');
	}
    
    public function home()
    {
        $return = array();
        
        $adver = D('Adver')->get_adver_by_key('wap_foodshop_index_top', 5);
        $return['adver'] = empty($adver) ? array() : $adver;
        
        $sliber = D('Slider')->get_slider_by_key('wap_foodshop_slider', 8);
        $return['sliber'] = empty($sliber) ? array() : $sliber;
        
        
        //判断地区信息
        $area_url = !empty($_POST['area_url']) ? $_POST['area_url'] : '';
        //判断排序信息
        $sort = !empty($_POST['sort']) ? htmlspecialchars(trim($_POST['sort'])) : 'juli';
        
        $queue = isset($_POST['queue']) ? intval($_POST['queue']) : -1;
        
        $cat_url = isset($_POST['cat_url']) ? htmlspecialchars(trim(($_POST['cat_url']))) : 'all';
        

            $sort_array = array(
                array('sort_id'=>'juli', 'sort_value'=>'离我最近'),
                array('sort_id'=>'rating', 'sort_value'=>'评价最高'),
                array('sort_id'=>'defaults', 'sort_value'=>'智能排序'),
            );

        $return['sort_list'] = $sort_array;

        $return['queue_list'] = array('不限', '无排号', '可排号');

        $all_area_list = D('Area')->get_all_area_list();
        
        $return['area_list'] = $this->removeNumberKey($all_area_list, 'area_list');

        
        $all_category_list = D('Meal_store_category')->get_all_category();
        $return['category_list'] = $this->removeNumberKey($all_category_list);
        $this->returnCode(0, $return);
    }
    private function removeNumberKey($list, $index = 'category_list')
    {
        $newList = array();
        foreach ($list as $row) {
            if ($row[$index]) {
                $row[$index] = $this->removeNumberKey($row[$index]);
            }
            $newList[] = $row;
        }
        return $newList;
    }
    public function ajaxList()
    {
        $this->header_json();
        
        $area_url = !empty($_POST['area_url']) ? $_POST['area_url'] : '';
        $sort = !empty($_POST['sort']) ? htmlspecialchars(trim($_POST['sort'])) : 'juli';
        $queue = isset($_POST['queue']) ? intval($_POST['queue']) : -1;
        $cat_url = isset($_POST['cat_url']) ? htmlspecialchars(trim(($_POST['cat_url']))) : 'all';
        $keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
        
        $circle_id = 0;
        $area_id = 0;
        if (!empty($area_url)) {
            $tmp_area = D('Area')->get_area_by_areaUrl($area_url);
            if(empty($tmp_area)){
                $this->returnCode(1, null, '当前区域不存在！');
            }
            
            if ($tmp_area['area_type'] == 3) {
                $now_area = $tmp_area;
            } else {
                $now_circle = $tmp_area;
                $now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
                if (empty($tmp_area)) {
                    $this->returnCode(1, null, '当前区域不存在！');
                }
                $circle_url = $now_circle['area_url'];
                $circle_id = $now_circle['area_id'];
                $area_url = $now_area['area_url'];
            }
            $area_id = $now_area['area_id'];
        }
        
        
        $cat_id = 0;
        if($cat_url != 'all'){
            $now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);
            if (empty($now_category)) {
                $this->returnCode(1, null, '此分类不存在！');
            }
            if (!empty($now_category['cat_fid'])) {
                $f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
                $all_category_url = $f_category['cat_url'];
                
                $cat_fid = $now_category['cat_fid'];
                $cat_id = $now_category['cat_id'];
            } else {
                $cat_id = 0;
                $cat_fid = $now_category['cat_id'];
            }
        }
        
        $lat = isset($_POST['lat']) ? $_POST['lat'] : 0;
        $long = isset($_POST['long']) ? $_POST['long'] : 0;

        
        $where = array('area_id' => $area_id, 'circle_id' => $circle_id, 'cat_fid' => $cat_fid, 'cat_id' => $cat_id, 'lat' => $lat, 'long' => $long, 'sort' => $sort, 'queue' => $queue, 'keyword' => $keyword);
        $_GET['page'] = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($where);
        
        if(!empty($return)) {
            foreach ($return['store_list'] as &$v) {
                if ($v['discount_txt']['discount_type'] == 1) {
                    $v['discount_txt'] = $v['discount_txt']['discount_percent'] . '折';
                } else if ($v['discount_txt']['discount_type'] == 2) {
                    $v['discount_txt'] = '每满' . $v['discount_txt']['condition_price'] . '减' . $v['discount_txt']['minus_price'] . '元';
                } else {
                    $v['discount_txt'] = '';
                }
            }
        }
        $this->returnCode(0, $return);
    }
}
?>