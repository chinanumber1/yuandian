<?php
/*
 * 社区功能管理
 *
 */
class House_expressAction extends BaseAction{
	//	取快递 
	public function express_edit(){
        $database_house_village_express = D('House_village_express');
       
        $status = I('status',2); //状态 1 本人 2 社区
        $fetch_code = I('fetch_code');
        $express_no = I('express_no');

 		if(!empty($fetch_code)){
 			$info = $database_house_village_express->where(array('fetch_code'=>$fetch_code,'status'=>0))->find();
 		}elseif (!empty($express_no)) {
 			$info = $database_house_village_express->where(array('express_no'=>$express_no,'status'=>0))->find();
 		}


        if(!is_array($info)){
            exit(json_encode(array('status'=>3,'msg'=>'数据处理有误！')));
        }

        $express_info = D('Express')->where(array('id'=>$info['express_type']))->find();

        $where['id'] = $order_where['express_id'] = $info['id'];
        $data['status'] = $status;

        $database_house_village_express_order = D('House_village_express_order');
        $database_house_village_express_order->house_village_express_order_edit($order_where,$data);

        $data['delivery_time'] = time();

        $result = $database_house_village_express->where($where)->data($data)->save();

        if(!$result){
            exit(json_encode(array('status'=>2,'msg'=>'数据处理有误！')));
        }else{
            exit(json_encode(array('error_code'=>1,'msg'=>'取件成功')));
        }

    }





    public function express_info(){
        $database_house_village_express = D('House_village_express');
        $fetch_code = I('fetch_code');
        $express_no = I('express_no');


 		if(empty($fetch_code)){
 			$info = $database_house_village_express->where(array('fetch_code'=>$fetch_code,'status'=>0))->find();
 		}elseif (empty($express_no)) {
 			$info = $database_house_village_express->where(array('express_no'=>$express_no,'status'=>0))->find();
 		}

        if(!is_array($info)){
            exit(json_encode(array('error_code'=>1,'msg'=>'数据处理有误！')));
        }else{
        	exit(json_encode(array('error_code'=>2,'msg'=>'返回成功','data'=>$info)));
        }
    }





	public function express_add(){
		$database_house_village_express = D('House_village_express');

		$data['express_no'] = I('express_no',0); //订单号
		$data['express_type'] = I('express_type',0); // 快递类型
		$data['phone'] = I('phone',0); // 手机号
		$data['memo'] = I('memo',0); //备注
		$data['village_id'] = I('village_id',0); //社区id

		if(empty($data['express_no']) || empty($data['express_type']) || empty($data['phone']) || empty($data['village_id'])){
			exit(json_encode(array('error_code'=>4,'msg'=>'数据格式有误！')));
		}

        $expressInfo = D('Express')->where(array('id'=>$data['express_type']))->find();

        $data['fetch_code'] = rand(10000,99999);
		$data['add_time'] = time();
        $result = $database_house_village_express->village_express_add($data);
        if(!$result){
            // $this->error('数据处理有误！');
            exit(json_encode(array('error_code'=>3,'msg'=>'数据处理有误！')));
        }else{
            if($result['status']){
                exit(json_encode(array('error_code'=>1,'msg'=>$result['msg'])));
            }else{
                exit(json_encode(array('error_code'=>2,'msg'=>$result['msg'])));
            }
        }
	}


	//获取订单类型
	public function check_express_no(){
        header("Content-Type: text/html;charset=utf-8"); 
        $express_no = I('express_no',0);
        $logisticResult = $this->getOrderTracesByJson($express_no);
        $Result = json_decode ( $logisticResult, JSON_FORCE_OBJECT );
        $express_info = D('Express')->where(array('kuaidiniao_code'=>$Result['Shippers'][0]['ShipperCode']))->find();
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



}