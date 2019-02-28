<?php

/**
 * 托管账号商家设置
 * User: 李俊
 * Date: 2018年4月28日
 * Time: 14:36:21
 */
class SetAccountDepositAction extends BaseAction
{
    private $allinyun;
    public function index(){
        $now_money =  $this->getBalance();
        $this->assign('now_money',$now_money);
        $this->assign('deposit',$this->allinyun);
        $this->display();
    }
    public function createAllinyunAccount(){
        if(!$_GET['type'] && M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find()){
            $this->error('您已经创建了云商通账号，不能再次创建！');
        }
        import('@.ORG.AccountDeposit.AccountDeposit');
        $deposit = new AccountDeposit('Allinyun');
        $allyun = $deposit->getDeposit();
        $member = array(
            'bizUserId'=>C('config.allinyun_mer_prefix').'_'.sprintf("%0".($_GET['type']==2?9:10)."d",$this->merchant_session['mer_id']),
            'memberType'=>$_GET['type']?$_GET['type']:2,
            'source'=>2,
        );
        // $allyun->setUser($member);
        // $allinyun_mer = $allyun->getMemberInfo();

        $res = $allyun->createMember($member);


        if($res['status']=='error'){
            if($res['errorCode']=='30000'){
                $data['mer_id'] = $this->merchant_session['mer_id'];
                $data['bizUserId'] =  $member['bizUserId'];
                $data['status'] = $_GET['type']==3?1:0; //未审核
                $data['type'] =  $member['memberType']; //类型
                M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->delete();
                M('Merchant_allinyun_info')->add($data);
                $this->success('该企业账号已存在，已恢复相关数据');die;
            }
            $this->error($res['message']);
        }else{
            M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->delete();
            $data['mer_id'] = $this->merchant_session['mer_id'];
            $data['bizUserId'] =  $member['bizUserId'];
            $data['status'] = $_GET['type']==3?1:0; //未审核
            $data['type'] =  $member['memberType']; //类型

            M('Merchant_allinyun_info')->add($data);
            if($_GET['type']==2){
                $this->success('创建成功，请填写企业审核信息并提交审核',U('SubmitVerify'));
            }else{
                $this->success('创建成功',U('index'));
            }
        }
    }

    public function submitVerify(){
        if(IS_POST) {

            import('@.ORG.AccountDeposit.AccountDeposit');
            $deposit = new AccountDeposit('Allinyun');
            $allyun = $deposit->getDeposit();
            $mer_arr = array(
                'companyName' => $_POST['companyName'],
                'companyAddress' => $_POST['companyAddress'],
                'authType' => $_POST['authType'],
                'uniCredit' => $_POST['uniCredit'],
                'businessLicense' => $_POST['businessLicense'],
                'organizationCode' => $_POST['organizationCode'],
                'taxRegister' => $_POST['taxRegister'],
                'expLicense' => $_POST['expLicense'],
                'telephone' => $_POST['telephone'],
                'legalName' => $_POST['legalName'],
                'identityType' =>1,
                'legalIds' => $_POST['legalIds'],
                'legalPhone' => $_POST['legalPhone'],
                'accountNo' => $_POST['accountNo'],
                'parentBankName' => $_POST['parentBankName'],
                'bankCityNo' => $_POST['bankCityNo'],
                'bankName' => $_POST['bankName'],
                'unionBank' => $_POST['unionBank'],
                'province' => $_POST['province']?$_POST['province']:'',
                'city' => $_POST['city']?$_POST['city']:'',
            );
            $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find();
            $allyun->setUser($allinyun);

            $res = $allyun->setCompanyInfo($mer_arr);
            $mer_arr['mer_id'] = $this->merchant_session['mer_id'];
            $mer_arr['expLicense'] = strtotime($mer_arr['expLicense']);
            if(M('Merchant_deposit_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find()){
                M('Merchant_deposit_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->save($mer_arr);
            }else{
                M('Merchant_deposit_info')->add($mer_arr);
            }
            if($res['status']=='error'){
                $this->error($res['message']);
            }else{
                $this->success('提交成功等待审核！');
            }
        }else{
            $company = M('Merchant_deposit_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find();
            $deposit =D('Deposit')->get_merchant_info($this->merchant_session['mer_id']);
            $company['status'] = $deposit['status'];
            $this->assign('company',$company);
            $this->display();
        }
    }

    public function allyun(){
        import('@.ORG.AccountDeposit.AccountDeposit');
        $deposit = new AccountDeposit('Allinyun');
        $allyun = $deposit->getDeposit();
        $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find();
        $allyun->setUser($allinyun);
        if($allinyun['status']!=0){
            $balance = $allyun->queryBalance();
        }
        $allinyun['money'] = floatval($balance['signedResult']['allAmount']/100);
        $this->allinyun = $allinyun;
        // $this->register_check($allinyun);

        return $allyun;
    }

    public function getBalance(){
        $allyun = $this->allyun();
        $res = $allyun->queryBalance();

        return $res['signedResult']['allAmount']/100;
    }

    public function verify_real_name(){
        $allyun = $this->allyun();
        // $this->register_check($this->allinyun,'verify_real_name');
        if(IS_POST) {
            $res = $allyun->setRealName($_POST);
            fdump($res,'verify',1);
            if($res['status']=='error'){
                $this->error('实名认证失败！'.$res['message']);
            }else{
                $data['identityNo'] =$_POST['identityNo'];
                $data['realName'] = $_POST['name'];
                M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->save($data);
                $this->success('实名认证成功！',U('index'));
            }
        }else{
            $this->display();
        }
    }

    public function bindphone(){
        $allyun = $this->allyun();
        if(IS_POST){
            if(empty($_POST['phone'])){
                $this->error('手机为空');
            }
            if(empty($_POST['code'])){
                $this->error('验证码为空');
            }
            $res =  $allyun->bindPhone($_POST);
            if($res['status']=='error'){
                $this->error($res['message']);
            }else{
                $data['bind_phone_status'] = 1;
                $data['phone'] = $_POST['phone'];
                M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->save($data);
                $this->success('绑定成功！',U('index'));
            }

        }else{
            if($this->allinyun['status']!=1&&$this->allinyun['type']==2){
                $this->error('商家还未通过审核，请审核通过后再请求');
            }
            $this->display();
        }
    }
    public function editphone(){
        if(IS_POST){
            $allyun = $this->allyun();
            if(empty($_POST['phone'])){
                $this->error('手机为空');
            }
            if(empty($_POST['code'])){
                $this->error('验证码为空');
            }
            $data['oldPhone'] = $this->allinyun['phone'];
            $data['newPhone'] = $_POST['phone'];
            $data['code'] = $_POST['code'];
            $res =  $allyun->changePhone($data);
            if($res['status']=='error'){
                $this->error($res['message']);
            }else{
                $data['bind_phone_status'] = 1;
                $data['phone'] = $_POST['phone'];
                M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['uid']))->save($data);
                $this->success('重置成功！',U('index'));
            }

        }else{
            $this->display();
        }
    }


    public function register_check($allinyun){

        // if($allinyun['phone']==''){
        // $this->error('您还未绑定手机,请先绑定手机号码',U('bindPhone'));
        // }
        // if($allinyun['realName']==''){
        // $this->error('您还未实名认证,请实名认证',U('index'));
        // }
        // if($allinyun['signStatus']==0){
        // $this->error('您还进行会员电子签约',U('signConnect'));
        // }
    }

    public function signConnect(){
        if($_GET['sign']==1){
            $allyun = $this->allyun();

            if($this->allinyun['status']!=1&&$this->allinyun['type']==2){
                $this->error('商家还未通过审核，请审核通过后再请求');
            }
            // $allyun->setUser($deposit);
            if($this->allinyun['type']==2){

                $url = $allyun->signContract(false,false,true);
            }else{

                $url = $allyun->signContract(true,false,true);
            }
            redirect($url);
        }

    }

    public function sendsms(){
        $allyun = $this->allyun();
        $res = $allyun->sendSMSCode($_POST['phone']);
    }

    public function money_list(){
        $allyun = $this->allyun();
        $result = $allyun->queryInExpDetail($_GET['page']?($_GET['page']-1)*10+1:1,$_GET['begin_time'],$_GET['end_time']);
        $order_type  =$this->get_alias_c_name2();

        foreach ($result['signedResult']['inExpDetail'] as &$v) {
            $tmp = explode('_',$v['bizOrderNo']);
            $v['order_type']  =$order_type[$tmp[0]];
            $v['order_id']  =$tmp[1];
            $v['chgAmount']  =$v['chgAmount'];
            if($v['chgAmount']<0){
                $v['order_type']  ='退款';
                $v['order_id']=$v['bizOrderNo'];
            }
            $v['user_info'] = $this->get_orderinfo($tmp[0] ,$v['order_id']);

        }
        $count = $result['signedResult']['totalNum'];

        import('@.ORG.merchant_page');
        $p = new Page($count,10);
        //$p->nowPage = $_GET['page']?$_GET['page']:1;
        //$p->totalPage = ceil($count/10);
        $this->assign('list', $result['signedResult']['inExpDetail']);

        $this->assign('pagebar',$p->show());
        $this->display();
    }

    public function get_orderinfo($order_type,$orderid){
        if ($order_type == 'takeout' || $order_type == 'food' || $order_type == 'foodPad') {
            $order_table = 'Meal_order';
        }else if($order_type=='recharge'){
            $order_table = 'User_recharge_order';

        }else if($order_type=='balance-appoint'){
            $order_table = 'Appoint_order';
        } else{
            $order_table = ucfirst($order_type).'_order';
        }
        $order =  M($order_table);
        $tmp_orderid = M('Tmp_orderid');
        $res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
        $now_order = $order->where(array('order_id'=>$res['order_id']))->join('as o LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = o.uid')->field('o.order_id,u.uid,u.nickname,u.phone')->find();

        return $now_order;
    }

    public  function get_alias_c_name2(){
        return array(
            'all'=>'选择分类',
            'group'=>$this->config['group_alias_name'],
            'shop'=>$this->config['shop_alias_name'],
            'meal'=>$this->config['meal_alias_name'],
            'appoint'=>$this->config['appoint_alias_name'],
            'waimai'=>$this->config['waimai_alias_name'],
            'store'=>'到店',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现',
            'coupon'=>'提现',
            'withdraw'=>'提现',
            'activity'=>'平台活动',
            'spread'=>'商家推广佣金'
        );
    }

    public function addBank(){
        $allyun = $this->allyun();
        if($this->allinyun['type']==2){

            $deposit =D('Deposit')->get_merchant_info($this->merchant_session['mer_id']);
        }else{
            $deposit = $this->allinyun;
        }
        if($this->allinyun['status']!=1&&$this->allinyun['type']==2){
            $this->error('您的企业信息未审核成功，请审核成功后再添加');
        }
        $payMethod = $allyun->getPayMethod();
        // dump($deposit);
        $this->assign('payMethod',$payMethod);
        $this->assign('deposit',$deposit);
        $this->display();
    }


    public function apply_bind_card(){
        if(IS_POST) {
            $allyun = $this->allyun();
            $data['cardNo'] = $_POST['cardNo'];
            $data['phone'] = $_POST['phone'];
            $data['name'] = $_POST['realName'];
            $data['identityNo'] = $_POST['identityNo'];
            $data['cardCheck'] =2;
            $data['unionCode'] =$_POST['unionCode'];
            if ($_POST['is_CreditCard']) {
                $data['validate'] = $_POST['validate'];
                $data['cvv2'] = $_POST['cvv2'];
            }
            if ($_POST['isSafeCard']) {
                $data['isSafeCard'] = $_POST['isSafeCard'];
            }
            $res = $allyun->applayBindBankCard($data);

            if ($res['status'] == 'error') {
                $this->error('申请失败，'.$res['message']);
            } else {
                $data_companypay['from']     = 1;
                $data_companypay['type']     = 0;
                $data_companypay['pay_id']   = $this->allinyun['mer_id'];
                $data_companypay['account_name'] = $_POST['realName'];
                $data_companypay['account'] = $_POST['cardNo'];
                $data_companypay['is_default'] = 0;
                $data_companypay['add_time'] = $_SERVER['REQUEST_TIME'];
                $data_companypay['remark']    = $res['signedResult']['bankName'];
                $aid = M('User_withdraw_account_list')->add($data_companypay);
                $res['signedResult']['aid'] = $aid;
                $this->success('申请成功，短信验证码已发送', $res['signedResult']);
            }
        }else{
            $deposit =D('Deposit')->get_merchant_info($this->merchant_session['mer_id']);
            $this->assign('deposit',$deposit);
            $this->display();
        }
    }

    public function bind_card(){
        $allyun = $this->allyun();
        $data['tranceNum'] = $_POST['tranceNum'];
        $_POST['transDate'] && $data['transDate'] = $_POST['transDate'];
        $data['phone'] = $_POST['phone'];
        $data['verificationCode'] = $_POST['verificationCode'];
        $res = $allyun->bindBankCard($data);
        if($res['status']=='error'){
            $this->error('绑定失败'.','.$res['message']);
        }else{
            $data['bank_list'] = $this->allinyun['bind_bank_list']!=''?','.$_POST['aid']:$_POST['aid'];
            M('Merchant_allinyun_info')->where(array('mer_id'=>$this->allinyun['mer_id']))->save($data);
            $this->success('绑定成功',U('index'));
        }
    }

    public function withdraw_apply(){
        $allyun = $this->allyun();
        $deposit =D('Deposit')->get_merchant_info($this->merchant_session['mer_id']);
        // dump($deposit);
        $deposit['bank_txt']= substr($deposit['company_info']['accountNo']  ,0,4).'***'.substr($deposit['company_info']['accountNo']  ,-4);
        $this->assign('deposit',$deposit);
        if(IS_POST) {
            if($_POST['money']<$this->config['company_least_money']){
                $this->error('不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
            }
            $bank = M('User_withdraw_account_list')->where(array('id'=>$_POST['bank_id']))->find();
            if($this->config['merchant_withdraw_fee_type']==0){
                $fee     = intval(bcmul($_POST['money'] * (($this->config['company_pay_mer_percent']) / 100), 100));
            }else if($this->config['merchant_withdraw_fee_type']==1){
                $fee     = intval($this->config['company_pay_mer_money']*100);
            }

            if($fee>=$_POST['money']*100){
                $this->error('申请失败,提现金额过小，不足以抵扣手续费');
            }
            if($this->allinyun['type']==3 || $_POST['bank_id']!='duigong'){

                $withdraw = array(
                    'order_id'=>'withdraw_'.time(),
                    'money'=>$_POST['money']*100,
                    'bankCardNo'=>$allyun->rsaEncrypt($bank['account']),
                    'extendInfo'=>$_POST['extendInfo'],
                );

            }else{

                $withdraw = array(
                    'order_id'=>'withdraw_'.time(),
                    'money'=>$_POST['money']*100,
                    'bankCardNo'=>$allyun->rsaEncrypt($deposit['company_info']['accountNo']),
                    'extendInfo'=>$_POST['extendInfo'],
                    'bankCardPro'=>1,
                );
            }
            $res = $allyun->withdrawApply($withdraw);

            if ($res['status'] == 'error') {
                $this->error('申请失败'.','.$res['message']);
            } else {
                $this->success('申请成功，短信验证码已发送',$res['signedResult']);
            }
        }else{
            $where=array('pay_id'=>$this->merchant_session['mer_id'],'from'=>1);
            $where['id'] = array('in',$this->allinyun['bank_list']);
            $bank_list = M('User_withdraw_account_list')->where($where)->select();

            foreach ($bank_list as &$value) {
                $value['account'] =substr($value['account']  ,0,4).'***'.substr($value['account']  ,-4);
            }

            if($this->allinyun['type']==2){
                $bank_list[] = array(
                    'id'=>'duigong',
                    'account'=>substr($deposit['company_info']['accountNo']  ,0,4).'***'.substr($deposit['company_info']['accountNo']  ,-4),
                    'remark'=>$deposit['company_info']['parentBankName']."(对公账号)",

                );
            }
            $this->assign('now_money',$this->allinyun['money']);
            $this->assign('bank_list',$bank_list);
            $this->display();
        }
    }

    public function withdraw(){
        $allyun = $this->allyun();

        if(IS_POST) {

            $confirm =array(
                'order_id'=>$_POST['order_id'],
                'orderNo'=>$_POST['orderNo'],
                'consumerIp'=>'36.7.111.127',
                'verificationCode'=>$_POST['verificationCode'],
            );
            $res = $allyun->confirmPayBackSms($confirm);

            if ($res['status'] == 'error') {
                $this->error('提现失败'.','. $res['message']);
            } else {
                $this->success('提现成功', U('index'));
            }
        }else{
            $this->display();
        }
    }

}