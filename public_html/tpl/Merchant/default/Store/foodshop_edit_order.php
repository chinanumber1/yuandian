<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<script type="text/javascript" src=".{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="mainBox">
			<div class="rightMain">
				<div class="grid-view">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="form_add" autocomplete="off">
						<input type="hidden" name="order_id" value="{pigcms{$now_order.order_id}"/>
						<div class="form-group">
							<label class="col-sm-2"><label for="table_type">桌台分类</label></label>
							<select id="table_type" name="table_type">
								<volist name="table_type" id="vo">
									<option value="{pigcms{$vo.id}" <if condition="$vo['id'] eq $now_order['table_type']">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
							</select>
						</div>
						<div class="form-group">
							<label class="col-sm-2"><label for="table_id">桌台编号</label></label>
							<select id="table_id" name="table_id">
								<volist name="table_list" id="vo">
									<option value="{pigcms{$vo.id}" <if condition="$vo['id'] eq $now_order['table_id']">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
							</select>
						</div>
						<div class="form-group">
							<label class="col-sm-2"><label for="book_num">用餐人数</label></label>
							<input class="col-sm-2" size="10" name="book_num" id="book_num" type="text" value="{pigcms{$now_order.book_num}"/>
							<span class="form_tips">不收取客户必点菜费用，可设置为 0</span>
						</div>
						<div class="clearfix form-actions">
							<div class="col-md-offset-3 col-md-9">
								<button class="btn btn-info" type="submit" id="submit_btn">
									保存订单
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
<script>
$(function(){
	$('#total_price').focus();
	$("#table_type").change(function(){
		var loadTip = layer.load(0, {shade: [0.6,'#fff']});
		$.getJSON("{pigcms{:U('get_table_list')}",{table_type:$(this).val()},function(result){
			layer.close(loadTip);
			var tableHtml = '';
			if(result){
				for(var i in result){			
					tableHtml+= '<option value="'+result[i].id+'">'+result[i].name+'</option>';
				}
			}else{
				tableHtml = '<option value="0">该类型无空闲桌子</option>';
			}
			$('#table_id').html(tableHtml);
		});
	});
	$('#book_num').blur(function(){
		var book_num = parseInt($('#book_num').val());
		if(isNaN(book_num)){
			$('#book_num').val('');
		}else{
			$('#book_num').val(book_num);
		}
	});
	
	$('#form_add').submit(function(){
		// if(isNaN(parseInt($('#book_num').val())) || parseInt($('#book_num').val()) <= 0){
		if(isNaN(parseInt($('#book_num').val()))){
			alert('请输入正确的就餐人数！');
			$('#book_num').focus();
			return false;
		}
		if($('#table_id').val() == '0'){
			alert('请选择就餐桌台');
			return false;
		}
		$('#submit_btn').html('保存中...').prop('disabled',true);
		$.post("{pigcms{:U('foodshop_edit_order_save')}",$('#form_add').serialize(),function(result){
			alert(result.info);
			$('#submit_btn').html('保存订单').prop('disabled',false);
		});
		return false;
	});
});
</script>
</html>