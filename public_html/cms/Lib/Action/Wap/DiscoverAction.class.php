<?php
/**
 * Created by PhpStorm.
 * User: wanzy
 * Date: 2018/6/25
 * Time: 13:37
 *
 * 发现-发布信息
 */

class DiscoverAction extends BaseAction {
    private $uid = 0;

    public function __construct() {
        parent::__construct();
        // 分类-查询状态正常的分类
        $wap_discover_category = M('Discover_category')->where(array('type_status' => 1))->field(true)->select();
        $this->assign('discover_category', $wap_discover_category);
        $uid = !empty($this->user_session) ? $this->user_session['uid'] : 0;
        $this->uid = $uid > 0 ? $uid : 0;
        $this->assign('uid', $uid);
        $site_url = C('config.site_url');
        $this->assign('site_url', $site_url);
    }

    /**
     * 获取发现-信息
     *
     */
    public function discover_index() {
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        // 默认分页、每页取8条信息
        $listRows = 5;
        $page = empty($_POST['page']) ? 1 : intval($_POST['page']);
        // 只获取正常状态下的信息
        $where = '`dm`.`discover_status` = 1';
        if (!empty($_POST['type_id'])) {
            $where .= 'AND `dm`.`type_id` =' . $_POST['type_id'];
        }
        $discover_msg_list = D('Discover_msg')->discover_msg_list($where, $listRows, $page);
        if ($discover_msg_list['list']) {
            // 查询收藏状态
            $discover_collection = D('Discover_collection');
            // 查询点赞状态
            $likes_collection = D('Discover_likes');
            foreach ($discover_msg_list['list'] as &$val) {
                $collect_info = $discover_collection->discover_collection_msg($val['discover_id'], $this->uid);
                if ($collect_info) {
                    $val['collection'] = true;
                } else {
                    $val['collection'] = false;
                }
                $likes_info = $likes_collection->where(array('discover_id' => $val['discover_id'], 'likes_uid' => $this->uid))->field('likes_id')->find();
                if ($likes_info) {
                    $val['likes'] = true;
                } else {
                    $val['likes'] = false;
                }
                // 处理图片
                $val['discover_img'] = !empty($val['discover_img']) ? unserialize($val['discover_img']) : false;
                foreach($val['discover_img'] as &$v){
                    if (strpos($v,$this->config['site_url']) == false) {
                        $v=$this->config['site_url'].$v;
                    }
                }
                // 过滤处理
                $discover_content = unserialize($val['discover_content']);
                if ($discover_content) {
                    $val['discover_content'] = $discover_content;
                }
            }
        }
        $this->assign('discover_msg_list', $discover_msg_list);
        $this->assign('default_avatar', $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png');
        $this->display();
    }


    /**
     * 获取发现-信息ajax请求
     *
     */
    public function discover_index_json() {
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        // 默认分页、每页取8条信息
        $listRows = 5;
        $page = empty($_POST['page']) ? 1 : intval($_POST['page']);
        // 只获取正常状态下的信息
        $where = '`dm`.`discover_status` = 1';
        if (!empty($_GET['type_id'])) {
            $where .= 'AND `dm`.`type_id` =' . $_GET['type_id'];
        }
        $discover_msg_list = D('Discover_msg')->discover_msg_list($where, $listRows, $page);
        if ($discover_msg_list['list']) {
            // 查询收藏状态
            $discover_collection = D('Discover_collection');
            // 查询点赞状态
            $likes_collection = D('Discover_likes');
            foreach ($discover_msg_list['list'] as &$val) {
                $collect_info = $discover_collection->discover_collection_msg($val['discover_id'], $this->uid);
                if ($collect_info) {
                    $val['collection'] = true;
                } else {
                    $val['collection'] = false;
                }
                $likes_info = $likes_collection->where(array('discover_id' => $val['discover_id'], 'likes_uid' => $this->uid))->field('likes_id')->find();
                if ($likes_info) {
                    $val['likes'] = true;
                } else {
                    $val['likes'] = false;
                }
                $val['add_time'] = date('Y-m-d', $val['add_time']);
                // 处理图片
                $val['discover_img'] = !empty($val['discover_img']) ? unserialize($val['discover_img']) : false;
                foreach($val['discover_img'] as &$v){
                    if (strpos($v,$this->config['site_url']) == false) {
                        $v=$this->config['site_url'].$v;
                    }
                }
                // 过滤处理
                $discover_content = unserialize($val['discover_content']);
                if ($discover_content) {
                    $val['discover_content'] = $discover_content;
                }
            }
        }
        $discover_msg_list['default_avatar'] = $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png';
        $this->returnCode(0,$discover_msg_list);
    }

    /**
     * 获取发现-信息（类型）
     *
     */
    public function discover_type_index() {
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        // 默认分页、每页取6条信息
        $listRows = 5;
        $page = empty($_POST['page']) ? 1 : intval($_POST['page']);
        // 只获取正常状态下的信息
        $where = '`dm`.`discover_status` = 1';
        if (!empty($_GET['type_id'])) {
            $where .= ' AND `dm`.`type_id` =' . $_GET['type_id'];
        }
        $discover_msg_list = D('Discover_msg')->discover_msg_list($where, $listRows, $page);
        if ($discover_msg_list['list']) {
            // 查询收藏状态
            $discover_collection = D('Discover_collection');
            foreach ($discover_msg_list['list'] as &$val) {
                $collect_info = $discover_collection->discover_collection_msg($val['discover_id'], $this->uid);
                if ($collect_info) {
                    $val['collection'] = true;
                } else {
                    $val['collection'] = false;
                }
                // 处理图片
                $val['discover_img'] = !empty($val['discover_img']) ? unserialize($val['discover_img']) : false;
                foreach($val['discover_img'] as &$v){
                    $v=$this->config['site_url'].$v;
                }
            }
        }
        // 查询发布类型
        if (!empty($_GET['type_id'])) {
            $categoryInfo = D('Discover_category')->field('type_name, type_id')->where(array('type_id'=>$_GET['type_id']))->find();
            $this->assign('categoryInfo', $categoryInfo);
        }
        $this->assign('discover_msg_list', $discover_msg_list);
        $this->assign('default_avatar', $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png');
        $this->display();
    }

    /**
     * 获取详细信息
     */
    public function look_discover_msg(){
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        $discover_id = intval($_GET['discover_id']);
        // 查询单条信息
        $info = M("Discover_msg")->where(array('discover_id'=>$discover_id))->find();
        // 处理链接地址信
        if ($info && $info['discover_url']) {
            $info['discover_url'] = htmlspecialchars_decode($info['discover_url'], true);
            $info['discover_url'] = str_replace('amp;', '', $info['discover_url']);
            $url_info = $this->shop_detail($info['discover_url']);
            if ($url_info && $url_info['status'] == 1) {
                $info['url_info'] = $url_info['info'];
            }
        }
        // 查询发布者昵称
        $userInfo = M('User')->field('nickname, avatar')->where(array('uid'=>$info['discover_uid']))->find();
        // 查询发布类型
        $categoryInfo = D('Discover_category')->field('type_name')->where(array('type_id'=>$info['type_id']))->find();
        $info['nickname'] = $userInfo['nickname'];
        $info['avatar'] = $userInfo['avatar'];
        $info['type_name'] = $categoryInfo['type_name'];
        // 查询收藏状态
        $discover_collection = D('Discover_collection');
        $collect_info = $discover_collection->discover_collection_msg($discover_id, $uid);
        if ($collect_info) {
            $info['collection'] = true;
        } else {
            $info['collection'] = false;
        }
        // 查询点赞状态
        $likes_collection = D('Discover_likes');
        $likes_info = $likes_collection->where(array('discover_id' => $discover_id, 'likes_uid' => $uid))->field('likes_id')->find();
        if ($likes_info) {
            $info['likes'] = true;
        } else {
            $info['likes'] = false;
        }
        $info['author'] = 0;
        if ($info['discover_uid'] == $uid) {
            $info['author'] = 1;
        }
        // 过滤处理
        $discover_content = unserialize($info['discover_content']);
        if ($discover_content) {
            $info['discover_content'] = $discover_content;
        }
        $info['discover_content'] = str_replace(PHP_EOL,'<br>',$info['discover_content']);
        $info['discover_content'] = htmlspecialchars_decode($info['discover_content'], ENT_QUOTES);
        $this->assign('info', $info);
        // 处理图片
        $img_arr = !empty($info['discover_img']) ? unserialize($info['discover_img']) : false;
        foreach($img_arr as &$v){
            if (strpos($v,$this->config['site_url']) == false) {
                $v=$this->config['site_url'].$v;
            }
        }
        $this->assign('img_list', $img_arr);
        $this->assign('default_avatar', $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png');
        $this->display();
    }

    /**
     * 添加发现-信息
     */
    public function add_discover_msg(){
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        if(IS_POST){
            $data_msg = $this->Removalquotes($_POST);
            // 处理获取图片信息
            $discover_img = isset($data_msg['inputimg']) ? serialize($data_msg['inputimg']) : '';
            // 分类id必传
            if (empty($data_msg['type_id'])){
                $this->error_tips('请选择分类！');
            }
            $type_id = $data_msg['type_id'];
            $type_msg = M('Discover_category')->where(array('type_id' => $type_id))->find();
            $count = M('Discover_category')->where(array('type_status' => 1))->count();
            if ($count == 0) {
                $this->error_tips('请等待管理员后台添加分类！');
            }
            if (empty($type_msg)) {
                $this->error_tips('所属分类不存在或者已被删除！');
            }
            // 内容必传
            if (empty($data_msg['discover_content'])){
                $this->error_tips('请填写内容！');
            }
            $discover_content = serialize($data_msg['discover_content']);
            // 链接 选传
            $discover_url = isset($data_msg['discover_url']) ? htmlspecialchars_decode(trim($data_msg['discover_url'])) : '';
            if ($discover_url) {
                $discover_url = str_replace('amp;', '', $discover_url);
                $url_info = $this->shop_detail($discover_url);
                if ($url_info && $url_info['status'] == 0) {
                    $this->error_tips($url_info['msg']);
                }
            }

            $add_info = array(
                'discover_status' => 1,
                'discover_uid' => $uid,
                'type_id' => $type_id,
                'discover_img' => $discover_img,
                'discover_content' => $discover_content,
                'discover_url' => $discover_url,
                'collection_num' => 0,
                'add_time' => time()
            );
            $add_id = M('Discover_msg')->data($add_info)->add();
            if ($add_id) {
                $this->success_tips('添加成功！', U('Discover/look_discover_msg?discover_id=' . $add_id));
            } else {
                $this->error_tips('添加失败，请重试！');
            }
        } else {
            $this->display();
        }
    }

    /**
     * 编辑发现-信息-页面
     */
    public function discover_msg_edit(){
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        $discover_id = intval($_GET['discover_id']);
        // 查询单条信息
        $info = M("Discover_msg")->where(array('discover_id'=>$discover_id))->find();
        // 查询发布者昵称
        $userInfo = M('User')->field('nickname, avatar')->where(array('uid'=>$info['discover_uid']))->find();
        // 查询发布类型
        $categoryInfo = D('Discover_category')->field('type_name')->where(array('type_id'=>$info['type_id']))->find();
        $info['nickname'] = $userInfo['nickname'];
        $info['avatar'] = $userInfo['avatar'];
        $info['type_name'] = $categoryInfo['type_name'];
        // 查询收藏状态
        $discover_collection = D('Discover_collection');
        $collect_info = $discover_collection->discover_collection_msg($discover_id, $uid);
        if ($collect_info) {
            $info['collection'] = true;
        } else {
            $info['collection'] = false;
        }
        // 过滤处理
        $discover_content = unserialize($info['discover_content']);
        if ($discover_content) {
            $info['discover_content'] = $discover_content;
        }
        if ($info['discover_url']) {
            $info['discover_url'] = htmlspecialchars_decode($info['discover_url'], true);
            $info['discover_url'] = str_replace('amp;', '', $info['discover_url']);
        }
        $this->assign('info', $info);
        // 处理图片
        $img_arr = !empty($info['discover_img']) ? unserialize($info['discover_img']) : false;
        foreach($img_arr as &$v){
            if (strpos($v,$this->config['site_url']) == false) {
                $v=$this->config['site_url'].$v;
            }
        }
        $this->assign('img_list', $img_arr);
        $this->assign('default_avatar', $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png');
        $this->display();
    }

    /**
     * 编辑发现-信息
     */
    public function edit_discover_msg(){
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        $data_msg = $this->Removalquotes($_POST);
        // 处理信息id
        if (empty($_POST['discover_id'])) {
            exit(json_encode(array('status'=>0,'msg'=>'缺少信息id！')));
        }
        $discover_id = intval($_POST['discover_id']);
        // 获取信息
        $discover_msg = M('Discover_msg');
        $discover_single = $discover_msg->where(array('discover_id' => $discover_id))->find();
        if (empty($discover_single)) {
            exit(json_encode(array('status'=>0,'info'=>'该信息不存在或者已被删除！')));
        }
        if ($uid != $discover_single['discover_uid']) {
            $this->error_tips('只能编辑自己添加的！');
            exit(json_encode(array('status'=>0,'info'=>'只能编辑自己添加的')));
        }
        $edit_info = array();
        // 处理获取图片信息
        $discover_img = isset($data_msg['inputimg']) ? $data_msg['inputimg'] : '';
        $discover_single['discover_img'] = unserialize($discover_single['discover_img']);
        foreach ($discover_img as &$val) {
            $val = str_replace($this->config['site_url'], '', $val);
        }
        $edit_info['discover_img'] =serialize($discover_img);
        // 分类id 传过来了就做一下处理
        if (!empty($data_msg['type_id'])){
            $type_id = $data_msg['type_id'];
            $type_msg = M('Discover_category')->where(array('type_id' => $type_id))->find();
            if (empty($type_msg)) {
                exit(json_encode(array('status'=>0,'info'=>'所属分类不存在或者已被删除')));
            }
            if ($type_id != $discover_single['type_id'] && $type_id) {
                $edit_info['type_id'] = $type_id;
            }
        }
        // 内容 处理
        if (!empty($data_msg['discover_content'])){
            $discover_content = serialize($data_msg['discover_content']);
            if ($discover_content != $discover_single['discover_content'] && $discover_content) {
                $edit_info['discover_content'] = $discover_content;
            }
        }
        // 链接 处理
        if (!empty($data_msg['discover_url'])){
            $discover_url = $data_msg['discover_url'];
            if ($discover_url != $discover_single['discover_url'] && $discover_url) {
                $discover_url = str_replace('amp;', '', $discover_url);
                $url_info = $this->shop_detail($discover_url);
                if ($url_info && $url_info['status'] == 0) {
                    $this->error_tips($url_info['msg']);
                }
                $edit_info['discover_url'] = $discover_url;
            }
        }
        if (!empty($edit_info)) {
            $edit_id = M('Discover_msg')->where(array('discover_id' => $discover_id))->data($edit_info)->save();
            if ($edit_id) {
                exit(json_encode(array('status'=>1,'info'=>'编辑成功！','url'=> U('Discover/look_discover_msg?discover_id=' . $discover_id))));
            } else {
                exit(json_encode(array('status'=>0,'info'=>'编辑失败，请重试！')));
            }
        } else {
            exit(json_encode(array('status'=>1,'info'=>'编辑成功！','url'=> U('Discover/look_discover_msg?discover_id=' . $discover_id))));
        }
    }

    /**
     * 删除发现-信息（只能自己删除自己添加的）
     */
    public function del_discover_msg(){
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息-确认是否存在该信息-并判断是否已经删除
        $info = M("Discover_msg")->where(array('discover_id'=>$discover_id))->find();
        if (empty($info) || $info['discover_status'] == 3) {
            exit(json_encode(array('status'=>0,'msg'=>'该信息不存在或者已经删除!')));
        }
        if ($uid != $info['discover_uid']) {
            exit(json_encode(array('status'=>0,'msg'=>'只能删除自己添加的!')));
        }
        // 确认存在未删除的正常该信息 做删除处理
        $close = M("Discover_msg")->where(array('discover_id'=>$discover_id))->data(array('discover_status' => 3))->save();
        if($close){
            exit(json_encode(array('status'=>1,'msg'=>'操作成功！')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'操作失败！请重试~')));
        }
    }

    /**
     * 收藏
     */
    public function collection_msg() {
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息-确认是否存在该信息
        $discover_msg = M("Discover_msg");
        $info = $discover_msg->where(array('discover_id'=>$discover_id, 'discover_status' => 1))->find();
        if (empty($info)) {
            exit(json_encode(array('status'=>0,'msg'=>'该信息不存在或者已经删除')));
        }
        $discover_collection = M('Discover_collection');
        $collect_info = $discover_collection->where(array('discover_id'=>$discover_id, 'collection_uid' => $uid))->find();
        if ($collect_info) {
            exit(json_encode(array('status'=>0,'msg'=>'已收藏')));
        }
        $add_info = array(
            'discover_id' => $discover_id,
            'collection_uid' => $uid,
            'add_time' => time()
        );
        D()->startTrans();
        $collect = $discover_collection->data($add_info)->add();
        if($collect){
            // 增加收藏数量
            $collect_num = $discover_msg->where(array('discover_id'=>$discover_id))->setInc('collection_num');
            if ($collect_num) {
                D()->commit();
                exit(json_encode(array('status'=>1,'msg'=>'收藏成功！')));
            } else {
                D()->rollback();
                exit(json_encode(array('status'=>0,'msg'=>'收藏失败！请重试~')));
            }
        }else{
            D()->rollback();
            exit(json_encode(array('status'=>0,'msg'=>'收藏失败！请重试~')));
        }
    }

    /**
     * 取消收藏
     */
    public function cancel_collection_msg() {
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息-确认是否存在该信息
        $discover_msg = M("Discover_msg");
        $info = $discover_msg->where(array('discover_id'=>$discover_id, 'discover_status' => 1))->find();
        if (empty($info)) {
            exit(json_encode(array('status'=>0,'msg'=>'该信息不存在或者已经删除')));
        }
        $discover_collection = M('Discover_collection');
        $collect_info = $discover_collection->where(array('discover_id'=>$discover_id, 'collection_uid' => $uid))->find();
        if (empty($collect_info)) {
            exit(json_encode(array('status'=>0,'msg'=>'该信息您未收藏')));
        }
        D()->startTrans();
        $collect = $discover_collection->where(array('discover_id'=>$discover_id, 'collection_uid' => $uid))->delete();
        if($collect){
            // 减少收藏数量
            $collect_num = $discover_msg->where(array('discover_id'=>$discover_id))->setDec('collection_num');
            if ($collect_num) {
                D()->commit();
                exit(json_encode(array('status'=>1,'msg'=>'取消收藏成功!')));
            } else {
                D()->rollback();
                exit(json_encode(array('status'=>0,'msg'=>'取消收藏失败！请重试~')));
            }
        }else{
            D()->rollback();
            exit(json_encode(array('status'=>0,'msg'=>'取消收藏失败！请重试~')));
        }
    }


    /**
     * 点赞
     */
    public function likes_msg() {
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息-确认是否存在该信息
        $discover_msg = M("Discover_msg");
        $info = $discover_msg->where(array('discover_id'=>$discover_id, 'discover_status' => 1))->find();
        if (empty($info)) {
            $this->error('该信息不存在或者已经删除');
        }
        $discover_likes = M('Discover_likes');
        $likes_info = $discover_likes->where(array('discover_id'=>$discover_id, 'likes_uid' => $uid))->find();
        if ($likes_info) {
            exit(json_encode(array('status'=>0,'msg'=>'已收藏')));
        }
        $add_info = array(
            'discover_id' => $discover_id,
            'likes_uid' => $uid,
            'add_time' => time()
        );
        D()->startTrans();
        $likes = $discover_likes->data($add_info)->add();
        if($likes){
            // 增加收藏数量
            $likes_num = $discover_msg->where(array('discover_id'=>$discover_id))->setInc('likes_num');
            if ($likes_num) {
                D()->commit();
                exit(json_encode(array('status'=>1,'msg'=>'点赞成功！')));
            } else {
                D()->rollback();
                exit(json_encode(array('status'=>0,'msg'=>'点赞失败！请重试~')));
            }
        }else{
            D()->rollback();
            exit(json_encode(array('status'=>0,'msg'=>'点赞失败！请重试~')));
        }
    }

    /**
     * 取消点赞
     */
    public function cancel_likes_msg() {
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息-确认是否存在该信息
        $discover_msg = M("Discover_msg");
        $info = $discover_msg->where(array('discover_id'=>$discover_id, 'discover_status' => 1))->find();
        if (empty($info)) {
            exit(json_encode(array('status'=>0,'msg'=>'该信息不存在或者已经删除')));
        }
        $discover_likes = M('Discover_likes');
        $likes_info = $discover_likes->where(array('discover_id'=>$discover_id, 'likes_uid' => $uid))->find();
        if (empty($likes_info)) {
            exit(json_encode(array('status'=>0,'msg'=>'该信息您未点赞')));
        }
        D()->startTrans();
        $likes = $discover_likes->where(array('discover_id'=>$discover_id, 'likes_uid' => $uid))->delete();
        if($likes){
            // 减少收藏数量
            $likes_num = $discover_msg->where(array('discover_id'=>$discover_id))->setDec('likes_num');
            if ($likes_num) {
                D()->commit();
                exit(json_encode(array('status'=>1,'msg'=>'取消点赞成功！')));
            } else {
                D()->rollback();
                exit(json_encode(array('status'=>0,'msg'=>'取消点赞失败！请重试~')));
            }
        }else{
            D()->rollback();
            exit(json_encode(array('status'=>0,'msg'=>'取消点赞失败！请重试~')));
        }
    }


    /**
     * 我的发现栏类
     */
    public function my_discover() {
        $this->display();
    }

    /**
     * 获得我的收藏列表
     */
    public function my_collection(){
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        // 默认分页、每页取5条信息
        $listRows = 5;
        $page = empty($_POST['page']) ? 1 : intval($_POST['page']);
        // 只获取正常状态下的信息
        $where = '`dm`.`discover_status` = 1 AND `dsc`.`collection_uid` =' . $uid;
        $discover_collection_list = D('Discover_collection')->discover_collection_list($where, $listRows, $page);
        if ($discover_collection_list['list']) {
            // 查询点赞状态
            $likes_collection = D('Discover_likes');
            foreach ($discover_collection_list['list'] as &$val) {
                $val['collection'] = true;
                $likes_info = $likes_collection->where(array('discover_id' => $val['discover_id'], 'likes_uid' => $this->uid))->field('likes_id')->find();
                if ($likes_info) {
                    $val['likes'] = true;
                } else {
                    $val['likes'] = false;
                }
                // 处理图片
                $val['discover_img'] = !empty($val['discover_img']) ? unserialize($val['discover_img']) : false;
                foreach($val['discover_img'] as &$v){
                    if (strpos($v,$this->config['site_url']) == false) {
                        $v=$this->config['site_url'].$v;
                    }
                }
                // 过滤处理
                $discover_content = unserialize($val['discover_content']);
                if ($discover_content) {
                    $val['discover_content'] = $discover_content;
                }
                // 判断一下是否为当前用户添加
                if ($val['discover_uid'] == $uid) {
                    $val['is_author'] = true;
                } else {
                    $val['is_author'] = false;
                }
            }
        }
        $this->assign('discover_msg_list', $discover_collection_list);
        $this->assign('default_avatar', $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png');
        $this->display();
    }

    /**
     * 获得我的收藏列表ajax请求
     */
    public function my_collection_json(){
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        // 默认分页、每页取5条信息
        $listRows = 5;
        $page = empty($_POST['page']) ? 1 : intval($_POST['page']);
        // 只获取正常状态下的信息
        $where = '`dm`.`discover_status` = 1 AND `dsc`.`collection_uid` =' . $uid;
        $discover_collection_list = D('Discover_collection')->discover_collection_list($where, $listRows, $page);
        if ($discover_collection_list['list']) {
            // 查询点赞状态
            $likes_collection = D('Discover_likes');
            foreach ($discover_collection_list['list'] as &$val) {
                $val['collection'] = true;
                $likes_info = $likes_collection->where(array('discover_id' => $val['discover_id'], 'likes_uid' => $this->uid))->field('likes_id')->find();
                if ($likes_info) {
                    $val['likes'] = true;
                } else {
                    $val['likes'] = false;
                }
                $val['add_time'] = date('Y-m-d', $val['add_time']);
                // 处理图片
                $val['discover_img'] = !empty($val['discover_img']) ? unserialize($val['discover_img']) : false;
                foreach($val['discover_img'] as &$v){
                    if (strpos($v,$this->config['site_url']) == false) {
                        $v=$this->config['site_url'].$v;
                    }
                }
                // 过滤处理
                $discover_content = unserialize($val['discover_content']);
                if ($discover_content) {
                    $val['discover_content'] = $discover_content;
                }
                // 判断一下是否为当前用户添加
                if ($val['discover_uid'] == $uid) {
                    $val['is_author'] = true;
                } else {
                    $val['is_author'] = false;
                }
            }
        }
        $discover_collection_list['default_avatar'] = $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png';
        $this->returnCode(0,$discover_collection_list);
    }

    /**
     * 获取我的发现-信息
     *
     */
    public function my_discover_list() {
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        // 默认分页、每页取5条信息
        $listRows = 5;
        $page = empty($_POST['page']) ? 1 : intval($_POST['page']);
        // 只获取正常状态下的信息
        $where = '`dm`.`discover_status` = 1 AND `dm`.`discover_uid` =' . $uid;
        if (!empty($_POST['type_id'])) {
            $where .= 'AND `dm`.`type_id` =' . $_POST['type_id'];
        }
        $discover_msg_list = D('Discover_msg')->discover_msg_list($where, $listRows, $page);
        if ($discover_msg_list['list']) {
            // 查询收藏状态
            $discover_collection = D('Discover_collection');
            // 查询点赞状态
            $likes_collection = D('Discover_likes');
            foreach ($discover_msg_list['list'] as &$val) {
                $collect_info = $discover_collection->discover_collection_msg($val['discover_id'], $this->uid);
                if ($collect_info) {
                    $val['collection'] = true;
                } else {
                    $val['collection'] = false;
                }
                $likes_info = $likes_collection->where(array('discover_id' => $val['discover_id'], 'likes_uid' => $this->uid))->field('likes_id')->find();
                if ($likes_info) {
                    $val['likes'] = true;
                } else {
                    $val['likes'] = false;
                }
                // 处理图片
                $val['discover_img'] = !empty($val['discover_img']) ? unserialize($val['discover_img']) : false;
                foreach($val['discover_img'] as &$v){
                    if (strpos($v,$this->config['site_url']) == false) {
                        $v=$this->config['site_url'].$v;
                    }
                }
                // 过滤处理
                $discover_content = unserialize($val['discover_content']);
                if ($discover_content) {
                    $val['discover_content'] = $discover_content;
                }
            }
        }
        $this->assign('discover_msg_list', $discover_msg_list);
        $this->assign('default_avatar', $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png');
        $this->display();
    }

    /**
     * 获取我的发现-信息ajax请求
     *
     */
    public function my_discover_list_json() {
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $uid = $this->uid;
        // 默认分页、每页取5条信息
        $listRows = 5;
        $page = empty($_POST['page']) ? 1 : intval($_POST['page']);
        // 只获取正常状态下的信息
        $where = '`dm`.`discover_status` = 1 AND `dm`.`discover_uid` =' . $uid;
        if (!empty($_POST['type_id'])) {
            $where .= 'AND `dm`.`type_id` =' . $_POST['type_id'];
        }
        $discover_msg_list = D('Discover_msg')->discover_msg_list($where, $listRows, $page);
        if ($discover_msg_list['list']) {
            // 查询收藏状态
            $discover_collection = D('Discover_collection');
            // 查询点赞状态
            $likes_collection = D('Discover_likes');
            foreach ($discover_msg_list['list'] as &$val) {
                $collect_info = $discover_collection->discover_collection_msg($val['discover_id'], $this->uid);
                if ($collect_info) {
                    $val['collection'] = true;
                } else {
                    $val['collection'] = false;
                }
                $likes_info = $likes_collection->where(array('discover_id' => $val['discover_id'], 'likes_uid' => $this->uid))->field('likes_id')->find();
                if ($likes_info) {
                    $val['likes'] = true;
                } else {
                    $val['likes'] = false;
                }
                $val['add_time'] = date('Y-m-d', $val['add_time']);
                // 处理图片
                $val['discover_img'] = !empty($val['discover_img']) ? unserialize($val['discover_img']) : false;
                foreach($val['discover_img'] as &$v){
                    if (strpos($v,$this->config['site_url']) == false) {
                        $v=$this->config['site_url'].$v;
                    }
                }
                // 过滤处理
                $discover_content = unserialize($val['discover_content']);
                if ($discover_content) {
                    $val['discover_content'] = $discover_content;
                }
            }
        }
        $discover_msg_list['default_avatar'] = $this->config['site_url'] . '/tpl/Wap/pure/static/img/tou.png';
        $this->returnCode(0,$discover_msg_list);
    }

    //	图片上传
    public function ajaxImgUpload() {
        $mulu=isset($_GET['ml']) ? trim($_GET['ml']):'discover';
        $mulu=!empty($mulu) ? $mulu : 'discover';
        $filename = trim($_POST['filename']);
        $img = $_POST[$filename];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $imgdata = base64_decode($img);
        $rand_num = date('Ymd').'/'.$this->user_session['uid'];
        $getupload_dir = "/upload/discover/msg/".$rand_num;
        $upload_dir = "." . $getupload_dir;
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $newfilename = $mulu.'_' . date('YmdHis').mt_rand(10,99). '.jpg';
        $save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
        if ($save) {
            $this->dexit(array('error' => 0, 'data' => array('code' => 1, 'siteurl'=>$this->config['site_url'],'imgurl' =>$getupload_dir . '/' . $newfilename, 'msg' => '')));
        } else {
            $this->dexit(array('error' => 1, 'data' => array('code' => 0, 'url' => '', 'msg' => '保存失败！')));
        }
    }


    /**
     * 获取详细信息
     */
    public function discover_detail(){
        // 检验登录
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息
        $info = M("Discover_msg")->where(array('discover_id'=>$discover_id))->find();
        // 处理图片
        $img_arr = !empty($info['discover_img']) ? unserialize($info['discover_img']) : false;
        foreach($img_arr as &$v){
            $v=$this->config['site_url'].$v;
        }
        exit(json_encode(array('status'=>1,'msg'=>'成功获取数据', 'img_list' => $img_arr)));
    }



    /**
     * json 格式封装函数
     */
    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }

    /**
     *去除单双引号以及一些非法字符
     */
    private function Removalquotes($array) {
        $regex = "/\'|\"|\\\|\<script|\<\/script/";
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = $this->Removalquotes($value);
                } else {
                    $value = strip_tags(trim($value));
                    $value = preg_replace($regex, '', $value);
                    $array[$key] = htmlspecialchars($value, ENT_QUOTES);
                }
            }
            return $array;
        } else {
            $array = strip_tags(trim($array));
            $array = preg_replace($regex, '', $array);
            return htmlspecialchars($array, ENT_QUOTES);
        }
    }



    /**
     * 检测用户添加的是否为正确的团购地址，返回部分信息
     */
    public function check_url() {
        if (empty($this->uid)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        if (!$_POST['url']) {
            exit(json_encode(array('status'=>0,'msg'=>'1请上传需要检测的团购地址')));
        }
        $url = trim($_POST['url']);
        $url = str_replace('amp;', '', $url);
        $url_info_arr = explode('?', $url);
        $site_url = C('config.site_url');
        // 截取参数
        $param_arr = explode('&', $url_info_arr[1]);
        // 判断一下是否需要判断 g 和域名即前缀是否正确
        if ($url_info_arr[0] == $site_url . '/wap.php') {
            $check_g = false;
        } elseif ($url_info_arr[0] == $site_url) {
            $check_g = true;
        } else {
            exit(json_encode(array('status'=>0,'msg'=>'1检测的地址不符合规则, 请重新输入正确地址！')));
        }

        // 需要判断的参数处理为数组
        $param_info = array();

        // 循环对应字段判断是否正确
        // 特殊的 使用 # 号分割的
        $word = array();
        foreach ($param_arr as $val) {
            $param = explode('=', $val);
            if (strpos($param[1], '#') != false) {
                $word_arr = explode('#', $param[1]);
                $param_info[$param[0]] = $word_arr[0];
                $word = explode('-', $word_arr[1]);
            } else {
                $param_info[$param[0]] = $param[1];
            }
        }
        // 需不需要 g 值 判断一下 g 值是否正确
        if ($check_g && $param_info['g'] != 'Wap') {
            exit(json_encode(array('status'=>0,'msg'=>'2检测的地址不符合规则, 请重新输入正确地址！')));
        }
        // 首先判断一下是否为团购 商品
        // 例如 https://hf.pigcms.com/wap.php?g=Wap&c=Group&a=detail&group_id=899
        if ($param_info['c'] == 'Group' && $param_info['a'] == 'detail') {
            if (!$param_info['group_id']) {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,团购商品id错误！')));
            } else {
                $title_info = $this->link_info('group_detail', $param_info['group_id'], $url);
            }
        } elseif ($param_info['c'] == 'Group' && $param_info['a'] == 'shop') {
            // 判断一下是否为团购 店铺
            // 例如 https://hf.pigcms.com/wap.php?g=Wap&c=Group&a=shop&store_id=1449
            if (!$param_info['store_id']) {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,团购店铺id错误！')));
            } else {
                $title_info = $this->link_info('group_shop', $param_info['store_id'], $url);
            }
        } elseif ($param_info['c'] == 'Shop' && $param_info['a'] == 'classic_good') {
            // 判断是否属于快店商品
            // 例如 https://hf.pigcms.com/wap.php?c=Shop&a=classic_good&shop_id=5&good_id=61
            if (!$param_info['shop_id']) {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,快店shop_id错误！')));
            } elseif (!$param_info['good_id']) {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,快店good_id错误！')));
            } else {
                $title_info = $this->link_info('shop_classic_good', $param_info['good_id'], $url);
            }
        } elseif ($param_info['c'] == 'Shop' && $param_info['a'] == 'index') {
            // 判断是否属于快店商品或者快店店铺

            if ($word && $word[0]=='good' && (count($word) < 3 || !$word[1] || !$word[2])) {
                // 判断是否为快店商品链接规则
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,商品good错误！')));
            } elseif ($word && $word[0]=='shop' && (count($word) < 2 || !$word[1])) {
                // 判断是否为快店店铺链接规则
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,快店shop错误！')));
            } elseif($word && $word[0]=='good') {
                // 判断是否为快店商品
                // 例如 https://hf.pigcms.com/wap.php?g=Wap&c=Shop&a=index#good-1430-1839
                $good_id = $word[2];
                $title_info = $this->link_info('shop_classic_good', $good_id, $url);
            } elseif($word && $word[0]=='shop') {
                // 判断是否为快店店铺
                // 例如 https://hf.pigcms.com/wap.php?g=Wap&c=Shop&a=index#shop-1430
                $shop_id = $word[1];
                $title_info = $this->link_info('group_shop', $shop_id, $url);
            } else {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,请重新输入正确地址！')));
            }
        } elseif ($param_info['c'] == 'Shop' && $param_info['a'] == 'classic_shop') {
            // 判断是否为快店店铺
            // 例如 https://hf.pigcms.com/wap.php?c=Shop&a=classic_shop&shop_id=1341
            if (!$param_info['shop_id']) {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,快店店铺shop_id错误！')));
            } else {
                $title_info = $this->link_info('group_shop', $param_info['shop_id'], $url);
            }
        } elseif ($param_info['c'] == 'Mall' && $param_info['a'] == 'detail') {
            // 判断是否为商城商品
            // 例如  https://hf.pigcms.com/wap.php?g=Wap&c=Mall&a=detail&goods_id=1723
            if (!$param_info['goods_id']) {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,商城商品goods_id错误！')));
            } else {
                $title_info = $this->link_info('shop_classic_good', $param_info['goods_id'], $url);
            }
        } elseif ($param_info['c'] == 'Mall' && $param_info['a'] == 'store') {
            // 判断是否为商城店铺
            // 例如  https://hf.pigcms.com/wap.php?g=Wap&c=Mall&a=store&store_id=844
            if (!$param_info['store_id']) {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,商城店铺store_id错误！')));
            } else {
                $title_info = $this->link_info('mall_store', $param_info['store_id'], $url);
            }
        } elseif ($param_info['c'] == 'Diypage' && $param_info['a'] == 'page') {
            // 判断是否为商城店铺
            // 例如   https://hf.pigcms.com/wap.php?g=Wap&c=Diypage&a=page&page_id=100
            if (!$param_info['page_id']) {
                exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,商城店铺page_id错误！')));
            } else {
                $title_info = $this->link_info('mall_diypage_store', $param_info['page_id'], $url);
            }
        } else {
            exit(json_encode(array('status'=>0,'msg'=>'检测的地址不符合规则,请重新输入正确地址！')));
        }
        if ($title_info && $title_info['status'] == 1 && $title_info['info'] && !$title_info['info']['title_pic']) {
            $title_info['info']['title_pic'] = $site_url . '/tpl/Wap/pure/static/discover/images/icon/link.png?t=001';
        }
        exit(json_encode($title_info));
    }

    /**
     * 获取对应的团购信息
     * @param $url
     * @return array
     */
    private function shop_detail($url) {
        $url_info_arr = explode('?', $url);
        $site_url = C('config.site_url');
        // 截取参数
        $param_arr = explode('&', $url_info_arr[1]);
        // 判断一下是否需要判断 g 和域名即前缀是否正确
        if ($url_info_arr[0] == $site_url . '/wap.php') {
            $check_g = false;
        } elseif ($url_info_arr[0] == $site_url) {
            $check_g = true;
        } else {
            return array('status'=>0,'msg'=>'检测的地址不符合规则, 请重新输入正确地址！');
        }

        // 需要判断的参数处理为数组
        $param_info = array();

        // 循环对应字段判断是否正确
        // 特殊的 使用 # 号分割的
        $word = array();
        foreach ($param_arr as $val) {
            $param = explode('=', $val);
            if (strpos($param[1], '#') != false) {
                $word_arr = explode('#', $param[1]);
                $param_info[$param[0]] = $word_arr[0];
                $word = explode('-', $word_arr[1]);
            } else {
                $param_info[$param[0]] = $param[1];
            }
        }
        // 需不需要 g 值 判断一下 g 值是否正确
        if ($check_g && $param_info['g'] != 'Wap') {
            return array('status'=>0,'msg'=>'检测的地址不符合规则, 请重新输入正确地址！');
        }
        // 首先判断一下是否为团购 商品
        // 例如 https://hf.pigcms.com/wap.php?g=Wap&c=Group&a=detail&group_id=899
        if ($param_info['c'] == 'Group' && $param_info['a'] == 'detail') {
            if (!$param_info['group_id']) {
                return array('status'=>0,'msg'=>'检测的地址不符合规则,团购商品id错误！');
            } else {
                $title_info = $this->link_info('group_detail', $param_info['group_id'], $url);
            }
        } elseif ($param_info['c'] == 'Group' && $param_info['a'] == 'shop') {
            // 判断一下是否为团购 店铺
            // 例如 https://hf.pigcms.com/wap.php?g=Wap&c=Group&a=shop&store_id=1449
            if (!$param_info['store_id']) {
                return array('status'=>0,'msg'=>'检测的地址不符合规则,团购店铺id错误！');
            } else {
                $title_info = $this->link_info('group_shop', $param_info['store_id'], $url);
            }
        } elseif ($param_info['c'] == 'Shop' && $param_info['a'] == 'classic_good') {
            // 判断是否属于快店商品
            // 例如 https://hf.pigcms.com/wap.php?c=Shop&a=classic_good&shop_id=5&good_id=61
            if (!$param_info['shop_id']) {
                return array('status'=>0,'msg'=>'检测的地址不符合规则,快店shop_id错误！');
            } elseif (!$param_info['good_id']) {
                return array('status'=>0,'msg'=>'检测的地址不符合规则,快店good_id错误！');
            } else {
                $title_info = $this->link_info('shop_classic_good', $param_info['good_id'], $url);
            }
        } elseif ($param_info['c'] == 'Shop' && $param_info['a'] == 'index') {
            // 判断是否属于快店商品或者快店店铺

            if ($word && $word[0]=='good' && (count($word) < 3 || !$word[1] || !$word[2])) {
                // 判断是否为快店商品链接规则
                return array('status'=>0,'msg'=>'检测的地址不符合规则,商品good错误！');
            } elseif ($word && $word[0]=='shop' && (count($word) < 2 || !$word[1])) {
                // 判断是否为快店店铺链接规则
                return array('status'=>0,'msg'=>'检测的地址不符合规则,快店shop错误！');
            } elseif($word && $word[0]=='good') {
                // 判断是否为快店商品
                // 例如 https://hf.pigcms.com/wap.php?g=Wap&c=Shop&a=index#good-1430-1839
                // 首先检查一下店铺信息
                $store_id = $word[1];
                $store_info = $this->link_info('mall_store', $store_id, $url);
                if ($store_info['status'] == 0) {
                    // 如果存在问题 返回错误
                    return $store_info;
                }
                $good_id = $word[2];
                $title_info = $this->link_info('shop_classic_good', $good_id, $url);
            } elseif($word && $word[0]=='shop') {
                // 判断是否为快店店铺
                // 例如 https://hf.pigcms.com/wap.php?g=Wap&c=Shop&a=index#shop-1430
                $shop_id = $word[1];
                $title_info = $this->link_info('group_shop', $shop_id, $url);
            } else {
                return array('status'=>0,'msg'=>'检测的地址不符合规则, 请重新输入正确地址！');
            }
        } elseif ($param_info['c'] == 'Shop' && $param_info['a'] == 'classic_shop') {
            // 判断是否为快店店铺
            // 例如 https://hf.pigcms.com/wap.php?c=Shop&a=classic_shop&shop_id=1341
            if (!$param_info['shop_id']) {
                return array('status'=>0,'msg'=>'检测的地址不符合规则,快店店铺shop_id错误！');
            } else {
                $title_info = $this->link_info('group_shop', $param_info['shop_id'], $url);
            }
        } elseif ($param_info['c'] == 'Mall' && $param_info['a'] == 'detail') {
            // 判断是否为商城商品
            // 例如  https://hf.pigcms.com/wap.php?g=Wap&c=Mall&a=detail&goods_id=1723
            if (!$param_info['goods_id']) {
                return array('status'=>0,'msg'=>'检测的地址不符合规则,商城商品goods_id错误！');
            } else {
                $title_info = $this->link_info('shop_classic_good', $param_info['goods_id'], $url);
            }
        } elseif ($param_info['c'] == 'Mall' && $param_info['a'] == 'store') {
            // 判断是否为商城店铺
            // 例如  https://hf.pigcms.com/wap.php?g=Wap&c=Mall&a=store&store_id=844
            if (!$param_info['store_id']) {
                return array('status'=>0,'msg'=>'检测的地址不符合规则,商城店铺store_id错误！');
            } else {
                $title_info = $this->link_info('mall_store', $param_info['store_id'], $url);
            }
        } elseif ($param_info['c'] == 'Diypage' && $param_info['a'] == 'page') {
            // 判断是否为商城店铺
            // 例如   https://hf.pigcms.com/wap.php?g=Wap&c=Diypage&a=page&page_id=100
            if (!$param_info['page_id']) {
                return array('status'=>0,'msg'=>'检测的地址不符合规则,商城店铺page_id错误！');
            } else {
                $title_info = $this->link_info('mall_diypage_store', $param_info['page_id'], $url);
            }
        } else {
            return array('status'=>0,'msg'=>'检测的地址不符合规则, 请重新输入正确地址！');
        }
        if ($title_info && $title_info['status'] == 1 && $title_info['info'] && !$title_info['info']['title_pic']) {
            $title_info['info']['title_pic'] = $site_url . '/tpl/Wap/pure/static/discover/images/icon/link.png?t=001';
        }
        return $title_info;
    }

    /**
     * @param string $link_type 连接类型
     * @param integer $link_id 获取连接信息参数id
     * @param string $link_url 链接
     * @return array
     */
    private function link_info($link_type, $link_id, $link_url) {
        switch ($link_type) {
            case 'group_detail': // 获取团购商品部分信息
                $condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `m`.`status`='1' AND `g`.`status`='1' AND `g`.`group_id`='$link_id'";
                $condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'merchant'=>'m');
                $condition_field  = '`g`.`name` AS `group_name`, `m`.`name` AS `merchant_name`, `g`.`intro`, `g`.`group_id`, `g`.`pic`';
                $now_group = D('')->field($condition_field)->table($condition_table)->where($condition_where)->find();
                if ($now_group) {
                    $group_image_class = new group_image();
                    $all_pic = $group_image_class->get_allImage_by_path($now_group['pic']);
                    if ($all_pic) {
                        $title_info['title_pic'] = $all_pic[0]['image'];
                    }
                    $title_info['title'] = $now_group['group_name'] ? $now_group['group_name'] : $now_group['intro'];
                    $title_info['url'] = $link_url;
                    return array('status'=>1,'msg'=>'符合条件', 'info' => $title_info);
                } else {
                    return array('status'=>0,'msg'=>'当前'.$this->config['group_alias_name'].'不存在！');
                }
            case 'group_shop':  // 获取店铺部分信息
                $now_store = D('Merchant_store')->get_store_by_storeId($link_id);
                if ($now_store) {
                    if ($now_store['all_pic']) {
                        $title_info['title_pic'] = $now_store['all_pic'][0];
                    }
                    $title_info['title'] = $now_store['name'] ? $now_store['name'] : $now_store['txt_info'];
                    $title_info['url'] = $link_url;
                    return array('status'=>1,'msg'=>'符合条件', 'info' => $title_info);
                } else {
                    return array('status'=>0,'msg'=>'该店铺不存在！');
                }
            case 'shop_classic_good':  // 获取商品信息
                $now_goods = M('Shop_goods')->field(true)->where(array('goods_id' => $link_id))->find();
                if ($now_goods) {
                    if(!empty($now_goods['image'])){
                        $goods_image_class = new goods_image();
                        $tmp_pic_arr = explode(';', $now_goods['image']);
                        foreach ($tmp_pic_arr as $key => $value) {
                            $now_goods['pic_arr'][$key]['title'] = $value;
                            $now_goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 'm');
                        }
                    }

                    if ($now_goods['pic_arr']) {
                        $title_info['title_pic'] = reset($now_goods['pic_arr'])['url'];
                    }
                    $title_info['title'] = $now_goods['name'] ? $now_goods['name'] : $now_goods['unit'];
                    $title_info['url'] = $link_url;
                    return array('status'=>1,'msg'=>'符合条件', 'info' => $title_info);
                } else {
                    return array('status'=>0,'msg'=>'商品不存在！');
                }
            case 'mall_store':  // 获取商城商品信息
                $where = array('store_id' => $link_id);
                $now_store = M('Merchant_store')->field('pic_info, name, txt_info, auth')->where($where)->find();
                //资质认证
                if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
                    return array('status'=>0,'msg'=>'您查看的'.$this->config['shop_alias_name'].'没有通过资质审核！');
                }
                $now_shop = M('Merchant_store_shop')->field(true)->where($where)->find();
                if (empty($now_shop) || empty($now_store)) {
                    return array('status'=>0,'msg'=>'店铺信息错误！');
                }
                if ($now_store) {
                    if(!empty($now_store['pic_info'])){
                        $store_image_class = new store_image();
                        $images = $store_image_class->get_allImage_by_path($now_store['pic_info']);
                        if ($images) {
                            $title_info['title_pic'] = reset($images);
                        }
                    }
                    $title_info['title'] = $now_store['name'] ? $now_store['name'] : $now_store['txt_info'];
                    $title_info['url'] = $link_url;
                    return array('status'=>1,'msg'=>'符合条件', 'info' => $title_info);
                } else {
                    return array('status'=>0,'msg'=>'商品不存在！');
                }
            case 'mall_diypage_store': // 获取商城店铺信息
                $condition_page['page_id'] = $link_id;
                $now_page = D('Merchant_store_diypage')->where($condition_page)->find();
                if(empty($now_page)){
                    $this->error_tips('该页面不存在');
                }
                $now_store = D('Merchant_store')->get_store_by_storeId($now_page['store_id']);
                if ($now_store) {
                    if(!empty($now_store['all_pic'])){
                        $title_info['title_pic'] = reset($now_store['all_pic']);
                    }
                    $title_info['title'] = $now_store['name'] ? $now_store['name'] : $now_store['txt_info'];
                    $title_info['url'] = $link_url;
                    return array('status'=>1,'msg'=>'符合条件', 'info' => $title_info);
                } else {
                    return array('status'=>0,'msg'=>'商品不存在！');
                }
        }
    }


    /**
     * 上传类型和值获取对应的链接信息
     * $link_type 连接类型
     * $link_id 获取连接信息参数id
     */
    public function get_link_msg() {
        $site_url = C('config.site_url');
        if ($_POST['link_type']) {
            $link_type = $_POST['link_type'];
        } else {
            exit(json_encode(array('status'=>0,'msg'=>'类型或者参数错误')));
        }
        if ($_POST['link_id']) {
            $link_id = $_POST['link_id'];
        } else {
            exit(json_encode(array('status'=>0,'msg'=>'类型或者参数错误')));
        }

        if ($link_type && $link_type == 'group_detail' && $link_id) {
            // 团购商品
            $link_url = $site_url . '/wap.php?g=Wap&c=Group&a=detail&group_id=' . $link_id;
            $title_info = $this->link_info('group_detail', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'group_shop' && $link_id) {
            // 团购店铺
            $link_url = $site_url . '/wap.php?g=Wap&c=Group&a=shop&store_id=' . $link_id;
            $title_info = $this->link_info('group_shop', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'shop_good_index' && $link_id) {
            // 快店商品
            $link_url = $site_url . '/wap.php?g=Wap&c=Shop&a=index#' . $link_id;
            $word = explode('-', $link_id);
            if ($word && $word[0] && $word[0]=='good' && $word[1] && $word[2]) {
                // 首先检查一下店铺信息
                $store_id = $word[1];
                $store_info = $this->link_info('mall_store', $store_id, $link_url);
                if ($store_info['status'] == 0) {
                    // 如果存在问题 返回错误
                    exit(json_encode($store_info));
                }
                $link_id =  $word[2];
            } else {
                exit(json_encode(array('status'=>0,'msg'=>'类型或者参数错误')));
            }
            $title_info = $this->link_info('shop_classic_good', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'shop_classic_good' && $link_id) {
            // 快店商品
            $word = explode('-', $link_id);
            if (!$word[0] || !$word[1]) {
                exit(json_encode(array('status'=>0,'msg'=>'类型或者参数错误')));
            }
            $link_url = $site_url . '/wap.php?c=Shop&a=classic_good&shop_id=' .$word[0]. '&good_id=' . $word[1];

            // 首先检查一下店铺信息
            $store_id = $word[0];
            $store_info = $this->link_info('mall_store', $store_id, $link_url);
            if ($store_info['status'] == 0) {
                // 如果存在问题 返回错误
                exit(json_encode($store_info));
            }
            $link_id =  $word[1];

            $title_info = $this->link_info('shop_classic_good', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'shop_index' && $link_id) {
            // 快店店铺
            $link_url = $site_url . '/wap.php?g=Wap&c=Shop&a=index#' . $link_id;
            $word = explode('-', $link_id);
            if ($word && $word[0] == 'shop' && $word[1]) {
                $link_id = $word[1];
            } else {
                exit(json_encode(array('status'=>0,'msg'=>'类型或者参数错误')));
            }
            $title_info = $this->link_info('group_shop', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'classic_shop' && $link_id) {
            // 快店店铺
            $link_url = $site_url . '/wap.php?c=Shop&a=classic_shop&shop_id=' . $link_id;
            $title_info = $this->link_info('group_shop', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'mall_detail' && $link_id) {
            // 商城商品
            $link_url = $site_url . '/wap.php?g=Wap&c=Mall&a=detail&goods_id=' . $link_id;
            $title_info = $this->link_info('shop_classic_good', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'diypage' && $link_id) {
            // 商城店铺
            $link_url = $site_url . '/wap.php?g=Wap&c=Diypage&a=page&page_id=' . $link_id;
            $title_info = $this->link_info('mall_diypage_store', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'mall_store' && $link_id) {
            // 商城店铺
            $link_url = $site_url . '/wap.php?g=Wap&c=Mall&a=store&store_id=' . $link_id;
            $title_info = $this->link_info('mall_store', $link_id, $link_url);
        } else {
            exit(json_encode(array('status'=>0,'msg'=>'类型或者参数错误')));
        }

        if ($title_info && $title_info['status'] == 1 && $title_info['info'] && !$title_info['info']['title_pic']) {
            $title_info['info']['title_pic'] = $site_url . '/tpl/Wap/pure/static/discover/images/icon/link.png?t=001';
        }
        exit(json_encode($title_info));
    }

    /**
     * 获取对应的团购信息
     */
    public function test() {
        $site_url = C('config.site_url');
        $title_info = array();
        $link_type = 'mall_store';
        $link_id = '1';
        if ($link_type && $link_type == 'group_detail' && $link_id) {
            // 团购商品
            $link_url = $site_url . '/wap.php?g=Wap&c=Group&a=detail&group_id=' . $link_id;
            $title_info = $this->link_info('group_detail', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'group_shop' && $link_id) {
            // 团购店铺
            $link_url = $site_url . '/wap.php?g=Wap&c=Group&a=shop&store_id=' . $link_id;
            $title_info = $this->link_info('group_shop', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'shop_good_index' && $link_id) {
            // 快店商品
            $link_url = $site_url . '/wap.php?g=Wap&c=Shop&a=index#' . $link_id;
            $word = explode('-', $link_id);
            if ($word && $word[0] && $word[0]=='good' && $word[1] && $word[2]) {
                // 首先检查一下店铺信息
                $store_id = $word[1];
                $store_info = $this->link_info('mall_store', $store_id, $link_url);
                if ($store_info['status'] == 0) {
                    // 如果存在问题 返回错误
                    return $store_info;
                }
                $link_id =  $word[2];
            } else {
                $this->returnCode('73000001');
            }
            $title_info = $this->link_info('shop_classic_good', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'shop_classic_good' && $link_id) {
            // 快店商品
            $word = explode('-', $link_id);
            if (!$word[0] || !$word[1]) {
                $this->returnCode('73000001');
            }
            $link_url = $site_url . '/wap.php?c=Shop&a=classic_good&shop_id=' .$word[0]. '&good_id=' . $word[1];

            // 首先检查一下店铺信息
            $store_id = $word[0];
            $store_info = $this->link_info('mall_store', $store_id, $link_url);
            if ($store_info['status'] == 0) {
                // 如果存在问题 返回错误
                return $store_info;
            }
            $link_id =  $word[1];

            $title_info = $this->link_info('shop_classic_good', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'shop_index' && $link_id) {
            // 快店店铺
            $link_url = $site_url . '/wap.php?g=Wap&c=Shop&a=index#' . $link_id;
            $word = explode('-', $link_id);
            if ($word && $word[0] == 'shop' && $word[1]) {
                $link_id = $word[1];
            } else {
                $this->returnCode('73000001');
            }
            $title_info = $this->link_info('group_shop', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'classic_shop' && $link_id) {
            // 快店店铺
            $link_url = $site_url . '/wap.php?c=Shop&a=classic_shop&shop_id=' . $link_id;
            $title_info = $this->link_info('group_shop', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'mall_detail' && $link_id) {
            // 商城商品
            $link_url = $site_url . '/wap.php?g=Wap&c=Mall&a=detail&goods_id=' . $link_id;
            $title_info = $this->link_info('shop_classic_good', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'diypage' && $link_id) {
            // 商城店铺
            $link_url = $site_url . '/wap.php?g=Wap&c=Diypage&a=page&page_id=' . $link_id;
            $title_info = $this->link_info('mall_diypage_store', $link_id, $link_url);

        } elseif ($link_type && $link_type == 'mall_store' && $link_id) {
            // 商城店铺
            $link_url = $site_url . '/wap.php?g=Wap&c=Mall&a=store&store_id=' . $link_id;
            $title_info = $this->link_info('mall_store', $link_id, $link_url);
        } else {
            $this->returnCode('73000001');
        }

        if ($title_info && $title_info['status'] == 1 && $title_info['info'] && !$title_info['info']['title_pic']) {
            $title_info['info']['title_pic'] = $site_url . '/tpl/Wap/pure/static/discover/images/icon/link.png?t=001';
        }
        fdump($link_type . '--------------->', 'get_link_msg', true);
        fdump($title_info, 'get_link_msg', true);
        $this->returnCode(0, $title_info);
    }

}