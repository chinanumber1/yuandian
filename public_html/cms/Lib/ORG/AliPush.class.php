<?php
final class AliPush
{

    public function __construct()
    {

    }

    public function push($param=array())
    {
        $accessKeyId =C('config.alipush_accessKeyId');
        $accessKeySecret =C('config.alipush_accessKeySecret');
        $device_id = $param['audience']['tag'][0];
        $title =$param['message']['title'];
        $content = $param['message']['msg_content'];
        if($param['send_type']=='storestaff'){
            $appKey =C('config.alipush_appKey');
            $appConfig  =   D('Appapi_app_config')->where(array('var'=>'storestaff_android_package_name'))->field(true)->find();
        }else{

            $appKey =C('config.alipush_deliver_appKey');
            $appConfig  =   D('Appapi_app_config')->where(array('var'=>'deliver_android_package_name'))->field(true)->find();

        }



        $storestaff_android_package_name = $appConfig['value']?$appConfig['value']:'com.pigcms.sendman';
        $activity_path =$storestaff_android_package_name.'.activity.ThirdPushPopupActivity';



        include_once 'alipush/aliyun-php-sdk-core/Config.php';
        include_once 'alipush/aliyun-php-sdk-push/Push/Request/V20160801/PushNoticeToAndroidRequest.php';
        $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $accessKeyId, $accessKeySecret);
        $client = new DefaultAcsClient($iClientProfile);
        $request = new \Push\Request\V20160801\PushRequest();


        // 推送目标
        $request->setAppKey($appKey);
        $request->setTarget("TAG"); //推送目标: DEVICE:推送给设备; ACCOUNT:推送给指定帐号,TAG:推送给自定义标签; ALL: 推送给全部
        $request->setTargetValue($device_id); //根据Target来设定，如Target=device, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
        $request->setDeviceType("ANDROID"); //设备类型 ANDROID iOS ALL.
        $request->setPushType("NOTICE"); //消息类型 MESSAGE NOTICE
        $request->setTitle($title); // 消息的标题
        $request->setBody($content); // 消息的内容


        $request->setAndroidNotifyType("BOTH");//通知的提醒方式 "VIBRATE" : 震动 "SOUND" : 声音 "BOTH" : 声音和震动 NONE : 静音
        $request->setAndroidNotificationBarType(1);//Android通知音乐
        $request->setAndroidMusic("default");//Android通知音乐

        $request->setAndroidOpenType("APPLICATION");//点击通知后动作 "APPLICATION" : 打开应用 "ACTIVITY" : 打开AndroidActivity "URL" : 打开URL "NONE" : 无跳转

        $request->setAndroidActivity($activity_path);//设定通知打开的activity，仅当AndroidOpenType="Activity"有效
        $request->setAndroidRemind(true);
        $request->setAndroidPopupActivity($activity_path);//设置该参数后启动小米托管弹窗功能, 此处指定通知点击后跳转的Activity（托管弹窗的前提条件：1. 集成小米辅助通道；2. StoreOffline参数设为true
        $request->setAndroidPopupTitle($title);
        $request->setAndroidPopupBody($content);
        $request->setAndroidExtParameters(json_encode($param['notification']['android'])); // 设定android类型设备通知的扩展属性

        // 推送控制
        $pushTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 second'));//延迟3秒发送
        $request->setPushTime($pushTime);
        $expireTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 day'));//设置失效时间为1天
        $request->setExpireTime($expireTime);
        $request->setStoreOffline("true"); // 离线消息是否保存,若保存, 在推送时候，用户即使不在线，下一次上线则会收到
        // fdump($request,'sdf',1);
        $response = $client->getAcsResponse($request);
        // fdump($response,'sdf',1);
    }
}
?>
