<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="white">
<meta name="format-detection" content="telephone=no">
<title>下载说明</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/blue/app/css/base_wap.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/blue/app/css/download.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/blue/app/css/idangerous.swiper.css">
<script type="text/javascript" src="{pigcms{$config.site_url}/tpl/Static/blue/app/js/jquery-1.7.1.min.js"></script>
<style type="text/css">
body{
    padding: 10px 10px 0px 10px;
}
.content{
    text-align:left;font-size: 14px;width:98%;
}
.m-t20{
    margin-top: 20px;
}
.m-t10{
    margin-top:10px;
}
.m-t5{
    margin-top:5px;
}
.color1{
    color:#333;
}
.color2{
    color:#999;
}
.color3{
    color:black;
}
.center{
    text-align:center;
}
.color4{
    color:red;
}
</style>
</head>
<body>
    <div class="content">
        <h1 class="color3 center">ios9下载说明</h1>
        <div class="m-t10">本IOS版为客户演示站体验版，仅供客户演示站体验使用，非用户正式使用版。所以需要通过浏览器下载安装后启动信任证书，请大家根据以下提示进行操作。</div><br>
        <div class="m-t5 color4">※ APP Store用户版不需要此流程，不会影响下载使用流程。</div>
        <h3  class="color3 m-t20">设置：</h3>
        <div class="m-t10 color1">点击下方"下载应用"按钮下载软件，然后手动回到桌面，打开软件会弹出"未受信任的企业级开发者"，根据手机ios系统版本，使用以下不同步骤。</div>
        <div class="m-t10 color2">苹果手机9.2系统操作步骤：</div>
        <div class="m-t5 color1">设置->通用->设备管理->找到对应的描述文件(以Hefei Faramita开头)->点击信任</div>
        <div class="m-t10 color2">苹果手机9.0系统操作步骤：</div>
        <div class="m-t5 color1">设置->通用->描述文件->找到对应的描述文件(以Hefei Faramita开头)->点击信任</div>
    </div>
<script>
$(function(){
    $("button").click(function(){
        var url =window.location.search;
        if (url.indexOf("?") != -1) {
            var str = url.substr(5);
            location.href= decodeURIComponent(str);
        }
    })
})
</script>
<footer>
    <button url=""><i></i>下载应用</button>
</footer>
</body>
</html>