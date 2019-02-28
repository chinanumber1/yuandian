<?php
/*
 * 分类信息管理
 *
 */
class ClassifynewAction extends BaseAction {
	public function __construct(){
		empty($this->config['classify_title']) && $this->config['classify_title'] == '分类信息';
		parent::__construct();
	}
	//浏览数统计、发布、分享	1小时的缓存
	private function classify_count(){
		$classify_count = S('classify_count_'.$this->config['now_city']);
		if(empty($classify_count)){
			$classify_count['views_count'] = M('Classify_userinput')->where(array('city_id'=>$this->config['now_city']))->sum('views');
			if($classify_count['views_count'] > 10000){
				$classify_count['views_count_txt'] = round($classify_count['views_count']/10000,1).'万';
			}else{
				$classify_count['views_count_txt'] = $classify_count['views_count'] ? $classify_count['views_count'] : 0;
			}
			
			$classify_count['shares_count'] = M('Classify_userinput')->where(array('city_id'=>$this->config['now_city']))->sum('shares');
			if($classify_count['shares_count'] > 10000){
				$classify_count['shares_count_txt'] = round($classify_count['shares_count']/10000,1).'万';
			}else{
				$classify_count['shares_count_txt'] = $classify_count['shares_count'] ? $classify_count['shares_count'] : 0;
			}
			
			$classify_count['content_count'] = M('Classify_userinput')->where(array('city_id'=>$this->config['now_city']))->count();
			if($classify_count['content_count'] > 10000){
				$classify_count['content_count_txt'] = round($classify_count['content_count']/10000,1).'万';
			}else{
				$classify_count['content_count_txt'] = $classify_count['content_count'] ? $classify_count['content_count'] : 0;
			}
			S('classify_count',$classify_count,3600);
		}
		return $classify_count;
	}
	//分类信息首页
    public function index() {
		//轮播图广告
        $classify_index_ad = D('Adver')->get_adver_by_key('classify_index_ad', 3);
		$this->assign('classify_index_ad',$classify_index_ad);
		
		//九宫格导航
        $tmp_wap_index_slider = D('Slider')->get_slider_by_key('wap_classify_slider', 0);
        $wap_index_slider = array();
        foreach ($tmp_wap_index_slider as $key => $value) {
            $tmp_i = floor($key / 10);
            $wap_index_slider[$tmp_i][] = $value;
        }
		$this->assign('wap_classify_slider',$wap_index_slider);
		
		//浏览数统计、发布、分享	1小时的缓存
		$classify_count = $this->classify_count();
		$this->assign('classify_count',$classify_count);
		
		//同城动态播报
		$classify_scrollmsg = S('classify_scrollmsg');
		if(empty($classify_scrollmsg)){
			$classify_scrollmsg = M('Classify_scrollmsg')->order('`pigcms_id` DESC')->limit(3)->select();
			S('classify_scrollmsg',$classify_scrollmsg,120);
		}
		$this->assign('classify_scrollmsg',$classify_scrollmsg);
		
		//热门一级分类
		$classify_hot_category = S('classify_hot_category');
		if(empty($classify_hot_category)){
			$classify_hot_category = M('Classify_category')->where(array('fcid'=>0,'cat_status'=>1,'is_hot'=>1))->order('`cat_sort` DESC')->select();
			S('classify_hot_category',$classify_hot_category,3600);
		}
		$this->assign('classify_hot_category',$classify_hot_category);
		
		
		$this->assign('page_title',$this->config['classify_title']);
		
		$this->display();
	}
	
	public function search(){
		$keyword = safeValue($_GET['keyword']);
		if ($keyword) {
			$this->assign('page_title',$this->config['classify_title'].'【'.$keyword.'】');		
            $this->display();
		}else{
			redirect(U('index'));
		}
	}
	
	public function category(){
		$cid = intval($_GET['cat_id']);
		if ($cid > 0) {
			$opt = isset($_GET['opt']) ? trim($_GET['opt']) : '';
			$where = $this->analyse_param($opt);
            $original = !empty($where) ? $where['original'] : '';
            $c_input = !empty($where) ? $where['fd'] : '';
			$tmpcid = $this->getTishFcid($cid);
            $where_arr = $tmpcid['fcid'] > 0 ? array('cid' => $cid, 'fcid' => $tmpcid['fcid']) : array('cid' => $cid);
            $category = D('Classify_category');
            $cat_field = $category->field('cid,fcid,pfcid,subdir,cat_field')->where($where_arr)->find();
            $conarr = array();
            if ($cat_field['cat_field']) {
                $cat_field_arr = unserialize($cat_field['cat_field']);
                foreach ($cat_field_arr as $cv) {
                    if (($cv['isfilter'] == 1) && ($cv['input'] > 0)) {
                        if (($cv['type'] == 1) && ($cv['inarr'] == 1)) {
                            $conarr[] = array('opt' => 1, 'name' => $cv['name'], 'input' => 'input' . $cv['input'], 'data' => $cv['filtercon']);
                        } else {
                            if (isset($cv['use_field']) && ($cv['use_field'] == 'area')) {
                                $get_area_list = D('Area')->get_area_list();
                                $new_areas = array();
                                if ($get_area_list) {
                                    foreach ($get_area_list as $vv) {
                                        $new_areas[$vv['area_id']] = $vv['area_name'];
                                    }
                                }
                                $cv['filtercon'] = $new_areas;
                            }
                            $conarr[] = array('opt' => 0, 'name' => $cv['name'], 'input' => 'input' . $cv['input'], 'data' => $cv['filtercon']);
                        }
                    }
                }
            }

            if (!empty($conarr) && count($conarr) < 4) {
                $categorys = $this->getTishFcategory($cid);
                $this->assign('categorys', $categorys);
            }
			
			$fcid = $tmpcid['fcid'] > 0 ? $tmpcid['fcid'] : $cat_field['fcid'];
			$this->assign('qsearch', $opt);
			$this->assign('original', $original);
            $this->assign('conarr', $conarr);
            $this->assign('cid', $cid);
            $this->assign('cat_name', $tmpcid['cat_name']);
            $this->assign('fcid', $fcid);
			
			$this->assign('page_title',$this->config['classify_title'].$tmpcid['cat_name']);
			
            $this->display();
		}else{
			redirect(U('index'));
		}
	}
	
	/**
     * **统一参数解析
     * **
     * ***** */
    private function analyse_param($str) {
        if (empty($str))
            return false;
        $s_str = base64_decode(str_replace(" ", "+", $str));

        if (!$s_str || (strpos($s_str, ',ty=') === false) || (strpos($s_str, ',fd=') === false) || (strpos($s_str, ',vv=') === false))
            return false;
        $s_arr = explode(',', $s_str);
        if (count($s_arr) != 4)
            return false;
        $tmpdata = array('ty' => '', 'fd' => '', 'vv' => '', 'original' => '');
        $tmp = explode('=', $s_arr['1']);
        $tmpdata['ty'] = intval($tmp['1']); //是否需要解析vv字符串
        $tmp = explode('=', $s_arr['2']); //是否需要解析vv字符串
        $tmpdata['fd'] = trim($tmp['1']); //字段名字 input1、input2....
        $tmp = explode('=', $s_arr['3']); //是否需要解析vv字符串
        $tmpdata['original'] = $tmpdata['vv'] = trim($tmp['1']); //条件值
        if ($tmpdata['ty'] == 1) {
            $tmpdata['vv'] = preg_replace('/[^0-9\-]*/', '', $tmpdata['vv']); /*             * 过滤掉不需要的字符* */
        }
        return $tmpdata;
    }
	
	/**     * */
    private function getTishFcid($cid, $cache = true) {
        $tmpdata = $_SESSION["session_FcidInfo{$cid}"];
        $tmpdata = !empty($tmpdata) ? unserialize($tmpdata) : false;
        if ($cache && !empty($tmpdata)) {
            return $tmpdata;
        } else {
            $tmpdata = M('classify_category')->field('cid,fcid,pfcid,subdir,cat_name')->where(array('cid' => $cid))->find();
            if ($cache) {
                $_SESSION["session_FcidInfo{$cid}"] = !empty($tmpdata) ? serialize($tmpdata) : '';
            } else {
                $_SESSION["session_FcidInfo{$cid}"] = '';
            }
            return $tmpdata;
        }
    }

    private function getTishFcategory($cid, $cache = true) {
        $tmpdata = $_SESSION["session_Fcategory{$cid}"];
        $tmpdata = !empty($tmpdata) ? unserialize($tmpdata) : false;
        if ($cache && !empty($tmpdata)) {
            return $tmpdata;
        } else {
            $classify_categoryDb = M('classify_category');
            $tmp = $classify_categoryDb->field('cid,fcid,subdir')->where(array('cid' => $cid, 'subdir' => 2))->find();
            if (!empty($tmp)) {
                $tmpdata = $classify_categoryDb->field('cid,fcid,pfcid,subdir,cat_name')->where(array('fcid' => $tmp['fcid'], 'subdir' => 2))->order('`cat_sort` DESC,`cid` ASC')->select();
                foreach ($tmpdata as $kk => $vv) {
                    if ($cid == $vv['cid']) {
                        $tmpdata[$kk]['subdir3'] = $this->get_Subdirectory($cid, 2, 3);
                    }
                }
            }
            return $tmpdata;
        }
    }
	
	public function getList(){
		$type = $_GET['type'];
		if($type != 'auditing'){
			$wherestrQuery = '`c`.`status`=1 AND `c`.`city_id`='.$this->config['now_city'].' AND `c`.`uid`=`u`.`uid`';
		}else{
			$wherestrQuery = '`c`.`status`=0 AND `c`.`city_id`='.$this->config['now_city'].' AND `c`.`uid`=`u`.`uid`';
		}
		$cid = intval($_GET['cid']);
		$fcid = intval($_GET['fcid']);
		$sub3dir = intval($_GET['sub3dir']);
		if($cid){
			$wherestrQuery .= ' AND `c`.`cid`='.$cid.' AND `cc`.`cid`=`c`.`cid`';
		}else if($fcid){
			$wherestrQuery .= ' AND `c`.`fcid`='.$fcid.' AND `cc`.`cid`=`c`.`cid`';
		}else if($sub3dir){
			$wherestrQuery .= ' AND `c`.`sub3dir`='.$sub3dir.' AND `cc`.`cid`=`c`.`sub3dir`';
		}else{
			$wherestrQuery .= ' AND `cc`.`cid`=`c`.`cid`';
		}
		if($type == 'fabu'){
			$wherestrQuery .= ' AND `c`.`uid`='.$this->user_session['uid'];
		}else if($type == 'auditing'){
			$wherestrQuery .= ' AND `c`.`uid`='.$this->user_session['uid'];
		}else if($type == 'collect'){
			$wherestrQuery .= ' AND `uct`.`uid`='.$this->user_session['uid'].' AND `c`.`id`=`uct`.`vid`';
		}
		
		$uid = intval($_GET['uid']);
		if($uid){
			$wherestrQuery .= ' AND `c`.`uid`='.$uid;
		}
		
		if($_GET['hongbao']){
			$wherestrQuery .= ' AND `c`.`redpack_count`>0';
			
			if($_GET['type'] == 'have'){
				$wherestrQuery .= ' AND `c`.`redpack_count`>`c`.`redpack_count_get`';
			}
		}
		
		if($cid && $_GET['opt']){
			$opt = isset($_GET['opt']) ? trim($_GET['opt']) : '';
			$where = $this->analyse_param($opt);
			if (!empty($where)) {
                if ($where['ty'] == 1) {
                    $tmp = explode('-', $where['vv']);
                    if ($tmp['0'] == 0) {
                        $wherestrQuery .= ' AND `c`.' . $where['fd'] . '>=0 AND `c`.' . $where['fd'] . '<=' . $tmp['1'];
                    } elseif ($tmp['1'] == 0) {
                        $wherestrQuery .= ' AND `c`.' . $where['fd'] . '>=' . $tmp['0'];
                    } else {
                        $wherestrQuery .= ' AND `c`.' . $where['fd'] . '<=' . $tmp['1'] . " AND `c`." . $where['fd'] . '>=' . $tmp['0'];
                    }
                } else {
                    $wherestrQuery .= ' AND `c`.' . $where['fd'] . ' LIKE "%' . $where['vv'] . '%"';
                }
            }
		}
		
		if($_GET['keyword']){
			$keyword = safeValue($_GET['keyword']);
			$wherestrQuery .= ' AND `c`.`description` LIKE "%' . $keyword . '%"';
		}
		
		$page = max($_GET['page'],1);
		$limit = (($page-1)*10) . ',10';
		
		/***置顶更新处理***/
		if($page == 1){
			$clearTopTime = S('clearTopTime_classify');
			if(empty($clearTopTime)){
				$topclearWhere = '`endtoptime`<>0 AND `endtoptime`<'.time();
				$topclearData  = array('toptime' => 0, 'topsort' => 0, 'endtoptime'=>0);
				M('Classify_userinput')->where($topclearWhere)->data($topclearData)->save();
				S('clearTopTime_classify',time(),60);
			}
		}
		
		
		// echo $wherestrQuery;die;
		$condition_table = array(C('DB_PREFIX').'classify_userinput'=>'c',C('DB_PREFIX').'classify_category'=>'cc',C('DB_PREFIX').'user'=>'u');
		
		if($type == 'collect'){
			$condition_table[C('DB_PREFIX').'classify_usercollect'] = 'uct';
		}
		
		$list = M('')->field('`c`.*,`cc`.`cid` as cat_id,`cc`.`cat_name`,`u`.`nickname`,`u`.`avatar`')->table($condition_table)->where($wherestrQuery)->order('`c`.`topsort` DESC,`c`.`toptime` DESC,`c`.`updatetime` DESC,`c`.`id` DESC')->limit($limit)->select();
		
		foreach($list as &$value){
			$content = !empty($value['content']) ? unserialize($value['content']) : false;
			$value['content_label'] = array();
			foreach($content as $vv){
				if(is_array($vv['vv'])){
					$value['content_label'] = $vv['vv'];
					break;
				}
			}
		}
		// dump($list);die;
		$this->assign('list',$list);
		
		$this->display();
	}
	public function view(){
		$vid = intval($_GET['id']);
        $vid = $vid > 0 ? $vid : 0;
        $content = false;
        if ($vid > 0) {
			//我有没有收藏此信息
            $database_classify_usercollect = M('Classify_usercollect');
            $classify_usercollect_info = $database_classify_usercollect->where(array('vid'=>$vid,'uid'=>$this->user_session['uid']))->find();
            if(!empty($classify_usercollect_info)){
                $this->assign('classify_usercollect_info' , $classify_usercollect_info);
            }
			
			//收藏此信息的用户列表
			$wherestrQuery = '`cu`.`vid`='.$vid.' AND `cu`.`uid`=`u`.`uid`';
			$usercollect_info_list = M('')->field('`u`.`uid`,`u`.`nickname`,`u`.`avatar`')->table(array(C('DB_PREFIX').'classify_usercollect'=>'cu',C('DB_PREFIX').'user'=>'u'))->where($wherestrQuery)->order('`cu`.`addtime` DESC')->select();
			if(!empty($usercollect_info_list)){
                $this->assign('usercollect_info_list' , $usercollect_info_list);
            }
			
			
            M('Classify_userinput')->where(array('id' => $vid, 'status' => 1))->setInc('views');
            $tmpdata = M('Classify_userinput')->field(true)->where(array('id' => $vid))->find();
            if (!empty($tmpdata)) {
				if($tmpdata['jumpUrl']){
					redirect($tmpdata['jumpUrl']);
                }
                $status = $tmpdata['status'];
                $content = !empty($tmpdata['content']) ? unserialize($tmpdata['content']) : false;
                // dump($content);die;
				$content_label = array();
				foreach($content as $value){
					if(is_array($value['vv'])){
						$content_label = $value['vv'];
						break;
					}
				}
				
				$imgarr = !empty($tmpdata['imgs']) ? unserialize($tmpdata['imgs']) : false;
                $tmpdata['updatetime'] = date('Y-m-d H:i', $tmpdata['updatetime']);
                $category = D('Classify_category');
                $mycategory = $category->field('cid,fcid,pfcid,subdir,cat_name')->where(array('cid' => $tmpdata['cid']))->find();
                // 查询一下 主分类信息
                $cid = intval($mycategory['cid']);
                $fcid = intval($mycategory['fcid']);
                $pfcid = intval($mycategory['$pfcid']);
                // 获取主分类信息
                $classify_id = $cid;
                if ($fcid && !$pfcid) {
                    $classify_id = $fcid;
                } elseif ($fcid && $pfcid) {
                    $classify_id = $pfcid;
                }
                $classify_category_info = M('Classify_category')->where(array('cid'=>$classify_id))->field('reward_type, reward_look')->find();
                $tmpdata['reward_type'] = $classify_category_info['reward_type'];
                $tmpdata['reward_look'] =  round($classify_category_info['reward_look'], 2);
                if (!empty($classify_category_info) && ($classify_category_info['reward_type'] == 3 || $classify_category_info['reward_type'] == 4)) {
                    $uid = (int)$_SESSION['user']['uid'];
                    if ($uid) {
                        $info_pay = M('Reward_order')->where(array('uid'=>$uid,'reward_id'=>$vid, 'status' => 1, 'type' => 2))->find();
                        if ($info_pay) {
                            $tmpdata['reward_type'] = 1;
                        }
                    }
                    if ($tmpdata['uid'] == $uid) {
                        $tmpdata['reward_type'] = 1;
                    }
                }
                $tmpdata['cat_name'] = $mycategory['cat_name'];
                unset($f_category, $mycategory);
                $tmpdata['s_c'] = array();
                if ($tmpdata['sub3dir'] > 0) {
                    $tmpdata['s_c'] = $category->field('cid,fcid,pfcid,subdir,cat_name')->where(array('cid' => $tmpdata['sub3dir']))->find();
                }
                foreach($imgarr as &$v){
                    $v=$this->config['site_url'].$v;
                }
                // $tmpdata['description'] = str_replace(PHP_EOL,'<br>',$tmpdata['description']);
                // $tmpdata['description'] = htmlspecialchars_decode($tmpdata['description'], ENT_QUOTES);
                $tmpdata['otherdesc'] = !empty($tmpdata['otherdesc']) ? htmlspecialchars_decode($tmpdata['otherdesc'], ENT_QUOTES) :'';
                $this->assign('detail', $tmpdata);
                $this->assign('content', $content);
                $this->assign('content_label', $content_label);
                $this->assign('status', $status);
                $this->assign('imglist', $imgarr);
                $this->assign('vid', $vid);
                $this->assign('client_ip', get_client_ip());

				//分类信息订单
                $database_classify_order = D('Classify_order');
                $classify_order_where['paid'] = 1;
                $classify_order_where['classify_userinput_id'] = $_GET['vid'] + 0;
                $classify_order_info = $database_classify_order->where($classify_order_where)->find();
				$this->assign('classify_order_info', $classify_order_info);
				
				//当前用户信息
				$user = D('User')->field(true)->where(array('uid' => $tmpdata['uid']))->find();
				if($_SESSION['openid'] && $user['openid']){	//是否支持联系该用户
					$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
					$im_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key;
					$this->assign('im_url', $im_url . '#group_' . $user['openid']);
				}
                $this->assign('user', $user);
				
				//当前用户发贴总数
				$user_input_count = M('Classify_userinput')->where(array('uid' => $user['uid'], 'status' => 1))->count();
                $this->assign('user_input_count', $user_input_count);
				
				$this->assign('page_title',$this->config['classify_title'].'【'.$tmpdata['cat_name'].'】'.msubstr($tmpdata['title'],0,15));
				
				if(!empty($this->user_session) && $tmpdata['redpack_count'] > 0 && $tmpdata['redpack_count'] - $tmpdata['redpack_count_get'] > 0){
					$get_redpack = M('Classify_redpack_list')->where(array('vid' => $vid,'uid'=>$this->user_session['uid']))->find();
					$this->assign('get_redpack', $get_redpack);
				}
				if($tmpdata['redpack_count_get']){
					$condition_table = array(C('DB_PREFIX').'classify_redpack_list'=>'crl',C('DB_PREFIX').'user'=>'u');
					$condition_where = '`crl`.`vid`='.$vid.' AND `crl`.`uid`=`u`.`uid`';
					$redpack_list = M()->field('`crl`.`money`,`u`.`nickname`,`u`.`avatar`')->table($condition_table)->where($condition_where)->order('`crl`.`red_id` ASC')->limit(5)->select();
					$this->assign('redpack_list', $redpack_list);
				}
				
				// if($tmpdata['redpack_count']){
					// $this->assign('page_title',$this->config['classify_title'].'红包来啦！'.$tmpdata['title']);
				// }
				
				$this->display();
            }else{
				$this->redirect(U('Classify/index'));
			}
        }else{
			$this->redirect(U('Classify/index'));
		}
	}
	public function collectOpt() {
        $vid = intval($_GET['vid']);
        if ($this->user_session['uid'] > 0 && ($vid > 0)) {
            $usercollectDb = M('Classify_usercollect');
            $tmp = $usercollectDb->where(array('uid' => $this->user_session['uid'], 'vid' => $vid))->find();
            if (empty($tmp)) {
				if($_GET['type'] == 'cancle'){
					die('error|还没有收藏');
				}else{
					$flag = $usercollectDb->add(array('uid' => $this->user_session['uid'], 'vid' => $vid, 'addtime' => time()));
					if ($flag){
						die('success|收藏成功');
					}
				}
            }else{
				if($_GET['type'] == 'cancle'){
					$usercollectDb->where(array('id' => $tmp['id']))->delete();
					die('success|取消收藏成功');
				}else{
					die('error|已经收藏过了');
				}
			}
        }
        die('error|请先登录');
    }
	public function updateView(){
		$_GET['ids'] = trim($_GET['ids'],',');
		$ids = explode(',',$_GET['ids']);
		$ids = array_unique($ids);
		if(empty($ids)){
			exit('');
		}else if(count($ids) == 1){
			$where = array('id'=>$ids[0]);
		}else{
			$where = array('id'=>array('in',$ids));
		}
		M('Classify_userinput')->where($where)->setInc('views');
	}
	public function shares(){
		$vid = intval($_GET['vid']);
		$where = array('id'=>$vid);
		M('Classify_userinput')->where($where)->setInc('shares');
	}
	public function getReplyList(){
		$vid = intval($_GET['vid']);
		$rid = intval($_GET['rid']);
		if($rid){
			$wherestrQuery = '`r`.`reply_id`='.$rid.' AND `r`.`uid`=`u`.`uid`';
		}else{
			$wherestrQuery = '`r`.`vid`='.$vid.' AND `r`.`uid`=`u`.`uid`';
		}
		
		$page = max($_GET['page'],1);
		$limit = (($page-1)*10) . ',10';
		

		$list = M('')->field('`r`.*,`u`.`nickname`,`u`.`avatar`')->table(array(C('DB_PREFIX').'classify_reply'=>'r',C('DB_PREFIX').'user'=>'u'))->where($wherestrQuery)->order('`r`.`reply_id` DESC')->limit($limit)->select();

		$this->assign('list',$list);
		
		$this->display();
	}
	public function replyTo(){
		$vid = intval($_GET['vid']);
		if(empty($vid)){
			exit('error|异常错误');
		}
		if(empty($this->user_session['uid'])){
			exit('error|请先登录');
		}
		
		$data['vid'] = $vid;
		$data['uid'] = $this->user_session['uid'];
		$data['content'] = trim($_POST['comment']);
		$data['add_time'] = time();
		
		if(empty($data['content'])){
			exit('error|评论请填写点字');
		}
		$reply_uid = intval($_POST['touid']);
		if(!empty($reply_uid)){
			$reply_user = D('User')->get_user($reply_uid);
			if(!empty($reply_user)){
				$data['reply_uid'] = $reply_uid;
				$data['reply_nickname'] = $reply_user['nickname'];
			}
		}
		if($reply_id = M('Classify_reply')->data($data)->add()){
			exit('success|回复成功||'.$reply_id);
		}else{
			exit('error|回复异常，请重试');
		}
	}
	public function hongbao(){
		$this->assign('page_title',$this->config['classify_title'].'抢红包');
		$this->display();
	}
	public function hongbao_list(){
		$this->view();
	}
	public function hongbao_getlist(){
		$vid = intval($_GET['id']);
		$page = max($_GET['page'],1);
		$limit = (($page-1)*10) . ',10';
		
		$condition_table = array(C('DB_PREFIX').'classify_redpack_list'=>'crl',C('DB_PREFIX').'user'=>'u');
		$condition_where = '`crl`.`vid`='.$vid.' AND `crl`.`uid`=`u`.`uid`';
		$redpack_list = M()->field('`crl`.`get_time`,`crl`.`money`,`u`.`nickname`,`u`.`avatar`')->table($condition_table)->where($condition_where)->order('`crl`.`red_id` ASC')->limit($limit)->select();
		$this->assign('redpack_list', $redpack_list);
					
		$this->display();
	}
	public function fabu(){
		$tmp_category = M('Classify_category')->field('`cid`,`fcid`,`cat_pic`,`cat_name`')->where(array('cat_status'=>1,'pfcid'=>0))->order('`subdir` ASC,`cat_sort` DESC')->select();
		$classify_first_category = array();
		$classify_second_category = array();
		foreach($tmp_category as $key => $value){
			if(empty($value['fcid']) && $value['cat_pic'] != ''){
				$value['cat_pic'] = $this->config['upload_site_url'].'/upload/system/'.$value['cat_pic'];
				$classify_first_category[] = $value;
			}else{
				$classify_second_category[] = $value;
			}
		}
		$this->assign('classify_first_category',$classify_first_category);
		$this->assign('classify_second_category',$classify_second_category);
		$this->assign('page_title',$this->config['classify_title'].'发布信息');
		$this->display();
	}
	public function fabu_detail(){
		if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
		
		$cid = intval($_GET['cid']);
        $fcid = intval($_GET['fcid']);
        if (($cid > 0) && ($fcid > 0)) {
            $cat_field = false;
            $database_Classify_category = D('Classify_category');
            $tmp = $database_Classify_category->where(array('cid' => $cid, 'fcid' => $fcid))->find();
			$subdir = $this->get_Subdirectory($tmp['cid'], 2, 3);
            $ftmp = $database_Classify_category->where(array('cid' => $fcid))->find();
            if (empty($tmp)) {
                $tmp = $ftmp;
            }else{
                $tmp_cat_field = !empty($tmp['cat_field']) ? unserialize($tmp['cat_field']) : false;
                if (!$tmp_cat_field) {
                    $tmp['cat_field']=$ftmp['cat_field'];
                }
            }
            if ($tmp) {
                $cat_field = !empty($tmp['cat_field']) ? unserialize($tmp['cat_field']) : false;
                if ($cat_field) {
                    foreach ($cat_field as $kk => $vv) {
                        if (isset($vv['use_field']) && ($vv['use_field'] == 'area')) {
                            $get_area_list = D('Area')->get_area_list();
                            $new_areas = array();
                            if ($get_area_list) {
                                foreach ($get_area_list as $vv) {
                                    $new_areas[$vv['area_id']] = $vv['area_name'];
                                }
                            }
                            $cat_field[$kk]['opt'] = $new_areas;
                        }
                    }
                }

            $database_area = D('Area');
            $id = $_GET['adress_id'];
            if(cookie('user_address') === '0' || cookie("user_address") == "") {
                $now_adress = D('User_adress')->get_adress($this->user_session['uid'], $id);
                if ($now_adress) {
                    $this->assign('now_adress', $now_adress);

                    $province_list = $database_area->get_arealist_by_areaPid(0);
                    $this->assign('province_list',$province_list);

                    $city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
                    $this->assign('city_list', $city_list);

                    $area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
                    $this->assign('area_list', $area_list);
                } else {
                    $now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
                    $this->assign('now_city_area',$now_city_area);

                    $province_list = $database_area->get_arealist_by_areaPid(0);
                    $this->assign('province_list',$province_list);

                    $city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
                    $this->assign('city_list',$city_list);

                    $area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
                    $this->assign('area_list',$area_list);
                }
            } else {
                $cookie = json_decode($_COOKIE['user_address'], true);
                $now_adress = $cookie;
                $now_adress['default'] = $now_adress['defaul'];
                $now_adress['adress_id'] = $now_adress['id'];
                $this->assign('now_adress', $now_adress);
                $province_list = $database_area->get_arealist_by_areaPid(0);
                $this->assign('province_list',$province_list);

                $city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
                $this->assign('city_list', $city_list);

                $area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
                $this->assign('area_list', $area_list);
            }

            $params = $_GET;
            unset($params['adress_id']);
            $this->assign('params',$params);

            } else {
                $this->error_tips('分类不存在！', U('Classify/SelectSub', array('cid' => $cid)));
            }
            //print_r($cat_field);
            $this->assign('subdir', $subdir);
            $this->assign('cid', $cid);
            $this->assign('fcid', $fcid);
            $this->assign('fabuset', $tmp);
            $this->assign('fabuTmp', $ftmp);
            $this->assign('catfield', $cat_field);
			
			$this->assign('page_title',$this->config['classify_title'].'发布'.$tmp['cat_name'].'信息');
			
            $this->display();
        } else {
            redirect(U('fabu'));
        }
	}
	
	/* 个人主页 */
	public function member(){
		$uid = intval($_GET['uid']);
		if(empty($uid)){
			$this->error_tips('用户id丢了，请重试。');
		}
		$nowUser = D('User')->get_user($uid);
		
		if($this->user_session['uid'] && $uid != $this->user_session['uid']){
			$condition['uid'] = $uid;
			$condition['visit_uid'] = $this->user_session['uid'];
			$visit = M('Classify_membervisitor')->where($condition)->find();
			if(empty($visit)){
				$condition['time'] = time();
				M('Classify_membervisitor')->data($condition)->add();
			}
		}
		
		$this->assign('nowUser',$nowUser);
		$this->assign('page_title',$this->config['classify_title'].$nowUser['nickname']);
		$this->display();
	}
	public function member_visit(){
		
		$uid = intval($_GET['uid']);
		if(empty($uid)){
			$this->error_tips('用户id丢了，请重试。');
		}
		$nowUser = D('User')->get_user($uid);
		$this->assign('nowUser',$nowUser);
		
		$this->assign('page_title',$this->config['classify_title'].$nowUser['nickname']);
		$this->display();
	}
	public function about(){
		//近三条分类信息公告
		$intro  = D('Classify_appintro')->order('`id` DESC')->limit(20)->select();
		$this->assign('intro',$intro);
		
		$this->assign('page_title',$this->config['classify_title'].'帮助中心');
		
		$this->display();
	}
	public function my(){
		if (empty($this->user_session)) {
			if($this->is_app_browser){
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				$this->error_tips('请先进行登录！',U('Login/index',$location_param));
			}else{
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				redirect(U('Login/index',$location_param));
			}
        }
		
		//近三条分类信息公告
		$intro  = D('Classify_appintro')->order('`id` DESC')->limit(3)->select();
		$this->assign('intro',$intro);
		
		//浏览数统计、发布、分享	1小时的缓存
		$classify_count = $this->classify_count();
		$this->assign('classify_count',$classify_count);
		
		//联系客服
		if($this->config['open_kefu_url'] && $services = M('Customer_service')->where(array('mer_id' => $this->config['kefu_mer_id']))->select()) {
            $kefu_url = $this->config['site_url'].'/wap.php?c=My&a=concact_kefu&mer_id='.$this->config['kefu_mer_id'];
			$this->assign('kefu_url',$kefu_url);
		}
		
		//我的浏览量
		$my_view_count = M('Classify_userinput')->where(array('uid'=>$this->user_session['uid']))->sum('views');
		$this->assign('my_view_count',$my_view_count);
		
		//我发布的数量
		$my_count = M('Classify_userinput')->where(array('uid'=>$this->user_session['uid']))->count();
		$this->assign('my_count',$my_count);
		
		$this->assign('page_title',$this->config['classify_title'].'个人中心');
		$this->display();
	}
	
	public function getMemberVisitList(){
		$uid = intval($_GET['uid']);
		if(empty($uid)){
			die();
		}
		
		$page = max($_GET['page'],1);
		$limit = (($page-1)*10) . ',10';
		
		$wherestrQuery = '`v`.`uid`='.$uid.' AND `v`.`visit_uid`=`u`.`uid`';
		$list = M('')->field('`v`.*,`u`.`nickname`,`u`.`avatar`')->table(array(C('DB_PREFIX').'classify_membervisitor'=>'v',C('DB_PREFIX').'user'=>'u'))->where($wherestrQuery)->order('`v`.`pigcms_id` DESC')->limit($limit)->select();
		$this->assign('list',$list);
		
		$this->display();
	}
	
	public function collect(){
		if(empty($this->user_session)){
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
		
		$this->assign('page_title',$this->config['classify_title'].'收藏列表');
		$this->display();
	}
	
	public function my_fabu(){
		if(empty($this->user_session)){
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
		
		$this->assign('page_title',$this->config['classify_title'].'我的发布');
		$this->display();
	}
	
	private function get_Subdirectory($cid, $subdir, $m = 2) {
        $Classify_categoryDb = M('Classify_category');
        $Subdirectory = array();
        $where = false;
        if ($m == 2) {
            $where = array('fcid' => $cid, 'subdir' => 2, 'cat_status' => 1);
        } elseif ($m == 3) {
            if ($subdir == 1) {
                $where = array('pfcid' => $cid, 'subdir' => 3, 'cat_status' => 1);
            } else {
                $where = array('fcid' => $cid, 'subdir' => 3, 'cat_status' => 1);
            }
        }
        if ($where) {
            $Subdirectory = $Classify_categoryDb->field(true)->where($where)->order('`cat_sort` DESC,`cid` ASC')->select();
        }
        return $Subdirectory;
    }
	
	public function del(){
		if (empty($this->user_session)) {
           exit('error|需要先登录|'.U('Login/index'));
        }
		$vid = intval($_GET['id']);
		$tmpdata = M('Classify_userinput')->field(true)->where(array('id' => $vid))->find();
		if(empty($tmpdata)){
			exit('error|该信息不存在');
		}
		if($tmpdata['uid'] != $this->user_session['uid']){
			exit('error|该信息不是您发布的');
		}
		if(M('Classify_userinput')->where(array('id'=>$vid))->delete()){
			exit('success|删除成功');
		}else{
			exit('error|删除失败，请重试');
		}
	}
	
	public function edit(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            exit();
        }

        $id = $_GET['id'] + 0;
        if(empty($id)){
            $this->error_tips('传递参数有误！');
        }

        $database_classify_userinput = D('Classify_userinput');
        $classify_condition['id'] = $id;
        $classify_userinput_info = $database_classify_userinput->where($classify_condition)->find();
		$cid = $classify_userinput_info['cid'];
        $fcid = $classify_userinput_info['fcid'];
        if (($cid > 0) && ($fcid > 0)) {
            $cat_field = false;
            $database_Classify_category = D('Classify_category');
            $tmp = $database_Classify_category->where(array('cid' => $cid, 'fcid' => $fcid))->find();
            $subdir = $this->get_Subdirectory($tmp['cid'], 2, 3);
            if (empty($tmp)) {
                $tmp = $database_Classify_category->where(array('cid' => $fcid))->find();
            }elseif(empty($tmp['cat_field'])){
                $tmp1 = $database_Classify_category->where(array('cid' => $fcid))->find();
                $tmp['cat_field']=$tmp1['cat_field'];
            }
            if ($tmp) {
                $cat_field = !empty($tmp['cat_field']) ? unserialize($tmp['cat_field']) : false;
                if ($cat_field) {
                    foreach ($cat_field as $kk => $vv) {
                        if (isset($vv['use_field']) && ($vv['use_field'] == 'area')) {
                            $get_area_list = D('Area')->get_area_list();
                            $new_areas = array();
                            if ($get_area_list) {
                                foreach ($get_area_list as $vv) {
                                    $new_areas[$vv['area_id']] = $vv['area_name'];
                                }
                            }
                            $cat_field[$kk]['opt'] = $new_areas;
                        }
                    }
                }
            } else {
                $this->error_tips('分类不存在！', U('Classify/SelectSub', array('cid' => $cid)));
            }
		}
		$classify_userinput_info['imgs'] = unserialize($classify_userinput_info['imgs']);
		$classify_userinput_info['content'] = unserialize($classify_userinput_info['content']);
		
		$classify_userinput_info['description'] = str_replace('<br/>',PHP_EOL,$classify_userinput_info['description']);
		$classify_userinput_info['description'] = str_replace('<br />',PHP_EOL,$classify_userinput_info['description']);
		
		$database_area = D('Area');
            $id = $_GET['adress_id'];
            if(cookie('user_address') === '0' || cookie("user_address") == "") {
                $now_adress = D('User_adress')->get_adress($this->user_session['uid'], $id);
                if ($now_adress) {
                    $this->assign('now_adress', $now_adress);

                    $province_list = $database_area->get_arealist_by_areaPid(0);
                    $this->assign('province_list',$province_list);

                    $city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
                    $this->assign('city_list', $city_list);

                    $area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
                    $this->assign('area_list', $area_list);
                } else {
                    $now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
                    $this->assign('now_city_area',$now_city_area);

                    $province_list = $database_area->get_arealist_by_areaPid(0);
                    $this->assign('province_list',$province_list);

                    $city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
                    $this->assign('city_list',$city_list);

                    $area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
                    $this->assign('area_list',$area_list);
                }
            } else {
                $cookie = json_decode($_COOKIE['user_address'], true);
                $now_adress = $cookie;
                $now_adress['default'] = $now_adress['defaul'];
                $now_adress['adress_id'] = $now_adress['id'];
                $this->assign('now_adress', $now_adress);
                $province_list = $database_area->get_arealist_by_areaPid(0);
                $this->assign('province_list',$province_list);

                $city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
                $this->assign('city_list', $city_list);

                $area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
                $this->assign('area_list', $area_list);
            }
            $params = $_GET;
            unset($params['adress_id']);
            $this->assign('params',$params);
			$this->assign('subdir', $subdir);
			$this->assign('cid', $cid);
			$this->assign('fcid', $fcid);
			$this->assign('fabuset', $tmp);
			$this->assign('catfield', $cat_field);
			$this->assign('classify_userinput_info' , $classify_userinput_info);
			
			// dump($classify_userinput_info);
			
			$this->assign('page_title',$this->config['classify_title'].'修改'.$tmp['cat_name'].'信息');
			
			$this->display();
    }
	
	/*
	 *图片上传
	 * 
	 */
    public function ajaxImgUpload(){
        if($_FILES['file']['error'] != 4){
			$image = D('Image')->handle($this->user_session['uid'], 'classify', 2);
			if(!$image['error']){
				echo 'success|'.$image['url']['file'];
			} else {
				echo 'error|'.$image['message'];
			}
        }
    }
	
	public function refresh(){
		if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
		$vid = intval($_GET['id']);
		$tmpdata = M('Classify_userinput')->field(true)->where(array('id' => $vid))->find();
		if(empty($tmpdata)){
			$this->error_tips('该信息不存在');
		}
		if(time() - $tmpdata['updatetime'] < 600){
			$this->error_tips('每次刷新要间隔十分钟');
		}
		
		$classify_refresh_money = floatval($this->config['classify_refresh_money']);
		if($classify_refresh_money > 0){
			$userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('uid, now_money')->find();
			// 如果余额不足提示去充值
			$now_money = floatval($userInfo['now_money']);
			if($classify_refresh_money > $now_money){
				$need_money = $classify_refresh_money - $now_money;
				$this->error_tips('余额不足，还需要支付'.$need_money.'元',U('My/recharge',array('money'=>$need_money)));
			}
			$dec_money = D('User')->user_money($this->user_session['uid'], $classify_refresh_money, "刷新分类信息【".$tmpdata['title']."】扣除余额 ". $classify_refresh_money ." 元");
			if($dec_money['error_code']){
				$this->error_tips($dec_money['msg']);
			}
		}
		
		$data['updatetime'] = time();
		M('Classify_userinput')->data($data)->where(array('id' => $vid))->save();
		$this->success_tips('刷新成功');
	}
	
	public function settop(){
		if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
		$vid = intval($_GET['id']);
		$tmpdata = M('Classify_userinput')->field(true)->where(array('id' => $vid))->find();
		if(empty($tmpdata)){
			$this->error_tips('该信息不存在');
		}
		$digtype = intval($_GET['digtype']);
		$classify_refresh_money = $this->config['classify_settop_day_money'] * $digtype;
		if(empty($classify_refresh_money)){
			$this->error_tips('暂未设定置顶费用，无法置顶');
		}
		if(time() - $tmpdata['toptime'] < 600){
			$this->error_tips('每次置顶要间隔十分钟');
		}
		if($classify_refresh_money > 0){
			$userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('uid, now_money')->find();
			// 如果余额不足提示去充值
			$now_money = floatval($userInfo['now_money']);
			if($classify_refresh_money > $now_money){
				$need_money = $classify_refresh_money - $now_money;
				$this->error_tips('余额不足，还需要支付'.$need_money.'元',U('My/recharge',array('money'=>$need_money)));
			}
			$dec_money = D('User')->user_money($this->user_session['uid'], $classify_refresh_money, "置顶分类信息【".$tmpdata['title']."】".$digtype."天 扣除余额 ". $classify_refresh_money ." 元");
			if($dec_money['error_code']){
				$this->error_tips($dec_money['msg']);
			}
		}
		
		$start_time = $tmpdata['endtoptime'] > time() ? $tmpdata['endtoptime'] : time();
		$data['topsort'] = $data['endtoptime'] = $start_time + ($digtype*86400);
		$data['toptime'] = time();
		
		M('Classify_userinput')->data($data)->where(array('id' => $vid))->save();
		$this->success_tips('置顶成功');
	}
	public function redpack(){
		if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
		$vid = intval($_GET['id']);
		$redpack_money = intval($_GET['redpack_money']);
		$tmpdata = M('Classify_userinput')->field(true)->where(array('id' => $vid))->find();
		if(empty($tmpdata)){
			$this->error_tips('该信息不存在');
		}
		if($tmpdata['redpack_count'] > 0){
			$this->error_tips('该信息已经发过红包，只能发一次');
		}
		
		if(!in_array($redpack_money,array(1,5,10,50))){
			$this->error_tips('红包金额不正确');
		}
		switch($redpack_money){
			case 1:
				$redpack_count = 5;
				break;
			case 5:
				$redpack_count = 8;
				break;
			case 10:
				$redpack_count = 20;
				break;
			case 50:
				$redpack_count = 100;
				break;
		}
		$rand_section = $this->rand_section($redpack_money/$redpack_count/4,$redpack_money/$redpack_count*2,$redpack_count,$redpack_money);
		

		$userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('uid, now_money')->find();
		// 如果余额不足提示去充值
		$now_money = floatval($userInfo['now_money']);
		if($redpack_money > $now_money){
			$need_money = $redpack_money - $now_money;
			$this->error_tips('余额不足，还需要支付'.$redpack_money.'元',U('My/recharge',array('money'=>$need_money)));
		}
		$dec_money = D('User')->user_money($this->user_session['uid'], $redpack_money, "分类信息【".$tmpdata['title']."】发红包（".$redpack_money."元".$redpack_count."包） 扣除余额 ". $redpack_money ." 元");
		if($dec_money['error_code']){
			$this->error_tips($dec_money['msg']);
		}
		
		$data['redpack_money'] = $redpack_money;
		$data['redpack_count'] = $redpack_count;
		
		M('Classify_userinput')->data($data)->where(array('id' => $vid))->save();
		
		$dataArr = array();
		foreach($rand_section as $value){
			$dataArr[] = array(
				'vid' 	=> $vid,
				'money' => $value,
			);
		}
		M('Classify_redpack_list')->addAll($dataArr);
		
		$this->success_tips('发红包成功');
	}
	/** 获取区间内随机红包（符合正态分布）
     * @param $min 红包最小值
     * @param $max 红包最大值
     * @param $num 红包个数
     * @param $total 红包金额
     * @return array
     */
	public function rand_section($min,$max,$num,$total){
        $data = array();
        if ($min * $num > $total) {
            return array();
        }
        if($max*$num < $total){
            return array();
        }
        while ($num >= 1) {
            $num--;
            $kmix = max($min, $total - $num * $max);
            $kmax = min($max, $total - $num * $min);
            $kAvg = $total / ($num + 1);
            //获取最大值和最小值的距离之间的最小值
            $kDis = min($kAvg - $kmix, $kmax - $kAvg);
            //获取0到1之间的随机数与距离最小值相乘得出浮动区间，这使得浮动区间不会超出范围
            $r = ((float)(rand(1, 10000) / 10000) - 0.5) * $kDis * 2;
            $k = sprintf("%.2f", $kAvg + $r);
            $total -= $k;
            $data[] = $k;
        }
        return $data;
    }
	
	public function focus_hongbao(){
		if(empty($this->user_session)) {
			exit('error|请先登录|'.U('Login/index'));
        }
		$vid = intval($_GET['id']);
		if(empty($vid)) {
			exit('error|内容不存在');
        }
		$tmpdata = M('Classify_userinput')->field(true)->where(array('id' => $vid))->find();
		if(empty($tmpdata)) {
			exit('error|内容不存在');
        }
		if(M('Classify_redpack_list')->where(array('vid' => $vid,'uid'=>$this->user_session['uid']))->find()){
			exit('error|您已经抢过了');
		}else{
			exit('success|success');
		}
	}
	public function get_hongbao(){
		if(empty($this->user_session)) {
			exit('error|请先登录|'.U('Login/index'));
        }
		$vid = intval($_GET['id']);
		if(empty($vid)) {
			exit('error|内容不存在');
        }
		$tmpdata = M('Classify_userinput')->field(true)->where(array('id' => $vid))->find();
		if(empty($tmpdata)) {
			exit('error|内容不存在');
        }
		if(M('Classify_redpack_list')->where(array('vid' => $vid,'uid'=>$this->user_session['uid']))->find()){
			exit('error|您已经抢过了');
		}else{
			$redpack = M('Classify_redpack_list')->where(array('vid' => $vid,'uid'=>'0'))->order('`red_id` ASC')->find();
			if(M('Classify_redpack_list')->where(array('red_id'=>$redpack['red_id'],'uid'=>'0'))->data(array('uid'=>$this->user_session['uid'],'get_time'=>time()))->save()){
				M('Classify_userinput')->where(array('id' => $vid))->setInc('redpack_count_get');
				M('Classify_userinput')->where(array('id' => $vid))->setInc('redpack_money_get',$redpack['money']);
				D('User')->add_money($this->user_session['uid'], $redpack['money'], "分类信息【".$tmpdata['title']."】抢红包");
				exit('success|'.$redpack['money']);
			}else{
				exit('error|失败请重试');
			}
		}
	}
}