<?php
/*渠道二维码列表
 *s2016年2月14日13:27:01
 */

	class ChanelListAction extends BaseAction{
		public function index(){
			$where['type_id'] = $this->merchant_session['mer_id'];
			$chanel_list = D('Chanel_msg_list')->where($where)->select();
			$this->assign('chanel_list',$chanel_list);
			$this->display();
		}

		public function add(){
			if(IS_POST){
				if(empty($_POST['Full_title'])){
					$this->error('大标题不能为空');
				}
				$data['title']=$_POST['Full_title'];
				$data['add_time']=$data['last_time']=time();
				$data['status']=0;
				$data['type_id']= $this->merchant_session['mer_id'];
				$fid = D('Chanel_msg_list')->add($data);
				foreach($_POST[title] as $key=>$v){
					if(empty($v)||empty($_POST['img'][$key])||empty($_POST['des'][$key])||empty($_POST['url'][$key])){
						D('Chanel_msg_list')->where(array('chanel_id'=>$fid))->delete();
						$this->error('数据不能为空,内容保存失败');exit;
					}
					$data_content[]=array('fid'=>$fid,'title'=>$v,'img'=>$_POST['img'][$key],'des'=>$_POST['des'][$key],'url'=>html_entity_decode($_POST['url'][$key]));
				}
				if(!D('Chanel_msg_content')->addAll($data_content)){
					$this->error("保存失败");
				}else{
					$this->success("保存成功");
				}
			}else{
				$this->display();
			}
		}

		public function edit(){
			if(IS_POST){
				if(empty($_POST['Full_title'])){
					$this->error('大标题不能为空');
				}
				$data['title']=$_POST['Full_title'];
				$data['last_time']=time();
				D('Chanel_msg_list')->where(array('chanel_id'=>$_POST['chanel_id']))->save($data);
				$flag = false;
				foreach($_POST[title] as $key=>$v){
					if(empty($v)||empty($_POST['img'][$key])||empty($_POST['des'][$key])||empty($_POST['url'][$key])){
						$this->error('数据不能为空');exit;
					}
					if(empty($_POST['id'][$key])){
						$data_content = array('fid'=>$_POST['chanel_id'],'title'=>$v,'img'=>$_POST['img'][$key],'des'=>$_POST['des'][$key],'url'=>html_entity_decode($_POST['url'][$key]));
						D('Chanel_msg_content')->add($data_content);
						$flag = true;
					}else{
						$data_content = array('id'=>$_POST['id'][$key],'title'=>$v,'img'=>$_POST['img'][$key],'des'=>$_POST['des'][$key],'url'=>html_entity_decode($_POST['url'][$key]));
						if(!$res=D('Chanel_msg_content')->where('id='.$_POST['id'][$key])->save($data_content)){
							if(!$flag){
								$flag=false;
							}
						}else{
							$flag=true;
						}
					}
				}
				if(!$flag){
					$this->error('编辑失败');
				}else{
					$this->success('编辑成功');
				}
			}else{
				$chanel_id=$_GET['chanel_id'];
				$Full_title =  D('Chanel_msg_list')->where(array('chanel_id'=>$chanel_id))->getField('title');
				$this->assign('Full_title',$Full_title);
				$chanel_content = D('Chanel_msg_content')->where(array('fid'=>$chanel_id))->select();
				$this->assign('chanel_content',$chanel_content);
				$this->display('add');
			}
		}

		public function del(){
			if(IS_GET){
				if(!empty($_GET['delete_content'])){
					if(D('Chanel_msg_content')->where(array('id'=>$_GET['delete_content']))->delete()){
						$this->success("删除成功");exit;
					}else{
						$this->error("删除失败");
					}
				}
				if(!D('Chanel_msg_list')->where(array('chanel_id'=>$_GET['chanel_id']))->delete()||!D('Chanel_msg_content')->where('fid='.$_GET['chanel_id'])->delete()){
					$this->error("删除失败");
				}else{
					$this->success("删除成功");exit;
				}
			}
		}
	} 
?>