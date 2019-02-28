<?php
/*
 * 修改站点配置
 *
 */
class AccountDepositAction extends BaseAction {
    public function index(){
		redirect(U('Config/index').'&galias=AccountDeposit&header=AccountDeposit/header');
	}


}
