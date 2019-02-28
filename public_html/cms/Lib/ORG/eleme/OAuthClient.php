<?php

class OAuthClient
{
    private $client_id;
    private $secret;
    private $token_url;
    private $authorize_url;

    public function __construct($config)
    {
        $this->config = $config;
        $this->client_id = $config->get_app_key();
        $this->secret = $config->get_app_secret();
        $this->token_url = $config->get_request_url() . "/token";
        $this->authorize_url = $config->get_request_url() . "/authorize";
    }

    /**
     * 客户端模式，获取token
     * @return mixed
     */
    public function get_token_in_client_credentials()
    {
        $body = array(
            "grant_type" => "client_credentials"
        );
        $response = $this->request($body);
        return $response;
    }

    /**
     * 生成授权url
     * @param $state 状态码，通常是随机的UUID，授权成功后会原样返回，可以用于校验
     * @param $scope 授权范围，默认情况下填写"all"
     * @param $callback_url 回调地址
     * @return string
     */
    public function get_auth_url($state, $scope, $callback_url)
    {
        $url = $this->authorize_url;
        $response_type = "code";
        $client_id = $this->client_id;
        $callback = $callback_url;
        return $url . "?response_type=" . $response_type . "&client_id=" . $client_id . "&state=" . $state . "&redirect_uri=" . urlencode($callback) . "&scope=" . $scope;
    }

    /**
     * 通过授权码获取token
     * @param $code 授权码
     * @param $callback_url 回调地址
     * @return mixed
     */
    public function get_token_by_code($code, $callback_url)
    {
        $body = array(
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $callback_url,
            "client_id" => $this->client_id
        );
        $response = $this->request($body);
        return $response;
    }

    /**
     * 通过refresh_token兑换新的token
     * @param $refresh_token 刷新的token
     * @param $scope 授权范围，默认情况下填写"all"
     * @return mixed
     */
    public function get_token_by_refresh_token($refresh_token, $scope)
    {
        $body = array(
            "grant_type" => "refresh_token",
            "refresh_token" => $refresh_token,
            "scope" => $scope
        );
        $response = $this->request($body);
        return $response;
    }

    private function get_headers()
    {
        return array(
            "Authorization: Basic " . base64_encode(urlencode($this->client_id) . ':' . urlencode($this->secret)),
            "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
            "Accept-Encoding: gzip");
    }

    private function request($body)
    {
        $ch = curl_init($this->token_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->get_headers());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "eleme-openapi-php-sdk");
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $request_response = curl_exec($ch);
        if (curl_errno($ch)) {
            $response = curl_errno($ch);
        } else {
            $response = json_decode($request_response, true);
        }
        return $response;
    }
}

