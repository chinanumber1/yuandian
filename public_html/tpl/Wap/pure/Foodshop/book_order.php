<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>
	<section class="Reservation">
		<ul class="ReserDate">
			<li id="Choice">
				<span class="fl">日期</span>
				<span class="Reser_right">
					<i class="m">{pigcms{$m}</i>月<i class="d">{pigcms{$d}</i>日 <i class="e">{pigcms{$w}</i> <i class="o">{pigcms{$o}</i>
				</span>
				<input type="hidden" name="book_time" id="book_time" value="{pigcms{$book_time}"/>
				<input type="hidden" name="store_id" id="store_id" value="{pigcms{$store['store_id']}"/>
			</li>
			<li>
				<span class="fl">人数</span>
				<input type="text" placeholder="请输入就餐人数" class="input_right" id="book_num" name="book_num" value="{pigcms{$book_num}">
			</li>
		</ul>
		<ul class="Resertable">
			<li>
				<span class="fl">选择餐桌</span>
				<input type="text" id="city_dummy" class="Reser_right" >
				<input type="hidden" name="table_type" id="table_type" value="{pigcms{$table_type}"/>
				<select id="city" class="demo-test-select dw-hsel" data-role="none" tabindex="-1">
				<volist name="table_list" id="table">
				<option value="{pigcms{$table['id']}" data-price="{pigcms{$table['deposit']|floatval}" <if condition="$table_type eq $table['id']">selected</if>>{pigcms{$table['name']}({pigcms{$table['min_people']}-{pigcms{$table['max_people']}人)</option>
				</volist>
				</select>
			</li>
			<li>
				<span class="fl">预交定金</span>
				<em><i id="show_book_price">{pigcms{$book_price}</i>元</em>
				<input type="hidden" name="book_price" id="book_price" value="{pigcms{$book_price}"/>
			</li>
		</ul>
		<ul class="Reserfillin">
			<li>
				<span class="fl">姓名</span>
				<input type="text" placeholder="请输入您的姓名" class="input_right" id="name" name="name" value="{pigcms{$name}">
			</li>
			<li>
				<span class="fl">性别</span>
				<div class="Gender">
					<span <if condition="$sex eq 0">class="on"</if> data-sex="0">女士</span>
					<span <if condition="$sex eq 1">class="on"</if> data-sex="1">先生</span>
				</div> 
				<input type="hidden" name="sex" value="{pigcms{$sex}" id="sex"/>
			</li>
			<li>
				<if condition="!$config['international_phone']">
					<span class="fl pho">+86</span>
				<else/>
					<span class="fl pho">联系</span>
				</if>
				<input type="text" placeholder="请输入您的手机号码" class="input_right" name="phone" value="{pigcms{$phone}">
			</li>
		</ul>
		<div class="textarea">
			<textarea placeholder="如有附加要求，可填写，我们会尽量安排" name="note" id="note"></textarea>
			<div class="SuOrde_top">如您不能准时到达餐厅，请在您预约时间的<b>{pigcms{$store['cancel_time']}</b>分钟前取消并可退定金，否则不得取消。</div>
		</div>
		<div class="Resersub"><input type="button" value="立即订座" id="button_save"></div>
	</section>
	<section class="elastic"  style="height: 0px; overflow: hidden;">
		<div class="swiper-container swiper-container2">
			<div class="swiper-wrapper">
				<volist name="day_list" id="day" key="di">
					<div class="swiper-slide <if condition="$day['date'] == $selectdate">on</if>" >
						<em>{pigcms{$day['title']}</em> 
						<span>{pigcms{$day['day']}</span>
						<input type="hidden" name="date" value="{pigcms{$day['date']}"/>
					</div>
				</volist>
			</div> 
			<div class="swiper-button-prev"></div> 
			<div class="swiper-button-next"></div> 
		</div>
		<div class="Switch">
			<volist name="time_list" id="times" key="ti">
				<ul class="clr" <if condition="$times['date'] != $selectdate">style="display:none"</if>>
					<volist name="times['time_list']" id="time">
					<li class="{pigcms{$time['class']}" style="cursor: pointer;">{pigcms{$time['time']}</li>
					</volist>
				</ul>
			</volist>
		</div>
		<div class="Resersub" style="display: none;"><input type="submit" value="确认选择"></div>
	</section>
</body>
</html>

<script type="text/javascript">
$(function () {
	$(".elastic .Switch").each(function(){
		$(this).height($(window).height()-110)
	})

	// 清除边框 
	$(".Reservation ul").each(function(){
		$(this).find("li").first().css("border-top", "none");
	});
	
	//性别
	$(".Gender span").click(function(){
		$(this).addClass("on").siblings().removeClass("on");
		$('#sex').val($(this).data('sex'));
	});
  
	//时间切换
	$(document).on('click', ".swiper-wrapper .swiper-slide", function(){
		$(this).addClass("on").siblings().removeClass("on")
		var index = $(this).index();
		$(".Switch ul").eq(index).show(200).siblings().hide(200);
	});  
 
	//选择时间点
	$(document).on('click', ".Switch ul li", function(){
		var End = $(this).hasClass("End")
		if (!End) {
			$(this).addClass("on").siblings().removeClass("on").parents("ul").siblings().find("li").removeClass("on");
		}
	});

	 //选择提交
	$("#Choice").click(function(){
		var height = $(document).height(); 
		$(".elastic").animate({"height":height}, 500);
		$(".elastic .Resersub").show(); 
	});
	
	$(".elastic .Resersub").click(function(){
		$(this).hide();
		var m = $(".swiper-wrapper").find(".on .m").text();
		var d = $(".swiper-wrapper").find(".on .d").text();
		var e = $(".swiper-wrapper").find(".on em").text(), _date = $(".swiper-wrapper").find(".on input").val();
		var t = $(".Switch").find(".on").text();
		$(".ReserDate .Reser_right").find(".m").text(m);
		$(".ReserDate .Reser_right").find(".d").text(d);
		$(".ReserDate .Reser_right").find(".e").text(e);
		$(".ReserDate .Reser_right").find(".o").text(t);
		$('#book_time').val(_date + ' ' + t);
		$(".elastic").animate({"height":0}, 500); 
		format_data();
	});
	//滑动天数
	var swiper = new Swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',
		slidesPerView: 'auto'
	});

	var opt = {'select':{preset:'select'}}
    opt.default = {
	        theme: 'android-ics light', //皮肤样式
	        mode: 'scroller', //日期选择模式
			display: 'bottom', //显示方式
			onSelect: function (valueText, inst) {
				$('#table_type').val(inst.getValue());
				$('#show_book_price').html(parseFloat($("#city option[value='" + inst.getValue() + "']").attr('data-price')));
				$('#book_price').val(parseFloat($("#city option[value='" + inst.getValue() + "']").attr('data-price')));
				format_data();
	        }
	};
	$('.demo-test-select').scroller($.extend(opt['select'], opt['default']));
	var tempTime = null;
	$('#book_num').keyup(function(){
	    clearTimeout(tempTime);
	    tempTime = setTimeout(format_data, 800);
	});
	var is_save = false;
	$('#button_save').click(function(){
		if (is_save) return false;
		is_save = true;
		var data = {
			'store_id':$("input[name=store_id]").val(),
			'name':$("input[name=name]").val(),
			'phone':$("input[name=phone]").val(),
			'sex':$("input[name=sex]").val(),
			'book_time':$("input[name=book_time]").val(),
			'book_num':$("input[name=book_num]").val(),
			'table_type':$("#city").val(),
			'note':$("#note").val()
		};
		if (data.phone.name < 0) {
			is_save = false;
			alert('姓名不能为空');
			return false;
		}
		if (data.phone.length < 0) {
			is_save = false;
			alert('电话不能为空');
			return false;
		}
		if (data.book_num < 1) {
			is_save = false;
			alert('请填写预订人数！');
			return false;
		}
		if (data.table_type < 1) {
			is_save = false;
			alert('请选择桌台！');
			return false;
		}
		
		$.post('{pigcms{:U("Foodshop/book_save")}', data, function(response){
			is_save = false;
			if (response.err_code) {
				alert(response.msg);
				return false;
			} else {
				window.location.href = response.url;
			}
		}, 'json');
	});
});
var flag = false;
function format_data()
{
	if (flag) return false;
	flag = true;	
	var data = {store_id:$('#store_id').val(), book_num:$('#book_num').val(), table_type:$('#city').val(), book_time:$('#book_time').val()};
	$.post('{pigcms{:U("Foodshop/get_data")}', data, function(response){
		if (response.err_code) {
			alert(response.msg);
		} else {
			var table_list_html = '', city_dummy = '';
			$.each(response.table_list, function(i, table) {
				if (response.table_type == table.id) {
					city_dummy = table.name + '(' + table.min_people + '-' + table.max_people + '人)';
					table_list_html += '<option value="' + table.id + '" data-price="' + table.deposit + '" selected>' + table.name + '(' + table.min_people + '-' + table.max_people + '人)</option>';
				} else {
					table_list_html += '<option value="' + table.id + '" data-price="' + table.deposit + '">' + table.name + '(' + table.min_people + '-' + table.max_people + '人)</option>';
				}
			});
			$('#city_dummy').val(city_dummy);
			$('#city').html(table_list_html);

			var day_list_html = '';
			$.each(response.day_list, function(i, day){
				if (response.selectdate == day.date) {
					day_list_html += '<div class="swiper-slide on" >';
				} else {
					day_list_html += '<div class="swiper-slide" >';
				}
				day_list_html += '<em>' + day.title + '</em>';
				day_list_html += '<span>' + day.day + '</span>';
				day_list_html += '<input type="hidden" name="date" value="' + day.date + '"/>';
				day_list_html += '</div>';
			});
			$('.swiper-wrapper').html(day_list_html);

			var time_list_html = '';
			$.each(response.time_list, function(i, times){
				if (response.selectdate != times.date) {
					time_list_html += '<ul class="clr" style="display:none">';
				} else {
					time_list_html += '<ul class="clr">';
				}
				$.each(times.time_list, function(ii, time){
					time_list_html += '<li class="' + time['class'] + '" style="cursor: pointer;">' + time['time'] + '</li>';
				});
				time_list_html += '</ul>';
			});
			$('.Switch').html(time_list_html);
			$('#show_book_price').html(response.book_price);
			$('#book_price').val(response.book_price);
			$('#book_time').val(response.book_time);
			$('.Reser_right .m').html(response.m);
			$('.Reser_right .d').html(response.d);
			$('.Reser_right .e').html(response.w);
			$('.Reser_right .o').html(response.o);
		}
		flag = false;
	}, 'json');
}
</script>