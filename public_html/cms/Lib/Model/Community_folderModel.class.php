<?php
/**
 * 群文件夹
 * */
class Community_folderModel extends Model
{
    /**
     * 获得文件夹信息
     * @param string $where
     * @param int $pageSize
     * @param int $page
     * @param string $order
     * @return array|bool
     */
    public function folder_info_list($where, $pageSize = 8, $page = 1, $order ='`cf`.`add_time` DESC')
    {
        if(!$where){
            return false;
        }
        // 最基础的搜索条件
        $where_str = '`cf`.`community_id` = `ci`.`community_id` AND `cf`.`folder_uid` = `u`.`uid`';
        $field = array('`cf`.*', '`ci`.`group_owner_uid`', '`u`.`nickname`');
        $table = array(C('DB_PREFIX').'community_folder'=>'cf',C('DB_PREFIX').'community_info'=>'ci',C('DB_PREFIX').'user'=>'u');
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
     * 获取群文件夹和相关的群信息
     * @param $folder_id
     * @return bool|mixed
     */
    public function folder_community_info($folder_id) {
        $folder_info = $this->field(true)->where(array('folder_id' => $folder_id))->find();
        if ($folder_info) {
            $folder_info['community_info'] = M('Community_info')->where(array('community_id' => $folder_info['community_id']))->field('community_id, group_owner_uid, status, add_time')->find();
            return $folder_info;
        } else {
            return false;
        }

    }


    /**
     * 获得文件夹信息-及创建者信息-- 系统后台获取
     * @param $where
     * @param int $firstRow
     * @param int $listRows
     * @return array|bool
     */
    public function folder_list($where, $firstRow = 1, $listRows = 8)
    {
        if(!$where){
            return false;
        }
        $order ='`cf`.`add_time` DESC';
        // 最基础的搜索条件
        $where['_string'] = '`cf`.`folder_uid` = `u`.`uid`';
        $field = array('`cf`.*', '`u`.`nickname`');
        $table = array(C('DB_PREFIX').'community_folder'=>'cf',C('DB_PREFIX').'user'=>'u');
        $list = D('')->field($field)->table($table)->where($where)->order($order)->limit($firstRow.','.$listRows)->select();
        return $list;
    }
}
?>