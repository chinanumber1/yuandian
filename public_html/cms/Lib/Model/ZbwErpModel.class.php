<?php
/**
 * Created by PhpStorm.
 * User: win 10
 * Date: 2018/1/9
 * Time: 10:11
 */
class ZbwErpModel extends Model{
    private $userKey;
    private $otherKey;
    private $randNumber;
    private $DB;
    private $url ;


    public function __construct(){
        $this->randNumber =createRandomStr(8,true) ;
        $this->userKey = C('config.zbw_key');
        $this->otherKey =  C('config.zbw_str');
        $this->DB =  C('config.zbw_db');
        $this->url = C('config.zbw_url');
    }

    public function get_key(){
        $timestamp = date('YmdHis');
        return array(
            'KEY'=>
                substr($this->randNumber,0,4)
                 .strtoupper(md5($this->randNumber.'|'.$this->userKey.'|'.$this->otherKey.'|'.$timestamp))
                 .substr($this->randNumber,4,8),
            'DateTime'=>$timestamp);
    }
    public function test(){
        $data['Parm']= "suLK1MGsvdM=";
        $json= json_encode($data, JSON_UNESCAPED_UNICODE);
        $return = httpRequest($this->url.'test','post',$json);
    }

    //2.会员信息
    public function GetVipInfo($carid){
        $data['CodeType']= 'UTF8';
        $data['Base64']= TRUE;
        $data['Parm']= base64_encode($carid);
        $result = $this->api_result($data,'GetVipInfo');
        $result = base64_decode($result['data']);

        $result = preg_replace("/\t+/","|",$result);
        $result = explode('|',$result);

        if(count($result)==38){
            $card['card_id'] = $result[0];
            $card['type'] = $result[1];
            $card['name'] = $result[2];
            $card['sex'] = $result[3];
            $card['create_staff'] = $result[7];
            $card['create_time'] = substr($result[8],0,19);
            $card['update_time'] = substr($result[26],0,19);
            $card['pass'] = $result[20];
            $card['phone'] = $result[34];
            $card['money'] = $result[36];
            $card['score'] = $result[37];
        }else if(count($result)==34){
            $card['card_id'] = $result[0];
            $card['type'] = $result[1];
            $card['name'] = $result[2];
            $card['sex'] = $result[3];
            $card['create_staff'] = $result[7];
            $card['create_time'] = substr($result[8],0,19);
            $card['update_time'] = substr($result[26],0,19);
            $card['pass'] = $result[20];
            $card['phone'] = $result[30];
            $card['money'] = $result[32];
            $card['score'] = $result[33];
        }else if(count($result)==32){
            $card['card_id'] = $result[0];
            $card['type'] = $result[1];
            $card['name'] = $result[2];
            $card['sex'] = $result[3];
            $card['create_staff'] = $result[7];
            $card['create_time'] = substr($result[7],0,19);
            $card['update_time'] = substr($result[21],0,19);
            $card['pass'] = $result[27];
            $card['phone'] = $result[28];
            $card['money'] = $result[30];
            $card['score'] = $result[31];
        }
        return $card;
    }

    public function GetVipInfoTel($phone){
        $data['CodeType']= 'UTF8';
        $data['Base64']= TRUE;
        $data['Parm']= base64_encode($phone);
        $result = $this->api_result($data,'GetVipInfoTel');

        $result = base64_decode($result['data']);
        if(empty($result)) {
            return null;
        }
        $result = preg_replace("/\t+/","|",$result);
        $result = explode('|',$result);
        if(count($result)==38){
            $card['card_id'] = $result[0];
            $card['type'] = $result[1];
            $card['name'] = $result[2];
            $card['sex'] = $result[3];
            $card['create_staff'] = $result[7];
            $card['create_time'] = substr($result[8],0,19);
            $card['update_time'] = substr($result[26],0,19);
            $card['pass'] = $result[20];
            $card['phone'] = $result[34];
            $card['money'] = $result[36];
            $card['score'] = $result[37];
        }else if(count($result)==34){
            $card['card_id'] = $result[0];
            $card['type'] = $result[1];
            $card['name'] = $result[2];
            $card['sex'] = $result[3];
            $card['create_staff'] = $result[7];
            $card['create_time'] = substr($result[8],0,19);
            $card['update_time'] = substr($result[26],0,19);
            $card['pass'] = $result[20];
            $card['phone'] = $result[30];
            $card['money'] = $result[32];
            $card['score'] = $result[33];
        }

        return $card;
    }

    public function GetVipSpareCash($carid){
        $data['Parm']= base64_encode($carid);
        $result = $this->api_result($data,'GetVipSpareCash');

        $money = base64_decode($result['data'],true);
       return $money;
    }

    public function GetVipIntegral($carid){
        $data['Parm']= base64_encode($carid);
        $result = $this->api_result($data,'GetVipIntegral');
        $score = $result['data'];
        return $score;
    }
    public function GetVipMoneyFlow($carid,$date_from=0){
        if(!$date_from){
            $date_from = strtotime(date('Ymd'));
        }
        $data['ParmJson']['CardId'] = $carid;
        $data['ParmJson']['DateFrom'] = date('Y-m-d H:i:s',$date_from);
        $data['ParmJson']['DateTo'] =date('Y-m-d H:i:s');
        $data['ParmJson']['PageNum'] = 100;
        $data['ParmJson']['Page'] =1  ;
        $data['CodeType']= 'UTF8';
        $data['Base64']= TRUE;
        $result = $this->api_result($data,'GetVipMoneyFlow');
        $result = base64_decode($result['data']);
        if(empty($result)){
            return array();
        }
        $result = explode(PHP_EOL,$result);
        $money_record_list = array();

        foreach ($result as $item) {
            $item = preg_replace("/\t+/","|",$item);
            $item = explode('|',$item);

            $money_record['id'] = $item[0];
            $money_record['staff'] = $item[2];

            $money_record['add_time'] = substr($item[3],0,19);
            $money_record['card_id'] = $item[4];

            if($item[5]=='B'){
                $money_record['origin_money'] = 0;
                $money_record['make_money'] = 0;
                $money_record['new_money'] = 0;
                $money_record['type'] = $item[8];
                $money_record['des'] = $item[9];
                $money_record['pay_type'] = $item[8];
            } if($item[5]=='SB'){
                $money_record['origin_money'] = $item[6];
                $money_record['make_money'] = $item[7];
                $money_record['new_money'] = $item[8];
                $money_record['type'] = '退款';
                $money_record['des'] = $item[9];
                $money_record['pay_type'] = $item[11];
            }if($item[5]=='SA'){
                $money_record['origin_money'] = $item[6];
                $money_record['make_money'] = $item[7];
                $money_record['new_money'] = $item[8];
                $money_record['type'] ='购买';
                $money_record['des'] = $item[9];
                $money_record['pay_type'] = $item[11];
            }else{
                $money_record['origin_money'] = $item[6];
                $money_record['make_money'] = $item[7];
                $money_record['new_money'] = $item[8];
                $money_record['type'] = $item[9];
                $money_record['des'] = $item[10];
                $money_record['pay_type'] = $item[11];
            }

            $money_record_list[] = $money_record;
        }
        return $money_record_list;
    }

    public function GetVipIntegralFlow($carid,$date_from=0){
        if(!$date_from){
            $date_from = strtotime(date('Ymd'));
        }
        $data['ParmJson']['CardId'] = $carid;
        $data['ParmJson']['DateFrom'] = date('Y-m-d H:i:s',$date_from);
        $data['ParmJson']['DateTo'] =date('Y-m-d H:i:s') ;
        $data['ParmJson']['PageNum'] = 100;
        $data['ParmJson']['Page'] =1;
        $data['CodeType']= 'UTF8';
        $data['Base64']= TRUE;
        $result = $this->api_result($data,'GetVipIntegralFlow');
        $result = base64_decode($result['data']);

        if(empty($result)){
            return array();
        }
        $score_record_list =array();
        $result = explode(PHP_EOL,$result);

        foreach ($result as $item) {
            $item = preg_replace("/\t+/","|",$item);

            $item = explode('|',$item);

            if(count($item)==12){
                $score_record['id'] = $item[0];
                $score_record['staff'] = $item[2];
                $score_record['add_time'] = substr($item[3],0,19);
                $score_record['card_id'] = $item[4];
                $score_record['type'] = $item[5];
                $score_record['origin_score'] = $item[6];
                $score_record['make_score'] = $item[7];
                $score_record['new_score'] = $item[8];
                $score_record['des'] = $item[9];
            }else{
                $score_record['id'] = $item[0];
                $score_record['staff'] = $item[2];
                $score_record['add_time'] = substr($item[3],0,19);
                $score_record['card_id'] = $item[4];
                $score_record['type'] = $item[5];
                $score_record['origin_score'] = $item[6];
                $score_record['make_score'] = $item[7];
                $score_record['new_score'] = $item[8];
                $score_record['des'] = $item[9];
            }
            $score_record_list[] = $score_record;

        }
       return $score_record_list;
       // $score = $result['data'];
    }

    //创建会员卡
    public function VipCreate($user){
        $data['ParmJson']['CardId'] =  sprintf('%09s', $user['uid']);;
        $data['ParmJson']['CardType'] = 'WX';
        $data['ParmJson']['VipMobile'] =strval($user['phone']);
        $data['ParmJson']['VipName'] = $user['nickname']?$user['nickname']:strval($user['phone']);
        $data['ParmJson']['VipSex'] = $user['sex']==1?'男':'女';
        $data['ParmJson']['IsIntegral'] = '1';
        $data['ParmJson']['Integral'] =floatval($user['score_count']);
        $data['ParmJson']['IsSaving'] = '1';
        $data['ParmJson']['Spare'] =floatval( $user['now_money']);

        $result = $this->api_result($data,'VipCreate');

        if($result['result']){
            return $data['ParmJson']['CardId'] ;
        }else{
            return false;
        }

    }

    //积分消费增加 +,-
    public function VipSaleSheet($user,$score,$des){
        $data['ParmJson']['BranchJg'] = '00';
        $data['ParmJson']['OperID'] ='00' ;
        $data['ParmJson']['SheetNo'] ='1' ;
        $data['ParmJson']['VipNo'] = strval($user['zbw_cardid']);
        $data['ParmJson']['SheetAmt'] = 1;
        $data['ParmJson']['VipIntegral'] =$score;
        $data['ParmJson']['sDateTime'] = date('Y-m-d H:i:s');
        $data['ParmJson']['Memo'] = $des;
        $result = $this->api_result($data,'VipSaleSheet');
        return $result;
    }

    //储值卡消费
    public function VipPaySheet($user,$money,$des){
        $data['ParmJson']['BranchJg'] = '00';
        $data['ParmJson']['OperID'] ='1';
        $data['ParmJson']['SheetNo'] = '1';
        $data['ParmJson']['VipNo'] = strval($user['zbw_cardid']);
        $data['ParmJson']['PayAmt'] = floatval($money);
        $data['ParmJson']['Memo'] = substr($des,0,10);;
        $data['ParmJson']['sDateTime'] = date('Y-m-d H:i:s');


//        $data['ParmJson']=json_decode($str,true);
        $result = $this->api_result($data,'VipPaySheet');

        return $result;
    }

    //会员充值
    public function VipFullAmt($user,$money,$remark=''){

        $data['ParmJson']['BranchJg'] = '00';
        $data['ParmJson']['OperID'] = '0' ;
        $data['ParmJson']['VipNo'] =  strval($user['zbw_cardid']);
        $data['ParmJson']['FullAmt'] = floatval($money);
        $data['ParmJson']['GiveAmt'] = 0;
        $data['ParmJson']['Memo'] = $remark;
        $data['ParmJson']['SaleMan'] = '0';

        $result = $this->api_result($data,'VipFullAmt');

        return $result;
    }

    //退款
    public function VipRetSheet($cardid,$money,$score,$order_id,$remark=''){
        $data['ParmJson']['BranchJg'] = '00';
        $data['ParmJson']['OperID'] = '0' ;
        $data['ParmJson']['SheetNo'] = '0';
        $data['ParmJson']['VipNo'] = $cardid;
        $data['ParmJson']['SheetAmt'] = floatval($money);
        $data['ParmJson']['PayAmt'] =  floatval($money);
        $data['ParmJson']['VipIntegral'] =  floatval($score);
        $data['ParmJson']['Memo'] = $remark;
        $data['CodeType']= 'UTF8';
        $data['Base64']= TRUE;

        $result = $this->api_result($data,'VipRetSheet');

        return $result;
    }

    public function api_result($data,$method='test'){
        $key =$this->get_key();
        $data['KEY']=$key['KEY'];
        $data['DateTime']=$key['DateTime'];
        $data['DB']=$this->DB;
        $json= json_encode($data, JSON_UNESCAPED_UNICODE);

        $return = httpRequest($this->url.$method,'post',$json);

        $return = json_decode($return[1],true);

        return $return;
    }

    //同步卡号
    public function sync_data($uid){

        $now_user = M('User')->where(array('uid'=>$uid))->find();
        $card_id = $now_user['zbw_cardid'];

        if($card_id){
            $card =$this->GetVipInfo($card_id);
        }else{
            $card =$this->GetVipInfoTel($now_user['phone']);
        }

        if($card){
            if(!$now_user['last_zbw_sync_time']){
                $now_user['last_zbw_sync_time'] = strtotime($card['create_time']);
            }
            if(!$card_id){
                $sync_date['zbw_cardid'] = $card['card_id'];
                $sync_date['last_zbw_sync_time'] = $_SERVER['REQUEST_TIME'];
                M('User')->where(array('uid'=>$now_user['uid']))->save($sync_date);
            }
            //已卡的金额为准
            $money =$card['money'];

            if(floatval($money)!=floatval($now_user['now_money'])){
                M('User')->where(array('uid'=>$now_user['uid']))->setField('now_money',floatval($money));
            }

            $score =$card['score'];
            if(floatval($score)!=floatval($now_user['score_count'])){
                M('User')->where(array('uid'=>$now_user['uid']))->setField('score_count',floatval($score));
            }

            $money_list =$this->GetVipMoneyFlow($card['card_id'],$now_user['last_zbw_sync_time']);
            $score_list = $this->GetVipIntegralFlow($card['card_id'],$now_user['last_zbw_sync_time']);

            $update = false;
            if($money_list){
                foreach ($money_list as $v) {
                    $money_where['time'] = strtotime($v['add_time']);
                    $money_where['uid'] = $now_user['uid'];
                    $money_where['money'] = abs(floatval($v['make_money']));
                    if(!M('User_money_list')->where($money_where)->find()){
                        $res = D('User_money_list')->add_row($now_user['uid'],($v['make_money']<0?2:1), abs($v['make_money']),$v['des'],true,0,0,false,$money_where['time']);
                        if($res){
                            $update = true;
                        }
                    }
                }
            }

            if($score_list){
                foreach ($score_list as $v) {
                    $score_where['time'] = strtotime($v['add_time']);
                    $score_where['uid'] = $now_user['uid'];
                    $score_where['score'] = floatval($v['make_score']);

                    if(!M('User_score_list')->where($score_where)->find()){
                        $res = D('User_score_list')->add_row($now_user['uid'],($v['make_score']<0?2:1), abs($v['make_score']),$v['des'],true,false,$score_where['time']);
                        if($res){
                            $update = true;
                        }
                    }
                }
            }
            if($update){
                M('User')->where(array('uid'=>$now_user['uid']))->setField('last_zbw_sync_time',time());
            }

        }else{
            if( $now_user['phone']){
                $zbw_card = $this->VipCreate($now_user);

                if($zbw_card){
                    $sync_date['zbw_cardid'] = $zbw_card;
                    $sync_date['last_zbw_sync_time'] = $_SERVER['REQUEST_TIME'];
                    M('User')->where(array('uid'=>$now_user['uid']))->save($sync_date);
                }
            }
        }
    }

}
