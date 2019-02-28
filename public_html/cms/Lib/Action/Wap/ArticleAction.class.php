<?php
/*
 * 微信图文的文章页
 *
 */
class ArticleAction extends BaseAction{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		if (isset($_GET['imid'])) {
			$id = isset($_GET['imid']) ? intval($_GET['imid']) : 0;
			$image_text = D('Image_text')->where(array('pigcms_id' => $id))->find();
			D('Image_text')->where(array('pigcms_id' => $id))->save(array('read_quantity'=>$image_text['read_quantity']+1));
			$mer_name = M('Merchant')->where(array('mer_id'=>$image_text['mer_id']))->getField('name');
			$image_text['mer_name'] = $mer_name;
			$image_text['now'] = date('Y-m-d', $image_text['dateline']);
			$this->assign('url', U('Article/index', array('imid' => $image_text['pigcms_id'])));
			//$source = D('Source_material')->where(array('pigcms_id' => $_GET['lid']))->find();
		
		

			if($_SESSION['openid'] && isset($_GET['lid'])){
				
				$logid = intval($_GET['lid']);
				$openid = $_SESSION['openid'];
				$log = D('Send_user')->where(array('log_id' => $logid,'openid'=>$openid,'status'=>1))->find();
				if (empty($log)  && !isset($_GET['preview']) )return;
				
				if($_GET['preview']){
					if($log['sendtime']<time() && $log['sendtime']>0){
						$image_text['preview'] = false;
					}else{
						$image_text['preview'] = true;
					}
					
				}else{
					$image_text['preview'] = false;
				}
				 
				$log['is_read'] = intval($log['is_read']);
				if($log['is_read'] == 0){
					$user = D('User')->get_user($openid,'openid');
					if($user){
						D('User')->add_score($user['uid'],$this->config['customer_one_score'],'群发消息 粉丝查看消息后获得'.$this->config['score_name'].'');
						
						D('Send_user')->where(array('log_id' => $logid,'openid'=>$openid))->save(array('is_read'=>1));
					}
				}
			}
			if($image_text['location']){
				redirect(htmlspecialchars_decode($image_text['url']));
			}
		} elseif (isset($_GET['sid'])) {
			$id = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
			$image_text = D('Platform')->where(array('id' => $id))->find();
			$image_text['cover_pic'] = $this->config['site_url'] . $image_text['pic'];
			$image_text['now'] = date('Y-m-d');
			$this->assign('url', U('Article/index', array('sid' => $image_text['id'])));
		}
		isset($image_text['content']) && !empty($image_text['content']) && isset($_GET['sid']) && $image_text['content']=nl2br($image_text['content']);
		$this->assign('nowImage', $image_text);
		$this->display();
	}
	public function hdetail()
	{
		if (isset($_GET['imid'])) {
			$id = isset($_GET['imid']) ? intval($_GET['imid']) : 0;
			$image_text = D('House_image_text')->where(array('pigcms_id' => $id))->find();
			D('House_image_text')->where(array('pigcms_id' => $id))->save(array('read_quantity'=>$image_text['read_quantity']+1));
			$mer_name = M('Merchant')->where(array('mer_id'=>$image_text['mer_id']))->getField('name');
			$image_text['mer_name'] = $mer_name;
			$image_text['now'] = date('Y-m-d');
			$this->assign('url', U('Article/hdetail', array('imid' => $image_text['pigcms_id'])));
			
			if($_SESSION['openid'] && isset($_GET['lid'])){
				
				$logid = intval($_GET['lid']);
				$openid = $_SESSION['openid'];
				$log = D('Send_user')->where(array('log_id' => $logid,'openid'=>$openid,'status'=>1))->find();
				if (empty($log))return;
				
				$log['is_read'] = intval($log['is_read']);
				if($log['is_read'] == 0){
					$user = D('User')->get_user($openid,'openid');
					if($user){
						D('User')->add_score($user['uid'],$this->config['customer_one_score'],'群发消息 粉丝查看消息后获得'.$this->config['score_name'].'');
						D('Send_user')->where(array('log_id' => $logid,'openid'=>$openid))->save(array('is_read'=>1));
					}
				}
			}
		} elseif (isset($_GET['sid'])) {
			$id = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
			$image_text = D('Platform')->where(array('id' => $id))->find();
			$image_text['cover_pic'] = $this->config['site_url'] . $image_text['pic'];
			$image_text['now'] = date('Y-m-d');
			$this->assign('url', U('Article/hdetail', array('sid' => $image_text['id'])));
		}
		isset($image_text['content']) && !empty($image_text['content']) && $image_text['content']=htmlspecialchars_decode($image_text['content'],ENT_QUOTES);
		$this->assign('nowImage', $image_text);
		$this->display('index');
	}

	public function article_list(){
		$source = D('Source_material')->where(array('pigcms_id' => $_GET['id']))->find();

		if (empty($source)) $this->error_tips('文章不存在');
		$ids = unserialize($source['it_ids']);
		$image_text = D('Image_text')->field(true)->where(array('pigcms_id' => array('in', $ids)))->select();
		$result = array();
		foreach ($image_text as $txt) {
			$result[$txt['pigcms_id']] = $txt;
		}
		$image_text = array();
		foreach ($ids as $id) {
			$image_text[] = isset($result[$id]) ? $result[$id] : array();
		}

		$this->assign('list',$image_text);
		$this->display();
	}
}
?>