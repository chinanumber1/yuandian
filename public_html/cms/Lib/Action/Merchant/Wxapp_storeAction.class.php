<?php
class Wxapp_storeAction extends BaseAction{
    public function index(){

        $mer_id = $this->merchant_session['mer_id'];
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['mer_id'] = $mer_id;
        if ($this->merchant_session['store_id']) {
            $count_store = 1;
        } else {
            $count_store = $database_merchant_store->where("mer_id='{$mer_id}' AND status<>4")->count();
        }
        $db_arr = array(C('DB_PREFIX').'area'=>'a',C('DB_PREFIX').'merchant_store'=>'s');
        import('@.ORG.merchant_page');
        $p = new Page($count_store,15);
        if ($this->merchant_session['store_id']) {
            $store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`area_id`=`a`.`area_id` AND s.status!=4 AND s.store_id={$this->merchant_session['store_id']}")->select();
        } else {
            $store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`area_id`=`a`.`area_id` AND s.status!=4")->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
        }
        foreach($store_list as $v){

        }
        $this->assign('store_list',$store_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);

        $this->display();

    }

//    添加门店
    public function add_store_in_map(){
        import('@.ORG.Wxapp_store');
        $store = new Wxapp_store();
        $area_list = $store->get_wx_city_list();
        $category_list = $store->get_category();
        $now_store = M('Merchant_store')->where(array('store_id'=>$_GET['store_id']))->find();
        $now_store['wxapp_map_param'] = unserialize($now_store['wxapp_map_param']);
        $now_store['wxapp_map_param']['category'] = explode(':',$now_store['wxapp_map_param']['category'] );
        $now_store['phone'] = str_replace(' ',';',$now_store['phone']);
        $this->assign('now_store',$now_store);

        if(IS_POST){

            $arr["name"]= $_POST['name'];
            $arr["longitude"]= "113.323753357";
            $arr["latitude"]= "23.0974903107";
            if( $_POST['district_name']){

                $arr["province"]=  $_POST['province_name'];
                $arr["city"]=  $_POST['city_name'];
                $arr["district"]=  $_POST['district_name'];
            }else{
                $arr["city"]=  $_POST['province_name'];
                $arr["district"]=  $_POST['city_name'];

            }
            $arr["address"]= $_POST['address'];
            $arr["category"]= $_POST['first_catid_name'].':'.$_POST['second_catid_name'];
            $arr["telephone"]= $_POST['phone'];
            $arr["photo"]= $_POST['photo'];
            $arr["license"]= $_POST['license'];
            $arr["introduct"]= $_POST['introduct'];
            if($_POST['district']){
                $arr["districtid"]= $_POST['district'];
            }else if($_POST['city_id']){
                $arr["districtid"]= $_POST['city_id'];
            }

            $res = $store->create_map_poi($arr);
            if(empty($res['error'])){
                $data['wxapp_map_param'] = serialize($arr);
                $data['base_id'] = $res['data']['base_id'];
                $data['wxapp_map_status'] = 1; // 0 未申请 1 待审核 2 审核成功
                M('Merchant_store')->where(array('store_id'=>$_GET['store_id']))->save($data);
                $this->success('添加成功');
            }else{
                $this->success($res['errmsg']);
            }
        }else{
            $this->assign("area_list",json_encode($area_list));
            $this->assign("category_list",json_encode($category_list));
            $this->display();
        }
    }

    public function add_store(){
        $now_store = M('Merchant_store')->where(array('store_id'=>$_GET['store_id']))->find();
        $now_merchant = D('Merchant')->get_info($this->merchant_session['mer_id']);
        $now_store['wxapp_param'] = unserialize($now_store['wxapp_param']);

        $this->assign('now_store',$now_store);
        if(IS_POST){

            import('@.ORG.Wxapp_store');
            $store = new Wxapp_store();
            $arr["map_poi_id"] = $_POST['map_poi_id'];
            $pic_list = explode(';',$_POST['photo']);
            foreach ($pic_list as $v) {
                if(!empty($v)){
                    $pic_list_tmp[] = $v;
                }
            }
            $arr["pic_list"] = json_encode(array('list'=>$pic_list_tmp));
            $arr["contract_phone"] = $_POST['contract_phone'];
            $arr["credential"] = $_POST['credential'];
            $arr["qualification_list"] = $_POST['qualification_list'];
            $arr["hour"] = $_POST['hour_start'].'-'.$_POST['hour_end'];
            $arr["company_name"] = $_POST['company_name'];
            $now_merchant['wx_cardid'] &&  $arr["card_id"] = $now_merchant['wx_cardid'];
            $res = $store->create_wx_store($arr);
            if(!$res['errcode']){
                $data['wxapp_param'] = serialize($arr);
                $data['audit_id'] = $res['data']['audit_id'];
                $data['wxapp_status'] = 1; // 0 未申请 1 待审核 2 审核成功
                M('Merchant_store')->where(array('store_id'=>$_GET['store_id']))->save($data);
            }
        }else{
            import('@.ORG.Wxapp_store');
            $store = new Wxapp_store();
            $area_list = $store->get_wx_city_list();
            $this->assign("area_list",json_encode($area_list));
            $this->display();
        }

    }

    public function store_search(){
        import('@.ORG.Wxapp_store');
        $store = new Wxapp_store();
        $res = $store->get_wx_area_search($_POST['districtid'],$_POST['store_name']);
        if($res['errcode']==0){

            echo json_encode(array('store'=>$res['data']['item']));

        }else{

            echo json_encode(array('store'=>array()));
        }
    }

}