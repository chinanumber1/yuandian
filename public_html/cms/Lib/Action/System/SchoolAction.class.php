<?php
/*
 * 学校管理
 *
 * @  BuildTime  2016年7月29日 17:14:13
 */

class SchoolAction extends BaseAction{

    public function index(){
		$count = D('Fc_school')->count();
		import('@.ORG.system_page');
		$p = new Page($count,15);
		$schoolList = D('Fc_school')->limit($p->firstRow.','.$p->listRows)->select();
		// echo D('Fc_school')->getlastsql();
		$this->assign('schoolList',$schoolList);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
    	$this->display();
    }

    public function school_add(){
        $traitList = D('Fc_school_trait')->select();
        $this->assign('traitList',$traitList);
        $this->display();
    }

    public function school_add_data(){
        
        $long_lat = explode(',',$_POST['long_lat']);
        $data['long'] = $long_lat[0];
        $data['lat'] = $long_lat[1];
        $data['school_name'] = $_POST['school_name'];
        $data['province_id'] = $_POST['province_id'];
        $data['city_id'] = $_POST['city_id'];
        $data['address'] = $_POST['address'];
        $data['phone'] = $_POST['phone'];
        $data['recruit'] = $_POST['recruit'];
        $data['introduce'] = $_POST['introduce'];
        $data['scope'] = $_POST['scope'];
        $data['term'] = $_POST['term'];
        $data['describe'] = $_POST['describe'];
        $data['school_cat'] = $_POST['school_cat'];
        $data['school_type'] = $_POST['school_type'];
        $data['list_img'] = $_POST['list_img'];
        $data['area_id'] = $_POST['area_id'];
        $data['last_time'] = time();

        $ret = D('Fc_school')->data($data)->add();

        if($ret){

            foreach ($_POST['trait'] as $k => $v) {
                D('Fc_school_trait_middle')->data(array('school_id'=>$ret,'trait_id'=>$v))->add();
            }

            $this->frame_submit_tips(1,'添加成功');
        }else{
            $this->frame_submit_tips(0,'添加失败！请重试~');
        }

    }



    public function school_save(){
        $schoolInfo = D('Fc_school')->where(array('school_id'=>$_GET['school_id']))->find();
        $this->assign('schoolInfo',$schoolInfo);

        $relationshipList = D('Fc_school_trait_middle')->where(array('school_id'=>$schoolInfo['school_id']))->select();

        $traitList = D('Fc_school_trait')->select();

        foreach ($traitList as $k => $v) {

            foreach ($relationshipList as $key => $value) {
                if($v['trait_id'] == $value['trait_id']){
                    $traitList[$k]['checked'] = "checked='checked'";
                }
            }
            
        }

        $this->assign('traitList',$traitList);

        $this->display();
    }

    public function school_save_data(){

        $long_lat = explode(',',$_POST['long_lat']);
        $data['long'] = $long_lat[0];
        $data['lat'] = $long_lat[1];
        $data['school_name'] = $_POST['school_name'];
        $data['province_id'] = $_POST['province_id'];
        $data['city_id'] = $_POST['city_id'];
        $data['address'] = $_POST['address'];
        $data['phone'] = $_POST['phone'];
        $data['recruit'] = $_POST['recruit'];
        $data['introduce'] = $_POST['introduce'];
        $data['scope'] = $_POST['scope'];
        $data['term'] = $_POST['term'];
        $data['describe'] = $_POST['describe'];
        $data['school_cat'] = $_POST['school_cat'];
        $data['school_type'] = $_POST['school_type'];
        $data['list_img'] = $_POST['list_img'];
        $data['area_id'] = $_POST['area_id'];
        $data['last_time'] = time();

        $ret = D('Fc_school')->where(array('school_id'=>$_POST['school_id']))->data($data)->save();
        D('Fc_school_trait_middle')->where(array('school_id'=>$_POST['school_id']))->delete();

        if($ret){

            foreach ($_POST['trait'] as $k => $v) {
                D('Fc_school_trait_middle')->data(array('school_id'=>$_POST['school_id'],'trait_id'=>$v))->add();
            }

            $this->frame_submit_tips(1,'修改成功');
        }else{
            $this->frame_submit_tips(0,'修改失败！请重试~');
        }

    }

    public function school_del(){
        $_POST['school_id'];
        if(D('Fc_school')->where(array('school_id'=>$_POST['school_id']))->delete()){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }


    public function school_counterpart(){
        
        $schoolInfo = D('Fc_school')->where(array('school_id'=>$_GET['school_id']))->find();
        $this->assign('schoolInfo',$schoolInfo);

        $counterpartList = D('')->table(array(C('DB_PREFIX').'fc_school_counterpart'=>'sc', C('DB_PREFIX').'fc_school'=>'s'))->where("sc.school_id= '".intval($_GET['school_id'])."' AND sc.counterpart_id = s.school_id")->select();
        $this->assign('counterpartList',$counterpartList);

        $this->display();
    }

    public function school_counterpart_save(){
        $count = count($_POST['counterpart']);
        D('Fc_school_counterpart')->where(array('school_id'=>$_POST['school_id']))->delete();
        D('Fc_school_counterpart')->where(array('counterpart_id'=>$_POST['school_id']))->delete();
        $i = 0;
        foreach ($_POST['counterpart'] as $key => $value) {
            if(D('Fc_school_counterpart')->data(array('school_id'=>$_POST['school_id'],'counterpart_id'=>$value))->add()){
                if(D('Fc_school_counterpart')->data(array('school_id'=>$value,'counterpart_id'=>$_POST['school_id']))->add()){
                    $i++;
                }
            }
        }
  
        if($count == $i){
            $this->frame_submit_tips(1,'修改成功');
        }else{
            $this->frame_submit_tips(0,'修改失败！请重试~');
        }

        // dump($_POST);
    }


    public function school_data(){
        $schoolList = D('Fc_school')->where(array('school_id'=>array('neq',$_POST['school_id']),'school_name'=>array('like','%'.$_POST['name'].'%')))->select();
        
        $html.="<option value='xz'>== 请选择学校 ==</option>";

        if($schoolList){
            foreach ($schoolList as $k => $v) {
                $html.="<option value='".$v['school_id']."'>".$v['school_name']."</option>";
            }
        }
            
        exit(json_encode(array('data'=>$html)));
    }


    public function apiSchool(){
        $schoolList = D('Fc_school')->where(array('school_name'=>array('like','%'.$_POST['name'].'%')))->select();
    
        $html.="<option value='xz'>== 请选择学校 ==</option>";
    
        if($schoolList){
            foreach ($schoolList as $k => $v) {
                $html.="<option value='".$v['school_id']."'>".D('Area')->name($v['city_id']) .' ' .$v['school_name']."</option>";
            }
        }
    
        exit(json_encode(array('data'=>$html)));
    }



    
}