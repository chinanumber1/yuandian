<?php
/*
 * 公共文件
 *
 *   Writers    Jaty
 *   BuildTime  2014/11/05 17:40
 *
 */

class Scenic_publicAction extends Action{
    public function header(){
		$this->display();
    }
	public function footer(){
		$this->display();
    }
}