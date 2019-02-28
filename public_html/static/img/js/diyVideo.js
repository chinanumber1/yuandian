$(document).ready(function () {
	//var isclient = false;
//	try{
//		if(window.external == null || typeof(window.external.JsCrossDomain) == "undefined"){
//			$("body").css("background", "#f0f2f4");
//			$(".website").show();
//			$(".mediaclient").hide();
//		}else{
//			isclient = true;
//		}
//	} catch (ex) {
//		//alert(ex.message);
//	}
//	if (!isclient) {
//		$("#preview_content").bind("click", function () {});
//	}
	//$(".element-item").bind("click", function () {
//		$(".element-item.selected").removeClass("selected");
//		$(this).addClass("selected");
//		$("#preview").show();
//		$("#preview_content").html("");
//		if(isclient){
//			$("#preview_content").html($(".content", this).html());
//		}else{
//			$("#preview_content").html("<br/>" + $(".content", this).html() + "<br/>");
//		}
//	});
	$("#preview_btok").bind("click",function(){
		var html = $("#preview_content").val();
		if(html.indexOf("iframe") <= 0){
			alert('您输入的链接框架不对，请以<iframe开头');
			return false;
		}
		if(html.indexOf("https://v.qq.com") <= 0){
			alert('您输入的不是腾讯视频，无法在微信里播放，请更换。');
			return false;
		}
		html	=	html.replace(/width="\d+"/, 'width="100%"');
		html	=	html.replace(/height="\d+"/, 'height="100%"');
		html	=	html.replace(/<iframe/, '<iframe class="content_video"');
		//返回数据到主框架
		var origin      = artDialog.open.origin;
		//接收iframe数据
		var iframeData = {
			'editer'      : art.dialog.data('editer'),
		};
		if(html != ''){
			iframeData.editer.insertHtml(html);
			art.dialog.close();
		}else{
			alert('您还没有输入网址');
			return false;
		}
	});
	//function colorSelected(color){
//		var colorattr = $(".element-item.selected").attr("data-color");
//		if (colorattr == undefined || colorattr == ""){
//			return;
//		}
//		var cas = colorattr.split(';');
//		for (var i = 0; i < cas.length; i++){
//			var ca = cas[i];
//			if (ca == undefined || ca == ""){
//				continue;
//			}
//			var cai = ca.split(':');
//			if (cai.length < 2){
//				continue;
//			}
//			$('#preview_content ' + cai[0]).css(cai[1], color);
//		}
//	}
	//if($("#picker").length > 0){
//		$.farbtastic('#picker', colorSelected).setColor("#000000");
//	}
});