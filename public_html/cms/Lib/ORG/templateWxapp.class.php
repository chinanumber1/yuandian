<?php
class templateWxapp{


    public $thisWxUser;

    public $appid;

    public $appsecret;

    public function __construct($appid = null, $appsecret = null){
        $this->appid = $appid;
        $this->appsecret = $appsecret;
    }

    public function sendWxappTempMsg($tempKey, $dataArr)
    {
        $tempMsg = M('Wxapp_tempmsg')->where(array('tempKey' => $tempKey))->find();
        $msg = array();
        $msg['touser'] = $dataArr['touser'];
        $msg['template_id'] = $tempMsg['tempid'];
        $msg['page'] = $dataArr['page'];
        $msg['form_id'] = $dataArr['form_id'];
        // （AT0052） 审核通知：  姓名 申请项目 状态 日期
        // （AT0751） 成员退出通知：  用户昵称 退出方式 备注 退出时间
        // （AT0130） 投票创建成功通知：  投票标题 创建时间 发起人 投票内容
        // （AT1175） 内容创建成功通知：  主题 内容类型 创建人 创建时间
        // （AT0322） 活动创建成功提醒：  活动名称 活动时间 发布人 发布时间 活动人数限制 报名费用
        // （AT0027） 报名成功通知：  报名项目 活动主题 报名姓名 报名时间
        // （AT1454） 已报名活动参加提醒：  活动时间 活动主题 参加人员 费用
        // （AT0036） 退款通知：  退款类型 退款原因 退款金额 退款时间
        // （AT0225） 活动取消通知：  活动名称 取消原因 取消时间 报名人数
        $data = $this->send_msg_check($dataArr);
        if ($tempMsg['status'] == 0) {
            if (!empty($tempMsg['textcolor'])) {
                $msg['color'] = $tempMsg['textcolor'];
            }
            $msg['data'] = $data;
            $sendData = json_encode($msg);
            $msg_class = new plan_msg();
            $param = array(
                'type' => '5',
                'content' => array('content' => $sendData)
            );
            $msg_class->addTask($param);
        }
        $id = $dataArr['id'] ? $dataArr['id'] : 0;
        if ($id) {
            $database_community_user_formid = D('Community_user_formid');
            $del = $database_community_user_formid->del($id, 'id');
            if ($del) {
                return false;
            }
        }
        return true;
    }

    private function send_msg_check($data) {
        $msg = array();
        if (!empty($data['keyword1'])) {
            $msg['keyword1'] = array(
                'value' => $data['keyword1']
            );
        }
        if (!empty($data['keyword2'])) {
            $msg['keyword2'] = array(
                'value' => $data['keyword2']
            );
        }
        if (!empty($data['keyword3'])) {
            $msg['keyword3'] = array(
                'value' => $data['keyword3']
            );
        }
        if (!empty($data['keyword4'])) {
            $msg['keyword4'] = array(
                'value' => $data['keyword4']
            );
        }
        if (!empty($data['keyword5'])) {
            $msg['keyword5'] = array(
                'value' => $data['keyword5']
            );
        }
        if (!empty($data['keyword6'])) {
            $msg['keyword6'] = array(
                'value' => $data['keyword6']
            );
        }
        return $msg;
    }


    public function sendWeixinTempMsg($sendData)
    {
        $access_token_array = D('Access_token_wxcapp_expires')->get_access_token();
        if ($access_token_array['errcode']) {
            return '获取access_token发生错误：错误代码' . $access_token_array['errcode'] . ',微信返回错误信息：' . $access_token_array['errmsg'];
        }
        $access_token = $access_token_array['access_token'];
        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;

        $result = $this->postCurl($requestUrl, $sendData);
        return $result;
    }

    function postCurl($url, $data){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        $errorno=curl_errno($ch);
        if ($errorno) {
            return array('rt'=>false,'errorno'=>$errorno);
        }else{
            $js=json_decode($tmpInfo,1);
            if ($js['errcode']=='0'){
                return array('rt'=>true,'errorno'=>0);
            }else {
                //exit('模板消息发送失败。错误代码'.$js['errcode'].',错误信息：'.$js['errmsg']);
                return array('rt'=>false,'errorno'=>$js['errcode'],'errmsg'=>$js['errmsg']);

            }
        }
    }


    function curlGet($url){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $temp = curl_exec($ch);
        return $temp;
    }


    /**
     * 返回处理好的值
     * @param $tempKey
     * @param $dataArr
     * @return array
     */
    public function sendWxappTempInfo($tempKey, $dataArr)
    {
        $tempMsg = M('Wxapp_tempmsg')->where(array('tempKey' => $tempKey))->find();
        $msg = array();
        $msg['touser'] = $dataArr['touser'];
        $msg['template_id'] = $tempMsg['tempid'];
        $msg['page'] = $dataArr['page'];
        $msg['form_id'] = $dataArr['form_id'];
        // （AT0052） 审核通知：  姓名 申请项目 状态 日期
        // （AT0751） 成员退出通知：  用户昵称 退出方式 备注 退出时间
        // （AT0130） 投票创建成功通知：  投票标题 创建时间 发起人 投票内容
        // （AT1175） 内容创建成功通知：  主题 内容类型 创建人 创建时间
        // （AT0322） 活动创建成功提醒：  活动名称 活动时间 发布人 发布时间 活动人数限制 报名费用
        // （AT0027） 报名成功通知：  报名项目 活动主题 报名姓名 报名时间
        // （AT1454） 已报名活动参加提醒：  活动时间 活动主题 参加人员 费用
        // （AT0036） 退款通知：  退款类型 退款原因 退款金额 退款时间
        // （AT0225） 活动取消通知：  活动名称 取消原因 取消时间 报名人数
        $data = $this->send_msg_check($dataArr);
        if ($tempMsg['status'] == 0) {
            if (!empty($tempMsg['textcolor'])) {
                $msg['color'] = $tempMsg['textcolor'];
            }
            $msg['data'] = $data;
            $sendData = json_encode($msg);
            return array(
                'content' => $sendData
            );
        }
        return array();
    }

}
