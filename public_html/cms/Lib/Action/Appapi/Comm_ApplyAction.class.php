<?php
/*
 * 群应用
 *
 */
class Comm_ApplyAction extends BaseAction {
    // 创建群投票
    public function set_vote()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        $data = array();
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_vote = D('Community_vote');
            $database_vote_option = D('Community_vote_option');
            $database_join = D('Community_join');
            $database_info = D('Community_info');

            //群id
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }
            $data['community_id'] = $_POST['community_id'];

            //验证群状态
            $community = $database_info->where(array('community_id'=>$data['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $add_user = $database_join->field('comm_nickname')->where(array('community_id'=>$_POST['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$add_user) {
                $this->returnCode('70000009', array('community_id' => $_POST['community_id']));
            }

            // 投票主题
            if (empty($_POST['title'])) {
                $this->returnCode('71000001');
            }
            if (mb_strlen($_POST['title'],'utf-8') > 60) {
                $this->returnCode('71000002');
            }
            $data['title'] = $_POST['title'];

            // 投票选项
            if(empty($_POST['option'])){
                $this->returnCode('71000003');
            }

            // 结束时间
            $data['end_time'] = strtotime($_POST['end_time']);

            // 投票相关图片
            if(!empty($_FILES) && $_FILES['file']['error'] != 4){
                $image = D('Image')->handle($info['uid'], 'comm', 1, array('size' => 10), false);
                $option = json_decode(htmlspecialchars_decode($_POST['option']), true);
                if (!$image['error']) {
                    $_POST = array_merge($_POST, str_replace('/upload/comm/', '', $image['url']));
                } else {
                    $this->returnCode(1,array(), $image['message']);
                }
            } else {
                $option = $_POST['option'];
            }

            //测试数据
            // $option = array(
            //     '选项1','选项2','选项3'
            // );

            if(count($option)<2){
                $this->returnCode('71000004');
            }
            if(count($option)>10){
                $this->returnCode('71000005');
            }
            if(is_array($option)){
                foreach ($option as $value) {
                    if (mb_strlen($value,'utf-8') > 30) {
                        $this->returnCode('71000006');
                    }
                }
            }

            // 投票类型 （1： 单选  2： 多选，最多2项  3： 多选，无限制）
            $data['type'] = !empty($_POST['type']) ? $_POST['type'] : 1;


            $data['image'] = $_POST['image'] ? $_POST['image']  : '';

            // 添加时间
            $data['add_time'] = time();

            // 是否需要模板消息通知群成员：0-否，1-是
            $data['is_remind'] = !empty($_POST['is_remind']) ? $_POST['is_remind'] : 0;

            $data['uid'] = $info['uid'];
            D()->startTrans();
            $addInfo = $database_vote->data($data)->add();
            $msg['id'] = $addInfo;
            $msg['type'] = 'vote';
            if($data['image']){
                $msg['img'] = C('config.site_url').'/upload/comm/'.$data['image'];
            }else{
                $msg['img'] = C('config.site_url') .'/static/wxapp/group_vote.png';
            }
            $msg['content'] = $data['title'];
            $dynamic_data=array(
                'community_id'  =>  $_POST['community_id'],
                'user_id'       =>  $info['uid'],
                'application_detail'  =>  serialize($msg),
                'addtime'       =>  time()
            );
            $dynamic = D('Community_dynamic')->data($dynamic_data)->add();
            if(!$dynamic){
                $this->returnCode(1,array(),'同步到动态失败!请重试~');
            }
            // var_dump($database_vote->getLastSql());
            if($addInfo){
                //添加选项
                $option_data = [];
                $image_model = D('Image');
                foreach ($option as $key => $value) {
                    $option_data[$key]['vote_id'] = $addInfo;
                    $option_data[$key]['community_id'] = $data['community_id'];
                    $option_data[$key]['name'] = $value['content'];
                    $option_data[$key]['add_time'] = time();
                    if($value['pigcms_id']){//投票选项是否上传了图片
                        $where_img['pigcms_id'] = $value['pigcms_id'];
                        $where_img['status'] = array('neq',4);
                        $res = $image_model->field('pic')->where($where_img)->find();
                        $option_data[$key]['img'] = C('config.site_url') . $res['pic'];
                    }
                }
                $addOption = $database_vote_option->addAll($option_data);
                
                if (!$addOption) {
                    D()->rollback();
                    $this->returnCode(1,array(),'发布失败！请重试');
                }

                $image_model->update_table_id('/upload/comm/' . $_POST['image'], $addInfo, 'comm');


                // 创建投票成功 同步发消息到云通讯
                $uid = $info['uid'];
                $group_id = $_POST['community_id'];
                $msg_body = array();
                $msgType = array();
                $msgType['MsgType'] = 'TIMTextElem';
//                if (!empty($data['image'])) {
//                    $url = C('config.site_url') . '/upload/comm/' . $data['image'];
//                } else {
//                    // 没有就取默认值
//                    $url = C('config.site_url') .'/static/wxapp/group_vote.png';
//                }
                $url = C('config.site_url') .'/static/wxapp/group_vote.png';
                $msgType['MsgContent'] = array(
                    'Text' => '【￥vote￥】&' . $addInfo . '&'.urlencode($_POST['title']) . '&' . urlencode( $url)
                );
                $msg_body[] = $msgType;
                $ret_group = $database_info->qcloud_send_group_msg($group_id, $msg_body, $uid);
                if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                    D()->commit();
                } else {
                    D()->rollback();
                    $this->returnCode(1, array(), '发布失败！请重试~');
                }
                // 如果需要模板推送 就推送消息
                if ($data['is_remind'] && $data['is_remind'] == 1) {
                    D('Community_join')->create_comm_plan($data['community_id'], 'comm_add_vote', array(), $addInfo);
                }
                $this->returnCode(0,array('vote_id'=>$addInfo));
            }else{
                D()->rollback();
                $this->returnCode(1,array(),'发布失败！请重试');
            }
        }
    }

    /**
     * [upload_img 上传图片]
     * @return [type] [description]
     */
    public function upload_img(){
        if(!empty($_FILES) && $_FILES['file']['error'] != 4){
            $image = D('Image')->handle($this->_uid, 'comm', 2, array('size' => 10), false,true);
            if (!$image['error']) {
                $this->returnCode(0,$image['pigcms_id']);
            } else {
                $this->returnCode(1,array(), $image['message']);
            }
        }
    }

    // 分享群投票
    public function vote_share()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        } else {
            // $info['uid'] = 16;
            $this->returnCode('20044013');
        }

        // 群投票id
        if (empty($_POST['vote_id'])) {
            $this->returnCode('71000007');
        }

        $database_vote = D('Community_vote');
        $condition_info['vote_id'] = $_POST['vote_id'];
        $info = $database_vote->field('vote_id, title, uid')->where($condition_info)->find();
        //查询昵称
        $user = D('User')->field('nickname')->where('uid='.$info['uid'])->find();
        $nickname = $user['nickname'] ? $user['nickname'] : '';

        if (!empty($info)) {
            // $info['share_url'] = C('config.site_url') . '/wap.php?g=Wap&c=Library&a=visitor_list_open_time&activity_id=' . $_POST['activity_id'];
            $info['share_info'] = '您好，'.$nickname.'发起的了【' . $info['title'] . '】投票，欢迎您的投票！';
        } else {
            $info = array();
        }
        $this->returnCode(0, $info);
    }

    // 删除群投票
    public function delete_vote(){
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        // 过滤群投票id
        if (empty($_POST['vote_id'])) {
            $this->returnCode('71000007');
        }

        $database_vote = D('Community_vote');
        $database_info = D('Community_info');
        $database_join = D('Community_join');

        // 获取对应数据
        $vote_info = $database_vote->where(array('vote_id'=>$_POST['vote_id']))->find();
        if (!$vote_info) {
            $this->returnCode('71000008');
        }
        //已删除
        if ($vote_info['status'] == 0) {
            $this->returnCode('71000031');
        }

        //验证群状态
        $community = $database_info->where(array('community_id'=>$vote_info['community_id']))->find();
        if (!$community) {
            $this->returnCode('70000005');
        } elseif ($community['status'] == 3) {
            $this->returnCode('70000008');
        } elseif ($community['status'] == 2) {
            $this->returnCode('70000007');
        }

        // 验证是否是群成员
        $is_exist = $database_join->where(array('community_id'=>$vote_info['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
        if (!$is_exist) {
            $this->returnCode('70000009', array('community_id' => $vote_info['community_id']));
        }

        //验证是否是发起者且不为群主
        if ($vote_info['uid'] != $info['uid'] && $community['group_owner_uid'] != $info['uid']) {
            $this->returnCode('71000009');
        }


        $dis = $database_vote->where(array('vote_id'=>$_POST['vote_id']))->data(array('status' => 0))->save();
        if($dis){
            $this->returnCode(0,'删除成功！');
        }else{
            $this->returnCode(1,array(),'删除失败！请重试~');
        }
    }

    // 获取群投票列表
    public function get_vote_list()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_vote_option = D('Community_vote_option');
            $database_vote_user = D('Community_vote_user');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //群id
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$_POST['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }


            // 验证是否是群成员
            $join_info = $database_join->where(array('community_id'=>$_POST['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$join_info) {
                $this->returnCode('70000009', array('community_id' => $_POST['community_id']));
            }

            // 获取投票列表
            $where = array();
            // $where['community_id'] = $_POST['community_id'];
            // $where['status'] = 1;

            $field = array('`u`.`avatar`','`cj`.`comm_nickname` as nickname', '`v`.*' );
            $table = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_vote'=>'v',C('DB_PREFIX').'community_join'=>'cj');
            $where['_string'] = '`u`.`uid` = `v`.`uid` AND `cj`.`add_uid` = `v`.`uid` AND `cj`.`community_id` = '.$_POST['community_id'].' AND `v`.`status`=1 AND `v`.`community_id`='.intval($_POST['community_id']);

            $vote_count = D()->table($table)->where($where)->count();
            import('@.ORG.comm_page');
            $p = new Page($vote_count,6);

            $vote_list = D()->table($table)->field($field)->where($where)->order('`v`.`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();

            if ($vote_list) {
                foreach ($vote_list as $key => $value) {
                    //开始时间
                    $vote_list[$key]['add_time_format']  = tmspan($vote_list[$key]['add_time']);
                    //截止时间
                    // 获取年份
                    $now_year = date('Y');
                    $year = date('Y', $vote_list[$key]['end_time']);
                    if (intval($now_year) == intval($year)) {
                        $vote_list[$key]['end_time_format']  = date('m-d H:i', $vote_list[$key]['end_time']);
                    } else {
                        $vote_list[$key]['end_time_format']  = date('Y-m-d H:i', $vote_list[$key]['end_time']);
                    }

                    //获取投票选项详情
                    $option_list = $database_vote_option->where(array('vote_id'=>$value['vote_id']))->select();
                    $vote_list[$key]['option'] = $option_list;

                    //是否投票
                    $vote_user = $database_vote_user->where(array('vote_id'=>$value['vote_id'],'uid'=>$info['uid']))->find();
                    if ($vote_user) {
                        $vote_list[$key]['is_vote'] = 1;
                    } else {
                        $vote_list[$key]['is_vote'] = 0;
                    }
                    //查询头像
                    // $user = $database_user->field('avatar,nickname')->where('uid='.$value['uid'])->find();
                    // $vote_list[$key]['avatar'] = $user['avatar'] ? $user['avatar'] : '';
                    // $vote_list[$key]['nickname'] = $user['nickname'] ? $user['nickname'] : '';

                    // 是否结束
                    $vote_list[$key]['is_end'] = $vote_list[$key]['end_time']<time() ? 1 : 0;

                }
            }else{
                $vote_list = [];
            }
            // 处理一下禁言时间显示
            $excuse_time_info = 0;
            if ($join_info['excuse_status'] == 2) {
                $timestamp = intval($join_info['excuse_end_time']) - intval(time());
                if ($timestamp > 0) {
                    $excuse_time_info = $this->excuse_time_info($timestamp);
                } else {
                    $excuse_time_info = 0;
                }
            }
            $vote_info = array(
                'list' => $vote_list,
                'excuse_time_info' => $excuse_time_info
            );
            $this->returnCode(0,$vote_info);
        }
    }

    // 获取群投票详情
    public function get_vote_detail()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }
        $where = array();
        if (!empty($_POST)) {
            // 处理数据
            $database_vote = D('Community_vote');
            $database_vote_option = D('Community_vote_option');
            $database_vote_user = D('Community_vote_user');
            $database_user = D('User');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //群投票id
            if (empty($_POST['vote_id'])) {
                $this->returnCode('71000007');
            }

            // 获取投票详情
            $where['vote_id'] = $_POST['vote_id'];

            $vote_info = $database_vote->where($where)->find();
            if (!$vote_info) {
                $this->returnCode('71000008');
            }
            //已删除
            if ($vote_info['status'] == 0) {
                $this->returnCode('71000031');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$vote_info['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            // 验证是否是群成员
            $is_exist = $database_join->where(array('community_id'=>$vote_info['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_exist) {
                $this->returnCode('70000009', array('community_id' => $vote_info['community_id']));
            }

            //开始时间
            $vote_info['add_time_format']  = tmspan($vote_info['add_time']);
            //截止时间
            // 获取年份
            $now_year = date('Y');
            $year = date('Y', $vote_info['end_time']);
            if (intval($now_year) == intval($year)) {
                $vote_info['end_time_format']  = date('m-d H:i', $vote_info['end_time']);
            } else {
                $vote_info['end_time_format']  = date('Y-m-d H:i', $vote_info['end_time']);
            }

            //获取投票选项详情
            $option_list = $database_vote_option->where($where)->select();
            $vote_info['option'] = $option_list ? $option_list : [];
            // 个人票选项
            $vote_self = $database_vote_user->where(array('vote_id'=>$_POST['vote_id'], 'uid'=>$info['uid']))->select();
            if (!empty($vote_self)) {
                if (!empty($vote_info['option'])) {
                    foreach ($vote_info['option'] as &$val) {
                        foreach ($vote_self as $v) {
                            if ($val['option_id'] == $v['option_id']) {
                                $val['is_vote'] = 1;
                            } elseif ($val['is_vote'] != 1) {
                                $val['is_vote'] = 0;
                            }
                        }
                    }
                }
            }

            //已投票选项
            // $field = array('`u`.`avatar`','`u`.`nickname`', '`vu`.*' );
            // $table = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_vote_user'=>'vu');
            // $where['_string'] = '`u`.`uid` = `vu`.`uid` AND `vu`.`vote_id`='.intval($_POST['vote_id']);
            // $vote_user = D('')->field($field)->table($table)->where($where)->order('`vu`.`add_time` DESC')->select();

            $field = array('`u`.`avatar`','`cj`.`comm_nickname` as nickname', '`vu`.*' );
            $table = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_vote_user'=>'vu',C('DB_PREFIX').'community_join'=>'cj');
            $where['_string'] = '`u`.`uid` = `vu`.`uid` AND `cj`.`add_uid` = `vu`.`uid` AND `cj`.`community_id` = '.$vote_info['community_id'].' AND `vu`.`vote_id`='.intval($_POST['vote_id']);

            $vote_user = D('')->field($field)->table($table)->where($where)->group('vu.uid')->order('`vu`.`add_time` DESC')->select();

            // 取用户头像
            if (!empty($vote_user)){
                foreach ($vote_user as &$v) {
                    //投票时间
                    $v['add_time_format']  = tmspan($v['add_time']);
                }
            }
            $vote_info['selected_option'] = $vote_user ? $vote_user : [];

            //查询头像,昵称
            $user = $database_user->field('avatar,nickname')->where('uid='.$vote_info['uid'])->find();
            $join_info = $database_join->field('comm_nickname')->where(array('add_uid'=>$vote_info['uid'],'community_id'=>$vote_info['community_id']))->find();
            $vote_info['avatar'] = $user['avatar'] ? $user['avatar'] : '';
            $vote_info['nickname'] = $join_info['comm_nickname'] ? $join_info['comm_nickname'] : $user['nickname'];

            // 是否结束
            $vote_info['is_end'] = $vote_info['end_time']<time() ? 1 : 0;

            // 投票人数
            $member_count = $database_vote_user->field('COUNT(DISTINCT(uid)) as count')->where(array('vote_id'=>$_POST['vote_id']))->find();
            $vote_info['member_count'] = isset($member_count)&&$member_count['count'] ? $member_count['count'] : 0;

            //投票类型
            $type_list = array(1=> '单选', 2 => '多选，最多2项', 3 => '多选，无限制');
            $vote_info['type_name'] = $type_list[$vote_info['type']];
            if ($vote_info['image']) {
                if (strpos($vote_info['image'], 'community') == false) {
                    $vote_info['image'] =C('config.site_url') . '/upload/comm/' . $vote_info['image'];
                } else {
                    $vote_info['image'] =C('config.site_url') . $vote_info['image'];
                }
            }

            //是否投票
            $vote_user = $database_vote_user->where(array('vote_id'=>$vote_info['vote_id'],'uid'=>$info['uid']))->find();
            if ($vote_user) {
                $vote_info['is_vote'] = 1;
            } else {
                $vote_info['is_vote'] = 0;
            }
            // 判断是否可删除
            if ($vote_info['uid'] == $info['uid']){
                $vote_info['is_del'] = 1;
            } elseif ($community['group_owner_uid'] == $info['uid']){
                $vote_info['is_del'] = 1;
            } else {
                $vote_info['is_del'] = 0;
            }

            $this->returnCode(0,$vote_info);
        }
    }

    //选项详情
    public function get_option_detail()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理数据
            $database_vote = D('Community_vote');
            $database_vote_option = D('Community_vote_option');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //选项id
            if (empty($_POST['option_id'])) {
                $this->returnCode('71000010');
            }

            // 获取选项详情
            $where = array();
            $where['option_id'] = $_POST['option_id'];
            $vote_option = $database_vote_option->where($where)->find();

            if (!$vote_option) {
                $this->returnCode('71000011');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$vote_option['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            // 验证是否是群成员
            $is_exist = $database_join->where(array('community_id'=>$vote_option['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_exist) {
                $this->returnCode('70000009', array('community_id' => $vote_option['community_id']));
            }

            // 获取投票详情
            $vote_info = $database_vote->where(array('vote_id'=>$vote_option['vote_id']))->find();
            if (!$vote_info) {
                $this->returnCode('71000008');
            }
            //已删除
            if ($vote_info['status'] == 0) {
                $this->returnCode('71000031');
            }

            //选项投票人员
            //已投票选项
            $field = array('`u`.`avatar`','`cj`.`comm_nickname` as nickname', '`vu`.*' );
            $table = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_vote_user'=>'vu',C('DB_PREFIX').'community_join'=>'cj');
            $where = '`u`.`uid` = `vu`.`uid` AND `cj`.`add_uid` = `vu`.`uid` AND `cj`.`community_id` = '.$vote_info['community_id'].' AND `vu`.`option_id`='.intval($_POST['option_id']);
            $vote_user = D('')->field($field)->table($table)->where($where)->order('`vu`.`add_time` DESC')->select();

            if ($vote_user) { //avatar
                foreach ($vote_user as &$value) {
                    //投票时间
                    $value['add_time_format']  = tmspan($value['add_time']);
                }
            }
            $vote_option['selected_user'] = $vote_user ? $vote_user : [];

            $this->returnCode(0,$vote_option);
        }
    }

    //投票
    public function set_option()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        $data = array();
        if (!empty($_POST)) {
            // 处理数据
            $database_vote = D('Community_vote');
            $database_vote_option = D('Community_vote_option');
            $database_vote_user = D('Community_vote_user');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //群投票id
            if (empty($_POST['vote_id'])) {
                $this->returnCode('71000007');
            }

            //选项id
            if (empty($_POST['option_id'])) {
                $this->returnCode('71000010');
            }

            // 获取投票详情
            $vote_info = $database_vote->where(array('vote_id'=>$_POST['vote_id']))->find();
            if (!$vote_info) {
                $this->returnCode('71000008');
            }
            //已删除
            if ($vote_info['status'] == 0) {
                $this->returnCode('71000031');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$vote_info['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            // 验证是否是群成员
            $is_exist = $database_join->where(array('community_id'=>$vote_info['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_exist) {
                $this->returnCode('70000009', array('community_id' => $vote_info['community_id']));
            }

            // 验证是否已投过票
            $is_voted = $database_vote_user->where(array('vote_id'=>$vote_info['vote_id'],'uid'=>$info['uid']))->find();
            if ($is_voted) {
                $this->returnCode('71000014');
            }

            // 验证单选，多选
            $option_ids = explode(',',trim($_POST['option_id'],','));
            $count = count($option_ids);
            if ($vote_info['type'] == 1 && $count > 1) { // 单选
                $this->returnCode('71000012');
            } elseif ($vote_info['type'] == 2 && $count > 2) { // 多选，最多2项
                $this->returnCode('71000013');
            }

            // 查询选项信息
            $where = 'option_id in ('.$_POST['option_id'].') AND vote_id='.$_POST['vote_id'];
            $vote_option = $database_vote_option->where($where)->select();
            // var_dump($database_vote_option->getLastSql());
            if (!$vote_option) {
                $this->returnCode('71000011');
            }

            //插入表
            D()->startTrans();
            foreach ($vote_option as $key => $value) {
                $data = array(
                    'uid' => $info['uid'],
                    'vote_id' => $value['vote_id'],
                    'option_id' => $value['option_id'],
                    'add_time' => time(),
                );
                $add_vote_user = $database_vote_user->data($data)->add();
                if (!$add_vote_user) {
                    D()->rollback();
                    $this->returnCode(1,array(),'投票失败！请重试~');
                }
                $number1 = $database_vote_option->where('option_id='.$value['option_id'])->setInc('number',1); // 选项选择人数加1
                if (!$number1) {
                    D()->rollback();
                    $this->returnCode(1,array(),'投票失败！请重试~');
                }
                $number2 = $database_vote->where('vote_id='.$value['vote_id'])->setInc('number',1); // 群投票选择人数加1
                if (!$number2) {
                    D()->rollback();
                    $this->returnCode(1,array(),'投票失败！请重试~');
                }
            }
            if ($add_vote_user) {
                D()->commit();
                $this->returnCode(0,'投票成功！');
            }else{
                D()->rollback();
                $this->returnCode(1,array(),'投票失败！请重试~');
            }
        }
    }

    // 创建群公告 只有管理员能发布
    public function set_notice()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        $data = array();
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_notice = D('Community_notice');
            $database_join = D('Community_join');
            $database_notice_user = D('Community_notice_user');
            $database_info = D('Community_info');

            //群id
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }
            $data['community_id'] = $_POST['community_id'];

            //验证群状态
            $community = $database_info->where(array('community_id'=>$_POST['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            // 验证是否是群成员
            $add_user = $database_join->field('comm_nickname')->where(array('community_id'=>$_POST['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$add_user) {
                $this->returnCode('70000009', array('community_id' => $_POST['community_id']));
            }

            //验证是否是该群群主
            $is_exist = $database_join->where(array('community_id'=>$data['community_id'],'add_uid'=>$info['uid'],'group_owner'=>2))->find();
            if (!$is_exist) {
                $this->returnCode('71000015');
            }

            // 公告主题
            if (empty($_POST['title'])) {
                $this->returnCode('71000016');
            }
            if (mb_strlen($_POST['title'],'utf-8') > 40) {
                $this->returnCode('71000017');
            }
            $data['title'] = $_POST['title'];

            // 公告内容
            if (empty($_POST['content'])) {
                $this->returnCode('71000018');
            }
            $data['content'] = $_POST['content'];

            // 公告关联文件
            if (!empty($_POST['file_id'])) {
                $data['file_id'] = $_POST['file_id'];
            }

            // 添加时间
            $data['add_time'] = time();

            // 是否需要模板消息通知群成员：0-否，1-是
            $data['is_remind'] = !empty($_POST['is_remind']) ? $_POST['is_remind'] : 0;

            $data['uid'] = $info['uid'];
            $data['status'] = 1;
            D()->startTrans();
            $notice_id = $database_notice->data($data)->add();
            $img = '/static/wxapp/group_notice.png';
            $msg['id'] = $notice_id;
            $msg['type'] = 'notice';
            $msg['img'] = C('config.site_url').$img;
            $msg['content'] = $data['title'];
            $dynamic_data=array(
                'community_id'  =>  $data['community_id'],
                'user_id'       =>  $data['uid'],
                'application_detail'  =>  serialize($msg),
                'addtime'       =>  time()
            );
            $dynamic = D('Community_dynamic')->data($dynamic_data)->add();
            if(!$dynamic){
                $this->returnCode(1,array(),'同步到动态失败!请重试~');
            }
            // var_dump($database_vote->getLastSql());
            if($notice_id){
                //获取所有群成员 不排除群主
                $all_join = $database_join->field('add_uid')->where('community_id='.$data['community_id'].' AND add_status=3')->select();
                if ($all_join) {
                    $join_data = [];
                    foreach ($all_join as $key => $value) {
                        $join_data[$key]['notice_id'] = $notice_id;
                        $join_data[$key]['community_id'] = $data['community_id'];
                        $join_data[$key]['uid'] = $value['add_uid'];
                        $join_data[$key]['add_time'] = time();
                    }
                    $add_notice_user = $database_notice_user->addAll($join_data);
                    if (!$add_notice_user) {
                        D()->rollback();
                        $this->returnCode(1,array(),'发布失败！请重试');
                    }
                }

                // 创建群公告 同步发消息到云通讯
                $uid = $info['uid'];
                $group_id = $_POST['community_id'];
                $msg_body = array();
                $msgType = array();
                $msgType['MsgType'] = 'TIMTextElem';
                $url = C('config.site_url') . '/static/wxapp/group_notice.png';
                $msgType['MsgContent'] = array(
                    'Text' => '【￥notice￥】&' . $notice_id . '&'.urlencode($_POST['title']) . '&' . urlencode( $url)
                );
                $msg_body[] = $msgType;
                $ret_group = $database_info->qcloud_send_group_msg($group_id, $msg_body, $uid);
                if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                    D()->commit();
                } else {
                    D()->rollback();
                    $this->returnCode(1, array(), '发布失败！请重试~');
                }
                // 判断一下是否推送
                if ($data['is_remind'] && $data['is_remind'] == 1) {
                    D('Community_join')->create_comm_plan($data['community_id'], 'comm_add_notice', array(), $notice_id);
                }
                $this->returnCode(0,array('notice_id' => $notice_id, 'msg' => '发布成功！'));
            }else{
                D()->rollback();
                $this->returnCode(1,array(),'发布失败！请重试');
            }
        }
    }

    // 分享群公告
    public function notice_share()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        } else {
            // $info['uid'] = 16;
            $this->returnCode('20044013');
        }
        $database_notice = D('Community_notice');

        //公告id
        if (empty($_POST['notice_id'])) {
            $this->returnCode('71000020');
        }

        //查询公告
        $notice_info = $database_notice->field('notice_id,title,uid,status')->where(array('notice_id'=>$_POST['notice_id']))->find();
        if (!$notice_info) {
            $this->returnCode('71000021');
        }
        if ($notice_info['status'] == 0) {
            $this->returnCode('71000032');
        }

        //查询昵称
        $create_user = D('User')->field('nickname')->where('uid='.$notice_info['uid'])->find();
        $share_user = D('User')->field('nickname')->where('uid='.$info['uid'])->find();

        if (!empty($notice_info)) {
            // $info['share_url'] = C('config.site_url') . '/wap.php?g=Wap&c=Library&a=visitor_list_open_time&activity_id=' . $_POST['activity_id'];
            $notice_info['share_info'] = '您好，'.$create_user['nickname'].'发布了【' . $notice_info['title'] . '】群公告，欢迎您的查看！';
            $notice_info['share_user'] = $share_user['nickname'];
        } else {
            $notice_info = array();
        }
        $this->returnCode(0, $notice_info);
    }

    // 查看公告详情，并设置已读
    public function notice_detail()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }

        $data = array();
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_notice = D('Community_notice');
            $database_notice_user = D('Community_notice_user');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //公告id
            if (empty($_POST['notice_id'])) {
                $this->returnCode('71000020');
            }

            //查询公告
            $notice_info = $database_notice->where(array('notice_id'=>$_POST['notice_id']))->find();
            if (!$notice_info) {
                $this->returnCode('71000021');
            }
            if ($notice_info['status'] == 0) {
                $this->returnCode('71000032');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$notice_info['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            // 验证是否是群成员
            $is_group_member = $database_join->where(array('community_id'=>$notice_info['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            // if (!$is_group_member) {
            //     $this->returnCode('70000009');
            // }

            if ($is_group_member && !empty($info['uid'])) { // 是群成员
                //查询该用户是否已读
                $notice_user = $database_notice_user->where(array('notice_id'=>$_POST['notice_id'],'uid'=>$info['uid']))->find();

                if (!$notice_user && $is_group_member['add_uid'] != $info['uid']) {
                    $this->returnCode('71000022');
                }
                if (!empty($notice_user)) {
                    if ($notice_user['is_read'] == 0) {
                        //设为已读
                        $data['is_read'] = 1;
                        $update = $database_notice_user->where(array('notice_id'=>$_POST['notice_id'],'uid'=>$info['uid']))->data($data)->save();
                    }
                } else {
                    //设为已读
                    $data['is_read'] = 1;
                    $data['notice_id'] =$_POST['notice_id'];
                    $data['uid'] = $info['uid'];
                    $data['community_id'] = $notice_info['community_id'];
                    $data['add_time'] = time();
                    $database_notice_user->data($data)->add();
                }
            } elseif (!empty($info['uid'])) {
                //查询该用户是否已读
                $notice_user = $database_notice_user->where(array('notice_id'=>$_POST['notice_id'],'uid'=>$info['uid']))->find();
                if (empty($notice_user)) {
                    //设为已读
                    $data['is_read'] = 1;
                    $data['notice_id'] =$_POST['notice_id'];
                    $data['uid'] = $info['uid'];
                    $data['community_id'] = $notice_info['community_id'];
                    $data['add_time'] = time();
                    $database_notice_user->data($data)->add();
                } elseif ($notice_user['is_read'] == 0) {
                    //设为已读
                    $data['is_read'] = 1;
                    $database_notice_user->where(array('notice_id'=>$_POST['notice_id'],'uid'=>$info['uid']))->data($data)->save();
                }
            }

            //查询已读人数
            $read_number = $database_notice_user->where(array('notice_id'=>$_POST['notice_id'],'is_read'=>1))->count();
            $notice_info['read_number'] = $read_number ? $read_number : 0;
            $notice_info['add_time'] = date('Y/m/d H:i' , $notice_info['add_time']);

            //查询昵称
            // $user = $database_user->field('nickname')->where('uid='.$notice_info['uid'])->find();
            $join_info = $database_join->field('comm_nickname')->where(array('add_uid'=>$notice_info['uid'],'community_id'=>$notice_info['community_id']))->find();
            $notice_info['nickname'] = $join_info['comm_nickname'] ? $join_info['comm_nickname'] : '';


            // 判断是否可删除
            if (!empty($info['uid']) && $community['group_owner_uid'] == $info['uid']){
                $notice_info['is_del'] = 1;
            } else {
                $notice_info['is_del'] = 0;
            }

            // 查询对应的文件信息
            if (!empty($notice_info) && !empty($notice_info['file_id'])) {
                $notice_info['file_detail'] = D('Community_file')->file_detail($notice_info['file_id']);
            }

            $this->returnCode(0, $notice_info);
        }
    }

    // 获得公告列表
    public function notice_list()
    {

        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理数据
            $database_notice = D('Community_notice');
            $database_notice_user = D('Community_notice_user');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //群id
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$_POST['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            // 验证是否是群成员
            $is_group_member = $database_join->where(array('community_id'=>$_POST['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $_POST['community_id']));
            }

            $where['community_id'] = $_POST['community_id'];
            $where['status'] = 1;

            $notice_count = $database_notice->where($where)->count();
            import('@.ORG.comm_page');
            $p = new Page($notice_count,6);

            $notice_list = $database_notice->where($where)->order('`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();

            if ($notice_list) {
                foreach ($notice_list as &$value) {
                    //查询已读人数
                    $read_number = $database_notice_user->where(array('notice_id'=>$value['notice_id'],'is_read'=>1))->count();
                    $value['read_number'] = $read_number ? $read_number : 0;
                    $value['add_time'] = date('Y/m/d H:i' , $value['add_time']);
                }
            }else{
                $notice_list = [];
            }
            $return = array();
            $return['list'] = $notice_list;
            // 判断是否可添加
            if ($community['group_owner_uid'] == $info['uid']){
                $return['is_add'] = 1;
            } else {
                $return['is_add'] = 0;
            }
            $return['totalPage'] = $p->totalPage;

            $this->returnCode(0, $return);
        }
    }

    // 删除群公告
    public function delete_notice(){
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        // 公告id
        if (empty($_POST['notice_id'])) {
            $this->returnCode('71000020');
        }

        $database_notice = D('Community_notice');
        $database_info = D('Community_info');
        $database_join = D('Community_join');

        // 获取对应数据
        $notice = $database_notice->where(array('notice_id'=>$_POST['notice_id']))->find();
        if (!$notice) {
            $this->returnCode('71000021');
        }
        if ($notice['status'] == 0) {
            $this->returnCode('71000032');
        }

        //验证群状态
        $community = $database_info->where(array('community_id'=>$notice['community_id']))->find();
        if (!$community) {
            $this->returnCode('70000005');
        } elseif ($community['status'] == 3) {
            $this->returnCode('70000008');
        } elseif ($community['status'] == 2) {
            $this->returnCode('70000007');
        }

        // 验证是否是群成员
        $is_group_member = $database_join->where(array('community_id'=>$notice['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
        if (!$is_group_member) {
            $this->returnCode('70000009', array('community_id' => $notice['community_id']));
        }

        //验证是否是群主
        if ($community['group_owner_uid'] != $info['uid']) {
            $this->returnCode('71000009');
        }

        $dis = $database_notice->where(array('notice_id'=>$_POST['notice_id']))->data(array('status' => 0))->save();
        if($dis){
            $this->returnCode(0,'删除成功！');
        }else{
            $this->returnCode(1,array(),'删除失败！请重试');
        }
    }

    // 创建编辑群活动
    public function set_activity()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }
        $data = array();
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_option = D('Community_activity_option');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //群id
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }
            $data['community_id'] = $_POST['community_id'];

            //验证群状态
            $community = $database_info->where(array('community_id'=>$_POST['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            // 验证是否是群成员
            $is_group_member = $database_join->field('comm_nickname')->where(array('community_id'=>$_POST['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $_POST['community_id']));
            }

            //群活动id
            $activity_id = $_POST['activity_id'];

            // 活动主题
            if (empty($_POST['title'])) {
                $this->returnCode('71000023');
            }
            // if (strlen($_POST['title']) > 60) {
            //     $this->returnCode('71000002');
            // }
            $data['title'] = $_POST['title'];

            // 开始时间
            if (empty($_POST['start_time'])) {
                $this->returnCode('71000024');
            }
            $data['start_time'] = strtotime($_POST['start_time']);

            // 结束时间
            if (empty($_POST['end_time'])) {
                $this->returnCode('71000025');
            }
            $data['end_time'] = strtotime($_POST['end_time']);

            // 活动地址
            $data['address'] = $_POST['address'] ? $_POST['address'] : '';
            $data['longitude'] = $_POST['longitude'] ? $_POST['longitude'] : '';
            $data['latitude'] = $_POST['latitude'] ? $_POST['latitude'] : '';
            $data['provincename'] = $_POST['provincename'] ? $_POST['provincename'] : '';
            $data['cityname'] = $_POST['cityname'] ? $_POST['cityname'] : '';
            $data['areaname'] = $_POST['areaname'] ? $_POST['areaname'] : '';

            // 活动详情
            if (empty($_POST['desc'])) {
                $this->returnCode('71000027');
            }
            $data['desc'] = $_POST['desc'];

            //费用设置
            $data['fees_name'] = $_POST['fees_name'] ? $_POST['fees_name'] : ''; // 费用名称
            $data['fees'] = $_POST['fees'] ? $_POST['fees'] : ''; // 费用金额
            $data['limit'] = $_POST['limit'] ? $_POST['limit'] : 0; // 名额限制

            // 活动图片
            if(!empty($_FILES) && $_FILES['file']['error'] != 4){
                $image = D('Image')->handle($info['uid'], 'comm', 1, array('size' => 10), false);
                $option = json_decode(htmlspecialchars_decode($_POST['custom']), true);
                // 报名设置
                if (!$image['error']) {
                    $_POST = array_merge($_POST, str_replace('/upload/comm/', '', $image['url']));
                } else {
                    $this->returnCode(1,array(), $image['message']);
                }
            } else {
                $option = $_POST['custom'];
            }
            // 活动图片
            $data['image'] = $_POST['image'] ? $_POST['image']  : '';

            // 咨询电话
            $data['phone'] = $_POST['phone'] ? $_POST['phone'] : 0;

            // 报名截止时间
            $data['deadline'] = $_POST['deadline'] ? strtotime($_POST['deadline']) : strtotime($_POST['end_time']); //活动结束之前均可报名
            // $data['deadline'] = $data['start_time']; // 活动开始之前都可报名

            // 是否需要模板消息通知群成员：0-否，1-是
            $data['is_remind'] = !empty($_POST['is_remind']) ? $_POST['is_remind'] : 0;

            $data['uid'] = $info['uid'];
            D()->startTrans();

            if ($activity_id) {  // 验证是否可以编辑
                //查询活动
                $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();

                if (!$activity) {
                    $this->returnCode('71000030');
                }
                if ($activity['uid'] != $info['uid']) {
                    $this->returnCode('71000055');
                }
                if ($activity['is_del'] == 1) { // 已删除
                    $this->returnCode('71000033');
                }
                if ($activity['status'] == 2) { // 已取消
                    $this->returnCode('71000034');
                }
                if ($activity['status'] == 3) { // 已结束
                    $this->returnCode('71000037');
                }
                // if ($activity['status'] == 3 || $activity['end_time'] < time()) { // 已结束
                //     $this->returnCode('71000037');
                // }

                // 暂时不给修改费用
                if ($data['fees'] != $activity['fees']) {
                    $this->returnCode('71000050');
                }
                unset($data['fees']);

                if (empty($data['image'])) {
                    unset($data['image']);
                }
                $res = $database_activity->where(array('activity_id'=>$activity_id))->data($data)->save();
                if ($res !== false) {
                    //查询报名设置
                    $option_detail = $database_activity_option->where(array('community_id'=>$data['community_id'],'activity_id'=>$activity_id,'status'=>1))->select();
                    
                    // 已有自定义名称
                    $old_title = array();

                    // 新的自定义项名称
                    $new_title = array();

                    $option_new = array();
                    if ($option) {
                        foreach ($option as $key => $value) {
                            $new_title[] = $value['title'];
                            $option_new[$value['title']] = array(
                                'title' => $value['title'], //选项名称
                                'type' => $value['type'], //类型1-单行文本，2-多行文本，3-单选，4-多选
                                'is_required' => $value['is_required'], //是否必填0-不必填，1-必填
                                'content' => $value['content'] ? json_encode($value['content']) : '',
                            );
                        }
                    }
                    if ($option_detail) {
                        //删除 编辑选项
                        foreach ($option_detail as $key => $value) {
                            $old_title[] = $value['title'];
                            if (in_array($value['title'], $new_title)) { // 编辑选项
                                $arr = $option_new[$value['title']];
                                $arr['add_time'] = time();
                                $opt = $database_activity_option->where(array('option_id'=>$value['option_id'],'activity_id'=>$activity_id))->data($arr)->save();
                            } else { // 删除选项
                                $opt = $database_activity_option->where(array('option_id'=>$value['option_id'],'activity_id'=>$activity_id))->data(array('status'=>0))->save();
                            }
                            if (!$opt) {
                                D()->rollback();
                                $this->returnCode(1,array(),'编辑失败！请重试');
                            }
                        }
                    }

                    // 新增选项
                    if ($option) {
                        foreach ($option as $key => $value) {
                            if (!in_array($value['title'], $old_title)) {
                                $option_data = array(
                                    'title' => $value['title'], //选项名称
                                    'type' => $value['type'], //类型1-单行文本，2-多行文本，3-单选，4-多选
                                    'is_required' => $value['is_required'], //是否必填0-不必填，1-必填
                                    'community_id' => $data['community_id'],
                                    'activity_id' => $activity_id,
                                    'content' => $value['content'] ? json_encode($value['content']) : '',
                                    'uid' => $info['uid'],
                                    'add_time' => time(),
                                );
                                $add = $database_activity_option->data($option_data)->add();
                                if (!$add) {
                                    D()->rollback();
                                    $this->returnCode(1,array(),'编辑失败！请重试');
                                }
                            }
                        }
                    }
                    // // 修改群活动 同步发消息到云通讯
                    // $title = empty($_POST['title']) ? $activity['title'] : $_POST['title'];
                    // $uid = $info['uid'];
                    // $group_id = $_POST['community_id'];
                    // $msg_body = array();
                    // $msgType = array();
                    // $msgType['MsgType'] = 'TIMTextElem';
                    // if (!empty($data['image'])) {
                    //     $url = C('config.site_url') . '/upload/comm/' . $data['image'];
                    // } elseif (!empty($activity['image'])) {
                    //     $url = C('config.site_url') . '/upload/comm/' . $activity['image'];
                    // } else {
                    //     // 没有就取默认值
                    //     $url = C('config.site_url') .'/static/wxapp/group_activity.png';
                    // }
                    // $msgType['MsgContent'] = array(
                    //     'Text' => '【￥activity￥】&' . $activity_id . '&'.urlencode($title) . '&' . urlencode( $url)
                    // );
                    // $msg_body[] = $msgType;
                    // $ret_group = $database_info->qcloud_send_group_msg($group_id, $msg_body, $uid);
                    // if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                    //     D()->commit();
                    //     $this->returnCode(0,array('activity_id'=>$activity_id));
                    // } else {
                    //     D()->rollback();
                    //     $this->returnCode(1, array(), '编辑失败！请重试~');
                    // }
                    D()->commit();
                    $this->returnCode(0,array('activity_id'=>$activity_id));
                } else {
                    D()->rollback();
                    $this->returnCode(1,array(),'编辑失败！请重试');
                }

            }else{
                // 新增
                // 添加时间
                $data['add_time'] = time();
                $data['enroll_status'] = 1; //报名状态:1-进行中，2-已结束
                $activity_id = $database_activity->data($data)->add();
                $msg['id'] = $activity_id;
                $msg['type'] = 'activity';
                $msg['img'] = C('config.site_url').'/upload/comm/'.$data['image'];
                $msg['content'] = $data['title'];
                $dynamic_data=array(
                    'community_id'  =>  $data['community_id'],
                    'user_id'       =>  $data['uid'],
                    'application_detail'       =>  serialize($msg),
                    'addtime'       =>  time()
                );
                $dynamic = D('Community_dynamic')->data($dynamic_data)->add();
                if(!$dynamic){
                    $this->returnCode(1,array(),'同步到动态失败!请重试~');
                }
                if($activity_id){
                    //添加选项
                    $option_data = [];
                    if ($option) {
                        foreach ($option as $key => $value) {
                            $option_data[$key]['activity_id'] = $activity_id;
                            $option_data[$key]['community_id'] = $data['community_id'];
                            $option_data[$key]['uid'] = $info['uid'];
                            $option_data[$key]['title'] = $value['title'];
                            $option_data[$key]['is_required'] = $value['is_required']; // 是否必填0-不必填，1-必填
                            $option_data[$key]['type'] =  $value['type']; // 默认单行文本（类型1-单行文本，2-多行文本，3-单选，4-多选）
                            $option_data[$key]['content'] = $value['content'] ? json_encode($value['content']) : '';
                            $option_data[$key]['add_time'] = time();
                        }
                        $option_data = array_values($option_data);
                        $addOption = $database_activity_option->addAll($option_data);
                        if (!$addOption) {
                            D()->rollback();
                            $this->returnCode(1,array(),'发布失败！请重试');
                        }
                    }

                    // 创建群活动 同步发消息到云通讯
                    $uid = $info['uid'];
                    $group_id = $_POST['community_id'];
                    $msg_body = array();
                    $msgType = array();
                    $msgType['MsgType'] = 'TIMTextElem';
                    if (!empty($data['image'])) {
                        $url = C('config.site_url') . '/upload/comm/' . $data['image'];
                    } else {
                        // 没有就取默认值
                        $url = C('config.site_url') .'/static/wxapp/group_activity.png';
                    }
                    $msgType['MsgContent'] = array(
                        'Text' => '【￥activity￥】&' . $activity_id . '&'.urlencode($_POST['title']) . '&' . urlencode( $url)
                    );
                    $msg_body[] = $msgType;
                    $ret_group = $database_info->qcloud_send_group_msg($group_id, $msg_body, $uid);
                    if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                        D()->commit();
                    } else {
                        D()->rollback();
                        $this->returnCode(1, array(), '发布失败！请重试~');
                    }
                    // 查询一下创建者名称
                    if ($data['is_remind'] && $data['is_remind'] == 1) {
                        D('Community_join')->create_comm_plan($data['community_id'], 'comm_add_activity', array(), $activity_id);
                    }
                    $this->returnCode(0,array('activity_id'=>$activity_id));
                }else{
                    D()->rollback();
                    $this->returnCode(1,array(),'发布失败！请重试');
                }
            }
        }
    }

    private function get_curl_img($url,$path,$uniName){
        //图片存放的路径
        if(!file_exists($path)){
            mkdir($path,0777,true); //创建目录
            chmod($path,0777); //赋予权限
        }
        //确保图片名唯一，防止重名产生覆盖
        $res =put_file_from_url_content($url,$path,$uniName);
        if($res){
            $result = C('config.site_url').substr($res['save_path'],1);
            return $result;
        }
    }
    // 获得活动详情
    public function activity_detail()
    {   
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_option = D('Community_activity_option');
            $database_user = D('User');
            $database_info = D('Community_info');
            $database_join = D('Community_join');
            $database_activity_join = D('Community_activity_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) {
                $this->returnCode('71000033');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            // $is_group_member = $database_join->where(array('community_id'=>$activity['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            // if (!$is_group_member) {
            //     $this->returnCode('70000009');
            // }
            $now_time = time();
            if (intval($activity['deadline']) > intval($now_time)) {
                $activity['is_sign_end'] = false;
            } else {
                $activity['is_sign_end'] = true;
            }
            if (intval($activity['end_time']) > intval($now_time)) {
                $activity['is_end'] = false;
            } else {
                $activity['is_end'] = true;
            }

            $activity['start_time'] = date('Y-m-d H:i' , $activity['start_time']);
            $activity['end_time'] = date('Y-m-d H:i' , $activity['end_time']);
            $activity['deadline'] = date('Y-m-d H:i' , $activity['deadline']);
            $activity['add_time'] = date('Y-m-d H:i' , $activity['add_time']);

            //查询昵称
            $user = $database_user->field('avatar,nickname')->where('uid='.$activity['uid'])->find();
            $join_info = $database_join->field('comm_nickname')->where(array('add_uid' => $activity['uid'],'community_id'=>$activity['community_id']))->find();
            $activity['avatar'] = $user['avatar'] ? $user['avatar'] : '';

            //图片存放的路径
            $data_time =date('Y-m-d',time());
            $path = "./upload/user/".$data_time."/";
            
            //确保图片名唯一，防止重名产生覆盖
            $uniName ='wx_'.$activity['uid'].'.jpg';
         
            $activity['avatar'] = $this->get_curl_img($user['avatar'],$path,$uniName);
            $activity['nickname'] = $join_info['comm_nickname'] ? $join_info['comm_nickname'] : '';

            // 参与者
            $field = array('`u`.`avatar`','`u`.`nickname`', '`aj`.*' );
            $table = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_activity_join'=>'aj');
            $where['_string'] = '`u`.`uid` = `aj`.`uid` AND `aj`.`status` in(1,2) AND `aj`.`activity_id`='.intval($_POST['activity_id']);
            $join_list = D('')->field($field)->table($table)->where($where)->order('`aj`.`add_time` DESC')->limit(6)->select();
            if ($join_list) {
                foreach ($join_list as &$value) {
                    $value['content'] = $value['content'] ? json_decode($value['content']) : $value['content'];
                    $value['add_time'] = time_info($value['add_time']);
                }
            }
            $activity['join_list'] = $join_list ? $join_list : [];

            //报名人数
            $activity['number'] = count($join_list);

            //浏览量 增加
            if ($info['uid']) {
                $data = array(
                    'activity_id' => $_POST['activity_id'],
                    'uid' => $info['uid'],
                    'add_time' => time(),
                );
                D('community_activity_views')->add($data);
            }

            //统计浏览量
            $view_count = D('community_activity_views')->field('COUNT(DISTINCT(uid)) as count')->where(array('activity_id'=>$_POST['activity_id']))->find();
            $activity['view_count'] = isset($view_count)&&$view_count['count'] ? $view_count['count'] : 0;

            // 自定义报名项
            $custom_list = $database_activity_option->where(array('activity_id'=>$_POST['activity_id'],'status'=>1))->select();
            if ($custom_list) {
                foreach ($custom_list as &$value) {
                    $value['content'] = $value['content'] ? json_decode($value['content']) : $value['content'];
                }
            }
            $activity['custom_list'] = $custom_list ? $custom_list : [];

            //活动图片
            if ($activity['image']) {
                if (strpos($activity['image'], 'community') == false) {
                    $activity['image'] = C('config.site_url') . '/upload/comm/' . $activity['image'];
                } else {
                    $activity['image'] =C('config.site_url') . $activity['image'];
                }
            }
            //活动excel 文件
            unset($activity['excel_url']);

            // 查询订单
            $activity['order'] = [];
            $activity['is_join'] = 0;
            // 是否已经报名
            if ($info['uid']) {
                $is_join = $database_activity_join->where(array('activity_id'=>$_POST['activity_id'],'uid'=>$info['uid']))->find();
                if ($is_join) {
                    if ($is_join['status'] == 2 || $is_join['status'] == 1) { // 已报名成功
                        $activity['is_join'] = 1;
                    }
                    $activity['join_status'] = $is_join['status'];
                    // 查询订单
                    if ($is_join['order_id']>0) {
                        $activity['order'] = D('Community_order')->where(array('order_id'=>$is_join['order_id']))->find();
                        $activity['order']['add_time'] = date('Y-m-d H:i',$activity['order']['add_time']);
                    }
                }
            }

            // 是否创建人
            $activity['is_owner'] = ($activity['uid']==$info['uid']) ? 1 : 0;

            // 判断收费的余额是否足够
            $user_info = D('User')->get_user($info['uid']);
            $activity['now_money'] = $user_info['now_money'];

            if ($activity['fees'] > $activity['now_money']) {
                $activity['pay_money'] = $activity['fees'] - $user_info['now_money'];
            }else{
                $activity['pay_money'] = 0;
            }

            $this->returnCode(0, $activity);
        }
    }

    // 获得活动参与者列表
    public function activity_join()
    {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_info = D('Community_info');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) {
                $this->returnCode('71000033');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }
            // 参与者
            $field = array('`u`.`avatar`','`u`.`nickname`', '`aj`.*' );
            $table = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_activity_join'=>'aj');
            $where['_string'] = '`u`.`uid` = `aj`.`uid` AND `aj`.`status` in(1,2) AND `aj`.`activity_id`='.intval($_POST['activity_id']);
            $join_count = D('')->field($field)->table($table)->where($where)->count();
            import('@.ORG.comm_page');
            $p = new Page($join_count,10);
            $join_list = D('')->field($field)->table($table)->where($where)->order('`aj`.`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();
            if ($join_list) {
                foreach ($join_list as &$value) {
                    $value['content'] = $value['content'] ? json_decode($value['content']) : $value['content'];
                    $value['add_time'] = time_info($value['add_time']);
                }
            } else {
                $join_list = array();
            }
            $this->returnCode(0, $join_list);
        }
    }

    // 获得活动列表 我创建的
    public function my_activity_list()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_join = D('Community_activity_join');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //群id
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$_POST['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->where(array('community_id'=>$_POST['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $_POST['community_id']));
            }
            $where = array();
            $where['community_id'] = $_POST['community_id'];
            $where['uid'] = $info['uid'];
            $where['is_del'] = 0;

            $activity_count = $database_activity->where($where)->count();
            import('@.ORG.comm_page');
            $p = new Page($activity_count,6);

            $activity_list = $database_activity->where($where)->order('`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();
            if ($activity_list) {
                $now_time = time();
                foreach ($activity_list as &$value) {
                    $value['start_time_format'] = time_info($value['start_time'], '', true);
                    $value['end_time_format'] = date('Y-m-d H:i' , $value['end_time']);
                    $value['deadline_format'] = date('Y-m-d H:i' , $value['deadline']);
                    $value['add_time_format'] = date('Y-m-d H:i' , $value['add_time']);
                    if (intval($value['deadline']) > intval($now_time)) {
                        $value['is_sign_end'] = false;
                    } else {
                        $value['is_sign_end'] = true;
                    }
                    if (intval($value['end_time']) > intval($now_time)) {
                        $value['is_end'] = false;
                    } else {
                        $value['is_end'] = true;
                    }

                    //是否最新发布
                    $value['is_new'] = 0;
                    if (time() - $value['add_time'] < 259200) { //最新发布 三天内
                        $value['is_new'] = 1;
                    }

                    //是否进行中
                    $value['is_going'] = 0;
                    if ($value['start_time'] <= time()) {
                        $value['is_going'] = 1;
                    }

                    // 已报名人数
                    $join_number = $database_activity_join->where(array('activity_id'=>$value['activity_id'],'status'=>array('in',array(1,2))))->count();
                    $value['number'] = $join_number ? $join_number : 0;
                    //活动图片
                    $value['image'] = C('config.site_url') . '/upload/comm/' . $value['image'];
                    //活动excel 文件
                    unset($value['excel_url']);
                }
            } else {
                $activity_list = [];
            }

            // //查询昵称
            // $user = $database_user->field('avatar,nickname')->where('uid='.$activity['uid'])->find();
            // $activity['avatar'] = $user['avatar'] ? $user['avatar'] : '';
            // $activity['nickname'] = $user['nickname'] ? $user['nickname'] : '';


            // 处理一下禁言时间显示
            $excuse_time_info = 0;
            if ($is_group_member['excuse_status'] == 2) {
                $timestamp = intval($is_group_member['excuse_end_time']) - intval(time());
                if ($timestamp > 0) {
                    $excuse_time_info = $this->excuse_time_info($timestamp);
                } else {
                    $excuse_time_info = 0;
                }
            }
            $activity_info = array(
                'list' => $activity_list,
                'excuse_time_info' => $excuse_time_info
            );
            $this->returnCode(0, $activity_info);
        }
    }

    // 获得活动列表 我参与的
    public function join_activity_list()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_user = D('User');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //群id
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$_POST['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->where(array('community_id'=>$_POST['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $_POST['community_id']));
            }

            //报名表查询参与的活动
            $field = array('`a`.*', '`aj`.`add_time` as join_time', '`aj`.`join_id`','`aj`.`uid` as join_uid', '`aj`.`status` as join_status');
            $table = array(C('DB_PREFIX').'community_activity'=>'a',C('DB_PREFIX').'community_activity_join'=>'aj');
            $where['_string'] = '`a`.`activity_id` = `aj`.`activity_id` AND `a`.`is_del`=0 AND `aj`.`uid`='.$info['uid'].' AND `a`.`community_id`='.intval($_POST['community_id']).' AND `aj`.`status` in (1,2,4)';

            import('@.ORG.comm_page');
            $activity_count = D('')->table($table)->where($where)->order('`aj`.`add_time` DESC,`a`.`status` ASC,`a`.`add_time` DESC')->count();
            $p = new Page($activity_count, 6);
            $activity_list = D('')->field($field)->table($table)->where($where)->order('`aj`.`add_time` DESC,`a`.`status` ASC,`a`.`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();

            // var_dump(D('')->getLastSql());
            if ($activity_list) {
                $join_status_arr = array(0 => '报名失败', 1 => '报名成功', 2 => '已完成', 3 => '待付款',4 => '已退款');
                foreach ($activity_list as &$value) {
                    $user = $database_user->where(array('uid' => $value['join_uid']))->find();
                    if ($user) {
                        $value['avatar'] = $user['avatar'];
                    }
                    $value['comm_nickname'] = $is_group_member['comm_nickname'];
                    $value['start_time_format'] = date('Y-m-d H:i' , $value['start_time']);
                    $value['end_time_format'] = date('Y-m-d H:i' , $value['end_time']);
                    $value['deadline_format'] = date('Y-m-d H:i' , $value['deadline']);
                    $value['add_time_format'] = date('Y-m-d H:i' , $value['add_time']);
                    $value['join_status_name'] = $join_status_arr[$value['join_status']];
                    //活动图片
                    $value['image'] = C('config.site_url') . '/upload/comm/' . $value['image'];
                    //活动excel 文件
                    unset($value['excel_url']);
                    // 判断是否群创建者
                    $value['group_owner']  = ($community['group_owner_uid'] == $info['uid']) ? 1 : 0;
                }
            } else {
                $activity_list = [];
            }
            // 处理一下禁言时间显示
            $excuse_time_info = 0;
            if ($is_group_member['excuse_status'] == 2) {
                $timestamp = intval($is_group_member['excuse_end_time']) - intval(time());
                if ($timestamp > 0) {
                    $excuse_time_info = $this->excuse_time_info($timestamp);
                } else {
                    $excuse_time_info = 0;
                }
            }
            $activity_info = array(
                'list' => $activity_list,
                'excuse_time_info' => $excuse_time_info
            );
            $this->returnCode(0, $activity_info);
        }
    }

    // 群活动列表
    public function activity_list()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_join = D('Community_activity_join');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //群id
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$_POST['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->where(array('community_id'=>$_POST['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $_POST['community_id']));
            }
            $where = array();
            $where['community_id'] = $_POST['community_id'];
            $where['is_del'] = 0;
            $where['status'] = array('neq',2);

            $activity_count = $database_activity->where($where)->count();
            import('@.ORG.comm_page');
            $p = new Page($activity_count,6);

            $activity_list = $database_activity->where($where)->order('`status` ASC,`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();
            $now_time = time();
            if ($activity_list) {
                foreach ($activity_list as &$value) {
                    $value['start_time_format'] = time_info($value['start_time'], '', true);
                    $value['end_time_format'] = date('Y-m-d H:i' , $value['end_time']);
                    $value['deadline_format'] = date('Y-m-d H:i' , $value['deadline']);
                    $value['add_time_format'] = date('Y-m-d H:i' , $value['add_time']);
                    if (intval($value['deadline']) > intval($now_time)) {
                        $value['is_sign_end'] = false;
                    } else {
                        $value['is_sign_end'] = true;
                    }
                    if (intval($value['end_time']) > intval($now_time)) {
                        $value['is_end'] = false;
                    } else {
                        $value['is_end'] = true;
                    }

                    //是否最新发布
                    $value['is_new'] = 0;
                    if (time() - $value['add_time'] < 259200) { //最新发布 三天内
                        $value['is_new'] = 1;
                    }

                    //是否进行中
                    $value['is_going'] = 0;
                    if ($value['start_time'] <= time()) {
                        $value['is_going'] = 1;
                    }

                    // 已报名人数
                    $join_number = $database_activity_join->where(array('activity_id'=>$value['activity_id'],'status'=>array('in',array(1,2))))->count();
                    $value['number'] = $join_number ? $join_number : 0;
                    //活动图片
                    $value['image'] = C('config.site_url') . '/upload/comm/' . $value['image'];
                    //活动excel 文件
                    unset($value['excel_url']);

                    // 判断是否创建者
                    $value['is_owner']  = ($value['uid'] == $info['uid']) ? 1 : 0;

                    // 判断是否群创建者
                    $value['group_owner']  = ($community['group_owner_uid'] == $info['uid']) ? 1 : 0;

                    // 参与信息
                    $join_info = $database_activity_join->where(array('activity_id'=>$value['activity_id'],'uid'=>$info['uid'],'status'=>array('in',array(1,2,4))))->find();
                    $value['join_id'] = $join_info ? $join_info['join_id'] : 0;
                    $value['join_status'] = $join_info ? $join_info['status'] : 0;
                }
            } else {
                $activity_list = [];
            }

            // //查询昵称
            // $user = $database_user->field('avatar,nickname')->where('uid='.$activity['uid'])->find();
            // $activity['avatar'] = $user['avatar'] ? $user['avatar'] : '';
            // $activity['nickname'] = $user['nickname'] ? $user['nickname'] : '';

            // 处理一下禁言时间显示
            $excuse_time_info = 0;
            if ($is_group_member['excuse_status'] == 2) {
                $timestamp = intval($is_group_member['excuse_end_time']) - intval(time());
                if ($timestamp > 0) {
                    $excuse_time_info = $this->excuse_time_info($timestamp);
                } else {
                    $excuse_time_info = 0;
                }
            }
            $activity_info = array(
                'list' => $activity_list,
                'excuse_time_info' => $excuse_time_info
            );
            $this->returnCode(0, $activity_info);
        }
    }

    // 取消活动
    public function cancle_activity()
    {
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_join= D('Community_activity_join');
            $database_community_order= D('Community_order');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }
            if ($activity['status'] == 2) { // 已取消
                $this->returnCode('71000034');
            }
            if ($activity['status'] == 3) { // 已结束
                $this->returnCode('71000037');
            }
            // if ($activity['status'] == 3 || $activity['end_time'] < time()) { // 已结束
            //     $this->returnCode('71000037');
            // }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->where(array('community_id'=>$activity['community_id'],'add_uid'=>$uid,'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $activity['community_id']));
            }

            //验证是否是创建者或者群主
            if ($uid != $activity['uid'] && $community['group_owner_uid'] != $uid) {
                $this->returnCode('71000035');
            }

            //改变状态
            $res = $database_activity->where('activity_id='.$_POST['activity_id'])->data(array('status'=>2))->save();

            if ($res) {
                //退还报名费
                if ($activity['fees'] >0 ) {
                    // 查询有效订单
                    $order_list = $database_community_order->where(array('activity_id'=>$activity['activity_id'],'is_del'=>0))->select();
                    if ($order_list) {
                        foreach ($order_list as $key => $value) {
                            if ($value['status'] == 1) { // 已付款 退款
                                // 状态设为已退款
                                $database_activity_join->where(array('order_id'=>$value['order_id']))->data(array('status'=>4))->save();
                                $database_community_order->where(array('order_id'=>$value['order_id']))->data(array('status'=>3))->save();
                                // 余额添加
                                $res3 = D('User')->add_money($value['uid'],$value['money'],'参加群活动【'.$activity['title'].'】报名退款，增加余额');
                            } elseif($value['status'] == 2) { // 待付款 设置失效
                                // 状态设为 订单失效
                                // $database_community_order->where(array('order_id'=>$value['order_id']))->data(array('status'=>0))->save();
                                // 状态设为 报名失败
                                // $database_activity_join->where(array('order_id'=>$value['order_id']))->data(array('status'=>0))->save();
                            }
                        }
                    }
                }
                D('Community_join')->create_comm_plan($activity['community_id'], 'comm_cancel_activity', array(), $activity['activity_id'], $uid);

                $this->returnCode(0,'取消成功');
            } else {
                $this->returnCode(1,array(),'取消失败，请重试');
            }

        }
    }

    // 关闭报名
    public function close_enroll()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }
            if ($activity['status'] == 2) { // 已取消
                $this->returnCode('71000034');
            }
            if ($activity['status'] == 3 || $activity['end_time'] < time()) { // 已结束
                $this->returnCode('71000037');
            }

            if ($activity['enroll_status'] == 2) { // 已关闭
                $this->returnCode('71000038');
            }
            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->where(array('community_id'=>$activity['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $activity['community_id']));
            }

            //验证是否是创建者
            if ($info['uid'] != $activity['uid']) {
                $this->returnCode('71000036');
            }

            //改变状态
            $res = $database_activity->where('activity_id='.$_POST['activity_id'])->data(array('enroll_status'=>2))->save();
            if ($res) {
                $this->returnCode(0,'关闭成功');
            } else {
                $this->returnCode(1,array(),'关闭失败，请重试');
            }

        }
    }

    // 开启报名
    public function open_enroll()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }
            if ($activity['status'] == 2) { // 已取消
                $this->returnCode('71000034');
            }
            if ($activity['status'] == 3 || $activity['end_time'] < time()) { // 已结束
                $this->returnCode('71000037');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->where(array('community_id'=>$activity['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $activity['community_id']));
            }

            //验证是否是创建者
            if ($info['uid'] != $activity['uid']) {
                $this->returnCode('71000036');
            }

            if ($activity['enroll_status'] == 1) { // 已开启
                $this->returnCode('71000053');
            }

            if ($activity['deadline'] < time()) { // 报名截止时间小于当前时间
                $this->returnCode('71000054');
            }

            //改变状态
            $res = $database_activity->where('activity_id='.$_POST['activity_id'])->data(array('enroll_status'=>1))->save();
            if ($res) {
                $this->returnCode(0,'开启成功');
            } else {
                $this->returnCode(1,array(),'开启失败，请重试');
            }
        }
    }

    // 分享活动
    public function activity_share()
    {
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        } else {
            $this->returnCode('20044013');
        }

        //活动id
        if (empty($_POST['activity_id'])) {
            $this->returnCode('71000029');
        }

        //查询活动
        $database_activity = D('Community_activity');
        $activity = $database_activity->field('activity_id,uid,status,title')->where(array('activity_id'=>$_POST['activity_id']))->find();
        if (!$activity) {
            $this->returnCode('71000030');
        }
        if ($activity['is_del'] == 1) { // 已删除
            $this->returnCode('71000033');
        }
        // if ($activity['status'] == 2) { // 已取消
        //     $this->returnCode('71000034');
        // }
        // if ($activity['status'] == 3 || $activity['end_time'] < time()) { // 已结束
        //     $this->returnCode('71000037');
        // }
        //分享次数加1
        $database_activity->where(array('activity_id'=>$_POST['activity_id']))->setInc('share_count',1);

        //查询昵称
        $create_user = D('User')->field('nickname')->where('uid='.$activity['uid'])->find();
        $share_user = D('User')->field('nickname')->where('uid='.$info['uid'])->find();

        if (!empty($activity)) {
            // $info['share_url'] = C('config.site_url') . '/wap.php?g=Wap&c=Library&a=visitor_list_open_time&activity_id=' . $_POST['activity_id'];
            $activity['share_info'] = '您好，'.$create_user['nickname'].'发起了【' . $activity['title'] . '】群活动，欢迎您的参加！';
            $activity['share_user'] = $share_user['nickname'];
        } else {
            $activity = array();
        }
        $this->returnCode(0, $activity);
    }

    //报名管理
    public function join_list(){
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_community_order = D('Community_order');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }
            // if ($activity['status'] == 2) { // 已取消
            //     $this->returnCode('71000034');
            // }
            // if ($activity['status'] == 3 || $activity['end_time'] < time()) { // 已结束
            //     $this->returnCode('71000037');
            // }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->where(array('community_id'=>$activity['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $activity['community_id']));
            }

            $activity['is_owner'] = 0;
            $activity['group_owner'] = 0;
            //验证是否是群创建者
            if ($activity['uid'] == $this->_uid) {
                $activity['is_owner'] = 1;
            }
            if ($community['group_owner_uid'] == $this->_uid) {
                $activity['group_owner'] = 1;
            }

            $where = array();
            $where['_string'] = '`aj`.`activity_id`='.$_POST['activity_id'];
            //验证是否发起者
            if ( !$activity['group_owner'] && !$activity['is_owner'] ) {
                // $this->returnCode('71000049');
                $status = ' AND `aj`.`status` in(1,2)';
            } else {
                $status = ' AND `aj`.`status` in(1,2,4)';
                if ($_POST['status']) {
                    $status = ' AND `aj`.`status` ='.$_POST['status'];
                }
                if ($_POST['phone']) {
                    $where['_string'] .= ' AND `aj`.`phone`='.$_POST['phone'];
                }
                if ($_POST['name']) {
                    $where['_string'] .= ' AND `aj`.`name` like "%'.$_POST['name'].'%"';
                }
            }
            $where['_string'] .= $status;

            $activity['start_time_format'] = time_info($activity['start_time'], '', true);

            //是否进行中
            $activity['is_going'] = 0;
            if ($activity['start_time'] <= time()) {
                $activity['is_going'] = 1;
            }
            //活动图片
            $activity['image'] = C('config.site_url') . '/upload/comm/' . $activity['image'];

            $field = array('`u`.`avatar`', '`u`.`nickname`','`aj`.*');
            $table = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_activity_join'=>'aj');
            $where['_string'] .= ' AND `u`.`uid` = `aj`.`uid`';

            $activity_count = D()->table($table)->field($field)->where($where)->count();
            // var_dump(D()->getLastSql());
            import('@.ORG.comm_page');
            $p = new Page($activity_count,10);

            $activity_list = D()->table($table)->field($field)->where($where)->order('`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();
            if ($activity_list) {
                foreach ($activity_list as &$value) {
                    $value['add_time_format'] = time_info($value['add_time'], '', true);
                    $value['orderid'] = '';
                    if ($value['order_id']) {
                        $order = $database_community_order->where(array('order_id'=>$value['order_id']))->find();
                        $value['orderid'] = $order&&$order['orderid'] ? $order['orderid'] : '' ;
                    }
                }
            } else {
                $activity_list = [];
            }
            $this->returnCode(0, array('count'=>$activity_count,'activity'=>$activity,'lists'=>$activity_list));
        }
    }

    // 查看订单详情
    public function join_order_detail(){
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_community_order= D('Community_order');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //订单id
            if (empty($_POST['order_id'])) {
                $this->returnCode('71000040');
            }

            // 查询有效订单
            $order = $database_community_order->where(array('order_id'=>$_POST['order_id'],'is_del'=>0))->find();
            if (!$order) {
                $this->returnCode('71000041');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$order['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            // $is_group_member = $database_join->where(array('community_id'=>$activity['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            // if (!$is_group_member) {
            //     $this->returnCode('70000009');
            // }

            //验证是否发起者 或者订单所有者
            if ( $activity['uid'] != $info['uid'] && $order['uid'] != $info['uid']) {
                $this->returnCode('71000049');
            }

            //查询昵称 活动所属者
            $user = D('User')->field('avatar,nickname')->where('uid='.$activity['uid'])->find();
            $join_info = $database_join->field('comm_nickname')->where(array('add_uid' => $activity['uid'],'community_id'=>$activity['community_id']))->find();
            $activity['nickname'] = $join_info['comm_nickname'] ? $join_info['comm_nickname'] : '';
            $activity['avatar'] = $user['avatar'] ? $user['avatar'] : '';

            // 时间格式
            $activity['start_time_format'] = date('Y-m-d H:i' , $activity['start_time']);
            $activity['end_time_format'] = date('Y-m-d H:i' , $activity['end_time']);
            $activity['deadline_format'] = date('Y-m-d H:i' , $activity['deadline']);
            $activity['add_time_format'] = date('Y-m-d H:i' , $activity['add_time']);

            // 订单所属者
            $user = D('User')->field('avatar,nickname')->where('uid='.$order['uid'])->find();
            $order['nickname'] = $user['nickname'] ? $user['nickname'] : '';
            $order['avatar'] = $user['avatar'] ? $user['avatar'] : '';
            $order['add_time_format'] = date('Y-m-d H:i' , $order['add_time']);
            $order['activity'] = $activity;
            $this->returnCode(0, $order);
        }
    }


    // 查看报名详情
    public function join_detail(){
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_join = D('community_activity_join');
            $database_community_order= D('Community_order');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //报名id
            if (empty($_POST['join_id'])) {
                $this->returnCode('71000059');
            }

            // 查询报名信息
            $join_info = $database_activity_join->where(array('join_id'=>$_POST['join_id']))->find();
            if (!$join_info) {
                $this->returnCode('71000060');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$join_info['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$join_info['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->where(array('community_id'=>$activity['community_id'],'add_uid'=>$info['uid'],'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $activity['community_id']));
            }

            //验证是否活动发起者 或者所有者
            if ( $activity['uid'] != $info['uid'] && $join_info['uid'] != $info['uid']) {
                $this->returnCode('71000049');
            }

            $activity['is_owner'] = 0;
            $activity['group_owner'] = 0;
            //群创建者
            if ($community['group_owner_uid'] == $info['uid']) {
                $activity['group_owner'] = 1;
            }

            //群活动创建者
            if ($activity['uid'] == $info['uid']) {
                $activity['is_owner'] = 1;
            }

            //查询昵称 活动所属者
            $user = D('User')->field('avatar,nickname')->where('uid='.$activity['uid'])->find();
            $join = $database_join->field('comm_nickname')->where(array('add_uid' => $activity['uid'],'community_id'=>$activity['community_id']))->find();
            $activity['nickname'] = $join['comm_nickname'] ? $join['comm_nickname'] : '';
            $activity['avatar'] = $user['avatar'] ? $user['avatar'] : '';

            // 时间格式
            $activity['start_time'] = date('Y-m-d H:i' , $activity['start_time']);
            $activity['end_time'] = date('Y-m-d H:i' , $activity['end_time']);
            $activity['deadline'] = date('Y-m-d H:i' , $activity['deadline']);
            $activity['add_time'] = date('Y-m-d H:i' , $activity['add_time']);

            // 是否创建人
            $activity['is_owner'] = ($activity['uid']==$info['uid']) ? 1 : 0;

             //活动图片
            if ($activity['image']) {
                $activity['image'] = C('config.site_url') . '/upload/comm/' . $activity['image'];
            }

            // 报名所属者
            $user = D('User')->field('avatar,nickname')->where('uid='.$join_info['uid'])->find();
            $join_info['nickname'] = $user['nickname'] ? $user['nickname'] : '';
            $join_info['avatar'] = $user['avatar'] ? $user['avatar'] : '';
            $join_info['add_time_format'] = date('Y-m-d H:i' , $join_info['add_time']);
            $join_info['content'] = $join_info['content'] ? json_decode($join_info['content']) : '';
            $activity['join_info'] = $join_info;

             // 查询订单
            $activity['order'] = [];
            if ($join_info['order_id']) {
                $activity['order'] = $database_community_order->where(array('order_id'=>$join_info['order_id']))->find();
                $activity['order']['add_time'] = date('Y-m-d H:i',$activity['order']['add_time']);
            }
            $this->returnCode(0, $activity);
        }
    }

    //退款
    public function refund(){
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_join = D('community_activity_join');
            $database_community_order= D('Community_order');
            $database_user = D('User');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }
            if ($activity['status'] == 2) { // 已取消
                $this->returnCode('71000034');
            }
            if ($activity['status'] == 3 ) { // 已验证
                $this->returnCode('71000037');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是创建者
            if ( $activity['uid'] != $info['uid'] ) {
                $this->returnCode('71000039');
            }

            //订单id
            if (empty($_POST['order_id'])) {
                $this->returnCode('71000040');
            }

            // 查询有效订单
            $order = $database_community_order->where(array('activity_id'=>$_POST['activity_id'],'order_id'=>$_POST['order_id'],'is_del'=>0))->find();
            if (!$order) {
                $this->returnCode('71000041');
            }
            if ($order['status'] == 0) { // 订单已失效
                $this->returnCode('71000042');
            }
            if ($order['status'] == 2) { // 订单未付款
                $this->returnCode('71000043');
            }
            if ($order['status'] == 3) { // 订单已退款
                $this->returnCode('71000044');
            }

            //查询是否已完成活动
            $finished = $database_activity_join->where(array('activity_id'=>$_POST['activity_id'],'order_id'=>$_POST['order_id'],'status'=>2))->find();
            if ($finished) { // 活动已完成
                $this->returnCode('71000056');
            }
            D()->startTrans();
            // 状态设为已退款
            $res = $database_community_order->where(array('order_id'=>$_POST['order_id']))->data(array('status'=>3))->save();
            if ($res) {
                $res1 = $database_activity_join->where(array('order_id'=>$_POST['order_id']))->data(array('status'=>4))->save();
                if ($res1) {
                    // 余额添加
                   $res3 = D('User')->add_money($order['uid'],$order['money'],'参加群活动【'.$activity['title'].'】报名退款，增加余额');
                   if ($res3['error_code'] == 0) {
                        D()->commit();
                        $msg = array(
                            'id' => $activity['activity_id'],
                            'community_id' => $activity['community_id'],
                            'order_id' => $_POST['order_id'],
                            'type' => '发布人退款',
                            'reason' => '发布人退款',
                            'money' => $order['money'],
                        );
                        $database_join->send_msg('single_refund', $msg);
                        $this->returnCode(0, '退款成功');
                   } else {
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败，请重试~');
                   }
                } else {
                    D()->rollback();
                    $this->returnCode(1, array(), '操作失败，请重试~');
                }
            } else {
                D()->rollback();
                $this->returnCode(1, array(), '操作失败，请重试~');
            }
        }
    }

    //加入活动 活动报名
    public function join_activity(){
        // 判断用户是否登录
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $data = array();
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_join = D('community_activity_join');
            $database_community_order= D('Community_order');
            $database_activity_option = D('Community_activity_option');
            $database_user = D('User');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }
            $data['activity_id'] = $_POST['activity_id'];

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }
            if ($activity['status'] == 2) { // 已取消
                $this->returnCode('71000034');
            }
            if ($activity['status'] == 3 || $activity['end_time'] < time()) { // 已结束
                $this->returnCode('71000037');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是该群人员
            $is_group_member = $database_join->field('comm_nickname')->where(array('community_id'=>$activity['community_id'],'add_uid'=>$uid,'add_status'=>3))->find();
            if (!$is_group_member) {
                $this->returnCode('70000009', array('community_id' => $activity['community_id']));
            }
            $data['community_id'] = $activity['community_id'];

            // 验证报名是否截止
            if ($activity['enroll_status'] != 1 || $activity['deadline'] < time()) {
                $this->returnCode('71000051'); //报名已结束
            }

            // 验证报名人数是否已满
            $join_number = $database_activity_join->where(array('activity_id'=>$_POST['activity_id'],'status'=>array('in',array(1,2))))->count();
            if ($activity['limit'] > 0 && $join_number>=$activity['limit']) {
                $this->returnCode('71000052'); //报名人数已满
            }

            //姓名
            if (empty($_POST['name'])) {
                $this->returnCode('71000045');
            }
            $data['name'] = $_POST['name'];

            //手机号
            if (empty($_POST['phone'])) {
                $this->returnCode('71000046');
            }
            $data['phone'] = $_POST['phone'];
            $data['uid'] = $uid;
            $data['money'] = $activity['fees'];

            //查询自定义报名项
            $activity_option = $database_activity_option->where(array('community_id'=>$activity['community_id'],'activity_id'=>$activity['activity_id'],'status'=>1))->select();
            if ($activity_option) {
                $option =  $_POST['custom'];
                //测试数据
                // $option = array(
                //     '性别' => '1',
                //     '年龄' => '19',
                // );
                //验证必填项
                foreach ($activity_option as $key => $value) {
                    if ($value['is_required'] == 1 && !$option[$value['title']]) {
                        $this->returnCode(1, array(),$value['title'].'为必填项');
                    }
                }
                $data['content'] = json_encode($option);
            }
            $data['add_time'] = time();

            //验证是否已报名
            $is_jioned = $database_activity_join->where(array('activity_id'=>$data['activity_id'],'uid'=>$uid))->find();
            if ($is_jioned&&$is_jioned['status']==1) { // 已经报名成功
                $this->returnCode('71000047');
            } elseif ($is_jioned&&$is_jioned['status']==3) { // 已报名 未付款
                // $this->returnCode('71000048');
            } elseif ($is_jioned&&$is_jioned['status']==2) { // 已报名 已完成
                $this->returnCode('71000047');
            }
            if ($activity['fees']>0) {
                $user_info = D('User')->get_user($uid);
                D()->startTrans();
                if ($user_info['now_money'] >= $activity['fees']) {
                    // 余额够用，直接扣余额
                    $res1 = D('User')->user_money($uid,$activity['fees'],'参加群活动【'.$activity['title'].'】报名费用，减少余额');
                    if ($res1['error_code']) { // 扣除失败 回滚
                        D()->rollback();
                        $this->returnCode(1, array(), '报名失败，请重试~');
                    } else { // 扣除成功 生成订单
                        // 生成订单
                        $order_data = array(
                            'community_id' => $activity['community_id'],
                            'activity_id' => $data['activity_id'],
                            'uid' => $uid,
                            'money' => $activity['fees'],
                            'status' => 1, // 已付款
                            'type' => 1, // 订单类型:1-活动，2-加群
                            'pay_type' => 3, // 订单类型:1-活动，2-加群
                            'add_time' => time(),
                        );
                        $order_id = $database_community_order->data($order_data)->add();
                        if ($order_id) {
                            $data['status'] = 1; //报名成功
                            //支付成功
                            $data['order_id'] = $order_id;
                            if ($is_jioned) {
                                $join_id = $is_jioned['join_id'];
                                $addInfo = $database_activity_join->where(array('activity_id'=>$data['activity_id'],'uid'=>$uid))->data($data)->save();
                            }else{
                                $addInfo = $database_activity_join->data($data)->add();
                                $join_id = $addInfo;
                            }
                            if ($addInfo) {
                                D()->commit();
                                $msg = array(
                                    'community_id' => $activity['community_id'],
                                    'id' => $activity['activity_id'],
                                    'name' => $data['name'] ? $data['name'] : $is_group_member['comm_nickname'],
                                    'community_name' => $community['community_name'],
                                    'title' => $activity['title'],
                                    'uid' => $activity['uid']
                                );
                                $database_join->send_msg('join_activity', $msg);
                                $this->returnCode(0, array('join_id'=>$join_id));
                            }else{
                                D()->rollback();
                                $this->returnCode(1, array(), '报名失败，请重试');
                            }
                        } else {
                            D()->rollback();
                            $this->returnCode(1, array(), '报名失败，请重试~');
                        }
                    }
                } else { // 余额不够
                    // 调起微信支付 先充值再扣除费用
                    $data['status'] = 3; //未付款
                    if ($is_jioned) {
                        $join_id = $is_jioned['join_id'];
                        $addInfo = $database_activity_join->where(array('activity_id'=>$data['activity_id'],'uid'=>$uid))->data($data)->save();
                    }else{
                        $addInfo = $database_activity_join->data($data)->add();
                        $join_id = $addInfo;
                    }
                    if ($addInfo) {
                        D()->commit();
                        $msg = array(
                            'community_id' => $activity['community_id'],
                            'id' => $activity['activity_id'],
                            'name' => $data['name'] ? $data['name'] : $is_group_member['comm_nickname'],
                            'community_name' => $community['community_name'],
                            'title' => $activity['title'],
                            'uid' => $activity['uid']
                        );
                        $database_join->send_msg('join_activity', $msg);
                        $this->returnCode(0, array('join_id'=>$join_id));
                    }else{
                        D()->rollback();
                        $this->returnCode(1, array(), '报名失败，请重试');
                    }
                }
            } else {
                if ($is_jioned) {
                    $join_id = $is_jioned['join_id'];
                    $addInfo = $database_activity_join->where(array('activity_id'=>$data['activity_id'],'uid'=>$uid))->data($data)->save();
                }else{
                    $addInfo = $database_activity_join->data($data)->add();
                    $join_id = $addInfo;
                }
                if ($addInfo) {
                    $msg = array(
                        'community_id' => $activity['community_id'],
                        'id' => $activity['activity_id'],
                        'name' => $data['name'] ? $data['name'] : $is_group_member['comm_nickname'],
                        'community_name' => $community['community_name'],
                        'title' => $activity['title'],
                        'uid' => $activity['uid']
                    );
                    $database_join->send_msg('join_activity', $msg);
                    $this->returnCode(0, array('join_id'=>$join_id));
                }else{
                    $this->returnCode(1, array(), '报名失败，请重试');
                }
            }
        }
    }

    //完成活动 活动结束之后方可点击完成活动
    public function finish_one(){
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_join = D('community_activity_join');
            $database_community_order= D('Community_order');
            $database_user = D('User');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            // 参与者报名id
            if (empty($_POST['join_id'])) {
                $this->returnCode('71000059');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }
            if ($activity['status'] == 2) { // 已取消
                $this->returnCode('71000034');
            }
            if ($activity['status'] == 3 ) { // 已完成
                $this->returnCode('71000057');
            }
            if ($activity['end_time'] > time()) { // 活动未结束，不可完成
                $this->returnCode('71000058');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是创建者
            if ( $activity['uid'] != $info['uid'] ) {
                $this->returnCode('71000067');
            }

            // 查询报名信息
            $join_info = $database_activity_join->where(array('join_id'=>$_POST['join_id']))->find();


            if (!$join_info) {
                $this->returnCode('71000060');
            }
            if ($join_info['status'] == 2) { // 已完成
                $this->returnCode('71000061');
            }
            if ($join_info['status'] == 4) { // 已退款
                $this->returnCode('71000062');
            }
            D()->startTrans();
            //保存报名状态
            $res = $database_activity_join->where(array('join_id'=>$_POST['join_id']))->data(array('status'=>2))->save();
            if ($res) {
                // 查询已付款订单
                if ($join_info['order_id']) {
                    $order = $database_community_order->where(array('order_id'=>$join_info['order_id'],'status'=>1))->find();
                    if (!$order) {
                        D()->rollback();
                        $this->returnCode(1, array(), '订单信息不存在');
                    }

                    // 收取平台费
                    $money = $order['money'];
                    $res1 = D('User')->add_money($info['uid'],$money,'群活动【'.$activity['title'].'】报名费用，增加余额');
                    if (C('config.community_activity_get_merchant_percent')>0) {
                        $money = $money * C('config.community_activity_get_merchant_percent') *0.01;
                        $use_res = D('User')->user_money($info['uid'],$money,'群活动【'.$activity['title'].'】，平台抽成，减少余额');
                    }
                    if($res1['error_code'] == 0){
                        D()->commit();
                        D('Community_join')->create_comm_plan($activity['community_id'], 'comm_finish_activity', array(), $activity['activity_id'], $_POST['join_id']);
                        $this->returnCode(0, '完成成功');
                    }else{
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败，请重试~');
                    }
                } else {
                    D()->commit();
                    D('Community_join')->create_comm_plan($activity['community_id'], 'comm_finish_activity', array(), $activity['activity_id'], $_POST['join_id']);
                    $this->returnCode(0, '完成成功');
                }
            } else {
                D()->rollback();
                $this->returnCode(1, array(), '操作失败，请重试~');
            }
        }
    }

    //完成活动 活动结束之后方可点击完成活动
    public function finish_all(){
        // 判断用户是否登录
        $ticket = I('ticket', false);
        $DEVICE_ID = I('Device-Id', false);
        if ($ticket) {
            $info = ticket::get($ticket, $DEVICE_ID, true);
            $this->user_session['uid'] = $info['uid'];
        }else{
            $this->returnCode('20044013');
        }

        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_activity_join = D('community_activity_join');
            $database_community_order = D('Community_order');
            $database_user = D('User');
            $database_info = D('Community_info');
            $database_join = D('Community_join');

            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }

            //查询活动
            $activity = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            if ($activity['is_del'] == 1) { // 已删除
                $this->returnCode('71000033');
            }
            if ($activity['status'] == 2) { // 已取消
                $this->returnCode('71000034');
            }
            if ($activity['status'] == 3 ) { // 已完成
                $this->returnCode('71000057');
            }
            if ($activity['end_time'] > time()) { // 活动未结束，不可完成
                $this->returnCode('71000058');
            }

            //验证群状态
            $community = $database_info->where(array('community_id'=>$activity['community_id']))->find();
            if (!$community) {
                $this->returnCode('70000005');
            } elseif ($community['status'] == 3) {
                $this->returnCode('70000008');
            } elseif ($community['status'] == 2) {
                $this->returnCode('70000007');
            }

            //验证是否是创建者
            if ( $activity['uid'] != $info['uid'] ) {
                $this->returnCode('71000067');
            }

            D()->startTrans();
            //修改活动状态
            $res = $database_activity->where(array('activity_id'=>$_POST['activity_id']))->data(array('status'=>3))->save();
            if ($res) {
                // 查询已付款订单
                if ($activity['fees']<=0) { // 免费
                    // 修改参与者状态=2 已完成
                    $res1 = $database_activity_join->where(array('activity_id'=>$_POST['activity_id'],'status'=>1))->data(array('status'=>2))->save();
                    if($res1){
                        D()->commit();
                        D('Community_join')->create_comm_plan($activity['community_id'], 'comm_finish_activity', array(), $activity['activity_id']);
                        $this->returnCode(0, '完成成功');
                    }else{
                        D()->rollback();
                        $this->returnCode(1, array(), '操作失败，请重试~');
                    }

                } else { // 付费
                    // 获取已报名信息
                    $join_list = $database_activity_join->where(array('activity_id'=>$_POST['activity_id'],'status'=>1))->select();
                    $res1 = $database_activity_join->where(array('activity_id'=>$_POST['activity_id'],'status'=>1))->data(array('status'=>2))->save();
                    if ($join_list) {
                        if (!$res1) {
                            D()->rollback();
                            $this->returnCode(1, array(), '操作失败，请重试~');
                        }
                        // 查询担保金
                        $join_order_id = array();
                        foreach ($join_list as $key => $value) {
                            $join_order_id[] = $value['order_id'];
                        }
                        $money = $database_community_order->field('sum(money) as total_money')->where(array('order_id'=>array('in',$join_order_id),'status'=>1))->find();
                        $money = $money['total_money'];
                        if ($money) {
                            // 拿回担保金
                            // 收取平台费
                            $res2 = D('User')->add_money($info['uid'],$money,'群活动【'.$activity['title'].'】报名费用，增加余额');
                            if (C('config.community_activity_get_merchant_percent')>0) {
                                $money = $money * C('config.community_activity_get_merchant_percent') *0.01;
                                $use_res = D('User')->user_money($info['uid'],$money,'群活动【'.$activity['title'].'】，平台抽成，减少余额');
                            }
                            if($res2['error_code'] == 0){
                                D()->commit();
                                D('Community_join')->create_comm_plan($activity['community_id'], 'comm_finish_activity', array(), $activity['activity_id']);
                                $this->returnCode(0, '完成成功');
                            }else{
                                D()->rollback();
                                $this->returnCode(1, array(), '操作失败，请重试~');
                            }
                        } else {
                            D()->commit();
                            D('Community_join')->create_comm_plan($activity['community_id'], 'comm_finish_activity', array(), $activity['activity_id']);
                            $this->returnCode(0, '完成成功');
                        }
                    } else {
                        D()->commit();
                        D('Community_join')->create_comm_plan($activity['community_id'], 'comm_finish_activity', array(), $activity['activity_id']);
                        $this->returnCode(0, '完成成功');
                    }
                }
            } else {
                D()->rollback();
                $this->returnCode(1, array(), '操作失败，请重试~');
            }
        }
    }

    public function clearCache(){
        import('ORG.Util.Dir');
        Dir::delDirnotself('./runtime');
    }


    // 活动导出
    public function activity_export(){
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        if (empty($_POST['activity_id'])) {
            $this->returnCode('71000029');
        }
        $activity_id = $_POST['activity_id'];

        $activity_info = D('Community_activity')->where(array('activity_id' => $activity_id))->find();
        if (empty($activity_info)) {
            $this->returnCode('71000030');
        }
        if ($this->_uid != $activity_info['uid']) {
            $this->returnCode('71000064');
        }
        // 查询一下选项信息
        $activity_option = D('Community_activity_option')->field('title')->where(array('activity_id' => $activity_id, 'status' => 1))->select();
        $where = array(
            'activity_id' => $activity_id,
            'community_id' => $activity_info['community_id'],
            'status' => array('in',array(1,2,4)),
        );
        $activity_join = D('Community_activity_join')->where($where)->order('add_time ASC')->select();
        $join_number = count($activity_join);
        if(count($activity_join) <= 0){
            $this->returnCode('71000063');
        }
        // 全部收款
        $total_money =  D('Community_activity_join')->where($where)->sum('money');
        // 实际收款
        $where['status'] = array('in',array(1,2));
        $real_money =  D('Community_activity_join')->where($where)->sum('money');

        $title_msg = "活动名称： " . $activity_info['title'] . '；共计' . $join_number . '人报名；共收款' . $total_money.'元，退款' . round($total_money-$real_money,2).'元，实收'.$real_money.'元';
//        $title_msg = "活动名称： " . $activity_info['title'] . '；共计 ' . $join_number . '人报名；实收'.$real_money.'元';
        $col_num = count($activity_option) + 5;

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

        $title = $activity_info['title'] . '活动-报名列表';

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $length = ceil(count($activity_join)/1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle($title);
            $objActSheet = $objExcel->getActiveSheet();
            // 添加第一行表头
            $objActSheet->mergeCellsByColumnAndRow('0', '1', '' . $col_num, '1');
            $objActSheet->setCellValueByColumnAndRow('0', '1', $title_msg);
            $objActSheet->setCellValueByColumnAndRow('0', '2', '序号');
            $objActSheet->setCellValueByColumnAndRow('1', '2', '姓名');
            $objActSheet->setCellValueByColumnAndRow('2', '2','手机号');
            $objActSheet->setCellValueByColumnAndRow('3', '2', '报名费用');
            $column = 3;
            if (!empty($activity_option)) {
                foreach ($activity_option as $key => $val) {
                    $column = $key + 4;
                    $col_str = '' . $column;
                    $objActSheet->setCellValueByColumnAndRow($col_str, '2', $val['title']);
                }
            }
            $col_str = '' . ($column + 1);
            $objActSheet->setCellValueByColumnAndRow($col_str, '2', '活动状态');
            $col_str_1 = '' . (intval($col_str) + 1);
            $objActSheet->setCellValueByColumnAndRow($col_str_1, '2', '报名时间');

            $status_info = array(
                0 => '报名失败',
                1 => '报名成功',
                2 => '已完成',
                3 => '待付款',
                4 => '已退款'
            );
            if (!empty($activity_join)) {
                $index = 3;

                $cell_list = range(0,$col_num);
                foreach ($cell_list as $cell) {           
                    $objActSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($cell))->setWidth(20);
                    $objActSheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($cell))->getAlignment()->setWrapText(true);
                }

                foreach ($activity_join as $value) {
                    $objActSheet->setCellValueExplicitByColumnAndRow('0', $index, trim($value['join_id']));
                    $objActSheet->setCellValueExplicitByColumnAndRow('1', $index, trim($value['name']));
                    $objActSheet->setCellValueExplicitByColumnAndRow('2', $index, trim($value['phone']));
                    $objActSheet->setCellValueExplicitByColumnAndRow('3', $index, trim($value['money']));
                    $columns = 3;
                    if (!empty($activity_option)) {
                        $content = !empty($value['content']) ? json_decode($value['content'], true) : '';
                        foreach ($activity_option as $key => $val) {
                            $columns = $key + 4;
                            if (!empty($content) && $content[$val['title']]) {
                                $content_info = $content[$val['title']];
                                if (is_array($content_info)) {
                                    $content_info = implode("/", $content_info);
                                }
                                $content_info = $this->filterEmoji($content_info);
                                $objActSheet->setCellValueExplicitByColumnAndRow('' . $columns, $index, trim($content_info));
                            } else {
                                $objActSheet->setCellValueExplicitByColumnAndRow('' . $columns, $index, '');
                            }
                        }
                    }
                    $objActSheet->setCellValueExplicitByColumnAndRow('' . ($columns + 1), $index, $status_info[$value['status']]);
                    $objActSheet->setCellValueExplicitByColumnAndRow('' . ($columns + 2), $index, date("Y-m-d H:i:s", $value['add_time']));
                    $index++;
                }
            }
        }
        $file_id = sprintf("%09d", $activity_id);
        $rand_num = substr($file_id, 0, 3) . '/' . substr($file_id, 3, 3) . '/' . substr($file_id, 6, 3);
        $title_info = $activity_id . '.xlsx';
        $upload_dir = "upload/comm/activity/{$rand_num}/";
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
        }
        $_savePath = $upload_dir . $title_info;
        ob_end_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objWriter->save($_savePath);

        $excel_url = '/' . $_savePath;
        $msg = D('Community_activity')->where(array('activity_id' => $activity_id))->data(array('excel_url' => $excel_url))->save();
        $this->returnCode(0, array('excel_url'=> C('config.site_url') . $excel_url));
    }

    // 过滤表情
    private function filterEmoji($str) {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }

    // 获得活动Excel文件
    public function activity_excel()
    {
        // 判断用户是否登录
//        if (empty($this->_uid)) {
//            $this->returnCode('20044013');
//        }
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }
            //查询活动
            $activity = $database_activity->field('excel_url, title')->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            $excel_info = array();
            if ($activity['excel_url']) {
                $excel_info['excel_url'] = C('config.site_url') . $activity['excel_url'];
            } else {
                $excel_info['excel_url'] = '';
            }
            $excel_info['is_mail'] = C('config.mail_config_smtp_host') ? true : false;
            // $excel_info['is_mail'] = true;
            $excel_info['title'] = $activity['title'];
            $this->returnCode(0, $excel_info);
        }
    }


    // 发送邮件
    public function activity_mail()
    {
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_activity = D('Community_activity');
            $database_mail = D('Community_mail');
            //活动id
            if (empty($_POST['activity_id'])) {
                $this->returnCode('71000029');
            }
            //邮箱
            if (empty($_POST['mail'])) {
                $this->returnCode('71000065');
            }
            //查询活动
            $activity = $database_activity->field('excel_url, title')->where(array('activity_id'=>$_POST['activity_id']))->find();
            if (!$activity) {
                $this->returnCode('71000030');
            }
            $excel_info = array();
            if ($activity['excel_url']) {
                $excel_info['excel_url'] = C('config.site_url') . $activity['excel_url'];
            } else {
                $this->returnCode('71000066');
            }
            $where = array(
                'activity_id' => $_POST['activity_id'],
                'status' => array('in',array(1,2,4)),
            );
            $join_count = D('Community_activity_join')->where($where)->count();
            $content = '<h2>活动报名数据（共'.$join_count.'人报名）</h2><p><strong>活动主题：</strong>'.$activity['title'].'</p><p><strong>报名名单：</strong></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$excel_info['excel_url'].'</p><p style="color:red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;点击链接下载报名Excel表格汇总</p>';
            $res = $database_mail->send($_POST['mail'],'报名活动数据-小猪社群！',$content);
            if ($res === true ) {
                $this->returnCode(0, $res);
            } else {
                $this->returnCode(1, array(),'发送失败，请重试！');
            }
        }
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


    /**
     * 获取活动二维码
     */
    
    public function small_program_activity(){
        if ($_POST['activity_id']) {
            $activity_id = $_POST['activity_id'];
        }else{
            $this->returnCode(1,'缺少参数');
        }
        if ($_POST['community_id']) {
            $community_id = $_POST['community_id'];
        }else{
            $this->returnCode(1,'缺少参数');
        }
        
        $access_token_array = D('Access_token_wxcapp_expires')->get_access_token();
        if ($access_token_array['errcode']) {
            exit('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
        }
        $qrcode_url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token_array['access_token'];
        $data['scene'] = 'activity_id='.$activity_id.'&community_id='.$community_id;
        $data['page'] = 'pages/activity/activityContent/activityContent';
        $data['width'] = 280;
        $data = json_encode($data);
        $img_content=$this->httpRequest($qrcode_url,$data);
        //图片存放的路径
        $datatime = date('Y-m-d',time());
        $path = "./upload/wx_qrcode/".$datatime."/";
        $uniName = 'wx_'.$activity_id.'.png';
        if(!file_exists($path)){
            mkdir($path,0777,true); //创建目录
            chmod($path,0777); //赋予权限
        }
        if(file_put_contents($path.$uniName, $img_content)){
            $url = C('config.site_url').substr($path.$uniName,1);
            $this->returnCode(0,$url);
        }else{
            $this->returnCode(1,false);
        }
    }

    public static function httpRequest($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty ($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data)
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

}