<?php
/**
 * 社群应用拓展管控表
 * */
class Community_application_developmentModel extends Model
{

    /**
     * 获得-社群应用拓展管控信息及群状态
     * @param $community_id
     * @return array
     */
    public function community_application_development($community_id) {

        $where = '`cad`.`community_id` = `ci`.`community_id` AND `cad`.`community_id` =' . $community_id;
        $field = array('`ci`.`status`','`ci`.`group_owner_uid`','`ci`.`community_name`', '`cad`.*');
        $table = array(C('DB_PREFIX').'community_application_development'=>'cad',C('DB_PREFIX').'community_info'=>'ci');
        $development_info = D('')->field($field)->table($table)->where($where)->order('`cad`.`add_time` DESC')->find();
        if (!$development_info) {
            $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('status, group_owner_uid, community_name')->find();
            if ($community_info) {
                $development_info = $this->add_info($community_id);
                $development_info['status'] = $community_info['status'];
                $development_info['group_owner_uid'] = $community_info['group_owner_uid'];
                $development_info['community_name'] = $community_info['community_name'];
            } else {
                $development_info = array();
            }
        }
        return $development_info;
    }

    /**
     * 添加
     * @param $community_id
     * @return array
     */
    public function add_info($community_id) {
        $add_info = array(
            'community_id' => $community_id,
            'is_community_vote' => 1,
            'is_community_notice' => 1,
            'is_community_activity' => 1,
            'is_community_shop' => 1,
            'is_community_dynamic' => 1,
            'is_community_topic' => 1,
            'is_community_album' => 1,
            'is_community_file' => 1,
            'is_community_card' => 1,
            'is_add_folder' => 2,
            'is_add_album' => 2,
            'add_time' => time(),
            'order_vote' => 0,
            'order_notice' => 1,
            'order_activity' => 2,
            'order_shop' => 3,
            'order_dynamic' => 4,
            'order_topic' => 5,
            'order_album' => 6,
            'order_file' => 7,
            'order_card' => 8
        );
        $this->data($add_info)->add();
        return $add_info;
    }


    /**
     * 获取对应的全部群应用
     * @param $community_id
     * @return array
     */
    public function application_control($community_id) {
        // 排序的字段
        $order_info = array(
            'order_vote', 'order_notice', 'order_activity', 'order_shop', 'order_dynamic', 'order_topic', 'order_album', 'order_file', 'order_card'
        );
        $site_url = C('config.site_url') ;
        $application_info = $this->field(true)->where(array('community_id' => $community_id))->find();
        if (!$application_info) {
            $application_info = $this->add_info($community_id);
        }
        $order_title = array(
            'order_vote' => array
            (
                'title' => '群投票',
                'icon' => $site_url . '/static/community/application/app_vote_all.png',
                'order_num' => $application_info['order_vote'],
                'is_show' => $application_info['is_community_vote'],
                'show_word' => 'is_community_vote',
                'order_word' => 'order_vote',
                'tip' => '轻松发布群投票，发布后消息提醒群成员参与。'
            ),
            'order_notice' => array(
                'title' => '群公告 ',
                'icon' => $site_url . '/static/community/application/app_notice_all.png',
                'order_num' => $application_info['order_notice'],
                'is_show' => $application_info['is_community_notice'],
                'show_word' => 'is_community_notice',
                'order_word' => 'order_notice',
                'tip' => '群中一键发布公告，轻松查看群员阅读情况。'
            ),
            'order_activity' => array(
                'title' => '群活动',
                'icon' => $site_url . '/static/community/application/app_activity_all.png',
                'order_num' => $application_info['order_activity'],
                'is_show' => $application_info['is_community_activity'],
                'show_word' => 'is_community_activity',
                'order_word' => 'order_activity',
                'tip' => '活动宣传报名工具，支持导出群员Excel报名信息。'
            ),
            'order_shop' => array(
                'title' => '群商城',
                'icon' => $site_url . '/static/community/application/app_mall_all.png',
                'order_num' => $application_info['order_shop'],
                'is_show' => $application_info['is_community_shop'],
                'show_word' => 'is_community_shop',
                'order_word' => 'order_shop',
                'tip' => '绑定个人商城店铺ID，轻松玩转群营销。'
            ),
            'order_dynamic' => array(
                'title' => '群动态',
                'icon' => $site_url . '/static/community/application/app_dynamic_all.png',
                'order_num' => $application_info['order_dynamic'],
                'is_show' => $application_info['is_community_dynamic'],
                'show_word' => 'is_community_dynamic',
                'order_word' => 'order_dynamic',
                'tip' => '与群员一起享受每一天的动态，记录感动。'
            ),
            'order_topic' => array(
                'title' => '群话题',
                'icon' => $site_url . '/static/community/application/app_topic_all.png',
                'order_num' => $application_info['order_topic'],
                'is_show' => $application_info['is_community_topic'],
                'show_word' => 'is_community_topic',
                'order_word' => 'order_topic',
                'tip' => '共同讨论，建立共识。'
            ),
            'order_album' => array(
                'title' => '群相册',
                'icon' => $site_url . '/static/community/application/app_album_all.png',
                'order_num' => $application_info['order_album'],
                'is_show' => $application_info['is_community_album'],
                'show_word' => 'is_community_album',
                'order_word' => 'order_album',
                'tip' => '分享感动瞬间，留住最美画面。'
            ),
            'order_file' => array(
                'title' => '群文件',
                'icon' => $site_url . '/static/community/application/app_file_all.png',
                'order_num' => $application_info['order_file'],
                'is_show' => $application_info['is_community_file'],
                'show_word' => 'is_community_file',
                'order_word' => 'order_file',
                'tip' => '免费的资源共享空间，随时查看，随时分享。'
            ),
            'order_card' => array(
                'title' => '群名片',
                'icon' => $site_url . '/static/community/application/app_card_all.png',
                'order_num' => $application_info['order_card'],
                'is_show' => $application_info['is_community_card'],
                'show_word' => 'is_community_card',
                'order_word' => 'order_card',
                'tip' => '可快速创建该群专用名片夹，收集群员名片信息从此轻松简单。'
            )
        );
        $info = array();
        // 按照顺序填写可以显示的应用
        foreach ($application_info as $key=>$val) {
            if (in_array($key, $order_info)) {
                $info[$val] = $order_title[$key];
            }
        }
        ksort($info);
        $msg = array();
        // 重新赋予新的数组
        foreach ($info as $val) {
            $msg[] = $val;
        }
        return $msg;
    }

    /**
     * 获取对应的群应用
     * @param $community_id
     * @param string $get_info  index 聊天页获取的数量(4) more 群主页获取的数量(7) all 群主页点击获取的所有
     * @return array
     */
    public function application_info($community_id, $get_info = 'index') {
        $order_control = array(
            'order_vote' => 'is_community_vote',
            'order_notice' => 'is_community_notice',
            'order_activity' => 'is_community_activity',
            'order_shop' => 'is_community_shop',
            'order_dynamic' => 'is_community_dynamic',
            'order_topic' => 'is_community_topic',
            'order_album' => 'is_community_album',
            'order_file' => 'is_community_file',
            'order_card' => 'is_community_card'
        );
        $site_url = C('config.site_url') ;
        $bind_shop = M('Community_bind_shop')->field('store_id')->where(array('community_id' => $community_id))->find();
        if ($bind_shop) {
            $bind_shop_url = $site_url . '/wap.php?g=Wap&c=Mall&a=store&store_id=' . $bind_shop['store_id'];
        } else {
            $bind_shop_url = '';
        }
        $application_info = $this->field(true)->where(array('community_id' => $community_id))->find();
        if (!$application_info) {
            $application_info = $this->add_info($community_id);
        }
        if ($get_info == 'all') {
            $order_vote_img = $site_url. '/static/community/application/app_vote_all.png';
            $order_notice_img = $site_url. '/static/community/application/app_notice_all.png';
            $order_activity_img = $site_url. '//static/community/application/app_activity_all.png';
            $order_shop_img = $site_url. '/static/community/application/app_mall_all.png';
            $order_dynamic_img = $site_url. '/static/community/application/app_dynamic_all.png';
            $order_topic_img = $site_url. '/static/community/application/app_topic_all.png';
            $order_album_img = $site_url. '/static/community/application/app_album_all.png';
            $order_file_img = $site_url. '/static/community/application/app_file_all.png';
            $order_card_img = $site_url. '/static/community/application/app_card_all.png';
        } else {
            $order_vote_img = $site_url. '/static/community/application/app_vote.png?t=001';
            $order_notice_img = $site_url. '/static/community/application/app_notice.png?t=001';
            $order_activity_img = $site_url. '/static/community/application/app_activity.png?t=001';
            $order_shop_img = $site_url. '/static/community/application/app_mall.png?t=001';
            $order_dynamic_img = $site_url. '/static/community/application/app_dynamic.png?t=001';
            $order_topic_img = $site_url. '/static/community/application/app_topic.png?t=001';
            $order_album_img = $site_url. '/static/community/application/app_album.png?t=001';
            $order_file_img = $site_url. '/static/community/application/app_file.png?t=001';
            $order_card_img = $site_url. '/static/community/application/app_card.png?t=001';
        }
        $order_title = array(
            'order_vote' => array(
                'title' => '群投票',
                'url' => '/pages/activity/groupVote/groupVote?community_id='.$community_id,
                'icon' => $order_vote_img,
                'order_num' => $application_info['order_vote'],
                'order_word' => 'order_vote'
            ),
            'order_notice' => array(
                'title' => '群公告 ',
                'url' => '/pages/activity/groupNotice/groupNotice?community_id='.$community_id,
                'icon' => $order_notice_img,
                'order_num' => $application_info['order_notice'],
                'order_word' => 'order_notice'
            ),
            'order_activity' => array(
                'title' => '群活动',
                'url' => '/pages/activity/activityList/activityList?community_id='.$community_id,
                'icon' => $order_activity_img,
                'order_num' => $application_info['order_activity'],
                'order_word' => 'order_activity'
            ),
            'order_shop' => array(
                'title' => '群商城',
                'url' => $bind_shop_url,
                'icon' => $order_shop_img,
                'order_num' => $application_info['order_shop'],
                'order_word' => 'order_shop'
            ),
            'order_dynamic' => array(
                'title' => '群动态',
                'url' => '/pages/activity/groupDynamic/groupDynamic?community_id='.$community_id,
                'icon' => $order_dynamic_img,
                'order_num' => $application_info['order_dynamic'],
                'order_word' => 'order_dynamic'
            ),
            'order_topic' => array(
                'title' => '群话题',
                'url' => '/pages/activity/groupSubject/groupSubject?community_id='.$community_id,
                'icon' => $order_topic_img,
                'order_num' => $application_info['order_topic'],
                'order_word' => 'order_topic'
            ),
            'order_album' => array(
                'title' => '群相册',
                'url' => '/pages/album/index/index?community_id='.$community_id,
                'icon' => $order_album_img,
                'order_num' => $application_info['order_album'],
                'order_word' => 'order_album'
            ),
            'order_file' => array(
                'title' => '群文件',
                'url' => '/pages/files/index/index?community_id='.$community_id,
                'icon' => $order_file_img,
                'order_num' => $application_info['order_file'],
                'order_word' => 'order_file'
            ),
            'order_card' => array(
                'title' => '群名片',
                'url' => '/pages/activity/groupCard/groupCard?community_id='.$community_id,
                'icon' => $order_card_img,
                'order_num' => $application_info['order_card'],
                'order_word' => 'order_card'
            )
        );
        // 判断一下获取的数量
        $num = '';
        if ($get_info == 'index') {
            $num = 4;
        } elseif ($get_info == 'more') {
            $num = 7;
        }
        $info = array();
        // 处理一下群商城显示问题
        // 总开关 是否开启群绑定商城（0开启 1关闭）
        $system_community_shop_switch = C('config.community_shop_switch');
        // 查询一下群商城
        $community_info = M('Community_info')->field('group_owner_uid, community_shop_switch')->where(array('community_id' => $community_id))->find();
        // 群开关 是否开启群绑定商城（0开启 1关闭）
        $community_shop_switch = $community_info['community_shop_switch'];
        if (!empty($system_community_shop_switch) && $system_community_shop_switch == 1) {
            $is_shop = false;
        } else {
            if (!empty($community_shop_switch) && $community_shop_switch == 1) {
                $is_shop = false;
            } else {
                $is_shop = true;
            }
        }
        // 排序的字段
        if (!$is_shop || !$bind_shop_url || $bind_shop_url == '') {
            $order_info = array(
                'order_vote', 'order_notice', 'order_activity', 'order_dynamic', 'order_topic', 'order_album', 'order_file', 'order_card'
            );
        } else {
            $order_info = array(
                'order_vote', 'order_notice', 'order_activity', 'order_shop', 'order_dynamic', 'order_topic', 'order_album', 'order_file', 'order_card'
            );
        }
        // 按照顺序填写可以显示的应用
        foreach ($application_info as $key=>$val) {
            if (in_array($key, $order_info)) {
                if ($application_info[$order_control[$key]] == 1) {
                    $info[$val] = $order_title[$key];
                }
            }
        }
        ksort($info);
        $msg = array();
        // 重新赋予新的数组
        foreach ($info as $val) {
            $msg[] = $val;
        }
        if ($msg && $num) {
            $msg = array_slice($msg,0,$num,true);
        }
        return $msg;
    }


    /***
     * 对应的群应用更换排序
     * @param $community_id
     * @param string $order_word
     * @param $order_num
     * @return bool
     */
    public function application_change_sort($community_id, $order_word = '', $order_num) {
        // 排序的字段
        $order_info = array(
            'order_vote', 'order_notice', 'order_activity', 'order_shop', 'order_dynamic', 'order_topic', 'order_album', 'order_file', 'order_card'
        );
        $data = array();
        if (in_array($order_word, $order_info)) {
            $application_info = $this->field(true)->where(array('community_id' => $community_id))->find();
            if (!$application_info) {
                $application_info = $this->add_info($community_id);
            }
            $order_num = intval($order_num);
            if ($order_num >= 0 && $order_num < 9) {
                $data[$order_word] = $order_num;
                foreach ($order_info as $val) {
                    if (intval($application_info[$order_word]) > $order_num) {
                        if ($val != $order_word && intval($application_info[$val]) >= $order_num && intval($application_info[$val]) < intval($application_info[$order_word])) {
                            $data[$val] = intval($application_info[$val]) + 1;
                        }
                    } else {
                        if ($val != $order_word && intval($application_info[$val]) > intval($application_info[$order_word]) && intval($application_info[$val]) <= $order_num) {
                            $data[$val] = intval($application_info[$val]) - 1;
                        }
                    }
                }
            }
        }
        $application_change = true;
        if ($data) {
            $application_change = $this->where(array('community_id' => $community_id))->data($data)->save();
        }
        return $application_change;
    }
}
?>