<?php
class Appoint_categoryModel extends Model{
    /*得到分类*/
	public function get_category($get_all=true,$cat_fid=0,$limit_num=0,$category_limit=1){
		if(empty($limit_num)){
			$limit_num = '';
		}
		if($get_all){
			$condition_group_category['cat_status'] = '1';
			$tmp_group_category = $this->field('pc_content,wap_content',true)->where($condition_group_category)->order('`cat_sort` DESC')->limit($limit_num)->select();
			$group_category = array();
			$tmp_category = array();
			foreach($tmp_group_category as $key=>$value){
				if(empty($value['cat_fid'])){
					$tmp_category[$value['cat_id']] = $key;
					
					$value['cat_count'] = 0;
					$value['url'] = $this->get_f_category_url($value);
					if(!empty($value['cat_pic'])){
						$value['cat_pic'] = C('config.site_url').'/upload/system/'.$value['cat_pic'];
					}
					$group_category[$key] = $value;
					unset($tmp_group_category[$key]);
				}
			}
			foreach($tmp_group_category as $key=>$value){
				
				if(!empty($value['cat_pic'])){
					$value['cat_pic'] = C('config.site_url').'/upload/system/'.$value['cat_pic'];
				}
				if(!empty($value['cat_big_pic'])){
					$value['cat_big_pic'] = C('config.site_url').'/upload/system/'.$value['cat_big_pic'];
				}
				$value['url'] = $this->get_category_url($value);
				if (isset($group_category[$tmp_category[$value['cat_fid']]])) {
				    $group_category[$tmp_category[$value['cat_fid']]]['cat_count'] += 1;
				    $group_category[$tmp_category[$value['cat_fid']]]['category_list'][$key] = $value;
				}
			}
			$web_category_show_limit = C('config.web_category_show_limit');
			$web_category_show_limit = 0;
			if($web_category_show_limit && $category_limit){
				$new_group_category = array();
				$i = 1;
				foreach($group_category as $key=>$value){
					if($i > $web_category_show_limit){
						break;
					}else{
						$new_group_category[] = $value;
					}
					$i++;
				}
				$group_category = $new_group_category;
			}			
			return $group_category;
		}else{
			$condition_group_category['cat_status'] = '1';
			$condition_group_category['cat_fid'] = $cat_fid;
			$tmp_group_category = $this->field(true)->where($condition_group_category)->order('`cat_sort` DESC')->limit($limit_num)->select();
			foreach($tmp_group_category as &$value){
					$value['url'] = $this->get_category_url($value);
			}
			return $tmp_group_category;
		}
	}
	/*得到列表所有分类*/
	public function get_all_category(){
		$condition_group_category['cat_status'] = '1';
		$tmp_group_category = $this->field(true)->where($condition_group_category)->order('`cat_sort` DESC')->limit()->select();
		$group_category = array();
		$tmp_category = array();
		foreach($tmp_group_category as $key=>$value){
			if(empty($value['cat_fid'])){
				$tmp_category[$value['cat_id']] = $key;
				
				$value['cat_count'] = 0;
				$value['url'] = $this->get_category_url($value);
				
				$group_category[$key] = $value;
				unset($tmp_group_category[$key]);
			}
		}
		foreach($tmp_group_category as $key=>$value){
			$value['url'] = $this->get_category_url($value);
			
			$group_category[$tmp_category[$value['cat_fid']]]['cat_count'] += 1;
			$group_category[$tmp_category[$value['cat_fid']]]['category_list'][$key] = $value;
		}
		foreach($group_category as $key=>$value){
			if(empty($value['cat_id'])){
				unset($group_category[$key]);
			}
		}
		return $group_category;

	}
	
	/*根据cat_url得到分类*/
	public function get_category_by_catUrl($cat_url){
		$condition_group_category['cat_url'] = $cat_url;
		$condition_group_category['cat_status'] = '1';
		$now_category = $this->field(true)->where($condition_group_category)->find();
		if(!empty($now_category)){
			$now_category['url'] = $this->get_category_url($now_category);
		}
		return $now_category;
	}
	
	/*根据cat_id得到分类*/
	public function get_category_by_id($cat_id){
		$condition_group_category['cat_id'] = $cat_id;
		$condition_group_category['cat_status'] = '1';
		$now_category = $this->field(true)->where($condition_group_category)->find();
		if(!empty($now_category)){
			$now_category['url'] = $this->get_category_url($now_category);
		}
		return $now_category;
	}
	
	/*根据顶级ID或子分类ID 得到子分类或子分类的同级分类*/
	public function get_son_category_list_byid($cat_fid,$cat_id){
		if(!empty($cat_fid)){
			$son_category_list = $this->get_son_category_list_byfid($cat_fid);
		}else{
			$son_category_list = $this->get_son_category_list_bycid($cat_id);
		}
		
		foreach($son_category_list as $key=>$value){
			$son_category_list[$key]['url'] = $this->get_category_url($value);
		}
		return $son_category_list;
	}
	/*根据顶级ID获得子分类列表*/
	public function get_son_category_list_byfid($cat_fid){
		$condition_group_category['cat_fid'] = $cat_fid;
		$condition_group_category['cat_status'] = '1';
		return $this->field(true)->where($condition_group_category)->order('`cat_sort` DESC')->select();
	}
	/*根据子分类ID获得同级分类列表*/
	public function get_son_category_list_bycid($cat_id){
		$condition_group_category['cat_fid'] = $cat_id;
		$condition_group_category['cat_status'] = '1';
		return $this->field(true)->where($condition_group_category)->order('`cat_sort` DESC')->select();
	}
	/* 得到主分类的URL */
	protected function get_f_category_url($category){
		return C('config.site_url').'/appoint/category/#'.$category['cat_url'];
	}
	/* 得到分类的URL */
	protected function get_category_url($category){
		return C('config.site_url').'/appoint/category/'.$category['cat_url'];
	}
        
        
        public function appoint_category_edit($where,$data){
            if(!$where || !$data){
                return false;
            }

            $insert_id = $this->where($where)->data($data)->save();
            if($insert_id){
                return array('status'=>1,'msg'=>'修改成功！');
            }else{
                return array('status'=>0,'msg'=>'修改失败！');
            }
        }
        
        
        public function appoint_category_detail($where,$field = true){
            if(!$where){
                return false;
            }
            
            $detail = $this->where($where)->field($field)->find();
            if($detail['pc_title']){
                $detail['pc_title'] = unserialize($detail['pc_title']);
            }
            
            if($detail['wap_title']){
                $detail['wap_title'] = unserialize($detail['wap_title']);
            }
            
            if($detail['pc_content']){
                $detail['pc_content'] = unserialize($detail['pc_content']);
            }
            
            if($detail['wap_content']){
                $detail['wap_content'] = unserialize($detail['wap_content']);
            }

            if($detail){
                return array('status'=>1,'detail'=>$detail);
            }else{
                return array('status'=>0,'detail'=>$detail);
            }
        }
        
        // 二维码
	public function get_qrcode($cat_id){
		$where['cat_id'] = $cat_id;
		$now_group = $this->field('`cat_id`,`qrcode_id`')->where($where)->find();
		if(empty($now_group)){
			return false;
		}
		return $now_group;
	}
	public function save_qrcode($cat_id,$qrcode_id){
		$where['cat_id'] = $cat_id;
		$data['qrcode_id'] = $qrcode_id;
		if($this->where($where)->data($data)->save()){
			return(array('error_code'=>false));
		}else{
			return(array('error_code'=>true,'msg'=>'保存二维码至预约失败！请重试。'));
		}
	}
}

?>