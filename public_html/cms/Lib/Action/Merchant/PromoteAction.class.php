<?php
/*
 * 商家推广
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/05 15:28
 * 
 */

class PromoteAction extends BaseAction{
	
    public function index()
    {
    	$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = $this->merchant_session['mer_id'];
		$now_merchant = $database_merchant->field(true,'pwd')->where($condition_merchant)->find();

		if(!empty($now_merchant['pic_info'])){
			$merchant_image_class = new merchant_image();
			$tmp_pic_arr = explode(';',$now_merchant['pic_info']);
			foreach($tmp_pic_arr as $key=>$value){
				$now_merchant['pic'][$key]['title'] = $value;
				$now_merchant['pic'][$key]['url'] = $merchant_image_class->get_image_by_path($value);
			}
		}
		$this->assign('now_merchant',$now_merchant);
		
		$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_merchant['mer_id']);

		$this->assign('merchant_group_list',$merchant_group_list);
		
		
// 		$hits = D('Group')->get_hits_log($now_merchant['mer_id']);
// 		$this->assign('hits', $hits['group_list']);
		
// 		$this->assign('pagebar', $hits['pagebar']);
		
    	$this->display();
    }
	
    public function info()
    {
		$hits = D('Group')->get_hits_log($this->merchant_session['mer_id']);
		if(!empty($hits)){
			import('ORG.Net.IpLocation');
			$IpLocation = new IpLocation();
			foreach($hits['group_list'] as &$hit){
				$last_location = $IpLocation->getlocation($hit['ip']);
				$hit['ip_txt'] = iconv('GBK','UTF-8',$last_location['country']);
			}
		}
		$this->assign('hits', $hits['group_list']);
		
		$this->assign('pagebar', $hits['pagebar']);
		
    	$this->display();
    }
    
    /*public function set()
    {
    	$home_share = D('Home_share')->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
    	if (IS_POST) {
    		$data['a_href'] = isset($_POST['a_href']) ? htmlspecialchars($_POST['a_href']) : $this->config['site_url'] . '/wap.php?c=Index&a=index&token=' . $this->merchant_session['mer_id'];
    		$data['a_name'] = isset($_POST['a_name']) ? htmlspecialchars($_POST['a_name']) : '查看';
    		$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '您是通过' . $this->merchant_session['name'] . '店铺进入本站';
    		if ($home_share) {
    			D('Home_share')->where(array('mer_id' => $this->merchant_session['mer_id']))->save($data);
    		} else {
    			$data['mer_id'] = $this->merchant_session['mer_id'];
    			D('Home_share')->add($data);
    		}
    		$this->success('操作成功');
    	} else {
    		if (empty($home_share)) {
    			$home_share = array('title' => '您是通过' . $this->merchant_session['name'] . '店铺进入本站', 'a_name' => '查看', 'a_href' => $this->config['site_url'] . '/wap.php?c=Index&a=index&token=' . $this->merchant_session['mer_id']);
    		}
    		$this->assign('home_share', $home_share);
    		$this->display();
    	}
    }*/

	/**
	 *商家二维码回复内容自定义
	 */

	public  function content_edit(){
		$mer_id = $this->merchant_session['mer_id'];
		if(IS_POST){
			$data['add_time']=time();
			$data['mer_id']= $mer_id;
			foreach ($_POST['title'] as $key=>$v) {
				if(empty($v)){
					unset($_POST['title'][$key]);
					unset($_POST['info'][$key]);
					unset($_POST['img'][$key]);
					unset($_POST['url'][$key]);
				}
			}
			$data['content']  = serialize($_POST);
			if(M('Merchant_qrcode_content')->where(array('mer_id'=>$mer_id))->find()){
				if(M('Merchant_qrcode_content')->where(array('mer_id'=>$mer_id))->save($data)){
					$this->success("保存成功");
				}else{
					$this->error("保存失败");
				}
			}else{
				if($fid = M('Merchant_qrcode_content')->add($data)){
					$this->success("保存成功");
				}else{
					$this->error("保存失败");
				}
			}


		}else{
			$content = M('Merchant_qrcode_content')->where(array('mer_id'=>$mer_id))->find();
			$content['content']  = unserialize($content['content']);
			$this->assign('content', $content['content']);
			$this->display();
		}
	}
}