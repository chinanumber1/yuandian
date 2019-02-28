<?php
//require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidBroadcast.php');
//require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidFilecast.php');
require_once(dirname(__FILE__) . '/' . 'umenPush/android/AndroidGroupcast.php');
require_once(dirname(__FILE__) . '/' . 'umenPush/android/AndroidUnicast.php');
//require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidCustomizedcast.php');
//require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSBroadcast.php');
//require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSFilecast.php');
//require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSGroupcast.php');
require_once(dirname(__FILE__) . '/' . 'umenPush/ios/IOSUnicast.php');
//require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSCustomizedcast.php');

class UmenPush {
	protected $appkey           = NULL;
	protected $appMasterSecret     = NULL;
	protected $timestamp        = NULL;
	protected $validation_token = NULL;

	function __construct($key, $secret) {
		$this->appkey = $key;
		$this->appMasterSecret = $secret;
		$this->timestamp = strval(time());
	}

	function push($params){
		if($params['device_token']){
			$this->sendAndroid($params);
		}else{
			$this->sendAndroidGroupcast($params);
		}
	}
	// 只针对单播
	function sendAndroid($param) {
		$device_id = $param['audience']['tag'][0];
		$title =$param['message']['title'];
		$content = $param['message']['msg_content'];

		if($param['send_type']=='storestaff'){
			$des = '店员通知';
			$appConfig  =   D('Appapi_app_config')->where(array('var'=>'storestaff_android_package_name'))->field(true)->find();
		}else{
			$des = '配送员通知';
			$appConfig  =   D('Appapi_app_config')->where(array('var'=>'deliver_android_package_name'))->field(true)->find();
		}

		$package_name = $appConfig['value'];
//		$activity_path =$package_name.'.activity.LauncherActivity';
		$activity_path =$package_name.'.activity.LauncherActivity';
		try {
			$unicast = new AndroidUnicast();
			$unicast->setAppMasterSecret($this->appMasterSecret);
			$unicast->setPredefinedKeyValue("appkey",           $this->appkey);
			$unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set your device tokens here
			$unicast->setPredefinedKeyValue("device_tokens",   $param['device_token']);
			$unicast->setPredefinedKeyValue("ticker",          $des);
			$unicast->setPredefinedKeyValue("title",            $title);
			$unicast->setPredefinedKeyValue("text",            $content);
			$unicast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device.
			// For how to register a test device, please see the developer doc.
			$unicast->setPredefinedKeyValue("production_mode", "true");
			$unicast->setPredefinedKeyValue("description", $des);
			$unicast->setPredefinedKeyValue("mipush", "true");
			$unicast->setPredefinedKeyValue("mi_activity",$activity_path);
			// Set extra fields
			$unicast->setExtraField("content", json_encode($param['notification']['android']));
			fdump($unicast,'ss');
//			print("Sending unicast notification, please wait...\r\n");
			$unicast->send();
//			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

	function sendAndroidGroupcast($param) {

		$device_id = $param['audience']['tag'][0];
		$title =$param['message']['title'];
		$content = $param['message']['msg_content'];

		if($param['send_type']=='storestaff'){
			$des = '店员通知';
			$appConfig  =   D('Appapi_app_config')->where(array('var'=>'storestaff_android_package_name'))->field(true)->find();
		}else{
			$des = '配送员通知';
			$appConfig  =   D('Appapi_app_config')->where(array('var'=>'deliver_android_package_name'))->field(true)->find();
		}
		$package_name = $appConfig['value'];
		$activity_path =$package_name.'.activity.LauncherActivity';
		try {

			$filter = 	array(
					"where" => 	array(
							"and"=>array(
									array(
											"tag" => $device_id
									),

							)
					)
			);

			$groupcast = new AndroidGroupcast();
			$groupcast->setAppMasterSecret($this->appMasterSecret);
			$groupcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$groupcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set the filter condition
			$groupcast->setPredefinedKeyValue("filter",           $filter);
			$groupcast->setPredefinedKeyValue("ticker",          $des);
			$groupcast->setPredefinedKeyValue("title",            $title);
			$groupcast->setPredefinedKeyValue("text",            $content);
			$groupcast->setPredefinedKeyValue("after_open",       "go_app");

			$groupcast->setPredefinedKeyValue("production_mode", "true");
			$groupcast->setPredefinedKeyValue("description", $des);
			$groupcast->setPredefinedKeyValue("mipush", "true");
			$groupcast->setPredefinedKeyValue("mi_activity",$activity_path);
			// Set extra fields
			$groupcast->setExtraField("content", json_encode($param['notification']['android']));
			dump($groupcast);
			print("Sending groupcast notification, please wait...\r\n");
			$groupcast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}



	function sendIOS() {
		try {
			$unicast = new IOSUnicast();
			$unicast->setAppMasterSecret($this->appMasterSecret);
			$unicast->setPredefinedKeyValue("appkey",           $this->appkey);
			$unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set your device tokens here
			$unicast->setPredefinedKeyValue("device_tokens",    "xx");
			$unicast->setPredefinedKeyValue("alert", "IOS 单播测试");
			$unicast->setPredefinedKeyValue("badge", 0);
			$unicast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$unicast->setPredefinedKeyValue("production_mode", "false");
			// Set customized fields
			$unicast->setCustomizedField("test", "helloworld");
			print("Sending unicast notification, please wait...\r\n");
			$unicast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}


}

// Set your appkey and master secret here
//$demo = new Demo("your appkey", "your app master secret");
//$demo->sendAndroidUnicast();
/* these methods are all available, just fill in some fields and do the test
 * $demo->sendAndroidBroadcast();
 * $demo->sendAndroidFilecast();
 * $demo->sendAndroidGroupcast();
 * $demo->sendAndroidCustomizedcast();
 * $demo->sendAndroidCustomizedcastFileId();
 *
 * $demo->sendIOSBroadcast();
 * $demo->sendIOSUnicast();
 * $demo->sendIOSFilecast();
 * $demo->sendIOSGroupcast();
 * $demo->sendIOSCustomizedcast();
 */