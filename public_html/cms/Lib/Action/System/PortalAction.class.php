<?php
class PortalAction extends BaseAction {

	public function _initialize(){
		parent::_initialize();
	}

	public function index(){
		$this->display();
	}
	// 分类列表
	public function article_cat(){
		$fcid = intval($_GET['fcid']);
		$condition['fcid'] = $fcid;
		$catList = D('Portal_article_cat')->where($condition)->order('`cat_sort` DESC,`cid` ASC')->select();
		if ($fcid > 0) {
            $condition_now['cid'] = $fcid;
            $now_category = D('Portal_article_cat')->field(true)->where($condition_now)->find();
            $this->assign('now_category', $now_category);
        }
		$this->assign('catList',$catList);
		$this->assign('fcid', $fcid);
		$this->display();
	}
	// 增加分类
	public function article_cat_add(){

		$fcid = intval($_GET['fcid']);
        $this->assign('fcid', $fcid);
		if($_POST){
			$data['fcid'] = $_POST['fcid'];
			$data['cat_name'] = $_POST['cat_name'];
			$data['cat_url'] = $_POST['cat_url'];
			$data['cat_sort'] = $_POST['cat_sort'];
			$data['cat_status'] = $_POST['cat_status'];
			$res = D('Portal_article_cat')->data($data)->add();
			if($res){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}
		$this->display();
	}
	// 修改分类
	public function article_cat_edit(){
		$portal_article_cat = D('Portal_article_cat');
		if (IS_POST) {
            $datas = $_POST;
            if ($portal_article_cat->data($datas)->save()) {
                $this->frame_submit_tips(1,'编辑成功！');
            } else {
                $this->frame_submit_tips(0,'编辑失败！请重试~');
            }
            die;
        }
        $where['cid'] = intval($_GET['cid']);
        $now_category = $portal_article_cat->field(true)->where($where)->find();
        if (empty($now_category)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        $this->assign('now_category', $now_category);
		$this->display();
	}
	// 删除分类
	public function article_cat_del(){
		if (IS_POST) {
            $portal_article_cat = D('Portal_article_cat');
            $where['cid'] = intval($_POST['cid']);
            $now_category = $portal_article_cat->field(true)->where($where)->find();
            if ($portal_article_cat->where($where)->delete()) {
                    $portal_article_cat->where(array('fcid' => $now_category['cid']))->delete();
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
	}

	// ajax获取分类
	public function ajax_article_child_cates(){
		$pid = (int)$_GET['pid'];
		if($pid <= 0){
			exit(json_encode(array('code'=>0,'msg'=>array())));
		}
		$child_cates = D('Portal_article_cat')->where(array('fcid'=>$pid,'cat_status'=>1))->select();
		exit(json_encode(array('code'=>0,'msg'=>$child_cates)));
	}

	public function uplad_img(){
    	if($_FILES['file_img']['error'] != 4){
			$image = D('Image')->handle($_SESSION['user']['uid'], 'portal', 0, array('size' => 3), false);
			if (!$image['error']){
				if((int)$_GET['flag'] == 1){
					exit('<script type="text/javascript">parent.upload_logo_success("'.$image['url']['file_img'].'")</script>');
				}else{
					exit('<script type="text/javascript">parent.upload_success("'.$image['url']['file_img'].'")</script>');
				}
			}
		}else{
			exit('<script type="text/javascript">parent.upload_error("没有选择图片");</script>');
		}
    }

	// 编辑器图片上传
	public function ajax_upload_pic(){

		if($_FILES['imgFile']['error'] == 4){
			exit(json_encode(array('error'=>1,'message'=>'没有选择图片')));
		}

		$upload_file = D('Image')->handle($this->system_session['uid'], 'upload_image', 0, array('size' => 5), false);
		if ($upload_file['error']){
			exit(json_encode(array('error'=>1,'message'=>$upload_file['message'])));
		}

		exit(json_encode(array('error' => 0, 'url' => $upload_file['url']['imgFile'], 'title' => '图片')));
	}

	// 资讯列表
	public function article(){
		$count = D('Portal_article')->count();
        import('@.ORG.system_page');
        $p = new Page($count,20);
		$article_list = D('Portal_article')->order('status asc,dateline desc')->limit($p->firstRow.','.$p->listRows)->select();
        $pagebar = $p->show();

        // 资讯分类
        $article_cates = D('Portal_article_cat')->select();
		// 来源列表
		$source_list = D('Portal_article_source')->select();
        if($article_list){
        	foreach($article_list as $key => $article){
        		foreach($article_cates as $item){
        			if($article['fcid'] == $item['cid'] && $item['fcid'] == 0){
        				$article_list[$key]['fcat_name'] = $item['cat_name'];
        			}
        			if($article['cid'] == $item['cid'] && $item['fcid'] != 0){
        				$article_list[$key]['cat_name'] = $item['cat_name'];
        			}
        			if($article_list[$key]['fcat_name']!='' && $article_list[$key]['cat_name']!=''){
        				continue;
        			}
        		}
        		if ($source_list) {
	        		foreach($source_list as $item){
	        			if($article['source_id'] == $item['id']){
	        				$article_list[$key]['source_name'] = $item['title'];
	        			}
	        		}
	        		$article_list[$key]['source_name'] = $article_list[$key]['source_name'] ? $article_list[$key]['source_name'] : '无';
        		}
        	}
        }

        $this->assign('pagebar',$pagebar);
        $this->assign('article_list',$article_list);
		$this->display();
	}

	// 增加/编辑资讯
	public function article_add(){
		// 资讯分类
		$fcid_list = D('Portal_article_cat')->where(array('fcid'=>0,'cat_status'=>1))->select();
		$this->assign('fcid_list',$fcid_list);

		// 标签列表
		$label_list = D('Portal_article_label')->select();

		// 来源列表
		$source_list = D('Portal_article_source')->select();

		$aid = (int)$_GET['aid'];
		$article = D('Portal_article')->where(array('aid'=>$aid))->find();
        if ($article && $article['reward_money']) {
            $article['reward_money'] = ($article['reward_money'] * 100) / 100;
        }

		// 文章标签
		$article_label_list = D('Portal_article_label_relation')->field('label_id')->where(array('article_id'=>$aid))->select();
		$this->assign('article',$article);
		$this->assign('label_list',$label_list);
		$this->assign('source_list',$source_list);
		$this->assign('article_label_list',$article_label_list);
		$this->display();
	}

	// 资讯活动评论
	public function portal_recomment(){
		$count = D('Portal_recomment')->where(array('target_id'=>$_GET['aid'],'type'=>$_GET['type']))->count();
        import('@.ORG.system_page');
        $p = new Page($count,20);
		$comment_list = D('Portal_recomment')->where(array('target_id'=>$_GET['aid'],'type'=>$_GET['type']))->order()->limit($p->firstRow.','.$p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->assign('comment_list',$comment_list);
		$this->display();
	}

	public function comment_del(){
		$id = (int)$_POST['id'];
		$res = D('Portal_recomment')->where(array('id'=>$id))->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	public function yellow_recomment(){
		// dump($_GET);
		$count = D('Portal_yellow_recomment')->where(array('yellow_id'=>$_GET['yellow_id']))->count();
        import('@.ORG.system_page');
        $p = new Page($count,20);
		$comment_list = D('Portal_yellow_recomment')->where(array('yellow_id'=>$_GET['yellow_id']))->limit($p->firstRow.','.$p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->assign('comment_list',$comment_list);
		$this->display();
	}

	public function yellow_comment_del(){
		$id = (int)$_POST['id'];
		$res = D('Portal_yellow_recomment')->where(array('id'=>$id))->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	// 资讯保存
	public function save_article(){
		$data['aid'] = (int)$_POST['aid'];
		$data['fcid'] = (int)$_POST['fcid'];
		$data['cid'] = (int)$_POST['cid'];
		$data['title'] = trim($_POST['title']);
		$data['desc'] = trim($_POST['desc']);
		$img_flag = (int)$_POST['img_flag'];
		$data['thumb'] = $_POST['thumb'];
		$data['msg'] = trim($_POST['msg']);
		$data['status'] = (int)$_POST['status'];
		$data['dateline'] = time();
        $data['source_id'] = (int)$_POST['source_id'];
        $data['is_reward'] = (int)$_POST['is_reward'];
		// 文章标签
		$labels = $_POST['labels'];

		if($data['fcid']<=0 || $data['cid'] <= 0){
			exit(json_encode(array('code'=>1,'msg'=>'请选择资讯分类')));
		}
		if($data['title'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'请填写资讯标题')));
		}
		if($data['msg'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'请填写资讯内容')));
		}
		if ($data['is_reward'] != 1 && $data['is_reward'] != 2) {
            $data['is_reward'] = 1;
		}
		if ($data['is_reward'] == 2) {
            $data['reward_money'] = round($_POST['reward_money'], 2);
		}

		if($data['aid']){
			if($img_flag==0){
				unset($data['thumb']);
			}
			// 删除文章所有标签
			D('Portal_article_label_relation')->where(array('article_id'=>$data['aid']))->delete();
			if($labels){
				foreach($labels as $label){
					D('Portal_article_label_relation')->data(array('article_id'=>$data['aid'],'label_id'=>$label))->add();
				}
			}
			$res = D('Portal_article')->where(array('aid'=>$data['aid']))->data($data)->save();
		}else{
			$res = D('Portal_article')->data($data)->add();
		}
		exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
	}

	// 特别推荐文章
	public function article_recommend(){
		$aid = (int)$_POST['aid'];
		$isrecomment = (int)$_POST['isrecommend'];
		$artile = D('Portal_article')->where(array('aid'=>$aid))->find();
		if(!$artile){
			exit(json_encode(array('code'=>1,'msg'=>'未找到该资讯')));
		}
		$res = D('Portal_article')->where(array('aid'=>$aid))->data(array('recommend'=>$isrecomment))->save();
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'操作失败')));
		}
		exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
	}

	// 资讯标签列表
	public function article_label(){
		$label_list = D('Portal_article_label')->select();
		$this->assign('label_list',$label_list);
		$this->display();
	}

	// 添加/编辑标签
	public function article_label_add(){
		$label_id = (int)$_GET['label_id'];
		$label = D('Portal_article_label')->where(array('id'=>$label_id))->find();
		$this->assign('label',$label);
		$this->display();
	}

	// 保存标签
	public function save_label(){
		$label_id = (int)$_POST['label_id'];
		$name = trim($_POST['name']);
		if($label_id){
			$res = D('Portal_article_label')->where(array('id'=>$label_id))->data(array('title'=>$name))->save();
		}else{
			$res = D('Portal_article_label')->data(array('title'=>$name))->add();
		}
		if($res === false){
			$this->frame_submit_tips(0,'保存失败~');
		}

		$this->frame_submit_tips(1,'保存成功');
	}

	// 删除标签
	public function article_label_del(){
		$label_id = (int)$_POST['label_id'];
		$res = D('Portal_article_label')->where(array('id'=>$label_id))->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	// 删除资讯
	public function article_del(){
		$aid = (int)$_POST['aid'];
		$res = D('Portal_article')->where(array('aid'=>$aid))->delete();
		if($res){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败');
		}
	}

	// 资讯来源列表
	public function article_source(){
		$lists = D('Portal_article_source')->select();
		$this->assign('lists',$lists);
		$this->display();
	}

	// 添加/编辑来源
	public function article_source_add(){
		$source_id = (int)$_GET['source_id'];
		$source = D('Portal_article_source')->where(array('id'=>$source_id))->find();
		$this->assign('source',$source);
		$this->display();
	}

	// 保存来源
	public function save_source(){
		$source_id = (int)$_POST['source_id'];
		$name = trim($_POST['name']);
		if($source_id){
			$res = D('Portal_article_source')->where(array('id'=>$source_id))->data(array('title'=>$name))->save();
		}else{
			$res = D('Portal_article_source')->data(array('title'=>$name))->add();
		}

		if($res === false){
			$this->frame_submit_tips(0,'保存失败~');
		}
		$this->frame_submit_tips(1,'保存成功');
	}

	// 删除来源
	public function article_source_del(){
		$source_id = (int)$_POST['source_id'];
		$res = D('Portal_article_source')->where(array('id'=>$source_id))->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	// 活动列表
	public function activity(){
        $count = D('Portal_activity')->count();
        import('@.ORG.system_page');
        $p = new Page($count,10);
		$activityList = D('Portal_activity')->limit($p->firstRow.','.$p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
		$this->assign('activityList',$activityList);
		$this->display();
	}

	//查看活动
    public function activity_detail()
    {
            $activityInfo = D('Portal_activity')->where(array('a_id' => $_GET['a_id']))->find();
            $this->assign('activityInfo', $activityInfo);
            $catInfo = D('Portal_activity_cat')->where(array('cid'=>$activityInfo['cid']))->getField('cat_name');
            $this->assign('catInfo',$catInfo);

            $this->display();


    }
    //报名管理
    public function activity_sign(){
		if(isset($_GET['a_id'])) {
            $count = D('Portal_activity_sign')->where(array('a_id'=>$_GET['a_id']))->count();
            import('@.ORG.system_page');
            $p = new Page($count,10);


            $activityList = D('Portal_activity_sign')->where(array('a_id'=>$_GET['a_id']))->limit($p->firstRow.','.$p->listRows)->order('create_time desc')->select();
            $money = D('Portal_activity')->where(array('a_id'=>$_GET['a_id']))->getField('price');
            $money = floatval($money);
            $people = count($activityList);
            $moneyTotal = $people * $money;

            $pagebar = $p->show();
            $this->assign('pagebar',$pagebar);
            $this->assign('money', $money);
            $this->assign('people', $people);
            $this->assign('moneyTotal', $moneyTotal);
            $this->assign('peopleList', $activityList);
            $this->display();
        }
    }
    //报名详情查看
    public function activity_sign_detail(){
        if(isset($_GET['sid'])) {

            $peopleInfo = D('Portal_activity_sign')->where(array('sid'=>$_GET['sid']))->find();
            $this->assign('peopleInfo', $peopleInfo);
            $money = D('Portal_activity')->where(array('a_id'=>$peopleInfo['a_id']))->getField('price');
            $this->assign('money', $money);
            $this->display();
        }
    }
    //导出活动报名
    public function export()
    {
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '报名列表';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);


        $price = D('Portal_activity')->where(array('a_id'=>$_GET['a_id']))->getField('price');

        $peopleList = D('Portal_activity_sign')->where(array('a_id'=>$_GET['a_id']))->order('create_time desc')->select();

            $objExcel->createSheet();
            $objActSheet = $objExcel->getActiveSheet();
			//设置首行
			$num = D('Portal_activity_sign')->where(array('a_id'=>$_GET['a_id']))->count();
			$activity = D('Portal_activity')->where(array('a_id'=>$_GET['a_id']))->find();
			$money = floatval($activity['price']);
			$moneyTotal = $num * $money;
			$str = '活动名称：'.$activity['title'].'；共报名：'.$num.'人；共收款：'.$moneyTotal.'元。';
            $objActSheet->setCellValue('A1', $str);
        	$objActSheet->mergeCells('A1:G1');
			//设置宽度
        	$objActSheet->getDefaultColumnDimension('A')->setWidth(20);
        	$objActSheet->getDefaultColumnDimension('B')->setWidth(20);
        	$objActSheet->getDefaultColumnDimension('C')->setWidth(20);
        	$objActSheet->getDefaultColumnDimension('D')->setWidth(20);
        	$objActSheet->getDefaultColumnDimension('E')->setWidth(20);
        	$objActSheet->getDefaultColumnDimension('F')->setWidth(20);
        	$objActSheet->getDefaultColumnDimension('G')->setWidth(20);
			//设置标题行
            $objActSheet->setCellValue('A2', '序号');
            $objActSheet->setCellValue('B2', '姓名');
            $objActSheet->setCellValue('C2', '电话');
            $objActSheet->setCellValue('D2', 'QQ');
            $objActSheet->setCellValue('E2', '备注');
            $objActSheet->setCellValue('F2', '活动费用');
            $objActSheet->setCellValue('G2', '报名时间');
			//插入数据
            $k = 3;
            foreach($peopleList as $key => $v){
                $objActSheet->setCellValueExplicit('A' . $k, $v['sid']);
                $objActSheet->setCellValueExplicit('B' . $k, $v['truename']);
                $objActSheet->setCellValueExplicit('C' . $k, $v['phone']);
                $objActSheet->setCellValueExplicit('D' . $k, $v['qq']);
                $objActSheet->setCellValueExplicit('E' . $k, $v['message']);
                $objActSheet->setCellValueExplicit('F' . $k, $price);
                $objActSheet->setCellValueExplicit('G' . $k, date('Y-m-d H:i:s',$v['create_time']));
            	$k++;
			}

            sleep(1);
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();

    }
	// 增加活动
	public function activity_add(){
		if(IS_POST){
			$image = D('Image')->handle($this->system_session['id'], 'portal', 0, array('size' => 10), false);
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/portal/', '', $image['url']));
			} else {
				$this->frame_submit_tips(0, $image['msg']);
			}
			$data['title'] = $_POST['title'];
			$data['time'] = $_POST['time'];
			$data['place'] = $_POST['place'];
			$data['price'] = $_POST['price'];
			$data['number'] = $_POST['number'];
			$data['cid'] = $_POST['cid'];
			$data['leader'] = $_POST['leader'];
			$data['status'] = $_POST['status'];
			$data['enroll_time'] = strtotime($_POST['enroll_time']);
			$data['pic'] = $_POST['pic'];
			$res = D('Portal_activity')->data($data)->add();
			if($res){
				$this->frame_submit_tips(1,'添加成功！');
			}else{
				$this->frame_submit_tips(0,'添加失败！请重试~');
			}
		}else{
			$catList = D('Portal_activity_cat')->where(array('cat_status'=>1))->select();
			$this->assign('catList',$catList);
			$this->display();
		}
	}
	// 修改活动
	public function activity_edit(){
		if(IS_POST){
			if($_FILES['pic']['error'] != 4){
				$image = D('Image')->handle($this->system_session['id'], 'portal', 0, array('size' => 10), false);
				if (!$image['error']) {
					$_POST = array_merge($_POST, str_replace('/upload/portal/', '', $image['url']));
				} else {
					$this->frame_submit_tips(0, $image['msg']);
				}
			}
			$data['a_id'] = $_POST['a_id'];
			$data['title'] = $_POST['title'];
			$data['time'] = $_POST['time'];
			$data['place'] = $_POST['place'];
			$data['price'] = $_POST['price'];
			$data['number'] = $_POST['number'];
			$data['cid'] = $_POST['cid'];
			if($_POST['pic']){
				$data['pic'] = $_POST['pic'];
			}
			$data['leader'] = $_POST['leader'];
			$data['status'] = $_POST['status'];
			$data['enroll_time'] = strtotime($_POST['enroll_time']);
			$res = D('Portal_activity')->where(array('a_id'=>intval($_POST['a_id'])))->data($data)->save();
			if($res){
				$this->frame_submit_tips(1,'修改成功！');
			}else{
				$this->frame_submit_tips(0,'修改失败！请重试~');
			}
		}else{
			$catList = D('Portal_activity_cat')->where(array('cat_status'=>1))->select();
			$this->assign('catList',$catList);
			$activityInfo = D('Portal_activity')->where(array('a_id'=>$_GET['a_id']))->find();
			$this->assign('activityInfo',$activityInfo);
			$this->display();
		}

	}

	public function activity_content_edit(){

		if(IS_POST){
			$data[$_POST['key']] = $_POST['content'];
			$res = D('Portal_activity')->where(array('a_id'=>intval($_POST['a_id'])))->data($data)->save();
			if($res){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$info = D('Portal_activity')->where(array('a_id'=>$_GET['a_id']))->find();
			$content['a_id'] = $info['a_id'];
			$content['key'] = $_GET['content'];
			$content['content'] = $info[$_GET['content']];
			$this->assign('content',$content);
			$this->display();
		}

	}

	// 删除活动
	public function activity_del(){
		if (IS_POST) {
            if (D('Portal_activity')->where(array('a_id'=>intval($_POST['a_id'])))->delete()) {
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
	}

	// 分类列表
	public function activity_cat(){
		$fcid = intval($_GET['fcid']);
		$condition['fcid'] = $fcid;
		$catList = D('Portal_activity_cat')->where($condition)->order('`cat_sort` DESC,`cid` ASC')->select();
		if ($fcid > 0) {
            $condition_now['cid'] = $fcid;
            $now_category = D('Portal_activity_cat')->field(true)->where($condition_now)->find();
            $this->assign('now_category', $now_category);
        }
		$this->assign('catList',$catList);
		$this->assign('fcid', $fcid);
		$this->display();
	}
	// 增加分类
	public function activity_cat_add(){

		$fcid = intval($_GET['fcid']);
        $this->assign('fcid', $fcid);
		if($_POST){
			$data['fcid'] = $_POST['fcid'];
			$data['cat_name'] = $_POST['cat_name'];
			$data['cat_url'] = $_POST['cat_url'];
			$data['cat_sort'] = $_POST['cat_sort'];
			$data['img'] = $_POST['img'];
			if(empty($data['img'])){
				$this->error('添加失败！请添加图片~');
			}
			$data['cat_status'] = $_POST['cat_status'];
			$res = D('Portal_activity_cat')->data($data)->add();
			if($res){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}
		$this->display();
	}
	// 修改分类
	public function activity_cat_edit(){
		$portal_activity_cat = D('Portal_activity_cat');
		if (IS_POST) {
            $datas = $_POST;
            if(empty($datas['img'])){
				$this->error('添加失败！请添加图片~');
			}
            if ($portal_activity_cat->data($datas)->save()) {
                // $this->frame_submit_tips(1,'编辑成功！');
                $this->success('编辑成功！');
            } else {
                // $this->frame_submit_tips(0,'编辑失败！请重试~');
                $this->error('编辑失败！请重试~');
            }
            die;
        }
        $where['cid'] = intval($_GET['cid']);
        $now_category = $portal_activity_cat->field(true)->where($where)->find();
        if (empty($now_category)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        $this->assign('now_category', $now_category);
		$this->display();
	}
	// 删除分类
	public function activity_cat_del(){
		if (IS_POST) {
            $portal_activity_cat = D('Portal_activity_cat');
            $where['cid'] = intval($_POST['cid']);
            $list = D('Portal_activity')->where(array('cid'=>intval($_POST['cid'])))->select();
            if(!is_array($list)){
            	$now_category = $portal_activity_cat->field(true)->where($where)->find();
	            if ($portal_activity_cat->where($where)->delete()) {
	                    $portal_activity_cat->where(array('fcid' => $now_category['cid']))->delete();
	                $this->success('删除成功！');
	            } else {
	                $this->error('删除失败！请重试~');
	            }
            }else{
            	$this->error('删除失败！请先删除分类下的活动~');
            }

        } else {
            $this->error('非法提交,请重新提交~');
        }
	}



	public function tieba(){
		$tiebaPlateList = D('Portal_tieba_plate')->where(array('status'=>1))->select();
		$this->assign('tiebaPlateList',$tiebaPlateList);

		if($_GET['essence'] == 1){
			$where['is_essence'] = 1;
		}

		if($_GET['plate_id'] >0){
			$where['plate_id'] = $_GET['plate_id'];
		}

        if($_GET['order']){
        	$order['is_top'] = 'desc';
            $order[$_GET['order']] = $_GET['sort'];
        }else{
            $order = array('is_top'=>'desc','last_time'=>'desc');
        }

        if($_GET['search']){
            $where['title'] = array('like','%'.$_GET['search'].'%');
        }
        $where['target_id'] = 0;
        $where['status'] = 0;
		$count = D('Portal_tieba')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count,20);
		$tiebaList = D('Portal_tieba')->where($where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();


		$pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->assign('count',$count);

		$this->assign('tiebaList',$tiebaList);


		$this->display();
	}

	public function tieba_del(){
		if(IS_POST){
			$res = D('Portal_tieba')->where(array('tie_id'=>intval($_POST['tie_id'])))->data(array('status'=>4,'del_uid'=>$this->system_session['uid']))->save();
			if ($res) {
	            $this->success('删除成功！');
	        } else {
	            $this->error('删除失败！请重试~');
	        }
		}else{
			$this->error('非法提交~');
		}



	}

	public function tieba_plate(){
		$pid = intval($_GET['pid']);
		$condition['pid'] = $pid;
		$plateList = D('Portal_tieba_plate')->where($condition)->order('`sort` DESC,`plate_id` ASC')->select();
		if ($pid > 0) {
            $condition_now['plate_id'] = $pid;
            $tieba_plate = D('Portal_tieba_plate')->field(true)->where($condition_now)->find();
            $this->assign('tieba_plate', $tieba_plate);
        }
		$this->assign('plateList',$plateList);
		$this->assign('pid', $pid);
		$this->display();
	}

	public function tieba_plate_add(){
		$pid = intval($_GET['pid']);
        $this->assign('pid', $pid);
		if($_POST){
			$data['pid'] = $_POST['pid'];
			$data['plate_name'] = $_POST['plate_name'];
			$data['sort'] = $_POST['sort'];
			$data['status'] = $_POST['status'];
			$res = D('Portal_tieba_plate')->data($data)->add();
			if($res){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}
		$this->display();
	}

	public function tieba_plate_edit(){
		$portal_tieba_plate = D('Portal_tieba_plate');
		if (IS_POST) {
            $datas = $_POST;
            if ($portal_tieba_plate->data($datas)->save()) {
                $this->frame_submit_tips(1,'编辑成功！');
            } else {
                $this->frame_submit_tips(0,'编辑失败！请重试~');
            }
            die;
        }
        $where['plate_id'] = intval($_GET['plate_id']);
        $tieba_plate = $portal_tieba_plate->field(true)->where($where)->find();
        if (empty($tieba_plate)) {
            $this->frame_error_tips('没有找到该板块信息！');
        }
        $this->assign('tieba_plate', $tieba_plate);
		$this->display();
	}

	public function tieba_plate_del(){
		if (IS_POST) {
            $portal_tieba_plate = D('Portal_tieba_plate');
            $where['plate_id'] = intval($_POST['plate_id']);
            $tieba_plate = $portal_tieba_plate->field(true)->where($where)->find();
            if ($portal_tieba_plate->where($where)->delete()) {
                    $portal_tieba_plate->where(array('pid' => $tieba_plate['plate_id']))->delete();
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
	}

	public function search_user() {
		$plateUserList = D('')->table(array(C('DB_PREFIX').'portal_tieba_plate_user'=>'pu', C('DB_PREFIX').'user'=>'u'))->where("pu.plate_id= '".intval($_GET['plate_id'])."' AND pu.uid = u.uid")->field('u.uid,u.nickname,pu.plate_id,pu.id')->select();
		$this->assign('plateUserList',$plateUserList);
        //搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }

        $condition_user['openid'] = array('notlike','%no_use');
		//排序
		$order_string = '`uid` DESC';
		if($_GET['sort']){
			switch($_GET['sort']){
				case 'uid':
					$order_string = '`uid` DESC';
					break;
				case 'lastTime':
					$order_string = '`last_time` DESC';
					break;
				case 'money':
					$order_string = '`now_money` DESC';
					break;
				case 'score':
					$order_string = '`score_count` DESC';
					break;
			}
		}
		//状态
        if ($_GET['status'] != '') {
        	$condition_user['status']	=	$_GET['status'];
        }
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_user['_string'] =" (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $database_user = D('User');
        $count_user = $database_user->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 10);
        $user_list = $database_user->field(true)->where($condition_user)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

        if (!empty($user_list)) {
            import('ORG.Net.IpLocation');
            $IpLocation = new IpLocation();
            foreach ($user_list as &$value) {
                $last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
                $value['last_ip_txt'] = iconv('GBK', 'UTF-8', $last_location['country']);
            }
        }
        $this->assign('user_list', $user_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('client', array(0=>'WAP端',1=>'苹果',2=>'安卓',3=>'电脑',4=>'小程序',5=>'微信'));
        $this->display();
    }

    public function plate_user_add(){
    	$count = D('Portal_tieba_plate_user')->where(array('plate_id'=>$_POST['plate_id']))->count();
    	if($count >=3){
    		exit(json_encode(array('error'=>4,'msg'=>'只能添加三个管理员')));
    	}
    	$info = D('Portal_tieba_plate_user')->where(array('plate_id'=>$_POST['plate_id'],'uid'=>$_POST['uid']))->find();
    	if($info){
    		exit(json_encode(array('error'=>3,'msg'=>'管理员已添加！')));
    	}
    	$res = D('Portal_tieba_plate_user')->data($_POST)->add();
    	// echo D('Portal_tieba_plate_user')->getlastsql();
    	if($res){
    		exit(json_encode(array('error'=>1,'msg'=>'管理员添加成功')));
    	}else{
    		exit(json_encode(array('error'=>2,'msg'=>'添加失败！请重试')));
    	}
    }

    public function plate_user_del(){
    	if (IS_POST) {
            $where['id'] = intval($_POST['id']);
            if (D('Portal_tieba_plate_user')->where($where)->delete()) {
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function yellow(){
		$count = D('Portal_yellow')->count();
        import('@.ORG.system_page');
        $p = new Page($count,20);
        $pagebar = $p->show();

		$yellow_list = D('Portal_yellow')->order('status asc,dateline desc')->limit($p->firstRow.','.$p->listRows)->select();
		// 黄页分类
		$catList = D('Group_category')->get_category();
		if($yellow_list){
			foreach($yellow_list as &$yellow){
				foreach($catList as $cat){
					if($yellow['pid'] == $cat['cat_id']){
						$yellow['parent_cat_name'] = $cat['cat_name'];
						foreach($cat['category_list'] as $childs){
							if($yellow['cid'] == $childs['cat_id']){
								$yellow['child_cat_name'] = $childs['cat_name'];
								break;
							}
						}
						break;
					}
				}
			}
		}
		$this->assign('yellow_list',$yellow_list);
		$this->assign('pagebar',$pagebar);
		$this->display();
	}


	public function yellow_del(){
		if (IS_POST) {
            $Portal_yellow = D('Portal_yellow');
            $where['id'] = intval($_POST['yid']);
            if ($Portal_yellow->where($where)->delete()) {
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
	}

	// 黄页置顶
	public function yellow_dotop(){
		$yid = (int)$_POST['yid'];
		$res = D('Portal_yellow')->where(array('id'=>$yid))->data(array('top_time'=>time()))->save();
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'置顶失败')));
		}
		exit(json_encode(array('code'=>0,'msg'=>'置顶成功')));
	}

	// 取消黄页置顶
	public function yellow_untop(){
		$yid = (int)$_POST['yid'];
		$res = D('Portal_yellow')->where(array('id'=>$yid))->data(array('top_time'=>0))->save();
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'取消失败')));
		}
		exit(json_encode(array('code'=>0,'msg'=>'取消成功')));
	}

	// 添加/编辑黄页
	public function yellow_add(){
		//省市区
        $province_list = D('Area')->get_arealist_by_areaPid(0);
        $this->assign('province_list',$province_list);

        $city_list = D('Area')->get_arealist_by_areaPid($province_list[0]['area_id']);
        $this->assign('city_list',$city_list);

        $area_list = D('Area')->get_arealist_by_areaPid($city_list[0]['area_id']);
        $this->assign('area_list',$area_list);

        $yid = (int)$_GET['yid'];
        $yellow_detail = D('Portal_yellow')->where(array('id'=>$yid))->find();
        $this->assign('Yellow_detail',$yellow_detail);
		$this->display();
	}

	// 分类选择
	public function ajax_get_yellow_categroy_list(){
		$all_category_list = D('Group_category')->get_category();

		$pid = (int)$_GET['pid'];
		$data_list = array();
		if($pid == 0){
			foreach($all_category_list as $item){
				if($item['cat_fid'] == 0){
					$data_list[] = array('cat_id'=>$item['cat_id'],'cat_name'=>$item['cat_name']);
				}
			}
		}else{
			foreach($all_category_list as $item){
				if($item['cat_id'] != $pid){
					continue;
				}
				foreach($item['category_list'] as $child){
					$data_list[] = array('cat_id'=>$child['cat_id'],'cat_name'=>$child['cat_name']);
				}
			}
		}
		exit(json_encode(array('code'=>0,'data'=>$data_list)));
	}

	// 区域选择
	public function select_area(){
		$area_list = D('Area')->get_arealist_by_areaPid($_POST['pid']);
		if(!empty($area_list)){
			$return['error'] = 0;
			$return['list'] = $area_list;
		}else{
			$return['error'] = 1;
		}
		echo json_encode($return);
	}

	// 加载百度地图
    public function yellow_baidu_map(){
    	$this->display();
    }

    // 保存申请信息
    public function save_apply(){
    	$data['uid'] = (int)$_SESSION['user']['uid'];
    	$data['title'] = trim($_POST['title']);
    	$data['tel'] = trim($_POST['tel']);
    	$data['email'] = trim($_POST['email']);
    	$data['address'] = trim($_POST['address']);
    	$data['pid'] = (int)$_POST['parent_cate'];
    	$data['cid'] = (int)$_POST['child_cate'];
    	$data['province'] = (int)$_POST['address_province'];
    	$data['city'] = (int)$_POST['address_city'];
    	$data['area'] = (int)$_POST['address_area'];
    	$data['lng'] = $_POST['lng'];
    	$data['lat'] = $_POST['lat'];
    	$data['logo'] = $_POST['logo'];
    	$data['qrcode'] = $_POST['qrcode'];
    	$data['service'] = trim($_POST['service']);
        $data['dateline'] = time();
        $data['status'] = (int)$_POST['status'];

    	if($data['title'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'公司名称缺失')));
    	}
    	if($data['tel'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'联系电话缺失')));
    	}
    	if($data['address'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'联系地址缺失')));
    	}
    	if($data['pid']<=0 || $data['cid'] <= 0){
    		exit(json_encode(array('code'=>1,'msg'=>'业务类型缺失')));
    	}
    	if($data['province']<=0 || $data['city']<=0 || $data['area']<=0){
    		exit(json_encode(array('code'=>1,'msg'=>'所在区域缺失')));
    	}
    	if($data['lng']==''|| $data['lat'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'经纬度缺失')));
    	}
    	if($data['logo'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'请上传公司logo')));
    	}
    	/*
    	if($data['qrcode'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'没有上传二维码')));
    	}
    	*/
    	if($data['service'] == ''){
    		exit(json_encode(array('code'=>1,'msg'=>'服务内容缺失')));
    	}

    	$id = (int)$_POST['id'];
    	if($id){
    		$res = D('Portal_yellow')->where(array('id'=>$id))->data($data)->save();
    	}else{
    		$res = D('Portal_yellow')->data($data)->add();
    	}

    	if(!$res){
    		exit(json_encode(array('code'=>1,'msg'=>'保存失败')));
    	}
    	exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }


}
?>
