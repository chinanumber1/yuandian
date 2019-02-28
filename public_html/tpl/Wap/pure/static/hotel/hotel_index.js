var oCal;
var week = ["日","一","二","三","四","五","六"];
(function (doc, win) {
  var docEl = doc.documentElement,
    resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
    recalc = function () {
      var clientWidth = docEl.clientWidth;
      if (!clientWidth) return;
      docEl.style.fontSize = 20 * (clientWidth / 320) + 'px';
    };
	var banner_height	=	$(window).width()/320;
	banner_height	=	 Math.ceil(banner_height*119);
	
	$("#banner_hei").css('height',banner_height);
	
	if($('.swiper-container1').size() > 0){
		var mySwiper = $('.swiper-container1').swiper({
			pagination:'.swiper-pagination1',
			loop:true,
			grabCursor: true,
			paginationClickable: true,
			autoplay:3000,
			autoplayDisableOnInteraction:false,
			simulateTouch:false
		});
	}
	
	if (!doc.addEventListener) return;
	win.addEventListener(resizeEvt, recalc, false);
	doc.addEventListener('DOMContentLoaded', recalc, false);
	
  
	$('.now_city').click(function(){
		window.location.href='./wap.php?g=Wap&c=Changecity&a=index&hotel=1';
	});
	
	// $('.search').click(function(){
		// window.location.href='./wap.php?g=Wap&c=Hotel&a=hotel_search';
	// });
  
	$('#choose_date').click(function(){
		//$('#hotel-info-box').hide();
		$('#J_Calendar').show();
	});

	//日历 start
	
	YUI({
		modules: {
			'price-calendar': {
				fullpath: public_path+'/trip-calendar/price-calendar.js',
				type    : 'js',
				requires: ['price-calendar-css']
			},
			'price-calendar-css': {
				fullpath: public_path+'/trip-calendar/price-calendar.css',
				type    : 'css'
			}
		}
	}).use('price-calendar', function(Y) {
		
		/**
		 * 非弹出式日历实例
		 * 直接将日历插入到页面指定容器内
		 */
		 if($.cookie('dep_date')!='' && $.cookie('dep_date')!=null){
			var depDate_ =$.cookie('dep_date');
			var endDate_ = $.cookie('end_date');
		 }else{
			var now = new Date();
			var tomorrow = new Date();  
			//设置第二天
			tomorrow.setDate(tomorrow.getDate()+1)
			var depDate_ = now.getFullYear() + '-' + Appendzero(now.getMonth() + 1) + '-' + Appendzero(now.getDate());
			var endDate_ = tomorrow.getFullYear() + '-' + Appendzero(tomorrow.getMonth() + 1) + '-' + Appendzero(tomorrow.getDate()); 

		 }
		
		oCal = new Y.PriceCalendar({
			container   : '#J_Calendar' //非弹出式日历时指定的容器（必选）
			// ,selectedDate: new Date       //指定日历选择的日期
			,count		: 3
			,afterDays	: 180
			,depDate	: depDate_
			,endDate	: endDate_
		});
		changeTime();
		
		$('.price-calendar-bounding-box table td').click(function(){
			if($(this).hasClass('disabled')){
				return false;
			}else{
				if(($('.dep-date').size() > 0 && $('.end-date').size() > 0) || ($('.dep-date').size() == 0 && $('.end-date').size() == 0)){
					$('.dep-date').find('.mark').empty();
					$('.dep-date').removeClass('dep-date');
					$('.end-date').find('.mark').empty();
					$('.end-date').removeClass('end-date');
					oCal.set('endDate','');
					
					$('.selected-range').removeClass('selected-range');
					
					oCal.set('depDate',$(this).data('date'));
					$(this).addClass('dep-date').find('.mark').html('入住');
				}else if(oCal.get('depDate')){
					var nowTmpdate = $(this).data('date').replace(/-/g,'');
					var prevTmpdate = oCal.get('depDate').replace(/-/g,'');
				
					if(nowTmpdate < prevTmpdate){
						$('.dep-date').find('.mark').empty();
						$('.dep-date').removeClass('dep-date');
						oCal.set('depDate',$(this).data('date'));
						$(this).addClass('dep-date').find('.mark').html('入住');
					}else{
						var tmp_dep_data = $(this).attr('class');
						if(tmp_dep_data=='dep-date'){
							alert('不能选同一天'); 
						}else{
							oCal.set('endDate',$(this).data('date'));
							
							var depTmpdate = parseInt(oCal.get('depDate').replace(/-/g,''));
							var endTmpdate = parseInt(oCal.get('endDate').replace(/-/g,''));
							$(this).addClass('end-date').find('.mark').html('离店');
							for(var i = depTmpdate+1;i<endTmpdate;i++){
								var tmpI = i.toString();
								var tmpDate = tmpI.substr(0,4)+'-'+tmpI.substr(4,2)+'-'+tmpI.substr(6,2);
								$('td[data-date="'+tmpDate+'"]').addClass('selected-range');
							}
					
							setTimeout(function(){
								changeTime()
							},300);
						}
					}
				}
			}
		});
		
		changeTime();
		
	
	});
	
	//日历结束
})(document, window);

function changeTime(){
	var dep_time = $('.dep-date').data('date');
	var end_time = $('.end-date').data('date');
	console.log(dep_time)
	console.log(end_time)
	$('.startweek').html('周'+week[oCal._toDate(dep_time).getDay()]+'入住');
	$('.endweek').html('周'+week[oCal._toDate(end_time).getDay()]+'离店');
	var aDate_start  = dep_time.split("-")  
	var start_date_md = aDate_start[1]  +  '月'  +  aDate_start[2]+'日';
	var oDate1  =  new  Date(aDate_start[0]  +  '-'  +  aDate_start[1]  +  '-'  +  aDate_start[2]) 
	var aDate_end  =  end_time.split("-")  
	var end_date_md = aDate_end[1]  +  '月'  +  aDate_end[2]+'日';
	var oDate2  =  new  Date(aDate_end[0]  +  '-'  +  aDate_end[1]  +  '-'  +  aDate_end[2])  
	var iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)  ;
	
	
	$.cookie('dep_time',aDate_start[0]+aDate_start[1]+aDate_start[2])
	$.cookie('end_time',aDate_end[0]+aDate_end[1]+aDate_end[2])
	$.cookie('dep_date',dep_time)
	$.cookie('end_date',end_time)
	$('.date_days').html('共'+iDays+'晚')
	$('.startdate').html(start_date_md)
	$('.enddate').html(end_date_md);
	$('#J_Calendar').hide();
}

//zero
function Appendzero(obj)  
{  
	if(obj<10) return "0" +""+ obj;  
	else return obj;  
}  


$(function(){
	getUserLocation({okFunction:'geoconvPlace',useHistory:false});
});

function geoconvPlace(userLongLat,lng,lat,is_baidu){
  if(is_baidu){
    var result = {
      result:[
        {
          x:lng,
          y:lat
        }
      ]
    }
    getStoreListBefore(result);
  }else{
    geoconv('getStoreListBefore',lng,lat);
  }
}

function showlocation(obj){
	
	if(obj.result.pois.length > 0){
		$.cookie('userLocationName',obj.result.pois[0].name,{expires:700,path:'/'});
		now_area_name = $.cookie('userLocationName')
		
	}else{
		$.cookie('userLocationName',obj.result.addressComponent.street,{expires:700,path:'/'});
		now_area_name = $.cookie('userLocationName')
	}
	now_city_name = obj.result.addressComponent.city;
	

	if(now_select_city_name==now_city_name.replace('市','')){
	
		now_area_name = $.cookie('userLocationName');
		$('.now_city').html($.cookie('userLocationName'))
	}
}

function getStoreListBefore(result){
	geocoder('showlocation',result.result[0].x,result.result[0].y);
	gethotel_around(result.result[0].x,result.result[0].y);
}

function gethotel_around(lng,lat){
	$.post(window.location.pathname+'?c=Hotel&a=ajax_hotel_around',{lng:lng,lat:lat},function(result){
		if(result.length > 0){
			var listHtml = '';
			
				laytpl($('#HotelListTpl').html()).render(result, function(html){
					
					$('.hearby_hotel .mui-card').append(html);
					
				});
			
		}
		
	},'json');
}