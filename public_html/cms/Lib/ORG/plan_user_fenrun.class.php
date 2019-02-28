<?php
/*用户分润

 * */
class plan_user_fenrun extends plan_base{
	public function runTask(){
		if(C('config.open_score_fenrun') && C('config.auto_fenrun')) {
			$Fenrun_model =  D('Fenrun');
			$user_list = $Fenrun_model->get_fenrun_user();
			set_time_limit(0);
			if (!empty($user_list)) {
				foreach ($user_list as $v) {
					$this->keepThread();
					$Fenrun_model->fenrun($v['uid']);
				}
				$Fenrun_model->save_today_fenrun_income_date();
			}
		}
		return true;
	}
}
?>