<?php
// 社区余额
    class Village_money_listModel extends Model{
        //增加余额
        public function add_money($order_info){
            //社区绑定用户
            //$village_user = D('Village')->get_village_user($village_id);

            $village_id = $order_info['village_id'];
            $desc = $order_info['desc'];
            $now_village = D('House_village')->get_one($order_info['village_id']);
            $date['village_id'] = $order_info['village_id'];
            $order_info['is_own'] -=4;

            //自有支付
            if(isset($order_info['is_own'])&&$order_info['is_own']>0){
                //$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                //$alias_name = $this->get_alias_c_name($order_info['order_type']);
                //$store_name = '无';
                //$remark = '请到社区平台中查看';
                //$model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $village_user['openid'], 'first' => '收款成功,' , 'keyword1' => $alias_name[$order_info['order_type']], 'keyword2' =>$order_info['payment_money'],'keyword3' =>$store_name, 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' =>$remark), $village_id);

                if(C('config.open_village_own_percent')==1){
                    $own_percent_money = $order_info['payment_money'];
                }
                $order_info['payment_money']=0;
            }

            switch($order_info['order_type']){

                case 'sqrecharge':
                    $num =1;
                    $money = $order_info['pay_money'];
                    $date['money']= $money;
                    $date['total_money']= $money;
                    break;
                case 'village_pay':
                    $num =1;
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte'];
                    $date['money']= $money;
                    $date['total_money']= $money;
                    break;
                case 'village_pay_cashier':
                    $num =1;
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte'];
                    $date['money']= $money;
                    $date['total_money']= $money;
                    break;
                case 'express':
                    $num =1;
                    $money = $order_info['balance_pay']+$order_info['payment_money'];
                    $date['money']= $money;
                    $date['total_money']= $money;
                    break;
                case 'withdraw':
                    $num =1;
                    $money = $order_info['money'];
                    $date['money']= $money;
                    $date['total_money']= $money;
                    break;
                case 'withdraw':
                    $num =1;
                    $money = $order_info['money'];
                    $date['money']= $money;
                    $date['total_money']= $money;
                    break;
                case 'custom':
                    $num =1;
                    $money = $order_info['money'];
                    $date['money']= $money;
                    $date['total_money']= $money;
                    break;
                default:
                    $money =0;
                    break;
            }
            if($now_village['percent']>0){
                $percent = $now_village['percent'];
            }else{
                $percent = C('config.platform_get_village_percent');
            }

            if(C('config.open_village_own_percent') && $own_percent_money>0){
                $desc_pay_for_system='对社区自有支付抽成，在线自有支付支付金额：'.$own_percent_money.',扣除社区余额：'.sprintf("%.2f",$own_percent_money*$percent/100);
                $result_pay = $this->use_money($village_id,sprintf("%.2f",$own_percent_money*$percent/100),$order_info['order_type'],$desc_pay_for_system,$order_info['order_id'],$percent,sprintf("%.2f",$own_percent_money*$percent/100));

            }

            $date['num'] = $num>0?$num:1;
            $date['money'] = $money;
            $date['type']=$order_info['order_type'];

            $date['order_id'] = $order_info['order_id'];


            if($order_info['order_type']!='withdraw'  && $order_info['order_type']!='sqrecharge'){
                $date['system_take']= ($money*$percent/100);
                $date['money']= sprintf("%.2f",$money*(100-$percent)/100);
                $date['percent'] = $percent;
            }else{
                $date['percent'] = 0;
            }

            $date['income'] = 1;
            $date['use_time']= time();
            $date['desc']=  empty($desc)?'':$desc;

            if(  !M('House_village')->where(array('village_id'=>$village_id))->setInc('money', $date['money'])  ){
                return array('error_code'=>true,'msg'=>'增加社区余额失败');
            }elseif($order_info['order_type']=='village_pay'){
                M('House_village_pay_order')->where(array('order_id' => $order_info['order_id']))->setField('is_pay_bill', 2);
                $date['type'] = $order_info['order_type'];
            }
            $now_village_money = M('House_village')->where(array('village_id'=> $village_id))->find();

            $date['now_village_money'] = $now_village_money['money'];

            if(!$this->add($date)){
                return array('error_code'=>true,'msg'=>$desc.' ，保存社区收入失败！');
            }else{
                //if($order_info['order_type'] != 'withdraw'&& !empty($village_user) && $village_user['open_money_tempnews']) {
                //    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                //    $alias_name = $this->get_alias_c_name($order_info['order_type']);
                //    $store_name = '无';
                //    if (!empty($now_store)) {
                //        $store_name = $now_store['name'];
                //    }
                //    $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $village_user['openid'], 'first' => '收款成功，当前社区余额:'.$now_village_money['money'] , 'keyword1' => $alias_name[$order_info['order_type']], 'keyword2' =>$money,'keyword3' =>$store_name, 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到社区中心社区余额中查看'), $village_id);
                //}


                return array('error_code'=>false,'msg'=>$desc.' ，保存社区收入成功！');
            }
        }



        public  function get_alias_c_name(){
            return array(
                'all'=>'选择分类',
                'withdraw'=>'提现',
                'sqrecharge'=>'充值',
                'village_pay'=>'社区缴费',
                'express'=>'快递代送',
                'village_pay_cashier'=>'社区收银台缴费',
            );
        }


        //减少余额
        public function use_money($village_id,$money,$type,$desc,$order_id,$percent=0,$system_take = 0,$village_user=array()){
            $date['village_id']=$village_id;
            $date['income'] = 2;
            $date['order_id'] = $order_id;
            if($percent){
                $date['percent'] = $percent;
                $date['system_take'] = $system_take;
            }
            $date['use_time']= time();
            $date['type']= $type;
            $date['desc']=  $desc;
            $date['money']=  $money;
         
            if(!M('House_village')->where(array('village_id'=>$village_id))->setDec('money', $date['money'])){
                return array('error_code'=>true,'msg'=>$desc.'，使用失败！');
            }
            $now_village_money = M('House_village')->field('money,village_owe_money')->where(array('village_id'=> $village_id))->find();
            //$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $village_status = 1;
            if(C('config.open_village_owe_money')==0){
                $now_village_money['village_owe_money'] = 0;
            }
            if($now_village_money['money']<$now_village_money['village_owe_money']){
                M('House_village')->where(array('village_id'=>$village_id))->setField('status',2);
                $village_status = 2;
            }
            //if(!empty($village_user['openid'])){
            //    if($village_status==3){
            //        $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_village_money['openid'], 'first' => $desc.'，当前社区余额:'.$now_village_money['money'].',您的社区状态为欠费，您的社区业务状态为禁止状态，请及时充值' , 'keyword1' => '社区余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到社区中心社区余额中查看'), $village_id);
            //    }else{
            //        $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_village_money['openid'], 'first' => $desc.'，当前社区余额:'.$now_village_money['money'] , 'keyword1' => '社区余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到社区中心社区余额中查看'), $village_id);
            //
            //    }
            //}

            $date['now_village_money'] = $now_village_money['money'];
            if(!$this->add($date)){
                return array('error_code'=>true,'msg'=>$desc.'，保存失败！');
            }else{
                return array('error_code'=>false,'msg'=>$desc.'，保存成功！');
            }
        }

        //统计所有社区余额
        public function  get_all_money($where){
            if(!empty($where)){
                return M('House_village')->where($where)->sum('money');
            }
            return M('House_village')->sum('money');
        }

        //提现
        public function withdraw($village_id,$name,$money,$remark){
            $date['village_id']=$village_id;
            $withdraw_money = $money;
            if(C('config.company_pay_village_percent')>0){
                $tmp_money  = $money;
                $withdraw_money = floor ($tmp_money * (100-C('config.company_pay_village_percent'))/100);
                //$date['percent'] = C('config.company_pay_village_percent');
                $system_take = floor($tmp_money * (C('config.company_pay_village_percent'))/100)/100;
            }
            $date['name']=$name;
            $date['money']=  $withdraw_money;
            $date['old_money']=  $money;
            $date['remark']=  $remark;
            $date['withdraw_time'] = time();

            $res =M('Village_withdraw')->add($date);
            if(!$res){
                return array('error_code'=>true,'msg'=>'保存失败！');
            }else{
                //考虑兑现后减值
                $this->use_money($village_id,$money/100,'withdraw','社区提现减少金额',$res,C('config.company_pay_village_percent'),$system_take);
                return array('error_code'=>false,'msg'=>'保存成功！');
            }
        }

        public function get_income_list($villiage_id,$is_system = 0,$where){
            if($_GET['page']){
                $where = $_SESSION['condition'];
            }

            if($is_system){
                import('@.ORG.system_page');
            }else{
                import('@.ORG.village_page');
            }
            $where['village_id']=$villiage_id;

            $count = M('Village_money_list')->where( $where)->count();
            unset($where['village_id']);
            $where['l.village_id']=$villiage_id;
           
            $p = new Page($count, 20);
            $pagebar=$p->show();
            if($_GET['page']>$p->totalPage){
                return array('income_list'=>array(),'total'=>0,'page_num'=>$p->totalPage);
            }else {
                $income_list = M('Village_money_list')->join('as l left join '.C('DB_PREFIX') . 'house_village m ON m.village_id = l.village_id ')->field('l.order_id,l.desc,l.use_time,l.num,l.money,l.type,l.id,l.income,l.now_village_money,l.system_take,l.percent')->where($where)->order('use_time DESC')->limit($p->firstRow, $p->listRows)->select();

                $total = M('Village_money_list')->join('as l left join '.C('DB_PREFIX') . 'house_village m ON m.village_id = l.village_id ')->where(array('l.village_id'=>$villiage_id,'l.income'=>1))->order('use_time DESC')->limit($p->firstRow, $p->listRows)->sum('l.money');
                $income_total = M('Village_money_list')->join('as l left join '.C('DB_PREFIX') . 'house_village m ON m.village_id = l.village_id ')->where(array('l.village_id'=>$villiage_id,'l.income'=>1,'l.type'=>array('neq','sqrecharge')))->order('use_time DESC')->limit($p->firstRow, $p->listRows)->sum('l.money');
                $recharge_total = M('Village_money_list')->join('as l left join '.C('DB_PREFIX') . 'house_village m ON m.village_id = l.village_id ')->where(array('l.village_id'=>$villiage_id,'l.income'=>1,'l.type'=>'sqrecharge'))->order('use_time DESC')->limit($p->firstRow, $p->listRows)->sum('l.money');
              //  $total_score = M('Village_money_list')->join('as l left join '.C('DB_PREFIX') . 'house_village m ON m.village_id = l.village_id ')->where($where)->order('use_time DESC')->limit($p->firstRow, $p->listRows)->sum('l.score');
                return array('income_list' => $income_list,'total'=>empty($total)?0:$total,'income_total'=>empty($income_total)?0:$income_total, 'total_score'=>empty($total_score)?0:$total_score,'recharge_total'=>empty($recharge_total)?0:$recharge_total,'pagebar' => $pagebar, 'page_num' => $p->totalPage);
            }
        }

        public function get_village_withdraw_list($condition_village,$page_count=15){
            $database_village = M('House_village');
            import('@.ORG.system_page');

            if(isset($condition_village)){
                $count_village = $database_village->join('as m left join '.C('DB_PREFIX').'village_withdraw AS w  ON m.village_id = w.village_id ')->where($condition_village)->count();

                foreach($condition_village as $k=>$v){
                    if(strpos($k,'status')){
                        continue;
                    }
                    $condition_village['m.'.$k] = $v;
                    unset($condition_village[$k]);
                }

                $p = new Page($count_village,$page_count);

                $village_withdraw_list = $database_village->join('as m left join (SELECT  village_id,SUM(money) AS  withdraw_money,withdraw_time,status  FROM '.C('DB_PREFIX').'village_withdraw WHERE status in (0,4)  GROUP BY village_id) w  ON m.village_id = w.village_id ')
                    ->field('m.village_id,m.property_phone as phone,m.village_name as name,m.money,w.withdraw_time,w.withdraw_money as withdraw_money')
                    ->where($condition_village)
                    ->order('m.money DESC')
                    ->limit($p->firstRow.','.$p->listRows)
                    ->select();

            }else{

                foreach($condition_village as $k=>$v){
                    $condition_village['m.'.$k] = $v;
                    unset($condition_village[$k]);
                }
                $count_village = $database_village->where($condition_village)->count();
                $p = new Page($count_village,$page_count);
                $village_withdraw_list = $database_village->join('as m left join (SELECT  village_id,SUM(money) AS  withdraw_money,withdraw_time,status  FROM pigcms_village_withdraw WHERE status in (0,4)  GROUP BY village_id) w ON m.village_id = w.village_id ')
                    ->field('m.village_id,m.property_phone as phone,m.village_name as name,m.money,w.withdraw_time,w.withdraw_money')
                    ->where($condition_village)
                    ->order('m.money DESC')
                    ->limit($p->firstRow.','.$p->listRows)
                    ->select();
            }
            $pagebar = $p->show();
            return array('village_withdraw_list'=>$village_withdraw_list,'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
        }


        //抽成列表
        public function get_village_percentage_list($condition_village,$page_count=15){
            $database_village = M('Merchant');
            $where['area_id'] = $condition_village['area_id'];
            $count_village = $database_village->where($where)->count();
            $time_condition = '';
            foreach($condition_village as $k=>$v){
                if($k!='_string'){
                    $condition_village['m.'.$k] = $v;
                }else{
                    $time_condition = 'WHERE '.$condition_village[$k];
                }
                unset($condition_village[$k]);
            }
            $extra_str ='';
            $extra_field ='';


            import('@.ORG.system_page');
            $p = new Page($count_village,$page_count);
            $village_percentage_list = $database_village->join('as m left join '.'(SELECT  village_id,SUM(system_take) AS  money ,SUM(score) as all_score FROM '.C('DB_PREFIX').'village_money_list '.$time_condition.' GROUP BY village_id) w ON m.village_id = w.village_id  '.$extra_str)
                ->field('m.village_id,m.name,m.phone,w.money,w.all_score'.$extra_field)
                ->where($condition_village)
                ->order('w.money DESC,w.all_score DESC')
                ->limit($p->firstRow.','.$p->listRows)
                ->select();

            $pagebar = $p->show();
            return array('village_percentage_list'=>$village_percentage_list,'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
        }

        //统计所有商家余额
        public function  get_all_percent_money($condition_village){
            foreach($condition_village as $k=>$v){
                if($k!='_string'){
                    $condition_village['m.'.$k] = $v;
                    unset($condition_village[$k]);
                }
            }
            return M('Merchant_money_list')->join('AS l left join '.C('DB_PREFIX') .'village as m ON m.village_id = l.village_id')->where($condition_village)->sum('l.system_take');
        }

        //统计所有送出的积分
        public function  get_all_score($condition_village){
            $all_socre=  0;

            foreach($condition_village as $k=>$v){
                if($k!='_string'){
                    $condition_village['m.'.$k] = $v;
                    unset($condition_village[$k]);
                }
            }
            $all_socre += M('Merchant_money_list')->join('AS l left join '.C('DB_PREFIX') .'village as m ON m.village_id = l.village_id')->where($condition_village)->sum('l.score');
            return $all_socre;
        }

        public function get_withdraw_list($village_id,$is_system = 0,$status=3,$time=''){
            if($is_system){
                import('@.ORG.system_page');
            }else{
                import('@.ORG.merchant_page');
            }
            $where['village_id']= $village_id;
            if($status!=3){
                $where['status']= $status;
            }
            if($status==0){
                $where['status'] = array('in','0,4');
            }
            if(!empty($time)){
                $where['_string'] = $time;
            }

            $count = M('Village_withdraw')->where($where)->count();
            $p = new Page($count, 20);
            $pagebar=$p->show();
            if($_GET['page']>$p->totalPage){
                return array('withdraw_list'=>array(),'page_num'=>$p->totalPage);
            }else{
                return array('withdraw_list'=>M('Village_withdraw')->where($where)->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select(),'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
            }

        }

        public function get_all_village_money($where){

                return M('House_village')->where($where)->sum('money');

        }

        public function agree($village_id,$money,$withdraw_id,$remark,$is_online=false){
            $date['status'] = 1;
            $date['remark'] = $remark;
            $date['online'] = $is_online;
            $date['money'] = $money;

            $res = M('Village_withdraw')->where(array('id'=>$withdraw_id,'village_id'=>$village_id))->find();
            if($res['status']==4){
                M('Withdraw_list')->where(array('withdraw_id'=>$withdraw_id,'type'=>'village','pay_id'=>$village_id))->setField('status',1);
            }
            $res = M('Village_withdraw')->where(array('id'=>$withdraw_id,'village_id'=>$village_id))->save($date);
            return $res;
        }
        
        //拒绝提现 增加余额
        public function reject($village_id,$withdraw_id,$reason){
            $res = M('Village_withdraw')->where(array('id'=>$withdraw_id,'village_id'=>$village_id))->find();
            $date['status'] = 2;
            $date['remark'] = $reason;
            M('Village_withdraw')->where(array('id'=>$withdraw_id,'village_id'=>$village_id))->save($date);
            $desc = '驳回社区提现增加金额';
            $order_info['money'] = $res['old_money']/100;
            $order_info['order_type'] = 'withdraw';
            $order_info['village_id'] = $village_id;
            $order_info['order_id'] = $withdraw_id;
            $order_info['desc'] = $desc;
            if($res['status']==4){
                M('Withdraw_list')->where(array('withdraw_id'=>$withdraw_id,'type'=>'village','pay_id'=>$village_id))->setField('status',4);
            }
            return $this->add_money($order_info);
        }





    }