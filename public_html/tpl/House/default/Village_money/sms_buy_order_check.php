<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/styles.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
<if condition="$config['site_favicon']">
	<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
</if>
<title>{pigcms{$config.site_name} - 社区中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="{pigcms{$static_path}css/bootstrap.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/font-awesome.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-fonts.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace.min.css" id="main-ace-style">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-skins.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-rtl.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/global.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui-timepicker-addon.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/ace-extra.min.js"></script>

<script type="text/javascript" src="{pigcms{$static_path}js/bootstrap.min.js"></script>
<!-- page specific plugin scripts -->
<script type="text/javascript" src="{pigcms{$static_path}js/bootbox.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.easypiechart.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.sparkline.min.js"></script>
<!-- ace scripts -->
<script type="text/javascript" src="{pigcms{$static_path}js/ace-elements.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/ace.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.yiigridview.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-i18n.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/echarts.min.js"></script>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>

<script type="text/javascript">
	try{ace.settings.check('navbar' , 'fixed')}catch(e){}
	try{ace.settings.check('main-container' , 'fixed')}catch(e){}
	try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

</head>

<body class="no-skin" style="background-color: #FFFFFF;">
<div class="main-content">
	
	<style>
		.payment-bank {
			margin-top: 10px;
			border: 1px solid #DFDFDF;
			padding: 5px 0 10px 20px;
			background-color: #F5F5F5;
		}
		.payment-banktit {
			height: 20px;
			line-height: 20px;
			margin-top: 5px;
			padding: 5px 0;
			font-family: \5b8b\4f53;
			cursor: pointer;
		}
		.payment-banktit b {
			display: inline-block;
			height: 20px;
			padding-left: 17px;
			color: #333;
			font-size: 14px;
		}
		.payment-bankcen {
			padding-top: 10px;
		}
		.bank {
			width: 786px;
			padding: 15px 0 0 20px;
		}
		.payment-bankcen .bank{
			padding-top: 0;
			width: 1210px;
		}
		.imgradio li {
			padding-left: 20px;
			width: 112px;
			height: 32px;
			float: left;
			position: relative;
			margin: 0 25px 15px 0;
			_display: inline;
			_zomm: 1;
		}
		.imgradio li input {
			position: absolute;
			left: 0;
			top: 10px;
		}
		.imgradio li label{
			cursor:pointer;
		}
		.payment-bankcen .bank .imgradio li {
			margin-right: 45px;
		}
		.clr {
			height: 0;
			font-size: 0;
			line-height: 0;
			clear: both;
			overflow: hidden;
		}
		.form-submit {
			margin: 30px 0 20px;
		}
		
		
#bd {
  width: 1210px;
  margin: 0 auto;
  padding: 10px 0 65px;
    border-top: 3px solid #fe5842;
  margin-top:20px;
}

#content {
  float: left;
  width: 1210px;
  _display: inline;
  padding: 0;
}
.cf {
  zoom: 1;
}		
		
.sysmsgw {
  width: 1150px;
  margin: 10px auto 0;
}
		
.common-tip {
  position: relative;
  margin-bottom: 10px;
  padding: 10px 30px;
  border: 1px #F5D8A7 solid;
  border-radius: 2px;
  background: #FFF6DB;
  font-size: 14px;
  text-align: center;
  color: #666;
  zoom: 1;
}
		
a.see_tmp_qrcode {
  color: #EE3968;
  text-decoration: none;
}
.mainbox {
  border: none;
  padding: 0;
  padding-bottom: 60px;
}	
.summary-table {
    font-size: 12px;
    overflow: visible;
    margin: 0 0 10px;
}

.btn_btn {
    color: #fff;
    background-color: #2db3a6;
    border-color: #0D7B71;
    filter: progid:DXImageTransform.Microsoft.gradient(gradientType=0,
	startColorstr='#FF2EC3B4', endColorstr='#FF2DB3A6');
    background-size: 100%;
    background-image: -moz-linear-gradient(top, #2ec3b4, #2db3a6);
    background-image: -webkit-linear-gradient(top, #2ec3b4, #2db3a6);
    background-image: linear-gradient(to bottom, #2ec3b4, #2db3a6);
}

.btn_btn, .btn-hot, .btn-normal {
    display: inline-block;
    vertical-align: middle;
    padding: 7px 20px 6px;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.5;
    font-family: SimSun, Arial;
    letter-spacing: .1em;
    text-align: center;
    text-decoration: none;
    border-width: 0 0 1px;
    border-style: solid;
    background-repeat: repeat-x;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    -moz-user-select: -moz-none;
    -ms-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    cursor: pointer;
}
.form-submit{
	overflow: hidden;
    text-align: right;
}
.table-section table td, .table-section table th {
    border-bottom: 1px dotted #e5e5e5;
    padding: 12px;
}

.table-section table {
	float: left;
    width: 100%;
}

		
	</style>

	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<form action="{pigcms{:U('Pay/go_pay')}" method="post" id="deal-buy-form" class="common-form J-wwwtracker-form" >
			            <div class="mainbox cf" style="min-height:0px;">
							<div class="table-section">
								<table class="">
					            	<tr>
					            		<td>短信价格：</td>
					            		<td>￥<strong id="deal-buy-total-t" class="need_pay">{pigcms{$config.sms_price} ( 分/条 )</strong></td>
					            	</tr>
					            	<tr>
					            		<td>购买数量：</td>
					            		<td><strong id="deal-buy-total-t" class="need_pay">{pigcms{$order_info.sms_number}</strong>（条）</td>
					            	</tr>
					            	<tr>
					            		<td>订单编号：</td>
					            		<td><strong id="deal-buy-total-t" class="need_pay">{pigcms{$order_info.orderid}</strong></td>
					            	</tr>
					            </table>
							</div>
			            

							<div id="need-pay" style="text-align:right">
								<strong>总金额</strong>：
								<span class="inline-block money" style="font-size:20px;color:#EA4F01;">
									￥<strong id="deal-buy-total-t" class="need_pay">{pigcms{$order_info.payment_money}</strong>
								</span>
							</div>
					
						
						<div id="pay_bank_list" >
							<div class="payment-bank">
								<div class="payment-banktit">
									<b class="open">选择支付方式</b>
								</div>	
								<div class="payment-bankcen">
									<div class="bank morebank">
										<ul class="imgradio" style="list-style:none">
											<volist name="pay_method" id="vo">
												<li>
													<label>
														<input type="radio" name="pay_type" value="{pigcms{$key}" <if condition="$i eq 1">checked="checked"</if>>
														<img src="{pigcms{$static_public}images/pay/{pigcms{$key}.gif" width="112" height="32" alt="{pigcms{$vo.name}" title="{pigcms{$vo.name}"/>
													</label>
												</li>
											</volist>

											<li>
												<label>
													<input type="radio" name="pay_type" value="yue">
													<span style="font-size: 20px; width: 200px; display: inherit;">余额支付（{pigcms{$now_store.money}）</span>
												</label>
											</li>
										</ul>
										<div class="clr"></div>
									</div>
								</div>
								<div class="clr"></div>
							</div>
						</div>
						
						<div class="form-submit">
							<input type="hidden" name="order_id" value="{pigcms{$order_info.order_id}"/>
				    		<input type="hidden" name="order_type" value="{pigcms{$order_info.order_type}"/>
			                <input id="J-order-pay-button" type="submit" class="btn_btn btn-large btn-pay" name="commit" value="去付款"/><br/>
			            </div>
			    	</form>	
				</div>
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
	
	$("form").submit(function() {
	   $("#J-order-pay-button").val("正在处理...");
	   $("#J-order-pay-button").attr("disabled", "disabled");
	});
	
	$('#sysmsg-error .close').click(function(){
		$('#sysmsg-error').remove();
	});
	
	$('.see_tmp_qrcode').click(function(){
		var qrcode_href = $(this).attr('href');
		art.dialog.open(qrcode_href+"&"+Math.random(),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('login_iframe_handle',iframe);
			},
			id: 'login_handle',
			title:'请使用微信扫描二维码',
			padding: 0,
			width: 430,
			height: 433,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		return false;
	});
	
	$('#deal-buy-form').submit(function(){			
		if($('input[name="pay_type"]:checked').val() == 'weixin' || $('input[name="pay_type"]:checked').val() == 'weifutong'){
			art.dialog({
				title: '提示信息',
				id: 'weixin_pay_tip',
				opacity:'0.4',
				lock:true,
				fixed: true,
				resize: false,
				content: '正在获取微信支付相关信息，请稍等...'
			});

			// alert($('#deal-buy-form').serialize());
			// return false;
			$.post($('#deal-buy-form').attr('action'),$('#deal-buy-form').serialize(),function(result){
				art.dialog.list['weixin_pay_tip'].close();			
				if(result.status == 1){
					orderid = result.orderid;
					art.dialog({
						title: '请使用微信扫码支付',
						id: 'weixin_pay_qrcode',
						width:'350px',
						opacity:'0.4',
						lock:true,
						fixed: true,
						resize: false,
						content: '<p style="margin-top:20px;margin-bottom:20px;text-align:center;font-size:16px;color:black;">请使用微信扫描二维码进行支付</p><p style="text-align:center;"><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_own_qrcode&qrCon='+result.info+'" style="width:240px;height:240px;"></p><p style="text-align:center;margin-top:20px;margin-bottom:20px;"><input id="J-order-weixin-button" type="button" class="btn_btn btn-large btn-pay" value="已支付完成"/></p>',
						cancel: function(){
							$("#J-order-pay-button").val("去付款");
							$("#J-order-pay-button").removeAttr("disabled");
						},
					});
				}else{
					$("#J-order-pay-button").val("去付款");
					$("#J-order-pay-button").removeAttr("disabled");
					art.dialog({
						title: '错误提示：',
						id: 'weixin_pay_error',
						opacity:'0.4',
						lock:true,
						fixed: true,
						resize: false,
						content: result.info
					});
					
				}
			});
			return false;
		}else{

			if($('input[name="pay_type"]:checked').val() == 'yue'){
				layer.confirm('您确定要用余额支付吗？', {
					btn: ['确定','取消'] //按钮
				}, function(){
					var pay_yue_url = "{pigcms{:U('go_pay_yue')}";
					var order_id = "{pigcms{$_GET['order_id']}";
					$.post(pay_yue_url,{order_id:order_id},function(data){
						if(data.error == 1){
							art.dialog.close();
						}else if(data.error == 4){
							art.dialog.close();
						}else{
							layer.msg(data.msg);
							$("#J-order-pay-button").val("去付款");
			    			$('#J-order-pay-button').removeAttr("disabled");
						}
					},'json')

				}, function(){
					$("#J-order-pay-button").val("去付款");
			    	$('#J-order-pay-button').removeAttr("disabled");
				});


			}
			return false;
		}
	});
	
	$('#J-order-weixin-button').live('click',function(){
		window.location.href="{pigcms{:U('Pay/weixin_back',array('order_type'=>$order_info['order_type']))}&order_id="+orderid+'&pay_type='+$('input[name="pay_type"]:checked').val();
	});

});






</script>
<include file="Public:footer"/>
