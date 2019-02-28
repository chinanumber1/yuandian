<include file="Public:top"/>
<body>
	<link rel="stylesheet" href="{pigcms{$static_path}css/shop.css">
	<link rel="stylesheet" href="{pigcms{$static_path}css/shop_info.css">
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/datePicker.css">
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll_min.css" media="all">
	<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll_min.js"></script>
	<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&key=Y7IBZ-W6WWJ-PP6FF-FPFGD-ES3JF-YNFPN"></script>
	<style>
		.top-img-container{
			padding-bottom: 0;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}
		.top-img-container .pigcms-form-title{
			float: left;
			width: auto;
			line-height: 60px;
		}
		.up-load-img{
			float:right;
			width: 60px;
			height: 60px;
			margin:0 4% 0 0;
			text-align: center;
		}
		.up-load-img img{
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		#big-pic{
			width: 110px;
			height: 50px;
			border-radius: 0!important;
		}
		#big-pic img{border-radius: 0!important;}
		.form_tips{color:red;}
		.radio{margin: 0 3%;padding: 5px 0; width: 92%;line-height: 20px!important;background-color: #FFF;}
		#choose_area{margin-right: 30px;width: 35%;padding-left: 20px;}
		#choose_circle{width: 45%;padding-left: 20px;}
		#perioddeliveryfeebox{margin:10px;height:auto;}
		.perioddeliveryfeeitem{margin:10px 0px;}
		.pigcms-input-block{display:inline-block;width: 75%;}
		.pigcms-container{margin-bottom: 10px;}
		.pigcms-textarea{width: 80%;margin-bottom: 20px;}
	</style>
	<header class="pigcms-header mm-slideout">
	   <a  href="{pigcms{:U('Index/store_list')}" id="pigcms-header-left">返 回</a>
	   <p id="pigcms-header-title">{pigcms{$config.meal_alias_name}信息管理</p>
	</header>
	<div class="container container-fill" >
			<!--左侧菜单-->
			<include file="Public:leftMenu"/>
			<!--左侧菜单结束-->
	<form class="pigcms-form" method="post" action="{pigcms{:U('Index/storemeal',array('store_id'=>$now_store['store_id']))}">
		<div class="pigcms-container">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>店铺公告</p>
			<textarea class="pigcms-textarea" rows="4" name="store_notice" id="Config_notice">{pigcms{$store_shop.store_notice}</textarea>
		</div>
								
		<div class="pigcms-container">
			<p class='pigcms-form-title'>开发票</p>
			<div class="radio">
			<label><input name="is_invoice" <if condition="$store_shop['is_invoice'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
			<label><input name="is_invoice" <if condition="$store_shop['is_invoice'] eq 1 ">checked="checked"</if> value="1" type="radio">&nbsp;&nbsp;支持</label>&nbsp;&nbsp;&nbsp;
			</div>
		</div>
		
		<div class="pigcms-container invoice" <if condition="$store_shop['is_invoice'] eq 0 ">style="display:none"</if>>
			<p class='pigcms-form-title'>满足</p>
			<input class="pigcms-input-block" style="width:50px" name="invoice_price" id="Config_invoice_price" type="text" value="{pigcms{$store_shop.invoice_price|floatval}" />
			<label class="form_tips">元，可开发票</label>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>预订</p>
			<div class="radio">
			<label><input name="is_book" <if condition="$store_shop['is_book'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
			<label><input name="is_book" <if condition="$store_shop['is_book'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
			</div>
		</div>
		<div class="pigcms-container book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
			<p class='pigcms-form-title'>预订时长</p>
			<div>
				<input id="book_day" class="pigcms-input-block" style="width:50px" type="text" value="{pigcms{$store_shop.book_day|default=1}" name="book_day"/>
				<span class="form_tips red">可提前预订多少天后的桌台</span>
			</div>
		</div>
		<div class="pigcms-container book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
			<p class='pigcms-form-title'>预订时间</p>
			<div>
				<input id="book_start" class="pigcms-input-block" type="text" value="{pigcms{$store_shop.book_start|default='00:00'}" name="book_start" style="width:90px" readonly/>	至
				<input id="book_stop" class="pigcms-input-block" type="text" value="{pigcms{$store_shop.book_stop|default='23:59'}" name="book_stop" style="width:90px" readonly/>
				<p class="pigcms-form-title form_tips red">如果两个都不填写的话，表示从零点开始，按预订间隔时长进行全天预订</p>
			</div>
		</div>
		<div class="pigcms-container book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
			<p class='pigcms-form-title'>预订间隔时长</p>
			<input class="pigcms-input-block" style="width:50px" name="book_time" type="text" value="{pigcms{$store_shop.book_time|default=60}" />
			<p class="pigcms-form-title form_tips">两个可预订时间之间相隔的时长，单位（分钟）</p>
		</div>
		
		<div class="pigcms-container">
			<p class='pigcms-form-title'>排号</p>
			<div class="radio">
			<label><input name="is_queue" <if condition="$store_shop['is_queue'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
			<label><input name="is_queue" <if condition="$store_shop['is_queue'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
			</div>
		</div>
		
		<div class="pigcms-container">
			<p class='pigcms-form-title'>外送</p>
			<div class="radio">
			<label><input name="is_takeout" <if condition="$store_shop['is_takeout'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
			<label><input name="is_takeout" <if condition="$store_shop['is_takeout'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
			</div>
		</div>
		
		<div class="pigcms-container">
			<p class='pigcms-form-title'>停车位</p>
			<div class="radio">
			<label><input name="is_park" <if condition="$store_shop['is_park'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;没有</label>&nbsp;&nbsp;&nbsp;
			<label><input name="is_park" <if condition="$store_shop['is_park'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;有</label>
			</div>
		</div>

		<p class='pigcms-form-title'>选择分类</p>
		<volist name="category_list" id="vo">
		<div class="pigcms-container">
			<div class="radio">
				<label>
					<span class="lbl" style="color: red">{pigcms{$vo.cat_name}：</span>
				</label>
				<volist name="vo['son_list']" id="child">
					<label>
						<input  type="checkbox" name="store_category[]" value="{pigcms{$vo.cat_id}-{pigcms{$child.cat_id}" id="Config_store_category_{pigcms{$child.cat_id}" <if condition="in_array($child['cat_id'],$relation_array)">checked="checked"</if>/>
						<span class="lbl"><label for="Config_store_category_{pigcms{$child.cat_id}">{pigcms{$child.cat_name}</label></span>
					</label>
				</volist>
			</div>
		</div>
	</volist>
	<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit">保存</button>

	</form>
 </div>
	
</body>
<script>
$(function($){
	$('input[name=is_book]').click(function(){
		if ($(this).val() == 1) {
			$('.book').css('display', 'block');
		} else {
			$('.book').css('display', 'none');
		}
	});
	$('input[name=is_invoice]').click(function(){
		if ($(this).val() == 1) {
			$('.invoice').css('display', 'block');
		} else {
			$('.invoice').css('display', 'none');
		}
	});
	
// 	$('#book_start').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'00','minute':'00'}));
// 	$('#book_stop').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'23','minute':'59'}));

	var is_submit = false;
	$('#edit_form').submit(function(){
		if (is_submit) return false;
		is_submit = true;
		$.post("{pigcms{:U('Foodshop/shop_edit',array('store_id'=>$_GET['store_id']))}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Foodshop/index')}";
			}else{
				is_submit = false;
				alert(result.info);
			}
		})
		return false;
	});

	var opt = {};
    opt.time = {preset:'time'};
	opt.time_default = {
	        theme: 'android-ics light', //皮肤样式
	        display: 'bottom', //显示方式
	        mode: 'scroller', //日期选择模式
	        lang:'zh',
	        minWidth: 64,
	        setText: '确定', //确认按钮名称
	        cancelText: '取消',//取消按钮
	        dateFormat: 'yy-mm-dd'
	};

$("#book_start").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
$("#book_stop").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));


// 	$("#book_start").scroller('destroy').scroller();
	
});
</script>
	<include file="Public:footer"/>
</html>
