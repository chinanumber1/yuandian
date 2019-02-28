<?php
/*
 * 循环消息 goi
 * */
class Scroll_msgModel extends Model
{
	public function add_msg($type,$fid,$content){
		if(C('config.show_scroll_msg')) {
			$date['type'] = $type;
			$date['fid'] = $fid;
			$date['content'] = $content;
			$date['add_time'] = $_SERVER['REQUEST_TIME'];
			$this->add($date);
		}
	}

	public function get_msg(){
		$msg_list = S('scroll_msg');
		if(empty($msg_list)){
			$msg_list = $this->order('add_time DESC')->limit(10)->select();
			S('scroll_msg',$msg_list,300);
		}
		return $msg_list;
	}


}
?>