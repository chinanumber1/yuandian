<?php
class SandpayAction extends BaseAction{
	protected $bankList;
	public function __construct(){
		
		parent::__construct();
		if(IS_GET){
			if(empty($this->user_session)){
				redirect(U('Login/index'));
			}
		}
	}
    public function index(){
		
		if(empty($this->config['pay_sandpay_open']) || empty($this->config['pay_sandpay_mid'])){
			$this->error_tips('平台未开启银行卡支付或未填写正确参数');
		}
		
		$sql = "SELECT `s`.*,`b`.`bankname`,`b`.`cardname` FROM " . C('DB_PREFIX') . 'sandpay AS s INNER JOIN ' . C('DB_PREFIX') . "banklist as b ON s.cardBin=b.cardbin WHERE s.uid={$this->user_session['uid']} AND s.is_bind='1' ORDER BY s.card_id DESC";
		
		$card_list = M()->query($sql);
		
		$this->assign('card_list',$card_list);
		
		if($_GET['no_order']){
			$_SESSION['sandpay_order_id'] = '';
		}
		
		if($_GET['order_id'] || $_SESSION['sandpay_order_id']){
			$condition_order['sandpay_rand_id'] = $_GET['order_id'] ? $_GET['order_id'] : $_SESSION['sandpay_order_id'];
			$now_order = M('Sandpay_order')->where($condition_order)->find();
			if(empty($now_order) || $now_order['uid'] != $this->user_session['uid']){
				$this->error('该订单不存在');
			}
			$_SESSION['sandpay_order_id'] = $now_order['sandpay_rand_id'];
			$this->assign('now_order',$now_order);
			// dump($now_order);
		}

    	$this->display();
    }
	public function add(){	
		$now_year = date('Y');
		$card_over_year = $now_year+10;
		$card_over_year_array = array();
		for($i = $now_year;$i <= $card_over_year;$i++){
			$card_over_year_array[] = array(
				'four'=>$i,
				'two'=>substr($i,-2)
			);
		}
		$this->assign('card_over_year_array',$card_over_year_array);
		
		$card_over_month_array = array();
		for($i = 1;$i <= 12;$i++){
			if($i < 10){
				$card_over_month_array[] = '0'.$i;
			}else{
				$card_over_month_array[] = $i;
			}
		}
		$this->assign('card_over_month_array',$card_over_month_array);
	
		$this->display();
	}
	public function motify(){
		if(empty($this->user_session)){
			$this->error('请先登录');
		}
		$condition_banklist['cardbin'] = substr($_POST['cardNo'],0,6);
		if(!M('Banklist')->where($condition_banklist)->find()){
			$this->error('暂只支持邮政储蓄银行信用卡使用');
		}
		
		$_POST['userId'] = $this->user_session['uid'];
		//随机绑卡流水号
		$nowtime = date("ymdHis");
		$_POST['applyNo'] = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);
		
		$_POST['extend'] = '';
		
		$return = $this->sendSandpay('/fastPay/apiPay/applyBindCard','sandPay.fastPay.apiPay.applyBindCard',$_POST);
		
		if($return['data']['head']['respCode'] != '000000'){
			$this->error('绑卡失败提示：'.$return['data']['head']['respMsg']);
		}
		
		$_POST['uid'] = $_POST['userId'];
		$_POST['sdMsgNo'] = strval($return['data']['body']['sdMsgNo']);
		$_POST['cardBin'] = substr($_POST['cardNo'],0,6);
		//添加成功返回ID给前台
		if($card_id = M('Sandpay')->data($_POST)->add()){
			$this->success($card_id);
		}else{
			$this->error('绑卡保存失败，请重试');
		}
	}
	
	public function order_sms(){
		//校验GET参数 bind_id 以及bind_id是否对应当前用户
		$condition['card_id'] = intval($_GET['bind_id']);
		$condition['uid'] = $this->user_session['uid'];
		$now_card = M('Sandpay')->where($condition)->find();
		
		if(empty($now_card)){
			$this->error_tips('绑卡不存在');
		}
		
		//计算卡的银行名称
		require_once APP_PATH . 'Lib/ORG/BankList.class.php';
		$this->bankList = $bankList;
		$now_card['card_bank'] = $this->get_bank_name($now_card['cardNo']);
		
		$this->assign('now_card',$now_card);
		
		
		$condition_order['sandpay_rand_id'] = $_GET['order_id'];
		$now_order = M('Sandpay_order')->where($condition_order)->find();
		if(empty($now_order) || $now_order['uid'] != $this->user_session['uid']){
			$this->error('该订单不存在');
		}
		$this->assign('now_order',$now_order);
			
		$this->display();
	}
	public function get_order_sms(){
		if(empty($this->user_session)){
			$this->error('请先登录');
		}
		
		$condition['card_id'] = intval($_POST['bind_id']);
		$condition['uid'] = $this->user_session['uid'];
		$now_card = M('Sandpay')->where($condition)->find();
		if(empty($now_card)){
			$this->error('绑卡不存在');
		}
		$post = array(
			'userId'	=> $this->user_session['uid'],
			'orderCode' => $_POST['order_id'],
			'phoneNo' 	=> $now_card['phoneNo'],
			'bid' 		=> $now_card['bid'],
			'extend' 	=> '',
		);
		
		$return = $this->sendSandpay('/fastPay/apiPay/sms','sandPay.fastPay.common.sms',$post);
		// var_dump($post);
		// var_dump($return);
		if($return['data']['head']['respCode'] != '000000'){
			$this->error('发送短信失败提示：'.$return['data']['head']['respMsg']);
		}

		$this->success('发送短信成功');
	}
	
	public function confirmSms(){
		//校验GET参数 bind_id 以及bind_id是否对应当前用户
		$condition['card_id'] = intval($_GET['bind_id']);
		$condition['uid'] = $this->user_session['uid'];
		$now_card = M('Sandpay')->where($condition)->find();

		if(empty($now_card)){
			$this->error_tips('绑卡不存在');
		}
		
		$this->assign('now_card',$now_card);
		
		$this->display();
	}
	public function delete(){
		$condition['card_id'] = intval($_POST['bind_id']);
		$condition['uid'] = $this->user_session['uid'];
		$now_card = M('Sandpay')->where($condition)->find();
		if(empty($now_card)){
			$this->error_tips('绑卡不存在');
		}
		$nowtime = date("ymdHis");
		$post = array(
			'userId'	=> $this->user_session['uid'],
			'applyNo'	=> $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']),
			'bid' 		=> $now_card['bid'],
			'notifyUrl'	=> $this->config['site_url'],
			'extend' 	=> '',
		);
		
		$return = $this->sendSandpay('/fastPay/apiPay/unbindCard','sandPay.fastPay.apiPay.unbindCard',$post);
		
		if($return['data']['head']['respCode'] != '000000'){
			$this->error('解绑卡失败提示：'.$return['data']['head']['respMsg']);
		}
		
		if(M('Sandpay')->where($condition)->delete()){
			$this->success('解绑成功');
		}else{
			$this->error('解绑失败，请重试');
		}
	}
	public function card_save(){
		if(empty($this->user_session)){
			$this->error('请先登录');
		}
		
		$condition['card_id'] = intval($_POST['bind_id']);
		$condition['uid'] = $this->user_session['uid'];
		$now_card = M('Sandpay')->where($condition)->find();
		if(empty($now_card)){
			$this->error('绑卡不存在');
		}
		$post = array(
			'userId' 	=> $this->user_session['uid'],
			'sdMsgNo' 	=> $now_card['sdMsgNo'],
			'phoneNo' 	=> $now_card['phoneNo'],
			'smsCode'	=> $_POST['smsCode'],
			'notifyUrl' => $this->config['site_url'],
			'extend' => '',
		);
		
		$return = $this->sendSandpay('/fastPay/apiPay/confirmBindCard','sandPay.fastPay.apiPay.confirmBindCard',$post);
		
		if($return['data']['head']['respCode'] != '000000'){
			$this->error('绑卡失败提示：'.$return['data']['head']['respMsg']);
		}
		
		$data['bid'] = strval($return['data']['body']['bid']);
		$data['is_bind'] = '1';
		$data['bind_time'] = time();
		if(M('Sandpay')->where($condition)->data($data)->save()){
			$this->success('绑卡成功');
		}else{
			$this->error('绑卡失败，请重试');
		}
	}
	public function card_pay(){
		if(empty($this->user_session)){
			$this->error('请先登录');
		}
		$condition['card_id'] = intval($_POST['bind_id']);
		$condition['uid'] = $this->user_session['uid'];
		$now_card = M('Sandpay')->where($condition)->find();
		if(empty($now_card)){
			$this->error('绑卡不存在');
		}
		
		$condition_order['sandpay_rand_id'] = $_POST['order_id'];
		$now_order = M('Sandpay_order')->where($condition_order)->find();
		if(empty($now_order) || $now_order['uid'] != $this->user_session['uid']){
			$this->error('该订单不存在');
		}
		
		$post = array(
			'userId'  			=> $this->user_session['uid'],
			'bid' 	  			=> $now_card['bid'],
			'phoneNo' 			=> $now_card['phoneNo'],
			'smsCode' 			=> $_POST['smsCode'],
			'orderCode' 		=> $_POST['order_id'],
			'orderTime' 		=> date('YmdHis',$now_order['create_time']),
			'totalAmount'		=> $now_order['order_price']*100,
			'subject' 			=> $now_order['order_name'],
			'body' 				=> '订单流水号：'.$now_order['order_id'],
			'currencyCode' 		=> '156',
			'clearCycle' 		=> '0',
			'notifyUrl' 		=> $this->config['site_url'],
			'extend'			=> '',
		);
		
		$return = $this->sendSandpay('/fastPay/apiPay/pay','sandPay.fastPay.apiPay.pay',$post);
		
		if($return['data']['head']['respCode'] != '000000'){
			$this->error('支付失败提示：'.$return['data']['head']['respMsg']);
		}
		
		$data_order['clear_date'] = strval($return['data']['body']['clearDate']);
		$data_order['paid_money'] = intval($return['data']['body']['totalAmount'])/100;
		$data_order['paid_time'] = strval($return['data']['body']['payTime']);
		$data_order['third_id'] = strval($return['data']['body']['tradeNo']);
		$data_order['is_paid'] = '1';
		$data_order['pay_time'] = time();
		$condition_order['sandpay_rand_id'] = $_POST['order_id'];
		if(M('Sandpay_order')->where($condition_order)->data($data_order)->save()){
			$this->success($this->config['site_url'].'/wap.php?c=Pay&a=return_url&pay_type=sandpay&is_mobile=1&sandpay_order_id='.$now_order['sandpay_rand_id']);
		}else{
			// dump(M('Sandpay'));
			$this->error('支付存储失败，请联系管理员');
		}
	}
	private function sendSandpay($url,$method,$data){
		
		$domain = 'https://cashier.sandpay.com.cn/';	//生产
		// $domain = 'http://61.129.71.103:8003/';			//测试
		
		$postData = array();
		$postData['charset'] = 'utf-8';
		
		$headData['version'] = '1.0';
		$headData['method'] = $method;
		$headData['productId'] = '00000018';
		$headData['accessType'] = '1';
		$headData['mid'] = $this->config['pay_sandpay_mid'];
		$headData['channelType'] = '07';
		$headData['reqTime'] = date('YmdHis');
		
		$data = array(
			'head' => $headData,
			'body' => $data,
		);
		
		$postData['data'] = json_encode($data,320);
		
		$postData['signType'] = '01';
		$postData['sign'] = base64_encode($postData['data']);
		
		$pub = $this->loadX509Cert(APP_PATH.'..'.$this->config['pay_sandpay_cer']);
		$prk = $this->loadPk12Cert(APP_PATH.'..'.$this->config['pay_sandpay_pfx'], $this->config['pay_sandpay_pfx_pwd']);
		
		$sign = $this->sandpaysign($prk,$postData['data']);
		$postData['sign'] = urlencode($sign);
		
		import('ORG.Net.Http');
		$http = new Http();
		
		$postDataStr = '';
		foreach($postData as $key=>$value){
			$postDataStr.= $key.'='.$value.'&';
		}
		$postDataStr = rtrim($postDataStr,'&');
		// echo $domain.$url;
		echo $domain.$url."\n\n\n";
		echo $postDataStr."\n\n\n";
		$return = Http::curlPostOwn($domain.$url, $postDataStr);
		// $this->error($postDataStr);
		echo ($return);
		$returnArr = explode('&',urldecode($return));
		$newReturnArr = array();
		foreach($returnArr as $value){
			$tmp = explode('=',$value);
			if($tmp[0] == 'data'){
				$tmp[1] = json_decode($tmp[1],true);
			}
			$newReturnArr[$tmp[0]] = $tmp[1];
		}
		dump($newReturnArr);
		fdump($newReturnArr,'returnData');
		// $this->error($newReturnArr);
		
		return $newReturnArr;
	}
	/**
     * 获取公钥
     * @param $path
     * @return mixed
     * @throws \Exception
     */
	public function loadX509Cert($path){
		try {
            $file = file_get_contents($path);
            if (!$file) {
                throw new \Exception('loadX509Cert::file_get_contents ERROR');
            }

            $cert = chunk_split(base64_encode($file), 64, "\n");
            $cert = "-----BEGIN CERTIFICATE-----\n" . $cert . "-----END CERTIFICATE-----\n";
			
            $res = openssl_pkey_get_public($cert);
            $detail = openssl_pkey_get_details($res);
            openssl_free_key($res);

            if (!$detail) {
                throw new \Exception('loadX509Cert::openssl_pkey_get_details ERROR');
            }
            return $detail['key'];
        } catch (\Exception $e) {
            throw $e;
        }
	}
	/**
     * 获取私钥
     * @param $path
     * @param $pwd
     * @return mixed
     * @throws \Exception
     */
    private function loadPk12Cert($path, $pwd) {
        try {
            $file = file_get_contents($path);
            if (!$file) {
                throw new \Exception('loadPk12Cert::file_get_contents ERROR');
            }

            if (!openssl_pkcs12_read($file, $cert, $pwd)) {
                throw new \Exception('loadPk12Cert::openssl_pkcs12_read ERROR');
            }

            return $cert['pkey'];
        } catch (\Exception $e) {
            errorHandle::log($e);
            throw $e;
        }
    }
	
	/**
     * 私钥签名
     * @param $plainText
     * @return string
     * @throws \Exception
     */
    public function sandpaysign($prk,$plainText) {
        try {
            $resource = openssl_pkey_get_private($prk);
            $result = openssl_sign($plainText, $sign, $resource);
            openssl_free_key($resource);

            if (!$result) {
                throw new \Exception('签名出错' . $plainText);
            }

            return base64_encode($sign);
        } catch (\Exception $e) {
            throw $e;
        }
    }
	
	public function  get_bank_name($card_number){
		if ($res = $this->bankInfo($card_number, $this->bankList)) {
			$resArr = explode('-',$res);
			return $resArr[0];
		} else {
			return '';
		}
	}

	function bankInfo($card, $bankList){
		$card_8 = substr($card, 0, 8);
		if (isset($bankList[$card_8])) {
			return $bankList[$card_8];
		}
		$card_6 = substr($card, 0, 6);
		if (isset($bankList[$card_6])) {
			return $bankList[$card_6];

		}
		$card_5 = substr($card, 0, 5);
		if (isset($bankList[$card_5])) {
			return $bankList[$card_5];

		}
		$card_4 = substr($card, 0, 4);
		if (isset($bankList[$card_4])) {
			return $bankList[$card_4];

		}

		return null;
	}
}
?>