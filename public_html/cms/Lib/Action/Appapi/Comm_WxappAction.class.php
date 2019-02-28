<?php
class Comm_WxappAction extends BaseAction{
    public function login(){
        if($_POST['ticket']){
            $info = ticket::get($_POST['ticket'],'wxcapp', true);
            if ($info && $info['uid']){
                $now_user = $this->autologin('uid',$info['uid']);
                if(!empty($now_user)){
                    $return = array(
                        'ticket'    =>	$_POST['ticket'],
                        'user'      =>	$now_user,
                    );
                    $this->returnCode(0, $return);
                }else{
                    $this->returnCode(0,array('emptyUser'=>true));
                }
            }else{
                $this->returnCode(0,array('emptyUser'=>true));
            }
        }

        $appid = $this->config['pay_wxapp_group_appid'];
        $appsecret = $this->config['pay_wxapp_group_appsecret'];


        import('ORG.Net.Http');
        $http = new Http();

        $return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$_POST['code'].'&grant_type=authorization_code', array());

        import('@.ORG.aeswxapp.wxBizDataCrypt');

        $pc = new WXBizDataCrypt($appid, $return['session_key']);
        $errCode = $pc->decryptData($_POST['encryptedData'],$_POST['iv'],$data);
        $jsonrt = json_decode($data,true);
        /*优先使用 unionId 登录*/
        if(!empty($jsonrt['unionId'])){
            $now_user = $this->autologin('union_id',$jsonrt['unionId'],$jsonrt['openId']);
        }else{
            /*再次使用 openId 登录*/
            $now_user = $this->autologin('group_openid',$jsonrt['openId']);
        }
		$jsonrt['avatarUrl'] = str_replace('http://wx.qlogo.cn','https://thirdwx.qlogo.cn',$jsonrt['avatarUrl']);
        if(empty($now_user)){
            $data_user = array(
                'group_openid' 	=> $jsonrt['openId'],
                'union_id' 	=> ($jsonrt['unionId'] ? $jsonrt['unionId'] : ''),
                'nickname' 	=> $jsonrt['nickName'],
                'sex' 		=> $jsonrt['gender'],
                'province' 	=> $jsonrt['province'],
                'city' 		=> $jsonrt['city'],
                'avatar' 	=> $jsonrt['avatarUrl'],
                'is_follow' => 1,
                'source' 	=> 'wxcapp'
            );
            $reg_result = D('User')->autoreg($data_user);
            if(!$reg_result['error_code']){
                $now_user = $this->autologin('group_openid',$jsonrt['openId']);
            }
        }else{
			if($now_user['avatar'] != $jsonrt['avatarUrl']){
				D('User')->save_user($now_user['uid'],'avatar',$jsonrt['avatarUrl']);
				D('Community_info')->change_group_avatar(0,$now_user['uid']);
			}
		}

        if(!empty($now_user)){
            $ticket = ticket::create($now_user['uid'],'wxcapp', true);
            $return = array(
                'ticket'=>	$ticket['ticket'],
                'user'	=>	$now_user,
            );
            $this->returnCode(0,$return);
        }else{
            $this->returnCode(0,array('emptyUser'=>true));
        }
    }
    protected function autologin($field,$value,$openid = ''){
        $result = D('User')->autologin($field,$value);
        $now_user = array();
        if(empty($result['error_code'])){
            if($field == 'union_id' && empty($result['user']['group_openid'])){
                $condition_user['union_id'] = $value;
                D('User')->where($condition_user)->data(array('group_openid'=>$openid))->save();
                $result['user']['group_openid'] = $openid;
            }
            $result['user']['showPhone'] = substr_replace($result['user']['phone'], '****', 3, 4);
            $now_user = $result['user'];
            // 判断是否登录云通讯，没有登录就去登陆一下
            if ($now_user['qcloud_tmp'] == 1) {
                $database_community_info = D('Community_info');
                $msg = $database_community_info->qcloud_im_group_add($now_user['uid'], $now_user['nickname'], $now_user['avatar']);
                if (!empty($msg) && $msg['ActionStatus'] == 'OK') {
                    // 将用户的云通讯登录信息状态修改
                    $user_id = D('User')->where(array('uid' => $now_user['uid']))->data(array('qcloud_tmp' => 2))->save();
                    if (empty($user_id)) {
                        // 登录失败返回登录失败信息
                        $this->returnCode(0, array('error_info' => true, 'qcloud_login' => false, 'ticket' => $_POST['ticket'], 'user' => $now_user));
                    } else {
                        // 判断为第一次登陆，创建体验群
                        if (C('config.community_user_experience') == 0) {
                            $this->user_experience_community($now_user);
                        }
                        $this->returnCode(0, array('error_info' => false, 'qcloud_login' => true, 'ticket' => $_POST['ticket'], 'user' => $now_user));
                    }
                } else {
                    // 登录失败返回登录失败信息
                    $this->returnCode(0, array('error_info' => true, 'qcloud_login' => false, 'ticket' => $_POST['ticket'], 'user' => $now_user));
                }
            }
        }
        return $now_user;
    }

    // 用户体验组创建
    private function user_experience_community($user_info) {
        // 处理添加的数据
        $database_community = D('Community_info');
        $database_community_join = M('Community_join');
        // 一个用户只创建一个体验群
        $community_info = M('Community_info')->where(array('community_type' => 2, 'community_uid' => $user_info['uid']))->find();
        if ($community_info) return true;

        $community_name = C('config.community_user_experience_name'); // 去配置中的群名称
        $data['community_name'] = $community_name ? $community_name : '小猪CMS';
        // 入群方式 默认 允许任何人加群
        $data['group_mode'] = 2;
        // 加群是否审核 默认 加群不需要审核
        $data['is_check'] = 1;
        // 是否收费 默认 加群不收费
        $data['is_charge'] = 1;
        $data['charge_money'] = 0.00;
        // 默认 关闭我在本群的昵称功能
        $data['is_nickname'] = 0;
        $data['community_uid'] = $user_info['uid'];
        $data['group_owner_uid'] = $user_info['uid'];
        $data['member_number'] = 1;
        $data['add_time'] = time();
        // 创建类型 体验创建
        $data['community_type'] = 2;
        D()->startTrans();
        $addInfo = M('Community_info')->data($data)->add();
        if ($addInfo) {
            $database_User = D('User');
            $add_user = $database_User->field(true)->where(array('uid' => $user_info['uid']))->find();
            $join = array(
                'community_id' => $addInfo,
                'group_owner' => 2,
                'add_source' => 0,
                'add_uid' => $user_info['uid'],
                'comm_nickname' => $add_user['nickname'],
                'add_status' => 3,
                'add_time' => time()
            );
            $join_id = $database_community_join->data($join)->add();
            if ($join_id) {
                // 创建群成功 同步创建到云通讯
                $uid = $user_info['uid'];
                $group_id = $addInfo;
                $ret_group = $database_community->qcloud_create_group($uid, $group_id, $data['community_name']);
                if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                    // 自动生成对应群消息
                    $param = array();
                    $param['community_id'] = $addInfo;
                    $param['type'] = 'comm_auto';
                    D('Community_info')->add_plan($param);
                    D()->commit();
                    //生成群头像
                    $database_community->create_comm_avatar($addInfo);
                } else {
                    D()->rollback();
                }
            } else {
                D()->rollback();
            }
        }
        return true;
    }
}
?>