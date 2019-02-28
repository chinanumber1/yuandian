<?php

/**
 * Created by gogo.
 * User: win 10
 * Date: 2018/6/29
 * Time: 10:40
 */
class Wxapp_store
{
    private $access_token;
    public function __construct()
    {

    }


    //获取城市
    public function get_wx_city_list(){
        $this->get_access();
        $city_list  = S('wxapp_city_list');
        if(empty($city_list)){
            $url="https://api.weixin.qq.com/wxa/get_district?access_token=".$this->access_token;
            $city_list = $this->httpRequest($url);
            $city_list = json_decode($city_list[1],true);
            $city_list = $city_list['result'];
            S('wxapp_city_list',$city_list);
        }
        return $city_list;
    }

    //创建门店程序
    public function create_wx_store($params){
        $this->get_access();
        $url="https://api.weixin.qq.com/wxa/add_store?access_token=".$this->access_token;
        $res = $this->httpRequest($url,'POST', json_encode($params, JSON_UNESCAPED_UNICODE));

        return json_decode($res[1],true);
    }

  public function del_wx_store($params){
        $this->get_access();
        $url="https://api.weixin.qq.com/wxa/del_store?access_token=".$this->access_token;
        $res = $this->httpRequest($url,'POST', json_encode($params, JSON_UNESCAPED_UNICODE));

        return json_decode($res[1],true);
    }



    //在腾讯地图中创建门店
    public function create_map_poi($params){
        $this->get_access();

        $url="https://api.weixin.qq.com/wxa/create_map_poi?access_token=".$this->access_token;
        $city_list = $this->httpRequest($url,'POST', json_encode($params, JSON_UNESCAPED_UNICODE));


        return json_decode($city_list[1],true);
    }

  public function get_store_info($params){
        $this->get_access();

        $url="https://api.weixin.qq.com/wxa/get_store_info?access_token=".$this->access_token;
        $city_list = $this->httpRequest($url,'POST', json_encode($params, JSON_UNESCAPED_UNICODE));


        return json_decode($city_list[1],true);
    }

    public function set_card($params){
        $this->get_access();

        $url="https://api.weixin.qq.com/card/storewxa/set?access_token=".$this->access_token;
        $city_list = $this->httpRequest($url,'POST', json_encode($params, JSON_UNESCAPED_UNICODE));


        return json_decode($city_list[1],true);
    }

    public function wifishop_list($params){
        $this->get_access();

        $url="https://api.weixin.qq.com/bizwifi/shop/list?access_token=".$this->access_token;
        $city_list = $this->httpRequest($url,'POST', json_encode($params, JSON_UNESCAPED_UNICODE));


        return json_decode($city_list[1],true);
    }

    public function wifi_jump_wxapp($params){
        $this->get_access();
        $url="https://api.weixin.qq.com/bizwifi/finishpage/set?access_token=".$this->access_token;
        $city_list = $this->httpRequest($url,'POST', json_encode($params, JSON_UNESCAPED_UNICODE));
        return json_decode($city_list[1],true);
    }

    //获取分类
    public function get_category(){
        $this->get_access();
        $tmp_store_category  = S('wxapp_category_list');
        if(empty($city_list)){
            $url="https://api.weixin.qq.com/wxa/get_merchant_category?access_token=".$this->access_token;
            $category_list = $this->httpRequest($url);
            $category_list = json_decode($category_list[1],true);

            $category_list = $category_list['data']['all_category_info']['categories'];
            $root = $category_list[0]['children'];
            unset($category_list[0]);
            $tmp = array();
            foreach ($category_list as $v) {
                $tmp[$v['id']] = $v;
            }
            $tmp_store_category = array();
            foreach ($root as $key=>$item) {
                $tmp_children = [];
                foreach ($tmp[$item]['children'] as $k=>$vv) {
                    $tmp[$vv]['key'] = $k;
                    $tmp[$vv]['fullname'] =  $tmp[$vv]['name'];
                    $tmp_children[] = $tmp[$vv];
                }

                $tmp[$item]['childrens'] = $tmp_children;
                $tmp[$item]['key'] = $key;
                $tmp[$item]['fullname'] =  $tmp[$item]['name'];
                $tmp_store_category[] = $tmp[$item];
            }
            S('wxapp_category_list',$tmp_store_category);
        }
       return $tmp_store_category;
    }

    public function get_wx_area_search($districtid,$keyword){
        $this->get_access();
        $url="https://api.weixin.qq.com/wxa/search_map_poi?access_token=".$this->access_token;
        $param['districtid'] = $districtid;
        $param['keyword'] = $keyword;
        $area = $this->httpRequest($url,'POST', json_encode($param, JSON_UNESCAPED_UNICODE));
        $area = json_decode($area[1],true);


        return $area;
    }

    public function get_access(){
        $mode = D('Access_token_expires');
        $res = $mode->get_access_token();
        $this->access_token =  $res['access_token'];
    }


    function upload_image($images){
        $url="https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$this->access_token;
        $this->curlUploadFile($url,$_FILES);
    }

    function update_user($date){
        $url="https://api.weixin.qq.com/card/membercard/updateuser?access_token=".$this->access_token;
        $this->httpRequest($url,'post','');
    }

    public function curlUploadFile($remote,$file) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $remote );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $file );
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }


    function httpRequest($url, $method = 'GET', $postfields = null, $headers = array(), $debug = false) {
        /* $Cookiestr = "";  * cUrl COOKIE处理*
          if (!empty($_COOKIE)) {
          foreach ($_COOKIE as $vk => $vv) {
          $tmp[] = $vk . "=" . $vv;
          }
          $Cookiestr = implode(";", $tmp);
          } */
        $method = strtoupper($method);
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }
        $ssl = preg_match('/^https:\/\//i', $url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
        if ($ssl) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
        }
        //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2); /* 指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的 */
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        /* curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response = curl_exec($ci);
        $requestinfo = curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);

            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response, $requestinfo);
    }
}