<?php

/*
微信卡包api SDK V1.0
!!README!!：
base_info的构造函数的参数是必填字段，有set接口的可选字段。
针对某一种卡的必填字段（参照文档）仍然需要手动set（比如团购券Groupon的deal_detail），通过card->get_card()拿到card的实体对象来set。
ToJson就能直接转换为符合规则的json。
Signature是方便生成签名的类，具体用法见示例。
注意填写的参数是int还是string或者bool或者自定义class。
更具体用法见最后示例test，各种细节以最新文档为准。
*/

class Sku
{
    function __construct($quantity)
    {
        $this->quantity = $quantity;
    }
}

;

class DateInfo
{
    function __construct($type, $arg0, $arg1 = null)
    {
        if (!is_int($type))
            exit("DateInfo.type must be integer");
        $this->type = $type;
        if ($type == 1)  //固定日期区间
        {
            if (!is_int($arg0) || !is_int($arg1))
                exit("begin_timestamp and  end_timestamp must be integer");
            $this->begin_timestamp = $arg0;
            $this->end_timestamp = $arg1;
        } else if ($type == 2)  //固定时长（自领取后多少天内有效）
        {
            if (!is_int($arg0))
                exit("fixed_term must be integer");
            $this->fixed_term = $arg0;
        } else
            exit("DateInfo.tpye Error");
    }
}

;

class BaseInfo
{
    function __construct($logo_url, $brand_name, $code_type, $title, $color, $notice, $service_phone,
                         $description, $date_info, $sku)
    {
        if (!$date_info instanceof DateInfo)
            exit("date_info Error");
        if (!$sku instanceof Sku)
            exit("sku Error");
        //if (!is_int($code_type))
        //exit("code_type must be integer");
        $this->logo_url = $logo_url;
        $this->brand_name = $brand_name;
        $this->code_type = $code_type;
        $this->title = $title;
        $this->color = $color;
        $this->notice = $notice;
        $this->service_phone = $service_phone;
        $this->description = $description;
        $this->date_info = $date_info;
        $this->sku = $sku;

    }

    function set_sub_title($sub_title)
    {
        $this->sub_title = $sub_title;
    }

    function set_use_limit($use_limit)
    {
        if (!is_int($use_limit))
            exit("use_limit must be integer");
        $this->use_limit = $use_limit;
    }

    function set_get_limit($get_limit)
    {
        if (!is_int($get_limit))
            exit("get_limit must be integer");
        $this->get_limit = $get_limit;
    }

    function set_use_custom_code($use_custom_code)
    {
        $this->use_custom_code = $use_custom_code;
    }

    function set_bind_openid($bind_openid)
    {
        $this->bind_openid = $bind_openid;
    }

    function set_can_share($can_share)
    {
        $this->can_share = $can_share;
    }

    function set_location_id_list($location_id_list)
    {
        $this->location_id_list = $location_id_list;
    }

    function set_url_name_type($url_name_type)
    {
        if (!is_int($url_name_type))
            exit("url_name_type must be int");
        $this->url_name_type = $url_name_type;
    }

    function set_custom_url($custom_url)
    {
        $this->custom_url = $custom_url;
    }
}

;

class CardBase
{
    public function __construct($base_info)
    {
        $this->base_info = $base_info;
    }
}

;

class GeneralCoupon extends CardBase
{
    function set_default_detail($default_detail)
    {
        $this->default_detail = $default_detail;
    }
}

;

class Groupon extends CardBase
{
    function set_deal_detail($deal_detail)
    {
        $this->deal_detail = $deal_detail;
    }
}

;

class Discount extends CardBase
{
    function set_discount($discount)
    {
        $this->discount = $discount;
    }
}

;

class Gift extends CardBase
{
    function set_gift($gift)
    {
        $this->gift = $gift;
    }
}

;

class Cash extends CardBase
{
    function set_least_cost($least_cost)
    {
        $this->least_cost = $least_cost;
    }

    function set_reduce_cost($reduce_cost)
    {
        $this->reduce_cost = $reduce_cost;
    }
}

;

class MemberCard extends CardBase
{
    function set_supply_bonus($supply_bonus)
    {
        $this->supply_bonus = $supply_bonus;
    }

    function set_supply_balance($supply_balance)
    {
        $this->supply_balance = $supply_balance;
    }

    function set_bonus_cleared($bonus_cleared)
    {
        $this->bonus_cleared = $bonus_cleared;
    }

    function set_bonus_rules($bonus_rules)
    {
        $this->bonus_rules = $bonus_rules;
    }

    function set_balance_rules($balance_rules)
    {
        $this->balance_rules = $balance_rules;
    }

    function set_prerogative($prerogative)
    {
        $this->prerogative = $prerogative;
    }

    function set_bind_old_card_url($bind_old_card_url)
    {
        $this->bind_old_card_url = $bind_old_card_url;
    }

    function set_activate_url($activate_url)
    {
        $this->activate_url = $activate_url;
    }
}

;

class ScenicTicket extends CardBase
{
    function set_ticket_class($ticket_class)
    {
        $this->ticket_class = $ticket_class;
    }

    function set_guide_url($guide_url)
    {
        $this->guide_url = $guide_url;
    }
}

;

class MovieTicket extends CardBase
{
    function set_detail($detail)
    {
        $this->detail = $detail;
    }
}

;

class Card
{  //工厂
    private $CARD_TYPE = Array("GENERAL_COUPON",
        "GROUPON", "DISCOUNT",
        "GIFT", "CASH", "MEMBER_CARD",
        "SCENIC_TICKET", "MOVIE_TICKET");

    function __construct($card_type, $base_info)
    {
        if (!in_array($card_type, $this->CARD_TYPE))
            exit("CardType Error");
        if (!$base_info instanceof BaseInfo)
            exit("base_info Error");
        $this->card_type = $card_type;
        switch ($card_type) {
            case $this->CARD_TYPE[0]:
                $this->general_coupon = new GeneralCoupon($base_info);
                break;
            case $this->CARD_TYPE[1]:
                $this->groupon = new Groupon($base_info);
                break;
            case $this->CARD_TYPE[2]:
                $this->discount = new Discount($base_info);
                break;
            case $this->CARD_TYPE[3]:
                $this->gift = new Gift($base_info);
                break;
            case $this->CARD_TYPE[4]:
                $this->cash = new Cash($base_info);
                break;
            case $this->CARD_TYPE[5]:
                $this->member_card = new MemberCard($base_info);
                break;
            case $this->CARD_TYPE[6]:
                $this->scenic_ticket = new ScenicTicket($base_info);
                break;
            case $this->CARD_TYPE[8]:
                $this->movie_ticket = new MovieTicket($base_info);
                break;
            default:
                exit("CardType Error");
        }
        return true;
    }

    function get_card()
    {
        switch ($this->card_type) {
            case $this->CARD_TYPE[0]:
                return $this->general_coupon;
            case $this->CARD_TYPE[1]:
                return $this->groupon;
            case $this->CARD_TYPE[2]:
                return $this->discount;
            case $this->CARD_TYPE[3]:
                return $this->gift;
            case $this->CARD_TYPE[4]:
                return $this->cash;
            case $this->CARD_TYPE[5]:
                return $this->member_card;
            case $this->CARD_TYPE[6]:
                return $this->scenic_ticket;
            case $this->CARD_TYPE[8]:
                return $this->movie_ticket;
            default:
                exit("GetCard Error");
        }
    }

    function toJson()
    {
        return "{ \"card\":" . json_encode($this, JSON_UNESCAPED_UNICODE) . "}";
    }
}

    class Create_wxcard{
        private $return;
        private $share_friends;
        private $res;
        function __construct($param)
        {
            $this->param = $param;
            $this->res = $param['token'];
            $this->share_friends = $param['share_friends'];
            $mode = D('Access_token_expires');
            $this->access_token = $mode->get_access_token();
        }

        function set_config()
        {
            $return['card_type'] = 'MEMBER_CARD';
            $return['member_card']['background_pic_url'] = $this->param['background_pic_url'];
            $return['member_card']['base_info'] = array(
                "logo_url" => $this->param['logo_url'],
                "brand_name" => $this->param['brand_name'],
                "code_type" => "CODE_TYPE_QRCODE",
                "title" =>  $this->param['title'],
                "color" =>   $this->param['color'],
                "notice" =>  $this->param['notice'],
                "service_phone" => $this->param['phone'],
                "description" => $this->param['description'],
                "date_info" => array(
                    "type" => "DATE_TYPE_PERMANENT"
                ),
                "sku" => array(
                    "quantity" =>100000000
                ),
                "get_limit" => 1,
                "use_custom_code" => false,
                "can_give_friend" => false,
                "center_title" => $this->param['center_title'],
                "center_sub_title" => $this->param['center_sub_title'],
                "center_url" => $this->param['center_url'],

                "custom_url_name" => $this->param['custom_url_name'],
                "custom_url" => $this->param['custom_url'],
                "custom_url_sub_title" => $this->param['custom_url_sub_title'],
                "promotion_url_sub_title" => $this->param['promotion_url_sub_title'],
                "promotion_url_name" => $this->param['promotion_url_name'],
                "promotion_url" => $this->param['promotion_url'],
                // "source" => $this->param['source']
            );
            $return['member_card']['advanced_info'] = array(
                "text_image_list" => $this->param['text_image_list'],
                "business_service" => $this->param['business_service'],
            );

            $return['member_card']['supply_bonus'] = false;
            $return['member_card']['custom_field1'] = array(
                'name_type'=> 'FIELD_NAME_TYPE_COUPON',
                'url'=> $this->param['coupon_url'],
            );
            $return['member_card']['custom_field2'] = array(
                'name'=> '我的余额',
                'url'=> $this->param['balance_url'],
            );
            if($this->param['custom_cell1_name']!=''){
                $return['member_card']['custom_cell1'] = array(
                    'name'=> $this->param['custom_cell1_name'],
                    'tips'=> $this->param['custom_cell1_tips'],
                    'url'=> $this->param['custom_cell1_url'],
                );
            }
            if($this->param['custom_cell2_name']!='') {
                $return['member_card']['custom_cell2'] = array(
                    'name' => $this->param['custom_cell2_name'],
                    'tips' => $this->param['custom_cell2_tips'],
                    'url'  => $this->param['custom_cell2_url'],
                );
            }
            //if($this->param['custom_cell3_name']!='') {
            //    $return['member_card']['custom_cell3'] = array(
            //        'name' => $this->param['custom_cell3_name'],
            //        'tips' => $this->param['custom_cell3_tips'],
            //        'url'  => $this->param['custom_cell3_url'],
            //    );
            //}
            $return['member_card']['supply_bonus'] = true;
            $return['member_card']['bonus_url'] = $this->param['bonus_url'];
           $return['member_card']['supply_balance'] = false;
//            $return['member_card']['balance_url'] = $this->param['balance_url'];
            if($this->param['prerogative']!=''){
                $return['member_card']['prerogative'] = $this->param['prerogative'];
            }
            $return['member_card']['auto_activate'] = true;
            $return['member_card']['discount'] = $this->param['discount'];
            $this->return  = $return;
            return $return;
        }

        function create(){
            $card = $this->set_config();
            $json =  "{ \"card\":" . json_encode($card, JSON_UNESCAPED_UNICODE) . "}";

            $return = $this->httpRequest('https://api.weixin.qq.com/card/create?access_token='.$this->res['access_token'],'post',$json);

            $return = json_decode($return[1],true);

            $date_qrcode['action_name'] = 'QR_CARD';
            $date_qrcode['action_info'] = array('card'=>array('card_id'=>$return['card_id']));
            $qrcode_url = $this->httpRequest('https://api.weixin.qq.com/card/qrcode/create?access_token='.$this->res['access_token'],'post',json_encode($date_qrcode,JSON_UNESCAPED_UNICODE));
            $qrcode_url = json_decode($qrcode_url[1],true);

            $ticket = $this->httpRequest('https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=wx_card&access_token='.$this->res['access_token']);
            $ticket = json_decode($ticket[1],true);

            return array('return' =>$return ,'ticket'=>$ticket,'qrcode_url'=>$qrcode_url );

        }
        function update(){
            $card = $this->set_config();
            unset(
                $card['card_type']
                , $card['member_card']['base_info']['brand_name']
                ,$card['member_card']['base_info']['sku']
                ,$card['member_card']['custom_field2']
                , $card['member_card']['base_info']['can_give_friend']
                ,$card['member_card']['base_info']['use_custom_code']
                ,$card['member_card']['supply_balance']
            );
            $card['card_id'] = $this->param['card_id'];
            $json =   json_encode($card, JSON_UNESCAPED_UNICODE) ;

            $return = $this->httpRequest('https://api.weixin.qq.com/card/update?access_token='.$this->res['access_token'],'post',$json);
            $return = json_decode($return[1],true);

            return array('return' =>$return  );

        }

        function upload_image($images){
            $url="https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$this->access_token;
            $this->httpRequest($url,$_FILES);
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

class Create_card{
    private $return;
    private $share_friends;
    private $res;
    function __construct($param)
    {
        $this->param = $param;
        $this->res = $param['res'];
        $this->share_friends = $param['share_friends'];
        $mode = D('Access_token_expires');
        $this->access_token = $mode->get_access_token();
    }

    function set_config()
    {
        $return['card_type'] = strtoupper($this->param['card_type']);
        $return['coupon']['base_info'] = array(
            "logo_url" => $this->param['logo_url'],
            "brand_name" => $this->param['brand_name'],
            "code_type" => "CODE_TYPE_NONE",
            "title" =>  $this->param['title'],
            "color" =>   $this->param['color'],
            "notice" => $this->param['notice'],
            "service_phone" => $this->param['phone'],
            "description" => $this->param['description'],
            "date_info" => array(
                "type" => "DATE_TYPE_FIX_TIME_RANGE",
                "begin_timestamp" => $this->param['begin_time'],
                "end_timestamp" => $this->param['end_time']
            ),
            "sku" => array(
                "quantity" => $this->param['num']
            ),
            "get_limit" => $this->param['limit'],
            "use_custom_code" => false,
            "bind_openid" => false, //指定用户
            "can_share" => true,
            "can_give_friend" => true,
            "center_title" => $this->param['center_title'],
            "center_sub_title" => $this->param['center_sub_title'],
            "center_url" => $this->param['center_url'],
            "custom_url_name" => $this->param['custom_url_name'],
            "custom_url" => $this->param['custom_url'],
            "custom_url_sub_title" => $this->param['custom_url_sub_title'],
            "promotion_url_name" => $this->param['promotion_url_name'],
            "promotion_url" => $this->param['promotion_url'],
           // "source" => $this->param['source']
        );
        $return['coupon']['advanced_info'] = array(
            "abstract" => array(
                "abstract" => $this->param['abstract'],
                "icon_url_list" => $this->param['icon_url_list'],
            ),
            "text_image_list" => $this->param['text_image_list'],
            "business_service" => $this->param['business_service'],
        );
        $return[$this->param['card_type']] = $return['coupon'];
        unset($return['coupon']);
        if($this->param['card_type']=='discount'){
            $return['discount']['discount'] = $this->param['discount'];
        }else{
            $return['cash']['least_cost'] = $this->param['least_cost'];
            $return['cash']['reduce_cost'] = $this->param['reduce_cost'];
        }

        if($this->share_friends){
            $return['cash']['advanced_info']['share_friends'] = true;
            $return['cash']['base_info']['can_share'] = false;
            $return['cash']['base_info']['can_give_friend'] = false;
        }
        $this->return  = $return;
        return $return;
    }

    function create(){
        $card = $this->set_config();
        $json =  "{ \"card\":" . json_encode($card, JSON_UNESCAPED_UNICODE) . "}";
        $return = $this->httpRequest('https://api.weixin.qq.com/card/create?access_token='.$this->res['access_token'],'post',$json);
        $return = json_decode($return[1],true);
        $date_qrcode['action_name'] = 'QR_CARD';
        $date_qrcode['action_info'] = array('card'=>array('card_id'=>$return['card_id']));
        $qrcode_url = $this->httpRequest('https://api.weixin.qq.com/card/qrcode/create?access_token='.$this->res['access_token'],'post',json_encode($date_qrcode,JSON_UNESCAPED_UNICODE));
        $qrcode_url = json_decode($qrcode_url[1],true);
        $ticket = $this->httpRequest('https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=wx_card&access_token='.$this->res['access_token']);
        $ticket = json_decode($ticket[1],true);
        return array('return' =>$return ,'ticket'=>$ticket,'qrcode_url'=>$qrcode_url );

    }

    function upload_image($images){
        $url="https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$this->access_token;
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


