<include file="Public:header"/>
<div class="main-content">
<!-- 内容头部 -->
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-tablet"></i>
			<a href="{pigcms{:U('owner_arrival')}">功能库列表</a>
		</li>
		<li class="active">在线付款</li>
	</ul>
</div>
<!-- 内容头部 -->
<style type="text/css">
.form_list{width:45%; float:left}
.form_list select{margin-right:10px;height:42px;}
.col-sm-1{width:23%}
</style>

	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
						<form method="post" id="find-form" onSubmit="return check_user_submit()" class="form_list">
							<select name="find_type" id="find_type" class="col-sm-2">
								<option value="1">房主姓名</option>
								<option value="2">手机号码</option>
								<option value="3">物业编号</option>
							</select>
							<input class="col-sm-4" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:42px;"/>

							<input class="btn btn-success" type="submit" id="find_submit" value="查找业主" style="margin-right:10px;"/>
							<a class="btn btn-success" onclick="location.href='{pigcms{:U('owner_arrival_add')}'">重置</a>
						</form>
						
						
						<!--form method="post" class="form_list">
							<div id="choose_cityarea">
							</div>
							
							<input class="btn btn-success" type="button" id="search_find" value="查找业主" />
						</form-->
						<div class="clearfix"></div>
					</div>
					<div class="form-group user_list" style="border:1px solid #c5d0dc;padding:10px; display:none">
						<span>物业信息:</span>
						<p class="user_list_content">
						</p>
					</div>
				
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="property_id">物业缴费类型</label></label>
									<label class="col-sm-1">
										<select name="type" id="type">
											<option value="property" selected>物业费</option>
											<option value="electric">电费</option>
											<option value="water">水费</option>
											<option value="gas">燃气费</option>
											<option value="park">停车费</option>
											<option value="custom">临时缴费</option>
											
										</select>
										<label id="custom_name" style="display:none">缴费名称：<input type="text"  value="" name="custom_remark"></label>
									</label>
									<label class="col-sm-2 property_desc red"></label>
								</div>
								<div class="form-group pay_money property">
									<label class="col-sm-1"><label for="property_id">物业缴费周期</label></label>
									<label class="col-sm-1">
										<select name="property_id" id="property">
											<option value="0">请选择</option>
											<volist name='list["list"]' id='row'>
												<option value='{pigcms{$row["id"]}' data-id="{pigcms{$row['id']}">{pigcms{$row.property_month_num}个月</option>
											</volist>
										</select>
									</label>
									<label class="col-sm-2  red"></label>
								</div>
								<div class="form-group pay_money electric">
									<label class="col-sm-1"><label for="property_id">缴费总额</label></label>
									<label class="col-sm-1">
										<input type="text" readonly value="" name="electric_price">元
									</label>
									<label class="col-sm-2  red"></label>
								</div>
								<div class="form-group pay_money water">
									<label class="col-sm-1"><label for="property_id">缴费总额</label></label>
									<label class="col-sm-1">
										<input type="text" readonly value="" name="water_price">元
									</label>
									<label class="col-sm-2  red"></label>
								</div>
								<div class="form-group pay_money gas">
									<label class="col-sm-1"><label for="property_id">缴费总额</label></label>
									<label class="col-sm-1">
										<input type="text" readonly value="" name="gas_price">元
									</label>
									<label class="col-sm-2  red"></label>
								</div>
								<div class="form-group pay_money park">
									<label class="col-sm-1"><label for="property_id">缴费总额</label></label>
									<label class="col-sm-1">
										<input type="text" readonly value="" name="park_price">元
									</label>
									<label class="col-sm-2  red"></label>
								</div>
								
								<div class="form-group pay_money custom">
									<label class="col-sm-1"><label for="property_id">缴费总额</label></label>
									<label class="col-sm-1" style="width:40%">
										
										<input type="text"  value="" name="custom_price">元
										
									</label>
									
									<label class="col-sm-2  red"></label>
								</div>
								
								<div class="form-group pay_money custom_payment">
									<label class="col-sm-1"><label for="custom_payment_id">自定义缴费周期</label></label>
									<label class="col-sm-6">
										<input type="text" onkeyup="cycle_keyup(this,this.value)" value="1" id="payment_paid_cycle" name="payment_paid_cycle">
										<span style="color: red; margin-left: 10px;">最大缴费周期（<span id="max_cycle_sum">0</span>/周期）</span>
									</label>
									<label class="col-sm-2  red"></label>
									<input type="hidden" name="payment_name" value="" id="payment_name">
									<input type="hidden" name="payment_bind_id" value="" id="bind_id">
								</div>
								
								<div class="form-group pay_money custom_payment" id="custom_info_html">
								</div>

								<script>
									function cycle_keyup(obj,diy_cycle){
										var cycle_sum = $("#cycle_sum").val();
										var paid_cycle = $("#paid_cycle").val();
										var max_cycle = cycle_sum-paid_cycle;
										var price = $("#price").val();
										if(diy_cycle>max_cycle){
											$(obj).val(max_cycle);
											diy_cycle = max_cycle;
										}else if(diy_cycle<1){
											$(obj).val(1);
											diy_cycle = 1;
										}
										$("#payment_price").val(parseFloat(diy_cycle*price).toFixed(2));
									}
								</script>

								<div class="form-group pay_money custom_payment">
									<label class="col-sm-1"><label for="custom_payment_id">缴费金额</label></label>
									<label class="col-sm-1">
										<input type="text" id="payment_price" value="" name="payment_price">元
									</label>
									<label class="col-sm-2  red"></label>
								</div>
								
								<div class="form-group  ">
									<label class="col-sm-1"><label for="usernum">备注</label></label>
									<label class="col-sm-1">
										<textarea id="remarks_bak" style="resize: none; width: 300px; height: 80px;"></textarea>
										<input type="hidden" name="remarks" value="" id="remarks">
									</label>
								</div>

								
								<div class="form-group  ">
									<label class="col-sm-1"><label for="usernum">业主物业编号</label></label>
									<label class="col-sm-1">
										<input type="text" placeholder="请输入业主物业编号" name="usernum" id="usernum" />
									</label>
								</div>
							</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa bigger-110"></i>
										生成订单
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<input type="hidden" id="pay_type" name="pay_type" value=""/>
	<input type="hidden" id="pay_money" name="pay_money" value=""/>
	<input type="hidden" id="paid_cycle" name="paid_cycle" value=""/>
	<input type="hidden" id="cycle_sum" name="cycle_sum" value=""/>
	<input type="hidden" id="metering_mode_val" name="metering_mode_val" value=""/>
	<input type="hidden" id="cycle_type" name="cycle_type" value=""/>
	<input type="hidden" id="price" name="price" value=""/>
	<input type="hidden" id="metering_mode" name="metering_mode" value="">
	<input type="hidden" id="start_time" name="start_time" value="">
	<input type="hidden" id="end_time" name="end_time" value="">



<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$('#search_find').live('click',function(){
	var owner_id = $('select[name="owner_id"]').val()
	var search_url = "{pigcms{:U('search_owner_info')}&owner_id="+owner_id;
	art.dialog.open(search_url,{
		init: function(){
			var iframe = this.iframe.contentWindow;
			window.top.art.dialog.data('iframe_handle',iframe);
		},
		id: 'handle',
		title:'业主信息',
		padding: 0,
		width: 720,
		height: 400,
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


$(function(){
	$('.pay_money').hide();
	$('.property').show();
	$('#type').change(function(){
		var type = $(this).val();
		$('.pay_money').hide();
		$('.'+type).show();
		if(type!='property'){
			$('.property_desc').html('');
			$('#property option:first').attr('selected','selected');
		}
		if(type=='custom'){
			$('#custom_name').show()
		}else{
			$('#custom_name').hide()
		}

		if(type == 'custom_payment'){
			var pay_type = $("option:selected",this).data('pay_type');
			var pay_money = $("option:selected",this).data('pay_money');
			var paid_cycle = $("option:selected",this).data('paid_cycle');
			var cycle_sum = $("option:selected",this).data('cycle_sum');
			var metering_mode_val = $("option:selected",this).data('metering_mode_val');
			var metering_mode = $("option:selected",this).data('metering_mode');
			var cycle_type = $("option:selected",this).data('cycle_type');
			var payment_name = $("option:selected",this).data('payment_name');
			var start_time = $("option:selected",this).data('start_time');
			var end_time = $("option:selected",this).data('end_time');
			var pay_cycle = $("option:selected",this).data('pay_cycle');
			var bind_id = $("option:selected",this).data('bind_id');

			$("#pay_type").val(pay_type);
			$("#pay_money").val(pay_money);
			$("#paid_cycle").val(paid_cycle);
			$("#cycle_sum").val(cycle_sum);
			$("#metering_mode_val").val(metering_mode_val);
			$("#cycle_type").val(cycle_type);
			$("#pay_money").val(pay_money);
			$("#payment_name").val(payment_name);
			$("#bind_id").val(bind_id);

			var max_cycle = cycle_sum-paid_cycle;
			
			$("#max_cycle_sum").html(max_cycle);

			if(pay_type == 2){
				var price = metering_mode_val*pay_money;
				var pay_type_html =  '按金额*数量';
				var metering_mode_html = '<p style="margin-left:24%;"><span>'+metering_mode+' : </span> <span style="color:#87b87f;"><span>'+metering_mode_val+'</span></span></p>';
			}else{
				var price = pay_money;
				var pay_type_html =  '固定模式';
				var metering_mode_html = '';
			}

			var infohtml = '<p style="margin-left:14px;">缴费信息</p> <p style="margin-left:24%;"><span>计算公式 : </span> <span style="color:#87b87f;">'+pay_type_html+'</span></p> '+metering_mode_html+' <p style="margin-left:24%;"><span>收费金额 : </span> <span style="color:#87b87f;"><span>'+price+'</span>&nbsp;元</span></p> <p style="margin-left:24%;"><span>收费周期 : </span> <span style="color:#87b87f;">'+pay_cycle+cycle_type+'/周期</span></p> <p style="margin-left:24%;"><span>服务日期 : </span> <span style="color:#87b87f;">'+start_time+'&nbsp;至&nbsp;'+end_time+' （'+cycle_sum+'周期）</span></p> <p style="margin-left:24%;"><span>您已缴费的周期的个数为 : </span> <span><span style="color:#87b87f;">'+paid_cycle+'</span>&nbsp;个</span></p>';
			
			$("#custom_info_html").html(infohtml);

			
			$("#payment_price").val(price);
			$("#price").val(price);

		}
	});
	
	$('#usernum').keyup(function(){
		var usernum  = $(this).val();
		var ajax_user_list_url = "{pigcms{:U('ajax_user_list')}";
		$.post(ajax_user_list_url,{usernum:usernum},function(data){
			var shtml = '';
			
			if(data.status){
				$('.user_list').show();
				for(var i in data['user_list']){
					var data_ = data['user_list'][i];
					console.log(data_)
					shtml += '<span class="red">编号：' + data['user_list'][i]['usernum'] + '&nbsp;&nbsp;|&nbsp;&nbsp;业主姓名：'+data['user_list'][i]['name']+'&nbsp;&nbsp;|&nbsp;&nbsp;地址：' + data['user_list'][i]['address'] + '</span><br />';
					$('input[name="electric_price"]').val(data_.electric_price)
					$('input[name="water_price"]').val(data_.water_price)
					$('input[name="gas_price"]').val(data_.gas_price)
					$('input[name="park_price"]').val(data_.park_price)

					var paymentHtml = '';
					$("#type option[value='custom_payment']").remove();
					for (var l in data_.payment_list) {
						var payment_ = data_.payment_list[l];

						paymentHtml += '<option value="custom_payment" data-pay_type="'+payment_.pay_type+'" data-pay_money="'+payment_.pay_money+'" data-paid_cycle="'+payment_.paid_cycle+'" data-cycle_sum="'+payment_.cycle_sum+'" data-metering_mode_val="'+payment_.metering_mode_val+'" data-metering_mode="'+payment_.metering_mode+'" data-pay_cycle="'+payment_.pay_cycle+'" data-cycle_type="'+payment_.cycle_type+'" data-payment_name="'+payment_.payment_name+'" data-start_time="'+payment_.start_time+'" data-end_time="'+payment_.end_time+'" data-bind_id="'+payment_.bind_id+'">'+payment_.payment_name+'('+payment_.remarks+')'+'</option>';
					}
					$("#type").append(paymentHtml);

				}
				$('.user_list_content').html(shtml);
			}else{
				shtml +='暂无';
				$('.user_list_content').html(shtml);
			}
		},'json')
	});
})
	function check_submit(){
		if($('#type').val()=='property'){
			var property = $("#property").val();
			
			if(property<=0){
				alert('请选择物业缴费周期');
				return false;
			}
			
			var usernum = $("#usernum").val();
			if(usernum == ''){
				alert('请填写业主物业编号');
				return false;
			}
		}

		if($('#type').val()=='custom_payment'){

			var payment_price = $("#payment_price").val();
			
			if(payment_price<=0){
				alert('缴费金额不可以为空');
				return false;
			}
			
			var payment_paid_cycle = $("#payment_paid_cycle").val();
			if(payment_paid_cycle <=0){
				alert('请输入缴费周期');
				return false;
			}

		}
		$("#remarks").val($("#remarks_bak").val());
		if(confirm('确认生成订单？')){
			return true;
		}else{
			return false;
		}
	}
	
	function check_user_submit(){
		var ajax_user_list_url = "{pigcms{:U('ajax_user_list')}";
		$.post(ajax_user_list_url,$('#find-form').serialize(),function(data){
			var shtml = '';
			if(data.status){
				$('.user_list').show();
				for(var i in data['user_list']){
					var data_ = data['user_list'][i];
					console.log(data_)
					shtml += '<span class="red">编号：' + data['user_list'][i]['usernum'] + '&nbsp;&nbsp;|&nbsp;&nbsp;业主姓名：'+data['user_list'][i]['name']+'&nbsp;&nbsp;|&nbsp;&nbsp;地址：' + data['user_list'][i]['address'] + '</span><br />';
					$('input[name="electric_price"]').val(data_.electric_price)
					$('input[name="water_price"]').val(data_.water_price)
					$('input[name="gas_price"]').val(data_.gas_price)
					$('input[name="park_price"]').val(data_.park_price)
				}
				$('.user_list_content').html(shtml);
			}else{
				shtml +='暂无';
				$('.user_list_content').html(shtml);
			}
		},'json')
		return false;
	}
	
	


var choose_province="{pigcms{:U('ajax_unit')}",choose_floor="{pigcms{:U('ajax_floor')}",choose_layer="{pigcms{:U('ajax_layer')}",choose_owner="{pigcms{:U('ajax_owner')}";

if(document.getElementById('choose_cityarea')){
	show_unit();
}	

function show_unit(){
	$.post(choose_province,function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select class="col-sm-2" id="choose_province" name="unit_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.floor_id+'" '+(item.floor_id==$('#choose_cityarea').attr('province_id') ? 'selected="selected"' : '')+'>'+item.floor_name+'</option>';
			});
			area_dom+= '</select>';
			$('#choose_cityarea').prepend(area_dom);
			show_city($('#choose_province').find('option:selected').attr('value'),$('#choose_province').find('option:selected').html(),1);
			$('#choose_province').change(function(){
				show_city($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
			});
		}else if(result.error == 2){
			var area_dom = '<select class="col-sm-2" id="choose_province_hide" name="unit_id" style="display:none;">';
			area_dom += '<option value="'+result.floor_id+'">'+result.floor_name+'</option>';
			area_dom += '</select>';
			$('#choose_cityarea').prepend(area_dom);
			show_city(result.id,result.name,0);
		}else{
			$('input[name="usernum"]').val('');
			//alert(result.info);
			location.href="{pigcms{:U('owner_arrival')}";
		}
	});
}

function show_city(id,name,type){
	$.post(choose_floor,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select class="col-sm-2" id="choose_city" name="floor_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.floor_id+'" '+(item.id==$('#choose_cityarea').attr('city_id') ? 'selected="selected"' : '')+'>'+item.floor_layer+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_city')){
				$('#choose_city').replaceWith(area_dom);
			}else if(document.getElementById('choose_province')){
				$('#choose_province').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
			if($('#choose_cityarea').attr('area_id') != '-1'){
				show_area($('#choose_city').find('option:selected').attr('value'),$('#choose_city').find('option:selected').html(),1);
			
				$('#choose_city').change(function(){
					show_area($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
				});
			}
		}else if(result.error == 2){
			var area_dom = '<select class="col-sm-2" id="choose_city_hide" name="floor_id" style="display:none;">';
			area_dom += '<option value="'+result.floor_id+'">'+result.floor_name+'</option>';
			area_dom += '</select>';
			$('#choose_cityarea').prepend(area_dom);
			if($('#choose_cityarea').attr('area_id') != '-1'){
				show_area(result.id,result.name,0);
			}
		}else{
			$('input[name="usernum"]').val('');
			//alert(result.info);
			location.href="{pigcms{:U('owner_arrival')}";
		}
	});
}

function show_area(id,name,type){
	$.post(choose_layer,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select class="col-sm-3" id="choose_area" name="pigcms_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.pigcms_id+'" '+(item.id==$('#choose_cityarea').attr('area_id') ? 'selected="selected"' : '')+'>'+item.address+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_area')){
				$('#choose_area').replaceWith(area_dom);
			}else if(document.getElementById('choose_city')){
				$('#choose_city').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
			if($('#choose_cityarea').attr('circle_id') != '-1'){
				show_circle($('#choose_area').find('option:selected').attr('value'),$('#choose_area').find('option:selected').html(),1);
				$('#choose_area').change(function(){
					show_circle($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
				});
			}
		}else{
			$('input[name="usernum"]').val('');
			//alert(result.info);
		}
	});
}

function show_circle(id,name,type){
	$.post(choose_owner,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_circle" name="owner_id" class="col-sm-2" style="margin-right:10px;">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.pigcms_id+'" '+(item.id==$('#choose_cityarea').attr('circle_id') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
				$('input[name="usernum"]').val(item['usernum']);
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_circle')){
				$('#choose_circle').replaceWith(area_dom);
			}else if(document.getElementById('choose_area')){
				$('#choose_area').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
			
			
			//show_market($('#choose_circle').find('option:selected').attr('value'),$('#choose_circle').find('option:selected').html(),1);
			$('#choose_circle').change(function(){
				//show_market($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
			});
		}else{
			$('input[name="usernum"]').val('');
			alert(result.info);
		}
	});
}

var ajax_property_info_url = "{pigcms{:U('ajax_property_info')}"
$('select[name="property_id"]').change(function(){
	var property_id = $(this).val();
	$.post(ajax_property_info_url,{'property_id':property_id},function(data){
		if(data.status){
			if(data['detail']['diy_type'] > 0){
				$('.property_desc').html(data['detail']['diy_content']);
			}else{
				if(data['detail']['presented_property_month_num'] > 0){
					$('.property_desc').html('赠送'+data['detail']['presented_property_month_num']+'个月');
				}else{
					$('.property_desc').html('');
				}
			}
		}else{
			$('.property_desc').html('');
		}
	},'json')
	
});
</script>

<include file="Public:footer"/>