<?php
$allinyun_config =  array(
    'payMethod'=>array(
        "GATEWAY"=>array('name'=>'网关支付','param'=>array('bankCode'=>'abc','payType'=>1,'amount'=>0)),
        "WITHHOLD_TLT"=>array('name'=>'通联通代扣','param'=>array('amount'=>0,'bankCardNo'=>'')),
        "WECHATPAY_APP"=>array('name'=>'微信APP','param'=>array()),
        "REALNAMEPAY"=>array('name'=>'实名付','param'=>array('amount'=>0,'bankCardNo'=>'')),
        "SCAN_ALIPAY"=>array('name'=>'支付宝扫码支付','param'=>array('payType'=>'A01','amount'=>0)),
        "SCAN_WEIXIN"=>array('name'=>'微信扫码支付','param'=>array('payType'=>'W02','amount'=>0)),
        "WECHAT_PUBLIC"=>array('name'=>'微信JS支付','param'=>array()),
        "ALIPAY_SERV ICE"=>array('name'=>'支付宝JS支付','param'=>array()),
        "BALANCE"=>array('name'=>'账户余额','param'=>array(array('accountSetNo'=>'','amount'=>0))), 
      

        // "POSPAY"=>array('name'=>'订单POS陕西','param'=>array()),
        // "REALNAMEPAY_BATCH"=>array('name'=>'实名付多笔','param'=>array()),bankCardNo 
        // "QQ_WALLET"=>array('name'=>'QQ钱包JS支付','param'=>array()),
        // "CODEPAY_W"=>array('name'=>'微信刷卡支付','param'=>array()),
        // "CODEPAY_A"=>array('name'=>'支付宝刷卡支付','param'=>array()),
        // "CODEPAY_Q"=>array('name'=>'QQ钱包刷卡支付','param'=>array()),
        // "WECHATPAY_H5"=>array('name'=>'微信H5支付','param'=>array()),
        // "POSPAY_SZ"=>array('name'=>'订单POS深圳','param'=>array()),
        // "WECHAT_PUBL IC_ORG"=>array('name'=>'微信JS支付_集团','param'=>array()),
        // "ALIPAY_SERV ICE_ORG"=>array('name'=>'支付宝JS支付_集团','param'=>array()),
        // "QQ_WALLET_ORG"=>array('name'=>'QQ钱包JS支付_集团','param'=>array()),
        // "ORDER_VSPPAY"=>array('name'=>'收银宝POS当面付','param'=>array()),
        // "QUICKPAY_H5"=>array('name'=>'H5快捷支付','param'=>array()),
        // "QUICKPAY_PC"=>array('name'=>'快捷支付','param'=>array()),
    ),

    // 3001 	String 	电商及其它 	代收消费金 
    // 3002 	String 	电商及其它 	代收（佣金/返利）金 
    // 4001 	String 	电商及其它 	代付购买金 
    // 4002 	String 	电商及其它 	代付（佣金/返利）金 
    'tradeCode'=>array('3001','3002','4001','4002'),
    'bank_code'=>array(

//        'chinapay'=>'银联电子商务',
    'boc'=>'中国银行',
    'icbc'=>'中国工商银行',
    'ccb'=>'中国建设银行',
    'abc'=>'中国农业银行',
    'comm'=>'交通银行',
    'cmb'=>'招商银行',
    'ceb'=>'光大银行',
    'cmbc'=>'中国民生银行',
    'citic'=>'中信银行',
    'bos'=>'上海银行' ,
    'spdb'=>'上海浦东发展银行',
//        'sdb'=>'深圳发展银行',
    'hxb'=>'华夏银行',
    'cgb'=>'广东发展银行' ,
    'cib'=>'兴业银行',
//        'bob'=>'北京银行',
    'pingan'=>'平安银行' ,
    'psbc'=>'邮政储蓄',
//        'egb'=>'恒丰银行',
//        'aps'=>'APS' ,
//        'telpshx'=>'陕西 IVR',
//        'aps-citic'=>'中信银行便捷付'
    ),
    'unionCode'=>array(
            array('工商银行','01020000'),
            array('农业银行','01030000'),
            array('中国银行','01040000'),
            array('建设银行','01050000'),
            array('中信银行','03020000'),
            array('光大银行','03030000'),
            array('华夏银行','03040000'),
            array('平安银行','04105840'),
            array('招商银行','03080000'),
            array('兴业银行','03090000'),
            array('浦发银行','03100000'),
            array('邮储银行','01000000'),
            array('宁波银行','04083320'),
            array('南京银行','04243010'),
            array('农信湖南','14385500'),
    ),
);

