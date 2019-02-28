<?php
/*
 * 通联云
 */
class AccountDeposit {
    public $typeName;
    public $userName;
    public $userPhone;
    public $userType;
    public $realName;
    public $identityNo;

    public $backUrl;
    public $actionName;
    public $serverAddress;
    public $privateKey;
    public $publicKey;

    public $DepositClass;

    public function __construct($typeName)
    {
        $this->typeName = $typeName;
        // import("@.ORG.AccountDeposit.{$typeName}");
        spl_autoload_register(function ($class_name) {  
            $class_name = str_replace('\\','/', $class_name); 
            require_once $class_name . '.class.php'; 
        });  
        $this->DepositClass = new $typeName();

    }

    public function createMember($userID)
    {
        return  $this->DepositClass->createMember($userID);
    }

    public function setRealName()
    {
        return $this->DepositClass->setRealName();
    }

    public function sendSMSCode($param)
    {
        return $this->DepositClass->sendSMSCode($param);
    }

    public function bindPhone($phone)
    {
        return $this->DepositClass->bindPhone($phone);
    }

    public function changePhone($params)
    {
        return $this->DepositClass->changePhone($params);
    }

    public function bindBankCard($params)
    {
        return $this->DepositClass->bindBankCard($params);
    }

    public function getDeposit(){
        return $this->DepositClass;
    }

 
    //签名
    public function Sign(){
       $this->privateKey;     
    }

    //验签
    public function verifySign()
    {
      
    }
}

