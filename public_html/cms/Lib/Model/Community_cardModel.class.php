<?php
/**
 * 群名片表
 * */
class Community_cardModel extends Model
{
    /**
     * 创建名片发消息
     * @param $community_card_id
     * @param $uid
     * @return array
     */
    public function card_info_send($community_card_id, $uid){
        if (!$community_card_id || !$uid) return false;
        $card_info = $this->where(array('community_card_id' => $community_card_id))->field('community_id, card_name, community_card_bgimg')->find();
        if (!$card_info) return false;
        // 创建名片成功， 同步到云通讯
        $des = '创建群名片【' . $card_info['card_name'] . '】';
        if ($card_info['community_card_bgimg']) {
            $community_card_bgimg = C('config.site_url') . $card_info['community_card_bgimg'];
        } else {
            $community_card_bgimg = C('config.site_url') . '/static/community/chat/comm_card.png?t=001';
        }
        $group_id = $card_info['community_id'];
        $msg_body = array();
        $msgType = array();
        $msgType['MsgType'] = 'TIMTextElem';
        $msgType['MsgContent'] = array(
            'Text' => '【￥community_card￥】&' . $group_id . '&' . urlencode($des) . '&' . urlencode($community_card_bgimg)
        );
        $msg_body[] = $msgType;
        $database_info = D('Community_info');
        $ret_group = $database_info->qcloud_send_group_msg($group_id, $msg_body, $uid);
        if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
            return array('erroe_code'=>false);
        } else {
            return array('erroe_code'=>true);
        }
    }
}
?>