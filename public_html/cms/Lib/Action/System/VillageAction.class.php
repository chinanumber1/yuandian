<?php

class VillageAction extends BaseAction
{
    public $village_table_name = 'FcVillage';
    
    public $village_school_table_name = 'FcVillageSchool';
    
    public $village_metro_table_name = 'FcVillageMetro';
    
    public $village_bus_table_name = 'FcVillageBus';

    public $metro_line_station_table_name = 'FcMetroLineStation';

    public $bus_line_station_table_name = 'FcBusLineStation';

    public function index()
    {
        
        $villages = D($this->village_table_name)->all();
        
        foreach($villages as $key => $village) {
            $villages[$key]['city'] = D('Area')->name($village['city_id']);
        }
        
        $this->assign('villages', $villages);
        $this->display();
    }
    
    
    public function create()
    {
        $this->display();
    }
    
    public function store()
    {
        list($long, $lat) = explode(',', $_POST['long_lat']);
        
        $data['name'] = $_POST['name'];
        $data['first_word'] = strtoupper($_POST['first_word']);
        $data['address'] = $_POST['address'];
        $data['city_id'] = $_POST['city_id'];
        $data['province_id'] = $_POST['province_id'];
        $data['area_id'] = $_POST['area_id'];
        $data['circle_id'] = $_POST['circle_id'];
        $data['administrator'] = $_POST['administrator'];
        $data['administrator_tel'] = $_POST['administrator_tel'];
        $data['long'] = $long;
        $data['lat'] = $lat;

        $data['property_price'] = $_POST['property_price'];
        $data['property_type'] = $_POST['property_type'];
        $data['building_age'] = $_POST['building_age'];
        $data['average_price'] = $_POST['average_price'];
        $data['developers'] = $_POST['developers'];
        $data['list_img'] = $_POST['list_img'];
        

        $result = D($this->village_table_name)->add($data);
       
        if($result) {
            $this->villageMetro( $_POST['station'], $result);
            $this->villageBus( $_POST['bus'], $result);
            $this->villageSchool( $_POST['school'], $result);
            $this->frame_submit_tips(1,'添加成功！');
        } else {
            $this->frame_submit_tips(0,'添加失败！请重试~');
        }
    }
    
    public function edit()
    {
        $id = intval($_GET['id']);
        $info = D($this->village_table_name)->info( $id );

        $metro_stations = D()->table(array(C('DB_PREFIX').'fc_village_metro'=>'vm', C('DB_PREFIX').'fc_metro_station'=>'ms'))
                            ->field('distinct(vm.metro_station_id),ms.*')
                            ->where("vm.village_id= '".$id."' AND vm.metro_station_id = ms.id")
                            ->select();


        $bus_stations = D()->table(array(C('DB_PREFIX').'fc_village_bus'=>'vb', C('DB_PREFIX').'fc_bus_station'=>'bs'))
                            ->field('distinct(vb.bus_station_id),bs.*')
                            ->where("vb.village_id= '".$id."' AND vb.bus_station_id = bs.station_id")
                            ->select();

        $schools = D()->table(array(C('DB_PREFIX').'fc_village_school'=>'vs', C('DB_PREFIX').'fc_school'=>'s'))
                            ->where("vs.village_id= '".$id."' AND vs.school_id = s.school_id")
                            ->select();

        $this->assign(array(
            'metro_stations' => $metro_stations,
            'bus_stations' => $bus_stations,
            'schools' => $schools,
            'info' => $info
        ));
        
        $this->display();
    }
    
    public function update()
    {
        $id = intval($_POST['id']);
        list($long, $lat) = explode(',', $_POST['long_lat']);
        
        $data['name'] = $_POST['name'];
        $data['first_word'] = strtoupper($_POST['first_word']);
        $data['address'] = $_POST['address'];
        $data['city_id'] = $_POST['city_id'];
        $data['province_id'] = $_POST['province_id'];
        $data['area_id'] = $_POST['area_id'];
        $data['circle_id'] = $_POST['circle_id'];
        $data['administrator'] = $_POST['administrator'];
        $data['administrator_tel'] = $_POST['administrator_tel'];
        $data['long'] = $long;
        $data['lat'] = $lat;

        $data['property_price'] = $_POST['property_price'];
        $data['property_type'] = $_POST['property_type'];
        $data['building_age'] = $_POST['building_age'];
        $data['average_price'] = $_POST['average_price'];
        $data['developers'] = $_POST['developers'];
        $data['list_img'] = $_POST['list_img'];

        $result = D($this->village_table_name)->where( array('village_id' => $id) )->save($data);
    
        $this->villageMetro( $_POST['station'], $id);
        $this->villageBus( $_POST['bus'], $id);
        $this->villageSchool( $_POST['school'], $id);

        return $this->frame_submit_tips(1,'更新成功！');
    }
    
    protected function villageMetro( $data, $village_id )
    {
        
        D($this->village_metro_table_name)->destroyByVillageId( $village_id );
        
        foreach ($data as $k => $v) {
            if($v['stationId']){

                $line_ids = D($this->metro_line_station_table_name)->line_ids( $v['stationId'] );
                if(! empty($line_ids)) {
                    foreach ($line_ids as $key => $line_id ) {
                        D($this->village_metro_table_name)->data(array('village_id'=>$village_id,'metro_station_id'=>$v['stationId'], 'metro_line_id' => $line_id))->add();
                    }
                }


            }
        }
    }

    protected function villageBus( $data, $village_id)
    {
        
        D($this->village_bus_table_name)->destroyByVillageId( $village_id );
        
        foreach ($data as $k => $v) {
            if($v['stationId']){

                $line_ids = D($this->bus_line_station_table_name)->line_ids( $v['stationId'] );
                if(! empty($line_ids)) {
                    foreach ($line_ids as $key => $line_id ) {
                        D($this->village_bus_table_name)->data(array('village_id'=>$village_id,'bus_station_id'=>$v['stationId'], 'bus_line_id' => $line_id))->add();
                    }
                }

            }
        }
    }
    
    protected function villageSchool( $data, $village_id)
    {
        
        D($this->village_school_table_name)->destroyByVillageId( $village_id );
        
    
        foreach ($data as $k => $v) {
            if($v['stationId']){
                D($this->village_school_table_name)->data(array('village_id'=>$village_id,'school_id'=>$v['stationId']))->add();
            }
        }
    }
    
    public function destroy()
    {
        if( D($this->village_table_name)->destroy($_POST['id']) ) {
    
            $this->success('删除成功！');
        } else {
    
            $this->error('删除失败！请重试~');
        }
    
    }



    public function img_list(){
        import('@.ORG.fc.Options');
        $this->assign('img_type',Options::get('village_img_type'));

        $imgList = D('Fc_village_img')->where(array('village_id'=>intval($_GET['village_id'])))->select();
        $this->assign('imgList',$imgList);

        $this->display();
    }

    public function img_add(){
        import('@.ORG.fc.Options');
        $this->assign('img_type',Options::get('village_img_type'));
        $this->display();
    }

    public function img_add_data(){
        $data['title'] = $_POST['title'];
        $data['sort'] = $_POST['sort'];
        $data['img_type'] = $_POST['img_type'];
        $data['url'] = $_POST['url'];
        $data['village_id'] = $_POST['village_id'];

        $ret = D('Fc_village_img')->data($data)->add();
        if($ret){
            $this->frame_submit_tips(1,'添加成功！');
        } else {
            $this->frame_submit_tips(0,'添加失败！请重试~');
        }
    }

    public function img_save(){
        import('@.ORG.fc.Options');
        $this->assign('img_type',Options::get('village_img_type'));
        $imgInfo = D('Fc_village_img')->where(array('img_id'=>intval($_GET['img_id'])))->find();
        $this->assign('imgInfo',$imgInfo);
        $this->display();
    }

    public function img_save_data(){
        $data['img_id'] = $_POST['img_id'];
        $data['title'] = $_POST['title'];
        $data['sort'] = $_POST['sort'];
        $data['img_type'] = $_POST['img_type'];
        $data['url'] = $_POST['url'];

        $ret = D('Fc_village_img')->where(array('img_id'=>$data['img_id']))->data($data)->save();
        if($ret){
            $this->frame_submit_tips(1,'修改成功！');
        } else {
            $this->frame_submit_tips(0,'修改失败！请重试~');
        }
    }


    public function img_del(){
        if( D('Fc_village_img')->where(array('img_id'=>$_POST['id']))->delete() ) {
    
            $this->success('删除成功！');
        } else {
    
            $this->error('删除失败！请重试~');
        }
    }

    public function info_edit(){
        $info = D('Fc_village_info')->where(array('village_id'=>intval($_GET['id'])))->find();
        if($info){
            $this->assign('info',$info);
        }else{
            $info['village_id'] = $_GET['id'];
            $this->assign('info',$info);
        }
        
        $this->display();
    }

    public function info_edit_data(){

        $data['details'] = $_POST['details'];
        $data['facilities'] = $_POST['facilities'];
        $data['synopsis'] = $_POST['synopsis'];
        $data['traffic'] = $_POST['traffic'];
        $data['ambient'] = $_POST['ambient'];
        $data['village_id'] = $_POST['village_id'];

        if($_POST['info_id']){
            $ret = D('Fc_village_info')->where(array('info_id'=>intval($_POST['info_id'])))->data($data)->save();
        }else{
            $ret = D('Fc_village_info')->data($data)->add();
        }

        if($ret){
            $this->frame_submit_tips(1,'修改成功！');
        } else {
            $this->frame_submit_tips(0,'修改失败！请重试~');
        }

    }



    // 上传图片
    public function ajax_upload_pic(){
        if ($_FILES['imgFile']['error'] != 4) {
            $upload_dir = './upload/fc/villige/'.date('Ymd').'/'.date('H').'/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = $this->config['group_pic_size'] * 1024 * 1024;
            $upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
            $upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif','application/octet-stream');
            $upload->savePath = $upload_dir;
            $upload->saveRule = 'uniqid';
            if ($upload->upload()) {
                $uploadList = $upload->getUploadFileInfo();
                $title = $uploadList[0]['savename'];
                $url = $upload_dir.$title;
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            } else {
                exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
            }
        } else {
            exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
        }
    }
    
    // 历史价格
    public function history_price(){
        $village_id = (int)$_GET['village_id'];
        $village_name = trim($_GET['village_name']);
        $count = D('Fc_history_price_village')->where(array('village_id'=>$village_id))->count();
        import('@.ORG.system_page');
        $p = new Page($count_price,10);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        
        $history_price_list = D('Fc_history_price_village')->where(array('village_id'=>$village_id))->order('dateline desc')->limit($p->firstRow.','.$p->listRows)->select();
        // 城市、小区名称
        $tmp_areas = D('Area')->field('area_id,area_name')->where(array('area_type'=>2))->select();
        $areas = array();
        foreach($tmp_areas as $tmp){
            $areas[$tmp['area_id']] = $tmp;
        }

        $this->assign('village_id',$village_id);
        $this->assign('village_name',$village_name);
        $this->assign('history_price_list',$history_price_list);
        $this->assign('areas',$areas);
        $this->display();
    }

    // 添加历史价格
    public function add_history_price(){

        $village_id = (int)$_GET['village_id'];
        $village_name = trim($_GET['village_name']);
        $this->assign('village_id',$village_id);
        $this->assign('village_name',$village_name);
        $this->display();
    }

    // 保存历史价格
    public function save_history_price(){
        $data['village_id'] = (int)$_POST['village_id'];
        $data['dateline'] = strtotime($_POST['dateline']);
        $data['price'] = (int)$_POST['price'];
        $data['rent_price'] = (int)$_POST['rent_price'];

        $area_info = D('Fc_village')->field('province_id,city_id,area_id')->where(array('village_id'=>$data['village_id']))->find();
        if(!$area_info){
            $this->error('没有找到该小区');
            exit;
        }

        $data['province_id'] = $area_info['province_id'];
        $data['city_id'] = $area_info['city_id'];
        $data['area_id'] = $area_info['area_id'];

        $res = D('Fc_history_price_village')->data($data)->add();
        if(!$res){
            $this->error('保存失败');
        }
        $this->success('保存成功');
    }

    // 删除历史价格
    public function del_price(){
        $id = (int)$_POST['id'];
        $res = D('Fc_history_price_village')->where(array('id'=>$id))->delete();
        if(!$res){
             $this->error('删除失败');
        }
         $this->success('删除成功');
    }
    
}