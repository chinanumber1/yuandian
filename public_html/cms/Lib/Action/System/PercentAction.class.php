<?php

class PercentAction extends BaseAction
{
    /**
     * @return 抽成设置
     */
    public function percent()
    {
        if (IS_POST) {
            $plat_percent = $_POST['platform_get_merchant_percent'];
            $money_start_arr = $_POST['money_start'];
            $money_end_arr = $_POST['money_end'];
            $percent_arr = $_POST['money_percent'];
            $tmp = 0;
            $data_arr  = array();
           // dump($_POST);die;
            if(empty($_POST['type'])) {
//                if(empty($money_start_arr)){
//                    $this->error('数据设置有误，请检查');
//                }elseif(empty($money_end_arr)){
//                    $this->error('数据设置有误，请检查');
//                }elseif(empty($percent_arr)){
//                    $this->error('数据设置有误，请检查');
//                }
                $config_where['name'] = 'platform_get_merchant_percent';
                $result_config = M('Config')->where($config_where)->save( array('value'=>$plat_percent));
                foreach ($money_start_arr as $key => $v) {
                    if (!is_numeric($v)||!is_numeric($money_end_arr[$key])||!is_numeric($percent_arr[$key])||$v < 0 || empty($money_end_arr[$key]) || $money_end_arr[$key] < 0 || empty($percent_arr[$key]) || $percent_arr[$key] < 0) {
                        $this->error('数据设置有误，请检查');
                    } else if ($v >= $money_end_arr[$key]) {
                        $this->error('金额范围设置有误，金额范围结束不能比金额范围起始小');
                    }
                    if ($tmp != 0 && $money_start_arr[$key] < $tmp) {
                        $this->error('金额范围设置有误，金额范围起始不能比上一级的金额范围结束小');
                    }
                    $data['money_start'] = $v;
                    $data['money_end'] = $money_end_arr[$key];
                    $data['percent'] = $percent_arr[$key];
                    $data_arr[] = $data;
                    $tmp = $money_end_arr[$key];
                }
                if(!M('')->execute('DELETE FROM '.C('DB_PREFIX').'percent_detail')){
                    M('Percent_detail')->addAll($data_arr);
                    $this->success('保存成功');
                }else{
                    $this->error('保存失败');
                }
            }else{
                $where['fid'] = 0;
                if(isset($_POST['meal_scan_percent'])){
                    $config_where_['name'] = 'meal_scan_percent';
                    $result_config_ = M('Config')->where($config_where_)->save( array('value'=>$_POST['meal_scan_percent']));
                }
                $config_where['name'] = $_POST['type'].'_percent';

                $result_config = M('Config')->where($config_where)->save( array('value'=>$_POST[$_POST['type'].'_percent']));
                $data[$_POST['type'].'_percent_detail'] = implode(',',$percent_arr);
                if(M('Percent_detail_by_type')->where($where)->find()){
                    $result = M('Percent_detail_by_type')->where($where)->save($data);
                }else{
                    $data['fid'] = 0;
                    $result = M('Percent_detail_by_type')->add($data);
                }
                if($result||$result_config||$result_config_){
                    $this->success('保存成功');
                }else{
                    $this->error('保存失败');
                }
            }
        } else {
            if(!empty($_GET['type'])){
                $result = M('Percent_detail_by_type')->where(array('fid'=>0))->find();
                $detail = explode(',',$result[$_GET['type'].'_percent_detail']);
                $this->assign('detail', $detail);
            }
            $percent_detail = M('Percent_detail')->select();

            $this->assign('percent_detail', $percent_detail);
            $this->display();
        }

    }

    public function rate(){
        $this->display();
    }

    public function user_rate(){
        $this->display();
    }

    public function score(){
        $this->display();
    }

    public function distributor_agent(){
        $levelDb = M('User_level');
        $levelarr = $levelDb->order('id ASC')->select();

        $this->assign('levelarr', $levelarr);
        $this->display();
    }


}