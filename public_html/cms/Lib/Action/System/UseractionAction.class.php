<?php
/*
 * 用户行为分类
 *
 */
class UseractionAction extends BaseAction{
    //  用户行为分类
    public  function    index(){
        $action_category    =   M('action_category')->order('action_id DESC')->select();
        $count  =   count($action_category);
        $p = new Page($count, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('action_category',$action_category);
        $this->display();
    }
    public  function    add(){
        $this->assign('type',$_GET['type']);
        $this->assign('action_id',$_GET['action_id']);
        $this->display();
    }
    public function modify(){
        if(IS_POST){
            if($_GET['status']==1){
                $database_action = M('Action_category');
                $condition_action['action_name'] = $_POST['action_name'];
                if($database_action->where($condition_action)->find()){
                    $this->error('数据库中已存在相同的分组名，请更换。');
                }
                $add    =   $database_action->data($_POST)->add();
            }else if($_GET['status']==2){
                $database_relation = M('Action_relation');
                $condition_relation['cat_type'] = $_POST['cat_type'];
                $condition_relation['cat_id'] = $_POST['cat_id'];
                $condition_relation['action_id'] = $_POST['action_id'];
                if($database_relation->where($condition_relation)->find()){
                    $this->error('数据库中已存在相同的行为关系，请更换。');
                }
                $add    =   $database_relation->data($_POST)->add();
            }
            if($add){
                import('ORG.Util.Dir');
                Dir::delDirnotself('./runtime');
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }
    public function edit(){
        if($_GET['type']==1){
            $database_action = M('action_category');
            $condition_action['action_id'] = $_GET['action_id'];
            $now_area = $database_action->field(true)->where($condition_action)->find();
        }else if($_GET['type']==2){
            $database_action = M('Action_relation');
            $condition_action['rela_id'] = $_GET['rela_id'];
            $now_area = $database_action->field(true)->where($condition_action)->find();
        }
        if(empty($now_area)){
            $this->frame_error_tips('数据库中没有查询到该信息！');
        }
        $this->assign('now_area',$now_area);
        $this->display();
    }
    public function amend(){
        if(IS_POST){
            if($_GET['type']==1){
                $database_action = M('Action_category');
                $condition_action['action_name'] = $_POST['action_name'];
            }else if($_GET['type']==2){
                $database_action = M('Action_relation');
                $condition_action['cat_type'] = $_POST['cat_type'];
                $condition_action['cat_id'] = $_POST['cat_id'];
            }
            if($database_action->where($condition_action)->find()){
                $this->error('数据库中已存在相同的数据，请更换。');
            } 
            if($database_action->data($_POST)->save()){
                import('ORG.Util.Dir');
                Dir::delDirnotself('./runtime');
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }
    public function del(){
        if(IS_POST){
            if($_GET['status']==1){
                $database_action = M('action_category');
                $condition_action['action_id'] = $_POST['action_id'];
                $return =   $database_action->where($condition_action)->delete();
                if($return){
                    $database_relation = M('Action_relation');
                    $delete =   $database_relation->where($condition_action)->delete();
                }
                if($delete){
                    $database_user_log = M('Action_user_log');
                    $database_user_log->where($condition_action)->delete();
                }
            }else if($_GET['status']==2){
                $database_relation = M('Action_relation');
                $condition_relation['rela_id'] = $_POST['rela_id'];
                $return =   $database_relation->where($condition_relation)->delete();
                if($return){
                    $database_user_log = M('Action_user_log');
                    $database_user_log->where($condition_relation)->delete();
                }
            }else if($_GET['status']==3){
                $database_user_log = M('action_user_log');
                $condition_user_log['log_id'] = $_POST['log_id'];
                $return =   $database_user_log->where($condition_user_log)->delete();
            }
            import('ORG.Util.Dir');
            Dir::delDirnotself('./runtime');
            if($return){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }
    public  function    relation(){
        $action_id['action_id']  =   $_GET['action_id'];
        $action_relation    =   M('Action_relation')->order('rela_id DESC')->where($action_id)->select();
        $count  =   count($action_relation);
        $p = new Page($count, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('action_relation',$action_relation);
        $this->assign('action_name',$_GET['action_name']);
        $this->display();
    }
    public  function    userLog(){
        if($_GET['status']==1){
            $action_id['rela_id']    =   $_GET['rela_id'];
        }else if($_GET['status']==2){
            $action_id['action_id']    =   $_GET['action_id'];
        }
        $action_relation    =   M('Action_user_log')->order('log_id DESC')->where($action_id)->select();
        $count  =   count($action_relation);
        $p = new Page($count, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('action_relation',$action_relation);
        $this->assign('action_name',$_GET['action_name']);
        $this->display();
    }
    public  function    userDetailsLog(){
        $aUser   =   M('User')->where($_GET)->find();
        $aUser['add_time']  =   date('Y-m-d H:i:s',$aUser['add_time']);
        $this->assign('aUser',$aUser);
        $this->display();
    }
    public  function    massGroup(){
        $aCate    =   M('Action_category')->select();
        $this->assign('aCate',$aCate);
        $this->display();
    }
    public  function    pushGroup(){
        $push_client       =   I('push_client',3);  // 客户端：1苹果  2安卓 3苹果、安卓
        $receiver_client   =   I('receiver_client',1);  // 群发方式：1APP  2微信
        $push_title        =   I('push_title');     // 标题：苹果专用
        $push_msg          =   I('push_msg');       // 发送内容
        $push_extra['url'] =   I('push_url');       // 跳转URL
        $checkname         =   I('checkname');
        $checkall          =   I('checkall');
        $page              =   I('count',1);
		dump($_GET);die;
        if(empty($push_title)){
            $this->error('标题不能为空');
        }else if(empty($push_msg)){
            $this->error('内容不能为空');
        }
        if($receiver_client==2){
            if($checkall){
                $aOpenId   =   M('User')->field('openid')->limit($page,'50')->select();
            }else{
                $aActionId['action_id']   =   array('in',$checkname);
                $aUids   =   M('action_user_log')->where($aActionId)->field('uid')->select();
                foreach($aUids as $v){
                    $aUid[] =   $v['uid'];
                }
                $aUid  =   array_unique($aUid);
                $arr['uid']    =   array('in',$aUid);
                $aOpenId   =   M('User')->where($arr)->field('openid')->limit($page,'50')->select();
            }
            if($aOpenId){
                foreach ($aOpenId as $userInfo){
                    if(empty($userInfo['openid'])){
                        continue;
                    }
                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $model->sendTempMsg('TM204601671', array('href' => $push_extra['url'], 'wecha_id' => $userInfo['openid'], 'first' => '您好，系统发布了一条新的消息\n', 'keynote2' => date('Y-m-d H:i:s'), 'keynote1' => $push_title, 'remark' => '\n请点击查看详细信息！'));
                }
                $page   =   $page+50;
                $this->success('不要关闭窗口，发送还在进行中...', U('Useraction/pushGroup', array('count' => $page,'receiver_client'=>$receiver_client,'checkname'=>$checkname,'checkall'=>$checkall,'push_title'=>$push_title,'push_msg'=>$push_msg,'push_url'=>$push_extra['url'])));
                exit;
            }else{
                $result['status']   =   1;
            }
        }else if($receiver_client==1){
            if($checkall){
                $audience   =   'all';
                $result = D('Group_order')->AuroraMass($push_client, $push_title, $push_msg, $push_extra,$audience);
            }else{
                $aActionId['action_id']   =   array('in',$checkname);
                $aUids   =   M('Action_user_log')->where($aActionId)->field('uid')->select();
                foreach($aUids as $v){
                    $aUid[] =   $v['uid'];
                }
                $aUid  =   array_unique($aUid);
                $arr['uid']    =   array('in',$aUid);
                $aDeviceId   =   M('User')->where($arr)->field('device_id')->select();
                if($aDeviceId){
                    foreach($aDeviceId as $v){
                        if($v['device_id'] && $v['device_id']!=200){
                            $tmp[]  =   str_replace('-','',$v['device_id']);
                        }
                    }
                    $audience   =   array('tag'=>$tmp);
                }else{
                    $this->error('未查询到用户');
                }
                $result = D('Group_order')->AuroraMass($push_client, $push_title, $push_msg, $push_extra,$audience);
            }
        }
        $aPushLog   =   array(
            'push_client'   =>  $push_client,
            'receiver_client'   =>  $receiver_client,
            'uid'   =>  isset($aUid)?serialize($aUid):'all',
            'action_id'   =>  isset($checkname)?'all':serialize($checkname),
            'push_title'   =>  $push_title,
            'push_msg'   =>  $push_msg,
            'push_url'   =>  $push_extra['url'],
            'push_status'   =>  $result['status'],
            'push_time'   =>  time(),
        );
        M('Action_push_log')->data($aPushLog)->add();
        $this->success($result['msg']);
    }
    //  查看群发记录
    public  function    pushLog(){
        $aPushLog    =   M('Action_push_log')->order('log_push_id desc')->select();
        foreach($aPushLog as $k=>$v){
            if($v['push_status']){
                $aPushLog[$k]['push_statu']  =   '成功';
            }else{
                $aPushLog[$k]['push_statu']  =   '失败';
            }
            if($v['receiver_client']==1){
                $aPushLog[$k]['receiver_client']  =   '生活通APP';
            }else if($v['receiver_client']==2){
                $aPushLog[$k]['receiver_client']  =   '生活通微信';
            }
            if($v['push_client'] == 3){
                $aPushLog[$k]['push_client']  =   '全部设备';
            }else if($v['push_client'] == 2){
                $aPushLog[$k]['push_client']  =   '安卓设备';
            }else if($v['push_client'] == 1){
               $aPushLog[$k]['push_client']  =   '苹果设备';
            }
            $aPushLog[$k]['push_time'] =   date('Y-m-d H:i:s',$v['push_time']);
        }
        $count  =   count($aPushLog);
        $p = new Page($count, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('aPushLog',$aPushLog);
        $this->display();
    }
    //  查看群发的用户
    public  function    pushUser(){
        $sLogPushId   =   $_GET['log_push_id'];
        if($sLogPushId){
            $aUids   =   M('Action_push_log')->where(array('log_push_id'=>$sLogPushId))->field('uid')->find();
            if($aUids['uid']){
                $aUid   =   unserialize($aUids['uid']);
                $where['uid']  =   array('in',$aUid);
                $aUser  =   M('User')->where($where)->select();
            }
        }else{
            
        }
        $count  =   count($aUser);
        $p = new Page($count, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('aUser',$aUser);
        $this->display();
    }
    //  查看群发的分组
    public  function    pushAction(){
        $sLogPushId   =   $_GET['log_push_id'];
        if($sLogPushId){
            $aUids   =   M('Action_push_log')->where(array('log_push_id'=>$sLogPushId))->field('action_id')->find();
            if($aUids['action_id']){
                $aUid   =   unserialize($aUids['action_id']);
                $where['action_id']  =   array('in',$aUid);
                $aUser  =   M('action_category')->where($where)->select();
            }
        }else{
            
        }
        $count  =   count($aUser);
        $p = new Page($count, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('aUser',$aUser);
        $this->display();
    }
}