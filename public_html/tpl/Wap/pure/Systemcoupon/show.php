<!DOCTYPE html>
<html style="font-size: 33.0833px;">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
  <title>{pigcms{$coupon.name}</title> 
  <meta charset="utf-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" /> 
  <meta name="apple-mobile-web-app-capable" content="yes" /> 
  <meta name="apple-mobile-web-app-status-bar-style" content="black" /> 
  <meta name="format-detection" content="telephone=no" /> 
  <meta http-equiv="X-UA-Compatible" content="edge" /> 
  <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
  <link rel="stylesheet" href="{pigcms{$static_path}coupon/css/global.css" />
  <link rel="stylesheet" href="{pigcms{$static_path}coupon/css/red_coupon.css" /> 
  <style>
    body {
        background: #ff3333;
    }
	#tips {
		display: none;
		font-size: .26rem;
		background-color: #FFF6E0;
		color: #D78900;
		border-bottom: 1px solid #FFEBC8;
		text-align: center;
		padding: .2rem;
		line-height: 1.4;
	}
	.msgbox {
		padding: .8rem 7.7%;
		border-radius: .3rem;
		margin: 0 auto;
		background: rgba(255,255,255,.85);
		background-position: 5% center;
		background-repeat: no-repeat;
		background-size: 30.35%;
		min-height: 2rem;
		width: 8rem;
		text-align:center;
		word-break: break-all;
		overflow: hidden;
	}

	.msgbox p{
		margin:0 auto;
	}
	.had_pull{
		background:url({pigcms{$static_path}coupon/img/hadpull.png) 4.5rem .28rem no-repeat;
		background-size:75px;
	}
	
	.no_coupon{
		background:url({pigcms{$static_path}coupon/img/no_coupon.png) 4.5rem .28rem no-repeat;
		background-size:75px;
		
	}
	.verify{
		margin-top: 5px;
		width: 88%;
	}
	
</style> 
<script>
    (function () {
        function o() {
            document.documentElement.style.fontSize = (document.documentElement.clientWidth > 414 ? 414 : document.documentElement.clientWidth) / 12 + "px"
        }
        var e = null;
        window.addEventListener("resize", function () {
            clearTimeout(e), e = setTimeout(o, 300)
        }, !1), o()
    })(window);
</script>
<script type="text/javascript">
	var international_phone = {pigcms{$config.international_phone|intval=###};
	$(document).ready(function() {
		<if condition="$coupon.has_get gt 0">
			$('#buy_now').show();
		</if>
		<if condition="$msg">
			var msg = '{pigcms{$msg}';
			alert(msg);
		</if>
		$('.result-inner-share').click(function(event) {
			<if condition="$unlogin_phone">
				var phone={pigcms{$unlogin_phone};
			<else />
				var phone='{pigcms{$phone}';
			</if>
			$.post("{pigcms{:U('Systemcoupon/had_pull')}", {coupon_id: {pigcms{$_GET['coupon_id']},phone:phone}, function(data, textStatus, xhr) {	
				if(!data.login&&data.has_get==1){
					alert('您已经领取一张了，登录后才可以再领取！');
					location.href="{pigcms{:U('Login/index')}";
				}else{
					switch(data.error_code)
					{	
						case 0:
							$('.share-infor').removeClass('no_coupon');
							$('.share-infor').addClass('had_pull');
							if(data.can_get!=0){
								
								if(!data.login){
									$('.info').html('你还可以领 '+data.can_get+'张优惠券,快点优惠券领取 <br>'+phone);
								}else{
									$('.info').html('你还可以领 '+data.can_get+'张优惠券,快点优惠券领取');
								}
							}else{
								$('.info').html('优惠券已经放入账户，快去消费！');
							}
							alert("领取优惠券成功！");
							break;
						case 1:
							$('.share-infor').removeClass('no_coupon');
							$('.share-infor').removeClass('had_pull');
							$('.info').html('');
							alert("领取优惠券发生错误！");
							break;
						case 2:
							$('.share-infor').removeClass('no_coupon');
							$('.share-infor').removeClass('had_pull');
							$('.info').html('');
							alert("该优惠券已过期！");
							break;
						case 3:
							$('.share-infor').removeClass('had_pull');
							$('.share-infor').addClass('no_coupon');
							$('.info').html('');
							alert("该优惠券已被领完！");
							break;
						case 4:
							$('.info').html('');
							alert("该优惠券只允许新用户领取！");
							break;
						case 5:
							$('.share-infor').removeClass('no_coupon');
							$('.share-infor').addClass('had_pull');
							if(!data.login){
								$('.info').html('优惠券已经放入账户，快去消费！<br>'+phone);
							}else{
								if(data.has_get!=0&&data.error_code!=4){
									$('.info').html('优惠券已经放入账户，快去消费！');
								}
							}
							alert("不能再领取了！");
							break;
						}
				}
			},"json");
		});
	});
	
	function had_pull(){
		var phone=$('input[name="phone"]').val();
		var verify=$('input[name="verify"]').val();
		var verify_type=$('input[name="verify_type"]').val();
		if(!international_phone && !/^[0-9]{11}$/.test(phone)){
			alert('请输入11位数字的手机号码','login-phone');
			return false;
		}
		$.post("{pigcms{:U('Systemcoupon/had_pull')}", {coupon_id: {pigcms{$_GET['coupon_id']},phone:phone,verify:verify,verify_type:verify_type}, function(data, textStatus, xhr) {
			if(data.dom_id=='verify'){
							alert(data.msg);
						}
			if(!data.login&&data.has_get>0){
				$('.share-infor').addClass('had_pull');
				alert('您已经领取了，登录后才可以再领取！');
				location.href="{pigcms{:U('Login/index')}";
			}else{
				
				switch(data.error_code)
				{	
					case 0:
						alert("领取成功！");
						break;
					case 1:
						alert("无法领取该优惠券！");
						break;
					case 2:
						alert("该优惠券已过期！");
						break;
					case 3:
						alert("该优惠券已被领完！");
						break;
					case 4:
						alert("该优惠券只允许新用户领取！");
						
						break;
					case 5:
						alert("不能再领取了！");
						break;
				}
				window.location.reload();
				
			}
			
		},"json");
	}
	function reg_fleshVerify(url){
		var time = new Date().getTime();
		$('#reg_verifyImg').attr('src',url+"&time="+time);
	}
</script>

</head> 
 <body class="i" style="padding-bottom: 0px;"> 
  <div style="height:0px;overflow:hidden;"> 
   <img src="{pigcms{$coupon.img_coupon}" /> 
  </div> 
  <div class="wrapper"> 
   <div class="t-banner">
    <img src="{pigcms{$coupon.img_coupon}" alt="" />
   </div> 
  
   <if condition="($phone OR $unlogin_phone OR ($coupon['had_pull'] eq $coupon['num']))">
	<section class="content2">
	
        <div class="result resulth5">
			<div class="result-inner-share">
				<div class="share-sort"><i>优惠券</i></div>
				<div class="share-infor <if condition='$coupon.has_get gt 0'>had_pull<elseif condition="$coupon['had_pull'] eq $coupon['num']" />no_coupon</if>">
					<div class="number"><em class="rmb">￥</em><i>{pigcms{$coupon.discount}</i></div>
					<div class="classify">在线支付专享，满{pigcms{$coupon.order_money}元可用</div>
				</div>
			</div>
        </div>
		
		
    </section>
	
	<else />
	<section class="content1">
		<div class="opr"> 
		<input id="phone-input" type="text" name="phone" class="borderradius-3" maxlength="11" placeholder="请输入您的手机号" />
		<div class="verify">
		
			<input type="hidden" name="verify_type" value="nosms"/>
			<input class="borderradius-3" type="text" id="reg_verify" style="width:60%;" maxlength="4" name="verify" placeholder="请输入验证码"/>&nbsp;&nbsp;		
			<img src="{pigcms{:U('Systemcoupon/verify',array('type'=>'reg'))}" id="reg_verifyImg" onclick="reg_fleshVerify('{pigcms{:U('Systemcoupon/verify',array('type'=>'reg'))}')" title="刷新验证码" alt="刷新验证码"/>&nbsp;
			
		</div>
		<div id="capture-tip" class="capture-tip"></div> 
			<input id="capture-btn" class="combtn borderradius-3" type="button" onclick="had_pull()" value="马上领取" /> 
		</div> 
	</section> 
	</if>	 
     
   <div class="content-bg"> 
   
	<div class="info"><if condition="($phone OR $unlogin_phone ) AND ($coupon.can_get gt 0)">你还可以领 {pigcms{$coupon.can_get}张优惠券,快点优惠券领取<elseif condition="($coupon.has_get gt 0) AND ($coupon.can_get eq 0)" />优惠券已经放入账户，快去消费！</if>
	<if condition="$unlogin_phone"><br>{pigcms{$unlogin_phone}</if>
    </div>
	<a href = "{pigcms{$coupon.url}" id="buy_now" style="display:none;"><input id="enveuse-btn" class="combtn downloadbtn" type="button" value="立即购买"></a>
    <section class="rule"> 
     <h4 class="sec-sub-title">活动规则</h4> 
     <ul> 
      <volist name="coupon.des_detial" id="vo">
		<li>{pigcms{$vo}</li>
	  </volist>
     </ul> 
    </section> 
   </div> 
  </div> 
   <script type="text/javascript">
   	var countdown = 60;
	function sendsms(val){
		if($("input[name='phone']").val()==''){
			alert('手机号码不能为空！');
		}else{
			
			if(countdown==60){
				$.ajax({
					url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send',
					type: 'POST',
					dataType: 'json',
					data: {phone: $("input[name='phone']").val()},

				});
			}
			if (countdown == 0) {
				val.removeAttribute("disabled");
				val.innerText="免费获取验证码";
				countdown = 60;
				//clearTimeout(t);
			} else {
				val.setAttribute("disabled", true);
				val.innerText="重新发送(" + countdown + ")";
				countdown--;
				setTimeout(function() {
					sendsms(val);
				},1000)
			}
		}
	}
	window.shareData = {  
				"moduleName":"Systemcoupon",
				"moduleID":"0",
				"imgUrl": '<if condition="$coupon.wx_share_img">{pigcms{$coupon.wx_share_img}<else/>{pigcms{$config.site_logo}</if>', 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Systemcoupon/show', array('coupon_id' => $coupon['coupon_id']))}",
				"tTitle": "{pigcms{$coupon['name']}",
				"tContent": "{pigcms{$coupon.des}"
	};
	</script>
	{pigcms{$shareScript}
 </body>

 
</html>