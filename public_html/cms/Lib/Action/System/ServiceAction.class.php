<?php

/*
 * 到位服务
 */

class ServiceAction extends BaseAction {
    //分类列表
	public function index() {
        $database_Service_category = D('Service_category');
        $fcid = intval($_GET['fcid']);
        $condition['fcid'] = $fcid;

        $count_Service_category = $database_Service_category->where($condition)->count();
        import('@.ORG.system_page');
        $p = new Page($count_Service_category, 30);
        $category_list = $database_Service_category->field(true)->where($condition)->order('`cat_sort` DESC,`cid` ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('category_list', $category_list);
        // dump($category_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        if ($fcid > 0) {
            $condition_now['cid'] = $fcid;
            $now_category = $database_Service_category->field(true)->where($condition_now)->find();
            if (empty($now_category)) {
                $this->error_tips('没有找到该分类信息！', 3, U('Service/index'));
            }
            $this->assign('now_category', $now_category);
        }
        $this->assign('fcid', $fcid);
        $this->display();
    }


    public function service_config(){
        redirect(U('Config/index',array('galias'=>'service','header'=>'Service/config')));
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
            $fcid = intval($_POST['fcid']);
            $pfcid = intval($_POST['pfcid']);
            if (($fcid > 0) && ($pfcid == 0))
                $_POST['subdir'] = 2;
            if (($fcid > 0) && ($pfcid > 0)) {
                $_POST['subdir'] = 3;
                $_POST['cat_sort'] = 0;
                $_POST['is_hot'] = 0;
                $_POST['cat_status'] = 1;
            }
            $datas = $this->Removalquotes($_POST);
            $database_Service_category = D('Service_category');
            $datas['addtime'] = $datas['updatetime'] = time();
            if ($database_Service_category->data($datas)->add()) {
                $this->success('添加成功！');
            } else {
                $this->error('添加失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function cat_edit() {
        $this->assign('bg_color', '#F3F3F3');

        $database_Service_category = D('Service_category');
        $condition_now_Service_category['cid'] = intval($_GET['cid']);
        $now_category = $database_Service_category->field(true)->where($condition_now_Service_category)->find();
        if (empty($now_category)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        $this->assign('now_category', $now_category);
        $this->display();
    }

    public function cat_amend() {
        if (IS_POST) {
            $database_Service_category = D('Service_category');
            $datas = $this->Removalquotes($_POST);
            $catInfo = $database_Service_category->where(array('cid'=>$_POST['cid']))->find();
            if ($database_Service_category->data($datas)->save()) {
                if($_POST['cat_status'] == 0){
                    $database_Service_category->where(array('fcid'=>$_POST['cid']))->data(array('cat_status'=>$_POST['cat_status']))->save();
                }else if($_POST['cat_status'] == 1){
                    if($catInfo['fcid']){
                        $database_Service_category->where(array('cid'=>$catInfo['fcid']))->data(array('cat_status'=>$_POST['cat_status']))->save();
                    }
                }
                $this->frame_submit_tips(1,'编辑成功！');
            } else {
                $this->frame_submit_tips(0,'编辑失败！请重试~');
            }
        } else {
            $this->frame_submit_tips(0,'非法提交,请重新提交~');
        }
    }

    public function cat_del() {
        if (IS_POST) {
            $database_Service_category = D('Service_category');
            $where['cid'] = intval($_POST['cid']);
            $now_category = $database_Service_category->field(true)->where($where)->find();
            if ($database_Service_category->where($where)->delete()) {
                $database_Service_category->where(array('fcid' => $now_category['cid']))->delete();
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function cat_field() {
        $database_Service_category = D('Service_category');
        $condition_now_Service_category['cid'] = intval($_GET['cid']);
        $now_category = $database_Service_category->field(true)->where($condition_now_Service_category)->find();
        if (empty($now_category)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        if (!empty($now_category['cat_field'])) {
            $now_category['cat_field'] = unserialize($now_category['cat_field']);
        }
        $f_category = $database_Service_category->field(true)->where(array('cid' => $now_category['fcid']))->find();
        $f_empty_cat_field = empty($f_category) || empty($f_category['cat_field']) ? true : false;
        unset($f_category);
        $this->assign('f_empty_cat_field', $f_empty_cat_field);
        // dump($now_category);
        $this->assign('now_category', $now_category);
        $InputTypeArr = $this->getInputType();
        // dump($InputTypeArr);
        $this->assign('inputTypeArr', $InputTypeArr);
        $this->display();
    }

    /*     * **添加字段***** */

    public function cat_field_add() {
        $database_Service_category = D('Service_category');
        $condition_now_Service_category['cid'] = intval($_GET['cid']);
        $now_category = $database_Service_category->field(true)->where($condition_now_Service_category)->find();
        $i = 0;
        if (!empty($now_category['cat_field'])) {
            $cat_field = unserialize($now_category['cat_field']);
            foreach ($cat_field as $key => $vv) {
                if (isset($vv['isfilter']) && ($vv['isfilter'] == 1)) {
                    $i++;
                }
            }
        }
        $this->assign('isfilter', $i);
        $InputTypeArr = $this->getInputType();
        $this->assign('bg_color', '#F3F3F3');
        $this->assign('inputTypeArr', $InputTypeArr);
        $this->display();
    }

    /*     * **编辑字段***** */

    public function cat_field_edit() {
        $cid = intval($_GET['cid']);
        $key = isset($_GET['eid']) ? intval($_GET['eid']) : false;
        if (($cid > 0) && ($key !== false) && ($key >= 0)) {
            $Service_categoryDb = D('Service_category');
            $now_category = $Service_categoryDb->field(true)->where(array('cid' => $cid))->find();
            if (!empty($now_category) && !empty($now_category['cat_field'])) {
                $cat_field = unserialize($now_category['cat_field']);
                $subdir = $now_category['subdir'];
                unset($now_category);
                $i = 0;
                if (!empty($cat_field)) {
                    foreach ($cat_field as $kyy => $vv) {
                        if (isset($vv['isfilter']) && ($vv['isfilter'] == 1)) {
                            $i++;
                        }
                    }
                }

                $text = '';
                $thiscat_field = isset($cat_field[$key]) ? $cat_field[$key] : array();
                if (isset($thiscat_field['filtercon']) && !empty($thiscat_field['filtercon'])) {
                    foreach ($thiscat_field['filtercon'] as $fvv) {
                        $text.=$fvv . PHP_EOL;
                    }
                }

                $optstr = '';
                if (isset($thiscat_field['opt']) && !empty($thiscat_field['opt'])) {
                    foreach ($thiscat_field['opt'] as $ovv) {
                        $optstr.=$ovv . PHP_EOL;
                    }
                }

                $descstr = '';
                if (isset($thiscat_field['desc']) && !empty($thiscat_field['desc'])) {
                    foreach ($thiscat_field['desc'] as $dvo) {
                        $descstr.=$dvo . PHP_EOL;
                    }
                }

                $this->assign('isfilter', $i);
                $this->assign('fkey', $key);
                $this->assign('optstr', rtrim($optstr));
                $this->assign('descstr', rtrim($descstr));
                $this->assign('textstr', rtrim($text));
                $this->assign('thiscat_field', $thiscat_field);
                $this->assign('cid', $cid);
                $InputTypeArr = $this->getInputType();
                $this->assign('bg_color', '#F3F3F3');
                $this->assign('inputTypeArr', $InputTypeArr);
                // dump($thiscat_field);
                $this->display();
            }
        }
    }


    public function cat_field_del(){
        if(IS_POST){
            $Service_categoryDb = D('Service_category');
            $condition_now_classify_category['cid'] = intval($_POST['cid']);
            $now_category = $Service_categoryDb->field(true)->where($condition_now_classify_category)->find();

            if(!empty($now_category['cat_field'])){
                $cue_field = unserialize($now_category['cat_field']);
                $new_cue_field = array();

                foreach($cue_field as $key=>$value){
                    if($value['name'] != $_POST['name']){
                        array_push($new_cue_field,$value);
                    }
                }
            }else{
                $this->error('此填写项不存在！');
            }

            $data_classify_category['cat_field'] = serialize($new_cue_field);
            $data_classify_category['cid'] = $now_category['cid'];
            if($Service_categoryDb->data($data_classify_category)->save()){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }

    /*     * *继承父目录字段** */

    public function fieldInherit() {
        $cid = intval($_GET['pcid']);
        $mycid = intval($_GET['cid']);
        $Service_categoryDb = D('Service_category');
        $pcategory = $Service_categoryDb->field(true)->where(array('cid' => $cid))->find();
        if (($pcategory['cat_field']) && ($mycid > 0)) {
            $fg = $Service_categoryDb->where(array('cid' => $mycid))->save(array('cat_field' => $pcategory['cat_field']));
            if ($fg) {
                $this->success('处理成功！');
                exit();
            }
        }
        $this->error('处理失败！');
    }

    private function getInputType() {
		$inputtypeDb = D('Service_inputtype');
		$session_InputType = $inputtypeDb->order('id ASC')->select();
		if (!empty($session_InputType)) {
			$newarr = array();
			foreach ($session_InputType as $vv) {
				$newarr[$vv['typ']] = $vv;
			}
			$session_InputType = $newarr;
		}else{
			$session_InputType = array();
		}
        return $session_InputType;
    }

    public function cat_field_modify() {

        if (IS_POST) {
            $Service_categoryDB = D('Service_category');
            $condition_now_Service_category['cid'] = intval($_POST['cid']);
            $fkey = isset($_POST['fkey']) ? intval($_POST['fkey']) : false;
            $now_category = $Service_categoryDB->field(true)->where($condition_now_Service_category)->find();
            $mc = 1;
            if (!empty($now_category['cat_field'])) {
                $cat_field = unserialize($now_category['cat_field']);
                foreach ($cat_field as $key => $value) {
                    if (($fkey === false) && ((!empty($_POST['alias_name']) && $value['alias_name'] == $_POST['alias_name']) || (!empty($_POST['key_value']) && $value['key_value'] == trim($_POST['key_value'])))) {
                        $this->error('字段已经添加，请勿重复添加！');
                    }
                }
            }else {
                $cat_field = array();
            }


            if(empty($cat_field)){
                $cat_field = array();
            }

            $numfilter = intval($_POST['numfilter']);
            $post_data['name'] = trim($_POST['name']);
            $post_data['type'] = intval($_POST['type']);
            $post_data['isinput'] = intval($_POST['isinput']);
            $post_data['iswrite'] = intval($_POST['iswrite']);
            $post_data['is_desc'] = intval($_POST['is_desc']);
            $post_data['alias_name'] = $_POST['alias_name'];
            $post_data['key_value'] = $_POST['key_value'];
            $post_data['istime'] = $_POST['istime'];

            if (in_array($post_data['type'], array(2, 3))) {
                $valueoftype = trim($_POST['valueoftype']);
                if (empty($valueoftype))
                    $this->error('供选择值框必须填上！');
                $valueoftype = $this->Removalquotes($valueoftype);
                $post_data['opt'] = explode(PHP_EOL, $valueoftype);

                $valuedesc = trim($_POST['valuedesc']);
                $valuedesc = $this->Removalquotes($valuedesc);
                $post_data['desc'] = explode(PHP_EOL, $valuedesc);
            }

            if (($fkey !== false) && ($fkey >= 0) && isset($cat_field[$fkey])) {
                $cat_field[$fkey] = $post_data;
            } else {
                array_push($cat_field, $post_data);
            }


            $data_Service_category['cat_field'] = serialize($cat_field);
            if ($Service_categoryDB->where(array('cid' => $now_category['cid']))->data($data_Service_category)->save()) {
                $msg = '添加字段成功！';
                if (($fkey !== false) && ($fkey >= 0))
                    $msg = '编辑字段成功！';
                $this->success($msg);
            } else {
                $msg = '添加失败！请重试~';
                if (($fkey !== false) && ($fkey >= 0))
                    $msg = '编辑失败！请重试~';
                $this->error($msg);
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    /*     * *去除单双引号*** */

    private function Removalquotes($array) {
        //$regex = "/\'|\"|\/|\\\|\<script|\<\/script/";
        $regex = "/\'|\"|\\\|\<script|\<\/script/";
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = $this->Removalquotes($value);
                } else {
                    $value = strip_tags(trim($value));
                    $array[$key] = preg_replace($regex, '', $value);
                    //$array[$key] = htmlspecialchars($value, ENT_QUOTES);
                }
            }
            return $array;
        } else {
            $array = strip_tags(trim($array));
            $array = preg_replace($regex, '', $array);
            return $array;
            //return htmlspecialchars($array, ENT_QUOTES);
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

        $count = D('Service_authentication')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 30);
        $authentication_list = D('Service_authentication')->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('authentication_list', $authentication_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function authentication_check(){
        if(IS_POST){
            $data['examine_time'] = time();
            $data['authentication_status'] = $_POST['authentication_status'];
            $info = D('Service_authentication')->where(array('authentication_id'=>$_POST['authentication_id']))->find();
            $res = D('Service_authentication')->where(array('authentication_id'=>$_POST['authentication_id']))->data($data)->save();
            if($res){

                if($_POST['authentication_status'] == 2){
                    $user_info = D("User")->where(array('uid'=>$info['uid']))->field('uid,openid,nickname')->find();
                    if ($user_info['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=authentication';
                        $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '您提交的服务认证，已经通过系统审核啦。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                    }
                }elseif ($_POST['authentication_status'] == 3) {
                    $user_info = D("User")->where(array('uid'=>$info['uid']))->field('uid,openid,nickname')->find();
                    if ($user_info['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=authentication';
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
            $authenticationInfo = D('Service_authentication')->where(array('authentication_id'=>$_GET['id']))->find();
            $authenticationInfo['authentication_field'] = unserialize($authenticationInfo['authentication_field']);
            $this->assign('authenticationInfo',$authenticationInfo);
            $this->display();
        }

    }
    // 实名认证
    public function authentication_config() {
        $authentication_config_list = D('Service_authentication_config')->select();
        $this->assign('authentication_config_list',$authentication_config_list);
        $this->display();
    }
    // 实名认证
    public function authentication_config_add() {
        if(IS_POST){
            $data = $_POST;
            $data['add_time'] = time();
            $info = D('Service_authentication_config')->where(array('key'=>$_POST['key']))->find();
            if($info){
                $this->error('添加失败！唯一标识已存在！');
                die;
            }
            $res = D('Service_authentication_config')->data($data)->add();
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

            $info = D('Service_authentication_config')->where(array('acid'=>array('neq',$_POST['acid']),'key'=>trim($_POST['key'])))->find();
            if($info){
                $this->error('修改失败！唯一标识已存在！');
                die;
            }
            $data = $_POST;
            $res = D('Service_authentication_config')->where(array('acid'=>$_POST['acid']))->data($data)->save();
            if($res){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！请重试！');
            }
            // dump($_POST);
        }else{
            $info = D('Service_authentication_config')->where(array('acid'=>$_GET['acid']))->find();
            $this->assign('info',$info);
            $this->display();
        }

    }
    // 实名认证
    public function authentication_config_del() {
        D('Service_authentication_config')->where(array('acid'=>$_POST['acid']))->delete();
        $this->success('删除成功！');
    }





    public function cue_field() {
        $database_Service_category = D('Service_category');
        $condition_now_Service_category['cid'] = intval($_GET['cid']);
        $now_category = $database_Service_category->field(true)->where($condition_now_Service_category)->find();
        if (empty($now_category)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        if (!empty($now_category['cat_fid'])) {
            $this->frame_error_tips('该分类不是主分类，无法使用商品字段功能！');
        }
        if (!empty($now_category['cue_field'])) {
            $now_category['cue_field'] = unserialize($now_category['cue_field']);
        }
        $this->assign('now_category', $now_category);

        $this->display();
    }

    public function cue_field_add() {
        $this->assign('bg_color', '#F3F3F3');

        $this->display();
    }

    public function cue_field_modify() {
        if (IS_POST) {
            $database_Service_category = D('Service_category');
            $condition_now_Service_category['cid'] = intval($_POST['cid']);
            $now_category = $database_Service_category->field(true)->where($condition_now_Service_category)->find();

            if (!empty($now_category['cue_field'])) {
                $cue_field = unserialize($now_category['cue_field']);
                foreach ($cue_field as $key => $value) {
                    if ($value['name'] == $_POST['name']) {
                        $this->error('该填写项已经添加，请勿重复添加！');
                    }
                }
            } else {
                $cue_field = array();
            }

            $post_data['name'] = $_POST['name'];
            $post_data['type'] = $_POST['type'];
            $post_data['sort'] = strval($_POST['sort']);

            array_push($cue_field, $post_data);
            $data_Service_category['cue_field'] = serialize($cue_field);
            $data_Service_category['cid'] = $now_category['cid'];
            if ($database_Service_category->data($data_Service_category)->save()) {
                $this->success('添加成功！');
            } else {
                $this->error('添加失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function cue_field_del() {
        if (IS_POST) {
            $database_Service_category = D('Service_category');
            $condition_now_Service_category['cid'] = intval($_POST['cid']);
            $now_category = $database_Service_category->field(true)->where($condition_now_Service_category)->find();

            if (!empty($now_category['cue_field'])) {
                $cue_field = unserialize($now_category['cue_field']);
                $new_cue_field = array();
                foreach ($cue_field as $key => $value) {
                    if ($value['name'] != $_POST['name']) {
                        array_push($new_cue_field, $value);
                    }
                }
            } else {
                $this->error('此填写项不存在！');
            }
            $data_Service_category['cue_field'] = serialize($new_cue_field);
            $data_Service_category['cid'] = $now_category['cid'];
            if ($database_Service_category->data($data_Service_category)->save()) {
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    // 服务列表
    public function offer_list(){

        $where = "so.publish_id= sup.publish_id AND u.uid = so.uid";
        //搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'order_sn') {
                $where .= " AND sup.order_sn LIKE '%".$_GET['keyword']."%' ";
            }  else if ($_GET['searchtype'] == 'nickname') {
                $where .= " AND u.nickname LIKE '%".$_GET['keyword']."%' ";
            } else if ($_GET['searchtype'] == 'phone') {
                $where .= " AND u.phone LIKE '%".$_GET['keyword']."%' ";
            }
        }

        if(!empty($_GET['start_time'])&&!empty($_GET['end_time'])){
            if ($_GET['start_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['start_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where .= " AND so.add_time BETWEEN ".$period[0].' AND '.$period[1];
        }

        if(!empty($_GET['start_time'])&&!empty($_GET['end_time'])){
            if ($_GET['start_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['start_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_user['_string'] =" (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }

        if(!empty($_GET['status'])){
            if (intval($_GET['status']) == 5) {
                $where .= " AND so.status = ".$_GET['status'];
            } else {
                $where .= " AND sup.status = ".$_GET['status'];
            }
        }

        $serviceOfferCount = D()->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_user_publish'=>'sup', C('DB_PREFIX').'user'=>'u'))->where($where)->field('so.*,sup.*,so.status as offer_status,u.nickname,u.phone')->count();

        import('@.ORG.system_page');
        $p = new Page($serviceOfferCount, 30);
        $serviceOfferList = D()->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_user_publish'=>'sup', C('DB_PREFIX').'user'=>'u'))->limit($p->firstRow . ',' . $p->listRows)->where($where)->field('so.*,sup.*,so.status as offer_status,u.nickname,u.phone')->order('so.offer_id desc')->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        foreach ($serviceOfferList as $key => $value) {

            $publishInfo = D('Service_user_publish')->where(array('publish_id'=>$value['publish_id']))->field('publish_id,cid,uid,catgory_type')->find();
            if($publishInfo['catgory_type'] == 2){
                $publish_total_price = D('Service_user_publish_buy')->where(array('publish_id'=>$publishInfo['publish_id']))->field('total_price')->find();
                $serviceOfferList[$key]['price'] = $publish_total_price['total_price'];
            }else if($publishInfo['catgory_type'] == 3){
                $publish_total_price = D('Service_user_publish_give')->where(array('publish_id'=>$publishInfo['publish_id']))->field('total_price')->find();
                $serviceOfferList[$key]['price'] = $publish_total_price['total_price'];
            }
            $serviceOfferList[$key]['catgory_type'] = $publishInfo['catgory_type'];
            $serviceOfferList[$key]['cid'] = $publishInfo['cid'];
            $categoryInfo = D('service_category')->where(array('cid'=>$publishInfo['cid']))->field('cid,cat_name,cut_proportion,return_integral_proportion')->find();
            $serviceOfferList[$key]['cat_name'] = $categoryInfo['cat_name'];
            $userInfo = D('User')->where(array('uid'=>$value['uid']))->field('nickname')->find();
            $serviceOfferList[$key]['nickname'] = $userInfo['nickname'];
            $userInfo = D('User')->where(array('uid'=>$value['uid']))->field('nickname')->find();
            $serviceOfferList[$key]['nickname'] = $userInfo['nickname'];
            $providerInfo = D('Service_provider')->where(array('uid'=>$value['p_uid']))->field('name')->find();
            $serviceOfferList[$key]['provider_name'] = $providerInfo['name'];

        }
        $this->assign('serviceOfferList',$serviceOfferList);
        $this->display();
    }

    public function offer_info(){
        $publish_id = isset($_GET['publish_id']) ? intval($_GET['publish_id']) : 0;
        $offer_id = isset($_GET['offer_id']) ? intval($_GET['offer_id']) : 0;
        if ($publish_id) {
            $publishInfo = D('Service_user_publish')->where(array('publish_id'=> $publish_id))->find();
        } else {
            $offerInfo = D('Service_offer')->field(true)->where(array('offer_id' => $offer_id))->find();


            $publishInfo = D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->find();
        }


        $catInfo = D('Service_category')->where(array('cid'=>$publishInfo['cid']))->field('cat_name,accept_time')->find();
        if($catInfo){
            $publishInfo['cat_name'] = $catInfo['cat_name'];
        }



        if($publishInfo['catgory_type'] == 1){
            $publishInfo['cat_field'] = unserialize($publishInfo['cat_field']);
            foreach ($publishInfo['cat_field'] as $kk => $vv) {
                if($vv['type'] == 7){
                   $publishInfo['address_start'] = $vv['value']['address_start'];
                   $publishInfo['address_start_lng'] = $vv['value']['address_start_lng'];
                   $publishInfo['address_start_lat'] = $vv['value']['address_start_lat'];
                   $publishInfo['address_end'] = $vv['value']['address_end'];
                   $publishInfo['address_end_lng'] = $vv['value']['address_end_lng'];
                   $publishInfo['address_end_lat'] = $vv['value']['address_end_lat'];
                }
            }
            unset($publishInfo['cat_field']['cid']);

            $offer_list = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$publishInfo['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,so.p_uid,so.status,u.avatar,u.phone,u.name')->select();
            foreach ($offer_list as $key => $value) {
                $offer_list[$key]['msg_list'] = D('Service_offer_message')->where(array('offer_id'=>$value['offer_id']))->field('message,type,add_time')->order('add_time asc')->select();
                $offer_list[$key]['msg_count'] = count($offer_list[$key]['msg_list']);

            }
            $this->assign('offer_list',$offer_list);
            $this->assign('offer_count',count($offer_list));
        }else if($publishInfo['catgory_type'] == 2){

            $cat_field_info = D('Service_user_publish_buy')->where(array('publish_id'=>$publishInfo['publish_id']))->find();
            $cat_field_info['img'] = array_filter(explode(';', $cat_field_info['img']));
            // 判断表中获取到地址id 获取对应id的地址信息写入，否则取表中记录的地址
            if ($cat_field_info['adress_id']) {
                $adress_info = D("User_adress")->get_adress($publishInfo['uid'],$cat_field_info['adress_id']);

                $cat_field_info['end_adress_name'] = $adress_info['adress'].$adress_info['detail'];
            } else {
                $cat_field_info['end_adress_name'] = $cat_field_info['end_adress_name'];
            }
            // $cat_field_info['arrival_time']= $publishInfo['add_time']+($cat_field_info['arrival_time']*60); //发布时间加上分钟
            // $cat_field_info['arrival_time']=$cat_field_info['arrival_time']/60; //小时送达

            $offer_info = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$publishInfo['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,so.p_uid,so.status,u.avatar,u.phone,u.name')->find();
            $this->assign('offer_info',$offer_info);

            $this->assign('cat_field_info',$cat_field_info);



        }else if($publishInfo['catgory_type'] == 3){
            $catInfo['accept_time']*60*60;

            $cat_field_info = D('Service_user_publish_give')->where(array('publish_id'=>$publishInfo['publish_id']))->find();
            $cat_field_info['img'] = array_filter(explode(';', $cat_field_info['img']));
            $offer_info = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$publishInfo['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,so.p_uid,so.status,u.avatar,u.phone,u.name')->find();
            $this->assign('offer_info',$offer_info);

            // 判断表中获取到地址id 获取对应id的地址信息写入，否则取表中记录的地址
            if ($cat_field_info['adress_id']) {
                $start_adress_info = D("User_adress")->get_adress($publishInfo['uid'],$cat_field_info['start_adress_id']);
                $cat_field_info['start_adress_name'] = $start_adress_info['adress'].$start_adress_info['detail'];
                $end_adress_info = D("User_adress")->get_adress($publishInfo['uid'],$cat_field_info['end_adress_id']);
                $cat_field_info['end_adress_name'] = $end_adress_info['adress'].$end_adress_info['detail'];
            } else {
                $cat_field_info['start_adress_name'] = $cat_field_info['start_adress_name'];
                $cat_field_info['end_adress_name'] = $cat_field_info['end_adress_name'];
            }

            $this->assign('cat_field_info',$cat_field_info);


        }

        // 查询发布服务快派用户注册的手机号
        if ($publishInfo['uid']) {
            $user_info = M('User')->field('phone')->where(array('uid' => $publishInfo['uid']))->find();
            if ($user_info['phone']) {
                $publishInfo['register_phone'] = $user_info['phone'];
            } else {
                $publishInfo['register_phone'] = '';
            }
        }

        $this->assign('publishInfo',$publishInfo);

        // dump($cat_field_info);
        // dump($publishInfo);

        $this->display();
    }

    public function offer_message(){
        $offerMessageList = D('Service_offer_message')->field(true)->where(array('offer_id'=>$_GET['offer_id']))->select();
        foreach ($offerMessageList as $key => $value) {
            if($value['type'] == 1){
                $userInfo = D('User')->where(array('uid'=>$value['uid']))->field('nickname,phone,avatar')->find();
            }else{
                $userInfo = D('User')->where(array('uid'=>$value['p_uid']))->field('nickname,phone,avatar')->find();
            }

            $offerMessageList[$key]['nickname'] = $userInfo['nickname'];
            $offerMessageList[$key]['phone'] = $userInfo['phone'];
            $offerMessageList[$key]['avatar'] = $userInfo['avatar'];

        }
        $this->assign('orderList',$offerMessageList);
        $this->display();
    }

    // 退款
    public function refund_reply(){
        $offerInfo = D('Service_offer')->where(array('offer_id' => $_POST['offer_id']))->find();
        if ($offerInfo['status'] == 1 || $offerInfo['status'] == 4 || $offerInfo['status'] == 6 || $offerInfo['status'] == 7 || $offerInfo['status'] == 10 || $offerInfo['status'] == 12) {
            exit(json_encode(array('error' => 2, 'msg' => '当前状态不可退款。')));
        }
        $publish_info = D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->field('publish_id,catgory_type,order_sn,uid')->find();

        $operation_info = '管理员操作服务商退款, ';
        $refund  = D('Plat_order')->order_refund(array('business_type'=>'service','business_id'=>$publish_info['publish_id']), $operation_info);

        if(!$refund['error']) {
            $res = D('Service_offer')->where(array('offer_id' => $_POST['offer_id']))->data(array('status' => $_POST['status']))->save();


            D('Service_user_publish')->where(array('publish_id' => $offerInfo['publish_id']))->data(array('status' => $_POST['status']))->save();
//            //修复 订单金额未统一
//            if ($publish_info['catgory_type'] == 2) {
//                $buy = D('Service_user_publish_buy')->where(array('publish_id' => $offerInfo['publish_id']))->field('buy_id,publish_id,total_price')->find();
//                $price = $buy['total_price'];
//            } elseif ($publish_info['catgory_type'] == 3) {
//                $give = D('Service_user_publish_give')->where(array('publish_id' => $offerInfo['publish_id']))->field('give_id,publish_id,total_price')->find();
//                $price = $give['total_price'];
//            } else {
//                $price = $offerInfo['price'];
//            }
            if ($res) {
                D('Deliver_supply')->updateStatusToZero($publish_info['publish_id']);
//                D('User')->where(array('uid' => $offerInfo['uid']))->setInc('now_money', $price);
                // D('User_money_list')->add_row($offerInfo['uid'], 1, $price, "管理员操作服务商退款 " . $price . " 元");
                // 推送模板消息
                $now_user = D('User')->field(true)->where(array('uid' => $publish_info['uid']))->find();
                if ($now_user['openid']) {
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $_POST['publish_id'];
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $model->sendTempMsg('TM00017', array(
                        'href' => $href,
                        'wecha_id' => $now_user['openid'],
                        'first' => '您的跑腿订单退款已完成',
                        'OrderSn' => $publish_info['order_sn'],
                        'OrderStatus' => '已完成退款',
                        'remark' => date('Y-m-d H:i:s')
                    ));
                }
                exit(json_encode(array('error' => 1, 'msg' => '退款成功')));
            } else {
                exit(json_encode(array('error' => 2, 'msg' => '退款失败请重试。')));
            }
        }else {
            exit(json_encode(array('error' => 2, 'msg' => '退款失败请重试。')));
        }
    }
    //确认服务
    public function confirm_service(){
        $offerInfo = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->find();
        $publishInfo = D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->field('publish_id,cid,uid')->find();
        $categoryInfo = D('service_category')->where(array('cid'=>$publishInfo['cid']))->field('cid,cat_name,cut_proportion,return_integral_proportion')->find();
        $cut_proportion = $categoryInfo['cut_proportion']/100;
        $extract_money = $offerInfo['price']*$cut_proportion;
        $price = $offerInfo['price']-$extract_money;
        $res = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->data(array('status'=>4))->save();
        if($res){
            D('User')->where(array('uid'=>$offerInfo['p_uid']))->setInc('now_money',$offerInfo['price']);
            D('User_money_list')->add_row($offerInfo['p_uid'],1,$price,"管理员操作用户付款 ".$offerInfo['price']." 元,扣除手续费".$extract_money."元");
            exit(json_encode(array('error'=>1,'msg'=>'服务已完成')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'完成服务失败请重试')));
        }
    }

    public function publish_list(){


        $publishCount = D('Service_user_publish')->count();

        import('@.ORG.system_page');
        $p = new Page($publishCount, 30);
        $publishList = D('Service_user_publish')->field(true)->order('add_time desc')->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);





        foreach ($publishList as $key => $value) {
            $offer_info = D('Service_offer')->field(true)->where(array('status'=>array('neq',1),'publish_id'=>$value['publish_id']))->find();
            // dump($offer_info);
            if($value['catgory_type'] == 2){
                $publish_total_price = D('Service_user_publish_buy')->where(array('publish_id'=>$value['publish_id']))->field('total_price')->find();
                $publishList[$key]['price'] = $publish_total_price['total_price'];
            }else if($value['catgory_type'] == 3){
                $publish_total_price = D('Service_user_publish_give')->where(array('publish_id'=>$value['publish_id']))->field('total_price')->find();
                $publishList[$key]['price'] = $publish_total_price['total_price'];
            }else{
                $publishList[$key]['price'] = $offer_info['price'];
            }
            $publishList[$key]['offer_id'] = $offer_info['offer_id'];
            $categoryInfo = D('service_category')->where(array('cid'=>$value['cid']))->field('cid,cat_name,cut_proportion,return_integral_proportion')->find();
            $publishList[$key]['cat_name'] = $categoryInfo['cat_name'];
            $userInfo = D('User')->where(array('uid'=>$value['uid']))->field('nickname')->find();
            $publishList[$key]['nickname'] = $userInfo['nickname'];
        }

        $this->assign('publishList',$publishList);
        $this->display();
    }


    public function config(){
        $this->display();
    }


    // 退款
    public function publish_refund_reply(){
        $publish_info = D('Service_user_publish')->where(array('publish_id'=>$_POST['publish_id']))->field('publish_id,catgory_type,order_sn,uid')->find();
        $refund  = D('Plat_order')->order_refund(array('business_type'=>'service','business_id'=>$publish_info['publish_id']));

        if(!$refund['error']){
            $now_order = D('Service_user_publish')->where(array('publish_id'=>$_POST['publish_id']))->data(array('status'=>$_POST['status']))->save();
            // 只针对帮我买帮我送退款
            if ($publish_info['catgory_type'] != 2 && $publish_info['catgory_type'] != 3) {
                exit(json_encode(array('error'=>2,'msg'=>'退款失败请去订单列表退款。')));
            }
            if($now_order){
                D('Deliver_supply')->updateStatusToZero($publish_info['publish_id']);
               // D('User')->where(array('uid'=>$publish_info['uid']))->setInc('now_money',$price);
               // D('User_money_list')->add_row($publish_info['uid'],1,$price,"管理员操作服务商退款 ".$price." 元");

                // 推送模板消息
                $now_user = D('User')->field(true)->where(array('uid' => $publish_info['uid']))->find();
                if ($now_user['openid']) {
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $_POST['publish_id'];
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $model->sendTempMsg('TM00017', array(
                        'href' => $href,
                        'wecha_id' => $now_user['openid'],
                        'first' => '您的跑腿订单退款已完成',
                        'OrderSn' => $publish_info['order_sn'],
                        'OrderStatus' => '已完成退款',
                        'remark' => date('Y-m-d H:i:s')
                    ));
                }

                exit(json_encode(array('error'=>1,'msg'=>'退款成功')));
            }else{
                exit(json_encode(array('error'=>2,'msg'=>'退款失败请重试。')));
            }
        }else {
            exit(json_encode(array('error' => 2, 'msg' => '退款失败请重试。')));
        }


    }

}