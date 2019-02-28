<?php
class System_couponModel extends Model{
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
        $res = M('System_coupon_hadpull')
            ->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id=c.coupon_id')
            ->field('h.id,c.coupon_id,c.name,c.des,c.des_detial,c.had_pull,c.num,c.limit,c.use_limit,c.order_money,c.discount as price,c.discount,c.is_discount,c.discount_value')
            ->where($where)->find();
        $res['discount_money']  = $res['discount'];
        return $res;
    }

    //获取该手机号码领取的优惠券数量
    public function get_coupon_count_by_phone($coupon_id,$phone){
        $where['coupon_id'] = $coupon_id;
        $where['phone'] = $phone;
        return M('System_coupon_hadpull')->where($where)->count();

    }

    //获取该手机号码领取的优惠券数量
    public function get_coupon_category_by_phone($uid){
        $where['uid'] = $uid;
        return M('System_coupon_hadpull')->where($where)->group('coupon_id')->select();

    }

    public function get_coupon_by_id($id){
        $where['c.end_time'] = array('gt',time());
        $where['c.status'] = 1;  //状态正常
        $where['h.id']=$id;
        $res = M('System_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id=c.coupon_id')->field('h.id,c.coupon_id,h.phone,c.end_time,h.is_use ,c.discount as price,c.discount,c.is_discount,c.discount_value')->where($where)->find();
        return $res;
    }

    //获取适用不同分类的优惠券 接口
    public function get_coupon_list_by_type($type,$cat_id,$limit=6,$is_new=-1){
        if(!empty($type)){
            $where['cate_name'] = array(array('eq',$type),array('eq','all'), 'or');
        }
        if($is_new!=-1){
            $where['allow_new'] = $is_new;
        }
        $where['end_time'] = array('gt',time());
        $where['status'] = 1;
        $where['start_time'] = array('lt',time());
        $res = $this->where($where)->order('allow_new DESC,discount DESC')->limit($limit)->getField('coupon_id,name,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new,limit,is_discount,discount_value');

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
    public function get_coupon_list($where =array()){
        $where['end_time'] = array('gt',time());
        $where['status'] =  1;
        $where['_string'] && krsort($where);//将_string 排在后面
        //$where['start_time'] = array('lt',time());
        $res = $this->where($where)->order('coupon_id DESC,status ASC,allow_new DESC,discount DESC')->getField('coupon_id,name,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,last_time,status,allow_new,platform,wx_cardid,wx_ticket_addtime,cardsign,limit,is_discount,discount_value');
        return $res;
    }

    public function weixin_send($money,$uid){
        //if($money>=C('config.weixin_send_money')){
        $coupon_list = explode(',',C('config.weixin_send_coupon_list'));
        if(empty($coupon_list)){
            return  array('error'=>1,'msg'=>'没有微信派发的优惠券');
        }
        foreach ($coupon_list as $item) {
            $this->had_pull($item,$uid);
        }
        //}

    }


    //is_num 要求领取数量限制
    public function send_coupon_by_id($coupon_id,$uid,$is_num=false,$href='',$order_id=0){
        $where['coupon_id']=$coupon_id;
        $coupon = $this->get_coupon($coupon_id);
        if($coupon['rand_send_num']>0 && $coupon['had_pull']>=$coupon['rand_send_num'] && !$is_num){
            return array('error'=>1,'msg'=>'随机已经派发完了');
        }
        if(($coupon['num']==$coupon['had_pull']||$coupon['status']==3) && !$is_num){
            $this->field(true)->where($where)->setField('status',3);
            return array('error'=>3,'msg'=>'优惠券已领完');
        }else{
            $hadpull = M('System_coupon_hadpull');
            if ($this->where($where)->setInc('had_pull')) {
                $this->where($where)->setField('last_time',$_SERVER['REQUEST_TIME']);
                $data['coupon_id'] = $coupon_id;
                $data['num'] = 1;
                $data['receive_time'] =$_SERVER['REQUEST_TIME'];
                $data['status'] = 0;
                $data['uid']  = $uid;
                $order_id && $data['share_get']  = $order_id;
                $coupon = $this->get_coupon($coupon_id);
                if ($hadpull->add($data)) {
                    if($now_user = M('User')->where(array('uid'=>$uid))->find()){
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $cate_platform = $this->cate_platform();
                        $href = empty($href)?C('config.site_url').'/wap.php?c=My&a=card_list&coupon_type=system':$href;
                        $model->sendTempMsg('TM00251', array('href' =>$href , 'wecha_id' => $now_user['openid'], 'first' =>  '您成功领取了'.$cate_platform['category'][$coupon['cate_name']].'优惠券', 'toName' => $now_user['nickname'], 'gift' => $coupon['name'],'time'=>date("Y年m月d日 H:i"), 'remark' => '有效期'.date("Y-m-d",$coupon['start_time']).' 至 '.date("Y-m-d",$coupon['end_time'])));
                    }
                    return array('error'=>0,'msg'=>'领取优惠券成功,优惠券【'.$coupon['name'].'】');
                }
            } else {
                return array('error'=>1,'msg'=>'领取失败');
            }

        }
    }

    public function  get_coupon_list_by_ids($ids){
        $where['coupon_id']=array('in',$ids);
        $res = $this->where($where)->getField('coupon_id,name,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new,is_discount,discount_value');

        return $res;
    }
    public function get_user_coupon_list($uid,$phone='',$is_use='' ){
        //$where['c.end_time'] = array('gt',time());
        $where['_string'] = '(c.status <> 0 AND c.status<>4)';   //状态正常
        $where['h.uid'] = $uid;
        if(!empty($is_use)){
            if($is_use==1){
                $where['h.is_use'] = 0;
                $where['c.end_time'] = array('gt',time());
            }
        }
        $n = 1;
        $cate_platform = $this->cate_platform();
        $res = M('System_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id=c.coupon_id')->field('h.id,c.cate_name as type,c.coupon_id,c.name,c.discount,h.phone,h.receive_time,c.platform,c.cate_name,c.cate_id,c.start_time,c.end_time,h.is_use,c.status,c.qrcode_id,c.des,c.des_detial,c.img,c.allow_new,c.order_money,c.is_discount,c.discount_value')->order('h.is_use ASC ,c.add_time DESC')->where($where)->select();

        foreach($res as &$v){
            if(empty($v['uid'])){
                M('System_coupon_hadpull')->where(array('id'=>$v['id']))->setField('uid',$uid);
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

    public function get_noworder_coupon_list($now_order,$order_type,$phone,$uid,$platform,$business_type='',$extra=''){
        if($business_type){
            $order_type = $business_type;
            $now_order['total_money'] = $now_order['total_money'];
        }
        if($order_type == 'group'){
            $table = 'group';
        }else if($order_type == 'meal' || $order_type == 'food' || $order_type == 'foodPad' || $order_type == 'takeout'||$order_type=='foodshop'){
            $table = 'meal';
        }else if($order_type == 'appoint'){
            $table = 'appoint';
        }else if($order_type == 'shop' || $order_type == 'mall'){
            $table = 'shop';
        }else if($order_type == 'balance-appoint'){
            $table = 'appoint';
        }else if($order_type == 'store'){
            $table = 'store';
        }else{
            return array();
        }
        $where['order_money'] = array('ELT',$now_order['total_money']);
        //$order_cate = D(ucfirst($table).'_order')->get_order_cate($now_order['order_id']);
        if($order_type!='store' && $order_type!='shop'){
            $order_cate = D(ucfirst($table).'_order')->get_order_cate($now_order['order_id']);
        }else if($order_type=='shop'){
            $order_cate = D(ucfirst($table).'_order')->get_order_cate_more($now_order['order_id']);
            foreach($order_cate as $s){
                $cate_arr[] = $s['cat_id'];
            }

        }else{
            $order_cate =array('store');
        }
        $where['c.end_time'] = array('gt',time());
        $where['c.start_time'] = array('lt',time());
        $where['c.status'] = array('in','1,3');  //状态正常
        $where['h.is_use'] = array('neq',1);  //状态正常
        if($extra){
            $key = array_keys($extra);
            $where['c.'.$key[0]] = $extra[$key[0]];
        }
        $where['uid'] = $uid;
        $where['_string'] = "(c.cate_name='".$table."') OR (c.cate_name ='all')";
        $res = M('System_coupon_hadpull')
            ->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id=c.coupon_id')
            ->field('h.id,c.coupon_id,c.name,c.order_money,c.discount,h.phone,h.receive_time,c.platform,c.cate_name,c.cate_id,c.start_time,c.end_time,h.is_use ,c.status,c.qrcode_id,c.des,c.des_detial,c.img,c.allow_new,c.is_discount,c.discount_value')
            ->where($where)->select();

        foreach($res as $key=>&$v){
            $flag = false;
            $v['platform']= unserialize($v['platform']);
            $v['cate_id'] = empty($v['cate_id'])?'0':unserialize($v['cate_id']);
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
                if($order_type=='shop'){
                    if(!in_array($v['cate_id']['cat_id'],$cate_arr)){
                        unset($res[$key]);
                        continue;
                    }
                }else{

                    $cate_arr1 = array_diff($v['cate_id'],$order_cate);
                    $cate_arr2 = array_diff($v['cate_id'],$order_cate);
                    if(!empty($cate_arr1) || !empty($cate_arr2)){
                        unset($res[$key]);
                        continue;
                    }
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
            if($v['end_time']<$_SERVER['REQUEST_TIME']&&$v['is_use']!=1){
                $v['is_use'] = 2;
            }
            if($v['is_discount']==1){
                $v['discount_money'] =round( $now_order['total_money'] * (100-$v['discount_value']*10)/100,2);
            }else{
                $v['discount_money'] = $v['discount'];
            }
        }

        return sortArrayAsc($res,'discount_money');
    }

    public function cate_platform(){
        $category=array('group'=>C('config.group_alias_name'),'meal'=>C('config.meal_alias_name'),'appoint'=>C('config.appoint_alias_name'),'shop'=>C('config.shop_alias_name'),'all'=>'全品类通用');
        if(C('config.pay_in_store')){
            $category['store'] = C('config.cash_alias_name');
        }
        $platform=array('wap'=>'移动网页','app'=>'App','weixin'=>'微信');
        return array('category'=>$category,'platform'=>$platform);
    }

    //检查平台优惠券状态
    public function check_coupon($record_id, $mer_id, $uid,$refund = false)
    {
        $now_merchant = M('Merchant')->field(true)->where(array('mer_id'=>$mer_id,'status'=>'1'))->find();
        if(empty($now_merchant)){
            return array('error_code' => 1, 'msg' => '商家暂时歇业');
        }
        $condition_coupon_record = array('id' => $record_id, 'uid' => $uid);
        if(empty($refund)){
            $condition_coupon_record['is_use'] = '0';
        }else{
            $condition_coupon_record['is_use'] = '1';
        }
        $now_coupon_record = M("System_coupon_hadpull")->field(true)->where($condition_coupon_record)->find();
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
        $result_ = M("System_coupon_hadpull")->where(array('id' => $record_id))->save(array('use_time' => $now, 'is_use' => '1'));
        if (empty($result_)) {
            return array('error_code' => 1, 'msg' => '优惠券使用失败');
        }
        $now_coupon_record = M("System_coupon_hadpull")->field(true)->where(array('id' => $record_id))->find();

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

        M('System_coupon_use_list')->add($arr);
        return array('error_code' => 0, 'msg' => '优惠券使用成功');
    }

    //领取方法
    public function had_pull($coupon_id,$uid,$card_code = ''){
        $where['coupon_id']=$coupon_id;
        $coupon = $this->get_coupon($coupon_id);
        $is_new = D('User')->check_new($uid,$coupon['cate_name']);

        if(empty($coupon)){
            return array('error_code'=>1,'coupon'=>$coupon);
        }else if($coupon['allow_new']&&!$is_new){
            return array('error_code'=>4,'coupon'=>$coupon);
        }else if($coupon['end_time']<$_SERVER['REQUEST_TIME']){
            $this->where($where)->setField('status',2);
            return array('error_code'=>2,'coupon'=>$coupon);
        }else if($coupon['status']==0){
            return array('error_code'=>1,'coupon'=>$coupon);
        }else if($coupon['status']==2){
            return array('error_code'=>2,'coupon'=>$coupon);
        }else if($coupon['status']==4){
            return array('error_code'=>2,'coupon'=>$coupon);
        }else if($coupon['num']==$coupon['had_pull']||$coupon['status']==3){
            $this->field(true)->where($where)->setField('status',3);
            return array('error_code'=>3,'coupon'=>$coupon);
        }else{
            $hadpull = M('System_coupon_hadpull');
            $hadpull_count = $hadpull->where(array('uid'=>$uid,'coupon_id'=>$coupon_id))->count();
            if($hadpull_count<$coupon['limit']) {
                if ($this->where($where)->setInc('had_pull')) {
                    $this->where($where)->setField('last_time',$_SERVER['REQUEST_TIME']);
                    $data['coupon_id'] = $coupon_id;
                    $data['num'] = 1;
                    $data['receive_time'] =$_SERVER['REQUEST_TIME'];
                    $data['status'] = 0;
                    if($card_code){
                        $data['wx_card_code']  = $card_code;
                    }
                    $data['uid']  = $uid;
                    $coupon = $this->get_coupon($coupon_id);
                    if ($hadpull->add($data)) {
                        if($now_user = M('User')->where(array('uid'=>$uid))->find()){
                            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                            $cate_platform = $this->cate_platform();
                            $model->sendTempMsg('TM00251', array('href' => C('config.site_url').'/wap.php?c=My&a=card_list&coupon_type=system', 'wecha_id' => $now_user['openid'], 'first' =>  '您成功领取了'.$cate_platform['category'][$coupon['cate_name']].'优惠券', 'toName' => $now_user['nickname'], 'gift' => $coupon['name'],'time'=>date("Y年m月d日 H:i"), 'remark' => '有效期'.date("Y-m-d",$coupon['start_time']).' 至 '.date("Y-m-d",$coupon['end_time'])));
                        }
                        $coupon['has_get'] = $hadpull_count+1;
                        return array('error_code'=>0,'coupon'=>$coupon);
                    }
                } else {
                    return array('error_code'=>1,'coupon'=>$coupon);
                }
            }else{
                return array('error_code'=>5,'coupon'=>$coupon);
            }
        }
    }

    //获取该用户领取的优惠券数量
    public function get_coupon_count_by_uid($coupon_id,$uid){
        $where['coupon_id'] = $coupon_id;
        $where['uid'] = $uid;
        return M('System_coupon_hadpull')->where($where)->count();
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

    //卡券颜色组合
    public function color_list(){
        return array(
            "Color010"=>"#63b359",
            "Color020"=>"#2c9f67",
            "Color030"=>"#509fc9",
            "Color040"=>"#5885cf",
            "Color050"=>"#9062c0",
            "Color060"=>"#d09a45",
            "Color070"=>"#e4b138",
            "Color080"=>"#ee903c",
            "Color081"=>"#f08500",
            "Color082"=>"#a9d92d",
            "Color090"=>"#dd6549",
            "Color100"=>"#cc463d",
            "Color101"=>"#cf3e36",
            "Color102"=>"#5E6671",
        );
    }

    //获取已领取的coupon的信息
    public  function get_coupon_hadpull($where){
        return M('System_coupon_hadpull')->field('c.*')->join('as s left join '.C('DB_PREFIX').'system_coupon c ON s.coupon_id = c.coupon_id')->where($where)->find();
    }

    public function share_coupon($param){
        $date['shera_num'] = C('config.share_coupon_num');
        $date['shera_get_num'] = C('config.share_coupon_get_num');
        $date['add_time']  = $_SERVER['REQUEST_TIME'];
        $date['had_pull']  = 0;
        $date['get_num']  = 0;
        $date['order_id']  = $param['order_id'];
        $date['uid']  = $param['uid'];
        $date['type']  = $param['type'];
        $where['order_id'] = $param['order_id'];
        $where['type'] = $param['type'];
        C('config.share_rand_send_coupon')==1 && $coupon_where['rand_send'] = 1;
        $share_info = M('Share_coupon_list')->where($where)->find();

        if($share_info){
            return array('error_code'=>1,'msg'=>'已经分享过了，不能再分享了');
        }
        $coupon_where['status']=1;
        $coupon_list = $this->get_coupon_list($coupon_where);
        shuffle($coupon_list);
        $date['coupon_id'] = $coupon_list[0]['coupon_id'];
        if(M('Share_coupon_list')->add($date)){
            return array('error_code'=>0,'msg'=>'添加成功');
        }else{
            return array('error_code'=>1,'msg'=>'添加分享记录失败');
        }
    }

    public function share_coupon_rand_get_coupon($param){
        $where['order_id'] = $param['order_id'];
        $where['type'] = $param['type'];
        $share_info = M('Share_coupon_list')->where($where)->find();
        if( $param['uid']!=$share_info['uid'] && $share_info['had_pull'] == $share_info['shera_num']){
            return array('error'=>1,'msg'=>'没有优惠券了');
        }

        if( $param['uid']==$share_info['uid'] && $share_info['get_num'] == $share_info['shera_get_num'] ){
            return array('error'=>1,'msg'=>'您已经领取过了!');
        }

        if( strpos($share_info['userlist'],strval($param['uid']))!==false){
            return array('error'=>1,'msg'=>'您已经领取过了');
        }
        //if($param['coupon_id']){
        //    $coupon_list[0]['coupon_id']=$param['coupon_id'];
        //}else{
        //    $coupon_list = $this->get_coupon_list();
        //    shuffle($coupon_list);
        //}
        if( $param['uid']==$share_info['uid'] &&$share_info['shera_get_num'] >1 ){
            for($i=0;$i<$share_info['shera_get_num'];$i++){
                $result = $this->send_coupon_by_id($share_info['coupon_id'],$param['uid'],1,'',$param['order_id']);
            }
        }else{
            $result = $this->send_coupon_by_id($share_info['coupon_id'],$param['uid'],1,'',$param['order_id']);
        }
        if(!$result['error']){
            $param['uid']!=$share_info['uid'] && $date['userlist'] = empty($share_info['userlist'])?$param['uid']:$share_info['userlist'].','.$param['uid'];
            $param['uid']==$share_info['uid']?$date['get_num'] = $share_info['get_num']+$share_info['shera_get_num']:$date['had_pull'] = $share_info['had_pull']+1;
            M('Share_coupon_list')->where($where)->save($date);
            return array('error_code'=>0,'msg'=>'领取成功');
        }else{
            return $result;
        }
    }

    //随机派发
    public function rand_send_coupon_get($param){
        $condition['rand_send'] = 1;
        $condition['status'] = 1;
        $condition['_string'] = "{$param['time']} >=rand_send_start_time AND {$param['time']}<=rand_send_end_time";
        $coupon_list = $this->get_coupon_list($condition);
        //shuffle($coupon_list);
        if(empty($coupon_list)) return null;

        foreach ($coupon_list as $v) {
            //限制随机派送仅一次
            if(M('Coupon_rand_send_hadpull')->where(array('uid'=>$param['uid'],'coupon_id'=>$v['coupon_id']))->find()){
                continue;
            }
            $result = $this->send_coupon_by_id($v['coupon_id'],$param['uid'],1);
            if(!$result['error']){
                $date['coupon_id'] = $v['coupon_id'];
                $date['uid'] = $param['uid'];
                $date['add_time'] = $_SERVER['REQUEST_TIME'];
                $date['num'] = 1;
                M('Coupon_rand_send_hadpull')->add($date);
                $success_coupon[] = $v['coupon_id'];
            }
        }
        //dump($success_coupon);

        $coupon_url = C('config.site_url').'/wap.php?g=Wap&c=My&a=card_list&coupon_type=system';

        if(!empty($success_coupon)){
            //$start_time = date('m月d日' ,$coupon_list[$success_coupon[0]]['start_time']);
            //$end_time = date('m月d日' ,$coupon_list[$success_coupon[0]]['end_time']);
            $days = '有效期'.intval(($coupon_list[$success_coupon[0]]['end_time']-$coupon_list[$success_coupon[0]]['start_time'])/86400).'天';
            $more_coupon = count($success_coupon)>1?'<div class="mengceng2"></div>':'';
            $coupon_list[$success_coupon[0]]['order_money'] = floatval($coupon_list[$success_coupon[0]]['order_money']);
            $coupon_list[$success_coupon[0]]['discount'] = floatval($coupon_list[$success_coupon[0]]['discount']);
            $tmp['coupon'] = array();
            $tmp['coupon_count'] = count($success_coupon);
            if($param['from']=='app'){
                foreach ($success_coupon as $v) {
                    $return['days'] = '有效期'.intval(($coupon_list[$v]['end_time']-$coupon_list[$v]['start_time'])/86400).'天';;
                    $return['order_money'] = $coupon_list[$v]['order_money'];
                    $return['discount'] = $coupon_list[$v]['discount'];
                    $return['name'] = $coupon_list[$v]['name'];
                    $return['coupon_id'] = $coupon_list[$v]['coupon_id'];
                    $tmp['coupon'][] = $return;
                }
                return $tmp;
            }

            $coupon_count = count($success_coupon);
            $coupon_html = <<<Eof
        <style>
            #mongolia_layer{position:fixed;bottom:0;top:0;left:0;right:0;background-color:#000;opacity:.5;z-index:1000;width:100%}
            #Coupon{width:290px;z-index:1500;background:url(../static/images/coupon/share_coupon2.png) center no-repeat;height:260px;background-size:cover;position:fixed;left:50%;margin-left:-145px;top:50%;margin-top:-130px}
            #Coupon .delate_money {
                position: absolute;
                left: 89%;
                top: 3%;
                width: 20px;
                height: 20px;
                font-weight: normal;
                color: #fff;
                border: 1px solid #fff;
                border-radius: 50%;
                padding: 0px 0px;
                z-index: 99;
            }

 #Coupon .mengceng{
	display: block;
    width: 256px;
    height: 75px;
    background: url(../static/images/coupon/share_coupon3.png) center no-repeat;
    background-size: cover;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -124px;
    margin-top: -37.5px;
}
 #Coupon .delate_money i{
 	font-style: normal;
    font-size: 20px;
    position: absolute;
    top: -6px;
    left: 3px;
    font-weight: normal;
 }
#Coupon .mengceng2{
	display: block;
    width: 256px;
    height: 75px;
    background: url(../static/images/coupon/share_coupon3.png) center no-repeat;
    background-size: cover;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -128px;
    margin-top: -45.5px;
    box-shadow: 0px 4px 4px #c7b2b2;
}
            #Coupon .title{text-align:center;font-size:18px;color:#fff;font-weight:900;width:100%;position:absolute;top:18px}
            #Coupon .desc{margin:0;padding:0;margin-top:53px;color:#fff;font-size:15px;text-align:center;width:100%;position:absolute;font-weight:600}
          #Coupon .left_text{
	position: absolute;
    top: 12%;
    left: 2%;
}
#Coupon .left_text dt{
	text-align:center;
}
#Coupon .left_text dt span{
	color: #f00;
    font-size: 1.2em;

}
#Coupon .left_text dt b{
	color: #f00;
    font-size: 1.7em;
}
#Coupon .left_text dd {
    text-align: center;
    font-size: 0.7em;
    color: #f00;
    margin-left: 15px;
}
#Coupon .right_text{
	position: absolute;
	top:13%;
	left:40%;
}
#Coupon .right_text dt{
	font-weight: bold;
    font-size: 1.4em;
}
 #Coupon .right_text dd {
    color: #f00;
    text-align: left;
    font-size: 12px;
}
#Coupon p{
	position:absolute;
	top:67%;
	left:25%;
}
           #Coupon .btn {
            position: absolute;
            top: 79%;
            left: 6%;
            text-align: center;
            width: 88%;
            height: 40px;
            border-radius: 30px;
            border: 0;
            color: #410509;
            background-color: #ffda31;
            font-weight: bold;
            font-family: "SimSun";
            font-size: 15px;
             }
              </style>
        <div id="mongolia_layer"></div>
		    <div id="Coupon">
			<span id="coupon_close" class="delate_money "><i>×</i></span>
			<span class="title">优惠劵来袭</span>
			<span class="desc">平台赠送您{$coupon_count}张优惠劵</span>
			<div class="mengceng">
				{$more_coupon}
                <dl class="left_text">
                    <dt><span>￥</span><b>{$coupon_list[$success_coupon[0]]['discount']}</b></dt>
                    <dd>满{$coupon_list[$success_coupon[0]]['order_money']}减{$coupon_list[$success_coupon[0]]['discount']}</dd>
                </dl>
                <dl class="right_text">
                    <dt>{$coupon_list[$success_coupon[0]]['name']}</dt>
                    <dd>{$days}</dd>
                </dl>
			</div>
			<p>优惠劵已存入您的账户</p>
			<button class="btn redirect_coupon" >立即查看</button>
		</div>
        <script>
            $('#coupon_close').click(function(){
                $('#Coupon').hide();
                $('#mongolia_layer').hide();
            });
            $('.redirect_coupon').click(function(){
                window.location.href ='$coupon_url'
            })
        </script>
Eof;
            //echo $coupon_hmtl;
            return $coupon_html;
        }
    }
}