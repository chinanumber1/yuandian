mui.init();
var name1='',address1='',ptype=1;
var character='';
var forms=common.getCache('forms');
var print_mobile_code='';
if(forms){
	$('.change').hide();
	var store_idAll=common.getCache('store_idAll');
	$('#store_id').val(store_idAll);
}else{
	$('.change').show();
}
//获取手机唯一标示
 if(common.checkIosApp()){
 	common.iosFunction('iOS_Device_UUID');
 }
 //客户端返回唯一标示
  function back_device_uuid (res){
       print_mobile_code=res;
  }
//筛选
(function($,doc){
	$.init();
	$.ready(function(){
		common.http('Merchantapp&a=merchant_store',{}, function(data){
			var store_list = [];
			if(data.length > 0){
				for(var i in data){
					store_list.push({value:data[i].store_id,text:data[i].name});
				}
			}
			var userPicker = new $.PopPicker();
			userPicker.setData(store_list);
			var showUserPickerButton = doc.getElementById('showUserPicker');
			showUserPickerButton.addEventListener('tap', function(event) {
				userPicker.show(function(items){
					doc.getElementById('shop_text').innerHTML = items[0].text;
					doc.getElementById('store_id').value = items[0].value;
				});
			}, false);
		});
	});
})(mui, document);
var printerList=[];

if(common.checkApp()){
	if(common.checkAndroidApp()|| common.checkIos()){
		printerList=[{"value":"1","text":"无线打印机"},{"value":"2","text":"本地打印机"},{"value":"3","text":"蓝牙打印机"}];
	}
}else{
	printerList=[{"value":"1","text":"无线打印机"},{"value":"2","text":"本地打印机"}];
}





///筛选打印机类型
(function($,doc){
	$.init();
	mui('.mui-content').on('tap','#printerClass',function(e){
		document.activeElement.blur(); 
		var userPicker = new $.PopPicker();
		userPicker.setData(printerList);
		userPicker.show(function(items){
			doc.getElementById('printerClass_text').innerHTML = items[0].text;
			ptype=items[0].value;
			if(items[0].value==1){
				document.getElementById('alls').style.display="block";
				document.getElementById('bettly').style.display="none";
				document.getElementById('items1').style.display="block";
				document.getElementById('mkey').value='';
			}else if(items[0].value==2){
				document.getElementById('items1').style.display="none";
				document.getElementById('bettly').style.display="none";
				document.getElementById('mkey').value='';
			}else if(items[0].value==3){
				document.getElementById('alls').style.display="none";
				document.getElementById('bettly').style.display="block";
				document.getElementById('mcode').value='600006';
				getCharacter();
			}
		});
			
	});
})(mui, document);


function getCharacter(){ 
	character='';
	for(var i=0;i<16;i++){
		character+= String.fromCharCode(Math.floor(Math.random()*26)+"A".charCodeAt(0)); 
	}
	$('#mkey').val(character);
 	console.log(character); 
}





mui('.mui-content').on('tap','.checkbox_change',function(e){
	document.activeElement.blur(); 
	$(this).is('.active') ? $(this).removeClass('active') : $(this).addClass('active');
});


$(function(){
	$('#add_button').click(function(){

		var postData = {};
		postData.status = 1;
		postData.store_id = $('#store_id').val();
		postData.name = $('#name').val();
		postData.mp = $('#mp').val();
		postData.username = $('#username').val();
		postData.mcode = $('#mcode').val();
		postData.mkey = $('#mkey').val();
		postData.count = $('#count').val();
		postData.is_main = $('.is_main').hasClass('mui-active') ? "1" : "0";
		postData.paper = $('input[name="paper"]').filter(':checked').val();
		postData.is_big = $('input[name="font_size"]').filter(':checked').val();
		postData.image = $('input[name="pic"]').filter(':checked').val();

		postData.print_type = ptype;
		postData.print_bluetooth_name = name1;
		postData.print_bluetooth_code=address1;
		postData.print_mobile_code=print_mobile_code;
		 
        if(common.checkIos()){
		  postData.device_type=2;
	     }else{
	     	  postData.device_type=1;
	     }
		var paidArr = [];
		$.each($('.checkbox_change.active'),function(i,item){
			paidArr.push($(item).data('paid'));
		});
		postData.paid = paidArr.join(',');
		if(ptype==1||ptype==2){
			if(postData.name == ''){
				mui.alert('请输入打印机名称');
			}else if(postData.mkey == ''){
				mui.alert('请输入密钥');
			}else if(postData.count <= 0){
				mui.alert('请输入打印份数');
			}else if(postData.store_id == '0'){
				mui.alert('请选择店铺');
			}else if(postData.paid == ''){
				mui.alert('请选择打印类型');
			}else{
				console.log(postData);
				common.http('Merchantapp&a=hardware_add',postData,function(data){
					mui.toast('保存成功');
					if(common.checkApp()){
						setTimeout(function(){
							if(common.checkAndroidApp()){
								window.pigcmspackapp.closewebview(2);
							}else{
								common.iosFunction('closewebview/2');
							}
						},2000);
					}else{
						setTimeout(function(){
							history.go(-1);
							document.execCommand('Refresh');
						},2000); 
					}			
				});
			}
		}else{
			if(postData.name == ''){
				mui.alert('请输入打印机名称');
			}else if(postData.count <= 0){
				mui.alert('请输入打印份数');
			}else if(postData.store_id == '0'){
				mui.alert('请选择店铺');
			}else if(postData.paid == ''){
				mui.alert('请选择打印类型');
			}else{
				if($('#printerClass_text').text()=="蓝牙打印机"&&name1==""){
					mui.toast('蓝牙打印机模式下必须选中蓝牙设备');
					return false;
				}else if($('#printerClass_text').text()=="蓝牙打印机"&&address1==""){
                    mui.toast('请重新选取蓝牙设备');
                    return false;
				}else{
					common.http('Merchantapp&a=hardware_add',postData,function(data){
                       
						mui.toast('保存成功');
						if(common.checkApp()){
							setTimeout(function(){
								if(common.checkAndroidApp()){
									window.pigcmspackapp.closewebview(2);
								}else{
									common.iosFunction('closewebview/2');
								}
							},2000);
						}else{
							setTimeout(function(){
								history.go(-1);
								document.execCommand('Refresh');
							},2000); 
						}	
						
					});
				}
			}
			
		}
	});
});

$('.mui-content').on('click','.phone_ti',function(e){
	e.stopPropagation();
	e.preventDefault();
	document.activeElement.blur(); 
	mui.alert('绑定手机号和绑定账号只能填写一个（【底部有终端号】需填写手机号，【底部无终端号】需填写绑定账号）');
});
$('.mui-content').on('click','.bang_ti',function(e){
	e.stopPropagation();
		e.preventDefault();
		document.activeElement.blur(); 
	mui.alert('绑定手机号和绑定账号只能填写一个（【底部有终端号】需填写手机号，【底部无终端号】需填写绑定账号）');
});
$('.mui-content').on('click','.zhong_ti',function(e){
	e.stopPropagation();
		e.preventDefault();
		document.activeElement.blur(); 
	mui.alert('【底部无终端号】的打印机点击打印机下面的黑色小按钮查看, 【底部有终端号】的打印机在打印机底部查看');
});
$('.mui-content').on('click','.mi_ti',function(e){
	e.stopPropagation();
		e.preventDefault();
		document.activeElement.blur(); 
	mui.alert('【底部无终端号】的打印机在注册页面查看, 【底部有终端号】的打印机在打印机底部查看');
});
$('.mui-content').on('click','.nums1',function(e){
	e.stopPropagation();
	e.preventDefault();
	document.activeElement.blur(); 
	mui.alert('每个订单打印几份（最多100）');
});


/*if(common.checkApp()){
	if(common.checkAndroidApp()){
		$('.lanShow').show();	
		//蓝牙打印机开关
		$('.checked1').click(function(e){
			window.pigcmspackapp.openBlueScanDialog();
		});
	}else{
		$('.lanShow').hide();
	}

}else{
	$('.lanShow').hide();
}*/

if(common.checkApp()){
    $('.lanShow').show();
    //蓝牙打印机开关
    $('.checked1').click(function(e){
        if(common.checkIos()){
            common.iosFunction('openBlueScanDialog');
        }else{
            window.pigcmspackapp.openBlueScanDialog();
        }
    });
}else{
    $('.lanShow').hide();
}
function  getChooseDeviceInfos(name,macAddress){
    address1='';
	name1=name;
	address1=macAddress;
	$('#bettly input').val(name1);
}
















