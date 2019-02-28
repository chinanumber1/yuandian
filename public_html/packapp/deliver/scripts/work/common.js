//当前业务名称
var visitWork = 'deliver';

var workColor = '#1b9dff';

//非微信隐藏头部
$(function(){
	if(!common.checkApp()){
		$('#fixed_top').remove();
	}
});