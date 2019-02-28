<!DOCTYPE html>
 <html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>退货状态记录</title>
	<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
	<meta name="description" content="{pigcms{$config.seo_description}" />
    <link href="{pigcms{$static_path}shop/css/order_detail.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
</head>

<body>
<section class="public">
    <a class="return link-url" href="javascript:window.history.go(-1);"></a>
    <div class="content">退货状态记录</div>
</section>
<div class="h44"></div>
<section class="g_details">
    <div class="orders_list">
        <ul>
            <volist name="status" id="vo">
            <li>
                <div class="time">{pigcms{$vo.dateline|date="Y-m-d H:i",###}</div>
                <div class="p18">
                    <div class="con">
                        <if condition="$vo['status'] eq 0"> <h2>退货申请状态</h2> <p>商家正在审核中 <php>if (!empty($vo['note'])) { </php>,<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></p> 
                        <elseif condition="$vo['status'] eq 1"/> <h2>退货申请状态</h2> <p>商家审核通过 <php>if (!empty($vo['note'])) { </php>,<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></p>
                        <elseif condition="$vo['status'] eq 2"/> <h2>退货申请状态</h2> <p>商家拒绝退货 <php>if (!empty($vo['note'])) { </php>,<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></p>
                        <elseif condition="$vo['status'] eq 3"/> <h2>退货申请状态</h2> <p>您重新申请退货 <php>if (!empty($vo['note'])) { </php>,<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></p>
                        <elseif condition="$vo['status'] eq 4"/> <h2>退货申请状态</h2> <p>您取消申请退货 <php>if (!empty($vo['note'])) { </php>,<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></p>
                        </if>
                    </div>
                </div>
            </li>
            </volist>
        </ul>
    </div>
</section>
</body>
</html>