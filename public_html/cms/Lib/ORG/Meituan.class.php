<?php
class Meituan
{
    private $appAuthToken = null;
    
    private $charset = 'UTF-8';
    
    private $developerId = '';//开发者ID
    
    private $signKey = '';//开放平台分配给开发者的数字签名
    
    private $apiRequestUrl = 'http://api.open.cater.meituan.com/';
    
    private $version = 1;
    
    public function __construct($appAuthToken = null)
    {
        $this->signKey = C('config.meituan_sign_key');
        
        $this->developerId = C('config.meituan_developer_id');
        
        $this->appAuthToken = $appAuthToken;
    }
    

    
    public function getAuthUrl($storeId = 1, $storeName = '老乡鸭')
    {
        $url = 'https://open-erp.meituan.com/storemap?developerId=' . $this->developerId . '&businessId=2&ePoiId=' . $storeId. '&signKey=' . $this->signKey . '&ePoiName=' . $storeName . '&netStore=1';
        return $url;
        header('Location:' . $url);
        exit;
    }
    
    public function cancelBind($appAuthToken)
    {
        $url = 'https://open-erp.meituan.com/releasebinding?signKey=' . $this->signKey . '&businessId=2&appAuthToken=' . $appAuthToken;
        return $url;
        header('Location:' . $url);
        exit;
    }
    
    
    public function getDataByApi($url = 'waimai/order/queryByEpoids', $params = array())
    {
        $url = $this->apiRequestUrl . $url;
        $params['appAuthToken'] = $this->appAuthToken;
        $params['charset'] = $this->charset;
        $params['timestamp'] = time();
        $params['version'] = $this->version;
        
        $params['sign'] = $this->generate_signature($params);
        
        $url .= '?' . http_build_query($params);
        return $this->post($url);
    }
    
    private function generate_signature($params)
    {
        $string = '';
        ksort($params);
        foreach ($params as $key => $value) {
            $value && $string .= $key . $value;
        }
        $string = $this->signKey . $string;
        return strtolower(sha1($string));
    }
    
    private function post($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json; charset=utf-8", "Accept-Encoding: gzip"));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        
        $data = json_decode($response, true);
        if (curl_errno($ch)) {
            return array('error' => true, 'msg' => $data);
        }
        $data['error'] = false;
        return $data;
    }
}
?>