<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <title>访客开门</title>
    <meta name="viewport"
          content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name='apple-touch-fullscreen' content='yes'/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
</head>
<style>
    .open {
        margin-top: 30%;
        padding: 20px 20% 40px 20%;
    }

    .erweima {
        padding: 10px 0px;
        text-align: center;
    }

    .erweima img {
        width: 250px;
        height: 250px
    }

    .open p {
        text-align: center
    }

    .open span {
        font-weight: bold;
    }
    .erweima{
        position: fixed;
        left: 50%;
        top: 50%;
        bottom: auto;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        width: 274px;
        box-sizing: border-box;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        background: transparent;
        -webkit-transition: -webkit-transform .3s;
        transition: -webkit-transform .3s;
        transition: transform .3s;
        transition: transform .3s, -webkit-transform .3s;
    }
</style>
<body>
<!--<header class="pageSliderHide">
    <div id="backBtn"></div>
    访客开门
</header>-->

       <!-- <span>您目前有以下门禁的开门权限,开门时效为20分钟</span>-->
        <div class="erweima">
             <img src=""/>
            <p style=" color: #5f5f5f;padding-top: 10px;">长按扫码开门</p>
        </div>


<script type="text/javascript">
    window.onload = function () {
        var id='{pigcms{$_GET.door_share_id}';
        var url='/index.php?g=Index&c=Recognition_wxapp&a=see_qrcode&type=doorshare&img=1&id='+id;
        var urls=window.location.href.slice(0,window.location.href.indexOf('com')+3)+url;
        $(" .erweima img").attr("src",urls);

    }
</script>

</body>
</html>