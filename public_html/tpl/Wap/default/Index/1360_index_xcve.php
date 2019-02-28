
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- [portable options] -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0;"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>{pigcms{$tpl.wxname}</title>
    <!-- [loading stylesheets] -->
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}tpl/threetemp/event/css/style.css" media="all">
</head>

<body>
<div class="weimob-content" style="min-height:456px;">
    <div class="weimob-list">
    <volist name="info" id="vo">
        <a href="<if condition="$vo['url'] eq ''">{pigcms{:U('Wap/Index/lists',array('classid'=>$vo['id'],'token'=>$vo['token']))}<else/>{pigcms{$vo.url|htmlspecialchars_decode}</if>" class="weimob-list-box">
                <div class="weimob-list-item">
                    <div class="weimob-list-item-line">
                        <div class="weimob-list-item-title">{pigcms{$vo.name}</div>
                        <div class="weimob-list-item-img">
                            <img src="{pigcms{$vo.img}" style="width:100%">
                        </div>
                        <div class="weimob-list-item-summary">{pigcms{$vo.info}</div>
                    </div>
                    <div class="weimob-list-more">
<?php  if($pageid != 1){
?>
                        <div class="weimob-list-item-more">阅读全文</div>
                        <div style="color:#666;"></div>
<?php
}
?>
                    </div>
                </div>
        </a>
    </volist>
            </div>
</div>
<!--分页————ＢＥＧＩＮ-->
<?php  if($pageid != 1){
?>
<div class="list_page">
{pigcms{$page}
</div>
<?php
}
?>
<!--分页————ＥＮＧ-->
<div class="authorization" >
    <a href=""  >版权所有：{pigcms{$homeInfo.copyright}</a>
</div>
</body>
</html>