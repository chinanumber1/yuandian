<?php
/*
 * 渠道二维码
 *
 */
class RecognitionAction extends BaseAction{
	public function see_qrcode($type,$id){
		//判断ID是否正确，如果正确且以前生成过二维码则得到ID
		if($type == 'group'){
			$pigcms_return = D('Group')->get_qrcode($id);
		}elseif($type == 'merchant'){
			$pigcms_return = D('Merchant')->get_qrcode($id);
		}elseif($type == 'merchantstore'){
			$pigcms_return = D('Recognition')->get_qrcode_byThirdid($type,$id);
		}elseif($type == 'meal'){
			$pigcms_return = D('Merchant_store')->get_qrcode($id);
		}elseif($type == 'shop'){
			$pigcms_return = D('Merchant_store_shop')->get_qrcode($id);
		}elseif($type == 'lottery'){
			$pigcms_return = D('Lottery')->get_qrcode($id);
		}elseif($type == 'appoint'){
			$pigcms_return = D('Appoint')->get_qrcode($id);
		}elseif($type == 'appoint_category'){
			$pigcms_return = D('Appoint_category')->get_qrcode($id);
		}elseif($type == 'wifi'){
			$pigcms_return = D('Recognition')->get_wifi_qrcode($id);
		}elseif($type == 'waimai'){
			$pigcms_return = D('Waimai_store')->get_qrcode($id);
		}elseif($type == 'chanel'){
			$pigcms_return = D('Chanel_msg_list')->get_qrcode($id);
		}elseif($type == 'house'){
			$pigcms_return = D('House_village')->get_qrcode($id);
		}elseif($type == 'coupon'){
			$pigcms_return = D('System_coupon')->get_qrcode($id);
		}elseif($type == 'card_coupon'){
			$pigcms_return = D('Card_new_coupon')->get_qrcode($id);
		}elseif($type == 'gift'){
			$pigcms_return = D('Gift')->get_qrcode($id);
		}else{
			exit('您查看的内容非法！无法查看二维码！');
		}

		if(empty($pigcms_return) && $type == 'waimai'){
			exit('请您完善店铺设置信息！');
		}elseif(empty($pigcms_return)){
			exit('您查看的内容不存在！无法查看二维码！');
		}


		if(empty($pigcms_return['qrcode_id'])){
			$qrcode_return = D('Recognition')->get_new_qrcode($type,$id);
		}elseif($_GET['gid']>0){
			$qrcode_return = D('Recognition')->get_tmp_qrcode(600000000+$_GET['gid']);
			redirect($qrcode_return['ticket']);
		}else{
			$qrcode_return = D('Recognition')->get_qrcode($pigcms_return['qrcode_id']);
		}

		if($qrcode_return['error_code']){
			exit($qrcode_return['msg']);
		}else if($qrcode_return['qrcode'] == 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='){
			$qrcode_return = D('Recognition')->get_new_qrcode($type,$id);
		}

		if($_GET['img']){
			echo '<html><head><style>*{margin:0;padding:0;}</style></head><body><img src="'.$qrcode_return['qrcode'].'"/></body></html>';
		}else{
			redirect($qrcode_return['qrcode']);
		}
	}
	public function see_login_qrcode(){
		$qrcode_return = D('Recognition')->get_login_qrcode();
		if($qrcode_return['error_code']){
			echo '<html><head></head><body>'.$qrcode_return['msg'].'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>';
		}else{
			$this->assign($qrcode_return);
			$this->display();
		}
	}
	public function see_admin_qrcode(){
		$qrcode_return = D('Recognition')->get_admin_qrcode();
		if($qrcode_return['error_code']){
			echo '<html><head></head><body>'.$qrcode_return['msg'].'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>';
		}else{
			$this->assign($qrcode_return);
			$this->display();
		}
	}
	public function see_tmp_qrcode(){
		$qrcode_return = D('Recognition')->get_tmp_qrcode($_GET['qrcode_id']);
		if($qrcode_return['error_code']){
			echo '<html><head></head><body>'.$qrcode_return['msg'].'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>';
		}else{
			$this->assign($qrcode_return);
			$this->display();
		}
	}

	public function get_tmp_qrcode(){
		$qrcode_return = D('Recognition')->get_tmp_qrcode($_GET['qrcode_id']);
		if($qrcode_return['error_code']){
			exit($qrcode_return['msg']);
		}else{
			redirect($qrcode_return['ticket']);
		}
	}
	
	public function get_own_qrcode_html(){
		$img_width = $_GET['img_width'] ? $_GET['img_width'] : 308;
		$img_height = $_GET['img_height'] ? $_GET['img_height'] : 308;
		$img_url = U('get_own_qrcode',array('qrCon'=>urlencode(htmlspecialchars_decode($_GET['qrCon'])),'size'=>$_GET['size'],'down'=>$_GET['down']));
		echo '<html><head></head><body style="padding:0;margin:0;"><img src="'.$img_url.'" style="width:'.$img_width.'px;height:'.$img_height.'px"></body></html>';
	}
	
	public function get_own_qrcode(){
		$qrCon = $_GET['qrCon'];
		import('@.ORG.phpqrcode');
		$size = $_GET['size'] ? $_GET['size']: 10;
		$is_down = $_GET['down'] ? true: false;
		
		QRcode::png(htmlspecialchars_decode(urldecode($qrCon)),false,0,$size,1,$is_down);
	}
}