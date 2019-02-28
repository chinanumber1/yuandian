<?php
/**
 * 群与分类
 * */
class Community_bind_categoryModel extends Model
{
    /**
     * 获得分类-群信息列表
     * @param string $where
     * @param int $pageSize
     * @param int $page
     * @param int $uid
     * @return array|bool
     */
    public function category_community_info_list($where, $pageSize = 8, $page = 1, $uid = 0){
        if(!$where){
            return false;
        }
        // 最基础的搜索条件
        $where_str = '`cbd`.`community_id` = `ci`.`community_id` AND `ci`.`status` = 1 AND `ci`.`group_mode` = 2';
        $field = array('`cbd`.`cid`', '`ci`.`community_id`', '`ci`.`community_name`', '`ci`.`group_owner_uid`', '`ci`.`member_number`', '`ci`.`avatar`', '`ci`.`community_avatar`', '`ci`.`community_des`');
        $table = array(C('DB_PREFIX').'community_bind_category'=>'cbd',C('DB_PREFIX').'community_info'=>'ci');
        if (empty($where)) {
            $where = $where_str;
        } else {
            $where .= ' AND ' . $where_str;
        }
        // 过滤用户加入过的群
        $join_community_id_arr = array();
        if ($uid)  {
            $join_community_id = M('community_join')->field('community_id')->where(array('add_uid' => $uid, 'add_status' => array('in', array(2,3))))->select();
            if ($join_community_id) {
                foreach ($join_community_id as $val) {
                    $join_community_id_arr[] = intval($val['community_id']);
                }
            }
        }
        if ($join_community_id_arr) {
            $join_community_id_str = implode(',', $join_community_id_arr);
            $where .= ' AND `ci`.`community_id` NOT IN (' . $join_community_id_str . ')';
        }
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total = D('')->table($table)->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->where($where)->order('`cbd`.`add_time` DESC')->limit($firstRow.','.$pageSize)->select();
            if (!$list) {
                $list = array();
            }
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order('`cbd`.`add_time` DESC')->select();
            if (!$list) {
                $list = array();
            }
            return [
                'list' => $list
            ];
        }
    }



    /**
     * 获取群的分类标签
     * @param $community_id
     * @return array
     */
    public function community_category_info($community_id) {
        $where = '`cbd`.`cid` = `cc`.`cid` AND `cbd`.`community_id` =' . $community_id;
        $field = array('`cbd`.`bind_id`','`cbd`.`cid`', '`cc`.*');
        $table = array(C('DB_PREFIX').'community_bind_category'=>'cbd',C('DB_PREFIX').'community_category'=>'cc');
        $category_list = D('')->field($field)->table($table)->where($where)->order('`cc`.`add_time` DESC')->select();
        if ($category_list) {
            $community_category = D('Community_category');
            $site_url = C('config.site_url') . '/upload/system/';
            foreach ($category_list as &$val) {
                if (intval($val['fid']) > 0) {
                    $f_info = $community_category->single_category($val['fid'], 'cid, cat_name');
                    $val['f_info'] = $f_info;
                    $val['cat_name_total'] = $f_info['cat_name'] . '-' . $val['cat_name'];
                    if ($val['cat_pic']) $val['cat_pic'] = $site_url . $val['cat_pic'];
                    if ($val['cat_select_pic']) $val['cat_select_pic'] = $site_url . $val['cat_select_pic'];
                }
            }
        } else {
            $category_list = array();
        }
        // 查询一下父分类
        return $category_list;
    }


    /**
     * 获取群的分类标签数量
     * @param $community_id
     * @return array
     */
    public function community_category_num($community_id) {
        $where = array('community_id' => $community_id);
        $category_count = $this->where($where)->count();
        return $category_count;
    }


    /**
     * 获取关联信息
     * @param $bind_id
     * @param string $field
     * @param string $field_info
     * @return mixed
     */
    public function get($bind_id, $field='bind_id', $field_info='bind_id')
    {
        $condition_info[$field] = $bind_id;
        $info = $this->field($field_info)->where($condition_info)->find();
        return $info;
    }
    /**
     * 删除关联信息
     * @param $bind_id
     * @param string $field
     * @return mixed
     */
    public function del($bind_id,$field='bind_id')
    {
        $condition_info[$field] = $bind_id;
        $info = $this->field(true)->where($condition_info)->delete();
        return $info;
    }
}
?>