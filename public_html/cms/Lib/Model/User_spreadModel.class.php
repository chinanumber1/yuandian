<?php
// 推广分佣
class User_spreadModel extends Model{
    public function get_spread_user($now_user_openid,$uid){
        //$now_user_spread_from = M('User_spread us')->join(C('DB_PREFIX').'user u ON us.spread_openid  = u.openid')->where(array('us.openid'=>$now_user_openid))->find();
        $now_user_spread_user_list =$this->join('as us left join '.C('DB_PREFIX').'user u ON us.openid  = u.openid')->where(array('us.spread_openid'=>$now_user_openid,'us.openid'=>array('neq','')))->select();
        foreach($now_user_spread_user_list as &$v){
            $v['spread_count'] = M('User_spread')->where(array('spread_openid'=>$v['openid']))->count();
            $v['spread_money'] = M('User_spread_list')->where(array('get_uid'=>$v['uid'],'uid'=>$uid))->sum('money');
            $v['spread_count'] = empty($v['spread_count'])?0:$v['spread_count'];
            $v['spread_money'] = empty($v['spread_money'])?0:$v['spread_money'];
        }
        return array('spread_user_list'=>$now_user_spread_user_list);
    }

    //过户用户列表
    public function get_spread_change_user($uid){
        $change_user = M('User')->where(array('spread_change_uid'=>$uid))->select();
        foreach($change_user as &$v){
            $v['spread_count'] = $this->where(array('spread_openid'=>$v['openid']))->count();
            $v['spread_money'] = M('User_spread_list')->where(array('uid'=>$v['uid'],'change_uid'=>$uid))->sum('money');
        }
        return array('spread_change_user_list'=>$change_user);
    }

    public function get_spread_num($now_user_openid,$uid){
        return $this->join('as us left join '.C('DB_PREFIX').'user u ON us.spread_uid  = u.uid')->where(array('us.spread_uid'=>$uid,'us.uid'=>array('neq','')))->count();

    }

    public function send_spread_msg($openid,$spread_openid,$is_wxapp=false){
        if($is_wxapp){
            $now_user = D('User')->get_user($openid,'wxapp_openid');
        }else{
            $now_user = D('User')->get_user($openid,'openid');
        }
        if(empty($now_user)){
            if($is_wxapp){
                $now_user_weixin = D('User')->get_userinfo_wxapp($_POST['code']);
            }else{
                $now_user_weixin = D('User')->get_userinfo_weixin($openid);
            }
            $nickname = $now_user_weixin['nickname'];
        }else{
            $nickname = $now_user['nickname'];
        }
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        $model->sendTempMsg('TM00356', array('href' => C('config.site_url').'/wap.php?g=Wap&c=My&a=spread_user_list', 'wecha_id' =>$spread_openid, 'first' => '推广用户信息',  'work' =>$nickname.'通过您的分享成为了您的下级推广用户。时间：'.date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'));

    }

    public  function get_spread_list($openid,$level=1,$user_spread=array()){
        $where['openid'] = $openid;
        $res = $this->where($where)->find();

        if(!$res['spread_openid']){
            return $user_spread;
        }

        if( $level<4){
            $user_spread[$level] = $res['spread_uid'];
            $level++;
            return  $this->get_spread_list($res['spread_openid'],$level,$user_spread);
        }else{
            return $user_spread;
        }
    }

}