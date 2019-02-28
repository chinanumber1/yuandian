<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>确认订单</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/weixin_pay.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
</head>
<body>


<?php if($is_app_browser && in_array($app_browser_type,array('android','ios')) && ($_REQUEST['app_version'] ? $_SESSION['app_version'] : $_SESSION['app_version']) ){ ?>
 <script type="text/javascript">
    <if condition="$app_browser_type eq 'android'">
        window.lifepasslogin.payCheck("{pigcms{$_GET['type']}","{pigcms{$_GET['order_id']}");
        layer.open({type: 2});
        function ReturnLastPay(){
            history.back();
        };
    <else/>
        $('body').append('<iframe src="pigcmso2o://gopay/<?php $arr=array('type'=>$type,'order_id'=>$order_id); echo base64_encode(json_encode($arr)); ?>" style="display:none"></iframe>');
        function payCheck(){
           window.location.reload();
        }
    </if>
 </script>
    <?php }else{ ?>
		<script type="text/javascript">
        var  now_money = Number("{pigcms{$now_user.now_money}");
        var  merchant_money = Number("{pigcms{$merchant_balance}");
        var  wx_cheap =Number("<?php if($cheap_info['can_cheap']){ ?>{pigcms{$cheap_info.wx_cheap}<?php }else{?>0<?php }?>");
        var  score_count = Number("{pigcms{$score_count}");
        var  score_deducte = Number("{pigcms{$score_deducte}");
        var  score_can_use_count = Number("{pigcms{$score_can_use_count}");
        var  coupon_price = Number("<?php if($now_coupon){ ?>{pigcms{$now_coupon.price}<?php }?>");
        var  total_money = Number("{pigcms{$order_info.order_total_money}");
		total_money = total_money-wx_cheap-coupon_price;
        var  need_pay;
         $(document).ready(function() {
			if($("#use_balance").is(':checked')==true){
				var total_money_tmp = total_money;
				if($("#use_score").is(':checked')==true){
					total_money_tmp -= score_deducte;
				}
				
				if(merchant_money>=total_money){
					show_money(0,0,1);
				}else if(now_money>=total_money_tmp-merchant_money){
					show_money(1,0,1);
				}else if(now_money==0){
					show_money(0,total_money_tmp-merchant_money-now_money,2);
				}else{
					show_money(1,total_money_tmp-merchant_money-now_money,2);
				}
			}else{
				var total_money_tmp = total_money;
				if($("#use_score").is(':checked')==true){
					total_money_tmp -= score_deducte;
				}
				
				if(merchant_money>=total_money_tmp){
					show_money(0,0,1);
					
				}else{
					if(now_money==0){
						show_money(0,total_money_tmp-merchant_money,2);
					}else{
						show_money(1,total_money_tmp-merchant_money,2);
					}
				}
			}    
			
			$("#use_score").bind("click", function () {
				var total_money_tmp = total_money;
				if($("#use_score").is(':checked')==true){
					if($("#use_balance").is(':checked')==true){
						if(merchant_money>=total_money_tmp-score_deducte){
							
							show_money(0,0,1);
						}else if(now_money>=total_money_tmp-score_deducte-merchant_money){
							show_money(1,0,1);
						}else{
							show_money(1,total_money-score_deducte-merchant_money-now_money,2);
						}
					}else{
						if(merchant_money>=total_money_tmp-score_deducte){
							show_money(0,0,1);
						}else{
							show_money(1,total_money_tmp-score_deducte-merchant_money,2);
						}
					}
					$("input[name='use_score']").attr('value',1);
				}else if($("#use_score").is(':checked')==false){
				
					if($("#use_balance").is(':checked')==true){
						if(merchant_money>=total_money_tmp){
							show_money(0,0,1);
						}else if(now_money>=total_money_tmp-merchant_money){
							show_money(1,0,1);
						}else{
							show_money(1,total_money_tmp-merchant_money-now_money,2);
						}
					}else{
						if(merchant_money>=total_money_tmp){
							show_money(0,0,1);
						}else{
							show_money(1,total_money_tmp-merchant_money,2);
						}
					}
					$("input[name='use_score']").attr('value',0);
				}
			});
            
			
			$('#use_balance').bind("click", function () {
				
				if($("#use_balance").is(':checked')==true){
					
					var total_money_tmp = total_money;
					if($("#use_score").is(':checked')==true){
						total_money_tmp -= score_deducte;
					}
					
					if(merchant_money>=total_money){
						show_money(0,0,1);
					}else if(now_money>=total_money_tmp-merchant_money){
						show_money(1,0,1);
					}else{
						show_money(1,total_money_tmp-merchant_money-now_money,2);
					}
					$("input[name='use_balance']").attr('value',0);
						
				}else if($("#use_balance").is(':checked')==false){
					var total_money_tmp = total_money;
					if($("#use_score").is(':checked')==true){
						total_money_tmp -= score_deducte;
					}
					if(merchant_money>=total_money){
						show_money(0,0,1);
					}else{
						show_money(1,total_money_tmp-merchant_money,2);
					}
					$("input[name='use_balance']").attr('value',1);
				}
			
			});

			$("form").submit(function() {
				$("button.mj-submit").attr("disabled", "disabled");
				$("button.mj-submit").html("正在处理...");
			});

        });
		function show_money(use_balance,money,pay_title){
			if(pay_title==1){
				$('#normal-fieldset').css('display','none');
				$('#normal-fieldset input[type="radio"]').removeAttr('checked');
			}else{
				console.log($('#normal-fieldset').css('display'))
				if($('#normal-fieldset').css('display')=='none'){
					
				$('#normal-fieldset input[type="radio"]:first').attr('checked','checked');
				}
				$('#normal-fieldset').css('display','block');
			}
			if(!use_balance){
				$('#balance_money').css('color','#C1B9B9');
				$('#use_balance').removeAttr('checked');
				$('#use_balance').attr('disabled','disabled');
			}else{
				if(now_money>0){
					$('#balance_money').css('color','#666666');
					$('#use_balance').removeAttr('disabled');
				}
			}
			$('.need-pay').html(money.toFixed(2))
		}
	<if condition="$config['twice_verify']">var twice_verify = true;<else />var twice_verify = false;</if>
	<if condition="$_SESSION['user']['verify_end_time']">var verify_end_time = {pigcms{$_SESSION['user']['verify_end_time']};</if>
    </script>
	<script language="javascript">
	function bio_verify(){
		layer.open({type:2,content:'页面加载中',shadeClose:false});
		var pay_type = $('input:radio:checked').val();
		$("button.mj-submit").attr("disabled", "disabled");
		$("button.mj-submit").html("正在处理...");
		var use_score= $("input[name='use_score']").val();
		var use_balance= $("input[name='use_balance']").val();
		//var  merchant_money = Number("{pigcms{$merchant_balance}");
		if(twice_verify&&(merchant_money!=0||use_balance==1||use_score==1)){
			if(typeof(wxSdkLoad) != "undefined"){
				wx.invoke('getSupportSoter', {}, function (res) {
				  if(res.support_mode=='0x01'){
					wx.invoke('requireSoterBiometricAuthentication', {
					  auth_mode: '0x01',
					  challenge: 'test',
					  auth_content: '请将指纹验证'  //指纹弹窗提示
					}, function (res) {
						if(res.err_code==0&&pay_type=='weixin'){
							callpay();
						}else if(res.err_code==0){
							layer.closeAll();
							$('#pay-form').submit();
						}else if (res.err_code==90009){
							layer.closeAll();
							$('#pwd_bg').css('display','block');
							$('#pwd_verify').css('display','block');
						}else{
							alert(res.err_code);
							$("button.mj-submit").removeAttr("disabled");
							$("button.mj-submit").html("确认支付");
						}
					})
				  }else{
					 // 密码验证
					 layer.closeAll();
					$('#pwd_bg').css('display','block')		
					$('#pwd_verify').css('display','block')		
				  }
				})
			}else{		
				layer.closeAll();
				$('#pwd_bg').css('display','block');
				$('#pwd_verify').css('display','block');
			}
		 
		}else{
			layer.closeAll();
			var res = callpay();
			if(res){
				$('#pay-form').submit();
			}
		}
	}
	
	//微信弹程支付
	function callpay(){
		var pay_type = $('input:radio:checked').val();
		if(typeof(pay_type)!='undefined'){
			if(pay_type!='weixin'){
				return true;
			}else if(pay_type=='weixin'){
				var pay_method = {pigcms{:json_encode($pay_method)};
				var orderid_info = {pigcms{:json_encode($orderid_info)};
				var pay_money = <?php if($order_info['order_type']=='recharge'){ ?> total_money<?php }else{ ?>need_pay<?php }?>;
				var short_orderid = {pigcms{$order_info.order_id};
				$("button.mj-submit").attr("disabled", "disabled");
				$("button.mj-submit").html("正在处理...");
				var param;
				$.ajax({
					url: '{pigcms{:U(\'Pay/get_weixin_param\')}',
					type: 'POST',
					dataType: 'json',
					data: {orderid_info:orderid_info,short_orderid:short_orderid,order_info:$('#pay-form').serialize()},
					beforeSend: function(){
						layer.open({type:2,content:'支付加载中',shadeClose:false});
					},
					success: function(date){
					layer.closeAll();
						if(!date.error){
								param =  date.weixin_param;
								WeixinJSBridge.invoke("getBrandWCPayRequest",param,function(res){
									WeixinJSBridge.log(res.err_msg);
									if(res.err_msg=="get_brand_wcpay_request:ok"){
										setTimeout("window.location.href = '"+date.redirctUrl+"'",200);
									}else{
										$("button.mj-submit").removeAttr("disabled");
										$("button.mj-submit").html("确认支付");
									}
								});
						}else{
							alert(date.msg)
						}
					}
				});
				return false;
			}
		}else{
			return true;
		}
	}	

</script>
		<script>layer.open({type:2,content:'页面加载中',shadeClose:false});</script>
        <div id="tips" class="tips"></div>
        <div class="wrapper-list">
			<dl class="list">
			    <dd>
			        <dl>
						 <dd class="kv-line-r dd-padding">
							<if condition="$order_info.order_type neq 'weidian' OR $order_info.order_type neq 'wxapp'"><img src="{pigcms{$order_info.img}" style="width:80px;height:80px;"></if>
							<div>
								<p style="margin-left: 20px;">{pigcms{$order_info.order_name}</p>
								<if condition="$order_info.order_price gt 0"><p style="margin-left: 20px;margin-top: 10px;">￥ {pigcms{$order_info.order_price} * {pigcms{$order_info.order_num}</p></if>
								<if condition="$order_info.order_txt_type"><p style="margin-left: 20px;margin-top: 5px;">{pigcms{$order_info.order_txt_type}</p></if>
								<p style="margin-left: 20px;text-align:left">总价：<b style="color:#FF9435">{pigcms{$order_info.order_total_money}</b></p>
							</div>
						 </dd>
			        </dl>
			    </dd>
			</dl>
			<if condition="$order_info['order_type'] != 'recharge' OR $order_info['order_type'] != 'weidian' ">
				<h4>结算信息</h4>
				<dl class="list">
					<dd>
						<dl>
							<if condition="$cheap_info['can_cheap']">
								<dd class="kv-line-r dd-padding">
									<h6>微信优惠：</h6><p>{pigcms{$cheap_info.wx_cheap}元</p>
								</dd>
							</if>
							<if condition="$_GET['type'] neq 'weidian'">
								<?php if(empty($notCard)){ ?>
									<dd>
										<a class="react" href="{pigcms{:U('My/select_card',($order_info['coupon_url_param'] ? $order_info['coupon_url_param'] :$_GET))}&coupon_type=system">
											<div class="more more-weak">
												<h6>优惠券：</h6>
												<span class="more-after"><?php if($now_coupon){ ?>￥{pigcms{$now_coupon.price}<?php }else{ ?>使用优惠券<?php } ?></span>
											</div>
										</a>
									</dd>
								<?php } ?>
							</if>
							<if condition="$merchant_balance">
							<dd class="kv-line-r dd-padding">
								<h6 style="font-size: .3rem;">商家会员卡余额：</h6><p>{pigcms{$merchant_balance}元</p>
							</dd>
							</if>
							<if condition="($score_deducte gt 0) or ($_GET['type']=='gift')">
								<dd class="dd-padding">
									<label class="mt"><span class="pay-wrapper">本单可使用{pigcms{$config['score_name']}：{pigcms{$score_can_use_count}<?php if($_GET['type'] != 'gift'){ ?><br/>可抵扣金额：￥{pigcms{$score_deducte|floatval=###}<?php } ?><input type="checkbox" class="mt" value="1" id="use_score" <?php if($_GET['type'] == 'gift'){ ?>checked="checked" disabled="disabled"<?php } ?> name="use_score"><p style="display:block;float:right;">是否使用{pigcms{$config['score_name']}：</p></span></label>
								</dd>
							</if>
				
							<dd class="dd-padding" id="balance_money" <if condition="$now_user.now_money eq 0 OR $merchant_balance gt $order_info.order_total_money ">style="color: #C1B9B9;"</if>>
								<label class="mt"><span class="pay-wrapper">使用余额支付(剩余￥{pigcms{$now_user.now_money}）<input type="checkbox" class="mt"  id="use_balance" name="use_balance_money"<if condition="$order_info.order_type eq 'recharge' OR $now_user.now_money eq 0 OR $merchant_balance gt $order_info.order_total_money ">disabled="disabled" value="1"<else /> value="0" checked="checked" </if>></span></label>
							</dd>
						</dl>
					</dd>
				</dl>
			</if>
			<form action="/source{pigcms{:U('Pay/go_pay',array('showwxpaytitle1'=>1))}" method="POST" id="pay-form" class="pay-form" >
				<input type="hidden" name="order_id" value="{pigcms{$order_info.order_id}"/>
				<input type="hidden" name="order_type" value="{pigcms{$order_info.order_type}"/>
				<input type="hidden" name="card_id" value="{pigcms{$now_coupon.record_id}"/>
				<input type="hidden" name="coupon_id" value="{pigcms{$now_coupon.id}"/>
				<input type="hidden" name="use_score" value="0"/>
				<input type="hidden" name="use_balance" <if condition="$order_info.order_type eq 'recharge' OR $now_user.now_money eq 0 OR $merchant_balance gt $order_info.order_total_money ">value="1"<else /> value="0" </if>/>
				<input type="hidden" name="score_used_count" value="{pigcms{$score_can_use_count}">
				<input type="hidden" name="score_deducte" value="{pigcms{$score_deducte}">
				<input type="hidden" name="score_count" value="{pigcms{$score_count}">
				<input type="hidden" name="merchant_balance" value="{pigcms{$merchant_balance}">
				<input type="hidden" name="balance_money" value="{pigcms{$now_user.now_money}">
				
					
				<div id="pay-methods-panel" class="pay-methods-panel">
					<div id="normal-fieldset" class="normal-fieldset" style="height: 100%;display:none;" >
						<h4>选择支付方式</h4>
						<dl class="list">
							<volist name="pay_method" id="vo">
								<if condition="empty($order_info['group_share_num']) OR ($key neq 'offline')">
								<dd class="dd-padding">
									<label class="mt"><i class="bank-icon icon-{pigcms{$key}"></i><span class="pay-wrapper">{pigcms{$vo.name}<input type="radio" class="mt" value="{pigcms{$key}"  <if condition="$i eq 1">checked="checked"</if> name="pay_type"></span></label>
								</dd>
								</if>
							</volist>
						</dl>
					</div>
					
					<div class="wrapper buy-wrapper" style="background-color: #FFFFFF; height: 53px;margin: 0.3rem 0rem;padding: 20px 45px 30px 45px;">
						<div style="text-align:center;margin-bottom:10px;">总金额 <div style="font-weight:bold;color:#FF9435;display: inline;">￥<div class="need-pay" style="display:inline;">0</div></div> </div>
						<button type="button" class="btn mj-submit btn-strong btn-larger btn-block" onclick="bio_verify()" style="display:none;">确认支付</button>
					</div>
				</div>
			</form>
		</div>
		
		<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
		<div id="pwd_bg" style="height: 921px;" style="display:block">

		</div>
		<div id="pwd_verify" class="pwd_verify" style="display:none" >
			<div class="pwd_menu" >
				<span class="cancle"><img src="{pigcms{$static_path}images/twice_cancel.png"></span><p>密码验证</p>
			</div>
				<input type="hidden" id="pwd_type" name="type" value="1">
			<div class="verify_pwd">
				<p class="tips"></p>
				<input type="password"  autocomplete="off"  id="pwd" placeholder="输入登录密码" name="pwd" value="">
				<a id="forget_pwd" href="{pigcms{:U('Login/forgetpwd')}"><p class="forget_pwd">忘记密码?</p></a>
			</div>
			<div class="verify_sms" style="display:none;">
				<span style="color:#5E5E5E;font-size: 12px;">验证码将发送您手机：</span><span id="verify_phone" style="color:#006600;font-size: 12px;"></span>
				<input type="text" name="sms_code" autocomplete="off"  placeholder="输入验证码" value="">
				<button onclick="sendsms(this)">发送短信</button>
				<p></p>
			</div>
			<div class="verify_button" id="verify">
				<p>验证</p>
			</div>
		</div>
	
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/bioauth_.js"></script>

		<script>
			layer.closeAll();
			var showBuyBtn = true;
			
		</script>
		<if condition="$cheap_info['can_buy'] heq false">
			<script>layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:'您必须关注公众号后才能购买本单！<br/>长按图片识别二维码关注：<br/><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$order_info['order_id']+2000000000}" style="width:230px;height:230px;"/>',shadeClose:false});$('button.mj-submit').remove();var showBuyBtn = false;</script>
		</if>
		<script>if(showBuyBtn){$('button.mj-submit').show();}</script>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
{pigcms{$hideScript}

<?php } ?>
</body>
</html>