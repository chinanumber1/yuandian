<?php
class InvitationAction extends BaseAction
{
	private $_lat = 0;
	
	private $_long = 0;
	
	private $_im_appid = '';
	
	private $_im_appkey = '';
		
		
	private $from_user_id = 0;
	
	private $from_user_name = '';
	
	private $from_user_avatar = '';
	
	private $no_header = '0';
	
	private $im_url = '';
	
	public function __construct()
	{
		parent::__construct();
		if(empty($this->user_session)){
			$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
			redirect(U('Login/index',$location_param));
		}
		if (empty($_SESSION['openid'])) {
			$this->error_tips('该功能只能在微信中使用！', U('Login/index'));
		}
// 		$_SESSION['openid'] = 'onfo6t5WPe6wJswql3ljRX9aeEUA';
		
		
// 		$this->_im_appid = $this->config['im_appid'];
// 		$this->_im_appkey = $this->config['im_appkey'];
		
// 		$this->from_user_id = $this->user_session['uid'];
// 		$this->from_user_name = $this->user_session['nickname'];
// 		$this->from_user_avatar = $this->user_session['avatar'];
// 		$this->no_header = '0';
// 		$this->back_url = $this->config['site_url'] . '/wap.php?c=Invitation&a=datelist';
// 		$code = md5($this->_im_appid.$this->from_user_id.$this->from_user_name.$this->from_user_avatar.$this->no_header.$this->back_url.$this->_im_appkey);
		
		$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
		
		$this->im_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key;
				
// 		$im_url = 'http://im.maopan.com/my.php?app_id='.$this->_im_appid.'&from_user_id='.$this->from_user_id.'&from_user_name='.urlencode($this->from_user_name).'&from_user_avatar='.urlencode($this->from_user_avatar).'&no_header='.$this->no_header.'&back_url='.urlencode($this->back_url).'&code='.$code;
		$this->assign('my_im', $this->im_url);		
		if ($long_lat = D('User_long_lat')->getLocation($_SESSION['openid'])) {
			$this->_long = $long_lat['long'];
			$this->_lat = $long_lat['lat'];
		}
	}
	
	/**
	 * 发现的功能
	 * 例举出最新的几位发起的约会
	 */
	public function index()
	{
		$mensql = "SELECT i.pigcms_id, u.avatar, u.uid, u.nickname FROM ". C('DB_PREFIX') . "user as u INNER JOIN ". C('DB_PREFIX') . "invitation as i ON i.uid=u.uid WHERE i.status=0 AND i.invite_time>" . time() . " AND u.sex=1 AND u.status=1 ORDER BY i.pigcms_id DESC limit 0,6";
		$womensql = "SELECT i.pigcms_id, u.avatar, u.uid, u.nickname FROM ". C('DB_PREFIX') . "user as u INNER JOIN ". C('DB_PREFIX') . "invitation as i ON i.uid=u.uid WHERE i.status=0 AND i.invite_time>" . time() . " AND u.sex=2 AND u.status=1 ORDER BY i.pigcms_id DESC limit 0,6";
		
		$mode = new Model();
		$men = $mode->query($mensql);
		$women = $mode->query($womensql);
		if (count($men) < 6) {
			$uids = array();
			foreach ($men as $m) {
				$uids[] = $m['uid'];
			}
			$l = 6 - count($men);
			if ($uids) {
				$othermen = D('User')->field('uid, nickname, avatar')->where(array('sex' => 1, 'uid' => array('not in', $uids),'openid'=>array('neq','')))->order('uid DESC')->limit('0,' . $l)->select();
			} else {
				$othermen = D('User')->field('uid, nickname, avatar')->where(array('sex' => 1,'openid'=>array('neq','')))->order('uid DESC')->limit('0,' . $l)->select();
			}
			$men = $men ? array_merge($men, $othermen) : $othermen;
			$i = count($men) + 1;
			for(; $i < 7; $i++) {
				$men[] = array('avatar' => $this->config['site_url'] . '/tpl/Wap/static/images/jr.png', 'uid' => 0, 'nickname' => '');
			}
		}
		if (count($women) < 6) {
			$uids = array();
			foreach ($women as $m) {
				$uids[] = $m['uid'];
			}
			$l = 6 - count($women);
			if ($uids) {
				$otherwomen = D('User')->field('uid, nickname, avatar')->where(array('sex' => 2, 'uid' => array('not in', $uids),'status'=>1,'openid'=>array('neq','')))->order('uid DESC')->limit('0,' . $l)->select();
			} else {
				$otherwomen = D('User')->field('uid, nickname, avatar')->where(array('sex' => 2,'status'=>1,'openid'=>array('neq','')))->order('uid DESC')->limit('0,' . $l)->select();
			}
			$women = $women ? array_merge($women, $otherwomen) : $otherwomen;
			$i = count($women) + 1;
			for(; $i < 7; $i++) {
				$women[] = array('avatar' => $this->config['site_url'] . '/tpl/Wap/static/images/jr.png', 'uid' => 0, 'nickname' => '');
			}
		}
		$count1 = D('User')->where(array('sex' => 1,'status'=>1,'openid'=>array('neq','')))->count();
		$this->assign('count1', $count1);
		$count2 = D('User')->where(array('sex' => 2,'status'=>1,'openid'=>array('neq','')))->count();
		$this->assign('count2', $count2);
		
		$this->assign('men', $men);
		$this->assign('women', $women);
		$this->display();
	}
	
	public function more()
	{
		$sex = isset($_GET['sex']) ? intval($_GET['sex']) : 2;
		
		$users = D('User')->field('uid, nickname, avatar')->where(array('sex' => $sex,'openid'=>array('neq','')))->order('uid DESC')->limit('0, 24')->select();
		$count = D('User')->where(array('sex' => $sex))->count();
		$this->assign('count', $count);
		$this->assign('user_list', $users);
		if ($sex == 1) {
			$this->assign('title', '高富帅');
		} else {
			$this->assign('title', '女神');
		}
		$this->assign('sex', $sex);
		$this->display();
	}
    public function ajaxmore()
    {
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        $pagesize = isset($_GET['pagesize']) && intval($_GET['pagesize']) > 1 ? intval($_GET['pagesize']) : 12;
		$sex = isset($_GET['sex']) ? intval($_GET['sex']) : 2;
        $start = ($page-1) * $pagesize;
		
		$users = D('User')->field('uid, nickname, avatar')->where(array('sex' => $sex,'openid'=>array('neq','')))->order('uid DESC')->limit("{$start}, {$pagesize}")->select();
		$count = D('User')->where(array('sex' => $sex,'openid'=>array('neq','')))->count();
		
        exit(json_encode(array('data' => $users, 'count' => $count)));
    }


    public function search_ajaxmore()
    {
        $sex = isset($_GET['sex']) ? intval($_GET['sex']) : '';
        // 如果存在昵称查询，添加昵称查询条件
        $nickname = $_GET['nickname'] ? $_GET['nickname'] : '';

        $where = array();
        $where['openid'] =  array('neq','');
        if ($sex) {
            $where['sex'] = $sex;
        }
        if ($nickname) {
            $where['nickname'] = array('like', '%'.$nickname.'%');;
        }

        $users = D('User')->field('uid, nickname, avatar, sex')->where($where)->order('uid DESC')->select();
        $count = D('User')->where($where)->count();
        if (!$users) {
            $users = array();
        }

        exit(json_encode(array('data' => $users, 'count' => $count)));
    }
	
	/**
	 * 约会列表
	 */
	public function datelist()
	{
		if (isset($_GET['activity_type'])) {
			$data = D('Invitation')->get_list($this->_lat, $this->_long, 1, 10, intval($_GET['activity_type']));
			$this->assign('activity_type', $activity_type);
		} else {
			$data = D('Invitation')->get_list($this->_lat, $this->_long, 1, 10);
		}
		$this->assign('date_list', $data['data']);
		$this->assign('count', $data['total']);
		$this->display();
	}
	
	/**
	 * 约会列表
	 */
	public function ajaxlist()
	{
		$page = isset($_GET['page']) && intval($_GET['page']) > 1 ? intval($_GET['page']) : 2;
		$pagesize = isset($_GET['pagesize']) && intval($_GET['pagesize']) > 1 ? intval($_GET['pagesize']) : 10;
		if (isset($_GET['activity_type']) && $_GET['activity_type'] != '') {
			$res = D('Invitation')->get_list($this->_lat, $this->_long, $page, $pagesize, intval($_GET['activity_type']));
		} else {
			$res = D('Invitation')->get_list($this->_lat, $this->_long, $page, $pagesize);
		}
		exit(json_encode($res));
	}
	
	/**
	 * 发起约会
	 */
	public function release_date()
	{
		
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		if ($merchant_store = M("Merchant_store")->where(array('store_id' => $store_id))->find()) {
			$this->assign('store', $merchant_store);
		}
		$week = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
		$date = array(array(date('Y-m-d'), date('n月j日 ') . $week[date('w')]));
		for ($i = 1; $i < 16; $i++) {
			$date[] = array(date('Y-m-d', strtotime("+{$i} day")), date('n月j日 ', strtotime("+{$i} day")) . $week[date('w', strtotime("+{$i} day"))]);
		}
		$this->assign('date', $date);
		$this->display();
	}
	
	/**
	 * 保存约会
	 */
	public function save_date()
	{
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;

		if (!($merchant_store = M("Merchant_store")->where(array('store_id' => $store_id))->find())) {
			exit(json_encode(array('error_code' => 1, 'msg' => '不存在的门店')));
		}
		if ($invitation = D('Invitation')->field(true)->where(array('uid' => $this->user_session['uid'], 'status' => 0, 'invite_time' => array('gt', time())))->find()) {
			exit(json_encode(array('error_code' => 1, 'msg' => '您已经有一个约会了，不能再发布约会了')));
		}
		$minute = isset($_POST['minute']) ? $_POST['minute'] : '';
		$hour = isset($_POST['hour']) ? $_POST['hour'] : '';
		$date = isset($_POST['date']) ? $_POST['date'] : '';
		$data['invite_time'] = strtotime($date.$hour.$minute.'00');
		
		$data['store_id'] = $merchant_store['store_id'];
		$store_image_class = new store_image();
		$merchant_store['images'] = $store_image_class->get_allImage_by_path($merchant_store['pic_info']);
		$data['store_image'] = $merchant_store['images'] ? array_shift($merchant_store['images']) : '';
		$data['address'] = $merchant_store['name'];
		$data['obj_sex'] = isset($_POST['obj_sex']) ? intval($_POST['obj_sex']) : 0;
		$data['pay_type'] = isset($_POST['pay_type']) ? intval($_POST['pay_type']) : 0;
		$data['activity_type'] = isset($_POST['activity_type']) ? intval($_POST['activity_type']) : 0;
		$data['note'] = isset($_POST['note']) ? htmlspecialchars($_POST['note']) : '';
		$data['uid'] = $this->user_session['uid'];
		$data['long'] = $this->_long;
		$data['lat'] = $this->_lat;
		
		if ($pigcms_id = D('Invitation')->add($data)) {
			exit(json_encode(array('error_code' => 0)));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => '发布失败')));
		}
	}
	
	/**
	 * 约会详情
	 */
	public function info()
	{
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		if ($invitation = D('Invitation')->field(true)->where(array('pigcms_id' => $pigcms_id))->find()) {
			$today = strtotime(date('Y-m-d')) + 86400;
			$tomorrow = $today + 86400;
			$lastday = $tomorrow + 86400;
			$invitation['status'] = $invitation['invite_time'] < time() ? 1 : $invitation['status'];
			$invitation['juli'] = ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(($this->_lat * PI() / 180- $invitation['lat'] * PI()/180)/2),2)+COS($this->_lat *PI()/180)*COS($invitation['lat']*PI()/180)*POW(SIN(($this->_long *PI()/180- $invitation['long']*PI()/180)/2),2)))*1000);
			$invitation['juli'] = $invitation['juli'] > 1000 ? number_format($invitation['juli']/1000, 1) . 'km' : ($invitation['juli'] < 100 ? '<100m' : $invitation['juli'] . 'm');
			$invitation['invite_time'] = $today > $invitation['invite_time'] ? '今天 ' . date('H:i', $invitation['invite_time']) : ($tomorrow > $invitation['invite_time'] ? '明天  ' . date('H:i', $invitation['invite_time']) : ($lastday > $invitation['invite_time'] ? '后天  ' . date('H:i', $invitation['invite_time']) : date('m-d H:i', $invitation['invite_time'])));
			$user = D('User')->field(true)->where(array('uid' => $invitation['uid']))->find();
			$user['birthday'] && $user['birthday'] != '0000-00-00' && $user['age'] = date('Y') - date('Y', strtotime($user['birthday']));
			if($user['age']){
				$user['age'] = ($user['age'] > 100 || $user['age'] < 0) ? '保密' : $user['age'] . '岁';
			}else{
				$user['age'] = '保密';
			}
			$store = D('Merchant_store')->field(true)->where(array('store_id' => $invitation['store_id']))->find();
			
			$looks = D('')->field('`u`.*')->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'invitation_look'=>'l'))->where('`l`.`uid`=`u`.`uid` AND `l`.`invid`=' . $invitation['pigcms_id'])->limit('0, 20')->select();
			
			if ($this->user_session['uid'] != $invitation['uid']) {
				if ($look = D('Invitation_look')->field('invid')->where(array('uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id']))->find()) {
					D('Invitation_look')->where(array('uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id']))->save(array('avatar' => $this->user_session['avatar'], 'dateline' => time()));
				} else {
					D('Invitation_look')->add(array('avatar' => $this->user_session['avatar'], 'uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id'], 'dateline' => time()));
					D('Invitation')->where(array('pigcms_id' => $pigcms_id))->setInc('look_num', 1);	
				}
			}
			
			$this->assign('looks', $looks);
			$this->assign('store', $store);
			$this->assign('invitation', $invitation);
			$this->assign('user', $user);
			
			if ($this->user_session['uid'] == $user['uid']) {
// 				$back_url = $this->config['site_url'] . '/wap.php?c=Invitation&a=info&pigcms_id='.$pigcms_id;
				$signs = D('')->field('`u`.*')->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'invitation_sign'=>'s'))->where('`s`.`uid`=`u`.`uid` AND `s`.`invid`=' . $invitation['pigcms_id'])->limit('0, 20')->select();
				foreach ($signs as &$v) {
// 					$to_user_id = $v['uid'];
// 					$to_user_name = $v['nickname'];
// 					$to_user_avatar = $v['avatar'];
// 					$code = md5($this->_im_appid.$this->from_user_id.$this->from_user_name.$this->from_user_avatar.$to_user_id.$to_user_name.$to_user_avatar.$this->no_header.$back_url.$this->_im_appkey);
					$v['birthday'] != '0000-00-00' && $v['age'] = date('Y') - date('Y', strtotime($v['birthday']));
					$v['age'] = isset($v['age']) ? (($v['age'] > 100 || $v['age'] < 0) ? '保密' : $v['age'] . '岁') : '保密';
					
// 					$v['im_url'] = 'http://im.maopan.com/?app_id='.$this->_im_appid.'&from_user_id='.$this->from_user_id.'&from_user_name='.urlencode($this->from_user_name).'&from_user_avatar='.urlencode($this->from_user_avatar).'&to_user_id='.$to_user_id.'&to_user_name='.urlencode($to_user_name).'&to_user_avatar='.urlencode($to_user_avatar).'&no_header='.$this->no_header.'&back_url='.urlencode($back_url).'&code='.$code;
					$v['im_url'] = $this->im_url . '#group_' . $v['openid'];
				}
				$this->assign('is_edit', 1);
				$this->assign('signs', $signs);
				$this->display('myinfo');
			} else {
// 				$to_user_id = $user['uid'];
// 				$to_user_name = $user['nickname'];
// 				$to_user_avatar = $user['avatar'];
// 				$back_url = $this->config['site_url'] . '/wap.php?c=Invitation&a=info&pigcms_id='.$pigcms_id;
// 				$code = md5($this->_im_appid.$this->from_user_id.$this->from_user_name.$this->from_user_avatar.$to_user_id.$to_user_name.$to_user_avatar.$this->no_header.$back_url.$this->_im_appkey);
// 				$im_url = 'http://im.maopan.com/?app_id='.$this->_im_appid.'&from_user_id='.$this->from_user_id.'&from_user_name='.urlencode($this->from_user_name).'&from_user_avatar='.urlencode($this->from_user_avatar).'&to_user_id='.$to_user_id.'&to_user_name='.urlencode($to_user_name).'&to_user_avatar='.urlencode($to_user_avatar).'&no_header='.$this->no_header.'&back_url='.urlencode($back_url).'&code='.$code;
				
				
				$this->assign('im_url', $this->im_url . '#group_' . $user['openid']);
				$sign = D('Invitation_sign')->field('invid')->where(array('uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id']))->find();
				$this->assign('is_edit', 0);
				$this->assign('sign', $sign);
				$this->display();
			}
			
		} else {
			$this->error_tips('没有相关的约会信息请确认！', U('Invitation/datelist'));
			exit();
		}
		
	}
	
	/**
	 * 报名约会
	 */
	public function sign()
	{
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		if ($invitation = D('Invitation')->field(true)->where(array('pigcms_id' => $pigcms_id))->find()) {
			if ($invitation['obj_sex'] && $invitation['obj_sex'] != $this->user_session['sex']) {
				if ($invitation['obj_sex'] == 1) {
					exit(json_encode(array('error_code' => 1, 'msg' => '该约会限男生报名', 'status' => 0)));
				} elseif ($invitation['obj_sex'] == 2) {
					exit(json_encode(array('error_code' => 1, 'msg' => '该约会限妹子报名', 'status' => 0)));
				}
			}
			if ($this->user_session['uid'] != $invitation['uid']) {
				if ($sign = D('Invitation_sign')->field('invid')->where(array('uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id']))->find()) {
					D('Invitation_sign')->where(array('uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id']))->delete();
					D('Invitation')->where(array('pigcms_id' => $pigcms_id))->setDec('sign_num', 1);
					exit(json_encode(array('error_code' => 0, 'msg' => '放弃约会', 'status' => 0)));
				} else {
					D('Invitation_sign')->add(array('avatar' => $this->user_session['avatar'], 'uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id'], 'dateline' => time()));
					D('Invitation')->where(array('pigcms_id' => $pigcms_id))->setInc('sign_num', 1);
					exit(json_encode(array('error_code' => 0, 'msg' => '约会成功', 'status' => 1)));
				}
			}
		}
		exit(json_encode(array('error_code' => 1, 'msg' => '操作失败')));
	}
	
	/**
	 * 我发起的约会
	 */
	public function mydate()
	{
		$invitations = D('Invitation')->field(true)->where(array('uid' => $this->user_session['uid']))->order('pigcms_id DESC')->select();
		$today = strtotime(date('Y-m-d')) + 86400;
		$tomorrow = $today + 86400;
		$lastday = $tomorrow + 86400;
		foreach ($invitations as &$i) {
			$i['status'] = $i['invite_time'] < time() ? 1 : $i['status'];
			$i['invite_time'] = $today > $i['invite_time'] ? '今天 ' . date('H:i', $i['invite_time']) : ($tomorrow > $i['invite_time'] ? '明天  ' . date('H:i', $i['invite_time']) : ($lastday > $i['invite_time'] ? '后天  ' . date('H:i', $v['invite_time']) : date('m-d H:i', $i['invite_time'])));
		}
		$this->assign('invitations', $invitations);
		$this->display();
	}
	
	/**
	 * 我报名的约会
	 */
	public function mysign()
	{
		$invitation_signs = D('Invitation_sign')->field('invid')->where(array('uid' => $this->user_session['uid']))->order('invid DESC')->select();
		$invids = $pre = '';
		foreach ($invitation_signs as $is) {
			$invids .= $pre . $is['invid'];
			$pre = ',';
		}
		$today = strtotime(date('Y-m-d')) + 86400;
		$tomorrow = $today + 86400;
		$lastday = $tomorrow + 86400;
		if ($invids) {
			$sql = "SELECT i.*, u.* FROM ". C('DB_PREFIX') . "user as u INNER JOIN ". C('DB_PREFIX') . "invitation as i ON i.uid=u.uid WHERE i.pigcms_id IN ({$invids}) ORDER BY i.pigcms_id DESC, u.sex DESC";
			$mode = new Model();
			$res = $mode->query($sql);
			foreach ($res as &$v) {
				$v['_time'] = date('Y-m-d H:i', $v['invite_time']);
				$v['juli'] = ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(($this->_lat * PI() / 180- $v['lat'] * PI()/180)/2),2)+COS($this->_lat *PI()/180)*COS($v['lat']*PI()/180)*POW(SIN(($this->_long *PI()/180- $v['long']*PI()/180)/2),2)))*1000);
				$v['juli'] = $v['juli'] > 1000 ? number_format($v['juli']/1000, 1) . 'km' : ($v['juli'] < 100 ? '<100m' : $v['juli'] . 'm');
				$v['invite_time'] = $today > $v['invite_time'] ? '今天 ' . date('H:i', $v['invite_time']) : ($tomorrow > $v['invite_time'] ? '明天  ' . date('H:i', $v['invite_time']) : ($lastday > $v['invite_time'] ? '后天  ' . date('H:i', $v['invite_time']) : date('m-d H:i', $v['invite_time'])));
				$v['birthday'] && $v['age'] = date('Y') - date('Y', strtotime($v['birthday']));
			}
			$this->assign('date_list', $res);
		}
		$this->display();
	}
	
	/**
	 * 查看个人信息
	 */
	public function userinfo()
	{
		$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
		if ($user = D('User')->field(true)->where(array('uid' => $uid))->find()) {
			if ($invitation = D('Invitation')->field(true)->where(array('uid' => $uid, 'status' => 0, 'invite_time' => array('gt', time())))->find()) {
				$today = strtotime(date('Y-m-d')) + 86400;
				$tomorrow = $today + 86400;
				$lastday = $tomorrow + 86400;
				$invitation['invite_time'] = $today > $invitation['invite_time'] ? '今天 ' . date('H:i', $invitation['invite_time']) : ($tomorrow > $invitation['invite_time'] ? '明天  ' . date('H:i', $invitation['invite_time']) : ($lastday > $invitation['invite_time'] ? '后天  ' . date('H:i', $invitation['invite_time']) : date('m-d H:i', $invitation['invite_time'])));
				
				if ($this->user_session['uid'] != $user['uid']) {//增加查看量
					if ($look = D('Invitation_look')->field('invid')->where(array('uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id']))->find()) {
						D('Invitation_look')->where(array('uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id']))->save(array('avatar' => $this->user_session['avatar'], 'dateline' => time()));
					} else {
						D('Invitation_look')->add(array('avatar' => $this->user_session['avatar'], 'uid' => $this->user_session['uid'], 'invid' => $invitation['pigcms_id'], 'dateline' => time()));
						D('Invitation')->where(array('pigcms_id' => $invitation['pigcms_id']))->setInc('look_num', 1);
					}
				}
				$this->assign('invitation', $invitation);
			}
			if ($this->user_session['uid'] == $user['uid']) {
// 				$back_url = $this->config['site_url'] . '/wap.php?c=Invitation&a=userinfo&uid='.$user['uid'];
// 				$code = md5($this->_im_appid.$this->from_user_id.$this->from_user_name.$this->from_user_avatar.$this->no_header.$back_url.$this->_im_appkey);
// 				$im_url = 'http://im.maopan.com/my.php?app_id='.$this->_im_appid.'&from_user_id='.$this->from_user_id.'&from_user_name='.urlencode($this->from_user_name).'&from_user_avatar='.urlencode($this->from_user_avatar).'&no_header='.$this->no_header.'&back_url='.urlencode($back_url).'&code='.$code;
				$im_url = $this->im_url;
				$this->assign('is_edit', 1);
			} else {
// 				$to_user_id = $user['uid'];
// 				$to_user_name = $user['nickname'];
// 				$to_user_avatar = $user['avatar'];
// 				$back_url = $this->config['site_url'] . '/wap.php?c=Invitation&a=userinfo&uid='.$user['uid'];
// 				$code = md5($this->_im_appid.$this->from_user_id.$this->from_user_name.$this->from_user_avatar.$to_user_id.$to_user_name.$to_user_avatar.$this->no_header.$back_url.$this->_im_appkey);
// 				$im_url = 'http://im.maopan.com/?app_id='.$this->_im_appid.'&from_user_id='.$this->from_user_id.'&from_user_name='.urlencode($this->from_user_name).'&from_user_avatar='.urlencode($this->from_user_avatar).'&to_user_id='.$to_user_id.'&to_user_name='.urlencode($to_user_name).'&to_user_avatar='.urlencode($to_user_avatar).'&no_header='.$this->no_header.'&back_url='.urlencode($back_url).'&code='.$code;
				
				$im_url = $this->im_url . '#group_' . $user['openid'];
				$this->assign('is_edit', 0);
			}
			$this->assign('im_url', $im_url);
			if($user['birthday'] != '0000-00-00'){
				$birthdayArr = explode('-',$user['birthday']);
				$user['age'] = date('Y') - $birthdayArr[0];
			}
			$user['age'] = isset($user['age']) ? (($user['age'] > 100 || $user['age'] < 0) ? '保密' : $user['age'] . '岁') : '保密';
			
			$occupation = D('Occupation')->field(true)->where(array('pigcms_id' => $user['occupation']))->find();
			$this->assign('occupation', $occupation);
			$this->assign('user', $user);
			$this->display();
		} else {
			$this->error_tips('不存在的用户信息请确认！', U('Invitation/datelist'));
		}
	}
	
	/**
	 * 编辑个人信息
	 */
	public function editinfo()
	{
		$occupations = D('Occupation')->field(true)->select();
		$this->assign('occupations', $occupations);
		$user = D('User')->field(true)->where(array('uid' => $this->user_session['uid']))->find();
		$birthday = explode('-', $user['birthday']);
		$user['year'] = $birthday[0];
		$user['month'] = $birthday[1];
		$user['day'] = $birthday[2];
		$this->assign('info', $user);
		$this->display();
	}
	
	/**
	 * 保存个人信息
	 */
	public function saveinfo()
	{
		$this->user_session['message'] = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';
		$this->user_session['nickname'] = isset($_POST['nickname']) ? htmlspecialchars($_POST['nickname']) : '';
		$this->user_session['avatar'] = isset($_POST['avatar']) ? htmlspecialchars($_POST['avatar']) : '';
		$this->user_session['sex'] = isset($_POST['sex']) ? intval($_POST['sex']) : 1;
		$year = isset($_POST['year']) ? intval($_POST['year']) : '1990';
		$month = isset($_POST['month']) ? intval($_POST['month']) : '1';
		$day = isset($_POST['day']) ? intval($_POST['day']) : '1';
		$this->user_session['occupation'] = isset($_POST['occupation']) ? intval($_POST['occupation']) : '1';
		$this->user_session['birthday'] = $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT) . '-' . str_pad($day, 2, 0, STR_PAD_LEFT);
		$this->user_session['last_time'] = time();
		if (D('User')->where(array('uid' => $this->user_session['uid']))->save($this->user_session)) {
			session('user',$this->user_session);
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok', 'uid' => $this->user_session['uid'])));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => '编辑失败')));
		}
	}
	
	/**
	 * 取消约会
	 */
	public function cancel()
	{
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		if (D('Invitation')->where(array('pigcms_id' => $pigcms_id, 'uid' => $this->user_session['uid']))->save(array('status' => 1))) {
			exit(json_encode(array('error_code' => 0)));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => 'oh,sorry!服务器开小差了。')));
		}
	}
	
	/**
	 * 判断是否已经发起了约会
	 */
	public function isrelease()
	{
		if ($invitation = D('Invitation')->field('pigcms_id')->where("`uid`='{$this->user_session['uid']}' AND `status`=0 AND `invite_time`>" . time())->find()) {
			exit(json_encode(array('error_code' => 1, 'pigcmsid' => $invitation['pigcms_id'])));
		} else {
			exit(json_encode(array('error_code' => 0)));
		}
	}
}
?>