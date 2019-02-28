<?php
/*
 * 选择城市
 *
 */
class ChangecityAction extends BaseAction{
    public function index(){
        $database_area = D('Area');
        $city_id    =   I('area_name');
        if($city_id){
            $now_city    =   $this->cityMatching($city_id);
        }else{
            $now_city    =   $this->nowCity($this->config['now_city']);
        }
        $arr['now_city'][]  =   isset($now_city)?$now_city:array();

        //得到所有的省份
        $all_province = S('all_province');
        if(empty($all_province)){
            $condition_all_area['area_type'] = 1;
            $condition_all_area['is_open'] = 1;
            $database_field = '`area_id`,`area_name`';
            $all_province = $database_area->field($database_field)->where($condition_all_area)->order('`area_sort` ASC,`area_id` ASC')->select();
            S('all_province',$all_province);
        }
        $arr['all_province']  =   isset($all_province)?$all_province:array();
        //得到推荐的城市
        $hot_city = S('hot_city');
        if(empty($hot_city)){
            $database_field = '`area_name`,`area_url`,`area_id`';
            $condition_tuijian_city['area_type'] = 2;
            $condition_tuijian_city['is_open'] = 1;
            $condition_tuijian_city['is_hot'] = 1;
            $hot_city = $database_area->field($database_field)->where($condition_tuijian_city)->order('`area_id` ASC')->select();
            S('hot_city',$hot_city);
        }else{
            $database_field = '`area_id`';
            foreach($hot_city as $k=>$v){
                $tmpCity = $database_area->field($database_field)->where(array('area_name' => $v['area_name'],'area_type'=>2))->select();
                $hot_city[$k]['area_id'] =  $tmpCity[0]['area_id'];
            }
        }
        $arr['hot_city']  =   isset($hot_city)?$hot_city:array();
        //得到所有城市并以城市首拼排序
        $all_city = S('all_city');
        if(empty($all_city)){
            $database_field = '`area_id`,`area_name`,`area_url`,`first_pinyin`,`is_hot`';
            $condition_all_city['area_type'] = 2;
            $condition_all_city['is_open'] = 1;
            $all_city_old = $database_area->field($database_field)->where($condition_all_city)->order('`first_pinyin` ASC,`area_id` ASC')->select();
            foreach($all_city_old as $key=>$value){
                //首拼转成大写
                if(!empty($value['first_pinyin'])){
                    $first_pinyin = strtoupper($value['first_pinyin']);
                    $all_city[$first_pinyin][] = $value;
                }
            }
            S('all_city',$all_city);
        }

        foreach($all_city as $k=>$v){
            $tmp[]    =   $k;
        }
        $arr['aCity']  =    isset($tmp)?$tmp:array();
        $arr['all_city']  =   isset($all_city)?$all_city:array();
        $this->returnCode(0,$arr);
    }
}