<?php
class Trade_hotel_categoryModel extends Model{
	function get_all_list($mer_id,$has_stock=false,$dep_time='',$end_time=''){
		$tmp_category_list = $this->where(array('mer_id'=>$mer_id,'is_remove'=>'0'))->order('`cat_fid` ASC,`cat_sort` DESC,`cat_id` ASC')->select();

		$category_list = array();
		
		if($has_stock){
			$stock_list = $this->get_category_room_stock($mer_id,$dep_time,$end_time);
		}

		$trade_hotel_image_class = new trade_hotel_image();
		
		foreach($tmp_category_list as $value){
			if($value['cat_fid'] == '0'){
				$value['cat_pic_list'] = $trade_hotel_image_class->get_allImage_by_path($value['cat_pic']);
				
				unset($value['cat_pic'],$value['is_remove'],$value['mer_id'],$value['code'],$value['cat_sort'],$value['enter_time'],$value['has_receipt'],$value['has_refund'],$value['refund_hour']);
				
				$category_list[$value['cat_id']] = $value;
			}else{
				if($has_stock){
					$value['stock_num'] = $stock_list[$value['cat_id']]['room'];
					$value['price_txt'] = $stock_list[$value['cat_id']]['price'];
					$value['discount_price_txt'] = $stock_list[$value['cat_id']]['discount_price']? $stock_list[$value['cat_id']]['discount_price']:0;

				}
				$value['refund_txt'] = $this->get_refund_txt($value['has_refund'],$value['refund_hour'],$value['enter_time']);
				unset($value['is_remove'],$value['cat_pic'],$value['window_info'],$value['floor_info'],$value['room_size'],$value['bed_info'],$value['network_info'],$value['breakfast_info'],$value['code'],$value['cat_sort'],$value['mer_id']);
				$category_list[$value['cat_fid']]['son_list'][] = $value;
			}
		}

		
		if($has_stock){
			foreach($category_list as $key=>$value){
				if(is_array($value['son_list'])){
					foreach($value['son_list'] as $k=>$v){
						if($v['price_txt'] && (empty($value['min_price']) || $v['price_txt'] < $value['min_price'])){
							$category_list[$key]['min_price'] = $value['min_price'] = $v['price_txt'];
						}
						if($v['price_txt'] != $value['min_price']){
							$category_list[$key]['many_min_price'] = true;
						}

						if($v['stock_num'] && $v['price_txt']){
							$category_list[$key]['has_room'] = true;
							// break;
						}elseif(!$v['stock_num'] && $v['price_txt']){
							$category_list[$key]['has_room'] = false;
						}
					}
				}
			}
		}
		return $category_list;
	}
	function get_refund_txt($has_refund,$refund_hour,$enter_time,$dep_time=''){
		if($has_refund == 0){
			$has_refund_txt = '任意退';
		}else if($has_refund == 1){
			$has_refund_txt = '不可取消';
		}else{
			// if($dep_time){
				// if($dep_time == date('Ymd')){
					// if(str_replace('00','',$enter_time) - $refund_hour*60 < 0){
						// $has_refund_txt = '不可取消';
					// }
				// }
			// }else{
				$has_refund_txt = '入住时间('.str_replace(':00','点',$enter_time).')前'.$refund_hour.'小时内能退';
			// }
		}
		return $has_refund_txt;
	}
	public function format_order_trade_info($trade_info){
		$trade_info_arr = unserialize($trade_info);
		$trade_info_cat = $this->field('`cat_id`,`cat_fid`,`cat_name`,`has_refund`,`refund_hour`,`enter_time`')->where(array('cat_id'=>$trade_info_arr['cat_id']))->find();
		$trade_info_pcat = $this->field('`cat_id`,`cat_fid`,`cat_name`')->where(array('cat_id'=>$trade_info_cat['cat_fid']))->find();
		
		$trade_info_arr['cat_name'] = $trade_info_cat['cat_name'];
		$trade_info_arr['cat_pid'] = $trade_info_pcat['cat_id'];
		$trade_info_arr['cat_pname'] = $trade_info_pcat['cat_name'];
		$trade_info_arr['enter_time'] = $trade_info_cat['enter_time'];
		
		$trade_info_arr['dep_time_txt'] = date('Y年m月d日',strtotime($trade_info_arr['dep_time']));
		$trade_info_arr['end_time_txt'] = date('Y年m月d日',strtotime($trade_info_arr['end_time']));
		ksort($trade_info_arr['price_list']);
		foreach($trade_info_arr['price_list'] as $key=>$value){
			$trade_info_arr['price_list_txt'][] = array(
				'day'=> date('Y年m月d日',strtotime($key)),
				'price'=> $value,
			);
		}
		
		return $trade_info_arr;
	}
	function change_cat_stock($mer_id,$cat_id,$dep_time,$end_time,$stock){
		$is_change =  M('Trade_hotel_stock')->where(array('mer_id'=>$mer_id,'cat_id'=>$cat_id,'stock_day'=>array(array('egt',$dep_time),array('lt',$end_time))))->setDec('stock',$stock);
		return $is_change;
	}
	function get_cat_price($mer_id,$cat_id,$dep_time,$end_time){
		//$hotel_stock_list = M('Trade_hotel_stock')->where(array('mer_id'=>$mer_id,'cat_id'=>$cat_id,'stock_day'=>array(array('egt',$dep_time),array('lt',$end_time))))->select();
		$sql = "SELECT * FROM `pigcms_trade_hotel_stock` WHERE ( `mer_id` = {$mer_id} ) AND ( `cat_id` = {$cat_id}  ) AND ( (`stock_day` >= '{$dep_time}') AND (`stock_day` < '{$end_time}')  ) AND stock_id in (select max(stock_id) from pigcms_trade_hotel_stock  where ( `mer_id` = {$mer_id} ) AND ( `cat_id` = {$cat_id}  ) AND ( (`stock_day` >= '{$dep_time}') AND (`stock_day` < '{$end_time}')  ) group by stock_day)";
		$hotel_stock_list = M()->query($sql);
		// dump(M('Trade_hotel_stock'));
		$hotel_cat = M('Trade_hotel_category')->where(array('cat_id'=>$cat_id))->find();
		if($hotel_cat['book_day'] != 0 && $hotel_cat['book_day']<(strtotime($end_time)-strtotime($dep_time))/86400){
			return array('err_code'=>true,'err_msg'=>'最多可预订'.$hotel_cat['book_day'].'天');
		}
		if(count($hotel_stock_list) <  (strtotime($end_time)-strtotime($dep_time))/86400){
			return array('err_code'=>true,'err_msg'=>'部分日期没有设置价格');
		}else{
			$now_stock = 0;
			$price_count = 0;
			$stock_list = array();
			foreach($hotel_stock_list as $value){
				if($value['stock'] == 0){
					return array('err_code'=>true,'err_msg'=>$value['stock_day'].'已经售完');
				}

				$price_count += $value['price'];

				if($now_stock){
					if($value['stock'] < $now_stock){
						$now_stock = $value['stock'];
					}
				}else{
					$now_stock = $value['stock'];
				}
				$stock_list[] = array('day'=>$value['stock_day'],'stock'=>$value['stock'],'price'=>floatval($value['price']),'discount_price'=>$value['discount_price']);
			}

			return array('err_code'=>false,'stock'=>$now_stock,'discount_room'=>$hotel_cat['discount_room'],'price'=>$price_count,'stock_list'=>$stock_list);
		}
	}
	function get_category_room_stock($mer_id,$dep_time,$end_time){
		$hotel_stock_list = M('Trade_hotel_stock')->where(array('mer_id'=>$mer_id,'stock_day'=>array(array('egt',$dep_time),array('lt',$end_time))))->select();
		

		$tmp_arr = array();
		foreach($hotel_stock_list as $key=>$value){
			$tmp_arr[$value['cat_id']][] = $key;
		}
		// dump($tmp_arr);
		foreach($tmp_arr as $value){
			// if($)
			if(count($value) < (strtotime($end_time)-strtotime($dep_time))/86400){
				foreach($value as $v){
					unset($hotel_stock_list[$v]);
				}
			}
		}
		// dump($hotel_stock_list);
		
		// dump($tmp_arr);
		$stock_list = array();
		$no_stock_day = array();
		foreach($hotel_stock_list as $value){
			if(!in_array($value['cat_id'],$no_stock_day)){
				if(empty($value['stock'])){
					$stock_list[$value['cat_id']]['room'] = 0;
					$stock_list[$value['cat_id']]['price'] = floatval($value['price']);
					$stock_list[$value['cat_id']]['discount_price'] = floatval($value['discount_price']);
					$no_stock_day[] = $value['cat_id'];
					//break;
				}else{
					if($stock_list[$value['cat_id']]){
						if($stock_list[$value['cat_id']]['room'] > $value['stock']){
							$stock_list[$value['cat_id']]['room'] = $value['stock'];
						}
						if($stock_list[$value['cat_id']]['price'] != $value['price']){
							if($stock_list[$value['cat_id']]['price'] > $value['price']){
								$stock_list[$value['cat_id']]['price'] = floatval($value['price']);
								$stock_list[$value['cat_id']]['discount_price'] = floatval($value['discount_price']);
							}
						}
					}else{
						$stock_list[$value['cat_id']]['room'] = $value['stock'];
						$stock_list[$value['cat_id']]['price'] = floatval($value['price']);
						$stock_list[$value['cat_id']]['discount_price'] = floatval($value['discount_price']);
					}
				}
			}
		}

		return $stock_list;
	}
}
?>