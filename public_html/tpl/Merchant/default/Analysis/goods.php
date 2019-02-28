<include file="Public:header"/>


<div class="main-content">

	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Analysis/goods')}" class="on">菜品信息</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				 <div class="form-group">
					<form action="{pigcms{:U('Appoint/order_list')}" method="get" id="myform1">
						<input type="hidden" name="c" value="Analysis"/>
						<input type="hidden" name="a" value="goods"/>
						
							<span class="fl">业务类型：</span>
							<select class="fl" id="type" name="type">
								<volist name="type_name" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $_GET['type']">selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
									
							<span class="fl" style="margin-left:60px">选择店铺：</span>
							<span class="fl">全部店铺</span><input type="radio" href="{pigcms{:U('Analysis/store_list')}" class="input-text fl "  data-type="all_store" name="store_list" value="-1" <if condition="$_GET['store_list'] eq -1 OR !isset($_GET['store_list'])">checked </if> >
							<span class="fl">部分店铺</span><input type="radio" href="{pigcms{:U('Analysis/store_list')}" class="input-text fl " id="part_store" data-type="part_store" name="store_list" <if condition="$_GET['store_list'] neq -1 AND !empty($_GET['store_list'])">checked value="{pigcms{$_GET['store_list']}" <else />value="0"</if>>
							
							<a href="javascript:void(0)" id="edit_store" style="display:none;line-height: 16px;" data-type="part_store">编辑</a>
							
							<span class="fl" style="margin-left: 30px;">商品类别：</span>
							<span class="fl">全部类别</span><input type="radio"  class="input-text fl " href="{pigcms{:U('Analysis/type_list')}" data-type="all_type" name="type_list" value="-1" <if condition="$_GET['type_list'] eq -1 OR !isset($_GET['type_list'])">checked </if>>
							<span class="fl">部分类别</span><input type="radio"  class="input-text fl "  href="{pigcms{:U('Analysis/type_list')}" id="part_type" data-type="part_type" name="type_list"  <if condition="$_GET['type_list'] neq -1 AND !empty($_GET['type_list'])">checked value="{pigcms{$_GET['type_list']}" <else />value="0"</if> >
							<a href="javascript:void(0)" id="edit_type" style="display:none;line-height: 16px;" data-type="part_type">编辑</a>
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
						
							
						
							<input type="text" class="input-text fl" style="width:120px;" id="d4311" name="goods_name" value="{pigcms{$_GET['goods_name']}" placeholder="商品名称检索"/>	
							<span class="fl">排名前：</span>
							<input type="text" class="input-text fl" style="width:60px;"  name="sort" value="{pigcms{$_GET['sort']}" />	
							
							<input type="submit" value="查询" class="button"/>　　
							<input type="reset" value="清空" id="reset"  class="button"/>
					
							<a href="javascript:void(0)" onclick="exports()" class="button" style="margin-right: 10px;">导出订单</a>
					</form>
				</div>
				<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
						
				
						<thead>
							<tr>
								<th>排名</th>
								<th>菜品名称</th>
								<th>规格</th>
								<th>单价</th>
								<th>销售数量</th>
								<th>销售金额</th>
								<th>销售占比</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($goods_date)">
								<volist name="goods_date" id="vo">
									<tr>
										<td><php>if($_GET['page']>1){ echo $i+15*($_GET['page']-1);}else {echo $i;}</php></td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.spec}</td>
										<td>{pigcms{$vo.price|floatval}</td>
										<td>{pigcms{$vo.sale_count|floatval}</td>
										<td>{pigcms{$vo.sale_money|floatval}</td>
										<td>{pigcms{$vo.sale_percent}</td>
										
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
.statist-wrap{width:15%;margin-right:2.3%;padding:0 15px;border:1px solid #ccc;border-radius:5px;margin-bottom:15px}.sta-mon{font-size:25px;color:#333;width:100%;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1}.sta-num{font-size:18px;color:#999;width:100%;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.sta-total{background:#1c84c6;padding:2px 7px;color:#fff}.sta-trade{background:#23c6c8;padding:2px 7px;color:#fff}.sta-refund{background:#ed5565;padding:2px 7px;color:#fff}.time-choice-wrap{margin:0 10px;border-radius:3px;border:1px solid #ccc}.time-choice{float:left;display:block;padding:0 10px;line-height:29px;font-size:12px;color:#333;outline:0;cursor:pointer}.time-choice.time-current{color:#fff;background:#1ab394}.bor-gray{border-left:1px solid #ccc;border-right:1px solid #ccc}.fl{line-height:16px;margin-right:5px;margin-bottom:5px;}.plat_form{display:none;} 
</style>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
	$(function(){

	$('#reset').click(function(){
		window.location.href="{pigcms{:U(goods)}";
	})
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
		if($(obj).attr('name')=='store_list'){
			var title= '选择店员'
		}else{
			var title= '菜品类别'
		}
		var type= $(obj).data('type')
	
		
		if(type=='part_type' || type=='part_store'){
			if(type=='part_store'){	
				window.top.art.dialog.data('store_list', $(obj).val());			
				var href= $(obj).attr('href')+'&type'+type+'&store_list='+$(obj).val();
				$('#edit_store').show();
			}else{
				window.top.art.dialog.data('type_list', $(obj).val());
				var href= $(obj).attr('href')+'&type='+$('#type').val()+'&type_list='+$(obj).val()+'&store_list='+$('#part_store').val();
				$('#edit_type').show();
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
					 if(type=='part_type'){	
						var type_list = window.top.art.dialog.data('type_list'); 
						$(obj).val(type_list)
					}else{
						var store_list = window.top.art.dialog.data('store_list'); 
						$(obj).val(store_list)
						$('#part_type').val(0)
						$("input[name='type_list']:eq(0)").attr("checked",'checked'); 
						$("input[name='type_list']:eq(1)").removeAttr("checked");
						$('#edit_type').hide();
					}
				}
			});
						
		}else{
			if(type=='all_store'){	
				$('#edit_store').hide();
			}else{
				$('#edit_type').hide();
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
				$('#part_type').val(0)
			
				$("input[name='type_list']:eq(0)").attr("checked",'checked'); 
				$("input[name='type_list']:eq(1)").removeAttr("checked");
				$('#edit_type').hide();
			}
		});
	})
	
	
	$('#edit_type').click(function(){
		var obj = $('#part_type');
		var title= '菜品类型'
		var type= $(obj).data('type')
		window.top.art.dialog.data('type_list', $(obj).val());
		var href= $(obj).attr('href')+'&type'+type+'&type_list='+$(obj).val()+'&store_list='+$('#part_store').val();

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
				var type_list = window.top.art.dialog.data('type_list'); 
		
				$(obj).val(type_list)
				
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
    var export_url = "{pigcms{:U('Analysis/goods_export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
<include file="Public:footer"/>
