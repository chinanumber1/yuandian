<?php
/*
 * 微信验证
 *
 */
class MpverifyAction extends Action{
    public function index(){
		header('Content-type: text/plain');
		echo $_GET['code'];
    }
}