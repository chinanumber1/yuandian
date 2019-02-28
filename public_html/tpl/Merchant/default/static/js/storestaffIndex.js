setInterval(function(){
	$.post("/store.php?g=Merchant&c=Store&a=ping");
},60000);
function checkApp(){
	if(/(pigcms_pack_app)/.test(navigator.userAgent.toLowerCase())){
		return true;
	}else{
		return false;
	}
}
function checkAndroid(){
	if(/(android)/.test(navigator.userAgent.toLowerCase())){
		return true;
	}else{
		return false;
	}
}
function checkAndroidApp(){
	if(checkApp() && checkAndroid()){
		return true;
	}else{
		return false;
	}
}
function iosFunction(string){
	$('body').append('<iframe id="iosIframe" src="pigcmspackapp://'+string+'"></iframe>');
	setTimeout(function(){
		$('#iosIframe').remove();
	},50);
}
function getDeviceId(){
	if(checkApp()){
		var reg = /device_id=(.*?),/;
		var arr = reg.exec(navigator.userAgent.toLowerCase());
		if(arr == null){
			return 'packapp';
		}else{
			return arr[1];
		}
	}else{
		return 'packapp';
	}
}

var print_mcode = '';
var print_paper = '';
var print_image = '';
var print_mkey = '';
function get_printer(arg1,arg2,arg3){
	if(arg1 != ''){
		$('.printer').show();
		print_mcode = arg1;
		print_paper = arg2;
		print_image = arg3;
		print_mkey  = getDeviceId();
		$.post('/store.php?c=Store&a=get_print_has',{mkey:print_mkey},function(result){
			if(result.status == 1){
				setInterval(function(){
					$.post('/store.php?c=Store&a=own_print_work',{mkey:print_mkey},function(opResult){
						if(opResult.status == 1 && opResult.info != ''){
							window.pigcmspackapp.printer_work(opResult.info,'');
						}
					});
				},3000);
			}
		});
	}
}
$(function(){
	
	if(checkAndroidApp()){
		window.pigcmspackapp.get_printer('get_printer');
	}
	
	$('.pageBg').css('height',$(window).height()*0.35);
	$('.infoBox').css('top',$(window).height()*0.35-90);
	
	$('.pageLink li').click(function(){
		if($(this).hasClass('printer')){
			var content = '<div style="letter-spacing:2px;font-size:16px;">';
				content+= '<div>终&nbsp;端&nbsp;号：'+ print_mcode+'</div>';
				content+= '<div>密&nbsp;&nbsp;&nbsp;&nbsp;钥：'+ print_mkey+'</div>';
				content+= '<div>纸张类型：'+ print_paper+'mm</div>';
				content+= '<div>支持图片：'+ (print_image == '1' ? '支持' : '不支持')+'</div>';
				content+= '<div>&nbsp;</div>';
				content+= '<div style="font-size:12px;">添加打印机后，请重新启动本软件。</div>';
				content+= '</div>';
			layer.open({
				title:'打印机参数',
				content:content,
				btn:[]
			});  
			return false;
		}
		
		var href = $(this).data('url');
		if($(this).data('confirm')){
			if(confirm($(this).data('confirm'))){
				location.href = href;
			}
		}else{
			if(checkApp()){
				if(checkAndroidApp()){
					window.pigcmspackapp.createwebview(href);
				}else{
					iosFunction('createwebview/'+window.btoa(href));
				}
			}else{
				location.href = href;
			}
		}
	});
});