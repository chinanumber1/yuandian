Date.prototype.format = function(format) {
    var o = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S": this.getMilliseconds()
    }
    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}
function setCode(d){
	var imgsrc=window['SiteUrl']+'image.aspx?action=codeimg&id='+d.imgcodeid+'&width=240';
	$('#authcode_btn').css({'background':'url('+imgsrc+') no-repeat 0 0'});
	$('#authcode_txt').html(d.code);
	$('#imgcodeid').val(d.imgcodeid);
}
function getCode(){
	var btn = $('#authcode_btn');
	btn.find('li').bind('click',function(){
		var val = $(this).attr('data-val');
		checkCode(btn,val);
	});
	$('#codeindex').val('');
	btn.removeClass('select');
	
	var url=window['SiteUrl']+'image.aspx?action=getcodeimg&jsoncallback=?';
	var  Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	$.getJSON(url,function(data){
		if(data[0].islogin==='1'){
			setCode(data[0].MSG);
		}else{
			alert(data[0].error);
		}
	});
	return false;
}
function checkCode(node,val){
	var url=window['SiteUrl']+'image.aspx?action=iscodeimg&jsoncallback=?&jsonxml={"codeindex":"'+val+'","imgcodeid":"'+$('#imgcodeid').val()+'","tracesdata":[{"x":"223","y":"221","date":"'+(new Date()).format('yyyy-MM-dd hh:mm:ss')+'"}]}';
	var  Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	$.getJSON(url,function(data){
		if(data[0].islogin==='1'){
			node.addClass('select').find('li').unbind('click');
			$('#codeindex').val(val);
			$('#chkcode').hide();
		}else{
			if(typeof data[0].error !== 'undefined'){
				MSGwindowShow('authcode','0',data[0].error,'','');
			}else{
				setCode(data[0].MSG);
			}
		}
	});
}
$.fn.iAuthcode = function(){
	getCode();
}