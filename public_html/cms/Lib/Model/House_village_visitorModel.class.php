<?php
class House_village_visitorModel extends Model{
    protected function _initialize() {
        parent::_initialize();

        $this->visitor_type = array(
             1=>'亲属',
             2=>'朋友',
             3=>'同事',
             255=>'其他（需备注）',
        );
    }

    protected $_validate = array(
            array('visitor_type','require','访客类型不能为空！'),
            array('visitor_phone','require','访客手机号不能为空！'),
            array('owner_phone','require','业主手机号不能为空！'),
            array('owner_phone','chkownerPhone','业主手机号码不正确',0,'callback'),
            array('visitor_phone','visitorphonePhone','访客手机号码不正确',0,'callback'),
    );

    protected function chkownerPhone(){
        $owner_phone = $_POST['owner_phone'] + 0;
        $phone_match='/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
        $mobile_match='/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/';
        if(!C('config.international_phone') && (!preg_match($phone_match, $owner_phone)) && (!preg_match($mobile_match, $owner_phone))){
            return false;
        }else{
            return true;
        }
    }

    protected function visitorphonePhone(){
        $visitor_phone = $_POST['visitor_phone'] + 0;
        $phone_match='/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
        $mobile_match='/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/';
        if(!C('config.international_phone') && (!preg_match($phone_match, $visitor_phone)) && (!preg_match($mobile_match, $visitor_phone))){
            return false;
        }else{
            return true;
        }
    }


    protected $_auto= array(
         array('add_time','time',1,'function'),
         array('village_id','get_village_id',1,'callback'),
         array('owner_uid','get_uid',3,'callback'),
         array('pass_time','time',2,'function'),

    );

    protected function get_uid(){
        $phone = $_POST['owner_phone'] + 0;
        $database_user = D('User');
        if($phone){
            $where['phone'] = $phone;
            $uid = $database_user->where($where)->getField('uid');
            return $uid ? $uid :0;
        }else{
            return 0;
        }
    }

    protected function get_village_id(){
        return $_SESSION['house']['village_id'];
    }


    public function house_village_visitor_add($data,$status=1){
        if(!$data){
            return false;
        }

        $database_user = D('User');
        if($status == 1){
			$userInfo	=	M('user')->where(array('phone'=>$data['owner_phone']))->find();
			if($userInfo){
				$data['owner_uid'] = $userInfo['uid'];
			}
			$insert_id	= $this->data($data)->add();
			if($insert_id){
				//发送微信模板消息start
				if($data['visitor_name']){
					$work_info ='\n姓名为：'.$data['visitor_name'].'  手机号为'.$data['visitor_phone'].'的访客正在等候，需要您的确认！';
				}else{
					$work_info ='\n手机号为'.$data['visitor_phone'].'的访客正在等候，需要您的确认！';
				}
				if($userInfo && $userInfo['openid'] && !$data['status']){
					$href = C('config.site_url').'/wap.php?g=Wap&c=Library&a=visitor_list&village_id='.$data['village_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					//信息要修改
					//如果有访客姓名，则同时提示访客姓名
				   if (($dbinfo = M('Tempmsg')->where(array('tempkey' => 'OPENTM408101810','mer_id'=>0))->find()) && $dbinfo['status'] && $dbinfo['tempid']) {
				
						$model->sendTempMsg('OPENTM408101810', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$_SESSION['house']['village_name'].'业主，您有新访客到访', 'keyword1' => $data['visitor_name'],'keyword2' => $data['visitor_phone'], 'keyword3' => date('Y年m月d日 H时i分'),'remark' => '\n访客正在等候，需要您的确认！'));
					}else{
					   $model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$_SESSION['house']['village_name'].' 业主\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
					 }
				}
	            //发送微信模板消息end
				if(!$data['status'] && C('config.village_sms') == 1){
					$sms_data['uid'] = $data['owner_uid'];
					$sms_data['mobile'] = $data['owner_phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['type'] = 'village_vistor';
					if($data['visitor_name']){
						$sms_data['content'] = '您好，'.$_SESSION['house']['village_name'].'业主，姓名为'.$data['visitor_name'].'，手机号为'.$data['visitor_phone'].'的访客正在等候，需要您的确认！';
					}else{
						$sms_data['content'] = '您好，'.$_SESSION['house']['village_name'].'业主，手机号为'.$data['visitor_phone'].'的访客正在等候，需要您的确认！';
					}
	                Sms::sendSms(($sms_data));
	            }
	            return array('status'=>1,'msg'=>'添加成功！');
			}else{
				return array('status'=>0,'msg'=>'添加失败！');
			}
        }else{
	        if(!$this->create()){
	            return array('status'=>0,'msg'=>$this->getError());
	        }else{
	            //发送微信模板消息start
	            $insert_info = $this->data;
	            if($insert_id = $this->add()){
                    $village_info = M('House_village')->where(array('village_id'=>$data['village_id']))->find();
	                $userInfo = $database_user->get_user($insert_info['owner_uid']);
	                if($insert_info['visitor_name']){
	                    $work_info ='\n姓名为：'.$insert_info['visitor_name'].'  手机号为'.$insert_info['visitor_phone'].'的访客正在等候，需要您的确认！';
	                }else{
	                    $work_info ='\n手机号为'.$insert_info['visitor_phone'].'的访客正在等候，需要您的确认！';
	                }
	                if($userInfo['openid'] && !$insert_info['status']){
	                        $href = C('config.site_url').'/wap.php?g=Wap&c=Library&a=visitor_list&village_id='.$insert_info['village_id'];
	                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
	                        //信息要修改
	                        //如果有访客姓名，则同时提示访客姓名
                            
                        if (($dbinfo = M('Tempmsg')->where(array('tempkey' => 'OPENTM408101810','mer_id'=>0))->find()) && $dbinfo['status'] && $dbinfo['tempid']) {
                    
                            $model->sendTempMsg('OPENTM408101810', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$_SESSION['house']['village_name'].'业主，您有新访客到访', 'keyword1' => $insert_info['visitor_name'],'keyword2' => $insert_info['visitor_phone'], 'keyword3' => date('Y年m月d日 H时i分'),'remark' => '\n访客正在等候，需要您的确认！'));
                        }else{
	                        $model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$_SESSION['house']['village_name'].' 业主\n', 'work' => $work_info, 'remark' => '\n访客正在等候，需要您的确认！'));
                        }
	               }
	               if(!$insert_info['status'] && C('config.village_sms') == 1  && $village_info['now_sms_number'] > 0){
	                    $sms_data['uid'] = $insert_info['owner_uid'];
	                    $sms_data['mobile'] = $insert_info['owner_phone'];
	                    $sms_data['sendto'] = 'user';
                        $sms_data['village_id'] = $data['village_id'];
						$sms_data['type'] = 'village_vistor';
	                    if($insert_info['visitor_name']){
	                        $sms_data['content'] = '您好，'.$_SESSION['house']['village_name'].'业主，姓名为'.$insert_info['visitor_name'].'，手机号为'.$insert_info['visitor_phone'].'的访客正在等候，需要您的确认！';
	                    }else{
	                        $sms_data['content'] = '您好，'.$_SESSION['house']['village_name'].'业主，手机号为'.$insert_info['visitor_phone'].'的访客正在等候，需要您的确认！';
	                    }
	                    Sms::sendSms(($sms_data));
                        M('House_village')->where(array('village_id'=>$data['village_id']))->setDec('now_sms_number');
	               }
	                return array('status'=>1,'msg'=>'添加成功！');
	                //发送微信模板消息end
	            }else{
	                return array('status'=>0,'msg'=>'添加失败！');
	            }
	        }
        }
    }

    public function house_village_visitor_edit($where , $data){
        if(!$where || !$data){
            return false;
        }

        if(!$this->create()){
            return array('status'=>0,'msg'=>$this->getError());
        }else{
            if($this->where($where)->save()){
                return array('status'=>1,'msg'=>'放行成功！');
            }else{
                return array('status'=>0,'msg'=>'放行失败！');
            }
        }
    }


    public function house_village_visitor_page_list($where , $field = true , $order = 'id desc',$pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $house_village_visitor_list = $this->where($where)->field($field)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        $list['list'] = $house_village_visitor_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }


    public function house_village_visitor_list($where , $field = true , $order = 'id desc',$pageSize = 20){
        if(!$where){
            return false;
        }

        $house_village_visitor_list = $this->where($where)->field($field)->order($order)->select();
        $list = $house_village_visitor_list;
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }


    public function house_village_visitor_del($where){
        if(!$where){
            return false;
        }

        $insert_id = $this->where($where)->delete();
        if($insert_id){
            return array('status'=>1,'msg'=>'删除数据成功！');
        }else{
            return array('status'=>0,'msg'=>'删除数据失败！');
        }
    }


    public function house_village_visitor_detail($where,$field=true){
        if(!$where){
            return false;
        }
        $visitor_type = $this->visitor_type;

        $detail = $this->where($where)->field($field)->find();
        $detail['visitor_type'] = $visitor_type[$detail['visitor_type']];

        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }


    public function ajax_house_village_visitor_search($where,$page=0,$page_coun=0){
        if(!$where){
            return false;
        }

        $where['village_id'] = $_SESSION['house']['village_id'];
        $count	= $this->where($where)->count();
        if($page && $page_coun){
			$list = $this->where($where)->page($page,$page_coun)->select();
        }else{
			$list = $this->where($where)->select();
			foreach($list as $k=>$v){
		    	$list[$k]['visitor_type'] = $this->visitor_type[$v['visitor_type']];
		    }
        }
        if($list){
        	if($page && $page_coun){
        		$arr	=	array(
					'totalPage' => ceil($count/$page_coun),
					'page' => intval($page),
					'list_count' => count($list),
					'list'	=>	isset($list)?$list:array(),
        		);
				return array('status'=>1,'list'=>$arr);
        	}else{
				return array('status'=>1,'list'=>$list);
        	}
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }
}
?>

