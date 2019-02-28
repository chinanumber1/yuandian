<?php
class Card_new_couponModel extends Model{
    public function get_qrcode($id){
        $condition_store['coupon_id'] = $id;
        $qrcode_id = $this->field('`coupon_id`,`qrcode_id`')->where($condition_store)->find();
        if(empty($qrcode_id)){
            return false;
        }
        return $qrcode_id;
    }
    //保存优惠券二维码
    public function save_qrcode($id,$qrcode_id){
        $coupon_where['coupon_id'] = $id;
        $data_coupon['qrcode_id'] = $qrcode_id;
        if($this->where($coupon_where)->data($data_coupon)->save()){
            return(array('error_code'=>false));
        }else{
            return(array('error_code'=>true,'msg'=>'保存二维码至平台优惠券失败！请重试。'));
        }
    }

	public function get_coupon($coupon_id){
		$coupon = $this->field(true)->where(array('coupon_id'=>$coupon_id))->find();
		return $coupon;
	}


    //根据已领表id获取优惠券信息,不筛选时间等条件 链表查询
    public function get_coupon_info($coupon_id){
        $where['h.id']=$coupon_id;
        $where['h.is_use']= 0;
        $where['l.status']= 1;
        $res = M('Card_new_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'card_new_coupon c ON h.coupon_id=c.coupon_id')->join(C('DB_PREFIX').'card_userlist l ON h.uid = l.uid')->field('h.id,c.coupon_id,c.name,c.des,c.des_detial,c.had_pull,c.num,c.limit,c.use_limit,c.order_money,c.discount ,c.discount as price')->where($where)->find();
        return $res;
    }

    //获取该手机号码领取的优惠券数量
    public function get_coupon_count_by_uid($coupon_id,$uid){
        $where['coupon_id'] = $coupon_id;
        $where['uid'] = $uid;
        return M('Card_new_coupon_hadpull')->where($where)->count();

    }
    public function get_coupon_by_uid($uid,$mer_id){
        $where['h.uid'] = $uid;
        $where['c.mer_id'] = $mer_id;
        $where['h.is_use']=0;
        $where['c.end_time'] = array('gt',time());
        $where['c.status'] = array('neq',0);  //状态正常
        $res = M('Card_new_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'card_new_coupon as c ON h.coupon_id = c.coupon_id')->where($where)->select();

        return $res;

    }

    public function get_coupon_detail_by_uid($uid){
        $where['uid'] = $uid;
        $res = M('Card_new_coupon_hadpull')->field('c.name,c.discount,c.start_time,c.end_time,c.num,c.des')->join('as h left join '.C('DB_PREFIX').'card_new_coupon as c ON h.coupon_id = c.coupon_id')->where($where)->select();
        return $res;

    }

    //获取该用户领取的优惠券
    public function get_coupon_category_by_uid($uid){
        $where['uid'] = $uid;
        return M('Card_new_coupon_hadpull')->where($where)->group('coupon_id')->select();

    }

    public function get_coupon_by_id($id){
        $where['c.end_time'] = array('gt',time());
        $where['c.status'] = array('neq',0);  //状态正常
        $where['h.id']=$id;
        $res = M('Card_new_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'card_new_coupon c ON h.coupon_id=c.coupon_id')->field('h.id,c.coupon_id,c.end_time,h.is_use ,c.discount as price')->where($where)->find();
        return $res;
    }

    //获取适用不同分类的优惠券 接口
    public function get_coupon_list_by_type_merid($type,$mer_id,$cat_id,$limit=6,$is_new=-1){
        if(!empty($type)){
            $where['cate_name'] = array(array('eq',$type),array('eq','all'), 'or');
        }
        if($is_new!=-1){
            $where['allow_new'] = $is_new;
        }
        $where['end_time'] = array('gt',time());
        $where['status'] = 1;
		$where['mer_id'] = $mer_id;
        $where['start_time'] = array('lt',time());
        $where['_string'] = 'had_pull<>num';
        $res = $this->where($where)->order('allow_new DESC,discount DESC')->limit($limit)->getField('coupon_id,name,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new,limit,wx_cardid,jsapi_ticket,wx_ticket_addtime,cardsign');

        foreach($res as $key=>&$v){
            $v['cate_id'] = unserialize($v['cate_id']);
            if($cat_id!=0){
                if($v['cate_id']['cat_id']!=$cat_id) {
                    unset($res[$key]);
                    continue;
                }
            }
            $v['url'] = C('config.config_site_url').'/coupon/'.$v['coupon_id'].'.html';
        }
        return $res;
    }

    //获取可以领取的优惠券种类
    public function get_coupon_list($param=array()){
        $where['end_time'] = array('gt',time());
        $where['status'] = 1;
        $where['start_time'] = array('lt',time());
        if($param){
           $where = array_merge($where,$param);
        }
        $res = $this->where($where)->order('status ASC,allow_new DESC,discount DESC')
            ->getField('coupon_id,name,img,had_pull,num,limit,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new');
        return $res;
    }

    //根据商家id获取可领取的优惠券
    public function get_coupon_list_by_merid($mer_id=0,$type=0){
        $where['end_time'] = array('gt',time());
        $where['start_time'] = array('lt',time());
        $where['status'] = 1;
        $where['send_type'] = array('neq',2);
        $mer_id && $where['mer_id'] = $mer_id;
        if($type){
            $where['_string'] = 'num > had_pull';
        }
        if($mer_id){

            $res = $this->where($where)->order('status ASC,allow_new DESC,discount DESC,add_time DESC')
                ->getField('coupon_id,name,platform,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,last_time,status,allow_new,wx_cardid,wx_ticket_addtime,cardsign,limit');
        }else{
            unset(  $where['status']);
            $where['c.status'] = 1;
            $where['cn.auto_get'] = 1;
            $res = $this->join('as c LEFT JOIN '.C('DB_PREFIX').'merchant as m ON m.mer_id=c.mer_id LEFT JOIN '.C('DB_PREFIX').'card_new cn ON cn.mer_id = m.mer_id')->where($where)->order('c.status ASC,c.allow_new DESC,c.discount DESC,c.add_time DESC')
                ->getField('c.coupon_id,c.name,c.platform,c.img,c.had_pull,c.num,c.des,c.cate_name,c.cate_id,c.discount,c.order_money,c.start_time,c.end_time,c.last_time,c.status,c.allow_new,c.wx_cardid,c.wx_ticket_addtime,c.cardsign,c.limit,m.name as merchant_name');

        }
        return $res;
    }
	//根据商家id获取可领取的优惠券
    public function get_coupon_list_by_merid_diypage($mer_id){
        $where['end_time'] = array('gt',time());
        $where['start_time'] = array('lt',time());
        $where['status'] = 1;
        $where['mer_id'] = $mer_id;
        //$where['_string'] = 'num > had_pull';
		
		if ($_POST['keyword']){
			$where['name'] = array('like','%'.$_POST['keyword'].'%');
		}
		
		$count = $this->where($where)->count();
		import('@.ORG.diypage');
		$Page = new Page($count,8);
		
        $res = $this->where($where)->order('status ASC,allow_new DESC,discount DESC,add_time DESC')->limit($Page->firstRow.','.$Page->listRows)
            ->getField('coupon_id,name,platform,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new,wx_cardid,wx_ticket_addtime,cardsign');
        return array('coupon_list'=>$res,'page_bar'=>$Page->show());
    }
    public function get_coupon_list_by_merid_and_auto_get($mer_id,$num=0){
        $where['end_time'] = array('gt',time());
        $where['start_time'] = array('lt',time());
        $where['status'] = array('neq',0);
        $where['auto_get'] = 1;
        $where['mer_id'] = $mer_id;
        //$where['_string'] = 'num > had_pull';
        $res = $this->where($where)->order('status ASC,allow_new DESC,discount DESC,add_time DESC')
            ->getField('coupon_id,name,platform,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new,wx_cardid,wx_ticket_addtime,cardsign');
        return $res;
    }

    public function  get_coupon_list_by_ids($ids,$can_hadpull=false){
        $where['coupon_id']=array('in',$ids);
        $res = $this->where($where)->getField('coupon_id,name,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new');

		foreach($res as $key=>&$v){
			if($can_hadpull){
				if($v['start_time']>time()||$v['end_time']<time()||$v['status']==0){
					unset($res[$key]);
				}
			}
			$v['order_money_txt'] = getFormatNumber($v['order_money']);
			$v['discount_txt'] = getFormatNumber($v['discount']);
			$v['img'] = C('config.site_url').$v['img'];
			$v['start_time_txt'] = date('Y.m.d',$v['start_time']);
			$v['end_time_txt'] = date('Y.m.d',$v['end_time']);
		}

        return $res;
    }
    public function get_user_coupon_list($uid,$mer_id,$type='',$order_id=0 ){
        //$where['c.end_time'] = array('gt',time());
        $where['h.uid'] = $uid;
        $where['c.mer_id'] = $mer_id;
        $where['c.is_shop'] = array('neq',2);//仅店内
        $where['_string'] = '(c.status <> 0 AND c.status<>4)';   //状态正常
        // $where['h.is_use'] = 0;
        $n = 1;
        $cate_platform = $this->cate_platform();
        $res = M('Card_new_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'card_new_coupon c ON h.coupon_id=c.coupon_id')->field('h.id,h.is_use,c.cate_name as type,c.*')->order('h.is_use ASC ,c.add_time DESC')->where($where)->select();
        foreach($res as &$v){
            if(empty($v['uid'])){
                M('Card_new_coupon_hadpull')->where(array('id'=>$v['id']))->setField('uid',$uid);
            }
            if($v['is_use']==1&&$n==1){
                $v['line']=1;
                $n++;
            }
            $v['platform']=unserialize($v['platform']);
            if(!empty($v['cate_id'])) {
                $v['cate_id'] = unserialize($v['cate_id']);
            }
            foreach($v['platform'] as &$vv){
                $vv=$cate_platform['platform'][$vv];
            }
            if($v['cate_name']!='all') {
                $v['cate_name'] = $cate_platform['category'][$v['cate_name']];
            }
            $v['platform']=trim(implode(',',$v['platform']),',');
            if($v['end_time']<$_SERVER['REQUEST_TIME']&&$v['is_use']!=1){
                $v['is_use'] = 2;
            }
        }
        return $res;
    }

    public function get_user_all_coupon_list($uid,$is_use=''){
        //$where['c.end_time'] = array('gt',time());
//        $where['c.status'] = array('neq',0);
        $where['h.uid'] = $uid;
        $where['_string'] = '(c.status <> 0 AND c.status<>4)';   //状态正常
        if(!empty($is_use)){
            if($is_use==1){
                $where['h.is_use'] = 0;
                $where['c.end_time'] = array('gt',time());
            }
        }
        $res =  M('Card_new_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'card_new_coupon as c ON  h.coupon_id = c.coupon_id')
            ->where($where)->order('h.is_use ASC ,c.add_time DESC')->select();
        $n = 1;
        $cate_platform = $this->cate_platform();
        foreach($res as &$v){
            if($v['is_use']==1&&$n==1){
                $v['line']=1;
                $n++;
            }
            $v['platform']=unserialize($v['platform']);
            if(!empty($v['cate_id'])) {
                $v['cate_id'] = unserialize($v['cate_id']);
            }
            foreach($v['platform'] as &$vv){
                $vv=$cate_platform['platform'][$vv];
            }
            if($v['cate_name']!='all') {
                $v['cate_name'] = $cate_platform['category'][$v['cate_name']];
            }
            $v['platform']=trim(implode(',',$v['platform']),',');
            if($v['end_time']<$_SERVER['REQUEST_TIME']&&$v['is_use']!=1){
                $v['is_use'] = 2;
            }
        }
        return $res;
    }

    //获取当前能用的优惠券列表
    public function get_noworder_coupon_list($now_order,$order_type,$platform,$business_type=''){
		if($business_type){
			$order_type = $business_type;
			$now_order['total_money'] = $now_order['total_money'];
		}
        if($order_type=='shop' && $now_order['can_discount_money']>0){
            $now_order['total_money'] = $now_order['can_discount_money'];
        }

        if(!D('Card_new')->where(array('mer_id'=>$now_order['mer_id'],'status'=>1))->find()){
            return array();
        }
        if($order_type == 'group'){
            $table = 'group';
          //  $where['order_money'] = array('ELT',$now_order['total_money']);
        }else if($order_type == 'meal' || $order_type == 'food' || $order_type == 'foodPad' || $order_type == 'takeout'||$order_type=='foodshop'){
            $table = 'meal';
          //  $where['order_money'] = array('ELT',$now_order['total_money']);
        }else if($order_type == 'appoint'){
            $table = 'appoint';
           // $where['order_money'] = array('ELT',$now_order['order_total_money']);
        }else if($order_type == 'shop' || $order_type == 'mall'){
            $table = 'shop';
            //$where['order_money'] = array('ELT',$now_order['total_money']);
        }else if($order_type == 'store'){
            $table = 'store';
           // $where['order_money'] = array('ELT',$now_order['total_money']);
        }else if($order_type == 'balance-appoint'){
            $table = 'appoint';
           // $where['order_money'] = array('ELT',$now_order['total_money']);
        }else{
            return array();
        }

        $where['order_money'] = array('ELT',$now_order['total_money']);
        if($order_type!='store'){
            $order_cate = D(ucfirst($table).'_order')->get_order_cate($now_order['order_id']);
        }else{
            $order_cate =array('store');
        }

        $where['c.end_time'] = array('gt',time());
        $where['c.start_time'] = array('lt',time());
        $where['c.status'] = array('in','1,3');  //状态正常
        $where['h.is_use'] = 0;  //状态正常
        $where['l.status'] = 1;  //状态正常
	    $where['l.mer_id'] = $now_order['mer_id'];  //状态正常
        $where['h.uid'] = $now_order['uid'];
        $where['c.mer_id'] =  $now_order['mer_id'];
        $where['_string'] = "((c.cate_name='".$table."') OR (c.cate_name ='all')) AND (c.use_with_card=1 OR ( c.use_with_card=0 AND (cn.discount=0 OR cn.discount =10) ))";
        if(intval($now_order['store_id'])){
            $where['_string'].=" AND (c.store_id ='' OR c.store_id LIKE '%".intval($now_order['store_id'])."%')  ";
        }
        $res = M('Card_new_coupon_hadpull')
            ->join('as h left join '.C('DB_PREFIX').'card_new_coupon c ON h.coupon_id=c.coupon_id')
            ->join(C('DB_PREFIX').'card_userlist l ON h.uid = l.uid ')
            ->join( C('DB_PREFIX').'card_new as cn ON cn.mer_id = l.mer_id')
            ->field('h.id,c.*')->where($where)
            ->group('h.id')
            ->order('h.is_use ASC,c.discount DESC ,c.add_time DESC')->select();

        foreach($res as $key=>&$v){
            $flag = false;
            $v['platform']= unserialize($v['platform']);
            $v['cate_id'] = empty($v['cate_id'])?'0':unserialize($v['cate_id']);
            $store_arr = explode(',',$v['store_id']);
            if($v['store_id']!='' && !in_array($now_order['store_id'],$store_arr)){
                unset($res[$key]);
                continue;
            }
            foreach($v['platform'] as $vp){
                if($vp==$platform) {
                   $flag = true;
                }
            }
            if(!$flag){
                unset($res[$key]);
                continue;
            }
            if(!empty($v['cate_id'])) {
                $cate_arr1 = array_diff($v['cate_id'],$order_cate);
                $cate_arr2 = array_diff($v['cate_id'],$order_cate);
                if(!empty($cate_arr1) || !empty($cate_arr2)){
                    unset($res[$key]);
                    continue;
                }
            }
            $cate_platform = $this->cate_platform();
            foreach($v['platform'] as &$vv){
                $vv=$cate_platform['platform'][$vv];
            }
            if($v['cate_name']!='all') {
                $v['cate_name'] = $cate_platform['category'][$v['cate_name']];
            }
            $v['platform']=trim(implode(',',$v['platform']),',');
        }

	
        return $res;
    }

    public function cate_platform(){
        $category=array('group'=>C('config.group_alias_name'),
            'meal'=>C('config.meal_alias_name'),
            'appoint'=>C('config.appoint_alias_name'),
            'shop'=>C('config.shop_alias_name'),
            'store'=>C('config.cash_alias_name'),
            'all'=>'全品类通用',
        );
		
        if(C('config.wxapp_url')){
            $category['wxapp']='微信营销';
        }
        $color_list =  D('System_coupon')->color_list();
        $platform=array('wap'=>'移动网页','app'=>'App','weixin'=>'微信');
        return array('category'=>$category,'platform'=>$platform,'color_list'=>$color_list);
    }

    //检查平台优惠券状态
    public function check_coupon($record_id, $mer_id, $uid,$refund = false)
    {
        $now_merchant = M('Merchant')->field(true)->where(array('mer_id'=>$mer_id,'status'=>'1'))->find();
        if(empty($now_merchant)){
            return array('error_code' => 1, 'msg' => '商家暂时歇业');
        }
        $condition_coupon_record = array('id' => $record_id, 'wecha_id' => $uid);
        if(empty($refund)){
            $condition_coupon_record['is_use'] = '0';
        }else{
            $condition_coupon_record['is_use'] = '1';
        }
        $now_coupon_record = M("Card_new_coupon_hadpull")->field(true)->where($condition_coupon_record)->find();
        if (empty($now_coupon_record)) {
            return array('error_code' => 1, 'msg' => '优惠券不可用');
        }
        $now = time();
        $now_coupon = $this->field(true)->where(array('coupon_id' => $now_coupon_record['coupon_id'],'start_time'=>array('lt', $now),'end_time'=>array('gt', $now)))->find();
        if (empty($now_coupon_record)) {
            return array('error_code' => 1, 'msg' => '优惠券已被商家取消了');
        }
        return array('error_code' => 0, 'msg' => '优惠券可用','coupon'=>$now_coupon);
    }

    //消费平台优惠券记录在表中 record_id 是 hadpull 表中的id
    public function user_coupon($record_id,$order_id,$order_type, $mer_id, $uid)
    {
        $result = $this->check_coupon($record_id, $mer_id, $uid);
        if ($result['error_code']) {
            return $result;
        }
        $now = time();
        $result_ = M("Card_new_coupon_hadpull")->where(array('id' => $record_id))->save(array('use_time' => $now, 'is_use' => '1'));
        if (empty($result_)) {
            return array('error_code' => 1, 'msg' => '优惠券使用失败');
        }

        $now_coupon_record = M("Card_new_coupon_hadpull")->field(true)->where(array('id' => $record_id))->find();

        if($result['coupon']['is_wx_card']){
            import('ORG.Net.Http');
            $mode = D('Access_token_expires');
            $res = $mode->get_access_token();
            $wx_date['code'] = $now_coupon_record['wx_card_code'];
            $return = httpRequest('https://api.weixin.qq.com/card/code/consume?access_token=' . $res['access_token'], 'post', json_encode($wx_date, JSON_UNESCAPED_UNICODE));
            $return = json_decode($return[1], true);

        }
        $arr = array();
        $arr['coupon_id']  	= $now_coupon_record['coupon_id'];
        $arr['order_type']	= $order_type;
        $arr['order_id']	= $order_id;
        $arr['hadpull_id']	= $record_id;
        $arr['uid']	= $uid;
        $arr['num']	= 1;
        $arr['use_time']	= $now;

        M('Card_new_coupon_use_list')->add($arr);
        return array('error_code' => 0, 'msg' => '优惠券使用成功');
    }

    //领取方法 error_code :1=>优惠券不存在 2 =>过期，3 领完了 4只允许新用户 5不能在领取了
    public function had_pull($coupon_id,$uid,$card_code='',$not_wxapp_hadpull=false){
        $where['coupon_id']=$coupon_id;
        $coupon = $this->get_coupon($coupon_id);
        $is_new = D('User')->check_new($uid,$coupon['cate_name']);

        if(empty($coupon)){
            return array('error_code'=>1,'coupon'=>$coupon);
        }else if($coupon['allow_new']&&!$is_new){
            return array('error_code'=>4,'coupon'=>$coupon);
        }else if($coupon['end_time']<time()){
            if($coupon['status']!=4){
                $this->field(true)->where($where)->setField('status',2);
            }
            return array('error_code'=>2,'end_time'=>$coupon,'coupon'=>$coupon);
        }else if($coupon['status']==0){
            return array('error_code'=>1,'coupon'=>$coupon);
        }else if($coupon['status']==2){
            return array('error_code'=>2,'coupon'=>$coupon);
        }else if($coupon['num']==$coupon['had_pull']||$coupon['status']==3){
            if($coupon['status']!=4) {
                $this->field(true)->where($where)->setField('status', 3);
            }
            return array('error_code'=>3,'coupon'=>$coupon);
        }else{
            $hadpull = M('Card_new_coupon_hadpull');
            $hadpull_count = $hadpull->where(array('uid'=>$uid,'coupon_id'=>$coupon_id))->count();
            if($hadpull_count<$coupon['limit']) {
                if($not_wxapp_hadpull && $coupon['wx_cardid']){
                    return array('error_code'=>0,'coupon'=>$coupon);
                }
                if ($this->where($where)->setInc('had_pull')) {
                    $this->where($where)->setField('last_time',$_SERVER['REQUEST_TIME']);
                    $data['coupon_id'] = $coupon_id;
                    $data['uid'] = $uid;
                    $data['num'] = 1;
                    $data['receive_time'] = time();
                    $data['status'] = 0;
                    if($card_code){
                        $data['wx_card_code']  = $card_code;
                    }
                    $data['uid']  = $uid;
                    $coupon = $this->get_coupon($coupon_id);
                    if(!M('Card_userlist')->where(array('mer_id'=>$coupon['mer_id'],'uid'=>$uid))->find()){
                        $result = D('Card_new')->auto_get($uid, $coupon['mer_id']);
                        //return array('error_code'=>1,'coupon'=>$coupon);
                    }
                    if ($hadpull_id = $hadpull->add($data)) {
                        if($now_user = M('User')->where(array('uid'=>$uid))->find()){
                            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                            $now_merchant = M('Merchant')->where(array('mer_id'=>$coupon['mer_id']))->find();
                            $cate_platform = $this->cate_platform();
                            $res = $model->sendTempMsg('TM00251', array('href' => C('config.site_url').'/wap.php?g=Wap&c=My_card&a=merchant_card&mer_id='.$coupon['mer_id'], 'wecha_id' => $now_user['openid'], 'first' => '您成功领取了商家【'.$now_merchant['name'].'】的'.$cate_platform['category'][$coupon['cate_name']].'优惠券', 'toName' => $now_user['nickname'], 'gift' => '优惠券 【'.$coupon['name'].'】，满'.$coupon['order_money'].'减'.$coupon['discount'],'time'=>date("Y年m月d日 H:i"), 'remark' => '有效期：'.date("Y-m-d",$coupon['start_time']).' 至 '.date("Y-m-d",$coupon['end_time'])),$coupon['mer_id']);
                        }
                        $coupon['has_get'] = $hadpull_count+1;
                        return array('error_code'=>0,'coupon'=>$coupon,'hadpull_id'=>$hadpull_id);
                    }
                } else {
                    return array('error_code'=>1,'coupon'=>$coupon);
                }
            }else{
                return array('error_code'=>5,'coupon'=>$coupon);
            }
        }
    }

    //自动领优惠券
    public function auto_get_coupon($mer_id,$uid){
        $coupon_list = $this->get_coupon_list_by_merid_and_auto_get($mer_id);
        foreach ($coupon_list as $item) {
            $this->had_pull($item['coupon_id'],$uid);
        }
        return true;
    }

    //减少卡券库存
    public function decrease_sku($add,$less,$coupon_id){
        $now_coupon  =$this->where(array('coupon_id'=>$coupon_id))->find();
        if(!$now_coupon['is_wx_card']){
            return ;
        }
        import('ORG.Net.Http');
        $res = D('Access_token_expires')->get_access_token();
        //修改库存
        $wx_data['card_id'] = $now_coupon['wx_cardid'];
        $wx_data['increase_stock_value'] = $add;
        $wx_data['reduce_stock_value'] = $less;
        $update_wx_card = httpRequest('https://api.weixin.qq.com/card/modifystock?access_token='.$res['access_token'],'post',json_encode($wx_data,JSON_UNESCAPED_UNICODE));
        $update_wx_card = json_decode($update_wx_card[1],true);
        $errorms = $update_wx_card['errmsg'];
        return $errorms;
    }

    //根据分类获取优惠券
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

    //获取还能领取多少优惠券
    public function get_can_had_coupon_by_params($uid, $mer_id, $cate_name)
    {
        $now_time = time();
        $is_new = D('User')->check_new($this->user_session['uid'],$cate_name);
        $sql = "SELECT c.coupon_id,count(h.id) as had FROM " . C('DB_PREFIX') . "card_new_coupon_hadpull AS h LEFT JOIN " . C('DB_PREFIX') . "card_new_coupon AS c  ON h.coupon_id = c.coupon_id WHERE h.uid='{$uid}' AND c.mer_id='{$mer_id}' AND c.use_with_card=1 AND c.status=1 AND c.end_time>'{$now_time}' AND (c.cate_name='all' OR c.cate_name='{$cate_name}') GROUP BY c.coupon_id";
        $sql2 = "SELECT * FROM  " . C('DB_PREFIX') . "card_new_coupon WHERE mer_id='{$mer_id}' AND use_with_card=1 AND status=1 AND end_time>'{$now_time}' AND (cate_name='all' OR cate_name='{$cate_name}')";
        $had_pull_num = M('')->query($sql);
        $coupon_list = M('')->query($sql2);
        foreach($had_pull_num as $h){
            $had[$h['coupon_id']] = $h['had'];
        }
        $n = 0;
        foreach($coupon_list as $c){
            if($c['all_new']&&!$is_new){
                continue;
            }
            $num = $c['num']-$c['had_pull'];
            $can_get = $c['limit']>$num?$num:$c['limit'];
            $n+=empty($had[$c['coupon_id']])?$can_get:$can_get-$had[$c['coupon_id']];
        }
        return $n;
    }


    public function send_coupon_by_id($coupon_id,$uid){
        $where['coupon_id']=$coupon_id;
        $coupon = $this->get_coupon($coupon_id);
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($uid,$coupon['mer_id']);
		if(empty($card_info)){
            D('Card_new')->auto_get($uid,$coupon['mer_id']);
        }
        if($coupon['num']==$coupon['had_pull']||$coupon['status']==3){
            if($coupon['status']!=4) {
                $this->field(true)->where($where)->setField('status', 3);
            }
            return array('error'=>3,'msg'=>'优惠券已领完');
        }else{
            $hadpull = M('Card_new_coupon_hadpull');
            if ($this->where($where)->setInc('had_pull')) {
                $this->where($where)->setField('last_time',$_SERVER['REQUEST_TIME']);
                $data['coupon_id'] = $coupon_id;
                $data['num'] = 1;
                $data['receive_time'] =$_SERVER['REQUEST_TIME'];
                $data['status'] = 0;
                $data['uid']  = $uid;
                //$coupon = $this->get_coupon($coupon_id);
                if ($hadpull->add($data)) {
                    if($now_user = M('User')->where(array('uid'=>$uid))->find()){
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $cate_platform = $this->cate_platform();
                        $model->sendTempMsg('TM00251', array('href' =>C('config.site_url').'/wap.php?g=Wap&c=My_card&a=merchant_card&mer_id='.$coupon['mer_id'], 'wecha_id' => $now_user['openid'], 'first' =>  '您成功领取了'.$cate_platform['category'][$coupon['cate_name']].'优惠券', 'toName' => $now_user['nickname'], 'gift' => $coupon['name'],'time'=>date("Y年m月d日 H:i"), 'remark' => '有效期：'.date("Y-m-d",$coupon['start_time']).' 至 '.date("Y-m-d",$coupon['end_time'])),$coupon['mer_id']);
                    }
                    return array('error'=>0,'msg'=>'领取优惠券成功,优惠券【'.$coupon['name'].'】');
                }
            } else {
                return array('error'=>1,'msg'=>'领取失败');
            }

        }
    }

    public function is_shop_coupon($store_id, $mer_id)
    {
        $res = $this->field('discount, store_id')->where(array('is_shop' => 1, 'mer_id' => $mer_id))->select();
        $tmp = array();
        foreach ($res as $re) {
            $store_ids = explode(',',$re['store_id']);
            if(in_array($store_id,$store_ids)){
                $tmp[] = $re;
            }
        }
        $min = 0;
        $max = 0;
        $str = '';
        foreach ($tmp as $t) {
            $min = min($min, floatval($t['discount']));
            $max = max($max, floatval($t['discount']));
        }
        if ($min > 0 && $max > 0) {
            if ($min == $max) {
                $str = '进店可领' . $max . '元商家优惠券';
            } else {
                $str = '进店可领' . $min . '-' . $max . '元商家优惠券';
            }
        }
        return $str;
    }


}