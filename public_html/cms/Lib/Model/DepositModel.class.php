<?php
class DepositModel extends Model{
    public function get_merchant_info($user_id=0,$user_type=1,$deposit_type='allinyun'){
        if($deposit_type=='allinyun'){
            if($user_type=1){
                return $this->allinyun_merchant($user_id);
            }
        }
    }

    public function allinyun_merchant($mer_id){
        $allinyun =  M('Merchant_allinyun_info')->where(array('mer_id'=>$mer_id))->find();
        $company_info = M('Merchant_deposit_info')->where(array('mer_id'=>$mer_id))->find();
        $allinyun['company_info'] = $company_info;
        if($allinyun['status']!=1 && $allinyun['bizUserId']!=''&& $allinyun['type']!=3){
            import('@.ORG.AccountDeposit.AccountDeposit');
            $deposit = new AccountDeposit('Allinyun');
            $allyun = $deposit->getDeposit();
            $allyun->setUser($allinyun);
            $allinyun_user = $allyun->getMemberInfo();
            $allinyun['MemberInfo'] = $allinyun_user;
            if( $allinyun_user['signedResult']['memberInfo']['status']==2){
                M('Merchant_allinyun_info')->where(array('mer_id'=>$mer_id))->setField('status',1);
                $allinyun['status'] = 1;
            }
        }

        return $allinyun;

    }

    public function allinyun_user($uid){
        $allinyun =  M('User_allinyun_info')->where(array('uid'=>$uid))->find();
//        import('@.ORG.AccountDeposit.AccountDeposit');
//        $deposit = new AccountDeposit('Allinyun');
//        $allyun = $deposit->getDeposit();
//        $allyun->setUser($allinyun);
//        if($allinyun['status']!=1){
//            $allinyun_user = $allyun->getMemberInfo();
//            $allinyun['status'] = 1;
//
//        }
        return $allinyun;

    }

    public function auto_reg_allinyun($uid){
        if($user_allinyun = M('User_allinyun_info')->where(array('uid'=>$uid))->find() && $user_allinyun['bizUserId']!=''){
            fdump($this->user_session['uid'],'user',1);
            return ;
        }
        import('@.ORG.AccountDeposit.AccountDeposit');
        $deposit = new AccountDeposit('Allinyun');
        $allyun = $deposit->getDeposit();
        $member = array(
            'bizUserId'=>C('config.allinyun_user_prefix').'_'.sprintf("%010d",$uid),
            'memberType'=>3,
            'source'=>1,
        );


        $allyun->setUser($member);
        $allinyun_user = $allyun->getMemberInfo();

        if($allinyun_user['status']=='OK' ){

            $data['uid'] = $uid;
            $data['bizUserId'] =  $member['bizUserId'];
            $data['userId'] =  $allinyun_user['signedResult']['memberInfo']['userId'];
            $data['status'] = 1; //未审核
            M('User_allinyun_info')->add($data);
            // $this->success_tips('创建云商通账号成功',U('index'));
        }else{

            $res = $allyun->createMember($member);

            if($res['status']=='error'){
                $this->error_tips($res['message']);
            }else{
                $data['uid'] = $uid;
                $data['bizUserId'] =  $res['signedResult']['bizUserId'];
                $data['userId'] =  $res['signedResult']['userId'];
                $data['status'] = 1; //未审核
                M('User_allinyun_info')->add($data);

                // $this->success_tips('创建云商通账号成功',U('bindphone'));
            }
        }
    }
}