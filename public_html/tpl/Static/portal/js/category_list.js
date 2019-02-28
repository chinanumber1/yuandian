$(function(){
	
	if($('.you').size() > 0){
		$(".you").pin({minWidth:1e3,containerSelector:".container"});
	}
	
	$(".elevator").pin({padding:{top:0,bottom:0},containerSelector:".container",minWidth:1e3,activeClass:"hover"});
	var t={};
	$(".elevator .menuItem").find("a").each(function(e,o){t[e]=$(o).attr("href").slice(1)});
	$(".elevator").stickUp({parts:t,itemClass:"menuItem",itemHover:"active"});
	$("#upward").click(function(){$("html,body").animate({scrollTop:0},300)});
	var e=window.location.hash;
	$.scrollTo(e,500,{});
	if(e == ''){
		$('#elevator .menuItem:first').addClass('active');
	}
	
	
	
	
$(function(){
	$('.service-type-select').click(function(){
		art.dialog({
			id: 'service-handle',
			title:'选择服务',
			content:document.getElementById('service-type-box'),
			padding: '30px',
			width: 438,
			padding:0,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			opacity:'0.4'
		});
		return false;
	});
	$('.service-list li').click(function(){
		$(this).addClass('active').siblings('li').removeClass('active');
		$('.con-service-inner').html('<h3>'+$(this).find('h3[data-role="title"]').html()+'</h3><span>'+$(this).find('span[data-role="content"]').html()+'</span>');
		$('.comm-service span span').html($(this).find('span[data-role="payAmount"]').html());
		$('#service_type').val($(this).data('id'));
		art.dialog({id: 'service-handle'}).close();
	});
	$('#serviceJobTime').click(function(){
		art.dialog({
			id: 'service-time-handle',
			title:'选择预约时间',
			content:document.getElementById('service-date'),
			padding: '30px',
			width: 538,
			padding:0,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			opacity:'0.4'
		});
		return false;
	});
	
	$('input[data-role="position"]').click(function(){
		var randNum = getRandNumber();
		setPointDom[randNum] = $(this);
		map_url += '&randNum='+randNum;
		if($(this).data('long')){
			map_url += '&long_lat='+$(this).data('long')+','+$(this).data('lat');
		}

		art.dialog.open(map_url,{
			id: 'service-position-handle',
			title:'标注地理位置 (拖动红色图标至您的坐标)',
			padding: '30px',
			width: 800,
			height: 559,
			padding:0,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			opacity:'0.4'
		});
		return false;
	});
	$('input[data-role="position-desc"]').focus(function(){
		if($(this).val() == '请标注地图后填写详细地址'){
			$(this).val('').css('color','black');
		}
	}).blur(function(){
		if($(this).val() == ''){
			$(this).val('请标注地图后填写详细地址').css('color','#999');
		}
	}).keyup(function(){
		$(this).closest('div').find('input[data-type="address"]').val($(this).closest('div').find('input[data-role="position"]').val()+$(this).val());
	});
	
	$('.yxc-time-con dt[data-role="date"]').click(function(){
		$('.yxc-time-con dt[data-role="date"]').removeClass('active');
		$(this).addClass('active');
		$('.date-'+$(this).data('date')).show().siblings('div').hide();
	});
	$('.yxc-time-con dd[data-role="item"]').click(function(){
		if(!$(this).hasClass('disable')){
			$('.yxc-time-con dd[data-role="item"]').removeClass('active');
			$(this).addClass('active');
			$('#serviceJobTime').val($('.yxc-time-con dt[data-role="date"].active').data('text') + ' ' +$(this).data('peroid')).css({'color':'black','font-size':'14px'});
			$('#service_date').val($('.yxc-time-con dt[data-role="date"].active').data('date'));
			$('#service_time').val($(this).data('peroid'));
			art.dialog({id: 'service-time-handle'}).close();
		}
	});
	
	// $('textarea.ipt-attr').focus(function(){
		// $(this).css('height','60px');
	// }).blur(function(){
		// if($(this).val() == ''){
			// $(this).css('height','24px');
		// }
	// });
	
	$('#deal-buy-form').submit(function(){
		var slA = $('#deal-buy-form').serializeArray();
		for(var i in slA){
			var tmpDom = $("[name='"+slA[i].name+"']");
			if(tmpDom.data('role')){
				if(tmpDom.data('required')){
					if(tmpDom.data('role') == 'phone' && !/^0?1[3|4|5|7|8][0-9]\d{8}$/.test(slA[i].value)){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'text' && slA[i].value == ''){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'position' && !tmpDom.data('long')){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'textarea' && slA[i].value == ''){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'number' && !/^[0-9]*$/.test(slA[i].value)){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'date' && (slA[i].value == '' || slA[i].value == '请点击选择日期')){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'time' && (slA[i].value == '' || slA[i].value == '请点击选择时间')){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'select' && slA[i].value == ''){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'datetime' && (slA[i].value == '' || slA[i].value == '请点击选择时间')){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'email'  && !/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(slA[i].value)){
						formError(tmpDom);
						return false;
					}
				}
			}
		}
		
		$('#confirmOrder').attr('disabled',true).css('background','gray');
		$.post($('#deal-buy-form').attr('action'),$('#deal-buy-form').serialize(),function(result){
			motify.log(result.msg);
			if(result.status == 0){
				location.href = result.url;
			}else{
				location.reload();
			}
		},'json');
		return false;
	});
});

function formError(tmpDom){
	$('.form_error').removeClass('form_error');
	motify.log('请正确填写该项：'+tmpDom.closest('.form-field').data('name'));
	$(window).scrollTop(tmpDom.offset().top-20);
	tmpDom.addClass('form_error');
}

var motify = {
	timer:null,
	log:function(msg){
		alert(msg);
	/* 	$('.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		motify.timer = setTimeout(function(){
			$('.motify').hide();
		},3000); */
	}
};



/**
 * 生成一个随机数
 */
function getRandNumber(){
	var myDate=new Date();
	return myDate.getTime() + '' + Math.floor(Math.random()*10000);
}
});