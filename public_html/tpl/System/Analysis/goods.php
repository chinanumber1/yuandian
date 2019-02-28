<include file="Public:header"/>
<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js"></script>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Analysis/goods')}" class="on">菜品分析</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Analysis/goods')}" method="get" id="myform1">
							<input type="hidden" name="c" value="Analysis"/>
							<input type="hidden" name="a" value="goods"/>
							
							<if condition="$now_area['area_type'] neq 3">
								<span class="fl">地区筛选：</span>
								<div class="fl" id="choose_pca" province_idss="{pigcms{$_GET.province_idss}" city_idss="{pigcms{$_GET.city_idss}" area_id="{pigcms{$_GET.area_id}" style="display:inline"></div>
							</if>
							
							<span class="fl">业务类型：</span>
							<select class="fl" id="type" name="type">
								<volist name="type_name" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $_GET['type']">selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
							<span class="fl" style="margin-left: 30px;">选择商家：</span>
							<span class="fl">全部商家</span><input type="radio"  class="input-text fl " href="{pigcms{:U('Analysis/mer_list')}" data-type="all_mer" name="mer_list" value="-1" <if condition="$_GET['mer_list'] eq -1 OR !isset($_GET['mer_list'])">checked </if>>
							<span class="fl">部分商家</span><input type="radio"  class="input-text fl "  href="{pigcms{:U('Analysis/mer_list')}" id="part_mer" data-type="part_mer" name="mer_list"  <if condition="$_GET['mer_list'] neq -1 AND !empty($_GET['mer_list'])">checked value="{pigcms{$_GET['mer_list']}" <else />value="0"</if> >
							<a href="javascript:void(0)" id="edit_mer" style="display:none;float:left;line-height: 16px;" data-type="part_mer">编辑</a>
							
							<span class="fl" style="margin-left:60px">选择店铺：</span>
							<span class="fl">全部店铺</span><input type="radio" href="{pigcms{:U('Analysis/store_list')}" class="input-text fl "  data-type="all_store" name="store_list" value="-1" <if condition="$_GET['store_list'] eq -1 OR !isset($_GET['store_list'])">checked </if> >
							<span class="fl">部分店铺</span><input type="radio" href="{pigcms{:U('Analysis/store_list')}" class="input-text fl " id="part_store" data-type="part_store" name="store_list" <if condition="$_GET['store_list'] neq -1 AND !empty($_GET['store_list'])">checked value="{pigcms{$_GET['store_list']}" <else />value="0"</if>>
							
							<a href="javascript:void(0)" id="edit_store" style="display:none;float:left;line-height: 16px;" data-type="part_store">编辑</a>
							<br>
							<span class="fl">日期筛选：</span>
							<input type="text" class="input-text fl" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text fl" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							
							<div class="fl time-choice-wrap cf stat-time-choice">
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
							<input type="reset" value="清空" id="reset" class="button"/>
							<a  href="javascript:void(0)" onclick="exports()" class="button" >导出</a>
						</form>
					</td>
				
				</tr>
			</table>
			
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<style>
					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}
					.statist-wrap{
						width: 15%;
						margin-right: 2.3%;
						padding:0 15px;
						border: 1px solid #ccc;
						border-radius: 5px;
						margin-bottom:15px;
					}
					.sta-mon{
					
						font-size: 25px;
						color: #333;
						width: 100%;
						text-align: center;
						white-space: nowrap;
						overflow: hidden;
						text-overflow: ellipsis;
						line-height: 1;
					}
					.sta-num{
						font-size: 18px;
						color: #999;
						width: 100%;
						text-align: center;
						white-space: nowrap;
						overflow: hidden;
						text-overflow: ellipsis;
					}
					
					.sta-total{
						background: #1c84c6;
						padding: 2px 7px;
						color: #fff;
					}
					.sta-trade{
						background: #23c6c8;
						padding: 2px 7px;
						color: #fff;
					}
					.sta-refund{
						background: #ed5565;
						padding: 2px 7px;
						color: #fff;
					}
					
					.time-choice-wrap {
						margin: 0 10px;
						border-radius: 3px;
						border: 1px solid #ccc
					}

					.time-choice {
						float: left;
						display: block;
						padding: 0 10px;
						line-height: 29px;
						font-size: 12px;
						color: #333;
						outline: 0;
						cursor: pointer
					}

					.time-choice.time-current {
						color: #fff;
						background: #1ab394
					}

					.bor-gray {
						border-left: 1px solid #ccc;
						border-right: 1px solid #ccc
					}
					.fl{
						line-height: 16px;
						margin-right:5px;
					}
				
					</style>
				
					<table width="100%" cellspacing="0">
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
			</form>
		</div>
<script>
$(function(){
	// $('#status').change(function(){
		// location.href = "{pigcms{:U('Group/order', array('type' => $type, 'sort' => $sort))}&status=" + $(this).val();
	// });	
	// $('#pay_type').change(function(){
		// location.href = "{pigcms{:U('Group/order', array('type' => $type, 'sort' => $sort))}&pay_type=" + $(this).val();
	// });	
	$('#reset').click(function(){
		window.location.href="{pigcms{:U(goods)}";
	})
	$('#layer-tip').click(function(){
		layer.tips('默认就是向右的', '#id或者.class');
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
		if($(obj).attr('name')=='mer_list'){
			var title= '选择商家'
		}else{
			var title= '选择店铺'
		}
		var type= $(obj).data('type')
	
		if(type=='part_mer' || type=='part_store'){
			if(type=='part_mer'){	
				window.top.art.dialog.data('mer_list', $(obj).val());			
				var href= $(obj).attr('href')+'&province='+$('#choose_provincess').val()+'&city='+$('#choose_cityss').val()+'&area='+$('#choose_areass').val()+'&type'+type+'&mer_list='+$(obj).val();
				$('#edit_mer').show();
			}else{
				window.top.art.dialog.data('store_list', $(obj).val());
				var href= $(obj).attr('href')+'&province='+$('#choose_provincess').val()+'&city='+$('#choose_cityss').val()+'&area='+$('#choose_areass').val()+'&type'+type+'&store_list='+$(obj).val()+'&mer_list='+$('#part_mer').val();
				$('#edit_store').show();
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
					if(type=='part_mer'){	
						var mer_list = window.top.art.dialog.data('mer_list'); 
						$(obj).val(mer_list)
						$('#part_store').val(0)
						$("input[name='store_list']:eq(0)").attr("checked",'checked'); 
						$("input[name='store_list']:eq(1)").removeAttr("checked");
						$('#edit_store').hide();
					}else{
						var store_list = window.top.art.dialog.data('store_list'); 
						$(obj).val(store_list)
					}
				}
			});
						
		}else{
			if(type=='all_mer'){	
				$('#edit_mer').hide();
			}else{
				$('#edit_store').hide();
			}
		}
		
	})
	
	
	$('#edit_mer').click(function(){
		var obj = $('#part_mer');
		var title= '选择商家'
		var type= $(obj).data('type')
		window.top.art.dialog.data('mer_list', $(obj).val());			
		var href= $(obj).attr('href')+'&province='+$('#choose_provincess').val()+'&city='+$('#choose_cityss').val()+'&area='+$('#choose_areass').val()+'&type'+type+'&mer_list='+$(obj).val();

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
				  var mer_list = window.top.art.dialog.data('mer_list'); 
				  $(obj).val(mer_list)
				$('#part_store').val(0)
				$("input[name='store_list']:eq(0)").attr("checked",'checked'); 
				$("input[name='store_list']:eq(1)").removeAttr("checked");
					$('#edit_store').hide();
			}
		});
	})
	
	
	$('#edit_store').click(function(){
		var obj = $('#part_store');
		var title= '选择店铺'
		var type= $(obj).data('type')
		window.top.art.dialog.data('store_list', $(obj).val());
		var href= $(obj).attr('href')+'&province='+$('#choose_provincess').val()+'&city='+$('#choose_cityss').val()+'&area='+$('#choose_areass').val()+'&type'+type+'&store_list='+$(obj).val()+'&mer_list='+$('#part_mer').val();

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
				console.log(store_list)
				$(obj).val(store_list)
			}
		});
	})
});

   var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('Analysis/goods_export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
<include file="Public:footer"/>