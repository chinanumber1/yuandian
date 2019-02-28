<?php
class LibraryAction extends BaseAction{
    protected $village_id;

    public function _initialize(){
        parent::_initialize();
        $this->village_id = $this->house_session['village_id'];
    }

    public function express_service_list(){
        //快递代收-查看 权限
        if (!in_array(207, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        $database_house_village_express = D('House_village_express');

        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');
        $this->assign('has_express_service',$has_express_service);

        if($has_express_service){
            $where['village_id'] = $village_id;
            $list = $database_house_village_express->express_service_page_list($where);
            if(!$list){
                $this->error('处理数据有误！');
            }else{
                $this->assign('list',$list['list']);
            }
        }

        $this->display();
    }


    public function fetch_code(){
        $village_id = $this->house_session['village_id'];
        $this->assign('village_id',$village_id);
        $this->display();
    }

    public function express_analysis(){
        //快递统计 权限
        if (!in_array(212, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        $database_house_village_express = D('House_village_express');
        $_GET['express_id'] && $where['express_type'] = $_GET['express_id'];
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));

            $where['_string']= "(ep.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');
        $this->assign('has_express_service',$has_express_service);
        $express_list = D('Express')->get_express_list();
        $this->assign('express_list',$express_list);
        if($has_express_service){
            $where['village_id'] = $village_id;
            $list = $database_house_village_express->express_service_page_list($where);
            if($_GET['export']){
                $this->express_export($where);exit;
            }
            if(!$list){
                $this->error('处理数据有误！');
            }else{
                $this->assign('list',$list['list']);
            }
        }
        $this->display();
    }

    public function express_export($where){
        //快递导出 权限
        if (!in_array(213, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = "快递订单记录";
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);



        $database_express = D('Express');
        $database_house_village_user_bind = D('House_village_user_bind');

        $where['ep.village_id'] = $where['village_id'];
        unset($where['village_id']);
        $count = M('House_village_express')->join('as ep LEFT JOIN '.C('DB_PREFIX').'house_village_floor as f ON ep.floor_id = f.floor_id ')->where($where)->count();
        $order = 'id desc';




        $express_where['status'] = 1;
        $express_info = $database_express->where($express_where)->getField('id,name');
        $length = ceil($count / 1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            $objExcel->getActiveSheet()->setTitle('第' . ($i + 1) . '个一千个订单信息');
            $objActSheet = $objExcel->getActiveSheet();
            $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

            $objActSheet->setCellValue('A1', '编号');
            $objActSheet->setCellValue('B1', '快递类型');
            $objActSheet->setCellValue('C1', '快递单号');
            $objActSheet->setCellValue('D1', '收件人手机号');
            $objActSheet->setCellValue('E1', '收件人地址');
            $objActSheet->setCellValue('F1', '送件费用');
            $objActSheet->setCellValue('G1', '状态');
            $objActSheet->setCellValue('H1', '预约代送时间');
            $objActSheet->setCellValue('I1', '添加时间');


            $village_express_list = M('House_village_express')->join('as ep LEFT JOIN '.C('DB_PREFIX').'house_village_floor as f ON ep.floor_id = f.floor_id LEFT JOIN '.C('DB_PREFIX').'house_village_express_order o ON o.express_id = ep.id')->where($where)->field('ep.*,f.floor_name,o.send_time')->order($order)->limit($i * 1000,1000)->select();



            if (!empty($village_express_list)) {
                $index = 1;
                foreach ($village_express_list as $value) {
                    $var_address = "";
                    $all_address = D('House_village_user_bind')->field('address')->where(array('village_id'=>$value['village_id'],'phone'=>$value['phone'],'floor_id'=>$value['floor_id'],'status'=>1))->select();
                    foreach($all_address as $m=>$n){
                        $var_address .= $n['address']."  ";
                    }
                    $value['address'] = $var_address;
                    $value['express_name'] = $express_info[$value['express_type']];
                    $index++;
                    $objActSheet->setCellValueExplicit('A' . $index, $value['id']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['express_name']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['express_no']);
                    $objActSheet->setCellValueExplicit('D' . $index, $value['phone']);
                    if($value['address']!=''&& $value['floor_name'] !=''){
                        $objActSheet->setCellValueExplicit('E' . $index, $value['address']);
                    }else{
                        $objActSheet->setCellValueExplicit('E' . $index, $value['floor_name']);
                    }

                    $objActSheet->setCellValueExplicit('F' . $index,$value['money'] );

                    if($value['status']==0){
                        $status_txt = '未取件';
                    }else if($value['status']==1){
                        $status_txt = '已取件（已取件（业主））';
                    }else{
                        $status_txt = '已取件（社区）';
                    }
                    $objActSheet->setCellValueExplicit('G' . $index, $status_txt);
                    $objActSheet->setCellValueExplicit('H' . $index, $value['send_time'] ? date('Y-m-d H:i:s', $value['send_time']) : '');
                    $objActSheet->setCellValueExplicit('I' . $index, $value['add_time'] ? date('Y-m-d H:i:s', $value['add_time']) : '');
                }
            }
            sleep(2);

        }


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

    public function express_add(){
        //快递-添加 权限
        if (!in_array(209, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');
        if($has_express_service){
            $database_house_village_express = D('House_village_express');
            $database_express = D('Express');

            if(IS_POST){
				$data = $_POST;
                $expressInfo = D('Express')->where(array('id'=>$data['express_type']))->find();

                if($expressInfo['kuaidiniao_code'] == 'YD'){//韵达快递
                    $res = $this->yunda($_POST['express_no'],date("Y-m-d H:i:s",time()));
                    if($res['success'] == true && $res['responseItems']['response']['status'] == true){

                    }
                }elseif ($expressInfo['kuaidiniao_code'] == 'HTKY') {//百世汇通
                    // echo '百世汇通';
                }elseif ($expressInfo['kuaidiniao_code'] == 'YTO') {//圆通快递
                    // echo '圆通快递';
                }

                $data['fetch_code'] = rand(10000,99999);
				$data['add_time'] = time();
				$data['village_id'] = $_SESSION['house']['village_id'];
                $result = $database_house_village_express->village_express_add($data);
                if(!$result){
                    $this->error('数据处理有误！');
                }else{
                    if($result['status']){
                        
                        $this->success($result['msg']);
                    }else{
                        $this->error($result['msg']);
                    }
                }
            }else{
                $express_list = $database_express->get_express_list();
                $express_config = M('House_village_express_config')->where(array('village_id'=>$village_id))->find();
                $express_money_status = $express_config['status'];
                $this->assign('express_config',$express_config);
                $this->assign('express_list',$express_list);
                $this->assign('express_money_status',$express_money_status);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }


    public function yunda($waybill,$time){
        $url = 'http://116.228.72.130:15137/cmn-web/getway/arrival_scan_info.do';
        $partnerid = "201101123456";
        $password = "123456";
        $appkey= "YUNDA123456";
        $xmldatatmp = '<?xml version="1.0" encoding="UTF-8"?><request><company>200230</company><scanList><scan><waybill>'.$waybill.'</waybill><scanTime>'.$time.'</scanTime></scan></scanList></request>'; 
        $xmldata =  base64_encode($xmldatatmp);
        $validation = md5($xmldata.$partnerid.$password.$appkey);
        $data = 'partnerid='.urlencode($partnerid).'&appkey='.urlencode($appkey).'&version=1.0&xmldata='.urlencode($xmldata).'&validation='.urlencode($validation);
        $result = $this->vpost($url,$data);
        //xml 转换成为数组
        $xml = simplexml_load_string($result);
        $arr = json_decode(json_encode($xml),TRUE);
        return $arr; // 返回数据
    }


    public function yundaSign($waybill,$status,$name,$time){
        $url = 'http://116.228.72.130:15137/cmn-web/getway/sign_scan_info.do';
        $partnerid = "201101123456";
        $password = "123456";
        $appkey= "YUNDA123456";
        $xmldatatmp = '<?xml version="1.0" encoding="UTF-8"?><request><company>200230</company><scanList><scan><waybill>'.$waybill.'</waybill><signStatus>'.$status.'</signStatus><signName>'.$name.'</signName><scanTime>'.$time.'</scanTime></scan></scanList></request>';
        $xmldata =  base64_encode($xmldatatmp);
        $validation = md5($xmldata.$partnerid.$password.$appkey);
        $data = 'partnerid='.urlencode($partnerid).'&appkey='.urlencode($appkey).'&version=1.0&xmldata='.urlencode($xmldata).'&validation='.urlencode($validation);
        $result = $this->vpost($url,$data);
        
        //xml 转换成为数组
        $xml = simplexml_load_string($result);
        $arr = json_decode(json_encode($xml),TRUE);
        return $arr; // 返回数据
    }



    public function yto(){
        $logistics_interface = '<request>
                    <waybillNo>1234567890</waybillNo>
                    <opCode>1310</opCode>
                    <opUserId>00011021</opUserId>
                    <opTime>2017-10-1815:48:53</opTime>
                    <weight>12.00</weight>
                    <frequencyNo>Z001</frequencyNo>
                    <logisticsCode>BLC0000001</logisticsCode>
                    <stationCode>210045000001</stationCode>
                    <orgCode>210045</orgCode>
                    <customerCode>KC000000001</customerCode>
                    <reserve1>reserve</reserve1>
                    <reserve2>reserve</reserve2>
                    <reserve3>reserve</reserve3>
                </request>';
        $data_digest = '';
        $costomer_code = '';
        $op_code = '';
        $station_type = '';
        $org_code = '';

    }

    public function ytoSign(){
 
    }

    public function baishi(){
        $url = "http://183.129.172.49/wuye/api/process";
        $partnerID = 'imxiaomai';
        $partnerKey = '123456';
        $express_no = '50004057408227';
        $serviceType = 'UpdateStatusByMailNo';
        $time = date("Y-m-d H:i:s",time()); 
        $datas['serviceProviderCode'] = $partnerID; //服务提供商代码
        $datas['requestTime'] = $time; //状态推送时间
        $datas['operator'] = 'BESTEXP'; //快递公司代码
        $datas['transactionCode'] = 'ba7816bf8f01cfea414140de5dae2223b00361a396177a9cb410ff61f20015ad'; //交易号
        $datas['billCode'] = $express_no;
        $datas['status'] = 'S01';
        $datas['remark'] = '快递员已投递包裹至快递柜';
        $datas['serviceSiteCode'] = '330106000001'; //服务点编号
        $bizData = json_encode($datas,JSON_UNESCAPED_UNICODE);
        $signString = base64_encode(md5($bizData.$partnerKey,32));
        $paramArray = 'serviceType='.$serviceType.'&partnerID='.$partnerID.'&digest='.$signString.'&bizData='.urlencode($bizData);
        $result = $this->vpost($url,$paramArray);
        dump(json_decode($result,true));
    }

    public function baishiSign(){
        $url = "http://183.129.172.49/wuye/api/process";
        $partnerID = 'imxiaomai';
        $partnerKey = '123456';
        $express_no = '50004057408227';
        $serviceType = 'UpdateStatusByMailNo';
        $time = date("Y-m-d H:i:s",time()); 
        $datas['serviceProviderCode'] = $partnerID; //服务提供商代码
        $datas['requestTime'] = $time; //状态推送时间
        $datas['operator'] = 'BESTEXP'; //快递公司代码
        $datas['transactionCode'] = 'ba7816bf8f01cfea414140de5dae2223b00361a396177a9cb410ff61f20015ad'; //交易号
        $datas['billCode'] = $express_no;
        $datas['status'] = 'S02';
        $datas['remark'] = '收件人已取货';
        $datas['serviceSiteCode'] = '330106000001'; //服务点编号
        $bizData = json_encode($datas,JSON_UNESCAPED_UNICODE);
        $signString = base64_encode(md5($bizData.$partnerKey,16));
        $paramArray = 'serviceType='.$serviceType.'&partnerID='.$partnerID.'&digest='.$signString.'&bizData='.urlencode($bizData);
        $result = $this->vpost($url,$paramArray);
        dump(json_decode($result,true));
    }


    public function vpost($url,$data){ // 模拟提交数据函数、
        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
           echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }


    //把数组转换成为xml数据
    public function arrayToXml($arr){ 
        $xml = "<responses>"; 
        foreach ($arr as $key=>$val){ 
            if(is_array($val)){ 
                $xml.="<".$key.">".arrayToXml($val)."</".$key.">"; 
            }else{ 
                $xml.="<".$key.">".$val."</".$key.">"; 
            } 
        } 
        $xml.="</responses>"; 
        return $xml; 
    }


    public function ajax_check_express_no(){
        header("Content-Type: text/html;charset=utf-8"); 
        $logisticResult = $this->getOrderTracesByJson($_POST['express_no']);
        $Result = json_decode ( $logisticResult, JSON_FORCE_OBJECT );
// dump($Result);
        $express_info = D('Express')->where(array('kuaidiniao_code'=>$Result['Shippers'][0]['ShipperCode']))->find();
        // dump($express_info);
        if($express_info){
            exit(json_encode($express_info));
        }else{
            exit(json_encode(array('error'=>1)));
        }
        
    }

    /**
     * Json方式 单号识别tracking number
     */
    function getOrderTracesByJson($kuaidihao){
        $requestData= "{'LogisticCode':'".$kuaidihao."'}";
        $datas = array(
            'EBusinessID' => '1305647',
            'RequestType' => '2002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, '6bfe8732-a74b-4589-a469-5e0bc633f14a');
        $result=$this->sendPost('http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx', $datas);   
        //根据公司业务处理返回的信息......
        return $result;
    }
     
    /**
     *  post提交数据 
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据 
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();   
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);      
        }   
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;   
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);  
        
        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容   
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }





    public function ajax_get_unit(){
		if(IS_AJAX){
			$village_id = $this->house_session['village_id'];
			$phone = $_POST['phone'];
			$floor_list = M('House_village_user_bind')->field('u.address,f.floor_id,f.floor_name')->join('as u LEFT JOIN '.C('DB_PREFIX').'house_village_floor f ON u.floor_id = f.floor_id')->where(array('u.phone'=>$phone,'u.status'=>1,'u.village_id'=>$village_id))->select();
			if(!empty($floor_list)){
	
				echo json_encode(array('status'=>1,'floor_list'=>$floor_list));exit;
			}else{
				echo json_encode(array('status'=>0,'floor_list'=>$floor_list));exit;
			}
		}
    }

    public function express_edit(){
        $database_house_village_express = D('House_village_express');
        if(IS_POST){
            //快递-取件 权限
            if (!in_array(210, $this->house_session['menus'])) {
               exit(json_encode(array('status'=>0,'msg'=>'对不起，您没有权限执行此操作')));
            }

            $id = $_POST['id'] + 0;
            $status = $_POST['status'] + 0;
            $fetch_code = intval($_POST['fetch_code']);
            if(!$id || !$status || !$fetch_code){
                exit(json_encode(array('status'=>0,'msg'=>'参数传递有误')));
            }

            $info = $database_house_village_express->where(array('id'=>$id,'fetch_code'=>$_POST['fetch_code']))->find();

            if(!is_array($info)){
                exit(json_encode(array('status'=>0,'msg'=>'请输入正确的取件码')));
            }
            
            $express_info = D('Express')->where(array('id'=>$info['express_type']))->find();

            if($express_info['kuaidiniao_code'] == 'YD'){//韵达快递
                $res = $this->yundaSign($info['express_no'],1,'本人',date("Y-m-d H:i:s",time()));
                if($res['success'] == true && $res['responseItems']['response']['status'] == true){

                }
            }elseif ($express_info['kuaidiniao_code'] == 'HTKY') {//百世汇通
                // echo '百世汇通';
            }elseif ($express_info['kuaidiniao_code'] == 'YTO') {//圆通快递
                // echo '圆通快递';
            }

            $where['id'] = $order_where['express_id'] = $id;
            $data['status'] = $status;

            $database_house_village_express_order = D('House_village_express_order');
            $database_house_village_express_order->house_village_express_order_edit($order_where,$data);

            $data['delivery_time'] = time();

            $result = $database_house_village_express->house_village_express_edit($where,$data);
            if(!$result){
                exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
            }else{




                exit(json_encode(array('status'=>$result['status'],'msg'=>$result['msg'])));
            }
        }
    }

    public function express_del(){
        //快递-删除 权限
        if (!in_array(211, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_express = D('House_village_express');
        $where['id'] = $id;
        $result = $database_house_village_express->village_express_del($where);
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }


    public function express_detail(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_express = D('House_village_express');
        $where['id'] = $id;
        $detail = $database_house_village_express->house_village_express_detail($where);
        if(!$detail){
            $this->error('数据处理有误！');
        }else{
            $this->assign('detail',$detail['detail']);
        }
        $this->display();
    }

    public function express_search(){
        $village_id = $this->house_session['village_id'];
        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');

        if($has_express_service){
            $database_house_village_express = D('House_village_express');
            $database_express = D('Express');
            if(IS_POST){
                $keyword = $_POST['keyword'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];

                if($keyword){
                    $where['phone|express_no'] = array('like','%'.$keyword.'%');
                }

                if($start_time && $end_time){
                    $start_time = strtotime($start_time);
                    $end_time = strtotime($end_time.'23:59:59');
                    $where['add_time'] = array('between',array($start_time,$end_time));
                }else if($start_time){
                    $start_time = strtotime($start_time);
                    $where['add_time'] = array('egt',$start_time);
                }else if($end_time){
                    $end_time = strtotime($end_time.'23:59:59');
                    $where['add_time'] = array('lt',$end_time);
                }
                $result = $database_house_village_express->ajax_vllage_express_search($where);
                //删除权限
                $result['power'] = in_array(211, $this->house_session['menus']) ? true : false;
                exit(json_encode($result));
            }else{
                $express_list = $database_express->get_express_list();
                $this->assign('express_list',$express_list);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }

    public function visitor_list(){
        //访客登记-查看 权限
        if (!in_array(216, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_visitor = D('House_village_visitor');
        $database_house_village = D('House_village');

        $village_id =  $this->house_session['village_id'];
        $has_visitor = $this->getHasConfig($village_id, 'has_visitor');
        $this->assign('has_visitor',$has_visitor);
        if($has_visitor){
            $where['village_id'] = $village_id;
            $list = $database_house_village_visitor->house_village_visitor_page_list($where);
            if(!$list){
                $this->error('数据处理有误！');
            }else{
                $this->assign('list',$list['list']);
                $this->assign('visitor_type',$database_house_village_visitor->visitor_type);
            }
        }
        $this->display();
    }

    public function visitor_add(){
        //访客登记-添加 权限
        if (!in_array(243, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        $has_visitor = $this->getHasConfig($village_id, 'has_visitor');
        if($has_visitor){
            $database_house_village_visitor = D('House_village_visitor');
            if(IS_POST){
                $_POST['village_id'] = $village_id;
                $_POST['add_time'] = $_SERVER['REQUEST_TIME'];
                $result =$database_house_village_visitor->house_village_visitor_add($_POST);
                if(!$result){
                    $this->error('数据处理有误！');
                }else{
                    if($result['status']){
                        $this->success($result['msg']);
                    }else{
                        $this->error($result['msg']);
                    }
                }
            }else{
                $visitor_type = $database_house_village_visitor->visitor_type;
                $this->assign('visitor_type' , $visitor_type);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }

    public function visitor_del(){
        //访客登记-删除 权限
        if (!in_array(218, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id =$_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }
        $database_house_village_visitor = D('House_village_visitor');
        $where['id'] = $id;
        $result = $database_house_village_visitor->house_village_visitor_del($where);
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }

    public function chk_visitor_info(){
        //访客登记-放行 权限
        if (!in_array(217, $this->house_session['menus'])) {
           exit(json_encode(array('status'=>0,'msg'=>'对不起，您没有权限执行此操作')));
        }
        $id = $_POST['id'] + 0;
        $status = $_POST['status'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }
        $database_house_village_visitor = D('House_village_visitor');
        $where['id'] = $id;
        $data['status'] = $status;
        $result = $database_house_village_visitor->house_village_visitor_edit($where,$data);
        if(!$result){
            exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
        }else{
            exit(json_encode(array('status'=>$result['status'],'msg'=>$result['msg'])));
        }
    }

    public function visitor_detail(){
        $id = $_GET['id'] + 0;

        if(!$id){
            $this->error('传递参数有误！');
        }
        $database_house_village_visitor = D('House_village_visitor');
        $where['id'] = $id;
        $detail = $database_house_village_visitor->house_village_visitor_detail($where);

        if(!$detail['status']){
            $this->error('该信息不存在！');
        }
        $this->assign('detail',$detail['detail']);
        $this->display();
    }


    public function visitor_search(){
        $village_id = $this->house_session['village_id'];
        $has_visitor = $this->getHasConfig($village_id, 'has_visitor');

        //访客登记-查看 权限
        if (!in_array(216, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if($has_visitor){
            $database_house_village_visitor = D('House_village_visitor');
            if(IS_POST){
                $visitor_keyword = $_POST['visitor_keyword'];
                $owner_keyword = $_POST['owner_keyword'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];
                $visitor_type = $_POST['visitor_type'];

                if($visitor_keyword){
                    $where['visitor_name|visitor_phone'] = array('like','%'.$visitor_keyword.'%');
                }

                if($owner_keyword){
                    $where['owner_name|owner_phone'] = array('like','%'.$owner_keyword.'%');
                }

                if($start_time && $end_time){
                    $start_time = strtotime($start_time);
                    $end_time = strtotime($end_time.'23:59:59');
                    $where['add_time'] = array('between',array($start_time,$end_time));
                }else if($start_time){
                    $start_time = strtotime($start_time);
                    $where['add_time'] = array('egt',$start_time);
                }else if($end_time){
                    $end_time = strtotime($end_time.'23:59:59');
                    $where['add_time'] = array('lt',$end_time);
                }

                if($visitor_type){
                    $where['visitor_type'] = $visitor_type;
                }
                $result = $database_house_village_visitor->ajax_house_village_visitor_search($where);
                $result['power'] = in_array(218, $this->house_session['menus']) ? true : false;
                exit(json_encode($result));
            }else{
                $this->assign('visitor_type',$database_house_village_visitor->visitor_type);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }

    }


    public function ajax_get_owner_info(){
        $database_house_village_user_bind = D('House_village_user_bind');
        $owner_phone = $_POST['owner_phone'];
        $village_id = $this->house_session['village_id'];
        $where['village_id'] = $village_id;
        $where['phone'] = $owner_phone;
        $where['parent_id'] = 0;
        $where['type'] = array('in', array(0, 3));

        $field = array('pigcms_id' , 'name' , 'address');
        $result = $database_house_village_user_bind->house_village_user_bind_detail($where , $field);
        if(!$result){
            exit(json_encode('数据处理有误！'));
        }else{
            exit(json_encode($result));
        }
    }


    public function owner_arrival(){
        //物业对账-创建订单 权限
        if (!in_array(35, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $this->display();
    }

    // 添加缴费订单
    public function owner_arrival_add(){
        //物业对账-创建订单 权限
        if (!in_array(35, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){

            $type = $_POST['type'];
            $bind_where['usernum'] = $_POST['usernum'];
            $database_house_village_user_bind = D('House_village_user_bind');
            $now_bind_info         = $database_house_village_user_bind->where($bind_where)->find();
            $now_village = D('House_village')->get_one($now_bind_info['village_id']);

            if (!$now_bind_info) {
                $this->error('该物业编号不存在！');
            }

            if($type=='property') {
                $database_house_village_property  = D('House_village_property');
                $database_house_village_floor     = D('House_village_floor');


                $now_floor_info = $database_house_village_floor->get_floor_info($now_bind_info['floor_id']);

                $property_where['id'] = $_POST['property_id'] + 0;
                $now_property_info    = $database_house_village_property->house_village_property_detail($property_where);
                $now_property_info    = $now_property_info['detail'];

                if (!$now_property_info) {
                    $this->error('物业缴费周期不存在！');
                }

                $data['order_name']         = '缴纳物业费';
                $data['order_type']         = 'property';
                $data['village_id']         = $this->house_session['village_id'];
                $data['time']               = time();
                $data['property_month_num'] = $now_property_info['property_month_num'];
                $data['floor_type_name']    = $now_floor_info['name'] ? $now_floor_info['name'] : '';
                $data['house_size']         = $now_bind_info['housesize'];
                $data['bind_id']            = $now_bind_info['pigcms_id'];
                $data['uid']                = $now_bind_info['uid'];
                $data['diy_type']           = $now_property_info['diy_type'];
                if ($now_property_info['diy_type'] > 0) {
                    $data['diy_content'] = $now_property_info['diy_content'];
                } else {
                    $data['presented_property_month_num'] = $now_property_info['presented_property_month_num'] ? $now_property_info['presented_property_month_num'] : 0;
                }

                if (($now_floor_info['property_fee'] != '0.00') && isset($now_floor_info['property_fee'])) {
                    $data['money']        = $now_floor_info['property_fee'] * $now_bind_info['housesize'] * $now_property_info['property_month_num'];
                    $data['property_fee'] = $now_floor_info['property_fee'];
                } else {
                    $data['money']        = $now_village['property_price'] * $now_bind_info['housesize'] * $now_property_info['property_month_num'];
                    $data['property_fee'] = $now_village['property_price'];
                }
                $data['remarks'] = $_POST['remarks'];
                $order_id = M("House_village_pay_order")->add($data);
                if ($order_id) {
                    $this->success('添加成功', U('owner_arrival_order', array('order_id' => $order_id)));
                } else {
                    $this->error('订单创建失败，请重试');
                }
            }else{

                switch($type){
                    case 'water':
                        if(empty($now_village['water_price'])) $this->error('当前小区不支持缴纳水费');
                        $pay_money = $now_bind_info['water_price'];
                        $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_water` AS `use`,`water_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                        foreach($order_list as $key=>$value){
                            $order_list[$key]['desc'] = '用水 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
                        }
                        $data_order['order_name'] = '水费';
                        break;
                    case 'electric':
                        if(empty($now_village['electric_price'])) $this->error('当前小区不支持缴纳电费');
                        $pay_money = $now_bind_info['electric_price'];
                        $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_electric` AS `use`,`electric_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                        foreach($order_list as $key=>$value){
                            $order_list[$key]['desc'] = '用电 '.floatval($value['use']).' 千瓦时(度)，总费用 '.floatval($value['price']).' 元';
                        }
                        $data_order['order_name'] = '电费';
                        break;
                    case 'gas':
                        if(empty($now_village['gas_price'])) $this->error('当前小区不支持缴纳燃气费');
                        $pay_money = $now_bind_info['gas_price'];
                        $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_gas` AS `use`,`gas_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                        foreach($order_list as $key=>$value){
                            $order_list[$key]['desc'] = '使用燃气 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
                        }
                        $data_order['order_name'] = '燃气费';
                        break;
                    case 'park':
                        if(empty($now_village['park_price'])) $this->error('当前小区不支持缴纳停车费');
                        $pay_money = $now_bind_info['park_price'];
                        $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`park_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                        foreach($order_list as $key=>$value){
                            $order_list[$key]['desc'] = '停车费 '.floatval($value['price']).' 元';
                        }
                        $data_order['order_name'] = '停车费';
                        break;
                    case 'custom_payment':
                        $pay_money = $_POST['payment_price'];
                        $data_order['order_name'] = $_POST['payment_name'];
                        $data_order['payment_paid_cycle'] = $_POST['payment_paid_cycle'];
                        $data_order['payment_bind_id'] = $_POST['payment_bind_id'];
                        break;
                    case 'custom':
                        $pay_money = $_POST['custom_price'];
                        $data_order['order_name'] = '自定义缴费【'.$_POST['custom_remark'].'】';
                        break;
                }
         
                $data_order['money'] = $pay_money ;
                $data_order['uid'] = $now_bind_info['uid'];
                $data_order['bind_id'] = $now_bind_info['pigcms_id'];
                $data_order['village_id'] = $now_village['village_id'];
                $data_order['time'] = $_SERVER['REQUEST_TIME'];
                $data_order['paid'] = 0;
                $data_order['order_type'] = $type;
                $data_order['remarks'] = $_POST['remarks'];

                if($order_id = D('House_village_pay_order')->data($data_order)->add()){
                    $this->success('添加成功', U('owner_arrival_order', array('order_id' => $order_id)));
                }else{
                    $this->error('下单失败，请重试');
                }
            }
        }else{
            $database_house_village_property = D('House_village_property');
            $where['village_id'] = $_SESSION['house']['village_id'];
            $where['status'] = 1;
			
            $list = $database_house_village_property->house_village_proerty_page_list($where , true , 'property_month_num desc' , 99999);

            if(!$list){
                $this->error_tips('数据处理有误！');
            }else{
                if($list['status']){
                    $this->assign('list' , $list['list']);
                }else{
                    $this->error_tips('请先添加缴费优惠。',U('Unit/preferential_add'));
                }
            }
            $this->display();
        }
    }

    protected function check_ajax_error_tips($err_tips,$err_url=''){
        if(I('app_version') && IS_POST){
            if($err_url){
                $this->error_tips($err_tips,$err_url);
            }else{
                $this->error_tips($err_tips);
            }
        }else{
            if(IS_POST){
                $this->header_json();
                echo json_encode(array('err_code'=>-1,'err_msg'=>$err_tips,'err_url'=>$err_url));
                exit();
            }else{
                if($err_url){
                    $this->error_tips($err_tips,$err_url);
                }else{
                    $this->error_tips($err_tips);
                }
            }
        }

    }

    protected function header_json(){
        header('Content-type: application/json');
    }

    public function ajax_user_list(){
        if(IS_JAX){
            $find_type = $_POST['find_type'];
            $find_value = $_POST['find_value'];

            if ($find_value) {
                if ($find_type == 1) {
                    $where['name'] = array('like', '%' . $find_value . '%');
                } else if ($find_type == 2) {
                    $where['phone'] = array('like', '%' . $find_value . '%');
                } else if ($find_type == 3) {
                    $where['usernum'] = array('like', '%' . $find_value . '%');
                }
            }
            if($_POST['usernum']){
                $where['usernum'] = $_POST['usernum'];
            }

            $village_id = $this->village_id;
            if (empty($where)) {
                $user_list = D('House_village_user_bind')->get_limit_list_page($village_id);
            } else {
                $user_list = D('House_village_user_bind')->get_limit_list_page($village_id, 99999, $where);
            }

            $user_list = $user_list['user_list'];


            $cycle_type = array(
                    'Y'=>'年',
                    'M'=>'月',
                    'D'=>'日',
                );

            foreach ($user_list as $key => $value) {
                $payment_list = array();
                $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',
                C('DB_PREFIX').'house_village_payment_standard'=>'ps',
                C('DB_PREFIX').'house_village_payment'=>'p'))
                ->where("psb.pigcms_id= '".$value['pigcms_id']."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->select();
                $payment_list = $payment_list ? $payment_list : array();

                // 车位缴费
                $position_payment_list = D('House_village_bind_position')->get_user_position_payment_list(array('pigcms_id'=>$value['pigcms_id']));
                $payment_list = array_merge($payment_list, $position_payment_list);
                
                foreach ($payment_list as $kk => $vv) {
                    $payment_list[$kk]['start_time'] = date('Y-m-d',$vv['start_time']);
                    $payment_list[$kk]['end_time'] = date('Y-m-d',$vv['end_time']);
                    $payment_list[$kk]['cycle_type'] = $cycle_type[$vv['cycle_type']];
                    if ($vv['garage_num']) {
                        $payment_list[$kk]['payment_name'] = $vv['payment_name'].'('.$vv['garage_num'].'-'.$vv['position_num'].')';
                    }
                }
                
                $user_list[$key]['payment_list'] = $payment_list;

            }

            if($user_list){
                exit(json_encode(array('status'=>1,'user_list'=>$user_list)));
            }else{
                exit(json_encode(array('status'=>0,'user_list'=>$user_list)));
            }
        }else{
            $this->error_tips('访问页面有误！');
        }
    }


    public function owner_arrival_order(){
        //物业对账-创建订单 权限
        if (!in_array(35, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $order_id = $_GET['order_id'] + 0;
        if(!$order_id){
            $this->error('传递参数有误！');
        }

        $database_house_village_pay_order = D('House_village_pay_order');

        $now_order = $database_house_village_pay_order->get_one($order_id);
        if($now_order['paid'] > 0){
            $this->error('该订单已支付。',U('Unit/pay_order'));
        }

        $this->assign('now_order',$now_order);
        $this->assign('pay_name',$this->pay_name());
        $this->display();
    }

    public function pay_name(){
        return array(
            'water'=>'水费',
'electric'=>'电费',
'gas'=>'燃气费',
'park'=>'停车费',

        );
    }

    public function chk_cash(){
        $order_id = $_GET['order_id'] + 0;
        if(!$order_id){
            $this->error('传递参数有误！');
        }

        $database_house_village_pay_order = D('House_village_pay_order');
        $now_order = $database_house_village_pay_order->get_one($order_id);

        if($now_order['paid'] > 0){
            $this->error('该订单已经支付！');
        }

        $data['paid'] = 1;
        $data['pay_time'] = time();
        $data['pay_type'] = 1;
        $result = $database_house_village_pay_order->where(array('order_id'=>$order_id))->data($data)->save();

        if($result){
            $bind_field = $now_order['order_type'].'_price';
            if(!empty($bind_field)){
                $now_user_info = D('House_village_user_bind')->get_one($now_order['village_id'],$now_order['bind_id'],'pigcms_id');

                $data_bind['pigcms_id'] = $now_user_info['pigcms_id'];

                if($now_user_info[$bind_field] - $now_order['money'] >= 0){
                    $data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'];
                }else{
                    $data_bind[$bind_field] = 0;
                }
                $data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'] >= 0 ? $now_user_info[$bind_field] - $now_order['money'] : 0;

                D('House_village_user_bind')->data($data_bind)->save();

            }
            $database_house_village_property_paylist = D('House_village_property_paylist');

            $paylist_data['bind_id'] = $now_order['bind_id'];
            $paylist_data['uid'] = $now_order['uid'];
            $paylist_data['village_id'] = $now_order['village_id'];
            $paylist_data['property_month_num'] = $now_order['property_month_num'] + 0;
            $paylist_data['presented_property_month_num'] = $now_order['presented_property_month_num'] + 0;
            $paylist_data['house_size'] = $now_order['house_size'];
            $paylist_data['property_fee'] = $now_order['property_fee'];
            $paylist_data['floor_type_name'] = $now_order['floor_type_name'];

            $now_user_info = D('House_village_user_bind')->get_one_by_bindId($now_order['bind_id']);
            $now_pay_info = $database_house_village_property_paylist->where(array('bind_id'=>$now_order['bind_id']))->order('add_time desc')->find();

            
            if($now_user_info['property_endtime']){
                $paylist_data['start_time'] = $now_user_info['property_endtime'];
                $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['property_endtime']);
            }else{
                if($now_user_info['add_time'] > 0){
                    $paylist_data['start_time'] = $now_user_info['add_time'] ;
                    $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
                }else{
                    $paylist_data['start_time'] = time();
                    $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
                }

            }
            
            $paylist_data['end_time'] = strtotime(date('Y-m-d 23:59:59',$paylist_data['end_time']));

            $paylist_data['add_time'] = time();
            $paylist_data['order_id'] = $order_id;

            //同步物业到期时间 
            $where['uid'] = $now_order['uid'];
            $where['village_id'] = $now_order['village_id'];
            $where['pigcms_id'] = $now_order['bind_id'];
            M('House_village_user_bind')->where($where)->save(array('property_endtime'=>$paylist_data['end_time']));

            

            $database_house_village_property_paylist->data($paylist_data)->add();


            //修改绑定缴费项目
            if($now_order['order_type'] == 'custom_payment'){
                M('House_village_payment_standard_bind')->where(array('bind_id'=>$now_order['payment_bind_id']))->setInc('paid_cycle',$now_order['payment_paid_cycle']);
            }

            // 小票打印start
            $printHaddle = new PrintVillage();
            $printHaddle->printit($order_id);
            // 小票打印end

            $this->success('提交成功！',U('Unit/pay_order'));
        }else{
            $this->error('提交失败！');
        }
    }

    public function ajax_unit(){
        $database_house_village_floor = D('House_village_floor');
        $condition['village_id'] =  $this->village_id;
        $condition['status'] =  1;

        $unit_list = $database_house_village_floor->field(true)->group('floor_name')->where($condition)->select();
        if(count($unit_list) == 1){
            $return['error'] = 2;
            $return['id'] = $unit_list[0]['id'];
            $return['name'] = $unit_list[0]['floor_name'];
        }else if(!empty($unit_list)){
            $return['error'] = 0;
            $return['list'] = $unit_list;
        }else{
            $return['error'] = 1;
            $return['info'] = '没有已开启的单元！';
        }
        exit(json_encode($return));
    }


    public function ajax_floor(){
        $database_house_village_floor = D('House_village_floor');
        $condition['village_id'] =  $this->village_id;
        $condition['status'] =  1;
        $condition['floor_name'] =  $_POST['name'];
        $floor_list = $database_house_village_floor->where($condition)->select();

        if(count($floor_list) == 1 && !$_POST['type']){
            $return['error'] = 2;
            $return['id'] = $floor_list[0]['id'];
            $return['name'] = $floor_list[0]['name'];
        }else if(!empty($floor_list)){
            $return['error'] = 0;
            $return['list'] = $floor_list;
        }else{
            $return['error'] = 1;
            $return['info'] = $_POST['name'] .' 该单元下未有楼层！';
        }
        exit(json_encode($return));
    }

    public function ajax_layer(){
        $database_house_village_user_bind = D('House_village_user_bind');
        $condition['village_id'] =  $this->village_id;
        $condition['status'] =  1;
        $condition['floor_id'] = $_POST['id'] + 0;
        $condition['parent_id'] = 0;

        $layer_list = $database_house_village_user_bind->where($condition)->select();
        if(count($layer_list) == 1 && !$_POST['type']){
            $return['error'] = 2;
            $return['id'] = $layer_list[0]['id'];
            $return['name'] = $layer_list[0]['name'];
        }else if(!empty($layer_list)){
            $return['error'] = 0;
            $return['list'] = $layer_list;
        }else{
            $return['error'] = 1;
            $return['info'] = '未有业主';
        }
        exit(json_encode($return));
    }

    public function ajax_owner(){
        $database_house_village_user_bind = D('House_village_user_bind');
        $condition['village_id'] =  $this->village_id;
        $condition['pigcms_id'] = $_POST['id'] + 0;
        $condition['parent_id'] = 0;

        $owner_list = $database_house_village_user_bind->where($condition)->select();
        if(count($owner_list) == 1 && !$_POST['type']){
            $return['error'] = 2;
            $return['id'] = $owner_list[0]['id'];
            $return['name'] = $owner_list[0]['name'];
        }else if(!empty($owner_list)){
            $return['error'] = 0;
            $return['list'] = $owner_list;
        }else{
            $return['error'] = 1;
            $return['info'] = '未有业主';
        }
        exit(json_encode($return));
    }

    public function search_owner_info(){
        $pigcms_id = $_GET['owner_id'] + 0;

        if(!$pigcms_id){
            $this->error_tips('传递参数有误！');
        }

        $condition['pigcms_id'] = $pigcms_id;
        $database_house_village_user_bind = D('House_village_user_bind');
        $database_house_village_property_paylist = D('House_village_property_paylist');
        $now_bind_user = $database_house_village_user_bind->get_one($this->village_id,$pigcms_id,'pigcms_id');

        $condition_pay['bind_id'] = $pigcms_id;
        $now_bind_user['expire_time'] = $database_house_village_property_paylist->where($condition_pay)->order('add_time desc')->getField('end_time');
        if(!$now_bind_user){
            $this->error_tips('该业主不存在！');
        }else{
            $this->assign('now_bind_user' , $now_bind_user);
            $this->display();
        }
    }

    public function search_owner_pay_list(){
        $pigcms_id = $_GET['pigcms_id'];
        $where['bind_id'] = $pigcms_id;
        $where['village_id'] = $this->village_id;

        $database_house_village_property_paylist = D('House_village_property_paylist');
        $list = $database_house_village_property_paylist->where($where)->order('add_time asc')->select();
        $this->assign('list' , $list);
        $this->display();
    }


    public function ajax_property_info(){
        if(IS_JAX){
            $property_id = $_POST['property_id'] + 0;

            if(!$property_id){
                exit(json_encode(array('status'=>0,'msg'=>'传递参数有误！')));
            }

            $database_house_village_property = D('House_village_property');

            $where['id'] = $property_id;
            $where['village_id'] = $this->village_id;
            $where['status'] = 1;
            $detail = $database_house_village_property->house_village_property_detail($where);

            if(!$detail){
                exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
            }

            if($detail['status']){
                exit(json_encode(array('status'=>1,'detail'=>$detail['detail'])));
            }else{
                exit(json_encode(array('status'=>0,'detail'=>$detail['detail'])));
            }
        }else{
            $this->error_tips('访问页面有误！');
        }
    }


    private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
    }

    //代送配置
    public function express_config(){
        $village_id = $this->house_session['village_id'];
        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');

        if($has_express_service){
            $mode = D('House_village_express_config');
            if(IS_POST){
                //代送配置-编辑 权限
                if (!in_array(208, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                $_POST['start_time'] = strtotime($_POST['start_time']);
                $_POST['end_time'] = strtotime($_POST['end_time']);
                if($_POST['start_time']>=$_POST['end_time']){
                    $this->error('起送时间不能比结束时间大');
                }
                if(!is_numeric($_POST['notice_phone'])){
                    $this->error('数据处理有误！');
                }
                if(!$mode->where(array('village_id'=>$village_id))->find()) {
                    $_POST['village_id'] = $village_id;
                    $result = $mode->add($_POST);
                }else {
                    $result = $mode->where(array('village_id'=>$village_id))->save($_POST);
                }

                if(!$result){
                    $this->error('数据处理有误！');
                }else{

                    $this->success('保存成功');

                }
            }else{
                $express_config = $mode->where(array('village_id'=>$village_id))->find();
                $this->assign('express_config',$express_config);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }

    public function index_nav(){
        //首页自定义导航-查看 权限
        if (!in_array(239, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_nav = D('House_village_nav');

        $where['village_id'] = $this->house_session['village_id'];
        $result = $database_house_village_nav->house_village_nav_page_list($where , true , 'sort desc');

        if(!$result){
            $this->error('数据处理有误！');
        }

        $this->assign('result',$result['result']);
        $this->display();
    }

    public function nav_add(){
        //首页自定义导航-添加 权限
        if (!in_array(240, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $_POST['url'] = htmlspecialchars_decode($_POST['url']);
            $_POST['add_time'] = time();
            $_POST['village_id'] = $this->house_session['village_id'];
            $database_house_village_nav = D('House_village_nav');
            $result = $database_house_village_nav->house_village_nav_add($_POST);

            if(!$result){
                $this->error('数据处理有误！');
            }

            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }else{
            $this->display();
        }
    }

    public function nav_edit(){
        $database_house_village_nav = D('House_village_nav');
        $where['id'] = $_GET['id'] + 0;

        if(IS_POST){
            //首页自定义导航编辑 权限
            if (!in_array(241, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $result  = $database_house_village_nav->house_village_nav_edit($where,$_POST);
            if(!$result){
                $this->error('信息处理有误！');
            }

            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }else{
            //首页自定义导航-查看 权限
            if (!in_array(239, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $result = $database_house_village_nav->house_village_nav_detail($where);
            if(!$result){
                $this->error('数据处理有误！');
            }

            $this->assign('detail',$result['detail']);
            $this->display();
        }
    }


    public function nav_del(){
        //首页自定义导航-删除 权限
        if (!in_array(242, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_nav = D('House_village_nav');
        $where['id'] = $_GET['id'] + 0;

        $result = $database_house_village_nav->house_village_nav_del($where);

        if(!$result){
            $this->error('数据处理有误！');
        }

        if($result['status']){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }

    public function express_send_list(){
        //快递代发-查看 权限
        if (!in_array(214, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $add_time_start = $_POST['add_time_start'];
        $add_time_end = $_POST['add_time_end'];
        $this->assign('add_time_start', $add_time_start);
        $this->assign('add_time_end', $add_time_end);
        
        if($add_time_start && $add_time_end){
            $start_time = strtotime($add_time_start);
            $end_time = strtotime($add_time_end.'23:59:59');
            $where['add_time'] = array('between',array($start_time,$end_time));
        }else if($add_time_start){
            $add_time_start = strtotime($add_time_start);
            $where['add_time'] = array('egt',$add_time_start);
        }else if($add_time_end){
            $add_time_end = strtotime($add_time_end.'23:59:59');
            $where['add_time'] = array('lt',$add_time_end);
        }

        $send_phone = $_POST['send_phone'];
        $this->assign('send_phone', $send_phone);
        if($send_phone){
            $where['send_phone'] = $send_phone;
        }

        $express = $_POST['express'];
        $this->assign('express', $express);
        if($express){
            $where['express'] = $express;
        }

        $village_id = $this->house_session['village_id'];
        $has_express_send = $this->getHasConfig($village_id, 'has_express_send');
        $this->assign('has_express_send',$has_express_send);
        if($has_express_send){

            $express_tmp = M('Express')->where(array('status'=>1))->select();
            $this->assign('express_tmp',$express_tmp);
            foreach ($express_tmp as $key => $value) {
                $express_list[$value['code']] = $value['name'];
            }
            $this->assign('express_list',$express_list);

            $goods_type = array('1'=>'文件','2'=>'数码产品','3'=>'生活用品','4'=>'服饰','5'=>'食品','6'=>'其他');
            $this->assign('goods_type',$goods_type);

            $where['village_id'] = $village_id;

            $count = M('House_village_express_send')->where($where)->count();
            import('@.ORG.merchant_page');
            $p = new Page($count, 15);
            $send_list= M('House_village_express_send')->where($where)->order('send_id DESC')->limit($p->firstRow,$p->listRows)->select();
            $this->assign('pagebar',$p->show());
            $this->assign('send_list',$send_list);


        }
        $this->display();
    }


    public function express_send_export(){
        //快递代发-导出 权限
        if (!in_array(215, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $goods_type = array('1'=>'文件','2'=>'数码产品','3'=>'生活用品','4'=>'服饰','5'=>'食品','6'=>'其他');

        $express_tmp = M('Express')->where(array('status'=>1))->select();
        $this->assign('express_tmp',$express_tmp);
        foreach ($express_tmp as $key => $value) {
            $express_list[$value['code']] = $value['name'];
        }


        if($_GET['send_id']){
            $where['send_id'] = array('in',$_GET['send_id']);
        }

        $send_list = M('House_village_express_send')->where($where)->select();
       
        if(count($send_list) <= 0 ){
            $this->error('无数据导出！');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

        $title = $this->village['village_name'] . '社区-快递代发列表';

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $length = ceil(count($send_list)/1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('共' . count($send_list) . '条数据');
            $objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '快递公司');
            $objActSheet->setCellValue('B1', '寄件人信息');
            $objActSheet->setCellValue('C1', '收件人信息');
            $objActSheet->setCellValue('D1', '物品重量');
            $objActSheet->setCellValue('E1', '文件类型');
            $objActSheet->setCellValue('F1', '代发费用');
            $objActSheet->setCellValue('G1', '备注');
            $objActSheet->setCellValue('H1', '提交时间');

            if (!empty($send_list)) {
                $index = 2;

                $cell_list = range('A','H');
                foreach ($cell_list as $cell) {
                    $objActSheet->getColumnDimension($cell)->setWidth(40);
                }

                foreach ($send_list as $value) {
                    $objActSheet->setCellValueExplicit('A' . $index, $express_list[$value['express']]);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['send_uname']." ".$value['send_phone']." ".$value['send_city']." ".$value['send_adress']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['collect_uname']." ".$value['collect_phone']." ".$value['collect_city']." ".$value['collect_adress']);
                    $objActSheet->setCellValueExplicit('D' . $index, $value['weight'].' (Kg)');
                    $objActSheet->setCellValueExplicit('E' . $index, $goods_type[$value['goods_type']]);
                    $objActSheet->setCellValueExplicit('F' . $index, $value['send_price'].' (元)');
                    $objActSheet->setCellValueExplicit('G' . $index, $value['remarks']);
                    $objActSheet->setCellValueExplicit('H' . $index, date('Y-m-d H:i:s',$value['add_time']));
                    M('House_village_express_send')->where(array('send_id'=>$value['send_id']))->data(array('export_time'=>time()))->save();
                    $index++;
                }
            }
            sleep(2);
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
    }


    public function store_arrival_check(){
        $order_info = D('House_village_pay_order')->where(array('order_id'=>$_POST['order_id']))->find();
        if($order_info['paid'] > 0 ){
            exit(json_encode(array('status'=>1,'msg'=>'缴费成功')));
        }
    }

}
?>

