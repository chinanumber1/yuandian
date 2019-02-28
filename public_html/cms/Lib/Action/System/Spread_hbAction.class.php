<?php

/**
 * 推广海报
 * Class Spread_hbAction
 */
class Spread_hbAction extends BaseAction
{

    function index(){
        $store_promote_setting = D('Store_promote_setting');
        $name = !empty($_POST['keyword']) ? $_POST['keyword'] : '';
        $where = array();
        if($name){
            $where['name'] = array('like','%'.$name.'%');
        }
        $where['status'] = 1;
        $where['owner'] = 2;
        $promote_list = $store_promote_setting->where($where)->order('pigcms_id DESC')->select();

        $this->assign('promote_list', $promote_list);
        $this->assign('name', $name);
        $this->display();
    }

    //上传文件
    public function uploadFile()
    {
       // import('upload', './source/class/');
        //import('Image', './source/class/');
        import('@.ORG.upload');
        import('@.ORG.Image');
        if(empty($_FILES['file']))
            $this->ajaxReturn(array('info'=>'上传文件不存在','status'=>0));
        $upload = new Upload();

        $upload->maxSize = 2*1024*1024;
        $upload->autoSub=true;
        $uploadPath='./upload/images/000/000/001/';// 设置附件上传目录
        $upload->savePath = $uploadPath;
        if(!file_exists($uploadPath))
        {
            mkdir($uploadPath,0777,true);
        }
        $upload->allowExts=array('jpg','jpeg','png','gif');
        if(!$upload->upload())
        {
            $this->ajaxReturn(array('status'=>0,'info'=>$upload->getErrorMsg()));
        }
        $info=$upload->getUploadFileInfo();
        $image = new Image();
        $imgPath=$info[0]['savepath'].$info[0]['savename'];
        $dirName=dirname($imgPath);
        $imgName=basename($imgPath);
        $sizeArr=array(array(400,256),array(320,500),array(320,320));
        $pathData=array('path'=>$this->siteUrl.ltrim($info[0]['savepath'],'.').$info[0]['savename']);
        $targetWidth=900;
        for($i=0;$i<count($sizeArr);$i++)
        {
            if(!file_exists($dirName."/{$sizeArr[$i][0]}_{$sizeArr[$i][1]}"))
                mkdir($dirName."/{$sizeArr[$i][0]}_{$sizeArr[$i][1]}",0777,true);
            $image->thumb2($imgPath,$dirName."/{$sizeArr[$i][0]}_{$sizeArr[$i][1]}/".$imgName,'',$sizeArr[$i][0],$sizeArr[$i][1]);
            $pathData["{$sizeArr[$i][0]}_{$sizeArr[$i][1]}"]=$this->siteUrl.ltrim($dirName,'.')."/{$sizeArr[$i][0]}_{$sizeArr[$i][1]}/".$imgName;
            //扩大到900
            $scale=$targetWidth/$sizeArr[$i][0];
            $scaleWidth=round($sizeArr[$i][0]*$scale);
            $scaleHeight=round($sizeArr[$i][1]*$scale);
            if(!file_exists($dirName."/{$scaleWidth}_{$scaleHeight}"))
                mkdir($dirName."/{$scaleWidth}_{$scaleHeight}",0777,true);
            $image->thumb2($imgPath,$dirName."/{$scaleWidth}_{$scaleHeight}/".$imgName,'',$scaleWidth,$scaleHeight);
        }

        //var_dump($pathData);
        $this->ajaxReturn(array('status'=>1,'info'=>'上传成功','data'=>$pathData));
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     */
    protected function ajaxReturn($data,$type='') {
        if(func_num_args()>2) {// 兼容3.0之前用法
            // $args           =   func_get_args();
            array_shift($args);
            $info           =   array();
            $info['data']   =   $data;
            $info['info']   =   array_shift($args);
            $info['status'] =   array_shift($args);
            $data           =   $info;
            // $type           =   $args?array_shift($args):'';
        }

        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:text/html; charset=utf-8');
        exit(json_encode($data));
        /*}elseif(strtoupper($type)=='XML'){
            // 返回xml格式数据
            header('Content-Type:text/xml; charset=utf-8');
            exit(xml_encode($data));
        }elseif(strtoupper($type)=='EVAL'){
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            exit($data);
        }else{
            // TODO 增加其它格式
        }*/
    }

    /**
     * 添加海报
     */
    public function add(){
        if (IS_POST) {
            $pigcms_id = !empty($_POST['pigcms_id']) ? $_POST['pigcms_id'] : '';
            $result = D('Store_promote_setting')->where(array('pigcms_id'=>$pigcms_id))->find();
            $data['name'] = !empty($_POST['name']) ? $_POST['name'] : '';
            $data['store_nickname'] = !empty($_POST['store_name']) ? $_POST['store_name'] : '';
            $data['store_id'] = 0;
            $data['start_time'] = !empty($_POST['start_time']) ? strtotime($_POST['start_time']) : '';
            $data['end_time'] = !empty($_POST['end_time']) ? strtotime($_POST['end_time']) : '';
            $data['descr'] = !empty($_POST['descr']) ? $_POST['descr'] : '';
            $data['banner_config'] = !empty($_POST['banner_config']) ? $_POST['banner_config'] : '';
            $data['status'] = 1;
            $data['poster_type'] = !empty($_POST['poster_type']) ? $_POST['poster_type'] : '';
            $data['owner'] = 2;
            $data['title'] = !empty($_POST['title']) ? $_POST['title'] : '';

            if(!D('Store_promote_setting')->where(array('type'=>1,'status'=>1,'owner'=>2))->find()){
                $data['type'] = 1;
            }
            if($pigcms_id && $result){
                $data['update_time'] = time();
                $result = D('Store_promote_setting')->where(array('pigcms_id'=>$pigcms_id))->save($data);

            } else {
                $data['create_time'] = time();
                $data['update_time'] = time();
                $result = D('Store_promote_setting')->add($data);
            }
            if($result){
                exit(json_encode(array('error' => 0, 'message' => '保存成功')));
            }
        }
        $this->display();
    }


    /**
     * 编辑海报
     */
    public function edit()
    {
        $id = $_GET['pigcms_id'];
        $promote = D('Store_promote_setting')->where(array('pigcms_id'=>$id,'owner'=>2))->find();
        $this->assign('promote' ,$promote);
        $this->display('add');
    }

    /**
     * 删除
     */
    public function detach_promote(){

        $pigcms_id = !empty($_POST['pigcms_id']) ? $_POST['pigcms_id'] : '';
        $result = D('Store_promote_setting')->where(array('pigcms_id'=>$pigcms_id,'owner'=>2))->data(array('status'=>2))->save();
        if($result){
            exit(json_encode(array('error' => 0, 'message' => '删除成功')));
        } else {
            exit(json_encode(array('error' => 1001, 'message' => '删除失败')));
        }
    }

    /**
     * 关闭 开启
     */
    public function enable(){
        $pigcms_id = !empty($_POST['pigcms_id']) ? $_POST['pigcms_id'] : '';
        $type = !empty($_POST['type']) ? $_POST['type'] : '';
        if($type == 1){
           // $enable = D('Store_promote_setting')->where(array('type'=>1,'owner'=>2))->find();
            //if($enable){
               // D('Store_promote_setting')->where(array('type'=>1,'owner'=>2))->data(array('type'=>0))->save();
                $result = D('Store_promote_setting')->where(array('pigcms_id'=>$pigcms_id,'owner'=>2))->data(array('type'=>1))->save();
           // } else {
                $result = D('Store_promote_setting')->where(array('pigcms_id'=>$pigcms_id,'owner'=>2))->data(array('type'=>1))->save();
           // }
            if(!$result){
                exit(json_encode(array('error' => 0, 'message' => '启用成功')));
            } else {
                exit(json_encode(array('error' => 1001, 'message' => '启用失败')));
            }
        } elseif (empty($type)){
            $result = D('Store_promote_setting')->where(array('pigcms_id'=>$pigcms_id,'owner'=>2))->data(array('type'=>0))->save();
            if($result){
                exit(json_encode(array('error' => 0, 'message' => '关闭成功')));
            } else {
                exit(json_encode(array('error' => 1001, 'message' => '关闭失败')));
            }
        }

    }


    //绑定区域代理微信openid
    public function agent_to_wchat(){

        $qrcode_id = 1200000000 + $this->admin_user['id'];
        $qrcode = D('Recognition')->get_platform_agent_binging($qrcode_id);
        //当前用户信息
        $userInfo = D('Admin')->where(array('id'=>$this->admin_user['id']))->find();
        if ($this->admin_user['type'] == 2 || $this->admin_user['type'] == 3) {

        } else {
            $this->frame_error_tips("仅允许客户经理(代理商)和区域管理员访问");
        }
        $this->assign('qrcode',$qrcode);
        $this->assign('userInfo',$userInfo);
        $this->display();
    }

    //解除绑定
    public function detach($id)
    {
        if(IS_POST && $id){
            $result = D('Admin')->where(array('id'=>$id))->data(array('open_id'=>''))->save();
            if($result){
                exit(json_encode(array('error' => 0, 'message' => '解绑成功')));
            }else{
                exit(json_encode(array('error' => 1001, 'message' => '解绑失败')));
            }
        }
    }

}