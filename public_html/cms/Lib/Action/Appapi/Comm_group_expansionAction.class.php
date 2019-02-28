<?php
class Comm_group_expansionAction extends BaseAction{
    /**
     * [dynamic_add 动态-帖子添加]
     * @return [type] [description]
     */
    public function dynamic_add(){
        $data['community_id'] = $_POST['community_id'] ? $_POST['community_id'] : 0;//群ID
        $data['user_id'] = $this->_uid;//用户ID
        $data['content'] = $_POST['content'];//动态内容
        $data['addtime'] = time();
        $ret = D('Community_file')->msgSecCheck($data['content']);
        if ($ret['errcode'] != 0) {
            $this->returnCode(1, array(), '内容存在违规！请重试~');
        }
        if($_POST['pigcms_id']){//用户是否上传了图片
            $arr = $_POST['pigcms_id'] ? $_POST['pigcms_id'] : array();//传递的图片数组 pigcms_id为image表的自增id
            $where['pigcms_id'] = array('in',$arr);
            $where['status'] = array('neq',4);
            $res = D('Image')->field('pic')->where($where)->select();
            foreach($res as $k=>$v){
                $data['img'][] = $v['pic'];
            }
            $data['img'] = serialize($data['img']);
        }
        
        $file_model = D('Community_file');
        if($_POST['file_id']){//用户是否上传了文件
            $file_id = $_POST['file_id'] ? $_POST['file_id'] : '';//传递的文件ID
            $where['file_id'] = $file_id;
            $where['file_status'] = 1;
            $res = $file_model->field('file_id,file_remark,file_url,file_type,file_suffix')->where($where)->find();

            $data['file']['file_remark'] = $res['file_remark'];
            $data['file']['file_url'] = $res['file_url'];
            $data['file']['file_id'] = $res['file_id'];
            $file_info= $file_model->type_and_img($res['file_type'],$res['file_suffix'],true);
            $data['file']['file_img'] = $file_info['url'];
            $data['file']['is_img'] = $file_info['is_img'];
            $data['file'] = serialize($data['file']);
        }
        
        $result = D('Community_dynamic')->add($data);

        if($_POST['topic_id'] && $result){
            $array = array_unique(explode(',',$_POST['topic_id']));
            foreach($array as $key=>$value){
                $msg[$key]['dynamic_id'] = $result;
                $msg[$key]['topic_id'] = $value;
            }
            $final = D('Community_dynamic_topic_bind')->addAll($msg);
            if(!$final){
                $this->returnCode(1,array(),'添加话题绑定信息失败!');
            }
        }
        
        if($result != false){
            $des = '发布了一条动态，点击查看详情';
            D('Community_file')->create_plan($result, $des);
            $this->returnCode(0,'添加成功!');
        }else{
            $this->returnCode(1,array(),'添加失败!');
        }
    }

    /**
     * [upload_img 上传图片]
     * @return [type] [description]
     */
    public function upload_img(){
        if(!empty($_FILES) && $_FILES['file']['error'] != 4){
            $image = D('Image')->handle($this->_uid, 'comm', 2, array('size' => 10), false,true);
            if (!$image['error']) {
                $this->returnCode(0,$image['pigcms_id']);
            } else {
                $this->returnCode(1,array(), $image['message']);
            }
        }
    }

    /**
     * [del_img 删除图片]
     * @return [type] [description]
     */
    public function del_img(){
        $where['pigcms_id']  = $_POST['pigcms_id'];
        $data['status']  = 4;
        if($where['pigcms_id']){
            $result = D('Image')->where($where)->save($data);
            
            if($result != false){
                $this->returnCode(0,'删除成功!');
            }else{
                $this->returnCode(1,array(),'删除图片失败!');
            }
        }else{
            returnCode('100001',array(),'传递参数错误!');
        }
    }


    /**
     * [dynamic_list 动态列表]
     * @return [type] [description]
     */
    public function dynamic_list(){
        $where['a.community_id'] = $_POST['community_id'];
        $where['a.is_del'] = 0;
        $pageSize = 10;
        $page= $_POST['page'] ? $_POST['page'] : 1;
        $group_owner_uid = D('Community_info')->where(array('community_id'=>$_POST['community_id']))->getField('group_owner_uid');
        $dynamic_model = D('Community_dynamic');
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total =$dynamic_model->where(array('community_id'=>$_POST['community_id'],'is_del'=>0))->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);

           
            $Distinct = 'false';
            $field='a.*,b.avatar,b.nickname';
            $join='join pigcms_user as b on a.user_id=b.uid';
            $order = 'a.addtime desc';
            $limit = $firstRow.','.$pageSize;
            $list = $dynamic_model->dynamic_select($Distinct,$field,$join,$where,$order,$limit);

            $reply_model = D('Community_reply');
            $user_model = D('User');
            $user_field = 'nickname';
            $praise_model = D('Community_praise');
            foreach($list as $key=>$value){
                $maple['dynamic_id'] = $value['id'];
                $maple['reply_is_del'] = 0;
                $dynamic_reply = $reply_model->reply_select('','','',$maple,'','');
                foreach($dynamic_reply as $kk=>$vv){
                	$user_where1= array('uid'=>$dynamic_reply[$kk]['reply_user_id']);
                	$user_where2= array('uid'=>$dynamic_reply[$kk]['reply_to_user_id']);
                    $user = $user_model->user_find($user_field,$user_where1);
                    $to_user = $user_model->user_find($user_field,$user_where2);
                    if($this->_uid != $vv['reply_user_id']){
                        $dynamic_reply[$kk]['can_reply'] = true;
                    }else{
                        $dynamic_reply[$kk]['can_reply'] = false;
                    }
                    $dynamic_reply[$kk]['user'] = $user['nickname'];
                    $dynamic_reply[$kk]['to_user'] = $to_user['nickname'];
                }
                if($value['img']){
                    $list[$key]['img'] = unserialize($value['img']);
                    $dynamic_img = $list[$key]['img'];
                    $list[$key]['img'] = array();
                    foreach($dynamic_img as $k=>$v){
                        $list[$key]['img'][] = C('config.site_url') .$v;
                    }
                }
                if($value['file']){
                    $list[$key]['file'] = unserialize($value['file']);
                    $dynamic_file = $list[$key]['file'];
                    $list[$key]['file'] = array();
                    $list[$key]['file']['file_url'] = C('config.site_url') .$dynamic_file['file_url'];
                    $list[$key]['file']['file_remark'] = $dynamic_file['file_remark'];
                    $list[$key]['file']['file_id'] = $dynamic_file['file_id'];
                    $list[$key]['file']['file_img'] = $dynamic_file['file_img'];
                    $list[$key]['file']['is_img'] = $dynamic_file['is_img'];
                }
                if($value['application_detail']){
                    $list[$key]['application_detail'] = unserialize($value['application_detail']);
                }
                $praise_field = 'b.nickname';
                $praise_join = 'join pigcms_user as b on a.praise_user_id=b.uid';
                $praise_where = array('dynamic_id'=>$value['id']);          
                $praise_dynamic = $praise_model->praise_select('true',$praise_field,$praise_join,$praise_where,'','');//动态的点赞
                
                $arr = D('Community_dynamic_topic_bind')->field('topic_id')->where(array('dynamic_id'=>$value['id']))->select();
                $array=array();
                foreach ($arr as $kkk => $vvv) {
                    $array[] =  $vvv['topic_id']; 
                }
                
                $map['topic_id'] = array('in',$array);
                $topic = D('Community_topic')->field('topic_id,topic_title')->where($map)->select();
                $list[$key]['addtime'] = time_info($list[$key]['addtime']);
                $list[$key]['reply'] = $dynamic_reply;
                $list[$key]['praise'] = $praise_dynamic;
                $list[$key]['topic_list'] = $topic;

                //是否可以为用户本人
                $dynamic_user_id = $dynamic_model->dynamic_find('','user_id','',array('id'=>$list[$key]['id']));
                $dynamic_praise = $praise_model->praise_find('','','',array('dynamic_id'=>$value['id'],'praise_user_id'=>$this->_uid));
                if(!$dynamic_praise){
                    $list[$key]['can_praise'] = true;
                }else{
                    $list[$key]['can_praise'] = false;
                }
                //是否为群主或该动态的发布者,用有删除的去权限
                if($this->_uid == $group_owner_uid || $this->_uid == $dynamic_user_id['user_id']){
                    $list[$key]['can_del'] = true;
                }else{
                    $list[$key]['can_del'] = false;
                }
            }
            $info_list = array(
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            );
        }
        $this->returnCode(0,$info_list);
    }

    /**
     * [dynamic_detail 动态详情]
     * @return [type] [description]
     */
    public function dynamic_detail(){
            $where['a.community_id'] = $_POST['community_id'];
            $where['a.is_del'] = 0;
            $where['a.id'] = $_POST['id'];
            if(!$_POST['community_id'] || !$_POST['id']){
                $this->returnCode(1,'缺少参数!');
            }
            $group_owner_uid = D('Community_info')->get_community_info(array('community_id'=>$_POST['community_id']));

            $praise_model = D('Community_praise');
            $field = 'a.*,b.avatar,b.nickname,c.community_name';
            $join = 'join pigcms_user as b on a.user_id=b.uid join pigcms_community_info as c on a.community_id=c.community_id';
            $list = D('Community_dynamic')->dynamic_select(false,$field,$join,$where,'','');
            foreach($list as $key=>$value){
                $maple['dynamic_id'] = $value['id'];
                $maple['reply_is_del'] = 0;
                $dynamic_reply = D('Community_reply')->where($maple)->select();
                foreach($dynamic_reply as $kk=>$vv){
                    $user = D('User')->field('nickname')->where(array('uid'=>$dynamic_reply[$kk]['reply_user_id']))->find();
                    $to_user = D('User')->field('nickname')->where(array('uid'=>$dynamic_reply[$kk]['reply_to_user_id']))->find();
                    if($this->_uid != $vv['reply_user_id']){
                        $dynamic_reply[$kk]['can_reply'] = true;
                    }else{
                        $dynamic_reply[$kk]['can_reply'] = false;
                    }
                    $dynamic_reply[$kk]['user'] = $user['nickname'];
                    $dynamic_reply[$kk]['to_user'] = $to_user['nickname'];
                }

                if($value['img']){
                    $list[$key]['img'] = unserialize($value['img']);
                    $dynamic_img = $list[$key]['img'];
                    $list[$key]['img'] = array();
                    foreach($dynamic_img as $k=>$v){
                        $list[$key]['img'][] = C('config.site_url') .$v;
                    }
                }
                if($value['file']){
                    $list[$key]['file'] = unserialize($value['file']);
                    $dynamic_file = $list[$key]['file'];
                    $list[$key]['file'] = array();
                    $list[$key]['file']['file_url'] = C('config.site_url') .$dynamic_file['file_url'];
                    $list[$key]['file']['file_remark'] = $dynamic_file['file_remark'];
                    $list[$key]['file']['file_id'] = $dynamic_file['file_id'];
                    $list[$key]['file']['file_img'] = $dynamic_file['file_img'];
                    $list[$key]['file']['is_img'] = $dynamic_file['is_img'];
                }
                if($value['application_detail']){
                    $list[$key]['application_detail'] = unserialize($value['application_detail']);
                }
                $praise_field = 'b.nickname';
                $praise_join = 'join pigcms_user as b on a.praise_user_id=b.uid';
                $praise_where = array('dynamic_id'=>$value['id']);          
                $praise_dynamic = $praise_model->praise_select('true',$praise_field,$praise_join,$praise_where,'','');//动态的点赞

                $arr = D('Community_dynamic_topic_bind')->field('topic_id')->where(array('dynamic_id'=>$value['id']))->select();
                $array=array();
                foreach ($arr as $kkk => $vvv) {
                    $array[] =  $vvv['topic_id']; 
                }
                
                $map['topic_id'] = array('in',$array);
                $topic = D('Community_topic')->field('topic_id,topic_title')->where($map)->select();
                $list[$key]['addtime'] = time_info($list[$key]['dynamic_addtime']);
                $list[$key]['reply'] = $dynamic_reply;
                $list[$key]['praise'] = $praise_dynamic;
                $list[$key]['topic_list'] = $topic;
                $dynamic_praise = D('Community_praise')->where(array('dynamic_id'=>$value['id'],'praise_user_id'=>$this->_uid))->find();
                if(!$dynamic_praise){
                    $list[$key]['can_praise'] = true;
                }else{
                    $list[$key]['can_praise'] = false;
                }
                //是否为用户本人
                $dynamic_user_id = D('Community_dynamic')->field('user_id')->where(array('id'=>$list[$key]['id']))->find();
                //是否为群主或该动态的发布者,用有删除的去权限
                if($this->_uid == $group_owner_uid['group_owner_uid'] || $this->_uid == $dynamic_user_id['user_id']){
                    $list[$key]['can_del'] = true;
                }else{
                    $list[$key]['can_del'] = false;
                }
            }
            $info_list = array(
                'list' => $list
            );
            $this->returnCode(0,$info_list);
    }



    /**
     * [select_praise 动态点赞]
     * @return [type] [description]
     */
    public function add_praise(){
        $data['topic_id'] = $_POST['topic_id'] ? $_POST['topic_id'] : 0;
        $uid = $this->_uid;
        if(!$_POST['id']){
            $this->returnCode(1,'缺少参数!');
        }
        $praise_model = D('Community_praise');
        if($data['topic_id'] != 0){
            $where['praise_user_id'] = $uid;
            $where['dynamic_id'] = $_POST['id'];
            foreach($data['topic_id'] as $key =>$value){
                $where['topic_id'] = $value;
                $res = $praise_model->where($where)->find();
                if($res){//如果存在该话题已经被该用户点赞 则取消
                    $result_delete[] = $praise_model->where($where)->delete();
                }else{
                    $result_add[] = $praise_model->add($where);
                }
            }
            if($result_delete){
                $this->returnCode(0,'取消点赞成功');
            }
            if($result_add){
                $this->returnCode(0,'点赞成功');
            }
        }else{
            $data['dynamic_id'] = $_POST['id'];
            $data['praise_user_id'] = $uid;
            $res = $praise_model->where($data)->find();
            if($res){
                $result_delete = $praise_model->where($data)->delete();
            }else{
                $result_add = $praise_model->add($data);
            }
            if($result_delete){
                $this->returnCode(0,'取消点赞成功');
            }
            if($result_add){
                $this->returnCode(0,'点赞成功');
            }
        }
    }



    /**
     * [add_reply 评论添加]
     */
    public function add_reply(){
        $data['reply_user_id'] = $this->_uid ? $this->_uid : '';
        $data['dynamic_id'] = $_POST['id'] ? $_POST['id'] : '';
        $data['reply_content'] = $_POST['reply_content'] ? $_POST['reply_content'] : '';
        $data['reply_to_user_id'] = $_POST['reply_to_user_id'] ? $_POST['reply_to_user_id'] : 0;
        $data['topic_id'] = $_POST['topic_id'] ? $_POST['topic_id'] : 0;

        $result = D('Community_reply')->add($data);
        if($result != false){
            $this->returnCode(0,'评论成功!');
        }else{
            $this->returnCode(1,array(),'评论失败');
        }
    }


    /**
     * [del_topic 动态-帖子删除]
     * @return [type] [description]
     */
    public function del_dynamic(){
        $data['id'] = $_POST['id'];
        $data['is_del'] = 1;//0不删除1删除
        $data['user_id'] = $this->_uid;
        $data['community_id'] = $_POST['community_id'] ? $_POST['community_id'] : 0;
        if($data){
            $dynamic_user_id = D('Community_dynamic')->field('user_id')->where(array('id'=>$data['id']))->find();
            
            $group_owner_uid = D('Community_info')->field('group_owner_uid')->where(array('community_id'=>$data['community_id']))->find();
            if(!$dynamic_user_id){
                $this->returnCode('300002',array(),'查询用户信息失败!');
            }
            if(!$group_owner_uid){
                $this->returnCode('300001',array(),'查询群主信息失败!');
            }

            $arr=array_merge($dynamic_user_id,$group_owner_uid);
            if(!in_array($data['user_id'], $arr)){
                $this->returnCode('200001',array(),'无权删除!');
            }
            
            $result1 = D('Community_dynamic')->save($data);
            $result2 = D('Community_dynamic_topic_bind')->where(array('dynamic_id'=>$data['id']))->delete();
            if($result1 != false){
                D('Community_reply')->where(array('dynamic_id'=>$data['id']))->delete();//删除帖子的同时把该帖的评论删除
                D('Community_praise')->where(array('dynamic_id'=>$data['id']))->delete();//删除帖子的同时把该帖的点赞删除
                $this->returnCode(0,'删除成功!');
            }else{
                $this->returnCode(1,array(),'删除失败!');
            }
        }else{
            $this->returnCode('100001',array(),'传递参数错误!');
        }
    }

    /**
     * [del_reply 删除评论]
     * @return [type] [description]
     */
    // public function del_reply(){
    //  $data['reply_id'] = $_POST['reply_id'];
    //  $data['reply_user_id'] = $this->_uid;
    //  $data['community_id'] = $_POST['community_id'];
    //  if($data['community_id']){
    //      $group_owner_uid = D('Community_info')->field('group_owner_uid')->where(array('community_id'=>$data['community_id']))->find();
    //  }

    //  if($data){
 //            $reply_user_id = D('Community_reply')->field('reply_user_id')->where(array('reply_id'=>$data['reply_id']))->find();

 //            if(!$reply_user_id){
 //             $arr=array($reply_user_id);
 //                $this->returnCode('300001',array(),'查询用户信息失败!');
 //            }
 //            if(!$group_owner_uid){
 //                $this->returnCode('300001',array(),'查询群主信息失败!');
 //            }else{
 //             $arr=array_merge($reply_user_id,$group_owner_uid);
 //            }
 //            ;
 //            if(!in_array($data['reply_user_id'], $arr)){
 //                $this->returnCode('200001',array(),'无权删除!');
 //            }

 //            $result = D('Community_reply')->where(array('reply_id'=>$data['reply_id']))->save(array('reply_is_del'=>1));
 //            if($result != false){
 //                $this->returnCode(0,'删除成功!');
 //            }else{
 //                $this->returnCode(1,array(),'删除失败!');
 //            }
 //        }else{
 //            $this->returnCode('100001',array(),'传递参数错误!');
 //        }
    // }

//--------------------------------------------群话题start---------------------------------------------------------------//
    
    /**
     * [add_topic 添加话题]
     */
    public function add_topic(){
        $data['topic_cate_id'] = $_POST['topic_cate_id'];
        $data['topic_user_id'] = $this->_uid;
        $data['topic_title'] = $_POST['topic_title'];
        $data['topic_content'] = $_POST['topic_content'];
        $data['community_id'] = $_POST['community_id'];
        $data['topic_addtime'] = time();
        if(!$data['topic_cate_id'] || !$data['topic_user_id'] || !$data['topic_title'] || !$data['topic_content']){
            $this->returnCode('100001',array(),'传递参数错误!');
        }
       
        $where['pigcms_id'] = $_POST['pigcms_id'];
        $where['status'] = array('neq',4);
        $res = D('Image')->field('pic')->where($where)->select();
        foreach($res as $k=>$v){
            $data['topic_img'][] = $v['pic'];
        }

        $data['topic_img'] = serialize($data['topic_img']);
        
        $info_list = D('Community_topic')->add($data);
        if($info_list){
            $this->returnCode(0,'添加话题成功!');
        }
    }


    /**
     * [report_cate_list 举报分类列表]
     * @return [type] [description]
     */
    public function report_cate_list(){
        $where['report_cate_is_del'] = 0;
        $info_list = D('Community_report_cate')
                    ->where($where)
                    ->select();
        $this->returnCode(0,$info_list);
    }

    /**
     * [add_report 添加举报]
     */
    public function add_report(){
        $data['user_id'] = $this->_uid;
        $data['report_cate_id'] = $_POST['report_cate_id'];
        $data['topic_id'] = $_POST['topic_id'];
        $data['dynamic_id'] = $_POST['dynamic_id'];
        $data['report_addtime'] = time();
        
        if(!$data['user_id'] || !$data['report_cate_id'] || !$data['topic_id'] || !$data['dynamic_id']){
            $this->returnCode('100001',array(),'传递参数错误!');
        }
        $list = D('Community_report_user_bind')->add($data);
        if($list){
            $this->returnCode(0,'举报成功!后台将及时审核并处理~');
        }else{
            $this->returnCode(1,array(),'举报失败!');
        }
    }

    /**
     * [topic_cate_list 话题分类列表]
     * @return [type] [description]
     */
    public function topic_cate_list(){
        $where['topic_cate_is_del'] = 0;
        $info_list = D('Community_topic_cate')
                    ->where($where)
                    ->select();
        $this->returnCode(0,$info_list);
    }

       /**
     * [topic_list 话题列表]
     * @return [type] [description]
     */
    public function topic_list(){
        $where['topic_is_del'] = 0;
        $where['community_id'] = $_POST['community_id'];
        if(!$_POST['community_id']){
            $this->returnCode('传递参数错误!');
        }
        if($_POST['topic_cate_id']){
            $where['topic_cate_id'] = $_POST['topic_cate_id'];
        }
        if ($_POST['key_words']) {
            $where['topic_title'] = array('like','%'.$_POST['key_words'].'%');
        }
        $pageSize = 10;
        $total = D('Community_topic')->where($where)->count();
        $page= $_POST['page'] ? $_POST['page'] : 1;
        $page = isset($page) ? intval($page) : 1;
        $totalPage = ceil($total / $pageSize);
        $firstRow = $pageSize * ($page - 1);
        $str='';
        if($page > 0){
            $list = D('Community_topic')
                    ->order('topic_addtime desc')
                    ->where($where)
                    ->limit($firstRow.','.$pageSize)
                    ->select();
            foreach($list as $key=>$value){
                //帖子总数
                $note_total_count = D('Community_dynamic_topic_bind')->where(array('topic_id'=>$value['topic_id']))->count();

                if($value['topic_img']){
                    $list[$key]['topic_img'] = unserialize($value['topic_img']);
                    $topic_img = $list[$key]['topic_img'];
                    $list[$key]['topic_img'] = array();
                    foreach($topic_img as $k=>$v){
                        $list[$key]['topic_img'][] = C('config.site_url') .$v;
                    }
                }
                //创建人
                $people_topic = D('Community_topic')->field('topic_user_id as user_id')->where(array('topic_id'=>$value['topic_id'],'community_id'=>$_POST['community_id']))->select();
                //帖子人数                                              
                $people_dynamic_topic = D('Community_dynamic')
                                        ->alias('a')
                                        ->Distinct(true)
                                        ->field('a.user_id')
                                        ->join('join pigcms_community_dynamic_topic_bind as b on a.id=b.dynamic_id')
                                        ->where(array('b.topic_id'=>$value['topic_id']))
                                        ->select();
                
                $reply_count = D('Community_reply')->Distinct(true)->field('reply_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//回复人数
                $praise_count = D('Community_praise')->Distinct(true)->field('praise_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//点赞人数
                if(!$people_dynamic_topic){
                    $people_dynamic_topic = array();
                }
                if(!$reply_count){
                    $reply_count = array();
                }
                if(!$praise_count){
                    $praise_count = array();
                }
                if(!$people_topic){
                    $people_topic = array();
                }
                
                $people_count=array();
                $people_count = array_merge($people_dynamic_topic,$reply_count,$praise_count,$people_topic);

                $temp=array();
                foreach ($people_count as $v) { 
                    $v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
                    $temp[] = $v; 
                } 

                $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
                foreach ($temp as $k => $v){
                    $temp[$k] = explode(",",$v); //再将拆开的数组重新组装 
                }
                $list[$key]['people_count']  = count($temp);
                $list[$key]['note_total_count'] = $note_total_count;//帖子总数
                }
        }
        $info_list = array(
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
        );
        $this->returnCode(0,$info_list);
    }

    /**
    * [type_topic_list 我关注的、我创建的、最近的话题]
    * @return [type] [description]
    */
    public function type_topic_list(){
    //全部分类
    if($_POST['type'] == 'all'){
        $pageSize = 10;
        $page= $_POST['page'] ? $_POST['page'] : 1 ;
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $where['community_id'] = $_POST['community_id'];
            if(!$_POST['community_id']){
                returnCode('没有接收到参数!');
            }
            $total = D('Community_topic')->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list  = D('Community_topic')
                ->where($where)
                ->order('topic_addtime desc')
                ->limit($firstRow.','.$pageSize)
                ->select();
            foreach($list as $key=>$value){
            //帖子总数
                $note_total_count = D('Community_dynamic_topic_bind')->where(array('topic_id'=>$value['topic_id']))->count();

                if($value['topic_img']){
                    $list[$key]['topic_img'] = unserialize($value['topic_img']);
                    $topic_img = $list[$key]['topic_img'];
                    $list[$key]['topic_img'] = array();
                    foreach($topic_img as $k=>$v){
                        $list[$key]['topic_img'][] = C('config.site_url') .$v;
                    }
                }
                //创建人
                $people_topic = D('Community_topic')->field('topic_user_id as user_id')->where(array('topic_id'=>$value['topic_id'],'community_id'=>$_POST['community_id']))->select();
        
                //帖子人数                                              
                $people_dynamic_topic = D('Community_dynamic')
                                        ->alias('a')
                                        ->Distinct(true)
                                        ->field('a.user_id')
                                        ->join('join pigcms_community_dynamic_topic_bind as b on a.id=b.dynamic_id')
                                        ->where(array('b.topic_id'=>$value['topic_id']))
                                        ->select();
                
                $reply_count = D('Community_reply')->Distinct(true)->field('reply_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//回复人数
                $praise_count = D('Community_praise')->Distinct(true)->field('praise_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//点赞人数
                if(!$people_dynamic_topic){
                    $people_dynamic_topic = array();
                }
                if(!$reply_count){
                    $reply_count = array();
                }
                if(!$praise_count){
                    $praise_count = array();
                }
                if(!$people_topic){
                    $people_topic = array();
                }
                
                $people_count=array();
                $people_count = array_merge($people_dynamic_topic,$reply_count,$praise_count,$people_topic);

                $temp=array();
                foreach ($people_count as $v) { 
                    $v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
                    $temp[] = $v; 
                } 

                $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
                foreach ($temp as $k => $v){
                    $temp[$k] = explode(",",$v); //再将拆开的数组重新组装 
                }
                $list[$key]['people_count']  = count($temp);
                $list[$key]['note_total_count'] = $note_total_count;//帖子总数
                }
        }
    }
        //我关注的话题
        if($_POST['type'] == 'focus'){
            $where['a.topic_is_del'] = 0;
            $where['b.user_id'] = $this->_uid;
            $where['a.community_id'] = $_POST['community_id'];
            if(!$_POST['community_id']){
                returnCode('没有接收到参数!');
            }
            $pageSize = 10;
            $page= $_POST['page'] ? $_POST['page'] : 1;
            // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
            if ($pageSize > 0) {
                $total = D('Community_topic')->alias('a')->join('join pigcms_community_user_topic_bind as b on a.topic_id=b.topic_id')->where($where)->count();
                $page = isset($page) ? intval($page) : 1;
                $totalPage = ceil($total / $pageSize);
                $firstRow = $pageSize * ($page - 1);
                $list = D('Community_topic')
                        ->alias('a')
                        ->field('a.*,b.topic_last_time')
                        ->join('join pigcms_community_user_topic_bind as b on a.topic_id=b.topic_id')
                        ->where($where)
                        ->limit($firstRow.','.$pageSize)
                        ->select();
                foreach ($list as $key => $value) {
                    $new_time = D('Community_dynamic')
                    ->alias('a')
                    ->field('a.content,a.addtime,b.nickname')
                    ->join('join pigcms_user as b on a.user_id=b.uid')
                    ->join('join pigcms_community_dynamic_topic_bind as c on a.id=c.dynamic_id')
                    ->where(array('c.topic_id'=>$value['topic_id']))
                    ->order('addtime desc')
                    ->find();
                    $list[$key]['new_message'] = $new_time;

                    if($value['topic_img']){
                        $list[$key]['topic_img'] = unserialize($value['topic_img']);
                        $topic_img = $list[$key]['topic_img'];
                        $list[$key]['topic_img'] = array();
                        foreach($topic_img as $k=>$v){
                            $list[$key]['topic_img'][] = C('config.site_url') .$v;
                        }
                    }

                //帖子总数
                $note_total_count = D('Community_dynamic_topic_bind')->where(array('topic_id'=>$value['topic_id']))->count();

                if($value['topic_img']){
                    $list[$key]['topic_img'] = unserialize($value['topic_img']);
                    $topic_img = $list[$key]['topic_img'];
                    $list[$key]['topic_img'] = array();
                    foreach($topic_img as $k=>$v){
                        $list[$key]['topic_img'][] = C('config.site_url') .$v;
                    }
                }
                //创建人
                $people_topic = D('Community_topic')->field('topic_user_id as user_id')->where(array('topic_id'=>$value['topic_id'],'community_id'=>$_POST['community_id']))->select();
                //帖子人数                                              
                $people_dynamic_topic = D('Community_dynamic')
                                        ->alias('a')
                                        ->Distinct(true)
                                        ->field('a.user_id')
                                        ->join('join pigcms_community_dynamic_topic_bind as b on a.id=b.dynamic_id')
                                        ->where(array('b.topic_id'=>$value['topic_id']))
                                        ->select();
                
                $reply_count = D('Community_reply')->Distinct(true)->field('reply_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//回复人数
                $praise_count = D('Community_praise')->Distinct(true)->field('praise_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//点赞人数
                if(!$people_dynamic_topic){
                    $people_dynamic_topic = array();
                }
                if(!$reply_count){
                    $reply_count = array();
                }
                if(!$praise_count){
                    $praise_count = array();
                }
                if(!$people_topic){
                    $people_topic = array();
                }
                
                $people_count=array();
                $people_count = array_merge($people_dynamic_topic,$reply_count,$praise_count,$people_topic);

                $temp=array();
                foreach ($people_count as $v) { 
                    $v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
                    $temp[] = $v; 
                } 

                $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
                foreach ($temp as $k => $v){
                    $temp[$k] = explode(",",$v); //再将拆开的数组重新组装 
                }
                $list[$key]['people_count']  = count($temp);
                $list[$key]['note_total_count'] = $note_total_count;//帖子总数
                }     
            }
            
        }
        //我发布的话题
        if($_POST['type'] == 'found'){
            $where['topic_is_del'] = 0;
            $pageSize = 10;
            $page= $_POST['page'] ? $_POST['page'] : 1;
            $where['topic_user_id'] = $this->_uid;
            $where['community_id'] = $_POST['community_id'];
            if(!$_POST['community_id']){
                returnCode('没有接收到参数!');
            }
            // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
            if ($pageSize > 0) {
                $total = D('Community_topic')->where($where)->count();
                $page = isset($page) ? intval($page) : 1;
                $totalPage = ceil($total / $pageSize);
                $firstRow = $pageSize * ($page - 1);
                $list  = D('Community_topic')->where($where)->limit($firstRow.','.$pageSize)->select();
                foreach($list as $key=>$value){
                    if($value['topic_img']){
                        $list[$key]['topic_img'] = unserialize($value['topic_img']);
                        $topic_img = $list[$key]['topic_img'];
                        $list[$key]['topic_img'] = array();
                        foreach($topic_img as $k=>$v){
                            $list[$key]['topic_img'][] = C('config.site_url') .$v;
                        }
                    }
                //帖子总数
                $note_total_count = D('Community_dynamic_topic_bind')->where(array('topic_id'=>$value['topic_id']))->count();

                if($value['topic_img']){
                    $list[$key]['topic_img'] = unserialize($value['topic_img']);
                    $topic_img = $list[$key]['topic_img'];
                    $list[$key]['topic_img'] = array();
                    foreach($topic_img as $k=>$v){
                        $list[$key]['topic_img'][] = C('config.site_url') .$v;
                    }
                }
                
                //创建人
                $people_topic = D('Community_topic')->field('topic_user_id as user_id')->where(array('topic_id'=>$value['topic_id'],'community_id'=>$_POST['community_id']))->select();
                //帖子人数                                              
                $people_dynamic_topic = D('Community_dynamic')
                                        ->alias('a')
                                        ->Distinct(true)
                                        ->field('a.user_id')
                                        ->join('join pigcms_community_dynamic_topic_bind as b on a.id=b.dynamic_id')
                                        ->where(array('b.topic_id'=>$value['topic_id']))
                                        ->select();
                
                $reply_count = D('Community_reply')->Distinct(true)->field('reply_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//回复人数
                $praise_count = D('Community_praise')->Distinct(true)->field('praise_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//点赞人数
                if(!$people_dynamic_topic){
                    $people_dynamic_topic = array();
                }
                if(!$reply_count){
                    $reply_count = array();
                }
                if(!$praise_count){
                    $praise_count = array();
                }
                if(!$people_topic){
                    $people_topic = array();
                }
                
                $people_count=array();
                $people_count = array_merge($people_dynamic_topic,$reply_count,$praise_count,$people_topic);

                $temp=array();
                foreach ($people_count as $v) { 
                    $v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
                    $temp[] = $v; 
                } 

                $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
                foreach ($temp as $k => $v){
                    $temp[$k] = explode(",",$v); //再将拆开的数组重新组装 
                }
                $list[$key]['people_count']  = count($temp);
                $list[$key]['note_total_count'] = $note_total_count;//帖子总数
                }
            }
        }
        //最近的话题
        if($_POST['type'] == 'recent'){
            $pageSize = 10;
            $page= $_POST['page'] ? $_POST['page'] : 1 ;
            // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
            if ($pageSize > 0) {
                $where['community_id'] = $_POST['community_id'];
                if(!$_POST['community_id']){
                    returnCode('没有接收到参数!');
                }
                $total = 5;
                $page = isset($page) ? intval($page) : 1;
                $totalPage = ceil($total / $pageSize);
                $firstRow = $pageSize * ($page - 1);
                $list  = D('Community_topic')
                    ->where($where)
                    ->order('topic_addtime desc')
                    ->limit(0,5)
                    ->select();
                foreach($list as $key=>$value){
                    if($value['topic_img']){
                        $list[$key]['topic_img'] = unserialize($value['topic_img']);
                        $topic_img = $list[$key]['topic_img'];
                        $list[$key]['topic_img'] = array();
                        foreach($topic_img as $k=>$v){
                            $list[$key]['topic_img'][] = C('config.site_url') .$v;
                        }
                    }
                //帖子总数
                $note_total_count = D('Community_dynamic_topic_bind')->where(array('topic_id'=>$value['topic_id']))->count();

                if($value['topic_img']){
                    $list[$key]['topic_img'] = unserialize($value['topic_img']);
                    $topic_img = $list[$key]['topic_img'];
                    $list[$key]['topic_img'] = array();
                    foreach($topic_img as $k=>$v){
                        $list[$key]['topic_img'][] = C('config.site_url') .$v;
                    }
                }
        
                //帖子人数                                              
                $people_dynamic_topic = D('Community_dynamic')
                                        ->alias('a')
                                        ->Distinct(true)
                                        ->field('a.user_id')
                                        ->join('join pigcms_community_dynamic_topic_bind as b on a.id=b.dynamic_id')
                                        ->where(array('b.topic_id'=>$value['topic_id']))
                                        ->select();
                
                $reply_count = D('Community_reply')->Distinct(true)->field('reply_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//回复人数
                $praise_count = D('Community_praise')->Distinct(true)->field('praise_user_id as user_id')->where(array('topic_id'=>$value['topic_id']))->select();//点赞人数
                if(!$people_dynamic_topic){
                    $people_dynamic_topic = array();
                }
                if(!$reply_count){
                    $reply_count = array();
                }
                if(!$praise_count){
                    $praise_count = array();
                }
                
                $people_count=array();
                $people_count = array_merge($people_dynamic_topic,$reply_count,$praise_count);

                $temp=array();
                foreach ($people_count as $v) { 
                    $v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
                    $temp[] = $v; 
                } 

                $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
                foreach ($temp as $k => $v){
                    $temp[$k] = explode(",",$v); //再将拆开的数组重新组装 
                }
                $list[$key]['people_count']  = count($temp);
                $list[$key]['note_total_count'] = $note_total_count;//帖子总数
                }
            }
        }
            $info_list = array(
                    'total' => $total,
                    'pageTotal' => $totalPage,
                    'has_more' => $totalPage > $page ? true : false,
                    'list' => $list
            );
            if($info_list != false){
                    $this->returnCode(0,$info_list);
                }else{
                    $this->returnCode(1,array(),'没有查询到结果!');
            }
    }


    

    /**
     * [note_list 帖子列表]
     * @return [type] [description]
     */
    public function note_list(){
        D('Community_user_topic_bind')->where(array('topic_id'=>$_POST['topic_id'],'user_id'=>$this->_uid))->data(array('topic_last_time'=>time()))->save();
        
        $whe['a.topic_is_del'] = 0;
        $whe['a.topic_id'] = $_POST['topic_id'];
        $whe['a.community_id'] = $_POST['community_id'];
        if(!$_POST['community_id']){
            $this->returnCode('传递参数错误!');
        }
        //该话题的信息
        $list_info = D('Community_topic')
                ->alias('a')
                ->field('a.*,b.nickname,b.avatar')
                ->join('join pigcms_user as b on a.topic_user_id=b.uid')
                ->where($whe)
                ->find();
        if($_POST['topic_id'] && $this->_uid){
            $is_concern = D('Community_user_topic_bind')->where(array('topic_id'=>$_POST['topic_id'],'user_id'=>$this->_uid))->find();

            if($is_concern){
                $list_info['concern'] = true;
            }else{
                $list_info['concern'] = false;
            }
        }        
        if($list_info['topic_img']){
            $list_info['topic_img'] = unserialize($list_info['topic_img']);
            $topic_img = $list_info['topic_img'];
            $list_info['topic_img'] = array();
            foreach($topic_img as $k=>$v){
                $list_info['topic_img'][] = C('config.site_url') .$v;
            }
        }
        
        $dynamic_topic_people = D('Community_dynamic')
                                ->alias('a')
                                ->Distinct(true)
                                ->field('a.id,a.user_id,c.nickname,c.avatar')
                                ->join('join pigcms_community_dynamic_topic_bind as b on a.id=b.dynamic_id')
                                ->join('join pigcms_user as c on a.user_id=c.uid')
                                ->where(array('b.topic_id'=>$list_info['topic_id']))
                                ->select();
        foreach ($dynamic_topic_people as $kk => $vv) {
            $where1['a.dynamic_id'] = $vv['id'];
            $where1['a.reply_is_del'] = 0;
            //回复的人
            $people_reply = D('Community_reply')
                            ->alias('a')
                            ->Distinct(true)
                            ->field('a.reply_user_id as user_id,b.avatar,b.nickname')
                            ->join('join pigcms_user as b on a.reply_user_id=b.uid')
                            ->where($where1)
                            ->select();
            //点赞的人
            $where2['a.dynamic_id'] = $vv['id'];
            $people_praise = D('Community_praise')
                            ->alias('a')
                            ->Distinct(true)
                            ->field('a.praise_user_id as user_id,b.avatar,b.nickname')
                            ->join('join pigcms_user as b on a.praise_user_id=b.uid')
                            ->where($where2)
                            ->select();
            $arrs[$kk]['avatar'] = $vv['avatar'];
            $arrs[$kk]['nickname'] = $vv['nickname'];
        }
        foreach($people_reply as $k=>$v){
            $arrs_reply[$k]['avatar'] = $v['avatar'];
            $arrs_reply[$k]['nickname'] = $v['nickname'];
        }
        foreach($people_praise as $k=>$v){
            $arrs_praise[$k]['avatar'] = $v['avatar'];
            $arrs_praise[$k]['nickname'] = $v['nickname'];
        }
        $info['people_avatar'] = array_unique($arrs,SORT_REGULAR);
        $info['people_reply'] = array_unique($arrs_reply);
        $info['people_praise'] = array_unique($arrs_praise);
        if(!$info['people_avatar'] && $info['people_reply'] && $info['people_praise']){
            $datas = array_merge($info['people_reply'],$info['people_praise']);
        }
        if(!$info['people_reply'] && $info['people_avatar'] && $info['people_praise']){
            $datas = array_merge($info['people_avatar'],$info['people_praise']);
        }
        if(!$info['people_praise'] && $info['people_avatar'] && $info['people_reply']){
            $datas = array_merge($info['people_avatar'],$info['people_reply']);
        }
        if(!$info['people_praise'] && !$info['people_reply']){
            $datas = $info['people_avatar'];
        }
        if($info['people_avatar'] && $info['people_reply'] && $info['people_praise']){
            $datas = array_merge($info['people_avatar'],$info['people_reply'],$info['people_praise']);
        }
        
        foreach($datas[0] as $k => $v){
            $arr_inner_key[]= $k;   //先把二维数组中的内层数组的键值记录在在一维数组中
        }
        foreach ($datas as $k => $v){
            $v =join(",",$v);    //降维 用implode()也行
            $temp[$k] =$v;      //保留原来的键值 $temp[]即为不保留原来键值
        }
        $temp =array_unique($temp);    //去重：去掉重复的字符串
        foreach ($temp as $k => $v){
            $a = explode(",",$v);   //拆分后的重组 如：Array( [0] => james [1] => 30 )
            $arr_after[$k]= array_combine($arr_inner_key,$a);  //将原来的键与值重新合并
        }
        $temp=array();
        $list_info['people_info'] = $arr_after;
        //创建人
        $people_topic = D('Community_topic')->field('topic_user_id as user_id')->where(array('topic_id'=>$list_info['topic_id'],'community_id'=>$_POST['community_id']))->select();
        //帖子人数                                              
        $people_dynamic_topic = D('Community_dynamic')
                                ->alias('a')
                                ->Distinct(true)
                                ->field('a.user_id')
                                ->join('join pigcms_community_dynamic_topic_bind as b on a.id=b.dynamic_id')
                                ->where(array('b.topic_id'=>$list_info['topic_id']))
                                ->select();
        
        $reply_count = D('Community_reply')->Distinct(true)->field('reply_user_id as user_id')->where(array('topic_id'=>$list_info['topic_id']))->select();//回复人数
        $praise_count = D('Community_praise')->Distinct(true)->field('praise_user_id as user_id')->where(array('topic_id'=>$list_info['topic_id']))->select();//点赞人数
        if(!$people_dynamic_topic){
            $people_dynamic_topic = array();
        }
        if(!$reply_count){
            $reply_count = array();
        }
        if(!$praise_count){
            $praise_count = array();
        }
        if(!$people_topic){
            $people_topic = array();
        }
        
        $people_count=array();
        $people_count = array_merge($people_dynamic_topic,$reply_count,$praise_count,$people_topic);

        $temp=array();
        foreach ($people_count as $v) { 
            $v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
            $temp[] = $v; 
        } 

        $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
        foreach ($temp as $k => $v){
            $temp[$k] = explode(",",$v); //再将拆开的数组重新组装 
        }
        $list_info['people_count']  = count($temp);
//------------------------------------------------------------------------------
        //-----帖子列表的信息------//
        $where['a.is_del'] = 0;
        if($_POST['topic_id']){
            $where['a.topic_id'] = $_POST['topic_id'];
        }

        $pageSize = 10;
        $page= $_POST['page'] ? $_POST['page'] : 1;
        
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $where_dynamic['_string'] = '`topic_id` in (' . $_POST['topic_id'] .')';

            $total = D('Community_dynamic_topic_bind')->where($where_dynamic)->count();

            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            
            $list = D('Community_dynamic')
                        ->alias('a')
                        ->field('a.*,b.topic_id,c.avatar,c.nickname')
                        ->join('join pigcms_community_dynamic_topic_bind as b on a.id=b.dynamic_id')
                        ->join('join pigcms_user as c on a.user_id=c.uid')
                        ->order('a.addtime desc')
                        ->where(array('a.is_del'=>0,'b.topic_id'=>$_POST['topic_id']))
                        ->limit($firstRow.','.$pageSize)
                        ->select();//符合话题的帖子包括动态
            foreach($list as $key=>$value){
                if($value['id']){
                    $maple['_string'] = '`dynamic_id` in (' . $value['id'] .')';
                }
                $maple['reply_is_del'] = 0;
                $reply = D('Community_reply')->where($maple)->select();
                foreach($reply as $kk=>$vv){
                    $user = D('User')->field('nickname')->where(array('uid'=>$reply[$kk]['reply_user_id']))->find();
                    $to_user = D('User')->field('nickname')->where(array('uid'=>$reply[$kk]['reply_to_user_id']))->find();
                    if($this->_uid != $vv['reply_user_id']){
                        $reply[$kk]['can_reply'] = true;
                    }else{
                        $reply[$kk]['can_reply'] = false;
                    }
                    $reply[$kk]['user'] = $user['nickname'];
                    $reply[$kk]['to_user'] = $to_user['nickname'];
                }
                
                if($value['img']){
                    $list[$key]['img'] = unserialize($value['img']);
                    $img = $list[$key]['img'];
                    $list[$key]['img'] = array();
                    foreach($img as $k=>$v){
                        $list[$key]['img'][] = C('config.site_url') .$v;
                    }
                }

                if($value['file']){
                    $list[$key]['file'] = unserialize($value['file']);
                    $dynamic_file = $list[$key]['file'];
                    $list[$key]['file'] = array();
                    $list[$key]['file']['file_url'] = C('config.site_url') .$dynamic_file['file_url'];
                    $list[$key]['file']['file_remark'] = $dynamic_file['file_remark'];
                    $list[$key]['file']['file_id'] = $dynamic_file['file_id'];
                    $list[$key]['file']['file_img'] = $dynamic_file['file_img'];
                    $list[$key]['file']['is_img'] = $dynamic_file['is_img'];
                }
                if($value['id']){
                    $praise_field = 'b.nickname';
                    $praise_join = 'join pigcms_user as b on a.praise_user_id=b.uid';
                    $praise_where = array('dynamic_id'=>$value['id']);  
                    $praise = D('Community_praise')->praise_select('true',$praise_field,$praise_join,$praise_where,'','');//动态的点赞
                   
                    $dynamic_praise = D('Community_praise')->where(array('dynamic_id'=>$value['id'],'praise_user_id'=>$this->_uid))->find();
                    if(!$dynamic_praise){
                        $list[$key]['can_praise'] = true;
                    }else{
                        $list[$key]['can_praise'] = false;
                    }           
                }
                $arr = D('Community_dynamic_topic_bind')->field('topic_id')->where(array('dynamic_id'=>$value['id']))->select();
                $array=array();
                foreach ($arr as $kkk => $vvv) {
                    $array[] =  $vvv['topic_id']; 
                }
                
                $map['topic_id'] = array('in',$array);
                $topic = D('Community_topic')->field('topic_id,topic_title')->where($map)->select();
                $list[$key]['note_addtime'] = time_info($list[$key]['note_addtime']);
                $list[$key]['reply'] = $reply;
                $list[$key]['praise'] = $praise;
                $list[$key]['topic_list'] = $topic;//每个帖子所关联的话题包括动态的帖子
                //是否可以为动态用户本人或群主
                if($list[$key]['id']){
                    $group_owner_uid = D('Community_info')->field('group_owner_uid')->where(array('community_id'=>$list[$key]['community_id']))->find();
                    
                    $dynamic_user_id = D('Community_dynamic')->field('user_id')->where(array('id'=>$list[$key]['id']))->find();//动态用户
                    if($this->_uid == $group_owner_uid['group_owner_uid'] || $this->_uid == $dynamic_user_id['user_id']){
                        $list[$key]['can_del'] = true;
                    }else{
                        $list[$key]['can_del'] = false;
                    }
                }
            }
            $info_list = array(
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list,
                'topic_info' => $list_info,
            );
            $this->returnCode(0,$info_list);
        }
        
    }


    /**
    * [note_detail 帖子详情]
    * @return [type] [description]
    */
    public function note_detail(){
        if($_POST['id']){
            $wh['a.id'] = $_POST['id'];
            $wh['a.is_del'] = 0;
            $wh['c.topic_id'] = $_POST['topic_id'];
            $list = D('Community_dynamic')
                    ->alias('a')
                    ->field('a.*,b.avatar,b.nickname')
                    ->join('join pigcms_user as b on a.user_id=b.uid')
                    ->join('join pigcms_community_dynamic_topic_bind as c on a.id=c.dynamic_id')
                    ->where($wh)
                    ->order('a.addtime desc')
                    ->select();
        }
        foreach($list as $key=>$value){
            $maple=array();
            
            if($value['id']){
                $maple['_string'] = '`dynamic_id` in (' . $value['id'] .')';
            }
            $maple['reply_is_del'] = 0;
            $reply = D('Community_reply')->where($maple)->select();

            foreach($reply as $kk=>$vv){
                $user = D('User')->field('nickname')->where(array('uid'=>$reply[$kk]['reply_user_id']))->find();
                $to_user = D('User')->field('nickname')->where(array('uid'=>$reply[$kk]['reply_to_user_id']))->find();
                if($this->_uid != $vv['reply_user_id']){
                    $reply[$kk]['can_reply'] = true;
                }else{
                    $reply[$kk]['can_reply'] = false;
                }
                $reply[$kk]['user'] = $user['nickname'];
                $reply[$kk]['to_user'] = $to_user['nickname'];
                
            }
            
            if($value['img']){
                $list[$key]['img'] = unserialize($value['img']);
                $img = $list[$key]['img'];
                $list[$key]['img'] = array();
                foreach($img as $k=>$v){
                    $list[$key]['img'][] = C('config.site_url') .$v;
                }
            }
            if($value['file']){
                    $list[$key]['file'] = unserialize($value['file']);
                    $dynamic_file = $list[$key]['file'];
                    $list[$key]['file'] = array();
                    $list[$key]['file']['file_url'] = C('config.site_url') .$dynamic_file['file_url'];
                    $list[$key]['file']['file_remark'] = $dynamic_file['file_remark'];
                    $list[$key]['file']['file_id'] = $dynamic_file['file_id'];
                    $list[$key]['file']['file_img'] = $dynamic_file['file_img'];
                    $list[$key]['file']['is_img'] = $dynamic_file['is_img'];
                }
            if($value['id']){
                $praise_field = 'b.nickname';
                $praise_join = 'join pigcms_user as b on a.praise_user_id=b.uid';
                $praise_where = array('dynamic_id'=>$value['id']);  
                $praise = D('Community_praise')->praise_select('true',$praise_field,$praise_join,$praise_where,'','');//动态的点赞
                $dynamic_praise = D('Community_praise')->where(array('dynamic_id'=>$value['id'],'praise_user_id'=>$this->_uid))->find();
                if(!$dynamic_praise){
                    $list[$key]['can_praise'] = true;
                }else{
                    $list[$key]['can_praise'] = false;
                }           
            }
            $arr = D('Community_dynamic_topic_bind')->field('topic_id')->where(array('dynamic_id'=>$value['id']))->select();
            $array=array();
            foreach ($arr as $kkk => $vvv) {
                $array[] =  $vvv['topic_id']; 
            }
            
            $map['topic_id'] = array('in',$array);
            $topic = D('Community_topic')->field('topic_id,topic_title')->where($map)->select();
            $list[$key]['note_addtime'] = time_info($list[$key]['note_addtime']);
            $list[$key]['reply'] = $reply;
            $list[$key]['praise'] = $praise;
            $list[$key]['topic_list'] = $topic;
            //是否可以为动态用户本人或群主
                if($list[$key]['id']){
                    $group_owner_uid = D('Community_info')->field('group_owner_uid')->where(array('community_id'=>$list[$key]['community_id']))->find();
                    
                    $dynamic_user_id = D('Community_dynamic')->field('user_id')->where(array('id'=>$list[$key]['id']))->find();//动态用户
                    if($this->_uid == $group_owner_uid['group_owner_uid'] || $this->_uid == $dynamic_user_id['user_id']){
                        $list[$key]['can_del'] = true;
                    }else{
                        $list[$key]['can_del'] = false;
                    }
                }
            $info_list = array(
                'list' => $list
            );
            $this->returnCode(0,$info_list);
        }
    }


    /**
     * [del_note 帖子删除]
     * @return [type] [description]
     */
    public function del_note(){
        $data['user_id'] = $this->_uid;
        $data['community_id'] = $_POST['community_id'];  
        $data['id'] = $_POST['id'];  

        if($data){
            //动态处理逻辑
            if($data['community_id'] && $data['id']){
                $group_owner_uid = D('Community_info')->field('group_owner_uid')->where(array('community_id'=>$data['community_id']))->find();
                $dynamic_user_id = D('Community_dynamic')->field('user_id')->where(array('id'=>$data['id']))->find();
                if(!$dynamic_user_id){
                    $this->returnCode('300001',array(),'查询用户信息失败!');
                }
                if(!$group_owner_uid){
                    $this->returnCode('300001',array(),'查询群主信息失败!');
                }

                $arr=array_merge($dynamic_user_id,$group_owner_uid);
                
                if(!in_array($data['user_id'], $arr)){
                    $this->returnCode('200001',array(),'无权删除!');
                }
                
                $result = D('Community_dynamic')->where(array('id'=>$data['id']))->data(array('is_del'=>1))->save();
            }
            
            if($result != false){
                $this->returnCode(0,'删除成功!');
            }else{
                $this->returnCode(1,array(),'删除失败!');
            }
        }else{
            $this->returnCode('100001',array(),'参数传递错误！');
        }
        
    }

    /**
     * [focus_topic 话题关注]
     * @return [type] [description]
     */
    public function focus_topic(){
        $data['topic_id'] = $_POST['topic_id'];
        $data['user_id'] = $this->_uid;
        if(!$data){
            $this->returnCode('100001',array(),'传递参数错误！');
        }
        $res = D('Community_user_topic_bind')->where(array('topic_id'=>$_POST['topic_id'],'user_id'=>$this->_uid))->find();
        if($res){
            $final = D('Community_user_topic_bind')->where(array('topic_id'=>$_POST['topic_id'],'user_id'=>$this->_uid))->delete();
            if($final){
                $this->returnCode(0,'已取消关注!');
            }
        }
        $result = D('Community_user_topic_bind')->add($data);
        if($result){
            $this->returnCode(0,'关注成功!');
        }else{
            $this->returnCode(1,array(),'关注失败!');
        }
    }


//--------------------------------------------群话题end---------------------------------------------------------------//



    // 群广场功能区块
    /**
     * 获取群的信息
     */
    public function community_single_info() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $community_info = D('Community_info');
        $community_info_single = $community_info->get_community_info($community_id, 'community_id');
        if (empty($community_info_single)) {
            $this->returnCode('70000005');
        }
        $user = M('user')->where(array('uid' => $community_info_single['group_owner_uid']))->field('nickname, avatar')->find();
        if (!empty($user)) {
            $community_info_single['owner_user'] = $user;
        } else {
            $community_info_single['owner_user'] = array();
        }
        // 获取分类标签
        $community_category_info = D('Community_bind_category')->community_category_info($community_id);
        if (!empty($community_category_info)) {
            $community_info_single['community_category_info'] = $community_category_info;
        } else {
            $community_info_single['community_category_info'] = array();
        }
        if ($community_info_single['community_avatar']) {
            $community_info_single['community_avatar'] = C('config.site_url') . '/upload/comm/' . $community_info_single['community_avatar'];
        }
        if ($community_info_single['avatar']) {
            $community_info_single['avatar'] = C('config.site_url') . $community_info_single['avatar'];
        }
        unset($community_info_single['openGId']);
        unset($community_info_single['income_money']);

        // 查询一下是否加入当前群
        $where_join = array('add_uid' => $uid, 'add_status' => array('in', array(2,3)));
        $where_join['community_id'] = $community_id;
        if (M('Community_join')->where($where_join)->count()) {
            $community_info_single['is_join'] = true;
        } else {
            $community_info_single['is_join'] = false;
        }

        $this->returnCode(0, $community_info_single);
    }

    /**
     * 删除群于分类的关联-一旦删除无法复原
     */
    public function del_community_category(){
        $bind_id = intval($_POST['bind_id']);
        // 查询单条信息-确认是否存在该信息
        $community_bind_category = D('Community_bind_category');
        $bind_info = $community_bind_category->get($bind_id);
        if (!$bind_info) {
            $this->returnCode('72000002');
        }
        if($community_bind_category->del($bind_id)){
            $this->returnCode(0, '删除成功！');
        }else{
            $this->returnCode(1, '删除失败！请重试~');
        }
    }

    /**
     * 群-获取二级分类类别
     */
    public function community_category() {
        $community_category = D('Community_category');
        $community_category_info = $community_category->category_info_list(array('subdir' => 1,'cat_status'=>1));
        if (!empty($community_category_info)) {
            $tmp = array();
            $site_url = C('config.site_url') . '/upload/system/';
            foreach ($community_category_info as $vv) {
                $subdir_info = $community_category->get_Subdirectory($vv['cid'], 2);
                if ($vv['cat_pic']) $vv['cat_pic'] = $site_url . $vv['cat_pic'];
                if ($vv['cat_select_pic']) $vv['cat_select_pic'] = $site_url . $vv['cat_select_pic'];
                if (!empty($subdir_info)) {
                    $vv['subdir_info'] = $subdir_info;
                    $tmp[] = $vv;
                }
            }
        } else {
            $tmp = array();
        }
        $this->returnCode(0, $tmp);
    }

    /**
     * 获取发现首页信息
     */
    public function discover_category_index() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_category = D('Community_category');
        $community_info = D('Community_info');
        $community_bind_category = D('Community_bind_category');
        $lat = empty($_POST['lat']) ? '' : $_POST['lat'];
        $lng = empty($_POST['lng']) ? '' : $_POST['lng'];
        $data = array();
        // 获取热门分类
        $hot_category = $community_category->hot_category_list();
        if ($hot_category) {
            $data['hot_category'] = $hot_category;
        } else {
            $data['hot_category'] = array();
        }
        if ($lat && $lng && floatval($lat) > 0 && floatval($lng) > 0) {
            $data['is_location'] = true;
        } else {
            $data['is_location'] = false;
        }
        // 获取附近的群
        if ($lat && $lng) {
            $nearby = $community_info->hasCommunity($lat, $lng, 3, $this->_uid);
            if ($nearby) {
                $site_url = C('config.site_url') ;
                foreach ($nearby as &$val) {
                    if ($lat && $lng && floatval($val['latitude']) > 0 && floatval($val['longitude']) > 0) {
                        $val['distance'] = getDistance($lat, $lng, $val['latitude'], $val['longitude']) / 1000;
                    }
                    if ($val['avatar']) $val['avatar'] = $site_url . $val['avatar'];
                    if ($val['community_avatar']) $val['community_avatar'] = $site_url . '/upload/comm/' . $val['community_avatar'];
                    if ($val['community_id']) $val['community_category_info'] = $community_bind_category->community_category_info($val['community_id']);
                }
                $data['nearby'] = $nearby;
            } else {
                $data['nearby'] = array();
            }
        } else {
            $data['nearby'] = array();
        }
        // 获取群推荐
        $recommend = $community_info->recommend_community($this->_uid);
        if ($recommend) {
            $site_url = C('config.site_url') ;
            foreach ($recommend as &$value) {
                if ($lat && $lng && floatval($value['latitude']) > 0 && floatval($value['longitude']) > 0) {
                    $value['distance'] = getDistance($lat, $lng, $value['latitude'], $value['longitude']) / 1000;
                } else {
                    $value['distance'] = '';
                }
                if ($value['avatar']) $value['avatar'] = $site_url . $value['avatar'];
                if ($value['community_avatar']) $value['community_avatar'] = $site_url . '/upload/comm/' . $value['community_avatar'];
                if ($value['community_id']) $value['community_category_info'] = $community_bind_category->community_category_info($value['community_id']);
            }
            $data['recommend'] = $recommend;
        } else {
            $data['recommend'] = array();
        }
        $this->returnCode(0, $data);
    }

    /**
     * 获取对应分类下的群信息
     */
    public function category_community_info() {
        if (!$_POST['cid']) {
            $this->returnCode('72000001');
        }
        $uid = 0;
        if ($this->_uid) {
            $uid = $this->_uid;
        }
        $lat = empty($_POST['lat']) ? '' : $_POST['lat'];
        $lng = empty($_POST['lng']) ? '' : $_POST['lng'];
        $page = empty($_POST['page']) ? 1 : intval($_POST['page']);
        $cid = $_POST['cid'];
        $community_bind_category = D('Community_bind_category');
        $where = '`cbd`.`cid` = ' . $cid;
        $community_msg = $community_bind_category->category_community_info_list($where, 8, $page, $uid);
        if (!empty($community_msg['list'])) {
            $site_url = C('config.site_url') ;
            // 计算一下群与当前用户的距离
            foreach ($community_msg['list'] as &$val) {
                if ($lat && $lng && floatval($val['latitude']) > 0 && floatval($val['longitude']) > 0) {
                    $val['distance'] = getDistance($lat, $lng, $val['latitude'], $val['longitude']) / 1000;
                } else {
                    $val['distance'] = '';
                }
                if ($val['avatar']) $val['avatar'] = $site_url . $val['avatar'];
                if ($val['community_avatar']) $val['community_avatar'] = $site_url . '/upload/comm/' . $val['community_avatar'];
                $val['community_category_info'] = $community_bind_category->community_category_info($val['community_id']);
            }
        }
        $this->returnCode(0, $community_msg);
    }

    /**
     * 修改群信息
     */
    public  function community_edit() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $uid = $this->_uid;
        $database_info = M('Community_info');
        // 查询一下该群是否正常存在
        $community_info_single = $database_info->where(array('community_id'=> $community_id))->find();
        if (!$community_info_single) {
            $this->returnCode('70000005');
        }
        if ($community_info_single['status'] == 2) {
            $this->returnCode('70000007');
        }
        if ($community_info_single['status'] == 3) {
            $this->returnCode('70000008');
        }
        // 群地址
        $data['address'] = $_POST['address'] ? $_POST['address'] : '';
        $data['longitude'] = $_POST['longitude'] ? $_POST['longitude'] : '';
        $data['latitude'] = $_POST['latitude'] ? $_POST['latitude'] : '';
        $data['province_name'] = $_POST['province_name'] ? $_POST['province_name'] : '';
        $data['city_name'] = $_POST['city_name'] ? $_POST['city_name'] : '';
        $data['area_name'] = $_POST['area_name'] ? $_POST['area_name'] : '';
        // 群介绍
        $data['community_des'] = $_POST['community_des'] ? $_POST['community_des'] : '';
        if ($data['community_des']) {
            $check = D('Community_file')->msgSecCheck($data['community_des']);
            if ($check['errcode'] != 0) {
                $this->returnCode(1,array(),'群介绍内容中含有违法违规内容');
            }
        }
        // 群头像
        if(!empty($_FILES) && $_FILES['file']['error'] != 4){
            $image = D('Image')->handle($uid, 'comm', 1, array('size' => 10), false, true);
            if (!$image['error']) {
                $_POST = array_merge($_POST, str_replace('/upload/comm/', '', $image['url']));
            } else {
                $this->returnCode(1,array(), $image['message']);
            }
        }
        // 群头像
        if ($_POST['image']) {
            $data['community_avatar'] = $_POST['image'];
        }
        $data['set_time'] = time();
        $set = $database_info->where(array('community_id'=> $community_id))->data($data)->save();
        if ($set) {
            // 判断是否传值分类id  cid_arr [1,2,3]
            $cid_arr = json_decode(htmlspecialchars_decode($_POST['cid_arr']), true);
            if (!empty($cid_arr) && is_array($cid_arr)) {
                $community_bind_category = D('Community_bind_category');
                $m_community_bind_category = M('Community_bind_category');
                $cout = $community_bind_category->community_category_num($community_id);
                $cid_count = count($cid_arr);
                if ($cout && (intval($cout) + intval($cid_count)) > 3) {
                    $this->returnCode(1,array(), '分类标签数不得大于3个！请重试');
                }
                $cid_str = implode(',', $cid_arr);
                $community_category_count = M('community_category')->where(array('cid'=>array('in', $cid_str), 'cat_status' => 1))->count();
                if ($community_category_count != $cid_count) {
                    $this->returnCode(1,array(), '所选标签存在被禁用状态！请重试');
                }
                // 去除已经添加上的标签
                $add_info = array(
                    'community_id' => $community_id,
                    'add_time' => time()
                );
                $where =array(
                    'community_id' => $community_id
                );
                foreach ($cid_arr as $val) {
                    $add_info['cid'] = $val;
                    $where['cid'] = $val;
                    if (!$m_community_bind_category->field('bind_id')->where($where)->find()) {
                        $m_community_bind_category->data($add_info)->add();
                    }
                }
            }
            $this->returnCode(0,array('community_id'=>$community_id));
        } else {
            $this->returnCode(1,array(),'编辑失败！请重试');
        }
    }

    /**
     * 按照群名称搜索群
     */
    public function search_community() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $lat = empty($_POST['lat']) ? '' : $_POST['lat'];
        $lng = empty($_POST['lng']) ? '' : $_POST['lng'];
        if (!empty($_POST['community_name'])) {
            $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;

//            $community_join_info = M('Community_join')->field('community_id')->where(array('add_uid' => $uid, 'add_status' => array('in', array(2,3))))->select();
//            $community_join_str = '';
//            // 查询一下关联的分类信息
//            $community_join_arr = array();
//            if ($community_join_info) {
//                foreach ($community_join_info as $val) {
//                    $community_join_arr[] = $val['community_id'];
//                }
////              只查询自己未加入的群
//              $community_join_str = implode(',', $community_join_arr);
//            }
            $where = '`ci`.`status` = 1 AND `ci`.`group_mode` = 2 AND `ci`.`community_name` like "%' . $_POST['community_name'] . '%"';

//            if ($community_join_str) {
////              只查询自己未加入的群
//                $where .= ' AND `ci`.`community_id` NOT IN (' . $community_join_str . ')';
//            }
            $info = D('Community_info')->community_msg_list($where, 8, $page);
            if (!empty($info['list'])) {
                $d_community_bind_category = D('Community_bind_category');
                $site_url = C('config.site_url');
                foreach ($info['list'] as &$val) {
                    $val['category_info'] = $d_community_bind_category->community_category_info($val['community_id']);
                    if ($val['avatar']) $val['avatar'] = $site_url . $val['avatar'];
                    if ($val['community_avatar']) $val['community_avatar'] = $site_url . '/upload/comm/' . $val['community_avatar'];
                    if ($lat && $lng && floatval($val['latitude']) > 0 && floatval($val['longitude']) > 0) {
                        $val['distance'] = getDistance($lat, $lng, $val['latitude'], $val['longitude']) / 1000;
                    } else {
                        $val['distance'] = '';
                    }
                }
            }
            $this->returnCode(0,$info);
        } else {
            $this->returnCode(0,array());
        }
    }


    // 群文件功能区块
    /**
     * 创建群文件夹
     */
    public function add_folder(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $folder_name = $_POST['folder_name'];
        if (empty($folder_name)) {
            $this->returnCode('72000003');
        }
        if ($folder_name) {
            $check = D('Community_file')->msgSecCheck($folder_name);
            if ($check['errcode'] != 0) {
                $this->returnCode(1,array(),'群文件夹名称中含有违法违规内容');
            }
        }
        if (M('Community_folder')->where(array('folder_name' =>$folder_name, 'folder_status' => array('neq', 3), 'community_id' => $community_id))->find()) {
            $this->returnCode('72000024');
        }
        $uid = $this->_uid;
        // 获取群部分信息和群关于群文件夹权限信息
        $d_community_info = D('Community_application_development');
        $community_development = $d_community_info->community_application_development($community_id);
        if (empty($community_development) || $community_development['status'] != 1) {
            $this->returnCode('72000016');
        }
        if (!empty($community_development['is_add_folder']) && $community_development['is_add_folder'] == 2 && $uid != $community_development['group_owner_uid']) {
            $this->returnCode('72000018');
        }
        $data = array();
        $data['community_id'] = $community_id;
        $data['folder_uid'] = $uid;
        $data['folder_name'] = $folder_name;
        if (!empty($_POST['other_is_upload']) && intval($_POST['other_is_upload']) == 1) {
            $data['other_is_upload'] = 1;
        } else {
            $data['other_is_upload'] = 2;
        }
        $data['folder_status'] = 1;
        $data['add_time'] = time();
        $folder_id = M('Community_folder')->data($data)->add();
        if ($folder_id) {
            $this->returnCode(0,array('folder_id'=>$folder_id));
        } else {
            $this->returnCode(1,array(),'添加失败！请重试');
        }
    }

    /**
     * 编辑群文件夹
     */
    public function edit_folder(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $folder_id = intval($_POST['folder_id']);
        if (empty($folder_id)) {
            $this->returnCode('72000004');
        }
        $m_community_folder = M('Community_folder');
        $folder_single = $m_community_folder->where(array('folder_id' => $folder_id))->find();
        if (!$folder_single || intval($folder_single['folder_status']) >= 2) {
            $this->returnCode('72000005');
        }
        if ($uid != $folder_single['folder_uid']) {
            $this->returnCode('72000006');
        }
        $data = array();
        if (!empty($_POST['folder_name']) && $_POST['folder_name'] != $folder_single['folder_name']) {
            $folder_name = $_POST['folder_name'];
            if (M('Community_folder')->where(array('folder_name' =>$folder_name, 'folder_status' => array('neq', 3), 'community_id' => $folder_single['community_id']))->find()) {
                $this->returnCode('72000024');
            }
            $data['folder_name'] = $folder_name;
            if ($folder_name) {
                $check = D('Community_file')->msgSecCheck($folder_name);
                if ($check['errcode'] != 0) {
                    $this->returnCode(1,array(),'群文件夹名称中含有违法违规内容');
                }
            }
        }
        if (!empty($_POST['other_is_upload']) && intval($_POST['other_is_upload']) != intval($folder_single['other_is_upload'])) {
            if (in_array(intval($_POST['other_is_upload']), array(1, 2))) {
                $data['other_is_upload'] = $_POST['other_is_upload'];
            }
        }
        if (!empty($data)) {
            $folder_id = $m_community_folder->where(array('folder_id' => $folder_id))->data($data)->save();
            if ($folder_id) {
                foreach ($data as $k => $v) {
                    if (array_key_exists($k, $folder_single)) {
                        $folder_single[$k] = $v;
                    }
                }
                $community_info = M('Community_info')->field('group_owner_uid')->where(array('community_id' => $folder_single['community_id']))->find();
                $user = M('User')->where(array('uid' => $folder_single['folder_uid']))->field('nickname')->find();
                $folder_single['time_info'] = time_info($folder_single['add_time']);
                $folder_single['group_owner_uid'] = $community_info['group_owner_uid'];
                $folder_single['nickname'] = $user['nickname'];
                if ($folder_single['folder_uid'] == $uid) {
                    $folder_single['is_del'] = true;
                    $folder_single['is_edit'] = true;
                } elseif($community_info && $community_info['group_owner_uid'] == $uid) {
                    $folder_single['is_del'] = true;
                    $folder_single['is_edit'] = false;
                } else {
                    $folder_single['is_del'] = false;
                    $folder_single['is_edit'] = false;
                }
                $this->returnCode(0, $folder_single);
            } else {
                $this->returnCode(1,array(),'编辑失败！请重试');
            }
        } else {
            $this->returnCode(0,array('folder_id'=>$folder_id));
        }
    }

    /**
     * 删除文件夹 群主和创建者均可删除，后台也可以进行禁用操作
     */
    public function del_folder(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $folder_id = intval($_POST['folder_id']);
        if (empty($folder_id)) {
            $this->returnCode('72000004');
        }
        $m_community_folder = M('Community_folder');
        $folder_single = $m_community_folder->where(array('folder_id' => $folder_id))->find();
        if (!$folder_single || intval($folder_single['folder_status']) >= 2) {
            $this->returnCode('72000005');
        }
        $community_info = M('Community_info')->where(array('community_id' => $folder_single['community_id'], 'group_owner_uid' => $uid))->field('community_name')->find();
        // 如果既不是创建者，也不是群主，无删除此文件夹权限
        if ($uid != $folder_single['folder_uid'] && !$community_info) {
            $this->returnCode('72000007');
        }
        $data = array('folder_status' => 3);
        $folder_del = $m_community_folder->where(array('folder_id' => $folder_id))->data($data)->save();
        if ($folder_del) {
            $this->returnCode(0,array('folder_id'=>$folder_id));
        } else {
            $this->returnCode(1,array(),'删除失败！请重试');
        }
    }

    /**
     * 获取群文件夹列表
     */
    public function folder_list(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        // 获取群部分信息和群关于群文件夹权限信息
        $d_community_info = D('Community_application_development');
        $community_development = $d_community_info->community_application_development($community_id);
        if (empty($community_development) || $community_development['status'] != 1) {
            $this->returnCode('72000016');
        }
        $is_add_folder = true;
        if (!empty($community_development['is_add_folder']) && $community_development['is_add_folder'] == 2 && $uid != $community_development['group_owner_uid']) {
            $is_add_folder = false;
        }
        $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;
        $time_order = '';
        // 时间顺序查询
        if (!empty($_POST['time_order']) && in_array(strtolower($_POST['time_order']), array('desc', 'asc'))) {
            $time_order = strtolower($_POST['time_order']);
        }
        $order = '`cf`.`add_time` DESC';
        if ($time_order) {
            $order = '`cf`.`add_time` ' . $time_order;
        }
        // 群文件名称 模糊查询
        if (!empty($_POST['folder_name'])) {
            $where = '`cf`.`folder_status` = 1  AND `cf`.`community_id` = ' . $community_id .' AND `cf`.`folder_name` like "%' . $_POST['folder_name'] . '%"';
        } else {
            $where = '`cf`.`folder_status` = 1 AND `cf`.`community_id` = ' . $community_id;
        }
        $d_community_folder = D('Community_folder')->folder_info_list($where, 10, $page, $order);
        // 群主和群文件创建者可以删除， 创建者还可以编辑
        if (!empty($d_community_folder['list'])) {
            foreach ($d_community_folder['list'] as &$val) {
                $val['time_info'] = time_info($val['add_time']);
                if ($val['folder_uid'] == $uid) {
                    $val['is_del'] = true;
                    $val['is_edit'] = true;
                } elseif($val['group_owner_uid'] == $uid) {
                    $val['is_del'] = true;
                    $val['is_edit'] = false;
                } else {
                    $val['is_del'] = false;
                    $val['is_edit'] = false;
                }
            }
        }
        $d_community_folder['is_add_folder'] = $is_add_folder;
        $this->returnCode(0,$d_community_folder);
    }

    /**
     * 上传文件 现在 规定上传的群文件必须归属于群和对应的群文件下
     */
    public function add_file(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $folder_id = intval($_POST['folder_id']);
        if (empty($folder_id)) {
            $this->returnCode('72000004');
        }
        $uid = $this->_uid;
        $m_community_folder = M('Community_folder');
        $folder_single = $m_community_folder->where(array('folder_id' => $folder_id))->find();
        if (!$folder_single || intval($folder_single['folder_status']) >= 2) {
            $this->returnCode('72000005');
        }
        if ($uid != $folder_single['folder_uid'] && $folder_single['other_is_upload'] == 1) {
            $this->returnCode('72000008');
        }
        $d_community_file = D('Community_file');
        $msg = $d_community_file->community_file_handle($uid, 'comm_file', array('size' => 50), $community_id, $folder_id);
        if ($msg['error'] == 0) {
            if ($msg['file_id_arr']) {
                $file_num = count($msg['file_id_arr']);
                $m_community_folder->where(array('folder_id' => $folder_id))->setInc('file_num', $file_num);
            }
            $this->returnCode(0,array('file_id_arr'=>$msg['file_id_arr']));
        } else {
            $this->returnCode(1,array(),$msg['message']);
        }
    }


    /**
     * 单上传文件 现在 规定上传的群文件必须归属于群和对应的群文件下
     */
    public function single_add_file(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $folder_id = intval($_POST['folder_id']);
        if (empty($folder_id)) {
            $this->returnCode('72000004');
        }
        $uid = $this->_uid;
        $m_community_folder = M('Community_folder');
        $folder_single = $m_community_folder->where(array('folder_id' => $folder_id))->find();
        if (!$folder_single || intval($folder_single['folder_status']) >= 2) {
            $this->returnCode('72000005');
        }
        if ($uid != $folder_single['folder_uid'] && $folder_single['other_is_upload'] == 1) {
            $this->returnCode('72000008');
        }
        $d_community_file = D('Community_file');
        $msg = $d_community_file->community_file_handle($uid, 'comm_file', array('size' => 50), $community_id, $folder_id);
        if ($msg['error'] == 0) {
            if ($msg['file_id_arr']) {
                $file_num = count($msg['file_id_arr']);
                $m_community_folder->where(array('folder_id' => $folder_id))->setInc('file_num', $file_num);
            }
            // 发送一条消息到群聊中
            D('Community_file')->file_info_send($folder_single['folder_name'], $msg['file_id'], $community_id, $uid);
            $this->returnCode(0,array('file_id'=>$msg['file_id']));
        } else {
            $this->returnCode(1,array(),$msg['message']);
        }
    }


    /**
     * 获取对应群中群文件列表
     */
    public function file_all_list(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $uid = $this->_uid;
        $d_community_file = D('Community_file');
        $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;
        $time_order = '';
        // 时间顺序查询
        if (!empty($_POST['time_order']) && in_array(strtolower($_POST['time_order']), array('desc', 'asc'))) {
            $time_order = strtolower($_POST['time_order']);
        }
        $order = '`cfl`.`add_time` DESC';
        if ($time_order) {
            $order = '`cfl`.`add_time` ' . $time_order;
        }
        // 群文件名称 模糊查询
        if (!empty($_POST['file_name'])) {
            $where = '`cfl`.`file_status` = 1 AND `cfl`.`file_remark` like "%' . $_POST['file_name'] . '%" AND `ci`.`community_id`='. $community_id;
        } else {
            $where = '`cfl`.`file_status` = 1 AND `ci`.`community_id`='. $community_id;
        }
        $community_file= $d_community_file->file_info_list($where, 10, $page, $order);
        // 群主可以删除文件， 上传者可以删除文件， 文件夹创建者可以删除文件
        if (!empty($community_file['list'])) {
            $site_url = C('config.site_url') ;
            foreach ($community_file['list'] as &$val) {
                $val['time_info'] = time_info($val['add_time']);
                if ($val['file_url']) $val['file_url'] = $site_url . $val['file_url'];
                if ($val['file_extra']) {
                    $file_extra = unserialize($val['file_extra']);
                    foreach ($file_extra as $k => $v) {
                        $val[$k] = $v;
                    }
                    unset($val['file_extra']);
                }
                if ($val['folder_uid'] == $uid) {
                    $val['is_del'] = true;
                } elseif($val['group_owner_uid'] && $val['group_owner_uid'] == $uid) {
                    $val['is_del'] = true;
                } elseif($val['file_uid'] == $uid) {
                    $val['is_del'] = true;
                } else {
                    $val['is_del'] = false;
                }
                $val['file_img'] = $d_community_file->type_and_img($val['file_type'], $val['file_suffix']);
            }
        }
        $this->returnCode(0,$community_file);
    }

    /**
     * 获取对应群文件夹中群文件列表
     */
    public function file_list(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $folder_id = intval($_POST['folder_id']);
        if (empty($folder_id)) {
            $this->returnCode('72000004');
        }
        $uid = $this->_uid;
        $d_community_folder = D('Community_folder');
        $d_community_file = D('Community_file');
        $folder_single = $d_community_folder->folder_community_info($folder_id);
        if (!$folder_single || intval($folder_single['folder_status']) >= 2) {
            $this->returnCode('72000005');
        }
        $other_is_upload = true;
        if ($uid != $folder_single['folder_uid'] && $folder_single['other_is_upload'] == 1) {
            $other_is_upload = false;
        }
        $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;
        $time_order = '';
        // 时间顺序查询
        if (!empty($_POST['time_order']) && in_array(strtolower($_POST['time_order']), array('desc', 'asc'))) {
            $time_order = strtolower($_POST['time_order']);
        }
        $order = '`cfl`.`add_time` DESC';
        if ($time_order) {
            $order = '`cfl`.`add_time` ' . $time_order;
        }
        // 群文件名称 模糊查询
        if (!empty($_POST['file_name'])) {
            $where = '`cfl`.`file_status` = 1 AND `cfl`.`file_remark` like "%' . $_POST['file_name'] . '%" AND `cfl`.`folder_id` = ' . $folder_id;
        } else {
            $where = '`cfl`.`file_status` = 1 AND `cfl`.`folder_id` = ' . $folder_id;
        }
        $community_file= $d_community_file->file_info_list($where, 10, $page, $order);
        // 群主可以删除文件， 上传者可以删除文件， 文件夹创建者可以删除文件
        if (!empty($community_file['list'])) {
            $site_url = C('config.site_url') ;
            foreach ($community_file['list'] as &$val) {
                $val['time_info'] = time_info($val['add_time']);
                if ($val['file_url']) $val['file_url'] = $site_url . $val['file_url'];
                if ($val['file_extra']) {
                    $file_extra = unserialize($val['file_extra']);
                    foreach ($file_extra as $k => $v) {
                        $val[$k] = $v;
                    }
                    unset($val['file_extra']);
                }
                if ($val['folder_uid'] == $uid) {
                    $val['is_del'] = true;
                } elseif($folder_single['community_info'] && $folder_single['community_info']['group_owner_uid'] == $uid) {
                    $val['is_del'] = true;
                } elseif($val['file_uid'] == $uid) {
                    $val['is_del'] = true;
                } else {
                    $val['is_del'] = false;
                }
                $val['file_img'] = $d_community_file->type_and_img($val['file_type'], $val['file_suffix']);
            }
        }
        $community_file['other_is_upload'] = $other_is_upload;
        $this->returnCode(0,$community_file);
    }

    /**
     * 删除文件 群主可以删除文件， 上传者可以删除文件， 文件夹创建者可以删除文件 后台也可以禁用
     */
    public function del_file(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $file_id = intval($_POST['file_id']);
        if (empty($file_id)) {
            $this->returnCode('72000019');
        }
        $m_community_file = M('Community_file');
        $file_single = $m_community_file->where(array('file_id' => $file_id))->find();
        if (!$file_single || intval($file_single['file_status']) >= 2) {
            $this->returnCode('72000009');
        }
        $d_community_folder = D('Community_folder');
        $folder_single = $d_community_folder->folder_community_info($file_single['folder_id']);
        // 如果既不是创建者，也不是群主，无删除此文件夹权限
        if ($uid != $folder_single['folder_uid'] && $folder_single['community_info'] && $folder_single['community_info']['group_owner_uid'] != $uid && $file_single['file_uid'] != $uid) {
            $this->returnCode('72000010');
        }
        $data = array('file_status' => 3);
        $file_del = $m_community_file->where(array('file_id' => $file_id))->data($data)->save();
        if ($file_del) {
            $this->returnCode(0,array('file_id'=>$file_id));
        } else {
            $this->returnCode(1,array(),'删除失败！请重试');
        }
    }

    /**
     * 获取文件详情
     */
    public function file_detail(){
        $file_id = intval($_POST['file_id']);
        if (empty($file_id)) {
            $this->returnCode('72000058');
        }
        $d_community_file = D('Community_file');
        $file_single = $d_community_file->file_detail($file_id);
        if (!$file_single || intval($file_single['file_status']) >= 2) {
            $this->returnCode('72000009');
        }
        $this->returnCode(0,$file_single);
    }



    // 群相册区块

    /**
     * 创建群相册
     */
    public function add_album(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $album_name = $_POST['album_name'];
        if (empty($album_name)) {
            $this->returnCode('72000011');
        }
        if ($album_name) {
            $check = D('Community_file')->msgSecCheck($album_name);
            if ($check['errcode'] != 0) {
                $this->returnCode(1,array(),'群相册名称中含有违法违规内容');
            }
        }
        if (M('Community_album')->where(array('album_name' =>$album_name, 'album_status' => array('neq', 3), 'community_id' => $community_id))->find()) {
            $this->returnCode('72000025');
        }
        $uid = $this->_uid;
        // 获取群部分信息和群关于群相册权限信息
        $d_community_info = D('Community_application_development');
        $community_development = $d_community_info->community_application_development($community_id);
        if (empty($community_development) || $community_development['status'] != 1) {
            $this->returnCode('72000016');
        }
        if (!empty($community_development['is_add_album']) && $community_development['is_add_album'] == 2 && $uid != $community_development['group_owner_uid']) {
            $this->returnCode('72000056');
        }
        $data = array();
        $data['community_id'] = $community_id;
        $data['album_uid'] = $uid;
        $data['album_name'] = $album_name;
        if (!empty($_POST['album_des'])) {
            $data['album_des'] = $_POST['album_des'];
            if ($data['album_des']) {
                $check = D('Community_file')->msgSecCheck($data['album_des']);
                if ($check['errcode'] != 0) {
                    $this->returnCode(1,array(),'群相册描述中含有违法违规内容');
                }
            }
        }
        if (!empty($_POST['other_is_upload']) && intval($_POST['other_is_upload']) == 1) {
            $data['other_is_upload'] = 1;
        } else {
            $data['other_is_upload'] = 2;
        }
        $data['album_status'] = 1;
        $data['add_time'] = time();
        $album_id = M('Community_album')->data($data)->add();
        if ($album_id) {
            $this->returnCode(0,array('album_id'=>$album_id));
        } else {
            $this->returnCode(1,array(),'添加失败！请重试');
        }
    }

    /**
     * 编辑群相册
     */
    public function edit_album(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $m_community_album = M('Community_album');
        $album_single = $m_community_album->where(array('album_id' => $album_id))->find();
        if (!$album_single || intval($album_single['album_status']) >= 2) {
            $this->returnCode('72000013');
        }
        if ($uid != $album_single['album_uid']) {
            $this->returnCode('72000014');
        }
        $data = array();
        if (!empty($_POST['album_name']) && $_POST['album_name'] != $album_single['album_name']) {
            $album_name = $_POST['album_name'];
            if (M('Community_album')->where(array('album_name' =>$album_name, 'album_status' => array('neq', 3), 'community_id' => $album_single['community_id']))->find()) {
                $this->returnCode('72000025');
            }
            $data['album_name'] = $album_name;
            if ($album_name) {
                $check = D('Community_file')->msgSecCheck($album_name);
                if ($check['errcode'] != 0) {
                    $this->returnCode(1,array(),'群相册名称中含有违法违规内容');
                }
            }
        }
        if (!empty($_POST['album_des']) && $_POST['album_des'] != $album_single['album_des']) {
            $data['album_des'] = $_POST['album_des'];
            if ($data['album_des']) {
                $check = D('Community_file')->msgSecCheck($data['album_des']);
                if ($check['errcode'] != 0) {
                    $this->returnCode(1,array(),'群相册描述中含有违法违规内容');
                }
            }
        }
        if (!empty($_POST['other_is_upload']) && intval($_POST['other_is_upload']) != intval($album_single['other_is_upload'])) {
            if (in_array(intval($_POST['other_is_upload']), array(1, 2))) {
                $data['other_is_upload'] = $_POST['other_is_upload'];
            }
        }
        if (!empty($data)) {
            $album_id = $m_community_album->where(array('album_id' => $album_id))->data($data)->save();
            if ($album_id) {
                $this->returnCode(0,array('album_id'=>$album_id));
            } else {
                $this->returnCode(1,array(),'编辑失败！请重试');
            }
        } else {
            $this->returnCode(0,array('album_id'=>$album_id));
        }
    }

    /**
     * 删除相册 群主和创建者均可删除，后台也可以进行禁用操作
     */
    public function del_album(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $m_community_album = M('Community_album');
        $album_single = $m_community_album->where(array('album_id' => $album_id))->find();
        if (!$album_single || intval($album_single['album_status']) >= 2) {
            $this->returnCode('72000013');
        }
        $community_info = M('Community_info')->where(array('community_id' => $album_single['community_id'], 'group_owner_uid' => $uid))->field('group_owner_uid, community_name')->find();
        // 如果既不是创建者，也不是群主，无删除此相册权限
        if ($uid != $album_single['album_uid'] && !$community_info) {
            $this->returnCode('72000015');
        }
        $data = array('album_status' => 3);
        $folder_del = $m_community_album->where(array('album_id' => $album_id))->data($data)->save();
        if ($folder_del) {
            $this->returnCode(0,array('album_status'=>$album_id));
        } else {
            $this->returnCode(1,array(),'删除失败！请重试');
        }
    }

    /**
     * 获取群相册列表 群主和群相册创建者可以删除， 创建者还可以编辑
     */
    public function album_list(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;
        $time_order = '';
        // 时间顺序查询
        if (!empty($_POST['time_order']) && in_array(strtolower($_POST['time_order']), array('desc', 'asc'))) {
            $time_order = strtolower($_POST['time_order']);
        }
        $order = '`ca`.`add_time` DESC';
        if ($time_order) {
            $order = '`ca`.`add_time` ' . $time_order;
        }
        // 群相册名称 模糊查询
        if (!empty($_POST['album_name'])) {
            $where = '`ca`.`album_status` = 1  AND `ca`.`community_id` = ' . $community_id .' AND `ca`.`album_name` like "%' . $_POST['album_name'] . '%"';
        } else {
            $where = '`ca`.`album_status` = 1 AND `ca`.`community_id` = ' . $community_id;
        }
        $community_album = D('Community_album')->album_info_list($where, 10, $page, $order);
        // 群主和群文件创建者可以删除， 创建者还可以编辑
        $where_num = array(
            'file_sign' => array('neq', ''),
            'file_status' => 1,
        );
        $d_community_file = D('Community_file');
        if (!empty($community_album['list'])) {
            foreach ($community_album['list'] as &$val) {
//                $where_num['album_id'] = $val['album_id'];
//                $val['pic_num'] = $d_community_file->change_num($where_num);
                $val['album_img'] = $d_community_file->album_img($val['album_id']);
            }
        }
        // 获取群部分信息和群关于群相册权限信息
        $d_community_info = D('Community_application_development');
        $community_development = $d_community_info->community_application_development($community_id);
        if (empty($community_development) || $community_development['status'] != 1) {
            $this->returnCode('72000016');
        }
        $community_album['is_add_album'] = true;
        if (!empty($community_development['is_add_album']) && $community_development['is_add_album'] == 2 && $uid != $community_development['group_owner_uid']) {
            $community_album['is_add_album'] = false;
        }
        $this->returnCode(0,$community_album);
    }

    /**
     * 单个上传图片文件，返回图片文件id
     */
    function add_single_img() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $uid = $this->_uid;
        $m_community_album = M('Community_album');
        $album_single = $m_community_album->where(array('album_id' => $album_id))->find();
        if (!$album_single || intval($album_single['album_status']) >= 2) {
            $this->returnCode('72000013');
        }
        if ($uid != $album_single['album_uid'] && $album_single['other_is_upload'] == 1) {
            $this->returnCode('72000057');
        }
        $d_community_file = D('Community_file');
        $msg = $d_community_file->community_file_handle($uid, 'comm_file', array('size' => 5), $community_id, 0, $album_id, 2, '');
        if ($msg['error'] == 0) {
            $this->returnCode(0,$msg);
        } else {
            $this->returnCode(1,array(),$msg['message']);
        }
    }

    /**
     * 上传图片 现在 规定上传的相册图片必须归属于群和对应的群相册下
     */
    public function add_album_img(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $file_id_arr = $_POST['file_id_arr'];
        if (empty($file_id_arr)) {
            $this->returnCode('72000020');
        }
        $d_community_file = M('Community_file');
        // 查询一下图片id信息
        $where = array('file_id' => array('in', $file_id_arr), 'community_id' => $community_id, 'album_id' => $album_id);
        $file = $d_community_file->where($where)->select();

        $file_url_arr = array();
        if (count($file_id_arr) != count($file)) {
            $this->returnCode('72000027');
        }
        foreach ($file as $val) {
            if (!$val || intval($val['album_id']) == 0 || intval($val['file_status']) >= 2) {
                $this->returnCode('72000026');
            }
            if ($val['file_url']) $file_url_arr[] = $val['file_url'];
        }
        $is_add_dynamic = 2;
        if ($_POST['is_add_dynamic'] && intval($_POST['is_add_dynamic']) == 1) {
            $is_add_dynamic = 1;
        }
        $file_des = '';
        if ($_POST['file_des']) {
            $file_des = $_POST['file_des'];
        }
        $uid = $this->_uid;
        $m_community_album = M('Community_album');
        $album_single = $m_community_album->where(array('album_id' => $album_id))->find();
        if (!$album_single || intval($album_single['album_status']) >= 2) {
            $this->returnCode('72000013');
        }
        if ($uid != $album_single['album_uid'] && $album_single['other_is_upload'] == 1) {
            $this->returnCode('72000057');
        }
        $file_sign = time();
        $data = array(
            'is_add_dynamic' => $is_add_dynamic,
            'file_des' =>$file_des,
            'file_sign' => $file_sign,
            'file_status' => 1,
            'add_time' => time(),
            'set_time' => time(),
        );
        $msg = $d_community_file->where($where)->data($data)->save();
        if ($msg) {
            if (count($file_id_arr) > 0) {
                $file_num = count($file_id_arr);
                $m_community_album->where(array('album_id' => $album_id))->setInc('pic_num', $file_num);
            }
            // 判断是否同时发布到群动态
            // 为 1 不同步到动态  2同步到动态
            $dynamic_id = 0;
            if ($is_add_dynamic == 2) {
                $data['community_id'] = $community_id;//群ID
                $data['user_id'] = $uid;//用户ID
                $data['content'] = $file_des;//图片的介绍->动态内容
                $data['addtime'] = time();
                if ($file_url_arr) {
                    $data['img'] = serialize($file_url_arr);
                }
                $dynamic_id = M('Community_dynamic')->add($data);
                if(!$dynamic_id) {
                    $this->returnCode('72000066');
                }
            }
            $file_uid = reset($file)['file_uid'];
            $bind_data = array(
                'dynamic_id' => $dynamic_id,
                'file_sign' => $file_sign,
                'file_uid' => $file_uid,
            );
            M('Community_user_file_bind')->data($bind_data)->add();
            $des = '在【'.$album_single['album_name'] .'】上传了'.count($file_url_arr).'张照片';
            D('Community_file')->create_plan($dynamic_id, $des);
            $this->returnCode(0,array('file_id_arr'=>$file_id_arr ,'dynamic_id'=>$dynamic_id));
        } else {
            $this->returnCode('72000067');
        }
    }

    /**
     * 获取对应群相册中用户分批次上传的群图片列表
     */
    public function album_img_list(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $uid = $this->_uid;
        $d_community_album = D('Community_album');
        $album_single = $d_community_album->album_community_info($album_id);
        if (!$album_single || intval($album_single['album_status']) >= 2) {
            $this->returnCode('72000013');
        }
        $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;
        $where = '`cfl`.`file_sign` IS NOT NULL AND `cfl`.`file_status` = 1 AND `cfl`.`album_id` = ' . $album_id;

        $d_community_file = D('Community_file');
        $community_file = D('Community_file')->file_info_user($where, 5, $page);
        if (!empty($community_file['list'])) {
            foreach ($community_file['list'] as &$val) {
                $val['img_info'] = $d_community_file->file_by_user($val['file_sign'], $album_id, $val['file_uid']);
                $val['img_num'] = count($val['img_info']);
            }
        }
        // 群主可以删除文件， 上传者可以删除文件， 文件夹创建者可以删除文件
        if (!empty($community_file['list'])) {
            foreach ($community_file['list'] as &$val) {
                if ($val['album_uid'] == $uid) {
                    $val['is_del'] = true;
                } elseif($album_single['community_info'] && $album_single['community_info']['group_owner_uid'] == $uid) {
                    $val['is_del'] = true;
                } elseif($val['file_uid'] == $uid) {
                    $val['is_del'] = true;
                } else {
                    $val['is_del'] = false;
                }
            }
        }
        // 处理群相册是否可以删除和编辑
        $album_info = $album_single;

        if ($album_info['album_uid'] == $uid) {
            $album_info['is_del'] = true;
            $album_info['is_edit'] = true;
        } elseif($album_info['community_info']['group_owner_uid'] == $uid) {
            $album_info['is_del'] = true;
            $album_info['is_edit'] = false;
        } else {
            $album_info['is_del'] = false;
            $album_info['is_edit'] = false;
        }
        // 重新计算一下数目
        $where_num = array(
            'file_sign' => array('neq', ''),
            'file_status' => 1,
            'album_id' => $album_id
        );
        $album_info['pic_num'] = $d_community_file->change_num($where_num);
        unset($album_info['community_info']);
        $community_file['album_info'] = $album_info;

        // 获取关于上传群图片信息权限
        $other_is_upload = true;
        if ($uid != $album_single['album_uid'] && $album_single['other_is_upload'] == 1) {
            $other_is_upload = false;
        }
        $community_file['other_is_upload'] = $other_is_upload;


        $this->returnCode(0,$community_file);
    }


    /**
     * 获取对应群相册中所有群图片列表
     */
    public function album_aLL_img_list(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $uid = $this->_uid;
        $d_community_album = D('Community_album');
        $album_single = $d_community_album->album_community_info($album_id);
        if (!$album_single || intval($album_single['album_status']) >= 2) {
            $this->returnCode('72000013');
        }
        $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;

        $where = '`cfl`.`file_status` = 1 AND `cfl`.`album_id` = ' . $album_id;

        $d_community_file = D('Community_file');
        $community_file = $d_community_file->img_info_list($where, 15, $page);
        // 群主可以删除文件， 上传者可以删除文件， 文件夹创建者可以删除文件
        if (!empty($community_file['list'])) {
            $site_url = C('config.site_url') ;
            foreach ($community_file['list'] as &$val) {
                $val['time_info'] = time_info($val['add_time']);
                if ($val['file_url']) $val['file_url'] = $site_url . $val['file_url'];
                if ($val['file_extra']) {
                    $file_extra = unserialize($val['file_extra']);
                    foreach ($file_extra as $k => $v) {
                        $val[$k] = $v;
                    }
                    unset($val['file_extra']);
                }
                if ($val['album_uid'] == $uid) {
                    $val['is_del'] = true;
                } elseif($album_single['community_info'] && $album_single['community_info']['group_owner_uid'] == $uid) {
                    $val['is_del'] = true;
                } elseif($val['file_uid'] == $uid) {
                    $val['is_del'] = true;
                } else {
                    $val['is_del'] = false;
                }
                unset($val['folder_id']);
                unset($val['ip']);
            }
        }
        // 重新计算一下数目
        $where_num = array(
            'file_sign' => array('neq', ''),
            'file_status' => 1,
            'album_id' => $album_id
        );
        $album_info['pic_num'] = $d_community_file->change_num($where_num);
        $this->returnCode(0,$community_file);
    }

    /**
     * 删除图片 群主可以删除图片， 上传者可以删除图片， 文件夹创建者可以删除图片 后台也可以禁用
     */
    public function del_album_img(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $file_id = intval($_POST['file_id']);
        if (empty($file_id)) {
            $this->returnCode('72000020');
        }
        $m_community_file = M('Community_file');
        $file_single = $m_community_file->where(array('file_id' => $file_id))->find();
        if (!$file_single || intval($file_single['album_id']) == 0 || intval($file_single['file_status']) >= 2) {
            $this->returnCode('72000021');
        }
        $d_community_folder = D('Community_folder');
        $folder_single = $d_community_folder->folder_community_info($file_single['folder_id']);
        // 如果既不是创建者，也不是群主，无删除此文件夹权限
        if ($uid != $folder_single['folder_uid'] && $folder_single['community_info'] && $folder_single['community_info']['group_owner_uid'] != $uid && $file_single['file_uid'] != $uid) {
            $this->returnCode('72000022');
        }
        $data = array('file_status' => 3);
        $file_del = $m_community_file->where(array('file_id' => $file_id))->data($data)->save();
        if ($file_del) {
            $this->returnCode(0,array('file_id'=>$file_id));
        } else {
            $this->returnCode(1,array(),'删除失败！请重试');
        }
    }

    /**
     * 获取批量删除的图片
     */
    public function batch_del_list() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $file_sign = intval($_POST['file_sign']);
        if (empty($file_sign)) {
            $this->returnCode('72000028');
        }
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $d_community_file = D('Community_file');
        $albumM_info = D('Community_album')->album_community_info($album_id);
        if ($albumM_info && $albumM_info['community_info'] && $albumM_info['community_info']['group_owner_uid'] == $uid) {
            $user = 0;
        } else {
            $user = $uid;
        }
        $msg = $d_community_file->file_by_user($file_sign, $album_id, $user);
        // 重新计算一下数目
        $where_num = array(
            'file_sign' => array('neq', ''),
            'file_status' => 1,
            'album_id' => $album_id
        );
        $album_info['pic_num'] = $d_community_file->change_num($where_num);
        $this->returnCode(0, $msg);
    }

    /**
     * 批量删除图片 群主可以删除图片， 上传者可以删除图片， 文件夹创建者可以删除图片 后台也可以禁用
     * $file_id_str 字段为  array(1,2,3,4)
     */
    public function batch_del_album_img(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $file_id_arr = $_POST['file_id_arr'];
        if (empty($file_id_arr)) {
            $this->returnCode('72000004');
        }
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $m_community_file = M('Community_file');
        $file_list = $m_community_file->where(array('file_id' => array('in', $file_id_arr)))->select();
        $d_community_album = D('Community_album');
        foreach ($file_list as $item) {
            if (!$item || intval($item['album_id']) == 0 || intval($item['file_status']) >= 2) {
                $this->returnCode('72000021');
            }
            if (intval($item['album_id']) != $album_id) {
                $this->returnCode('72000029');
            }
            $album_single = $d_community_album->album_community_info($item['album_id']);
            // 如果既不是创建者，也不是群主，无删除此图片文件权限
            if ($uid != $album_single['folder_uid'] && !$album_single['group_owner_uid'] != $uid && $item['file_uid'] != $uid) {
                $this->returnCode('72000022');
            }
        }
        $data = array('file_status' => 3);
        $file_del = $m_community_file->where(array('file_id' => array('in', $file_id_arr)))->data($data)->save();
        if ($file_del) {
            $m_community_album = M('Community_album');
            $m_community_album->where(array('album_id' => $album_id))->setDec('pic_num', count($file_id_arr));
            $this->returnCode(0,$file_del);
        } else {
            $this->returnCode(1,array(),'删除失败！请重试');
        }
    }

    /**
     * 获取当前用户可以转移的相册 图片转移到别的相册 群主可以转移图片， 允许其他人上传的相册可以转移图片， 相册创建者可以转移图片
     * $file_id_str 字段为  array(1,2,3,4)
     */
    public function change_album(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;
        $time_order = '';
        // 时间顺序查询
        if (!empty($_POST['time_order']) && in_array(strtolower($_POST['time_order']), array('desc', 'asc'))) {
            $time_order = strtolower($_POST['time_order']);
        }
        $order = '`ca`.`add_time` DESC';
        if ($time_order) {
            $order = '`ca`.`add_time` ' . $time_order;
        }
        $where = '(`ci`.`group_owner_uid` = '. $uid . ' OR `ca`.`album_uid` = ' . $uid . ' OR `ca`.`other_is_upload` = 2) AND `ca`.`album_id`<>' . $album_id;
        // 群相册名称 模糊查询
        if (!empty($_POST['album_name'])) {
            $where .= ' AND `ca`.`album_status` = 1  AND `ca`.`community_id` = ' . $community_id .' AND `ca`.`album_name` like "%' . $_POST['album_name'] . '%"';
        } else {
            $where .= ' AND `ca`.`album_status` = 1 AND `ca`.`community_id` = ' . $community_id;
        }
        $community_album = D('Community_album')->album_info_list($where, 10, $page, $order);
        $this->returnCode(0,$community_album);
    }

    /**
     * 图片转移到别的相册 群主可以转移图片， 上传者可以转移图片， 文件夹创建者可以转移图片
     * $file_id_str 字段为  array(1,2,3,4)
     */
    public function img_change_album(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $file_id_arr = $_POST['file_id_arr'];
        if (empty($file_id_arr)) {
            $this->returnCode('72000004');
        }
        $album_id = intval($_POST['album_id']);
        if (empty($album_id)) {
            $this->returnCode('72000012');
        }
        $m_community_file = M('Community_file');
        $file_list = $m_community_file->where(array('file_id' => array('in', $file_id_arr)))->select();
        $d_community_album = D('Community_album');
        foreach ($file_list as $item) {
            if (!$item || intval($item['album_id']) == 0 || intval($item['file_status']) >= 2) {
                $this->returnCode('72000021');
            }
            $album_single = $d_community_album->album_community_info($item['album_id']);
            // 如果既不是创建者，也不是群主，无转移此图片权限
            if ($uid != $album_single['folder_uid'] && !$album_single['group_owner_uid'] != $uid && $item['file_uid'] != $uid) {
                $this->returnCode('72000023');
            }
            $d_community_album->where(array('album_id' => $item['album_id']))->setDec('pic_num', 1);
        }
        $data = array('album_id' => $album_id);
        $file_change = $m_community_file->where(array('file_id' => array('in', $file_id_arr)))->data($data)->save();
        $d_community_album->where(array('album_id' => $album_id))->setInc('pic_num', count($file_id_arr));
        if ($file_change) {
            $this->returnCode(0,$file_change);
        } else {
            $this->returnCode(1,array(),'转移失败！请重试');
        }
    }



    // 群名片区块
    /**
     * 单图片文件上传
     * */
    public function single_img_post() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        // 名片背景图
        $image = array();
        if(!empty($_FILES) && $_FILES['file']['error'] != 4){
            $image = D('Image')->handle($uid, 'comm', 1, array('size' => 10), false, true);
            if ($image['error']) {
                $this->returnCode(1,array(), $image['message']);
            }
        }
        if ($image) {
            $ret = array(
                'img_id' => $image['pigcms_id'],
                'url' => C('config.site_url') . $image['url']['image'],
            );
        } else {
            $ret = array();
        }
        $this->returnCode(0, $ret);
    }


    /**
     * 添加个人名片信息
     */
    public function add_personal_card(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $m_community_personal_card = M('Community_personal_card');
        $where = array('uid' => $uid);
        $personal_single = $m_community_personal_card->where($where)->find();
        if ($personal_single) {
            $this->returnCode('72000039');
        }
        // 信息过滤
        $data = array();
        if ($_POST['wx_ID']) {
            $data['wx_ID'] = $_POST['wx_ID'];
        }
        if ($_POST['job']) {
            $data['job'] = $_POST['job'];
        }
        if ($_POST['company']) {
            $data['company'] = $_POST['company'];
        }
        if ($_POST['address']) {
            $data['address'] = $_POST['address'];
        }
        if ($_POST['email']) {
            $data['email'] = $_POST['email'];
        }
        if ($_POST['lng']) {
            $data['lng'] = $_POST['lng'];
        }
        if ($_POST['lat']) {
            $data['lat'] = $_POST['lat'];
        }

        // 名片背景图
        if(!empty($_POST['bgimg_id'])){
            $image = M('Image')->field('pic')->where(array('pigcms_id' => $_POST['bgimg_id']))->find();
            // 名片背景图
            if ($image) {
                $data['bgimg'] = $image['pic'];
            }
        }
        // 名片个人头像，没传取默认的用户头像
        $user = M('User')->field('avatar, nickname')->where(array('uid'=>$uid))->find();
        if(!empty($_POST['avatar_id'])){
            $image = M('Image')->field('pic')->where(array('pigcms_id' => $_POST['avatar_id']))->find();
            // 名片背景图
            if ($image) {
                $data['avatar'] = $image['pic'];
            }
        } else {
            $data['avatar'] = $user['avatar'];
        }
        // 名片个人姓名，没传取默认的用户昵称
        $user = M('User')->field('avatar, nickname')->where(array('uid'=>$uid))->find();
        if(!empty($_POST['nickname'])){
            $data['nickname'] = $_POST['nickname'];
        } else {
            $data['nickname'] = $user['nickname'];
        }

        if (!empty($data)) {
            $data['add_time'] = time();
            $data['uid'] = $uid;
            $personal_id = M('Community_personal_card')->data($data)->add();
            if ($personal_id) {
                $this->returnCode(0,array('personal_id'=>$personal_id));
            } else {
                $this->returnCode(1,array(),'添加失败！请重试');
            }
        } else {
            $this->returnCode('72000030');
        }
    }

    /**
     * 编辑个人名片信息
     */
    public function edit_personal_card(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $m_community_personal_card = M('Community_personal_card');
        $where = array('uid' => $uid);
        $personal_single = $m_community_personal_card->where($where)->find();
        if (!$personal_single) {
            $this->returnCode('72000032');
        }
        if ($uid != $personal_single['uid']) {
            $this->returnCode('72000014');
        }
        // 信息过滤
        $data = array();
        if ($_POST['wx_ID'] && $_POST['wx_ID'] != $personal_single['wx_ID']) {
            $data['wx_ID'] = $_POST['wx_ID'];
        }
        if ($_POST['job'] && $_POST['job'] != $personal_single['job']) {
            $data['job'] = $_POST['job'];
        }
        if ($_POST['company'] && $_POST['company'] != $personal_single['company']) {
            $data['company'] = $_POST['company'];
        }
        if ($_POST['address'] && $_POST['address'] != $personal_single['address']) {
            $data['address'] = $_POST['address'];
        }
        if ($_POST['email'] && $_POST['email'] != $personal_single['email']) {
            $data['email'] = $_POST['email'];
        }
        if ($_POST['lng'] && $_POST['lng'] != $personal_single['lng']) {
            $data['lng'] = $_POST['lng'];
        }
        if ($_POST['lat'] && $_POST['lat'] != $personal_single['lat']) {
            $data['lat'] = $_POST['lat'];
        }

        // 名片背景图
        if(!empty($_POST['bgimg_id'])){
            $image = M('Image')->field('pic')->where(array('pigcms_id' => $_POST['bgimg_id']))->find();
            // 名片背景图
            if ($image) {
                $data['bgimg'] = $image['pic'];
            }
        }
        // 名片个人头像，没传取默认的用户头像
        $user = M('User')->field('avatar, nickname')->where(array('uid'=>$uid))->find();
        if(!empty($_POST['avatar_id'])){
            $image = M('Image')->field('pic')->where(array('pigcms_id' => $_POST['avatar_id']))->find();
            // 名片背景图
            if ($image) {
                $data['avatar'] = $image['pic'];
            }
        } elseif(!$personal_single['avatar']) {
            $data['avatar'] = $user['avatar'];
        }
        // 名片个人姓名，没传取默认的用户昵称
        $user = M('User')->field('avatar, nickname')->where(array('uid'=>$uid))->find();
        if(!empty($_POST['nickname'])){
            $data['nickname'] = $_POST['nickname'];
        } elseif(!$personal_single['nickname']) {
            $data['nickname'] = $user['nickname'];
        }

        if (!empty($data)) {
            $data['add_time'] = time();
            $data['uid'] = $uid;
            $personal_id = $m_community_personal_card->where($where)->data($data)->save();
            if ($personal_id) {
                $this->returnCode(0,array('personal_id'=>$personal_id));
            } else {
                $this->returnCode(1,array(),'编辑失败！请重试');
            }
        } else {
            $this->returnCode(0,array('personal_id'=>$personal_single['personal_id']));
        }
    }

    /**
     * 获取个人名片信息
     * */
     public function personal_detail(){
         if (!$this->_uid) {
             $this->returnCode('20044013');
         }
         $uid = $this->_uid;
         $m_community_personal_card = M('Community_personal_card');
         $where = array('uid' => $uid);
         $personal_single = $m_community_personal_card->where($where)->find();
         if (!$personal_single) {
             $this->returnCode(0, array());
         }
         $site_url = C('config.site_url');
         if ($personal_single['bgimg']) $personal_single['bgimg'] = $site_url . $personal_single['bgimg'];
         if ($personal_single['avatar']) $personal_single['avatar'] = $site_url . $personal_single['avatar'];
         $this->returnCode(0, $personal_single);
     }


    /**
     * 添加群名片的时候获取到的数据
     * */
    public function info_add_card() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $community_info = M('Community_info')->field('group_owner_uid, community_name')->where(array('community_id' => $community_id))->find();
        if ($community_info['group_owner_uid'] != $uid) {
            $this->returnCode('72000042');
        }
        $word = $this->card_word(true);
        $this->returnCode(0,$word);
    }

    /**
     * 添加群名片
     * */
    public function add_community_card(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $m_community_card = M('Community_card');
        $where = array(
            'community_id' => $community_id
        );
        $card_single = $m_community_card->where($where)->find();
        if ($card_single) {
            $this->returnCode('72000040');
        }
        // 群名片名称处理
        $card_name = $_POST['card_name'];
        if (empty($card_name)) {
            $this->returnCode('72000033');
        }
        // 群名片填写项处理
        $option = json_decode(htmlspecialchars_decode($_POST['option']), true);
        if (empty($option)) {
            $this->returnCode('72000034');
        }
        // 名片背景图
        $image = array();
        if(!empty($_FILES) && $_FILES['file']['error'] != 4){
            $image = D('Image')->handle($uid, 'comm', 1, array('size' => 10), false, true);
            if ($image['error']) {
                $this->returnCode(1,array(), $image['message']);
            }
        }
        // 名片背景图
        if ($image) {
            $data['community_card_bgimg'] = $image['url']['image'];
        }
        // 简介
        if ($_POST['community_card_des']) {
            $data['community_card_des'] = $_POST['community_card_des'];
        }
        $data['add_time'] = time();
        $data['community_id'] = $community_id;
        $data['card_name'] = $card_name;
        D()->startTrans();
        $community_card_id = $m_community_card->data($data)->add();
        $msg['id'] = $community_card_id;
        $msg['type'] = 'community_card';
        if(!$data['community_card_bgimg']){
            $com_img = '/static/community/chat/comm_card.png';
        }else{
            $com_img = $data['community_card_bgimg'];
        }
        $msg['img'] = C('config.site_url').$com_img;
        $msg['content'] = $data['community_card_des'];
        $dynamic_data=array(
            'community_id'  =>  $data['community_id'],
            'user_id'       =>  $this->_uid,
            'application_detail'  =>  serialize($msg),
            'addtime'       =>  time()
        );
        $dynamic = D('Community_dynamic')->data($dynamic_data)->add();
        if(!$dynamic){
            $this->returnCode(1,array(),'同步到动态失败!请重试~');
        }
        if ($community_card_id) {
            //添加选项
            $option_data = [];
            if ($option) {
                $option_name = array();
                $default_word = array();
                foreach ($option as $key => $val) {
                    if ($option_name && in_array($val['option_name'], $option_name)) {
                        D()->rollback();
                        $this->returnCode('72000037');
                    }
                    if ($default_word && in_array($val['default_word'], $default_word)) {
                        D()->rollback();
                        $this->returnCode('72000045');
                    }
                    $option_name[] = $val['option_name'];
                    if ($val['default_word']) $default_word[] = $val['default_word'];
                    $option_data[$key]['community_card_id'] = $community_card_id;
                    $option_data[$key]['community_id'] = $community_id;
                    $option_data[$key]['option_uid'] = $uid;
                    $option_data[$key]['option_name'] = $val['option_name'];
                    $option_data[$key]['is_required'] = in_array(intval($val['is_required']), array(1,2)) ? intval($val['is_required']) : 1; // 是否必填1-不必填，2-必填
                    $option_data[$key]['is_look'] = in_array(intval($val['is_look']), array(1,2)) ? intval($val['is_look']) : 1; // 群成员是否可查看（隐私问题） 1 可以查看  2 不可查看
                    $option_data[$key]['option_type'] = in_array(intval($val['option_type']), array(1,2)) ? intval($val['option_type']) : 1; // 默认单行文本（类型1-单行文本，2-多行文本）
                    $option_data[$key]['option_content'] = $val['option_content'] ? json_encode($val['option_content']) : '';
                    $option_data[$key]['default_word'] = $val['default_word'] ? $val['default_word'] : '';
                    $option_data[$key]['add_time'] = time();
                }
                $option_data = array_values($option_data);
                $addOption = M('Community_card_option_bind')->addAll($option_data);
                if (!$addOption) {
                    D()->rollback();
                    $this->returnCode(1,array(),'添加失败！请重试');
                }
            } else {
                D()->rollback();
                $this->returnCode(1,array(),'添加失败！请重试');
            }
            D()->commit();
            // 发送消息
            D('Community_card')->card_info_send($community_card_id, $uid);
            $this->returnCode(0,array('community_card_id'=>$community_card_id));
        } else {
            $this->returnCode(1,array(),'添加失败！请重试');
        }

    }


    /**
     * 获取群名片编辑信息
     * */
    public function community_card_info(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $m_community_card = M('Community_card');
        $where = array(
            'community_id' => $community_id
        );
        $community_info = M('Community_info')->field('group_owner_uid, community_name')->where(array('community_id' => $community_id))->find();
        if ($community_info['group_owner_uid'] != $uid) {
            $this->returnCode(0,array('msg' => '抱歉，您不是群主，不能编辑群名片！', 'community_name' => $community_info['community_name'], 'community_id' => $community_id));
        }
        $card_single = $m_community_card->where($where)->find();
        if (!$card_single) {
            $this->returnCode('72000036');
        }
        // 获取选项信息
        $m_community_card_option_bind = M('Community_card_option_bind');
        $single_where = array('community_id'=>$community_id,'community_card_id'=>$card_single['community_card_id'],'option_status'=>1);
        $option_detail = $m_community_card_option_bind->where($single_where)->order('option_id asc')->select();
//        foreach($option_detail as $key=>$value){
//            if($value['is_required'] == 1){
//                $option_detail[$key]['is_required'] = false;
//            }else{
//                $option_detail[$key]['is_required'] = true;
//            }
//            if($value['is_look'] == 1){
//                $option_detail[$key]['is_look'] = true;
//            }else{
//                $option_detail[$key]['is_look'] = false;
//            }
//        }

        $already_word = array();
        if ($option_detail) {
            foreach ($option_detail as $val) {
                if ($val['default_word']) {
                    $already_word[] = $val['default_word'];
                }
            }
        }
        $word_select = $this->card_word(true, $already_word);
        if (!$word_select) $word_select = array();

        if ($card_single['community_card_bgimg']) {
            $card_single['community_card_bgimg'] = C('config.site_url') . $card_single['community_card_bgimg'];
        }
        $info =array(
            'community_card' => $card_single,
            'option_detail' => $option_detail,
            'word_select' => $word_select
        );
        $this->returnCode(0, $info);
    }

    /**
     * 编辑群名片
     * */
    public function edit_community_card(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $edit_data = array();
        $m_community_card = M('Community_card');
        $where = array(
            'community_id' => $community_id
        );
        $card_single = $m_community_card->where($where)->find();
        if (!$card_single) {
            $this->returnCode('72000036');
        }
        // 群名片名称处理
        if ($_POST['card_name'] && $_POST['card_name'] != $card_single['card_name']) {
            $edit_data['card_name'] = $_POST['card_name'];
        }
        // 名片背景图
        $image = array();
        if(!empty($_FILES) && $_FILES['file']['error'] != 4){
            $image = D('Image')->handle($uid, 'comm', 1, array('size' => 10), false, true);
            if ($image['error'])  {
                $this->returnCode(1,array(), $image['message']);
            }
        }
        // 名片背景图
        if ($image['url']['image'] && $image['url']['image'] != $card_single['community_card_bgimg']) {
            $edit_data['community_card_bgimg'] = $image['url']['image'];
        }
        // 简介
        if ($_POST['community_card_des'] && $_POST['community_card_des'] != $card_single['community_card_des']) {
            $edit_data['community_card_des'] = $_POST['community_card_des'];
        }
        // 群名片填写项处理
        $option = json_decode(htmlspecialchars_decode($_POST['option']), true);

        D()->startTrans();
        if (!empty($edit_data)) {
            $community_card_id = $m_community_card->where($where)->data($edit_data)->save();
        }
        $community_card_id = $card_single['community_card_id'];
        if ($community_card_id) {
            //修改选项
            $m_community_card_option_bind = M('Community_card_option_bind');
            $single_where = array('community_id'=>$community_id,'community_card_id'=>$community_card_id,'option_status'=>1);
            $option_detail = $m_community_card_option_bind->where($single_where)->select();

            // 已有自定义名称
            $old_option_name = array();
            // 新的自定义项名称
            $new_option_name = array();
            // 新编辑的选项内容处理
            $option_new = array();
            if ($option) {
                $default_word = array();
                foreach ($option as $key => $val) {
                    if ($new_option_name && in_array($val['option_name'], $new_option_name)) {
                        D()->rollback();
                        $this->returnCode('72000037');
                    }
                    if ($default_word && in_array($val['default_word'], $default_word)) {
                        D()->rollback();
                        $this->returnCode('72000045');
                    }
                    if ($val['default_word']) $default_word[] = $val['default_word'];
                    $new_option_name[] = $val['option_name'];
                    $option_new[$val['option_name']] = array(
                        'option_name' => $val['option_name'], //选项名称
                        'is_look'     => in_array(intval($val['is_look']), array(1,2)) ? intval($val['is_look']) : 1, // 群成员是否可查看（隐私问题） 1 可以查看  2 不可查看
                        'option_type' => in_array(intval($val['option_type']), array(1,2)) ? intval($val['option_type']) : 1, //类型1-单行文本，2-多行文本
                        'is_required' => in_array(intval($val['is_required']), array(1,2)) ? intval($val['is_required']) : 1, //是否必填0-不必填，1-必填
                        'option_content' => $val['option_content'] ? json_encode($val['option_content']) : '',
                        'default_word' => $val['default_word'] ? $val['default_word'] : '',
                    );
                }
            }
            if ($option_detail) {
                //删除 编辑选项
                $where_detail = array('community_card_id'=>$community_card_id);
                foreach ($option_detail as $key => $val) {
                    $old_option_name[] = $val['option_name'];
                    $where_detail['option_id'] = $val['option_id'];
                    if (in_array($val['option_name'], $new_option_name)) { // 编辑选项
                        $arr = $option_new[$val['option_name']];
                        $arr['add_time'] = time();
                        $opt = $m_community_card_option_bind->where($where_detail)->data($arr)->save();
                    } else { // 删除选项
                        $opt = $m_community_card_option_bind->where($where_detail)->data(array('option_status'=>2))->save();
                    }
                    if (!$opt) {
                        D()->rollback();
                        $this->returnCode(1,array(),'编辑失败！请重试');
                    }
                }
            }
            // 新增选项
            if ($option) {
                foreach ($option as $key => $val) {
                    if (!in_array($val['option_name'], $old_option_name)) {
                        $option_data = array(
                            'option_name' => $val['option_name'], //选项名称
                            'is_look'     => in_array(intval($val['is_look']), array(1,2)) ? intval($val['is_look']) : 1, // 群成员是否可查看（隐私问题） 1 可以查看  2 不可查看
                            'option_type' => in_array(intval($val['option_type']), array(1,2)) ? intval($val['option_type']) : 1, //类型1-单行文本，2-多行文本
                            'is_required' => in_array(intval($val['is_required']), array(1,2)) ? intval($val['is_required']) : 1, //是否必填0-不必填，1-必填
                            'community_id' => $community_id,
                            'community_card_id' => $community_card_id,
                            'option_content' => $val['option_content'] ? json_encode($val['option_content']) : '',
                            'default_word' => $val['default_word'] ? json_encode($val['default_word']) : '',
                            'option_uid' => $uid,
                            'add_time' => time(),
                        );
                        $add = $m_community_card_option_bind->data($option_data)->add();
                        if (!$add) {
                            D()->rollback();
                            $this->returnCode(1,array(),'编辑失败！请重试');
                        }
                    }
                }
            }
            D()->commit();
            $this->returnCode(0,array('community_card_id'=>$community_card_id));
        } else {
            $this->returnCode(1,array(),'编辑失败！请重试');
        }
    }

    /**
     * 群名片信息
     * */
    public function single_community_card(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $m_community_card = M('Community_card');
        $where = array(
            'community_id' => $community_id
        );
        $community_info = M('Community_info')->field('group_owner_uid, community_name')->where(array('community_id' => $community_id))->find();
        $card_single = $m_community_card->where($where)->find();
        $is_owner = false;
        if ($community_info['group_owner_uid'] == $uid) {
            $is_owner = true;
            $card_single['owner_avatar'] = M('User')->where(array('uid'=>$community_info['group_owner_uid']))->getField('avatar');
        }
        if (!$card_single) {
            $this->returnCode('72000036');
        }
        if ($card_single['community_card_bgimg']) {
            $card_single['community_card_bgimg'] = C('config.site_url') . $card_single['community_card_bgimg'];
        }

        $info =array(
            'community_card' => $card_single,
            'is_owner' => $is_owner
        );
        $this->returnCode(0, $info);
    }

    /**
     * 用户加入群名片
     * */
    public function join_community_card(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        // 验证是否是群成员
        $join_info = M('Community_join')->where(array('community_id'=>$community_id,'add_uid'=>$uid, 'add_status' => 3))->find();
        if (!$join_info ) {
            $this->returnCode('70000009', array('community_id' => $community_id));
        }
        $m_community_card = M('Community_card');
        $m_community_card_bind = M('Community_card_bind');
        $m_community_card_option_bind = M('Community_card_option_bind');
        $where = array(
            'community_id' => $community_id
        );
        $card_single = $m_community_card->where($where)->find();
        if (!$card_single) {
            $this->returnCode('72000036');
        }
        $where['join_uid'] = $uid;
        $where['community_id'] = $community_id;
        $where['join_status'] = 1;
        $info = $m_community_card_bind->where($where)->find();
        if ($info) {
            $this->returnCode('72000038');
        }
        // 名片背景图
        if(!empty($_POST['bgimg_id'])){
            $image = M('Image')->field('pic')->where(array('pigcms_id' => $_POST['bgimg_id']))->find();
            // 名片背景图
            if ($image) {
                $data['join_bgimg'] = $image['pic'];
            }
        } elseif($_POST['join_bgimg']) {
            $data['join_bgimg'] = $_POST['join_bgimg'];
        }
        // 名片个人头像，没传取默认的用户头像
        if(!empty($_POST['avatar_id'])){
            $image = M('Image')->field('pic')->where(array('pigcms_id' => $_POST['avatar_id']))->find();
            // 名片背景图
            if ($image) {
                $data['join_avatar'] = $image['pic'];
            }
        } elseif($_POST['join_bgimg']) {
            $data['join_bgimg'] = $_POST['join_bgimg'];
        }
        if ($_POST['lng']) {
            $data['lng'] = $_POST['lng'];
        }
        if ($_POST['lat']) {
            $data['lat'] = $_POST['lat'];
        }
        $single_where = array('community_id'=>$community_id,'community_card_id'=>$card_single['community_card_id'],'option_status'=>1);
        $card_option = $m_community_card_option_bind->where($single_where)->select();
        $nickname = '';
        $phone = '';
        if ($card_option) {
            $option = $_POST['option'];
            foreach ($card_option as &$val) {
                if ($val['is_required'] == 2 && !$option[$val['option_name']]) {
                    $this->returnCode(1, array(),$val['option_name'].'为必填项');
                }
                // 如果是姓名、昵称 特殊处理
                if ($val['default_word'] == 'nickname') {
                    $nickname = $option[$val['option_name']];
//                    unset($option[$val['option_name']]);
                } else if ($val['default_word'] == 'phone') {
                    // 如果是手机号 特殊处理
                    $phone = $option[$val['option_name']];
//                    unset($option[$val['option_name']]);
                }
            }
            if ($nickname) {
                $data['nickname'] = $nickname;
            }
            if ($phone) {
                $data['phone'] = $phone;
            }
            $data['content'] = json_encode($option);
        }
        if (!empty($data)) {
            $data['join_time'] = time();
            $data['join_uid'] = $uid;
            $data['community_id'] = $community_id;
            $data['community_card_id'] = $card_single['community_card_id'];
            $join_id = $m_community_card_bind->data($data)->add();
            if ($join_id) {
                $community_card_id = $m_community_card->where($where)->setInc('join_num');
                if (!$community_card_id) {
                    $m_community_card_bind->change_num($where);
                }
                $this->returnCode(0,array('join_id'=>$join_id));
            } else {
                $this->returnCode(1,array(),'添加失败！请重试');
            }
        } else {
            $this->returnCode('72000030');
        }

    }

    /**
     * 获取群名片及群名片下的群成员名片信息
     * */
    public function community_card_join_info() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $community_info = M('Community_info')->field('group_owner_uid')->where(array('community_id' => $community_id))->find();
        if (!$community_info) {
            $this->returnCode('70000005');
        }
        // 验证是否是群成员
        $join_msg = M('Community_join')->where(array('community_id'=>$community_id,'add_uid'=>$uid))->find();
        if (!$join_msg || $join_msg['add_status'] != 3) {
            if (!$join_msg) $join_msg = array();
            $this->returnCode(0, array('community_id' => $community_id, 'join_info' => $join_msg, 'error' => true, 'errmsg' => '用户未加入该群！'));
        }
        $m_community_card = M('Community_card');
        $d_community_card_bind = D('Community_card_bind');
        $where = array(
            'community_id' => $community_id
        );
        $is_owner = false;
        if ($uid == $community_info['group_owner_uid']) {
            $is_owner = true;
        }
        $card_single = $m_community_card->where($where)->find();
        if (!$card_single) {
            $return = array(
                'community_card' => array(),
                'join_info' => array(),
                'is_owner' => $is_owner,
                'is_join' => false
            );
            $this->returnCode(0, $return);
        }
        $page = $_POST['page'] ? $_POST['page'] : 1;
        $where = array(
            'join_status' => 1,
            'community_id' => $community_id,
            'community_card_id' => $card_single['community_card_id']
        );
        if ($_POST['nickname']) {
            $where['nickname'] = '%' . $_POST['nickname'] . '%';
        }
        $join_info = $d_community_card_bind->join_card_list($where, 8, $page);
        if (!$join_info) $join_info = array();
        // 查询一下当前用户是否加入
        $where_join = array(
            'join_status' => 1,
            'community_id' => $community_id,
            'community_card_id' => $card_single['community_card_id'],
            'join_uid' => $uid
        );
        $join = $d_community_card_bind->where($where_join)->field('join_uid')->find();
        $is_join = false;
        if ($join) {
            $is_join = true;
        }
        $where_num = array(
            'join_status' => 1,
            'community_id' => $community_id,
            'community_card_id' => $card_single['community_card_id']
        );
        $m_community_card->where($where_num)->setInc('browse_num');
        if (!$is_owner) {
            unset($card_single['excel_url']);
        }
        // 获取群主的头像和昵称
        $user = M('User')->field('nickname, avatar')->where(array('uid' => $community_info['group_owner_uid']))->find();
        if ($card_single['community_card_bgimg']) {
            $card_single['community_card_bgimg'] = C('config.site_url') . $card_single['community_card_bgimg'];
        }
        $return = array(
            'owner' => $user,
            'community_card' => $card_single,
            'join_info' => $join_info,
            'is_owner' => $is_owner,
            'is_join' => $is_join
        );
        $this->returnCode(0, $return);
    }

    /**
     * 获取群名片成员详细信息
     * */
    public function community_card_member_detail() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $join_id = intval($_POST['join_id']);
        if (empty($join_id)) {
            $this->returnCode('72000041');
        }
        $where = array(
            'community_id' => $community_id
        );
        $m_community_card = M('Community_card');
        $card_single = $m_community_card->field('card_name')->where($where)->find();
        if (!$card_single) {
            $this->returnCode('72000036');
        }
        $community_info = M('Community_info')->field('group_owner_uid, community_name')->where(array('community_id' => $community_id))->find();
        if (!$community_info) {
            $this->returnCode('70000005');
        }
        $d_community_card_bind = D('Community_card_bind');
        $card_where['community_id'] = $community_id;
        $card_where['community_card_id'] = $card_single['community_card_id'];
        $community_card_bind = $d_community_card_bind->where($card_where)->setInc('browse_number');//单个群名片浏览量
        $where_join = array(
            'join_id' => $join_id
        );
        $join = $d_community_card_bind->where($where_join)->field(true)->find();
        $is_owner = false;
        if ($uid == $community_info['group_owner_uid']) {
            $is_owner = true;
        }
        // 获取群名片的选项
        $where['option_status'] = 1;
        $option = M('Community_card_option_bind')->field('option_name, is_look')->where($where)->find();
        $option_info = array();
        foreach ($option as $val) {
            $option_info[$val['option_name']] = $option_info[$val['is_look']];
        }
        if ($join) {
            // 如果不是群主且群成员不可看的隐私内容，过滤掉
            if ($join['content']) {
                $join['content']  = json_decode($join['content'], true);
                if (!$is_owner && $join['join_uid'] != $uid) {
                    foreach ($join['content'] as $k => $v) {
                        if (!$option_info[$k] || $option_info[$k] == 2) {
                            unset($join['content'][$k]);
                        }
                    }
                }
            }
        }
        // 单独判断手机号和昵称
        $card_word = $this->card_word(true);
        if (!$join['nickname']){
            $join['nickname'] = M('User')->where(array('uid'=>$join['join_uid']))->getField('nickname');
        }
        if ($join && $option_info[$card_word['nickname']['word']] == 1 && !$join['content'][$card_word['nickname']['word']]) {
            if (!$join['content']) $join['content'] = array();
            $join['content'][$card_word['nickname']['word']] = $join['nickname'];
        }
        if ($join && $option_info[$card_word['phone']['word']] == 1 && !$join['content'][$card_word['phone']['word']]) {
            if (!$join['content']) $join['content'] = array();
            $join['content'][$card_word['phone']['word']] = $join['phone'];
        }
        if ($join['join_uid'] != $uid) {
            $join['is_user'] = false;
        } else {
            $join['is_user'] = true;
        }
        $join['card_name'] = $card_single['card_name'];
        $join['community_name'] = $community_info['community_name'];
        $join['card_praise'] = D('Community_card_praise')->where(array('join_id'=>$join_id))->count();
        $can_praise = D('Community_card_praise')->where(array('join_id'=>$join_id,'card_praise_user_id'=>$this->_uid))->find();
        if($can_praise){
            $join['can_praise'] = false;
        }else{
            $join['can_praise'] = true;
        }
        $site = C('config.site_url');
        if ($join['join_avatar']) $join['join_avatar'] = $site . $join['join_avatar'];
        if ($join['join_bgimg']) $join['join_bgimg'] = $site . $join['join_bgimg'];
        if($join['join_uid'] && !$join['join_avatar']){
            $join['join_avatar'] = M('User')->where(array('uid'=>$join['join_uid']))->getField('avatar');
        }
        $this->returnCode(0, $join);
    }

    /**
     * 获取群名片成员详细编辑信息
     * */
    public function community_card_edit_info() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $join_id = intval($_POST['join_id']);
        if (empty($join_id)) {
            $this->returnCode('72000041');
        }
        $m_community_card_bind = M('Community_card_bind');
        $where_join = array(
            'join_id' => $join_id
        );
        $join = $m_community_card_bind->where($where_join)->field(true)->find();
        if (!$join) {
            $this->returnCode('72000050');
        }
        if ($join['join_uid'] != $uid) {
            $this->returnCode('72000051');
        }
        $where = array(
            'community_id' => $join['community_id']
        );
        $m_community_card = M('Community_card');
        $card_single = $m_community_card->field('card_name')->where($where)->find();
        if (!$card_single) {
            $this->returnCode('72000036');
        }
        $community_info = M('Community_info')->field('group_owner_uid, community_name')->where(array('community_id' => $join['community_id']))->find();
        if (!$community_info) {
            $this->returnCode('70000005');
        }
        $content = $join['content']  = json_decode($join['content'], true);
        // 获取选项
        $m_community_card_option_bind = M('Community_card_option_bind');
//        $m_community_personal_card = M('Community_personal_card');
//        $where_card = array('uid' => $uid);
//        $personal_single = $m_community_personal_card->where($where_card)->find();
        $single_where = array('community_id'=>$join['community_id'],'community_card_id'=>$join['community_card_id'],'option_status'=>1);
        $card_option = $m_community_card_option_bind->where($single_where)->select();
        $content_info = array();
        if ($card_option) {
            $card_word = $this->card_word(true);
            foreach ($card_option as $key => &$val) {
                $content_info[$val['option_name']] = array();
                $content_info[$val['option_name']]['is_required'] = $val['is_required'];
                $content_info[$val['option_name']]['option_type'] = $val['option_type'];
                $content_info[$val['option_name']]['is_look'] = $val['is_look'];
                if ($content[$val['option_name']]) {
                    $content_info[$val['option_name']]['value'] = $content[$val['option_name']];
                } elseif ($val['option_name'] == $card_word['nickname']['word']) {
                    $content_info[$val['option_name']]['value'] = $join['nickname'];
                } elseif ($val['option_name'] == $card_word['phone']['word']) {
                    $content_info[$val['option_name']]['value'] = $join['phone'];
                } else {
                    $content_info[$val['option_name']]['value'] = '';
                }
            }
        }
        $join['card_option'] = $card_option;
        $join['content_info'] = $content_info;
        if ($join['join_uid'] != $uid) {
            $join['is_user'] = false;
        } else {
            $join['is_user'] = true;
        }
        $join['card_name'] = $card_single['card_name'];
        $join['community_name'] = $community_info['community_name'];
        $site = C('config.site_url');
        if ($join['join_avatar']) $join['join_avatar'] = $site . $join['join_avatar'];
        if ($join['join_bgimg']) $join['join_bgimg'] = $site . $join['join_bgimg'];
        $this->returnCode(0, $join);
    }

    /**
     * 用户编辑群名片个人名片信息
     * */
    public function edit_join_community_card(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $join_id = intval($_POST['join_id']);
        if (empty($join_id)) {
            $this->returnCode('72000041');
        }
        $m_community_card = M('Community_card');
        $m_community_card_bind = M('Community_card_bind');
        $m_community_card_option_bind = M('Community_card_option_bind');
        $where_join = array(
            'join_id' => $join_id
        );
        $join = $m_community_card_bind->where($where_join)->field(true)->find();
        if (!$join) {
            $this->returnCode('72000050');
        }
        if ($join['join_uid'] != $uid) {
            $this->returnCode('72000051');
        }
        $where = array(
            'community_id' => $join['community_id'],
            'community_card_id' => $join['community_card_id']
        );
        $card_single = $m_community_card->where($where)->find();
        if (!$card_single) {
            $this->returnCode('72000036');
        }
        // 名片背景图
        if(!empty($_POST['join_bgimg_id'])){
            $image = M('Image')->field('pic')->where(array('pigcms_id' => $_POST['join_bgimg_id']))->find();
            // 名片背景图
            if ($image) {
                $data['join_bgimg'] = $image['pic'];
            }
        }
        // 名片个人头像，没传取默认的用户头像
        if(!empty($_POST['join_avatar_id'])){
            $image = M('Image')->field('pic')->where(array('pigcms_id' => $_POST['join_avatar_id']))->find();
            // 名片背景图
            if ($image) {
                $data['join_avatar'] = $image['pic'];
            }
        }
        if ($_POST['lng'] && $_POST['lng'] != $join['lng']) {
            $data['lng'] = $_POST['lng'];
        }
        if ($_POST['lat'] && $_POST['lat'] != $join['lat']) {
            $data['lat'] = $_POST['lat'];
        }
        if (!empty($_POST['option']) && json_decode($join['content'], true) != $_POST['option']) {
            $single_where = array('community_id'=>$join['community_id'],'community_card_id'=>$join['community_card_id'],'option_status'=>1);
            $card_option = $m_community_card_option_bind->where($single_where)->select();
            $nickname = '';
            $phone = '';
            if ($card_option) {
                $option = $_POST['option'];
                foreach ($card_option as &$val) {
                    if ($val['is_required'] == 2 && !$option[$val['option_name']]) {
                        $this->returnCode(1, array(),$val['option_name'].'为必填项');
                    }
                    // 如果是姓名、昵称 特殊处理
                    if ($val['default_word'] == 'nickname') {
                        $nickname = $option[$val['option_name']];
//                    unset($option[$val['option_name']]);
                    } else if ($val['default_word'] == 'phone') {
                        // 如果是手机号 特殊处理
                        $phone = $option[$val['option_name']];
//                    unset($option[$val['option_name']]);
                    }
                }
                $data['content'] = json_encode($option);
            }
            if ($nickname) {
                $data['nickname'] = $nickname;
            }
            if ($phone) {
                $data['phone'] = $phone;
            }
        }
        if (!empty($data)) {
            $data['join_time'] = time();
            $join_id = $m_community_card_bind->where($where_join)->data($data)->save();
            if ($join_id) {
                $this->returnCode(0,array('join_id'=>$join_id));
            } else {
                $this->returnCode(1,array(),'编辑失败！请重试');
            }
        } else {
            $this->returnCode('72000030');
        }

    }

    /**
     * 获取用户需要填写的字段信息
     */
    public function card_join_content() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $m_community_card = M('Community_card');
        $m_community_card_option_bind = M('Community_card_option_bind');
        $where = array(
            'community_id' => $community_id
        );
        $card_single = $m_community_card->where($where)->find();
        if (!$card_single) {
            $this->returnCode('72000036');
        }
        $m_community_personal_card = M('Community_personal_card');
        $where_card = array('uid' => $uid);
        $personal_single = $m_community_personal_card->where($where_card)->find();
        $single_where = array('community_id'=>$community_id,'community_card_id'=>$card_single['community_card_id'],'option_status'=>1);
        $card_option = $m_community_card_option_bind->where($single_where)->select();
        if ($card_option) {
            foreach ($card_option as &$val) {
                if ($val['default_word'] && $personal_single[$val['default_word']]) {
                    $val['word_value'] = $personal_single[$val['default_word']];
                }
            }
        }
        $card_option['avatar'] = M('User')->where(array('uid'=>$uid))->getField('avatar');
        $this->returnCode(0, $card_option);
    }

    /**
     * 名片导出
     * */
    public function card_export(){
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $community_id = $_POST['community_id'];
        $m_community_card = M('Community_card');
        $card_info = $m_community_card->where(array('community_id' => $community_id))->find();
        if (empty($card_info)) {
            $this->returnCode('72000036');
        }
        $community_info = M('Community_info')->field('group_owner_uid, community_name')->where(array('community_id' => $community_id))->find();
        if ($community_info['group_owner_uid'] != $uid) {
            $this->returnCode('72000043');
        }
        // 查询一下选项信息
        $option_where = array('community_card_id' => $card_info['community_card_id'], 'community_id' => $community_id, 'option_status' => 1);
        $community_card_option = M('Community_card_option_bind')->field('option_name')->where($option_where)->select();
        $where = array(
            'community_card_id' => $card_info['community_card_id'],
            'community_id' => $community_id,
            'join_status' => 1
        );
        $card_join = M('Community_card_bind')->where($where)->order('join_time ASC')->select();
        $join_number = count($card_join);
        if(count($card_join) <= 0){
            $this->returnCode('72000044');
        }

        $title_msg = '【' . $community_info['community_name'] . "】群名片名称： " . $card_info['card_name'] . '；共计' . $join_number . '成员加入。';
        $col_num = count($community_card_option) + 5;

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

        $title = $card_info['card_name'] . '名片-成员列表';

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $length = ceil(count($card_join)/1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle($title);
            $objActSheet = $objExcel->getActiveSheet();
            // 添加第一行表头
            $objActSheet->mergeCellsByColumnAndRow('0', '1', '' . $col_num, '1');
            $objActSheet->setCellValueByColumnAndRow('0', '1', $title_msg);
            $objActSheet->setCellValueByColumnAndRow('0', '2', '序号');
            $objActSheet->setCellValueByColumnAndRow('1', '2', '姓名');
            $objActSheet->setCellValueByColumnAndRow('2', '2','手机号');
            $column = 2;
            if (!empty($community_card_option)) {
                foreach ($community_card_option as $key => $val) {
                    $column = $key + 3;
                    $col_str = '' . $column;
                    $objActSheet->setCellValueByColumnAndRow($col_str, '2', $val['option_name']);
                }
            }
            $col_str = '' . ($column + 1);
            $objActSheet->setCellValueByColumnAndRow($col_str, '2', '加入时间');

            if (!empty($card_join)) {
                $index = 3;

                $cell_list = range(0,$col_num);
                foreach ($cell_list as $cell) {
                    $objActSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($cell))->setWidth(20);
                    $objActSheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($cell))->getAlignment()->setWrapText(true);
                }

                foreach ($card_join as $value) {
                    $objActSheet->setCellValueExplicitByColumnAndRow('0', $index, trim($value['join_id']));
                    $objActSheet->setCellValueExplicitByColumnAndRow('1', $index, trim($value['nickname']));
                    $objActSheet->setCellValueExplicitByColumnAndRow('2', $index, trim($value['phone']));
                    $columns = 2;
                    if (!empty($community_card_option)) {
                        $content = !empty($value['content']) ? json_decode($value['content'], true) : '';
                        foreach ($community_card_option as $key => $val) {
                            $columns = $key + 3;
                            if (!empty($content) && $content[$val['option_name']]) {
                                $content_info = $content[$val['option_name']];
                                if (is_array($content_info)) {
                                    $content_info = implode("/", $content_info);
                                }
                                $content_info = $this->filterEmoji($content_info);
                                $objActSheet->setCellValueExplicitByColumnAndRow('' . $columns, $index, trim($content_info));
                            } else {
                                $objActSheet->setCellValueExplicitByColumnAndRow('' . $columns, $index, '');
                            }
                        }
                    }
                    $objActSheet->setCellValueExplicitByColumnAndRow('' . ($columns + 1), $index, date("Y-m-d H:i:s", $value['join_time']));
                    $index++;
                }
            }
        }
        $file_id = sprintf("%09d", $community_id);
        $rand_num = substr($file_id, 0, 3) . '/' . substr($file_id, 3, 3) . '/' . substr($file_id, 6, 3);
        $title_info = $community_id . '.xlsx';
        $upload_dir = "upload/comm/community_card/{$rand_num}/";
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
        }
        $_savePath = $upload_dir . $title_info;
        ob_end_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objWriter->save($_savePath);

        $excel_url = '/' . $_savePath;
        $m_community_card->where($where)->data(array('excel_url' => $excel_url))->save();
        $this->returnCode(0, array('excel_url'=> C('config.site_url') . $excel_url));
    }


    /**
     *  群名片字段
     * @param  bool $get_word
     * @param  array $already_word  已经有了的字段
     * @return array
     */
    private function card_word($get_word = true, $already_word = array()) {
        $word = array(
            'job' => array('val' => 'job', 'word' => '职务', 'tip' => '填写职务职称'),
            'company' => array('val' => 'company', 'word' => '公司', 'tip' => '填写所在公司名称'),
            'address' => array('val' => 'address', 'word' => '地址', 'tip' => '填写地址'),
            'email' => array('val' => 'email', 'word' => '邮箱', 'tip' => '填写联系邮箱'),
            'nickname' => array('val' => 'nickname', 'word' => '姓名', 'tip' => '填写姓名'),
            'phone' => array('val' => 'phone', 'word' => '手机号', 'tip' => '填写手机号')
        );
        $word_key = array(
            'job' => '职务',
            'company' => '公司',
            'address' => '地址',
            'email' => '邮箱',
            'nickname' => '姓名',
            'phone' => '手机号'
        );
        if ($get_word) {
            if (!empty($already_word)) {
                foreach ($word as $key=>$val) {
                    if (in_array($key, $already_word)) {
                        unset($word[$key]);
                    }
                }
            }
            $ret = $word;
        } else {
            $ret = $word_key;
        }
        return $ret;
    }

    // 过滤表情
    private function filterEmoji($str) {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }


    // 群应用拓展管理

    /**
     * 群应用拓展管理控制信息获取
     */
    public function control_application_development() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $d_community_info = D('Community_application_development');
        $community_development = $d_community_info->community_application_development($community_id);
        if (empty($community_development) || $community_development['status'] != 1) {
            $this->returnCode('72000016');
        }
        if ($this->_uid != $community_development['group_owner_uid']) {
            $this->returnCode(0, array('error' => 1, 'errmsg' => '抱歉，您不是群主不能查看此页信息'));
        }
        $community_development = $d_community_info->application_control($community_id);
        $this->returnCode(0, $community_development);
    }


    /**
     * 群应用拓展排序管理控制信息获取
     */
    public function sort_application_development() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $d_community_info = D('Community_application_development');
        $community_development = $d_community_info->community_application_development($community_id);
        if (empty($community_development) || $community_development['status'] != 1) {
            $this->returnCode('72000016');
        }
        if ($this->_uid != $community_development['group_owner_uid']) {
            $this->returnCode(0, array('error' => 1, 'errmsg' => '抱歉，您不是群主不能查看此页信息'));
        }
        $community_development = $d_community_info->application_info($community_id, 'all');
        $this->returnCode(0, $community_development);
    }


    /**
     * 群应用拓展管理
     */
    public function switch_application_development() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        unset($_POST['community_id']);

        $d_community_info = D('Community_application_development');
        $community_development = $d_community_info->community_application_development($community_id);
        if (empty($community_development) || $community_development['status'] != 1) {
            $this->returnCode('72000016');
        }
        if ($this->_uid != $community_development['group_owner_uid']) {
            $this->returnCode('72000043');
        }

        // 处理上传数据
        $switch_data = $this->filter_word($_POST, $community_development);
        $where = array('community_id'=>$community_id);
        $switch = false; // 切换成果
        $change = false;  // 是否有所改变
        if ($switch_data) {
            $change = true;
            $switch_data['set_time'] = time();
            $switch = $d_community_info->where($where)->data($switch_data)->save();
        }

        if (!$change) {
            $this->returnCode(0,array('community_id'=>$community_id));
        } else if ($switch) {
            $this->returnCode(0,array('community_id'=>$community_id));
        } else {
            $this->returnCode(1,array(),'修改失败！请重试');
        }
    }

    /**
     * 群应用拓展管理信息获取
     */
    public function single_application_development() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        $d_community_info = D('Community_application_development');
        $community_development = $d_community_info->community_application_development($community_id);
        if (empty($community_development) || $community_development['status'] != 1) {
            $this->returnCode('72000016');
        }
        if ($this->_uid != $community_development['group_owner_uid']) {
            $this->returnCode(0, array('error' => 1, 'errmsg' => '抱歉，您不是群主不能查看此页信息'));
        }
        $this->returnCode(0, $community_development);
    }

    /**
     * 获取聊天页面的群应用
     */
    public function application_chat_info(){
        if(!$_POST['community_id']){
            $this->returnCode('71000029');
        }
        $info = D('Community_application_development')->application_info($_POST['community_id'], 'index');
        $this->returnCode(0, $info);
    }
    /**
     * 获取群主页页面的群应用
     */
    public function application_more_info(){
        if(!$_POST['community_id']){
            $this->returnCode('71000029');
        }
        $info = D('Community_application_development')->application_info($_POST['community_id'], 'more');
        $this->returnCode(0, $info);
    }
    /**
     * 获取群所有群应用
     */
    public function application_all_info(){
        if(!$_POST['community_id']){
            $this->returnCode('71000029');
        }
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        $community_id = intval($_POST['community_id']);
        $community_info = M('Community_info')->field('group_owner_uid')->where(array('community_id' => $community_id))->find();
        if (!$community_info) {
            $this->returnCode('70000005');
        }
        $info = D('Community_application_development')->application_info($community_id, 'all');
        $site_url = C('config.site_url') ;
        if ($uid == $community_info['group_owner_uid'] && $info) {
            $info[] = array
            (
                'title' => '应用开关',
                'url' => '/pages/group/goupDevelopment/goupDevelopment?community_id='.$community_id,
                'icon' => $site_url . '/static/community/application/app_admin_switch.png'
            );
            $info[] = array
            (
                'title' => '应用排序',
                'url' => '/pages/group/groupDevelopmentSort/groupDevelopmentSort?community_id='.$community_id,
                'icon' => $site_url . '/static/community/application/app_admin_sort.png'
            );
        } else if($uid == $community_info['group_owner_uid']) {
            $info = array();
            $info[] = array
            (
                'title' => '应用开关',
                'url' => '/pages/group/goupDevelopment/goupDevelopment?community_id='.$community_id,
                'icon' => $site_url . '/static/community/application/app_admin_switch.png'
            );
            $info[] = array
            (
                'title' => '应用排序',
                'url' => '/pages/group/groupDevelopmentSort/groupDevelopmentSort?community_id='.$community_id,
                'icon' => $site_url . '/static/community/application/app_admin_sort.png'
            );
        }

        $this->returnCode(0, $info);
    }
    /**
     * 群应用排序
     */
    public function application_change_sort(){
        if(!$_POST['community_id']){
            $this->returnCode('71000029');
        }
        $community_id = intval($_POST['community_id']);
        if(!$_POST['order_word']){
            $this->returnCode('72000052');
        }
        $order_word = $_POST['order_word'];
        $order_info = array(
            'order_vote', 'order_notice', 'order_activity', 'order_shop', 'order_dynamic', 'order_topic', 'order_album', 'order_file', 'order_card'
        );
        if (!in_array($order_word, $order_info)) {
            $this->returnCode('72000055');
        }
        if(!$_POST['order_num'] && $_POST['order_num'] != 0){
            $this->returnCode('72000053');
        }
        $order_num = intval($_POST['order_num']);
        // 暂时排序值只有 0 到 8
        if (!($order_num >= 0 && $order_num < 9)) {
            $this->returnCode('72000054');
        }
        $info = D('Community_application_development')->application_change_sort($community_id, $order_word, $order_num);
        $this->returnCode(0, $info);
    }


    /**
     *  群应用字段过滤
     * @param $word_array 过滤的字段
     * @param $now_word_array 原来的字段
     * @return array
     */
    private function filter_word($word_array, $now_word_array) {
        $word = array(
            'is_community_vote',
            'is_community_notice',
            'is_community_activity',
            'is_community_shop',
            'is_community_dynamic',
            'is_community_topic',
            'is_community_album',
            'is_community_file',
            'is_community_card',
            'is_add_folder',
            'is_add_album',
        );
        // 返回值初始化
        $return_word = array();
        foreach ($word_array as $key => $val) {
            if (in_array($key, $word) && intval($val) != $now_word_array[$key]) {
                if (in_array(intval($val), array(1,2))) {
                    $return_word[$key] = $val;
                } else {
                    $this->returnCode('72000017');
                }
            }
        }
        return $return_word;
    }



    // 群消息模块
    /**
     * 在聊天群里面发布图片消息
     */
    public function chat_img_info() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $community_id = intval($_POST['community_id']);
        // 名片背景图
        $image = array();
        if(!empty($_FILES) && $_FILES['file']['error'] != 4){
            $image = D('Image')->handle($uid, 'comm', 1, array('size' => 10), false, true);
            if ($image['error']) {
                $this->returnCode(1,array(), $image['message']);
            }
        }
        if ($image) {
            $url = C('config.site_url') . $image['url']['image'];
            $img_id = $image['pigcms_id'];
            $info = M('Image')->where(array('pigcms_id' => $img_id))->field('img_width', 'img_height')->find();
            $img_width = 0;
            $img_height = 0;
            if ($info) {
                $img_width = $info['img_width'] ? $info['img_width'] : 0;
                $img_height = $info['img_height'] ? $info['img_height'] : 0;
            }
            // 同步发消息到云通讯
            $group_id = $community_id;
            $msg_body = array();
            $msgType = array();
            $msgType['MsgType'] = 'TIMTextElem';
            $msgType['MsgContent'] = array(
                'Text' => '【￥image￥】&' . $img_id .  '&' . urlencode($url) . '&' . $img_width . '&' . $img_height
            );
            $msg_body[] = $msgType;
            $database_info = D('Community_info');
            $ret_group = $database_info->qcloud_send_group_msg($group_id, $msg_body, $uid);
            if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
                $this->returnCode(0, array(
                    'url' => $url,
                    'img_id' => $img_id
                ));
            } else {
                $this->returnCode(1, array(), '图片发送失败！请重试~');
            }
        } else {
            $this->returnCode(1, array(), '图片发送失败！请重试~');
        }
    }

    /**
     * 撤销自己发的消息,相当于删除此条消息
     */
    public function revoke_message() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $community_id = intval($_POST['community_id']);
        if (empty($community_id)) {
            $this->returnCode('70000004');
        }
        // 消息id
        $mid = intval($_POST['mid']);
        if (empty($mid)) {
            $this->returnCode('72000046');
        }
        // 用户身份
        $user = $_POST['user'];
        if (empty($user)) {
            $this->returnCode('72000047');
        }
        if ('user_' . $uid != $user) {
            $this->returnCode('72000048');
        }
        // 消息时间
//        $time = $_POST['time'];
//        if ($time) {
//            // 暂时不做时间限制，后期添加时间限制
//        }
        fdump($community_id . '<---------->' . $mid, 'qcloud_delete_group_msg_recall', true);
        $revoke =  D('Community_info')->qcloud_delete_group_msg_recall($community_id, $mid);
        if (!empty($revoke) && $revoke['ActionStatus'] == 'OK') {
            $this->returnCode(0, array(
                'mid' => $mid
            ));
        } else {
            $this->returnCode(1, array(), '删除消息失败！请重试~');
        }
    }

    /**
     * @人 的成员列表
     */
    public function reminding_members_list()
    {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000012');
        }
        $where = array();
        // 昵称模糊查询
        if ($_POST['comm_nickname']) {
            $where['comm_nickname'] = array('like', '%' . $_POST['comm_nickname'] . '%');
        }
        // 查询一下群信息
        $database_community_info = D('Community_info');
        $community_info = $database_community_info->where(array('community_id' => $_POST['community_id']))->find();
        if (empty($community_info)) {
            $this->returnCode('70000007');
        }
        if ($community_info['status'] == 3) {
            $this->returnCode('70000024');
        }
        // 分页处理

        $database_Community_join = D('Community_join');

        $where['community_id'] = $_POST['community_id'];
        $where['group_owner'] = 1;
        $where['add_status'] = 3;
        $where['add_uid'] = array('neq', $uid);
        $members = array();
        // 获取群成员信息
        $join_count = $database_Community_join->where(array('add_status' => 3, 'community_id' => $_POST['community_id']))->count();
        import('@.ORG.comm_page');
        $p = new Page($join_count, 15);
        $firstRow = $p->firstRow;
        $members['totalPage'] = $p->totalPage;
        // 获取分页首页信息 且 不是群主时候
        if ($firstRow == 0 && $community_info['group_owner_uid'] != $uid) {
            $listRows = 14;
            // 需要获取群主信息
            $get_owner = true;
        } else {
            $listRows = $p->listRows;
            $get_owner = false;
        }
        $group_members_info = $database_Community_join->where($where)->limit($firstRow . ',' . $listRows)->select();

        $database_User = D('User');
        $site_url = C('config.site_url');
        if (!empty($group_members_info)) {
            $members['list'] = array();
            foreach ($group_members_info as $val) {
                $add_user = $database_User->field('nickname, phone, avatar')->where(array('uid' => $val['add_uid']))->find();
                if (!empty($add_user)) {
                    if ($add_user['nickname']) $val['nickname'] = $add_user['nickname'];
                    if ($add_user['phone']) $val['phone'] = substr_replace($add_user['phone'], '****', 3, 4);
                    if (empty($add_user['avatar'])) {
                        // 没有头像取默认头像
                        $val['avatar'] = $site_url . '/static/avatar.jpg';
                    } else {
                        $val['avatar'] = $add_user['avatar'];
                    }
                } else {
                    // 没有头像取默认头像
                    $val['avatar'] = $site_url . '/static/avatar.jpg';
                }
                $members['list'][] = $val;
            }
        } else {
            $members['list'] = array();
        }
        // 获取者不是群主 且需要获取群主信息的时候获取
        if ($get_owner && $community_info['group_owner_uid'] != $uid) {
            $where['group_owner'] = 2;
            $group_members_owner = $database_Community_join->where($where)->find();
            if (!empty($group_members_owner)) {
                $owner_user = $database_User->field(true)->where(array('uid' => $group_members_owner['add_uid']))->find();
                if ($owner_user['nickname']) $group_members_owner['nickname'] = $owner_user['nickname'];
                if ($owner_user['phone']) $group_members_owner['phone'] = substr_replace($owner_user['phone'], '****', 3, 4);
                if (empty($owner_user['avatar'])) {
                    // 没有头像取默认头像
                    $group_members_owner['avatar'] = $site_url . '/static/avatar.jpg';
                } else {
                    $group_members_owner['avatar'] = $owner_user['avatar'];
                }
                $group_members_owner['is_owner'] = true;
                array_unshift($members['list'], $group_members_owner);
            }
        }
        // 判断是否为群主
        if ($community_info['group_owner_uid'] == $uid) {
            $members['is_owner'] = true;
        } else {
            $members['is_owner'] = false;
        }
        $members['community_info'] = array();
        if ($community_info['community_avatar']) {
            $members['community_info']['avatar'] = $site_url . '/upload/comm/' . $community_info['community_avatar'];
        } elseif ($community_info['avatar']){
            $members['community_info']['avatar'] = $site_url . $community_info['avatar'];
        } else {
            $members['community_info']['avatar'] = '';
        }

        $this->returnCode(0, $members);
    }


    /**
     * 消息@人，处理为发送推送消息给对应的人  可单人，可多人，可全部（除自己以外）@人提醒--即推送消息
     * comm_target   all_member 全体  members 对应群成员
     * message    具体的消息信息
     */
    public function reminding_others() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000004');
        }
        $community_id = intval($_POST['community_id']);
        // 首先查询群信息
        $community_info = M('Community_info')->where(array('community_id' => $community_id))->field('group_owner_uid')->find();
        if (empty($community_info)) {
            $this->returnCode('70000005');
        }
        if ($community_info['group_owner_uid'] != $uid) {
            $this->returnCode(0, array('community_id' => $community_id));
        }
        //  @人目标 分 对应人和全体
        if (empty($_POST['comm_target']) || !in_array($_POST['comm_target'], array('all_member', 'members'))) {
            $this->returnCode('72000062');
        }
        $target = $_POST['comm_target'];
        $join_id_arr = array();
        //  如果@人目标为对应人 即存在对应成员join_id数组，单人或者多人
        if (!empty($target) && $target == 'members') {
            if (empty($_POST['join_id_arr'])) {
                $this->returnCode('72000063');
            }
            $join_id_arr = $_POST['join_id_arr'];
        }
        // 具体的消息信息
        if (empty($_POST['message'])) {
            $this->returnCode('72000064');
        }
        $message = $_POST['message'];
        $reminding_others_info = D('Community_join')->create_comm_plan($community_id, 'comm_reminding_others', $join_id_arr, 0, $uid, $message);
        fdump($reminding_others_info, 'reminding_others_info', true);
        $this->returnCode(0, array('community_id' => $community_id));

    }


    // 邮件发送区域

    // 群名片发送邮件
    public function card_mail()
    {
           
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_card = D('Community_card');
            $database_mail = D('Community_mail');
            //群ID
            if (empty($_POST['community_id'])) {
                $this->returnCode('70000004');
            }
            //邮箱
            if (empty($_POST['mail'])) {
                $this->returnCode('71000065');
            }
            //查询名片
            $card = $database_card->field('excel_url, card_name')->where(array('community_id'=>$_POST['community_id']))->find();
            $community_name = D('Community_info')->field('community_name')->where(array('community_id'=>$_POST['community_id']))->find();
            if (!$card) {
                $this->returnCode('71000030');
            }
            $excel_info = array();
            if ($card['excel_url']) {
                $excel_info['excel_url'] = C('config.site_url') . $card['excel_url'];
            } else {
                $this->returnCode('72000049');
            }
            $where = array(
                'community_id' => $_POST['community_id'],
                'join_status' => 1,
            );
            $join_count = D('Community_card_bind')->where($where)->count();
            $excel_url = $excel_info['excel_url'];
            $content = '<h2>【'.$community_name['community_name'].'】群名片【'.$card['card_name'].'】（共'.$join_count.'人）</h2><p><strong>群名片名单：</strong></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=' . $excel_url. '>'.$excel_url.'</a></p><p style="color:red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;点击链接下载群名片Excel表格汇总</p>';
            $res = $database_mail->send($_POST['mail'],'群名片数据-小猪社群！',$content);
            
            if ($res === true ) {
                $this->returnCode(0, $res);
            } else {
                $this->returnCode(1, array(),'发送失败，请重试！');
            }
        }
    }

    // 群文件发送邮件
    public function file_mail()
    {
        if (!empty($_POST)) {
            // 处理添加的数据
            $database_file = D('Community_file');
            $database_mail = D('Community_mail');
            //文件ID
            if (empty($_POST['file_id'])) {
                $this->returnCode('70000004');
            }
            //邮箱
            if (empty($_POST['mail'])) {
                $this->returnCode('71000065');
            }
            //查询文件
            $file = $database_file->field('community_id,file_url,file_name')->where(array('file_id'=>$_POST['file_id']))->find();
            $community_name = D('Community_info')->field('community_name')->where(array('community_id'=>$file['community_id']))->find();
            if (!$file) {
                $this->returnCode('71000030');
            }
            $excel_info = array();
            if ($file['file_url']) {
                $excel_info['file_url'] = C('config.site_url') . $file['file_url'];
            } else {
                $this->returnCode('72000009');
            }
            $file_url = $excel_info['file_url'];
            $content = '<h2>【'.$community_name['community_name'].'】群文件【'.$file['file_name'].'】</h2><p><strong>群文件：</strong></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$file_url.'">'.$file_url.'</a></p><p style="color:red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;点击链接下载群文件Excel表格汇总</p>';
            $res = $database_mail->send($_POST['mail'],'群文件数据-小猪社群！',$content);
            if ($res === true ) {
                $this->returnCode(0, $res);
            } else {
                $this->returnCode(1, array(),'发送失败，请重试！');
            }
        }
    }

    /**
     * [card_praise 单人名片点赞]
     * @return [type] [description]
     */
    public function card_praise(){
        $data['card_praise_user_id'] = $this->_uid;
        $data['join_id'] = intval($_POST['join_id']);
        if(!$data['join_id']){
            $this->returnCode('缺少群名片成员ID参数!');
        }
        $data['community_id'] = $_POST['community_id'];
        if(!$data['community_id']){
            $this->returnCode('缺少群ID参数!');
        }
        $card_praise_model = D('Community_card_praise');
        $res = $card_praise_model->where($data)->find();
        if($res){
            $result = $card_praise_model->where($data)->delete();
            if($result != false){
                $this->returnCode(0,'点赞已取消!');
            }
        }else{
            $result = $card_praise_model->add($data);
            if($result != false){
                $this->returnCode(0,'点赞成功!');
            }else{
                $this->returnCode(1,'点赞失败!');
            }
        }
    }

    // 群成员
    public function group_members_list()
    {
        // 判断用户是否登录
        if (empty($this->_uid)) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (empty($_POST['community_id'])) {
            $this->returnCode('70000012');
        }
        $where = array();
        // 昵称模糊查询  上传的字段存在nickname  就赋予条件
        if (array_key_exists('nickname', $_POST)) {
            $where['comm_nickname'] = array('like', '%' . $_POST['nickname'] . '%');
        }
        // 查询一下群信息
        $database_community_info = D('Community_info');
        $community_info = $database_community_info->where(array('community_id' => $_POST['community_id']))->find();
        if (empty($community_info)) {
            $this->returnCode('70000007');
        }
        if ($community_info['status'] == 3) {
            $this->returnCode('70000024');
        }
        // 分页处理

        $database_Community_join = D('Community_join');
        $m_community_card = M('Community_card');
        $d_community_card_bind = M('Community_card_bind');
        $d_community_card_praise = D('Community_card_praise');
        $where['community_id'] = $_POST['community_id'];
        $where['group_owner'] = 1;
        $where['add_status'] = 3;
        $members = array();
        $member_number = $database_Community_join->where(array('add_status' => 3, 'community_id' => $_POST['community_id']))->count();
        $join_count = $database_Community_join->where(array('add_status' => 3, 'community_id' => $_POST['community_id']))->count();
        import('@.ORG.comm_page');
        $p = new Page($join_count, 15);
        $firstRow = $p->firstRow;
        $members['totalPage'] = $p->totalPage;
        $listRows = $p->listRows;
        $get_owner = true;
        if (array_key_exists('comm_nickname', $where)) {
            unset($where['group_owner']);
            $get_owner = false;
        }
        $group_members_info = $database_Community_join->where($where)->limit($firstRow . ',' . $listRows)->select();

        $database_User = D('User');
        if (!empty($group_members_info)) {
            $members['list'] = array();
            foreach ($group_members_info as $val) {
                $add_user = $database_User->field('nickname, phone, avatar')->where(array('uid' => $val['add_uid']))->find();
                if (!empty($add_user)) {
                    if ($add_user['nickname']) $val['nickname'] = $add_user['nickname'];
                    if ($add_user['phone']) $val['phone'] = substr_replace($add_user['phone'], '****', 3, 4);
                    if (empty($add_user['avatar'])) {
                        // 没有头像取默认头像
                        $val['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                    } else {
                        $val['avatar'] = $add_user['avatar'];
                    }
                } else {
                    // 没有头像取默认头像
                    $val['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                }
                $val['is_owner'] = false;
                if ($val['add_uid'] == $uid) {
                    $val['is_user'] = true;
                } else {
                    $val['is_user'] = false;
                }
                if($val['add_uid'] && $val['community_id']){
                    $is_card = $d_community_card_bind
                            ->where(array('join_uid'=>$val['add_uid'],'community_id'=>$val['community_id'],'join_status'=>1))
                            ->find();
                    if($is_card){
                        $val['card_praise'] = $d_community_card_praise->where(array('community_id'=>$_POST['community_id'],'join_id'=>$is_card['join_id']))->count();
                        $val['browse_number'] = $is_card['browse_number'];
                        $val['is_join'] = true;
                        $val['join_id'] = $is_card['join_id'];
                    }else{
                        $val['card_praise'] = '';
                        $val['browse_number'] = '';
                        $val['is_join'] = false;
                        $val['join_id'] = '';
                    }
                }   

                $members['list'][] = $val;
            }
        } else {
            $members['list'] = array();
        }
        if ($get_owner) {
            unset($where['comm_nickname']);
            $where['group_owner'] = 2;
            $group_members_owner = $database_Community_join->where($where)->find();
            if (!empty($group_members_owner)) {
                $owner_user = $database_User->field(true)->where(array('uid' => $group_members_owner['add_uid']))->find();
                if ($owner_user['nickname']) $group_members_owner['nickname'] = $owner_user['nickname'];
                if ($owner_user['phone']) $group_members_owner['phone'] = substr_replace($owner_user['phone'], '****', 3, 4);
                if (empty($owner_user['avatar'])) {
                    // 没有头像取默认头像
                    $group_members_owner['avatar'] = C('config.site_url') . '/static/avatar.jpg';
                } else {
                    $group_members_owner['avatar'] = $owner_user['avatar'];
                }
                $group_members_owner['is_owner'] = true;
                if ($group_members_owner['add_uid'] == $uid) {
                    $group_members_owner['is_user'] = true;
                } else {
                    $group_members_owner['is_user'] = false;
                }
                if($group_members_owner['add_uid'] && $group_members_owner['community_id']){
                    $owner_card = $d_community_card_bind
                            ->where(array('join_uid'=>$group_members_owner['add_uid'],'community_id'=>$group_members_owner['community_id'],'join_status'=>1))
                            ->find();
                    $group_members_owner['card_praise'] = $d_community_card_praise->where(array('community_id'=>$_POST['community_id'],'join_id'=>$owner_card['join_id']))->count();
                    if($owner_card){
                        $group_members_owner['browse_number'] = $owner_card['browse_number'];
                        $group_members_owner['is_join'] = true;
                        $group_members_owner['join_id'] = $owner_card['join_id'];
                    }else{
                        $group_members_owner['browse_number'] = '';
                        $group_members_owner['is_join'] = false;
                        $group_members_owner['join_id'] = '';
                    }
                }
            }
        }
        
        // 判断是否为群主
        if ($community_info['group_owner_uid'] == $uid) {
            $members['is_owner'] = true;
        } else {
            $members['is_owner'] = false;
        }
        if ($community_info['member_number'] != $member_number) {
            // 如果统计数据出错，重写改写一下
            $community_info = $database_community_info->where(array('community_id' => $_POST['community_id']))->data(array('member_number' => $member_number))->save();
        }
        $group_members_myself = $database_Community_join->where(array('community_id' => $_POST['community_id'], 'add_uid' => $uid))->find();
        $members['community_name'] = $community_info['community_name'];
        $members['comm_nickname'] = $group_members_myself['comm_nickname'];
        $members['community_id'] = $_POST['community_id'];
        $members['is_nickname'] = ($community_info['is_nickname'] == 2) ? true : false;

        //群名片的基础信息
        $card_single = $m_community_card->where(array('community_id'=>$_POST['community_id']))->find();

        // 查询一下当前用户是否加入群
        $join_comm = $database_Community_join->where(array('add_uid' => $uid, 'community_id'=>$_POST['community_id']))->field('add_status')->find();

        // 查询一下当前用户是否加入群名片
        $where_join = array(
            'join_status' => 1,
            'community_id' => $_POST['community_id'],
            'community_card_id' => $card_single['community_card_id'],
            'join_uid' => $uid
        );
        $join = $d_community_card_bind->where($where_join)->field('join_uid')->find();
        $members['is_join'] = false;
        if ($join) {
            if ($join_comm['add_status'] && $join_comm['add_status'] == 3) {
                $members['is_join'] = true;
            } else {
                $members['is_join'] = false;
            }
        }
        $where_num = array(
            'join_status' => 1,
            'community_id' => $_POST['community_id'],
            'community_card_id' => $card_single['community_card_id']
        );
        $community_card_id = $m_community_card->where($where_num)->setInc('browse_num');
        
        // 获取群主的头像和昵称
        $user = M('User')
                ->field('nickname, avatar')
                ->where(array('uid' => $community_info['group_owner_uid']))
                ->find();
        $user_message = M('Community_card_bind')
                        ->field('join_id,browse_number')
                        ->where(array('join_uid'=>$community_info['group_owner_uid'],'community_id'=>$_POST['community_id']  ))
                        ->find();   
        $user['join_id'] =  $user_message['join_id'];              
        $user['browse_number'] =  $user_message['browse_number'];              
        if ($card_single['community_card_bgimg']) {
            $card_single['community_card_bgimg'] = C('config.site_url') . $card_single['community_card_bgimg'];
        }
        $user['card_praise'] = $d_community_card_praise->where(array('community_id'=>$_POST['community_id'],'join_id'=>$user['join_id']))->count();
        $members['community_card'] = $card_single;
        $members['owner'] = $user;
        $this->returnCode(0, $members);
    }


    // pc端上传功能
    // 收集测试小程序跳转信息
    public function small_program_activity() {
        fdump('收集测试小程序跳转信息   ' . __LINE__, 'small_program_jump', true);
        fdump('$_POST   ' . __LINE__, 'small_program_jump', true);
        fdump($_POST , 'small_program_jump', true);
        fdump('$_GET   ' . __LINE__, 'small_program_jump', true);
        fdump($_GET , 'small_program_jump', true);
        fdump('$_FILES   ' . __LINE__, 'small_program_jump', true);
        fdump($_FILES , 'small_program_jump', true);
        fdump('$_SERVER   ' . __LINE__, 'small_program_jump', true);
        fdump($_SERVER , 'small_program_jump', true);
        fdump('$_SESSION   ' . __LINE__, 'small_program_jump', true);
        fdump($_SESSION , 'small_program_jump', true);
    }

    // 小程序端接口确认登录pc端上传页面
    public function pc_confirm_login() {
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $uid = $this->_uid;
        if (!$_POST['file_ticket']) {
            $this->returnCode('72000059');
        }
        $file_ticket = $_POST['file_ticket'];
        if (!intval($_POST['id'])) {
            $this->returnCode('72000059');
        }
        $id = intval($_POST['id']);
        $m_community_file_qrcode = M('Community_file_qrcode');
        $where = array(
            'ticket' => $file_ticket,
            'id' => $id
        );
        // 二维码过期时间
        // 获取配置有效时间（单位： 秒）
        $community_code_effective_time = C('config.community_code_effective_time');
        if ($community_code_effective_time && intval($community_code_effective_time) > 0) {
            $effective_time = intval($community_code_effective_time);
        } else {
            $effective_time = 1800; // 取默认的半小时
        }
        $msg = $m_community_file_qrcode->where($where)->find();
        if ($msg['add_time'] && intval($_SERVER['REQUEST_TIME']) - intval($msg['add_time']) > $effective_time) {
            $m_community_file_qrcode->where(array('add_time'=>array('lt',($_SERVER['REQUEST_TIME']-$effective_time))))->delete();
            $this->returnCode('72000060');
        }
        if ($msg['uid']) {
            $this->returnCode(0, array('uid' => $msg['uid']));
        }
        $qrcode_id = $m_community_file_qrcode->where($where)->data(array('uid' => $uid))->save();
        if(empty($qrcode_id)){
            $this->returnCode('72000061');
        }
        $this->returnCode(0, array('uid' => ``));
    }

    // 小程序端获取pc端上传地址
    public function pc_url() {
        $url = C('config.site_url') . '/index.php?c=CommFilePc';
        $this->returnCode(0, array('url' => $url));
    }

    //群首页
    public function community_index(){
        $join_community_id = D('Community_join')->field('community_id')->where(array('add_uid'=>$this->_uid,'add_status'=>3))->select();
        foreach ($join_community_id as $key => $value) {
            $ids[] = $value['community_id'];
        }
        $where['a.community_id'] = array('in',$ids);
        $where['a.is_del'] = 0;
        $pageSize = 10;
        $page= $_POST['page'] ? $_POST['page'] : 1;
        $dynamic_model = D('Community_dynamic');
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total =$dynamic_model->alias('a')->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);

            $Distinct = 'false';
            $field='a.*,b.avatar,b.nickname,c.community_name';
            $join='join pigcms_user as b on a.user_id=b.uid join pigcms_community_info as c on a.community_id=c.community_id';
            $order = 'a.addtime desc';
            $limit = $firstRow.','.$pageSize;
            $list = $dynamic_model->dynamic_select($Distinct,$field,$join,$where,$order,$limit);

            $reply_model = D('Community_reply');
            $user_model = D('User');
            $user_field = 'nickname';
            $praise_model = D('Community_praise');
            $community_card = D('Community_card_bind');
            $community_join = D('Community_join');
            foreach($list as $key=>$value){
                $maple['dynamic_id'] = $value['id'];
                $maple['reply_is_del'] = 0;
                $dynamic_reply = $reply_model->reply_select('','','',$maple,'','');
                foreach($dynamic_reply as $kk=>$vv){
                    $user_where1= array('uid'=>$dynamic_reply[$kk]['reply_user_id']);
                    $user_where2= array('uid'=>$dynamic_reply[$kk]['reply_to_user_id']);
                    $user = $user_model->user_find($user_field,$user_where1);
                    $to_user = $user_model->user_find($user_field,$user_where2);
                    if($this->_uid != $vv['reply_user_id']){
                        $dynamic_reply[$kk]['can_reply'] = true;
                    }else{
                        $dynamic_reply[$kk]['can_reply'] = false;
                    }
                    $dynamic_reply[$kk]['user'] = $user['nickname'];
                    $dynamic_reply[$kk]['to_user'] = $to_user['nickname'];
                }
                if($value['img']){
                    $list[$key]['img'] = unserialize($value['img']);
                    $dynamic_img = $list[$key]['img'];
                    $list[$key]['img'] = array();
                    foreach($dynamic_img as $k=>$v){
                        $list[$key]['img'][] = C('config.site_url') .$v;
                    }
                }
                if($value['file']){
                    $list[$key]['file'] = unserialize($value['file']);
                    $dynamic_file = $list[$key]['file'];
                    $list[$key]['file'] = array();
                    $list[$key]['file']['file_url'] = C('config.site_url') .$dynamic_file['file_url'];
                    $list[$key]['file']['file_remark'] = $dynamic_file['file_remark'];
                    $list[$key]['file']['file_id'] = $dynamic_file['file_id'];
                    $list[$key]['file']['file_img'] = $dynamic_file['file_img'];
                    $list[$key]['file']['is_img'] = $dynamic_file['is_img'];
                }
                if($value['application_detail']){
                    $list[$key]['application_detail'] = unserialize($value['application_detail']);
                }
                $praise_field = 'b.nickname';
                $praise_join = 'join pigcms_user as b on a.praise_user_id=b.uid';
                $praise_where = array('dynamic_id'=>$value['id']);          
                $praise_dynamic = $praise_model->praise_select('true',$praise_field,$praise_join,$praise_where,'','');//动态的点赞
                
                $arr = D('Community_dynamic_topic_bind')->field('topic_id')->where(array('dynamic_id'=>$value['id']))->select();
                $array=array();
                foreach ($arr as $kkk => $vvv) {
                    $array[] =  $vvv['topic_id']; 
                }
                
                $map['topic_id'] = array('in',$array);
                $topic = D('Community_topic')->field('topic_id,topic_title')->where($map)->select();
                $list[$key]['addtime'] = time_info($list[$key]['addtime']);
                $list[$key]['reply'] = $dynamic_reply;
                $list[$key]['praise'] = $praise_dynamic;
                $list[$key]['topic_list'] = $topic;

                //是否可以为用户本人
                $dynamic_user_id = $dynamic_model->dynamic_find('','user_id','',array('id'=>$list[$key]['id']));
                $dynamic_praise = $praise_model->praise_find('','','',array('dynamic_id'=>$value['id'],'praise_user_id'=>$this->_uid));
                if(!$dynamic_praise){
                    $list[$key]['can_praise'] = true;
                }else{
                    $list[$key]['can_praise'] = false;
                }
                $group_owner_uid = D('Community_info')->where(array('community_id'=>$value['community_id']))->getField('group_owner_uid');
                //是否为群主或该动态的发布者,用有删除的去权限
                if($this->_uid == $group_owner_uid || $this->_uid == $dynamic_user_id['user_id']){
                    $list[$key]['can_del'] = true;
                }else{
                    $list[$key]['can_del'] = false;
                }
                $is_join = $community_card->where(array('join_uid'=>$value['user_id'],'community_id'=>$value['community_id'],'join_status'=>1))->find();
                $add_id = $community_join->where(array('community_id'=>$value['community_id'],'add_uid'=>$value['user_id'],'add_status'=>3,'del_info'=>1))->getField('add_id');
                if($is_join){
                    $list[$key]['is_join'] = true;
                    $list[$key]['add_id'] = $add_id;
                    $list[$key]['join_id'] = $is_join['join_id'];
                }else{
                    $list[$key]['is_join'] = false;
                    $list[$key]['add_id'] = $add_id;
                    $list[$key]['join_id'] = $is_join['join_id'];
                }
            }
            $info_list = array(
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            );
        }
        $this->returnCode(0,$info_list);
    }

    /**
     * 测试接口
     */
    public function test_wanzy() {
        // 判断oss 功能是否开启
        if ($this->config['oss_switch'] == 1) {
            // 开启了oss 文件上传至oss

            // 运行程序所使用的存储空间
            $oss_bucket = $this->config['oss_bucket'];
            // OSS的AccessKeyId
            $oss_access_id = $this->config['oss_access_id'];
            // OSS的AccessKeySecret
            $oss_access_key = $this->config['oss_access_key'];
            // OSS数据中心的访问域名
            $oss_endpoint = 'http://' . $this->config['oss_endpoint'];
            // Oss 访问域名
            $oss_access_domain_names = $this->config['oss_access_domain_names'];

            import('@.ORG.Oss_client');
            $ossClient = new Oss_client($oss_access_id, $oss_access_key, $oss_endpoint, $oss_bucket);

            $file = $_SERVER['DOCUMENT_ROOT'] . '/static/community/msg/activity.png';
			dump($ossClient);
			$result = $ossClient->api->uploadFile($oss_bucket, $file, __FILE__);
			dump($result);
//            fdump('$result ---' . __LINE__, 'oss_info', true);
//            fdump($result, 'oss_info', true);

        }
    }

}
?>