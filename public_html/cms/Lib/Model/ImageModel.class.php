<?php
class ImageModel extends Model
{
    /**
     * @param int $oid 用户ID
     * @param string $path 文件路劲
     * @param int $otype 用户类型 0：系统后台管理员，1：商家后台管理员，2：用户，3：社区后台管理员
     * @param array $param  各种属性 其中size 图片最大尺寸
     * @param bool $ismark  是否打水印
     * @param bool $is_group_check  是否进行社群小程序图片安全检测
     * @return array
     */
	public function handle($oid, $path, $otype = 0, $param = array('size' => 5), $ismark = true, $is_group_check = false)
	{
// 		array('size' => 5, 'path', 'thumbMaxWidth', 'thumbMaxHeight', 'thumb' => true, 'imageClassPath' => 'ORG.Util.Image', 'thumbPrefix' => 'm_,s_', 'thumbRemoveOrigin' => false);

		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = $param['size'] * 1024 * 1024 ;
		$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif', 'mp3', 'ico');
		$upload->allowTypes = array('image/png', 'image/x-png', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'audio/mp3', 'image/x-icon', 'application/octet-stream');
		$upload->saveRule = 'uniqid_rand';
		isset($param['thumb']) && $upload->thumb = $param['thumb'];
		isset($param['imageClassPath']) && $upload->imageClassPath = $param['imageClassPath'];
		isset($param['thumbPrefix']) && $upload->thumbPrefix = $param['thumbPrefix'];
		isset($param['thumbMaxWidth']) && $upload->thumbMaxWidth = $param['thumbMaxWidth'];
		isset($param['thumbMaxHeight']) && $upload->thumbMaxHeight = $param['thumbMaxHeight'];
		isset($param['thumbRemoveOrigin']) && $upload->thumbRemoveOrigin = $param['thumbRemoveOrigin'];


		$img_mer_id = sprintf("%09d", $oid);
		if ($path == 'sysgoods') {
		    $rand_num = 'sysgoods_' . substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
		} else {
		    $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
		}
		$upload_dir = "./upload/{$path}/{$rand_num}/";
		
		if(!is_dir($upload_dir)){
			mkdir($upload_dir, 0777, true);
		}

		$upload->savePath = $upload_dir;// 设置附件上传目录

		if (!$upload->upload()) {// 上传错误提示错误信息
			return array('error' => 1, 'message' => $upload->getErrorMsg());
		} else {// 上传成功 获取上传文件信息
			$watermarkfile =  C('config.site_water_mark');//'./upload/watermark/home.png';
			$flag = false;
			if ($ismark && $watermarkfile) {
				$pt = pathinfo($watermarkfile);
				$pu = parse_url($watermarkfile);
				if (isset($pu['path']) && isset($pt['extension'])) {
					$watermarkfile = '.' . $pu['path'];
					$watermarksize = @getimagesize($watermarkfile);
					$watermark = array();
					$watermark['watermarkstatus'] = C('config.site_water_mark_pos');
					$watermark['watermarktype'] = isset($pt['extension']) ? $pt['extension'] : 'png';//'png';
					$watermark['watermarkfile'] = $watermarkfile;
					$watermark['watermarkminwidth'] = $watermarksize[0];
					$watermark['watermarkminheight'] = $watermarksize[1];
					$watermark['watermarkquality'] = 90;
					$watermark['watermarktrans'] = 100;
					$image_water_mark = new image_water_mark();
					$flag = true;
				}
			}
			$images = array();
			$files = $upload->getUploadFileInfo();
			$d_community_file = D('Community_file');
            $site = C('config.site_url');
			foreach ($files as $file) {
				$images['url'][$file['key']] = substr($file['savepath'] . $file['savename'], 1);
				// 判断是否需要进行图片内容安全检测
                if ($is_group_check) {
                    $url = $site . $images['url'][$file['key']];
                    $check = $d_community_file->imgSecCheck($url);
                    if ($check['errcode'] != 0) {
                        $real_url = "{$_SERVER['DOCUMENT_ROOT']}{$images['url'][$file['key']]}";
                        // 不合规的不安全内容进行本地清除
                        unlink($real_url);
                        return array('error' => 1, 'message' => '图片内容含有违法违规内容');
                    }
                }
				$images['title'][$file['key']] = $rand_num . ',' . $file['savename'];
				$imageSizeArr = getimagesize($file['savepath'].$file['savename']);
				$pigcms_id = $this->add(array('oid' => $oid, 'otype' => $otype, 'ip' => get_client_ip(), 'dateline' => time(), 'pic' => $images['url'][$file['key']], 'pic_md5' => md5($images['url'][$file['key']]),'img_remark'=>$file['name'],'img_width'=>intval($imageSizeArr[0]),'img_height'=>intval($imageSizeArr[1])));
                $images['pigcms_id'] = $pigcms_id;
				if ($flag) {
					$image_water_mark->Watermark($file['savepath'] . $file['savename'], $watermark);
					if ($upload->thumbPrefix) {
						$thumbPrefix	=	explode(',', $upload->thumbPrefix);
						foreach ($thumbPrefix as $pre) {
							if (is_file($file['savepath'] . $pre . $file['savename'])) $image_water_mark->Watermark($file['savepath'] . $pre . $file['savename'], $watermark);
						}
					}
				}
			}

			$images['error'] = 0;
			return $images;

		}
	}

	/**
	 * @param int $tableid 表中的主键
	 * @param string $path 图片地址
	 * @param string $tablename 表名
	 *
	 * path = '/upload/...'
	 */
	public function update_table_id($path, $tableid, $tableName)
	{
		if ($image = $this->field(true)->where(array('pic_md5' => md5($path)))->find()) {
			$this->where(array('pigcms_id' => $image['pigcms_id']))->save(array('tableid' => $tableid, 'tablename' => $tableName));
		}
	}
}