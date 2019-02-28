<?php
class AdverModel extends Model
{
    /* 通过分类的KEY得到广告列表 */
    public function get_adver_by_key($cat_key, $limit = 3)
    {
        $now_city = C('config.now_city');
        
        $adver_list = S('adver_list_' . $cat_key . $now_city);
        if (! empty($adver_list)) {
            return $adver_list;
        }
        $database_adver_category = D('Adver_category');
        $condition_adver_category['cat_key'] = $cat_key;
        $now_adver_category = $database_adver_category->field('`cat_id`')->where($condition_adver_category)->find();
        if ($now_adver_category) {
            $condition_adver['cat_id'] = $now_adver_category['cat_id'];
            $condition_adver['status'] = '1';
            $condition_adver['city_id'] = $now_city;
            $adver_list = $this->field(true)->where($condition_adver)->order('`complete` DESC,`sort` DESC,`id` DESC')->limit($limit)->select();
            $img_count = count($adver_list);
            $enough = $limit - $img_count;
            if (empty($adver_list)) {
                $condition_adver['city_id'] = 0;
                $adver_list = $this->field(true)->where($condition_adver)->order('`sort` DESC,`id` DESC')->limit($limit)->select();
            } elseif ($enough > 0 && $adver_list[0]['complete'] == 1) {
                $condition_adver['city_id'] = 0;
                $complete = $this->field(true)->where($condition_adver)->order('`sort` DESC,`id` DESC')->limit($enough)->select();
                if ($complete) {
                    if ($adver_list) {
                        $adver_list = array_merge_recursive($adver_list, $complete);
                    } else {
                        $adver_list = $complete;
                    }
                }
            }
            foreach ($adver_list as $key => $value) {
                $adver_list[$key]['pic'] = C('config.site_url') . '/upload/adver/' . $value['pic'];
                $adver_list[$key]['img_count'] = $img_count;
            }
            // web版导航多城市
            if (C('config.many_city')) {
                foreach ($adver_list as $key => $value) {
                    if (substr($value['url'], - 6) == 'nocity') {
                        $adver_list[$key]['url'] = substr($value['url'], 0, strlen($value['url']) - 6);
                    } else if (strpos($value['url'],'/wap.php') >= 0) {
                        $adver_list[$key]['url'] = $value['url'];
                    } else {
                        $adver_list[$key]['url'] = str_replace(C('config.config_site_url'), C('config.now_site_url'), $value['url']);
                    }
                }
            }
			if(!$adver_list){
				$adver_list = array();
			}
            S('adver_list_' . $cat_key . C('config.now_city'), $adver_list);
            return $adver_list;
        } else {
            return array();
        }
    }
    public function get_one_adver($cat_key)
    {
        $adver_list = $this->get_adver_by_key($cat_key, 1);
        if ($adver_list) {
            return $adver_list[0];
        } else {
            return false;
        }
    }
}
?>