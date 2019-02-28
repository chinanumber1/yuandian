<?php

if (is_file(__DIR__ . '/OSS/autoload.php')) {
    require_once __DIR__ . '/OSS/autoload.php';
}

use OSS\OssClient;
use OSS\Core\OssException;
use OSS\Core\OssUtil;

class Oss_client{
	//SDK API类
    public $api;
	//阿里云AccessKeyId：
	public $accessKeyId;
	//阿里云AccessKeySecret：
	public $accessKeySecret;
    //Bucket 域名：Bucket 域名，即用于用户访问该文件的域名。在OSS Bucket概览里可以查看。可以使用阿里云默认域名，也可以自行绑定域名。（自行绑定请注意需要上传HTTPS证书）
    public $endpoint;
    // 运行示例程序所使用的存储空间。示例程序会在这个存储空间中创建一些文件。
    public $bucket;
    //错误信息
    public $message;
	
	//架构函数
	public function __construct($accessKeyId, $accessKeySecret, $endpoint, $bucket){
		//设置 SDK 需要的参数
		$this->accessKeyId   = $accessKeyId;
		$this->accessKeySecret = $accessKeySecret;
        $this->endpoint = $endpoint;
        $this->bucket = $bucket;
		
        // true为开启CNAME。CNAME是指将自定义域名绑定到存储空间上。
        $this->api = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint, false);
	}
}
?>