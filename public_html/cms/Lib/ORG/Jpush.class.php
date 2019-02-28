<?php
class Jpush
{
    public $jpush_api = "https://api.jpush.cn/v3/push";
    public $user, $pwd;

    public function __construct($user = null, $pwd = null) {
        $this->user = $user;
        $this->pwd = $pwd;
        //$this->jpush_api = $jpush_api;
    }
    /**
    * 发送Jpush HTTP请求方法
    * @param  string $url    请求URL
    * @param  array  $params 请求参数
    * @param  string $method 请求方法GET/POST
    * @return array  $data   响应数据
    */
    public function request($url, $params, $method = 'POST', $header=array("Content-Type: application/json")){
        $opts = array(
                //CURLOPT_TIMEOUT        => 30,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERPWD => $this->user.':'.$this->pwd,
                CURLOPT_HTTPHEADER     => $header
        );
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                //$params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = json_encode($params);
                break;
            default:
                throw new Exception('不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            //throw new ThinkException($error);
        }

        return  json_decode($data, true);
    }

    /**
    * 封装Jpush body体
    * @param  string  $platform     推送平台设置
    * @param  array   $audience     推送设备指定
    * @param  array   $notification 通知内容体
    * @param  array   $message      消息内容体
    * @return boolean $return       推送结果
    */
    public function send($platform, $audience, $notification, $message=null,$is_product=true) {
        $columns = array();
        $columns['platform'] = $platform;
		if(isset($audience['tag'][0]) && is_string($audience['tag'][0])){
			$audience['tag'][0] = strtoupper($audience['tag'][0]);
		}
        $columns['audience'] = $audience;
        $columns['notification'] = $notification;
        if ($message) {
            $columns['message'] = $message;
        }
        $columns['options'] = array(
            'apns_production'   =>  $is_product,
        );
        //$data = $columns;
        $authorization = base64_encode($this->user.':'.$this->pwd);
        $header = array(
            "Content-Type: application/json",
            "Authorization: Basic ".$authorization
        );

        $result = $this->request($this->jpush_api, $columns, "POST", $header);
        if ($result['code']) {
            return array('status'=>0, 'msg'=>$result['msg']);
        }

        return array('status'=>1, 'msg'=>"推送成功");
    }

    public function createBody($client, $title, $msg, $extra, $sound='default') {
        switch ($client) {
            case 1:
                $return = array(
					'ios'   =>  array(
                        'alert'     => $msg,
                        'sound'     => $sound,
                        'badge'     => "+1",
                        'extras'    => $extra
                    )
                );break;
            case 2:
                $return = array(
                    'android'       =>  array(
                        'alert'      =>  $msg,
                        'title'      =>  $title,
                        'builder_id' =>  1,
                        'extras'     =>  $extra
                    )
                );break;
            case 3:
                $return = array(
                    'android'       =>  array(
                        'alert'      =>  $msg,
                        'title'      =>  $title,
                        'builder_id' =>  1,
                        'extras'     =>  $extra
                    ),
                    'ios'   =>  array(
                        'alert'     => $msg,
                        'sound'     => "default",
                        'badge'     => "+1",
                        'extras'    => $extra
                    )
                );break;
            default:
                $return = array();
        }

        return $return;
    }

    public function createMsg($title, $msg, $extra) {
        $return =   array(
            'msg_content'   =>  $msg,
            'title'         =>  $title,
            'content_type'  =>  1,
            'extras'        =>  $extra,
        );
        return $return;
    }
}