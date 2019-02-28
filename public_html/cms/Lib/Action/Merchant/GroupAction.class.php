<?php

/*
 * 团购
 */

class GroupAction extends BaseAction {
    /* 团购列表 */

    public function index() {

        $database_group = D('Group');
        $condition_where='mer_id = '.$this->merchant_session['mer_id'];
        if(!empty($_GET['keyword'])){
            switch($_GET['searchtype']){
                case 'group_id':
                    $condition_where .= " AND `group_id`=" . intval($_GET['keyword']);
                    break;
                case 's_name':
                    $condition_where .= " AND `s_name` LIKE '%" . $_GET['keyword'] . "%'";
                    break;
                case 'name':
                    $condition_where .= " AND `name` LIKE '%" . $_GET['keyword'] . "%'";
                    break;
            }
        }


        if(!empty($_GET['searchstatus'])){
            $now_time = $_SERVER['REQUEST_TIME'];
            switch($_GET['searchstatus']){
                case '1':
                    $condition_where .= " AND `status`='1'  AND `type`='1' AND `begin_time`<'$now_time' AND `end_time`>'$now_time'";
                    break;
                case '2':
                    $condition_where .= " AND (`status`<>'1' OR `type`<>'1' OR `begin_time`>'$now_time' OR `end_time`<'$now_time')";
                    break;
            }
        }

        $group_count = $database_group->where($condition_where)->count();

        import('@.ORG.merchant_page');
        $p = new Page($group_count, 20);
        $group_list = $database_group->field(true)->where($condition_where)->order('`group_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        $group_image_class = new group_image();
        foreach ($group_list as $key => $value) {
            $tmp_pic_arr = explode(';', $value['pic']);
            $group_list[$key]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
        }
        $this->assign('group_list', $group_list);

        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        $this->display();
    }

    public function add() {
        if (IS_POST) {
            if (empty($_POST['name'])) {
                $this->error('请填写商品标题');
            }
            if (empty($_POST['s_name'])) {
                $this->error('请填写商品名称');
            }
            if (empty($_POST['intro'])) {
                $this->error('请填写商品简介');
            }

            if($_POST['tuan_type']==2 && $_POST['open_express']==0 && $_POST['pick_in_store']==0){
                $this->error('请选择一种配送方式');
            }

            //判断关键词
            $keywords = trim($_POST['keywords']);
            if (!empty($keywords)) {
                $tmp_key_arr = explode(' ', $keywords);
                $key_arr = array();
                foreach ($tmp_key_arr as $value) {
                    if (!empty($value)) {
                        array_push($key_arr, $value);
                    }
                }
                if (count($key_arr) > 5) {
                    $this->error('关键词最多5个。');
                }
            }

            if (empty($_POST['old_price'])&&$this->config['open_extra_price']==0) {
                $this->error('请填写商品原价');
            }
            if (empty($_POST['price'])&&$this->config['open_extra_price']==0) {
                $this->error('请填写商品' . $this->config['group_alias_name'] . '价');
            }
			if($_POST['price'] - $_POST['wx_cheap'] <= 0){
				$this->error('商品价格减去微信优惠不能小于0');
			}
            if (empty($_POST['store'])) {
                $this->error('请至少选择一家店铺');
            }
            if (empty($_POST['content'])) {
                $this->error('请填写本单详情');
            }
            if (empty($_POST['pic'])) {
                $this->error('请至少上传一张照片');
            }
            if (empty($_POST['success_num'])) {
                $this->error('成功' . $this->config['group_alias_name'] . '人数要求至少为1人');
            }
            //商品总数量
            if ($_POST['count_num'] > 0 && $_POST['count_num'] < $_POST['once_min']) {
            	$this->error('商品总数量不能小于一次最少购买数量');
            }
            
            if ($_POST['once_max'] > 0 && $_POST['once_min'] > $_POST['once_max']) {
            	$this->error('ID最多购买数量不能小于一次最少购买数量');
            }
			
			if($_POST['bind_trade'] == 0){
				unset($_POST['appoint_id']);
			}else if($_POST['bind_trade'] == 1){
				if(empty($_POST['appoint_id'])){
                    $this->error('绑定预约不能为空！');
                }
				$_POST['is_appoint_bind'] = 1;
				$_POST['trade_type'] = 'appoint';
				$_POST['trade_info'] = $_POST['appoint_id'];
			}else if($_POST['bind_trade'] == 2){
				if(empty($_POST['trade_hotel_cat_id'])){
                    $this->error('请选择酒店分类');
                }
                if($_POST['stock_reduce_method']==1){
                    $this->error('酒店库存仅支持支付完成后减库存');
                }

                if($_POST['count_num']>0 ){
                    $_POST['count_num'] = 0;
                }
                $_POST['pin_num'] = 0;
				$_POST['trade_type'] = 'hotel';
				$_POST['trade_info'] = implode(',',$_POST['trade_hotel_cat_id']);
			}
            
            isset($_POST['tagname']) && $_POST['tagname'] = trim($_POST['tagname']);
            $packageid = isset($_POST['packageid']) ? intval($_POST['packageid']) : 0;
			 if(($packageid>0) && empty($_POST['tagname'])){
			     $this->error($this->config['group_alias_name'] .'套餐标签必须要写上！');
			 }
            $leveloff = isset($_POST['leveloff']) ? $_POST['leveloff'] : false;
            unset($_POST['leveloff']);
			
			$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
			$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
			
			foreach($_POST['pic'] as $kp => $vp){
				$tmp_vp = explode(',',$vp);
				$_POST['pic'][$kp]=$rand_num.','.$tmp_vp[1];
			}
            $_POST['pic'] = implode(';', $_POST['pic']);
		
			
            $_POST['cue'] = '';
            if ($_POST['cue_field']) {
                $cue_field = array();
                foreach ($_POST['cue_field']['value'] as $key => $value) {
                    array_push($cue_field, array('key' => $_POST['cue_field']['key'][$key], 'value' => $value));
                }
                $_POST['cue'] = serialize($cue_field);
            }
            if (is_array($_POST['custom'])) {
                foreach ($_POST['custom'] as $key => $value) {
                    if (is_array($value)) {
                        $_POST[$key] = implode(',', $value);
                    } else {
                        $_POST[$key] = $value;
                    }
                }
            }

            $_POST['content'] = fulltext_filter($_POST['content']);
//            $_POST['discount'] = $_POST['price'] / $_POST['old_price'] * 10;

            $_POST['mer_id'] = $this->merchant_session['mer_id'];
            if ($this->config['group_verify'] && $_POST['status'] ) {
                $_POST['status'] = $this->merchant_session['issign'] ? 1 : 2;
            } else {
                $_POST['status'] = 1;
            }

            $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
            $_POST['add_time'] = $_SERVER['REQUEST_TIME'];
            $_POST['begin_time'] = strtotime($_POST['begin_time']);
            $_POST['end_time'] = strtotime($_POST['end_time']);
            $_POST['deadline_time'] = strtotime($_POST['deadline_time']);


            //店铺信息
            $database_merchant_store = D('Merchant_store');
            foreach ($_POST['store'] as $key => $value) {
                $condition_merchant_store['store_id'] = $value;
                $tmp_group_store = $database_merchant_store->field('`store_id`,`province_id`,`city_id`,`area_id`,`circle_id`')->where($condition_merchant_store)->find();
                if (!empty($tmp_group_store)) {
                    $data_group_store_arr[] = $tmp_group_store;
                }
                //给店铺添加分类
                if (!($store_catgory = D('Store_category')->field(true)->where(array('cat_id' => intval($_POST['cat_fid']), 'store_id' => $value))->find())) {
                    D('Store_category')->add(array('cat_id' => intval($_POST['cat_fid']), 'store_id' => $value));
                }
            }

            if (empty($data_group_store_arr)) {
                $this->error('您选择的店铺信息不正确！请重试。');
            } else if ($_POST['tuan_type'] == 2) {
                $_POST['prefix_title'] = '购物';
            } else if (count($data_group_store_arr) == 1) {
                $circle_info = D('Area')->get_area_by_areaId($data_group_store_arr[0]['circle_id']);
                if (empty($circle_info)) {
                    $this->error('您选择的店铺区域商圈信息不正确！请修改店铺资料后重试。');
                }
                $_POST['prefix_title'] = $circle_info['area_name'];
            } else {
                $_POST['prefix_title'] = count($data_group_store_arr) . '店通用';
            }

            $newleveloff = array();
            if (!empty($leveloff)) {
                foreach ($leveloff as $kk => $vv) {
                    $vv['type'] = intval($vv['type']);
                    $vv['vv'] = intval($vv['vv']);
                    if (($vv['type'] > 0) && ($vv['vv'] > 0)) {
                        $vv['level'] = $kk;
                        $newleveloff[$kk] = $vv;
                    }
                }
            }

            $_POST['leveloff'] = !empty($newleveloff) ? serialize($newleveloff) : '';
            if ($leveloff === false){
                unset($_POST['leveloff']);
			}
            $database_group = D('Group');
            if ($group_id = $database_group->data($_POST)->add()) {
                $database_group_store = D('Group_store');
                foreach ($data_group_store_arr as $key => $value) {
                    $data_group_store = $value;
                    $data_group_store['group_id'] = $group_id;
                    $database_group_store->data($data_group_store)->add();
                }

                //判断关键词
                if (!empty($key_arr)) {
                    $database_keywords = D('Keywords');
                    $data_keywords['third_id'] = $group_id;
                    $data_keywords['third_type'] = 'group';
                    foreach ($key_arr as $value) {
                        $data_keywords['keyword'] = $value;
                        $database_keywords->data($data_keywords)->add();
                    }
                }

                //添加或删除到套餐
                if ($packageid > 0) {
                    $mpackageDb = M('Group_packages');
                    $mpackage = $mpackageDb->where(array('id' => $packageid, 'mer_id' => $this->merchant_session['mer_id']))->find();
                    if (!empty($mpackage)) {
                        $mpackage['groupidtext'] = !empty($mpackage['groupidtext']) ? unserialize($mpackage['groupidtext']) : array();
                        $mpackage['groupidtext'][$group_id] = $_POST['tagname'];
                        $mpackageDb->where(array('id' => $mpackage['id']))->save(array('groupidtext' => serialize($mpackage['groupidtext'])));
                    }
                }
                $this->success('添加成功！',U('Group/index'));
            } else {
                $this->error('添加失败！请重试。');
            }
        } else {
            $database_group_category = D('Group_category');
            $condition_f_group_category['cat_fid'] = 0;
            $f_category_list = $database_group_category->field('`cat_id`,`cat_name`,`cat_field`,`cue_field`')->where($condition_f_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
            $this->assign('f_category_list', $f_category_list);
            if (empty($f_category_list)) {
                $this->error('管理员没有添加' . $this->config['group_alias_name'] . '分类！');
            }

            $condition_s_group_category['cat_fid'] = $f_category_list[0]['cat_id'];
            $condition_s_group_category['cat_status'] = 1;
            $s_category_list = $database_group_category->field('`cat_id`,`cat_name`')->where($condition_s_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
            $this->assign('s_category_list', $s_category_list);
            if (empty($s_category_list)) {
                $this->error($f_category_list[0]['cat_name'] . ' 分类下没有添加子分类！');
            }

            if (!empty($f_category_list[0]['cat_field'])) {
                $cat_field = unserialize($f_category_list[0]['cat_field']);
                $custom_html = '';
                foreach ($cat_field as $key => $value) {
                    if (empty($value['use_field'])) {
                        $custom_html .= '<div class="form-group"><label class="col-sm-1">' . $value['name'] . '：</label>';
                        if ($value['type'] == 0) {
                            $custom_html .= '<select name="custom[custom_' . $key . ']" class="col-sm-1">';
                            foreach ($value['value'] as $k => $v) {
                                $custom_html .= '<option value="' . $k . '">' . $v . '</option>';
                            }
                            $custom_html .= '</select>';
                        } else {
                            foreach ($value['value'] as $k => $v) {
                                $custom_html .= '<label style="margin-right:30px;"><input class="ace" type="checkbox" name="custom[custom_' . $key . '][]" value="' . $k . '" id="custom_' . $key . '_' . $k . '"/><span class="lbl"><label for="custom_' . $key . '_' . $k . '">&nbsp;' . $v . '</label></span></label>';
                            }
                        }
                        $custom_html .= '</div>';
                    }
                }
            }
            $this->assign('custom_html', $custom_html);

            if (!empty($f_category_list[0]['cue_field'])) {
                $cue_field = unserialize($f_category_list[0]['cue_field']);
                $cue_html = '';
                foreach ($cue_field as $key => $value) {
                    $cue_html .= '<div class="form-group"><label class="col-sm-1">' . $value['name'] . '：</label>';
                    if ($value['type'] == 0) {
                        $cue_html .= '<input type="hidden" name="cue_field[key][]" value="' . $value['name'] . '"/><input type="text" class="col-sm-4" name="cue_field[value][]"/>';
                    } else {
                        $cue_html .= '<input type="hidden" name="cue_field[key][]" value="' . $value['name'] . '"/><textarea class="col-sm-4" rows="5" name="cue_field[value][]"></textarea>';
                    }
                    $cue_html .= '</div>';
                }
            }
            $this->assign('cue_html', $cue_html);

            $mer_id = $this->merchant_session['mer_id'];
            $db_arr = array(C('DB_PREFIX') . 'area' => 'a', C('DB_PREFIX') . 'merchant_store' => 's');
            $store_list = D()->table($db_arr)->field('a.`area_name`,s.`adress`,`s`.`name`,`s`.`store_id`')->where("`s`.`mer_id`='$mer_id' AND `s`.`status`='1' AND `s`.`have_group`='1' AND `s`.`area_id`=`a`.`area_id`")->order('`sort` DESC,`store_id` ASC')->select();
            if (empty($store_list)) {
				$this->assign('jumpUrl',U('Config/store'));
                $this->error('您暂时还没有能添加' . $this->config['group_alias_name'] . '信息的店铺！');
            }
            $this->assign('store_list', $store_list);
            $levelDb = M('User_level');
            $tmparr = $levelDb->order('id ASC')->select();
            $levelarr = array();
            if ($tmparr && $this->config['level_onoff'] && $this->config['group_level_onoff']) {


                foreach ($tmparr as $vv) {

                    $vv['vv'] = $vv['boon'];
                    $vv['type'] = $vv['type'];
                    $vv['lid'] = $vv['id'];

                    $levelarr[$vv['level']] = $vv;
                }


            }

            unset($tmparr);
            $this->assign('levelarr', $levelarr);
            $mpackageDb = M('Group_packages');
            $mpackagelist = $mpackageDb->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->order('id DESC')->select();
            $this->assign('mpackagelist', $mpackagelist);
            
            if(isset($this->config['appoint_category_bgimg'])){
				$database_appoint = D('Appoint');
				$appoint_list = $database_appoint->get_appointmerchantlist_by_MerchantId($mer_id);
				$this->assign('appoint_list',$appoint_list);
			}
			
			$database_trade_hotel_category = D('Trade_hotel_category');
			$trade_hotel_category_list = $database_trade_hotel_category->where(array('mer_id'=>$mer_id,'is_remove'=>'0','cat_fid'=>'0'))->order('`cat_sort` DESC')->select();
			$this->assign('trade_hotel_category_list',$trade_hotel_category_list);

            $this->display();
        }
    }
	
	public function edit() {
        if (empty($this->config['merchant_edit_group'])) {
            $this->error('管理员没有给予编辑团购的权限');
        }


        $database_group = D('Group');
        $condition_group['group_id'] = $_GET['group_id'];
        $now_group = $database_group->field(true)->where($condition_group)->find();
        if($now_group['trade_info']!=0){
            $now_group['trade_info'] = explode(',',$now_group['trade_info']);
        }
        if (empty($now_group)) {
            $this->error('该' . $this->config['group_alias_name'] . '不存在！');
        }
        if (IS_POST) {
            if (empty($_POST['name'])) {
                $this->error('请填写商品标题');
            }
            if (empty($_POST['s_name'])) {
                $this->error('请填写商品名称');
            }
            if (empty($_POST['intro'])) {
                $this->error('请填写商品简介');
            }
            if($now_group['tuan_type']==2 && $_POST['open_express']==0 && $_POST['pick_in_store']==0){
                $this->error('请选择一种配送方式');
            }

            //判断关键词
            $keywords = trim($_POST['keywords']);
            if (!empty($keywords)) {
                $tmp_key_arr = explode(' ', $keywords);
                $key_arr = array();
                foreach ($tmp_key_arr as $value) {
                    if (!empty($value)) {
                        array_push($key_arr, $value);
                    }
                }
                if (count($key_arr) > 5) {
                    $this->error('关键词最多5个。');
                }
            }

            if (empty($_POST['old_price'])&&$this->config['open_extra_price']==0) {
                $this->error('请填写商品原价');
            }
            if (empty($_POST['price'])&&$this->config['open_extra_price']==0) {
                $this->error('请填写商品' . $this->config['group_alias_name'] . '价');
            }
			if($_POST['price'] - $_POST['wx_cheap'] <= 0){
				$this->error('商品价格减去微信优惠不能小于0');
			}
            if (empty($_POST['store'])) {
                $this->error('请至少选择一家店铺');
            }
            if (empty($_POST['content'])) {
                $this->error('请填写本单详情');
            }
            if (empty($_POST['pic'])) {
                $this->error('请至少上传一张照片');
            }
            if (empty($_POST['success_num'])) {
                $this->error('成功' . $this->config['group_alias_name'] . '人数要求至少为1人');
            }
            //商品总数量
            if ($_POST['count_num'] > 0 && $_POST['count_num'] < $_POST['once_min']) {
            	$this->error('商品总数量不能小于一次最少购买数量');
            }
            
            if ($_POST['once_max'] > 0 && $_POST['once_min'] > $_POST['once_max']) {
            	$this->error('ID最多购买数量不能小于一次最少购买数量');
            }
            
             if($_POST['is_appoint_bind'] && ($_POST['is_appoint_bind'] == 1)){
                if(!$_POST['appoint_id']){
                    $this->error('绑定预约不能为空！');
                }
            }
            if($_POST['bind_trade']==0){
                $_POST['is_appoint_bind'] = 0;
                $_POST['appoint_id'] = 0;
                $_POST['trade_type'] = '';
                //$_POST['trade_info'] = '';
            }

            
            $leveloff = isset($_POST['leveloff']) ? $_POST['leveloff'] : false;
            isset($_POST['tagname']) && $_POST['tagname'] = trim($_POST['tagname']);
            $packageid = isset($_POST['packageid']) ? intval($_POST['packageid']) : 0;
             if(($packageid>0) && empty($_POST['tagname'])){
                 $this->error($this->config['group_alias_name'] .'套餐标签必须要写上！');
             }
            unset($_POST['leveloff']);
			//处理图片 文件名统一
			$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
			$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
			
			foreach($_POST['pic'] as $kp => $vp){
				$tmp_vp = explode(',',$vp);
				$_POST['pic'][$kp]=$rand_num.','.$tmp_vp[1];
			}
            $_POST['pic'] = implode(';', $_POST['pic']);
			
            $_POST['content'] = fulltext_filter($_POST['content']);
//            $_POST['discount'] = $_POST['price'] / $_POST['old_price'] * 10;

            $_POST['sort'] = intval($_POST['sort']);
            $_POST['index_sort'] = intval($_POST['index_sort']);

            $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
            $_POST['begin_time'] = strtotime($_POST['begin_time']);
            $_POST['end_time'] = strtotime($_POST['end_time']);
            $_POST['deadline_time'] = strtotime($_POST['deadline_time']);

            if ($_POST['cue_field']) {
                $cue_field = array();
                foreach ($_POST['cue_field']['value'] as $key => $value) {
                    array_push($cue_field, array('key' => $_POST['cue_field']['key'][$key], 'value' => $value));
                }
                $_POST['cue'] = serialize($cue_field);
            }
            if (!isset($_POST['noedittype']) && isset($_POST['cat_fid']) && isset($_POST['cat_id'])) {
                $_POST['custom_0'] = $_POST['custom_1'] = $_POST['custom_2'] = $_POST['custom_3'] = $_POST['custom_4'] = '';

                if (isset($_POST['custom']) && !empty($_POST['custom'])) {
                    foreach ($_POST['custom'] as $key => $value) {
                        if (is_array($value)) {
                            $_POST[$key] = implode(',', $value);
                        } else {
                            $_POST[$key] = $value;
                        }
                    }
                }
            }
            //店铺信息
            $database_merchant_store = D('Merchant_store');
            foreach ($_POST['store'] as $key => $value) {
                $condition_merchant_store['store_id'] = $value;
                $tmp_group_store = $database_merchant_store->field('`store_id`,`province_id`,`city_id`,`area_id`,`circle_id`')->where($condition_merchant_store)->find();
                if (!empty($tmp_group_store)) {
                    $data_group_store_arr[] = $tmp_group_store;
                }
            }
            if (empty($data_group_store_arr)) {
                $this->error('您选择的店铺信息不正确！请重试。');
            } else if ($_POST['tuan_type'] == 2) {
                $_POST['prefix_title'] = '购物';
            } else if (count($data_group_store_arr) == 1) {
                $circle_info = D('Area')->get_area_by_areaId($data_group_store_arr[0]['circle_id']);
                $_POST['prefix_title'] = $circle_info['area_name'];
            } else {
                $_POST['prefix_title'] = count($data_group_store_arr) . '店通用';
            }

            $group_id = $now_group['group_id'];
            $condition_save_group['group_id'] = $group_id;
            $newleveloff = array();
            if (!empty($leveloff)) {
                foreach ($leveloff as $kk => $vv) {
                    $vv['type'] = intval($vv['type']);
                    $vv['vv'] = intval($vv['vv']);
                    if (($vv['type'] > 0) && ($vv['vv'] > 0)) {
                        $vv['level'] = $kk;
                        $newleveloff[$kk] = $vv;
                    }
                }
            }

            $_POST['leveloff'] = !empty($newleveloff) ? serialize($newleveloff) : '';
            if ($leveloff === false)
                unset($_POST['leveloff']);



            if($this->config['group_verify'] == 1 && $_POST['status']){
                $_POST['status'] = 2;
            }
            switch($_POST['bind_trade']){
                case 0:
                    unset($_POST['appoint_id']);
                    $_POST['trade_type']=0;
                    $_POST['trade_info']='';
                    break;
                case 1:
                    if(empty($_POST['appoint_id'])){
                        $this->error('绑定预约不能为空！');
                    }
                    $_POST['is_appoint_bind'] = 1;
                    $_POST['trade_type'] = 'appoint';
                    $_POST['trade_info'] = $_POST['appoint_id'];

                    break;
                case 2:
                    if(empty($_POST['trade_hotel_cat_id'])){
                        $this->error('请选择酒店分类');
                    }
                    if($_POST['stock_reduce_method']==1){
                        $this->error('酒店库存仅支持支付完成后减库存');
                    }
                    if($_POST['count_num']>0 ){
                        $_POST['count_num'] = 0;
                    }
                    $_POST['pin_num'] = 0;
                    $_POST['pin_effective_time'] = 0;
                    $_POST['trade_type'] = 'hotel';
                    $_POST['trade_info'] = implode(',',$_POST['trade_hotel_cat_id']);
                    $_POST['is_appoint_bind'] = 0;
                    $_POST['appoint_id'] = 0;
                    break;
            }

            if ($database_group->where($condition_save_group)->data($_POST)->save()) {
                $database_group_store = D('Group_store');
                $condition_group_store['group_id'] = $group_id;
                $database_group_store->where($condition_group_store)->delete();

                foreach ($data_group_store_arr as $key => $value) {
                    $data_group_store = $value;
                    $data_group_store['group_id'] = $group_id;
                    $database_group_store->data($data_group_store)->add();
                }

                //判断关键词
                $database_keywords = D('Keywords');
                $condition_keywords['third_id'] = $group_id;
                $condition_keywords['third_type'] = 'group';
                $database_keywords->where($condition_keywords)->delete();

                if (!empty($key_arr)) {
                    $data_keywords['third_id'] = $group_id;
                    $data_keywords['third_type'] = 'group';
                    foreach ($key_arr as $value) {
                        $data_keywords['keyword'] = $value;
                        $database_keywords->data($data_keywords)->add();
                    }
                }
                //添加或删除到套餐
                $mpackageDb = M('Group_packages');
                if ($now_group['packageid'] > 0) {
                    $mpackage = $mpackageDb->where(array('id' => $now_group['packageid'], 'mer_id' => $now_group['mer_id']))->find();
                    if (!empty($mpackage)) { /*                     * **删除原有的**** */
                        $mpackage['groupidtext'] = !empty($mpackage['groupidtext']) ? unserialize($mpackage['groupidtext']) : array();
                        unset($mpackage['groupidtext'][$group_id]);
                        $mpackage['groupidtext'] = !empty($mpackage['groupidtext']) ? serialize($mpackage['groupidtext']) : '';
                        $mpackageDb->where(array('id' => $mpackage['id']))->save(array('groupidtext' => $mpackage['groupidtext']));
                    }
                }
                if ($packageid > 0) { /*                 * ****现在编辑处理**** */
                    $mpackage2 = $mpackageDb->where(array('id' => $packageid, 'mer_id' => $now_group['mer_id']))->find();
                    if (!empty($mpackage2)) {
                        $mpackage2['groupidtext'] = !empty($mpackage2['groupidtext']) ? unserialize($mpackage2['groupidtext']) : array();
                        $mpackage2['groupidtext'][$group_id] = $_POST['tagname'];
                        $mpackageDb->where(array('id' => $mpackage2['id']))->save(array('groupidtext' => serialize($mpackage2['groupidtext'])));
                    }
                }
                $this->success('编辑成功！',U('Group/index'));
            } else {
                $this->error('编辑失败！请重试。');
            }
        } else {
            //图片
            $group_image_class = new group_image();
            $tmp_pic_arr = explode(';', $now_group['pic']);
            foreach ($tmp_pic_arr as $key => $value) {
                $now_group['pic_arr'][$key]['title'] = $value;
                $now_group['pic_arr'][$key]['url'] = $group_image_class->get_image_by_path($value, 's');
            }
            if ($now_group['cue']) {
                $now_group['cue_arr'] = unserialize($now_group['cue']);
            }
            
            $this->assign('now_group', $now_group);

            //关键词
            $database_keywords = D('Keywords');
            $conditon_keywords['third_id'] = $now_group['group_id'];
            $conditon_keywords['third_type'] = 'group';
            $keywords_list = $database_keywords->field('`keyword`')->where($conditon_keywords)->order('`id` ASC')->select();
            if (!empty($keywords_list)) {
                $keywords_arr = array();
                foreach ($keywords_list as $value) {
                    $keywords_arr[] = $value['keyword'];
                }
                $keywords = implode(' ', $keywords_arr);
                $this->assign('keywords', $keywords);
            }

            //所属店铺
            $database_group_store = D('Group_store');
            $condition_group_store['group_id'] = $now_group['group_id'];
            $store_list = $database_group_store->field(true)->where($condition_group_store)->select();
            $store_arr = array();
            foreach ($store_list as $value) {
                $store_arr[] = $value['store_id'];
            }
            $this->assign('store_arr', $store_arr);



            //分类
            $database_group_category = D('Group_category');
            $condition_f_group_category['cat_fid'] = 0;
            $condition_f_group_category['cat_status'] = 1;
            $f_category_list = $database_group_category->field('`cat_id`,`cat_name`,`cat_field`,`cue_field`')->where($condition_f_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
            $this->assign('f_category_list', $f_category_list);
            if (empty($f_category_list)) {
                $this->error('管理员没有添加' . $this->config['group_alias_name'] . '分类！');
            }

            foreach ($f_category_list as $value) {
                if ($value['cat_id'] == $now_group['cat_fid']) {
                    $now_f_category = $value;
                    break;
                }
            }

            $condition_s_group_category['cat_fid'] = $now_group['cat_fid'];
            $condition_s_group_category['cat_status'] = 1;
            $s_category_list = $database_group_category->field('`cat_id`,`cat_name`')->where($condition_s_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
            $this->assign('s_category_list', $s_category_list);
            if (empty($s_category_list)) {
                $this->error($f_category_list[0]['cat_name'] . ' 分类下没有添加子分类！');
            }
            if (!empty($now_f_category['cat_field'])) {
                $cat_field = unserialize($now_f_category['cat_field']);
                $custom_html = '';
                foreach ($cat_field as $key => $value) {
                    if (empty($value['use_field'])) {
                        $custom_html .= '<div class="form-group"><label class="col-sm-1">' . $value['name'] . '：</label>';
                        if ($value['type'] == 0) {
                            $custom_html .= '<select name="custom[custom_' . $key . ']" class="col-sm-1">';
                            foreach ($value['value'] as $k => $v) {
                                $custom_html .= '<option value="' . $k . '"';
                                if ($now_group['custom_' . $key] == $k) {
                                    $custom_html .=' selected=selected';
                                }
                                $custom_html .= ' >' . $v . '</option>';
                            }
                            $custom_html .= '</select>';
                        } else {
                            $checkVarr = explode(',', $now_group['custom_' . $key]);
                            $checkVarr = !empty($checkVarr) ? $checkVarr : array();
                            foreach ($value['value'] as $k => $v) {
                                $custom_html .= '<label style="margin-right:30px;"><input class="ace" type="checkbox" name="custom[custom_' . $key . '][]" value="' . $k . '" id="custom_' . $key . '_' . $k . '"';
                                if (in_array($k, $checkVarr)) {
                                    $custom_html .=' checked=checked';
                                }
                                $custom_html .= ' /><span class="lbl"><label for="custom_' . $key . '_' . $k . '">&nbsp;' . $v . '</label></span></label>';
                            }
                        }
                        $custom_html .= '</div>';
                    }
                }
            }
            $this->assign('custom_html', $custom_html);

            if (!empty($now_f_category['cue_field'])) {
                $cue_field = unserialize($now_f_category['cue_field']);
                $cue_html = '';
                foreach ($cue_field as $key => $value) {
                    $cue_html .= '<div class="form-group"><label class="col-sm-1">' . $value['name'] . '：</label>';
                    if ($value['type'] == 0) {
                        $cue_html .= '<input type="hidden" name="cue_field[key][]" value="' . $value['name'] . '"/><input type="text" class="col-sm-4" name="cue_field[value][]" value="' . (!empty($now_group['cue_arr'][$key]['value']) ? $now_group['cue_arr'][$key]['value'] : '') . '"/>';
                    } else {
                        $cue_html .= '<input type="hidden" name="cue_field[key][]" value="' . $value['name'] . '"/><textarea class="col-sm-4" rows="5" name="cue_field[value][]">' . (!empty($now_group['cue_arr'][$key]['value']) ? $now_group['cue_arr'][$key]['value'] : '') . '</textarea>';
                    }
                    $cue_html .= '</div>';
                }
            }
            $this->assign('cue_html', $cue_html);

            $mer_id = $this->merchant_session['mer_id'];
            $db_arr = array(C('DB_PREFIX') . 'area' => 'a', C('DB_PREFIX') . 'merchant_store' => 's');
            $store_list = D()->table($db_arr)->field('a.`area_name`,s.`adress`,`s`.`name`,`s`.`store_id`')->where("`s`.`mer_id`='$mer_id' AND `s`.`status`='1' AND `s`.`have_group`='1' AND `s`.`area_id`=`a`.`area_id`")->order('`sort` DESC,`store_id` ASC')->select();
            if (empty($store_list)) {
                $this->error('您暂时还没有能添加' . $this->config['group_alias_name'] . '信息的店铺！');
            }
            $this->assign('store_list', $store_list);
            $leveloff = !empty($now_group['leveloff']) ? unserialize($now_group['leveloff']) : false;

            $levelDb = M('User_level');
            $tmparr = $levelDb->order('id ASC')->select();
            $levelarr = array();
            if ($tmparr && $this->config['level_onoff']) {
                foreach ($tmparr as $vv) {
                    if (!empty($leveloff) && isset($leveloff[$vv['level']])) {
                        $vv['vv'] = $leveloff[$vv['level']]['vv'];
                        $vv['type'] = $leveloff[$vv['level']]['type'];
                    } else {
                        $vv['vv'] = '';
                        $vv['type'] = '';
                    }
                    $levelarr[$vv['level']] = $vv;
                }
            }
            unset($tmparr);
            $this->assign('levelarr', $levelarr);
            $mpackageDb = M('Group_packages');
            $mpackagelist = $mpackageDb->field(true)->where(array('mer_id' => $now_group['mer_id']))->order('id DESC')->select();
            $this->assign('mpackagelist', $mpackagelist);
            
            if(isset($this->config['appoint_category_bgimg'])){
				$database_appoint = D('Appoint');
				$appoint_list = $database_appoint->get_appointmerchantlist_by_MerchantId($mer_id);
				$this->assign('appoint_list',$appoint_list);
			}
            ///if($now_group['trade_type']=='hotel') {
                $database_trade_hotel_category = D('Trade_hotel_category');
                $trade_hotel_category_list = $database_trade_hotel_category->where(array('mer_id' => $mer_id, 'is_remove' => '0', 'cat_fid' => '0'))->order('`cat_sort` DESC')->select();
                $this->assign('trade_hotel_category_list', $trade_hotel_category_list);
           // }

            $where_express = array('mer_id' => $this->merchant_session['mer_id']);

            $express_list = M('Express_template')->where($where_express)->select();

            $this->assign('express_list', $express_list);

            $this->display();
        }
    }

    public function frame_edit() {
        if (empty($_SESSION['system'])) {
            $this->error('非法修改');
        }

        $database_group = D('Group');
        $condition_group['group_id'] = $_GET['group_id'];
        $now_group = $database_group->field(true)->where($condition_group)->find();
        if($now_group['trade_info']!=0){
            $now_group['trade_info'] = explode(',',$now_group['trade_info']);
        }

        if (empty($now_group)) {
            $this->error('该' . $this->config['group_alias_name'] . '不存在！');
        }
        if (IS_POST) {
            if (empty($_POST['name'])) {
                $this->error('请填写商品标题');
            }
            if (empty($_POST['s_name'])) {
                $this->error('请填写商品名称');
            }
            if (empty($_POST['intro'])) {
                $this->error('请填写商品简介');
            }
            if($now_group['tuan_type']==2 && $_POST['open_express']==0 && $_POST['pick_in_store']==0){
                $this->error('请选择一种配送方式');
            }
            //判断关键词
            $keywords = trim($_POST['keywords']);
            if (!empty($keywords)) {
                $tmp_key_arr = explode(' ', $keywords);
                $key_arr = array();
                foreach ($tmp_key_arr as $value) {
                    if (!empty($value)) {
                        array_push($key_arr, $value);
                    }
                }
                if (count($key_arr) > 5) {
                    $this->error('关键词最多5个。');
                }
            }

            if (empty($_POST['old_price'])&&$this->config['open_extra_price']==0) {
                $this->error('请填写商品原价');
            }
            if (empty($_POST['price'])&&$this->config['open_extra_price']==0) {
                $this->error('请填写商品' . $this->config['group_alias_name'] . '价');
            }
			if($_POST['price'] - $_POST['wx_cheap'] <= 0){
				$this->error('商品价格减去微信优惠不能小于0');
			}
            if (empty($_POST['store'])) {
                $this->error('请至少选择一家店铺');
            }
            if (empty($_POST['content'])) {
                $this->error('请填写本单详情');
            }
            if (empty($_POST['pic'])) {
                $this->error('请至少上传一张照片');
            }
            if (empty($_POST['success_num'])) {
                $this->error('成功' . $this->config['group_alias_name'] . '人数要求至少为1人');
            }
            //商品总数量
            if ($_POST['count_num'] > 0 && $_POST['count_num'] < $_POST['once_min']) {
            	$this->error('商品总数量不能小于一次最少购买数量');
            }
            
            if ($_POST['once_max'] > 0 && $_POST['once_min'] > $_POST['once_max']) {
            	$this->error('ID最多购买数量不能小于一次最少购买数量');
            }
			
			if($_POST['is_appoint_bind'] && ($_POST['is_appoint_bind'] == 1)){
                if(!$_POST['appoint_id']){
                    $this->error('绑定预约不能为空！');
                }
            }
			
            $leveloff = isset($_POST['leveloff']) ? $_POST['leveloff'] : false;
            isset($_POST['tagname']) && $_POST['tagname'] = trim($_POST['tagname']);
            $packageid = isset($_POST['packageid']) ? intval($_POST['packageid']) : 0;
			 if(($packageid>0) && empty($_POST['tagname'])){
			     $this->error($this->config['group_alias_name'] .'套餐标签必须要写上！');
			 }
            unset($_POST['leveloff']);
            $_POST['pic'] = implode(';', $_POST['pic']);

            $_POST['content'] = fulltext_filter($_POST['content']);
            $_POST['discount'] = $_POST['price'] / $_POST['old_price'] * 10;

            $_POST['sort'] = intval($_POST['sort']);
            $_POST['index_sort'] = intval($_POST['index_sort']);
            $_POST['group_max_score_use'] = floatval($_POST['group_max_score_use']);
            $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
            $_POST['begin_time'] = strtotime($_POST['begin_time']);
            $_POST['end_time'] = strtotime($_POST['end_time']);
            $_POST['deadline_time'] = strtotime($_POST['deadline_time']);

            if ($_POST['cue_field']) {
                $cue_field = array();
                foreach ($_POST['cue_field']['value'] as $key => $value) {
                    array_push($cue_field, array('key' => $_POST['cue_field']['key'][$key], 'value' => $value));
                }
                $_POST['cue'] = serialize($cue_field);
            }
            if (!isset($_POST['noedittype']) && isset($_POST['cat_fid']) && isset($_POST['cat_id'])) {
                $_POST['custom_0'] = $_POST['custom_1'] = $_POST['custom_2'] = $_POST['custom_3'] = $_POST['custom_4'] = '';

                if (isset($_POST['custom']) && !empty($_POST['custom'])) {
                    foreach ($_POST['custom'] as $key => $value) {
                        if (is_array($value)) {
                            $_POST[$key] = implode(',', $value);
                        } else {
                            $_POST[$key] = $value;
                        }
                    }
                }
            }


            //店铺信息
            $database_merchant_store = D('Merchant_store');
            foreach ($_POST['store'] as $key => $value) {
                $condition_merchant_store['store_id'] = $value;
                $tmp_group_store = $database_merchant_store->field('`store_id`,`province_id`,`city_id`,`area_id`,`circle_id`')->where($condition_merchant_store)->find();
                if (!empty($tmp_group_store)) {
                    $data_group_store_arr[] = $tmp_group_store;
                }
            }
            if (empty($data_group_store_arr)) {
                $this->error('您选择的店铺信息不正确！请重试。');
            } else if ($_POST['tuan_type'] == 2) {
                $_POST['prefix_title'] = '购物';
            } else if (count($data_group_store_arr) == 1) {
                $circle_info = D('Area')->get_area_by_areaId($data_group_store_arr[0]['circle_id']);
                $_POST['prefix_title'] = $circle_info['area_name'];
            } else {
                $_POST['prefix_title'] = count($data_group_store_arr) . '店通用';
            }

            $group_id = $now_group['group_id'];
            $condition_save_group['group_id'] = $group_id;
            $newleveloff = array();
            if (!empty($leveloff)) {
                foreach ($leveloff as $kk => $vv) {
                    $vv['type'] = intval($vv['type']);
                    $vv['vv'] = intval($vv['vv']);
                    if (($vv['type'] > 0) && ($vv['vv'] > 0)) {
                        $vv['level'] = $kk;
                        $newleveloff[$kk] = $vv;
                    }
                }
            }

            $_POST['leveloff'] = !empty($newleveloff) ? serialize($newleveloff) : '';
            if ($leveloff === false)
                unset($_POST['leveloff']);


            switch($_POST['bind_trade']){
                case 0:
                    $_POST['trade_type']=0;
                    $_POST['trade_info']='';
                    unset($_POST['appoint_id']);
                    break;
                case 1:
                    if(empty($_POST['appoint_id'])){
                        $this->error('绑定预约不能为空！');
                    }
                    $_POST['is_appoint_bind'] = 1;
                    $_POST['trade_type'] = 'appoint';
                    $_POST['trade_info'] = $_POST['appoint_id'];

                    break;
                case 2:
                    if(empty($_POST['trade_hotel_cat_id'])){
                        $this->error('请选择酒店分类');
                    }
                    $_POST['trade_type'] = 'hotel';
                    $_POST['trade_info'] = implode(',',$_POST['trade_hotel_cat_id']);
                    $_POST['is_appoint_bind'] = 0;
                    $_POST['appoint_id'] = 0;
                    break;
            }


            if ($database_group->where($condition_save_group)->data($_POST)->save()) {
                $database_group_store = D('Group_store');
                $condition_group_store['group_id'] = $group_id;
                $database_group_store->where($condition_group_store)->delete();

                foreach ($data_group_store_arr as $key => $value) {
                    $data_group_store = $value;
                    $data_group_store['group_id'] = $group_id;
                    $database_group_store->data($data_group_store)->add();
                }

                //判断关键词
                $database_keywords = D('Keywords');
                $condition_keywords['third_id'] = $group_id;
                $condition_keywords['third_type'] = 'group';
                $database_keywords->where($condition_keywords)->delete();

                if (!empty($key_arr)) {
                    $data_keywords['third_id'] = $group_id;
                    $data_keywords['third_type'] = 'group';
                    foreach ($key_arr as $value) {
                        $data_keywords['keyword'] = $value;
                        $database_keywords->data($data_keywords)->add();
                    }
                }
                //添加或删除到套餐
                $mpackageDb = M('Group_packages');
                if ($now_group['packageid'] > 0) {
                    $mpackage = $mpackageDb->where(array('id' => $now_group['packageid'], 'mer_id' => $now_group['mer_id']))->find();
                    if (!empty($mpackage)) { /*                     * **删除原有的**** */
                        $mpackage['groupidtext'] = !empty($mpackage['groupidtext']) ? unserialize($mpackage['groupidtext']) : array();
                        unset($mpackage['groupidtext'][$group_id]);
                        $mpackage['groupidtext'] = !empty($mpackage['groupidtext']) ? serialize($mpackage['groupidtext']) : '';
                        $mpackageDb->where(array('id' => $mpackage['id']))->save(array('groupidtext' => $mpackage['groupidtext']));
                    }
                }
                if ($packageid > 0) { /*                 * ****现在编辑处理**** */
                    $mpackage2 = $mpackageDb->where(array('id' => $packageid, 'mer_id' => $now_group['mer_id']))->find();
                    if (!empty($mpackage2)) {
                        $mpackage2['groupidtext'] = !empty($mpackage2['groupidtext']) ? unserialize($mpackage2['groupidtext']) : array();
                        $mpackage2['groupidtext'][$group_id] = $_POST['tagname'];
                        $mpackageDb->where(array('id' => $mpackage2['id']))->save(array('groupidtext' => serialize($mpackage2['groupidtext'])));
                    }
                }
                $this->success('编辑成功！');
            } else {
                $this->error('编辑失败！请重试。');
            }
        } else {
            //图片
            $group_image_class = new group_image();
            $tmp_pic_arr = explode(';', $now_group['pic']);
            foreach ($tmp_pic_arr as $key => $value) {
                $now_group['pic_arr'][$key]['title'] = $value;
                $now_group['pic_arr'][$key]['url'] = $group_image_class->get_image_by_path($value, 's');
            }
            if ($now_group['cue']) {
                $now_group['cue_arr'] = unserialize($now_group['cue']);
            }
            $this->assign('now_group', $now_group);

            //关键词
            $database_keywords = D('Keywords');
            $conditon_keywords['third_id'] = $now_group['group_id'];
            $conditon_keywords['third_type'] = 'group';
            $keywords_list = $database_keywords->field('`keyword`')->where($conditon_keywords)->order('`id` ASC')->select();
            if (!empty($keywords_list)) {
                $keywords_arr = array();
                foreach ($keywords_list as $value) {
                    $keywords_arr[] = $value['keyword'];
                }
                $keywords = implode(' ', $keywords_arr);
                $this->assign('keywords', $keywords);
            }

            //所属店铺
            $database_group_store = D('Group_store');
            $condition_group_store['group_id'] = $now_group['group_id'];
            $store_list = $database_group_store->field(true)->where($condition_group_store)->select();
            $store_arr = array();
            foreach ($store_list as $value) {
                $store_arr[] = $value['store_id'];
            }
            $this->assign('store_arr', $store_arr);



            //分类
            $database_group_category = D('Group_category');
            $condition_f_group_category['cat_fid'] = 0;
            $condition_f_group_category['cat_status'] = 1;
            $f_category_list = $database_group_category->field('`cat_id`,`cat_name`,`cat_field`,`cue_field`')->where($condition_f_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
            $this->assign('f_category_list', $f_category_list);
            if (empty($f_category_list)) {
                $this->error('管理员没有添加' . $this->config['group_alias_name'] . '分类！');
            }

            foreach ($f_category_list as $value) {
                if ($value['cat_id'] == $now_group['cat_fid']) {
                    $now_f_category = $value;
                    break;
                }
            }

            $condition_s_group_category['cat_fid'] = $now_group['cat_fid'];
            $condition_s_group_category['cat_status'] = 1;
            $s_category_list = $database_group_category->field('`cat_id`,`cat_name`')->where($condition_s_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
            $this->assign('s_category_list', $s_category_list);
            if (empty($s_category_list)) {
                $this->error($f_category_list[0]['cat_name'] . ' 分类下没有添加子分类！');
            }
            if (!empty($now_f_category['cat_field'])) {
                $cat_field = unserialize($now_f_category['cat_field']);
                $custom_html = '';
                foreach ($cat_field as $key => $value) {
                    if (empty($value['use_field'])) {
                        $custom_html .= '<div class="form-group"><label class="col-sm-1">' . $value['name'] . '：</label>';
                        if ($value['type'] == 0) {
                            $custom_html .= '<select name="custom[custom_' . $key . ']" class="col-sm-1">';
                            foreach ($value['value'] as $k => $v) {
                                $custom_html .= '<option value="' . $k . '"';
                                if ($now_group['custom_' . $key] == $k) {
                                    $custom_html .=' selected=selected';
                                }
                                $custom_html .= ' >' . $v . '</option>';
                            }
                            $custom_html .= '</select>';
                        } else {
                            $checkVarr = explode(',', $now_group['custom_' . $key]);
                            $checkVarr = !empty($checkVarr) ? $checkVarr : array();
                            foreach ($value['value'] as $k => $v) {
                                $custom_html .= '<label style="margin-right:30px;"><input class="ace" type="checkbox" name="custom[custom_' . $key . '][]" value="' . $k . '" id="custom_' . $key . '_' . $k . '"';
                                if (in_array($k, $checkVarr)) {
                                    $custom_html .=' checked=checked';
                                }
                                $custom_html .= ' /><span class="lbl"><label for="custom_' . $key . '_' . $k . '">&nbsp;' . $v . '</label></span></label>';
                            }
                        }
                        $custom_html .= '</div>';
                    }
                }
            }
            $this->assign('custom_html', $custom_html);

            if (!empty($now_f_category['cue_field'])) {
                $cue_field = unserialize($now_f_category['cue_field']);
                $cue_html = '';
                foreach ($cue_field as $key => $value) {
                    $cue_html .= '<div class="form-group"><label class="col-sm-1">' . $value['name'] . '：</label>';
                    if ($value['type'] == 0) {
                        $cue_html .= '<input type="hidden" name="cue_field[key][]" value="' . $value['name'] . '"/><input type="text" class="col-sm-4" name="cue_field[value][]" value="' . (!empty($now_group['cue_arr'][$key]['value']) ? $now_group['cue_arr'][$key]['value'] : '') . '"/>';
                    } else {
                        $cue_html .= '<input type="hidden" name="cue_field[key][]" value="' . $value['name'] . '"/><textarea class="col-sm-4" rows="5" name="cue_field[value][]">' . (!empty($now_group['cue_arr'][$key]['value']) ? $now_group['cue_arr'][$key]['value'] : '') . '</textarea>';
                    }
                    $cue_html .= '</div>';
                }
            }
            $this->assign('cue_html', $cue_html);

            $mer_id = $this->merchant_session['mer_id'];
            $db_arr = array(C('DB_PREFIX') . 'area' => 'a', C('DB_PREFIX') . 'merchant_store' => 's');
            $store_list = D()->table($db_arr)->field('a.`area_name`,s.`adress`,`s`.`name`,`s`.`store_id`')->where("`s`.`mer_id`='$mer_id' AND `s`.`status`='1' AND `s`.`have_group`='1' AND `s`.`area_id`=`a`.`area_id`")->order('`sort` DESC,`store_id` ASC')->select();
            if (empty($store_list)) {
                $this->error('您暂时还没有能添加' . $this->config['group_alias_name'] . '信息的店铺！');
            }
            $this->assign('store_list', $store_list);
            $leveloff = !empty($now_group['leveloff']) ? unserialize($now_group['leveloff']) : false;

            $levelDb = M('User_level');
            $tmparr = $levelDb->where('22=22')->order('id ASC')->select();
            $levelarr = array();
            if ($tmparr && $this->config['level_onoff']) {
                foreach ($tmparr as $vv) {
                    if (!empty($leveloff) && isset($leveloff[$vv['level']])) {
                        $vv['vv'] = $leveloff[$vv['level']]['vv'];
                        $vv['type'] = $leveloff[$vv['level']]['type'];
                    } else {
                        $vv['vv'] = '';
                        $vv['type'] = '';
                    }
                    $levelarr[$vv['level']] = $vv;
                }
            }
            unset($tmparr);
            $this->assign('levelarr', $levelarr);
            $mpackageDb = M('Group_packages');
            $mpackagelist = $mpackageDb->field(true)->where(array('mer_id' => $now_group['mer_id']))->order('id DESC')->select();
            $this->assign('mpackagelist', $mpackagelist);

            if(isset($this->config['appoint_category_bgimg'])){
                $database_appoint = D('Appoint');
                $appoint_list = $database_appoint->get_appointmerchantlist_by_MerchantId($mer_id);
                $this->assign('appoint_list',$appoint_list);
            }
            ///if($now_group['trade_type']=='hotel') {
            $database_trade_hotel_category = D('Trade_hotel_category');
            $trade_hotel_category_list = $database_trade_hotel_category->where(array('mer_id' => $mer_id, 'is_remove' => '0', 'cat_fid' => '0'))->order('`cat_sort` DESC')->select();
            $this->assign('trade_hotel_category_list', $trade_hotel_category_list);
            // }

            $this->display();
        }
    }

    public function ajax_get_category() {
        $database_group_category = D('Group_category');
        $condition_now_group_category['cat_id'] = $_GET['cat_fid'];
        $condition_now_group_category['cat_status'] = 1;
        $now_category = $database_group_category->field('`cat_field`,`cue_field`')->where($condition_now_group_category)->find();
        if (empty($now_category)) {
            $return['error'] = 1;
            $return['msg'] = '该分类不存在！';
        } else {
            $condition_s_group_category['cat_fid'] = $_GET['cat_fid'];
            $condition_s_group_category['cat_status'] = 1;
            $s_category_list = $database_group_category->field('`cat_id`,`cat_name`')->where($condition_s_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
            if (empty($s_category_list)) {
                $return['error'] = 1;
                $return['msg'] = '该分类下没有添加子分类！请勿选择。';
            } else {
                if (!empty($now_category['cat_field'])) {
                    $cat_field = unserialize($now_category['cat_field']);
                    $custom_html = '';
                    foreach ($cat_field as $key => $value) {
                        if (empty($value['use_field'])) {
                            $custom_html .= '<div class="form-group"><label class="col-sm-1">' . $value['name'] . '：</label>';
                            if ($value['type'] == 0) {
                                $custom_html .= '<select name="custom[custom_' . $key . ']" class="col-sm-1" style="margin-right:10px;">';
                                foreach ($value['value'] as $k => $v) {
                                    $custom_html .= '<option value="' . $k . '">' . $v . '</option>';
                                }
                                $custom_html .= '</select>';
                            } else {
                                foreach ($value['value'] as $k => $v) {
                                    $custom_html .= '<label style="margin-right:30px;"><input class="ace" type="checkbox" name="custom[custom_' . $key . '][]" value="' . $k . '" id="custom_' . $key . '_' . $k . '"/><span class="lbl"><label for="custom_' . $key . '_' . $k . '">&nbsp;' . $v . '</label></span></label>';
                                }
                            }
                            $custom_html .= '</div>';
                        }
                    }
                    $return['custom_html'] = $custom_html;
                } else {
                    $return['custom_html'] = '';
                }

                if (!empty($now_category['cue_field'])) {
                    $cue_field = unserialize($now_category['cue_field']);
                    $cue_html = '';
                    foreach ($cue_field as $key => $value) {
                        $cue_html .= '<div class="form-group"><label class="col-sm-1">' . $value['name'] . '：</label>';
                        if ($value['type'] == 0) {
                            $cue_html .= '<input type="hidden" name="cue_field[key][]" value="' . $value['name'] . '"/><input type="text" class="col-sm-4" name="cue_field[value][]"/>';
                        } else {
                            $cue_html .= '<input type="hidden" name="cue_field[key][]" value="' . $value['name'] . '"/><textarea class="col-sm-4" rows="5" name="cue_field[value][]"></textarea>';
                        }
                        $cue_html .= '</div>';
                    }
                    $return['cue_html'] = $cue_html;
                } else {
                    $return['cue_html'] = '';
                }

                $return['error'] = 0;
                $return['cat_list'] = $s_category_list;
            }
        }
        exit(json_encode($return));
    }

    public function ajax_upload_pic() {
        if ($_FILES['imgFile']['error'] != 4) {
			$param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $this->config['group_pic_width'];
            $param['thumbMaxHeight'] = $this->config['group_pic_height'];
            $param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'group', 1, $param);
			if ($image['error']) {
				exit(json_encode(array('error' => 1,'message' =>$image['message'])));
			} else {
				$title = $image['title']['imgFile'];
				$group_image_class = new group_image();
				$url = $group_image_class->get_image_by_path($title, 's');
				exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
    public function ajax_del_pic() {
        $group_image_class = new group_image();
        $group_image_class->del_image_by_path($_POST['path']);
    }

    public function order_list() {

        $mer_id = $this->merchant_session['mer_id'];
        $group_id = intval($_GET['group_id']);
        //团购列表
        $now_group = D('Group')->get_group_by_groupId($group_id);
        if (empty($now_group)) {
            //$this->error('当前' . $this->config['group_alias_name'] . '不存在！');
        }
        $this->assign('now_group', $now_group);


        $condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `m`.`mer_id`=`o`.`mer_id` AND `o`.`mer_id` = ".$mer_id;
        if($group_id){
            $condition_where.=' AND o.group_id = '.$group_id;
        }
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'real_orderid') {
                $condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
            } elseif ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
                $condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
            } elseif ($_GET['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= " AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
            } elseif ($_GET['searchtype'] == 's_name') {
                $condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            }elseif ($_GET['searchtype'] == 'third_id') {
                $condition_where .= " AND `o`.`third_id` ='".$_GET['keyword']."'";
            }
        }
        if ($this->system_session['area_id']) {
            $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
            $condition_where .= " AND `m`.`{$area_index}`={$this->system_session['area_id']}";
        }

        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';
        $order_sort = '';
        if ($type && $sort) {
            $order_sort .= 'o.' . $type . ' ' . $sort . ',';
            $order_sort .= 'o.order_id DESC';
        } else {
            $order_sort .= 'o.order_id DESC';
        }

        if ($status != -1) {
            $condition_where .= " AND `o`.`status`={$status}";
        }
        if($pay_type){
            if($pay_type=='balance'){
                $condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 OR `o`.`card_give_money` <> 0) AND `o`.`paid` = 1";
            }else{
                $condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
            }
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
            //$condition_where['_string']=$time_condition;
        }

        $condition_table = array(C('DB_PREFIX').'group'=>'g', C('DB_PREFIX').'group_order'=>'o', C('DB_PREFIX').'user'=>'u', C('DB_PREFIX').'merchant'=>'m');
        $order_count = D('')->where($condition_where)->table($condition_table)->count();
        import('@.ORG.merchant_page');
        $p = new Page($order_count,20);

        $order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`g`.`price` as g_price,`u`.`uid`,`u`.`nickname`,`u`.`phone`,`m`.`phone` as m_phone,`m`.`name` as m_name,`m`.`mer_id`,`g`.`group_id`')->where($condition_where)->table($condition_table)->order($order_sort)->limit($p->firstRow.','.$p->listRows)->select();
        if(empty($order_list)){
            //$this->error_tips('当前'.$this->config['group_alias_name'].'并未产生订单！');
        }
        $pay_method = D('Config')->get_pay_method('','',1);
        $this->assign('pay_method',$pay_method);
        $this->assign('order_list',$order_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));
        $this->assign('status_list', D('Group_order')->status_list);

        $this->display();
    }

    //到店自提
    public function pick_in_store(){


        $pick_addr = D('Pick_address')->get_pick_addr_by_merid($this->merchant_session['mer_id'],true);
        $this->assign('pick_addr',$pick_addr);
        $this->display();
    }

    public function pick_address_add(){
        if(IS_POST){
            if(empty($_POST['pick_addr'])||empty($_POST['phone'])){
                $this->error("信息不全，请检查！");
            }
            $_POST['mer_id'] = $this->merchant_session['mer_id'];
            if(M('Pick_address')->add($_POST)){
                $this->success("保存成功！");
            }else{
                $this->error("保存失败！");
            }

        }else{
            $this->display();
        }
    }

    public function pick_address_edit(){
        if(IS_POST){

            if(M('Pick_address')->where(array('id'=>$_POST['id']))->save($_POST)){
                $this->success("保存成功！");
            }else{
                $this->error("保存失败！");
            }

        }else{
            if(!empty($_GET['id'])){
                $n = preg_match('/\d+/',$_GET['id'],$id);
                $pick_addr = M('Pick_address')->where(array('id'=>$id[0]))->find();

                $this->assign('pick_addr',$pick_addr);
                $this->display();
            }else{
                $this->error("访问失败！");
            }
        }
    }

    public function pick_address_del(){
        if(IS_GET){
            $n = preg_match('/\d+/',$_GET['id'],$id);
            if(M('Pick_address')->where(array('id'=>$id[0]))->delete()){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        }
    }

    public function order_detail() {
        if(strlen($_GET['order_id'])>=20){
            $now_group_order = D('Group_order')->where(array('real_orderid'=>$_GET['order_id']))->find();
            $_GET['order_id'] = $now_group_order['order_id'];
        }
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'], $_GET['order_id'], false);
        if (empty($now_order)) {
            exit('此订单不存在！');
        }
        if (!empty($now_order['pay_type'])) {
            if($now_order['is_pick_in_store']){
                $now_order['paytypestr']="到店自提";
            }else{
                $now_order['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type'],$now_order['is_mobile_pay'],$now_order['paid']);
            }

            if (($now_order['pay_type'] == 'offline') && !empty($now_order['third_id']) && ($now_order['paid'] == 1)) {
                $now_order['paytypestr'] .='<span style="color:green">&nbsp; 已支付</span>';
            } else if (($now_order['pay_type'] != 'offline') && ($now_order['paid'] == 1)) {
                $now_order['paytypestr'] .='<span style="color:green">&nbsp; 已支付</span>';
            } else {
                $now_order['paytypestr'] .='<span style="color:red">&nbsp; 未支付</span>';
            }
        }else {
			if($now_order['paid']){
				$now_order['paytypestr'] = '余额支付';
			}else{
				$now_order['paytypestr'] = '未支付';
			}
        }
        $pin_info = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
        $this->assign('pin_info', $pin_info);

        if ($now_order['tuan_type'] == 2 && $now_order['paid'] == 1) {
            $express_list = D('Express')->get_express_list();
            $this->assign('express_list', $express_list);

            //得到该订单归属团购的店铺列表
            $group_store_list = D('Group_store')->get_storelist_by_groupId($now_order['group_id']);
            $this->assign('group_store_list', $group_store_list);
        }
        $pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
        $this->assign('pass_array',$pass_array);
//        if(!empty($now_order['coupon_id'])) {
//            $system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
//            $now_order['coupon_price'] = $system_coupon['price'];
//            $this->assign('system_coupon',$system_coupon);
//        }else if(!empty($now_order['card_id'])) {
//            $card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
//            $now_order['coupon_price'] = $card['price'];
//            $this->assign('card', $card);
//        }
//
		if($now_order['trade_info']){
			$trade_info_arr = unserialize($now_order['trade_info']);
			if($trade_info_arr['type'] == 'hotel'){
				$trade_hotel_info = D('Trade_hotel_category')->format_order_trade_info($now_order['trade_info']);
				$this->assign('trade_hotel_info',$trade_hotel_info);
			}
		}
		
        $this->assign('now_order', $now_order);
        $this->display();
    }

    public function order_store_id() {
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'], $_GET['order_id'], true, false);
        if (empty($now_order)) {
            $this->error('此订单不存在！');
        }
        if (empty($now_order['paid'])) {
            $this->error('此订单尚未支付！');
        }
        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['store_id'] = $_POST['store_id'];
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
			$now_order['store_id'] = $_POST['store_id'];
			//店员APP推送
			D('Merchant_store_staff')->sendMsgGroupOrder($now_order);
			
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！请重试。');
        }
    }

    public function group_remark() {
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'], $_GET['order_id'], true, false);
        if (empty($now_order)) {
            $this->error('此订单不存在！');
        }
        if (empty($now_order['paid'])) {
            $this->error('此订单尚未支付！');
        }
        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['merchant_remark'] = $_POST['merchant_remark'];
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！请重试。');
        }
    }

    public function group_pass_array(){
        $this->check_group();
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'],$_GET['order_id'],false);
        $pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
        $this->assign('pass_array',$pass_array);
        $this->assign('now_order',$now_order);
        $this->display();
    }

    /*     * ****套餐管理页**开始****** */

    public function mpackage() {
        $mpackageDb = M('Group_packages');
        $_count = $mpackageDb->where(array('mer_id' => $this->merchant_session['mer_id']))->count();
        import('@.ORG.merchant_page');
        $p = new Page($_count, 20);
        $mpackagelist = $mpackageDb->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->order('id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('mpackagelist', $mpackagelist);
        $this->display();
    }

    public function mpackageadd() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $mpackageDb = M('Group_packages');
        if (IS_POST) {
            $_POST['title'] = trim($_POST['title']);
            if (empty($_POST['title']))
                $this->error('套餐标示名称不能为空，必须填上');
            $id = isset($_POST['idx']) ? intval($_POST['idx']) : $id;
            unset($_POST['idx']);
            $_POST['mer_id'] = $this->merchant_session['mer_id'];
            if ($id > 0) {
                $tmpid = $mpackageDb->where(array('id' => $id))->save($_POST);
                $this->success('修改成功！', U('Group/mpackage'));
                exit();
            } else {
                $tmpid = $mpackageDb->add($_POST);
                $this->success('保存成功！', U('Group/mpackage'));
                exit();
            }
            $this->error('保存失败');
            exit();
        } else {
            $mpackage = $mpackageDb->where(array('id' => $id))->find();
            $this->assign('mpackage', !empty($mpackage) ? $mpackage : array('id' => 0, 'title' => '', 'description' => ''));
            $this->display();
        }
    }

    public function start_group_list(){
        $mer_id =  $this->merchant_session['mer_id'];
        $start_group_list = D('Group_start')->get_start_group_list_by_merid($mer_id);
        $this->assign('start_group_list',$start_group_list);
        $this->display();
    }

    public function start_group_info(){
        $mer_id =  $this->merchant_session['mer_id'];
        $buyer_list = D('Group_start')->get_buyer_list_by_order_id('',$_GET['id']);
        $robot_list = M('Robot_list')->where(array('mer_id'=>$mer_id))->getField('id,robot_name');
        foreach ($buyer_list as &$v) {
            if($v['type']==1){
                $v['nickname'] = $robot_list[$v['uid']];
                $v['phone'] = '';
            }
        }

        $this->assign('buyer_list',$buyer_list);
        $this->display();
    }

    public function update_start_status(){
        $status = $_POST['status'];
        $id = explode(',',$_POST['id']);

        if($_POST['status']==3){
            foreach ($id as $item) {
                $start_info = D('Group_start')->where(array('id'=>$item))->find();
                $need_num = $start_info['complete_num']-$start_info['num'];
                if($need_num>0){
                    D('Group_start')->create_robot($start_info);
                }
            }

        }
        $this->ajaxReturn(D('Group_start')->update_start_group($id,$status));
    }



    public function export()
    {
       $param = $_POST;
        $param['type'] = 'group';
        $param['rand_number'] = time();
        $param['merchant_session']['mer_id'] = $this->merchant_session['mer_id'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
        die;
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '团购订单信息';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        // 设置当前的sheet
        $condition_where = "WHERE o.mer_id = ".$this->merchant_session['mer_id'];
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'real_orderid') {
                $condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
            } elseif ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
                $condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
            } elseif ($_GET['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= " AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
            } elseif ($_GET['searchtype'] == 's_name') {
                $condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            }elseif ($_GET['searchtype'] == 'third_id') {
                $condition_where .= " AND `o`.`third_id` ='".$_GET['keyword']."'";
            }
        }
        if ($this->system_session['area_id']) {
            $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
            $condition_where .= " AND `m`.`{$area_index}`={$this->system_session['area_id']}";
        }

        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';
        $order_sort = '';
        if ($type && $sort) {
            $order_sort .= 'o.' . $type . ' ' . $sort . ',';
            $order_sort .= 'o.order_id DESC';
        } else {
            $order_sort .= 'o.order_id DESC';
        }

        if ($status != -1) {
            $condition_where .= " AND `o`.`status`={$status}";
        }
        if($pay_type){
            if($pay_type=='balance'){
                $condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
            }else{
                $condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
            }
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
            //$condition_where['_string']=$time_condition;
        }

        $sql = "SELECT count(order_id) as count FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC ";
        $count = D()->query($sql);

        $length = ceil($count[0]['count'] / 1000);
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
            $objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '订单编号');
            $objActSheet->setCellValue('B1', '商家名称');
            $objActSheet->setCellValue('C1', '客户姓名');
            $objActSheet->setCellValue('D1', '客户电话');
            $objActSheet->setCellValue('E1', '订单总价');
            $objActSheet->setCellValue('F1', '平台余额');
            $objActSheet->setCellValue('G1', '商家余额');
            $objActSheet->setCellValue('H1', '在线支付金额');
            $objActSheet->setCellValue('I1', '平台'.$this->config['score_name'].'');
            $objActSheet->setCellValue('J1', '平台优惠券');
            $objActSheet->setCellValue('K1', '商家优惠券');
            $objActSheet->setCellValue('L1', '商家折扣');
            $objActSheet->setCellValue('M1', '支付时间');
            $objActSheet->setCellValue('N1', '订单状态');
            $objActSheet->setCellValue('O1', '支付情况');
            $sql = "SELECT o.*, m.name AS merchant_name,u.nickname as username FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
            $result_list = D()->query($sql);

            if (!empty($result_list)) {
                $index = 2;
                foreach ($result_list as $value) {
                    $objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['merchant_name']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['username'] . ' ');
                    $objActSheet->setCellValueExplicit('D' . $index, $value['phone'] . ' ');
                    $objActSheet->setCellValueExplicit('E' . $index, floatval($value['total_money']));
                    $objActSheet->setCellValueExplicit('F' . $index, floatval($value['balance_pay']));
                    $objActSheet->setCellValueExplicit('G' . $index, floatval($value['merchant_balance']));
                    $objActSheet->setCellValueExplicit('H' . $index, floatval($value['payment_money']));
                    $objActSheet->setCellValueExplicit('I' . $index, floatval($value['score_reducte']));
                    $objActSheet->setCellValueExplicit('J' . $index, floatval($value['coupon_price']));
                    $objActSheet->setCellValueExplicit('K' . $index, floatval($value['card_price']));
                    $objActSheet->setCellValueExplicit('L' . $index, floatval($value['card_discount'])?floatval($value['card_discount']). '折':'');
                    $objActSheet->setCellValueExplicit('M' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
                    $objActSheet->setCellValueExplicit('N' . $index, $this->get_order_status($value));
                    $objActSheet->setCellValueExplicit('O' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));


                    $index++;
                }
            }
            sleep(2);
        }
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function get_order_status($order){
        $status = '';
        if($order['paid']){
            if($order['pay_type']=='offline' && empty($order['third_id'])&& $order['status'] == 0){
                $status='线下支付，未付款';
            }elseif($order['status']==0){
                $status='已付款';
                if($order['tuan_type'] != 2){
                    $status.='已付款';
                }else{
                    if($order['is_pick_in_store']){
                        $status.='未取货';
                    }else{
                        $status.='未发货';
                    }
                }
            }elseif($order['status']==1){
                if($order['tuan_type'] != 2){
                    $status='已消费';
                }else{
                    if($order['is_pick_in_store']){
                        $status='已取货';
                    }else{
                        $status='已发货';
                    }
                }
                $status.='待评价';
            }elseif($order['status']==2){
                $status='已完成';
            }elseif($order['status']==3){
                $status='已退款';
            }elseif($order['status']==4){
                $status='已取消';
            }
        }else{
            if($status==4){
                $status='已取消';
            }else{
                $status='未付款';
            }
        }

        return $status;
    }

    public function del_package(){
        if(!empty($_GET['id'])){
            if(M('Group_packages')->where(array('id'=>$_GET['id']))->delete()){
                $date['tagname'] = '';
                $date['packageid'] = 0;
                $where['packageid'] = $_GET['id'];
                M('Group')->where($where)->save($date);
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('非法操作');
        }
    }

    /*     * ****套餐管理页**结束**** */
}
