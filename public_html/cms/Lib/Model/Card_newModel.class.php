<?php
class Card_newModel extends Model{
    //根据商家获取会员卡用户列表
    public function get_card_user_list_by_mer_id($mer_id){
        $where['mer_id']=$mer_id;
        $card_user_list = M('Card_userlist')->field('c.id,c.card_money,c.card_money_give,c.card_score,c.physical_id,c.add_time,c.status,u.uid,u.nickname,u.phone')->join('as c left join '.C('DB_PREFIX').'user as u ON c.uid=u.uid')->where($where)->select();

        return $card_user_list;
    }

    //会员卡信息
    public function get_cardinfo_by_id($id){
        $where['c.id']=$id;
        return M('Card_userlist')->field('c.*,u.nickname,u.phone,u.openid')->join('as c left join '.C('DB_PREFIX').'user as u ON c.uid=u.uid')->where($where)->find();
    }

    

    //会员卡金额记录
    public function card_use_record($id){
        // $where['uid']=$uid;
        $where['card_id']=$id;
        import('@.ORG.system_page');
        $count = M('Card_new_record')->where($where)->count();
        $p = new Page($count,10);
        $result =  M('Card_new_record')->where($where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
        return array('record'=>$result,'pagebar'=>$p->show());
    }
	
	//根据商家获取会员卡
    public function get_card_by_mer_id($mer_id){
        $where['c.mer_id']=$mer_id;
        $card = $this->field('c.*,m.name,m.pic_info')->join('as c left join '.C('DB_PREFIX').'merchant m ON m.mer_id = c.mer_id ')->where($where)->find();
        if(empty($card)){
            return array();
        }
        $img = explode(';',$card['pic_info']);
        $card['mer_pic'] = str_replace(',','/',$img[0]);
        return $card;
    }
	
	/*增加会员卡余额*/
	public function add_user_money($mer_id,$uid,$money,$give_money,$give_score,$desc,$give_desc=''){
		$now_userlist = M('Card_userlist')->where(array('mer_id'=>$mer_id,'uid'=>$uid))->find();
		$data_userlist['card_money'] = $now_userlist['card_money'] + $money;
		$data_userlist['card_money_give'] = $now_userlist['card_money_give'] + $give_money;
		$data_userlist['card_score'] = $now_userlist['card_score'] + $give_score;

		if(M('Card_userlist')->where(array('id'=>$now_userlist['id']))->data($data_userlist)->save()){
            if($money>0){
                $param = array(
                    'card_id' => $now_userlist['id'],
                    'type' => 1,
                    'money_add' => $money,
                    'desc' => $desc,
                );
                $this->add_row($param);
            }
			if($give_money>0){
				$param = array(
					'card_id' => $now_userlist['id'],
					'type' => 1,
					'money_add' => $give_money,
					'desc' => $give_desc,
				);
				$this->add_row($param);
			}
            if($give_score>0){
                $param = array(
                    'card_id' => $now_userlist['id'],
                    'type' => 1,
                    'score_add' => $give_score,
                    'desc' => $give_desc,
                );
                $this->add_row($param);
            }
            if ($now_userlist['wx_card_code'] != ''){
                if($money+$give_money!=0){
                    $this->update_wx_card($now_userlist['wx_card_code'],($money+$give_money),0,$desc);
                }
                if($give_score!=0){
                    $this->update_wx_card($now_userlist['wx_card_code'],0,$give_score,'',$give_desc);
                }

            }
			return true;
		}else{
			return false;
		}
	}

    /*增加记录行数*/
    public function add_row($parm){
        $data_user_money_list['card_id'] = $parm['card_id'];
        $data_user_money_list['type'] = $parm['type'];
        $data_user_money_list['money_add']  = empty($parm['money_add'])?0:$parm['money_add'];
        $data_user_money_list['money_use']  = empty($parm['money_use'])?0:$parm['money_use'];
        $data_user_money_list['score_add']  = empty($parm['score_add'])?0:$parm['score_add'];
        $data_user_money_list['score_use']  = empty($parm['score_use'])?0:$parm['score_use'];
        $data_user_money_list['coupon_add'] = empty($parm['coupon_add'])?0:$parm['coupon_add'];
        $data_user_money_list['coupon_use'] = empty($parm['coupon_use'])?0:$parm['coupon_use'];
        $data_user_money_list['desc'] =$parm['desc'];

        $card_info = D('Card_new')->get_cardinfo_by_id($parm['card_id']);
        $now_merchant =D('Merchant')->get_info($card_info['mer_id']);
        if($card_info['openid'] && ($parm['money_add']>0 || $parm['money_use']>0)) {
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href = C('config.site_url') . '/wap.php?c=My_card&a=merchant_card&mer_id='.$card_info['mer_id'];
            $money = $parm['money_add']>0?$parm['money_add']:$parm['money_use'];
            $model->sendTempMsg('OPENTM401833445', array('href' => $href,
                'wecha_id' => $card_info['openid'],
                'first' => '尊敬的' . $card_info['nickname'] . ',您的【'.$now_merchant['name'].'】会员卡赠送余额账户发生变动',
                'keyword1' => date('Y-m-d H:i'),
                'keyword2' =>$parm['desc'],
                'keyword3' => $money,
                'keyword4' => $card_info['card_money']+$card_info['card_money_give'] ,
                'remark' => '详情请点击此消息进入会员卡查询!'),
                $card_info['mer_id']);
        }
        $data_user_money_list['time'] = $_SERVER['REQUEST_TIME'];
        if(M('Card_new_record')->data($data_user_money_list)->add()){
            return true;
        }else{
            return false;
        }
    }

    function  get_qrcode($id){
        $condition_store['id'] = $id;
        $qrcode_id = M('Card_userlist')->field('`ticket`,`qrcode_id`')->where($condition_store)->find();
        if(empty($qrcode_id)){
            return false;
        }
        return $qrcode_id;
    }


    //自动领卡
    public function auto_get($uid,$mer_id,$wxcardcode=''){
        if($card = M('Card_new')->where(array('mer_id'=>$mer_id))->find()){
            if(!$card['auto_get']&&!$card['auto_get_buy']){
                return array('error_code'=>true,'msg'=>'该商家会员卡不能自动领卡') ;
            }
            if(!$card['status']){
                return array('error_code'=>true,'msg'=>'该商家会员卡没有启用') ;
            }
            if(($uid && M('Card_userlist')->where(array('uid'=>$uid,'mer_id'=>$mer_id))->find())){
                return array('error_code'=>true,'msg'=>'您已经有一张会员卡了，不能再领了') ;
            }
            $data['card_id']=$card['card_id'];
            $data['mer_id']=$mer_id;
            $data['uid']=$uid;
            $data['card_money_give']=$card['begin_money'];
            $data['card_score']=$card['begin_score'];
            $data['add_time']=time();
            $data['status']=1;
            $data['wx_card_code']=$wxcardcode;
            if($card_id = M('Card_userlist')->add($data)){
                if($card['begin_money']>0||$card['begin_score']>0){
                    $date_row['card_id']=$card_id;
                    $date_row['type']=1;
                    $date_row['money_add']=$card['begin_money'];
                    $date_row['score_add']=$card['begin_score'];
                    $date_row['desc']='开卡赠送余额积分';
                    $this->add_row($date_row);
                }
                if($card['auto_get_coupon']){
                    D('Card_new_coupon')->auto_get_coupon($mer_id,$uid);
                }
                if($now_user = M('User')->where(array('uid'=>$uid))->find()){
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $now_merchant = M('Merchant')->where(array('mer_id'=>$mer_id))->find();
                    $model->sendTempMsg('OPENTM200964573', array('href' => C('config.site_url').'/wap.php?g=Wap&c=My_card&a=merchant_card&mer_id='.$mer_id, 'wecha_id' =>$now_user['openid'], 'first' =>  '您成功领取了商家【'.$now_merchant['name'].'】的会员卡', 'keyword1' =>$card_id, 'keyword2' => $now_user['nickname'],'keyword3'=>$now_user['phone'],'keyword4'=>date("Y年m月d日 H:i"), 'remark' => '感谢您的关注！'),$now_merchant['mer_id']);
                }

                return array('error_code'=>false,'msg'=>'领卡成功','card_id'=>$card_id);
            }else{
                return  array('error_code'=>true,'msg'=>'领卡失败') ;
            }
        }else{
            return array('error_code'=>true,'msg'=>'未查询到商家的会员卡') ;
        }
    }

    //根据uid,merid获取会员卡
    public function get_card_by_uid_and_mer_id($uid,$mer_id){
        $merchant_card = $this->where(array('mer_id'=>$mer_id))->find();
		//dump($merchant_card);
        if(!$merchant_card['status']){
            return array();
        }
        $now_user_card = M('Card_userlist')->where(array('uid'=>$uid,'mer_id'=>$mer_id))->find();
        if(empty($now_user_card) || $now_user_card['status']==0){
            return array();
        }
        $coupon_list = D('Card_new_coupon')->get_coupon_by_uid($uid,$now_user_card['mer_id']);
        if(empty($coupon_list)){
            $count = 0;
        }else{
            $count = count($coupon_list);
        }
        $now_user = D('User')->get_user($uid);
        $card_info = array_merge($merchant_card,$now_user_card,$now_user);

        $card_info['coupon_num'] = $count;
        return $card_info;
    }
	
	//根据商家ID和用户ID返回会员卡折扣信息和用户是否领取
    public function get_card_by_mer_id_and_uid($mer_id,$uid=''){
        $merchant_card = $this->where(array('mer_id'=>$mer_id))->find();
        if(!$merchant_card['status']){
            return array();
        }
		$merchant_card['discount'] = $merchant_card['discount'] == 10 ? 0 : $merchant_card['discount'];
        $now_user_card = M('Card_userlist')->where(array('uid'=>$uid,'mer_id'=>$mer_id))->find();
        if(empty($now_user_card)){
            return array('card_discount'=>$merchant_card['discount'],'get_card'=>false,'url'=>C('config.site_url').'/wap.php?c=My_card&a=merchant_card&mer_id='.$mer_id);
        }
        return array('card_discount'=>$merchant_card['discount'],'get_card'=>true,'url'=>C('config.site_url').'/wap.php?c=My_card&a=merchant_card&mer_id='.$mer_id);
    }

	public function get_use_coupon_by_params($uid, $mer_id, $cate_name)
	{
		$now_time = time();
		$sql = "SELECT h.*, c.* FROM " . C('DB_PREFIX') . "card_new_coupon_hadpull AS h INNER JOIN " . C('DB_PREFIX') . "card_new_coupon AS c ON h.coupon_id = c.coupon_id WHERE h.uid='{$uid}' AND c.mer_id='{$mer_id}' AND h.is_use=0 AND c.use_with_card=1 AND c.status<>0 AND c.end_time>'{$now_time}' AND (c.cate_name='all' OR c.cate_name='{$cate_name}')";
		$res = $this->query($sql);
		$coupon_list = array();
		foreach ($res as $row) {
			$coupon_list[] = array('coupon_id' => $row['id'], 'full_money' => floatval($row['order_money']), 'reduce_money' => floatval($row['discount']), 'end_time' => date('Y-m-d', $row['end_time']));
		}
		return $coupon_list;
	}


    //添加实体卡
    public function add_pythsical_id($pyhsical_id,$uid,$card_id){
        return M('Card_userlist')->where(array('uid'=>$uid,'id'=>$card_id))->setField('physical_id',$pyhsical_id);
    }

    public function use_money($uid,$mer_id,$money,$desc){
        $card_info = $this->get_card_by_uid_and_mer_id($uid,$mer_id);
        if($card_info['card_money']<$money){
            return array('error_code'=>true,'msg'=>'会员卡余额不足!');
        }
        $where['uid'] = $uid;
        $where['id'] = $card_info['id'];
        if(M('Card_userlist')->where($where)->setDec('card_money',$money)) {
            $data['card_id']   = $card_info['id'];
            $data['type']      = 2;
            $data['money_use'] = $money;
            $data['desc']      = $desc;
            $this->add_row($data);
            if ($card_info['wx_card_code'] != ''){
                $this->update_wx_card($card_info['wx_card_code'],$money*-1,0,$desc);
            }
            return array('error_code'=>false,'msg'=>'扣除会员卡余额成功!');
        }else{
            return array('error_code'=>true,'msg'=>'扣除会员卡余额失败!');
        }
    }

    public function user_score($uid,$mer_id,$score,$desc){
        $card_info = $this->get_card_by_uid_and_mer_id($uid,$mer_id);
        if($card_info['card_score']<$score){
            return array('error_code'=>true,'msg'=>'会员卡积分不足!');
        }
        $where['uid'] = $uid;
        $where['id'] = $card_info['id'];
        if(M('Card_userlist')->where($where)->setDec('card_score',$score)) {
            $data['card_id']   = $card_info['id'];
            $data['type']      = 2;
            $data['score_use'] = $score;
            $data['desc']      = $desc;
            $this->add_row($data);
            if ($card_info['wx_card_code'] != ''){
                $this->update_wx_card($card_info['wx_card_code'],0,$score*-1,$desc);
            }
            return array('error_code'=>false,'msg'=>'扣除会员卡积分成功!');
        }else{
            return array('error_code'=>true,'msg'=>'扣除会员卡积分失败!');
        }
    }


    public function use_give_money($uid,$mer_id,$money,$desc){
        $card_info = $this->get_card_by_uid_and_mer_id($uid,$mer_id);
        if($card_info['card_money_give']<$money){
            return array('error_code'=>true,'msg'=>'会员卡余额不足');
        }
        $where['uid'] = $uid;
        $where['id'] = $card_info['id'];
        if(M('Card_userlist')->where($where)->setDec('card_money_give',$money)){
            $data['card_id'] = $card_info['id'];
            $data['type'] = 2;
            $data['money_use'] = $money;
            $data['desc'] = $desc;
            $this->add_row($data);
            if ($card_info['wx_card_code'] != ''){
                $this->update_wx_card($card_info['wx_card_code'],$money*-1,0,$desc);
            }
            return array('error_code'=>false,'msg'=>'扣除会员卡赠送余额成功!');
        }else{
            return array('error_code'=>true,'msg'=>'扣除会员卡赠送余额失败');
        }
    }

    public function auto_reg_or_bind($mer_id,$openid,$card_id=0,$auto_get=0){
        $card_user = M('Card_userlist')->where(array('id'=>$card_id))->find();
        if($card_user['uid']>0){
            return array('error_code'=>true,'msg'=>'该会员卡已经被其他用户绑定了，您不能再绑定了');
        }
        $now_card = $this->where(array('mer_id' => $mer_id))->find();
        $now_user = M('User')->where(array('openid'=>$openid))->find();
        if(!empty($now_card)&&($now_card['auto_get']||$auto_get==1)){
            if(empty($now_user)){
                if ($now_card['auto_get']) {
                    $access_token_array = D('Access_token_expires')->get_access_token();
                    if (!$access_token_array['errcode']) {
                        import('ORG.Net.Http');
                        $http = new Http();
                        $return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token_array['access_token'] . '&openid=' . $openid . '&lang=zh_CN');
                        $userifo = json_decode($return, true);
                        $data_user = array(
                            'openid' => $userifo['openid'],
                            'union_id' => ($userifo['unionid'] ? $userifo['unionid'] : ''),
                            'nickname' => $userifo['nickname'],
                            'sex' => $userifo['sex'],
                            'province' => $userifo['province'],
                            'city' => $userifo['city'],
                            'avatar' => $userifo['headimgurl'],
                            'is_follow' => $userifo['subscribe'],
                            'source' => 'scan_mer_qrcode',
                        );
                        $reg_result = D('User')->autoreg_by_scan_merchant_qrcode($data_user);
                        //自动领卡
                        $now_user = D('User')->get_user($reg_result['msg']['uid']);
                        if($card_id){
                            if($card_id&&!M('Card_userlist')->where(array('uid'=>$now_user['uid'],'mer_id'=>$mer_id))->find()){
                                $data['add_time'] = time();
                                $data['uid'] = $now_user['uid'];
                                M('Card_userlist')->where(array('id'=>$card_id))->save($data);

                                if($now_user['openid']){
                                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                                    $now_merchant = M('Merchant')->where(array('mer_id'=>$mer_id))->find();
                                    $model->sendTempMsg('OPENTM200964573', array('href' => C('config.site_url').'/wap.php?g=Wap&c=My_card&a=merchant_card&mer_id='.$mer_id, 'wecha_id' =>$openid, 'first' => '您成功领取了商家【'.$now_merchant['name'].'】的会员卡', 'keyword1' =>$card_id, 'keyword2' => $now_user['nickname'],'keyword3'=>$now_user['phone'],'keyword4'=>date("Y年m月d日 H:i"), 'remark' => '感谢您的关注！'),$now_merchant['mer_id']);
                                }
                                return array('error_code'=>false,'msg'=>'会员绑定商家会员卡成功，卡号'.$card_id);
                            }else{
                                return array('error_code'=>true,'msg'=>'您已经绑定过该商家的会员卡了，不能再绑定了');
                            }
                        }else{
                            if(!$reg_result['error_code']){
                                return $this->auto_get($reg_result['msg']['uid'],$mer_id);
                            }
                        }
                    }
                }
            }else{
                if($card_id){
                    if($card_id&&!M('Card_userlist')->where(array('uid'=>$now_user['uid'],'mer_id'=>$mer_id))->find()){
                        $data['add_time'] = time();
                        $data['uid'] = $now_user['uid'];
                        M('Card_userlist')->where(array('id'=>$card_id))->save($data);

                        if($now_user['openid']){
                            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                            $now_merchant = M('Merchant')->where(array('mer_id'=>$mer_id))->find();
                            $model->sendTempMsg('OPENTM200964573', array('href' => C('config.site_url').'/wap.php?g=Wap&c=My_card&a=merchant_card&mer_id='.$mer_id, 'wecha_id' =>$openid, 'first' =>'您成功领取了商家【'.$now_merchant['name'].'】的会员卡', 'keyword1' =>$card_id, 'keyword2' => $now_user['nickname'],'keyword3'=>$now_user['phone'],'keyword4'=>date("Y年m月d日 H:i"), 'remark' => '感谢您的关注！'),$now_merchant['mer_id']);
                        }
                        return array('error_code'=>false,'msg'=>'会员绑定商家会员卡成功，卡号'.$card_id);
                    }else{
                        return array('error_code'=>true,'msg'=>'您已经绑定过该商家的会员卡了，不能再绑定了');
                    }
                }else{
                    return $this->auto_get($now_user['uid'],$mer_id);
                }
            }
        }
    }

    //获取分组用户
    public function get_user_by_gid_merid($gid,$mer_id){
        $where['c.mer_id']=$mer_id;
        $where['c.gid']=$gid;
        return M('Card_userlist')->field('c.id,c.uid,c.card_money,c.card_money_give,c.card_score,c.physical_id,c.add_time,c.status,c.gid,u.nickname,u.phone')->join('as c left join '.C('DB_PREFIX').'user as u ON c.uid=u.uid')->where($where)->select();
    }

    public function get_userlist_by_car_group($card_group_id){
        $where['c.gid']=array('in',$card_group_id);
        $where['c.uid'] = array('neq',0);
        $where['c.status'] = array('eq',1);
        $res = M('Card_userlist')->join('as c left join '.C('DB_PREFIX').'user as u ON c.uid=u.uid left join '.C('DB_PREFIX').'card_group g on c.gid = g.id')->where($where)->getField('c.id,c.uid,c.card_money,c.card_money_give,c.card_score,c.physical_id,c.add_time,c.status,c.gid,u.nickname,u.phone,g.name as group_name');
        foreach ($res as $re) {
            $tmp[$re['gid']]['group_name'] = $re['group_name'];
            $tmp[$re['gid']]['userlist'][] = $re;
        }
        return $tmp;

    }

    //派送记录，计划任务执行
    public function  add_send_log($mer_id=0,$group_id,$coupon_id,$uid=0){
        $data['mer_id'] = $mer_id;
        $data['type'] = 1; //0 平台 1 商家 2 用户
        if($uid>0){
            $data['type']=2;
            $data['uid'] = $uid;
        }
        $data['group_id'] = $group_id; //空为全部派送
        $data['coupon_id'] = $coupon_id;
        $data['status'] = 0;
        $data['dateline'] = $_SERVER['REQUEST_TIME'];
        if($id = M('Send_coupon_log')->add($data)){
            return array('error_code'=>false,'msg'=>'添加派送记录成功','id'=>$id);
        }else{
            return array('error_code'=>true,'msg'=>'添加派送记录失败');
        }
    }

    //用户所有的会员卡列表
    public function get_user_all_card($uid){
         $sql = 'SELECT c.card_id,c.bg,c.diybg,c.numbercolor,m.name,c.discount,cl.id as cardid,cl.card_money,cl.card_money_give,'
            .'m.pic_info,m.mer_id,m.logo,c.status as card_status ,cl.status as usercard_status FROM '
            .C('DB_PREFIX').'card_userlist `cl`  left join '
            .C('DB_PREFIX').'merchant m on m.mer_id  = cl.mer_id left join '
            .C('DB_PREFIX').'card_new `c` on m.mer_id = c.mer_id   where ( cl.uid = '.$uid.' AND m.status=1 AND  cl.status =1 AND c.status =1 ) group by cl.mer_id ';
        $res =  M('')->query($sql);

        foreach ($res as $v) {
            $tmp[$v['card_id']]['id'] = $v['cardid'];
            $tmp[$v['card_id']]['card_id'] = $v['card_id'];
            $tmp[$v['card_id']]['name'] = $v['name'];
            $tmp[$v['card_id']]['mer_id'] = $v['mer_id'];
            $tmp[$v['card_id']]['merchant_logo'] = $v['logo'];
            $tmp[$v['card_id']]['card_status'] = $v['card_status'];
            $tmp[$v['card_id']]['usercard_status'] = $v['usercard_status'];
            $tmp[$v['card_id']]['numbercolor'] = $v['numbercolor'];
            $img = explode(';',$v['pic_info']);
            $tmp[$v['card_id']]['mer_pic'] = str_replace(',','/',$img[0]);
			if(!empty($v['diybg'])){
               $tmp[$v['card_id']]['bg'] = $v['diybg'];
			}else{
                $tmp[$v['card_id']]['bg'] = $v['bg'];
			}
            $tmp[$v['card_id']]['discount'] = $v['discount'];
            $tmp[$v['card_id']]['money'] = $v['card_money']+$v['card_money_give'];
            $where['c.end_time'] = array('gt',time());
            $where['c.status'] = array('neq',0);
            $where['h.uid'] = $uid;
            $where['c.card_id'] = $v['card_id'];
            $where['h.is_use'] = 0;
            $tmp[$v['card_id']]['coupon_count'] = M('Card_new_coupon_hadpull')
                ->join('as h left join '.C('DB_PREFIX').'card_new_coupon as c on c.coupon_id =h.coupon_id  ')
                ->where($where)->count();
        }

        return $tmp;
    }

    //充值积分列表 线下
    public function offline_recharge_list($is_system){
        if($is_system){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'card_id') {
                $where['c.card_id'] = htmlspecialchars($_GET['keyword']);
            } elseif ($_GET['searchtype'] == 'name') {
                $where['us.nickname'] = array('like','%'.$_GET['keyword'].'%');
            } elseif ($_GET['searchtype'] == 'id') {
                $where['c.id'] =$_GET['keyword'];
            } elseif ($_GET['searchtype'] == 'phone') {
                $where['us.phone'] = $_GET['keyword'];
            } elseif ($_GET['searchtype'] == 'm_name') {
                $where['m.name'] = array('like','%'.$_GET['keyword'].'%');
            }
        }
        $where['c.type']= 1;
        $where['c.desc']= array('like','商家%');
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =" (c.time BETWEEN ".$period[0].' AND '.$period[1].")";

        }
        $count = M('Card_new_record')->join('as c left join '.C('DB_PREFIX').'card_userlist u ON c.card_id  = u.id  left join '.C('DB_PREFIX').'merchant m ON m.mer_id = u.mer_id  left join '.C('DB_PREFIX').'user us ON u.uid  = us.uid')->where($where)->count();
        $p = new Page($count, 20);
        $cardlist = M('Card_new_record')->field('c.*,u.id as cardid,u.uid,u.card_money,u.card_money_give,u.card_score,m.name,us.nickname,us.phone')->join('as c left join '.C('DB_PREFIX').'card_userlist u ON c.card_id  = u.id  left join '.C('DB_PREFIX').'merchant m ON m.mer_id = u.mer_id  left join '.C('DB_PREFIX').'user us ON u.uid  = us.uid')->where($where)->order('c.time DESC')->limit($p->firstRow,$p->listRows)->select();
        $pagebar = $p->show();
        return array('list'=>$cardlist,'pagebar'=>$pagebar);
    }

    //线上充值
    public function online_recharge_list($is_system){
        if($is_system){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }
        $where['o.paid'] = 1;
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'card_id') {
                $where['u.id'] = htmlspecialchars($_GET['keyword']);
            } elseif ($_GET['searchtype'] == 'name') {
                $where['us.nickname'] = array('like','%'.$_GET['keyword'].'%');
            } elseif ($_GET['searchtype'] == 'orderid') {
                $where['o.orderid'] = $_GET['keyword'];
            }elseif ($_GET['searchtype'] == 'phone') {
                $where['us.phone'] = $_GET['keyword'];
            } elseif ($_GET['searchtype'] == 'm_name') {
                $where['m.name'] = array('like','%'.$_GET['keyword'].'%');
            }
        }
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =" (c.add_time BETWEEN ".$period[0].' AND '.$period[1].")";

        }
        $count = M('Card_new_recharge_order')->join('as c left join '.C('DB_PREFIX').'plat_order o ON c.order_id = o.business_id AND o.business_type = "card_new_recharge" as c  left join '.C('DB_PREFIX').'card_userlist u ON c.uid  = u.uid AND c.mer_id = u.mer_id left join '.C('DB_PREFIX').'merchant m ON m.mer_id = c.mer_id  left join '.C('DB_PREFIX').'user us ON u.uid  = us.uid')->where($where)->count();

        $p = new Page($count, 20);
        $cardlist = M('Card_new_recharge_order')->field('c.*,u.id as cardid,u.uid,u.card_money,u.card_money_give,u.card_score,m.name,us.nickname,us.phone')->join('as c left join '.C('DB_PREFIX').'plat_order o ON c.order_id = o.business_id AND o.business_type = "card_new_recharge" left join '.C('DB_PREFIX').'card_userlist u ON c.uid  = u.uid AND c.mer_id = u.mer_id left join '.C('DB_PREFIX').'merchant m ON m.mer_id = c.mer_id  left join '.C('DB_PREFIX').'user us ON u.uid  = us.uid')->where($where)->order('c.order_id DESC')->limit($p->firstRow,$p->listRows)->select();

        $pagebar = $p->show();
        return array('list'=>$cardlist,'pagebar'=>$pagebar);
    }

    //更新微信会员卡
    public function update_wx_card($wx_card_code,$money=0,$score=0,$money_des='',$score_des=''){
        $user_info = M('Card_userlist')->where(array('wx_card_code'=>$wx_card_code))->find();
        $card = $this->get_card_by_mer_id($user_info['mer_id']);
        import('ORG.Net.Http');
        $http = new Http();
        $mode = D('Access_token_expires');
        $res = $mode->get_access_token();
        $url="https://api.weixin.qq.com/card/membercard/updateuser?access_token=".$this->$res['access_token'];

        $arr['code']=$wx_card_code;
        $arr['card_id']=$card['wx_cardid'];
        if($money!=0){
//            $arr['add_balance']=$money*100;
//            $arr['balance']=($user_info['card_money']+$user_info['card_money_give'])*100;
//            $arr['record_balance']=$money_des;
            $now_user = D('User')->get_user($user_info['uid']);
            $href = C('config.site_url').'/wap.php?c=My_card&a=merchant_card&mer_id='.$user_info['mer_id'];
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $card['name'].'会员卡余额变动', 'work' => $money_des, 'remark' => '\n请点击查看详细信息！'));
        }
        if($score!=0){
            $arr['bonus']=$user_info['card_score'];
            $arr['add_bonus']=$score;
            $arr['record_bonus']=$score_des;
        }
        $mode = D('Access_token_expires');
        $res = $mode->get_access_token();
        $url="https://api.weixin.qq.com/card/membercard/updateuser?access_token=".$res['access_token'];
        $res =   $http->curlPost($url,json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}

