<?php
/*
 * 公司管理
 *
 * @  BuildTime  2016年8月1日 17:04:51
 */

class CompanyAction extends BaseAction{

    public function index(){
		$count = D('Fc_company')->where(array('is_del'=>0))->count();
		import('@.ORG.system_page');
		$p = new Page($count,15);
		$companyList = D('Fc_company')->where(array('is_del'=>0))->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('companyList',$companyList);
        // dump($companyList);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
    	$this->display();
    }


    public function company_save(){
        $companyInfo = D('Fc_company')->where(array('company_id'=>$_GET['company_id']))->find();
        $this->assign('companyInfo',$companyInfo);
        $this->display();
    }

    public function company_save_data(){
        $data['company_id'] = $_POST['company_id'];
        $data['name'] = $_POST['name'];
        $data['account'] = $_POST['account'];
        if($_POST['pwd']){
            $data['pwd'] = md5($_POST['pwd']);
        }
        $data['phone'] = $_POST['phone'];
        $data['status'] = $_POST['status'];
        $ret = D('Fc_company')->where(array('company_id'=>intval($_POST['company_id'])))->data($data)->save();

        if($ret){
            $this->frame_submit_tips(1,'修改成功！');
        }else{
            $this->frame_submit_tips(0,'修改失败！请重试~');
        }

    }

    public function company_del(){
        if(D('Fc_company')->where(array('company_id'=>$_POST['company_id']))->data(array('is_del'=>1))->save()){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }

}