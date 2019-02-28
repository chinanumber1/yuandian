<?php
class House_village_print_templateModel extends Model{
	/*得到打印模板列表*/
	public function get_limit_list_page($column,$pageSize=20,$isSystem=false){
		if(!$column['village_id']){
			return null;
		}
    	
    	$order = '`template_id` DESC';
    	if($isSystem){
    		import('@.ORG.system_page');
    	}else{
    		import('@.ORG.merchant_page');
    	}
    	$count_order = $this->where($column)->count();
    	$p = new Page($count_order,$pageSize,'page');
    	$list = $this->where($column)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
    	
    	$return['pagebar'] = $p->show();
    	$return['list'] = $list;
    	return $return;
	}

	public function get_one($template_id){
		if(!$template_id){
			return false;
		}
        $print_template = $this->where(array('template_id'=>$template_id))->find();
        $condition_table  = array(C('DB_PREFIX').'house_village_print_custom'=>'c',C('DB_PREFIX').'house_village_print_custom_configure'=>'b');
        $condition_where = " `c`.`configure_id` = `b`.`configure_id`  AND `c`.`template_id` =".$template_id;
        
        $condition_field = 'c.*,b.*';
        $order = ' `b`.`weight` DESC,`c`.`id` ASC';
        $custom_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->select();
        $print_template['custom'] = $custom_list ? $custom_list : [];

		return $print_template;
	}

    public function get_select($where){
        $print_template = D('House_village_print_template')->where($where)->select();
        if($print_template){
            return $print_template;
        }else{
            return false;
        }
    }
	
}