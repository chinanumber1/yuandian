<?php
class ZhiBaiWei
{
    private $apiRequestUrl = 'http://119.29.113.96:9018/Services.ashx';
    
    private $sToken = null;
    
    public function __construct()
    {
    }
    
    
    public function setToken($sToken)
    {
        $this->sToken = $sToken;
    }
    
    
    public function getToken($sAppKey = 'YLSM', $sAppCode = 'Bw8848@171118')
    {
        $params = array();
        $params['action'] = 'getToken';
        $params['sAppKey'] = $sAppKey;
        $params['sAppCode'] = $sAppCode;
        
        return $this->post($this->generate_signature($params));
    }
    
    
    public function getGoods($page = 1, $sBranchNo = '00')
    {
        $params = array();
        $params['action'] = 'getGoods';
        $params['sBranchNo'] = $sBranchNo;
        $params['querType'] = 2;
        $params['iPageIndex'] = $page;
        return $this->post($this->generate_signature($params));
    }
    
    
    public function getItemCls($page = 1)
    {
        $params = array();
        $params['action'] = 'getItemCls';
        $params['iPageIndex'] = $page;
        return $this->post($this->generate_signature($params));
    }
    
    public function getItemImage($goodsId = '0000000000528')
    {
        $params = array();
        $params['action'] = 'getItemImage';
        $params['goodsId'] = $goodsId;
        return $this->post($this->generate_signature($params));
    }
    
    public function goodsStock($goodsIds, $sBranchNo = 'all')
    {
        $params = array();
        $params['action'] = 'goodsStock';
        $params['sBranchNo'] = $sBranchNo;
        $params['iPageIndex'] = 1;
        $params['iPageSize'] = 10;
        $params['goodsIds'] = $goodsIds;
        return $this->post($this->generate_signature($params));
    }
    
    private function generate_signature($params = array())
    {
        $string = '';
        $pre = '';
        $params['sToken'] = $this->sToken;
        ksort($params);
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $string .= $pre . $key . '=' . $value;
                $pre = '&';
            }
        }
        $string = strtoupper($string);
        $params['sMd5'] = strtoupper(md5($string));
        return $url = $this->apiRequestUrl . '?param=' . json_encode($params);
    }
    
    private function post($url)
    {
        $ch = curl_init($url);
//         echo $url . '<br/>';
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json; charset=utf-8"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $data = json_decode($response, true);
        return $data;
    }
}
?>