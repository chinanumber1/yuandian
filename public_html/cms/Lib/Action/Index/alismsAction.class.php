<?php
 class alismsAction extends BaseAction {
    public function index(){
        $row = array('name' => 'sms_name', 'type' =>'type=text&validate=required:true', 'value' => 'alisms', 'info' =>'阿里短信用户名', 'desc' =>'你在阿里短信注册的用户名', 'tab_id' =>'0', 'tab_name'=>'','gid'=>'15','sort'=>'12','status'=>'1');
        $add=M('config')->add($row);
        echo "<h4>小猪o2o短信宝短信插件安装成功，请删除install_smsbao.php文件</h4>";
    }
}