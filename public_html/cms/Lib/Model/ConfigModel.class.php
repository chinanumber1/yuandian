<?php
class ConfigModel extends Model{
	public function get_config(){
		// $config = S(C('now_city').'config');
		// if(empty($config)){
			$configs = $this->field('`name`,`value`')->select();
			foreach($configs as $key=>$value){
				$config[$value['name']] = $value['value'];
			}
			$domain_array = parse_url($config['site_url']);
			$config['top_domain'] = $this->get_domain($domain_array['host']);
			// S(C('now_city').'config',$config);
		// }
		!isset($config['group_alias_name']) && $config['group_alias_name']='团购';
		!isset($config['meal_alias_name']) && $config['meal_alias_name']='快店';
		!isset($config['wxapp_alias_name']) && $config['wxapp_alias_name']='营销';
		!isset($config['store_alias_name']) && $config['store_alias_name']='收银';
		!isset($config['weidian_alias_name']) && $config['weidian_alias_name']='微店';
		!isset($config['weidian_url']) && $config['weidian_url']='http://v.bupu.net';

		if($config['map_config']=='google'){
            $IP = get_client_ip(0);
            import('ORG.Net.IpLocation');
            $IpLocation = new IpLocation();
            $last_location = $IpLocation->getlocation($IP);
            $now_country = iconv('GBK','UTF-8',$last_location['country']);
            $arr = array('北京','上海','重庆','天津','省','IANA');
            foreach($arr as $v){
                if(strpos($now_country,$v) !== false){
                    $config['map_config']='baidu';
                    break;
                }
            }
        }
		return $config;
	}
	public function get_gid_config($gid){
		$condition_config['gid'] = $gid;
		$config = $this->field(true)->where($condition_config)->order('`sort` DESC')->select();
		
		return $config;
	}
	protected function get_domain($host){
		$host = strtolower($host);
		$two_suffix = array('.com.cn','.gov.cn','.net.cn','.org.cn','.ac.cn');
		foreach($two_suffix as $key=>$value){
			preg_match('#(.*?)'.$value.'$#',$host,$match_arr);
			if(!empty($match_arr)){
				$match_array = $match_arr;
				break;
			}
		}
		$host_arr = explode('.',$host);
		if(!empty($match_array)){
			$host_arr_last1 = array_pop($host_arr);
			$host_arr_last2 = array_pop($host_arr);
			$host_arr_last3 = array_pop($host_arr);
			
			return $host_arr_last3.'.'.$host_arr_last2.'.'.$host_arr_last1;
		}else{
			$host_arr_last1 = array_pop($host_arr);
			$host_arr_last2 = array_pop($host_arr);
			return $host_arr_last2.'.'.$host_arr_last1;
		}
	}
	public function get_pay_method($notOnline=0,$notOffline=0,$is_wap=false,$is_app=false){
		$tmp_config_list = $this->get_gid_config(7);
		$tmp_config_list_app = $this->get_gid_config(23);

		foreach($tmp_config_list as $key=>$value){
			$config_list[$value['tab_id']]['name'] = $value['tab_name'];
			$config_list[$value['tab_id']]['config'][$value['name']] = $value['value'];
		}
		foreach($tmp_config_list_app as $key=>$value){
			$config_list[$value['tab_id']]['name'] = $value['tab_name'];
			$config_list[$value['tab_id']]['config'][$value['name']] = $value['value'];
		}
		//剔除已关闭的支付
		foreach($config_list as $key=>$value){
			$pigcms_key = 'pay_'.$key.'_open';
			if(empty($value['config'][$pigcms_key]) || ($is_wap && $key == 'chinabank') || ($is_wap && $key == 'alipay' && $value['config'][$pigcms_key] == 3) || (empty($is_wap) && $key == 'alipay' && $value['config'][$pigcms_key] == 2)){
				unset($config_list[$key]);
			}else{	
				$tmp_alias = 'pay_'.$key.'_alias_name';
				if(!empty($value['config'][$tmp_alias])){
					$config_list[$key]['name'] = $value['config'][$tmp_alias];
				}
			}
		}
		if (!$is_wap||$is_app) unset($config_list['alipayh5']);
		if (isset($config_list['alipayh5']) && $config_list['alipayh5']['config']['pay_alipayh5_open'] && $config_list['alipayh5']['config']['pay_alipayh5_appid'] && $config_list['alipayh5']['config']['pay_alipayh5_merchant_private_key'] && $config_list['alipayh5']['config']['pay_alipayh5_public_key']) {
		    unset($config_list['alipay']);
		}
		if($notOffline && $config_list['offline']){
			unset($config_list['offline']);
		}
		if($notOnline){
			$new_config_list = array();
			if($config_list['offline']){
				$new_config_list['offline'] = $config_list['offline'];
			}
			$config_list = $new_config_list;
		}
		return $config_list;
	}
}
?>