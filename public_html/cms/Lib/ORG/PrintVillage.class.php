<?php

class PrintVillage
{

    public $orderPrint = null;
    
    public $payarr = array(
                'alipay' => '支付宝',
                'weixin' => '微信支付',
                'tenpay' => '财付通[wap手机]',
                'tenpaycomputer' => '财付通[即时到帐]',
                'yeepay' => '易宝支付',
                'allinpay' => '通联支付',
                'daofu' => '货到付款',
                'dianfu' => '到店付款',
                'chinabank' => '网银在线',
                'offline' => '线下支付'
            );
    public function __construct()
    {
        $this->orderPrint = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
    }
    
    public function printit($order_id)
    {


        $database_house_village_pay_order = D('House_village_pay_order');
        $order = $database_house_village_pay_order->get_one($order_id);
        if (empty($order)) return false;
        if ($order['paid'] <= 0) return false;

        $prints = $this->getPrints($order['village_id']);
        if (empty($prints)) return false;
  
        $userBind = D('House_village_user_bind')->field(true)->where(array('village_id'=>$order['village_id'],'uid'=>$order['uid']))->find();

        foreach ($prints as $usePrinter) {

            // if ($usePrinter['is_main'] == 0) {
            //     continue;
            // }

            $formatData = $this->getThreeData($usePrinter);

            $width = $formatData['width'];
            $firstSpace = $formatData['one'];
            $secondSpace = $formatData['two'];
            $thirdSpace = $formatData['three'];
            $spaceWidth= $formatData['spaceWidth'];
            
            $format_str = '';
            $format_str .= chr(10) . '订单编号：' . $order['order_id'];
            $format_str .= chr(10) . '业主姓名：' . $userBind['name'];
            $format_str .= chr(10) . '业主电话：' . $userBind['phone'];
            // $format_str .= chr(10) . '业主地址：' . $order['name'];

            $format_str .= chr(10) . '***************************';

            $format_str .= chr(10) . '缴费项目：' . $order['order_name'];
            // $format_str .= chr(10) . '缴费单价：' . $order['name'];
            $format_str .= chr(10) . '缴费总额：' . $order['money'];

            // $format_str .= chr(10) . '缴费面积：' . $order['name'];
            $format_str .= chr(10) . '缴费周期：' . $order['payment_paid_cycle'];

            $format_str .= chr(10) . '***************************';

            if($order['pay_type'] == 0){
                $format_str .= chr(10) . '支付方式：扫码支付';
            }else{
                $format_str .= chr(10) . '支付方式：现金支付';
            }
            
            $format_str .= chr(10) . '支付时间：' . date('Y-m-d H:i:s');
            $format_str .= chr(10) . '谢谢惠顾，欢迎再次光临！';
            $this->orderPrint->newPrint($usePrinter, $format_str);
        }

        return false;
    }
    

    
    private function getThreeData($usePrinter)
    {
        if ($usePrinter['is_big'] == 0 && $usePrinter['paper'] == 0) {
            $width = 16;
            $firstSpace = 4;
            $secondSpace = 9;
            $thirdSpace = 6;
            $spaceWidth = 16;//数量前的所占的字符数
        } elseif ($usePrinter['is_big'] == 1 && $usePrinter['paper'] == 0) {
            $width = 12;
            $firstSpace = 3;
            $secondSpace = 4;
            $thirdSpace = 3;
            $spaceWidth = 10;
        } elseif ($usePrinter['is_big'] == 2 && $usePrinter['paper'] == 0) {
            $width = 8;
            $firstSpace = 1;
            $secondSpace = 2;
            $thirdSpace = 1;
            $spaceWidth = 8;
        } elseif ($usePrinter['is_big'] == 0 && $usePrinter['paper'] == 1) {
            $width = 24;
            $firstSpace = 7;
            $secondSpace = 14;
            $thirdSpace = 12;
            $spaceWidth = 24;
        } elseif ($usePrinter['is_big'] == 1 && $usePrinter['paper'] == 1) {
            $width = 18;
            $firstSpace = 4;
            $secondSpace = 11;
            $thirdSpace = 8;
            $spaceWidth = 18;
        } elseif ($usePrinter['is_big'] == 2 && $usePrinter['paper'] == 1) {
            $width = 12;
            $firstSpace = 3;
            $secondSpace = 4;
            $thirdSpace = 3;
            $spaceWidth = 10;
        }
        return array('width' => $width, 'one' => $firstSpace, 'two' => $secondSpace, 'three' => $thirdSpace, 'spaceWidth' => $spaceWidth);
    }
    
    private function getTwoData($mainPrint)
    {
        if ($mainPrint['is_big'] == 0 && $mainPrint['paper'] == 0) {
            $width = 16;
            $firstSpace = 4;
            $secondSpace = 16;
        } elseif ($mainPrint['is_big'] == 1 && $mainPrint['paper'] == 0) {
            $width = 12;
            $firstSpace = 4;
            $secondSpace = 8;
        } elseif ($mainPrint['is_big'] == 2 && $mainPrint['paper'] == 0) {
            $width = 8;
            $firstSpace = 2;
            $secondSpace = 2;
        } elseif ($mainPrint['is_big'] == 0 && $mainPrint['paper'] == 1) {
            $width = 24;
            $firstSpace = 4;
            $secondSpace = 32;
        } elseif ($mainPrint['is_big'] == 1 && $mainPrint['paper'] == 1) {
            $width = 18;
            $firstSpace = 4;
            $secondSpace = 20;
        } elseif ($mainPrint['is_big'] == 2 && $mainPrint['paper'] == 1) {
            $width = 12;
            $firstSpace = 4;
            $secondSpace = 8;
        }
        return array('width' => $width, 'one' => $firstSpace, 'two' => $secondSpace);
    }

    public static function dstrlen($string)
    {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n ++;
                $noc ++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n ++;
            }
        }
        return $noc;
    }

    public static function cutstr($string, $length = 16, $mod = 32)
    {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n ++;
                $noc ++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n ++;
            }
        }
        $noc = $noc % $mod;
        if ($noc > $length) {
            return $string . chr(10);
        } else {
            return $string;
        }
    }

    public static function format($string, $space)
    {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n ++;
                $noc ++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n ++;
            }
        }
        if ($noc < 4) {
            $space += 1;
        } elseif ($noc > 4) {
            $space = $space - $noc + 4;
        }
        for ($i = 0; $i < $space; $i ++) {
            $string = chr(32) . $string;
        }
        if ($noc < 4) {
            for ($k = 0; $k < (3 - $noc); $k ++) {
                $string = $string . chr(32);
            }
        }
        return $string;
    }
    
    /**
     * 获取社区的打印机列表 (如果社区没有主打印机的话，将最先添加的打印机当做主打印机)
     * @param int $village_id
     * @return number|unknown[]
     */
    private function getPrints($village_id)
    {
        $prints = D('House_village_printer')->where(array('village_id' => $village_id))->order('is_main DESC, pigcms_id ASC')->select();
        $result = array();
        $isMain = 0;
        $firstId = 0;
        foreach ($prints as $print) {
            if ($print['is_main']) {
                $isMain = $print['pigcms_id'];
            } elseif (empty($firstId)) {
                $firstId = $print['pigcms_id'];
            }
            $result[$print['pigcms_id']] = $print;
        }
        if ($isMain == 0) {
            $result[$firstId]['is_main'] = 1;
        }
        return $result;
    }
}
