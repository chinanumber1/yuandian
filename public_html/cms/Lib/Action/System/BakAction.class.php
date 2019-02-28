<?php

class BakAction extends CommonAction
{
	protected $system_session;
	
	protected $bak_path = 'bakfdhjfdbt';
	
	public function __construct()
	{
		parent::__construct();
		$this->bak_path = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/' . $this->bak_path . '/';
		

		session_commit();
		session_id($_GET['session_id']);
		session_start();
		$this->system_session = session('soft_system') ? session('soft_system') : session('system');
		// if (empty($this->system_session)) $this->returnCode('1006', '没有权限进去操作');
		// if ($this->system_session['level'] != 2) $this->returnCode('1006', '没有权限进去操作');
	}
	public function returnCode($code=0,$result=array()){
        if($code == 0){
            $array = array(
                'errorCode'=>0,
                'errorMsg'=>'success',
                'result'=>$result
            );
        }else{
            $array = array(
				'errorCode'=>$code,
				'errorMsg'=>$result
            );
        }
        echo json_encode($array);
        exit();
    }
	
	function get_allfiles($path, &$files) {
		if(is_dir($path)){
			$dp = dir($path);
			while ($file = $dp->read()){
				if($file !="." && $file !=".."){
					$this->get_allfiles($path."/".$file, $files);
				}
			}
			$dp->close();
		}
		if(is_file($path)){
			$files[] =  $path;
		}
	}
	
	function get_filenamesbydir($dir){
		$files =  array();
		$this->get_allfiles($dir, $files);
		return $files;
	}
	function dmkdir($dir, $mode = 0777, $makeindex = true)
	{
	    $dir = dirname($dir);
	    if (!is_dir($dir)) {
	        dmkdir($dir, $mode, $makeindex);
	        @mkdir($dir, $mode);
	        @chmod($dir, $mode);
	        if (!empty($makeindex)) {
	            @touch($dir . '/index.html');
	            @chmod($dir . '/index.html', 0777);
	        }
	    }
		return true;
	}
	
	
	public function change_file()
	{
		if (!IS_POST) {
			set_time_limit(0);
			$star_time = time();
			echo '开始时间：' . date("Y-m-d H:i:s", $star_time) . '<br/>';
			$source_file_path = $_SERVER['DOCUMENT_ROOT'].'/old';//isset($_REQUEST['source_file_path']) && $_REQUEST['source_file_path'] ? htmlspecialchars($_REQUEST['source_file_path']) : 'C:\Users\pigcms_03\Desktop\update\20160106';
			$target_file_path = $_SERVER['DOCUMENT_ROOT'].'/update';//isset($_REQUEST['target_file_path']) && $_REQUEST['target_file_path'] ? htmlspecialchars($_REQUEST['target_file_path']) : 'C:\Users\pigcms_03\Desktop\update\20160106_bak';
			$version = isset($_REQUEST['version']) && $_REQUEST['version'] ? $_REQUEST['version'] : 2;
			if (empty($version)) exit();
			
			$target_file_path .= '/' . $version;
// 			$this->dmkdir($target_file_path);
// 			copy($source_file_path, $target_file_path);
			
			$filenames = $this->get_filenamesbydir($source_file_path);
			foreach ($filenames as $file) {
// 				echo str_replace($source_file_path, $target_file_path, $file) . '<br/>';
				$this->dmkdir(str_replace($source_file_path, $target_file_path, $file));
				copy($file, str_replace($source_file_path, $target_file_path, $file));
				
				if($fp = @fopen($file, 'r')) {
					$template = @fread($fp, filesize($file));
					fclose($fp);
				}
				$t_template = $template;
				for ($i = 1; $i < 6; $i++) {
					$template = $t_template;
					$phps = array();
					preg_match_all("/\/\*\*\[if(.+?)\]\*\*\/[\n\r\t]*(.+?)[\n\r\t]*\/\*\*\[\/if\]\*\*\//s", $template, $phps, PREG_SET_ORDER);
					foreach ($phps as $param) {
						$str = $this->condition_code($i, $param[0]);
						$template = str_replace($param[0], $str, $template);
					}
					
					$htmls = array();
					preg_match_all("/\{pigcms\{\/\*\*\*\[if(.+?)\]\*\*\*\/\}[\n\r\t]*(.+?)[\n\r\t]*\{pigcms\{\/\*\*\*\[\/if\]\*\*\*\/\}/s", $template, $htmls, PREG_SET_ORDER);
					foreach ($htmls as $html) {
						$str = $this->condition_code($i, $html[0], '{pigcms{/***[', ']***/}');
						$template = str_replace($html[0], $str, $template);
					}
					
					$new_file = str_replace($source_file_path, $target_file_path . '/1_' . $i, $file);
					echo $file.'<br/>';
					echo $source_file_path.'<br/>';
					echo $target_file_path.'<br/>';
					echo $new_file.'<br/>';
					if (!is_dir(dirname($new_file))) $this->dmkdir($new_file);
					if($nfp = fopen($new_file, 'w')) {
						flock($nfp, 2);
						fwrite($nfp, $template);
						fclose($nfp);
					}
				}
			}
			$end_time = time();
			$use_time = $end_time - $star_time;
			echo '结束时间：' . date("Y-m-d H:i:s", $end_time) . '<br/>';
			echo '版本拆分成功！耗时：' . $use_time;
// 			$zip = new ZipArchive();
// 			echo dirname($target_file_path) . '/test.zip<br/>';
// 			if($zip->open(dirname($target_file_path) . '/test.zip', ZipArchive::OVERWRITE)=== TRUE){
// 				$this->addFileToZip($target_file_path, $zip, $target_file_path);
// 				$zip->close();
// 			}
// 			if (file_exists(dirname($target_file_path) . '/test.zip')) {
// 				$end_time = time();
// 				echo '打包成功！耗时：' . intval($end_time) - intval($star_time);
// 			}
		} else {
			$this->display();
		}
	}
	
	public function zip()
	{
		set_time_limit(0);
		
		$bak_name = 'upload_' . date('YmdHis') . '.zip';
		$zip_name_path = $this->bak_path . $bak_name;
		if (!is_dir(dirname($zip_name_path))) {
			$this->dmkdir($zip_name_path);
		}

		$star = time();
		$zip = new ZipArchive();
		if($zip->open($zip_name_path, ZipArchive::OVERWRITE)=== TRUE){
			$this->addFileToZip('./upload', $zip, './upload');
			$zip->close();
		}
		$this->returnCode(0, $bak_name);
	}
	
	
	private function addFileToZip($path, $zip, $source_path) 
	{
		$handler = opendir($path);
		while (($filename = readdir($handler)) !== false) {
			if($filename != "." && $filename != ".." && $filename != ".." && $filename != "Cashier" && $filename != "文档" && $filename != "runtime" && $filename != "download" && $filename != "core" && $filename != ".svn" && $filename != ".project"){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
// 				echo $filename . '<br/>';
				if (is_dir($path . "/" . $filename)) {
					$this->addFileToZip($path . "/" . $filename, $zip, $source_path);
				} else {
					if(filectime($path . "/" . $filename) > 1469261602){
						$zip->addFile($path . "/" . $filename, str_replace($source_path . '/', '', $path . "/" . $filename));
						// echo 111;exit;
					}
					// $zip->addFile($path . "/" . $filename, str_replace($source_path . '/', '', $path . "/" . $filename));
				}
			}
		}
		@closedir($path);
	}
	
	//导出所有表的结构
	public function export_table()
	{
		set_time_limit(0);
		
		$bak_name = 'tabes_' . date('YmdHis') . '.sql';
		$config_file = $this->bak_path . $bak_name;
		if (!is_dir(dirname($config_file))) {
			$this->dmkdir($config_file);
		}
		
		$fp = fopen($config_file, 'a+');
		$tables = D()->query("show tables");
		foreach ($tables as $table) {
			$sql = D()->query("show create table " . $table['Tables_in_' . C('DB_USER')]);
			fwrite($fp, $sql[0]['Create Table'] . ";\n");
		}
		fclose($fp);
		
		$this->returnCode(0, $bak_name);
	}
	
	//导出所有表数据
	public function export_data()
	{
		$stat_time = time();
		set_time_limit(0);
		
		$bak_name = 'table_data_' . date('YmdHis') . '.sql';
		$config_file = $this->bak_path . $bak_name;
		if (!is_dir(dirname($config_file))) {
			$this->dmkdir($config_file);
		}
		
		$fp = fopen($config_file, 'a+');
		//获取数据库下所有的表
		$tables = D()->query("show tables");
		foreach ($tables as $table) {
			$filed_list = array();
			
			$str = "INSERT INTO `" . $table['Tables_in_' . C('DB_USER')] . "` (";
			$pre = '';
			//获取表中所有字段
			$fileds = D()->query("desc " . $table['Tables_in_' . C('DB_USER')]);
			foreach ($fileds as $filed) {
				$str .= $pre . "`{$filed['Field']}`";
				$pre = ',';
				$filed_list[$filed['Field']] = 0;
				if (strstr($filed['Type'], 'int')) {
					$filed_list[$filed['Field']] = 1;
				}
			}
			$str .= ") VALUES (";
			
			$page = 1;
			while ($page) {
				$stat = ($page - 1) * 1000;
				$datas = D()->query("SELECT * FROM " . $table['Tables_in_' . C('DB_USER')] . " LIMIT {$stat}, 1000");
				if ($datas) {
					$page ++;
					foreach ($datas as $data) {
						$data_str = $str;
						$dpre = '';
						foreach ($filed_list as $key => $val) {
							if ($val) {
								$data_str .= $dpre . $data[$key];
							} else {
								$data_str .= $dpre . "'" . $data[$key] . "'";
							}
							$dpre = ',';
						}
						$data_str .= ");";
						fwrite($fp, $data_str . "\n");
					}
					
				} else {
					$page = 0;
				}
			}
		}
		fclose($fp);
		$end = time();
		
		$zip_name_path = $config_file.'.zip';
		$zip = new ZipArchive();
		if($zip->open($zip_name_path, ZipArchive::OVERWRITE)=== TRUE){
			$zip->addFile($config_file,$bak_name);
			$zip->close();
		}
		
		$this->returnCode(0, $bak_name);
	}
	
	public function delfile()
	{
		$file_path = isset($_GET['file_path']) ? $_GET['file_path'] : '';
		if (file_exists($this->bak_path . $file_path)) {
			unlink($this->bak_path . $file_path);
			$this->returnCode(0, 'delete file is ok!');
		} else {
			$this->returnCode('1007', '不存在的文件');
		}
	}
	
	private function condition_code($v, $str, $start_search = '/**[', $end_search = ']**/')
	{
		$flag = true;
		$star = 0;
		$end = 0;
		while ($flag) {
			$star = strpos($str, $start_search, $star);
			if ($star === false) {
				$flag = false;
				continue;
			}
			
			$end = strpos($str, $end_search, $end);
			if ($end === false) {
				$flag = false;
				continue;
			}
			$condition = substr($str, $star + strlen($start_search), $end - $star - strlen($start_search));
			$if_con = ''; 
			$operator = '';
			$value = '';
			$intval = 1;
			for ($i = 0; $i <strlen($condition); $i++) {
				if ((ord($condition[$i]) >= 65 && ord($condition[$i]) <= 90) || (ord($condition[$i]) >= 97 && ord($condition[$i]) <= 122) || ord($condition[$i]) == 47) {
					$if_con .= $condition[$i];
				}
				if (ord($condition[$i]) == 60 || ord($condition[$i]) == 61 || ord($condition[$i]) == 62 || ord($condition[$i]) == 33) {
					$operator .= $condition[$i];
				}
				if ((ord($condition[$i]) >= 48 && ord($condition[$i]) <= 57) || ord($condition[$i]) == 46) {
					$value .= $condition[$i];
					if (ord($condition[$i]) == 46) $intval = 0;
				}
			}
			$value = $intval ? intval($value) : floatval($value);
			if (trim(strtolower($if_con)) == 'if' || trim(strtolower($if_con)) == 'elseif') {
				switch (trim($operator)) {
					case '>':
						if ($v > $value) {
							$star = strpos($str, $start_search, $star + 1);
							$return = substr($str, $end + strlen($end_search), $star - $end - strlen($end_search));
							$flag = false;
							continue;
						}
						break;
					case '>=':
						if ($v >= $value) {
							$star = strpos($str, $start_search, $star + 1);
							$return = substr($str, $end + strlen($end_search), $star - $end - strlen($end_search));
							$flag = false;
							continue;
						}
						break;
					case '<' :
						if ($v < $value) {
							$star = strpos($str, $start_search, $star + 1);
							$return = substr($str, $end + strlen($end_search), $star - $end - strlen($end_search));
							$flag = false;
							continue;
						}
						break;
					case '<=':
						if ($v <= $value) {
							$star = strpos($str, $start_search, $star + 1);
							$return = substr($str, $end + strlen($end_search), $star - $end - strlen($end_search));
							$flag = false;
							continue;
						}
						break;
					case '==';
						if ($v == $value) {
							$star = strpos($str, $start_search, $star + 1);
							$return = substr($str, $end + strlen($end_search), $star - $end - strlen($end_search));
							$flag = false;
							continue;
						}
						break;
					case '!=';
						if ($v != $value) {
							$star = strpos($str, $start_search, $star + 1);
							$return = substr($str, $end + strlen($end_search), $star - $end - strlen($end_search));
							$flag = false;
							continue;
						}
						break;
				}
				
			} elseif (trim($if_con) == 'else') {
				$star = strpos($str, $start_search, $star + 1);
				$return = substr($str, $end + strlen($end_search), $star - $end - strlen($end_search));
				$flag = false;
				continue;
			} elseif (trim($if_con) == '/if') {
				$return = '';
				$flag = false;
				continue;
			}
			$star ++;
			$end ++;
		}
		return $return;
	}
}