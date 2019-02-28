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
    public function createAllinyun(){
        if(M('Merchant_allinyun_info')->where(array('mer_id'=>0))->find()){
            redirect(U('submitVerify'));
//            $this->error('您已经创建了云商通账号，不能再次创建！');
        }
        import('@.ORG.AccountDeposit.AccountDeposit');
        $deposit = new AccountDeposit('Allinyun');
        $allyun = $deposit->getDeposit();
        $member = array(
            'bizUserId'=>$this->system_session['account'].'_admin_'.rand(10,99),
            'memberType'=>2,
            'source'=>2,
        );

        $res = $allyun->createMember($member);

        if($res['status']=='error'){
            $this->error($res['message']);
        }else{
            $data['mer_id'] = 0;
            $data['bizUserId'] =  $member['bizUserId'];
            $data['status'] = 0; //未审核

            M('Merchant_allinyun_info')->add($data);

            $this->success('创建成功，请填写企业审核信息并提交审核',U('SubmitVerify'));
        }
    }

    public function submitVerify(){
       if(IS_POST) {


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
           );




           $mer_arr['mer_id'] = 0;
           $mer_arr['expLicense'] = strtotime($mer_arr['expLicense']);
           if(M('Merchant_deposit_info')->where(array('mer_id'=>0))->find()){
               M('Merchant_deposit_info')->where(array('mer_id'=>0))->save($mer_arr);
           }else{
               M('Merchant_deposit_info')->add($mer_arr);
           }

       }else{
           $company = M('Merchant_deposit_info')->where(array('mer_id'=>0))->find();
           $deposit =D('Deposit')->get_merchant_info(0);
           $company['status'] = $deposit['status'];
           $this->assign('company',$company);
           $this->assign('deposit',$deposit);
           $this->display();
       }
   }

    public function submitAllinyun(){
        $allyun = $this->allyun();
        $mer_arr = M('Merchant_deposit_info')->where(array('mer_id'=>0))->find();
        $res = $allyun->setCompanyInfo($mer_arr);
        if($res['status']=='error'){
            $this->error($res['message']);
        }else{
            $this->success('提交成功等待审核！');
        }
    }


    public function allyun(){
        import('@.ORG.AccountDeposit.AccountDeposit');
        $deposit = new AccountDeposit('Allinyun');
        $allyun = $deposit->getDeposit();
        $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>0))->find();
        $allyun->setUser($allinyun);
        $balance = $allyun->queryBalance();
        $allinyun['money'] = $balance['signedResult']['allAmount']/100;
        $this->allinyun = $allinyun;
        return $allyun;
    }

    public function getBalance(){
        $allyun = $this->allyun();
        $res = $allyun->queryBalance();

        return $res['signedResult']['allAmount']/100;
    }


    public function bindphone(){
        if(IS_POST){
            import('@.ORG.AccountDeposit.AccountDeposit');
            $deposit = new AccountDeposit('Allinyun');
            $allyun = $deposit->getDeposit();
            $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>0))->find();
            $allyun->setUser($allinyun);
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
                M('Merchant_allinyun_info')->where(array('mer_id'=>0))->save($data);
                $this->success('绑定成功！',U('Config/merchant'));
            }

        }else{
            $this->display();
        }
    }
    public function editphone(){
        if(IS_POST){
            import('@.ORG.AccountDeposit.AccountDeposit');
            $deposit = new AccountDeposit('Allinyun');
            $allyun = $deposit->getDeposit();
            $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>0))->find();
            $allyun->setUser($allinyun);
            if(empty($_POST['phone'])){
                $this->error('手机为空');
            }
            if(empty($_POST['code'])){
                $this->error('验证码为空');
            }
            $data['oldPhone'] = $allinyun['phone'];
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

    public function signConnect(){
        if($_GET['sign']==1){
            import('@.ORG.AccountDeposit.AccountDeposit');
            $deposit = new AccountDeposit('Allinyun');
            $allyun = $deposit->getDeposit();
            $allinyun = M('Merchant_allinyun_info')->where(array('mer_id' => 0))->find();
            $allyun->setUser($allinyun);
            $url = $allyun->signContract(false);
            redirect($url);
        }

    }

    public function sendsms(){
        import('@.ORG.AccountDeposit.AccountDeposit');
        $deposit = new AccountDeposit('Allinyun');
        $allyun = $deposit->getDeposit();
        $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>0))->find();
        $allyun->setUser($allinyun);
        $res = $allyun->sendSMSCode($_POST['phone']);
    }

    public function money_list(){
        $allyun = $this->allyun();
        $result = $allyun->queryInExpDetail($_GET['page']?($_GET['page']-1)*10+1:1,$_GET['begin_time'],$_GET['end_time']);

        $count = $result['signedResult']['totalNum'];

        import('@.ORG.system_page');
        $p = new Page($count,10);
        //$p->nowPage = $_GET['page']?$_GET['page']:1;
        //$p->totalPage = ceil($count/10);
        $this->assign('list', $result['signedResult']['inExpDetail']);

        $this->assign('pagebar',$p->show());
        $this->display();
    }

    public function setAllinyun(){
        $company = M('Merchant_deposit_info')->where(array('mer_id'=>0))->find();
        $deposit =D('Deposit')->get_merchant_info(0);
        $company['status'] = $deposit['status'];
        $this->assign('company',$company);
        $this->assign('deposit',$deposit);
        $this->display();
    }


    public function addBank(){
        $deposit =D('Deposit')->get_merchant_info(0);
        if($deposit['status']!=1){
            $this->error('您的企业信息未审核成功，请审核成功后再添加');
        }

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
            $data['cardCheck'] =3;
            if ($_POST['is_CreditCard']) {
                $data['validate'] = $_POST['validate'];
                $data['cvv2'] = $_POST['cvv2'];
            }
            if ($_POST['isSafeCard']) {
                $data['isSafeCard'] = $_POST['isSafeCard'];
            }
            $res = $allyun->applayBindBankCard($data);

            if ($res['status'] == 'error') {
                $this->error('申请失败', $res['message']);
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
            $deposit =D('Deposit')->get_merchant_info(0);
            $this->assign('deposit',$deposit);
            $this->display();
        }
    }

    public function bind_card(){
        $allyun = $this->allyun();
        $allinyun = $this->allinyun;
        $data['tranceNum'] = $_POST['tranceNum'];
        $_POST['transDate'] && $data['transDate'] = $_POST['transDate'];
        $data['phone'] = $_POST['phone'];
        $data['verificationCode'] = $_POST['verificationCode'];
        fdump($data);
        $res = $allyun->bindBankCard($data);
        if($res['status']=='error'){
            $this->frame_error_tips('绑定失败',$res['message']);
        }else{
            $data['bind_bank_list'] = $allinyun['bind_bank_list']!=''?','.$_POST['aid']:$_POST['aid'];
            M('Merchant_allinyun_info')->where(array('mer_id'=>0))->save($data);
            $this->success_tips('绑定成功',U('index'));
        }
    }

    public function withdraw_apply(){
        $allyun = $this->allyun();
        if(IS_POST) {

            $withdraw = array(
                'order_id'=>'withdraw_'.time(),
                'money'=>$_POST['money']*100,
                'bankCardNo'=>$allyun->rsaEncrypt('6228480402637874214'),
                'extendInfo'=>$_POST['extendInfo'],
            );
            $res = $allyun->withdrawApply($withdraw);

            if ($res['status'] == 'error') {
                $this->error('申请失败'.','.$res['message']);
            } else {
                $this->success('申请成功，短信验证码已发送',$res['signedResult']);
            }
        }else{

            $this->assign('now_money',$this->allinyun['money']);
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
                $this->success('提现成功', $res['signedResult']);
            }
        }else{
            $this->display();
        }
    }

}