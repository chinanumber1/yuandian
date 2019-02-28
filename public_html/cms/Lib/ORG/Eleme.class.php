<?php
require 'eleme/Config.php';
require 'eleme/OAuthClient.php';
class Eleme
{
    public $config = null;
    
    private $token = null;
    
    public function __construct($sandbox = false)
    {
        $this->config = new Config($sandbox);
        
    }
    public function setToken($token)
    {
        $this->token = $token;
    }
    
    public function getToken()
    {
        $client = new OAuthClient($this->config);
        
        $token = $client->get_token_in_client_credentials();
    }
    
    public function getAuthUrl()
    {
        $client = new OAuthClient($this->config);
        
        $url = $client->get_auth_url(md5(uniqid()), 'all', C('config.site_url') . '/index.php?g=Index&c=Eleme&a=index');
        return $url;
    }
    
    public function getTokenByRefreshToken($refresh_token)
    {
        $client = new OAuthClient($this->config);
        
        $token = $client->get_token_by_refresh_token($refresh_token, 'all');
        
        return $token;
    }
    
    public function getTokenByCode($code)
    {
        $client = new OAuthClient($this->config);
        
        $token = $client->get_token_by_code($code, C('config.site_url') . '/index.php?g=Index&c=Eleme&a=index');
        
        return $token;
    }
    
    public function getDataByApi($action, $params = array())
    {
        require 'eleme/RpcClient.php';
        $api = new RpcClient($this->token, $this->config);
        
        return $api->call($action, $params);
    }
}
?>