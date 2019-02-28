<include file="Public:header"/>


<div class="main-content">

	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Analysis/trade')}" class="on">交易信息</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				 <div class="form-group" style="margin-bottom:0px">
					<form action="{pigcms{:U('Appoint/order_list')}" method="get" id="myform1">
						<input type="hidden" name="c" value="Analysis"/>
						<input type="hidden" name="a" value="trade"/>
						
							<span class="fl">业务类型：</span>
							<select class="fl" id="type" name="type">
								<volist name="type_name" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $_GET['type']">selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
							
							<span class="fl plat_form">收入来源：</span>
							<select class="fl plat_form" id="plat_form" name="platform">
								
								<option value="0" <if condition="0 eq $_GET['platform']">selected="selected"</if>>本平台</option>
								<option value="1" <if condition="1 eq $_GET['platform']">selected="selected"</if>>饿了么</option>
								<option value="2" <if condition="2 eq $_GET['platform']">selected="selected"</if>>美团</option>
								
							</select>
					
							
							<span class="fl" style="margin-left:60px">选择店铺：</span>
							<span class="fl">全部店铺</span><input type="radio" href="{pigcms{:U('Analysis/store_list')}" class="input-text fl "  data-type="all_store" name="store_list" value="-1" <if condition="$_GET['store_list'] eq -1 OR !isset($_GET['store_list'])">checked </if> >
							<span class="fl">部分店铺</span><input type="radio" href="{pigcms{:U('Analysis/store_list')}" class="input-text fl " id="part_store" data-type="part_store" name="store_list" <if condition="$_GET['store_list'] neq -1 AND !empty($_GET['store_list'])">checked value="{pigcms{$_GET['store_list']}" <else />value="0"</if>>
							
							<a href="javascript:void(0)" id="edit_store" style="display:none;line-height: 16px;" data-type="part_store">编辑</a>
							
							<span class="fl" style="margin-left: 30px;">选择店员：</span>
							<span class="fl">全部店员</span><input type="radio"  class="input-text fl " href="{pigcms{:U('Analysis/staff_list')}" data-type="all_staff" name="staff_list" value="-1" <if condition="$_GET['staff_list'] eq -1 OR !isset($_GET['staff_list'])">checked </if>>
							<span class="fl">部分店员</span><input type="radio"  class="input-text fl "  href="{pigcms{:U('Analysis/staff_list')}" id="part_staff" data-type="part_staff" name="staff_list"  <if condition="$_GET['staff_list'] neq -1 AND !empty($_GET['staff_list'])">checked value="{pigcms{$_GET['staff_list']}" <else />value="0"</if> >
							<a href="javascript:void(0)" id="edit_staff" style="display:none;line-height: 16px;" data-type="part_staff">编辑</a>
							<br>
							<span class="fl">日期筛选：</span>
							<input type="text" class="input-text fl" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text fl" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							
							<div class="fl time-choice-wrap cf stat-time-choice" style="float: left;">
								<span class="time-btn time-choice  <if condition="$_GET['selectTimeType'] eq 1">time-current</if>" timetype="1" id="choice-today">
								今天</span>
								<span class="time-btn time-choice bor-gray <if condition="$_GET['selectTimeType'] eq 7">time-current</if>" id="choice-week" timetype="7" >
								近7天</span>
								<span class="time-btn time-choice <if condition="$_GET['selectTimeType'] eq 30">time-current</if>" id="choice-month" timetype="30" >
								近30天</span>
								<input type="hidden" id="_selectTimeType" name="selectTimeType" value="{pigcms{$_GET['selectTimeType']}">
							</div>
						
							
						
							<span class="fl" style="margin-left: 30px;">支付方式筛选</span>
							<!--select id="pay_type" name="pay_type">
								<option value="" <if condition="$key eq $pay_type">selected="selected"</if>>全部支付方式</option>
									<volist name="pay_method" id="vo">
										<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
									</volist>
								<option value="balance" <if condition="$key eq $pay_type">selected="selected"</if>>余额支付</option>
							</select-->
							
							<select id="pay_type" name="pay_type" >
									<option value="all" <if condition="'all' eq $_GET['pay_type']">selected="selected"</if>>全部支付方式</option>
								<volist name="pay_method" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $_GET['pay_type']">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
									<option value="balance" <if condition="'balance' eq $_GET['pay_type']">selected="selected"</if>>余额支付</option>
							</select>
							<input type="submit" value="查询" class="button"/>　　
							<input type="reset" id="reset" value="清空" class="button"/>
					
							<a href="javascript:void(0)" onclick="exports()" class="button" style="margin-right: 10px;">导出订单</a>
					</form>
				</div>
				<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
					<div class="white-bg receivables-statist cf" style="padding: 0px 5	px;margin-bottom: 15px;">
						<h3 class="payment-titile clearfix" style="font-size:16px;">
							<span class="title-text">关键指标 <i id="layer-tip" style="    font-size: 16px;border-radius: 50%;border: 1px solid orange;padding: 0px 7px;color: orange;font-style: normal;">?</i></span>
							<div class="com-layer-box">
								<i class="icon-question-sign com-layer" ></i>
								<span class="com-layer-text dn" style="display:none">
									<p>1. 营业金额：即支付成功+退
										款失败的订单金额之和;</p>
									<p>2. 营业单数：即支付成功+退
										款失败的订单数之和;</p>
									<p>3. 实收金额：即支付成功+退
										款失败的已验证消费金额之和;</p>
									<p>4. 实收单数：即支付成功+退
										款失败的已验证消费订单数之和;</p>
									<p>5. 退款金额：退款成功的退款金额之和;</p>
									<p>6. 退款单数：退款成功的退款订单数之和;</p>
								</span>
							</div>
						</h3>
						
						<div class="statist-wrap-list">
								<div class="statist-wrap fl" style="float:left;margin-right:50px;">
									<p><span class="statist-wrap-title sta-total">帐单总额</span></p>
									<p class="sta-mon">{pigcms{$total_money_sum|floatval}</p>
									<p class="sta-num">{pigcms{$total_money_count|floatval}笔</p>
								</div>
								<div class="statist-wrap fl"  style="float:left;margin-right:50px;">
									<p><span class="statist-wrap-title sta-trade">实收金额</span></p>
									<p class="sta-mon">{pigcms{$consume_money_sum|floatval}</p><p class="sta-num">{pigcms{$consume_money_count|floatval}笔</p>
									</div>
							
								<div class="statist-wrap fl statist-wrap-spe"  style="float:left">
									<p><span class="statist-wrap-title sta-refund">退款金额</span></p>
									<p class="sta-mon">{pigcms{$refund_money_sum|floatval}</p><p class="sta-num">{pigcms{$refund_money_count|floatval}笔</p>
								</div>
						</div>
					</div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
						
						<thead>
							<tr>
								<th>日期</th>
								<th>商家信息</th>
								<th>店铺信息</th>
								<th>营业金额</th>
								<th>营业单数</th>
								<th>实收金额</th>
								<th>实收单数</th>
								<th>退款金额</th>
								<th>退款单数</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($sale_date)">
								<volist name="sale_date" id="vo">
									<tr>
										<td>{pigcms{$vo.date_id}</td>
										<td>{pigcms{$vo.merchant_name}</td>
										<td>{pigcms{$vo.store_name}</td>
										<td>{pigcms{$vo.total_money|floatval}</td>
										<td>{pigcms{$vo.total_count|floatval}</td>
										<td>{pigcms{$vo.consume_money|floatval}</td>
										<td>{pigcms{$vo.consume_count|floatval}</td>
										<td>{pigcms{$vo.refund_money|floatval}</td>
										<td>{pigcms{$vo.refund_count|floatval}</td>
										
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
							</if>
						</tbody>
					
						</table>
			
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
.statist-wrap{width:15%;margin-right:2.3%;padding:0 15px;border:1px solid #ccc;border-radius:5px;margin-bottom:15px}.sta-mon{font-size:25px;color:#333;width:100%;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1}.sta-num{font-size:18px;color:#999;width:100%;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.sta-total{background:#1c84c6;padding:2px 7px;color:#fff}.sta-trade{background:#23c6c8;padding:2px 7px;color:#fff}.sta-refund{background:#ed5565;padding:2px 7px;color:#fff}.time-choice-wrap{margin:0 10px;border-radius:3px;border:1px solid #ccc}.time-choice{float:left;display:block;padding:0 10px;line-height:29px;font-size:12px;color:#333;outline:0;cursor:pointer}.time-choice.time-current{color:#fff;background:#1ab394}.bor-gray{border-left:1px solid #ccc;border-right:1px solid #ccc}.fl{margin-right:5px;margin-bottom:5px;}.plat_form{display:none;} 
</style>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
	$(function(){
		if($('#type').val()=='shop'){
				$('.plat_form').show()
			}
		$('#type').change(function(){
				console.log($(this).val())
			if($(this).val()=='shop'){
				$('.plat_form').show()
			}else{
				$('.plat_form').hide()
			}
		})
		
		$('#reset').click(function(){
			window.location.href="{pigcms{:U(trade)}";
		})
		
	$('#layer-tip').on('mouseover',function(){
		console.log(111)
		//$('.com-layer-text').show();
		layer.tips($('.com-layer-text').html(), '#layer-tip',{
		  tips: 1
		});
	});
	$('#layer-tip').on('mouseout',function(){
		layer.closeAll()
	});
		
	$('.time-btn').click(function(){
		$('.time-btn').removeClass('time-current')
		$(this).addClass('time-current');
		$('input[name="begin_time"]').val('')
		$('input[name="end_time"]').val('')
		$('#_selectTimeType').val($(this).attr('timetype'))
		$('#myform1').submit();
	})
	$('input:radio').click(function(){
		var obj = $(this);
		if($(obj).attr('name')=='staff_list'){
			var title= '选择店员'
		}else{
			var title= '选择店铺'
		}
		var type= $(obj).data('type')
	
		
		if(type=='part_staff' || type=='part_store'){
			if(type=='part_store'){	
				window.top.art.dialog.data('store_list', $(obj).val());			
				var href= $(obj).attr('href')+'&type'+type+'&store_list='+$(obj).val();
				$('#edit_store').show();
			}else{
				window.top.art.dialog.data('staff_list', $(obj).val());
				var href= $(obj).attr('href')+'&type'+type+'&staff_list='+$(obj).val()+'&store_list='+$('#part_store').val();
				$('#edit_staff').show();
			}
			
			window.top.art.dialog.open(href,{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: type,
				title:title,
				padding: 0,
				width: 600,
				height: 500,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4',
				close:function(){
					 if(type=='part_staff'){	
						var staff_list = window.top.art.dialog.data('staff_list'); 
						$(obj).val(staff_list)
					}else{
						var store_list = window.top.art.dialog.data('store_list'); 
						$(obj).val(store_list)
						$('#part_staff').val(0)
						$("input[name='staff_list']:eq(0)").attr("checked",'checked'); 
						$("input[name='staff_list']:eq(1)").removeAttr("checked");
						$('#edit_staff').hide();
					}
				}
			});
						
		}else{
			if(type=='all_store'){	
				$('#edit_store').hide();
			}else{
				$('#edit_staff').hide();
			}
		}
		
	})
	
	$('#edit_store').click(function(){
		var obj = $('#part_store');
		var title= '选择店铺'
		var type= $(obj).data('type')
		window.top.art.dialog.data('store_list', $(obj).val());			
		var href= $(obj).attr('href')+'&type'+type

		window.top.art.dialog.open(href,{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: type,
			title:title,
			padding: 0,
			width: 600,
			height: 500,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4',
			close:function(){
				var store_list = window.top.art.dialog.data('store_list'); 
				$(obj).val(store_list)
				$('#part_staff').val(0)
			
				$("input[name='staff_list']:eq(0)").attr("checked",'checked'); 
				$("input[name='staff_list']:eq(1)").removeAttr("checked");
				$('#edit_staff').hide();
			}
		});
	})
	
	
	$('#edit_staff').click(function(){
		var obj = $('#part_staff');
		var title= '选择店员'
		var type= $(obj).data('type')
		window.top.art.dialog.data('staff_list', $(obj).val());
		var href= $(obj).attr('href')+'&type'+type+'&staff_list='+$(obj).val()+'&store_list='+$('#part_store').val();

		window.top.art.dialog.open(href,{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: type,
			title:title,
			padding: 0,
			width: 600,
			height: 500,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4',
			close:function(){
				var staff_list = window.top.art.dialog.data('staff_list'); 
		
				$(obj).val(staff_list)
				
			}
		});
	})
	});


	// $('#merchant_worker_id').change(function(){
		// var merchant_worker_id = $(this).val();
		// var Url="{pigcms{:U('Appoint/order_list')}"+'&merchant_worker_id='+merchant_worker_id;
		// location.href=Url;
	// });

	
    var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('Analysis/export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
<include file="Public:footer"/>
