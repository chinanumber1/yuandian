<?php

/*
 * 社群
 *
 */

class Comm_GroupAction extends BaseAction
{
    // 首页
    // 引导页
    public function group_index_guide()
    {
        $community_index_guide = D("Adver")->get_adver_by_key("community_index_guide", 4);
        foreach ($community_index_guide as &$val) {
            if ($val['url']) unset($val['url']);
        }
        $this->returnCode(0, $community_index_guide);
    }


    // 社群首页广告
    public function group_index_top()
    {
        $community_index_guide = D("Adver")->get_adver_by_key("community_index_top", 4);
        $this->returnCode(0, $community_index_guide);
    }


    // 创建群/ 修改群/ 转让群
    public function group_set()
    {
        // 判断用户是否登录

        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        if (empty($_POST['community_id']) && empty($_POST['community_name'])) {
            $this->returnCode('70000001');
        }
        if (!empty($_POST['community_name']) && strlen($_POST['community_name']) > 30) {
            $this->returnCode('70000003');
        }
        // 加群需要收费，所填写的收费必须大于0
        if (!empty($_POST['is_charge']) && $_POST['is_charge'] == 2 && (empty($_POST['charge_money']) || floatval($_POST['charge_money']) <= 0)) {
            $this->returnCode('70000002');
        }

        if ($_POST['is_charge'] == 2) { // 收费金额>=0.01 保留两位
            $_POST['charge_money'] = round($_POST['charge_money'], 2);
            if ($_POST['charge_money'] <= 0) {
                $this->returnCode('70000028');
            }
        }
        $data = array();
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_community = D('Community_info');
            $database_community_join = D('Community_join');
            if (empty($_POST['community_id'])) {
                // 没有群id,为添加信息
                if (empty($_POST['community_name'])) {
                    $this->returnCode('70000001');
                }
                if (strlen($_POST['community_name']) > 30) {
                    $this->returnCode('70000003');
                }
                $data['community_name'] = $_POST['community_name'];
                // 入群方式 （1： 只允许微信群内成员加群  2： 允许任何人加群  3： 不允许任何人加群）
                $data['group_mode'] = !empty($_POST['group_mode']) ? $_POST['group_mode'] : 1;
                // 加群是否审核（1： 加群不需要审核  2： 加群需要群主审核）
                $data['is_check'] = !empty($_POST['is_check']) ? $_POST['is_check'] : 1;
                // 是否收费（1：加群不收费  2：加群需要收费）
                $data['is_charge'] = !empty($_POST['is_charge']) ? $_POST['is_charge'] : 1;
                $data['charge_money'] = !empty($_POST['charge_money']) ? $_POST['charge_money'] : 0.00;
                // (0: 关闭我在本群的昵称功能  1： 开启)
                $data['is_nickname'] = !empty($_POST['is_nickname']) ? $_POST['is_nickname'] : 1;
                $data['community_uid'] = $this->_uid;
                $data['group_owner_uid'] = $this->_uid;
                $data['member_number'] = 1;
                $data['add_time'] = time();
                D()->startTrans();
                $addInfo = $database_community->data($data)->add();
                if ($addInfo) {
                    $database_User = D('User');
                    $add_user = $database_User->field(true)->where(array('uid' => $this->_uid))->find();
                    $join = array(
                        'community_id' => $addInfo,
                        'group_owner' => 2,
                        'add_source' => 0,
                        'add_uid' => $this->_uid,
                        'comm_nickname' => $add_user['nickname'],
                        'add_status' => 3,
                        'add_time' => time()
                    );
                    $join_id = $database_community_join->data($join)->add();
                    $data = [
                        'community_id' => $addInfo
                    ];
                    if ($join_id) {
                        // 创建群成功 同步创建到云通讯
                        $uid = $this->_uid;
                        $group_id = $addInfo;
                        $group_name = $_POST['community_name'];
                        $ret_group = $database_community->qcloud_create_group($uid, $group_id, $group_name);
                        if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                            D()->commit();
                            //生成群头像
                            $database_community->create_comm_avatar($addInfo);

                            $this->returnCode(0, $data);
                        } else {
                            D()->rollback();
                            $this->returnCode(1, array(), '添加失败！请重试~');
                        }
                    } else {
                        D()->rollback();
                        $this->returnCode(1, array(), '添加失败！请重试~');
                    }
                } else {
                    $this->returnCode(1, array(), '添加失败！请重试~');
                }
            } else {
                // 传群id 修改对应群信息
                $community_info = $database_community->where(array('community_id' => $_POST['community_id']))->find();
                if (!empty($community_info)) {
                    if ($community_info['group_owner_uid'] != $this->_uid) {
                        $this->returnCode('70000010');
                    }
                    if (!empty($data['community_uid']) && $data['community_uid'] != $community_info['community_uid']) {
                        $this->returnCode('70000010');
                    }
                    $data = $this->array_key_val($_POST);
                    unset($data['community_id']);
                    unset($data['ticket']);
                    unset($data['Device-Id']);
                    unset($data['app_version']);
                    D()->startTrans();
                    if (!empty($data['group_owner_uid'])) {
                        if (!empty($data['group_owner_uid']) && $data['group_owner_uid'] == $community_info['group_owner_uid']) {
                            $this->returnCode('70000026');
                        }
                        // 转让群的时候改变加群信息中的群主
                        $change1 = $database_community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $community_info['group_owner_uid']))->data(array('group_owner' => 1))->save();
                        if (!$change1) {
                            D()->rollback();
                            $this->returnCode(1, array(), '操作失败！请重试~');
                        }
                        // 群主被转让，保证为解除禁言状态
                        $save_data = array(
                            'group_owner' => 2,
                            'excuse_status' => 1,
                            'excuse_time' => 0,
                            'excuse_time_length' => 0,
                            'excuse_end_time' => 0,
                        );
                        $change2 = $database_community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $data['group_owner_uid']))->data($save_data)->save();
                        if (!$change2) {
                            D()->rollback();
                            $this->returnCode(1, array(), '操作失败！请重试~');
                        }
                    }
                    $data['set_time'] = time();
                    $info = $database_community->where(array('community_id' => $_POST['community_id']))->data($data)->save();
                    if ($info) {
                        // 修改群名称成功 同步到云通讯
                        if (!empty($data['community_name'])) {
                            $group_id = $_POST['community_id'];
                            $group_name = $data['community_name'];
                            $ret_group = $database_community->qcloud_modify_group_base_info($group_id, $group_name);
                            if (empty($ret_group) && $ret_group['ActionStatus'] != 'OK') {
                                D()->rollback();
                                $this->returnCode(1, array(), '操作失败！请重试~');
                            }
                        }
                        // 转让群 同步到云通讯
                        if (!empty($data['group_owner_uid'])) {
                            $group_id = $_POST['community_id'];
                            $new_owner_uid = $data['group_owner_uid'];
                            $ret_group = $database_community->qcloud_change_group_owner($group_id, $new_owner_uid);
                            if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                                // 群头像 群主未上传群聊头像
                                if (!$community_info['community_avatar']) {
                                    $database_community->change_group_avatar($group_id, $this->_uid);
                                }
                                D()->commit();
                                $this->returnCode(0, '操作成功！');
                            } else {
                                D()->rollback();
                                $this->returnCode(1, array(), '操作失败！请重试~');
                            }
                        }
                        D()->commit();
                        $this->returnCode(0, '操作成功！');
                    } else {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                } else {
                    $this->returnCode('70000005');
                }

            }
        }
    }


    // 群分享信息
    public function group_share()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $database_Community_info = D('Community_info');
        $condition_info['community_id'] = $_POST['community_id'];
        $info = $database_Community_info->field('community_id, community_name')->where($condition_info)->find();
        if (!empty($info)) {
            $now_user = D('User')->field(true)->where(array('uid' => $this->_uid))->find();
            $info['share_url'] = C('config.site_url') . '/wap.php?g=Wap&c=Library&a=visitor_list_open_time&community_id=' . $_POST['community_id'];
            $info['share_info'] = '您好，这是我创建的【' . $info['community_name'] . '】群，欢迎您的加入！';
            $info['nickname'] = $now_user['nickname'];
            $info['phone'] = substr_replace($now_user['phone'], '****', 3, 4);
        } else {
            $info = array();
        }
        $this->returnCode(0, $info);
    }


    // 绑定微信群id
    public function group_bind_openGId()
    {
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000012');
        }
        $appid = $this->config['pay_wxapp_group_appid'];
        $appsecret = $this->config['pay_wxapp_group_appsecret'];


        import('ORG.Net.Http');
        $http = new Http();

        $return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $appsecret . '&js_code=' . $_POST['code'] . '&grant_type=authorization_code', array());

        import('@.ORG.aeswxapp.wxBizDataCrypt');

        $pc = new WXBizDataCrypt($appid, $return['session_key']);
        $errCode = $pc->decryptData($_POST['encryptedData'], $_POST['iv'], $data);
        $jsonrt = json_decode($data, true);
        $database_Community_info = D('Community_info');
        $community_info = $database_Community_info->get_community_info($_POST['community_id'], 'community_id');
        if (empty($community_info) || $community_info['status'] != 1) {
            $this->returnCode('70000013');
        }
        // 如果群未绑定微信群， 首先进行绑定
        if (empty($community_info['openGId'])) {
            if (empty($jsonrt['openGId'])) {
                $this->returnCode('70000018');
            }
            $bind = $database_Community_info->where(array('community_id' => $_POST['community_id']))->data(array('openGId' => $jsonrt['openGId']))->save();
            if ($bind) {
                $this->returnCode(0, array('community_id' => $_POST['community_id'], 'openGId' => $jsonrt['openGId']));
            } else {
                $this->returnCode('70000018');
            }
        } else {
            $this->returnCode(0, array('community_id' => $_POST['community_id'], 'openGId' => $jsonrt['openGId']));
        }
    }


    // 绑定手机号快速获取手机号
    public function group_get_bind_phone()
    {
        $appid = $this->config['pay_wxapp_group_appid'];
        $appsecret = $this->config['pay_wxapp_group_appsecret'];


        import('ORG.Net.Http');
        $http = new Http();

        $return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $appsecret . '&js_code=' . $_POST['code'] . '&grant_type=authorization_code', array());

        import('@.ORG.aeswxapp.wxBizDataCrypt');

        $pc = new WXBizDataCrypt($appid, $return['session_key']);
        $pc->decryptData($_POST['encryptedData'], $_POST['iv'], $data);
        $jsonrt = json_decode($data, true);

        if ($jsonrt) {
            $this->returnCode(0, array('phoneNumber' => $jsonrt['phoneNumber'], 'purePhoneNumber' => $jsonrt['purePhoneNumber']));
        } else {
            $this->returnCode(1, array(), '获取失败！请重试~');
        }
    }

    // 绑定手机号
    public function comm_bind_phone() {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;

        $appid = $this->config['pay_wxapp_group_appid'];
        $appsecret = $this->config['pay_wxapp_group_appsecret'];


        import('ORG.Net.Http');
        $http = new Http();

        $return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $appsecret . '&js_code=' . $_POST['code'] . '&grant_type=authorization_code', array());

        import('@.ORG.aeswxapp.wxBizDataCrypt');

        $pc = new WXBizDataCrypt($appid, $return['session_key']);
        $pc->decryptData($_POST['encryptedData'], $_POST['iv'], $data);
        $jsonrt = json_decode($data, true);

        if ($jsonrt) {
            if ($jsonrt['phoneNumber']) {
                $phone = $jsonrt['phoneNumber'];
            } elseif($jsonrt['purePhoneNumber']) {
                $phone = $jsonrt['purePhoneNumber'];
            } else {
                $this->returnCode(1, array(), '获取失败！请重试~');
            }
            $database_user = D('User');
            $now_user = $database_user->where(array('uid'=>$uid))->find();
            if(!empty($now_user['phone'])){
//                $condition_user['phone'] = $phone;
                // 重复手机号，暂不处理
//                if($database_user->field(true)->where($condition_user)->find()){
//                    $this->returnCode('10044005');
//                }
                if ($now_user['phone'] == $phone) {
                    $this->returnCode(0, array('phone' => $phone));
                }
            }
            $condition_save_user['uid'] = $uid;
            $data_save_user['phone'] = $phone;
            $set_user = $database_user->where($condition_save_user)->data($data_save_user)->save();
            if($set_user){
                $this->returnCode(0, array('phone' => $phone));
            }else{
                $this->returnCode('20120006');
            }
        } else {
            $this->returnCode(1, array(), '获取失败！请重试~');
        }
    }


    // 加群时获取的信息及加入与否及状态
    public function group_info()
    {
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }

        $database_Community_info = D('Community_info');
        $community_info = $database_Community_info->get_community_info($_POST['community_id'], 'community_id');
        if (empty($community_info) || $community_info['status'] != 1) {
            $this->returnCode('70000013');
        }
        if ($community_info['income_money']) unset($community_info['income_money']);
//        if ($community_info['openGId']) unset($community_info['openGId']);
        if ($community_info['group_owner_uid'] == $this->_uid) {
            $community_info['is_owner'] = true;
        } else {
            $community_info['is_owner'] = false;
        }
        $community_info['charge_money'] = floatval($community_info['charge_money']);
        $community_info['tip'] = false;
        $community_info['tip_info'] = '';
        $database_community_join = D('Community_join');
        if ($this->_uid) {
            $community_join = $database_community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $this->_uid))->find();
            if (!empty($community_join)) {
                $community_info['add_status'] = $community_join['add_status'];
                $community_info['add_charge_money'] = floatval($community_join['charge_money']);
                $community_info['add_group_time'] = $community_join['add_time'];
                $community_info['refusing_remark'] = $community_join['refusing_remark'];
            } elseif (!$community_info['is_owner']) {
                if ($community_info['group_mode'] == 1) {
                    if (empty($_POST['openGId']) || $_POST['openGId'] != $community_info['openGId']) {
                        $community_info['tip'] = true;
                        $community_info['tip_info'] = '只允许微信群内成员加群';
                    }
                }
                if ($community_info['group_mode'] == 3) {
                    $community_info['tip'] = true;
                    $community_info['tip_info'] = '不允许任何人加群';
                }
            }
            //用户信息
            $user_info = D('User')->get_user($this->_uid);
            $community_info['now_money'] = floatval($user_info['now_money']);
            //如果加群，获得还需支付金额
            $community_info['pay_money'] = ($community_info['is_charge'] == 2 && $community_info['charge_money'] > $community_info['now_money']) ? round($community_info['charge_money'] - $community_info['now_money'], 2) : 0;

        }
        // 返回已经加群的群成员头像
        $community_join_msg = $database_community_join->where(array('community_id' => $_POST['community_id'], 'add_status' => 3))->select();
        if (!empty($community_join_msg)) {
            $community_info['join_user'] = array();
            $database_User = D('User');
            foreach ($community_join_msg as $val) {
                $add_user = $database_User->field('`avatar`')->where(array('uid' => $val['add_uid']))->find();
                if (empty($add_user['avatar'])) {
                    // 没有头像取默认头像
                    $add_user['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                }
                if (!empty($add_user)) {
                    $community_info['join_user'][] = $add_user;
                }
            }
        }
        if (!empty($_POST['openGId']) && $_POST['openGId'] == $community_info['openGId']) {
            $community_info['is_group'] = true;
        } else {
            $community_info['is_group'] = false;
        }

        //群头像
        $community_info['avatar'] = C('config.site_url') . $community_info['avatar'];
        $this->returnCode(0, $community_info);
    }

    // 进群后个人对应信息获取
    public function group_chat_info()
    {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $database_Community_join = D('Community_join');

        $where = '`ci`.community_id = ' . $_POST['community_id'] . ' AND `ci`.status = 1 AND `cj`.add_uid=' . $this->_uid;

        $community_join_info = $database_Community_join->get_community_join($where);
        if (empty($community_join_info['list'])) {
            $this->returnCode('70000013');
        }
        //群人数
        $count = $database_Community_join->where(array('community_id' => $_POST['community_id'], 'add_status' => 3))->count();
        $join_info = reset($community_join_info['list']);
        $join_info['comm_count'] = $count;

        if ($join_info['status'] != 1) {
            $this->returnCode('70000013');
        }

        if ($join_info['add_status'] == 2) {
            $this->returnCode('70000027');
        }

        if ($join_info['add_status'] != 3) {
            $this->returnCode('70000019');
        }
        // 获取用户信息
        $database_User = D('User');
        $add_user = $database_User->field(true)->where(array('uid' => $this->_uid))->find();
        if (empty($add_user['phone'])) {
            // 手机未绑定
            $join_info['bind_phone'] = false;
        } else {
            $join_info['bind_phone'] = true;
        }
        if (!empty($add_user)) {
            if (empty($add_user['avatar'])) {
                // 没有头像取默认头像
                $join_info['avatar'] = C('config.site_url') . '/static/avatar.jpg';
            } else {
                $join_info['avatar'] = $add_user['avatar'];
            }
        }
        if ($join_info['income_money']) unset($join_info['income_money']);
        if ($join_info['openGId']) unset($join_info['openGId']);
        if ($join_info['group_owner_uid'] == $this->_uid) {
            $join_info['is_owner'] = true;
        } else {
            $join_info['is_owner'] = false;
        }

        // 查询一下群商城
        $condition_info['group_owner_uid'] = $join_info['group_owner_uid'];
        $condition_info['community_id'] = $join_info['community_id'];
        $database_community_bind_shop = D('community_bind_shop');
        $bind_shop = $database_community_bind_shop->field(true)->where($condition_info)->find();
        if (!empty($bind_shop)) {
            $join_info['bind_shop'] = true;
            $join_info['bind_shop_url'] = C('config.site_url') . '/wap.php?g=Wap&c=Mall&a=store&store_id=' . $bind_shop['store_id'];
        } else {
            $join_info['bind_shop'] = false;
            $join_info['bind_shop_url'] = '';
        }
        // 处理一下禁言时间显示
        if ($join_info['excuse_status'] == 2) {
            $timestamp = intval($join_info['excuse_end_time']) - intval(time());
            if ($timestamp > 0) {
                $join_info['excuse_time_info'] = $this->excuse_time_info($timestamp);
            }
            $join_info['add_time_info'] = date('Y-m-d H:i:s', $join_info['add_group_time']);
        }
        // 获取 云通讯 配置
        $join_info['sdkappid'] = C('config.cloud_communication_appid');
        $join_info['accountType'] = 884;
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'), 'user_' . $this->_uid);
        $join_info['UserSig'] = $qcloud_im->usersig;
        $join_info['Identifier'] = 'user_' . $this->_uid;
        $join_info['avChatRoomId'] = 'community_' . $_POST['community_id'];

        // 获取一条最新的该群公告信息
        $field = array('`cn`.*', '`cnu`.`is_read`', '`cn`.`add_time` as add_notice_time', '`cnu`.`uid` as notice_uid');
        $table = array(C('DB_PREFIX') . 'community_notice' => 'cn', C('DB_PREFIX') . 'community_notice_user' => 'cnu');
        $where = '`cn`.`notice_id` = `cnu`.`notice_id` AND `cnu`.`uid` = ' . $this->_uid . ' AND `cn`.`community_id` = ' . $_POST['community_id'];
        $notice = D('')->field($field)->table($table)->where($where)->order('`cnu`.`add_time` DESC')->find();
        if (!empty($notice) && $notice['is_read'] == 0) {
            $join_info['notice'] = $notice;
            $database_join = D('Community_join');
            $database_notice_user = D('Community_notice_user');
            $read_number = $database_notice_user->where(array('notice_id' => $notice['notice_id'], 'is_read' => 1))->count();
            $join_info['notice']['read_number'] = $read_number ? $read_number : 0;
            $join_info['notice']['add_time'] = $this->time_info($notice['add_time'], '');
            $join_msg = $database_join->field('comm_nickname')->where(array('add_uid' => $notice['uid'], 'community_id' => $notice['community_id']))->find();
            $join_info['notice']['nickname'] = $join_msg['comm_nickname'] ? $join_msg['comm_nickname'] : '';
            // 查询当前是否已读
            $read_notice_user = $database_notice_user->where(array('notice_id' => $notice['notice_id'], 'uid' => $this->_uid))->find();
            if ($read_notice_user['is_read'] == 0) {
                //设为已读
                $read_info['is_read'] = 1;
                $database_notice_user->where(array('notice_id' => $notice['notice_id'], 'uid' => $this->_uid))->data($read_info)->save();
            } elseif (empty($read_notice_user)) {
                //设为已读
                $data['is_read'] = 1;
                $data['notice_id'] = $notice['notice_id'];
                $data['uid'] = $this->_uid;
                $data['community_id'] = $notice['community_id'];
                $data['add_time'] = time();
                $database_notice_user->data($data)->add();
            }
        }

        // 添加判断  是否显示 群商城 和 群聊天
        // 总开关  是否开启群聊  默认开启 （0开启 1关闭）
        $system_community_chat_switch = C('config.community_chat_switch');
        // 总开关 是否开启群绑定商城（0开启 1关闭）
        $system_community_shop_switch = C('config.community_shop_switch');
        // 群开关 是否开启群聊  默认开启 （0开启 1关闭）
        $community_chat_switch = $join_info['community_chat_switch'];
        // 群开关 群主是否开启群聊天（0开启 1关闭）
        $community_owner_chat_switch = $join_info['community_owner_chat_switch'];
        // 群开关 是否开启群绑定商城（0开启 1关闭）
        $community_shop_switch = $join_info['community_shop_switch'];
        if (!empty($system_community_chat_switch) && $system_community_chat_switch == 1) {
            $join_info['is_chat'] = false;
        } else {
            if (!empty($community_chat_switch) && $community_chat_switch == 1) {
                $join_info['is_chat'] = false;
            } else {
                if (!empty($community_owner_chat_switch) && $community_owner_chat_switch == 2) {
                    $join_info['is_chat'] = false;
                } else {
                    $join_info['is_chat'] = true;
                }
            }
        }
        if (!empty($system_community_shop_switch) && $system_community_shop_switch == 1) {
            $join_info['is_shop'] = false;
        } else {
            if (!empty($community_shop_switch) && $community_shop_switch == 1) {
                $join_info['is_shop'] = false;
            } else {
                $join_info['is_shop'] = true;
            }
        }

        $this->returnCode(0, $join_info);
    }


    // 个人加群
    public function group_personal_join()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $database_Community_info = D('Community_info');
        $community_info = $database_Community_info->where(array('community_id' => $_POST['community_id']))->field(true)->find();
        if (empty($community_info) || $community_info['status'] != 1) {
            $this->returnCode('70000013');
        }
        $database_Community_join = D('Community_join');
        $where_join = array(
            'add_uid' => $this->_uid,
            'community_id' => $_POST['community_id'],
        );
        $join_info = $database_Community_join->field(true)->where($where_join)->find();
        if (!empty($join_info)) {
            if ($join_info['add_status'] == 2) { // 审核中
                $this->returnCode('70000021');
            }

            if ($join_info['add_status'] == 3) { // 已加入
                $this->returnCode('70000020');
            }
        }

        $add_status = 3;
        if ($community_info['is_charge'] == 2) { // 收费
            $add_status = 1;
        } elseif ($community_info['is_check'] != 1) {
            $add_status = 2;
        }
        $database_User = D('User');
        $add_user = $database_User->field(true)->where(array('uid' => $this->_uid))->find();
        $join = array(
            'community_id' => $_POST['community_id'],
            'group_owner' => 1,
            'add_uid' => $this->_uid,
            'comm_nickname' => $add_user['nickname'],
            'charge_money' => $community_info['charge_money'],
            'add_status' => $add_status,
            'add_time' => time()
        );
        if (!empty($_POST['add_source'])) {
            $join['add_source'] = $_POST['add_source'];
        }
        D()->startTrans();
        if ($join_info) { // 更新
            $addInfo = $database_Community_join->where(array('add_id' => $join_info['add_id'], 'community_id' => $_POST['community_id']))->data($join)->save();
            $add_id = $join_info['add_id'];
        } else { // 新增
            $addInfo = $database_Community_join->data($join)->add();
            $add_id = $addInfo;
        }

        if ($addInfo !== false) {
            if ($add_status == 3) {
                // 加群成功添加成员数
                $database_Community_info = D('Community_info');
                $num = $database_Community_info->where(array('community_id' => $_POST['community_id']))->setInc('member_number');
                if (!$num) {
                    D()->rollback();
                    $this->returnCode(1, array(), '加群失败！请重试~');
                }
                // 如果是别人邀请加入，记录邀请人邀请数
                if (!empty($join['add_source'])) {
                    $num2 = $database_Community_join->where(array('community_id' => $join['community_id'], 'add_uid' => $join['add_source']))->setInc('invitation_num');
                    if (!$num2) {
                        D()->rollback();
                        $this->returnCode(1, array(), '加群失败！请重试~');
                    }
                }
                // 加入群成功 同步到云通讯
                $uid = $this->_uid;
                $group_id = $community_info['community_id'];
                $ret_group = $database_Community_info->qcloud_add_group_member($group_id, $uid);
                if (empty($ret_group) || $ret_group['ActionStatus'] != 'OK') {
                    D()->rollback();
                    $this->returnCode(1, array(), '加群失败！请重试~');
                }
                // 加入群成功 同步到发一条系统消息云通讯
                $content = $add_user['nickname'] . '加入了本群';
                $ret_group = $database_Community_info->qcloud_send_group_system_notification($group_id, $content);
                if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                    // 群头像 群主未上传群聊头像
                    if (!$community_info['community_avatar']) {
                        $database_Community_info->change_group_avatar($group_id, $this->_uid);
                    }
                    D()->commit();
                    $this->returnCode(0, '加群功成！');
                } else {
                    D()->rollback();
                    $this->returnCode(1, array(), '加群失败！请重试~');
                }
            } elseif ($add_status == 1) {
                $user_info = D('User')->get_user($this->_uid);
                if ($user_info['now_money'] > $community_info['charge_money']) { // 余额足够
                    $res1 = D('User')->user_money($this->_uid, $community_info['charge_money'], '加入群【' . $community_info['community_name'] . '】费用，减少余额');
                    if ($res1['error_code']) { // 扣除失败 回滚
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    } else { // 扣除成功 生成订单
                        // 生成订单
                        $order_data = array(
                            'community_id' => $join['community_id'],
                            'uid' => $this->_uid,
                            'money' => $community_info['charge_money'],
                            'status' => 1, // 已付款
                            'type' => 2, // 订单类型:1-活动，2-加群
                            'pay_type' => 3, // 付款方式1-微信，2-支付宝，3-余额
                            'add_time' => time(),
                        );
                        $order_id = D('Community_order')->data($order_data)->add();
                        if (!$order_id) {
                            D()->rollback();
                            $this->returnCode(1, array(), '加群失败！请重试~');
                        }

                        // 加群是否审核
                        if ($community_info['is_check'] != 1) {
                            $add_status = 2;
                        } else {
                            $add_status = 3;
                        }

                        // 跟新加群状态
                        $update_data = array(
                            'add_status' => $add_status,
                            'order_id' => $order_id,
                        );
                        $upSql = $database_Community_join->where(array('add_id' => $add_id))->data($update_data)->save();
                        if (!$upSql) {
                            D()->rollback();
                            $this->returnCode(1, array(), '加群失败！请重试');
                        }
                        if ($add_status == 3) { // 不需审核 直接加入成功
                            // 加群成功添加成员数
                            $database_Community_info = D('Community_info');
                            $num = $database_Community_info->where(array('community_id' => $_POST['community_id']))->setInc('member_number');
                            if (!$num) {
                                D()->rollback();
                                $this->returnCode(1, array(), '加群失败！请重试~');
                            }

                            // 如果是别人邀请加入，记录邀请人邀请数
                            if (!empty($join['add_source'])) {
                                $num2 = $database_Community_join->where(array('community_id' => $join['community_id'], 'add_uid' => $join['add_source']))->setInc('invitation_num');
                                if (!$num2) {
                                    D()->rollback();
                                    $this->returnCode(1, array(), '加群失败！请重试~');
                                }
                            }

                            // 群主余额增加
                            // 收取平台费用
                            $money = $join['charge_money'];
                            $add_result = D('User')->add_money($community_info['group_owner_uid'], $money, $user_info['nickname'] . '加入群【' . $community_info['community_name'] . '】，增加余额');
                            if (C('config.community_join_get_merchant_percent') > 0) {
                                $money = $money * C('config.community_join_get_merchant_percent') * 0.01;
                                $user_result = D('User')->user_money($community_info['group_owner_uid'], $money, $user_info['nickname'] . '加入群【' . $community_info['community_name'] . '】，平台抽成，减少余额');
                            }
                            if ($add_result['error_code']) {
                                D()->rollback();
                                $this->returnCode(1, array(), '加群失败！请重试~');
                            }
                            // 加入群成功 同步到云通讯
                            $group_id = $community_info['community_id'];
                            $ret_group = $database_Community_info->qcloud_add_group_member($group_id, $this->_uid);
                            if (empty($ret_group) || $ret_group['ActionStatus'] != 'OK') {
                                D()->rollback();
                                $this->returnCode(1, array(), '加群失败！请重试~');
                            }
                            // 加入群成功 同步到发一条系统消息云通讯
                            $content = $add_user['nickname'] . '加入了本群';
                            $ret_group = $database_Community_info->qcloud_send_group_system_notification($group_id, $content);
                            if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                                // 群头像 群主未上传群聊头像
                                if (!$community_info['community_avatar']) {
                                    $database_Community_info->change_group_avatar($group_id, $this->_uid);
                                }
                                D()->commit();
                                $this->returnCode(0, '加群功成！');
                            } else {
                                D()->rollback();
                                $this->returnCode(1, array(), '加群失败！请重试~');
                            }
                        }
                    }
                } else {
                    D()->commit();
                    //如果加群，获得还需支付金额
                    $ret_info = array();
                    $user_info['now_money'] = floatval($user_info['now_money']);
                    $ret_info['pay_money'] = ($community_info['is_charge'] == 2 && $community_info['charge_money'] > $user_info['now_money']) ? round($community_info['charge_money'] - $user_info['now_money'], 2) : 0;
                    $this->returnCode(0, $ret_info);
                }
                D()->commit();
            } else {
                D()->commit();
            }
            if ($add_status == 2) {
                $database_user = D('User');
                $database_community_user_formid = D('Community_user_formid');
                $send_user = $database_user->field('group_openid')->where(array('uid' => $community_info['group_owner_uid']))->find();
                $from_info = $database_community_user_formid->get($community_info['group_owner_uid']);
                if ($from_info && $send_user && $send_user['group_openid']) {
                    // 审核中 推动模板消息 给群主
                    $apply_user = $database_user->field('nickname')->where(array('uid' => $this->_uid))->find();
                    $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));
                    $comm_nickname = $join_info['comm_nickname'] ? $join_info['comm_nickname'] : $apply_user['nickname'];
                    $data = array(
                        'touser' => $send_user['group_openid'],
                        'page' => 'pages/group/joinDetail/joinDetail?add_id=' . $add_id,
                        'form_id' => $from_info['formid'],
                        'keyword1' => $comm_nickname, // 姓名
                        'keyword2' => '申请加入群【' . $community_info['community_name'] . '】', // 申请项目
                        'keyword3' => '待审核', // 状态
                        'keyword4' => date('Y-m-d H:i:s'), // 日期
                        'id' => $from_info['id'], // fromid主键id
                    );
                    $info = $model->sendWxappTempMsg('AT0052', $data);
                    if ($info) {
                        $database_community_user_formid->del($from_info['fromid']);
                    }
                }
            }
            $this->returnCode(0, array('community_id' => $_POST['community_id'], 'add_id' => $add_id));
        } else {
            D()->rollback();
            $this->returnCode(1, array(), '操作失败！请重试~');
        }
    }

    // 个人群列表
    public function group_personal_list()
    {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        // 获取个人群列表 ->order('`fid` ASC,`sort` DESC,`id` ASC')
        $database_Community_join = D('Community_join');
        // 分页
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;


        $where = '`cj`.add_status = 3 AND `ci`.status = 1 AND `cj`.del_info = 1 AND `cj`.add_uid=' . $this->_uid;
        $personal_group_list = $database_Community_join->get_community_join($where, 10, $page);

        $data = array();
        if (!empty($personal_group_list['list'])) {
            $data = $personal_group_list;
            $time = time();
            $site_url = C('config.site_url');
            foreach ($data['list'] as &$val) {
                // 判断该群是否绑定微信群id
                if ($val['openGId']) {
                    $val['is_share'] = true;
                } else {
                    $val['is_share'] = false;
                }
                // 判断该群是否被禁止
                if ($val['status'] == 3) {
                    $val['is_ban'] = true;
                } else {
                    $val['is_ban'] = false;
                }
                //群头像
                if ($val['avatar']) {
                    $val['avatar'] = $site_url . $val['avatar'] . '?time=' . $time;
                } else {
                    $val['avatar'] = $site_url . '/static/wxapp/person.png';
                }
                // 群主上传的群头像
                if ($val['community_avatar']) {
                    $val['community_avatar'] = $site_url . '/upload/comm/' . $val['community_avatar'];
                }
                unset($val['openGId'], $val['income_money']);
                $val['add_time_display'] = $this->time_info($val['add_time'], '创建');
                $val['new_msg_time_display'] = $val['new_msg_time'] ? $this->time_info($val['new_msg_time'], '') : '';

                $val['new_msg'] = htmlspecialchars_decode($val['new_msg']);
            }
        } else {
            $data['list'] = array();
        }
        // 获取 云通讯 配置
        $data['sdkappid'] = C('config.cloud_communication_appid');
        $data['accountType'] = 884;
        import('@.ORG.qcloud_im');
        $qcloud_im = new qcloud_im(C('config.cloud_communication_appid'), 'user_' . $this->_uid);
        $data['UserSig'] = $qcloud_im->usersig;
        $data['Identifier'] = 'user_' . $this->_uid;
        $this->returnCode(0, $data);
    }


    // 群列表假删除
    public function group_list_del()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $database_community_join = D('Community_join');
        // 获取对应数据
        $Community_info = $database_community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $this->_uid))->find();
        if (empty($Community_info)) {
            $this->returnCode('70000009');
        }
        if ($Community_info['add_status'] != 3) {
            $this->returnCode('70000010');
        }
        $dis = $database_community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $this->_uid))->data(array('del_info' => 2))->save();

        if ($dis) {
            $this->returnCode(0, array('add_id' => $dis));
        } else {
            $this->returnCode(1, array(), '删除失败！请重试~');
        }
    }


    // 群置顶解除置顶
    public function group_set_top()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        // 置顶 置顶时间越新越靠前

        $community_id = $_POST['community_id'];
        $uid = $this->_uid;
        // 过滤操作权限
        $database_community_join = D('Community_join');
        $condition_info['community_id'] = $community_id;
        $condition_info['add_uid'] = $uid;
        $info = $database_community_join->field('community_id, set_top')->where($condition_info)->find();
        if (empty($info)) {
            $this->returnCode('70000010');
        }
        $data = array();
        if (empty($info['set_top'])) {
            $data['set_top_time'] = time();
            $data['set_top'] = intval($data['set_top_time']);
        } else {
            $data['set_top_time'] = 0;
            $data['set_top'] = 0;
        }
        $database_community_join = D('Community_join');
        $join_id = $database_community_join->where(array('community_id' => $community_id, 'add_uid' => $uid))->data($data)->save();
        if ($join_id) {
            $this->returnCode(0, array('add_id' => $join_id));
        } else {
            $this->returnCode(1, array(), '操作失败！请重试~');
        }
    }


    // 群管理页
    // 群信息
    public function group_single_info()
    {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $database_Community_info = D('Community_info');
        $where = array();
        $where['community_id'] = $_POST['community_id'];
        $where['status'] = 1;
        $community_info = $database_Community_info->get_community_list($where, 1);

        if (empty($community_info['list'])) {
            $this->returnCode('70000013');
        }
        $join_info = reset($community_info['list']);
        // 获取用户信息
        $database_User = D('User');
        $add_user = $database_User->field(true)->where(array('uid' => $this->_uid))->find();
        if (empty($add_user['phone'])) {
            // 手机未绑定
            $join_info['bind_phone'] = false;
        } else {
            $join_info['bind_phone'] = true;
        }
        if ($join_info['income_money']) unset($join_info['income_money']);
        if ($join_info['openGId']) unset($join_info['openGId']);
        if ($join_info['group_owner_uid'] == $this->_uid) {
            $join_info['is_owner'] = true;
        } else {
            $join_info['is_owner'] = false;
        }
        // 查询一下群商城
        $condition_info['group_owner_uid'] = $join_info['group_owner_uid'];
        $condition_info['community_id'] = $join_info['community_id'];
        $database_community_bind_shop = D('community_bind_shop');
        $bind_shop = $database_community_bind_shop->field(true)->where($condition_info)->find();
        if (!empty($bind_shop)) {
            $join_info['store_id'] = $bind_shop['store_id'];
            $join_info['bind_shop'] = true;
            $join_info['bind_shop_url'] = C('config.site_url') . '/wap.php?g=Wap&c=Mall&a=store&store_id=' . $bind_shop['store_id'];
        } else {
            $join_info['store_id'] = '';
            $join_info['bind_shop'] = false;
            $join_info['bind_shop_url'] = '';
        }
        // 添加判断  是否显示 群商城 和 群聊天
        // 总开关  是否开启群聊  默认开启 （0开启 1关闭）
        $system_community_chat_switch = C('config.community_chat_switch');
        // 总开关 是否开启群绑定商城（0开启 1关闭）
        $system_community_shop_switch = C('config.community_shop_switch');
        // 群开关 群主是否开启群聊天（1开启 2关闭）
        $community_owner_chat_switch = C('config.community_owner_chat_switch');

        // 群开关 是否开启群聊  默认开启 （0开启 1关闭）
        $community_chat_switch = $join_info['community_chat_switch'];
        // 群开关 是否开启群绑定商城（0开启 1关闭）
        $community_shop_switch = $join_info['community_shop_switch'];
        if (!empty($system_community_chat_switch) && $system_community_chat_switch == 1) {
            $join_info['is_chat'] = false;
        } elseif (!empty($community_owner_chat_switch) && $community_owner_chat_switch == 1) {
            $join_info['is_chat'] = false;
        } else {
            if (!empty($community_chat_switch) && $community_chat_switch == 1) {
                $join_info['is_chat'] = false;
            } else {
                $join_info['is_chat'] = true;
            }
        }
        if (!empty($system_community_shop_switch) && $system_community_shop_switch == 1) {
            $join_info['is_shop'] = false;
        } else {
            if (!empty($community_shop_switch) && $community_shop_switch == 1) {
                $join_info['is_shop'] = false;
            } else {
                $join_info['is_shop'] = true;
            }
        }
        $join_info['site_name'] = C('config.site_name');
        $join_info['site_url'] = C('config.site_url');
        // 获取群文件和群相册相关权限
        $application = D('Community_application_development')->community_application_development($_POST['community_id']);
        // 群文件应用的情况下，控制是否可以其他人创建文件夹
        if (!empty($application['is_community_file']) && $application['is_community_file'] == 1) {
            $join_info['is_add_folder'] = $application['is_add_folder'];
        }
        // 群相册应用的情况下，控制是否可以其他人创建相册
        if (!empty($application['is_community_album']) && $application['is_community_album'] == 1) {
            $join_info['is_add_album'] = $application['is_add_album'];
        }

        // 群主设定是否应用 群商城应用 1 是 2 否 默认 是
        if (!empty($application['is_community_shop'])) {
            $join_info['is_community_shop'] = $application['is_community_shop'];
        }

        $this->returnCode(0, $join_info);
    }


    // 解散群
    public function group_dissolution()
    {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $database_community = D('Community_info');
        // 获取对应数据
        $Community_info = $database_community->where(array('community_id' => $_POST['community_id']))->find();
        if (empty($Community_info)) {
            $this->returnCode('70000005');
        }
        if ($Community_info['group_owner_uid'] != $uid) {
            $this->returnCode('70000006');
        }
        if ($Community_info['status'] == 2) {
            $this->returnCode('70000007');
        }
        if ($Community_info['status'] == 3) {
            $this->returnCode('70000008');
        }
        D()->startTrans();
        $dis = $database_community->where(array('community_id' => $_POST['community_id']))->data(array('status' => 2))->save();
        if ($dis) {
            $database_Community_join = D('Community_join');
            // 群解散成功 查询一下处于待审核状态的申请者, 如果有，进行退款
            $join_check = $database_Community_join->where(array('community_id' => $_POST['community_id'], 'add_status' => 2))->select();
            if (!empty($join_check)) {
                foreach ($join_check as $val) {
                    if ($val['add_uid'] && $val['charge_money'] > 0) {
                        $add_result = D('User')->add_money($val['add_uid'], $val['charge_money'], '解散群,申请入群费用退款，增加余额');
                        if (!$add_result['error_code']) {
                            D()->rollback();
                            $this->returnCode(1, array(), '操作失败！请重试~');
                        }
                    }
                }
            }
            // 解散群成功 同步解散群到云通讯
            $group_id = $_POST['community_id'];
            $ret_group = $database_community->qcloud_destroy_group($group_id);
            if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                D('Community_join')->create_comm_plan($group_id);
                D()->commit();
                $this->returnCode(0, '解散成功！');
            } else {
                D()->rollback();
                $this->returnCode(1, array(), '解散失败！请重试~');
            }
        } else {
            $this->returnCode(1, array(), '解散失败！请重试~');
        }
    }


    // 群主禁言解禁
    public function group_owner_excuse()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        // 过滤必要参数
        if (empty($_POST['excuse_status'])) {
            $this->returnCode('70000012');
        }
        // 如果禁言就需要传禁言时间长度
        if (intval($_POST['excuse_status']) == 2) {
            if (empty($_POST['excuse_time_length'])) {
                $this->returnCode('70000012');
            }
        }
        $community_id = $_POST['community_id'];
        // 过滤群用户id
        if (empty($_POST['add_uid'])) {
            $this->returnCode('70000011');
        }
        $add_uid = $_POST['add_uid'];
        $add_user = D('User')->field(true)->where(array('uid' => $add_uid))->find();
        if (empty($add_user)) {
            $this->returnCode('70000011');
        }
        $uid = $this->_uid;
        // 过滤操作权限
        $database_community_info = D('Community_info');
        $condition_info['community_id'] = $community_id;
        $condition_info['group_owner_uid'] = $uid;
        $info = $database_community_info->field('community_id, community_name')->where($condition_info)->find();
        if (empty($info)) {
            $this->returnCode('70000010');
        }
        $data = array();
        if (intval($_POST['excuse_status']) == 2) {
            $time_length = intval($_POST['excuse_time_length']);
            $data['excuse_end_time'] = strtotime("+" . $time_length . " minute");
            $data['excuse_time'] = time();
            $data['excuse_time_length'] = intval($_POST['excuse_time_length']);
        } else {
            $data['excuse_end_time'] = 0;
            $data['excuse_time_length'] = 0;
            $data['excuse_time'] = 0;
        }
        $data['excuse_status'] = intval($_POST['excuse_status']);
        $database_community_join = D('Community_join');
        D()->startTrans();
        $join_id = $database_community_join->where(array('community_id' => $community_id, 'add_uid' => $add_uid))->data($data)->save();
        if ($join_id) {
            $shutUpTime = $data['excuse_time_length'] * 60;
            $members_account = array();
            $members_account[] = 'user_' . $add_uid;
            // 禁言或者解禁成功 同步到云通讯
            $group_id = $_POST['community_id'];
            $ret_group = $database_community_info->qcloud_forbid_send_msg($group_id, $members_account, $shutUpTime);
            if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                // 加入群成功 同步到发一条系统消息云通讯
                if ($_POST['excuse_status'] == 2) {
                    $content = '你被群主禁言';
                } else {
                    $content = '你被群主解除禁言';
                }
                $toMembers_account = array();
                $toMembers_account[] = 'user_' . $add_uid;
                $ret_group = $database_community_info->qcloud_send_group_system_notification($group_id, $content, $toMembers_account);
                if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                    D()->commit();
                    $this->returnCode(0, '操作成功！');
                } else {
                    D()->rollback();
                    $this->returnCode(1, array(), '操作失败！请重试~');
                }
            } else {
                D()->rollback();
                $this->returnCode(1, array(), '操作失败！请重试~');
            }
        } else {
            $this->returnCode(1, array(), '操作失败！请重试~');
        }
    }


    // 群主批量踢人
    public function group_owner_kicking()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $community_id = $_POST['community_id'];
        // 单个多个群用户id
        if (empty($_POST['add_id_arr'])) {
            $this->returnCode('70000011');
        }
        $uid = $this->_uid;
        // 过滤操作权限
        $database_community_info = D('Community_info');
        $condition_info['community_id'] = $community_id;
        $condition_info['group_owner_uid'] = $uid;
        $info = $database_community_info->field('community_id, community_name')->where($condition_info)->find();
        if (empty($info)) {
            $this->returnCode('70000010');
        }
        // 批量删除
        if (!empty($_POST['add_id_arr']) && is_array($_POST['add_id_arr'])) {
            $database_community_join = D('Community_join');
            D()->startTrans();
            $join_count = $database_community_join->where(array('community_id' => $community_id, 'add_status' => 3))->count();
            $cout_add_id = 0;
            $memberToDel_account = array();
            $group_owner = false;
            $temp_msg = array();
            $m_community_card_bind = M('Community_card_bind');
            foreach ($_POST['add_id_arr'] as $val) {
                if (!$val) continue;
                // 首先查询一下 获取对应用户id  然后获取用户信息 进行推送
                $add_info = $database_community_join->field('add_uid, group_owner')->where(array('add_id' => $val))->find();
                if ($add_info) {
                    // 然后对应进行模板推送
                    $temp_msg[] = $add_info['add_uid'];
                } else {
                    continue;
                }
                if ($add_info['group_owner'] == 2) {
                    $group_owner = true;
                }
                $join_id = $database_community_join->where(array('community_id' => $community_id, 'add_id' => $val))->delete();
                if (!$join_id) {
                    D()->rollback();
                    $this->returnCode(1, array(),'操作失败！请重试~');
                }
                $where_card = array('community_id' => $community_id, 'join_uid' => $add_info['add_uid'], 'join_status' => 1);
                if ($m_community_card_bind->where($where_card)->field('join_uid')->find()) {
                    $join_card = $m_community_card_bind->where($where_card)->data(array('join_status' => 2))->save();
                    if (!$join_card) {
                        D()->rollback();
                        $this->returnCode(1, array(),'操作失败！请重试~');
                    }
                }
                $cout_add_id = $cout_add_id + 1;
                $memberToDel_account[] = 'user_' . $add_info['add_uid'];
            }
            if ($group_owner && $join_count != $cout_add_id) {
                D()->rollback();
                $this->returnCode('70000025');
            }

            if (intval($join_count) == intval($cout_add_id)) {
                // 群解散
                $dis = D('Community_info')->where(array('community_id' => $community_id))->data(array('status' => 2, 'set_time' => time()))->save();
                if ($dis) {
                    // 群解散成功 查询一下处于待审核状态的申请者, 如果有，进行退款
                    $join_check = $database_community_join->where(array('community_id' => $community_id, 'add_status' => 2))->select();
                    if (!empty($join_check)) {
                        foreach ($join_check as $val) {
                            if ($val['add_uid'] && $val['charge_money'] > 0) {
                                $add_result = D('User')->add_money($val['add_uid'], $val['charge_money'], '解散群,申请入群费用退款，增加余额');
                                if (!$add_result['error_code']) {
                                    D()->rollback();
                                    $this->returnCode(1, array(), '操作失败！请重试~');
                                }
                            }
                        }
                    }

                    // 解散群成功 同步解散群到云通讯
                    $group_id = $community_id;
                    $ret_group = $database_community_info->qcloud_destroy_group($group_id);
                    if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                        D('Community_join')->create_comm_plan($group_id, 'comm_dissolution_group', $temp_msg);
                        D()->commit();
                        $this->returnCode(0, array('is_dissolution' => true, 'msg' => "解散"));
                    } else {
                        if ($ret_group['ErrorCode'] == 10010) {
                            D()->rollback();
                            $this->returnCode(1, array('is_dissolution' => true, 'msg' => "解散"), '群已经解散！');
                        }
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                } else {
                    D()->rollback();
                    $this->returnCode(1, array(), '操作失败！请重试~');
                }
                $this->returnCode(0, array('is_dissolution' => true, 'msg' => "解散"));
            } else {
                // 群成员移除
                // 群成员移除成功 同步到云通讯
                $group_id = $community_id;
                $ret_group = $database_community_info->qcloud_delete_group_member($group_id, $memberToDel_account);
                if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                    // 然后对应进行模板推送
                    // 推送为被移出群 的消息
                    if ($temp_msg) {
                        D('Community_join')->create_comm_plan($community_id, 'comm_owner_kicking', $temp_msg);
                    }
                    D()->commit();

                    // 群头像 群主未上传群聊头像
                    $community_info = M('Community_info')->where(array('community_id' => $_POST['community_id']))->field('community_avatar')->find();
                    if (!$community_info['community_avatar']) {
                        $database_community_info->change_group_avatar($group_id, $_POST['add_id_arr']);
                    }
                    $this->returnCode(0, '操作成功！');
                } else {
                    if ($ret_group['ErrorCode'] == '10004') {
                        D()->commit();
                        // 群头像 群主未上传群聊头像
                        $community_info = M('Community_info')->where(array('community_id' => $_POST['community_id']))->field('community_avatar')->find();
                        if (!$community_info['community_avatar']) {
                            $database_community_info->change_group_avatar($group_id, $_POST['add_id_arr']);
                        }
                        $this->returnCode(0, '操作成功！');
                    }
                    D()->rollback();
                    if ($ret_group['ErrorCode'] == '10007') {
                        $this->returnCode('70000025');
                    }
                    $this->returnCode(1, array(), '操作失败！请重试~');
                }
            }
            D()->commit();
            $this->returnCode(0, '操作成功！');
        } else {
            $this->returnCode(1, array(), '操作失败！请重试~');
        }
    }


    // 群主移出某成员/成员自己退群
    public function group_retreat()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }

        if (empty($_POST['add_id'])) {
            $this->returnCode('70000012');
        }
        $database_community_join = D('Community_join');
        $m_community_card_bind = M('Community_card_bind');
        $add_id = $_POST['add_id'];
        $add_info = $database_community_join->get_community_join_single($add_id);
        if (empty($add_info)) {
            $this->returnCode('70000017');
        }
        $community_id = $add_info['community_id'];
        $uid = $this->_uid;
        // 判断是群主移出还是自己退群
        // 过滤操作权限
        $database_community_info = D('Community_info');
        $condition_info['community_id'] = $community_id;
        $condition_info['group_owner_uid'] = $uid;
        $info = $database_community_info->field('community_id, community_name')->where($condition_info)->find();
        if (!empty($_POST['add_id'])) {
            D()->startTrans();
            if (empty($info) && $add_info['add_uid'] == $uid) {
                $add_info = $database_community_join->get_community_join_single($add_id);
                $join_id = $database_community_join->where(array('community_id' => $community_id, 'add_id' => $add_id))->delete();
                if ($join_id) {
                    $member_number = $database_community_info->where(array('community_id' => $community_id))->setDec('member_number');
                    if (empty($member_number)) {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                    $where_card = array('community_id' => $community_id, 'join_uid' => $add_info['add_uid'], 'join_status' => 1);
                    if ($m_community_card_bind->where($where_card)->field('join_uid')->find()) {
                        $join_card = $m_community_card_bind->where($where_card)->data(array('join_status' => 2))->save();
                        if (!$join_card) {
                            D()->rollback();
                            $this->returnCode(1, array(),'操作失败！请重试~');
                        }
                    }
                    // 群成员移除成功 同步到云通讯
                    $group_id = $community_id;
                    $memberToDel_account = array();
                    $memberToDel_account[] = 'user_' . $add_info['add_uid'];
                    $ret_group = $database_community_info->qcloud_delete_group_member($group_id, $memberToDel_account, 1);
                    if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                        // 群头像 群主未上传群聊头像
                        $community_info = M('Community_info')->where(array('community_id' => $_POST['community_id']))->field('community_avatar')->find();
                        if (!$community_info['community_avatar']) {
                            $database_community_info->change_group_avatar($group_id, $add_info['add_uid']);
                        }
                        D()->commit();
                        $this->returnCode(0, '操作成功！');
                    } else {
                        D()->rollback();
                        if ($ret_group['ErrorCode'] == '10007') {
                            $this->returnCode('70000025');
                        }
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                } else {
                    D()->rollback();
                    $this->returnCode(1, array(), '操作失败！请重试~');
                }
            } elseif (!empty($info) && $add_info['add_uid'] != $uid) {
                $add_info = $database_community_join->get_community_join_single($add_id);
                $join_id = $database_community_join->where(array('community_id' => $community_id, 'add_id' => $add_id))->delete();
                if ($join_id) {
                    $member_number = $database_community_info->where(array('community_id' => $community_id))->setDec('member_number');
                    if (empty($member_number)) {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                    $where_card = array('community_id' => $community_id, 'join_uid' => $add_info['add_uid'], 'join_status' => 1);
                    if ($m_community_card_bind->where($where_card)->field('join_uid')->find()) {
                        $join_card = $m_community_card_bind->where($where_card)->data(array('join_status' => 2))->save();
                        if (!$join_card) {
                            D()->rollback();
                            $this->returnCode(1, array(),'操作失败！请重试~');
                        }
                    }
                    // 群成员移除成功 同步到云通讯
                    $group_id = $community_id;
                    $memberToDel_account = array();
                    $memberToDel_account[] = 'user_' . $add_info['add_uid'];
                    $ret_group = $database_community_info->qcloud_delete_group_member($group_id, $memberToDel_account, 0);
                    if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                        // 群头像 群主未上传群聊头像
                        $community_info = M('Community_info')->where(array('community_id' => $_POST['community_id']))->field('community_avatar')->find();
                        if (!$community_info['community_avatar']) {
                            $database_community_info->change_group_avatar($group_id, $add_info['add_uid']);
                        }
                        D()->commit();
                        if ($add_info) {
                            $database_User = D('User');
                            $apply_user = $database_User->field('nickname, group_openid')->where(array('uid' => $add_info['add_uid']))->find();
                            // 然后对应进行模板推送
                            // 推送为被移出群 的消息
                            $database_community_user_formid = D('Community_user_formid');
                            $from_info = $database_community_user_formid->get($add_info['add_uid']);
                            if ($from_info && $apply_user && $apply_user['group_openid']) {
                                // 审核中 推动模板消息 给群主
                                $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));
                                $comm_nickname = $add_info['comm_nickname'] ? $add_info['comm_nickname'] : $apply_user['nickname'];
                                $msg = array(
                                    'touser' => $apply_user['group_openid'],
                                    'page' => 'pages/group/groupList/groupList',
                                    'form_id' => $from_info['formid'],
                                    'keyword1' => $comm_nickname, // 用户昵称
                                    'keyword2' => '群主移出', // 退出方式
                                    'keyword3' => '群主将您移出【' . $info['community_name'] . '】', // 备注
                                    'keyword4' => date('Y-m-d H:i:s'), // 退出时间
                                );
                                $info = $model->sendWxappTempMsg('AT0751', $msg);
                                if ($info) {
                                    $database_community_user_formid->del($from_info['formid']);
                                }
                            }
                        }
                        $this->returnCode(0, '操作成功！');
                    } else {
                        D()->rollback();
                        if ($ret_group['ErrorCode'] == '10007') {
                            $this->returnCode('70000025');
                        }
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                } else {
                    D()->rollback();
                    $this->returnCode(1, array(), '操作失败！请重试~');
                }
            } else {
                $this->returnCode('70000010');
            }
        } else {
            $this->returnCode('70000012');
        }
    }


    // 群昵称
    public function group_set_nickname()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        // 过滤昵称
        if (empty($_POST['comm_nickname'])) {
            $this->returnCode('70000004');
        }
        $database_community_join = D('Community_join');
        $database_community_info = D('Community_info');
        // 获取对应数据
        $Community_join = $database_community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $this->_uid))->find();
        if (empty($Community_join)) {
            $this->returnCode('70000009');
        }
        // 获取对应数据
        $Community_info = $database_community_info->where(array('community_id' => $_POST['community_id']))->find();

        // 判断昵称是否可改
        if ($Community_info['is_nickname'] != 2 && $Community_info['group_owner_uid'] != $this->_uid) {
            $this->returnCode('70000016');
        }
        D()->startTrans();
        $dis = $database_community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $this->_uid))->data(array('comm_nickname' => $_POST['comm_nickname']))->save();
        if ($dis) {
            // 修改昵称成功 同步到云通讯
            $group_id = $_POST['community_id'];
            $ret_group = $database_community_info->qcloud_modify_group_member_info($group_id, $this->_uid, $_POST['comm_nickname']);
            if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                D()->commit();
                $this->returnCode(0, '操作成功！');
            } else {
                D()->rollback();
                $this->returnCode(1, array(), '操作失败！请重试~');
            }
            $this->returnCode(0, '操作成功！');
        } else {
            D()->rollback();
            $this->returnCode(1, '操作失败！请重试~');
        }
    }


    // 群成员
    public function group_members_list()
    {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000012');
        }
        $where = array();
        // 昵称模糊查询
        if ($_POST['comm_nickname']) {
            $where['comm_nickname'] = array('like', '%' . $_POST['comm_nickname'] . '%');
        }
        // 查询一下群信息
        $database_community_info = D('Community_info');
        $community_info = $database_community_info->where(array('community_id' => $_POST['community_id']))->find();
        if (empty($community_info)) {
            $this->returnCode('70000007');
        }
        if ($community_info['status'] == 3) {
            $this->returnCode('70000024');
        }
        // 分页处理

        $database_Community_join = D('Community_join');

        $where['community_id'] = $_POST['community_id'];
        $where['group_owner'] = 1;
        $where['add_status'] = 3;
        $get_owner = true;
        $members = array();
        $member_number = $database_Community_join->where(array('add_status' => 3, 'community_id' => $_POST['community_id']))->count();
        if (!empty($_POST['more'])) {
            $join_count = $database_Community_join->where(array('add_status' => 3, 'community_id' => $_POST['community_id']))->count();
            import('@.ORG.comm_page');
            $p = new Page($join_count, 20);
            $firstRow = $p->firstRow;
            $members['totalPage'] = $p->totalPage;
            if ($firstRow == 0) {
                $listRows = 19;
                // 需要获取群主信息
                $get_owner = true;
            } else {
                $firstRow = $p->firstRow - 1;
                $listRows = $p->listRows;
                $get_owner = false;
            }
            $group_members_info = $database_Community_join->where($where)->limit($firstRow . ',' . $listRows)->select();
        } else {
            if ($community_info['group_owner_uid'] == $uid) {
                $group_members_info = $database_Community_join->where($where)->limit('0,7')->select();
            } else {
                $group_members_info = $database_Community_join->where($where)->limit('0,8')->select();
            }
        }
        $database_User = D('User');
        if (!empty($group_members_info)) {
            $members['list'] = array();
            foreach ($group_members_info as $val) {
                $add_user = $database_User->field('nickname, phone, avatar')->where(array('uid' => $val['add_uid']))->find();
                if (!empty($add_user)) {
                    if ($add_user['nickname']) $val['nickname'] = $add_user['nickname'];
                    if ($add_user['phone']) $val['phone'] = substr_replace($add_user['phone'], '****', 3, 4);
                    if (empty($add_user['avatar'])) {
                        // 没有头像取默认头像
                        $val['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                    } else {
                        $val['avatar'] = $add_user['avatar'];
                    }
                } else {
                    // 没有头像取默认头像
                    $val['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                }
                $val['is_owner'] = false;
                if ($val['add_uid'] == $uid) {
                    $val['is_user'] = true;
                } else {
                    $val['is_user'] = false;
                }
                $members['list'][] = $val;
            }
        } else {
            $members['list'] = array();
        }
        if ($get_owner) {
            $where['group_owner'] = 2;
            $group_members_owner = $database_Community_join->where($where)->find();
            if (!empty($group_members_owner)) {
                $owner_user = $database_User->field(true)->where(array('uid' => $group_members_owner['add_uid']))->find();
                if ($owner_user['nickname']) $group_members_owner['nickname'] = $owner_user['nickname'];
                if ($owner_user['phone']) $group_members_owner['phone'] = substr_replace($owner_user['phone'], '****', 3, 4);
                if (empty($owner_user['avatar'])) {
                    // 没有头像取默认头像
                    $group_members_owner['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                } else {
                    $group_members_owner['avatar'] = $owner_user['avatar'];
                }
                $group_members_owner['is_owner'] = true;
                if ($group_members_owner['add_uid'] == $uid) {
                    $group_members_owner['is_user'] = true;
                } else {
                    $group_members_owner['is_user'] = false;
                }
                array_unshift($members['list'], $group_members_owner);
            }
        }
        // 查询一下群商城
        $condition_info['group_owner_uid'] = $community_info['group_owner_uid'];
        $condition_info['community_id'] = $community_info['community_id'];
        $database_community_bind_shop = D('community_bind_shop');
        $bind_shop = $database_community_bind_shop->field(true)->where($condition_info)->find();
        if (!empty($bind_shop)) {
            $members['bind_shop'] = true;
            $members['bind_shop_url'] = C('config.site_url') . '/wap.php?g=Wap&c=Mall&a=store&store_id=' . $bind_shop['store_id'];
        } else {
            $members['bind_shop'] = false;
            $members['bind_shop_url'] = '';
        }
        // 判断是否为群主
        if ($community_info['group_owner_uid'] == $uid) {
            $members['is_owner'] = true;
        } else {
            $members['is_owner'] = false;
        }
        if ($community_info['member_number'] != $member_number) {
            // 如果统计数据出错，重写改写一下
           $database_community_info->where(array('community_id' => $_POST['community_id']))->data(array('member_number' => $member_number))->save();
        }
        $group_members_myself = $database_Community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $uid))->find();
        $members['community_name'] = $community_info['community_name'];
        $members['member_number'] = $member_number;
        $members['comm_nickname'] = $group_members_myself['comm_nickname'];
        $members['set_top'] = $group_members_myself['set_top'];
        $members['community_id'] = $_POST['community_id'];
        $members['is_nickname'] = ($community_info['is_nickname'] == 2) ? true : false;
        $members['add_id'] = $group_members_myself['add_id'];

        // 添加判断  是否显示 群商城 和 群聊天
        // 总开关 是否开启群绑定商城（0开启 1关闭）
        $system_community_shop_switch = C('config.community_shop_switch');
        // 群开关 是否开启群绑定商城（0开启 1关闭）
        $community_shop_switch = $community_info['community_shop_switch'];
        if (!empty($system_community_shop_switch) && $system_community_shop_switch == 1) {
            $members['is_shop'] = false;
        } else {
            if (!empty($community_shop_switch) && $community_shop_switch == 1) {
                $members['is_shop'] = false;
            } else {
                $members['is_shop'] = true;
            }
        }
        $members['group_mode'] = $community_info['group_mode'];

        $this->returnCode(0, $members);
    }


    // 群成员详细
    public function group_members_info()
    {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['add_id'])) {
            $this->returnCode('70000012');
        }
        $where = array();
        // 查询一下群信息
        $database_community_info = D('Community_info');

        $database_Community_join = D('Community_join');
        $where['add_id'] = $_POST['add_id'];
        $group_members_info = $database_Community_join->where($where)->find();
        if (empty($group_members_info)) {
            $this->returnCode('70000017');
        }

        $community_info = $database_community_info->where(array('community_id' => $group_members_info['community_id']))->find();
        if (empty($community_info)) {
            $this->returnCode('70000007');
        }
        if ($community_info['status'] == 3) {
            $this->returnCode('70000024');
        }


        $database_User = D('User');

        if (!empty($group_members_info)) {
            $add_user = $database_User->field(true)->where(array('uid' => $group_members_info['add_uid']))->find();
            if (!empty($add_user)) {
                if ($add_user['nickname']) $group_members_info['nickname'] = $add_user['nickname'];
                if ($add_user['phone']) $group_members_info['phone'] = substr_replace($add_user['phone'], '****', 3, 4);
                if (empty($add_user['avatar'])) {
                    // 没有头像取默认头像
                    $group_members_info['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                } else {
                    $group_members_info['avatar'] = $add_user['avatar'];
                }
            }
            // 判断是否为群主
            if ($group_members_info['group_owner'] == 2) {
                $group_members_info['is_user_owner'] = true;
            } else {
                $group_members_info['is_user_owner'] = false;
            }

            // 判断获取信息的是否为群主
            if ($community_info['group_owner_uid'] == $uid) {
                $group_members_info['is_owner'] = true;
            } else {
                $group_members_info['is_owner'] = false;
            }

            if ($group_members_info['add_uid'] == $uid) {
                $group_members_info['is_user'] = true;
            } else {
                $group_members_info['is_user'] = false;
            }
            $members = $group_members_info;
        } else {
            $members = array();
        }
        // 处理一下禁言时间显示
        if ($members['excuse_status'] == 2) {
            $timestamp = intval($members['excuse_end_time']) - intval(time());
            if ($timestamp > 0) {
                $members['excuse_time_info'] = $this->excuse_time_info($timestamp);
            }
        }
        $members['add_time_info'] = date('Y-m-d H:i:s', $members['add_time']);

        $this->returnCode(0, $members);
    }


    // 个人中心
    // 群主待办事项外层消息外层
    public function group_owner_backlog_list()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $database_Community_join = D('Community_join');

        $where = '`ci`.group_owner_uid = ' . $this->_uid . ' AND `ci`.status = 1 AND `cj`.add_uid <> ' . $this->_uid . ' AND `cj`.standby_status = 1 AND `cj`.add_status = 2';

        $personal_group_list = $database_Community_join->get_community_info_join($where, 1);
        $tip_message = array();
        if (!empty($personal_group_list['list'])) {
            $database_User = D('User');
            $personal_group_list = reset($personal_group_list['list']);
            if ($personal_group_list['income_money']) unset($personal_group_list['income_money']);
            if ($personal_group_list['openGId']) unset($personal_group_list['openGId']);
            $add_user = $database_User->field(true)->where(array('uid' => $personal_group_list['add_uid']))->find();
            $add_name = $personal_group_list['comm_nickname'];
            if ($add_user['nickname'] && !$add_name) $add_name = $add_user['nickname'];
            if ($add_user['phone'] && !$add_name) $add_name = substr_replace($add_user['phone'], '****', 3, 4);
//            $tip_message['time_info'] = $this->time_info($personal_group_list['add_time']);
            $tip_message['tip_info'] = "来自【" . $personal_group_list['community_name'] . "】的" . $add_name . "申请加群";
        } else {
            $tip_message['tip_info'] = "暂无新消息";
        }
        $tip_message['tip_name'] = '申请加群消息';
        $this->returnCode(0, array('backlog_notice' => $tip_message, 'system_notice' => array('tip_name' => '系统通知消息', 'tip_info' => '暂无新消息')));
    }


    // 群主待办事项
    public function group_owner_backlog()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $database_Community_join = D('Community_join');
        if (empty($_POST['verification'])) {
            $this->returnCode('70000012');
        }
        $where = '`ci`.group_owner_uid = ' . $this->_uid . ' AND `ci`.status = 1 AND `cj`.add_uid <> ' . $this->_uid . ' AND `cj`.standby_status = 1';
        if ($_POST['verification'] == 1) {
            // 待验证
            $where .= ' AND `cj`.add_status = 2';
        } else {
            // 已经验证， 包括同意，拒绝
            $where .= ' AND `cj`.add_status > 2';
        }
        // 分页
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

        $personal_group_list = $database_Community_join->get_community_info_join($where, 6, $page);
        if (!empty($personal_group_list['list'])) {
            $database_User = D('User');
            foreach ($personal_group_list['list'] as &$val) {
                if ($val['income_money']) unset($val['income_money']);
                if ($val['openGId']) unset($val['openGId']);
                $add_user = $database_User->field(true)->where(array('uid' => $val['add_uid']))->find();
                if (!empty($add_user)) {
                    if ($add_user['nickname']) $val['nickname'] = $add_user['nickname'];
                    if ($add_user['phone']) $val['phone'] = substr_replace($add_user['phone'], '****', 3, 4);
                    if (empty($add_user['avatar'])) {
                        // 没有头像取默认头像
                        $val['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                    } else {
                        $val['avatar'] = $add_user['avatar'];
                    }
                } else {
                    // 没有头像取默认头像
                    $val['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                }
                $val['time_info'] = $this->time_info($val['add_group_time'], ' 申请');
            }
        } else {
            $personal_group_list['list'] = array();
        }
        $this->returnCode(0, $personal_group_list);
    }


    // 群主待办事项详细信息
    public function group_owner_backlog_detail()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        if (empty($_POST['add_id'])) {
            $this->returnCode('70000012');
        }
        $database_Community_join = D('Community_join');

        $where = '`cj`.add_id = ' . $_POST['add_id'];
        $personal_group_info = $database_Community_join->get_community_join($where, 1);
        if (!empty($personal_group_info['list'])) {
            $single_info = reset($personal_group_info['list']);
            $database_User = D('User');
            if ($single_info['income_money']) unset($single_info['income_money']);
            if ($single_info['openGId']) unset($single_info['openGId']);
            $add_user = $database_User->field(true)->where(array('uid' => $single_info['add_uid']))->find();
            if (!empty($add_user)) {
                if ($add_user['nickname']) $single_info['nickname'] = $add_user['nickname'];
                if ($add_user['phone']) $single_info['phone'] = substr_replace($add_user['phone'], '****', 3, 4);
                if (empty($add_user['avatar'])) {
                    // 没有头像取默认头像
                    $single_info['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                } else {
                    $single_info['avatar'] = $add_user['avatar'];
                }
            } else {
                // 没有头像取默认头像
                $single_info['avatar'] = C('config.site_url') . '/static/avatar.jpg';
            }
            $single_info['time_info'] = $this->time_info($single_info['add_group_time']);
        }
        $this->returnCode(0, $single_info);
    }


    // 群主审核
    public function group_check_info()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $database_Community_join = D('Community_join');
        if (empty($_POST['add_id'])) {
            $this->returnCode('70000012');
        }
        if (empty($_POST['add_status'])) {
            $this->returnCode('70000012');
        }
        // 拒绝必须填写拒绝理由
        if ($_POST['add_status'] == 4 && empty($_POST['refusing_remark'])) {
            $this->returnCode('70000012');
        }
        $where = array();
        $add_id = $_POST['add_id'];
        $add_status = $_POST['add_status'];
        $where['add_id'] = $add_id;


        $where = '`ci`.group_owner_uid = ' . $this->_uid . ' AND `ci`.status = 1 AND `cj`.add_uid <> ' . $this->_uid . ' AND `cj`.standby_status = 1 AND `cj`.`add_id`=' . $add_id;
        $personal_group_info = $database_Community_join->get_community_join($where, -1);
        if (empty($personal_group_info['list'])) {
            $this->returnCode('70000010');
        }
        $personal_group_info = reset($personal_group_info['list']);
        if ($personal_group_info['group_owner_uid'] != $this->_uid) {
            $this->returnCode('70000010');
        }
        D()->startTrans();
        $add_uid = $personal_group_info['add_uid'];
        if ($add_status == 4) {
            // 先修改状态，再退款
            $setStatus = $database_Community_join->where(array('add_id' => $add_id))->data(array('add_status' => 4, 'refusing_remark' => $_POST['refusing_remark']))->save();
            if ($setStatus) {
                // 状态修改完成 完成退款
                $add_user = D('User')->field(true)->where(array('uid' => $add_uid))->find();
                $add_name = '';

                if (floatval($personal_group_info['add_charge_money']) > 0) {
                    if ($personal_group_info['comm_nickname']) $add_name = $personal_group_info['comm_nickname'];
                    if ($add_user['nickname']) $add_name = $add_user['nickname'];
                    if ($add_user['phone'] && !$add_name) $add_name = substr_replace($add_user['phone'], '****', 3, 4);

                    $add_result = D('User')->add_money($add_uid, $personal_group_info['add_charge_money'], '拒绝入群,退款 ' . $add_name . ' 增加余额');
                    if ($add_result['error_code'] == 0) {
                        $database_Community_join->where(array('add_id' => $add_id))->data(array('add_status' => 5))->save();
                        //訂單狀態修改為已退款 考慮到管理員踢出成員或成員退出後又加入，訂單不止一個
                        // M('Community_order')->where(array('uid' => $add_id,'community_id'=>$personal_group_info['community_id'],'status'=>1))->data(array('status' => 3))->save();
                        D()->commit();
                    } else {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                } else {
                    D()->commit();
                }
            }
        } else if ($add_status == 3) {
            $setStatus = $database_Community_join->where(array('add_id' => $add_id))->data(array('add_status' => 3))->save();
            if ($setStatus) {
                // 加群成功添加成员数
                $database_Community_info = D('Community_info');
                $database_Community_info->where(array('community_id' => $personal_group_info['community_id']))->setInc('member_number');
                // 如果是别人邀请加入，记录邀请人邀请数
                if (!empty($personal_group_info['add_source'])) {
                    $database_Community_join->where(array('community_id' => $personal_group_info['community_id'], 'add_uid' => $personal_group_info['add_source']))->setInc('invitation_num');
                }
                //是否收费
                if (floatval($personal_group_info['add_charge_money']) > 0) {
                    $user_info = D('User')->get_user($personal_group_info['add_uid']);
                    $money = $personal_group_info['add_charge_money'];
                    $add_result = D('User')->add_money($personal_group_info['group_owner_uid'], $money, $user_info['nickname'] . '加入群【' . $personal_group_info['community_name'] . '】，增加余额');
                    // 收取平台费用
                    if (C('config.community_join_get_merchant_percent') > 0) {
                        $money = $money * C('config.community_join_get_merchant_percent') * 0.01;
                        $user_result = D('User')->user_money($personal_group_info['group_owner_uid'], $money, $user_info['nickname'] . '加入群【' . $personal_group_info['community_name'] . '】，平台抽成，减少余额');
                    }
                    if ($add_result['error_code'] == 0) {
                        // 加入群成功 同步到云通讯
                        $group_id = $personal_group_info['community_id'];
                        $ret_group = $database_Community_info->qcloud_add_group_member($group_id, $add_uid);
                        if (empty($ret_group) || $ret_group['ActionStatus'] != 'OK') {
                            D()->rollback();
                            $this->returnCode(1, array(), '加群失败！请重试~');
                        }
                        // 加入群成功 同步到发一条系统消息云通讯
                        $content = $personal_group_info['comm_nickname'] . '加入了本群';
                        $ret_group = $database_Community_info->qcloud_send_group_system_notification($group_id, $content);
                        if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                            // 群头像
                            $database_Community_info->change_group_avatar($group_id, $add_uid);
                            // 群头像 群主未上传群聊头像
                            $community_info = M('Community_info')->where(array('community_id' => $_POST['community_id']))->field('community_avatar')->find();
                            if (!$community_info['community_avatar']) {
                                $database_Community_info->change_group_avatar($group_id, $add_uid);
                            }
                            D()->commit();
                        } else {
                            D()->rollback();
                            $this->returnCode(1, array(), '操作失败！请重试~');
                        }
                    } else {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                } else {
                    // 加入群成功 同步到云通讯
                    $group_id = $personal_group_info['community_id'];
                    $ret_group = $database_Community_info->qcloud_add_group_member($group_id, $add_uid);
                    if (empty($ret_group) || $ret_group['ActionStatus'] != 'OK') {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                    // 加入群成功 同步到发一条系统消息云通讯
                    $content = $personal_group_info['comm_nickname'] . '加入了本群';
                    $ret_group = $database_Community_info->qcloud_send_group_system_notification($group_id, $content);
                    if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                        // 群头像 群主未上传群聊头像
                        $community_info = M('Community_info')->where(array('community_id' => $_POST['community_id']))->field('community_avatar')->find();
                        if (!$community_info['community_avatar']) {
                            $database_Community_info->change_group_avatar($group_id, $add_uid);
                        }
                        D()->commit();
                    } else {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                }
            } else {
                D()->rollback();
                $this->returnCode(1, array(), '操作失败！请重试~');
            }
        } else {
            $this->returnCode('70000010');
        }
        if ($add_status == 3 || $add_status == 4) {
            $database_user = D('User');
            $database_community_user_formid = D('Community_user_formid');
            $apply_user = $database_user->field('nickname, group_openid')->where(array('uid' => $personal_group_info['add_uid']))->find();
            $from_info = $database_community_user_formid->get($personal_group_info['add_uid']);
            if ($from_info && $apply_user && $apply_user['group_openid']) {
                // 审核中 推动模板消息 给群主
                $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));
                $comm_nickname = $personal_group_info['comm_nickname'] ? $personal_group_info['comm_nickname'] : $apply_user['nickname'];
                $database_community_info = M('Community_info');
                $community_info = $database_community_info->where(array('community_id' => $personal_group_info['community_id']))->find();
                if ($add_status == 3) {
                    $status_info = '群主已通过';
                } else {
                    $status_info = '群主已拒绝（' . $_POST['refusing_remark'] . '）';
                }
                $data = array(
                    'touser' => $apply_user['group_openid'],
                    'page' => 'pages/group/shareGroupMeno/shareGroupMeno?community_id=' . $personal_group_info['community_id'],
                    'form_id' => $from_info['formid'],
                    'keyword1' => $comm_nickname, // 姓名
                    'keyword2' => '申请加入群【' . $community_info['community_name'] . '】', // 申请项目
                    'keyword3' => $status_info, // 状态
                    'keyword4' => date('Y-m-d H:i:s'), // 日期
                );
                $info = $model->sendWxappTempMsg('AT0052', $data);
                if ($info) {
                    $database_community_user_formid->del($from_info['formid']);
                }
            }
        }
        $this->returnCode(0, array('add_id' => $add_id));
    }


    // 群主删除待办事项信息 待验证的默认系统拒绝  已验证的不出现在列表上
    public function group_del_backlog()
    {
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['add_id'])) {
            $this->returnCode('70000012');
        }
        $add_id = $_POST['add_id'];
        $database_Community_join = D('Community_join');
        $join_info = $database_Community_join->get_community_join_single($add_id);
        if (empty($join_info)) {
            $this->returnCode('70000022');
        }
        if ($join_info['standby_status'] == 2) {
            $this->returnCode('70000023');
        }
        // 如果是待审核状态，直接拒绝
        if ($join_info['add_status'] == 2) {
            $data = array(
                'standby_status' => 2,
                'add_status' => 4,
                'refusing_remark' => '群主已经拒绝',
            );
            D()->startTrans();
            // 先修改状态，再退款
            $setStatus = $database_Community_join->where(array('add_id' => $add_id))->data($data)->save();
            if ($setStatus) {
                // 状态修改完成 完成退款
                $add_user = D('User')->field(true)->where(array('uid' => $join_info['add_uid']))->find();
                $add_name = '';
                if ($join_info['comm_nickname']) $add_name = $join_info['comm_nickname'];
                if ($add_user['nickname']) $add_name = $add_user['nickname'];
                if ($add_user['phone'] && !$add_name) $add_name = substr_replace($add_user['phone'], '****', 3, 4);
                if ($join_info['charge_money']) {
                    $add_result = D('User')->add_money($join_info['add_uid'], $join_info['charge_money'], '拒绝入群,退款 ' . $add_name . ' 增加余额');
                    if (!$add_result['error_code'] == 0) {
                        $database_Community_join->where(array('add_id' => $add_id))->data(array('add_status' => 5))->save();
                        D()->commit();
                        $this->returnCode(0, array('add_id' => $add_id));
                    } else {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败！请重试~');
                    }
                } else {
                    D()->commit();
                    $this->returnCode(0, array('add_id' => $add_id));
                }

            } else {
                D()->rollback();
                $this->returnCode(1, array(), '操作失败！请重试~');
            }

        } else {
            $data = array(
                'standby_status' => 2
            );
            $setStatus = $database_Community_join->where(array('add_id' => $add_id))->data($data)->save();
            if ($setStatus) {
                $this->returnCode(0, array('add_id' => $add_id));
            } else {
                $this->returnCode(1, array(), '操作失败！请重试~');
            }
        }


    }


    // 我创建的群
    public function group_own()
    {
        // 判断登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $database_Community_join = D('Community_join');
        $where = array();
        // 解散的群看不到
        $where['status'] = array('neq', 2);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        // 匹配创建者uid

        $where = '`ci`.status <> 2 AND `ci`.group_owner_uid =' . $this->_uid . ' AND `cj`.add_status < 4 AND `cj`.add_status > 1 AND `cj`.add_uid =' . $this->_uid;

        $personal_group_own_list = $database_Community_join->get_community_join($where, 10, $page);
        $data = array();
        if (!empty($personal_group_own_list['list'])) {
            $data = $personal_group_own_list;
            $time = time();
            $site_url = C('config.site_url');
            foreach ($data['list'] as &$val) {
                // 判断该群是否绑定微信群id
                if ($val['openGId']) {
                    $val['is_share'] = true;
                } else {
                    $val['is_share'] = false;
                }
                // 判断该群是否被禁止
                if ($val['status'] == 3) {
                    $val['is_ban'] = true;
                } else {
                    $val['is_ban'] = false;
                }
                //群头像
                if ($val['avatar']) {
                    $val['avatar'] = $site_url . $val['avatar'] . '?time=' . $time;
                } else {
                    $val['avatar'] = $site_url . '/static/wxapp/person.png';
                }
                // 群主上传的群头像
                if ($val['community_avatar']) {
                    $val['community_avatar'] = $site_url . '/upload/comm/' . $val['community_avatar'];
                }
                unset($val['openGId'], $val['income_money']);
                $val['add_time_display'] = $this->time_info($val['add_time'], '创建');
                $val['new_msg_time_display'] = $val['new_msg_time'] ? $this->time_info($val['new_msg_time'], '') : '';
            }
        } else {
            $data['list'] = array();
        }
        $this->returnCode(0, $data);
    }


    // 我参加的群
    public function group_join()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $database_Community_join = D('Community_join');

        $where = '`ci`.status <> 2 AND `cj`.add_uid = ' . $this->_uid . ' AND `cj`.add_status < 4  AND `cj`.add_status > 1';

        $personal_group_join_list = $database_Community_join->get_community_join($where, 10, $page);
        $data = array();
        if (!empty($personal_group_join_list['list'])) {
            $data = $personal_group_join_list;
            $time = time();
            $site_url = C('config.site_url');
            foreach ($data['list'] as &$val) {
                // 判断该群是否被禁止
                if ($val['status'] == 3) {
                    $val['is_ban'] = true;
                } else {
                    $val['is_ban'] = false;
                }
                // 判断该群是否绑定微信群id
                if ($val['openGId']) {
                    $val['is_share'] = true;
                } else {
                    $val['is_share'] = false;
                }
                // 判断是否待审核
                if ($val['add_status'] == 2) {
                    $val['is_checking'] = true;
                } else {
                    $val['is_checking'] = false;
                }
                //群头像
                if ($val['avatar']) {
                    $val['avatar'] = $site_url . $val['avatar'] . '?time=' . $time;
                } else {
                    $val['avatar'] = $site_url . '/static/wxapp/person.png';
                }
                // 群主上传的群头像
                if ($val['community_avatar']) {
                    $val['community_avatar'] = $site_url . '/upload/comm/' . $val['community_avatar'];
                }
                unset($val['openGId'], $val['income_money']);
                $val['add_time_display'] = $this->time_info($val['add_time'], '创建');
                $val['new_msg_time_display'] = $val['new_msg_time'] ? $this->time_info($val['new_msg_time'], '') : '';
            }
        } else {
            $data['list'] = array();
        }
        $this->returnCode(0, $data);
    }


    // 获取绑定群商城id
    public function group_get_bind_shop()
    {
        // 判断登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $condition_info['group_owner_uid'] = $uid;
        $database_community_bind_shop = D('community_bind_shop');
        $info = $database_community_bind_shop->field(true)->where($condition_info)->find();
        if (empty($info)) {
            $this->returnCode(0, array('store_id' => ''));
        } else {
            $this->returnCode(0, $info);
        }

    }


    // 绑定群商城
    public function group_bind_shop()
    {
        // 判断登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        // 过滤商城id
        if (empty($_POST['store_id'])) {
            $this->returnCode('70000012');
        }
        // 过滤群id
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000012');
        }
        $store_id = $_POST['store_id'];
        $community_id = $_POST['community_id'];
        $where = array('store_id' => $store_id);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();
        //资质认证
        if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
            $this->returnCode(1, array(), '您查看的' . $this->config['shop_alias_name'] . '没有通过资质审核！');
        }
        $now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
        if (empty($now_shop) || empty($now_store)) {
            $this->returnCode(1, array(), '暂无此店铺信息');
        }


        $condition_info['group_owner_uid'] = $uid;
        $condition_info['community_id'] = $community_id;
        $database_community_bind_shop = D('community_bind_shop');
        $info = $database_community_bind_shop->field(true)->where($condition_info)->find();
        if (empty($info)) {
            $data = array(
                'group_owner_uid' => $uid,
                'community_id' => $community_id,
                'store_id' => $store_id
            );
            $addInfo = $database_community_bind_shop->data($data)->add();
            if ($addInfo) {
                $this->returnCode(0, '绑定成功！');
            } else {
                $this->returnCode(1, array(), '绑定失败！');
            }
        } else {
            $condition_info['store_id'] = $store_id;
            $condition_info['community_id'] = $community_id;
            $store_info = $database_community_bind_shop->field(true)->where($condition_info)->find();
            if ($store_info) {
                $this->returnCode(0, '商城店铺id已绑定！');
            }
            $info = $database_community_bind_shop->where(array('group_owner_uid' => $uid))->data(array('store_id' => $_POST['store_id']))->save();
            if ($info) {
                $this->returnCode(0, '绑定成功！');
            } else {
                $this->returnCode(1, array(), '绑定失败！请重试~');
            }
        }
    }


    // 获取个人余额列表
    public function group_user_money_list()
    {
        // 判断登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $page = !empty($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = 10;
        $database_user_money_list = D('User_money_list');
        $money_info = $database_user_money_list->get_list($uid, $page, $pageSize);
        if (empty($money_info['money_list'])) {
            $money_info['money_list'] = array();
        }
        if (!empty($money_info['money_list'])) {
            foreach ($money_info['money_list'] as &$val) {
                $val['time_info'] = date("Y-m-d H:i:s", $val['time']);
            }
        }
        $this->returnCode(0, $money_info);
    }

    // 处理去除空值 0也会被过滤
    private function array_key_val($data)
    {
        $ret = array();
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $val) {
                if ($val) $ret[$key] = $val;
            }
        }
        return $ret;
    }


    // 时间显示处理
    private function time_info($timestamp, $str = '申请')
    {
        if (!$timestamp) {
            $timestamp = time();
        }
        // 取今天的时间范围
        $now_start_time = strtotime(date('Y-m-d', time()) . " 00:00:00");
        $now_end_time = strtotime(date('Y-m-d', time()) . " 23:59:59");
        if (intval($timestamp) >= intval($now_start_time) && intval($timestamp) <= intval($now_end_time)) {
            $time_info = date('H:i', $timestamp) . $str;
            return $time_info;
        }
        // 取昨天的时间范围
        $yesterday_start_time = strtotime("-1 day", $now_start_time);
        $yesterday_end_time = strtotime("-1 day", $now_end_time);
        if (intval($timestamp) >= intval($yesterday_start_time) && intval($timestamp) <= intval($yesterday_end_time)) {
            $time_info = '昨天' . date('H:i', $timestamp) . $str;
            return $time_info;
        }
        // 取前天的时间范围
        $week_start_time = strtotime("-2 day", $now_start_time);
        $week_end_time = strtotime("-2 day", $now_end_time);
        if (intval($timestamp) >= intval($week_start_time) && intval($timestamp) <= intval($week_end_time)) {
            $weekarray = array("日", "一", "二", "三", "四", "五", "六");
            $w = date("w", $timestamp);
            $time_info = '周' . $weekarray[$w] . date('H:i', $timestamp) . $str;
            return $time_info;
        }
        // 获取年份
        $now_year = date('Y');
        $year = date('Y', $timestamp);
        if (intval($now_year) == intval($year)) {
            $time_info = date('m月d日', $timestamp) . $str;
        } else {
            $time_info = date('Y年m月d日', $timestamp) . $str;
        }
        return $time_info;
    }


    // 禁言时间显示处理 分钟
    private function excuse_time_info($timestamp, $is_hour = 1, $is_minutes = 1)
    {
        if (!$timestamp) return false;
        $day = floor($timestamp / (3600 * 24));
        $day = $day > 0 ? $day . '天' : '';
        $hour = floor(($timestamp % (3600 * 24)) / 3600);
        $hour = $hour > 0 ? $hour . '小时' : '';
        if ($is_hour && $is_minutes) {
            $minutes = floor((($timestamp % (3600 * 24)) % 3600) / 60);
            $minutes = $minutes > 0 ? $minutes . '分钟' : '';
            return $day . $hour . $minutes;
        }
    }

    //保存 formid
    public function post_formid()
    {
        $data['uid'] = $this->_uid;
        $data['formid'] = $_POST['formid'];
        $data['add_time'] = time();

        $add_result = M('Community_user_formid')->data($data)->add();
        if (!$add_result['error']) {
            $this->returnCode(0, '添加成功');
        } else {
            $this->returnCode(1001, '添加失败！' . $add_result['err_msg']);
        }
    }


    // 获取消息和是否显示角标
    public function group_message()
    {
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        // 上传复数的加入者id
        if (empty($_POST['add_id_info'])) {
            $this->returnCode(1, array(), '上传参数缺失！请重试~');
        }
        $add_id_str = implode(',', $_POST['add_id_info']);
        $field = array('`cj`.`add_id`', '`cj`.`community_id`', '`cj`.`is_badge`', '`ci`.`new_msg_time`', '`ci`.`new_msg`', '`ci`.`add_time`');
        $table = array(C('DB_PREFIX') . 'community_join' => 'cj', C('DB_PREFIX') . 'community_info' => 'ci');
        $where = '`cj`.`community_id` = `ci`.`community_id` AND `cj`.`add_id` in (' . $add_id_str . ')';
        $list = D('')->field($field)->table($table)->where($where)->order('`cj`.`set_top` DESC, `ci`.`new_msg_time` DESC,`cj`.`add_id` DESC')->select();
        if ($list) {
            foreach ($list as &$val) {
                $val['add_time_display'] = $this->time_info($val['add_time'], '创建');
                $val['new_msg_time_display'] = $val['new_msg_time'] ? $this->time_info($val['new_msg_time'], '') : '';
            }
        }
        $data = array(
            'list' => $list
        );
        $this->returnCode(0, $data);
    }


    // 改变角标状态
    public function group_change_badge()
    {
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        // 上传加入者id
        if (empty($_POST['add_id'])) {
            $this->returnCode(1, array(), '上传参数缺失！请重试~');
        }
        $change = M('community_join')->where(array('add_id' => $_POST['add_id']))->data(array('is_badge' => 2))->save();
        $this->returnCode(0, array('add_id' => $change));
    }

    //个人中心代办事项提示显示
    public function group_tips()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $database_Community_join = D('Community_join');

        $where = '`ci`.group_owner_uid = ' . $this->_uid . ' AND `ci`.status = 1 AND `cj`.add_uid <> ' . $this->_uid . ' AND `cj`.standby_status = 1 AND `cj`.add_status = 2';

        $result = $database_Community_join->get_community_count_tips($where);
        $this->returnCode(0,$result);
    }
}