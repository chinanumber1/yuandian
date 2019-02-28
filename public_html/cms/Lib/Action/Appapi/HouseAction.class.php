<?php
class HouseAction  extends BaseAction{
    protected $village_bind;
    public $pay_list_type = array(
        'property'=>'物业费',
        'water'=>'水费',
        'electric'=>'电费',
        'gas'=>'燃气费',
        'park'=>'停车费',
        'custom'=>'其他缴费',
    );

    protected $send_time_type = array('分钟', '小时', '天', '周', '月');

    // 手机app 判定当前用户是否为小区绑定用户
    public function check_village_session($village_id, $info){
        if(!empty($village_id)){
            $House_village_user_bindModel = D('House_village_user_bind');
            $bind_village_list = $House_village_user_bindModel->field(true)->where(array('uid' => $info['uid'], 'village_id' => $village_id))->order('`pigcms_id` DESC')->select();

            if(!$bind_village_list){
                $bind_village_list = $House_village_user_bindModel->where(array('uid' => $info['uid'], 'village_id' => $village_id , 'parent_id' => array('neq',0)))->order('`pigcms_id` DESC')->select();
                if(!$bind_village_list){
                    $bind_village_list = $House_village_user_bindModel->field(true)->where(array('uid' => $bind_village_list[0]['parent_id'], 'village_id' => $village_id))->order('`pigcms_id` DESC')->select();
                }
            }
            if(!empty($bind_village_list)){
                if(count($bind_village_list) == 1){
                    return $bind_village_list[0];
                }else{
                    return array();
                }
            }
        }else{
            return array();
        }
    }

    public function config(){
        $config = M('Appapi_app_config')->select();
        foreach ($config as $v) {
            if ($v['var'] == 'village_android_v') {
                $arr['android_version'] = $v['value'];
            } elseif ($v['var'] == 'village_android_url') {
                $arr['android_downurl'] = $v['value'];
            } elseif ($v['var'] == 'village_android_vcode') {
                $arr['android_version_code'] = $v['value'];
            } elseif ($v['var'] == 'village_android_vdesc') {
                $arr['android_version_version_desc'] = $v['value'];
            }
        }
    }


    //	小区列表
    public function index(){
        $long_lat	=	array(
            'lat'	=>	I('lat',0),
            'long'	=>	I('long',0),
        );
        $city	=	I('city',C('config.now_city'));
        C('config.now_city',$city);
        $keyword	=	I('keyword');
        $_GET['page']	=	I('page',1);
        $House_village	=	D('House_village');
        //	查询社区列表
        $aHouseVillageList	=	$House_village->wap_get_list($long_lat,$keyword);
        $arr	=	array();
        if($aHouseVillageList){
            foreach($aHouseVillageList['village_list'] as $k=>$v){
                $arr['village_list'][$k]['village_id']	=	$v['village_id'];
                $arr['village_list'][$k]['village_name']	=	$v['village_name'];
                $arr['village_list'][$k]['village_address']	=	$v['village_address'];
                $arr['village_list'][$k]['range']	=	$v['range'];
                $arr['village_list'][$k]['market_url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Housemarket&a=index&village_id=' . $v['village_id'];
            }
            $arr['totalPage']	=	$aHouseVillageList['totalPage'];
            $arr['village_count']	=	$aHouseVillageList['village_count'];
        }else{
            $arr['village_list']	=	array();
            $arr['totalPage']		=	0;
            $arr['village_count']	=	0;
        }
        if($keyword){
            $arr['village_me']	=	array();
        }else{
            $ticket	=	I('ticket');
            $info = ticket::get($ticket,$this->DEVICE_ID,true);
            if($info){
                //	查询我居住的社区列表
                $bindList	=	$House_village->get_bind_list($info['uid'],'',true,$long_lat);
                if($bindList){
                    foreach($bindList as $k=>$v){
                        $arr['village_me'][$k]['village_id']	=	$v['village_id'];
                        $arr['village_me'][$k]['village_name']	=	$v['village_name'];
                        $arr['village_me'][$k]['village_address']	=	$v['village_address'];
                        $arr['village_me'][$k]['range']			=	$v['range'];
                        $arr['village_me'][$k]['market_url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Housemarket&a=index&village_id=' . $v['village_id'];
                    }
                }else{
                    $arr['village_me']	=	array();
                }

                $database_house_village_user_bind = D('House_village_user_bind');
                $database_house_village = D('House_village');
                $now_user =  D('User')->get_user($info['uid']);
                $where['phone'] = $now_user['phone'];
                $where['parent_id'] = array('neq',0);
                $where['status'] = array('eq',1); //2017-05-09
                $parent_id_arr = $database_house_village_user_bind->where($where)->getField('pigcms_id,parent_id');
                if(!empty($parent_id_arr)){
                    $Map['pigcms_id'] = array('in' , $parent_id_arr);
                    $Map['uid'] = array('gt' , 0);
                    $bind_family_list = $database_house_village->get_bind_family_list($Map);
                    foreach($bind_family_list as $v){
                        $tmp['range'] = getRange(getDistance($v['lat'],$v['long'],$long_lat['lat'],$long_lat['long']));
                        $tmp['village_id'] = $v['village_id'];
                        $tmp['village_name'] = $v['village_name'];
                        $tmp['village_address'] = $v['village_address'];
                        $tmp['market_url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Housemarket&a=index&village_id=' . $v['village_id'];
                        $arr['village_me'][]=$tmp;
                    }
                }
            }else{
                $arr['village_me']	=	array();
            }
        }
        $this->returnCode(0,$arr);
    }
    //	我的小区列表
    public function bind_list(){
        $long_lat	=	array(
            'lat'	=>	I('lat',0),
            'long'	=>	I('long',0),
        );
        $House_village	=	D('House_village');
        $ticket	=	I('ticket');
        $info = ticket::get($ticket, $this->DEVICE_ID, true);
        $bindList	=	$House_village->get_bind_list($info['uid'],'',true,$long_lat);
        $arr	=	array();
        if($bindList){
            foreach($bindList as $k=>$v){
                $arr[$k]['village_id']	=	$v['village_id'];
                $arr[$k]['village_name']	=	$v['village_name'];
                $arr[$k]['village_address']	=	$v['village_address'];
                $arr[$k]['range']		=	$v['range'];
            }
        }
        $this->returnCode(0,$arr);
    }
    //	我的小区里的房子
    public function village_list(){
        if($_POST['app_version']>=500){
            $this->village_list_new();
        }else{
            $this->village_list_old();
        }
    }

    // 我的小区里的房子
    public function village_list_old(){
        $ticket =   I('ticket');
        if(empty($ticket)){
            $this->returnCode('20044013');
        }
        $info = ticket::get($ticket,$this->DEVICE_ID,true);
        if(empty($info)){
            $this->returnCode('20000009');
        }
        $village_id =   I('village_id');
        if(empty($village_id)){
            $this->returnCode('30000001');
        }
        $this->get_village($village_id);
        $House_village  =   D('House_village_user_bind');
        $village_list   =   $House_village->get_user_bind_list($info['uid'],$village_id);
        $arr    =   array();
        if($village_list){
            foreach($village_list as $k=>$v){
                $arr[$k]    =   array(
                    'pigcms_id' =>  $v['pigcms_id'],
                    'address'   =>  $v['address'],
                    'usernum'   =>  $v['usernum'],
                    'parent_id' =>  $v['parent_id'],
                );
            }
        }else{
            $village_list   =   $House_village->get_family_user_bind_list($info['uid'],$village_id);
            foreach($village_list as $k=>$v){
                $arr[$k]    =   array(
                    'pigcms_id' =>  $v['pigcms_id'],
                    'address'   =>  $v['address'],
                    'usernum'   =>  $v['usernum'],
                    'parent_id' =>$v['parent_id']
                );
            }
        }
        $this->returnCode(0,$arr);
    }

    // 我的小区里的房子
    public function village_list_new(){
        $ticket =   I('ticket');
        if(empty($ticket)){
            $this->returnCode('20044013');
        }
        $info = ticket::get($ticket,$this->DEVICE_ID,true);
        if(empty($info)){
            $this->returnCode('20000009');
        }
        $village_id =   I('village_id');
        if(empty($village_id)){
            $this->returnCode('30000001');
        }
        $this->get_village($village_id);
        $House_village  =   D('House_village_user_bind');
        $village_list   =   $House_village->get_user_bind_list($info['uid'],$village_id);
        $arr    =   array(
            'bind' => array(),
            'url' => ''
        );
        if($village_list){
            foreach($village_list as $k=>$v){
                $arr['bind'][$k]    =   array(
                    'pigcms_id' =>  $v['pigcms_id'],
                    'address'   =>  $v['address'],
                    'usernum'   =>  $v['usernum'],
                    'parent_id' =>  $v['parent_id'],
                );
            }
        }else{
            $village_list   =   $House_village->get_family_user_bind_list($info['uid'],$village_id);
            foreach($village_list as $k=>$v){
                $arr['bind'][$k]    =   array(
                    'pigcms_id' =>  $v['pigcms_id'],
                    'address'   =>  $v['address'],
                    'usernum'   =>  $v['usernum'],
                    'parent_id' =>$v['parent_id']
                );
            }
            if(empty($village_list)){
                // 查询是否存在申请中的数据
                $where['village_id'] = $village_id;
                $where['is_del'] = 0;
                $where['uid'] = $info['uid'];
                $result = D('House_village_user_vacancy')->where($where)->find();
               
                $pigcms_type    =   I('pigcms_type',1);
                if($result){
                    $arr['url']  =   $this->config['site_url'].U('Wap/House/my_village_list');
                    if($pigcms_type == 1){
                        $arr['url']   =   str_replace('appapi.php','wap.php',$arr['url']);
                    }else if($pigcms_type == 2){
                        $arr['url']   =   str_replace('appapi.php','wap_house.php',$arr['url']);
                    }
                }
            }
        }
        $this->returnCode(0,$arr);
    }
    //	社区--首页
    public  function village_index(){
        if($_POST['app_version']>=150){
            $this->village_new();
        }else{
            $this->village_old();
        }
    }

    public function village_old(){
        $village_id	=	I('village_id');
        $pigcms_type	=	I('pigcms_type',1);
        if(empty($village_id)){
            $this->returnCode('30000001');
        }

        $database_shequ_slider = D('House_village_slider');
        $now_village = $this->get_village($village_id);
        $has_slide = $this->getHasConfig($now_village['village_id'],'has_slide');
        if($has_slide){
            //幻灯片
            $where['village_id'] = $now_village['village_id'];
            $where['status'] = '1';
            $where['type'] = '0';
            $slider_list = $database_shequ_slider->where($where)->order('`sort` DESC,`id` ASC')->select();
            if($slider_list){
                foreach($slider_list as $k=>$v){
                    $slider[$k]['id'] 	= $v['id'];
                    $slider[$k]['name'] = $v['name'];
                    $slider[$k]['pic'] = $this->config['site_url'].'/upload/slider/'.$v['pic'];
                    $slider[$k]['url'] = $v['url'];
                }
            }else{
                $slider	=	array();
            }
        }else{
            $slider	=	array();
        }
        $arr['slider']	=	$slider;
        //找到模板排序
        $displayArr = explode(' ',$this->config['house_display']);
        $displayTplArr = array(
            1=>'village_index_news',
            2=>'village_index_pay',
            3=>'village_index_group',
            4=>'village_index_meal',
            5=>'village_index_appoint',
            6=>'village_index_bbs',
        );
        $displayIncludeTplArr = array();
        foreach($displayArr as $value){
            if($value>=1 && $value<=6){
                $displayIncludeTplArr[] = $displayTplArr[$value];
            }
        }
        $arr['sort']	=	$displayArr;
        $long_lat	=	array(
            'lat'	=>	I('lat'),
            'long'	=>	I('long'),
        );
        $user_long_lat = D('User_long_lat')->getLocation(1,0,$long_lat);
        foreach($displayIncludeTplArr as $v){
            if($v == 'village_index_news'){
                $news = D('House_village_news')->get_limit_list($now_village['village_id'],2);
                if($news){
                    foreach($news as $kk=>$vv){
                        $new_url	=	$this->config['site_url'].U('Wap/House/village_news',array('village_id'=>$village_id,'news_id'=>$vv['news_id']));
                        if($pigcms_type == 1){
                            $new_url	=	str_replace('/appapi.php?','/wap.php?',$new_url);
                        }else if($pigcms_type == 2){
                            $new_url	=	str_replace('/appapi.php?','/wap_house.php?',$new_url);
                        }
                        unset($news[$kk]['status'],$news[$kk]['is_hot'],$news[$kk]['cat_id'],$news[$kk]['is_notice'],$news[$kk]['content']);
                        $news[$kk]['add_time_s']	=	date('Y-m-d H:i',$vv['add_time']);
                        $news[$kk]['url']	=	$new_url;
                    }
                    $arr['news']['list']	=	$news;
                    $news_url	=	$this->config['site_url'].U('Wap/House/village_newslist',array('village_id'=>$village_id));
                    $news_url	=	str_replace('/appapi.php?','/wap_house.php?',$news_url);
                    if($pigcms_type == 1){
                        $news_url	=	str_replace('/appapi.php?','/wap.php?',$news_url);
                    }else if($pigcms_type == 2){
                        $news_url	=	str_replace('/appapi.php?','/wap_house.php?',$news_url);
                    }
                    $arr['news']['news_url']	=	$news_url;
                }else{
                    $arr['news']['list']	=	array();
                    $arr['news']['news_url']	=	'';
                }
                $arr['news']['sort']	=	'1';
            }
            if($v == 'village_index_pay'){
                if($this->config['house_bbsservice_limit']){
                    $category	= D('House_service_category')->getIndexCatList($now_village['village_id'],$this->config['house_bbsservice_limit']);
                }else{
                    $category	= D('House_service_category')->getIndexCatList($now_village['village_id'],16);
                }
                if($category){
                    foreach($category as $kk=>$vv){
                        $cat_url	=	substr($vv['cat_url'],0,3);
                        if($cat_url != 'htt'){
                            $category[$kk]['cat_url']	=	$this->config['site_url'].$vv['cat_url'];
                            if($pigcms_type == 1){
                                $category[$kk]['cat_url']	=	str_replace('/appapi.php?','/wap.php?',$category[$kk]['cat_url']);
                                $category[$kk]['cat_url']	=	str_replace('Appapi','Wap',$category[$kk]['cat_url']);
                            }else if($pigcms_type == 2){
                                $category[$kk]['cat_url']	=	str_replace('/appapi.php?','/wap_house.php?',$category[$kk]['cat_url']);
                                $category[$kk]['cat_url']	=	str_replace('Appapi','Wap',$category[$kk]['cat_url']);
                            }

                        }
                    }
                    $arr['category']['list']	=	$category;
                }else{
                    $arr['category']['list']	=	array();
                }
                $arr['category']['sort']	=	'2';
            }
            if($v == 'village_index_group'){
                $group = D('House_village_group')->get_limit_list($now_village['village_id'],3,$user_long_lat);
                if($group){
                    foreach($group as $kk=>$vv){
                        $group_url	=	$this->config['site_url'].$vv['url'];
                        if($pigcms_type == 1){
                            $group_url	=	str_replace('/appapi.php?','/wap.php?',$group_url);
                            $group_url	=	str_replace('Appapi','Wap',$group_url);
                        }else if($pigcms_type == 2){
                            $group_url	=	str_replace('/appapi.php?','/wap_house.php?',$group_url);
                            $group_url	=	str_replace('Appapi','Wap',$group_url);
                        }
                        $group_url	=	str_replace('g=Appapi','g=Wap',$group_url);
                        $group_list[$kk]	=	array(
                            'group_id'	=>	$vv['group_id'],
                            'group_name'	=>	$vv['group_name'],
                            'prefix_title'	=>	$vv['prefix_title'],
                            'price'			=>	$vv['price'],
                            'wx_cheap'		=>	$vv['wx_cheap'],
                            'sale_count'	=>	$vv['sale_count'],
                            'list_pic'		=>	$vv['list_pic'],
                            'range'			=>	isset($vv['range'])?$vv['range']:'',
                            'url'			=>	$group_url,
                            'pin_num'			=>	$vv['pin_num'],
                        );
                        if($vv['tuan_type'] == 2){
                            $group_list[$kk]['name']	=	$vv['s_name'];
                        }else{
                            $group_list[$kk]['name']	=	$vv['name'];
                        }
                    }
                    $arr['group']['list']		=	$group_list;
                    $list_url	=	$this->config['site_url'].U('Wap/House/village_grouplist',array('village_id'=>$village_id));
                    if($pigcms_type == 1){
                        $list_url	=	str_replace('/appapi.php?','/wap.php?',$list_url);
                        $list_url	=	str_replace('Appapi','Wap',$list_url);
                    }else if($pigcms_type == 2){
                        $list_url	=	str_replace('/appapi.php?','/wap_house.php?',$list_url);
                        $list_url	=	str_replace('Appapi','Wap',$list_url);
                    }
                    $arr['group']['list_url']	=	$list_url;
                }else{
                    $arr['group']['list']	=	array();
                    $arr['group']['list_url']	=	'';
                }
                $arr['group']['sort']	=	'3';
            }
            if($v == 'village_index_meal'){
                $meal = D('House_village_meal')->get_limit_list($now_village['village_id'],3,$user_long_lat);
                if($meal){
                    foreach($meal as $kk=>$vv){
                        $meal_url	=	$this->config['site_url'].$vv['wap_url'];
                        if($pigcms_type == 1){
                            $meal_url	=	str_replace('/appapi.php?','/wap.php?',$meal_url);
                            $meal_url	=	str_replace('Appapi','Wap',$meal_url);
                        }else if($pigcms_type == 2){
                            $meal_url	=	str_replace('/appapi.php?','/wap_house.php?',$meal_url);
                            $meal_url	=	str_replace('Appapi','Wap',$meal_url);
                        }
                        $meal_url	=	str_replace('g=Appapi','g=Wap',$meal_url);
                        $meal_list[$kk]	=	array(
                            'name'			=>	$vv['name'],
                            'adress'		=>	$vv['adress'],
                            'mean_money'	=>	$vv['mean_money'],
                            'sale_count'	=>	$vv['sale_count'],
                            'store_type'	=>	$vv['store_type'],
                            'list_pic'		=>	$vv['list_pic'],
                            'range'			=>	isset($vv['range'])?$vv['range']:'',
                            'state'			=>	isset($vv['state'])?$vv['state']:'',
                            'wap_url'		=>	$meal_url,
                        );
                    }
                    $arr['meal']['list']	=	$meal_list;
                    $list_url	=	$this->config['site_url'].U('village_meallist',array('village_id'=>$village_id));
                    if($pigcms_type == 1){
                        $list_url	=	str_replace('/appapi.php?','/wap.php?',$list_url);
                        $list_url	=	str_replace('Appapi','Wap',$list_url);
                    }else if($pigcms_type == 2){
                        $list_url	=	str_replace('/appapi.php?','/wap_house.php?',$list_url);
                        $list_url	=	str_replace('Appapi','Wap',$list_url);
                    }
                    $arr['meal']['list_url']=	$list_url;
                }else{
                    $arr['meal']['list']	=	array();
                    $arr['meal']['list_url']	=	'';
                }
                $arr['meal']['sort']	=	'4';
            }
            if($v == 'village_index_appoint'){
                $appoint = D('House_village_appoint')->get_limit_list($now_village['village_id'],3,$user_long_lat);
                if($appoint){
                    foreach($appoint as $kk=>$vv){
                        $appoint_url	=	$this->config['site_url'].$vv['url'];
                        if($pigcms_type == 1){
                            $appoint_url	=	str_replace('appapi.php?','wap.php?',$appoint_url);
                            $appoint_url	=	str_replace('Appapi','Wap',$appoint_url);
                        }else if($pigcms_type == 2){
                            $appoint_url	=	str_replace('appapi.php?','wap_house.php?',$appoint_url);
                            $appoint_url	=	str_replace('Appapi','Wap',$appoint_url);
                        }
                        $appoint_url	=	str_replace('g=Appapi','g=Wap',$appoint_url);
                        $appoint_list[$kk]	=	array(
                            'appoint_name'	=>	$vv['appoint_name'],
                            'payment_money'	=>	$vv['payment_money'],
                            'appoint_content'	=>	$vv['appoint_content'],
                            'appoint_sum'	=>	$vv['appoint_sum'],
                            'list_pic'		=>	$vv['list_pic'],
                            'range'			=>	isset($vv['range'])?$vv['range']:'',
                            'appoint_status'=>	$vv['appoint_status'],
                            'url'	=>	$appoint_url,
                        );
                    }
                    $arr['appoint']['list']	=	$appoint_list;
                    $list_url	=	$this->config['site_url'].U('village_appointlist',array('village_id'=>$village_id));
                    if($pigcms_type == 1){
                        $list_url	=	str_replace('/appapi.php?','/wap.php?',$list_url);
                        $list_url	=	str_replace('Appapi','Wap',$list_url);
                    }else if($pigcms_type == 2){
                        $list_url	=	str_replace('/appapi.php?','/wap_house.php?',$list_url);
                        $list_url	=	str_replace('Appapi','Wap',$list_url);
                    }
                    $arr['appoint']['list_url']	=	$list_url;
                }else{
                    $arr['appoint']['list']	=	array();
                    $arr['appoint']['list_url']	=	'';
                }
                $arr['appoint']['sort']	=	'5';
            }
            if($v == 'village_index_bbs'){
                $bbs = D('Bbs')->bbsHotAricle('house',$now_village['village_id'],$this->config['house_bbsarticle_limit']);
                if($bbs){
                    foreach($bbs['aricle'] as $kk=>$vv){
                        if($pigcms_type == 1){
                            $bbs_url	=	str_replace('wap.php?','wap.php?',$vv['url']);
                            $bbs_url	=	str_replace('Appapi','Wap',$vv['url']);
                        }else if($pigcms_type == 2){
                            $bbs_url	=	str_replace('wap.php?','wap_house.php?',$vv['url']);
                            $bbs_url	=	str_replace('Appapi','Wap',$vv['url']);
                        }
                        $aricle[$kk]	=	array(
                            'aricle_id'	=>	$vv['aricle_id'],
                            'aricle_img'	=>	$vv['aricle_img'],
                            'aricle_title'	=>	$vv['aricle_title'],
                            'aricle_praise_num'		=>	$vv['aricle_praise_num'],
                            'aricle_comment_num'		=>	$vv['aricle_comment_num'],
                            'url'		=>	$bbs_url,
                            'update_time'	=>	date('Y-m-d H:i',$vv['update_time']),
                        );
                    }
                    $arr['bbs']['aricle']	=	$aricle;
                    if($pigcms_type == 1){
                        $arr['bbs']['bbs_url']	=	str_replace('wap.php?','wap.php?',$bbs['bbs_url']);
                        $arr['bbs']['bbs_url']	=	str_replace('Appapi','Wap',$bbs['bbs_url']);
                    }else if($pigcms_type == 2){
                        $arr['bbs']['bbs_url']	=	str_replace('wap.php?','wap_house.php?',$bbs['bbs_url']);
                        $arr['bbs']['bbs_url']	=	str_replace('Appapi','Wap',$bbs['bbs_url']);
                    }
                }else{
                    $arr['bbs']['aricle']	=	array();
                    $arr['bbs']['bbs_url']	=	'';
                }
                $arr['bbs']['sort']	=	'6';
            }
        }
        if(empty($arr['news'])){
            $arr['news']	=	array(
                'list'	=>	array(),
                'news_url'	=>	'',
                'sort'	=>	'1',
            );
        }
        if(empty($arr['category'])){
            $arr['category']	=	array(
                'list'	=>	array(),
                'sort'	=>	'2',
            );
        }
        if(empty($arr['group'])){
            $arr['group']	=	array(
                'list'	=>	array(),
                'list_url'	=>	'',
                'sort'	=>	'3',
            );
        }
        if(empty($arr['meal'])){
            $arr['meal']	=	array(
                'list'	=>	array(),
                'list_url'	=>	'',
                'sort'	=>	'4',
            );
        }
        if(empty($arr['appoint'])){
            $arr['appoint']	=	array(
                'list'	=>	array(),
                'list_url'	=>	'',
                'sort'	=>	'5',
            );
        }
        if(empty($arr['bbs'])){
            $arr['bbs']	=	array(
                'aricle'	=>	array(),
                'bbs_url'	=>	'',
                'sort'	=>	'6',
            );
        }
        $this->returnCode(0,$arr);
    }

    public function village_new(){
        //$ticket	=	I('ticket');
        //$info = ticket::get($ticket, $this->DEVICE_ID, true);
        //if(empty($info)){
        //	$this->returnCode('20000009');
        //}
        //$now_user = D('User')->get_user($info['uid']);
        if(!$_POST['village_id']){
            $this->returnCode('20090003');
        }
        $ticket	=	I('ticket');
        $info = ticket::get($ticket, $this->DEVICE_ID, true);
        $database_shequ_slider = D('House_village_slider');

        $now_village = $this->get_village($_POST['village_id']);

        $slider_list = array();
        if($now_village['has_slide']){
            //幻灯片
            $where['village_id'] = $now_village['village_id'];
            $where['status'] = '1';
            $where['type'] = '0';
            $slider_list = $database_shequ_slider->where($where)->order('`sort` DESC,`id` ASC')->select();
            if(!empty($slider_list)){
                foreach($slider_list as $k=>$v){

                    $slider_list[$k]['url'] = $v['url'];
                    $slider_list[$k]['pic'] =  $this->config['site_url'].'/upload/slider/'.$slider_list[$k]['pic'];
                }
            }else{
                $slider_list = array();
            }


            $arr['silider_list'] = $slider_list;
        }

        //首页是否显示快店

        $arr['has_index_store']=!empty($now_village['has_index_store'])?$now_village['has_index_store']:'';

        // 首页是否处于游客
        $tourist = $this->getHasConfig($now_village['village_id'],'tourist');
        // 返回值 is_house_bind  0: 游客状态 1:　需要绑定 2： 跳转路径 is_redirect 数据
        if ($tourist != 1) {
            $bind_info = $this->check_village_session($_POST['village_id'], $info);
            if (!empty($bind_info)) {
                if ($bind_info['status'] != 1) {
                    $arr['is_house_bind'] = 2;
                    $arr['is_redirect'] = $this->config['site_url'] .'/wap.php?g=Wap&c=House&a=my_village_list';
                } else {
                    $arr['is_house_bind'] = 0;
                    $arr['is_redirect'] = '';
                }
            } else {
                $arr['is_house_bind'] = 1;
                $arr['is_redirect'] = '';
            }
        } else {
            $arr['is_house_bind'] = 0;
            $arr['is_redirect'] = '';
        }

        //5条最新新闻
        $news_list = D('House_village_news')->get_limit_list($now_village['village_id'],3);
        foreach ($news_list as &$nv) {
            $nv['add_time'] = date('m/d H:i',$nv['add_time']);
            $nv['content'] = '';
        }
        $arr['news_list']=empty($news_list)?array():$news_list;
        $arr['news_url']=$this->config['site_url'] .'/wap.php?g=Wap&c=House&a=village_newslist&village_id='.$_POST['village_id'];
        $arr['activity_listurl']=$this->config['site_url'] .'/wap.php?g=Wap&c=House&a=village_activitylist&village_id='.$_POST['village_id'];
        //}

        //if(in_array('2',$displayArr)){
        if($now_village["has_index_nav"]){
            $nav_where['village_id'] = $now_village['village_id'];
            $nav_where['status'] = 1;

            if($this->config['house_bbsservice_limit']){
                $tmp_index_service_cat_list = D('House_village_nav')->house_village_nav_page_list($nav_where,true,'sort desc',20);
            }else{
                $tmp_index_service_cat_list = D('House_village_nav')->house_village_nav_page_list($nav_where,true,'sort desc',99999);
            }

            $index_service_cat_list = array();
            foreach($tmp_index_service_cat_list['result']['list'] as $value){
                $value['img'] = $this->config['site_url'].'/upload/service/'.$value['img'];
                $value['url'] = html_entity_decode($value['url']);
                //$tmp_i = floor($key/8);
                $index_service_cat_list[] = $value;
            }
        }else{
            $index_service_cat_list = array(
                array(
                    'img'=>$this->config['site_url']."/tpl/Wap/pure/static/img/house_index_2.png",
                    'url'=>$this->config['site_url'].U('Wap/House/village_manager_list',array('village_id'=>$now_village['village_id'])),
                    'name'=>"小区管家",
                ),
                array(
                    'img'=>$this->config['site_url']."/tpl/Wap/pure/static/img/house_index_5.png",
                    'url'=>$this->config['site_url'].U('Wap/House/village_my_pay',array('village_id'=>$now_village['village_id'])),
                    'name'=>"生活缴费",
                ),
                array(
                    'img'=>$this->config['site_url']."/tpl/Wap/pure/static/img/house_index_4.png",
                    'url'=>$this->config['site_url'].U('Wap/Ride/ride_list',array('village_id'=>$now_village['village_id'])),
                    'name'=>"社区用车",
                ),
                array(
                    'img'=>$this->config['site_url']."/tpl/Wap/pure/static/img/house_index_1.png",
                    'url'=>$this->config['site_url'].U('Wap/Library/express_service_list',array('village_id'=>$now_village['village_id'],'app_no_header'=>1)),
                    'name'=>"快递代收",
                ),
                array(
                    'img'=>$this->config['site_url']."/tpl/Wap/pure/static/img/house_index_3.png",
                    'url'=>$this->config['site_url'].U('Wap/House/village_activitylist',array('village_id'=>$now_village['village_id'])),
                    'name'=>"社区活动",
                ),
                array(
                    'img'=>$this->config['site_url']."/tpl/Wap/pure/static/img/house_index_7.png",
                    'url'=>$this->config['site_url'].U('Wap/House/village_grouplist',array('village_id'=>$now_village['village_id'])),
                    'name'=>"周边".$this->config['group_alias_name'],
                ),
                array(
                    'img'=>$this->config['site_url']."/tpl/Wap/pure/static/img/house_index_6.png",
                    'url'=>$this->config['site_url'].U('Wap/House/shop',array('village_id'=>$now_village['village_id']."#cat-all")),
                    'name'=>"周边".$this->config['shop_alias_name'],
                ),
                array(
                    'img'=>$this->config['site_url']."/tpl/Wap/pure/static/img/house_index_8.png",
                    'url'=>$this->config['site_url'].U('Wap/House/village_more_list',array('village_id'=>$now_village['village_id'])),
                    'name'=>"更多",
                ),
            );
        }
        $arr['index_service_cat_list']=$index_service_cat_list;

        //社区活动
        $database_house_village_activity = D('House_village_activity');
        $activity_where['status'] = 1;
        $activity_where['village_id'] = $_POST['village_id'] ;
        $activity_where['pic'] = array('neq' , '');
        $activity_list = $database_house_village_activity->house_village_activity_page_list($activity_where,true,'sort desc',3);
        $activity_list = $activity_list['list']['list'];
        foreach ($activity_list as &$v) {
            $tmp['pic'] = $this->config['site_url'] . '/upload/activity/' . $v['pic'];
            $tmp['url'] = $this->config['site_url'] . str_replace('appapi.php', 'wap.php', $v['url']);
            if ($v["apply_end_time"] + 86400 > time()) {
                $tmp['time_str'] = '截至' . date('Y/m/d', $v['apply_end_time']);
            } else {
                $tmp['time_str'] = '已截止';
            }
            $tmp['title'] = $v['title'];
            //$tmp['content'] = $v['content'];
            $tmp['apply_fee'] = $v['apply_fee'];
            $activity_list_tmp[] = $tmp;
        }
        $arr['activity_list']=$activity_list_tmp?$activity_list_tmp:array() ;
        //}

        $this->returnCode(0,$arr);
    }

    // 进入社区的判断
    public function village_tourist(){
        //$ticket	=	I('ticket');
        //$info = ticket::get($ticket, $this->DEVICE_ID, true);
        //if(empty($info)){
        //	$this->returnCode('20000009');
        //}
        //$now_user = D('User')->get_user($info['uid']);
        if(!$_POST['village_id']){
            $this->returnCode('20090003');
        }
        $ticket	=	I('ticket');
        $info = ticket::get($ticket, $this->DEVICE_ID, true);

        $now_village = $this->get_village($_POST['village_id']);

        // 首页是否处于游客
        $tourist = $this->getHasConfig($now_village['village_id'],'tourist');

        // 返回值 is_house_bind  0: 游客状态 1:　需要绑定 2： 跳转路径 is_redirect 数据
        if ($tourist != 1) {
            $bind_info = $this->check_village_session($_POST['village_id'], $info);
            if (!empty($bind_info)) {
                if ($bind_info['status'] != 1) {
                    $arr['is_house_bind'] = 2;
                    $arr['is_redirect'] = $this->config['site_url'] .'/wap.php?g=Wap&c=House&a=my_village_list';
                } else {
                    $arr['is_house_bind'] = 0;
                    $arr['is_redirect'] = '';
                }
            } else {
                $arr['is_house_bind'] = 1;
                $arr['is_redirect'] = '';
            }
        } else {
            $arr['is_house_bind'] = 0;
            $arr['is_redirect'] = '';
        }

        $this->returnCode(0,$arr);
    }

    public function village_shop_list(){
        $now_village = $this->get_village($_POST['village_id']);
        $database_merchant_store_shop = D('Merchant_store_shop');
        $merchant_store_shop_where['lat'] = $now_village['lat'];
        $merchant_store_shop_where['long'] = $now_village['long'];
        $merchant_store_shop_lists = $database_merchant_store_shop->get_list_by_option($merchant_store_shop_where,3);
        // 同步微信端， 获取当前所在小区的前10条后台添加的店铺
        if ($_POST['village_id']) {
            $where = array('lat' => $now_village['lat'], 'long' => $now_village['long'],'village_id'=>$_POST['village_id']);
            $merchant_store_shop_lists = D('House_village_store')->get_list_by_option($where,10);
        }
        $merchant_store_shop_result = array();
        $deliver_type = 'all';
        $now_time = date('H:i:s');
        $return = array();
        foreach ($merchant_store_shop_lists['shop_list'] as $row) {
            $temp = array();
            $temp['store_id'] = $row['store_id'];
            $temp['name'] = $row['name'];
            $temp['store_theme'] = $row['store_theme'];
            $temp['isverify'] = $row['isverify'];
            $temp['juli_wx'] = $row['juli'];
            $temp['range'] = $row['range'];
            $temp['image'] = $this->config['site_url'].'/index.php?c=Image&a=thumb&width=180&height=120&url='.urlencode($row['image']);
            $temp['star'] = $row['score_mean'];
            $temp['month_sale_count'] = $row['sale_count'];
            $temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
            $temp['delivery'] = $temp['delivery'] ? true : false;
            $temp['delivery_time'] = $row['send_time'];//配送时长
            $temp['send_time_type'] = $row['send_time_type'];
            $temp['delivery_time_type'] = $this->send_time_type[$row['send_time_type']];
            $temp['delivery_price'] = floatval($row['basic_price']);//起送价
            $temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
            $temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
            $temp['is_close'] = 1;

            if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
                $temp['time'] = '24小时营业';
                $temp['is_close'] = 0;
            } else {
                $temp['time'] = $row['open_1'] . '~' . $row['close_1'];
                if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
                    $temp['is_close'] = 0;
                }
                if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
                    $temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
                    if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
                        $temp['is_close'] = 0;
                    }
                }
                if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
                    $temp['time'] .= ',' . $row['open_3'] . '~' . $row['close_3'];
                    if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
                        $temp['is_close'] = 0;
                    }
                }
            }

            $temp['coupon_list'] = array();
            if ($row['is_invoice']) {
                $temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
            }
            if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
                $temp['coupon_list']['discount'] = $row['store_discount']/10;
            }
            $system_delivery = array();
            foreach ($row['system_discount'] as $row_d) {
                if ($row_d['type'] == 0) {//新单
                    $temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                } elseif ($row_d['type'] == 1) {//满减
                    $temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                } elseif ($row_d['type'] == 2) {//配送
                    if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
                        $system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                    }
                }
            }
            foreach ($row['merchant_discount'] as $row_m) {
                if ($row_m['type'] == 0) {
                    $temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                } elseif ($row_m['type'] == 1) {
                    $temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                }
            }
            if ($row['deliver']) {
                if ($temp['delivery_system']) {
                    $system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
                } else {
                    if ($row['is_have_two_time']) {
                        if ($row['reach_delivery_fee_type2'] == 0) {
                            if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
                                $temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
                            }
                        } elseif ($row['reach_delivery_fee_type2'] == 1) {
                            //$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                        } elseif ($row['reach_delivery_fee_type2'] == 2) {
                            $row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
                        }
                    } else {
                        if ($row['reach_delivery_fee_type'] == 0) {
                            if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
                                $temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
                            }
                        } elseif ($row['reach_delivery_fee_type'] == 1) {
                            //$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                        } elseif ($row['reach_delivery_fee_type'] == 2) {
                            $row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
                        }
                    }
                }
            }
            $temp['coupon_count'] = count($temp['coupon_list']);
            $temp['coupon_list'] = $this->parseCoupon($temp['coupon_list'],'array');
            $temp['has_index_store'] = $now_village['hax_index_store'];
            $temp['deliver_name'] = isset($this->config['deliver_name']) && !empty($this->config['deliver_name']) ? $this->config['deliver_name'] : '平台配送';
            $return[] = $temp;
        }

        $this->returnCode(0,$return);
    }

    //便民
    public function house_service_new(){
        $now_village = $this->get_village($_POST['village_id']);

        //$hot_cat_list = D('House_service_category')->getHotCatList($now_village['village_id'],6);
        //foreach ($hot_cat_list as &$v) {
        //	$v['cat_url'] = $this->config['site_url'].str_replace('appapi','wap',$v['cat_url']);
        //	$v['cat_url'] =str_replace('Appapi','Wap',$v['cat_url']);
        //}
        $cat_list = D('House_service_category')->getAllCatList($now_village['village_id']);
        //if(!$cat_list){
        //$this->house_service_test();
        //$cat_list = D('House_service_category')->getAllCatList($now_village['village_id']);
        //}

        foreach ($cat_list as &$vv) {
            foreach ($vv['son_list'] as &$vvv) {
                $vvv['cat_img'] =  strpos($vvv['cat_img'],'tpl/Wap') !== false ? $this->config['site_url']."/".$vvv['cat_img'] : $vvv['cat_img'];
                if(empty($vvv['cat_url'])){
                    //$cat_url = $this->config['site_url'] . U('Wap/Houseservice/cat_list',array('village_id'=>$now_village['village_id'],'id'=>$vvv['id']));
                    $cat_url = U('Wap/Houseservice/cat_list',array('village_id'=>$now_village['village_id'],'id'=>$vvv['id']));
                    $vvv['cat_url'] = str_replace('appapi.php','wap.php',$cat_url);
                }


                //$vvv['cat_url'] = $this->config['site_url']."/".$vvv['cat_url'];
                $vvv['cat_url'] = strpos($vvv['cat_url'],'http')===0?$vvv['cat_url']:$this->config['site_url'].$vvv['cat_url'];
            }
            $tmp[] = $vv;
        }
        //$arr['hot_cat_lists']=$hot_cat_list;
        if(!empty($tmp)){
            $arr['cat_list']=$tmp;
        }else{
            $arr['cat_list']=array();
        }
        $this->returnCode(0,$arr);
    }

    private function get_village($village_id){
        $now_village = D('House_village')->get_one($village_id);
        if(empty($now_village)){
            $this->returnCode('20120001');
        }
        return $now_village;
    }
    private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
    }
    //	社区--便民服务
    public function house_service(){
        if($_POST['app_version']>=150){
            $this->house_service_new();
        }else{
            $this->house_service_old();
        }
    }
    public function house_service_old(){
        $village_id	=	I('village_id');
        $pigcms_type	=	I('pigcms_type',1);
        if(empty($village_id)){
            $this->returnCode('30000001');
        }
        $now_village = $this->get_village($village_id);
        $hot_cat_list = D('House_service_category')->getHotCatList($now_village['village_id'],6);
        //print_r($hot_cat_list);exit;



        if($hot_cat_list){
            // foreach($hot_cat_list as $k=>$v){
            // $url	=	htmlspecialchars_decode($v['cat_url']);
            // if($pigcms_type == 2){
            // $hot_cat_list[$k]['cat_url']	=	str_replace('wap.php','wap_house.php',$url);
            // }
            // }


            foreach($hot_cat_list as $k=>$v){
                $url	=	htmlspecialchars_decode($v['cat_url']);
                $url	=	substr($url,0,3);
                if($url != 'htt'){
                    $cat_url	=	$this->config['site_url'].$v['cat_url'];
                    $hot_cat_list[$k]['cat_url']	=	str_replace('appapi.php','wap.php',$cat_url);
                }
                if($pigcms_type == 2){
                    $hot_cat_list[$k]['cat_url']	=	str_replace('wap.php','wap_house.php',$url);
                }
            }
        }
        //幻灯片
        $has_service_slide = $this->getHasConfig($now_village['village_id'],'has_service_slide');
        if($has_service_slide){
            $where['village_id'] = $now_village['village_id'];
            $where['status'] = '1';
            $where['type'] = '1';
            $slider_list = M('House_village_slider')->where($where)->order('sort DESC,id ASC')->select();
            if($slider_list){
                foreach($slider_list as $k=>$v){
                    $url	=	htmlspecialchars_decode($v['url']);
                    if(stripos($url , 'LBS://')!==FALSE){
                        $url	=	wapLbsTranform($url);
                    }
                    if($pigcms_type == 2){
                        $url	=	str_replace('wap.php','wap_house.php',$url);
                    }
                    $slider[$k]['id']	=	$v['id'];
                    $slider[$k]['url']	=	$url;
                    $slider[$k]['name']	=	$v['name'];
                    $slider[$k]['pic']	=	$this->config['site_url'].'/upload/service/'.$v['pic'];
                }
            }
        }
        $cat_list = D('House_service_category')->getAllCatList($now_village['village_id']);
        if($cat_list){
            foreach($cat_list as $k=>$v){
                if(is_array($v)){
                    foreach($v['son_list'] as $kk=>$vv){
                        if(empty($vv['cat_url'])){
                            $cat_url = $this->config['site_url'] . U('Wap/Houseservice/cat_list',array('village_id'=>$now_village['village_id'],'id'=>$vv['id']));
                            $vv['cat_url'] = str_replace('appapi.php','wap.php',$cat_url);
                        }
                        $vvUrl	=	htmlspecialchars_decode($vv['cat_url']);
                        if(stripos($vvUrl , 'LBS://')!==FALSE){
                            $vvUrl	=	wapLbsTranform($vvUrl);
                        }
                        if($pigcms_type == 2){
                            $vvUrl	=	str_replace('wap.php','wap_house.php',$vvUrl);
                        }
                        $cat[$k][]	=	array(
                            'cat_fname'	=>	$v['cat_name'],
                            'id'		=>	$vv['id'],
                            'cat_name'	=>	$vv['cat_name'],
                            'cat_url'	=>	$vvUrl,
                            'cat_img'	=>	$vv['cat_img'],
                        );
                    }
                }
            }
            foreach($cat as $v){
                $cat_lists[]	=	$v;
            }
            if(empty($cat_lists)){
                $cat_lists[]	=	array();
            }
        }
        $arr['slider_list']	=	isset($slider)?$slider:array();
        $arr['hot_cat_list']=	isset($hot_cat_list)?$hot_cat_list:array();
        $arr['cat_list']	=	isset($cat_lists)?$cat_lists:array();
        $this->returnCode(0,$arr);
    }
    //	社区--常用电话
    public function house_phone(){
        $village_id	=	I('village_id');
        if(empty($village_id)){
            $this->returnCode('30000001');
        }
        $now_village = $this->get_village($village_id);
        $phone_list = D('House_village_phone_category')->getAllCatPhoneList($now_village['village_id']);
        if(empty($phone_list)){
            $phone_list	=	array();
        }else{
            foreach($phone_list as $k=>$v){
                $tmp = array();
                foreach($v['phone_list'] as $kk=>$vv){
                    $tmp[$kk]=	$vv;
                    $tmp[$kk]['cat_id']	=	$v['cat_id'];
                    $tmp[$kk]['cat_name']	=	$v['cat_name'];
                }
                $arr['phone_list'][] = $tmp;
            }
        }
        $arr['phone']	=	array(
            'name'	=>	'物业服务中心',
            'title'	=>	'拨打物业服务中心电话',
            'phone'	=>	isset($now_village['property_phone'])?$now_village['property_phone']:'',
        );
        $this->returnCode(0,$arr);
    }
    //	社区--我的

    public  function village_my(){
        if($_POST['app_version']>=150){
            $this->village_my_new();
        }else{
            $this->village_my_old();

        }
    }

    public function village_my_old(){
        //判断用户是否属于本小区
        $village_id	=	I('village_id');
        if(empty($village_id)){
            $this->returnCode('30000001');
        }
        $ticket	=	I('ticket');
        $info = ticket::get($ticket, $this->DEVICE_ID, true);
        if(empty($info)){
            $this->returnCode('20000009');
        }

        $pigcms_type    =   I('pigcms_type',1);

        $pigcms_id	=	I('pigcms_id');
        if(empty($pigcms_id)){            
            $this->returnCode('20120004');            
        }
        //$bindList	=	M('House_village_user_bind')->where(array('pigcms_id'=>$pigcms_id))->find();
//		if($info['uid']	!=	$bindList['uid']){
//			$this->returnCode('20120002');
//		}
        $now_village = $this->get_village($village_id);
        $now_user_info = $this->get_user_village_info($pigcms_id,$village_id,$info['uid']);
        $now_user = D('User')->get_user($info['uid']);
        $arr['user']	=	array(
            'name'		=>	$now_user_info['name'],
            'usernum'	=>	$now_user_info['usernum'],
            'address'	=>	$now_user_info['address'],
            'avatar'	=>	$now_user['avatar'],
            'url'		=>	$this->config['site_url'].U('Wap/My/myinfo'),
        );
        if(empty($now_user['avatar'])){
            $arr['user']['avatar']	=	$this->config['site_url'].'/tpl/Wap/pure/static/images/pic-default.png';
        }
        if(empty($arr['user'])){
            $arr['user']	=	array();
        }else{
            if($pigcms_type == 1){
                $arr['user']['url']	=	str_replace('appapi.php','wap.php',$arr['user']['url']);
            }else if($pigcms_type == 2){
                $arr['user']['url']	=	str_replace('appapi.php','wap_house.php',$arr['user']['url']);
            }
        }
        if($arr['now_user_info']['parent_id'] == 0){
            $arr['village'][]	=	array(
                'url'	=>	$this->config['site_url'].U('Wap/House/village_my_bind_family_add',array('village_id'=>$now_village['village_id'])),
                'title'	=>	'绑定家属',
                'img'	=>	$this->config['site_url'].'/static/images/wap_house/family.png',
            );
        }
        $arr['village'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_pay',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'小区缴费',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/fee.png',
        );
        $arr['village'][]	=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_repair',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'在线报修',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/repair.png',
        );
        $arr['village'][]=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_utilities',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'水电煤上报',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/newspaper.png',
        );
        $arr['service'][]	=	array(
            'url'	=>	$this->config['site_url'].U('Wap/My/group_order_list'),
            'title'	=>	'团购订单',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/group.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/My/appoint_order_list'),
            'title'	=>	'预约订单',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/meal.png',
        );
        $arr['service'][]	=	array(
            'url'	=>	$this->config['site_url'].U('Wap/My/meal_order_list'),
            'title'	=>	'外卖订单',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/appoint.png',
        );

        $arr['life'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_paylists',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'缴费订单列表',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/book.png',
        );
        $arr['life'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_repairlists',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'在线报修列表',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/life_repair.png',
        );
        $arr['life'][]	=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_utilitieslists',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'水电煤上报列表',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/life_newspaper.png',
        );
        if($arr['now_user_info']['parent_id'] == 0){
            $arr['life'][]	=	array(
                'url'	=>	$this->config['site_url'].U('Wap/House/village_my_bind_family_list',array('village_id'=>$now_village['village_id'])),
                'title'	=>	'绑定家属列表',
                'img'	=>	$this->config['site_url'].'/static/images/wap_house/parent_id.png',
            );
        }
        $arr['life'][]	=	array(
            'url'	=>	$this->config['site_url'].U('Wap/Library/express_service_list',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'快递代收',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/pass.png',
        );
        $arr['life'][]	=	array(
            'url'	=>	$this->config['site_url'].U('Wap/Library/visitor_list',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'访客登记',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/interview.png',
        );
        $arr['interaction'][]	=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_suggest',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'投诉建议',
            'img'	=>	$this->config['site_url'].'/static/images/wap_house/suggest.png',
        );
        foreach($arr['village'] as &$v){
            if($pigcms_type == 1){
                $v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
            }else if($pigcms_type == 2){
                $v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
            }
        }
        foreach($arr['service'] as &$v){
            if($pigcms_type == 1){
                $v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
            }else if($pigcms_type == 2){
                $v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
            }
        }
        foreach($arr['life'] as &$v){
            if($pigcms_type == 1){
                $v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
            }else if($pigcms_type == 2){
                $v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
            }
        }
        foreach($arr['interaction'] as &$v){
            if($pigcms_type == 1){
                $v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
            }else if($pigcms_type == 2){
                $v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
            }
        }
        if($pigcms_type == 1){
            $array	=	array('list'=>array($arr['village'],$arr['life'],$arr['service'],$arr['interaction']),'user'=>$arr['user']);
        }else{
            $array	=	array('list'=>array($arr['village'],$arr['service'],$arr['life'],$arr['interaction']),'user'=>$arr['user']);
        }
        $this->returnCode(0,$array);
    }

    public function village_my_new(){
        //判断用户是否属于本小区
        $village_id	=	I('village_id');
        if(empty($village_id)){
            $this->returnCode('30000001');
        }
        $ticket	=	I('ticket');
        $info = ticket::get($ticket, $this->DEVICE_ID, true);
        if(empty($info)){
            $this->returnCode('20000009');
        }

        $pigcms_type    =   I('pigcms_type',1);

        $pigcms_id	=	I('pigcms_id');
        if(empty($pigcms_id)){        
            $this->returnCode('20120004');
        }

        $now_village = $this->get_village($village_id);
        $now_user_info = $this->get_user_village_info($pigcms_id,$village_id,$info['uid']);
        $now_user = D('User')->get_user($info['uid']);
        $arr['user'] = array(
            'name' => $now_user_info['name'],
            'usernum' => $now_user_info['usernum'],
            'address' => $now_user_info['address'],
            'type' => $now_user_info['type'],
            'memo' => $now_user_info['memo'],
            'avatar' => $now_user['avatar'],
            'url' => $this->config['site_url'] . U('Wap/My/myinfo'),
            'money' => $now_user['now_money'], // 金额
            'score' => $now_user['score_count'], // 积分
            'level' => $now_user['level'], // 等级
            'my_money_url' => $this->config['site_url'] . U('Wap/My/my_money'), // 我的钱包链接
            'img' => $this->config['site_url'] . '/tpl/Wap/pure/static/images/money.png'
        );
        if(empty($now_user['avatar'])){
            $arr['user']['avatar']	=	$this->config['site_url'].'/tpl/Wap/pure/static/images/pic-default.png';
        }
        if(empty($arr['user'])){
            $arr['user']	=	array();
        }else{
            if($pigcms_type == 1){
                $arr['user']['url']	=	str_replace('appapi.php','wap.php',$arr['user']['url']);
            }else if($pigcms_type == 2){
                $arr['user']['url']	=	str_replace('appapi.php','wap_house.php',$arr['user']['url']);
            }
        }
        /*if($arr['now_user_info']['parent_id'] == 0){
            $arr['village'][]	=	array(
                    'url'	=>	$this->config['site_url'].U('Wap/House/village_my_bind_family_add',array('village_id'=>$now_village['village_id'])),
                    'title'	=>	'绑定家属',
                    'img'	=>	$this->config['site_url'].'/static/images/wap_house/family.png',
            );
        }*/
        //我的小区 生活缴费 我的订单 收货地址  我的帖子 积分商城 快递代收 访客登记 物业报修 投诉建议
        $arr['village'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/my_village_list'),
            'title'	=>	'我的小区',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/house.png',
        );
        $arr['village'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_paylists',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'生活缴费',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/fee.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/order_list'),
            'title'	=>	'我的订单',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/order.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/My/adress'),
            'title'	=>	'收货地址',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/address.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/Bbs/my_bbs_list',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'我的帖子',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/bbs.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/Gift/index',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'积分商城',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/gift.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/Library/express_service_list',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'快递代收',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/kuaidi.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/Library/visitor_list',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'访客登记',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/visitor.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_repairlists',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'物业报修',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/baoxiu2.png',
        );
        $arr['service'][]		=	array(
            'url'	=>	$this->config['site_url'].U('Wap/House/village_my_suggestlist',array('village_id'=>$now_village['village_id'])),
            'title'	=>	'投诉建议',
            'img'	=>	$this->config['site_url'].'/tpl/Wap/pure/static/images/tousu.png',
        );

        foreach($arr['village'] as &$v){
            if($pigcms_type == 1){
                $v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
            }else if($pigcms_type == 2){
                $v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
            }
        }
        foreach($arr['service'] as &$v){
            if($pigcms_type == 1){
                $v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
            }else if($pigcms_type == 2){
                $v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
            }
        }

        $array	=	array('list'=>array($arr['village'],$arr['service']),'user'=>$arr['user']);

        $this->returnCode(0,$array);
    }

    //搜索小区附近
    public function lbs_search(){
        $keyword = I('keyword');
        $village_id = I('village_id');
        $city_id = I('city_id');
        if(empty($keyword)){
            $this->returnCode('20090012');
        }
        if(empty($village_id)){
            $this->returnCode('30000001');
        }
        if(empty($city_id)){
            $this->returnCode('20090002');
        }
        $now_city = D('Area')->field(true)->where(array('area_id' => $city_id))->find();
        $database_house_village = D('House_village');
        $now_village = $database_house_village->get_one($village_id);
        if(empty($now_village)){
            $this->returnCode('20090005');
        }
        $this->assign('now_village',$now_village);
        $url = 'http://api.map.baidu.com/place/v2/search?query='.urlencode($keyword).'&page_size=9999&page_num=0&scope=1&location=&location='.$now_village['lat'].','.$now_village['long'].'&radius=2000&output=json&ak=4c1bb2055e24296bbaef36574877b4e2';
        import('ORG.Net.Http');
        $http = new Http();
        $results = $http->curlGet($url);
        $return = array();
        if($results){
            $results = json_decode($results,true);
            if($results['status'] == 0 && $results['results']){
                $return = array();
                foreach($results['results'] as $value){
                    if(!isset($value['location']['lat']) && !isset($value['location']['lng'])){
                        continue;
                    }
                    $url = wapLbsTranform("LBS://".$value['location']['lng'].",".$value['location']['lat'],array('title'=>$value['name'],'village_id'=>$village_id),true);
                    $return['list'][] = array(
                        'name'=>$value['name'],
                        'lat'=>$value['location']['lat'],
                        'long'=>$value['location']['lng'],
                        'address'=>$value['address'],
                        'url'=>$url['url']
                    );
                }
            }
            $return['count'] = count($return['list']);
        }

        $this->returnCode(0,$return);
    }


    //	查询是否绑定了当前小区
    protected function get_user_village_info($bind_id,$village_id,$uid){
        $now_user_info = D('House_village_user_bind')->get_one_by_bindId($bind_id);
        if(empty($now_user_info)){
            $this->returnCode('20120003');
        }
        $database_house_village_user_bind = D('House_village_user_bind');
        $where['parent_id|pigcms_id'] = $bind_id;
        $where['uid'] = $uid;
        $where['village_id'] = $village_id;
        $house_village_user_bind_count = $database_house_village_user_bind->where($where)->count();
        if(!$house_village_user_bind_count){
            $this->returnCode('20120002');
        }
        return $now_user_info;
    }

    public function parseCoupon($obj,$type){
        $returnObj = array();
        foreach($obj as $key=>$value){
            if($key=='invoice'){
                $returnObj[$key] = '满'.$obj[$key].'元支持开发票，请在下单时填写发票抬头';
            }else if($key=='discount'){
                $returnObj[$key] = '店内全场'.$obj[$key].'折';
            }else{
                $returnObj[$key] = [];
                foreach($obj[$key] as $k=>$v){
                    if ($key == 'delivery')  {
                        $returnObj[$key][] = '商品满'.$obj[$key][$k]['money'].'元,配送费减'.$obj[$key][$k]['minus'].'元';
                    } else {
                        $returnObj[$key][] = '满'.$obj[$key][$k]['money'].'元减'.$obj[$key][$k]['minus'].'元';
                    }
                }
            }
        }

        $textObj = array();
        foreach($returnObj as $key=>$value){
            if($key=='invoice' || $key=='discount'){
                $textObj[$key] = $value;
            }else{
                switch($key){
                    case 'system_newuser':
                        $textObj[$key] = '平台首单'.implode(',',$value);
                        break;
                    case 'system_minus':
                        $textObj[$key] = '平台优惠'.implode(',',$value);
                        break;
                    case 'newuser':
                        $textObj[$key] = '店铺首单'.implode(',',$value);
                        break;
                    case 'minus':
                        $textObj[$key] = '店铺优惠'.implode(',',$value);
                        break;
                    case 'system_minus':
                        $textObj[$key] = '平台优惠'.implode(',',$value);
                        break;
                    case 'delivery':
                        $textObj[$key] = implode(',',$value);
                        break;
                }
            }
        }
        if($type == 'text'){
            $tmpObj = array();
            foreach($textObj as $key=>$value){
                $tmpObj[] = $value;
            }
            return implode(';',$tmpObj);
        }else{
            $returnObj = array();
            foreach($textObj as $key=>$value){
                $returnObj[] = array(
                    'type'=>$key,
                    'value'=>$value
                );
            }
            return $returnObj;
        }
    }



    public function header_json(){
        header('Content-type: application/json');
    }

    // 通过分享id获取详细信息
    public function house_village_door_share_info() {
        $door_share_id	= I('door_share_id');
        if(empty($door_share_id)){
            $this->returnCode('60000003');
        }
        $where = array();
        $where['share_status'] = 0;
        $where['door_share_id'] = $door_share_id;
        $list = M('House_village_door_share')->where($where)->find();
        if (!empty($list) && $list['share_info']) {
            $share_info = $list['share_info'] = unserialize($list['share_info']);
            if (!empty($share_info)) {
                foreach ($share_info as $key =>$val) {
                    $data = $list['share_info'][$key] =	M('House_village_floor')->field(array('floor_name','floor_layer', 'floor_id'))->where(array('floor_id'=>$val))->find();
                    $condition_door['door_status'] = 1;
                    $condition_door['floor_id'] = $val;
                    $aDoorList	=	M('House_village_door')->distinct(true)->field(true)->where($condition_door)->select();
                    if (!empty($aDoorList)) {
                        $aDoorList = reset($aDoorList);
                        $list['share_info'][$key]['door_id'] = $aDoorList['door_id'];
                        $list['share_info'][$key]['door_device_id'] = $aDoorList['door_device_id'];
                        $list['share_info'][$key]['door_name'] = $aDoorList['door_name'];
                    }
                    if(empty($data) && $val == '-1') {
                        $list['share_info'][$key]['floor_name'] = '小区';
                        $list['share_info'][$key]['floor_layer'] = '大门';
                    }
                }
                $list['share_info'] = $this->wxapp_door($list['share_info']);
            }
            $this->returnCode(0,$list);
        } else {
            return array();
        }
    }

    // 获取门禁密码
    private function wxapp_door($aDoor){
        foreach ($aDoor as &$item) {
            $lockDate = S($item['door_psword'].$item['door_device_id']);

            if(empty($lockDate) && $this->DEVICE_ID=='wxapp'){
                $return_msg = file_get_contents("http://120.24.53.51:8080/dhpkgcomm/app/wxlockData?DEVICEPSW={$item['door_psword']}&DEVICE_ID={$item['door_device_id']}");
                $return_msg = json_decode($return_msg,true);
                $item['lockData'] = $return_msg['lockData'];
                S($item['door_psword'].$item['door_device_id'],$item['lockData'],86400);
            }else{
                $item['lockData']  = $lockDate;
            }
        }
        return $aDoor;
    }
}
?>