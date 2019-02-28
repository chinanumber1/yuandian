<?php
/*
 * 图片打包，一般用于程序转移
 *
 */
class PackimageAction extends BaseAction{
	public $new_dir;
	public $dir;
	protected function _initialize(){
		parent::_initialize();
		set_time_limit(0);
		$this->dir = './upload/';
		$this->new_dir = './imagepack/'.$_SERVER['HTTP_HOST'].'/';
		$this->mknewdir($this->new_dir);
	}
	//第一次打包广告图片
	public function index(){
		$adver_dir = $this->dir.'adver/';
		$new_adver_dir = $this->new_dir.'adver/';
		$this->mknewdir($new_adver_dir);
		$adver_list = D('Adver')->field('`pic`')->select();
		foreach($adver_list as $value){
			if(!empty($value['pic'])){
				$tmp_adver_dir = $new_adver_dir.dirname($value['pic']).'/';
				$this->mknewdir($tmp_adver_dir);
				$this->newcopy($adver_dir.$value['pic'],$new_adver_dir.$value['pic']);
			}
		}
		echo '<script>window.location.href="'.U('Packimage/catemenu').'"</script>';
	}
	public function catemenu(){
		$catemenu_list = D('Catemenu')->field('`picurl`')->select();
		foreach($catemenu_list as $value){
			if(substr($value['picurl'],0,9) == './upload/'){
				$tmp_picurl = substr($value['picurl'],9);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy($value['picurl'],$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/classify').'"</script>';
	}
	public function classify(){
		$classify_list = D('Classify')->field('`img`')->select();
		foreach($classify_list as $value){
			if(substr($value['img'],0,9) == './upload/'){
				$tmp_picurl = substr($value['img'],9);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy($value['img'],$tmp_new_dir.basename($tmp_picurl));
			}else if(substr($value['img'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['img'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				echo '.'.$value['img'].'<br/>';
				echo $tmp_new_dir.basename($tmp_picurl).'<br/>';
				$this->newcopy('.'.$value['img'],$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/config').'"</script>';
	}
	public function config(){
		$config_list = D('Config')->field('`value`')->select();
		foreach($config_list as $value){
			$ltrimstr = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
			if(substr($value['value'],0,strlen($ltrimstr)) == $ltrimstr){
				$tmp_picurl = substr($value['value'],strlen($ltrimstr));
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}else if(substr($value['value'],0,8) == '/upload/'){
				$tmp_fileurl = substr($value['value'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/customer_service').'"</script>';
	}
	public function customer_service(){
		$customer_service_list = D('Customer_service')->field('`head_img`')->select();
		foreach($customer_service_list as $value){
			if(substr($value['head_img'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['head_img'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/first').'"</script>';
	}
	public function first(){
		echo '<script>window.location.href="'.U('Packimage/flash').'"</script>';
	}
	
	public function flash(){
		$flash_list = D('Flash')->field('`img`')->select();
		foreach($flash_list as $value){
			if(substr($value['img'],0,9) == './upload/'){
				$tmp_picurl = substr($value['img'],9);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}else if(substr($value['img'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['img'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/group').'"</script>';
	}
	public function group(){
		$new_group_dir = $this->new_dir.'group/';
		$group_list = D('Group')->field('`pic`')->select();	
		foreach($group_list as $group_value){
			$tmp_pic_arr = explode(';',$group_value['pic']);
			foreach($tmp_pic_arr as $key=>$value){
				$image_tmp = explode(',',$value);
				$this->mknewdir($new_group_dir.$image_tmp[0]);
				$this->newcopy('./upload/group/'.$image_tmp[0].'/'.$image_tmp[1],$new_group_dir.$image_tmp[0].'/'.$image_tmp[1]);
				$this->newcopy('./upload/group/'.$image_tmp[0].'/m_'.$image_tmp[1],$new_group_dir.$image_tmp[0].'/m_'.$image_tmp[1]);
				$this->newcopy('./upload/group/'.$image_tmp[0].'/s_'.$image_tmp[1],$new_group_dir.$image_tmp[0].'/s_'.$image_tmp[1]);
			}
		}
		echo '<script>window.location.href="'.U('Packimage/image_text').'"</script>';
	}
	public function image_text(){
		$image_text_list = D('Image_text')->field('`cover_pic`')->select();
		foreach($image_text_list as $value){
			if(substr($value['cover_pic'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['cover_pic'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/image_text_content').'"</script>';
	}
	public function image_text_content(){
		$group_list = D('Image_text')->field('`content`')->select();
		foreach($group_list as $value){
			preg_match_all("/<img src=\"(.*?)\"/",$value['content'],$match);
			if(!empty($match[1])){
				foreach($match[1] as $k=>$v){
					if(substr($v,0,8) == '/upload/'){
						$tmp_fileurl = substr($v,8);
						$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
						$this->mknewdir($tmp_new_dir);
						$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
					}else{
						$ltrimstr = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
						if(substr($v,0,strlen($ltrimstr)) == $ltrimstr){
							$tmp_picurl = substr($v,strlen($ltrimstr));
							$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
							$this->mknewdir($tmp_new_dir);
							$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
						}
					}
				}
			}
		}
		echo '<script>window.location.href="'.U('Packimage/invitation').'"</script>';
	}
	public function invitation(){
		$invitation_list = D('Invitation')->field('`store_image`')->select();
		foreach($invitation_list as $value){
			$ltrimstr = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
			if(substr($value['value'],0,strlen($ltrimstr)) == $ltrimstr){
				$tmp_picurl = substr($value['value'],strlen($ltrimstr));
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/lottery').'"</script>';
	}
	public function lottery(){
		$lottery_list = D('Lottery')->field('`starpicurl`')->select();
		foreach($lottery_list as $value){
			if(substr($value['starpicurl'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['starpicurl'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/meal').'"</script>';
	}
	public function meal(){
		$new_meal_dir = $this->new_dir.'meal/';
		$meal_list = D('Meal')->field('`image`')->select();
		
		echo '共'.count($meal_list).'条数据<br/>';
		
		foreach($meal_list as $key=>$value){
			echo '正在处理'.($key+1).'条数据<br/>';
			if(!empty($value['image'])){
				$image_tmp = explode(',',$value['image']);
				$this->mknewdir($new_meal_dir.$image_tmp[0]);
				$this->newcopy('./upload/meal/'.$image_tmp[0].'/'.$image_tmp[1],$new_meal_dir.$image_tmp[0].'/'.$image_tmp[1]);
				$this->newcopy('./upload/meal/'.$image_tmp[0].'/m_'.$image_tmp[1],$new_meal_dir.$image_tmp[0].'/m_'.$image_tmp[1]);
				$this->newcopy('./upload/meal/'.$image_tmp[0].'/s_'.$image_tmp[1],$new_meal_dir.$image_tmp[0].'/s_'.$image_tmp[1]);
			}
		}
		echo '<script>window.location.href="'.U('Packimage/shop').'"</script>';
	}
	public function shop(){
		$new_shop_dir = $this->new_dir.'goods/';
		$goods_list = D('Shop_goods')->field('`image`')->select();
		echo '总共'.count($goods_list).'行数据'.'<br/>';
		foreach($goods_list as $key=>$value){
			if(!empty($value['image'])){
				echo '正在处理第'.($key+1).'个'.'<br/>';
				$tmp_pic_arr = explode(';',$value['image']);
				foreach($tmp_pic_arr as $k=>$v){
					$image_tmp = explode(',',$v);
					$this->mknewdir($new_shop_dir.$image_tmp[0]);
					$this->newcopy('./upload/goods/'.$image_tmp[0].'/'.$image_tmp[1],$new_shop_dir.$image_tmp[0].'/'.$image_tmp[1]);
					$this->newcopy('./upload/goods/'.$image_tmp[0].'/m_'.$image_tmp[1],$new_shop_dir.$image_tmp[0].'/m_'.$image_tmp[1]);
					$this->newcopy('./upload/goods/'.$image_tmp[0].'/s_'.$image_tmp[1],$new_shop_dir.$image_tmp[0].'/s_'.$image_tmp[1]);
				}
			}
		}
		echo '<script>window.location.href="'.U('Packimage/shop_content').'"</script>';
	}
	public function shop_content(){
		$group_list = D('Shop_goods')->field('`des`')->select();
		echo '总共'.count($group_list).'行数据'.'<br/>';
		foreach($group_list as $key=>$value){
			echo '正在处理第'.($key+1).'个'.'<br/>';
			preg_match_all("/<img src=\"(.*?)\"/",$value['des'],$match);
			if(!empty($match[1])){
				foreach($match[1] as $k=>$v){
					if(substr($v,0,8) == '/upload/'){
						$tmp_fileurl = substr($v,8);
						$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
						$this->mknewdir($tmp_new_dir);
						$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
					}
				}
			}
		}
		echo '<script>window.location.href="'.U('Packimage/foodshop').'"</script>';
	}
	public function foodshop(){
		$new_meal_dir = $this->new_dir.'foodshop_goods/';
		$goods_list = D('Foodshop_goods')->field('`image`')->select();
		echo '总共'.count($goods_list).'行数据'.'<br/>';
		foreach($goods_list as $key=>$value){
			echo '正在处理第'.($key+1).'个'.'<br/>';
			if(!empty($value['image'])){
				$image_tmp = explode(',',$value['image']);
				$this->mknewdir($new_meal_dir.$image_tmp[0]);
				$this->newcopy('./upload/foodshop_goods/'.$image_tmp[0].'/'.$image_tmp[1],$new_meal_dir.$image_tmp[0].'/'.$image_tmp[1]);
				$this->newcopy('./upload/foodshop_goods/'.$image_tmp[0].'/m_'.$image_tmp[1],$new_meal_dir.$image_tmp[0].'/m_'.$image_tmp[1]);
				$this->newcopy('./upload/foodshop_goods/'.$image_tmp[0].'/s_'.$image_tmp[1],$new_meal_dir.$image_tmp[0].'/s_'.$image_tmp[1]);
			}
		}
		// exit;
		echo '<script>window.location.href="'.U('Packimage/member_card_coupon').'"</script>';
	}
	public function member_card_coupon(){
		$member_card_coupon_list = D('Member_card_coupon')->field('`pic`')->select();
		foreach($member_card_coupon_list as $value){
			if(substr($value['pic'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['pic'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/member_card_focus').'"</script>';
	}
	public function member_card_focus(){
		$member_card_focus_list = D('Member_card_focus')->field('`img`')->select();
		foreach($member_card_focus_list as $value){
			if(substr($value['img'],0,9) == './upload/'){
				$tmp_picurl = substr($value['img'],9);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/member_card_integral').'"</script>';
	}
	public function member_card_integral(){
		$member_card_integral_list = D('Member_card_integral')->field('`pic`')->select();
		foreach($member_card_integral_list as $value){
			if(substr($value['pic'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['pic'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/member_card_set').'"</script>';
	}
	public function member_card_set(){
		$member_card_set_list = D('Member_card_set')->field('`logo`,`bg`,`diybg`,`Lastmsg`,`vip`,`qiandao`,`shopping`,`membermsg`,`contact`,`recharge`,`payrecord`')->select();
		foreach($member_card_set_list as $value){
			if(substr($value['logo'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['logo'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['bg'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['bg'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['diybg'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['diybg'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['Lastmsg'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['Lastmsg'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['vip'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['vip'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['qiandao'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['qiandao'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['shopping'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['shopping'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['membermsg'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['membermsg'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['contact'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['contact'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['recharge'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['recharge'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
			
			if(substr($value['payrecord'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['payrecord'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/merchant').'"</script>';
	}
	
	public function merchant(){
		$new_merchant_dir = $this->new_dir.'merchant/';
		$merchant_list = D('Merchant')->field('`pic_info`')->select();	
		echo '总共'.count($merchant_list).'行数据'.'<br/>';
		foreach($merchant_list as $merchant_key=>$merchant_value){
			echo '正在处理第'.($merchant_key+1).'个'.'<br/>';
			$tmp_pic_arr = explode(';',$merchant_value['pic_info']);
			foreach($tmp_pic_arr as $key=>$value){
				$image_tmp = explode(',',$value);
				$this->mknewdir($new_merchant_dir.$image_tmp[0]);
				$this->newcopy('./upload/merchant/'.$image_tmp[0].'/'.$image_tmp[1],$new_merchant_dir.$image_tmp[0].'/'.$image_tmp[1]);
			}
		}
		echo '<script>window.location.href="'.U('Packimage/merchant_store').'"</script>';
	}
	
	public function merchant_store(){
		$new_store_dir = $this->new_dir.'store/';
		$merchant_store_list = D('Merchant_store')->field('`pic_info`')->select();	
		echo '总共'.count($merchant_store_list).'行数据'.'<br/>';
		foreach($merchant_store_list as $merchant_store_key=>$merchant_store_value){
			echo '正在处理第'.($merchant_store_key+1).'个'.'<br/>';
			$tmp_pic_arr = explode(';',$merchant_store_value['pic_info']);
			foreach($tmp_pic_arr as $key=>$value){
				$image_tmp = explode(',',$value);
				$this->mknewdir($new_store_dir.$image_tmp[0]);
				$this->newcopy('./upload/store/'.$image_tmp[0].'/'.$image_tmp[1],$new_store_dir.$image_tmp[0].'/'.$image_tmp[1]);
			}
		}
		echo '<script>window.location.href="'.U('Packimage/platform').'"</script>';
	}
	
	public function platform(){
		$platform_list = D('Platform')->field('`pic`')->select();
		foreach($platform_list as $value){
			if(substr($value['pic'],0,8) == '/upload/'){
				$tmp_picurl = substr($value['pic'],8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}
		}
		echo '<script>window.location.href="'.U('Packimage/reply_pic').'"</script>';
	}
	
	public function reply_pic(){
		$new_reply_dir = $this->new_dir.'reply/';
		$reply_pic_list = D('Reply_pic')->field('`pic`')->select();
		foreach($reply_pic_list as $value){
			$image_tmp = explode(',',$value['pic']);
			$this->mknewdir($new_reply_dir.$image_tmp[0]);
			$this->newcopy('./upload/reply/'.$image_tmp[0].'/'.$image_tmp[1],$new_reply_dir.$image_tmp[0].'/'.$image_tmp[1]);
			$this->newcopy('./upload/reply/'.$image_tmp[0].'/m_'.$image_tmp[1],$new_reply_dir.$image_tmp[0].'/m_'.$image_tmp[1]);
			$this->newcopy('./upload/reply/'.$image_tmp[0].'/s_'.$image_tmp[1],$new_reply_dir.$image_tmp[0].'/s_'.$image_tmp[1]);
		}
		echo '<script>window.location.href="'.U('Packimage/slider').'"</script>';
	}
	
	public function slider(){
		$slider_dir = $this->dir.'slider/';
		$new_slider_dir = $this->new_dir.'slider/';
		$this->mknewdir($new_slider_dir);
		$slider_list = D('Slider')->field('`pic`')->select();
		foreach($slider_list as $value){
			if(!empty($value['pic'])){
				$tmp_slider_dir = $new_slider_dir.dirname($value['pic']).'/';
				$this->mknewdir($tmp_slider_dir);
				$this->newcopy($slider_dir.$value['pic'],$new_slider_dir.$value['pic']);
			}
		}
		echo '<script>window.location.href="'.U('Packimage/group_category').'"</script>';
	}
	public function group_category(){
		$system_dir = $this->dir.'system/';
		$new_system_dir = $this->new_dir.'system/';
		$this->mknewdir($new_system_dir);
		$category_list = D('Group_category')->field('`cat_pic`')->select();
		foreach($category_list as $value){
			if(!empty($value['cat_pic'])){
				$tmp_system_dir = $new_system_dir.dirname($value['cat_pic']).'/';
				$this->mknewdir($tmp_system_dir);
				$this->newcopy($system_dir.$value['cat_pic'],$new_system_dir.$value['cat_pic']);
			}
		}
		echo '<script>window.location.href="'.U('Packimage/classify_category').'"</script>';
	}
	public function classify_category(){
		$system_dir = $this->dir.'system/';
		$new_system_dir = $this->new_dir.'system/';
		$this->mknewdir($new_system_dir);
		$category_list = D('Classify_category')->field('`cat_pic`')->select();
		foreach($category_list as $value){
			if(!empty($value['cat_pic'])){
				$tmp_system_dir = $new_system_dir.dirname($value['cat_pic']).'/';
				$this->mknewdir($tmp_system_dir);
				$this->newcopy($system_dir.$value['cat_pic'],$new_system_dir.$value['cat_pic']);
			}
		}
		echo '<script>window.location.href="'.U('Packimage/group_content').'"</script>';
	}
	public function group_content(){
		$group_list = D('Group')->field('`content`')->select();
		foreach($group_list as $value){
			preg_match_all("/<img src=\"(.*?)\"/",$value['content'],$match);
			if(!empty($match[1])){
				foreach($match[1] as $k=>$v){
					if(substr($v,0,8) == '/upload/'){
						$tmp_fileurl = substr($v,8);
						$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
						$this->mknewdir($tmp_new_dir);
						$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
					}
				}
			}
			// dump($match);
			// if(!empty($value['cat_pic'])){
				// $tmp_system_dir = $new_system_dir.dirname($value['cat_pic']).'/';
				// $this->mknewdir($tmp_system_dir);
				// $this->newcopy($system_dir.$value['cat_pic'],$new_system_dir.$value['cat_pic']);
			// }
		}
		echo '<script>window.location.href="'.U('Packimage/extension_activity').'"</script>';
	}
	public function extension_activity(){
		$new_extension_dir = $this->new_dir.'extension/';
		$extension_activity = D('Extension_activity')->field('`bg_pic`')->select();	
		$this->mknewdir($new_extension_dir);
		foreach($extension_activity as $extension_value){
			$this->newcopy('./upload/extension/'.$extension_value['bg_pic'],$new_extension_dir.$extension_value['bg_pic']);
		}
		echo '<script>window.location.href="'.U('Packimage/extension_activity_list').'"</script>';
	}
	public function extension_activity_list(){
		$new_extension_dir = $this->new_dir.'extension/';
		$new_index_extension_dir = $this->new_dir.'activity/index_pic/';
		$extension_activity_list = D('Extension_activity_list')->field('`pic`,`index_pic`')->select();	
		foreach($extension_activity_list as $extension_value){
			$tmp_pic_arr = explode(';',$extension_value['pic']);
			foreach($tmp_pic_arr as $key=>$value){
				$image_tmp = explode(',',$value);
				$this->mknewdir($new_extension_dir.$image_tmp[0]);
				$this->newcopy('./upload/extension/'.$image_tmp[0].'/'.$image_tmp[1],$new_extension_dir.$image_tmp[0].'/'.$image_tmp[1]);
				$this->newcopy('./upload/extension/'.$image_tmp[0].'/m_'.$image_tmp[1],$new_extension_dir.$image_tmp[0].'/m_'.$image_tmp[1]);
				$this->newcopy('./upload/extension/'.$image_tmp[0].'/s_'.$image_tmp[1],$new_extension_dir.$image_tmp[0].'/s_'.$image_tmp[1]);
			}
			if(!empty($extension_value['index_pic'])){
				// dump($new_index_extension_dir.dirname($extension_value['index_pic']));
				$this->mknewdir($new_index_extension_dir.dirname($extension_value['index_pic']));
				$this->newcopy('./upload/activity/index_pic/'.$extension_value['index_pic'],$new_index_extension_dir.$extension_value['index_pic']);
			}
			$this->newcopy('./upload/extension/'.$extension_value['bg_pic'],$new_extension_dir.$extension_value['bg_pic']);
		}
		echo '<script>window.location.href="'.U('Packimage/extension_activity_content').'"</script>';
	}
	public function extension_activity_content(){
		$extension_activity_list = D('Extension_activity_list')->field('`info`')->select();
		foreach($extension_activity_list as $value){
			preg_match_all("/<img src=\"(.*?)\"/",$value['info'],$match);
			if(!empty($match[1])){
				foreach($match[1] as $k=>$v){
					if(substr($v,0,8) == '/upload/'){
						$tmp_fileurl = substr($v,8);
						$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
						$this->mknewdir($tmp_new_dir);
						$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
					}
				}
			}
		}
		echo '<script>window.location.href="'.U('Packimage/appoint_category').'"</script>';
	}
	public function appoint_category(){
		$new_appoint_dir = $this->new_dir.'system/';
		$category_list = D('Appoint_category')->field('`cat_pic`,`cat_big_pic`')->select();	
		foreach($category_list as $cat_value){
			$this->newcopy('./upload/system/'.$cat_value['cat_pic'],$new_appoint_dir.$cat_value['cat_pic']);
			$this->newcopy('./upload/system/'.$cat_value['cat_big_pic'],$new_appoint_dir.$cat_value['cat_big_pic']);
		}
		// die;
		echo '<script>window.location.href="'.U('Packimage/appoint').'"</script>';
	}
	public function appoint(){
		$new_appoint_dir = $this->new_dir.'appoint/';
		$appoint_list = D('Appoint')->field('`pic`,`appoint_pic_content`')->select();	
		foreach($appoint_list as $appoint_value){
			$tmp_pic_arr = explode(';',$appoint_value['pic']);
			foreach($tmp_pic_arr as $key=>$value){
				$image_tmp = explode(',',$value);
				$this->mknewdir($new_appoint_dir.$image_tmp[0]);
				$this->newcopy('./upload/appoint/'.$image_tmp[0].'/'.$image_tmp[1],$new_appoint_dir.$image_tmp[0].'/'.$image_tmp[1]);
				$this->newcopy('./upload/appoint/'.$image_tmp[0].'/m_'.$image_tmp[1],$new_appoint_dir.$image_tmp[0].'/m_'.$image_tmp[1]);
				$this->newcopy('./upload/appoint/'.$image_tmp[0].'/s_'.$image_tmp[1],$new_appoint_dir.$image_tmp[0].'/s_'.$image_tmp[1]);
			}
			
			preg_match_all("/<img src=\"(.*?)\"/",$appoint_value['appoint_pic_content'],$match);
			if(!empty($match[1])){
				foreach($match[1] as $k=>$v){
					if(substr($v,0,8) == '/upload/'){
						$tmp_fileurl = substr($v,8);
						$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
						$this->mknewdir($tmp_new_dir);
						$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
					}else{
						$ltrimstr = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
						if(substr($v,0,strlen($ltrimstr)) == $ltrimstr){
							$tmp_picurl = substr($v,strlen($ltrimstr));
							$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
							$this->mknewdir($tmp_new_dir);
							$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
						}
					}
				}
			}
		}
		echo '<script>window.location.href="'.U('Packimage/classify_userinput').'"</script>';
	}
	public function classify_userinput(){
		$classify_userinput_list = D('Classify_userinput')->field('`imgs`')->select();	
		$ltrimstr = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
		foreach($classify_userinput_list as $classify_userinput_value){
			if(empty($classify_userinput_value['imgs'])){
				continue;
			}
			$imgs = unserialize($classify_userinput_value['imgs']);
			foreach($imgs as $img){
				echo $img.'<br/>';
				if(substr($img,0,strlen($ltrimstr)) == $ltrimstr){
					$tmp_picurl = substr($img,strlen($ltrimstr));
					$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
				}else if(substr($img,0,8) == '/upload/'){
					$tmp_fileurl = substr($img,8);
					$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
				}
				echo $tmp_new_dir.basename($tmp_picurl).'<br/><br/><br/><br/>';
			}
			// dump($imgs);
		}
		
		echo '<script>window.location.href="'.U('Packimage/system_coupon').'"</script>';
	}
	public function system_coupon(){
		$system_coupon = D('System_coupon')->field('`wx_share_img`,`img`,`img_coupon`')->select();	
		$ltrimstr = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
		foreach($system_coupon as $value){
			$img = $value['wx_share_img'];
			if(!empty($img)){
				if(substr($img,0,strlen($ltrimstr)) == $ltrimstr){
					$tmp_picurl = substr($img,strlen($ltrimstr));
					$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
				}else if(substr($img,0,8) == '/upload/'){
					$tmp_fileurl = substr($img,8);
					$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
				}
				echo $tmp_new_dir.basename($tmp_picurl).'<br/><br/><br/><br/>';
			}
			
			$img = $value['img'];
			if(!empty($img)){				
				if(substr($img,0,strlen($ltrimstr)) == $ltrimstr){
					$tmp_picurl = substr($img,strlen($ltrimstr));
					$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
				}else if(substr($img,0,8) == '/upload/'){
					$tmp_fileurl = substr($img,8);
					$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
				}
				echo $tmp_new_dir.basename($tmp_picurl).'<br/><br/><br/><br/>';
			}
			
			$img = $value['img_coupon'];
			if(!empty($img)){	
				if(substr($img,0,strlen($ltrimstr)) == $ltrimstr){
					$tmp_picurl = substr($img,strlen($ltrimstr));
					$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
				}else if(substr($img,0,8) == '/upload/'){
					$tmp_fileurl = substr($img,8);
					$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
				}
				echo $tmp_new_dir.basename($tmp_picurl).'<br/><br/><br/><br/>';
			}
		}
		
		echo '<script>window.location.href="'.U('Packimage/card_new_coupon').'"</script>';
	}
	public function card_new_coupon(){
		$card_new_coupon = D('Card_new_coupon')->field('`wx_share_img`,`img`,`img_coupon`')->select();	
		$ltrimstr = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
		foreach($card_new_coupon as $value){
			$img = $value['wx_share_img'];
			if(!empty($img)){
				if(substr($img,0,strlen($ltrimstr)) == $ltrimstr){
					$tmp_picurl = substr($img,strlen($ltrimstr));
					$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
				}else if(substr($img,0,8) == '/upload/'){
					$tmp_fileurl = substr($img,8);
					$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
				}
				echo $tmp_new_dir.basename($tmp_picurl).'<br/><br/><br/><br/>';
			}
			$img = $value['img'];
			if(!empty($img)){
				if(substr($img,0,strlen($ltrimstr)) == $ltrimstr){
					$tmp_picurl = substr($img,strlen($ltrimstr));
					$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
				}else if(substr($img,0,8) == '/upload/'){
					$tmp_fileurl = substr($img,8);
					$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
				}
				echo $tmp_new_dir.basename($tmp_picurl).'<br/><br/><br/><br/>';
			}
			$img = $value['img_coupon'];
			if(!empty($img)){
				if(substr($img,0,strlen($ltrimstr)) == $ltrimstr){
					$tmp_picurl = substr($img,strlen($ltrimstr));
					$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
				}else if(substr($img,0,8) == '/upload/'){
					$tmp_fileurl = substr($img,8);
					$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
					$this->mknewdir($tmp_new_dir);
					$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
				}
				echo $tmp_new_dir.basename($tmp_picurl).'<br/><br/><br/><br/>';
			}
		}
		echo '<script>window.location.href="'.U('Packimage/house_village_slider').'"</script>';
	}
	public function house_village_slider(){
		$slider_dir = $this->dir.'slider/';
		$new_slider_dir = $this->new_dir.'slider/';
		$this->mknewdir($new_slider_dir);
		$slider_list = D('House_village_slider')->field('`pic`')->select();
		foreach($slider_list as $value){
			if(!empty($value['pic'])){
				$tmp_slider_dir = $new_slider_dir.dirname($value['pic']).'/';
				$this->mknewdir($tmp_slider_dir);
				$this->newcopy($slider_dir.$value['pic'],$new_slider_dir.$value['pic']);
			}
		}
		
		echo '<script>window.location.href="'.U('Packimage/house_village_activity').'"</script>';
	}
	public function house_village_activity(){
		$slider_dir = $this->dir.'activity/';
		$new_slider_dir = $this->new_dir.'activity/';
		$this->mknewdir($new_slider_dir);
		$slider_list = D('House_village_activity')->field('`pic`')->select();
		foreach($slider_list as $value){
			if(!empty($value['pic'])){
				$tmp_slider_dir = $new_slider_dir.dirname($value['pic']).'/';
				$this->mknewdir($tmp_slider_dir);
				$this->newcopy($slider_dir.$value['pic'],$new_slider_dir.$value['pic']);
			}
		}
		
		echo '<script>window.location.href="'.U('Packimage/house_service_category').'"</script>';
	}
	public function house_service_category(){
		$slider_dir = $this->dir.'service/';
		$new_slider_dir = $this->new_dir.'service/';
		$this->mknewdir($new_slider_dir);
		$slider_list = D('House_service_category')->field('`cat_img`')->select();
		foreach($slider_list as $value){
			if(!empty($value['cat_img']) && substr($value['cat_img'],0,4) != '/tpl'){
				$tmp_slider_dir = $new_slider_dir.dirname($value['cat_img']).'/';
				$this->mknewdir($tmp_slider_dir);
				$this->newcopy($slider_dir.$value['cat_img'],$new_slider_dir.$value['cat_img']);
			}
		}
		
		echo '<script>window.location.href="'.U('Packimage/house_village_nav').'"</script>';
	}
	public function house_village_nav(){
		$slider_dir = $this->dir.'service/';
		$new_slider_dir = $this->new_dir.'service/';
		$this->mknewdir($new_slider_dir);
		$slider_list = D('House_village_nav')->field('`img`')->select();
		foreach($slider_list as $value){
			if(!empty($value['img']) && substr($value['img'],0,4) != '/tpl'){
				$tmp_slider_dir = $new_slider_dir.dirname($value['img']).'/';
				$this->mknewdir($tmp_slider_dir);
				$this->newcopy($slider_dir.$value['img'],$new_slider_dir.$value['img']);
			}
		}
		
		echo '<script>window.location.href="'.U('Packimage/bbs_aricle').'"</script>';
		
	}
	public function bbs_aricle(){
		$bbs_aricle = D('Bbs_aricle')->field('`aricle_img`')->select();	
		$ltrimstr = 'http://'.$_SERVER['HTTP_HOST'].'/upload/';
		foreach($bbs_aricle as $value){
			if(empty($value['aricle_img'])){
				continue;
			}
			$img = $value['aricle_img'];
			echo $img.'<br/>';
			if(substr($img,0,strlen($ltrimstr)) == $ltrimstr){
				$tmp_picurl = substr($img,strlen($ltrimstr));
				$tmp_new_dir = $this->new_dir.dirname($tmp_picurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_picurl,$tmp_new_dir.basename($tmp_picurl));
			}else if(substr($img,0,8) == '/upload/'){
				$tmp_fileurl = substr($img,8);
				$tmp_new_dir = $this->new_dir.dirname($tmp_fileurl).'/';
				$this->mknewdir($tmp_new_dir);
				$this->newcopy('./upload/'.$tmp_fileurl,$tmp_new_dir.basename($tmp_fileurl));
			}
			echo $tmp_new_dir.basename($tmp_picurl).'<br/><br/><br/><br/>';
		}
		
		echo '<script>window.location.href="'.U('Packimage/home_menu').'"</script>';
	}
	public function home_menu(){
		$slider_dir = $this->dir.'slider/';
		$new_slider_dir = $this->new_dir.'slider/';
		$this->mknewdir($new_slider_dir);
		$slider_list = D('Home_menu')->field('`pic_path`,`hover_pic_path`')->select();
		foreach($slider_list as $value){
			if(!empty($value['pic_path'])){
				$tmp_slider_dir = $new_slider_dir.dirname($value['pic_path']).'/';
				$this->mknewdir($tmp_slider_dir);
				$this->newcopy($slider_dir.$value['pic_path'],$new_slider_dir.$value['pic_path']);
			}
			if(!empty($value['hover_pic_path'])){
				$tmp_slider_dir = $new_slider_dir.dirname($value['hover_pic_path']).'/';
				$this->mknewdir($tmp_slider_dir);
				$this->newcopy($slider_dir.$value['hover_pic_path'],$new_slider_dir.$value['hover_pic_path']);
			}
		}
		
		echo '图片打包完成';
	}
	
	public function newcopy($old,$new){
		if(file_exists($old) && !file_exists($new)){
			echo $old.'<br/>';
			echo $new.'<br/>';
			@copy($old,$new);
		}
	}
	public function mknewdir($dirname){
		if(!is_dir($dirname)){
			echo $dirname.'<br/>';
			@mkdir($dirname,0777,true);
		}
	}
}