<?php
/*
 * 景区评论
 *   Writers    hanlu
 *   BuildTime  2016/07/06 16:00
 */

class Scenic_replyAction extends BaseAction{
	//景区评论
    public function index(){
        $database_reply = M('Scenic_reply');
		$condition_scenic['scenic_id'] = $this->merchant_session['scenic_id'];
		$count_store = $database_reply->where($condition_scenic)->count();
    	$p = new Page($count_store,15);
		$now_scenic = $database_reply->field(true)->where($condition_scenic)->limit($p->firstRow.','.$p->listRows)->order('reply_id DESC')->select();
		$now_project = M('Scenic_project')->field(array('project_id','project_title'))->where($condition_scenic)->select();
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('reply_list',$now_scenic);
		$this->assign('store_list',$now_project);
		$this->display();
    }
    //景区评论详情
    public function detail(){
        $where['scenic_id'] = $this->merchant_session['scenic_id'];
        $where['reply_id'] = $_GET['reply_id'] + 0;
        $reply_detail = M('Scenic_reply')->where($where)->find();
        if(!empty($reply_detail['reply_pic'])){
			$store_image_class = new scenic_image();
			$tmp_pic_arr = explode(';',$reply_detail['reply_pic']);
			foreach($tmp_pic_arr as $key=>$value){
				$pic[$key]['title'] = $value;
				$pic[$key]['url'] = $store_image_class->get_image_by_path($value,$this->config['site_url'],'project','1');
			}
		}
        if(empty($reply_detail)){
            $this->error('该评论不存在！');
        }
        if(IS_POST){
            $database_reply = M('Scenic_reply');
            $condition_reply['reply_id'] = $reply_detail['reply_id'];
            $data_reply['replys_content'] = $_POST['replys_content'];
            $data_reply['replys_time'] = $_SERVER['REQUEST_TIME'];
            $data_reply['status'] = $_POST['status'];
			$save	=	$database_reply->where($condition_reply)->data($data_reply)->save();
            if($save){
                $this->success('回复成功！');
            }else{
                $this->error('回复失败！请重试。');
            }
        }else{
        	$this->assign('reply_detail',$reply_detail);
        	$this->assign('pic',$pic);
            $this->display();
        }
    }
}