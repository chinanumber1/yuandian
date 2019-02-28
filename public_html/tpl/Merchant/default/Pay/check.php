<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Merchant_money/index')}">商家余额</a></li>
			<li class="active">收入记录</li>

		</ul>
	</div>
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
.table-section .order-table-head-row th.desc {
    width: 700px;
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
.table-section table th {
    background: #f7f7f7 none repeat scroll 0 0;
    font-size: 14px;
    font-weight: 700;
    padding: 6px 12px;
}
		
		
	</style>

	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<form action="{pigcms{:U('Pay/go_pay')}" method="post" id="deal-buy-form" class="common-form J-wwwtracker-form" style="width:800px">
			            <div class="mainbox cf" style="min-height:0px;">
			            	<div class="table-section summary-table">
			                    <table cellspacing="0" class="buy-table">
			                        <tr class="order-table-head-row">
			                        	<th class="desc">项目</th>
										<th class="col-total">总价</th>
			                    	</tr>
									<tr>
										<td class="desc">{pigcms{$order_info.order_name}</td>
										
										<td class="money total rightpadding col-total">
											￥<span id="J-deal-buy-total">{pigcms{$order_info.money}</span>
										</td>
									</tr>
				                   
			                        <tr>
										<td>
			                        							
										</td>
				                        <td colspan="3" class="extra-fee total-fee rightpadding">
											<strong>订单总额</strong>：
				                            <span class="inline-block money">
				                                ￥<strong id="deal-buy-total-t">{pigcms{$order_info.money}</strong>
				                            </span>
				                        </td>
			                    	</tr>
			                    	
									 
			                	</table>
			            	</div>
			            </div>
					
							<div id="need-pay" style="text-align:right">
								<strong>总金额</strong>：
								<span class="inline-block money" style="font-size:20px;color:#EA4F01;">
									￥<strong id="deal-buy-total-t" class="need_pay">{pigcms{$order_info.money}</strong>
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
												<php>if($pay_offline || $key != 'offline'){</php>
												<li>
													<label>
														<input type="radio" name="pay_type" value="{pigcms{$key}" <if condition="$i eq 1">checked="checked"</if>>
														<img src="{pigcms{$static_public}images/pay/{pigcms{$key}.gif" width="112" height="32" alt="{pigcms{$vo.name}" title="{pigcms{$vo.name}"/>
													</label>
												</li>
												<php>}</php>
											</volist>
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
		}
	});
	
	$('#J-order-weixin-button').live('click',function(){
		window.location.href="{pigcms{:U('Pay/weixin_back',array('order_type'=>$order_info['order_type']))}&order_id="+orderid+'&pay_type='+$('input[name="pay_type"]:checked').val();
	});

});






</script>
<include file="Public:footer"/>
