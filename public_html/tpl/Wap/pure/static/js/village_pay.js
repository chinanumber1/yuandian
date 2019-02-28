var myScroll;
var isApp = motify.checkApp();
$(function(){
	$('#backBtn').click(function(){
		window.history.go(-1);
	});
	if(pay_money > 10000){
		$('#recharge_money').val('￥10000');
		layer.open({content:'单次缴费金额最高不能超过1万元！<br/>您当前需缴费 ￥'+pay_money+'<br/>本次缴费金额已修改为1万元，请分多次缴费',shadeClose:false,btn: ['确定']});
	}
	$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	if(isApp){
        $('#container').css({'top':'0px'});
    }else{
        myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
    }
	$('#recharge_btn').click(function(){
		var postData = {};
		if(pay_type == 'custom'){
			$('#recharge_txt').val($.trim($('#recharge_txt').val()));
			postData.txt  = $('#recharge_txt').val();
			if(postData.txt == ''){
				motify.log('请填写缴费事项');
				return false;
			}
			$('#recharge_money').val($.trim($('#recharge_money').val()));
			postData.money  = parseFloat($('#recharge_money').val());
			console.log(postData.money);
			if(isNaN(postData.money)){
				motify.log('请填写正确的缴费金额');
				return false;
			}else if(postData.money > 10000){
				motify.log('单次缴费金额最高不能超过1万元');
				return false;
			}else if(postData.money < 0.1){
				motify.log('单次缴费金额最低不能低于 0.1 元');
				return false;
			}

		}else{
			if(pay_money == 0){
				motify.log('您当前不需要缴费');
				return false;
			}
			postData.txt = '';
			postData.money = $('#recharge_money').val();
		}
		if(postData.txt != ''){
			layer.open({title:['请确认','background-color:#06c1ae;color:#fff;'],content:'缴费事项：'+postData.txt+'<br/>缴费金额： '+postData.money,shadeClose:false,btn: ['确定','取消'],yes:function(){
				submitPost(postData);
			}});
		}else{
			submitPost(postData);
		}
	});
});
function submitPost(postData){
	layer.closeAll();
	layer.open({type: 2,content: '提交中，请稍等',shadeClose:false});
	$.post(window.location.href,postData,function(result){
		layer.closeAll();
		if(result.err_code == 1){
			pageLoadTip('跳转支付中..');
			window.location.href = result.order_url;
		}else{
			motify.log(result.err_msg);
		}
	});
}