<?php


// 约单管理

class YuedanAction extends BaseAction {
   
    public function _initialize() {
        parent::_initialize();

    }

    // 分类列表
    public function category() {
        $database_Yuedan_category = D('Yuedan_category');
        $fcid = intval($_GET['fcid']);
        $condition['fcid'] = $fcid;
    
        $count_Yuedan_category = $database_Yuedan_category->where($condition)->count();
        import('@.ORG.system_page');
        $p = new Page($count_Yuedan_category, 30);
        $category_list = $database_Yuedan_category->field(true)->where($condition)->order('`cat_sort` DESC,`cid` ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('category_list', $category_list);
        // dump($category_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        if ($fcid > 0) {
            $condition_now['cid'] = $fcid;
            $now_category = $database_Yuedan_category->field(true)->where($condition_now)->find();
            if (empty($now_category)) {
                $this->error_tips('没有找到该分类信息！', 3, U('Service/index'));
            }
            $this->assign('now_category', $now_category);
        }
        $this->assign('fcid', $fcid);
        $this->display();
    }

    /*     * *目录添加操作页** */

    public function cat_add() {
        $fcid = intval($_GET['fcid']);
        $this->assign('bg_color', '#F3F3F3');
        $this->assign('fcid', $fcid);
        $this->display();
    }

    /*     * *保存目录** */

    public function cat_modify() {
        if (IS_POST) {
            
            if(($_FILES['icon'] && $_FILES['icon']['error'] != 4) || ($_FILES['click_icon'] && $_FILES['click_icon']['error'] != 4)){
                $image = D('Image')->handle($this->system_session['id'], 'upload_image', 0, array('size' => 10), false);
                if (!$image['error']) {
                    $_POST = array_merge($_POST, $image['url']);
                } else {
                    $this->frame_submit_tips(0, $image['message']);
                }
            }

            $database_Yuedan_category = D('Yuedan_category');
            unset($_POST['dosubmit']);
            $datas = $_POST;
            $datas['add_time'] = time();
            if ($database_Yuedan_category->data($datas)->add()) {
                $this->frame_submit_tips(1,'添加成功！');
            } else {
                $this->frame_submit_tips(0,'添加失败！请重试~');
            }
        } else {
            $this->frame_submit_tips(0,'非法提交,请重新提交~');
        }
    }

    public function cat_edit(){
        $this->assign('bg_color', '#F3F3F3');

        $database_Yuedan_category = D('Yuedan_category');
        $condition_now_Yuedan_category['cid'] = intval($_GET['cid']);
        $now_category = $database_Yuedan_category->field(true)->where($condition_now_Yuedan_category)->find();
        if (empty($now_category)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        $this->assign('now_category', $now_category);
        $this->display();
    }

    public function cat_amend() {
        if (IS_POST) {
            if(($_FILES['icon'] && $_FILES['icon']['error'] != 4) || ($_FILES['click_icon'] && $_FILES['click_icon']['error'] != 4)){
                $image = D('Image')->handle($this->system_session['id'], 'upload_image', 0, array('size' => 10), false);
                if (!$image['error']) {
                    $_POST = array_merge($_POST, $image['url']);
                } else {
                    $this->frame_submit_tips(0, $image['message']);
                }
            }
            $database_Yuedan_category = D('Yuedan_category');
            unset($_POST['dosubmit']);
            $datas = $_POST;
            $catInfo = $database_Yuedan_category->where(array('cid'=>$_POST['cid']))->find();
            if ($database_Yuedan_category->data($datas)->save()) {
                if($_POST['status'] == 0){
                    $database_Yuedan_category->where(array('fcid'=>$_POST['cid']))->data(array('status'=>$_POST['status']))->save();
                }else if($_POST['status'] == 1){
                    if($catInfo['fcid']){
                        $database_Yuedan_category->where(array('cid'=>$catInfo['fcid']))->data(array('status'=>$_POST['status']))->save();
                    }
                }
                $this->frame_submit_tips(1,'编辑成功！');
            } else {
                $this->frame_submit_tips(0,'编辑失败！请编辑您要编辑的内容在重试~');
            }
        } else {
            $this->frame_submit_tips(0,'非法提交,请重新提交~');
        }
    }

    public function cat_del() {
        if (IS_POST) {
            $database_Yuedan_category = D('Yuedan_category');
            $where['cid'] = intval($_POST['cid']);
            $now_category = $database_Yuedan_category->field(true)->where($where)->find();
            if ($database_Yuedan_category->where($where)->delete()) {
                $database_Yuedan_category->where(array('fcid' => $now_category['cid']))->delete();
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    // 编辑器图片上传
    public function ajax_upload_pic(){

        if($_FILES['imgFile']['error'] == 4){
            exit(json_encode(array('error'=>1,'message'=>'没有选择图片')));
        }

        $upload_file = D('Image')->handle($this->system_session['uid'], 'upload_image', 0, array('size' => 5), false);
        if ($upload_file['error']){
            exit(json_encode(array('error'=>1,'message'=>$upload_file['message'])));
        }

        exit(json_encode(array('error' => 0, 'url' => $upload_file['url']['imgFile'], 'title' => '图片')));
    }


    // 审核列表
    public function examine() {
        if($_GET['keyword']){
            $where['title'] = array('like','%'.$_GET['keyword'].'%');
        }
        if($_GET['status'] == 2){
            $where['status'] =array('neq',1);
            $this->assign('status',2);
        }else{
            $where['status'] =1;
            $this->assign('status',1);
        }

        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if($now_area['area_type']==3){
                $area_index = 'area_id';
            }elseif($now_area['area_type']==2){
                $area_index = 'city_id';
            }elseif($now_area['area_type']==1){
                $area_index = 'province_id';
            }
            $this->assign('admin_area',$now_area['area_type']);
            $where[$area_index] = $this->system_session['area_id'];
        }

        if($_GET['province_idss']){
            $where['province_id']= $_GET['province_idss'];
        }

        if($_GET['city_idss'] && $this->config['many_city']){
            $where['city_id']= $_GET['city_idss'];
        }
        if($_GET['area_id']){
            $where['area_id']= $_GET['area_id'];
        }

        $count = D('Yuedan_service_release')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);
        $service_list = D('Yuedan_service_release')->field(true)->where($where)->order('add_time ASC')->limit($p->firstRow.','.$p->listRows)->order('add_time desc')->select();
        foreach ($service_list as $key => $value) {
            $ingList = array_filter(explode(';', $value['img']));
            $service_list[$key]['img'] = $ingList;
            $service_list[$key]['listimg'] = $ingList[0];
            $userinfo = D('User')->where(array('uid'=>$value['uid']))->field('nickname')->find();
            $service_list[$key]['nickname'] = $userinfo['nickname'];
        }
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('service_list',$service_list);
        $this->display();
    }
    //审核
    public function examine_edit(){
        if(IS_POST){
            $service_info = D('Yuedan_service_release')->where(array('rid'=>$_POST['rid']))->find();
            $res = D('Yuedan_service_release')->where(array('rid'=>$_POST['rid']))->data(array('status'=>$_POST['status'],'reasons'=>$_POST['reasons']))->save();
            if($res){

                if($_POST['status'] == 2){
                    $user_info = D("User")->where(array('uid'=>$service_info['uid']))->field('uid,openid,nickname')->find();
                    if ($user_info['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=my_service_list';
                        $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '您发布的'.$service_info['title'].'服务，已经通过系统审核啦。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                    }
                }elseif ($_POST['status'] == 3) {
                    $user_info = D("User")->where(array('uid'=>$service_info['uid']))->field('uid,openid,nickname')->find();
                    if ($user_info['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=my_service_list';
                        $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '您发布的'.$service_info['title'].'服务，系统审核失败请重新编辑提交。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                    }
                }
                $this->success('审核成功！');
            }else{
                $this->error('审核失败！请重试~');
            }
        }else{
            $service_info = D('Yuedan_service_release')->where(array('rid'=>$_GET['rid']))->find();
            $service_info['img'] = array_filter(explode(';', $service_info['img']));
            $userinfo = D('User')->where(array('uid'=>$service_info['uid']))->field('nickname')->find();
            $service_info['nickname'] = $userinfo['nickname'];
            $this->assign('service_info',$service_info);
            $this->display();
        }
        
    }

    public function examine_show(){
        $service_info = D('Yuedan_service_release')->where(array('rid'=>$_GET['rid']))->find();
        $service_info['img'] = array_filter(explode(';', $service_info['img']));
        $userinfo = D('User')->where(array('uid'=>$service_info['uid']))->field('nickname')->find();
        $service_info['nickname'] = $userinfo['nickname'];
        $this->assign('service_info',$service_info);
        $this->display();
    }

    public function service_release_del(){
        if(D('Yuedan_service_release')->where(array('rid'=>$_POST['rid']))->delete()){
            $this->success('删除成功！');
        }
    }

    // 订单列表
    public function order() {

        $where = 'ysr.rid = yso.rid AND yso.uid = u.uid';
        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if($now_area['area_type']==3){
                $area_index = 'area_id';
            }elseif($now_area['area_type']==2){
                $area_index = 'city_id';
            }elseif($now_area['area_type']==1){
                $area_index = 'province_id';
            }
            $this->assign('admin_area',$now_area['area_type']);
            $where .= 'AND ysr.'.$area_index.' = '.$this->system_session['area_id'];
        }

        if($_GET['province_idss']){
            $where .= ' AND ysr.province_id = '.$_GET['province_idss'];
        }

        if($_GET['city_idss'] && $this->config['many_city']){
            $where .= ' AND ysr.city_id = '.$_GET['city_idss'];
        }
        if($_GET['area_id']){
            $where .= ' AND ysr.area_id = '.$_GET['area_id'];
        }

        $count = D('')->table(array(C('DB_PREFIX').'yuedan_service_order'=>'yso',C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'yuedan_service_release'=>'ysr'))->where($where)->order('yso.add_time desc')->field('yso.*,u.nickname,ysr.*')->limit($p->firstRow . ',' . $p->listRows)->count();

        import('@.ORG.system_page');
        $p = new Page($count, 30);
        $orderList = D('')->table(array(C('DB_PREFIX').'yuedan_service_order'=>'yso',C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'yuedan_service_release'=>'ysr'))->where($where)->order('yso.add_time desc')->field('yso.*,u.nickname,ysr.title,ysr.unit,ysr.cat_name')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('orderList', $orderList);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function order_message(){

        $orderList = D('')->table(array(C('DB_PREFIX').'yuedan_message'=>'ym',C('DB_PREFIX').'user'=>'u'))->where("ym.order_id = ".$_GET['order_id']." AND ym.uid = u.uid")->order('ym.add_time desc')->field('ym.*,u.nickname,u.phone,u.avatar')->select();
        $this->assign('orderList',$orderList);
        // dump($orderList);
        $this->display();
    }



    //订单关闭退款
    public function refund_reply(){
        $orderInfo = D('')->table(array(C('DB_PREFIX').'yuedan_service_order'=>'so',C('DB_PREFIX').'yuedan_service_release'=>'sr'))->where("so.order_id = '".$_POST['order_id']."' AND sr.rid = so.rid")->field('so.*,sr.title,sr.img,sr.unit,sr.cat_name')->order('so.add_time desc')->find();
        $res = D('Yuedan_service_order')->where(array('order_id'=>$_POST['order_id']))->save(array('status'=>6));
        if($res){
            D('User')->where(array('uid'=>$orderInfo['uid']))->setInc('now_money',$orderInfo['total_price']);
            D('User_money_list')->add_row($orderInfo['uid'],1,$orderInfo['total_price'],"系统退还您购买 ".$orderInfo['title']." 服务金额,返还金额".$orderInfo['total_price']."元");
            exit(json_encode(array('error'=>1,'msg'=>'退款成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'退款失败，请重试。')));
        }
    }


    // 实名认证
    public function authentication() {


        if($_GET['status'] == 2){
            $where['authentication_status'] =array('neq',1);
            $this->assign('status',2);
        }else{
            $where['authentication_status'] =1;
            $this->assign('status',1);
        }

        // if($_GET['status'] == 2){
        //     $where['authentication_status'] =2;
        // }else{
        //     $where['authentication_status'] =1;
        // }
        // $this->assign('status',$where['authentication_status']);
        $count = D('Yuedan_authentication')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 30);
        $authentication_list = D('Yuedan_authentication')->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('authentication_list', $authentication_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function authentication_check(){
        if(IS_POST){
            $data['examine_time'] = time();
            $data['authentication_status'] = $_POST['authentication_status'];
            $info = D('Yuedan_authentication')->where(array('authentication_id'=>$_POST['authentication_id']))->find();
            $res = D('Yuedan_authentication')->where(array('authentication_id'=>$_POST['authentication_id']))->data($data)->save();
            if($res){

                if($_POST['authentication_status'] == 2){
                    $user_info = D("User")->where(array('uid'=>$info['uid']))->field('uid,openid,nickname')->find();
                    if ($user_info['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=authentication';
                        $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '您提交的服务认证，已经通过系统审核啦。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                    }
                }elseif ($_POST['authentication_status'] == 3) {
                    $user_info = D("User")->where(array('uid'=>$info['uid']))->field('uid,openid,nickname')->find();
                    if ($user_info['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=Yuedan&a=authentication';
                        $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '您提交的服务认证，系统审核失败请重新编辑提交。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                    }
                }

                $this->success('审核成功！');
            }else{
                $this->error('审核失败！请重试！');
            }
        }else{
            if($_GET['status'] == 1){
                $this->assign('status',$_GET['status']);
            }
            $authenticationInfo = D('Yuedan_authentication')->where(array('authentication_id'=>$_GET['id']))->find();
            $authenticationInfo['authentication_field'] = unserialize($authenticationInfo['authentication_field']);
            $this->assign('authenticationInfo',$authenticationInfo);
            $this->display();
        }
        
    }
    // 实名认证
    public function authentication_config() {
        $authentication_config_list = D('Yuedan_authentication_config')->select();
        $this->assign('authentication_config_list',$authentication_config_list);
        $this->display();
    }
    // 实名认证
    public function authentication_config_add() {
        if(IS_POST){
            $data = $_POST;
            $data['add_time'] = time(); 
            $info = D('Yuedan_authentication_config')->where(array('key'=>$_POST['key']))->find();
            if($info){
                $this->error('添加失败！唯一标识已存在！');
                die;
            }
            $res = D('Yuedan_authentication_config')->data($data)->add();
            if($res){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！请重试！');
            }
        }else{
            $this->display();
        }
        
    }
    // 实名认证
    public function authentication_config_edit() {
        if(IS_POST){

            $info = D('Yuedan_authentication_config')->where(array('acid'=>array('neq',$_POST['acid']),'key'=>trim($_POST['key'])))->find();
            if($info){
                $this->error('修改失败！唯一标识已存在！');
                die;
            }
            $data = $_POST;
            $res = D('Yuedan_authentication_config')->where(array('acid'=>$_POST['acid']))->data($data)->save();
            if($res){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！请重试！');
            }
            // dump($_POST);
        }else{
            $info = D('Yuedan_authentication_config')->where(array('acid'=>$_GET['acid']))->find();
            $this->assign('info',$info);
            $this->display();
        }
        
    }
    // 实名认证
    public function authentication_config_del() {
        D('Yuedan_authentication_config')->where(array('acid'=>$_POST['acid']))->delete();
        $this->success('删除成功！');
    }

    // 协议
    public function agreement() {
        $agreementList = D('Yuedan_agreement')->select();
        $this->assign('agreementList',$agreementList);
        $this->display();
    }

    public function agreement_add(){
        if(IS_POST){
            $info = D('Yuedan_agreement')->where(array('key'=>$_POST['key']))->find();
            if($info){
                $this->error('标识已存在，添加失败！请重试~'); die;
            }
            $data['title'] = $_POST['title'];
            $data['key'] = $_POST['key'];
            $data['content'] = $_POST['content'];
            $data['add_time'] = time();
            $res = D('Yuedan_agreement')->data($data)->add();
            if($res){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！请重试~');
            }
        }else{
            $this->display();
        }
    }

    public function agreement_edit(){
        if(IS_POST){
            $info = D('Yuedan_agreement')->where(array('key'=>$_POST['key'],'aid'=>array('neq',$_POST['aid'])))->find();
            if($info){
                $this->error('标识已存在，添加失败！请重试~'); die;
            }
            $data['title'] = $_POST['title'];
            $data['key'] = $_POST['key'];
            $data['content'] = $_POST['content'];
            $data['add_time'] = time();
            $res = D('Yuedan_agreement')->where(array('aid'=>$_POST['aid']))->data($data)->save();
            if($res){
                $this->success('编辑成功！');
            }else{
                $this->error('编辑失败！请重试~');
            }
        }else{
            $agreementInfo = D('Yuedan_agreement')->where(array('aid'=>$_GET['aid']))->find();
            $this->assign('agreementInfo',$agreementInfo);
            $this->display();
        }
    }

    public function agreement_del(){
        D('Yuedan_agreement')->where(array('aid'=>$_POST['aid']))->delete();
        $this->success('删除成功！');
    }

    public function agreement_show(){
        $info = D('Yuedan_agreement')->where(array('aid'=>$_GET['aid']))->find();
        $this->assign('info',$info);
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

    public function handbook_add(){
        if(IS_POST){
            $data['title'] = $_POST['title'];
            $data['content'] = $_POST['content'];
            $data['add_time'] = time();
            $data['is_show'] = $_POST['is_show'];
            $res = D('Yuedan_handbook')->data($data)->add();
            if($res){
                $this->frame_submit_tips(1,'添加成功！');
            }else{
                $this->frame_submit_tips(0,'添加失败！请重试~');
            }
        }else{
           $this->display(); 
        }
        
    }

    public function handbook_edit(){
        if(IS_POST){
            $data['title'] = $_POST['title'];
            $data['content'] = $_POST['content'];
            $data['is_show'] = $_POST['is_show'];
            $res = D('Yuedan_handbook')->where(array('handbook_id'=>$_POST['handbook_id']))->data($data)->save();
            if($res){
                $this->frame_submit_tips(1,'编辑成功！');
            }else{
                $this->frame_submit_tips(0,'编辑失败！请重试~');
            }
        }else{
            $handbook_info = D('Yuedan_handbook')->where(array('handbook_id'=>$_GET['handbook_id']))->find();
            $this->assign('handbook_info',$handbook_info);
            $this->display(); 
        }
    }

    public function handbook_del(){
        if(D('Yuedan_handbook')->where(array('handbook_id'=>$_POST['handbook_id']))->delete()){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！请重试~');
        }
    }

    public function handbook_show(){
        $handbook_info = D('Yuedan_handbook')->where(array('handbook_id'=>$_GET['handbook_id']))->find();
        $this->assign('handbook_info',$handbook_info);
        $this->display(); 
    }


    public function grade(){
        $gradeList = D('Yuedan_grade')->order('grade asc')->select();
        $this->assign('gradeList',$gradeList);
        $this->display();
    }

    public function grade_add(){
        if($_POST){
            $data['grade'] = $_POST['grade'];
            $data['icon'] = $_POST['icon'];
            $data['money'] = $_POST['money'];
            $data['precent'] = $_POST['precent'];
            $ret = D('Yuedan_grade')->data($data)->add();
            if($ret){
                $this->frame_submit_tips(1,'添加等级成功！');
            }else{
                $this->frame_submit_tips(0,'添加失败！请重试~');
            }
        }else{
            $gradeInfo = D('Yuedan_grade')->order('grade desc')->find();
            $this->assign('gradeInfo',$gradeInfo);
            $this->display();
        }
    }

    public function grade_edit(){
        if($_POST){
            $data['money'] = $_POST['money'];
            $data['precent'] = $_POST['precent'];
            $ret = D('Yuedan_grade')->where(array('grade_id'=>$_POST['grade_id']))->data($data)->save();
            if($ret){
                $this->frame_submit_tips(1,'修改等级成功！');
            }else{
                $this->frame_submit_tips(0,'修改失败！请重试~');
            }
        }else{
            $gradeInfo = D('Yuedan_grade')->where(array('grade_id'=>intval($_GET['grade_id'])))->find();
            $this->assign('gradeInfo',$gradeInfo);
            $this->display();
        }
    }

    public function grade_order(){
        $gradeList = D('Yuedan_grade_order')->where()->select();
        $this->assign('gradeList',$gradeList);
        $this->display();
    }

    public function grade_config(){
        $this->display();
    }

}