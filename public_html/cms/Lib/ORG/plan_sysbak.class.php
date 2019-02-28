<?php
class plan_sysbak extends plan_base{
	public $lastBakTime;
	public $bak_path;
	public function runTask(){
		set_time_limit(0);
		$this->dmkdir('./source/sys_bak/');
		$this->bak_path = './source/sys_bak/'.date('Ymd').'/';
		if(!file_exists($this->bak_path)){
			$this->dmkdir($this->bak_path);
			$this->lastBakTime = 0;
			
			import('ORG.Util.Dir');
			$dirObj = new Dir('./source/sys_bak/');
			foreach($dirObj as $value){
				if($value['isDir']){
					if(strtotime($value['filename'].' 00:00:00') <= mktime(0,0,0,date('m'),date('d'),date('Y')) - 86400*3){
						Dir::delDirnotself($value['pathname']);
						rmdir($value['pathname']);
					}
				}
			}
		}else{
			$this->lastBakTime = file_get_contents($this->bak_path.'lastBakTime.txt');
			if(empty($this->lastBakTime)) $this->lastBakTime = 0;
		}
		//打包文件
		$this->zipFile();
		
		//打包数据结构
		if(empty($this->lastBakTime)){
			$this->export_table();
		}
		
		//打包数据表
		$this->export_data();
		
		file_put_contents($this->bak_path.'lastBakTime.txt',time());
		
		return true;
	}
	public function export_data(){
		$bak_name = 'table_data_' . date('YmdHis') . '.sql';
		$config_file = $this->bak_path . $bak_name;

		$fp = fopen($config_file, 'a+');
		//获取数据库下所有的表
		$tables = D()->query("show tables");
		foreach ($tables as $table) {
			$this->keepThread();
			$filed_list = array();
			
			$str = "INSERT INTO `" . $table['Tables_in_' . C('DB_USER')] . "` (";
			$pre = '';
			//获取表中所有字段
			$fileds = D()->query("desc " . $table['Tables_in_' . C('DB_USER')]);
			foreach ($fileds as $filed) {
				$this->keepThread();
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
				$this->keepThread();
				$stat = ($page - 1) * 1000;
				$datas = D()->query("SELECT * FROM " . $table['Tables_in_' . C('DB_USER')] . " LIMIT {$stat}, 1000");
				if ($datas) {
					$page ++;
					foreach ($datas as $data) {
						$this->keepThread();
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
		
		$zip_name_path = $config_file.'.zip';
		$zip = new ZipArchive();
		if($zip->open($zip_name_path, ZipArchive::OVERWRITE)=== TRUE){
			$zip->addFile($config_file,$bak_name);
			$zip->close();
			unlink($config_file);
		}
		
	}
	public function export_table(){
		$bak_name = 'table_' . date('His') . '.sql';
		$config_file = $this->bak_path . $bak_name;
		
		$fp = fopen($config_file, 'a+');
		$tables = D()->query("show tables");
		foreach ($tables as $table) {
			$this->keepThread();
			$sql = D()->query("show create table " . $table['Tables_in_' . C('DB_USER')]);
			fwrite($fp, $sql[0]['Create Table'] . ";\n");
		}
		fclose($fp);
		
		$zip_name_path = $config_file.'.zip';
		$zip = new ZipArchive();
		if($zip->open($zip_name_path, ZipArchive::OVERWRITE)=== TRUE){
			$zip->addFile($config_file,$bak_name);
			$zip->close();
			unlink($config_file);
		}
	}
	
	private function zipFile(){
		$bak_name = 'file_' . date('His') . '.zip';
		$zip_name_path = $this->bak_path . $bak_name;
		$this->dmkdir($zip_name_path);
		$zip = new ZipArchive();
		
		if($zip->open($zip_name_path, ZipArchive::OVERWRITE)=== TRUE){
			$this->addFileToZip('./upload', $zip, './upload');
			$zip->close();
		}
	}
	private function addFileToZip($path, $zip, $source_path) {
		$handler = opendir($path);
		$this->keepThread();
		while (($filename = readdir($handler)) !== false) {
			$this->keepThread();
			if($filename != "." && $filename != ".." && $filename != ".." && $filename != "Cashier" && $filename != "文档" && $filename != "runtime" && $filename != "download" && $filename != "core" && $filename != ".svn" && $filename != ".project"){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
				if (is_dir($path . "/" . $filename)) {
					$this->addFileToZip($path . "/" . $filename, $zip, $source_path);
				} else {
					if($this->lastBakTime){
						if(filectime($path . "/" . $filename) > $this->lastBakTime){
							$zip->addFile($path . "/" . $filename, str_replace($source_path . '/', '', $path . "/" . $filename));
						}
					}else{
						$zip->addFile($path . "/" . $filename, str_replace($source_path . '/', '', $path . "/" . $filename));
					}
				}
			}
		}
		@closedir($path);
	}
	function dmkdir($dir, $mode = 0777, $makeindex = true){
	    $dir = dirname($dir);
		
	    if (!is_dir($dir)){
	        mkdir($dir,0777,true);
	        if (!empty($makeindex)){
	            @touch($dir . '/index.html');
	        }
	    }
		return true;
	}
}
?>