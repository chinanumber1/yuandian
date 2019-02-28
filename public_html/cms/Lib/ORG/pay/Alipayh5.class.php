<?php

class Alipayh5
{

    protected $order_info;

    protected $pay_money;

    protected $pay_type;

    protected $is_mobile;

    protected $user_info;

    protected $config;

    public function __construct($order_info=array(),$pay_money=0,$pay_type='alipay',$pay_config=array(),$user_info=array(),$is_mobile=0)
    {
        $this->order_info = $order_info;
        
        $this->pay_money = $pay_money;
        
        $this->pay_type = $pay_type;
        
        $this->is_mobile = $is_mobile;
        
        $this->user_info = $user_info;
        
        $this->config = array(
            // 应用ID,您的APPID。
            'app_id' => $pay_config['pay_alipayh5_appid'],
            
            // 商户私钥，您的原始格式RSA私钥
            'merchant_private_key' => $pay_config['pay_alipayh5_merchant_private_key'],
            
            // 异步通知地址
            'notify_url' => C('config.site_url') . '/source/wap_alipayh5_notice.php',
            
            // 同步跳转
            'return_url' => C('config.site_url') . '/source/wap_alipayh5_return.php',
            
            // 编码格式
            'charset' => "UTF-8",
            
            // 签名方式
            'sign_type' => $pay_config['pay_alipayh5_sign_type'],
            
            // 支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
            
            // 支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => $pay_config['pay_alipayh5_public_key']
        );
    }

    public function pay()
    {
        if ($this->is_mobile) {
            return $this->mobile_pay();
        } else {
            return $this->web_pay();
        }
    }

    public function mobile_pay()
    {
        // 商户订单号，商户网站订单系统中唯一订单号，必填
        $contentData = array(
            'productCode' => 'QUICK_WAP_PAY'
        );
        
        $contentData['out_trade_no'] = $this->order_info['order_type'] . '_' . $this->order_info['order_id'];
        
        // 订单名称，必填
        $contentData['subject'] = '订单编号' . $this->order_info['order_id'];
        
        // 付款金额，必填
        $contentData['total_amount'] = $this->pay_money;
        
        // 商品描述，可空
        $contentData['body'] = '订单编号' . $this->order_info['order_id'];
        
        // 超时时间
        $contentData['timeout_express'] = '1d';
        
        $contentJson = json_encode($contentData, JSON_UNESCAPED_UNICODE);
        
        import("@.ORG.pay.AlipayWapH5.lib.AlipayTradeService");
        
        $payResponse = new AlipayTradeService($this->config);
        
        $data['return_url'] = $this->config['return_url'];
        
        $data['notify_url'] = $this->config['notify_url'];
        
        $data['biz_content'] = $contentJson;
        
        $data['api_version'] = '1.0';
        
        $data['method'] = 'alipay.trade.wap.pay';
        
        $result = $payResponse->wapPay($data);
        
        return array(
            'error' => 0,
            'form' => $result
        );
    }

    public function web_pay()
    {}
    // 异步通知
    public function notice_url()
    {
        if ($this->is_mobile) {
            return $this->mobile_notice();
        } elseif ($this->is_mobile == 2) {
            return $this->app_notice();
        } else {
            return $this->web_notice();
        }
    }

    public function mobile_notice()
    {
        unset($_POST['pay_type'], $_GET['pay_type']);
        
        import("@.ORG.pay.AlipayWapH5.lib.AlipayTradeService");
        $alipaySevice = new AlipayTradeService($this->config);
        $result = $alipaySevice->check($_POST);
        /*
         * 实际验证过程建议商户添加以下校验。
         * 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
         * 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
         * 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
         * 4、验证app_id是否为该商户本身。
         */
        if ($result) { // 验证成功
                       // 请在这里加上商户的业务逻辑程序代
                       
            // ——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
                       
            // 获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
                       
            // 商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            
            // 支付宝交易号
            $trade_no = $_POST['trade_no'];
            
            // 交易状态
            $trade_status = $_POST['trade_status'];
            
            $pay_money = $_POST['total_amount'];
            
            $order_id_arr = explode('_', $out_trade_no);
            $order_param['pay_type'] = 'alipayh5';
            $order_param['is_mobile'] = '1';
            $order_param['order_type'] = $order_id_arr[0];
            $order_param['order_id'] = $order_id_arr[1];
            $order_param['third_id'] = $trade_no;
            $order_param['trade_status'] = $_POST['trade_status'];
            $order_param['pay_money'] = $pay_money;
            $order_param['return'] = 1;

            
            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                return array(
                    'error' => 1
                );
                // 判断该笔订单是否在商户网站中已经做过处理
                // 如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                // 请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                // 如果有做过处理，不执行商户的业务程序
                
                // 注意：
                // 退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            } elseif ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                // 判断该笔订单是否在商户网站中已经做过处理
                // 如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                // 请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                // 如果有做过处理，不执行商户的业务程序
                // 注意：
                // 付款完成后，支付宝系统发送该交易状态通知
            } else {
                return array(
                    'error' => 1
                );
            }
            return array(
                'error' => 0,
                'order_param' => $order_param
            );
            echo "success"; // 请不要修改或删除
        } else {
            return array(
                'error' => 1
            );
            // 验证失败
            echo "fail"; // 请不要修改或删除
        }
    }

    public function web_notice()
    {
        exit('success');
    }
    
    // 跳转通知
    public function return_url()
    {
        if ($this->is_mobile) {
            return $this->mobile_return();
        } else {
            return $this->web_return();
        }
    }

    public function mobile_return()
    {
        unset($_GET['pay_type']);
        import("@.ORG.pay.AlipayWapH5.lib.AlipayTradeService");
        $alipaySevice = new AlipayTradeService($this->config);
        $result = $alipaySevice->check($_GET);
        /*
         * 实际验证过程建议商户添加以下校验。
         * 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
         * 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
         * 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
         * 4、验证app_id是否为该商户本身。
         */
        if ($result) {
            // 验证成功
            
            // 请在这里加上商户的业务逻辑程序代码
            // ——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            // 获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            // 商户订单号
            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
            
            // 支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            
            $pay_money = $_GET['total_amount'];
            
            $order_id_arr = explode('_', $out_trade_no);
            $order_param['pay_type'] = 'alipayh5';
            $order_param['is_mobile'] = '1';
            $order_param['order_type'] = $order_id_arr[0];
            $order_param['order_id'] = $order_id_arr[1];
            $order_param['third_id'] = $trade_no;
            $order_param['pay_money'] = $pay_money;
            $order_param['return'] = 1;
            return array(
                'error' => 0,
                'order_param' => $order_param
            );
        } else {
            // 验证失败
            return array(
                'error' => 1,
                'msg' => '支付错误：认证签名失败！请联系管理员。'
            );
            echo "验证失败";
        }
    }

    public function app_notice($config)
    {}

    public function app_return_url($config)
    {}

    public function web_return()
    {}

    public function query_order()
    {
        if ($this->is_mobile == 1) {
            return $this->mobile_query_order();
        } elseif ($this->is_mobile == 2) {
            return $this->app_query_order();
        } else {
            return $this->web_query_order();
        }
    }

    public function mobile_query_order()
    {
        return $this->mobile_return();
    }

    public function app_query_order()
    {}

    public function web_query_order()
    {}

    public function refund()
    {
        // 商户订单号，商户网站订单系统中唯一订单号，必填
        $contentData = array();
        
        // 商户订单号和支付宝交易号不能同时为空。 trade_no、 out_trade_no如果同时存在优先取trade_no
        // 商户订单号，和支付宝交易号二选一
        $contentData['out_trade_no'] = $this->order_info['order_type'] . '_' . $this->order_info['order_id'];
        
        // 支付宝交易号，和商户订单号二选一
        $contentData['trade_no'] = $this->order_info['third_id'];
        
        // 退款金额，不能大于订单总金额
        $contentData['refund_amount'] = $this->pay_money;
        
        // 退款的原因说明
        $contentData['refund_reason'] = '正常退款';
        
        // 标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
        $contentData['out_request_no'] = $this->order_info['order_type'] . '_' . $this->order_info['order_id'] . '_' . $_SERVER['REQUEST_TIME'];
        
        $contentJson = json_encode($contentData, JSON_UNESCAPED_UNICODE);
        
        import("@.ORG.pay.AlipayWapH5.lib.AlipayTradeService");
        
        $payResponse = new AlipayTradeService($this->config);
        
        $data['return_url'] = $this->config['return_url'];
        
        $data['notify_url'] = $this->config['notify_url'];
        
        $data['biz_content'] = $contentJson;
        
        $data['api_version'] = '1.0';
        
        $data['method'] = 'alipay.trade.refund';
        
        $result = $payResponse->refund($data);
        
        if ($result->code == 10000) {
            $refund_param['refund_id'] = $contentData['out_request_no'];
            $refund_param['refund_time'] = $result->gmt_refund_pay;
            return array(
                'error' => 0,
                'type' => 'ok',
                'msg' => '退款申请成功！请注意查收“支付宝”给您发的退款通知。',
                'refund_param' => $refund_param
            );
        } else {
            $refund_param['err_code'] = $result->code;
            $refund_param['err_msg'] = $result->msg;
            $refund_param['refund_time'] = time();
            return array(
                'error' => 1,
                'type' => 'fail',
                'msg' => $result->msg,
                'refund_param' => $refund_param
            );
        }
    }

    public function refund_verify()
    {}
}
?>