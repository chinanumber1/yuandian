<?php
/**
 * 发现-信息类
 * */
class Discover_msgModel extends Model
{
    /**
     * 获得发现-信息列表
     * @param string $where
     * @param int $pageSize
     * @param int $page
     * @return array|bool
     */
    public function discover_msg_list($where, $pageSize = 8, $page = 1){
        if(!$where){
            return false;
        }

        // 最基础的搜索条件
        $where_str = '`dm`.`type_id` = `dc`.`type_id` AND `dm`.`discover_uid` = `u`.`uid`';
        $field = array('`dm`.*', '`dc`.`type_name`','`u`.`nickname`','`u`.`avatar`');
        $table = array(C('DB_PREFIX').'discover_msg'=>'dm',C('DB_PREFIX').'discover_category'=>'dc',C('DB_PREFIX').'user'=>'u');
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
            $list = D('')->field($field)->table($table)->where($where)->order('`dm`.`add_time` DESC')->limit($firstRow.','.$pageSize)->select();
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order('`dm`.`add_time` DESC')->select();
            return [
                'list' => $list
            ];
        }
    }

}
?>