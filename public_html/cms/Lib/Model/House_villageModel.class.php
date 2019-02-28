<?php
class House_villageModel extends Model{
    protected $_validate = array(
        array('wx_image','require','图片必须存在！',1),
    );

    protected $_auto = array(
        array('last_time','time',3,'function'),
    );


    /*得到用户关注的小区*/
	public function get_bind_list($uid,$phone = '',$flag = false,$long_lat=array()){
		$where = "`hv`.`status`='1' AND`hv`.`village_id`=`hvub`.`village_id` AND `hv`.`city_id`='".C('config.now_city')."' AND `parent_id`=0";
		if(!empty($uid)){
			$where .= " AND `hvub`.`uid`='$uid'";
		}

		if(!empty($phone)){
			D('House_village_user_bind')->bind($uid,$phone);
		}
		$village_list = D('')->field('`hv`.*,`hvub`.*')->table(array(C('DB_PREFIX').'house_village'=>'hv',C('DB_PREFIX').'house_village_user_bind'=>'hvub'))->where($where)->order('`hvub`.`pigcms_id` DESC')->group('`hv`.`village_id`')->select();

		//演示站添加默认数据
		if(empty($village_list) && $_SERVER['HTTP_HOST'] == 'hf.pigcms.com' && $flag == false){
			$data_arr = array(
				'village_id'=>'9',
				'uid'=>$uid,
				'usernum'=>20010305+$uid,
				'name'=>'测试业主',
				'phone'=>$phone,
				'housesize'=>'100',
				'park_flag'=>1,
				'address'=>'测试业主之家',
			);
			D('House_village_user_bind')->data($data_arr)->add();
			return $this->get_bind_list($uid,$phone,true);
		}
		if($flag == true && $village_list && $_SERVER['HTTP_HOST'] == 'hf.pigcms.com'){
			$village_list[0]['first_test'] = true;
		}
		if($long_lat && $village_list){
			foreach($village_list as &$village_value){
				$village_value['range'] = getRange(getDistance($village_value['lat'],$village_value['long'],$long_lat['lat'],$long_lat['long']));
			}
		}

		return $village_list;
	}

	//衩绑定家属小区列表
	public function get_bind_family_list($where){
	    if(!$where){
		return false;
	    }
	    $field = array('`hv`.*','`hvub`.`pigcms_id`','`hvub`.`name`','`hvub`.`uid`');
	    $table = array(C('DB_PREFIX').'house_village'=>'hv',C('DB_PREFIX').'house_village_user_bind'=>'hvub');
	    $where['_string'] = '`hv`.`village_id` = `hvub`.`village_id`';
	    $list = D('')->field($field)->table($table)->where($where)->group('uid desc')->select();
	    foreach($list as $k=>$v){
		$list[$k]['flag'] = 1;
	    }

	    return $list;
	}


	/*得到小区列表，支持经纬度*/
	public function wap_get_list($long_lat='',$keyword= '',$account = ''){
		if ($long_lat) {
			$order = "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$long_lat['lat']} * PI() / 180- `lat` * PI()/180)/2),2)+COS({$long_lat['lat']} *PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$long_lat['long']} *PI()/180- `long`*PI()/180)/2),2)))*1000) ASC";
		} else {
			$order = "`village_id` DESC";
		}
		import('@.ORG.wap_group_page');
		$condition_village = array(
			'status'=>'1',
			'city_id'=>C('config.now_city')
		);
		if(!empty($keyword)){
			$condition_village['village_name'] = array('like','%'.$keyword.'%');
		}
		if(!empty($account)){
			$condition_village['account'] = $account;
		}
		$count = $this->where($condition_village)->count('village_id');
		$p = new Page($count,10,'page');
		$village_list = $this->field(true)->where($condition_village)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		if($long_lat && $village_list){
			foreach($village_list as &$village_value){
				$village_value['range'] = getRange(getDistance($village_value['lat'],$village_value['long'],$long_lat['lat'],$long_lat['long']));
			}
		}
		$return = array();
		if($village_list){
			$return['village_list'] = $village_list;
			$return['totalPage'] = ceil($count/10);
			$return['village_count'] = count($village_list);
			$return['pagebar'] =$p->show();
		}
		return $return;
	}
	public function get_one($village_id,$field =true){
		return $this->field($field)->where(array('village_id'=>$village_id,'status'=>'1'))->find();
	}


	// 二维码
	public function get_qrcode($id){
		$where['village_id'] = $id;
		$now_village = $this->field('`village_id`,`qrcode_id`')->where($where)->find();
		if(empty($now_village)){
			return false;
		}
		return $now_village;
	}
	public function save_qrcode($id,$qrcode_id){
		$where['village_id'] = $id;
		$data['qrcode_id'] = $qrcode_id;
		if($this->where($where)->data($data)->save()){
			return(array('error_code'=>false));
		}else{
			return(array('error_code'=>true,'msg'=>'保存二维码至社区失败！请重试。'));
		}
	}

        public function house_village_edit($where){
            if(!$where){
                return false;
            }
            if(!$this->create()){
                return array('status'=>0,'msg'=>$this->getError());
            }else{
                if($this->where($where)->save()){
                    return array('status'=>1,'msg'=>'修改成功！');
                }else{
                    return array('status'=>0,'msg'=>'修改失败！');
                }
            }
        }
	
	//获取小区基本信息 - wangdong
	public function get_village_info($village_id,$field=''){
	
		$condition['village_id'] = $village_id;
		
		if(empty($field)) $field=true;
		
		$info = $this->field($field)->where($condition)->find();
		
		return $info;
		
	}

	public function get_village_list(){

		return M('House_village')->field('village_id,village_name')->where(array('status'=>1))->order('village_id DESC')->select();
	}
	
			
}

?>