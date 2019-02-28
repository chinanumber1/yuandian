<?php
class Community_infoModel extends Model
{
	/*得到社群信息*/
	public function get_community_info($uid,$field='group_owner_uid')
	{
		$condition_info[$field] = $uid;
		$community_info = $this->field(true)->where($condition_info)->find();
		return $community_info;
	}

    /*得到社群列表*/
    public function get_community_list($where, $pageSize = 6, $page = 1)
    {
        if(!$where){
            return false;
        }
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total = $this->where($where)->order('`add_time` DESC')->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = $this->field(true)->where($where)->order('`add_time` DESC')->limit($firstRow.','.$pageSize)->select();
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = $this->field(true)->where($where)->order('`add_time` DESC')->select();
            return [
                'list' => $list
            ];
        }
    }


    /**
     * 云通讯 注册用户
     * @access public
     * @param $uid  int 用户uid 例： 1001
     * @param $nick  string 用户昵称 例： 太阳
     * @param $avatar  string 用户头像
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_im_group_add($uid, $nick, $avatar)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $user = 'user_' . $uid;
        $req_body = array(
            'Identifier' => $user,
            'Nick' 		 => $nick,
            'FaceUrl' 	 => $avatar,
        );
        $ret = $qcloud_im->api->comm_rest("im_open_login_svc", "account_import", $req_body);
        return $ret;
    }


    /**
     * 云通讯用户创建群
     * @access public
     * @param $uid  int 用户uid
     * @param $group_id  int 用户自定义群组ID
     * @param $group_name  string 群名称
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0,
     *   "GroupId": "community_88"
     * }
     */
    public function qcloud_create_group($uid, $group_id, $group_name)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $user = 'user_' . $uid;
        $group = 'community_' . $group_id;
        $req_body = array(
            "Owner_Account"  => $user, // 群主的UserId（选填）
            "Type"           => "Public", // 群组类型：Private/Public/ChatRoom/AVChatRoom/BChatRoom（必填）
            "GroupId"        => $group, //用户自定义群组ID（选填）
            "Name"           => $group_name   // 群名称（必填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "create_group", $req_body);
        return $ret;
    }


    /**
     * 云通讯 修改群组基础资料
     * @access public
     * @param $group_id  int 用户自定义群组ID
     * @param $group_name  string 群名称
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_modify_group_base_info($group_id, $group_name)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $req_body = array(
            "GroupId"        => $group, //用户自定义群组ID（选填）
            "Name"           => $group_name   // 群名称（必填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "modify_group_base_info", $req_body);
        return $ret;
    }


    /**
     * 云通讯用户加入群
     * @access public
     * @param $group_id  int 用户自定义群组ID
     * @param $add_uid  int  加群用户uid
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0,
     *   "MemberList": [
     *      {
     *           "Member_Account": "user_88",
     *          "Result": 1   // 加人结果：0为失败；1为成功；2为已经是群成员
     *      }
     *   ]
     * }
     */
    public function qcloud_add_group_member($group_id, $add_uid)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $member_account = 'user_' . $add_uid;
        $group = 'community_' . $group_id;
        $req_body = array(
            "GroupId"        => $group, //用户自定义群组ID（选填）
            "MemberList"     => array(  // 一次最多添加500个成员
                array(
                    'Member_Account' => $member_account   // 要添加的群成员ID（必填）
                )
            )
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "add_group_member", $req_body);
        // 我们暂时只会出现一个人加群，故处理为取第一条返回信息
        if (!empty($ret) && !empty($ret['MemberList'])) {
            $ret['accout'] = reset($ret['MemberList']);
            unset($ret['MemberList']);
        }
        return $ret;
    }


    /**
     * 云通讯 删除群组成员
     * @access public
     * @param $group_id  int 用户自定义群组ID
     * @param $silence  int 是否静默删人。0：非静默删人；1：静默删人。不填该字段默认为 0。
     * @param $memberToDel_account  array
     * [
     *    'user_888',
     *    'user_999'
     * ]
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_delete_group_member($group_id, $memberToDel_account, $silence = 0)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));
        $group = 'community_' . $group_id;
        $req_body = array(
            "GroupId"                 => $group, // 用户自定义群组ID（选填)
            "Silence"                 => $silence, // 是否静默删人。0：非静默删人；1：静默删人。不填该字段默认为 0。
            "MemberToDel_Account"     => $memberToDel_account
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "delete_group_member", $req_body);
        return $ret;
    }


    /**
     * 云通讯 修改群成员资料 暂时主要是群昵称
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @param $uid  int   被操作用户id
     * @param $nameCard  string   昵称
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_modify_group_member_info($group_id, $uid, $nameCard)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $user = 'user_' . $uid;
        $req_body = array(
            "GroupId"                 => $group, //用户自定义群组ID（选填）
            "Member_Account"     => $user,   //  要操作的群成员（必填）
            "NameCard"     => $nameCard   //  群名片（选填）及群昵称
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "modify_group_member_info", $req_body);
        return $ret;
    }


    /**
     * 云通讯 解散群组
     * @access public
     * @param $group_id  string 用户自定义群组ID 群组解散之后将无法恢复，请谨慎调用该接口。
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_destroy_group($group_id)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $req_body = array(
            "GroupId"                 => $group //用户自定义群组ID（选填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "destroy_group", $req_body);
        return $ret;
    }


    /**
     * 云通讯 查询用户在群组中的身份
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @param $user_account  array
     * [
     *    'user_999',
     *    'user_8888'
     * ]
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0,
     *    "UserIdList": [
     *       {
     *          "Member_Account": "user_999",
     *          "Role": "Owner"  // 身份：Owner/Admin/Member/NotMember
     *       },
     *       {
     *          "Member_Account": "user_999",
     *          "Role": "Member"
     *       }
     *    ]
     * }
     */
    public function qcloud_get_role_in_group($group_id, $user_account)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $req_body = array(
            "GroupId"                 => $group, //用户自定义群组ID（选填）
            "User_Account"     => $user_account   //  要操作的群成员（必填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "get_role_in_group", $req_body);
        return $ret;
    }


    /**
     * 云通讯 批量禁言和取消禁言
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @param $shutUpTime int 禁言时间 0 为取消禁言  单位为秒
     * @param $members_account  array
     * [
     *    'user_999',
     *    'user_8888'
     * ]
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_forbid_send_msg($group_id, $members_account, $shutUpTime)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $req_body = array(
            "GroupId"                 => $group, //用户自定义群组ID（选填）
            "Members_Account"     => $members_account,   //  要操作的群成员（必填）
            "ShutUpTime"                 => $shutUpTime //用户自定义群组ID（选填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "forbid_send_msg", $req_body);
        return $ret;
    }


    /**
     * 云通讯 用户在群组中发送普通消息 (活动和投票及公告消息)
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @param $uid  int  用户ID
     * @param $msg_body  array
     * [
     *    {
     *      "MsgType": "TIMTextElem", // 文本(暂时我们统一走文本)
     *      "MsgContent": {
     *          "Text": "" // 活动处理成json{type: 'activity', 'activity_id': '', 'title': ''}
     *                     // 投票处理成json{type: 'vote', 'vote_id': '', 'title': ''}
     *                     // 公告处理成json{type: 'notice', 'notice_id': '', 'title': ''}
     *                     // 前端消息处理成json{type: 'senMsg', 'content': '我是新人，请大家多多关照[face:][:D][:face]'}  [face:][:face]之间为表情
     *       }
     *   },
     *    {
     *      "MsgType": "TIMFaceElem", // 表情
     *      "MsgContent": {
     *           "Index": 6,
     *           "Data": "abc\u0000\u0001",
     *       }
     *   }
     * ]
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0,
     *   "MsgTime": 1497249503, // 消息发送的时间戳，对应后台server时间。
     *   "MsgSeq": 1  // 消息序列号，唯一标示一条消息。
     * }
     */
    public function qcloud_send_group_msg($group_id, $msg_body, $uid)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $user = 'user_' . $uid;
        $random = rand(1,999999999);  // 随机取数

        $req_body = array(
            "GroupId"          => $group, // 用户自定义群组ID（选填）
            "From_Account"     => $user, //指定消息发送者（选填）
            "Random"           => $random, // 随机数字，五分钟数字相同认为是重复消息
            "OnlineOnlyFlag"   => 0,   // 表示只在线下发(只有在线群成员才能收到)，不存离线及漫游
            "MsgBody"          => $msg_body,   //  要操作的群成员（必填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "send_group_msg", $req_body);
        return $ret;
    }


    /**
     * 云通讯 在群组中发送系统通知
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @param $content  string  系统通知消息
     * @param $toMembers_account  array
     * [
     *    'user_88',
     *    'user_99'
     * ]
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_send_group_system_notification($group_id, $content, $toMembers_account = array())
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $req_body = array(
            "GroupId" => $group, // 用户自定义群组ID（选填）
            "Content" => $content, // 系统通知内容
            "ToMembers_Account" => $toMembers_account // 接收者群成员列表，不填或为空表示全员下发
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "send_group_system_notification", $req_body);
        return $ret;
    }


    /**
     * 云通讯 转让群组
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @param $new_owner_uid  int  新群主ID
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_change_group_owner($group_id, $new_owner_uid)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $newOwner_account = 'user_' . $new_owner_uid;
        $req_body = array(
            "GroupId"          => $group, // 用户自定义群组ID（选填）
            "NewOwner_Account" => $newOwner_account  // 新群主ID（必填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "change_group_owner", $req_body);
        return $ret;
    }


    /**
     * 云通讯 删除指定用户发送的消息
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @param $uid  int 被删除消息的发送者ID
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     * }
     */
    public function qcloud_delete_group_msg_by_sender($group_id, $uid)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $message = array(
            'MsgSeq' => $mid
        );
        $msgSeqList = array();
        $msgSeqList[] = $message;
        $req_body = array(
            "GroupId"          => $group, // 用户自定义群组ID（选填）
            "MsgSeqList" => $msgSeqList  // 被删除消息的发送者ID（必填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "group_msg_recall", $req_body);
        return $ret;
    }



    /**
     * 云通讯 删除指定用户发送的消息
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @param $mid  int 被撤销的消息的消息ID
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0
     *       "RecallRetList":[
     *            {
     *              "MsgSeq":100,
     *              "RetCode":10030
     *            },
     *           {
     *              "MsgSeq":101,
     *              "RetCode":10030
     *           }
     *        ]
     * }
     *
     */
    public function qcloud_delete_group_msg_recall($group_id, $mid)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));

        $group = 'community_' . $group_id;
        $msgSeqList = array(
            'MsgSeq' => $mid
        );
        $req_body = array(
            "GroupId"          => $group, // 用户自定义群组ID（选填）
            "MsgSeqList" => $msgSeqList  // 被删除消息的发送者ID（必填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "group_msg_recall", $req_body);
        return $ret;
    }


    /**
     * 云通讯 获取群组成员详细资料
     * @access public
     * @param $group_id  string 用户自定义群组ID
     * @return array
     * {
     *   "ActionStatus":"OK",
     *   "ErrorInfo":"",
     *   "ErrorCode":0,
     *   "MemberInfoFilter": [
     *       {
     *          "Member_Account": "bob",
     *          "Role": "Owner",
     *          "JoinTime": 1425976500, // 入群时间
     *          "MsgSeq": 1233,
     *          "MsgFlag": "AcceptAndNotify",
     *          "LastSendMsgTime": 1425976500, // 最后一次发消息的时间
     *          "ShutUpUntil": 1431069882, // 禁言截至时间（秒数）
     *          "AppMemberDefinedData": [ //群成员自定义字段
     *                        {
     *                            "Key": "MemberDefined1",
     *                            "Value": "ModifyDefined1"
     *                         },
     *                        {
     *                            "Key": "MemberDefined2",
     *                            "Value": "ModifyDefined2"
     *                        }
     *          ]
     *          }
     *    ]
     * }
     */
    public function qcloud_get_group_member_info($group_id)
    {
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'));
        $group = 'community_' . $group_id;
        $req_body = array(
            "GroupId"          => $group, // 用户自定义群组ID（选填）
        );
        $ret = $qcloud_im->api->comm_rest("group_open_http_svc", "get_group_member_info", $req_body);
		
        return $ret;
    }

    /*
     * 群头像改变写入计划任务
     * $community_id  活动id
     * $uid  string  array 修改头像的uid
     * */
    public function change_group_avatar($community_id,$uid=0){
        if (!$community_id&&!$uid) {
            return array('erroe_code'=>true,'msg'=>'参数不能全为空');
        }
        $param = array();
        $param['type'] = 'comm_avatar';
        $param['rand_number'] = $_SERVER['REQUEST_TIME'];
        $add_task = false;
        if ($community_id) { // 加群 退群 创建群 群主转让
			$param['community_id'] = $community_id;
            //判斷是否前9個人头像改变       
            $community_info = $this->get_community_info($community_id,'community_id');
            if ($community_info['avatar_uids']) { // 有头像
                $avatar_uids = explode(',' , $community_info['avatar_uids']);
				
                 // 是生成头像的用户或者小于9人 加入任务
                if ($uid) {
                    if (is_array($uid)) { //多人
                        $edit_uid = array_intersect($uid,$avatar_uids);
                        $modify = $edit_uid ? true : false;
                    }else{ // 单人
                        $modify = in_array($uid, $avatar_uids) ? true : false;
                    }
                    if (count($avatar_uids)<9 || $modify) {
                        $add_task = true;
                    }
                }
            }else{ //没有头像 加入任务
                $add_task = true;
            }
        } else { // 没有群id 用户修改头像
            // 查询该用户所在所有群
            if (is_array($uid)) {
                return array('erroe_code'=>true,'msg'=>'参数uid不能为数组');
            }
            $where = '`ci`.`status`=1 AND`cj`.`add_status`=3 AND `cj`.`add_uid`='.$uid;
            $comm_list = D('Community_join')->get_community_info_join($where,0);
            if ($comm_list) {
                $community_ids = '';
                foreach ($comm_list['list'] as $key => $value) {
                    if ($value['avatar_uids']) {
                        $avatar_uids = explode(',',$value['avatar_uids']);
                        if (in_array($uid, $avatar_uids)) {
                            $community_ids .= $value['community_id'] . ',';
                        }
                    } else {
                        $community_ids .= $value['community_id'] . ',';
                    }
                }
                $community_ids = trim($community_ids,',');
                if ($community_ids) {
                    $add_task = true;
                    $param['community_id'] = $community_ids;
                }
            }
        }
        $add_task && $this->add_plan($param);
    }

    /*
     * 群头像改变写入计划任务
     * $param  参数数组
     * */
    public function add_plan($param){
        import('@.ORG.plan');
        $plan_class = new plan();  
        $params = array(
            'file'=>'community',
            'plan_time'=>time(),
            'param'=>$param,
        );
        $task_id = $plan_class->addTask($params);
        return array('task_id'=>$task_id);
    }

    /*
     * 執行计划任务
     * $param  参数数组
     * */
    public function comm_runtask($param){
        import('@.ORG.plan');
        $plan_class = new plan();  
        switch ($param['type']) {
            case 'comm_avatar': //生成群頭像
                $community_ids = explode(',',$param['community_id']);
                foreach ($community_ids as $key => $value) {
                    $this->create_comm_avatar($value);
                }
                return 0;
            case 'comm_merge_pic': //生成群相册上传图片合并图片
                $dynamic_id = explode(',',$param['dynamic_id']);
                $des = $param['des'];
                foreach ($dynamic_id as $key => $value) {
                    D('Community_file')->create_comm_merge_pic($value, $des);
                }
                return 0;
            case 'comm_dissolution_group': // 解散群推送消息
                $community_id = $param['community_id'];
                $uid_arr = explode(',',$param['uid_str']);
                D('Community_join')->comm_dissolution_group($community_id, $uid_arr);
                return 0;
            case 'comm_owner_kicking': // 群主踢人
                $community_id = $param['community_id'];
                $uid_arr = explode(',',$param['uid_str']);
                D('Community_join')->comm_owner_kicking($community_id, $uid_arr);
                return 0;
            case 'comm_add_vote': // 群投票创建通知群里他人
                $community_id = $param['community_id'];
                $vote_id = $param['comm_id'];
                D('Community_join')->comm_add_vote($community_id, $vote_id);
                return 0;
            case 'comm_add_notice': // 群公告创建通知群里他人
                $community_id = $param['community_id'];
                $notice_id = $param['comm_id'];
                D('Community_join')->comm_add_notice($community_id, $notice_id);
                return 0;
            case 'comm_add_activity': // 群活动创建通知群里他人
                $community_id = $param['community_id'];
                $activity_id = $param['comm_id'];
                D('Community_join')->comm_add_activity($community_id, $activity_id);
                return 0;
            case 'comm_cancel_activity': // 群活动创建通知群里他人
                $community_id = $param['community_id'];
                $activity_id = $param['comm_id'];
                $uid = $param['other_id'];
                D('Community_join')->comm_cancel_activity($community_id, $activity_id, $uid);
                return 0;
            case 'comm_finish_activity': // 群活动创建通知群里他人
                $community_id = $param['community_id'];
                $activity_id = $param['comm_id'];
                $join_id = $param['other_id'];
                D('Community_join')->comm_finish_activity($community_id, $activity_id, $join_id);
                return 0;
            case 'comm_reminding_others': // 群聊@人
                $community_id = $param['community_id'];
                $uid_arr = explode(',',$param['uid_str']);
                $reminding_uid = $param['other_id'];
                $message = $param['message'];
                D('Community_join')->comm_reminding_others($community_id, $uid_arr, $reminding_uid, $message);
                return 0;
            case 'comm_auto': // 自动添加部分信息
                $community_id = $param['community_id'];
                $this->community_auto_add_dynamic($community_id);
                $this->community_auto_add_topic($community_id);
                $this->community_auto_add_vote($community_id);
                $this->community_auto_add_activity($community_id);
                return 0;
                break;
        }
       
    }

    /**
     * 生成群头像
     * @access public
     * @param $community_id  int 群ID
     * @return array
     * {
     *   "erroe_code":false,
     * }
     */
    public function create_comm_avatar($community_id)
    {
        $table = array(C('DB_PREFIX').'community_join'=>'cj',C('DB_PREFIX').'user'=>'u');
      
        $where = '`cj`.`add_uid` = `u`.`uid` AND `u`.`avatar`<>"" AND `cj`.`add_status`=3 AND `cj`.`community_id` = '.$community_id;
        
        //查询前9个人的头像
        $join_info = D('')->field('`u`.`avatar`,`u`.`uid`')->table($table)->where($where)->order('`cj`.`group_owner` DESC,`cj`.`add_time` ASC')->limit(9)->select();
        if (!$join_info) {
            return false;
        }
        $pic_list = array();
        $uids = '';
        foreach ($join_info as $value) {
            $pic_list[] = $value['avatar'];
            $uids .= $value['uid'].',';
        }
        $uids = trim($uids,',');

        // 合成图片
        $bg_w = 150;// 背景图片宽度 
        $bg_h = 150;// 背景图片高度 
        $background = imagecreatetruecolor($bg_w,$bg_h); // 背景图片
        $color = imagecolorallocate($background, 221, 222, 224); // 灰色
        // $color = imagecolorallocate($background, 202, 201, 201); // 为真彩色画布创建白色背景，再设置为透明 

        imagefill($background, 0, 0, $color); 
        // imageColorTransparent($background, $color); 

        $pic_count = count($pic_list); 
        $lineArr = array(); // 需要换行的位置 
        $space_x = 3; 
        $space_y = 3; 
        $line_x = 0; 
        switch($pic_count) { 
            case 1: // 正中间 
                $start_x = 0; // 开始位置X 
                $start_y = 0; // 开始位置Y 
                $pic_w = intval($bg_w); // 宽度 
                $pic_h = intval($bg_h); // 高度 
                // $start_x = intval($bg_w/4); // 开始位置X 
                // $start_y = intval($bg_h/4); // 开始位置Y 
                // $pic_w = intval($bg_w/2); // 宽度 
                // $pic_h = intval($bg_h/2); // 高度 
                break;
            case 2: // 中间位置并排 
                $start_x = 2; 
                $start_y = intval($bg_h/4) + 3; 
                $pic_w = intval($bg_w/2) - 5; 
                $pic_h = intval($bg_h/2) - 5; 
                $space_x = 5; 
                break; 
            case 3: 
                $start_x = 40; // 开始位置X 
                $start_y = 5; // 开始位置Y 
                $pic_w = intval($bg_w/2) - 5; // 宽度 
                $pic_h = intval($bg_h/2) - 5; // 高度 
                $lineArr = array(2); 
                $line_x = 4;
                break; 
            case 4: 
                $start_x = 4; // 开始位置X 
                $start_y = 5; // 开始位置Y 
                $pic_w = intval($bg_w/2) - 5; // 宽度 
                $pic_h = intval($bg_h/2) - 5; // 高度 
                $lineArr = array(3); 
                $line_x = 4;
                break; 
            case 5: 
                $start_x = 30; // 开始位置X 
                $start_y = 30; // 开始位置Y 
                $pic_w = intval($bg_w/3) - 5; // 宽度 
                $pic_h = intval($bg_h/3) - 5; // 高度 
                $lineArr = array(3); 
                $line_x = 5; 
                break; 
            case 6: 
                $start_x = 5; // 开始位置X 
                $start_y = 30; // 开始位置Y 
                $pic_w = intval($bg_w/3) - 5; // 宽度 
                $pic_h = intval($bg_h/3) - 5; // 高度 
                $lineArr = array(4); 
                $line_x = 5; 
                break; 
            case 7: 
                $start_x = 53; // 开始位置X 
                $start_y = 5; // 开始位置Y 
                $pic_w = intval($bg_w/3) - 5; // 宽度 
                $pic_h = intval($bg_h/3) - 5; // 高度 
                $lineArr = array(2,5); 
                $line_x = 5; 
                break; 
            case 8: 
                $start_x = 30; // 开始位置X 
                $start_y = 5; // 开始位置Y 
                $pic_w = intval($bg_w/3) - 5; // 宽度 
                $pic_h = intval($bg_h/3) - 5; // 高度 
                $lineArr = array(3,6); 
                $line_x = 5; 
                break; 
            case 9: 
                $start_x = 5; // 开始位置X 
                $start_y = 5; // 开始位置Y 
                $pic_w = intval($bg_w/3) - 5; // 宽度 
                $pic_h = intval($bg_h/3) - 5; // 高度 
                $lineArr = array(4,7); 
                $line_x = 5; 
                break; 
        } 
        foreach( $pic_list as $k=>$pic_path ) { 
            $kk = $k + 1; 
            if ( in_array($kk, $lineArr) ) { 
                $start_x = $line_x; 
                $start_y = $start_y + $pic_h + $space_y; 
            } 
            $pathInfo = pathinfo($pic_path);
            switch( strtolower($pathInfo['extension']) ) { 
                case 'jpg': 
                case 'jpeg': 
                    $imagecreatefromjpeg = 'imagecreatefromjpeg'; 
                    break; 
                case 'png': 
                    $imagecreatefromjpeg = 'imagecreatefrompng'; 
                    break; 
                case 'gif': 
                default: 
                    $imagecreatefromjpeg = 'imagecreatefromstring';
                    $pic_path = $this->get_img($pic_path); 
                    break; 
            }
            $resource = $imagecreatefromjpeg($pic_path); 
            
            // $start_x,$start_y copy图片在背景中的位置 
            // 0,0 被copy图片的位置 
            // $pic_w,$pic_h copy后的高度和宽度 
            $res = imagecopyresized($background,$resource,$start_x,$start_y,0,0,$pic_w,$pic_h,imagesx($resource),imagesy($resource)); // 最后两个参数为原始图片宽度和高度，倒数两个参数为copy时的图片宽度和高度

            $start_x = $start_x + $pic_w + $space_x; 
        }
        // header("Content-type: image/jpg"); 
        // imagejpeg($background);
        $img_community_id = sprintf("%09d", $community_id);
       
        $rand_num = substr($img_community_id, 0, 3) . '/' . substr($img_community_id, 3, 3) . '/' . substr($img_community_id, 6, 3);
        $upload_dir  = "./upload/comm/group/".$rand_num;
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
        }
        $upload_dir  .=  "/".$community_id.".png";
        if(imagegif($background, $upload_dir)){
            //保存到群信息
            $data = array(
                'avatar' => "/upload/comm/group/".$rand_num."/".$community_id.".png",
                'avatar_uids' => $uids,
            );
            $upSql = $this->where(array('community_id'=>$community_id))->data($data)->save();
            if ($upSql !== false) {
                return array('erroe_code'=>false,'url'=>"/upload/comm/group/".$rand_num."/".$community_id.".png");
            } else {
                return array('erroe_code'=>true);
            }
        }else{
            return array('erroe_code'=>true);
        }
    }
    //获取微信头像文本流
    public function get_img($url){
        $header = array(     
        'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',      
        'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',      
        'Accept-Encoding: gzip, deflate',);  
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($curl, CURLOPT_ENCODING, 'gzip');  
        curl_setopt($curl, CURLOPT_HTTPHEADER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //
        // var_dump(curl_error($curl)); 
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl); 
        if ($code == 200) {//把URL格式的图片转成base64_encode格式的！      
            $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);  
        }  
        $img_content=$imgBase64Code;//图片内容
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result))  
        {   
            return  base64_decode(str_replace($result[1], '', $img_content));
        }else{
            return false;
        }
    }


    // 以下是群拓展功能
    /**
     * 按照距离排序查询
     * 附近的群，按照由近到远推荐前3个
     * @param $lat  int 纬度
     * @param $lng  int 经度
     * @param $uid  int 用户uid
     * @param $pageSize int 查询几个
     * @return mixed
     */
    public function hasCommunity($lat, $lng, $pageSize=8, $uid = 0)
    {
        $number_6 = 6378.138 * 2;
        $lat_pi = $lat * pi() / 180;
        $lnt_pi = $lng * pi() / 180;
        // 过滤用户加入过的群
        $join_community_id_arr = array();
        if ($uid)  {
            $join_community_id = M('community_join')->field('community_id')->where(array('add_uid' => $uid, 'add_status' => array('in', array(2,3))))->select();
            if ($join_community_id) {
                foreach ($join_community_id as $val) {
                    $join_community_id_arr[] = intval($val['community_id']);
                }
            }
        }
        $field = "`community_id`, `community_name`, `group_owner_uid`, `community_uid`, `member_number`, `avatar`, `community_avatar`, `latitude`, `longitude`, `community_des`, ROUND({$number_6}* ASIN(SQRT(POW(SIN(({$lat_pi}-`latitude`*PI()/180)/2),2)+COS({$lat_pi})*COS(`latitude`*PI()/180)*POW(SIN(({$lnt_pi}-`longitude`*PI()/180)/2),2)))*1000) AS distance";
        $where = "`group_mode`=2 AND `status`=1 AND (`latitude` <> NULL OR `latitude` <> '') AND (`longitude` <> 0 OR `longitude` <> 0)";
        if ($join_community_id_arr) {
            $join_community_id_str = implode(',', $join_community_id_arr);
            $where .= ' AND `community_id` NOT IN (' . $join_community_id_str . ')';
        }
        $list = $this->field($field)->where($where)->limit($pageSize)->order('`distance` ASC')->select();
        return $list;
    }

    /**
     * 推荐群-群推荐，
     * 展示该用户已加入的所有群中最热门分类的群，
     * 如果用户一个群都没加入，则推荐最新添加的3个群
     * @param $uid int 用户id
     * @param $recommend_num int 推荐数量
     * @return mixed
     */
    public function recommend_community($uid, $recommend_num = 3)
    {
        $community_join = M('Community_join');
        $community_join_info = $community_join->field('community_id')->where(array('add_uid' => $uid, 'add_status' => array('in', array(2,3))))->select();
        // 判断是否加入过群
        if (!$community_join_info) {
            $list = $this->field(true)->where(array('status' => 1, 'group_mode' => 2))->limit($recommend_num)->order('`add_time` DESC')->select();
            return $list;
        }
        // 查询一下关联的分类信息
        $community_join_arr = array();
        foreach ($community_join_info as $val) {
            $community_join_arr[] = $val['community_id'];
        }
        $community_join_str = implode(',', $community_join_arr);
        // 查询所加入的群分类最多的分类id
        $bind_category = array();
        if ($community_join_str) {
            $bind_category = M('Community_bind_category')->group('cid')->field('COUNT(`cid`) as num, cid')->where(array('community_id' => array('in', $community_join_str)))->order('`num` DESC')->find();
        }
         if ($bind_category && $bind_category['cid']) {
            // 最基础的搜索条件
            $where = '`cbd`.`community_id` = `ci`.`community_id` AND `ci`.`status` = 1 AND `ci`.`group_mode` = 2 AND `cbd`.`cid` =' . $bind_category['cid'];
            $field = array('`cbd`.`cid`', '`ci`.`community_id`', '`ci`.`community_name`', '`ci`.`group_owner_uid`', '`ci`.`member_number`', '`ci`.`avatar`', '`ci`.`community_avatar`', '`ci`.`community_des`', '`ci`.`latitude`', '`ci`.`longitude`');
            $table = array(C('DB_PREFIX').'community_bind_category'=>'cbd',C('DB_PREFIX').'community_info'=>'ci');
            // 过滤用户加入过的群
            if ($community_join_str) {
                $where .= ' AND `ci`.`community_id` NOT IN (' . $community_join_str . ')';
            }
            $list = D('')->field($field)->table($table)->where($where)->limit($recommend_num)->order('`ci`.`add_time` DESC')->select();
             $num = count($list);
             if ($num < $recommend_num) {
                 $limit = $recommend_num - $num;
                 $where_arr = array(
                     'status' => 1,
                     'group_mode' => 2
                 );
                 if ($community_join_str) {
                     $where_arr['community_id'] = array('not in', $community_join_str);
                 }
                 $msg = $this->field(true)->where($where_arr)->limit($limit)->order('`add_time` DESC')->select();
                 $list = array_merge($list, $msg);
             }
            return $list;
        } else {
            $where_arr = array(
                'status' => 1,
                'group_mode' => 2
            );
            if ($community_join_str) {
                $where_arr['community_id'] = array('not in', $community_join_str);
            }
            $list = $this->field(true)->where($where_arr)->limit($recommend_num)->order('`add_time` DESC')->select();
            return $list;
        }
    }

    /**
     * 获得群信息
     * @param $where
     * @param int $pageSize
     * @param int $page
     * @return array|bool
     */
    public function community_msg_list($where, $pageSize = 6, $page = 1){
        if(!$where){
            return false;
        }

        $field = array('`u`.`nickname`','`u`.`avatar` as user_avatar' , '`ci`.`community_id`', '`ci`.`community_name`', '`ci`.`group_owner_uid`', '`ci`.`member_number`', '`ci`.`avatar`', '`ci`.`community_avatar`', '`ci`.`community_des`', '`ci`.`latitude`', '`ci`.`longitude`');
        $table = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_info'=>'ci');
        if (empty($where)) {
            $where = '`u`.`uid` = `ci`.`group_owner_uid`';
        } else {
            $where .= ' AND `u`.`uid` = `ci`.`group_owner_uid`';
        }

        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total = D('')->table($table)->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->where($where)->order('`ci`.`community_id` DESC')->limit($firstRow.','.$pageSize)->select();
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order('`ci`.`community_id` DESC')->select();
            return [
                'list' => $list
            ];
        }
    }




    // 以下是进行 初始用户添加了初始群后自动添加对应数据，群投票，群活动，

    /**
     * 自动添加一条群动态
     * @param integer $community_id 群id
     * @return bool
     */
    public function community_auto_add_dynamic($community_id) {
        $community_info = M('Community_info')->field('group_owner_uid, community_name')->where(array('community_id' => $community_id))->find();
        if (!$community_info) return false;
        $content = '欢迎加入' . $community_info['community_name'] . '![>\/<]';
        $data['community_id'] = $community_id;//群ID
        $data['user_id'] = $community_info['group_owner_uid'];//用户ID
        $data['content'] = $content;//动态内容
        $data['addtime'] = time();
        M('Community_dynamic')->add($data);
    }
    /**
     * 创建群话题
     * @param integer $community_id 群id
     * @return bool
     */
    public function community_auto_add_topic($community_id) {
        $community_info = M('Community_info')->field('group_owner_uid')->where(array('community_id' => $community_id))->find();
        if (!$community_info) return false;
        $topic_cate = '邂逅'; // 话题分类
        $topic_title = '遇见'; // 话题
        $topic_content = '茫茫人海,不期而遇。你遇到过哪些令你念念不忘的人或事。'; // 话题内容
        // 查询是否存在此分类，没有就优先添加
        $cate_info = M('Community_topic_cate')->where(array('topic_cate_name' => $topic_cate, 'topic_cate_is_del' => 0))->find();
        if (!$cate_info) {
            $data = array(
                'topic_cate_name' => $topic_cate,
                'topic_cate_is_del' => 0,
                'topic_cate_addtime' => time()
            );
            $topic_cate_id = M('Community_topic_cate')->data($data)->add();
        } else {
            $topic_cate_id = $cate_info['topic_cate_id'];
        }
        $topic_info = array(
            'topic_cate_id' => $topic_cate_id,
            'topic_user_id' => $community_info['group_owner_uid'],
            'topic_title' => $topic_title,
            'topic_content' => $topic_content,
            'community_id' => $community_id,
            'topic_addtime' => time(),
        );
        $topic_info['topic_img'] = array();
        $topic_info['topic_img'][] = '/static/community/msg/yujian.png';
        $topic_id = M('Community_topic')->data($topic_info)->add();
        if ($topic_id) {
            $this->community_auto_add_topic_dynamic($community_id, $topic_id);
        }
    }

    /**
     *  自动添加话题对应动态
     * @param integer $community_id 群id
     * @param integer $topic_id 话题id
     * @return bool
     */
    public function community_auto_add_topic_dynamic($community_id, $topic_id) {
        $community_info = M('Community_info')->field('group_owner_uid')->where(array('community_id' => $community_id))->find();
        if (!$community_info) return false;
        $content = '我还是很喜欢你,像风走了八千里,不问归期。我还是很喜欢你,像森林拥抱委顿的倦鸟,相思成疾。我还是很喜欢你,像老旧留声机里缠绵的回忆,半尘半旧,失了声息。';
        $data['community_id'] = $community_id;//群ID
        $data['user_id'] = $community_info['group_owner_uid'];//用户ID
        $data['content'] = $content;//动态内容
        $data['addtime'] = time();
        $topic_info['img'] = array();
        $topic_info['img'][] = '/static/community/msg/dynamic_yj.jpg';
        $dynamic_id = M('Community_dynamic')->add($data);
        if ($dynamic_id) {
            $dynamic_topic_bind = array(
                'dynamic_id' => $dynamic_id,
                'topic_id' => $topic_id
            );
            M('Community_dynamic_topic_bind')->data($dynamic_topic_bind)->add();
        }

    }

    /**
     * 自动创建一个投票
     * @param integer $community_id 群id
     * @return bool
     */
    public function community_auto_add_vote($community_id) {
        $community_info = M('Community_info')->field('group_owner_uid, community_name')->where(array('community_id' => $community_id))->find();
        if (!$community_info) return false;
        $group_owner_uid = $community_info['group_owner_uid'];
        $title = '萌宠大比拼'; // 投票标题
        $end_time = strtotime('+7 day'); // 获取当前时间七天后
        $data = array(
            'community_id' => $community_id,
            'title' => $title,
            'end_time' => $end_time,
            'type' => 1,
            'add_time' => time(),
            'image' => '/static/community/msg/vote_pet_title.png',
            'is_remind' => 0,
            'uid' => $group_owner_uid,
        );
        $addInfo = M('Community_vote')->data($data)->add();
        if ($addInfo) {
            // 添加选项
            $site_url = C('config.site_url');
            $option = array(
                0 => array(
                    'vote_id' => $addInfo,
                    'community_id' =>  $community_id,
                    'name' => '1号萌宠',
                    'add_time' => time(),
                    'img' => $site_url . '/static/community/msg/vote_pet1.png'
                ),
                1 => array(
                    'vote_id' => $addInfo,
                    'community_id' =>  $community_id,
                    'name' => '2号萌宠',
                    'add_time' => time(),
                    'img' => $site_url . '/static/community/msg/vote_pet2.png'
                ),
                2 => array(
                    'vote_id' => $addInfo,
                    'community_id' =>  $community_id,
                    'name' => '3号萌宠',
                    'add_time' => time(),
                    'img' => $site_url . '/static/community/msg/vote_pet3.png'
                ),
                3 => array(
                    'vote_id' => $addInfo,
                    'community_id' =>  $community_id,
                    'name' => '4号萌宠',
                    'add_time' => time(),
                    'img' => $site_url . '/static/community/msg/vote_pet4.png'
                ),
                4 => array(
                    'vote_id' => $addInfo,
                    'community_id' =>  $community_id,
                    'name' => '5号萌宠',
                    'add_time' => time(),
                    'img' => $site_url . '/static/community/msg/vote_pet5.png'
                ),
                5 => array(
                    'vote_id' => $addInfo,
                    'community_id' =>  $community_id,
                    'name' => '6号萌宠',
                    'add_time' => time(),
                    'img' => $site_url . '/static/community/msg/vote_pet6.png'
                )
            );
            D('Community_vote_option')->addAll($option);

            // 同步到云通讯

            $uid = $group_owner_uid;
            $group_id = $community_id;
            $msg_body = array();
            $msgType = array();
            $msgType['MsgType'] = 'TIMTextElem';
            $url = C('config.site_url') .'/static/wxapp/group_vote.png';
            $msgType['MsgContent'] = array(
                'Text' => '【￥vote￥】&' . $addInfo . '&'.urlencode($title) . '&' . urlencode($url)
            );
            $msg_body[] = $msgType;
            D('Community_info')->qcloud_send_group_msg($group_id, $msg_body, $uid);


            // 同步发动态
            $msg['id'] = $addInfo;
            $msg['type'] = 'vote';
            $msg['img'] = C('config.site_url').'/static/community/msg/vote_pet_title.png';
            $msg['content'] = $data['title'];
            $dynamic_data=array(
                'community_id'  =>  $community_id,
                'user_id'       =>  $group_owner_uid,
                'application_detail'  =>  serialize($msg),
                'addtime'       =>  time()
            );
            M('Community_dynamic')->data($dynamic_data)->add();
        }

    }


    /**
     *
     * @param integer $community_id 群id
     * @return bool
     */
    public function community_auto_add_activity($community_id) {
        $community_info = M('Community_info')->field('group_owner_uid')->where(array('community_id' => $community_id))->find();
        if (!$community_info) return false;
        $title = '黄山-西海大峡谷-宏村-屯溪老街自由行'; // 活动主题
        $desc = '一生痴绝处,无梦到徽州。为了丰富群友们的生活,故组织此次旅游活动。希望大家踊跃报名。★山顶宿一晚观旭日东升，览黄山全景、探奇松怪石、赏云海奔腾！★市区宿一晚，自行游览屯溪老街，品尝徽州特色美食！'; // 介绍
        $group_owner_uid = $community_info['group_owner_uid'];
        $fees = 412; // 费用
        $start_time = strtotime(date('Y-m-d ') . '10:00');
        $end_time = strtotime(date('Y-m-d ' . '10:00', strtotime(' +4 day')));
        $deadline = $end_time;
        $image = '/static/community/msg/activity.png';
        $data = array(
            'community_id' => $community_id,
            'title' => $title,
            'desc' => $desc,
            'uid' => $group_owner_uid,
            'fees' => $fees,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'deadline' => $deadline,
            'image' => $image
        );
        $data['add_time'] = time();
        $data['enroll_status'] = 1; //报名状态:1-进行中，2-已结束
        $activity_id = M('Community_activity')->data($data)->add();
        if ($activity_id) {
            // 创建群活动 同步发消息到云通讯
            $uid = $group_owner_uid;
            $group_id = $community_id;
            $msg_body = array();
            $msgType = array();
            $msgType['MsgType'] = 'TIMTextElem';
            // 没有就取默认值
            $url = C('config.site_url') .'/static/community/msg/activity.png';
            $msgType['MsgContent'] = array(
                'Text' => '【￥activity￥】&' . $activity_id . '&'.urlencode($title) . '&' . urlencode( $url)
            );
            $msg_body[] = $msgType;
            D('Community_info')->qcloud_send_group_msg($group_id, $msg_body, $uid);


            // 同步到动态
            $msg['id'] = $activity_id;
            $msg['type'] = 'activity';
            $msg['img'] = C('config.site_url').'/static/community/msg/activity.png';
            $msg['content'] = $data['title'];
            $dynamic_data=array(
                'community_id'  =>  $data['community_id'],
                'user_id'       =>  $data['uid'],
                'application_detail' =>  serialize($msg),
                'addtime'       =>  time()
            );
            M('Community_dynamic')->data($dynamic_data)->add();
        }
    }
}
?>