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
</head>
<if condition="$error lt 0">
<body id="cardunion" class="mode_webapp2">
<else/>
<body id="card" class="mode_webapp">
</if>
<if condition="$error lt 0">
<div class="error" style="margin:50px auto 20px auto;text-align:center"><img src="{pigcms{$static_path}card/images/error.jpg" /></div>
<div style="font-size:14px;text-align:center"><php>if($error==-1){</php>会员卡暂时缺货<php>}elseif($error==-2){</php>您的{pigcms{$config['score_name']}不够<php>}elseif($error==-3){</php>领取此会员卡需要{pigcms{$thisCard.miniscore}{pigcms{$config['score_name']}，而您只有{pigcms{$userScore}{pigcms{$config['score_name']}<php>}elseif($error==-4){</php>还没领取会员卡，现正在跳转<php>}</php></div>
<include file="Card:bottom"/>
<else/>
<div id="overlay"></div>
<div class="cardcenter">
<div class="card"><img src="<?php if($thisCard['diybg']!=''){?>{pigcms{$thisCard.diybg}<?php }else{?>{pigcms{$thisCard.bg}<?php }?>" class="cardbg" />
<if condition="$card.logo"><img id="cardlogo" class="logo" src="{pigcms{$thisCard.logo}"></if>
<h1 style="color:{pigcms{$card.vipnamecolor}">{pigcms{$thisCard.cardname}</h1>
<strong class="pdo verify" style="color:{pigcms{$card.numbercolor}"><span id="cdnb" ><em>会员卡号</em>{pigcms{$thisMember.number}</span></strong> </div>
<p class="explain"><span>{pigcms{$thisCard.msg}</span></p>
<div class="window" id="windowcenter">
<div id="title" class="wtitle">领卡信息<span class="close" id="alertclose"></span></div>
<div class="content">
<div id="txt"></div>
<p>
<input name="truename" value=""  class="px" id="truename"  type="text" placeholder="请输入您的姓名">
</p>
<p>
<input name="tel"  class="px" id="tel"  value=""  type="number"  placeholder="请输入您的电话">
</p>
<input type="button" value="确 定" name="确 定" class="txtbtn" id="windowclosebutton">
</div>
</div>
</div>


<div class="cardexplain" >
<style>
		.button{width:100%;margin-bottom:10px;}
		.button .b1,.button .b2{width:49%;text-align:center;font-weight:bold;text-align:center;line-height:40px;background:#1cc200; border: 1px solid #179f00;border-radius: 5px;color:#fff;}
		.button a:hover{background:#179f00}
		.button .b1{margin-right:2px;float:left;}
		.button .b2{float:right;}
</style>


<div class="jifen-box">
<ul class="zongjifen">
<li><a href="/wap.php?g=Wap&c=Card&a=expense&token={pigcms{$token}&cardid={pigcms{$card.id}">
<div class="fengexian">
<p>消费记录</p>
<span>{pigcms{$userInfo['expensetotal']}元</span></div>
</a></li>
<li><a href="/wap.php?g=Wap&c=Card&a=signscore&token={pigcms{$token}&cardid={pigcms{$card.id}">
<div class="fengexian">
<p>剩余{pigcms{$config['score_name']}</p>
<span>{pigcms{$userScore}分</span></div>
</a></li>
<li><a href="/wap.php?g=Wap&c=Card&a=payRecord&token={pigcms{$token}&cardid={pigcms{$card.id}&month={pigcms{:date('n')}">
<p>我的余额</p>
<span>{pigcms{$userInfo['balance']}元</span></a></li>
</ul>
<div class="clr"></div>
</div>


<ul class="round" id="notice">
<!--li><a href="/wap.php?g=Wap&c=Card&a=coupon&token={pigcms{$token}&cardid={pigcms{$card.id}&type=1"><span>我的优惠券<?php if ($couponCount1>0){echo '<em class="ok">'.$couponCount1.'</em>';}else{echo '<em class="error">0</em>';}?></span></a></li-->
<li><a href="/wap.php?g=Wap&c=Card&a=coupon&token={pigcms{$token}&cardid={pigcms{$card.id}&type=2"><span>我的优惠券<?php if ($couponCount2>0){echo '<em class="ok">'.$couponCount2.'</em>';}else{echo '<em class="error">0</em>';}?></span></a></li>
<li><a href="/wap.php?g=Wap&c=Card&a=integral&token={pigcms{$token}&cardid={pigcms{$card.id}"><span>我的礼品券<?php if ($integralCount>0){echo '<em class="ok">'.$integralCount.'</em>';}else{echo '<em class="error">0</em>';}?></span></a></li>
<!--li><a href="/wap.php?g=Wap&c=Card&a=previlege&token={pigcms{$token}&cardid={pigcms{$card.id}"><span>会员特权<?php if ($previlegeCount>0){echo '<em class="ok">'.$previlegeCount.'</em>';}else{echo '<em class="error">0</em>';}?></span></a></li-->          	
</ul>

<ul class="round"  id="powerandgift">
<li><a href="/wap.php?g=Wap&c=Card&a=payRecord&token={pigcms{$token}&cardid={pigcms{$card.id}&month={pigcms{:date('n')}"><span>交易记录</span></a></li>
<li><a href="/wap.php?g=Wap&c=Card&a=signscore&token={pigcms{$token}&cardid={pigcms{$card.id}"><span>签到赚{pigcms{$config['score_name']}<?php if ($todaySigned){echo '<em class="ok">今日已签到</em>';}else{echo '<em class="error">今日未签到</em>';}?></span></a></li>
<li><a href="{pigcms{:U('Userinfo/index',array('token'=> $token,'cardid' => (int)$_GET['cardid'],'redirect'=>'Card/card|cardid:'.(int)$_GET['cardid']))}"><span>个人资料</span></a></li>
</ul>

</div>

<include file="Card:cardFooter"/>
<include file="Card:share"/>
</if>

</body>
</html>
