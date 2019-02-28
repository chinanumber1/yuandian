<?php
class ExportFileAction extends BaseAction
{
	public function download_export_file(){
		if(empty($_SESSION['staff'])&&empty($_SESSION['merchant'])&&empty($_SESSION['system'])){
			exit;
		}
		$where['export_id']=I('id');

		$export  = M('Export_log')->where($where)->find();


		if(file_exists('./runtime/'.$export['file_name'])){
			if(IS_AJAX){
				echo json_encode(array('error_code'=>0));
			}else{

				header('Content-Disposition: attachment; filename="'.$export['title'].'_'.$export['file_name'].'"');;


				header("Content-type:application/vnd.ms-excel");

				readfile('./runtime/'.$export['file_name']) ;

			}
		}else{
			echo json_encode(array('error_code'=>1));
		}
		exit;
	}
}