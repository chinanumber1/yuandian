<?php

class RpcClient
{

    private $app_key;
    private $app_secret;
    private $api_request_url;
    private $token;

    public function __construct($token, $config)
    {
        $this->app_key = $config->get_app_key();
        $this->app_secret = $config->get_app_secret();
        $this->api_request_url = $config->get_request_url() . "/api/v1";
        $this->token = $token;
    }

    /** call server api with nop
     * @param $action
     * @param array $parameters
     * @return mixed
     * @throws BusinessException
     * @throws Exception
     */
    public function call($action, array $parameters)
    {
        $protocol = array(
            "nop" => '1.0.0',
            "id" => $this->create_uuid(),
            "action" => $action,
            "token" => $this->token,
            "metas" => array(
                "app_key" => $this->app_key,
                "timestamp" => time(),
            ),
            "params" => $parameters,
        );



        $protocol['signature'] = $this->generate_signature($protocol);

        //如果没有参数，赋值为一个空对象
        if (count($parameters) == 0) {
            $protocol["params"] = (object)array();
        }

        $result = $this->post($this->api_request_url, $protocol);
        $response = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        return $response;
    }

    private function generate_signature($protocol)
    {
        $merged = array_merge($protocol['metas'], $protocol['params']);
        ksort($merged);
        $string = "";
        foreach ($merged as $key => $value) {
            $string .= $key . "=" . json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        $splice = $protocol['action'] . $this->token . $string . $this->app_secret;

        $encode = mb_detect_encoding($splice, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        if ($encode != null) {
            $splice = mb_convert_encoding($splice, 'UTF-8', $encode);
        }

        return strtoupper(md5($splice));
    }

    private function create_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    private function post($url, $data)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json; charset=utf-8", "Accept-Encoding: gzip"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_USERAGENT, "eleme-openapi-php-sdk");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            if ($log != null) {
                $log->error("error: " . curl_error($ch));
            }
            throw new Exception(curl_error($ch));
        }

        if ($log != null) {
            $log->info("response: " . $response);
        }
        return $response;
    }
}
