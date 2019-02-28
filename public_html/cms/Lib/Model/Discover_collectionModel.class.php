<?php
/**
 * 发现-信息类
 * */
class Discover_collectionModel extends Model
{
    /**
     * 获取用户是否收藏该信息
     * @param $discover_id
     * @param $collection_uid
     * @return mixed
     */
    public function discover_collection_msg($discover_id, $collection_uid) {
         return $this->where(array('discover_id'=>$discover_id, 'collection_uid' => $collection_uid))->find();
    }

    /**
     * 获得收藏列表
     * @param string $where
     * @param int $pageSize
     * @param int $page
     * @return array|bool
     */
    public function discover_collection_list($where, $pageSize = 8, $page = 1) {
        if(!$where){
            return false;
        }
        // 最基础的搜索条件
        $where_str = '`dm`.`type_id` = `dc`.`type_id` AND `dm`.`discover_uid` = `u`.`uid` AND `dsc`.`discover_id` = `dm`.`discover_id`';
        $field = array('`dsc`.`collection_uid`','`dm`.*', '`dc`.`type_name`','`u`.`nickname`','`u`.`avatar`');
        $table = array(C('DB_PREFIX').'discover_collection'=>'dsc',C('DB_PREFIX').'discover_msg'=>'dm',C('DB_PREFIX').'discover_category'=>'dc',C('DB_PREFIX').'user'=>'u');
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