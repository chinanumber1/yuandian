<?php
include_once 'LowFeePay/Config.class.php';

interface PayMethod{
    public function pay($order,$pay_info);
    public function refund($order);
    public function check($order);
    public function notice($sign_data);
}

 class LowFeePay implements PayMethod {
    private $adaptee;
    function __construct($pay_type='juhepay') {
        if($pay_type=='juhepay'){
            $pay = new JuHePay();
        }
        $this->adaptee = $pay;
    }

     public function __set($property, $value) {
         $this->adaptee->$property=$value;
     }


     public function pay($order,$pay_info=''){
        return $this->adaptee->pay($order,$pay_info);
    }

    public function set_config($param){
        return $this->adaptee->set_config($param);
    }
    public function refund($order){
        return $this->adaptee->refund($order);
    }
    public function check($order){
        return $this->adaptee->check($order);
    }
    public function notice($sign_data){
       return  $this->adaptee->notice($sign_data);
    }

}


?>
