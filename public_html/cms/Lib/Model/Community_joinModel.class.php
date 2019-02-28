<?php
class Community_joinModel extends Model
{
    /*得到加群信息*/
    public function get_community_join_single($uid,$field='add_id')
    {
        $condition_info[$field] = $uid;
        $community_join_single = $this->field(true)->where($condition_info)->find();
        return $community_join_single;
    }

    /*得到加群及社群信息*/
    public function get_community_join($where, $pageSize = 6, $page = 1){
        if(!$where){
            return false;
        }

        $field = array('`cj`.*', '`ci`.*','`cj`.`add_time` as add_group_time','`cj`.`charge_money` as add_charge_money');
        $table = array(C('DB_PREFIX').'community_join'=>'cj',C('DB_PREFIX').'community_info'=>'ci');
        if (empty($where)) {
            $where = '`cj`.`community_id` = `ci`.`community_id`';
        } else {
            $where .= ' AND `cj`.`community_id` = `ci`.`community_id`';
        }

        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total = D('')->table($table)->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->where($where)->order('`cj`.`set_top` DESC, `ci`.`new_msg_time` DESC,`cj`.`add_id` DESC')->limit($firstRow.','.$pageSize)->select();
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order('`cj`.`set_top` DESC, `ci`.`new_msg_time` DESC,`cj`.`add_id` DESC')->select();
            return [
                'list' => $list
            ];
        }
    }


    /*得到加群及社群信息 只按照时间排序*/
    public function get_community_info_join($where, $pageSize = 6, $page = 1){
        if(!$where){
            return false;
        }

        $field = array('`cj`.*', '`ci`.*','`cj`.`add_time` as add_group_time','`cj`.`charge_money` as add_charge_money');
        $table = array(C('DB_PREFIX').'community_join'=>'cj',C('DB_PREFIX').'community_info'=>'ci');
        if (empty($where)) {
            $where = '`cj`.`community_id` = `ci`.`community_id`';
        } else {
            $where .= ' AND `cj`.`community_id` = `ci`.`community_id`';
        }

        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total = D('')->table($table)->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->where($where)->order('`cj`.`add_id` DESC')->limit($firstRow.','.$pageSize)->select();
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order('`add_id` DESC')->select();
            return [
                'list' => $list
            ];
        }
    }


    public function get_community_count_tips($where){
        if(!$where){
            return false;
        }
        $table = array(C('DB_PREFIX').'community_join'=>'cj',C('DB_PREFIX').'community_info'=>'ci');
        if (empty($where)) {
            $where = '`cj`.`community_id` = `ci`.`community_id`';
        } else {
            $where .= ' AND `cj`.`community_id` = `ci`.`community_id`';
        }

        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        $total['count'] = D('')->table($table)->where($where)->count();
        $total['time'] = time();
        return $total;
    }



    public function send_msg($type='join_activity', $msg) {
        // 报名通知 活动举办方
        if ($type == 'join_activity') {
            // 如果需要模板推送 就推送消息
            $tip = M('User')->field('group_openid')->where(array('uid' => $msg['uid']))->find();
            // 投票创建 进行模板推送
            if (!empty($tip)) {
                $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));

                $database_community_user_formid = D('Community_user_formid');
                // 然后对应进行模板推送
                $from_info = $database_community_user_formid->get($msg['uid']);
                if ($from_info && $from_info['formid'] && $tip['group_openid']) {
                    $data = array(
                        'touser' => $tip['group_openid'],
                        'page' => 'pages/group/groupList/groupList?redirect=activityImDetail&community_id=' . $msg['community_id'] . '&activity_id='.$msg['id'],
                        'form_id' => $from_info['formid'],
                        'keyword1' => '【' . $msg['community_name'] . '】群活动',  // 报名项目
                        'keyword2' => '【' . $msg['title'] . '】',  // 活动主题
                        'keyword3' => $msg['name'],   // 报名姓名
                        'keyword4' => date('Y-m-d H:i:s'),  // 报名时间
                    );
                    $info = $model->sendWxappTempMsg('AT0027', $data);
                    if ($info) {
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        } elseif ($type == 'single_refund') {
            $table = array(C('DB_PREFIX').'user'=>'u', C('DB_PREFIX').'community_activity_join'=>'caj');
            $field_info = array('`u`.`nickname`, `u`.`group_openid`, `caj`.`uid`,`caj`.`name`,`caj`.`name`,`caj`.`money`');
            $where = '`caj`.`order_id` ='. $msg['order_id'] .' AND `caj`.`uid`=`u`.`uid`';
            $tip = D('')->field($field_info)->table($table)->where($where)->find();
            // 活动完成 推送消息
            if (!empty($tip)) {
                $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));

                $database_community_user_formid = D('Community_user_formid');
                // 然后对应进行模板推送
                $from_info = $database_community_user_formid->get($tip['uid']);
                if ($from_info && $from_info['formid'] && $tip['group_openid']) {
                    $data = array(
                        'touser' => $tip['group_openid'],
                        'page' => 'pages/group/groupList/groupList?redirect=activityImDetail&community_id=' . $msg['community_id'] . '&activity_id='.$msg['id'],
                        'form_id' => $from_info['formid'],
                        'keyword1' => $msg['type'],  // 退款类型
                        'keyword2' => $msg['reason'],  // 退款原因
                        'keyword3' => $msg['money'],  // 退款金额
                        'keyword4' => date('Y-m-d H:i:s'),  // 退款时间
                    );
                    $info = $model->sendWxappTempMsg('AT0036', $data);
                    if ($info) {
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        }
    }


    /**
     * 推送任务添加
     * @param  array $param  参数数组
     * @return array
     */
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


    /**
     * 生成群推送计划
     * @param integer $community_id
     * @param string $type   群解散 comm_dissolution_group ; 群主踢人 comm_owner_kicking ; 创建群投票 comm_add_vote;
     *                       群公告 comm_add_notice; 群活动 comm_add_activity; 取消活动 comm_cancel_activity;
     *                       完成活动 comm_finish_activity; @人 消息提醒推送 comm_reminding_others;
     * @param array $uid_arr
     * @param integer $comm_id 群应用操作id
     * @param integer $other_id 操作者id 或者额外的id 比如是join_id
     * @param string $message 消息
     */
    public function create_comm_plan($community_id, $type = 'comm_dissolution_group', $uid_arr = array(), $comm_id = 0, $other_id = 0, $message = '') {
        $param = array();
        $param['type'] = $type;
        if ($uid_arr) { // 需要通知到的用户
            $param['uid_str'] = implode(',', $uid_arr);
        }
        if ($community_id) { // 需要通知用户的所在群的群id
            $param['community_id'] = $community_id;
        }
        if ($comm_id) { // 群应用操作id 比如投票id
            $param['comm_id'] = $comm_id;
        }
        if ($other_id) { // 操作者id
            $param['other_id'] = $other_id;
        }
        if ($message) { // 消息
            $param['message'] = $message;
        }
        if ($param) {
            $this->add_plan($param);
        }
    }



    /**
     * 统一整合推送功能
     */
    /**
     * 群解散消息推送
     * @param integer $community_id
     * @param array $uid_arr
     */
    public function comm_dissolution_group($community_id, $uid_arr = array()) {
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('community_name, group_owner_uid')->find();
        $group_owner_uid = 0;
        if ($community_info) {
            $group_owner_uid = $community_info['group_owner_uid'];
        }
        $database_community_user_formid = D('Community_user_formid');
        // 解散成功 推动模板消息 给群群员
        $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));
        $msg = array(
            'page' => 'pages/group/groupList/groupList',
            'keyword2' => '群主解散群', // 退出方式
            'keyword3' => '群主解散群【' . $community_info['community_name'] . '】', // 备注
            'keyword4' => date('Y-m-d H:i:s'), // 退出时间
        );

        if (empty($uid_arr[0])) {
            $table = array(C('DB_PREFIX') . 'user' => 'u', C('DB_PREFIX') . 'Community_join' => 'cj');
            $field_info = array('`u`.`nickname`, `u`.`group_openid`, `cj`.`comm_nickname` ,`cj`.`add_uid`');
            $where = '`cj`.`community_id` =' . $community_id . ' AND `cj`.`add_uid`=`u`.`uid`';
            if ($group_owner_uid > 0) {
                $where .= ' AND `cj`.`add_uid`<>' . $group_owner_uid;
            }
            $group_members_info = D('')->field($field_info)->table($table)->where($where)->select();
            if (!empty($group_members_info)) {
                foreach ($group_members_info as $val) {
                    // 然后对应进行模板推送
                    // 推送为被移出群 的消息
                    $from_info = $database_community_user_formid->get($val['add_uid']);
                    if ($from_info && $from_info['formid'] && $val['group_openid']) {
                        $comm_nickname = $val['comm_nickname'] ? $val['comm_nickname'] : $val['nickname'];
                        $msg['touser'] = $val['group_openid'];
                        $msg['form_id'] = $from_info['formid'];
                        $msg['keyword1'] = $comm_nickname; // 用户昵称
                        $info = $model->sendWxappTempInfo('AT0751', $msg);
                        if ($info['content']) {
                            $model->sendWeixinTempMsg($info['content']);
                            $database_community_user_formid->del($from_info['formid']);
                        }
                    }
                }
            }
        } else {
            $m_user = M('User');
            foreach ($uid_arr as $val) {
                if ($val == $group_owner_uid) continue;
                // 推送为被移出群 的消息
                $apply_user = $m_user->field('nickname, group_openid')->where(array('uid' => $val))->find();
                $from_info = $database_community_user_formid->get($val);
                $add_info = $this->field('comm_nickname')->where(array('add_uid' => $val))->find();
                if ($from_info && $from_info['formid'] && $val['group_openid']) {
                    $comm_nickname = $add_info['comm_nickname'] ? $add_info['comm_nickname'] : $apply_user['nickname'];
                    $msg['touser'] = $apply_user['group_openid'];
                    $msg['form_id'] = $from_info['formid'];
                    $msg['keyword1'] = $comm_nickname; // 用户昵称
                    $info = $model->sendWxappTempInfo('AT0751', $msg);
                    if ($info['content']) {
                        $model->sendWeixinTempMsg($info['content']);
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        }
    }


    /**
     * 批量移出群
     * @param integer $community_id
     * @param array $uid_arr
     */
    public function comm_owner_kicking($community_id, $uid_arr) {
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('community_name, group_owner_uid')->find();

        $m_user = M('User');
        $d_community_user_formid = D('Community_user_formid');
        $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));
        $database_community_user_formid = D('Community_user_formid');
        $msg = array(
            'page' => 'pages/group/groupList/groupList',
            'keyword2' => '群主移出', // 退出方式
            'keyword3' => '群主将您移出【' . $community_info['community_name'] . '】', // 备注
            'keyword4' => date('Y-m-d H:i:s'), // 退出时间
        );
        foreach ($uid_arr as $val) {
            $apply_user = $m_user->field('nickname, group_openid')->where(array('uid' => $val))->find();
            $from_info = $d_community_user_formid->get($val);
            $add_info = $this->field('comm_nickname')->where(array('add_uid' => $val))->find();

            if ($from_info && $from_info['formid'] && $apply_user['group_openid']) {
                // 用户被群主移出 推送消息给用户
                $comm_nickname = $add_info['comm_nickname'] ? $add_info['comm_nickname'] : $apply_user['nickname'];
                $msg['touser'] = $apply_user['group_openid'];
                $msg['form_id'] = $from_info['formid'];
                $msg['keyword1'] = $comm_nickname; // 用户昵称
                $info = $model->sendWxappTempInfo('AT0751', $msg);
                if ($info) {
                    $model->sendWeixinTempMsg($info['content']);
                    $database_community_user_formid->del($val['formid']);
                }
            }
        }
    }


    /**
     * 创建群投票消息推送
     * @param integer $community_id
     * @param integer $vote_id
     */
    public function comm_add_vote($community_id, $vote_id) {
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('community_name, group_owner_uid')->find();
        $group_owner_uid = 0;
        if ($community_info) {
            $group_owner_uid = $community_info['group_owner_uid'];
        }
        // 查询一下投票信息
        $m_user = M('User');
        $vote_info = M('Community_vote')->where(array('vote_id' => $vote_id))->field('uid, title')->find();
        if ($vote_info['uid']) {
            $vote_uid = $vote_info['uid'];
            $vote_user = $m_user->field('nickname')->where(array('uid' => $vote_uid))->find();
            $vote_join_info = $this->field('comm_nickname')->where(array('add_uid' => $vote_uid))->find();
            $vote_name = $vote_join_info['comm_nickname'] ? $vote_join_info['comm_nickname'] : $vote_user['nickname'];
        } else {
            $vote_name = '';
            $vote_uid = $group_owner_uid;
        }
        $table = array(C('DB_PREFIX') . 'user' => 'u', C('DB_PREFIX') . 'Community_join' => 'cj');
        $field_info = array('`u`.`nickname`, `u`.`group_openid`, `cj`.`comm_nickname` ,`cj`.`add_uid`');
        $where = '`cj`.`community_id` =' . $community_id . ' AND `cj`.`add_status`=3 AND `cj`.`add_uid`=`u`.`uid`';
        if ($vote_uid > 0) {
            $where .= ' AND `cj`.`add_uid`<>' . $vote_uid;
        }
        $group_members_info = D('')->field($field_info)->table($table)->where($where)->select();
        if (!empty($group_members_info)) {
            $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));

            $database_community_user_formid = D('Community_user_formid');
            $msg = array(
                'page' => 'pages/group/groupList/groupList?redirect=voteImDetail&community_id=' . $community_id . '&vote_id='.$vote_id,
                'keyword1' => '【' . $community_info['community_name'] . '】群投票',  // 投票标题
                'keyword2' => date('Y-m-d H:i:s'),  // 创建时间
                'keyword3' => $vote_name, // 发起人
            );
            if ($vote_info['title']) {
                $msg['keyword4'] = '发布了投票【' . $vote_info['title'] . '】'; // 投票内容
            } else {
                $msg['keyword4'] = '发布了投票'; // 投票内容
            }
            foreach ($group_members_info as $val) {
                // 然后对应进行模板推送
                // 推送为被移出群 的消息
                $from_info = $database_community_user_formid->get($val['add_uid']);
                if ($from_info && $from_info['formid'] && $val['group_openid']) {
                    $msg['touser'] = $val['group_openid'];
                    $msg['form_id'] = $from_info['formid'];
                    $info = $model->sendWxappTempInfo('AT0130', $msg);
                    if ($info['content']) {
                        $model->sendWeixinTempMsg($info['content']);
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        }
    }

    /**
     * 创建群公告消息推送
     * @param integer $community_id
     * @param integer $notice_id
     */
    public function comm_add_notice($community_id, $notice_id) {
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('community_name, group_owner_uid')->find();
        $group_owner_uid = 0;
        if ($community_info) {
            $group_owner_uid = $community_info['group_owner_uid'];
        }
        // 查询一下公告信息
        $m_user = M('User');
        $notice_info = M('Community_notice')->where(array('notice_id' => $notice_id))->field('uid, title')->find();
        if ($notice_info['uid']) {
            $notice_uid = $notice_info['uid'];
            $notice_user = $m_user->field('nickname')->where(array('uid' => $notice_uid))->find();
            $notice_join_info = $this->field('comm_nickname')->where(array('add_uid' => $notice_uid))->find();
            $notice_name = $notice_join_info['comm_nickname'] ? $notice_join_info['comm_nickname'] : $notice_user['nickname'];
        } else {
            $notice_name = '';
            $notice_uid = $group_owner_uid;
        }
        $table = array(C('DB_PREFIX') . 'user' => 'u', C('DB_PREFIX') . 'Community_join' => 'cj');
        $field_info = array('`u`.`nickname`, `u`.`group_openid`, `cj`.`comm_nickname` ,`cj`.`add_uid`');
        $where = '`cj`.`community_id` =' . $community_id . ' AND `cj`.`add_status`=3 AND `cj`.`add_uid`=`u`.`uid`';
        if ($notice_uid > 0) {
            $where .= ' AND `cj`.`add_uid`<>' . $notice_uid;
        }
        $group_members_info = D('')->field($field_info)->table($table)->where($where)->select();
        if (!empty($group_members_info)) {
            $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));

            $database_community_user_formid = D('Community_user_formid');
            if ($notice_info['title']) {
                $title_info = '发布了公告【' . $notice_info['title'] . '】'; // 公告
            } else {
                $title_info = '发布了公告'; // 公告内容
            }
            $msg = array(
                'page' => 'pages/group/groupList/groupList?redirect=noticeImDetail&community_id=' . $community_id . '&notice_id=' . $notice_id,
                'keyword1' => $title_info,  // 主题
                'keyword2' => '群公告', // 内容类型
                'keyword3' => $notice_name,  // 创建人
                'keyword4' => date('Y-m-d H:i:s'),  // 创建时间
            );
            foreach ($group_members_info as $val) {
                // 然后对应进行模板推送
                $from_info = $database_community_user_formid->get($val['add_uid']);
                if ($from_info && $from_info['formid'] && $val['group_openid']) {
                    $msg['touser'] = $val['group_openid'];
                    $msg['form_id'] = $from_info['formid'];
                    $info = $model->sendWxappTempInfo('AT1175', $msg);
                    if ($info['content']) {
                        $model->sendWeixinTempMsg($info['content']);
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        }
    }

    /**
     * 创建群活动消息推送
     * @param integer $community_id
     * @param integer $activity_id
     */
    public function comm_add_activity($community_id, $activity_id) {
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('community_name, group_owner_uid')->find();
        $group_owner_uid = 0;
        if ($community_info) {
            $group_owner_uid = $community_info['group_owner_uid'];
        }
        // 查询一下活动信息
        $m_user = M('User');
        $activity_info = M('Community_activity')->where(array('activity_id' => $activity_id))->field('start_time, end_time, uid, title, limit, fees')->find();
        if ($activity_info['uid']) {
            $activity_uid = $activity_info['uid'];
            $activity_user = $m_user->field('nickname')->where(array('uid' => $activity_uid))->find();
            $activity_join_info = $this->field('comm_nickname')->where(array('add_uid' => $activity_uid))->find();
            $activity_name = $activity_join_info['comm_nickname'] ? $activity_join_info['comm_nickname'] : $activity_user['nickname'];
        } else {
            $activity_name = '';
            $activity_uid = $group_owner_uid;
        }
        $table = array(C('DB_PREFIX') . 'user' => 'u', C('DB_PREFIX') . 'Community_join' => 'cj');
        $field_info = array('`u`.`nickname`, `u`.`group_openid`, `cj`.`comm_nickname` ,`cj`.`add_uid`');
        $where = '`cj`.`community_id` =' . $community_id . ' AND `cj`.`add_status`=3 AND `cj`.`add_uid`=`u`.`uid`';
        if ($activity_uid > 0) {
            $where .= ' AND `cj`.`add_uid`<>' . $activity_uid;
        }
        $group_members_info = D('')->field($field_info)->table($table)->where($where)->select();
        if (!empty($group_members_info)) {
            $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));

            $database_community_user_formid = D('Community_user_formid');
            $date_info = date('Y-m-d H:i:s', $activity_info['start_time']) . '至' . date('Y-m-d H:i:s', $activity_info['end_time']);
            $title_info = '【' . $activity_info['title'] . '】';
            $limit_num = $activity_info['limit'] > 0 ? '限 ' . $activity_info['limit'] . '人' : '暂无限制';
            $join_fees = $activity_info['fees'] > 0 ? $activity_info['fees'] . '元/人' : '免费';
            $msg = array(
                'page' => 'pages/group/groupList/groupList?redirect=activityImDetail&community_id=' . $community_id . '&activity_id='.$activity_id,
                'keyword1' => $title_info,  // 活动名称
                'keyword2' => $date_info,  // 活动时间
                'keyword3' => $activity_name, // 发布人
                'keyword4' => date('Y-m-d H:i:s'), // 发布时间
                'keyword5' => $limit_num,  // 活动人数限制
                'keyword6' => $join_fees, // 报名费用
            );
            foreach ($group_members_info as $val) {
                // 然后对应进行模板推送
                $from_info = $database_community_user_formid->get($val['add_uid']);
                if ($from_info && $from_info['formid'] && $val['group_openid']) {
                    $msg['touser'] = $val['group_openid'];
                    $msg['form_id'] = $from_info['formid'];
                    $info = $model->sendWxappTempInfo('AT0322', $msg);
                    if ($info['content']) {
                        $model->sendWeixinTempMsg($info['content']);
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        }
    }

    /**
     * 取消群活动消息推送
     * @param integer $community_id
     * @param integer $activity_id
     * @param integer $uid
     */
    public function comm_cancel_activity($community_id, $activity_id, $uid) {
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('community_name, group_owner_uid')->find();
        $group_owner_uid = 0;
        if ($community_info) {
            $group_owner_uid = $community_info['group_owner_uid'];
        }
        $database_community_order= D('Community_order');
        $type = '活动发布者取消活动';
        if ($group_owner_uid == $uid) {
            $type = '群主取消活动';
        }
        // 查询一下活动信息
        $activity_info = M('Community_activity')->where(array('activity_id' => $activity_id))->field('start_time, end_time, uid, title, limit, fees')->find();
        if ($activity_info['uid']) {
            $activity_uid = $activity_info['uid'];
        } else {
            $activity_uid = $group_owner_uid;
        }

        $order_list = $database_community_order->where(array('activity_id'=>$activity_id,'is_del'=>0))->select();

        if ($order_list) {
            $table = array(C('DB_PREFIX').'user'=>'u', C('DB_PREFIX').'community_activity_join'=>'caj');
            $field_info = array('`u`.`nickname`, `u`.`group_openid`, `caj`.`uid`,`caj`.`name`,`caj`.`name`,`caj`.`money`');
            foreach ($order_list as $key => $value) {
                if ($value['status'] == 1) {
                    $where = '`caj`.`order_id` ='. $value['order_id'] .' AND `caj`.`uid`=`u`.`uid`';
                    $tip = D('')->field($field_info)->table($table)->where($where)->find();
                    // 活动完成 推送消息
                    if (!empty($tip)) {
                        $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));

                        $database_community_user_formid = D('Community_user_formid');
                        // 然后对应进行模板推送
                        $from_info = $database_community_user_formid->get($tip['uid']);
                        if ($from_info && $from_info['formid'] && $tip['group_openid']) {
                            $data = array(
                                'touser' => $tip['group_openid'],
                                'page' => 'pages/group/groupList/groupList?redirect=activityImDetail&community_id=' . $community_id . '&activity_id='.$activity_id,
                                'form_id' => $from_info['formid'],
                                'keyword1' => $type,  // 退款类型
                                'keyword2' => '活动取消',  // 退款原因
                                'keyword3' => $value['money'],  // 退款金额
                                'keyword4' => date('Y-m-d H:i:s'),  // 退款时间
                            );
                            $info = $model->sendWxappTempInfo('AT0036', $data);
                            if ($info['content']) {
                                $model->sendWeixinTempMsg($info['content']);
                                $database_community_user_formid->del($from_info['formid']);
                            }
                        }
                    }

                }
            }
        }
        // 群主取消活动，且不是群主发布的活动，通知活动发布者
        if ($group_owner_uid == $uid && $activity_uid != $uid) {
            $where = array(
                'activity_id' => $activity_id,
                'status' => array('in', array(1,2))
            );
            $join_list = M('Community_activity_join')->field('join_id')->where($where)->select();
            $join_num = count($join_list);
            $tip = M('User')->field('group_openid')->where(array('uid' => $activity_uid))->find();
            // 活动完成 推送消息
            if (!empty($tip)) {
                $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));

                $database_community_user_formid = D('Community_user_formid');
                // 然后对应进行模板推送
                $from_info = $database_community_user_formid->get($activity_uid);
                if ($from_info && $from_info['formid'] && $tip['group_openid']) {
                    $data = array(
                        'touser' => $tip['group_openid'],
                        'page' => 'pages/group/groupList/groupList?redirect=activityImDetail&community_id=' . $community_id . '&activity_id='.$activity_id,
                        'form_id' => $from_info['formid'],
                        'keyword1' => $activity_info['title'],  // 活动名称
                        'keyword2' => '群主取消活动',  // 取消原因
                        'keyword3' => date('Y-m-d H:i:s'),  // 取消时间
                        'keyword4' => $join_num,  // 报名人数
                        'keyword5' => '群主取消该活动',  // 备注
                    );
                    $info = $model->sendWxappTempInfo('AT0225', $data);
                    if ($info['content']) {
                        $model->sendWeixinTempMsg($info['content']);
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        }
    }

    /**
     * 完成群活动消息推送
     * @param integer $community_id
     * @param integer $activity_id
     * @param integer $join_id
     */
    public function comm_finish_activity($community_id, $activity_id, $join_id) {
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('community_name, group_owner_uid')->find();
        $group_owner_uid = 0;
        if ($community_info) {
            $group_owner_uid = $community_info['group_owner_uid'];
        }
        // 查询一下活动信息
        $activity_info = M('Community_activity')->where(array('activity_id' => $activity_id))->field('start_time, end_time, uid, title, limit, fees')->find();
        if ($activity_info['uid']) {
            $activity_uid = $activity_info['uid'];
        } else {
            $activity_uid = $group_owner_uid;
        }


        // 如果需要模板推送 就推送消息
        $table = array(C('DB_PREFIX').'user'=>'u', C('DB_PREFIX').'community_activity_join'=>'caj');
        $field_info = array('`u`.`nickname`, `u`.`group_openid`, `caj`.`uid`,`caj`.`name`,`caj`.`money`');
        if ($join_id) {
            $where = '`caj`.`join_id` =' . $join_id . ' AND `caj`.`uid`=`u`.`uid`';
        } else {
            $where = '`caj`.`activity_id` ='. $activity_id .' AND `caj`.`uid`=`u`.`uid`';
        }
        $tip = D('')->field($field_info)->table($table)->where($where)->select();
        $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));
        $database_community_user_formid = D('Community_user_formid');

        $page = 'pages/group/groupList/groupList?redirect=activityImDetail&community_id=' . $community_id . '&activity_id='.$activity_id;
        $activity_time_info = date('Y-m-d H:i:s', $activity_info['start_time']) . '至' . date('Y-m-d H:i:s', $activity_info['end_time']);
        $title_info = '【' . $activity_info['title'] . '】';

        if ($join_id) {
            // 活动完成 推送消息
            if (!empty($tip)) {
                $tip = reset($tip);
                // 查询报名信息
                $join_info = $this->where(array('join_id'=>$join_id))->find();
                $join_fee = $join_info['money'] > 0 ? $join_info['money']. '元/人' : '免费';
                // 然后对应进行模板推送
                $from_info = $database_community_user_formid->get($tip['uid']);
                if ($from_info && $from_info['formid'] && $tip['group_openid']) {
                    $data = array(
                        'touser' => $tip['group_openid'],
                        'page' => $page,
                        'form_id' => $from_info['formid'],
                        'keyword1' => $activity_time_info,  // 活动时间
                        'keyword2' => $title_info,  // 活动主题
                        'keyword3' => $tip['name'] ? $tip['name'] : $tip['nickname'],  // 参加人员
                        'keyword4' => $join_fee,
                    );
                    $info = $model->sendWxappTempInfo('AT1454', $data);
                    if ($info['content']) {
                        $tip = $model->sendWeixinTempMsg($info['content']);
                        fdump($data, 'comm_finish_activity', true);
                        fdump($tip, 'comm_finish_activity', true);
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        } else {
            // 投票创建 进行模板推送
            if (!empty($tip)) {
                $data = array(
                    'page' => 'pages/group/groupList/groupList?redirect=activityImDetail&community_id=' . $community_id . '&activity_id='.$activity_id,
                    'keyword1' => $activity_time_info,  // 活动时间
                    'keyword2' => $title_info,  // 活动主题
                );
                foreach ($tip as $val) {
                    // 然后对应进行模板推送
                    $from_info = $database_community_user_formid->get($val['uid']);
                    if ($from_info && $from_info['formid'] && $val['group_openid']) {
                        $data['touser'] = $val['group_openid'];
                        $data['form_id'] = $from_info['formid'];
                        $data['keyword3'] = $val['name'] ? $val['name'] : $val['nickname']; // 参加人员
                        $data['keyword4'] = $val['money'] > 0 ? $val['money']. '元/人' : '免费'; // 费用
                        $info = $model->sendWxappTempInfo('AT1454', $data);
                        if ($info['content']) {
                            $tip = $model->sendWeixinTempMsg($info['content']);
                            fdump($data, 'comm_finish_activity', true);
                            fdump($tip, 'comm_finish_activity', true);
                            $database_community_user_formid->del($from_info['formid']);
                        }
                    }
                }
            }
        }

    }

    /**
     * @人消息推送
     * @param integer $community_id
     * @param array $uid_arr 推送对象
     * @param integer $reminding_uid
     * @param string $message
     * @return bool
     */
    public function comm_reminding_others($community_id, $uid_arr = array(), $reminding_uid, $message) {
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('community_name, group_owner_uid')->find();
        $group_owner_uid = 0;
        if ($community_info) {
            $group_owner_uid = $community_info['group_owner_uid'];
        }
        // 二次拦截 非群主不做推送
        if ($reminding_uid && $reminding_uid != $group_owner_uid) {
            return true;
        }
        // 获取推送人信息
        $user = M('User')->where(array('uid' => $reminding_uid))->field('nickname')->find();


        // 如果需要模板推送 就推送消息
        $table = array(C('DB_PREFIX').'user'=>'u', C('DB_PREFIX').'community_join'=>'cj');
        $field_info = array('`u`.`nickname`, `u`.`group_openid`, `cj`.`add_uid`,`cj`.`comm_nickname`,`cj`.`add_status`');
        if ($uid_arr) {
            $uid_str = implode(',', $uid_arr);
            $where = '`cj`.`community_id` ='. $community_id .' AND `cj`.`add_uid` in (' . $uid_str . ') AND `cj`.`add_uid`=`u`.`uid`';
        } else {
            $where = '`cj`.`community_id` ='. $community_id .' AND `cj`.`add_uid`=`u`.`uid` AND `cj`.`add_uid` <> ' . $reminding_uid;
        }

        $tip = D('')->field($field_info)->table($table)->where($where)->select();

        $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));
        $database_community_user_formid = D('Community_user_formid');

        $page = 'pages/group/groupList/groupList?redirect=chatDetail&community_id=' . $community_id;


        if ($tip) {
            // @人推送消息
            // 推送消息者
            $user_info = '来自【' . $community_info['community_name'] . '】群';
            if ($user['nickname']) {
                $user_info .= '的' . $user['nickname'];
            }
            // 截取前50个字
            if (strlen($message) > 50) {
                $message = substr($message, 0, 50) . '...';
            }
            $detail = '【有人@我】：' . $message;
            foreach ($tip as $val) {
                // 然后对应进行模板推送
                if ($val['add_status'] && $val['add_status'] != 3) continue;
                $from_info = $database_community_user_formid->get($val['add_uid']);
                if ($from_info && $from_info['formid'] && $val['group_openid']) {
                    $data = array(
                        'touser' => $val['group_openid'],
                        'page' => $page,
                        'form_id' => $from_info['formid'],
                        'keyword1' => $user_info,  // 发送人
                        'keyword2' => $detail,  // 详情
                        'keyword3' => date('Y-m-d H:i:s'),  // 发送时间
                    );
                    $info = $model->sendWxappTempInfo('AT0878', $data);
                    if ($info['content']) {
                        $msg = $model->sendWeixinTempMsg($info['content']);
                        fdump($data, 'comm_reminding_others', true);
                        fdump($msg, 'comm_reminding_others', true);
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        }

    }


    /**
     * 群投票后自动推送结果给投票者
     * @param integer $vote_id
     * @return bool
     */
    public function comm_finish_vote($vote_id) {
        if (!$vote_id) return false;
        $where_vote = array('vote_id' => $vote_id);
        $vote_info = M('Community_vote')->where($where_vote)->field(true)->find();
        if (!$vote_info) return false;
        $vote_user = M('User')->where(array('uid' => $vote_info['uid']))->field('uid, nickname, group_openid')->find();
        $vote_user_join = M('Community_join')->where(array('add_uid' => $vote_info['uid']))->field('comm_nickname')->find();

        $vote_option = M('Community_vote_option')->where($where_vote)->field(true)->order('number desc, add_time desc')->find();


        $table = array(C('DB_PREFIX').'user'=>'u', C('DB_PREFIX').'community_vote_user'=>'cvu');
        $field_info = array('`u`.`nickname`, `u`.`group_openid`, `cvu`.*');
        $where = '`cvu`.`vote_id` ='. $vote_id .' AND `cvu`.`uid`=`u`.`uid` AND `cvu`.`uid` <> ' . $vote_info['uid'];
        $tip = D('')->field($field_info)->table($table)->where($where)->select();


        $where_count = '`cvu`.`vote_id` ='. $vote_id .' AND `cvu`.`uid`=`u`.`uid`';
        // 参与投票人数
        $join_count = D('')->table($table)->where($where_count)->count();

        $model = new templateWxapp(C('config.pay_wxapp_group_appid'), C('config.pay_wxapp_group_appsecret'));
        $database_community_user_formid = D('Community_user_formid');

        // 跳转的页面
        $page = 'pages/group/groupList/groupList?redirect=voteImDetail&community_id=' . $vote_info['community_id'] . '&vote_id='.$vote_id;

        // 投票标题
        $vote_title = '【' . $vote_info['title'] . '】';
        // 投票类型
        if ($vote_info['type'] == 1) {
            $vote_type = '单选';
        } elseif ($vote_info['type'] == 2) {
            $vote_type = '多选，最多2项';
        } elseif ($vote_info['type'] == 3) {
            $vote_type = '多选，无限制';
        } else {
            $vote_type = '其他';
        }
        // 投票发起人
        $vote_create_user = $vote_user_join['comm_nickname'] ? $vote_user_join['comm_nickname'] : $vote_user['nickname'];
        // 投票结果
        $vote_finish_info = '第一名是【选项：' . $vote_option['name'] . '】，共' . $vote_option['number'] . '票， 点击查看完整结果...';

        $data = array(
            'page' => $page,
            'keyword1' => $vote_title,  // 投票标题
            'keyword2' => $vote_type,  // 投票类型
            'keyword3' => $vote_create_user,  // 投票发起人
            'keyword4' => $vote_finish_info,  // 投票结果
            'keyword5' => $join_count,  // 参与人数
        );
        // 如果存在除创建者以外的参与者，推送结束消息
        if ($tip) {
            foreach ($tip as $val) {
                $from_info = $database_community_user_formid->get($val['uid']);
                if ($from_info && $from_info['formid'] && $val['group_openid']) {
                    $data['touser'] = $val['group_openid'];
                    $data['form_id'] = $from_info['formid'];
                    $info = $model->sendWxappTempInfo('AT0196', $data);
                    if ($info['content']) {
                        $msg = $model->sendWeixinTempMsg($info['content']);
                        fdump('$tip----------'.__LINE__, 'comm_finish_vote', true);
                        fdump($data, 'comm_finish_vote', true);
                        fdump($msg, 'comm_finish_vote', true);
                        $database_community_user_formid->del($from_info['formid']);
                    }
                }
            }
        }
        // 发送消息给创建者
        if ($vote_user) {
            $from_info = $database_community_user_formid->get($vote_user['uid']);
            if ($from_info && $from_info['formid'] && $vote_user['group_openid']) {
                $data['touser'] = $vote_user['group_openid'];
                $data['form_id'] = $from_info['formid'];
                $data['keyword3'] = '您创建的投票';
                $info = $model->sendWxappTempInfo('AT0196', $data);
                if ($info['content']) {
                    $msg = $model->sendWeixinTempMsg($info['content']);
                    fdump('$vote_user----------'.__LINE__, 'comm_finish_vote', true);
                    fdump($data, 'comm_finish_vote', true);
                    fdump($msg, 'comm_finish_vote', true);
                    $database_community_user_formid->del($from_info['formid']);
                }
            }
        }
        // 修改信息推送状态
        M('Community_vote')->where($where_vote)->data(array('is_already_push' => 2))->save();

    }
}
?>