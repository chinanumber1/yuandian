<?php
class House_village_user_vacancyModel extends Model{
    public function house_village_user_vacancy_page_list($where, $fields = true ,$order = 'pigcms_id desc' , $pageSize = 20){
        if(!$where){
            return false;
        }


        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $vacancy_list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

        $database_house_village_floor = D('House_village_floor');
        $database_house_village_floor_type = D('House_village_floor_type');

        $village_id = $_SESSION['house']['village_id'] ? $_SESSION['house']['village_id'] : $_GET['village_id'] + 0;
        $floor_list = $database_house_village_floor->where(array('village_id'=>$village_id,'status'=>1))->getField('floor_id,floor_name,floor_layer,floor_type');
        $floor_type_list = $database_house_village_floor_type->where(array('village_id'=>$village_id,'status'=>1))->getField('id,name');
        foreach($vacancy_list as $Key=>$row){
            $row['floor_name'] = $floor_list[$row['floor_id']]['floor_name'];
            $row['floor_layer'] = $floor_list[$row['floor_id']]['floor_layer'];
            $row['floor_type_name'] = $floor_type_list[$floor_list[$row['floor_id']]['floor_type']];
            $vacancy_list[$Key] = $row;
        }
        $list['pageCount'] = ceil($count / $pageSize);
        $list['list'] = $vacancy_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'result'=>$list);
        }else{
            return array('status'=>0,'result'=>$list);
        }
    }


	//获取指定单元的房间列表 - wangdong
	public function house_village_user_vacancy_page_list_room($where, $fields = true ,$order = 'pigcms_id desc' , $pageSize = 20){
        if(!$where){
            return false;
        }


        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $vacancy_list_arr = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		foreach($vacancy_list_arr as $k=>$v){
			if($v['uid']==0 && $v['name']!='' && $v['phone']!=''){

			}else{
			$vacancy_list[]=$v;
			}
		}


        $list['list'] = $vacancy_list_arr;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'result'=>$list);
        }else{
            return array('status'=>0,'result'=>$list);
        }
    }



    public function import_village_del($where){
        if(!$where){
            return false;
        }

        //验证是否绑定业主
        $list = $this->house_village_user_vacancy_page_list($where,'','',99999);
        if ($list) {
            $flag = false;
            foreach ($list as $key => $value) {
                $yz_bind_num = D('House_village_user_bind')->where(array('vacancy_id'=>$value['pigcms_id'],'type'=>array('in','0,3'),'status'=>array('in','1,2')))->count();
                if ($yz_bind_num) {
                    $flag = true;break;
                }
            }
            if ($flag) {
                return array('status'=>0,'msg'=>'已绑定业主，无法删除！');
            }
        }else{
            return array('status'=>0,'msg'=>'房间不存在！');
        }

        $insert_id = $this->where($where)->delete();

        if($insert_id){
            return array('status'=>1,'msg'=>'删除成功！');
        }else{
            return array('status'=>0,'msg'=>'删除失败！');
        }
    }


    public function house_village_user_vacancy_detail($where,$fields = true){
        if(!$where){
            return false;
        }

        $detail = $this->where($where)->field($fields)->find();
        if($detail['type'] == 0){
            $detail['relation_val'] = '房主';
        }else if($detail['type'] == 1){
            $detail['relation_val'] = '家人';
        }else if($detail['type'] == 2){
            $detail['relation_val'] = '租客';
        }else{
            $detail['relation_val'] = '未知';
        }

        $floor_info = D('House_village_floor')->where(array('floor_id'=>$detail['floor_id'],'status'=>1,'village_id'=>$_SESSION['house']['village_id']))->getField('floor_id,floor_name,floor_layer');
        $detail['floor_name'] = $floor_info[$detail['floor_id']]['floor_name'];
        $detail['layer_name'] = $floor_info[$detail['floor_id']]['floor_layer'];

        $database_house_village_floor_type = D('House_village_floor_type');
        $floor_type_name = $database_house_village_floor_type->where(array('id'=>$floor_info[$detail['floor_id']]['floor_type'],'status'=>1))->getField('name');
        $detail['floor_type_name'] = $floor_type_name;

        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }


    public function house_village_user_vacancy_edit($where,$data){
        if(!$where || !$data){
            return false;
        }

        $condition['usernum'] = $data['usernum'];
        /* $info = $this->where($condition)->find();*/
        //if(!$info){
            $database_house_village_user_bind = D('House_village_user_bind');
            $info = $database_house_village_user_bind->where($condition)->find();
            if(!$info){
                $database_house_village_floor = D('House_village_floor');
                $_condition['floor_name'] = $data['floor_name'];
                $_condition['floor_layer'] = $data['floor_layer'];

                $info = $database_house_village_floor->where($_condition)->find();

                if(!$info){
                    return array('status'=>0,'msg'=>'单元不存在！');
                }else{
                    $data['floor_id'] = $_where['floor_id'] = $info['floor_id'];
                    $_where['layer'] = $data['layer'];
                    $_where['room'] = $data['room'];

                    $vacancy_info = $this->where($_where)->find();
                    if($vacancy_info){
                        return array('status'=>0,'msg'=>'该房间已存在！');
                    }

                    $insert_id = $this->where($where)->data($data)->save();
                    if($insert_id){
                        return array('status'=>1,'msg'=>'修改成功！');
                    }else{
                        return array('status'=>0,'msg'=>'修改失败！');
                    }
                }
            }else{
                return array('status'=>0,'msg'=>'物业编号已存在！');
            }
        /*}else{
            return array('status'=>0,'msg'=>'物业编号已存在！');
        }*/
    }

	//查询和保存房间信息 - wangdong
	 public function house_village_user_vacancy_edit_find($where,$data){
        if(!$where || !$data){
            return false;
        }
        $condition['pigcms_id'] = $where['pigcms_id'];
		$info = $this->where($condition)->find();

		if($info){
			$database_house_village_floor = D('House_village_floor');
			$_condition['floor_name'] = $data['floor_name'];
            $_condition['floor_layer'] = $data['floor_layer'];
            if ($where['village_id']) {
                $_condition['village_id'] = $where['village_id'];
            }

			$info = $database_house_village_floor->where($_condition)->find();

			if(!$info){
				return array('status'=>0,'msg'=>'单元不存在！');
			}else{
				$data['floor_id'] = $_where['floor_id'] = $info['floor_id'];
				$_where['pigcms_id'] = $where['pigcms_id'];

				$vacancy_info = $this->where($_where)->find();
				if(!$vacancy_info){
					return array('status'=>0,'msg'=>'房间不存在！');
				}

				$data_info['usernum']   =  $data['usernum'];
				$data_info['layer']     =  $data['layer'];
				$data_info['room']      =  $data['room'];


                if ($vacancy_info['uid']) {
                    $bind = M('House_village_user_bind')->where(array('uid' => $vacancy_info['uid']))->find();
                } else {
                    $bind = 0;
                }
                if (intval($vacancy_info['status'])==0 && $vacancy_info['uid'] && intval($data['status']) == 1) {
                    if ($bind && $bind['status'] == 1) {
                        $data_info['status'] = 3;
                    } elseif ($bind && $bind['status'] == 2) {
                        $data_info['status'] = 2;
                    } else {
                        $data_info['status']  =  1;
                    }
                } elseif (intval($data['status']) == 0 || intval($vacancy_info['status']) == 0) {
                    $data_info['status']  =  $data['status'];
                    if (intval($data['status']) == 1) {
                        if ($bind && $bind['status'] == 1) {
                            $data_info['status'] = 3;
                        } elseif ($bind && $bind['status'] == 2) {
                            $data_info['status'] = 2;
                        } else {
                            $data_info['status']  =  1;
                        }
                    }
                } else {
                    $data_info['status'] =  $vacancy_info['status'];
                    if (intval($vacancy_info['status']) == 1) {
                        if ($bind && $bind['status'] == 1) {
                            $data_info['status'] = 3;
                        } elseif ($bind && $bind['status'] == 2) {
                            $data_info['status'] = 2;
                        } else {
                            $data_info['status']  =  1;
                        }
                    }
                }
				$data_info['floor_id']  =  $data['floor_id'];
                $data_info['housesize']  =  $data['housesize'];
				$data_info['add_time']  =  time();

				$insert_id = $this->where($where)->data($data_info)->save();
				if($insert_id){
				    // 修改了房屋面积，且在绑定表格中存在对应的数据，同步更改其中的房屋面积 (更改对象包括房主，家属，租客及更新房主)
				    if ($data['housesize']) {
                        $bind_condition = [];
//                        $bind_condition['usernum'] = $data['usernum'];
                        $bind_condition['village_id'] = $vacancy_info['village_id'];
                        $bind_condition['floor_id'] = $vacancy_info['floor_id'];
                        $bind_condition['layer_num'] = $vacancy_info['layer'];
                        $bind_condition['room_addrss'] = $vacancy_info['room'];
//                        $bind_condition['uid'] = $vacancy_info['uid'];
                        $bind_info = M('House_village_user_bind')->field('pigcms_id')->where($bind_condition)->select();
                        if ($bind_info) {
                            $bind_data_info = [];
                            $bind_data_info['housesize'] = $data['housesize'];
                            foreach ($bind_info as $v) {
                                M('House_village_user_bind')->where(array('pigcms_id' => $v['pigcms_id']))->data($bind_data_info)->save();
                            }
                        }
                    }
					return array('status'=>1,'msg'=>'修改成功！');
				}else{
					return array('status'=>0,'msg'=>'修改失败！');
				}
			}
		}else{
			return array('status'=>0,'msg'=>'房间不存在！');
		}

    }


	//获取指定房间信息 - wangdong
	public function get_find_room_info($pigcms_id,$field=""){

		if(empty($field)) $field=true;

		$condition['pigcms_id'] = $pigcms_id;

		$room_info = $this->field($field)->where($condition)->find();

		return $room_info;

	}

	// 获取房间是否绑定业主 - wangdong
	public function get_find_room_count($pigcms_id){

		if(empty($pigcms_id)){
			return false;
		}

		$where['pigcms_id'] = $pigcms_id;
		$where['type'] = 0 ;
		$where['status'] = 3;

		$room_true_find = $this->field(true)->where($where)->find();
		return $room_true_find;
	}

	//查询用户所在小区的所有房主
	public function get_my_village_lists($uid , $field=""){

		$condition['uid'] = $uid;

		$condition['type'] = 0;

		//$condition['is_del'] = 0;

		if(empty($field)) $field = true;

		$lists = $this->field($field)->where($condition)->order('village_id DESC,floor_id Asc,pigcms_id ASC')->select();

		return $lists;

	}

    /**
     * [get_count 获取数量]
     * @return [type] [description]
     */
    public function get_user_vacancy_count($where,$field=''){
        $count = D('House_village_user_vacancy')->where($where)->count($field);
        return $count;
    }



}