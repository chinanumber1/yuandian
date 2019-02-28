<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>领取优惠券</title>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<script src="{pigcms{$static_path}coupon/js/tracker.js"></script><script type="text/javascript">
var bi_params = {cityid:'96',cateid:'',pagetype:'pc_gw_banner_jiazheng_detail',termtype:1};

(function() {
  var bi_hm = document.createElement("script");
  bi_hm.src = "{pigcms{$static_path}coupon/js/tracker.js?v=1";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(bi_hm, s);
})();
function reg_fleshVerify(url){
	var time = new Date().getTime();
	$('#reg_verifyImg').attr('src',url+"&time="+time);
}
</script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}coupon/css/style.css?t=123">
<style type="text/css"></style></head>

<body>
<!--start -->
<div class="wrap yhqDiv" id="inputPhone" style="display: block;">
	<div class="main jiazheng">
	    <div class="q-bg">
	    	<div class="money">{pigcms{$coupon.discount}</div>
			<div class="form" style="margin-top:5px;">
	        	<p class="err" id="phoneInvaild" style="display: none;">请输入正确的手机号</p>
					<input id="phone" name="phone" type="tel" maxlength="11" value="" placeholder="请输入您的手机号码">
					<if condition="C('config.bind_phone_verify_sms') AND C('config.sms_key')">
					<input id="sms_code" class="orderradius-3"  style="width:60%;" maxlength="6" name = "verify" type="text" placeholder="填写短信验证码" required/>
					<input type="hidden" name="verify_type" value="sms"/>
					<input type="button" value="获取验证码" onclick="sendsms(this)" style="width:87px;font-size:12px;cursor:pointer;height:30px;"/>
					<else />
					<input type="hidden" name="verify_type" value="nosms"/>
					<input class="borderradius-3" type="text" id="reg_verify" style="width:60%;" maxlength="4" name="verify" placeholder="请输入验证码"/>&nbsp;&nbsp;		
					<img src="{pigcms{:U('Coupon/verify',array('type'=>'reg'))}" id="reg_verifyImg" onclick="reg_fleshVerify('{pigcms{:U('Coupon/verify',array('type'=>'reg'))}')" title="刷新验证码" alt="刷新验证码"/>&nbsp;
					</if>
				
	        	<input id="coupon_id" type="hidden" maxlength="11" value="{pigcms{$_GET['coupon_id']}">
	            <input id="doReceive" type="button" value="领取代金券" class="disable" data-href="{pigcms{:U('Coupon/had_pull',array('coupon_id'=>$_GET['coupon_id']))}">
	        </div>
        </div>
	</div>
	<div class="footer"><p>注意事项：</p><volist name="coupon.des_detial" id="vo">{pigcms{$vo}<br></volist></div>
</div>
<!--end -->
<!--start -->
<div class="wrap open yhqDiv" id="toOrder" style="display:none;">
	<div class="main jiazheng">
	    <div class="q-bg">
			<div class="lingqu" id="msgTitle">恭喜您，领券成功！</div>
			<div class="msg" id="msg">
				<span>{pigcms{$coupon.discount}元</span>优惠券已存入<span id="had_phone"></span>账户中
			</div>
			<div class="form-btn">
	            <input type="button" value="立即下单" class="btn" id="toOrderBtn" data-href="{pigcms{$coupon.url}">
	        </div>
        </div>
	</div>
	<div class="footer"><p>注意事项：</p><volist name="coupon.des_detial" id="vo">{pigcms{$vo}<br></volist></div>
</div>
<!--end -->
<script type="text/javascript">
window.oldFailMsg = '对不起，该券只适用新用户！';
window.oldSuccessMsg = '<span>#amount#元</span>优惠券已存入<span>#phone#</span>账户中';
window.newSuccessMsg = '<span>#amount#元</span>优惠券已存入<span>#phone#</span>账户中';
tracker_url = "{pigcms{$static_path}coupon/js/tracker.js";
</script>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
<script type="text/javascript" src="{pigcms{$static_path}coupon/js/coupon.js"></script>
<script type="text/javascript">
	var countdown = 60;
	$(document).ready(function() {
		<if condition="$unlogin_phone">
			$("#inputPhone").show();
			$("#toOrder").hide();
		<elseif condition="$phone AND ($coupon['has_get'] gt 0)" />
			$("#toOrder").show();
			$("#inputPhone").hide();
		</if>
	});

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
</script>

</body></html>