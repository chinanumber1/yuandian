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
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/ceshi.css"/>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<link href="{pigcms{$static_path}layer/need/layer.css" type="text/css" rel="styleSheet" id="layermcss">
    	<script src="{pigcms{$static_path}js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8"></script>
	</head>
	<body>
	<header class="pageSliderHide"><div id="backBtn"></div>快递代发</header>
	<div class="content">
		<div class="add_ji">
			<div class="add_jiLeft"> <span>寄</span> <b><if condition="$send_adress">{pigcms{$send_adress.uname} {pigcms{$send_adress.phone} {pigcms{$send_adress.city} {pigcms{$send_adress.adress}<else/>添加寄件人地址</if></b> </div>
			<i class="addRight"></i>
			<input type="hidden" name="send_uname" value="{pigcms{$send_adress.uname}" id="send_uname"/>
			<input type="hidden" name="send_phone" value="{pigcms{$send_adress.phone}" id="send_phone"/>
			<input type="hidden" name="send_city" value="{pigcms{$send_adress.city}" id="send_city"/>
			<input type="hidden" name="send_adress" value="{pigcms{$send_adress.adress}" id="send_adress"/>
		</div>
		<div class="add_shou">
			<div class="add_jiLeft"> <span>收</span> <b><if condition="$collect_adress">{pigcms{$collect_adress.uname} {pigcms{$collect_adress.phone} {pigcms{$collect_adress.city} {pigcms{$collect_adress.adress}<else/>添加收件人地址</if></b> </div>
			<i class="addRight"></i>
			<input type="hidden" name="collect_uname" value="{pigcms{$collect_adress.uname}" id="collect_uname"/>
			<input type="hidden" name="collect_phone" value="{pigcms{$collect_adress.phone}" id="collect_phone"/>
			<input type="hidden" name="collect_city" value="{pigcms{$collect_adress.city}" id="collect_city"/>
			<input type="hidden" name="collect_adress" value="{pigcms{$collect_adress.adress}" id="collect_adress"/>
		</div>
		<div class="weight">
			<span>物件重量 ( kg )</span>
			<div class="changeNum">
				<span class="less">-</span>
				<input type="number" name="weight" id="weight" <if condition="$send_info['weight']">value="{pigcms{$send_info.weight}" <else/>value="1"</if> >
				<span class="adds">+</span>
			</div>
		</div>
		<div class="simple goodShop">
			<span>物件类型</span>
			<select name="goods_type" id="goods_type">
				<volist name="goods_type" id="vo">
					<option <if condition="$send_info['goods_type'] eq $key">selected="selected"</if> value="{pigcms{$key}">{pigcms{$vo}</option>
				</volist>
			</select>
		</div>
		<div class="simple goodShop">
			<span>快递公司</span>
			<select name="express" id="express">
				<volist name="express_list" id="vo">
					<option <if condition="$send_info['express'] eq $vo['code']">selected="selected"</if> value="{pigcms{$vo.code}">{pigcms{$vo.name}</option>
				</volist>
			</select>
		</div>
		<div class="remarks">
			<span>备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</span>
			<textarea name="remarks" id="remarks" rows="3" cols="5" placeholder="填写备注 , 20字以内">{pigcms{$send_info.remarks}</textarea>
		</div>
		<div class="moeny">
			<span>代发费用</span>
			<p><span>{pigcms{$express_send_price}</span> 元</p>
			<input type="hidden" name="send_price" value="{pigcms{$express_send_price}" id="send_price" / >
			<input type="hidden" name="village_id" id="village_id" value="{pigcms{$_GET['village_id']}">
		</div>
		<p class="mes">提交信息后 , 社区将及时通知相关快递公司上门取件收发快递。</p>
		<button type="button" class="btnss" onclick="btn_submit()">提交 (  <span>￥{pigcms{$express_send_price}</span> )</button>
	</div>
  
	
	<script type="text/javascript">
		
		$('#backBtn').click(function(e){
			window.location.href="{pigcms{:U('express_send_list',array('village_id'=>$_GET['village_id']))}";
		});

		$('.add_ji').click(function(e){

			var weight = $("#weight").val();
			var goods_type = $("#goods_type").val();
			var express = $("#express").val();
			var remarks = $("#remarks").val();
	  
	  		var send_adress_ajax_url = "{pigcms{:U('send_adress_ajax')}";
	  		$.post(send_adress_ajax_url,{weight:weight,goods_type:goods_type,express:express,remarks:remarks,type:3},function(data){
	  			window.location.href="{pigcms{:U('express_send_adress',array('type'=>1,'village_id'=>$_GET['village_id']))}";
	  		});

		});

		$('.add_shou').click(function(e){
			var weight = $("#weight").val();
			var goods_type = $("#goods_type").val();
			var express = $("#express").val();
			var remarks = $("#remarks").val();
	  		var send_adress_ajax_url = "{pigcms{:U('send_adress_ajax')}";
	  		$.post(send_adress_ajax_url,{weight:weight,goods_type:goods_type,express:express,remarks:remarks,type:3},function(data){
	  			window.location.href="{pigcms{:U('express_send_adress',array('type'=>2,'village_id'=>$_GET['village_id']))}";
	  		});

		});

		$('.less').click(function(e){
			var vals=Number($(this).next().val());
			if(vals>1){
				vals--;
			}
			$("#weight").val(vals);
		});

		$('.adds').click(function(e){
			var vals=Number($(this).prev().val());
			vals++;
			$("#weight").val(vals);
		});

		$('.changeNum input').blur(function(e){
			var val=Number($(this).val());
			if(val<=1){
				val=1
			}
			$(this).val(val);
		});

		function btn_submit(){

			var send_uname = $("#send_uname").val();
			var send_phone = $("#send_phone").val();
			var send_city = $("#send_city").val();
			var send_adress = $("#send_adress").val();

			if(!send_uname){
				alert('寄件地址不能为空');
				return false;
			}

			var collect_uname = $("#collect_uname").val();
			var collect_phone = $("#collect_phone").val();
			var collect_city = $("#collect_city").val();
			var collect_adress = $("#collect_adress").val();

			if(!collect_uname){
				alert('收件地址不能为空');
				return false;
			}

			var weight = $("#weight").val();
			var goods_type = $("#goods_type").val();
			var express = $("#express").val();
			var remarks = $("#remarks").val();
			var send_price = $("#send_price").val();
			var village_id = $("#village_id").val();

			if(!village_id){
				alert('数据异常！');
				return false;
			}


			layer.open({
				content: '您确定要支付'+send_price+'元，并发布需求吗？'
				,btn: ['确定', '取消']
				,yes: function(index){
					layer.closeAll();
					layer.open({type: 2 ,content: '提交中...'});
					var express_send_add_url = "{pigcms{:U('express_send_add')}";

					$.post(express_send_add_url,{send_uname:send_uname,send_phone:send_phone,send_city:send_city,send_adress:send_adress,collect_uname:collect_uname,collect_phone:collect_phone,collect_city:collect_city,collect_adress:collect_adress,weight:weight,goods_type:goods_type,express:express,remarks:remarks,send_price:send_price,village_id:village_id},function(data){
						if(data.error == 1){
							layer.open({
							    content: data.msg
							    ,btn: ['确定']
							    ,yes: function(index){
							        location.href = "{pigcms{:U('express_send_list',array('village_id'=>$_GET['village_id']))}";
							    }
							});
						}else if(data.error == 3){
							layer.open({
							    content: data.msg
							    ,btn: ['确定']
							    ,yes: function(index){
							        location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_expressSend_'))}{pigcms{$_GET['village_id']}";
							    }
							});
						}else{
							layer.open({
								content: data.msg
								,btn: ['确定']
							});
						}

					},'json');
				}
			});
		}
	</script>
</html>