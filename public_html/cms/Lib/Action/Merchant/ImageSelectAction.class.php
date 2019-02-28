<?php
    class ImageSelectAction extends  BaseAction{
        //选择图片
        public function selectimg()
        {
            $search = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '';
            $condition = "`otype`=1 AND `oid`='{$this->merchant_session['mer_id']}' AND `status`=1";
            if ($search) $condition .= " AND `img_remark` LIKE '%{$search}%'";
            $count = D('Image')->where($condition)->count();
            $Page = new Page($count, 5);
            $fans_list = D('Image')->where($condition)->order('`pigcms_id` DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list', $fans_list);
            $this->assign('page', $Page->show());
            $this->display();
        }
        //检查是否有缩略图存在 不存在就生成，Image 方法要求物理路径
        public function check_thumb_exist(){
            if(IS_POST){
                import('ORG.Util.Image');
                $thumb_prefix = array('m_','s_');
                $thumbMaxWidth = explode(',',$this->config['group_pic_width']);
                $thumbMaxHeight = explode(',',$this->config['group_pic_height']);

                $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
                $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

                $upload_dir = "./upload/{$_POST['type']}/{$rand_num}/";
                if(!is_dir($upload_dir)){
                    mkdir($upload_dir,0777,true);
                }
                foreach($_POST['image_path'] as $v) {
                    $tmp_img_path = explode(',', $v);
                    if (!is_file($upload_dir . $tmp_img_path[1])) {
                        $img_content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $tmp_img_path[0] . '/' . $tmp_img_path[1]);
                        $l = file_put_contents($upload_dir . $tmp_img_path[1],$img_content);
                        for ($i = 0, $len = count($thumbMaxWidth); $i < $len; $i++) {
                            if (!is_file($upload_dir . $thumb_prefix[$i] . $tmp_img_path[1])) {
                                $res = Image::thumb($upload_dir . $tmp_img_path[1],$upload_dir . $thumb_prefix[$i] . $tmp_img_path[1], '', $thumbMaxWidth[$i], $thumbMaxHeight[$i], true);

                            }
                        }

                    }
                }
                return true;
            }
        }

        public function img_option(){
            if(IS_POST){
                if(!empty($_POST['text'])){
                    D('Image')->where(array('pigcms_id'=>$_POST['id']))->setField('img_remark',$_POST['text']);
                }

                if($_POST['op']=='del'){
                    D('Image')->where(array('pigcms_id'=>$_POST['id']))->setField('status',4);
                }
            }
        }

    }