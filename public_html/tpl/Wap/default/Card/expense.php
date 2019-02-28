<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{pigcms{$thisCard.cardname}</title>
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="{pigcms{$static_path}card/style/style.css" rel="stylesheet" type="text/css">
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
<style type="text/css">
.window {
width:240px;
position:absolute;
display:none;
margin:-50px auto 0 -120px;
padding:2px;
top:0;
left:50%;
border-radius:0.6em;
-webkit-border-radius:0.6em;
-moz-border-radius:0.6em;
background-color: rgba(255, 0, 0, 0.5);
-webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
-moz-box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
-o-box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
font:14px/1.5 Microsoft YaHei,Helvitica,Verdana,Arial,san-serif;
z-index:10;
bottom: auto;
}
.window .content {
overflow:auto;
padding:10px;
    color: #222222;
    text-shadow: 0 1px 0 #FFFFFF;
border-radius: 0 0 0.6em 0.6em;
-webkit-border-radius: 0 0 0.6em 0.6em;
-moz-border-radius: 0 0 0.6em 0.6em;
}
.window #txt {
min-height:30px;font-size:20px; line-height:22px; color:#FFF; text-align:center;
}
.jifen-box {
	margin:10px 5px;
}
</style>
</head>
<body id="cardintegral" class="mode_webapp">
<div class="qiandaobanner"><a href="javascript:history.go(-1);"><img src="{pigcms{$thisCard.shopping}" ></a> </div>
<div class="jifen-box">
<ul class="zongjifen">
<li><a href="/wap.php?g=Wap&c=Card&a=expense&token={pigcms{$token}&cardid={pigcms{$thisCard.id}">
<div class="fengexian">
<p>消费记录</p>
<span>{pigcms{$userInfo['expensetotal']}元</span></div>
</a></li>
<li><a href="/wap.php?g=Wap&c=Card&a=signscore&token={pigcms{$token}&cardid={pigcms{$thisCard.id}">
<div class="fengexian">
<p>剩余{pigcms{$config['score_name']}</p>
<span>{pigcms{$userScore}分</span></div>
</a></li>
<li><a href="/wap.php?g=Wap&c=Card&a=signscore&token={pigcms{$token}&cardid={pigcms{$thisCard.id}">
<p>签到{pigcms{$config['score_name']}</p>
<span>{pigcms{$userInfo['sign_score']}分</span></a></li>
</ul>
<div class="clr"></div>
</div>

<div class="jifen-box header_highlight">
<!-- <div style="font-size:12px;text-align:center;margin-bottom:10px;">{pigcms{$config['score_name']}和线下交易记录</div> -->
<div class="tab month_sel"> <span class="title">查看每月{pigcms{$config['score_name']}和线下交易记录详情
<p>点击这里选择其他月份</p>
</span> </div>
<select onChange="dourl2(this.value)" class="month">
<option  value="1">1月</option>
<option value="2">2月</option>
<option value="3">3月</option>
<option value="4">4月</option>
<option value="5">5月</option>
<option value="6">6月</option>
<option value="7">7月</option>
<option value="8">8月</option>
<option value="9">9月</option>
<option value="10">10月</option>
<option value="11">11月</option>
<option value="12">12月</option>
</select>
<div class="accordion_child">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="integral_table">
<thead>
<tr>
<th>日期</th>
<th>消费(元)</th>
<th>{pigcms{$config['score_name']}</th>
<th>类型</th>
</tr>
</thead>
<tbody>
<volist name="records" id="c">                 
<tr>
<td>{pigcms{$c.time|date='m月d日',###}</td>
<td><span class="wqian">{pigcms{$c.expense}</span></td>
<td><?php if ($c['score']>0){echo '+'.$c['score'];}else{echo $c['score'];}?></td>
<td><?php if ($c['cat']==2){echo '兑换';}elseif($c['cat']==3){echo '赠送';}elseif($c['cat']==98){echo '分享';}else{echo '消费';}?></td>
</tr>
</volist>
</tbody>
</table>
</div>
</div>

<div class="window" id="windowcenter">
<div class="content">
 <div id="txt"></div>
</div>

</div>

<a href="/wap.php?g=Wap&c=Card&a=payRecord&token={pigcms{$token}&cardid={pigcms{:intval($_GET['cardid'])}&month={pigcms{:date('n')}">
	<div class="jifen-box" style="text-align:center;font-size:12px;">
		会员卡交易记录
	</div>
</a>

<script>
function dourl(m){
	location.reload();
}
function dourl2(m){
	location.href= '/wap.php?g=Wap&c=Card&a=expense&token={pigcms{$token}&cardid={pigcms{$thisCard.id}&month='+m;
}
</script>

<script type="text/javascript"> 

function alert(title){ 
$("#windowcenter").slideToggle("slow"); 
$("#txt").html(title);
setTimeout('$("#windowcenter").slideUp(500)',2000);
} 

</script>
<include file="Card:cardFooter"/>
<include file="Card:share"/>
</body>
</html>
