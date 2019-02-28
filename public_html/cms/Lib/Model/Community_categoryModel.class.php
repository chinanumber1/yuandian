<?php
/**
 * 群分类
 * */
class Community_categoryModel extends Model
{
    /**
     * 获得分类信息列表
     * @param string $where
     * @return array|bool
     */
    public function category_info_list($where){
        if(!$where){
            return false;
        }
        $info = $this->field(true)->where($where)->select();
        return $info;
    }

    /**
     * 查找热门分类
     * @param int $limit
     * @return mixed
     */
    public function hot_category_list($limit = 7){
        // 优先查找热门，如果没有热门，按照排序值和时间查找 (暂时均默认为最多查7个)
        $where = array(
            'is_hot' => 2,
            'cat_status' => 1
        );
        $hot_category = $this->field(true)->where($where)->order('`cat_sort` DESC,`add_time` DESC')->limit($limit)->select();
        // 判断是否存在热门分类
        if (!$hot_category) {
            $where = array(
                'cat_status' => 1
            );
            $hot_category = $this->field(true)->where($where)->order('`cat_sort` DESC,`add_time` DESC')->limit($limit)->select();
        }
        $site_url = C('config.site_url') . '/upload/system/';
        foreach ($hot_category as &$val) {
            if ($val['cat_pic']) $val['cat_pic'] = $site_url . $val['cat_pic'];
            if ($val['cat_select_pic']) $val['cat_select_pic'] = $site_url . $val['cat_select_pic'];
        }
        return $hot_category;
    }

    /**
     * @param $cid int 分类id
     * @param $subdir int 层级
     * @return array
     */
    public function get_Subdirectory($cid, $subdir = 2) {
        $where = array('fid' => $cid, 'subdir' => $subdir, 'cat_status' => 1);
        $subdirectory = $this->field(true)->where($where)->order('`cat_sort` DESC,`cid` SC')->select();
        if (!$subdirectory) {
            $subdirectory = array();
        }

        return $subdirectory;
    }


    /**
     * 获取单条分类信息
     * @param $cid int 分类id
     * @param $field_str string 查询所需的字段
     * @return array
     */
    public function single_category($cid, $field_str='') {
        if ($field_str) {
            $field = $field_str;
        } else {
            $field = true;
        }
        $single_category = $this->field($field)->where(array('cid' => $cid))->find();
        return $single_category;
    }
}
?>