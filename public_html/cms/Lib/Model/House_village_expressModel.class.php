<?php
class House_village_expressModel extends Model{
    public function village_express_add($data){
        if(!$data){
			return array('status'=>0,'msg'=>'请携带快递参数');
        }
		$userInfo	=	D('User')->get_user($data['phone'],'phone');
		if($userInfo){
			$data['uid'] = $userInfo['uid'];
		}
		$add = $this->data($data)->add();
		if($add){
			$express = D('Express')->get_express($data['express_type']);
			$village_info = M('House_village')->where(array('village_id'=>$data['village_id']))->find();
			//发送微信模板消息start
			if($userInfo && $userInfo['openid']){
				$href = C('config.site_url').'/wap.php?g=Wap&c=Library&a=express_service_list&village_id='.$data['village_id'];
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$first = '您好，您有新的快递待取';
				// $express_info = '\n您的'.$express['name'].'包裹到达'.$village_info["village_name"].'。请您及时领取，电话：'.$village_info['property_phone'].'。取件码'.$data['fetch_code'].'。请及时领取，避免快递公司退回。';
                $express_info = '\n您的'.$express['name'].'快递包裹到达'.$village_info["village_name"].'服务站,取货码'.$data['fetch_code'].',电话'.$village_info['property_phone'].',地址：'.$village_info['property_address'].'。';
				$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => $first, 'work' => $express_info, 'remark' => '\n请点击查看详细信息！'));
			}
			//发送微信模板消息end
			if (C('config.village_sms') == 1 && (!C('config.buy_sms') || $village_info['now_sms_number'] > 0)){
				$sms_data['type'] = 'village_express';
				$sms_data['uid'] = intval($data['uid']);
				$sms_data['mobile'] = $data['phone'];
                $sms_data['village_id'] = $data['village_id'];
				// $sms_data['content'] = '您的'.$express['name'].'包裹到达'.$village_info["village_name"].'。请您及时领取，电话：'.$village_info['property_phone'].'。取件码'.$data['fetch_code'].'。请及时领取，避免快递公司退回。';
                $sms_data['content'] = '您的'.$express['name'].'快递包裹到达'.$village_info["village_name"].'服务站,取货码'.$data['fetch_code'].',电话'.$village_info['property_phone'].',地址：'.$village_info['property_address'].'。';

				Sms::sendSms($sms_data);
                M('House_village')->where(array('village_id'=>$data['village_id']))->setDec('now_sms_number');
			}
			return array('status'=>1,'msg'=>'数据添加成功！');
		}else{
			return array('status'=>0,'msg'=>'数据添加失败！');
		}
    }


    public function house_village_express_edit($where , $data){
        if(!$where || !$data){
            return false;
        }

        if(!$this->create()){
            return array('status'=>0,'msg'=>$this->getError());
        }else{
            if($this->where($where)->data($data)->save()){
                return array('status'=>1,'msg'=>'取件成功！');
            }else{
                return array('status'=>0,'msg'=>'取件失败！');
            }
            // return array('status'=>1,'msg'=>'取件成功！');
        }
    }

    public function express_service_page_list($where , $field = true , $order = 'id desc',$pageSize = 20){
        if(!$where){
            return false;
        }

        $database_express = D('Express');
		$database_house_village_user_bind = D('House_village_user_bind');
        import('@.ORG.merchant_page');
        $where['ep.village_id'] = $where['village_id'];
        unset($where['village_id']);
        $count = $this->join('as ep LEFT JOIN '.C('DB_PREFIX').'house_village_floor as f ON ep.floor_id = f.floor_id ')->where($where)->count();
        $p = new Page($count,$pageSize,'page');


		$village_express_list = $this->join('as ep LEFT JOIN '.C('DB_PREFIX').'house_village_floor as f ON ep.floor_id = f.floor_id LEFT JOIN '.C('DB_PREFIX').'house_village_express_order o ON o.express_id = ep.id')->where($where)->field('ep.*,f.floor_name,o.send_time')->order($order)->limit($p->firstRow.','.$p->listRows)->select();
//       dump($this);
		foreach($village_express_list as $k=>&$v){	
			$var_address = "";
			$all_address = $database_house_village_user_bind->field('address')->where(array('village_id'=>$v['village_id'],'phone'=>$v['phone'],'floor_id'=>$v['floor_id'],'status'=>1))->select();
			foreach($all_address as $m=>$n){
				$var_address .= "Ad: ".$n['address']."&nbsp;&nbsp;";	
			}
			$v['address'] = $var_address;			   
		}
	   
        $express_where['status'] = 1;
        $express_info = $database_express->where($express_where)->getField('id,name');

        foreach ($village_express_list as $key => $value) {
            if($value['express_type']==255){
               $village_express_list[$key]['express_name'] = '其他';
            }else{
               $village_express_list[$key]['express_name'] = $express_info[$value['express_type']];
            }
        }

        $list['list'] = $village_express_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }


    public function express_service_list($where , $field = true , $order = 'id desc'){
        if(!$where){
            return false;
        }

        $database_express = D('Express');

        $village_express_list = $this->where($where)->field($field)->order($order)->select();
        $express_where['status'] = 1;
        $express_info = $database_express->where($express_where)->getField('id,name');

        $express_id_arr = array();
        foreach($village_express_list as $k=>$v){
            $express_id_arr[] = $v['id'];
            if($v['express_type']==255){
               $village_express_list[$k]['express_name'] = '其他';
            }else{
               $village_express_list[$k]['express_name'] = $express_info[$v['express_type']];
            }
        }

        $database_house_village_express_order = D('House_village_express_order');
        $order_where['express_id'] = array('in',$express_id_arr);
        $order_field = array('order_id','paid','express_id','status','send_time');
        $order_list = $database_house_village_express_order->house_village_express_order_page_list($order_where , $order_field , 'order_id desc' , 99999);
        $order_list = $order_list['result']['list'];

        if($order_list){
            foreach($order_list as $order){
                foreach($village_express_list as $Key=>$express_info){
                    if($order['express_id'] == $express_info['id']){
                        $village_express_list[$Key]['order_info'] = $order;
                    }
                }
            }
        }
        if($list){
            return array('status'=>1,'list'=>$village_express_list);
        }else{
            return array('status'=>0,'list'=>$village_express_list);
        }
    }


    public function village_express_del($where){
        if(!$where){
            return false;
        }

        $insert_id = $this->where($where)->delete();
        if($insert_id){
            return array('status'=>1,'msg'=>'删除信息成功！');
        }else{
            return array('status'=>0,'msg'=>'删除信息失败！');
        }
    }


    public function ajax_vllage_express_search($where,$page=0,$pageSize=0){
        if(!$where){
            return false;
        }

        $database_express = D('Express');
        $where['village_id'] = $_SESSION['house']['village_id'];
        if(empty($page) && empty($pageSize)){
            $list = $this->where($where)->select();
        }else{
            $count      = $this->where($where)->page($page,$pageSize)->count();
            $list       = $this->where($where)->order('id desc')->page($page,$pageSize)->select();
        }
        $express_where['status'] = 1;
        $express_info = $database_express->where($express_where)->getField('id,name');
        foreach($list as $k=>$v){
            if($v['express_type']==255){
               $list[$k]['express_name'] = '未知';
            }else{
               $list[$k]['express_name'] = $express_info[$v['express_type']];
            }

            $list[$k]['add_time_s'] = date('Y-m-d H:i:s',$v['add_time']);
            $list[$k]['delivery_time_s'] = date('Y-m-d H:i:s',$v['delivery_time']);
            $list[$k]['idd'] = $v['id'];
        }
        // if(!$this->create()){
        //     return array('status'=>0,'msg'=>$this->getError());
        // }else{
            
        // }

        if($list){
        	if(empty($page) && empty($pageSize)){
				return array('status'=>1,'list'=>$list);
			}else{
				$list	=	array(
					'totalPage'		=>	ceil($count/$pageSize),
					'page'		=>	intval($page),
					'count_list'	=>	count($list),
					'list'	=>	isset($list)?$list:array(),
					'status'	=>	1,
				);
				return	$list;
			}
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }


    public function house_village_express_detail($where , $field=true){
        if(!$where){
            return false;
        }
        $database_express = D('Express');
        $express_where['status'] = 1;
        $express_info = $database_express->where($express_where)->getField('id,name');

        $detail = $this->where($where)->field($field)->find();
        $floor = M('House_village_floor')->where(array('floor_id'=>$detail['floor_id']))->find();
        $detail['floor_name'] = $floor['floor_name'];
        if($detail['express_type']==255){
               $detail['express_name'] = '其他';
            }else{
               $detail['express_name'] = $express_info[$detail['express_type']];
            }

        if(!$detail){
            return array('status'=>0,'detail'=>$detail);
        }else{
            $database_house_village_express_order = D('House_village_express_order');

            $order_where['express_id'] = $detail['id'];
            $order_info = $database_house_village_express_order->house_village_express_order_detail($order_where);
            $order_info = $order_info['detail'];
            $detail['order_info'] = $order_info;
            $detail['send_time'] = $order_info['send_time'];

            return array('status'=>1,'detail'=>$detail);
        }
    }
}
?>
