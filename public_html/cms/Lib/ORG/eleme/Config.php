<?php
class Config
{
    private $request_url;

    private $default_request_url = "https://open-api.shop.ele.me";
    
    private $default_sandbox_request_url = "https://open-api-sandbox.shop.ele.me";

    public function __construct($sandbox)
    {
        if ($sandbox) {
            $this->request_url = $this->default_sandbox_request_url;
        } else {
            $this->request_url = $this->default_request_url;
        }
    }

    public function get_app_key()
    {
        return C('config.eleme_app_key');
    }

    public function get_app_secret()
    {
        return C('config.eleme_app_secret');
    }

    public function get_request_url()
    {
        return $this->request_url;
    }

    public function set_request_url($request_url)
    {
        $this->request_url = $request_url;
    }
}