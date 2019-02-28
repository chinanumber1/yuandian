<?php
/**
 * 群相册
 * */
class Community_albumModel extends Model
{
    /**
     * 获得群相册信息
     * @param string $where
     * @param int $pageSize
     * @param int $page
     * @param string $order
     * @return array|bool
     */
    public function album_info_list($where, $pageSize = 8, $page = 1, $order ='`ca`.`add_time` DESC')
    {
        if(!$where){
            return false;
        }
        // 最基础的搜索条件
        $where_str = '`ca`.`community_id` = `ci`.`community_id` AND `ca`.`album_uid` = `u`.`uid`';
        $field = array('`ca`.*', '`ci`.`group_owner_uid`', '`u`.`nickname`');
        $table = array(C('DB_PREFIX').'community_album'=>'ca',C('DB_PREFIX').'community_info'=>'ci',C('DB_PREFIX').'user'=>'u');
        if (empty($where)) {
            $where = $where_str;
        } else {
            $where .= ' AND ' . $where_str;
        }
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部

        if ($pageSize > 0) {
            $total = D('')->table($table)->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->where($where)->order($order)->limit($firstRow.','.$pageSize)->select();
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order($order)->select();
            return [
                'list' => $list
            ];
        }
    }

    /**
     * 获取群相册和相关的群信息
     * @param $album_id
     * @return bool|mixed
     */
    public function album_community_info($album_id) {
        $album_info = $this->field(true)->where(array('album_id' => $album_id))->find();
        if ($album_info) {
            $album_info['community_info'] = M('Community_info')->where(array('community_id' => $album_info['community_id']))->field('community_id, group_owner_uid, status, add_time')->find();
            return $album_info;
        } else {
            return false;
        }

    }


    /**
     * 获得群相册信息-及创建者信息-- 系统后台获取
     * @param $where
     * @param int $firstRow
     * @param int $listRows
     * @return array|bool
     */
    public function album_list($where, $firstRow = 1, $listRows = 8)
    {
        if(!$where){
            return false;
        }
        $order ='`ca`.`add_time` DESC';
        // 最基础的搜索条件
        $where['_string'] = '`ca`.`album_uid` = `u`.`uid`';
        $field = array('`ca`.*', '`u`.`nickname`');
        $table = array(C('DB_PREFIX').'community_album'=>'ca',C('DB_PREFIX').'user'=>'u');
        $list = D('')->field($field)->table($table)->where($where)->order($order)->limit($firstRow.','.$listRows)->select();
        return $list;
    }
}
?>