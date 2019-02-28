$(function(){
	
	$(".panel").pin({minWidth:1e3,containerSelector:".container"});
	var t={};
	$(".elevator .menuItem").find("a").each(function(e,o){t[e]=$(o).attr("href").slice(1)});
	$(".elevator").pin({containerSelector:".container",minWidth:1000,activeClass:"hover"});
	$(".elevator").stickUp({parts:t,itemClass:"menuItem",itemHover:"active"});
	$("#upward").click(function(){$("html,body").animate({scrollTop:0},300)});
	
	var map = null;
	var oPoint = new BMap.Point(store_long,store_lat);
	var marker = new BMap.Marker(oPoint);
	
	map = new BMap.Map("map-canvas",{"enableMapClick":false});
	map.enableScrollWheelZoom();
	map.enableKeyboard();
	marker.enableDragging();
	
	map.centerAndZoom(oPoint, 17);

	map.addControl(new BMap.NavigationControl());
	map.enableScrollWheelZoom();

	map.addOverlay(marker);
	
	$('#map-canvas').on('mousewheel',function(){
        return false;
	});
	
	$('.J-view-full').click(function(){
		var map_dom = $('#map-canvas');
		var map_url = map_dom.attr('frame_url')+'&map_point='+map_dom.attr('map_point')+'&store_name='+encodeURIComponent(map_dom.attr('store_name'))+'&store_adress='+encodeURIComponent(map_dom.attr('store_adress'))+'&store_phone='+map_dom.attr('store_phone');
		art.dialog.open(map_url, {
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_map', iframe);
			},
			id: 'iframe_map',
			title: '查看地图',
			padding: "20",
			width: "800px",
			height: "559px",
			background:'white',
			lock: true,
			button: null,
			opacity:'0.4'
		});
	});
	
	$('.map_txt .biz-info').mouseover(function(){
		if(!$(this).hasClass('biz-info--open')){
			$(this).addClass('biz-info--open').siblings('.biz-info').removeClass('biz-info--open');
		}
	});
	
	
	$('.view-map').click(function(){
		var map_dom = $(this);
		var map_url = map_dom.attr('frame_url')+'&map_point='+map_dom.attr('map_point')+'&store_name='+encodeURIComponent(map_dom.attr('store_name'))+'&store_adress='+encodeURIComponent(map_dom.attr('store_adress'))+'&store_phone='+map_dom.attr('store_phone');
		art.dialog.open(map_url, {
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_map', iframe);
			},
			id: 'iframe_map',
			title: '查看地图',
			padding: "20",
			width: "800px",
			height: "559px",
			background:'white',
			lock: true,
			button: null,
			opacity:'0.4'
		});
	});
	
	//商家位置
	$.each($('.search-path'),function(i,item){
		$(item).attr({'href':'http://map.baidu.com/m?word='+encodeURIComponent($(item).attr('shop_name')),'target':'_blank'});
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
	
	
	$('.yxc-time-con dt[data-role="date"]').click(function(){
			$('.yxc-time-con dt[data-role="date"]').removeClass('active');
			$(this).addClass('active');
			$('.date-'+$(this).data('date')).show().siblings('div').hide();
		});
		$('.yxc-time-con dd[data-role="item"]').click(function(){
			if(!$(this).hasClass('disable')){
				var sDate = $('.yxc-time-con dt[data-role="date"].active').data('date');
				var sDay = $(this).data('peroid');
				$('.yxc-time-con dd[data-role="item"]').removeClass('active');
				$(this).addClass('active');
				$('#serviceJobTime').val($('.yxc-time-con dt[data-role="date"].active').data('text') + ' ' +sDay).css({'color':'black','font-size':'14px'});
				$('#service_date').val(sDate);
				//$('#service_time').val($(this).data('peroid'));
				$('#serviceJobTime').find('span').html(sDate + ' ' +sDay);
				art.dialog({id: 'service-time-handle'}).close();
			}
		});
	
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
		
		var sTitle = $(this).find('h3[data-role="title"]').html();
		var sPrice = $(this).find('span[data-role="payAmount"]').html();
		var product_id = $(this).data('id');
		$('.service-type-select').find('span').html(sTitle+' '+sPrice);
		$('span[name="product_id"]').data('product_id',product_id);
		
		art.dialog({id: 'service-handle'}).close();
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
	
/**
 * 生成一个随机数
 */
function getRandNumber(){
	var myDate=new Date();
	return myDate.getTime() + '' + Math.floor(Math.random()*10000);
}


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
	

//评论事件
get_reply_list(1);
$('.rate-filter__item a').click(function(){
	$(this).addClass('on').siblings().removeClass('on');
	get_reply_list(1);
	return false;
});
$('.J-filter-ordertype').change(function(){
	get_reply_list(1);
});
$('.J-rate-paginator a').live('click',function(){
	get_reply_list($(this).attr('data-index'));
});
$('.J-piclist-wrapper li a').live('click',function(){
	var m_src = $(this).closest('li').attr('m-src');
	var big_src = $(this).closest('li').attr('big-src');
	window.art.dialog({
		title: '查看图片',
		lock: true,
		fixed: true,
		opacity: '0.4',
		resize: false,
		left: '50%',
		top: '38.2%',
		content:'<a href="'+big_src+'" target="_blank" title="新窗口打开查看原图"><img src="'+m_src+'" alt="大图"/></a>',
		close: null
	});
	return false;
});

function get_reply_list(page){
	$('.ratelist-content').prepend('<div class="loading-surround--large ratelist-content__loading J-list-loading"></div>');
	$('.J-rate-list').empty();
	$('.J-rate-paginator').empty();
	
	$.post(get_reply_url,{tab:$('.rate-filter__item .on').attr('data-tab'),order:$('.J-filter-ordertype').val(),page:page},function(result){
		$('.J-list-loading').remove();
		if(result == '0'){
			$('.J-rate-list').html('<li class="norate-tip">暂无该类型评价</li>');
		}else{
			result = $.parseJSON(result);
			$('.J-rate-paginator').html(result.page);
			$.each(result.list,function(i,item){
				var item_html = '<dd class="clearfix"><div class="appraise_li-list_img"><div class="appraise_li-list_icon"><img src="'+(item.avatar ? item.avatar : site_url+'/static/images/portrait.jpg')+'" /></div></div><div class="appraise_li-list_right clearfix"><p>'+item.nickname+'</p><div class="appraise_li-list_top clearfix"><div class="appraise_li-list_top_icon"><div><span style="width:'+(parseInt(item.score)/5*100)+'%"></span></div></div><div class="appraise_li-list_data">'+item.add_time+'</div></div><div class="appraise_li-list_txt">'+item.comment+'</div>';
				if(item.pics){
					item_html+= '<div class="pic-list J-piclist-wrapper"><div class="J-pic-thumbnails pic-thumbnails"><ul class="pic-thumbnail-list widget-carousel-indicator-list">';
					$.each(item.pics,function(j,jtem){
						item_html+= '<li m-src="'+jtem.m_image+'" big-src="'+jtem.image+'"><a class="pic-thumbnail" href="#" hidefocus="true"><img src="'+jtem.s_image+'"></a></li>';
					});
					item_html+= '</ul></div></div>';
				}
				if(item.merchant_reply_content != ''){
					item_html+= '<p class="biz-reply">商家回复：'+item.merchant_reply_content+'</p>';
				}
				item_html+= ''+(item.store_name ? '<p class="shopname">'+item.store_name+'</p>' : '')+'</dd>';
				$('.J-rate-list').append(item_html);
			});
		}
		if(page>1){$(window).scrollTop($('.J-rate-filter').offset().top+50);}
	});
}
});

