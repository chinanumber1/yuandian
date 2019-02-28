<?php
class ApplogAction extends BaseAction{
    public function index(){
		if(!file_exists('./api/applog/')){
			mkdir('./api/applog/',0777,true);
		}
		$fileName = intval($this->_uid).'_'.$this->DEVICE_ID.'.log';
    	file_put_contents('./api/applog/'.$fileName,var_export($_POST,true),FILE_APPEND);
	}
}