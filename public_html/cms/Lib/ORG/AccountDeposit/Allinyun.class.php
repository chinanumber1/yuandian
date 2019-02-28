<?php
/*
 * 通联云
 */
require 'Allinyun\SOAClient.php';
require_once 'Allinyun\RSAUtil.php';


class Allinyun {
    private $client;
    private $source;
    private $publicKey;
    private $privateKey;
    private $signContractUrl;
    private $bizUserId;
    private $setPayPwdUrl;
    private $updatePayPwdUrl;
    private $accountSetNo;  //托管账户集
    private $PayUrl;
    private $backUrl;
    public function __construct()
    {

        //参数要重新配置
        // $serverAddress = "http://122.227.225.142:23661/service/soa";
        // $this->signContractUrl = 'http://122.227.225.142:23661/yungateway/member/signContract.html';
        // $this->setPayPwdUrl = 'http://122.227.225.142:23661/pwd/setPayPwd.html';
        // $this->updatePayPwdUrl = 'http://122.227.225.142:23661/pwd/updatePayPwd.html';
        // $this->resetPayPwdUrl = 'http://122.227.225.142:23661/pwd/resetPayPwd.html';
        // $this->PayUrl = 'http://122.227.225.142:23661/service/gateway/frontTrans.do';

        $serverAddress = "https://yun.allinpay.com/service/soa";
        $this->signContractUrl = 'https://yun.allinpay.com/yungateway/member/signContract.html';
        $this->setPayPwdUrl = 'https://yun.allinpay.com/yungateway/pwd/setPayPwd.html';
        $this->updatePayPwdUrl = 'https://yun.allinpay.com/yungateway/pwd/updatePayPwd.html';
        $this->resetPayPwdUrl = 'https://yun.allinpay.com/yungateway/pwd/resetPayPwd.html';
        $this->PayUrl = 'https://yun.allinpay.com/yungateway/frontTrans.do';

        $this->backUrl = C('config.site_url').'/source/allinyun_notice.php';//C('config.site_url');
        $this->frontUrl = C('config.site_url').'/source/allinyun_back.php';//C('config.site_url');
        $this->wap_frontUrl = C('config.site_url').'/source/wap_allinyun_back.php';//C('config.site_url');
        $this->accountSetNo =C('config.accountSetNo');
        $this->client = new SOAClient();
        //$this->source = 1;

        $sysid =C('config.sysid');
        $alias = '100009000325';
        $privatePath = $_SERVER['DOCUMENT_ROOT'].C('config.allinyun_private_pem');
        $publicPath = $_SERVER['DOCUMENT_ROOT'].C('config.allinyun_public_pem');
        $pwd = C('config.allinyun_cert_pwd');
        $signMethod = 'SHA1WithRSA';

        $this->client->setServerAddress($serverAddress);
        $this->client->setAlias($alias);
        $this->client->setPwd($pwd);
        $this->client->setPrivatePath($privatePath);
        $this->client->setPublicPath($publicPath);
        $this->privateKey = RSAUtil::loadPrivateKey($alias, $privatePath, $pwd);
        $this->publicKey = RSAUtil::loadPublicKey($alias, $publicPath, $pwd);
        $this->client->setSignKey($this->privateKey);
        $this->client->setPublicKey($this->publicKey);
        $this->client->setSysId($sysid);
        $this->client->setSignMethod($signMethod);

    }

    //创建会员
    function createMember($params) {
        $param["bizUserId"] = $params['bizUserId'];
        $param["memberType"] = $params['memberType'];
        $param["source"] =  $params['source'];
        $result =  $this->client->request("MemberService", "createMember", $param);
        return $result;
    }

    //创建会员
    function setUser($params) {
        $this->bizUserId = $params['bizUserId'];
    }

    //实名认证
    public function setRealName($params){
        $param["bizUserId"] = $this->bizUserId;
        $param["name"] =$params['name'];// "夏睿明"
        $param["identityType"] = "1"; //目前只支持身份证
        $param["identityNo"] = $this->rsaEncrypt($params['identityNo'], $this->publicKey, $this->privateKey);//"640324199508162754"
        $result = $this->client->request("MemberService", "setRealName", $param);
        return $result;
    }
    /* 企业信息 */
    public function setCompanyInfo($params)
    {
        $param["bizUserId"] = $this->bizUserId;
        $param["backUrl"] = $this->backUrl;
        $param["companyBasicInfo"]['companyName'] = $params['companyName'];  //公司名称
        $param["companyBasicInfo"]['companyAddress'] = $params['companyAddress']?$params['companyAddress']:'';//0
        $param["companyBasicInfo"]['authType'] = $params['authType'];// 1 三证 2 一证
        $param["companyBasicInfo"]['uniCredit'] = $params['uniCredit'];// 统一社会信用（一证） 认证类型为 2 时必传
        $param["companyBasicInfo"]['businessLicense'] = $params['businessLicense'];// 0 营业执照
        $param["companyBasicInfo"]['organizationCode'] = $params['organizationCode'];// 1
        $param["companyBasicInfo"]['taxRegister '] = $params['taxRegister'];// 1
        $param["companyBasicInfo"]['expLicense'] = $params['expLicense'];  // 0 营业执照到期时间
        $param["companyBasicInfo"]['telephone'] = $params['telephone']?$params['telephone']:'';  // 0
        $param["companyBasicInfo"]['legalName'] = $params['legalName'];  // 0
        $param["companyBasicInfo"]['identityType'] = $params['identityType'];
        $param["companyBasicInfo"]['legalIds'] =  $this->rsaEncrypt($params['legalIds'], $this->publicKey, $this->privateKey);
        $param["companyBasicInfo"]['legalPhone'] = $params['legalPhone'];
        $param["companyBasicInfo"]['accountNo'] = $this->rsaEncrypt($params['accountNo'], $this->publicKey, $this->privateKey);
        $param["companyBasicInfo"]['parentBankName'] = $params['parentBankName']; //开户行银行名称
        $param["companyBasicInfo"]['bankCityNo'] = $params['bankCityNo']? $params['bankCityNo']:'';  //0
        $param["companyBasicInfo"]['bankName'] = $params['bankName']? $params['bankName']:'';  //0
        $param["companyBasicInfo"]['unionBank'] = $params['unionBank']? $params['unionBank']:'';  //0
        $param["companyBasicInfo"]['province'] = $params['province']? $params['province']:'';  //0
        $param["companyBasicInfo"]['city'] = $params['city']? $params['city']:'';  //0
        $param["isAuth"]= true;  //0


        $result = $this->client->request("MemberService", "setCompanyInfo", $param);
        return $result;
    }


    //电子签约
    public function signContract($is_user=true,$is_wap=true,$is_mer = false){
        $param["bizUserId"] = $this->bizUserId;
        $param["memberType"] = $is_user?"3":"2";
        $param["source"] = $is_wap?"1":"2";
        if($is_mer){

            $param["jumpUrl"] =C('config.site_url').'/merchant.php';
        }else{

            $param["jumpUrl"] =$is_wap?$this->wap_frontUrl:$this->frontUrl;
        }
        $param["backUrl"] =$this->backUrl;
        $req = $this->client->praseParam("MemberService", "signContract", $param);
        $url =$this->signContractUrl.'?'.http_build_query($req);
        return $url;
    }

    //短信验证 9绑定手机 6 解绑手机
    public function sendSMSCode($phone,$type=9){
        $param["bizUserId"] = $this->bizUserId;
        $param["phone"] = $phone;
        $param["verificationCodeType"] = $type;
        $result =  $this->client->request("MemberService", "sendVerificationCode", $param);
        return $result;
    }

    public function bindPhone($params){
        $param["bizUserId"] = $this->bizUserId;
        $param["phone"] = $params['phone'];
        $param["verificationCode"] = $params['code'];

        $result =  $this->client->request("MemberService", "bindPhone", $param);
        return $result;
    }

    public function changePhone($params){
        $param["bizUserId"] = $this->bizUserId;
        $param["oldPhone"] = $params['oldPhone'];
        $param["newPhone"] = $params['newPhone'];
        $param["newVerificationCode"] = $params['code'];
        $result =  $this->client->request("MemberService", "changeBindPhone", $param);
        return $result;
    }

    /* 申请绑定银行卡 */
    public function applayBindBankCard($params){
        $param["bizUserId"] = $this->bizUserId;
        $param["cardNo"] = $this->rsaEncrypt( $params['cardNo'],$this->publicKey,$this->privateKey);
        $param["phone"] = $params['phone'];
        $param["name"] = $params['name'];
        $param["cardCheck"] = $params['cardCheck']; // 1 三要素不用验证短信 2 默认验证短信 3 实名认证
        $param["identityType"] =1;
        $param["identityNo"] =$this->rsaEncrypt( $params['identityNo'],$this->publicKey,$this->privateKey);
        $param["unionCode"] && $param["unionBank"] =$param["unionCode"];
        //信用卡
        if($params['is_CreditCard']){
            $param["validate"] =$this->rsaEncrypt( $params['validate'],$this->publicKey,$this->privateKey);
            $param["cvv2"] =$this->rsaEncrypt( $params['cvv2'],$this->publicKey,$this->privateKey);
        }else if( isset($params['isSafeCard'])){
            //信用卡这个不能填
            $param["isSafeCard"] =$params['isSafeCard'];
        }
        unset($params['is_CreditCard']);
        $result =  $this->client->request("MemberService", "applyBindBankCard", $param);
        return $result;
    }

    /* 绑定银行卡 */
    public function bindBankCard($params){
        $param["bizUserId"] = $this->bizUserId;
        // $param["cardNo"] = $this->rsaEncrypt( $params['cardNo'],$this->publicKey,$this->privateKey);
        $param["tranceNum"] = $params['tranceNum'];
        $params['transDate'] && $param["transDate"] = $params['transDate'];
        $param["phone"] = $params['phone'];
        $param["verificationCode"] = $params['verificationCode'];

        $result =  $this->client->request("MemberService", "bindBankCard", $param);
        return $result;
    }

    /* 查询银行卡  */
    public function queryBankCard()
    {
        $param["bizUserId"] = $this->bizUserId;
        $result =  $this->client->request("MemberService", "queryBankCard", $param);
        return $result;
    }

    /* 解绑银行卡  */
    public function unbindBankCard($params)
    {
        $param["bizUserId"] = $this->bizUserId;
        $param["cardNo"] = $this->rsaEncrypt( $params['cardNo'],$this->publicKey,$this->privateKey);
        $result =  $this->client->request("MemberService", "unbindBankCard", $param);
        return $result;
    }
    /* 获取卡bin */
    public function getBankCardBin($params){
        $param["cardNo"] = $this->rsaEncrypt( $params['cardNo'],$this->publicKey,$this->privateKey);
        $result =  $this->client->request("MemberService", "getBankCardBin", $param);
        return $result;
    }
    /* 锁定会员 */
    public function lockMember($params)
    {
        $param["bizUserId"] = $this->bizUserId;
        $result =  $this->client->request("MemberService", "lockMember", $param);
        return $result;
    }

    /* 解锁会员 */
    public function unlockMember($params)
    {
        $param["bizUserId"] = $this->bizUserId;
        $result =  $this->client->request("MemberService", "unlockMember", $param);
        return $result;
    }

    //设置支付密码
    public function PayPWD($params){
        $param["bizUserId"] = $this->bizUserId;

        $param["phone"] = $params['phone'];
        $param["name"] = $params['realName'];
        $param["identityType"] =1;
        $param["identityNo"] =$this->rsaEncrypt( $params['identityNo'],$this->publicKey,$this->privateKey);

        $param["jumpUrl"] = $params['source']==1?C('config.site_url').'/source/allinyun_back.php':C('config.site_url').'/source/allinyun_wap_back.php';
        $param["backUrl"] = $this->backUrl;
        // $param["jumpUrl"] = urlencode($param["jumpUrl"]);
        // $param["backUrl"] = urlencode($param["backUrl"]);
        $action = $params['action'];

        if($action=='setPayPwd'){
            $req = $this->client->praseParam("MemberPwdService", 'setPayPwd', $param);

            $url = "req=".urlencode(urlencode($req['req']));
            $url .= "&sysid=".$req['sysid'];
            $url .= "&timestamp=".urlencode(urlencode($req['timestamp']));
            $url .= "&sign=".urlencode(urlencode($req['sign']));
            $url .= "&v=".$req['v'];
            $url =$this->setPayPwdUrl.'?'.$url;

            // $url =$this->setPayPwdUrl.'?'. http_build_query($req);
        }else if($action=='updatePayPwd'){

            $req = $this->client->praseParam("MemberPwdService", 'updatePayPwd', $param);
            $url = "req=".urlencode(urlencode($req['req']));
            $url .= "&sysid=".$req['sysid'];
            $url .= "&timestamp=".urlencode(urlencode($req['timestamp']));
            $url .= "&sign=".urlencode(urlencode($req['sign']));
            $url .= "&v=".$req['v'];
            $url =$this->updatePayPwdUrl.'?'.$url;

        }else if($action=='resetPayPwd'){

            $req = $this->client->praseParam("MemberPwdService", 'resetPayPwd', $param);
            $url = "req=".urlencode(urlencode($req['req']));
            $url .= "&sysid=".$req['sysid'];
            $url .= "&timestamp=".urlencode(urlencode($req['timestamp']));
            $url .= "&sign=".urlencode(urlencode($req['sign']));
            $url .= "&v=".$req['v'];
            $url =$this->resetPayPwdUrl.'?'.$url;
        }
        // fdump($url,'url');
        return $url;
    }
    /* 获取用户信息 */
    public function getMemberInfo()
    {
        $param['bizUserId'] = $this->bizUserId;
        $result =  $this->client->request("MemberService", "getMemberInfo", $param);
        return $result;
    }


    /* 充值申请 */
    public function rechargeApply($params)
    {
        $param['bizUserId'] = $this->bizUserId;
        $param['bizOrderNo'] =$params['order_id'];
        $param['accountSetNo'] = $this->accountSetNo;
        $param['amount'] = $params['money'];
        $param['frontUrl'] = $this->frontUrl;
        $param['fee'] = 0.00;
        $param['backUrl'] = $this->backUrl;
        if(isset($params['payMethod']['REALNAMEPAY'])){
            $params['payMethod']['REALNAMEPAY']['bankCardNo'] = $this->rsaEncrypt( $params['payMethod']['REALNAMEPAY']['bankCardNo'],$this->publicKey,$this->privateKey);
        }
        $param['payMethod'] = $params['payMethod'];
        $param['validateType'] = 0;
        $param['industryCode'] = '1910';
        $param['industryName'] = '其他';
        $param['source'] = $params['source'];
        $param['extendInfo'] = $params['extendInfo'];
        $result =  $this->client->request("OrderService", "depositApply", $param);
        return $result;
    }

    /* 提现申请 */
    public function withdrawApply($params)
    {
        $param_t["sysid"] = $this->client->getSysId();
        // $param_t["accountSetNo"] =$this->accountSetNo;
        $result = $this->client->request("MerchantService", 'queryReserveFundBalance', $param_t);

        if($result['signedResult']['balance']<=0){
            $result['status'] = 'error';
            $result['message'] = '通联头寸资金不足，请稍后再试';
            return $result;
        }
        $param['bizUserId'] = $this->bizUserId;
        $param['bizOrderNo'] =$params['order_id'];
        $param['accountSetNo'] = $this->accountSetNo;
        $param['amount'] = intval($params['money']);
        $param['fee'] = 0.00;
        $param['backUrl'] = $this->backUrl;
        $param['bankCardNo'] = $params['bankCardNo'];
        // $param['withdrawType'] = '';  默认及时到账T0
        if(!empty($params['extendInfo'])){
            $param['extendInfo'] = $params['extendInfo'];
        }

        if(isset($params['bankCardPro'])){
            $param['bankCardPro'] = $params['bankCardPro'];
        }
        $param['industryCode'] = '1910';
        $param['industryName'] = '其他';
        $param['source'] = 2;
        fdump($param,'pora');
        $result =  $this->client->request("OrderService", "withdrawApply", $param);
        return $result;
    }

    /* 消费申请 */
    public function consumeApply($params)
    {
        $param['payerId'] = $params['payerId'];      //付款方
        $param['recieverId'] =$params['recieverId']; //收款方
        $param['bizOrderNo'] =$params['order_id']; //bizOrderNo
        $param['amount'] = $params['money'];
        $param['fee'] = $params['fee'];
        $param['frontUrl'] = $this->frontUrl;
        $param['backUrl'] = $this->backUrl;

        // $param['withdrawType'] = '';  默认及时到账T0
        $param['payMethod'] = $params['payMethod'];
        $param['validateType'] = $params['validateType']?$params['validateType']:0;
        $param['industryCode'] = '1910';
        $param['industryName'] = '其他';
        $param['source'] = $params['source'];
        $param['summary'] = $params['desc'];
        $param['extendInfo'] = $params['extendInfo'];
        $result =  $this->client->request("OrderService", "consumeApply", $param);
        return $result;
    }

    public function refundApply($params)
    {

        $param_t["sysid"] = $this->client->getSysId();
        // $param_t["accountSetNo"] =$this->accountSetNo;
        $result = $this->client->request("MerchantService", 'queryReserveFundBalance', $param_t);

        if($result['signedResult']['balance']<=0){
            $result['status'] = 'error';
            $result['message'] = '通联头寸资金不足，请稍后再试';
            return $result;
        }

        $param['bizOrderNo'] = $params['oriBizOrderNo'];      //商户订单号
        $param['oriBizOrderNo'] =$params['bizOrderNo']; //商户原订单号
        $param['bizUserId'] = $this->bizUserId;
        //  $param['refundList'] = $params['refundList'];
        $param['backUrl'] = $this->backUrl;
        $param['amount'] = $params['amount'];
        $result =  $this->client->request("OrderService", "refund", $param);
        return $result;
    }


    /* 查询订单状态 */
    public function OrderDetail($params)
    {
        $param['bizUserId'] = $this->bizUserId;
        $param['bizOrderNo'] =$params['order_id'];
        $result =  $this->client->request("OrderService", "getOrderDetail", $param);
        return $result;
    }

    /* 确认支付，前台+密码 */

    public function confirmPay($params)
    {
        $param["bizUserId"] = $this->bizUserId;
        $param["bizOrderNo"] = $params['order_id'];
        $param["backUrl"] = $this->backUrl;
        $param["consumerIp"] = $params['consumerIp'];
        // $params['verificationCode'] && $param["verificationCode"] =$params['verificationCode'];
        $req = $this->client->praseParam("OrderService", 'Pay', $param);
        $url =$this->PayUrl.'?'.http_build_query($req);

        return $url;
    }

    /* 确认支付，后台+短信 */
    public function confirmPayBackSms($params)
    {
        $param["bizUserId"] = $this->bizUserId;
        $param["bizOrderNo"] = $params['order_id'];
        $param["backUrl"] = $this->backUrl;
        $param["consumerIp"] = $params['consumerIp'];
        $param["verificationCode"] =$params['verificationCode'];
        $result = $this->client->request("OrderService", 'pay', $param);
        return $result;
    }

    public function Pay($order_type='',$orderid='',$payment_money='',$order_info = array()){
        require_once 'Allinyun\allinyun_pay_method.php';
        $payment_money = intval($payment_money*100);
        $params['order_id'] = $order_type.'_'.$orderid;
        $params['money'] = $payment_money;
        $params['source'] = $order_info['source'];
        $params['fee'] = intval(D('Percent_rate')->get_percent_money($order_type,$payment_money,$order_info));

        switch($order_info['pay_type']){
            case 'gateway':

                $params['payMethod'] = array('GATEWAY'=>array(
                    'bankCode'=>$order_info['bank_code'],
                    'payType'=>1,
                    'amount'=>$payment_money,
                ));
                $params['extendInfo']='gateway,'.$order_info['platform'];
                break;
            case 'weixin':
                $params['payMethod'] = array('WECHAT_PUBLIC'=>array(
                    'acct'=>$order_info['openid'],
                    'payType'=>'',
                    'amount'=>$payment_money,
                ));
                $params['extendInfo']='weixin,'.$order_info['platform'];
                break;
            case 'balance':
                $params['payMethod'] = array('BALANCE'=>array(array(
                    'accountSetNo'=>$this->accountSetNo,
                    'amount'=>$payment_money,
                )));
                $params['validateType'] = 1;
                $params['extendInfo']='balance,'.$order_info['platform'];
                break;

            case 'scan_weixin':
                $params['payMethod'] = array(

                    'SCAN_WEIXIN'=>array(
                        'payType'=>'W01',
                        'amount'=>$payment_money,
                    )

                );
                $params['extendInfo']='weixin,'.$order_info['platform'];

                break;
            case 'alipay':
            case 'scan_alipay':
                $params['payMethod'] = array(

                    'SCAN_ALIPAY'=>array(
                        'payType'=>'A01',
                        'amount'=>$payment_money,
                    )

                );
                $params['extendInfo']='alipay,'.$order_info['platform'];
                break;

        }


        if($order_type=='recharge'){
            $res = $this->rechargeApply($params);

        }else{
            $params['desc'] = $order_info['order_name'];
            $params['payerId']  = $order_info['payerId'];
            $params['recieverId']  =$order_info['recieverId'];
            $res = $this->consumeApply($params);

        }

        if( $res['signedResult']['payInfo']){

            if($order_info['pay_type']=='scan_weixin' || $order_info['pay_type']=='scan_alipay' ||$order_info['pay_type']=='alipay' ){
                $res['signedResult']['backUrl']=C('config.site_url').'/index.php?c=Deposit&a=allinyun_back&order_id='.$params['order_id'].'&uid='.$order_info['uid'].($order_info['platform']=='APP'?'&source=2':'');
                if($order_info['platform']=='APP'){
                    return $res;
                }else if($order_info['platform']=='wap' && $order_info['pay_type']=='scan_alipay' ){
                    return $res;
                }else{

                    $returnArr = array(
                        'status'=>1,
                        'info'=>$res['signedResult']['payInfo'],
                        'orderid'=>$orderid,

                    );
                    header('Content-type: application/json');
                    // $this->header_json();
                    echo json_encode($returnArr);die;
                }

            }else{
                $redirctUrl = C('config.site_url') . '/index.php?c=Deposit&a=allinyun_back&order_id=' . $params['order_id'].'&amount='.$payment_money.'&source='.$order_info['source'];
                $arr['redirctUrl'] = $redirctUrl;
                $arr['pay_money'] = $payment_money/100;
                $arr['weixin_param'] = json_decode($res['signedResult']['payInfo']);
                $arr['error'] = 0;
                echo json_encode($arr);die;
            }
        }
        $pay_arr= array(
            'order_id'=>$params['order_id'],
            'consumerIp'=>'36.7.111.127',
        );

        if($order_info['pay_type']=='balance'){

            if($res['status']=='OK' && $params['validateType']){
                $url = U('setAccountDeposit/SmsPay',array('order_id'=>$pay_arr['order_id'],'payment_money'=>$payment_money));
                // if($order_info['platform']=='APP'){

                // $returnArr = array(
                // 'status'=>1,
                // 'info'=>$url ,
                // 'orderid'=>$orderid,
                // );
                // header('Content-type: application/json');
                // echo json_encode($returnArr);die;
                // }else{

                redirect($url);
                // }
            }else{
                $returnArr = array(
                    'status'=>0,
                    'message'=>$res['message'] ,
                    'orderid'=>$orderid,
                );
                return $returnArr;
                // if($order_info['platform']=='APP'){
                // header('Content-type: application/json');
                // echo json_encode($returnArr);die;
                // }
            }
            // $res['signedResult']['method'] = 'balance_pay';
            // $res['signedResult']['amount'] = $payment_money;
            // $res['signedResult']['status'] = 'OK';
            //redirect(C('config.site_url').'/source/allinyun_back.php?rps='.json_encode($res['signedResult']));
        }else if($res['status']=='OK'){
            $url = $this->confirmPay($pay_arr);
            redirect($url);
        }else{

        }

    }

    /*分账规则*/
    public function splitRule($params){
        //1级分佣
        $split[] =array(
            "amount"=> $params['first_money'],
            "fee"=> 0,
            "remark"=>$params['first_desc'],
            "bizUserId"=> $params['first_bizUserId'],
        );
        //2级分佣
        $split[] =array(
            "amount"=> $params['second_money'],
            "fee"=> 0,
            "remark"=> $params['second_desc'],
            "bizUserId"=> $params['second_bizUserId'],
        );
        //3级分佣
        $split[] =array(
            "amount"=> $params['third_money'],
            "fee"=> 0,
            "remark"=> $params['third_desc'],
            "bizUserId"=> $params['third_bizUserId'],
        );

        return $split;
    }


    /* 托管代收 */
    public function agentCollectApplySimplify ($params)
    {
        $param['payerId'] = $this->bizUserId;
        $param['bizOrderNo'] =$params['order_id'];
        $param['tradeCode'] =$params['tradeCode'];
        $param['amount'] =$params['money'];
        // $param['validateType'] =$params['validateType'];
        // $param['frontUrl'] =$params['frontUrl'];
        $param['backUrl'] =$this->backUrl;
        $param['payMethod'] =$params['payMethod'];
        $param['industryCode'] = '1910';
        $param['industryName'] = '其他';
        $param['source'] =2;

        $result =  $this->client->request("OrderService", "agentCollectApplySimplify", $param);
        return $result;
    }


    /* 托管代付  中间账户支付给卖方 */
    public function signalAgentPaySimplify($params)
    {
        $param['bizUserId '] = $this->bizUserId;
        $param['accountSetNo'] = $this->accountSetNo;
        $param['bizOrderNo'] =$params['order_id'];
        $param['amount'] =$params['amount'];
        $param['payToBankCardInfo'] =$params['payToBankCardInfo'];
        $param['splitRuleList'] =$params['splitRuleList'];
        $param['tradeCode'] =$params['tradeCode'];
        $result =  $this->client->request("OrderService", "signalAgentPaySimplify", $param);
        return $result;
    }
    //加密
    function rsaEncrypt($str) {
        $rsaUtil = new RSAUtil($this->publicKey,$this->privateKey);
        $encryptStr = $rsaUtil->encrypt($str);
        return $encryptStr;
    }

    //解密
    function rsaDecrypt($str) {
        $rsaUtil = new RSAUtil($this->publicKey,$this->privateKey);
        $encryptStr = $rsaUtil->decrypt($str);
        return $encryptStr;
    }

    public function getPayMethod(){
        require_once 'Allinyun\allinyun_pay_method.php';
        return $allinyun_config;
    }


    public function verify($sign,$signValue){
        return $this->client->verifySign($signValue,$sign);
    }

    public function sign($sysid, $req, $timestamp){
//        return $this->client->sign($sysid, $req, $timestamp);
    }


    /* 查询余额 */

    public function queryBalance()
    {
        $param["bizUserId"] = $this->bizUserId;
        $param["accountSetNo"] =$this->accountSetNo;
        $result = $this->client->request("OrderService", 'queryBalance', $param);

        return $result;
    }

    public function queryInExpDetail($start=1,$dateStart='',$dateEnd=''){
        $param["bizUserId"] = $this->bizUserId;
        $param["accountSetNo"] =$this->accountSetNo;
        $param["startPosition"] =$start;
        $param["queryNum"] =10;
        if($dateStart && $dateEnd){
            $param["dateStart"] =$dateStart;
            $param["dateEnd"] =$dateEnd;
        }

        $result = $this->client->request("OrderService", 'queryInExpDetail', $param);

        return $result;
    }

    /* 通联头寸  查询通联钱够不够 */
    public function queryReserveFundBalance()
    {
        $param["bizUserId"] = $this->bizUserId;
        $param["accountSetNo"] =$this->accountSetNo;
        $result = $this->client->request("MemberService", 'queryReserveFundBalance', $param);

    }

    /* 
     *企业相关
     */

    //企业审核结果查询
    public function companyResultverify()
    {
        # code...
    }

}

