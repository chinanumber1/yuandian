<?php

/**
 * 免单
 * User: 李俊
 * Date: 2017年7月29日14:21:42
 */
class Sub_cardAction extends BaseAction
{
    public function index(){
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'sub_card_id') {
                $condition_sub_card['s.id'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'name') {
                $condition_sub_card['name'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        //$condition_sub_card['delete'] = 0;
        $condition_sub_card['s.status'] = array('neq',4);
        //$condition_sub_card['ms.mer_id'] = array('EXP','IS NULL');
        //排序 /*/
        $order_string = '`id` DESC';

        $sub_card = M('Sub_card');
        $count= $sub_card->where($condition_sub_card)->join('as s LEFT JOIN ( SELECT sub_card_id FROM '.C('DB_PREFIX').'sub_card_mer_apply where mer_id='.$this->merchant_session['mer_id'].' GROUP BY mer_id ) as ms ON ms.sub_card_id = s.id ')->count();

        import('@.ORG.merchant_page');
        $p = new Page($count, 15);
        $sub_card_list = $sub_card->field('s.*,ms.sub_card_id,ms.mer_id,ms.status as check_status')
            ->join('as s LEFT JOIN ( SELECT * FROM '.C('DB_PREFIX').'sub_card_mer_apply where mer_id='.$this->merchant_session['mer_id'].' GROUP BY sub_card_id,mer_id ) as ms ON ms.sub_card_id = s.id ')
            ->where($condition_sub_card)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

        foreach ($sub_card_list as &$item) {
            if($sub_card_list['buy_time_type']==1 && $item['end_time']<time()){
                M('Sub_card')->where(array(id=>$item['id']))->setField('status',3);
                $item['status']=3;
            }
        }
        $mer_sub_card_list = M('Sub_card_mer_apply')->where(array('mer_id'=>$this->merchant_session['mer_id']))->getField('sub_card_id,status');

        //foreach ($mer_sub_card_list as $v) {
        //    $join_card[] =$v['sub_card_id'];
        //}

        $this->assign('mer_sub_card_list',$mer_sub_card_list);
        //$this->assign('join_card',$join_card);
        $this->assign('sub_card_list',$sub_card_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function merchant_join(){
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'id') {
                $condition_sub_card['id'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'name') {
                $condition_sub_card['name'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        //$condition_sub_card['delete'] = 0;
        $condition_sub_card['status'] = 0;
        //排序 /*/
        $order_string = '`id` DESC';

        $sub_card = M('Sub_card');
        $count= $sub_card->where($condition_sub_card)->count();

        import('@.ORG.merchant_page');
        $p = new Page($count, 15);
        $sub_card_list = $sub_card->field(true)->where($condition_sub_card)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
        $mer_sub_card_list = M('Sub_card_mer_apply')->where(array('mer_id'=>$this->merchant_session['mer_id']))->getField('sub_card_id,status');

        foreach ($mer_sub_card_list as $v) {
            $join_card[] =$v['sub_card_id'];
        }

        $this->assign('mer_sub_card_list',$mer_sub_card_list);
        $this->assign('join_card',$join_card);
        $this->assign('sub_card_list',$sub_card_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function join_card(){

        if(IS_POST){
            $sub_card = D('Sub_card')->get_card_info($_POST['sub_card_id']);
            if($sub_card['status']!=1){
                $this->error('该套餐状态不可编辑');
            }
            if(empty($_POST['store_id'])){
                $this->error('请勾选要参加套餐的店铺');
            }

            $store_join_list = D('Sub_card')->get_sub_card_store($_POST['sub_card_id'],$this->merchant_session['mer_id']);
            //store_max_join_num 等于 0 不限制店铺参加数
            if(  $sub_card['store_max_join_num']>0 && $sub_card['store_max_join_num']<$sub_card['store_join_num']+count($_POST['store_id'])-count($store_join_list) ){
                $this->error('参加的店铺数量已经超出最大店铺参加数');
            }

            foreach ($store_join_list as $key=>$vs) {
                if((strtotime($_POST['end_time'][$vs['store_id']])+86399!=$vs['end_time'] || strtotime($_POST['start_time'][$vs['store_id']])!=$vs['start_time']) && $vs['status']==1 && M('Sub_card_user_pass')->where(array('sub_card_id'=>$sub_card['id'],'store_id'=>$vs['store_id']))->find()){
                    $this->error("【{$vs['name']}】参加的套餐已有订单不能编辑时间");
                }
                if(!in_array($key,$_POST['store_id'])){
                    D('Sub_card')->delete_mer_join($_POST['sub_card_id'],$this->merchant_session['mer_id'],$key);
                }
            }
            foreach ($_POST['store_id'] as $key=>$v) {

                if(empty($_POST['desc'][$v])){
                    $this->error("【{$_POST['store_name'][$v]}】的描述没有填写");
                }
                if(!is_numeric($_POST['sku'][$v])||$_POST['sku'][$v]<=0){
                    $this->error("【{$_POST['store_name'][$v]}】的库存数据不正确，应填写大于0的整数");
                }
//                if(empty($_POST['pic_list'][$v]) || strpos('undefined',$_POST['pic_list'][$v])){
//                    $this->error("【{$_POST['store_name'][$v]}】的图片没有上传");
//                }

                if(strtotime($_POST['end_time'][$v])<strtotime($_POST['start_time'][$v])||strtotime($_POST['end_time'][$v])+86399<time()){
                    $this->error("【{$_POST['store_name'][$v]}】起始时间设置有误！");
                }
                $arr['sub_card_id'] = $_POST['sub_card_id'];
                $arr['sku'] = $_POST['sku'][$v];
                $arr['mer_id'] = $this->merchant_session['mer_id'];
                $arr['store_id'] = $v;
                $arr['appoint'] = $_POST['appoint'][$v];
                $arr['desc'] = $_POST['desc'][$v];
                $arr['desc_txt'] = $_POST['desc_txt'][$v];
                $arr['apply_time'] =$_SERVER['REQUEST_TIME'];
                $arr['pic_list'] =$_POST['pic_list'][$v];
                $arr['start_time'] =strtotime($_POST['start_time'][$v]);
                $arr['end_time'] =strtotime($_POST['end_time'][$v])+86399;
                $arr['status'] =$store_join_list[$v]['status']?$store_join_list[$v]['status']:0;
                $data[] = $arr;
            }
            //D('Sub_card')->delete_mer_join($_POST['sub_card_id'],$this->merchant_session['mer_id']);
            foreach ($data as $item) {
                $where['mer_id']= $item['mer_id'];
                $where['store_id']= $item['store_id'];
                $where['sub_card_id']= $_POST['sub_card_id'];
                if(M('Sub_card_mer_apply')->where($where)->find()){
                   $result  =  M('Sub_card_mer_apply')->where($where)->save($item);
                }else{
                    D('Sub_card')->sub_card_change_num($_POST['sub_card_id'],'store_join_num');
                    $result  =  M('Sub_card_mer_apply')->add($item);
                }
                if(!$result){
                    $this->error('保存店铺【'.$_POST['store_name'][$item['store_id']].'】的数据时发生错误');
                }
            }

            $mer_count = M('Sub_card_mer_apply')->where(array('sub_card_id'=>$_POST['sub_card_id']))->group('mer_id')->select();
            D('Sub_card')->where(array('id'=>$_POST['sub_card_id']))->setField('mer_join_num',count($mer_count));
            if($result){
                $this->success('保存成功');
            }

        }else{
            $store_list = D('Merchant_store')->get_storelist_by_merId($this->merchant_session['mer_id']);
            $store_join_list =  D('Sub_card')->get_sub_card_store($_GET['id'],$this->merchant_session['mer_id']);
            $this->assign('store_list',$store_list);
            $this->assign('store_join_list',$store_join_list);
            $this->display();
        }
    }

    public function my_join(){
        $where['sub_card_id'] = $_GET['id'] ;
        $where['mer_id'] = $this->merchant_session['mer_id'] ;
        $sub_card = M('Sub_card')->where(array('id'=>$_GET['id']))->find();

        import('@.ORG.merchant_page');
        $count = M('Sub_card_mer_apply')->where($where)->count();
        $p = new Page($count, 20);
        $where['ms.mer_id']  = $where['mer_id'];
        unset($where['mer_id']);
        $join_list = M('Sub_card_mer_apply')->field('ms.*,s.name')->join('AS ms LEFT JOIN '.C('DB_PREFIX').'merchant_store AS s ON ms.store_id = s.store_id')->where($where)->limit($p->firstRow,$p->listRows)->select();

        $this->assign('join_list',$join_list);
        $this->assign('package_name',$sub_card['name']);
        $this->display();
    }

    public function edit_desc(){
        $this->display();
    }

    public function del_join_store(){
//        $where['id']= $_GET['id'];
        if( D('Sub_card')->delete_mer_join($_GET['sub_card_id'],$this->merchant_session['mer_id'],$_GET['store_id'])){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    public function edit_store_join(){
        if(IS_POST){
            if(!is_numeric($_POST['sku'])||$_POST['sku']<=0){
                $this->error('库存数据不正确，应填写大于0的整数');
            }
            if(M('Sub_card_mer_apply')->save($_POST)){
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }else {
            $where['id'] = $_GET['id'];
            $store_join  = M('Sub_card_mer_apply')->where($where)->find();
            $where['id'] = $store_join['sub_card_id'];
            $sub_card    = M('Sub_card')->where($where['id'])->find();
            $store = M('Merchant_store')->where(array('store_id' => $store_join['store_id']))->find();
            $this->assign('package_name', $sub_card['name']);
            $this->assign('store_name', $store['name']);
            $this->assign('store_join', $store_join);
            $this->display();
        }
    }

    public function order_list(){
        import('@.ORG.merchant_page');

        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'pass') {
                $where['pass'] = htmlspecialchars($_GET['keyword']);
            }else if ($_GET['searchtype'] == 'name') {
                $where['nickname'] = array('like','%'.htmlspecialchars($_GET['keyword']).'%');
            }else if ($_GET['searchtype'] == 'phone') {
                $where['phone'] = htmlspecialchars($_GET['keyword']);
            }
        }
        !empty($_GET['pay_type']) && $condition_where['pay_type'] =$_GET['pay_type'];
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string']= "use_time BETWEEN ".$period[0].' AND '.$period[1];

        }
        $where['status']=1;
        $where['mer_id']=$this->merchant_session['mer_id'];


        $count = M('Sub_card_user_pass')->where($where)->count();
        $p=new Page($count,15);
        $where['s.status']=1;
        unset($where['status']);
        $list = M('Sub_card_user_pass')->field('s.* ,sc.name,sc.desc,sc.price,sc.free_total_num,u.nickname,u.phone')->join('AS s LEFT JOIN '.C('DB_PREFIX').'sub_card sc ON sc.id = s.sub_card_id LEFT JOIN '.C('DB_PREFIX').'user u ON s.uid  =u.uid  ')->where($where)->order('s.use_time DESC')->limit($p->firstRow,$p->listRows)->select();

        $this->assign('order_list',$list);
        $this->assign('pagebar',$p->show());
        $this->display();
    }

    public function sub_card_mer_sale(){
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'name') {
                $where['s.name'] = htmlspecialchars($_GET['keyword']);
            }else if ($_GET['searchtype'] == 'nickname') {
                $where['nickname'] = array('like','%'.htmlspecialchars($_GET['keyword']).'%');
            }else if ($_GET['searchtype'] == 'phone') {
                $where['u.phone'] = htmlspecialchars($_GET['keyword']);
            }else if ($_GET['searchtype'] == 'store_name') {
                $where['st.name'] = array('like','%'.htmlspecialchars($_GET['keyword']).'%');
            }
        }
        $mer_id = $this->merchant_session['mer_id'];
        $where['sp.mer_id']=$mer_id;
        import('@.ORG.merchant_page');

        $count = M('Sub_card_user_pass')->join('AS sp LEFT JOIN '.C('DB_PREFIX').'sub_card s on s.id= sp.sub_card_id LEFT JOIN '.C('DB_PREFIX').'merchant_store st ON st.store_id=sp.store_id  LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = sp.uid')
            ->where($where)->group('sp.store_id,sp.fid,sp.sub_card_id,sp.uid')->select();

        $p=new Page(count($count),15);
        $res = M('Sub_card_user_pass')
            ->field('sp.fid,count(sp.id) as num ,sp.uid,sp.add_time,sp.store_id,s.name,s.desc,st.name as store_name,u.nickname,u.phone')
            ->join('AS sp LEFT JOIN '.C('DB_PREFIX').'sub_card s on s.id= sp.sub_card_id LEFT JOIN '.C('DB_PREFIX').'merchant_store st ON st.store_id=sp.store_id  LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = sp.uid')
            ->where($where)->group('sp.store_id,sp.fid,sp.sub_card_id,sp.uid')->limit($p->firstRow,$p->listRows)->order('sp.fid DESC')->select();
        $this->assign('list',$res);
        $this->assign('pagebar',$p->show());
        $this->display();
    }

    public  function order_detail(){
       $sub_order = D('Sub_card_order')->get_order_by_id($_GET['id']);
        $user = D('User')->get_user($sub_order['uid']);

        $pass_list = D('Sub_card_order')->get_store_pass($_GET['id'],$_GET['store_id']);
        $this->assign('order',$sub_order);
        $this->assign('user',$user);
        $this->assign('pass_list',$pass_list);
        $this->display();
    }

    public function sub_card_pass_list(){
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'name') {
                $where['s.name'] = htmlspecialchars($_GET['keyword']);
            }else if ($_GET['searchtype'] == 'nickname') {
                $where['nickname'] = array('like','%'.htmlspecialchars($_GET['keyword']).'%');
            }else if ($_GET['searchtype'] == 'phone') {
                $where['u.phone'] = htmlspecialchars($_GET['keyword']);
            }else if ($_GET['searchtype'] == 'store_name') {
                $where['st.name'] = array('like','%'.htmlspecialchars($_GET['keyword']).'%');
            }
        }
        $mer_id = $this->merchant_session['mer_id'];
        $where['sp.mer_id']=$mer_id;
        import('@.ORG.merchant_page');

        $count = M('Sub_card_user_pass')->join('AS sp LEFT JOIN '.C('DB_PREFIX').'sub_card s on s.id= sp.sub_card_id LEFT JOIN '.C('DB_PREFIX').'merchant_store st ON st.store_id=sp.store_id  LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = sp.uid')
            ->where($where)->count();
        $p=new Page($count,15);
        $res = M('Sub_card_user_pass')
            ->field('sp.fid,sp.pass ,sp.uid,sp.add_time,sp.status,sp.store_id,s.name,s.desc,st.name as store_name,u.nickname,u.phone')
            ->join('AS sp LEFT JOIN '.C('DB_PREFIX').'sub_card s on s.id= sp.sub_card_id LEFT JOIN '.C('DB_PREFIX').'merchant_store st ON st.store_id=sp.store_id  LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = sp.uid')
            ->where($where)->limit($p->firstRow,$p->listRows)->order('sp.fid DESC ,sp.status ASC')->select();

        $this->assign('list',$res);
        $this->assign('pagebar',$p->show());
        $this->display();
    }


    public function slider_list(){
        $where['store_id'] = $_GET['store_id'];
        $where['sub_card_id'] = $_GET['sub_card_id'];
        $slider_list = M('Sub_card_store_slider')->where($where)->select();

        $this->assign('slider_list',$slider_list);
        $this->display();
    }
    public function add_slider(){
        if(IS_POST){

            $database_slider = D('Sub_card_store_slider');
            if(empty($_POST['name'])||empty($_FILES['pic'])||empty($_POST['url'] )){
                $this->error('数据不能为空');
            }
            if($_FILES['pic']['error'] != 4){
                $image = D('Image')->handle($this->system_session['id'], 'slider');
                if (!$image['error']) {
                    $_POST = array_merge($_POST, str_replace('/upload/slider/', '', $image['url']));
                } else {
                    $this->error($image['message']);
                }
            }else{
                unset($_POST['pic']);
            }
            $_POST['url'] = htmlspecialchars_decode($_POST['url']);
            $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
//            $_POST['store_id'] = $_POST['store_id'];
//            $_POST['sub_card_id'] = $_POST['sub_card_id'];
            $_POST['last_time'] = $_SERVER['REQUEST_TIME'];


            if($id = $database_slider->data($_POST)->add()){
                D('Image')->update_table_id('/upload/slider/' . $_POST['pic'], $id, 'slider');
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！请重试~');
            }

        }else{
            if($_GET['id']){
                $slider = M('Sub_card_store_slider')->where(array('id'=>$_GET['id']))->find();
                $this->assign('slider',$slider);
            }
            $this->display();
        }
    }

    public function edit_slider(){
        if(IS_POST){

            if(empty($_POST['name'])||empty($_POST['url'] )){
                $this->error('数据不能为空');
            }
            $database_slider = D('Sub_card_store_slider');

            if($_FILES['pic']['error'] != 4){
                $image = D('Image')->handle($this->system_session['id'], 'slider');
                if (!$image['error']) {
                    $_POST = array_merge($_POST, str_replace('/upload/slider/', '', $image['url']));
                } else {
                    $this->error($image['message']);
                }
            }else{
                unset($_POST['pic']);
            }
            $_POST['url'] = htmlspecialchars_decode($_POST['url']);
            $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
//            $_POST['store_id'] = $_POST['store_id'];
//            $_POST['sub_card_id'] = $_POST['sub_card_id'];
            $_POST['last_time'] = $_SERVER['REQUEST_TIME'];


            if($id = $database_slider->where(array('id'=>$_POST['id']))->data($_POST)->save()){
                if($_POST['pic']){
                    D('Image')->update_table_id('/upload/slider/' . $_POST['pic'], $id, 'slider');
                }
                $this->success('保存成功！');
            }else{
                $this->error('保存失败！请重试~');
            }

        }else{
            if($_GET['id']){
                $slider = M('Sub_card_store_slider')->where(array('id'=>$_GET['id']))->find();
                $this->assign('slider',$slider);
            }
            $this->display();
        }
    }

    public function slider_del(){
        if(M('Sub_card_store_slider')->where(array('id'=>$_GET['id']))->delete()){
            $this->success('删除成功！');
        }else{
            $this->success('删除失败');
        }
    }

}