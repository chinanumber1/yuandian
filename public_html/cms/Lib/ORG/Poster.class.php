<?php

/**
 * Class Poster
 * 创建图片
 */
class Poster
{
    private $img;//海报资源
    private $width;//画布宽度
    private $height;//画布高度
    private $error;//错误信息
    private $config;//相关配置
    private $scale;//缩放比例
    private $originalWidth;
    private $originalHeight;

    /* 缩略图相关常量定义 */
    const IMAGE_THUMB_SCALE     =   1 ; //常量，标识缩略图等比例缩放类型
    const IMAGE_THUMB_FILLED    =   2 ; //常量，标识缩略图缩放后填充类型
    const IMAGE_THUMB_CENTER    =   3 ; //常量，标识缩略图居中裁剪类型
    const IMAGE_THUMB_NORTHWEST =   4 ; //常量，标识缩略图左上角裁剪类型
    const IMAGE_THUMB_SOUTHEAST =   5 ; //常量，标识缩略图右下角裁剪类型
    const IMAGE_THUMB_FIXED     =   6 ; //常量，标识缩略图固定尺寸缩放类型

    public function __construct($config=array())
    {
        $this->config=array(
            'target_width'=>900,//目标大小
            'default_font_scale'=>0.62,//字体缩放比例 ps中：0.786
            'default_size'=>14,//默认字体大小
            'default_color'=>'255,255,255,1',//默认字体颜色
            'default_font'=>'yahei',//默认字体
            'line_spacing'=>2,//行间距
            'word_spacing'=>2,//字间距
            'fonts'=>array(
                'yahei'=>'./static/font/yahei.ttf',
            ),
            'vars' => array(),
            'avatar' => '',
            'default_avatar' =>'./static/imgages/tx.png',
            'qrcode'=> '',
            'qrcode_url' => '',
            'logo'=> '',
            'tmp_path' =>'./runtime/cache/images/promote_qrcode/'//存储临时文件，如微信头像等
        );
        $this->config=array_merge($this->config,$config);
    }

    //设置配置
    public function setConfig($config=array())
    {
        $this->config=array_merge($this->config,$config);
        return $this;
    }

    //设置变量
    public function setVars($vars=array())
    {
        $this->config['vars']=$this->config['vars']?$this->config['vars']:array();
        $this->config['vars']=array_merge($this->config['vars'],$vars);

        return $this;
    }

    //创建图片
    public function create($data)
    {

        $this->originalWidth=$data['width'];
        $this->originalHeight=$data['height'];
        $data['scale']=$data['scale']?$data['scale']:$this->getTargetScale($data['width']);
        $this->scale=$data['scale'];
        $this->width=$data['width']=$this->scaleSize($data['width']);
        $this->height=$data['height']=$this->scaleSize($data['height']);
        if($data['width']<=0)
        {
            $this->error='画布宽度不合法';
            return false;
        }
        if($data['height']<=0)
        {
            $this->error='画布高度不合法';
            return false;
        }
        $this->img = imagecreatetruecolor($data['width'],$data['height']);

        if(!$this->drawBg($data['bg'])){
            return false;
        }

        if(!empty($data['element']))
        {
            $element=$this->sortByz($data['element']);

            for($i=0;$i<count($element);$i++)
            {
                $type = $element[$i]['type'];

                //echo $type . "<br/>";

                $display=isset($element[$i]['display'])?$element[$i]['display']:'1';

                //不显示的对象则不绘制
                if($display=='0'||empty($display)||$display=='none'){
                    continue;
                }
                $result=true;
                switch ($type)
                {
                    case 'textarea':
                        $result=$this->drawTextArea($element[$i]);

                        break;
                    case 'avatar':
                        $result=$this->drawAvatar($element[$i]);
                        break;
                    case 'qrcode':
                        $result=$this->drawQrcode($element[$i]);
                        break;
                    default:
                        $result=true;
                }

                if(!$result)
                {
                    return false;
                }
            }
        }
        return true;
    }

    //图片背景
    private function drawBg($data)
    {
        $colorArr=$this->getColor($data['color'],'255,255,255,1');
        $color = imagecolorallocatealpha($this->img,$colorArr[0],$colorArr[1],$colorArr[2],$colorArr[3]);
        imagefill($this->img,0,0,$color);

        //背景图片
        if(!empty($data['image']))
        {
            $image=$data['image'];

            $url = trim(STATIC_URL,'static/');
            if(stripos($image['src'],$url) !== false || stripos($image['src'],C('config.site_url')) !== false){
                $image_url = $image['src'];
            } else if(stripos($image['src'],'upload/') !== false){
                $image_url = trim(C('config.site_url'),'/').$image['src'];
            } else {
                $image_url = trim(C('config.site_url'),'/').$image['src'];
            }

            $bgImg=$this->getImg($image_url);
            if(empty($bgImg))
            {
                $this->error='背景图片不存在';
                return false;
            }
            $bgImg=$this->thumb($bgImg,$this->width,$this->height,self::IMAGE_THUMB_CENTER);
            $this->cornerHandler($bgImg,30);
            $alpha=isset($image['alpha'])?$image['alpha']*100:100;
            $this->imagecopymerge_alpha($this->img,$bgImg, 0, 0, 0, 0,imagesx($bgImg),imagesy($bgImg), $alpha);
            imagedestroy($bgImg);
        }
        if(!empty($data['head']))
        {
            $head=$data['head'];
            $tmpH=$this->scaleSize(isset($head['height'])?$head['height']:100);
            $tmpC=$this->getColor($head['color'],'255,255,255,1');
            $tmpA=isset($head['alpha'])?127-ceil($head['alpha']*127):$tmpC[3];
            $headColor=imagecolorallocatealpha($this->img,$tmpC[0],$tmpC[1],$tmpC[2],$tmpA);
            imagefilledrectangle($this->img,0,0,0+imagesx($this->img),0+$tmpH,$headColor);
        }
        return true;
    }

    //绘制多行文本
    private function drawTextArea($data)
    {
        $width=$data['width']?$data['width']:null;
        if(isset($width))
            $width=$this->scaleSize($width);
        $x=$this->scaleSize($data['x']?$data['x']:0);
        $y=$this->scaleSize($data['y']?$data['y']:0);
        $lineHeight=$this->scaleSize($data['line_height']?$data['line_height']:0);
        $lineSpacing=$this->scaleSize($data['line_spacing']?$data['line_spacing']:$this->config['line_spacing']);
        $content=$data['content']?$data['content']:array();
        $parent=array();
        $parent['size']=$data['size'];
        $parent['angle']=$data['angle'];
        $parent['font']=$data['font'];
        $parent['color']=$data['color'];
        $parent['vertical_align']=$data['vertical_align'];
        $parent['word_spacing']=$data['word_spacing'];
        $multiLine=$this->typesetting($content,$parent,$x,$y,$lineHeight,$lineSpacing,$width);
        foreach ($multiLine as $line)
        {
            $words=$line['words'];

            for($i=0;$i<count($words);$i++)
            {
                $word=$words[$i];

                if(!is_file($word['font']))
                {
                    $this->error='字体文件不存在';

                    return false;
                }
                $color=imagecolorallocatealpha($this->img,$word['color'][0],$word['color'][1],$word['color'][2],$word['color'][3]);
                imagettftext($this->img,$word['size'],$word['angle'],$word['x']+$word['left'],$word['y']+$word['top'],
                    $color,$word['font'],$word['text']);
            }
        }
        return true;
    }

    //多行排版
    private function typesetting($content,$parent,$x,$y,$lineHeight=0,$lineSpacing=0,$width=null)
    {
        $posX=$x;
        $posY=$y;
        $multiLine=array();//多行
        $lineNum=0;//当前行号
        $multiLine[]=array('words'=>array(),'line_height'=>$lineHeight,'posY'=>$posY);
        for ($m=0;$m<count($content);$m++)
        {
            $item=$content[$m];
            //继承父级
            $item['size']=empty($item['size'])?$parent['size']:$item['size'];
            $item['angle']=empty($item['angle'])?$parent['angle']:$item['angle'];
            $item['font']=empty($item['font'])?$parent['font']:$item['font'];
            $item['color']=empty($item['color'])?$parent['color']:$item['color'];
            $item['vertical_align']=empty($item['vertical_align'])?$parent['vertical_align']:$item['vertical_align'];
            $item['word_spacing']=empty($item['word_spacing'])?$parent['word_spacing']:$item['word_spacing'];

            $size=$this->scaleFont($item['size']?$item['size']:$this->config['default_size']);
            $angle=$item['angle']?$item['angle']:0;
            $font=$this->getFont($item['font']);
            $color=$this->getColor($item['color'],$this->config['default_color']);
            $vertical_align=$item['vertical_align']?$item['vertical_align']:'baseline';
            $wordSpacing=$this->scaleSize($item['word_spacing']?$item['word_spacing']:$this->config['word_spacing']);
            $text=$this->replaceVars($item['text']);
            $strArr=$this->splitUnicode($text,1);//拆分文字
            for($i=0;$i<count($strArr);$i++)
            {
                $str=$strArr[$i];
                $box=$this->calculateTextBox($size,$angle,$font,$str);
                $multiLine[$lineNum]['line_height']=max($multiLine[$lineNum]['line_height'],$box['height']);
                $word=array('size'=>$size,'angle'=>$angle,'font'=>$font,'color'=>$color,'text'=>$str,'left'=>$box['left'],'top'=>$box['top']);
                $word['x']=$posX;
                $word['height']=$box['height'];
                $word['width']=$box['width'];
                $word['vertical_align']=$vertical_align;
                $multiLine[$lineNum]['words'][]=$word;
                $posX+=$box['width']+$wordSpacing;
                $nextBox=$this->nextBox($size,$angle,$font,$strArr[$i+1],$content[$m+1]);
                if(isset($width)&&($posX+(empty($nextBox)?0:$nextBox['width'])>$x+$width))
                {
                    $posY+=$multiLine[$lineNum]['line_height']+$lineSpacing;
                    $posX=$x;
                    $lineNum++;
                    $multiLine[]=array('words'=>array(),'line_height'=>$lineHeight,'posY'=>$posY);//新增一行
                }
            }
        }
        for($i=0;$i<count($multiLine);$i++)
        {
            $multiLine[$i]=$this->verticalAlign($multiLine[$i]);
        }
        return $multiLine;
    }

    //垂直布局
    private function verticalAlign($line)
    {
        $lineHeight=$line['line_height'];
        $posY=$line['posY'];
        $words=array();
        foreach ($line['words'] as $word)
        {
            if($word['vertical_align']=='baseline'||$word['vertical_align']=='bottom')
            {
                $word['y']=$posY+($lineHeight-$word['height']);
            }
            elseif ($word['vertical_align']=='middle')
            {
                $word['y']=$posY+($lineHeight-$word['height'])/2;
            }
            elseif ($word['vertical_align']=='top')
            {
                $word['y']=$posY;
            }
            $words[]=$word;
        }
        $line['words']=$words;
        return $line;
    }

    //下一个文字的盒模型
    private function nextBox($size,$angle,$font,$next,$nextItem)
    {
        if(isset($next))
            return $this->calculateTextBox($size,$angle,$font,$next);
        if(!isset($nextItem))
            return null;
        $size2=$this->scaleFont($nextItem['size']?$nextItem['size']:$this->config['default_size']);
        $angle2=$nextItem['angle']?$nextItem['angle']:0;
        $font2=$this->getFont($nextItem['font']);
        $strArr2=$this->splitUnicode($this->replaceVars($nextItem['text']),1);//拆分文字
        return isset($strArr2[0])?$this->calculateTextBox($size2,$angle2,$font2,$strArr2[0]):null;
    }

    //按照z对元素进行排序
    private function sortByz($element=array())
    {
        $index=array();
        for($i=0;$i<count($element);$i++)
        {
            $index[$element[$i]['z'].''.$i]=$i;
        }
        ksort($index);
        $newArr=array();
        foreach ($index as $key=>$value)
        {
            $newArr[]=$element[$value];
        }

        return $newArr;
    }

    //自动计算适应比例
    private function getTargetScale($width)
    {
        return !empty($this->config['target_width'])?$this->config['target_width']/$width:1;
    }

    //缩放尺寸
    private function scaleSize($size)
    {
        return round($this->scale*$size);
    }

    //缩放字体尺寸
    private function scaleFont($size)
    {
        return round($this->scale*$size*$this->config['default_font_scale']);
    }

    //获得一张图片操作句柄(支持远程图片、图片字符串、图片资源)
    private function getImg($path)
    {
        if(empty($path))
            return null;
        $path=ltrim($path);
        if(gettype($path)=='resource')
            return $path;
        if(strpos($path,'/')===0)
        {
            $path='.'.$path;
        }
        elseif (strpos($path,C('site_url'))===0)
        {
            $path='./'.ltrim(str_replace(C('site_url'),'',$path),'./');
        }
        if(strpos($path,'http')===0 || strpos($path,'https')===0)
        {
            $tmpPath=rtrim($this->config['tmp_path'],'/').'/'.sha1($path).'.tmp';
            if(!file_exists($tmpPath))
            {

                import('ORG.Net.Http');
                $http = new Http();
                $data = Http::curlGet($path);
                if(empty($data))
                    return null;
                if(!file_exists($this->config['tmp_path']))
                    mkdir($this->config['tmp_path'],0777,true);
                file_put_contents($tmpPath,$data);
                return imagecreatefromstring($data);
            }
            else
            {
                $path=$tmpPath;
            }
        }
        if (file_exists($path))
        {
            $info=getimagesize($path);
            if(empty($info))
                return null;
            $fun= 'imagecreatefrom'.image_type_to_extension($info[2], false);
            return call_user_func_array($fun,array($path));
        }
        $strInfo=getimagesizefromstring($path);
        if(!empty($strInfo))
            return imagecreatefromstring($path);
        return null;
    }

    //支持png透明的图片拼合
    private function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
    {
        $cut = imagecreatetruecolor($src_w, $src_h);
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
        imagedestroy($cut);
    }

    //获取字体文件
    private function getFont($font,$default=null)
    {
        $font=$font?$font:($default?$default:$this->config['default_font']);
        return $this->config['fonts'][$font];
    }

    //获取字体颜色(这里的alpha值是相反的0表示完全透明)
    private function getColor($color,$default=null)
    {
        $color=$color?$color:($default?$default:$this->config['default_color']);

        if(is_string($color))
        {
            if(0 === strpos($color, '#'))
            {
                $colorStr=substr($color, 1);
                //缩写表示法
                if(strlen($colorStr)==3||strlen($colorStr)==4)
                {
                    $tmpArr=str_split($colorStr);
                    $colorStr='';
                    for($i=0;$i<count($tmpArr);$i++)
                    {
                        $colorStr.=$tmpArr[$i].$tmpArr[$i];
                    }
                }
                $color = str_split($colorStr, 2);
                $color = array_map('hexdec', $color);
                $color[3]=isset($color[3])?$color[3]:255;
                $color[3]=127-ceil($color[3]*(127/255));
            }
            else
            {
                $color=explode(',',$color);
                $color[3]=isset($color[3])?$color[3]:1;
                $color[3]=127-ceil($color[3]*127);
            }
        }
        $color[3]=isset($color[3])?$color[3]:0;
        return $color;
    }

    //拆分字符串
    private function splitUnicode($str, $l = 0)
    {
        if ($l > 0)
        {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    //计算文本框盒模型
    private function calculateTextBox($fontSize,$fontAngle,$fontFile,$text)
    {
        $is=preg_match('/[a-zA-Z0-9+\-*\/=\\\?\(\)\{\}\[\]#&%$@!\'"\s\.\,]/',$text);
        $rect = imagettfbbox($fontSize,$fontAngle,$fontFile,$is?$text:'中');
        $minX = min(array($rect[0],$rect[2],$rect[4],$rect[6]));
        $maxX = max(array($rect[0],$rect[2],$rect[4],$rect[6]));
        $minY = min(array($rect[1],$rect[3],$rect[5],$rect[7]));
        $maxY = max(array($rect[1],$rect[3],$rect[5],$rect[7]));
        $rect2 = imagettfbbox($fontSize,$fontAngle,$fontFile,'中');//解决高度测量不一致问题
        $minY2 = min(array($rect2[1],$rect2[3],$rect2[5],$rect2[7]));
        $maxY2 = max(array($rect2[1],$rect2[3],$rect2[5],$rect2[7]));
        return array(
            "left"   => abs($minX) - 1,
            "top"    => abs($minY2) - 1,
            "width"  => $maxX - $minX,
            "height" => $maxY2 - $minY2,
            "box"    => $rect
        );
    }

    //替换变量
    private function replaceVars($text)
    {
        $vars=$this->config['vars'];
        foreach ($vars as $key=>$value)
        {
            $text=str_replace('{$'.$key.'}',$value,$text);
        }
        return preg_replace('/\{\$\w+\}/','',$text);
    }

    //绘制头像
    private function drawAvatar($data)
    {
        $width=$this->scaleSize($data['width']?$data['width']:76);
        $height=$this->scaleSize($data['height']?$data['height']:76);
        $x=$this->scaleSize($data['x']?$data['x']:0);
        $y=$this->scaleSize($data['y']?$data['y']:0);
        $this->config['avatar']=!empty($this->config['avatar'])?$this->config['avatar']:$this->config['default_avatar'];
        $avatarImg=$this->getImg($this->config['avatar']);

        if(empty($avatarImg))
        {
            $this->error='头像不存在';
            return false;
        }
        $avatarImg=$this->thumb($avatarImg,$width,$height,self::IMAGE_THUMB_CENTER);
        $newAvatar=imagecreatetruecolor($width,$height);
        $transColor=imagecolorallocatealpha($newAvatar,0,0,0,127);
        imagefill($newAvatar,0,0,$transColor);
        imageantialias($newAvatar,true);//抗锯齿 仅在与 GD 库捆绑编译的 PHP 版本中可用
        imagesettile($newAvatar,$avatarImg);
        imagefilledellipse($newAvatar,$width/2,$height/2,$width,$height,IMG_COLOR_TILED);
        $alpha=isset($data['alpha'])?$data['alpha']*100:100;
        $this->imagecopymerge_alpha($this->img, $newAvatar,$x, $y, 0, 0, $width, $height,$alpha);
        imagedestroy($newAvatar);
        imagedestroy($avatarImg);
        return true;
    }

    //获得一个左上角圆角
    private function getLtCorner($radius, $color_r='255', $color_g='255', $color_b='255')
    {
        // 创建一个正方形的图像
        $img = imagecreatetruecolor($radius, $radius);
        // 图像的背景
        $bgcolor = imagecolorallocate($img, $color_r, $color_g, $color_b);
        $fgcolor = imagecolorallocate($img, 0, 0, 0);
        imagefill($img, 0, 0, $bgcolor);
        // $radius,$radius：以图像的右下角开始画弧
        // $radius*2, $radius*2：已宽度、高度画弧
        // 180, 270：指定了角度的起始和结束点
        // fgcolor：指定颜色
        imagefilledarc($img, $radius, $radius, $radius * 2, $radius * 2, 180, 270, $fgcolor, IMG_ARC_PIE);
        // 将弧角图片的颜色设置为透明
        imagecolortransparent($img, $fgcolor);
        return $img;
    }

    //处理成圆角矩形
    private function cornerHandler($img,$radius)
    {
        $lw=imagesx($img);
        $lh=imagesy($img);
        $lt_corner=$this->getLtCorner($radius);
        // lt(左上角)
        imagecopymerge($img, $lt_corner, 0, 0, 0, 0, $radius, $radius, 100);
        // lb(左下角)
        $lb_corner = imagerotate($lt_corner, 90, 0);
        imagecopymerge($img, $lb_corner, 0, $lh - $radius, 0, 0, $radius, $radius, 100);
        // rb(右上角)
        $rb_corner = imagerotate($lt_corner, 180, 0);
        imagecopymerge($img, $rb_corner, $lw - $radius, $lh - $radius, 0, 0, $radius, $radius, 100);
        // rt(右下角)
        $rt_corner = imagerotate($lt_corner, 270, 0);
        imagecopymerge($img, $rt_corner, $lw - $radius, 0, 0, 0, $radius, $radius, 100);
    }

    //生成缩略图
    private function thumb($img,$width, $height, $type = self::IMAGE_THUMB_SCALE)
    {
        //原图宽度和高度
        $w = imagesx($img);
        $h = imagesy($img);
        $x=$y=0;
        /* 计算缩略图生成的必要参数 */
        switch ($type)
        {
            /* 等比例缩放 */
            case self::IMAGE_THUMB_SCALE:
                //原图尺寸小于缩略图尺寸则不进行缩略
                if($w < $width && $h < $height) return $img;
                //计算缩放比例
                $scale = min($width/$w, $height/$h);
                //设置缩略图的坐标及宽度和高度
                $x = $y = 0;
                $width  = $w * $scale;
                $height = $h * $scale;
                break;
            /* 居中裁剪 */
            case self::IMAGE_THUMB_CENTER:
                //计算缩放比例
                $scale = max($width/$w, $height/$h);
                //设置缩略图的坐标及宽度和高度
                $w = $width/$scale;
                $h = $height/$scale;
                $x = (imagesx($img) - $w)/2;
                $y = (imagesy($img) - $h)/2;
                break;
            /* 左上角裁剪 */
            case self::IMAGE_THUMB_NORTHWEST:
                //计算缩放比例
                $scale = max($width/$w, $height/$h);
                //设置缩略图的坐标及宽度和高度
                $x = $y = 0;
                $w = $width/$scale;
                $h = $height/$scale;
                break;
            /* 右下角裁剪 */
            case self::IMAGE_THUMB_SOUTHEAST:
                //计算缩放比例
                $scale = max($width/$w, $height/$h);
                //设置缩略图的坐标及宽度和高度
                $w = $width/$scale;
                $h = $height/$scale;
                $x = imagesx($img) - $w;
                $y = imagesy($img) - $h;
                break;
            /* 填充 */
            case self::IMAGE_THUMB_FILLED:
                //计算缩放比例
                if($w < $width && $h < $height){
                    $scale = 1;
                } else {
                    $scale = min($width/$w, $height/$h);
                }
                //设置缩略图的坐标及宽度和高度
                $neww = $w * $scale;
                $newh = $h * $scale;
                $posx = ($width  - $w * $scale)/2;
                $posy = ($height - $h * $scale)/2;
                //创建新图像
                $newimg = imagecreatetruecolor($width, $height);
                // 调整默认颜色
                $color = imagecolorallocate($newimg, 255, 255, 255);
                imagefill($newimg, 0, 0, $color);
                //裁剪
                imagecopyresampled($newimg, $img, $posx, $posy, $x, $y, $neww, $newh, $w, $h);
                imagedestroy($img); //销毁原图
                return $newimg;
            /* 固定 */
            case self::IMAGE_THUMB_FIXED:
                $x = $y = 0;
                break;
            default:
                //do nothing
        }
        /* 裁剪图像 */
        return $this->crop($img,$w, $h, $x, $y, $width, $height);
    }

    //裁切图片,返回裁切后的新图
    private function crop($img,$w, $h, $x = 0, $y = 0, $width = null, $height = null)
    {
        //设置保存尺寸
        empty($width)  && $width  = $w;
        empty($height) && $height = $h;
        //创建新图像
        $newimg = imagecreatetruecolor($width, $height);
        // 调整默认颜色
        $color = imagecolorallocate($newimg, 255, 255, 255);
        imagefill($newimg, 0, 0, $color);
        //裁剪
        imagecopyresampled($newimg, $img, 0, 0, $x, $y, $width, $height, $w, $h);
        imagedestroy($img); //销毁原图
        return $newimg;
    }

    private function drawQrcode($data)
    {
        $width=$this->scaleSize($data['width']?$data['width']:122);
        $height=$this->scaleSize($data['height']?$data['height']:122);
        $x=$this->scaleSize($data['x']?$data['x']:0);
        $y=$this->scaleSize($data['y']?$data['y']:0);
        $alpha=isset($data['alpha'])?$data['alpha']*100:100;
        //有URL的话则直接生成
        if(!empty($this->config['qrcode_url'])){
            $qrcodeImg=$this->createQrcode($this->config['qrcode_url'],$width,$this->config['logo']);
        } else {
            $qrcodeImg = $this->getImg($this->config['qrcode']);

            if(empty($qrcodeImg)) {
                $this->error='二维码图片不存在';
                return false;
            }
        }
        $qrcodeImg=$this->thumb($qrcodeImg,$width,$height,self::IMAGE_THUMB_CENTER);
        $this->imagecopymerge_alpha($this->img, $qrcodeImg,$x, $y, 0, 0, $width, $height,$alpha);
        imagedestroy($qrcodeImg);
        return true;
    }

    //创建二维码
    private function createQrcode($text,$width,$logo='')
    {
        import('@.ORG.phpqrcode');
        $pxPath=$this->config['tmp_path'].'/qrcode';
        if(!file_exists($pxPath))
            mkdir($pxPath,0777,true);
        $pxPath.='/'.uniqid().'.png';
        QRcode::png($text,$pxPath,QR_ECLEVEL_Q,1,2);//越高二维码越密集，但是可污染的区域越大
        $pxInfo=getimagesize($pxPath);
        $pxNum=ceil($width/$pxInfo[0]);
        QRcode::png($text,$pxPath,QR_ECLEVEL_Q,$pxNum,2);
        $qrcodeImg=imagecreatefrompng($pxPath);
        if(!empty($logo))
        {
            $lw=$lh=round($width*0.15);
            $logoImg=$this->getImg($logo);
            if(!empty($logoImg))
            {
                $radius=10;
                $bgW=$lw+10;
                $bgH=$lh+10;
                $logoBg=imagecreatetruecolor($bgW,$bgH);
                $logoBgColor=imagecolorallocate($logoBg,0,0,0);
                imagefill($logoBg,0,0,$logoBgColor);
//                imagecolortransparent($logoBg, $logoBgColor);
                $this->cornerHandler($logoBg,$radius);
                $bgx=$bgy=($width-$bgW)/2;
                $this->imagecopymerge_alpha($qrcodeImg,$logoBg,$bgx,$bgy,0,0,$bgW,$bgH,100);
                imagedestroy($logoBg);
                $logoImg=$this->thumb($logoImg,$lw,$lh,self::IMAGE_THUMB_CENTER);
                $this->cornerHandler($logoImg,$radius);
                $dstx=$dsty=($width-$lw)/2;
                $this->imagecopymerge_alpha($qrcodeImg,$logoImg,$dstx,$dsty,0,0,$lw,$lh,100);
                imagedestroy($logoImg);
            }
        }
        unlink($pxPath);
        return $qrcodeImg;
    }

    public function getError()
    {
        return $this->error;
    }

    //保存图片
    public function save($path)
    {
        if(!file_exists(dirname($path)))
        {
            mkdir(dirname($path),0777,true);
        }
        imagejpeg($this->img,$path);
    }

    //输出图片
    public function output()
    {
        header('content-type:image/jpg');
        imagejpeg($this->img);
    }

    function getUrlTopDomain($url){
        $domain_array = parse_url($url);
        $host = strtolower($domain_array['host']);
        $two_suffix = array('.com.cn','.gov.cn','.net.cn','.org.cn','.ac.cn');
        foreach($two_suffix as $key=>$value){
            preg_match('#(.*?)'.$value.'$#',$host,$match_arr);
            if(!empty($match_arr)){
                $match_array = $match_arr;
                break;
            }
        }
        $host_arr = explode('.',$host);
        if(!empty($match_array)){
            $host_arr_last1 = array_pop($host_arr);
            $host_arr_last2 = array_pop($host_arr);
            $host_arr_last3 = array_pop($host_arr);

            return $host_arr_last3.'.'.$host_arr_last2.'.'.$host_arr_last1;
        }else{
            $host_arr_last1 = array_pop($host_arr);
            $host_arr_last2 = array_pop($host_arr);
            return $host_arr_last2.'.'.$host_arr_last1;
        }
    }
}