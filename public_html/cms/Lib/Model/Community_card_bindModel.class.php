<?php
/**
 * 群名片加入成员信息表
 * */
class Community_card_bindModel extends Model
{


    /**
     * 获得群名片加入者信息
     * @param string $where
     * @param int $pageSize
     * @param int $page
     * @return array|bool
     */
    public function join_card_list($where, $pageSize = 8, $page = 1)
    {
        if(!$where){
            return false;
        }
        $order = '`join_time` DESC';
        // 最基础的搜索条件
        $where['_string'] = '`cab`.`join_uid` = `u`.`uid`';
        if ($where['nickname']) {
            $where['_string'] = '`cab`.`join_uid` = `u`.`uid` AND (`cab`.`nickname` LIKE "' . $where['nickname'] . '" OR `u`.`nickname` LIKE "' . $where['nickname'] . '")';
            unset($where['nickname']);
        }
        $field = array('`cab`.*', '`u`.`nickname` as user_nickname', '`u`.`avatar` as user_avatar');
        $table = array(C('DB_PREFIX').'community_card_bind'=>'cab',C('DB_PREFIX').'user'=>'u');
        if ($pageSize > 0) {

            $total = D('')->field($field)->table($table)->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->where($where)->order($order)->limit($firstRow.','.$pageSize)->select();
            if ($list) {
                $site = C('config.site_url');
                foreach ($list as &$val) {
                    if ($val['join_avatar'])  $val['join_avatar'] = $site . $val['join_avatar'];
                    if ($val['join_bgimg'])  $val['join_bgimg'] = $site . $val['join_bgimg'];
                    $user_card = M('Community_card_bind')->where(array('community_id'=>$val['community_id'],'join_uid'=>$val['join_uid']))->find();
                    if($user_card){
                        $val['has_card'] = true;
                    }else{
                        $val['has_card'] = false;
                    }
                    $praise = M('Community_card_praise')->where(array('join_id'=>$val['join_id']))->count();
                    if($praise){
                        $val['praise_num'] = $praise;
                    }else{
                        $val['praise_num'] = 0;
                    }
                    
                }
            }
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order($order)->select();
            if ($list) {
                $site = C('config.site_url');
                foreach ($list as &$val) {
                    if ($val['join_avatar'])  $val['join_avatar'] = $site . $val['join_avatar'];
                    if ($val['join_bgimg'])  $val['join_bgimg'] = $site . $val['join_bgimg'];
                }
            }
            return [
                'list' => $list
            ];
        }
    }


    /**
     * 处理数目问题
     * @param $where
     * @param string $m_table
     * @param string $field
     * @return array|bool
     */
    public function change_num($where, $m_table = 'Community_card', $field = 'join_num') {
        $count = $this->where($where)->count();
        $single = M($m_table)->field($field)->where($where)->find();
        if (intval($single[$field]) != $count) {
            M($m_table)->where($where)->data(array($field => $count))->save();
        }
        return $count;
    }




    /**
     * 获得群相册信息-及创建者信息-- 系统后台获取
     * @param $where
     * @param int $firstRow
     * @param int $listRows
     * @return array|bool
     */
    public function join_card($where, $firstRow = 1, $listRows = 8)
    {
        if(!$where){
            return false;
        }
        $order ='`cab`.`join_time` DESC';
        // 最基础的搜索条件
        $where['_string'] = '`cab`.`join_uid` = `u`.`uid`';
        if ($where['nickname']) {
            $where['_string'] = '`cab`.`join_uid` = `u`.`uid` AND `cab`.`nickname` LIKE "' . $where['nickname'] . '"';
            unset($where['nickname']);
        }
        $field = array('`cab`.*', '`u`.`nickname` as user_nickname');
        $table = array(C('DB_PREFIX').'community_card_bind'=>'cab',C('DB_PREFIX').'user'=>'u');
        $list = D('')->field($field)->table($table)->where($where)->order($order)->limit($firstRow.','.$listRows)->select();
        return $list;
    }
}
?>