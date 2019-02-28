<?php
/**
 * 群文件
 * */
class Community_fileModel extends Model
{
    /**
     * 获得文件信息
     * @param string $where
     * @param int $pageSize
     * @param int $page
     * @param string $order
     * @return array|bool
     */
    public function file_info_list($where, $pageSize = 8, $page = 1, $order ='`cfl`.`add_time` DESC')
    {
        if(!$where){
            return false;
        }
        // 最基础的搜索条件
        $where_str = '`cfd`.`folder_id` = `cfl`.`folder_id` AND `cfl`.`file_uid` = `u`.`uid` AND `cfl`.`community_id` = `ci`.`community_id`';
        $field = array('`cfl`.*', '`cfd`.`folder_name`', '`cfd`.`other_is_upload`', '`cfd`.`folder_status`', '`cfd`.`folder_uid`', '`u`.`nickname` as file_nickname', '`ci`.`group_owner_uid`');
        $table = array(C('DB_PREFIX').'community_folder'=>'cfd',C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_file'=>'cfl', C('DB_PREFIX').'community_info'=>'ci',);
        if (empty($where)) {
            $where = $where_str;
        } else {
            $where .= ' AND ' . $where_str;
        }
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total = D('')->table($table)->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->where($where)->order($order)->limit($firstRow.','.$pageSize)->select();
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order($order)->select();
            return [
                'list' => $list
            ];
        }
    }


    /**
     * 获取同一次上传者列表
     * @param $where
     * @param int $pageSize 查询 用户同批次上传分组显示，查组数
     * @param int $page
     * @return array|bool
     */
    public function file_info_user($where, $pageSize = 3, $page = 1){
        if(!$where){
            return false;
        }
        // 最基础的搜索条件
        $where_str = '`u`.`uid` = `cfl`.`file_uid` AND `ca`.`album_id` = `cfl`.`album_id`';
        $field = array('`cfl`.`file_uid`','`cfl`.`add_time`', '`cfl`.`file_des`', '`ca`.`album_id`', '`cfl`.`file_sign`', '`u`.`nickname`', '`u`.`avatar`');
        $table = array(C('DB_PREFIX').'community_album'=>'ca',C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'community_file'=>'cfl');
        if (empty($where)) {
            $where = $where_str;
        } else {
            $where .= ' AND ' . $where_str;
        }
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total_select = D('')->field('`cfl`.`file_uid`')->table($table)->group('`cfl`.`file_uid`, `cfl`.`file_sign`')->where($where)->select();
            $total = count($total_select);
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->group('`cfl`.`file_uid`, `cfl`.`file_sign`')->where($where)->order('`cfl`.`add_time` DESC')->limit($firstRow.','.$pageSize)->select();

            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list ? $list : array()
            ];
        } else {
            $list = D('')->field($field)->table($table)->group('`cfl`.`file_uid`, `cfl`.`file_sign`')->where($where)->order('`cfl`.`add_time` DESC')->select();
            return [
                'list' =>  $list ? $list : array()
            ];
        }
    }





    /**
     * 获取上传者同一次列表所有图片
     * @param int $file_sign  时间标记
     * @param int $album_id  相册id
     * @param int $file_uid 文件上传者uid
     * @return array|bool
     */
    public function file_by_user($file_sign, $album_id, $file_uid = 0){
        if(!$file_sign){
            return array();
        }
        $where = array(
            'file_sign' => $file_sign,
            'album_id' => $album_id,
            'file_status' => 1
        );
        if ($file_uid && $file_uid != 0) {
            $where['file_uid'] = $file_uid;
        }
        $site_url = C('config.site_url') ;
        $list = $this->field('file_id, community_id, album_id, file_uid, file_type, add_time, file_url, file_extra')->where($where)->order('`add_time` DESC')->select();
        if ($list) {
            foreach ($list as &$val) {
                if ($val['file_extra']) {
                    $file_extra = unserialize($val['file_extra']);
                    foreach ($file_extra as $k => $v) {
                        $val[$k] = $v;
                    }
                    unset($val['file_extra']);
                }
                if ($val['file_url']) $val['file_url'] = $site_url . $val['file_url'];
            }
        }
        return $list;
    }

    /**
     * 处理数目问题
     * @param $where
     * @param string $m_table
     * @param string $field
     * @return array|bool
     */
    public function change_num($where, $m_table = 'Community_album', $field = 'pic_num') {
        $count = $this->where($where)->count();
        $single = M($m_table)->field($field)->where($where)->find();
        if (intval($single[$field]) != $count) {
            M($m_table)->where($where)->data(array($field => $count))->save();
        }
        return $count;
    }



    /**
     * 获得图片信息
     * @param string $where
     * @param int $pageSize
     * @param int $page
     * @return array|bool
     */
    public function img_info_list($where, $pageSize = 8, $page = 1)
    {
        if(!$where){
            return false;
        }
        // 最基础的搜索条件
        $where_str = '`ca`.`album_id` = `cfl`.`album_id`';
        $field = array('`cfl`.*', '`ca`.`album_name`', '`ca`.`other_is_upload`', '`ca`.`album_status`', '`ca`.`album_uid`');
        $table = array(C('DB_PREFIX').'community_album'=>'ca',C('DB_PREFIX').'community_file'=>'cfl');
        if (empty($where)) {
            $where = $where_str;
        } else {
            $where .= ' AND ' . $where_str;
        }
        // 如果 $pageSize 大于 0 分页 ，小于等于 0 查询全部
        if ($pageSize > 0) {
            $total = D('')->table($table)->where($where)->count();
            $page = isset($page) ? intval($page) : 1;
            $totalPage = ceil($total / $pageSize);
            $firstRow = $pageSize * ($page - 1);
            $list = D('')->field($field)->table($table)->where($where)->order('`cfl`.`add_time` DESC')->limit($firstRow.','.$pageSize)->select();
            return [
                'total' => $total,
                'pageTotal' => $totalPage,
                'has_more' => $totalPage > $page ? true : false,
                'list' => $list
            ];
        } else {
            $list = D('')->field($field)->table($table)->where($where)->order('`cfl`.`add_time` DESC')->select();
            return [
                'list' => $list
            ];
        }
    }


    /**
     * 获得文件信息-及创建者信息-- 系统后台获取
     * @param $where
     * @param int $firstRow
     * @param int $listRows
     * @return array|bool
     */
    public function file_list($where, $firstRow = 1, $listRows = 8)
    {
        if(!$where){
            return false;
        }
        $order ='`cf`.`add_time` DESC';
        // 最基础的搜索条件
        $where['_string'] = '`cf`.`file_uid` = `u`.`uid`';
        $field = array('`cf`.*', '`u`.`nickname`');
        $table = array(C('DB_PREFIX').'community_file'=>'cf',C('DB_PREFIX').'user'=>'u');
        $list = D('')->field($field)->table($table)->where($where)->order($order)->limit($firstRow.','.$listRows)->select();
        return $list;
    }


    /**
     * 上传文件发消息
     * @param $folder_name
     * @param $file_id
     * @param $community_id
     * @param $uid
     * @return array
     */
    public function file_info_send($folder_name, $file_id, $community_id, $uid){
        if (!$folder_name || !$file_id || !$community_id || !$uid) return false;
        $file_info = $this->where(array('file_id' => $file_id))->field('file_remark, file_type, file_suffix')->find();
        if (!$file_info) return false;
        // 上传文件夹文件成功， 同步到云通讯 'pages/files/fileDetail/fileDetail?file_id=' . $file_id;
        $msg = $this->type_and_img($file_info['file_type'], $file_info['file_suffix']);
        $des = '在【' . $folder_name . '】上传了文件：' .  $file_info['file_remark'];
        $group_id = $community_id;
        $msg_body = array();
        $msgType = array();
        $msgType['MsgType'] = 'TIMTextElem';
        $msgType['MsgContent'] = array(
            'Text' => '【￥folder_file￥】&' . $file_id . '&' . urlencode($des) . '&' . urlencode($msg['url'])
        );
        $msg_body[] = $msgType;
        $database_info = D('Community_info');
        $ret_group = $database_info->qcloud_send_group_msg($group_id, $msg_body, $uid);
        if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
            return array('erroe_code'=>true);
        } else {
            return array('erroe_code'=>false);
        }
    }


    /**
     * 文件上传
     * @param $uid  int 操作用户uid
     * @param $community_id  int 群id
     * @param $folder_id  int 文件夹-所有文件均可上传
     * @param $album_id  int 相册-只允许上传图片
     * @param $is_add_dynamic  int 是否同步到动态（1 否 2 是）
     * @param $file_des string 文件介绍
     * @param $path string 路径
     * @param array $param 参数
     * @return array
     */
    public function community_file_handle($uid, $path,  $param = array('size' => 50), $community_id = 0, $folder_id = 0, $album_id = 0, $is_add_dynamic = 2, $file_des = '')
    {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize = $param['size'] * 1024 * 1024 ;
        // 全部文件的后缀
        $all_exts = array(
            'jpg', 'jpeg', 'png', 'gif',
            'mp3', 'ico', 'mp4', 'wmv',
            'xls', 'xlsx', 'txt', 'ppt', 'jnt', 'doc', 'docx', 'rtf', 'pdf', 'chm', 'pptx',
            'apk', 'ipa',
            '7z', 'tar', 'wim', 'zip', 'rar',
            'bmp'
        );
        // 图片文件的后缀
        $image_exts = array(
            'jpg', 'jpeg', 'png', 'gif'
        );
        // 图片，音频，  视频 ， excel文件, txt文本, ppt, word, pdf, 安卓安装包
        $all_type = array(
            'image/png', 'image/x-png', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/x-icon',
            'audio/mp3',
            'application/octet-stream', 'video/mp4','video/x-ms-wmv',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-powerpoint',
            'application/msword',
            'application/pdf',
            'application/vnd.android.package-archive',
            'application/x-tar', 'application/x-rar', 'application/x-zip-compressed',
            'image/bmp'
        );
        // 图片文件的所有类型
        $image_type = array('image/png', 'image/x-png', 'image/jpg', 'image/jpeg',
            'image/pjpeg', 'image/gif', 'image/x-icon');
        // 判断给予所需要的条件
        if ($folder_id && intval($folder_id) > 0) {
            $upload->allowTypes = $all_type;
            $upload->allowExts = $all_exts;
        } else {
            $upload->allowTypes = $image_type;
            $upload->allowExts = $image_exts;
        }
        $upload->saveRule = 'uniqid_rand';
        isset($param['thumb']) && $upload->thumb = $param['thumb'];
        isset($param['imageClassPath']) && $upload->imageClassPath = $param['imageClassPath'];
        isset($param['thumbPrefix']) && $upload->thumbPrefix = $param['thumbPrefix'];
        isset($param['thumbMaxWidth']) && $upload->thumbMaxWidth = $param['thumbMaxWidth'];
        isset($param['thumbMaxHeight']) && $upload->thumbMaxHeight = $param['thumbMaxHeight'];
        isset($param['thumbRemoveOrigin']) && $upload->thumbRemoveOrigin = $param['thumbRemoveOrigin'];

        $img_mer_id = sprintf("%09d", $uid);

        $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

        $upload_dir = "./upload/{$path}/{$rand_num}/";

        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
        }

        $upload->savePath = $upload_dir;// 设置附件上传目录

        if (!$upload->upload()) {// 上传错误提示错误信息
            return array('error' => 1, 'message' => $upload->getErrorMsg());
        } else {// 上传成功 获取上传文件信息
            $msg = array();
            $files = $upload->getUploadFileInfo();
            $msg['file_id_arr'] = array();
            //  处理统一标记
            $file_sign = '' . time();
            $site = C('config.site_url');
            foreach ($files as $file) {
                $msg['url'][$file['key']] = substr($file['savepath'] . $file['savename'], 1);
                // 做一下图片内容安全检测
                if (in_array($file['type'], $image_type)) {
                    $url = $site . $msg['url'][$file['key']];
                    $check = $this->imgSecCheck($url);
                    if ($check['errcode'] != 0) {
                        $real_url = "{$_SERVER['DOCUMENT_ROOT']}{$msg['url'][$file['key']]}";
                        // 不合规的不安全内容进行本地清除
                        unlink($real_url);
                        return array('error' => 1, 'message' => '图片内容含有违法违规内容');
                    }
                }
                $msg['title'][$file['key']] = $rand_num . ',' . $file['savename'];
                if (in_array(strtolower($file['type']),$image_type,true)) {
                    $imageSizeArr = getimagesize($file['savepath'].$file['savename']);
                    $file_extra = serialize(array(
                        'img_width'=>intval($imageSizeArr[0]),
                        'img_height'=>intval($imageSizeArr[1]),
                        'size'=>$file['size']
                    ));
                } else {
                    $file_extra = serialize(array(
                        'size'=>$file['size']
                    ));
                }
                $add_data = array(
                    'community_id' => $community_id,
                    'folder_id' => $folder_id,
                    'album_id' => $album_id,
                    'file_uid' => $uid,
                    'ip' => get_client_ip(),
                    'add_time' => time(),
                    'file_url' => $msg['url'][$file['key']],
                    'file_remark'=>$file['name'],
                    'file_suffix' => $file['extension'],
                    'file_name' => $file['savename'],
                    'file_type' => $file['type'],
                    'file_extra' => $file_extra,
                    'is_add_dynamic' => $is_add_dynamic,
                    'file_des' => $file_des,
                    'file_sign' => $file_sign
                );
                $file_id = $this->add($add_data);
                $msg['file_id_arr'][] = $file_id;
                $msg['file_id'] = $file_id;
                $msg['file_url_arr'][] = $msg['url'][$file['key']];
            }
            $msg['error'] = 0;
            return $msg;
        }
    }


    /**
     * 通过文件类型确定 文件的图片
     * @param $type 文件类型
     * @param $exts 文件后缀
     * @param bool $is_preview 是否获取能否预览
     * @return array
     */
    public function type_and_img($type, $exts, $is_preview = false) {
        // 图片
        $img_type = array('image/png', 'image/x-png', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/x-icon', 'image/bmp');
        $img_exts = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'ico');
        // 音频
        $music_type = array('audio/mp3');
        $music_exts = array('mp3');
        // 视频
        $video_type = array('application/octet-stream', 'video/mp4','video/x-ms-wmv');
        $video_exts = array('mp4', 'wmv');
        // excel文件
        $excel_type = array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $excel_exts = array('xls', 'xlsx');
        // txt 文本
        $txt_type = array('text/plain');
        $txt_exts = array('txt');
        // ppt
        $ppt_type = array('application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');
        $ppt_exts = array('ppt', 'pptx');
        // word
        $word_type = array('application/msword');
        $word_exts = array('jnt', 'doc', 'rtf', 'docx');
        // pdf
        $pdf_type = array('application/pdf');
        $pdf_exts = array('pdf');
        // 压缩文件
        $compress_type = array('application/x-tar', 'application/x-zip-compressed');
        $compress_exts = array('7z', 'tar', 'wim', 'zip');
        // 安卓包
        $android_type = array('application/vnd.android.package-archive');
        $android_exts = array('apk', 'ipa');
        // ios 安装包
        $ios_exts = array('ipa');
        // chm
        $chm_exts = array('chm');

        $info = array('is_img' => false,'url' => '');
        $site = C('config.site_url');
        if ($is_preview) {
            $info['is_preview'] = false;
        }

        if (in_array($exts, $img_exts)) {
            // 符合图片后缀，获取对应默认图片
            $info['is_img'] = true;
            $info['url'] = $site . '/static/community/file/img_image.png';
        } else if (in_array($exts, $music_exts)) {
            // 符合音频后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_music.png';
        } else if (in_array($exts, $video_exts)) {
            // 符合视频后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_video.png';
        } else if (in_array($exts, $excel_exts)) {
            // 符合excel后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_excel.png';
            if ($is_preview) {
                $info['is_preview'] = true;
            }
        } else if (in_array($exts, $txt_exts)) {
            // 符合txt后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_txt.png';
        } else if (in_array($exts, $ppt_exts)) {
            // 符合ppt后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_ppt.png';
            if ($is_preview) {
                $info['is_preview'] = true;
            }
        } else if (in_array($exts, $word_exts)) {
            // 符合word后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_word.png';
            if ($is_preview) {
                $info['is_preview'] = true;
            }
        } else if (in_array($exts, $pdf_exts)) {
            // 符合pdf后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_pdf.png';
            if ($is_preview) {
                $info['is_preview'] = true;
            }
        } else if (in_array($exts, $compress_exts)) {
            // 符合压缩文件后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_compress.png';
        } else if (in_array($exts, $android_exts)) {
            // 符合安卓安装包文件后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_apk.png';
        } else if (in_array($exts, $ios_exts)) {
            // 符合ios安装包文件后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_apk.png';
        } else if (in_array($exts, $chm_exts)) {
            // 符合chm文件后缀，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_chm.png';
        } else if (in_array($type, $img_type)) {
            // 符合图片类型，获取对应默认图片
            $info['is_img'] = true;
            $info['url'] = $site . '/static/community/file/img_image.png';
        } else if (in_array($type, $music_type)) {
            // 符合音频类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_music.png';
        } else if (in_array($type, $video_type)) {
            // 符合视频类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_video.png';
        } else if (in_array($type, $excel_type)) {
            // 符合excel类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_excel.png';
            if ($is_preview) {
                $info['is_preview'] = true;
            }
        } else if (in_array($type, $txt_type)) {
            // 符合txt类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_txt.png';
        } else if (in_array($type, $ppt_type)) {
            // 符合ppt类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_ppt.png';
            if ($is_preview) {
                $info['is_preview'] = true;
            }
        } else if (in_array($type, $word_type)) {
            // 符合word类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_word.png';
            if ($is_preview) {
                $info['is_preview'] = true;
            }
        } else if (in_array($type, $pdf_type)) {
            // 符合pdf类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_pdf.png';
            if ($is_preview) {
                $info['is_preview'] = true;
            }
        } else if (in_array($type, $compress_type)) {
            // 符合压缩文件类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_compress.png';
        } else if (in_array($type, $android_type)) {
            // 符合安卓安装包文件类型，获取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_android.png';
        } else {
            // 未知文件，取对应默认图片
            $info['is_img'] = false;
            $info['url'] = $site . '/static/community/file/img_default.png';
        }
        return $info;
    }


    /**
     * 获取群文件详细信息
     * @param $file_id
     * @return array|mixed
     */
    public function file_detail($file_id){
        $file_single = $this->where(array('file_id' => $file_id))->find();
        if (!$file_single) {
            return array();
        }
        if ($file_single && $file_single['file_url']) {
            $site_url = C('config.site_url') ;
            $file_single['file_url'] = $site_url . $file_single['file_url'];
        }
        if ($file_single['file_extra']) {
            $file_extra = unserialize($file_single['file_extra']);
            foreach ($file_extra as $k => $v) {
                $file_single[$k] = $v;
            }
            unset($file_single['file_extra']);
        }
        $info = $this->type_and_img($file_single['file_type'], $file_single['file_suffix'], true);
        // 可以预览的文件
        if ($info && $info['is_preview']) {
            $file_single['is_preview'] = true;
        } else {
            $file_single['is_preview'] = false;
        }
        unset($info['is_preview']);
        $file_single['file_img'] = $info;
        unset($file_single['ip']);
        unset($file_single['file_extra']);
        return $file_single;
    }


    /**
     * 获取相册最新的一张图片
     * @param $album_id
     * @return array|mixed
     */
    public function album_img($album_id){
        $file_single = $this->where(array('album_id' => $album_id, 'file_status' =>1))->order('add_time')->find();
        if (!$file_single) {
            return array();
        }
        if ($file_single && $file_single['file_url']) {
            $site_url = C('config.site_url') ;
            $file_single['file_url'] = $site_url . $file_single['file_url'];
        }
        if ($file_single['file_extra']) {
            $file_extra = unserialize($file_single['file_extra']);
            foreach ($file_extra as $k => $v) {
                $file_single[$k] = $v;
            }
            unset($file_single['file_extra']);
        }
        unset($file_single['ip']);
        unset($file_single['file_extra']);
        return $file_single;
    }


    /**
     * 鉴定图片内容是否安全
     * @param $img
     * @return bool
     */
    public function imgSecCheck($img) {
		$access_token_array = D('Access_token_wxcapp_expires')->get_access_token();
		if ($access_token_array['errcode'] == 0) {
            $access_token = $access_token_array['access_token'];
            $url = 'https://api.weixin.qq.com/wxa/img_sec_check?access_token=' . $access_token;
            if (class_exists('\CURLFile')) {
                $data['media'] = new \CURLFile(realpath($img));
            } else {
                $data['media'] = '@'.realpath($img);
            }
            import('ORG.Net.Http');
            $http = new Http();
            $check_ret = Http::curlPost($url, $data);
            if ($check_ret['errcode'] <= -1) {
                $check_ret['errcode'] = 0;
            }
            return $check_ret;
        } else {
            return $access_token_array;
        }
    }


    /**
     * 鉴定文字内容是否安全
     * @param $msg
     * @return bool
     */
    public function msgSecCheck($msg) {
        $access_token_array = D('Access_token_wxcapp_expires')->get_access_token();
        if ($access_token_array['errcode'] == 0) {
            $access_token = $access_token_array['access_token'];
            $url = 'https://api.weixin.qq.com/wxa/msg_sec_check?access_token=' . $access_token;
            $data['content'] = $msg;
            import('ORG.Net.Http');
            $http = new Http();
            $check_ret = Http::curlPost($url, json_encode($data));
            if ($check_ret['errcode'] <= -1) {
                $check_ret['errcode'] = 0;
            }
            return $check_ret;
        } else {
            return $access_token_array;
        }
    }


    /**
     * 生成计划
     * @param $dynamic_id
     * @param $des
     */
    public function create_plan($dynamic_id, $des = '') {
        $param = array();
        $param['type'] = 'comm_merge_pic';
        if ($dynamic_id) { // 上传图片后生成的动态id
            $param['dynamic_id'] = $dynamic_id;
        }
        if ($des) { // 上传图片后生成的描述
            $param['des'] = $des;
        }
        if ($param) {
            $this->add_plan($param);
        }
    }


    /*
     * 相册上传图片合并写入计划任务
     * $param  参数数组
     * */
    public function add_plan($param){
        import('@.ORG.plan');
        $plan_class = new plan();
        $params = array(
            'file'=>'community',
            'plan_time'=>time(),
            'param'=>$param,
        );
        $task_id = $plan_class->addTask($params);
        return array('task_id'=>$task_id);
    }


    /**
     * 生成合成群文件
     * @access public
     * @param $dynamic_id  int 统一上传标记
     * @param $des  string 描述
     * @return array
     * {
     *   "erroe_code":false,
     * }
     */
    public function create_comm_merge_pic($dynamic_id, $des = '')
    {
        //查询前9个人的图片
        $post_img = M('Community_dynamic')->where(array('id' => $dynamic_id))->field(true)->find();
        if (!$post_img) {
            return false;
        }
        if ($post_img['img']) {
            $dynamic_img = unserialize($post_img['img']);
            if (count($dynamic_img) > 9) {
                $dynamic_img = array_slice($dynamic_img, 0, 8);
            }
            foreach($dynamic_img as &$val){
                $val = C('config.site_url') .$val;
            }
            $msg = $this->create_img($dynamic_img, $dynamic_id);
            if (!$msg['erroe_code']) {
                $url = $msg['url'] . '?t=' . time();
                $data = array(
                    'file_uid' => $post_img['user_id'],
                    'dynamic_id' => $dynamic_id,
                    'merge_pic' => $url,
                    'add_time' => time()
                );
                $where = array(
                    'file_uid' => $post_img['user_id'],
                    'dynamic_id' => $dynamic_id,
                );
                $m_community_user_file_bind = M('Community_user_file_bind');
                if ($m_community_user_file_bind->where($where)->count()) {
                    $m_community_user_file_bind->where($where)->data($data)->save();
                } else {
                    $m_community_user_file_bind ->data($data)->add();
                }
                $this->dynamic_info_send($url, $dynamic_id, $post_img['community_id'], $des, $post_img['user_id']);
                return array('erroe_code'=>false);
            } else {
                return array('erroe_code'=>true);
            }
        } elseif ($post_img['file']) {
            $url = '/static/community/chat/comm_dynamic.png?t=001';
            $this->dynamic_info_send($url, $dynamic_id, $post_img['community_id'], $des, $post_img['user_id']);
            return array('erroe_code'=>false);
        } else {
            $url = '/static/community/chat/comm_dynamic.png?t=001';
            $this->dynamic_info_send($url, $dynamic_id, $post_img['community_id'], $des, $post_img['user_id']);
            return array('erroe_code'=>false);
        }
    }

    /**
     * 发消息
     * @param $image_url
     * @param $dynamic_id
     * @param $community_id
     * @param string $des
     * @param int $uid
     * @param string $all_url 完整的图片
     * @return array
     */
    public function dynamic_info_send($image_url, $dynamic_id, $community_id, $des = '用户上传了图片', $uid = 0, $all_url = ''){
        if ($image_url) {
            $url = C('config.site_url') . $image_url;
        } else if($all_url){
            $url = $all_url;
        } else {
            $url = '';
        }
        // 上传相册图片 同步发消息到云通讯
        $group_id = $community_id;
        $msg_body = array();
        $msgType = array();
        $msgType['MsgType'] = 'TIMTextElem';
        $msgType['MsgContent'] = array(
            'Text' => '【￥album_image￥】&' . $dynamic_id . '&' . urlencode($des) . '&' . urlencode($url)
        );
        $msg_body[] = $msgType;
        $database_info = D('Community_info');
        $ret_group = $database_info->qcloud_send_group_msg($group_id, $msg_body, $uid);
        fdump($ret_group, 'create_comm_merge_pic', true);
        if (!empty($ret_group) && $ret_group['ActionStatus'] == 'OK') {
            return array('erroe_code'=>true);
        } else {
            return array('erroe_code'=>false);
        }
    }


    /**
     * 生成图片
     * @param array $pic_list  图片路径组成的数组
     * @param int $pathNum  图片路径数据
     * @param string $path_msg 路径信息
     * @return array
     */
    public function create_img($pic_list, $pathNum = 0, $path_msg = '/upload/comm_file/group/')
    {

        // 合成图片
        $bg_w = 150;// 背景图片宽度
        $bg_h = 150;// 背景图片高度

        $pic_count = count($pic_list);
        $lineArr = array(); // 需要换行的位置
        $space_x = 3;
        $space_y = 3;
        $line_x = 0;
        $width = $bg_w;
        $height = $bg_h;
        switch($pic_count) {
            case 1: // 正中间
                $start_x = 0; // 开始位置X
                $start_y = 0; // 开始位置Y
                $pic_w = intval($bg_w); // 宽度
                $pic_h = intval($bg_h); // 高度
                $width = $bg_w; // 作为背景的宽
                $height = $bg_h; // 作为背景的宽
                // $start_x = intval($bg_w/4); // 开始位置X
                // $start_y = intval($bg_h/4); // 开始位置Y
                // $pic_w = intval($bg_w/2); // 宽度
                // $pic_h = intval($bg_h/2); // 高度
                break;
            case 2: // 中间位置并排
                $start_x = 0; // 开始位置X
                $start_y = 0; // 开始位置Y
                $pic_w = intval($bg_w/2) - 1;
                $pic_h = intval($bg_h/2) - 1;
                $space_x = 1;
                $width = $bg_w; // 作为背景的宽
                $height = $pic_h; // 作为背景的宽
                break;
            case 3:
                $start_x = 40; // 开始位置X
                $start_y = 5; // 开始位置Y
                $pic_w = intval($bg_w/2) - 5; // 宽度
                $pic_h = intval($bg_h/2) - 5; // 高度
                $lineArr = array(2);
                $line_x = 4;
                $width = $bg_w; // 作为背景的宽
                $height = $bg_h; // 作为背景的宽
                break;
            case 4:
                $start_x = 4; // 开始位置X
                $start_y = 5; // 开始位置Y
                $pic_w = intval($bg_w/2) - 5; // 宽度
                $pic_h = intval($bg_h/2) - 5; // 高度
                $lineArr = array(3);
                $line_x = 4;
                break;
            case 5:
                $start_x = 30; // 开始位置X
                $start_y = 30; // 开始位置Y
                $pic_w = intval($bg_w/3) - 5; // 宽度
                $pic_h = intval($bg_h/3) - 5; // 高度
                $lineArr = array(3);
                $line_x = 5;
                break;
            case 6:
                $start_x = 5; // 开始位置X
                $start_y = 30; // 开始位置Y
                $pic_w = intval($bg_w/3) - 5; // 宽度
                $pic_h = intval($bg_h/3) - 5; // 高度
                $lineArr = array(4);
                $line_x = 5;
                break;
            case 7:
                $start_x = 53; // 开始位置X
                $start_y = 5; // 开始位置Y
                $pic_w = intval($bg_w/3) - 5; // 宽度
                $pic_h = intval($bg_h/3) - 5; // 高度
                $lineArr = array(2,5);
                $line_x = 5;
                break;
            case 8:
                $start_x = 30; // 开始位置X
                $start_y = 5; // 开始位置Y
                $pic_w = intval($bg_w/3) - 5; // 宽度
                $pic_h = intval($bg_h/3) - 5; // 高度
                $lineArr = array(3,6);
                $line_x = 5;
                break;
            case 9:
                $start_x = 5; // 开始位置X
                $start_y = 5; // 开始位置Y
                $pic_w = intval($bg_w/3) - 5; // 宽度
                $pic_h = intval($bg_h/3) - 5; // 高度
                $lineArr = array(4,7);
                $line_x = 5;
                break;
        }
        // 处理背景
        $background = imagecreatetruecolor($width,$height); // 背景图片
        $color = imagecolorallocate($background, 221, 222, 224); // 灰色
//         $color = imagecolorallocate($background, 202, 201, 201); // 为真彩色画布创建白色背景，再设置为透明

        imagefill($background, 0, 0, $color);
        // imageColorTransparent($background, $color);


        foreach( $pic_list as $k=>$pic_path ) {
            $kk = $k + 1;
            if ( in_array($kk, $lineArr) ) {
                $start_x = $line_x;
                $start_y = $start_y + $pic_h + $space_y;
            }
            $pathInfo = pathinfo($pic_path);
            switch( strtolower($pathInfo['extension']) ) {
                case 'jpg':
                case 'jpeg':
                    $imagecreatefromjpeg = 'imagecreatefromjpeg';
                    break;
                case 'png':
                    $imagecreatefromjpeg = 'imagecreatefrompng';
                    break;
                case 'gif':
                default:
                    $imagecreatefromjpeg = 'imagecreatefromstring';
                    $pic_path = $this->get_img($pic_path);
                    break;
            }
            $resource = $imagecreatefromjpeg($pic_path);

            // $start_x,$start_y copy图片在背景中的位置
            // 0,0 被copy图片的位置
            // $pic_w,$pic_h copy后的高度和宽度
            $res = imagecopyresized($background,$resource,$start_x,$start_y,0,0,$pic_w,$pic_h,imagesx($resource),imagesy($resource)); // 最后两个参数为原始图片宽度和高度，倒数两个参数为copy时的图片宽度和高度

            $start_x = $start_x + $pic_w + $space_x;
        }
        // header("Content-type: image/jpg");
        // imagejpeg($background);
        $img_community_id = sprintf("%09d", $pathNum);

        $rand_num = substr($img_community_id, 0, 3) . '/' . substr($img_community_id, 3, 3) . '/' . substr($img_community_id, 6, 3);
        $upload_dir  = "." . $path_msg . $rand_num;
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
        }
        $upload_dir  .=  "/".$pathNum.".png";
        if(imagegif($background, $upload_dir)){
            return array('erroe_code'=>false,'url'=> $path_msg . $rand_num."/".$pathNum.".png");
        }else{
            return array('erroe_code'=>true);
        }
    }

    //获取图片文本流
    public function get_img($url){
        $header = array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate',);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_HTTPHEADER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //
        // var_dump(curl_error($curl));
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($code == 200) {//把URL格式的图片转成base64_encode格式的！
            $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);
        }
        $img_content=$imgBase64Code;//图片内容
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result))
        {
            return  base64_decode(str_replace($result[1], '', $img_content));
        }else{
            return false;
        }
    }



    // pc 文件上传处理相关

    //生成pc文件上传用的临时二维码
    public function get_community_file_qrcode(){
        $appid     = C('config.pay_wxapp_group_appid');
        $appsecret = C('config.pay_wxapp_group_appsecret');

        if(empty($appid) || empty($appsecret)){
            return(array('error_code'=>true,'msg'=>'请联系管理员配置【AppId】【 AppSecret】'));
        }

        $database_community_file_qrcode = M('Community_file_qrcode');
        // 获取配置有效时间（单位： 秒）
        $community_code_effective_time = C('config.community_code_effective_time');
        if ($community_code_effective_time && intval($community_code_effective_time) > 0) {
            $effective_time = intval($community_code_effective_time);
        } else {
            $effective_time = 1800; // 取默认的半小时
        }
        $database_community_file_qrcode->where(array('add_time'=>array('lt',($_SERVER['REQUEST_TIME']-$effective_time))))->delete();

        $data_community_file_qrcode['add_time'] = $_SERVER['REQUEST_TIME'];
        $qrcode_id = $database_community_file_qrcode->data($data_community_file_qrcode)->add();
        if(empty($qrcode_id)){
            return(array('error_code'=>true,'msg'=>'获取二维码错误！无法写入数据到数据库。请重试。'));
        }
        $condition_community_file_qrcode['id'] = $qrcode_id;
        // 字符串拼接然后MD5
        $md5_string = md5($appid . $qrcode_id . $appsecret . 'pc');
        $data_community_file_qrcode['ticket'] = $md5_string;
        if($database_community_file_qrcode->where($condition_community_file_qrcode)->data($data_community_file_qrcode)->save()){

            return(array('error_code'=>false,'id'=>$qrcode_id,'file_ticket'=> $md5_string));
        }else{
            $database_community_file_qrcode->where($condition_community_file_qrcode)->delete();
            return(array('error_code'=>true,'msg'=>'获取二维码错误！保存二维码失败。请重试。'));
        }
    }
}
?>