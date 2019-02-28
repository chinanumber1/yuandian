<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
    <meta content="no-cache" http-equiv="pragma">
    <meta content="0" http-equiv="expires">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{pigcms{$tpl.wxname}</title>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}tpl/threetemp/teacher/css/style.css">
</head>
<body>
<script type="text/javascript">
function click_href(id,token,url){
  var lohref='{pigcms{:U("Wap/Index/lists")}&classid='+id+'&token='+token;
    if(url.length>0){
      location=url;
    }else{
      location=lohref;
    }
}
</script>
<volist name="info" id="vo">
<section>
       <div class="content" onclick="click_href('{pigcms{$vo.classid}','{pigcms{$vo.token}','{pigcms{$vo.url}');">
          <div class="icon">
              <span class="font_size">{pigcms{$vo.name}</span>
              <div class="clear"></div>
          </div>
      <img src="{pigcms{$vo.img}"  width="100%">
           <div style="padding: 10px; line-height: 20px;">
               <span>{pigcms{$vo.info}</span>
           </div>
       </div>
</section>
</volist>
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
<div class="gotop" onclick="window.scrollTo(0,0);">
  <span></span>
  <span></span>
</div>
</body>
</html>