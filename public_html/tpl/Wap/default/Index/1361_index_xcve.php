<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- [portable options] -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0;" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Mobile Devices Support @begin -->
    <meta content="textml; charset=UTF-8" http-equiv="Content-Type">
    <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
    <meta content="no-cache" http-equiv="pragma">
    <meta content="0" http-equiv="expires">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes"> <!-- apple devices fullscreen -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- Mobile Devices Support @end -->
    <title>{pigcms{$tpl.wxname}</title>
    <!-- [loading stylesheets] -->
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}tpl/threetemp/hospital/css/style.css" media="all">
</head>
<body>
<div class="weimob-content" style="min-height:456px;">
    <div class="weimob-list">
    <volist name="info" id="vo">
            <a href="<if condition="$vo['url'] eq ''">{pigcms{:U('Wap/Index/lists',array('classid'=>$vo['id'],'token'=>$vo['token']))}<else/>{pigcms{$vo.url|htmlspecialchars_decode}</if>" class="weimob-list-box">

                <?php if($pageid ===0){
                ?>
                <div class="weimob-list-sau">
                <span class="mday"><b class="day">{pigcms{$vo.createtime|date='d',###}</b>/{pigcms{$vo.uptatetime|date='m',###}</span>
                <span class="years"> 20{pigcms{$vo.uptatetime|date='y',###}</span>
                </div>
                <?php
                }else{
                ?>
                <img src="{pigcms{$vo.img}" style="width:50px;height: 50px;">
                <?php
                }
                ?>

                <div class="weimob-list-item">
                    <div class="weimob-list-item-line">
                        <div class="weimob-list-item-title">{pigcms{$vo.name}</div>
                        <div class="weimob-list-item-img">
                            <img src="{pigcms{$vo.img}">
                        </div>
                        <div class="weimob-list-item-summary">{pigcms{$vo.info}</div>
                    </div>
                    <div class="weimob-list-more">
                    <?php  if($pageid === 0){
                    ?>
                        <div class="weimob-list-item-more">
                            阅读全文
                        </div>
                    <?php
                    }
                    ?>
                        <div class="weimob-list-item-more-icon icon-arrow-r">
                        </div>
                    </div>
                </div>
            </a>
    </volist>
            </div>
</div>
<!--分页————ＢＥＧＩＮ-->
<div class="list_page">
<?php  if($pageid === 0){
?>
<div class="list_page">
{pigcms{$page}
</div>
<?php
}
?>
</div>
<!--分页————ＥＮＧ-->
<div class="authorization" >
    <a href="#"  >版权所有：{pigcms{$homeInfo.copyright}</a>
</div>
</body>
</html>