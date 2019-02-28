<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<style>
			*{margin: 0;padding: 0;}
			body{background: #f4f4f4;}	
			header {
			    height: 50px;
			    background-color: #06c1ae;
			    color: white;
			    line-height: 50px;
			    text-align: center;
			    position: relative;
			    font-size: 16px;
			}
			header #backBtn {
			    position: absolute;
			    width: 50px;
			    height: 100%;
			    top: 0;
			    left: 0;
			}
			header #backBtn:after {
			    display: block;
			    content: "";
			    border-top: 2px solid white;
			    border-left: 2px solid white;
			    width: 12px;
			    height: 12px;
			    -webkit-transform: rotate(315deg);
			    background-color: transparent;
			    position: absolute;
			    top: 19px;
			    left: 19px;
			}
			.message{
				background:white;
				width: 90%;
				padding: 10px 5%;
				color: #333;
				font-size: 14px;
			}
			.message>p:first-child{
				font-size: 16px;
				height:16px;
				line-height: 16px;
				margin:10px 0;
				border-left:4px solid #06C1AE;
				padding-left: 5px;
				color: #111;
			}
			.message>p{
				margin: 7px 0;
				margin-left:6%;
			}
			.message>p span.tou{
				margin-right: 15px;
			}
			.message>p span.wei{
				color: #008100;
			}
			h5{
				margin: 0 5%;
				margin-top: 30px;
				font-size: 16px;
				font-weight: normal;
				color: #666;
				width: 90%;
			}
			h5 i{
				display: inline-block;
				width: 4px;height: 16px;
				background:#06C1AE;
				vertical-align:middle;

			}
			h5 span{color: #06C1AF;}
			.add_less{
				/*text-align: center;*/
				height: 40px;
				border:1px solid #BDBDBC;
				border-radius: 4px;
				width:200px;
				margin: 0 auto;
				margin-top: 35px;
				font-size: 0;
				line-height: 40px;
			}
			.add_less .less{
				display:inline-block;
				font-size: 59px;
				width: 50px;
				height: 40px;
				text-align: center;
				line-height: 30px;
				color: #06C1AE;
				border-right:1px solid #BDBDBC;
			}
			.add_less .add{
				display:inline-block;
				width: 50px;
				height: 40px;
				border:none;
				border-left:1px solid #BDBDBC;
				font-size: 45px;
				text-align: center;
				line-height: 41px;
				color: #06C1AE;
			}
			.add_less .add span{
				display: inline-block;
				position: relative;
				top:-3px;
			}
			.add_less input{
				width: 98px;
				font-size: 16px;
				height: 16px;
				line-height: 16px;
				padding: 12px 0;
				vertical-align: top;
				border:none;
				color: #333;
				text-align:center;
			}
			h6{
				text-align:center;
				margin-top: 15px;
				color: #9A0000;
				font-size: 13px;
				font-weight: normal;
			}
			h6 i{
				display: inline-block;
				width: 16px;
				height: 16px;
				background:url({pigcms{$static_path}images/jinshi.png) center no-repeat;
				background-size:contain;
				vertical-align:sub;
			}
			footer{
				position: fixed;
				bottom: 0;
				width: 100%;
				height: 50px;
				line-height: 50px;
				background: white;
				font-size: 0;
				
			}
			footer .total{
				display: inline-block;
				width: 60%;
				margin-left: 4%;
				color: #333;
				font-size: 16px;
				border-top: 1px solid #eee;
			}
			footer .total span{color: #06C1AE;}
			.pay{
				display: inline-block;
				width: 36%;
				font-size: 14px;
				text-align: center;
				background: #06C1AE;
				color: #fff;
				border-top:1px solid #06C1AE;
			}
		</style>
	</head>
	<body>
	<header class="pageSliderHide"><div id="backBtn" onclick="history.go(-1)"></div>{pigcms{$payment_info.payment_name}<if condition="$payment_info['remarks'] neq ''">({pigcms{$payment_info['remarks']})</if></header>
	<div class="content">
		<div class="message">
			<p>缴费信息</p>
			<p><span class="tou">计算公式 : </span> <span class="wei"><if condition="$payment_info.pay_type eq 1">固定模式<else/>按金额*数量</if></span></p>
			<if condition="$payment_info.pay_type neq 1"><p><span class="tou">{pigcms{$payment_info.metering_mode} : </span> <span class="wei"><span class="size">{pigcms{$payment_info.metering_mode_val}</span></span></p></if>
			<p><span class="tou">收费金额 : </span> <span class="wei"><span class="price">{pigcms{$payment_info.pay_money}</span>&nbsp;元</span></p>
			<p><span class="tou">收费周期 : </span> <span class="wei">{pigcms{$payment_info.pay_cycle}{pigcms{$cycle_type[$payment_info['cycle_type']]}/周期</span></p>
			<p><span class="tou">服务日期 : </span> <span class="wei">{pigcms{$payment_info.start_time|date="Y-m-d",###}&nbsp;至&nbsp;{pigcms{$payment_info.end_time|date="Y-m-d",###} （{pigcms{$payment_info['cycle_sum']} 周期）</span></p>
			<p><span class="tou">您已缴费的周期的个数为 : </span> <span class="wei lenth"><span>{pigcms{$payment_info.paid_cycle}</span>&nbsp;个</span></p>
		</div>
		<div>
			<h5> <i></i>	请<span>选择</span>您要缴纳的周期数</h5>
			<div class="add_less">
				<span class="less">-</span>
				<input type="tel" value="1" class="diy_cycle" onkeyup="diy_cycle_keyup(this.value)">
				<span class="add"><span>+</span></span>
			</div>
			<h6> <i></i>本次最大只可缴费{pigcms{$payment_info['cycle_sum']-$payment_info['paid_cycle']}个周期</h6>
		</div>
	</div>
	<footer>
		<input type="hidden" name="total_price" value="{pigcms{$payment_info.price}" id="total_price" />
		<div class="total">合计: ￥ <span id="totalmoney">{pigcms{$payment_info.price}</span></div>
		<div class="pay" onclick="confirm()">去支付</div>
	</footer>
	<script src="{pigcms{:C('JQUERY_FILE_190')}" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script type="text/javascript">
		var price = "{pigcms{$payment_info.price}";

		$('.add').click(function(e){
			var cycle_sum = "{pigcms{$payment_info['cycle_sum']}";
			var paid_cycle = "{pigcms{$payment_info['paid_cycle']}";
			var max_cycle = cycle_sum-paid_cycle;
			var diy_cycle=$('.diy_cycle').val();
			if(diy_cycle<max_cycle){
				diy_cycle++;
				$('.diy_cycle').val(diy_cycle);

				$('#totalmoney').html(parseFloat(diy_cycle*price).toFixed(2));
				$('#total_price').val(parseFloat(diy_cycle*price).toFixed(2));

			}
		});

		$('.less').click(function(e){
			var diy_cycle=$('.diy_cycle').val();
			if(diy_cycle>1){
				diy_cycle--;
				$('.diy_cycle').val(diy_cycle);
				$('#totalmoney').html(parseFloat(diy_cycle*price).toFixed(2));
				$('#total_price').val(parseFloat(diy_cycle*price).toFixed(2));
			}
		});

		function diy_cycle_keyup(diy_cycle){
			var cycle_sum = "{pigcms{$payment_info['cycle_sum']}";
			var paid_cycle = "{pigcms{$payment_info['paid_cycle']}";
			var max_cycle = cycle_sum-paid_cycle;
			diy_cycle = parseInt(diy_cycle);
			if(diy_cycle>max_cycle){
				$('.diy_cycle').val(max_cycle);
				$('#totalmoney').html(parseFloat(max_cycle*price).toFixed(2));
				$('#total_price').val(parseFloat(max_cycle*price).toFixed(2));
			}else if(diy_cycle>1){
				$('.diy_cycle').val(diy_cycle);
				$('#totalmoney').html(parseFloat(diy_cycle*price).toFixed(2));
				$('#total_price').val(parseFloat(diy_cycle*price).toFixed(2));
			}else{
				$('.diy_cycle').val(1);
				$('#totalmoney').html(parseFloat(1*price).toFixed(2));
				$('#total_price').val(parseFloat(1*price).toFixed(2));
			}
		}

		function confirm(){
			var cycle_sum = "{pigcms{$payment_info['cycle_sum']}";
			var paid_cycle = "{pigcms{$payment_info['paid_cycle']}";
			var max_cycle = cycle_sum-paid_cycle;
			var bind_id = "{pigcms{$payment_info['bind_id']}";
			var money = $('#total_price').val();
			var diy_cycle = parseInt($('.diy_cycle').val());
			var text = "{pigcms{$payment_info.payment_name}";

			layer.open({title:['是否确认提交？','background-color:#06c1ae;color:#fff;'],content:'金额：' +  money,shadeClose:false,btn: ['确定','取消'],yes:function(){
				layer.closeAll();
				layer.open({type: 2,content: '提交中，请稍等',shadeClose:false});

				if(!diy_cycle){
					layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'周期不能为空！',btn: ['确定'],end:function(){}});
				}

				if(diy_cycle >max_cycle || diy_cycle <= 0){
					layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'周期超出签订日期',btn: ['确定'],end:function(){}});
				}

				$.post(window.location.href,{'txt':text,'money':money,'diy_cycle':diy_cycle,'bind_id':bind_id},function(result){
					layer.closeAll();
					if(result.err_code == 1){
						window.location.href = result.order_url;
					}else{
						layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:result.err_msg,btn: ['确定'],end:function(){}});
					}
				},'json');
			}});
		}
	</script>
</html>