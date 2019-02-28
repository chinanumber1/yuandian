<?php
/*
 * 关于我们
 *
 */
class AppintroAction extends BaseAction {

    public function index(){
        $activity_arr = array();
        $intro = D('Appintro');
        if($intro->count()){
            $intro_info = $intro->select();
            foreach($intro_info as $v){
                $activity_arr[] = array(
                    'title'=>$v['title'],
                    'url'=>$this->config['site_url'] .'/wap.php?g=Wap&c=Appintro&a=intro&id='.$v['id']
                );
            }
        }else{
            $activity_arr  = array();
        }
        $this->returnCode(0,$activity_arr);
    }

}