<?php
class MerchantAction extends BaseAction{
    public function around(){
        $list = D('Merchant')->get_merchants_by_long_lat($_POST['lat'], $_POST['lng'],2000);
        $this->returnCode(0,$list);
    }

    //	店铺详情
    public function shop(){
        $now_store = D('Merchant_store')->get_store_by_storeId($_POST['store_id']);
        if(empty($now_store)){
            $this->returnCode('20046001');
        }
        //得到当前店铺的评分
        $store_score = D('Merchant_score')->field('`score_all`,`reply_count`')->where(array('parent_id'=>$now_store['store_id'],'type'=>'2'))->find();

        $arr['store_score'] = number_format($store_score['score_all']/$store_score['reply_count'],1);
        if(!empty($this->user_session)){
            $database_user_collect = D('User_collect');
            $condition_user_collect['type'] = 'group_shop';
            $condition_user_collect['id'] = $now_store['store_id'];
            $condition_user_collect['uid'] = $this->user_session['uid'];
            if($database_user_collect->where($condition_user_collect)->find()){
                $now_store['is_collect'] = true;
            }
        }
        //	快店
        $now_store['wap_url'] = U('Food/shop',array('mer_id'=>$now_store['mer_id'],'store_id'=>$now_store['store_id']));

        $arr['now_store']['mer_id'] = $now_store['mer_id'];
        $arr['now_store']['name'] = $now_store['name'];
        $arr['now_store']['phone'] = $now_store['phone'];
        $arr['now_store']['adress'] = $now_store['adress'];
        $arr['now_store']['long'] = $now_store['long'];
        $arr['now_store']['lat'] = $now_store['lat'];
        $arr['now_store']['name'] = $now_store['name'];
        $arr['now_store']['have_group'] = $now_store['have_group'];
        $arr['now_store']['have_meal'] = $now_store['have_meal'];
        $arr['now_store']['have_shop'] = $now_store['have_shop'];
        $arr['now_store']['pay_in_store'] = $this->config['pay_in_store'];
        $arr['now_store']['all_pic'] = $now_store['all_pic'];
        $arr['now_store']['isverify'] = $now_store['isverify'];

        $store_group_list = D('Group')->get_store_group_list($now_store['store_id'],0,true);
        $arr['store_group_list_name'] = '本店'.$this->config['group_alias_name'];

        foreach ($store_group_list as $v) {
			if($v['trade_type'] != 'hotel'){
				$tmp['group_id'] =$v['group_id'] ;
				$tmp['group_name'] =$v['group_name'] ;
				$tmp['price'] =$v['price'] ;
				$tmp['wx_cheap'] =$v['wx_cheap'] ;
				$tmp['old_price'] =$v['old_price'] ;
				$tmp['pin_num'] =$v['pin_num'] ;
				$tmp['sale_count'] =$v['sale_count']+$v['virtual_num'] ;
				$tmp['list_pic'] =$v['list_pic'] ;
				if($v['pin_num'] == 0){
					$arr['store_group_list'][] = $tmp ;
				}
			}
        }
        if(empty($store_group_list)){
            $arr['store_group_list'] = array();
        }


        /* 粉丝行为分析 */
        D('Merchant_request')->add_request($now_store['mer_id'],array('group_hits'=>1));
        /*人气自增*/
        M('Merchant_store')->where(array('store_id'=>$now_store['store_id']))->setInc('hits');
        /* 粉丝行为分析 */
        $this->behavior(array('mer_id'=>$now_store['mer_id'],'store_id'=>$now_store['store_id']));

        if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_store['mer_id']))->select()) {
            $key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
            $kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_store['mer_id'];
        }
       $this->returnCode(0,$arr);
    }

    //店铺列表 参照团购列表做
    public function store_list(){
        //判断排序信息
        $sort_id = 'juli';
        $long_lat['lat'] = $_POST['lat'];
        $long_lat['long'] = $_POST['lng'];
        if(empty($long_lat)) {
            $sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
        }
        $_GET['page'] = $_POST['page'];
        $store_name = I('store_name',false);
        //根据分类信息获取分类
        $return = D('Group')->wap_get_storeList_by_catid('','','',$sort_id, $long_lat['lat'], $long_lat['long'], 0,$store_name);
        foreach($return['store_list'] as &$storeValue){
			$storeValue['juli_wx'] = $storeValue['juli'];
            $storeValue['range_txt'] = $this->wapFriendRange($storeValue['juli']);
            $group_list = S('wap_store_group_wxapi_'.$_POST['page'].$storeValue['store_id']);
            if(empty($group_list)){
                $group_list = D('Group')->get_single_store_group_list($storeValue['store_id'],0,true);
				foreach($group_list as $groupKey=>$groupValue){
					if($groupValue['pin_num'] > 0){
						unset($group_list[$groupKey]);
					}
					if($_POST['Device-Id'] == 'wxapp' && $groupValue['trade_type'] == 'hotel'){
						unset($group_list[$groupKey]);
					}
                }
                S('wap_store_group_wxapi_'.$_POST['page'].$storeValue['store_id'],$group_list,360);
            }else{
                foreach($group_list as $groupKey=>$groupValue){
					if($groupValue['pin_num'] > 0){
						unset($group_list[$groupKey]);
					}
                    if($groupValue['end_time'] < $_SERVER['REQUEST_TIME']){
                        unset($group_list[$groupKey]);
                    }
					if($_POST['Device-Id'] == 'wxapp' && $groupValue['trade_type'] == 'hotel'){
						unset($group_list[$groupKey]);
					}
                }
            }
            $storeValue['group_list'] = array_values($group_list);
            $storeValue['group_count'] = count($group_list);
            if(empty($storeValue['group_count'])){
                unset($storeValue);
            }
        }
        $return['style'] = 'store';
        $return['now_page'] = $_POST['page']?$_POST['page']:1;

        $arr = $return;
        /* 粉丝行为分析 */
        $this->behavior(array('model'=>'Group_index'));

        $this->returnCode(0,$arr);
    }

    /*粉丝行为分析、统计*/
    public function behavior($param=array(),$extra_param=false){
        $openid = $_SESSION['openid'];

        if(empty($param) || empty($openid)){
            return false;
        }

        if(empty($param['model'])){
            $param['model'] = MODULE_NAME.'_'.ACTION_NAME;
        }

        $database_behavior = M('Behavior');

        $data_behavior = $param;
        $data_behavior['openid'] = $openid;
        $data_behavior['date'] = $data_behavior['last_date'] = $_SERVER['REQUEST_TIME'];
        $database_behavior->data($data_behavior)->add();
    }
}
?>