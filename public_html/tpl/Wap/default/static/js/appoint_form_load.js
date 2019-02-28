var motify = {
	timer:null,
	log:function(msg){
		$('.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		motify.timer = setTimeout(function(){
			$('.motify').hide();
		},3000);
	}
};
var map = null;
var marker = null;
var mapObjDom = null;
$(function(){
	
	$('section').css('min-height','100%');
	
	$('.comm-service').click(function(){
		if($('#service-type .service-list li').size() > 1){
			$('#service-type').show();
			$('#main').hide();
		}
	});
	
	$('#service-type .service-list li').click(function(){
		$(this).addClass('active').siblings('li').removeClass('active');
		$('.con-service-inner').html($(this).find('h3[data-role="title"]').html()+'：'+$(this).find('span[data-role="content"]').html());
		$('.comm-service span span').html($(this).find('span[data-role="payAmount"]').html());
		$('#service_type').val($(this).data('id'));
		$('#service-type').hide();
		$('#main').show();
		$(window).scrollTop(0);
	});
	
	$('.arrow-wrapper').click(function(){
		closeWin();
	});
	
	$('.yxc-time-con dt[data-role="date"]').click(function(){
		$('.yxc-time-con dt[data-role="date"]').removeClass('active');
		$(this).addClass('active');
			$('.date-'+$(this).data('date')).show().siblings('div').hide();
		if(time_gap!=-1){		
			
		}else{
			$('#serviceJobTime').val($('.yxc-time-con dt[data-role="date"].active').data('text'));
			$('#service_date').val($('.yxc-time-con dt[data-role="date"].active').data('date'));
			$('#service_time').val($(this).data('date'));
			closeWin();
		}
	});
	
	$('.yxc-time-con dd[data-role="item"]').click(function(){
		if(!$(this).hasClass('disable')){
			$('.yxc-time-con dd[data-role="item"]').removeClass('active');
			$(this).addClass('active');
			$('#serviceJobTime').val($('.yxc-time-con dt[data-role="date"].active').data('text') + ' ' +$(this).data('peroid'));
			$('#service_date').val($('.yxc-time-con dt[data-role="date"].active').data('date'));
			$('#service_time').val($(this).data('peroid'));
			closeWin();
		}
	});
	
	$('textarea.ipt-attr').focus(function(){
		$(this).css('height','60px');
	}).blur(function(){
		if($(this).val() == ''){
			$(this).css('height','24px');
		}
	});
	
	$('.select select').change(function(){
		if($(this).val() != ''){
			$(this).css('color','black');
		}else{
			$(this).css('color','#999');
		}
	});
	
	$('input[data-role="position"]').click(function(){
		$('#service-position').css({'z-index':'1111','opacity':1}).show();
		$('#main').hide();
		if(map == null){
			// $('#allmap').height($(window).height()-41);
		}
		selectPosition($(this));
	});
	$('input[data-role="position-desc"]').keyup(function(){
		$(this).closest('li').find('input[data-type="address"]').val($(this).closest('li').find('input[data-role="position"]').val()+' '+$(this).val());
	});
	
	$("#se-input-wd").bind('input',function(e){
		var address = $.trim($('#se-input-wd').val());
		if(address.length>0 && address !== '直接输入定位您的地址'){
			$('#addressShow').empty();
			$.get('/index.php?g=Index&c=Map&a=suggestion', {query:user_city+address}, function(data){
				if(data.status == 1){
					getAdress(data.result);
				} else {
					//alert(data.result);return false;
				}
			});
		}
	});
	
	$('#addressShow').delegate("li","click",function(){ 
		var addressName = $(this).attr("address");
		var addressLongitude = $(this).attr("lng");
		var addressLatitude = $(this).attr("lat");
		var addressSugAddress = $(this).attr("sug_address");
		layer.open({
			title:['位置提示','background-color:#8DCE16;color:#fff;'],
			content:'您选择的位置是：'+addressName,
			btn: ['确定位置','重新选择'],
			yes:function(index){
				mapObjDom.closest('li').find('input[data-type="long"]').val(addressLongitude);
				mapObjDom.closest('li').find('input[data-type="lat"]').val(addressLatitude);
				mapObjDom.closest('li').find('input[data-type="address"]').val(addressName);
				mapObjDom.data({'long':addressLongitude,'lat':addressLatitude,'address':addressName}).val(addressSugAddress);
				layer.close(index);
				closeWin();
			}
		});
}); 

	
	$('.bt-sub-order').click(function(){
		var nowDom = $(this);
		if($('#store_id').val() == ''){
			$(window).scrollTop($('#store_id').offset().top-20);
			motify.log('请选择预约店铺');
			return false;
		}
		
		if($('#merchant_workers_id').val() == ''){
			$(window).scrollTop($('#merchant_workers_id').offset().top-20);
			motify.log('请选择预约技师');
			return false;
		}
		
		if($('#service_date').val() == ''){
			$(window).scrollTop($('#serviceJobTime').offset().top-20);
			motify.log('请选择预约时间');
			return false;
		}
		var slA = $('#main_form').serializeArray();
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
					}else if(tmpDom.data('role') == 'date'  && slA[i].value == '' ){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'time' && slA[i].value == ''){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'select' && slA[i].value == ''){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'email'  && !/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(slA[i].value)){
						formError(tmpDom);
						return false;
					}
				}
			}
		}
		
		nowDom.addClass('disabled').html('提交中...');

		return false;
	});
});

function formError(tmpDom){
	$('.form_error').removeClass('form_error');
	motify.log(tmpDom.attr('placeholder'));
	$(window).scrollTop(tmpDom.offset().top-20);
	tmpDom.closest('li').addClass('form_error');
}

var map = null;
var marker = null;
function selectPosition(objDom){
	$('.se-input-wd').val('');
	$('#addressShow').empty();
	mapObjDom = objDom;
	// 百度地图API功能
	if(objDom.data('long')){
		// setTimeout(function(){
			map.centerAndZoom(new BMap.Point(objDom.data('long'),objDom.data('lat')), 16);
		// },300);
	}else{
		map = new BMap.Map("allmap",{enableMapClick:false});
		if(user_long == 0){
			map.centerAndZoom(user_city, 16);  
		}else{
			map.centerAndZoom(new BMap.Point(user_long,user_lat), 16);
		}	
		// map.addControl(new BMap.ZoomControl()); //添加地图缩放控件
		
		map.addEventListener("dragend", function(e){
			$('#addressShow').empty();
			var centerMap = map.getCenter();
			getPositionInfo(centerMap.lat,centerMap.lng);
		});
		
		map.addEventListener("load", function(e){
			var centerMap = map.getCenter();
			getPositionInfo(centerMap.lat,centerMap.lng);
		});
	}
}

function getPositionInfo(lat,lng){
	$.getJSON('https://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&callback=renderReverse&location='+lat+','+lng+'&output=json&pois=1&callback=getPositionAdress&json=?');
}

function getPositionAdress(result){
	if(result.status == 0){
		result = result.result;
		var re = [];
		re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
		for(var i in result.pois){
			re.push({'name':result.pois[i].name,'address':result.pois[i].addr,'long':result.pois[i].point.x,'lat':result.pois[i].point.y});
		}
		getAdress(re);
	}else{
		alert('获取位置失败！');
	}
}
function getAdress(re){
	var addressHtml = '';
	for(var i=0;i<re.length;i++){
		addressHtml += '<li lng="'+re[i]['long']+'" lat="'+re[i]['lat']+'" sug_address="'+re[i]['name']+'" address="'+re[i]['address']+'">';
		addressHtml += '<div class="mapaddress-title"><span class="icon-location" data-node="icon"></span><span class="recommend">'+(i == 0 ? '[推荐位置]' : '')+''+re[i]['name']+'</span></div>';
		addressHtml += '<div class="mapaddress-body">'+re[i]['address']+'</div>';
		addressHtml += '</li>';
	}
	$('#addressShow').append(addressHtml);
}

function closeWin(){
	$('#service-type').hide();
	$('#service-date').hide();
	$('#service-position').css({'z-index':'-999','opacity':0});
	$('#main').css('z-index','0').show();
}



/*$('.yxc-attr-list').first().change(function(){
	var store_id=$('#store_id').val();
	$.post(ajaxWorkUrl,{'merchant_store_id':store_id,'appoint_id':appoint_id},function(data){
		$('.worker-list').remove();
		var str='';
		if(data.status){
			str+='<ul class="yxc-attr-list worker-list"><li data-role="chooseStore"><i class="icon-store"></i><p class="cover select"><select onchange="getWorkerTime($(this))" name="merchant_workers_id" id="merchant_workers_id" class="ipt-attr" style="color: black;"><option value="0">选择技师</option>';
			for(var i in data.worker_list){
				str+='<option value="'+data.worker_list[i]["merchant_worker_id"]+'">'+data.worker_list[i]["name"]+'</option>';
			}
			str+='</select></p></li></ul><div class="yxc-space worker-list"></div>';
			$('#store_id').parents('.yxc-attr-list').next('.yxc-space').after(str);
		}else{
			//$('.appoint-time').hide();
			getAppointTime();
		}
	},'json')
});*/



function getAppointTime(){
	$.post(ajaxAppointTimeUrl,{'appoint_id':appoint_id},function(data){
		var html='';
		if(data.status){
			
			function show(){
			   var mydate = new Date();
			   var str = "" + mydate.getFullYear() + "-";
			   str += ((mydate.getMonth() + 1)>10 ? (mydate.getMonth() + 1) : '0'+(mydate.getMonth() + 1)) + "-";
			   str += parseInt(mydate.getDate())>10 ? mydate.getDate() : '0' + mydate.getDate();
			   return str;
			 }
			  
			  function DateDiff(sDate1, sDate2) {  //sDate1和sDate2是yyyy-MM-dd格式
				var aDate, oDate1, oDate2, iDays;
				aDate = sDate1.split("-");
				oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);  //转换为yyyy-MM-dd格式
				aDate = sDate2.split("-");
				oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);
				iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
			 
				return iDays;  //返回相差天数
			}
			  var currentDate=show();
			html+='<section id="service-date" style="min-height: 100%; display:none"><div class="yxc-pay-main yxc-payment-bg pad-bot-comm"><header class="yxc-brand"><a class="arrow-wrapper" data-role="cancel"><i class="bt-brand-back"></i></a><span>选择预约时间</span></header><div class="yxc-time-con number-4">';
			for(var i in data.timeOrder){
				html+='<dl><dt data-role="date"';

				if(currentDate==i){
					html+='data-text="今天" class="active"';
				}else if(DateDiff(i,currentDate)==1){
					html+='data-text="明天"';
				}else if(DateDiff(i,currentDate)==2){
					html+='data-text="后天"';
				}else{
					html+='data-text="'+i+'"';
				}
				html+=' data-date="'+i+'">';
				if(currentDate==i){
					html+='今天';
				}else if(DateDiff(i,currentDate)==1){
					html+='明天';
				}else if(DateDiff(i,currentDate)==2){
					html+='后天';
				}else{
					if(time_gap!=-1){
						html+=i;
					}
				}
				html+='<span>'+i+'</span></dt></dl>'
			}
			html+='</div><div class="yxc-time-con" data-role="timeline">';
			if(time_gap!=-1){		
				for(var i in data.timeOrder){
					html+='<div class="date-'+i+' timeline"';
					if(currentDate!=i){
						html+='style="display:none"';
					}
					html+='>';
					for(var j in data.timeOrder[i]){
						html+='<dl><dd data-role="item" data-peroid="'+data.timeOrder[i][j]["start"]+'"';
						if(data.timeOrder[i][j]["order"]=='no' || data.timeOrder[i][j]["order"]=='all'){
							html+='class="disable"';
						}
						html+='>'+data.timeOrder[i][j]["start"]+'<br>';
						
						if(data.timeOrder[i][j]["order"]=='no'){
							html+='不可预约';
						}else if(data.timeOrder[i][j]["order"]=='all'){
							html+='已约满';
						}else{
							html+='可预约';
						}
						
						html+='</dd></dl>';
					}
					html+='</div>';
				}
			}
			$('.appoint-time').show();
			$('#service-type').after(html);
			$.getScript(jqueryUrl);
			$.getScript(layUrl);
			$.getScript(appointFormLoadUrl);
		}else{
			$('.appoint-time').hide();
		}
	},'json')
}


function getWorkerTime(worker_id){
	// var worker_id=obj.val();
	// if(worker_id == 0){
		// $('.appoint-time').hide();
		// return;
	// }
	if(worker_id){
		$('input[name="merchant_workers_id"]').val(worker_id);
		$.post(ajaxWorkerTimeUrl,{'worker_id':worker_id,'appoint_id':appoint_id},function(data){
		var html='';
		if(data.status){
			function show(){
			   var mydate = new Date();
			   var str = "" + mydate.getFullYear() + "-";
			   str += ((mydate.getMonth() + 1)>10 ? (mydate.getMonth() + 1) : '0'+(mydate.getMonth() + 1)) + "-";
			   str += parseInt(mydate.getDate())>10 ? mydate.getDate() : '0' + mydate.getDate();
			   return str;
			 }
			  
			  function DateDiff(sDate1, sDate2) {  //sDate1和sDate2是yyyy-MM-dd格式
				var aDate, oDate1, oDate2, iDays;
				aDate = sDate1.split("-");
				oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);  //转换为yyyy-MM-dd格式
				aDate = sDate2.split("-");
				oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);
				iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
			 
				return iDays;  //返回相差天数
			}
			  var currentDate=show();
			html+='<section id="service-date" style="min-height: 100%; display:none"><div class="yxc-pay-main yxc-payment-bg pad-bot-comm"><header class="yxc-brand"><a class="arrow-wrapper" data-role="cancel"><i class="bt-brand-back"></i></a><span>选择预约时间</span></header><div class="yxc-time-con number-4">';
			for(var i in data.timeOrder){
				html+='<dl><dt data-role="date"';
				if(currentDate==i){
					html+='data-text="今天" class="active"';
				}else if(DateDiff(i,currentDate)==1){
					html+='data-text="明天"';
				}else if(DateDiff(i,currentDate)==2){
					html+='data-text="后天"';
				}else{
					html+='data-text="'+i+'"';
				}
				html+=' data-date="'+i+'">';
				if(currentDate==i){
					html+='今天';
				}else if(DateDiff(i,currentDate)==1){
					html+='明天';
				}else if(DateDiff(i,currentDate)==2){
					html+='后天';
				}else{
					if(time_gap!=-1){
						html+=i;
					}
				}
				html+='<span>'+i+'</span></dt></dl>'
			}
			html+='</div><div class="yxc-time-con" data-role="timeline">';
			if(time_gap!=-1){
				for(var i in data.timeOrder){
					html+='<div class="date-'+i+' timeline"';
					if(currentDate!=i){
						html+='style="display:none"';
					}
					html+='>';
					for(var j in data.timeOrder[i]){
						html+='<dl><dd data-role="item" data-peroid="'+data.timeOrder[i][j]["start"]+'"';
						if(data.timeOrder[i][j]["order"]=='no' || data.timeOrder[i][j]["order"]=='all'){
							html+='class="disable"';
						}
						html+='>'+data.timeOrder[i][j]["start"]+'<br>';
						
						if(data.timeOrder[i][j]["order"]=='no'){
							html+='不可预约';
						}else if(data.timeOrder[i][j]["order"]=='all'){
							html+='已约满';
						}else{
							html+='可预约';
						}
						
						html+='</dd></dl>';
					}
					html+='</div>';
				}
			}
			$('.appoint-time').show();
		}else{
			$('.appoint-time').hide();
		}
		$('#service-type').after(html);
		$.getScript(jqueryUrl);
			$.getScript(layUrl);
			$.getScript(appointFormLoadUrl);
	},'json');
	}else{
		serviceJobTime();
	}
	
}





  /**
   * 将JS的任意对象输出为json格式字符串
   * @param {Object} _obj: 需要输出为string的对象
   */
  var obj2String = function(_obj) {
    var t = typeof (_obj);
    if (t != 'object' || _obj === null) {
      // simple data type
      if (t == 'string') {
        _obj = '"' + _obj + '"';
      }
      return String(_obj);
    } else {
      if ( _obj instanceof Date) {
        return _obj.toLocaleString();
      }
      // recurse array or object
      var n, v, json = [], arr = (_obj && _obj.constructor == Array);
      for (n in _obj) {
        v = _obj[n];
        t = typeof (v);
        if (t == 'string') {
          v = '"' + v + '"';
        } else if (t == "object" && v !== null) {
          v = this.obj2String(v);
        }
        json.push(( arr ? '' : '"' + n + '":') + String(v));
      }
      return ( arr ? '[' : '{') + String(json) + ( arr ? ']' : '}');
    }
  };
  var obj = {
    "result" : {
      "fs" : {
        "TSP.IBR.MIRROR" : [{
          "_value" : "1.0",
          "_class" : 4
        }],
        "TSP.IBR.GET_FNAMES" : [{
          "_value" : "0.0",
          "_class" : 4
        }],
        "TSP.IBR.GET_TOKEN_ID" : [{
          "_value" : "0.0",
          "_class" : 4
        }],
        "TSP.IBR.INFO" : [{
          "_value" : "0.0",
          "_class" : 4
        }]
      }
    },
    "isCanceled" : false,
    "e" : "",
    "isResponsed" : true,
    "aoqSize" : 0,
    "isAsyncPost" : false,
    "code" : 0,
    "reqUID" : "xxxx-xxxxxx-xxxxx-6c2f17bb-ea18-42ec-98fa-3f63b8d26aba-nd-rq",
    "version" : "1.0",
    "fName" : "TSP.IBR.GET_FNAMES",
    "message" : "成功获取 4 个功能",
    "dir" : "DOWN",
    "nodeTime" : 1362462128706,
    "isKeyCompressed" : false,
    "seq" : 2
  }
