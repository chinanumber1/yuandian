<?php


class YuedanAction extends BaseAction {
    //首页
    public function index(){
        // 轮播
        $yuedan_index_top_lunbo = D('Adver')->get_adver_by_key('yuedan_index_top_lunbo',3);
        $this->assign('yuedan_index_top_lunbo',$yuedan_index_top_lunbo);
    
        $catListBak = D('Yuedan_category')->where(array('status'=>1,'is_hot'=>1,'fcid'=>array('neq','0')))->limit(9)->order('cat_sort desc,cid asc')->select();
        $this->assign('catList',$catListBak);

        //同城热约
        $service_list = D('Yuedan_service_release')->where(array('city_id'=>$this->config['now_city'],'status'=>2))->order('sales_volume desc')->limit(6)->select();
        foreach ($service_list as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            $service_list[$key]['img'] = $ingList;
            $service_list[$key]['listimg'] = $ingList[0];
        }
        $this->assign('service_list',$service_list);

        $this->display();
    }

    public function recommend_list_ajax(){
        $listRows = 6;
        $firstRow = $_POST['page']*$listRows;
        $condition_field = "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$_POST['lat']}*PI()/180-`address_lat`*PI()/180)/2),2)+COS({$_POST['lat']}*PI()/180)*COS(`address_lat`*PI()/180)*POW(SIN(({$_POST['lng']}*PI()/180-`address_lng`*PI()/180)/2),2)))*1000) AS juli";
        $condition_field .= ",rid,title,img,address_name,price,unit,cid,cat_name,add_time,status,sales_volume,comment_sum,describe,address_lng,address_lat,uid";
        $order .= 'juli ASC';
        $recommend_list = D('Yuedan_service_release')->where($where)->order($order)->field($condition_field)->limit($firstRow,$listRows)->select();
        foreach ($recommend_list as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            $recommend_list[$key]['img'] = $ingList;
            $recommend_list[$key]['listimg'] = $ingList[0];
            $userInfo = D('User')->where(array('uid'=>$value['uid']))->field('nickname,avatar,uid')->find();


            if($value['juli'] < 1000){
                $juli = '< 1 ';
            }else{
                $juli = $value['juli']/1000;
            }

            $ingbak = '';
            foreach ($ingList as $key => $imgval) {
                if($key < 3){
                    $ingbak .= '<li class="ft" style="float: left"><p class="img_click" data-src="{pigcms{$vo.listimg}" style="display:inline-block;    margin: 5px 5%; width: 90%;background: transparent url('.$imgval.') no-repeat 0 0px;background-size: cover; height:90px;text-align: center;"></p></li>';
                }
            }
            $html.='<div class="details_list">
                    <a href="'.U('Yuedan/user_details',array('uid'=>$userInfo['uid'],'juli'=>$value['juli'])).'">
                        <div class="list_header">
                            <img style="border-radius: 50%;" src="'.$userInfo['avatar'].'" alt="">
                            <dl> 
                                <dt>'.$userInfo['nickname'].'</dt> 
                                <dd><i></i>'.$juli.'km</dd> 
                            </dl>
                        </div>
                    </a>
                    <a href="'.U('Yuedan/service_detail',array('rid'=>$value['rid'])).'">
                        <div class="list_content">
                            <ul class="clear"> '.$ingbak.'</ul>
                        </div>
                        <div class="details_price">
                            <p>'.$value['title'].'&nbsp;<span>'.$value['price'].'&nbsp;/&nbsp;'.$value['unit'].'</span></p>
                            <p>接单量 <span>'.$value['sales_volume'].'</span></p>
                        </div>
                        <div class="notes"> '.$value['describe'].'</div>
                    </a>
                </div>';
        }

        if(is_array($recommend_list)){
            exit(json_encode(array('error'=>1,'msg'=>'加载成功','html'=>$html)));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'暂无数据')));
        }

        
    }

    public function around(){
        $this->display();
    }

    public function ajaxAround(){
        $this->header_json();
        $where = "(ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$_POST['lat']}*PI()/180-`address_lat`*PI()/180)/2),2)+COS({$_POST['lat']}*PI()/180)*COS(`address_lat`*PI()/180)*POW(SIN(({$_POST['lng']}*PI()/180-`address_lng`*PI()/180)/2),2)))*1000) <= 2000)";
        $plist = D("Yuedan_service_release")->where($where)->select();
        foreach ($plist as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            $plist[$key]['img'] = $ingList;
            $plist[$key]['listimg'] = $ingList[0];
            $userInfo = D('User')->where(array('uid'=>$value['uid']))->find();
            $plist[$key]['nickname'] = $userInfo['nickname'];
        }
        echo json_encode($plist);
    }

    // 搜索
    public function search(){
        if($_GET['search']){

            if($_GET['cid']){
                $where['cid'] = $_GET['cid'];
            }
            $where['title'] = array('like','%'.$_GET['search'].'%');
            $where['status'] =2;
            $service_list = D('Yuedan_service_release')->where($where)->order($order)->select();
            // echo D('Yuedan_service_release')->getlastsql();
            foreach ($service_list as $key => $value) {
                $ingList = array_filter(explode(';', $value['img']));
                $service_list[$key]['img'] = $ingList;
                $service_list[$key]['listimg'] = $ingList[0];
                $userinfo = D('User')->where(array('uid'=>$value['uid']))->field('nickname')->find();
                $service_list[$key]['nickname'] = $userinfo['nickname'];
            }
            // dump($service_list);
            $this->assign('service_list',$service_list);

        }
        $this->display();
    }

    public function cat_list(){
        $catListBak = D('Yuedan_category')->where(array('status'=>1))->order('cat_sort desc,cid asc')->select();
        $catList = array();
        foreach ($catListBak as $key => $value) {
            if($value['fcid'] == 0){
                $catList [$value['cid']]= $value;
            }
        }
        foreach ($catListBak as $k => $v) {
            if($v['fcid'] != 0){
                $catList [$v['fcid']]['catList'][$v['cid']]= $v;
            }
        }
        $this->assign('catList',$catList);
        $this->display();
    }

    // 服务列表
    public function service_list(){
        $where = array();
        if($_GET['cid']){
            $where['cid'] = $_GET['cid'];
            $categoryInfo = D('Yuedan_category')->where(array('cid'=>$_GET['cid']))->find();
            $this->assign('categoryInfo',$categoryInfo);
        }
        if($_GET['search']){
            $where['title'] = array('like','%'.$_GET['search'].'%');
        }

        if($_GET['minPrice']){

            if($_GET['maxPrice'] == 0){
                $where['price'] = array('egt',$_GET['minPrice']);
            }else{
                $where['price'] =  array(array('egt',$_GET['minPrice']),array('elt',$_GET['maxPrice']));
            }
            
        }

        if($_GET['maxPrice']){
            if($_GET['minPrice'] == 0){
                $where['price'] = array('elt',$_GET['maxPrice']);
            }else{
                $where['price'] =  array(array('egt',$_GET['minPrice']),array('elt',$_GET['maxPrice']));
            }
        }

        if($_GET['order']){
            $order[$_GET['order']] = $_GET['sort'];
        }

        
        $where['status'] =2;
        $service_list = D('Yuedan_service_release')->where($where)->order($order)->select();
        // echo D('Yuedan_service_release')->getlastsql();
        foreach ($service_list as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            $service_list[$key]['img'] = $ingList;
            $service_list[$key]['listimg'] = $ingList[0];
            $userinfo = D('User')->where(array('uid'=>$value['uid']))->field('nickname')->find();
            $service_list[$key]['nickname'] = $userinfo['nickname'];
        }
        $this->assign('service_list',$service_list);
        $this->display();
    }

    // 服务详情
    public function service_detail(){
        $service_info = D('Yuedan_service_release')->where(array('rid'=>$_GET['rid']))->find();
        if(!$service_info){
            $this->error_tips('数据异常!');
        }
        $collectionInfo = D('Yuedan_collection')->where(array('rid'=>intval($_GET['rid']),'uid'=>$this->user_session['uid']))->find();
        $this->assign('collectionInfo',$collectionInfo);
        $service_info['img'] = array_filter(explode(';', $service_info['img']));

        foreach ($service_info['img'] as $key => $value) {
            $img_data = getimagesize($this->config['site_url'].'/'.$value);
            $img_data['url'] = $this->config['site_url'].'/'.$value;
            $service_info['img'][$key] = $img_data;
        }

        // dump($service_info);
        $authentication = D('Yuedan_authentication')->where(array('uid'=>$service_info['uid']))->find();
        $this->assign('authentication',$authentication);
        $this->assign('service_info',$service_info);

        // $commentList = D("Yuedan_comment")->where(array('rid'=>$_GET['rid']))->limit(5)->order('add_time desc')->select();
        $commentList = D('')->table(array(C('DB_PREFIX').'yuedan_comment'=>'co',C('DB_PREFIX').'user'=>'u'))->where("co.rid= '".$_GET['rid']."' AND co.uid = u.uid")->field('co.*,u.nickname')->order('co.add_time desc')->limit(5)->select();

        $this->assign('commentList',$commentList);
        $this->assign('commentCount',count($commentList));
        $totalGrade = D('Yuedan_comment')->where(array('rid'=>$_GET['rid']))->sum('total_grade');
        $this->assign('totalGrade',sprintf("%.1f", $totalGrade/$service_info['comment_sum']/2));
        // dump($service_info);
        $this->display();
    }

    //全部评价
    public function all_evaluate(){
        $commentList = D('')->table(array(C('DB_PREFIX').'yuedan_comment'=>'co',C('DB_PREFIX').'user'=>'u'))->where("co.rid= '".$_GET['rid']."' AND co.uid = u.uid")->field('co.*,u.nickname')->order('co.add_time desc')->select();

        $this->assign('commentList',$commentList);
        $this->assign('commentCount',count($commentList));
        $totalGrade = D('Yuedan_comment')->where(array('rid'=>$_GET['rid']))->sum('total_grade');
        $this->assign('totalGrade',sprintf("%.1f", $totalGrade/count($commentList)/2));

        $this->display();
    }

    public function next_order(){
        $service_info = D('Yuedan_service_release')->where(array('rid'=>$_GET['rid']))->find();
        $ingList = array_filter(explode(';', $service_info['img']));
        $service_info['img'] = $ingList;
        $service_info['listimg'] = $ingList[0];
        $userinfo = D('User')->where(array('uid'=>$service_info['uid']))->field('nickname')->find();
        $service_info['nickname'] = $userinfo['nickname'];
        $this->assign('service_info',$service_info);


        $adress_list = D('User_adress')->get_adress_list($this->user_session['uid']);
        foreach ($adress_list as $key => $value) {
            if($value['adress_id'] == $_GET['adress_id']){
                $addressInfo = $value;
            }elseif($value['default'] == 1){
                $addressInfo = $value;
            }
        }
        $this->assign('addressInfo', $addressInfo);
        $this->assign('adress_list', $adress_list);


        $categoryInfo = D('Yuedan_category')->where(array('cid'=>$service_info['cid']))->find();
        $this->assign('categoryInfo',$categoryInfo);

        $agreementInfo = D('Yuedan_agreement')->where(array('key'=>'buy_agreement'))->find();
        $this->assign('agreementInfo',$agreementInfo);



        $weekarray=array("日","一","二","三","四","五","六"); //定义星期
        $maxDay = $service_info['bespeak_day']; //最大天数
        $startH = $service_info['startH']; //开始时间
        $endH = $service_info['endH']; //结束时间
        $interval = $service_info['interval']; // 时间间隔(分钟)

        for ($d=0; $d < $maxDay; $d++) { 
            for ($h=$startH; $h <= $endH ; $h++) { 
                if($d==0){
                    if($h>=date("H") && date("H")<=$h){
                        for ($s=0; $s < 60/$interval; $s++) { 
                            if($s == 0){
                                $timebak = $h.":00";
                            }else{
                                $timebak = $h.":".$s*$interval;
                            }
                            $time[$d][$h][$s]['time'] = $timebak;
                            $time[$d][$h][$s]['week'] = "周".$weekarray[date("w",strtotime("+".$d." day"))];
                            $time[$d][$h][$s]['date'] = date("Y-m-d",strtotime("+".$d." day"));
                            $time[$d][$h][$s]['bespeak_time'] = strtotime(date("Y-m-d",strtotime("+".$d." day")).' '.$timebak);
                            $order_info = D('Yuedan_service_order')->where(array('rid'=>$_GET['rid'],'bespeak_time'=>strtotime(date("Y-m-d",strtotime("+".$d." day")).' '.$timebak)))->find();
                            if($order_info){
                                 $time[$d][$h][$s]['disable'] = 'disable';
                            }
                        }
                    }
                }else{
                    for ($s=0; $s < 60/$interval; $s++) { 
                        if($s == 0){
                            $time[$d][$h][$s]['time'] = $h.":00";
                        }else{
                            $time[$d][$h][$s]['time'] = $h.":".$s*$interval;
                        }
                        $time[$d][$h][$s]['week'] = "周".$weekarray[date("w",strtotime("+".$d." day"))];
                        $time[$d][$h][$s]['date'] = date("Y-m-d",strtotime("+".$d." day"));
                        $order_info = D('Yuedan_service_order')->where(array('rid'=>$_GET['rid'],'bespeak_time'=>strtotime(date("Y-m-d",strtotime("+".$d." day")).' '.$timebak)))->find();
                        if($order_info){
                             $time[$d][$h][$s]['disable'] = 'disable';
                        }
                    }
                }
                
            }
        }

        foreach ($time as $key => $value) {
            foreach ($value as $kk => $vv) {
                foreach ($vv as $kkk => $vvv) {
                    $timeList[$key][] = $vvv; 
                }
            }
        }

        for ($d=0; $d < intval($maxDay); $d++) { 
            $daysList[] =  array('a'=>"周".$weekarray[date("w",strtotime("+".$d." day"))],'b'=>date("Y-m-d",strtotime("+".$d." day")));
        }

        $this->assign('daysList',$daysList);
        $this->assign('timeList',$timeList);

        $this->display();
    }

    public function place_order(){
        $adress = D('User_adress')->get_adress($this->user_session['uid'], $_POST['adress_id']);
        // dump($adress);die;
        $service_info = D('Yuedan_service_release')->where(array('rid'=>$_POST['rid']))->find();
        $data['rid'] = $_POST['rid'];
        $data['sum'] = $_POST['sum'];
        $data['price'] = $service_info['price'];
        $data['total_price'] = $_POST['sum']*$service_info['price'];
        $data['remarks'] = $_POST['remarks'];
        $data['add_time'] = time();
        $data['uid'] = $this->user_session['uid'];
        $data['ruid'] = $service_info['uid'];
        $data['status'] = 2;
        $data['adress_id'] = $_POST['adress_id'];
        $data['bespeak_time'] = $_POST['bespeak_time'];

        $userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('now_money')->find();
        if($userInfo['now_money']<$data['total_price']){
            exit(json_encode(array('error'=>3,'msg'=>'您的余额不足请前去充值')));
        }
        $res = D('Yuedan_service_order')->data($data)->add();
        if($res){
            D('User')->where(array('uid'=>$this->user_session['uid']))->setDec('now_money',$data['total_price']);
            D('User_money_list')->add_row($this->user_session['uid'],2,$data['total_price'],"购买 ".$service_info['title']."服务 ".$data['sum']." 次");

            //模板消息
            $user_info = D("User")->where(array('uid'=>$service_info['uid']))->field('uid,openid,nickname,phone')->find();
            if ($user_info['openid']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=my_order&type=1';
                $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '已有用户购买您发布的'.$service_info['title'].'，请及时处理。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
            }

            if($user_info['phone']){
                $sms_data['uid'] = $user_info['uid'];
                $sms_data['mobile'] = $user_info['phone'];
                $sms_data['sendto'] = 'user';
                $sms_data['content'] = '已有用户购买您发布的'.$service_info['title'].'，请及时处理。';
                Sms::sendSms($sms_data);
            }


            //模板消息
            $my_user_info = D("User")->where(array('uid'=>$this->user_session['uid']))->field('uid,openid,nickname,phone')->find();

            if ($my_user_info['openid']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=my_order';
                $model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $res, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $service_info['title'], 'remark' => '您的该次约单下单成功，感谢您的使用！'), $order_data['mer_id']);
            }

            if($my_user_info['phone']){
                $sms_data['uid'] = $my_user_info['uid'];
                $sms_data['mobile'] = $my_user_info['phone'];
                $sms_data['sendto'] = 'user';
                $sms_data['content'] = '您购买['.$service_info['title'].']服务的订单(订单号：'.$res.')已经完成支付';
                Sms::sendSms($sms_data);
            }
            //分佣
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $now_user = $userInfo;
            $order_id = $res;
            $spread_total_money = $money;
            if(!empty($now_user['openid'])&&C('config.open_user_spread') && (C('config.spread_money_limit')==0 || C('config.spread_money_limit')<=$spread_total_money)){
                //上级分享佣金
                $money  = $service_info['price'];
                $spread_rate = D('Percent_rate')->get_user_spread_rate('','yuedan');
                $spread_type = 'yuedan';

                $spread_users[]=$now_user['uid'];
                if($now_user['wxapp_openid']!=''){
                    $spread_where['_string'] = "openid = '{$now_user['openid']}' OR openid = '{$now_user['wxapp_openid']}' ";
                }else{
                    $spread_where['_string'] = "openid = '{$now_user['openid']}'";
                }
                $now_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where)->find();

                $href = C('config.site_url') . '/wap.php?g=Wap&c=My&a=spread_list&status=-1';
                if(!empty($now_user_spread)){
                   if($now_user_spread['is_wxapp']){
                            $spread_user = D('User')->get_user($now_user_spread['spread_openid'],'wxapp_openid');
                        }else{
                            $spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
                        }
                    //$user_spread_rate = $update_group['spread_rate'] > 0 ? $update_group['spread_rate'] : C('config.user_spread_rate');
                    $user_spread_rate = $spread_rate['first_rate'];
                    if($spread_user && $user_spread_rate&&!in_array($spread_user['uid'],$spread_users)){
                        $spread_money = round(($money) * $user_spread_rate / 100, 2);
                        $spread_data = array(
                            'uid'=>$spread_user['uid'],
                            'spread_uid'=>0,
                            'get_uid'=>$now_user['uid'],
                            'money'=>$spread_money,
                            'order_type'=>$spread_type,
                            'order_id'=>$order_id,
                            'third_id'=>$service_info['rid'],
                            'add_time'=>$_SERVER['REQUEST_TIME']
                        );
                        if($spread_user['spread_change_uid']!=0){
                            $spread_data['change_uid'] =    $spread_user['spread_change_uid'];
                        }
                        D('User_spread_list')->data($spread_data)->add();
                        $buy_user = D('User')->get_user($now_user_spread['openid'],'openid');
                        if($spread_money>0){

                            $money_name = '佣金';

                            $model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $spread_user['openid'], 'first' => $buy_user['nickname'] . '通过您的分享购买约单【'.$service_info['title'].'】，您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
                        }
                        $spread_users[]=$spread_user['uid'];
                        // D('User')->add_money($spread_user['uid'],$spread_money,'推广用户 '.$now_user['nickname'].' 购买 ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
                    }

                    //第二级分享佣金
                    $spread_where['_string'] = "openid = '{$spread_user['openid']}' OR openid = '{$spread_user['wxapp_openid']}' ";
                    $second_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where )->find();

                    if(!empty($second_user_spread)) {
                       if($second_user_spread['is_wxapp']){
                                $second_user = D('User')->get_user($second_user_spread['spread_openid'], 'wxapp_openid');
                            }else{
                                $second_user = D('User')->get_user($second_user_spread['spread_openid'], 'openid');
                            }
//                          //$sub_user_spread_rate = $update_group['sub_spread_rate'] > 0 ? $update_group['sub_spread_rate'] : C('config.user_first_spread_rate');
                        $sub_user_spread_rate = $spread_rate['second_rate'];
                        if ($second_user && $sub_user_spread_rate&&!in_array($second_user['uid'],$spread_users)) {
                            $spread_money = round(($money) * $sub_user_spread_rate / 100, 2);
                            $spread_sec_data =array('uid' => $second_user['uid'], 'spread_uid' => $spread_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => $spread_type, 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
                            if($second_user['spread_change_uid']!=0){
                                $spread_sec_data['change_uid'] =    $second_user['spread_change_uid'];
                            }
                            D('User_spread_list')->data($spread_sec_data)->add();
                            $sec_user = D('User')->get_user($second_user_spread['openid'], 'openid');
                            if($spread_money>0) {
                                $model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $second_user['penid'], 'first' => $sec_user['nickname'] .'的子用户'.$buy_user['nickname'] .  '通过您的分享购买约单【'.$service_info['title'].'】，您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'),  $now_order['mer_id']);
                            }
                            $spread_users[]=$second_user['uid'];
                            // D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
                        }

                        //顶级分享佣金
                        $spread_where['_string'] = "openid = '{$second_user['openid']}' OR openid = '{$second_user['wxapp_openid']}' ";
                        $first_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where(	$spread_where)->find();

                        if (!empty($first_user_spread) && C('config.user_third_level_spread')) {
                             if($first_user_spread['is_wxapp']){
                                $first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'wxapp_openid');
                            }else{
                                $first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'openid');
                            }
//                              //$sub_user_spread_rate = $update_group['third_spread_rate'] > 0 ? $update_group['third_spread_rate'] : C('config.user_second_spread_rate');
                            $sub_user_spread_rate = $spread_rate['third_rate'];
                            if ($first_spread_user && $sub_user_spread_rate&&!in_array($first_spread_user['uid'],$spread_users)) {
                                $spread_money = round(($money) * $sub_user_spread_rate / 100, 2);
                                $spread_thd_data=array('uid' => $first_spread_user['uid'], 'spread_uid' => $second_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => $spread_type, 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
                                if($first_spread_user['spread_change_uid']!=0){
                                    $spread_thd_data['change_uid'] =    $first_spread_user['spread_change_uid'];
                                }
                                D('User_spread_list')->data($spread_thd_data)->add();

                                $fir_user = D('User')->get_user($first_user_spread['openid'], 'openid');
                                if($spread_money>0) {
                                    $model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $first_spread_user['openid'], 'first' =>$fir_user['nickname'].'的子用户的子用户'.$buy_user['nickname'] .  '通过您的分享购买约单【'.$service_info['title'].'】，您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
                                }
                                // D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
                            }
                        }

                    }
                }
            }
            //滚动消息
            D('Scroll_msg')->add_msg('yuedan',$my_user_info['uid'],'用户'.$my_user_info['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.'['.$service_info['title'].']服务成功');


            exit(json_encode(array('error'=>1,'msg'=>'下单成功！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'下单失败请重试！')));
        }

    }

    public function my_order(){
        if($_GET['type'] == 1){
            $orderList = D('')->table(array(C('DB_PREFIX').'yuedan_service_order'=>'so',C('DB_PREFIX').'yuedan_service_release'=>'sr'))->where("so.ruid= '".$this->user_session['uid']."' AND sr.rid = so.rid")->field('so.*,sr.title,sr.img,sr.unit,sr.cat_name')->order('so.add_time desc')->select();
            foreach ($orderList as $key => $value) {
                $ingList = array_filter(explode(';', $value['img']));
                $orderList[$key]['img'] = $ingList;
                $orderList[$key]['listimg'] = $ingList[0];
            }
            // $authentication_status = D('Yuedan_authentication')->where(array('uid'=>$this->user_session['uid']))->find();
            $authentication_status = D('Yuedan_authentication')->where(array('uid'=>$this->user_session['uid']))->getField('authentication_status');
            $this->assign('authentication_status',$authentication_status);
            $this->assign('orderList',$orderList);
        }else{
            $orderList = D('')->table(array(C('DB_PREFIX').'yuedan_service_order'=>'so',C('DB_PREFIX').'yuedan_service_release'=>'sr',C('DB_PREFIX').'yuedan_category'=>'c'))->where("so.uid= '".$this->user_session['uid']."' AND sr.rid = so.rid AND sr.cid = c.cid")->field('so.*,sr.title,sr.img,sr.unit,sr.cat_name,c.is_free,c.cancel_proportion,c.cancel_time')->order('so.add_time desc')->select();
            foreach ($orderList as $key => $value) {
                $ingList = array_filter(explode(';', $value['img']));
                $orderList[$key]['img'] = $ingList;
                $orderList[$key]['listimg'] = $ingList[0];
            }

            // dump($orderList);
            $this->assign('orderList',$orderList);
        }
        
        $this->display();
    }



    //取消订单
    public function cancel_order(){
        $orderInfo = D('')->table(array(C('DB_PREFIX').'yuedan_service_order'=>'so',C('DB_PREFIX').'yuedan_service_release'=>'sr',C('DB_PREFIX').'yuedan_category'=>'c'))->where("so.order_id = '".$_POST['order_id']."' AND so.uid= '".$this->user_session['uid']."' AND sr.rid = so.rid AND sr.cid = c.cid")->field('so.*,sr.title,sr.img,sr.unit,sr.cat_name,c.is_free,c.cancel_proportion,c.cancel_time,sr.uid as ruid,c.cut_proportion')->order('so.add_time desc')->find();
        $overdue_time =  $orderInfo['add_time']+$orderInfo['cancel_time']*60*60;



        $res = D('Yuedan_service_order')->where(array('order_id'=>$_POST['order_id'],'uid'=>$this->user_session['uid']))->save(array('status'=>6));
        if($res){
            if($overdue_time < time() && $orderInfo['is_free'] == 1){
                $cancel_proportion = $orderInfo['cancel_proportion']/100;
                $cancel_price =  round($orderInfo['total_price']*$cancel_proportion,2);
                $return_price = $orderInfo['total_price'] - $cancel_price;
                D('User')->where(array('uid'=>$this->user_session['uid']))->setInc('now_money',$return_price);
                D('User_money_list')->add_row($this->user_session['uid'],1,$return_price,"系统退还您购买 ".$orderInfo['title']." 服务金额,返还金额".$orderInfo['total_price']."元,扣除违约金".round($cancel_price,2)."元");


                $system_proportion = $orderInfo['cut_proportion']/100;
                $system_price = round($cancel_price*$system_proportion,2);
                $return_service_price = $cancel_price-$system_price;

                D('User')->where(array('uid'=>$orderInfo['ruid']))->setInc('now_money',$return_service_price);
                D('User_money_list')->add_row($orderInfo['ruid'],1,$return_service_price,"用户关闭 ".$orderInfo['title']." 服务,支付给您违约金".round($cancel_price,2)."元,平台扣除手续费".round($system_price,2)."元");

            }else{
                D('User')->where(array('uid'=>$this->user_session['uid']))->setInc('now_money',$orderInfo['total_price']);
                D('User_money_list')->add_row($this->user_session['uid'],1,$orderInfo['total_price'],"系统退还您购买 ".$orderInfo['title']." 服务金额,返还金额".$orderInfo['total_price']."元");
            }
            exit(json_encode(array('error'=>1,'msg'=>'关闭成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'关闭失败，请重试。')));
        }
    }



    public function order_details(){
        $orderInfo = D('Yuedan_service_order')->where(array('order_id'=>$_GET['order_id']))->find();
        $releaseInfo = D('Yuedan_service_release')->where(array('rid'=>$orderInfo['rid']))->field('title,img,unit,cat_name,uid')->find();
        $ingList = array_filter(explode(';', $releaseInfo['img']));
        $releaseInfo['img'] = $ingList;
        $releaseInfo['listimg'] = $ingList[0];

        $releaseUserInfo = D('user')->where(array('uid'=>$releaseInfo['uid']))->field('nickname,phone,avatar,uid')->find();
        $buyUserInfo = D('user')->where(array('uid'=>$orderInfo['uid']))->field('nickname,phone,avatar,uid')->find();
        // $orderInfo = D('')->table(array(C('DB_PREFIX').'yuedan_service_order'=>'so',C('DB_PREFIX').'yuedan_service_release'=>'sr',C('DB_PREFIX').'user'=>'u'))->where("so.order_id= '".$_GET['order_id']."' AND sr.rid = so.rid AND u.uid = so.uid")->field('so.*,sr.title,sr.img,sr.unit,sr.cat_name,u.nickname,u.phone,u.avatar')->find();
        // $orderInfo = D('Yuedan_service_order')->where(array('uid'=>$this->user_session['uid'],'order_id'=>$_GET['order_id']))->find();

        if($this->user_session['uid'] == $releaseUserInfo['uid']){
            $userInfo = $buyUserInfo;
        }else{
            $userInfo = $releaseUserInfo;
        }

        if(!$orderInfo){
            $this->error_tips('数据异常请重试！');
        }

        $this->assign('orderInfo',$orderInfo);
        $this->assign('userInfo',$userInfo);
        $this->assign('releaseInfo',$releaseInfo);
        $this->display();
    }

    public function confirm_service(){
        $orderInfo = D('Yuedan_service_order')->where(array('order_id'=>$_POST['order_id']))->find();
        $service_info = D('Yuedan_service_release')->where(array('rid'=>$orderInfo['rid']))->find();
        $category_info = D('Yuedan_category')->where(array('cid'=>$service_info['cid']))->find();

        $res = D('Yuedan_service_order')->where(array('order_id'=>$_POST['order_id']))->data(array('status'=>$_POST['status']))->save();
        if($res){
            if($_POST['status'] == 4){
                D('Yuedan_service_release')->where(array('rid'=>$orderInfo['rid']))->setInc('sales_volume');
                //模板消息
                $user_info = D("User")->where(array('uid'=>$service_info['uid']))->field('uid,openid,nickname')->find();
                if ($user_info['openid']) {
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=my_order&type=1';
                    $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '用户购买您发布的'.$service_info['title'].'，已经确认服务。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                }

                $system_proportion = $category_info['cut_proportion']/100;
                $system_price = $orderInfo['total_price']*$system_proportion;
                $return_service_price = $orderInfo['total_price']-$system_price;

                D('User')->where(array('uid'=>$service_info['uid']))->setInc('now_money',$return_service_price);
                D('User_money_list')->add_row($service_info['uid'],1,$return_service_price,"用户购买 ".$service_info['title']." 服务,已经确认服务，支付给您".$orderInfo['total_price']."元,平台扣除手续费".round($system_price,2)."元");

            }elseif ($_POST['status'] == 3) {
                //模板消息
                $user_info = D("User")->where(array('uid'=>$orderInfo['uid']))->field('uid,openid,nickname')->find();
                if ($user_info['openid']) {
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=my_order';
                    $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '您购买的“'.$service_info['title'].'”服务商已完成服务，请及时处理。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                }
            }elseif ($_POST['status'] == 10) {
                //模板消息
                $user_info = D("User")->where(array('uid'=>$orderInfo['uid']))->field('uid,openid,nickname')->find();
                if ($user_info['openid']) {
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=my_order';
                    $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '您购买的“'.$service_info['title'].'”服务商已拒绝服务，请及时处理。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                }

                // D('User')->where(array('uid'=>$user_info['uid']))->setInc('now_money',$return_service_price);
                // D('User_money_list')->add_row($user_info['uid'],1,$return_service_price,"用户购买 ".$user_info['title']." 服务,已经确认服务，支付给您".$orderInfo['total_price']."元,平台扣除手续费".round($system_price,2)."元");

            }
            exit(json_encode(array('error'=>1,'msg'=>'状态修改成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'状态修改失败请重试。')));
        }
    }

    // 发表评价
    public function comment(){
        $orderInfo = D('Yuedan_service_order')->where(array('order_id'=>$_GET['order_id'],'uid'=>$this->user_session['uid'],'status'=>4))->find();
        // if(empty($orderInfo)){
        //     $this->error_tips('没有发现已完成的订单');
        // }
        $serviceInfo = D('Yuedan_service_release')->where(array('rid'=>$orderInfo['rid']))->find();
        $ingList = array_filter(explode(';', $serviceInfo['img']));
        $serviceInfo['img'] = $ingList;
        $serviceInfo['listimg'] = $ingList[0];
        $this->assign('serviceInfo',$serviceInfo);
        // dump($serviceInfo);
        $this->assign('orderInfo',$orderInfo);
        $this->display();
    }

    public function comment_data(){
        $orderInfo = D('Yuedan_service_order')->where(array('order_id'=>$_POST['order_id'],'uid'=>$this->user_session['uid'],'status'=>4))->find();
        if(empty($orderInfo)){
            $this->error_tips('没有发现已完成的订单');
        }
        $commentInfo = D('Yuedan_comment')->where(array('order_id'=>$_POST['order_id'],'uid'=>$this->user_session['uid']))->find();
        if($commentInfo){
            $this->error_tips('订单已经评价');
        }
        $data = $_POST;
        $data['rid'] = $orderInfo['rid'];
        $data['add_time'] = time();
        $data['uid'] = $this->user_session['uid'];
        $res = D('Yuedan_comment')->data($data)->add();
        if($res){
            D('Yuedan_service_order')->where(array('order_id'=>$_POST['order_id']))->data(array('status'=>5))->save();
            D('Yuedan_service_release')->where(array('rid'=>$orderInfo['rid']))->setInc('comment_sum');
            $this->success_tips('提交成功！', U('Yuedan/my_order'));
        }else{
            $this->error_tips('评论失败请重试！');
        }
    }

    //留言交流
    public function message(){
        $orderInfo = D('Yuedan_service_order')->where(array('order_id'=>$_GET['order_id']))->find();
        // dump($orderInfo);
        $this->assign('orderInfo',$orderInfo);
        $messageList = D('Yuedan_message')->where(array('order_id'=>$orderInfo['order_id']))->order('add_time desc')->select();
        foreach ($messageList as $key => $value) {
            if($value['uid'] == $this->user_session['uid']){
                $messageList[$key]['nickname'] = $this->user_session['nickname'];
                $messageList[$key]['avatar'] = $this->user_session['avatar'];
                $messageList[$key]['sort'] = 'right';
            }else{
                $userInfo = D('User')->where(array('uid'=>$value['uid']))->find();
                $messageList[$key]['nickname'] = $userInfo['nickname'];
                $messageList[$key]['avatar'] = $userInfo['avatar'];
                $messageList[$key]['sort'] = 'left';
            }
        }
        // dump($messageList);
        $this->assign('messageList',$messageList);
        $this->display();
    }

    public function ajax_message_data(){
        $data['type'] = $_POST['type'];
        $data['order_id'] = $_POST['order_id'];
        $data['content'] = $_POST['content'];
        $data['uid'] = $this->user_session['uid'];
        $data['add_time'] = time();
        $res = D('Yuedan_message')->data($data)->add();
        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'留言成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'留言失败，请重试！')));
        }
    }

    // 发布个人服务
    public function release(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $catListBak = D('Yuedan_category')->where(array('status'=>1))->order('cat_sort desc,cid asc')->select();
        $catList = array();
        foreach ($catListBak as $key => $value) {
            if($value['fcid'] == 0){
                $catList [$value['cid']]= $value;
            }
        }
        foreach ($catListBak as $k => $v) {
            if($v['fcid'] != 0){
                $catList [$v['fcid']]['catList'][$v['cid']]= $v;
            }
        }
        $this->assign('catList',$catList);

        // dump($catList);
        $agreementInfo = D('Yuedan_agreement')->where(array('key'=>'release_agreement'))->find();
        $this->assign('agreementInfo',$agreementInfo);

        $this->display();
    }

    public function miaoshu(){
        $this->display();
    }

    // 个人发布服务
    public function release_data(){

        $userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('now_money')->find();
        if($userInfo['now_money'] < $this->config['price_per_service']){
            exit(json_encode(array('error'=>3,'msg'=>'您的余额不足请前去充值')));
        }

        $data = $_POST;
        if($this->config['is_examine'] == 1){
            $data['status'] = 1;
        }else{
            $data['status'] =2;
        }
        $data['add_time'] = time();
        $data['uid'] = $this->user_session['uid'];
        $res = D('Yuedan_service_release')->data($data)->add();
        if($res){

            D('User')->where(array('uid'=>$this->user_session['uid']))->setDec('now_money',$this->config['price_per_service']);
            D('User_money_list')->add_row($this->user_session['uid'],2,$this->config['price_per_service'],"支付约单平台费用 ".$this->config['price_per_service']." 元");

            if($this->config['is_examine'] == 1){
                exit(json_encode(array('error'=>1,'msg'=>'发布成功，等待系统审核。')));
            }else{
                exit(json_encode(array('error'=>1,'msg'=>'发布成功')));
            }
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'添加失败，请重试，或者联系管理员。')));
        }
    }

    // 个人中心主页
    public function my_index(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->find();
        $this->assign('userInfo',$userInfo);

        $this->display();
    }


    //技能管理 我的服务
    public function my_service_list(){
        $service_list = D('Yuedan_service_release')->where(array('uid'=>$this->user_session['uid']))->order('add_time desc')->select();
        foreach ($service_list as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            $service_list[$key]['img'] = $ingList;
            $service_list[$key]['listimg'] = $ingList[0];
        }
        $this->assign('service_list',$service_list);
        $this->display();
    }

    //删除 技能管理 我的服务
    public function my_service_del(){
        if(D('Yuedan_service_release')->where(array('rid'=>$_POST['rid'],'uid'=>$this->user_session['uid']))->delete()){
            exit(json_encode(array('error'=>1,'msg'=>'删除成功!')));
        }else{
            exit(json_encode(array('error'=>1,'msg'=>'删除失败!请重试。')));
        }
    }

    //修改 技能管理 我的服务
    public function my_service_save(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }

        $myServiceInfo = D('Yuedan_service_release')->where(array('rid'=>$_GET['rid'],'uid'=>$this->user_session['uid']))->find();
        $ingList = array_filter(explode(';', $myServiceInfo['img']));
        $myServiceInfo['img'] = $ingList;
        $myServiceInfo['imgCount'] = count($ingList);
        $this->assign('myServiceInfo',$myServiceInfo);

        $catListBak = D('Yuedan_category')->where(array('status'=>1))->order('cat_sort desc,cid asc')->select();
        $catList = array();
        foreach ($catListBak as $key => $value) {
            if($value['fcid'] == 0){
                $catList [$value['cid']]= $value;
            }
        }
        foreach ($catListBak as $k => $v) {
            if($v['fcid'] != 0){
                $catList [$v['fcid']]['catList'][$v['cid']]= $v;
            }
        }
        $this->assign('catList',$catList);

        $agreementInfo = D('Yuedan_agreement')->where(array('key'=>'release_agreement'))->find();
        $this->assign('agreementInfo',$agreementInfo);
        $this->display();
    }

    //修改 技能管理 我的服务
    public function my_service_save_data(){
        $data = $_POST;
        if($this->config['is_examine'] == 1){
            $data['status'] = 1;
        }else{
            $data['status'] =2;
        }
        $data['add_time'] = time();
        $res = D('Yuedan_service_release')->where(array('rid'=>$_POST['rid'],'uid'=>$this->user_session['uid']))->data($data)->save();
        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'修改成功！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'修改失败，请重试，或者联系管理员。')));
        }

    }


    //关闭技能
    public function my_service_close(){
        if(D('Yuedan_service_release')->where(array('rid'=>$_POST['rid'],'uid'=>$this->user_session['uid']))->save(array('status'=>$_POST['status']))){
            exit(json_encode(array('error'=>1,'msg'=>'操作成功!')));
        }else{
            exit(json_encode(array('error'=>1,'msg'=>'操作失败!请重试。')));
        }
    }

    public function authentication(){
        // echo $this->config['authentication_price'];
        $authentication_info = D('Yuedan_authentication')->where(array('uid'=>$this->user_session['uid']))->find();
        if($authentication_info){

            $authentication_info['authentication_field'] = unserialize($authentication_info['authentication_field']);
            $this->assign('authentication_info',$authentication_info);
            if($authentication_info['authentication_status'] == 3){
                $authentication_config_list = D('Yuedan_authentication_config')->order('type asc')->select();
                foreach ($authentication_config_list as $key => $value) {
                    if($value['type'] == 1){
                        $authentication_wenben[] = $value;
                    }else{
                        $authentication_tupian[] = $value;
                    }
                }
                $this->assign('authentication_wenben',$authentication_wenben);
                $this->assign('authentication_tupian',$authentication_tupian);

                $this->display('authentication_edit');
            }else{
                $this->display('authentication_index');
            }
        }else{
            $authentication_config_list = D('Yuedan_authentication_config')->order('type asc')->select();
            foreach ($authentication_config_list as $key => $value) {
                if($value['type'] == 1){
                    $authentication_wenben[] = $value;
                }else{
                    $authentication_tupian[] = $value;
                }
            }
            $this->assign('authentication_wenben',$authentication_wenben);
            $this->assign('authentication_tupian',$authentication_tupian);
            $this->display();
        }
        
    }

    public function authentication_data(){

        $data['uid'] = $this->user_session['uid']; 
        $data['authentication_status'] = 1;
        $data['authentication_time'] = time();
        $authentication_id = $_POST['authentication_id'];
        unset($_POST['imgFile']);
        unset($_POST['authentication_id']);
        foreach ($_POST as $key => $value) {
            if(!$value['value']){
                $this->error_tips($value['title'].'不可以为空！');
            }
        }
        $data['authentication_field'] = serialize($_POST);

        if($authentication_id){
            $res = D('Yuedan_authentication')->where(array('authentication_id'=>$authentication_id))->data($data)->save();
            if($res){
                //扣款写记录
                $this->success_tips('提交成功！', U('Yuedan/authentication'));
            }else{
                $this->error_tips('提交失败请重试');
            }
        }else{
            $userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->find();
            if($userInfo['now_money'] < $this->config['authentication_price']){
                $this->error_tips('帐号余额不足');
            }
            $data['authentication_price'] = $this->config['authentication_price'];
            $res = D('Yuedan_authentication')->data($data)->add();
            if($res){
                //扣款写记录
                D('User')->where(array('uid'=>$this->user_session['uid']))->setDec('now_money',$this->config['authentication_price']);
                D('User_money_list')->add_row($this->user_session['uid'],2,$this->config['authentication_price'],"支付约单服务认证费 ".$this->config['authentication_price']." 元"); 
                $this->success_tips('提交成功！', U('Yuedan/authentication'));
            }else{
                $this->error_tips('提交失败请重试');
            }
        }
        

        
        
    }





    //收藏功能
    public function collection(){
        $info = D('Yuedan_collection')->where(array('rid'=>intval($_POST['rid']),'uid'=>$this->user_session['uid']))->find();
        if($info){
            if(D('Yuedan_collection')->where(array('rid'=>intval($_POST['rid']),'uid'=>$this->user_session['uid']))->delete()){
                exit(json_encode(array('error'=>3,'msg'=>'取消收藏成功')));
            }
            
        }
        $res = D('Yuedan_collection')->data(array('rid'=>$_POST['rid'],'uid'=>$this->user_session['uid']))->add();
        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'收藏成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'收藏失败请重试')));
        }
    }

    public function my_collection(){
        $collectionList = D('')->table(array(C('DB_PREFIX').'yuedan_collection'=>'col',C('DB_PREFIX').'yuedan_service_release'=>'sr'))->where("col.uid= '".$this->user_session['uid']."' AND sr.rid = col.rid")->select();

        foreach ($collectionList as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            $collectionList[$key]['img'] = $ingList;
            $collectionList[$key]['listimg'] = $ingList[0];
        }

        $this->assign('collectionList',$collectionList);
        $this->display();
    }



    // 我的地址
    public function adress() {
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！');
        }

        $adress_list = D('User_adress')->get_adress_list($this->user_session['uid']);
        if (empty($adress_list)) {
            redirect(U('Yuedan/edit_adress', $_GET));
        } else {
            
            if ($_GET['rid']) {
                $select_url = 'Yuedan/next_order';
            }

            if ($select_url) {
                $this->assign('back_url', U($select_url, $_GET));
            } else {
                $this->assign('back_url', U('Yuedan/my_index'));
            }
            $param = $_GET;

            foreach ($adress_list as $key => $value) {
                $param['adress_id'] = $value['adress_id'];
                if (! empty($select_url)) {
                    $adress_list[$key]['select_url'] = U($select_url, $param);
                }
                $adress_list[$key]['edit_url'] = U('Yuedan/edit_adress', $param);
                $adress_list[$key]['del_url'] = U('Yuedan/del_adress', $param);
            }
            $this->assign('adress_list', $adress_list);
            $this->display();
        }
    }


    /*添加编辑地址*/
    public function edit_adress(){
        if(IS_POST){
            if(empty($_POST['adress'])){
                $this->error('您的位置没有选择！请点击选择位置进行完善！');
            }
            if(D('User_adress')->post_form_save($this->user_session['uid']) !== false){
                cookie('user_address', 0);
                $param['group_id'] =$_POST['group_id'];
                $param['store_id'] =$_POST['store_id'];
                $param['gift_id'] =$_POST['gift_id'];
                $param['buy_type'] =$_POST['buy_type'];
                $param['classify_userinput_id'] =$_POST['classify_userinput_id'];
                $param['current_id'] =$_POST['current_id'];
                $this->success('保存成功！',U('adress',$param));
            }else{
                $this->error('地址保存失败！请重试');
            }
        }else{
            $database_area = D('Area');
            $id = $_GET['adress_id'];
            if(cookie('user_address') === '0' || cookie("user_address") == "") {
                $now_adress = D('User_adress')->get_adress($this->user_session['uid'], $id);
                if ($now_adress) {
                    $this->assign('now_adress', $now_adress);

                    $province_list = $database_area->get_arealist_by_areaPid(0);
                    $this->assign('province_list',$province_list);

                    $city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
                    $this->assign('city_list', $city_list);

                    $area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
                    $this->assign('area_list', $area_list);
                } else {
                    $now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
                    $this->assign('now_city_area',$now_city_area);

                    $province_list = $database_area->get_arealist_by_areaPid(0);
                    $this->assign('province_list',$province_list);

                    $city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
                    $this->assign('city_list',$city_list);

                    $area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
                    $this->assign('area_list',$area_list);
                }
            } else {
                $cookie = json_decode($_COOKIE['user_address'], true);
                $now_adress = $cookie;
                $now_adress['default'] = $now_adress['defaul'];
                $now_adress['adress_id'] = $now_adress['id'];
                $this->assign('now_adress', $now_adress);
                $province_list = $database_area->get_arealist_by_areaPid(0);
                $this->assign('province_list',$province_list);

                $city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
                $this->assign('city_list', $city_list);

                $area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
                $this->assign('area_list', $area_list);
            }

            $params = $_GET;
            unset($params['adress_id']);
            $this->assign('params',$params);
        }

        $this->display();
    }



    /* 地图 */
    public function adres_map(){
        $params = $_GET;
        unset($params['adress_id']);
        $this->assign('params',$params);
        
        //得到所有城市并以城市首拼排序
        $database_area = D('Area');
        $all_city = S('all_city_address');
        if(empty($all_city)){
            $database_field = '`area_id`,`area_name`,`area_url`,`first_pinyin`,`is_hot`';
            $condition_all_city['area_type'] = 2;
            $all_city_old = $database_area->field($database_field)->where($condition_all_city)->order('`first_pinyin` ASC,`area_id` ASC')->select();
            foreach($all_city_old as $key=>$value){
                //首拼转成大写
                if(!empty($value['first_pinyin'])){
                    $first_pinyin = strtoupper($value['first_pinyin']);
                    $all_city[$first_pinyin][] = $value;
                }
            }
            S('all_city_address',$all_city);
        }
        $this->assign('all_city',$all_city);
        
        $this->display();
    }
    /*删除地址*/
    public function del_adress(){
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！');
        }
        $result = D('User_adress')->delete_adress($this->user_session['uid'],$_GET['adress_id']);
        if($result){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }



    // 上传图片
    public function ajax_upload_file(){
        if($_FILES['imgFile']['error'] == 4){
            exit(json_encode(array('error'=>1,'msg'=>'没有选择图片')));
        }
        $upload_file = D('Image')->handle($this->user_session['uid'], 'yuedan', 0, array('size' => 4), false);
        if ($upload_file['error']){
            exit(json_encode(array('error'=>1,'msg'=>'上传失败，请重试！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'上传成功','url'=>$upload_file['url']['imgFile'])));
        }
        
    }

    //通过坐标获取城市  通过城市名字查询数据
    public function cityMatching(){
        $url = 'http://api.map.baidu.com/geocoder/v2/?output=json&ak=4c1bb2055e24296bbaef36574877b4e2&location='.$_GET['lat'].','.$_GET['lng'];
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlGet($url);
        $result = json_decode($result,true);
        $city_name = $result['result']['addressComponent']['city'];
        $long   =   strlen($city_name);
        if($long >= 7){
            $city_name    =   str_replace('市','',$city_name);
            $city_name    =   str_replace('地区','',$city_name);
            $city_name    =   str_replace('特别行政区','',$city_name);
            $city_name    =   str_replace('特別行政區','',$city_name);
            $city_name    =   str_replace('蒙古自治州','',$city_name);
            $city_name    =   str_replace('回族自治州','',$city_name);
            $city_name    =   str_replace('柯尔克孜自治州','',$city_name);
            $city_name    =   str_replace('哈萨克自治州','',$city_name);
            $city_name    =   str_replace('土家族苗族自治州','',$city_name);
            $city_name    =   str_replace('藏族羌族自治州','',$city_name);
            $city_name    =   str_replace('傣族自治州','',$city_name);
            $city_name    =   str_replace('布依族苗族自治州','',$city_name);
            $city_name    =   str_replace('苗族侗族自治州','',$city_name);
            $city_name    =   str_replace('壮族苗族自治州','',$city_name);
            $city_name    =   str_replace('澳门','澳門',$city_name);
			$city_name 	  =   str_replace('哈尼族彝族自治州','',$city_name);
        }
        $database_area = D('Area');
        $database_field = '`area_id`,`area_name`,`area_url`';
        $condition_all_city['area_name'] = $city_name;
        $condition_all_city['area_type'] = 2;
        $condition_all_city['is_open'] = 1;
        $oCity = $database_area->field($database_field)->where($condition_all_city)->find();
        if($oCity){
            exit(json_encode(array('error'=>1,'msg'=>'获取成功','area_id'=>$oCity['area_id'],'area_name'=>$oCity['area_name'])));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'当前城市未开启，请选择别的城市')));
        }

    }



    //用户详情页面
    public function user_details(){
        D('User')->where(array('uid'=>$_GET['uid']))->setInc('page_view');
        $user_info = D('User')->where(array('uid'=>$_GET['uid']))->find();
        $gradeUser = D('Yuedan_grade_user')->where(array('uid'=>$_GET['uid']))->find();
        $this->assign('gradeUser',$gradeUser);
        
        $authentication = D('Yuedan_authentication')->where(array('uid'=>$this->user_session['uid'],'authentication_status'=>2))->find();
        $this->assign('authentication',$authentication);

        if($_GET['juli'] < 1000){
            $user_info['juli'] = '< 1 ';
        }else{
            $user_info['juli'] = $_GET['juli']/1000;
        }

        $this->assign('user_info',$user_info);

        $service_photos = array();

        $service_list = D('Yuedan_service_release')->where(array('uid'=>$_GET['uid'],'status'=>2))->select();
        foreach ($service_list as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            $service_list[$key]['img'] = $ingList;
            foreach ($ingList as $imgv) {
                $service_photos[] = $imgv;
            }
            $service_list[$key]['listimg'] = $ingList[0];
        }
        $this->assign('service_list',$service_list);
        $this->assign('service_photos',$service_photos);
        $this->display();
    }


    public function user_photos(){

        $service_photos = array();

        $service_list = D('Yuedan_service_release')->where(array('uid'=>$_GET['uid'],'status'=>2))->select();
        foreach ($service_list as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            foreach ($ingList as $imgv) {
                $service_photos[] = $imgv;
            }
        }
        $this->assign('service_photos',$service_photos);
        $this->display();
    }



    public function handbook(){
        $count = D('Yuedan_handbook')->where()->count();
        import('@.ORG.system_page');
        $p = new Page($count, 30);
        $handbook_list = D('Yuedan_handbook')->where()->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('handbook_list',$handbook_list);
        $this->display();
    }

    public function handbook_detail(){
        $handbook_info = D('Yuedan_handbook')->where(array('handbook_id'=>$_GET['handbook_id']))->find();
        $this->assign('handbook_info',$handbook_info);
        $this->display(); 
    }

    public function grade(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $userGradeInfo = D('Yuedan_grade_user')->where(array('uid'=>$this->user_session['uid']))->find();
        if($userGradeInfo){
            $grade = $userGradeInfo['grade'];
        }else{
            $grade = 0;
        }
        $gradeList = D('Yuedan_grade')->where(array('grade'=>array('gt',$grade)))->select();
        $this->assign('gradeList',$gradeList);
        if($userGradeInfo){
            $this->assign('userGradeInfo',$userGradeInfo);
        }
        $this->display();
    }

    public function buy_grade(){
        

        $grade_user_info = D('Yuedan_grade_user')->where(array('uid'=>$this->user_session['uid']))->find();
        $grade_info = D('Yuedan_grade')->where(array('grade_id'=>$_POST['grade_id']))->find();
        $userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('now_money')->find();
        if($userInfo['now_money']<$grade_info['money']){
            exit(json_encode(array('error'=>3,'msg'=>'您的余额不足请前去充值')));
        }

        if($grade_user_info){
            $pay_money = $grade_info['money']-$grade_user_info['money'];
        }else{
            $pay_money = $grade_info['money'];
        }


        $order_data['uid'] = $this->user_session['uid'];
        $order_data['pay_money'] = $pay_money;
        $order_data['grade_money'] = $grade_info['money'];
        $order_data['grade_id'] = $grade_info['grade_id'];
        $order_data['add_time'] = time();
        $order_data['status'] = 1;

        $grade_user_data['grade_id'] = $grade_info['grade_id'];
        $grade_user_data['grade'] = $grade_info['grade'];
        $grade_user_data['uid'] = $this->user_session['uid'];
        $grade_user_data['money'] = $grade_info['money'];
        $grade_user_data['precent'] = $grade_info['precent'];
        $grade_user_data['add_time'] =time();

        if($grade_user_info){
            $u_res = D('Yuedan_grade_user')->where(array('uid'=>$this->user_session['uid'],'user_grade_id'=>$grade_user_info['user_grade_id']))->data($grade_user_data)->save();
        }else{
            $u_res = D('Yuedan_grade_user')->data($grade_user_data)->add();
        }

        $o_res = D('Yuedan_grade_order')->data($order_data)->add();

        if($o_res){
            D('User')->where(array('uid'=>$this->user_session['uid']))->setDec('now_money',$pay_money);
            D('User_money_list')->add_row($this->user_session['uid'],2,$pay_money,"支付等级费用费用 ".$pay_money." 元");
            exit(json_encode(array('error'=>1,'msg'=>'购买成功！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'操作失败！请重试~')));
        }

    }


    /** 
    * 计算两组经纬度坐标 之间的距离 
    * params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km); 
    * return m or km 
    */ 
    function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 2, $decimal = 2){
        $EARTH_RADIUS = 6378.137; //地球半径
        $PI = 3.1415926;
        $radLat1 = $lat1 * $PI / 180.0; 
        $radLat2 = $lat2 * $PI / 180.0; 
        $a = $radLat1 - $radLat2; 
        $b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0); 
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2))); 
        $s = $s * $EARTH_RADIUS; 
        $s = round($s * 1000); 
        if ($len_type > 1){
            $s /= 1000; 
        } 
        return round($s); 
    }



    /**
     * 
     * @param  $latitude    纬度    
     * @param  $longitude    经度
     * @param  $raidus        半径范围(单位：米)
     * @return multitype:number
     */
    public function getAround($latitude,$longitude,$raidus){
        $PI = 3.14159265;
        $degree = (24901*1609)/360.0;
        $dpmLat = 1/$degree;
        $radiusLat = $dpmLat*$raidus;
        $minLat = $latitude - $radiusLat;
        $maxLat = $latitude + $radiusLat;
        $mpdLng = $degree*cos($latitude * ($PI/180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng*$raidus;
        $minLng = $longitude - $radiusLng;
        $maxLng = $longitude + $radiusLng;
        return array (minLat=>$minLat, maxLat=>$maxLat, minLng=>$minLng, maxLng=>$maxLng);
    }




    
}