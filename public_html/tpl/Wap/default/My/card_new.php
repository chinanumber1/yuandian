<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>商家会员卡</title>
<link href="{pigcms{$static_path}css/css_whir.css" rel="stylesheet"/>
<script src="{pigcms{$static_path}js/jquery.min1.8.js"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
 <style type="text/css">
  .Disable{background:url({pigcms{$static_path}images/ssbj_03.png);  width: 100%; height: 100% ;border-radius: 10px; position: absolute; top: 10px; left:20px;
    padding: 8px 10px; text-align: center; font-size: 18px; color:#fff; font-weight: bold;  }
 </style>

<body>

  <section class="Membership">
    <ul>
		<if condition="$card_list">
			<volist name="card_list" id="vo">
			  <li>
			  <a href="{pigcms{:U('My_card/merchant_card',array('mer_id'=>$vo['mer_id']))}">
				<div class="Membership_n" style="background: url({pigcms{$vo.bg}) center; background-size: 100%">
				  <div class="Member_top">
					<if condition="$vo['merchant_logo']">
						<img src="{pigcms{$vo.merchant_logo}" style="border:none;"/>
					<else/>
						<img src="{pigcms{:C('config.site_url')}/upload/merchant/{pigcms{$vo.mer_pic}" style="border:none;"/>
					</if>
					<h2>{pigcms{$vo.name}</h2>
				  </div>
				  <div class="Member_Price" style="margin:13% 0;">
					<span class="price_l"><i>{pigcms{$vo.money}</i>元</span>
					<if condition="$vo.discount neq 0 AND $vo.discount neq 10"><span class="pricl_r"><i>{pigcms{$vo.discount}</i>折</span></if>
				  </div>
				  <div class="Member_end">
					<span>您有{pigcms{$vo.coupon_count}张优惠券</span>
					<span class="fr" style="color:{pigcms{$vo.numbercolor}">NO.{pigcms{$vo.id}</span>
				  </div>
				</div>
				<if condition="$vo.usercard_status eq 0 OR $vo.card_status eq 0 ">
					<div class="Disable" >此会员卡已禁用！</div>
				</if>
			  </a>  
			  </li>
			</volist>
		</if>
    </ul>
  </section>
</body>

<script>
	$('.Membership_n').css({height:($(window).width()-40)*630/1052+'px','padding':0,'position':'relative','background-size':'100% 100%','background-repeat':'no-repeat'});
	$('.Membership_n .Member_top').css({'padding-top':$('.Membership_n').height()*58/630+'px','width':'100%'});

	$(".Disable").each(function(){
		var w=$(".Membership_n").width();
		var h=$(".Membership_n").height();
		$(this).height(h).width(w).css({"line-height":+h+"px"})
	});

</script>
</html>