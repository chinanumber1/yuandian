<?php
/*
 * 短信发送记录
 *
 */

class SmsAction extends BaseAction
{
    public function index()
    {
		$count = D("Sms_record")->count();
		import('@.ORG.system_page');
		$p = new Page($count, 20);
		$mod = new Model();
		$sql = "SELECT a.name, b.* FROM ". C('DB_PREFIX') . "merchant AS a RIGHT JOIN ". C('DB_PREFIX') . "sms_record AS b ON a.mer_id=b.mer_id ORDER BY pigcms_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$res = $mod->query($sql);
		
		$status = array('0' =>'发送成功', '-1' => '验证失败未购买', '-2' => '短信不足', '-3' => '操作失败', '-4' => '非法字符', '-5' => '内容过多', '-6' => '号码过多', '-7' => '频率过快', '-8' => '号码内容空', '-9' => '账号冻结', '-10' => '禁止频繁单条发送', '-11' => '系统暂定发送', '-12' => '有错误号码', '-13' => '定时时间不对', '-14' => '账号被锁，10分钟后登录', '-15' => '连接失败', '-16' => '禁止接口发送', '-17' => '绑定IP不正确', '-18' => '系统升级', '-19' => '域名不对', '-20' => 'key不匹配', '-21' => '用户不存在', '-22' => '余额不足', '-100' => '发送的token不合法','-999'=>'频繁发送');
		
		$this->assign('record_list', $res);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);
		$this->assign('status', $status);
		$this->display();
    }
    
    //APP版本升级
    public function mobileApp() {
        $config = D("Appapi_app_config");
        if (IS_POST) {
           // $ios_version = I("ios_version", false);
            $android_version = I("android_version", false);
           // $ios_download_url = htmlspecialchars_decode($_POST['ios_download_url']);
            $android_download_url = htmlspecialchars_decode($_POST['android_download_url']);
           // $ios_version_code = I("ios_version_code", false);
          //  $ios_version_desc = I("ios_version_desc", false);
            $android_version_desc = I("android_version_desc", false);
            $android_version_code = I("android_version_code", false);
           // $about = I("about");
           // $rules = I("rules");
            
            $columns = array();
           // $columns['ios_version'] = $ios_version;
            $columns['android_version'] = $android_version;
          //  $columns['ios_download_url'] = $ios_download_url;
            $columns['android_download_url'] = $android_download_url;
            $columns['android_version_code'] = $android_version_code;
         //   $columns['ios_version_code'] = $ios_version_code;
            $columns['android_version_desc'] = $android_version_desc;
           // $columns['ios_version_desc'] = $ios_version_desc;
           // $columns['about'] = $about;
            //$columns['rules'] = $rules;
            foreach ($columns as $key=>$val) {
                $config->where(array('var'=>$key))->data(array('value'=>$val))->save();
            }

            $this->success("修改成功");
        } else {
            $data = $config->select();
            $return = array();
            foreach ($data as $val) {
                $return[$val['var']] = $val['value'];
            }
            $this->assign($return);
            $this->display();
        }
    }

    //店员App版本升级
    public function storestaffApp() {
        $config = D("Appapi_app_config");
        if (IS_POST) {
            $android_version = I("android_version", false);
            $android_download_url = htmlspecialchars_decode($_POST['android_download_url']);
            $android_version_desc = I("android_version_desc", false);
            $android_version_code = I("android_version_code", false);
            $storestaff_ios_download_url= I("storestaff_ios_download_url", false);
            $storestaff_ios_package_name = I("storestaff_ios_package_name", false);
            $storestaff_android_package_name = I("storestaff_android_package_name", false);

            $columns = array();
            $columns['staff_android_v'] = $android_version;
            $columns['staff_android_url'] = $android_download_url;
            $columns['staff_android_vcode'] = $android_version_code;
            $columns['staff_android_vdesc'] = $android_version_desc;
            $columns['storestaff_ios_download_url'] = $storestaff_ios_download_url;
            $columns['storestaff_ios_package_name'] = $storestaff_ios_package_name;
            $columns['storestaff_android_package_name'] = $storestaff_android_package_name;
            $i = 1;
            foreach ($columns as $key=>$val) {
                $result=$config->where(array('var'=>$key))->data(array('value'=>$val))->save();
                if($result === 0){
                    $date['var'] = $key;
                    $date['value'] = $val;
                    $date['sort'] = $i;
                    $i++;
                    $config->data($date)->add();
                }
            }

            $this->success("修改成功");
        } else {
            $data = $config->select();
            $return = array();
            foreach ($data as $val) {
                $return[$val['var']] = $val['value'];
            }
            $this->assign($return);
            $this->display();
        }
    }

    //社区app

    public function VillageApp() {
        $config = D("Appapi_app_config");
        if (IS_POST) {
            $android_version = I("android_version", false);
            $android_download_url = htmlspecialchars_decode($_POST['android_download_url']);
            $android_version_desc = I("android_version_desc", false);
            $android_version_code = I("android_version_code", false);

            $columns = array();
            $columns['village_android_v'] = $android_version;
            $columns['village_android_url'] = $android_download_url;
            $columns['village_android_vcode'] = $android_version_code;
            $columns['village_android_vdesc'] = $android_version_desc;
            $i = 1;
            foreach ($columns as $key=>$val) {
                $result=$config->where(array('var'=>$key))->data(array('value'=>$val))->save();
                if($result === 0){
                    $date['var'] = $key;
                    $date['value'] = $val;
                    $date['sort'] = $i;
                    $i++;
                    $config->data($date)->add();
                }
            }

            $this->success("修改成功");
        } else {
            $data = $config->select();
            $return = array();
            foreach ($data as $val) {
                $return[$val['var']] = $val['value'];
            }
            $this->assign($return);
            $this->display();
        }
    }


    //商家中心App版本升级
    public function merchantApp() {
        $config = D("Appapi_app_config");
        if (IS_POST) {
            $android_version = I("android_version", false);
            $android_download_url = htmlspecialchars_decode($_POST['android_download_url']);
            $android_version_desc = I("android_version_desc", false);
            $android_version_code = I("android_version_code", false);
            $mer_ios_download_url = I("mer_ios_download_url", false);
            $mer_ios_package_name = I("mer_ios_package_name", false);
            $mer_android_package_name = I("mer_android_package_name", false);

            $columns = array();
            $columns['mer_android_v'] = $android_version;
            $columns['mer_android_url'] = $android_download_url;
            $columns['mer_android_vcode'] = $android_version_code;
            $columns['mer_android_vdesc'] = $android_version_desc;
            $columns['mer_ios_download_url'] = $mer_ios_download_url;
            $columns['mer_ios_package_name'] = $mer_ios_package_name;
            $columns['mer_android_package_name'] = $mer_android_package_name;
            $i = 1;
            foreach ($columns as $key=>$val) {
                $result=$config->where(array('var'=>$key))->data(array('value'=>$val))->save();
                if($result === 0){
                    $date['var'] = $key;
                    $date['value'] = $val;
                    $date['sort'] = $i;
                    $i++;
                    $config->data($date)->add();
                }
            }

            $this->success("修改成功");
        } else {
            $data = $config->select();
            $return = array();
            foreach ($data as $val) {
                $return[$val['var']] = $val['value'];
            }
            $this->assign($return);
            $this->display();
        }
    }

    //商家中心App版本升级
    public function deliverApp() {
        $config = D("Appapi_app_config");
        if (IS_POST) {
            $android_version = I("android_version", false);
            $android_download_url = htmlspecialchars_decode($_POST['android_download_url']);
            $android_version_desc = I("android_version_desc", false);
            $android_version_code = I("android_version_code", false);
			
			$package_name = I("android_package_name", false);

            $columns = array();
            $columns['deliver_android_version'] = $android_version;
            $columns['deliver_android_url'] = $android_download_url;
            $columns['deliver_android_vcode'] = $android_version_code;
            $columns['deliver_android_vdes'] = $android_version_desc;
			 $columns['deliver_android_package_name'] = $package_name;
            $i = 1;
            foreach ($columns as $key=>$val) {
                $result=$config->where(array('var'=>$key))->data(array('value'=>$val))->save();
                if($result === 0){
                    $date['var'] = $key;
                    $date['value'] = $val;
                    $date['sort'] = $i;
                    $i++;
                    $config->data($date)->add();
                }
            }

            $this->success("修改成功");
        } else {
            $data = $config->select();
            $return = array();
            foreach ($data as $val) {
                $return[$val['var']] = $val['value'];
            }

            $this->assign($return);
            $this->display();
        }
    }

    //极光推送群发
    public function jpushGroup() {
        if (IS_POST) {
            $jpush_client       =   I('jpush_client',3);  // 客户端：1苹果  2安卓 3苹果、安卓
            $jpush_title        =   I('jpush_title');     // 标题：苹果专用
            $jpush_msg          =   I('jpush_msg');       // 发送内容
            $jpush_extra['url'] =   I('jpush_url');       // 跳转URL
            $device_id          =   I('device_id','all');
            if(empty($jpush_title)){
                $this->assign('标题不能为空');
            }else if(empty($jpush_msg)){
                $this->assign('内容不能为空');
            }
            if($device_id != 'all'){
                $device_id  =   explode("\n",$device_id);
                foreach($device_id as $v){
                    $tmp[]  =   str_replace('-','',$v);
                }
                $audience   =   array('tag'=>array($tmp));
            }else{
                $audience   =   $device_id;
            }
            $result = D('Group_order')->AuroraMass($jpush_client, $jpush_title, $jpush_msg, $jpush_extra,$audience);
            $this->success($result['msg']);
        }else{
            $this->display();
        }
    }
}